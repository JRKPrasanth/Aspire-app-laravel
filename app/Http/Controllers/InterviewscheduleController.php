<?php

namespace App\Http\Controllers;

use App\interviewschedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Input;
use Mail;
use DB;

class InterviewscheduleController extends Controller
{
 public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	 }
    public function index()
    {

    }

    /** Interview Schedule  Page Load Start **/
    public function create(Request $request)
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

        $schedule_interview     = Schema::getColumnListing('hr_schedule_interview');
        $this->data['row']= (object)array();
        foreach($schedule_interview as $key=>$value)
        {
            $this->data['row']->{$value}= '';
        }
       $user_id = Session('emp_id');
       $dept_id = Session('dept_id');
       $this->data['row']->employee            = $this->jcustomselecttool('add_resume','resume_id','name_of_the_candidate','','and select_status="0" and schedule_status="0"');
       $this->data['row']->job_description     = $this->jcustomselecttool('hr_job_description','description_id','description_name','',' and approve_status="3"');
        $this->data['pageUrl']="createinterview";
        
        return view('scheduleinterview.form',$this->data);
    }
    /** Interview Schedule  Page Load End **/

/** Interview Schedule  Search Start **/
	public function createsearch($id)
    {
		   if($_GET['job_desc']==0)
			   {
				     $this->data['pageUrl']="resumecollection";
			   }
				   else{
		  $this->data['pageUrl']="searchcandidate";
				   }
        $schedule_interview     = Schema::getColumnListing('hr_schedule_interview');
        $this->data['row']= (object)array();
        foreach($schedule_interview as $key=>$value)
        {
            $this->data['row']->{$value}= '';
        }
      $data_s= DB::table('add_resume')->where('resume_id',$id)->get();
      $data_s_new= DB::table('hr_job_description')->where('description_id',$id)->get();
		
        $user_id = Session('emp_id');
        $dept_id = Session('dept_id');
	
        $this->data['row']->employee = $this->jcustomselecttool('add_resume','resume_id','name_of_the_candidate',$data_s[0]->resume_id," and resume_id='".$data_s[0]->resume_id."'");
		  if($_GET['job_desc']==0){
        $this->data['row']->job_description     = $this->jCombologin('hr_job_description','description_id','description_name','');
		  }else{
			   $this->data['row']->job_description     = $this->jcustomselect('hr_job_description','description_id','description_name',$data_s[0]->resume_id," and description_id='".$data_s_new[0]->description_id."'");
		  }
		  
        return view('scheduleinterview.form',$this->data);
	  }
/** Interview Schedule  Search End **/

	/** Interview Schedule Save Start **/
    public function store(Request $request)
    {
		$edit_id = $request->input('edit_id');
       if($edit_id != "")
        {
            
            $interviewschedule  = interviewschedule::findOrFail($edit_id); 
            $input_data = $request->all();
			$middle = strtotime($request->interview_date);    
            $input_data->interview_date = date('Y-m-d H:i:s', $middle);
            $interviewschedule->fill($input_data)->save();
            $table = $interviewschedule->getTable();
            $column = $interviewschedule->getKeyName();
            $id = $interviewschedule->interview_id;
            $this->hrmssaveinsert($table,$column,$edit_id,2);
            // auditlog
            $this->auditlog($id,"Interview Schedule","create",$_POST,"hr_schedule_interview");
            return 2;
            
        }
        else
        {
            $interviewschedule = new interviewschedule();
            $interviewschedule->name_of_the_candidate = $candidate_id =  $candidate_name = $request->input('name_of_the_candidate');
            $interviewschedule->job_description_name = $request->input('job_description_name');
            $interviewschedule->remarks = $request->input('remarks');  
            $interviewschedule->active = $request->input('active'); 
			$middle = strtotime($request->input('interview_date'));    
            $interviewschedule->interview_date = $interview_date=date('Y-m-d H:i:s', $middle);
            $date = date('Y-m-d');
            $interviewschedule->date = $date; 
            $result = $interviewschedule->save();
			$id = $interviewschedule->interview_id;
			$table = $interviewschedule->getTable();
            $column = $interviewschedule->getKeyName();
            $this->hrmssaveinsert($table,$column,$id,1);
            DB::table('add_resume')->where('resume_id', $candidate_name)->update(array('schedule_status' => 1,'interview_date'=>$interview_date));
            
            $query = DB::table('add_resume')->where('resume_id',$request->name_of_the_candidate)->get();
            $interviewschedule->date= $data['interview_date'] = $interview_date;
            $job_description_name=\DB::select("SELECT hr_job_description.*,m_job_title.job_title_name from hr_job_description
            LEFT JOIN m_job_title on m_job_title.job_title_id=hr_job_description.job_title where description_id='$interviewschedule->job_description_name'");
            $company_id = \Session::get('companyid');
            $location_id =\Session::get('location');
            $address=\DB::select("SELECT m_company_t.company_name,m_location_t.location_name,m_location_t.address,m_location_t.street_name,
            m_area_t.area_name,m_states_t.state_name,m_cities_t.city_name,m_countries_t.country_name from m_company_t
            LEFT JOIN m_location_t ON m_location_t.location_id=m_company_t.location_id 
            LEFT JOIN m_area_t on m_area_t.area_id=m_location_t.area
            LEFT JOIN m_states_t on m_states_t.state_id=m_location_t.state_id
            LEFT JOIN m_cities_t on m_cities_t.city_id=m_location_t.city_id
            LEFT JOIN m_countries_t on m_countries_t.country_id=m_location_t.country_id
            where m_company_t.company_id=m_location_t.company_id and m_company_t.location_id=m_location_t.location_id and m_company_t.company_id='$company_id' and m_company_t.location_id='$location_id'");
            $data=\DB::select("SELECT company_name,email_id,website_address from m_company_t where company_id='$company_id'");
            $data['job_title']=$job_description_name[0]->job_title_name;
            $data['name_of_the_candidate']=$query[0]->name_of_the_candidate;
            $data['interview_date'] = $interview_date;
            $data['email']=$data[0]->email_id;
            $data['website_address']=$data[0]->website_address;
            $l_error=array();
            if(count($address)>0)
            {
                $company_name=@$address[0]->company_name;
                $data['company_name']= $company_name;
                $location_name=@$address[0]->location_name;
                $data['location_name']= $location_name;
                $address1=$address[0]->address;
                $data['address1']= $address1;
                $street =$address[0]->street_name;
                $data['street']= $street;
                $area=$address[0]->area_name;
                $data['area']= $area;
                $data['city']= $address[0]->city_name;
            }
            if($address==0)
            {
              //  dd("fnsdjfn");
            	array_push($l_error,"Please Check Company Address details");
            }
            $data['l_error'] = $l_error;
            $rest=\Mail::send('scheduleinterview.mail_details',$data, function($message)
              {    
                    $can_id=$_POST['name_of_the_candidate'];
                    $email=\DB::select("select * from add_resume where resume_id='$can_id'");
                    $message->to($email[0]->email);
                    $message->subject('Interview Schedule');
                    //$message->from('no-reply@ifive.in');    
                    if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
              });
              return response()->json(array(
                'status' => 'success',
                'candidate_id' =>$candidate_id,
                'message' => 'Saved Successfully'
    ));
            // auditlog
            $this->auditlog($id,"Interview Schedule","Update",$_POST,"hr_schedule_interview");
            return 1;
        }
    }
    /** Interview Schedule Save End **/
    
    
   
   /** Interview Schedule Udate Status Start **/
    public function save(Request $request,interviewschedule $interviewschedule)
    {
       
        $update_interview_id = $request->input('update_interview_id');
        if($update_interview_id != '')
        {
           
            $interviewschedule  = interviewschedule::findOrFail($update_interview_id); 
            
            $interviewschedule->name_of_the_candidate   = $name_of_the_candidate = $request->input('cand_name');
            $interviewschedule->job_description_name    = $job_description_name  = $request->input('description_id');
            $interviewschedule->remarks                 = $inter_process         = $request->input('inter_process');  
            $percentages                                = $request->input('percentages'); 
            $department_id                              = $request->input('department_id'); 
            $j_title                                    = $request->input('j_title'); 
            $process_status                             = $request->input('process_status'); 
            $interviewschedule->remarks                 = $remarks               = $request->input('ramarks1'); 
            $selection_status                           = $request->input('selection_status'); 
			if(($request->input('selection_status'))==1){
				$middle = strtotime($request->input('date_of_joining'));    
                $date_of_join=date('Y-m-d', $middle);
				$request->input('date_of_joining');
			$interviewschedule->date_of_joining         = $date_of_joining       = $date_of_join;	
				$job_desc_data = DB::table('hr_job_description')->where('description_id',$request->input('description_id'))->get();
				$add_selected=$job_desc_data[0]->no_of_persons_selected+1;
				DB::table('hr_job_description')->where('description_id',$request->input('description_id'))->update(array('no_of_persons_selected' => $add_selected));
				
			}else{
            $interviewschedule->date_of_joining         = $date_of_joining       = "";
			}
            $current_date = date('Y-m-d');
          // dd($date_of_joining);
            $interview_process= DB::table('interview_process_details')->insert(['interview_id' => $update_interview_id, 
                            'interview_process' =>$inter_process,
                            'date_of_joining'=>$date_of_joining,
                            'process_status'=>$process_status,
                            'percentage'=>$percentages,
                            'remarks'=>$remarks,
                            'date'=>$current_date,
                            'select_candidate'=>$selection_status,
                            'department'=>$department_id,
                            'job_title'=>$j_title,
                            'name_of_the_candidate'=>$name_of_the_candidate]);
            
            $date = date('Y-m-d');
            $interviewschedule->date = $date; 
          
            $result = $interviewschedule->save($request->all());
         
            if(($process_status == 1 || $process_status == 2) && ($selection_status == 1))
            {
              
               
                $job_desc = DB::table('hr_schedule_interview')->where('interview_id',$update_interview_id)->get();
               
                $description_id = $job_desc[0]->job_description_name;
               
                
                $job_description = DB::table('hr_job_description')->where('description_id',$description_id)->get(); 
                
                $no_of_person = $job_description[0]->no_of_persons;
                if(!empty($no_of_person))
                {
                    $no_of_person = $no_of_person-1;                
                }
                
                DB::table('hr_job_description')->where('description_id',$description_id)->update(['no_of_persons' => $no_of_person]);
            }
           
            $results = DB::table('hr_schedule_interview')->where('interview_id',$update_interview_id)->update(array('schedule_status' => $process_status,'interview_process'=>$interview_process,'select_candidate'=>$selection_status,'date_of_joining'=>$date_of_joining));
            $results1 = DB::table('add_resume')->where('resume_id',$name_of_the_candidate)->update(array('schedule_status'=>$process_status,'select_status'=>$selection_status));
            
            return 1;
        }
    }
    /** Interview Schedule Udate Status End **/
    
    /** Interview Schedule Delete Start **/
    public function destroy(interviewschedule $interviewschedule,$id=null)
    {
         $del_id = $_GET['del_id'];
       
   // dd($del_id);        
        $j=0;   
       
        if($j==0)
        {
            $query = DB::table('hr_schedule_interview')->where('interview_id',$del_id)->delete();
            // auditlog
            $this->auditlog($del_id,"Interview Schedule","create",$_GET,"hr_schedule_interview");
            
        }
       
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
        
       
    }
    /** Interview Schedule Delete End **/

    /** Jqgrid Interview Schedule load data Start **/
    public function interveiwschedulregriddata()
    {
	$wh='';
      $comp=\Session::get('companyid');  
	$page = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx = $_GET['sidx'];
	$sord = $_GET['sord'];
    $search_tables=["hr_schedule_interview","hr_job_description","m_interview_steps"];
       if($_GET['_search']=='true'){
         $wh=$this->jqgridsearch("add_resume",$_GET['filters'],$search_tables);
        
       }
	if(!$sidx) $sidx =1;
        
       

	$result = \DB::select("SELECT COUNT(hr_schedule_interview.interview_id) AS count FROM hr_schedule_interview left JOIN add_resume on add_resume.resume_id=hr_schedule_interview.name_of_the_candidate
        left JOIN hr_job_description on hr_job_description.description_id=hr_schedule_interview.job_description_name
        left JOIN m_interview_steps on m_interview_steps.interview_steps_id=hr_schedule_interview.interview_process where 1=1 and hr_schedule_interview.company_id=$comp $wh");
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
            
            add_resume.name_of_the_candidate,
             add_resume.resume_id,
            hr_job_description.description_name,
            hr_job_description.description_id,
            hr_job_description.job_title,
            hr_job_description.department,
            hr_job_description.interview_process as interview,
            hr_schedule_interview.date,
            hr_schedule_interview.interview_date,
            m_interview_steps.interview_process,
            hr_schedule_interview.schedule_status,
            hr_schedule_interview.job_description_name,
            hr_schedule_interview.interview_id,
            hr_schedule_interview.remarks,
            hr_schedule_interview.interview_process,
            hr_schedule_interview.active,
            (case 
                when hr_schedule_interview.schedule_status = 1 then 'selected'
                when hr_schedule_interview.schedule_status = 2 then 'Waiting List'
                when hr_schedule_interview.schedule_status = 3 then 'Rejected'
                when hr_schedule_interview.schedule_status = 0 then 'In Process'
            end) as result
            
        from hr_schedule_interview 
        left JOIN add_resume on add_resume.resume_id=hr_schedule_interview.name_of_the_candidate
        left JOIN hr_job_description on hr_job_description.description_id=hr_schedule_interview.job_description_name
        left JOIN m_interview_steps on m_interview_steps.interview_steps_id=hr_schedule_interview.interview_process  where 1=1 and hr_schedule_interview.schedule_status IN('0','2','1') and select_candidate IN('0','2') and hr_schedule_interview.company_id=$comp $wh ORDER BY $sidx $sord LIMIT $start,$limit";
        
	$result = \DB::select($SQL);
	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
       
	echo json_encode($responce);

}
/** Jqgrid Interview Schedule load data Start **/

/** Interview Schedule Cancel Start **/
	public function cancelschedule($id)
        {
            $cancel = DB::table('add_resume')->where('resume_id',$id)->update(['schedule_status' => 0]);
            $del_schedule_interview = DB::table('hr_schedule_interview')->where('name_of_the_candidate', $id)->delete();
            // auditlog
            $this->auditlog($id,"Interview Schedule","Cancel Schedule",$del_schedule_interview,"hr_schedule_interview");
            return 1;
           
        }
        /** Interview Schedule Cancel End **/
       
        
}
