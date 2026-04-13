<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Applicationviewers;
use DB;
use Session;
use Config;
use DateInterval;
use DateTime;
use DatePeriod;
use Yajra\DataTables\DataTables;

class paySlipController extends Controller
{
    public function __construct()
    {
        $this->data['pageModule'] = \Request::route()->getName();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();
    }


    public function attendancereport(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();

        $Controller = new Controller();
        $access = $Controller->Accessdined();

        $userAccess = json_decode($access, true);

        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }

        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');

        }

        // END


        return view('payproposal.employeeatten', $this->data);


    }

    public function attendancereportdata()
    {
        error_reporting(0);
        $year = $_GET['year'];
        $month = $_GET['month'];

        if ($month == 1) {
            $start_year = $year - 1;
            $first_day_this_month = date($start_year . "-12-26");
        } else {
            $start_year = $year;
            $first_day_this_month = date($start_year . "-" . ($month - 1) . "-26");
        }
        $last_day_this_month = date($year . "-" . $month . "-25");

        $gid = \Session('groupid');
        $emp_id = \Session('emp_id');

        if ($gid != 1 && $gid != 2) {
            $emp = \DB::SELECT("SELECT hr_employee_t.*,a_lookuplines_t.lookup_code 
            FROM hr_employee_t 
            JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type 
            WHERE hr_employee_t.employee_id != 1 
            AND hr_employee_t.active='Yes' 
            AND hr_employee_t.employee_id = $emp_id");
        } else {
            $emp = \DB::SELECT("SELECT hr_employee_t.*,a_lookuplines_t.lookup_code 
            FROM hr_employee_t 
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type 
            WHERE hr_employee_t.employee_id != 1  
            AND hr_employee_t.active='Yes'");
        }

        // Badge color mapping
        $badgeColor = function ($status) {
            $s = strtoupper(trim($status));
            $map = [
                'P' => 'success',
                'P(H)' => 'success',
                'P(OD)' => 'success',
                'A' => 'danger',
                'WO' => 'primary',
                'H' => 'secondary',
                'CL' => 'warning',
                'SL' => 'warning',
                'EL' => 'warning',
                'C-OFF' => 'info',
                '' => 'light'
            ];
            return $map[$s] ?? 'secondary';
        };

        // Get all dates for the period
        $allDates = [];
        $cursor = new DateTime($first_day_this_month);
        $end = new DateTime($last_day_this_month);
        while ($cursor <= $end) {
            $allDates[] = $cursor->format('Y-m-d');
            $cursor->modify('+1 day');
        }

        $name_month = date("F", strtotime($last_day_this_month));

        // Prepare Excel headers
        $my_file = uniqid() . '.xls';
        $file = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file);
        $columnHeader = "Employee Number\tEmployee Name\tEmployee Type\t" . implode("\t", array_map(function ($d) {
            return date('d-m-Y', strtotime($d));
        }, $allDates)) . "\n";

        $rowData = '';

        $empRows = [];

        if (count($emp) > 0) {
            foreach ($emp as $value) {
                $emp_number = $value->employee_number;
                $emp_name = $value->first_name;
                $emp_type = $value->lookup_code;
                $emp_id = $value->employee_id;
                $check_category = $value->category;
                $loc_id = json_decode($value->location_id);
                $locationid = $loc_id[0];

                $rows = [];
                $lineExcel = [$emp_number, $emp_name, $emp_type];

                $begin = new DateTime($first_day_this_month);
                $enddt = new DateTime($last_day_this_month);

                while ($begin <= $enddt) {
                    $date = $begin->format('Y-m-d');
                    $status = '';

                    // ---- Attendance logic from original code ----
                    $holidays = \DB::select("SELECT * FROM `hr_holiday_t` WHERE date='$date' AND company_id='$value->company_id' AND active='Yes'");
                    $atten_detail = \DB::select("SELECT `check_in`,`check_out`,`working_hours` FROM `hr_emp_attendence` WHERE `emp_id`='$emp_number' AND date(`atten_date`) LIKE '$date'");
                    $query_leave_full = \DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM `hr_leaves_t` 
                    LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_type 
                    WHERE employee_id='$emp_id' AND leave_status='APPROVE' 
                    AND (a_lookuplines_t.lookup_code IN ('ON-DUTY','EARN LEAVE','CASUAL LEAVE','SICK LEAVE','COMP-OFF')) 
                    AND ('$date' BETWEEN start_date AND end_date) 
                    AND leave_mode='135'");
                    $query_leave_half = \DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM `hr_leaves_t` 
                    LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_type 
                    WHERE employee_id='$emp_id' AND leave_status='APPROVE' 
                    AND (a_lookuplines_t.lookup_code IN ('ON-DUTY','EARN LEAVE','CASUAL LEAVE','SICK LEAVE')) 
                    AND ('$date' BETWEEN start_date AND end_date) 
                    AND leave_mode='134'");

                    $current_date = date('Y-m-d');
                    if (count($atten_detail) > 0) {
                        if (count($query_leave_half) > 0) {
                            $status = 'P(H)' . substr($query_leave_half[0]->lookup_code, 0, 2);
                        } else {
                            if ($atten_detail[0]->check_in != $atten_detail[0]->check_out) {
                                $status = 'P';
                            } else {
                                $status = 'A';
                            }
                        }
                    } else if (count($query_leave_full) > 0) {
                        $status = substr($query_leave_full[0]->lookup_code, 0, 2);
                    } else if (count($query_leave_half) > 0) {
                        $status = 'P(H)';
                    } else if ($date > $current_date) {
                        $status = '';
                    } else {
                        $date_day = date('N', strtotime($date));
                        $week_off_query = \DB::select("SELECT * FROM `hr_emp_payroll_settings_t`");
                        $week_off = (count($week_off_query) != 0) ? json_decode($week_off_query[0]->week_off) : [];

                        $holiday_location = (count($holidays) == 1) ? json_decode($holidays[0]->location) : [];

                        if (in_array($locationid, $holiday_location)) {
                            $status = 'H';
                        } else if (in_array($date_day, $week_off)) {
                            $status = 'WO';
                        } else {
                            $status = 'A';
                        }
                    }

                    $rows[] = ['date' => $date, 'status' => $status];
                    $lineExcel[] = $status;
                    $begin->modify('+1 day');
                }

                $empRows[] = [
                    'emp_number' => $emp_number,
                    'emp_name' => $emp_name,
                    'emp_type' => $emp_type,
                    'rows' => $rows
                ];

                $rowData .= implode("\t", $lineExcel) . "\n";
            }
        }

        fwrite($file, $columnHeader . $rowData);

        // Build HTML output
        $html = '<div class="card shadow-lg rounded-4 border-0 mb-4">';
        $html .= '<div class="card-header bg-primary text-white"><h5 class="mb-0 text-center">EMPLOYEE ATTENDANCE REPORT</h5></div>';
        $html .= '<div class="card-body">';
        $html .= '<div class="text-center mb-3 fw-bold">For - ' . $name_month . ' ' . $year . '</div>';
        $html .= '<div class="d-flex flex-wrap gap-2 mb-3 justify-content-center">
                <span class="badge bg-success">P / P(H) / P(OD)</span>
                <span class="badge bg-danger">A</span>
                <span class="badge bg-primary">WO</span>
                <span class="badge bg-secondary">H</span>
                <span class="badge bg-warning text-dark">CL / SL / EL</span>
                <span class="badge bg-info text-dark">C-OFF</span>
              </div>';

        foreach ($empRows as $empRow) {
            $html .= '<div class="card mb-4 shadow-sm border-0">';
            $html .= '<div class="card-header bg-light">
                    <strong>Emp No:</strong> ' . $empRow["emp_number"] . ' | 
                    <strong>Name:</strong> ' . $empRow["emp_name"] . ' | 
                    <strong>Type:</strong> ' . $empRow["emp_type"] . '
                  </div>';
            $html .= '<div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="table-warning">
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($empRow['rows'] as $r) {
                $status = $r['status'];
                $color = $badgeColor($status);
                $html .= '<tr>
                        <td>' . date('d-m-Y', strtotime($r['date'])) . '</td>
                        <td><span class="badge bg-' . $color . '">' . htmlspecialchars($status) . '</span></td>
                      </tr>';
            }
            $html .= '        </tbody>
                        </table>
                    </div>
                  </div>';
            $html .= '</div>';
        }

        $html .= "<div class='text-center'>
                <a href='$my_file' download class='btn btn-danger'>
                    <i class='fa fa-download'></i> Download
                </a>
              </div>";
        $html .= '</div></div>';

        return $html;
    }


    public function weeknumber($date)
    {

        $week_name = array("1" => "first", "2" => "second", "3" => "third", "4" => "fourth", "5" => "fifth");
        $firstOfMonth = date("Y-m-01", strtotime($date));
        $Month = (string) date("m", strtotime($date));
        $Date = (string) date("d", strtotime($date));
        if ($date == "2019-09-30") {
            $week_month = 1;
        } else {
            if ($Month == "01" && $Date != "01")
                $week_month = intval(date("W", strtotime($date)));
            else if ($Month == "12") {
                $week = intval(date("W", strtotime($date)));

                if ($week == 1) {
                    $week_month = 5;
                } else {
                    $week_month = intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth))) + 1;
                }
            } else {
                $week_month = intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth))) + 1;
            }
        }

        // echo $week_month;
        return $week_name[$week_month];
    }

    // array combine
    public function arraycombine($a, $b)
    {
        $c = [];
        foreach ($a as $k => $v) {
            if (!isset($c[$v])) {
                $c[$v][] = $b[$k];
            } else {
                $c[$v][] = $b[$k];
            }
        }
        return $c;
    }


    // index for employeeactive
    public function employeeactive(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();

        $Controller = new Controller();
        $access = $Controller->Accessdined();

        $userAccess = json_decode($access, true);

        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }

        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');

        }

        // END


        return view('payproposal.employeeactive', $this->data);


    }
    // report get for employeeactive
    public function employeeData(Request $request, $month = null, $year = null)
    {

        $wh = '';
        /*Human Resources Marketing access control */
        $groupname = \Session::get('groupname');
        if ($groupname == "16") {
            $wh = " AND (v1.emp_type_name='Marketing' OR v1.emp_type_name='Business Associate' OR v1.emp_type_name='Commission Based')";
        }


        $result = \DB::select("select* from(SELECT hr_employee_t.*,group_type_t.group_name  as group_name ,emp_type_t.lookup_code as emp_type_name,m_position.position as position_name,m_job_title.job_title_name,m_company_t.company_name,
             (CASE WHEN hr_employee_t.prefix=1 THEN 'MR'
          WHEN hr_employee_t.prefix=2  THEN 'MS' 
           WHEN hr_employee_t.prefix=3  THEN 'MRS'
          END) as prefix_name,  (CASE WHEN hr_employee_t.zone_id = 16 THEN 'East'
            WHEN hr_employee_t.zone_id = 18 THEN 'North'
            WHEN hr_employee_t.zone_id = 19 THEN 'Corporate'
            WHEN hr_employee_t.zone_id = 27 THEN 'Central West'
            WHEN hr_employee_t.zone_id = 24 THEN 'South 2'
            WHEN hr_employee_t.zone_id = 25 THEN 'South 1'
      END) AS zone_name,(CASE WHEN hr_employee_t.ot_formula=1 THEN 'OT Applicable'
          WHEN hr_employee_t.ot_formula=2  THEN 'OT Not Applicable' 
          END) as ot,concat(rep.employee_number,'-',rep.first_name) as rep_name,concat(rep1.employee_number,'-',rep1.first_name) as rep1_name
         from hr_employee_t 
         left join m_position on(m_position.position_id=hr_employee_t.position)
         left join m_job_title on(m_job_title.job_title_id=hr_employee_t.job_title)
         left join hr_employee_t as rep on(rep.employee_id=hr_employee_t.reporting_manager)
         left join hr_employee_t as rep1 on(rep1.employee_id=hr_employee_t.reporting_manager1)
         left join a_m_group_t as group_type_t on(group_type_t.group_id=hr_employee_t.group_type)
         left join a_lookuplines_t as emp_type_t on(emp_type_t.lookuplines_id=hr_employee_t.employee_type)
         left join m_company_t on(m_company_t.company_id=hr_employee_t.company_id)
          where 1=1 and hr_employee_t.employee_id!=1 )v1 where 1=1 $wh");


        return DataTables::of($result)->make(true);

    }


    // index for employeerelieve
    public function employeerelieve(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();

        $Controller = new Controller();
        $access = $Controller->Accessdined();

        $userAccess = json_decode($access, true);

        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }

        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');

        }

        // END

        return view('payproposal.employeerelieve', $this->data);


    }
    public function employeerelievereportgrid(Request $request)
    {

        $wh = '';

        $month = $request->month;
        $year = $request->year;

        /*Human Resources Marketing access control */
        $groupname = \Session::get('groupname');
        if ($groupname == "16") {
            $wh = " where 1=1 AND (v1.emp_type='Marketing' OR v1.emp_type='Business Associate' OR v1.emp_type='Commission Based')";
        }



        $dat = " and month(hr_employee_t.date_of_leaving)='$month' and year(hr_employee_t.date_of_leaving)='$year'";

        $result1 = DB::select("SELECT * from(SELECT hr_employee_t.*,hr_emp_personal.date_of_birth as dob, hr_emp_personal.father_name as father_name, m_job_title.job_title_name,concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as emp_nmae, relieving_t.relieve_reason as reason, a_lookuplines_t.lookup_code as emp_type,
     ( CASE WHEN hr_employee_t.zone_id = 16 THEN 'East'

            WHEN hr_employee_t.zone_id = 18 THEN 'North'
            WHEN hr_employee_t.zone_id = 19 THEN 'Corporate'
            WHEN hr_employee_t.zone_id = 27 THEN 'Central West'
            WHEN hr_employee_t.zone_id = 24 THEN 'South 2'
            WHEN hr_employee_t.zone_id = 25 THEN 'South 1'
        END) AS zone_name from hr_employee_t 
        left join m_job_title on m_job_title.job_title_id=hr_employee_t.job_title
     left join hr_emp_personal on(hr_emp_personal.employee_id=hr_employee_t.employee_id)
     left join relieving_t on(relieving_t.emp_id=hr_employee_t.employee_id)
    LEFT JOIN a_lookuplines_t on a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
     where 1=1 and hr_employee_t.employee_id!=1 $dat)v1 $wh");


        if (count($result1) > 0) {
            foreach ($result1 as $k => $v) {
                if ($v->department != '' && $v->department != null) {
                    $array = json_decode($v->department);
                    $query = \DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
                    $deptnames = json_decode(json_encode($query), true);
                    $result1[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
                } else {
                    $result1[$k]->department = "";
                }
            }
        }



        return DataTables::of($result1)->make(true);


    }

    public function employeeactivereport()
    {

        return view('payproposal.employeeselect', $this->data);


    }
    public function employeeactivereportgrid(Request $request)
    {

        $month = $request->month;
        $year = $request->year;

        $wh = '';

        /*Human Resources Marketing access control */
        $groupname = \Session::get('groupname');
        if ($groupname == "16") {
            $wh = " where 1=1 AND (v1.emp_type='Marketing' OR v1.emp_type='Business Associate' OR v1.emp_type='Commission Based')";
        }



        $dat = " and month(hr_employee_t.date_of_joining)='$month' and year(hr_employee_t.date_of_joining)='$year'";


        $result = DB::select("SELECT * from(SELECT hr_employee_t.pf_no,hr_employee_t.date_of_joining,hr_employee_t.uan_no,hr_employee_t.esi_no,hr_employee_t.department,hr_emp_personal.date_of_birth,hr_emp_personal.aadhar_number,hr_emp_personal.pan_number, m_job_title.job_title_name, hr_emp_personal.father_name as father_name,hr_employee_t.employee_number,hr_employee_t.first_name as emp_nmae, relieving_t.relieve_reason as reason,(CASE WHEN hr_emp_personal.gender=1 THEN 'Male'
      WHEN hr_emp_personal.gender=2  THEN 'Female' 
      END) as gender,     ( CASE WHEN hr_employee_t.zone_id = 16 THEN 'East'

            WHEN hr_employee_t.zone_id = 18 THEN 'North'
            WHEN hr_employee_t.zone_id = 19 THEN 'Corporate'
            WHEN hr_employee_t.zone_id = 27 THEN 'Central West'
            WHEN hr_employee_t.zone_id = 24 THEN 'South 2'
            WHEN hr_employee_t.zone_id = 25 THEN 'South 1'
            WHEN hr_employee_t.zone_id = 26 THEN 'South 3'
        END) AS zone_name, a_lookuplines_t.lookup_code as emp_type
      from hr_employee_t 
      left join hr_emp_personal on(hr_emp_personal.employee_id=hr_employee_t.employee_id)
      left join m_job_title on m_job_title.job_title_id=hr_employee_t.job_title
      left join relieving_t on(relieving_t.emp_id=hr_employee_t.employee_id)
      LEFT JOIN a_lookuplines_t on a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
 where 1=1 and hr_employee_t.employee_id!=1 $dat)v1 $wh ");

        if (count($result) > 0) {
            foreach ($result as $k => $v) {
                if ($v->department != '' && $v->department != null) {
                    $array = json_decode($v->department);
                    $query = \DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
                    $deptnames = json_decode(json_encode($query), true);
                    $result[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
                } else {
                    $result[$k]->department = "";
                }
            }
        }

        return DataTables::of($result)->make(true);

    }

    public function index(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();

        $Controller = new Controller();
        $access = $Controller->Accessdined();

        $userAccess = json_decode($access, true);

        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }

        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');

        }

        // END

        return view('payslip.table', $this->data);
    }

    // employee grid data
    public function employeepayrollgriddata()
    {

        $comp = \Session::get('companyid');
        $emp = \Session::get('emp_id');

        $wh = 'and v1.status=1 ';

        //based on superadmin load all data and remaining all indivudual data only

        $emp_data = DB::table("hr_employee_t")->where('employee_id', $emp)->get();
        $dept = json_decode($emp_data[0]->department);
        if (in_array(28, $dept)) {
            $wh .= '';
        } else {
            if ($emp != "1") {
                $wh .= "and v1.employee_id=$emp";
            }

        }


        $result = \DB::select("select * from (SELECT  hr_employee_payroll_lists.id,hr_employee_payroll_lists.employee_id,
      concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name,
      hr_employee_t.email as email,
      hr_employee_payroll_lists.month,
      hr_employee_payroll_lists.year,
      hr_employee_payroll_lists.approved_status as status,
      hr_employee_payroll_lists.date,
	  hr_employee_payroll_lists.annual_allowance,
      hr_employee_payroll_lists.basic_salary,
      hr_employee_payroll_lists.hra,
      hr_employee_payroll_lists.da,
      hr_employee_payroll_lists.pf,
      hr_employee_payroll_lists.pt,
      hr_employee_payroll_lists.esi,
      hr_employee_payroll_lists.vpf,
      hr_employee_payroll_lists.gross_salary,
      hr_employee_payroll_lists.net_salary,
      hr_employee_payroll_lists.ctc_pay as list_ctc_pay,
      hr_employee_payproposal.volunter_pf,
      look_emp_type.lookup_code as employee_type,
      hr_employee_payproposal.gross_pay,
      hr_employee_payproposal.net_pay,
      hr_employee_payproposal.ctc_pay,
      
      a_lookuplines_t.lookup_code,
      (
      CASE WHEN hr_employee_payroll_lists.approved_status = 0 THEN 'Pending' WHEN hr_employee_payroll_lists.approved_status = 1 THEN 'Approved'
                        END
      ) 
        AS approved_status,
        (
      CASE WHEN hr_employee_payproposal.esi = 0 THEN 'No' WHEN hr_employee_payproposal.esi = 1 THEN 'Yes'
                        END
      ) 
        AS esi1,
        (
      CASE WHEN hr_employee_payproposal.pf = 0 THEN 'No' WHEN hr_employee_payproposal.pf = 1 THEN 'Yes'
                        END
      ) 
        AS pf1,
        (CASE WHEN hr_employee_payproposal.volunter_pf!='NULL' THEN 'Yes' ELSE 'No' END) 
        AS vpf1,
    (
      CASE WHEN hr_employee_payproposal.pt = 0 THEN 'No' WHEN hr_employee_payproposal.pt = 1 THEN 'Yes'
                        END
      )    
        AS pt1
      FROM
        hr_employee_payroll_lists
      
                                  left join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_employee_payproposal.payroll_type
      LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id 
        left join a_lookuplines_t  as look_emp_type on look_emp_type.lookuplines_id=hr_employee_payproposal.employee_type WHERE 1 = 1 and hr_employee_payroll_lists.company_id=$comp )v1 where 1=1  $wh");

        return DataTables::of($result)->make(true);

    }

    // payslip create page
    public function generatepayslip($id = null, Request $request)
    {

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

        $payslip = explode(",", $id);
        $key = 1;
        foreach ($payslip as $key_pay => $value) {
            $payslip_id = $value;
            $this->data['key'] = $key;
            $key++;



            $paylists = \DB::select("SELECT
      hr_employee_payroll_lists.*,
      hr_employee_t.date_of_birth,
      hr_employee_t.employee_id,
      hr_employee_t.employee_number,
      hr_employee_t.first_name,
      hr_employee_t.department,
      hr_employee_t.last_name,
      hr_employee_t.last_name,
      a_lookuplines_t.lookup_code,
      hr_employee_t.employee_number,
      hr_employee_t.location_id,
      hr_employee_t.pf_no,
      hr_employee_t.esi_no,
      hr_employee_t.uan_no,
      hr_emp_salary.bank_name,
      Leave_balance_tbl.causal_leave as c_l,
      Leave_balance_tbl.sick_leave as s_l,
      Leave_balance_tbl.earn_leave as e_l,
      hr_emp_personal.pan_number,
      m_position.position,
      m_company_t.*,
                        hr_employee_t.employee_type,
                        hr_emp_salary.account_number,
                        hr_emp_salary.bank_name,
      location.location_name
      FROM
      hr_employee_payroll_lists
      JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
      LEFT JOIN m_position ON m_position.position_id = hr_employee_t.position
      LEFT JOIN m_location_t as location ON location.location_id = hr_employee_t.location_id
      LEFT JOIN m_company_t ON m_company_t.company_id = hr_employee_t.company_id
      LEFT JOIN hr_emp_salary ON hr_emp_salary.employee_id = hr_employee_t.employee_id
      LEFT JOIN hr_emp_personal ON hr_emp_personal.employee_id = hr_employee_t.employee_id
      LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
      Left Join Leave_balance_tbl on Leave_balance_tbl.employee_id=hr_employee_payroll_lists.employee_id
      WHERE hr_employee_payroll_lists.id ='$payslip_id'");


            $elogin_id = \Session::get('emp_id');

            if ($paylists[0]->employee_id != $elogin_id) {


                // login viewers track purpose start

                $emp_id = auth()->user()->id;
                $date = date('Y-m-d');
                $date_time = date('Y-m-d h:s:i');
                $url = $request->path();
                $created_at = date('Y-m-d h:s:i');
                $updated_at = date('Y-m-d h:s:i');
                $com_id = \Session::get('organization');
                $type = "Pay Slip unauthorized access";

                $existingRecord = Applicationviewers::where('emp_id', $emp_id)
                    ->where('date', $date)
                    ->where('url', $url)
                    ->first(); // Use first() to retrieve a single model instance

                if ($existingRecord) {
                    $existingRecord->update(['date_time' => $date_time]); // Call update() on the model instance

                } else {
                    $trackingData = [
                        'emp_id' => $emp_id,
                        'date' => $date,
                        'date_time' => $date_time,
                        'url' => $url,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'organization_id' => $com_id,
                        'type' => $type,
                    ];

                    Applicationviewers::create($trackingData);
                }

                $zoneWiseData = DB::table('hr_tracker_t')->get();
                // end
            }


            $payslip_arrear = \DB::SELECT("select * from hr_employee_payroll_lists where month='" . $paylists[0]->month . "' and year ='" . $paylists[0]->year . "' and id !='" . $payslip_id . "' and employee_id='" . $paylists[0]->employee_id . "'");

            if (count($payslip_arrear) > 0) {
                $this->data['basic_arrear'] = $payslip_arrear[0]->basic_salary;
                $this->data['hra_arrear'] = $payslip_arrear[0]->hra;
                $this->data['da_arrear'] = $payslip_arrear[0]->da;
                $this->data['esi_arrear'] = $payslip_arrear[0]->esi;
                $this->data['pt_arrear'] = $payslip_arrear[0]->pt;
                $this->data['gross_salary_arrear'] = $payslip_arrear[0]->gross_salary;
                $this->data['net_salary_arrear'] = $payslip_arrear[0]->net_salary;
                $this->data['esi_cutoff'] = $payslip_arrear[0]->esi_cutoff;
                $allowance_arrear = json_decode($payslip_arrear[0]->allowance);
                $allowance_arrear = collect($allowance_arrear)->map(function ($x) {
                    return (array) $x;
                })->toArray();

            } else {

                $this->data['basic_arrear'] = 0;
                $this->data['hra_arrear'] = 0;
                $this->data['da_arrear'] = 0;
                $this->data['esi_arrear'] = 0;
                $this->data['pt_arrear'] = 0;
                $this->data['gross_salary_arrear'] = 0;
                $this->data['net_salary_arrear'] = 0;
                $this->data['esi_cutoff'] = 0;


            }
            $employee_id = $paylists[0]->employee_id;
            $payroll_id = $paylists[0]->id;
            $year = $paylists[0]->year;
            $month_id = $paylists[0]->month;

            $leave_taken_query_cl = \DB::select("SELECT start_date,end_date,sum(alloted_days) as alloted_days FROM `hr_leaves_t` where  hr_leaves_t.employee_id=$employee_id and ((month(hr_leaves_t.start_date)=$month_id and year(hr_leaves_t.start_date)=$year) or (month(hr_leaves_t.end_date)=$month_id and year(hr_leaves_t.end_date)=$year))  and hr_leaves_t.leave_status='APPROVE' and hr_leaves_t.leave_type='130'");
            $leave_taken_query_sl = \DB::select("SELECT  start_date,end_date,sum(alloted_days) as alloted_days FROM `hr_leaves_t` where hr_leaves_t.employee_id=$employee_id and ((month(hr_leaves_t.start_date)=$month_id and year(hr_leaves_t.start_date)=$year) or (month(hr_leaves_t.end_date)=$month_id and year(hr_leaves_t.end_date)=$year)) and hr_leaves_t.leave_status='APPROVE' and hr_leaves_t.leave_type='131'");
            $leave_taken_query_el = \DB::select("SELECT  start_date,end_date,sum(alloted_days) as alloted_days FROM `hr_leaves_t` where hr_leaves_t.employee_id=$employee_id and ((month(hr_leaves_t.start_date)=$month_id and year(hr_leaves_t.start_date)=$year) or (month(hr_leaves_t.end_date)=$month_id and year(hr_leaves_t.end_date)=$year)) and hr_leaves_t.leave_status='APPROVE' and hr_leaves_t.leave_type='132'");
            $leave_taken_query_co = \DB::select("SELECT  start_date,end_date,sum(alloted_days) as alloted_days FROM `hr_leaves_t` where hr_leaves_t.employee_id=$employee_id and ((month(hr_leaves_t.start_date)=$month_id and year(hr_leaves_t.start_date)=$year) or (month(hr_leaves_t.end_date)=$month_id and year(hr_leaves_t.end_date)=$year)) and hr_leaves_t.leave_status='APPROVE' and hr_leaves_t.leave_type='277'");

            if (count($leave_taken_query_cl) > 0) {
                $cl_leave = $leave_taken_query_cl[0]->alloted_days;
                if (isset($cl_leave)) {
                    $this->data['cl'] = $cl_leave;
                } else {
                    $this->data['cl'] = '0';
                }
            } else {
                $this->data['cl'] = '0';
            }

            if (count($leave_taken_query_sl) > 0) {
                $sl_leave = $leave_taken_query_sl[0]->alloted_days;
                if (isset($sl_leave)) {
                    $this->data['sl'] = $sl_leave;
                } else {
                    $this->data['sl'] = '0';
                }

            } else {
                $this->data['sl'] = '0';
            }

            if (count($leave_taken_query_el) > 0) {
                $el_leave = $leave_taken_query_el[0]->alloted_days;
                if (isset($el_leave)) {
                    $this->data['el'] = $el_leave;
                } else {
                    $this->data['el'] = '0';
                }

            } else {
                $this->data['el'] = '0';
            }

            if (count($leave_taken_query_co) > 0) {
                $co_leave = $leave_taken_query_co[0]->alloted_days;
                if (isset($co_leave)) {
                    $this->data['co'] = $co_leave;
                } else {
                    $this->data['co'] = '0';
                }

            } else {
                $this->data['co'] = '0';
            }


            $this->data['pan_number'] = $paylists[0]->pan_number;
            $this->data['account_number'] = $paylists[0]->account_number;
            $this->data['bank_name'] = $paylists[0]->bank_name;
            $this->data['uan_no'] = $paylists[0]->uan_no;
            $this->data['pf_no'] = $paylists[0]->pf_no;
            $this->data['esi_no'] = $paylists[0]->esi_no;

            // department name display
            $department = json_decode($paylists[0]->department);
            $id = implode(',', $department);
            $department_id = DB::select('SELECT sub_department_name FROM `m_department_lines_t` WHERE `department_line_id` in(' . $id . ')');
            $dept = '';
            foreach ($department_id as $k => $v) {
                $dept .= ucwords($v->sub_department_name) . ",";
            }
            $dept = rtrim($dept, ",");
            $this->data['department'] = $dept;
            $employee_id = $paylists[0]->employee_id;
            // monthly attendend data 
            $month_atten = DB::table('hr_monthly_attendance')->where('employee_id', $employee_id)->where('month', $month_id)->get();
            if (count($month_atten) > 0)
                $this->data['month_attend'] = $month_atten;
            else
                $this->data['month_attend'] = "";
            // employee details
            $emp_details = DB::table('hr_employee_t')->select('hr_employee_t.*', 'm_position.position as position_name', 'm_job_title.job_title_name as job_title_name')->where('employee_id', $employee_id)->leftjoin('m_job_title', 'm_job_title.job_title_id', 'hr_employee_t.job_title')->leftjoin('m_position', 'm_position.position_id', 'hr_employee_t.position')->get();
            $leave_balance = \DB::select("SELECT *  FROM `Leave_balance_tbl` WHERE `employee_id` = $employee_id");
            $this->data['leave_balance'] = $leave_balance;
            $this->data['emp_details'] = $emp_details;
            $loc = "1";
            //   dd($loc);
            $loca_name = DB::table('m_location_t')->where('location_id', $loc[0])->get();
            $this->data['location_name_emp'] = $loca_name[0]->location_name;
            $position_id = $emp_details[0]->position;
            $company_id = $emp_details[0]->company_id;

            // payslip data
            $this->data['payslip_details'] = $payslip_details = DB::table('hr_employee_payroll_lists')->where('id', $payslip_id)->get();
            //dd($paylists[0]->employee_type);
            // allowance data
            $allowance_data_query = DB::table("m_allowance_tbl")
                ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_allowance_tbl.employee_type')
                ->select('m_allowance_tbl.allowance_name', 'm_allowance_tbl.allowance_id', 'm_allowance_tbl.type')
                ->where('m_allowance_tbl.employee_type', $paylists[0]->employee_type)->where('m_allowance_tbl.company_id', $company_id)->get();
            //dd($allowance_data_query);
            $allowance_data = json_decode($payslip_details[0]->allowance);
            //   dd($allowance_data);
            $allowance_data = collect($allowance_data)->map(function ($x) {
                return (array) $x;
            })->toArray();

            // payproposal data 
            $check_date = $payslip_details[0]->date;
            // dd($check_date);
            $check_basic_pay_created = DB::SELECT("SELECT * FROM `hr_employee_payproposal` WHERE 1=1 and((`created_at`<='$check_date' and updated_at IS NULL  and employee_id='$employee_id') or(`updated_at`<='$check_date'  and employee_id='$employee_id'))");

            $check_basic_pay_updated_before = DB::SELECT("SELECT * FROM `hr_employee_payproposal_before` WHERE `updated_at`>='$check_date'  and employee_id='$employee_id' order by id desc limit 1");
            //  dd($check_basic_pay_updated_before);
            if (count($check_basic_pay_created) > 0) {
                //dd($check_basic_pay_created);
                $this->data['emp_basic'] = $emp_basic = $check_basic_pay_created;
            } else if (count($check_basic_pay_updated_before) > 0) {

                $this->data['emp_basic'] = $emp_basic = $check_basic_pay_updated_before;
            }
            if (count($payslip_arrear) > 0) {
                $this->data['emp_basic'] = $emp_basic = DB::SELECT("SELECT * FROM `hr_employee_payproposal` WHERE 1=1  and employee_id='$employee_id'");
            }
            $allowance_data_basic = json_decode($emp_basic[0]->allowance);
            //   dd($allowance_data);
            $allowance_data_basic = collect($allowance_data_basic)->map(function ($x) {
                return (array) $x;
            })->toArray();

            $allow_print = [];
            $deduction = [];
            //   dd($allowance_data_query);
            foreach ($allowance_data_query as $key => $value) {
                // dd($allowance_data_query);

                foreach ($allowance_data as $k => $v) {

                    if (isset($v[$value->allowance_id])) {
                        if ($value->type == "Allowance") {
                            if ($v[$value->allowance_id] != 0) {
                                // dd($v[$value->allowance_id]);
                                $allow_print[$value->allowance_name]['amount'] = $v[$value->allowance_id];
                            } else {
                                $allow_print[$value->allowance_name]['amount'] = 0;
                            }
                        }
                        if ($value->type == "Deduction") {
                            if ($v[$value->allowance_id] != 0) {

                                $deduction[$value->allowance_name]['amount'] = $v[$value->allowance_id];
                            } else {
                                $deduction[$value->allowance_name]['amount'] = 0;
                            }
                        }
                    }
                }
                foreach ($allowance_data_basic as $k => $v) {
                    if (isset($v[$value->allowance_id])) {
                        if ($value->type == "Allowance") {
                            if ($v[$value->allowance_id] != 0) {

                                $allow_print[$value->allowance_name]['actual'] = $v[$value->allowance_id];
                            } else {
                                $allow_print[$value->allowance_name]['actual'] = 0;
                            }
                        }
                        if ($value->type == "Deduction") {
                            if ($v[$value->allowance_id] != 0) {

                                $deduction[$value->allowance_name]['actual'] = $v[$value->allowance_id];
                            } else {
                                $deduction[$value->allowance_name]['actual'] = 0;
                            }
                        }
                    }
                }
                //   dd($allow_print);
                if (count($payslip_arrear) > 0) {

                    foreach ($allowance_arrear as $k => $v) {

                        if (isset($v[$value->allowance_id])) {

                            if ($value->type == "Allowance") {
                                if ($v[$value->allowance_id] != 0) {

                                    $allow_print[$value->allowance_name]['arrear'] = $v[$value->allowance_id];
                                } else {
                                    $allow_print[$value->allowance_name]['arrear'] = 0;
                                }
                            }
                            if ($value->type == "Deduction") {

                                if ($v[$value->allowance_id] != 0) {

                                    $deduction[$value->allowance_name]['arrear'] = $v[$value->allowance_id];
                                } else {
                                    $deduction[$value->allowance_name]['arrear'] = 0;
                                }
                            }
                        }
                    }
                }
            }

            $this->data['allowance_data'] = $allow_print;
            $this->data['deduction_data'] = $deduction;



            $monthNum = $payslip_details[0]->month;

            $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
            $_POST['month'] = $this->data['month'] = $month = $monthName;
            $_POST['year'] = $this->data['year'] = $year = $payslip_details[0]->year;
            $total_days_month = DB::table('hr_monthly_attendance')->where('employee_id', $employee_id)->where('month', $paylists[0]->month)->where('year', $paylists[0]->year)->get();
            if (count($total_days_month) > 0) {
                $this->data['total_days'] = $total_days_month[0]->no_of_days_employee;
                $this->data['no_of_present_days'] = $total_days_month[0]->no_of_present_days;
            } else {
                $this->data['total_days'] = $payslip_details[0]->total_days;
                $this->data['no_of_present_days'] = $payslip_details[0]->attendance_days;
            }
            $this->data['date_of_birth'] = $paylists[0]->date_of_birth;
            $company_name = DB::table('m_company_t')->where('company_id', $company_id)->get();
            $this->data['company_name'] = $company_name[0]->company_name;
            $this->data['company_logo'] = $company_name[0]->company_logo_name;

            $voluter_pf = DB::table('hr_company_contribute_pf')->where('emp_id', $employee_id)->where('month', $paylists[0]->month)->where('year', $paylists[0]->year)->select(DB::raw('SUM(volunter_pf) AS volunter_pf'), DB::raw('SUM(amount_employee) AS amount_employee'))->get();
            // viki dd($voluter_pf);
            if (count($voluter_pf) > 0) {
                $this->data['volunter_pf'] = $voluter_pf[0]->volunter_pf;
                $this->data['amount_employee'] = $voluter_pf[0]->amount_employee;
            } else {
                $this->data['volunter_pf'] = 0;
                $this->data['amount_employee'] = $voluter_pf[0]->amount_employee;
            }
            $company_name = DB::table('m_company_t')->where('company_id', $company_id)->get();
            // dd($company_name);
            $this->data['company_name'] = $company_name[0]->company_name;
            $this->data['website_address'] = $company_name[0]->website_address;
            $this->data['cin_no'] = $company_name[0]->cin_no;
            $this->data['pan_no'] = $company_name[0]->pan_no;
            $location = "1";

            $professional_taxs = DB::table('m_location_t')->leftJoin('hr_professional_tax_hdr', 'hr_professional_tax_hdr.ptax_state_id', '=', 'm_location_t.state_id')->leftjoin('hr_professional_tax_lines', 'hr_professional_tax_lines.ptax_id', '=', 'hr_professional_tax_hdr.ptax_id')->select('hr_professional_tax_hdr.*', 'hr_professional_tax_lines.*')->where('m_location_t.location_id', $location)->get();

            $gross_salary = $this->data['emp_basic'][0]->gross_pay;
            if (count($professional_taxs) > 0 && $emp_basic[0]->pt == '1') {

                foreach ($professional_taxs as $index => $value) {

                    if ($gross_salary >= $value->from_value && $gross_salary <= $value->to_value) {
                        $professional_tax = $value->deduction_amount;
                        break;
                    } else {
                        $professional_tax = 0;
                    }
                }
            } else {
                $professional_tax = 0;

            }

            $this->data['professional_tax'] = $professional_tax;


            $this->data['gst_no'] = $company_name[0]->gst_no;


            $this->data['company_logo'] = $company_name[0]->company_logo_name;


            $address = $this->getLocationwiseaddress($company_name[0]->location_id);

            if ($address != 0) {
                $location_name = $address[0]->location_name;
                $this->data['location_name'] = $location_name;
                $this->data['address'] = $address[0]->address;
                $this->data['street_name'] = $address[0]->street_name;
                $this->data['location'] = $address[0]->location_name;
                $this->data['area'] = $address[0]->area;
                //GET COMPANY ADDRESS
                $this->data['company_gst_no'] = '';
                //$this->data['pan_no']=$address[0]->pan_no;
                $this->data['city'] = $this->getCity($address[0]->city_id);
                $this->data['loc_city'] = $this->getCity($address[0]->city_id);
                $this->data['state'] = $this->getState($address[0]->state_id);
                $this->data['state_no'] = $state = $this->data['state'][0]->state_id;
                $this->data['state_name'] = $state = $this->data['state'][0]->state_name;
                $this->data['state_code'] = $state = $this->data['state'][0]->state_code;
                $this->data['country'] = $this->getCountry($address[0]->country_id);
                $this->data['loc_country'] = $this->getCountry($address[0]->country_id);
                //$this->data['gst_no']=$address[0]->gst_no;
                $this->data['e_mail'] = $address[0]->e_mail;
                $this->data['state_code'] = $this->data['state'][0]->state_code;
                $this->data['pincode'] = $address[0]->pincode;
                $this->data['phone'] = $address[0]->Phone;
                $this->data['web'] = $address[0]->Web;
                $this->data['cin'] = $address[0]->CIN;
            }

            $this->data['company_address'] = $this->data['address'] . ",";
            $this->data['comp_address'] = $this->data['city'] . "," . $this->data['pincode'] . ',' . $this->data['state_name'] . "," . $this->data['country'];


            $this->data['company_address'] = ucwords($this->data['company_address']);

            if ($address == 0) {
                array_push($l_error, "Check Organisation or company Details");
            }


            // check for mail
            if (isset($_GET['mail'])) {
                $mail_p = explode(',', $_GET['mail']);
                $_GET['mails'] = $mail_p[$key_pay];

                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));

                }

                $this->data['print'] = "PRINTS";
                $this->data['employee_number'] = $paylists[0]->employee_number;


                $this->data['msg'] = $_GET['msg'];   // or $request->msg

                \Mail::send('payslip.payslip_mail', $this->data, function ($message) use ($paylists, $payslip_details) {

                    if (!empty($_GET['cc'])) {
                        $cc = explode(',', $_GET['cc']);
                        $message->cc($cc);
                    }

                    $message->to($_GET['mails']);
                    $message->from('payroll@jrkresearch.com');

                    // subject from current payslip month/year (better than $_POST)
                    $monthNum = $payslip_details[0]->month;
                    $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                    $year = $payslip_details[0]->year;

                    $message->subject("Online Payslip {$monthName}'{$year}");

                    // ✅ attach the pdf file you already generated
                    $m = str_pad($monthNum, 2, '0', STR_PAD_LEFT);
                    $empNo = $paylists[0]->employee_number;

                    $path = public_path("uploads/payslip/payslip_{$m}_{$empNo}.pdf"); // see note below
                    if (file_exists($path)) {
                        $message->attach($path);
                    }
                });


            }


        }
        if (isset($_GET['mail'])) {
            return 1;
        }

        return view('payslip.payslip', $this->data);

    }


    public function payslipfilesave(Request $request)
    {
        $m = date('m');

        if ($request->hasfile('email_attachment')) {
            File::deleteDirectory(public_path('uploads/payslip/payslip_' . $m . '_' . $_POST['emp_id']));
            // File::deleteDirectory(public_path('Uploads/purchaseorder/PO_'.$_POST['po_hdr_id']));

            foreach ($request->file('email_attachment') as $file) {
                $name = $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/payslip/payslip_' . $m . '_' . $_POST['emp_id'], $name);
                $data[] = $name;
            }
            $attachfile_name = json_encode($data);
            /*\DB::update("update p_po_hdr_t set attachment_file='".$attachfile_name."' where po_hdr_id=".$_POST['po_hdr_id']);
            return 1;*/
            return 1;
        } else {
            /*$var = File::deleteDirectory(public_path('Uploads/purchaseorder/PO_'.$_POST['po_hdr_id']));

            \DB::update("update p_po_hdr_t set attachment_file='' where po_hdr_id=".$_POST['po_hdr_id']);
            return 2;*/
            return 2;
        }

    }



    function getLocationwiseaddress($location = null)
    {
        $sql = array();
        $location = "1";
        $sql = \DB::SELECT("select * from m_location_t where location_id=" . $location . "");
        if (!empty($sql)) {
            return $sql;
        }
        return 0;
    }

    function getState($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select state_id,state_name,state_code,state_code_no from m_states_t where state_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;

        } else {
            return 0;
        }

    }

    function getCountry($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select country_id,country_name from m_countries_t where country_id =' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->country_name;
        } else {
            return 0;
        }
    }
}
