<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CompanyorganogramController extends Controller
{
    public function index(Request $request)
    {
        $Url = url('images');

        // logged-in user
        $sid = (int) Session::get('emp_id');

        // dashboard access (same as your code)
        $dsql = DB::table('a_other_dashboard_access_t')->where('user_id', $sid)->get();
        $das_access = [];
        if (count($dsql) > 0) {
            $das_access = json_decode($dsql[0]->dashboard_option, true) ?? [];
        }

        $emp_id = trim((string) $request->input('emp_search', ''));

        // Always build a safe root
        $companyNode = $this->emptyCompanyNode($Url);

        if ($emp_id === '') {
            // default: show self + direct reports + parent (if exists)
            $companyNode = $this->buildDefaultChart($sid, $Url);
        } elseif ($emp_id === '1') {
            // HO/Co chart
            $companyNode = $this->buildHoCoChart($Url);
        } elseif ($emp_id === '3') {
            // HO/Co chart
            $companyNode = $this->buildEastChart($Url);
        } elseif ($emp_id === '4') {
            // HO/Co chart
            $companyNode = $this->buildCWChart($Url);
        } elseif ($emp_id === '5') {
            // HO/Co chart
            $companyNode = $this->buildNorthChart($Url);
        } elseif ($emp_id === '6') {
            // HO/Co chart
            $companyNode = $this->buildSouth1Chart($Url);
        } elseif ($emp_id === '7') {
            // HO/Co chart
            $companyNode = $this->buildSouth2Chart($Url);
        } elseif ($emp_id === '8') {
            // HO/Co chart
            $companyNode = $this->buildSouth3Chart($Url);
        } elseif ($emp_id === '9') {
            // HO/Co chart
            $companyNode = $this->buildCorporateChart($Url);
        } else {
            // search by employee id
            $companyNode = $this->buildSearchChart((int) $emp_id, $Url);
        }

        
        
        // Store JSON in session for data endpoint
        Session::put('emp_chart_data', json_encode($companyNode, JSON_UNESCAPED_SLASHES));

        return view('organizationchart.chart', [
            'das_access' => $das_access
        ]);
    }

    /**
     * IMPORTANT: Return pure JSON (not view), because chart library expects JSON.
     */
    public function data()
    {
        $chartData = Session::get('emp_chart_data');

        // fallback safe node
        if (!$chartData) {
            $Url = url('images');
            return response()->json($this->emptyCompanyNode($Url));
        }

        // Return JSON as actual JSON (not string)
        $decoded = json_decode($chartData, true);
        if (!is_array($decoded)) {
            $Url = url('images');
            return response()->json($this->emptyCompanyNode($Url));
        }

        return response()->json($decoded);
    }

    // -----------------------------
    // Chart Builders
    // -----------------------------

    private function emptyCompanyNode(string $Url): array
    {
        return [
            'id' => 1,
            'name' => 'DR. JRK RESEARCH AND PHARMACEUTICALS PVT LTD',
            'positionName' => 'Company',
            'imageUrl' => "$Url/profile_images/logo.png",
            'profileUrl' => "$Url/profile_images/logo.png",
            'area' => '-',
            'place' => '-',
            'grade' => '-',
            'doj' => '-',
            'children' => [],
        ];
    }

    private function buildDefaultChart(int $sid, string $Url): array
    {
        // Fetch self + direct reports
        $employee_data = DB::select("
            SELECT
                e.employee_id as id,
                e.reporting_manager as pid,
                e.first_name as name,
                e.photo,
                a.area_name,
                e.date_of_joining,
                p.position as grade,
                d.sub_department_name as dept,
                j.job_title_name as title
            FROM hr_employee_t e
            LEFT JOIN m_position p ON e.position = p.position_id
            LEFT JOIN m_area_t a ON CAST(JSON_UNQUOTE(JSON_EXTRACT(e.med_rep_area, '$[0]')) AS SIGNED) = a.area_id
            LEFT JOIN m_department_lines_t d ON CAST(JSON_UNQUOTE(JSON_EXTRACT(e.department, '$[0]')) AS SIGNED) = d.department_line_id
            LEFT JOIN m_job_title j ON e.job_title = j.job_title_id
            WHERE e.active='yes'
              AND (e.employee_id = ? OR e.reporting_manager = ?)
        ", [$sid, $sid]);

        // Find self row
        $selfRow = null;
        $childrenRows = [];

        foreach ($employee_data as $row) {
            if ((int) $row->id === $sid) {
                $selfRow = $row;
            } elseif ((int) $row->pid === $sid) {
                $childrenRows[] = $row;
            }
        }

        // If self not found, return safe empty
        if (!$selfRow) {
            return $this->emptyCompanyNode($Url);
        }

        $selfNode = $this->makeNodeFromRow($selfRow, $Url);
        $selfNode['children'] = [];

        foreach ($childrenRows as $childRow) {
            $childNode = $this->makeNodeFromRow($childRow, $Url);
            $childNode['children'] = [];
            $selfNode['children'][] = $childNode;
        }

        // Now fetch manager (1 level up), if exists
        $managerId = (int) ($selfRow->pid ?? 0);
        if ($managerId > 0) {
            $managerRowArr = DB::select("
                SELECT
                    e.employee_id as id,
                    e.reporting_manager as pid,
                    e.first_name as name,
                    e.photo,
                    a.area_name,
                    e.date_of_joining,
                    p.position as grade,
                    d.sub_department_name as dept,
                    j.job_title_name as title
                FROM hr_employee_t e
                LEFT JOIN m_position p ON e.position = p.position_id
                LEFT JOIN m_area_t a ON CAST(JSON_UNQUOTE(JSON_EXTRACT(e.med_rep_area, '$[0]')) AS SIGNED) = a.area_id
                LEFT JOIN m_department_lines_t d ON CAST(JSON_UNQUOTE(JSON_EXTRACT(e.department, '$[0]')) AS SIGNED) = d.department_line_id
                LEFT JOIN m_job_title j ON e.job_title = j.job_title_id
                WHERE e.active='yes'
                  AND e.employee_id = ?
                LIMIT 1
            ", [$managerId]);

            if (!empty($managerRowArr)) {
                $managerNode = $this->makeNodeFromRow($managerRowArr[0], $Url);
                $managerNode['children'] = [$selfNode];

                $company = $this->emptyCompanyNode($Url);
                $company['children'] = [$managerNode];
                return $company;
            }
        }

        // If no manager, make company -> self
        $company = $this->emptyCompanyNode($Url);
        $company['children'] = [$selfNode];
        return $company;
    }

    /**
     * HO/Co: build full tree for given employee types.
     */
    private function buildHoCoChart(string $Url): array
    {
        $rows = DB::select("
            SELECT
                emp.employee_id as id,
                emp.reporting_manager as pid,
                emp.first_name as name,
                emp.photo,
                a.area_name,
                emp.date_of_joining,
                p.position as grade,
                d.sub_department_name as dept,
                j.job_title_name as title
            FROM hr_employee_t emp
            LEFT JOIN m_position p ON emp.position = p.position_id
            LEFT JOIN m_area_t a ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.med_rep_area, '$[0]')) AS SIGNED) = a.area_id
            LEFT JOIN m_department_lines_t d ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.department, '$[0]')) AS SIGNED) = d.department_line_id
            LEFT JOIN m_job_title j ON emp.job_title = j.job_title_id
            WHERE emp.employee_type IN ('150','229','237','238','151','152','249')
              AND emp.active='Yes'
        ");

        if (empty($rows)) {
            return $this->emptyCompanyNode($Url);
        }

        // Build map by id and children adjacency
        $nodeById = [];
        $childrenByPid = [];

        foreach ($rows as $r) {
            $nodeById[(int) $r->id] = $this->makeNodeFromRow($r, $Url) + ['children' => []];
            $pid = (int) ($r->pid ?? 0);
            if ($pid > 0) {
                $childrenByPid[$pid][] = (int) $r->id;
            }
        }

        // Link children
        foreach ($childrenByPid as $pid => $childIds) {
            if (!isset($nodeById[$pid]))
                continue;
            foreach ($childIds as $cid) {
                if (isset($nodeById[$cid])) {
                    $nodeById[$pid]['children'][] = &$nodeById[$cid];
                }
            }
        }

        // Roots: those whose pid not found or pid = 0
        $roots = [];
        foreach ($nodeById as $id => $node) {
            $pid = (int) ($node['pid'] ?? 0);
            if ($pid === 0 || !isset($nodeById[$pid])) {
                $roots[] = $node;
            }
        }

        $company = $this->emptyCompanyNode($Url);
        $company['children'] = $roots;

        return $company;
    }

    // east zone
    private function buildEastChart(string $Url): array
    {
        $rows = DB::select("
            SELECT 
        emp.employee_id as id,
        emp.reporting_manager as pid,
        emp.first_name as name,
        emp.photo,
        m_area_t.area_name,
        emp.date_of_joining,
        m_position.position as grade,
        m_department_lines_t.sub_department_name as dept,
        m_job_title.job_title_name as title,
        manager.employee_id as manager_id,
        manager.first_name as manager_name
    FROM 
        hr_employee_t AS emp
    LEFT JOIN 
        `m_position` ON emp.position = m_position.position_id
    LEFT JOIN 
        m_area_t ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.med_rep_area, '$[0]')) AS SIGNED) = m_area_t.area_id
    LEFT JOIN 
        `m_job_title` ON emp.job_title = m_job_title.job_title_id 
        LEFT JOIN 
    `m_department_lines_t` ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.department, '$[0]')) AS SIGNED) = m_department_lines_t.department_line_id 
    LEFT JOIN 
        hr_employee_t AS manager ON emp.reporting_manager = manager.employee_id
    WHERE 
       (emp.zone_id ='16' OR  emp.employee_id='160' OR  emp.employee_id='1') AND emp.active = 'Yes'
        ");

        if (empty($rows)) {
            return $this->emptyCompanyNode($Url);
        }

        // Build map by id and children adjacency
        $nodeById = [];
        $childrenByPid = [];

        foreach ($rows as $r) {
            $nodeById[(int) $r->id] = $this->makeNodeFromRow($r, $Url) + ['children' => []];
            $pid = (int) ($r->pid ?? 0);
            if ($pid > 0) {
                $childrenByPid[$pid][] = (int) $r->id;
            }
        }

        // Link children
        foreach ($childrenByPid as $pid => $childIds) {
            if (!isset($nodeById[$pid]))
                continue;
            foreach ($childIds as $cid) {
                if (isset($nodeById[$cid])) {
                    $nodeById[$pid]['children'][] = &$nodeById[$cid];
                }
            }
        }

        // Roots: those whose pid not found or pid = 0
        $roots = [];
        foreach ($nodeById as $id => $node) {
            $pid = (int) ($node['pid'] ?? 0);
            if ($pid === 0 || !isset($nodeById[$pid])) {
                $roots[] = $node;
            }
        }

        $company = $this->emptyCompanyNode($Url);
        $company['children'] = $roots;

        return $company;
    }

    // CW zone
    private function buildCWChart(string $Url): array
    {
        $rows = DB::select("
          SELECT 
        emp.employee_id as id,
        emp.reporting_manager as pid,
        emp.first_name as name,
        emp.photo,
        m_area_t.area_name,
        emp.date_of_joining,
        m_position.position as grade,
        m_department_lines_t.sub_department_name as dept,
        m_job_title.job_title_name as title,
        manager.employee_id as manager_id,
        manager.first_name as manager_name
    FROM 
        hr_employee_t AS emp
    LEFT JOIN 
        `m_position` ON emp.position = m_position.position_id
    LEFT JOIN 
        m_area_t ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.med_rep_area, '$[0]')) AS SIGNED) = m_area_t.area_id
    LEFT JOIN 
        `m_job_title` ON emp.job_title = m_job_title.job_title_id 
        LEFT JOIN 
    `m_department_lines_t` ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.department, '$[0]')) AS SIGNED) = m_department_lines_t.department_line_id 
    LEFT JOIN 
        hr_employee_t AS manager ON emp.reporting_manager = manager.employee_id
    WHERE 
       (emp.zone_id ='27' OR  emp.employee_id='160' OR  emp.employee_id='1') AND emp.active = 'Yes'
        ");

        if (empty($rows)) {
            return $this->emptyCompanyNode($Url);
        }

        // Build map by id and children adjacency
        $nodeById = [];
        $childrenByPid = [];

        foreach ($rows as $r) {
            $nodeById[(int) $r->id] = $this->makeNodeFromRow($r, $Url) + ['children' => []];
            $pid = (int) ($r->pid ?? 0);
            if ($pid > 0) {
                $childrenByPid[$pid][] = (int) $r->id;
            }
        }

        // Link children
        foreach ($childrenByPid as $pid => $childIds) {
            if (!isset($nodeById[$pid]))
                continue;
            foreach ($childIds as $cid) {
                if (isset($nodeById[$cid])) {
                    $nodeById[$pid]['children'][] = &$nodeById[$cid];
                }
            }
        }

        // Roots: those whose pid not found or pid = 0
        $roots = [];
        foreach ($nodeById as $id => $node) {
            $pid = (int) ($node['pid'] ?? 0);
            if ($pid === 0 || !isset($nodeById[$pid])) {
                $roots[] = $node;
            }
        }

        $company = $this->emptyCompanyNode($Url);
        $company['children'] = $roots;

        return $company;
    }

    // Northzone
    private function buildNorthChart(string $Url): array
    {
        $rows = DB::select("
            SELECT 
        emp.employee_id as id,
        emp.reporting_manager as pid,
        emp.first_name as name,
        emp.photo,
        m_area_t.area_name,
        emp.date_of_joining,
        m_position.position as grade,
        m_department_lines_t.sub_department_name as dept,
        m_job_title.job_title_name as title,
        manager.employee_id as manager_id,
        manager.first_name as manager_name
    FROM 
        hr_employee_t AS emp
    LEFT JOIN 
        `m_position` ON emp.position = m_position.position_id
    LEFT JOIN 
        m_area_t ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.med_rep_area, '$[0]')) AS SIGNED) = m_area_t.area_id
    LEFT JOIN 
        `m_job_title` ON emp.job_title = m_job_title.job_title_id 
        LEFT JOIN 
    `m_department_lines_t` ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.department, '$[0]')) AS SIGNED) = m_department_lines_t.department_line_id 
    LEFT JOIN 
        hr_employee_t AS manager ON emp.reporting_manager = manager.employee_id
    WHERE 
       (emp.zone_id ='18' OR  emp.employee_id='160' OR  emp.employee_id='1') AND emp.active = 'Yes'
        ");

        if (empty($rows)) {
            return $this->emptyCompanyNode($Url);
        }

        // Build map by id and children adjacency
        $nodeById = [];
        $childrenByPid = [];

        foreach ($rows as $r) {
            $nodeById[(int) $r->id] = $this->makeNodeFromRow($r, $Url) + ['children' => []];
            $pid = (int) ($r->pid ?? 0);
            if ($pid > 0) {
                $childrenByPid[$pid][] = (int) $r->id;
            }
        }

        // Link children
        foreach ($childrenByPid as $pid => $childIds) {
            if (!isset($nodeById[$pid]))
                continue;
            foreach ($childIds as $cid) {
                if (isset($nodeById[$cid])) {
                    $nodeById[$pid]['children'][] = &$nodeById[$cid];
                }
            }
        }

        // Roots: those whose pid not found or pid = 0
        $roots = [];
        foreach ($nodeById as $id => $node) {
            $pid = (int) ($node['pid'] ?? 0);
            if ($pid === 0 || !isset($nodeById[$pid])) {
                $roots[] = $node;
            }
        }

        $company = $this->emptyCompanyNode($Url);
        $company['children'] = $roots;

        return $company;
    }

    // south1
    private function buildSouth1Chart(string $Url): array
    {
        $rows = DB::select("
           SELECT 
        emp.employee_id as id,
        emp.reporting_manager as pid,
        emp.first_name as name,
        emp.photo,
        m_area_t.area_name,
        emp.date_of_joining,
        m_position.position as grade,
        m_department_lines_t.sub_department_name as dept,
        m_job_title.job_title_name as title,
        manager.employee_id as manager_id,
        manager.first_name as manager_name
    FROM 
        hr_employee_t AS emp
    LEFT JOIN 
        `m_position` ON emp.position = m_position.position_id
    LEFT JOIN 
        m_area_t ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.med_rep_area, '$[0]')) AS SIGNED) = m_area_t.area_id
    LEFT JOIN 
        `m_job_title` ON emp.job_title = m_job_title.job_title_id 
        LEFT JOIN 
    `m_department_lines_t` ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.department, '$[0]')) AS SIGNED) = m_department_lines_t.department_line_id 
    LEFT JOIN 
        hr_employee_t AS manager ON emp.reporting_manager = manager.employee_id
    WHERE 
       (emp.zone_id ='25' OR  emp.employee_id='160' OR  emp.employee_id='1') AND emp.active = 'Yes'
        ");

        if (empty($rows)) {
            return $this->emptyCompanyNode($Url);
        }

        // Build map by id and children adjacency
        $nodeById = [];
        $childrenByPid = [];

        foreach ($rows as $r) {
            $nodeById[(int) $r->id] = $this->makeNodeFromRow($r, $Url) + ['children' => []];
            $pid = (int) ($r->pid ?? 0);
            if ($pid > 0) {
                $childrenByPid[$pid][] = (int) $r->id;
            }
        }

        // Link children
        foreach ($childrenByPid as $pid => $childIds) {
            if (!isset($nodeById[$pid]))
                continue;
            foreach ($childIds as $cid) {
                if (isset($nodeById[$cid])) {
                    $nodeById[$pid]['children'][] = &$nodeById[$cid];
                }
            }
        }

        // Roots: those whose pid not found or pid = 0
        $roots = [];
        foreach ($nodeById as $id => $node) {
            $pid = (int) ($node['pid'] ?? 0);
            if ($pid === 0 || !isset($nodeById[$pid])) {
                $roots[] = $node;
            }
        }

        $company = $this->emptyCompanyNode($Url);
        $company['children'] = $roots;

        return $company;
    }
    //south 2
    private function buildSouth2Chart(string $Url): array
    {
        $rows = DB::select("
           SELECT 
        emp.employee_id as id,
        emp.reporting_manager as pid,
        emp.first_name as name,
        emp.photo,
        m_area_t.area_name,
        emp.date_of_joining,
        m_position.position as grade,
        m_department_lines_t.sub_department_name as dept,
        m_job_title.job_title_name as title,
        manager.employee_id as manager_id,
        manager.first_name as manager_name
    FROM 
        hr_employee_t AS emp
    LEFT JOIN 
        `m_position` ON emp.position = m_position.position_id
    LEFT JOIN 
        m_area_t ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.med_rep_area, '$[0]')) AS SIGNED) = m_area_t.area_id
    LEFT JOIN 
        `m_job_title` ON emp.job_title = m_job_title.job_title_id 
        LEFT JOIN 
    `m_department_lines_t` ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.department, '$[0]')) AS SIGNED) = m_department_lines_t.department_line_id 
    LEFT JOIN 
        hr_employee_t AS manager ON emp.reporting_manager = manager.employee_id
    WHERE 
       (emp.zone_id ='24' OR  emp.employee_id='160' OR  emp.employee_id='1') AND emp.active = 'Yes'
        ");

        if (empty($rows)) {
            return $this->emptyCompanyNode($Url);
        }

        // Build map by id and children adjacency
        $nodeById = [];
        $childrenByPid = [];

        foreach ($rows as $r) {
            $nodeById[(int) $r->id] = $this->makeNodeFromRow($r, $Url) + ['children' => []];
            $pid = (int) ($r->pid ?? 0);
            if ($pid > 0) {
                $childrenByPid[$pid][] = (int) $r->id;
            }
        }

        // Link children
        foreach ($childrenByPid as $pid => $childIds) {
            if (!isset($nodeById[$pid]))
                continue;
            foreach ($childIds as $cid) {
                if (isset($nodeById[$cid])) {
                    $nodeById[$pid]['children'][] = &$nodeById[$cid];
                }
            }
        }

        // Roots: those whose pid not found or pid = 0
        $roots = [];
        foreach ($nodeById as $id => $node) {
            $pid = (int) ($node['pid'] ?? 0);
            if ($pid === 0 || !isset($nodeById[$pid])) {
                $roots[] = $node;
            }
        }

        $company = $this->emptyCompanyNode($Url);
        $company['children'] = $roots;

        return $company;
    }

    //south3
    private function buildSouth3Chart(string $Url): array
    {
        $rows = DB::select("
            SELECT 
        emp.employee_id as id,
        emp.reporting_manager as pid,
        emp.first_name as name,
        emp.photo,
        m_area_t.area_name,
        emp.date_of_joining,
        m_position.position as grade,
        m_department_lines_t.sub_department_name as dept,
        m_job_title.job_title_name as title,
        manager.employee_id as manager_id,
        manager.first_name as manager_name
    FROM 
        hr_employee_t AS emp
    LEFT JOIN 
        `m_position` ON emp.position = m_position.position_id
    LEFT JOIN 
        m_area_t ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.med_rep_area, '$[0]')) AS SIGNED) = m_area_t.area_id
    LEFT JOIN 
        `m_job_title` ON emp.job_title = m_job_title.job_title_id 
        LEFT JOIN 
    `m_department_lines_t` ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.department, '$[0]')) AS SIGNED) = m_department_lines_t.department_line_id 
    LEFT JOIN 
        hr_employee_t AS manager ON emp.reporting_manager = manager.employee_id
    WHERE 
       (emp.zone_id ='26' OR  emp.employee_id='160' OR  emp.employee_id='1' OR  emp.employee_id='455') AND emp.active = 'Yes'
        ");

        if (empty($rows)) {
            return $this->emptyCompanyNode($Url);
        }

        // Build map by id and children adjacency
        $nodeById = [];
        $childrenByPid = [];

        foreach ($rows as $r) {
            $nodeById[(int) $r->id] = $this->makeNodeFromRow($r, $Url) + ['children' => []];
            $pid = (int) ($r->pid ?? 0);
            if ($pid > 0) {
                $childrenByPid[$pid][] = (int) $r->id;
            }
        }

        // Link children
        foreach ($childrenByPid as $pid => $childIds) {
            if (!isset($nodeById[$pid]))
                continue;
            foreach ($childIds as $cid) {
                if (isset($nodeById[$cid])) {
                    $nodeById[$pid]['children'][] = &$nodeById[$cid];
                }
            }
        }

        // Roots: those whose pid not found or pid = 0
        $roots = [];
        foreach ($nodeById as $id => $node) {
            $pid = (int) ($node['pid'] ?? 0);
            if ($pid === 0 || !isset($nodeById[$pid])) {
                $roots[] = $node;
            }
        }

        $company = $this->emptyCompanyNode($Url);
        $company['children'] = $roots;

        return $company;
    }
    //corporate
    private function buildCorporateChart(string $Url): array
    {
        $rows = DB::select("
              SELECT 
        emp.employee_id as id,
        emp.reporting_manager as pid,
        emp.first_name as name,
        emp.photo,
        m_area_t.area_name,
        emp.date_of_joining,
        m_position.position as grade,
        m_department_lines_t.sub_department_name as dept,
        m_job_title.job_title_name as title,
        manager.employee_id as manager_id,
        manager.first_name as manager_name
    FROM 
        hr_employee_t AS emp
    LEFT JOIN 
        `m_position` ON emp.position = m_position.position_id
    LEFT JOIN 
        m_area_t ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.med_rep_area, '$[0]')) AS SIGNED) = m_area_t.area_id
    LEFT JOIN 
        `m_job_title` ON emp.job_title = m_job_title.job_title_id 
    LEFT JOIN 
    `m_department_lines_t` ON CAST(JSON_UNQUOTE(JSON_EXTRACT(emp.department, '$[0]')) AS SIGNED) = m_department_lines_t.department_line_id 
    LEFT JOIN 
        hr_employee_t AS manager ON emp.reporting_manager = manager.employee_id
    WHERE 
       (emp.zone_id ='19' OR  emp.employee_id='160' OR  emp.employee_id='1') AND emp.active = 'Yes'
        ");

        if (empty($rows)) {
            return $this->emptyCompanyNode($Url);
        }

        // Build map by id and children adjacency
        $nodeById = [];
        $childrenByPid = [];

        foreach ($rows as $r) {
            $nodeById[(int) $r->id] = $this->makeNodeFromRow($r, $Url) + ['children' => []];
            $pid = (int) ($r->pid ?? 0);
            if ($pid > 0) {
                $childrenByPid[$pid][] = (int) $r->id;
            }
        }

        // Link children
        foreach ($childrenByPid as $pid => $childIds) {
            if (!isset($nodeById[$pid]))
                continue;
            foreach ($childIds as $cid) {
                if (isset($nodeById[$cid])) {
                    $nodeById[$pid]['children'][] = &$nodeById[$cid];
                }
            }
        }

        // Roots: those whose pid not found or pid = 0
        $roots = [];
        foreach ($nodeById as $id => $node) {
            $pid = (int) ($node['pid'] ?? 0);
            if ($pid === 0 || !isset($nodeById[$pid])) {
                $roots[] = $node;
            }
        }

        $company = $this->emptyCompanyNode($Url);
        $company['children'] = $roots;

        return $company;
    }
    private function buildSearchChart(int $searchId, string $Url): array
    {
        if ($searchId <= 0) {
            return $this->emptyCompanyNode($Url);
        }

        $rows = DB::select("
            SELECT
                e.employee_id as id,
                e.reporting_manager as pid,
                e.first_name as name,
                e.photo,
                a.area_name,
                e.date_of_joining,
                p.position as grade,
                d.sub_department_name as dept,
                j.job_title_name as title
            FROM hr_employee_t e
            LEFT JOIN m_position p ON e.position = p.position_id
            LEFT JOIN m_area_t a ON CAST(JSON_UNQUOTE(JSON_EXTRACT(e.med_rep_area, '$[0]')) AS SIGNED) = a.area_id
            LEFT JOIN m_department_lines_t d ON CAST(JSON_UNQUOTE(JSON_EXTRACT(e.department, '$[0]')) AS SIGNED) = d.department_line_id
            LEFT JOIN m_job_title j ON e.job_title = j.job_title_id
            WHERE e.active='yes'
              AND (e.employee_id = ? OR e.reporting_manager = ?)
        ", [$searchId, $searchId]);

        $rootRow = null;
        $childRows = [];

        foreach ($rows as $r) {
            if ((int) $r->id === $searchId)
                $rootRow = $r;
            if ((int) ($r->pid ?? 0) === $searchId)
                $childRows[] = $r;
        }

        if (!$rootRow) {
            // Show a “not found” node rather than null (prevents JS crash)
            $company = $this->emptyCompanyNode($Url);
            $company['children'] = [
                [
                    'id' => -1,
                    'pid' => 1,
                    'name' => 'Employee not found',
                    'positionName' => '-',
                    'area' => '-',
                    'place' => '-',
                    'grade' => '-',
                    'doj' => '-',
                    'imageUrl' => "$Url/profile_images/logo.png",
                    'profileUrl' => "$Url/profile_images/logo.png",
                    'children' => [],
                ]
            ];
            return $company;
        }

        $rootNode = $this->makeNodeFromRow($rootRow, $Url);
        $rootNode['children'] = [];

        foreach ($childRows as $cr) {
            $childNode = $this->makeNodeFromRow($cr, $Url);
            $childNode['children'] = [];
            $rootNode['children'][] = $childNode;
        }

        $company = $this->emptyCompanyNode($Url);
        $company['children'] = [$rootNode];

        return $company;
    }

    private function makeNodeFromRow($row, string $Url): array
    {
        $photo = $row->photo ?: 'logo.png';

        return [
            'id' => (int) $row->id,
            'pid' => $row->pid ? (int) $row->pid : 0,
            'name' => (string) $row->name,
            'positionName' => (string) ($row->title ?? ''),
            'area' => (string) ($row->dept ?? ''),
            'place' => (string) ($row->area_name ?? ''),
            'grade' => (string) ($row->grade ?? ''),
            'doj' => (string) ($row->date_of_joining ?? ''),
            'profileUrl' => "$Url/profile_images/{$photo}",
            'imageUrl' => "$Url/profile_images/{$photo}",
        ];
    }
}
