<?php

namespace App\Http\Controllers;

use App\Soagingsummaryrpt;
use Illuminate\Http\Request;

class SoagingsummaryrptController extends Controller
{
      public function index()
    {
        $this->data['customeropt']=$this->jqgridselect('m_customers_t','customer_id','customer_name'); 
        $this->data['pageMethod']='soinvoiceagingsummaryrpt'; 
		  $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $currentdate=date('Y-m-d');  

      $current_date="\"".$currentdate."\"";

          if(isset($_GET['to_date'])){
          //$from_date=date('Y-m-d',strtotime($_GET['from_date']));
          $to_date=date('Y-m-d',strtotime($_GET['to_date']));
      }else{
        //  $from_date='2019-04-01';
          $to_date=date('Y-m-d');
      }
               $SQL = "SELECT sum(exp_amt) as exp_amt,sum(debit_amt) as debit_amt,sum(credit_amt) as credit_amt,SUM(total_blnce) as total_blnce,customer_id,customer_name,SUM(advanceamount) as advance_amount 
               FROM
     (SELECT 0 as exp_amt, 0 as debit_amt, 0 as credit_amt, (ROUND(SUM(s_invoice_hdr_t.balance_amount),2)) as total_blnce, m_customers_t.customer_id, m_customers_t.customer_name, 0 as advanceamount FROM m_customers_t JOIN s_invoice_hdr_t on s_invoice_hdr_t.ship_to_customer_id=m_customers_t.customer_id  where  s_invoice_hdr_t.invoice_status='APPROVED' and s_invoice_hdr_t.invoice_date between '2022-04-01' and '$to_date'  group by s_invoice_hdr_t.ship_to_customer_id    
    UNION ALL 
      SELECT 0 as exp_amt ,0 as debit_amt ,0 as credit_amt, 0 as total_blnce, m_customers_t.customer_id, m_customers_t.customer_name, ROUND(SUM(s_salesorder_hdr_t.balance_amount),2) as advanceamount FROM m_customers_t 
        Left JOIN s_salesorder_hdr_t on m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id and s_salesorder_hdr_t.advance_amount!=0 and s_salesorder_hdr_t.advance_status=0 and s_salesorder_hdr_t.sales_order_date between '2022-04-01' and '$to_date'  group by m_customers_t.customer_id
    UNION ALL 
        SELECT 0 as exp_amt,0 as debit_amt,round(sum(f_debitcredit_t.balance_amount),2)as credit_amt,0 as total_blnce,m_customers_t.customer_id,m_customers_t.customer_name,0 as advanceamount FROM m_customers_t left join f_debitcredit_t on f_debitcredit_t.customer_id=m_customers_t.customer_id and f_debitcredit_t.source_type='CREDIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date'  group by m_customers_t.customer_id
     UNION ALL  
        SELECT 0 as exp_amt,round(sum(f_debitcredit_t.balance_amount),2) as debit_amt,0 as credit_amt, 0 as total_blnce,m_customers_t.customer_id,m_customers_t.customer_name,0 as advanceamount FROM m_customers_t left join f_debitcredit_t on f_debitcredit_t.customer_id=m_customers_t.customer_id and f_debitcredit_t.source_type='DEBIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date'   group by m_customers_t.customer_id
     UNION ALL 
        SELECT  round(sum(f_expenses_t.balance_amount),2) as exp_amt,0 as debit_amt,0 as credit_amt, 0 as total_blnce,m_customers_t.customer_id,m_customers_t.customer_name,0 as advanceamount FROM m_customers_t left join f_expenses_t on f_expenses_t.customer_id=m_customers_t.customer_id and f_expenses_t.expense_status='APPROVED' and f_expenses_t.expense_date between '2022-04-01' and '$to_date'  group by m_customers_t.customer_id)v1   group by v1.customer_id";
    
    
    //dd($SQL);
    
		$result = \DB::select( $SQL ); 
   // dd($result);
    $total=0;
    $current=0;
    $advanceamount=0;
    $total15=0;
    $total30=0;
    $total45=0;
    $total46=0;

    
if(!empty($result)){
			  foreach($result as $key=>$value)
		  {
			  $current_date=date('Y-m-d');   
            //$current_date="\"".$currentdate."\"";
            $date1=date('Y-m-d');

$curr_date=date('Y-m-d');
            $purchase_inv=\DB::select(" SELECT COALESCE((ROUND(SUM(s_invoice_hdr_t.balance_amount),2)),0) as balance_amount 
    FROM s_invoice_hdr_t where  DATEDIFF('$current_date',due_date)<=0 and ship_to_customer_id='$value->customer_id' and invoice_status='APPROVED' and s_invoice_hdr_t.invoice_date between '2022-04-01' and '$to_date'
 UNION ALL
  SELECT COALESCE((ROUND(SUM(s_invoice_hdr_t.balance_amount),2)),0) as balance_amount
     FROM s_invoice_hdr_t where DATEDIFF('$current_date',due_date)<16 and DATEDIFF('$current_date',due_date)>0 and ship_to_customer_id='$value->customer_id' and invoice_status='APPROVED' and s_invoice_hdr_t.invoice_date between '2022-04-01' and '$to_date'
UNION ALL
   SELECT COALESCE((ROUND(SUM(s_invoice_hdr_t.balance_amount),2)),0) as '30'  
    FROM s_invoice_hdr_t where DATEDIFF('$current_date',due_date)<31 and DATEDIFF('$current_date',due_date)>15 and ship_to_customer_id='$value->customer_id' and invoice_status='APPROVED'  and s_invoice_hdr_t.invoice_date between '2022-04-01' and '$to_date'
UNION ALL 
  SELECT COALESCE((ROUND(SUM(s_invoice_hdr_t.balance_amount),2)),0) as '45' 
    FROM s_invoice_hdr_t where DATEDIFF('$current_date',due_date)<46 and DATEDIFF('$current_date',due_date)>30 and ship_to_customer_id='$value->customer_id' and invoice_status='APPROVED'  and s_invoice_hdr_t.invoice_date between '2022-04-01' and '$to_date'
UNION ALL 
  SELECT COALESCE((ROUND(SUM(s_invoice_hdr_t.balance_amount),2)),0) as 'above 45' 
    FROM s_invoice_hdr_t where DATEDIFF('$current_date',due_date)>45 and ship_to_customer_id='$value->customer_id' and invoice_status='APPROVED' and s_invoice_hdr_t.invoice_date between '2022-04-01' and '$to_date' ");   


            $debit_inv=\DB::select("SELECT COALESCE(ROUND(SUM(f_debitcredit_t.balance_amount),2),0) as balance_amount FROM f_debitcredit_t where  DATEDIFF('$current_date',debitcredit_date)<=0 and customer_id='$value->customer_id' and debitcredit_status='APPROVED' and source_type='DEBIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date'
            union all
            SELECT COALESCE(ROUND(SUM(f_debitcredit_t.balance_amount),2),0) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<16 and DATEDIFF('$current_date',debitcredit_date)>0 and customer_id='$value->customer_id' and debitcredit_status='APPROVED' and source_type='DEBIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(f_debitcredit_t.balance_amount),2),0) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<31 and DATEDIFF('$current_date',debitcredit_date)>15 and customer_id='$value->customer_id' and debitcredit_status='APPROVED' and source_type='DEBIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(f_debitcredit_t.balance_amount),2),0) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<46 and DATEDIFF('$current_date',debitcredit_date)>30 and customer_id='$value->customer_id' and debitcredit_status='APPROVED' and source_type='DEBIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(f_debitcredit_t.balance_amount),2),0) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)>45 and customer_id='$value->customer_id' and debitcredit_status='APPROVED' and source_type='DEBIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date' ");

            $credit_inv=\DB::select("SELECT COALESCE(ROUND(SUM(f_debitcredit_t.balance_amount),2),0) as balance_amount FROM f_debitcredit_t where  DATEDIFF('$current_date',debitcredit_date)<=0 and customer_id='$value->customer_id' and debitcredit_status='APPROVED' and source_type='CREDIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date'
            union all
            SELECT COALESCE(ROUND(SUM(f_debitcredit_t.balance_amount),2),0) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<16 and DATEDIFF('$current_date',debitcredit_date)>0 and customer_id='$value->customer_id' and debitcredit_status='APPROVED' and source_type='CREDIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(f_debitcredit_t.balance_amount),2),0) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<31 and DATEDIFF('$current_date',debitcredit_date)>15 and customer_id='$value->customer_id' and debitcredit_status='APPROVED' and source_type='CREDIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(f_debitcredit_t.balance_amount),2),0) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<46 and DATEDIFF('$current_date',debitcredit_date)>30 and customer_id='$value->customer_id' and debitcredit_status='APPROVED' and source_type='CREDIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(f_debitcredit_t.balance_amount),2),0) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)>45 and customer_id='$value->customer_id' and debitcredit_status='APPROVED' and source_type='CREDIT' and f_debitcredit_t.debitcredit_date between '2022-04-01' and '$to_date' ");


            $expan_inv=\DB::select("SELECT COALESCE(ROUND(SUM(f_expenses_t.balance_amount),2),0) as balance_amount FROM f_expenses_t where  DATEDIFF('$curr_date',bill_date)<=0 and customer_id='$value->customer_id' and expense_status='APPROVED' and f_expenses_t.bill_date between '2022-04-01' and '$to_date'
            union all
            SELECT COALESCE(ROUND(SUM(f_expenses_t.balance_amount),2),0) as balance_amount FROM f_expenses_t where DATEDIFF('$curr_date',bill_date)<16 and DATEDIFF('$curr_date',bill_date)>0 and customer_id='$value->customer_id' and expense_status='APPROVED' and f_expenses_t.bill_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(f_expenses_t.balance_amount),2),0) as balance_amount FROM f_expenses_t where DATEDIFF('$curr_date',bill_date)<31 and DATEDIFF('$curr_date',bill_date)>15 and customer_id='$value->customer_id' and expense_status='APPROVED' and f_expenses_t.bill_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(f_expenses_t.balance_amount),2),0) as balance_amount FROM f_expenses_t where DATEDIFF('$curr_date',bill_date)<46 and DATEDIFF($curr_date,bill_date)>30 and customer_id='$value->customer_id' and expense_status='APPROVED' and f_expenses_t.bill_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(f_expenses_t.balance_amount),2),0) as balance_amount FROM f_expenses_t where DATEDIFF('$curr_date',f_expenses_t.bill_date)>45 and customer_id='$value->customer_id' and expense_status='APPROVED' and f_expenses_t.bill_date between '2022-04-01' and '$to_date' ");


//dd($credit_inv);

            $return_inv=\DB::select("SELECT COALESCE(ROUND(SUM(so_rma_hdr_t.balance_amount),2),0) as balance_amount FROM so_rma_hdr_t where  DATEDIFF('$curr_date',return_date)<=0 and customerid='$value->customer_id' and return_status='APPROVED' and so_rma_hdr_t.return_date between '2022-04-01' and '$to_date'
            union all
            SELECT COALESCE(ROUND(SUM(so_rma_hdr_t.balance_amount),2),0) as balance_amount FROM so_rma_hdr_t where DATEDIFF('$curr_date',return_date)<16 and DATEDIFF('$curr_date',return_date)>0 and customerid='$value->customer_id' and return_status='APPROVED' and return_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(so_rma_hdr_t.balance_amount),2),0) as balance_amount FROM so_rma_hdr_t where DATEDIFF('$curr_date',return_date)<31 and DATEDIFF('$curr_date',return_date)>15 and customerid='$value->customer_id' and return_status='APPROVED' and return_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(so_rma_hdr_t.balance_amount),2),0) as balance_amount FROM so_rma_hdr_t where DATEDIFF('$curr_date',return_date)<46 and DATEDIFF($curr_date,return_date)>30 and customerid='$value->customer_id' and return_status='APPROVED' and return_date between '2022-04-01' and '$to_date'
            UNION ALL
            SELECT COALESCE(ROUND(SUM(so_rma_hdr_t.balance_amount),2),0) as balance_amount FROM so_rma_hdr_t where DATEDIFF('$curr_date',return_date)>45 and customerid='$value->customer_id' and return_status='APPROVED' and return_date between '2022-04-01' and '$to_date' ");


//dd($return_inv);

        	  $data[$key]['customer_id']=$value->customer_id;
			  $data[$key]['customer_name']=$value->customer_name;
         if($value->advance_amount!=''){
            $advance_amount=$value->advance_amount;
        }else{
          $advance_amount=0;
        }
        $data[$key]['advance_amount']=$value->advance_amount;
        
   
			  $data[$key]['total']=$value->total_blnce-$advance_amount+$value->debit_amt-$value->credit_amt-$value->exp_amt-$return_inv[0]->balance_amount-$return_inv[1]->balance_amount-$return_inv[2]->balance_amount-$return_inv[3]->balance_amount-$return_inv[4]->balance_amount;
			 // dd($data[$key]['total']);
			  $data[$key]['current']=$purchase_inv[0]->balance_amount+$debit_inv[0]->balance_amount-$credit_inv[0]->balance_amount-$expan_inv[0]->balance_amount-$return_inv[0]->balance_amount;
			  $data[$key]['lessthan16']=$purchase_inv[1]->balance_amount+$debit_inv[1]->balance_amount-$credit_inv[1]->balance_amount-$expan_inv[1]->balance_amount-$return_inv[1]->balance_amount;
			  $data[$key]['lessthan31']=$purchase_inv[2]->balance_amount+$debit_inv[2]->balance_amount-$credit_inv[2]->balance_amount-$expan_inv[2]->balance_amount-$return_inv[2]->balance_amount;
			  $data[$key]['lessthan46']=$purchase_inv[3]->balance_amount+$debit_inv[3]->balance_amount-$credit_inv[3]->balance_amount-$expan_inv[3]->balance_amount-$return_inv[3]->balance_amount;
			   $data[$key]['above46']=$purchase_inv[4]->balance_amount+$debit_inv[4]->balance_amount-$credit_inv[4]->balance_amount-$expan_inv[4]->balance_amount-$return_inv[4]->balance_amount;
 
          $total=$total+$value->total_blnce+$value->debit_amt-$value->credit_amt-$value->exp_amt-$data[$key]['advance_amount']-$return_inv[0]->balance_amount-$return_inv[1]->balance_amount-$return_inv[2]->balance_amount-$return_inv[3]->balance_amount-$return_inv[4]->balance_amount;
        $current=$current+$data[$key]['current'];
         $advanceamount=$advanceamount+$data[$key]['advance_amount'];
        $total15=$total15+$data[$key]['lessthan16'];
        $total30=$total30+$data[$key]['lessthan31'];
        $total45=$total45+$data[$key]['lessthan46'];
        $total46=$total46+$data[$key]['above46'];
			  
		  }  
			$this->data['result']=$data;
      $this->data['total']=$total;
      $this->data['current']=$current;
      $this->data['advance_amount']=$advanceamount;
        $this->data['total15']=$total15;
          $this->data['total30']=$total30;
            $this->data['total45']=$total45;
              $this->data['total46']=$total46;
                }  
                else{
                    $this->data['result']=array();
                }

       return view('soinvoiceagingsummaryrpt.table',$this->data);
    }
       public function getsoagingsummaryData(){
      
                $wh='';
                $search_table=array();

                if($_GET['_search']=='true')
                {
                $wh=$this->jqgridsearch("s_invoice_hdr_t",$_GET['filters']);
                }
                $page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
              $result = \DB::select("SELECT COUNT(invoice_hdr_id) AS count FROM s_invoice_hdr_t where 1=1 $wh");
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

      $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $currentdate=date('Y-m-d');   
        $current_date="\"".$currentdate."\"";
               $SQL = "SELECT
                m_customers_t.customer_name as ship_to_customer_id,   
  (SELECT
    SUM(balance_amount) FROM s_invoice_hdr_t WHERE DATEDIFF($current_date,due_date) < 16 AND DATEDIFF($current_date,due_date) > 0) AS 15days,
 ( SELECT
    SUM(balance_amount) FROM s_invoice_hdr_t  WHERE DATEDIFF($current_date,due_date) < 31 AND DATEDIFF($current_date,due_date) > 15) AS 30days,
  (SELECT
    SUM(balance_amount) FROM s_invoice_hdr_t WHERE DATEDIFF($current_date, due_date) < 46 AND DATEDIFF($current_date, due_date) > 30) AS 45days,
  (SELECT
    SUM(balance_amount) FROM s_invoice_hdr_t WHERE DATEDIFF($current_date, due_date) > 45) AS above45days
                FROM s_invoice_hdr_t 
                join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) 
                where 1=1 $wh  and s_invoice_hdr_t.company_id=$compy and s_invoice_hdr_t.location_id=$loc GROUP BY s_invoice_hdr_t.ship_to_customer_id ORDER BY $sidx $sord LIMIT $start , $limit";
//              dd($SQL); 
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	} 
}
