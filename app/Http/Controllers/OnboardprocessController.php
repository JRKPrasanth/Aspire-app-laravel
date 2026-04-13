<?php

namespace App\Http\Controllers;

use App\Onboardprocess;
use App\Lettercontent;
use Illuminate\Http\Request;
use DB;
use Session,DateTime;
use App\Employeecreate;
use Yajra\DataTables\DataTables;

class OnboardprocessController extends Controller
{
    public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	 }
     /** On Board Process Page Load **/
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

       // (schedule_status=1 OR schedule_status=2)  AND select_candidate=1
        
        $result = DB::table('hr_schedule_interview')->orWhere('schedule_status',1)->orWhere('schedule_status',2)->where('select_candidate',1)->get();
        $this->data['onboard_list'] = $result;
		$dept=json_decode(session::get('dept_id'));
		$depart="";
		foreach($dept as $key=>$value){
			$depart.=$value.",";
		}
	    $dprtmnt=rtrim($depart,",");
        $this->data['department_id'] =$dprtmnt;
        return view('onboardprocess.table',$this->data);
        
    }

    /** Acceptance letter Update Start **/
    public function acceptanceletterupdate($result = null, $interview_id = null) 
    {
        $interviewid = $interview_id;
        $status = $result;
        
        $update_result =  DB::table('hr_schedule_interview')->where('interview_id',$interviewid)->update(['acceptance_status' => $status]);
      
        if ($update_result) 
            return 1;
        else
            return 2;
    }
    /** Acceptance letter Update End **/

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Onboardprocess  $onboardprocess
     * @return \Illuminate\Http\Response
     */
    public function destroy(Onboardprocess $onboardprocess)
    {
        //
    }
   
    /** Jqgrid On Board Load Data Grid Start **/
    public function onboardlistdata()
    {
      
	    $wh='';
        $comp=\Session::get('companyid');
        $wh .="and (hr_schedule_interview.schedule_status=1 OR hr_schedule_interview.schedule_status=2)  AND hr_schedule_interview.select_candidate=1";
 
	$SQL = "SELECT  
            add_resume.name_of_the_candidate,
            hr_job_description.description_name,
            hr_job_description.description_id,
            hr_job_description.interview_process,
            hr_schedule_interview.date,
            add_resume.resume_id,
            hr_schedule_interview.interview_date,
            m_interview_steps.interview_process,
            hr_schedule_interview.schedule_status,
            hr_schedule_interview.job_description_name,
            hr_schedule_interview.interview_id,
            hr_schedule_interview.remarks,
            hr_schedule_interview.interview_process,
            hr_schedule_interview.date_of_joining,
            hr_schedule_interview.salary_annum,
            hr_schedule_interview.offer_status,
            hr_schedule_interview.acceptance_status,
            hr_schedule_interview.approve_status,
            hr_schedule_interview.employee_status
        from hr_schedule_interview 
        left JOIN add_resume on add_resume.resume_id=hr_schedule_interview.name_of_the_candidate
        left JOIN hr_job_description on hr_job_description.description_id=hr_schedule_interview.job_description_name
        left JOIN m_interview_steps on m_interview_steps.interview_steps_id=hr_schedule_interview.interview_process  where 1=1  and hr_schedule_interview.company_id=$comp $wh ";
        
	$result = \DB::select($SQL);

    return DataTables::of($result)->make(true);

}
/** Jqgrid On Board Load Data Grid Start **/

/** Print Start **/
    public function getprint(Request $request,$id=null,$type=null)
    {
        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
        $onboard_list = Onboardprocess::findorfail($id);
        $company_id = Session::get('companyid');
        $comp = DB::table('m_company_t')->leftjoin('m_company_line_t','m_company_line_t.companyid','m_company_t.company_id')->leftjoin('m_location_t','m_location_t.location_id','m_company_line_t.locationid')->leftjoin('m_countries_t','m_location_t.country_id','m_countries_t.country_id')->leftjoin('m_cities_t','m_location_t.city_id','m_cities_t.city_id')->leftjoin('m_states_t','m_location_t.state_id','m_states_t.state_id')->where('m_company_t.company_id',$company_id)->get();
        
        $this->data['company_name'] = $comp[0]->company_name;
        $company_address='';
        /*foreach($comp as $key=>$value){
              $company_address.=$value->description." : ".$value->address.",".$value->location_name.",".$value->city_name."-".$value->pincode.",".$value->country_name;
              $posting_address.=$value->description.":".$value->location_name;
        }*/
         
        $company_address.= $comp[0]->address.",".$comp[0]->city_name."-".$comp[0]->pincode;
        $adrs_replace =  str_replace(',', '<br />', $company_address);
        $this->data['company_address'] = $adrs_replace;
        $name_id = $onboard_list->name_of_the_candidate;
        $user_details = DB::table('hr_schedule_interview')
                ->leftjoin('add_resume','add_resume.resume_id','=','hr_schedule_interview.name_of_the_candidate')
                ->leftjoin('m_location_t','m_location_t.location_id','=','hr_schedule_interview.location_id')
                ->leftjoin('hr_job_description','hr_job_description.description_id','=','hr_schedule_interview.job_description_name')
                ->leftjoin('m_job_title','m_job_title.job_title_id','=','hr_job_description.job_title')
                ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_schedule_interview.created_by')
                  ->leftjoin('m_position','m_position.position_id','=','hr_job_description.position_id')
                ->select('m_job_title.job_title_name','hr_employee_t.first_name','add_resume.name_of_the_candidate','hr_schedule_interview.name_of_the_candidate as candidate_id','hr_schedule_interview.date_of_joining','hr_schedule_interview.salary_annum','m_location_t.location_name','hr_schedule_interview.interview_id','add_resume.address','hr_job_description.position_id','hr_job_description.description_name','m_position.position','add_resume.location','add_resume.gender')
                ->where('hr_schedule_interview.interview_id',$id)->get();
        $prefix ="";      
        if($user_details[0]->gender=="male"){
            $prefix = "Mr";
        }else if($user_details[0]->gender=="female"){
             $prefix = "Ms";
        }else{
             $prefix = "Mrs";
        }
        $this->data['prefix'] = $prefix;
        $candidate_id = $user_details[0]->candidate_id;
        $salary_details = DB::table('hr_emp_offer_letter')->where('hr_emp_offer_letter.candidate_id',$candidate_id)->get();
        if(isset($salary_details[0]->position)){
        $position_details = DB::table('m_position')->select('position')->where('m_position.position_id',$salary_details[0]->position)->get();
        }else{
         $position_details = DB::table('m_position')->select('position')->where('m_position.position_id',$user_details[0]->position_id)->get();   
        }
        $rand_id = "000";
        $this->data['randid'] = $rand_id . $user_details[0]->interview_id;
        $this->data['interview_id'] = $user_details[0]->interview_id;
        $this->data['date_of_joining'] = $user_details[0]->date_of_joining;
        $cur_date = date("d-m-Y");
         $join=  new DateTime($cur_date);
         $date=$join->modify('+2 days');
        $this->data['at_date'] = (string)$date->format("d-m-Y");
        $this->data['name_of_the_candidate'] = $user_details[0]->name_of_the_candidate;
        $this->data['address'] = $user_details[0]->address;
        $this->data['description_name'] = $user_details[0]->job_title_name;
        $this->data['grade_name'] = $position_details[0]->position;
        $this->data['hr_name'] = $user_details[0]->first_name."-HR.";
        $position_id = $salary_details[0]->position;
        $this->data['location'] = ucfirst(strtolower($user_details[0]->location));
        $this->data['basic_pay'] = $salary_details[0]->basic_pay;
        $basic_pay  = $salary_details[0]->basic_pay;
        $this->data['hra'] = $salary_details[0]->hra;
        $this->data['gross_pay'] = round($salary_details[0]->gross_pay);
        $gross_pay = $salary_details[0]->gross_pay;
        $this->data['ctc_pay'] = $salary_details[0]->ctc_pay;
        $this->data['annual_allowance'] = $salary_details[0]->annual_allowance;
        $this->data['employee_type'] = $salary_details[0]->employee_type;
        $this->data['da'] = $salary_details[0]->da;
        $this->data['pf'] = $salary_details[0]->pf;
        $this->data['esi'] = $salary_details[0]->esi;  
        $this->data['offer_letter_no'] = $salary_details[0]->offer_letter_no;
        
        $department_details = DB::table('m_department_lines_t')->select('sub_department_name')->where('m_department_lines_t.department_line_id',$salary_details[0]->department)->get();
        $this->data['department_name'] = $department_details[0]->sub_department_name;
        
        $office_location = DB::table('m_location_t')->select('location_name')->where('m_location_t.location_id',$salary_details[0]->location)->get();

        if($office_location[0]->location_name == 'Kundrathur'){
            $this->data['office_location'] = "Head Office – Kunrathur, Chennai";
        }else if($office_location[0]->location_name == 'Abiramapuram'){
            $this->data['office_location'] = "Corporate Office – Abiramapuram, Chennai";
        }else{
            $this->data['office_location'] = "Head Office – Kunrathur, Chennai";
        }
        
        
        if(isset($onboard_list)){
          $this->data['onboard_list'] =$onboard_list;
        }else{
          $this->data['onboard_list'] ="";
        }
        $letter_type  =  ($type =='viewoffer') ? 1 : 2;
        $type_letter_contect= DB::table('a_lookuplines_t')->where('lookup_type',"LETTER_TYPE")->where('lookup_meaning',"Offer Letter")->get();
        if(count($type_letter_contect)>0){
        $letter_content = DB::table('m_letter_content')->where('employee_type',$this->data['employee_type'] )->where('company_id',\Session::get('companyid') )->where('letter_type',$type_letter_contect[0]->lookuplines_id)->where('active',"Yes" )->orderBy('id', 'desc')->get();
       if(count($letter_content)>0){
        $this->data['letter_content'] =$letter_content;
       }
       else
       {
        $this->data['letter_content'] ='';
       }
       }
       else{
        $this->data['letter_content'] ='';
       }
     	$this->data['print']="PRINT";
        $this->data['logo'] = Session::get('companylogo');
        
        return view('onboardprocess.offerletter_print',$this->data);
    }


    public function getctcprint(Request $request,$id=null,$type=null)
    {
        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
      
        $onboard_list = Onboardprocess::findorfail($id);
        $company_id = Session::get('companyid');
        $comp = DB::table('m_company_t')->leftjoin('m_company_line_t','m_company_line_t.companyid','m_company_t.company_id')->leftjoin('m_location_t','m_location_t.location_id','m_company_line_t.locationid')->leftjoin('m_countries_t','m_location_t.country_id','m_countries_t.country_id')->leftjoin('m_cities_t','m_location_t.city_id','m_cities_t.city_id')->leftjoin('m_states_t','m_location_t.state_id','m_states_t.state_id')->where('m_company_t.company_id',$company_id)->get();
        
        $this->data['company_name'] = $comp[0]->company_name;
        $company_address='';

        $company_address.= $comp[0]->address.",".$comp[0]->city_name."-".$comp[0]->pincode;
        $adrs_replace =  str_replace(',', '<br />', $company_address);
        $this->data['company_address'] = $adrs_replace;
        $name_id = $onboard_list->name_of_the_candidate;
        $user_details = DB::table('hr_schedule_interview')
                ->leftjoin('add_resume','add_resume.resume_id','=','hr_schedule_interview.name_of_the_candidate')
                ->leftjoin('m_location_t','m_location_t.location_id','=','hr_schedule_interview.location_id')
                ->leftjoin('hr_job_description','hr_job_description.description_id','=','hr_schedule_interview.job_description_name')
                ->leftjoin('m_job_title','m_job_title.job_title_id','=','hr_job_description.job_title')
                ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_schedule_interview.created_by')
                  ->leftjoin('m_position','m_position.position_id','=','hr_job_description.position_id')
                ->select('m_job_title.job_title_name','hr_employee_t.first_name','add_resume.name_of_the_candidate','hr_schedule_interview.name_of_the_candidate as candidate_id','hr_schedule_interview.date_of_joining','hr_schedule_interview.salary_annum','m_location_t.location_name','hr_schedule_interview.interview_id','add_resume.address','hr_job_description.position_id','hr_job_description.description_name','m_position.position','add_resume.location','add_resume.gender','add_resume.marital_status','add_resume.date_of_birth')
                ->where('hr_schedule_interview.interview_id',$id)->get();
        $prefix ="";      
        if($user_details[0]->gender=="male"){
            $prefix = "Mr";
        }else if($user_details[0]->gender=="female"){
             $prefix = "Ms";
        }else{
             $prefix = "Mrs";
        }
        $this->data['prefix'] = $prefix;
        $candidate_id = $user_details[0]->candidate_id;
        $salary_details = DB::table('hr_emp_offer_letter')->where('hr_emp_offer_letter.candidate_id',$candidate_id)->get();
        if($salary_details[0]->class_of_hq > 0){
         $class_of_hq = DB::table('a_lookuplines_t')->where('lookup_type',"CLASS_OF_HQ")->where('lookuplines_id',$salary_details[0]->class_of_hq)->get();   
        }else{
          $class_of_hq = '';  
        }
        $loc = $user_details[0]->location;
        if(isset($salary_details[0]->position)){
        $position_details = DB::table('m_position')->select('position')->where('m_position.position_id',$salary_details[0]->position)->get();
        $vehicle_allow = DB::table('hr_emp_vehicleallowance_t')->select('hq_value')->where('hr_emp_vehicleallowance_t.position_id',$salary_details[0]->position)->get();
        $mobile_allow = DB::table('hr_emp_mobileallowance_t')->select('hq_value')->where('hr_emp_mobileallowance_t.position_id',$salary_details[0]->position)->get();
        $railbus_allow = \DB::select("SELECT *  FROM `hr_emp_railbuspassallowance_t` WHERE `position_id` = ".$salary_details[0]->position." AND `hq` LIKE '%".$loc."%'");
        if($salary_details[0]->employee_type == '231'){
        if($class_of_hq[0]->lookup_code == "A"){
        $hqdaily_allow_a = \DB::select("SELECT *  FROM `hr_emp_hqdailyallowance_t` WHERE `position_id` = ".$salary_details[0]->position." AND `city_class`='".$class_of_hq[0]->lookup_code."'");    
        }else{
        $hqdaily_allow_b = \DB::select("SELECT *  FROM `hr_emp_hqdailyallowance_t` WHERE `position_id` = ".$salary_details[0]->position." AND `city_class`='".$class_of_hq[0]->lookup_code."'");
        }
        }
        $hqdaily_allow = \DB::select("SELECT *  FROM `hr_emp_hqdailyallowance_t` WHERE `position_id` = ".$salary_details[0]->position);    

        $hill_allow = DB::table('hr_emp_hillallowance_t')->select('hill_allowance')->where('hr_emp_hillallowance_t.position_id',$salary_details[0]->position)->get();
        $jw_allow = DB::table('hr_emp_jointworkallowance_t')->select('jw_allowance','jw_allowance_applicable')->where('hr_emp_jointworkallowance_t.position_id',$salary_details[0]->position)->get();
        $travel_allow = DB::table('hr_emp_travelallowance_t')->select('travel_allowance')->where('hr_emp_travelallowance_t.position_id',$salary_details[0]->position)->get();
        $medical_allow = DB::table('hr_emp_medicalallowance_t')->select('hq_value')->where('hr_emp_medicalallowance_t.position_id',$salary_details[0]->position)->get();
        $furnishing_allow = DB::table('hr_emp_furnishingallowance_t')->select('hq_value')->where('hr_emp_furnishingallowance_t.position_id',$salary_details[0]->position)->get();
        $leavetravel_allow = DB::table('hr_emp_ltallowance_t')->select('hq_value')->where('hr_emp_ltallowance_t.position_id',$salary_details[0]->position)->get();
        }else{
         $position_details = DB::table('m_position')->select('position')->where('m_position.position_id',$user_details[0]->position_id)->get(); 
         $vehicle_allow = DB::table('hr_emp_vehicleallowance_t')->select('hq_value')->where('hr_emp_vehicleallowance_t.position_id',$user_details[0]->position_id)->get();
         $mobile_allow = DB::table('hr_emp_mobileallowance_t')->select('hq_value')->where('hr_emp_mobileallowance_t.position_id',$user_details[0]->position_id)->get();
         $railbus_allow = \DB::select("SELECT *  FROM `hr_emp_railbuspassallowance_t` WHERE `position_id` = ".$user_details[0]->position_id." AND `hq` LIKE '%".$loc."%'");
         
         if($class_of_hq[0]->lookup_code == "A"){
         $hqdaily_allow_a = \DB::select("SELECT *  FROM `hr_emp_hqdailyallowance_t` WHERE `position_id` = ".$user_details[0]->position_id." AND `city_class`='".$class_of_hq[0]->lookup_code."'");
         }else{
         $hqdaily_allow_b = \DB::select("SELECT *  FROM `hr_emp_hqdailyallowance_t` WHERE `position_id` = ".$user_details[0]->position_id." AND `city_class`='".$class_of_hq[0]->lookup_code."'");
         }
         
         $hqdaily_allow = \DB::select("SELECT *  FROM `hr_emp_hqdailyallowance_t` WHERE `position_id` = ".$user_details[0]->position_id);
         
         $hill_allow = DB::table('hr_emp_hillallowance_t')->select('hill_allowance')->where('hr_emp_hillallowance_t.position_id',$user_details[0]->position_id)->get();
         $jw_allow = DB::table('hr_emp_jointworkallowance_t')->select('jw_allowance','jw_allowance_applicable')->where('hr_emp_jointworkallowance_t.position_id',$user_details[0]->position_id)->get();
         $travel_allow = DB::table('hr_emp_travelallowance_t')->select('travel_allowance')->where('hr_emp_travelallowance_t.position_id',$user_details[0]->position_id)->get();
         $medical_allow = DB::table('hr_emp_medicalallowance_t')->select('hq_value')->where('hr_emp_medicalallowance_t.position_id',$user_details[0]->position_id)->get();
         $furnishing_allow = DB::table('hr_emp_furnishingallowance_t')->select('hq_value')->where('hr_emp_furnishingallowance_t.position_id',$user_details[0]->position_id)->get();
         $leavetravel_allow = DB::table('hr_emp_ltallowance_t')->select('hq_value')->where('hr_emp_ltallowance_t.position_id',$user_details[0]->position_id)->get();
        }
        
        $date_of_birth = $user_details[0]->date_of_birth;
        $dob = new DateTime($date_of_birth);
        $today = new DateTime();
        $age = $today->diff($dob)->y;
        $age_cond = 'greaterthan35';

        if($age > 35){
        $pa_insurance = DB::table('hr_emp_insurance_t')->select('pa_premium1')->where('hr_emp_insurance_t.insurance_condition',$age_cond)->get();    
        $mi_insurance = DB::table('hr_emp_insurance_t')->select('mi_premium','pa_premium2',DB::raw('(mi_premium + pa_premium2) AS total_mi_premium'))->where('hr_emp_insurance_t.insurance_condition',$age_cond)->get();    
        }else{
        $pa_insurance = DB::table('hr_emp_insurance_t')->select('pa_premium1')->where('hr_emp_insurance_t.insurance_condition',$user_details[0]->marital_status)->get();
        $mi_insurance = DB::table('hr_emp_insurance_t')->select('mi_premium','pa_premium2',DB::raw('(mi_premium + pa_premium2) AS total_mi_premium'))->where('hr_emp_insurance_t.insurance_condition',$user_details[0]->marital_status)->get();    
        }
        $rand_id = "000";
        $this->data['randid'] = $rand_id . $user_details[0]->interview_id;
        $this->data['interview_id'] = $user_details[0]->interview_id;
        $this->data['date_of_joining'] = $user_details[0]->date_of_joining;
        $cur_date = date("d-m-Y");
         $join=  new DateTime($cur_date);
         $date=$join->modify('+3 days');
        $this->data['at_date'] = (string)$date->format("d-m-Y");
        $this->data['name_of_the_candidate'] = $user_details[0]->name_of_the_candidate;
        $this->data['address'] = $user_details[0]->address;
        $this->data['description_name'] = $user_details[0]->job_title_name;
        $this->data['grade_name'] = $position_details[0]->position;
        $this->data['hr_name'] = $user_details[0]->first_name."-HR.";
        $position_id = $salary_details[0]->position;
        $this->data['location'] = ucfirst(strtolower($user_details[0]->location));
        $this->data['basic_pay'] = $salary_details[0]->basic_pay;
        $basic_pay  = $salary_details[0]->basic_pay;
        $this->data['hra'] = round($salary_details[0]->hra);
        $this->data['gross_pay'] = round($salary_details[0]->gross_pay);
        $gross_pay = $salary_details[0]->gross_pay;
        $this->data['ctc_pay'] = $salary_details[0]->ctc_pay;
        $this->data['annual_allowance'] = $salary_details[0]->annual_allowance;
        $this->data['gratuity'] = $salary_details[0]->gratuity;
        $this->data['employee_type'] = $salary_details[0]->employee_type;
        $this->data['da'] = round($salary_details[0]->da);
        $this->data['pf'] = $salary_details[0]->pf;
        $this->data['esi'] = $salary_details[0]->esi; 
        $this->data['pf_amount'] = $salary_details[0]->pf_amount;
        $this->data['esi_amount'] = $salary_details[0]->esi_amount; 
        $this->data['pt_amount'] = $salary_details[0]->pt_amount; 
        
        $this->data['gross_pay_month'] = round($salary_details[0]->basic_pay + $salary_details[0]->hra + $salary_details[0]->da); 
        $this->data['net_pay'] = round($salary_details[0]->gross_pay) - $salary_details[0]->pf_amount - $salary_details[0]->esi_amount; 
        if($salary_details[0]->employee_type == '231'){
        $this->data['class_of_hq'] = $class_of_hq[0]->lookup_code;
        $this->data['vehicle_allowance'] = $vehicle_allow[0]->hq_value; 
        $this->data['mobileint_allowance'] = $mobile_allow[0]->hq_value; 
        $this->data['medical_allowance'] = $medical_allow[0]->hq_value; 
        $this->data['furnishing_allowance'] = $furnishing_allow[0]->hq_value; 
        $this->data['leavetravel_allowance'] = $leavetravel_allow[0]->hq_value;
        $this->data['hill_allowance'] = $hill_allow[0]->hill_allowance;
        $this->data['jw_allowance'] = $jw_allow[0]->jw_allowance;
        $this->data['jw_allowance_applicable'] = $jw_allow[0]->jw_allowance_applicable;
        $this->data['travel_allowance'] = $travel_allow[0]->travel_allowance;
        $this->data['pa_premium'] = $pa_insurance[0]->pa_premium1;
        $this->data['mi_premium'] = $mi_insurance[0]->total_mi_premium;
        if(isset($railbus_allow[0]->rail_allowance)){
        $this->data['railbus_allowance'] = $railbus_allow[0]->rail_allowance;
        }else{
        $this->data['railbus_allowance'] = 0;    
        }
        if($salary_details[0]->other_allowance_expense !=''){
         $this->data['other_allow_exp'] = $salary_details[0]->other_allowance_expense;    
        }else{
         $this->data['other_allow_exp'] = '0'; 
        }
        $this->data['tot_allow_month'] = $vehicle_allow[0]->hq_value + $mobile_allow[0]->hq_value + $this->data['railbus_allowance'] + $this->data['other_allow_exp'];
        $this->data['pf_amount_annual'] = round($salary_details[0]->pf_amount * 12);
        $this->data['esi_amount_annual'] = round($salary_details[0]->esi_amount * 12);
        $this->data['ctc_per_annum'] = round($this->data['ctc_pay']) + round($this->data['pf_amount_annual']) + round($this->data['esi_amount_annual']) + round($this->data['mi_premium']) + round($this->data['pa_premium']) + round($this->data['gratuity']);
        $this->data['ctc_per_month'] = round($this->data['ctc_per_annum'] / 12);

        if(!empty($hqdaily_allow_a)){
            $this->data['hqdaily_allow_hq_a'] = $hqdaily_allow_a[0]->hq;
             
        }else{
            $this->data['hqdaily_allow_hq_a'] ='0';
            
            
        } 
        if(!empty($hqdaily_allow_b)){
            $this->data['hqdaily_allow_hq_b'] = $hqdaily_allow_b[0]->hq;
            
            
        }else{
            
            $this->data['hqdaily_allow_hq_b'] ='0';
            
        }

        if(!empty($hqdaily_allow)){
            $this->data['hqdaily_allow_exhq_a'] = $hqdaily_allow[0]->ex_hq; 
            $this->data['hqdaily_allow_os_a'] = $hqdaily_allow[0]->os;
            $this->data['hqdaily_allow_exhq_b'] = $hqdaily_allow[1]->ex_hq; 
            $this->data['hqdaily_allow_os_b'] = $hqdaily_allow[1]->os;
        }else{
            $this->data['hqdaily_allow_exhq_a'] = '0';
            $this->data['hqdaily_allow_os_a'] ='0';
            $this->data['hqdaily_allow_exhq_b'] = '0';
            $this->data['hqdaily_allow_os_b'] ='0';
        }
        
 if (!empty($salary_details) && !empty($salary_details[0]->allowance)) {

    // Decode JSON to array
    $allowanceData = json_decode($salary_details[0]->allowance, true);
    if (!is_array($allowanceData)) {
        $allowanceData = [];
    }

    // Convert JSON array of objects into key-value pairs
    $allowanceValues = [];
    foreach ($allowanceData as $item) {
        $allowanceValues[key($item)] = current($item);
    }

    // Fetch all allowances for this employee type
    $allAllowances = DB::table('m_allowance_tbl')
        ->where('employee_type', $salary_details[0]->employee_type)
        ->pluck('allowance_name', 'allowance_id'); // [id => name]

    // Loop through all allowances
    foreach ($allAllowances as $id => $name) {
        // Sanitize allowance name for use as key: lowercase, replace spaces/dashes/slashes with underscores
        $varName = strtolower($name);
        $varName = preg_replace('/[^a-z0-9]+/', '_', $varName); // only letters, numbers, underscore
        $varName = trim($varName, '_'); // remove leading/trailing underscores
        
        // Assign value from JSON or 0
        $this->data[$varName] = isset($allowanceValues[$id]) && $allowanceValues[$id] !== null
            ? $allowanceValues[$id]
            : 0;
    }
}
        }else{
        
        $this->data['ctc_per_annum'] = round($this->data['ctc_pay']) + round($this->data['gratuity']);
        $this->data['ctc_per_month'] = round($this->data['ctc_per_annum'] / 12);

}

         
        if(isset($onboard_list)){
          $this->data['onboard_list'] =$onboard_list;
        }else{
          $this->data['onboard_list'] ="";
        }
        $letter_type  =  ($type =='viewoffer') ? 1 : 2;
        if($this->data['employee_type']=='231'){
        $type_letter_contect= DB::table('a_lookuplines_t')->where('lookup_type',"LETTER_TYPE")->where('lookup_meaning',"Marketing Cost Sheet")->get();    
        }else{
        $type_letter_contect= DB::table('a_lookuplines_t')->where('lookup_type',operator: "LETTER_TYPE")->where('lookup_meaning',"HOCO Cost Sheet")->get();
        }
        if(count($type_letter_contect)>0){
        $letter_content = DB::table('m_letter_content')->where('employee_type',$this->data['employee_type'] )->where('company_id',\Session::get('companyid') )->where('letter_type',$type_letter_contect[0]->lookuplines_id)->where('active',"Yes" )->orderBy('id', 'desc')->get();
       if(count($letter_content)>0){
        $this->data['letter_content'] =$letter_content;
       }
       else
       {
        $this->data['letter_content'] ='';
       }
       }
       else{
        $this->data['letter_content'] ='';
       }
     	$this->data['print']="PRINT";
        $this->data['logo'] = Session::get('companylogo');
       //dd($this->data['letter_content']);
        if($this->data['employee_type'] == '231'){
        return view('onboardprocess.marketing_costsheet_print',$this->data);    
        }else{
        return view('onboardprocess.costsheet_print',$this->data);
        }
    
    
    }


/** Appoinment Print Start **/
	public function getappoinmentprint(Request $request,$id=null,$type=null)
    {

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
        $onboard_list = Onboardprocess::findorfail($id);
        $company_id = Session::get('companyid');
        $comp = DB::table('m_company_t')->where('company_id',$company_id)->get();
        //dd($comp);
        $this->data['company_name'] = $comp[0]->company_name;
        $this->data['logo'] = Session::get('companylogo');
        $this->data['company_address'] = "Chennai";
        $name_id = $onboard_list->name_of_the_candidate;
        $user_details = DB::table('hr_schedule_interview')
                ->leftjoin('add_resume','add_resume.resume_id','=','hr_schedule_interview.name_of_the_candidate')
                ->leftjoin('m_location_t','m_location_t.location_id','=','hr_schedule_interview.location_id')
                 ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_schedule_interview.created_by')
                ->leftjoin('hr_job_description','hr_job_description.description_id','=','hr_schedule_interview.job_description_name')
                ->leftjoin('m_job_title','m_job_title.job_title_id','=','hr_job_description.job_title')
                ->select('m_job_title.job_title_name','add_resume.name_of_the_candidate','hr_schedule_interview.name_of_the_candidate as candidate_id','hr_schedule_interview.date_of_joining','hr_schedule_interview.salary_annum','m_location_t.location_name','hr_schedule_interview.interview_id','add_resume.address','hr_job_description.description_name','hr_employee_t.prefix','add_resume.location','add_resume.city','add_resume.gender')
                ->where('hr_schedule_interview.interview_id',$id)->get();
        $prefix ="";      
        if($user_details[0]->gender=="male"){
            $prefix = "Mr";
        }else if($user_details[0]->gender=="female"){
             $prefix = "Ms";
        }else{
             $prefix = "Mrs";
        }
        $this->data['prefix'] = $prefix;
        $rand_id = "000";
        $this->data['randid'] = $rand_id . $user_details[0]->interview_id;
        $candidate_id = $user_details[0]->candidate_id;
        $salary_details = DB::table('hr_emp_offer_letter')->where('hr_emp_offer_letter.candidate_id',$candidate_id)->get();
        $this->data['interview_id'] = $user_details[0]->interview_id;
        $this->data['Date_of_joining'] = $user_details[0]->date_of_joining;
        $this->data['employee_name'] = $user_details[0]->name_of_the_candidate;
        $this->data['address'] = $user_details[0]->address;
        $this->data['description_name'] = $user_details[0]->description_name;
        $this->data['jobdesc_name'] = $user_details[0]->job_title_name;
        $position_id = $salary_details[0]->position;
        $position_level = DB::table('m_position')->select('position')->where('position_id', $position_id)->get();
        $this->data['grade_level'] = $position_level[0]->position;
        $this->data['location'] = $user_details[0]->location_name;
        $this->data['loc_name'] = ucfirst(strtolower($user_details[0]->location));
        $this->data['state'] = $user_details[0]->city;
        $this->data['basic_pay'] = $salary_details[0]->basic_pay;
        $basic_pay= $salary_details[0]->basic_pay;
        $this->data['hra'] = $salary_details[0]->hra;
        $this->data['gross_pay'] = $salary_details[0]->gross_pay;
        $gross_pay = $salary_details[0]->gross_pay;
        $this->data['ctc_pay'] = $salary_details[0]->ctc_pay;
        $this->data['annual_allowance'] = $salary_details[0]->annual_allowance;
        $this->data['employee_type'] = $salary_details[0]->employee_type;
        $department_details = DB::table('m_department_lines_t')->select('sub_department_name')->where('m_department_lines_t.department_line_id',$salary_details[0]->department)->get();
        $this->data['department_name'] = $department_details[0]->sub_department_name;
        $this->data['da'] = $salary_details[0]->da;
        $this->data['pf'] = $salary_details[0]->pf;
        $this->data['esi'] = $salary_details[0]->esi;     
        $letter_type  =  ($type =='viewoffer') ? 1 : 2;
        $this->data['onboard_list'] =$onboard_list;
        if( $this->data['employee_type'] == '231'){
        $type_letter_contect= DB::table('a_lookuplines_t')->where('lookup_type',"LETTER_TYPE")->where('lookup_meaning',"Appointment Letter")->get();
        }else{
        $type_letter_contect= DB::table('a_lookuplines_t')->where('lookup_type',"LETTER_TYPE")->where('lookup_meaning',"HOCO Appointment Letter")->get();    
        }
        if(count($type_letter_contect)>0){
        $letter_content = DB::table('m_letter_content')->where('employee_type',$this->data['employee_type'] )->where('company_id',\Session::get('companyid') )->where('letter_type',$type_letter_contect[0]->lookuplines_id)->where('active',"Yes" )->orderBy('id', 'desc')->get();

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
        //dd($letter_content);
         $director_details = DB::table('hr_employee_t')->leftjoin('m_job_title','m_job_title.job_title_id','hr_employee_t.job_title')->where('m_job_title.job_title_name','Director')->get();
       if(count($director_details)>0){
           $this->data['director'] =$director_details[0]->job_title_name;
       }else{
          $this->data['director'] =''; 
       }
         return view('onboardprocess.appoinmentletter_print',$this->data);
    }
    /** Appoinment Print Start **/

    /** Offer Letter Print Start **/
    public function getoffer(Request $request)
    {
        $id = $_GET['interview_id'];
        $result_details = DB::table("hr_schedule_interview")
                ->leftjoin('hr_job_description', 'hr_job_description.description_id', '=', 'hr_schedule_interview.job_description_name')
                ->leftjoin('add_resume', 'add_resume.resume_id', '=', 'hr_schedule_interview.name_of_the_candidate')
                ->select('hr_schedule_interview.interview_id','hr_schedule_interview.name_of_the_candidate as candidate_id','hr_schedule_interview.date_of_joining','add_resume.name_of_the_candidate','add_resume.address','hr_job_description.description_id')
                ->where('hr_schedule_interview.interview_id',$id)->get();  
           $result_details_data = DB::table("hr_schedule_interview")
                ->leftjoin('interview_process_details', 'interview_process_details.interview_id', '=', 'hr_schedule_interview.interview_id')
                ->select('interview_process_details.job_title')
                ->where('hr_schedule_interview.interview_id',$id)->where('interview_process_details.select_candidate',1)->get();  
        
        $data['interview_id'] = $result_details[0]->interview_id;
        $data['name_of_the_candidate'] = $result_details[0]->name_of_the_candidate;
        $data['candidate_id'] = $result_details[0]->candidate_id;
        $data['address'] = $result_details[0]->address;
        $data['job_title'] = $result_details_data[0]->job_title;
		  $format ="d-m-Y";
			$middle = strtotime($result_details[0]->date_of_joining); 
        $data['date_of_joining'] =  date($format, $middle);
         $esi=DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.employeer_contribute','m_emp_esi.company_contribute','m_emp_esi.limit','m_emp_esi.limitto')->where('lookup_code','ESI')->get();
		if(count($esi)>0){
                    foreach($esi as $k=>$v){
                          $data['esi'][$k] = $v->employeer_contribute;
                          $data['esi_c'][$k] = $v->company_contribute;
                          $data['esi_from'][$k] = $v->limit;
                          $data['esi_to'][$k] = $v->limitto;
                    }
		}
		else{
			 $data['esi'] = 0;
			 $data['esi_c'] = 0;
			 $data['esi_from'] = 0;
			 $data['esi_to'] = 0;
		}
		$pf=DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.employeer_contribute','m_emp_esi.company_contribute','m_emp_esi.company_contribute1','m_emp_esi.limit','m_emp_esi.limitto')->where('lookup_code','PF')->get();
        	if(count($pf)>0){
                     foreach($pf as $k=>$v){
			 $data['pf'][$k] = $v->employeer_contribute;
                         $pf_c=$v->company_contribute+$v->company_contribute1;
			 $data['pf_c'][$k] =$pf_c;
			 $data['pf_from'][$k] = $v->limit;
			 $data['pf_to'][$k] = $v->limitto;
                     }
		}
		else{
			 $data['pf'] = 0;
			 $data['pf_c'] = 0;
			 $data['pf_from'] = 0;
			 $data['pf_to'] = 0;
		}
                 $location = json_decode(Session::get('location'));
                 $professional_taxs = DB::table('m_location_t')->leftJoin('hr_professional_tax_hdr', 'hr_professional_tax_hdr.ptax_state_id', '=', 'm_location_t.state_id')->leftjoin('hr_professional_tax_lines','hr_professional_tax_lines.ptax_id','=','hr_professional_tax_hdr.ptax_id')->select('hr_professional_tax_hdr.*','hr_professional_tax_lines.*')->where('m_location_t.location_id',$location)->get();
                 if(count($professional_taxs)>0){
                     foreach($professional_taxs as $key=>$value){
			 $data['pt_from'][$key] = $value->from_value;
			 $data['pt_to'][$key]= $value->to_value;
			 $data['deduct'][$key] = $value->deduction_amount;
                     }
		}
		else{
			 $data['pt_from'] = 0;
			 $data['pt_to'] = 0;
			 $data['deduct'] = 0;
		} 
        return $data;
        
    }
    /** Offer Letter Print End **/

    /** Payproposal Save Start **/
    public function savepayproposal(Request $request)
    {
	
        $data['interview_id'] = $request->input('interview_id1');
        $data['name_of_the_candidate'] = $request->input('name_of_the_candidate');
        $data['candidate_id'] = $request->input('candidate_id1');
        $data['address'] = $request->input('address');
        $data['job_title'] = $request->input('job_title');
        $data['position'] = $request->input('position');
        $data['location'] = $request->input('location');
        $data['date_of_joining'] = date("Y-m-d", strtotime($request->input('date_of_joining'))); ;
        $data['basic_pay'] = $request->input('basic');
        $data['annual_allowance'] = $request->input('annual_allowance');
        $data['mail_send'] = $request->input('mail_send');
	    $allow_id=$request->input('allowance_id');
        $allow=$request->input('allowance');
        foreach($allow_id as $k=>$v){
            $allow_id[$k]=array();
            $allow_id[$k][$v]=$allow[$k];
        }
        $data['allowance'] = json_encode($allow_id);
        $data['gratuity'] = $request->input('gratuity');
        $data['hra'] = $request->input('hra');
        $data['da'] = $request->input('da');
        $data['pf'] = $request->input('pf');
        $data['esi'] = $request->input('esi');
        $data['gross_pay'] = $request->input('gross_pay');
        $data['net_pay'] = $request->input('net_pay');
        $data['ctc_pay'] = $request->input('ctc_pay');
        $data['department'] = $request->input('department_id');
        $data['employee_type'] = $request->input('employee_type');
        $data['group_type'] = $request->input('group_type');
        if($request->input('class_of_hq') != ''){
        $data['class_of_hq'] = $request->input('class_of_hq');
        }else{
        $data['class_of_hq'] = '0';    
        }
        $data['offer_letter_no'] = $request->input('offer_letter_no');
        $data['esi_amount'] = $request->input('esi_amount');
        $data['pf_amount'] = $request->input('pf_amount');
        $data['pt_amount'] = $request->input('pt_amount');
        $data['pt'] = $request->input('pt');

        $emp_letter = DB::table('hr_emp_offer_letter')->insert(array('name_of_the_candidate'=>$data['name_of_the_candidate'],'address'=>$data['address'],'job_title'=>$data['job_title'],'position'=>$data['position'],'location'=>$data['location'],'date_of_joining'=>$data['date_of_joining'],'basic_pay'=>$data['basic_pay'],'gratuity'=>$data['gratuity'],'annual_allowance'=>$data['annual_allowance'],'allowance'=>$data['allowance'],'hra'=>$data['hra'],'da'=>$data['da'],'pf'=>$data['pf'],'esi'=>$data['esi'],'pt'=>$data['pt'],'gross_pay'=>$data['gross_pay'],'net_pay'=>$data['net_pay'],'ctc_pay'=>$data['ctc_pay'],'department'=>$data['department'],'employee_type'=>$data['employee_type'],'group_type'=>$data['group_type'],'class_of_hq'=>$data['class_of_hq'],'offer_letter_no'=>$data['offer_letter_no'],'esi_amount'=>$data['esi_amount'],'pf_amount'=>$data['pf_amount'],'pt_amount'=>$data['pt_amount'],'mail_send'=>$data['mail_send'],'candidate_id'=>$data['candidate_id']));
        $emp_user   = DB::table('hr_schedule_interview')->where('interview_id',$data['interview_id'])->update(array('date_of_joining' => $data['date_of_joining'],'salary_annum'=>$data['ctc_pay'],'location_id'=>$data['location'],'offer_status'=>1));
        
		if( $data['mail_send']==1){
			  $this->data['print']="PRINTS";
			  $onboard_list = Onboardprocess::findorfail($data['interview_id']);
			  $this->data['onboard_list'] =$onboard_list;
        $company_id = Session::get('companyid');
        $comp = DB::table('m_company_t')->where('company_id',$company_id)->get();
        $this->data['company_name'] = $comp[0]->company_name;
        $this->data['company_address'] = "Chennai";
			$name_id = $onboard_list->name_of_the_candidate;
           $user_details = DB::table('hr_schedule_interview')
                ->leftjoin('add_resume','add_resume.resume_id','=','hr_schedule_interview.name_of_the_candidate')
                ->leftjoin('m_location_t','m_location_t.location_id','=','hr_schedule_interview.location_id')
                ->leftjoin('hr_job_description','hr_job_description.description_id','=','hr_schedule_interview.job_description_name')
                ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_schedule_interview.created_by')
                ->select('hr_employee_t.first_name','add_resume.name_of_the_candidate','hr_schedule_interview.name_of_the_candidate as candidate_id','hr_schedule_interview.date_of_joining','hr_schedule_interview.salary_annum','m_location_t.location_name','hr_schedule_interview.interview_id','add_resume.address','hr_job_description.description_name')
                ->where('hr_schedule_interview.interview_id',$data['interview_id'])->get();
        	
        	  $this->data['hr_name'] = $user_details[0]->first_name."-HR.";
        
        $this->data['employee_id']= $employee_id = $request->input('candidate_id');
        $salary_details = DB::table('hr_emp_offer_letter')->where('hr_emp_offer_letter.candidate_id',$data['candidate_id'])->get();
        $this->data['logo'] = Session::get('companylogo');       
        $this->data['interview_id'] = $user_details[0]->interview_id;
        $this->data['candidate_id'] = $user_details[0]->candidate_id;
        $this->data['date_of_joining'] = $user_details[0]->date_of_joining;
        $this->data['name_of_the_candidate'] = $user_details[0]->name_of_the_candidate;
        $this->data['address'] = $user_details[0]->address;
        $this->data['description_name'] = $user_details[0]->description_name;
        $position_id = $salary_details[0]->position;
        $this->data['location'] = $user_details[0]->location_name;
        $this->data['basic_pay'] = $salary_details[0]->basic_pay;
        $basic_pay= $salary_details[0]->basic_pay;
        $this->data['hra'] = $salary_details[0]->hra;
        $this->data['gross_pay'] = $salary_details[0]->gross_pay;
        $gross_pay = $salary_details[0]->gross_pay;
        $this->data['ctc_pay'] = $salary_details[0]->ctc_pay;
        $this->data['annual_allowance'] = $salary_details[0]->annual_allowance;
        $this->data['employee_type'] = $salary_details[0]->employee_type;   
        $this->data['da'] = $salary_details[0]->da;
        $this->data['pf'] = $salary_details[0]->pf;
        $this->data['esi'] = $salary_details[0]->esi;  
        $letter_content = DB::table('m_letter_content')->where('employee_type',$data['employee_type'] )->orderBy('id', 'asc')->get();
	    $this->data['letter_content'] =$letter_content;
		  $join=  new DateTime($user_details[0]->date_of_joining);
         $date=$join->modify('+30 days');
        $this->data['at_date'] = (string)$date->format("Y-m-d");
		$candidate_details = DB::table('add_resume')
                ->select('add_resume.email')->where('add_resume.resume_id',$data['candidate_id'])->get();
        $this->data['email'] = $candidate_details[0]->email; 
	    $_POST['email'] = $candidate_details[0]->email; 
    \Mail::send('onboardprocess.offerletter_print',$this->data, function($message)
        {

	    $msg="Thanks and Regards, Hr Department";
	    $message->setBody($msg);
		$message->subject("Offer Letter");	
        $message->to($_POST['email']);
		
        $message->attach('Uploads/offetletter/C_'.stripslashes($this->data['candidate_id']).'.pdf');
        });
		}
        return 1;
    }

    
    /** Convert to Employee  Start **/
    public function converttoemployee($interview_id = null,$employee_number=null,$candidate_id=null)
    {
        $candidate_details = DB::table('hr_schedule_interview')
                ->leftjoin('hr_job_description','hr_job_description.description_id','=','hr_schedule_interview.job_description_name')
                ->leftjoin('add_resume','add_resume.resume_id','=','hr_schedule_interview.name_of_the_candidate')
                ->select('hr_schedule_interview.interview_id','add_resume.email',
                        'add_resume.resume_id','add_resume.mobile_no',
                        'hr_schedule_interview.employee_status','add_resume.name_of_the_candidate as candidate_name',
                        'hr_schedule_interview.employee_status','hr_job_description.description_name',
                        'hr_job_description.department','hr_job_description.job_title',
                        'hr_schedule_interview.interview_date',
                        'hr_schedule_interview.date_of_joining',
                        'add_resume.current_company',
                        'add_resume.current_position',
                        'add_resume.current_salary',
                        'add_resume.years_of_experience',
                        'add_resume.exp_level','add_resume.marital_status',
                        'add_resume.gender')->where('hr_schedule_interview.interview_id',$interview_id)->get();
            $company_id=Session::get('companyid');
            $department=Session::get('dept_id');
            $company_details = DB::table('m_company_t')->where('company_id',$company_id)->get();
            $company_code = $company_details[0]->company_code;
            $employee_count =  Employeecreate::orderBy('employee_count', 'DESC')->where('company_id',$company_id)->get();
            if(count($employee_count)>0)
                $count = $employee_count[0]->employee_count+1;
            else
                $count = 1; 

            $insert['employee_number'] = $employee_number; 
            $insert['employee_count'] = $count;
            $insert['first_name'] = $candidate_details[0]->candidate_name;
            $insert['email'] = $candidate_details[0]->email;
            $insert['work_telephone_number'] = $candidate_details[0]->mobile_no;
            $insert['years_of_experience'] = $candidate_details[0]->years_of_experience;
            $insert['date_of_joining'] = $candidate_details[0]->date_of_joining;
            $insert['department'] = $department;
            $insert['job_title'] = $candidate_details[0]->job_title;
            $insert['position']= $department;
            $insert['employment_status']= $candidate_details[0]->employee_status;
            $insert['company_id']=$company_id;
            $interview_id = $candidate_details[0]->interview_id;
            $insert_data['first_name'] = $candidate_details[0]->resume_id;
            $insert_data['email'] = $candidate_details[0]->email;
            $insert_data['work_telephone_number'] = $candidate_details[0]->mobile_no;
            $insert_data['company_id']= \Session::get('companyid');
            $insert_data['organization_id']=\Session::get('organization');
            $insert_data['location_id']= \Session::get('location');
            $insert_data['created_by']=\Session::get('id');
            $insert_data['last_updated_by']=\Session::get('id');
            $insert_data['created_at']=date('Y-m-d');
            $insert_data['updated_at']=date('Y-m-d');
            $insert['active']="Yes";
            $employee_id_get = DB::table('hr_emp_offer_letter')->where('candidate_id',$candidate_id)->get();
            $group_type=$employee_id_get[0]->group_type;
            $insert['group_type']=$group_type;
            $insert['employee_type']=$employee_id_get[0]->employee_type;
            $employee = DB::table('hr_employee_t')->insertGetId($insert);
            $insert_data['employee_number'] = $employee;

            $emp_doc = DB::table('hr_employee_document_tbl')->insertGetId($insert_data);
    $this->auditlog($emp_doc,"onboardprocess","create",$insert_data,"hr_employee_document_tbl");
            DB::table('hr_emp_offer_letter')->where('candidate_id',$candidate_id)->update(array('employee_id' => $employee));
            
          
            $emp_pay_proposal = DB::table('hr_employee_payproposal')->insertGetId(array('employee_id'=>$employee,'basic_pay'=>$employee_id_get[0]->basic_pay,'gratuity'=>$employee_id_get[0]->gratuity,'annual_allowance'=>$employee_id_get[0]->annual_allowance,'hra'=>$employee_id_get[0]->hra,'da'=>$employee_id_get[0]->da,'allowance'=>$employee_id_get[0]->allowance,'pf'=>$employee_id_get[0]->pf,'pt'=>$employee_id_get[0]->pt,'esi'=>$employee_id_get[0]->esi,'gross_pay'=>$employee_id_get[0]->gross_pay,'ctc_pay'=>$employee_id_get[0]->ctc_pay,'employee_type'=>$employee_id_get[0]->employee_type
                ,'company_id'=>$insert_data['company_id'],'organization_id'=>$insert_data['organization_id'],'location_id'=>$insert_data['location_id'],'created_by'=>$insert_data['created_by'],'last_updated_by'=>$insert_data['last_updated_by'],'created_at'=>$insert_data['created_at'],'updated_at'=>$insert_data['updated_at'],'net_pay'=>$employee_id_get[0]->net_pay));
            $daat_up=array('employee_id'=>$employee_id_get[0]->employee_id,'basic_pay'=>$employee_id_get[0]->basic_pay,'gratuity'=>$employee_id_get[0]->gratuity,'annual_allowance'=>$employee_id_get[0]->annual_allowance,'hra'=>$employee_id_get[0]->hra,'da'=>$employee_id_get[0]->da,'allowance'=>$employee_id_get[0]->allowance,'pf'=>$employee_id_get[0]->pf,'pt'=>$employee_id_get[0]->pt,'esi'=>$employee_id_get[0]->esi,'gross_pay'=>$employee_id_get[0]->gross_pay,'ctc_pay'=>$employee_id_get[0]->ctc_pay,'employee_type'=>$employee_id_get[0]->employee_type
                ,'company_id'=>$insert_data['company_id'],'organization_id'=>$insert_data['organization_id'],'location_id'=>$insert_data['location_id'],'created_by'=>$insert_data['created_by'],'last_updated_by'=>$insert_data['last_updated_by'],'created_at'=>$insert_data['created_at'],'updated_at'=>$insert_data['updated_at'],'net_pay'=>$employee_id_get[0]->net_pay);
                $this->auditlog($emp_pay_proposal,"onboardprocess","create",$daat_up,"hr_employee_payproposal");

            if($employee)
            {
                $exp_level= $candidate_details[0]->exp_level;
                if($exp_level == 2)
                {   
                    $insert_data_exp['employee_id'] = $employee;
                    $insert_data_exp['organization_name'] = $candidate_details[0]->current_company;
                    $insert_data_exp['designation'] = $candidate_details[0]->current_position;
                    $insert_data_exp['ctc'] = $candidate_details[0]->current_salary;
                    $emp_exp = DB::table('hr_emp_experience')->insertGetId($insert_data_exp);
                        $this->auditlog($emp_exp,"onboardprocess","create",$insert_data_exp,"hr_emp_experience");

                }
 
                $insert_data_per['id'] = $employee;
                $insert_data_per['gender'] = $candidate_details[0]->gender;
                $insert_data_per['marital_status'] = $candidate_details[0]->marital_status;
                $emp_per = DB::table('hr_emp_personal')->insertGetId($insert_data_per);
                    $this->auditlog($emp_per,"onboardprocess","create",$insert_data_per,"hr_emp_personal");
            }
            if($employee)
            {
                
                $result1 = DB::table('hr_schedule_interview')->where('interview_id',$interview_id)->update(array('employee_id' => $employee,'employee_status'=>1));
                $user_data = array(
                'group_id' => 1,
                'username' => $insert['employee_number'],
                'password' => bcrypt('welcome123'),
                'email' => $insert['email'],
                'first_name' => $insert['first_name'],
                'company_id'=>$insert['company_id'],
                'employee_id' => $employee
            );
                $tb_user = DB::table('tb_users')->insertGetId($user_data);
                    $this->auditlog($tb_user,"onboardprocess","create",$user_data,"tb_users");
                       $group = DB::table('a_group_menu_access_t')->where('group_id',$group_type)->get();
                $group_data['user_id'] = $tb_user;
                $group_data['group_id'] = $group_type;
                if(count($group)>0)
                {
                    $group_data['menus'] = $group[0]->menus;
                    $group_data['permission'] = $group[0]->permission;
                    
                }
                else
                {
                    $group_data['menus'] = '';
                    $group_data['permission'] = '';
                    
                }

                $user_access = DB::table('a_user_access_t')->insertGetId($group_data);
                $this->auditlog($user_access,"createemployee","create",$_POST,"a_user_access_t");
                
            }

            return 1;
    }

 
 //based on employee get type and allowance
        public function employeetypegetallowanceget($id){
        $comp=\Session::get('companyid');
          $result_details = DB::table("m_allowance_tbl")
                ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_allowance_tbl.employee_type')
                ->select('m_allowance_tbl.allowance_name','m_allowance_tbl.allowance_id','m_allowance_tbl.employee_type')
                ->where('m_allowance_tbl.employee_type',$id)->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$comp)->get(); 
        
        $data=array();
        $html='';
        if(count($result_details)>0){
                     
        foreach($result_details as $key=>$value){
            if($value->allowance_id=="50"){
                $value->allowance_name="Mobile/Internet allowance";
            }
            
            $html.='<div class="row mb-3">  <label class="form-control-label col-md-5">'.$value->allowance_name.':</label>    <div class="col-md-7"><input type="hidden"  name="allowance_id[]" value="'.($value->allowance_id).'"  ><input type="text" id="allowance_id"  name="allowance[]" class="form-control allowance  allowance_id'.($key+1).' col-md-3"  >  </div> </div>';
        }
        }
         $hra=DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.limitto')->where('lookup_code','HRA')->where('employee_type',$id)->get();
        	$da=DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.limitto')->where('lookup_code','DA')->where('employee_type',$id)->get();
           if(count($hra)>0){
                $data['hra_per'] = $hra[0]->limitto;
           }else{
                 $data['hra_per'] =0;
           }
           if(count($da)>0){
                   $data['da_per'] = $da[0]->limitto;
           }else{
                 $data['da_per'] =0;
           }
                 $data['html']=$html;
        return $data;
    }
}