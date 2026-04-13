<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Carbon\CarbonPeriod;


class HrmsHomeController extends Controller
{

  public function index(Request $request)
  {

    $this->data['group'] =\Session::get('groupname');
    $this->data['pageMethod'] = "hrmshome";
    $emp_id = Session::get('emp_id');
	$holiday = date('Y');
    $month = date('m');  
	$today = date('Y-m-d');  
	$this_year = "$holiday-01-01";
    $report_from = request('report_from', date('Y-01-01'));
    $report_to   = request('report_to', date('Y-m-d'));


     $this->data['emp_search']=$this->jcustomselecttool("hr_employee_t","employee_id","employee_number|first_name","","and active='yes' and employee_id!='1'");

    if( $request->input('emp_search')!=''){
    $emp_id = $request->input('emp_search');
    } else{ 
    $emp_id =$emp_id;
    }


	// leave chart
    $this->data['leave_details'] = \DB::select("SELECT v1.type AS name, v1.leave_type AS y FROM (SELECT 'Casual' AS type, 
                        (ocl - (
                            SELECT COALESCE(SUM(alloted_days), 0)
                            FROM hr_leaves_t
                            WHERE leave_type = '130'
                                AND employee_id = '$emp_id'
                                AND DATE(start_date) BETWEEN '$this_year' AND '$today'
                                AND leave_status = 'APPROVE'
                        )) AS leave_type
                    FROM Leave_balance_tbl
                    WHERE employee_id = '$emp_id'

                    UNION ALL

                    SELECT 'Earn' AS type, earn_leave AS leave_type
                    FROM Leave_balance_tbl
                    WHERE employee_id = '$emp_id'

                    UNION ALL

                    SELECT 'Combo' AS type, comp_off_leave AS leave_type
                    FROM Leave_balance_tbl
                    WHERE employee_id = '$emp_id'

                    UNION ALL

                    SELECT 'Sick' AS type, sick_leave AS leave_type
                    FROM Leave_balance_tbl
                    WHERE employee_id = '$emp_id')v1 HAVING v1.leave_type !='0'");


    $leveData = [];
    foreach ($this->data['leave_details'] as $item) {
      $leveData[] = [
        'name' => $item->name,
        'y' => $item->y,
      ];
    }

    $this->data['leave_details'] = json_encode($leveData);

// holiday list
    $this->data['holiday_detail'] = \DB::select("SELECT * FROM `hr_holiday_t` WHERE YEAR(`date`) = $holiday order by date ASC ");

// personal details
    $user_details = DB::table("hr_employee_t")
      ->leftjoin('m_company_t', 'm_company_t.company_id', '=', 'hr_employee_t.company_id')
      ->leftjoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'hr_employee_t.organisation')
      ->leftjoin('m_location_t', 'm_location_t.location_id', '=', 'hr_employee_t.location_id')
      ->leftjoin('m_job_title', 'm_job_title.job_title_id', '=', 'hr_employee_t.job_title')
      ->leftJoin('m_area_t', function ($join) {
        $join->on(DB::raw('CAST(JSON_UNQUOTE(JSON_EXTRACT(hr_employee_t.med_rep_area, \'$\[0\]\')) AS SIGNED)'), '=', 'm_area_t.area_id'); })
      ->leftjoin('hr_employee_t as registration', 'hr_employee_t.reporting_manager', '=', 'registration.employee_id')
      ->leftjoin('hr_emp_personal', 'hr_emp_personal.employee_id', '=', 'hr_employee_t.employee_id')
      ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_employee_t.employee_type')
      ->leftjoin('m_position', 'm_position.position_id', '=', 'hr_employee_t.position')
      ->select(
        'm_company_t.company_name',
        'm_organizations_t.organization_name',
        'm_location_t.location_name',
        'm_job_title.job_title_name',
        'm_position.position',
        'hr_employee_t.employee_number',
        'hr_employee_t.reporting_manager',
        'hr_employee_t.date_of_birth',
        'm_job_title.job_title_name',
        'hr_employee_t.date_of_joining',
        'hr_employee_t.years_of_experience',
        'hr_employee_t.first_name',
        'hr_employee_t.active',
        'hr_employee_t.last_name',
        'hr_employee_t.email',
        'hr_employee_t.photo',
        'm_area_t.area_name',
        'hr_emp_personal.date_of_birth',
        'hr_employee_t.work_telephone_number',
        'hr_employee_t.prefix',
        'hr_employee_t.email',
        'a_lookuplines_t.lookup_code as employeement_status_name',
        'hr_employee_t.biometric_empno',
        'hr_employee_t.department',
        'hr_emp_personal.marital_status',
        DB::raw('ROUND(DATEDIFF(CURDATE(), hr_emp_personal.date_of_birth) / 365,0) AS age'),
        'hr_emp_personal.blood_group',
        'hr_employee_t.work_telephone_number',
        'hr_employee_t.years_of_experience',
        'hr_employee_t.date_of_joining',
        'hr_employee_t.date_of_leaving',
        'hr_employee_t.employee_id',
        'hr_employee_t.employee_number',
        'hr_employee_t.work_telephone_number',
        'hr_employee_t.work_telephone_number',
        'registration.first_name as reporting_manager_name'
      )
      ->where('hr_employee_t.employee_id', $emp_id)->get();

    $department = json_decode($user_details[0]->department);
    $dept = "";
    foreach ($department as $key => $val) {
      $data_dept = DB::table("m_department_lines_t")->select('m_department_lines_t.sub_department_name', 'm_department_lines_t.sub_department_code')->where('m_department_lines_t.department_line_id', '=', $val)->get();
      $dept .= $data_dept[0]->sub_department_name . ",";
    }
    $dept = rtrim($dept, ',');
    $user_details[0]->department_name = $dept;
    $this->data['user_details'] = $user_details;



    $contact_details = DB::table("hr_emp_contact")
      ->leftjoin('m_countries_t', 'm_countries_t.country_id', '=', 'hr_emp_contact.permanent_country')
      ->leftjoin('m_countries_t as m_current_country', 'm_current_country.country_id', '=', 'hr_emp_contact.current_country')
      ->leftjoin('m_states_t', 'm_states_t.state_id', '=', 'hr_emp_contact.permanent_state')
      ->leftjoin('m_states_t as m_current_state', 'm_current_state.state_id', '=', 'hr_emp_contact.current_state')
      ->leftjoin('m_cities_t', 'm_cities_t.city_id', '=', 'hr_emp_contact.permanent_city')
      ->leftjoin('m_cities_t  as m_current_cities', 'm_current_cities.city_id', '=', 'hr_emp_contact.current_city')
      ->select('current_postal_code', 'm_current_state.state_name as current_state_name', 'm_states_t.state_name', 'm_current_cities.city_name as current_city_name', 'm_cities_t.city_name', 'm_current_country.country_name as current_countyname', 'm_countries_t.country_name', 'hr_emp_contact.permanent_flat_no', 'hr_emp_contact.current_flat_no', 'hr_emp_contact.current_street', 'hr_emp_contact.permanent_street', 'hr_emp_contact.permanent_street_address', 'hr_emp_contact.current_street_address', 'hr_emp_contact.permanent_postal_code')->where('hr_emp_contact.employee_id', $emp_id)->get();

    if ($contact_details->isEmpty()) {
      $contact_details[0] = (object) array();
      $contact_details[0]->permanent_street_address = '';
      $contact_details[0]->current_street_address = '';
    } else {
      $contact_details = $contact_details;

      $contact_details[0]->permanent_street_address = $contact_details[0]->permanent_flat_no . $contact_details[0]->permanent_street . $contact_details[0]->permanent_street_address . $contact_details[0]->country_name . "," . $contact_details[0]->state_name . "," . $contact_details[0]->city_name . "-" . $contact_details[0]->permanent_postal_code;
      $contact_details[0]->current_street_address = $contact_details[0]->current_flat_no . $contact_details[0]->current_street . $contact_details[0]->current_street_address . $contact_details[0]->current_countyname . "," . $contact_details[0]->current_state_name . "," . $contact_details[0]->current_city_name . "-" . $contact_details[0]->current_postal_code;
    }

    $this->data['contact_details'] = $contact_details;
	  
	  
// day roster calender	  
	  
    $this_year = request('from', Carbon::now()->startOfYear()->toDateString()); 
    $today     = request('to', Carbon::now()->toDateString());

    // 1) Get employee number
    $emp_number = DB::table("hr_employee_t")
        ->where('employee_id', $emp_id)
        ->value('employee_number');

    // 2) Prefetch all attendance rows for the range, key by date
    $attendance = DB::table('hr_emp_attendence')
        ->select('atten_date', 'check_in', 'check_out')
        ->where('emp_id', $emp_number)
        ->whereBetween('atten_date', [$this_year, $today])
        ->get()
        ->keyBy(fn($r) => Carbon::parse($r->atten_date)->toDateString());

    // 3) Prefetch APPROVED leaves for the range
    $leaves = DB::table('hr_leaves_t as l')
        ->leftJoin('a_lookuplines_t as a', 'a.lookuplines_id', '=', 'l.leave_type')
        ->select('l.start_date', 'l.end_date', 'l.leave_mode', 'a.lookup_code')
        ->where('l.employee_id', $emp_id)
        ->where('l.leave_status', 'APPROVE')
        ->where(function ($q) use ($this_year, $today) {
            $q->whereBetween('l.start_date', [$this_year, $today])
              ->orWhereBetween('l.end_date', [$this_year, $today])
              ->orWhere(function($qq) use ($this_year, $today) {
                    $qq->where('l.start_date', '<=', $this_year)
                       ->where('l.end_date', '>=', $today);
              });
        })
        ->get();

    // Map leave ranges to quick lookup
    $leaveDates = [];  // 'Y-m-d' => ['code' => 'CL', 'half' => true/false]
    foreach ($leaves as $lv) {
        $code = strtoupper($lv->lookup_code); // CASUAL LEAVE, ON-DUTY, etc.
        $half = ($lv->leave_mode == 134);     // your half-day flag
        $period = CarbonPeriod::create(Carbon::parse($lv->start_date), Carbon::parse($lv->end_date));
        foreach ($period as $d) {
            $leaveDates[$d->toDateString()] = [
                'code' => $code,
                'half' => $half
            ];
        }
    }

    // 4) Prefetch holidays
    $emp = DB::table('hr_employee_t')->where('employee_id', $emp_id)->first();
    $holidays = DB::table('hr_holiday_t')
        ->whereBetween('date', [$this_year, $today])
        ->where('company_id', $emp->company_id ?? 1)
        ->where('active', 'Yes')
        ->get()
        ->keyBy('date');

    // 5) Color map
    $colorMap = [
        'P'     => '#198754', // green
        'P(H)'  => '#198754',
        'P(OD)' => '#198754',
        'A'     => '#dc3545', // red
        'WO'    => '#0d6efd', // blue
        'H'     => '#6c757d', // gray
        'CL'    => '#ffc107',
        'SL'    => '#ffc107',
        'EL'    => '#ffc107',
        'C-OFF' => '#0dcaf0',
        ''      => '#adb5bd'
    ];

    // 6) Iterate every day and build events
    $events = [];
    $period = CarbonPeriod::create(Carbon::parse($this_year), Carbon::parse($today));

    foreach ($period as $date) {
        $d = $date->toDateString();
        $att = $attendance->get($d);
        $status = '';
        $leave_type = null;
        $in_time = $out_time = $working_hours = null;

        if ($att) {
            $check_in = $att->check_in;
            $check_out = $att->check_out;

            if ($check_in && $check_out) {
                $status = 'P';
                $start = Carbon::parse($check_in);
                $end   = Carbon::parse($check_out);
                $working_hours = $start->diffInHours($end) . ' hrs ' . $start->diff($end)->format('%I mins');
                $in_time = $start->format('h:i A');
                $out_time = $end->format('h:i A');
            } else {
                // (No or equal check_in/out)
                $status = 'A'; // you can expand like in your heavy logic
            }

            // if a half day leave is also there, you can override:
            if (isset($leaveDates[$d]) && $leaveDates[$d]['half']) {
                $code = $leaveDates[$d]['code'];
                if ($code == 'ON-DUTY') {
                    $status = 'P(OD)';
                } else {
                    $status = 'P(H)'; // show code if you like: "P(H)CL"
                }
                $leave_type = $code;
            }
        } else {
            // No attendance row
            if (isset($leaveDates[$d])) {
                $code = $leaveDates[$d]['code'];
                $leave_type = $code;
                if ($code == 'ON-DUTY') {
                    $status = 'P(OD)';
                } else if ($code == 'CASUAL LEAVE') {
                    $status = 'CL';
                } else if ($code == 'SICK LEAVE') {
                    $status = 'SL';
                } else if ($code == 'EARN LEAVE') {
                    $status = 'EL';
                } else if ($code == 'COMP-OFF') {
                    $status = 'C-OFF';
                } else {
                    $status = $code; // fallback
                }
                if ($leaveDates[$d]['half'] && $status == 'P') {
                    $status = 'P(H)';
                }
            } else if ($holidays->has($d)) {
                $status = 'H';
            } else if (strtoupper($date->format('D')) == 'SUN') {
                $status = 'WO';
            } else {
                $status = 'A';
            }
        }

        $events[] = [
            'title'          => $status,
            'start'          => $d,
            'status'         => $status,
            'backgroundColor'=> $colorMap[$status] ?? '#6c757d',
            'borderColor'    => $colorMap[$status] ?? '#6c757d',
            'extendedProps'  => [
                'in_time'       => $in_time,
                'out_time'      => $out_time,
                'leave_type'    => $leave_type,
            ]
        ];
    }


 // =====================================
// Executive KPI Summary
// =====================================

$kpi = DB::selectOne("
    SELECT 
        SUM(pl.gross_salary) AS total_gross,
        SUM(pl.net_pay) AS total_net,
        SUM(jcl.achieved) AS total_achieved
    FROM (
        SELECT employee_id,
               SUM(gross_salary) gross_salary,
               SUM(net_pay) net_pay
        FROM hr_employee_payroll_lists
        WHERE STR_TO_DATE(CONCAT(year,'-',month,'-01'),'%Y-%m-%d')
              BETWEEN ? AND ?
        GROUP BY employee_id
    ) pl
    LEFT JOIN (
        SELECT rjcl.product_id employee_id,
               SUM(rjcl.total) achieved
        FROM r_job_cost_lines_tbl rjcl
        LEFT JOIN r_job_cost_hdr_tbl rjch
            ON rjch.r_job_cost_hdr_id = rjcl.r_job_cost_hdr_id
        WHERE rjch.job_date BETWEEN ? AND ?
        GROUP BY rjcl.product_id
    ) jcl ON jcl.employee_id = pl.employee_id
", [$report_from,$report_to,$report_from,$report_to]);

$overall_percent = ($kpi->total_gross > 0)
    ? round(($kpi->total_achieved / $kpi->total_gross) * 100,2)
    : 0;

$this->data['kpi'] = $kpi;
$this->data['overall_percent'] = $overall_percent;




    // ===============================
//7 Employee Wise Gross vs Achievement
// ===============================

$gr_ach_report = DB::select("SELECT 
        dp.sub_department_name AS department,
        hr.employee_id,
        CONCAT(hr.first_name,' ',IFNULL(hr.last_name,'')) AS employee_name,

        DATE_FORMAT(pl.pay_month, '%b-%Y') AS month_year,
        YEAR(pl.pay_month) AS year,
        MONTH(pl.pay_month) AS month,

        IFNULL(pl.gross_salary,0) AS gross_salary,
        IFNULL(pl.net_pay,0) AS net_pay,
        IFNULL(jcl.achieved,0) AS achieved,

        ROUND(
            (IFNULL(jcl.achieved,0) / NULLIF(pl.gross_salary,0)) * 100,
            2
        ) AS contribution_percent

    FROM hr_employee_t hr

    LEFT JOIN m_department_lines_t dp 
        ON JSON_UNQUOTE(JSON_EXTRACT(hr.department, '$[0]')) = dp.department_line_id 

    LEFT JOIN (
        SELECT 
            employee_id,
            STR_TO_DATE(CONCAT(year,'-',month,'-01'), '%Y-%m-%d') AS pay_month,
            SUM(gross_salary) AS gross_salary,
            SUM(net_pay) AS net_pay
        FROM hr_employee_payroll_lists
        GROUP BY employee_id, year, month
    ) pl 
        ON pl.employee_id = hr.employee_id
        AND pl.pay_month BETWEEN ? AND ?

    LEFT JOIN (
        SELECT 
            rjcl.product_id AS employee_id,
            YEAR(rjch.job_date) AS year,
            MONTH(rjch.job_date) AS month,
            SUM(rjcl.total) AS achieved
        FROM r_job_cost_lines_tbl rjcl
        LEFT JOIN r_job_cost_hdr_tbl rjch
            ON rjch.r_job_cost_hdr_id = rjcl.r_job_cost_hdr_id
        WHERE rjch.job_date BETWEEN ? AND ? AND rjcl.type = 'EMPLOYEE'
        GROUP BY rjcl.type, rjcl.product_id, YEAR(rjch.job_date), MONTH(rjch.job_date)
    ) jcl 
        ON jcl.employee_id = hr.employee_id
        AND jcl.year = YEAR(pl.pay_month)
        AND jcl.month = MONTH(pl.pay_month)

    WHERE hr.active = 'yes'
    AND pl.pay_month IS NOT NULL AND dp.sub_department_name!='board'

    ORDER BY dp.sub_department_name, hr.employee_id, pl.pay_month
", [
    $report_from,
    $report_to,
    $report_from,
    $report_to
]);

$this->data['gr_ach_report'] = $gr_ach_report;
$this->data['report_from'] = $report_from;
$this->data['report_to'] = $report_to;




// =======================================================
// Department Monthly Graph Data (Chart)
// =======================================================

$dept_chart_raw = DB::select("
    SELECT 
        dp.sub_department_name AS department,
        DATE_FORMAT(rjch.job_date,'%Y-%m') AS month_key,
        DATE_FORMAT(rjch.job_date,'%b-%Y') AS month_label,
        SUM(rjcl.total) AS achieved
    FROM r_job_cost_lines_tbl rjcl
    LEFT JOIN r_job_cost_hdr_tbl rjch
        ON rjch.r_job_cost_hdr_id = rjcl.r_job_cost_hdr_id
    LEFT JOIN hr_employee_t hr
        ON hr.employee_id = rjcl.product_id
    LEFT JOIN m_department_lines_t dp 
        ON JSON_UNQUOTE(JSON_EXTRACT(hr.department, '$[0]')) = dp.department_line_id 
    WHERE rjch.job_date BETWEEN ? AND ? AND rjcl.type = 'EMPLOYEE'
    AND hr.active = 'yes'
    GROUP BY dp.sub_department_name, month_key
    ORDER BY month_key
", [$report_from, $report_to]);

// -------------------------------------------
// Convert to Chart Friendly Structure
// -------------------------------------------

$months = [];
$departments = [];

foreach ($dept_chart_raw as $row) {
    $months[$row->month_key] = $row->month_label;
    $departments[$row->department][$row->month_key] = $row->achieved;
}

// Ensure all months exist for all departments
$monthKeys = array_keys($months);
$chartSeries = [];

foreach ($departments as $dept => $values) {
    $data = [];
    foreach ($monthKeys as $m) {
        $data[] = $values[$m] ?? 0;
    }

    $chartSeries[] = [
        'name' => $dept,
        'data' => $data
    ];
}

$this->data['chart_months'] = array_values($months);
$this->data['chart_series'] = json_encode($chartSeries);


$salary_vs_ach_raw = DB::select("
    SELECT 
        DATE_FORMAT(rjch.job_date,'%Y-%m') AS month_key,
        DATE_FORMAT(rjch.job_date,'%b-%Y') AS month_label,
        SUM(rjcl.total) AS achieved
    FROM r_job_cost_lines_tbl rjcl
    LEFT JOIN r_job_cost_hdr_tbl rjch
        ON rjch.r_job_cost_hdr_id = rjcl.r_job_cost_hdr_id
    WHERE rjch.job_date BETWEEN ? AND ?
    GROUP BY month_key
    ORDER BY month_key
", [$report_from,$report_to]);

$salaryMonths = [];
$achievedData = [];

foreach($salary_vs_ach_raw as $row){
    $salaryMonths[] = $row->month_label;
    $achievedData[] = (float)$row->achieved;
}

$this->data['salaryMonths'] = $salaryMonths;
$this->data['achievedData'] = $achievedData;


// ============================================
// Department Contribution Ranking
// ============================================

$dept_rank_raw = DB::select("
    SELECT 
        dp.sub_department_name AS department,
        SUM(rjcl.total) AS achieved
    FROM r_job_cost_lines_tbl rjcl
    LEFT JOIN r_job_cost_hdr_tbl rjch
        ON rjch.r_job_cost_hdr_id = rjcl.r_job_cost_hdr_id
    LEFT JOIN hr_employee_t hr
        ON hr.employee_id = rjcl.product_id
    LEFT JOIN m_department_lines_t dp 
        ON JSON_UNQUOTE(JSON_EXTRACT(hr.department,'$[0]')) = dp.department_line_id
    WHERE rjch.job_date BETWEEN ? AND ?
    AND hr.active = 'yes'
    GROUP BY dp.sub_department_name
    ORDER BY achieved DESC
", [$report_from,$report_to]);

$deptCategories = [];
$deptAchieved = [];

foreach($dept_rank_raw as $row){
    $deptCategories[] = $row->department ?? 'Unknown';
    $deptAchieved[] = (float)$row->achieved;
}

$this->data['deptCategories'] = $deptCategories;
$this->data['deptAchieved'] = $deptAchieved;


$top_performers = DB::select("
    SELECT 
        CONCAT(hr.first_name,' ',IFNULL(hr.last_name,'')) AS employee,
        SUM(rjcl.total) AS achieved
    FROM r_job_cost_lines_tbl rjcl
    LEFT JOIN r_job_cost_hdr_tbl rjch
        ON rjch.r_job_cost_hdr_id = rjcl.r_job_cost_hdr_id
    LEFT JOIN hr_employee_t hr
        ON hr.employee_id = rjcl.product_id
    WHERE rjch.job_date BETWEEN ? AND ? AND rjcl.type = 'EMPLOYEE'
    GROUP BY hr.employee_id
    ORDER BY achieved DESC
    LIMIT 5
", [$report_from,$report_to]);

$this->data['top_performers'] = $top_performers; 
	  
	  
    return view('otherdashboard.hrmshome',[
        'events' => $events,
        'legend' => $colorMap
    ], $this->data);

  }
	


}