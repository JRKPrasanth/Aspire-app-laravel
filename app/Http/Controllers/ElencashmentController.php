<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use DB,Session;
use DateTime;

class ElencashmentController extends Controller
{   
        public function __construct()
    {
            $this->data['pageModule']=\Request::route()->getName();
           
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs();    
     }
    
    //payrollgenerate index page load function start 
    public function index(Request $request)
    {

       $cur_year = date("Y");
       $pre_year =  $cur_year - 1 ;   

       $eldata =  \DB::select("SELECT hr_employee_t.employee_id,hr_employee_t.employee_number,hr_employee_t.first_name,hr_employee_t.date_of_joining,hr_employee_t.active,REPLACE(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(hr_employee_t.department, ',', 1), '[', -1), ']', ''), '\"', '') AS department,
                            Leave_balance_tbl.earn_leave,ROUND(hr_employee_payproposal.gross_pay/30 ,0) as perdaysal,m_department_lines_t.sub_department_name,Leave_balance_tbl.earn_leave - 30 as el_eligible,ROUND(hr_employee_payproposal.gross_pay/30 ,0) * (Leave_balance_tbl.earn_leave - 30) as total FROM `hr_employee_t`  
                            LEFT JOIN Leave_balance_tbl ON Leave_balance_tbl.employee_id = hr_employee_t.employee_id
                            LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_t.employee_id
                            LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_employee_t.department
                            WHERE hr_employee_t.active='Yes' AND Leave_balance_tbl.earn_leave > 30 GROUP BY hr_employee_t.employee_id");

       foreach ($eldata as $data) {
        // Check if a record with the same year and employee_id already exists
        $exists = \DB::table('hr_elencashment_t')
            ->where('employee_id', $data->employee_id)
            ->where('year', $pre_year)
            ->exists();

        if (!$exists) {
            // Insert the record if it does not exist
            \DB::table('hr_elencashment_t')->insert([
                'employee_id' => $data->employee_id,
                'emp_number' => $data->employee_number,
                'emp_name' => $data->first_name,
                'year' => $pre_year,
                'department' => $data->department,
                'salary_day' => $data->perdaysal,
                'active' => $data->active,
                'status' => "INITIATED",
                'doj' => $data->date_of_joining,
                'total_el' => $data->earn_leave,
                'extra_el' => $data->el_eligible,
                'total_amt' => $data->total,
                'created_by' => \Session::get('id'),
                'created_at' => date('Y/m/d'),
                'last_updated_by' => \Session::get('id'),
                'updated_at' => date('Y/m/d'),
                'company_id' => \Session::get('companyid'),
                'location_id' => "1",
                'organization_id' => \Session::get('organization')
            ]);
        }
    }

        return view('elencashment.index', $this->data);
    }

    
        public function approvegrid(Request $request)
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

       return view('elencashment.approvetable',$this->data);
    }
    
    public function getencashmentlist(Request $request) {
   
		$wh=' and status= "INITIATED" ';
        
        
	$SQL = "SELECT hr_elencashment_t.*,m_department_lines_t.sub_department_name FROM hr_elencashment_t LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_elencashment_t.department where 1=1 $wh";

 
       
	$result = \DB::select($SQL);
		
    return DataTables::of($result)->make(true);
	
      
     }
     
    // request 
    public function requestelencashment(){
        
    $row_id = $_GET['row_id'];
    $list_id = explode(',',$row_id);
    if($row_id != '')
    {
      foreach($list_id as $key=>$value)
      {
        
        $query = DB::table('hr_elencashment_t')->where('id',$value)->update(['status'=>"REQUESTED"]);
                                //auditlog
                                $update['status']="REQUESTED";
                                   $this->auditlog($value,"requestelencashment","update",$update,"hr_elencashment_t");
      }
    }
    return 1;
  }

    // approve grid
    
    
    public function elapprovelist(Request $request) {

                
		$wh =' and status= "REQUESTED" ';

        
	$SQL = "SELECT hr_elencashment_t.*,m_department_lines_t.sub_department_name FROM hr_elencashment_t LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_elencashment_t.department where 1=1 $wh";

         
       
	$result = \DB::select($SQL);
		
    return DataTables::of($result)->make(true);

     }
    
    // Approve
    
        // request 
    public function approveelencashment(){
        
    $row_id = $_GET['row_id'];
    $list_id = explode(',',$row_id);
    if($row_id != '')
    {
      foreach($list_id as $key=>$value)
      {
        
        $query = DB::table('hr_elencashment_t')->where('id',$value)->update(['status'=>"APPROVED"]);
                                //auditlog
                                $update['status']="APPROVED";
                                   $this->auditlog($value,"requestelencashment","update",$update,"hr_elencashment_t");
      }
    }
    return 1;
  }
}