<?php
namespace App\Http\Controllers;
use App\Employeecreate;
use App\employeetraining;
use App\employeevisaimmigration;
use App\employeeskill;
use App\employeeexperience;
use App\employeeeducation;
use App\employeesalary;
use App\employeecontact;

use App\employeepersonal;
use Illuminate\Http\Request,DB,Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Input;
use DateTime;

class EmployeecreateController extends Controller
{

    public function __construct()
    {
		
        $this->data=array();
        $this->model=new Employeecreate();
       $this->data['pageMethod']=\Request::route()->getName();
       $this->data['pageModule']=\Request::route()->getName();
        $this->data['urlmenu']=$this->indexs();
		
    }
 /** index pge open funcation start **/   
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

       $compid=\Session::get('companyid');
        $this->data['tab_id'] 			= 1;
        $this->data['company']     		= $this->jCombo('m_company_t','company_id','company_name',$compid);
        $this->data['department']   		= $this->jCombo('m_department_lines_t','department_line_id','sub_department_name','');
        $this->data['job_title']    		= $this->jCombo('m_job_title','job_title_id','job_title_name','');		
        $this->data['area']    		= $this->jCombologin('m_area_t','area_id','area_name','');		
        $this->data['position']     		= $this->jCombo('m_position','position_id','position','');
        $this->data['location']                 = $this->jCombo('m_location_t','location_id','location_name','');
        $this->data['group_type']               =$this->jcombologin("a_m_group_t","group_id","group_name",'');
        $this->data['employee_type']            = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='EMPLOYEE_TYPE'");
         $this->data['account_type']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='ACCOUNT_TYPE'");
         $this->data['education_level']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='EDUCATION_LEVEL'");  
         $this->data['competency_level']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='COMPETENCY_LEVEL'");
         $this->data['certificate_level']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='CERTIFICATE_LEVEL'");
         $this->data['visa_country']         = $this->jcombologin('m_countries_t','country_id','country_name','');
         $this->data['idtype1']            = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='idtype1'");
        $this->data['idtype2']            = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='idtype1'");
             $this->data['nationality']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='NATIONALITY'");
        $this->data['mother_tongue']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='MOTHER_TONGUE'");
        $this->data['religion']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='RELIGION'");
        $this->data['blood_group']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='BLOOD_GROUP'");
        $user_id = Session::get('emp_id');
        $this->data['reporting_manager']     = $this->jcustomselectcomp('hr_employee_t','employee_id','employee_number|first_name',$user_id,'');
        $this->data['reporting_manager1']     = $this->jcustomselectcomp('hr_employee_t','employee_id','employee_number|first_name',$user_id,'');
        $this->data['p_city']      			 = $this->jCombologin('m_cities_t','city_id','city_name','');
        $this->data['p_state']     			 = $this->jCombologin('m_states_t','state_id','state_name','');
        $this->data['p_country']  			 = $this->jCombologin('m_countries_t','country_id','country_name','');
        $this->data['c_city']    			 = $this->jCombologin('m_cities_t','city_id','city_name','');
        $this->data['c_state']     			 = $this->jCombologin('m_states_t','state_id','state_name','');
        $this->data['c_country']   			 = $this->jCombologin('m_countries_t','country_id','country_name','');
        $employee_official      			 = Schema::getColumnListing('hr_employee_t');
        $employee_personal     				 = Schema::getColumnListing('hr_emp_personal');
        $employee_contact      				 = Schema::getColumnListing('hr_emp_contact');
        $employee_salary       				 = Schema::getColumnListing('hr_emp_salary');
        $employee_education    				 = Schema::getColumnListing('hr_emp_education');
        $employee_experience   				 = Schema::getColumnListing('hr_emp_experience');
        $employee_skill        				 = Schema::getColumnListing('hr_emp_skills');
        $employee_training    				 = Schema::getColumnListing('hr_emp_training_certification');
        $employee_visa         				 = Schema::getColumnListing('hr_emp_visa_immigration');
        
        foreach($employee_official as $key=>$value)
        {
            $employeeofficial[0][$value] = '';
        }
        $this->data['employee_official'][0]= (object)$employeeofficial[0];
        
        foreach($employee_personal as $key=>$value)
        {
            $employeepersonal[0][$value] = '';
        }
        $this->data['employee_personal'][0]= (object)$employeepersonal[0];
        
        foreach($employee_contact as $key=>$value)
        {
            $employeecontact[0][$value] = '';
        }
        $this->data['employee_contact'][0]= (object)$employeecontact[0];
        
        
        $this->data['employee_salary']= [];
        $this->data['employee_education']= [];
        $this->data['employee_experience']= [];
        $this->data['employee_skill']= [];
        $this->data['employee_training']= [];
        $this->data['employee_visa']= [];
        $this->data['edit_id'] = '';
		$this->data['save_button']=1;
        
        return view('employeecreate.form',$this->data);
    }
     /** index pge open funcation end **/  
    /*
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    /** save funcation start **/
    public function save(Request $request)
    {

        $edit_id = $request->input('edit_id');
        $row_id = $request->input('row_id');        
        $form_name = $request->input('form_name');
     /** employee create **/
            if($form_name == "official_details")
            {
                if($edit_id == '')
                {                
                $employeecreate = new Employeecreate();                
                $employeecreate->employee_number = $request->input('employee_number');
                $employeecreate->first_name      = $request->input('first_name');
                $employeecreate->last_name       = $request->input('last_name');
                $employeecreate->email           = $request->input('email');
                $employeecreate->prefix          = $request->input('prefix');
                $employeecreate->company_id      = $request->input('company_id');
                //$employeecreate->organisation    = $request->input('organisation');
                $employeecreate->organisation    = \Session::get('organization');
                $employeecreate->location_id     = json_encode($request->input('location_id'));
                $employeecreate->department      = json_encode($request->input('department'));
                $employeecreate->reporting_manager = $request->input('reporting_manager');
                $employeecreate->reporting_manager1 = $request->input('reporting_manager1');
                $employeecreate->job_title       = $request->input('job_title');
                $employeecreate->med_rep_area       = json_encode($request->input('area'));
                $employeecreate->position        = $request->input('position');
                $employeecreate->date_of_joining = $request->input('date_of_joining');
                $employeecreate->esi_dispensary          = $request->input('esi_dispensary');
                $employeecreate->esi_no          = $request->input('esi_no');
                $employeecreate->pf_date          = $request->input('pf_date');
                $employeecreate->pf_no           = $request->input('pf_no');
                $employeecreate->uan_no          = $request->input('uan_no');
                $employeecreate->work_telephone_number   = $request->input('work_telephone_number');
                $employeecreate->alternative_telephone_number   = $request->input('alternative_telephone_number');
                $employeecreate->biometric_empno = $request->input('biometric_empno');
                $employeecreate->active   = $request->input('active');
                $employeecreate->ot_formula   = $request->input('ot_formula');
                $employeecreate->employee_type   = $request->input('employee_type');
                $employeecreate->c_l   = $request->input('c_l');
                $employeecreate->s_l   = $request->input('s_l');
                $employeecreate->e_l   = $request->input('e_l');
                $employeecreate->group_type =$group_type    = $request->input('group_type');
                $employeecreate->zone_id  = $request->input('zone_id');
                $employeecreate->updated_at      =  "";
                $employeecreate->created_at      =  "";
                
                $employee_count =  Employeecreate::orderBy('employee_count', 'DESC')->where('company_id',$employeecreate->company_id)->get();
                if(count($employee_count)>0)
                    $count = $employee_count[0]->employee_count+1;
                else
                    $count = 1; 
                
                $employeecreate->employee_count = $count;                
                
                $image = $request->file('photo');
                if($image != "")
                {
                    $name = $employeecreate->company_id.rand(10,100).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/profile_images');
                    $image->move($destinationPath, $name);
                    $employeecreate->photo = $name;
                }else{
                    if($request->input('prefix')=="1"){
                        $employeecreate->photo ="male-icon.png";
                    }else{
                       $employeecreate->photo ="female-icon.png"; 
                    }
                }               
                
                $employeecreate->save(); 
                  
                $insert_id = $employeecreate->employee_id;
               
               // auditlog
              $this->auditlog($insert_id,"createemployee","create",$_POST,"hr_employee_t");
                /*** user table ****/
             
                $user_details = array('active'=>'Yes','admindept_id'=>$employeecreate->department  ,'group_id' => $group_type,'mobile_no'=>$employeecreate->work_telephone_number,'org_id'=>$employeecreate->organisation,'employee_number'=>$employeecreate->employee_number,'username' =>$employeecreate->employee_number,'password' => bcrypt('welcome123'),'email' => $employeecreate->email,
                'first_name' => $employeecreate->first_name,'loc_id'=>$employeecreate->location_id ,'last_name' => $employeecreate->last_name,'company_id'=>$employeecreate->company_id,'employee_id' => $insert_id);
                /**********/
                
                  $tb_user = DB::table('tb_users')->insertGetId($user_details);   // auditlog
                 $this->auditlog($tb_user,"createemployee","create",$_POST,"tb_users");
              
                // leave balace details 
                 $leave_details = array('causal_leave'=>$employeecreate->c_l,'earn_leave' => $employeecreate->e_l,'sick_leave'=>$employeecreate->s_l,'ocl'=>$employeecreate->c_l,'oel'=>$employeecreate->e_l,'osl' =>$employeecreate->e_l,
                'location_id'=>$employeecreate->location_id ,'company_id'=>$employeecreate->company_id,'employee_id' => $insert_id);
                
                 $tb_levbal = DB::table('Leave_balance_tbl')->insertGetId($leave_details);  
                 $this->auditlog($tb_levbal,"createemployee","create",$_POST,"Leave_balance_tbl");
              
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
                   // auditlog
              $this->auditlog($user_access,"createemployee","create",$_POST,"a_user_access_t");
                
              
                
                }
                else
                {
                    $query = Employeecreate::find($edit_id);
                    $input_data = $request->all();
                    $department1 = json_decode($query->department);
                    $department2 = $request->input('department');
                    
                    foreach($department1 as $key => $value)
                    { 
                        if(in_array($value, $department2))
                        {
                           $k = true; 
                        }
                        else
                        {
                            $k = false;
                        }
                    }
                    
                    if($k == false)
                    {
                        $data['department_id'] = json_encode($department2);
                        $data['employee_id'] = $edit_id;
                        $data['updated_at'] = date('Y-m-d');
                        $data['updated_by'] = Session::get('emp_id'); 
                        $querys = DB::table('department_changes')->insert($data);
                    }
                    
                    $input_data['department'] = json_encode($request->input('department'));
                    //dd($edit_id);
                    $update_employee  = Employeecreate::findOrFail($edit_id);
                    
                    $image = $request->file('photo');
                    if($image != "")
                    {                        
                        $name = $update_employee->company_id.rand(10,100).'.'.$image->getClientOriginalExtension();
                        $destinationPath = public_path('/images/profile_images');
                        $image->move($destinationPath, $name);
                        $photo = $name;
                        
                    }
                    else
                    {
                        $user = DB::table('hr_employee_t')->where('employee_id',$edit_id)->first();
                        $photo = $user->photo;
                    }
                    $input_data['photo'] = $photo;
                    $input_data['location_id']= json_encode($input_data['location_id']);
                    $input_data['med_rep_area']= json_encode($input_data['area']);
                    $update_employee->update($input_data);
             
                    $insert_id = $edit_id;
                      // auditlog
              $this->auditlog($edit_id,"createemployee","update",$_POST,"hr_employee_t");
                }
                
                
                $outp_put['status']=1;
                $outp_put['id']=$insert_id;
                return $outp_put;
            }
            /** personal details **/
            else if($form_name == "personal_details")
            {
               
                $sql = \DB::table('hr_emp_personal')->where('employee_id',$edit_id)->get();

                if($sql->isEmpty())
                {
                                    
                    $gender         = $request->input('gender');
                    $marital_status = $request->input('marital_status');
                    $nationality    = $request->input('nationality');
                    $date_of_birth  = $request->input('date_of_birth');
                    $age            = $request->input('age');
                    $monther_tongue = $request->input('monther_tongue');
                    $religion       = $request->input('religion');
                    $blood_group    = $request->input('blood_group');
                    $personal_mail  = $request->input('personal_mail');
                    $personal_mobile = $request->input('personal_mobile');
                    $passport_expiry_date = $request->input('passport_expiry_date');
                    $pan_number     = $request->input('pan_number');
                    $aadhar_number  = $request->input('aadhar_number');
                    $id_name        = $request->input('id_name');
                    $id_number      = $request->input('id_number');
                    $id_name1       = $request->input('id_name1');
                    $id_number1     = $request->input('id_number1');
                    $father_name    = $request->input('father_name');
                    $father_aadhar_number = $request->input('father_aadhar_number');
                    $mother_name    = $request->input('mother_name');
                    $mother_aadhar_number    = $request->input('mother_aadhar_number');
                    $spouse_name    = $request->input('spouse_name');
                    $spouce_aadhar_number    = $request->input('spouce_aadhar_number');
                    $spouse_dob     = $request->input('spouse_dob');
                    $no_of_children = $request->input('no_of_children');
                    $children_name1 = json_encode($request->input('children_name'));
                    $children_dob1  = json_encode($request->input('children_dob'));
                    $updated_at      =  "";
                    $created_at      =  "";
                    
                    for($i=0;  $i < count($request->input('language')); $i++)
                    {
                        $language   = $request->input('language')[$i];
                       
                        $langr      =  !empty($request->input('langr'.$i)) ? $request->input('langr'.$i) : "";
                        $langw      =  !empty($request->input('langw'.$i)) ? $request->input('langw'.$i) : "";
                        $langs      =  !empty($request->input('langs'.$i)) ? $request->input('langs'.$i) : "";
                        $data = array($language,$langr,$langw,$langs); 
                        $result[] = $data;   
                    }
                    $data['language'] = json_encode($result);
                    $language_known = json_encode($request->input('language'));
                    
                    $datas = array('employee_id'=>$edit_id,'gender'=>$gender,'marital_status'=>$marital_status,'nationality'=>$nationality,'date_of_birth'=>$date_of_birth
                    ,'age'=>$age,'mother_tongue'=>$monther_tongue,'religion'=>$religion,'blood_group'=>$blood_group,'personal_mail'=>$personal_mail,'personal_mobile'=>$personal_mobile
                    ,'passport_expiry_date'=>$passport_expiry_date,'pan_number'=>$pan_number,'aadhar_number'=>$aadhar_number
                    ,'id_name'=>$id_name,'id_number'=>$id_number,'id_name1'=>$id_name1,'id_number1'=>$id_number1,'father_name'=>$father_name,'mother_name'=>$mother_name,
                    'spouse_name'=>$spouse_name,'spouse_dob'=>$spouse_dob,'no_of_children'=>$no_of_children,'children_name1'=>$children_name1,
                    'children_dob1'=>$children_dob1,'updated_at'=>$updated_at,'created_at'=>$created_at,'language'=>$language_known,'father_aadhar_number'=>$father_aadhar_number,'mother_aadhar_number'=>$mother_aadhar_number,'spouce_aadhar_number'=>$spouce_aadhar_number);

                    $user = DB::table('hr_emp_personal')->insert([$datas]);
                $user_id = DB::select('SELECT id FROM hr_emp_personal order by id desc limit 1');
                                          // auditlog
                
              $this->auditlog($user_id[0]->id,"createemployee-personal","create",$_POST,"hr_emp_personal");
                  $outp_put['status']=1;
                    return $outp_put;
                }
                else
                {    
                    
                   // dd($request->all());
                    for($i=0;  $i < count($request->input('language')); $i++)
                    {
                        $language   = $request->input('language')[$i];
                        $langr      =  !empty($request->input('langr'.$i)) ? $request->input('langr'.$i) : "";
                        $langw      =  !empty($request->input('langw'.$i)) ? $request->input('langw'.$i) : "";
                        $langs      =  !empty($request->input('langs'.$i)) ? $request->input('langs'.$i) : "";
                        $data = array($language,$langr,$langw,$langs); 
                        $result[] = $data;   
                    }
         
                   
                    $language = json_encode($result);
                    $children_name1 = json_encode($request->input('children_name'));
                    $children_dob1  = json_encode($request->input('children_dob'));
                    $edit_id = $sql[0]->id;
                    $update_personal  = employeepersonal::findOrFail($edit_id);
                    $input = array_merge($request->all(), ['language' => $language], ['children_name1' => $children_name1], ['children_dob1' => $children_dob1],['mother_tongue' => $request->input('monther_tongue')]);
                  
                    $update_personal->fill($input)->save();
                       // auditlog
              $this->auditlog($edit_id,"createemployee-personal","update",$_POST,"hr_emp_personal");
                    $outp_put['status']=1;
                    return $outp_put;
                } 
            }
            /** contact details save **/
            else if($form_name == "contact_details")
            {
                $sql = \DB::table('hr_emp_contact')->where('employee_id',$edit_id)->get();				
                if($sql->isEmpty())
              {               
               $data['employee_id'] = $edit_id;
                $data['permanent_street_address'] = $request->input('p_address');
                $data['permanent_street'] = $request->input('p_street');
                $data['permanent_flat_no'] = $request->input('p_flat_no');
                $data['permanent_street_address'] = $request->input('p_address');
               $data['permanent_country'] = $request->input('p_country');
               $data['permanent_state'] = $request->input('p_state');
               $data['permanent_city'] = $request->input('p_city');
               $data['permanent_postal_code'] = $request->input('p_pincode');
               $data['permanent_locality'] = $request->input('p_locality');
               $data['same_address'] = $request->input('same_address');
                $data['current_street_address'] = $request->input('c_address');
                $data['current_street'] = $request->input('c_street');
                $data['current_flat_no'] = $request->input('c_flat_no');
                $data['current_country'] = $request->input('c_country');
                $data['current_state'] = $request->input('c_state');
                $data['current_city'] = $request->input('c_city');
                $data['current_postal_code'] = $request->input('c_pincode');
                $data['current_locality'] = $request->input('c_locality');
                //$data = array();
                $result = array();
                
                for($i=0;  $i<count($request->input('emer_name')); $i++)
                {
                    $emer_name = $request->input('emer_name')[$i];
                    $relation   = $request->input('relation')[$i];
                    $address    = $request->input('address')[$i];
                    $mobile_no  = $request->input('mobile_no')[$i];
                    $data1 = array($emer_name,$relation,$address,$mobile_no); 
                   
                    $result[] = $data1;
                }
                $data['emergency_contacts'] = json_encode($result);
              
               $emp_contact = DB::table('hr_emp_contact')->insertGetId($data);
                       // auditlog
              $this->auditlog($emp_contact,"createemployee-contact","create",$_POST,"hr_emp_contact");
                $outp_put['status']=1;
                return $outp_put;
              }
              else
              {
                  	
                $data['permanent_street_address'] = $request->input('p_address');
                $data['permanent_street'] = $request->input('p_street');
                $data['permanent_flat_no'] = $request->input('p_flat_no');
                $data['permanent_country'] = $request->input('p_country');
                $data['permanent_state'] = $request->input('p_state');
                $data['permanent_city'] = $request->input('p_city');
                $data['permanent_postal_code'] = $request->input('p_pincode');
                $data['permanent_locality'] = $request->input('p_locality');
                $data['same_address'] = $request->input('same_address');
                $data['current_street_address'] = $request->input('c_address');
                $data['current_street'] = $request->input('c_street');
                $data['current_flat_no'] = $request->input('c_flat_no');
                $data['current_country'] = $request->input('c_country');
                $data['current_state'] = $request->input('c_state');
                $data['current_city'] = $request->input('c_city');
                $data['current_postal_code'] = $request->input('c_pincode');
                $data['current_locality'] = $request->input('c_locality');
                $result = array();
              
                for($i=0;  $i<count($request->input('emer_name')); $i++)
                {
                    $emer_name = $request->input('emer_name')[$i];
                    $relation   = $request->input('relation')[$i];
                    $address    = $request->input('address')[$i];
                    $mobile_no  = $request->input('mobile_no')[$i];
                    $data1 = array($emer_name,$relation,$address,$mobile_no); 
                   
                    $result[] = $data1;
                }
                $edit_id = $sql[0]->id;
                $out_put = (json_encode($result));
                $update_contact  = employeecontact::findOrFail($edit_id);	
                $input = array_merge($data, ['emergency_contacts' => $out_put]);
                $update_contact->fill($input)->save();
                $outp_put['status']=1;
                              // auditlog
              $this->auditlog($edit_id,"createemployee-contact","update",$_POST,"hr_emp_contact");
                return $outp_put;
                    
              }
               
            }
            /** salary details **/
            else if($form_name == "salary_details")
            {
			
                if( $edit_id != ""  && $row_id == "")
                {
                    
                    $data['pay_frequency'] = $request->input('pay_frequency');
                    $data['salary']      = $request->input('salary');
                    $data['bank_name']   = $request->input('bank_name');
                    $data['branch_name'] = $request->input('branch_name');
                    $data['ifsc_code']   = $request->input('ifsc_code');
                    $data['account_holder_name'] = $request->input('account_holder_name');
                    $data['account_type']        = $request->input('account_type');
                    $data['account_number']      = $request->input('account_number');
                    $data['employee_id']      = $edit_id;
                    
                    $bank_file = $request->file('bank_file');
                    if($bank_file != "")
                    {
                        $name = uniqid().'.'.$bank_file->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/file_uploads');
                        $bank_file->move($destinationPath, $name);
                        $data['files'] = $name;
                    }

                    $emp_salary = DB::table('hr_emp_salary')->insertGetId($data);
                  // auditlog
              $this->auditlog($emp_salary,"createemployee-salary","create",$_POST,"hr_emp_salary");
                    $outp_put['status']=1;
                    return $outp_put;
                }
                else
                {       
                   
                   $update_salary  = employeesalary::where('id',$row_id)->firstOrFail();
                   $input_data = array_merge($request->all(),['employee_id'=>$edit_id]);
                   
                    $bank_file = $request->file('bank_file');
                    if($bank_file != "")
                    {
                        $name = uniqid().'.'.$bank_file->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/file_uploads');
                        $bank_file->move($destinationPath, $name);
                        $input_data['files'] = $name;
                    }
               
                    $update_salary->fill($input_data)->save();
                    $outp_put['status']=1;
                        // auditlog
              $this->auditlog($row_id,"createemployee-salary","update",$_POST,"hr_emp_salary");
                    return $outp_put;
                }
            }
            /** education details */
            else if($form_name == "education_details")
            {
                
                if($edit_id != "" && $row_id == "")
                {
                    
                    $data['employee_id']    = $edit_id;
                    $data['education_level']  = $request->input('education_level');
                    $data['other']            = $request->input('other');
                    $data['institution_name'] = $request->input('institution_name');
                    $data['school_board']     = $request->input('school_board');
                    $data['school_name']     = $request->input('school_name');
                    $data['course']          = $request->input('course');
                    $data['from_date']       = $request->input('from_date');
                    $data['to_date']         = $request->input('to_date');
                    $data['percentage']      = $request->input('percentage');
                   
                   
                     if($request->hasfile('education_file'))
         {

            foreach($request->file('education_file') as $file)
            {

                $name=$file->getClientOriginalName();
                $file->move(public_path().'uploads/file_uploads', $name);  
                $files[] = $name;  
                
            }
           $data['files']=json_encode($files);

         }
      
                  
                    
                    $emp_salary = DB::table('hr_emp_education')->insertGetId($data);

                    $outp_put['status']=1;
                      // auditlog
              $this->auditlog($emp_salary,"createemployee-education","create",$_POST,"hr_emp_education");
                    return $outp_put;
                }
                else
                {
                    
                   
                    $emp_salary  = employeeeducation::where('id',$row_id)->firstOrFail();
                    $input_data = array_merge($request->all(),['employee_id'=>$edit_id]);
                    
                    $bank_file = $request->file('education_file');
                   
                    if($bank_file != "")
                    {
                          foreach($request->file('education_file') as $file)
            {
                $name=uniqid().'.'.$file->getClientOriginalName();
                $file->move(public_path().'/uploads/file_uploads', $name);  
                $files[] = $name;  
            }
                        $input_data['files'] =json_encode($files);
                    }
                   
                    $emp_salary->fill($input_data)->save();
                      // auditlog-insertGetId
              $this->auditlog($row_id,"createemployee-education","update",$_POST,"hr_emp_education");
                    $outp_put['status']=1;
                    return $outp_put;
                }
            }
            /** experience details **/
            else if($form_name == "experience_details")
            {
                if($edit_id != "" && $row_id == "")
                {
                    $data['employee_id']    = $edit_id;
                    $data['organization_name']       = $request->input('organization_name');
                    $data['organization_website']    = $request->input('organization_website');
                    $data['designation']             = $request->input('designation');
                    $data['ctc']                     = $request->input('ctc');
                    $data['from_date']               = $request->input('from_date');
                    $data['to_date']                 = $request->input('to_date');
                    $data['reason_leaving']          = $request->input('reason_leaving');
                    $data['experience_type']          = $request->input('experience_type');
                    
                    $bank_file = $request->file('exp_file');
                    if($bank_file != "")
                    {
                        $name = uniqid().'.'.$bank_file->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/file_uploads');
                        $bank_file->move($destinationPath, $name);
                        $data['files'] = $name;
                    }

                    $emp_experience = DB::table('hr_emp_experience')->insertGetId($data);

                    $outp_put['status']=1;
                    // auditlog
              $this->auditlog($emp_experience,"createemployee-experience","create",$_POST,"hr_emp_experience");
                    return $outp_put;
                }
                else
                {
                    
                    $emp_experience  = employeeexperience::where('id',$row_id)->firstOrFail();
                    $input_data = array_merge($request->all(),['employee_id'=>$edit_id]);
                    
                    $bank_file = $request->file('exp_file');
                   
                    if($bank_file != "")
                    {
                        $name = uniqid().'.'.$bank_file->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/file_uploads');
                        $bank_file->move($destinationPath, $name);
                        $input_data['files'] = $name;
                    }
                    
                    $emp_experience->fill($input_data)->save();
                    $outp_put['status']=1;
                    	    // auditlog
              $this->auditlog($row_id,"createemployee-experience","update",$_POST,"hr_emp_experience");
                    return $outp_put;
                    
                }
            }
		/** skill details **/	
            else if($form_name == "skill_details")
            {
              
                if($edit_id != "" && $row_id == "")
                {
                    
                    $data['employee_id']                = $edit_id;
                    $data['skill']                = $request->input('skill');
                    $data['version']              = $request->input('version');
                    $data['competency_level']     = $request->input('competency_level');
                    
                    $bank_file = $request->file('skl_file');
                    if($bank_file != "")
                    {
                        $name = uniqid().'.'.$bank_file->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/file_uploads');
                        $bank_file->move($destinationPath, $name);
                        $data['files'] = $name;
                    }
                    

                    $emp_experience = DB::table('hr_emp_skills')->insertGetId($data);
                    $outp_put['status']=1;
                              // auditlog
              $this->auditlog($emp_experience,"createemployee-skill","create",$_POST,"hr_emp_skills");
					return $outp_put;
                }
                else
                {
                   
                    $emp_experience  = employeeskill::where('id',$row_id)->firstOrFail();
                    $input_data = array_merge($request->all(),['employee_id'=>$edit_id]);
                
                    $bank_file = $request->file('skl_file');
                   
                    if($bank_file != "")
                    {
                        $name = uniqid().'.'.$bank_file->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/file_uploads');
                        $bank_file->move($destinationPath, $name);
                        $input_data['files'] = $name;
                    }
                   
                   
                    $emp_experience->fill($input_data)->save();
                      // auditlog
              $this->auditlog($row_id,"createemployee-skill","update",$_POST,"hr_emp_skills");
                        $outp_put['status']=1;
                        return $outp_put;
                }
            }
            /** trainig details **/
            else if($form_name == "training_details")
            {
                if($edit_id != "" && $row_id == "")
                {
                    $data['employee_id']                = $edit_id;
                    $data['course_name']                 = $request->input('course_name');
                    $data['certificate_name']            = $request->input('certificate_name');
                    $data['certificate_level']           = $request->input('certificate_level');
                    $data['course_offered_by']           = $request->input('course_offered_by');
                    $data['course_duration']             = $request->input('course_duration');
                    
                    $bank_file = $request->file('train_file');
                    if($bank_file != "")
                    {
                        $name = uniqid().'.'.$bank_file->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/file_uploads');
                        $bank_file->move($destinationPath, $name);
                        $data['files'] = $name;
                    }
                    
                    $emp_experience = DB::table('hr_emp_training_certification')->insertGetId($data);
                    $outp_put['status']=1;
                        // auditlog
              $this->auditlog($emp_experience,"createemployee-training","create",$_POST,"hr_emp_training_certification");
                    return $outp_put;
                }
                else
                {
                    $emp_experience  = employeetraining::where('id',$row_id)->firstOrFail(); 
                    $input_data = array_merge($request->all(),['employee_id'=>$edit_id]);
                    $bank_file = $request->file('train_file');
                   
                    if($bank_file != "")
                    {
                        $name = uniqid().'.'.$bank_file->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/file_uploads');
                        $bank_file->move($destinationPath, $name);
                        $input_data['files'] = $name;
                    }
                    
                    $emp_experience->fill($input_data)->save();
				 // auditlog
              $this->auditlog($row_id,"createemployee-training","update",$_POST,"hr_emp_training_certification");	
                    $outp_put['status']=1;
                    return $outp_put;
                }
            }
            /** visa details **/
            else if($form_name == "visa_details")
            {
				
                if($edit_id != "" && $row_id == "")
                {
                    $data['employee_id']                = $edit_id;
                    $data['passport_number']                 = $request->input('passport_number');
                    $data['passport_issued_date']            = $request->input('passport_issued_date');
                    $data['passport_expiry_date']           = $request->input('passport_expiry_date');
                    $data['visa_type_code']           = $request->input('visa_type_code');
                    $data['visa_number']             = $request->input('visa_number');
                    $data['visa_country']             = $request->input('visa_country');
                    $data['visa_issued_date']             = $request->input('visa_issued_date');
                    $data['visa_expiry_date']             = $request->input('visa_expiry_date');
                    
                    $bank_file = $request->file('visa_file');
                    if($bank_file != "")
                    {
                        $name = uniqid().'.'.$bank_file->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/file_uploads');
                        $bank_file->move($destinationPath, $name);
                        $data['files'] = $name;
                    }
                    
                    $emp_experience = DB::table('hr_emp_visa_immigration')->insertGetId($data);
// auditlog
              $this->auditlog($emp_experience,"createemployee-visa","create",$_POST,"hr_emp_visa_immigration");
                    $outp_put['status']=1;
                    return $outp_put;
                }
                else
                {  
                    $emp_experience  = employeevisaimmigration::where('id',$row_id)->firstOrFail();
                    $input_data = array_merge($request->all(),['employee_id'=>$edit_id]);
                    
                    
                    $bank_file = $request->file('visa_file');
                    if($bank_file != "")
                    {
                        $name = uniqid().'.'.$bank_file->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/file_uploads');
                        $bank_file->move($destinationPath, $name);
                        $input_data['files'] = $name;
                    }
                   
                    $emp_experience->fill($input_data)->save();
                    // auditlog
              $this->auditlog($row_id,"createemployee-visa","update",$_POST,"hr_emp_visa_immigration");
                    $outp_put['status']=1;
                    return $outp_put;
                }
                
            }

    }
/**** update profile data set start ***/
  
    public function edit(Employeecreate $employeecreate,$id=null,$tab_id=0)
    {
    
          $edit_id  =Session::get('emp_id'); 
          $emp_data   =   DB::table("hr_employee_t")->where('employee_id',$edit_id)->get();
          $dept=json_decode($emp_data[0]->department);
          $search_this = array
            (
            0 =>28,
            1=>15
            );
          $containsSearch = array_intersect($search_this, $dept);
           if (count($containsSearch)>0)
           {
             $this->data['save_button']=1;
           }else{
             $this->data['save_button']=0;
                }
        $this->data['tab_id'] = $tab_id;
        
        $edit_id = ($id == '') ? Session::get('emp_id') : $id ;
     
        $employee_official      = DB::table('hr_employee_t')->where('employee_id',$edit_id)->get();

        $employee_personal      = DB::table('hr_emp_personal')->where('employee_id',$edit_id)->get();
      
        if(count($employee_personal)==0)
        {
            $employee_personal      = Schema::getColumnListing('hr_emp_personal');
            foreach($employee_personal as $key=>$value)
            {
                $employeepersonal[0][$value] = '';
            }
            $employee_personal[0]= (object)$employeepersonal[0];
        }
       
        $employee_contact       = DB::table('hr_emp_contact')->where('employee_id',$edit_id)->get();
     
       
            
        if(count($employee_contact)==0)
        {
            
        $employee_contact       = Schema::getColumnListing('hr_emp_contact');
        
        foreach($employee_contact as $key=>$value)
        {
            $employeecontact[0][$value] = '';
        }
            $employee_contact[0]= (object)$employeecontact[0];
        }
		
        $employee_salary        = DB::table('hr_emp_salary')->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_emp_salary.account_type')->where('employee_id',$edit_id)->get();
        
        $employee_education     = DB::table('hr_emp_education')->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_emp_education.education_level')->where('employee_id',$edit_id)->get();
        $employee_experience    = DB::table('hr_emp_experience')->where('employee_id',$edit_id)->get();
		
        $employee_skills        = DB::table('hr_emp_skills')->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_emp_skills.competency_level')->where('employee_id',$edit_id)->get();
        $employee_training      = DB::table('hr_emp_training_certification')->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_emp_training_certification.certificate_level')->where('employee_id',$edit_id)->get();
        $employee_visa          = DB::table('hr_emp_visa_immigration')->leftjoin('m_countries_t','m_countries_t.country_id','=','hr_emp_visa_immigration.visa_country')->where('employee_id',$edit_id)->get();
        
        //dd($employee_personal);
        $this->data['employee_official']     = $employee_official;
        $this->data['employee_personal']     = $employee_personal;
        $this->data['employee_contact']      = $employee_contact;
        $this->data['employee_salary']       = $employee_salary;
        $this->data['employee_education']    = $employee_education;
        $this->data['employee_experience']   = $employee_experience;
        $this->data['employee_skill']        = $employee_skills;
        $this->data['employee_training']     = $employee_training;
        $this->data['employee_visa']         = $employee_visa;
        
        //dd($this->data['employee_contact']);
        //dd($employee_official[0]);
        $this->data['edit_id']               = $edit_id;
        $this->data['company']               = $this->jCombo('m_company_t','company_id','company_name',$employee_official[0]->company_id);
        $this->data['organization']          = $this->jCombo('m_organizations_t','organization_id','organization_name',$employee_official[0]->company_id);
        //$this->data['department']            = $this->jCombo('m_department_t','department_id','department_name',$employee_official[0]->department);
        //$this->data['department']            = $this->jCombo('m_department_lines_t','department_line_id','sub_department_name',$employee_official[0]->department);
       
        $dept = json_decode($employee_official[0]->department);
 
        if(count($dept)>0)
        {
            $dept = implode(',',$dept);
        }
        else{
            $dept = '';
        }
       
         $loc = json_decode($employee_official[0]->location_id);
        
        if(count($loc)>0)
        {
            $loc = implode(',',$loc);
        }
        else{
            $loc = '';
        }
       
        $area = json_decode($employee_official[0]->med_rep_area);
        if($area!=''){
        if(count($area)>0)
        {
            $area = implode(',',$area);
        }
        else{
            $area = '';
        }
        }
        $this->data['department']   		= $this->jcustommultiselect('m_department_lines_t','department_line_id','sub_department_name',$dept,'');
        $this->data['job_title']             = $this->jCombo('m_job_title','job_title_id','job_title_name',$employee_official[0]->job_title);
      $this->data['area']    		= $this->jcustommultiselect('m_area_t','area_id','area_name',$area,'');
        $this->data['employee_type']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_official[0]->employee_type,"  and lookup_type='EMPLOYEE_TYPE'");
        if(count($employee_salary)>0)
        $this->data['account_type']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_salary[0]->account_type,"  and lookup_type='ACCOUNT_TYPE'");
      else
        $this->data['account_type']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='ACCOUNT_TYPE'");
        if(count($employee_education)>0)
        $this->data['education_level']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_education[0]->education_level,"  and lookup_type='EDUCATION_LEVEL'");
      else
        $this->data['education_level']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='EDUCATION_LEVEL'");
       if(count($employee_skills)>0)
        $this->data['competency_level']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_skills[0]->competency_level,"  and lookup_type='COMPETENCY_LEVEL'");
      else
        $this->data['competency_level']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='COMPETENCY_LEVEL'");
        if(count($employee_training)>0)
        $this->data['certificate_level']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_training[0]->certificate_level,"  and lookup_type='CERTIFICATE_LEVEL'");
      else
        $this->data['certificate_level']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='CERTIFICATE_LEVEL'");
      if(count($employee_visa)>0)
        $this->data['visa_country']         = $this->jcombologin('m_countries_t','country_id','country_name',$employee_visa[0]->visa_country);
      else
        $this->data['visa_country']         = $this->jcombologin('m_countries_t','country_id','country_name','');
       if(count($employee_personal)>0){
        $this->data['nationality']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_personal[0]->nationality,"  and lookup_type='NATIONALITY'");
        $this->data['mother_tongue']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_personal[0]->mother_tongue,"  and lookup_type='MOTHER_TONGUE'");
        $this->data['religion']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_personal[0]->religion,"  and lookup_type='RELIGION'");
        $this->data['blood_group']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_personal[0]->blood_group,"  and lookup_type='BLOOD_GROUP'");
      
       }
      else{
       $this->data['nationality']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='NATIONALITY'");
        $this->data['mother_tongue']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='MOTHER_TONGUE'");
        $this->data['religion']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='RELIGION'");
        $this->data['blood_group']         = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',"  and lookup_type='BLOOD_GROUP'");
      }
     
      $this->data['position']              = $this->jCombo('m_position','position_id','position',$employee_official[0]->position);
        $this->data['group_type']            = $this->jcombologin("a_m_group_t","group_id","group_name",$employee_official[0]->group_type);
        $this->data['location']              = $this->jcustommultiselect('m_location_t','location_id','location_name',$loc,'');
        $this->data['reporting_manager']     = $this->jCombocomp('hr_employee_t','employee_id','employee_number|first_name',$employee_official[0]->reporting_manager);
         $this->data['reporting_manager1']     = $this->jCombocomp('hr_employee_t','employee_id','employee_number|first_name',$employee_official[0]->reporting_manager1);
       
        $this->data['p_city']                = $this->jCombologin('m_cities_t','city_id','city_name',$employee_contact[0]->permanent_city);
        $this->data['p_state']               = $this->jCombologin('m_states_t','state_id','state_name',$employee_contact[0]->permanent_state);
        $this->data['p_country']             = $this->jCombologin('m_countries_t','country_id','country_name',$employee_contact[0]->permanent_country);
        
        $this->data['c_city']                = $this->jCombologin('m_cities_t','city_id','city_name',$employee_contact[0]->current_city);
        $this->data['c_state']               = $this->jCombologin('m_states_t','state_id','state_name',$employee_contact[0]->current_state);
        $this->data['c_country']             = $this->jCombologin('m_countries_t','country_id','country_name',$employee_contact[0]->current_country);
        $this->data['idtype1']            = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_personal[0]->id_name,"  and lookup_type='idtype1'");
        $this->data['idtype2']            = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$employee_personal[0]->id_name1,"  and lookup_type='idtype1'");
        
        
        return view('employeecreate.form',$this->data);
        
        
    }

  
    /** mail duplicate check**/
    public function getCheckname (Request $request)
    {
       
        $edit_id = $_GET['edit_id'];
        if($edit_id == '')
            $mail=DB::table('hr_employee_t')->where('email',$_GET['mail_id'])->get();
        else
        {
            $whereData = [['email', $_GET['mail_id']],['employee_id', '!=', $edit_id]];
            
            $mail=DB::table('hr_employee_t')->where($whereData)->get();
        }
        
        
        if(count($mail)>0)
            return 1;
        else
            return 0;
        
        
    }
    
    /** mail duplicate check**/
    public function getmail (Request $request)
    {
       
        $edit_id = $_GET['edit_id'];
        if($edit_id == '')
            $mail=DB::table('hr_employee_t')->where('employee_number',$_GET['employee_id'])->get();
        else
        {
            $whereData = [['employee_number', $_GET['employee_id']],['employee_id', '!=', $edit_id]];
            
            $mail=DB::table('hr_employee_t')->where($whereData)->get();
        }
        
        
        if(count($mail)>0)
            return 1;
        else
            return 0;
        
        
    }
    /** edit data for all tabs**/
    public function fetchinformation(Request $request,$form_name,$edit_id)
    {
        
        $table_name = array("salary_form"=>"hr_emp_salary","education_form"=>"hr_emp_education","experience_form"=>"hr_emp_experience","skill_form"=>"hr_emp_skills","training_form"=>"hr_emp_training_certification","visa_form"=>"hr_emp_visa_immigration");
        $result = DB::table($table_name[$form_name])->where('id',$edit_id)->get();
     
        return $result;
    }
        /** delete data for all tabs**/
    public function deleteformation(Request $request,$form_name,$edit_id)
    {
        $table_name = array("salary_form"=>"hr_emp_salary","education_form"=>"hr_emp_education","experience_form"=>"hr_emp_experience","skill_form"=>"hr_emp_skills","training_form"=>"hr_emp_training_certification","visa_form"=>"hr_emp_visa_immigration");
        $result = DB::table($table_name[$form_name])->where('id',$edit_id)->delete();
        if($result)
            return 1;
        else
            return 0;
    }
    /** calculate age **/
      public function getage()
    {
          $data=[];
             $date=$_GET['date'];
             $from = new DateTime($date);
           $to   = new DateTime('today');
            $age=$from->diff($to)->y;
       
        $time=strtotime($date);
$month=date("F",$time);
$date=date("j",$time);
 $time1=strtotime( date('Y-m-d'));
$month1=date("F",$time1);
$date1=date("j",$time1);
if($month==$month1 && $date==$date1)
{
    $data[1]=1;
}else{
     $data[1]=0;
}
     $data[0]=$age;
return $data;
    }
    /** calculate experienc**/
     public function getexperience()
    {
    $datetime1 = new DateTime($_GET['from_date']);
$datetime2 = new DateTime($_GET['to_date']);
$interval = $datetime1->diff($datetime2);
return $interval->format('%y years %m months and %d days');
     }
     // add ten years for pass port
      public function passport()
    {
        $datetime = new DateTime($_GET['from_date']);
        $date= $datetime->modify('+10 years');
    
   return $date->format(\Session::get('p_date_format'));
    }
    

}