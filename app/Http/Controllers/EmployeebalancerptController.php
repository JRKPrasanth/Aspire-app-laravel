<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\employeebalancerpt;
use Illuminate\Http\Request;
use DB;

class EmployeebalancerptController extends Controller
{
   public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }
    public function index()
    {
        
        $emp_id=\Session::get('emp_id');
		$groupname=\Session::get('groupname');

    	if($groupname=='1' || $groupname=='3' || $groupname=='15' || $groupname=='4' || $emp_id=='155'){
    	    $this->data['employee_id'] = $this->jCombocomp('hr_employee_t','employee_id','employee_number|first_name','');
    	}else{
    	$this->data['employee_id'] = $this->jcustomselectcomp('hr_employee_t','employee_id','employee_number|first_name','',' and employee_id='.$emp_id);
    	}
     $this->data['pageMethod']=\Request::route()->getName();
     if($this->data['pageMethod']=="employeesummaryrpt"){
        return view('employeebalancerpt.employeesummary', $this->data);        
     }else if($this->data['pageMethod']=="employeebalancerpt"){
 return view('employeebalancerpt.employeebalance', $this->data);       
    }
    else
    {
      return view('employeebalancerpt.employeebalanceall', $this->data); 
    }
    }
    
    
      public function cusindex()
    {

     $this->data['employee_id'] = $this->jCombocomp('hr_employee_t','employee_id','employee_number|first_name','');
     $this->data['pageMethod']=\Request::route()->getName();
    
      return view('employeebalancerpt.customerbalanceall', $this->data); 
    }
       public function supindex()
    {
        
       	$this->data['employee_id'] = $this->jCombocomp('hr_employee_t','employee_id','employee_number|first_name','');
     $this->data['pageMethod']=\Request::route()->getName();
    
      return view('employeebalancerpt.supplierbalanceall', $this->data); 
    }

   
public function employeebalance(Request $request)
{
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : null;
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : null;
    $employee_id = $request->employee_id ?? null;

    // Get employee address details
    $this->data['customer_dat'] = \DB::select("
        SELECT 
            e.first_name AS customer_name,
            CONCAT(c.permanent_flat_no, ' ', c.permanent_street) AS customer_site_name,
            c.permanent_street_address AS address,
            (SELECT city_name FROM m_cities_t WHERE city_id = c.permanent_city) AS city_name,
            (SELECT state_name FROM m_states_t WHERE state_id = c.permanent_state) AS state_name,
            (SELECT country_name FROM m_countries_t WHERE country_id = c.permanent_country) AS country_name,
            c.permanent_postal_code AS pincode
        FROM hr_employee_t e
        LEFT JOIN hr_emp_contact c ON c.employee_id = e.employee_id
        WHERE e.active = 'Yes' AND e.employee_id = ?
        LIMIT 1
    ", [$employee_id]);

    // Get employee balance ledger
    $employee_data_sql = "
        SELECT 
            v4.*, 
            (@s := @s + v4.op_balance + v4.debit_amount - v4.credit_amount) AS balance 
        FROM (
            SELECT 
                journal_name, journal_date, nar,
                COALESCE((SELECT concatenated_segments FROM f_account_structure_t WHERE f_account_structure_id = v3.acc_id), '') AS acc_name,
                COALESCE((SELECT account_name FROM f_account_structure_t WHERE f_account_structure_id = v3.acc_id), '') AS name,
                op_balance, debit_amount, credit_amount 
            FROM (
                -- Opening Balance Calculation
                SELECT 
                    0 AS op_balance, 
                    'Opening Balance' AS nar, 
                    '' AS f_journal_entry_line_id, '' AS journal_entry_id, '' AS journal_name, 
                    '' AS journal_type, '' AS journal_reference, '' AS acc_id, '' AS journal_date,
                    IF(SUM(balance) < 0, SUM(balance) * -1, 0) AS credit_amount,
                    IF(SUM(balance) > 0, SUM(balance), 0) AS debit_amount 
                FROM (
                    SELECT COALESCE(SUM(amount), 0) AS balance 
                    FROM hr_imprest_tbl 
                    WHERE employee_id = ? AND imprest_number = 'Opening Balance'
                    UNION ALL
                    SELECT 
                        COALESCE(SUM(fjel.debit_amount - fjel.credit_amount), 0) AS balance
                    FROM f_journal_entry_lines_t fjel
                    JOIN f_journal_entry_t fje ON fje.journal_entry_id = fjel.journal_entry_id
                    WHERE 
                        fjel.reference_source = 'EMPLOYEE' AND 
                        fjel.reference_id = ? AND 
                        fjel.journal_date < ? AND 
                        (
                            fjel.account_id = 106 OR 
                            fje.journal_type = 'SALARYPAYMENT' OR 
                            (fjel.account_id = 72 AND fje.journal_type = 'PAYROLL')
                        )
                ) AS opening_balance_calc

                UNION ALL

                -- Transactions within selected date
                SELECT 
                    '' AS op_balance,
                    CASE 
                        WHEN fje.journal_type = 'EXPENSES' THEN (
                            SELECT invoice FROM f_expenses_t WHERE expense_id = fje.journal_reference
                        )
                        WHEN fje.journal_type = 'EMPLOYEE EXPENSES' THEN (
                            SELECT remarks FROM f_emp_expenses_lines_t WHERE expense_id = fje.journal_reference AND employee_id = ?
                        )
                        WHEN fje.journal_type = 'MANUAL' THEN ''
                        WHEN fje.journal_type IN ('DIRECTRECEIPTS', 'ADVANCE RECEIPT', 'RECEIPT') THEN (
                            SELECT remarks FROM s_receipts_t WHERE receipt_id = fje.journal_reference
                        )
                        ELSE (
                            SELECT remarks FROM p_payments_t WHERE payment_id = fje.journal_reference
                        )
                    END AS nar,
                    fjel.f_journal_entry_line_id, fjel.journal_entry_id, fje.journal_name, fje.journal_type, fje.journal_reference,
                    (
                        SELECT account_id 
                        FROM f_journal_entry_lines_t 
                        WHERE journal_entry_id = fjel.journal_entry_id AND f_journal_entry_line_id != fjel.f_journal_entry_line_id 
                        LIMIT 1
                    ) AS acc_id,
                    fjel.journal_date, 
                    fjel.credit_amount, 
                    fjel.debit_amount 
                FROM f_journal_entry_lines_t fjel
                JOIN f_journal_entry_t fje ON fje.journal_entry_id = fjel.journal_entry_id
                WHERE 
                    fjel.reference_source = 'EMPLOYEE' AND 
                    fjel.reference_id = ? AND 
                    fjel.journal_date BETWEEN ? AND ? AND (
                        fjel.account_id = 106 OR 
                        fje.journal_type IN ('SALARYPAYMENT', 'REVERSE') OR 
                        (fjel.account_id = 72 AND fje.journal_type = 'PAYROLL')
                    )
            ) v3 
            ORDER BY journal_date ASC
        ) v4 
        CROSS JOIN (SELECT @s := 0) p 
        ORDER BY journal_date ASC
    ";

    $employee_data = \DB::select($employee_data_sql, [
        $employee_id, $employee_id, $start_date, $employee_id, $employee_id, $start_date, $end_date
    ]);

    return response()->json(['data' => $employee_data]);
}



   public function employeebalanceall(Request $request)
   {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : NULL;
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : NULL;
 
 $employee_data="SELECT
    v4.*
FROM
    (
    SELECT
        SUM(op_balance) AS op_balance,
        (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = v3.emp_id
    ) AS employee_name,
    (
    SELECT
        hr_employee_t.employee_number
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = v3.emp_id
) AS employee_number,
(
    SELECT
        hr_employee_t.active
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = v3.emp_id
) AS employee_status,
(
    SELECT
        a_lookuplines_t.lookup_code
    FROM
        a_lookuplines_t
    WHERE
        a_lookuplines_t.lookuplines_id = v3.employee_type
) AS employee_type,
SUM(debit_amount) AS debit_amount,
SUM(credit_amount) AS credit_amount,
SUM(
    op_balance + debit_amount - credit_amount
) AS balance
FROM
    (
    SELECT
        SUM(balance) AS op_balance,
        employee_id AS emp_id,
        employee_type,
        0 AS credit_amount,
        0 AS debit_amount
    FROM
        (
        SELECT
            COALESCE(SUM(hr_imprest_tbl.amount),
            0) AS balance,
            hr_imprest_tbl.employee_id,
            hr_employee_t.employee_type
        FROM
            hr_imprest_tbl
        JOIN hr_employee_t ON hr_employee_t.employee_id = hr_imprest_tbl.employee_id
        WHERE
            hr_imprest_tbl.employee_id != '' AND hr_imprest_tbl.imprest_number = 'Opening Balance'
        GROUP BY
            hr_imprest_tbl.employee_id
        UNION ALL
    SELECT
        COALESCE(
            SUM(
                f_journal_entry_lines_t.debit_amount - f_journal_entry_lines_t.credit_amount
            ),
            0
        ) AS balance,
        f_journal_entry_lines_t.reference_id,
        hr_employee_t.employee_type
    FROM
        `f_journal_entry_lines_t`
    JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    JOIN hr_employee_t ON hr_employee_t.employee_id = f_journal_entry_lines_t.reference_id
    WHERE
        f_journal_entry_lines_t.reference_source LIKE 'EMPLOYEE' AND f_journal_entry_lines_t.reference_id != '' AND f_journal_entry_lines_t.journal_date < ? AND(
            (
                f_journal_entry_lines_t.account_id = 106 OR f_journal_entry_t.journal_type = 'SALARYPAYMENT' or f_journal_entry_t.journal_type='REVERSE'
            ) OR(
                f_journal_entry_lines_t.account_id = 72 AND f_journal_entry_t.journal_type = 'PAYROLL'
            )
        )
    GROUP BY
        f_journal_entry_lines_t.reference_id
    ) v1
GROUP BY
    employee_id
UNION ALL
    (
    SELECT
        0 AS op_balance,
        f_journal_entry_lines_t.reference_id AS emp_id,
        hr_employee_t.employee_type,
        f_journal_entry_lines_t.credit_amount,
        f_journal_entry_lines_t.debit_amount
    FROM
        `f_journal_entry_lines_t`
    JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
    JOIN hr_employee_t ON hr_employee_t.employee_id = f_journal_entry_lines_t.reference_id
    WHERE
        f_journal_entry_lines_t.reference_source LIKE 'EMPLOYEE' AND f_journal_entry_lines_t.reference_id != '' AND f_journal_entry_lines_t.journal_date BETWEEN ? AND ? AND(
            (
                f_journal_entry_lines_t.account_id = 106 OR f_journal_entry_t.journal_type = 'SALARYPAYMENT' or f_journal_entry_t.journal_type='REVERSE'
            ) OR(
                f_journal_entry_lines_t.account_id = 72 AND f_journal_entry_t.journal_type = 'PAYROLL'
            )
        )
    ORDER BY
        f_journal_entry_lines_t.journal_date ASC
)
) v3
GROUP BY
    emp_id
) v4";
 

    $results = \DB::select($employee_data, [$start_date, $start_date,$end_date]);

    return response()->json(['data' => $results]);
   
   } 

	
   public function custttomerbalanceall(Request $request)
   {
	   

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

	  // dd($start_date);
	   
 $SQL = "select * from(SELECT
    *
FROM
    (
    SELECT
        m_customers_t.customer_name,
        m_customers_t.customer_number,
        f_account_structure_t.account_name,
        round(opening + asd,2) AS op_balance,
        CASE WHEN debit_amount1 IS null THEN
        round(debit_amount2,2) WHEN debit_amount2 IS null THEN round(debit_amount1,2) 
        ELSE round(debit_amount1 + debit_amount2,2) END as debit_amount,
        CASE WHEN credit_amount1 IS null THEN
        round(credit_amount2,2) WHEN credit_amount2 IS null THEN round(credit_amount1,2) 
        ELSE round(credit_amount1 + credit_amount2,2) END as credit_amount,
        ROUND(
            (
                COALESCE(CASE WHEN debit_amount1 IS null THEN
        round(debit_amount2,2) WHEN debit_amount2 IS null THEN round(debit_amount1,2) 
        ELSE round(debit_amount1 + debit_amount2,2) END ,0)
                
                - COALESCE(CASE WHEN credit_amount1 IS null THEN
        round(credit_amount2,2) WHEN credit_amount2 IS null THEN round(credit_amount1,2) 
        ELSE round(credit_amount1 + credit_amount2,2) END ,0) + opening + asd
            ),
            2
        ) AS balance
    FROM
        (
        SELECT
            m_customers_t.customer_id as reference_id,
            COALESCE(
                (
                SELECT
                    COALESCE(
                        SUM(
                            s_invoice_hdr_t.invoice_grand_total
                        ),
                        0
                    ) AS balance_amount
                FROM
                    s_invoice_hdr_t
                WHERE
                    s_invoice_hdr_t.ship_to_customer_id = m_customers_t.customer_id AND s_invoice_hdr_t.created_by = '' AND s_invoice_hdr_t.invoice_date < ?
            ),
            0
            ) AS asd,
            (
                COALESCE(
                    (
                    SELECT
                        SUM(v.debit_amount - v.credit_amount)
                    FROM
                        `f_journal_entry_lines_t` AS v
                    JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = v.journal_entry_id
                    WHERE
                        v.reference_source = 'CUSTOMER' AND v.reference_id = m_customers_t.customer_id AND f_journal_entry_t.journal_date < ?
                ),
                0
                )
            ) AS opening,
            
            	(select 
                        SUM(v.debit_amount)
                    FROM
                        `f_journal_entry_lines_t` AS v
                    JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = v.journal_entry_id
                    WHERE
                        v.reference_source = 'CUSTOMER' AND v.reference_id = m_customers_t.customer_id AND (f_journal_entry_t.journal_date >= ? and f_journal_entry_t.journal_date <= ?) and v.account_id in (57,58,59,60,61,62,63) and f_journal_entry_t.journal_type !='MANUAL') as debit_amount1,
              
        (select  
    		SUM(u.debit_amount)
                    FROM
                        `f_journal_entry_lines_t` AS u
                    JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = u.journal_entry_id
                    WHERE
                        u.reference_source = 'CUSTOMER' AND u.reference_id = m_customers_t.customer_id AND (f_journal_entry_t.journal_date >= ? and f_journal_entry_t.journal_date <= ?) and u.account_id not in (57,58,59,60,61,62,63) and f_journal_entry_t.journal_type='MANUAL') as debit_amount2,
            
            (select 
                        SUM(v.credit_amount)
                    FROM
                        `f_journal_entry_lines_t` AS v
                    JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = v.journal_entry_id
                    WHERE
                        v.reference_source = 'CUSTOMER' AND v.reference_id = m_customers_t.customer_id AND (f_journal_entry_t.journal_date >= ? and f_journal_entry_t.journal_date <= ?) and v.account_id in (57,58,59,60,61,62,63) and f_journal_entry_t.journal_type !='MANUAL') as credit_amount1,
              
        (select  
    		SUM(u.credit_amount)
                    FROM
                        `f_journal_entry_lines_t` AS u
                    JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = u.journal_entry_id
                    WHERE
                        u.reference_source = 'CUSTOMER' AND u.reference_id = m_customers_t.customer_id AND (f_journal_entry_t.journal_date >= ? and f_journal_entry_t.journal_date <= ?) and u.account_id not in (57,58,59,60,61,62,63) and f_journal_entry_t.journal_type ='MANUAL') as credit_amount2            
            
            
        FROM
            m_customers_t 
         left join   `f_journal_entry_lines_t` on f_journal_entry_lines_t.reference_id=m_customers_t.customer_id and f_journal_entry_lines_t.reference_source='CUSTOMER' AND f_journal_entry_lines_t.journal_date BETWEEN ? AND ?
        left JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id 

        where 
         m_customers_t.customer_id not in (2,35,45,46,56,62,63,75)

        GROUP BY

            m_customers_t.customer_id
    ) f
JOIN m_customers_t ON m_customers_t.customer_id = f.reference_id
JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=m_customers_t.account_structure_id

union all 

select name,'','',opening,de,cr,(opening-de-cr)as bala from (SELECT (select f_account_structure_t.account_name from f_account_structure_t where f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id)as name,COALESCE(
                    (
                    SELECT
                        SUM(v.debit_amount - v.credit_amount)
                    FROM
                        `f_journal_entry_lines_t` AS v
                    JOIN f_journal_entry_t as n ON n.journal_entry_id = v.journal_entry_id
                    WHERE
                         v.account_id = f_journal_entry_lines_t.account_id AND n.journal_date < '2022-04-01' and n.journal_type LIKE 'MANUAL' and n.journal_category='OTHERS' 
                ),
                0
                )
             AS opening,sum(f_journal_entry_lines_t.debit_amount) as de,sum(f_journal_entry_lines_t.credit_amount) as cr  FROM `f_journal_entry_t` join f_journal_entry_lines_t on f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id WHERE `journal_type` LIKE 'MANUAL' and journal_category='OTHERS'  and f_journal_entry_lines_t.account_id in (0) and f_journal_entry_t.journal_date BETWEEN '2022-04-01' and '2023-03-31' group by f_journal_entry_lines_t.account_id)d

) v1
WHERE
    1 = 1)v1";

	 $results = \DB::select($SQL, [$start_date,$start_date,$start_date, $end_date,$start_date, $end_date,$start_date, $end_date,$start_date, $end_date,$start_date, $end_date]);
	   
    return response()->json(['data' => $results]);
	   
   }
	
	
	
 public function getsupplierbalanceall(Request $request) {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

 $employee_data=\DB::select("select * from(select m_supplier_t.supplier_name,f_account_structure_t.account_name,m_supplier_t.supplier_number,(round((opening+asd+asd1),2)*(-1)) as op_balance,debit_amount,credit_amount,(round((credit_amount-debit_amount+opening+asd+asd1),2)*(-1)) as balance from ( SELECT f_journal_entry_lines_t.reference_id, 

 COALESCE( (select COALESCE(sum(p_po_invoice_hdr_t.invoice_grand_total),0) as balance_amount from p_po_invoice_hdr_t where p_po_invoice_hdr_t.supplier_id=f_journal_entry_lines_t.reference_id and p_po_invoice_hdr_t.created_by='' and p_po_invoice_hdr_t.invoice_date < '$start_date'),0) as asd,
 COALESCE( (select COALESCE(sum(f_expenses_t.expense_amount),0) as balance_amount1 from f_expenses_t where f_expenses_t.supplier_id=f_journal_entry_lines_t.reference_id and f_expenses_t.expense_no='Opening Balance' and f_expenses_t.bill_date < '$start_date'),0) as asd1,

 (COALESCE((SELECT sum(v.credit_amount-v.debit_amount) FROM `f_journal_entry_lines_t` as v join f_journal_entry_t on f_journal_entry_t.journal_entry_id=v.journal_entry_id where v.reference_source='SUPPLIER' and v.reference_id=f_journal_entry_lines_t.reference_id and (f_journal_entry_t.journal_date < '$start_date' or f_journal_entry_t.journal_name LIKE '%ob_supplier%') ),0)) as opening,sum(debit_amount) as debit_amount,sum(credit_amount) as credit_amount FROM `f_journal_entry_lines_t` join f_journal_entry_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id   where reference_source='SUPPLIER'   and f_journal_entry_t.journal_date BETWEEN '$start_date' and '$end_date' group by f_journal_entry_lines_t.reference_id)f join m_supplier_t on m_supplier_t.supplier_id=f.reference_id join f_account_structure_t on
  f_account_structure_t.f_account_structure_id = m_supplier_t.account_structure_id)v1");
 
	 return response()->json(['data' => $employee_data]);
	 
   }
	
    public function getemployeesummaryrpt(){
	 $wh='';
           
               if(isset($_GET['pq_filter']))
            {
    $data=json_decode($_GET['pq_filter']);
    $data=$data->data;
       $wh.=$this->pqgridsearchsum('v1',$data);
    }
      $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];     

    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?   date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
    $employee_id =  $_GET['employee_id'];
$sidx='';
 if (!$sidx)
            $sidx = 1;
 $employee_data=\DB::select("select * from((select f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_t.journal_date,0 as net_salary,CASE WHEN f_journal_entry_t.journal_type='EXPENSES' THEN f_expenses_t.invoice
 	ELSE p_payments_t.remarks END as narr
  FROM f_journal_entry_lines_t JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id
   left join p_payments_t on (p_payments_t.payment_id=f_journal_entry_t.journal_reference and f_journal_entry_t.journal_type!='EXPENSES')
   left join f_expenses_t on (f_expenses_t.expense_id=f_journal_entry_t.journal_reference and f_journal_entry_t.journal_type='EXPENSES')
    where f_journal_entry_lines_t.reference_source='employee' and f_journal_entry_lines_t.reference_id='$employee_id' and f_journal_entry_lines_t.journal_date BETWEEN '$start_date' and '$end_date' group by f_journal_entry_t.journal_entry_id order by f_journal_entry_t.journal_date asc)
 union all 
   (SELECT 0 as journal_entry_id,'Salary' as journal_name,'Salary' as journal_type,date as journal_date,net_salary,'Salary' as narr
   FROM `hr_employee_payroll_lists` WHERE employee_id='$employee_id' and date BETWEEN '$start_date' and '$end_date'))v1 where v1.journal_type!='JOB CARD CLOSE' order by v1.journal_date asc ");
 
//dd($employee_data);
$count = count($employee_data);
               if( $count > 0 && $limit > 0)
    {
    $total_pages = ceil($count/$limit);
    } else {
    $total_pages = 0;
    }
    if ($page > $total_pages)
    $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;
         $loc=\Session::get('location');

        $balance= \DB::select("select sum(f.debit-f.credit)as balance from( select COALESCE(SUM(debit_amount),0) as debit,COALESCE(SUM(credit_amount),0)as credit from f_journal_entry_lines_t where f_journal_entry_lines_t.reference_source='employee' and f_journal_entry_lines_t.reference_id='$employee_id' and f_journal_entry_lines_t.journal_date < '$start_date')f");
       $emp_balance=\DB::select("select COALESCE(sum(hr_employee_payroll_lists.net_salary),0) as balance_amount from hr_employee_payroll_lists where hr_employee_payroll_lists.employee_id='$employee_id' and hr_employee_payroll_lists.date < '$start_date'");
       $opning_balance=\DB::select("SELECT sum(amount) as amount from hr_imprest_tbl where employee_id='$employee_id' and imprest_number='OPENING BALANCE' and imprest_date='2019-04-01'");
           
		   $b=0;
		   if($opning_balance[0]->amount!=null)
		   {
			   if($opning_balance[0]->amount>0)
			   {
				   $b=$opning_balance[0]->amount*(-1);
			   }
			   else
			   {
				  $b=$opning_balance[0]->amount*(-1); 
			   }
		   }
		   //dd($opning_balance);          
                     $compy=\Session::get('companyid');

                    $balance=round($balance[0]->balance+$emp_balance[0]->balance_amount+$b,2);

					$overall_datas[0] = (object)array();
										
				if($balance>0)
				{					
					$overall_datas[0]->balance=$balance;
  //$balance=$balance+$v->debit_amount;
  $overall_datas[0]->debit_amounts=$balance;
  $overall_datas[0]->credit_amounts=0;
  $overall_datas[0]->net_salary=0;
  $overall_datas[0]->journal_date=$start_date;
  $overall_datas[0]->journal_type="Opening Balance";
  $overall_datas[0]->journal_name="Opening Balance";
  $overall_datas[0]->concatenated_segments='';
  $overall_datas[0]->narr='';
				}
				else
				{
				$overall_datas[0]->balance=$balance;
  //$balance=$balance+$v->debit_amount;
  $overall_datas[0]->debit_amounts=0;
  $overall_datas[0]->credit_amounts=$balance*-1;
  $overall_datas[0]->net_salary=0;
  $overall_datas[0]->journal_date=$start_date;
  $overall_datas[0]->journal_type="Opening Balance";
  $overall_datas[0]->journal_name="Opening Balance";
  $overall_datas[0]->concatenated_segments='';
  $overall_datas[0]->narr='';
  //narr
				}

					$key=1;

foreach($employee_data as $k=>$value)
{
	
	$id=$value->journal_entry_id;
if($value->journal_type=="SALES INVOICE")
{
	$datas=\DB::select("SELECT  debit_amount as debit_amount , sum(credit_amount) as credit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");
}
elseif($value->journal_type=="RECEIPT")
{
	$datas=\DB::select("SELECT 0 as debit_amount , round(sum(debit_amount),2) as credit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");

}
elseif($value->journal_type=="EXPENSES")
{
	$datas=\DB::select("select 0 as debit_amount,round(sum(credit_amount),2) as credit_amount,account_id,concatenated_segments from((SELECT sum(debit_amount) as debit_amount , 0 as credit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id') union all (SELECT sum(debit_amount) as debit_amount , credit_amount as credit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.reference_source='EMPLOYEE'))v1");
//dd($datas);
}
elseif($value->journal_type=="PAYMENT" || $value->journal_type=="IMPRESTPAYMENT" || $value->journal_type=="SALARYPAYMENT" || $value->journal_type=="DIRECTPAYMENT" || $value->journal_type=="REVERSE-IMPRESTPAYMENT")
{

//dd($id);
  if($value->journal_type=='REVERSE-IMPRESTPAYMENT'){
      $datas=\DB::select("SELECT debit_amount as credit_amount , 0 as debit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");

  }else{
    
  
	$datas=\DB::select("SELECT credit_amount as debit_amount , 0 as credit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount=0");
}
if($datas[0]->account_id=='106')
{
	//dd($datas);
	//dd($value);
$datas1=\DB::select("SELECT   debit_amount , credit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' ");
if(Count($datas1)>1){
$datas=\DB::select("SELECT   debit_amount , credit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.reference_id='$employee_id' ");
}else{
$datas=\DB::select("SELECT   debit_amount , credit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' ");
}

if($datas[0]->account_id!='106')
{
$datas[0]->credit_amount=round($datas[0]->debit_amount,2);	
$datas[0]->debit_amount=0;
unset($datas[1]);
//dd($datas);
}


}
}
elseif($value->journal_type=="Salary" )
{
//echo "1";
$datas=array();
	$datas[0]=(object) array();
	$datas[0]->concatenated_segments="Salary";
	$datas[0]->debit_amount=0;
	$datas[0]->credit_amount=$value->net_salary;
 // print_r($datas[0]->credit_amount."//");
  //dd($datas[0]->credit_amount);
}elseif($value->journal_type=="MANUAL" )
{
	
$datas=\DB::select("SELECT debit_amount , credit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.reference_source='EMPLOYEE' and f_journal_entry_lines_t.reference_id='$employee_id' and f_journal_entry_lines_t.account_id!='106'");
$da=\DB::select("SELECT debit_amount , credit_amount ,account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.reference_source='EMPLOYEE' and f_journal_entry_lines_t.reference_id='$employee_id' and f_journal_entry_lines_t.account_id='106'");
if(isset($da[0]))
{
if($da[0]->debit_amount >0)
{
	foreach($datas as $k1=>$v)
{
	$datas[$k1]->debit_amount=$v->credit_amount;
	$datas[$k1]->credit_amount=0;
}
}
else
{
		foreach($datas as $k1=>$v)
{
	$datas[$k1]->credit_amount=$v->debit_amount;
	$datas[$k1]->debit_amount=0;
}
	
}	
}
}
//dd($datas);
foreach($datas as $v)
{
	$overall_datas[$key] = (object)array();
if($v->debit_amount > 0 )
{
  $overall_datas[$key]->concatenated_segments=$v->concatenated_segments;	
  $overall_datas[$key]->balance=round($balance+$v->debit_amount,2);
  $balance=$balance+$v->debit_amount;
  $overall_datas[$key]->debit_amounts=$v->debit_amount;
  $overall_datas[$key]->net_salary=0;
  $overall_datas[$key]->journal_date=$value->journal_date;
  $overall_datas[$key]->journal_type=$value->journal_type;
  $overall_datas[$key]->journal_name=$value->journal_name;
  $overall_datas[$key]->narr=$value->narr;
}
else
{
$overall_datas[$key]->concatenated_segments=$v->concatenated_segments;
  $overall_datas[$key]->balance=round($balance-$v->credit_amount,2);
  $balance=$balance-$v->credit_amount; 
  $overall_datas[$key]->credit_amounts=$v->credit_amount;
  $overall_datas[$key]->net_salary=0;
  $overall_datas[$key]->journal_date=$value->journal_date;
  $overall_datas[$key]->journal_type=$value->journal_type;
  $overall_datas[$key]->journal_name=$value->journal_name;
  $overall_datas[$key]->narr=$value->narr;
}
$key++;
}

}
//dd($overall_data);
       $result1 =$overall_datas;
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }


    $responce->rows[]='';
    $responce->data=$overall_datas;
    $responce->curPage = $page;
    $responce->total = $total_pages;
    $responce->totalRecords = $count;
    echo json_encode($responce);
   
   }
   public function emppindex()
    {   

 return view('employeebalancerpt.emp', $this->data);       
    }


public function getemp(){

            $wh='';
            if(isset($_GET['pq_filter'])){
    $data=json_decode($_GET['pq_filter']);
    $data=$data->data;
    $table=array('i_qoh_detail_t','m_products_t','a_m_group_t','m_product_subcategory_t','m_product_category_t');
                $wh.=$this->pqgridsearch('i_qoh_detail_t',$data,$table);
      }
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;

              $result = \DB::select("SELECT m_products_t.concatenated_product,i_qoh_detail_t.`qoh_trx_qty`,i_qoh_detail_t.`batch_number`,i_qoh_detail_t.`qoh_source`,i_qoh_detail_t.`job_id`,(i_qoh_detail_t.`qoh_trx_qty`*i_qoh_detail_t.`cost`) as cost,w_jobcard_hdr_t.job_no,w_jobcard_hdr_t.job_process,w_jobcard_hdr_t.bom_process,w_qa_submitstage_trx_t.job_assigned_to,w_qa_submitstage_trx_t.total_working_hrs,w_qa_submitstage_trx_t.working_hrs  FROM `i_qoh_detail_t` left join w_jobcard_hdr_t on(w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id) left join m_products_t on(m_products_t.product_id=i_qoh_detail_t.`product_id`) left join w_qa_submitstage_trx_t on(w_qa_submitstage_trx_t.job_no=i_qoh_detail_t.job_id) WHERE i_qoh_detail_t.`qoh_source`='WIP Store Move'");
             

    $count = COUNT($result);
    if( $count > 0 && $limit > 0)
    {
    $total_pages = ceil($count/$limit);
    } else {
    $total_pages = 0;
    }
    if ($page > $total_pages)
    $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;
      $org=\Session::get('organization');
      $loc=\Session::get('location');
      $compy=\Session::get('companyid');
        $SQL = "SELECT m_products_t.concatenated_product,
        i_qoh_detail_t.qoh_trx_qty,
        i_qoh_detail_t.batch_number,
        i_qoh_detail_t.qoh_source,
        i_qoh_detail_t.job_id,
        (i_qoh_detail_t.qoh_trx_qty*i_qoh_detail_t.cost) as cost,
        w_jobcard_hdr_t.job_process,
        w_jobcard_hdr_t.bom_process,
        w_qa_submitstage_trx_t.job_assigned_to,
        w_jobcard_hdr_t.job_no,
        w_qa_submitstage_trx_t.total_working_hrs,
        w_qa_submitstage_trx_t.working_hrs 
         FROM i_qoh_detail_t 
         left join w_jobcard_hdr_t on(w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id)
         left join m_products_t on(m_products_t.product_id=i_qoh_detail_t.`product_id`) 
         left join w_qa_submitstage_trx_t on(w_qa_submitstage_trx_t.job_no=i_qoh_detail_t.job_id)
          WHERE i_qoh_detail_t.`qoh_source`='WIP Store Move' 
         ";


     $result = \DB::select( $SQL );
    $responce->rows[]='';
    $responce->data=$result;
    $responce->curPage = $page;
    $responce->total = $total_pages;
    $responce->totalRecords = $count;
    echo json_encode($responce);
  }




}