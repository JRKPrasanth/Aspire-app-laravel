<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class TcsoutputController extends Controller
{

 
      public function Index(Request $request) {
		  
     $this->data['pageMethod']=\Request::route()->getName();

     // restrict illegal entry purpose for menus - VIGNESH M

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
     
            $this->data['ledger'] = $this->jcustomselecttool("f_account_structure_t", "f_account_structure_id", "account_name", "", " AND concatenated_segments LIKE '%TCS%'");
            
            $acc_id = $request->input('account_id');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            

    
              $this->data['tcs_report'] = \DB::select("SELECT v1.*, 
       CONCAT(LEFT(MONTHNAME(DATE(v1.date)),3), '-', YEAR(DATE(v1.date))) AS yr_month,
       CASE 
           WHEN MONTH(v1.date) IN (4,5,6) THEN 'Q1' 
           WHEN MONTH(v1.date) IN (7,8,9) THEN 'Q2'
           WHEN MONTH(v1.date) IN (10,11,12) THEN 'Q3' 
           ELSE 'Q4' 
       END AS period,
       (v1.tds_amt / v1.taxable_amt) * 100 AS percentage 
FROM (
    SELECT 
        tds_line.journal_entry_id, 
        taxable_line.reference_source, 
        

        COALESCE(s_invoice_hdr_t.invoice_number, si2.invoice_number, 0) AS ref_no, 
        

        COALESCE(s_invoice_hdr_t.tcs_calc_amount, si2.tcs_calc_amount, 0) AS taxable_amt,  

        tds_line.journal_date AS date, 
        CASE 
            WHEN tds_line.account_id = '$acc_id' THEN tds_line.credit_amount 
            ELSE 0 
        END AS tds_amt,  
                                                                           
        CASE 
            WHEN taxable_line.reference_source = 'CUSTOMER' THEN m_customers_t.customer_name 
            ELSE NULL 
        END AS name,  

        CASE 
            WHEN taxable_line.reference_source = 'CUSTOMER' THEN m_customers_t.pan_no 
            ELSE NULL 
        END AS pan  

    FROM f_journal_entry_lines_t tds_line
    
    LEFT JOIN f_journal_entry_lines_t taxable_line 
        ON tds_line.journal_entry_id = taxable_line.journal_entry_id 
        
    LEFT JOIN m_customers_t 
        ON m_customers_t.customer_id = taxable_line.reference_id

    LEFT JOIN f_journal_entry_t 
        ON f_journal_entry_t.journal_entry_id = taxable_line.journal_entry_id


    LEFT JOIN s_invoice_hdr_t 
        ON s_invoice_hdr_t.invoice_hdr_id = f_journal_entry_t.journal_reference 
        AND f_journal_entry_t.journal_type = 'SALES INVOICE'


    LEFT JOIN f_debitcredit_t dc1 
        ON dc1.debitcredit_id = f_journal_entry_t.journal_reference 
        AND f_journal_entry_t.journal_type = 'DEBIT'


    LEFT JOIN f_debitcredit_t dc2 
        ON dc2.invoice_no = s_invoice_hdr_t.invoice_hdr_id 


    LEFT JOIN s_invoice_hdr_t si2 
        ON si2.invoice_hdr_id = dc1.invoice_no  

    WHERE 
        tds_line.account_id = '$acc_id'
        AND tds_line.journal_date BETWEEN '$start_date' AND '$end_date'

    GROUP BY tds_line.journal_entry_id 
    HAVING reference_source = 'CUSTOMER'
) v1 
HAVING tds_amt != 0");
    
   // dd($this->data['tcs_report']);
        

       return view('tcsoutput.table', $this->data);
     
   }
    
    
}