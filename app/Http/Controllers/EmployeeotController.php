<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB, Session;
use DateTime;
use Yajra\DataTables\DataTables;


class EmployeeotController extends Controller
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
        // restrict menu illegal entry purpose - VIGNESH M

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

        $this->data['groupname'] = \Session::get('groupname');

        return view('employeeot.table', $this->data);
    }


    public function generateot(Request $request)
    {

        // Retrieve query parameters
        $month = $request->input('month');
        $year = $request->input('year');
        $pre_year = $year - 1;

        if ($month == "1") {
            $pre_month = "12";
            $year = $year;
            $pre_year = $pre_year;
        } else {
            $pre_month = $month - 1;
            $pre_year = $year;
            $year = $year;
        }

        $start_date = $pre_year . '-' . str_pad($pre_month, 2, '0', STR_PAD_LEFT) . '-26';
        $end_date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-25';
        // dd($end_date);
        $loc = "1";
        $compy = \Session::get('companyid');
        $org_id = \Session::get('organization');
        $emp_id = \Session::get('id');


        $ot_cal = \DB::select("SELECT
    v4.*
FROM
    (
    SELECT
        v3.*,
        SUM(
            v3.mrng_ot_amount + v3.mrng_food_amount + v3.evng_ot_amount + v3.evng_food_amount + v3.nght_ot_amount + v3.nght_food_amount + v3.sunday_ot_amount + v3.sunday_food_amount
        ) AS total_amt,
        SUM(
            v3.mrng_ot_amount + v3.evng_ot_amount + v3.nght_ot_amount + v3.sunday_ot_amount
        ) AS ot_amt,
        SUM(
            v3.mrng_food_amount + v3.evng_food_amount + v3.nght_food_amount + v3.sunday_food_amount
        ) AS food_amt
    FROM
        (
        SELECT
            v2.*,
            TIME_FORMAT(
                SEC_TO_TIME(
                    TIME_TO_SEC(v2.mrng_OT) + TIME_TO_SEC(v2.evng_OT) + TIME_TO_SEC(v2.nght_OT) + TIME_TO_SEC(v2.sunday_OT)
                ),
                '%H:%i'
            ) AS overall_OT_hrs,
            CASE WHEN v2.mrng_OT != '00:00' AND v2.evng_OT != '00:00' AND v2.nght_OT != '00:00' THEN 'Mrng/Evg/Night' WHEN v2.mrng_OT != '00:00' AND v2.evng_OT != '00:00' THEN 'Mrng/Evg' WHEN v2.mrng_OT != '00:00' AND v2.nght_OT != '00:00' THEN 'Mrng/Night' WHEN v2.mrng_OT != '00:00' THEN 'Morning' WHEN v2.evng_OT != '00:00' THEN 'Evening' WHEN v2.nght_OT != '00:00' THEN 'Night' WHEN v2.sunday_OT != '00:00' THEN 'Sunday' ELSE NULL
    END AS OT_type,
    COALESCE(hr_ot_amount_mrng.ot_amount, 0) AS mrng_ot_amount,
    COALESCE(
        hr_ot_amount_mrng.food_amount,
        0
    ) AS mrng_food_amount,
    COALESCE(hr_ot_amount_evng.ot_amount, 0) AS evng_ot_amount,
    COALESCE(
        hr_ot_amount_evng.food_amount,
        0
    ) AS evng_food_amount,
    COALESCE(hr_ot_amount_nght.ot_amount, 0) AS nght_ot_amount,
    COALESCE(
        hr_ot_amount_nght.food_amount,
        0
    ) AS nght_food_amount,
    CASE WHEN v2.day = 'Sunday' AND v2.sunday_OT != '00:00' AND v2.date NOT IN(
    SELECT
        start_date
    FROM
        hr_compoff_t
    WHERE
        hr_compoff_t.employee_id = v2.employee_id AND leave_status ='APPROVE'
) THEN COALESCE(
    hr_ot_amount_sunday.ot_amount,
    0
) ELSE 0
END AS sunday_ot_amount,
CASE WHEN v2.day = 'Sunday' AND TIME(v2.check_in) < '06:20:00' AND v2.date NOT IN(
    SELECT
        start_date
    FROM
        hr_compoff_t
    WHERE
        hr_compoff_t.employee_id = v2.employee_id AND leave_status ='APPROVE'
) THEN 50 ELSE 0
END AS sunday_food_amount
FROM
    (
    SELECT
        v1.*,
        TIME_FORMAT(
            IF(
                v1.day != 'Sunday' AND TIME(v1.check_out) > '16:00:00',
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
    FROM
        (
        SELECT
            hr_employee_t.employee_id,
            hr_employee_t.employee_number,
            hr_employee_t.first_name,
            CASE WHEN INSTR(
            REPLACE
                (
                REPLACE
                    (
                    REPLACE
                        (hr_employee_t.department, '[', ''),
                        ']',
                        ''
                ),
                '\"',
                ''
            ),
            ','
        ) > 0 THEN SUBSTRING_INDEX(
        REPLACE
            (
            REPLACE
                (
                REPLACE
                    (hr_employee_t.department, '[', ''),
                    ']',
                    ''
            ),
            '\"',
            ''
        ),
        ',',
        -1
    ) ELSE
REPLACE
    (
    REPLACE
        (
        REPLACE
            (hr_employee_t.department, '[', ''),
            ']',
            ''
    ),
    '\"',
    ''
)
    END AS department,
    hr_employee_t.job_title,
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
TIMEDIFF(
    hr_emp_attendence.check_out,
    hr_emp_attendence.check_in
) AS wrk_hrs,
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
) AS late_out,
            TIME_FORMAT(
                IF(TIME_TO_SEC(TIMEDIFF(TIME_FORMAT(hr_emp_attendence.check_in, '%H:%i'), '09:30')) < 0, 
                   '00:00', 
                   TIMEDIFF(TIME_FORMAT(hr_emp_attendence.check_in, '%H:%i'), '09:30')
                ), '%H:%i') AS late_in,
            TIME_FORMAT(
                IF(TIME_TO_SEC(TIMEDIFF('18:00', TIME_FORMAT(hr_emp_attendence.check_out, '%H:%i'))) < 0, 
                   '00:00', 
                   TIMEDIFF('18:00', TIME_FORMAT(hr_emp_attendence.check_out, '%H:%i'))
                ), '%H:%i') AS early_out
FROM
    `hr_employee_t`
LEFT JOIN hr_emp_attendence ON hr_emp_attendence.emp_id = hr_employee_t.employee_number
LEFT JOIN hr_compoff_t ON hr_compoff_t.employee_id = hr_employee_t.employee_id
WHERE
    hr_employee_t.ot_formula = '1' AND hr_employee_t.active = 'yes' AND hr_emp_attendence.atten_date BETWEEN '$start_date' AND '$end_date'
) v1
) v2
LEFT JOIN hr_ot_amount_t hr_ot_amount_mrng ON
    FIND_IN_SET(
        hr_ot_amount_mrng.department,
        v2.department
    ) AND hr_ot_amount_mrng.job_tittle = v2.job_title AND hr_ot_amount_mrng.ot_type = 'Morning' AND v2.mrng_OT = hr_ot_amount_mrng.ot_hrs
LEFT JOIN hr_ot_amount_t hr_ot_amount_evng ON
    FIND_IN_SET(
        hr_ot_amount_evng.department,
        v2.department
    ) AND hr_ot_amount_evng.job_tittle = v2.job_title AND hr_ot_amount_evng.ot_type = 'Evening' AND v2.evng_OT = hr_ot_amount_evng.ot_hrs
LEFT JOIN hr_ot_amount_t hr_ot_amount_nght ON
    FIND_IN_SET(
        hr_ot_amount_nght.department,
        v2.department
    ) AND hr_ot_amount_nght.job_tittle = v2.job_title AND hr_ot_amount_nght.ot_type = 'Night' AND v2.nght_OT = hr_ot_amount_nght.ot_hrs
LEFT JOIN hr_ot_amount_t hr_ot_amount_sunday ON
    FIND_IN_SET(
        hr_ot_amount_sunday.department,
        v2.department
    ) AND hr_ot_amount_sunday.job_tittle = v2.job_title AND hr_ot_amount_sunday.ot_type = 'Sunday' AND v2.sunday_OT = hr_ot_amount_sunday.ot_hrs
GROUP BY
    v2.date,
    v2.employee_id
) v3
GROUP BY
    v3.date,
    v3.employee_id
) v4
GROUP BY
    v4.date,
    v4.employee_id
HAVING
    v4.total_amt != '0'
ORDER BY
    `v4`.`check_in` ASC");



        foreach ($ot_cal as $data) {
            // Check if a record already exists for this employee and check-in date
            $exists = \DB::table('hr_emp_ot_details_t')
                ->where('emp_id', $data->employee_id)
                ->where('check_in', $data->check_in)
                ->exists();

            if ($exists) {
                return response()->json(array('status' => 'error', 'message' => 'OT Already Run For the month '));
                continue;
            }

            // Insert new OT record if not exists
            $result = \DB::table('hr_emp_ot_details_t')->insert([
                'emp_id' => $data->employee_id,
                'emp_number' => $data->employee_number,
                'emp_name' => $data->first_name,
                'department' => $data->department,
                'emp_type' => $data->employee_type,
                'emp_group' => $data->group_type,
                'position' => $data->position,
                'job_tittle' => $data->job_title,
                'day' => $data->day,
                'check_in' => $data->check_in,
                'check_out' => $data->check_out,
                'wrking_hrs' => $data->wrk_hrs,
                'early_in' => $data->early_in,
                'early_out' => $data->early_out,
                'late_in' => $data->late_in,
                'late_out' => $data->late_out,
                'mrng_ot' => $data->mrng_OT,
                'evng_ot' => $data->evng_OT,
                'night_ot' => $data->nght_OT,
                'sunday_ot' => $data->sunday_OT,
                'ot_type' => $data->OT_type,
                'overall_ot_hrs' => $data->overall_OT_hrs,
                'ot_amount' => $data->ot_amt,
                'food_amount' => $data->food_amt,
                'total_amt' => $data->total_amt,
                'status' => 'INITIATED',
                'created_by' => $emp_id,
                'created_at' => date('Y/m/d'),
                'last_updated_by' => $emp_id,
                'updated_at' => date('Y/m/d'),
                'company_id' => $compy,
                'location_id' => $loc,
                'organization_id' => $org_id,
                'ot_month' => $request->input('month'),
                'ot_year' => $request->input('year'),
                'ot_run_date' => date('Y/m/d')
            ]);
        }

        return response()->json(['success' => true]);

    }

    public function employeeotgrid(Request $request)
    {

        $wh1 = '';
        $wh = ' and status="INITIATED"';




        $SQL = "SELECT hr_emp_ot_details_t.id,
hr_emp_ot_details_t.emp_number,
hr_emp_ot_details_t.emp_id,
hr_emp_ot_details_t.emp_name,
m_department_lines_t.sub_department_name,
m_job_title.job_title_name,
hr_emp_ot_details_t.check_in,
hr_emp_ot_details_t.check_out,
hr_emp_ot_details_t.day,
hr_emp_ot_details_t.mrng_ot,
hr_emp_ot_details_t.evng_ot,
hr_emp_ot_details_t.night_ot,
hr_emp_ot_details_t.sunday_ot,
hr_emp_ot_details_t.ot_type,
hr_emp_ot_details_t.overall_ot_hrs,
hr_emp_ot_details_t.ot_amount,
hr_emp_ot_details_t.food_amount,
hr_emp_ot_details_t.total_amt,
hr_emp_ot_details_t.status

FROM `hr_emp_ot_details_t` 

LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_emp_ot_details_t.department
LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_emp_ot_details_t.job_tittle WHERE 1=1 $wh ORDER BY date(check_in) ASC";

        $data = \DB::select($SQL);
        return DataTables::of($data)->make(true);


    }

    public function validateot()
    {

        $row_id = $_GET['row_id'];
        $list_id = explode(',', $row_id);
        if ($row_id != '') {
            foreach ($list_id as $key => $value) {

                $query = DB::table('hr_emp_ot_details_t')->where('id', $value)->update(['status' => "VALIDATED"]);
                //auditlog
                $update['status'] = "VALIDATED";
                $this->auditlog($value, "validateot", "update", $update, "hr_emp_ot_details_t");
            }
        }
        return 1;
    }


    // edit purpose
    public function empepupdate($id = null)
    {

        $id = $id;
        $ot_amount = $_POST['ep_amount_edit'];
        $food_amount = $_POST['food_amount_ep'];
        $total_amunt = $ot_amount + $food_amount;


        \DB::table('hr_emp_ot_details_t')->where('id', $id)->update(['ot_amount' => $ot_amount, 'food_amount' => $food_amount, 'total_amt' => $total_amunt]);

        return response()->json(array('status' => 'success', 'message' => 'Edited Successfully'));

    }

    public function otmailsend()
    {

        // approve mail function start
        $month = $_POST['ep_month'];
        $from_mail = "payroll@jrkresearch.com";
        $to_mail = "gayathri_rajagopal@jrkresearch.com";
        $cc = "rajagopal_jk@jrkresearch.com,munusamy_s@jrkresearch.com,ho-hr@jrkresearch.com,payroll@jrkresearch.com";
        $sub = "Employee Extra Productivity Approval For $month";


        $msg = "<p>Dear Mam,<br><br>This is to inform you that the employee Extra Productivity has been generated for the month $month, for your kind approval.<br><br>Regards,
        <br> HR Payroll & Admin";

        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }

        \Mail::send([], [], function ($message) use ($from_mail, $to_mail, $cc, $sub, $msg) {

            $message->from($from_mail)
                ->to($to_mail)
                ->cc(explode(',', $cc))
                ->subject($sub)
                ->setBody($msg, 'text/html');
        });

        // mail functrion end 
        return response()->json(array('status' => 'success', 'message' => 'Mail Send Successfully'));
    }



}