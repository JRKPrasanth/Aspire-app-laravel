<?php

namespace App\Http\Controllers;

use App\bankstatementupload;
use Illuminate\Http\Request;
use Validator, Input, Redirect, DB;
use Yajra\DataTables\DataTables;
use Session;

class BankstatementuploadController extends Controller
{
    public function __construct()
    {

        $this->pageModule = "viewstatementdetails";
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->table = "s_dispatch_hdr_t";
        $this->subtable = "s_dispatch_lines_t";
        $this->data = array(
            'pageModule' => 'viewstatementdetails',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu'] = $this->indexs();

    }

    public function index(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

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

        $batch = $type = '';
        $this->data['status'] = $this->data['message'] = '';
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND f_bankstmtupload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        $this->data['bank'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
        $this->data['account'] = $this->jqgridselect('f_bank_account_lines_t', 'bank_account_line_id', 'account_number');
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
        $this->data['account_no'] = $this->jcombo("f_bank_account_lines_t", "bank_account_line_id", "account_number", '');

        return view('bankstatementupload.table', $this->data);
    }

    public function view(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

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

        $sql = \DB::table('f_bankstmtupload_t')
            ->leftjoin('f_bank_account_hdr_t', 'f_bank_account_hdr_t.bank_account_hdr_id', '=', 'f_bankstmtupload_t.bank_id')
            ->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
            ->select('f_bankstmtupload_t.bankstmt_id', 'f_bankstmtupload_t.date', 'f_bankstmtupload_t.value_date', 'f_bankstmtupload_t.chq_no', 'f_bankstmtupload_t.narration', 'f_bankstmtupload_t.cod', 'f_bankstmtupload_t.debit', 'f_bankstmtupload_t.credit', 'f_bankstmtupload_t.balance', 'f_bank_account_hdr_t.bank_name', 'f_bank_account_lines_t.account_number')
            ->get();

        $this->data['result'] = $sql;

        $this->data['bankstmt_id'] = "";
        $this->data['bank_name'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");

        $this->data['account_no'] = $this->jcombo("f_bank_account_lines_t", "bank_account_line_id", "account_number", '');
        $this->data['supplieropt'] = $this->jqgridselect("m_supplier_t", "supplier_id", "supplier_name");
        $this->data['customeropt'] = $this->jqgridselect("m_customers_t", "customer_id", "customer_name");

        return view('bankstatementupload.view', $this->data);
    }

   public function getReceiptsData()
{
    $wh = '';

    // Handle search filters
    if (isset($_GET['_search']) && $_GET['_search'] == 'true') {
        $filters = $_GET['filters'] ?? '';
        $wh = $this->jqgridsearchnotab("v1", $filters);
    }

    $org      = \Session::get('organization');
    $loc      = \Session::get('location');
    $compy    = \Session::get('companyid');
    $bank_nam = \Session::get('bank_name');

    // -------------------------
    // DataTables parameters
    // -------------------------
    $start  = $_GET['start'] ?? 0;      // starting record index
    $length = $_GET['length'] ?? 10;    // page size
    $order  = $_GET['order'][0] ?? null;
    $columns = $_GET['columns'] ?? [];

    $order_by = '';
    if ($order) {
        $colIndex = $order['column'];
        $dir      = $order['dir'] ?? 'asc';
        $colName  = $columns[$colIndex]['data'] ?? 'receipt_id';
        $order_by = "ORDER BY $colName $dir";
    } else {
        $order_by = "ORDER BY receipt_id ASC";
    }

    // -------------------------
    // Main query (get ALL rows)
    // -------------------------
    $result = \DB::select("
        SELECT * FROM (
            SELECT 
                s_receipts_t.receipt_id,
                s_receipts_t.receipt_number,
                s_invoice_hdr_t.invoice_number,
                m_customers_t.customer_name,
                f_bank_account_hdr_t.bank_name,
                s_receipts_t.receipt_date,
                s_receipts_t.invoice_currency,
                (CASE  
                    WHEN s_receipts_t.exchangeamount != '0'
                    THEN s_receipts_t.exchangeamount
                    ELSE s_receipts_t.receipt_amount
                END) AS receipt_amount,
                s_receipts_t.cheque_no,
                s_receipts_t.receipt_type_id,
                s_receipts_t.paid_amount
            FROM s_receipts_t 
            LEFT JOIN m_customers_t 
                ON m_customers_t.customer_id = s_receipts_t.customer_id
            LEFT JOIN s_invoice_hdr_t 
                ON s_invoice_hdr_t.invoice_hdr_id = s_receipts_t.invoice_hdr_id
            LEFT JOIN f_bank_account_hdr_t 
                ON f_bank_account_hdr_t.bank_account_hdr_id = s_receipts_t.bank_id
            WHERE 1 = 1
              AND (s_receipts_t.stmtid = 0 OR s_receipts_t.stmtid IS NULL)
              AND (s_receipts_t.cheque_cancel_status IS NULL 
                   OR s_receipts_t.cheque_cancel_status = '')
              AND f_bank_account_hdr_t.bank_account_hdr_id = $bank_nam
              AND s_receipts_t.receipt_amount != '0'
              AND s_receipts_t.company_id = $compy
              AND s_receipts_t.location_id = $loc
        ) AS v1
        WHERE 1 = 1 $wh
        $order_by
    ");

    $recordsTotal    = count($result);
    $recordsFiltered = $recordsTotal;

    // -------------------------
    // Apply pagination (slice)
    // -------------------------
    //$data = array_slice($result, (int)$start, (int)$length);
    $data = $result;

    // -------------------------
    // Prepare DataTables response
    // -------------------------
    $response = [
        "draw"            => intval($_GET['draw'] ?? 1),
        "recordsTotal"    => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data"            => $data,
    ];

    return response()->json($response);
}


    public function getpoInvoiceData()
    {

        $wh = '';

        if ($_GET['_search'] == 'true') {
            $search_tables = array('m_supplier_t', 'p_po_hdr_t');
            $wh = $this->jqgridsearch('p_po_invoice_hdr_t', $_GET['filters'], $search_tables);
        }
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(po_invoice_id) AS count FROM p_po_invoice_hdr_t left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_po_invoice_hdr_t.`supplier_id`)
                        left join p_po_hdr_t on(
                        p_po_hdr_t.po_hdr_id=p_po_invoice_hdr_t.`po_number`) where 1=1  and p_po_invoice_hdr_t.balance_amount!=0 and  p_po_invoice_hdr_t.company_id=$compy and p_po_invoice_hdr_t.location_id=$loc $wh");
        $count = $result[0]->count;
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;
        $SQL = "SELECT
                        p_po_invoice_hdr_t.po_invoice_id,
                        p_po_invoice_hdr_t.bill_number,
                        p_po_invoice_hdr_t.invoice_date,
                        p_po_invoice_hdr_t.po_invoice_status,
                        p_po_hdr_t.po_number,
                        p_po_hdr_t.po_hdr_id,
                        p_po_invoice_hdr_t.po_date,
                        m_supplier_t.supplier_name
                        FROM  p_po_invoice_hdr_t 
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_po_invoice_hdr_t.`supplier_id`)
                        left join p_po_hdr_t on(
                        p_po_hdr_t.po_hdr_id=p_po_invoice_hdr_t.`po_number`) where 1=1  and p_po_invoice_hdr_t.balance_amount!=0 and p_po_invoice_hdr_t.company_id=$compy and p_po_invoice_hdr_t.location_id=$loc $wh ORDER BY $sidx $sord LIMIT $start , $limit";
        //          dd($SQL);
        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }

    public function viewstatement($bank_name = null, $account_no = null, $from_date = null, $to_date = null)
    {
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));
        Session::put('bank_name', $bank_name);
        $stmtdata = \DB::select("select 
                f_bankstmtupload_t.bankstmt_id,
                   f_bank_account_hdr_t.bank_name,
                   f_bank_account_lines_t.account_number,
                   f_bankstmtupload_t.cod,
                   f_bankstmtupload_t.date,
                   f_bankstmtupload_t.value_date,
                   f_bankstmtupload_t.chq_no,
                   f_bankstmtupload_t.narration,
                   f_bankstmtupload_t.debit,
				    f_bankstmtupload_t.status,
                    f_bankstmtupload_t.balance,
                   f_bankstmtupload_t.credit 
         from f_bankstmtupload_t
            left join f_bank_account_hdr_t on f_bank_account_hdr_t.bank_account_hdr_id=f_bankstmtupload_t.bank_id
            left join f_bank_account_lines_t on f_bank_account_lines_t.bank_account_hdr_id=f_bankstmtupload_t.account_no where 1=1 and
                   (f_bank_account_hdr_t.bank_account_hdr_id=$bank_name or f_bank_account_lines_t.bank_account_line_id=$account_no) and (f_bankstmtupload_t.date between '$from_date' and '$to_date') and f_bankstmtupload_t.status=0");
        //  dd($stmtdata);
        return json_encode($stmtdata);
        //  $this->data['data']=$stmtdata;
        // return view('bankstmtupload.view',$this->data); 
    }

    public function getstmtuploaddata()
    {

        $wh = '';
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $wh .= 'and f_bankstmtupload_t.company_id=' . $compy;


        if (isset($_GET['batchname'])) {
            if ($_GET['batchname'] != "") {
                $wh .= " and batch_name like '" . $_GET['batchname'] . "'";
            }
        }


        $SQL = "SELECT f_bankstmtupload_t.*,f_bank_account_hdr_t.bank_name,"
            . "f_bank_account_lines_t.account_number"
            . " from f_bankstmtupload_t "
            . "join f_bank_account_hdr_t on (f_bank_account_hdr_t.bank_account_hdr_id = f_bankstmtupload_t.bank_id) "
            . "left join f_bank_account_lines_t on (f_bank_account_lines_t.bank_account_line_id = f_bankstmtupload_t.account_no) where 1=1 $wh";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);


    }

    /* purpose:To Upload excel*/
    public function Uploadexcel(Request $request)
    {
        // dd($_POST);
        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        // dd($path,$extension);
        $data = array();
        $stmtdata = array();
        $return = 'stmtupload';
        if ($extension == "csv") {
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;

            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                if ($c > 0) {
                    // dd($filesop[0]);

                    if (strtoupper(trim($filesop[6])) == '')
                        $filesop[6] = 0;
                    if (strtoupper(trim($filesop[5])) == '')
                        $filesop[5] = 0;

                    $check_data = \DB::select("select * from f_bankstmtupload_t where bank_id='" . $_POST['bank_id'] . "' and account_no='" . $_POST['account_no'] . "' and value_date='" . date('Y-m-d', strtotime($filesop[1])) . "' and narration like '" . strtoupper(trim($filesop[3])) . "' and debit like'" . strtoupper(trim($filesop[5])) . "' and credit like '" . strtoupper(trim($filesop[6])) . "'");
                    //dd($check_data);
                    if (count($check_data) <= 0) {
                        $stmtdata[$c]['date'] = date('Y-m-d', strtotime($filesop[0]));
                        $stmtdata[$c]['value_date'] = date('Y-m-d', strtotime($filesop[1]));
                        $stmtdata[$c]['chq_no'] = strtoupper(trim($filesop[2]));
                        $stmtdata[$c]['narration'] = strtoupper(trim($filesop[3]));
                        $stmtdata[$c]['cod'] = strtoupper(trim($filesop[4]));
                        $stmtdata[$c]['debit'] = strtoupper(trim($filesop[5]));
                        $stmtdata[$c]['credit'] = strtoupper(trim($filesop[6]));
                        $stmtdata[$c]['balance'] = strtoupper(trim($filesop[7]));
                        $stmtdata[$c]['bank_id'] = $_POST['bank_id'];
                        $stmtdata[$c]['account_no'] = $_POST['account_no'];
                        $stmtdata[$c]['batch_status'] = "UPLOADED";
                        $stmtdata[$c]['batch_name'] = $_POST['batch_name'];
                        $stmtdata[$c]['batch_date'] = date('Y-m-d');
                        $stmtdata[$c]['company_id'] = \Session::get('companyid');
                    }
                    //dd($stmtdata);
                }
                $c = $c + 1;
            }

            $id = \DB::table('f_bankstmtupload_t')->insert($stmtdata);
            //  dd($id);    
        } else {

            $message = "Please upload an valid CSV file";
            // return Redirect::to($return)->with('messagetext',$message)->with('msgstatus','error');      
            return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }


        // return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');    
        return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));


    }
    /*end*/
    public function viewstatementdetailsindex(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

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

        $this->data['pageMethod'] = \Request::route()->getName();
        return view('bankstatementupload.viewdetails', $this->data);
    }


    public function moveStatement(Request $request)
    {
        DB::table('f_bankstmtupload_t')   
            ->where('bankstmt_id', $request->id)
            ->update([
                'status' => 1,   // moved
                'last_updated_by' => \Session::get('id'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return response()->json(['status' => true]);
    }


    public function getviewstatementdetails()
    {

        $wh = '';
        $wh1 = '';

        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');

        $groupname = \Session::get('groupname');

        $SQL = "select * from (select 
					f_bankstmtupload_t.bankstmt_id,
                  f_bank_account_hdr_t.bank_name,
                  f_bank_account_lines_t.account_number,
                  f_bankstmtupload_t.date,
                  f_bankstmtupload_t.value_date,
                  f_bankstmtupload_t.chq_no,
                  s_receipts_t.cheque_no,
                  f_bankstmtupload_t.narration,
                  f_bankstmtupload_t.credit as stmt_amount,
                  f_bankstmtupload_t.debit as table_find,
                  s_receipts_t.receipt_id as ref_id,
                  s_receipts_t.receipt_number as ref_no,
                  s_invoice_hdr_t.invoice_number as billref,
                  0 as supplier_name,
                  s_receipts_t.receipt_date as ref_date,
                  s_receipts_t.receipt_amount as ref_amount,
                  s_receipts_t.bank_date,
                  s_receipts_t.receipt_type_id as payment_type,
                  m_customers_t.customer_name,
                  0  as first_name 
                    from f_bankstmtupload_t
                left join f_bank_account_hdr_t on f_bank_account_hdr_t.bank_account_hdr_id=f_bankstmtupload_t.bank_id
                left join f_bank_account_lines_t on f_bank_account_lines_t.bank_account_hdr_id=f_bankstmtupload_t.account_no
                left join s_receipts_t on s_receipts_t.stmtid =f_bankstmtupload_t.bankstmt_id
                left join m_customers_t on(m_customers_t.customer_id=s_receipts_t.customer_id)
                left join s_invoice_hdr_t on(s_invoice_hdr_t.invoice_hdr_id=s_receipts_t.invoice_hdr_id)
                    where 1=1 and f_bankstmtupload_t.status=1 and f_bankstmtupload_t.debit=0
                    UNION ALL
                    select 
                  f_bankstmtupload_t.bankstmt_id,
                  f_bank_account_hdr_t.bank_name,
                  f_bank_account_lines_t.account_number,
                  f_bankstmtupload_t.date,
                  f_bankstmtupload_t.value_date,
                  f_bankstmtupload_t.chq_no,
                   p_payments_t.cheque_no,
                  f_bankstmtupload_t.narration,
                  f_bankstmtupload_t.debit as stmt_amount,
                  f_bankstmtupload_t.debit as table_find,
                    p_payments_t.payment_id as ref_id,
                p_payments_t.payment_number as ref_no,
                p_po_invoice_hdr_t.bill_number as billref,
                m_supplier_t.supplier_name,
                p_payments_t.payment_date as ref_date,
                p_payments_t.payment_amount as ref_amount,
                p_payments_t.bank_date,
                p_payments_t.payment_type_id as payment_type,
                m_customers_t.customer_name,
                hr_employee_t.first_name 
                    from f_bankstmtupload_t
                left join f_bank_account_hdr_t on f_bank_account_hdr_t.bank_account_hdr_id=f_bankstmtupload_t.bank_id
                left join f_bank_account_lines_t on f_bank_account_lines_t.bank_account_hdr_id=f_bankstmtupload_t.account_no
                left join p_payments_t on p_payments_t.stmtid =f_bankstmtupload_t.bankstmt_id
                left join m_supplier_t on(m_supplier_t.supplier_id=p_payments_t.supplier_id)
                left join m_customers_t on(m_customers_t.customer_id=p_payments_t.customer_id)
                left join hr_employee_t on(hr_employee_t.employee_id=p_payments_t.employee_id)
                left join f_expenses_t on(f_expenses_t.expense_id=p_payments_t.reference_id)
                left join p_po_invoice_hdr_t on(p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id)
                where 1=1 and f_bankstmtupload_t.status=1 and f_bankstmtupload_t.credit=0 $wh) as a1 where 1=1 $wh1 ORDER BY a1.bankstmt_id DESC";


        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }

    public function unmatchedstatment($id = null, Request $request)
    {
        // dd($_GET['table']);
        $statment_id = $id;
        $amount = $_GET['table'];
        if ($amount == 0) {
            $update = \DB::update("update s_receipts_t Set stmtid='0',bank_date='' where stmtid='$statment_id' ");

        } else {
            $update = \DB::update("update p_payments_t Set stmtid='0',bank_date='' where stmtid='$statment_id' ");
        }
        $stmtupdate = \DB::update("update f_bankstmtupload_t Set status='0' where bankstmt_id='$statment_id' ");
        return 1;
    }

    public function brspaymentrpt()
    {

        return view('bankstatementupload.brsdetailsrpt', $this->data);
    }

    public function getbrssummaryreport(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $wh1 = " and p_payments_t.payment_date between '$start_date' and '$end_date' and (p_payments_t.payment_type_id != 'CASH' and p_payments_t.payment_type_id != 'IMPREST')";
        $wh2 = " and s_receipts_t.receipt_date between '$start_date' and '$end_date' and s_receipts_t.receipt_type_id != 'CASH'";


        $overall_datas = \DB::select("select * from (select v1.*,f_bank_account_hdr_t.bank_name,f_bank_account_lines_t.account_number from (SELECT p_payments_t.payment_id,p_payments_t.payment_number,p_payments_t.payment_date,p_payments_t.payment_amount AS p_payment_amount,
            0 AS r_payment_amount,p_payments_t.payment_source,
(SELECT concat(m_supplier_t.supplier_number,'-',m_supplier_t.supplier_name) from m_supplier_t where m_supplier_t.supplier_id=p_payments_t.supplier_id) as supplier_name, 
(SELECT p_po_invoice_hdr_t.bill_number from p_po_invoice_hdr_t where p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id) as invoice_no,
(SELECT p_po_hdr_t.po_number from p_po_hdr_t where p_po_hdr_t.po_hdr_id=p_payments_t.po_hdr_id) as po_no,
(SELECT concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) from hr_employee_t where hr_employee_t.employee_id=p_payments_t.employee_id) as employee_name,
f_bank.bank_name as from_bank_name,
f_bankstmtupload_t.bankstmt_id,
f_bankstmtupload_t.bank_id,
f_bankstmtupload_t.account_no,
f_bankstmtupload_t.date,
f_bankstmtupload_t.mode,
f_bankstmtupload_t.particulars,
f_bankstmtupload_t.narration,
p_payments_t.payment_type_id as tmode
FROM `p_payments_t` 
left join f_bankstmtupload_t on f_bankstmtupload_t.bankstmt_id=p_payments_t.stmtid
left join f_bank_account_hdr_t as f_bank on f_bank.bank_account_hdr_id = p_payments_t.bank_id
where 1=1 $wh1
UNION ALL 
SELECT s_receipts_t.receipt_id as payment_id,s_receipts_t.receipt_number as payment_number,s_receipts_t.receipt_date as payment_date,0 AS p_payment_amount,        
    s_receipts_t.receipt_amount AS r_payment_amount,s_receipts_t.receipt_reference as payment_source,
(SELECT concat(m_customers_t.customer_number,'-',m_customers_t.customer_name) from m_customers_t where m_customers_t.customer_id=s_receipts_t.customer_id) as supplier_name,
(SELECT s_invoice_hdr_t.invoice_number from s_invoice_hdr_t where s_invoice_hdr_t.invoice_hdr_id=s_receipts_t.invoice_hdr_id) as invoice_no,
(SELECT s_salesorder_hdr_t.sales_order_no from s_salesorder_hdr_t where s_salesorder_hdr_t.sales_hdr_id=s_receipts_t.sales_hdr_id) as po_no,
'' as employee_name,
f_r_bank.bank_name as from_bank_name,
f_bankstmtupload_t.bankstmt_id,
f_bankstmtupload_t.bank_id,
f_bankstmtupload_t.account_no,
f_bankstmtupload_t.date,
f_bankstmtupload_t.mode,
f_bankstmtupload_t.particulars,
f_bankstmtupload_t.narration,
s_receipts_t.receipt_type_id as tmode
from s_receipts_t 
left join f_bankstmtupload_t on f_bankstmtupload_t.bankstmt_id=s_receipts_t.stmtid 
left join f_bank_account_hdr_t as f_r_bank on f_r_bank.bank_account_hdr_id = s_receipts_t.bank_id
WHERE 1=1 $wh2) v1 
LEFT join f_bank_account_hdr_t on f_bank_account_hdr_t.bank_account_hdr_id=v1.bank_id
LEFT join f_bank_account_lines_t on f_bank_account_lines_t.bank_account_line_id=v1.account_no
where 1=1)v2");

        // $results = \DB::select($SQL, [$start_date, $end_date]);

        return response()->json(['data' => $overall_datas]);

    }

    public function getbrsmappedreport(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $wh1 = " and p_payments_t.payment_date between '$start_date' and '$end_date' and f_bankstmtupload_t.bankstmt_id is not null  and (p_payments_t.payment_type_id != 'CASH' and p_payments_t.payment_type_id != 'IMPREST')";
        $wh2 = " and s_receipts_t.receipt_date between '$start_date' and '$end_date' and f_bankstmtupload_t.bankstmt_id is not null  and s_receipts_t.receipt_type_id != 'CASH'";

        $overall_datas = \DB::select("select * from (select v1.*,f_bank_account_hdr_t.bank_name,f_bank_account_lines_t.account_number from (SELECT p_payments_t.payment_id,p_payments_t.payment_number,p_payments_t.payment_date,p_payments_t.payment_amount AS p_payment_amount,
            0 AS r_payment_amount,p_payments_t.payment_source,
(SELECT concat(m_supplier_t.supplier_number,'-',m_supplier_t.supplier_name) from m_supplier_t where m_supplier_t.supplier_id=p_payments_t.supplier_id) as supplier_name, 
(SELECT p_po_invoice_hdr_t.bill_number from p_po_invoice_hdr_t where p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id) as invoice_no,
(SELECT p_po_hdr_t.po_number from p_po_hdr_t where p_po_hdr_t.po_hdr_id=p_payments_t.po_hdr_id) as po_no,
(SELECT concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) from hr_employee_t where hr_employee_t.employee_id=p_payments_t.employee_id) as employee_name,
f_bank.bank_name as from_bank_name,
f_bankstmtupload_t.bankstmt_id,
f_bankstmtupload_t.bank_id,
f_bankstmtupload_t.account_no,
f_bankstmtupload_t.date,
f_bankstmtupload_t.mode,
f_bankstmtupload_t.particulars,
f_bankstmtupload_t.narration,
p_payments_t.payment_type_id as tmode
FROM `p_payments_t` 
left join f_bankstmtupload_t on f_bankstmtupload_t.bankstmt_id=p_payments_t.stmtid
left join f_bank_account_hdr_t as f_bank on f_bank.bank_account_hdr_id = p_payments_t.bank_id
where 1=1 $wh1
UNION ALL 
SELECT s_receipts_t.receipt_id as payment_id,s_receipts_t.receipt_number as payment_number,s_receipts_t.receipt_date as payment_date,0 AS p_payment_amount,        
    s_receipts_t.receipt_amount AS r_payment_amount,s_receipts_t.receipt_reference as payment_source,
(SELECT concat(m_customers_t.customer_number,'-',m_customers_t.customer_name) from m_customers_t where m_customers_t.customer_id=s_receipts_t.customer_id) as supplier_name,
(SELECT s_invoice_hdr_t.invoice_number from s_invoice_hdr_t where s_invoice_hdr_t.invoice_hdr_id=s_receipts_t.invoice_hdr_id) as invoice_no,
(SELECT s_salesorder_hdr_t.sales_order_no from s_salesorder_hdr_t where s_salesorder_hdr_t.sales_hdr_id=s_receipts_t.sales_hdr_id) as po_no,
'' as employee_name,
f_r_bank.bank_name as from_bank_name,
f_bankstmtupload_t.bankstmt_id,
f_bankstmtupload_t.bank_id,
f_bankstmtupload_t.account_no,
f_bankstmtupload_t.date,
f_bankstmtupload_t.mode,
f_bankstmtupload_t.particulars,
f_bankstmtupload_t.narration,
s_receipts_t.receipt_type_id as tmode
from s_receipts_t 
left join f_bankstmtupload_t on f_bankstmtupload_t.bankstmt_id=s_receipts_t.stmtid 
left join f_bank_account_hdr_t as f_r_bank on f_r_bank.bank_account_hdr_id = s_receipts_t.bank_id
WHERE 1=1 $wh2) v1 
LEFT join f_bank_account_hdr_t on f_bank_account_hdr_t.bank_account_hdr_id=v1.bank_id
LEFT join f_bank_account_lines_t on f_bank_account_lines_t.bank_account_line_id=v1.account_no
where 1=1 )v2");

        return response()->json(['data' => $overall_datas]);

    }

    public function getbrsunmapped(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $wh1 = " and p_payments_t.payment_date between '$start_date' and '$end_date'  and (p_payments_t.payment_type_id != 'CASH' and p_payments_t.payment_type_id != 'IMPREST')";
        $wh2 = " and s_receipts_t.receipt_date between '$start_date' and '$end_date'  and s_receipts_t.receipt_type_id != 'CASH'";

        $overall_datas = \DB::select("select * from (select v1.*,f_bank_account_hdr_t.bank_name,f_bank_account_lines_t.account_number 
 from (SELECT p_payments_t.payment_id,p_payments_t.payment_number,p_payments_t.payment_date,p_payments_t.payment_amount AS p_payment_amount,
            0 AS r_payment_amount,p_payments_t.payment_source,p_payments_t.cancel_status,
(SELECT concat(m_supplier_t.supplier_number,'-',m_supplier_t.supplier_name) from m_supplier_t where m_supplier_t.supplier_id=p_payments_t.supplier_id) as supplier_name, 
(SELECT p_po_invoice_hdr_t.bill_number from p_po_invoice_hdr_t where p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id) as invoice_no,
(SELECT p_po_hdr_t.po_number from p_po_hdr_t where p_po_hdr_t.po_hdr_id=p_payments_t.po_hdr_id) as po_no,
(SELECT concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) from hr_employee_t where hr_employee_t.employee_id=p_payments_t.employee_id) as employee_name,
f_bank.bank_name as from_bank_name,
f_bankstmtupload_t.bankstmt_id,
f_bankstmtupload_t.bank_id,
f_bankstmtupload_t.account_no,
f_bankstmtupload_t.date,
f_bankstmtupload_t.mode,
f_bankstmtupload_t.particulars,
f_bankstmtupload_t.narration,
p_payments_t.payment_type_id as tmode
FROM `p_payments_t` 
left join f_bankstmtupload_t on f_bankstmtupload_t.bankstmt_id=p_payments_t.stmtid 
left join f_bank_account_hdr_t as f_bank on f_bank.bank_account_hdr_id = p_payments_t.bank_id
where 1=1 $wh1
UNION ALL 
SELECT s_receipts_t.receipt_id as payment_id,s_receipts_t.receipt_number as payment_number,s_receipts_t.receipt_date as payment_date,0 AS p_payment_amount,        
    s_receipts_t.receipt_amount AS r_payment_amount,s_receipts_t.receipt_reference as payment_source,s_receipts_t.cheque_cancel_status as cancel_status,
(SELECT concat(m_customers_t.customer_number,'-',m_customers_t.customer_name) from m_customers_t where m_customers_t.customer_id=s_receipts_t.customer_id) as supplier_name,
(SELECT s_invoice_hdr_t.invoice_number from s_invoice_hdr_t where s_invoice_hdr_t.invoice_hdr_id=s_receipts_t.invoice_hdr_id) as invoice_no,
(SELECT s_salesorder_hdr_t.sales_order_no from s_salesorder_hdr_t where s_salesorder_hdr_t.sales_hdr_id=s_receipts_t.sales_hdr_id) as po_no,
'' as employee_name,
f_r_bank.bank_name as from_bank_name,
f_bankstmtupload_t.bankstmt_id,
f_bankstmtupload_t.bank_id,
f_bankstmtupload_t.account_no,
f_bankstmtupload_t.date,
f_bankstmtupload_t.mode,
f_bankstmtupload_t.particulars,
f_bankstmtupload_t.narration,
s_receipts_t.receipt_type_id as tmode
from s_receipts_t 
left join f_bankstmtupload_t on f_bankstmtupload_t.bankstmt_id=s_receipts_t.stmtid 
left join f_bank_account_hdr_t as f_r_bank on f_r_bank.bank_account_hdr_id = s_receipts_t.bank_id
WHERE 1=1 $wh2) v1 
LEFT join f_bank_account_hdr_t on f_bank_account_hdr_t.bank_account_hdr_id=v1.bank_id
LEFT join f_bank_account_lines_t on f_bank_account_lines_t.bank_account_line_id=v1.account_no
where 1=1 ) v1 where 1=1 and v1.bankstmt_id is null");

        return response()->json(['data' => $overall_datas]);

    }


    public function getbrssummarydeatilsrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $wh1 = " and p_payments_t.payment_date between '$start_date' and '$end_date' and (p_payments_t.payment_type_id != 'CASH' and p_payments_t.payment_type_id != 'IMPREST')";
        $wh2 = " and s_receipts_t.receipt_date between '$start_date' and '$end_date' and s_receipts_t.receipt_type_id != 'CASH'";

        $overall_datas = \DB::select("SELECT
    *
FROM (
    SELECT
        COUNT(vv) AS payment_cout,
        SUM(paycount) AS pay_count,
        SUM(recp_cout) AS recpt_count,
        SUM(mapstmtid) AS brs,
        SUM(ummapstmtid) AS unbrs,
        SUM(IF(record_type = 'payment' AND mapstmtid = 1, 1, 0)) AS brs_pay_count,
        SUM(IF(record_type = 'receipt' AND mapstmtid = 1, 1, 0)) AS brs_receipt_count,
        SUM(IF(record_type = 'payment' AND ummapstmtid = 1, 1, 0)) AS unbrs_pay_count,
        SUM(IF(record_type = 'receipt' AND ummapstmtid = 1, 1, 0)) AS unbrs_receipt_count,
        m,
        Y
    FROM (
        SELECT
            (p_payments_t.payment_id) AS vv,
            '1' AS paycount,
            '0' AS recp_cout,
            'payment' AS record_type,
            DATE_FORMAT(p_payments_t.payment_date, '%m') AS m,
            DATE_FORMAT(p_payments_t.payment_date, '%Y') AS Y,
            IF(p_payments_t.stmtid != 0, 1, 0) AS mapstmtid,
            IF(p_payments_t.stmtid != 0, 0, 1) AS ummapstmtid
        FROM
            p_payments_t
        WHERE
            1 = 1
            $wh1
        UNION ALL
        SELECT
            (s_receipts_t.receipt_id) AS vv,
            '0' AS paycount,
            '1' AS recp_cout,
            'receipt' AS record_type,
            DATE_FORMAT(s_receipts_t.receipt_date, '%m') AS m,
            DATE_FORMAT(s_receipts_t.receipt_date, '%Y') AS Y,
            IF(s_receipts_t.stmtid != 0, 1, 0) AS mapstmtid,
            IF(s_receipts_t.stmtid != 0, 0, 1) AS ummapstmtid
        FROM
            s_receipts_t
        WHERE
            1 = 1
            $wh2
    ) v2
    WHERE 1 = 1
    GROUP BY m, Y
) v1");

        return response()->json(['data' => $overall_datas]);

    }

}
