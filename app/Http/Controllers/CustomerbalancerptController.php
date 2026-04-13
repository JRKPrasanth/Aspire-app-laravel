<?php

namespace App\Http\Controllers;

use App\customerbalancerpt;
use Illuminate\Http\Request;
use DB;
class CustomerbalancerptController extends Controller
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
       
       $this->data['customer_id']=$this->jCombocompdist('m_customers_t','customer_id','customer_name','');

	 return view('customerbalancerpt.customerbalance', $this->data);       
    }
	
	
	
     public function customerbalance(Request $request){
		  $wh='';
    require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : null;
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : null;
    $customer = $request->customer_id ?? null;
		 
    $this->data['start_date']=$start_date;
    $this->data['end_date']=$end_date;
		 
		 
    $this->data['customer_dat']=\DB::select("select m_customers_t.customer_name,m_customer_sites_t.customer_site_name,m_customer_sites_t.address,
(SELECT m_cities_t.city_name from m_cities_t where m_cities_t.city_id=m_customer_sites_t.city) as city_name,
(SELECT m_countries_t.country_name from m_countries_t WHERE m_countries_t.country_id=m_customer_sites_t.country) as country_name,
(SELECT m_states_t.state_name from m_states_t WHERE m_states_t.state_id=m_customer_sites_t.state) as state_name,m_customer_sites_t.pincode
from m_customers_t 
left join m_customer_sites_t on m_customer_sites_t.customer_id=m_customers_t.customer_id where m_customer_sites_t.site_type='BILL_TO' and m_customers_t.active='Yes'  and m_customers_t.customer_id='$customer' limit 1");


 $overall_data=\DB::select("select f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_t.journal_date,CONCAT(LEFT(MONTHNAME(f_journal_entry_t.journal_date),3),'-',YEAR(f_journal_entry_t.journal_date))as monyr,CASE WHEN MONTH(f_journal_entry_t.journal_date)>=4 THEN concat(YEAR(f_journal_entry_t.journal_date), '-',YEAR(f_journal_entry_t.journal_date)+1) ELSE concat(YEAR(f_journal_entry_t.journal_date)-1,'-', YEAR(f_journal_entry_t.journal_date)) END AS financial_year FROM f_journal_entry_lines_t JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id where f_journal_entry_lines_t.reference_source='customer' and f_journal_entry_lines_t.reference_id='$customer' and f_journal_entry_lines_t.journal_date BETWEEN '$start_date' and '$end_date' group by f_journal_entry_t.journal_entry_id order by f_journal_entry_t.journal_date asc");

        $balance= \DB::select("select sum(f.debit-f.credit)as balance from( select COALESCE(SUM(debit_amount),0) as debit,COALESCE(SUM(credit_amount),0)as credit from f_journal_entry_lines_t where f_journal_entry_lines_t.reference_source='CUSTOMER' and f_journal_entry_lines_t.reference_id='$customer' and f_journal_entry_lines_t.journal_date < '$start_date')f");
        $cus_balance=\DB::select("select COALESCE(sum(s_invoice_hdr_t.invoice_grand_total),0) as balance_amount from s_invoice_hdr_t where s_invoice_hdr_t.ship_to_customer_id='$customer' and s_invoice_hdr_t.created_by='' and s_invoice_hdr_t.invoice_date < '$start_date'");
        
                    $balance=round($balance[0]->balance+$cus_balance[0]->balance_amount,2);
					
					 $overall_datas = [];
           $overall_datas[0] = (object)[];
           $overall_datas[0]->sort_order = 0;

        if($balance>0)
        {         
          $overall_datas[0]->balance=$balance;

  $overall_datas[0]->debit_amounts=$balance;
  $overall_datas[0]->credit_amounts=0;
  $overall_datas[0]->net_salary=0;
  $overall_datas[0]->journal_date=$start_date;
  $overall_datas[0]->monyr='';
  $overall_datas[0]->financial_year='';
  $overall_datas[0]->journal_type="Opening Balance";
  $overall_datas[0]->journal_name="Opening Balance";
  $overall_datas[0]->name="Opening Balance";
  $overall_datas[0]->concatenated_segments='';
  
        }
        else
        {
        $overall_datas[0]->balance=$balance;

  $overall_datas[0]->debit_amounts=0;
  $overall_datas[0]->credit_amounts=$balance*-1;
  $overall_datas[0]->net_salary=0;
  $overall_datas[0]->journal_date=$start_date;
  $overall_datas[0]->monyr='';
  $overall_datas[0]->financial_year='';
  $overall_datas[0]->journal_type="Opening Balance";
  $overall_datas[0]->journal_name="Opening Balance";
  $overall_datas[0]->name="Opening Balance";
  $overall_datas[0]->concatenated_segments='';
        }
$key=1;
	
foreach($overall_data as $key1=>$value)
{
	
	//REVERSE RECEIPT  REVERSE ADVANCE RECEIPT
	$id=$value->journal_entry_id;
if($value->journal_type=="SALES INVOICE" || $value->journal_type=="REVERSE RECEIPT" || $value->journal_type=="REVERSE ADVANCE RECEIPT")
{
	$datas=\DB::select("SELECT  debit_amount as debit_amount , sum(credit_amount) as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");
}
elseif($value->journal_type=="DIRECTRECEIPTS" || $value->journal_type=="RECEIPT" || $value->journal_type=="REVERSE" || $value->journal_type=="ADVANCE RECEIPT" || $value->journal_type=="CREDIT")
{

 $datas=\DB::select("SELECT 0 as debit_amount , round(credit_amount,2) as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount=0 limit 1");
        
}
elseif($value->journal_type=="EXPENSES")
{
	$datas=\DB::select("select 0 as debit_amount,round(sum(credit_amount),2) as credit_amount,account_id,concatenated_segments,account_name from((SELECT sum(debit_amount) as debit_amount , 0 as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id') union all (SELECT sum(debit_amount) as debit_amount , credit_amount as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.reference_source='CUSTOMER'))v1");

}
elseif($value->journal_type=="PAYMENT" || $value->journal_type=="DEBIT" || $value->journal_type=="DIRECTPAYMENT" || $value->journal_type=="RMA")
{
	$datas=\DB::select("SELECT credit_amount as debit_amount , 0 as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount=0");

}
elseif($value->journal_type=="SALES RETURN" )
{
  //dd($id);
  $datas=\DB::select("SELECT sum(a.credit_amount) as credit_amount,sum(a.account_id) as account_id,a.journal_entry_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name,0 as debit_amount  
from
((SELECT 0 as credit_amount,f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.account_id  FROM `f_journal_entry_lines_t` where journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount!=0 ORDER BY journal_entry_id ASC limit 1) union all(SELECT f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_entry_id,0 as account_id FROM `f_journal_entry_lines_t` where journal_entry_id='$id' and reference_source='CUSTOMER' )  
 ) as a  left join f_account_structure_t on (f_account_structure_t.f_account_structure_id=a.account_id)  where 1=1");
  //dd($datas);
}elseif($value->journal_type=="MANUAL" )
{
  
$datas=\DB::select("SELECT debit_amount , credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and reference_source='CUSTOMER' ");

//dd($datas);

$da=\DB::select("SELECT debit_amount , credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id'  and f_journal_entry_lines_t.reference_source='CUSTOMER'");

//dd($da);

if(isset($da[0]))
{
if($da[0]->credit_amount >0.0)
{
  //dd("dd");
  foreach($datas as $k1=>$v)
{
  //dd($v->debit_amount);
  $datas[$k1]->debit_amount=$v->credit_amount;
  $datas[$k1]->credit_amount=0;
}
}
else
{
  //dd($datas);
    foreach($datas as $k1=>$v)
{
  $datas[$k1]->credit_amount=$v->debit_amount;
  $datas[$k1]->debit_amount=0;
  
}
  
} 
}
}
//dd($datas);
 $overall_datas[$key] = (object)array();
if($datas[0]->debit_amount > 0 )
{
  $overall_datas[$key]->concatenated_segments=$datas[0]->concatenated_segments;  
  $overall_datas[$key]->balance=round($balance+$datas[0]->debit_amount,2);
  $balance=$balance+$datas[0]->debit_amount;
  $overall_datas[$key]->debit_amounts=round($datas[0]->debit_amount,2);
  $overall_datas[$key]->net_salary=0;
  $overall_datas[$key]->journal_date=$value->journal_date;
  $overall_datas[$key]->monyr=$value->monyr;
  $overall_datas[$key]->credit_amounts=0;
  $overall_datas[$key]->financial_year=$value->financial_year;
  $overall_datas[$key]->journal_type=$value->journal_type;
  $overall_datas[$key]->journal_name=$value->journal_name;
  $overall_datas[$key]->name=$datas[0]->account_name;
  
}
else
{
	
$overall_datas[$key]->concatenated_segments=$datas[0]->concatenated_segments;
  $overall_datas[$key]->balance=round($balance-$datas[0]->credit_amount,2);
  $balance=$balance-$datas[0]->credit_amount; 
  $overall_datas[$key]->credit_amounts=round($datas[0]->credit_amount,2);
  $overall_datas[$key]->net_salary=0;
  $overall_datas[$key]->journal_date=$value->journal_date;
  $overall_datas[$key]->monyr=$value->monyr;
  $overall_datas[$key]->debit_amounts=0;
  $overall_datas[$key]->financial_year=$value->financial_year;
  $overall_datas[$key]->journal_type=$value->journal_type;
  $overall_datas[$key]->journal_name=$value->journal_name;
  $overall_datas[$key]->name=$datas[0]->account_name;
}

$overall_datas[$key]->sort_order = 1;
$key++;

}


 if(isset($_GET['print']))
    {
        
        $this->data['results'] =$overall_datas;
        $this->data['ddd'] ="Dssd";
       // dd($this->data);
        return view('customerbalancerpt.print',$this->data) ;
    }

    $result =$overall_datas;
		 
	return response()->json(['data' => $result]);
   

}							 

	
	
}
