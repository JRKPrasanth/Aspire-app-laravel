<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB,Session;
use DateTime;

class GeneratepayrollController extends Controller
{   
        public function __construct()
    {
            $this->data['pageModule']=\Request::route()->getName();
           
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs();    
     }
    
    //payrollgenerate index page load function start 
    public function index()
    {

//     dd($journal_lines_data);


       return view('approvepayroll.generatepayroll',$this->data);
    }
    public function hrmsjournal()
    {


      $employee_data=  \DB::select("SELECT hr_employee_payroll_lists.*,(select hr_employee_t.employee_number from hr_employee_t where hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id)as emp_number,(select hr_employee_t.department from hr_employee_t where hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id)as department  FROM `hr_employee_payroll_lists` WHERe journal_status='0'  ORDER by month asc,year asc,employee_id asc");
     // dd($employee_data);
      $prevoius_emp_id=0;
      
      foreach($employee_data as $employee)
      {
       // dd($employee);
      	$payroll_id=$employee->id;
      	\DB::update("update hr_employee_payroll_lists set journal_status=1 where id='$payroll_id' ");
        
                   $emp_code            = $employee->emp_number;
                   $emp_id                  =intval($employee->employee_id);
                   $department_check        = json_decode($employee->department);
                   $arrear_status=$employee->arrear_status;
                   
                   if($prevoius_emp_id==$emp_id)
                   {
                      $arr=1; 
                   }
                   else
                   {
                     $arr=0;  
                   }
                   $prevoius_emp_id=$emp_id;
                   $month=$employee->month;
                   $year=$employee->year;
                   
                   $dep=$department_check[0];
                   $i=1;
                   while($i>0)
                   {
                       $dep1=\DB::select("SELECT `parent_class_id` FROM `m_department_lines_t` WHERE `department_line_id`='$dep'");
                       if($dep1[0]->parent_class_id==0)
                       {
                           $dep=$dep;
                           $i=0;
                       }
                       else
                       {
                          $dep=$dep1[0]->parent_class_id;  
                          $i=2;
                       }
                   }
                   
        if($arrear_status==1)
        {
       $journal_name="PAYROLL-ARREARS-".$emp_code."-".$month."-".$year;
       $journal_name_esi="PAYROLL/PF/ESI-ARREARS-".$emp_code."-".$month."-".$year;
       

        }
        else
        {
            $journal_name_esi="PAYROLL/PF/ESI-".$emp_code."-".$month."-".$year;
            $journal_name="PAYROLL-".$emp_code."-".$month."-".$year;
        }
        
         $esi=\DB::select("SELECT * FROM `hr_company_contribute_esi` where emp_id='$emp_id' and month='$month' and year='$year' and arrear_status='$arrear_status' limit 1");
            
        $pf=\DB::select("SELECT * FROM `hr_company_contribute_pf` where emp_id='$emp_id' and month='$month' and year='$year' and arrear_status='$arrear_status' limit 1");
            
     
        
        
       $date = new DateTime($year.'-'.$month.'-01');


$date->modify('last day of this month');
$last_day_this_month = $date->format('Y-m-d');

        
        
       $payrolldate=$last_day_this_month;
       $loc=\Session::get('location');
       $compy=\Session::get('companyid'); 
       
       
        $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id)values('$journal_name','PAYROLL','$payrolldate','','APPROVED','$compy','$loc')");
        $jid =DB::getPdo()->lastInsertId();
       
       
           
    
  $accounts_data= \DB::select("select * from f_hr_account_setting_t where department_id=$dep");

 $accounts_data_allowance= \DB::select("select *,(SELECT m_allowance_tbl.type FROM `m_allowance_tbl` where m_allowance_tbl.allowance_id=f_hr_account_allowance_setting_lines_t.allowance_id) as type from f_hr_account_allowance_setting_t left join f_hr_account_allowance_setting_lines_t on f_hr_account_allowance_setting_lines_t.account_allowance_setting_id=f_hr_account_allowance_setting_t.account_allowance_setting_id where department_id='$dep'");
 
 //dd($accounts_data_allowance);
 
 $basic_account=$employee->basic_salary;
 $hra_account=$employee->hra;
 $da_account=$employee->da;
 $annual_allowance_account=$employee->annual_allowance;
 $gratuity_account=$employee->gratuity;
 
 
   $data_acc_debit=["basic_account_id","hra_account_id","da_account_id","annual_allowance_account_id","gratuity_account_id"];
   
  $data_acc_value_debit=[$basic_account,$hra_account,$da_account,$annual_allowance_account,$gratuity_account];
  
   $data_acc_credit=["professional_account_id","esi_employee_account_id","pf_employee_account_id","volunter_pf_account_id","advance_account_id","salary_account_id"];
   
$pt_account=$employee->pt;
$esi_employee_account=0;
$esi_company_account=0;
if(count($esi)>0)
{
$esi_employee_account=$esi[0]->amount_employee;
$esi_company_account=$esi[0]->amount;
}

$pf_employee_account=0;
$volunter_account=0;
$pf_company_account=0;
$pf_company1_account=0;

if(count($pf)>0)
{
$pf_employee_account=$pf[0]->amount_employee;
$volunter_account=$pf[0]->volunter_pf;

$pf_company_account=$pf[0]->amount;
$pf_company1_account=$pf[0]->amount1;


}


$advance_account=$employee->loan_deduction;
$net_account=$employee->net_salary;

  $data_acc_value_credit=[$pt_account,$esi_employee_account,$pf_employee_account,$volunter_account,$advance_account,$net_account];



  $data_acc_exp=["esi_company_account_id","pf_company_account_id","pf_company1_account_id"];
  

  
  $data_acc_value_exp=[$esi_company_account,$pf_company_account,$pf_company1_account];
                   

$tkey=0;
$debit_amount=0;
$credit_amount=0;
$journal_lines_data=array();
 foreach($data_acc_debit as $k1=>$v1){           

if($data_acc_value_debit[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="EMPLOYEE";
                $journal_lines_data[$tkey]['reference_id']=$emp_id;
                $journal_lines_data[$tkey]['account_id']=$accounts_data[0]->$v1; 
                $debit_amount=$debit_amount+round($data_acc_value_debit[$k1],2);
                
                $journal_lines_data[$tkey]['debit_amount']=round($data_acc_value_debit[$k1],2);
                $journal_lines_data[$tkey]['credit_amount']='';
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=$employee->created_by;
                $journal_lines_data[$tkey]['created_at']=$employee->created_at;
                $journal_lines_data[$tkey]['last_updated_by']=$employee->created_by;
                $journal_lines_data[$tkey]['updated_at']=$employee->created_at;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                
                 $tkey++;
}
                   
  }
  
  
 $allowance_deduction=json_decode($employee->allowance); 
 //dd($allowance_deduction);
 $accounts_data_allowance=collect($accounts_data_allowance);

foreach($allowance_deduction as $i=>$v)
{
    foreach($v as $ii=>$vv)
    {
      $filter_data=$accounts_data_allowance->where('allowance_id',$ii)->where('type','Allowance');
      $filter_data->all();
      $filter_data_all=array();
      foreach($filter_data as $filter_data_val)
      {
          $filter_data_all[]=$filter_data_val;
          break;
      }
      
      if(count($filter_data_all)>0)
      {
          $debit_amount=$debit_amount+round($vv,2);
      }
      
      if(count($filter_data_all)>0 && $vv!=0)
      {
                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="EMPLOYEE";
                $journal_lines_data[$tkey]['reference_id']=$emp_id;
                $journal_lines_data[$tkey]['account_id']=$filter_data_all[0]->account_structure_id; 
                
                
                if($vv<0)
                {
                $journal_lines_data[$tkey]['debit_amount']='';
                $journal_lines_data[$tkey]['credit_amount']=round($vv*-1,2);
                }
                else
                {
                 $journal_lines_data[$tkey]['debit_amount']=round($vv,2);
                $journal_lines_data[$tkey]['credit_amount']='';   
                }
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=$employee->created_by;
                $journal_lines_data[$tkey]['created_at']=$employee->created_at;
                $journal_lines_data[$tkey]['last_updated_by']=$employee->created_by;
                $journal_lines_data[$tkey]['updated_at']=$employee->created_at;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                
                 $tkey++;
      }
      
    }
}

foreach($allowance_deduction as $i=>$v)
{
    foreach($v as $ii=>$vv)
    {
      $filter_data=$accounts_data_allowance->where('allowance_id',$ii)->where('type','Deduction');
      $filter_data->all();
      $filter_data_all=array();
      foreach($filter_data as $filter_data_val)
      {
          $filter_data_all[]=$filter_data_val;
          break;
      }
      
        if(count($filter_data_all)>0)
      {
           $credit_amount=$credit_amount+round($vv,2);
      }
      
      if(count($filter_data_all)>0 && $vv!=0)
      {
                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="EMPLOYEE";
                $journal_lines_data[$tkey]['reference_id']=$emp_id;
                $journal_lines_data[$tkey]['account_id']=$filter_data_all[0]->account_structure_id; 
                
                
                 if($vv<0)
                {
                $journal_lines_data[$tkey]['debit_amount']=round($vv*-1,2);
                $journal_lines_data[$tkey]['credit_amount']='';
                }
                else
                {
                $journal_lines_data[$tkey]['debit_amount']='';
                $journal_lines_data[$tkey]['credit_amount']=round($vv,2);  
                }
                
                
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=$employee->created_by;
                $journal_lines_data[$tkey]['created_at']=$employee->created_at;
                $journal_lines_data[$tkey]['last_updated_by']=$employee->created_by;
                $journal_lines_data[$tkey]['updated_at']=$employee->created_at;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                
                 $tkey++;
      }
      
    }
}


  foreach($data_acc_credit as $k1=>$v1){           

if($data_acc_value_credit[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="EMPLOYEE";
                $journal_lines_data[$tkey]['reference_id']=$emp_id;
                $journal_lines_data[$tkey]['account_id']=$accounts_data[0]->$v1;  
                
                $journal_lines_data[$tkey]['debit_amount']='';
                
                $credit_amount=$credit_amount+round($data_acc_value_credit[$k1],2);
                
                $journal_lines_data[$tkey]['credit_amount']=round($data_acc_value_credit[$k1],2);
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=$employee->created_by;
                $journal_lines_data[$tkey]['created_at']=$employee->created_at;
                $journal_lines_data[$tkey]['last_updated_by']=$employee->created_by;
                $journal_lines_data[$tkey]['updated_at']=$employee->created_at;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                
                 $tkey++;
}
                   
  }


$diff=round($debit_amount-$credit_amount,2);

 if($diff !=0)
 {
                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="EMPLOYEE";
                $journal_lines_data[$tkey]['reference_id']=$emp_id;
                $journal_lines_data[$tkey]['account_id']=460;  
                if($diff > 0)
                {
                $journal_lines_data[$tkey]['debit_amount']=0;
                $journal_lines_data[$tkey]['credit_amount']=$diff;
              }
              else
              {
                $journal_lines_data[$tkey]['debit_amount']=$diff*(-1);
                 $journal_lines_data[$tkey]['credit_amount']=0;
              }
              $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=$employee->created_by;
                $journal_lines_data[$tkey]['created_at']=$employee->created_at;
                $journal_lines_data[$tkey]['last_updated_by']=$employee->created_by;
                $journal_lines_data[$tkey]['updated_at']=$employee->created_at;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                
                 $tkey++;  
             
 }


   \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 



$journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id)values('$journal_name_esi','PAYROLL/PF/ESI','$payrolldate','','APPROVED','$compy','$loc')");
        $jid = DB::getPdo()->lastInsertId();
   $tkey=0;
   $journal_lines_data=array();
   
   
    $credit_acc=[89,87,88];
    foreach($data_acc_exp as $k1=>$v1){           

if($data_acc_value_exp[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="EMPLOYEE";
                $journal_lines_data[$tkey]['reference_id']=$emp_id;
                $journal_lines_data[$tkey]['account_id']=$credit_acc[$k1];  
                
                $journal_lines_data[$tkey]['debit_amount']=$data_acc_value_exp[$k1];;
                $journal_lines_data[$tkey]['credit_amount']='';
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=$employee->created_by;
                $journal_lines_data[$tkey]['created_at']=$employee->created_at;
                $journal_lines_data[$tkey]['last_updated_by']=$employee->created_by;
                $journal_lines_data[$tkey]['updated_at']=$employee->created_at;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                
                 $tkey++; 
}
   
                            }
   
    foreach($data_acc_exp as $k1=>$v1){           

if($data_acc_value_exp[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="EMPLOYEE";
                $journal_lines_data[$tkey]['reference_id']=$emp_id;
                $journal_lines_data[$tkey]['account_id']=$accounts_data[0]->$v1;  
                
                $journal_lines_data[$tkey]['debit_amount']='';
                $journal_lines_data[$tkey]['credit_amount']=$data_acc_value_exp[$k1];
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=$employee->created_by;
                $journal_lines_data[$tkey]['created_at']=$employee->created_at;
                $journal_lines_data[$tkey]['last_updated_by']=$employee->created_by;
                $journal_lines_data[$tkey]['updated_at']=$employee->created_at;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                
                 $tkey++; 
             
}
   
  }
 
                             \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 








                   
      }        

        //payrollgenerate index page load function end
    }
    // get company data
	function get_company($emp_id)
    {
        $query = \DB::select("SELECT * FROM hr_employee_t WHERE `employee_id`='$emp_id'");
        if(count($query)>0)
        {
           $company_id = $query[0]->company_id;
        }
        return $company_id;
    }
	// generate payroll
    public function payrollgenerate() 
    {
     
                $monthly_entry_check=\DB::select("SELECT a_lookuplines_t.lookup_code FROM `a_lookuplines_t` where a_lookuplines_t.lookuplines_id=".$_GET['source']);
                if(count($monthly_entry_check)>0){
                            $name_month=$monthly_entry_check[0]->lookup_code;
                            if($name_month!="MONTHLY ENTRY"){
        $current_year    = $_GET['year'];
        $source  =   $_GET['source'];
        $department    =   $_GET['department'];
        $employee    =   $_GET['employee'];
        $error_msg = "";
        // get month name from month
        $comp=\Session::get('companyid');
        $account_month=$_GET['month']."-".$current_year;
        $dateObj   = DateTime::createFromFormat('!m', $_GET['month']);
        $month_name = $dateObj->format('F');
       	$month =  $_GET['month'];
        // get cutoff date for the year and company
        $cutoff_date    = DB::table('hr_emp_payroll_cutoff')->where('company',$comp)->whereYear('cutoff_date',$current_year)->get();
        if(count($cutoff_date)>0)
        {
                $cutoff_date    = $cutoff_date[0]->cutoff_date;
                $cut_off        = explode('-', $cutoff_date);
               
                // for cutoff date 31 mean set cutoff start date as 1
                if($cut_off[2] == '31')
                {
                        $cuttoff_start_date = 1; 
                     
                }
                  // for cutoff date  not 31 mean set cutoff start date as addition of one date
                else
                {
                        $cuttoff_start_date = $cut_off[2] ;
                     
                }
                 // for  first month  set cutoff date and start date
             
               $start_month=$month-1;
                if($month=='01')
                {	

                        $cutoff_date    = date($current_year.'-'.$month.'-'.($cuttoff_start_date-1));
                        $start_date     = date(($current_year-1).'-12-'.$cuttoff_start_date);
                }
                 // for other than first month  set cutoff date and start date
                else
                {

                      $cutoff_date       = date($current_year.'-'.$month.'-'.($cuttoff_start_date-1));
                     $start_date       = date($current_year.'-'.$start_month.'-'.$cuttoff_start_date);
                }
 // dd($start_date."->".$cutoff_date); 
             // set date start and end as correct format
            $begin      = new DateTime($start_date);
            
            $end        = new DateTime($cutoff_date);
            $start_date =   $begin->format('Y-m-d');
             
            $end_date   =   $end->format('Y-m-d');
            // get current month and year and date
            $current_date   = date("Y-m-d");     
            $current_month  = date('m');
            $current_day    = date('d');
            $date_reach = true;
            // check current month and generate month is same or not check
            if($current_month == $month)
            {
                $date_reach = true;
            } 
            $list_of_user = ''; 
            // get not days between the date
            if($cut_off[2]=='31')
            {
               $no_days ="31";
            }
            else
            {
	       $no_day=date_diff(date_create($start_date),date_create($end_date));
               $no_days =$no_day->format("%a")+1;
              
            }
			$begin=new DateTime('2019-05-01');
			$end=new DateTime('2019-05-31');       
            // if month reach  is true mean execute
		if($date_reach)
		{ 
                    // check for the month and year attendence sync or not
            $attendance_exists  =   DB::table("hr_emp_attendence")->whereMonth('atten_date',$month)->whereYear('atten_date',$current_year)->get();    
            // if sync mean its execute
            if(count($attendance_exists)>0)
            { 	
                // query for get employee and payproposal and active employee details
                $employees  = DB::table('hr_employee_t')->join("hr_employee_payproposal",function($join) use ($source,$comp)
                {		
                        $join->on("hr_employee_payproposal.employee_id","=","hr_employee_t.employee_id")
                        ->where("hr_employee_payproposal.payroll_type","=",$source);
                })->select('hr_employee_t.*','hr_employee_payproposal.*')->where('hr_employee_t.active','Yes')->get();
                                $emp_data_payroll=array();
				$emp_data_attend = array();
				$emp_data_type = array();
				$emp_data_shift = array();
				$emp_data_payproposal = array();
                                $payroll_settings   =   DB::table("hr_emp_payroll_settings_t")->get();
                                //if employee data is have mean execute
                         	if(count($employees)>0) {
			
                                   $basic_account=0;
                                   $hra_account=0;
                                   $da_account=0;
                                   $net_account=0;
                                   $esi_employee_account=0;
                                   $esi_company_account=0;
                                   $pf_employee_account=0;
                                   $pf_company_account=0;
                                   $pf_company1_account=0;
                                   $pt_account=0;
                                   $tax_account=0;
                                   $ot_account=0;
                                   $advance_account=0;
                                   $allow_acc=array();
                                   $deduct_acc=array();
                                   $annual_allowance_account=0;
                                   $gratuity_account=0;
                                   $gross_salary_account=0;
                                   $volunter_account=0;
                                    // each employee generate payroll start
								
				foreach($employees as $key=>$employee) 
				{
				   $emp_code   		    = $employee->employee_number;
				   $emp_id     	            = $employee->employee_id;
				   $department_check        = json_decode($employee->department);
				   $dep=$department_check[0];
				   $i=1;
				   while($i>0)
				   {
					   $dep1=\DB::select("SELECT `parent_class_id` FROM `m_department_lines_t` WHERE `department_line_id`='$dep'");
					   if($dep1[0]->parent_class_id==0)
					   {
						   $dep=$dep;
						   $i=0;
					   }
					   else
					   {
						  $dep=$dep1[0]->parent_class_id;  
						  $i=2;
					   }
				   }
				   
                                   if ($department==$dep)
                                   {
				   $emp_type                = $employee->employee_type;
				   $employee_position       = $employee->position;
				   $compid   	            = $employee->company_id;
                                   $loc_id=json_decode($employee->location_id);
				 
				 $locationid   	    =$loc_id[0] ;
                                    
                                   $casual_leave            = 0;
                                   $sick_leave 	            = 0;
                                 //employee already payroll generated check
				$payroll_exists = DB::table("hr_employee_payroll_lists")->where('month',$month)->where('year',$current_year)->where('employee_id',$emp_id)->get();			
        if(count($payroll_exists)>0)
        {
          \DB::select("delete  from hr_employee_payroll_lists where id='".$payroll_exists[0]->id."'");
        }	
			    $payroll_exists = DB::table("hr_employee_payroll_lists")->where('month',$month)->where('year',$current_year)->where('employee_id',$emp_id)->get();
              // check attendance exists or not
                                $attendance_exists_employee  =   1;
      
                                if($attendance_exists_employee==1){
                              
				if (count($payroll_exists) == 0) 
				{
					$begin  =   new DateTime($start_date);
					$end    =   new DateTime($end_date);
                                        // attendance setting exists
					$attendence_setting=DB::table("hr_attendance_settings_t")->where('employee_type',$emp_type)->where('company_id',$comp)->get();
                                      if(count($attendence_setting)>0) 
					{
                                         
						$shift_type = DB::table('hr_employee_shift_details')->where('employee_id',$emp_id)->get();
                                                if(count($shift_type)>0)
                                                {
						$presentdays = 0;
						$pres_abs = 0;
						$no_holidays = 0;
						$absent    = 0;
						$holi_day = 0;
						$abs_ent   = 0;
						$compensated_days  = 0;
						$ot_t  = 0;
						$weekoff           = 0;
						$holidayoff        = 0;
						$hour	= 0;
					        $end1 	  = 0;
						$start 	  = 0;
						$ot_t_t   =  0;
						$ot_t     =  0;
					        $earn_salary=0;
						$el=0;
                                                $sl=0;
                                                $cl=0;
                                                $od=0;
                                                $partial=0;

						  $checkpunch=0;
                                                    $lastpunch=0;
                                                    $fhalf_day=0;
                                                    $fabsent_day=0;
                                                    $prefix_day=0;
                                                    $suffix_day=0;
                                                    $pref_suff_day=0;
                                                    $late_day_hrs=0;
                                                    $full_day_hrs=0;
                                                    $half_day_hrs=0;
                                                    $early_day_hrs=0;
                                                    $month_day_hrs=0;
                                                    $full_late_day =0;
                                                    $half_late_day=0;
                                                    $half_early_day=0;
                                                    $month_early_day=0;
                                                    $late_month_hrs=0;
                                                    $month_day_hrs=0;
                                                    $half=0;
                                               // date loop start 
                                      
						while ($begin <= $end)
						{
                                                    $date=(string)$begin->format("Y-m-d");
                                                    // get casual leave sick leave for employee balance
                                                    $leave_policy = DB::table('hr_employee_t')->where('employee_id',$emp_id)->get();
                                                    if(count($leave_policy)>0)
                                                    {
                                                         $casual_leave = $leave_policy[0]->c_l;
                                                         $sick_leave 	= $leave_policy[0]->s_l;
                                                         $earn_leave 	= $leave_policy[0]->e_l;
                                                    }
                                                     
                                                   // get employee checkin details
                                                    $atten_detail=DB::table('hr_emp_attendence')->where('emp_id',$emp_code)->where('atten_date','like', $date.'%')->get();

                                                 
                                                    $date_explode = explode('-',$date);
                                                    $y1 =   $date_explode[0];
                                                    $m1 =   $date_explode[1];
                                                    $d1 =   $date_explode[2];
                                                    $d1 =   $d1-1;
						         // get shift for a day
                                                        $shift_type = DB::table('hr_employee_shift_details')->where('employee_id',$emp_id)->get();
                                                        $list_of_timig  = json_decode($shift_type[0]->shift_type);

                                                        $shift_type  	 = $list_of_timig[$d1][0];
                                                        //get shift timing
                                                        $query3         = DB::table("shift_timing")->where('shift_name',$shift_type)->get();
                                                        
                                                        if(count($query3)>0){
                                                        
                                                        //based on shift get start  time and end time
                                                          $start_time     = $query3[0]->start_time;
                                                          $end_time       = $query3[0]->end_time;
                                                          $ss_time= new DateTime($start_time);
                                                          $ee_time= new DateTime($end_time);
                                                          $to_time     = $ss_time->diff($ee_time) ;
                                                          $to_time=strtotime($to_time->format("%H:%I:%S"));
                                                          
                                                          $start_time= new DateTime($start_time);
                                                          $end_time= new DateTime($end_time);
                                                          $start_times=strtotime($start_time->format('H:i:s'));
                                                          $end_times=strtotime($end_time->format('H:i:s'));
                                                          $shift_time=$end_times-$start_times;
                                                          
                                                           $end_s=strtotime(date('H:i:s',$end_times));
                                                           $start_s=$start_times-strtotime("00:00:00");
                                                         $shift_s=$end_s-$start_s;
                                                        // duration for a day 
                                                    
                                                         
                                                       
                                              
                                                        // ot calculation
                                                    $ot_formula=$attendence_setting[0]->ot_formula;
                                                    $ot_maxtime=$this->mintohr($attendence_setting[0]->max_ot,$to_time);
                                                    $ot_mintime=$this->mintohr($attendence_setting[0]->min_ot,$to_time);
                                                  
                                                    //get attendance rules from attendance settings
                                                   
                                                    $rules=json_decode($attendence_setting[0]->attendance_rules);
                                                    $rules_val=json_decode($attendence_setting[0]->rules_data);
                                                    $sunday_count = 0;
                                                     
                                                 //   dd($rules);
                                               //   dd($atten_detail)
                                               if(count($atten_detail)>0)
                                                        {
														
															
                                                     //consider early coming punch
                                                    if(in_array("1",$rules))
                                                    {
                                                            $checkpunch="1";
                                                    }
                                                    //consider late going punch
                                                    if(in_array("2",$rules))
                                                    {
                                                            $lastpunch="1";
                                                    }
                                                  
                                                    //Calculate If HalfDay If work Duration Is Less Than given hrs
                                                    if(in_array("5",$rules))
                                                    {
                                                        $fhalf_day="1";
                                                        
                                                        $half_day_hrs= $this->mintohr($rules_val[0],$to_time);
                                                       
                                                      
                                                        
                                                    }
                                                    //Calculate Absent If work Duration Is Less Than given hrs
                                                    if(in_array("6",$rules))
                                                    {
                                                        $fabsent_day="1";
                                                        $full_day_hrs=$this->mintohr($rules_val[1],$to_time); 
                                                        
                                                    }
                                                    
                                                    //Mark weekly Off and Holiday as Absent for Prefix Day is Absent
                                                    if(in_array("9",$rules))
                                                    {
                                                        $prefix_day="1";
                                                    } 
                                                
                                                    //Mark weekly Off and Holiday as Absent for Suffix Day is Absent
                                                    if(in_array("10",$rules))
                                                    {
                                                        $suffix_day="1";     
                                                    } 
                                                    // Mark weekly Off and Holiday as Absent if both Suffix Day and Prefix Day is Absent
                                                    if(in_array("11",$rules))
                                                    {
                                                             $pref_suff_day="1";
                                                    }
                                                    //Mark FullDay Absent When late For
                                                    if(in_array("12",$rules))
                                                    {
                                                       $full_late_day="1";
                                                       $secs = strtotime($this->mintohr($rules_val[4]))-strtotime("00:00:00");
                                                       $full_late_day_hrs = strtotime(date("H:i:s",$start_times+$secs));
                                                       
                                                       
                                                    }
                                                    //Mark Half Day If late by
                                                    if(in_array("13",$rules))
                                                    {
                                                         $half_late_day="1";
                                                       $secs = strtotime($this->mintohr($rules_val[5]))-strtotime("00:00:00");
                                                       $late_day_hrs = strtotime(date("H:i:s",$start_times+$secs));
                                                      
                                                    }  
                                                    // Mark Half Day If Early Going by
                                                    if(in_array("14",$rules))
                                                    {
                                                        $half_early_day="1";
                                                        $secs = strtotime($this->mintohr($rules_val[6]))-strtotime("00:00:00");
                                                        $early_day_hrs = strtotime(date("H:i:s",$end_times-$secs));
                                                      
                                                      
                                                    }
                                                     // Per Month If Late By
                                                    if(in_array("15",$rules))
                                                    {
                                                        $month_early_day="1";
                                                        $month_day_hrs = $rules_val[7];
                                                      
                                                    }
                                                    // get check in check out of date wise
                                                        $new_start  =  new DateTime($atten_detail[0]->check_in);
                                                        $new_end    =  new DateTime($atten_detail[0]->check_out);
                                                        $new_start=strtotime($new_start->format('H:i:s'));
                                                        $new_end=strtotime($new_end->format('H:i:s'));
							
							$holi_day=0;
							$abs_ent=0; 
                                             
                                                        //  both conside early coming and going is check and attendane for that date mean
						if($atten_detail &&($checkpunch=="1" && $lastpunch=="1"))
						{
                                                  
                                                        $start= $new_start;
							$end1=  $new_end;
                                                       
						}  
                                                //   consider only early  going is check and attendane for that date mean
						else if($atten_detail && $checkpunch!="1" && $lastpunch=="1" )
						{  
                                                
                                                        $start=$sys_start=$start_times;
							$act_start=$new_start;
							if($act_start > $sys_start)
							{
								$start=$act_start;
							}
							$end1=$new_end;
						} 
                                                  //   consider only early  coming is check and attendane for that date mean
						else if($atten_detail && $checkpunch=="1" && $lastpunch!="1" )
						{
                                                   
                                                        $end1=$sys_end=$end_times;
							$act_end=$new_end;
							if($act_end < $sys_end)
							{
							   $end1=$act_end;
							}
							$start=$new_start;
						}
                                               
                                                // calucluate working hrs 
                                                 else
                                                {
                                                  $end1=$sys_end=$end_times;
							$act_end=$new_end;
							if($act_end < $sys_end)
							{
							   $end1=$act_end;
							}
                                                      $start=$sys_start=$start_times;
							$act_start=$new_start;
							if($act_start > $sys_start)
							{
								$start=$act_start;
							} 
							
                                                }
                                                  
                                               $end1=strtotime(date('H:i:s',$end1));
                                               $start1=$start-strtotime("00:00:00");
                                               
						$working_hours=$end1-$start1;
                                                
						$pres_days=0;
                                                //check partial day
                                                $check_partial_day=DB::table("hr_partial_day")->where('partial_date',$date)->where('active','Yes')->get();
						// if there is partial day in a month
                                                if(count($check_partial_day)>0)
						{
							
							$partial_start_times =  new DateTime($check_partial_day[0]->start_time);
							$partial_start=strtotime(date_format($partial_start_times,"H:i:s"));
                                                        
                                                        
                                                        
                                                        $partial_end =  new DateTime($check_partial_day[0]->end_time);
							$partial_end=strtotime(date_format($partial_end,"H:i:s"));
                                                      
                                                        $acc_hours=$end1-$start;
                                                         
							$p=1;
                                                    //On Partial day Calculation Absent If Work Duration is Less Than
							if(in_array("8",$rules))
							{
                                                              
                                                                 $secs= strtotime($this->mintohr($rules_val[3]))-strtotime("00:00:00");
                                                                 $partial_abs_hrs = strtotime(date("H:i:s",$acc_hours-$secs));
								if($working_hours < $partial_abs_hrs)
								{
									
									$p=0;
									$name=$date."partial day as  leave if working hour less than a  ".date("H:i:s",$partial_abs_hrs)." Hour"."<br>";
								       $pres_days=0;
                                                                       $partial=$partial;
                                                                        
                                                                } 
							}
                                                        //On Partial day Calculation HalfDay If Work Duration is Less Than
							if(in_array("7",$rules) && $p=="1")
							{
                                                                 $secs= strtotime($this->mintohr($rules_val[2]))-strtotime("00:00:00");
                                                                 $partial_halfday_hrs = strtotime(date("H:i:s",$acc_hours-$secs));
								if($working_hours < $partial_halfday_hrs)
								{
									
									$name=$date."partial  as half  if working hour less than a".date("H:i:s",$partial_halfday_hrs)." Hour"."<br>";
                                                                      $partial=$partial+0.5;
                                                                      $pres_days=0.5;
								} 
								else
								{
									
									$name=$date."partial working Hours"."<br>";
                                                                         $partial=$partial+1;
                                                                         $pres_days=1;
								} 

							}
							elseif($p=="1") 
							{
								
								$name=$date."partial working  Hour"."<br>";
                                                                $partial=$partial+1;
                                                                $pres_days=1;
                                                                
							}
                                                      
						} 
                                                // for not partial day mean
						else
						{
                                                     
                                               $late_month=0;
							//Calculate Absent If work Duration Is Less Than given hrs
							if($fabsent_day=="1" && $full_day_hrs > $working_hours)
							{
                                                             
                                                         
								$name=$date."Lessthan Working Hour  ".$full_day_hrs."so leave"."<br>";
								$pres_days=0;
                                                                $late_month=1;
								
							}
                                                         //Calculate If HalfDay If work Duration Is Less Than given hrs
							else if($fhalf_day=="1" && $half_day_hrs > $working_hours)
							{
                                                            
								 $name=$date." Lessthan ".date("H:i:s",$half_day_hrs)." Hour so half day"."<br>";
								 $pres_days=0.5;
                                                                 $late_month=1;
							}
                                                         //Mark FullDay Absent When late For
							else if($full_late_day=="1" && $full_late_day_hrs < $start)
							{
   
								$name=$date."full day abs beacuse of late".date("H:i:s",$full_late_day_hrs)." Hour"."<br>";
								$pres_days=0;
                                                                $late_month=1;
								
							}
                                                            //Mark Half Day If late by
							else if($half_late_day=="1" && $late_day_hrs < $start)
							{
                                                            $pres_days=0.5;	
                                                             $late_month=1;
							
							}
                                                         // Mark Half Day If Early Going by
							else if($half_early_day=="1" && $early_day_hrs > $end1)
							{
                                                          
									$name=$date."Early ".date("H:i:s",$early_day_hrs)." Hour BUT OD Entry half day mean"."<br>";
									$pres_days=0.5;	
                                                                         $late_month=1;
							
							} 
							else 
							{	
                                                                // full working day
								$name=$date." full Working Hour"."<br>";
								$pres_days=1;
							}
                                                       // dd($working_hours);
                                                    //  dd($shift_s);
                                                  if($month_early_day==1 && $late_month==0){
                                                     
                                                     if($working_hours<$shift_s) {
                                                       
                                                             $work = strtotime(date("H:i:s",$working_hours))-strtotime("00:00:00");  
                                                             $diff_month =$shift_s-$work;
                                                           if($late_month_hrs!=0)
                                                                  $late_month_hrs=$late_month_hrs-strtotime("00:00:00");  
                                                    
                                                              $late_month_hrs = $late_month_hrs+$diff_month;
                                                         
                                                     }
                                                  }
                                                 
						} 
                                           
						if($ot_formula=="1")
						{

							$ot_time=$working_hours-$to_time;
							$hour=0;
                                                        if($ot_time>0)
                                                        {
                                                           
                                                                if($ot_time >= $ot_mintime)
                                                                {
                                                                        $hour=$ot_time;
                                                                        if($ot_time <= $ot_maxtime)
                                                                        {
                                                                                $ot_t=$ot_time+$ot_t;

                                                                        }
                                                                        else
                                                                        {
                                                                                $ot_t=$ot_t+$ot_maxtime;
                                                                        }
                                                                } 
                                                                else
                                                                {
                                                                         $ot_t=$ot_t;
                                                                }

                                                        }
                                                        else
                                                        {
                                                                $ot_t=0;
                                                        } 
						}
						else 
						{
							 $ot_t=0;
						}
						$ot_t_t=$ot_t+$ot_t_t;
						$presentdays=$presentdays+$pres_days;
						// if sync and half cl,sl 
                                               $query_leave_half=\DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM `hr_leaves_t` left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_type WHERE employee_id='$emp_id' and leave_status='APPROVE' AND (a_lookuplines_t.lookup_code='CASUAL LEAVE' OR a_lookuplines_t.lookup_code='SICK LEAVE') and ( '$date'  BETWEEN start_date AND end_date) and leave_mode='134'");
                                           if(count($query_leave_half)>0) 
							{
                                               
                                               // half day declare
								$pres_days=0.5;
								if($query_leave_half[0]->lookup_code == 'CASUAL LEAVE')//casual Leave
								{
                                                                     if($casual_leave>=$pres_days){
									$r_casual_leave = $casual_leave - $pres_days;
                                                                        $cl=$cl+$pres_days;
									$update_query  = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['c_l'=>$r_casual_leave]);
                                                                          // auditlog
                                                                        $data['c_l']=$r_casual_leave;
                                                                        $data['actual_c_l']=$casual_leave;
                                                                        $data['taken_c_l']=$pres_days;
                                                                           $this->auditlog($emp_id,"payrollgenerate","update",$data,"hr_employee_t");
								$presentdays=$presentdays+$pres_days;
                                                                $name=$date." half cl and sync also"."<br>";
                                                                        }
                                                                }
								else if($query_leave_half[0]->lookup_code == 'SICK LEAVE')//Sick Leave
								{
                                                                     if($sick_leave>=$pres_days){
									$r_sick_leave = $sick_leave - $pres_days;
                                                                        $sl=$sl+$pres_days;
									$update_query  = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['s_l'=>$r_sick_leave]);
                                                                        // auditlog
                                                                        $data['s_l']=$r_sick_leave;
                                                                        $data['actual_s_l']=$sick_leave;
                                                                        $data['taken_s_l']=$pres_days;
                                                                           $this->auditlog($emp_id,"payrollgenerate","update",$data,"hr_employee_t");
								 $presentdays=$presentdays+$pres_days;
                                                                   $name=$date." half sl and sync also"."<br>";
                                                                        }
                                                                   
                                                                        
                                                                }
								

							}
                                                        // query for earn leave half
                                               $query_earn_half=\DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM `hr_leaves_t` left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_type WHERE employee_id='$emp_id' and leave_status='APPROVE' AND (a_lookuplines_t.lookup_code='EARN LEAVE') and ( '$date'  BETWEEN start_date AND end_date) and leave_mode='134'");
                                                if(count($query_earn_half)>0) 
							{
                                                 
								$pres_days=0.5;
								if($query_earn_half[0]->lookup_code == 'EARN LEAVE')//Earn Leave
								{
                                                                    if($earn_leave>=$pres_days){
                                                                     
									$r_earn_leave = $earn_leave - 3;
                                                                        $el=$el+$pres_days;
									$update_query  = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['e_l'=>$r_earn_leave]);
                                                                        // auditlog
                                                                        $data['e_l']=$r_earn_leave;
                                                                        $data['actual_e_l']=$earn_leave;
                                                                        $data['taken_e_l']=$pres_days;
                                                                           $this->auditlog($emp_id,"payrollgenerate","update",$data,"hr_employee_t");
								$presentdays=$presentdays+$pres_days;
                                                                  $name=$date." half el and sync also"."<br>";
                                                                          }
                                                                   
                                                                }
								
							}
                                                       // query for od leave half
                                                      $od_query_half=\DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM `hr_leaves_t` left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_type WHERE employee_id='$emp_id' and leave_status='APPROVE' AND (a_lookuplines_t.lookup_code='ON-DUTY') and ( '$date'  BETWEEN start_date AND end_date) and leave_mode='134'");
                                                 if(count($od_query_half)>0) 
							{
								$pres_days=0.5;
								if($od_query_half[0]->lookup_code == 'ON-DUTY')//od  Leave
								{
                                                                       $od=$od+$pres_days;
                                                                        $presentdays=$presentdays+$pres_days;
                                                                 $name=$date." half od and sync also"."<br>";
                                                                 }
							}
                                                        //compensated day if work on holiday and week off and month off
                     $holidays=\DB::select("SELECT location FROM `hr_holiday_t` where date='$date' and company_id='$compid' and active='Yes'");  
                     // if compensated on holiday
                        if(count($holidays)=="1")
                        {
                            // check location based leave
                            $holiday_location=json_decode($holidays[0]->location);
                            if (in_array($locationid, $holiday_location)){
                               
                             $name=$date."compensated  holiday"."<br>";
                              $compensated_days++;  
                              $compen_leave['date']     = $date;
                              $compen_leave['employee_id']    = $emp_id;
                              $compensated_id= \DB::table('hr_compensation_table')->insertGetId($compen_leave);
                                // auditlog
                                   $this->auditlog($compensated_id,"payrollgenerate","create",$compen_leave,"hr_compensation_table");
                            }
                        } 
                        else 
                        {
                            // compensated on month off week off
                            $date_day=date('N', strtotime($date));
                            $date_weekno=$this->weeknumber($date);
                            $week_off_query=\DB::select("SELECT * FROM `hr_emp_payroll_settings_t`");
							if(count($week_off_query)>0)
							{
                            $week_off=json_decode($week_off_query[0]->week_off);
                            $week_period=json_decode($week_off_query[0]->week_period);
                            $month_off=json_decode($week_off_query[0]->month_off);
                            $month_period=$this->arraycombine($month_off,$week_period);
                            if(in_array($date_day,$week_off))
                            {
                                      $name=$date."compensated  week off"."<br>";
                                      $compensated_days++;             
                                      $compen_leave['date']     = $date;
                                      $compen_leave['employee_id']    = $emp_id;
                                      $compensated_id= \DB::table('hr_compensation_table')->insertGetId($compen_leave);
                              // auditlog
                                   $this->auditlog($compensated_id,"payrollgenerate","create",$compen_leave,"hr_compensation_table");
                            } 
                            elseif(isset($month_period[$date_day]))
                            {
                                if(in_array($date_weekno,$month_period[$date_day]))
                                {
                                          $name=$date."compensated  month off"."<br>";
                                   $compensated_days++;
                                   $compen_leave['date']     = $date;
                                   $compen_leave['employee_id']    = $emp_id;
                                   $compensated_id= \DB::table('hr_compensation_table')->insertGetId($compen_leave);
                                   // auditlog
                                   $this->auditlog($compensated_id,"payrollgenerate","create",$compen_leave,"hr_compensation_table");
                                }
                            }
							}
                        }
                        
						}
                                                // sync not check holiday or weeek off or month off
						else 
						{
                                                    
                                                 
							  $holidays=\DB::select("SELECT location FROM `hr_holiday_t` where date='$date' and company_id='$compid' and active='Yes'");  
						          /*** casual and sick,earn,od leave  half and full***/
                                                        $query_leave_full=\DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM `hr_leaves_t` left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_type WHERE employee_id='$emp_id' and leave_status='APPROVE' AND (a_lookuplines_t.lookup_code='ON-DUTY' OR a_lookuplines_t.lookup_code='EARN LEAVE' OR a_lookuplines_t.lookup_code='CASUAL LEAVE' OR a_lookuplines_t.lookup_code='SICK LEAVE') and ( '$date'  BETWEEN start_date AND end_date) and leave_mode='135'");
							$query_leave_half=\DB::select("SELECT hr_leaves_t.*,a_lookuplines_t.lookup_code FROM `hr_leaves_t` left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_type WHERE employee_id='$emp_id' and leave_status='APPROVE' AND (a_lookuplines_t.lookup_code='ON-DUTY' OR a_lookuplines_t.lookup_code='EARN LEAVE' OR a_lookuplines_t.lookup_code='CASUAL LEAVE' OR a_lookuplines_t.lookup_code='SICK LEAVE') and ( '$date'  BETWEEN start_date AND end_date) and leave_mode='134'");

                                                     $holiday_location=array();
							if(count($holidays)=="1")
							{
                                                            $holiday_location=json_decode($holidays[0]->location);
                                                        }
                                                        
                                                                 $date_day=date('N', strtotime($date));
								 $date_weekno=$this->weeknumber($date);
								 $week_off_query=DB::table("hr_emp_payroll_settings_t")->get();
                                                                 if(count($week_off_query)>0){
								 $week_off=json_decode($week_off_query[0]->week_off);
								 $week_period=json_decode($week_off_query[0]->week_period);
								 $month_off=json_decode($week_off_query[0]->month_off);
                                                                 $month_period=$this->arraycombine($month_off,$week_period);
                                                                 }else{
                                                                    $week_off=[]; 
                                                                 }
                                                                 
							
                                                            
                                                        if (in_array($locationid, $holiday_location)){
                                                            $name=$date." holiday"."<br>";
                                                            $holidayoff++;
                                                            $no_holidays++;
                                                            $holi_day++;
                                                            	if($abs_ent!="0")
									{
										$prefday=1;
									}
									else
									{
										$prefday=0;
									}
                                                            
						        }else if(in_array($date_day,$week_off))
								{
									$name=$date." weekoff"."<br>";
									$weekoff++;
									$no_holidays++;
									$holi_day++;
									if($abs_ent!="0")
									{
										$prefday=1;
									}
									else
									{
										$prefday=0;
									}

								} 
                                                                elseif(isset($month_period[$date_day]) && in_array($date_weekno,$month_period[$date_day]))
                                                               {	
                                                                   
                                                                       $name=$date." monthoff"."<br>";
                                                                       $weekoff++;
                                                                       $no_holidays++;
                                                                       $holi_day++;
                                                                       if($abs_ent!="0")
                                                                       {
                                                                           $prefday=1;
                                                                       } 
                                                                       else
                                                                       {
                                                                           $prefday=0;
                                                                       }
                                                                  
                                                               }
                                                               else if(count($query_leave_full)>0) 
							{
                                                         
								   $pres_days=1;
								if($query_leave_full[0]->lookup_code == 'CASUAL LEAVE' && $casual_leave>=$pres_days)//casual Leave full day
								{
                                                                  
									$r_casual_leave = $casual_leave - $pres_days;
                                                                        $cl=$cl+$pres_days;
									$update_query  = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['c_l'=>$r_casual_leave]);
                                                                         // auditlog
                                                                        $data['c_l']=$r_casual_leave;
                                                                        $data['actual_c_l']=$casual_leave;
                                                                        $data['taken_c_l']=$pres_days;
                                                                        $this->auditlog($emp_id,"payrollgenerate","update",$data,"hr_employee_t");
                                                                        $presentdays=$presentdays+$pres_days;
                                                                        $name=$date." full cl"."<br>";
                                                                       
                                                                        
                                                                }
                                                                else if($query_leave_full[0]->lookup_code == 'SICK LEAVE' && $sick_leave>=$pres_days)//Sick Leave full day
								{
                                                                        $r_sick_leave = $sick_leave - $pres_days;
                                                                        $sl=$sl+$pres_days;
									$update_query  = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['s_l'=>$r_sick_leave]);
                                                                     // auditlog
                                                                        $data['s_l']=$r_sick_leave;
                                                                        $data['actual_s_l']=$sick_leave;
                                                                        $data['taken_s_l']=$pres_days;
                                                                        $this->auditlog($emp_id,"payrollgenerate","update",$data,"hr_employee_t");
								$presentdays=$presentdays+$pres_days;
                                                                 $name=$date." full sl"."<br>";
                                                                    
                                                                        
                                                                }
                                                             else if($query_leave_full[0]->lookup_code == 'EARN LEAVE' && $earn_leave>=$pres_days)// earn Leave full day
								{
                                                                     
									$r_earn_leave = $earn_leave - 3;
                                                                        $el=$el+$pres_days;
									$update_query  = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['e_l'=>$r_earn_leave]);
                                                                        // auditlog
                                                                        $data['e_l']=$r_earn_leave;
                                                                        $data['actual_e_l']=$earn_leave;
                                                                        $data['taken_e_l']=$pres_days;
                                                                        $this->auditlog($emp_id,"payrollgenerate","update",$data,"hr_employee_t");
								$presentdays=$presentdays+$pres_days;
                                                                 $name=$date." full el"."<br>";
                                                                     
                                                                     }else if($query_leave_full[0]->lookup_code == 'ON-DUTY')//od full Leave
								{
                                                              
                                                                        $od=$od+$pres_days;
                                                                        $presentdays=$presentdays+$pres_days;
                                                                        $name=$date." full od"."<br>";
                                                                }else{   
                                                                    $absent++;
                                                                    $abs_ent++;
                                                                    if($holi_day!="0")
                                                                             {
                                                                                 $suffday=1;
                                                                             } 
                                                                             else 
                                                                             {
                                                                                 $suffday=0;
                                                                             }
                                                        }
                                                        }
                                                        else if(count($query_leave_half)>0) 
							{
								$pres_days=0.5;
								if($query_leave_half[0]->lookup_code == 'CASUAL LEAVE' && $casual_leave>=$pres_days)//casual Leave half day
								{
									$r_casual_leave = $casual_leave - $pres_days;
                                                                        $cl=$cl+$pres_days;
									$update_query  = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['c_l'=>$r_casual_leave]);
                                                                        // auditlog
                                                                        $data['c_l']=$r_casual_leave;
                                                                        $data['actual_c_l']=$casual_leave;
                                                                        $data['taken_c_l']=$pres_days;
                                                                        $this->auditlog($emp_id,"payrollgenerate","update",$data,"hr_employee_t");
								$presentdays=$presentdays+$pres_days;
                                                                         $name=$date." half cl"."<br>";
                                                                  
                                                                }
                                                                else if($query_leave_half[0]->lookup_code == 'SICK LEAVE' && $sick_leave>=$pres_days)//Sick Leave half day
								{
                                                                       
									$r_sick_leave = $sick_leave - $pres_days;
                                                                        $sl=$sl+$pres_days;
									$update_query  = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['s_l'=>$r_sick_leave]);
                                                                        // auditlog
                                                                        $data['s_l']=$r_sick_leave;
                                                                        $data['actual_s_l']=$sick_leave;
                                                                        $data['taken_s_l']=$pres_days;
                                                                        $this->auditlog($emp_id,"payrollgenerate","update",$data,"hr_employee_t");
								        $presentdays=$presentdays+$pres_days;
                                                                         $name=$date." half sl"."<br>";
                                                            
                                                                }
								else if($query_leave_half[0]->lookup_code == 'EARN LEAVE' && $earn_leave>=$pres_days)//Earn Leave half day
								{
                                                                    
                                                                   
									$r_earn_leave = $earn_leave - 3;
                                                                        $el=$el+$pres_days;
									$update_query  = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['e_l'=>$r_earn_leave]);
                                                                        // auditlog
                                                                        $data['e_l']=$r_earn_leave;
                                                                        $data['actual_e_l']=$earn_leave;
                                                                        $data['taken_e_l']=$pres_days;
                                                                        $this->auditlog($emp_id,"payrollgenerate","update",$data,"hr_employee_t");
								$presentdays=$presentdays+$pres_days;
                                                                 $name=$date." half el"."<br>";
                                                                   
                                                                    }
                                                                  else if($query_leave_half[0]->lookup_code == 'ON-DUTY')//od full Leave
								{
								if($od_query_half[0]->lookup_code == 'ON-DUTY')//od half  Leave
								{
                                                                       $od=$od+$pres_days;
                                                                        $presentdays=$presentdays+$pres_days;
                                                                 $name=$date." half od"."<br>";
                                                                    }
                                                                }else{
                                                                    $absent=$absent+0.5;
                                                                   $abs_ent++;
                                                              if($holi_day!="0")
                                                                       {
                                                                           $suffday=1;
                                                                       } 
                                                                       else 
                                                                       {
                                                                           $suffday=0;
                                                                       }
                                                                }
                                                        }
							else{
                                                            $absent++;
                                                            $abs_ent++;
                                                              if($holi_day!="0")
                                                                       {
                                                                           $suffday=1;
                                                                       } 
                                                                       else 
                                                                       {
                                                                           $suffday=0;
                                                                       }
                                                            
                                                        }	
                                                   
							if($prefix_day=="1" && $holi_day != "0" && $abs_ent != "0")
							{
                                                              
								$absent++;
								$no_holidays--;
								$holi_day=0;
							} 
                                                      
							if($suffix_day=="1" && $holi_day != "0" && $abs_ent != "0")
							{
                                                           
								$absent=$absent+$holi_day;
								$no_holidays=$no_holidays-$holi_day;
								$holi_day=0;
							}
							if($pref_suff_day=="1" && $holi_day != "0" && $abs_ent != "0" && $suffday =="1" && $prefday =="1" )
							{
								$absent=$absent+$holi_day;
								$no_holidays=$no_holidays-$holi_day;
                                                                $holi_day=0;
							}

 
						}
                                                
                                                }else{
                                                      $name=$date." not have shift type"."<br>";
                                                }
							$begin->modify('+1 day');
                                             
                                                
						}
                                               
                                              if($late_month_hrs!=0){
                                                   $timesplit=explode(':',date("H:i:s",$late_month_hrs));
                                                   $min=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]>30?1:0);
                                                  $total_minutes =(int)($min/$month_day_hrs);
                                                 $half=$total_minutes/2;
                                              }
                 
                                                // no days
                                        $totaldays=$no_days;
                                        // week plus month plus holiday
                                        $no_holidays=$no_holidays; 
                                        // week off and monthoff
                                        $weekoff= $weekoff; 
                                        // holiday
                                        $holidayoff=$holidayoff;
                                        
                                        // present days
                                        $presentdays=$presentdays-$half;
                                    //   dd($presentdays);
                                        // leave
                                        $leave =$totaldays - ($presentdays-$weekoff-$holidayoff);
                                        // working days
										
                                        $working_days = $presentdays+$weekoff+$holidayoff;	
									
                                       	$grade_details = DB::table("hr_employee_payproposal")->where('employee_id',$emp_id)->get();
						
						$gross_pay                    = $grade_details[0]->gross_pay;
						$net_pay_tax                    = $grade_details[0]->net_pay;
						$basic_pay                    = $grade_details[0]->basic_pay;
						$hra                          = $grade_details[0]->hra;
						$annual_allowance 	      = $grade_details[0]->annual_allowance;
						$da  			      = $grade_details[0]->da;
						$gratuity 		      = $grade_details[0]->gratuity;
						$allowance 	              = $grade_details[0]->allowance;
						
						$volunter_pf	              = $grade_details[0]->volunter_pf;
                                                if($volunter_pf==''){
                                                    $volunter_pf=0;
                                                }
                                                  $total_basic=$basic_pay/$totaldays;
                                                        $total_hra=$hra/$totaldays;
                                                        $total_da=$da/$totaldays;
                                                        $basic_pay = round($total_basic*$working_days);
                                                        $hra =round($total_hra*$working_days);
                                                        $da =round($total_da*$working_days);
                                                  $allow=json_decode($allowance);
                                                
                                                      $deduct_allowance=0;
                                                      $allow_en=[];
						//total days and working days not equal mean calculate
					
							   foreach($allow as $k=>$v){
                                                           foreach($v as $k1=>$v1){
                                                                $allowance_deduction=\DB::SELECT("SELECT type from m_allowance_tbl where allowance_id=".$k1);
                                                       if(count($allowance_deduction)>0){
                                                                if($allowance_deduction[0]->type=="Allowance"){
                                                                $total_allow=$v1/$totaldays;
                                                                 
                                                            $add=($total_allow*$working_days);
                                                          
                                                            $allow_en[$k][$k1]=(string)round($add,2);
                                                            if(isset($allow_acc[$k1]))
                                                            {
                                                           
                                                              $allow_acc[$k1]=round($allow_acc[$k1]+$add,2);
                                                            }
                                                            else
                                                            {
                                                                $allow_acc[$k1]=round($add,2); 
                                                            }
                                                       }  
                                                       
                                                         if($allowance_deduction[0]->type=="Deduction"){
                                                           
                                                             $total_allow=$v1/$totaldays;
                                                                 
                                                            $add=($total_allow*$totaldays);
                                                          $deduct_allowance=$add+$deduct_allowance;
                                                            $allow_en[$k][$k1]=(string)round($add,2);
                                                            if(isset($deduct_acc[$k1]))
                                                            {
                                                           
                                                              $deduct_acc[$k1]=round($deduct_acc[$k1]+$add,2);
                                                            }
                                                            else
                                                            {
                                                                $deduct_acc[$k1]=round($add,2); 
                                                            }
                                                             
                                                         }
                                                         
                                                           }
                                                            
                                                        }
                                                        }
                                                      
                                                       // dd(json_encode($allow_en));
                                                        $allowance =json_encode($allow_en);
                                              
				   $basic_account=$basic_account+$basic_pay;
                                   $hra_account=$hra_account+$hra;
                                   $da_account=$da_account+$da;
                                   $volunter_account=$volunter_account+$volunter_pf;
                              
					    $year_net=$net_pay_tax*12;
						//dd($year_net);
						$all=($basic_pay+$hra+$da);
						$length     = \DB::select("SELECT COUNT(DISTINCT to_value) as length FROM `hr_tax_master`");
						$length     = $length[0]->length;
						$limit      = 0;
						$tax_value  = 0;
						$tax        = 0; 
						$tax_am=0;
						$tax        = DB::table("hr_tax_master")->select('precentage','to_value','from_value')->orderBy('id', 'asc')->get();
							$balance_amount=$year_net;
						//Checking Percentage For Annual Income for Basic Pay
						foreach($tax as $tax_val)
						{
							$from_value = $tax_val->from_value;
							$to_value   = $tax_val->to_value;
							$anu_pay    = $year_net*12;
							$deduct_amounts=$to_value-$from_value;
							if($to_value <= $year_net)
							{
								if($deduct_amounts <= $balance_amount)
								{
									$deduct_amount_val=$deduct_amounts;
								}
								else
								{
								$deduct_amount_val=	$balance_amount;
							
								}
								$balance_amount=$balance_amount-$deduct_amount_val;
								$precentage =   $tax_val->precentage;
								
								$tax_am+=($deduct_amount_val*$precentage)/100;
								
							}
							else if($from_value <= $year_net && $to_value >= $year_net)
							{
								$precentage =   $tax_val->precentage;
								$tax_am+=($balance_amount*$precentage)/100;
								
							}
							
						}
						//dd($tax_am);
						//If Tax is zero No deduction 
					    /******* ******/
                                                $tax=0;
						if($tax_am!=0)
						{
							$year=DB::table("account_year")->where('status',1)->get();
							$year=$year[0]->id;
							$inv_type=DB::table('hr_inve_type')->get();
							$inv_pay=0;
							$act_pay=0;
							$amo=0;
							$act=0;
							//dd($month);
							$check=DB::table("hr_inve_declaration")->where('emp_id',$emp_id)->where('year',$year)->where('upload_status',1)->get();
							if(count($check)>0)
								$month1=$check[0]->month;
							else
								$month1=0;
							if($month <= $month1)
								$check="";

							if(!$check)
							{
								foreach ($inv_type as $inv)
								{
									$inv_id=$inv->inv_id;
									$limit=$inv->inv_limit;
									$value=DB::table('hr_inve_declaration')->select(DB::raw('sum(hr_inve_declaration.inv_amount) as total'))->where('emp_id',$emp_id)->where('inv_id',$inv_id)->where('year',$year)->where('status',1)->get();
                                                                       
									if($value[0]->total != 0)
									{
										$amo=$value[0]->total;
									}
									if($limit != 0)
									{
										if($amo > $limit)
										{
											$amo=$limit; 
										}  
									}
									$inv_pay=$inv_pay+$amo;   
								}
								
								$inv_pay=  round($inv_pay/12,2);
								if($tax > $inv_pay)
									$tax=$tax-$inv_pay;
								else 
									$tax=0;
							}
							else
							{
								foreach ($inv_type as $inv)
								{
									$inv_id=$inv->inv_id;
									$limit=$inv->inv_limit;
									$value=DB::table("hr_inve_declaration")->selectRaw('SUM(inv_amount) as inv_total,SUM(act_amount) as act_total')->where('emp_id',$emp_id)->where('inv_id',$inv_id)->where('year',$year)->get();
									if($value[0]->inv_total != 0)
									{
										$amo=$value[0]->inv_total;
										$act=$value[0]->act_total;
									}
									
									if($limit != 0)
									{
										if($amo > $limit)
										{
										  $amo=$limit;  
										}
										if($act > $limit)
										{
											$act=$limit;
										}
									}
									
									$act_pay=$act_pay+$act;
									$inv_pay=$inv_pay+$amo;  
								} 

								$loss_value=0;
								$gain_value=0;
								
								if($act_pay <= $inv_pay)
								{
									$loss_value= $inv_pay - $act_pay;
								}
								else
								{
									$gain_value=$act_pay-$inv_pay;
									$months=DB::table("hr_inve_declaration")->select('month')->where('emp_id',$emp_id)->where('inv_id',$inv_id)->where('year',$year)->get();
									$inv_month=$months[0]->month;
									$year=DB::table(" account_year")->select('year_to')->where('status',1)->get();
									$year=$year[0]->year_to;
									$eff_from=date('01-'.$inv_month.'-Y');
									$eff_from=new DateTime($eff_from);
									$eff_to=date('31-03-'.$year);
									$eff_to=new DateTime($eff_to);
									$diff=$eff_to->diff($eff_from);
									$diff_value= $diff->format('%m months');
								}

								if($loss_value != 0)
								{
									$loss=round($loss_value/$diff_value,2);
									$act_pay=  round($inv_pay/12,2);
									if($tax > $act_pay)
										$tax=$tax-$inv_pay+$loss;
									else 
										$tax=0;
								}
								
								if($gain_value != 0)
								{
									$gain=round($gain_value/$diff_value,2);
									$act_pay=  round($inv_pay/12,2);
									if($tax > $act_pay)
									{
										$tax=$tax-$act_pay-$gain;
										if($tax < 0)
											$tax=0;
									}
									else 
										$tax=0;
								}
                                                            }
                                }  
                             //    dd($tax);
                                   $tax_account=$tax_account+$tax;
                                                $salary=$gross_pay;      
						$perday_salary  = $salary / $totaldays;    
						$gross_salary   = round($perday_salary * $working_days);
						$ot = 0;
						//ESI calculation and deduction
						// esi percentage get from master
						$esi_detatil    =    $esi=DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.limitto','m_emp_esi.employeer_contribute','m_emp_esi.company_contribute')->where('lookup_code','ESI')->get();
                                                // pf percentage get from master
						$pf_detatil   =DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.limitto','m_emp_esi.employeer_contribute','m_emp_esi.company_contribute','m_emp_esi.company_contribute1')->where('lookup_code','PF')->get();
                                                // pf basic
						$pf_value=$basic_pay+$da;
                                                // esi basic
						$esi_value=$gross_salary;
						$company_contribute1=array();
                                                // esi employee and company contribute
                            $emp_con=0;     
                            $date_month = $month;
                            $date_year = $current_year;               
						if($grade_details[0]->esi==1)
						{
                                                
							if(count($esi_detatil)>0){
                                                            $limit=$esi_detatil[0]->limitto;
                                                          
                                                            if($gross_pay>$limit){
                                                                if($date_month!=4 && $date_month!=10){
                                                                    if($date_month==1){
                                                                        $prev_month = 12;
                                                                        $prev_year = $date_year-1;
                                                                    }else{
                                                                        $prev_month = $date_month-1;
                                                                        $prev_year = $date_year;
                                                                    }
                                                                    $prev_cont_esi = DB::table('hr_company_contribute_esi')->where('emp_id',$emp_id)->where('month',$prev_month)->where('year',$prev_year)->where('amount_employee','!=',0)->get();  
                                                                    if(count($prev_cont_esi)>0){
                                                                        $emp_con    =   ceil($overall_gross*$esi_detatil[0]->employeer_contribute/100);
                                                                        $com_con    =  round($overall_gross*$esi_detatil[0]->company_contribute/100,2);
                                                                        $emp_con = $emp_con - $old_emp_con;
                                                                        $com_con = $com_con - $old_comp_con;
                                                                       $esi_cutoff = 1; 
                                                                    }else{
                                                                        $emp_con = 0;
                                                                        $com_con = 0;
                                                                    }
                                                                }else{
                                                                   $emp_con = 0;
                                                                   $com_con = 0;
                                                                }                 
                                                                
                                                                
                                                            }else{
                                                                $emp_con                              =   ceil($esi_value*$esi_detatil[0]->employeer_contribute/100);
							                                    $com_con                              =  round( $esi_value*$esi_detatil[0]->company_contribute/100,2);
                                                            }
							
							$esi                                  =   $emp_con;
							$company_contribute1['emp_id']         =   $emp_id;                                             
							$company_contribute1['month']          =   $month;                                             
							$company_contribute1['year']           =   $current_year;                                             
							$company_contribute1['amount']         =   round($com_con);                                             
							$company_contribute1['amount_employee']=  $emp_con;                                             
							$company_contribute1['date']           =   date('Y-m-d');
							$result = DB::table('hr_company_contribute_esi')->insertGetId($company_contribute1);
                                                          // auditlog
                                                            $this->auditlog($result,"createemployee","create",$company_contribute1,"hr_company_contribute_esi");
                                                                $esi_company_account=$esi_company_account+ round($com_con);
                                                        }
						}
						else
						{
							$esi=0;
							
                        // dd($overall_gross,$esi_detatil[0]->employeer_contribute,($overall_gross*$esi_detatil[0]->employeer_contribute/100));
                        if($date_month!=4 && $date_month!=10){
                            if($date_month==1){
                                $prev_month = 12;
                                $prev_year = $date_year-1;
                            }else{
                                $prev_month = $date_month-1;
                                $prev_year = $date_year;
                            }
                            $prev_cont_esi = DB::table('hr_company_contribute_esi')->where('emp_id',$emp_id)->where('month',$prev_month)->where('year',$prev_year)->get();  
                            if(count($prev_cont_esi)>0){
                                $emp_con    =   ceil($esi_value*$esi_detatil[0]->employeer_contribute/100);
                                $com_con    =  round($esi_value*$esi_detatil[0]->company_contribute/100,2);
                            }else{
                                $emp_con = 0;
                                $com_con = 0;
                            }
                        }else{
                           $emp_con = 0;
                           $com_con = 0;
                        }  
                         if($emp_con!=0){
                      
                              $esi                                  =   $emp_con;
                              $company_contribute['emp_id']         =   $emp_id;                                             
                              $company_contribute['month']          =   $date_month;                                             
                              $company_contribute['year']           =   $date_year;                                             
                              $company_contribute['amount']         =  $com_con;                                             
                              $company_contribute['amount_employee']=  $emp_con;                                             
                              $company_contribute['date']           =   date('Y-m-d');
                              $company_contribute['arrear_status']           =1;
                              $result = DB::table('hr_company_contribute_esi')->insertGetId($company_contribute);
                                // auditlog
                                $this->auditlog($result,"createemployee","create",$company_contribute,"hr_company_contribute_esi");
                                $esi_company_account=$esi_company_account+ round($com_con);
                            }
                      $esi=$emp_con;
                    
						}
						$esi = ceil($esi);
                                              // pf company and employee contibute
						if($grade_details[0]->pf==1)
						{
                                                    if(count($pf_detatil)>0){
                                                        $limit=$pf_detatil[0]->limitto;
                                                            if($pf_value>=$limit){
                                                                    $pf                                      = round($limit*$pf_detatil[0]->employeer_contribute/100);
                                                                    $emp_con1                                = 1800;
                                                                    $com_con1                                = 1250;
                                                                    $com_con2                               = 550;
																	$edli=75;
                                                                
                                                            }else{
                                                                $pf                                      = round($pf_value*$pf_detatil[0]->employeer_contribute/100);
                                                                $emp_con1                                = round($pf);
                                                                $com_con1                                = round($pf_value*$pf_detatil[0]->company_contribute/100);
                                                                $com_con2                                = round($pf_value*$pf_detatil[0]->company_contribute1/100);
																$edli=round(($pf_value*0.5)/100);
															}
															$epf=$pf;
							 $pf =$volunter_pf+$pf;
							 
							 $vpf=$volunter_pf;
							$company_contribute['emp_id']            =   $emp_id;                                             
							$company_contribute['volunter_pf']            =$volunter_pf;                                             
							$company_contribute['month']             =   $month;                                             
							$company_contribute['year']              =   $current_year;                                             
							$company_contribute['amount']            =   round($com_con1);                                             
							$company_contribute['amount1']            =   round($com_con2);                                             
							$company_contribute['edli_charges']            =   round($edli);                                             
							$company_contribute['admin_charges']            =   round($edli);                                             
							$company_contribute['amount_employee']   =  round($emp_con1);                                             
							$company_contribute['date']              =   date('Y-m-d');
							
							$result = DB::table('hr_company_contribute_pf')->insertGetId($company_contribute);
                                                        // auditlog
                                                        $this->auditlog($result,"createemployee","create",$company_contribute,"hr_company_contribute_pf");
                                                            
                                                        $pf_company_account=$pf_company_account+ round($com_con1);
                                                        $pf_company1_account=$pf_company1_account+ round($com_con2);
                                                    }
						}
						else
						{
							$pf = 0;
							$epf = 0;
						}
						$pf = round($pf);
						
						              
                    // IF Professional tax   
               $location = json_decode(Session::get('location'));
               $professional_taxs = DB::table('m_location_t')->leftJoin('hr_professional_tax_hdr', 'hr_professional_tax_hdr.ptax_state_id', '=', 'm_location_t.state_id')->leftjoin('hr_professional_tax_lines','hr_professional_tax_lines.ptax_id','=','hr_professional_tax_hdr.ptax_id')->select('hr_professional_tax_hdr.*','hr_professional_tax_lines.*')->where('m_location_t.location_id',$location)->get();
             
               if($grade_details[0]->pt==1)
                    {      
                   if(count($professional_taxs)>0){
                       
                        foreach($professional_taxs as $index=>$value)
                        {
                            
                            if($gross_salary>=$value->from_value && $gross_salary<=$value->to_value)
                            {
                                 $professional_tax = $value->deduction_amount;
                                 break;
                            }else{
                                 $professional_tax=0;
                            }
                        }
                    }else{
                        $professional_tax=0;
                       
                    }
                    } 
                    else
                    {							 
                        $professional_tax=0;
                    }  
                                           
                                             
                                   $esi_employee_account=$esi_employee_account+$esi;
                                   $pf_employee_account=$pf_employee_account+ round($epf);
                                   $pt_account=$pt_account+ round($professional_tax);   
						// IF TAXATION AMOUNT
						$tax_amount = $tax;
                                                // minus tax amount
						$net_salary = round($gross_salary - $tax_amount,2);
						$net_salary = round($gross_salary-$pf-$esi-$professional_tax-$deduct_allowance,2);
						$loan_deduction_amt = 0;
						//IF DEDUCTION AMOUNT
						$total_loan_amount=0;
						$deduction = DB::table("hr_employee_advance_t")->where('employee_id',$emp_id)->where('remaining_amount','!=',0)->where('paid_status',0)->where('approved_status','=',1)->get();

						if (!empty($deduction)) 
						{  
							foreach($deduction as $value)
							{
								if($deduction[0]->advance_from==1){
								$permonth_amount    =   $deduction[0]->amount / $deduction[0]->emi;
                                                                }
								else{
                                                                    $permonth_amount    =   $deduction[0]->amount;
                                                                }
								$paid_amount1        =   $value->remaining_amount-$permonth_amount;

								if($value->remaining_amount<$permonth_amount)
									$paid_amount1 = $value->remaining_amount;
								else
									$paid_amount1 = $permonth_amount;
								
								
								
									$loan_deduction_amt  =   $paid_amount1;
									$paid_amount         =   round($deduction[0]->paid_amount + round($paid_amount1));
									$remaining_amount    =   round($value->remaining_amount-$loan_deduction_amt);
									$net_salary          =   round($net_salary - $loan_deduction_amt);							
									$total_loan_amount   =   round($total_loan_amount+$loan_deduction_amt);
									$employee_advance_id =   $value->advance_id;
									$emp_advance = array("paid_amount" => $paid_amount,"remaining_amount" => $remaining_amount);								
									\DB::table('hr_employee_advance_t')->where('advance_id',$employee_advance_id)->limit(1)->update($emp_advance);
                                                                               // auditlog
                                                                        $this->auditlog($employee_advance_id,"payrollgenerate","update",$emp_advance,"hr_employee_advance_t");
									if($paid_amount1 == $value->remaining_amount)
									{
										$query1 = DB::table("hr_employee_advance_t")->where('advance_id',$employee_advance_id)->update(['paid_status'=>1]); 
                                                                                 // auditlog
                                                                                $update['paid_status']=1;
                                                                        $this->auditlog($employee_advance_id,"payrollgenerate","update",$update,"hr_employee_advance_t");
									}

									$current_date=date('Y-m-d');

									$deductions_data = DB::table("hr_employee_advance_t")->where('advance_id',$employee_advance_id)->get();
								    foreach($deductions_data as $key=>$value)
									{
										$deductions = array(
											'ref_id'        => $value->advance_id,
											'employee_id'       => $value->employee_id,
											'deduction_date'    => $current_date,
											'total_amount'      => round($deductions_data[0]->amount,2),
											'till_paid_amount'  => $value->paid_amount,
											'till_remaining_amount' => $value->remaining_amount,
											'amount_pay' 		=>$paid_amount1,
											'company_id' 		=>\Session::get('companyid'),
											'location_id' 		=>\Session::get('location'),
											'organization_id' 		=>\Session::get('organization'),
											'created_at' 		=>date('Y-m-d'),
											'created_by' 		=>\Session::get('id'),
										);
									}
								
								$result = DB::table('hr_employee_advancedeductions_t')->insertGetId($deductions);
                                                                          // auditlog
                                                                        $this->auditlog($result,"payrollgenerate","create",$deductions,"hr_employee_advancedeductions_t");
								if($paid_amount1 == $value->remaining_amount)
								{
									$query2 = DB::table("hr_employee_advancedeductions_t")->where('paid_status',0)->where('employee_id',$emp_id)->where('ref_id',$value->advance_id)->update(['paid_status'=>1]); 
                                                                        // auditlog
                                                                        $update['paid_status']=1;
                                                                        $this->auditlog($value->advance_id,"payrollgenerate","update",$update,"hr_employee_advancedeductions_t");
								}
							}
						}
                                                
						
                                              //earn leave greater than 24 gives additional for those days(basic+da*extra days(
                                                 /**   $earn_leave_data=DB::table('hr_employee_t')->where('employee_id',$emp_id)->get();
						$earn_leave_original=$earn_leave_data[0]->e_l;
                                                if($earn_leave_original>24){
                                                DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['e_l'=>24]);
                                                // auditlog
                                                 $update['e_l']=24;
                                                 $update_earn['actual_e_l']=$earn_leave_original;
                                                 $update_earn['remaining_e_l']=24;
                                                 $this->auditlog($emp_id,"payrollgenerate","update",$update_earn,"hr_employee_t");
						 $salary_earn=$earn_leave_original-24;
                                                $earn_salary=($basic_pay+$da)*$salary_earn;
                                                }
                                                // adittion of net to earn
						$net_salary=$earn_salary+$net_salary; **/
                                                //ot 
                                                if($ot_t_t!=0){
                                                 $ot_hr       = round(abs($ot_t_t) / 3600,2);
                                            $ot_amount         =($basic_pay/$total_days)/8*1 * $ot_hr;
                                                }
                                                else{
                                                   $ot_amount=0;  
                                                }
                                                $net_account=$net_account+$net_salary;
                                                $advance_account=$advance_account+$total_loan_amount;
                                                $ot_account=$ot_account+$ot_amount;
                                                $annual_allowance_account=$annual_allowance+$annual_allowance_account;
                                                $gratuity_account=$gratuity_account+$gratuity;
                                                $gross_salary_account=$gross_salary_account+$gross_salary;
                                              
						$data = array(
							'employee_id'       => $emp_id,
							'month'             => $month,
							'year'              => $current_year,
							'basic_salary'      => round($basic_pay),
							'hra'               => round($hra),
							'da'                => round($da),
							'pf'                => round($pf),
							'annual_allowance'  => round($annual_allowance),
							'gratuity'          => round($gratuity),
							'allowance'         => $allowance,
							'esi'               => round($esi),
							'pt'               => round($professional_tax),
							'earn_salary'       => round($earn_salary),
							'gross_salary'      => round($gross_salary),
							'net_salary'        => round($net_salary),
							'date'              => $current_date,
							'total_days'        => $totaldays,
							'no_of_days_employee'        => $totaldays,
							'attendance_days'   => $working_days,
							'loan_deduction'    => $total_loan_amount,
							'atten_status'      => $source,
							'tax_amount'        => round($tax_amount),
							'cl'                => $cl,
							'el'                => $el,
							'od'                => $od,
							'sl'                => $sl,
							'ot'                => $ot_amount,
							'partial_day'                => $partial,
							'compensated_day'                => $compensated_days,
                                                        'company_id'        =>\Session::get('companyid'),
						        'location_id' 	    =>\Session::get('location'),
							'organization_id'   =>\Session::get('organization'),
							'created_at' 	    =>date('Y-m-d'),
							'created_by' 	    =>\Session::get('id'),);
                                                  // save payslip for an employee
                         			 $emp_id_leave = DB::table('hr_employee_payroll_lists')->insertGetId($data);
                                                  //auditlog
                                                 $this->auditlog($emp_id_leave,"payrollgenerate","update",$data,"hr_employee_payroll_lists");
						 $emp_data_payproposal[]=$employee->first_name.$employee->last_name;
                                       
                                        }
                                        else{
                                             $emp_data_shift[] = $employee->first_name.$employee->last_name; 
                                        }
					} 
                                        else{
                                            $emptype=DB::table("a_lookuplines_t")->where('lookuplines_id',$emp_type)->get();
                                         $emp_data_type[] = $emptype[0]->lookup_code;
                                        }
                                    }
                                    else{
                                         $emp_data_payroll[] = $employee->first_name.$employee->last_name; 
                                    }
			  	}
                                }
                                else{
                                         $emp_data_attend[] = $employee->first_name.$employee->last_name; 
                                    }
                                }	
				
  //dd("volunter--".$volunter_account."basic--".$basic_account."hra--".$hra_account."da--".$da_account."net--".$net_account."esi_emp--".$esi_employee_account."esi_comp--".$esi_company_account."pf_emp--".$pf_employee_account."pf_cmp--".$pf_company_account."pf_cmp1--".$pf_company1_account."pt--".$pt_account."tax--".$tax_account."ot--".$ot_account."annual allow--".$annual_allowance_account."gratuity--".$gratuity_account);
$accounts_data_allowance= \DB::select("select * from f_hr_account_allowance_setting_t left join f_hr_account_allowance_setting_lines_t on f_hr_account_allowance_setting_lines_t.account_allowance_setting_id=f_hr_account_allowance_setting_t.account_allowance_setting_id where department_id=$department");
  
 
  $department_name=\DB::select("SELECT `sub_department_name` FROM `m_department_lines_t` WHERE `department_line_id`=$department");
  $department_name=$department_name[0]->sub_department_name;
       $journal_name="PAYROLL-".$department_name."-".$account_month;
       $payrolldate=date('Y-m-d');
       $loc=\Session::get('location');
       $compy=\Session::get('companyid'); 
           
       // $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id)values('$journal_name','PAYROLL','$payrolldate','','APPROVED','$compy','$loc')");
        $jid = 1;//DB::getPdo()->lastInsertId();
  $accounts_data= \DB::select("select * from f_hr_account_setting_t where department_id=$department");

  $accounts_data_allowance= \DB::select("select * from f_hr_account_allowance_setting_t left join f_hr_account_allowance_setting_lines_t on f_hr_account_allowance_setting_lines_t.account_allowance_setting_id=f_hr_account_allowance_setting_t.account_allowance_setting_id where department_id=$department");
  $data_acc_debit=["basic_account_id","hra_account_id","da_account_id","annual_allowance_account_id","gratuity_account_id"];
  $data_acc_value_debit=[$basic_account,$hra_account,$da_account,$annual_allowance_account,$gratuity_account];
   $data_acc_credit=["professional_account_id","esi_employee_account_id","pf_employee_account_id","volunter_pf_account_id","advance_account_id","salary_account_id"];
  $gross_salary_account=0;
 
  
    $data_acc_exp=["esi_company_account_id","pf_company_account_id","pf_company1_account_id"];
  $data_acc_value_exp=[$esi_company_account,$pf_company_account,$pf_company1_account];
 $journal_lines_data=[];
 
   $tkey=0;  $r=0;$c=0;
   
     foreach($data_acc_debit as $k1=>$v1){           

if($data_acc_value_debit[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$accounts_data[0]->$v1;  
                
                   $gross_salary_account+=$data_acc_value_debit[$k1];
                $journal_lines_data[$tkey]['debit_amount']=$data_acc_value_debit[$k1];
                $journal_lines_data[$tkey]['credit_amount']='';
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
}
                   
  }
   
    if(count($accounts_data_allowance)>0){
      if(count($allow_acc)>0){
  foreach($accounts_data_allowance as $k11=>$v11){
              $check_id=$v11->allowance_id;
              if(isset($allow_acc[$check_id]) && $allow_acc[$check_id]>0){
                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$v11->account_structure_id;
				$gross_salary_account+=$allow_acc[$check_id];
                $r+= $journal_lines_data[$tkey]['debit_amount']=$allow_acc[$check_id];
                $journal_lines_data[$tkey]['credit_amount']='';
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
                   
              }
  }
      }
        if(count($deduct_acc)>0){
  foreach($accounts_data_allowance as $k11=>$v11){
              $check_id=$v11->allowance_id;
              if(isset($deduct_acc[$check_id])){
                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$v11->account_structure_id;
                $journal_lines_data[$tkey]['debit_amount']='';
               $c+= $journal_lines_data[$tkey]['credit_amount']=$deduct_acc[$check_id];
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
                   
              }
  }
      }
  }
  $gross_salary_account= $gross_salary_account-$pt_account-$esi_employee_account-$pf_employee_account-$volunter_account-$advance_account;
   $data_acc_value_credit=[$pt_account,$esi_employee_account,$pf_employee_account,$volunter_account,$advance_account,$gross_salary_account];
       foreach($data_acc_credit as $k1=>$v1){           

if($data_acc_value_credit[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$accounts_data[0]->$v1;  
                
                $journal_lines_data[$tkey]['debit_amount']='';
                $journal_lines_data[$tkey]['credit_amount']=$data_acc_value_credit[$k1];
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
}
                   
  }
  
 


  // \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 
   
     $journal_name="PAYROLL/PF/ESI-".$department_name."-".$account_month;
       $payrolldate=date('Y-m-d');
       $loc=\Session::get('location');
       $compy=\Session::get('companyid'); 
           
     //   $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id)values('$journal_name','PAYROLL/PF/ESI','$payrolldate','','APPROVED','$compy','$loc')");
        $jid =1; //DB::getPdo()->lastInsertId();
   $tkey=0;
   $journal_lines_data=array();
   
   
    $credit_acc=[89,87,88];
    foreach($data_acc_exp as $k1=>$v1){           

if($data_acc_value_exp[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL/PF/ESI";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$credit_acc[$k1];  
                
                $journal_lines_data[$tkey]['debit_amount']=$data_acc_value_exp[$k1];;
                $journal_lines_data[$tkey]['credit_amount']='';
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
}
   
							}
   
    foreach($data_acc_exp as $k1=>$v1){           

if($data_acc_value_exp[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL/PF/ESI";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$accounts_data[0]->$v1;  
                
                $journal_lines_data[$tkey]['debit_amount']='';
                $journal_lines_data[$tkey]['credit_amount']=$data_acc_value_exp[$k1];
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
}
   
  }
 
							// \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 
							}
  
            
				else
				{
                                   
                                    return array('status' => 'error','message' =>"Please check payproposal");  
					// $emp_data[] = $employee->first_name.$employee->last_name; 
				}
				
			   return array('status' => 'success','message' => "Payroll generated successfully",'emp_data_payroll'=>$emp_data_payroll,'emp_data_attend'=>$emp_data_attend,'emp_data_type'=>$emp_data_type,'emp_data_shift'=>$emp_data_shift,'emp_data_payproposal'=>$emp_data_payproposal); 
			}
			else
			{
			return array('status' => 'error','message' =>"Please Sync Attendance From Biometric");         
			}
			} 
			else 
			{
			 return array('status' => 'error','message' => "You have not reached the attendance cutt off date");  
			}	
                }
                else{
            return array('status' => 'error','message' => "Please enter The Cut-off date for the Year and the Company"); 
		}
    }else{

        $emp_data_payroll=array();
        	$emp_data_attend = array();
                    $emp_data_type=array();
                    $emp_data_shift=array();
                    $emp_data_payproposal=array();
                     $current_year    = $_GET['year'];
        $source  =   $_GET['source'];
        $department    =   $_GET['department'];
        $error_msg = "";
        // get month name from month
        $comp=\Session::get('companyid');
        $account_month=$_GET['month']."-".$current_year;
        $dateObj   = DateTime::createFromFormat('!m', $_GET['month']);
        $month_name = $dateObj->format('F');
       	$month =  $_GET['month'];
          $employees  = DB::table('hr_employee_t')->join("hr_employee_payproposal",function($join) use ($source,$comp)
                {		
                        $join->on("hr_employee_payproposal.employee_id","=","hr_employee_t.employee_id")
                        ->where("hr_employee_payproposal.payroll_type","=",$source);
                })->select('hr_employee_t.*','hr_employee_payproposal.*')->where('hr_employee_t.active','Yes')->get();

               
                if(count($employees)>0) {
                      $basic_account=0;
                                   $hra_account=0;
                                   $da_account=0;
                                   $net_account=0;
                                   $esi_employee_account=0;
                                   $esi_company_account=0;
                                   $pf_employee_account=0;
                                   $pf_company_account=0;
                                   $pf_company1_account=0;
                                   $pt_account=0;
                                   $tax_account=0;
                                   $ot_account=0;
                                   $advance_account=0;
                                   $allow_acc=array();
                                   $deduct_acc=array();
                                   $annual_allowance_account=0;
                                   $gratuity_account=0;
                                   $gross_salary_account=0;
                                   $volunter_account=0;
                                   $cl=0;
                                   $el=0;
                                   $od=0;
                                   $sl=0;
                                   $partial=0;
                                   $compensated_days=0;
								  // dd($employees);
                foreach($employees as $key=>$employee) 
				{
				   $emp_code   		    = $employee->employee_number;
				   $emp_id     	            = $employee->employee_id;
				   $department_check        = json_decode($employee->department);
                            //  dd($department);
                                    $dep=$department_check[0];

				   $i=1;
				   while($i>0)
				   {
					   $dep1=\DB::select("SELECT `parent_class_id` FROM `m_department_lines_t` WHERE `department_line_id`='$dep'");
         // echo $emp_code."<br>";
					   if($dep1[0]->parent_class_id==0)
					   {
						   $dep=$dep;
						   $i=0;
					   }
					   else
					   {
						  $dep=$dep1[0]->parent_class_id;  
						  $i=2;
					   }
				   }

				   if ($department==$dep)
                                   {
									  // dd($emp_id);
				   $emp_type                = $employee->employee_type;
				   $employee_position       = $employee->position;
				   $compid   	            = $employee->company_id;
                                   $loc_id=json_decode($employee->location_id);
				   $locationid   	    =$loc_id[0] ;
                                     $earn_salary=0;
                                     $ot_t_t=0;
                                   $casual_leave            = 0;
                                   $sick_leave 	            = 0;
                                 //employee already payroll generated check
				$attendance_exists_employee = DB::table("hr_monthly_attendance")->where('month',$month)->where('upload_file',1)->where('year',$current_year)->where('employee_id',$emp_id)->get();				
                               		$payroll_exists = DB::table("hr_employee_payroll_lists")->where('month',$month)->where('year',$current_year)->where('employee_id',$emp_id)->get();
  //$payroll_exists=array();

                           error_reporting(0);
                                if(count($attendance_exists_employee)>0){
                          
                                if (count($payroll_exists) == 0) 
				{
				    // dd($emp_id);
                                    // no of days
                                   $totaldays=$attendance_exists_employee[0]->no_of_days;
                                   $no_of_days_employee=$attendance_exists_employee[0]->no_of_days_employee;
								   
                                   $loan=$attendance_exists_employee[0]->loan_deduction;
								  
                                        // working days
                                        $working_days = $attendance_exists_employee[0]->no_of_present_days;	
									
                                       	$grade_details = DB::table("hr_employee_payproposal")->where('employee_id',$emp_id)->get();
						
						$gross_pay                    = $grade_details[0]->gross_pay;
						$net_pay_tax                    = $grade_details[0]->net_pay;
						$basic_pay                    = $grade_details[0]->basic_pay;
						$hra                          = $grade_details[0]->hra;
						$annual_allowance 	      = $grade_details[0]->annual_allowance;
						$da  			      = $grade_details[0]->da;
						$gratuity 		      = $grade_details[0]->gratuity;
						$allowance 	              = $grade_details[0]->allowance;
						
						$volunter_pf	              = $grade_details[0]->volunter_pf;
                                                if($volunter_pf==''){
                                                    $volunter_pf=0;
                                                }
                                                  $total_basic=$basic_pay/$totaldays;
                                                        $total_hra=$hra/$totaldays;
                                                        $total_da=$da/$totaldays;
                                                        $basic_pay = round($total_basic*$working_days);
                                                        $hra =round($total_hra*$working_days);
                                                        $da =round($total_da*$working_days);
                                                  $allows=json_decode($allowance,true);
                                                  
                                                  
                          $ex_allow= \DB::select("SELECT * FROM `hr_employee_payproposal_allowance` where employee_id='$emp_id' and month='$month' and year='$current_year'");                      
                              
                        $allow=(object)array();
                              
                        foreach($allows as $val)
                        {
                        foreach($val as $k=>$v)
                        {
                            
                            $allow->{$k}=$v;
                        }
                        }
                                                  
                          // dd($allow);         
						if(count($ex_allow)>0)
						{
						$ex_allow1=json_decode($ex_allow[0]->allowance);
					
							foreach($ex_allow1 as $ind=>$val)
						{
						    foreach($val as $k=>$v)
						    {
						       // echo $k."-->".$v;
						    if((float)$v>0)
						    {
						       // dd($allow);
						  $allow->{$k}=$v;  
						    }
						    }
						}
						}
						
                                                  
                         //  dd($allow);                       
                                                  
                                                
                                                      $deduct_allowance=0;
                                                      $allow_en=[];
						//total days and working days not equal mean calculate
					
							   foreach($allow as $k1=>$v1){
                                                        
                                                                $allowance_deduction=\DB::SELECT("SELECT type from m_allowance_tbl where allowance_id=".$k1);
                                                       if(count($allowance_deduction)>0){
                                                                if($allowance_deduction[0]->type=="Allowance"){
                                                                $total_allow=$v1/$totaldays;
                                                                 
                                                            $add=($total_allow*$working_days);
                                                          
                                                            $allow_en[$k][$k1]=(string)round($add,2);
                                                            if(isset($allow_acc[$k1]))
                                                            {
                                                           
                                                              $allow_acc[$k1]=round($allow_acc[$k1]+$add,2);
                                                            }
                                                            else
                                                            {
                                                                $allow_acc[$k1]=round($add,2); 
                                                            }
                                                       }  
                                                       
                                                         if($allowance_deduction[0]->type=="Deduction"){
                                                           
                                                             $total_allow=$v1/$totaldays;
                                                                 
                                                            $add=($total_allow*$totaldays);
                                                          $deduct_allowance=$add+$deduct_allowance;
                                                            $allow_en[$k][$k1]=(string)round($add,2);
                                                            if(isset($deduct_acc[$k1]))
                                                            {
                                                           
                                                              $deduct_acc[$k1]=round($deduct_acc[$k1]+$add,2);
                                                            }
                                                            else
                                                            {
                                                                $deduct_acc[$k1]=round($add,2); 
                                                            }
                                                             
                                                         }
                                                         
                                                           
                                                            
                                                        }
                                                        }
                                                        
                                   // dd($deduct_acc);                    
                                                        
                        	if(count($ex_allow)>0)
						{
						$ex_allow1=json_decode($ex_allow[0]->allowance);
					
							foreach($ex_allow1 as $ind=>$val)
						{
						    
						    
						     foreach($val as $k=>$v)
						    {
						       if(isset($allow_acc[$k]) && $v!=0)
						  $allow_acc[$k]=$v;  
						    }
						    
						   
						}
						}                                
                                                        
                                                      
                                                       // dd(json_encode($allow_en));
                                                        $allowance =json_encode($allow_en);
                                              
				   $basic_account=$basic_account+$basic_pay;
                                   $hra_account=$hra_account+$hra;
                                   $da_account=$da_account+$da;
                                   $volunter_account=$volunter_account+$volunter_pf;
                              
					    $year_net=$net_pay_tax*12;
						//dd($year_net);
						$all=($basic_pay+$hra+$da);
						$length     = \DB::select("SELECT COUNT(DISTINCT to_value) as length FROM `hr_tax_master`");
						$length     = $length[0]->length;
						$limit      = 0;
						$tax_value  = 0;
						$tax        = 0; 
						$tax_am=0;
						$tax        = DB::table("hr_tax_master")->select('precentage','to_value','from_value')->orderBy('id', 'asc')->get();
							$balance_amount=$year_net;
						//Checking Percentage For Annual Income for Basic Pay
						foreach($tax as $tax_val)
						{
							$from_value = $tax_val->from_value;
							$to_value   = $tax_val->to_value;
							$anu_pay    = $year_net*12;
							$deduct_amounts=$to_value-$from_value;
							if($to_value <= $year_net)
							{
								if($deduct_amounts <= $balance_amount)
								{
									$deduct_amount_val=$deduct_amounts;
								}
								else
								{
								$deduct_amount_val=	$balance_amount;
							
								}
								$balance_amount=$balance_amount-$deduct_amount_val;
								$precentage =   $tax_val->precentage;
								
								$tax_am+=($deduct_amount_val*$precentage)/100;
								
							}
							else if($from_value <= $year_net && $to_value >= $year_net)
							{
								$precentage =   $tax_val->precentage;
								$tax_am+=($balance_amount*$precentage)/100;
								
							}
							
						}
						//dd($tax_am);
						//If Tax is zero No deduction 
					    /******* ******/
                                                $tax=0;
						if($tax_am!=0)
						{
							$year=DB::table("account_year")->where('status',1)->get();
							$year=$year[0]->id;
							$inv_type=DB::table('hr_inve_type')->get();
							$inv_pay=0;
							$act_pay=0;
							$amo=0;
							$act=0;
							//dd($month);
							$check=DB::table("hr_inve_declaration")->where('emp_id',$emp_id)->where('year',$year)->where('upload_status',1)->get();
							if(count($check)>0)
								$month1=$check[0]->month;
							else
								$month1=0;
							if($month <= $month1)
								$check="";

							if(!$check)
							{
								foreach ($inv_type as $inv)
								{
									$inv_id=$inv->inv_id;
									$limit=$inv->inv_limit;
									$value=DB::table('hr_inve_declaration')->select(DB::raw('sum(hr_inve_declaration.inv_amount) as total'))->where('emp_id',$emp_id)->where('inv_id',$inv_id)->where('year',$year)->where('status',1)->get();
                                                                       
									if($value[0]->total != 0)
									{
										$amo=$value[0]->total;
									}
									if($limit != 0)
									{
										if($amo > $limit)
										{
											$amo=$limit; 
										}  
									}
									$inv_pay=$inv_pay+$amo;   
								}
								
								$inv_pay=  round($inv_pay/12,2);
								if($tax > $inv_pay)
									$tax=$tax-$inv_pay;
								else 
									$tax=0;
							}
							else
							{
								foreach ($inv_type as $inv)
								{
									$inv_id=$inv->inv_id;
									$limit=$inv->inv_limit;
									$value=DB::table("hr_inve_declaration")->selectRaw('SUM(inv_amount) as inv_total,SUM(act_amount) as act_total')->where('emp_id',$emp_id)->where('inv_id',$inv_id)->where('year',$year)->get();
									if($value[0]->inv_total != 0)
									{
										$amo=$value[0]->inv_total;
										$act=$value[0]->act_total;
									}
									
									if($limit != 0)
									{
										if($amo > $limit)
										{
										  $amo=$limit;  
										}
										if($act > $limit)
										{
											$act=$limit;
										}
									}
									
									$act_pay=$act_pay+$act;
									$inv_pay=$inv_pay+$amo;  
								} 

								$loss_value=0;
								$gain_value=0;
								
								if($act_pay <= $inv_pay)
								{
									$loss_value= $inv_pay - $act_pay;
								}
								else
								{
									$gain_value=$act_pay-$inv_pay;
									$months=DB::table("hr_inve_declaration")->select('month')->where('emp_id',$emp_id)->where('inv_id',$inv_id)->where('year',$year)->get();
									$inv_month=$months[0]->month;
									$year=DB::table(" account_year")->select('year_to')->where('status',1)->get();
									$year=$year[0]->year_to;
									$eff_from=date('01-'.$inv_month.'-Y');
									$eff_from=new DateTime($eff_from);
									$eff_to=date('31-03-'.$year);
									$eff_to=new DateTime($eff_to);
									$diff=$eff_to->diff($eff_from);
									$diff_value= $diff->format('%m months');
								}

								if($loss_value != 0)
								{
									$loss=round($loss_value/$diff_value,2);
									$act_pay=  round($inv_pay/12,2);
									if($tax > $act_pay)
										$tax=$tax-$inv_pay+$loss;
									else 
										$tax=0;
								}
								
								if($gain_value != 0)
								{
									$gain=round($gain_value/$diff_value,2);
									$act_pay=  round($inv_pay/12,2);
									if($tax > $act_pay)
									{
										$tax=$tax-$act_pay-$gain;
										if($tax < 0)
											$tax=0;
									}
									else 
										$tax=0;
								}
                                                            }
                                }  
                             //    dd($tax);
                                   $tax_account=$tax_account+$tax;
                                                $salary=$gross_pay;      
						$perday_salary  = $salary / $totaldays;    
						$gross_salary   = round($perday_salary * $working_days);
						$ot = 0;
						//ESI calculation and deduction
						// esi percentage get from master
						$esi_detatil    =    $esi=DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.limitto','m_emp_esi.employeer_contribute','m_emp_esi.company_contribute')->where('lookup_code','ESI')->get();
                                                // pf percentage get from master
						$pf_detatil   =DB::table("m_emp_esi")
                ->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','m_emp_esi.components')
                ->select('m_emp_esi.limitto','m_emp_esi.employeer_contribute','m_emp_esi.company_contribute','m_emp_esi.company_contribute1')->where('lookup_code','PF')->get();
                                                // pf basic
						$pf_value=$basic_pay+$da;
                                                // esi basic
						$esi_value=$gross_salary;
                                                // esi employee and company contribute
												$company_contribute=array();
                            $emp_con=0; 
                            $date_month = $month;
                            $date_year = $current_year;
                            // dd($grade_details);
						if($grade_details[0]->esi==1)
						{
                                                
							if(count($esi_detatil)>0){
                                        $limit=$esi_detatil[0]->limitto;
                                                          
                                        if($gross_pay>$limit){
                                            $emp_con =0;  
							                $com_con=0;
                                             // dd($overall_gross,$esi_detatil[0]->employeer_contribute,($overall_gross*$esi_detatil[0]->employeer_contribute/100));
                                                if($date_month!=4 && $date_month!=10){
                                                    if($date_month==1){
                                                        $prev_month = 12;
                                                        $prev_year = $date_year-1;
                                                    }else{
                                                        $prev_month = $date_month-1;
                                                        $prev_year = $date_year;
                                                    }
                                                    $prev_cont_esi = DB::table('hr_company_contribute_esi')->where('emp_id',$emp_id)->where('amount_employee','!=',0)->where('month',$prev_month)->where('year',$prev_year)->get();  
                                                    if(count($prev_cont_esi)>0){
                                                        $emp_con    =   ceil($esi_value*$esi_detatil[0]->employeer_contribute/100);
                                                        $com_con    =  round($esi_value*$esi_detatil[0]->company_contribute/100,2);
                                                    }else{
                                                        $emp_con = 0;
                                                        $com_con = 0;
                                                    }
                                                }else{
                                                   $emp_con = 0;
                                                   $com_con = 0;
                                                }           
                                        }else{
                                            $emp_con                              =   ceil($esi_value*$esi_detatil[0]->employeer_contribute/100);
							                $com_con                              =  round($esi_value*$esi_detatil[0]->company_contribute/100,2);
                                        }
				// 			dd($emp_con,$com_con);
							$esi                                  =   $emp_con;
							$company_contribute['emp_id']         =   $emp_id;                                             
							$company_contribute['month']          =   $month;                                             
							$company_contribute['year']           =   $current_year;                                             
							$company_contribute['amount']         =  $com_con;                                             
							$company_contribute['amount_employee']=  $emp_con;                                             
							$company_contribute['date']           =   date('Y-m-d');
							$result = DB::table('hr_company_contribute_esi')->insertGetId($company_contribute);
                                                          // auditlog
                                                            $this->auditlog($result,"createemployee","create",$company_contribute,"hr_company_contribute_esi");
                                                                $esi_company_account=$esi_company_account+ round($com_con);
                                                        }
						}
						else
						{
							$esi=0;
							
                        // dd($overall_gross,$esi_detatil[0]->employeer_contribute,($overall_gross*$esi_detatil[0]->employeer_contribute/100));
                        if($date_month!=4 && $date_month!=10){
                            if($date_month==1){
                                $prev_month = 12;
                                $prev_year = $date_year-1;
                            }else{
                                $prev_month = $date_month-1;
                                $prev_year = $date_year;
                            }
                            $prev_cont_esi = DB::table('hr_company_contribute_esi')->where('emp_id',$emp_id)->where('amount_employee','!=',0)->where('month',$prev_month)->where('year',$prev_year)->get();  
                            if(count($prev_cont_esi)>0){
                                $emp_con    =   ceil($esi_value*$esi_detatil[0]->employeer_contribute/100);
                                $com_con    =  round($esi_value*$esi_detatil[0]->company_contribute/100,2);
                            }else{
                                $emp_con = 0;
                                $com_con = 0;
                            }
                        }else{
                           $emp_con = 0;
                           $com_con = 0;
                        }  
                        // dd($emp_con,$com_con);
                         if($emp_con!=0){
                      
                              $esi                                  =   $emp_con;
                              $company_contribute['emp_id']         =   $emp_id;                                             
                              $company_contribute['month']          =   $date_month;                                             
                              $company_contribute['year']           =   $date_year;                                             
                              $company_contribute['amount']         =  $com_con;                                             
                              $company_contribute['amount_employee']=  $emp_con;                                             
                              $company_contribute['date']           =   date('Y-m-d');
                              $company_contribute['arrear_status']           =1;
                              $result = DB::table('hr_company_contribute_esi')->insertGetId($company_contribute);
                                // auditlog
                                $this->auditlog($result,"createemployee","create",$company_contribute,"hr_company_contribute_esi");
                                $esi_company_account=$esi_company_account+ round($com_con);
                            }
                      $esi=$emp_con;
                    
						}
				// 		dd($esi);
						$esi = ceil($esi);
                                              // pf company and employee contibute
						if($grade_details[0]->pf==1)
						{
                                                    if(count($pf_detatil)>0){
                                                        $limit=$pf_detatil[0]->limitto;
                                                            if($pf_value>=$limit){
                                                                    /*$pf                                      = round($limit*$pf_detatil[0]->employeer_contribute/100);
                                                                    $emp_con1                                = round($limit*$pf_detatil[0]->employeer_contribute/100);
                                                                    $com_con1                                = round(($limit*$pf_detatil[0]->company_contribute/100)-1);                                                                    $com_con2                               = round($limit*$pf_detatil[0]->company_contribute1/100);
																	$edli=75;*/
																	$pf                                      = round($limit*$pf_detatil[0]->employeer_contribute/100);
                                                                    $emp_con1                                = 1800;
                                                                    $com_con1                                = 1250;
                                                                    $com_con2                               = 550;
																	$edli=75;
                                                                
                                                            }else{
                                                                $pf                                      = round($pf_value*$pf_detatil[0]->employeer_contribute/100);
                                                                $emp_con1                                = round($pf);
                                                                $com_con1                                = round($pf_value*$pf_detatil[0]->company_contribute/100);
                                                                $com_con2                                = round($pf_value*$pf_detatil[0]->company_contribute1/100);
																$edli=round(($pf_value*0.5)/100);
														   }
															$epf=$pf;
							 $pf =$volunter_pf+$pf;
							 
							 $vpf=$volunter_pf;
							$company_contribute['emp_id']            =   $emp_id;                                             
							$company_contribute['volunter_pf']            =$volunter_pf;                                             
							$company_contribute['month']             =   $month;                                             
							$company_contribute['year']              =   $current_year;                                             
							$company_contribute['amount']            =   round($com_con1);                                             
							$company_contribute['amount1']            =   round($com_con2); 
                                            
							$company_contribute['edli_charges']            =   round($edli);                                             
							$company_contribute['admin_charges']            =   round($edli); 							
							$company_contribute['amount_employee']   =  round($emp_con1);                                             
							$company_contribute['date']              =   date('Y-m-d');
							
							$result = DB::table('hr_company_contribute_pf')->insertGetId($company_contribute);
                                                        // auditlog
                                                        $this->auditlog($result,"createemployee","create",$company_contribute,"hr_company_contribute_pf");
                                                            
                                                        $pf_company_account=$pf_company_account+ round($com_con1);
                                                        $pf_company1_account=$pf_company1_account+ round($com_con2);
                                                    }
						}
						else
						{
							$pf = 0;
							$epf = 0;
						}
						$pf = round($pf);
						
						              
                    // IF Professional tax   
               $location = json_decode(Session::get('location'));
               $professional_taxs = DB::table('m_location_t')->leftJoin('hr_professional_tax_hdr', 'hr_professional_tax_hdr.ptax_state_id', '=', 'm_location_t.state_id')->leftjoin('hr_professional_tax_lines','hr_professional_tax_lines.ptax_id','=','hr_professional_tax_hdr.ptax_id')->select('hr_professional_tax_hdr.*','hr_professional_tax_lines.*')->where('m_location_t.location_id',$location)->get();
             
               if($grade_details[0]->pt==1)
                    {      
                   if(count($professional_taxs)>0){
                       
                        foreach($professional_taxs as $index=>$value)
                        {
                            
                            if($gross_salary>=$value->from_value && $gross_salary<=$value->to_value)
                            {
                                 $professional_tax = $value->deduction_amount;
                                 break;
                            }else{
                                 $professional_tax=0;
                            }
                        }
                    }else{
                        $professional_tax=0;
                       
                    }
                    } 
                    else
                    {							 
                        $professional_tax=0;
                    }  
                                           
                                             
                                   $esi_employee_account=$esi_employee_account+$esi;
                                   $pf_employee_account=$pf_employee_account+ round($epf);
                                   $pt_account=$pt_account+ round($professional_tax);   
						// IF TAXATION AMOUNT
						$tax_amount = $tax;
                                                // minus tax amount
						$net_salary = round($gross_salary - $tax_amount,2);
						$net_salary = round($gross_salary-$pf-$esi-$professional_tax-$deduct_allowance,2);
						$loan_deduction_amt = 0;
						//IF DEDUCTION AMOUNT
						$total_loan_amount=0;
						$deduction = DB::table("hr_employee_advance_t")->where('employee_id',$emp_id)->where('remaining_amount','!=',0)->where('paid_status',0)->where('approved_status','=',1)->get();
if($loan==1){
						if (!empty($deduction)) 
						{  
							foreach($deduction as $value)
							{
								if($deduction[0]->advance_from==1){
								$permonth_amount    =   $deduction[0]->amount / $deduction[0]->emi;
                                                                }
								else{
                                                                    $permonth_amount    =   $deduction[0]->amount;
                                                                }
								$paid_amount1        =   $value->remaining_amount-$permonth_amount;

								if($value->remaining_amount<$permonth_amount)
									$paid_amount1 = $value->remaining_amount;
								else
									$paid_amount1 = $permonth_amount;
									$loan_deduction_amt  =   $paid_amount1;
									$paid_amount         =   round($deduction[0]->paid_amount + round($paid_amount1));
									$remaining_amount    =   round($value->remaining_amount-$loan_deduction_amt);
									$net_salary          =   round($net_salary - $loan_deduction_amt);							
									$total_loan_amount   =   round($total_loan_amount+$loan_deduction_amt);
									$employee_advance_id =   $value->advance_id;
									$emp_advance = array("paid_amount" => $paid_amount,"remaining_amount" => $remaining_amount);								
									\DB::table('hr_employee_advance_t')->where('advance_id',$employee_advance_id)->limit(1)->update($emp_advance);
                                                                               // auditlog
                                                                        $this->auditlog($employee_advance_id,"payrollgenerate","update",$emp_advance,"hr_employee_advance_t");
									if($paid_amount1 == $value->remaining_amount)
									{
										$query1 = DB::table("hr_employee_advance_t")->where('advance_id',$employee_advance_id)->update(['paid_status'=>1]); 
                                                                                 // auditlog
                                                                                $update['paid_status']=1;
                                                                        $this->auditlog($employee_advance_id,"payrollgenerate","update",$update,"hr_employee_advance_t");
									}

									$current_date=date('Y-m-d');

									$deductions_data = DB::table("hr_employee_advance_t")->where('advance_id',$employee_advance_id)->get();
								    foreach($deductions_data as $key=>$value)
									{
										$deductions = array(
											'ref_id'        => $value->advance_id,
											'employee_id'       => $value->employee_id,
											'deduction_date'    => $current_date,
											'total_amount'      => round($deductions_data[0]->amount,2),
											'till_paid_amount'  => $value->paid_amount,
											'till_remaining_amount' => $value->remaining_amount,
											'amount_pay' 		=>$paid_amount1,
											'company_id' 		=>\Session::get('companyid'),
											'location_id' 		=>\Session::get('location'),
											'organization_id' 		=>\Session::get('organization'),
											'created_at' 		=>date('Y-m-d'),
											'created_by' 		=>\Session::get('id'),
										);
									}
								
								$result = DB::table('hr_employee_advancedeductions_t')->insertGetId($deductions);
                                                                          // auditlog
                                                                        $this->auditlog($result,"payrollgenerate","create",$deductions,"hr_employee_advancedeductions_t");
								if($paid_amount1 == $value->remaining_amount)
								{
									$query2 = DB::table("hr_employee_advancedeductions_t")->where('paid_status',0)->where('employee_id',$emp_id)->where('ref_id',$value->advance_id)->update(['paid_status'=>1]); 
                                                                        // auditlog
                                                                        $update['paid_status']=1;
                                                                        $this->auditlog($value->advance_id,"payrollgenerate","update",$update,"hr_employee_advancedeductions_t");
								}
							}
						}
                                                
}		
                                              //earn leave greater than 24 gives additional for those days(basic+da*extra days(
                                                 /**   $earn_leave_data=DB::table('hr_employee_t')->where('employee_id',$emp_id)->get();
						$earn_leave_original=$earn_leave_data[0]->e_l;
                                                if($earn_leave_original>24){
                                                DB::table('hr_employee_t')->where('employee_id',$emp_id)->update(['e_l'=>24]);
                                                // auditlog
                                                 $update['e_l']=24;
                                                 $update_earn['actual_e_l']=$earn_leave_original;
                                                 $update_earn['remaining_e_l']=24;
                                                 $this->auditlog($emp_id,"payrollgenerate","update",$update_earn,"hr_employee_t");
						 $salary_earn=$earn_leave_original-24;
                                                $earn_salary=($basic_pay+$da)*$salary_earn;
                                                }
                                                // adittion of net to earn
						$net_salary=$earn_salary+$net_salary;**/
                                                //ot 
                                                if($ot_t_t!=0){
                                                 $ot_hr       = round(abs($ot_t_t) / 3600,2);
                                            $ot_amount         =($basic_pay/$total_days)/8*1 * $ot_hr;
                                                }
                                                else{
                                                   $ot_amount=0;  
                                                }
                                                $net_account=$net_account+$net_salary;
                                                $advance_account=$advance_account+$total_loan_amount;
                                                $ot_account=$ot_account+$ot_amount;
                                                $annual_allowance_account=$annual_allowance+$annual_allowance_account;
                                                $gratuity_account=$gratuity_account+$gratuity;
                                                $gross_salary_account=$gross_salary_account+$gross_salary;
                                              $current_date=date('Y-m-d');
						$data = array(
							'employee_id'       => $emp_id,
							'month'             => $month,
							'year'              => $current_year,
							'basic_salary'      => round($basic_pay),
							'hra'               => round($hra),
							'da'                => round($da),
							'pf'                => round($pf),
							'annual_allowance'  => round($annual_allowance),
							'gratuity'          => round($gratuity),
							'allowance'         => $allowance,
							'esi'               => round($esi),
							'pt'               => round($professional_tax),
							'earn_salary'       => round($earn_salary),
							'gross_salary'      => round($gross_salary),
							'net_salary'        => round($net_salary),
							'date'              => $current_date,
							'total_days'        => $totaldays,
							'no_of_days_employee'        => $no_of_days_employee,
							'attendance_days'   => $working_days,
							'loan_deduction'    => $total_loan_amount,
							'atten_status'      => $source,
							'tax_amount'        => round($tax_amount),
							'cl'                => $cl,
							'el'                => $el,
							'od'                => $od,
							'sl'                => $sl,
							'ot'                => $ot_amount,
							'partial_day'                => $partial,
							'compensated_day'                => $compensated_days,
                                                        'company_id'        =>\Session::get('companyid'),
						        'location_id' 	    =>\Session::get('location'),
							'organization_id'   =>\Session::get('organization'),
							'created_at' 	    =>date('Y-m-d'),
							'created_by' 	    =>\Session::get('id'),);
                                                  // save payslip for an employee
                         			 $emp_id_leave = DB::table('hr_employee_payroll_lists')->insertGetId($data);
                                                  //auditlog
                                                $this->auditlog($emp_id_leave,"payrollgenerate","update",$data,"hr_employee_payroll_lists");
						 $emp_data_payproposal[]=$employee->first_name.$employee->last_name;
                                }else{
                                      $emp_data_payroll[] = $employee->first_name.$employee->last_name;
                                }  
                                }else{
                                      $emp_data_attend[] = $employee->first_name.$employee->last_name; 
                                }
                                
                                   }
                               }
                                $department_name=\DB::select("SELECT `sub_department_name` FROM `m_department_lines_t` WHERE `department_line_id`=$department");
  $department_name=$department_name[0]->sub_department_name;
       $journal_name="PAYROLL-".$department_name."-".$account_month;
       $payrolldate=date('Y-m-d');
       $loc=\Session::get('location');
       $compy=\Session::get('companyid'); 
           
       // $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id)values('$journal_name','PAYROLL','$payrolldate','','APPROVED','$compy','$loc')");
        $jid =1; //DB::getPdo()->lastInsertId();
  $accounts_data= \DB::select("select * from f_hr_account_setting_t where department_id=$department");

  $accounts_data_allowance= \DB::select("select * from f_hr_account_allowance_setting_t left join f_hr_account_allowance_setting_lines_t on f_hr_account_allowance_setting_lines_t.account_allowance_setting_id=f_hr_account_allowance_setting_t.account_allowance_setting_id where department_id=$department");
  $data_acc_debit=["basic_account_id","hra_account_id","da_account_id","annual_allowance_account_id","gratuity_account_id"];
  $data_acc_value_debit=[$basic_account,$hra_account,$da_account,$annual_allowance_account,$gratuity_account];
   $data_acc_credit=["professional_account_id","esi_employee_account_id","pf_employee_account_id","volunter_pf_account_id","advance_account_id","salary_account_id"];
  $gross_salary_account=0;
 
  
    $data_acc_exp=["esi_company_account_id","pf_company_account_id","pf_company1_account_id"];
  $data_acc_value_exp=[$esi_company_account,$pf_company_account,$pf_company1_account];
 $journal_lines_data=[];
  $debit_amount=0;
 $credit_amount=0;
 
   $tkey=0;  $r=0;$c=0;
   
     foreach($data_acc_debit as $k1=>$v1){           

if($data_acc_value_debit[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$accounts_data[0]->$v1;  
                
                   $gross_salary_account+=$data_acc_value_debit[$k1];
                   $debit_amount=round($debit_amount+$data_acc_value_debit[$k1],2);
                $journal_lines_data[$tkey]['debit_amount']=round($data_acc_value_debit[$k1],2);
                $journal_lines_data[$tkey]['credit_amount']='';
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
}
                   
  }
   
    if(count($accounts_data_allowance)>0){
      if(count($allow_acc)>0){
  foreach($accounts_data_allowance as $k11=>$v11){
              $check_id=$v11->allowance_id;
              if(isset($allow_acc[$check_id]) && $allow_acc[$check_id]>0){
                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$v11->account_structure_id;
        $gross_salary_account+=$allow_acc[$check_id];
        $debit_amount=round($debit_amount+$allow_acc[$check_id],2);
                $r+= $journal_lines_data[$tkey]['debit_amount']=round($allow_acc[$check_id],2);
                $journal_lines_data[$tkey]['credit_amount']='';
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
                   
              }
  }
      }
        if(count($deduct_acc)>0){
  foreach($accounts_data_allowance as $k11=>$v11){
              $check_id=$v11->allowance_id;
              if(isset($deduct_acc[$check_id])){
                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$v11->account_structure_id;
                $journal_lines_data[$tkey]['debit_amount']='';
                $credit_amount=round($credit_amount+$deduct_acc[$check_id],2);
               $c+= $journal_lines_data[$tkey]['credit_amount']=round($deduct_acc[$check_id],2);
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
                   
              }
  }
      }
  }
  $gross_salary_account= $gross_salary_account-$pt_account-$esi_employee_account-$pf_employee_account-$volunter_account-$advance_account;
   $data_acc_value_credit=[$pt_account,$esi_employee_account,$pf_employee_account,$volunter_account,$advance_account,$net_account];
       foreach($data_acc_credit as $k1=>$v1){           

if($data_acc_value_credit[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$accounts_data[0]->$v1;  
                
                $journal_lines_data[$tkey]['debit_amount']='';
                  $credit_amount=round($credit_amount+$data_acc_value_credit[$k1],2);
                $journal_lines_data[$tkey]['credit_amount']=round($data_acc_value_credit[$k1],2);
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
}
                   
  }
  
 $diff=round($debit_amount-$credit_amount,2);

 if($diff !=0)
 {
                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=460;  
                if($diff > 0)
                {
                $journal_lines_data[$tkey]['debit_amount']=0;
                $journal_lines_data[$tkey]['credit_amount']=$diff;
              }
              else
              {
                $journal_lines_data[$tkey]['debit_amount']=$diff*(-1);
                 $journal_lines_data[$tkey]['credit_amount']=0;
              }
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
 }


  // \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 

   //\DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 
   
     $journal_name="PAYROLL/PF/ESI-".$account_month;
       $payrolldate=date('Y-m-d');
       $loc=\Session::get('location');
       $compy=\Session::get('companyid'); 
           
      //  $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id)values('$journal_name','PAYROLL/PF/ESI','$payrolldate','','APPROVED','$compy','$loc')");
        $jid =1; //DB::getPdo()->lastInsertId();
   $tkey=0;
   $journal_lines_data=array();
   
   
    $credit_acc=[89,87,88];
    foreach($data_acc_exp as $k1=>$v1){           

if($data_acc_value_exp[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$credit_acc[$k1];  
                
                $journal_lines_data[$tkey]['debit_amount']=$data_acc_value_exp[$k1];;
                $journal_lines_data[$tkey]['credit_amount']='';
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
}
   
							}
   
    foreach($data_acc_exp as $k1=>$v1){           

if($data_acc_value_exp[$k1] > 0)
{

                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$payrolldate;
                $journal_lines_data[$tkey]['reference_source']="PAYROLL";
                $journal_lines_data[$tkey]['reference_id']=$department;
                $journal_lines_data[$tkey]['account_id']=$accounts_data[0]->$v1;  
                
                $journal_lines_data[$tkey]['debit_amount']='';
                $journal_lines_data[$tkey]['credit_amount']=$data_acc_value_exp[$k1];
                
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
                 $tkey++;
}
   
  }
 
//							 \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 
							         return array('status' => 'success','message' => "Payroll generated successfully",'emp_data_payroll'=>$emp_data_payroll,'emp_data_attend'=>$emp_data_attend,'emp_data_type'=>$emp_data_type,'emp_data_shift'=>$emp_data_shift,'emp_data_payproposal'=>$emp_data_payproposal); 
                             
                }else{
                         return array('status' => 'error','message' =>"Please check payproposal");  
                }
    }
	}
    }
	// min to hr calculation
	public function mintohr($minutes,$start_time=null)
		{
			
                   if($start_time==null)
                    {
                      $minutes=(int)$minutes;

                      $return=date('H:i:s', mktime(0,$minutes,0));
                      return $return;
                       
                    }
                    else 
                    {
                         
                        $hours = $this->digit(floor($minutes / 60));
                        $min = $this->digit($minutes - ($hours * 60));
                        
                        $time_diff=new DateTime($hours.":".$min.":00");

                        $time_diff=  strtotime(($time_diff->format('H:i:s')))-strtotime("00:00:00");
                        $total_time=  $start_time-$time_diff;
                      
                        return $total_time;
                     }
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
		// week number check
		public function weeknumber($date)
        {
            $week_name  =  array("1"=>"first","2"=>"second","3"=>"third","4"=>"fourth","5"=>"fifth");
            $firstOfMonth = date("Y-m-01", strtotime($date));
            $Month        = (string)date("m", strtotime($date));
            $Date         = (string)date("d", strtotime($date));
            
            if($Month=="01" && $Date!="01")
                $week_month=intval(date("W", strtotime($date)));
            else if($Month=="12") 
            { 
                $week=intval(date("W", strtotime($date)));
                
                if($week==1)
                {
                    $week_month=5;
                } 
                else
                {
                    $week_month=intval(date("W", strtotime($date)))-intval(date("W", strtotime($firstOfMonth)))+1;
                } 
            }  
            else
            {  
                $week_month=intval(date("W",strtotime($date)))-intval(date("W",strtotime($firstOfMonth)))+1;
            }
           // echo $week_month;
            return  $week_name[$week_month]; 
        }
		// hr to digit
		function digit($digit)
        {
            if($digit <= 9)
            {
                $result="0".$digit;
            }
            else 
            {
                $result=$digit;
            }
            return $result;
        }
	// grid data for generate payroll
	public function payrolllistgriddata(Request $request)
    {
     
		$wh=' and approved_status=0';
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
       
	if(!$sidx) $sidx =1;
        
        

	$result = \DB::select("SELECT COUNT(id) AS count FROM  hr_employee_payroll_lists as  hr_employee_payroll_lists where 1=1 $wh");
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
                    hr_employee_payroll_lists.id,
                    hr_employee_payroll_lists.employee_id,
                    hr_employee_payroll_lists.month,
                    hr_employee_payroll_lists.year,
                    hr_employee_payroll_lists.basic_salary,
                    hr_employee_payroll_lists.hra,
                    hr_employee_payroll_lists.da,
                    hr_employee_payroll_lists.conveyance,
                    hr_employee_payroll_lists.pf,
                    hr_employee_payroll_lists.esi,
					hr_employee_payroll_lists.gross_salary,
					hr_employee_payroll_lists.net_salary,
					hr_employee_payroll_lists.total_days,
					hr_employee_payroll_lists.attendance_days
					
                    FROM
                        hr_employee_payroll_lists
                    
                    WHERE
                        1 = 1 $wh ORDER BY $sidx $sord LIMIT $start,$limit";
        
       
	$result = \DB::select($SQL);
	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

      
	echo json_encode($responce);
    }
		
}
