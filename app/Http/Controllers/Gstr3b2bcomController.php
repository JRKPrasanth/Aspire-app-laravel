<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\DataTables;

class Gstr3b2bcomController extends Controller
{
    
	    public function __construct(){
		
        $this->data=array();
        $this->data['pageMethod']=\Request::route()->getName();

    }
	
	public function index(){


        return view('Gstr3b2bcom.index', $this->data);	

        }

    public function getpurchasereg(Request $request){

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

  $pur_reg =\DB::select("SELECT MONTHNAME(v2.credit_date) as credit_date ,ROUND(SUM(v2.total_tax_value), 0) as tax_value,ROUND(SUM(v2.cgst), 0) as cgst,ROUND(SUM(v2.sgst), 0) as sgst,   ROUND(SUM(v2.igst), 0) as igst FROM (
    SELECT 
    v1.credit_date,
    v1.monyr,
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
        p_po_invoice_hdr_t.credit_date,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', -1) as transport_amt,
        ROUND((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1)), 2) as transport_tax_value,
        transport_tax_group_t.tax_group_name as transport_tax_group_name,
        'PURCHASE' AS type,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', 1) as trans_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', 1) as unload_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', 1) as insure_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', 1) as pack_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', 1) as otax_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', 1) as freight_id,
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
        p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'  AND p_po_invoice_hdr_t.credit_taken ='Yes'
        AND m_products_t.tax_credit = 'yes'
        AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL) 
        AND DATE(p_po_invoice_hdr_t.credit_date) BETWEEN '$start_date' and '$end_date' 
    GROUP BY 
      p_po_invoice_hdr_t.credit_date, m_tax_group_t.tax_group_name  
    HAVING m_tax_group_t.tax_group_name != 'NO TAX'
UNION ALL
SELECT 
     f_expenses_t.bill_date as credit_date,
     '0' as transport_amt,
     '0' as transport_tax_value,
     '0' as transport_tax_group_name,
     'EXPENSE' AS type,
     ''  trans_id,
     '' as unload_id,
     '' as insure_id,
     '' as pack_id,
     '' as otax_id,
     '' as freight_id,

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
    f_expenses_t.expense_status = 'APPROVED'  AND f_expenses_t.credit_taken ='Yes' AND m_tax_group_t.tax_group_name != 'NO TAX'
    AND f_expenses_t.credit_date BETWEEN '$start_date' and '$end_date' 
GROUP BY 
    f_expenses_t.credit_date, m_tax_group_t.tax_group_name  HAVING m_tax_group_t.tax_group_name != 'NO TAX' 
UNION ALL
SELECT 
     f_debitcredit_t.credit_date,
     '0' as transport_amt,
     '0' as transport_tax_value,
     '0' as transport_tax_group_name,
     'DEBIT' AS type,
     ''  trans_id,
     '' as unload_id,
     '' as insure_id,
     '' as pack_id,
     '' as otax_id,
     '' as freight_id,

   CONCAT(LEFT(MONTHNAME(f_debitcredit_t.debitcredit_date), 3), '-', YEAR(f_debitcredit_t.debitcredit_date)) as monyr,
        CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND((f_debitcredit_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS cgst1,
     '0' as cgst2,
     '0' as cgst3,
     '0' as cgst4,
     '0' as cgst5,
     '0' as cgst6,
     '0' as cgst7,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND((f_debitcredit_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS sgst1,
     '0' as sgst2,
 '0' as sgst3,
 '0' as sgst4,
 '0' as sgst5,
 '0' as sgst6,
 '0' as sgst7,
               CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN ROUND((f_debitcredit_lines_t.tax_amount), 2) 
        ELSE 0 
    END AS igst1,
     '0' as igst2,
 '0' as igst3,
 '0' as igst4,
 '0' as igst5,
 '0' as igst6,
 '0' as igst7,
 '' as tax_group_name,
  f_debitcredit_lines_t.debitcredit_line_amount as tax_value
FROM 
    f_debitcredit_t
LEFT JOIN 
    f_debitcredit_lines_t ON f_debitcredit_lines_t.debitcredit_id = f_debitcredit_t.debitcredit_id
LEFT JOIN 
    m_tax_group_t ON m_tax_group_t.tax_group_id = f_debitcredit_lines_t.tax_group_id
LEFT JOIN 
f_account_structure_t as accountstructure_lines  on(accountstructure_lines.f_account_structure_id=f_debitcredit_lines_t.debitcredit_account_id)
WHERE 
    f_debitcredit_t.debitcredit_status = 'APPROVED' AND f_debitcredit_t.source_type='DEBIT' AND f_debitcredit_t.credit_taken ='Yes' AND m_tax_group_t.tax_group_name != 'NO TAX'
    AND f_debitcredit_t.credit_date BETWEEN '$start_date' and '$end_date' 
GROUP BY 
    MONTH(f_debitcredit_t.debitcredit_date), m_tax_group_t.tax_group_name  HAVING m_tax_group_t.tax_group_name != 'NO TAX'
     )v1)v2 GROUP BY MONTHNAME(v2.credit_date)
    ORDER BY v2.credit_date");      

 $gst_rpt =\DB::select("SELECT 
    months.month_name AS credit_date, 
    COALESCE (ROUND(SUM(v2.tax_value), 0),0) AS tax_value,
    COALESCE (ROUND(SUM(v2.cgst), 0),0) AS cgst,
    COALESCE (ROUND(SUM(v2.sgst), 0),0) AS sgst,
    COALESCE (ROUND(SUM(v2.igst), 0),0) AS igst
FROM 
    (SELECT 'January' AS month_name, 1 AS month_number UNION ALL
     SELECT 'February', 2 UNION ALL
     SELECT 'March', 3 UNION ALL
     SELECT 'April', 4 UNION ALL
     SELECT 'May', 5 UNION ALL
     SELECT 'June', 6 UNION ALL
     SELECT 'July', 7 UNION ALL
     SELECT 'August', 8 UNION ALL
     SELECT 'September', 9 UNION ALL
     SELECT 'October', 10 UNION ALL
     SELECT 'November', 11 UNION ALL
     SELECT 'December', 12) AS months
LEFT JOIN (
    SELECT 
        MONTH(f_debitcredit_t.debitcredit_date) AS month_number,
        f_debitcredit_t.credit_date,
        f_debitcredit_lines_t.debitcredit_line_amount AS tax_value,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' 
            THEN ROUND(f_debitcredit_lines_t.tax_amount / 2, 2) 
            ELSE 0 
        END AS cgst,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' 
            THEN ROUND(f_debitcredit_lines_t.tax_amount / 2, 2) 
            ELSE 0 
        END AS sgst,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' 
            THEN ROUND(f_debitcredit_lines_t.tax_amount, 2) 
            ELSE 0 
        END AS igst
    FROM 
        f_debitcredit_t
    LEFT JOIN 
        f_debitcredit_lines_t 
        ON f_debitcredit_lines_t.debitcredit_id = f_debitcredit_t.debitcredit_id
    LEFT JOIN 
        m_tax_group_t 
        ON m_tax_group_t.tax_group_id = f_debitcredit_lines_t.tax_group_id
    LEFT JOIN 
        f_account_structure_t AS accountstructure_lines  
        ON accountstructure_lines.f_account_structure_id = f_debitcredit_lines_t.debitcredit_account_id
    WHERE 
        f_debitcredit_t.debitcredit_status = 'APPROVED' 
        AND f_debitcredit_t.source_type = 'CREDIT'  AND f_debitcredit_t.credit_taken ='Yes'
        AND f_debitcredit_t.credit_date BETWEEN '$start_date' and '$end_date' 
        AND m_tax_group_t.tax_group_name IS NOT NULL 
        AND m_tax_group_t.tax_group_name != 'NO TAX'
) v2 
ON months.month_number = v2.month_number
GROUP BY months.month_number, months.month_name
ORDER BY months.month_number");

    $pur_report = $pur_reg;
    $gst_report = $gst_rpt;
    
    $comparisonData = [];
    
    $total_tax_value = 0;
    $total_cgst = 0;
    $total_sgst = 0;
    $total_igst = 0;
    
    foreach ($pur_report as $saleRow) {
        foreach ($gst_report as $preSaleRow) {
            if ($saleRow->credit_date == $preSaleRow->credit_date) {
                $tax_value = $saleRow->tax_value - $preSaleRow->tax_value;
                $cgst = $saleRow->cgst - $preSaleRow->cgst;
                $sgst = $saleRow->sgst - $preSaleRow->sgst;
                $igst = $saleRow->igst - $preSaleRow->igst;
    
                // Add individual values to totals
                $total_tax_value += $tax_value;
                $total_cgst += $cgst;
                $total_sgst += $sgst;
                $total_igst += $igst;
    
                $comparisonData[] = [
                    'invoice_date' => $saleRow->credit_date,
                    'tax_value' => $tax_value,
                    'cgst' => $cgst,
                    'sgst' => $sgst,
                    'igst' => $igst,
                ];
    
                break;
            }
        }
    }
    
    // Append Total row
    $comparisonData[] = [
        'invoice_date' => 'Total',
        'tax_value' => $total_tax_value,
        'cgst' => $total_cgst,
        'sgst' => $total_sgst,
        'igst' => $total_igst,
    ];
    
    $result1 = $comparisonData;

    return response()->json(['data' => $result1]);
    }


	public function getgst3report(Request $request){

         $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $result1 = \DB::select("SELECT 
    MONTHNAME(report_date) AS invoice_date,
    ROUND(SUM(igst), 2) AS igst,
    ROUND(SUM(cgst), 2) AS cgst,
    ROUND(SUM(sgst), 2) AS sgst
FROM 
    `a_gst3b_t` 
WHERE 
    description like '%ITC Net%' AND report_date BETWEEN '$start_date' and '$end_date' 
GROUP BY 
    report_date

UNION ALL

SELECT 
    'Total' AS invoice_date,
    ROUND(SUM(igst), 2) AS igst,
    ROUND(SUM(cgst), 2) AS cgst,
    ROUND(SUM(sgst), 2) AS sgst
FROM 
    `a_gst3b_t`
WHERE 
    description like '%ITC Net%' AND report_date BETWEEN '$start_date' and '$end_date' ");

    return response()->json(['data' => $result1]);

    }

    public function gstupldreport(Request $request)
    {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $result1 = \DB::select("SELECT 
    
      MONTHNAME(report_date) AS invoice_date,
        ROUND(SUM(CASE WHEN (invoice_type = 'Regular' OR invoice_type = '') THEN central_tax ELSE 0 END) +
              SUM(CASE WHEN invoice_type = 'Debit Note' THEN central_tax ELSE 0 END) -
              SUM(CASE WHEN invoice_type = 'Credit Note' THEN central_tax ELSE 0 END), 0) AS cgst,
        ROUND(SUM(CASE WHEN (invoice_type = 'Regular' OR invoice_type = '') THEN state_tax ELSE 0 END) + 
              SUM(CASE WHEN invoice_type = 'Debit Note' THEN state_tax ELSE 0 END) - 
              SUM(CASE WHEN invoice_type = 'Credit Note' THEN state_tax ELSE 0 END), 0) AS sgst,
        ROUND(SUM(CASE WHEN (invoice_type = 'Regular' OR invoice_type = '') THEN integrated_tax ELSE 0 END) + 
              SUM(CASE WHEN invoice_type = 'Debit Note' THEN integrated_tax ELSE 0 END) - 
              SUM(CASE WHEN invoice_type = 'Credit Note' THEN integrated_tax ELSE 0 END), 0) AS igst
    FROM a_gstb2b_t
    WHERE itc_availability='Yes' AND report_date BETWEEN '$start_date' and '$end_date' AND report_date!=''
    GROUP BY report_date
UNION ALL

SELECT 
    'Total' AS invoice_date,

        ROUND(SUM(CASE WHEN (invoice_type = 'Regular' OR invoice_type = '') THEN central_tax ELSE 0 END) +
              SUM(CASE WHEN invoice_type = 'Debit Note' THEN central_tax ELSE 0 END) -
              SUM(CASE WHEN invoice_type = 'Credit Note' THEN central_tax ELSE 0 END), 0) AS cgst,
        ROUND(SUM(CASE WHEN (invoice_type = 'Regular' OR invoice_type = '') THEN state_tax ELSE 0 END) + 
              SUM(CASE WHEN invoice_type = 'Debit Note' THEN state_tax ELSE 0 END) - 
              SUM(CASE WHEN invoice_type = 'Credit Note' THEN state_tax ELSE 0 END), 0) AS sgst,
        ROUND(SUM(CASE WHEN (invoice_type = 'Regular' OR invoice_type = '') THEN integrated_tax ELSE 0 END) + 
              SUM(CASE WHEN invoice_type = 'Debit Note' THEN integrated_tax ELSE 0 END) - 
              SUM(CASE WHEN invoice_type = 'Credit Note' THEN integrated_tax ELSE 0 END), 0) AS igst
    FROM a_gstb2b_t
    WHERE itc_availability='Yes' AND report_date BETWEEN '$start_date' and '$end_date' AND report_date!=''");

      return response()->json(['data' => $result1]);
}


    
    public function gstcomprreport(Request $request){
        
         $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
        
   $pur_reg =\DB::select("SELECT 
    MONTHNAME(report_date) AS invoice_date,
    ROUND(SUM(igst), 2) AS igst,
    ROUND(SUM(cgst), 2) AS cgst,
    ROUND(SUM(sgst), 2) AS sgst
FROM 
    `a_gst3b_t` 
WHERE 
    description like '%ITC Net%' AND report_date BETWEEN '$start_date' and '$end_date' 
GROUP BY 
    report_date");      

  $gst_rpt =\DB::select("SELECT 
    
      MONTHNAME(report_date) AS invoice_date,
        ROUND(SUM(CASE WHEN (invoice_type = 'Regular' OR invoice_type = '') THEN central_tax ELSE 0 END) +
              SUM(CASE WHEN invoice_type = 'Debit Note' THEN central_tax ELSE 0 END) -
              SUM(CASE WHEN invoice_type = 'Credit Note' THEN central_tax ELSE 0 END), 0) AS cgst,
        ROUND(SUM(CASE WHEN (invoice_type = 'Regular' OR invoice_type = '') THEN state_tax ELSE 0 END) + 
              SUM(CASE WHEN invoice_type = 'Debit Note' THEN state_tax ELSE 0 END) - 
              SUM(CASE WHEN invoice_type = 'Credit Note' THEN state_tax ELSE 0 END), 0) AS sgst,
        ROUND(SUM(CASE WHEN (invoice_type = 'Regular' OR invoice_type = '') THEN integrated_tax ELSE 0 END) + 
              SUM(CASE WHEN invoice_type = 'Debit Note' THEN integrated_tax ELSE 0 END) - 
              SUM(CASE WHEN invoice_type = 'Credit Note' THEN integrated_tax ELSE 0 END), 0) AS igst
    FROM a_gstb2b_t
    WHERE itc_availability='Yes' AND report_date BETWEEN '$start_date' and '$end_date' 
    GROUP BY report_date");

            $pur_report = $pur_reg;
            $gst_report = $gst_rpt;

            $comparisonData = [];
            $grandTotals = [
                'invoice_date' => 'Total',
                'igst' => 0,
                'cgst' => 0,
                'sgst' => 0,
            ];

    // Convert $gst_report to an associative array for faster lookup
        $gstReportIndexed = [];
    foreach ($gst_report as $preSaleRow) {
        $gstReportIndexed[$preSaleRow->invoice_date] = $preSaleRow;
    }

    // Compare data and calculate differences
    foreach ($pur_report as $saleRow) {
        if (isset($gstReportIndexed[$saleRow->invoice_date])) {
            $preSaleRow = $gstReportIndexed[$saleRow->invoice_date];
    
            // Calculate differences
            $igstDiff = $saleRow->igst - $preSaleRow->igst;
            $cgstDiff = $saleRow->cgst - $preSaleRow->cgst;
            $sgstDiff = $saleRow->sgst - $preSaleRow->sgst;
    
            // Add to comparison data
            $comparisonData[] = [
                'invoice_date' => $saleRow->invoice_date,
                'igst' => $igstDiff,
                'cgst' => $cgstDiff,
                'sgst' => $sgstDiff,
            ];
    
            // Accumulate Totals
    
            $grandTotals['igst'] += $igstDiff;
            $grandTotals['cgst'] += $cgstDiff;
            $grandTotals['sgst'] += $sgstDiff;
        }
    }

// Append Total to comparison data
$comparisonData[] = $grandTotals;

// Output the result (optional)
//print_r($comparisonData);


            $result1 = $comparisonData;

        return response()->json(['data' => $result1]);
        
    } 
    


  /*Main Page Load Function*/
    public function gstrthreebupload()
    { 
     
      $batch = $type = '';
        $this->data['status'] = $this->data['message'] = ''; //dd($_GET['batchname']);
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND f_empexpupload_t.batchname = "' . $_GET['batchname'] . '"'; 
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'EMPINSUPLOAD';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':$upload = $this->UploadValidation($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
                case 'load':$upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
                case 'load':$upload = $this->LoadLineMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;    
            }
        }  


        $this->data['pageMethod']=\Request::route()->getName();
        return view('Gstr3b2bcom.table', $this->data);
    }

    
      /*Get Product  Grid */
     public function getgst3bdata()
    {

        $wh='';
        

        if(isset($_GET['_search']))
        {   
        if($_GET['_search']=='true')
        {
        $wh=$this->jqgridsearch('a_gst3b_t',$_GET['filters']);
        }
        }
            
        if(isset($_GET['batchname'])){
        if($_GET['batchname']!=""){
        $wh= " and batchname like '" . $_GET['batchname'] . "'";   
        }}
            
            
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
            
            
            
            
        if(!$sidx) $sidx =1;
        $result = \DB::select("SELECT COUNT(gst3b_id) AS count FROM a_gst3b_t where 1=1 $wh");
        $count = $result[0]->count;
        if( $count > 0 && $limit > 0) {
        $total_pages = ceil($count/$limit);
        } else {
        $total_pages = 0;
        }

        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $SQL = " Select * from a_gst3b_t $wh ORDER BY $sidx $sord LIMIT $start , $limit";
        $result = \DB::select($SQL);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;

        echo json_encode($responce);

    }  
    
    
     /* purpose:To Upload excel*/ 
    public function Uploadexcel(Request $request){  
        
        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        $data =array();
        $return = 'gstrthreebupload';
        if($extension == "csv"){
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
                if($c>0){
                    // dd($filesop);
                    $gstdata[$c]['gstin'] = $filesop[0];
                    $gstdata[$c]['period'] = $filesop[1];
                    $gstdata[$c]['fn_yr'] = $filesop[2];
                    $gstdata[$c]['description'] = $filesop[3];
                    $gstdata[$c]['tax_val'] = $filesop[4];
                    $gstdata[$c]['igst'] = $filesop[5];
                    $gstdata[$c]['cgst'] = $filesop[6];
                    $gstdata[$c]['sgst'] = $filesop[7];
                    $gstdata[$c]['cess'] = $filesop[8];
                    $gstdata[$c]['date'] = $filesop[9];
                    $gstdata[$c]['report_date'] = $filesop[10];
                    $gstdata[$c]['uploaded_by'] = \Session::get('id');
                    $gstdata[$c]['uploaded_on'] = date('Y-m-d');
                    $gstdata[$c]['company_id'] = \Session::get('companyid');
                    $gstdata[$c]['batch_date'] = date('Y-m-d');
                    $gstdata[$c]['batch_status'] = "UPLOADED";
                    $gstdata[$c]['batch_name'] = $_POST['batchname'];          
            //insert record from csv        
                }  
            $c = $c + 1;
            }
            $id = \DB::table('a_gst3b_t')->insert($gstdata);    
        }else {

            $message = "Please upload an valid CSV file";
     
            return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }
  
        return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));
         
    } 
    /*end*/


}

