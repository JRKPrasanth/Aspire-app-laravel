<?php

namespace App\Http\Controllers;

use App\payreport;

use Session;
use DateTime,DB;
use Illuminate\Http\Request;

class PayreportController extends Controller
{
    public function __construct()
	{
            $this->data=array();
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	}
        
      public function employeedetailindex()
    {
    
       $company_id=\Session::get('companyid');
    return view('employeepayreport.employeedetailreport',$this->data);
    }
    
     public function hrmsallowancesettingsrpt()
    {
    
       $company_id=\Session::get('companyid');
    return view('employeepayreport.hrmsallowancesettingsrpt',$this->data);
    }
      public function getemployeedetail()
    {
         $wh='';
         
         /*Human Resources Marketing access control */
      $groupname=\Session::get('groupname');
         if($groupname == "16" || $groupname == "11" || $groupname == "13" || $groupname == "5"){
             $wh.=" AND (v2.emp_type='Marketing' OR v2.emp_type='Business Associate' OR v2.emp_type='Commission Based' OR v2.grp_type='Marketing Personnel')";
         }
            if(isset($_GET['pq_filter']))
                {
        $data=json_decode($_GET['pq_filter']);
        $data=$data->data;
         $wh.=$this->pqgridsearchsum('v2',$data);
        }
        //$table=array('hr_employee_t','hr_employee_payproposal','a_lookuplines_t','p_qc_lines_t','m_products_t','hr_employee_t');
         //       $data=$data->data;
       //  $wh.=$this->pqgridsearchsum('v1',$data);
      //  }
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;

 $result = \DB::select("SELECT * FROM(SELECT
    hr_employee_t.employee_number,
    hr_employee_t.first_name,
    hr_employee_t.last_name,
    (
        CASE WHEN hr_employee_t.prefix = 1 THEN 'Mr' WHEN hr_employee_t.prefix = 2 THEN 'Ms' ELSE 'Mrs'
    END
) AS prefix_name,
hr_employee_t.email,
m_company_t.company_name,
report.first_name AS report_name,
report1.first_name AS report_name1,
m_job_title.job_title_name,
m_position.position,
hr_employee_t.date_of_joining,
hr_employee_t.esi_no,
hr_employee_t.esi_dispensary,
hr_employee_t.pf_date,
hr_employee_t.pf_no,
hr_employee_t.uan_no,
hr_employee_t.work_telephone_number,
hr_employee_t.alternative_telephone_number,
hr_employee_t.biometric_empno,
hr_employee_t.c_l,
hr_employee_t.s_l,
hr_employee_t.e_l,
hr_employee_t.department,
hr_employee_t.location_id,
hr_employee_t.med_rep_area,
grouptype.group_name AS grp_type,
a_lookuplines_t.lookup_code AS emp_type,
(
    CASE WHEN hr_employee_t.ot_formula = 1 THEN 'OT Applicable' WHEN hr_employee_t.ot_formula = 2 THEN 'OT Not Applicable'
END
) AS ot,
hr_employee_t.active,
hr_employee_t.date_of_leaving,
(
    CASE WHEN hr_emp_personal.gender = 1 THEN 'Male' WHEN hr_emp_personal.gender = 2 THEN 'Female'
END
) AS gender_name,
(
    CASE WHEN hr_emp_personal.marital_status = 1 THEN 'Single' WHEN hr_emp_personal.marital_status = 2 THEN 'Married'
END
) AS marital_status_name,
nation.lookup_code AS nation_name,
hr_emp_personal.date_of_birth,
hr_emp_personal.age,
hr_emp_personal.language,
hr_emp_personal.personal_mail,
hr_emp_personal.personal_mobile,
hr_emp_personal.pan_number,
hr_emp_personal.aadhar_number,
hr_emp_personal.father_name,
hr_emp_personal.father_aadhar_number,
hr_emp_personal.mother_name,
hr_emp_personal.mother_aadhar_number,
hr_emp_personal.spouse_name,
hr_emp_personal.spouse_dob,
hr_emp_personal.spouce_aadhar_number,
hr_emp_personal.no_of_children,
mother.lookup_code AS mother_t_name,
religion.lookup_code AS religion_name,
blood.lookup_code AS blood_name,
id.lookup_code AS idtype,
hr_emp_personal.id_number,
id1.lookup_code AS idtype1,
hr_emp_personal.id_number1,
hr_emp_contact.permanent_street,
hr_emp_contact.permanent_street_address,
hr_emp_contact.permanent_flat_no,
hr_emp_contact.permanent_postal_code,
hr_emp_contact.permanent_locality,
hr_emp_contact.current_street,
hr_emp_contact.current_street_address,
hr_emp_contact.current_flat_no,
hr_emp_contact.current_postal_code,
hr_emp_contact.current_locality,
hr_emp_salary.bank_name,
hr_emp_salary.branch_name,
hr_emp_salary.ifsc_code,
hr_emp_salary.account_holder_name,
concat ('Bank-', hr_emp_salary.account_number) as acc_num,
m_countries_t.country_name,
m_states_t.state_name,
m_cities_t.city_name,
current_country.country_name AS current_count_name,
current_stat.state_name AS current_state_name,
current_city.city_name AS current_city_name
FROM
    hr_employee_t
LEFT JOIN m_company_t ON m_company_t.company_id = hr_employee_t.company_id
LEFT JOIN hr_employee_t AS report
ON
    report.employee_id = hr_employee_t.reporting_manager
LEFT JOIN hr_employee_t AS report1
ON
    report1.employee_id = hr_employee_t.reporting_manager1
LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_employee_t.job_title
LEFT JOIN m_position ON m_position.position_id = hr_employee_t.position
LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
LEFT JOIN a_m_group_t AS grouptype
ON
    grouptype.group_id = hr_employee_t.group_type
LEFT JOIN hr_emp_personal ON hr_emp_personal.employee_id = hr_employee_t.employee_id
LEFT JOIN a_lookuplines_t AS nation
ON
    nation.lookuplines_id = hr_emp_personal.nationality
LEFT JOIN a_lookuplines_t AS mother
ON
    mother.lookuplines_id = hr_emp_personal.mother_tongue
LEFT JOIN a_lookuplines_t AS religion
ON
    religion.lookuplines_id = hr_emp_personal.religion
LEFT JOIN a_lookuplines_t AS blood
ON
    blood.lookuplines_id = hr_emp_personal.blood_group
LEFT JOIN a_lookuplines_t AS id
ON
    id.lookuplines_id = hr_emp_personal.id_name
LEFT JOIN a_lookuplines_t AS id1
ON
    id1.lookuplines_id = hr_emp_personal.id_name1
LEFT JOIN hr_emp_contact ON hr_emp_contact.employee_id = hr_employee_t.employee_id
LEFT JOIN hr_emp_salary ON hr_emp_salary.employee_id = hr_employee_t.employee_id
LEFT JOIN m_countries_t ON m_countries_t.country_id = hr_emp_contact.permanent_country
LEFT JOIN m_states_t ON m_states_t.state_id = hr_emp_contact.permanent_state
LEFT JOIN m_cities_t ON m_cities_t.city_id = hr_emp_contact.permanent_city
LEFT JOIN m_countries_t AS current_country
ON
    current_country.country_id = hr_emp_contact.current_country
LEFT JOIN m_states_t AS current_stat
ON
    current_stat.state_id = hr_emp_contact.current_state
LEFT JOIN m_cities_t AS current_city
ON
    current_city.city_id = hr_emp_contact.current_city )v2 WHERE  1 = 1 $wh");
       
       $count = count($result);
       //dd($count);
     if( $count > 0 && $limit > 0)
    {
    $total_pages = ceil($count/$limit);
    } else {
    $total_pages = 0;
    }
    if ($page > $total_pages)
    $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;
         $loc=\Session::get('location');
          $compy=\Session::get('companyid');
         
        $query_result =\DB::select("select * FROM(SELECT
    hr_employee_t.employee_number,
    hr_employee_t.first_name,
    hr_employee_t.last_name,
    (
        CASE WHEN hr_employee_t.prefix = 1 THEN 'Mr' WHEN hr_employee_t.prefix = 2 THEN 'Ms' ELSE 'Mrs'
    END
) AS prefix_name,
hr_employee_t.email,
m_company_t.company_name,
report.first_name AS report_name,
report1.first_name AS report_name1,
m_job_title.job_title_name,
m_position.position,
hr_employee_t.date_of_joining,
hr_employee_t.esi_no,
hr_employee_t.esi_dispensary,
hr_employee_t.pf_date,
hr_employee_t.pf_no,
hr_employee_t.uan_no,
hr_employee_t.work_telephone_number,
hr_employee_t.alternative_telephone_number,
hr_employee_t.biometric_empno,
hr_employee_t.c_l,
hr_employee_t.s_l,
hr_employee_t.e_l,
hr_employee_t.department,
hr_employee_t.location_id,
hr_employee_t.med_rep_area,
grouptype.group_name AS grp_type,
a_lookuplines_t.lookup_code AS emp_type,
(
    CASE WHEN hr_employee_t.ot_formula = 1 THEN 'OT Applicable' WHEN hr_employee_t.ot_formula = 2 THEN 'OT Not Applicable'
END
) AS ot,
hr_employee_t.active,
hr_employee_t.date_of_leaving,
(
    CASE WHEN hr_emp_personal.gender = 1 THEN 'Male' WHEN hr_emp_personal.gender = 2 THEN 'Female'
END
) AS gender_name,
(
    CASE WHEN hr_emp_personal.marital_status = 1 THEN 'Single' WHEN hr_emp_personal.marital_status = 2 THEN 'Married'
END
) AS marital_status_name,
nation.lookup_code AS nation_name,
hr_emp_personal.date_of_birth,
hr_emp_personal.age,
hr_emp_personal.language,
hr_emp_personal.personal_mail,
hr_emp_personal.personal_mobile,
hr_emp_personal.pan_number,
hr_emp_personal.aadhar_number,
hr_emp_personal.father_name,
hr_emp_personal.father_aadhar_number,
hr_emp_personal.mother_name,
hr_emp_personal.mother_aadhar_number,
hr_emp_personal.spouse_name,
hr_emp_personal.spouse_dob,
hr_emp_personal.spouce_aadhar_number,
hr_emp_personal.no_of_children,
mother.lookup_code AS mother_t_name,
religion.lookup_code AS religion_name,
blood.lookup_code AS blood_name,
id.lookup_code AS idtype,
hr_emp_personal.id_number,
id1.lookup_code AS idtype1,
hr_emp_personal.id_number1,
hr_emp_contact.permanent_street,
hr_emp_contact.permanent_street_address,
hr_emp_contact.permanent_flat_no,
hr_emp_contact.permanent_postal_code,
hr_emp_contact.permanent_locality,
hr_emp_contact.current_street,
hr_emp_contact.current_street_address,
hr_emp_contact.current_flat_no,
hr_emp_contact.current_postal_code,
hr_emp_contact.current_locality,
hr_emp_salary.bank_name,
hr_emp_salary.branch_name,
hr_emp_salary.ifsc_code,
hr_emp_salary.account_holder_name,
concat ('Bank-',hr_emp_salary.account_number) as acc_num,
m_countries_t.country_name,
m_states_t.state_name,
m_cities_t.city_name,
current_country.country_name AS current_count_name,
current_stat.state_name AS current_state_name,
current_city.city_name AS current_city_name
FROM
    hr_employee_t
LEFT JOIN m_company_t ON m_company_t.company_id = hr_employee_t.company_id
LEFT JOIN hr_employee_t AS report
ON
    report.employee_id = hr_employee_t.reporting_manager
LEFT JOIN hr_employee_t AS report1
ON
    report1.employee_id = hr_employee_t.reporting_manager1
LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_employee_t.job_title
LEFT JOIN m_position ON m_position.position_id = hr_employee_t.position
LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
LEFT JOIN a_m_group_t AS grouptype
ON
    grouptype.group_id = hr_employee_t.group_type
LEFT JOIN hr_emp_personal ON hr_emp_personal.employee_id = hr_employee_t.employee_id
LEFT JOIN a_lookuplines_t AS nation
ON
    nation.lookuplines_id = hr_emp_personal.nationality
LEFT JOIN a_lookuplines_t AS mother
ON
    mother.lookuplines_id = hr_emp_personal.mother_tongue
LEFT JOIN a_lookuplines_t AS religion
ON
    religion.lookuplines_id = hr_emp_personal.religion
LEFT JOIN a_lookuplines_t AS blood
ON
    blood.lookuplines_id = hr_emp_personal.blood_group
LEFT JOIN a_lookuplines_t AS id
ON
    id.lookuplines_id = hr_emp_personal.id_name
LEFT JOIN a_lookuplines_t AS id1
ON
    id1.lookuplines_id = hr_emp_personal.id_name1
LEFT JOIN hr_emp_contact ON hr_emp_contact.employee_id = hr_employee_t.employee_id
LEFT JOIN hr_emp_salary ON hr_emp_salary.employee_id = hr_employee_t.employee_id
LEFT JOIN m_countries_t ON m_countries_t.country_id = hr_emp_contact.permanent_country
LEFT JOIN m_states_t ON m_states_t.state_id = hr_emp_contact.permanent_state
LEFT JOIN m_cities_t ON m_cities_t.city_id = hr_emp_contact.permanent_city
LEFT JOIN m_countries_t AS current_country
ON
    current_country.country_id = hr_emp_contact.current_country
LEFT JOIN m_states_t AS current_stat
ON
    current_stat.state_id = hr_emp_contact.current_state
LEFT JOIN m_cities_t AS current_city
ON
    current_city.city_id = hr_emp_contact.current_city )v2 WHERE  1 = 1 $wh");
              // dd($query_result);


 if(count($query_result)>0){
         foreach($query_result  as $k=>$v  ){
             if($v->department!='' && $v->department!=null){
                    $array=json_decode($v->department);
                    $query = \DB::table('m_department_lines_t')->whereIn('department_line_id',$array)->get();
                    $deptnames = json_decode(json_encode($query), true);    
                    $query_result[$k]->department_name=implode(' , ',array_column($deptnames,'sub_department_name'));    
                     }
             else{
                 $query_result[$k]->department_name="";
                }

    if($v->location_id!='' && $v->location_id!=null){
            $array=json_decode($v->location_id);
       $query = \DB::table('m_location_t')->whereIn('location_id',$array)->get();
                    $deptnames = json_decode(json_encode($query), true);    
                    $query_result[$k]->location_name=implode(' , ',array_column($deptnames,'location_name')); 
    }  else{
                 $query_result[$k]->location_name="";
                }
             
              if($v->med_rep_area!='' && $v->med_rep_area!=null){
            $array=json_decode($v->med_rep_area);
          $query = \DB::table('m_area_t')->whereIn('area_id',$array)->get();
          $deptnames = json_decode(json_encode($query), true);    
                    $query_result[$k]->area_name=implode(' , ',array_column($deptnames,'area_name')); 
             }  else{
                 $query_result[$k]->area_name="";
                }
              
        }
        } 

    if(isset($_GET['download']))
    {
    	//dd($query_result);
    $result1 = $query_result;
    $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }
       
        $result = $query_result;
        $responce->rows[]='';
        $responce->data=$result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
        // dd($responce);
        
    }  
    
    public function gethrmsallowancesettingsrpt()
    {
         $wh='';
         
         /*Human Resources Marketing access control */
/*      $groupname=\Session::get('groupname');
         if($groupname == "Human Resources Marketing" || $groupname == "Logistics Stores" || $groupname == "General" || $groupname == "Sales Support"){
             $wh.=" AND (v2.emp_type='Marketing' OR v2.emp_type='Business Associate' OR v2.emp_type='Commission Based' OR v2.grp_type='Marketing Personnel')";
         }*/
            if(isset($_GET['pq_filter']))
                {
        $data=json_decode($_GET['pq_filter']);
        $data=$data->data;
         $wh.=$this->pqgridsearchsum('v2',$data);
        }
        //$table=array('hr_employee_t','hr_employee_payproposal','a_lookuplines_t','p_qc_lines_t','m_products_t','hr_employee_t');
         //       $data=$data->data;
       //  $wh.=$this->pqgridsearchsum('v1',$data);
      //  }
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;

 $result = \DB::select("SELECT * FROM(SELECT
    f_hr_account_allowance_setting_t.account_allowance_setting_id,
    m_department_lines_t.sub_department_name,
    m_allowance_tbl.allowance_name,
    m_allowance_tbl.type,
    a_lookuplines_t.lookup_code,
    f_hr_account_allowance_setting_lines_t.account_structure_id,
    f_account_structure_t.concatenated_segments
FROM
    f_hr_account_allowance_setting_t
LEFT JOIN f_hr_account_allowance_setting_lines_t ON f_hr_account_allowance_setting_t.account_allowance_setting_id = f_hr_account_allowance_setting_lines_t.account_allowance_setting_id
LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = f_hr_account_allowance_setting_t.department_id
LEFT JOIN m_allowance_tbl ON m_allowance_tbl.allowance_id = f_hr_account_allowance_setting_lines_t.allowance_id
LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id =
f_hr_account_allowance_setting_lines_t.account_structure_id
LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = 
m_allowance_tbl.employee_type )v2 WHERE  1 = 1 $wh");
       
       $count = count($result);
       //dd($count);
     if( $count > 0 && $limit > 0)
    {
    $total_pages = ceil($count/$limit);
    } else {
    $total_pages = 0;
    }
    if ($page > $total_pages)
    $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;
         $loc=\Session::get('location');
          $compy=\Session::get('companyid');
         
        $query_result =\DB::select("select * FROM(SELECT
    f_hr_account_allowance_setting_t.account_allowance_setting_id,
    m_department_lines_t.sub_department_name,
    m_allowance_tbl.allowance_name,
    m_allowance_tbl.type,
    a_lookuplines_t.lookup_code,
    f_hr_account_allowance_setting_lines_t.account_structure_id,
    f_account_structure_t.concatenated_segments
FROM
    f_hr_account_allowance_setting_t
LEFT JOIN f_hr_account_allowance_setting_lines_t ON f_hr_account_allowance_setting_t.account_allowance_setting_id = f_hr_account_allowance_setting_lines_t.account_allowance_setting_id
LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = f_hr_account_allowance_setting_t.department_id
LEFT JOIN m_allowance_tbl ON m_allowance_tbl.allowance_id = f_hr_account_allowance_setting_lines_t.allowance_id
LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id =
f_hr_account_allowance_setting_lines_t.account_structure_id
LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = 
m_allowance_tbl.employee_type )v2 WHERE  1 = 1 $wh");
              // dd($query_result);



    if(isset($_GET['download']))
    {
    	//dd($query_result);
    $result1 = $query_result;
    $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }
       
        $result = $query_result;
        $responce->rows[]='';
        $responce->data=$result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
        // dd($responce);
        
    } 
    
        
        // attendance report function
    public function attendancereport_index()
    {
		
       $company_id = Session::get('companyid');
		$wh='';
        $wh .= " and hr_emp_attendence.company_id=' $company_id' ";
        $log_id=Session::get('emp_id');
         if($log_id!=1 && $log_id != 315 && $log_id != 152){
            $data=\DB::SELECT("select employee_number from hr_employee_t where employee_id='".$log_id."'");
	 $wh .= " and hr_emp_attendence.emp_id='".$data[0]->employee_number."'";
    }
         
        $SQL = "SELECT
        hr_emp_attendence.atten_id,
        hr_emp_attendence.emp_id,
        hr_employee_t.biometric_empno as bio_id,
        hr_emp_attendence.atten_date,
        hr_emp_attendence.check_in,
        hr_emp_attendence.check_out,
        hr_emp_attendence.working_hours,
        timediff(hr_emp_attendence.check_out,hr_emp_attendence.check_in) as wrk_hrs,
        hr_emp_attendence.ot,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as first_name,
       
      a_lookuplines_t.lookup_code
            FROM
        hr_emp_attendence
            LEFT JOIN hr_employee_t ON hr_employee_t.employee_number = hr_emp_attendence.emp_id
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_emp_attendence.atten_type
        where 1=1 $wh ";
	$this->data['result'] = json_encode(\DB::select($SQL));
        return view('payproposal.attenreporttable',$this->data);
    }
    
    public function getattendancereportdata()
    {
        $company_id = Session::get('companyid');
		$wh='';
        //$wh .= " and hr_emp_attendence.company_id=' $company_id' ";
        $log_id=Session::get('emp_id');
         if($log_id!=1 && $log_id != 315 && $log_id != 302 && $log_id != 152){
            $data=\DB::SELECT("select employee_number from hr_employee_t where employee_id='".$log_id."'");
	 $wh .= " and v1.emp_id='".$data[0]->employee_number."'";
    }
        
        $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?  date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
        
        
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
    $result = \DB::select("select*,time_format(subtime(subtime(wrk_hrs,early_in),late_out),'%H:%i') as off_hrs,time_format(subtime(wrk_hrs,subtime(subtime(wrk_hrs,early_in),late_out)),'%H:%i') as ovr_time from(SELECT
        hr_emp_attendence.atten_id,
        hr_emp_attendence.emp_id,
        hr_employee_t.biometric_empno as bio_id,
        hr_emp_attendence.atten_date,
        time_format(hr_emp_attendence.check_in,'%H:%i')as check_in,
        time_format(hr_emp_attendence.check_out,'%H:%i')as check_out,
        hr_emp_attendence.working_hours,
        time_format(timediff(hr_emp_attendence.check_out,hr_emp_attendence.check_in),'%H:%i') as wrk_hrs,
        time_format(if ((time_to_sec(timediff('09:30',time_format(hr_emp_attendence.check_in,'%H:%i')))/3600)<0,time_format(time(0),'%H:%i'),timediff('09:30',time_format(hr_emp_attendence.check_in,'%H:%i'))),'%H:%i') as early_in,
        time_format(if((time_to_sec(timediff(time_format(hr_emp_attendence.check_in,'%H:%i'),'09:30'))/3600)<0,time(0),timediff(time_format(hr_emp_attendence.check_in,'%H:%i'),'09:30')),'%H:%i') as late_in,
        time_format(if((time_to_sec(timediff('18:00',time_format(hr_emp_attendence.check_out,'%H:%i')))/3600)<0,time(0),timediff('18:00',time_format(hr_emp_attendence.check_out,'%H:%i'))),'%H:%i') as early_out,
        time_format(if((time_to_sec(timediff(time_format(hr_emp_attendence.check_out,'%H:%i'),'18:00'))/3600)<0,time(0),timediff(time_format(hr_emp_attendence.check_out,'%H:%i'),'18:00')),'%H:%i') as late_out,
        hr_emp_attendence.ot,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as first_name,
       
      a_lookuplines_t.lookup_code
            FROM
        hr_emp_attendence
            LEFT JOIN hr_employee_t ON hr_employee_t.employee_number = hr_emp_attendence.emp_id
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_emp_attendence.atten_type
        where 1=1)v1 where 1=1 $wh and v1.atten_date BETWEEN  '$start_date' and '$end_date'");

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
    
    $SQL = "select*,time_format(subtime(subtime(wrk_hrs,early_in),late_out),'%H:%i') as off_hrs,time_format(subtime(wrk_hrs,subtime(subtime(wrk_hrs,early_in),late_out)),'%H:%i') as ovr_time from(SELECT
        hr_emp_attendence.atten_id,
        hr_emp_attendence.emp_id,
        hr_employee_t.biometric_empno as bio_id,
        hr_emp_attendence.atten_date,
        time_format(hr_emp_attendence.check_in,'%H:%i')as check_in,
        time_format(hr_emp_attendence.check_out,'%H:%i')as check_out,
        hr_emp_attendence.working_hours,
        time_format(timediff(hr_emp_attendence.check_out,hr_emp_attendence.check_in),'%H:%i') as wrk_hrs,
        time_format(if ((time_to_sec(timediff('09:30',time_format(hr_emp_attendence.check_in,'%H:%i')))/3600)<0,time_format(time(0),'%H:%i'),timediff('09:30',time_format(hr_emp_attendence.check_in,'%H:%i'))),'%H:%i') as early_in,
        time_format(if((time_to_sec(timediff(time_format(hr_emp_attendence.check_in,'%H:%i'),'09:30'))/3600)<0,time(0),timediff(time_format(hr_emp_attendence.check_in,'%H:%i'),'09:30')),'%H:%i') as late_in,
        time_format(if((time_to_sec(timediff('18:00',time_format(hr_emp_attendence.check_out,'%H:%i')))/3600)<0,time(0),timediff('18:00',time_format(hr_emp_attendence.check_out,'%H:%i'))),'%H:%i') as early_out,
        time_format(if((time_to_sec(timediff(time_format(hr_emp_attendence.check_out,'%H:%i'),'18:00'))/3600)<0,time(0),timediff(time_format(hr_emp_attendence.check_out,'%H:%i'),'18:00')),'%H:%i') as late_out,
        hr_emp_attendence.ot,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as first_name,
       
      a_lookuplines_t.lookup_code
            FROM
        hr_emp_attendence
            LEFT JOIN hr_employee_t ON hr_employee_t.employee_number = hr_emp_attendence.emp_id
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_emp_attendence.atten_type
        where 1=1)v1 where 1=1 $wh and v1.atten_date BETWEEN  '$start_date' and '$end_date' ORDER BY $sidx $sord LIMIT $start,$limit "; 


      $download_SQL = "select*,time_format(subtime(subtime(wrk_hrs,early_in),late_out),'%H:%i') as off_hrs,time_format(subtime(wrk_hrs,subtime(subtime(wrk_hrs,early_in),late_out)),'%H:%i') as ovr_time from(SELECT
        hr_emp_attendence.atten_id,
        hr_emp_attendence.emp_id,
        hr_employee_t.biometric_empno as bio_id,
        hr_emp_attendence.atten_date,
        time_format(hr_emp_attendence.check_in,'%H:%i')as check_in,
        time_format(hr_emp_attendence.check_out,'%H:%i')as check_out,
        hr_emp_attendence.working_hours,
        time_format(timediff(hr_emp_attendence.check_out,hr_emp_attendence.check_in),'%H:%i') as wrk_hrs,
        time_format(if ((time_to_sec(timediff('09:30',time_format(hr_emp_attendence.check_in,'%H:%i')))/3600)<0,time_format(time(0),'%H:%i'),timediff('09:30',time_format(hr_emp_attendence.check_in,'%H:%i'))),'%H:%i') as early_in,
        time_format(if((time_to_sec(timediff(time_format(hr_emp_attendence.check_in,'%H:%i'),'09:30'))/3600)<0,time(0),timediff(time_format(hr_emp_attendence.check_in,'%H:%i'),'09:30')),'%H:%i') as late_in,
        time_format(if((time_to_sec(timediff('18:00',time_format(hr_emp_attendence.check_out,'%H:%i')))/3600)<0,time(0),timediff('18:00',time_format(hr_emp_attendence.check_out,'%H:%i'))),'%H:%i') as early_out,
        time_format(if((time_to_sec(timediff(time_format(hr_emp_attendence.check_out,'%H:%i'),'18:00'))/3600)<0,time(0),timediff(time_format(hr_emp_attendence.check_out,'%H:%i'),'18:00')),'%H:%i') as late_out,
        hr_emp_attendence.ot,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as first_name,
       
      a_lookuplines_t.lookup_code
            FROM
        hr_emp_attendence
            LEFT JOIN hr_employee_t ON hr_employee_t.employee_number = hr_emp_attendence.emp_id
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_emp_attendence.atten_type
        where 1=1)v1 where 1=1 $wh and v1.atten_date BETWEEN  '$start_date' and '$end_date' ORDER BY $sidx $sord "; 

                         $query_result = \DB::select($download_SQL);

        $result1=collect($query_result)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }


        
            $result = \DB::select($SQL);

            $responce->rows[]='';
            $responce->rows=$result;
            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            echo json_encode($responce);
    
    }
    
     // index for monthatten
         public function attenindex()
         {

            $this->data['emp_id']=$this->jCombo('hr_employee_t','employee_id','employee_number|first_name',\Session::get('emp_id'));
	 return view('payproposal.monthattenform',$this->data);
             
             
         } 
	//calender
	public function calendar()
	{
	
        $this->data['emp_id']=$this->jCombo('hr_employee_t','employee_id','employee_number|first_name',\Session::get('emp_id'));
		//$sql="select * from  hr_emp_attendence";
		return view('payproposal.calendar',$this->data);
	}
        // calendar json data
    function jsondata(Request $request) 
    {
        $emp_id =   $request->input('filter');
           $query2 = \DB::select("select * from hr_employee_t where employee_id='$emp_id'");
             $emp_id = $query2[0]->employee_number; 
        $month  =   $request->input('month');
        $year   =   $request->input('year');
        
        $start_date=date($year.'-'.$month.'-01');
		
        $month1=date('m');
        $d=cal_days_in_month(CAL_GREGORIAN,$month,$year); 
        
        if($month==$month1)
        {
            $end_date=date($year.'-'.$month.'-d');
        }
        else
        {
            $d=cal_days_in_month(CAL_GREGORIAN,$month,$year);  
            $end_date=date($year.'-'.$month.'-'.$d);
        }
          
        $begin              =   new DateTime($start_date);
        $month_name         =   $begin->format('F').' '.$year;
        $second_saturday    =   date('Y-m-d', strtotime('second saturday of '.$month_name));
        $end                =   new DateTime($end_date);
        $holidays=\DB::select("SELECT * FROM `hr_holiday_t` WHERE  year(date)='$year'");  
        $total_days = $d;
        $present_days = 0;
        $absent_days = 0;
        $sundays = 0;
        $holidayss = 0;
        $data=array();
 
        while ($begin <= $end)
        {
                $color="black";
                $val="";
                if($holidays)
                {
                    foreach ($holidays as $holiday)
                    {
                        $date=$begin->format('Y-m-d');
                        if($date == $holiday->date)
                        {
                            $data[] = array(
                            'id' =>$emp_id,
                            'title' => strtoupper($holiday->holiday_name),
                            'start' => $holiday->date,
                            'employee_id' => $emp_id,
                            'textColor' => "#ffffff",
                            'bordercolor' => "#ffc107",
                            'color' => "#ffc107");
                        }
                    }
                }
                
                if($val=="")
                {
                    if($begin->format("D") == "Sun")
                    {
                        if($val=="")
                        {
                            $class="sunday";
                            $val="<span class='sunday'>SUNDAY</span>";
                            $sundays++;
                            $present_days++;
                              $data[] = array(
                        'id' =>$emp_id,
                        'title' => strtoupper($class),
                        'start' => $date,
                        'employee_id' => $emp_id,
                        'textColor' => "#ffffff",
                        'bordercolor' => "#ffc107",
                        'color' => "#ffc107" 
                      );
                        }
                    }
                } 
                $date=(string)$begin->format("Y-m-d");
                
                
                $atten_detail=\DB::select("SELECT atten_date,`check_in`,`check_out`,`working_hours` FROM `hr_emp_attendence` WHERE `emp_id`='$emp_id' AND `atten_date`like'$date%'");
             
                $employee_id = $query2[0]->employee_id;       

                if($atten_detail)
                {
                    $atten_date=$atten_detail[0]->atten_date;
                    $check_in=$atten_detail[0]->check_in;
                    $check_out=$atten_detail[0]->check_out;
                   
                    $data[] = array(
                        'id' =>$emp_id,
                        'title' => 'CHECK-IN-'.$check_in,
                        'start' => $atten_date,
                        'employee_id' => $emp_id,
                        'textColor' => "#ffffff",
                        'bordercolor' => "#03a9f4",
                        'color' => "#8BC34A"
                        );
                
                    $data[] = array(
                        'id' =>$emp_id,
                        'title' => 'CHECK-OUT-'.$check_out,
                        'start' => $atten_date,
                        'employee_id' => $emp_id,
                        'textColor' => "#ffffff",
                        'bordercolor' => "#03a9f4",
                        'color' => "#03a9f4"
                        );
                }
                else 
                {
                    $od_detail=\DB::select("SELECT * FROM hr_leaves_t WHERE employee_id='$employee_id' AND DATE(start_date)='$date' AND DATE(end_date)='$date' AND leave_type='1959' AND leave_status='APPROVED' ");
                    if(count($od_detail)>0)
                    {
                            $od_hr =$od_detail[0]->end_date;
                    $data[] = array(
                                'id' =>$emp_id,
                                'title' => 'PRESENT-ON DUTY('.$od_hr.')',
                                'start' => $date,
                                'employee_id' => $emp_id,
                                'textColor' => "#ffffff",
                                'bordercolor' => "#03a9f4",
                            'color' => "#8BC34A"
                            );
                    }
                    else
                    {
                        if($val=="")
                        {
                            if($second_saturday!=$begin->format("Y-m-d"))
                            {
                            $data[] = array(
                                'id' =>$emp_id,
                                'title' => 'LEAVE',
                                'start' => $date,
                                'employee_id' => $emp_id,
                                'textColor' => "#ffffff",
                                'bordercolor' => "#F44336",
                                'color' => "#800080"
                             );
                            }
                        }
                        }
                }
                $begin->modify('+1 day');
        } 
      
        return json_encode($data);
    }
	//chart
	public function attendancecharts($month=null,$emp_code = null,$year=null) 
	{
		 if(!$month )
        {
            $month=date("m"); 
        }
        	 if(!$year )
        {
            $year= date("Y");
        }
        if(!$emp_code)
        {
            $emp_id = \Session::get('emp_id'); 
            $emp_code_query=\DB::SELECT("select employee_number from hr_employee_t where employee_id='$emp_id' ");
            $emp_code=$emp_code_query[0]->employee_number;
            	
        $this->data['emp_id']=$this->jCombo('hr_employee_t','employee_id','employee_number|first_name',$emp_id);
        }else{
            	
   
            $emp_id =$emp_code; 
            $emp_code_query=\DB::SELECT("select employee_number from hr_employee_t where employee_id='$emp_id' ");
            $emp_code=$emp_code_query[0]->employee_number;
            $this->data['emp_id']=$this->jCombo('hr_employee_t','employee_id','employee_number|first_name',$emp_id);
        }
        $start_date = date($year ."-". $month . '-01');
        $month1 = date('m');
        if ($month == $month1)
            $end_date = date($year ."-". $month . '-d', strtotime("-1 days"));
        else
            $end_date = date($year ."-". $month . '-t');
        
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $date = (string) $begin->format("Y-m-d");

        $x = 1;
        $dates = [];
        $working_hours = [];
        $check_in = [];
        $check_out = [];
        $in_out = [];
        $test_arr = [];
        while ($begin <= $end) 
        {
            $date = (string) $begin->format("Y-m-d");
            $atten_detail = \DB::select("SELECT `check_in`,`check_out`,`working_hours`,`atten_date` FROM `hr_emp_attendence` WHERE `emp_id`='$emp_code' AND DATE(`atten_date`)='$date' ");
            if ($atten_detail) 
            {
                $dates[] = $atten_detail[0]->atten_date;
                $working_hours[] = floatval(date('H.i', $atten_detail[0]->working_hours));
                $temp_check_in = strtotime($atten_detail[0]->check_in);
                $check_in[]=  floatval(date('H.i',$temp_check_in));
                $temp_check_out = strtotime($atten_detail[0]->check_out);
                $check_out[]=  floatval(date('H.i',$temp_check_out));
                $time = new DateTime($atten_detail[0]->atten_date);
                $date_attend = $time->format('Y-m-d');
                $time = $time->format('H:i');
                $test_arr[]=[$date_attend,floatval(date('H.i', $atten_detail[0]->working_hours))];
                $in_out[]=[floatval(date('H.i',$temp_check_in)),floatval(date('H.i',$temp_check_out))];
                
            } 
            else 
            {
                if ($begin->format("D") == "Sun") 
                {
                    $working_hours[] =  floatval(date('H.i', 0000));
                    $check_in[]=floatval(date('H.i',000));
                    $check_out[]=floatval(date('H.i',000));
                    $sunday_date = (string) $begin->format("Y-m-d");
                    $dates[] = $sunday_date;
                    $test_arr[]=[$sunday_date."<br>(SUNDAY)",000];
                    $in_out[]=[floatval(date('H.i',000)),floatval(date('H.i',000))];
                    
                } 
                else 
                {
                   
                    $working_hours[] =  floatval(date('H.i', 0000));
                    $check_in[]=floatval(date('H.i',000));
                    $check_out[]=floatval(date('H.i',000));
                    $leave_date = (string) $begin->format("Y-m-d");
                    $dates[] = $leave_date;
                    $test_arr[]=[$leave_date."<br>(LEAVE)",0];
                    $in_out[]=[floatval(date('H.i',000)),floatval(date('H.i',000))];
                    $every_date = (string) $begin->format('Y-m-d');
                    $holidays=\DB::select("SELECT * FROM hr_holiday_t WHERE date='$every_date' "); 
                }
            }
            $begin->modify('+1 day');

            $x++;
        }
        
        $working_hours = str_replace('"', '', json_encode($working_hours));
        $work_hour = str_replace('"', "'" , json_encode($test_arr));        
        $in_out = str_replace('"', "'" , json_encode($in_out));
        
        $emp_name = \DB::select("select first_name,employee_number from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $emp_name[0]->employee_number." ".$emp_name[0]->first_name;
        
        $this->data['categories'] = json_encode($dates);
        $this->data['data'] = $working_hours;
        $this->data['points_header'] = "POINTS TABLE";
        $this->data['emp_name'] = $emp_name;
        $this->data['check_in'] = json_encode($check_in);
        $this->data['check_out'] = json_encode($check_out);
        $this->data['work_hours']=$work_hour;    
        $this->data['in_out'] = $in_out;
	
	return view('payproposal.chart',$this->data);	
		
		
		
	}
         // get report data
           public function reportmonthatten($emp_id = null , $month = null,$year = null)
        {
               
            //echo $emp_id."--".$month."--".$year; exit;
           $current_year= $year = $year;
            $month = sprintf('%02d',$month);


            $start_date=date($year.'-'.$month.'-01');
            $end_date=date("Y-m-t", strtotime($start_date));
            
           $query2 = \DB::select("select * from hr_employee_t where employee_id='$emp_id'");
                $employee_id = $query2[0]->employee_id;
                $employee_number = $query2[0]->employee_number;
                $compid = $query2[0]->company_id;
            $month1=date('m');
            $my_file = uniqid().'.xls';
            $file = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
           // get cutoff date for the year and company
        $cutoff_date    = DB::table('hr_emp_payroll_cutoff')->where('company',$compid)->whereYear('cutoff_date',$year)->get();
        
        if(count($cutoff_date)==0)
        {
              
           
             // set date start and end as correct format
            $begin      = new DateTime($start_date);
            
            $end        = new DateTime($end_date);
           
           // dd("test");
            $data   ="<table class='table .table-hover'>"
                    . "<th width='30'>  Date </th>"
                    . "<th width='30'>  Check-In </th>"
                    . "<th width='30'>  Check-Out </th>"
                    . "<th width='30'>  Day </th>";
          $columnHeader = "Date"."\t"."Check-In"."\t"."Check-Out"."\t"."Day"."\t";
         
             
            $cl = $query2[0]->c_l;
            $sl = $query2[0]->s_l;
            $el = $query2[0]->e_l;
            $loc_id=json_decode($query2[0]->location_id);
            $locationid   	    =$loc_id[0] ;           
            $total_days = 0;
            $present_days = 0;
            $absent_days = 0;
            $sundays = 0;
            $holidayss = 0;
            $weekoffs = 0;
            $c_l= 0;
            $s_l = 0;
            $e_l = 0;
            $od = 0;
            $rowData='';
            $setData='';
            $value='';
            //dd($cl);
            while ($begin <= $end)
            {
                
               $date=(string)$begin->format("Y-m-d");
               
            $hol_month_week=0;
                                 // holidaye based on location
                     $holidays=\DB::select("SELECT location,date,holiday_name FROM `hr_holiday_t` where date='$date' and company_id='$compid' and active='Yes'");
                         // if compensated on holiday
                        if(count($holidays)=="1")
                        {
                            // check location based leave
                            $holiday_location=json_decode($holidays[0]->location);
                            if (in_array($locationid, $holiday_location)){
                               
                if($holidays)
                {
                    foreach ($holidays as $holiday)
                    {
                        $date=$begin->format('Y-m-d');
                        
                        if($date == $holiday->date)
                        {
							$hol_month_week=1;
                           $data.="<tr style='color:black;'>
                                        <td>".$date."</td>"
                                    . "<td></td>"
                                    . "<td></td>"
                                    . "<td><span class='sunday'>Holiday -".$holiday->holiday_name."</span></td>"
                                    . "</tr>";
                           $holidayss++;
                           $present_days++;
                            $value= '"' . $date.'"'."\t"
                            ."\t"
                            ."\t"
                            .$holiday->holiday_name.'" - HOLIDAY"'."\t";
                          
                       $rowData .= $value."\n";
                        }
                    }
                }
                            }
                        }
                        // month off
                         $date_day=date('N', strtotime($date));
                        // dd($date);
                            $date_weekno=$this->weeknumber($date);
                         //   $date_weekno=$this->weeknumber(2022-05-01);
                            //dd($date_weekno);
                            $week_off_query=\DB::select("SELECT * FROM `hr_emp_payroll_settings_t`");
                            $week_off=json_decode($week_off_query[0]->week_off);
                            $week_period=json_decode($week_off_query[0]->week_period);
                            $month_off=json_decode($week_off_query[0]->month_off);
                            $month_period=$this->arraycombine($month_off,$week_period);
                            if(in_array($date_day,$week_off))
                            {
								$hol_month_week=1;
                                      $data.="<tr style='color:black;'>
                                        <td>".$date."</td>"
                                    . "<td></td>"
                                    . "<td></td>"
                                    . "<td><span class='sunday'>WEEKOFF</span></td>"
                                    . "</tr>";
                                    $weekoffs++;   
                                    $present_days++;
                                    $value= '"' . $date.'"'."\t"
                                    ."\t"
                                    ."\t"
                                    ."WEEKOFF"."\t";
                                  $rowData .= $value."\n";
                            } 
                            elseif(isset($month_period[$date_day]))
                            {
                                if(in_array($date_weekno,$month_period[$date_day]))
                                {
									$hol_month_week=1;
                                    $data.="<tr style='color:black;'>
                                        <td>".$date."</td>"
                                    . "<td></td>"
                                    . "<td></td>"
                                    . "<td><span class='sunday'>MONTHOFF</span></td>"
                                    . "</tr>";
                                    $weekoffs++;
                                    $present_days++;
                                    $value= '"' . $date.'"'."\t"
                                    ."\t"
                                    ."\t"
                                    ."MONTHOFF"."\t";
                                  $rowData .= $value."\n";
                                }
                            }
              
                $date=(string)$begin->format("Y-m-d");
                $atten_detail=\DB::select("SELECT `check_in`,`check_out`,`working_hours` FROM `hr_emp_attendence` WHERE `emp_id`='$employee_number' AND `atten_date` like '$date%'");
                  /*** casual and sick,earn,od leave  half and full***/
            $query_leave_full=\DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM `hr_leaves_t` left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_type WHERE employee_id='$emp_id' and leave_status='APPROVE' AND (a_lookuplines_t.lookup_code='ON-DUTY' OR a_lookuplines_t.lookup_code='EARN LEAVE' OR a_lookuplines_t.lookup_code='CASUAL LEAVE' OR a_lookuplines_t.lookup_code='SICK LEAVE' OR a_lookuplines_t.lookup_code='COM-OFF') and ( '$date'  BETWEEN start_date AND end_date) and leave_mode='135'");
            $query_leave_half=\DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM `hr_leaves_t` left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_type WHERE employee_id='$emp_id' and leave_status='APPROVE' AND (a_lookuplines_t.lookup_code='ON-DUTY' OR a_lookuplines_t.lookup_code='EARN LEAVE' OR a_lookuplines_t.lookup_code='CASUAL LEAVE' OR a_lookuplines_t.lookup_code='SICK LEAVE') and ( '$date'  BETWEEN start_date AND end_date) and leave_mode='134'");
$curr_date=date('Y-m-d');
                if(count($atten_detail)>0)
                
                    {	
                   
                    if(count($query_leave_half)>0){
                         $data.="<tr style='color:black;'>
				<td>".$date."</td>"
                            . "<td>".$atten_detail[0]->check_in."</td>"
                            . "<td>".$atten_detail[0]->check_out."</td>"
                            . "<td><span class='present'>PRESENT  AND HALF ".$query_leave_half[0]->lookup_code."</span></td>"
                            . "</tr>";
                  
					
                    $value= '"' . $date.'"'."\t"
                            .'"'.$atten_detail[0]->check_in.'"'."\t"
                            .'"'.$atten_detail[0]->check_out.'"'."\t"
                            .'"PRESENT AND HALF "'.$query_leave_half[0]->lookup_code."\t";
                       $rowData .= $value."\n";    
                    		$present_days++;
                                if($query_leave_half[0]->lookup_code=="CASUAL LEAVE")
                                    $c_l=$c_l+0.5;
                                 if($query_leave_half[0]->lookup_code=="SICK LEAVE")
                                    $s_l=$s_l+0.5;
                                  if($query_leave_half[0]->lookup_code=="EARN LEAVE")
                                    $c_l=$c_l+0.5;
                                   if($query_leave_half[0]->lookup_code=="ON-DUTY")
                                    $od=$od+0.5;
                    }else{
                         $data.="<tr style='color:black;'>
				<td>".$date."</td>"
                            . "<td>".$atten_detail[0]->check_in."</td>"
                            . "<td>".$atten_detail[0]->check_out."</td>"
                            . "<td><span class='present'>PRESENT</span></td>"
                            . "</tr>";
                    
					
                    $value= '"' . $date.'"'."\t"
                            .'"'.$atten_detail[0]->check_in.'"'."\t"
                            .'"'.$atten_detail[0]->check_out.'"'."\t"
                            .'"PRESENT"'."\t";
                      $rowData .= $value."\n";    
                    		$present_days++;
                    }
                          
                }else if(count($query_leave_full)>0){
                    $data.="<tr style='color:black;'>
				<td>".$date."</td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td><span class='present'>PRESENT - ".$query_leave_full[0]->lookup_code."</span></td>"
                            . "</tr>";
                   
					
                    $value= '"' . $date.'"'."\t"
                            ."\t"
                            ."\t"
                            .'"PRESENT -"'.$query_leave_full[0]->lookup_code."\t";
                         $rowData .= $value."\n";
                    		$present_days++;
                                 if($query_leave_full[0]->lookup_code=="CASUAL LEAVE")
                                    $c_l=$c_l+1;
                                 if($query_leave_full[0]->lookup_code=="SICK LEAVE")
                                    $s_l=$s_l+1;
                                  if($query_leave_full[0]->lookup_code=="EARN LEAVE")
                                    $c_l=$c_l+1;
                                   if($query_leave_full[0]->lookup_code=="ON-DUTY")
                                    $od=$od+1;
                }
                else if(count($query_leave_half)>0){
                    $data.="<tr style='color:black;'>
				<td>".$date."</td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td><span class='present'>HALD DAY PRESENT - ".$query_leave_half[0]->lookup_code."</span></td>"
                            . "</tr>";
                   
					
                    $value= '"' . $date.'"'."\t"
                            ."\t"
                            ."\t"
                            .'"HALD DAY PRESENT -"'.$query_leave_half[0]->lookup_code."\t";
                       $rowData .= $value."\n";
                    		$present_days=$present_days+0.5;
                                 if($query_leave_half[0]->lookup_code=="CASUAL LEAVE")
                                    $c_l=$c_l+0.5;
                                 if($query_leave_half[0]->lookup_code=="SICK LEAVE")
                                    $s_l=$s_l+0.5;
                                  if($query_leave_half[0]->lookup_code=="EARN LEAVE")
                                    $c_l=$c_l+0.5;
                                   if($query_leave_half[0]->lookup_code=="ON-DUTY")
                                    $od=$od+0.5;
                }
				else if($hol_month_week==1){
					
				}
				else if($date>$curr_date){
					 $data.="<tr style='color:black;'>
				<td>".$date."</td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "</tr>";
                    
					
                    $value= '"' . $date.'"'."\t"
                            ."\t"
                            ."\t"
                            .'""'."\t";
                          $rowData .= $value."\n";
						  $absent_days++;
				}
                else 
                {
                       $data.="<tr style='color:black;'>
				<td>".$date."</td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td><span class='absent'>ABSENT"."</span></td>"
                            . "</tr>";
                    
					
                    $value= '"' . $date.'"'."\t"
                            ."\t"
                            ."\t"
                            .'"ABSENT"'."\t";
                          $rowData .= $value."\n";
                    		$absent_days++;
                }
                
                
                    $begin->modify('+1 day');
                    $total_days++;
                }
                  
                
                    
            }
            
              $setData .= trim($rowData)."\n";
   
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=".uniqid()." Calendar - ".date("d-m-Y").".xls");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=Book record sheet.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    $columnHeader =  $columnHeader."\n".$setData."\n";
    $file = fwrite($file,$columnHeader);

     $query_check= \DB::select("select * from hr_employee_payroll_lists join Leave_balance_tbl on Leave_balance_tbl.employee_id=hr_employee_payroll_lists.employee_id where Leave_balance_tbl.employee_id='$emp_id' and hr_employee_payroll_lists.month=$month and hr_employee_payroll_lists.year=$year");
     
     if(count($query_check)>0){
        $days = array(
                'total_days' => $query_check[0]->total_days,
                'present_days' => $query_check[0]->attendance_days,
                'absent_days' => ($query_check[0]->total_days- $query_check[0]->attendance_days),
                'holidays' => $holidayss,
                'weekoff' => $weekoffs,
                'casual' =>($query_check[0]->ocl- $query_check[0]->causal_leave),
                'sick' => ($query_check[0]->osl- $query_check[0]->sick_leave),
                'earn' => ($query_check[0]->oel- $query_check[0]->earn_leave),
                'remcl' => $query_check[0]->causal_leave,
                'remsl' => $query_check[0]->sick_leave,
                'remel' => $query_check[0]->earn_leave,
                'od' => $query_check[0]->od,
            ); 
     }else{
            $days = array(
                'total_days' => $total_days,
                'present_days' => $present_days,
                'absent_days' => $absent_days,
                'holidays' => $holidayss,
                'weekoff' => $weekoffs,
                'casual' =>$c_l,
                'sick' => $s_l,
                'earn' => $e_l,
                'remcl' => $cl,
                'remsl' => $sl,
                'remel' => $el,
                'od' => $od,
            );
     }
            //dd($days);
           
        $data.=    "<tr>"
                    . "<td colspan='12' align='center'><a href=".$my_file." class='delimeters ' download><button class='btn btn-danger fa fa-download' type='button'>DOWNLOAD</button></a></td>"
                    . "</tr>";
              		$data.="</tbody></table>"; 
              
               return response()->json(array('data1'=> $data,'data'=> $data,'days'	=> $days,'download_ling'=>$my_file));
       
        }
        // index for delimiter
         public function index()
         {
         
	 return view('payproposal.delimiterform',$this->data);
             
             
         }    
       
                 
        // report get for employeerelieve
          public function delimeterreport($month=null,$year=null)
         {
            $year = $year;
            $month = $month;
            
            $my_file = uniqid().'.csv';
            $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
			
			 
            $excel_file = uniqid().'12.xls';
            $handle1 = fopen($excel_file, 'w') or die('Cannot open file:  '.$excel_file);
		 	
            $groupname=\Session::get('groupname');
          //  $query1 = \DB::select("select hr_employee_t.* from hr_employee_t left join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_t.employee_id where hr_employee_t.active='Yes' and 1=1 and hr_employee_t.employee_id!=1 and hr_employee_payproposal.pf=1");
             if($groupname == "2"){
               $query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where hr_employee_t.active='Yes' and 1=1 and hr_employee_t.employee_id!=1 and hr_employee_payproposal.pf=1 and hr_employee_payroll_lists.month='$month' and year='$year'  group by hr_employee_payroll_lists.id");  
             }else{
               $query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where 1=1 and hr_employee_t.employee_id!=1 and hr_employee_payproposal.pf=1 and hr_employee_payroll_lists.month='$month' and year='$year'  group by hr_employee_payroll_lists.id");  
             }
      //  $query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where hr_employee_t.active='Yes' and 1=1 and hr_employee_t.employee_id!=1 and hr_employee_payproposal.pf=1 and hr_employee_payroll_lists.month='$month' and year='$year'  group by hr_employee_payroll_lists.id");
            $data1 ='';
                $pf_detatil   =DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.limitto','m_emp_esi.employeer_contribute','m_emp_esi.company_contribute','m_emp_esi.company_contribute1')->where('lookup_code','PF')->get();
            $data  = "<table class='table .table-hover' border='1'>"
                    ."<th  style='background-color: #81caea; color:white;'>S.No</th>"                    
                    ."<th  style='background-color: #81caea; color:white;'>Employee No</th>"
                    ."<th  style='background-color: #81caea; color:white;'>PF Number</th>"
                    ."<th  style='background-color: #81caea; color:white;'>Employee Name</th>"
                    ."<th  style='background-color: #81caea; color:white;'>UAN Number</th>"
                    ."<th  style='background-color: #81caea; color:white;'>Worked Days</th>"
                    ."<th  style='background-color: #81caea; color:white;'>NCP Days</th>"
                    ."<th  style='background-color: #008000ad; color:white;'>Gross Salary</th>"
					."<th  style='background-color: #008000ad; color:white;'>Earned Wages</th>"
                    ."<th  style='background-color: #008000ad; color:white;'>PF Employee Contribution-".$pf_detatil[0]->employeer_contribute."%</th>"
                    ."<th  style='background-color: #008000ad; color:white;'>VPF Employee Contribution</th>"
                    ."<th  style='background-color: #ea8181; color:white;'>Total</th>"
                    ."<th  style='background-color: #ae81ea; color:white;'>EPS Employer's Contribution-".$pf_detatil[0]->company_contribute."%</th>"
                    ."<th style='background-color: #806400ad; color:white;'>EPF Employer's Contribution-".$pf_detatil[0]->company_contribute1."%</th>"
                    ."<th  style='background-color: #ea8181; color:white;'>Total</th>";
           
            $i=1;
           $data2 ="S.No."."\t"."Employee Number"."\t"."PF Number"."\t"."Employee Name"." \t"."UAN Number"." \t"."Worked Days"." \t"."NCP Days"." \t"."Gross Salary"." \t"."Earned Wages"." \t"."PF EC-12"." \t"."VPF"." \t"."Total"." \t"."EPS-8.33"." \t"."EPF-3.67"." \t"."Total"."\r\n";
        
              $wages=0;    
              $wages_month=0;
              $pf_total=0;         
              $pfv_total=0;         
              $pf_v_total=0;         
              $cpf_total=0;         
              $cpf1_total=0;         
              $cpf_total=0;  
              $lop_days=0;
			  $gross_salary=0;
            $result_days = '~';

            $check_arr=array();
        
            foreach($query1 as $row)
            {
                
                if($row->uan_no == 0)
                    $uan_no = '~';
                else
                    $uan_no = $row->uan_no;
                  
                  if(isset($check_arr[$row->employee_id]))
                  {
                    $check_arr[$row->employee_id]=$check_arr[$row->employee_id]+1;
                  }
                  else
                  {
                    $check_arr[$row->employee_id]=0;
                  }
                   
                 
                $result = $this->contribute($row->employee_id,$month,$year,$row->id,$check_arr[$row->employee_id]);
				
                $data   .="<tr>"
                            ."<td>".$i."</td>"
                            ."<td>".$row->employee_number."</td>"
                            ."<td>".$row->pf_no."</td>"
                            ."<td>".$row->first_name."</td>"                        
                            ."<td>".$uan_no."</td>"
                            ."<td>".$result['day']."</td>"
                            ."<td>".$result['ncp']."</td>"
							."<td>".$result['gross_salary']."</td>"
                            ."<td>".$result['earned_wages']."</td>"
                            ."<td>".$result['pf']."</td>"
                            ."<td>".$result['v_pf']."</td>"
                            ."<td>".($result['pf']+$result['v_pf'])."</td>"
                            ."<td>".$result['amount_comp']."</td>"
                            ."<td>".$result['amount_comp1']."</td>"
                            ."<td>".($result['amount_comp']+$result['amount_comp1'])."</td>"
                            
                        ."</tr>";
						
						
			 $data2 .= $i."\t".$row->employee_number."\t".$row->pf_no."\t".$row->first_name."\t".$uan_no."\t".$result['day']."\t".$result['ncp']."\t".$result['gross_salary']."\t".$result['earned_wages']."\t".$result['pf']."\t".$result['v_pf']."\t".($result['pf']+$result['v_pf'])."\t".$result['amount_comp']."\t".$result['amount_comp1']."\t".($result['amount_comp']+$result['amount_comp1'])."\r\n";
          			
						
                $wages=$wages+$result['earned_wages'];
             $pf_total=$pf_total+$result['pf'];         
              $pfv_total=$pfv_total+$result['v_pf'];         
              $pf_v_total=$pf_v_total+$result['pf']+$result['v_pf'];         
              $cpf_total=$cpf_total+$result['amount_comp'];         
              $cpf1_total=$cpf1_total+$result['amount_comp1'];  
$gross_salary=$gross_salary+$result['gross_salary'];			  
              $cpf_total=$cpf_total+$result['amount_comp']+$result['amount_comp1'];
                   $query2 = \DB::select("select * from hr_employee_payroll_lists where id='$row->id' and month='$month' and year='$year'"); 
                   if(count($query2)>0){
                       $lop_days=$query2[0]->no_of_days_employee-round($query2[0]->attendance_days);
                       $wages_month=$query2[0]->basic_salary+$query2[0]->da;
              if($wages_month>$pf_detatil[0]->limitto){
                 $wages_month=$pf_detatil[0]->limitto; 
              }
              }
              
              
                        $data1 .= $row->uan_no."#~#".$row->first_name."#~#".$result['gross_salary']."#~#".$wages_month."#~#".$wages_month."#~#".$wages_month."#~#".($result['pf']+$result['v_pf'])."#~#".$result['amount_comp']."#~#".$result['amount_comp1']."#~#".$lop_days."#~#"."\r\n";
                        $i++;
            }
            
            
           
            $data   .= "<tr>"
                     ."<td>".$i."</td>"
                            ."<td></td>"
                            ."<td></td>"
                            ."<td></td>"                        
                            ."<td></td>"
                            ."<td></td>"
                            ."<td>Total:</td>"
							."<td>".$gross_salary."</td>"
                            ."<td>".$wages."</td>"
                            ."<td>".$pf_total."</td>"
                            ."<td>".$pfv_total."</td>"
                            ."<td>".$pf_v_total."</td>"
                            ."<td>".$cpf_total."</td>"
                            ."<td>".$cpf1_total."</td>"
                            ."<td>".$cpf_total."</td>"
                            ."<td>~</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td colspan='12' align='center'><a href=".$my_file." class='delimeters ' download><button class='btn btn-danger fa fa-download' type='button'>DOWNLOAD</button></a><a href=".$excel_file." class='delimeters_excel' download><button class='btn btn-danger fa fa-download' type='button'>DOWNLOAD Excel</button></a></td>"
                    . "</tr>";
                    fwrite($handle, $data1);
                    fwrite($handle1, $data2);
                    
            return response()->json(array('data'=> $data,'download_ling'=>$my_file));
             
             
             
         } 
         
         //get data for delimiter
           function contribute($employe_id,$month,$year,$id,$index)
        {
        
          $index1=$index+1;
            $query2 = \DB::select("select * from hr_employee_payroll_lists where id='$id' and month='$month' and year='$year'");  
           
		   $query = \DB::select("select * from hr_employee_payproposal where employee_id='$employe_id' ");  
            $query_pf = \DB::select("select * from hr_company_contribute_pf where emp_id='$employe_id' and month='$month' and year='$year' order by employee_conribute_id asc limit $index, $index1 ");  
            if(count($query2)>0)
            { 
                $no_of_days     = $query2[0]->attendance_days;
                $total_days = $query2[0]->no_of_days_employee;
                $ncp = $query2[0]->no_of_days_employee - $query2[0]->attendance_days;
				$gross_salary=$query2[0]->gross_salary;
                if(count($query)>0){
                $earn_wages   = $query2[0]->basic_salary+$query2[0]->da;
				if($earn_wages>15000)
					$earn_wages=15000;
                }else{
                     $earn_wages   =0;
                }
                 if(count($query_pf)>0){
                $pf   = $query_pf[0]->amount_employee;
                $v_pf   = $query_pf[0]->volunter_pf;
                if($v_pf==''){
                    $v_pf=0;
                }
                $pf_emp=$pf+$v_pf;
                $amount_comp   = $query_pf[0]->amount;
                $amount_comp1   = $query_pf[0]->amount1;
                }else{
                     $amount_comp1   =0;
                     $amount_comp   =0;
                     $pf_emp   =0;
                     $v_pf   =0;
                     $pf   =0;
                }

                return  array("gross_salary"=>$gross_salary,"day"=>$no_of_days,"ncp"=>$ncp,"earned_wages"=>$earn_wages,"pf"=>$pf,"v_pf"=>$v_pf,"pf_emp"=>$pf_emp,"amount_comp"=>$amount_comp,"amount_comp1"=>$amount_comp1);
            }
            else
            {
                return  array("gross_salary"=>0,"day"=>"0","ncp"=>"0","earned_wages"=>"0","pf"=>"0","v_pf"=>"0","pf_emp"=>"0","amount_comp"=>"0","amount_comp1"=>"0");
            }
            return  array("gross_salary"=>0,"day"=>"0","ncp"=>"0","earned_wages"=>"0","pf"=>"0","v_pf"=>"0","pf_emp"=>"0","amount_comp"=>"0","amount_comp1"=>"0"); }
      // index for esi delimiter
               public function esiindex()
         {
         
	 return view('payproposal.delimiteresiform',$this->data);
             
             
         } 
         public function ptindex()
         {
         
	 return view('payproposal.delimiterptform',$this->data);
             
             
         } 
        public function delimeterreportpt($month=null,$year=null)
         {
              $year = $year;
            $month = $month;
            $employee_type = $_GET['type'];
            $my_file = uniqid().'.xls';
            $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
            $m1=0;
            $m2=0;
            $m3=0;
            $m4=0;
            $m5=0;
            $m6=0;
            $gross_sum1=0;
            $paid_pt=0;
            $paid_pt_total=0;
            $pt=0;
            if($employee_type!=0 ){
              $query1 = \DB::select("select a_lookuplines_t.lookup_code,hr_employee_t.*,m_position.position,m_job_title.job_title_name from hr_employee_t left join m_job_title on m_job_title.job_title_id=hr_employee_t.job_title left join m_position on m_position.position_id=hr_employee_t.position left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_employee_t.employee_type where  1=1 and hr_employee_t.employee_id!=1 and employee_type=$employee_type ");
               
            }else{
            $query1 = \DB::select("select a_lookuplines_t.lookup_code,hr_employee_t.*,m_position.position,m_job_title.job_title_name from hr_employee_t left join m_job_title on m_job_title.job_title_id=hr_employee_t.job_title left join m_position on m_position.position_id=hr_employee_t.position left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_employee_t.employee_type where  1=1 and hr_employee_t.employee_id!=1 ");
            }
                $pf_detatil   =DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.limitto','m_emp_esi.employeer_contribute','m_emp_esi.company_contribute','m_emp_esi.company_contribute1')->where('lookup_code','ESI')->get();
             $data2 ="S.No."."\t"."Employee Number"."\t"."Particulars"." \t"."Designation"." \t"."Employee Type"." \t"."DOJ"." \t"."DOR";
                $data  = "<table class='table .table-hover' border='1'>"
                    ."<th  style='background-color: #81caea; color:white;'>S.No</th>"                    
                    ."<th  style='background-color: #81caea; color:white;'>Employee Number</th>"
                    ."<th  style='background-color: #81caea; color:white;'>Particulars</th>"
                    ."<th  style='background-color: #81caea; color:white;'>Designation</th>"
                    ."<th  style='background-color: #81caea; color:white;'>Employee Type</th>"
                    ."<th  style='background-color: #81caea; color:white;'>DOJ</th>"
                    ."<th  style='background-color: #008000ad; color:white;'>DOR</th>";
                 $month_name=date("F'Y", strtotime( $year."-".$month."-01"));
     $data  .="<th  style='background-color: #008000ad; color:white;'>".$month_name."</th>";
      $data2  .= " \t".$month_name;
            for ($i = 1; $i < 6; $i++) 
{
    $month_name=date("F'Y", strtotime( $year."-".$month."-01 -$i months"));
     $data  .="<th  style='background-color: #008000ad; color:white;'>".$month_name."</th>";
      $data2  .= " \t".$month_name;
}
                   
                  $data  .="<th  style='background-color: #008000ad; color:white;'>1 st half - Gross Salary (Approximate)</th>"
                    ."<th  style='background-color: #ae81ea; color:white;'>PT Amount</th>"
                    ."<th  style='background-color: #ea8181; color:white;'>Paid PT Amount</th>"
                    ."<th  style='background-color: #ea8181; color:white;'>Total PT Amount</th>"
                    ."<th  style='background-color: #ea8181; color:white;'>Remark</th>";
           $data2  .= " \t"."1 st half - Gross Salary (Approximate)"."\t"."PT Amount"."\t"."Paid PT Amount"."\t"."Total PT Amount"."\t"."Remark";
            $i1=1;
        
        
                        for ($i = 1; $i < 6; $i++) 

                 $data2  .= "\r\n";
               
            foreach($query1 as $row)
            {
               
               $data2.= $i1."\t".$row->employee_number."\t".$row->first_name."\t".$row->job_title_name."/".$row->position."\t".$row->lookup_code."\t".$row->date_of_joining."\t".$row->date_of_leaving;
                $data.="<tr>"
                            ."<td>".$i1."</td>"
                            ."<td>".$row->employee_number."</td>"
                            ."<td>".$row->first_name."</td>"   
                            ."<td>".$row->job_title_name."/".$row->position."</td>"   
                            ."<td>".$row->lookup_code."</td>"
                            ."<td>".$row->date_of_joining."</td>"
                            ."<td>".$row->date_of_leaving."</td>";
                $paid_pt_org=0;
                $gross_sum=0;
                 $month_name=date("m", strtotime( $year."-".$month."-01"));
    $year_name=date("Y", strtotime( $year."-".$month."-01"));
    $query2 = \DB::select("SELECT * FROM `hr_employee_payroll_lists` where month=$month_name and year=$year_name and employee_id=$row->employee_id");
    if(count($query2)>0){
    $data.="<td>".$query2[0]->gross_salary."</td>";
    $data2.="\t".$query2[0]->gross_salary;
    $paid_pt_org=$paid_pt_org+$query2[0]->pt;
    $m="m1";
    $$m=$$m+$query2[0]->gross_salary;
    $gross_sum=$gross_sum+$query2[0]->gross_salary;
    }
    else{
          $data.="<td></td>";
    $data2.="\t";
           $gross_sum=$gross_sum+0;
       $paid_pt_org=$paid_pt_org+0;
    }
    $j=1;
                        for ($i = 1; $i < 6; $i++) 
{
                            $j++;
    $month_name=date("m", strtotime( $year."-".$month."-01 -$i months"));
    $year_name=date("Y", strtotime( $year."-".$month."-01 -$i months"));
    $query2 = \DB::select("SELECT * FROM `hr_employee_payroll_lists` where month=$month_name and year=$year_name and employee_id=$row->employee_id");
    if(count($query2)>0){
    $data.="<td>".$query2[0]->gross_salary."</td>";
    $data2.="\t".$query2[0]->gross_salary;
    $paid_pt_org=$paid_pt_org+$query2[0]->pt;
    $m="m".$j;
    $$m=$$m+$query2[0]->gross_salary;
    $gross_sum=$gross_sum+$query2[0]->gross_salary;
    }
    else{
          $data.="<td></td>";
    $data2.="\t";
           $gross_sum=$gross_sum+0;
       $paid_pt_org=$paid_pt_org+0;
    }
}
if($gross_sum>=75000){
    $pt_val=1250;
}
elseif($gross_sum>=60001){
    $pt_val=1188;
}
elseif($gross_sum>=45001){
    $pt_val=797;
}
elseif($gross_sum>=30001){
    $pt_val=398;
}
elseif($gross_sum>=20001){
    $pt_val=162;
}else{
    $pt_val=0;
}
$pt=$pt+$pt_val;
$paid_pt=$paid_pt+$paid_pt_org;
$paid_pt_total=$paid_pt_total+($pt_val-$paid_pt_org);
$gross_sum1=$gross_sum1+$gross_sum;
  $data2.="\t".$gross_sum."\t".$pt_val."\t".$paid_pt_org."\t".($pt_val-$paid_pt_org);
                             $data.="<td>".$gross_sum."</td>"
                            ."<td>".$pt_val."</td>"
                            ."<td>".$paid_pt_org."</td>"
                            ."<td>".($pt_val-$paid_pt_org)."</td>"
                            ."<td></td>"
                        ."</tr>";
            $data2.="\r\n";
                        $i1++;
            }
            
           
            $data   .= "<tr>"
                     ."<td></td>"
                            ."<td></td>"
                            ."<td></td>"
                            ."<td></td>"  
                            ."<td></td>"  
                            ."<td></td>"  
                            ."<td>Total:</td>"
                            ."<td>".$m1."</td>"
                            ."<td>".$m2."</td>"
                            ."<td>".$m3."</td>"
                            ."<td>".$m4."</td>"
                            ."<td>".$m5."</td>"
                            ."<td>".$m6."</td>"
                            ."<td>".$gross_sum1."</td>"
                            ."<td>".$pt."</td>"
                            ."<td>".$paid_pt."</td>"
                            ."<td>".$paid_pt_total."</td>"
                            ."<td></td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td colspan='12' align='center'><a href=".$my_file." class='delimeters ' download><button class='btn btn-danger fa fa-download' type='button'>DOWNLOAD</button></a></td>"
                    . "</tr>";
            $data2.="\t\t\t\t\t\t"."Total"."\t"."$m1"."\t"."$m2"."\t"."$m3"."\t"."$m4"."\t"."$m5"."\t"."$m6"."\t"."$gross_sum1"."\t"."$pt"."\t"."$paid_pt"."\t"."$paid_pt_total";
//           / dd($data2);
                    fwrite($handle, $data2);
                    
            return response()->json(array('data'=> $data,'download_ling'=>$my_file));
              
             
         }   
            
   
         // report get for esi delimiter
          public function delimeterreportesi($month=null,$year=null)
         {
              $year = $year;
            $month = $month;
            
            $my_file = uniqid().'.xls';
            $excel_file = uniqid().'12.xls';
            $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
            $handle1 = fopen($excel_file, 'w') or die('Cannot open file:  '.$excel_file);
            $groupname=\Session::get('groupname');
           // $query1 = \DB::select("select hr_employee_t.* from hr_employee_t left join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_t.employee_id where hr_employee_t.active='Yes' and 1=1 and hr_employee_t.employee_id!=1 and hr_employee_payproposal.esi=1 ");
            //   $query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where hr_employee_t.active='Yes' and 1=1 and hr_employee_t.employee_id!=1 and hr_employee_payproposal.esi=1 and hr_employee_payroll_lists.month='$month' and year='$year'  group by hr_employee_payroll_lists.id");
              if($groupname == "2"){
                $query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where hr_employee_t.active='Yes' and 1=1 and hr_employee_t.employee_id!=1 and (hr_employee_payproposal.gross_pay<21000 or hr_employee_payroll_lists.esi>0) and hr_employee_payroll_lists.month='$month' and year='$year'  group by hr_employee_payroll_lists.id");  
              }else{
                $query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where 1=1 and hr_employee_t.employee_id!=1 and (hr_employee_payproposal.gross_pay<21000 or hr_employee_payroll_lists.esi>0) and hr_employee_payroll_lists.month='$month' and year='$year'  group by hr_employee_payroll_lists.id");  
              }
              //$query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where hr_employee_t.active='Yes' and 1=1 and hr_employee_t.employee_id!=1 and (hr_employee_payproposal.gross_pay<21000 or hr_employee_payroll_lists.esi>0) and hr_employee_payroll_lists.month='$month' and year='$year'  group by hr_employee_payroll_lists.id");
        // dd($query1);
            $data1 ='';
                $pf_detatil   =DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.limitto','m_emp_esi.employeer_contribute','m_emp_esi.company_contribute','m_emp_esi.company_contribute1')->where('lookup_code','ESI')->get();
            $data  = "<table class='table .table-hover' border='1'>"
                    ."<th  style='background-color: #81caea; color:white;'>S.No</th>"                    
                    ."<th  style='background-color: #81caea; color:white;'>Employee No</th>"
                    ."<th  style='background-color: #81caea; color:white;'>Employee Name</th>"
                    ."<th  style='background-color: #81caea; color:white;'>Esi Number</th>"
                    ."<th  style='background-color: #81caea; color:white;'>Worked Days</th>"
                    ."<th  style='background-color: #008000ad; color:white;'>Fixed Wages</th>"
                    ."<th  style='background-color: #008000ad; color:white;'>Earned Wages</th>"
                    ."<th  style='background-color: #008000ad; color:white;'>Employee's Contribution-".$pf_detatil[0]->employeer_contribute."%</th>"
                    ."<th  style='background-color: #ae81ea; color:white;'>Employer's Contribution-".$pf_detatil[0]->company_contribute."%</th>"
                    ."<th  style='background-color: #ea8181; color:white;'>Total</th>";
           
            $i=1;
        
              $wages=0;    
              $earned_wages=0;
              $esi_total=0;
              $esi_comp_total=0;
              $gran_total=0;
              $data1='';
			  
               $data1 .="IP Number"."\t"."IP Name"."\t"." No of Days for which wages paid/payable during the month"." \t"."Total Monthly Wages"."\r\n";
         $data2 ="S.No."."\t"."Employee Number"."\t"."Employee Name"." \t"."ESI Number"." \t"."Worked Days"." \t"."Fixed Wages"." \t"."Earned Wages"." \t"."EC-1.75"." \t"."ErC-4.75"." \t"."Total"."\r\n";
               $check_arr=array();
            foreach($query1 as $row)
            {
                if(isset($check_arr[$row->employee_id]))
                  {
                    $check_arr[$row->employee_id]=$check_arr[$row->employee_id]+1;
                  }
                  else
                  {
                    $check_arr[$row->employee_id]=0;
                  }
                
                $result = $this->contributeesi($row->employee_id,$month,$year,$row->id,$check_arr[$row->employee_id]);
                $data   .="<tr>"
                            ."<td>".$i."</td>"
                            ."<td>".$row->employee_number."</td>"
                            ."<td>".$row->first_name."</td>"   
                            ."<td>".$row->esi_no."</td>"
                            ."<td>".$result['day']."</td>"
                            ."<td>".$result['fixed_wages']."</td>"
                            ."<td>".$result['earned_wages']."</td>"
							."<td>".$result['comp_esi']."</td>"
                            ."<td>".$result['esi']."</td>"
                            
                            ."<td>".($result['esi']+$result['comp_esi'])."</td>"
                            
                        ."</tr>";
                $wages=$wages+$result['fixed_wages'];
                $earned_wages=$earned_wages+$result['earned_wages'];
             $esi_total=$esi_total+$result['esi'];         
              $esi_comp_total=$esi_comp_total+$result['comp_esi'];         
              $gran_total=$gran_total+$result['esi']+$result['comp_esi'];  
			    $data2 .= $i."\t".$row->employee_number."\t".$row->first_name."\t".$row->esi_no."\t".$result['day']."\t".$result['fixed_wages']."\t".$result['earned_wages']."\t".$result['comp_esi']."\t".$result['esi']."\t".($result['esi']+$result['comp_esi'])."\r\n";
                       
             
              if(($result['esi']+$result['comp_esi'])!=0)
                        $data1 .= $row->esi_no."\t".$row->first_name."\t".round($result['day'])."\t".$result['earned_wages']."\r\n";
                        $i++;
            }
            
            
           
            $data   .= "<tr>"
                     ."<td>".$i."</td>"
                            ."<td></td>"
                            ."<td></td>"
                            ."<td></td>"  
                            ."<td>Total:</td>"
                            ."<td>".$wages."</td>"
                            ."<td>".$earned_wages."</td>"
							."<td>".$esi_comp_total."</td>"
                            ."<td>".$esi_total."</td>"
                            
                            ."<td>".$gran_total."</td>"
                    . "</tr>"
                    . "<tr>"
                    . "<td colspan='12' align='center'><a href=".$my_file." class='delimeters ' download><button class='btn btn-danger fa fa-download' type='button'>DOWNLOAD</button></a><a href=".$excel_file." class='delimeters_excel ' download><button class='btn btn-danger fa fa-download' type='button'>DOWNLOAD Excel</button></a></td>"
                    . "</tr>";
                    fwrite($handle, $data1);
                    fwrite($handle1, $data2);
                    
            return response()->json(array('data'=> $data,'download_ling'=>$my_file,'excel_file'=>$excel_file));
              
             
         } 
          //get data for esi delimiter
           function contributeesi($employe_id,$month,$year,$id,$index)
        {
        $index1=$index+1;
            $query2 = \DB::select("select * from hr_employee_payroll_lists where id='$id' and month='$month' and year='$year'");  
            $query = \DB::select("select * from hr_employee_payproposal where employee_id='$employe_id'");  
            $query_pf = \DB::select("select * from hr_company_contribute_esi where emp_id='$employe_id' and month='$month' and year='$year' order by company_conribute_id asc limit $index, $index1 ");  
            
            if(count($query2)>0)
            { 
                $no_of_days     = $query2[0]->attendance_days;
                $fixed          = $query[0]->gross_pay;
                $earn_wages     = $query2[0]->gross_salary;
				if($query2[0]->esi>0)
				{
				// dd($query_pf);
				if(count($query_pf)>0){
				     $esi            = $query_pf[0]->amount;
                    $esi_comp       = $query_pf[0]->amount_employee;
			    }else{
			        $esi=0;
			        $esi_comp =0;
			    }
               	}
				else
				{
				 $esi            = 0;
                $esi_comp       = 0;	
				}
              

                return  array("day"=>$no_of_days,"fixed_wages"=>$fixed,"earned_wages"=>$earn_wages,"esi"=>$esi,"comp_esi"=>$esi_comp);
            }
            else
            {
                return  array("day"=>"0","fixed_wages"=>'0',"earned_wages"=>"0","esi"=>"0","comp_esi"=>"0");
            }
            return  array("day"=>"0","fixed_wages"=>'0',"earned_wages"=>"0","esi"=>"0","comp_esi"=>"0");
        }
    //esi report
        public function esireportindex()
    {
			
		 return view('payproposal.esireport',$this->data);
   
	}
       public function pfreportgrid()
    {
      $company_id = Session::get('companyid');
		
	 $emp=\Session::get('emp_id');	
	
		$wh='';
       
       //based on superadmin load all data and remaining all indivudual data only
         $emp_data   =   DB::table("hr_employee_t")->where('employee_id',$emp)->get();
		 //dd($emp_data);
          $dept=json_decode($emp_data[0]->department);
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
       $search_tables=[""];
       if($_GET['_search']=='true'){
           $wh.=$this->jqgridsearchnotab('v1',$_GET['filters']);
       }
	
 if(!$sidx) $sidx =1;
    $result = \DB::select("select * from (SELECT hr_company_contribute_pf.date,hr_company_contribute_pf.emp_id,hr_employee_t.employee_id,max(hr_company_contribute_pf.employee_conribute_id) as employee_conribute_id,hr_company_contribute_pf.month,hr_company_contribute_pf.year,hr_company_contribute_pf.amount,hr_company_contribute_pf.amount1,hr_company_contribute_pf.amount_employee,hr_company_contribute_pf.volunter_pf,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM  hr_employee_t    JOIN hr_company_contribute_pf ON hr_employee_t.employee_id = hr_company_contribute_pf.emp_id where 1=1 group by emp_id,month,year)v1  WHERE  1 = 1  $wh  group by v1.emp_id,v1.month,v1.year");
    $count = count($result);
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

        
    $SQL = "select * from (SELECT hr_company_contribute_pf.date,hr_company_contribute_pf.emp_id,hr_employee_t.employee_id,max(hr_company_contribute_pf.employee_conribute_id) as employee_conribute_id,hr_company_contribute_pf.month,hr_company_contribute_pf.year,hr_company_contribute_pf.amount,hr_company_contribute_pf.amount1,hr_company_contribute_pf.amount_employee,hr_company_contribute_pf.volunter_pf,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM  hr_employee_t    JOIN hr_company_contribute_pf ON hr_employee_t.employee_id = hr_company_contribute_pf.emp_id where 1=1 group by emp_id,month,year)v1  WHERE  1 = 1  $wh   ORDER BY v1.employee_conribute_id $sord LIMIT $start,$limit";
          $download_SQL = "select * from (SELECT hr_company_contribute_pf.date,hr_company_contribute_pf.emp_id,hr_employee_t.employee_id,max(hr_company_contribute_pf.employee_conribute_id) as employee_conribute_id,hr_company_contribute_pf.month,hr_company_contribute_pf.year,hr_company_contribute_pf.amount,hr_company_contribute_pf.amount1,hr_company_contribute_pf.amount_employee,hr_company_contribute_pf.volunter_pf,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM  hr_employee_t    JOIN hr_company_contribute_pf ON hr_employee_t.employee_id = hr_company_contribute_pf.emp_id where 1=1 group by emp_id,month,year)v1  WHERE  1 = 1  $wh   ORDER BY v1.employee_conribute_id $sord ";

           $result1 = \DB::select( $download_SQL );
       
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
       
    $result = \DB::select($SQL);
    $responce->rows[]='';
    $responce->rows=$result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;

    echo json_encode($responce);
    }
	   public function esireportgrid()
    {
	
      $company_id = Session::get('companyid');
		
	 $emp=\Session::get('emp_id');	
	
		$wh='';
       
       //based on superadmin load all data and remaining all indivudual data only
         $emp_data   =   DB::table("hr_employee_t")->where('employee_id',$emp)->get();
		 //dd($emp_data);
          $dept=json_decode($emp_data[0]->department);
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
       $search_tables=[""];
       if($_GET['_search']=='true'){
           $wh.=$this->jqgridsearchnotab('v1',$_GET['filters']);
       }
	
 if(!$sidx) $sidx =1;
    $result = \DB::select("select * from (SELECT hr_company_contribute_esi.date,hr_company_contribute_esi.emp_id,hr_employee_t.employee_id,max(hr_company_contribute_esi.company_conribute_id) as company_conribute_id,hr_company_contribute_esi.month,hr_company_contribute_esi.year,hr_company_contribute_esi.amount,hr_company_contribute_esi.amount_employee,hr_company_contribute_esi.volunter_pf,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM  hr_employee_t    JOIN hr_company_contribute_esi ON hr_employee_t.employee_id = hr_company_contribute_esi.emp_id where 1=1 group by emp_id,month,year)v1  WHERE  1 = 1  $wh  group by v1.emp_id,v1.month,v1.year");
    $count = count($result);
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

        
    $SQL = "select * from (SELECT hr_company_contribute_esi.date,hr_company_contribute_esi.emp_id,hr_employee_t.employee_id,max(hr_company_contribute_esi.company_conribute_id) as company_conribute_id,hr_company_contribute_esi.month,hr_company_contribute_esi.year,hr_company_contribute_esi.amount,hr_company_contribute_esi.amount_employee,hr_company_contribute_esi.volunter_pf,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM  hr_employee_t    JOIN hr_company_contribute_esi ON hr_employee_t.employee_id = hr_company_contribute_esi.emp_id where 1=1 group by emp_id,month,year)v1  WHERE  1 = 1  $wh   ORDER BY v1.company_conribute_id $sord LIMIT $start,$limit";
          $download_SQL = "select * from (SELECT hr_company_contribute_esi.date,hr_company_contribute_esi.emp_id,hr_employee_t.employee_id,max(hr_company_contribute_esi.company_conribute_id) as company_conribute_id,hr_company_contribute_esi.month,hr_company_contribute_esi.year,hr_company_contribute_esi.amount,hr_company_contribute_esi.amount_employee,hr_company_contribute_esi.volunter_pf,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM  hr_employee_t    JOIN hr_company_contribute_esi ON hr_employee_t.employee_id = hr_company_contribute_esi.emp_id where 1=1 group by emp_id,month,year)v1  WHERE  1 = 1  $wh   ORDER BY v1.company_conribute_id $sord ";

           $result1 = \DB::select( $download_SQL );
       
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
       
    $result = \DB::select($SQL);
    $responce->rows[]='';
    $responce->rows=$result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;

    echo json_encode($responce);
    }
    // pf report
        public function pfreportindex()
    {
			
		   
       
        return view('payproposal.pfreport',$this->data);
    }
    // week number check
		public function weeknumber($date)
        {
            $week_name  =  array("1"=>"first","2"=>"second","3"=>"third","4"=>"fourth","5"=>"fifth","6"=>"first","52"=>"second","53"=>"third","54"=>"fourth");
            $firstOfMonth = date("Y-m-01", strtotime($date));
            $Month        = (string)date("m", strtotime($date));
            $Date         = (string)date("d", strtotime($date));
            
            //echo $Month;
           // dd($Month);
           if($Month == "01" && $Date != "01"){
                //echo "test4";
                //dd($date);
                $week_month=intval(date("W", strtotime($date)));
               //dd($week_month);
            }
            
            else if($Month=="12") 
            { //echo "test1";
                $week=intval(date("W", strtotime($date)));
                
                if($week==1)
                {//echo "test2";
                    $week_month=5;
                } 
                else
                {//echo "test3";
                    $week_month=intval(date("W", strtotime($date)))-intval(date("W", strtotime($firstOfMonth)))+1;
                } 
            }  
            else
            {  //echo "test5";
           // dd(intval(date("W",strtotime($date))));
          //  dd(intval(date("W",strtotime($firstOfMonth))));
                $week_month=intval(date("W",strtotime($date)))-intval(date("W",strtotime($firstOfMonth)))+1;
            //    dd($week_month);
                
            }
          //  dd($week_month);
            // echo $week_month;
           // echo $week_name[$week_month];
          // dd($week_name[$week_month]);
             return  $week_name[$week_month]; 
        }
        // array combine
		public function arraycombine($a,$b) 
        {
            $c=[];
            foreach($a as $k=>$v)
            {
                if(!isset($c[$v]))
                {
                    $c[$v][]=$b[$k];
                } 
                else 
                {
                    $c[$v][]=$b[$k];
                }
            }
            return $c;
        }
	// pay report
	   public function employeepayreportgrid()
    {

//dd("FDg");
      $company_id = Session::get('companyid');
		
	 $emp=\Session::get('emp_id');	
	
		$wh='';
       
       //based on superadmin load all data and remaining all indivudual data only
         $emp_data   =   DB::table("hr_employee_t")->where('employee_id',$emp)->get();
		 //dd($emp_data);
          $dept=json_decode($emp_data[0]->department);
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
       $search_tables=[""];
       if($_GET['_search']=='true'){
           $wh.=$this->jqgridsearchnotab('v1',$_GET['filters']);
       }
	
 if(!$sidx) $sidx =1;
    $result = \DB::select("select * from (SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payroll_lists.month,
                 hr_employee_payroll_lists.year,
                 hr_employee_payroll_lists.date,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_payroll_lists.basic_salary as list_basic_salary,
                 hr_employee_payroll_lists.hra as list_hra,
                 hr_employee_payroll_lists.da as list_da,
                 hr_employee_payroll_lists.allowance as list_allowance,	
                 hr_employee_payroll_lists.pf as list_pf_amount,
                 hr_employee_payroll_lists.esi as list_esi_amount,
                 hr_employee_payroll_lists.pt as list_pt_amount,
                 hr_employee_payroll_lists.gross_salary as list_gross_salary,
                 hr_employee_payroll_lists.net_salary as list_net_salary,
                 hr_employee_payroll_lists.loan_deduction as list_loan_deduction,
                 hr_employee_payroll_lists.total_days as totaldays,
                 hr_employee_payroll_lists.attendance_days as presentdays,
                 hr_employee_payroll_lists.el as taken_el,
                 hr_employee_payroll_lists.sl as taken_sl,
                 hr_employee_payroll_lists.cl as taken_cl,
                 hr_employee_payroll_lists.od as taken_od,
                 hr_employee_payroll_lists.partial_day as taken_partial_day,
                 hr_employee_payroll_lists.compensated_day as taken_compensated_day,
                 hr_employee_t.e_l as rem_el,
                 hr_employee_t.s_l as rem_sl,
                 hr_employee_t.c_l as rem_cl,
                 hr_employee_payroll_lists.id,
                 hr_employee_t.department,
                 hr_employee_payproposal.employee_type
                 FROM   hr_employee_payroll_lists
                 LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type WHERE  1 = 1 )v1  WHERE  1 = 1  $wh ");
    $count = count($result);
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

        
    $SQL = "select * from (SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payroll_lists.month,
                 hr_employee_payroll_lists.year,
                 hr_employee_payroll_lists.date,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_payroll_lists.basic_salary as list_basic_salary,
                 hr_employee_payroll_lists.hra as list_hra,
                 hr_employee_payroll_lists.da as list_da,
                 hr_employee_payroll_lists.allowance as list_allowance,	
                 hr_employee_payroll_lists.pf as list_pf_amount,
                 hr_employee_payroll_lists.esi as list_esi_amount,
                 hr_employee_payroll_lists.pt as list_pt_amount,
                 hr_employee_payroll_lists.gross_salary as list_gross_salary,
                 hr_employee_payroll_lists.net_salary as list_net_salary,
                 hr_employee_payroll_lists.loan_deduction as list_loan_deduction,
                 hr_employee_payroll_lists.total_days as totaldays,
                 hr_employee_payroll_lists.attendance_days as presentdays,
                 hr_employee_payroll_lists.el as taken_el,
                 hr_employee_payroll_lists.sl as taken_sl,
                 hr_employee_payroll_lists.cl as taken_cl,
                 hr_employee_payroll_lists.od as taken_od,
                 hr_employee_payroll_lists.partial_day as taken_partial_day,
                 hr_employee_payroll_lists.compensated_day as taken_compensated_day,
                 hr_employee_t.e_l as rem_el,
                 hr_employee_t.s_l as rem_sl,
                 hr_employee_t.c_l as rem_cl,
                 hr_employee_payroll_lists.id,
                 hr_employee_t.department,
                 hr_employee_payproposal.employee_type
                 FROM   hr_employee_payroll_lists
                 LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type WHERE  1 = 1 )v1  WHERE  1 = 1  $wh  LIMIT $start,$limit";
          $download_SQL = "select * from (SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payroll_lists.month,
                 hr_employee_payroll_lists.year,
                 hr_employee_payroll_lists.date,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_payroll_lists.basic_salary as list_basic_salary,
                 hr_employee_payroll_lists.hra as list_hra,
                 hr_employee_payroll_lists.da as list_da,
                 hr_employee_payroll_lists.allowance as list_allowance,	
                 hr_employee_payroll_lists.pf as list_pf_amount,
                 hr_employee_payroll_lists.esi as list_esi_amount,
                 hr_employee_payroll_lists.pt as list_pt_amount,
                 hr_employee_payroll_lists.gross_salary as list_gross_salary,
                 hr_employee_payroll_lists.net_salary as list_net_salary,
                 hr_employee_payroll_lists.loan_deduction as list_loan_deduction,
                 hr_employee_payroll_lists.total_days as totaldays,
                 hr_employee_payroll_lists.attendance_days as presentdays,
                 hr_employee_payroll_lists.el as taken_el,
                 hr_employee_payroll_lists.sl as taken_sl,
                 hr_employee_payroll_lists.cl as taken_cl,
                 hr_employee_payroll_lists.od as taken_od,
                 hr_employee_payroll_lists.partial_day as taken_partial_day,
                 hr_employee_payroll_lists.compensated_day as taken_compensated_day,
                 hr_employee_t.e_l as rem_el,
                 hr_employee_t.s_l as rem_sl,
                 hr_employee_t.c_l as rem_cl,
                 hr_employee_payroll_lists.id,
                 hr_employee_t.department,
                 hr_employee_payproposal.employee_type
                 FROM   hr_employee_payroll_lists
                 LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type WHERE  1 = 1 )v1  WHERE  1 = 1  $wh    ";

           $result1 = \DB::select( $download_SQL );

           //dd($result1);
       
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
       
    $result = \DB::select($SQL);
    $responce->rows[]='';
    $responce->rows=$result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;

    echo json_encode($responce);
    }

	public function employeepayreport1()
	{
		
		$company_id = Session::get('companyid');
		$wh='';
        $wh .= " and hr_employee_t.company_id=' $company_id' ";
		   $log_id=Session::get('emp_id');
         if($log_id!=1)
	 $wh .= " and hr_employee_t.employee_id=' $log_id' ";
         
        $query_result =\DB::select(" SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payroll_lists.month,
                 hr_employee_payroll_lists.year,
                 hr_employee_payroll_lists.date,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_payroll_lists.basic_salary as list_basic_salary,
                 hr_employee_payroll_lists.hra as list_hra,
                 hr_employee_payroll_lists.da as list_da,
                 hr_employee_payroll_lists.allowance as list_allowance,	
                 hr_employee_payroll_lists.pf as list_pf_amount,
                 hr_employee_payroll_lists.esi as list_esi_amount,
                 hr_employee_payroll_lists.pt as list_pt_amount,
                 hr_employee_payroll_lists.gross_salary as list_gross_salary,
                 hr_employee_payroll_lists.net_salary as list_net_salary,
                 hr_employee_payroll_lists.loan_deduction as list_loan_deduction,
                 hr_employee_payroll_lists.total_days as totaldays,
                 hr_employee_payroll_lists.attendance_days as presentdays,
                 hr_employee_payroll_lists.el as taken_el,
                 hr_employee_payroll_lists.sl as taken_sl,
                 hr_employee_payroll_lists.cl as taken_cl,
                 hr_employee_payroll_lists.od as taken_od,
                 hr_employee_payroll_lists.partial_day as taken_partial_day,
                 hr_employee_payroll_lists.compensated_day as taken_compensated_day,
                 hr_employee_t.e_l as rem_el,
                 hr_employee_t.s_l as rem_sl,
                 hr_employee_t.c_l as rem_cl,
                 hr_employee_payroll_lists.id,
                 hr_employee_t.department,
                 hr_employee_payproposal.employee_type
                 FROM   hr_employee_payroll_lists
                 LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type WHERE  1 = 1 $wh");
              
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

    if($v->pay_allowance!='' && $v->pay_allowance!=null){
			$array=json_decode($v->pay_allowance);
		     $query = \DB::table('m_allowance_tbl')->where('employee_type',$v->employee_type)->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
	    foreach($query as $k1=>$v1) {                   
            foreach($array as $e=>$r){    
                    if(isset($r->{$v1->allowance_id})){
                       $query_result[$k]->{$v1->allowance_name."0"}=$r->{$v1->allowance_id};  
                       break;
                    }
                       
            }
        }
    }
             
              if($v->list_allowance!='' && $v->list_allowance!=null){
			$array=json_decode($v->list_allowance);
		 $query = \DB::table('m_allowance_tbl')->where('employee_type',$v->employee_type)->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
	             foreach($query as $k1=>$v1) {                   
                     foreach($array as $e=>$r){    
                       if(isset($r->{$v1->allowance_id})){
                       $query_result[$k]->{$v1->allowance_name."1"}=$r->{$v1->allowance_id};  
                       break;
                       }
                       
                     }
                   }
             }
             unset($query_result[$k]->pay_allowance);
             unset($query_result[$k]->list_allowance);
		}
        }
           
	$this->data['result'] = json_encode($query_result);
        $html=[];
       $html[]=array('name'=> "id", 'label'=> " ID", 'width'=> 250, 'hidden'=> true);
       $html[]=array( 'name'=> "employee_number", 'label'=> "Employee Name", 'width'=> 250);
       $html[]=array('name'=> "department", 'label'=> "Department", 'width'=> 250);
       $html[]=array('name'=> "date", 'label'=> "Payroll Date", 'width'=> 250 ,'editable'=>true, 'formatter'=> 'date');
       $html[]=array('name'=> "month", 'label'=> "Month", 'width'=> 250);
       $html[]=array('name'=> "year", 'label'=> "Year", 'width'=> 250);
       $html[]=array('name'=> "pay_basic", 'label'=> "Basic", 'width'=> 250);
       $html[]=array('name'=> "pay_hra", 'label'=> "HRA", 'width'=> 250);
       $html[]=array('name'=> "pay_da", 'label'=> "DA",'editable'=>true, 'width'=> 250);
       
              $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
	           foreach($query as $k1=>$v1) {
                      $html[]=array('name'=>$v1->allowance_name."0", 'label'=> ucwords($v1->allowance_name), 'width'=> 250);
                   }
       $html[]=array('name'=> "pay_annual_allowance", 'label'=> "Annual Allowance", 'width'=> 250);
       $html[]=array( 'name'=> "pay_gratuity", 'label'=> "Gratuity", 'width'=> 250);
       $html[]=array('name'=> "pay_esi", 'label'=> "ESI", 'width'=> 250);
       $html[]=array('name'=> "pay_pf", 'label'=> "PF", 'width'=> 250);
       $html[]=array('name'=> "pay_pt", 'label'=> "PT", 'width'=> 250);
       $html[]=array('name'=> "pay_gross_pay", 'label'=> "Gross Salary", 'width'=> 250);
       $html[]=array('name'=> "pay_net_pay", 'label'=> "Net Pay", 'width'=> 250);
       $html[]=array('name'=> "pay_ctc_pay", 'label'=> "CTC", 'width'=> 250);
       $html[]=array('name'=> "totaldays", 'label'=> "Total Days", 'width'=> 250);
       $html[]=array('name'=> "presentdays", 'label'=> "Attendance Days", 'width'=> 250);
       $html[]=array('name'=> "list_basic_salary", 'label'=> "Basic", 'width'=> 250);
       $html[]=array( 'name'=> "list_hra", 'label'=> "HRA", 'width'=> 250);
       $html[]=array('name'=> "list_da", 'label'=> "DA", 'width'=> 250 );
         $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
	           foreach($query as $k1=>$v1) {
                      $html[]=array('name'=>$v1->allowance_name."1", 'label'=> ucwords($v1->allowance_name), 'width'=> 250);
                   }
       $html[]=array('name'=> "list_pf_amount", 'label'=> "PF Amount", 'width'=> 250);
       $html[]=array('name'=> "list_esi_amount", 'label'=> "ESI Amount", 'width'=> 250);
       $html[]=array('name'=> "list_pt_amount", 'label'=> "PT Amount", 'width'=> 250);
       $html[]=array('name'=> "pay_ctc_pay", 'label'=> "CTC", 'width'=> 250);
       $html[]=array('name'=> "list_gross_salary", 'label'=> "Gross Salary", 'width'=> 250);
       $html[]=array('name'=> "list_net_salary", 'label'=> "Net Salary", 'width'=> 250);
       $html[]=array('name'=> "list_loan_deduction", 'label'=> "Loan Deduction", 'width'=> 250);
       $html[]=array('name'=> "taken_cl", 'label'=> "Taken Casual Leave", 'width'=> 250);
       $html[]=array( 'name'=> "taken_el", 'label'=> "Taken Earn Leave", 'width'=> 250 );
       $html[]=array('name'=> "taken_sl", 'label'=> "Taken Sick Leave", 'width'=> 250);
       $html[]=array('name'=> "taken_od", 'label'=> "Taken ON-Duty", 'width'=> 250);
       $html[]=array('name'=> "taken_partial_day", 'label'=> "Partial Day", 'width'=> 250);
       $html[]=array('name'=> "taken_compensated_day", 'label'=> "Compensated Day", 'width'=> 250);
       $html[]=array( 'name'=> "rem_el", 'label'=> "Remaining Earn Leave", 'width'=> 250);
       $html[]=array('name'=> "rem_sl", 'label'=> "Remaining Sick Leave", 'width'=> 250);
       $html[]=array('name'=> "rem_cl", 'label'=> "Remaining Casual Leave", 'width'=> 250);
   
        $this->data['datacolumn'] = json_encode($html);
        return view('employeepayreport.employeepayreport',$this->data);
		
	}

    public function payreportgrid()
    {
        $company_id = Session::get('companyid');
        $wh='';
        $wh .= " and hr_employee_t.company_id=' $company_id' ";
        $log_id=Session::get('emp_id');
        if($log_id!=1)
           $wh .= " and hr_employee_t.employee_id=' $log_id' ";

       $query_result =\DB::select(" SELECT employeetype.lookup_code as employee_type_name,
           payrolltype.lookup_code as payroll_type_name,
           CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
           hr_employee_payroll_lists.month,
           hr_employee_payroll_lists.year,
           hr_employee_payroll_lists.date,
           hr_employee_payproposal.basic_pay as pay_basic,
           hr_employee_payproposal.hra as pay_hra,
           hr_employee_payproposal.da as pay_da,
           hr_employee_payproposal.allowance as pay_allowance,
           hr_employee_payproposal.annual_allowance as pay_annual_allowance,
           hr_employee_payproposal.gratuity as pay_gratuity,
           (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
           (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
           (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
           hr_employee_payproposal.gross_pay as pay_gross_pay,
           hr_employee_payproposal.net_pay as pay_net_pay,
           hr_employee_payproposal.ctc_pay as pay_ctc_pay,
           hr_employee_payroll_lists.basic_salary as list_basic_salary,
           hr_employee_payroll_lists.hra as list_hra,
           hr_employee_payroll_lists.da as list_da,
           hr_employee_payroll_lists.allowance as list_allowance, 
           hr_employee_payroll_lists.pf as list_pf_amount,
           hr_employee_payroll_lists.esi as list_esi_amount,
           hr_employee_payroll_lists.pt as list_pt_amount,
           hr_employee_payroll_lists.gross_salary as list_gross_salary,
           hr_employee_payroll_lists.net_salary as list_net_salary,
           hr_employee_payroll_lists.loan_deduction as list_loan_deduction,
           hr_employee_payroll_lists.total_days as totaldays,
           hr_employee_payroll_lists.attendance_days as presentdays,
           hr_employee_payroll_lists.el as taken_el,
           hr_employee_payroll_lists.sl as taken_sl,
           hr_employee_payroll_lists.cl as taken_cl,
           hr_employee_payroll_lists.od as taken_od,
           hr_employee_payroll_lists.partial_day as taken_partial_day,
           hr_employee_payroll_lists.compensated_day as taken_compensated_day,
           hr_employee_t.e_l as rem_el,
           hr_employee_t.s_l as rem_sl,
           hr_employee_t.c_l as rem_cl,
           hr_employee_payroll_lists.id,
           hr_employee_t.department,
           hr_employee_payproposal.employee_type
           FROM   hr_employee_payroll_lists
           LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
           LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
           LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
           LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type WHERE  1 = 1 $wh");

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

           if($v->pay_allowance!='' && $v->pay_allowance!=null){
            $array=json_decode($v->pay_allowance);
            $query = \DB::table('m_allowance_tbl')->where('employee_type',$v->employee_type)->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
            foreach($query as $k1=>$v1) {                   
                foreach($array as $e=>$r){    
                    if(isset($r->{$v1->allowance_id})){
                     $query_result[$k]->{$v1->allowance_name."0"}=$r->{$v1->allowance_id};  
                     break;
                 }
             }
         }
     }

     if($v->list_allowance!='' && $v->list_allowance!=null){
        $array=json_decode($v->list_allowance);
        $query = \DB::table('m_allowance_tbl')->where('employee_type',$v->employee_type)->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
        foreach($query as $k1=>$v1) {                   
           foreach($array as $e=>$r){    
             if(isset($r->{$v1->allowance_id})){
                 $query_result[$k]->{$v1->allowance_name."1"}=$r->{$v1->allowance_id};  
                 break;
             }

         }
     }
 }
 unset($query_result[$k]->pay_allowance);
 unset($query_result[$k]->list_allowance);
}
}

/*$this->data['result'] = json_encode($query_result);
$html=[];
$html[]=array('name'=> "id", 'label'=> " ID", 'width'=> 250, 'hidden'=> true);
$html[]=array( 'name'=> "employee_number", 'label'=> "Employee Name", 'width'=> 250);
$html[]=array('name'=> "department", 'label'=> "Department", 'width'=> 250);
$html[]=array('name'=> "date", 'label'=> "Payroll Date", 'width'=> 250 ,'editable'=>true, 'formatter'=> 'date');
$html[]=array('name'=> "month", 'label'=> "Month", 'width'=> 250);
$html[]=array('name'=> "year", 'label'=> "Year", 'width'=> 250);
$html[]=array('name'=> "pay_basic", 'label'=> "Basic", 'width'=> 250);
$html[]=array('name'=> "pay_hra", 'label'=> "HRA", 'width'=> 250);
$html[]=array('name'=> "pay_da", 'label'=> "DA",'editable'=>true, 'width'=> 250);

$query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
foreach($query as $k1=>$v1) {
  $html[]=array('name'=>$v1->allowance_name."0", 'label'=> ucwords($v1->allowance_name), 'width'=> 250);
}
$html[]=array('name'=> "pay_annual_allowance", 'label'=> "Annual Allowance", 'width'=> 250);
$html[]=array( 'name'=> "pay_gratuity", 'label'=> "Gratuity", 'width'=> 250);
$html[]=array('name'=> "pay_esi", 'label'=> "ESI", 'width'=> 250);
$html[]=array('name'=> "pay_pf", 'label'=> "PF", 'width'=> 250);
$html[]=array('name'=> "pay_pt", 'label'=> "PT", 'width'=> 250);
$html[]=array('name'=> "pay_gross_pay", 'label'=> "Gross Salary", 'width'=> 250);
$html[]=array('name'=> "pay_net_pay", 'label'=> "Net Pay", 'width'=> 250);
$html[]=array('name'=> "pay_ctc_pay", 'label'=> "CTC", 'width'=> 250);
$html[]=array('name'=> "totaldays", 'label'=> "Total Days", 'width'=> 250);
$html[]=array('name'=> "presentdays", 'label'=> "Attendance Days", 'width'=> 250);
$html[]=array('name'=> "list_basic_salary", 'label'=> "Basic", 'width'=> 250);
$html[]=array( 'name'=> "list_hra", 'label'=> "HRA", 'width'=> 250);
$html[]=array('name'=> "list_da", 'label'=> "DA", 'width'=> 250 );
$query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
foreach($query as $k1=>$v1) {
  $html[]=array('name'=>$v1->allowance_name."1", 'label'=> ucwords($v1->allowance_name), 'width'=> 250);
}
$html[]=array('name'=> "list_pf_amount", 'label'=> "PF Amount", 'width'=> 250);
$html[]=array('name'=> "list_esi_amount", 'label'=> "ESI Amount", 'width'=> 250);
$html[]=array('name'=> "list_pt_amount", 'label'=> "PT Amount", 'width'=> 250);
$html[]=array('name'=> "pay_ctc_pay", 'label'=> "CTC", 'width'=> 250);
$html[]=array('name'=> "list_gross_salary", 'label'=> "Gross Salary", 'width'=> 250);
$html[]=array('name'=> "list_net_salary", 'label'=> "Net Salary", 'width'=> 250);
$html[]=array('name'=> "list_loan_deduction", 'label'=> "Loan Deduction", 'width'=> 250);
$html[]=array('name'=> "taken_cl", 'label'=> "Taken Casual Leave", 'width'=> 250);
$html[]=array( 'name'=> "taken_el", 'label'=> "Taken Earn Leave", 'width'=> 250 );
$html[]=array('name'=> "taken_sl", 'label'=> "Taken Sick Leave", 'width'=> 250);
$html[]=array('name'=> "taken_od", 'label'=> "Taken ON-Duty", 'width'=> 250);
$html[]=array('name'=> "taken_partial_day", 'label'=> "Partial Day", 'width'=> 250);
$html[]=array('name'=> "taken_compensated_day", 'label'=> "Compensated Day", 'width'=> 250);
$html[]=array( 'name'=> "rem_el", 'label'=> "Remaining Earn Leave", 'width'=> 250);
$html[]=array('name'=> "rem_sl", 'label'=> "Remaining Sick Leave", 'width'=> 250);
$html[]=array('name'=> "rem_cl", 'label'=> "Remaining Casual Leave", 'width'=> 250);*/

/*$this->data['datacolumn'] = json_encode($html);
echo json_encode($html);*/
        

        
        echo json_encode($query_result);
        // return view('employeepayreport.employeepayreport',$this->data);

}

    public function employeepayreportindex()
    {
        $company_id=\Session::get('companyid');
        $html=[];
       $html[]=array('name'=> "id", 'label'=> " ID", 'width'=> 250, 'hidden'=> true);
       $html[]=array( 'dataIndx'=> "employee_number", 'title'=> "Employee Name", 'width'=> '15%');
       $html[]=array('dataIndx'=> "department", 'title'=> "Department", 'width'=> '15%');
       $html[]=array('dataIndx'=> "date", 'title'=> "Payroll Date", 'width'=> '15%' ,'editable'=>true, 'formatter'=> 'date');
       $html[]=array('dataIndx'=> "month", 'title'=> "Month", 'width'=> '15%');
       $html[]=array('dataIndx'=> "year", 'title'=> "Year", 'width'=> '15%');
       $html[]=array('dataIndx'=> "employee_type_name", 'title'=> "Employee Type", 'width'=> '15%');
       $html[]=array('dataIndx'=> "payroll_type_name", 'title'=> "Payroll Type", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_basic", 'title'=> "Basic", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_hra", 'title'=> "HRA", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_da", 'title'=> "DA",'editable'=>true, 'width'=> '15%');
       
              $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
               foreach($query as $k1=>$v1) {
                      $html[]=array('dataIndx'=>$v1->allowance_id."0", 'title'=> ucwords($v1->allowance_name), 'width'=> '15%');
                   }
       $html[]=array('dataIndx'=> "pay_annual_allowance", 'title'=> "Annual Allowance", 'width'=> '15%');
       $html[]=array( 'dataIndx'=> "pay_gratuity", 'title'=> "Gratuity", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_esi", 'title'=> "ESI", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_pf", 'title'=> "PF", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_pt", 'title'=> "PT", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_gross_pay", 'title'=> "Gross Salary", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_net_pay", 'title'=> "Net Pay", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_ctc_pay", 'title'=> "CTC", 'width'=> '15%');
       $html[]=array('dataIndx'=> "totaldays", 'title'=> "Total Days", 'width'=> '15%');
       $html[]=array('dataIndx'=> "presentdays", 'title'=> "Attendance Days", 'width'=> '15%');
       $html[]=array('dataIndx'=> "list_basic_salary", 'title'=> "Basic", 'width'=> '15%');
       $html[]=array( 'dataIndx'=> "list_hra", 'title'=> "HRA", 'width'=> '15%');
       $html[]=array('dataIndx'=> "list_da", 'title'=> "DA", 'width'=> '15%' );
         $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
               foreach($query as $k1=>$v1) {
                      $html[]=array('dataIndx'=>$v1->allowance_id."1", 'title'=> ucwords($v1->allowance_name), 'width'=> '15%');
                   }
       $html[]=array('dataIndx'=> "list_pf_amount", 'title'=> "PF Amount", 'width'=> '15%');
       $html[]=array('dataIndx'=> "list_esi_amount", 'title'=> "ESI Amount", 'width'=> '15%');
       $html[]=array('dataIndx'=> "list_pt_amount", 'title'=> "PT Amount", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_ctc_pay", 'title'=> "CTC", 'width'=> '15%');
       $html[]=array('dataIndx'=> "list_gross_salary", 'title'=> "Gross Salary", 'width'=> '15%');
       $html[]=array('dataIndx'=> "list_net_salary", 'title'=> "Net Salary", 'width'=> '15%');
       $html[]=array('dataIndx'=> "list_loan_deduction", 'title'=> "Loan Deduction", 'width'=> '15%');
       $html[]=array('dataIndx'=> "taken_cl", 'title'=> "Taken Casual Leave", 'width'=> '15%');
       $html[]=array( 'dataIndx'=> "taken_el", 'title'=> "Taken Earn Leave", 'width'=> '15%' );
       $html[]=array('dataIndx'=> "taken_sl", 'title'=> "Taken Sick Leave", 'width'=> '15%');
       $html[]=array('dataIndx'=> "taken_od", 'title'=> "Taken ON-Duty", 'width'=> '15%');
       $html[]=array('dataIndx'=> "taken_partial_day", 'title'=> "Partial Day", 'width'=> '15%');
       $html[]=array('dataIndx'=> "taken_compensated_day", 'title'=> "Compensated Day", 'width'=> '15%');
       $html[]=array( 'dataIndx'=> "rem_el", 'title'=> "Remaining Earn Leave", 'width'=> '15%');
       $html[]=array('dataIndx'=> "rem_sl", 'title'=> "Remaining Sick Leave", 'width'=> '15%');
       $html[]=array('dataIndx'=> "rem_cl", 'title'=> "Remaining Casual Leave", 'width'=> '15%');
   
        $this->data['datacolumn'] = json_encode($html);

    return view('employeepayreport.employeereport',$this->data);
    }


    public function employeepayreport()
    {
       // dd("hh");
         $wh='';
         
         /*Human Resources Marketing access control */
      $groupname=\Session::get('groupname');
      if($groupname == "16"){
          $wh.= " AND v2.payroll_type_name = 'Marketing'";
      }
         
            if(isset($_GET['pq_filter']))
                {
        $data=json_decode($_GET['pq_filter']);
        $data=$data->data;
         $wh.=$this->pqgridsearchsum('v2',$data);
        }
        //$table=array('hr_employee_t','hr_employee_payproposal','a_lookuplines_t','p_qc_lines_t','m_products_t','hr_employee_t');
         //       $data=$data->data;
       //  $wh.=$this->pqgridsearchsum('v1',$data);
      //  }
  //    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?  date("Y-m-d", strtotime($_GET['start_date'])) : '';
//    $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
      
      
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;

 $result = \DB::select("SELECT * FROM(SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payroll_lists.month,
                 hr_employee_payroll_lists.year,
                 hr_employee_payroll_lists.date,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_payroll_lists.basic_salary as list_basic_salary,
                 hr_employee_payroll_lists.hra as list_hra,
                 hr_employee_payroll_lists.da as list_da,
                 hr_employee_payroll_lists.allowance as list_allowance, 
                 hr_employee_payroll_lists.pf as list_pf_amount,
                 hr_employee_payroll_lists.esi as list_esi_amount,
                 hr_employee_payroll_lists.pt as list_pt_amount,
                 hr_employee_payroll_lists.gross_salary as list_gross_salary,
                 hr_employee_payroll_lists.net_salary as list_net_salary,
                 hr_employee_payroll_lists.loan_deduction as list_loan_deduction,
                 hr_employee_payroll_lists.total_days as totaldays,
                 hr_employee_payroll_lists.attendance_days as presentdays,
                 hr_employee_payroll_lists.el as taken_el,
                 hr_employee_payroll_lists.sl as taken_sl,
                 hr_employee_payroll_lists.cl as taken_cl,
                 hr_employee_payroll_lists.od as taken_od,
                 hr_employee_payroll_lists.partial_day as taken_partial_day,
                 hr_employee_payroll_lists.compensated_day as taken_compensated_day,
                 hr_employee_t.e_l as rem_el,
                 hr_employee_t.s_l as rem_sl,
                 hr_employee_t.c_l as rem_cl,
                 hr_employee_payroll_lists.id,
                 hr_employee_t.department
                 FROM   hr_employee_payroll_lists
                 LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type )v2 WHERE  1 = 1 $wh");
       
       $count = count($result);
       //dd($count);
     if( $count > 0 && $limit > 0)
    {
    $total_pages = ceil($count/$limit);
    } else {
    $total_pages = 0;
    }
    if ($page > $total_pages)
    $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;
         $loc=\Session::get('location');
          $compy=\Session::get('companyid');
         
        $query_result =\DB::select("select * FROM(SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payroll_lists.month,
                 hr_employee_payroll_lists.year,
                 hr_employee_payroll_lists.date,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_payroll_lists.basic_salary as list_basic_salary,
                 hr_employee_payroll_lists.hra as list_hra,
                 hr_employee_payroll_lists.da as list_da,
                 hr_employee_payroll_lists.allowance as list_allowance, 
                 hr_employee_payroll_lists.pf as list_pf_amount,
                 hr_employee_payroll_lists.esi as list_esi_amount,
                 hr_employee_payroll_lists.pt as list_pt_amount,
                 hr_employee_payroll_lists.gross_salary as list_gross_salary,
                 hr_employee_payroll_lists.net_salary as list_net_salary,
                 hr_employee_payroll_lists.loan_deduction as list_loan_deduction,
                 hr_employee_payroll_lists.total_days as totaldays,
                 hr_employee_payroll_lists.attendance_days as presentdays,
                 hr_employee_payroll_lists.el as taken_el,
                 hr_employee_payroll_lists.sl as taken_sl,
                 hr_employee_payroll_lists.cl as taken_cl,
                 hr_employee_payroll_lists.od as taken_od,
                 hr_employee_payroll_lists.partial_day as taken_partial_day,
                 hr_employee_payroll_lists.compensated_day as taken_compensated_day,
                 hr_employee_t.e_l as rem_el,
                 hr_employee_t.s_l as rem_sl,
                 hr_employee_t.c_l as rem_cl,
                 hr_employee_payroll_lists.id,
                 hr_employee_t.department
                 FROM   hr_employee_payroll_lists
                 LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type )v2 WHERE  1 = 1  $wh   LIMIT $start , $limit");
              // dd($query_result);

  $download =\DB::select("select * FROM(SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payroll_lists.month,
                 hr_employee_payroll_lists.year,
                 hr_employee_payroll_lists.date,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_payroll_lists.basic_salary as list_basic_salary,
                 hr_employee_payroll_lists.hra as list_hra,
                 hr_employee_payroll_lists.da as list_da,
                 hr_employee_payroll_lists.allowance as list_allowance, 
                 hr_employee_payroll_lists.pf as list_pf_amount,
                 hr_employee_payroll_lists.esi as list_esi_amount,
                 hr_employee_payroll_lists.pt as list_pt_amount,
                 hr_employee_payroll_lists.gross_salary as list_gross_salary,
                 hr_employee_payroll_lists.net_salary as list_net_salary,
                 hr_employee_payroll_lists.loan_deduction as list_loan_deduction,
                 hr_employee_payroll_lists.total_days as totaldays,
                 hr_employee_payroll_lists.attendance_days as presentdays,
                 hr_employee_payroll_lists.el as taken_el,
                 hr_employee_payroll_lists.sl as taken_sl,
                 hr_employee_payroll_lists.cl as taken_cl,
                 hr_employee_payroll_lists.od as taken_od,
                 hr_employee_payroll_lists.partial_day as taken_partial_day,
                 hr_employee_payroll_lists.compensated_day as taken_compensated_day,
                 hr_employee_t.e_l as rem_el,
                 hr_employee_t.s_l as rem_sl,
                 hr_employee_t.c_l as rem_cl,
                 hr_employee_payroll_lists.id,
                 hr_employee_t.department
                 FROM   hr_employee_payroll_lists
                 LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type )v2 WHERE  1 = 1  $wh  ");
              // dd($query_result);

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

    if($v->pay_allowance!='' && $v->pay_allowance!=null){
            $array=json_decode($v->pay_allowance);
             $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$compy)->get();

             foreach($query as $k1=>$v1) {                   
            foreach($array as $e=>$r){    
                    if(isset($r->{$v1->allowance_id})){
                     
                       $query_result[$k]->{$v1->allowance_id."0"}=$r->{$v1->allowance_id}; 
                       break;
                     
                    }else{
                      
                         $query_result[$k]->{$v1->allowance_id."0"}='';  
                    }
                       
            }
        }
    }

             
              if($v->list_allowance!='' && $v->list_allowance!=null){
            $array=json_decode($v->list_allowance);
         $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$compy)->get();
                 foreach($query as $k1=>$v1) {                   
                     foreach($array as $e=>$r){    
                       if(isset($r->{$v1->allowance_id})){
                       $query_result[$k]->{$v1->allowance_id."1"}=$r->{$v1->allowance_id};  
                          break;
                       }else{
                          $query_result[$k]->{$v1->allowance_id."1"}='';    
                       }
                       
                     }
                   }
             }
             unset($query_result[$k]->pay_allowance);
             unset($query_result[$k]->list_allowance);
        }
        } 
 if(count($download)>0){
         foreach($download  as $k=>$v  ){
             if($v->department!='' && $v->department!=null){
                    $array=json_decode($v->department);
                    $query = \DB::table('m_department_lines_t')->whereIn('department_line_id',$array)->get();
                    $deptnames = json_decode(json_encode($query), true);    
                    $download[$k]->department=implode(' , ',array_column($deptnames,'sub_department_name'));    
                     }
             else{
                 $download[$k]->department="";
                }

    if($v->pay_allowance!='' && $v->pay_allowance!=null){
            $array=json_decode($v->pay_allowance);
             $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$compy)->get();

             foreach($query as $k1=>$v1) {                   
            foreach($array as $e=>$r){    
                    if(isset($r->{$v1->allowance_id})){
                     
                       $download[$k]->{$v1->allowance_id."0"}=$r->{$v1->allowance_id}; 
                       break;
                     
                    }else{
                      
                         $download[$k]->{$v1->allowance_id."0"}='';  
                    }
                       
            }
        }
    }

             
              if($v->list_allowance!='' && $v->list_allowance!=null){
            $array=json_decode($v->list_allowance);
         $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$compy)->get();
                 foreach($query as $k1=>$v1) {                   
                     foreach($array as $e=>$r){    
                       if(isset($r->{$v1->allowance_id})){
                       $download[$k]->{$v1->allowance_id."1"}=$r->{$v1->allowance_id};  
                          break;
                       }else{
                          $download[$k]->{$v1->allowance_id."1"}='';    
                       }
                       
                     }
                   }
             }
             unset($download[$k]->pay_allowance);
             unset($download[$k]->list_allowance);
        }
        } 
         
      //dd($result1);
       
    if(isset($_GET['download']))
    {
    	$result1=$download;
    	 $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }
        $result = $query_result;
        $responce->rows[]='';
        $responce->data=$result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
      
        
    }

public function employeestdpayreportindex()
    {
        $company_id=\Session::get('companyid');
        $html=[];
       $html[]=array('name'=> "employee_id", 'label'=> " ID", 'width'=> 250, 'hidden'=> true);
       $html[]=array( 'dataIndx'=> "employee_number", 'title'=> "Employee Name", 'width'=> '15%');
       $html[]=array('dataIndx'=> "department", 'title'=> "Department", 'width'=> '15%');
       $html[]=array('dataIndx'=> "employee_type_name", 'title'=> "Payroll Type", 'width'=> '15%');
       $html[]=array('dataIndx'=> "payroll_type_name", 'title'=> "Employee Type", 'width'=> '15%');
       $html[]=array('dataIndx'=> "date_of_joining", 'title'=> "Date of Joining", 'width'=> '15%');
       $html[]=array('dataIndx'=> "effective_date", 'title'=> "Date of Effective", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_basic", 'title'=> "Basic", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_hra", 'title'=> "HRA", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_da", 'title'=> "DA",'editable'=>true, 'width'=> '15%');
       
              $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$company_id)->get();
               foreach($query as $k1=>$v1) {
                      $html[]=array('dataIndx'=>$v1->allowance_id."0", 'title'=> ucwords($v1->allowance_name), 'width'=> '15%');
                   }
       $html[]=array('dataIndx'=> "pay_annual_allowance", 'title'=> "Annual Allowance", 'width'=> '15%');
       $html[]=array( 'dataIndx'=> "pay_gratuity", 'title'=> "Gratuity", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_esi", 'title'=> "ESI", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_pf", 'title'=> "PF", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_pt", 'title'=> "PT", 'width'=> '15%');
       $html[]=array('dataIndx'=> "esi_amount", 'title'=> "ESI AMOUNT", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pf_amount", 'title'=> "PF AMOUNT", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pt_amount", 'title'=> "PT AMOUNT", 'width'=> '15%');
       $html[]=array('dataIndx'=> "volunter_pf", 'title'=> "Volunter PF AMOUNT", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_gross_pay", 'title'=> "Gross Salary", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_net_pay", 'title'=> "Net Pay", 'width'=> '15%');
       $html[]=array('dataIndx'=> "pay_ctc_pay", 'title'=> "CTC", 'width'=> '15%');
   
        $this->data['datacolumn'] = json_encode($html);

    return view('employeepayreport.employeestdpayreport',$this->data);
    }


public function employeestdpayreport()
    {
       // dd("hh");
         $wh='';
         
         /*Human Resources Marketing access control */
      $groupname=\Session::get('groupname');
      if($groupname == "16"){
          $wh.= " AND v2.payroll_type_name = 'Marketing'";
      }
         
            if(isset($_GET['pq_filter']))
                {
        $data=json_decode($_GET['pq_filter']);
        $data=$data->data;
         $wh.=$this->pqgridsearchsum('v2',$data);
        }
        //$table=array('hr_employee_t','hr_employee_payproposal','a_lookuplines_t','p_qc_lines_t','m_products_t','hr_employee_t');
         //       $data=$data->data;
       //  $wh.=$this->pqgridsearchsum('v1',$data);
      //  }
      
      
      
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;

 $result = \DB::select("SELECT * FROM(SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.pf_amount,
                 hr_employee_payproposal.esi_amount,
                 hr_employee_payproposal.pt_amount,
                 hr_employee_payproposal.volunter_pf,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_t.department,
                 hr_employee_t.date_of_joining,
                 hr_employee_payproposal.effective_date,
                 hr_employee_t.active,
                 hr_employee_t.employee_id
                 FROM   hr_employee_t
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_t.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type )v2 WHERE  1 = 1 AND v2.active = 'Yes' $wh");
       
       $count = count($result);
       //dd($count);
     if( $count > 0 && $limit > 0)
    {
    $total_pages = ceil($count/$limit);
    } else {
    $total_pages = 0;
    }
    if ($page > $total_pages)
    $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;
         $loc=\Session::get('location');
          $compy=\Session::get('companyid');
         
        $query_result =\DB::select("SELECT * FROM(SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                hr_employee_payproposal.pf_amount,
                 hr_employee_payproposal.esi_amount,
                 hr_employee_payproposal.pt_amount,
                 hr_employee_payproposal.volunter_pf,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_t.department,
                 hr_employee_t.date_of_joining,
                 hr_employee_payproposal.effective_date,
                 hr_employee_t.active,
                 hr_employee_t.employee_id
                 FROM   hr_employee_t
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_t.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type )v2 WHERE  1 = 1 AND v2.active = 'Yes' $wh   LIMIT $start , $limit");
              // dd($query_result);

  $download =\DB::select("SELECT * FROM(SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.pf_amount,
                 hr_employee_payproposal.esi_amount,
                 hr_employee_payproposal.pt_amount,
                 hr_employee_payproposal.volunter_pf,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_t.department,
                 hr_employee_t.date_of_joining,
                 hr_employee_payproposal.effective_date,
                 hr_employee_t.active,
                 hr_employee_t.employee_id
                 FROM   hr_employee_t
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_t.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type )v2 WHERE  1 = 1 AND v2.active = 'Yes' $wh  ");
              // dd($query_result);

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

    if($v->pay_allowance!='' && $v->pay_allowance!=null){
            $array=json_decode($v->pay_allowance);
             $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$compy)->get();

             foreach($query as $k1=>$v1) {                   
            foreach($array as $e=>$r){    
                    if(isset($r->{$v1->allowance_id})){
                     
                       $query_result[$k]->{$v1->allowance_id."0"}=$r->{$v1->allowance_id}; 
                       break;
                     
                    }else{
                      
                         $query_result[$k]->{$v1->allowance_id."0"}='';  
                    }
                       
            }
        }
    }

             
              
             unset($query_result[$k]->pay_allowance);
             unset($query_result[$k]->list_allowance);
        }
        } 
 if(count($download)>0){
         foreach($download  as $k=>$v  ){
             if($v->department!='' && $v->department!=null){
                    $array=json_decode($v->department);
                    $query = \DB::table('m_department_lines_t')->whereIn('department_line_id',$array)->get();
                    $deptnames = json_decode(json_encode($query), true);    
                    $download[$k]->department=implode(' , ',array_column($deptnames,'sub_department_name'));    
                     }
             else{
                 $download[$k]->department="";
                }

    if($v->pay_allowance!='' && $v->pay_allowance!=null){
            $array=json_decode($v->pay_allowance);
             $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$compy)->get();

             foreach($query as $k1=>$v1) {                   
            foreach($array as $e=>$r){    
                    if(isset($r->{$v1->allowance_id})){
                     
                       $download[$k]->{$v1->allowance_id."0"}=$r->{$v1->allowance_id}; 
                       break;
                     
                    }else{
                      
                         $download[$k]->{$v1->allowance_id."0"}='';  
                    }
                       
            }
        }
    }

             
              
             unset($download[$k]->pay_allowance);
             unset($download[$k]->list_allowance);
        }
        } 
         
      //dd($result1);
       
    if(isset($_GET['download']))
    {
    	$result1=$download;
    	 $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }
        $result = $query_result;
        $responce->rows[]='';
        $responce->data=$result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
      
        
    }

  public function attendancetablegriddata()
    { 
  
          $compy=\Session::get('companyid');  
         $logged_user = \Session::get('emp_id');
            
  
        $wh='';
        $wh .= " and v1.company_id=' $company_id' ";
        if($_GET['_search']=='true')
        {
        $wh.=$this->jqgridsearchnotab('v1',$_GET['filters']);
        }

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
        $result = \DB::select("select * from (SELECT
        hr_emp_attendence.atten_id,
        hr_emp_attendence.company_id,
        hr_emp_attendence.emp_id,
        hr_employee_t.biometric_empno as bio_id,
        hr_emp_attendence.atten_date,
        hr_emp_attendence.check_in,
        hr_emp_attendence.check_out,
        hr_emp_attendence.working_hours,
        hr_emp_attendence.ot,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as first_name,
       
      a_lookuplines_t.lookup_code
            FROM
        hr_emp_attendence
            LEFT JOIN hr_employee_t ON hr_employee_t.employee_number = hr_emp_attendence.emp_id
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_emp_attendence.atten_type) v1 where 1=1 $wh GROUP BY v1.atten_id");
          $count = count($result);
        if($limit==0)
            $limit=$count;
        
        if( $count > 0 && $limit > 0)
        {
        $total_pages = ceil($count/$limit);
        } else {
        $total_pages = 0;
        }
        if ($page > $total_pages)
        $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;
         $comp=\Session::get('companyid');
    $SQL = "select * from (SELECT
        hr_emp_attendence.atten_id,

        hr_emp_attendence.company_id,
        hr_emp_attendence.emp_id,
        hr_employee_t.biometric_empno as bio_id,
        hr_emp_attendence.atten_date,
        hr_emp_attendence.check_in,
        hr_emp_attendence.check_out,
        hr_emp_attendence.working_hours,
        hr_emp_attendence.ot,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as first_name,
       
      a_lookuplines_t.lookup_code
            FROM
        hr_emp_attendence
            LEFT JOIN hr_employee_t ON hr_employee_t.employee_number = hr_emp_attendence.emp_id
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_emp_attendence.atten_type)v1 where 1=1  $wh ORDER by v1.atten_id $sord LIMIT $start , $limit";
    
    
      $download_SQL = "select * from (SELECT
        hr_emp_attendence.atten_id,
        hr_emp_attendence.emp_id,

        hr_emp_attendence.company_id,
        hr_employee_t.biometric_empno as bio_id,
        hr_emp_attendence.atten_date,
        hr_emp_attendence.check_in,
        hr_emp_attendence.check_out,
        hr_emp_attendence.working_hours,
        hr_emp_attendence.ot,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as first_name,
       
      a_lookuplines_t.lookup_code
            FROM
        hr_emp_attendence
            LEFT JOIN hr_employee_t ON hr_employee_t.employee_number = hr_emp_attendence.emp_id
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_emp_attendence.atten_type)v1 where 1=1 $wh ORDER by v1.atten_id $sord";
         $result1 = \DB::select( $download_SQL );
       
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
        
        $result = \DB::select( $SQL );
        
    
        // dd($SQL);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        
        echo json_encode($responce);
    }
        

        //ajimaa

     public function esireportgriddata()
    { 
     //dd($_GET['filters']);

         $logged_user = \Session::get('emp_id');
            
       $company_id = Session::get('companyid');
        $wh='';
      // $wh .= " and v1.company_conribute_id=' $logged_user' ";
        if($_GET['_search']=='true')
        {
        $wh.=$this->jqgridsearchnotab('v1',$_GET['filters']);
        }

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
        $result = \DB::select("select * from (SELECT hr_company_contribute_esi.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM   hr_company_contribute_esi  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_esi.emp_id ) v1 where 1=1 $wh GROUP BY v1.company_conribute_id");
          $count = count($result);
        if($limit==0)
            $limit=$count;
        
        if( $count > 0 && $limit > 0)
        {
        $total_pages = ceil($count/$limit);
        } else {
        $total_pages = 0;
        }
        if ($page > $total_pages)
        $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;
         $comp=\Session::get('companyid');
    $SQL = "select * from (SELECT hr_company_contribute_esi.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM   hr_company_contribute_esi  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_esi.emp_id )v1 where 1=1  $wh ORDER by v1.company_conribute_id $sord LIMIT $start , $limit";
    
    
      $download_SQL = "select * from (SELECT hr_company_contribute_esi.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM   hr_company_contribute_esi  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_esi.company_conribute_id )v1 where 1=1 $wh ORDER by v1.company_conribute_id $sord";
         $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
        
        $result = \DB::select( $SQL );
        
    
        // dd($SQL);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        
        echo json_encode($responce);
    }

    //aaaajima


      public function pfreportgirddata()
    { 
     //dd($_GET['filters']);
         $logged_user = \Session::get('emp_id');
            
       $company_id = Session::get('companyid');
        $wh='';
      
       
        if($_GET['_search']=='true')
        {
        $wh.=$this->jqgridsearchnotab('v1',$_GET['filters']);
        }

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
        $result = \DB::select("select * from (SELECT hr_company_contribute_pfhr_company_contribute_pf.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.pf_no FROM   hr_company_contribute_pf  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_pf.emp_id  ) v1 where 1=1 $wh GROUP BY v1.employee_conribute_id");
          $count = count($result);
        if($limit==0)
            $limit=$count;
        
        if( $count > 0 && $limit > 0)
        {
        $total_pages = ceil($count/$limit);
        } else {
        $total_pages = 0;
        }
        if ($page > $total_pages)
        $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;
         $comp=\Session::get('companyid');
    $SQL = "select * from (SELECT hr_company_contribute_pf.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.pf_no FROM   hr_company_contribute_pf  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_pf.emp_id   )v1 where 1=1  $wh ORDER by v1.employee_conribute_id $sord LIMIT $start , $limit";
    
    
      $download_SQL = "select * from (SELECT hr_company_contribute_pf.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.pf_no FROM   hr_company_contribute_pf  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_pf.emp_id   )v1 where 1=1 $wh ORDER by v1.employee_conribute_id $sord";
         $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
        
        $result = \DB::select( $SQL );
        
    
        // dd($SQL);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        
        echo json_encode($responce);
    }
   
   
       public function ptreportindex()
    {
       $company_id=\Session::get('companyid');
       $html=[];
       $html[]=array('name'=> "id", 'label'=> " ID", 'width'=> 250, 'hidden'=> true);
       $html[]=array( 'dataIndx'=> "employee_number", 'title'=> "Employee Name", 'width'=> '20%');
       $html[]=array('dataIndx'=> "department", 'title'=> "Department", 'width'=> '20%');
       
       $html[]=array('dataIndx'=> "month", 'title'=> "Month", 'width'=> '20%');
       $html[]=array('dataIndx'=> "year", 'title'=> "Year", 'width'=> '20%');
       
      
     
      
       $html[]=array('dataIndx'=> "list_pt_amount", 'title'=> "PT Amount", 'width'=> '20%');
      
   
        $this->data['datacolumn'] = json_encode($html);

    return view('employeepayreport.ptreport',$this->data);
    }


    public function ptreport()
    {
         $wh='';
            if(isset($_GET['pq_filter']))
                {
        $data=json_decode($_GET['pq_filter']);
        $data=$data->data;
         $wh.=$this->pqgridsearchsum('v2',$data);
        }
        //$table=array('hr_employee_t','hr_employee_payproposal','a_lookuplines_t','p_qc_lines_t','m_products_t','hr_employee_t');
         //       $data=$data->data;
       //  $wh.=$this->pqgridsearchsum('v1',$data);
      //  }
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;

 $result = \DB::select("SELECT * FROM(SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payroll_lists.month,
                 hr_employee_payroll_lists.year,
                 hr_employee_payroll_lists.date,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_payroll_lists.basic_salary as list_basic_salary,
                 hr_employee_payroll_lists.hra as list_hra,
                 hr_employee_payroll_lists.da as list_da,
                 hr_employee_payroll_lists.allowance as list_allowance, 
                 hr_employee_payroll_lists.pf as list_pf_amount,
                 hr_employee_payroll_lists.esi as list_esi_amount,
                 hr_employee_payroll_lists.pt as list_pt_amount,
                 hr_employee_payroll_lists.gross_salary as list_gross_salary,
                 hr_employee_payroll_lists.net_salary as list_net_salary,
                 hr_employee_payroll_lists.loan_deduction as list_loan_deduction,
                 hr_employee_payroll_lists.total_days as totaldays,
                 hr_employee_payroll_lists.attendance_days as presentdays,
                 hr_employee_payroll_lists.el as taken_el,
                 hr_employee_payroll_lists.sl as taken_sl,
                 hr_employee_payroll_lists.cl as taken_cl,
                 hr_employee_payroll_lists.od as taken_od,
                 hr_employee_payroll_lists.partial_day as taken_partial_day,
                 hr_employee_payroll_lists.compensated_day as taken_compensated_day,
                 hr_employee_t.e_l as rem_el,
                 hr_employee_t.s_l as rem_sl,
                 hr_employee_t.c_l as rem_cl,
                 hr_employee_payroll_lists.id,
                 hr_employee_t.department,
                 hr_employee_payproposal.employee_type
                 FROM   hr_employee_payroll_lists
                 LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type )v2 WHERE  1 = 1 $wh");
       
       $count = count($result);
       //dd($count);
     if( $count > 0 && $limit > 0)
    {
    $total_pages = ceil($count/$limit);
    } else {
    $total_pages = 0;
    }
    if ($page > $total_pages)
    $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;
         $loc=\Session::get('location');
          $compy=\Session::get('companyid');
         
        $query_result =\DB::select("select * FROM(SELECT employeetype.lookup_code as employee_type_name,
                 payrolltype.lookup_code as payroll_type_name,
                 CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
                 hr_employee_payroll_lists.month,
                 hr_employee_payroll_lists.year,
                 hr_employee_payroll_lists.date,
                 hr_employee_payproposal.basic_pay as pay_basic,
                 hr_employee_payproposal.hra as pay_hra,
                 hr_employee_payproposal.da as pay_da,
                 hr_employee_payproposal.allowance as pay_allowance,
                 hr_employee_payproposal.annual_allowance as pay_annual_allowance,
                 hr_employee_payproposal.gratuity as pay_gratuity,
                (CASE WHEN hr_employee_payproposal.esi=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
                (CASE WHEN hr_employee_payproposal.pf=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
                (CASE WHEN hr_employee_payproposal.pt=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
                 hr_employee_payproposal.gross_pay as pay_gross_pay,
                 hr_employee_payproposal.net_pay as pay_net_pay,
                 hr_employee_payproposal.ctc_pay as pay_ctc_pay,
                 hr_employee_payroll_lists.basic_salary as list_basic_salary,
                 hr_employee_payroll_lists.hra as list_hra,
                 hr_employee_payroll_lists.da as list_da,
                 hr_employee_payroll_lists.allowance as list_allowance, 
                 hr_employee_payroll_lists.pf as list_pf_amount,
                 hr_employee_payroll_lists.esi as list_esi_amount,
                 hr_employee_payroll_lists.pt as list_pt_amount,
                 hr_employee_payroll_lists.gross_salary as list_gross_salary,
                 hr_employee_payroll_lists.net_salary as list_net_salary,
                 hr_employee_payroll_lists.loan_deduction as list_loan_deduction,
                 hr_employee_payroll_lists.total_days as totaldays,
                 hr_employee_payroll_lists.attendance_days as presentdays,
                 hr_employee_payroll_lists.el as taken_el,
                 hr_employee_payroll_lists.sl as taken_sl,
                 hr_employee_payroll_lists.cl as taken_cl,
                 hr_employee_payroll_lists.od as taken_od,
                 hr_employee_payroll_lists.partial_day as taken_partial_day,
                 hr_employee_payroll_lists.compensated_day as taken_compensated_day,
                 hr_employee_t.e_l as rem_el,
                 hr_employee_t.s_l as rem_sl,
                 hr_employee_t.c_l as rem_cl,
                 hr_employee_payroll_lists.id,
                 hr_employee_t.department,
                 hr_employee_payproposal.employee_type
                 FROM   hr_employee_payroll_lists
                 LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
                 LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type )v2 WHERE  1 = 1 $wh");
              // dd($query_result);


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

    if($v->pay_allowance!='' && $v->pay_allowance!=null){
            $array=json_decode($v->pay_allowance);
             $query = \DB::table('m_allowance_tbl')->where('employee_type',$v->employee_type)->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$compy)->get();
        foreach($query as $k1=>$v1) {                   
            foreach($array as $e=>$r){    
                    if(isset($r->{$v1->allowance_id})){
                       $query_result[$k]->{$v1->allowance_id."0"}=$r->{$v1->allowance_id};  
                       break;
                    }
                       
            }
        }
    }
             
              if($v->list_allowance!='' && $v->list_allowance!=null){
            $array=json_decode($v->list_allowance);
         $query = \DB::table('m_allowance_tbl')->where('employee_type',$v->employee_type)->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$compy)->get();
                 foreach($query as $k1=>$v1) {                   
                     foreach($array as $e=>$r){    
                       if(isset($r->{$v1->allowance_id})){
                       $query_result[$k]->{$v1->allowance_id."1"}=$r->{$v1->allowance_id};  
                       break;
                       }
                       
                     }
                   }
             }
             unset($query_result[$k]->pay_allowance);
             unset($query_result[$k]->list_allowance);
        }
        } 
    $result1=collect($query_result)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
           
        $result = $query_result;
        $responce->rows[]='';
        $responce->data=$result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
        // dd($responce);
        
    }  
    

        
   
}
