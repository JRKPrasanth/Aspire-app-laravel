<?php

namespace App\Http\Controllers;
use DB;
use DateTime;
use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ApproveemployeeotController extends Controller
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

    $this->data['sum_inv_tot'] = \DB::select("SELECT ROUND(SUM(total_amt),0) as total from hr_emp_ot_details_t where status='VALIDATED'");
    return view('employeeot.approveot',$this->data);
  } 
  

      public function employeeotgrid1(Request $request) {
                 
		$wh=' and status="VALIDATED"';

        
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
  
  
  public function approveot(){
        
      $row_id = $_GET['row_id'];
      $list_id = explode(',',$row_id);
    if($row_id != '')
    {
      foreach($list_id as $key=>$value)
      {
        
        $query = DB::table('hr_emp_ot_details_t')->where('id',$value)->update(['status'=>"APPROVED"]);
                                //auditlog
                                  $update['status']="APPROVED";
                                   $this->auditlog($value,"approveot","update",$update,"hr_emp_ot_details_t");
      }
    }
    return 1;
  }

  
  
  
  
  
}