<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\DataTables;

class B2breportController extends Controller
{

  public function __construct()
  {

    $this->data = array();
    $this->data['pageMethod'] = \Request::route()->getName();

  }


  public function index()
  {

    return view('b2report.b2breport', $this->data);

  }

  public function getb2breport(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $result1 = \DB::select("SELECT 
        s_invoice_hdr_t.invoice_date,
        s_invoice_hdr_t.invoice_number,
        COALESCE(m_customers_t.customer_name, hr_employee_t.first_name) AS cusname,
        m_customer_sites_t.gst_no,
        CASE 
        WHEN s_invoice_hdr_t.employee_id != '0' THEN (SELECT state_name FROM m_states_t WHERE m_states_t.state_id = hr_emp_contact.current_state) 
        ELSE (SELECT state_name FROM m_states_t WHERE m_states_t.state_id = m_customer_sites_t.state) 
    END AS statename,
    m_tax_group_t.tax_group_name,
    ROUND(SUM(s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount), 2) AS accessablevalu,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM(s_invoice_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS cgst,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM(s_invoice_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS sgst,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN ROUND(SUM(s_invoice_lines_t.tax_amount), 2) 
        ELSE 0 
    END AS igst
FROM 
    s_invoice_lines_t
JOIN 
    s_invoice_hdr_t ON s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id
LEFT JOIN 
    m_customers_t ON m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
LEFT JOIN 
    m_customer_sites_t ON m_customer_sites_t.customer_site_id = s_invoice_hdr_t.bill_to_address_id 
  AND m_customer_sites_t.site_type = 'BILL_TO' AND m_customer_sites_t.active = 'Yes'
LEFT JOIN 
    m_tax_group_t ON m_tax_group_t.tax_group_id = s_invoice_lines_t.tax_group_id
LEFT JOIN 
    hr_employee_t ON hr_employee_t.employee_id = s_invoice_hdr_t.employee_id
LEFT JOIN 
    hr_emp_contact ON hr_emp_contact.employee_id = s_invoice_hdr_t.employee_id
WHERE  
    s_invoice_hdr_t.invoice_date BETWEEN '$start_date' AND '$end_date' AND s_invoice_hdr_t.invoice_type='STANDARD' AND s_invoice_hdr_t.invoice_status ='APPROVED' AND m_customer_sites_t.gst_no !=''
GROUP BY s_invoice_hdr_t.invoice_date,s_invoice_hdr_t.invoice_number,m_customers_t.customer_name ORDER BY s_invoice_hdr_t.invoice_date DESC");

    return response()->json(['data' => $result1]);
  }

  // gst purchase report
  public function index1()
  {

    return view('b2report.gstpurcrpt', $this->data);

  }

  public function getgstpurcreport(Request $request)
  {

    $from = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $to = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $result1 = \DB::select("SELECT 
    v1.bill_number,
    v1.invoice_date,
    v1.supplier_name,
    v1.monyr,
    v1.rcm,
    v1.credit_taken,
    v1.credit_date,
    v1.concatenated_product,
    v1.type,
    v1.gst_number,
    v1.transport_amt,
    v1.tax_value,
    CASE 
        WHEN v1.trans_id = '8' OR v1.unload_id = '8' OR v1.insure_id = '8' OR v1.pack_id = '8' OR v1.otax_id = '8' OR v1.freight_id = '8' 
            THEN v1.tax_value 
        ELSE (v1.tax_value + v1.transport_amt) 
    END AS total_tax_value,
    CASE 
        WHEN v1.trans_id = '8' OR v1.unload_id = '8' OR v1.insure_id = '8' OR v1.pack_id = '8' OR v1.otax_id = '8' OR v1.freight_id = '8' 
            THEN v1.cgst1 
        ELSE (v1.cgst1 + v1.cgst2 + v1.cgst3 + v1.cgst4 + v1.cgst5 + v1.cgst6 + v1.cgst7) 
    END AS cgst,
    CASE 
        WHEN v1.trans_id = '8' OR v1.unload_id = '8' OR v1.insure_id = '8' OR v1.pack_id = '8' OR v1.otax_id = '8' OR v1.freight_id = '8' 
            THEN v1.sgst1 
        ELSE (v1.sgst1 + v1.sgst2 + v1.sgst3 + v1.sgst4 + v1.sgst5 + v1.sgst6 + v1.sgst7) 
    END AS sgst,
        CASE 
        WHEN v1.trans_id = '8' OR v1.unload_id = '8' OR v1.insure_id = '8' OR v1.pack_id = '8' OR v1.otax_id = '8' OR v1.freight_id = '8' 
            THEN v1.igst1 
        ELSE (v1.igst1 + v1.igst2 + v1.igst3 + v1.igst4 + v1.igst5 + v1.igst6 + v1.igst7)
    END AS igst
FROM (SELECT 
        p_po_invoice_hdr_t.bill_number,
        m_supplier_sites_t.gst_number as gst_number,
        m_products_t.concatenated_product,
        'Purchase' as type,
        p_po_invoice_hdr_t.invoice_date,
        m_supplier_t.supplier_name,
        p_po_invoice_hdr_t.credit_taken,
        p_po_invoice_hdr_t.credit_date,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', -1) as transport_amt,
        ROUND((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1)), 2) as transport_tax_value,
        transport_tax_group_t.tax_group_name as transport_tax_group_name,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', 1) as trans_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', 1) as unload_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', 1) as insure_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', 1) as pack_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', 1) as otax_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', 1) as freight_id,
        CASE p_po_hdr_t.reverse_charge
            WHEN 1 THEN 'Yes'
            ELSE 'No'
        END AS rcm,
        CONCAT(LEFT(MONTHNAME(p_grn_hdr_t.grn_date), 3), '-', YEAR(p_grn_hdr_t.grn_date)) as monyr,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM((p_po_invoice_lines_t.tax_amount) / 2), 2)
            ELSE 0 
        END AS cgst1,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst2,          
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.unloading_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst3,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.insurance_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst4,  
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.packing_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst5,  
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst6, 
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.other_freight_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst7,                                                                     
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM((p_po_invoice_lines_t.tax_amount) / 2), 2)
            ELSE 0 
        END AS sgst1,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst2,   
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.unloading_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst3,  
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.insurance_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst4,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.packing_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst5,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst6,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.other_freight_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst7, 
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN ROUND(SUM((p_po_invoice_lines_t.tax_amount)), 2)
            ELSE 0 
        END AS igst1,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst2,   
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.unloading_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst3,  
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.insurance_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst4,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.packing_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst5,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst6,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.other_freight_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst7, 
        m_tax_group_t.tax_group_name as tax_group_name,
        ROUND(SUM((p_po_invoice_lines_t.line_total - p_po_invoice_lines_t.tax_amount)), 2) AS tax_value
    FROM 
        p_po_invoice_hdr_t
    LEFT JOIN 
        p_po_invoice_lines_t ON p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id
    LEFT JOIN 
        m_supplier_t ON m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id
    LEFT JOIN 
        m_tax_group_t as transport_tax_group_t on (transport_tax_group_t.tax_group_id = SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', 1))
    LEFT JOIN 
        p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number
    LEFT JOIN 
        p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number
    LEFT JOIN 
        m_tax_group_t ON m_tax_group_t.tax_group_id = p_po_invoice_lines_t.tax_group_id
    LEFT JOIN 
        m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
    LEFT JOIN 
        m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
    WHERE 
        p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'  
        AND m_products_t.tax_credit = 'yes'
        AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL) 
        AND DATE(p_po_invoice_hdr_t.invoice_date) BETWEEN '$from' and '$to' 
    GROUP BY 
        p_po_invoice_hdr_t.bill_number, p_po_invoice_hdr_t.invoice_date, m_supplier_t.supplier_name, m_tax_group_t.tax_group_name  
    HAVING m_tax_group_t.tax_group_name != 'NO TAX'
UNION ALL
SELECT 
     f_expenses_t.invoice as bill_number,
     m_supplier_sites_t.gst_number as gst_number,
     accountstructure_lines.account_name as concatenated_product,
     'Expense' as type,
     f_expenses_t.bill_date as invoice_date,
     m_supplier_t.supplier_name,
     f_expenses_t.credit_taken,
     f_expenses_t.credit_date,
     '0' as transport_amt,
     '0' as transport_tax_value,
     '0' as transport_tax_group_name,
     ''  trans_id,
     '' as unload_id,
     '' as insure_id,
     '' as pack_id,
     '' as otax_id,
     '' as freight_id,
   IF(f_expenses_t.reverse_charge=1,'Yes','No') AS rcm,
   CONCAT(LEFT(MONTHNAME(f_expenses_t.expense_date), 3), '-', YEAR(f_expenses_t.expense_date)) as monyr,
        CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND((f_expenses_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS cgst1,
     '0' as cgst2,
     '0' as cgst3,
     '0' as cgst4,
     '0' as cgst5,
     '0' as cgst6,
     '0' as cgst7,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND((f_expenses_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS sgst1,
     '0' as sgst2,
 '0' as sgst3,
 '0' as sgst4,
 '0' as sgst5,
 '0' as sgst6,
 '0' as sgst7,
               CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN ROUND((f_expenses_lines_t.tax_amount), 2) 
        ELSE 0 
    END AS igst1,
     '0' as igst2,
 '0' as igst3,
 '0' as igst4,
 '0' as igst5,
 '0' as igst6,
 '0' as igst7,
 '' as tax_group_name,
  f_expenses_lines_t.expense_line_amount as tax_value
FROM 
    f_expenses_t
LEFT JOIN 
    f_expenses_lines_t ON f_expenses_lines_t.expense_id = f_expenses_t.expense_id
LEFT JOIN 
    m_tax_group_t ON m_tax_group_t.tax_group_id = f_expenses_lines_t.tax_group_id
LEFT JOIN 
    m_supplier_t ON m_supplier_t.supplier_id = f_expenses_t.supplier_id
LEFT JOIN 
m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
LEFT JOIN 
f_account_structure_t as accountstructure_lines  on(accountstructure_lines.f_account_structure_id=f_expenses_lines_t.expense_account_id)
WHERE 
    f_expenses_t.expense_status = 'APPROVED'  AND m_tax_group_t.tax_group_name != 'NO TAX'
    AND f_expenses_t.bill_date BETWEEN '$from' and '$to' 
GROUP BY 
    f_expenses_t.expense_no ,f_expenses_t.bill_date, m_supplier_t.supplier_name, m_tax_group_t.tax_group_name  HAVING m_tax_group_t.tax_group_name != 'NO TAX')v1");

    return response()->json(['data' => $result1]);

  }


  // expense report
  public function index2()
  {

    return view('b2report.btwoerpt', $this->data);

  }

  public function getBtwoerpt(Request $request)
  {


    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $result1 = \DB::select("SELECT 
    v1.invoice_date,
    v1.invoice_number,
    v1.cusname,
    v1.gst_no,
    v1.statename,
    v1.igst,
    v1.cgst,
    v1.sgst,
    v1.invoice_grand_total,
    ROUND((v1.line_total - v1.tax_amount), 2) AS accessablevalu
  
    FROM (
        SELECT 
        s_invoice_hdr_t.invoice_date,
        s_invoice_hdr_t.invoice_number,
        COALESCE(m_customers_t.customer_name, hr_employee_t.first_name) AS cusname,
        m_customer_sites_t.gst_no,
        CASE 
            WHEN s_invoice_hdr_t.employee_id != '0' THEN (
                SELECT state_name 
                FROM m_states_t 
                WHERE m_states_t.state_id = hr_emp_contact.current_state
            )
            ELSE (
                SELECT state_name 
                FROM m_states_t 
                WHERE m_states_t.state_id = m_customer_sites_t.state
            ) 
        END AS statename,
        m_tax_group_t.tax_group_name,
        SUM(s_invoice_lines_t.tax_amount) AS tax_amount,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM(s_invoice_lines_t.tax_amount) / 2, 2) 
            ELSE 0 
        END AS cgst,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM(s_invoice_lines_t.tax_amount) / 2, 2) 
            ELSE 0 
        END AS sgst,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN ROUND(SUM(s_invoice_lines_t.tax_amount), 2) 
            ELSE 0 
        END AS igst,
        ROUND(
            (SELECT conversion_rate 
             FROM f_account_exchangerates_t
             WHERE from_currency_id = s_invoice_hdr_t.invoice_currency 
             AND s_invoice_hdr_t.invoice_date BETWEEN from_date AND to_date 
             LIMIT 1) * s_invoice_hdr_t.invoice_grand_total, 2
        ) AS invoice_grand_total,
        ROUND(
            (SELECT conversion_rate 
             FROM f_account_exchangerates_t
             WHERE from_currency_id = s_invoice_hdr_t.invoice_currency 
             AND s_invoice_hdr_t.invoice_date BETWEEN from_date AND to_date 
             LIMIT 1) * (
                CASE 
                    WHEN s_invoice_hdr_t.transport_charges IS  NULL 
                    THEN s_invoice_lines_t.line_total 
                    ELSE s_invoice_hdr_t.invoice_grand_total - s_invoice_hdr_t.transport_charges 
                END
            ), 2
        ) AS line_total
    FROM 
        s_invoice_lines_t
    JOIN 
        s_invoice_hdr_t ON s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id
    LEFT JOIN 
        m_customers_t ON m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
    LEFT JOIN 
        m_customer_sites_t ON m_customer_sites_t.customer_site_id = s_invoice_hdr_t.bill_to_address_id 
        AND m_customer_sites_t.site_type = 'BILL_TO' 
        AND m_customer_sites_t.active = 'Yes'
    LEFT JOIN 
        m_tax_group_t ON m_tax_group_t.tax_group_id = s_invoice_lines_t.tax_group_id
    LEFT JOIN 
        hr_employee_t ON hr_employee_t.employee_id = s_invoice_hdr_t.employee_id
    LEFT JOIN 
        s_salesorder_lines_t ON s_salesorder_lines_t.tax_group_id = m_tax_group_t.tax_group_id
    LEFT JOIN 
        hr_emp_contact ON hr_emp_contact.employee_id = s_invoice_hdr_t.employee_id
    WHERE  
        s_invoice_hdr_t.invoice_date BETWEEN '$start_date' AND '$end_date' 
        AND s_invoice_hdr_t.invoice_type IN ('EXPORT INVOICE', 'EXPORT SAMPLE') 
        AND s_invoice_hdr_t.invoice_status = 'APPROVED'
    GROUP BY 
        s_invoice_hdr_t.invoice_date,
        s_invoice_hdr_t.invoice_number
    ) v1
    ORDER BY 
        v1.invoice_date ASC");

    return response()->json(['data' => $result1]);
  }


  public function getExpenserpt(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $result1 = \DB::select("SELECT CONCAT(LEFT(MONTHNAME(f_expenses_t.expense_date), 3), '-', YEAR(f_expenses_t.expense_date)) as monyr,
                    f_expenses_t.invoice,
                    f_expenses_t.expense_date,
                    m_supplier_t.supplier_name,
                    m_tax_group_t.tax_group_name,
                   ROUND(SUM(f_expenses_lines_t.tax_amount),2) as tax_amount,
                    if (f_expenses_t.reverse_charge=1,'Yes','no') as rcm,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND((f_expenses_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS cgst,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND((f_expenses_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS sgst,
    CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN ROUND((f_expenses_lines_t.tax_amount), 2) 
            ELSE 0 
        END AS igst
                    
                    FROM f_expenses_t
                    left join f_expenses_lines_t on(f_expenses_lines_t.expense_id=f_expenses_t.expense_id)
                    left join m_tax_group_t on(m_tax_group_t.tax_group_id=f_expenses_lines_t.tax_group_id)
                    left join f_gst_code_hdr_t on(f_gst_code_hdr_t.gst_code_hdr_id=f_expenses_lines_t.gst_code_id)
                    left join f_account_structure_t as accountstructure_hdr  on(accountstructure_hdr.f_account_structure_id=f_expenses_t.tds_account_id) 
                    left join f_account_structure_t as accountstructure_lines  on(accountstructure_lines.f_account_structure_id=f_expenses_lines_t.expense_account_id)
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
                    left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                    left join m_customers_t on(m_customers_t.customer_id=f_expenses_t.customer_id)
                    left join m_customer_sites_t on(m_customer_sites_t.customer_id=m_customers_t.customer_id and m_customer_sites_t.primary_address='YES' and m_customer_sites_t.site_type = 'BILL_TO')

                    where 1=1 and f_expenses_t.expense_status='APPROVED' and f_expenses_t.expense_date BETWEEN '$start_date' and '$end_date' GROUP BY m_supplier_t.supplier_name ORDER BY f_expenses_t.expense_date DESC");

    return response()->json(['data' => $result1]);

  }


}
