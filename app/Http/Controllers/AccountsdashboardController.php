<?php

namespace App\Http\Controllers;
use session;
use Illuminate\Http\Request;

class AccountsdashboardController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }

    public function index(Request $request)
    {


        $cur_year = date('Y');
        $year = $cur_year - 1;
        $preyr_date = \Session::get('griddate');
        $start_date = "2025-04-01";
        $curyr_date = "2025-03-31";
        $end_date = \Session::get('gridenddate');

        // Recivables

        $agingSummary = \DB::select("SELECT
    v2.name AS customer,
    v2.`0-30`,
    v2.total
FROM
    (
    SELECT
        v1.name,
        ROUND(v1.`0-30`, 0) AS `0-30`,
        ROUND(v1.total, 2) AS total
    FROM
        (
        SELECT
            m_customers_t.customer_name AS NAME,

            (
                COALESCE(debit_0_30, 0) + COALESCE(debit_0_30_manual, 0) + COALESCE(advance_0_30, 0) - COALESCE(credit_0_30, 0) - COALESCE(credit_0_30_manual, 0)
            ) AS `0-30`,

            (
                COALESCE(total_debit, 0) + COALESCE(total_debit_manual, 0) + COALESCE(total_advance, 0) - COALESCE(total_credit, 0) - COALESCE(total_credit_manual, 0)
            ) AS total
        FROM
            m_customers_t

        LEFT JOIN(
            SELECT
                reference_id AS customer_id,
                SUM(
                    CASE WHEN DATEDIFF(
                        CURDATE(), f_journal_entry_lines_t.journal_date) BETWEEN 0 AND 30 THEN debit_amount ELSE 0
                    END
                ) AS debit_0_30,
                SUM(debit_amount) AS total_debit
            FROM
                f_journal_entry_lines_t
            LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
            WHERE
                f_journal_entry_lines_t.reference_source = 'CUSTOMER' AND f_journal_entry_t.journal_type IN(
                    'REVERSE RECEIPT',
                    'SALES INVOICE',
                    'PAYMENT',
                    'DEBIT',
                    'DIRECTPAYMENT',
                    'REVERSE ADVANCE RECEIPT',
                    'EXPENSES',
                    'OPENING BALANCE'
                )
            GROUP BY
                reference_id
        ) debit
    ON
        debit.customer_id = m_customers_t.customer_id
        LEFT JOIN(
            SELECT
                reference_id AS customer_id,
                SUM(
                    CASE WHEN DATEDIFF(
                        CURDATE(), f_journal_entry_lines_t.journal_date) BETWEEN 0 AND 30 THEN credit_amount ELSE 0
                    END
                ) AS debit_0_30_manual,
                SUM(credit_amount) AS total_debit_manual

            FROM
                f_journal_entry_lines_t
            LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
            WHERE
                f_journal_entry_lines_t.reference_source = 'CUSTOMER' AND f_journal_entry_t.journal_type = 'MANUAL'

            GROUP BY
                reference_id
        ) debit_manual
    ON
        debit_manual.customer_id = m_customers_t.customer_id
    LEFT JOIN(
        SELECT
            ship_to_customer_id AS customer_id,
            SUM(
                CASE WHEN DATEDIFF(CURDATE(), invoice_date) BETWEEN 0 AND 30 THEN invoice_grand_total ELSE 0
                END) AS advance_0_30,
            SUM(invoice_grand_total) AS total_advance
        FROM
            s_invoice_hdr_t
        WHERE
            invoice_status = 'APPROVED' AND invoice_date < '$year-04-01'
        GROUP BY
            ship_to_customer_id
    ) advance
ON
    advance.customer_id = m_customers_t.customer_id

    LEFT JOIN(
    SELECT
        reference_id AS customer_id,
        SUM(
            CASE WHEN DATEDIFF(
                CURDATE(), f_journal_entry_lines_t.journal_date) BETWEEN 0 AND 30 THEN credit_amount ELSE 0
            END
        ) AS credit_0_30,
        SUM(credit_amount) AS total_credit
    FROM
        f_journal_entry_lines_t
    LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    WHERE
        f_journal_entry_lines_t.reference_source = 'CUSTOMER' AND f_journal_entry_t.journal_type IN(
            'RECEIPT',
            'DIRECTRECEIPTS',
            'SALES RETURN',
            'ADVANCE RECEIPT',
            'CREDIT',
            'OPENING BALANCE'
        )
    GROUP BY
        reference_id
) credit
ON
    credit.customer_id = m_customers_t.customer_id

            LEFT JOIN(
            SELECT
                reference_id AS customer_id,
                SUM(
                    CASE WHEN DATEDIFF(
                        CURDATE(), f_journal_entry_lines_t.journal_date) BETWEEN 0 AND 30 THEN debit_amount ELSE 0
                    END
                ) AS credit_0_30_manual,
                SUM(debit_amount) AS total_credit_manual

            FROM
                f_journal_entry_lines_t
            LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
            WHERE
                f_journal_entry_lines_t.reference_source = 'CUSTOMER' AND f_journal_entry_t.journal_type = 'MANUAL'

            GROUP BY
                reference_id
        ) credit_manual
    ON
        credit_manual.customer_id = m_customers_t.customer_id
    ) v1
) v2
GROUP BY
    v2.name
HAVING
    v2.total != '0'
ORDER BY
    v2.name ASC");

        //   dd( $agingSummary);

        $sum0_30 = 0;
        $sum_total = 0;
        foreach ($agingSummary as $summary) {
            $sum0_30 += $summary->{'0-30'};
            $sum_total += $summary->total;
        }

        $grandTotal = (object) [
            'customer' => 'Total',
            'cmonth' => $sum0_30,
            'total' => $sum_total,
        ];

        $agingSummary[] = $grandTotal;

        $this->data['grandTotal'] = $grandTotal->total;
        $this->data['cmonth'] = $grandTotal->cmonth;

        // Payables
        $payagingSummary = \DB::select("SELECT
    v2.name AS supplier,
    v2.`0-30`,
    v2.total
FROM
    (
    SELECT
        v1.name,
        ROUND(v1.`0-30`, 0) AS `0-30`,
        ROUND(v1.total, 2) AS total
    FROM
        (
        SELECT
            m_supplier_t.supplier_name AS NAME,
            (
                COALESCE(debit_0_30, 0) - COALESCE(advance_0_30, 0) - COALESCE(credit_0_30, 0)
            ) AS `0-30`,

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
        $sum_total = 0;
        foreach ($payagingSummary as $summary) {
            $sum0_30 += $summary->{'0-30'};
            $sum_total += $summary->total;
        }

        $grandTotal1 = (object) [
            'cmonth' => $sum0_30,
            'total' => $sum_total,
        ];

        $payagingSummary[] = $grandTotal1;

        $this->data['grandTotalpay'] = $grandTotal1->total;
        $this->data['cmonthpay'] = $grandTotal1->cmonth;

        $totalsales = \DB::select("SELECT 
    SUM(
        CASE 
            WHEN final.sub1 = 'Direct Income' 
            THEN final.amount 
            ELSE 0 
        END
    ) AS sales,

    SUM(
        CASE 
            WHEN final.sub1 IN ('Direct Expenses', 'Indirect Expenses') 
            THEN final.amount 
            ELSE 0 
        END
    ) AS expense
FROM (
    SELECT 
        a.account_id,
        a.main_account_code,
        a.main,
        a.sub1,
        a.sub2,
        a.sub3,
        a.sub4,
        ROUND(SUM(COALESCE(j.amount,0)), 2) AS amount
    FROM (
        /* Account Structure */
        SELECT
            f.f_account_structure_id AS account_id,
            m.main_account_code,
            m.account_class_name AS main,
            r1.account_code_meaning AS sub1,
            IF(f.future_reference1 > 0, r2.account_code_meaning, '') AS sub2,
            IF(f.future_reference2 > 0, r3.account_code_meaning, '') AS sub3,
            IF(f.sub_account4_id > 0, r4.account_code_meaning, '') AS sub4
        FROM f_account_structure_t f
        JOIN f_account_class_t m 
            ON m.account_class_id = f.main_account_id
        LEFT JOIN f_account_codes_lines_t r1 
            ON r1.account_codes_line_id = f.sub_account_id
        LEFT JOIN f_account_codes_lines_t r2 
            ON r2.account_codes_line_id = f.future_reference1
        LEFT JOIN f_account_codes_lines_t r3 
            ON r3.account_codes_line_id = f.future_reference2
        LEFT JOIN f_account_codes_lines_t r4 
            ON r4.account_codes_line_id = f.sub_account4_id
        WHERE m.main_account_code IN (600000, 700000)
    ) a
    LEFT JOIN (
        /* Journal Aggregation */
        SELECT
            jel.account_id,
            SUM(
                CASE 
                    WHEN ac.main_account_code = 600000 
                        THEN jel.credit_amount - jel.debit_amount
                    WHEN ac.main_account_code = 700000 
                        THEN jel.debit_amount - jel.credit_amount
                END
            ) AS amount
        FROM f_journal_entry_lines_t jel
        JOIN f_account_structure_t fas 
            ON fas.f_account_structure_id = jel.account_id
        JOIN f_account_class_t ac 
            ON ac.account_class_id = fas.main_account_id
        WHERE ac.main_account_code IN (600000, 700000)
          AND jel.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY jel.account_id
    ) j 
        ON j.account_id = a.account_id
    GROUP BY a.account_id, a.sub1
) final");

        $this->data['totalsales'] = $totalsales[0]->sales;
        $this->data['totalexpense'] = $totalsales[0]->expense;


        $chart_detail = \DB::select("SELECT
    t.yr_month,

    SUM(
        CASE 
            WHEN t.main_account_code = 600000 
            THEN t.amount 
            ELSE 0 
        END
    ) AS sales,

    SUM(
        CASE 
            WHEN t.main_account_code = 700000 
            THEN t.amount 
            ELSE 0 
        END
    ) AS expense

FROM (
    SELECT
        ac.main_account_code,

        CONCAT(
            LEFT(MONTHNAME(jel.journal_date),3),
            '-',
            YEAR(jel.journal_date)
        ) AS yr_month,

        CASE
            WHEN ac.main_account_code = 600000
                THEN SUM(jel.credit_amount - jel.debit_amount)
            WHEN ac.main_account_code = 700000
                THEN SUM(jel.debit_amount - jel.credit_amount)
        END AS amount

    FROM f_journal_entry_lines_t jel
    JOIN f_journal_entry_t je
        ON je.journal_entry_id = jel.journal_entry_id
    JOIN f_account_structure_t fas
        ON fas.f_account_structure_id = jel.account_id
    JOIN f_account_class_t ac
        ON ac.account_class_id = fas.main_account_id

    WHERE ac.main_account_code IN (600000, 700000)
      AND jel.journal_date BETWEEN '$start_date' AND '$end_date'

    GROUP BY
        ac.main_account_code,
        YEAR(jel.journal_date),
        MONTH(jel.journal_date)
) t

GROUP BY t.yr_month
ORDER BY
    STR_TO_DATE(CONCAT('01-', t.yr_month), '%d-%b-%Y')");



        $Data = [];
        foreach ($chart_detail as $item) {
            $Data[] = [
                'month' => $item->yr_month,
                'sale' => $item->sales,
                'expense' => $item->expense,
            ];
        }

        $this->data['chart_datas'] = json_encode($Data);




        $summary = \DB::select("SELECT v2.revenue,v2.manuf,v2.profit,v2.opexp,ROUND(SUM(v2.profit - v2.opexp), 2) as pandl FROM (SELECT v1.*,SUM(v1.revenue - v1.manuf) as profit,SUM(v1.expense - v1.manuf) as opexp FROM (SELECT
    SUM(
        CASE WHEN final.sub1 IN(
        'Direct Income',
        'Indirect Income'
    )  THEN final.amount ELSE 0
    END
) AS revenue,
SUM(
    CASE WHEN final.sub2 =
        'Cost of Goods Manufacture'
    THEN final.amount ELSE 0
END
) AS manuf,
    SUM(
        CASE 
            WHEN final.sub1 IN ('Direct Expenses', 'Indirect Expenses') 
            THEN final.amount 
            ELSE 0 
        END
    ) AS expense,
                                                       
    '' AS COGS
FROM
    (
    SELECT
        a.account_id,
        a.main_account_code,
        a.main,
        a.sub1,
        a.sub2,
        a.sub3,
        a.sub4,
        ROUND(SUM(COALESCE(j.amount, 0)),
        2) AS amount
    FROM
        (
            /* Account Structure */
        SELECT
            f.f_account_structure_id AS account_id,
            m.main_account_code,
            m.account_class_name AS main,
            r1.account_code_meaning AS sub1,
            IF(
                f.future_reference1 > 0,
                r2.account_code_meaning,
                ''
            ) AS sub2,
            IF(
                f.future_reference2 > 0,
                r3.account_code_meaning,
                ''
            ) AS sub3,
            IF(
                f.sub_account4_id > 0,
                r4.account_code_meaning,
                ''
            ) AS sub4
        FROM
            f_account_structure_t f
        JOIN f_account_class_t m ON
            m.account_class_id = f.main_account_id
        LEFT JOIN f_account_codes_lines_t r1 ON
            r1.account_codes_line_id = f.sub_account_id
        LEFT JOIN f_account_codes_lines_t r2 ON
            r2.account_codes_line_id = f.future_reference1
        LEFT JOIN f_account_codes_lines_t r3 ON
            r3.account_codes_line_id = f.future_reference2
        LEFT JOIN f_account_codes_lines_t r4 ON
            r4.account_codes_line_id = f.sub_account4_id
        WHERE
            m.main_account_code IN(600000, 700000)
        ) a
    LEFT JOIN(
            /* Journal Aggregation */ SELECT
            jel.account_id,
            SUM(
                CASE WHEN ac.main_account_code = 600000 THEN jel.credit_amount - jel.debit_amount WHEN ac.main_account_code = 700000 THEN jel.debit_amount - jel.credit_amount
            END
        ) AS amount
    FROM
        f_journal_entry_lines_t jel
    JOIN f_account_structure_t fas ON
        fas.f_account_structure_id = jel.account_id
    JOIN f_account_class_t ac ON
        ac.account_class_id = fas.main_account_id
    WHERE
        ac.main_account_code IN(600000, 700000) AND jel.journal_date BETWEEN '$start_date' AND '$end_date'
    GROUP BY
        jel.account_id
) j
ON
    j.account_id = a.account_id
GROUP BY
    a.account_id,
    a.sub1
) final)v1)v2");


        $this->data['revenue'] = $summary[0]->revenue;
        $this->data['manuf'] = $summary[0]->manuf;
        $this->data['profit'] = $summary[0]->profit;
        $this->data['opexp'] = $summary[0]->opexp;
        $this->data['pandl'] = $summary[0]->pandl;

        $summary_pre = \DB::select("SELECT v2.revenue,v2.manuf,v2.profit,v2.opexp,ROUND(SUM(v2.profit - v2.opexp), 2) as pandl FROM (SELECT v1.*,SUM(v1.revenue - v1.manuf) as profit,SUM(v1.expense - v1.manuf) as opexp FROM (SELECT
    SUM(
        CASE WHEN final.sub1 IN(
        'Direct Income',
        'Indirect Income'
    )  THEN final.amount ELSE 0
    END
) AS revenue,
SUM(
    CASE WHEN final.sub2 =
        'Cost of Goods Manufacture'
    THEN final.amount ELSE 0
END
) AS manuf,
    SUM(
        CASE 
            WHEN final.sub1 IN ('Direct Expenses', 'Indirect Expenses') 
            THEN final.amount 
            ELSE 0 
        END
    ) AS expense,
                                                       
    '' AS COGS
FROM
    (
    SELECT
        a.account_id,
        a.main_account_code,
        a.main,
        a.sub1,
        a.sub2,
        a.sub3,
        a.sub4,
        ROUND(SUM(COALESCE(j.amount, 0)),
        2) AS amount
    FROM
        (
            /* Account Structure */
        SELECT
            f.f_account_structure_id AS account_id,
            m.main_account_code,
            m.account_class_name AS main,
            r1.account_code_meaning AS sub1,
            IF(
                f.future_reference1 > 0,
                r2.account_code_meaning,
                ''
            ) AS sub2,
            IF(
                f.future_reference2 > 0,
                r3.account_code_meaning,
                ''
            ) AS sub3,
            IF(
                f.sub_account4_id > 0,
                r4.account_code_meaning,
                ''
            ) AS sub4
        FROM
            f_account_structure_t f
        JOIN f_account_class_t m ON
            m.account_class_id = f.main_account_id
        LEFT JOIN f_account_codes_lines_t r1 ON
            r1.account_codes_line_id = f.sub_account_id
        LEFT JOIN f_account_codes_lines_t r2 ON
            r2.account_codes_line_id = f.future_reference1
        LEFT JOIN f_account_codes_lines_t r3 ON
            r3.account_codes_line_id = f.future_reference2
        LEFT JOIN f_account_codes_lines_t r4 ON
            r4.account_codes_line_id = f.sub_account4_id
        WHERE
            m.main_account_code IN(600000, 700000)
        ) a
    LEFT JOIN(
            /* Journal Aggregation */ SELECT
            jel.account_id,
            SUM(
                CASE WHEN ac.main_account_code = 600000 THEN jel.credit_amount - jel.debit_amount WHEN ac.main_account_code = 700000 THEN jel.debit_amount - jel.credit_amount
            END
        ) AS amount
    FROM
        f_journal_entry_lines_t jel
    JOIN f_account_structure_t fas ON
        fas.f_account_structure_id = jel.account_id
    JOIN f_account_class_t ac ON
        ac.account_class_id = fas.main_account_id
    WHERE
        ac.main_account_code IN(600000, 700000) AND jel.journal_date BETWEEN '$preyr_date' AND '$curyr_date'
    GROUP BY
        jel.account_id
) j
ON
    j.account_id = a.account_id
GROUP BY
    a.account_id,
    a.sub1
) final)v1)v2");


        $this->data['revenue_pre'] = $summary_pre[0]->revenue;
        $this->data['manuf_pre'] = $summary_pre[0]->manuf;
        $this->data['profit_pre'] = $summary_pre[0]->profit;
        $this->data['opexp_pre'] = $summary_pre[0]->opexp;
        $this->data['pandl_pre'] = $summary_pre[0]->pandl;

        return view('otherdashboard.accountdashboard', $this->data);

    }

}