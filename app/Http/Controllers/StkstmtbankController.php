<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;
use DateTime;
class StkstmtbankController extends Controller
{

    
    
    public function index(Request $request)
    {
 
        $this->data['final_value'] = \DB::select("SELECT 
        particulars,
        stock_on_date,
        unit_weight,
        mar_purc_rate,
        total_value,
        stkid,
        skt_id
    FROM (
        SELECT 
            f_stkstmt_gne_lines_t.particulars,
            f_stkstmt_gne_lines_t.stock_on_date,
            f_stkstmt_gne_lines_t.unit_weight,
            f_stkstmt_gne_lines_t.mar_purc_rate,
            f_stkstmt_gne_lines_t.total_value,
            f_stkstmt_gne_lines_t.stkid,
            f_stkstmt_gne_hdr_t.skt_id
        FROM 
            f_stkstmt_gne_lines_t 
        LEFT JOIN 
            f_stkstmt_gne_hdr_t 
        ON 
            f_stkstmt_gne_hdr_t.skt_id = f_stkstmt_gne_lines_t.stkid
        WHERE 
            f_stkstmt_gne_hdr_t.status='FINAL'
        ORDER BY 
            f_stkstmt_gne_lines_t.stkline_id ASC
    ) AS subquery LIMIT 21");
 
 
 
   $this->data['grand_totals'] = \DB::select("SELECT 
        grand_total,total_stock,total_value_sum,trade_creditors,
        net_value,sum_margin,total_a,sundry_debtor,
        margin_two,total_b,drawing_power 
        FROM `f_stkstmt_gne_hdr_t` 
        WHERE status='FINAL' ORDER BY skt_id DESC LIMIT 1");
 
// dd($this->data['grand_totals']);
 
       $this->data['last_date'] = \DB::select("SELECT select_date FROM `f_stkstmt_gne_hdr_t` ORDER BY `skt_id` DESC LIMIT 1");
 
  if (!empty($this->data['last_date']) && $this->data['last_date'][0]->select_date !== null) {
      
      $select_date = $this->data['last_date'][0]->select_date;
    } else {
      $select_date = ''; 
    }  
    
    $date = new DateTime($select_date);
    $this->data['date'] = $date->format('jS F Y');
    

 // sundry debtor
  $this->data['sundry_debtor'] = \DB::select("select * from(SELECT * FROM ( SELECT  m_customers_t.customer_name,ROUND(( COALESCE(debit_amount,0) - COALESCE(credit_amount,0) + opening + asd), 2) AS cus_balance
FROM (SELECT m_customers_t.customer_id as reference_id, COALESCE((SELECT COALESCE(SUM(s_invoice_hdr_t.invoice_grand_total),0) AS balance_amount
FROM s_invoice_hdr_t WHERE s_invoice_hdr_t.ship_to_customer_id = m_customers_t.customer_id AND s_invoice_hdr_t.created_by = '' AND s_invoice_hdr_t.invoice_date <= '$select_date'),0) AS asd,
            (
                COALESCE(
                    (
                    SELECT
                       
                        SUM(v.debit_amount - v.credit_amount)
                    FROM
                        `f_journal_entry_lines_t` AS v
                    JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = v.journal_entry_id
                    WHERE
                        v.reference_source = 'CUSTOMER' AND v.reference_id = m_customers_t.customer_id AND f_journal_entry_t.journal_date <= '$select_date'
                ),
                0
                )
            ) AS opening,
            SUM(debit_amount) AS debit_amount,
            SUM(credit_amount) AS credit_amount
        FROM
            m_customers_t 
         left join   `f_journal_entry_lines_t` on f_journal_entry_lines_t.reference_id=m_customers_t.customer_id and f_journal_entry_lines_t.reference_source='CUSTOMER' AND f_journal_entry_lines_t.journal_date <= '$select_date'
        left JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id 

        where 
        m_customers_t.active = 'Yes' and m_customers_t.customer_id not in (2,35,45,46,56,62,63,75)

        GROUP BY

            m_customers_t.customer_id
    ) f
JOIN m_customers_t ON m_customers_t.customer_id = f.reference_id
union all 

select name,(opening-de-cr)as bala from (SELECT (select f_account_structure_t.account_name from f_account_structure_t where f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id)as name,COALESCE(
                    (
                    SELECT
                        SUM(v.debit_amount - v.credit_amount)
                    FROM
                        `f_journal_entry_lines_t` AS v
                    JOIN f_journal_entry_t as n ON n.journal_entry_id = v.journal_entry_id
                    WHERE
                         v.account_id = f_journal_entry_lines_t.account_id AND n.journal_date  <= '$select_date' and n.journal_type LIKE 'MANUAL' and n.journal_category='OTHERS' 
                ),
                0
                )
             AS opening,sum(f_journal_entry_lines_t.debit_amount) as de,sum(f_journal_entry_lines_t.credit_amount) as cr  FROM `f_journal_entry_t` join f_journal_entry_lines_t on f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id WHERE `journal_type` LIKE 'MANUAL' and journal_category='OTHERS'  and f_journal_entry_lines_t.account_id in (0) and f_journal_entry_t.journal_date <= '$select_date' group by f_journal_entry_lines_t.account_id)d

) v1
WHERE
    1 = 1 HAVING cus_balance !='0')v1");
 
 // sundry creditors
 
  $this->data['sundry_creditor'] = \DB::select("select * from(select m_supplier_t.supplier_name,round((credit_amount-debit_amount+opening+asd+asd1),2)as balance from (SELECT f_journal_entry_lines_t.reference_id, 
COALESCE( (select COALESCE(sum(p_po_invoice_hdr_t.invoice_grand_total),0) as balance_amount from p_po_invoice_hdr_t where p_po_invoice_hdr_t.supplier_id=f_journal_entry_lines_t.reference_id and p_po_invoice_hdr_t.created_by='' and p_po_invoice_hdr_t.invoice_date <= '$select_date'),0) as asd,
COALESCE( (select COALESCE(sum(f_expenses_t.expense_amount),0) as balance_amount1 from f_expenses_t where f_expenses_t.supplier_id=f_journal_entry_lines_t.reference_id and f_expenses_t.expense_no='Opening Balance' and f_expenses_t.bill_date <= '$select_date'),0) as asd1,
(COALESCE((SELECT sum(v.credit_amount-v.debit_amount) FROM `f_journal_entry_lines_t` as v join f_journal_entry_t on f_journal_entry_t.journal_entry_id=v.journal_entry_id where v.reference_source='SUPPLIER' and v.reference_id=f_journal_entry_lines_t.reference_id and f_journal_entry_t.journal_date <= '$select_date' ),0)) as opening,sum(debit_amount) as debit_amount,sum(credit_amount) as credit_amount FROM `f_journal_entry_lines_t` join f_journal_entry_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id   where reference_source='SUPPLIER' and f_journal_entry_t.journal_date <= '$select_date' group by f_journal_entry_lines_t.reference_id)f join m_supplier_t on m_supplier_t.supplier_id=f.reference_id HAVING balance !='0')v1");
 
 
 
        return view('stockstmtbank.table', $this->data);      
    }
    
    
}