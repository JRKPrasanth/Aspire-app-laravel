<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Yajra\DataTables\DataTables;


class ItccredittakenController extends Controller
{
    
	    public function __construct(){
		
        $this->data=array();
        $this->data['pageMethod']=\Request::route()->getName();

    }
	
	
	public function index(){


        return view('itccredittaken.index', $this->data);	

        }

	public function getitcreport(Request $request){

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

     $pur_reg =\DB::select("SELECT MONTHNAME(v2.invoice_date) as invoice_date ,ROUND(SUM(v2.total_tax_value), 0) as tax_value,ROUND(SUM(v2.cgst), 0) as cgst,ROUND(SUM(v2.sgst), 0) as sgst,   ROUND(SUM(v2.igst), 0) as igst FROM (
    SELECT 
    v1.invoice_date,
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
        p_po_invoice_hdr_t.invoice_date,
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
        p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'  
        AND m_products_t.tax_credit = 'yes'
        AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL) 
        AND DATE(p_po_invoice_hdr_t.invoice_date) BETWEEN '$start_date' and '$end_date' 
    GROUP BY 
      p_po_invoice_hdr_t.invoice_date, m_tax_group_t.tax_group_name  
    HAVING m_tax_group_t.tax_group_name != 'NO TAX'
UNION ALL
SELECT 
     f_expenses_t.bill_date as invoice_date,
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
    f_expenses_t.expense_status = 'APPROVED'  AND m_tax_group_t.tax_group_name != 'NO TAX'
    AND f_expenses_t.bill_date BETWEEN '$start_date' and '$end_date' 
GROUP BY 
    f_expenses_t.bill_date, m_tax_group_t.tax_group_name  HAVING m_tax_group_t.tax_group_name != 'NO TAX' 
UNION ALL
SELECT 
     f_debitcredit_t.debitcredit_date as invoice_date,
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
    f_debitcredit_t.debitcredit_status = 'APPROVED' AND f_debitcredit_t.source_type='DEBIT'  AND m_tax_group_t.tax_group_name != 'NO TAX'
    AND f_debitcredit_t.debitcredit_date BETWEEN '$start_date' and '$end_date' 
GROUP BY 
    MONTH(f_debitcredit_t.debitcredit_date), m_tax_group_t.tax_group_name  HAVING m_tax_group_t.tax_group_name != 'NO TAX'
     )v1)v2 GROUP BY MONTHNAME(v2.invoice_date)
    ORDER BY v2.invoice_date");      

 $gst_rpt =\DB::select("SELECT 
    months.month_name AS invoice_date, 
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
        f_debitcredit_t.debitcredit_date AS invoice_date,
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
        AND f_debitcredit_t.source_type = 'CREDIT'  
        AND f_debitcredit_t.debitcredit_date BETWEEN '$start_date' and '$end_date' 
        AND m_tax_group_t.tax_group_name IS NOT NULL 
        AND m_tax_group_t.tax_group_name != 'NO TAX'
) v2 
ON months.month_number = v2.month_number
GROUP BY months.month_number, months.month_name
ORDER BY months.month_number");


            $pur_report = $pur_reg;
            $gst_report = $gst_rpt;

            $comparisonData = [];
//dd($gst_report);
            foreach ($pur_report as $saleRow) {
                foreach ($gst_report as $preSaleRow) {
                    if ($saleRow->invoice_date == $preSaleRow->invoice_date) {
                        $comparisonData[] = [
                            'invoice_date' => $saleRow->invoice_date,
                            'tax_value' => $saleRow->tax_value - $preSaleRow->tax_value,
                            'cgst' => $saleRow->cgst - $preSaleRow->cgst,
                            'sgst' => $saleRow->sgst - $preSaleRow->sgst,
                            'igst' => $saleRow->igst - $preSaleRow->igst,
                        ];

                        break;
                    }
                }
            }
            
        $result1 = $comparisonData;
  
		 return response()->json(['data' => $result1]);
		
    }

    public function gstulpdreport(Request $request)
    {
        
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $result1 = \DB::select("SELECT *
FROM (
    SELECT 
        months.month_name AS invoice_date,
        months.month_number, 
        COALESCE(v1.tax_value, 0) AS tax_value,
        COALESCE(v1.cgst, 0) AS cgst,
        COALESCE(v1.sgst, 0) AS sgst,
        COALESCE(v1.igst, 0) AS igst
    FROM (
        SELECT 'January' AS month_name, 1 AS month_number
        UNION ALL SELECT 'February', 2
        UNION ALL SELECT 'March', 3
        UNION ALL SELECT 'April', 4
        UNION ALL SELECT 'May', 5
        UNION ALL SELECT 'June', 6
        UNION ALL SELECT 'July', 7
        UNION ALL SELECT 'August', 8
        UNION ALL SELECT 'September', 9
        UNION ALL SELECT 'October', 10
        UNION ALL SELECT 'November', 11
        UNION ALL SELECT 'December', 12
    ) AS months
    LEFT JOIN (
        SELECT 
            DATE_FORMAT(report_date, '%M') AS month_name,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN taxable_value ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN taxable_value ELSE 0 END), 0) AS tax_value,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN central_tax ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN central_tax ELSE 0 END), 0) AS cgst,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN state_tax ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN state_tax ELSE 0 END), 0) AS sgst,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN integrated_tax ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN integrated_tax ELSE 0 END), 0) AS igst
        FROM a_gstb2b_t
        WHERE report_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY MONTH(report_date), YEAR(report_date)
    ) v1 ON months.month_name = v1.month_name
) AS subquery
WHERE tax_value != 0
ORDER BY subquery.month_number");

   return response()->json(['data' => $result1]);
}

    public function gstcomparereport(Request $request){

	$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
		

  $pur_reg =\DB::select("SELECT MONTHNAME(v2.invoice_date) as invoice_date ,ROUND(SUM(v2.total_tax_value), 0) as tax_value,ROUND(SUM(v2.cgst), 0) as cgst,ROUND(SUM(v2.sgst), 0) as sgst,   ROUND(SUM(v2.igst), 0) as igst FROM (
    SELECT 
    v1.invoice_date,
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
        p_po_invoice_hdr_t.invoice_date,
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
        p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'  
        AND m_products_t.tax_credit = 'yes'
        AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL) 
        AND DATE(p_po_invoice_hdr_t.invoice_date) BETWEEN '$start_date' and '$end_date' 
    GROUP BY 
      p_po_invoice_hdr_t.invoice_date, m_tax_group_t.tax_group_name  
    HAVING m_tax_group_t.tax_group_name != 'NO TAX'
UNION ALL
SELECT 
     f_expenses_t.bill_date as invoice_date,
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
    f_expenses_t.expense_status = 'APPROVED'  AND m_tax_group_t.tax_group_name != 'NO TAX'
    AND f_expenses_t.bill_date BETWEEN '$start_date' and '$end_date' 
GROUP BY 
    f_expenses_t.bill_date, m_tax_group_t.tax_group_name  HAVING m_tax_group_t.tax_group_name != 'NO TAX' 
UNION ALL
SELECT 
     f_debitcredit_t.debitcredit_date as invoice_date,
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
    f_debitcredit_t.debitcredit_status = 'APPROVED' AND f_debitcredit_t.source_type='DEBIT'  AND m_tax_group_t.tax_group_name != 'NO TAX'
    AND f_debitcredit_t.debitcredit_date BETWEEN '$start_date' and '$end_date' 
GROUP BY 
    MONTH(f_debitcredit_t.debitcredit_date), m_tax_group_t.tax_group_name  HAVING m_tax_group_t.tax_group_name != 'NO TAX'
     )v1)v2 GROUP BY MONTHNAME(v2.invoice_date)
    ORDER BY v2.invoice_date");      

 $gst_rpt =\DB::select("SELECT *
FROM (
    SELECT 
        months.month_name AS invoice_date,
        months.month_number, 
        COALESCE(v1.tax_value, 0) AS tax_value,
        COALESCE(v1.cgst, 0) AS cgst,
        COALESCE(v1.sgst, 0) AS sgst,
        COALESCE(v1.igst, 0) AS igst
    FROM (
        SELECT 'January' AS month_name, 1 AS month_number
        UNION ALL SELECT 'February', 2
        UNION ALL SELECT 'March', 3
        UNION ALL SELECT 'April', 4
        UNION ALL SELECT 'May', 5
        UNION ALL SELECT 'June', 6
        UNION ALL SELECT 'July', 7
        UNION ALL SELECT 'August', 8
        UNION ALL SELECT 'September', 9
        UNION ALL SELECT 'October', 10
        UNION ALL SELECT 'November', 11
        UNION ALL SELECT 'December', 12
    ) AS months
    LEFT JOIN (
        SELECT 
            DATE_FORMAT(report_date, '%M') AS month_name,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN taxable_value ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN taxable_value ELSE 0 END), 0) AS tax_value,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN central_tax ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN central_tax ELSE 0 END), 0) AS cgst,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN state_tax ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN state_tax ELSE 0 END), 0) AS sgst,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN integrated_tax ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN integrated_tax ELSE 0 END), 0) AS igst
        FROM a_gstb2b_t
        WHERE report_date BETWEEN '$start_date' and '$end_date' 
        GROUP BY MONTH(report_date), YEAR(report_date)
    ) v1 ON months.month_name = v1.month_name
) AS subquery
WHERE tax_value != 0
ORDER BY subquery.month_number");

            $pur_report = $pur_reg;
            $gst_report = $gst_rpt;

            $comparisonData = [];

            foreach ($pur_report as $saleRow) {
                foreach ($gst_report as $preSaleRow) {
                    if ($saleRow->invoice_date == $preSaleRow->invoice_date) {
                        $comparisonData[] = [
                            'invoice_date' => $saleRow->invoice_date,
                            'tax_value' => $saleRow->tax_value - $preSaleRow->tax_value,
                            'cgst' => $saleRow->cgst - $preSaleRow->cgst,
                            'sgst' => $saleRow->sgst - $preSaleRow->sgst,
                            'igst' => $saleRow->igst - $preSaleRow->igst,
                        ];

                        break;
                    }
                }
            }
            
        $result = $comparisonData;


        $gst_rpt1 =\DB::select("SELECT *
FROM (
    SELECT 
        months.month_name AS invoice_date,
        months.month_number, 
        COALESCE(v1.tax_value, 0) AS tax_value,
        COALESCE(v1.cgst, 0) AS cgst,
        COALESCE(v1.sgst, 0) AS sgst,
        COALESCE(v1.igst, 0) AS igst
    FROM (
        SELECT 'January' AS month_name, 1 AS month_number
        UNION ALL SELECT 'February', 2
        UNION ALL SELECT 'March', 3
        UNION ALL SELECT 'April', 4
        UNION ALL SELECT 'May', 5
        UNION ALL SELECT 'June', 6
        UNION ALL SELECT 'July', 7
        UNION ALL SELECT 'August', 8
        UNION ALL SELECT 'September', 9
        UNION ALL SELECT 'October', 10
        UNION ALL SELECT 'November', 11
        UNION ALL SELECT 'December', 12
    ) AS months
    LEFT JOIN (
        SELECT 
            DATE_FORMAT(report_date, '%M') AS month_name,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN taxable_value ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN taxable_value ELSE 0 END), 0) AS tax_value,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN central_tax ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN central_tax ELSE 0 END), 0) AS cgst,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN state_tax ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN state_tax ELSE 0 END), 0) AS sgst,
            ROUND(SUM(CASE WHEN invoice_type = 'Regular' THEN integrated_tax ELSE 0 END) - 
                  SUM(CASE WHEN invoice_type = 'Debit Note' THEN integrated_tax ELSE 0 END), 0) AS igst
        FROM a_gstb2b_t
        WHERE report_date BETWEEN '$start_date' and '$end_date' 
        GROUP BY MONTH(report_date), YEAR(report_date)
    ) v1 ON months.month_name = v1.month_name
) AS subquery
WHERE tax_value != 0
ORDER BY subquery.month_number");


$pur_report = json_decode(json_encode($result), true);
$gst_report = json_decode(json_encode($gst_rpt1), true);

$comparisonData = [];

foreach ($pur_report as $saleRow) {
    foreach ($gst_report as $preSaleRow) {
        if ($saleRow['invoice_date'] == $preSaleRow['invoice_date']) {
            $comparisonData[] = [
                'invoice_date' => $saleRow['invoice_date'],
                'tax_value' => $saleRow['tax_value'] - $preSaleRow['tax_value'],
                'cgst' => $saleRow['cgst'] - $preSaleRow['cgst'],
                'sgst' => $saleRow['sgst'] - $preSaleRow['sgst'],
                'igst' => $saleRow['igst'] - $preSaleRow['igst'],
            ];
          
            break;
        }
    }
}

$result1 = $comparisonData;

 return response()->json(['data' => $result1]);
		
    } 
    
	
  public function getcomaprereport(Request $request){

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $result1 = \DB::select("SELECT 
    p_po_invoice_hdr_t.bill_number,
    m_supplier_sites_t.gst_number AS gst_number,
    'Purchase' AS type,
    p_po_invoice_hdr_t.invoice_date,
    m_supplier_t.supplier_name,
    p_po_invoice_hdr_t.credit_taken,
    SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1) + 
    SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', -1) + 
    SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', -1) + 
    SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', -1) + 
    SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1) + 
    SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', -1) AS transport_amt,
    ROUND((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1)), 2) AS transport_tax_value,
    SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', 1) AS trans_id,
    SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', 1) AS unload_id,
    SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', 1) AS insure_id,
    SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', 1) AS pack_id,
    SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', 1) AS otax_id,
    SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', 1) AS freight_id,
    ROUND(SUM((p_po_invoice_lines_t.line_total - p_po_invoice_lines_t.tax_amount)), 2) AS tax_value,
    CASE  
        WHEN a_gstb2b_t.invoice_number IS NOT NULL 
             AND p_po_invoice_hdr_t.bill_number = a_gstb2b_t.invoice_number
             AND m_supplier_sites_t.gst_number = a_gstb2b_t.supplier_gstin 
             AND p_po_invoice_hdr_t.invoice_date = a_gstb2b_t.invoice_date THEN 'Match'
        WHEN a_gstb2b_t.invoice_number IS NULL THEN 'Missing in 2B'
        ELSE 'Mismatch'
    END AS status
FROM 
    p_po_invoice_hdr_t
LEFT JOIN 
    p_po_invoice_lines_t ON p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id
LEFT JOIN 
    m_supplier_t ON m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id
LEFT JOIN 
    m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
LEFT JOIN 
    m_tax_group_t ON m_tax_group_t.tax_group_id = p_po_invoice_lines_t.tax_group_id
LEFT JOIN 
    m_supplier_sites_t ON m_supplier_sites_t.supplier_id = m_supplier_t.supplier_id 
    AND m_supplier_sites_t.primary_address = 'Yes'
LEFT JOIN 
    a_gstb2b_t ON p_po_invoice_hdr_t.bill_number = a_gstb2b_t.invoice_number 
    AND m_supplier_sites_t.gst_number = a_gstb2b_t.supplier_gstin 
    AND p_po_invoice_hdr_t.invoice_date = a_gstb2b_t.invoice_date
WHERE 
    p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'  
    AND m_products_t.tax_credit = 'yes'
    AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL) 
    AND DATE(p_po_invoice_hdr_t.invoice_date) BETWEEN '$start_date' AND '$end_date'
GROUP BY 
    p_po_invoice_hdr_t.bill_number, p_po_invoice_hdr_t.invoice_date, m_supplier_t.supplier_name

UNION ALL

SELECT   
    a_gstb2b_t.invoice_number AS bill_number,
    a_gstb2b_t.supplier_gstin AS gst_number,
    'GST' AS type,
    a_gstb2b_t.invoice_date,
    a_gstb2b_t.legal_name AS supplier_name,
    '' AS credit_taken,
    '' AS transport_amt,
    '' AS transport_tax_value,
    '' AS trans_id,
    '' AS unload_id,
    '' AS insure_id,
    '' AS pack_id,
    '' AS otax_id,
    '' AS freight_id,
    a_gstb2b_t.taxable_value AS tax_value,
    CASE  
        WHEN p_po_invoice_hdr_t.bill_number IS NOT NULL 
             AND p_po_invoice_hdr_t.bill_number = a_gstb2b_t.invoice_number
             AND m_supplier_sites_t.gst_number = a_gstb2b_t.supplier_gstin 
             AND p_po_invoice_hdr_t.invoice_date = a_gstb2b_t.invoice_date THEN 'Match'
        WHEN p_po_invoice_hdr_t.bill_number IS NULL THEN 'Missing in Aspire'
        ELSE 'Mismatch'
    END AS status
FROM 
    a_gstb2b_t
LEFT JOIN 
    p_po_invoice_hdr_t ON p_po_invoice_hdr_t.bill_number = a_gstb2b_t.invoice_number 
    AND p_po_invoice_hdr_t.invoice_date = a_gstb2b_t.invoice_date
LEFT JOIN 
    m_supplier_sites_t ON m_supplier_sites_t.gst_number = a_gstb2b_t.supplier_gstin
WHERE 
    a_gstb2b_t.invoice_date BETWEEN '$start_date' AND '$end_date'
GROUP BY 
    a_gstb2b_t.invoice_number, a_gstb2b_t.invoice_date, a_gstb2b_t.supplier_gstin");

   return response()->json(['data' => $result1]);

 } 

  /*Main Page Load Function*/
    public function gstrtwobupload(Request $request)
    { 
     // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        
        $userAccess = json_decode($access, true);
        
        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }
        
        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');
               
        }

        // END

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
        return view('itccredittaken.table', $this->data);
    }

    
      /*Get Product  Grid */
     public function getgst2bdata(Request $request)
    {

        $wh='';

            
        if(isset($_GET['batchname'])){
        if($_GET['batchname']!=""){
        $wh= " and batch_name like '" . $_GET['batchname'] . "'";   
        }}

        $SQL = "SELECT * from a_gstb2b_t where 1=1 $wh";
        $result = \DB::select($SQL);

		return DataTables::of($result)->make(true);
    }  
    
    
     /* purpose:To Upload excel*/ 
    public function Uploadexcel(Request $request){  
        
        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        $data =array();
        $return = 'gstrtwobupload';
        if($extension == "csv"){
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
                if($c>0){
                    // dd($filesop);
                    $gstdata[$c]['supplier_gstin'] = $filesop[0];
                    $gstdata[$c]['legal_name'] = $filesop[1];
                    $gstdata[$c]['invoice_number'] = $filesop[2];
                    $gstdata[$c]['invoice_type'] = $filesop[3];
                    $gstdata[$c]['supply_type'] = $filesop[4];
                    $gstdata[$c]['invoice_date'] = $filesop[5];
                    $gstdata[$c]['invoice_value'] = $filesop[6];
                    $gstdata[$c]['place_of_supply'] = $filesop[7];
                    $gstdata[$c]['reverse_charge'] = $filesop[8];
                    $gstdata[$c]['taxable_value'] = $filesop[9];
                    $gstdata[$c]['integrated_tax'] = $filesop[10];
                    $gstdata[$c]['central_tax'] = $filesop[11];
                    $gstdata[$c]['state_tax'] = $filesop[12];
                    $gstdata[$c]['cess'] = $filesop[13];
                    $gstdata[$c]['period'] = $filesop[14];
                    $gstdata[$c]['filing_date'] = $filesop[15];
                    $gstdata[$c]['itc_availability'] = $filesop[16];
                    $gstdata[$c]['reason'] = $filesop[17];
                    $gstdata[$c]['tax_rate'] = $filesop[18];
                    $gstdata[$c]['source'] = $filesop[19];
                    $gstdata[$c]['irn'] = $filesop[20];
                    $gstdata[$c]['irn_date'] = $filesop[21];
                     $gstdata[$c]['report_date'] = $filesop[22];
                    $gstdata[$c]['batch_date'] = date('Y-m-d');
                    $gstdata[$c]['batch_status'] = "UPLOADED";
                    $gstdata[$c]['batch_name'] = $_POST['batch_name'];              
            //insert record from csv        
                }  
            $c = $c + 1;
            }
            $id = \DB::table('a_gstb2b_t')->insert($gstdata);    
        }else {

            $message = "Please upload an valid CSV file";  
            return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }
 
        return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));
         
    } 
	
    /*end*/

	
}

?>