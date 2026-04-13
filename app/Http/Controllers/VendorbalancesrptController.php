<?php

namespace App\Http\Controllers;

use App\Vendorbalancesrpt;
use Illuminate\Http\Request;

class VendorbalancesrptController extends Controller
{
    public $module="vendorbalancesrpt";
	public function __construct()
	{
		$this->data=array();
                $this->data['urlmenu']=$this->indexs(); 
		//$this->table="p_payments_t";
		$this->pageModule="vendorbalancesrpt";
                $this->model=new Vendorbalancesrpt();
		$this->model=new Vendorbalancesrpt;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
              //  $this->table="p_payments_t";

	}
    public function index()
    {
        $this->data['supplieropt']=$this->jqgridselect('m_supplier_t','supplier_name','supplier_name'); 
        $this->data['pageMethod']='vendorbalances'; 
		$org=\Session::get('organization');
        $loc="1";
        $compy=\Session::get('companyid');
        
               $SQL = "SELECT 
                m_supplier_t.supplier_id as supplier_ids,
                m_supplier_t.supplier_name as supplier_id,
                p_po_invoice_hdr_t.invoice_date,
                SUM(ROUND(p_po_invoice_hdr_t.invoice_grand_total,2)) as invoice_grand_total,
                SUM(ROUND(p_po_invoice_hdr_t.paid_amount,2))as paid_amount,
                SUM(ROUND(p_po_invoice_hdr_t.balance_amount,2))as balance_amount,
                SUM(p_po_invoice_hdr_t.balance_amount) as total_balance,
                IF(p_po_invoice_hdr_t.paid_amount <= 0,'UnPaid','Partital Paid') AS paid_status
                FROM p_po_invoice_hdr_t 
                left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id) 
                where 1=1  and p_po_invoice_hdr_t.balance_amount!=0 and p_po_invoice_hdr_t.company_id=$compy and p_po_invoice_hdr_t.location_id=$loc GROUP BY p_po_invoice_hdr_t.supplier_id ";
               
		$result = \DB::select( $SQL );
		$this->data['result']=json_encode($result);
		
	
       return view('vendorbalancesrpt.table',$this->data);
    }
	
 /*Purpose for vendor balance pqgrid*/
	
   	public function getvendorbalanceData(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
    $compy=\Session::get('companyid');

    $SQL = "SELECT * from (
                SELECT p_po_invoice_hdr_t.po_invoice_id,
                p_po_invoice_hdr_t.bill_number,
                p_po_invoice_hdr_t.supplier_id,
                m_supplier_t.supplier_name,
                p_po_invoice_hdr_t.invoice_date,
				 ROUND(sum(p_po_invoice_hdr_t.debit_note),2) as debit_note,
               ROUND(sum(p_po_invoice_hdr_t.credit_note),2) as credit_note,
                ROUND(SUM((p_po_invoice_hdr_t.invoice_grand_total)+(p_po_invoice_hdr_t.credit_note)-(p_po_invoice_hdr_t.debit_note)),2) as invoice_grand_total,
                ROUND(SUM(p_po_invoice_hdr_t.paid_amount),2)as paid_amount,
                ((ROUND(SUM(p_po_invoice_hdr_t.balance_amount),2) + sum(p_po_invoice_hdr_t.credit_note)) - sum(p_po_invoice_hdr_t.debit_note)) as balance_amount,
                SUM(p_po_invoice_hdr_t.balance_amount) as total_balance,
                IF(p_po_invoice_hdr_t.paid_amount <= 0,'UnPaid','Partital Paid') AS paid_status
                FROM p_po_invoice_hdr_t 
                left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id) 
                where 1=1 AND p_po_invoice_hdr_t.invoice_date BETWEEN ? AND ? and p_po_invoice_hdr_t.balance_amount!=0 and p_po_invoice_hdr_t.company_id=$compy GROUP BY p_po_invoice_hdr_t.supplier_id) AS v1";

		$results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
         /*END*/
}
