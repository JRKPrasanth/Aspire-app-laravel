<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        /* ---------------- DATE RANGE ---------------- */
        $start_date = isset($request->start_date) ? $request->start_date : date('Y-m-01');
        $end_date   = isset($request->end_date) ? $request->end_date : date('Y-m-t');

        /* ---------------- 1. SUMMARY PRODUCTION ---------------- */
        $summary = DB::select("
            SELECT 
                product_classification,
                tot_bch_tkn,
                tot_prdn_qty
            FROM
            (
                SELECT 
                    p.product_classification,
                    COUNT(j.job_no) AS tot_bch_tkn,
                    ROUND(SUM(j.job_qty), 0) AS tot_prdn_qty
                FROM w_jobcard_hdr_t j
                LEFT JOIN m_products_t p ON p.product_id = j.product_id
                WHERE 
                    j.job_no LIKE '%jobpr%'
                    AND j.job_date BETWEEN ? AND ?
                    AND j.job_status IN ('qa submitted', 'store moved')
                    AND p.product_subcategory_id IN ('29','30','31','50')
                GROUP BY p.product_classification

                UNION ALL

                SELECT 
                    'GRAND TOTAL' AS product_classification,
                    COUNT(j.job_no) AS tot_bch_tkn,
                    ROUND(SUM(j.job_qty), 0) AS tot_prdn_qty
                FROM w_jobcard_hdr_t j
                LEFT JOIN m_products_t p ON p.product_id = j.product_id
                WHERE 
                    j.job_no LIKE '%jobpr%'
                    AND j.job_date BETWEEN ? AND ?
                    AND j.job_status IN ('qa submitted', 'store moved')
                    AND p.product_subcategory_id IN ('29','30','31','50')
            ) t
            ORDER BY 
                CASE WHEN product_classification = 'GRAND TOTAL' THEN 1 ELSE 0 END,
                product_classification
        ", [$start_date, $end_date, $start_date, $end_date]);

        /* ---------------- 2. SUMMARY CHART DATA ---------------- */
        $chart = DB::select("
            SELECT 
                m.product_classification,
                DATE_FORMAT(j.job_date, '%b-%y') AS month,
                ROUND(SUM(j.job_qty), 0) AS tot_prdn_qty
            FROM w_jobcard_hdr_t j
            LEFT JOIN m_products_t m ON m.product_id = j.product_id
            WHERE 
                j.job_no LIKE '%jobpr%'
                AND j.job_date BETWEEN ? AND ?
                AND j.job_status IN ('qa submitted', 'store moved')
                AND m.product_subcategory_id IN ('29','30','31','50')
            GROUP BY m.product_classification, DATE_FORMAT(j.job_date, '%b-%y')
            ORDER BY m.product_classification, STR_TO_DATE(month, '%b-%y')
        ", ['2025-04-01', $end_date]);

        /* ---------------- 3. MATERIAL ISSUED DELAY ---------------- */
        $mtrl_delay = DB::select("
        SELECT 
        v.prnt_prd,
        GROUP_CONCAT(v.sub_prd SEPARATOR '||') AS sub_products,
        COUNT(v.sub_prd) AS no_of_prods,
        MAX(v.updated_at) AS updated_at
        FROM (
        SELECT 
            wj.job_no, 
            wj.job_date,
            mj.concatenated_product AS prnt_prd,
            mp.concatenated_product AS sub_prd,
            wml.mtl_issue_qty, 
            wml.updated_at
        FROM w_materialissue_line_t wml
        LEFT JOIN w_materialissue_hdr_t wmh ON wmh.w_materialissue_hdr_id = wml.w_materialissue_hdr_id
        LEFT JOIN m_products_t mp ON mp.product_id = wml.product_id
        LEFT JOIN w_jobcard_hdr_t wj ON wj.w_jobs_hdr_id = wmh.w_jobs_hdr_id
        LEFT JOIN m_products_t mj ON mj.product_id = wj.product_id
        WHERE 
            wml.receive_status = 2 
            AND wml.created_at BETWEEN ? AND ?
        ) v
        GROUP BY v.prnt_prd
    ", [$start_date, $end_date]);


        /* ---------------- 4. BREAKDOWN MAINTENANCE ---------------- */
        $breakdown = DB::select("
            SELECT 
                (@sno := @sno + 1) AS sno,
                w_machine_hdr_t.machine_name,
                b_maintenance_t.causes AS issue_causes,
                b_maintenance_t.corrective_action AS corrective_action
            FROM b_maintenance_t
            CROSS JOIN (SELECT @sno := 0) counter
            LEFT JOIN w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id = b_maintenance_t.machine_id
            WHERE b_maintenance_t.issue_date BETWEEN ? AND ?
        ", [$start_date, $end_date]);

        /* ---------------- 5. UNDER PROCESS PRODUCTS ---------------- */
        $under_process = DB::select("
            SELECT 
                (@sno := @sno + 1) AS sno,
                CASE
                    WHEN (LENGTH(m.concatenated_product)
                        - LENGTH(REPLACE(m.concatenated_product,' ',''))) >= 2
                    THEN TRIM(
                        LEFT(
                            m.concatenated_product,
                            LENGTH(m.concatenated_product)
                            - LENGTH(SUBSTRING_INDEX(m.concatenated_product,' ',-2)) - 1
                        )
                    )
                    ELSE m.concatenated_product
                END AS prd_name,
                CASE WHEN m.product_group_id = 1 THEN 'FG' ELSE 'SFG' END AS job_type,
                m.concatenated_product,
                j.job_qty AS btch_size,
                j.job_date AS prdn_date,
                qa.qatrx_date AS qc_date
            FROM w_jobcard_hdr_t j
            CROSS JOIN (SELECT @sno := 0) counter
            LEFT JOIN m_products_t m ON m.product_id = j.product_id
            LEFT JOIN w_qa_submitstage_trx_t qa ON qa.job_no = j.w_jobs_hdr_id
            WHERE 
                j.job_status IN ('qa submitted','material issued','material received')
                AND j.qa_status = 0
                AND j.job_date BETWEEN ? AND ?
                AND m.product_group_id IN (1,4)
                AND m.qc_check = 'Yes'
                AND m.product_subcategory_id IN ('29','30','31','50','3','4','76','2','102','101','74','1','95','5','59','75','77','78')
            
        ", [$start_date, $end_date]);

        /* ---------------- 6. OPRN BDR ---------------- */
        $oprn_bdr = DB::select("
            SELECT 
                (@sno := @sno + 1) AS sno,
                j.w_jobs_hdr_id,
                j.product_id,
                p.concatenated_product,
                MIN(CASE WHEN proc.process_level = 'process-1' THEN proc.process_date END) AS strt_date,
                MAX(CASE WHEN proc.process_level = 'finalprocess' THEN proc.process_date END) AS end_date,
                SUM(proc.move_qty) AS qty,
                DATEDIFF(
                    MAX(CASE WHEN proc.process_level = 'finalprocess' THEN proc.process_date END),
                    MIN(CASE WHEN proc.process_level = 'process-1' THEN proc.process_date END)
                ) AS opn_bdr
            FROM w_jobcard_hdr_t j
            CROSS JOIN (SELECT @sno := 0) counter
            JOIN w_jobcard_process_details_t proc ON j.w_jobs_hdr_id = proc.job_id
            JOIN m_products_t p ON p.product_id = j.product_id
            WHERE proc.process_date BETWEEN ? AND ?
            GROUP BY j.w_jobs_hdr_id, j.product_id, p.concatenated_product
            HAVING strt_date IS NOT NULL AND opn_bdr >= 7
        ", [$start_date, $end_date]);

        /* ---------------- 7. TOTAL BDR (QC → Final) ---------------- */
        $tot_bdr = DB::select("
            SELECT 
                (@sno := @sno + 1) AS sno,
                j.w_jobs_hdr_id,
                j.product_id, 
                j.batch_no,
                p.concatenated_product AS prd_name,
                qa.production_qty AS qty,
                qa.qatrx_date AS prdn_date,
                (
                    SELECT MAX(proc.process_date)
                    FROM w_jobcard_process_details_t proc
                    JOIN w_jobcard_hdr_t parent ON proc.job_id = parent.w_jobs_hdr_id
                    WHERE 
                        proc.process_level = 'finalprocess'
                        AND j.bom_product_id = parent.bom_product_id
                        AND j.batch_no = parent.batch_no
                ) AS end_date,
                DATEDIFF(
                    (
                        SELECT MAX(proc.process_date)
                        FROM w_jobcard_process_details_t proc
                        JOIN w_jobcard_hdr_t parent ON proc.job_id = parent.w_jobs_hdr_id
                        WHERE 
                            proc.process_level = 'finalprocess'
                            AND j.bom_product_id = parent.bom_product_id
                            AND j.batch_no = parent.batch_no
                    ), 
                    qa.qatrx_date
                ) AS tot_bdr
            FROM w_jobcard_hdr_t j
            CROSS JOIN (SELECT @sno := 0) counter
            LEFT JOIN m_products_t p ON p.product_id = j.product_id
            LEFT JOIN w_qa_submitstage_trx_t qa ON qa.job_no = j.w_jobs_hdr_id
            WHERE j.job_date BETWEEN ? AND ?
            GROUP BY j.batch_no, p.concatenated_product
            HAVING end_date IS NOT NULL AND tot_bdr >= 12
        ", [$start_date, $end_date]);
        
        /* ---------------- 8. SUMMARY OUTWARDS CHART DATA ---------------- */
        
        $outwardchart = DB::select(" 
            SELECT
            v.product_variant_name as product_classification,
            p.concatenated_product,
            DATE_FORMAT(
                q.qoh_trx_date,
                '%b-%y'
            ) AS month,
            SUM(
                q.qoh_trx_qty * -1
            ) AS sales
        FROM
            i_qoh_detail_t q
        LEFT JOIN m_products_t p ON p.product_id = q.product_id
        LEFT JOIN m_product_variants_t v ON v.product_variant_id = p.variant_group_id
        WHERE
            q.qoh_trx_date BETWEEN ? AND ? 
            AND q.qoh_source LIKE 'dispatch' AND p.product_group_id = '1'
        GROUP BY v.product_variant_name,p.concatenated_product, DATE_FORMAT(q.qoh_trx_date, '%b-%y')
        ORDER BY v.product_variant_name,p.concatenated_product, STR_TO_DATE(month, '%b-%y')
        ", [$start_date, $end_date]);
        
        /* ---------------- 9. Month End Requirement ---------------- */
        
        $monthend = DB::select("SELECT
            mnth.product_classification as prd_class,
            mnth.concatenated_product as prd_name,
            (mnth.month_req * -1) AS qty
        FROM
            (
            SELECT
                v.product_classification,
                v.concatenated_product,
                v.stk,
                SUM(v.so_qty) so_qty,
                SUM(v.free_qty) free_qty,
                (
                    v.stk - SUM(v.so_qty + v.free_qty)
                ) AS month_req
            FROM
                (
                SELECT
                    p.product_classification,
                    p.concatenated_product,
                    COALESCE(
                        (
                            CASE WHEN SUM(q.qoh_trx_qty) > 0 THEN SUM(q.qoh_trx_qty) ELSE 0
                        END
                    ),
                    0
            ) AS stk,
            0 AS so_qty,
            0 AS free_qty
        FROM
            m_products_t p
        LEFT JOIN i_qoh_detail_t q ON
            p.product_id = q.product_id AND q.qoh_source != 'WIP Store Move' AND q.qoh_source != 'MATERIAL RECEIVE' AND q.qoh_source != 'SALES RETURN-SCRAP' AND q.qoh_source != 'Return store move' AND(
                q.subinventory_id = 3 OR q.subinventory_id = 4
            )
        WHERE
            p.product_group_id = 1 AND p.active = 'Yes' AND q.qoh_trx_date <= ?
        GROUP BY
            p.concatenated_product
        UNION ALL
        SELECT
            p.product_classification,
            p.concatenated_product,
            0 AS stk,
            COALESCE(
                SUM(o.qty - o.dispatched_qty),
                0
            ) AS so_qty,
            COALESCE(SUM(o.free_qty),
            0) AS free_qty
        FROM
            m_products_t p
        LEFT JOIN s_salesorder_lines_t o ON
            o.product_id = p.product_id
        LEFT JOIN s_salesorder_hdr_t s ON
            s.sales_hdr_id = o.sales_hdr_id AND(
                s.order_status_id = 'APPROVED' OR s.order_status_id = 'INITIATED'
            )
        WHERE
            p.product_group_id = 1 AND p.active = 'Yes' AND s.sales_order_date <= ? AND o.pending_qty > 0
        GROUP BY
            p.concatenated_product
        ) v
        GROUP BY
            v.concatenated_product
        HAVING
            month_req < 0
        ) mnth
        ", [$end_date,$end_date]);

        $pageMethod = '';
        /* ---------------- RETURN TO BLADE ---------------- */
        return view('production.dashboard',[
            'summary'       => $summary,
            'chart'         => $chart,
            'mtrl_delay'    => $mtrl_delay,
            'breakdown'     => $breakdown,
            'under_process' => $under_process,
            'oprn_bdr'      => $oprn_bdr,
            'tot_bdr'       => $tot_bdr,
            'outwardchart'  => $outwardchart,
            'monthend'      => $monthend,
            'start_date'    => $start_date,
            'end_date'      => $end_date,
        ]);
        
    }
    
public function outwardByClassAjax(Request $request)
{
    $classId = $request->input('classId');
    
    $class = $request->classification;
    $rawEnd = trim((string) $request->end_date);

    if ($rawEnd !== '') {
        // if your end_date comes as dd-mm-yyyy, use createFromFormat:
        // $end_date = Carbon::createFromFormat('d-m-Y', $rawEnd);
        // otherwise parse normally:
        $end_date = Carbon::parse($rawEnd);
    } else {
        $end_date = Carbon::today();
    }

    // start_date = end_date - 3 months
    $start_date = (clone $end_date)->subMonths(3);

    // format dates for SQL (Y-m-d)
    $start_sql = $start_date->format('Y-m-d');
    $end_sql   = $end_date->format('Y-m-d');

    $rows = DB::select("
        SELECT
            v.product_variant_name as product_classification,
            p.concatenated_product,
            DATE_FORMAT(q.qoh_trx_date, '%b-%y') AS month,
            SUM(q.qoh_trx_qty * -1) AS sales
        FROM i_qoh_detail_t q
        LEFT JOIN m_products_t p ON p.product_id = q.product_id
        LEFT JOIN m_product_variants_t v ON v.product_variant_id = p.variant_group_id
        WHERE q.qoh_trx_date BETWEEN ? AND ?
          AND q.qoh_source LIKE 'dispatch'
          AND p.product_group_id = '1'
        GROUP BY
            v.product_variant_name,
            p.concatenated_product,
            DATE_FORMAT(q.qoh_trx_date, '%b-%y')
        ORDER BY
            v.product_variant_name,
            p.concatenated_product,
            STR_TO_DATE(month, '%b-%y')
    ", [$start_sql, $end_sql]);

    $collection = collect($rows);

    $filtered = $collection->where('product_classification', $class);

    $grouped = $filtered->groupBy('concatenated_product')->map(function ($items) {
        return [
            'months' => $items->pluck('month'),
            'sales'  => $items->pluck('sales'),
        ];
    });

    return response()->json($grouped);
}


}