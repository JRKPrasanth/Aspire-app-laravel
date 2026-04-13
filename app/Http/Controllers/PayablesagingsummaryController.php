<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB,Session;

class PayablesagingsummaryController extends Controller
{
    
        public function __construct(){
			
        $this->data['pageMethod']=\Request::route()->getName();
    }
	
    public function index(Request $request)
    
	{

         $cur_year = date('Y');
         $year =  $cur_year - 1;

     // Current date

    $agingSummary = \DB::select("SELECT
    v2.name AS supplier,
    v2.`0-30`,
    v2.`31-60`,
    v2.`61-90`,
    v2.`91+`,
    v2.total
FROM
    (
    SELECT
        v1.name,
        ROUND(v1.`0-30`, 0) AS `0-30`,
        ROUND(v1.`31-60`, 0) AS `31-60`,
        ROUND(v1.`61-90`, 0) AS `61-90`,
        ROUND(v1.`91+`, 0) AS `91+`,
        ROUND(v1.total, 2) AS total
    FROM
        (
        SELECT
            m_supplier_t.supplier_name AS NAME,
            (
                COALESCE(debit_0_30, 0) - COALESCE(advance_0_30, 0) - COALESCE(credit_0_30, 0)
            ) AS `0-30`,

            (
                COALESCE(debit_31_60, 0) - COALESCE(advance_31_60, 0) - COALESCE(credit_31_60, 0)
            ) AS `31-60`,

            (
                COALESCE(debit_61_90, 0) - COALESCE(advance_61_90, 0) - COALESCE(credit_61_90, 0)
            ) AS `61-90`,

            (
                COALESCE(debit_91_plus, 0) - COALESCE(advance_91_plus, 0) - COALESCE(credit_91_plus, 0)
            ) AS `91+`,

            (
                COALESCE(total_debit, 0) - COALESCE(total_advance, 0) - COALESCE(total_credit, 0)
            ) AS total
        FROM
            m_supplier_t

        LEFT JOIN(
            SELECT
                reference_id AS supplier_id,
                SUM(
                    CASE WHEN DATEDIFF(
                        CURDATE(), f_journal_entry_lines_t.journal_date) BETWEEN 0 AND 30 THEN debit_amount ELSE 0
                    END
                ) AS debit_0_30,
                SUM(
                    CASE WHEN DATEDIFF(
                        CURDATE(), f_journal_entry_lines_t.journal_date) BETWEEN 31 AND 60 THEN debit_amount ELSE 0
                    END
                ) AS debit_31_60,
                SUM(
                    CASE WHEN DATEDIFF(
                        CURDATE(), f_journal_entry_lines_t.journal_date) BETWEEN 61 AND 90 THEN debit_amount ELSE 0
                    END
                ) AS debit_61_90,
                SUM(
                    CASE WHEN DATEDIFF(
                        CURDATE(), f_journal_entry_lines_t.journal_date) > 90 THEN debit_amount ELSE 0
                    END
                ) AS debit_91_plus,
                SUM(debit_amount) AS total_debit
            FROM
                f_journal_entry_lines_t
            LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
            WHERE
                f_journal_entry_lines_t.reference_source = 'SUPPLIER' AND f_journal_entry_t.journal_type IN(
                    'ADVANCE PAYMENT','DIRECTPAYMENT','PAYMENT','DEBIT','DEBIT NOTE','MANUAL','OB_SUPPLIER'
                )
            GROUP BY
                reference_id
        ) debit
    ON
        debit.supplier_id = m_supplier_t.supplier_id

    LEFT JOIN(
        SELECT
            supplier_id AS supplier_id,
            SUM(
                CASE WHEN DATEDIFF(CURDATE(), invoice_date) BETWEEN 0 AND 30 THEN invoice_grand_total ELSE 0
                END) AS advance_0_30,
            SUM(
                CASE WHEN DATEDIFF(CURDATE(), invoice_date) BETWEEN 31 AND 60 THEN invoice_grand_total ELSE 0
                END) AS advance_31_60,
            SUM(
                CASE WHEN DATEDIFF(CURDATE(), invoice_date) BETWEEN 61 AND 90 THEN invoice_grand_total ELSE 0
                END) AS advance_61_90,
            SUM(
                CASE WHEN DATEDIFF(CURDATE(), invoice_date) > 90 THEN invoice_grand_total ELSE 0
                END) AS advance_91_plus,
            SUM(invoice_grand_total) AS total_advance
        FROM
            p_po_invoice_hdr_t
        WHERE
            po_invoice_status = 'APPROVED' AND created_by='' AND invoice_date < '$year-04-01' 
        GROUP BY
            supplier_id
    ) advance
ON
    advance.supplier_id = m_supplier_t.supplier_id
    
    LEFT JOIN(
    SELECT
        reference_id AS supplier_id,
        SUM(
            CASE WHEN DATEDIFF(
                CURDATE(), f_journal_entry_lines_t.journal_date) BETWEEN 0 AND 30 THEN credit_amount ELSE 0
            END
        ) AS credit_0_30,
        SUM(
            CASE WHEN DATEDIFF(
                CURDATE(), f_journal_entry_lines_t.journal_date) BETWEEN 31 AND 60 THEN credit_amount ELSE 0
            END
        ) AS credit_31_60,
        SUM(
            CASE WHEN DATEDIFF(
                CURDATE(), f_journal_entry_lines_t.journal_date) BETWEEN 61 AND 90 THEN credit_amount ELSE 0
            END
        ) AS credit_61_90,
        SUM(
            CASE WHEN DATEDIFF(
                CURDATE(), f_journal_entry_lines_t.journal_date) > 90 THEN credit_amount ELSE 0
            END
        ) AS credit_91_plus,
        SUM(credit_amount) AS total_credit
    FROM
        f_journal_entry_lines_t
    LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    WHERE
        f_journal_entry_lines_t.reference_source = 'SUPPLIER' AND f_journal_entry_t.journal_type IN(
            'CREDIT','EXPENSES','MANUAL','OB_SUPPLIER','PO INVOICE','REVERSE'
        )
    GROUP BY
        reference_id
) credit
ON
    credit.supplier_id = m_supplier_t.supplier_id
    ) v1
) v2
GROUP BY
    v2.name
HAVING
    v2.total != '0'
ORDER BY
    v2.name ASC");
            
                    
                $sum0_30 = 0;
                $sum31_60 = 0;
                $sum61_90 = 0;
                $sum91_plus = 0;
                $sum_total = 0;
                
                foreach ($agingSummary as $summary) {
                    $sum0_30 += $summary->{'0-30'};
                    $sum31_60 += $summary->{'31-60'};
                    $sum61_90 += $summary->{'61-90'};
                    $sum91_plus += $summary->{'91+'};
                    $sum_total += $summary->total;
                }
                
                $grandTotal = (object)[
                    'supplier' => 'Total',
                    '0-30' => $sum0_30,
                    '31-60' => $sum31_60,
                    '61-90' => $sum61_90,
                    '91+' => $sum91_plus,
                    'total' => $sum_total,
                ];
                
                $agingSummary[] = $grandTotal;

  // table query end - vignesh m                  
                    
    // card query 

      $card_query = \DB::select("SELECT SUM(v2. `120+`) AS total_120, SUM(v2. `180+`) AS total_180 FROM (SELECT
        ROUND(v1.`120+`, 0) AS `120+`,
        ROUND(v1.`180+`, 0) AS `180+`
    FROM
        (
        SELECT
            m_supplier_t.supplier_name AS NAME,

  
            (
                COALESCE(debit_120_plus, 0) - COALESCE(advance_120_plus, 0) - COALESCE(credit_120_plus, 0)
            ) AS `120+`,

           (
                COALESCE(debit_180_plus, 0) - COALESCE(advance_180_plus, 0) - COALESCE(credit_180_plus, 0)
            ) AS `180+`

        FROM
            m_supplier_t

        LEFT JOIN(
            SELECT
                reference_id AS supplier_id,
              
                SUM(
                    CASE WHEN DATEDIFF(
                        CURDATE(), f_journal_entry_lines_t.journal_date) > 120 THEN debit_amount ELSE 0
                    END
                ) AS debit_120_plus,

                     SUM(
                    CASE WHEN DATEDIFF(
                        CURDATE(), f_journal_entry_lines_t.journal_date) > 180 THEN debit_amount ELSE 0
                    END
                ) AS debit_180_plus
                
            FROM
                f_journal_entry_lines_t
            LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
            WHERE
                f_journal_entry_lines_t.reference_source = 'SUPPLIER' AND f_journal_entry_t.journal_type IN(
          'ADVANCE PAYMENT','DIRECTPAYMENT','PAYMENT','DEBIT','DEBIT NOTE','MANUAL','OB_SUPPLIER'
                )

        ) debit
    ON
        debit.supplier_id = m_supplier_t.supplier_id
        
     LEFT JOIN(
        SELECT
            supplier_id AS supplier_id,

               SUM(
                CASE WHEN DATEDIFF(CURDATE(), invoice_date) > 120 THEN invoice_grand_total ELSE 0
                END) AS advance_120_plus,

                SUM(
                CASE WHEN DATEDIFF(CURDATE(), invoice_date) > 180 THEN invoice_grand_total ELSE 0
                END) AS advance_180_plus

        FROM
           p_po_invoice_hdr_t
        WHERE
            po_invoice_status = 'APPROVED' AND created_by='' AND invoice_date < '$year-04-01'

    ) advance
ON
    advance.supplier_id = m_supplier_t.supplier_id
    
LEFT JOIN(
    SELECT
        reference_id AS supplier_id,

        SUM(
            CASE WHEN DATEDIFF(
                CURDATE(), f_journal_entry_lines_t.journal_date) > 120 THEN credit_amount ELSE 0
            END
        ) AS credit_120_plus,
        SUM(
            CASE WHEN DATEDIFF(
                CURDATE(), f_journal_entry_lines_t.journal_date) > 180 THEN credit_amount ELSE 0
            END
        ) AS credit_180_plus
    FROM
        f_journal_entry_lines_t
    LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    WHERE
        f_journal_entry_lines_t.reference_source = 'SUPPLIER' AND f_journal_entry_t.journal_type IN(
            'CREDIT','EXPENSES','MANUAL','OB_SUPPLIER','PO INVOICE','REVERSE'
        )
    ) credit
    ON
        credit.supplier_id = m_supplier_t.supplier_id
        ) v1)v2");
            
            
       $activesuppliersCount = count($agingSummary);             
       $totalOutstanding = $grandTotal->total; 
       $total_120 = $card_query[0]->total_120;
       $total_180 = $card_query[0]->total_180;
                 
     // dd($totalOutstanding);
                
    return view('payablesagingsummary.table',$this->data, compact('agingSummary','activesuppliersCount','total_120','total_180','totalOutstanding'));
 
}
	
	// popup query
	public function getrecagingtransactions(Request $request)
    {
        $supplierName = $request->input('supplier');
      //  $as_on_date = date('Y-m-d');
        $cur_year = date('Y');
        $year =  $cur_year - 1;
        $days = $request->input('days');
        

        if($days =='0-30'){
          $between = " BETWEEN 0 AND 30";
        }
        if($days =='31-60'){
            $between = " BETWEEN 31 AND 60";
        }
        if($days =='61-90'){
            $between = " BETWEEN 61 AND 90";
        }
        if($days =='91+'){
            $between = " > 90";
        }
         if($days =='total'){
            $between = " ";
        }
      //  dd($between);

        $detail_qry = \DB::select("SELECT
    v1.*,
    ROUND(
        SUM(
            v1.debit_amount - v1.credit_amount
        ),
        2
    ) AS balance_amount
FROM
    (
    SELECT
        f_journal_entry_t.journal_name,
        f_journal_entry_t.journal_date,
        f_journal_entry_t.journal_type,
        f_journal_entry_lines_t.reference_source,
        f_journal_entry_lines_t.debit_amount,
        f_journal_entry_lines_t.credit_amount
    FROM
        f_journal_entry_lines_t
    LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = f_journal_entry_lines_t.reference_id
    WHERE
        f_journal_entry_lines_t.reference_source = 'SUPPLIER' AND f_journal_entry_t.journal_type IN(
 'ADVANCE PAYMENT','DIRECTPAYMENT','PAYMENT','DEBIT','DEBIT NOTE','MANUAL','OB_SUPPLIER''CREDIT','EXPENSES','OB_SUPPLIER','PO INVOICE','REVERSE'
        ) AND m_supplier_t.supplier_name = '$supplierName' AND DATEDIFF(
            CURDATE(), f_journal_entry_lines_t.journal_date) $between
        GROUP BY
             f_journal_entry_lines_t.f_journal_entry_line_id,journal_date,journal_name
             
    UNION ALL
    SELECT
        p_po_invoice_hdr_t.bill_number AS journal_name,
        p_po_invoice_hdr_t.invoice_date AS journal_date,
        'ADVANCE' AS journal_type,
        p_po_invoice_hdr_t.invoice_type AS reference_source,
        '0' AS debit_amount,
        p_po_invoice_hdr_t.invoice_grand_total AS credit_amount
    FROM
        p_po_invoice_hdr_t
    LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id
    WHERE
        po_invoice_status = 'APPROVED' AND p_po_invoice_hdr_t.created_by='' AND invoice_date < '$year-04-01' AND m_supplier_t.supplier_name = '$supplierName' AND DATEDIFF(
        CURDATE(), p_po_invoice_hdr_t.invoice_date) $between
        GROUP BY
            po_invoice_id,bill_number,invoice_date
        ) v1
    GROUP BY
        v1.journal_date,v1.journal_name");
    
            $html = '';
            $html = "<table class='table table-striped table-hover' style='width:100%; border-collapse:collapse;'>";
            $html .= "<thead class='table-danger'><tr><th>Reference Number</th><th>Reference Date</th><th>Reference Type</th><th>Debit Amount</th><th>Credit Amount</th><th>Balance Amount</th></tr></thead><tbody>";
            
            $grandTotalDebit = 0;
            $grandTotalCredit = 0;
            $grandTotalBalAmt = 0;
            
            foreach ($detail_qry as $details) {
                // Accumulate totals
                $grandTotalDebit += $details->debit_amount;
                $grandTotalCredit += $details->credit_amount;
                $grandTotalBalAmt += $details->balance_amount;
            
                // Add rows
                $html .= "<tr>
                    <td>{$details->journal_name}</td>
                    <td>{$details->journal_date}</td>
                    <td>{$details->journal_type}</td>
                    <td>{$details->debit_amount}</td>
                    <td>{$details->credit_amount}</td>
                    <td>{$details->balance_amount}</td>
                </tr>";
            }
            
            // Add grand total row
            $html .= "<tr style='font-weight: bold;background-color: #f8d7da;'>
                <td>Grand Total</td>
                <td></td>
                <td></td>
                <td>{$grandTotalDebit}</td>
                <td>{$grandTotalCredit}</td>
                <td>{$grandTotalBalAmt}</td>
                <td></td>
            </tr>";
            
            $html .= "</tbody></table>";

    
        return response()->json(['html' => $html]);
    }
}
