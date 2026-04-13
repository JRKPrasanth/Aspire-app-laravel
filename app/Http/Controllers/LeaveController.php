<?php

namespace App\Http\Controllers;

use App\leaveapplications;
use App\partialday;
use App\holiday;
use Illuminate\Http\Request;
use Session;
use DB;
use DatePeriod;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\Input;
use App\Employeecreate;

class LeaveController extends Controller
{
    public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	 }
         //** Leave page index open funcation start **/
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

//       //  $logged_user = Session::get('emp_id');
//       //dd($logged_user);
//         $result = DB::table('hr_employee_t')->where('employee_id',$logged_user)->get();
      $emp_depart=\Session::get('dept_id');
	
        $SQL = "SELECT hr_leaves_t.leave_id,hr_leaves_t.leave_combo,  hr_leaves_t.employee_id, hr_leaves_t.start_date,hr_leaves_t.end_date,  hr_leaves_t.no_of_days,hr_leaves_t.alloted_days, hr_leaves_t.forwarded_id, hr_leaves_t.approval_reason, hr_leaves_t.approvel_comments, hr_leaves_t.leave_type,hr_leaves_t.leave_type, CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name )as forwarded_name,concat(hr_employee_name.employee_number,'-',hr_employee_name.first_name) as employee_name,hr_leaves_t.employee_id, hr_leaves_t.leave_reason, hr_leaves_t.organization_id,hr_leaves_t.leave_status,hr_leaves_t.od_start_date,hr_leaves_t.od_end_date,hr_leaves_t.od_no_of_days, hr_leaves_t.leave_mode, hr_leaves_t.leave_reason, hr_leaves_t.od_alloted_days FROM   hr_leaves_t  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_leaves_t.forwarded_id LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_leaves_t.employee_id WHERE  1 = 1 order by  hr_leaves_t.leave_id  desc ";
	$this->data['result'] = json_encode(\DB::select($SQL));
	$this->data['employee_type']            = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='EMPLOYEE_TYPE'");
        
//dd(	$this->data['employee_type'] );
         return view('leave.form',$this->data);
    
        
    }
    
    
    
    
	 //** Leave page index open funcation end **/
	 
/** Leave Approver List **/


/**dummy leave**/
  public function mailapprove()
    {
        $id=$_GET['id'];
        $idd=DB::table('hr_leaves_t')->where('leave_id',$id)->update(['leave_status' => 'APPROVE']);
         $this->data['leave_data']= $leave_data = DB::table('hr_leaves_t')
                       ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_leaves_t.employee_id')
                       ->leftjoin('hr_employee_t as report','hr_employee_t.employee_id','=','hr_leaves_t.forwarded_id')
                       ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_leaves_t.leave_type')
                       ->leftjoin('a_lookuplines_t as leavemode','leavemode.lookuplines_id','=','hr_leaves_t.leave_mode')
                       ->select('hr_leaves_t.leave_reason','hr_leaves_t.no_of_days','hr_employee_t.email','hr_leaves_t.leave_status','hr_leaves_t.end_date','hr_leaves_t.start_date','hr_leaves_t.approval_reason','hr_leaves_t.alloted_days','hr_employee_t.employee_number','hr_employee_t.first_name','report.employee_number as report_number','report.first_name as report_name','a_lookuplines_t.lookup_meaning','leavemode.lookup_meaning as leave_mode')
                       ->where('leave_id',$id)->get();
                $_POST['mail']=$leave_data[0]->email;
                $this->data['status']="approve";
                \Mail::send('leaves.mail_details',$this->data,function($message)
                {
                   $message->to('ifivetechteam@gmail.com');
                    //$message->from('ifivetechteam@gmail.com');
                    if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    $message->subject("Leave Approved");                    
                });
		//return 1;	   	
        
        return view('leaves.approve');
    }
     public function mailreject()
    {
      $id=$_GET['id'];
       $idd=DB::table('hr_leaves_t')->where('leave_id',$id)->update(['leave_status' => 'REJECT']);
          $this->data['leave_data']= $leave_data = DB::table('hr_leaves_t')
                       ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_leaves_t.employee_id')
                       ->leftjoin('hr_employee_t as report','hr_employee_t.employee_id','=','hr_leaves_t.forwarded_id')
                       ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_leaves_t.leave_type')
                       ->leftjoin('a_lookuplines_t as leavemode','leavemode.lookuplines_id','=','hr_leaves_t.leave_mode')
                       ->select('hr_leaves_t.leave_reason','hr_leaves_t.no_of_days','hr_employee_t.email','hr_leaves_t.leave_status','hr_leaves_t.end_date','hr_leaves_t.start_date','hr_leaves_t.approval_reason','hr_leaves_t.alloted_days','hr_employee_t.employee_number','hr_employee_t.first_name','report.employee_number as report_number','report.first_name as report_name','a_lookuplines_t.lookup_meaning','leavemode.lookup_meaning as leave_mode')
                       ->where('leave_id',$id)->get();
                $_POST['mail']=$leave_data[0]->email;
                $this->data['status']="reject";
                \Mail::send('leaves.mail_replay',$this->data,function($message)
                {
                   $message->to('ifivetechteam@gmail.com');
                    //$message->from('ifivetechteam@gmail.com');
                    if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    $message->subject("Leave Rejected");                    
                });
        return view('leaves.reject');
    }

    public function index1()
    {
        $logged_user = Session::get('emp_id');
       //dd($logged_user);
        $result = DB::table('hr_employee_t')->where('employee_id',$logged_user)->get();
       $emp_depart=\Session::get('dept_id');
       $emp_depart=$emp_depart;

       $reporting=\DB::select("SELECT GROUP_CONCAT( hr_userdepartment_lines_t.employee_id) as employee_id FROM hr_userdepartment_t join hr_userdepartment_lines_t on hr_userdepartment_lines_t.userdepartment_id=hr_userdepartment_t.userdepartment_id where hr_userdepartment_t.department_line_id='$emp_depart' group by hr_userdepartment_lines_t.userdepartment_id");
       $report=$reporting[0]->employee_id;
       
        $this->data['logged_id'] = $logged_user;
       
        $this->data['forwarded_id'] =$report;
        
        $logged_user = Session::get('emp_id');
        $company_id = Session::get('companyid');
		$wh='';
        $wh .= "and hr_leaves_t.employee_id ='$logged_user' and hr_leaves_t.company_id=' $company_id' ";
		
        $SQL = "SELECT hr_leaves_t.leave_id,hr_leaves_t.leave_combo,  hr_leaves_t.employee_id, hr_leaves_t.start_date,hr_leaves_t.end_date,  hr_leaves_t.no_of_days,hr_leaves_t.alloted_days, hr_leaves_t.forwarded_id, hr_leaves_t.approval_reason, hr_leaves_t.approvel_comments, hr_leaves_t.leave_type,hr_leaves_t.leave_type, CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name )as forwarded_name,concat(hr_employee_name.employee_number,'-',hr_employee_name.first_name) as employee_name,hr_leaves_t.employee_id, hr_leaves_t.leave_reason, hr_leaves_t.organization_id,hr_leaves_t.leave_status,hr_leaves_t.od_start_date,hr_leaves_t.od_end_date,hr_leaves_t.od_no_of_days, hr_leaves_t.leave_mode, hr_leaves_t.leave_reason, hr_leaves_t.od_alloted_days FROM   hr_leaves_t  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_leaves_t.forwarded_id LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_leaves_t.employee_id WHERE  1 = 1 $wh order by  hr_leaves_t.leave_id  desc ";
	$this->data['result'] = json_encode(\DB::select($SQL));
	
	
	 if($result[0]->employee_type=='276')
	 {
	     	$this->data['reporting'] = $this->jcustommultiselect('hr_employee_t','employee_id','employee_number|first_name',$result[0]->reporting_manager,'');
	 }
	 else
	 {
	$this->data['reporting'] = $this->jcustommultiselect('hr_employee_t','employee_id','employee_number|first_name',$reporting[0]->employee_id,'');
	 }

//dd($this->data['reporting']);
   
        return view('leaves.form1',$this->data);
    }
/**dummy leave end**/


public function leaveapprover()
{
  $result = DB::table('hr_employee_t')->where('employee_id',$_GET['employee_id'])->get();
  //dd($result);
       $emp_depart=\Session::get('dept_id');
       $emp_depart=$result[0]->department;

       $reporting=\DB::select("SELECT GROUP_CONCAT( hr_userdepartment_lines_t.employee_id) as employee_id FROM hr_userdepartment_t join hr_userdepartment_lines_t on hr_userdepartment_lines_t.userdepartment_id=hr_userdepartment_t.userdepartment_id where hr_userdepartment_t.department_line_id='$emp_depart' group by hr_userdepartment_lines_t.userdepartment_id");
       $report=$reporting[0]->employee_id;   
       
        if($result[0]->employee_type=='276')
	 {
	     	$reporting = $this->jcustommultiselect('hr_employee_t','employee_id','employee_number|first_name',$result[0]->reporting_manager,'');

	 }
	 else
	 {
	    	$reporting = $this->jcustommultiselect('hr_employee_t','employee_id','employee_number|first_name',$reporting[0]->employee_id,'');

	 }
       return $reporting;
}

/** End  **/
	 
	 
/*** leave remove funcation start **/
   public function getcombodate($date=null,$eid=null){
   $date=date('Y-m-d',strtotime($date));
      $data= DB::table('hr_emp_attendence')->where('emp_id',$eid)->where('atten_date',$date)->get();   
     
      if(count($data)>0){
           $return_data['data']=1;
           $return_data['data_combo']= $data[0];
      }else{
          $return_data['data']=0;
           $return_data['data_combo']= [];
      }
      return $return_data;
   }
   public function getRemove(Request $request)
    {
        $del_id = $_GET['del_id'];
            $query = DB::table('hr_leaves_t')->where('leave_id',$del_id)->delete();     
            // auditlog
              $this->auditlog($del_id,"leave","delete",$_POST,"hr_leaves_t");
            return 2;
    
    }
   /*** leave remove function end **/
   /*** leave save function start **/
    public function save(Request $request)
    {
		$edit_id = $request->input('edit_id');
	//	dd($edit_id);
		
		
		if($edit_id =='')
		{

			$leaveapplication = new leaveapplications();
					    						    // dd($_POST);  

			$leaveapplication->leave_mode = $leave_mode = $request->input('leave_mode');

			$x = ($leave_mode == 134) ? '' : '1';
			$leaveapplication->start_date = $request->input('start_date'.$x);
			$leaveapplication->end_date = $request->input('end_date'.$x);
			$leaveapplication->no_of_days = $request->input('no_of_days');
			$leaveapplication->leave_type= $leave_type = $request->input('leave_type');

			$leaveapplication->leave_reason = $request->input('reason');		    $leave_status =$leaveapplication->leave_status=  $request->input('leave_status');
			$leaveapplication->approval_reason   = '';
			$leaveapplication->approvel_comments = '';
		    $leave_status =$leaveapplication->leave_status=  $request->input('leave_status');
			$result = DB::table('a_lookuplines_t')->where('lookuplines_id',$leave_type)->get();
			if($result[0]->lookup_code == "ON-DUTY")
			{
				$leaveapplication->od_start_date = $request->input('od_start_date');
				$leaveapplication->od_end_date = $request->input('od_end_date');
				$leaveapplication->od_no_of_days = $request->input('od_no_of_days');
				$leaveapplication->od_alloted_days = $request->input('od_alloted_days');
			}
			else
			{
				$leaveapplication->od_start_date = '';
				$leaveapplication->od_end_date = '';
				$leaveapplication->od_no_of_days = '';
				$leaveapplication->od_alloted_days = '';
			}
			//dd($leaveapplication);
			$leaveapplication->save();
			$id = $leaveapplication->leave_id;
            $table = $leaveapplication->getTable();
			$column = $leaveapplication->getKeyName();
			$this->hrmssaveinsert($table,$column,$id,1);
// auditlog
            $this->auditlog($id,"leave","create",$_POST,"hr_leaves_t");
            
            $this->data['leave_data']= $leave_data = DB::table('hr_leaves_t')
                      ->leftjoin('hr_employee_t as emp','emp.employee_id','=','hr_leaves_t.employee_id')
                      ->leftjoin('hr_employee_t as report','report.employee_id','=','hr_leaves_t.forwarded_id')
                      ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_leaves_t.leave_type')
                      ->leftjoin('a_lookuplines_t as leavemode','leavemode.lookuplines_id','=','hr_leaves_t.leave_mode')
                      ->select('hr_leaves_t.leave_reason','hr_leaves_t.no_of_days','report.email as report_email','hr_leaves_t.leave_status','hr_leaves_t.end_date','hr_leaves_t.start_date','hr_leaves_t.approval_reason','hr_leaves_t.alloted_days','emp.employee_number','emp.first_name','report.employee_number as report_number','report.first_name as report_name','a_lookuplines_t.lookup_meaning','leavemode.lookup_meaning as leave_mode','hr_leaves_t.leave_id')
                      ->where('leave_id',$id)->get();
              
           
              
                $this->data['status']="request";
                \Mail::send('leaves.mail_details',$this->data,function($message)
                {
                   //   dd($this->data['leave_data']);
                  $message->to('ifivetechteam@gmail.com');
                    //$message->from('projects@i5tech.in');
                    if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    $message->subject("Leave Initiated");                    
                });
              
			return 1;
		}
		else
		{
			$employee_id = $request->input('employee_id');
			$data['leave_mode'] = $request->input('leave_mode');
			$x = ($data['leave_mode'] == 134) ? '' : '1';
			
			$data['start_date'] = $request->input('start_date'.$x);
			$data['end_date'] = $request->input('end_date'.$x);
			$data['no_of_days'] = $request->input('no_of_days');
			$data['alloted_days'] = $request->input('alloted_days');
			$data['leave_type']= $leave_type = $request->input('leave_type');
			  $leave_combo =$leaveapplication->leave_combo=  $request->input('leave_combo');
			$data['leave_reason'] = $request->input('reason');
			$data['forwarded_id'] = $request->input('forwarded_id');
			$data['approval_reason'] = $request->input('approval_reason');
			$data['approvel_comments'] = $request->input('approvel_commemts');
			$data['leave_status'] = $leave_status = $request->input('leave_status');
			$result = DB::table('a_lookuplines_t')->where('lookuplines_id',$leave_type)->get();
			if($result[0]->lookup_code == "ON-DUTY")
			{
				$data['od_start_date'] = $request->input('od_start_date');
				$data['od_end_date'] = $request->input('od_end_date');
				$data['od_no_of_days'] = $request->input('od_no_of_days');
				$data['od_alloted_days'] = $request->input('od_alloted_days');
			}
			else
			{
				$data['od_start_date'] = '';
				$data['od_end_date'] = '';
				$data['od_no_of_days'] = '';
				$data['od_alloted_days'] = '';
			}
			$update = DB::table('hr_leaves_t')->where('leave_id',$edit_id)->update($data);
		    $leaveapplication  = leaveapplication::findOrFail($edit_id); 
		    $table = $leaveapplication->getTable();
			$column = $leaveapplication->getKeyName();
			$this->hrmssaveinsert($table,$column,$edit_id,2);
                           // auditlog
              $this->auditlog($edit_id,"leave","edit",$_POST,"hr_leaves_t");
			return 2;
		}
    }
    
       /*** leave save function end **/

      
    /** available leave based check  funcation start **/
    public function leavecheck(Request $request)
    {
        $employee_id = $_GET['employee_id'];
        $end_date    = $_GET['end_date'];
        $start_date  = $_GET['start_date'];
        $no_of_days  = $_GET['no_of_days'];
        $leave_type  = $_GET['leave_type'];
       
        $query = DB::table('hr_leaves_t')->where('employee_id',$employee_id)->get();
        
        if($leave_type == 130)
        {
            if($no_of_days == 1)
            {
                list($year,$month) = explode('-',$start_date);
                $users = DB::table('hr_leaves_t')->whereMonth('start_date',$month)->whereYear('start_date',$year)->get();

                if(count($users)==0)
                {
                    $year   = $year;
                    $month = $month-1;
                    $i=1;
                    $no_days = 0;
                    if($i!= $month)
                    {
                        $mont_list = array();
                        while($i <= $month)
                        {
                            $i = sprintf('%02d',$i); 
                            $query1 = \DB::select("SELECT hr_leaves_t.no_of_days as count_days from hr_leaves_t WHERE employee_id='1' and ( '$i' BETWEEN month(start_date) AND month(end_date)) and ( '$year' BETWEEN year(start_date) AND year(end_date))");
                            if(count($query1)>0)
                            {
                                $no_days += $query1[0]->count_days;
                            }
                            $mont_list[] = $i;
                            $i++;
                        }   
                    }
                    if($no_days < 12)
                    {
                        $remain_days = 12-$no_days;
                    }
                    else
                    {
                        $remain_days = 0;
                    }
                    $employee_details = Employeecreate::find(1);
                    $cl = $employee_details->c_l;   
                    $data['leave_days'] = $no_days;
                    $data['remain_leave'] = $remain_days;
                    $data['cl'] = $cl;
                    $month  = $month;
                } 
                else
                {
                    $year   = $year;
                    $month = $month;
                    $i=1;
                    $no_days = 0;
                    if($i!= $month)
                    {
                        $mont_list = array();
                        while($i <= $month)
                        {
                            $i = sprintf('%02d',$i); 

                            $query1 = \DB::select("SELECT hr_leaves_t.no_of_days as count_days from hr_leaves_t WHERE employee_id='1' and ( '$i' BETWEEN month(start_date) AND month(end_date)) and ( '$year' BETWEEN year(start_date) AND year(end_date))");
                            if(count($query1)>0)
                            {
                                $no_days += $query1[0]->count_days;
                            }
                            $mont_list[] = $i;
                            $i++;
                        }   
                    }
                    if($no_days < 12)
                    {
                        $remain_days = 12-$no_days;
                    }
                    else
                    {
                        $remain_days = 0;
                    }
                    $employee_details = Employeecreate::find(1);
                    $cl = $employee_details->c_l;
                    $data['leave_days'] = $no_days;
                    $data['remain_leave'] = $remain_days;
                    $data['cl'] = $cl;
                }
            }
            else
            {

                list($year,$month) = explode('-',$start_date);
                $year   = $year;
                $month = $month;
                $i=1;
                $no_days = 0;
                if($i!= $month)
                {
                    $mont_list = array();
                    while($i <= $month)
                    {
                        $i = sprintf('%02d',$i); 

                        $query1 = \DB::select("SELECT hr_leaves_t.no_of_days as count_days from hr_leaves_t WHERE employee_id='1' and ( '$i' BETWEEN month(start_date) AND month(end_date)) and ( '$year' BETWEEN year(start_date) AND year(end_date))");
                        if(count($query1)>0)
                        {
                            $no_days += $query1[0]->count_days;
                        }
                        $mont_list[] = $i;
                        $i++;
                    }   
                }
                if($no_days < 12)
                {
                    $remain_days = 12-$no_days;
                }
                else
                {
                    $remain_days = 0;
                }

                $employee_details = Employeecreate::find(1);
                $cl = $employee_details->c_l;

                $data['leave_days'] = $no_days;
                $data['remain_leave'] = $remain_days;
                $data['cl'] = $cl;
                $month  = $month;
            }
        }
        else if($leave_type  == 131  || $leave_type  == 132)
        {
          
            
            $user = DB::table('hr_employee_t')->where('employee_id',1)->get();
           
            if(count($user)>0 && $leave_type == 131)
            { 
                $data['cl'] = $user[0]->s_l;
            }
            else if(count($user)>0 && $leave_type == 132)
            {
               
                $data['cl'] = $user[0]->e_l;
            }
            else
            { 
                $data['cl'] = 0;
            }
        }
        //dd($data);
       
        return $data;
    }
	   /** available leave based check  funcation end **/
	  /** leave approval data load funcation start **/
	public function leaveapprovalindex(Request $request)
    {
		$logged_user = Session::get('emp_id');
		$wh='';
		 $gid=\Session('groupid');
		 if($gid !=1 && $gid!=2){
        $wh .= "and (hr_leaves_t.forwarded_id like '$logged_user' or  hr_leaves_t.forwarded_id like '$logged_user,%' or hr_leaves_t.forwarded_id like '%,$logged_user' or hr_leaves_t.forwarded_id like '%,$logged_user,%' )";
		 }
       //$wh .= "and hr_leaves_t.leave_status ='INITIATED'";
			$SQL = "SELECT
                    hr_leaves_t.leave_id,
                    hr_leaves_t.employee_id,
                    hr_leaves_t.start_date,
                    hr_leaves_t.end_date,
                    hr_leaves_t.no_of_days,
                    hr_leaves_t.alloted_days,
                    hr_leaves_t.forwarded_id,
                    hr_leaves_t.approval_reason,
                    hr_leaves_t.approvel_comments,
                    hr_leaves_t.leave_type,
                    concat(hr_employee_f.employee_number,'-',hr_employee_f.first_name) as forwarded_name,
                    concat(hr_employee_name.employee_number,'-',hr_employee_name.first_name) as employee_name,
                    hr_leaves_t.employee_id,
                    hr_leaves_t.leave_reason,
                    hr_leaves_t.organization_id,
                    hr_leaves_t.leave_status,
                    hr_leaves_t.od_start_date,
                    hr_leaves_t.od_end_date,
                    hr_leaves_t.od_no_of_days,
                    hr_leaves_t.leave_mode,
                    hr_leaves_t.leave_reason,
                    hr_leaves_t.od_alloted_days,
                    hr_leaves_t.leave_status
					
                    FROM
                        hr_leaves_t
                    LEFT JOIN hr_employee_t as hr_employee_f ON hr_employee_f.employee_id = hr_leaves_t.forwarded_id
                    LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_leaves_t.employee_id
                    WHERE
                        1 = 1 $wh ";
     
       
				$this->data['result'] = json_encode(\DB::select($SQL));
			//	dd($this->data['result']);
        return view('leaves.approveleave',$this->data);
    }
	 /** leave approval data load funcation end **/
	
	
/** approve leave  create page open function start **/
	public function leaveapprove(Request  $request,$id =null)
	{
		$edit_id = $id;
        
		$result = DB::table('hr_leaves_t')->where('leave_id',$edit_id )->get();
		
		if(count($result)>0)
		{
			
                $this->data['edit_id'] = $edit_id;
                $this->data['logged_id'] = $result[0]->employee_id;
        	$this->data['organisation_id'] = $result[0]->organization_id;	
        	$this->data['leave_type'] = $result[0]->leave_type;	
        	$this->data['leave_mode'] = $result[0]->leave_mode;	
        	$this->data['start_date'] = $result[0]->start_date;	
        	$this->data['end_date'] = $result[0]->end_date;	
        	$this->data['no_of_days'] = $result[0]->no_of_days;	
        	$this->data['leave_reason'] = $result[0]->leave_reason;	
        	$this->data['leave_status'] = $result[0]->leave_status;	
        	$this->data['forwarded_id'] = $result[0]->forwarded_id;	
        	$this->data['organization_id'] = $result[0]->organization_id;	
        	$this->data['leave_combo'] = $result[0]->leave_combo;	
        	
			
		}
		

		
		return view('leaves.approveform',$this->data);
	      	
	}
	/** approve leave  create page open function end **/
        /*** leave approve  save funcation start **/
	public function leaveapprovesave(Request  $request,$id =null)
	{
	    
	   // dd($_POST);
	    
		$edit_id = $request->input('edit_id');
		$alloted_days = $request->input('alloted_days');
		$approval_reason = $request->input('approval_reason');
		$approval_comments = $request->input('approval_comments');
		$leave_status = $request->input('leave_status');
		$emp_id = $request->input('employee_id');
		
		$leave_balance=\DB::select("select * from Leave_balance_tbl where employee_id='$emp_id'");
		
		if($leave_status=="APPROVE" && count($leave_balance)>0)
		{
		 if($id==130)
     {
        $remaining= $leave_balance[0]->causal_leave-$alloted_days;
        if($remaining<0)
        $remaining=0;
        
        \DB::update("update Leave_balance_tbl set causal_leave='$remaining' where leave_balance_id='".$leave_balance[0]->leave_balance_id."'");
        
     }
     else if($id==131)
     {
        $remaining= $leave_balance[0]->sick_leave-$alloted_days;
        
           if($remaining<0)
        $remaining=0;
        
        \DB::update("update Leave_balance_tbl set causal_leave='$remaining' where sick_leave='".$leave_balance[0]->leave_balance_id."'");
         
     }
     else if($id==132)
     {
           $remaining= $leave_balance[0]->earn_leave-$alloted_days;
           
              if($remaining<0)
        $remaining=0;
        
        \DB::update("update Leave_balance_tbl set causal_leave='$remaining' where earn_leave='".$leave_balance[0]->leave_balance_id."'");
       
     }
		}
		
		
		$update_result = DB::table('hr_leaves_t')->where('leave_id',$edit_id)->update(array('alloted_days'=>$alloted_days,'approval_reason'=>$approval_reason,'approvel_comments'=>$approval_comments,'leave_status'=>$leave_status));
		$update_result_data = DB::table('hr_leaves_t')->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_leaves_t.leave_type')->where('hr_leaves_t.leave_id',$edit_id)->where('a_lookuplines_t.lookup_code','ON-DUTY')->get();
		// auditlog
              $this->auditlog($edit_id,"leave","approve",$_POST,"hr_leaves_t");
		if(count($update_result_data)>0){
			if($update_result_data[0]->leave_status=="APPROVE"){
				
				if($alloted_days>1){
$period = new DatePeriod(
     new DateTime($update_result_data[0]->start_date),
     new DateInterval('P1D'),
     new DateTime($update_result_data[0]->end_date)
);
			// for on duty record insert in travel claim  for morethan one day
					foreach($period as $k1=>$v1){
				 DB::table("hr_employee_travel_claim_t")->insert(['employee_id'=>$update_result_data[0]->employee_id,'claim_title'=>'ON-DUTY','travel_date'=>$v1->format('Y-m-d'),'forwarded_id'=>$update_result_data[0]->forwarded_id,'location_id'=>$update_result_data[0]->location_id,'company_id'=>$update_result_data[0]->company_id,'created_at'=>date('Y-m-d'),'created_by'=>$update_result_data[0]->created_by]);
					}
					DB::table("hr_employee_travel_claim_t")->insert(['employee_id'=>$update_result_data[0]->employee_id,'claim_title'=>'ON-DUTY','travel_date'=>$update_result_data[0]->end_date,'forwarded_id'=>$update_result_data[0]->forwarded_id,'location_id'=>$update_result_data[0]->location_id,'company_id'=>$update_result_data[0]->company_id,'created_at'=>date('Y-m-d'),'created_by'=>$update_result_data[0]->created_by]);
			}else{
						// for on duty record insert in travel claim  for  one day
					DB::table("hr_employee_travel_claim_t")->insert(['employee_id'=>$update_result_data[0]->employee_id,'claim_title'=>'ON-DUTY','travel_date'=>$update_result_data[0]->start_date,'forwarded_id'=>$update_result_data[0]->forwarded_id,'location_id'=>$update_result_data[0]->location_id,'company_id'=>$update_result_data[0]->company_id,'created_at'=>date('Y-m-d'),'created_by'=>$update_result_data[0]->created_by]);
					
					
				}
		}
		}
		return 1;	   	
	}
        /*** leave approve save funcation end **/

    /*** partial day index function start **/
       public function indexpartial()
    {
     
        $company_id = Session::get('companyid');
		$wh='';
        $wh .= " and hr_partial_day.company_id=' $company_id' ";
		
        $SQL = "SELECT hr_partial_day.* from hr_partial_day WHERE  1 = 1 $wh";
	$this->data['result'] = json_encode(\DB::select($SQL));
        return view('leaves.formpartial',$this->data);
    }
      public function leavetypebase($id=null)
    {
     //  dd($id);
     $emp_id=$_GET['employee_id'];
     
     $leave_balance=\DB::select("select * from Leave_balance_tbl where employee_id='$emp_id'");
     
     $remaining=0;
     
     if($id==130)
     {
        $remaining= $leave_balance[0]->causal_leave;
        
     }
     else if($id==131)
     {
        $remaining= $leave_balance[0]->sick_leave;
         
     }
     else if($id==132)
     {
           $remaining= $leave_balance[0]->earn_leave;
       
     }
     else
     {
       $remaining=-1;  
     }
     
        return $remaining;
    }
	    /*** partial day index function end **/
        /** partial leave function start **/
     public function partialsave(Request $request)
    {
        
		$edit_id = $request->input('edit_id');
		
		if($edit_id == '')
		{
			$partialday = new partialday();
			$partialday->id = $request->input('id');
			$partialday->partial_date = $leave_mode = $request->input('partial_date');
			$partialday->start_time = $request->input('start_time');
			$partialday->end_time = $request->input('end_time');
			$partialday->description = $request->input('description');
			$partialday->active = $request->input('active');
			$partialday->save();
			$id = $partialday->id;
                        $table = $partialday->getTable();
			$column = $partialday->getKeyName();
			$this->hrmssaveinsert($table,$column,$id,1);
                        // auditlog
              $this->auditlog($id,"partialday","create",$_POST,"hr_holiday_t");
			return 1;
		}
		else
		{
			
			$data['id'] =$edit_id= $request->input('edit_id');
			$data['partial_date'] = $request->input('partial_date');
			$data['start_time'] = $request->input('start_time');
			$data['end_time'] = $leave_status = $request->input('end_time');
		        $data['description'] = $request->input('description');
		        $data['active'] = $request->input('active');
			$update = DB::table('hr_partial_day')->where('id',$edit_id)->update($data);
                        $partialday  = partialday::findOrFail($edit_id); 
                        $table = $partialday->getTable();
			$column = $partialday->getKeyName();
			$this->hrmssaveinsert($table,$column,$edit_id,2);
                        // auditlog
              $this->auditlog($edit_id,"partialday","edit",$_POST,"hr_holiday_t");
			return 2;
		}
    }
    
     /** partial leave function end **/
	/** leave report function start **/
	public function leavereportindex()
    {
			
		$company_id = Session::get('companyid');
        $emp=\Session::get('emp_id');
		$wh='';
        $wh .= " and hr_leaves_t.company_id=' $company_id' ";
        $log_id=Session::get('emp_id');
         /*if($log_id!=1)
	 $wh .= " and (hr_leaves_t.employee_id=' $log_id' or hr_leaves_t.forwarded_id='$log_id' ) ";*/


        $emp_data   =   DB::table("hr_employee_t")->where('employee_id',$emp)->get();
        $dept=json_decode($emp_data[0]->department);
		/* check user's department is payroll */
        if (in_array(28, $dept))
        {
         $wh.='';
        }else{
            if($emp!="1"){
                $wh.="and hr_leaves_t.employee_id=$emp"; 
            }
        }
         


         $query_result = \DB::select("SELECT hr_employee_t.department,hr_leaves_t.leave_id,hr_leaves_t.created_at,  hr_leaves_t.employee_id, hr_leaves_t.start_date,hr_leaves_t.end_date,  hr_leaves_t.no_of_days,hr_leaves_t.alloted_days, hr_leaves_t.forwarded_id, hr_leaves_t.approval_reason, hr_leaves_t.approvel_comments, hr_leaves_t.leave_type,hr_leaves_t.leave_type,hr_leaves_t.employee_id, hr_leaves_t.leave_reason, hr_leaves_t.organization_id,hr_leaves_t.leave_status,hr_leaves_t.od_start_date,hr_leaves_t.od_end_date,hr_leaves_t.od_no_of_days, hr_leaves_t.leave_mode, hr_leaves_t.leave_reason, hr_leaves_t.od_alloted_days,CONCAT(hr_employee_name.employee_number,'-', hr_employee_name.first_name) as employee_name,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as reporting_name FROM   hr_leaves_t  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_leaves_t.forwarded_id LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_leaves_t.employee_id WHERE  1 = 1 $wh");
	
         if(count($query_result)>0){
         foreach($query_result  as $k=>$v  ){
             if($v->department!='' && $v->department!=null){
			$array=json_decode($v->department);
		 $query = DB::table('m_department_lines_t')->whereIn('department_line_id',$array)->get();
	    $deptnames = json_decode(json_encode($query), true);	
		$query_result[$k]->department=implode(' , ',array_column($deptnames,'sub_department_name'));	
             }else{
                 $query_result[$k]->department="";
             }
		}
        }
        
        $this->data['result'] = json_encode($query_result);
        
        
        return view('leaves.leavereport',$this->data);
    }     	
																						  
	/** leave report function end **/
    /** holiday index page function start **/
     public function holiday()
    {
         return view('leaves.holidayform',$this->data);
    }
	  /** holiday index page function end **/
      /** holiday grid load function start **/
	public function getholidaygriddata()
    {
        
		$wh='';
                $comp=\Session::get('companyid');
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		$search_tables=["m_company_t","m_location_t"];
       if($_GET['_search']=='true'){
         $wh=$this->jqgridsearch("hr_holiday_t",$_GET['filters'],$search_tables);
       }
        
		if(!$sidx) $sidx =1;

		$result = \DB::select("SELECT COUNT(holiday_id) AS count FROM hr_holiday_t  LEFT JOIN m_company_t ON m_company_t.company_id = hr_holiday_t.company LEFT JOIN m_location_t ON m_location_t.location_id = hr_holiday_t.location where 1=1 and hr_holiday_t.company_id=$comp $wh ORDER BY $sidx $sord");
		$count = $result[0]->count;
		if( $count > 0 && $limit > 0)
		{
			$total_pages = ceil($count/$limit);
		}
		else
		{
			$total_pages = 0;
		}

		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;

        
		$SQL = "SELECT
                   hr_holiday_t.holiday_name,
				   hr_holiday_t.date,
				   hr_holiday_t.holiday_id,
				   hr_holiday_t.company,
				   hr_holiday_t.location,
				   hr_holiday_t.active,
				   m_company_t.company_name,
				   m_location_t.location_name
				   FROM
                        hr_holiday_t
				   LEFT JOIN m_company_t ON m_company_t.company_id = hr_holiday_t.company
				   LEFT JOIN m_location_t ON m_location_t.location_id = hr_holiday_t.location
				   WHERE 1 = 1 and hr_holiday_t.company_id=$comp $wh ORDER BY $sidx $sord LIMIT $start,$limit";
           
			$result = \DB::select($SQL);
			$responce->rows[]='';
			$responce->rows=$result;
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;

			echo json_encode($responce);
		
    }
          /** holiday grid load function end **/
     /** holiday save function start **/
	  public function holidaysave(Request $request)
    {
  
        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
            
            $empallow = new holiday();
            $empallow->holiday_name = $request->input('holiday_name');
            $empallow->date =  $request->input('date');
            $empallow->company =  $request->input('company');
            $empallow->location =  json_encode($request->input('location'));
            $empallow->active       = $request->input('active');
            $empallow->save(); 
            $name = $empallow->getKeyName();
            $id = $empallow->$name; 
            $table = $empallow->getTable();
            $column = $empallow->getKeyName();
            $this->hrmssaveinsert($table,$column,$id,1);
            // auditlog
              $this->auditlog($id,"holiday","create",$_POST,"hr_holiday_t");
            return 1;
        }
        else
        { 
            $empallow = new holiday();
            $edit_id=$_POST['edit_id'];
            $_POST['location']=json_encode($_POST['location']);
            holiday::find($edit_id)->update($_POST); 
            $table = $empallow->getTable();
            $column = $empallow->getKeyName();
            $this->hrmssaveinsert($table,$column,$edit_id,2);
            // auditlog
              $this->auditlog($edit_id,"holiday","edit",$_POST,"hr_holiday_t");
            return 2;
        }
    }
     
        /** holiday save function end **/


      public function destroy(holiday $holiday,$id=null)
    {

     
         $del_id = $_GET['del_id'];
       
        
        $j=0;   
       
        if($j==0)
        {
            $query = DB::table('hr_holiday_t')->where('holiday_id',$del_id)->delete();
            // auditlog
            $this->auditlog($del_id,"holiday","create",$_GET,"hr_holiday_t");
            
        }
       
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
        
       
    }



     public function partialdestroy(holiday $holiday,$id=null)
    {

     
         $del_id = $_GET['del_id'];
       
        
        $j=0;   
       
        if($j==0)
        {
            $query = DB::table('hr_partial_day')->where('id',$del_id)->delete();
            // auditlog
            $this->auditlog($del_id,"partial","create",$_GET,"hr_partial_day");
            
        }
       
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
        
       
    }



















}

