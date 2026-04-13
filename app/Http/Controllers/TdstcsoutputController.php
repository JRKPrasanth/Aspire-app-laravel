<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class TdstcsoutputController extends Controller
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
     
            $this->data['ledger'] = $this->jcustomselecttool("f_account_structure_t", "f_account_structure_id", "account_name", "", " AND concatenated_segments LIKE '%TDS%'");
            $this->data['account'] ='';
            $acc_id = $request->input('account_id');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            
        if($acc_id !=''){
        $account_id = \DB::select("SELECT account_name from f_account_structure_t where f_account_structure_id='$acc_id'");
        $this->data['account'] = $account_id[0]->account_name;
   
        }
            
        // for account id u/s 195 purpose
        
    if ($acc_id =='758' || $acc_id =='27' ) {
        
         $this->data['tcs_report'] = \DB::select("SELECT v1.*, 
       CONCAT(LEFT(MONTHNAME(DATE(v1.entry_date)),3), '-', YEAR(DATE(v1.entry_date))) AS yr_month, 
       CASE 
           WHEN MONTH(v1.entry_date) IN (4,5,6) THEN 'Q1' 
           WHEN MONTH(v1.entry_date) IN (7,8,9) THEN 'Q2' 
           WHEN MONTH(v1.entry_date) IN (10,11,12) THEN 'Q3' 
           ELSE 'Q4' 
       END AS period,  
       ROUND((v1.tds_amt / v1.taxable_amt) * 100, 2) AS percentage 
    FROM ( 
        SELECT 
        tds_line.journal_entry_id, 
        GROUP_CONCAT(DISTINCT taxable_line.reference_source) AS reference_source,
        GROUP_CONCAT(DISTINCT 
            CASE 
                WHEN taxable_line.reference_source = 'EMPLOYEE' THEN hr_employee_t.first_name 
                WHEN taxable_line.reference_source = 'SUPPLIER' THEN m_supplier_t.supplier_name 
                ELSE NULL 
            END 
            SEPARATOR ', '
        ) AS name,  
        GROUP_CONCAT(DISTINCT 
            CASE 
                WHEN taxable_line.reference_source = 'EMPLOYEE' THEN hr_emp_personal.pan_number 
                WHEN taxable_line.reference_source = 'SUPPLIER' THEN m_supplier_t.pan_number 
                ELSE NULL 
            END 
            SEPARATOR ', '
        ) AS pan,  
        CASE 
            WHEN taxable_line.account_id NOT IN ('17','18','19','20','21','22') THEN SUM(taxable_line.debit_amount) 
            ELSE 0 
        END AS taxable_amt, 
                CASE 
                WHEN f_journal_entry_t.journal_type = 'PO INVOICE' THEN p_po_invoice_hdr_t.invoice_date
                WHEN f_journal_entry_t.journal_type = 'EXPENSES' THEN f_expenses_t.expense_date 
                WHEN f_journal_entry_t.journal_type = 'ADVANCE PAYMENT' THEN f_journal_entry_t.journal_date 
                ELSE NULL 
            END AS date,
        f_journal_entry_t.journal_name as ref_no,
        DATE(tds_line.journal_date) AS entry_date, 
        CASE 
            WHEN tds_line.account_id = '$acc_id' THEN tds_line.credit_amount 
            ELSE 0 
        END AS tds_amt  
        FROM f_journal_entry_lines_t tds_line 
        LEFT JOIN f_journal_entry_lines_t taxable_line 
            ON tds_line.journal_entry_id = taxable_line.journal_entry_id 
        LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = taxable_line.reference_id 
        LEFT JOIN hr_emp_personal ON hr_emp_personal.employee_id = hr_employee_t.employee_id 
        LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = taxable_line.reference_id 
        LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = taxable_line.journal_entry_id
        LEFT JOIN p_po_invoice_hdr_t ON p_po_invoice_hdr_t.po_invoice_id = f_journal_entry_t.journal_reference 
        LEFT JOIN f_expenses_t ON f_expenses_t.expense_id = f_journal_entry_t.journal_reference   
        WHERE tds_line.account_id = '$acc_id' 
            AND taxable_line.account_id NOT IN ('17','18','19','20','21','22') 
            AND DATE(tds_line.journal_date) BETWEEN '$start_date' AND '$end_date' 
        GROUP BY tds_line.journal_entry_id HAVING reference_source !=''
    ) v1");
    
    }else {
    
              $this->data['tcs_report'] = \DB::select("SELECT v1.*,CONCAT(LEFT(MONTHNAME(date(v1.entry_date)),3), '-', 
            year(date(v1.entry_date))) as yr_month,CASE WHEN MONTH(v1.entry_date) IN (4,5,6) THEN 'Q1' 
            
            WHEN MONTH(v1.entry_date) IN (7,8,9) THEN 'Q2'
            
            WHEN MONTH(v1.entry_date) IN (10,11,12) THEN 'Q3' ELSE 'Q4' END AS period,  ROUND((v1.tds_amt/v1.taxable_amt)*100, 2) as percentage FROM (SELECT 
                tds_line.journal_entry_id, 
                taxable_line.reference_source, 
            CASE WHEN taxable_line.account_id NOT IN ('17','18','19','20','21','22') THEN SUM(taxable_line.debit_amount) ELSE 0 END AS taxable_amt,
        
                 CASE 
                WHEN f_journal_entry_t.journal_type = 'PO INVOICE' THEN p_po_invoice_hdr_t.invoice_date
                WHEN f_journal_entry_t.journal_type = 'EXPENSES' THEN f_expenses_t.expense_date 
                WHEN f_journal_entry_t.journal_type = 'ADVANCE PAYMENT' THEN f_journal_entry_t.journal_date 
                ELSE NULL 
            END AS date,
            f_journal_entry_t.journal_name as ref_no,
           DATE(tds_line.journal_date) AS entry_date, 
           CASE WHEN  tds_line.account_id = '$acc_id' THEN tds_line.credit_amount ELSE 0 END AS tds_amt,  
                                                                                   
            CASE 
                WHEN taxable_line.reference_source = 'EMPLOYEE' THEN hr_employee_t.first_name 
                WHEN taxable_line.reference_source = 'SUPPLIER' THEN m_supplier_t.supplier_name 
                ELSE NULL 
            END AS name, 
        
        
            CASE 
                WHEN taxable_line.reference_source = 'EMPLOYEE' THEN hr_emp_personal.pan_number 
                WHEN taxable_line.reference_source = 'SUPPLIER' THEN m_supplier_t.pan_number 
                ELSE NULL 
            END AS pan 
        
        FROM f_journal_entry_lines_t tds_line
        
        
        LEFT JOIN f_journal_entry_lines_t taxable_line 
            ON tds_line.journal_entry_id = taxable_line.journal_entry_id 
            
        LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = taxable_line.reference_id
        LEFT JOIN hr_emp_personal ON hr_emp_personal.employee_id = hr_employee_t.employee_id
        LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = taxable_line.reference_id
        LEFT JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = taxable_line.journal_entry_id
        LEFT JOIN p_po_invoice_hdr_t ON p_po_invoice_hdr_t.po_invoice_id = f_journal_entry_t.journal_reference 
        LEFT JOIN f_expenses_t ON f_expenses_t.expense_id = f_journal_entry_t.journal_reference   
        WHERE 
            tds_line.account_id = '$acc_id' AND  taxable_line.account_id NOT IN ('17','18','19','20','21','22')
            AND DATE(tds_line.journal_date) BETWEEN '$start_date' AND '$end_date'
            GROUP BY tds_line.journal_entry_id HAVING (reference_source ='EMPLOYEE' OR reference_source ='SUPPLIER' ))v1");
    
    }

       return view('tdsoutput.table', $this->data);
     
   }
    
    
}