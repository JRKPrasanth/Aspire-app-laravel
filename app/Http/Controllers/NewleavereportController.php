<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use yajra\datatables\datatables;

class NewleavereportController extends Controller
{
	public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }
    
  public function index()
    {
        return view('newleaverpt.report', $this->data);
    }
  
	
     public function Leavedata(Request $request)
	{

            $start_date = $request->month;

            $month = $start_date ? date("m", strtotime($start_date)) : '0';
            $year  = $start_date ? date("Y", strtotime($start_date)) : '0';
            $p_month = '';
            $p_year = '';

            if($start_date !=''){
            if ((int)$month === 1) {
                $p_month = "12";
                $p_year  = $year - 1;
            } else {
                $p_month = str_pad((int)$month - 1, 2, '0', STR_PAD_LEFT);
                $p_year  = $year;
            }
            }
  
        $active = $request->active;

        $wh ='';
        if($active=='Yes'){
            $wh =" AND hr_employee_t.active = 'Yes'";
        }
        if($active=='No'){
            $wh =" AND hr_employee_t.active = 'No'";
        }
        if($active=='ALL'){
             $wh ="";
        }

    $SQL = "SELECT
    v3.employee_number,
    v3.first_name,
    v3.date_of_joining,
    v3.date_of_leaving,
    v3.active,
    v3.cl_eligible,
    v3.cl_taken,
    v3.cl_bal,
    v3.el_eligible,
    v3.el_taken,
    v3.el_bal,
    v3.col_eligible,
    v3.col_taken,
    v3.col_bal,
    v3.lop_taken,
    v3.sub_department_name,
    CASE WHEN v3.ocl < 12 AND $year = year(v3.date_of_joining) THEN (($month + 1) - month(v3.date_of_joining)) ELSE '$month' END  as ocl,
    v3.oel,
    v3.ocol
FROM
    (
        SELECT
            v2.*,
            (v2.cl_eligible - v2.cl_taken) as cl_bal,
            (v2.el_eligible - v2.el_taken) as el_bal,
            (v2.col_eligible - v2.col_taken) as col_bal
        FROM
            (
                SELECT
                    v1.*,
                    (v1.oel - v1.el_taken_all) as el_eligible,
                    (v1.ocol - v1.col_taken_all) as col_eligible,
                    CASE WHEN v1.ocl < 12 AND $year = year(v1.date_of_joining) THEN (($month - 1) - v1.cl_taken_all) ELSE ($month - v1.cl_taken_all)  END  as cl_eligible
                FROM
                    (
                        SELECT
                            hr_employee_t.employee_id,
                            hr_employee_t.employee_number,
                            hr_employee_t.date_of_joining,
                            hr_employee_t.date_of_leaving,
                            hr_employee_t.first_name,
                            hr_employee_t.active,
                            Leave_balance_tbl.oel,
                            Leave_balance_tbl.ocol,
                            Leave_balance_tbl.ocl,
                            dept.sub_department_name,

                        CASE 
                            WHEN INSTR(REPLACE(REPLACE(REPLACE(hr_employee_t.department, '[', ''), ']', ''), '\"', ''), ',') > 0 
                                THEN SUBSTRING_INDEX(
                                        REPLACE(REPLACE(REPLACE(hr_employee_t.department, '[', ''), ']', ''), '\"', ''), 
                                        ',', 
                                        -1
                                    )
                            ELSE REPLACE(REPLACE(REPLACE(hr_employee_t.department, '[', ''), ']', ''), '\"', '')
                        END AS department,
                            COALESCE(
                                (
                                    SELECT
                                        SUM(hl.alloted_days)
                                    FROM
                                        hr_leaves_t hl
                                    WHERE
                                        hl.leave_type = '132'
                                        AND hl.employee_id = hr_employee_t.employee_id AND hl.leave_status ='APPROVE'
                                        AND hl.start_date BETWEEN '$p_year-$p_month-26' AND '$year-$month-25'
                                ),
                                0
                            ) AS el_taken,
                            COALESCE(
                                (
                                    SELECT
                                        SUM(hl.alloted_days)
                                    FROM
                                        hr_leaves_t hl
                                    WHERE
                                        hl.leave_type = '132'
                                        AND hl.employee_id = hr_employee_t.employee_id AND hl.leave_status ='APPROVE'
                                        AND hl.start_date BETWEEN '$year-01-01' AND '$p_year-$p_month-25'
                                ),
                                0
                            ) AS el_taken_all,
                            COALESCE(
                                (
                                    SELECT
                                        SUM(hl.alloted_days)
                                    FROM
                                        hr_leaves_t hl
                                    WHERE
                                        hl.leave_type = '277'
                                        AND hl.employee_id = hr_employee_t.employee_id AND hl.leave_status ='APPROVE'
                                        AND hl.start_date BETWEEN '$year-01-01' AND '$p_year-$p_month-25'
                                ),
                                0
                            ) AS col_taken_all,
                            COALESCE(
                                (
                                    SELECT
                                        SUM(hl.alloted_days)
                                    FROM
                                        hr_leaves_t hl
                                    WHERE
                                        hl.leave_type = '130'
                                        AND hl.employee_id = hr_employee_t.employee_id AND hl.leave_status ='APPROVE'
                                        AND hl.start_date BETWEEN '$year-01-01' AND '$p_year-$p_month-25'
                                ),
                                0
                            ) AS cl_taken_all,
                            COALESCE(
                                (
                                    SELECT
                                        SUM(hl.alloted_days)
                                    FROM
                                        hr_leaves_t hl
                                    WHERE
                                        hl.leave_type = '276'
                                        AND hl.employee_id = hr_employee_t.employee_id AND hl.leave_status ='APPROVE'
                                        AND hl.start_date BETWEEN '$p_year-$p_month-26' AND '$year-$month-25'
                                ),
                                0
                            ) AS lop_taken,
                            COALESCE(
                                (
                                    SELECT
                                        SUM(hl.alloted_days)
                                    FROM
                                        hr_leaves_t hl
                                    WHERE
                                        hl.leave_type = '277'
                                        AND hl.employee_id = hr_employee_t.employee_id AND hl.leave_status ='APPROVE'
                                        AND hl.start_date BETWEEN '$p_year-$p_month-25' AND '$year-$month-26'
                                ),
                                0
                            ) AS col_taken,
                            COALESCE(
                                (
                                    SELECT
                                        SUM(hl.alloted_days)
                                    FROM
                                        hr_leaves_t hl
                                    WHERE
                                        hl.leave_type = '130'
                                        AND hl.employee_id = hr_employee_t.employee_id AND hl.leave_status ='APPROVE'
                                        AND hl.start_date BETWEEN '$p_year-$p_month-25' AND '$year-$month-26'
                                ),
                                0
                            ) AS cl_taken
                        FROM
                            hr_employee_t
                            LEFT JOIN Leave_balance_tbl ON hr_employee_t.employee_id = Leave_balance_tbl.employee_id
                            LEFT JOIN hr_leaves_t ON hr_leaves_t.employee_id = hr_employee_t.employee_id
                            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
                            LEFT JOIN m_department_lines_t dept 
    ON dept.department_line_id = 
       CASE 
           WHEN INSTR(REPLACE(REPLACE(REPLACE(hr_employee_t.department, '[', ''), ']', ''), '\"', ''), ',') > 0 
               THEN SUBSTRING_INDEX(
                       REPLACE(REPLACE(REPLACE(hr_employee_t.department, '[', ''), ']', ''), '\"', ''), 
                       ',', 
                       -1
                    )
           ELSE REPLACE(REPLACE(REPLACE(hr_employee_t.department, '[', ''), ']', ''), '\"', '')
       END
                        WHERE 1=1
                           $wh
                        GROUP BY
                            hr_employee_t.employee_id
                    ) v1
                GROUP BY
                    v1.employee_id
            ) v2
        GROUP BY
            v2.employee_id
    ) v3
GROUP BY
    v3.employee_id ";

   $results = \DB::select($SQL);

   return DataTables::of($results)->make(true);
    

}

}
