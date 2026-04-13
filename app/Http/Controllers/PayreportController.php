<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\payreport;
use App\Applicationviewers;
use Session;
use DateTime, DB;
use Illuminate\Http\Request;

class PayreportController extends Controller
{
  public function __construct()
  {
    $this->data = array();
    $this->data['pageModule'] = \Request::route()->getName();
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['urlmenu'] = $this->indexs();
  }

  public function employeedetailindex(Request $request)
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

    $company_id = \Session::get('companyid');
    return view('employeepayreport.employeedetailreport', $this->data);
  }

  public function hrmsallowancesettingsrpt(Request $request)
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

    $company_id = \Session::get('companyid');
    return view('employeepayreport.hrmsallowancesettingsrpt', $this->data);
  }

  public function employeedocumentindex(Request $request)
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

    $company_id = \Session::get('companyid');
    return view('employeepayreport.employeedocumentupload', $this->data);
  }

  public function getemployeedetail(Request $request)
  {

    $wh = '';

    /*Human Resources Marketing access control */
    $groupname = \Session::get('groupname');

    if ($groupname == "16" || $groupname == "11" || $groupname == "13" || $groupname == "5" || $groupname == "14") {
      
      $wh = " AND (v2.emp_type='Marketing' OR v2.emp_type='Business Associate' OR v2.emp_type='Commission Based' OR v2.grp_type='Marketing Personnel')";

    }


    $query_result = \DB::select("select * FROM(SELECT
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
( CASE WHEN hr_employee_t.zone_id = 16 THEN 'East'
            
            WHEN hr_employee_t.zone_id = 18 THEN 'North'
            WHEN hr_employee_t.zone_id = 19 THEN 'Corporate'
            WHEN hr_employee_t.zone_id = 27 THEN 'Central West'
            WHEN hr_employee_t.zone_id = 24 THEN 'South 2'
            WHEN hr_employee_t.zone_id = 25 THEN 'South 1'
      END) AS zone_name,
hr_employee_t.active,
hr_employee_t.date_of_leaving,
( CASE WHEN hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NULL THEN 'In Service'
            WHEN hr_employee_t.active = 'No' AND hr_employee_t.date_of_leaving IS NOT NULL THEN 'Resigned'
            WHEN hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NOT NULL THEN 'F & F Pending'
      END) AS ff_status, 
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
DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),hr_emp_personal.date_of_birth)), '%Y')+0 AS age,
hr_emp_personal.age as join_age,
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

    if (count($query_result) > 0) {
      foreach ($query_result as $k => $v) {
        if ($v->department != '' && $v->department != null) {
          $array = json_decode($v->department);
          $query = \DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
          $deptnames = json_decode(json_encode($query), true);
          $query_result[$k]->department_name = implode(' , ', array_column($deptnames, 'sub_department_name'));
        } else {
          $query_result[$k]->department_name = "";
        }

        if ($v->location_id != '' && $v->location_id != null) {
          $array = json_decode($v->location_id);
          $query = \DB::table('m_location_t')->whereIn('location_id', $array)->get();
          $deptnames = json_decode(json_encode($query), true);
          $query_result[$k]->location_name = implode(' , ', array_column($deptnames, 'location_name'));
        } else {
          $query_result[$k]->location_name = "";
        }

        if ($v->med_rep_area != '' && $v->med_rep_area != null) {
          $array = json_decode($v->med_rep_area);
          $query = \DB::table('m_area_t')->whereIn('area_id', $array)->get();
          $deptnames = json_decode(json_encode($query), true);
          $query_result[$k]->area_name = implode(' , ', array_column($deptnames, 'area_name'));
        } else {
          $query_result[$k]->area_name = "";
        }

      }
    }

    return DataTables::of($query_result)->make(true);

  }


  public function gethrmsallowancesettingsrpt(Request $request)
  {

    if ($request->ajax()) {
      $data = \DB::table('f_hr_account_allowance_setting_t')
        ->leftJoin('f_hr_account_allowance_setting_lines_t', 'f_hr_account_allowance_setting_t.account_allowance_setting_id', '=', 'f_hr_account_allowance_setting_lines_t.account_allowance_setting_id')
        ->leftJoin('m_department_lines_t', 'f_hr_account_allowance_setting_t.department_id', '=', 'm_department_lines_t.department_line_id')
        ->leftJoin('m_allowance_tbl', 'f_hr_account_allowance_setting_lines_t.allowance_id', '=', 'm_allowance_tbl.allowance_id')
        ->leftJoin('f_account_structure_t', 'f_hr_account_allowance_setting_lines_t.account_structure_id', '=', 'f_account_structure_t.f_account_structure_id')
        ->leftJoin('a_lookuplines_t', 'm_allowance_tbl.employee_type', '=', 'a_lookuplines_t.lookuplines_id')


        ->select([
          'f_hr_account_allowance_setting_t.account_allowance_setting_id',
          'm_department_lines_t.sub_department_name',
          'm_allowance_tbl.allowance_name',
          'm_allowance_tbl.type',
          'a_lookuplines_t.lookup_code',
          'f_hr_account_allowance_setting_lines_t.account_structure_id',
          'f_account_structure_t.concatenated_segments'
        ]);


      return DataTables::of($data)

        ->rawColumns(['actions'])
        ->make(true);
    }
  }


  public function getemployeedocument(Request $request)
  {


    $query_result = \DB::select("SELECT
v.*
FROM
(SELECT 
t.*,

(SELECT 
        m_company_check_list.document

        FROM m_company_check_list

        WHERE m_company_check_list.id=t.co_docs_num   

    ) as co_doc_name,

(SELECT 
        m_employee_doc_check_list.emp_document

        FROM m_employee_doc_check_list

        WHERE m_employee_doc_check_list.id=t.emp_docs_num   

    ) as emp_doc_name,
 
 (SELECT 
        hr_employee_t.first_name

        FROM hr_employee_t

        WHERE hr_employee_t.employee_id=t.created_by 

    ) as creaed_by


FROM
(
SELECT
    
    hr_employee_t.employee_id,
    hr_employee_t.employee_number,
    hr_employee_t.first_name,
    hr_employee_document_tbl.doc_field,
    hr_employee_document_tbl.emp_photo,
    hr_employee_document_tbl.emp_doc,
    date(hr_employee_document_tbl.created_at) as created_on,
    date(hr_employee_document_tbl.updated_at) as last_update,
    hr_employee_document_tbl.created_by as created_by,
    hr_employee_t.active,
    hr_employee_t.zone_id,
    hr_employee_t.date_of_leaving,

replace(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX( SUBSTRING_INDEX( hr_employee_document_tbl.doc_field, ',', m_products_t.product_id ), ',', -1 ),'[',-1),']',-1),'\"','') as co_docs_num,
    
replace(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX( SUBSTRING_INDEX( hr_employee_document_tbl.emp_photo, ',', m_products_t.product_id ), ',', -1 ),'[',-1),']',-1),'\"','') as emp_docs_num,

replace(replace((CASE 
    WHEN replace(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX( SUBSTRING_INDEX( hr_employee_document_tbl.emp_photo, ',', m_products_t.product_id ), ',', -1 ),'[',-1),']',-1),'\"','') = 0 THEN ''
    ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(emp_photo, ',', m_products_t.product_id+1), ',', -1)
  END),'\]',''),'\"','') AS docs_col
    
FROM hr_employee_document_tbl join hr_employee_t ON (hr_employee_document_tbl.employee_number=hr_employee_t.employee_id)

JOIN m_products_t ON CHAR_LENGTH(
       concat(hr_employee_document_tbl.doc_field,hr_employee_document_tbl.emp_photo)
    ) - CHAR_LENGTH(
    REPLACE
        (
            concat(hr_employee_document_tbl.doc_field,hr_employee_document_tbl.emp_photo),
            ',',
            ''
        )
) >= m_products_t.product_id -1

WHERE 1=1 ) as t

) as v WHERE (v.co_docs_num > 0 or v.emp_docs_num > 0) ORDER BY employee_number");


    return DataTables::of($query_result)->make(true);

  }

  // attendance report function
  public function attendancereport_index(Request $request)
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

    $company_id = Session::get('companyid');
    $wh = '';
    $wh .= " and hr_emp_attendence.company_id=' $company_id' ";
    $log_id = Session::get('emp_id');
    if ($log_id != 1 && $log_id != 315 && $log_id != 152) {
      $data = \DB::SELECT("select employee_number from hr_employee_t where employee_id='" . $log_id . "'");
      $wh .= " and hr_emp_attendence.emp_id='" . $data[0]->employee_number . "'";
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
    return view('payproposal.attenreporttable', $this->data);
  }



  public function getattendancereportdata(Request $request)
  {

    $company_id = Session::get('companyid');
    $wh = '';

    $log_id = Session::get('emp_id');
    if ($log_id != 1 && $log_id != 315 && $log_id != 302 && $log_id != 152 && $log_id != 600 && $log_id != 151 && $log_id != 160) {
      $data = \DB::SELECT("select employee_number from hr_employee_t where employee_id='" . $log_id . "'");
      $wh = " and v1.emp_id='" . $data[0]->employee_number . "'";
    }

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "select * from (SELECT 
        '' as atten_id,
        date_format(d.dt, '%b') as month,       
        m.employee_number as emp_id,
        m.biometric_empno as bio_id,
        d.dt as atten_date,
        date_format(d.dt, '%a') as day,
        '00:00' as check_in,
        '00:00' as check_out,
        '' as working_hours,
        '00:00' as wrk_hrs,
        '00:00' as early_in,
        '00:00' as late_in,
        '00:00' as early_out,
        '00:00' as late_out,
        '' as ot,
        m.first_name as first_name,                                                     
        a.lookup_code as dept,
        m_job_title.job_title_name as position,
        'Absent' as status,
        '00:00' as off_hrs,
        '00:00' as ovr_time,  
        '00:00' as loss_hrs,
        '00:00' as mrng_EP,
        '00:00' as evng_EP,
        '00:00' as nght_EP,
        '0' as mrng_ep_val,
        '0' as evng_ep_val,
        '0' as nght_ep_val,
        '0' as tot_ep_val
   FROM 
  ( SELECT DATE(t.atten_date) AS dt
           FROM hr_emp_attendence t
          WHERE t.atten_date >= '$start_date' 
            AND t.atten_date < DATE_ADD( '$end_date' ,INTERVAL 1 DAY)
          GROUP BY DATE(t.atten_date)
          ORDER BY DATE(t.atten_date)
       ) d
 CROSS JOIN hr_employee_t m
  LEFT
  JOIN  hr_emp_attendence p
    ON p.atten_date >= d.dt
   AND p.atten_date <  d.dt + INTERVAL 1 DAY
   AND p.emp_id = m.employee_number
   LEFT 
   JOIN hr_leaves_t ON `hr_leaves_t`.employee_id = m.employee_id AND hr_leaves_t.start_date <= '$start_date' AND hr_leaves_t.end_date >= '$end_date' AND hr_leaves_t.leave_status = 'APPROVE'               
  LEFT 
  JOIN a_lookuplines_t a ON
    a.lookuplines_id = m.employee_type
  LEFT
  JOIN m_job_title ON
  m_job_title.job_title_id=m.job_title     
 WHERE p.emp_id IS NULL AND m.group_type!='14' AND m.active='yes' AND (a.lookup_code LIKE '%contract%' OR a.lookup_code LIKE '%staff%' OR a.lookup_code LIKE '%board%')

UNION ALL
               
(select *,time_format(subtime(subtime(wrk_hrs,early_in),late_out),'%H:%i') as off_hrs,time_format(subtime(wrk_hrs,subtime(subtime(wrk_hrs,early_in),late_out)),'%H:%i') as ovr_time,time_format(addtime(early_out,late_in),'%H:%i') as loss_hrs,
 time_format(if(early_in>time_format('02:00','%H:%i'),time_format(addtime(subtime('04:00',early_in),early_in),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') as mrng_EP,
 time_format(if((late_out < time_format('03:00','%H:%i') AND late_out > time_format('01:00', '%H:%i')),time_format(addtime(subtime('02:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') AS evng_EP, 
 time_format(if((late_out > time_format('03:00','%H:%i') AND late_out < time_format('05:00', '%H:%i')),time_format(addtime(subtime('04:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') AS nght_EP,

 if(time_format(if(early_in>time_format('02:00','%H:%i'),time_format(addtime(subtime('04:00',early_in),early_in),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'associate' OR position = 'Junior Officer' OR position = 'Technician' OR position = 'Junior Technician'), (time_format(if(early_in>time_format('02:00','%H:%i'),time_format(addtime(subtime('04:00',early_in),early_in),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i')* 50), if(time_format(if(early_in>time_format('02:00','%H:%i'),time_format(addtime(subtime('04:00',early_in),early_in),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'Senior Technician' OR position = 'Assistant Manager' OR position = 'Officer' OR position = 'Senior Officer'),(time_format(if(early_in>time_format('02:00','%H:%i'),time_format(addtime(subtime('04:00',early_in),early_in),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i')* 100),0)) as mrng_ep_val,

if(time_format(if((late_out < time_format('03:00','%H:%i') AND late_out > time_format('01:00', '%H:%i')),time_format(addtime(subtime('02:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'associate' OR position = 'Junior Officer' OR position = 'Technician' OR position = 'Junior Technician'),(time_format(if((late_out < time_format('03:00','%H:%i') AND late_out > time_format('01:00', '%H:%i')),time_format(addtime(subtime('02:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i')*50),if(time_format(if((late_out < time_format('03:00','%H:%i') AND late_out > time_format('01:00', '%H:%i')),time_format(addtime(subtime('02:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'Senior Technician' OR position = 'Assistant Manager' OR position = 'Officer' OR position = 'Senior Officer'), (time_format(if((late_out < time_format('03:00','%H:%i') AND late_out > time_format('01:00', '%H:%i')),time_format(addtime(subtime('02:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i')*100),0)) as evng_ep_val,
 
if(time_format(if((late_out > time_format('03:00','%H:%i') AND late_out < time_format('05:00', '%H:%i')),time_format(addtime(subtime('04:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'associate' OR position = 'Junior Officer' OR position = 'Technician' OR position = 'Junior Technician'),(1*350),if(time_format(if((late_out > time_format('03:00','%H:%i') AND late_out < time_format('05:00', '%H:%i')),time_format(addtime(subtime('04:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'Assistant Manager' OR position = 'Officer' OR position = 'Senior Officer'), (time_format(if((late_out > time_format('03:00','%H:%i') AND late_out < time_format('05:00', '%H:%i')),time_format(addtime(subtime('04:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i')*100),if(time_format(if((late_out > time_format('03:00','%H:%i') AND late_out < time_format('05:00', '%H:%i')),time_format(addtime(subtime('04:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND position = 'Senior Technician', (1*450),0))) as nght_ep_val,
( if(time_format(if(early_in>time_format('02:00','%H:%i'),time_format(addtime(subtime('04:00',early_in),early_in),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'associate' OR position = 'Junior Officer' OR position = 'Technician' OR position = 'Junior Technician'), (time_format(if(early_in>time_format('02:00','%H:%i'),time_format(addtime(subtime('04:00',early_in),early_in),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i')* 50), if(time_format(if(early_in>time_format('02:00','%H:%i'),time_format(addtime(subtime('04:00',early_in),early_in),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'Senior Technician' OR position = 'Assistant Manager'  OR position = 'Officer' OR position = 'Senior Officer'),(time_format(if(early_in>time_format('02:00','%H:%i'),time_format(addtime(subtime('04:00',early_in),early_in),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i')* 100),0)) + if(time_format(if((late_out < time_format('03:00','%H:%i') AND late_out > time_format('01:00', '%H:%i')),time_format(addtime(subtime('02:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'associate' OR position = 'Junior Officer' OR position = 'Technician' OR position = 'Junior Technician'),(time_format(if((late_out < time_format('03:00','%H:%i') AND late_out > time_format('01:00', '%H:%i')),time_format(addtime(subtime('02:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i')*50),if(time_format(if((late_out < time_format('03:00','%H:%i') AND late_out > time_format('01:00', '%H:%i')),time_format(addtime(subtime('02:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'Senior Technician'  OR position = 'Officer' OR position = 'Assistant Manager' OR position = 'Senior Officer'), (time_format(if((late_out < time_format('03:00','%H:%i') AND late_out > time_format('01:00', '%H:%i')),time_format(addtime(subtime('02:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i')*100),0)) + if(time_format(if((late_out > time_format('03:00','%H:%i') AND late_out < time_format('05:00', '%H:%i')),time_format(addtime(subtime('04:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'associate' OR position = 'Junior Officer' OR position = 'Technician' OR position = 'Junior Technician'),(1*350),if(time_format(if((late_out > time_format('03:00','%H:%i') AND late_out < time_format('05:00', '%H:%i')),time_format(addtime(subtime('04:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND (position = 'Assistant Manager'  OR position = 'Officer' OR position = 'Senior Officer'), (time_format(if((late_out > time_format('03:00','%H:%i') AND late_out < time_format('05:00', '%H:%i')),time_format(addtime(subtime('04:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i')*100),if(time_format(if((late_out > time_format('03:00','%H:%i') AND late_out < time_format('05:00', '%H:%i')),time_format(addtime(subtime('04:00', late_out),late_out),'%H:00'),time_format(time(0),'%H:%i')),'%H:%i') > time_format(time(0),'%H:%i') AND position = 'Senior Technician', (1*450),0)))) as tot_ep_val
 
 from(SELECT
        
        hr_emp_attendence.atten_id,
        date_format(hr_emp_attendence.atten_date, '%b') as month,
        hr_emp_attendence.emp_id,
        hr_employee_t.biometric_empno as bio_id,
        hr_emp_attendence.atten_date,
        date_format(hr_emp_attendence.atten_date, '%a') as day,
        time_format(hr_emp_attendence.check_in,'%H:%i')as check_in,
        time_format(hr_emp_attendence.check_out,'%H:%i')as check_out,
        hr_emp_attendence.working_hours,
        time_format(timediff(hr_emp_attendence.check_out,hr_emp_attendence.check_in),'%H:%i') as wrk_hrs,
        time_format(if ((time_to_sec(timediff('09:30',time_format(hr_emp_attendence.check_in,'%H:%i')))/3600)<0,time_format(time(0),'%H:%i'),timediff('09:30',time_format(hr_emp_attendence.check_in,'%H:%i'))),'%H:%i') as early_in,
        time_format(if((time_to_sec(timediff(time_format(hr_emp_attendence.check_in,'%H:%i'),'09:30'))/3600)<0,time(0),timediff(time_format(hr_emp_attendence.check_in,'%H:%i'),'09:30')),'%H:%i') as late_in,
        time_format(if((time_to_sec(timediff('18:00',time_format(hr_emp_attendence.check_out,'%H:%i')))/3600)<0,time(0),timediff('18:00',time_format(hr_emp_attendence.check_out,'%H:%i'))),'%H:%i') as early_out,
        time_format(if((time_to_sec(timediff(time_format(hr_emp_attendence.check_out,'%H:%i'),'18:00'))/3600)<0,time(0),timediff(time_format(hr_emp_attendence.check_out,'%H:%i'),'18:00')),'%H:%i') as late_out,
        hr_emp_attendence.ot,
        hr_employee_t.first_name as first_name,
        a_lookuplines_t.lookup_code as dept,
        m_job_title.job_title_name as position,
        'Present' as status
            FROM
        hr_emp_attendence
            LEFT JOIN hr_employee_t ON hr_employee_t.employee_number = hr_emp_attendence.emp_id
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
            LEFT JOIN m_job_title ON hr_employee_t.job_title = m_job_title.job_title_id
        where 1=1)v1 where 1=1 and v1.atten_date BETWEEN '$start_date' AND '$end_date' HAVING v1.atten_date !='')) t ORDER BY t.atten_date DESC";



    $data = \DB::select($SQL);

    return DataTables::of($data)->make(true);

  }

  // index for monthatten
  public function attenindex(Request $request)
  {

    // restrict illegal menu entry purpose - VIGNESH M
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


    $this->data['emp_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', \Session::get('emp_id'));
    return view('payproposal.monthattenform', $this->data);


  }
  //calender
  public function calendar(Request $request)
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

    $this->data['emp_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', \Session::get('emp_id'));
    //$sql="select * from  hr_emp_attendence";
    return view('payproposal.calendar', $this->data);
  }
  // calendar json data
  function jsondata(Request $request)
  {
    $emp_id = $request->input('filter');
    $query2 = \DB::select("select * from hr_employee_t where employee_id='$emp_id'");
    $emp_id = $query2[0]->employee_number;
    $month = $request->input('month');
    $year = $request->input('year');

    $start_date = date($year . '-' . $month . '-01');

    $month1 = date('m');
    $d = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    if ($month == $month1) {
      $end_date = date($year . '-' . $month . '-d');
    } else {
      $d = cal_days_in_month(CAL_GREGORIAN, $month, $year);
      $end_date = date($year . '-' . $month . '-' . $d);
    }

    $begin = new DateTime($start_date);
    $month_name = $begin->format('F') . ' ' . $year;
    $second_saturday = date('Y-m-d', strtotime('second saturday of ' . $month_name));
    $end = new DateTime($end_date);
    $holidays = \DB::select("SELECT * FROM `hr_holiday_t` WHERE  year(date)='$year'");
    $total_days = $d;
    $present_days = 0;
    $absent_days = 0;
    $sundays = 0;
    $holidayss = 0;
    $data = array();

    while ($begin <= $end) {
      $color = "black";
      $val = "";
      if ($holidays) {
        foreach ($holidays as $holiday) {
          $date = $begin->format('Y-m-d');
          if ($date == $holiday->date) {
            $data[] = array(
              'id' => $emp_id,
              'title' => strtoupper($holiday->holiday_name),
              'start' => $holiday->date,
              'employee_id' => $emp_id,
              'textColor' => "#ffffff",
              'bordercolor' => "#ffc107",
              'color' => "#ffc107"
            );
          }
        }
      }

      if ($val == "") {
        if ($begin->format("D") == "Sun") {
          if ($val == "") {
            $class = "sunday";
            $val = "<span class='sunday'>SUNDAY</span>";
            $sundays++;
            $present_days++;
            $data[] = array(
              'id' => $emp_id,
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
      $date = (string) $begin->format("Y-m-d");


      $atten_detail = \DB::select("SELECT atten_date,`check_in`,`check_out`,`working_hours` FROM `hr_emp_attendence` WHERE `emp_id`='$emp_id' AND `atten_date`like'$date%'");

      $employee_id = $query2[0]->employee_id;

      if ($atten_detail) {
        $atten_date = $atten_detail[0]->atten_date;
        $check_in = $atten_detail[0]->check_in;
        $check_out = $atten_detail[0]->check_out;

        $data[] = array(
          'id' => $emp_id,
          'title' => 'CHECK-IN-' . $check_in,
          'start' => $atten_date,
          'employee_id' => $emp_id,
          'textColor' => "#ffffff",
          'bordercolor' => "#03a9f4",
          'color' => "#8BC34A"
        );

        $data[] = array(
          'id' => $emp_id,
          'title' => 'CHECK-OUT-' . $check_out,
          'start' => $atten_date,
          'employee_id' => $emp_id,
          'textColor' => "#ffffff",
          'bordercolor' => "#03a9f4",
          'color' => "#03a9f4"
        );
      } else {
        $od_detail = \DB::select("SELECT * FROM hr_leaves_t WHERE employee_id='$employee_id' AND DATE(start_date)='$date' AND DATE(end_date)='$date' AND leave_type='1959' AND leave_status='APPROVED' ");
        if (count($od_detail) > 0) {
          $od_hr = $od_detail[0]->end_date;
          $data[] = array(
            'id' => $emp_id,
            'title' => 'PRESENT-ON DUTY(' . $od_hr . ')',
            'start' => $date,
            'employee_id' => $emp_id,
            'textColor' => "#ffffff",
            'bordercolor' => "#03a9f4",
            'color' => "#8BC34A"
          );
        } else {
          if ($val == "") {
            if ($second_saturday != $begin->format("Y-m-d")) {
              $data[] = array(
                'id' => $emp_id,
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
  public function attendancecharts($month = null, $emp_code = null, $year = null)
  {
    if (!$month) {
      $month = date("m");
    }
    if (!$year) {
      $year = date("Y");
    }
    if (!$emp_code) {
      $emp_id = \Session::get('emp_id');
      $emp_code_query = \DB::SELECT("select employee_number from hr_employee_t where employee_id='$emp_id' ");
      $emp_code = $emp_code_query[0]->employee_number;

      $this->data['emp_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $emp_id);
    } else {


      $emp_id = $emp_code;
      $emp_code_query = \DB::SELECT("select employee_number from hr_employee_t where employee_id='$emp_id' ");
      $emp_code = $emp_code_query[0]->employee_number;
      $this->data['emp_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $emp_id);
    }
    $start_date = date($year . "-" . $month . '-01');
    $month1 = date('m');
    if ($month == $month1)
      $end_date = date($year . "-" . $month . '-d', strtotime("-1 days"));
    else
      $end_date = date($year . "-" . $month . '-t');

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
    while ($begin <= $end) {
      $date = (string) $begin->format("Y-m-d");
      $atten_detail = \DB::select("SELECT `check_in`,`check_out`,`working_hours`,`atten_date` FROM `hr_emp_attendence` WHERE `emp_id`='$emp_code' AND DATE(`atten_date`)='$date' ");
      if ($atten_detail) {
        $dates[] = $atten_detail[0]->atten_date;
        $working_hours[] = floatval(date('H.i', $atten_detail[0]->working_hours));
        $temp_check_in = strtotime($atten_detail[0]->check_in);
        $check_in[] = floatval(date('H.i', $temp_check_in));
        $temp_check_out = strtotime($atten_detail[0]->check_out);
        $check_out[] = floatval(date('H.i', $temp_check_out));
        $time = new DateTime($atten_detail[0]->atten_date);
        $date_attend = $time->format('Y-m-d');
        $time = $time->format('H:i');
        $test_arr[] = [$date_attend, floatval(date('H.i', $atten_detail[0]->working_hours))];
        $in_out[] = [floatval(date('H.i', $temp_check_in)), floatval(date('H.i', $temp_check_out))];

      } else {
        if ($begin->format("D") == "Sun") {
          $working_hours[] = floatval(date('H.i', 0000));
          $check_in[] = floatval(date('H.i', 000));
          $check_out[] = floatval(date('H.i', 000));
          $sunday_date = (string) $begin->format("Y-m-d");
          $dates[] = $sunday_date;
          $test_arr[] = [$sunday_date . "<br>(SUNDAY)", 000];
          $in_out[] = [floatval(date('H.i', 000)), floatval(date('H.i', 000))];

        } else {

          $working_hours[] = floatval(date('H.i', 0000));
          $check_in[] = floatval(date('H.i', 000));
          $check_out[] = floatval(date('H.i', 000));
          $leave_date = (string) $begin->format("Y-m-d");
          $dates[] = $leave_date;
          $test_arr[] = [$leave_date . "<br>(LEAVE)", 0];
          $in_out[] = [floatval(date('H.i', 000)), floatval(date('H.i', 000))];
          $every_date = (string) $begin->format('Y-m-d');
          $holidays = \DB::select("SELECT * FROM hr_holiday_t WHERE date='$every_date' ");
        }
      }
      $begin->modify('+1 day');

      $x++;
    }

    $working_hours = str_replace('"', '', json_encode($working_hours));
    $work_hour = str_replace('"', "'", json_encode($test_arr));
    $in_out = str_replace('"', "'", json_encode($in_out));

    $emp_name = \DB::select("select first_name,employee_number from hr_employee_t where employee_id='$emp_id'");
    $emp_name = $emp_name[0]->employee_number . " " . $emp_name[0]->first_name;

    $this->data['categories'] = json_encode($dates);
    $this->data['data'] = $working_hours;
    $this->data['points_header'] = "POINTS TABLE";
    $this->data['emp_name'] = $emp_name;
    $this->data['check_in'] = json_encode($check_in);
    $this->data['check_out'] = json_encode($check_out);
    $this->data['work_hours'] = $work_hour;
    $this->data['in_out'] = $in_out;

    return view('payproposal.chart', $this->data);



  }
  // get report data
  public function reportmonthatten($emp_id = null, $month = null, $year = null)
  {
    $current_year = $year;
    $month = sprintf('%02d', $month);
    $start_date = date("$year-$month-01");
    $end_date = date("Y-m-t", strtotime($start_date));

    $query2 = \DB::select("SELECT * FROM hr_employee_t WHERE employee_id='$emp_id'");
    $employee = $query2[0];
    $employee_id = $employee->employee_id;
    $employee_number = $employee->employee_number;
    $compid = $employee->company_id;
    $my_file = uniqid() . '.xls';
    $file = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file);

    $cutoff_date = DB::table('hr_emp_payroll_cutoff')->where('company', $compid)->whereYear('cutoff_date', $year)->get();

    if (count($cutoff_date) == 0) {
      $begin = new DateTime($start_date);
      $end = new DateTime($end_date);

      // Bootstrap 5 Table start
      $data = '<div class="table-responsive shadow border rounded-4">
        <table class="table table-bordered table-hover table-striped align-middle mb-0">
            <thead class="table-primary text-center">
                <tr>
                    <th scope="col" style="width: 150px;">Date</th>
                    <th scope="col" style="width: 150px;">Check-In</th>
                    <th scope="col" style="width: 150px;">Check-Out</th>
                    <th scope="col" style="width: 300px;">Day Status</th>
                </tr>
            </thead>
            <tbody>';

      $columnHeader = "Date\tCheck-In\tCheck-Out\tDay\t";
      $cl = $employee->c_l;
      $sl = $employee->s_l;
      $el = $employee->e_l;
      $loc_id = json_decode($employee->location_id);
      $locationid = $loc_id[0];

      // Counters
      $total_days = $present_days = $absent_days = $sundays = $holidayss = $weekoffs = 0;
      $c_l = $s_l = $e_l = $od = 0;
      $rowData = $setData = $value = '';

      $leaveClasses = [
        'cl' => 'bg-warning text-dark',
        'sl' => 'bg-danger',
        'el' => 'bg-info',
        'od' => 'bg-primary',
        'col' => 'bg-secondary',
        'lop' => 'bg-dark text-white',
      ];

      $curr_date = date('Y-m-d');

      while ($begin <= $end) {
        $date = $begin->format("Y-m-d");
        $hol_month_week = 0;

        // Holiday
        $holidays = \DB::select("SELECT location, date, holiday_name FROM hr_holiday_t WHERE date='$date' AND company_id='$compid' AND active='Yes'");
        if (count($holidays) == 1) {
          $holiday_location = json_decode($holidays[0]->location);
          if (in_array($locationid, $holiday_location)) {
            $data .= "<tr>
                        <td>$date</td><td></td><td></td>
                        <td><span class='badge bg-warning text-dark'>HOLIDAY - {$holidays[0]->holiday_name}</span></td>
                    </tr>";
            $value = "$date\t\t\tHOLIDAY - {$holidays[0]->holiday_name}\t";
            $rowData .= $value . "\n";
            $holidayss++;
            $present_days++;
            $hol_month_week = 1;
          }
        }

        // WEEK OFF and MONTH OFF
        $date_day = date('N', strtotime($date));
        $date_weekno = $this->weeknumber($date);
        $week_off_query = \DB::select("SELECT * FROM hr_emp_payroll_settings_t");
        $week_off = json_decode($week_off_query[0]->week_off);
        $week_period = json_decode($week_off_query[0]->week_period);
        $month_off = json_decode($week_off_query[0]->month_off);
        $month_period = $this->arraycombine($month_off, $week_period);

        if (in_array($date_day, $week_off)) {
          $data .= "<tr><td>$date</td><td></td><td></td>
                    <td><span class='badge bg-info text-dark'>WEEKOFF</span></td></tr>";
          $value = "$date\t\t\tWEEKOFF\t";
          $rowData .= $value . "\n";
          $weekoffs++;
          $present_days++;
          $hol_month_week = 1;
        } elseif (isset($month_period[$date_day]) && in_array($date_weekno, $month_period[$date_day])) {
          $data .= "<tr><td>$date</td><td></td><td></td>
                    <td><span class='badge bg-secondary'>MONTHOFF</span></td></tr>";
          $value = "$date\t\t\tMONTHOFF\t";
          $rowData .= $value . "\n";
          $weekoffs++;
          $present_days++;
          $hol_month_week = 1;
        }

        // Attendance and Leave
        $atten_detail = \DB::select("SELECT check_in, check_out FROM hr_emp_attendence WHERE emp_id='$employee_number' AND atten_date LIKE '$date%'");

        $query_leave_full = \DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM hr_leaves_t LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_leaves_t.leave_type WHERE employee_id='$emp_id' AND leave_status='APPROVE' AND (a_lookuplines_t.lookup_code IN ('ON-DUTY','EARN LEAVE','CASUAL LEAVE','SICK LEAVE','COM-OFF','LEAVE WITH NO PAY')) AND ('$date' BETWEEN start_date AND end_date OR '$date' BETWEEN DATE(od_start_date) AND DATE(od_end_date)) AND leave_mode='135'");

        $query_leave_half = \DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM hr_leaves_t LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_leaves_t.leave_type WHERE employee_id='$emp_id' AND leave_status='APPROVE' AND (a_lookuplines_t.lookup_code IN ('ON-DUTY','EARN LEAVE','CASUAL LEAVE','SICK LEAVE','COM-OFF','LEAVE WITH NO PAY')) AND ('$date' BETWEEN start_date AND end_date OR '$date' BETWEEN DATE(od_start_date) AND DATE(od_end_date)) AND leave_mode='134'");

        $leave_type = '';
        if (count($query_leave_half) > 0) {
          $leave_type = strtolower(substr(str_replace(' ', '_', $query_leave_half[0]->lookup_code), 0, 3)); // cl, sl, el, etc.
        } elseif (count($query_leave_full) > 0) {
          $leave_type = strtolower(substr(str_replace(' ', '_', $query_leave_full[0]->lookup_code), 0, 3));
        }

        $class = $leaveClasses[$leave_type] ?? 'bg-primary';

        if (count($atten_detail) > 0) {
          if (count($query_leave_half) > 0) {
            $data .= "<tr><td>$date</td><td>{$atten_detail[0]->check_in}</td><td>{$atten_detail[0]->check_out}</td>
                        <td><span class='badge $class'>HALF DAY PRESENT AND HALF DAY {$query_leave_half[0]->lookup_code}</span></td></tr>";
            $value = "$date\t{$atten_detail[0]->check_in}\t{$atten_detail[0]->check_out}\tHALF DAY PRESENT AND HALF DAY {$query_leave_half[0]->lookup_code}\t";
            $rowData .= $value . "\n";
            $present_days++;
            if ($leave_type == 'cl')
              $c_l += 0.5;
            if ($leave_type == 'sl')
              $s_l += 0.5;
            if ($leave_type == 'el')
              $e_l += 0.5;
            if ($leave_type == 'od')
              $od += 0.5;
          } else {
            $data .= "<tr><td>$date</td><td>{$atten_detail[0]->check_in}</td><td>{$atten_detail[0]->check_out}</td>
                        <td><span class='badge bg-success'>PRESENT</span></td></tr>";
            $value = "$date\t{$atten_detail[0]->check_in}\t{$atten_detail[0]->check_out}\tPRESENT\t";
            $rowData .= $value . "\n";
            $present_days++;
          }
        } elseif (count($query_leave_full) > 0) {
          $data .= "<tr><td>$date</td><td></td><td></td>
                    <td><span class='badge $class'>{$query_leave_full[0]->lookup_code}</span></td></tr>";
          $value = "$date\t\t\t{$query_leave_full[0]->lookup_code}\t";
          $rowData .= $value . "\n";
          $present_days++;
          if ($leave_type == 'cl')
            $c_l++;
          if ($leave_type == 'sl')
            $s_l++;
          if ($leave_type == 'el')
            $e_l++;
          if ($leave_type == 'od')
            $od++;
        } elseif ($hol_month_week == 0 && $date <= $curr_date) {
          $data .= "<tr><td>$date</td><td></td><td></td>
                    <td><span class='badge bg-danger'>ABSENT</span></td></tr>";
          $value = "$date\t\t\tABSENT\t";
          $rowData .= $value . "\n";
          $absent_days++;
        }

        $begin->modify('+1 day');
        $total_days++;
      }

      $setData .= trim($rowData) . "\n";

      header("Content-type: application/vnd-ms-excel");
      header("Content-Disposition: attachment; filename=Book-record-sheet.xls");
      header("Pragma: no-cache");
      header("Expires: 0");

      $file = fwrite($file, $columnHeader . "\n" . $setData . "\n");

      $query_check = \DB::select("SELECT * FROM hr_employee_payroll_lists JOIN Leave_balance_tbl ON Leave_balance_tbl.employee_id = hr_employee_payroll_lists.employee_id WHERE Leave_balance_tbl.employee_id = '$emp_id' AND hr_employee_payroll_lists.month = $month AND hr_employee_payroll_lists.year = $year");

      if (count($query_check) > 0) {
        $days = [
          'total_days' => $query_check[0]->total_days,
          'present_days' => $query_check[0]->attendance_days,
          'absent_days' => $query_check[0]->total_days - $query_check[0]->attendance_days,
          'holidays' => $holidayss,
          'weekoff' => $weekoffs,
          'casual' => $query_check[0]->ocl - $query_check[0]->causal_leave,
          'sick' => $query_check[0]->osl - $query_check[0]->sick_leave,
          'earn' => $query_check[0]->oel - $query_check[0]->earn_leave,
          'remcl' => $query_check[0]->causal_leave,
          'remsl' => $query_check[0]->sick_leave,
          'remel' => $query_check[0]->earn_leave,
          'od' => $query_check[0]->od,
        ];
      } else {
        $days = [
          'total_days' => $total_days,
          'present_days' => $present_days,
          'absent_days' => $absent_days,
          'holidays' => $holidayss,
          'weekoff' => $weekoffs,
          'casual' => $c_l,
          'sick' => $s_l,
          'earn' => $e_l,
          'remcl' => $cl,
          'remsl' => $sl,
          'remel' => $el,
          'od' => $od,
        ];
      }

      $data .= "<tr><td colspan='4' class='text-center p-3'>
            <a href='$my_file' class='btn btn-success' download>
                <i class='bi bi-download'></i> Download Excel
            </a>
        </td></tr>";
      $data .= '</tbody></table></div>';

      return response()->json(['data1' => $data, 'data' => $data, 'days' => $days, 'download_ling' => $my_file]);
    }
  }



  // index for delimiter
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


    return view('payproposal.delimiterform', $this->data);


  }


  // report get for employeerelieve
  public function delimeterreport($month = null, $year = null)
  {
    $my_file = uniqid() . '.csv';
    $handle = fopen($my_file, 'w') or die('Cannot open file: ' . $my_file);

    $excel_file = uniqid() . '12.xls';
    $handle1 = fopen($excel_file, 'w') or die('Cannot open file: ' . $excel_file);

    $groupname = \Session::get('groupname');

    if ($groupname == "2") {
      $query1 = \DB::select("
            SELECT hr_employee_t.*, hr_employee_payroll_lists.id 
            FROM hr_employee_payroll_lists  
            JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id  
            JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id  
            WHERE hr_employee_t.active = 'Yes' 
                AND hr_employee_t.employee_id != 1 
                AND hr_employee_payproposal.pf = 1 
                AND hr_employee_payroll_lists.month = '$month' 
                AND year = '$year'  
            GROUP BY hr_employee_payroll_lists.id
        ");
    } else {
      $query1 = \DB::select("
            SELECT hr_employee_t.*, hr_employee_payroll_lists.id 
            FROM hr_employee_payroll_lists  
            JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id  
            JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id  
            WHERE hr_employee_t.employee_id != 1 
                AND hr_employee_payproposal.pf = 1 
                AND hr_employee_payroll_lists.month = '$month' 
                AND year = '$year'  
            GROUP BY hr_employee_payroll_lists.id
        ");
    }

    $pf_detatil = DB::table("m_emp_esi")
      ->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
      ->select('m_emp_esi.limitto', 'm_emp_esi.employeer_contribute', 'm_emp_esi.company_contribute', 'm_emp_esi.company_contribute1')
      ->where('lookup_code', 'PF')
      ->get();

    $data = "<div class='table-responsive mb-4'><table class='table table-bordered table-striped align-middle'>
            <thead class='table-primary text-center'>
            <tr>
                <th>S.No</th>
                <th>Employee No</th>
                <th>PF Number</th>
                <th>Employee Name</th>
                <th>UAN Number</th>
                <th>Worked Days</th>
                <th>NCP Days</th>
                <th class='table-success'>Gross Salary</th>
                <th class='table-success'>Earned Wages</th>
                <th class='table-success'>PF Employee Contribution - {$pf_detatil[0]->employeer_contribute}%</th>
                <th class='table-success'>VPF Employee Contribution</th>
                <th class='table-danger'>Total</th>
                <th class='table-warning'>EPS Employer's Contribution - {$pf_detatil[0]->company_contribute}%</th>
                <th class='table-warning'>EPF Employer's Contribution - {$pf_detatil[0]->company_contribute1}%</th>
                <th class='table-danger'>Total</th>
            </tr>
            </thead>
            <tbody>";

    $data2 = "S.No.\tEmployee Number\tPF Number\tEmployee Name\tUAN Number\tWorked Days\tNCP Days\tGross Salary\tEarned Wages\tPF EC-12\tVPF\tTotal\tEPS-8.33\tEPF-3.67\tTotal\r\n";

    $i = 1;
    $wages = 0;
    $gross_salary = 0;
    $pf_total = 0;
    $pfv_total = 0;
    $pf_v_total = 0;
    $cpf_total = 0;
    $cpf1_total = 0;
    $lop_days = 0;
    $check_arr = [];
    $data1 = '';

    foreach ($query1 as $row) {
      $uan_no = ($row->uan_no == 0) ? '~' : $row->uan_no;

      $check_arr[$row->employee_id] = isset($check_arr[$row->employee_id]) ? $check_arr[$row->employee_id] + 1 : 0;

      $result = $this->contribute($row->employee_id, $month, $year, $row->id, $check_arr[$row->employee_id]);

      $data .= "<tr class='text-center'>
                    <td>{$i}</td>
                    <td>{$row->employee_number}</td>
                    <td>{$row->pf_no}</td>
                    <td>{$row->first_name}</td>
                    <td>{$uan_no}</td>
                    <td>{$result['day']}</td>
                    <td>{$result['ncp']}</td>
                    <td>{$result['gross_salary']}</td>
                    <td>{$result['earned_wages']}</td>
                    <td>{$result['pf']}</td>
                    <td>{$result['v_pf']}</td>
                    <td>" . ($result['pf'] + $result['v_pf']) . "</td>
                    <td>{$result['amount_comp']}</td>
                    <td>{$result['amount_comp1']}</td>
                    <td>" . ($result['amount_comp'] + $result['amount_comp1']) . "</td>
                  </tr>";

      $data2 .= "{$i}\t{$row->employee_number}\t{$row->pf_no}\t{$row->first_name}\t{$uan_no}\t{$result['day']}\t{$result['ncp']}\t{$result['gross_salary']}\t{$result['earned_wages']}\t{$result['pf']}\t{$result['v_pf']}\t" . ($result['pf'] + $result['v_pf']) . "\t{$result['amount_comp']}\t{$result['amount_comp1']}\t" . ($result['amount_comp'] + $result['amount_comp1']) . "\r\n";

      $wages += $result['earned_wages'];
      $pf_total += $result['pf'];
      $pfv_total += $result['v_pf'];
      $pf_v_total += $result['pf'] + $result['v_pf'];
      $cpf_total += $result['amount_comp'];
      $cpf1_total += $result['amount_comp1'];
      $gross_salary += $result['gross_salary'];
      $cpf_total += $result['amount_comp'] + $result['amount_comp1'];

      $query2 = \DB::select("SELECT * FROM hr_employee_payroll_lists WHERE id='{$row->id}' AND month='{$month}' AND year='{$year}'");

      if (count($query2) > 0) {
        $lop_days = $query2[0]->no_of_days_employee - round($query2[0]->attendance_days);
        $wages_month = $query2[0]->basic_salary + $query2[0]->da;
        if ($wages_month > $pf_detatil[0]->limitto) {
          $wages_month = $pf_detatil[0]->limitto;
        }
      }

      $data1 .= "{$row->uan_no}#~#{$row->first_name}#~#{$result['gross_salary']}#~#{$wages_month}#~#{$wages_month}#~#{$wages_month}#~#" . ($result['pf'] + $result['v_pf']) . "#~#{$result['amount_comp']}#~#{$result['amount_comp1']}#~#{$lop_days}#~#\r\n";

      $i++;
    }

    // Grand total row
    $data .= "<tr class='fw-bold text-center bg-light'>
                <td>{$i}</td>
                <td colspan='6'>Total:</td>
                <td>{$gross_salary}</td>
                <td>{$wages}</td>
                <td>{$pf_total}</td>
                <td>{$pfv_total}</td>
                <td>{$pf_v_total}</td>
                <td>{$cpf_total}</td>
                <td>{$cpf1_total}</td>
                <td>{$cpf_total}</td>
              </tr>";

    $data .= "</tbody></table></div>";

    // Download buttons
    $data .= "<div class='text-center mb-4'>
                <a href='{$my_file}' class='btn btn-outline-primary me-2' download>
                    <i class='fa fa-download me-1'></i>Download CSV
                </a>
                <a href='{$excel_file}' class='btn btn-outline-success' download>
                    <i class='fa fa-download me-1'></i>Download Excel
                </a>
              </div>";

    fwrite($handle, $data1);
    fwrite($handle1, $data2);

    return response()->json(['data' => $data, 'download_ling' => $my_file]);
  }



  // index for arrearpfdelimiter
  public function arrearpfindex(Request $request)
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


    return view('payproposal.arrearpfdelimiterform', $this->data);


  }


  // report get for arrearpfdelimiter
  public function arrearpfdelimeterreport($month = null, $year = null)
  {
    $year = $year;
    $month = $month;

    $my_file = uniqid() . '.csv';
    $handle = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file);


    $excel_file = uniqid() . '12.xls';
    $handle1 = fopen($excel_file, 'w') or die('Cannot open file:  ' . $excel_file);

    $groupname = \Session::get('groupname');

    if ($groupname == "2") {
      $query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where hr_employee_t.active='Yes' and 1=1 and hr_employee_t.employee_id!=1 and hr_employee_payproposal.pf=1 and hr_employee_payroll_lists.month='$month' and year='$year' and hr_employee_payroll_lists.arrear_status='1' group by hr_employee_payroll_lists.id");
    } else {
      $query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where 1=1 and hr_employee_t.employee_id!=1 and hr_employee_payproposal.pf=1 and hr_employee_payroll_lists.month='$month' and year='$year' and hr_employee_payroll_lists.arrear_status='1' group by hr_employee_payroll_lists.id");
    }

    $data1 = '';
    $pf_detatil = DB::table("m_emp_esi")
      ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
      ->select('m_emp_esi.limitto', 'm_emp_esi.employeer_contribute', 'm_emp_esi.company_contribute', 'm_emp_esi.company_contribute1')->where('lookup_code', 'PF')->get();

    $data1 = '';
    $data2 = "UAN\tMEMBER NAME\tARREAR_EPF_WAGES\tARREAR_EPS_WAGES\tARREAR_EDLI_WAGES\tARREAR_EPF_EE_SHARE\tARREAR_EPF_ER_SHARE\tARREAR_EPS_SHARE\r\n";

    $wages = $wages_month = $pf_total = $pfv_total = $pf_v_total = 0;
    $cpf_total = $cpf1_total = $cpf2_total = $lop_days = $gross_salary = 0;
    $result_days = '~';
    $check_arr = [];

    $data = '<div class="table-responsive">';
    $data .= '<table class="table table-bordered table-striped align-middle">';
    $data .= '<thead class="table-primary text-center"><tr>
    <th>UAN Number</th>
    <th>Member Name</th>
    <th class="table-success">Arrear EPF Wages</th>
    <th class="table-success">Arrear EPS Wages</th>
    <th class="table-success">Arrear EDLI Wages</th>
    <th class="table-warning">Arrear EPF EE Share - ' . $pf_detatil[0]->employeer_contribute . '%</th>
    <th class="table-info">Arrear EPF ER Share - ' . $pf_detatil[0]->company_contribute1 . '%</th>
    <th class="table-secondary">Arrear EPS Share - ' . $pf_detatil[0]->company_contribute . '%</th>
</tr></thead><tbody>';

    $i = 1;

    foreach ($query1 as $row) {
      $uan_no = $row->uan_no ?: '~';
      $check_arr[$row->employee_id] = ($check_arr[$row->employee_id] ?? -1) + 1;

      $result = $this->contributearrearpf($row->employee_id, $month, $year, $row->id, $check_arr[$row->employee_id]);

      $data .= "<tr>
        <td>{$uan_no}</td>
        <td>{$row->first_name}</td>
        <td>{$result['earned_wages']}</td>
        <td>{$result['earned_wages']}</td>
        <td>{$result['earned_wages']}</td>
        <td>" . ($result['amount_comp'] + $result['amount_comp1']) . "</td>
        <td>{$result['amount_comp1']}</td>
        <td>{$result['amount_comp']}</td>
    </tr>";

      $data2 .= "{$uan_no}\t{$row->first_name}\t{$result['earned_wages']}\t{$result['earned_wages']}\t{$result['earned_wages']}\t" . ($result['amount_comp'] + $result['amount_comp1']) . "\t{$result['amount_comp1']}\t{$result['amount_comp']}\r\n";

      $wages += $result['earned_wages'];
      $pf_total += $result['pf'];
      $pfv_total += $result['v_pf'];
      $pf_v_total += $result['pf'] + $result['v_pf'];
      $cpf2_total += $result['amount_comp'];
      $cpf1_total += $result['amount_comp1'];
      $gross_salary += $result['gross_salary'];
      $cpf_total += $result['amount_comp'] + $result['amount_comp1'];

      $query2 = \DB::select("SELECT * FROM hr_employee_payroll_lists WHERE id = ? AND month = ? AND year = ?", [$row->id, $month, $year]);
      if (!empty($query2)) {
        $lop_days = $query2[0]->no_of_days_employee - round($query2[0]->attendance_days);
        $wages_month = $query2[0]->basic_salary + $query2[0]->da;
        if ($wages_month > $pf_detatil[0]->limitto) {
          $wages_month = $pf_detatil[0]->limitto;
        }
      }

      $data1 .= "{$row->uan_no}#~#{$row->first_name}#~#{$wages_month}#~#{$wages_month}#~#{$wages_month}#~#" . ($result['amount_comp'] + $result['amount_comp1']) . "#~#{$result['amount_comp1']}#~#{$result['amount_comp']}\r\n";

      $i++;
    }

    // Add totals row
    $data .= "<tr class='fw-bold text-end'>
    <td></td>
    <td>Total:</td>
    <td>{$wages}</td>
    <td>{$wages}</td>
    <td>{$wages}</td>
    <td>{$cpf_total}</td>
    <td>{$cpf1_total}</td>
    <td>{$cpf2_total}</td>
</tr>";

    $data .= '</tbody></table></div>';

    // Download Buttons
    $data .= '
<div class="mt-4 text-center">
    <a href="' . $my_file . '" class="btn btn-outline-danger me-2" download>
        <i class="fa fa-file-download me-1"></i>Download Text File
    </a>
    <a href="' . $excel_file . '" class="btn btn-outline-success" download>
        <i class="fa fa-file-excel me-1"></i>Download Excel File
    </a>
</div>';

    // Write to files
    fwrite($handle, $data1);
    fwrite($handle1, $data2);

    return response()->json([
      'data' => $data,
      'download_ling' => $my_file
    ]);




  }



  //get data for delimiter
  function contribute($employe_id, $month, $year, $id, $index)
  {

    $index1 = $index + 1;
    $query2 = \DB::select("select * from hr_employee_payroll_lists where id='$id' and month='$month' and year='$year'");

    $query = \DB::select("select * from hr_employee_payproposal where employee_id='$employe_id' ");
    $query_pf = \DB::select("select * from hr_company_contribute_pf where emp_id='$employe_id' and month='$month' and year='$year' order by employee_conribute_id asc limit $index, $index1 ");
    if (count($query2) > 0) {
      $no_of_days = $query2[0]->attendance_days;
      $total_days = $query2[0]->no_of_days_employee;
      $ncp = $query2[0]->no_of_days_employee - $query2[0]->attendance_days;
      $gross_salary = $query2[0]->gross_salary;
      if (count($query) > 0) {
        $earn_wages = $query2[0]->basic_salary + $query2[0]->da;
        if ($earn_wages > 15000)
          $earn_wages = 15000;
      } else {
        $earn_wages = 0;
      }
      if (count($query_pf) > 0) {
        $pf = $query_pf[0]->amount_employee;
        $v_pf = $query_pf[0]->volunter_pf;
        if ($v_pf == '') {
          $v_pf = 0;
        }
        $pf_emp = $pf + $v_pf;
        $amount_comp = $query_pf[0]->amount;
        $amount_comp1 = $query_pf[0]->amount1;
      } else {
        $amount_comp1 = 0;
        $amount_comp = 0;
        $pf_emp = 0;
        $v_pf = 0;
        $pf = 0;
      }

      return array("gross_salary" => $gross_salary, "day" => $no_of_days, "ncp" => $ncp, "earned_wages" => $earn_wages, "pf" => $pf, "v_pf" => $v_pf, "pf_emp" => $pf_emp, "amount_comp" => $amount_comp, "amount_comp1" => $amount_comp1);
    } else {
      return array("gross_salary" => 0, "day" => "0", "ncp" => "0", "earned_wages" => "0", "pf" => "0", "v_pf" => "0", "pf_emp" => "0", "amount_comp" => "0", "amount_comp1" => "0");
    }
    return array("gross_salary" => 0, "day" => "0", "ncp" => "0", "earned_wages" => "0", "pf" => "0", "v_pf" => "0", "pf_emp" => "0", "amount_comp" => "0", "amount_comp1" => "0");
  }


  //get data for arrearpfdelimiter
  function contributearrearpf($employe_id, $month, $year, $id, $index)
  {

    $index1 = $index + 1;
    $query2 = \DB::select("select * from hr_employee_payroll_lists where id='$id' and month='$month' and year='$year'");

    $query = \DB::select("select * from hr_employee_payproposal where employee_id='$employe_id' ");
    $query_pf = \DB::select("select * from hr_company_contribute_pf where emp_id='$employe_id' and month='$month' and year='$year' and arrear_status='1' order by employee_conribute_id asc limit $index, $index1 ");
    if (count($query2) > 0) {
      $no_of_days = $query2[0]->attendance_days;
      $total_days = $query2[0]->no_of_days_employee;
      $ncp = $query2[0]->no_of_days_employee - $query2[0]->attendance_days;
      $gross_salary = $query2[0]->gross_salary;
      if (count($query) > 0) {
        $earn_wages = $query2[0]->basic_salary + $query2[0]->da;
        if ($earn_wages > 15000)
          $earn_wages = 15000;
      } else {
        $earn_wages = 0;
      }
      if (count($query_pf) > 0) {
        $pf = $query_pf[0]->amount_employee;
        $v_pf = $query_pf[0]->volunter_pf;
        if ($v_pf == '') {
          $v_pf = 0;
        }
        $pf_emp = $pf + $v_pf;
        $amount_comp = $query_pf[0]->amount;
        $amount_comp1 = $query_pf[0]->amount1;
      } else {
        $amount_comp1 = 0;
        $amount_comp = 0;
        $pf_emp = 0;
        $v_pf = 0;
        $pf = 0;
      }

      return array("gross_salary" => $gross_salary, "day" => $no_of_days, "ncp" => $ncp, "earned_wages" => $earn_wages, "pf" => $pf, "v_pf" => $v_pf, "pf_emp" => $pf_emp, "amount_comp" => $amount_comp, "amount_comp1" => $amount_comp1);
    } else {
      return array("gross_salary" => 0, "day" => "0", "ncp" => "0", "earned_wages" => "0", "pf" => "0", "v_pf" => "0", "pf_emp" => "0", "amount_comp" => "0", "amount_comp1" => "0");
    }
    return array("gross_salary" => 0, "day" => "0", "ncp" => "0", "earned_wages" => "0", "pf" => "0", "v_pf" => "0", "pf_emp" => "0", "amount_comp" => "0", "amount_comp1" => "0");
  }




  // index for esi delimiter
  public function esiindex(Request $request)
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

    return view('payproposal.delimiteresiform', $this->data);


  }
  public function ptindex(Request $request)
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


    return view('payproposal.delimiterptform', $this->data);


  }
  public function delimeterreportpt($month = null, $year = null, $type = null)
  {
    $employee_type = $type;
    $my_file = uniqid() . '.xls';
    $handle = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file);

    $m1 = $m2 = $m3 = $m4 = $m5 = $m6 = 0;
    $gross_sum1 = $paid_pt = $paid_pt_total = $pt = 0;

    // Employee filter
    if ($employee_type != 0) {
      $query1 = DB::select("
            SELECT a_lookuplines_t.lookup_code, hr_employee_t.*, m_position.position, m_job_title.job_title_name
            FROM hr_employee_t
            LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_employee_t.job_title
            LEFT JOIN m_position ON m_position.position_id = hr_employee_t.position
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
            WHERE hr_employee_t.employee_id != 1 AND employee_type = $employee_type
        ");
    } else {
      $query1 = DB::select("
            SELECT a_lookuplines_t.lookup_code, hr_employee_t.*, m_position.position, m_job_title.job_title_name
            FROM hr_employee_t
            LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_employee_t.job_title
            LEFT JOIN m_position ON m_position.position_id = hr_employee_t.position
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
            WHERE hr_employee_t.employee_id != 1
        ");
    }

    $data2 = "S.No.\tEmployee Number\tParticulars\tDesignation\tEmployee Type\tDOJ\tDOR";

    $data = '<div class="table-responsive">';
    $data .= '<table class="table table-bordered table-hover align-middle">';
    $data .= '<thead class="table-primary text-center"><tr>
        <th>S.No</th>
        <th>Employee Number</th>
        <th>Particulars</th>
        <th>Designation</th>
        <th>Employee Type</th>
        <th>DOJ</th>
        <th>DOR</th>';

    $month_name = date("F Y", strtotime("$year-$month-01"));
    $data .= "<th>$month_name</th>";
    $data2 .= "\t" . $month_name;

    for ($i = 1; $i < 6; $i++) {
      $prev_month = date("F Y", strtotime("$year-$month-01 -$i months"));
      $data .= "<th>$prev_month</th>";
      $data2 .= "\t" . $prev_month;
    }

    $data .= '
        <th class="bg-info text-white">1st Half Gross</th>
        <th class="bg-warning text-dark">PT Amount</th>
        <th class="bg-success text-white">Paid PT</th>
        <th class="bg-danger text-white">Total PT</th>
        <th>Remark</th>
    </tr></thead><tbody>';

    $data2 .= "\t1st Half Gross\tPT Amount\tPaid PT\tTotal PT\tRemark";

    $i1 = 1;

    foreach ($query1 as $row) {
      $data2 .= "\r\n";
      $data2 .= "$i1\t$row->employee_number\t$row->first_name\t$row->job_title_name/$row->position\t$row->lookup_code\t$row->date_of_joining\t$row->date_of_leaving";

      $data .= "<tr>
            <td>$i1</td>
            <td>$row->employee_number</td>
            <td>$row->first_name</td>
            <td>$row->job_title_name/$row->position</td>
            <td>$row->lookup_code</td>
            <td>$row->date_of_joining</td>
            <td>$row->date_of_leaving</td>";

      $paid_pt_org = 0;
      $gross_sum = 0;

      // Current month
      $month_name_num = date("m", strtotime("$year-$month-01"));
      $year_name = date("Y", strtotime("$year-$month-01"));
      $query2 = DB::select("SELECT * FROM hr_employee_payroll_lists WHERE month=$month_name_num AND year=$year_name AND employee_id=$row->employee_id");

      if (count($query2) > 0) {
        $gross = $query2[0]->gross_salary;
        $data .= "<td>$gross</td>";
        $data2 .= "\t$gross";
        $paid_pt_org += $query2[0]->pt;
        $m1 += $gross;
        $gross_sum += $gross;
      } else {
        $data .= "<td></td>";
        $data2 .= "\t";
      }

      // Previous 5 months
      for ($i = 1, $j = 2; $i < 6; $i++, $j++) {
        $month_name_num = date("m", strtotime("$year-$month-01 -$i months"));
        $year_name = date("Y", strtotime("$year-$month-01 -$i months"));
        $query2 = DB::select("SELECT * FROM hr_employee_payroll_lists WHERE month=$month_name_num AND year=$year_name AND employee_id=$row->employee_id");

        if (count($query2) > 0) {
          $gross = $query2[0]->gross_salary;
          $data .= "<td>$gross</td>";
          $data2 .= "\t$gross";
          $paid_pt_org += $query2[0]->pt;
          ${"m$j"} += $gross;
          $gross_sum += $gross;
        } else {
          $data .= "<td></td>";
          $data2 .= "\t";
        }
      }

      // PT Calculation Slab
      if ($gross_sum >= 75000)
        $pt_val = 1250;
      elseif ($gross_sum >= 60001)
        $pt_val = 1188;
      elseif ($gross_sum >= 45001)
        $pt_val = 797;
      elseif ($gross_sum >= 30001)
        $pt_val = 398;
      elseif ($gross_sum >= 20001)
        $pt_val = 162;
      else
        $pt_val = 0;

      $pt += $pt_val;
      $paid_pt += $paid_pt_org;
      $paid_pt_total += ($pt_val - $paid_pt_org);
      $gross_sum1 += $gross_sum;

      $data2 .= "\t$gross_sum\t$pt_val\t$paid_pt_org\t" . ($pt_val - $paid_pt_org);
      $data .= "<td>$gross_sum</td>
            <td>$pt_val</td>
            <td>$paid_pt_org</td>
            <td>" . ($pt_val - $paid_pt_org) . "</td>
            <td></td>
        </tr>";

      $i1++;
    }

    // Total Row
    $data .= "<tr class='fw-bold text-end'>
        <td colspan='7' class='text-center'>Total:</td>
        <td>$m1</td><td>$m2</td><td>$m3</td><td>$m4</td><td>$m5</td><td>$m6</td>
        <td>$gross_sum1</td><td>$pt</td><td>$paid_pt</td><td>$paid_pt_total</td><td></td>
    </tr></tbody></table></div>";

    // Download Button
    $data .= '<div class="mt-4 text-center">
        <a href="' . $my_file . '" class="btn btn-outline-danger" download>
            <i class="fa fa-download me-2"></i>Download Report
        </a>
    </div>';

    // Final Row in XLS
    $data2 .= "\r\n\t\t\t\t\t\tTotal\t$m1\t$m2\t$m3\t$m4\t$m5\t$m6\t$gross_sum1\t$pt\t$paid_pt\t$paid_pt_total";

    fwrite($handle, $data2);
    fclose($handle);

    return response()->json([
      'data' => $data,
      'download_ling' => $my_file
    ]);
  }



  // report get for esi delimiter
  public function delimeterreportesi($month = null, $year = null)
  {
    $my_file = uniqid() . '.xls';
    $excel_file = uniqid() . '12.xls';
    $handle = fopen($my_file, 'w') or die('Cannot open file: ' . $my_file);
    $handle1 = fopen($excel_file, 'w') or die('Cannot open file: ' . $excel_file);

    $groupname = \Session::get('groupname');

    if ($groupname == "2") {
      $query1 = \DB::select("
            SELECT hr_employee_t.*, hr_employee_payroll_lists.id 
            FROM hr_employee_payroll_lists  
            JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id  
            JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id  
            WHERE hr_employee_t.active = 'Yes' 
                AND hr_employee_t.employee_id != 1 
                AND (hr_employee_payproposal.gross_pay < 21000 OR hr_employee_payroll_lists.esi > 0) 
                AND hr_employee_payroll_lists.month = '$month' 
                AND year = '$year'  
            GROUP BY hr_employee_payroll_lists.id
        ");
    } else {
      $query1 = \DB::select("
            SELECT hr_employee_t.*, hr_employee_payroll_lists.id 
            FROM hr_employee_payroll_lists  
            JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id  
            JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id  
            WHERE hr_employee_t.employee_id != 1 
                AND (hr_employee_payproposal.gross_pay < 21000 OR hr_employee_payroll_lists.esi > 0) 
                AND hr_employee_payroll_lists.month = '$month' 
                AND year = '$year'  
            GROUP BY hr_employee_payroll_lists.id
        ");
    }

    $pf_detail = DB::table("m_emp_esi")
      ->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
      ->select('m_emp_esi.limitto', 'm_emp_esi.employeer_contribute', 'm_emp_esi.company_contribute', 'm_emp_esi.company_contribute1')
      ->where('lookup_code', 'ESI')
      ->first();

    $data = "<div class='table-responsive mb-4'>
                <table class='table table-bordered table-striped align-middle text-center'>
                    <thead class='table-primary'>
                        <tr>
                            <th>S.No</th>
                            <th>Employee No</th>
                            <th>Employee Name</th>
                            <th>ESI Number</th>
                            <th>Worked Days</th>
                            <th class='table-success'>Fixed Wages</th>
                            <th class='table-success'>Earned Wages</th>
                            <th class='table-warning'>Employee's Contribution ({$pf_detail->employeer_contribute}%)</th>
                            <th class='table-warning'>Employer's Contribution ({$pf_detail->company_contribute}%)</th>
                            <th class='table-danger'>Total</th>
                        </tr>
                    </thead>
                    <tbody>";

    $data1 = "IP Number\tIP Name\tNo of Days for which wages paid/payable during the month\tTotal Monthly Wages\r\n";
    $data2 = "S.No.\tEmployee Number\tEmployee Name\tESI Number\tWorked Days\tFixed Wages\tEarned Wages\tEC-0.75\tErC-3.25\tTotal\r\n";

    $i = 1;
    $wages = $earned_wages = $esi_total = $esi_comp_total = $grand_total = 0;
    $check_arr = [];

    foreach ($query1 as $row) {
      $check_arr[$row->employee_id] = ($check_arr[$row->employee_id] ?? -1) + 1;

      $result = $this->contributeesi($row->employee_id, $month, $year, $row->id, $check_arr[$row->employee_id]);

      $total_esi = $result['esi'] + $result['comp_esi'];

      $data .= "<tr>
                    <td>{$i}</td>
                    <td>{$row->employee_number}</td>
                    <td>{$row->first_name}</td>
                    <td>{$row->esi_no}</td>
                    <td>{$result['day']}</td>
                    <td>{$result['fixed_wages']}</td>
                    <td>{$result['earned_wages']}</td>
                    <td>{$result['comp_esi']}</td>
                    <td>{$result['esi']}</td>
                    <td>{$total_esi}</td>
                  </tr>";

      $wages += $result['fixed_wages'];
      $earned_wages += $result['earned_wages'];
      $esi_total += $result['esi'];
      $esi_comp_total += $result['comp_esi'];
      $grand_total += $total_esi;

      $data2 .= "{$i}\t{$row->employee_number}\t{$row->first_name}\t{$row->esi_no}\t{$result['day']}\t{$result['fixed_wages']}\t{$result['earned_wages']}\t{$result['comp_esi']}\t{$result['esi']}\t{$total_esi}\r\n";

      if ($total_esi != 0) {
        $data1 .= "{$row->esi_no}\t{$row->first_name}\t" . round($result['day']) . "\t{$result['earned_wages']}\r\n";
      }

      $i++;
    }

    // Total Row
    $data .= "<tr class='fw-bold bg-light'>
                <td>{$i}</td>
                <td colspan='4'>Total:</td>
                <td>{$wages}</td>
                <td>{$earned_wages}</td>
                <td>{$esi_comp_total}</td>
                <td>{$esi_total}</td>
                <td>{$grand_total}</td>
              </tr>";

    $data .= "</tbody></table></div>";

    // Download Buttons
    $data .= "<div class='text-center mb-4'>
                <a href='{$my_file}' class='btn btn-outline-primary me-2' download>
                    <i class='fa fa-download me-1'></i>Download CSV
                </a>
                <a href='{$excel_file}' class='btn btn-outline-success' download>
                    <i class='fa fa-download me-1'></i>Download Excel
                </a>
              </div>";

    fwrite($handle, $data1);
    fwrite($handle1, $data2);

    return response()->json([
      'data' => $data,
      'download_ling' => $my_file,
      'excel_file' => $excel_file
    ]);
  }

  //get data for esi delimiter
  function contributeesi($employe_id, $month, $year, $id, $index)
  {
    $index1 = $index + 1;
    $query2 = \DB::select("select * from hr_employee_payroll_lists where id='$id' and month='$month' and year='$year'");
    $query = \DB::select("select * from hr_employee_payproposal where employee_id='$employe_id'");
    $query_pf = \DB::select("select * from hr_company_contribute_esi where emp_id='$employe_id' and month='$month' and year='$year' order by company_conribute_id asc limit $index, $index1 ");

    if (count($query2) > 0) {
      $no_of_days = $query2[0]->attendance_days;
      $fixed = $query[0]->gross_pay;
      $earn_wages = $query2[0]->gross_salary;
      if ($query2[0]->esi > 0) {
        // dd($query_pf);
        if (count($query_pf) > 0) {
          $esi = $query_pf[0]->amount;
          $esi_comp = $query_pf[0]->amount_employee;
        } else {
          $esi = 0;
          $esi_comp = 0;
        }
      } else {
        $esi = 0;
        $esi_comp = 0;
      }


      return array("day" => $no_of_days, "fixed_wages" => $fixed, "earned_wages" => $earn_wages, "esi" => $esi, "comp_esi" => $esi_comp);
    } else {
      return array("day" => "0", "fixed_wages" => '0', "earned_wages" => "0", "esi" => "0", "comp_esi" => "0");
    }
    return array("day" => "0", "fixed_wages" => '0', "earned_wages" => "0", "esi" => "0", "comp_esi" => "0");
  }

  //esi report

  public function esireportindex(Request $request)
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

    return view('payproposal.esireport', $this->data);

  }
  public function pfreportgrid()
  {
    $company_id = Session::get('companyid');

    $emp = \Session::get('emp_id');

    $wh = '';

    //based on superadmin load all data and remaining all indivudual data only
    $emp_data = DB::table("hr_employee_t")->where('employee_id', $emp)->get();
    //dd($emp_data);
    $dept = json_decode($emp_data[0]->department);
    if (in_array(28, $dept)) {
      $wh .= '';
    } else {
      if ($emp != "1") {
        $wh .= "and v1.employee_id=$emp";
      }

    }



    $SQL = "select * from (SELECT hr_company_contribute_pf.date,hr_company_contribute_pf.emp_id,hr_employee_t.employee_id,max(hr_company_contribute_pf.employee_conribute_id) as employee_conribute_id,hr_company_contribute_pf.month,hr_company_contribute_pf.year,hr_company_contribute_pf.amount,hr_company_contribute_pf.amount1,hr_company_contribute_pf.amount_employee,hr_company_contribute_pf.volunter_pf,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.pf_no,hr_employee_t.uan_no FROM  hr_employee_t    JOIN hr_company_contribute_pf ON hr_employee_t.employee_id = hr_company_contribute_pf.emp_id where 1=1 group by emp_id,month,year)v1  WHERE  1 = 1  $wh   ORDER BY v1.employee_conribute_id DESC";


    $result = \DB::select($SQL);
    return DataTables::of($result)->make(true);

  }



  public function esireportgrid()
  {

    $company_id = Session::get('companyid');
    $emp = \Session::get('emp_id');
    $wh = '';

    //based on superadmin load all data and remaining all indivudual data only
    $emp_data = DB::table("hr_employee_t")->where('employee_id', $emp)->get();
    //dd($emp_data);
    $dept = json_decode($emp_data[0]->department);
    if (in_array(28, $dept)) {
      $wh .= '';
    } else {
      if ($emp != "1") {
        $wh .= "and v1.employee_id=$emp";
      }

    }


    $SQL = "select * from (SELECT hr_company_contribute_esi.date,hr_company_contribute_esi.emp_id,hr_employee_t.employee_id,max(hr_company_contribute_esi.company_conribute_id) as company_conribute_id,hr_company_contribute_esi.month,hr_company_contribute_esi.year,hr_company_contribute_esi.amount,hr_company_contribute_esi.amount_employee,hr_company_contribute_esi.volunter_pf,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM  hr_employee_t  JOIN hr_company_contribute_esi ON hr_employee_t.employee_id = hr_company_contribute_esi.emp_id where 1=1 group by emp_id,month,year)v1  WHERE  1 = 1 $wh  ORDER BY v1.company_conribute_id DESC";


    $result = \DB::select($SQL);
    return DataTables::of($result)->make(true);

  }

  // pf report
  public function pfreportindex(Request $request)
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


    return view('payproposal.pfreport', $this->data);
  }
  // week number check
  public function weeknumber($date)
  {
    $week_name = array("1" => "first", "2" => "second", "3" => "third", "4" => "fourth", "5" => "fifth", "6" => "first", "52" => "second", "53" => "third", "54" => "fourth");
    $firstOfMonth = date("Y-m-01", strtotime($date));
    $Month = (string) date("m", strtotime($date));
    $Date = (string) date("d", strtotime($date));

    //echo $Month;
    // dd($Month);
    if ($Month == "01" && $Date != "01") {
      //echo "test4";
      //dd($date);
      $week_month = intval(date("W", strtotime($date)));
      //dd($week_month);
    } else if ($Month == "12") { //echo "test1";
      $week = intval(date("W", strtotime($date)));

      if ($week == 1) {//echo "test2";
        $week_month = 5;
      } else {//echo "test3";
        $week_month = intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth))) + 1;
      }
    } else {  //echo "test5";
      // dd(intval(date("W",strtotime($date))));
      //  dd(intval(date("W",strtotime($firstOfMonth))));
      $week_month = intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth))) + 1;
      //    dd($week_month);

    }
    //  dd($week_month);
    // echo $week_month;
    // echo $week_name[$week_month];
    // dd($week_name[$week_month]);
    return $week_name[$week_month];
  }
  // array combine
  public function arraycombine($a, $b)
  {
    $c = [];
    foreach ($a as $k => $v) {
      if (!isset($c[$v])) {
        $c[$v][] = $b[$k];
      } else {
        $c[$v][] = $b[$k];
      }
    }
    return $c;
  }
  
  // pay report

  public function employeepayreport1()
  {

    $company_id = Session::get('companyid');
    $wh = '';
    $wh .= " and hr_employee_t.company_id=' $company_id' ";
    $log_id = Session::get('emp_id');
    if ($log_id != 1)
      $wh .= " and hr_employee_t.employee_id=' $log_id' ";

    $query_result = \DB::select(" SELECT employeetype.lookup_code as employee_type_name,
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

    if (count($query_result) > 0) {
      foreach ($query_result as $k => $v) {
        if ($v->department != '' && $v->department != null) {
          $array = json_decode($v->department);
          $query = \DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
          $deptnames = json_decode(json_encode($query), true);
          $query_result[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
        } else {
          $query_result[$k]->department = "";
        }

        if ($v->pay_allowance != '' && $v->pay_allowance != null) {
          $array = json_decode($v->pay_allowance);
          $query = \DB::table('m_allowance_tbl')->where('employee_type', $v->employee_type)->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $company_id)->get();
          foreach ($query as $k1 => $v1) {
            foreach ($array as $e => $r) {
              if (isset($r->{$v1->allowance_id})) {
                $query_result[$k]->{$v1->allowance_name . "0"} = $r->{$v1->allowance_id};
                break;
              }

            }
          }
        }

        if ($v->list_allowance != '' && $v->list_allowance != null) {
          $array = json_decode($v->list_allowance);
          $query = \DB::table('m_allowance_tbl')->where('employee_type', $v->employee_type)->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $company_id)->get();
          foreach ($query as $k1 => $v1) {
            foreach ($array as $e => $r) {
              if (isset($r->{$v1->allowance_id})) {
                $query_result[$k]->{$v1->allowance_name . "1"} = $r->{$v1->allowance_id};
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
    $html = [];
    $html[] = array('name' => "id", 'label' => " ID", 'width' => 250, 'hidden' => true);
    $html[] = array('name' => "employee_number", 'label' => "Employee Name", 'width' => 250);
    $html[] = array('name' => "department", 'label' => "Department", 'width' => 250);
    $html[] = array('name' => "date", 'label' => "Payroll Date", 'width' => 250, 'editable' => true, 'formatter' => 'date');
    $html[] = array('name' => "month", 'label' => "Month", 'width' => 250);
    $html[] = array('name' => "year", 'label' => "Year", 'width' => 250);
    $html[] = array('name' => "pay_basic", 'label' => "Basic", 'width' => 250);
    $html[] = array('name' => "pay_hra", 'label' => "HRA", 'width' => 250);
    $html[] = array('name' => "pay_da", 'label' => "DA", 'editable' => true, 'width' => 250);

    $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $company_id)->get();
    foreach ($query as $k1 => $v1) {
      $html[] = array('name' => $v1->allowance_name . "0", 'label' => ucwords($v1->allowance_name), 'width' => 250);
    }
    $html[] = array('name' => "pay_annual_allowance", 'label' => "Annual Allowance", 'width' => 250);
    $html[] = array('name' => "pay_gratuity", 'label' => "Gratuity", 'width' => 250);
    $html[] = array('name' => "pay_esi", 'label' => "ESI", 'width' => 250);
    $html[] = array('name' => "pay_pf", 'label' => "PF", 'width' => 250);
    $html[] = array('name' => "pay_pt", 'label' => "PT", 'width' => 250);
    $html[] = array('name' => "pay_gross_pay", 'label' => "Gross Salary", 'width' => 250);
    $html[] = array('name' => "pay_net_pay", 'label' => "Net Pay", 'width' => 250);
    $html[] = array('name' => "pay_ctc_pay", 'label' => "CTC", 'width' => 250);
    $html[] = array('name' => "totaldays", 'label' => "Total Days", 'width' => 250);
    $html[] = array('name' => "presentdays", 'label' => "Attendance Days", 'width' => 250);
    $html[] = array('name' => "list_basic_salary", 'label' => "Basic", 'width' => 250);
    $html[] = array('name' => "list_hra", 'label' => "HRA", 'width' => 250);
    $html[] = array('name' => "list_da", 'label' => "DA", 'width' => 250);
    $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $company_id)->get();
    foreach ($query as $k1 => $v1) {
      $html[] = array('name' => $v1->allowance_name . "1", 'label' => ucwords($v1->allowance_name), 'width' => 250);
    }
    $html[] = array('name' => "list_pf_amount", 'label' => "PF Amount", 'width' => 250);
    $html[] = array('name' => "list_esi_amount", 'label' => "ESI Amount", 'width' => 250);
    $html[] = array('name' => "list_pt_amount", 'label' => "PT Amount", 'width' => 250);
    $html[] = array('name' => "pay_ctc_pay", 'label' => "CTC", 'width' => 250);
    $html[] = array('name' => "list_gross_salary", 'label' => "Gross Salary", 'width' => 250);
    $html[] = array('name' => "list_net_salary", 'label' => "Net Salary", 'width' => 250);
    $html[] = array('name' => "list_loan_deduction", 'label' => "Loan Deduction", 'width' => 250);
    $html[] = array('name' => "taken_cl", 'label' => "Taken Casual Leave", 'width' => 250);
    $html[] = array('name' => "taken_el", 'label' => "Taken Earn Leave", 'width' => 250);
    $html[] = array('name' => "taken_sl", 'label' => "Taken Sick Leave", 'width' => 250);
    $html[] = array('name' => "taken_od", 'label' => "Taken ON-Duty", 'width' => 250);
    $html[] = array('name' => "taken_partial_day", 'label' => "Partial Day", 'width' => 250);
    $html[] = array('name' => "taken_compensated_day", 'label' => "Compensated Day", 'width' => 250);
    $html[] = array('name' => "rem_el", 'label' => "Remaining Earn Leave", 'width' => 250);
    $html[] = array('name' => "rem_sl", 'label' => "Remaining Sick Leave", 'width' => 250);
    $html[] = array('name' => "rem_cl", 'label' => "Remaining Casual Leave", 'width' => 250);

    $this->data['datacolumn'] = json_encode($html);
    return view('employeepayreport.employeepayreport', $this->data);

  }

  public function payreportgrid()
  {
    $company_id = Session::get('companyid');
    $wh = '';
    $wh .= " and hr_employee_t.company_id=' $company_id' ";
    $log_id = Session::get('emp_id');
    if ($log_id != 1)
      $wh .= " and hr_employee_t.employee_id=' $log_id' ";

    $query_result = \DB::select(" SELECT employeetype.lookup_code as employee_type_name,
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

    if (count($query_result) > 0) {
      foreach ($query_result as $k => $v) {
        if ($v->department != '' && $v->department != null) {
          $array = json_decode($v->department);
          $query = \DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
          $deptnames = json_decode(json_encode($query), true);
          $query_result[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
        } else {
          $query_result[$k]->department = "";
        }

        if ($v->pay_allowance != '' && $v->pay_allowance != null) {
          $array = json_decode($v->pay_allowance);
          $query = \DB::table('m_allowance_tbl')->where('employee_type', $v->employee_type)->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $company_id)->get();
          foreach ($query as $k1 => $v1) {
            foreach ($array as $e => $r) {
              if (isset($r->{$v1->allowance_id})) {
                $query_result[$k]->{$v1->allowance_name . "0"} = $r->{$v1->allowance_id};
                break;
              }
            }
          }
        }

        if ($v->list_allowance != '' && $v->list_allowance != null) {
          $array = json_decode($v->list_allowance);
          $query = \DB::table('m_allowance_tbl')->where('employee_type', $v->employee_type)->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $company_id)->get();
          foreach ($query as $k1 => $v1) {
            foreach ($array as $e => $r) {
              if (isset($r->{$v1->allowance_id})) {
                $query_result[$k]->{$v1->allowance_name . "1"} = $r->{$v1->allowance_id};
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

  public function employeepayreportindex(Request $request)
  {

    // login viewers track purpose start

    $emp_id = auth()->user()->id;
    $date = date('Y-m-d');
    $date_time = date('Y-m-d h:s:i');
    $url = $request->path();
    $created_at = date('Y-m-d h:s:i');
    $updated_at = date('Y-m-d h:s:i');
    $com_id = \Session::get('organization');
    $type = "Pay Report";

    $existingRecord = Applicationviewers::where('emp_id', $emp_id)
      ->where('date', $date)
      ->where('url', $url)
      ->first(); // Use first() to retrieve a single model instance

    if ($existingRecord) {
      $existingRecord->update(['date_time' => $date_time]); // Call update() on the model instance

    } else {
      $trackingData = [
        'emp_id' => $emp_id,
        'date' => $date,
        'date_time' => $date_time,
        'url' => $url,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
        'organization_id' => $com_id,
        'type' => $type,
      ];

      Applicationviewers::create($trackingData);
    }

    $zoneWiseData = DB::table('hr_tracker_t')->get();
    // end

    // include(app_path() . '/functions/restrictcode.php');
    // restrict illegal menu entry purpose
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

    $company_id = \Session::get('companyid');
    $html = [];
    $html[] = array('name' => "id", 'label' => " ID", 'width' => 250, 'hidden' => true);
    $html[] = array('dataIndx' => "employee_number", 'title' => "Employee Name", 'width' => '15%');
    $html[] = array('dataIndx' => "department", 'title' => "Department", 'width' => '15%');
    $html[] = array('dataIndx' => "date", 'title' => "Payroll Date", 'width' => '15%', 'editable' => true, 'formatter' => 'date');
    $html[] = array('dataIndx' => "month", 'title' => "Month", 'width' => '15%');
    $html[] = array('dataIndx' => "year", 'title' => "Year", 'width' => '15%');
    $html[] = array('dataIndx' => "employee_type_name", 'title' => "Employee Type", 'width' => '15%');
    $html[] = array('dataIndx' => "payroll_type_name", 'title' => "Payroll Type", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_basic", 'title' => "Basic", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_hra", 'title' => "HRA", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_da", 'title' => "DA", 'editable' => true, 'width' => '15%');

    $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $company_id)->get();
    foreach ($query as $k1 => $v1) {
      $html[] = array('dataIndx' => $v1->allowance_id . "0", 'title' => ucwords($v1->allowance_name), 'width' => '15%');
    }
    $html[] = array('dataIndx' => "pay_annual_allowance", 'title' => "Annual Allowance", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_gratuity", 'title' => "Gratuity", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_esi", 'title' => "ESI", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_pf", 'title' => "PF", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_vpf", 'title' => "VPF", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_pt", 'title' => "PT", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_gross_pay", 'title' => "Gross Salary", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_net_pay", 'title' => "Net Pay", 'width' => '15%');
    $html[] = array('dataIndx' => "pay_ctc_pay", 'title' => "CTC", 'width' => '15%');
    $html[] = array('dataIndx' => "totaldays", 'title' => "Total Days", 'width' => '15%');
    $html[] = array('dataIndx' => "presentdays", 'title' => "Attendance Days", 'width' => '15%');
    $html[] = array('dataIndx' => "list_basic_salary", 'title' => "Basic", 'width' => '15%');
    $html[] = array('dataIndx' => "list_hra", 'title' => "HRA", 'width' => '15%');
    $html[] = array('dataIndx' => "list_da", 'title' => "DA", 'width' => '15%');
    $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $company_id)->get();
    foreach ($query as $k1 => $v1) {
      $html[] = array('dataIndx' => $v1->allowance_id . "1", 'title' => ucwords($v1->allowance_name), 'width' => '15%');
    }
    $html[] = array('dataIndx' => "list_pf_amount", 'title' => "PF Amount", 'width' => '15%');
    $html[] = array('dataIndx' => "list_vpf_amount", 'title' => "VPF Amount", 'width' => '15%');
    $html[] = array('dataIndx' => "list_esi_amount", 'title' => "ESI Amount", 'width' => '15%');
    $html[] = array('dataIndx' => "list_pt_amount", 'title' => "PT Amount", 'width' => '15%');
    $html[] = array('dataIndx' => "list_ctc_salary", 'title' => "CTC", 'width' => '15%');
    $html[] = array('dataIndx' => "list_gross_salary", 'title' => "Gross Salary", 'width' => '15%');
    $html[] = array('dataIndx' => "list_net_salary", 'title' => "Net Salary", 'width' => '15%');
    $html[] = array('dataIndx' => "list_loan_deduction", 'title' => "Loan Deduction", 'width' => '15%');
    $html[] = array('dataIndx' => "arrear_status", 'title' => "Arrear Status", 'width' => '15%');
    $this->data['datacolumn'] = json_encode($html);

    return view('employeepayreport.employeereport', $this->data);
  }


public function employeepayreport()
{
    $wh = '';

    /*Human Resources Marketing access control */
    $groupname = \Session::get('groupname');
    if ($groupname == "16" || $groupname == "13") {
        $wh .= " AND v2.payroll_type_name = 'Marketing'";
    }

    $loc   = \Session::get('location');
    $compy = \Session::get('companyid');

    $query_result = \DB::select("SELECT * FROM(
        SELECT employeetype.lookup_code as employee_type_name,
               payrolltype.lookup_code as payroll_type_name,
               CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,
               hr_employee_payroll_lists.month,
               hr_employee_payroll_lists.year,
               hr_employee_payroll_lists.date,
               hr_employee_payroll_lists.basic_pay as pay_basic,
               hr_employee_payroll_lists.hra_pay as pay_hra,
               hr_employee_payroll_lists.da_pay as pay_da,
               hr_employee_payroll_lists.allowance_pay as pay_allowance,
               hr_employee_payproposal.annual_allowance as pay_annual_allowance,
               hr_employee_payproposal.gratuity as pay_gratuity,
               (CASE WHEN hr_employee_payroll_lists.esi_pay=1 THEN 'Yes' ELSE 'No' END) as pay_esi,
               (CASE WHEN hr_employee_payroll_lists.pf_pay=1 THEN 'Yes' ELSE 'No' END) as pay_pf,
               (CASE WHEN hr_employee_payroll_lists.volunter_pf_pay!='NULL' THEN 'Yes' ELSE 'No' END) as pay_vpf,
               (CASE WHEN hr_employee_payroll_lists.pt_pay=1 THEN 'Yes' ELSE 'No' END) as pay_pt,
               hr_employee_payroll_lists.gross_pay as pay_gross_pay,
               hr_employee_payroll_lists.net_pay as pay_net_pay,
               hr_employee_payroll_lists.ctc_pay as pay_ctc_pay,
               hr_employee_payroll_lists.basic_salary as list_basic_salary,
               hr_employee_payroll_lists.hra as list_hra,
               hr_employee_payroll_lists.da as list_da,
               hr_employee_payroll_lists.allowance as list_allowance,
               (hr_employee_payroll_lists.pf - hr_employee_payroll_lists.vpf) as list_pf_amount,
               hr_employee_payroll_lists.vpf as list_vpf_amount,
               hr_employee_payroll_lists.esi as list_esi_amount,
               hr_employee_payroll_lists.pt as list_pt_amount,
               hr_employee_payroll_lists.ctc_pay as list_ctc_salary,
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
               (CASE WHEN hr_employee_payroll_lists.arrear_status=1 THEN 'Yes' ELSE 'No' END) as arrear_status,
               hr_employee_t.department
        FROM hr_employee_payroll_lists
        LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
        LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_payroll_lists.employee_id
        LEFT JOIN a_lookuplines_t as employeetype ON employeetype.lookuplines_id = hr_employee_payproposal.payroll_type
        LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type
    )v2 WHERE 1=1 $wh");

    // ---------- Collect all allowance_ids from both JSON fields ----------
    $allIds = [];

    foreach ($query_result as $row) {
        foreach (['pay_allowance', 'list_allowance'] as $field) {
            if (!empty($row->$field)) {
                $arr = json_decode($row->$field, true);
                if (is_array($arr)) {
                    foreach ($arr as $obj) {
                        if (is_array($obj)) {
                            $allIds = array_merge($allIds, array_keys($obj));
                        }
                    }
                }
            }
        }
    }

    $allIds = array_values(array_unique(array_filter($allIds)));

    // ---------- Build metadata map once: id => (type,name) ----------
    $allowMetaMap = [];
    if (!empty($allIds)) {
        $allowMetaMap = \DB::table('m_allowance_tbl')
            ->whereIn('allowance_id', $allIds)
            ->get(['allowance_id','type','allowance_name'])
            ->keyBy('allowance_id')
            ->toArray(); // [id => stdClass{allowance_id,type,allowance_name}]
    }

    // ---------- Deduction bucket function ----------
    $deductionBucket = function (string $name): string {
        $n = strtolower(trim($name));

        if (str_contains($n, 'insurance')) return 'insurance';
        if (str_contains($n, 'tds')) return 'tds';
        if (str_contains($n, 'loan')) return 'loan';

        // add more rules if you want:
        // if (str_contains($n, 'salary advance')) return 'loan';

        return 'other';
    };

    // ---------- Process rows ----------
    if (count($query_result) > 0) {
        foreach ($query_result as $k => $v) {

            // ---- Department names ----
            if (!empty($v->department)) {
                $array = json_decode($v->department, true);
                $query = \DB::table('m_department_lines_t')
                    ->whereIn('department_line_id', $array)
                    ->get();
                $deptnames = json_decode(json_encode($query), true);
                $query_result[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
            } else {
                $query_result[$k]->department = "";
            }

            // ---- Totals for pay_allowance JSON ----
            $totalAllowance = 0;
            $totalDeduction = 0;

            $dedInsurance = 0;
            $dedTds = 0;
            $dedLoan = 0;
            $dedOther = 0;

            if (!empty($v->pay_allowance)) {
                $arr = json_decode($v->pay_allowance, true);
                if (is_array($arr)) {
                    foreach ($arr as $obj) {
                        if (!is_array($obj)) continue;

                        foreach ($obj as $allowanceId => $amount) {
                            if ($amount === null || $amount === '' || !is_numeric($amount)) continue;

                            $meta = $allowMetaMap[$allowanceId] ?? null;
                            $type = $meta->type ?? null;

                            if ($type === 'Allowance') {
                                $totalAllowance += (float)$amount;
                            } elseif ($type === 'Deduction') {
                                $totalDeduction += (float)$amount;

                                $name = $meta->allowance_name ?? '';
                                $bucket = $deductionBucket($name);

                                if ($bucket === 'insurance') $dedInsurance += (float)$amount;
                                elseif ($bucket === 'tds') $dedTds += (float)$amount;
                                elseif ($bucket === 'loan') $dedLoan += (float)$amount;
                                else $dedOther += (float)$amount;
                            }
                        }
                    }
                }
            }

            // ---- Totals for list_allowance JSON ----
            $totalAllowance_pay = 0;
            $totalDeduction_pay = 0;

            $dedInsurance_pay = 0;
            $dedTds_pay = 0;
            $dedLoan_pay = 0;
            $dedOther_pay = 0;

            if (!empty($v->list_allowance)) {
                $arr = json_decode($v->list_allowance, true);
                if (is_array($arr)) {
                    foreach ($arr as $obj) {
                        if (!is_array($obj)) continue;

                        foreach ($obj as $allowanceId => $amount) {
                            if ($amount === null || $amount === '' || !is_numeric($amount)) continue;

                            $meta = $allowMetaMap[$allowanceId] ?? null;
                            $type = $meta->type ?? null;

                            if ($type === 'Allowance') {
                                $totalAllowance_pay += (float)$amount;
                            } elseif ($type === 'Deduction') {
                                $totalDeduction_pay += (float)$amount;

                                $name = $meta->allowance_name ?? '';
                                $bucket = $deductionBucket($name);

                                if ($bucket === 'insurance') $dedInsurance_pay += (float)$amount;
                                elseif ($bucket === 'tds') $dedTds_pay += (float)$amount;
                                elseif ($bucket === 'loan') $dedLoan_pay += (float)$amount;
                                else $dedOther_pay += (float)$amount;
                            }
                        }
                    }
                }
            }

            // ---- Assign output columns ----
            $query_result[$k]->total_allowance = $totalAllowance;
            $query_result[$k]->total_deduction = $totalDeduction;

            $query_result[$k]->total_allowance_pay = ROUND($totalAllowance_pay,0);
            $query_result[$k]->total_deduction_pay = $totalDeduction_pay;

            // Deduction split (from pay_allowance)
            $query_result[$k]->ded_insurance = $dedInsurance;
            $query_result[$k]->ded_tds = $dedTds;
            $query_result[$k]->ded_loan = $dedLoan;
            $query_result[$k]->ded_other = $dedOther;

            // Deduction split (from list_allowance)
            $query_result[$k]->ded_insurance_pay = $dedInsurance_pay;
            $query_result[$k]->ded_tds_pay = $dedTds_pay;
            $query_result[$k]->ded_loan_pay = $dedLoan_pay;
            $query_result[$k]->ded_other_pay = $dedOther_pay;

            // Remove old JSON fields if you don't want them in DataTable
            unset($query_result[$k]->pay_allowance);
            unset($query_result[$k]->list_allowance);
        }
    }

    return DataTables::of($query_result)->make(true);
}


  public function employeestdpayreportindex(Request $request)
  {

    // login viewers track purpose start

    $emp_id = auth()->user()->id;
    $date = date('Y-m-d');
    $date_time = date('Y-m-d h:s:i');
    $url = $request->path();
    $created_at = date('Y-m-d h:s:i');
    $updated_at = date('Y-m-d h:s:i');
    $com_id = \Session::get('organization');
    $type = "Pay Report - Standard";

    $existingRecord = Applicationviewers::where('emp_id', $emp_id)
      ->where('date', $date)
      ->where('url', $url)
      ->first(); // Use first() to retrieve a single model instance

    if ($existingRecord) {
      $existingRecord->update(['date_time' => $date_time]); // Call update() on the model instance

    } else {
      $trackingData = [
        'emp_id' => $emp_id,
        'date' => $date,
        'date_time' => $date_time,
        'url' => $url,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
        'organization_id' => $com_id,
        'type' => $type,
      ];

      Applicationviewers::create($trackingData);
    }

    $zoneWiseData = DB::table('hr_tracker_t')->get();
    // end

    // restrict illegal menu entry purpose
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


    return view('employeepayreport.employeestdpayreport', $this->data);
  }



  public function employeestdpayreport()
  {

    $wh = '';

    /*Human Resources Marketing access control */
    $groupname = \Session::get('groupname');
    if ($groupname == "16" || $groupname == "13") {
      $wh = " AND v2.payroll_type_name = 'Marketing'";
    }


    $loc = \Session::get('location');
    $compy = \Session::get('companyid');

    $query_result = \DB::select("SELECT * FROM(SELECT employeetype.lookup_code as employee_type_name,
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

    if (count($query_result) > 0) {
      foreach ($query_result as $k => $v) {
        if ($v->department != '' && $v->department != null) {
          $array = json_decode($v->department);
          $query = \DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
          $deptnames = json_decode(json_encode($query), true);
          $query_result[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
        } else {
          $query_result[$k]->department = "";
        }

        if ($v->pay_allowance != '' && $v->pay_allowance != null) {
          $array = json_decode($v->pay_allowance);
          $query = \DB::table('m_allowance_tbl')->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $compy)->get();

          foreach ($query as $k1 => $v1) {
            foreach ($array as $e => $r) {
              if (isset($r->{$v1->allowance_id})) {

                $query_result[$k]->{$v1->allowance_id . "0"} = $r->{$v1->allowance_id};
                break;

              } else {

                $query_result[$k]->{$v1->allowance_id . "0"} = '';
              }

            }
          }
        }



        unset($query_result[$k]->pay_allowance);
        unset($query_result[$k]->list_allowance);
      }
    }

    return DataTables::of($query_result)->make(true);
  }


  public function attendancetablegriddata()
  {

    $compy = \Session::get('companyid');
    $logged_user = \Session::get('emp_id');


    $wh = '';
    $wh .= " and v1.company_id=' $company_id' ";
    if ($_GET['_search'] == 'true') {
      $wh .= $this->jqgridsearchnotab('v1', $_GET['filters']);
    }

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if (!$sidx)
      $sidx = 1;
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
    if ($limit == 0)
      $limit = $count;

    if ($count > 0 && $limit > 0) {
      $total_pages = ceil($count / $limit);
    } else {
      $total_pages = 0;
    }
    if ($page > $total_pages)
      $page = $total_pages;
    $start = $limit * $page - $limit;
    if ($start < 0)
      $start = 0;
    $comp = \Session::get('companyid');
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
    $result1 = \DB::select($download_SQL);

    $result1 = collect($result1)->map(function ($x) {
      return (array) $x;
    })->toArray();
    if (isset($_GET['download'])) {
      return $result1;
    }

    $result = \DB::select($SQL);


    // dd($SQL);
    $responce->rows[] = '';
    $responce->rows = $result;
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
    $wh = '';
    // $wh .= " and v1.company_conribute_id=' $logged_user' ";
    if ($_GET['_search'] == 'true') {
      $wh .= $this->jqgridsearchnotab('v1', $_GET['filters']);
    }

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if (!$sidx)
      $sidx = 1;
    $result = \DB::select("select * from (SELECT hr_company_contribute_esi.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM   hr_company_contribute_esi  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_esi.emp_id ) v1 where 1=1 $wh GROUP BY v1.company_conribute_id");
    $count = count($result);
    if ($limit == 0)
      $limit = $count;

    if ($count > 0 && $limit > 0) {
      $total_pages = ceil($count / $limit);
    } else {
      $total_pages = 0;
    }
    if ($page > $total_pages)
      $page = $total_pages;
    $start = $limit * $page - $limit;
    if ($start < 0)
      $start = 0;
    $comp = \Session::get('companyid');
    $SQL = "select * from (SELECT hr_company_contribute_esi.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM   hr_company_contribute_esi  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_esi.emp_id )v1 where 1=1  $wh ORDER by v1.company_conribute_id $sord LIMIT $start , $limit";


    $download_SQL = "select * from (SELECT hr_company_contribute_esi.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.esi_no FROM   hr_company_contribute_esi  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_esi.company_conribute_id )v1 where 1=1 $wh ORDER by v1.company_conribute_id $sord";
    $result1 = \DB::select($download_SQL);
    $result1 = collect($result1)->map(function ($x) {
      return (array) $x;
    })->toArray();
    if (isset($_GET['download'])) {
      return $result1;
    }

    $result = \DB::select($SQL);


    // dd($SQL);
    $responce->rows[] = '';
    $responce->rows = $result;
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
    $wh = '';


    if ($_GET['_search'] == 'true') {
      $wh .= $this->jqgridsearchnotab('v1', $_GET['filters']);
    }

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if (!$sidx)
      $sidx = 1;
    $result = \DB::select("select * from (SELECT hr_company_contribute_pfhr_company_contribute_pf.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.pf_no FROM   hr_company_contribute_pf  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_pf.emp_id  ) v1 where 1=1 $wh GROUP BY v1.employee_conribute_id");
    $count = count($result);
    if ($limit == 0)
      $limit = $count;

    if ($count > 0 && $limit > 0) {
      $total_pages = ceil($count / $limit);
    } else {
      $total_pages = 0;
    }
    if ($page > $total_pages)
      $page = $total_pages;
    $start = $limit * $page - $limit;
    if ($start < 0)
      $start = 0;
    $comp = \Session::get('companyid');
    $SQL = "select * from (SELECT hr_company_contribute_pf.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.pf_no FROM   hr_company_contribute_pf  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_pf.emp_id   )v1 where 1=1  $wh ORDER by v1.employee_conribute_id $sord LIMIT $start , $limit";


    $download_SQL = "select * from (SELECT hr_company_contribute_pf.*,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_number,hr_employee_t.pf_no FROM   hr_company_contribute_pf  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_company_contribute_pf.emp_id   )v1 where 1=1 $wh ORDER by v1.employee_conribute_id $sord";
    $result1 = \DB::select($download_SQL);
    $result1 = collect($result1)->map(function ($x) {
      return (array) $x;
    })->toArray();
    if (isset($_GET['download'])) {
      return $result1;
    }

    $result = \DB::select($SQL);


    // dd($SQL);
    $responce->rows[] = '';
    $responce->rows = $result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;

    echo json_encode($responce);
  }


  public function ptreportindex(Request $request)
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

    $company_id = \Session::get('companyid');
    $html = [];
    $html[] = array('name' => "id", 'label' => " ID", 'width' => 250, 'hidden' => true);
    $html[] = array('dataIndx' => "employee_number", 'title' => "Employee Name", 'width' => '20%');
    $html[] = array('dataIndx' => "department", 'title' => "Department", 'width' => '20%');

    $html[] = array('dataIndx' => "month", 'title' => "Month", 'width' => '20%');
    $html[] = array('dataIndx' => "year", 'title' => "Year", 'width' => '20%');




    $html[] = array('dataIndx' => "list_pt_amount", 'title' => "PT Amount", 'width' => '20%');


    $this->data['datacolumn'] = json_encode($html);

    return view('employeepayreport.ptreport', $this->data);
  }


  public function ptreport()
  {


    $loc = \Session::get('location');
    $compy = \Session::get('companyid');

    $query_result = \DB::select("select * FROM(SELECT employeetype.lookup_code as employee_type_name,
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
                 LEFT JOIN a_lookuplines_t as payrolltype ON payrolltype.lookuplines_id = hr_employee_payproposal.employee_type )v2");
    // dd($query_result);


    if (count($query_result) > 0) {
      foreach ($query_result as $k => $v) {
        if ($v->department != '' && $v->department != null) {
          $array = json_decode($v->department);
          $query = \DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
          $deptnames = json_decode(json_encode($query), true);
          $query_result[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
        } else {
          $query_result[$k]->department = "";
        }

        if ($v->pay_allowance != '' && $v->pay_allowance != null) {
          $array = json_decode($v->pay_allowance);
          $query = \DB::table('m_allowance_tbl')->where('employee_type', $v->employee_type)->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $compy)->get();
          foreach ($query as $k1 => $v1) {
            foreach ($array as $e => $r) {
              if (isset($r->{$v1->allowance_id})) {
                $query_result[$k]->{$v1->allowance_id . "0"} = $r->{$v1->allowance_id};
                break;
              }

            }
          }
        }

        if ($v->list_allowance != '' && $v->list_allowance != null) {
          $array = json_decode($v->list_allowance);
          $query = \DB::table('m_allowance_tbl')->where('employee_type', $v->employee_type)->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $compy)->get();
          foreach ($query as $k1 => $v1) {
            foreach ($array as $e => $r) {
              if (isset($r->{$v1->allowance_id})) {
                $query_result[$k]->{$v1->allowance_id . "1"} = $r->{$v1->allowance_id};
                break;
              }

            }
          }
        }
        unset($query_result[$k]->pay_allowance);
        unset($query_result[$k]->list_allowance);
      }
    }


    return DataTables::of($query_result)->make(true);


  }


}
