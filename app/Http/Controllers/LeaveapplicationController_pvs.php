<?php

namespace App\Http\Controllers;

use App\leaveapplication;
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

class LeaveapplicationController extends Controller
{
    public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	 }
         //** Leave page index open funcation start **/
    public function index()
    {
        $logged_user = Session::get('emp_id');
        $result = DB::table('hr_employee_t')->where('employee_id',$logged_user)->get();
        $this->data['logged_id'] = $logged_user;
        $this->data['forwarded_id'] = $result[0]->reporting_manager;
        $logged_user = Session::get('emp_id');
        $company_id = Session::get('companyid');
		$wh='';
        $wh .= "and hr_leaves_t.employee_id ='$logged_user' and hr_leaves_t.company_id=' $company_id' ";
		
        $SQL = "SELECT a_lookuplines_t.lookup_code as leave_type_name,hr_leaves_t.start_date_time,hr_leaves_t.end_date_time,hr_leaves_t.no_of_hrs,hr_leaves_t.leave_id,  hr_leaves_t.employee_id, hr_leaves_t.start_date,hr_leaves_t.end_date,  hr_leaves_t.no_of_days,hr_leaves_t.alloted_days, hr_leaves_t.forwarded_id, hr_leaves_t.approval_reason, hr_leaves_t.approvel_comments, hr_leaves_t.leave_type,hr_leaves_t.leave_type, CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name )as forwarded_name,concat(hr_employee_name.employee_number,'-',hr_employee_name.first_name) as employee_name,hr_leaves_t.employee_id, hr_leaves_t.leave_reason, hr_leaves_t.organization_id,hr_leaves_t.leave_status,hr_leaves_t.od_start_date,hr_leaves_t.od_end_date,hr_leaves_t.od_no_of_days, hr_leaves_t.leave_mode, hr_leaves_t.leave_reason, hr_leaves_t.od_alloted_days FROM   hr_leaves_t  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_leaves_t.forwarded_id LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_leaves_t.employee_id left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_type WHERE  1 = 1 $wh order by  hr_leaves_t.leave_id  desc ";
	$this->data['result'] = json_encode(\DB::select($SQL));
   
        return view('leaves.form',$this->data);
    }
	 //** Leave page index open funcation end **/
/*** leave remove funcation start **/
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
    public function save(leaveapplication $leaveapplication,Request $request)
    {
        
		$edit_id = $request->input('edit_id');
		
		if($edit_id == '')
		{
                  
			$leaveapplication = new leaveapplication();
			$leaveapplication->employee_id = $request->input('employee_id');
			$leaveapplication->leave_mode = $leave_mode = $request->input('leave_mode');
			$x = ($leave_mode == 134) ? '' : '1';
			$leaveapplication->start_date = $request->input('start_date'.$x);
			$leaveapplication->end_date = $request->input('end_date'.$x);
			$leaveapplication->no_of_days = $request->input('no_of_days');
			$leaveapplication->alloted_days = $request->input('alloted_days');
			$leaveapplication->leave_type= $leave_type = $request->input('leave_type');
			$leaveapplication->leave_reason = $request->input('reason');
			$leaveapplication->forwarded_id = $request->input('forwarded_id');
		    $leave_status =$leaveapplication->leave_status=  $request->input('leave_status');
			$leaveapplication->approval_reason   = '';
			$leaveapplication->approvel_comments = '';
                        $leaveapplication->start_date_time =$request->input('start_date_time');
                        $leaveapplication->end_date_time =$request->input('end_date_time');
                        $leaveapplication->no_of_hrs =$request->input('no_of_hrs');
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
			$leaveapplication->save();
			$id = $leaveapplication->leave_id;
                        $table = $leaveapplication->getTable();
			$column = $leaveapplication->getKeyName();
			$this->hrmssaveinsert($table,$column,$id,1);
                        // auditlog
              $this->auditlog($id,"leave","create",$_POST,"hr_leaves_t");
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
			$data['start_date_time'] =$request->input('start_date_time');
                       $data['end_date_time'] =$request->input('end_date_time');
                        $data['no_of_hrs'] =$request->input('no_of_hrs');
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
        $wh .= "and hr_leaves_t.forwarded_id ='$logged_user'";
        $wh .= "and hr_leaves_t.leave_status ='INITIATED'";
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
                    hr_leaves_t.leave_status,
                    a_lookuplines_t.lookup_code  as leave_type_name,
                    hr_leaves_t.start_date_time,
                    hr_leaves_t.end_date_time,
                    hr_leaves_t.no_of_hrs
					
                    FROM
                        hr_leaves_t
                    LEFT JOIN hr_employee_t as hr_employee_f ON hr_employee_f.employee_id = hr_leaves_t.forwarded_id
                    LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_leaves_t.employee_id
                    LEFT JOIN a_lookuplines_t  ON a_lookuplines_t.lookuplines_id = hr_leaves_t.leave_type
                    WHERE
                        1 = 1 $wh ";
     
       
				$this->data['result'] = json_encode(\DB::select($SQL));
        return view('leaves.approveleave',$this->data);
    }
	 /** leave approval data load funcation end **/
	
	
/** approve leave  create page open function start **/
	public function leaveapprove(Request  $request,$id =null)
	{
		$edit_id = $id;
        
		$result = DB::table('hr_leaves_t')->where('leave_id',$edit_id )->get();
		$employee_data= DB::table('hr_employee_t')->where('employee_id',$result[0]->employee_id )->get();
		if(count($result)>0)
		{
			
                $this->data['edit_id'] = $edit_id;
                $this->data['logged_id'] = $result[0]->employee_id;
        	$this->data['organisation_id'] = $result[0]->organization_id;	
        	$this->data['leave_type'] = $result[0]->leave_type;	
        	$this->data['leave_mode'] = $result[0]->leave_mode;	
        	$this->data['start_date'] =$result[0]->start_date;	
        	$this->data['end_date'] = $result[0]->end_date;
                $this->data['start_date_time'] = $result[0]->start_date_time;	
        	$this->data['end_date_time'] = $result[0]->end_date_time;
                $this->data['no_of_hrs'] = $result[0]->no_of_hrs;
                $this->data['alloted_hrs'] = $result[0]->alloted_hrs;
        	$this->data['no_of_days'] = $result[0]->no_of_days;	
        	$this->data['leave_reason'] = $result[0]->leave_reason;	
        	$this->data['leave_status'] = $result[0]->leave_status;	
        	$this->data['forwarded_id'] = $result[0]->forwarded_id;	
        	$this->data['organization_id'] = $result[0]->organization_id;	
		$this->data['c_l'] = $employee_data[0]->c_l;  
            $this->data['s_l'] = $employee_data[0]->s_l;    
            $this->data['e_l'] = $employee_data[0]->e_l;    
		}
		return view('leaves.approveform',$this->data);
	      	
	}
        	public function permissionslipprint(Request $request,$id=null,$type=null)
    {
        $leave_list = DB::table('hr_leaves_t')
                ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_leaves_t.employee_id')
                ->leftjoin('hr_employee_t as reporting','reporting.employee_id','=','hr_leaves_t.forwarded_id')
                ->select('hr_leaves_t.start_date_time','hr_leaves_t.end_date_time','hr_leaves_t.no_of_hrs','hr_employee_t.first_name','reporting.first_name as reporting_name','hr_employee_t.employee_type')
                ->where('hr_leaves_t.leave_id',$id)
                ->get();;
        $company_id = Session::get('companyid');
        $comp = DB::table('m_company_t')->where('company_id',$company_id)->get();
        //dd($comp);
        $this->data['company_name'] = $comp[0]->company_name;
        $this->data['logo'] = Session::get('companylogo');
        $this->data['company_address'] = "Chennai";
        $this->data['leave_list'] =$leave_list;
        $this->data['start_date_time'] =$leave_list[0]->start_date_time;
        $this->data['end_date_time'] =$leave_list[0]->end_date_time;
        $this->data['no_of_hrs'] =$leave_list[0]->no_of_hrs;
        $this->data['employee_name'] =$leave_list[0]->first_name;
        $this->data['forwarded_id'] =$leave_list[0]->reporting_name;
    
        $type_letter_contect= DB::table('a_lookuplines_t')->where('lookup_type',"LETTER_TYPE")->where('lookup_meaning',"Permission Slip")->get();
        if(count($type_letter_contect)>0){
        $letter_content = DB::table('m_letter_content')->where('employee_type',$leave_list[0]->employee_type )->where('company_id',\Session::get('companyid') )->where('letter_type',$type_letter_contect[0]->lookuplines_id)->where('active',"Yes" )->orderBy('id', 'desc')->get();

        //dd($letter_content);
    if(count($letter_content)>0){
        $this->data['letter_content'] =$letter_content;
       }
       else{
        $this->data['letter_content'] ='';
       }
        }
       else{
        $this->data['letter_content'] ='';
       }
         $director_details = DB::table('hr_employee_t')->leftjoin('m_job_title','m_job_title.job_title_id','hr_employee_t.job_title')->where('m_job_title.job_title_name','Director')->get();
       if(count($director_details)>0){
           $this->data['director'] =$director_details[0]->job_title_name;
       }else{
          $this->data['director'] =''; 
       }
         return view('leaves.permissionslip',$this->data);
    }
	/** approve leave  create page open function end **/
        /*** leave approve  save funcation start **/
	public function leaveapprovesave(Request  $request,$id =null)
	{
        
		$edit_id = $request->input('edit_id');
		$alloted_days = $request->input('alloted_days');
		$alloted_hrs = $request->input('alloted_hrs');
		$approval_reason = $request->input('approval_reason');
		$approval_comments = $request->input('approval_comments');
		$leave_status = $request->input('leave_status');
		
		$update_result = DB::table('hr_leaves_t')->where('leave_id',$edit_id)->update(array('alloted_hrs'=>$alloted_hrs,'alloted_days'=>$alloted_days,'approval_reason'=>$approval_reason,'approvel_comments'=>$approval_comments,'leave_status'=>$leave_status));
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
         


         $query_result = \DB::select("SELECT hr_leaves_t.no_of_hrs,hr_leaves_t.alloted_hrs,hr_employee_t.department,hr_leaves_t.leave_id,hr_leaves_t.created_at,  hr_leaves_t.employee_id, hr_leaves_t.start_date,hr_leaves_t.end_date,  hr_leaves_t.no_of_days,hr_leaves_t.alloted_days, hr_leaves_t.forwarded_id, hr_leaves_t.approval_reason, hr_leaves_t.approvel_comments, hr_leaves_t.leave_type,hr_leaves_t.leave_type,hr_leaves_t.employee_id, hr_leaves_t.leave_reason, hr_leaves_t.organization_id,hr_leaves_t.leave_status,hr_leaves_t.od_start_date,hr_leaves_t.od_end_date,hr_leaves_t.od_no_of_days, hr_leaves_t.leave_mode, hr_leaves_t.leave_reason, hr_leaves_t.od_alloted_days,CONCAT(hr_employee_name.employee_number,'-', hr_employee_name.first_name) as employee_name,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as reporting_name FROM   hr_leaves_t  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_leaves_t.forwarded_id LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_leaves_t.employee_id WHERE  1 = 1 $wh");
	
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
    
 public function getleavegrid()
        {
                  $company_id = Session::get('companyid');
        $emp=\Session::get('emp_id');
		$wh='';
        $wh .= " and v1.company_id=' $company_id' ";
        $log_id=Session::get('emp_id');
         /*if($log_id!=1)
	 $wh .= " and (hr_leaves_t.employee_id=' $log_id' or hr_leaves_t.forwarded_id='$log_id' ) ";*/


        $emp_data   =   DB::table("hr_employee_t")->where('employee_id',$emp)->get();
        $dept=json_decode($emp_data[0]->department);
      //  dd($dept);
		/* check user's department is payroll */
        if (in_array(28, $dept))
        {
         $wh.='';
        }else{
            if($emp!="1"){
                $wh.="and v1.employee_id=$emp"; 
            }
        }
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
     if($_GET['_search']=='true')
    {
              $wh.=$this->jqgridsearchnotab('v1',$_GET['filters']);

    }
    if(!$sidx) $sidx =1;
    //  dd($wh);
    $result = \DB::select("select* from(SELECT 
        hr_leaves_t.leave_id,
        hr_leaves_t.company_id,
        hr_leaves_t.created_at,
        hr_leaves_t.start_date,
        hr_leaves_t.end_date,
        hr_leaves_t.no_of_days,
        hr_leaves_t.alloted_days,
        hr_leaves_t.forwarded_id,
        hr_leaves_t.approval_reason,
        hr_leaves_t.approvel_comments,
        hr_leaves_t.alloted_hrs,
        hr_leaves_t.no_of_hrs,
        a_lookuplines_t.lookup_code as leave_type,
        hr_leaves_t.employee_id,
        hr_leaves_t.leave_reason,
        hr_leaves_t.organization_id,
        hr_leaves_t.leave_status,
        hr_leaves_t.od_start_date,
        hr_leaves_t.od_end_date,
        hr_leaves_t.od_no_of_days,
        hr_leaves_t.leave_mode,
        m_department_lines_t.sub_department_name as department,
        hr_leaves_t.od_alloted_days,
        CONCAT(hr_employee_name.employee_number,'-', hr_employee_name.first_name) as employee_name,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as reporting_name 
        FROM hr_leaves_t 
        LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_leaves_t.forwarded_id 
        LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_leaves_t.employee_id
        LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_leaves_t.employee_id
        LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_leaves_t.leave_type
        WHERE 1 = 1 )v1 where 1=1 $wh");

    $count = COUNT($result);
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
    
    $SQL = "select* from(SELECT 
        hr_leaves_t.leave_id,
        hr_leaves_t.company_id,
        hr_leaves_t.created_at,
        hr_leaves_t.start_date,
        hr_leaves_t.end_date,
        hr_leaves_t.no_of_days,
        hr_leaves_t.alloted_days,
        hr_leaves_t.forwarded_id,
        hr_leaves_t.approval_reason,
        hr_leaves_t.approvel_comments,
          hr_leaves_t.alloted_hrs,
        hr_leaves_t.no_of_hrs,
        a_lookuplines_t.lookup_code as leave_type,
        hr_leaves_t.employee_id,
        hr_leaves_t.leave_reason,
        hr_leaves_t.organization_id,
        hr_leaves_t.leave_status,
        hr_leaves_t.od_start_date,
        hr_leaves_t.od_end_date,
        hr_leaves_t.od_no_of_days,
        hr_leaves_t.leave_mode,
        hr_leaves_t.od_alloted_days,
          hr_employee_t.department,
        CONCAT(hr_employee_name.employee_number,'-', hr_employee_name.first_name) as employee_name,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as reporting_name 
        FROM hr_leaves_t 
        LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_leaves_t.forwarded_id 
        LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_leaves_t.employee_id
        LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_leaves_t.leave_type
        WHERE 1 = 1 )v1 where 1=1 $wh ORDER BY $sidx $sord LIMIT $start,$limit "; 


      $download_SQL = "select* from(SELECT 
        hr_leaves_t.leave_id,
        hr_leaves_t.company_id,
        hr_leaves_t.created_at,
        hr_leaves_t.start_date,
        hr_leaves_t.end_date,
        hr_leaves_t.no_of_days,
        hr_leaves_t.alloted_days,
        hr_leaves_t.forwarded_id,
        hr_leaves_t.approval_reason,
        hr_leaves_t.approvel_comments,
        a_lookuplines_t.lookup_code as leave_type,
        hr_leaves_t.employee_id,
        hr_leaves_t.leave_reason,
        hr_leaves_t.organization_id,
        hr_leaves_t.leave_status,
        hr_leaves_t.od_start_date,
        hr_leaves_t.od_end_date,
        hr_leaves_t.od_no_of_days,
        hr_leaves_t.leave_mode,
        hr_leaves_t.od_alloted_days,
        hr_employee_t.department,
        CONCAT(hr_employee_name.employee_number,'-', hr_employee_name.first_name) as employee_name,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as reporting_name 
        FROM hr_leaves_t 
        LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_leaves_t.forwarded_id 
        LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_leaves_t.employee_id
        LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_leaves_t.leave_type
        WHERE 1 = 1 )v1 where 1=1 $wh ORDER BY $sidx $sord "; 

                         $query_result = \DB::select($download_SQL);
                          if(count($query_result)>0){
           foreach($query_result  as $k=>$v  ){
               if($v->department!='' && $v->department!=null){
                $array=json_decode($v->department);
                $query = \DB::table('m_department_lines_t')->whereIn('department_line_id',$array)->get();
                $deptnames = json_decode(json_encode($query), true);    
                $query_result[$k]->department=implode(' , ',array_column($deptnames,'sub_department_name'));    
            }
            else{
               $query_result[$k]->department="";
           }
       }
   }
        $result1=collect($query_result)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }


        
            $result = \DB::select($SQL);
                      if(count($result)>0){
           foreach($result  as $k=>$v  ){
               if($v->department!='' && $v->department!=null){
                $array=json_decode($v->department);
                $query = \DB::table('m_department_lines_t')->whereIn('department_line_id',$array)->get();
                $deptnames = json_decode(json_encode($query), true);    
                $result[$k]->department=implode(' , ',array_column($deptnames,'sub_department_name'));    
            }
            else{
               $result[$k]->department="";
           }
       }
   }
            $responce->rows[]='';
            $responce->rows=$result;
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            echo json_encode($responce);
        
    }



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

