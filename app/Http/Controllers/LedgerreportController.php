<?php

namespace App\Http\Controllers;

use App\ledgerreport;
use Illuminate\Http\Request;
use DB;

class LedgerreportController extends Controller
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
        $gname =\Session::get('groupname');
		if($gname=="3"){
		  $this->data['account_id']=$this->jcombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
		}else{
		$emp=\Session::get('emp_id');
		$emp_data   =   DB::table("hr_employee_t")->where('employee_id',$emp)->get();
		 //dd($emp_data);
          $dept=json_decode($emp_data[0]->department);
           if (in_array(28, $dept) || in_array(15, $dept))
           {
            $this->data['account_id']=$this->jcustomselecttool('f_account_structure_t','f_account_structure_id','concatenated_segments',''," and (concatenated_segments LIKE '%PF%' OR concatenated_segments LIKE '%ESI%') and concatenated_segments NOT LIKE '%DESIGN%'");  
           }else{
    	    $this->data['account_id']=$this->jcombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
           }
		}  

		  return view('ledgerreport.ledgerbalance', $this->data);       
    }

	
   public function ledgerbalance(Request $request){

	$start = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';  
    //$ledger_id  = $request->ledger_id;

    $account_id = $request->account_id;
    $acc_name = 0;

 $exclude_ledgers = $request->get('exclude_ledgers') ?? [];
    $filterMode = $request->get('filter_mode');

    // Exclude condition placeholder
    $excludeConditionSql = '';
    /*if ($filterMode === 'exclude' && !empty($exclude_ledgers)) {
        $ids = implode(',', array_map('intval', $exclude_ledgers));
        $excludeConditionSql = " AND fjel.account_id NOT IN ($ids)";
    }*/

    // ==============================
    // 🔹 FETCH LEDGER TRANSACTIONS
    // ==============================
    $ledger_data = \DB::select("
        SELECT * FROM (
            SELECT 
                fje.journal_name,
                fje.journal_type,
                fjel.journal_entry_id,
                fjel.debit_amount,
                fjel.credit_amount,
                fjel.journal_date,
                fjel.f_journal_entry_line_id,
                fjel.reference_source,
                CASE 
                    WHEN fjel.reference_source='PRODUCT' THEN mp.concatenated_product
                    WHEN fjel.reference_source='EMPLOYEE' THEN he.first_name
                    WHEN fjel.reference_source='SUPPLIER' THEN ms.supplier_name
                    WHEN fjel.reference_source='CUSTOMER' THEN mc.customer_name
                    WHEN fjel.reference_source='MACHINE' THEN wm.machine_name
                    WHEN fjel.reference_source IN ('PAYROLL', 'PAYROLL/PF/ESI') THEN mdl.sub_department_name
                    ELSE ''
                END AS reference_name,
                fjel.product_qty,
                fjel.batch_number
            FROM f_journal_entry_lines_t fjel
            LEFT JOIN m_products_t mp ON mp.product_id=fjel.reference_id
            LEFT JOIN hr_employee_t he ON he.employee_id=fjel.reference_id
            LEFT JOIN m_supplier_t ms ON ms.supplier_id=fjel.reference_id
            LEFT JOIN m_customers_t mc ON mc.customer_id=fjel.reference_id
            LEFT JOIN w_machine_hdr_t wm ON wm.machine_hdr_id=fjel.reference_id
            LEFT JOIN m_department_lines_t mdl ON mdl.department_line_id=fjel.reference_id
            JOIN f_journal_entry_t fje ON fje.journal_entry_id=fjel.journal_entry_id
            WHERE fjel.account_id='$account_id'
              AND fjel.journal_date BETWEEN '$start' AND '$end'
              AND fje.journal_type!='OPENING BALANCE'
              
            ORDER BY fjel.journal_date ASC
        ) AS v1
        WHERE 1=1
    ");

    // ==============================
    // 🔹 FETCH OPENING BALANCE (fixed logic)
    // ==============================
    $openingSql = "
        SELECT (
            CASE 
                WHEN (fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%')
                THEN 0
                ELSE ROUND(SUM(
                    CASE 
                        WHEN fje.journal_type = 'MANUAL' AND fjel.reference_source = 'CUSTOMER' AND fjel.credit_amount > 0 THEN   fjel.credit_amount
                        ELSE 0
                    END
                    +
                    CASE 
                        WHEN fje.journal_type != 'MANUAL' THEN fjel.debit_amount
                        ELSE 0
                    END
                    +
                    CASE 
                        WHEN fje.journal_type = 'MANUAL' AND fjel.reference_source != 'CUSTOMER' THEN fjel.debit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN fje.journal_type = 'MANUAL' AND fjel.reference_source = 'CUSTOMER' AND fjel.debit_amount > 0 THEN fjel.debit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN fje.journal_type = 'MANUAL' AND fjel.reference_source != 'CUSTOMER' THEN fjel.credit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN fje.journal_type != 'MANUAL' THEN fjel.credit_amount
                        ELSE 0
                    END
                ), 2)
            END
        ) AS opening_balance
        FROM f_journal_entry_lines_t fjel
        JOIN f_journal_entry_t fje ON fje.journal_entry_id = fjel.journal_entry_id
        JOIN f_account_structure_t fas ON fas.f_account_structure_id = fjel.account_id
        WHERE fjel.account_id = :account_id
          AND (DATE(fjel.journal_date) < :start_date OR fje.journal_type = 'OPENING BALANCE')
          $excludeConditionSql
    ";

    $openingRow = \DB::selectOne($openingSql, [
        'account_id' => $account_id,
        'start_date' => $start
    ]);

    $balance = round($openingRow->opening_balance ?? 0, 2);

    // ==============================
    // 🔹 BUILD OPENING ENTRY ROW
    // ==============================
    $overall_datas = [];
    $overall_datas[0] = (object)[];
    $overall_datas[0]->sort_order = 0;
    if ($balance > 0) {
        $overall_datas[0]->balance = $balance;
        $overall_datas[0]->debit_amounts = $balance;
        $overall_datas[0]->credit_amounts = 0;
        $overall_datas[0]->net_salary = 0;
        $overall_datas[0]->journal_date = $start;
        $overall_datas[0]->journal_type = "Opening Balance";
        $overall_datas[0]->journal_name = "Opening Balance";
        $overall_datas[0]->concatenated_segments = '';
        $overall_datas[0]->reference_source = '';
        $overall_datas[0]->reference_name = '';
        $overall_datas[0]->product_qty = '';
        $overall_datas[0]->batch_number = '';
    } else {
        $overall_datas[0]->balance = $balance;
        $overall_datas[0]->debit_amounts = 0;
        $overall_datas[0]->credit_amounts = $balance * -1;
        $overall_datas[0]->net_salary = 0;
        $overall_datas[0]->journal_date = $start;
        $overall_datas[0]->journal_type = "Opening Balance";
        $overall_datas[0]->journal_name = "Opening Balance";
        $overall_datas[0]->concatenated_segments = '';
        $overall_datas[0]->reference_source = '';
        $overall_datas[0]->reference_name = '';
        $overall_datas[0]->product_qty = '';
        $overall_datas[0]->batch_number = '';
    }

    

    // ==============================
    // 🔹 LOOP THROUGH LEDGER DATA
    // ==============================
    $key = 1;
    foreach ($ledger_data as $value) {
            if($value->reference_source!='')
        {
        $id = $value->journal_entry_id;
        $reference_name = $value->reference_name;
        $reference_source = $value->reference_source;
        $product_qty = $value->product_qty;
        $batch_number = $value->batch_number;
        $debit_amount = $value->debit_amount;
        $credit_amount = $value->credit_amount;

        // Get the counter-account segment name
        if ($debit_amount > 0) {
            $accQuery = \DB::select("
                SELECT fas.concatenated_segments
                FROM f_journal_entry_lines_t fjel
                JOIN f_account_structure_t fas 
                  ON fas.f_account_structure_id = fjel.account_id
                WHERE fjel.journal_entry_id = '$id'
                  AND fjel.debit_amount = 0
                LIMIT 1
            ");
              
        } else {
            $accQuery = \DB::select("
                SELECT fas.concatenated_segments
                FROM f_journal_entry_lines_t fjel
                JOIN f_account_structure_t fas 
                  ON fas.f_account_structure_id = fjel.account_id
                WHERE fjel.journal_entry_id = '$id'
                  AND fjel.credit_amount = 0
                LIMIT 1
            ");

            if (!empty($accQuery)) {
                $acc_name = $accQuery[0]->concatenated_segments;
                } else {
                    $acc_name = 0; // or handle the case where there is no data
                }
        }

    }
    else {

      //  $acc_name = !empty($accQuery) ? $accQuery[0]->concatenated_segments : '';
        $id = $value->journal_entry_id;
        $product_qty = $value->product_qty;
        $batch_number = $value->batch_number;
        $debit_amount = $value->debit_amount;
        $credit_amount = $value->credit_amount;

            if($debit_amount > 0)
        {
      $wh1=" and f_journal_entry_lines_t.debit_amount=0";
     
        }
    else
     {
      $wh1=" and f_journal_entry_lines_t.credit_amount=0";
        }

$d=\DB::select("select v2.*, if(v2.names!='',v2.names,v2.nameothr) as name,if(v2.reference_source!='',v2.reference_source,v2.reff) as reference_source from (select f_journal_entry_lines_t.journal_entry_id,
  f_journal_entry_lines_t.debit_amount,
  f_journal_entry_lines_t.credit_amount,
  f_journal_entry_lines_t.journal_date,
  f_journal_entry_lines_t.`f_journal_entry_line_id`,
  f_account_structure_t.concatenated_segments ,
  f_journal_entry_lines_t.reference_source,
   (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
         WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
         WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
         WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
         WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
         WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
         WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
         else '' end ) as names,
         (select 
(case  WHEN jlin.reference_source='EMPLOYEE' then emp.first_name
         WHEN jlin.reference_source='SUPPLIER' then sup.supplier_name
         WHEN jlin.reference_source='CUSTOMER' then cus.customer_name
   else '' end ) as nameothr       
     from f_journal_entry_lines_t as jlin 
     left JOIN hr_employee_t as emp ON emp.employee_id=jlin.reference_id and jlin.reference_source='EMPLOYEE'
     left JOIN m_supplier_t as sup ON sup.supplier_id=jlin.reference_id and jlin.reference_source='SUPPLIER'
     left JOIN m_customers_t as cus ON cus.customer_id=jlin.reference_id and jlin.reference_source='CUSTOMER'
         where jlin.journal_entry_id='$id' and (jlin.reference_source='EMPLOYEE' or jlin.reference_source='SUPPLIER' or jlin.reference_source='CUSTOMER') limit 1 ) as nameothr,
         (select jlin1.reference_source as reff     
     from f_journal_entry_lines_t as jlin1 
         where jlin1.journal_entry_id='$id' and (jlin1.reference_source='EMPLOYEE' or jlin1.reference_source='SUPPLIER' or jlin1.reference_source='CUSTOMER') limit 1 ) as reff
     from f_journal_entry_lines_t 
     left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id 
     left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id and f_journal_entry_lines_t.reference_source='EMPLOYEE'
     left JOIN m_supplier_t  ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id and f_journal_entry_lines_t.reference_source='SUPPLIER'
     left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id and f_journal_entry_lines_t.reference_source='CUSTOMER'
     left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id
     left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id 
     JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id where f_journal_entry_lines_t.journal_entry_id='$id' $wh1
) AS v2 where 1=1");

$dd=collect($d);
$dk=$dd->where('credit_amount',$debit_amount)->where('debit_amount',$credit_amount);
//dd($dk);
//$dk->all();
if(count($dk)>0)
{
    foreach($dk as $dkv)
    {
     $reference_name=$dkv->name;
    $reference_source=$dkv->reference_source;
$acc_name=$dkv->concatenated_segments;   
    }
}
else
{

$reference_name=$d[0]->name;
    $reference_source=$d[0]->reference_source;
$acc_name=$d[0]->concatenated_segments; 
}

  }


        $overall_datas[$key] = (object)[];
        
        

        // Debit or Credit Handling
        if ($debit_amount > 0) {
            $overall_datas[$key]->concatenated_segments = $acc_name;
            if ($value->journal_type == 'MANUAL' && $reference_source == 'CUSTOMER') {
                $overall_datas[$key]->credit_amounts = abs($debit_amount);
                $overall_datas[$key]->debit_amounts = 0;
                $balance -= abs($debit_amount);
            } else {
                $overall_datas[$key]->debit_amounts = $debit_amount;
                $overall_datas[$key]->credit_amounts = 0;
                $balance += $debit_amount;
            }

            $overall_datas[$key]->journal_entry_id = $id;
            $overall_datas[$key]->journal_date = $value->journal_date;
            $overall_datas[$key]->journal_type = $value->journal_type;
            $overall_datas[$key]->journal_name = $value->journal_name;
            $overall_datas[$key]->reference_source = $reference_source;
            $overall_datas[$key]->reference_name = $reference_name;
            $overall_datas[$key]->product_qty = $product_qty;
            $overall_datas[$key]->batch_number = $batch_number;

        } else {
                $overall_datas[$key]->concatenated_segments = $acc_name;
            if ($value->journal_type == 'MANUAL' && $reference_source == 'CUSTOMER') {
                $overall_datas[$key]->debit_amounts = abs($credit_amount);
                $overall_datas[$key]->credit_amounts = 0;
                $balance += abs($credit_amount);
            } else {
                $overall_datas[$key]->credit_amounts = $credit_amount;
                $overall_datas[$key]->debit_amounts = 0;
                $balance -= $credit_amount;
            }

            $overall_datas[$key]->journal_entry_id = $id;
            $overall_datas[$key]->journal_date = $value->journal_date;
            $overall_datas[$key]->journal_type = $value->journal_type;
            $overall_datas[$key]->journal_name = $value->journal_name;
            $overall_datas[$key]->reference_source = $reference_source;
            $overall_datas[$key]->reference_name = $reference_name;
            $overall_datas[$key]->product_qty = $product_qty;
            $overall_datas[$key]->batch_number = $batch_number;
        }
        $overall_datas[$key]->sort_order = 1;
        $overall_datas[$key]->balance = round($balance, 2);
        $overall_datas[$key]->net_salary = 0;
        $key++;
    }



    // Return JSON response
    $data = collect($overall_datas)->sortBy([
        ['sort_order', 'asc'],
        ['journal_date', 'asc']
    ])->values()->toArray();

    //return response()->json($data);

    return response()->json(['data' => $data ?? []]);

    
}
	
	
}