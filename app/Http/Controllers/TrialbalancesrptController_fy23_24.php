<?php

namespace App\Http\Controllers;

use App\Trialbalancesrpt;
use Illuminate\Http\Request;

class TrialbalancesrptController extends Controller
{
   public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }
    public function index()
    {
		$this->data['pageMethod']='customerbalancesrpt'; 
		
		 $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        
               $SQL = "SELECT 
                s_invoice_hdr_t.invoice_number,
                s_invoice_hdr_t.invoice_hdr_id,
				m_customers_t.customer_id as customer_id,
                m_customers_t.customer_name as ship_to_customer_id,
                s_invoice_hdr_t.invoice_date,
                SUM(ROUND(s_invoice_hdr_t.invoice_grand_total,2)) as invoice_grand_total,
                SUM(ROUND(s_invoice_hdr_t.paid_amount,2))as paid_amount,
                SUM(ROUND(s_invoice_hdr_t.balance_amount,2))as balance_amount,
                SUM(s_invoice_hdr_t.balance_amount) as total_balance,
                IF(s_invoice_hdr_t.paid_amount <= 0,'UnPaid','Partital Paid') AS paid_status
                FROM s_invoice_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) 
                where 1=1  and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.company_id=$compy and s_invoice_hdr_t.location_id=$loc GROUP BY s_invoice_hdr_t.ship_to_customer_id ";
               
		$result = \DB::select( $SQL );
		$this->data['result']=json_encode($result);
		
		
      	
    }

   
	
	public function accounttransaction(){
                $this->data['result']=[];
	   	return view('trailbalance.accounttrxrpt',$this->data);	
	}
	public function getaccounttransaction(){
            	$start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? 	date("Y-m-d", strtotime($_GET['start_date'])) : '';
		$end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
		$cash =  isset($_GET['cash']) && !empty($_GET['cash']) ? '1' : $_GET['cash'];
// 		$bank =  isset($_GET['bank']) && !empty($_GET['bank']) ? '1' : $_GET['bank'];
		
// 		dd($cash,$bank);
		
		$acc_id1 = $acc_id = -1;
		
		if($cash==1){
		    $ac_set = \DB::select("select cash_account_id from f_account_setting_t where f_account_setting_t.module_name='cashaccount'");
		    $acc_id =  $ac_set[0]->cash_account_id;
		}
		
// 		if($bank==1){
// 		    $ac_set = \DB::select("select group_concat(account_code_id) as acc_id from f_bank_account_lines_t");
		    
// 		    if(count($ac_set)>0 && $ac_set[0]->acc_id!=null){
// 		        $acc_id1 = $ac_set[0]->acc_id;
// 		    }
		    
// 		}
		
		$account_code_id = $acc_id.','.$acc_id1;
// 		dd($account_code_id);
// 		$journal = \DB::select("select group_concat(journal_entry_id) as journal_entry_id from f_journal_entry_lines_t where f_journal_entry_lines_t.account_id in ($account_code_id) and  f_journal_entry_lines_t.journal_date between '$start_date' and  '$end_date'");
// // 		dd($journal);
// 		$jid = 0;
// 		if(count($journal)>0 && $journal[0]->journal_entry_id!=null){
// 		    $jid = $journal[0]->journal_entry_id;
// 		}
		
		$wh1="";
		if(isset($_GET['pq_filter'])){
            $data=json_decode($_GET['pq_filter']);
            $data=$data->data;
            $wh1.=$this->pqgridsearchsum('v1',$data);
        }
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;
            
                // $sql=\DB::select("select * from (select (select f_account_structure_t.concatenated_segments from f_account_structure_t where f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id)as ac_name,
                //                             f_journal_entry_t.journal_name,
                //                             f_journal_entry_t.journal_type,
                //                             f_journal_entry_lines_t.journal_entry_id,
                //                             if(f_journal_entry_lines_t.debit_amount=0,'',f_journal_entry_lines_t.debit_amount) as debit_amount,
                //                             if(f_journal_entry_lines_t.credit_amount=0,'',f_journal_entry_lines_t.credit_amount) as credit_amount,
                //                             f_journal_entry_lines_t.journal_date,
                //                             f_journal_entry_lines_t.`f_journal_entry_line_id`, 
                //                             f_journal_entry_lines_t.reference_source, 
                //                             (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                //                                   WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                //                                   WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                //                                   WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                //                                   WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                //                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                //                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                //                                   else '' end ) as name   
                //                                   from f_journal_entry_lines_t left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id 
                //                                   left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id 
                //                                   left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id 
                //                                   left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id 
                //                                   left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id 
                //                                   left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id 
                //                                   JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id 
                //                                   where f_journal_entry_lines_t.journal_date between '$start_date' and  '$end_date' and f_journal_entry_t.journal_status='APPROVED' and f_journal_entry_lines_t.journal_entry_id not in ($jid) order by f_journal_entry_t.journal_entry_id desc)v1 where 1=1 $wh1");
                                                  
    //   dd($account_code_id);
 $ledger_data=\DB::select("select f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`, f_journal_entry_lines_t.reference_source, 
                                    (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                                       WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                                       WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                                       WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                                       WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                                       WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                                       WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                                    else '' end) as name,
                                    (case when journal_type like '%PAYMENT%' then (select pmt.remarks from p_payments_t pmt where pmt.payment_id = journal_reference) 
  	                                    WHEN journal_type like '%EXPENSE%' then (select exp.remarks from f_expenses_t exp where exp.expense_id = journal_reference)
                                    ELSE '' END) as narration
                                    from f_journal_entry_lines_t 
                                    left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id 
                                    left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id 
                                    left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id 
                                    left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id 
                                    left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id 
                                    left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id 
                                    JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id 
                                    where f_journal_entry_lines_t.account_id in ($account_code_id)  and f_journal_entry_lines_t.journal_date between '$start_date' and  '$end_date' 
                                    order by f_journal_entry_lines_t.journal_date asc");
// dd($ledger_data);
        $balance= \DB::select("select sum(f.debit-f.credit)as balance from( select COALESCE(SUM(debit_amount),0) as debit,COALESCE(SUM(credit_amount),0)as credit from f_journal_entry_lines_t where  f_journal_entry_lines_t.account_id in ($account_code_id) and f_journal_entry_lines_t.journal_date>='2021-08-31' and f_journal_entry_lines_t.journal_date < '$start_date')f");
    // dd($balance);
                     $compy=\Session::get('companyid');

        $balance=round($balance[0]->balance,2);
          
          
          $overall_datas[0] = (object)array();
  $cop=0;       
$dop=0;         
          
        if($balance>0)
        {         
          $overall_datas[0]->balance=$balance;
  //$balance=$balance+$v->debit_amount;
  $dop=$balance;
  $overall_datas[0]->debit_amounts=$balance;
  $overall_datas[0]->credit_amounts=0;
  $overall_datas[0]->net_salary=0;
  $overall_datas[0]->journal_date=$start_date;
  $overall_datas[0]->journal_type="Opening Balance";
  $overall_datas[0]->journal_name="Opening Balance";
  $overall_datas[0]->concatenated_segments='';

  $overall_datas[0]->reference_source='';
  $overall_datas[0]->reference_name='';
        }
        else
        {
        $overall_datas[0]->balance=$balance;
  //$balance=$balance+$v->debit_amount;
  $cop=$balance;
  $overall_datas[0]->debit_amounts=0;
  $overall_datas[0]->credit_amounts=$balance*-1;
  $overall_datas[0]->net_salary=0;
  $overall_datas[0]->journal_date=$start_date;
  $overall_datas[0]->journal_type="Opening Balance";
  $overall_datas[0]->journal_name="Opening Balance";
  $overall_datas[0]->concatenated_segments='';

  $overall_datas[0]->reference_source='';
  $overall_datas[0]->reference_name='';
        }
    //   dd($overall_datas);   
    //$employee_data=\DB::select("select * from((select f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_t.journal_date,0 as net_salary FROM f_journal_entry_lines_t JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id where f_journal_entry_lines_t.reference_source='employee' and f_journal_entry_lines_t.reference_id='$employee_id' and f_journal_entry_lines_t.journal_date BETWEEN '$start_date' and '$end_date' group by f_journal_entry_t.journal_entry_id order by f_journal_entry_t.journal_date asc) union all (SELECT 0 as journal_entry_id,'Salary' as journal_name,'Salary' as journal_type,date as journal_date,net_salary FROM `hr_employee_payroll_lists` WHERE employee_id='$employee_id' and date BETWEEN '$start_date' and '$end_date'))v1 order by v1.journal_date asc ");
      
//  dd($cop);       
          //dd($employee_data);
          $key=1;
    //   dd($ledger_data);
foreach($ledger_data as $k=>$value)
{
    // dd($value->reference_source);
  //dd($value);
  if($value->reference_source!='')
  {
    $id=$value->journal_entry_id;
    $reference_name=$value->name;
    $reference_source=$value->reference_source;
    $debit_amount=$value->debit_amount;    
    $credit_amount=$value->credit_amount; 
//   dd($debit_amount);
    if($debit_amount > 0)
    {
      $d=\DB::select("SELECT account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount=0");
// dd($d);
    if(COUNT($d)==0){
      $acc_name='';  
     }else{
      $acc_name=$d[0]->account_name; 
}

    }  
    else
    {

     $d=\DB::select("SELECT account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");
     ///dd($d);
     if(COUNT($d)==0){
      $acc_name='';  
     }else{
      $acc_name=$d[0]->account_name; 
}
    }

  }
  else
  {
     $id=$value->journal_entry_id;
     $debit_amount=$value->debit_amount;    
    $credit_amount=$value->credit_amount; 
    if($debit_amount > 0)
    {
      $wh1=" and f_journal_entry_lines_t.debit_amount=0";
    }
    else
    {
      $wh1=" and f_journal_entry_lines_t.credit_amount=0";
    }
$d=\DB::select("select f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name ,f_journal_entry_lines_t.reference_source, 
                                            (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                                                   WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                                                   WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                                                   WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                                                   WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                                                  else '' end ) as name   from f_journal_entry_lines_t left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id where f_journal_entry_lines_t.journal_entry_id='$id' $wh1");
// dd($d);
$reference_name=$d[0]->name;
    $reference_source=$d[0]->reference_source;
$acc_name=$d[0]->account_name; 


  }

  $overall_datas[$key] = (object)array();

if($debit_amount > 0 )
{
  $overall_datas[$key]->concatenated_segments=$acc_name;  
  $overall_datas[$key]->balance=round($balance+$debit_amount,2);
  $dbal=$balance=$balance+$debit_amount;
  $overall_datas[$key]->debit_amounts=$debit_amount;
  $overall_datas[$key]->net_salary=0;
  $overall_datas[$key]->journal_date=$value->journal_date;
  $overall_datas[$key]->journal_type=$value->journal_type;
  $overall_datas[$key]->journal_name=$value->journal_name;
  $overall_datas[$key]->narration=$value->narration;
  $overall_datas[$key]->reference_source=$reference_source;
  $overall_datas[$key]->reference_name=$reference_name;

}
else
{
  $overall_datas[$key]->concatenated_segments=$acc_name;
  $overall_datas[$key]->balance=round($balance-$credit_amount,2);
  $cbal=$balance=$balance-$credit_amount; 
  $overall_datas[$key]->credit_amounts=$credit_amount;
  $overall_datas[$key]->net_salary=0;
  $overall_datas[$key]->journal_date=$value->journal_date;
  $overall_datas[$key]->journal_type=$value->journal_type;
  $overall_datas[$key]->journal_name=$value->journal_name;
  $overall_datas[$key]->narration=$value->narration;
   $overall_datas[$key]->reference_source=$reference_source;
  $overall_datas[$key]->reference_name=$reference_name;
}
$key++;


}


$count1=COUNT($overall_datas);
//dd($overall_datas[$count1-1]);
$csum = array_column($overall_datas, 'credit_amounts');

$csum1 = array_sum($csum)-$cop;
//dd($csum1);
$csum2 = array_sum($csum);
$dsum = array_column($overall_datas, 'debit_amounts');
$dsum1 = array_sum($dsum)-$dop;
$dsum2 = array_sum($dsum);
$tsum1 = $dsum1-$csum1;
$tsum2 = $dsum2-$csum2;
//$tsum1 = array_sum($tsum);

 $overall_datas[$count1]=(object)array();
 $overall_datas[$count1]->credit_amounts=money_format('%!n', round($csum2,3));
 $overall_datas[$count1]->debit_amounts=money_format('%!n', round($dsum2,3));
 $overall_datas[$count1]->balance=money_format('%!n', round($tsum2,3));
 $overall_datas[$count1]->concatenated_segments="Current Total";
 
//  $overall_datas[$count1+1]=(object)array();
//  $overall_datas[$count1+1]->credit_amounts=money_format('%!n', round($csum1,3));
//  $overall_datas[$count1+1]->debit_amounts=money_format('%!n', round($dsum1,3));
//  $overall_datas[$count1+1]->balance=money_format('%!n', round($tsum1,3));
//  $overall_datas[$count1+1]->concatenated_segments="Closing Total";
 
//  $overall_datas[$count1+1]=(object)array();
//  $overall_datas[$count1+1]->credit_amounts=money_format('%!n', round($csum2,3));
//  $overall_datas[$count1+1]->debit_amounts=money_format('%!n', round($dsum2,3));
//  $overall_datas[$count1+1]->balance=money_format('%!n', round($tsum2,3));
//  $overall_datas[$count1+1]->concatenated_segments="Current Total";



//dd($overall_datas[$count1]);
$ref_so=$overall_datas[1]->reference_source;
 
        $count = count($overall_datas);
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
        
        if(isset($_GET['print']))
    {

        $this->data['customer_dat']='';        
        $this->data['start_date'] = $start_date;
        $this->data['end_date'] = $end_date;
        
        $this->data['results'] =$overall_datas;
        $this->data['ddd'] ="Dssd";
        // dd($this->data);
        return view('trailbalance.accounttrxrptprint',$this->data) ;
    }
        
    if(isset($_GET['download']))
    { 
                 
        $result1=collect($overall_datas)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }
 

        $result = array_slice($overall_datas,$start,$limit);
      //  dd($result);
        $responce->rows[]='';
        $responce->data=$result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
        
//                 $this->data['start_date']=$start_date;
// 		$this->data['end_date']=$end_date;
// 		$this->data['result']=$sql;
                
// 	   	return view('trailbalance.accounttrxtable',$this->data);	
	}
	
	
    	public function accountbanktransaction(){
                $this->data['result']=[];
                $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t','bank_account_line_id','account_number','');
                          
	   	return view('trailbalance.accountbanktrxrpt',$this->data);	
	}
	
    public function getaccountbanktransaction()
    {
        $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? 	date("Y-m-d", strtotime($_GET['start_date'])) : '';
		$end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
        // 		$cash =  isset($_GET['cash']) && !empty($_GET['cash']) ? '1' : $_GET['cash'];
		$bank =  isset($_GET['bank']) && !empty($_GET['bank']) ? '1' : $_GET['bank'];
        // 		dd($cash,$bank);
        
        
       
		$acc_id1 = $acc_id = -1;
		
        // 		if($cash==1){
        // 		    $ac_set = \DB::select("select cash_account_id from f_account_setting_t where f_account_setting_t.module_name='cashaccount'");
        // 		    $acc_id =  $ac_set[0]->cash_account_id;
        // 		}
		
		if($bank==1)
		{
		    $ac_set = \DB::select("select group_concat(account_code_id) as acc_id from f_bank_account_lines_t");
		    if(count($ac_set)>0 && $ac_set[0]->acc_id!=null)
		    {
		        $acc_id1 = $ac_set[0]->acc_id;
		    }
		}
		
		$account_code_id = $acc_id.','.$acc_id1;
        //dd($account_code_id);
        // 		$journal = \DB::select("select group_concat(journal_entry_id) as journal_entry_id from f_journal_entry_lines_t where f_journal_entry_lines_t.account_id in ($account_code_id) and  f_journal_entry_lines_t.journal_date between '$start_date' and  '$end_date'");
        // // 		dd($journal);
        // 		$jid = 0;
        // 		if(count($journal)>0 && $journal[0]->journal_entry_id!=null){
        // 		    $jid = $journal[0]->journal_entry_id;
        // 		}
		
		$wh1="";
		if(isset($_GET['pq_filter']))
		{
            $data=json_decode($_GET['pq_filter']);
            $data=$data->data;
            $wh1.=$this->pqgridsearchsum('v1',$data);
        }
        
         $whh1='';
        // if(isset($_GET['account_no']) && !empty($_GET['account_no']))
        // {
        //     $account_no=$_GET['account_no'];
        //     $whh1=" and s_receipts_t.account_no=$account_no";
           
        // }
        
        
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;
            
            // $sql=\DB::select("select * from (select (select f_account_structure_t.concatenated_segments from f_account_structure_t where f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id)as ac_name,
            //                             f_journal_entry_t.journal_name,
            //                             f_journal_entry_t.journal_type,
            //                             f_journal_entry_lines_t.journal_entry_id,
            //                             if(f_journal_entry_lines_t.debit_amount=0,'',f_journal_entry_lines_t.debit_amount) as debit_amount,
            //                             if(f_journal_entry_lines_t.credit_amount=0,'',f_journal_entry_lines_t.credit_amount) as credit_amount,
            //                             f_journal_entry_lines_t.journal_date,
            //                             f_journal_entry_lines_t.`f_journal_entry_line_id`, 
            //                             f_journal_entry_lines_t.reference_source, 
            //                             (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
            //                                   WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
            //                                   WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
            //                                   WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
            //                                   WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
            //                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
            //                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
            //                                   else '' end ) as name   
            //                                   from f_journal_entry_lines_t left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id 
            //                                   left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id 
            //                                   left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id 
            //                                   left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id 
            //                                   left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id 
            //                                   left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id 
            //                                   JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id 
            //                                   where f_journal_entry_lines_t.journal_date between '$start_date' and  '$end_date' and f_journal_entry_t.journal_status='APPROVED' and f_journal_entry_lines_t.journal_entry_id not in ($jid) order by f_journal_entry_t.journal_entry_id desc)v1 where 1=1 $wh1");
                //   dd($account_code_id)   ;   
                
                // $sql="select * from(select f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_bank_account_lines_t.account_number,f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`, f_journal_entry_lines_t.reference_source, (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                //                           WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                //                           WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                //                           WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                //                           WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                //                           WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                //                           WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                //                           else '' end ) as name,
                //                           (case when journal_type like '%PAYMENT%' then (select pmt.remarks from p_payments_t pmt where pmt.payment_id = journal_reference) 
  		            //                         WHEN journal_type like '%EXPENSE%' then (select exp.remarks from f_expenses_t exp where exp.expense_id = journal_reference)
                //                             ELSE '' END) as narration
                //                         from f_journal_entry_lines_t 
                //                         left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id 
                //                         left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id 
                //                         left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id 
                //                         left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id 
                //                         left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id 
                //                         left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id 
                //                         left join f_bank_account_lines_t ON f_bank_account_lines_t.account_code_id=f_journal_entry_lines_t.account_id 
                //                         JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id 
                                        
                //                         where f_journal_entry_lines_t.account_id in ($account_code_id) and f_journal_entry_lines_t.journal_date between '$start_date' and '$end_date' $whh1 order by f_journal_entry_lines_t.journal_date asc)v1 where 1=1 $wh1";
                
                // echo $sql;die;
      
            $ledger_data=\DB::select("select * from(select f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_bank_account_lines_t.account_number,f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`, f_journal_entry_lines_t.reference_source, (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                                           WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                                           WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                                           WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                                           WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                                           WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                                           WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                                          else '' end ) as name,
                                          (case when journal_type like '%PAYMENT%' then (select pmt.remarks from p_payments_t pmt where pmt.payment_id = journal_reference) 
  		                                    WHEN journal_type like '%EXPENSE%' then (select exp.remarks from f_expenses_t exp where exp.expense_id = journal_reference)
                                            ELSE '' END) as narration
                                        from f_journal_entry_lines_t 
                                        left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id 
                                        left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id 
                                        left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id 
                                        left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id 
                                        left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id 
                                        left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id 
                                        left join f_bank_account_lines_t ON f_bank_account_lines_t.account_code_id=f_journal_entry_lines_t.account_id 
                                        JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id 
                                        
                                        where f_journal_entry_lines_t.account_id in ($account_code_id) and f_journal_entry_lines_t.journal_date between '$start_date' and '$end_date' $whh1 order by f_journal_entry_lines_t.journal_date asc)v1 where 1=1 $wh1");
         
            // dd($ledger_data);
            $balance= \DB::select("select sum(f.debit-f.credit)as balance from( select COALESCE(SUM(debit_amount),0) as debit,COALESCE(SUM(credit_amount),0)as credit from f_journal_entry_lines_t where  f_journal_entry_lines_t.account_id in ($account_code_id) and f_journal_entry_lines_t.journal_date>='2021-08-31' and f_journal_entry_lines_t.journal_date < '$start_date')f");
            //dd($balance);
            $compy=\Session::get('companyid');
            $balance=round($balance[0]->balance,2);
            $overall_datas[0] = (object)array();
            $cop=0;       
            $dop=0;         
          
            if($balance>0)
            {         
                $overall_datas[0]->balance=$balance;
                //$balance=$balance+$v->debit_amount;
                $dop=$balance;
                $overall_datas[0]->debit_amounts=$balance;
                $overall_datas[0]->credit_amounts=0;
                $overall_datas[0]->net_salary=0;
                $overall_datas[0]->journal_date=$start_date;
                $overall_datas[0]->journal_type="Opening Balance";
                $overall_datas[0]->journal_name="Opening Balance";
                $overall_datas[0]->concatenated_segments='';
                
                $overall_datas[0]->reference_source='';
                $overall_datas[0]->reference_name='';
            }
            else
            {
                $overall_datas[0]->balance=$balance;
                //$balance=$balance+$v->debit_amount;
                $cop=$balance;
                $overall_datas[0]->debit_amounts=0;
                $overall_datas[0]->credit_amounts=$balance*-1;
                $overall_datas[0]->net_salary=0;
                $overall_datas[0]->journal_date=$start_date;
                $overall_datas[0]->journal_type="Opening Balance";
                $overall_datas[0]->journal_name="Opening Balance";
                $overall_datas[0]->concatenated_segments='';
                
                $overall_datas[0]->reference_source='';
                $overall_datas[0]->reference_name='';
            }
            //dd($overall_datas);   
            //$employee_data=\DB::select("select * from((select f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_t.journal_date,0 as net_salary FROM f_journal_entry_lines_t JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id where f_journal_entry_lines_t.reference_source='employee' and f_journal_entry_lines_t.reference_id='$employee_id' and f_journal_entry_lines_t.journal_date BETWEEN '$start_date' and '$end_date' group by f_journal_entry_t.journal_entry_id order by f_journal_entry_t.journal_date asc) union all (SELECT 0 as journal_entry_id,'Salary' as journal_name,'Salary' as journal_type,date as journal_date,net_salary FROM `hr_employee_payroll_lists` WHERE employee_id='$employee_id' and date BETWEEN '$start_date' and '$end_date'))v1 order by v1.journal_date asc ");
            //  dd($cop);       
            //dd($employee_data);
            $key=1;
            //dd($ledger_data);
        
            foreach($ledger_data as $k=>$value)
            {
                // dd($value->reference_source);
                // dd($value);
                if($value->reference_source!='')
                {
                    $id=$value->journal_entry_id;
                    $reference_name=$value->name;
                    $reference_source=$value->reference_source;
                    $account_number=$value->account_number;
                    $debit_amount=$value->debit_amount;    
                    $credit_amount=$value->credit_amount; 
                    //   dd($debit_amount);
                    if($debit_amount > 0)
                    {
                        $d=\DB::select("SELECT account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount=0");
                        // dd($d);
                        if(COUNT($d)==0){
                            $acc_name='';  
                        }else{
                            $acc_name=$d[0]->account_name; 
                        }
                    
                    }  
                    else
                    {
                        $d=\DB::select("SELECT account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");
                        ///dd($d);
                        if(COUNT($d)==0){
                            $acc_name='';  
                        }else{
                            $acc_name=$d[0]->account_name; 
                        }
                    }
                
                }
                else
                {
                    $id=$value->journal_entry_id;
                    $account_number=$value->account_number;
                    $debit_amount=$value->debit_amount;    
                    $credit_amount=$value->credit_amount; 
                    if($debit_amount > 0)
                    {
                      $wh1=" and f_journal_entry_lines_t.debit_amount=0";
                    }
                    else
                    {
                      $wh1=" and f_journal_entry_lines_t.credit_amount=0";
                    }
                    $d=\DB::select("select f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name ,f_journal_entry_lines_t.reference_source, 
                            (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                                   WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                                   WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                                   WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                                   WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                                  else '' end ) as name   from f_journal_entry_lines_t 
                                  left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id 
                                  left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id 
                                  left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id 
                                  left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id 
                                  left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id 
                                  left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id 
                                  JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id 
                                  where f_journal_entry_lines_t.journal_entry_id='$id' $wh1");
                    // dd($d);
                    if(COUNT($d)>0)
                    {
                        $reference_name=$d[0]->name;
                        $reference_source=$d[0]->reference_source;
                        $acc_name=$d[0]->account_name; 
                    }
                }
                $overall_datas[$key] = (object)array();

                if($debit_amount > 0 )
                {
                  $overall_datas[$key]->concatenated_segments=$acc_name;  
                  $overall_datas[$key]->balance=round($balance+$debit_amount,2);
                  $dbal=$balance=$balance+$debit_amount;
                  $overall_datas[$key]->debit_amounts=$debit_amount;
                  $overall_datas[$key]->net_salary=0;
                  $overall_datas[$key]->journal_date=$value->journal_date;
                  $overall_datas[$key]->journal_type=$value->journal_type;
                  $overall_datas[$key]->journal_name=$value->journal_name;
                  $overall_datas[$key]->narration=$value->narration;
                  $overall_datas[$key]->account_number=$account_number;
                  $overall_datas[$key]->reference_source=$reference_source;
                  $overall_datas[$key]->reference_name=$reference_name;
                
                }
                else
                {
                  $overall_datas[$key]->concatenated_segments=$acc_name;
                  $overall_datas[$key]->balance=round($balance-$credit_amount,2);
                  $cbal=$balance=$balance-$credit_amount; 
                  $overall_datas[$key]->credit_amounts=$credit_amount;
                  $overall_datas[$key]->net_salary=0;
                  $overall_datas[$key]->journal_date=$value->journal_date;
                  $overall_datas[$key]->journal_type=$value->journal_type;
                  $overall_datas[$key]->journal_name=$value->journal_name;
                  $overall_datas[$key]->narration=$value->narration;
                  $overall_datas[$key]->account_number=$account_number;
                   $overall_datas[$key]->reference_source=$reference_source;
                  $overall_datas[$key]->reference_name=$reference_name;
                }
                $key++;
            }
            
            // dd($overall_datas);
            $count1=COUNT($overall_datas);
            //dd($overall_datas[$count1-1]);
            $csum = array_column($overall_datas, 'credit_amounts');
            
            $csum1 = array_sum($csum)-$cop;
            //dd($csum1);
            $csum2 = array_sum($csum);
            $dsum = array_column($overall_datas, 'debit_amounts');
            $dsum1 = array_sum($dsum)-$dop;
            $dsum2 = array_sum($dsum);
            $tsum1 = $dsum1-$csum1;
            $tsum2 = $dsum2-$csum2;
            //$tsum1 = array_sum($tsum);
            
            $overall_datas[$count1]=(object)array();
            $overall_datas[$count1]->credit_amounts=money_format('%!n', round($csum2,3));
            $overall_datas[$count1]->debit_amounts=money_format('%!n', round($dsum2,3));
            $overall_datas[$count1]->balance=money_format('%!n', round($tsum2,3));
            $overall_datas[$count1]->concatenated_segments="Current Total";
            
            // $overall_datas[$count1+1]=(object)array();
            // $overall_datas[$count1+1]->credit_amounts=money_format('%!n', round($csum1,3));
            // $overall_datas[$count1+1]->debit_amounts=money_format('%!n', round($dsum1,3));
            // $overall_datas[$count1+1]->balance=money_format('%!n', round($tsum1,3));
            // $overall_datas[$count1+1]->concatenated_segments="Closing Total";
            
            //  $overall_datas[$count1+1]=(object)array();
            //  $overall_datas[$count1+1]->credit_amounts=money_format('%!n', round($csum2,3));
            //  $overall_datas[$count1+1]->debit_amounts=money_format('%!n', round($dsum2,3));
            //  $overall_datas[$count1+1]->balance=money_format('%!n', round($tsum2,3));
            //  $overall_datas[$count1+1]->concatenated_segments="Current Total";
            
            
            
            //dd($overall_datas[$count1]);
            $ref_so=$overall_datas[0]->reference_source;
 
            $count = count($overall_datas);
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
        
            if(isset($_GET['print']))
            {
        
                $this->data['customer_dat']='';        
                $this->data['start_date'] = $start_date;
                $this->data['end_date'] = $end_date;
                
                $this->data['results'] =$overall_datas;
                $this->data['ddd'] ="Dssd";
               // dd($this->data);
                return view('trailbalance.accountbanktrxrptprint',$this->data) ;
            }
        
            if(isset($_GET['download']))
            { 
                         
                $result1=collect($overall_datas)->map(function($x){ return (array) $x; })->toArray();
                return $result1;
            }
 

            $result = array_slice($overall_datas,$start,$limit);
            //  dd($result);
            $responce->rows[]='';
            $responce->data=$result;
            $responce->curPage = $page;
            $responce->total = $total_pages;
            $responce->totalRecords = $count;
            echo json_encode($responce);
            
            //                 $this->data['start_date']=$start_date;
            // 		$this->data['end_date']=$end_date;
            // 		$this->data['result']=$sql;
                
            // 	   	return view('trailbalance.accounttrxtable',$this->data);	
	}
        public function getaccounttransaction_old(){
            	$start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? 	date("Y-m-d", strtotime($_GET['start_date'])) : '';
		$end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
             //   $sql=\DB::select("SELECT f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_lines_t.journal_date,f_journal_entry_t.journal_type,f_journal_entry_lines_t.account_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_account_structure_t.future_reference2, f_account_codes_lines_t.account_code,f_account_codes_lines_t.account_code_meaning,f_journal_entry_lines_t.f_journal_entry_line_id FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on (f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id) LEFT JOIN f_account_structure_t ON(f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id) LEFT JOIN f_account_codes_lines_t ON (f_account_codes_lines_t.account_codes_line_id=f_account_structure_t.future_reference2) where f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date' and f_journal_entry_t.journal_status='POSTED'");
                  $sql=\DB::select("SELECT f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_lines_t.journal_date,f_journal_entry_t.journal_type,f_journal_entry_lines_t.account_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_account_structure_t.concatenated_segments, f_account_codes_lines_t.account_code,f_account_codes_lines_t.account_code_meaning,f_journal_entry_lines_t.f_journal_entry_line_id FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on (f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id) LEFT JOIN f_account_structure_t ON(f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id) LEFT JOIN f_account_codes_lines_t ON (f_account_codes_lines_t.account_codes_line_id=f_account_structure_t.future_reference2) where f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'");
                $this->data['start_date']=$start_date;
		$this->data['end_date']=$end_date;
		$this->data['result']=$sql;
                
	   	return view('trailbalance.accounttrxtable',$this->data);	
	}
	

public function balancesheet(){
    
             $this->data['ship_date'] = \DB::select("SELECT f_journal_entry_t.journal_date,f_journal_entry_lines_t.created_at FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id WHERE f_journal_entry_t.journal_name LIKE '%SHIP-SO%' ORDER BY f_journal_entry_t.journal_entry_id DESC LIMIT 1");
              //  dd($this->data['ship_date']);
                
                $this->data['result']=[];
                
      return view('trailbalance.balancesheet',$this->data);  
  }

  public function getbalancesheetdata1(){


    


$start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?   date("Y-m-d", strtotime($_GET['start_date'])) : '';
$start_date = '2019-04-01';
        $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
//dd("dsd");
$profit=$this->getprofitandloss($start_date,$end_date);

        
$SQL ="select account_id,main_account_code,main,sub1,sub2,sub3,sub4,
        (Case
  when account_id='553' then amount+$profit
  else amount
  end) as amount,`main_account_id`,
  `sub_account_id`,
  `future_reference1`,
  `future_reference2`,
  `sub_account4_id` 
 from ((select v1.account_id,v1.main_account_code,v1.main,v1.sub1,v1.sub2,v1.sub3,v1.sub4,round(sum(v1.amount),2)  as amount,v1.`main_account_id`,
 v1.`sub_account_id`,
 v1.`future_reference1`,
 v1.`future_reference2`,
 v1.`sub_account4_id` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount,
        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=100000 or m.main_account_code=200000 or m.main_account_code=300000  union all (SELECT 
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.credit_amount- f_journal_entry_lines_t.debit_amount
                ),
                2
            )  AS amount,
'' as `main_account_id`,
        '' as `sub_account_id`,
        '' as `future_reference1`,
        '' as `future_reference2`,
        '' as `sub_account4_id`           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=100000 or f_account_class_t.main_account_code=200000 or f_account_class_t.main_account_code=300000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v1 group by v1.account_id ORDER by v1.main_account_code asc) union all (select v2.account_id,v2.main_account_code,v2.main,v2.sub1,v2.sub2,v2.sub3,v2.sub4,round(sum(v2.amount),2)  as amount, v2.`main_account_id`,
            v2.`sub_account_id`,
            v2.`future_reference1`,
            v2.`future_reference2`,
            v2.`sub_account4_id` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount,

        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`

    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=400000 or m.main_account_code=500000 or m.main_account_code=800000  union all (SELECT
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.debit_amount- f_journal_entry_lines_t.credit_amount
                ),
                2
            )  AS amount,'' as `main_account_id`,
            '' as `sub_account_id`,
            '' as `future_reference1`,
            '' as `future_reference2`,
            '' as `sub_account4_id` 
           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=400000 or f_account_class_t.main_account_code=500000 or f_account_class_t.main_account_code=800000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v2 group by v2.account_id ORDER by v2.main_account_code asc) )v3 order by v3.main_account_code asc";

        $result = \DB::select( $SQL );


 if(isset($_GET['download']))
    {
         $result1=collect($result)->map(function($x){ return (array) $x; })->toArray();
            $data=''; 
  
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=data.xls');
 $output = fopen('balancesheet.xls', 'w');     

$rows=array();
$rows="Account Class\t";
$rows.="Account Type\t";
$rows.="Sub1\t";
$rows.="Sub2\t";
$rows.="Sub3\t";
$rows.="Sub4\t";
$rows.="Amount(in Rs.)\n";

 //fputcsv($output, $rows); 
 
      foreach($result1 as  $row)
{
   // $rows=array();
    $rows.=$row['main_account_code']."\t";
    $rows.=$row['main']."\t";
    $rows.=$row['sub1']."\t";
    $rows.=$row['sub2']."\t";
    $rows.=$row['sub3']."\t";
    $rows.=$row['sub4']."\t";
    $rows.=$row['amount']."\n";
  
    //fputcsv($output, $rows); 
   
}
fwrite($output,$rows);
 
return 1;
    }
    
$result=collect($result);
$data=\DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (11,12,2) order by main_account_code asc");

setlocale(LC_MONETARY, 'en_IN');
$total=0;
error_reporting(0);
$html='<table class="table" style="    width: 100%;"><thead><th>Particulars</th><th>Amount in (Rs.)</th><th>Amount in (Rs.)</th></thead><tbody>';
$html.="<tr class='heading'><td><b>EQUITY & LIABILITIES</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";
foreach($data as $k=>$val)
{


$id=$val->account_class_id;
$name0=ucwords(strtolower($val->account_class_name));

$filter=$result->where('main_account_id',$id);
$filter->all();
$filter0=collect($filter);
$filter=collect($filter)->map(function($x){ return (array) $x; })->toArray();

$arr_new= round(array_sum(array_column($filter, 'amount')),2);
if($arr_new==0)
    $arr_new='-';

$html.="<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

$sub1=\DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");


//dd($sub1);

foreach($sub1 as $val1)
{
    $p_id=$id=$val1->account_codes_line_id;
    $name1=ucwords(strtolower($val1->account_code_meaning)); 
    $code1= $val1->account_code;
    $filter1=$filter0->where('sub_account_id',$id);
    $filter1->all();
    $filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new1 = round(array_sum(array_column($filter1, 'amount')),2);

    if($arr_new1==0)
    $arr_new1='-';
    
        $html.="<tr><td class='parent' data='$p_id' col='0'>$code1  $name1</td><td style='
    text-align: right;
'></td><td style='
text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1,2))."</td></tr>"; 

//Sub2 
$sub2=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

 if(count($sub2)>0)
 {
//dd($filter11);
foreach($sub2 as $val2)
{
    $id=$subid=$val2->account_codes_line_id;
    $name2=ucwords(strtolower($val2->account_code_meaning)); 
    $code2= $val2->account_code;
    $filter1=$filter11->where('future_reference1',$id);
    $filter1->all();
    //$filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new2 = round(array_sum(array_column($filter1, 'amount')),2);
    if($arr_new2==0)
    $arr_new2='-';
    
    
    
    $html.="<tr class='child child$p_id'><td  class='parent' data='$id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2,2))."</td><td></td></tr>";




 $sub3=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
 if(count($sub3)>0)
 {
 foreach($sub3 as $val3)
 {
//   // dd($val3);
   $id= $sub3id=$val3->account_codes_line_id;
      $name3=ucwords(strtolower($val3->account_code_meaning)); 
     $code3= $val3->account_code;
     $subfilter1=$filter11->where('future_reference2',$id);
     $subfilter1->all();
     $subfilter12=collect($subfilter1);
   $subfilter2= $subfilter1=collect($subfilter1)->map(function($x){ return (array) $x; })->toArray();
    
     $arr_new3 = round(array_sum(array_column($subfilter1, 'amount')),2);
     if($arr_new3==0)
     $arr_new3='-';
   
   $html.="<tr class='child child$subid'><td class='parent' data='$sub3id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
     text-align: right;
 '>".money_format('%!i',$arr_new3)."</td><td></td></tr>";
  

   foreach($subfilter12 AS $val4){
 $accid=$val4->account_id;   
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4->account_id);
$accountname=$accn[0]->account_name;
$amount4=$val4->amount;
 $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4)."</td><td></td></tr>";

}
 }
}
 else
 {
    foreach($filter11 AS $val4){
    
 $accountname=''; 
if($val4->account_id!=''){
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4->account_id);
$accountname=$accn[0]->account_name;
}
 $amount4=$val4->amount;
  $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>".money_format('%!i',$amount4)."</td><td></td></tr>";

} 
 }

}
}
 else
 {
    foreach($filter1 AS $val4){
    
   

$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4->account_id);
$accountname=$accn[0]->account_name;
 $amount4=$val4->amount;
  $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>".money_format('%!i',$amount4)."</td><td></td></tr>";

}
 }
// $html.="<tr><td><b>$code1  $name1 Total</b></td><td ></td><td style='
// text-align: right;
// '>".money_format('%!i',$arr_new1)."</td></tr><tr><td></td><td></td><td></td></tr>";

}
$html.="<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new,2))."</b></td></tr>";

    $total=$total+$arr_new;


}

$html.="<tr class='heading'><td><b>EQUITY & LIABILITIES Total</b></td><td></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total,2))."</b></td></tr>";



$data=\DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (10,5,13) order by main_account_code asc");
//dd($data);
setlocale(LC_MONETARY, 'en_IN');
$total=0;
error_reporting(0);

$html.="<tr class='heading'><td><b>ASSEST</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";
foreach($data as $k=>$val)
{


$id=$val->account_class_id;
$name0=ucwords(strtolower($val->account_class_name));

$filter=$result->where('main_account_id',$id);
$filter->all();
$filter0=collect($filter);
$filter=collect($filter)->map(function($x){ return (array) $x; })->toArray();

$arr_new= round(array_sum(array_column($filter, 'amount')),2);
if($arr_new==0)
    $arr_new='-';

$html.="<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

$sub1=\DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

foreach($sub1 as $val1)
{
    $p_id=$id=$val1->account_codes_line_id;
    $name1=ucwords(strtolower($val1->account_code_meaning)); 
    $code1= $val1->account_code;
    $filter1=$filter0->where('sub_account_id',$id);
    $filter1->all();
   $filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new1 = round(array_sum(array_column($filter1, 'amount')),2);

    if($arr_new1==0)
    $arr_new1='-';
    
    
 $html.="<tr><td class='parent' data='$p_id' col='0'>$code1  $name1</td><td style='
    text-align: right;
'></td><td style='
text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1,2))."</td></tr>"; 
//Sub2 
$sub2=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

if(count($sub2)>0)
 {
foreach($sub2 as $val2)
{
    $id=$val2->account_codes_line_id;
    $name2=ucwords(strtolower($val2->account_code_meaning)); 
    $code2= $val2->account_code;
    $filter1=$filter11->where('future_reference1',$id);
    $filter1->all();
    //$filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new2 = round(array_sum(array_column($filter1, 'amount')),2);
    if($arr_new2==0)
    $arr_new2='-';
    
    $html.="<tr class='child child$p_id'><td class='parent' data='$id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2,2))."</td><td></td></tr>";
$sub3=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
 if(count($sub3)>0)
 {
 foreach($sub3 as $val3)
 {
//   // dd($val3);
    $sub3id=$val3->account_codes_line_id;
      $name3=ucwords(strtolower($val3->account_code_meaning)); 
     $code3= $val3->account_code;
     $subfilter1=$filter11->where('future_reference2',$sub3id);
     $subfilter1->all();
     $subfilter12=collect($subfilter1);
   $subfilter2= $subfilter1=collect($subfilter1)->map(function($x){ return (array) $x; })->toArray();
    
     $arr_new3 = round(array_sum(array_column($subfilter1, 'amount')),2);
     if($arr_new3==0)
     $arr_new3='-';
   
   $html.="<tr class='child child$id'><td class='parent' data='$sub3id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
     text-align: right;
 '>".money_format('%!i',$arr_new3)."</td><td></td></tr>";
  

   foreach($subfilter12 AS $val4){
 $accid=$val4->account_id;   
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4->account_id);
$accountname=$accn[0]->account_name;
$amount4=$val4->amount;
 $html.="<tr class='child child$sub3id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4)."</td><td></td></tr>";

}
 }
}
 else
 {
    foreach($filter1 AS $val4){
  //  dd($filter1);
 $accountname=''; 
$accid=$val4['account_id'];
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;

 $amount4=$val4['amount'];
  $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>".money_format('%!i',$amount4)."</td><td></td></tr>";

} 

}
}
} else
 {
    foreach($filter1 AS $val4){
    
   

$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4->account_id);
$accountname=$accn[0]->account_name;
 $amount4=$val4->amount;
  $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>".money_format('%!i',$amount4)."</td><td></td></tr>";

}
 }
}
$html.="<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new,2))."</b></td></tr>";

    $total=$total+$arr_new;


}

$html.="<tr class='heading'><td><b>ASSEST Total</b></td><td></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total,2))."</b></td></tr>";



   return $html;     


    }

	public function profitandloss(){
	    $this->data['ship_date'] = \DB::select("SELECT f_journal_entry_t.journal_date,f_journal_entry_lines_t.created_at FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id WHERE f_journal_entry_t.journal_name LIKE '%SHIP-SO%' ORDER BY f_journal_entry_t.journal_entry_id DESC LIMIT 1");
                $this->data['result']=[];
	   	return view('trailbalance.profitandloss',$this->data);	
	}
   	public function getprofitandloss($start_date=null,$end_date=null){


        

         $id=0;

if($start_date)
{
$id=1;
}
else
{
$start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?   date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
    }

        
$SQL ="select v3.*,f_account_structure_t.main_account_id,f_account_structure_t.sub_account_id,f_account_structure_t.future_reference1,f_account_structure_t.future_reference2 from ((select v1.account_id,v1.main_account_code,v1.main,v1.sub1,v1.sub2,v1.sub3,v1.sub4,round(sum(v1.amount),2)  as amount from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=600000  union all (SELECT 
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.credit_amount- f_journal_entry_lines_t.debit_amount
                ),
                2
            )  AS amount
           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=600000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v1 group by v1.account_id ORDER by v1.main_account_code asc) union all (select v2.account_id,v2.main_account_code,v2.main,v2.sub1,v2.sub2,v2.sub3,v2.sub4,round(sum(v2.amount),2)  as amount from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=700000  union all (SELECT
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.debit_amount- f_journal_entry_lines_t.credit_amount
                ),
                2
            )  AS amount
           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=700000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v2 group by v2.account_id ORDER by v2.main_account_code asc) )v3 join f_account_structure_t on f_account_structure_t.f_account_structure_id=v3.account_id  order by v3.main_account_code asc";

        $result1 = \DB::select( $SQL );
        if($id==1)
        {
       
        
        $result=collect($result1);
        
        //$arr_new = array_sum(array_column($result, 'amount'));
        
        $lia=$result->where('main_account_code','like','600000');
        $lia->all();
       $lia= json_decode(json_encode($lia));
       $arr_new1 = array_sum(array_column($lia, 'amount'));
     
     
       
       $arr_new = array_sum(array_column($result1, 'amount'));
        
    $sum=$arr_new1-($arr_new-$arr_new1);
        return $sum;
        }
       

    if(isset($_GET['download']))
    {
         $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
            $data=''; 
  
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=data.xls');
 $output = fopen('profitandlossregular.xls', 'w');     

$rows=array();
$rows="Account Class\t";
$rows.="Account Type\t";
$rows.="Sub1\t";
$rows.="Sub2\t";
$rows.="Sub3\t";
$rows.="Sub4\t";
$rows.="Amount(in Rs.)\n";

 //fputcsv($output, $rows); 
 
      foreach($result1 as  $row)
{
   // $rows=array();
    $rows.=$row['main_account_code']."\t";
    $rows.=$row['main']."\t";
    $rows.=$row['sub1']."\t";
    $rows.=$row['sub2']."\t";
    $rows.=$row['sub3']."\t";
    $rows.=$row['sub4']."\t";
    $rows.=$row['amount']."\n";
  
    //fputcsv($output, $rows); 
   
}
fwrite($output,$rows);
 
return 1;
    }



$result=collect($result1);


$data=\DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (3,9)");

setlocale(LC_MONETARY, 'en_IN');
$total=0;
error_reporting(0);
$html='<table class="table" style="    width: 100%;"><thead><th>Particulars</th><th>Amount in (Rs.)</th><th>Amount in (Rs.)</th></thead><tbody>';
foreach($data as $k=>$val)
{


$id=$val->account_class_id;
$name0=ucwords(strtolower($val->account_class_name));

$filter=$result->where('main_account_id',$id);
$filter->all();
$filter0=collect($filter);
$filter=collect($filter)->map(function($x){ return (array) $x; })->toArray();

$arr_new= round(array_sum(array_column($filter, 'amount')),2);
if($arr_new==0)
    $arr_new='-';

$html.="<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

$sub1=\DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

foreach($sub1 as $val1)
{
    $p_id=$id=$val1->account_codes_line_id;
    $name1=ucwords(strtolower($val1->account_code_meaning)); 
    $code1= $val1->account_code;
    $filter1=$filter0->where('sub_account_id',$id);
    $filter1->all();
    $filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new1 = round(array_sum(array_column($filter1, 'amount')),2);

    if($arr_new1==0)
    $arr_new1='-';
    
    $html.="<tr><td class=parent' data='$id' col='0'>$code1  $name1</td><td ></td><td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1,2))."</td></tr>";

//Sub2 
$sub2=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
if(count($sub2)>0){
foreach($sub2 as $val2)
{
  $p_id1=  $id=$val2->account_codes_line_id;
    $name2=ucwords(strtolower($val2->account_code_meaning)); 
    $code2= $val2->account_code;
    $filter1=$filter11->where('future_reference1',$id);
    $filter1->all();
    //$filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new2 = round(array_sum(array_column($filter1, 'amount')),2);
    if($arr_new2==0)
    $arr_new2='-';
    
    $html.="<tr class='child child$p_id'><td class='parent' data='$id' col='0' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2,2))."</td><td></td></tr>";
//Sub3 

$sub3=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
if(count($sub3)>0){
foreach($sub3 as $val3)
{
    
    $id=$val3->account_codes_line_id;
    $p_id2=$val3->account_codes_line_id;
    $name3=ucwords(strtolower($val3->account_code_meaning)); 
    $code3= $val3->account_code;
    $filter1=$filter11->where('future_reference2',$id);
    $filter1->all();
    //$filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new2 = round(array_sum(array_column($filter1, 'amount')),2);
    if($arr_new2==0)
    $arr_new2='-';
    
    $html.="<tr class='child child$p_id1'><td class='parent' data='$id' col='0' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2,2))."</td><td></td></tr>";

foreach($filter1 AS $val4){
 $accid=$val4['account_id'];   
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;
$amount4=$val4['amount'];
 $html.="<tr class='child child$p_id2'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4)."</td><td></td></tr>";

}
}
}else{
      foreach($filter1 AS $val4){
$accid=$val4['account_id'];
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;
$amount4=$val4['amount'];
 $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4)."</td><td></td></tr>";

}  
}
}
}else{
      foreach($filter1 AS $val4){
$accid=$val4['account_id'];
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;
$amount4=$val4['amount'];
 $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4)."</td><td></td></tr>";

}
}
// $html.="<tr><td><b>$code1  $name1 Total</b></td><td ></td><td style='
// text-align: right;
// '>".money_format('%!i',$arr_new1)."</td></tr><tr><td></td><td></td><td></td></tr>";

}
$html.="<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new,2))."</b></td></tr>";
if($k==0)
{
    $total=$arr_new;
}
else{
    $total=$total-$arr_new;
}

}

$html.="<tr class='heading'><td><b>Net Income</b></td><td></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total,2))."</b></td></tr>";

   return $html; 

    }
     public function profitandlossstdtindex(){
         $this->data['ship_date'] = \DB::select("SELECT f_journal_entry_t.journal_date,f_journal_entry_lines_t.created_at FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id WHERE f_journal_entry_t.journal_name LIKE '%SHIP-SO%' ORDER BY f_journal_entry_t.journal_entry_id DESC LIMIT 1");
                $this->data['result']=[];
        return view('trailbalance.profitandlossstd',$this->data);  
    }
   public function getprofitandlossstd($start_date=null,$end_date=null){


$start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?   date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
    


            $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
       

$cost=\DB::select("select sum(total) as total from(SELECT round(sum(m_products_t.`Std Cost`*s_invoice_lines_t.qty),2)as total FROM `s_invoice_lines_t` join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=s_invoice_lines_t.invoice_hdr_id and s_invoice_hdr_t.invoice_status='APPROVED' and s_invoice_hdr_t.invoice_date between '$start_date' and '$end_date' join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id and m_products_t.product_group_id=1
union ALL
SELECT round(sum(i_qoh_detail_t.cost*s_invoice_lines_t.qty),2)as total FROM `s_invoice_lines_t` join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=s_invoice_lines_t.invoice_hdr_id and s_invoice_hdr_t.invoice_status='APPROVED'  and s_invoice_hdr_t.invoice_date between '$start_date' and '$end_date' join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id and m_products_t.product_group_id!=1
join i_qoh_detail_t on i_qoh_detail_t.batch_number=s_invoice_lines_t.batch_number and i_qoh_detail_t.product_id=s_invoice_lines_t.product_id and i_qoh_detail_t.qoh_source='PURCHASE_STOREMOVE')f");


$total=$cost[0]->total;

//dd($total);
       
$SQL ="select account_id,main_account_code,main,sub1,sub2,sub3,sub4,
        (Case
  when account_id='11' then $total
  when account_id='85' then 0
  else amount
  end) as amount,`main_account_id`,
  `sub_account_id`,
  `future_reference1`,
  `future_reference2`,
  `sub_account4_id` 
 from ((select v1.account_id,v1.main_account_code,v1.main,v1.sub1,v1.sub2,v1.sub3,v1.sub4,round(sum(v1.amount),2)  as amount,v1.`main_account_id`,
 v1.`sub_account_id`,
 v1.`future_reference1`,
 v1.`future_reference2`,
 v1.`sub_account4_id` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount,
        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=600000  union all (SELECT 
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.credit_amount- f_journal_entry_lines_t.debit_amount
                ),
                2
            )  AS amount,
'' as `main_account_id`,
        '' as `sub_account_id`,
        '' as `future_reference1`,
        '' as `future_reference2`,
        '' as `sub_account4_id`           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=600000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v1 group by v1.account_id ORDER by v1.main_account_code asc) union all (select v2.account_id,v2.main_account_code,v2.main,v2.sub1,v2.sub2,v2.sub3,v2.sub4,round(sum(v2.amount),2)  as amount, v2.`main_account_id`,
            v2.`sub_account_id`,
            v2.`future_reference1`,
            v2.`future_reference2`,
            v2.`sub_account4_id` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount,

        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`

    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=700000  union all (SELECT
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.debit_amount- f_journal_entry_lines_t.credit_amount
                ),
                2
            )  AS amount,'' as `main_account_id`,
            '' as `sub_account_id`,
            '' as `future_reference1`,
            '' as `future_reference2`,
            '' as `sub_account4_id` 
           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=700000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v2 group by v2.account_id ORDER by v2.main_account_code asc) )v3 order by v3.main_account_code asc";

        $result = \DB::select( $SQL );


    if(isset($_GET['download']))
    {
         $result1=collect($result)->map(function($x){ return (array) $x; })->toArray();
            $data=''; 
  
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=data.xls');
 $output = fopen('profitandloss.xls', 'w');     

$rows=array();
$rows="Account Class\t";
$rows.="Account Type\t";
$rows.="Sub1\t";
$rows.="Sub2\t";
$rows.="Sub3\t";
$rows.="Sub4\t";
$rows.="Amount(in Rs.)\n";

 //fputcsv($output, $rows); 
 
      foreach($result1 as  $row)
{
   // $rows=array();
    $rows.=$row['main_account_code']."\t";
    $rows.=$row['main']."\t";
    $rows.=$row['sub1']."\t";
    $rows.=$row['sub2']."\t";
    $rows.=$row['sub3']."\t";
    $rows.=$row['sub4']."\t";
    $rows.=$row['amount']."\n";
  
    //fputcsv($output, $rows); 
   
}
fwrite($output,$rows);
 
return 1;
    }

//dd($result);

$result=collect($result);


$data=\DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (3,9)");

setlocale(LC_MONETARY, 'en_IN');
$total=0;
error_reporting(0);
$html='<table class="table" style="    width: 100%;"><thead><th>Particulars</th><th>Amount in (Rs.)</th><th>Amount in (Rs.)</th></thead><tbody>';
foreach($data as $k=>$val)
{


$id=$val->account_class_id;
$name0=ucwords(strtolower($val->account_class_name));

$filter=$result->where('main_account_id',$id);
$filter->all();
$filter0=collect($filter);
$filter=collect($filter)->map(function($x){ return (array) $x; })->toArray();

$arr_new= round(array_sum(array_column($filter, 'amount')),2);
if($arr_new==0)
    $arr_new='-';

$html.="<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

$sub1=\DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

foreach($sub1 as $val1)
{
    $p_id=$id=$val1->account_codes_line_id;
    $name1=ucwords(strtolower($val1->account_code_meaning)); 
    $code1= $val1->account_code;
    $filter1=$filter0->where('sub_account_id',$id);
    $filter1->all();
    $filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new1 = round(array_sum(array_column($filter1, 'amount')),2);

    if($arr_new1==0)
    $arr_new1='-';
    
    $html.="<tr><td class=parent' data='$id' col='0'>$code1  $name1</td><td ></td><td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1,2))."</td></tr>";

//Sub2 
$sub2=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
if(count($sub2)>0)
{
foreach($sub2 as $val2)
{
    $id=$val2->account_codes_line_id;
     $p_id1=$val2->account_codes_line_id;
    $name2=ucwords(strtolower($val2->account_code_meaning)); 
    $code2= $val2->account_code;
    $filter1=$filter11->where('future_reference1',$id);
    $filter1->all();
    //$filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new2 = round(array_sum(array_column($filter1, 'amount')),2);
    if($arr_new2==0)
    $arr_new2='-';
    
    $html.="<tr class='child child$p_id'><td class='parent' data='$id' col='0' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2,2))."</td><td></td></tr>";

//Sub3 

$sub3=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
if(count($sub3)>0){
foreach($sub3 as $val3)
{
   // dd($val3);
    $id=$val3->account_codes_line_id;
    $p_id2=$val3->account_codes_line_id;
    $name3=ucwords(strtolower($val3->account_code_meaning)); 
    $code3= $val3->account_code;
    $filter1=$filter11->where('future_reference2',$id);
    $filter1->all();
    //$filter11=collect($filter1);
    $filter1=collect($filter1)->map(function($x){ return (array) $x; })->toArray();
    
    $arr_new2 = round(array_sum(array_column($filter1, 'amount')),2);
    if($arr_new2==0)
    $arr_new2='-';
    
    $html.="<tr class='child child$p_id1'><td class='parent' data='$id' col='0' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
    text-align: right;
'>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new2,2))."</td><td></td></tr>";
foreach($filter1 AS $val4){
 $accid=$val4['account_id'];   
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;
$amount4=$val4['amount'];
 $html.="<tr class='child child$p_id2'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4)."</td><td></td></tr>";

}
}
}else{
      foreach($filter1 AS $val4){
$accid=$val4['account_id'];
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;
$amount4=$val4['amount'];
 $html.="<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4)."</td><td></td></tr>";

}  
}
}

// $html.="<tr><td><b>$code1  $name1 Total</b></td><td ></td><td style='
// text-align: right;
// '>".money_format('%!i',$arr_new1)."</td></tr><tr><td></td><td></td><td></td></tr>";
}
else
{
    
    foreach($filter1 AS $val4){
$accid=$val4['account_id'];
$accn=\DB::select('select account_name from f_account_structure_t where f_account_structure_id='.$val4['account_id']);
$accountname=$accn[0]->account_name;
$amount4=$val4['amount'];
 $html.="<tr class='child child$p_id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>".money_format('%!i',$amount4)."</td><td></td></tr>";

}
}
}
$html.="<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new,2))."</b></td></tr>";
if($k==0)
{
    $total=$arr_new;
}
else{
    $total=$total-$arr_new;
}

}

$html.="<tr class='heading'><td><b>Net Income</b></td><td></td><td style='
text-align: right;
'><b>".preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total,2))."</b></td></tr>";

   return $html;   
       
  

    }
    public function getledgerpandlData(){
       $app_id=\Session::get('id');

                $wh='';
             error_reporting(0);

        if($_GET['_search']=='true')
                {
                   
                    $wh .=$this->jqgridsearchnotab('v1',$_GET['filters'],$search_tables);
                }

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

 $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?   date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
    $ledger_id  =  $_GET['ledger_id'];
$loc=\Session::get('location');
        $compy=\Session::get('companyid');
    $groupname=\Session::get('groupname');
   

//dd($end_date);
        if(!$sidx) $sidx =1;
       $ledger_data=\DB::select("SELECT * FROM (select f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`, f_journal_entry_lines_t.reference_source, (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                                                   WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                                                   WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                                                   WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                                                   WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                                                  else '' end ) as name   from f_journal_entry_lines_t left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id where f_journal_entry_lines_t.account_id='$ledger_id' and f_journal_entry_lines_t.journal_date between '$start_date' and  '$end_date' and f_journal_entry_t.journal_type!='OPENING BALANCE' order by f_journal_entry_lines_t.journal_date  asc) as v1 where 1=1 $wh");

$count = count($ledger_data);
     // dd($count);
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
      $balance= \DB::select("select sum(f.debit-f.credit)as balance from( select COALESCE(SUM(debit_amount),0) as debit,COALESCE(SUM(credit_amount),0)as credit from f_journal_entry_lines_t join f_journal_entry_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id where  f_journal_entry_lines_t.account_id='$ledger_id' and (f_journal_entry_lines_t.journal_date < '$start_date' or f_journal_entry_t.journal_type='OPENING BALANCE'))f");
     $balance=round($balance[0]->balance,2);
          
          
          $overall_datas[0] = (object)array();
  $cop=0;       
$dop=0;         
          
        if($balance>0)
        {         
          $overall_datas[0]->balance=$balance;
  //$balance=$balance+$v->debit_amount;
  $dop=$balance;
  $overall_datas[0]->debit_amounts=$balance;
  $overall_datas[0]->credit_amounts=0;
  $overall_datas[0]->net_salary=0;
  $overall_datas[0]->journal_date=$start_date;
  $overall_datas[0]->journal_type="Opening Balance";
  $overall_datas[0]->journal_name="Opening Balance";
  $overall_datas[0]->concatenated_segments='';

  $overall_datas[0]->reference_source='';
  $overall_datas[0]->reference_name='';
        }
        else
        {
        $overall_datas[0]->balance=$balance;
  //$balance=$balance+$v->debit_amount;
  $cop=$balance;
  $overall_datas[0]->debit_amounts=0;
  $overall_datas[0]->credit_amounts=$balance*-1;
  $overall_datas[0]->net_salary=0;
  $overall_datas[0]->journal_date=$start_date;
  $overall_datas[0]->journal_type="Opening Balance";
  $overall_datas[0]->journal_name="Opening Balance";
  $overall_datas[0]->concatenated_segments='';

  $overall_datas[0]->reference_source='';
  $overall_datas[0]->reference_name='';
        }
      //dd($overall_datas);   
    //$employee_data=\DB::select("select * from((select f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_t.journal_date,0 as net_salary FROM f_journal_entry_lines_t JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id where f_journal_entry_lines_t.reference_source='employee' and f_journal_entry_lines_t.reference_id='$employee_id' and f_journal_entry_lines_t.journal_date BETWEEN '$start_date' and '$end_date' group by f_journal_entry_t.journal_entry_id order by f_journal_entry_t.journal_date asc) union all (SELECT 0 as journal_entry_id,'Salary' as journal_name,'Salary' as journal_type,date as journal_date,net_salary FROM `hr_employee_payroll_lists` WHERE employee_id='$employee_id' and date BETWEEN '$start_date' and '$end_date'))v1 order by v1.journal_date asc ");
      
//  dd($cop);       
          //dd($employee_data);
          $key=1;
       //  dd($ledger_data);
foreach($ledger_data as $k=>$value)
{
  //dd($value);
  if($value->reference_source!='')
  {
    $id=$value->journal_entry_id;
    $reference_name=$value->name;
    $reference_source=$value->reference_source;
    $debit_amount=$value->debit_amount;    
    $credit_amount=$value->credit_amount; 
   
    if($debit_amount > 0)
    {
      $d=\DB::select("SELECT account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount=0");

    if(COUNT($d)==0){
      $acc_name='';  
     }else{
      $acc_name=$d[0]->concatenated_segments; 
}

    }  
    else
    {

     $d=\DB::select("SELECT account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");
     ///dd($d);
     if(COUNT($d)==0){
      $acc_name='';  
     }else{
      $acc_name=$d[0]->concatenated_segments; 
}
    }

  }
  else
  {
     $id=$value->journal_entry_id;
     $debit_amount=$value->debit_amount;    
    $credit_amount=$value->credit_amount; 
    if($debit_amount > 0)
    {
      $wh1=" and f_journal_entry_lines_t.debit_amount=0";
    }
    else
    {
      $wh1=" and f_journal_entry_lines_t.debit_amount=0";
    }
$d=\DB::select("select f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`,f_account_structure_t.concatenated_segments ,f_journal_entry_lines_t.reference_source, (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                                                   WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                                                   WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                                                   WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                                                   WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                                                  else '' end ) as name   from f_journal_entry_lines_t left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id where f_journal_entry_lines_t.journal_entry_id='$id' $wh1");
 //  dd($d);                                               
$reference_name=$d[0]->name;
    $reference_source=$d[0]->reference_source;
$acc_name=$d[0]->concatenated_segments; 


  }

  $overall_datas[$key] = (object)array();

if($debit_amount > 0 )
{
  $overall_datas[$key]->concatenated_segments=$acc_name;  
  $overall_datas[$key]->balance=round($balance+$debit_amount,2);
  $dbal=$balance=$balance+$debit_amount;
  $overall_datas[$key]->debit_amounts=$debit_amount;
  $overall_datas[$key]->net_salary=0;
  $overall_datas[$key]->journal_date=$value->journal_date;
  $overall_datas[$key]->journal_type=$value->journal_type;
  $overall_datas[$key]->journal_name=$value->journal_name;
  $overall_datas[$key]->reference_source=$reference_source;
  $overall_datas[$key]->reference_name=$reference_name;

}
else
{
  $overall_datas[$key]->concatenated_segments=$acc_name;
  $overall_datas[$key]->balance=round($balance-$credit_amount,2);
  $cbal=$balance=$balance-$credit_amount; 
  $overall_datas[$key]->credit_amounts=$credit_amount;
  $overall_datas[$key]->net_salary=0;
  $overall_datas[$key]->journal_date=$value->journal_date;
  $overall_datas[$key]->journal_type=$value->journal_type;
  $overall_datas[$key]->journal_name=$value->journal_name;
   $overall_datas[$key]->reference_source=$reference_source;
  $overall_datas[$key]->reference_name=$reference_name;
}
$key++;


}


$count1=COUNT($overall_datas);
//dd($overall_datas[$count1-1]);
$csum = array_column($overall_datas, 'credit_amounts');

$csum1 = array_sum($csum)-$cop;
//dd($csum1);
$csum2 = array_sum($csum);
$dsum = array_column($overall_datas, 'debit_amounts');
$dsum1 = array_sum($dsum)-$dop;
$dsum2 = array_sum($dsum);
$tsum1 = $dsum1-$csum1;
$tsum2 = $dsum2-$csum2;
//$tsum1 = array_sum($tsum);
 $overall_datas[$count1]=(object)array();
 $overall_datas[$count1]->credit_amounts=money_format('%!n', round($csum1,3));
 $overall_datas[$count1]->debit_amounts=money_format('%!n', round($dsum1,3));
 $overall_datas[$count1]->balance=money_format('%!n', round($tsum1,3));
 $overall_datas[$count1]->concatenated_segments="Current Total";
 
 $overall_datas[$count1+1]->credit_amounts=money_format('%!n', round($csum2,3));
 $overall_datas[$count1+1]->debit_amounts=money_format('%!n', round($dsum2,3));
 $overall_datas[$count1+1]->balance=money_format('%!n', round($tsum2,3));
 $overall_datas[$count1+1]->concatenated_segments="Total";



//dd($overall_datas[$count1]);
$ref_so=$overall_datas[1]->reference_source;

       
    if(isset($_GET['download']))
    {
       
        $result1=collect($overall_datas)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }

    $overall_datas =array_slice($overall_datas, $start , $limit );
   
      
        $responce->rows[]='';
        $responce->rows=$overall_datas;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
     
 } 
     /* END PROFIT AND LOSS BALANCE*/
    

    	public function trialbalance(){
                $this->data['result']=[];
	   	return view('trailbalance.trialbalancerpt',$this->data);	
	}

 public function gettrialbalance(){


   // dd("fdddf");
         $wh='';
        if(isset($_GET['pq_filter']))
         {
         $data=json_decode($_GET['pq_filter']);
         $data=$data->data;
         $table=array('f_journal_entry_lines_t','f_account_structure_t');

      $wh.=$this->pqgridsearch('f_journal_entry_t',$data,$table);
      //$wh.=$this->pqgridsearch('v1',$data);
      
         }
         $type='';

if($_GET['type']=="POSTED")
{
$type="f_journal_entry_t.journal_status='POSTED' and";
}

$start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?   date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';


            $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
      $sidx='';
        if (!$sidx)
            $sidx = 1;
              $result = \DB::select("SELECT count(f_journal_entry_t.journal_name) as count,round(sum(f_journal_entry_lines_t.debit_amount),2) as debit,round(sum(f_journal_entry_lines_t.credit_amount),'2') as credit,f_account_structure_t.concatenated_segments FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_t.journal_status='POSTED'   GROUP BY f_journal_entry_lines_t.account_id");
        
              $count = $result[0]->count;
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

        $compy=\Session::get('companyid');
        
$SQL ="select journal_name,debit,credit,concatenated_segments,sum(balance)as opening_balance,round(sum(balance+debit-credit),2) as balance ,FS, account_id from((SELECT f_journal_entry_t.journal_name,round(sum(f_journal_entry_lines_t.debit_amount),2) as debit,round(sum(f_journal_entry_lines_t.credit_amount),'2') as credit,f_account_structure_t.concatenated_segments,0 as balance,f_journal_entry_lines_t.account_id,(CASE WHEN f_account_structure_t.concatenated_segments LIKE '%60000%' OR f_account_structure_t.concatenated_segments LIKE '%70000%' THEN 'P&L'
         ELSE 'BS' END
         ) as FS FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE $type f_journal_entry_lines_t.journal_date between '$start_date' and '$end_date' $wh  GROUP BY f_journal_entry_lines_t.account_id  ORDER BY $sidx) union all (SELECT '' as journal_name,0 as debit,0 as credit,f_account_structure_t.concatenated_segments,round(sum(f_journal_entry_lines_t.debit_amount-f_journal_entry_lines_t.credit_amount),2) as balance,f_journal_entry_lines_t.account_id,(CASE WHEN f_account_structure_t.concatenated_segments LIKE '%60000%' OR f_account_structure_t.concatenated_segments LIKE '%70000%' THEN 'P&L'
         ELSE 'BS' END
         ) as FS FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE $type f_journal_entry_lines_t.journal_date < '$start_date' and  f_journal_entry_lines_t.journal_date >='2019-04-01' $wh  GROUP BY f_journal_entry_lines_t.account_id  ORDER BY $sidx))v1 group by v1.account_id ";

    if(isset($_GET['download']))
    {
        $download_SQL = "select journal_name,debit,credit,concatenated_segments,sum(balance)as opening_balance,sum(balance+debit-credit) as balance ,FS, account_id from((SELECT f_journal_entry_t.journal_name,sum(f_journal_entry_lines_t.debit_amount) as debit,sum(f_journal_entry_lines_t.credit_amount) as credit,f_account_structure_t.concatenated_segments,0 as balance,f_journal_entry_lines_t.account_id,(CASE WHEN f_account_structure_t.concatenated_segments LIKE '%60000%' OR f_account_structure_t.concatenated_segments LIKE '%70000%' THEN 'P&L'
         ELSE 'BS' END
         ) as FS FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE $type f_journal_entry_lines_t.journal_date between '$start_date' and '$end_date' $wh  GROUP BY f_journal_entry_lines_t.account_id  ORDER BY $sidx) union all (SELECT '' as journal_name,0 as debit,0 as credit,f_account_structure_t.concatenated_segments,round(sum(f_journal_entry_lines_t.debit_amount-f_journal_entry_lines_t.credit_amount),2) as balance,f_journal_entry_lines_t.account_id,(CASE WHEN f_account_structure_t.concatenated_segments LIKE '%60000%' OR f_account_structure_t.concatenated_segments LIKE '%70000%' THEN 'P&L'
         ELSE 'BS' END
         ) as FS FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE $type f_journal_entry_lines_t.journal_date < '$start_date' and  f_journal_entry_lines_t.journal_date >='2019-04-01' $wh  GROUP BY f_journal_entry_lines_t.account_id  ORDER BY $sidx))v1 group by v1.account_id";

  $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }



$result = \DB::select( $SQL );

        $responce->rows[]='';
        $responce->data=$result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
    }
	public function getledgerreport(){


         $wh='';
        if(isset($_GET['pq_filter']))
         {
         $data=json_decode($_GET['pq_filter']);
         $data=$data->data;
         $table=array('f_journal_entry_lines_t','f_account_structure_t');

      $wh.=$this->pqgridsearch('f_journal_entry_t',$data,$table);
         }


$start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?   date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';


            $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        
        
      $sidx='';
        if (!$sidx)
            $sidx = 1;
             // $result = \DB::select("SELECT count(f_journal_entry_t.journal_name) as count,round(sum(f_journal_entry_lines_t.debit_amount),2) as debit,round(sum(f_journal_entry_lines_t.credit_amount),'2') as credit,f_account_structure_t.concatenated_segments FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_t.journal_status='POSTED'   GROUP BY f_journal_entry_lines_t.account_id  $wh");
        
         
        $compy=\Session::get('companyid');
        
$SQL ="select v2.*,sum(v1.debit) as debit,sum(v1.credit)credit,sum(v1.balance)as opening_balance,round(sum(v1.balance+v1.debit-v1.credit),2) as balance  from((SELECT f_journal_entry_t.journal_name,round(sum(f_journal_entry_lines_t.debit_amount),2) as debit,round(sum(f_journal_entry_lines_t.credit_amount),'2') as credit,f_account_structure_t.concatenated_segments,0 as balance,f_journal_entry_lines_t.account_id FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE   f_journal_entry_lines_t.journal_date between '$start_date' and '$end_date'   GROUP BY f_journal_entry_lines_t.account_id  ORDER BY $sidx) union all (SELECT '' as journal_name,0 as debit,0 as credit,f_account_structure_t.concatenated_segments,round(sum(f_journal_entry_lines_t.debit_amount-f_journal_entry_lines_t.credit_amount),2) as balance,f_journal_entry_lines_t.account_id FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE  f_journal_entry_lines_t.journal_date < '$start_date' and  f_journal_entry_lines_t.journal_date >='2019-04-01'   GROUP BY f_journal_entry_lines_t.account_id  ORDER BY $sidx))v1 join (select f.f_account_structure_id,m.account_class_name as main,r1.account_code_meaning as sub1, if(f.future_reference1>0,r2.account_code_meaning,'')as sub2,if(f.future_reference2>0,r3.account_code_meaning,'')as sub3,if(f.sub_account4_id>0,r4.account_code_meaning,'')as sub4 from f_account_structure_t as f left JOIN f_account_class_t as m ON m.account_class_id=f.main_account_id left JOIN f_account_codes_lines_t as r1 ON r1.account_codes_line_id=f.sub_account_id
JOIN f_account_codes_lines_t as r2 ON r2.account_codes_line_id=f.future_reference1 left JOIN f_account_codes_lines_t as r3 ON r3.account_codes_line_id=f.future_reference2 left JOIN f_account_codes_lines_t as r4 ON r4.account_codes_line_id=f.sub_account4_id) as v2 on v2.f_account_structure_id=v1.account_id group by f_account_structure_id";


//dd($SQL);



$result = \DB::select( $SQL );

        $responce->rows[]='';
        $responce->data=$result;
        $responce->curPage = 1;
        $responce->total = 1;
        $responce->totalRecords = count($result);
        echo json_encode($responce);
    }

        public function generalledger(){
                $this->data['result']=[];
	   	return view('trailbalance.generalledgerrpt',$this->data);	
	}
	public function getgeneralledger(){
                $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? 	date("Y-m-d", strtotime($_GET['start_date'])) : '';
		        $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';

	$sql=\DB::select("SELECT
    f_account_codes_lines_t.account_codes_line_id,
    f_account_codes_lines_t.account_class_id,
f_account_codes_lines_t.account_code,
f_account_codes_lines_t.account_code_meaning,
    SUM(
        f_journal_entry_lines_t.debit_amount
    ) AS total_debit,
    SUM(
        f_journal_entry_lines_t.credit_amount
    ) AS total_credit,
    f_journal_entry_lines_t.f_journal_entry_line_id,
    f_account_structure_t.future_reference2
FROM
    `f_account_codes_lines_t`
LEFT JOIN f_account_structure_t ON
    (
        f_account_structure_t.future_reference2 = f_account_codes_lines_t.account_codes_line_id
    )
LEFT JOIN f_journal_entry_lines_t ON(
        f_journal_entry_lines_t.account_id = f_account_structure_t.f_account_structure_id
    )
	left join f_journal_entry_t on(f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id)
where f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date' and (f_journal_entry_t.journal_status='POSTED' or f_journal_entry_t.journal_status='APPROVED')
GROUP BY
    f_account_codes_lines_t.account_codes_line_id");
         $this->data['start_date']=$start_date;
	 $this->data['end_date']=$end_date;
                
		$this->data['result']=$sql;
	   	return view('trailbalance.gltable',$this->data);
	}
}
