<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\expensesrpt;
use Illuminate\Http\Request;

class ExpensesrptController extends Controller
{
  public function __construct()
  {
    $this->data = array();
    $this->data['urlmenu'] = $this->indexs();
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['pageFormtype'] = 'ajax';
  }

  public function index()
  {
    return view('expensesreport.table', $this->data);
  }

  public function empexpenseindex()
  {
    return view('expensesreport.multipleemptable', $this->data);
  }

  public function creditdebitnoteindex()
  {
    return view('expensesreport.creditdebitnotetable', $this->data);
  }
  public function consolidatedempexpenseindex()
  {
    return view('expensesreport.consolidatedemptable', $this->data);
  }

  public function getexpenserptdata(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "select * from(SELECT f_expenses_t.expense_id,
                    f_expenses_t.expense_no,
                    f_expenses_t.expense_date,
                    f_expenses_t.expense_type,
                    f_expenses_t.invoice,
                    f_expenses_t.bill_date,
                    f_expenses_t.tds_applicable,
                    f_expenses_t.tds_prcnt,
                    f_expenses_t.tds_amount,
                    f_expenses_t.round_off,
					round((f_expenses_t.tds_prcnt*f_expenses_lines_t.expense_line_amount)/100,2) as tdsamount,
                    f_expenses_t.tax_amount,
                    f_expenses_t.expense_status,
                    accountstructure_hdr.concatenated_segments,
                    accountstructure_lines.concatenated_segments as accountstructure,
                    accountstructure_lines.account_name as accountname,
                    m_supplier_t.supplier_name,
                    m_supplier_t.supplier_number,
                    f_expenses_t.expense_amount,
                    tb_users.username,
                    hr_employee_t.employee_number,
                    hr_employee_t.first_name,
                    a_lookuplines_t.lookup_code as emp_type,
                    m_supplier_sites_t.gst_number,
                    m_customers_t.customer_number,
                    m_customers_t.customer_name,
                    m_customer_sites_t.gst_no,
                    if (f_expenses_t.reverse_charge=1,'Yes','no') as reverse_charge,
                    f_expenses_lines_t.remarks,
                    f_expenses_lines_t.created_at,
                    f_expenses_lines_t.updated_at,
                    f_expenses_lines_t.tax_amount as taxamount,
                    f_expenses_lines_t.expense_line_amount,
                    m_tax_group_t.tax_group_name,
                    f_gst_code_hdr_t.classification_code,
                    '' as cgst,
                    '' as sgst,
                    '' as igst
                    FROM f_expenses_t
                    left join f_expenses_lines_t on(f_expenses_lines_t.expense_id=f_expenses_t.expense_id)
                    left join m_tax_group_t on(m_tax_group_t.tax_group_id=f_expenses_lines_t.tax_group_id)
                    left join f_gst_code_hdr_t on(f_gst_code_hdr_t.gst_code_hdr_id=f_expenses_lines_t.gst_code_id)
                    left join f_account_structure_t as accountstructure_hdr  on(accountstructure_hdr.f_account_structure_id=f_expenses_t.tds_account_id) 
                    left join f_account_structure_t as accountstructure_lines  on(accountstructure_lines.f_account_structure_id=f_expenses_lines_t.expense_account_id)
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
                    left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                    left join hr_employee_t on(hr_employee_t.employee_id=f_expenses_t.employee_id)
                    left join a_lookuplines_t on(hr_employee_t.employee_type=a_lookuplines_t.lookuplines_id)
                    left join m_customers_t on(m_customers_t.customer_id=f_expenses_t.customer_id)
                    left join m_customer_sites_t on(m_customer_sites_t.customer_id=m_customers_t.customer_id and m_customer_sites_t.primary_address='YES' and m_customer_sites_t.site_type = 'BILL_TO')
                    left join tb_users on (tb_users.id =f_expenses_t.created_by)
                    where 1=1 and f_expenses_t.expense_status='APPROVED' and f_expenses_t.expense_date BETWEEN '$start_date' and '$end_date') as v1";

    $result = \DB::select($SQL);

    foreach ($result as $key => $value) {

      if ($result[$key]->tds_applicable == "YES") {
        $prcnt = $result[$key]->tds_prcnt;
        $amttds = $result[$key]->expense_line_amount;
        $tdsaamt = (($amttds * $prcnt) / 100);
        $result[$key]->tdsaamt = round($tdsaamt, 2);
        //dd($tdsaamt);
      } else {
        $result[$key]->tdsaamt = '';
      }
      $first = substr($result[$key]->tax_group_name, 0, 3);

      if ($first == "GST") {

        $rate = ($result[$key]->taxamount / 2);
        $result[$key]->cgst = $rate;
        $result[$key]->sgst = $rate;
        $result[$key]->igst = '';
      } elseif ($first == "IGS") {
        $result[$key]->igst = $result[$key]->taxamount;
        $result[$key]->cgst = '';
        $result[$key]->sgst = '';
      } else {

      }
    }

    return response()->json(['data' => $result]);

  }


  public function getmultipleempexpenserptdata(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "select * from(SELECT f_emp_expenses_t.expense_id,
                    f_emp_expenses_t.expense_no,
                    f_emp_expenses_t.expense_date,
                    f_emp_expenses_lines_t.bill_no,
                    f_emp_expenses_lines_t.bill_date,
                    f_emp_expenses_t.round_off,
                    f_emp_expenses_t.expense_status,
                    f_emp_expenses_t.created_at,
                    accountstructure_lines.concatenated_segments as accountstructure,
                    accountstructure_lines.account_name as accountname,
                    f_emp_expenses_t.expense_amount,
                    tb_users.username,
                    hr_employee_t.employee_number,
                    hr_employee_t.first_name,
                    a_lookuplines_t.lookup_code as emp_type,
                    if (f_emp_expenses_lines_t.payment_status=1,'Paid','Payment Pending') as payment_status,
                    f_emp_expenses_lines_t.remarks,
                    f_emp_expenses_lines_t.expense_line_amount
                    FROM  f_emp_expenses_lines_t
                    left join f_emp_expenses_t on(f_emp_expenses_lines_t.expense_id=f_emp_expenses_t.expense_id)
                    left join f_account_structure_t as accountstructure_lines  on(accountstructure_lines.f_account_structure_id=f_emp_expenses_lines_t.expense_account_id)
                    left join hr_employee_t on(hr_employee_t.employee_id=f_emp_expenses_lines_t.employee_id)
                    left join a_lookuplines_t on(hr_employee_t.employee_type=a_lookuplines_t.lookuplines_id)
                    left join tb_users on (tb_users.id =f_emp_expenses_t.created_by)
                    where 1=1 and f_emp_expenses_t.expense_status='APPROVED' and f_emp_expenses_t.expense_date BETWEEN ? and ?) as v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);

  }

  public function getcreditdebitnoterptdata(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';



    $SQL = "select * from(SELECT
    f_debitcredit_t.debitcredit_id,
    f_debitcredit_t.debitcredit_no,
    f_debitcredit_t.source_type,
    f_debitcredit_t.debitcredit_date,
    f_debitcredit_t.debitcredit_type,
    f_debitcredit_t.debitcredit_amount,
    f_debitcredit_t.debitcredit_status,
    f_debitcredit_t.reference_no,
        f_debitcredit_t.credit_taken,
    CONCAT(LEFT(MONTHNAME(f_debitcredit_t.credit_date),3),'',YEAR(f_debitcredit_t.credit_date))as credit_date,
    f_debitcredit_t.balance_amount,
    f_debitcredit_t.paid_amount,
    m_supplier_t.supplier_name,
    m_customers_t.customer_name,
    (case when f_debitcredit_t.customer_id > 0 then
    s_invoice_hdr_t.invoice_number
    else
    p_po_invoice_hdr_t.bill_number
    end) as invoice_number,
    f_debitcredit_lines_t.description,
    f_debitcredit_lines_t.tax_amount,
    f_debitcredit_lines_t.remarks,
    f_debitcredit_lines_t.debitcredit_line_amount,
    f_account_structure_t.concatenated_segments,
    f_gst_code_hdr_t.classification_code,
    m_tax_group_t.tax_group_name
FROM
    f_debitcredit_t
LEFT JOIN f_debitcredit_lines_t ON f_debitcredit_t.debitcredit_id = f_debitcredit_lines_t.debitcredit_id
LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = f_debitcredit_t.supplier_id
LEFT JOIN m_customers_t ON m_customers_t.customer_id = f_debitcredit_t.customer_id
LEFT JOIN s_invoice_hdr_t ON s_invoice_hdr_t.invoice_hdr_id = f_debitcredit_t.invoice_no
LEFT JOIN p_po_invoice_hdr_t ON p_po_invoice_hdr_t.po_invoice_id = f_debitcredit_t.invoice_no
LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_debitcredit_lines_t.debitcredit_account_id
LEFT JOIN f_gst_code_hdr_t ON f_gst_code_hdr_t.gst_code_hdr_id = f_debitcredit_lines_t.gst_code_id
LEFT JOIN m_tax_group_t ON m_tax_group_t.tax_group_id = f_debitcredit_lines_t.tax_group_id where f_debitcredit_t.debitcredit_date BETWEEN ? and ?) as v1";


    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);

  }


  public function credittakenexpenseindex()
  {
    return view('expensesreport.credittakenexpenserpt', $this->data);
  }


  public function getcredittakenexpense(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "select * from(SELECT f_expenses_t.expense_id,
                    f_expenses_t.expense_no,
                    f_expenses_t.expense_date,
                    f_expenses_t.expense_type,
                    f_expenses_t.invoice,
                    f_expenses_t.bill_date,
                    f_expenses_t.tds_applicable,
                    f_expenses_t.tds_prcnt,
                    f_expenses_t.tds_amount,
                    f_expenses_t.round_off,
					round((f_expenses_t.tds_prcnt*f_expenses_lines_t.expense_line_amount)/100,2) as tdsamount,
                    f_expenses_t.tax_amount,
                    f_expenses_t.expense_status,
                    accountstructure_hdr.concatenated_segments,
                    accountstructure_lines.concatenated_segments as accountstructure,
                    accountstructure_lines.account_name as accountname,
                    m_supplier_t.supplier_name,
                    m_supplier_t.supplier_number,
                    f_expenses_t.expense_amount,
                    f_expenses_t.credit_taken,
                    CONCAT(LEFT(MONTHNAME(f_expenses_t.credit_date),3),'-',YEAR(f_expenses_t.credit_date))as credit_date,
                    tb_users.username,
                    hr_employee_t.employee_number,
                    hr_employee_t.first_name,
                    a_lookuplines_t.lookup_code as emp_type,
                    m_supplier_sites_t.gst_number,
                    m_customers_t.customer_number,
                    m_customers_t.customer_name,
                    m_customer_sites_t.gst_no,
                    if (f_expenses_t.reverse_charge=1,'Yes','no') as reverse_charge,
                    f_expenses_lines_t.remarks,
                    f_expenses_lines_t.created_at,
                    f_expenses_lines_t.updated_at,
                    f_expenses_lines_t.tax_amount as taxamount,
                    f_expenses_lines_t.expense_line_amount,
                    m_tax_group_t.tax_group_name,
                    f_gst_code_hdr_t.classification_code
                    FROM f_expenses_t
                    left join f_expenses_lines_t on(f_expenses_lines_t.expense_id=f_expenses_t.expense_id)
                    left join m_tax_group_t on(m_tax_group_t.tax_group_id=f_expenses_lines_t.tax_group_id)
                    left join f_gst_code_hdr_t on(f_gst_code_hdr_t.gst_code_hdr_id=f_expenses_lines_t.gst_code_id)
                    left join f_account_structure_t as accountstructure_hdr  on(accountstructure_hdr.f_account_structure_id=f_expenses_t.tds_account_id) 
                    left join f_account_structure_t as accountstructure_lines  on(accountstructure_lines.f_account_structure_id=f_expenses_lines_t.expense_account_id)
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
                    left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                    left join hr_employee_t on(hr_employee_t.employee_id=f_expenses_t.employee_id)
                    left join a_lookuplines_t on(hr_employee_t.employee_type=a_lookuplines_t.lookuplines_id)
                    left join m_customers_t on(m_customers_t.customer_id=f_expenses_t.customer_id)
                    left join m_customer_sites_t on(m_customer_sites_t.customer_id=m_customers_t.customer_id and m_customer_sites_t.primary_address='YES' and m_customer_sites_t.site_type = 'BILL_TO')
                    left join tb_users on (tb_users.id =f_expenses_t.created_by)
                    where 1=1 and f_expenses_t.expense_status='APPROVED' AND f_expenses_t.credit_taken ='Yes' and f_expenses_t.credit_date BETWEEN '$start_date' and '$end_date') as v1";

    $result = \DB::select($SQL);

    foreach ($result as $key => $value) {

      if ($result[$key]->tds_applicable == "YES") {
        $prcnt = $result[$key]->tds_prcnt;
        $amttds = $result[$key]->expense_line_amount;
        $tdsaamt = (($amttds * $prcnt) / 100);
        $result[$key]->tdsaamt = round($tdsaamt, 2);
      } else {
        $result[$key]->tdsaamt = '';
      }
      $first = substr($result[$key]->tax_group_name, 0, 3);
      if ($first == "GST") {

        $rate = ($result[$key]->taxamount / 2);
        $result[$key]->cgst = $rate;
        $result[$key]->sgst = $rate;
      } elseif ($first == "IGS") {
        $result[$key]->igst = $result[$key]->taxamount;
      } else {

      }
    }
    return response()->json(['data' => $result]);

  }


  public function getconsolidatedempexpenserptdata(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "select * from(SELECT 
    f_expenses_t.expense_id,
    f_expenses_t.expense_no,
    f_expenses_t.expense_date,
    f_expenses_t.invoice AS bill_no, 
    f_expenses_t.bill_date,
    f_expenses_t.round_off,
    f_expenses_t.expense_status,
    f_expenses_lines_t.created_at,
    f_expenses_lines_t.updated_at,
    f_expenses_t.tds_applicable,
    f_expenses_t.tds_prcnt,
    f_expenses_t.tds_amount,
    ROUND((f_expenses_t.tds_prcnt * f_expenses_lines_t.expense_line_amount) / 100, 2) AS tdsamount,
    accountstructure_hdr.concatenated_segments,
    accountstructure_lines.concatenated_segments AS accountstructure,
    accountstructure_lines.account_name AS accountname,
    f_expenses_t.expense_amount,
    tb_users.username,
    hr_employee_t.employee_number,
    hr_employee_t.first_name,
    a_lookuplines_t.lookup_code AS emp_type,
    IF(f_expenses_t.reverse_charge = 1, 'Yes', 'No') AS reverse_charge,
    f_expenses_lines_t.remarks,
    NULL AS payment_status, 
    f_expenses_lines_t.expense_line_amount
FROM f_expenses_t
LEFT JOIN f_expenses_lines_t ON f_expenses_lines_t.expense_id = f_expenses_t.expense_id
LEFT JOIN f_account_structure_t AS accountstructure_hdr ON accountstructure_hdr.f_account_structure_id = f_expenses_t.tds_account_id
LEFT JOIN f_account_structure_t AS accountstructure_lines ON accountstructure_lines.f_account_structure_id = f_expenses_lines_t.expense_account_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = f_expenses_t.employee_id
LEFT JOIN a_lookuplines_t ON hr_employee_t.employee_type = a_lookuplines_t.lookuplines_id
LEFT JOIN tb_users ON tb_users.id = f_expenses_t.created_by
WHERE f_expenses_t.expense_status = 'APPROVED'
  AND f_expenses_t.expense_type = 'EMPLOYEE'
  AND f_expenses_t.expense_date BETWEEN '$start_date' AND '$end_date'

UNION ALL

SELECT 
    f_emp_expenses_t.expense_id,
    f_emp_expenses_t.expense_no,
    f_emp_expenses_t.expense_date,
    f_emp_expenses_lines_t.bill_no,
    f_emp_expenses_lines_t.bill_date,
    f_emp_expenses_t.round_off,
    f_emp_expenses_t.expense_status,
    f_emp_expenses_t.created_at,
    f_emp_expenses_t.updated_at,
    f_emp_expenses_lines_t.tds_applicable,
    f_emp_expenses_lines_t.tds_percentage AS tds_prcnt,
    f_emp_expenses_lines_t.tds_amount AS tds_amount,
    ROUND((f_emp_expenses_lines_t.tds_percentage * f_emp_expenses_lines_t.expense_line_amount) / 100, 2) AS tdsamount,
    accountstructure_hdr.concatenated_segments,
    accountstructure_lines.concatenated_segments AS accountstructure,
    accountstructure_lines.account_name AS accountname,
    f_emp_expenses_t.expense_amount,
    tb_users.username,
    hr_employee_t.employee_number,
    hr_employee_t.first_name,
    a_lookuplines_t.lookup_code AS emp_type,
    NULL AS reverse_charge,
    f_emp_expenses_lines_t.remarks,
    IF(f_emp_expenses_lines_t.payment_status = 1, 'Paid', 'Payment Pending') AS payment_status,
    f_emp_expenses_lines_t.expense_line_amount
FROM f_emp_expenses_lines_t
LEFT JOIN f_emp_expenses_t ON f_emp_expenses_lines_t.expense_id = f_emp_expenses_t.expense_id
LEFT JOIN f_account_structure_t AS accountstructure_hdr ON accountstructure_hdr.f_account_structure_id = f_emp_expenses_lines_t.tds_account_id
LEFT JOIN f_account_structure_t AS accountstructure_lines ON accountstructure_lines.f_account_structure_id = f_emp_expenses_lines_t.expense_account_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = f_emp_expenses_lines_t.employee_id
LEFT JOIN a_lookuplines_t ON hr_employee_t.employee_type = a_lookuplines_t.lookuplines_id
LEFT JOIN tb_users ON tb_users.id = f_emp_expenses_t.created_by
WHERE f_emp_expenses_t.expense_status = 'APPROVED'
  AND f_emp_expenses_t.expense_date BETWEEN '$start_date' AND '$end_date') as v1";


    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);
  }

}
