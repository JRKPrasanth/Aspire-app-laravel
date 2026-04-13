<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Config;
use App\Paymentforinvoice;
class PaymentforelencashmentController extends Controller   // Employee EL payment - VIGNESH M 
{


    public $module="paymentforinvoice";

  public function __construct()
  {
    $this->data=array();
    $this->data=array();
    $this->table="p_payments_t";
 //   $this->pageModule="paymentforinvoice";
 //   $this->model=new Paymentforinvoice();
 //   $this->submodel = new Paymentforinvoice;
 //   $this->model=new Paymentforinvoice;
    $this->data['pageMethod']=\Request::route()->getName();
    //dd($this->data['pageMethod']);
    $this->data['pageFormtype']='ajax';
            //    $this->table="p_payments_t";
                $this->data['urlmenu']=$this->indexs(); 

  }
  
    public function paymentindex(Request $request)
    
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

       $this->data['sum_inv_tot'] = \DB::select("SELECT ROUND(SUM(total_amt),0) as total from hr_elencashment_t where payment_status='2'");
       $this->data['department_id']=$this->jcombo('m_department_lines_t','department_line_id','sub_department_name','');
       
       return view('elencashment.paymentforelcash',$this->data);
       
    }
    
    // payment for bonus
    public function elencashpayment()
    
    {

	  $wh='';
        
      $search_tables=["hr_elencashment_t"];
       if($_GET['_search']=='true'){
         $wh=$this->jqgridsearch("hr_elencashment_t",$_GET['filters'],$search_tables);
       }
       
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
       
	if(!$sidx) $sidx =1;
        
	$result = \DB::select("SELECT COUNT(id) AS count FROM  hr_elencashment_t as hr_elencashment_t where 1=1 $wh");
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

        
	$SQL = "SELECT hr_elencashment_t.*,m_department_lines_t.sub_department_name FROM hr_elencashment_t LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_elencashment_t.department WHERE 1=1  and status='APPROVED' and payment_status=2 $wh ORDER BY $sidx $sord LIMIT $start,$limit";
        
       
	$result = \DB::select($SQL);
	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

      
	echo json_encode($responce);
    
    }
    
    // elencash payment request
    
     public function empelencashrequest(Request $request){
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

        $this->data['sum_inv_tot'] = \DB::select("SELECT ROUND(SUM(total_amt),0) as total from hr_elencashment_t where status='APPROVED' and payment_status='0'");

         $table = \DB::table('f_emp_expenses_lines_t')->get();
         $this->data['datas'] = $table;
     
         $wh='';
            if($this->data['pageMethod']=="empexpensepaymentrequestapproval"){
                $wh.=' and f_emp_expenses_lines_t.payment_request_status=1';
                $wh.=$grid_data=$this->grid_statuscheck('f_emp_expenses_lines_t','bill_date','payment_request_status','=',1);
            }else{
                $wh.=' and f_emp_expenses_lines_t.payment_request_status=0'; 
                $wh.=$grid_data=$this->grid_statuscheck('f_emp_expenses_lines_t','bill_date','payment_request_status','=',0);
                
            }

                $loc=\Session::get('location');
                $compy=\Session::get('companyid');
     
                $this->data['sum_exp_tot'] = \DB::select("select * from(SELECT 
                    ROUND(SUM(f_emp_expenses_lines_t.balance_amounts),2) as sum_bal_total,
                    ROUND(SUM(f_emp_expenses_lines_t.expense_line_amount),2) as sum_exp_total
                    FROM f_emp_expenses_lines_t
                    left join f_emp_expenses_t on f_emp_expenses_t.expense_id = f_emp_expenses_lines_t.expense_id
                    left join hr_employee_t on hr_employee_t.employee_id=f_emp_expenses_lines_t.employee_id
                    left join tb_users on (tb_users.id =f_emp_expenses_t.created_by)
                    where 1=1 and f_emp_expenses_t.expense_status='APPROVED' and f_emp_expenses_lines_t.payment_status='0' $wh )as v1 where 1=1 ");
     
                    return view('elencashment.paymentrequest',$this->data);
    }
    
    // payment request grid data
    
     public function requesteldata(){
         
       // $wh1='';
        $wh='';
        
        if($_GET['_search']=='true'){
           
      //  $wh1.=$this->jqgridsearchnotab('v1',$_GET['filters']);

        }
              $loc=\Session::get('location');
              $compy=\Session::get('companyid');

              $groupname=\Session::get('groupname');
   		$search_tables=["hr_elencashment_t"];
       if($_GET['_search']=='true'){
         $wh =$this->jqgridsearch("hr_elencashment_t",$_GET['filters'],$search_tables);
       }

    if(isset($_GET['pagemethod'])){
        if($_GET['pagemethod']=="paymentrequestapproveelencash"){
            $wh.=' AND hr_elencashment_t.status="APPROVED" AND payment_status=1';
         
        }else{
            
          $wh.=' AND hr_elencashment_t.status="APPROVED" AND payment_status=0';
            
        }
     }

      
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
       
	if(!$sidx) $sidx =1;
        
	$result = \DB::select("SELECT COUNT(id) AS count FROM  hr_elencashment_t as hr_elencashment_t where 1=1 $wh");
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

        
	$SQL = "SELECT hr_elencashment_t.*,m_department_lines_t.sub_department_name FROM hr_elencashment_t LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_elencashment_t.department where 1=1 $wh ORDER BY $sidx $sord LIMIT $start,$limit";
        
        

       
	$result = \DB::select($SQL);
	 // dd($result);
	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
    //dd($responce);
      
	echo json_encode($responce);

	        
    }
    
 // payment request
 
      /*Employee Expense Payment Request Status Update*/
       public function getelpaymentreq($id = null){
           
          if($id!="" ){
              
            $journal=\DB::update("update hr_elencashment_t set payment_status='1' WHERE id IN ($id)");

            return response()->json(array('status' => 'success', 'message' => "Payment Requested Successfully",'id' => $id));
          }
        }  
    
    
    
    // request approve
           public function elencashreqapprove(Request $request,$id = null){
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

           
          if($id!="" ){
              
            $journal=\DB::update("update hr_elencashment_t set payment_status='2' WHERE id IN ($id)");

            return response()->json(array('status' => 'success', 'message' => "Payment Request Approved Successfully",'id' => $id));
          }
        }  
    
    
    
    // create payment
    
    public function paymentforelencashcreate($payslipid=null){
        
  
       $this->data['pageModule']="paymentforemployee";
       $this->data['pageUrl']=url('paymentforemployee');
       $table = \DB::table('hr_elencashment_t')->select('employee_id','total_amt','account_code_id','id')->whereIn('employee_id', explode(',',$payslipid))->get();
       
      $this->data['row']= (object) array();
      $this->data['row']->payment_id = "";
      $this->data['row']->payment_number = "";
      $this->data['row']->payment_date=date('Y-m-d');
      $this->data['row']->cheque_date=date('Y-m-d');
      $payamt=$this->data['row']->payment_status="";
      $this->data['row']->payment_amount="";
      $this->data['row']->payment_source="ELENCASHMENT";
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
                ->where('hr_emp_salary.employee_id',$value->employee_id)->get();
        
        $salaryaccount = \DB::table('f_hr_account_setting_t')->select('f_hr_account_setting_t.salary_account_id')->groupBy('salary_account_id')->get();

                $this->data['linedata'][$key]->employee_id = $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name',$value->employee_id);
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
            $this->data['linedata'][$key]->net_amt =$value->total_amt;
           // dd($value->account_code_id);
        $this->data['linedata'][$key]->account_code_id = $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name',$value->account_code_id);
        }
        
        }
  
      return view('elencashment.payforemployeelencashform',$this->data);
      } 
    
    // bonus final save
    
        public function elpaysave(Request $request){
 
               $id='';
               $data = $this->validatePost($request->all(),$this->table,'header');
              \DB::beginTransaction();
    
                     try{
             foreach($_POST['bulk_employee_id'] as $k=>$v){


        if($_POST['payment_source']=="ELENCASHMENT"){
        $ref_id=$_POST['bulk_reference_id'][$k];
        
       // dd($ref_id);
                      $check_pay=\DB::select("select * from  hr_elencashment_t  where id =".$ref_id);
                      \DB::update("UPDATE hr_elencashment_t set payment_status='3' where id=".$ref_id); 
                              
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
                 $linesdata['account_code_id']=$data['account_code_id'];
                 $linesdata['cheque_no']=$data['cheque_no'];
                 $linesdata['payment_source']=$data['payment_source'];
                 $linesdata['payment_status']=$data['payment_status'];
                 $linesdata['remarks']=$data['remarks'];
                 $linesdata['created_by']=$data['created_by'];
                 $linesdata['created_at']=date('Y-m-d H:i:s');
                 $linesdata['last_updated_by']=$data['last_updated_by'];
                 $linesdata['updated_at']=date('Y-m-d H:i:s');
                 $linesdata['company_id']=\Session::get('companyid');
                 $linesdata['location_id']=\Session::get('location');
                 $linesdata['employee_id']= $_POST['bulk_employee_id'][$k]; 
                 $linesdata['payment_amount']= $_POST['bulk_payment_amount'][$k]; 
                 //$linesdata['account_code_id']= $_POST['bulk_account_code_id'][$k];
                 //$linesdata['remarks']= $data['remarks']." ".$_POST['bulk_supplier_account_name'][$k]." ".$_POST['bulk_remarks'][$k]; 
                 $linesdata['remarks']= $data['remarks']." ".$_POST['bulk_remarks'][$k]; 
          
        //if($_POST['payment_source']=="BONUSPAYMENT"){
         $linesdata['supplier_bank_id']= $_POST['bulk_supplier_bank_id'][$k]; 
                 $linesdata['supplier_account_name']= $_POST['bulk_supplier_account_name'][$k]; 
                 $linesdata['supplier_account_no']= $_POST['bulk_supplier_account_no'][$k];
         $linesdata['supplier_ifsc_code']= $_POST['bulk_supplier_ifsc_code'][$k];  
          //}
           
           
           //update payment id in bonus table
            $bonus_pay=\DB::select("select * from  p_payments_t order by payment_id  desc limit 1");
            $pay_id = $bonus_pay[0]->payment_id;
         \DB::update("UPDATE hr_elencashment_t set payment_id=$pay_id where id=".$ref_id); 
             DB::table('p_payments_t')->insert($linesdata);
             $id = DB::getPdo()->lastInsertId();
       
      if($_POST['payment_source']=="ELENCASHMENT"){
                $paymt_no="ELENCASHMENT-". $linesdata['payment_number'];
        $pay_name="ELENCASHMENT";
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
            
      if($_POST['payment_source']!="ELENCASHMENT"){
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
        //dd($dbCode);
        \DB::rollback();
        return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
      }
   }
    
}