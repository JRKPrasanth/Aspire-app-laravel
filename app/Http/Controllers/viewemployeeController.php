<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use App\Employeecreate;
use App\employeetraining;
use App\employeevisaimmigration;
use App\employeeskill;
use App\employeeexperience;
use App\employeeeducation;
use App\employeesalary;
use App\employeecontact;
use DB;

class viewemployeeController extends Controller
{
	
	     public function __construct(){
        $this->data=array();
        $this->data['pageMethod']=\Request::route()->getName();
    }
	
	
   public function index(Request $request)
    {
        
       $this->data['emp_id'] = Session::get('emp_id');
        
      //  dd($this->data['emp_id']);

    ini_set('memory_limit', '-1');
     if($request->has('searchdata')){
        
$user_details = DB::table("hr_employee_t")
    ->leftjoin('m_company_t', 'm_company_t.company_id', '=', 'hr_employee_t.company_id')
    ->leftjoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'hr_employee_t.organisation')
    ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_employee_t.employee_type')
    ->leftjoin('m_job_title', 'm_job_title.job_title_id', '=', 'hr_employee_t.job_title')
    ->leftjoin('m_position', 'm_position.position_id', '=', 'hr_employee_t.position')
    ->leftJoin('m_area_t', function($join) {
        $join->on(DB::raw('CAST(JSON_UNQUOTE(JSON_EXTRACT(hr_employee_t.med_rep_area, \'$\[0\]\')) AS SIGNED)'), '=', 'm_area_t.area_id');
    })
    ->leftjoin('hr_emp_personal', 'hr_emp_personal.employee_id', '=', 'hr_employee_t.employee_id')
    ->select(
        'a_lookuplines_t.lookup_code',
        'm_company_t.company_name',
        'm_organizations_t.organization_name',
        'm_job_title.job_title_name',
        'm_position.position',
        'hr_employee_t.employee_number',
        'hr_employee_t.first_name',
        'hr_employee_t.last_name',
        'hr_employee_t.active',
        'hr_employee_t.date_of_leaving',
        'hr_employee_t.photo',
        'hr_employee_t.date_of_birth',
        'hr_emp_personal.date_of_birth',
        'hr_employee_t.work_telephone_number',
        'm_area_t.area_name',
        'hr_employee_t.email',
        'hr_employee_t.biometric_empno',
        'hr_employee_t.years_of_experience',
        'hr_employee_t.date_of_joining',
        'hr_employee_t.employee_id',
        'hr_employee_t.department',
        DB::raw("
            CASE 
                WHEN hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NULL THEN 'In Service'
                WHEN hr_employee_t.active = 'No' AND hr_employee_t.date_of_leaving IS NOT NULL THEN 'Resigned'
                WHEN hr_employee_t.active = 'No' AND hr_employee_t.date_of_leaving IS NULL THEN 'Resigned'
                WHEN hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NOT NULL THEN 'Resigned - F & F Pending'
            END AS ff_status
        ")
    )
    ->orWhere('a_lookuplines_t.lookup_code', 'like', '%'.$request->searchdata.'%')
    ->orWhere('m_company_t.company_name', 'like', '%'.$request->searchdata.'%')
    ->orWhere('hr_employee_t.first_name', 'like', '%'.$request->searchdata.'%')
    ->orWhere('m_area_t.area_name', 'like', '%'.$request->searchdata.'%')
    ->orWhere('hr_employee_t.employee_number', 'like', '%'.$request->searchdata.'%')
    ->orWhere('hr_employee_t.email', 'like', '%'.$request->searchdata.'%')
    ->orWhere('hr_employee_t.biometric_empno', 'like', '%'.$request->searchdata.'%')
    ->orWhere('hr_employee_t.years_of_experience', 'like', '%'.$request->searchdata.'%')
    ->orWhere('m_position.position', 'like', '%'.$request->searchdata.'%')
    ->orderByRaw("
        CASE 
            WHEN hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NULL THEN 1
            WHEN hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NOT NULL THEN 2
            WHEN hr_employee_t.active = 'No' AND hr_employee_t.date_of_leaving IS NOT NULL THEN 3
            WHEN hr_employee_t.active = 'No' AND hr_employee_t.date_of_leaving IS NULL THEN 4
        END
    ")
    ->get();

     }else{

           $user_details = DB::table("hr_employee_t")
                ->leftjoin('m_company_t', 'm_company_t.company_id', '=', 'hr_employee_t.company_id')
                ->leftjoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'hr_employee_t.organisation')
                ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_employee_t.employee_type')
                ->leftjoin('m_job_title', 'm_job_title.job_title_id', '=', 'hr_employee_t.job_title')
                ->leftjoin('m_position', 'm_position.position_id', '=', 'hr_employee_t.position')
                ->leftjoin('hr_emp_personal', 'hr_emp_personal.employee_id', '=', 'hr_employee_t.employee_id')
                 ->leftJoin('m_area_t', function($join) {$join->on(DB::raw('CAST(JSON_UNQUOTE(JSON_EXTRACT(hr_employee_t.med_rep_area, \'$\[0\]\')) AS SIGNED)'), '=', 'm_area_t.area_id');  })
                ->select('a_lookuplines_t.lookup_code','m_company_t.company_name','m_organizations_t.organization_name','m_job_title.job_title_name','m_position.position','hr_employee_t.employee_number',
                        'hr_employee_t.first_name','hr_employee_t.active','hr_employee_t.date_of_leaving','hr_employee_t.last_name','hr_employee_t.photo', 'm_area_t.area_name',
                        'hr_employee_t.date_of_birth','hr_emp_personal.date_of_birth','hr_employee_t.work_telephone_number',
                        'hr_employee_t.email','hr_employee_t.biometric_empno','hr_employee_t.years_of_experience','hr_employee_t.date_of_joining','hr_employee_t.employee_id','hr_employee_t.department',DB::raw("
            CASE 
                WHEN hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NULL THEN 'In Service'
                WHEN hr_employee_t.active = 'No' AND hr_employee_t.date_of_leaving IS NOT NULL THEN 'Resigned'
                WHEN hr_employee_t.active = 'No' AND hr_employee_t.date_of_leaving IS NULL THEN 'Resigned'
                WHEN hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NOT NULL THEN 'Resigned - F & F Pending'
            END AS ff_status
        ")
        )
        ->orderByRaw("
        CASE 
            WHEN hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NULL THEN 1
            WHEN hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NOT NULL THEN 2
            WHEN hr_employee_t.active = 'No' AND hr_employee_t.date_of_leaving IS NOT NULL THEN 3
            WHEN hr_employee_t.active = 'No' AND hr_employee_t.date_of_leaving IS NULL THEN 4
        END
    ")
        ->get();
     }
// kaviya purpose for mp handling department only show marketing department employee only start
$array_check=[];
$id=\Session::get('emp_id');
//231
                $department_data=\DB::SELECT("select department from hr_employee_t where employee_id =$id");
                $dept=json_decode($department_data[0]->department);
                if(in_array("116",$dept)){
                           $dept_marketing =\DB::SELECT("select department_line_id from m_department_lines_t WHERE `sub_department_code` LIKE '%c007%'"); 
                    if(count($user_details)>0){
                   foreach($user_details as $key=>$value){
                      $dept_id= json_decode($value->department);  
                      foreach($dept_marketing as $k=>$v){
                                 if(in_array($v->department_line_id,$dept_id)){
                             array_push($array_check,$key);      
                             break; 
                      } 
                   }

                } 
            }
                $array=array_unique($array_check);
                if(count($array)>0){
                    if(count($user_details)>0){
                    foreach($user_details as $k1=>$v1){
                      if(in_array($k1,$array)){
                        
                    }else{
                        unset($user_details[$k1]); 
                    }
                    }
                }
                }
            }
          
               // end
        $this->data['employee_list'][] =$user_details; 
        $this->data['urlmenu']=$this->indexs();
        return view('viewemployee.view',$this->data);
    }
    
    public function getArrayfromQuery($table, $columns)
    {   
      
        $data=\DB::table($table)->select($columns)->get();
        $result=[];
        foreach ($data as $key => $value) 
        {
            $result[$value->{$columns[0]}] = strtoupper($value->{$columns[1]});      
            
        }
       
        return $result;
    }
    
    
    
    public function profileview(Request $request,$type, $id) 
    {
        
        if($type == "view_profile"){
         $user_details = DB::table("hr_employee_t")
                ->leftjoin('m_company_t','m_company_t.company_id','=','hr_employee_t.company_id')
                ->leftjoin('m_organizations_t','m_organizations_t.organization_id','=','hr_employee_t.organisation')
                ->leftjoin('m_location_t','m_location_t.location_id', '=', 'hr_employee_t.location_id')
                ->leftjoin('m_job_title', 'm_job_title.job_title_id', '=', 'hr_employee_t.job_title')
                ->leftJoin('m_area_t', function($join) {$join->on(DB::raw('CAST(JSON_UNQUOTE(JSON_EXTRACT(hr_employee_t.med_rep_area, \'$\[0\]\')) AS SIGNED)'), '=', 'm_area_t.area_id');  })
                ->leftjoin('hr_employee_t as registration','hr_employee_t.reporting_manager','=','registration.employee_id')
                ->leftjoin('hr_emp_personal','hr_emp_personal.employee_id','=','hr_employee_t.employee_id')
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_employee_t.employee_type')
                ->leftjoin('m_position', 'm_position.position_id', '=', 'hr_employee_t.position')->select('m_company_t.company_name','m_organizations_t.organization_name','m_location_t.location_name','m_job_title.job_title_name','m_position.position',
                        'hr_employee_t.employee_number','hr_employee_t.reporting_manager','hr_employee_t.date_of_birth','m_job_title.job_title_name','hr_employee_t.date_of_joining','hr_employee_t.years_of_experience',
                        'hr_employee_t.first_name','hr_employee_t.active','hr_employee_t.last_name','hr_employee_t.photo','m_area_t.area_name',
                        'hr_emp_personal.date_of_birth','hr_employee_t.work_telephone_number','hr_employee_t.prefix',
                        'hr_employee_t.email','a_lookuplines_t.lookup_code as employeement_status_name','hr_employee_t.biometric_empno','hr_employee_t.department','hr_employee_t.years_of_experience','hr_employee_t.date_of_joining','hr_employee_t.date_of_leaving','hr_employee_t.employee_id','hr_employee_t.employee_number','hr_employee_t.work_telephone_number','hr_employee_t.work_telephone_number','registration.first_name as reporting_manager_name')->where('hr_employee_t.employee_id',$id)->get();
        $department=json_decode($user_details[0]->department);
        $dept="";
        foreach($department as $key=>$val){
            $data_dept= DB::table("m_department_lines_t")->select('m_department_lines_t.sub_department_name','m_department_lines_t.sub_department_code')->where('m_department_lines_t.department_line_id','=',$val)->get();
$dept.=$data_dept[0]->sub_department_code."-".$data_dept[0]->sub_department_name.",";         
        }
        $dept=rtrim($dept,',');
     $user_details[0]->department_name=$dept;
$personal_details = DB::table("hr_emp_personal")
    ->leftJoin('a_lookuplines_t as nation', 'nation.lookuplines_id', '=', 'hr_emp_personal.nationality')
    ->leftJoin('a_lookuplines_t as mother', 'mother.lookuplines_id', '=', 'hr_emp_personal.mother_tongue')
    ->leftJoin('a_lookuplines_t as religion', 'religion.lookuplines_id', '=', 'hr_emp_personal.religion')
    ->leftJoin('a_lookuplines_t as blood', 'blood.lookuplines_id', '=', 'hr_emp_personal.blood_group')
    ->leftJoin('hr_employee_t as personal', 'personal.employee_id', '=', 'hr_emp_personal.employee_id')
    ->select(
        'nation.lookup_code as nation',
        'mother.lookup_code as mother',
        'religion.lookup_code as religion_name',
        'blood.lookup_code as blood',
        'hr_emp_personal.gender',
        'hr_emp_personal.marital_status',
        'hr_emp_personal.nationality',
        DB::raw('ROUND(DATEDIFF(CURDATE(), hr_emp_personal.date_of_birth) / 365,0) AS age'),
        'hr_emp_personal.mother_tongue',
        'hr_emp_personal.religion',
        'hr_emp_personal.blood_group',
        'hr_emp_personal.passport_number',
        'hr_emp_personal.father_name',
        'hr_emp_personal.mother_name',
        'hr_emp_personal.passport_expiry_date',
        'hr_emp_personal.aadhar_number',
        'hr_emp_personal.pan_number',
        'personal.uan_no',
        DB::raw('(CASE 
            WHEN personal.zone_id = 16 THEN "East"
            WHEN personal.zone_id = 18 THEN "North"
            WHEN personal.zone_id = 19 THEN "Corporate"
            WHEN personal.zone_id = 27 THEN "Central West"
            WHEN personal.zone_id = 24 THEN "South 2"
            WHEN personal.zone_id = 25 THEN "South 1"
            WHEN personal.zone_id = 26 THEN "South 3"
           END) AS zone_id'),
        'personal.esi_no'
    )
    ->where('hr_emp_personal.employee_id', $id)
    ->get();

         
            if($personal_details->isEmpty())
            {
            $personal_details[0]= (object) array();
            $personal_details[0]->gender=0; 
            $personal_details[0]->marital_status=0; 
            $personal_details[0]->nationality=0;    
            $personal_details[0]->age=0;    
            $personal_details[0]->mother_tongue=0;  
            $personal_details[0]->religion=0;   
            $personal_details[0]->blood_group=0;    
            $personal_details[0]->passport_number='';   
            $personal_details[0]->father_name='';   
            $personal_details[0]->mother_name='';   
            $personal_details[0]->passport_expiry_date='';
            $personal_details[0]->aadhar_number=0; 
            $personal_details[0]->pan_number=0; 
            $personal_details[0]->uan_no=0; 
            $personal_details[0]->zone_id=0; 
            $personal_details[0]->esi_no=0; 
            }
            else
            {
                $personal_details = $personal_details;
            }
         
         $contact_details = DB::table("hr_emp_contact")
                ->leftjoin('m_countries_t', 'm_countries_t.country_id', '=', 'hr_emp_contact.permanent_country')
                ->leftjoin('m_countries_t as m_current_country', 'm_current_country.country_id', '=', 'hr_emp_contact.current_country')
                ->leftjoin('m_states_t', 'm_states_t.state_id', '=', 'hr_emp_contact.permanent_state')
                ->leftjoin('m_states_t as m_current_state', 'm_current_state.state_id', '=', 'hr_emp_contact.current_state')
                ->leftjoin('m_cities_t', 'm_cities_t.city_id', '=', 'hr_emp_contact.permanent_city')
                ->leftjoin('m_cities_t  as m_current_cities', 'm_current_cities.city_id', '=', 'hr_emp_contact.current_city')
                ->select('current_postal_code','m_current_state.state_name as current_state_name','m_states_t.state_name','m_current_cities.city_name as current_city_name','m_cities_t.city_name','m_current_country.country_name as current_countyname','m_countries_t.country_name','hr_emp_contact.permanent_flat_no','hr_emp_contact.current_flat_no','hr_emp_contact.current_street','hr_emp_contact.permanent_street','hr_emp_contact.permanent_street_address','hr_emp_contact.current_street_address','hr_emp_contact.permanent_postal_code')->where('hr_emp_contact.employee_id',$id)->get(); 
        
         if($contact_details->isEmpty())
            {
             $contact_details[0]= (object) array();
             $contact_details[0]->permanent_street_address='';
             $contact_details[0]->current_street_address='';
            }
            else
            {
                $contact_details =$contact_details;
                                 $contact_details[0]->permanent_street_address=$contact_details[0]->permanent_flat_no.$contact_details[0]->permanent_street.$contact_details[0]->permanent_street_address.$contact_details[0]->country_name.",".$contact_details[0]->state_name.",".$contact_details[0]->city_name."-".$contact_details[0]->permanent_postal_code;
                     $contact_details[0]->current_street_address=$contact_details[0]->current_flat_no.$contact_details[0]->current_street.$contact_details[0]->current_street_address.$contact_details[0]->current_countyname.",".$contact_details[0]->current_state_name.",".$contact_details[0]->current_city_name."-".$contact_details[0]->current_postal_code;
            }
            
         $experience_details = DB::table("hr_emp_experience")->where('hr_emp_experience.employee_id',$id)->get();   
         $skills_details = DB::table("hr_emp_skills")->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_emp_skills.competency_level')->where('hr_emp_skills.employee_id',$id)->get();   
         $education_details = DB::table("hr_emp_education")->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_emp_education.education_level')->where('hr_emp_education.employee_id',$id)->get(); 
         $training_details = DB::table("hr_emp_training_certification")->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_emp_training_certification.certificate_level')->where('hr_emp_training_certification.employee_id',$id)->get();        
         $visa_details = DB::table("hr_emp_visa_immigration")->leftjoin('m_countries_t','m_countries_t.country_id','=','hr_emp_visa_immigration.visa_country')->where('hr_emp_visa_immigration.employee_id',$id)->get();   
         $bank_details = DB::table("hr_emp_salary")->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_emp_salary.account_type')->where('hr_emp_salary.employee_id',$id)->get(); 
            
        $this->data['user_details']     = $user_details;
        $this->data['personal_details'] = $personal_details;
        $this->data['contact_details']  = $contact_details;
        $this->data['skill_details']  = $skills_details;
        $this->data['experience_details']  = $experience_details;
        $this->data['education_details']  = $education_details;
        $this->data['training_details']  = $training_details;
        $this->data['visa_details']  = $visa_details;
        $this->data['bank_details']  = $bank_details;
            
            
        
            
            return view('viewemployee.show',$this->data);
            
        }
        if($type == "delete_profile" )
        {
            
        $user_details = DB::table("hr_employee_t")->where('hr_employee_t.employee_id',$id)->delete();
        $user_details = DB::table("hr_emp_contact")->where('hr_emp_contact.employee_id',$id)->delete();
        $user_details = DB::table("hr_emp_education")->where('hr_emp_education.employee_id',$id)->delete();
        $user_details = DB::table("hr_emp_experience")->where('hr_emp_experience.employee_id',$id)->delete();
        $user_details = DB::table("hr_emp_personal")->where('hr_emp_personal.employee_id',$id)->delete();
        $user_details = DB::table("hr_emp_skills")->where('hr_emp_skills.employee_id',$id)->delete();
        $user_details = DB::table("hr_emp_salary")->where('hr_emp_salary.employee_id',$id)->delete();
        $user_details = DB::table("hr_emp_training_certification")->where('hr_emp_training_certification.employee_id',$id)->delete();
        $user_details = DB::table("hr_emp_visa_immigration")->where('hr_emp_visa_immigration.employee_id',$id)->delete();
      
            return 2;
        
        }
        
        
        
         
    }
    
    function check_existing($company_code)
    {
        
        
            $val = 0;
            $employee_count = 0;
            $out_put = array();
           
            $emp_code = DB::table('hr_employee_t')->orderBy('employee_count', 'desc')->where('employee_number','LIKE','%'.$company_code.'%')->get();
          
            if($emp_code)
            {
                $val=$emp_code[0]->employee_count+1;
                $val=sprintf('%03d',$val);
                $employee_number = $company_code.$val;
            }
            else    
            {
                $employee_number = $company_code."001";  
                $val =1;
            }
                //$val=$emp_code[0]->employee_count+1;
                $employee_count = $val;
                
                $out_put['employee_count'] = $employee_count;
                $out_put['employee_number'] = $employee_number;
                
               
            return $out_put;
    }
    
    public function getArrayfromQuery1($table, $columns)
    {
       
        $data=\DB::table($table)->select($columns)->get();
        $result=[];
        foreach ($data as $key => $value) 
        {
         
            $result[$value->{$columns[0]}] = strtoupper($value->{$columns[1]});            
        }
       
        return $result;
    }
    
    
    public function getLoad(Request $request)
    {
     
            $batch_id=$request->input('batch_number');
       
            $batch_number = DB::table('hr_emp_upload_details_t')->where('emp_upload_id',$batch_id)->get();
        
            $field_name1 = array("company_id","location_id","department_id","employee_id",
                            "job_title_id","position_id","employment_status_id","nationality_id",
                            "language_id","country_id","state_id","city_id","education_level_id",
                            "id","nationality_id","language_id");
        
            $field_name2 = array("company_name","location_name","department_name","employee_number",
                            "job_title_name","position","emp_status_name","nationality_name",
                            "language_name","country_name","state_name","city_name","education_level_name",
                            "blood_name","nationality_name","language_name");
        
            $variable_name = array("com_columns"=>"company_details","loc_columns"=>"location_details","dep_columns"=>"dep_details","rep_columns"=>"rep_details",
                                "job_columns"=>"job_details","position_columns"=>"position_details","emp_columns"=>"emp_details","national_columns"=>"national_details","lan_columns"=>"lan_details","country_columns"=>"country_details","state_columns"=>"state_details",
                                "city_columns"=>"city_details","edu_columns"=>"edu_details","blood_columns"=>"blood_details","national_columns"=>"national_details","lan_columns"=>"lan_details");
        
            $table_name = array("m_company_t","m_location_t","m_department_lines_t","hr_employee_t",
                            "m_job_title","m_position","m_emloyment_status","m_nationality","m_language","m_countries_t","m_states_t",
                            "m_cities_t","m_education_level","blood_group","m_nationality","m_language");
        
        
            $rep_mng = array("employee_number","first_name");
            $rep_mng_details = $this->getArrayfromQuery1('hr_employee_t',$rep_mng);
        
            $i=0;
            foreach($variable_name as $key=>$value)
            {
                ${$key}     = array($field_name1[$i],$field_name2[$i]);
                ${$value}   = $this->getArrayfromQuery($table_name[$i],${$key});    
                $i++; 
            }
            
            $result  = DB::table('hr_employee_int_t')->where('batch_no',$batch_number[0]->batch_no)->get();
            $batch_no = $batch_number[0]->batch_no;
    
            foreach($result as $key)
            {
                /* testing */  
                $result         = $this->check_existing($key->company);
                $emp_number1    = $emp_number =$result['employee_number'];
                $emp_no         = $key->emp_number;
                $emp_count      =   $result['employee_count'];
                $company        =   $key->company;
                $location       =   $key->location;
                $department     =   $key->department;
                $reporting_manger   =   $key->reporting_manger;
                $job_name           =   $key->job_title;
                $position           =   $key->position;
                $employment_status  =   $key->employment_status;
                $date_of_birth      =   $key->birth_date;
                $mobile_number      =   $key->mobile_number;
                $date_of_joining    =   $key->joining_date;
                $blood              =   $key->blood_group;
                $flg=0;

                $variable_array = array('com_name','location_name','department_name','emp_name');
                $field_value    = array('company_id','location_id','department_id','emp_val');
                $value_variable = array('company','location','department','employment_status');

                $variable_name1 = array("company_details","location_details","dep_details","emp_details");

                $variable_name1 = array("company_details","location_details","dep_details","emp_details",
                        "job_details","position_details","emp_details","national_details","lan_details","country_details","state_details",
                       "city_details","edu_details","blood_details");



                foreach($value_variable as $key1 => $value)
                {
                    ${$variable_array[$key1]}=  ${$value};
                    ${$field_value[$key1]}= array_search(${$value}, ${$variable_name1[$key1]});
                }  

                /** not empty check ***/

                if($emp_no=="")
                {
                    $update = DB::table('hr_employee_int_t')->where('batch_no',$batch_no)->where('emp_number',$emp_no)->update(array('remarks'=>'Employee Number Field Required'));
                    $flg=1; 
                }
                else
                {
                    $emp_check = DB::table('hr_employee_t')->where('employee_number',$emp_no)->exists();
                    if($emp_check)
                    {
                        $update = DB::table('hr_employee_int_t')->where('batch_no',$batch_no)->where('emp_number',$emp_no)->update(array('remark'=>'Employee Number Already Exists'));
                        $flg=1;  
                    }
                }

                if($company==null || $company=="")
                {

                         $update = DB::table('hr_employee_int_t')->where('batch_no',$batch_no)->where('emp_number',$emp_no)->update(array('remark'=>'Company Field Required'));


                        $flg=1;
                }
                else if($company_id==false)
                { 
                    $update = DB::table('hr_employee_int_t')->where('batch_no',$batch_no)->where('emp_number',$emp_no)->update(array('remark'=>'Company Mismatch'));
                    $flg=1;
                }

                if($location=="")
                {
                    $update = DB::table('hr_employee_int_t')->where('batch_no',$batch_no)->where('emp_number',$emp_no)->update(array('remark'=>'Location Field Required'));
                     $flg=1;
                }
                else if($location_id==false)
                {

                    $update = DB::table('hr_employee_int_t')->where('batch_no',$batch_no)->where('emp_number',$emp_no)->update(array('remark'=>'Location Mismatch'));
                    $flg=1;
                }

                if($department=="")
                {
                    $update = DB::table('hr_employee_int_t')->where('batch_no',$batch_no)->where('emp_number',$emp_no)->update(array('remark'=>'Department Field Required'));
                    $flg=1;
                }
                elseif($department_id==false)
                {
                    $update = DB::table('hr_employee_int_t')->where('batch_no',$batch_no)->where('emp_number',$emp_no)->update(array('remark'=>'Department Mismatch'));
                    $flg=1;
                }

                if($flg==1)
                {

                }
                else
                {
                    $prefix_opt = array('1' => 'MR', '2' => 'MS', '3' => 'MRS');
                    $employee_type = array('1' => 'WORKER', '2' => 'STAFF');

                    $user['username'] = $employee_number = $data['employee_number'] = $emp_number;
                    $data['prefix']=$key->prefix;
                    $data['employee_count']=$emp_count;

                    $prefix         =   $key->prefix;
                    $prefix_result  =   array_search($prefix, $prefix_opt);
                    $data['prefix'] =   $prefix_result;

                    $user['first_name']=  $data['first_name']=$key->first_name;
                    $user['last_name']= $data['last_name']=$key->last_name;

                    $data['date_of_birth']=date("Y-m-d", strtotime($key->birth_date));
                    $user['email']=  $data['email']=$key->email;

                    $user['company_id']=$data['company_id'] = (($company_id != false) ? $company_id : "");
                    $data['location_id'] = (($location_id != false) ? $location_id : "");
                    $data['department']=(($dep_id != false) ? $dep_id : "");
                }


                //$rept_mng   = rtrim(strtoupper($key->reporting_manger));
                $rept_mng   = $key->reporting_manger;


                $rept_mng1 = $rept_mng;
                $rept_id    = array_search($rept_mng1, $rep_mng_details);
                $data['reporting_manager']=(($rept_id != false) ? $rept_id : "");

                $job_name=rtrim(strtoupper($key->job_title));
                $job_title = array_search($job_name, $job_details);
                $data['job_title']=(($job_title != false) ? $job_title : "");
                
                 
                $position_name=strtoupper($key->position);
                $position_name = array_search($position_name, $position_details);
                $data['position']=(($position_name != false) ? $position_name : "");

                //setting company id for company name from excel upload
                $emp_name=strtoupper($key->employment_status);
                $emp_name = array_search($emp_name, $emp_details);
                $data['employment_status']=(($emp_name != false) ? $emp_name : "");
                
               
                $data['date_of_joining']=date("Y-m-d", strtotime($key->joining_date));
                $data['esi_no']=$key->esi_number;
                $data['pf_no']=$key->pf_number;
                $data['work_telephone_number']=$key->mobile_number;

                // dd($data);
                $employee_id = DB::table('hr_employee_t')->insertGetId($data);

                $user['employee_id']=$employee_id;
                $user['password'] = bcrypt('welcome123');

                $insert_tb_user = DB::table('tb_users')->insert($user);

                if($employee_id)
                {
                    $personal['id']=$employee_id;
                    $gender=  trim(strtolower($key->gender));
                    $personal['gender']=ucwords($gender);
                    $marital_status=trim(strtolower($key->marital_status));
                    $personal['marital_status']=ucwords($marital_status);

                    
                    $nationality=strtoupper($key->nationality);
                    $nationality = array_search($nationality, $national_details);
                    $personal['nationality']=(($nationality != false) ? $nationality : "");
                    
                    $personal['date_of_birth']=$key->birth_date_other;
                    $personal['age']=$key->age;
                    
                   
                    $mother_tongue = array_search(strtoupper($key->mother_tongue), $lan_details);                   
                    $personal['mother_tongue']=(($mother_tongue != false) ? $mother_tongue : "");
                    $personal['religion']=$key->religion;

                    $language=array();
                    if($key->language1!='')
                    $language[0]=[$key->language1,$key->read1,$key->write1];
                    if($key->language2!='')
                    $language[1]=[$key->language2,$key->read2,$key->write2];
                    if($key->language3!='')
                    $language[2]=[$key->language3,$key->read3,$key->write3];

                    $personal['language']=json_encode($language);

                    $blood_grp=rtrim(strtoupper($key->blood_group));
                    $blood_grp = array_search($blood_grp, $blood_details);
                    $personal['blood_group']=(($blood_grp != false) ? $blood_grp : "");
                    
                    $personal['id_name']=$key->id_type_1;
                    $personal['id_number']=$key->id_number_1;
                    $personal['id_name1']=$key->id_type_2;
                    $personal['id_number1']=$key->id_number_2;


                    $personal['father_name']=$key->father_name;
                    $personal['mother_name']=$key->mother_name;
                    $personal['spouse_name']=$key->spouse_name;
                    $personal['spouse_dob']=$key->spouse_dob;
                    $personal['no_of_children']=$key->children_number;
                    $personal['children_name1']=$key->child1name;
                    $personal['children_dob1']=$key->child1dob;
                    $personal['children_name2']=$key->child2name;
                    $personal['children_dob2']=$key->child2dob;

                   
                    $insert_tb_personal = DB::table('hr_emp_personal')->insert($personal);

                    $contact['employee_id']=$employee_id;
                    $contact['permanent_street_address']=$key->permanent_address;
                    $contact['permanent_country']=  $key->perm_country;
                    $contact['permanent_state']=  $key->perm_state;
                    $contact['permanent_city']=  $key->perm_city;
                    $contact['permanent_postal_code']=$key->perm_pincode;
                    $contact['current_street_address']=$key->current_address;
                    $contact['current_country']=$key->curr_country;
                    $contact['current_country']=  $key->curr_country;
                    $contact['current_state']=  $key->curr_state;
                    $contact['current_city']=$key->curr_city;
                    $contact['current_postal_code']=$key->curr_pincode;
                    $emergency_contacts=array();
                    if($key->emergency_contact!="")
                    {
                        $emergency_contacts=[$key->emergency_contact,$key->emergency_relationtype,$key->emergency_address,$key->emergency_number];
                    }

                    $contact['emergency_contacts']=json_encode($emergency_contacts);

                    $insert_tb_contact = DB::table('hr_emp_contact')->insert($contact);
                    
                    
                    $salary['employee_id']  =$employee_id;
                    $salary['bank_name']    =$key->bank_name;
                    $salary['branch_name']  =$key->branch_name;
                    $salary['ifsc_code']    =$key->ifsc_code;
                    $salary['account_holder_name']=$key->acc_holder_name;
                    $salary['account_number']   =$key->accunt_number;
                  
                    
                    $insert_tb_salary = DB::table('hr_emp_salary')->insert($salary);
                    
                    
                    /** education level ***/
                    if($key->education_level1!="")
                    {
                        $education1['employee_id']=$employee_id;    
                        $education_level=strtoupper($key->education_level1);
                       
                        $education_level = array_search($education_level, $edu_details);
                        $education1['education_level']=(($education_level != false) ? $education_level : "");
                        

                        $education1['institution_name']=$key->institutionname1;
                        $education1['course']=$key->course_board1;
                         if($key->fromduration1!="")
                        $education1['from_date']=date("Y-m-d", strtotime($key->fromduration1));
                          if($key->toduration1!="")
                        $education1['to_date']=date("Y-m-d", strtotime($key->toduration1));
                        $education1['percentage']=$key->percentage1;
                        
                        $insert_tb_education1 = DB::table('hr_emp_education')->insert($education1);
                      
                    }
                    if($key->education_level2!="")
                    {
                        $education2['employee_id']=$employee_id;
                       //setting Education Level id for Education Level name from excel upload 
                        $education_level=strtoupper($key->education_level2);
                        $education_level = array_search($education_level, $edu_details);
                        $education2['education_level']=(($education_level != false) ? $education_level : "");
                      
                        $education2['institution_name']=$key->institutionname2;
                        $education2['course']=$key->course_board2;
                        if($key->fromduration2!="")
                        $education2['from_date']=date("Y-m-d", strtotime($key->fromduration2));
                         if($key->to_duration2!="")
                        $education2['to_date']=date("Y-m-d", strtotime($key->to_duration2));
                        $education2['percentage']=$key->percentage2;
                      
                        
                        $insert_tb_education2 = DB::table('hr_emp_education')->insert($education2);
                    }
                    if($key->education_level3!="")
                    {
                        
                        $education3['employee_id']=$employee_id;
                       //setting Education Level id for Education Level name from excel upload 
                        $education_level=strtoupper($key->education_level3);
                        $education_level = array_search($education_level, $edu_details);
                        $education3['education_level']=(($education_level != false) ? $education_level : "");
                      
                        $education3['institution_name']=$key->institution3;
                        $education3['course']=$key->course_board3;
                        if($key->fromduration3!="")
                       $education3['from_date']=date("Y-m-d", strtotime($key->from_duration3));
                         if($key->to_duration3!="")
                       $education3['to_date']=date("Y-m-d", strtotime($key->to_duration3));
                       $education3['percentage']=$key->percentage3;
                      
                       $insert_tb_education3 = DB::table('hr_emp_education')->insert($education3);
                      
                    }
                    
                    if($key->organization_name1!="")
                    {
                        $experience1['employee_id']=$employee_id;
                        $experience1['organization_name']=$key->organization_name1;
                        $experience1['organization_website']=$key->website1;
                        $experience1['designation']=$key->designation1;
                        $experience1['ctc']=$key->ctc1;
                        if($key->expfrom1!="")
                        $experience1['from_date']=date("Y-m-d", strtotime($key->expfrom1));
                        if($key->expto1!="")
                        $experience1['to_date']=date("Y-m-d", strtotime($key->expto1));

                        $insert_tb_organization1 = DB::table('hr_emp_experience')->insert($experience1);
                      
                      
                    }
                    if($key->organization2!="")
                    {
                      $experience2['employee_id']=$employee_id;
                      $experience2['organization_name']=$key->organization2;
                      $experience2['organization_website']=$key->website2;
                      $experience2['designation']=$key->designation2;
                      $experience2['ctc']=$key->ctc2;
                       if($key->expfrom2!="")
                      $experience2['from_date']=date("Y-m-d", strtotime($key->expfrom2));
                        if($key->expto2!="")
                      $experience2['to_date']=date("Y-m-d", strtotime($key->expto2));
                        
                      \DB::table("emp_experience")->insertGetid($experience2);
                      
                        $insert_tb_organization2 = DB::table('hr_emp_experience')->insert($experience2);
                    }
                    
                    if($key->organization3!="")
                    {
                      $experience3['employee_id']=$employee_id;
                      $experience3['organization_name']=$key->organization3;
                      $experience3['organization_website']=$key->website3;
                      $experience3['designation']=$key->designation3;
                      $experience3['ctc']=$key->ctc3;
                       if($key->expfrom3!="")
                      $experience3['from_date']=date("Y-m-d", strtotime($key->expfrom3));
                        if($key->expto3!="")
                      $experience3['to_date']=date("Y-m-d", strtotime($key->expto3));
                      
                       
                       $insert_tb_organization3 = DB::table('hr_emp_experience')->insert($experience3);
                    }
                    
                    if($key->skills1 !="")
                    {
                        $skill1['employee_id']=$employee_id;
                        $skill1['skill']=$key->skills1;
                        $skill1['version']=$key->version1;
                        $skill1['competency_level']=$key->competency1;
                        
                        $insert_tb_skills1 = DB::table('hr_emp_skills')->insert($skill1);
                        
                        
                    }
                    if($key->skills2 !="")
                    {
                        $skill2['employee_id']=$employee_id;
                        $skill2['skill']=$key->skills2;
                        $skill2['version']=$key->version2;
                        $skill2['competency_level']=$key->competency2;
                        
                        $insert_tb_skills2 = DB::table('hr_emp_skills')->insert($skill2);
                        
                        
                    }   
                    if($key->skills3 !="")
                    {
                        $skill3['employee_id']=$employee_id;
                        $skill3['skill']=$key->skills3;
                        $skill3['version']=$key->version3;
                        $skill3['competency_level']=$key->competency3;
                        
                        $insert_tb_skills3 = DB::table('hr_emp_skills')->insert($skill3);
                    }
                    
                    
                    $employee_id=$key->hr_employee_id;
                    $result = DB::table('hr_employee_int_t')->where('employee_id',$employee_id)->update(array('remark' => 'Success','status'=>1));
                }


        }
    }
}
