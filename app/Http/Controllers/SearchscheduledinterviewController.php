<?php

namespace App\Http\Controllers;

use App\searchscheduledinterview;
use App\interviewschedule;
use Illuminate\Http\Request;
use DB;

class SearchscheduledinterviewController extends Controller
{
    
    public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	 }

	/** Search Schedule Interview Page load Start **/
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

		return view('interviewscheduled.form',$this->data);
    }
    /** Search Schedule Interview Page load End **/

	/** Search Data RE schedule_interview change date start **/
     public function searchdata(Request $request)
     {
      $employee_id 	=  (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) ? $_GET['employee_id'] : '';
      $start_date 	= (isset($_GET['start_date']) && !empty($_GET['start_date'])) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
      $end_date 	= (isset($_GET['end_date']) && !empty($_GET['end_date'])) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
		 
	   $query1 = DB::table('hr_schedule_interview')->where('name_of_the_candidate',$employee_id)->whereIn('schedule_status',[0,1,2])->whereIn('select_candidate',[0,2]);
		if($start_date != '')
			$query1->where('interview_date','>=', $start_date);
		if($end_date != '')
			$query1->where('interview_date','<=', $end_date);
		
		$query1 = $query1->get();
		 $html='';
		 if(count($query1) >0)
		 {
			 foreach($query1 as $key=>$value)
			 {
				 print_r($value->interview_id);
				 //$newDate = date("d-m-Y", strtotime($value->interview_date));
				 $sql = \DB::table('add_resume')->select('name_of_the_candidate')->where('resume_id',$value->name_of_the_candidate)->get();
				 $html.='<tr>';
				 $html.='<td style="width:5%">'.'<input type="radio" data-date="'.$value->interview_date.'" value="'.$value->interview_id.'" name ="interview_id" class ="interview_id"/>'.'</td>';
				 $html.='<td style="width:10%">'.($key + 1).'</td>';
				 $html.='<td style="width:15%">'.$sql[0]->name_of_the_candidate.'</td>';
				 $html.='<td style="width:20%">'.$value->interview_date.'</td>';
				 $html.='<td style="width:10%">'.$value->date.'</td>';
				 $html.='</tr>';
			 }
		 }
		 else
		 {
			 $html.='<tr>';
			 $html.='<td align="center" colspan="4">No Data Found</td>';
			 $html.='</tr>'; 
		 }
		 return $html;
		 
		 }
		 /** Search Data RE schedule_interview change date End **/

		 /** Search Data RE schedule_interview mail Send start **/
     public function changeinterviewdate($id,$date)
     {
		 $middle = strtotime($date);    
                 $newDate = date('Y-m-d H:i:s', $middle);
		 $data=interviewschedule::find($id);
		 $data->interview_date=$newDate;
		 $name = $data->name_of_the_candidate;
		 $data->save();	
		 $req = DB::table('hr_schedule_interview')->where('interview_id',$id)->get();
		 $des = $req[0]->job_description_name;
		 $can = $req[0]->name_of_the_candidate;
		 $query = DB::table('add_resume')->where('resume_id',$req[0]->name_of_the_candidate)->get();
		 $job_description_name=\DB::select("SELECT hr_job_description.*,m_job_title.job_title_name from hr_job_description
		 LEFT JOIN m_job_title on m_job_title.job_title_id=hr_job_description.job_title where description_id='$des'");
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
		 $data['interview_date'] = $req[0]->interview_date;
		 	$data['datetime'] = $newDate;
		 $data['email']=$data[0]->email_id;
		 $data['website_address']=$data[0]->website_address;
		 $l_error=array();
		 if($address!=0)
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
			 
			 array_push($l_error,"Please Check Company Address details");
		 }
		 $_POST['id'] = $id;
		 $data['l_error'] = $l_error;
		 $rest=\Mail::send('scheduleinterview.remail',$data, function($message)
			 {    
						 $can_id=$_POST['id'];
						 $email=\DB::select("SELECT add_resume.email FROM `hr_schedule_interview` LEFT JOIN add_resume on add_resume.resume_id=hr_schedule_interview.name_of_the_candidate WHERE hr_schedule_interview.interview_id='$can_id'");
						 $message->to($email[0]->email);
						 $message->subject('Interview ReSchedule');
			 });
		
// auditlog
		$this->auditlog($id,"Search Schedule Interview","Re-Schedule Interview Date",$_POST,"hr_schedule_interview");
		 return 0;

	 }
	 /** Search Data RE schedule_interview mail Send End **/
  
}
