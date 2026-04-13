<?php

namespace App\Http\Controllers;

use App\salestaxaccountrpt;
use Illuminate\Http\Request,DB;
use yajra\datatables\datatables;

class SalestaxaccountrptController extends Controller
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
        return view('salestaxaccountrpt.sotaxacctable',$this->data);
    }


	  public function salestaxaccount(Request $request)
	{

		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (SELECT
    f_journal_entry_lines_t.journal_date,
    f_journal_entry_lines_t.account_id,
    f_journal_entry_lines_t.credit_amount,
    f_journal_entry_t.journal_reference,
    s_invoice_hdr_t.invoice_number,
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
LEFT JOIN s_invoice_hdr_t ON(
        s_invoice_hdr_t.invoice_hdr_id = f_journal_entry_t.journal_reference
    )
RIGHT JOIN m_tax_group_lines_t taxgrouplines ON
    (
        taxgrouplines.output_tax_account_id = f_journal_entry_lines_t.account_id
    )
LEFT JOIN f_account_codes_lines_t ON(
        f_account_codes_lines_t.account_codes_line_id = f_account_structure_t.future_reference2
    )
WHERE
    f_journal_entry_lines_t.journal_date BETWEEN ? AND ? AND f_journal_entry_t.journal_type = 'SALES INVOICE' AND f_journal_entry_lines_t.account_id = taxgrouplines.output_tax_account_id
GROUP BY
    taxgrouplines.output_tax_account_id) AS v1";

   $results = \DB::select($SQL, [$start_date,$end_date]);

   return DataTables::of($results)->make(true);
}


}
