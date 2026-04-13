<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Config;
use App\Paymentforinvoice;
use Yajra\DataTables\DataTables;

class PaymentforfandfController extends Controller   // Employee bonus payment - VIGNESH M 
{

    public $module="paymentforinvoice";

  public function __construct()
  {
    $this->data=array();
    $this->data=array();
    $this->table="p_payments_t";
    $this->data['pageMethod']=\Request::route()->getName();
    $this->data['pageFormtype']='ajax';
    $this->data['urlmenu']=$this->indexs(); 

  }
  
    public function employeepayindex(Request $request)
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

        $this->data['sum_inv_tot'] = \DB::select("SELECT ROUND(SUM(balance_amount),0) as total from hr_ff_t where hr_ff_t.status = 'APPROVED' AND hr_ff_t.payment_status IS NULL");
        $this->data['department_id']=$this->jcombo('m_department_lines_t','department_line_id','sub_department_name','');
        return view('paymentforfandf.paymentforfandf',$this->data);
       
    }
    
    // payment for bonus
    public function employeefandfgrid()
    {
  
        
	$SQL = "SELECT
    hr_ff_t.hr_ff_id,
    hr_ff_t.emp_id,
    hr_employee_t.employee_number,
    hr_employee_t.first_name,
    hr_ff_t.balance_amount,
    hr_ff_t.status
    FROM
        hr_ff_t
    LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_ff_t.emp_id
    WHERE
        1 = 1 AND hr_ff_t.status = 'APPROVED' AND hr_ff_t.payment_status IS NULL ORDER BY hr_ff_t.hr_ff_id DESC";

	$result = \DB::select($SQL);
	return DataTables::of($result)->make(true);
    
    }
    

    // create payment
    
    public function paymentforfandfcreate($payslipid=null){
        
  
       $this->data['pageModule']="paymentforemployee";
       $this->data['pageUrl']=url('paymentforemployee');
       $table = \DB::table('hr_ff_t')->select('emp_id','balance_amount','hr_ff_id')->where('payment_status', NULL)->where('payment_id', NULL)->whereIn('emp_id', explode(',',$payslipid))->get();
       
      $this->data['row']= (object) array();
      $this->data['row']->payment_id = "";
      $this->data['row']->payment_number = "";
      $this->data['row']->payment_date=date('Y-m-d');
      $this->data['row']->cheque_date=date('Y-m-d');
      $payamt=$this->data['row']->payment_status="";
      $this->data['row']->payment_amount="";
      $this->data['row']->payment_source="F&FPAYMENT";
      $this->data['row']->reference_id=$payslipid;
      $this->data['row']->payment_type_id="";
      $this->data['row']->sender_information="";
      $this->data['row']->payment_reference="";
      $this->data['row']->cheque_no="";
      $this->data['row']->remarks="";
      $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t','bank_account_line_id','account_number','');
      $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','',"and bank_source='Company Account'");
      $this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','account_name','');
      $this->data['employee_id']= $this->jCombologin('hr_employee_t','employee_id','employee_number|first_name','');
      $this->data['imprest_employee_id']= $this->jCombologin('hr_employee_t','employee_id','employee_number|first_name','');
      $this->data['statement']=0; 
          $this->data['linedata'] = $table;
     
        
         if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
        
         $employeebankdetails = \DB::table('hr_emp_salary')->select('hr_emp_salary.*')
                ->where('hr_emp_salary.employee_id',$value->emp_id)->get();
        
        $salaryaccount = \DB::table('f_hr_account_setting_t')->select('f_hr_account_setting_t.salary_account_id')->groupBy('salary_account_id')->get();

                $this->data['linedata'][$key]->employee_id = $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name',$value->emp_id);
        //$this->data['linedata'][$key]->account_code_id = $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments','');
       // $this->data['linedata'][$key]->account_code_id = $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name','');
    
              if(count($employeebankdetails)>0){
                if(isset($employeebankdetails[0]->bank_name)){
        $this->data['linedata'][$key]->bank_name =  $employeebankdetails[0]->bank_name;
                }else{
                  $this->data['linedata'][$key]->bank_name ="";
                }
                  if(isset($employeebankdetails[0]->account_holder_name)){
        $this->data['linedata'][$key]->account_holder_name =$employeebankdetails[0]->account_holder_name;
                  }else{
        $this->data['linedata'][$key]->account_holder_name ="";
                    
                  }
                    if(isset($employeebankdetails[0]->account_holder_name)){
        $this->data['linedata'][$key]->account_number =$employeebankdetails[0]->account_number;
                  }else{
        $this->data['linedata'][$key]->account_number ="";
                    
                  }
        if(isset($employeebankdetails[0]->ifsc_code)){
        $this->data['linedata'][$key]->ifsc_code =$employeebankdetails[0]->ifsc_code;
        }else{
          $this->data['linedata'][$key]->ifsc_code ="";
        }
      
                } else{
                  
        $this->data['linedata'][$key]->bank_name = "";
        $this->data['linedata'][$key]->account_holder_name ="";
        $this->data['linedata'][$key]->account_number ="";
        $this->data['linedata'][$key]->ifsc_code ="";
                }                 
            $this->data['linedata'][$key]->net_amt =$value->balance_amount;
           // dd($value->account_code_id);
        $this->data['linedata'][$key]->account_code_id = $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name','');
        }
        
        }
  
      return view('paymentforfandf.createpayforfandf',$this->data);
      } 
    
    // fandf final save
    
        public function fandfpaysave(Request $request){
			
			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
              \DB::beginTransaction();
    
                     try{
             foreach($_POST['bulk_employee_id'] as $k=>$v){


        if($_POST['payment_source']=="FANDFPAYMENT"){
        $ref_id=$_POST['bulk_reference_id'][$k];
        
       // dd($ref_id);
                      $check_pay=\DB::select("select * from  hr_ff_t  where hr_ff_id =".$ref_id);
                      \DB::update("UPDATE hr_ff_t set payment_status='1' where hr_ff_id=".$ref_id); 
                              
                     }
                  $seqno=$this->Seqnoe('PMT-','p_payments_t','','payment_count');
                 $linesdata['payment_number']= $seqno[0];
                 $linesdata['payment_count']=$seqno[1];
                 $linesdata['payment_date']=$data['payment_date'];
                 $linesdata['sender_information']=$data['sender_information'];
                 $linesdata['payment_type_id']=$data['payment_type_id'];
                 $linesdata['payment_reference']=$data['payment_reference'];
                 $linesdata['bank_id']=$data['bank_id'];
                 $linesdata['account_no']=$data['account_no'];
                 $linesdata['account_code_id']=$_POST['account_code_id'];
                 $linesdata['cheque_no']=$data['cheque_no'];
                 $linesdata['payment_source']=$data['payment_source'];
                 $linesdata['payment_status']=$data['payment_status'];
                 $linesdata['remarks']=$_POST['remarks'];
                 $linesdata['created_by']=$data['created_by'];
                 $linesdata['created_at']=date('Y-m-d H:i:s');
                 $linesdata['last_updated_by']=$data['last_updated_by'];
                 $linesdata['updated_at']=date('Y-m-d H:i:s');
                 $linesdata['company_id']=\Session::get('companyid');
                 $linesdata['location_id']=\Session::get('location');
                 $linesdata['employee_id']= $_POST['bulk_employee_id'][$k]; 
                 $linesdata['payment_amount']= $_POST['bulk_payment_amount'][$k]; 
                 $linesdata['remarks']= $_POST['remarks']." ".$_POST['bulk_remarks'][$k]; 
          
        //if($_POST['payment_source']=="BONUSPAYMENT"){
         $linesdata['supplier_bank_id']= $_POST['bulk_supplier_bank_id'][$k]; 
                 $linesdata['supplier_account_name']= $_POST['bulk_supplier_account_name'][$k]; 
                 $linesdata['supplier_account_no']= $_POST['bulk_supplier_account_no'][$k];
         $linesdata['supplier_ifsc_code']= $_POST['bulk_supplier_ifsc_code'][$k];  
          //}
           
           
           //update payment id in bonus table
            $bonus_pay=\DB::select("select * from  p_payments_t order by payment_id  desc limit 1");
            $pay_id = $bonus_pay[0]->payment_id;
         \DB::update("UPDATE hr_ff_t set payment_id=$pay_id where hr_ff_id =".$ref_id); 
             DB::table('p_payments_t')->insert($linesdata);
             $id = DB::getPdo()->lastInsertId();
       
      if($_POST['payment_source']=="FANDFPAYMENT"){
                $paymt_no="FANDFPAYMENT-". $linesdata['payment_number'];
                $pay_name="FANDFPAYMENT";
          }
          else{
             $paymt_no="IMPRESTPAYMENT-". $linesdata['payment_number'];
             $pay_name="IMPRESTPAYMENT";
          }
                $paydate=$_POST['payment_date'];
                //   dd($paydate);
                   $org=\Session::get('organization');
                   $loc=\Session::get('location');
                   $compy=\Session::get('companyid');
                   //Journal Header Insert
                   $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$paymt_no','$pay_name','$paydate','$id','APPROVED','$compy','$loc','$org')");
                   $jid = DB::getPdo()->lastInsertId();

                $account_id_data=\DB::select("select * from f_account_setting_t where module_name='hrms'");
                        //Journal lINES Insert       


            $tkey=0;

            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['payment_date'];
            $journal_lines_data[$tkey]['reference_source']="EMPLOYEE";
           
                 $journal_lines_data[$tkey]['reference_id']= $_POST['bulk_employee_id'][$k];
            
      if($_POST['payment_source']!="FANDFPAYMENT"){
       $journal_lines_data[$tkey]['account_id']=$account_id_data[0]->imprest_account_id; 
       }
        else{
     $journal_lines_data[$tkey]['account_id']=$_POST['bulk_account_code_id'][$k];
      }
           
            
            $journal_lines_data[$tkey]['debit_amount']= $linesdata['payment_amount'];
            $journal_lines_data[$tkey]['credit_amount']='';
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');   
             $tkey++;
            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['payment_date'];
            if($_POST['payment_type_id']=='IMPREST'){
            $journal_lines_data[$tkey]['reference_source']="EMPLOYEE";
            }else{
            $journal_lines_data[$tkey]['reference_source']="";    
            }
                $journal_lines_data[$tkey]['reference_id']= $_POST['bulk_employee_id'][$k];
            
            $journal_lines_data[$tkey]['account_id']=$_POST['account_code_id'];
            
            $journal_lines_data[$tkey]['debit_amount']='';
            $journal_lines_data[$tkey]['credit_amount']= $linesdata['payment_amount'];
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');                           
            
                 
           \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);  
       

            }   
      
      
            $paymenttype=$_POST['payment_type_id'];
            if($paymenttype =='CHEQUE'){
                   //Cheque no count update
                    $chequeno=$_POST['cheque_no'];
          if($chequeno!=''){
                    $accno=$_POST['account_no'];

                    $chequeupdate=\DB::update("UPDATE f_bank_cheque_lines_t set cheque_count='$chequeno' where bank_cheque_hdr_id='$accno'");
                    }
                    }
               
        \DB::commit();
        return response()->json(array('status' => 'success', 'message' => 'Payment Saved','id' => $id));
      }
      catch (\Illuminate\Database\QueryException$e){
        $message = explode('(', $e->getMessage());
        $dbCode = rtrim($message[0], ']');
        $dbCode = trim($dbCode, '[');
        \DB::rollback();
        return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
      }
      
      
        }
    
	
    }