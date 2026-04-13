<?php

namespace App\Http\Controllers;

use App\purchasetaxaccountrpt;
use Illuminate\Http\Request,DB;;
use yajra\datatables\datatables;

class PurchasetaxaccountrptController extends Controller
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
        return view('purchasetaxaccountrpt.potaxacctable', $this->data);
    }
  
	
     public function potaxaccount(Request $request)
	{

		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (SELECT
    f_journal_entry_lines_t.journal_date,
    f_journal_entry_lines_t.account_id,
    f_journal_entry_lines_t.debit_amount,
    f_journal_entry_t.journal_reference,
    p_po_invoice_hdr_t.bill_number,
    f_account_structure_t.concatenated_segments,
    f_account_codes_lines_t.account_code_meaning
FROM
    f_journal_entry_lines_t
LEFT JOIN f_journal_entry_t ON(
        f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    )
LEFT JOIN f_account_structure_t ON
    (
        f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
    )
LEFT JOIN p_po_invoice_hdr_t ON(
        p_po_invoice_hdr_t.po_invoice_id = f_journal_entry_t.journal_reference
    )
RIGHT JOIN m_tax_group_lines_t taxgrouplines ON
    (
        taxgrouplines.input_tax_account_id = f_journal_entry_lines_t.account_id
    )
LEFT JOIN f_account_codes_lines_t ON(
        f_account_codes_lines_t.account_codes_line_id = f_account_structure_t.future_reference2
    )
WHERE
    f_journal_entry_lines_t.account_id = taxgrouplines.input_tax_account_id AND f_journal_entry_t.journal_type = 'PO INVOICE' AND f_journal_entry_t.journal_date BETWEEN ? AND ?
GROUP BY taxgrouplines.input_tax_account_id) AS v1";

   $results = \DB::select($SQL, [$start_date,$end_date]);

   return DataTables::of($results)->make(true);
}

}
