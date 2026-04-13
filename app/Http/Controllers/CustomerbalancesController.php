<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Customerbalances;
use Illuminate\Http\Request;
use DB;

class CustomerbalancesController extends Controller
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
		 
        $this->data['customeropt']=$this->jqgridselect('m_customers_t','customer_id','customer_name'); 
        $this->data['pageMethod']='customerbalancesrpt'; 
		 
		 $org=\Session::get('organization');
        $loc="1";
        $compy=\Session::get('companyid');
        
               $SQL = "SELECT 
                s_invoice_hdr_t.invoice_number,
                s_invoice_hdr_t.invoice_hdr_id,
				m_customers_t.customer_id as customer_id,
                m_customers_t.customer_name as ship_to_customer_id,
                s_invoice_hdr_t.invoice_date,
                SUM(ROUND(s_invoice_hdr_t.invoice_grand_total,2)) as invoice_grand_total,
                SUM(ROUND(s_invoice_hdr_t.paid_amount,2))as paid_amount,
                SUM(ROUND(s_invoice_hdr_t.balance_amount,2))as balance_amount,
                SUM(s_invoice_hdr_t.balance_amount) as total_balance,
                IF(s_invoice_hdr_t.paid_amount <= 0,'UnPaid','Partital Paid') AS paid_status
                FROM s_invoice_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) 
                where 1=1  and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.company_id=$compy and s_invoice_hdr_t.location_id=$loc GROUP BY s_invoice_hdr_t.ship_to_customer_id ";
               
		$result = \DB::select( $SQL );
		$this->data['result']=json_encode($result);
		
		 
		 
       return view('customerbalancesrpt.table',$this->data);
    }
  
public function productrmrpt()
    {
     
        $this->data['product_id']=$this->jcustomselect('m_products_t','product_id','product_code|concatenated_product','',' and product_group_id in (2,12) '); 
        
     
       return view('customerbalancerpt.producttable',$this->data);
    }

      public function getproductrmrpt1Data(){
              $wh='';
           if(isset($_GET['pq_filter'])){
    $data=json_decode($_GET['pq_filter']);
    $data=$data->data;
///    $table=array('m_customers_t');
              $wh.=$this->pqgridsearch('v1',$data);
  }
                $page = $_GET['pq_curpage'];
                $limit = $_GET['pq_rpp'];
                $sidx='';
        if (!$sidx)
            $sidx = 1;
          $start_date=$GET_['start_date'];
          $end_date=$GET_['end_date'];
          $product_id=$GET_['product_id'];
              $result = \DB::select("SELECT COUNT(s_invoice_hdr_t.invoice_hdr_id) AS count FROM s_invoice_hdr_t left join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id)  where 1=1 $wh");
    $count = $result[0]->count;
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

               $SQL = "SELECT 
                s_invoice_hdr_t.invoice_number,s_invoice_hdr_t.invoice_hdr_id,
                m_customers_t.customer_name,
                s_invoice_hdr_t.ship_to_customer_id,
                s_invoice_hdr_t.invoice_date,
               ROUND(SUM(s_invoice_hdr_t.debit_note),2) as debit_note,
               ROUND(SUM(s_invoice_hdr_t.credit_note),2) as credit_note,
                ROUND(SUM(s_invoice_hdr_t.invoice_grand_total)+(s_invoice_hdr_t.debit_note)-(s_invoice_hdr_t.credit_note),2) as invoice_grand_total,
                ROUND(SUM(s_invoice_hdr_t.paid_amount),2)as paid_amount,
                ((ROUND(SUM(s_invoice_hdr_t.balance_amount),2) - sum(s_invoice_hdr_t.credit_note)) + sum(s_invoice_hdr_t.debit_note)) as balance_amount,
                 ((ROUND(SUM(s_invoice_hdr_t.balance_amount),2) - sum(s_invoice_hdr_t.credit_note)) + sum(s_invoice_hdr_t.debit_note)) as total_balance,
                IF(s_invoice_hdr_t.paid_amount <= 0,'UnPaid','Partital Paid') AS paid_status
                FROM s_invoice_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) 
                where 1=1 $wh and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.company_id=$compy and s_invoice_hdr_t.location_id=$loc GROUP BY s_invoice_hdr_t.ship_to_customer_id ORDER BY $sidx LIMIT $start , $limit";
    

    if(isset($_GET['download']))
    {

        $result1=collect($result)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }

$result = \DB::select( $SQL );
    $responce->rows[]='';
    $responce->data=$result;
    $responce->curPage = $page;
    $responce->total = $total_pages;
    $responce->totalRecords = $count;
    echo json_encode($responce);
  }  

         /* Purpose for vendor balance pqgrid*/
    	public function getcustomerbalanceData(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
    $compy=\Session::get('companyid');

    $SQL = "SELECT * from (
                SELECT 
                s_invoice_hdr_t.invoice_number,s_invoice_hdr_t.invoice_hdr_id,
                m_customers_t.customer_name,
                s_invoice_hdr_t.ship_to_customer_id,
                s_invoice_hdr_t.invoice_date,
                ROUND(SUM(s_invoice_hdr_t.debit_note),2) as debit_note,
                ROUND(SUM(s_invoice_hdr_t.credit_note),2) as credit_note,
                ROUND(SUM(s_invoice_hdr_t.invoice_grand_total)+(s_invoice_hdr_t.debit_note)-(s_invoice_hdr_t.credit_note),2) as invoice_grand_total,
                ROUND(SUM(s_invoice_hdr_t.paid_amount),2)as paid_amount,
                ((ROUND(SUM(s_invoice_hdr_t.balance_amount),2) - sum(s_invoice_hdr_t.credit_note)) + sum(s_invoice_hdr_t.debit_note)) as balance_amount,
                 ((ROUND(SUM(s_invoice_hdr_t.balance_amount),2) - sum(s_invoice_hdr_t.credit_note)) + sum(s_invoice_hdr_t.debit_note)) as total_balance,
                IF(s_invoice_hdr_t.paid_amount <= 0,'UnPaid','Partital Paid') AS paid_status
                FROM s_invoice_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) 
                where 1=1 AND s_invoice_hdr_t.invoice_date BETWEEN ? AND ?  and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.company_id=$compy GROUP BY s_invoice_hdr_t.ship_to_customer_id) AS v1";

		    $results = \DB::select($SQL, [$start_date, $end_date]);

            return response()->json(['data' => $results]);
}
	
	
	public function customerwisereport()
{
    $this->data['pageMethod']='customerbalancesrpt';
    $this->data['cus_name']=$this->jcombocomp('m_customers_t','customer_id','customer_name','');
    $this->data['emp_name']=$this->jcombocomp('hr_employee_t','employee_id','first_name','');
    return view('customerbalancerpt.customerwisereport',$this->data);
}
	
	
public function customerwisereportdata(Request $request){
    
          $wh='';
          
		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
		$cus_name = $request->cus_name ? : '';
		$emp_name = $request->emp_name ?  : '';
	
           
        if($cus_name !=''){
        if(isset($cus_name)){
        $wh=' and v1.ship_to_customer_id='.$cus_name;
        }else{
        $wh='';     
        }   
        }
        if($emp_name != ''){
        if(isset($emp_name)){
        $wh=' and v1.employee_id='.$emp_name;
        }else{
        $wh='';     
        }
        }
        
$result = \DB::select("select * from (SELECT
              s_salesorder_hdr_t.sales_hdr_id,
              
    (case when s_salesorder_hdr_t.ship_to_customer_id != 0 THEN m_customers_t.customer_name ELSE hr_employee_t.first_name end) as customer_name,
    CASE WHEN s_salesorder_hdr_t.employee_id != 0 THEN
    
    (SELECT
        state_name
    FROM
        `m_states_t`
    WHERE
        state_id = hr_emp_contact.current_state)
        ELSE
        (SELECT
        state_name
    FROM
        `m_states_t`
    WHERE
        state_id = m_customer_sites_t.state)
        
        END AS state,
    s_salesorder_hdr_t.order_type_id,
    s_salesorder_hdr_t.sales_order_no,
    s_salesorder_hdr_t.sales_order_date,
    s_salesorder_hdr_t.order_total,
    s_salesorder_hdr_t.order_status_id,
    s_dispatch_hdr_t.dispatch_number,
    s_dispatch_hdr_t.dispatch_date,
    s_dispatch_hdr_t.dispatch_status,
    s_invoice_hdr_t.invoice_number,
    s_invoice_hdr_t.invoice_date,
    s_invoice_hdr_t.invoice_grand_total,
    s_invoice_hdr_t.balance_amount as inv_bal_amount,
    s_receipts_t.receipt_number,
    s_receipts_t.receipt_date,
    s_receipts_t.receipt_amount,
    s_salesorder_hdr_t.ship_to_customer_id,
    s_salesorder_hdr_t.employee_id,
    (case when s_receipts_t.balance_amount < 0 THEN 0 ELSE s_receipts_t.balance_amount end) as balance_amount,
    (case when s_receipts_t.stmtid > 0 THEN 1 ELSE 0 end) as brs_receipt
FROM
    `s_salesorder_hdr_t`
LEFT JOIN s_dispatch_hdr_t ON s_salesorder_hdr_t.sales_hdr_id = s_dispatch_hdr_t.reference_source_id    
LEFT JOIN s_invoice_hdr_t ON s_salesorder_hdr_t.sales_hdr_id = s_invoice_hdr_t.ar_sales_hdr_id
LEFT JOIN m_customers_t ON s_salesorder_hdr_t.ship_to_customer_id = m_customers_t.customer_id
LEFT JOIN hr_employee_t ON s_salesorder_hdr_t.employee_id = hr_employee_t.employee_id
LEFT JOIN s_receipts_t ON s_invoice_hdr_t.invoice_hdr_id = s_receipts_t.invoice_hdr_id
left join m_customer_sites_t on m_customer_sites_t.customer_site_id=s_salesorder_hdr_t.ship_to_address_id 
LEFT JOIN hr_emp_contact ON hr_emp_contact.employee_id = s_salesorder_hdr_t.employee_id) v1 where 1=1 $wh  and v1.sales_order_date >= '$start_date' AND v1.sales_order_date <= '$end_date'");

		        $dp_no='';
               foreach($result as $k =>$v){
                $dp_num=$v->sales_hdr_id;
                if($dp_num!=null){
                $dp_numb=\DB::select("select invoice_number from s_invoice_hdr_t where ar_sales_hdr_id in ($dp_num)");
                $dp="";
                foreach($dp_numb as $pk=>$pv){
                $dp.=$pv->invoice_number.",";
                }
                $dp_number=rtrim($dp,",");
                $result[$k]->invoice_number=$dp_number;
                }
               }


    return response()->json(['data' => $result]);
    
}

	
	public function supplierwisereport(Request $request)
	{
		return view('customerbalancerpt.supplierwisereport', $this->data);
	}
	
	public function supplierwiseinvexpreport(Request $request)
{
    return view('customerbalancerpt.supplierwiseinvexpreport',$this->data);
}
	
	public function supplierwisereportdata(Request $request)
	{

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : NULL;
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : NULL;

$SQL = "select * from (SELECT
    p_po_hdr_t.po_hdr_id, 
    m_supplier_t.supplier_name,
    p_po_hdr_t.po_type,
    p_po_hdr_t.po_number,
    p_po_hdr_t.po_date,
    p_po_hdr_t.po_grand_total,
    p_po_hdr_t.po_status,
    p_grn_hdr_t.grn_number,
    p_grn_hdr_t.grn_date,
    p_grn_hdr_t.grn_status,
    p_po_invoice_hdr_t.bill_number,
    p_po_invoice_hdr_t.invoice_date,
    p_po_invoice_hdr_t.invoice_grand_total,
    p_payments_t.payment_number,
    p_payments_t.payment_date,
    p_payments_t.payment_amount,
    (case when p_payments_t.balance_amount < 0 THEN 0 ELSE p_payments_t.balance_amount end) as balance_amount,
    (case when p_payments_t.stmtid > 0 THEN 1 ELSE 0 end) as brs_payment
FROM
    `p_po_hdr_t`
LEFT JOIN p_po_invoice_hdr_t ON p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number
LEFT JOIN p_grn_hdr_t ON p_po_hdr_t.po_hdr_id = p_grn_hdr_t.po_number
LEFT JOIN m_supplier_t ON p_po_hdr_t.supplier_id = m_supplier_t.supplier_id
LEFT JOIN p_payments_t ON p_po_invoice_hdr_t.po_invoice_id = p_payments_t.po_invoice_id 
WHERE p_po_hdr_t.po_date >= ? AND  p_po_hdr_t.po_date <= ?)v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
    
}

public function supplierwiseinvexpreportdata(Request $request)
{
    
    
    
          $wh='';
          
          //$start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?  date("Y-m-d", strtotime($_GET['start_date'])) : '';
          //$end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
           
           $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : NULL;
           $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : NULL;

           if(isset($_GET['pq_filter']))
        {
        $data=json_decode($_GET['pq_filter']);
        $data=$data->data;
        $wh.=$this->pqgridsearchsum('v1',$data);
        }
                $loc=\Session::get('location');
                $compy=\Session::get('companyid');
        $page = isset($_GET['pq_curpage']) ? intval($_GET['pq_curpage']) : 1;
        //$limit = $_GET['pq_rpp'];
      $sidx='';
        if (!$sidx)
            $sidx = 1;
            
    // =============================
// 1️⃣ Normalize Payments Before Join
// =============================
$paymentsRaw = DB::table('p_payments_t')
    ->whereBetween('payment_date', [$start_date, $end_date])
    ->get();
//dd($paymentsRaw);
// Expand comma-separated po_invoice_id + invoice_brkup into separate rows
$expandedPayments = collect();
//dd($expandedPayments);
foreach ($paymentsRaw as $pay) {
    $invoiceIds = !empty($pay->po_invoice_id) ? explode(',', $pay->po_invoice_id) : [];
    $amounts = !empty($pay->invoice_brkup) ? explode(',', $pay->invoice_brkup) : [];

    // Clean whitespace and normalize decimals
    $invoiceIds = array_map('trim', $invoiceIds);
    $amounts = array_map(function ($a) {
        return round(floatval(trim($a)), 2);
    }, $amounts);

    // Fetch all invoice totals for these IDs to validate pairing
    $invoiceTotalsRaw = DB::table('p_po_invoice_hdr_t')
        ->whereIn('po_invoice_id', $invoiceIds)
        ->pluck('invoice_grand_total', 'po_invoice_id');

    $invoiceTotals = [];
    foreach ($invoiceTotalsRaw as $key => $val) {
        $invoiceTotals[$key] = round(floatval($val), 2);
    }

    // Try to match amounts by comparing closest totals
    $matched = [];
    $usedAmounts = [];

    foreach ($invoiceIds as $invId) {
        $invTotal = isset($invoiceTotals[$invId]) ? $invoiceTotals[$invId] : null;

        if ($invTotal !== null) {
            $closest = null;
            $closestDiff = INF;
            foreach ($amounts as $idx => $amt) {
                if (isset($usedAmounts[$idx])) continue;
                $diff = abs($amt - $invTotal);
                if ($diff < $closestDiff) {
                    $closestDiff = $diff;
                    $closest = $idx;
                }
            }

            if ($closest !== null) {
                $matched[$invId] = $amounts[$closest];
                $usedAmounts[$closest] = true;
            }
        }
    }

    // Fallback sequential mapping if anything remains unmatched
    if (count($matched) < count($invoiceIds)) {
        foreach ($invoiceIds as $i => $id) {
            if (!isset($matched[$id])) {
                $matched[$id] = isset($amounts[$i]) ? $amounts[$i] : 0;
            }
        }
    }

    // Push mapped records
    foreach ($matched as $invoiceId => $amount) {
        $new = clone $pay;
        $new->single_po_invoice_id = $invoiceId;
        $new->single_amount = $amount;
        $expandedPayments->push($new);
    }
}
//dd($new->single_po_invoice_id = $invoiceId);
        
// =============================
// 2️⃣ PO-based Payments
// =============================
$poPayments = $expandedPayments
    ->where('payment_source', '!=', 'expense')
    ->map(function ($p) {
        $po = DB::table('p_po_invoice_hdr_t as inv')
            ->join('p_po_hdr_t as po', 'po.po_hdr_id', '=', 'inv.po_number')
            ->leftJoin('p_grn_hdr_t as grn', 'po.po_hdr_id', '=', 'grn.po_number')
            ->join('m_supplier_t as sup', 'sup.supplier_id', '=', 'inv.supplier_id')
            ->leftJoin('p_grn_hdr_t as gl', 'gl.grn_id', '=', 'inv.grn_number')
            ->leftJoin('m_supplier_sites_t as site', 'site.supplier_id', '=', 'sup.supplier_id')
            ->where('inv.po_invoice_id', $p->single_po_invoice_id)
            ->selectRaw("
                inv.po_invoice_id AS id,
                sup.supplier_name,
                sup.msme_status,
                site.gst_number,
                po.po_type,
                po.po_number,
                po.po_date,
                po.po_grand_total,
                po.po_status,
                gl.grn_number,
                gl.dc_date as grn_date,
                gl.grn_status AS status,
                inv.bill_number,
                inv.invoice_date,
                inv.invoice_grand_total AS total,
                ? AS payment_number,
                ? AS payment_date,
                ? AS payment_amount,
                CASE WHEN ? < 0 THEN 0 ELSE ? END AS balance_amount,
                CASE WHEN gl.dc_date != '' THEN DATEDIFF(?, gl.dc_date)
                     ELSE DATEDIFF(?, inv.invoice_date) END AS days,
                CASE WHEN ? > 0 THEN 1 ELSE 0 END AS brs_payment,
                ? AS payment_source
            ", [
                $p->payment_number,
                $p->payment_date,
                $p->single_amount,
                $p->balance_amount,
                $p->balance_amount,
                $p->payment_date,
                $p->payment_date,
                $p->stmtid,
                $p->payment_source
            ])
            ->first();

        return $po;
    })
    ->filter();

$paymentsexpRaw = DB::table('p_payments_t')
    ->whereBetween('payment_date', [$start_date, $end_date])
    ->get();
//dd($paymentsexpRaw);
// Expand comma-separated po_invoice_id + invoice_brkup into separate rows
$expandedexpPayments = collect();
//dd($expandedexpPayments);
foreach ($paymentsexpRaw as $pay) {
    $invoiceIds = !empty($pay->reference_id) ? explode(',', $pay->reference_id) : [];
    $amounts = !empty($pay->expense_brkup) ? explode(',', $pay->expense_brkup) : [];

    // Clean whitespace and normalize decimals
    $invoiceIds = array_map('trim', $invoiceIds);
    $amounts = array_map(function ($a) {
        return round(floatval(trim($a)), 2);
    }, $amounts);

    // Fetch all invoice totals for these IDs to validate pairing
    $invoiceTotalsRaw = DB::table('f_expenses_t')
        ->whereIn('expense_id', $invoiceIds)
        ->pluck('expense_amount', 'expense_id');

    $invoiceTotals = [];
    foreach ($invoiceTotalsRaw as $key => $val) {
        $invoiceTotals[$key] = round(floatval($val), 2);
    }

    // Try to match amounts by comparing closest totals
    $matched = [];
    $usedAmounts = [];

    foreach ($invoiceIds as $invId) {
        $invTotal = isset($invoiceTotals[$invId]) ? $invoiceTotals[$invId] : null;

        if ($invTotal !== null) {
            $closest = null;
            $closestDiff = INF;
            foreach ($amounts as $idx => $amt) {
                if (isset($usedAmounts[$idx])) continue;
                $diff = abs($amt - $invTotal);
                if ($diff < $closestDiff) {
                    $closestDiff = $diff;
                    $closest = $idx;
                }
            }

            if ($closest !== null) {
                $matched[$invId] = $amounts[$closest];
                $usedAmounts[$closest] = true;
            }
        }
    }

    // Fallback sequential mapping if anything remains unmatched
    if (count($matched) < count($invoiceIds)) {
        foreach ($invoiceIds as $i => $id) {
            if (!isset($matched[$id])) {
                $matched[$id] = isset($amounts[$i]) ? $amounts[$i] : 0;
            }
        }
    }

    // Push mapped records
    foreach ($matched as $invoiceId => $amount) {
        $new = clone $pay;
        $new->single_exp_invoice_id = $invoiceId;
        $new->single_amount = $amount;
        $expandedexpPayments->push($new);
    }
}

//dd($expandedexpPayments);
//dd($expandedexpPayments->pluck('single_exp_invoice_id', 'payment_number'));
//dd($expandedexpPayments->where('payment_source', 'EXPENSE')->pluck('single_exp_invoice_id'));


$expensePayments = $expandedexpPayments
    ->filter(function ($p) {
        return strtoupper($p->payment_source) === 'EXPENSE';
    })
    ->map(function ($p) {
        $p->balance_amount = $p->balance_amount ?? 0;
        $p->stmtid = $p->stmtid ?? 0;

        $exp = DB::table('f_expenses_t as e')
            ->Join('m_supplier_t as s', 's.supplier_id', '=', 'e.supplier_id')
            ->leftJoin('m_supplier_sites_t as site', 'site.supplier_id', '=', 's.supplier_id')
            ->where('e.expense_id', $p->single_exp_invoice_id)
            ->where('e.expense_status', 'approved')
            ->selectRaw("
                e.expense_id AS id,
                COALESCE(s.supplier_name, '') AS supplier_name,
                COALESCE(s.msme_status, '') AS msme_status,
                COALESCE(site.gst_number, '') AS gst_number,
                '' AS po_type,
                '' AS po_number,
                '' AS po_date,
                '' AS po_grand_total,
                '' AS po_status,
                '' AS grn_number,
                e.expense_date AS grn_date,
                e.expense_status AS status,
                e.expense_no AS bill_number,
                e.bill_date AS invoice_date,
                e.expense_amount AS total,
                ? AS payment_number,
                ? AS payment_date,
                ? AS payment_amount,
                CASE WHEN ? < 0 THEN 0 ELSE ? END AS balance_amount,
                DATEDIFF(?, e.bill_date) AS days,
                0 AS brs_payment,
                ? AS payment_source
            ", [
                $p->payment_number,
                $p->payment_date,
                $p->single_amount,
                $p->balance_amount,
                $p->balance_amount,
                $p->payment_date,
                $p->payment_source
            ])
            ->first();

        return $exp;
    })
    ->filter();

    // =============================
    // 3️⃣ Merge Both Results
    // =============================
    
    //dd($expensePayments);
$result = $poPayments->merge($expensePayments);
$sorted = $result->sortByDesc('payment_date')->values();

return response()->json([
    'curPage' => $page,
    'totalRecords' => $sorted->count(),
    'data' => $sorted
]);


}
    
}
