<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Config;
use DateInterval;
use DateTime;
use DatePeriod;
use Yajra\DataTables\DataTables;

class LeavereminderController extends Controller
{
       public function __construct()
  {
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
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

       return view('leavereminder.table', $this->data);
    }
   
   
   // table data

  public function punchmissdata(Request $request) {
                
     
	$start_date = $request->start_date;

          $comp=\Session::get('companyid'); 
          $emp=\Session::get('emp_id');
          
            
      $data = \DB::select("SELECT * FROM (SELECT
    v1.*,
    COALESCE(rm.email, 'Unknown') AS reporting_manager_email
FROM
    (
    SELECT
        '$start_date' AS date,
        DATE_FORMAT('$start_date', '%W') AS day,
        hr_employee_t.employee_id,
        hr_employee_t.email,
        hr_employee_t.reporting_manager,
        hr_employee_t.employee_number,
        hr_employee_t.biometric_empno,
        hr_employee_t.active,
        hr_employee_t.first_name,
        a.lookup_code AS dept,
        COALESCE(a_lookuplines_t.lookup_meaning, 'Absent') AS status
    FROM
        hr_employee_t
    LEFT JOIN hr_leaves_t ON hr_leaves_t.employee_id = hr_employee_t.employee_id 
                           AND hr_leaves_t.start_date <= '$start_date' 
                           AND hr_leaves_t.end_date >= '$start_date' 
                           AND (hr_leaves_t.leave_status = 'APPROVE' OR hr_leaves_t.leave_status = 'INITIATED')
    LEFT JOIN hr_emp_attendence ON hr_employee_t.employee_number = hr_emp_attendence.emp_id 
                                 AND DATE(hr_emp_attendence.atten_date) = '$start_date'
    LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_leaves_t.leave_mode
    LEFT JOIN a_lookuplines_t a ON a.lookuplines_id = hr_employee_t.employee_type
    WHERE
        hr_emp_attendence.check_in IS NULL 
        AND hr_employee_t.employee_id != '1' 
        AND hr_employee_t.active = 'yes' 
        AND (
            a.lookup_code LIKE '%contract%' 
            OR a.lookup_code LIKE '%staff%' 
            OR a.lookup_code LIKE '%board%' 
        ) 
    HAVING status = 'Absent'
    ) AS v1
LEFT JOIN hr_employee_t rm ON v1.reporting_manager = rm.employee_id

UNION ALL

SELECT
    v1.*,
    COALESCE(rm.email, 'Unknown') AS reporting_manager_email
FROM
    (
    SELECT
        '$start_date' AS date,
        DATE_FORMAT('$start_date', '%W') AS day,
        hr_employee_t.employee_id,
        hr_employee_t.email,
        hr_employee_t.reporting_manager,
        hr_employee_t.employee_number,
        hr_employee_t.biometric_empno,
        hr_employee_t.active,
        hr_employee_t.first_name,
        a.lookup_code AS dept,
        'Absent / HALF DAY' AS status
    FROM
        hr_employee_t
    LEFT JOIN hr_leaves_t ON hr_leaves_t.employee_id = hr_employee_t.employee_id  
                           AND (DATE('$start_date') BETWEEN DATE(hr_leaves_t.start_date_time) AND DATE(hr_leaves_t.end_date_time) 
                           OR DATE('$start_date') BETWEEN DATE(hr_leaves_t.start_date) AND DATE(hr_leaves_t.end_date)
                           OR DATE('$start_date') BETWEEN DATE(hr_leaves_t.od_start_date) AND DATE(hr_leaves_t.od_end_date))
                           AND (hr_leaves_t.leave_status = 'APPROVE' OR hr_leaves_t.leave_status = 'INITIATED')
    LEFT JOIN hr_emp_attendence ON hr_employee_t.employee_number = hr_emp_attendence.emp_id 
                                 AND DATE(hr_emp_attendence.atten_date) = '$start_date'
    LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_leaves_t.leave_mode
    LEFT JOIN a_lookuplines_t a ON a.lookuplines_id = hr_employee_t.employee_type
    WHERE
        ((hr_emp_attendence.check_in >= '$start_date 11:30')  
        OR  (hr_emp_attendence.check_out BETWEEN '$start_date 13:00' AND '$start_date 16:00')) 
        AND hr_emp_attendence.check_in != hr_emp_attendence.check_out 
        AND hr_employee_t.employee_id != '1' 
        AND hr_employee_t.active = 'yes' 
        AND (
            a.lookup_code LIKE '%contract%' 
            OR a.lookup_code LIKE '%staff%' 
            OR a.lookup_code LIKE '%board%' 
        ) 
        AND hr_leaves_t.leave_id IS NULL 
    HAVING status = 'Absent / HALF DAY'
    ) AS v1
LEFT JOIN hr_employee_t rm ON v1.reporting_manager = rm.employee_id

UNION ALL

SELECT
    v1.*,
    COALESCE(rm.email, 'Unknown') AS reporting_manager_email
FROM
    (
    SELECT
        '$start_date' AS date,
        DATE_FORMAT('$start_date', '%W') AS day,
        hr_employee_t.employee_id,
        hr_employee_t.email,
        hr_employee_t.reporting_manager,
        hr_employee_t.employee_number,
        hr_employee_t.biometric_empno,
        hr_employee_t.active,
        hr_employee_t.first_name,
        a.lookup_code AS dept,
        'PERMISSION' AS status
    FROM
        hr_employee_t
    LEFT JOIN hr_leaves_t ON hr_leaves_t.employee_id = hr_employee_t.employee_id  
                           AND (DATE('$start_date') BETWEEN DATE(hr_leaves_t.start_date_time) AND DATE(hr_leaves_t.end_date_time) 
                           OR DATE('$start_date') BETWEEN DATE(hr_leaves_t.start_date) AND DATE(hr_leaves_t.end_date)
                           OR DATE('$start_date') BETWEEN DATE(hr_leaves_t.od_start_date) AND DATE(hr_leaves_t.od_end_date))
                           AND (hr_leaves_t.leave_status = 'APPROVE' OR hr_leaves_t.leave_status = 'INITIATED')
    LEFT JOIN hr_emp_attendence ON hr_employee_t.employee_number = hr_emp_attendence.emp_id 
                                 AND DATE(hr_emp_attendence.atten_date) = '$start_date'
    LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_leaves_t.leave_mode
    LEFT JOIN a_lookuplines_t a ON a.lookuplines_id = hr_employee_t.employee_type
    WHERE
        ((hr_emp_attendence.check_in > '$start_date 10:10')  
        OR  (hr_emp_attendence.check_out < '$start_date 17:45')) 
        AND hr_emp_attendence.check_in != hr_emp_attendence.check_out 
        AND hr_employee_t.employee_id != '1' 
        AND hr_employee_t.active = 'yes' 
        AND (
            a.lookup_code LIKE '%contract%' 
            OR a.lookup_code LIKE '%staff%' 
            OR a.lookup_code LIKE '%board%' 
        ) 
        AND hr_leaves_t.leave_id IS NULL 
        AND hr_employee_t.employee_id NOT IN (
            SELECT
                hr_employee_t.employee_id
            FROM
                hr_employee_t
            LEFT JOIN hr_leaves_t ON hr_leaves_t.employee_id = hr_employee_t.employee_id  
            AND (DATE('$start_date') BETWEEN DATE(hr_leaves_t.start_date_time) AND DATE(hr_leaves_t.end_date_time) 
            OR DATE('$start_date') BETWEEN DATE(hr_leaves_t.start_date) AND DATE(hr_leaves_t.end_date)
            OR DATE('$start_date') BETWEEN DATE(hr_leaves_t.od_start_date) AND DATE(hr_leaves_t.od_end_date))
            AND (hr_leaves_t.leave_status = 'APPROVE' OR hr_leaves_t.leave_status = 'INITIATED')
            LEFT JOIN hr_emp_attendence ON hr_employee_t.employee_number = hr_emp_attendence.emp_id 
                                        AND DATE(hr_emp_attendence.atten_date) = '$start_date'
            LEFT JOIN a_lookuplines_t a ON a.lookuplines_id = hr_employee_t.employee_type
            WHERE
                ((hr_emp_attendence.check_in >= '$start_date 11:30')  
                OR  (hr_emp_attendence.check_out BETWEEN '$start_date 13:00' AND '$start_date 16:00')) 
                AND hr_emp_attendence.check_in != hr_emp_attendence.check_out 
                AND hr_employee_t.employee_id != '1' 
                AND hr_employee_t.active = 'yes' 
                AND (
                    a.lookup_code LIKE '%contract%' 
                    OR a.lookup_code LIKE '%staff%' 
                    OR a.lookup_code LIKE '%board%' 
                ) 
                AND hr_leaves_t.leave_id IS NULL
        )
    HAVING status = 'PERMISSION'
    ) AS v1 
LEFT JOIN hr_employee_t rm ON v1.reporting_manager = rm.employee_id)v2 HAVING v2.date !=''");

    
           return DataTables::of($data)->make(true);
        
	  
        }
   
	
    public function sendreminder($id = null, Request $request)
{

    $leaveIds = explode(",", $id);
    $employeeNames = explode(",", request()->get('employee_name', ''));
    $emails = explode(",", request()->get('mail', ''));
    $ccList = explode(",", request()->get('cc', ''));
    $statuses = explode(",", request()->get('status', ''));
    $msg = request()->get('msg', '');
    $msg1 = request()->get('msg1', '');
    $msg2 = request()->get('msg2', '');
    $date = request()->get('date', date('Y-m-d'));

    foreach ($leaveIds as $key => $leaveId) {
        $employeeName = $employeeNames[$key] ?? 'Employee';
        $toEmail = $emails[$key] ?? null;
        $status = $statuses[$key] ?? '';

        if (!$toEmail) continue; // Skip if no email provided

        // Set session-based mail credentials
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }

        // Choose message body based on status
        if ($status == "PERMISSION") {
            $body = $msg1;
            $subject = "Reminder _ Permission not applied for $date";
        } elseif ($status == "Absent / HALF DAY") {
            $body = $msg2;
            $subject = "Reminder _ HALF DAY Leave/On Duty not applied for $date";
        } else {
            $body = $msg;
            $subject = "Reminder _ Leave/On Duty not applied for $date";
        }

        $finalMessage = "Dear $employeeName,<br>" . $body;

        // Send mail
        \Mail::send([], [], function ($message) use ($toEmail, $employeeName, $subject, $finalMessage, $ccList) {
            $message->to($toEmail)
                ->from('payroll@jrkresearch.com')
                ->cc(array_merge(['payroll@jrkresearch.com','rajesh_r@jrkresearch.com'], $ccList))
                ->subject($subject)
                ->setBody($finalMessage, 'text/html'); // Important: HTML content
        });
    }

    if (request()->has('mail')) {
        return response()->json(['status' => 'success'], 200);
    }

    return response()->json(['status' => 'no action'], 400);
}


   

}