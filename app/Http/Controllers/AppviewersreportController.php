<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;


    class AppviewersreportController extends Controller
    
    {
        
        // month Wise 
        public function Index(Request $request)
			
        {
			
	  $this->data['pageMethod']="appviewerreport";
      $this->data['employee'] = $this->jcustomselecttool("hr_employee_t", "employee_id", "first_name", "","and employee_id!='1'");
			
          return view('applicationviewers.table',  $this->data);
    }
    
    
    public function getviewdata(Request $request)
	{

	$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
	$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
	$emp_id = $request->emp_id ?? null;


	$SQL = "SELECT * from (SELECT tb_users.first_name,hr_tracker_t.type,date_time, DATE_FORMAT(date, '%b-%Y') AS month  FROM `hr_tracker_t` 
			LEFT JOIN tb_users ON tb_users.id = hr_tracker_t.emp_id
			LEFT JOIN  tb_menus ON tb_menus.controller_name = hr_tracker_t.url WHERE 1=1 AND date BETWEEN ? AND ? AND emp_id= ? ORDER BY date DESC
	) AS v1";

	$results = \DB::select($SQL, [$start_date, $end_date, $emp_id]);

	return response()->json(['data' => $results]);
	}
		
		
           // month Wise 
        public function userlastlogin(Request $request)
        {
			
			$this->data['pageMethod']="userlastlogin";
            $this->data['employee'] = $this->jcustomselecttool("hr_employee_t", "employee_id", "first_name", "","AND active='Yes'");

            return view('applicationviewers.lastlogin',  $this->data);
     
    } 
    
    
        public function GetloginData(Request $request)
	{


	$emp_id = $request->emp_id ?? null;


	$SQL = "SELECT * from (SELECT employee_number,first_name,last_login,ip_address,login_browser,last_logout FROM `tb_users` WHERE 1=1  AND active='Yes' AND id !='1' AND id= ? ) AS v1";

	$results = \DB::select($SQL, [$emp_id]);

	return response()->json(['data' => $results]);
	}
    
    
    
    }