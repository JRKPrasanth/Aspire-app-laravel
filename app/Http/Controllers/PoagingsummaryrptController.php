<?php
namespace App\Http\Controllers;
use App\Poagingsummaryrpt;
use Illuminate\Http\Request;

class PoagingsummaryrptController extends Controller
{
    public $module="poagingsummaryrpt";
	public function __construct()
	{
		$this->data=array();
        $this->data=array();
		$this->pageModule="poagingsummaryrpt";
        $this->model=new Poagingsummaryrpt();
		$this->model=new Poagingsummaryrpt;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
        $this->data['urlmenu']=$this->indexs(); 
              	}
    public function index()
    {

    	if(isset($_GET['to_date'])){
    			//$from_date=date('Y-m-d',strtotime($_GET['from_date']));
    			$to_date=date('Y-m-d',strtotime($_GET['to_date']));
    	}else{
    		//	$from_date='2019-04-01';
    			$to_date=date('Y-m-d');
    	}
       $this->data['supplieropt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name'); 
        $this->data['pageMethod']='poagingsummaryrpt'; 
		      $SQL = "select sum(exp_amt) as exp_amt,sum(debit_amt) as debit_amt,sum(credit_amt) as credit_amt,sum(total_blnce) as total_blnce,supplier_id,supplier_name,sum(advanceamount) as advance_amount
		       from(
		       SELECT  0 as exp_amt,0 as debit_amt,0 as credit_amt,ROUND(SUM(p_po_invoice_hdr_t.balance_amount),2) as total_blnce,p_po_invoice_hdr_t.supplier_id,m_supplier_t.supplier_name,0 as advanceamount
		        FROM m_supplier_t left join p_po_invoice_hdr_t on p_po_invoice_hdr_t.supplier_id=m_supplier_t.supplier_id where po_invoice_status='APPROVED' and invoice_date <= '$to_date' group by p_po_invoice_hdr_t.supplier_id 
		        union all 
		         SELECT  0 as exp_amt,0 as debit_amt,0 as credit_amt,0 as total_blnce,m_supplier_t.supplier_id,m_supplier_t.supplier_name,round(sum(balance_amount),2)as advanceamount FROM m_supplier_t left join p_po_hdr_t on p_po_hdr_t.supplier_id=m_supplier_t.supplier_id and advance_amount!=0 and advance_status=0  and po_date <= '$to_date' group by m_supplier_t.supplier_id
		 union all 
		 SELECT 0 as exp_amt,0 as debit_amt,round(sum(f_debitcredit_t.balance_amount),2)as credit_amt,0 as total_blnce,m_supplier_t.supplier_id,m_supplier_t.supplier_name,0 as advanceamount  FROM m_supplier_t left join f_debitcredit_t on f_debitcredit_t.supplier_id=m_supplier_t.supplier_id and f_debitcredit_t.source_type='CREDIT' and debitcredit_date <= '$to_date' group by m_supplier_t.supplier_id
		 union all 
		 SELECT 0 as exp_amt,round(sum(f_debitcredit_t.balance_amount),2) as debit_amt,0 as credit_amt, 0 as total_blnce,m_supplier_t.supplier_id,m_supplier_t.supplier_name,0 as advanceamount  FROM m_supplier_t left join f_debitcredit_t on f_debitcredit_t.supplier_id=m_supplier_t.supplier_id and f_debitcredit_t.source_type='DEBIT'  and debitcredit_date <= '$to_date' group by m_supplier_t.supplier_id
		 union all 
	SELECT  round(sum(f_expenses_t.balance_amount),2) as exp_amt,0 as debit_amt,0 as credit_amt, 0 as total_blnce,m_supplier_t.supplier_id,m_supplier_t.supplier_name,0 as advanceamount  FROM m_supplier_t left join f_expenses_t on f_expenses_t.supplier_id=m_supplier_t.supplier_id and f_expenses_t.expense_status='APPROVED' and expense_date <= '$to_date' group by m_supplier_t.supplier_id	 
		     )v1 group by v1.supplier_id";

		$result = \DB::select( $SQL );
		//dd($result);
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
		  //	dd($value);
			$current_date=date('Y-m-d');   
           // $current_date="\"".$currentdate."\"";
            $date1=date('Y-m-d');

$curr_date=date('Y-m-d', strtotime($date1. ' + 15 day'));
//$curr_date=date_format($date1,"Y-m-d");
//echo date_format($date,"Y-m-d");
            $purchase_inv=\DB::select("SELECT ROUND(SUM(p_po_invoice_hdr_t.balance_amount),2) as balance_amount FROM p_po_invoice_hdr_t where  DATEDIFF('$current_date',due_date)<=0 and supplier_id='$value->supplier_id' and po_invoice_status='APPROVED' and invoice_date <= '$to_date'
            union all
            SELECT ROUND(SUM(p_po_invoice_hdr_t.balance_amount),2)  as balance_amount FROM p_po_invoice_hdr_t where DATEDIFF('$current_date',due_date)<16 and DATEDIFF('$current_date',due_date)>0 and supplier_id='$value->supplier_id'  and po_invoice_status='APPROVED' and invoice_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(p_po_invoice_hdr_t.balance_amount),2)  as balance_amount  FROM p_po_invoice_hdr_t where DATEDIFF('$current_date',due_date)<31 and DATEDIFF('$current_date',due_date)>15 and supplier_id='$value->supplier_id'  and po_invoice_status='APPROVED' and invoice_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(p_po_invoice_hdr_t.balance_amount),2) as balance_amount FROM p_po_invoice_hdr_t where DATEDIFF('$current_date',due_date)<46 and DATEDIFF('$current_date',due_date)>30 and supplier_id='$value->supplier_id'  and po_invoice_status='APPROVED'  and invoice_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(p_po_invoice_hdr_t.balance_amount),2)  as balance_amount FROM p_po_invoice_hdr_t where DATEDIFF('$current_date',due_date)>45 and supplier_id='$value->supplier_id'  and po_invoice_status='APPROVED' and invoice_date <= '$to_date' ");   
            //dd($purchase_inv);
			

			$debit_inv=\DB::select("SELECT ROUND(SUM(f_debitcredit_t.balance_amount),2) as balance_amount FROM f_debitcredit_t where  DATEDIFF('$current_date',debitcredit_date)<=0 and supplier_id='$value->supplier_id' and debitcredit_status='APPROVED' and source_type='DEBIT' and debitcredit_date <= '$to_date'
            union all
            SELECT ROUND(SUM(f_debitcredit_t.balance_amount),2) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<16 and DATEDIFF('$current_date',debitcredit_date)>0 and supplier_id='$value->supplier_id' and debitcredit_status='APPROVED' and source_type='DEBIT' and debitcredit_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(f_debitcredit_t.balance_amount),2) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<31 and DATEDIFF('$current_date',debitcredit_date)>15 and supplier_id='$value->supplier_id' and debitcredit_status='APPROVED' and source_type='DEBIT' and debitcredit_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(f_debitcredit_t.balance_amount),2) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<46 and DATEDIFF('$current_date',debitcredit_date)>30 and supplier_id='$value->supplier_id' and debitcredit_status='APPROVED' and source_type='DEBIT' and debitcredit_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(f_debitcredit_t.balance_amount),2) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)>45 and supplier_id='$value->supplier_id' and debitcredit_status='APPROVED' and source_type='DEBIT' and debitcredit_date <= '$to_date' ");

            $credit_inv=\DB::select("SELECT ROUND(SUM(f_debitcredit_t.balance_amount),2) as balance_amount FROM f_debitcredit_t where  DATEDIFF('$current_date',debitcredit_date)<=0 and supplier_id='$value->supplier_id' and debitcredit_status='APPROVED' and source_type='CREDIT' and debitcredit_date <= '$to_date'
            union all
            SELECT ROUND(SUM(f_debitcredit_t.balance_amount),2) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<16 and DATEDIFF('$current_date',debitcredit_date)>0 and supplier_id='$value->supplier_id' and debitcredit_status='APPROVED' and source_type='CREDIT' and debitcredit_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(f_debitcredit_t.balance_amount),2) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<31 and DATEDIFF('$current_date',debitcredit_date)>15 and supplier_id='$value->supplier_id' and debitcredit_status='APPROVED' and source_type='CREDIT' and debitcredit_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(f_debitcredit_t.balance_amount),2) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)<46 and DATEDIFF('$current_date',debitcredit_date)>30 and supplier_id='$value->supplier_id' and debitcredit_status='APPROVED' and source_type='CREDIT' and debitcredit_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(f_debitcredit_t.balance_amount),2) as balance_amount FROM f_debitcredit_t where DATEDIFF('$current_date',debitcredit_date)>45 and supplier_id='$value->supplier_id' and debitcredit_status='APPROVED' and source_type='CREDIT' and debitcredit_date <= '$to_date' ");


            $expan_inv=\DB::select("SELECT ROUND(SUM(f_expenses_t.balance_amount),2) as balance_amount FROM f_expenses_t where  DATEDIFF('$curr_date',bill_date)<=0 and supplier_id='$value->supplier_id' and expense_status='APPROVED' and bill_date <= '$to_date'
            union all
            SELECT ROUND(SUM(f_expenses_t.balance_amount),2) as balance_amount FROM f_expenses_t where DATEDIFF('$curr_date',bill_date)<16 and DATEDIFF('$curr_date',bill_date)>0 and supplier_id='$value->supplier_id' and expense_status='APPROVED' and bill_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(f_expenses_t.balance_amount),2) as balance_amount FROM f_expenses_t where DATEDIFF('$curr_date',bill_date)<31 and DATEDIFF('$curr_date',bill_date)>15 and supplier_id='$value->supplier_id' and expense_status='APPROVED' and bill_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(f_expenses_t.balance_amount),2) as balance_amount FROM f_expenses_t where DATEDIFF('$curr_date',bill_date)<46 and DATEDIFF('$curr_date',bill_date)>30 and supplier_id='$value->supplier_id' and expense_status='APPROVED' and bill_date <= '$to_date'
            UNION ALL
            SELECT ROUND(SUM(f_expenses_t.balance_amount),2) as balance_amount FROM f_expenses_t where DATEDIFF('$curr_date',bill_date)>45 and supplier_id='$value->supplier_id' and expense_status='APPROVED' and bill_date <= '$to_date' ");

		$data[$key]['supplier_id']=$value->supplier_id;
			$data[$key]['supplier_name']=$value->supplier_name;
			if($value->advance_amount!=''){
				$advance_amount=$value->advance_amount;
			}else{
			  	$advance_amount=0;
			}
			  $data[$key]['advance_amount']=$value->advance_amount;
			  $data[$key]['total']=$value->total_blnce-$advance_amount-$value->debit_amt+$value->credit_amt+$value->exp_amt;
			  $data[$key]['current']=$purchase_inv[0]->balance_amount-$debit_inv[0]->balance_amount+$credit_inv[0]->balance_amount+$expan_inv[0]->balance_amount;
			  $data[$key]['lessthan16']=$purchase_inv[1]->balance_amount-$debit_inv[1]->balance_amount+$credit_inv[1]->balance_amount+$expan_inv[1]->balance_amount;
			  $data[$key]['lessthan31']=$purchase_inv[2]->balance_amount-$debit_inv[2]->balance_amount+$credit_inv[2]->balance_amount+$expan_inv[2]->balance_amount;
			  $data[$key]['lessthan46']=$purchase_inv[3]->balance_amount-$debit_inv[3]->balance_amount+$credit_inv[3]->balance_amount+$expan_inv[3]->balance_amount;
			  $data[$key]['above46']=$purchase_inv[4]->balance_amount-$debit_inv[4]->balance_amount+$credit_inv[4]->balance_amount+$expan_inv[4]->balance_amount;
			  
			  $total=$total+$value->total_blnce-$value->debit_amt+$value->credit_amt+$value->exp_amt-$data[$key]['advance_amount'];
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
       return view('poagingsummaryrpt.table',$this->data);
    }   
}
