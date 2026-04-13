<?php

namespace App\Http\Controllers;
use DB;
use App\Advancereceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class AdvancereceiptController extends Controller
{
  public $module = "advancereceipt";
  public function __construct()
  {
    $this->data = array();

    $this->table = "s_receipts_t";
    $this->pageModule = "advancereceipt";
    $this->model = new Advancereceipt();
    $this->model = new Advancereceipt;
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

    $this->data['salperson'] = $this->jqgridselect('s_salesperson_t', 'salesperson_id', 'salesperson_name', '');
    $this->data['customeropt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
    $table = \DB::table('s_receipts_t')->get();
    $this->data['datas'] = $table;
    $this->data['pageMethod'] = \Request::route()->getName();

    return view('advancereceipt.table', $this->data);

  }
  public function soorderdetailsgriddata()
  {

    $wh = '';
    $wh1 = '';
    $wh2 = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $org = \Session::get('organization');
    $groupname = \Session::get('groupname');

    $wh .= $grid_data = $this->grid_check('s_salesorder_hdr_t', 'sales_order_date');

    $result = \DB::select("select * from (SELECT
			s_salesorder_hdr_t.sales_hdr_id,
			s_salesorder_hdr_t.`sales_order_no`,
			s_salesorder_hdr_t.`sales_order_date`,
			s_salesorder_hdr_t.`order_type_id`,
			s_salesorder_hdr_t.`order_status_id`,
			s_salesorder_hdr_t.`savestatus`,
			s_salesorder_hdr_t.`contact_number` as contact_number,
			s_salesorder_hdr_t.`contact_person`,
			s_salesperson_t.salesperson_name as salesperson_id,
			m_customers_t.customer_name as ship_to_customer_id
			FROM `s_salesorder_hdr_t`
			left join s_salesperson_t  on(
				s_salesperson_t.salesperson_id=s_salesorder_hdr_t.`salesperson_id`
				)
				left join m_customers_t  on
				m_customers_t.customer_id=s_salesorder_hdr_t.`ship_to_customer_id`
				left join s_quote_hdr_t  on (
				s_quote_hdr_t.quote_hdr_id=s_salesorder_hdr_t.`ar_quote_hdr_id`)
				where s_salesorder_hdr_t.order_status_id='approved' and s_salesorder_hdr_t.order_type_id!='sample' $wh  order by s_salesorder_hdr_t.sales_hdr_id) v1 where 1=1 $wh1");

    return DataTables::of($result)->make(true);

  }


  public function create($id = null)
  {

    $this->data['pageModule'] = 'advancereceipt';
    $this->data['pageUrl'] = url('advancereceipt');
    $table = \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $id)->get();

    $customerbankdetails = \DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*', 'f_bank_account_lines_t.*')
      ->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
      ->where('f_bank_account_hdr_t.customerid', $table[0]->ship_to_customer_id)->where('f_bank_account_lines_t.active', 'Yes')->get();

    $this->data['row'] = (object) array();
    $this->data['row']->receipt_id = "";
    $this->data['row']->receipt_number = "";
    $this->data['row']->receipt_date = date('Y-m-d');
    $this->data['row']->receipt_amount = "";
    $this->data['row']->receipt_status = "";
    $this->data['row']->so_amount = $table[0]->order_total;
    if (count($customerbankdetails) > 0) {
      $this->data['row']->bank_name = $customerbankdetails[0]->bank_name;
      $this->data['row']->account_number = $customerbankdetails[0]->account_number;
      $this->data['row']->ifsc_code = $customerbankdetails[0]->ifsc_code;
      $this->data['row']->customer_account_name = $customerbankdetails[0]->name_in_account;
    } else {
      $this->data['row']->supplier_bank_id = "";
      $this->data['row']->supplier_account_no = "";
      $this->data['row']->supplier_ifsc_code = "";
      $this->data['row']->supplier_account_name = "";
    }
    $this->data['row']->receipt_type_id = "";
    $so_number = \DB::select("select sales_order_no from s_salesorder_hdr_t where sales_hdr_id='$id'");
    $this->data['row']->receipt_reference = $so_number[0]->sales_order_no;
    $this->data['row']->cheque_no = "";
    $this->data['row']->remarks = "";

    //dd($table);
    if ($table[0]->invoice_currency != '' && $table[0]->invoice_currency != 0) {
      $this->data['row']->invoice_currency_id = $table[0]->invoice_currency;
      $curr = \DB::table('f_account_currency_t')->select('currency_code')->where('account_currency_id', $table[0]->invoice_currency)->get();
      // dd($curr);
      if ($curr[0]->currency_code != "INR") {
        $this->data['row']->invoice_currency = $curr[0]->currency_code;
        $data11 = \DB::table('f_account_exchangerates_t')->whereDate('from_date', '<=', $table[0]->sales_order_date)->whereDate('to_date', '>=', $table[0]->sales_order_date)->where('from_currency_id', $table[0]->invoice_currency)->where('to_currency_id', 37)->where('active', 'Yes')->select('*')->get();
        // dd($data11);
        if (count($data11) > 0) {
          $this->data['row']->conversion_rate = $data11[0]->conversion_rate;
        } else {
          $data112 = \DB::table('f_account_exchangerates_t')->where('from_currency_id', $table[0]->invoice_currency)->where('to_currency_id', 37)->where('active', 'Yes')->select('*')->OrderBY('account_exchangerate_id')->get();
          $this->data['row']->conversion_rate = $data112[0]->conversion_rate;
        }
      } else {
        $this->data['row']->invoice_currency = 'INR';
        $this->data['row']->invoice_currency_id = 37;
        $this->data['row']->conversion_rate = 1;
      }
    } else {
      $this->data['row']->invoice_currency_id = 37;
      $this->data['row']->invoice_currency = 'INR';
      $this->data['row']->conversion_rate = 1;
    }

    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', '');
    $this->data['sales_hdr_id'] = $this->jCombocomp('s_salesorder_hdr_t', 'sales_hdr_id', 'sales_order_no', $table[0]->sales_hdr_id);
    $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', $table[0]->ship_to_customer_id);
    $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
    //$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
    $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');
    $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

    return view('advancereceipt.form', $this->data);
  }


  /* Purpose for Po based Supplier and amount load*/
  function getsodetails($id = null)
  {
    $sodata = \DB::select("select sales_hdr_id,ship_to_customer_id,order_total from s_salesorder_hdr_t where sales_hdr_id='$id'");
    if (!empty($sodata)) {
      return $sodata;
    } else {
      return 0;
    }
  }
  /*End*/
  /* Purpose for Default Cheque Number*/
  function getchequeno($accno = null)
  {
    $sql = \DB::SELECT("select cheque_from_no,cheque_to_no from f_bank_cheque_lines_t where bank_cheque_hdr_id = '$accno' and cheque_status='ACTIVE'");
    $cheqno = \DB::select("SELECT MAX(cheq.cheque_count) AS cheque_no FROM f_bank_cheque_lines_t cheq LEFT JOIN s_receipts_t payhdr on (payhdr.account_no=cheq.bank_cheque_hdr_id)where payhdr.account_no='$accno'");
    $max = $cheqno[0]->cheque_no;
    $end = $sql[0]->cheque_to_no;
    if ($max == null) {
      $data1 = $sql[0]->cheque_from_no;
    } else {
      $data1 = $max + 1;
    }
    $data['chequeno'] = $data1;
    $data['endcheque'] = $end;

    return $data;
  }
  /*End*/

  /* purpose for Save function*/
  public function save(Request $request)
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
      'existing_file','so_amount','invoice_currency_id',
    ]);
    // Normalize "bulk_" keys once
    $form = $this->normalizeLineFormKeys($form);

    // Build header + lines
    $data = $this->validatePost($form, $this->table, 'header');

    if ($_POST['receipt_number'] == "") {
      $seqno = $this->Seqnoe('RCPT-', 's_receipts_t', '', 'receipt_count');
      $data['receipt_number'] = $seqno[0];
      $data['receipt_count'] = $seqno[1];
    } else {
      $seqno[0] = $_POST['receipt_number'];
    }
    //dd($data);
    \DB::beginTransaction();
    try {

      $so_data = \DB::select("select * from s_salesorder_hdr_t where sales_hdr_id='" . $_POST['sales_hdr_id'] . "'");

      $advance_amount = $so_data[0]->advance_amount + $_POST['receipt_amount'];
      $balance_amount = $so_data[0]->balance_amount + $_POST['receipt_amount'];

      \DB::update("update s_salesorder_hdr_t set advance_amount='$advance_amount',balance_amount='$balance_amount',advance_status='0' where sales_hdr_id='" . $_POST['sales_hdr_id'] . "'");

      $invoice_currency = $_POST['invoice_currency'];
      if ($invoice_currency != 'INR') {
        $exchangerate = $_POST['exchangerate'];
        \DB::update("update s_salesorder_hdr_t set advance_exchangerate='$exchangerate' where sales_hdr_id='" . $_POST['sales_hdr_id'] . "'");
      } else {
        $exchangerate = 1;
      }

      $id = $this->model->insertRow($data);
      $chequeno = '';
      $accno = '';
      $chequeupdate = \DB::update("UPDATE f_bank_cheque_lines_t set cheque_count='$chequeno' where bank_cheque_hdr_id='$accno'");

      if ($_POST['receipt_status'] == "INITIATED") {
        $advanceno = "ADVANCE RECEIPT-" . $data['receipt_number'];
        $advdate = $_POST['receipt_date'];
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        //Journal Header Insert
        $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$advanceno','ADVANCE RECEIPT','$advdate','$id','APPROVED','$compy','$loc','$org')");
        $jid = DB::getPdo()->lastInsertId();
        //Journal lINES Insert           
        $customer_acc = \DB::table('m_customers_t')->where('customer_id', $_POST['customer_id'])->get();
        $customer_acc = $customer_acc[0]->account_structure_id;
        //Journal lINES Insert       

        $tkey = 0;

        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $_POST['receipt_date'];
        $journal_lines_data[$tkey]['reference_source'] = "CUSTOMER";
        $journal_lines_data[$tkey]['reference_id'] = $_POST['customer_id'];
        $journal_lines_data[$tkey]['account_id'] = $customer_acc;

        $journal_lines_data[$tkey]['debit_amount'] = "";
        $journal_lines_data[$tkey]['credit_amount'] = $_POST['receipt_amount'] * $exchangerate;
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
        $journal_lines_data[$tkey]['journal_date'] = $_POST['receipt_date'];
        $journal_lines_data[$tkey]['reference_source'] = "";
        $journal_lines_data[$tkey]['reference_id'] = '';
        $journal_lines_data[$tkey]['account_id'] = $_POST['account_code_id'];

        $journal_lines_data[$tkey]['debit_amount'] = $_POST['receipt_amount'] * $exchangerate;
        $journal_lines_data[$tkey]['credit_amount'] = "";
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
        ;
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

        \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

      }
      \DB::commit();
      return response()->json(array('status' => 'success', 'message' => 'Advance Saved Successfully', 'id' => $id));
    } catch (\Illuminate\Database\QueryException $e) {
      $message = explode('(', $e->getMessage());
      $dbCode = rtrim($message[0], ']');
      $dbCode = trim($dbCode, '[');
      \DB::rollback();
      dd($dbCode);
      return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
    }

  }

  /* purpose for Display View function*/
  public function show(request $request, $id = null)
  {
    if (isset($id)) {
      $vdata = \DB::table('s_receipts_t')->leftjoin('f_account_structure_t', 'f_account_structure_t.f_account_structure_id', '=', 's_receipts_t.account_code_id')
        ->leftjoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_receipts_t.customer_id')
        ->leftjoin('s_salesorder_hdr_t', 's_salesorder_hdr_t.sales_hdr_id', '=', 's_receipts_t.sales_hdr_id')
        ->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_line_id', '=', 's_receipts_t.account_no')
        ->leftjoin('f_bank_account_hdr_t', 'f_bank_account_hdr_t.bank_account_hdr_id', '=', 's_receipts_t.bank_id')
        ->where('receipt_id', $id)->get();

      $this->data['sales_order_no'] = $vdata[0]->sales_order_no;
      $this->data['customer_name'] = $vdata[0]->customer_name;
      $this->data['advance_amount'] = $vdata[0]->advance_amount;
      $this->data['advance_date'] = $vdata[0]->advance_date;
      $this->data['receipt_type_id'] = $vdata[0]->receipt_type_id;
      $this->data['receipt_reference'] = $vdata[0]->receipt_reference;
      $this->data['remarks'] = $vdata[0]->remarks;
      $this->data['bank_name'] = $vdata[0]->bank_name;
      $this->data['account_number'] = $vdata[0]->account_number;
      $this->data['cheque_no'] = $vdata[0]->cheque_no;
      $this->data['concatenated_segments'] = $vdata[0]->concatenated_segments;

      return view('advancereceipt.view', $this->data);
    }
  }

  // AJith Purpose of export exchange value get
  function exchangerategetval()
  {
    //dd($_GET);
    $invoice_currency = $_GET['invoice_currency'];
    $receipt_date = date('Y-m-d', strtotime($_GET['receipt_date']));
    // dd($receipt_date);
    if ($invoice_currency != "INR") {
      $data11 = \DB::table('f_account_exchangerates_t')->whereDate('from_date', '<=', $receipt_date)->whereDate('to_date', '>=', $receipt_date)->where('from_currency_id', $invoice_currency)->where('to_currency_id', 37)->where('active', 'Yes')->select('*')->get();
      // dd($data11);
      if (count($data11) > 0) {
        $data['conversion_rate'] = $data11[0]->conversion_rate;
      } else {
        $data112 = \DB::table('f_account_exchangerates_t')->where('from_currency_id', $invoice_currency)->where('to_currency_id', 37)->where('active', 'Yes')->select('*')->OrderBY('account_exchangerate_id')->get();
        $data['conversion_rate'] = $data112[0]->conversion_rate;
      }
    } else {
      $data['conversion_rate'] = 1;
    }
    //dd($data);      
    return $data;
  }
  /*End*/

}

