<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItcreversalController extends Controller
{
    protected $data = [];

    public function __construct()
    {
        $this->data = [
            'urlmenu' => '',
            'pageMethod' => request()->route() ? request()->route()->getName() : '',
            'pageFormtype' => 'ajax'
        ];
    }

    /*--------------------------------------------------------------
    | Views
    --------------------------------------------------------------*/

    public function costrptindex()
    {
        return view('itccostrpt.itcrvrsl', $this->data);
    }

    public function costrptindex1()
    {
        return view('itccostrpt.itcsummary', $this->data);
    }

 
    
 public function getitccostData(Request $request)
    {
        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : NULL;
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : NULL;

$SQL = "SELECT
    samp.FnYr,
    samp.tax_credit,
    samp.p_product_name,
    samp.name,
    samp.batch_number,
    samp.sls,

    /* Main Job Info */
    samp.oprn,
    samp.job_no,
    samp.main_comp_prod_name,
    samp.prod_uom,
    CASE WHEN samp.oprn > 0 THEN ((samp.main_comp_qty / samp.oprn) * samp.sls) ELSE 0 END as smp_main_comp_qty,
    samp.comp_id,
    samp.comp_grp_name,
    samp.comp_name,
    samp.comp_uom,
    samp.lot_no,
    samp.comp_qty,
    samp.comp_rate,
    samp.comp_val,

    /* Adjusted Component Quantity */
    
        CASE 
            WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0 THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
            ELSE 0 
        END
     AS smp_cnsmd_qty,
/* === adjusted consumed value (base) === */
(
    (
        CASE 
            WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0
                 THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
            ELSE 0 
        END
    ) * samp.comp_rate
) AS smp_cnsmd_qty_val,

/* === ITC TAX BASE === */

    (
        (
            CASE 
                WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0
                     THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
                ELSE 0 
            END
        ) * samp.comp_rate
    ) 
    * IFNULL(purch.tax_group, 0) / 100
 AS smp_cnsmd_qty_tax,

/* === ITC SGST === */

    CASE 
        WHEN purch.tax_grp_name NOT LIKE '%IGST%'
        THEN (
            (
                (
                    CASE 
                        WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0
                            THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
                        ELSE 0 
                    END
                ) 
                * samp.comp_rate
            ) * purch.tax_group / 100
        ) / 2
        ELSE 0
    END
 AS ITC_SGST,

/* === ITC CGST === */

    CASE 
        WHEN purch.tax_grp_name NOT LIKE '%IGST%'
        THEN (
            (
                (
                    CASE 
                        WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0
                            THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
                        ELSE 0 
                    END
                ) 
                * samp.comp_rate
            ) * purch.tax_group / 100
        ) / 2
        ELSE 0
    END AS ITC_CGST,

/* === ITC IGST === */

    CASE 
        WHEN purch.tax_grp_name LIKE '%IGST%'
        THEN (
            (
                (
                    CASE 
                        WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0
                            THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
                        ELSE 0 
                    END
                ) 
                * samp.comp_rate
            ) * purch.tax_group / 100
        )
        ELSE 0
    END AS ITC_IGST,

    /* Purchase + Tax + GRN Info */
    IFNULL(concat(purch.tax_group,'%'),0) AS tax_group,
    IFNULL(purch.tax_grp_name,0) AS tax_group_name,
    ROUND(
    CASE 
        WHEN purch.tax_grp_name NOT LIKE '%IGST%' THEN purch.tax_amount / 2
        ELSE 0
    END, 2
) AS SGST,

ROUND(
    CASE 
        WHEN purch.tax_grp_name NOT LIKE '%IGST%' THEN purch.tax_amount / 2
        ELSE 0
    END, 2
) AS CGST,

ROUND(
    CASE 
        WHEN purch.tax_grp_name LIKE '%IGST%' THEN purch.tax_amount
        ELSE 0
    END, 2
) AS IGST,
    ifnull(purch.tax_amount,0) as pur_tax_amt,
    IFNULL(purch.credit_taken,'') AS credit_taken,
    IFNULL(purch.credit_date,'') AS credit_date,
    ROUND(
    CASE 
        WHEN purch.credit_taken = 'Yes' THEN purch.tax_amount
        ELSE 0
    END, 2
) AS credit_taken_tax

FROM
(
    /* ==================================================
       Sample-only + first-month-per-product+batch
       ================================================== */
    SELECT
        inv.FnYr,
        inv.tax_credit,
        inv.p_product_name,
        inv.name,
        inv.batch_number,
        inv.sls,
        IFNULL(jsel.oprn, 0) AS oprn,
        IFNULL(hdr_map.job_number,'') AS job_no,
        IFNULL(fc.comp_prod_name, '') AS main_comp_prod_name,
        IFNULL(fc.prod_uom, '') AS prod_uom,
        ROUND(IFNULL(fc.comp_prod_qty, 0),2) AS main_comp_qty,
        IFNULL(ca.comp_id, 0) AS comp_id,
        IFNULL(ca.comp_grp_name, '') AS comp_grp_name,
        IFNULL(ca.comp_name, '') AS comp_name,
        IFNULL(ca.comp_uom, '') AS comp_uom,
        IFNULL(ca.lot_no, '') AS lot_no,
        ROUND(IFNULL(ca.comp_qty, 0),2) AS comp_qty,
        ROUND(IFNULL(ca.comp_rate, 0),2) AS comp_rate,
        ROUND(IFNULL((ca.comp_qty * ca.comp_rate),0),2) AS comp_val
    FROM
    (
        /* ============================
           1. Aggregate sample sales
           ============================ */
        SELECT
            DATE_FORMAT(h.invoice_date, '%b-%y') AS FnYr,
            l.product_id AS id,
            p.tax_credit,
            TRIM(SUBSTRING(p.concatenated_product, 1,
                LENGTH(p.concatenated_product)
                - LENGTH(SUBSTRING_INDEX(p.concatenated_product, ' ', -2)) - 1
            )) AS p_product_name,
            TRIM(SUBSTRING_INDEX(p.concatenated_product, ' ', -2)) AS pack_size,
            p.concatenated_product AS name,
            l.batch_number,
            SUM(l.qty) AS sls
        FROM s_invoice_lines_t l
        JOIN s_invoice_hdr_t h ON h.invoice_hdr_id = l.invoice_hdr_id
        JOIN m_products_t p ON p.product_id = l.product_id
        JOIN (
            /* first month per product + batch */
            SELECT 
                l.product_id, 
                l.batch_number, 
                MIN(DATE_FORMAT(h.invoice_date, '%b-%y')) AS first_month
            FROM s_invoice_lines_t l
            JOIN s_invoice_hdr_t h ON h.invoice_hdr_id = l.invoice_hdr_id
            WHERE h.invoice_type = 'sample'
            GROUP BY l.product_id, l.batch_number
        ) first_sale
        ON first_sale.product_id = l.product_id
       AND first_sale.batch_number = l.batch_number
        WHERE h.invoice_type = 'sample'
          AND DATE_FORMAT(h.invoice_date, '%b-%y') = first_sale.first_month   /* ONLY FIRST MONTH */
          AND h.invoice_date BETWEEN ? AND ?
          AND p.product_group_id = '1' AND p.concatenated_product NOT LIKE '%1-3-2%'
        GROUP BY DATE_FORMAT(h.invoice_date, '%b-%y'),
                 l.product_id,
                 p.tax_credit,
                 p.concatenated_product,
                 l.batch_number
    ) inv

    /* ==================================================
       2. Join first job, main component, other components
       ================================================== */
    LEFT JOIN (
 SELECT j.product_id, j.batch_no, MIN(j.job_date) AS min_job_date
    FROM w_jobcard_hdr_t j
    WHERE j.job_status = 'QA SUBMITTED'
    GROUP BY j.product_id, j.batch_no
) jmin 
    ON jmin.product_id = inv.id 
   AND jmin.batch_no   = inv.batch_number

LEFT JOIN (
    SELECT  j.product_id, j.batch_no,
           MIN(j.w_jobs_hdr_id) AS job_id,
           MIN(j.job_date)      AS job_date,
           SUM(j.job_qty)       AS oprn
    FROM w_jobcard_hdr_t j
    WHERE j.job_status = 'QA SUBMITTED'
    GROUP BY j.product_id, j.batch_no, j.job_date
    HAVING j.job_date = (
        SELECT MIN(j2.job_date)
        FROM w_jobcard_hdr_t j2
        WHERE j2.product_id = j.product_id
          AND j2.batch_no   = j.batch_no
          AND j2.job_status = 'QA SUBMITTED'
    )
) jsel 
    ON jsel.product_id = inv.id 
   AND jsel.batch_no   = inv.batch_number



/* ============================================================
   3. FETCH FIRST COMPONENT (MAIN COMPONENT)
   ============================================================ */

LEFT JOIN (
    SELECT mr.job_id,mr.job_number,
           MIN(ml.r_job_cost_lines_id) AS min_line_id
    FROM r_job_cost_lines_tbl ml
    JOIN r_job_cost_hdr_tbl mr 
         ON mr.r_job_cost_hdr_id = ml.r_job_cost_hdr_id
    GROUP BY mr.job_id
) fc_min 
    ON fc_min.job_id = jsel.job_id

LEFT JOIN (
    SELECT ml.r_job_cost_lines_id,
           mr.job_id,
           mr.job_number,   
           ml.product_id   AS comp_prod_id,
           ml.product_name AS comp_prod_name,
           um.uom_code     AS prod_uom,
           ml.qty         AS comp_prod_qty,
           ml.rate         AS comp_prod_rate
    FROM r_job_cost_lines_tbl ml
    JOIN r_job_cost_hdr_tbl mr 
         ON mr.r_job_cost_hdr_id = ml.r_job_cost_hdr_id
    JOIN m_products_t mt
        ON mt.product_id=ml.product_id
    JOIN m_uom_codes_t um
        ON um.uom_code_id=mt.trx_uom_id
) fc 
    ON fc.r_job_cost_lines_id = fc_min.min_line_id
   AND fc.job_id              = jsel.job_id



/* ============================================================
   4. CONS_QTY FOR MAIN COMPONENT
   ============================================================ */

LEFT JOIN (
    SELECT mr.job_id,
           mr.job_number,
           ml.product_id,
           ROUND(SUM(ml.qty),2) AS cons_qty
    FROM r_job_cost_lines_tbl ml
    JOIN r_job_cost_hdr_tbl mr 
         ON mr.r_job_cost_hdr_id = ml.r_job_cost_hdr_id
    GROUP BY mr.job_id, ml.product_id
) cons_main 
    ON cons_main.job_id    = jsel.job_id
   AND cons_main.product_id = fc.comp_prod_id



/* ============================================================
   5. CORRECT HEADER FOR MAIN COMPONENT (GREEN HEADER)
   ============================================================ */

LEFT JOIN (
    SELECT h2.product_id, h2.batch_number,h2.job_number,
           MIN(h2.r_job_cost_hdr_id) AS comp_hdr_id
    FROM r_job_cost_hdr_tbl h2
    GROUP BY h2.product_id, h2.batch_number
) hdr_map 
    ON hdr_map.product_id  = fc.comp_prod_id
   AND hdr_map.batch_number = jsel.batch_no



/* ============================================================
   6. ALL COMPONENTS (EXCEPT MAIN) — Option B2
   ============================================================ */

LEFT JOIN (
    SELECT ml.r_job_cost_lines_id AS comp_line_id,
           mr.r_job_cost_hdr_id,
           ml.product_id      AS comp_id,
           ml.product_group   AS prd_grp,
           mp.group_name      AS comp_grp_name,
           ml.product_name    AS comp_name,
           um.uom_code        AS comp_uom,
           ml.batch_number    AS lot_no,
           ml.qty             AS comp_qty,
           ml.rate            AS comp_rate
    FROM r_job_cost_lines_tbl ml
    JOIN r_job_cost_hdr_tbl mr 
         ON mr.r_job_cost_hdr_id = ml.r_job_cost_hdr_id
    JOIN m_product_groups_t mp
        ON mp.product_group_id=ml.product_group
    JOIN m_products_t mt
        ON mt.product_id=ml.product_id
    JOIN m_uom_codes_t um
        ON um.uom_code_id=mt.trx_uom_id
    WHERE ml.product_group = '2'
) ca 
    ON ca.r_job_cost_hdr_id = hdr_map.comp_hdr_id
   AND (fc.comp_prod_id IS NULL OR ca.comp_id <> fc.comp_prod_id)   /* REMOVE MAIN COMPONENT */



/* ============================================================
   7. FINAL SAMPLE-ONLY LOCK
   ============================================================ */
    WHERE inv.sls > 0
) samp

/* ================================================
   Compute total component qty per job (for adj_comp_qty)
   ================================================ */
LEFT JOIN (
    SELECT mr.job_number AS job_no, SUM(ml.qty) AS total_comp_qty
    FROM r_job_cost_hdr_tbl mr
    JOIN r_job_cost_lines_tbl ml 
        ON mr.r_job_cost_hdr_id = ml.r_job_cost_hdr_id
    WHERE ml.product_group = '2'
    GROUP BY mr.job_number
) comp_sum
ON comp_sum.job_no = samp.job_no

/* ================================================
   Purchase + Tax + GRN Info join
   ================================================ */
LEFT JOIN (
        SELECT 
        DATE_FORMAT(h.credit_date, '%b-%y') AS FnYr,
        l.product_id,
        p.concatenated_product,
        tg.display_name AS tax_group,
        tg.tax_group_name AS tax_grp_name,
        SUM(l.tax_amount) AS tax_amount,
        h.credit_taken,
        h.credit_date,
        h.grn_number,
        q.batch_number
    FROM p_po_invoice_lines_t l
    JOIN p_po_invoice_hdr_t h 
        ON h.po_invoice_id = l.po_invoice_id
    JOIN m_products_t p 
        ON p.product_id = l.product_id
    LEFT JOIN m_tax_group_t tg 
        ON tg.tax_group_id = l.tax_group_id
    LEFT JOIN (
        SELECT d.product_id, d.batch_number, d.qoh_trx_qty AS qty, d.grn_id
        FROM i_qoh_detail_t d
        WHERE d.qoh_source = 'PURCHASE_STOREMOVE'
    ) q 
        ON q.grn_id = h.grn_number
       AND q.product_id = l.product_id
    WHERE h.credit_date BETWEEN ? AND ?
    GROUP BY DATE_FORMAT(h.credit_date, '%b-%y'),
             l.product_id,
             p.concatenated_product,
             tg.display_name,
             h.credit_taken,
             h.credit_date,
             h.grn_number,
             q.batch_number
) purch
ON purch.product_id   = samp.comp_id
AND purch.batch_number = samp.lot_no

ORDER BY 
    STR_TO_DATE(samp.FnYr, '%b-%y'),
    samp.p_product_name,
    samp.batch_number";

    $results = \DB::select($SQL, [$start_date, $end_date, $start_date, $end_date]);

    return response()->json(['data' => $results]);

}    



public function getSummaryQuery()
{
    return "
SELECT
    IFNULL(MonthYY, 'Grand Total') AS MonthYY,
    Taxable_Val,
    IGST_Tax,
    SGST_Tax,
    CGST_Tax,
    RefDate,
    NoOfDays,
    Int_IGST,
    Int_SGST,
    Int_CGST
FROM
(
    /* Aggregate per MonthYY with ROLLUP */
    SELECT
        MonthYY,
        SUM(TaxableVal) AS Taxable_Val,
        SUM(IGST_Tax)   AS IGST_Tax,
        SUM(SGST_Tax)   AS SGST_Tax,
        SUM(CGST_Tax)   AS CGST_Tax,
    CASE 
        WHEN MonthYY IS NOT NULL THEN
            DATE_FORMAT(
                DATE_ADD(
                    STR_TO_DATE(CONCAT('01-', MonthYY), '%d-%b-%y'),
                    INTERVAL 1 MONTH
                ),
            '%Y-%m-21')
        ELSE NULL
    END AS RefDate,

    /* No. of days = today - RefDate */
    CASE 
        WHEN MonthYY IS NOT NULL THEN
            DATEDIFF(
                CURDATE(),
                STR_TO_DATE(
                    DATE_FORMAT(
                        DATE_ADD(
                            STR_TO_DATE(CONCAT('01-', MonthYY), '%d-%b-%y'),
                            INTERVAL 1 MONTH
                        ),
                    '%Y-%m-21'),
                '%Y-%m-%d')
            )
        ELSE ''
    END AS NoOfDays,
    round((sum(IGST_Tax * (18/100)*  CASE 
        WHEN MonthYY IS NOT NULL THEN
            DATEDIFF(
                CURDATE(),
                STR_TO_DATE(
                    DATE_FORMAT(
                        DATE_ADD(
                            STR_TO_DATE(CONCAT('01-', MonthYY), '%d-%b-%y'),
                            INTERVAL 1 MONTH
                        ),
                    '%Y-%m-21'),
                '%Y-%m-%d')
            )
        ELSE 0
    END)/365) ,2) as Int_IGST,
    round((sum(SGST_Tax * (18/100)*  CASE 
        WHEN MonthYY IS NOT NULL THEN
            DATEDIFF(
                CURDATE(),
                STR_TO_DATE(
                    DATE_FORMAT(
                        DATE_ADD(
                            STR_TO_DATE(CONCAT('01-', MonthYY), '%d-%b-%y'),
                            INTERVAL 1 MONTH
                        ),
                    '%Y-%m-21'),
                '%Y-%m-%d')
            )
        ELSE 0
    END)/365),2) as Int_SGST,
    round((sum(CGST_Tax * (18/100)*  CASE 
        WHEN MonthYY IS NOT NULL THEN
            DATEDIFF(
                CURDATE(),
                STR_TO_DATE(
                    DATE_FORMAT(
                        DATE_ADD(
                            STR_TO_DATE(CONCAT('01-', MonthYY), '%d-%b-%y'),
                            INTERVAL 1 MONTH
                        ),
                    '%Y-%m-21'),
                '%Y-%m-%d')
            )
        ELSE 0
    END)/365),2) as Int_CGST
    
    FROM
    (
        /* === inner projection: produce MonthYY + values from your detailed query === */
        SELECT
            detailed.FnYr AS MonthYY,
            -- Taxable base (adjusted consumed value)
            smp_cnsmd_qty_val AS TaxableVal,
            -- ITC splits (these columns are coming from your detailed SELECT)
            ITC_IGST   AS IGST_Tax,
            ITC_SGST   AS SGST_Tax,
            ITC_CGST   AS CGST_Tax
        FROM
        (
            /* ===========================
               YOUR FULL DETAILED QUERY
               (copy / paste your working detailed SELECT here)
               =========================== */
            SELECT 
                samp.FnYr,
                samp.tax_credit,
                samp.p_product_name,
                samp.name,
                samp.batch_number,
                samp.sls,

                /* Main Job Info */
                samp.oprn,
                samp.job_no,
                samp.main_comp_prod_name,
                samp.prod_uom,
                (CASE WHEN samp.oprn > 0 THEN ((samp.main_comp_qty / samp.oprn) * samp.sls) ELSE 0 END) as smp_main_comp_qty,
                samp.comp_id,
                samp.comp_grp_name,
                samp.comp_name,
                samp.comp_uom,
                samp.lot_no,
                samp.comp_qty,
                samp.comp_rate,
                samp.comp_val,

                /* Adjusted Component Quantity */
                    (
                    CASE 
                        WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0 THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
                        ELSE 0 
                    END
                ) AS smp_cnsmd_qty,

                /* adjusted consumed value (base) */
                (
                    (
                        CASE 
                            WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0
                                 THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
                            ELSE 0 
                        END
                    ) * samp.comp_rate
                ) AS smp_cnsmd_qty_val,

                /* ITC TAX BASE */
                (
                    (
                        (
                            CASE 
                                WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0
                                     THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
                                ELSE 0 
                            END
                        ) * samp.comp_rate
                    ) 
                    * IFNULL(purch.tax_group, 0) / 100
                ) AS smp_cnsmd_qty_tax,

                /* ITC SGST */
                (
                    CASE 
                        WHEN purch.tax_grp_name NOT LIKE '%IGST%'
                        THEN (
                            (
                                (
                                    CASE 
                                        WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0
                                            THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
                                        ELSE 0 
                                    END
                                ) 
                                * samp.comp_rate
                            ) * purch.tax_group / 100
                        ) / 2
                        ELSE 0
                    END
                ) AS ITC_SGST,

                /* ITC CGST */
                (
                    CASE 
                        WHEN purch.tax_grp_name NOT LIKE '%IGST%'
                        THEN (
                            (
                                (
                                    CASE 
                                        WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0
                                            THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
                                        ELSE 0 
                                    END
                                ) 
                                * samp.comp_rate
                            ) * purch.tax_group / 100
                        ) / 2
                        ELSE 0
                    END
                ) AS ITC_CGST,

                /* ITC IGST */
                (
                    CASE 
                        WHEN purch.tax_grp_name LIKE '%IGST%'
                        THEN (
                            (
                                (
                                    CASE 
                                        WHEN comp_sum.total_comp_qty > 0 and samp.oprn > 0
                                            THEN (((samp.main_comp_qty / samp.oprn) * samp.sls) / comp_sum.total_comp_qty) * samp.comp_qty
                                        ELSE 0 
                                    END
                                ) 
                                * samp.comp_rate
                            ) * purch.tax_group / 100
                        )
                        ELSE 0
                    END
                ) AS ITC_IGST,

                /* Purchase + Tax + GRN Info */
                IFNULL(concat(purch.tax_group,'%'),0) AS tax_group,
                IFNULL(purch.tax_grp_name,0) AS tax_group_name,
                (
                    CASE 
                        WHEN purch.tax_grp_name NOT LIKE '%IGST%' THEN purch.tax_amount / 2
                        ELSE 0
                    END
                ) AS SGST,

                (
                    CASE 
                        WHEN purch.tax_grp_name NOT LIKE '%IGST%' THEN purch.tax_amount / 2
                        ELSE 0
                    END
                ) AS CGST,

                (
                    CASE 
                        WHEN purch.tax_grp_name LIKE '%IGST%' THEN purch.tax_amount
                        ELSE 0
                    END
                ) AS IGST,
                IFNULL(purch.tax_amount,0) as pur_tax_amt,
                IFNULL(purch.credit_taken,'') AS credit_taken,
                IFNULL(purch.credit_date,'') AS credit_date,
                (
                    CASE 
                        WHEN purch.credit_taken = 'Yes' THEN purch.tax_amount
                        ELSE 0
                    END
                ) AS credit_taken_tax

            FROM
            (
                /* ==================================================
                   Sample-only + first-month-per-product+batch (inv derivation)
                   ================================================== */
                SELECT
                    inv.FnYr,
                    inv.tax_credit,
                    inv.p_product_name,
                    inv.name,
                    inv.batch_number,
                    inv.sls,
                    IFNULL(jsel.oprn, 0) AS oprn,
                    IFNULL(hdr_map.job_number,'') AS job_no,
                    IFNULL(fc.comp_prod_name, '') AS main_comp_prod_name,
                    IFNULL(fc.prod_uom, '') AS prod_uom,
                    ROUND(IFNULL(fc.comp_prod_qty, 0),2) AS main_comp_qty,
                    IFNULL(ca.comp_id, 0) AS comp_id,
                    IFNULL(ca.comp_grp_name, '') AS comp_grp_name,
                    IFNULL(ca.comp_name, '') AS comp_name,
                    IFNULL(ca.comp_uom, '') AS comp_uom,
                    IFNULL(ca.lot_no, '') AS lot_no,
                    ROUND(IFNULL(ca.comp_qty, 0),2) AS comp_qty,
                    ROUND(IFNULL(ca.comp_rate, 0),2) AS comp_rate,
                    ROUND(IFNULL((ca.comp_qty * ca.comp_rate),0),2) AS comp_val
                FROM
                (
                    /* ============================
                       1. Aggregate sample sales (first month only)
                       ============================ */
                    SELECT
                        DATE_FORMAT(h.invoice_date, '%b-%y') AS FnYr,
                        l.product_id AS id,
                        p.tax_credit,
                        TRIM(SUBSTRING(p.concatenated_product, 1,
                            LENGTH(p.concatenated_product)
                            - LENGTH(SUBSTRING_INDEX(p.concatenated_product, ' ', -2)) - 1
                        )) AS p_product_name,
                        TRIM(SUBSTRING_INDEX(p.concatenated_product, ' ', -2)) AS pack_size,
                        p.concatenated_product AS name,
                        l.batch_number,
                        SUM(l.qty) AS sls
                    FROM s_invoice_lines_t l
                    JOIN s_invoice_hdr_t h ON h.invoice_hdr_id = l.invoice_hdr_id
                    JOIN m_products_t p ON p.product_id = l.product_id
                    JOIN (
                        /* first month per product + batch */
                        SELECT 
                            l.product_id, 
                            l.batch_number, 
                            MIN(DATE_FORMAT(h.invoice_date, '%b-%y')) AS first_month
                        FROM s_invoice_lines_t l
                        JOIN s_invoice_hdr_t h ON h.invoice_hdr_id = l.invoice_hdr_id
                        WHERE h.invoice_type = 'sample'
                        GROUP BY l.product_id, l.batch_number
                    ) first_sale
                    ON first_sale.product_id = l.product_id
                   AND first_sale.batch_number = l.batch_number
                    WHERE h.invoice_type = 'sample'
                      AND DATE_FORMAT(h.invoice_date, '%b-%y') = first_sale.first_month   /* ONLY FIRST MONTH */
                      AND h.invoice_date BETWEEN :start_date AND :end_date
                      AND p.product_group_id = '1'
                    GROUP BY DATE_FORMAT(h.invoice_date, '%b-%y'),
                             l.product_id,
                             p.tax_credit,
                             p.concatenated_product,
                             l.batch_number
                ) inv

                /* ============================================
                   2. Join job + components (jsel, fc, ca, hdr_map, etc.)
                   ============================================ */
                LEFT JOIN (
                    SELECT j.product_id, j.batch_no, MIN(j.job_date) AS min_job_date
                    FROM w_jobcard_hdr_t j
                    WHERE j.job_status = 'QA SUBMITTED'
                    GROUP BY j.product_id, j.batch_no
                ) jmin 
                    ON jmin.product_id = inv.id 
                   AND jmin.batch_no   = inv.batch_number

                LEFT JOIN (
                    SELECT  j.product_id, j.batch_no,
                           MIN(j.w_jobs_hdr_id) AS job_id,
                           MIN(j.job_date)      AS job_date,
                           SUM(j.job_qty)       AS oprn
                    FROM w_jobcard_hdr_t j
                    WHERE j.job_status = 'QA SUBMITTED'
                    GROUP BY j.product_id, j.batch_no, j.job_date
                    HAVING j.job_date = (
                        SELECT MIN(j2.job_date)
                        FROM w_jobcard_hdr_t j2
                        WHERE j2.product_id = j.product_id
                          AND j2.batch_no   = j.batch_no
                          AND j2.job_status = 'QA SUBMITTED'
                    )
                ) jsel 
                    ON jsel.product_id = inv.id 
                   AND jsel.batch_no   = inv.batch_number

                /* fetch first component (fc_min + fc) */
                LEFT JOIN (
                    SELECT mr.job_id,mr.job_number,
                           MIN(ml.r_job_cost_lines_id) AS min_line_id
                    FROM r_job_cost_lines_tbl ml
                    JOIN r_job_cost_hdr_tbl mr 
                         ON mr.r_job_cost_hdr_id = ml.r_job_cost_hdr_id
                    GROUP BY mr.job_id
                ) fc_min 
                    ON fc_min.job_id = jsel.job_id

                LEFT JOIN (
                    SELECT ml.r_job_cost_lines_id,
                           mr.job_id,
                           mr.job_number,   
                           ml.product_id   AS comp_prod_id,
                           ml.product_name AS comp_prod_name,
                           um.uom_code     AS prod_uom,
                           ml.qty          AS comp_prod_qty,
                           ml.rate         AS comp_prod_rate
                    FROM r_job_cost_lines_tbl ml
                    JOIN r_job_cost_hdr_tbl mr 
                         ON mr.r_job_cost_hdr_id = ml.r_job_cost_hdr_id
                    JOIN m_products_t mt
                        ON mt.product_id=ml.product_id
                    JOIN m_uom_codes_t um
                        ON um.uom_code_id=mt.trx_uom_id
                ) fc 
                    ON fc.r_job_cost_lines_id = fc_min.min_line_id
                   AND fc.job_id              = jsel.job_id

                /* cons qty for main component */
                LEFT JOIN (
                    SELECT mr.job_id,
                           mr.job_number,
                           ml.product_id,
                           ROUND(SUM(ml.qty),2) AS cons_qty
                    FROM r_job_cost_lines_tbl ml
                    JOIN r_job_cost_hdr_tbl mr 
                         ON mr.r_job_cost_hdr_id = ml.r_job_cost_hdr_id
                    GROUP BY mr.job_id, ml.product_id
                ) cons_main 
                    ON cons_main.job_id    = jsel.job_id
                   AND cons_main.product_id = fc.comp_prod_id

                /* hdr_map */
                LEFT JOIN (
                    SELECT h2.product_id, h2.batch_number,h2.job_number,
                           MIN(h2.r_job_cost_hdr_id) AS comp_hdr_id
                    FROM r_job_cost_hdr_tbl h2
                    GROUP BY h2.product_id, h2.batch_number
                ) hdr_map 
                    ON hdr_map.product_id  = fc.comp_prod_id
                   AND hdr_map.batch_number = jsel.batch_no

                /* all components (ca) */
                LEFT JOIN (
                    SELECT ml.r_job_cost_lines_id AS comp_line_id,
                           mr.r_job_cost_hdr_id,
                           ml.product_id      AS comp_id,
                           ml.product_group   AS prd_grp,
                           mp.group_name      AS comp_grp_name,
                           ml.product_name    AS comp_name,
                           um.uom_code        AS comp_uom,
                           ml.batch_number    AS lot_no,
                           ml.qty             AS comp_qty,
                           ml.rate            AS comp_rate
                    FROM r_job_cost_lines_tbl ml
                    JOIN r_job_cost_hdr_tbl mr 
                         ON mr.r_job_cost_hdr_id = ml.r_job_cost_hdr_id
                    JOIN m_product_groups_t mp
                        ON mp.product_group_id=ml.product_group
                    JOIN m_products_t mt
                        ON mt.product_id=ml.product_id
                    JOIN m_uom_codes_t um
                        ON um.uom_code_id=mt.trx_uom_id
                    WHERE ml.product_group = '2'
                ) ca 
                    ON ca.r_job_cost_hdr_id = hdr_map.comp_hdr_id
                   AND (fc.comp_prod_id IS NULL OR ca.comp_id <> fc.comp_prod_id)

                WHERE inv.sls > 0
            ) samp

            /* total component qty per job (for prorating) */
            LEFT JOIN (
                SELECT mr.job_number AS job_no, SUM(ml.qty) AS total_comp_qty
                FROM r_job_cost_hdr_tbl mr
                JOIN r_job_cost_lines_tbl ml 
                    ON mr.r_job_cost_hdr_id = ml.r_job_cost_hdr_id
                WHERE ml.product_group = '2'
                GROUP BY mr.job_number
            ) comp_sum
                ON comp_sum.job_no = samp.job_no

            /* purchase + tax join */
            LEFT JOIN (
                SELECT 
                    DATE_FORMAT(h.credit_date, '%b-%y') AS FnYr,
                    l.product_id,
                    p.concatenated_product,
                    tg.display_name AS tax_group,
                    tg.tax_group_name AS tax_grp_name,
                    SUM(l.tax_amount) AS tax_amount,
                    h.credit_taken,
                    h.credit_date,
                    h.grn_number,
                    q.batch_number
                FROM p_po_invoice_lines_t l
                JOIN p_po_invoice_hdr_t h 
                    ON h.po_invoice_id = l.po_invoice_id
                JOIN m_products_t p 
                    ON p.product_id = l.product_id
                LEFT JOIN m_tax_group_t tg 
                    ON tg.tax_group_id = l.tax_group_id
                LEFT JOIN (
                    SELECT d.product_id, d.batch_number, d.qoh_trx_qty AS qty, d.grn_id
                    FROM i_qoh_detail_t d
                    WHERE d.qoh_source = 'PURCHASE_STOREMOVE'
                ) q 
                    ON q.grn_id = h.grn_number
                   AND q.product_id = l.product_id
                WHERE h.credit_date BETWEEN :start_date AND :end_date
                GROUP BY DATE_FORMAT(h.credit_date, '%b-%y'),
                         l.product_id,
                         p.concatenated_product,
                         tg.display_name,
                         h.credit_taken,
                         h.credit_date,
                         h.grn_number,
                         q.batch_number
            ) purch
                ON purch.product_id   = samp.comp_id
               AND purch.batch_number = samp.lot_no

            -- NOTE: removed ORDER BY from detailed query to allow correct aggregation later
        ) AS detailed WHERE detailed.credit_taken='Yes' 
        -- end of detailed SELECT
    ) AS inner_proj 
    GROUP BY MonthYY
    WITH ROLLUP
) AS aggregated 
-- final outer ordering, place Grand Total last
ORDER BY
    CASE WHEN MonthYY IS NULL THEN 1 ELSE 0 END,
    CASE 
        WHEN MonthYY = 'Grand Total' THEN NULL 
        ELSE STR_TO_DATE(CONCAT('01-', MonthYY), '%d-%b-%y') 
    END
    ";

  }

}