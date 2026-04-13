<?php

namespace App\Http\Controllers;

use App\supplierbalancerpt;
use Illuminate\Http\Request;
use DB;
class SupplierbalancerptController extends Controller
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

        $this->data['supplier_id']=$this->jcombo('m_supplier_t','supplier_id','supplier_name','');
		    return view('supplierbalancerpt.supplierbalance', $this->data);       
   
     }


    public function supplierbalance(Request $request){


	  $wh='';
      require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : null;
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : null;
    $supplier = $request->supplier_id ?? null;
      
    $this->data['start_date']=$start_date;
    $this->data['end_date']=$end_date;
		
		
    $this->data['supplier_dat']=\DB::select("select m_supplier_t.supplier_name,m_supplier_sites_t.supplier_site_name,m_supplier_sites_t.address,
(SELECT m_cities_t.city_name from m_cities_t where m_cities_t.city_id=m_supplier_sites_t.city) as city_name,
(SELECT m_countries_t.country_name from m_countries_t WHERE m_countries_t.country_id=m_supplier_sites_t.country) as country_name,
(SELECT m_states_t.state_name from m_states_t WHERE m_states_t.state_id=m_supplier_sites_t.state) as state_name,m_supplier_sites_t.pincode
from m_supplier_t 
left join m_supplier_sites_t on m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id where m_supplier_t.active='Yes'  and m_supplier_t.supplier_id='$supplier' limit 1");


        $overall_data=\DB::select("select f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_t.journal_date,CONCAT(LEFT(MONTHNAME(f_journal_entry_t.journal_date),3),'-',YEAR(f_journal_entry_t.journal_date))as monyr,CASE WHEN MONTH(f_journal_entry_t.journal_date)>=4 THEN concat(YEAR(f_journal_entry_t.journal_date), '-',YEAR(f_journal_entry_t.journal_date)+1) ELSE concat(YEAR(f_journal_entry_t.journal_date)-1,'-', YEAR(f_journal_entry_t.journal_date)) END AS financial_year FROM f_journal_entry_lines_t JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id where f_journal_entry_lines_t.reference_source='supplier' and f_journal_entry_lines_t.reference_id='$supplier' and f_journal_entry_lines_t.journal_date BETWEEN '$start_date' and '$end_date' order by f_journal_entry_t.journal_date asc");
        $balance= \DB::select("select sum(f.credit-f.debit)as balance from( select COALESCE(SUM(debit_amount),0) as debit,COALESCE(SUM(credit_amount),0)as credit from f_journal_entry_lines_t where f_journal_entry_lines_t.reference_source='SUPPLIER' and f_journal_entry_lines_t.reference_id='$supplier' and f_journal_entry_lines_t.journal_date < '$start_date')f");
        $sup_balance=\DB::select("select COALESCE(sum(p_po_invoice_hdr_t.invoice_grand_total),0) as balance_amount from p_po_invoice_hdr_t where p_po_invoice_hdr_t.supplier_id='$supplier' and p_po_invoice_hdr_t.created_by='' and p_po_invoice_hdr_t.invoice_date < '$start_date'");
        $sup_balanc=\DB::select("select COALESCE(sum(f_expenses_t.expense_amount),0) as balance_amount1 from f_expenses_t where f_expenses_t.supplier_id='$supplier' and f_expenses_t.expense_no='Opening Balance' and f_expenses_t.bill_date < '$start_date'");

                 
  $balance=round($balance[0]->balance+$sup_balance[0]->balance_amount+$sup_balanc[0]->balance_amount1,2);
  $overall_datas[0] = (object)array();
        if($balance>0)
        {     
          $overall_datas[0]->balance=$balance*-1;

  $overall_datas[0]->debit_amounts=0;
  $overall_datas[0]->credit_amounts=$balance;
  $overall_datas[0]->net_salary=0;
  $overall_datas[0]->journal_date=$start_date;
  $overall_datas[0]->monyr='';
  $overall_datas[0]->financial_year='';
  $overall_datas[0]->journal_type="Opening Balance";
  $overall_datas[0]->journal_name="Opening Balance";
  $overall_datas[0]->name="Opening Balance";
  $overall_datas[0]->concatenated_segments='';
   $balance=$balance*-1;
        }
        else
        {

 $overall_datas[0]->balance=$balance*-1;
        
  $overall_datas[0]->debit_amounts=$balance*-1;
  $balance=$balance*-1;
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
$key=1;

foreach($overall_data as $ke=>$value)
{
	$id=$value->journal_entry_id;
if($value->journal_type=="PO INVOICE")
{
	$datas=\DB::select("select 0 as debit_amount,round(sum(credit_amount),2) as credit_amount,account_id,concatenated_segments,account_name from((SELECT sum(debit_amount) as debit_amount , 0 as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id') union all (SELECT sum(debit_amount) as debit_amount , credit_amount as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.reference_source='SUPPLIER'))v1");
}
elseif($value->journal_type=="RECEIPT" || $value->journal_type=="DIRECTRECEIPTS" || $value->journal_type=="REVERSE")
{
	$datas=\DB::select("SELECT 0 as debit_amount , round(sum(debit_amount),2) as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");

}
elseif($value->journal_type=="EXPENSES")
{

	$datas=\DB::select("select 0 as debit_amount,round(sum(credit_amount),2) as credit_amount,account_id,concatenated_segments,account_name from((SELECT sum(debit_amount) as debit_amount , 0 as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id') union all (SELECT sum(debit_amount) as debit_amount , credit_amount as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.reference_source='SUPPLIER'))v1");

}
elseif($value->journal_type=="DEBIT NOTE" && str_contains($value->journal_name, 'POINVOICE'))
{
    $datas=\DB::select("SELECT sum(debit_amount) as debit_amount , 0 as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.reference_source='SUPPLIER'");

}
elseif($value->journal_type=="PAYMENT" || $value->journal_type=="ADVANCE PAYMENT" || $value->journal_type=="DEBIT" || $value->journal_type=="DEBIT NOTE" || $value->journal_type=="DIRECTPAYMENT")
{
	$datas=\DB::select("SELECT sum(credit_amount) as debit_amount , 0 as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount=0");

}
elseif($value->journal_type=="CREDIT")
{
	$datas=\DB::select("SELECT 0 as debit_amount , round(sum(debit_amount),2) as credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");

}elseif($value->journal_type=="MANUAL" )
{

$datas=\DB::select("SELECT debit_amount , credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and reference_source !='SUPPLIER' ");

$da=\DB::select("SELECT debit_amount , credit_amount ,account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id'  and f_journal_entry_lines_t.reference_source='SUPPLIER' ");
  
if(isset($da[0]))
{
if($da[0]->debit_amount >0.0)
{

  foreach($datas as $k1=>$v)
{
  
  $datas[$k1]->debit_amount=$v->credit_amount;
  $datas[$k1]->credit_amount=0;

}
}
else
{
    foreach($datas as $k1=>$v)
{
  $datas[$k1]->credit_amount=$v->debit_amount;
  $datas[$k1]->debit_amount=0;
  
}
  
} 
}
}

$overall_datas[$key] = (object)array();

if (!empty($datas) && isset($datas[0]->debit_amount) && $datas[0]->debit_amount > 0) 
{ 
  $overall_datas[$key]->concatenated_segments=$datas[0]->concatenated_segments;  
  $overall_datas[$key]->balance=round($balance+$datas[0]->debit_amount,2);
  $balance=$balance+$datas[0]->debit_amount;
  $overall_datas[$key]->debit_amounts=$datas[0]->debit_amount;
  $overall_datas[$key]->net_salary=0;
  $overall_datas[$key]->credit_amounts=0;
  $overall_datas[$key]->journal_date=$value->journal_date;
  $overall_datas[$key]->monyr=$value->monyr;
  $overall_datas[$key]->financial_year=$value->financial_year;
  $overall_datas[$key]->journal_type=$value->journal_type;
  $overall_datas[$key]->journal_name=$value->journal_name;
  $overall_datas[$key]->name=$datas[0]->account_name;
}else{
  $overall_datas[$key]->concatenated_segments=$datas[0]->concatenated_segments;
  $overall_datas[$key]->balance=round($balance-$datas[0]->credit_amount,2);
  $balance=$balance-$datas[0]->credit_amount; 
  $overall_datas[$key]->credit_amounts=$datas[0]->credit_amount;
  $overall_datas[$key]->net_salary=0;
  $overall_datas[$key]->journal_date=$value->journal_date;
  $overall_datas[$key]->monyr=$value->monyr;
  $overall_datas[$key]->debit_amounts=0;
  $overall_datas[$key]->financial_year=$value->financial_year;
  $overall_datas[$key]->journal_type=$value->journal_type;
  $overall_datas[$key]->journal_name=$value->journal_name;
  $overall_datas[$key]->name=$datas[0]->account_name;
}
$key++;
}

 if(isset($_GET['print']))
    {
        
        $this->data['results'] =$overall_datas;
        $this->data['ddd'] ="Dssd";
        return view('supplierbalancerpt.print',$this->data) ;
    }


              $result = $overall_datas;

		return response()->json(['data' => $result]);

}

public function getCurrentSupplierBalance(Request $request)
{
    $supplier_id = $request->supplier_id;

    $balance = \DB::table('f_journal_entry_lines_t')
        ->where('reference_source', 'SUPPLIER')
        ->where('reference_id', $supplier_id)
        ->selectRaw('COALESCE(SUM(credit_amount - debit_amount),0) as balance')
        ->value('balance');

    return response()->json([
        'balance' => round($balance, 2)
    ]);
}	
	
}
