<?php

namespace App\Http\Controllers;
use DB;
use DateTime;
use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ApprovebonusController extends Controller
{
    public function __construct()
  {
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
   }
    // approve index page load function start
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

    $result = DB::table('hr_employee_bonus_lists')->where('bonus_status',0)->get();  
    
    return view('generatebonus.approvebonus',$this->data);
	  
  } 
  
    public function releasebonus(Request $request)
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

    
    return view('generatebonus.releasebonus',$this->data);
  }
  
  
    public function approvedbonus(){
        
      $row_id = $_GET['row_id'];
    $list_id = explode(',',$row_id);
    if($row_id != '')
    {
      foreach($list_id as $key=>$value)
      {
        
        $query = DB::table('hr_employee_bonus_lists')->where('id',$value)->update(['bonus_status'=>1]);
                                //auditlog
                                $update['bonus_status']=1;
                                   $this->auditlog($value,"approvebonus","update",$update,"hr_employee_bonus_lists");
      }
    }
    return 1;
  }
  
  
          public function employeebonusreleasegrid(Request $request) {
            
                
          $group_name = \Session::get('groupname');
                
            $condition ='';    
                
        if ($group_name =="16"){
            
            $condition = " AND hr_employee_bonus_lists.group_type='14' ";
        }    
        
		$wh=' and bonus_status=1';
			  
	
        
	$SQL = "SELECT 
	                hr_employee_bonus_lists.id,
                    hr_employee_bonus_lists.employee_id,
                    hr_employee_bonus_lists.employee_number,
                    hr_employee_bonus_lists.employee_name,
                    m_department_lines_t.sub_department_name as department,
                    hr_employee_bonus_lists.zone,
                    hr_employee_bonus_lists.last_revision_date,
                    hr_employee_bonus_lists.doj,
                    hr_employee_bonus_lists.bonus1,
                    hr_employee_bonus_lists.bonus2,
                    hr_employee_bonus_lists.bonus3,
					hr_employee_bonus_lists.bonus4,
					hr_employee_bonus_lists.from1,
					hr_employee_bonus_lists.to1,
					hr_employee_bonus_lists.payable1,
					hr_employee_bonus_lists.from2,
					hr_employee_bonus_lists.to2,
					hr_employee_bonus_lists.payable2,
                    hr_employee_bonus_lists.from3,
					hr_employee_bonus_lists.to3,
					hr_employee_bonus_lists.payable3,
                    hr_employee_bonus_lists.from4,
					hr_employee_bonus_lists.to4,
					hr_employee_bonus_lists.payable4,
                    hr_employee_bonus_lists.last_period,
                    hr_employee_bonus_lists.end_date,
                    hr_employee_bonus_lists.final_payable,
                    hr_employee_bonus_lists.arrear_bonus,
                    CASE
                        WHEN bonus_status = '0' THEN 'INITIATED'
                        WHEN bonus_status = '1' THEN 'APPROVED'
                        WHEN bonus_status = '2' THEN 'RELEASED'
                        ELSE NULL
                    END AS status,
                    hr_employee_bonus_lists.total_bonus
	                FROM hr_employee_bonus_lists LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_employee_bonus_lists.department  WHERE 1=1 $wh $condition";

	$result = \DB::select($SQL);
			  
   return DataTables::of($result)->make(true);
			  
     }
  
	
  
    public function releasedbonus(){
    
    $row_id = $_GET['row_id'];
    $list_id = explode(',',$row_id);
    if($row_id != '')
    {
      foreach($list_id as $key=>$value)
      {
        
        $query = DB::table('hr_employee_bonus_lists')->where('id',$value)->update(['bonus_status'=>2]);
                                //auditlog
                    $update['bonus_status']=2;
                 $this->auditlog($value,"approvebonus","update",$update,"hr_employee_bonus_lists");
      }
    }
    return 1;
  }
  
  
  
  
  
}