<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB, Session;
use DateTime;
use Yajra\DataTables\DataTables;


class OtdailycheckreportController extends Controller
{
    public function __construct()
    {
        $this->data['pageModule'] = \Request::route()->getName();

        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();
    }

    //payrollgenerate index page load function start 
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


        return view('employeeot.report', $this->data);
    }


    public function otreportgrid(Request $request)
    {


        $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';


        $SQL = "SELECT v4.* FROM (SELECT v3.*,SUM(v3.mrng_ot_amount + v3.mrng_food_amount + v3.evng_ot_amount + v3.evng_food_amount + v3.nght_ot_amount + v3.nght_food_amount + v3.sunday_ot_amount + v3.sunday_food_amount) as total_amt,SUM(v3.mrng_ot_amount  + v3.evng_ot_amount + v3.nght_ot_amount + v3.sunday_ot_amount) as ot_amt,SUM(v3.mrng_food_amount + v3.evng_food_amount + v3.nght_food_amount + v3.sunday_food_amount) as food_amt  FROM (SELECT v2.*,
    TIME_FORMAT(SEC_TO_TIME(
    TIME_TO_SEC(v2.mrng_OT) +
    TIME_TO_SEC(v2.evng_OT) +
    TIME_TO_SEC(v2.nght_OT) +
    TIME_TO_SEC(v2.sunday_OT) 
    ),'%H:%i') AS overall_OT_hrs,
       CASE 
           WHEN v2.mrng_OT != '00:00' AND v2.evng_OT != '00:00' AND v2.nght_OT != '00:00' THEN 'Mrng/Evg/Night'
           WHEN v2.mrng_OT != '00:00' AND v2.evng_OT != '00:00' THEN 'Mrng/Evg'
           WHEN v2.mrng_OT != '00:00' AND v2.nght_OT != '00:00' THEN 'Mrng/Night'
           WHEN v2.mrng_OT != '00:00' THEN 'Morning'
           WHEN v2.evng_OT != '00:00' THEN 'Evening'
           WHEN v2.nght_OT != '00:00' THEN 'Night'
           WHEN v2.sunday_OT != '00:00' THEN 'Sunday'
           ELSE NULL 
       END AS OT_type,
dept.sub_department_name,
       
       COALESCE(hr_ot_amount_mrng.ot_amount, 0) AS mrng_ot_amount,
       COALESCE(hr_ot_amount_mrng.food_amount, 0) AS mrng_food_amount,
       COALESCE(hr_ot_amount_evng.ot_amount, 0) AS evng_ot_amount,
       COALESCE(hr_ot_amount_evng.food_amount, 0) AS evng_food_amount,
       COALESCE(hr_ot_amount_nght.ot_amount, 0) AS nght_ot_amount,
       COALESCE(hr_ot_amount_nght.food_amount, 0) AS nght_food_amount,
    CASE 
        WHEN v2.day = 'Sunday' 
            AND v2.sunday_OT != '00:00' 
            AND v2.date NOT IN (SELECT start_date FROM hr_compoff_t WHERE hr_compoff_t.employee_id = v2.employee_id AND leave_status ='APPROVE') 
        THEN COALESCE(hr_ot_amount_sunday.ot_amount, 0) 
        ELSE 0 
    END AS sunday_ot_amount,

CASE 
         WHEN v2.day = 'Sunday' 
         AND TIME(v2.check_in) < '06:20:00' 
         AND v2.date NOT IN (SELECT start_date FROM hr_compoff_t WHERE hr_compoff_t.employee_id = v2.employee_id AND leave_status ='APPROVE') 
    THEN 50 
    ELSE 0 
END AS sunday_food_amount

FROM (
    SELECT v1.*, 
 TIME_FORMAT(
            IF(
                v1.day != 'Sunday' AND TIME(v1.check_out) > '16:00:00' ,
                MAKETIME(
                    HOUR(v1.early_in) + IF(MINUTE(v1.early_in) > 30,
                    1,
                    0),
                    0,
                    0
                ),
                '00:00:00'
            ),
            '%H:%i'
        ) AS mrng_OT,
       
        TIME_FORMAT(
            IF(
                v1.day != 'Sunday' AND REPLACE(REPLACE(REPLACE(department, '[\"', ''), '\"]', ''), '\"', '') != '111' AND v1.late_out < '03:00' AND v1.late_out > '01:00',
                ADDTIME(
                    SUBTIME('02:00', v1.late_out),
                    v1.late_out
                ),
                '00:00'
            ),
            '%H:%i'
        ) AS evng_OT,

    TIME_FORMAT(
    IF(
        REPLACE(REPLACE(REPLACE(department, '[\"', ''), '\"]', ''), '\"', '') = '111'
        AND v1.late_out > '00:00',
        SEC_TO_TIME(
            3600 * ROUND(TIME_TO_SEC(v1.late_out) / 3600)
        ),
        IF(
            v1.day != 'Sunday' AND v1.late_out > '03:00' AND v1.late_out < '05:00',
            ADDTIME(
                SUBTIME('04:00', v1.late_out),
                v1.late_out
            ),
            '00:00:00'
        )
    ),
    '%H:%i'
) AS nght_OT,
        
        TIME_FORMAT(
            IF(
                v1.day = 'Sunday',
                SEC_TO_TIME(
                    ROUND(
                        TIME_TO_SEC(
                            TIMEDIFF(v1.check_out, v1.check_in)
                        ) / 3600
                    ) * 3600
                ),
                '00:00'
            ),
            '%H:%i'
        ) AS sunday_OT


    FROM (
        SELECT 
            hr_employee_t.employee_id,
            hr_employee_t.employee_number,
            hr_employee_t.first_name,
            
            CASE 
    WHEN INSTR(REPLACE(REPLACE(REPLACE(hr_employee_t.department, '[', ''), ']', ''), '\"', ''), ',') > 0 
        THEN SUBSTRING_INDEX(REPLACE(REPLACE(REPLACE(hr_employee_t.department, '[', ''), ']', ''), '\"', ''), ',', -1)
    ELSE REPLACE(REPLACE(REPLACE(hr_employee_t.department, '[', ''), ']', ''), '\"', '')
END AS department,
            hr_employee_t.job_title,
            m_job_title.job_title_name,
            hr_employee_t.position,  
            hr_employee_t.employee_type,
            hr_employee_t.group_type,
            hr_emp_attendence.atten_date AS DATE,
            CASE WHEN hr_emp_attendence.atten_date IN(
        SELECT
            DATE
        FROM
            hr_holiday_t
        ) THEN 'Sunday' WHEN DAYOFWEEK(hr_emp_attendence.atten_date) = 7 AND DAY(hr_emp_attendence.atten_date) BETWEEN 8 AND 14 THEN 'Sunday' ELSE DAYNAME(hr_emp_attendence.atten_date)
        END AS day,
            hr_emp_attendence.check_in,
            hr_emp_attendence.check_out,
            TIMEDIFF(hr_emp_attendence.check_out, hr_emp_attendence.check_in) AS wrk_hrs,
            TIME_FORMAT(
            IF(
                TIME_TO_SEC(
                TIMEDIFF(
                    CASE 
                    WHEN REPLACE(REPLACE(REPLACE(department, '[\"', ''), '\"]', ''), '\"', '') = '111' THEN '08:00:00'
                    WHEN REPLACE(REPLACE(REPLACE(department, '[\"', ''), '\"]', ''), '\"', '') = ('53,95') THEN '08:30:00'
                    ELSE '09:30:00'
                    END,
                    TIME_FORMAT(hr_emp_attendence.check_in, '%H:%i:%s')
                )
                ) < 0,
                '00:00:00',
                TIMEDIFF(
                CASE 
                    WHEN REPLACE(REPLACE(REPLACE(department, '[\"', ''), '\"]', ''), '\"', '') = '111' THEN '08:00:00'
                    WHEN REPLACE(REPLACE(REPLACE(department, '[\"', ''), '\"]', ''), '\"', '') = ('53,95') THEN '08:30:00'
                    ELSE '09:30:00'
                END,
                TIME_FORMAT(hr_emp_attendence.check_in, '%H:%i:%s')
                )
            ),
            '%H:%i'
            ) AS early_in,

            TIME_FORMAT(
                IF(
                    TIME_TO_SEC(
                        TIMEDIFF(
                            TIME_FORMAT(hr_emp_attendence.check_out, '%H:%i'),
                            CASE 
                                WHEN REPLACE(REPLACE(REPLACE(department, '[\"', ''), '\"]', ''), '\"', '') = '111' THEN '20:00'
                                ELSE '18:00'
                            END
                        )
                    ) < 0,
                    '00:00',
                    TIMEDIFF(
                        TIME_FORMAT(hr_emp_attendence.check_out, '%H:%i'),
                        CASE 
                            WHEN REPLACE(REPLACE(REPLACE(department, '[\"', ''), '\"]', ''), '\"', '') = '111' THEN '20:00'
                            ELSE '18:00'
                        END
                    )
                ),
                '%H:%i'
            ) AS late_out
        FROM `hr_employee_t`
        LEFT JOIN hr_emp_attendence ON hr_emp_attendence.emp_id = hr_employee_t.employee_number
        LEFT JOIN hr_compoff_t ON hr_compoff_t.employee_id = hr_employee_t.employee_id
        LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_employee_t.job_title
        WHERE hr_employee_t.ot_formula = '1' 
              AND hr_employee_t.active = 'yes' 
              AND hr_emp_attendence.atten_date BETWEEN '$start_date' AND '$end_date') v1
) v2  


LEFT JOIN hr_ot_amount_t hr_ot_amount_mrng 
       ON FIND_IN_SET(hr_ot_amount_mrng.department, v2.department) 
       AND hr_ot_amount_mrng.job_tittle = v2.job_title 
       AND hr_ot_amount_mrng.ot_type = 'Morning' 
       AND v2.mrng_OT = hr_ot_amount_mrng.ot_hrs

LEFT JOIN hr_ot_amount_t hr_ot_amount_evng 
       ON FIND_IN_SET(hr_ot_amount_evng.department, v2.department) 
       AND hr_ot_amount_evng.job_tittle = v2.job_title 
       AND hr_ot_amount_evng.ot_type = 'Evening' 
       AND v2.evng_OT = hr_ot_amount_evng.ot_hrs

LEFT JOIN hr_ot_amount_t hr_ot_amount_nght 
       ON FIND_IN_SET(hr_ot_amount_nght.department, v2.department) 
       AND hr_ot_amount_nght.job_tittle = v2.job_title 
       AND hr_ot_amount_nght.ot_type = 'Night' 
       AND v2.nght_OT = hr_ot_amount_nght.ot_hrs
       
LEFT JOIN hr_ot_amount_t hr_ot_amount_sunday 
       ON FIND_IN_SET(hr_ot_amount_sunday.department, v2.department) 
       AND hr_ot_amount_sunday.job_tittle = v2.job_title 
       AND hr_ot_amount_sunday.ot_type = 'Sunday' 
       AND v2.sunday_OT = hr_ot_amount_sunday.ot_hrs
LEFT JOIN m_department_lines_t AS dept ON dept.department_line_id = v2.department       
GROUP BY v2.date, v2.employee_id)v3 GROUP BY v3.date, v3.employee_id)v4 GROUP BY v4.date, v4.employee_id  HAVING v4.total_amt != '0'
 ORDER BY `v4`.`check_in` ASC";

        $data = \DB::select($SQL);

        return DataTables::of($data)->make(true);

    }


}