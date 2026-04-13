<?php

namespace App\Http\Controllers;

use App\Receiptdetails;
use Illuminate\Http\Request;
use DB;
use yajra\datatables\datatables;

class ReceiptdetailsController extends Controller
{
  public $module = "receiptdetails";
  public function __construct()
  {
    $this->data = array();
    $this->data = array();
    $this->table = "s_receipts_t";
    $this->pageModule = "receiptdetails";
    $this->model = new Receiptdetails();
    $this->model = new Receiptdetails;
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['pageFormtype'] = 'ajax';
    $this->table = "s_receipts_t";
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

    $this->data['customeropt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
    $this->data['invoiceno'] = $this->jqgridselect('s_invoice_hdr_t', 'invoice_hdr_id', 'invoice_number');
    $table = \DB::table('s_receipts_t')->get();
    $this->data['datas'] = $table;
    $this->data['pageMethod'] = "receiptsindex";
    return view('receiptdetails.table', $this->data);
  }

  public function receiptindex(Request $request)
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

    $this->data['customeropt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
    $this->data['invoiceno'] = $this->jqgridselect('s_invoice_hdr_t', 'invoice_hdr_id', 'invoice_number');
    $table = \DB::table('s_receipts_t')->get();
    $this->data['datas'] = $table;
    $this->data['pageMethod'] = "receiptsindex";

    return view('receiptdetails.receipttable', $this->data);

  }

  public function getreceiptgridData()
  {

    $wh = '';
    $search_table = array();

    $depart = \Session::get('groupname');

    $grid_date = \Session::get('griddate');
    $gridenddate = \Session::get('gridenddate');
    $compy = \Session::get('companyid');
    $loc = \Session::get('location');
    $tbl_name = 's_receipts_t';
    $date_col = 'receipt_date';

    if ($depart == '1' || $depart == 'Admin' || $depart == '4') {
      $wh .= " and  $tbl_name.company_id=" . $compy;

    } elseif ($depart == "14") {
      $emp_id = \Session::get('emp_id');
      $cus_id = \DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");

      $wh .= " and s_receipts_t.customer_id in (" . $cus_id[0]->dis . ")";
    } else {

      $wh .= " and ( ( $tbl_name.$date_col < '$grid_date') or  ( $tbl_name.$date_col BETWEEN '$grid_date' and '$gridenddate' ) )  ";
      $wh .= "and  $tbl_name.company_id=" . $compy . " and $tbl_name.location_id=" . $loc;
    }


    $SQL = "SELECT 
                s_receipts_t.receipt_id,
                if(s_receipts_t.receipt_source!='',s_receipts_t.receipt_source,'INVOICE') as receipt_source,
                s_receipts_t.receipt_number,
                s_receipts_t.invoice_hdr_id,
                s_invoice_hdr_t.invoice_number,
                FORMAT(s_invoice_hdr_t.invoice_grand_total,2) as invoice_grand_total,
                m_customers_t.customer_name,
                f_bank_account_hdr_t.bank_name,
                s_receipts_t.receipt_date,
                s_receipts_t.receipt_amount,
		            s_receipts_t.receipt_type_id,
                s_receipts_t.cheque_no,
                s_receipts_t.cheque_date,
                s_receipts_t.remarks,
                s_receipts_t.bank_date,
                FORMAT(s_receipts_t.paid_amount,2) as paid_amount,
                s_receipts_t.cheque_cancel_status,
                s_receipts_t.cheque_bounce_status,
                 FORMAT(s_receipts_t.balance_amount,2) as balance_amount,
                 f_bankstmtupload_t.narration
                FROM s_receipts_t 
                left join m_customers_t on(m_customers_t.customer_id=s_receipts_t.customer_id)
                left join s_invoice_hdr_t on(s_invoice_hdr_t.invoice_hdr_id=s_receipts_t.invoice_hdr_id)
                left join f_bank_account_hdr_t on(f_bank_account_hdr_t.bank_account_hdr_id=s_receipts_t.bank_id)
                LEFT JOIN f_bankstmtupload_t ON f_bankstmtupload_t.bankstmt_id = s_receipts_t.stmtid
                where 1=1 $wh and s_receipts_t.company_id=$compy and s_receipts_t.location_id=$loc";

    $result = \DB::select($SQL);
    $inv_no = '';
    foreach ($result as $k => $v) {
      $inv_num = $v->invoice_hdr_id;
      $inv_num = rtrim($inv_num, ",");
      if ($inv_num != null) {
        $inv_numb = \DB::select("select invoice_number from s_invoice_hdr_t where invoice_hdr_id in ($inv_num)");

        $inv = "";
        foreach ($inv_numb as $pk => $pv) {
          $inv .= $pv->invoice_number . ",";
        }
        $inv_number = rtrim($inv, ",");
        $result[$k]->invoice_number = $inv_number;
      }

      return DataTables::of($result)->make(true);

    }

  }

  public function directreceiptcreate()
  {

    $this->data['customeropt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
    $table = \DB::table('s_receipts_t')->get();
    $this->data['row'] = (object) array();
    $this->data['datas'] = $table;
    $this->data['row']->receipt_id = "";
    $this->data['row']->receipt_number = "";
    $this->data['row']->receipt_reference = "";

    $this->data['row']->cheque_no = "";
    $this->data['row']->remarks = "";
    $this->data['row']->receipt_date = date('Y-m-d');

    $payamt = $this->data['row']->receipt_status = "";
    $this->data['row']->receipt_amount = "";

    $this->data['row']->supplier_bank_id = "";
    $this->data['row']->supplier_account_no = "";
    $this->data['row']->receipt_type_id = "";
    $this->data['row']->customer_account_name = "";
    $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', '');
    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', '');
    //$this->data['direct_accountcodeid'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
    $this->data['direct_accountcodeid'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');
    $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
    //$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
    $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');

    $this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
    $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', '');
    $this->data['pageModule'] = "receiptsindex";

    return view('receiptdetails.directreceiptform', $this->data);
  }
  public function directreceiptsave(Request $request)
  {

    $id = '';
    $form = $request->all();
    $dataupload = "";
    $form = $request->except([
      '_token',
      'form_config',
      'form_data_json',
      'savestatus',
      'submit_type',
      'choosefile',
      'existing_file',
      'source_type_id',
      'employee_id',
      'supplier_id',
    ]);
    // Normalize "bulk_" keys once
    $form = $this->normalizeLineFormKeys($form);

    // Build header + lines
    $data = $this->validatePost($form, $this->table, 'header');
    /*karthigaa Purpose for Auto Number*/
    if ($_POST['receipt_number'] == "") {
      $seqno = $this->Seqnoe('RCPT-', 's_receipts_t', '', 'receipt_count');
      $data['receipt_number'] = $seqno[0];
      $data['receipt_count'] = $seqno[1];
    } else {
      $seqno[0] = $_POST['receipt_number'];
    }
    /*End*/
    //  dd($data);       
    \DB::beginTransaction();
    try {
      $data['receipt_source'] = 'DIRECTRECEIPT';
      $data['receipt_date'] = !empty($_POST['receipt_date']) ? date('Y-m-d', strtotime($_POST['receipt_date'])) : null;
      $id = $this->model->insertRow($data);

      $receipt = $_POST['receipt_amount'];


      /**ajith Purpose for Journal  Insert**/
      $receiptno = "DIRECTRECEIPTS-" . $data['receipt_number'];
      $paydate = !empty($_POST['receipt_date']) ? date('Y-m-d', strtotime($_POST['receipt_date'])) : null;;
      $org = \Session::get('organization');
      $loc = \Session::get('location');
      $compy = \Session::get('companyid');
      //Journal Header Insert
      $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$receiptno','DIRECTRECEIPTS','$paydate','$id','APPROVED','$compy','$loc','$org')");
      $jid = DB::getPdo()->lastInsertId();
      $tkey = 0;

      $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
      $journal_lines_data[$tkey]['journal_date'] = $paydate;
      $journal_lines_data[$tkey]['reference_source'] = "";
      $journal_lines_data[$tkey]['reference_id'] = '';
      $journal_lines_data[$tkey]['account_id'] = $_POST['account_code_id'];

      $journal_lines_data[$tkey]['debit_amount'] = $_POST['receipt_amount'];
      $journal_lines_data[$tkey]['credit_amount'] = '';
      $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
      $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
      $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
      $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
      $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
      ;
      $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
      $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
      $tkey++;
      $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
      $journal_lines_data[$tkey]['journal_date'] = $paydate;


      $journal_lines_data[$tkey]['reference_source'] = "";
      $journal_lines_data[$tkey]['reference_id'] = '';

      if ($_POST['employee_id'] != '') {
        $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
        $journal_lines_data[$tkey]['reference_id'] = $_POST['employee_id'];
      } else if ($_POST['supplier_id'] != '') {
        $journal_lines_data[$tkey]['reference_source'] = "SUPPLIER";
        $journal_lines_data[$tkey]['reference_id'] = $_POST['supplier_id'];
      } else if ($_POST['customer_id'] != '') {
        $journal_lines_data[$tkey]['reference_source'] = "CUSTOMER";
        $journal_lines_data[$tkey]['reference_id'] = $_POST['customer_id'];
      }

      $journal_lines_data[$tkey]['account_id'] = $_POST['direct_accountcodeid'];

      $journal_lines_data[$tkey]['debit_amount'] = '';
      $journal_lines_data[$tkey]['credit_amount'] = $_POST['receipt_amount'];
      $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
      $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
      $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
      $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
      $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
      ;
      $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
      $journal_lines_data[$tkey]['company_id'] = \Session::get('location');


      \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);


      \DB::commit();
      return response()->json(array('status' => 'success', 'message' => 'Receipt Saved', 'id' => $id));
    } catch (\Illuminate\Database\QueryException $e) {
      $message = explode('(', $e->getMessage());
      $dbCode = rtrim($message[0], ']');
      $dbCode = trim($dbCode, '[');
      dd($dbCode);
      \DB::rollback();
      return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
    }

  }

  /* Purpose for Receipt Details pqgrid*/
  public function getReceiptdetailssData(Request $request)
  {

    $compy = \Session::get('companyid');
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
               SELECT s_receipts_t.receipt_id,
                s_receipts_t.receipt_number,
                s_receipts_t.receipt_date,
                s_receipts_t.receipt_status,
                s_receipts_t.receipt_type_id,
                s_receipts_t.receipt_amount,
		s_receipts_t.invoice_amount,
                s_receipts_t.invoice_hdr_id,
                s_invoice_hdr_t.invoice_number,
                m_customers_t.customer_name
                FROM s_receipts_t 
                left join m_customers_t on(m_customers_t.customer_id=s_receipts_t.customer_id)
                left join s_invoice_hdr_t on(s_invoice_hdr_t.invoice_hdr_id=s_receipts_t.invoice_hdr_id)
                where 1=1 AND s_receipts_t.receipt_date BETWEEN ? AND ? and s_receipts_t.company_id=$compy) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return DataTables::of($results)->make(true);
  }

  /*END*/
  public function getinvno($id = null)
  {
    $sql = \DB::select("select invoice_hdr_id from s_receipts_t where receipt_id='$id'");
    $product = '';
    foreach ($sql as $key => $val) {
      $invoice .= $val->invoice_hdr_id . ",";

    }
    $invoice1 = rtrim($invoice, ",");
    $invoicedata = $this->data['product_id'] = $this->jcustomselect('s_invoice_hdr_t', 'invoice_hdr_id', 'invoice_number', '', 'and invoice_hdr_id in ' . "(" . $invoice1 . ")");
    return $invoicedata;
  }

  function getreceivablesreport($id = null)
  {
    $this->data['data'] = $row = \DB::table('s_receipts_t')->select('s_receipts_t.*', 's_invoice_hdr_t.*', 's_invoice_hdr_t.invoice_hdr_id', 'm_customers_t.customer_id', 'm_customers_t.customer_name')
      ->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_receipts_t.customer_id')
      ->leftJoin('s_invoice_hdr_t', 's_invoice_hdr_t.invoice_hdr_id', '=', 's_receipts_t.invoice_hdr_id')
      ->where('receipt_id', $id)->get();
    //dd($row);
    $duedate = $row[0]->due_date;
    $current_date = date('Y-m-d');
    //dd($duedate);
    $date = strtotime($duedate);
    $currentdate = strtotime($current_date);
    $datediff = $currentdate - $date;
    $due_date = round($datediff / (60 * 60 * 24));
    //dd($due_date);
    $this->data['duedate'] = $due_date;

    return view('receiptdetails.receivablereport', $this->data);
  }

  /*  purpose: to get voucher details */
  public function getReceiptvoucher($id = null)
  {
    require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

    $sql = \DB::Select('select s_receipts_t.*,s_receipts_t.bank_name as cus_bank_name,f_bank_account_hdr_t.bank_name,f_bank_account_lines_t.account_number,f_bank_account_lines_t.branch_name,f_bank_account_lines_t.name_in_account,m_customers_t.customer_name,comp_f_bank_account_hdr_t.bank_name as com_bank_name,com_f_bank_account_lines_t.account_number as com_account_number,com_f_bank_account_lines_t.branch_name as com_branch_name,com_f_bank_account_lines_t.name_in_account as com_name_in_account
                            from s_receipts_t 
                            left join  m_customers_t on (m_customers_t.customer_id=s_receipts_t.customer_id)
                            left join f_bank_account_hdr_t on (f_bank_account_hdr_t.customerid=s_receipts_t.customer_id) and f_bank_account_hdr_t.active ="Yes"
                            left join f_bank_account_lines_t on (f_bank_account_lines_t.bank_account_hdr_id=f_bank_account_hdr_t.bank_account_hdr_id)
                            left join f_bank_account_hdr_t as comp_f_bank_account_hdr_t on (comp_f_bank_account_hdr_t.bank_account_hdr_id=s_receipts_t.bank_id)
                            left join f_bank_account_lines_t as com_f_bank_account_lines_t on (com_f_bank_account_lines_t.bank_account_hdr_id=comp_f_bank_account_hdr_t.bank_account_hdr_id)
                            where receipt_id=' . $id);
    // dd($sql);
    $arr = array();
    foreach ($sql as $key => $value) {
      $arr[$key]['cheque_no'] = $value->cheque_no;
      $arr[$key]['payment_amount'] = $value->receipt_amount;
      //            $arr[$key]['batch_total'] = $value->batch_total;
    }
    //$this->data['payment_number'] = $sql[0]->receipt_number;
    $this->data['payment_name'] = $sql[0]->receipt_type_id;
    $this->data['bank_name'] = $sql[0]->bank_name;
    $this->data['payment_reference'] = $sql[0]->receipt_reference;
    $this->data['cheque_no'] = $sql[0]->cheque_no;
    $this->data['cheque_date'] = $sql[0]->cheque_date;
    $this->data['customer_name'] = $sql[0]->customer_name;
    $this->data['payment_amount'] = $sql[0]->receipt_amount;
    $this->data['bank_name'] = $sql[0]->bank_name;
    $this->data['cus_bank_name'] = $sql[0]->cus_bank_name;
    $this->data['branch_name'] = $sql[0]->branch_name;
    $this->data['account_number'] = $sql[0]->account_number;
    $this->data['name_in_account'] = $sql[0]->name_in_account;
    $this->data['com_bank_name'] = $sql[0]->com_bank_name;
    $this->data['com_branch_name'] = $sql[0]->com_branch_name;
    $this->data['com_account_number'] = $sql[0]->com_account_number;
    $this->data['com_name_in_account'] = $sql[0]->com_name_in_account;
    $this->data['payment_date'] = date("d-m-Y", strtotime($sql[0]->receipt_date));
    //        $this->data['batch_total'] = $sql[0]->batch_total;
    $company = \Session::get('companyid');
    $company_name = \DB::select("select * from m_company_t where company_id='$company'");
    $this->data['company_name'] = $company_name[0]->company_name;
    $address = $this->getLocationwiseaddress();
    /*      if ($address != 0) {
              $location_name = @$address[0]->location_name;
              $this->data['location_name'] = $location_name;
              $address1 = $address[0]->address;
              $this->data['address1'] = $address1;
              $street = $address[0]->street_name;
              $this->data['street'] = $street;
              $location = $address[0]->location_name;
              $this->data['location'] = $location;
              $area = $address[0]->area;
              $this->data['area'] = $area;
              $this->data['city'] = $address[0]->city_name;
              $this->data['state'] = $address[0]->state_name;
              $this->data['country'] = $address[0]->country_name;
          }*/
    $this->data['result'] = $arr;
    //        dd($this->data);
    return view('receiptdetails.voucher', $this->data);
  }

  /*Karthigaa Purpose for getting Location Details for Payment Voucher*/
  function getLocationwiseaddress()
  {
    $sql = array();
    $location = \Session::get('location');
    $sql = \DB::SELECT("SELECT m_location_t.*,m_countries_t.country_name,m_states_t.state_name,m_cities_t.city_name
                            FROM `m_location_t` 
                            left join m_countries_t on (m_countries_t.country_id=m_location_t.country_id)
                            left join m_states_t on (m_states_t.state_id=m_location_t.state_id)
                            left join m_cities_t on (m_cities_t.city_id=m_location_t.city_id)
                            WHERE m_location_t.location_id='$location'");
    if (!empty($sql)) {
      return $sql;
    } else {
      return 0;
    }
  }

  public function show($id = null)
  {
    $vdata = \DB::table('s_receipts_t')->select('s_receipts_t.*', 's_invoice_hdr_t.invoice_number', 'm_customers_t.customer_name', 'f_account_structure_t.concatenated_segments', 'tb_users.username')
      ->leftjoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_receipts_t.customer_id')
      ->leftjoin('f_account_structure_t', 'f_account_structure_t.f_account_structure_id', '=', 's_receipts_t.account_code_id')
      ->leftJoin('s_invoice_hdr_t', 's_invoice_hdr_t.invoice_hdr_id', '=', 's_receipts_t.invoice_hdr_id')
      ->leftjoin('tb_users', 'tb_users.id', '=', 's_receipts_t.created_by')
      ->where('receipt_id', $id)->get();

    $this->data['invoice_number'] = $vdata[0]->invoice_number;
    $this->data['receipt_status'] = $vdata[0]->receipt_status;
    $this->data['paid_amount'] = $vdata[0]->paid_amount;
    $this->data['remarks'] = $vdata[0]->remarks;
    $this->data['receipt_number'] = $vdata[0]->receipt_number;
    $this->data['receipt_date'] = $vdata[0]->receipt_date;
    // $this->data['receipt_date'] =  date(\Session::get('p_date_format'), strtotime($recpt_date));
    $this->data['invoice_amount'] = $vdata[0]->invoice_amount;
    $this->data['receipt_amount'] = $vdata[0]->receipt_amount;
    $this->data['balance_amount'] = $vdata[0]->balance_amount;
    $this->data['receipt_type_id'] = $vdata[0]->receipt_type_id;
    $this->data['receipt_reference'] = $vdata[0]->receipt_reference;
    $this->data['customer_name'] = $vdata[0]->customer_name;
    $this->data['concatenated_segments'] = $vdata[0]->concatenated_segments;
    $this->data['cheque_no'] = $vdata[0]->cheque_no;
    $this->data['account_no'] = $vdata[0]->account_no;
    $this->data['username'] = $vdata[0]->username;

    return view('receiptdetails.view', $this->data);

  }
  // Ajith :purpose of cheque cancellation   
  public function chequecancellation()
  {

    $sql = \DB::select("select receipt_reference from s_receipts_t where receipt_id='" . $_GET['id'] . "'");
    if (isset($sql)) {
      if ($sql[0]->receipt_reference != '') {
        $data['receipt_reference'] = $sql[0]->receipt_reference;
      }
    }
    return $data;

  }

  public function getReceiptcanclconfirm($id = null)
  {

    $chequecancel = \DB::update("update s_receipts_t set cheque_cancel_status='cancelled' where receipt_id='$id' ");

    $sql = \DB::select("SELECT f_journal_entry_t.*,
                         f_journal_entry_lines_t.*, 
                         s_salesorder_hdr_t.advance_amount as so_ad_pay,
                         s_salesorder_hdr_t.balance_amount as so_bal_amt,
                         s_receipts_t.receipt_id,
                         s_receipts_t.sales_hdr_id,
                         s_receipts_t.invoice_hdr_id,
                         s_receipts_t.balance_amount,
                         s_receipts_t.paid_amount,
                         s_receipts_t.receipt_amount,
                         s_receipts_t.receipt_id,
                         s_receipts_t.customer_id
        FROM f_journal_entry_lines_t 
        left join f_journal_entry_t on (f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id)
        LEFT JOIN s_receipts_t on (s_receipts_t.receipt_id = f_journal_entry_t.journal_reference )
        left join s_invoice_hdr_t  on (s_invoice_hdr_t.invoice_hdr_id = s_receipts_t.invoice_hdr_id )
        left join s_salesorder_hdr_t on (s_salesorder_hdr_t.sales_hdr_id = s_receipts_t.sales_hdr_id )
     WHERE  s_receipts_t.receipt_id='$id' and (f_journal_entry_t.journal_type='RECEIPT' or f_journal_entry_t.journal_type='ADVANCE RECEIPT') ");
    // dd($sql);
    foreach ($sql as $key => $value) {
      if ($value->journal_type == "RECEIPT") {
        if ($value->reference_source != "") {
          $receipt_id = $value->receipt_id;
          $invoice_hdr_id = $value->invoice_hdr_id;

          $invhdrid = \DB::select("select invoice_hdr_id,balance_amount,paid_amount,invoice_grand_total from s_invoice_hdr_t where invoice_hdr_id in ($invoice_hdr_id) ");
          //  dd($invhdrid);
          foreach ($invhdrid as $tkey => $tvalue) {
            $balance_amount = $tvalue->balance_amount;
            $invoice_grand_total = $tvalue->invoice_grand_total;
            $paid_amount = $tvalue->paid_amount;
            $invid = $tvalue->invoice_hdr_id;
            $current_balance_amount = $balance_amount + $value->receipt_amount;
            if ($current_balance_amount < 0) {
              $paid_amount = 0;
              $receipt_amount = $receipt_amount + $balance_amount;
              $bal = $invoice_grand_total;
              $update = \DB::update("update s_invoice_hdr_t set receipt_status= '0',paid_amount='$paid_amount',balance_amount='$bal',receipt_status='0' where invoice_hdr_id='$invid'");
              break;
            } else {

              $bal = $current_balance_amount;
              $paid_amount = $paid_amount - $value->receipt_amount;
              $update1 = \DB::update("update s_invoice_hdr_t set receipt_status= '0',paid_amount='$paid_amount',balance_amount='$bal',receipt_status='0' where invoice_hdr_id='$invid'");

              // break;

            }
          }

        }
      }
      if ($value->journal_type == "ADVANCE RECEIPT") {
        if ($value->reference_source != "") {
          $sales_hdr_id = $value->sales_hdr_id;
          $credit_amount = $value->credit_amount;
          $debit_amount = $value->debit_amount;
          $balance_amount = $value->so_bal_amt - $value->receipt_amount;
          $paid_amount = $value->so_ad_pay - $value->receipt_amount;
          $update = \DB::update("update s_salesorder_hdr_t set advance_amount= '$paid_amount',advance_status='0', balance_amount='$balance_amount' where sales_hdr_id ='$sales_hdr_id' ");
        }
      }
    }


    /*REVERSE insert in payments for statement view */

    $receiptid = $sql[0]->journal_reference;
    $revpay = \DB::Select("Select * from s_receipts_t where receipt_id='$receiptid' ");
    $payment_hdr['payment_id'] = '';
    $payment_hdr['payment_number'] = 'Reverse-' . $revpay[0]->receipt_number;
    $payment_hdr['payment_count'] = '';
    $payment_hdr['cheque_count'] = '';
    $payment_hdr['po_invoice_id'] = $revpay[0]->invoice_hdr_id;
    $payment_hdr['supplier_id'] = '';
    $payment_hdr['supplier_site_id'] = '';
    $payment_hdr['payment_date'] = $revpay[0]->receipt_date;
    $payment_hdr['advance_amount'] = $revpay[0]->advance_amount;
    $payment_hdr['invoice_amount'] = $revpay[0]->invoice_amount;
    $payment_hdr['payment_amount'] = $revpay[0]->receipt_amount;
    $payment_hdr['paid_amount'] = $revpay[0]->paid_amount;
    $payment_hdr['balance_amount'] = $revpay[0]->balance_amount;
    $payment_hdr['account_code_id'] = $revpay[0]->account_code_id;
    $payment_hdr['payment_type_id'] = $revpay[0]->receipt_type_id;
    $payment_hdr['payment_reference'] = $revpay[0]->receipt_reference;
    $payment_hdr['remarks'] = $revpay[0]->remarks;
    $payment_hdr['payment_status'] = $revpay[0]->receipt_status;
    $payment_hdr['cheque_no'] = $revpay[0]->cheque_no;
    $payment_hdr['account_no'] = $revpay[0]->account_no;
    $payment_hdr['bank_id'] = $revpay[0]->bank_id;
    $payment_hdr['created_by'] = \Session::get('id');
    $payment_hdr['created_at'] = date('Y-m-d H:i:s');
    $payment_hdr['last_updated_by'] = \Session::get('id');
    $payment_hdr['updated_at'] = date('Y-m-d H:i:s');
    ;
    $payment_hdr['location_id'] = \Session::get('companyid');
    $payment_hdr['company_id'] = \Session::get('location');
    $payment_hdr['advance_deduction'] = $revpay[0]->advance_deduction;
    $payment_hdr['payment_source'] = 'REV' . $revpay[0]->receipt_source;
    $payment_hdr['reference_id'] = $revpay[0]->receipt_id;
    $payment_hdr['po_hdr_id'] = $revpay[0]->sales_hdr_id;
    $payment_hdr['sender_information'] = $revpay[0]->customer_account_name;
    $payment_hdr['customer_id'] = $revpay[0]->customer_id;

    $hdr_id = \DB::table('p_payments_t')->insertGetId($payment_hdr);
    //*Reverse Journal Insert*/
    $journal_hdr['journal_name'] = "Reverse-" . $sql[0]->journal_name;
    $journal_hdr['journal_category'] = $sql[0]->journal_category;
    $journal_hdr['journal_date'] = date('Y-m-d');
    ;
    $journal_hdr['journal_type'] = "REVERSE " . $sql[0]->journal_type;
    $journal_hdr['journal_reference'] = $sql[0]->journal_reference;
    $journal_hdr['journal_status'] = $sql[0]->journal_status;
    $journal_hdr['created_by'] = \Session::get('id');
    $journal_hdr['created_at'] = date('Y-m-d H:i:s');
    $journal_hdr['last_updated_by'] = \Session::get('id');
    $journal_hdr['updated_at'] = date('Y-m-d H:i:s');
    ;
    $journal_hdr['location_id'] = \Session::get('companyid');
    $journal_hdr['company_id'] = \Session::get('location');
    //dd($journal_hdr);
    $hdr_id = \DB::table('f_journal_entry_t')->insertGetId($journal_hdr);
    $jid = DB::getPdo()->lastInsertId();
    // dd($jid);  
    //dd($hdr_id);
    foreach ($sql as $tkey => $tvalue) {

      $journal_lines['journal_entry_id'] = $jid;
      $journal_lines['reference_source'] = $tvalue->reference_source;
      $journal_lines['journal_date'] = date('Y-m-d');
      ;
      $journal_lines['reference_id'] = $tvalue->reference_id;
      $journal_lines['status'] = $tvalue->status;
      // $journal_lines[$tkey]['line_no']=$v->line_no; 

      $journal_lines['account_id'] = $tvalue->account_id;
      $journal_lines['credit_amount'] = $tvalue->debit_amount;
      $journal_lines['debit_amount'] = $tvalue->credit_amount;
      $journal_lines['employee_id'] = $tvalue->employee_id;
      $journal_lines['payment_id'] = $tvalue->payment_id;
      $journal_lines['payment_amount'] = $tvalue->payment_amount;
      $journal_lines['reference_source_id'] = $tvalue->reference_source_id;

      $journal_lines['line_no'] = $tkey + 1;
      $journal_lines['created_by'] = \Session::get('id');
      $journal_lines['created_at'] = date('Y-m-d H:i:s');
      $journal_lines['last_updated_by'] = \Session::get('id');
      $journal_lines['updated_at'] = date('Y-m-d H:i:s');
      $journal_lines['location_id'] = \Session::get('companyid');
      $journal_lines['company_id'] = \Session::get('location');

      $journal_lines_data = \DB::table('f_journal_entry_lines_t')->insert($journal_lines);
    }
    // RECEIPT  in receipt
    // ADVANCE RECEIPT   in so
    return 1;
  }

  public function getReceiptbounceconfirm($id = null)
  {

    $chequebounce = \DB::update("update s_receipts_t set cheque_bounce_status='reversed' where receipt_id='$id' ");

    $sql = \DB::select("SELECT f_journal_entry_t.*,
                         f_journal_entry_lines_t.*, 
                         s_salesorder_hdr_t.advance_amount as so_ad_pay,
                         s_salesorder_hdr_t.balance_amount as so_bal_amt,
                         s_receipts_t.receipt_id,
                         s_receipts_t.sales_hdr_id,
                         s_receipts_t.invoice_hdr_id,
                         s_receipts_t.balance_amount,
                         s_receipts_t.paid_amount,
                         s_receipts_t.receipt_amount,
                         s_receipts_t.receipt_id,
                         s_receipts_t.customer_id
        FROM f_journal_entry_lines_t 
        left join f_journal_entry_t on (f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id)
        LEFT JOIN s_receipts_t on (s_receipts_t.receipt_id = f_journal_entry_t.journal_reference )
        left join s_invoice_hdr_t  on (s_invoice_hdr_t.invoice_hdr_id = s_receipts_t.invoice_hdr_id )
        left join s_salesorder_hdr_t on (s_salesorder_hdr_t.sales_hdr_id = s_receipts_t.sales_hdr_id )
     WHERE  s_receipts_t.receipt_id='$id' and (f_journal_entry_t.journal_type='RECEIPT' or f_journal_entry_t.journal_type='ADVANCE RECEIPT') ");
    // dd($sql);
    foreach ($sql as $key => $value) {
      if ($value->journal_type == "RECEIPT") {
        if ($value->reference_source != "") {
          $receipt_id = $value->receipt_id;
          $invoice_hdr_id = $value->invoice_hdr_id;

          $invhdrid = \DB::select("select invoice_hdr_id,balance_amount,paid_amount,invoice_grand_total from s_invoice_hdr_t where invoice_hdr_id in ($invoice_hdr_id) ");
          //  dd($invhdrid);
          foreach ($invhdrid as $tkey => $tvalue) {
            $balance_amount = $tvalue->balance_amount;
            $invoice_grand_total = $tvalue->invoice_grand_total;
            $paid_amount = $tvalue->paid_amount;
            $invid = $tvalue->invoice_hdr_id;
            $current_balance_amount = $balance_amount + $value->receipt_amount;
            if ($current_balance_amount < 0) {
              $paid_amount = 0;
              $receipt_amount = $receipt_amount + $balance_amount;
              $bal = $invoice_grand_total;
              $update = \DB::update("update s_invoice_hdr_t set receipt_status= '0',paid_amount='$paid_amount',balance_amount='$bal',receipt_status='0' where invoice_hdr_id='$invid'");
              break;
            } else {

              $bal = $current_balance_amount;
              $paid_amount = $paid_amount - $value->receipt_amount;
              $update1 = \DB::update("update s_invoice_hdr_t set receipt_status= '0',paid_amount='$paid_amount',balance_amount='$bal',receipt_status='0' where invoice_hdr_id='$invid'");

              // break;

            }
          }
          //   $balance_amount = $value->receipt_amount+$invoice[0]->balance_amount;
          //   $paid_amount= $invoice[0]->paid_amount-$value->receipt_amount;
          //   //$receipt_amount= $value->receipt_amount-$value->receipt_amount;
          // //   $update = \DB::update("update s_receipts_t set paid_amount= '$paid_amount', balance_amount='$balance_amount' where receipt_id ='$receipt_id' ");
          //    $update1 = \DB::update("update s_invoice_hdr_t set receipt_status= '0',paid_amount= '$paid_amount', balance_amount='$balance_amount' where invoice_hdr_id ='$invoice_hdr_id' ");
        }
      }
      if ($value->journal_type == "ADVANCE RECEIPT") {
        if ($value->reference_source != "") {
          $sales_hdr_id = $value->sales_hdr_id;
          $credit_amount = $value->credit_amount;
          $debit_amount = $value->debit_amount;
          $balance_amount = $value->so_bal_amt - $value->receipt_amount;
          $paid_amount = $value->so_ad_pay - $value->receipt_amount;
          $update = \DB::update("update s_salesorder_hdr_t set advance_amount= '$paid_amount',advance_status='0', balance_amount='$balance_amount' where sales_hdr_id ='$sales_hdr_id' ");
        }
      }
    }


    /*REVERSE insert in payments for statement view */
    $receiptid = $sql[0]->journal_reference;

    $revpay = \DB::Select("Select * from s_receipts_t where receipt_id='$receiptid' ");
    //  dd($revpay);
    $payment_hdr['payment_id'] = '';
    $payment_hdr['payment_number'] = 'Reverse-' . $revpay[0]->receipt_number;
    $payment_hdr['payment_count'] = '';
    $payment_hdr['cheque_count'] = '';
    $payment_hdr['po_invoice_id'] = $revpay[0]->invoice_hdr_id;
    $payment_hdr['supplier_id'] = '';
    $payment_hdr['supplier_site_id'] = '';
    $payment_hdr['payment_date'] = $revpay[0]->receipt_date;
    $payment_hdr['advance_amount'] = $revpay[0]->advance_amount;
    $payment_hdr['invoice_amount'] = $revpay[0]->invoice_amount;
    $payment_hdr['payment_amount'] = $revpay[0]->receipt_amount;
    $payment_hdr['paid_amount'] = $revpay[0]->paid_amount;
    $payment_hdr['balance_amount'] = $revpay[0]->balance_amount;
    $payment_hdr['account_code_id'] = $revpay[0]->account_code_id;
    $payment_hdr['payment_type_id'] = $revpay[0]->receipt_type_id;
    $payment_hdr['payment_reference'] = $revpay[0]->receipt_reference;
    $payment_hdr['remarks'] = $revpay[0]->remarks;
    $payment_hdr['payment_status'] = $revpay[0]->receipt_status;
    $payment_hdr['cheque_no'] = $revpay[0]->cheque_no;
    $payment_hdr['account_no'] = $revpay[0]->account_no;
    $payment_hdr['bank_id'] = $revpay[0]->bank_id;
    $payment_hdr['created_by'] = \Session::get('id');
    $payment_hdr['created_at'] = date('Y-m-d H:i:s');
    $payment_hdr['last_updated_by'] = \Session::get('id');
    $payment_hdr['updated_at'] = date('Y-m-d H:i:s');
    ;
    $payment_hdr['location_id'] = \Session::get('companyid');
    $payment_hdr['company_id'] = \Session::get('location');
    $payment_hdr['advance_deduction'] = $revpay[0]->advance_deduction;
    $payment_hdr['payment_source'] = 'REV' . $revpay[0]->receipt_source;
    $payment_hdr['reference_id'] = $revpay[0]->receipt_id;
    $payment_hdr['po_hdr_id'] = $revpay[0]->sales_hdr_id;
    $payment_hdr['sender_information'] = $revpay[0]->customer_account_name;
    $payment_hdr['customer_id'] = $revpay[0]->customer_id;
    // $payment_hdr['bank_date']='';
    // $payment_hdr['supplier_bank_id']='';
    // $payment_hdr['supplier_account_no']='';
    // $payment_hdr['supplier_ifsc_code']='';
    // $payment_hdr['supplier_account_name']='';
    // $payment_hdr['employee_id']='';
    // $payment_hdr['remark']='';
    // $payment_hdr['batch_status']='';
    // $payment_hdr['imprest_employee_id']=''; 
    // $payment_hdr['penality_amount']='';
    // $payment_hdr['discount_amount']='';
    // $payment_hdr['tds_applicable']='';
    // $payment_hdr['tds_prcnt']='';
    // $payment_hdr['tds_account_id']='';
    // $payment_hdr['tds_amount']='';
    // $payment_hdr['direct_accountcodeid']='';
    // $payment_hdr['favouring_name']='';
    // $payment_hdr['ac_payee_status']='';
    // $payment_hdr['cheque_cancel_status']='';
    // $payment_hdr['stmtid']='';
    $hdr_id = \DB::table('p_payments_t')->insertGetId($payment_hdr);
    //*Reverse Journal Insert*/
    $journal_hdr['journal_name'] = "Reverse-" . $sql[0]->journal_name;
    $journal_hdr['journal_category'] = $sql[0]->journal_category;
    $journal_hdr['journal_date'] = date('Y-m-d');
    ;
    $journal_hdr['journal_type'] = "REVERSE " . $sql[0]->journal_type;
    $journal_hdr['journal_reference'] = $sql[0]->journal_reference;
    $journal_hdr['journal_status'] = $sql[0]->journal_status;
    $journal_hdr['created_by'] = \Session::get('id');
    $journal_hdr['created_at'] = date('Y-m-d H:i:s');
    $journal_hdr['last_updated_by'] = \Session::get('id');
    $journal_hdr['updated_at'] = date('Y-m-d H:i:s');
    ;
    $journal_hdr['location_id'] = \Session::get('companyid');
    $journal_hdr['company_id'] = \Session::get('location');
    //dd($journal_hdr);
    $hdr_id = \DB::table('f_journal_entry_t')->insertGetId($journal_hdr);
    $jid = DB::getPdo()->lastInsertId();
    // dd($jid);  
    //dd($hdr_id);
    foreach ($sql as $tkey => $tvalue) {

      $journal_lines['journal_entry_id'] = $jid;
      $journal_lines['reference_source'] = $tvalue->reference_source;
      $journal_lines['journal_date'] = date('Y-m-d');
      ;
      $journal_lines['reference_id'] = $tvalue->reference_id;
      $journal_lines['status'] = $tvalue->status;
      // $journal_lines[$tkey]['line_no']=$v->line_no; 

      $journal_lines['account_id'] = $tvalue->account_id;
      $journal_lines['credit_amount'] = $tvalue->debit_amount;
      $journal_lines['debit_amount'] = $tvalue->credit_amount;
      $journal_lines['employee_id'] = $tvalue->employee_id;
      $journal_lines['payment_id'] = $tvalue->payment_id;
      $journal_lines['payment_amount'] = $tvalue->payment_amount;
      $journal_lines['reference_source_id'] = $tvalue->reference_source_id;

      $journal_lines['line_no'] = $tkey + 1;
      $journal_lines['created_by'] = \Session::get('id');
      $journal_lines['created_at'] = date('Y-m-d H:i:s');
      $journal_lines['last_updated_by'] = \Session::get('id');
      $journal_lines['updated_at'] = date('Y-m-d H:i:s');
      $journal_lines['location_id'] = \Session::get('companyid');
      $journal_lines['company_id'] = \Session::get('location');

      $journal_lines_data = \DB::table('f_journal_entry_lines_t')->insert($journal_lines);
    }
    // RECEIPT  in receipt
    // ADVANCE RECEIPT   in so
    return 1;
  }

}
