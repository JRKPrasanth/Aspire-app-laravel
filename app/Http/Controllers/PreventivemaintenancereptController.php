<?php

namespace App\Http\Controllers;

use App\expensesrpt;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PreventivemaintenancereptController extends Controller
{
     public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
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

     return view('pmreport.table',$this->data);   
    }
    
    public function breakdownindex(Request $request)
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

     return view('pmreport.breakdowntable',$this->data);   
    }

 

	public function datapreventivemaintenancereportget(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
SELECT
    CONCAT(
        w_machine_hdr_t.machine_code,
        '-',
        w_machine_hdr_t.machine_name
    ) AS machine_name,
    CONCAT(
        m_department_lines_t.sub_department_code,
        '-',
        m_department_lines_t.sub_department_name
    ) AS sub_department,
    machine_pm_detail_t.initiate_pm_id,
    machine_pm_detail_t.actual_pm_date,
    machine_pm_detail_t.move_reason,
    clearance.first_name as cleared_by,
    (CASE WHEN machine_pm_detail_t.initiate_date = '0000-00-00' THEN '' ELSE machine_pm_detail_t.initiate_date END) as  initiate_date,
    (CASE WHEN machine_pm_detail_t.postponed_date = '0000-00-00' THEN '' ELSE machine_pm_detail_t.postponed_date END) as  postponed_date,
    (CASE WHEN machine_pm_detail_t.done_on_date = '0000-00-00' THEN '' ELSE machine_pm_detail_t.done_on_date END) as  done_on_date,
    (CASE WHEN machine_pm_detail_t.shift_timing = '0000-00-00 00:00:00' THEN '' ELSE machine_pm_detail_t.shift_timing END) as  shift_timing,
    machine_pm_detail_t.machine_id,
    machine_pm_detail_t.pm_no,
     (CASE WHEN machine_pm_detail_t.allocation_type = 'engineer' AND machine_pm_detail_t.allocation_type != 'NULL' THEN
    hr_employee_t.first_name
    ELSE
    ma_agency_t.agency_name
    END) as agency, 
    (
        CASE WHEN machine_pm_detail_t.move_status = 1 THEN 'PM NOT DONE' WHEN machine_pm_detail_t.status = 0 AND machine_pm_detail_t.postpone_status = 0 AND machine_pm_detail_t.initiate_status = 0 THEN 'Yet to Initiate' WHEN machine_pm_detail_t.status = 0 AND machine_pm_detail_t.postpone_status = 1 AND machine_pm_detail_t.initiate_status = 0 THEN 'Postponed' WHEN machine_pm_detail_t.status = 1 AND machine_pm_detail_t.postpone_status = 0 AND machine_pm_detail_t.initiate_status = 1 THEN 'Yet to Engineer Allocate' WHEN machine_pm_detail_t.status = 0 AND machine_pm_detail_t.postpone_status = 0 AND machine_pm_detail_t.initiate_status = 1 THEN 'Waiting for User Clearance' WHEN machine_pm_detail_t.status = 0 AND machine_pm_detail_t.postpone_status = 1 AND machine_pm_detail_t.initiate_status = 1 THEN 'Waiting for User Clearance' WHEN machine_pm_detail_t.status = 2 THEN 'Checklist to be verified' WHEN machine_pm_detail_t.status = 3 AND pm_monthly_checking_tbl.approval_status = '' THEN 'Waiting for checklist user approval' WHEN machine_pm_detail_t.status = 3 AND pm_monthly_checking_tbl.approval_status = 'APPROVED' THEN 'Completed' 
    END
) AS staus
FROM
    machine_pm_detail_t
LEFT JOIN w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id = machine_pm_detail_t.machine_id
LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = machine_pm_detail_t.department_id
LEFT JOIN pm_monthly_checking_tbl ON pm_monthly_checking_tbl.initate_pm_id = machine_pm_detail_t.initiate_pm_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = machine_pm_detail_t.allocated_agency
LEFT JOIN ma_agency_t ON ma_agency_t.agency_id = machine_pm_detail_t.allocated_agency
LEFT JOIN hr_employee_t as clearance ON clearance.employee_id = machine_pm_detail_t.cleared_by
WHERE
    machine_pm_detail_t.machine_id IS NOT NULL AND (
        machine_pm_detail_t.actual_pm_date BETWEEN ? AND ?
    ) OR (
        machine_pm_detail_t.initiate_date > '$end_date'
    )
GROUP BY  machine_pm_detail_t.pm_no) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
	
	
	public function databreakdownmaintenancereportget(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "SELECT * from (
            SELECT
                b_maintenance_t.*,
                m_department_lines_t.sub_department_name AS department_name,
                m_breakdowntype_t.breakdown_name,
                breakdown_severity.severity_name,
                hr_employee_t.first_name,
                w_machine_hdr_t.machine_name,
                w_machine_hdr_t.machine_code
            FROM
                b_maintenance_t
            LEFT JOIN(m_department_lines_t)
            ON
                (
                    m_department_lines_t.department_line_id = b_maintenance_t.department_id
                )
            LEFT JOIN(m_breakdowntype_t)
            ON
                (
                    m_breakdowntype_t.breakdowntype_id = b_maintenance_t.break_type_id
                )
            LEFT JOIN breakdown_severity ON breakdown_severity.breakdownseverity_id = b_maintenance_t.breakdown_sevearity
            LEFT JOIN w_machine_hdr_t ON b_maintenance_t.machine_id = w_machine_hdr_t.machine_hdr_id
            left join hr_employee_t on hr_employee_t.employee_id = b_maintenance_t.issue_created_by
            WHERE
                1 = 1 and b_maintenance_t.issue_date BETWEEN ? AND ? order by b_maintenance_t.id DESC
    ) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
	
}
