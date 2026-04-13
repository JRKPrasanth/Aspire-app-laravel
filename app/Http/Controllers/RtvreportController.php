<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;

use App\Rtvreport;
use Illuminate\Http\Request;

class RtvreportController extends Controller
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
      return view('rtvreport.rtvreport',$this->data);    
    }

	public function getrtv(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
   SELECT
    p_return_header_t.return_header_id,
    p_return_header_t.return_invoice_number,
    p_return_header_t.return_date,
    p_return_lines_t.qty,
    p_return_lines_t.reject_qty,
    p_return_lines_t.unit_price,
    p_return_lines_t.tax_amount,
    m_tax_group_t.tax_group_name,
    p_return_lines_t.line_total,
    p_return_lines_t.reason,
    p_po_invoice_hdr_t.bill_number,
    p_po_hdr_t.po_number,
    m_products_t.concatenated_product,
    m_supplier_t.supplier_name,
    p_qc_header_t.qc_number,
    p_grn_hdr_t.grn_number,
    p_return_header_t.p_return_status
FROM
    p_return_header_t
LEFT JOIN p_return_lines_t ON p_return_header_t.return_header_id = p_return_lines_t.return_header_id
LEFT JOIN p_po_invoice_hdr_t ON p_return_header_t.invoice_number = p_po_invoice_hdr_t.po_invoice_id
LEFT JOIN p_po_hdr_t ON p_return_header_t.po_number = p_po_hdr_t.po_hdr_id
LEFT JOIN m_products_t ON p_return_lines_t.product_id = m_products_t.product_id
LEFT JOIN m_supplier_t ON p_return_header_t.supplier_id = m_supplier_t.supplier_id
LEFT JOIN p_qc_header_t ON p_return_header_t.qc_id = p_qc_header_t.qc_header_id
LEFT JOIN p_grn_hdr_t ON p_return_header_t.grn_number = p_grn_hdr_t.grn_id
LEFT JOIN m_tax_group_t ON p_return_lines_t.tax_group_id = m_tax_group_t.tax_group_id) AS v1";

    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);
}
	
	
	public function credittakenrtvindex()
    {
      return view('rtvreport.crdttakenrtvreport',$this->data);    
    }

  public function getcredittakenrtv(Request $request) {
	  
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
                
             $SQL = "SELECT
    p_return_header_t.return_header_id,
    p_return_header_t.return_invoice_number,
    p_return_header_t.return_date,
    p_return_header_t.credit_taken,
    CONCAT(LEFT(MONTHNAME(p_return_header_t.credit_date),3),'',YEAR(p_return_header_t.credit_date))as credit_date,
    p_return_lines_t.qty,
    p_return_lines_t.reject_qty,
    p_return_lines_t.unit_price,
    p_return_lines_t.tax_amount,
    m_tax_group_t.tax_group_name,
    p_return_lines_t.line_total,
    p_return_lines_t.reason,
    p_po_invoice_hdr_t.bill_number,
    p_po_hdr_t.po_number,
    m_products_t.concatenated_product,
    m_supplier_t.supplier_name,
    p_qc_header_t.qc_number,
    p_grn_hdr_t.grn_number,
    p_return_header_t.p_return_status
FROM
    p_return_header_t
LEFT JOIN p_return_lines_t ON p_return_header_t.return_header_id = p_return_lines_t.return_header_id
LEFT JOIN p_po_invoice_hdr_t ON p_return_header_t.invoice_number = p_po_invoice_hdr_t.po_invoice_id
LEFT JOIN p_po_hdr_t ON p_return_header_t.po_number = p_po_hdr_t.po_hdr_id
LEFT JOIN m_products_t ON p_return_lines_t.product_id = m_products_t.product_id
LEFT JOIN m_supplier_t ON p_return_header_t.supplier_id = m_supplier_t.supplier_id
LEFT JOIN p_qc_header_t ON p_return_header_t.qc_id = p_qc_header_t.qc_header_id
LEFT JOIN p_grn_hdr_t ON p_return_header_t.grn_number = p_grn_hdr_t.grn_id
LEFT JOIN m_tax_group_t ON p_return_lines_t.tax_group_id = m_tax_group_t.tax_group_id where 1=1  and p_return_header_t.p_return_status='APPROVED' AND p_return_header_t.credit_taken ='Yes' and p_return_header_t.credit_date BETWEEN ? and ? ";
             
    
	$results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
	}
	
}
