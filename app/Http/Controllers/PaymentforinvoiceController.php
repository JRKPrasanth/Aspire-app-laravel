<?php

namespace App\Http\Controllers;
use DB;
use App\Paymentforinvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SupplierbalancerptController;
use App\Services\SupplierService;
use Config;
use Yajra\DataTables\DataTables;

class PaymentforinvoiceController extends Controller
{
  public $module = "paymentforinvoice";

  public function __construct()
  {
    $this->data = array();
    $this->data = array();
    $this->table = "p_payments_t";
    $this->pageModule = "paymentforinvoice";
    $this->model = new Paymentforinvoice();
    $this->submodel = new Paymentforinvoice;
    $this->model = new Paymentforinvoice;
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['pageFormtype'] = 'ajax';
    $this->table = "p_payments_t";
    $this->data['urlmenu'] = $this->indexs();

  }
  /* Purpose For Payment Request Index Call Function*/
  public function paymentrequestindex(Request $request)
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


    $this->data['supplieropt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
    $table = \DB::table('p_po_invoice_hdr_t')->get();
    $this->data['datas'] = $table;

    $wh = '';
    if ($this->data['pageMethod'] == "paymentrequestapproval") {
      $wh .= ' and p_po_invoice_hdr_t.payment_request_status=1';
      $wh .= $grid_data = $this->grid_statuscheck('p_po_invoice_hdr_t', 'invoice_date', 'payment_request_status', '=', 1);
    } else {
      $wh .= ' and p_po_invoice_hdr_t.payment_request_status=0';
      $wh .= $grid_data = $this->grid_statuscheck('p_po_invoice_hdr_t', 'invoice_date', 'payment_request_status', '=', 0);

    }


    $loc = \Session::get('location');
    $compy = \Session::get('companyid');

    $this->data['sum_inv_tot'] = \DB::select("select * from(SELECT
                        ROUND(SUM(p_po_invoice_hdr_t.invoice_grand_total),2) as sum_inv_total,
                        ROUND(SUM(p_po_invoice_hdr_t.balance_amount),2) as sum_bal_total
                        FROM  p_po_invoice_hdr_t 
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                        left join p_grn_hdr_t on(
                        p_grn_hdr_t.grn_id=p_po_invoice_hdr_t.grn_number)
                        left join p_po_hdr_t on(
                        p_po_hdr_t.po_hdr_id=p_po_invoice_hdr_t.po_number) where 1=1  and p_po_invoice_hdr_t.balance_amount!=0  and p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.company_id=$compy and p_po_invoice_hdr_t.location_id=$loc $wh )as v1 where 1=1 ");

    return view('paymentforinvoice.paymentrequesttable', $this->data);
  }

  /* Purpose For PAyment For Invoice Jqgrid*/
  public function getRequestforpaymentData()
  {
    $wh1 = '';
    $wh = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');

    $groupname = \Session::get('groupname');


    if (isset($_GET['pagemethod'])) {
      if ($_GET['pagemethod'] == "paymentrequestapproval") {
        $wh .= ' and p_po_invoice_hdr_t.payment_request_status=1';
        $wh .= $grid_data = $this->grid_statuscheck('p_po_invoice_hdr_t', 'invoice_date', 'payment_request_status', '=', 1);
      } else {
        $wh .= ' and p_po_invoice_hdr_t.payment_request_status=0';
        $wh .= $grid_data = $this->grid_statuscheck('p_po_invoice_hdr_t', 'invoice_date', 'payment_request_status', '=', 0);

      }
    }

    $SQL = "select * from(SELECT
                        p_po_invoice_hdr_t.po_invoice_id,
                        p_po_invoice_hdr_t.bill_number,
                        p_po_invoice_hdr_t.invoice_date,
                        p_po_invoice_hdr_t.po_invoice_status,
                        p_po_invoice_hdr_t.attachfile_name,
                        p_po_hdr_t.po_number,
                        p_po_hdr_t.po_hdr_id,
                        p_po_hdr_t.advance_amount,
                        p_po_invoice_hdr_t.balance_amount,
                        p_po_invoice_hdr_t.po_date,
                        p_po_invoice_hdr_t.invoice_grand_total,
                        p_po_invoice_hdr_t.due_date,
                        p_po_invoice_hdr_t.credit_note_balance,
                        p_po_invoice_hdr_t.debit_note_balance,
                        m_supplier_t.supplier_name,
                        m_supplier_t.msme_status,
                        p_po_invoice_hdr_t.dc_date,
                        p_grn_hdr_t.grn_date,
                        DATEDIFF(CURDATE(), p_po_invoice_hdr_t.due_date) as over_due,
                        DATEDIFF(CURDATE(), p_grn_hdr_t.grn_date) as msme_over_due,
                         (case when p_po_invoice_hdr_t.payment_request_status=0 then 'OPEN'  when p_po_invoice_hdr_t.payment_request_status=1 then 'INITIATED' else 'APPROVED' end) as requeststatus
                        FROM  p_po_invoice_hdr_t 
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                        left join p_grn_hdr_t on(
                        p_grn_hdr_t.grn_id=p_po_invoice_hdr_t.grn_number)
                        left join p_po_hdr_t on(
                        p_po_hdr_t.po_hdr_id=p_po_invoice_hdr_t.po_number) where 1=1  and p_po_invoice_hdr_t.balance_amount!=0  and p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.company_id=$compy and p_po_invoice_hdr_t.location_id=$loc $wh ORDER BY p_po_invoice_hdr_t.po_invoice_id DESC )as v1 where 1=1 $wh1";

    $result = \DB::select($SQL);

    foreach ($result as $k => $v) {

      // ---- PO Number link ----
      $poUrl = \URL::to('purchaseorderview/' . $v->po_hdr_id . '?return=purchaseorder');
      $result[$k]->po_number = "<a href='{$poUrl}' target='_blank' class='text-decoration-none text-primary fw-bold'>{$v->po_number}</a>";

      // ---- Invoice Number link ----
      $invUrl = \URL::to('invoiceDataview/' . $v->po_invoice_id . '?return=purchaseinvoice');
      $result[$k]->bill_number = "<a href='{$invUrl}' target='_blank' class='text-decoration-none text-primary fw-bold'>{$v->bill_number}</a>";

      if (!empty($v->attachfile_name)) {
        $dataupload = json_decode($v->attachfile_name, true);
        if (!empty($dataupload)) {
          $links = [];
          foreach ($dataupload as $file) {
            $fileUrl = asset("uploads/purchaseinvoice/PO{$v->po_invoice_id}/{$file}");
            $links[] = "<a href='{$fileUrl}' target='_blank' class='badge bg-primary text-white me-1'>{$file}</a>";
          }
          $result[$k]->attachfile_name = implode(' ', $links);
        }
      }
    }

    return DataTables::of($result)
      ->rawColumns(['po_number', 'bill_number', 'attachfile_name'])
      ->make(true);


  }

  /*Payment Request Status Update*/
  public function getpaymentreq($id = null)
  {
    if ($id != "") {
      $status = 0;
      if (isset($_GET['status'])) {
        $status = $_GET['status'];
      }

      $journal = \DB::update("update p_po_invoice_hdr_t set payment_request_status='$status'  WHERE po_invoice_id IN ($id)");
      if ($status == 1) {
        $status1 = 'Requested';
      } else {
        $status1 = 'Approved';
      }
      return response()->json(array('status' => 'success', 'message' => "Payment $status1 Successfully", 'id' => $id));
    }
  }


  /*Vijay Purpose For Expense Payment Request Index Call Function*/
  public function expensepaymentrequestindex(Request $request)
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

    $this->data['supplieropt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
    $table = \DB::table('f_expenses_t')->get();
    $this->data['datas'] = $table;

    $wh = '';
    if ($this->data['pageMethod'] == "expensepaymentrequestapproval") {
      $wh .= ' and f_expenses_t.payment_request_status=1';
      $wh .= $grid_data = $this->grid_statuscheck('f_expenses_t', 'expense_date', 'payment_request_status', '=', 1);
    } else {
      $wh .= ' and f_expenses_t.payment_request_status=0';
      $wh .= $grid_data = $this->grid_statuscheck('f_expenses_t', 'expense_date', 'payment_request_status', '=', 0);

    }

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');

    $this->data['sum_exp_tot'] = \DB::select("select * from(SELECT 
                        ROUND(SUM(f_expenses_t.expense_amount),2) as sum_exp_total,
                        ROUND(SUM(f_expenses_t.balance_amount),2) as sum_bal_total
                    FROM f_expenses_t
                    left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_expenses_t.expense_account_id) 
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
          left join m_customers_t on(m_customers_t.customer_id=f_expenses_t.customer_id)
          left join hr_employee_t on(hr_employee_t.employee_id=f_expenses_t.employee_id)
          LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
                    left join tb_users on (tb_users.id =f_expenses_t.created_by)
                    where 1=1 and f_expenses_t.expense_status='APPROVED' and f_expenses_t.payment_status='0' $wh  )as v1 where 1=1");

    return view('paymentforinvoice.expensepaymentrequesttable', $this->data);
  }

  /* Purpose For Expense PAyment For Invoice Jqgrid*/
  public function getexpenseRequestforpaymentData()
  {
    $wh1 = '';
    $wh = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $groupname = \Session::get('groupname');

    if (isset($_GET['pagemethod'])) {
      if ($_GET['pagemethod'] == "expensepaymentrequestapproval") {
        $wh .= ' and f_expenses_t.payment_request_status=1';
        $wh .= $grid_data = $this->grid_statuscheck('f_expenses_t', 'expense_date', 'payment_request_status', '=', 1);
      } else {
        $wh .= ' and f_expenses_t.payment_request_status=0';
        $wh .= $grid_data = $this->grid_statuscheck('f_expenses_t', 'expense_date', 'payment_request_status', '=', 0);

      }
    }

    $SQL = "select * from(SELECT f_expenses_t.expense_id,
                    f_expenses_t.expense_date,
                    f_expenses_t.expense_no,
                    f_expenses_t.expense_type,
                    f_expenses_t.expense_status,
                    f_expenses_t.invoice,
                    f_expenses_t.remarks,
                    f_expenses_t.balance_amount,
                    m_supplier_t.supplier_name,
                    m_customers_t.customer_name,
                    hr_employee_t.first_name,
                    hr_employee_t.employee_number,
                    a_lookuplines_t.lookup_code as emp_type,
                    f_expenses_t.expense_amount,
                    DATEDIFF(CURDATE(), f_expenses_t.expense_date) as over_due,
                    f_expenses_lines_t.expense_line_id,
                    f_expenses_lines_t.choosefile,
                    (case when f_expenses_t.payment_request_status=0 then 'OPEN'  when f_expenses_t.payment_request_status=1 then 'INITIATED' else 'APPROVED' end) as requeststatus,
                    tb_users.username
                    FROM f_expenses_t
                    left join f_expenses_lines_t on f_expenses_t.expense_id = f_expenses_lines_t.expense_id
                    left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_expenses_t.expense_account_id) 
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
          left join m_customers_t on(m_customers_t.customer_id=f_expenses_t.customer_id)
          left join hr_employee_t on(hr_employee_t.employee_id=f_expenses_t.employee_id)
          LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
                    left join tb_users on (tb_users.id =f_expenses_t.created_by)
                    where 1=1 and f_expenses_t.expense_status='APPROVED' and f_expenses_t.payment_status='0' $wh  )as v1 where 1=1 $wh1 GROUP BY v1.expense_no ORDER BY v1.expense_id DESC";

    $result = \DB::select($SQL);

    foreach ($result as $k => $v) {

      $url = \URL::to('expensesview/' . $v->expense_id);
      $result[$k]->expense_no = "<a href='{$url}' target='_blank' class='text-decoration-none text-primary fw-bold'>{$v->expense_no}</a>";

      if ($v->choosefile != "" || $v->choosefile != null) {
        $dataupload = json_decode($v->choosefile);

        $dw = "";
        if (!empty($dataupload)) {
          foreach ($dataupload as $k1 => $v1) {


            $poinvid = $v->expense_line_id;

            $dw .= "<a href='../Uploads/expense/$poinvid/$v1' target='_blank' class='badge bg-primary text-white me-1'>" . $v1 . "</a>" . ",";

          }
        }
        $dw1 = trim($dw, ",");
        $result[$k]->choosefile = $dw1;
      }
    }

    return DataTables::of($result)->rawColumns(['expense_no', 'choosefile'])->make(true);
  }


  /*Expense Payment Request Status Update*/
  public function getexpensepaymentreq($id = null)
  {
    if ($id != "") {
      $status = 0;
      if (isset($_GET['status'])) {
        $status = $_GET['status'];
      }

      $journal = \DB::update("update f_expenses_t set payment_request_status='$status'  WHERE expense_id IN ($id)");
      if ($status == 1) {
        $status1 = 'Requested';
      } else {
        $status1 = 'Approved';
      }
      return response()->json(array('status' => 'success', 'message' => "Payment $status1 Successfully", 'id' => $id));
    }
  }


  /*Vijay Purpose For Employee Expense Payment Request Index Call Function*/
  public function empexpensepaymentrequestindex(Request $request)
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

    $this->data['supplieropt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
    $table = \DB::table('f_emp_expenses_lines_t')->get();
    $this->data['datas'] = $table;

    $wh = '';
    if ($this->data['pageMethod'] == "empexpensepaymentrequestapproval") {
      $wh .= ' and f_emp_expenses_lines_t.payment_request_status=1';
      $wh .= $grid_data = $this->grid_statuscheck('f_emp_expenses_lines_t', 'bill_date', 'payment_request_status', '=', 1);
    } else {
      $wh .= ' and f_emp_expenses_lines_t.payment_request_status=0';
      $wh .= $grid_data = $this->grid_statuscheck('f_emp_expenses_lines_t', 'bill_date', 'payment_request_status', '=', 0);

    }


    $loc = \Session::get('location');
    $compy = \Session::get('companyid');

    $this->data['sum_exp_tot'] = \DB::select("select * from (SELECT 
                    ROUND(SUM(f_emp_expenses_lines_t.balance_amounts),2) as sum_bal_total,
                        ROUND(SUM(f_emp_expenses_lines_t.expense_line_amount),2) as sum_exp_total
                    FROM f_emp_expenses_lines_t
                    left join f_emp_expenses_t on f_emp_expenses_t.expense_id = f_emp_expenses_lines_t.expense_id
                    left join hr_employee_t on hr_employee_t.employee_id=f_emp_expenses_lines_t.employee_id
                    left join tb_users on (tb_users.id =f_emp_expenses_t.created_by)
                    where 1=1 and f_emp_expenses_t.expense_status='APPROVED' and f_emp_expenses_lines_t.payment_status='0' $wh )as v1 where 1=1");


    return view('paymentforinvoice.empexpensepaymentrequesttable', $this->data);
  }

  /* Purpose For Employee Expense PAyment For Invoice Jqgrid*/
  public function getempexpenseRequestforpaymentData()
  {

    $wh1 = '';
    $wh = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $groupname = \Session::get('groupname');

    if (isset($_GET['pagemethod'])) {
      if ($_GET['pagemethod'] == "empexpensepaymentrequestapproval") {
        $wh .= ' and f_emp_expenses_lines_t.payment_request_status=1';
        $wh .= $grid_data = $this->grid_statuscheck('f_emp_expenses_lines_t', 'bill_date', 'payment_request_status', '=', 1);
      } else {
        $wh .= ' and f_emp_expenses_lines_t.payment_request_status=0';
        $wh .= $grid_data = $this->grid_statuscheck('f_emp_expenses_lines_t', 'bill_date', 'payment_request_status', '=', 0);

      }
    }

    $SQL = "select * from(SELECT f_emp_expenses_t.expense_id,
                    f_emp_expenses_t.expense_date,
                    f_emp_expenses_t.expense_no,
                    f_emp_expenses_t.expense_status,
                    f_emp_expenses_t.remarks,
                    f_emp_expenses_lines_t.balance_amounts,
                    f_emp_expenses_lines_t.expense_line_amount,
                    f_emp_expenses_lines_t.bill_no,
                    f_emp_expenses_lines_t.expense_line_id,
                    f_emp_expenses_lines_t.choosefile,
                    concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_name,
                    (case when f_emp_expenses_lines_t.payment_request_status=0 then 'OPEN'  when f_emp_expenses_lines_t.payment_request_status=1 then 'INITIATED' else 'APPROVED' end) as requeststatus,
                    tb_users.username
                    FROM f_emp_expenses_lines_t
                    left join f_emp_expenses_t on f_emp_expenses_t.expense_id = f_emp_expenses_lines_t.expense_id
                    left join hr_employee_t on hr_employee_t.employee_id=f_emp_expenses_lines_t.employee_id
                    left join tb_users on (tb_users.id =f_emp_expenses_t.created_by)
                    where 1=1 and f_emp_expenses_t.expense_status='APPROVED' and f_emp_expenses_lines_t.payment_status='0' $wh ORDER BY f_emp_expenses_t.expense_id DESC)as v1 where 1=1 $wh1 ";

    $result = \DB::select($SQL);

    foreach ($result as $k => $v) {

      $url = \URL::to('empexpensesview/' . $v->expense_id);
      $result[$k]->expense_no = "<a href='$url' target='_blank' class='text-decoration-none text-primary fw-bold'>" . $v->expense_no . "</a>";

         $dw ='';
      if ($v->choosefile != "" || $v->choosefile != null) {
        $dataupload = json_decode($v->choosefile);

        if (!empty($dataupload)) {
          foreach ($dataupload as $k1 => $v1) {


            $poinvid = $v->expense_line_id;

            $dw .= "<a href='../Uploads/expense/$poinvid/$v1' target='_blank' class='badge bg-primary text-white me-1'>" . $v1 . "</a>" . ",";

          }

        $dw1 = trim($dw, ",");
        $result[$k]->choosefile = $dw1;

        }

      }
    }

    return DataTables::of($result)
      ->rawColumns(['expense_no', 'choosefile'])
      ->make(true);

  }

  /*Employee Expense Payment Request Status Update*/
  public function getempexpensepaymentreq($id = null)
  {
    if ($id != "") {
      $status = 0;
      if (isset($_GET['status'])) {
        $status = $_GET['status'];
      }

      $journal = \DB::update("update f_emp_expenses_lines_t set payment_request_status='$status'  WHERE expense_line_id IN ($id)");
      if ($status == 1) {
        $status1 = 'Requested';
      } else {
        $status1 = 'Approved';
      }
      return response()->json(array('status' => 'success', 'message' => "Payment $status1 Successfully", 'id' => $id));
    }
  }



  /* Purpose For PAyment For Invoice Index Call Function*/
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

    $this->data['supplieropt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');

    $this->data['sum_inv_tot'] = \DB::select("select* from(SELECT
                        ROUND(SUM(p_po_invoice_hdr_t.invoice_grand_total),2) as sum_inv_total,
                        ROUND(SUM(p_po_invoice_hdr_t.balance_amount),2) as sum_bal_total
                        FROM  p_po_invoice_hdr_t 
            where 1=1  and p_po_invoice_hdr_t.balance_amount!=0 and p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.payment_request_status='2' and p_po_invoice_hdr_t.company_id=$compy and p_po_invoice_hdr_t.location_id=$loc ) as v1 where 1=1 ");


    $table = \DB::table('p_payments_t')->get();
    $this->data['datas'] = $table;
    return view('paymentforinvoice.table', $this->data);

  }

  /**  purpose invocie balance for payment **/
  public function indexblnc(Request $request)
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


    return view('paymentforinvoice.blncetable', $this->data);
  }

  //  purpose for grid load data
  public function getBlnceInvoicedetailsData()
  {

    $wh = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $org = \Session::get('organization');
    $groupname = \Session::get('groupname');

    $wh .= $grid_data = $this->grid_statuscheck('s_invoice_hdr_t', 'invoice_date', 'invoice_status', '=', "'APPROVED'");


    $SQL = "SELECT
                        s_invoice_hdr_t.invoice_hdr_id ,
                        s_invoice_hdr_t.invoice_number,
                        s_invoice_hdr_t.invoice_date,
                        s_invoice_hdr_t.invoice_status,
                        REPLACE(s_invoice_hdr_t.balance_amount,'-','') as balance_amount ,
                        m_customers_t.customer_name
                        FROM  s_invoice_hdr_t 
                        left join m_customers_t on(
                        m_customers_t.customer_id=s_invoice_hdr_t.`ship_to_customer_id`)
            where 1=1  and s_invoice_hdr_t.balance_amount<0  and s_invoice_hdr_t.invoice_status='APPROVED'  $wh ORDER BY s_invoice_hdr_t.invoice_hdr_id DESC";


    $result = \DB::select($SQL);
    return DataTables::of($result)->make(true);

  }

  /* Purpose For PAyment For Invoice Jqgrid*/
  public function getInvoicedetailsData()
  {
    $wh = '';
    $wh1 = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $org = \Session::get('organization');
    $groupname = \Session::get('groupname');

    $wh .= $grid_data = $this->grid_check('p_po_invoice_hdr_t', 'invoice_date');

    $SQL = "select* from(SELECT
                        p_po_invoice_hdr_t.po_invoice_id as po_invoice_id,
                        p_po_invoice_hdr_t.bill_number,
                        p_po_invoice_hdr_t.invoice_date,
                        p_po_invoice_hdr_t.po_invoice_status,
                        p_po_invoice_hdr_t.po_number as po_id,
                        p_po_invoice_hdr_t.po_date,
                        p_po_invoice_hdr_t.balance_amount,
                        p_po_invoice_hdr_t.invoice_grand_total,
                        p_po_invoice_hdr_t.due_date,
                        p_po_invoice_hdr_t.bal_closure_remarks,
                        DATEDIFF(CURDATE(), p_po_invoice_hdr_t.due_date) as over_due,
                        p_po_invoice_hdr_t.credit_note_balance,
                        p_po_invoice_hdr_t.debit_note_balance,
                        p_po_invoice_hdr_t.attachfile_name,
                        p_po_hdr_t.po_hdr_id,
                        p_po_hdr_t.po_number,
                        p_po_hdr_t.balance_amount AS advance_amount,
                        m_supplier_t.supplier_name,
                        m_supplier_t.msme_status,
                         (case when p_po_invoice_hdr_t.payment_request_status=0 then 'OPEN'  when p_po_invoice_hdr_t.payment_request_status=1 then 'INITIATED' else 'APPROVED' end) as requeststatus
                        FROM  p_po_invoice_hdr_t 
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_po_invoice_hdr_t.`supplier_id`)
                        left join p_po_hdr_t on(
                        p_po_hdr_t.po_hdr_id=p_po_invoice_hdr_t.po_number) 
            where 1=1  and p_po_invoice_hdr_t.balance_amount!=0 and p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.payment_request_status='2' and p_po_invoice_hdr_t.company_id=$compy and p_po_invoice_hdr_t.location_id=$loc $wh ORDER BY p_po_invoice_hdr_t.po_invoice_id DESC) as v1 where 1=1 $wh1";

    $result = \DB::select($SQL);

    foreach ($result as $k => $v) {

      // ---- PO Number link ----
      $poUrl = \URL::to('purchaseorderview/' . $v->po_hdr_id . '?return=purchaseorder');
      $result[$k]->po_number = "<a href='{$poUrl}' target='_blank' class='text-decoration-none text-primary fw-bold'>{$v->po_number}</a>";

      // ---- Invoice Number link ----
      $invUrl = \URL::to('invoiceDataview/' . $v->po_invoice_id . '?return=purchaseinvoice');
      $result[$k]->bill_number = "<a href='{$invUrl}' target='_blank' class='text-decoration-none text-primary fw-bold'>{$v->bill_number}</a>";

      // ---- Attachment links ----

      if (!empty($v->attachfile_name)) {
        $dataupload = json_decode($v->attachfile_name, true);

        // If not array, split by comma
        if (!is_array($dataupload)) {
          $dataupload = explode(",", $v->attachfile_name);
        }

        $links = [];
        foreach ($dataupload as $file) {
          $file = trim($file);
          if ($file == "")
            continue;

          $fileUrl = asset("uploads/purchaseinvoice/PO{$v->po_invoice_id}/{$file}");
          $links[] = "<a href='{$fileUrl}' target='_blank' class='badge bg-primary text-white me-1'>{$file}</a>";
        }

        $result[$k]->attachfile_name = implode(' ', $links);

      }
    }

    return DataTables::of($result)
      ->rawColumns(['po_number', 'bill_number', 'attachfile_name'])
      ->make(true);

  }

  public function create($invid = null, $poid = null, $id = null)
  {

    $this->data['pageModule'] = "paymentforinvoice";
    $this->data[''] = url('paymentforinvoice');
    $table = \DB::table('p_po_invoice_hdr_t')->where('po_invoice_id', $invid)->get();

    $supplierbankdetails = \DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*', 'f_bank_account_lines_t.*')
      ->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
      ->where('f_bank_account_hdr_t.supplierid', $table[0]->supplier_id)->where('f_bank_account_lines_t.active', 'Yes')->get();

    $this->data['row'] = (object) array();
    $this->data['row']->payment_id = "";
    $this->data['row']->payment_number = "";
    $this->data['row']->payment_date = date('Y-m-d');
    $this->data['row']->cheque_date = date('Y-m-d');
    $payamt = $this->data['row']->payment_status = "";
    $this->data['row']->payment_amount = "";
    if (count($supplierbankdetails) > 0) {
      $this->data['row']->supplier_bank_id = $supplierbankdetails[0]->bank_name;
      $this->data['row']->supplier_account_no = $supplierbankdetails[0]->account_number;
      $this->data['row']->supplier_ifsc_code = $supplierbankdetails[0]->ifsc_code;
      $this->data['row']->supplier_account_name = $supplierbankdetails[0]->name_in_account;
      if ($supplierbankdetails[0]->favouring_name == '') {
        $this->data['row']->favouring_name = $supplierbankdetails[0]->name_in_account;
      } else {
        $this->data['row']->favouring_name = $supplierbankdetails[0]->favouring_name;
      }
    }
    $this->data['row']->payment_source = "INVOICE";
    //Advance

    $result = \DB::select("select advance_amount from p_advance_payments_t where po_hdr_id in ($poid)");
    //   dd($result);
    if ($result == null) {
      $this->data['row']->advance_amount = 0;
    } else {
      $advanceamount = $result[0]->advance_amount;
      $this->data['row']->advance_amount = round($advanceamount, 2);
    }
    //Invoice Amount
    $this->data['row']->po_invoice_id = $invid;
    $invamount = \DB::select("select sum(invoice_grand_total) as inv_amt,sum(balance_amount) as balance_amt,sum(debit_note) as debit_note,sum(credit_note) as credit_note,sum(tds_amount) as tds_amt,SUM(paid_amount) as paid_amount from p_po_invoice_hdr_t where po_invoice_id in($invid)");
    $inv_amount = $this->data['row']->invoice_amount = round((($invamount[0]->inv_amt - $invamount[0]->debit_note + $invamount[0]->credit_note)) - $invamount[0]->paid_amount, 2);
    $tds_amount = $this->data['row']->tds_amount = round($invamount[0]->tds_amt, 2);

    //paid
    // $result = \DB::select("select SUM(paid_amount) as paid_amount from p_po_invoice_hdr_t where po_invoice_id in ($invid)");
    $paidamount = $invamount[0]->paid_amount;
    if ($paidamount == null) {
      $this->data['row']->paid_amount = 0;
    } else {
      $this->data['row']->paid_amount = $paidamount;
    }
    //balance
    //  $balamt= \DB::select("select sum(balance_amount) as balance_amt from p_po_invoice_hdr_t where po_invoice_id in($invid)");


    $balance = $this->data['row']->balance_amount = round($invamount[0]->balance_amt, 2);

    $this->data['row']->payment_type_id = "";
    $this->data['row']->payment_reference = "";
    $this->data['row']->cheque_no = "";
    $this->data['row']->remarks = "";
    $this->data['row']->sender_information = "";

    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', '');
    if ($table[0]->supplier_id != "") {
      $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $table[0]->supplier_id);
    } else {
      $this->data['supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', $table[0]->subcontract_supplier_id);
    }
    $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");

    //$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
    $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');
    $this->data['imprest_employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', '');

    $this->data['invoice_balamt'] = $sql = \DB::select("select bill_number,balance_amount,po_invoice_id,debit_note,credit_note from p_po_invoice_hdr_t where po_invoice_id in ($invid)");
    $this->data['credit_balamt'] = $creditsql = \DB::select("select 'invoice' as type,bill_number,balance_amount,po_invoice_id,credit_note,credit_note_balance from p_po_invoice_hdr_t where supplier_id=" . $table[0]->supplier_id . " and credit_note_balance!=0 union all SELECT 'debitcredit' as type,`debitcredit_no` as bill_number,`balance_amount`,`debitcredit_id` as po_invoice_id,0 as debit_note,balance_amount as debit_note_balance  FROM `f_debitcredit_t` WHERE `supplier_id`=" . $table[0]->supplier_id . " and balance_amount!=0 and source_type='CREDIT' and debitcredit_type='SUPPLIER'");
    $this->data['debit_balamt'] = $debitsql = \DB::select("select 'invoice' as type,bill_number,balance_amount,po_invoice_id,debit_note,debit_note_balance from p_po_invoice_hdr_t where supplier_id=" . $table[0]->supplier_id . " and debit_note_balance!=0 union all SELECT 'debitcredit' as type,`debitcredit_no` as bill_number,`balance_amount`,`debitcredit_id` as po_invoice_id,0 as debit_note,balance_amount as debit_note_balance  FROM `f_debitcredit_t` WHERE `supplier_id`=" . $table[0]->supplier_id . " and balance_amount!=0 and source_type='DEBIT' and debitcredit_type='SUPPLIER' union all SELECT 'returntovendor' as type,`return_invoice_number` as bill_number,`balance_amount`,`return_header_id` as po_invoice_id,0 as debit_note,balance_amount as debit_note_balance  FROM `p_return_header_t` WHERE `supplier_id`=" . $table[0]->supplier_id . " and balance_amount!=0");

    $this->data['po_balamt'] = $sql1 = \DB::select("select po_number,balance_amount,advance_amount,po_hdr_id from p_po_hdr_t where supplier_id=" . $table[0]->supplier_id . " and balance_amount!=0 and advance_status=0 and advance_amount!=0 ");
    // dd($sql1);
    $poid = "";
    $poid1 = 0;
    if (count($sql1) < 0) {
      $this->data['poamt'] = 1;
    } else {
      foreach ($sql1 as $k => $v) {
        $poid .= $v->po_hdr_id . ",";
      }
      $poid1 = rtrim($poid, ",");
      $this->data['poamt'] = 0;
    }
    $this->data['row']->po_hdr_id = $poid1;

    $invno = "";
    foreach ($sql as $key => $value) {
      $invno .= $value->bill_number . ",";
    }
    $invoiceno = rtrim($invno, ',');
    $this->data['bill_number'] = $invoiceno;
    $this->data['statement'] = 0;
    return view('paymentforinvoice.form', $this->data);



  }


  //kaviya purpose
  public function createblnce($invid = null, $poid = null, $id = null)
  {
    $this->data['pageModule'] = "paymentforinvoiceblnce";
    $this->data[''] = url('paymentforinvoiceblnce');
    $invid1 = explode(',', $invid);
    $table = \DB::table('s_invoice_hdr_t')->whereIn('invoice_hdr_id', $invid1)->get();

    $customerbankdetails = \DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*', 'f_bank_account_lines_t.*')
      ->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
      ->where('f_bank_account_hdr_t.customerid', $table[0]->ship_to_customer_id)->where('f_bank_account_lines_t.active', 'Yes')->get();

    $this->data['row'] = (object) array();
    $this->data['row']->payment_id = "";
    $this->data['row']->payment_number = "";
    $this->data['row']->payment_date = date('Y-m-d');
    $this->data['row']->cheque_date = date('Y-m-d');
    $payamt = $this->data['row']->payment_status = "";
    $this->data['row']->payment_amount = "";
    if (count($customerbankdetails) > 0) {
      $this->data['row']->customer_bank_id = $customerbankdetails[0]->bank_name;
      $this->data['row']->customer_account_no = $customerbankdetails[0]->account_number;
      $this->data['row']->customer_ifsc_code = $customerbankdetails[0]->ifsc_code;
      $this->data['row']->customer_account_name = $customerbankdetails[0]->name_in_account;
      if ($customerbankdetails[0]->favouring_name == '') {
        $this->data['row']->favouring_name = $customerbankdetails[0]->name_in_account;
      } else {
        $this->data['row']->favouring_name = $customerbankdetails[0]->favouring_name;
      }
    }
    $this->data['row']->payment_source = "INVOICE";
    //Advance


    $balance = $this->data['row']->balance_amount = str_replace("-", "", $table[0]->balance_amount);

    $this->data['row']->payment_type_id = "";
    $this->data['row']->payment_reference = "";
    $this->data['row']->cheque_no = "";
    $this->data['row']->remarks = "";
    $this->data['row']->sender_information = "";

    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', '');

    if ($table[0]->ship_to_customer_id != "") {
      $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', $table[0]->ship_to_customer_id);
    } else {
      $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', '');
    }

    $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");

    //$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
    $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');
    $invno = "";
    $invoice_amt = 0;
    foreach ($table as $key => $value) {
      $invno .= $value->invoice_number . ",";
      $b_a = str_replace("-", "", $value->balance_amount);
      $invoice_amt = $invoice_amt + $b_a;
    }
    $invoiceno = rtrim($invno, ',');
    $this->data['invoice_amt'] = $invoice_amt;
    $this->data['bill_number'] = $invoiceno;
    $this->data['invoice_balamt'] = $table;
    $this->data['so_invoice_id'] = $invid;
    return view('paymentforinvoice.blnceform', $this->data);
  }
  public function createfromstatement($invid = null, $poid = null, $row_id = null)
  {

    $this->data = array('pageModule' => 'paymentforinvoice', 'pageUrl' => url('paymentforinvoice'));
    $table = \DB::table('p_po_invoice_hdr_t')->where('po_invoice_id', $invid)->get();

    $supplierbankdetails = \DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*', 'f_bank_account_lines_t.*')
      ->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
      ->where('f_bank_account_hdr_t.supplierid', $table[0]->supplier_id)->where('f_bank_account_lines_t.active', 'Yes')->get();

    $this->data['row'] = (object) array();
    $this->data['row']->payment_id = "";
    $this->data['row']->payment_number = "";
    $this->data['row']->payment_date = date('Y-m-d');

    $this->data['row']->supplier_bank_id = $supplierbankdetails[0]->bank_name;
    $this->data['row']->supplier_account_no = $supplierbankdetails[0]->account_number;
    $this->data['row']->supplier_ifsc_code = $supplierbankdetails[0]->ifsc_code;
    $this->data['row']->supplier_account_name = $supplierbankdetails[0]->name_in_account;
    $this->data['row']->favouring_name = $supplierbankdetails[0]->favouring_name;
    $this->data['row']->payment_source = "INVOICE";

    $payamt = $this->data['row']->payment_status = "";

    $statement = \DB::select("select * from f_bankstmtupload_t where bankstmt_id='$row_id'");
    //dd($statement);
    $this->data['row']->payment_amount = $statement[0]->withdrawals;
    //Advance

    $this->data['row']->advance_amount = 0;

    //Invoice Amount
    $this->data['row']->po_invoice_id = $invid;
    $invamount = \DB::select("select sum(invoice_grand_total) as inv_amt,sum(tds_amount) as tds_amt from p_po_invoice_hdr_t where po_invoice_id in($invid)");
    $inv_amount = $this->data['row']->invoice_amount = $invamount[0]->inv_amt;
    $tds_amount = $this->data['row']->tds_amount = round($invamount[0]->tds_amt, 2);
    //paid
    $result = \DB::select("select SUM(paid_amount) as paid_amount from p_po_invoice_hdr_t where po_invoice_id in ($invid)");
    $paidamount = $result[0]->paid_amount;
    if ($paidamount == null) {
      $this->data['row']->paid_amount = 0;
    } else {
      $this->data['row']->paid_amount = $paidamount;
    }
    //balance
    $balamt = \DB::select("select sum(balance_amount) as balance_amt from p_po_invoice_hdr_t where po_invoice_id in($invid)");
    $balance = $this->data['row']->balance_amount = $balamt[0]->balance_amt;
    $this->data['row']->payment_type_id = $statement[0]->mode;
    $this->data['row']->payment_reference = $statement[0]->particulars;
    $this->data['row']->cheque_no = "";
    $this->data['row']->remarks = "";

    $this->data['po_balamt'] = $sql1 = \DB::select("select po_number,balance_amount,advance_amount,po_hdr_id from p_po_hdr_t where supplier_id=" . $table[0]->supplier_id . " and balance_amount!=0");
    $poid = "";
    $poid1 = 0;
    if (count($sql1) < 0) {
      $this->data['poamt'] = 1;
    } else {
      foreach ($sql1 as $k => $v) {
        $poid .= $v->po_hdr_id . ",";
      }
      $poid1 = rtrim($poid, ",");
      $this->data['poamt'] = 0;
    }
    $this->data['row']->po_hdr_id = $poid1;
    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', $statement[0]->account_no);
    if ($table[0]->supplier_id != "") {
      $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $table[0]->supplier_id);
    } else {
      $this->data['supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', $table[0]->subcontract_supplier_id);
    }
    $this->data['bank_id'] = $this->jCombo('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', $statement[0]->bank_name);
    //$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
    $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');
    $this->data['imprest_employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
    $this->data['invoice_balamt'] = $sql = \DB::select("select bill_number,balance_amount,po_invoice_id from p_po_invoice_hdr_t where po_invoice_id in ($invid)");

    $invno = "";
    foreach ($sql as $key => $value) {
      $invno .= $value->bill_number . ",";
    }
    $invoiceno = rtrim($invno, ',');
    $this->data['bill_number'] = $invoiceno;
    $this->data['statement'] = 1;
    return view('paymentforinvoice.form', $this->data);
  }

  /*Karthigaa purpose for Save function*/
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
    'existing_file',
    'supplier_balance',
]);

// Normalize "bulk_" keys once
$form = $this->normalizeLineFormKeys($form);

// Build header + lines
$data = $this->validatePost($form, $this->table, 'header');


if ($_POST['payment_number'] == "") {
    $seqno = $this->Seqnoe('PMT-', 'p_payments_t', '', 'payment_count');
    $data['payment_number'] = $seqno[0];
    $data['payment_count'] = $seqno[1];
} else {
    $seqno[0] = $_POST['payment_number'];
}

\DB::beginTransaction();
try {
    if (isset($_POST['balance_amount']) && $_POST['balance_amount'] == 0 && $_POST['payment_source'] == "INVOICE" && $_POST['invoice_amount'] == $_POST['advance_amount']) {
        if ($_POST['payment_source'] == "INVOICE") {
            if ($_POST['po_hdr_id'] != "") {
                $poid = explode(",", $_POST['po_hdr_id']);
                $advance_amount = $_POST['advance_amount'];


                foreach ($poid as $k => $v) {
                    $pohdrid = \DB::select("select  po_hdr_id,balance_amount from p_po_hdr_t where po_hdr_id =$v");
                    if (count($pohdrid) > 0) {
                        $balance_amount1 = $pohdrid[0]->balance_amount;
                    } else {
                        $balance_amount1 = 0;
                    }

                    if ($_POST['popaymentamt'][$v] == "" || $_POST['popaymentamt'][$v] == "0") {
                        $pocurrentbalance_amount = $balance_amount1;
                    } else {
                        $pocurrentbalance_amount = $balance_amount1 - $_POST['popaymentamt'][$v];
                        if ($pocurrentbalance_amount == 0) {
                            \DB::update("Update p_po_hdr_t set balance_amount='$pocurrentbalance_amount',advance_status='1' where po_hdr_id='$v'");
                        } else {
                            \DB::update("Update p_po_hdr_t set balance_amount='$pocurrentbalance_amount' where po_hdr_id='$v'");
                        }
                    }
                }
            }
            /* purpose:update credit balance in invoice*/
            if ($_POST['credit_amount'] != "") {
                foreach ($_POST['creditamount'] as $ck => $cv) {
                    if (isset($_POST['debittype'])) {
                        if ($_POST['debittype'][$ck] == 'invoice') {
                            $creditinv = \DB::select("select  po_invoice_id,credit_note_balance from p_po_invoice_hdr_t where po_invoice_id =$ck");
                            $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='CREDIT' and  invoice_no =$ck and balance_amount!='0'");

                            if (count($credit_balance) > 0) {
                                $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                                $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                                $credit_debit_id = $credit_balance[0]->debitcredit_id;
                                \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");
                            }


                            $creditbalance_amount = $creditinv[0]->credit_note_balance - $cv;
                            \DB::update("Update p_po_invoice_hdr_t set credit_note_balance='$creditbalance_amount' where po_invoice_id='$ck'");

                        } else {
                            $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='CREDIT' and  debitcredit_id =$ck and balance_amount!='0'");

                            if (count($credit_balance) > 0) {
                                $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                                $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                                $credit_debit_id = $credit_balance[0]->debitcredit_id;
                                \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                            }
                        }
                    } else {
                        $creditinv = \DB::select("select  po_invoice_id,credit_note_balance from p_po_invoice_hdr_t where po_invoice_id =$ck");



                        $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='CREDIT' and  invoice_no =$ck and balance_amount!='0'");

                        if (count($credit_balance) > 0) {
                            $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                            $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                            $credit_debit_id = $credit_balance[0]->debitcredit_id;
                            \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                        }


                        $creditbalance_amount = $creditinv[0]->credit_note_balance - $cv;
                        \DB::update("Update p_po_invoice_hdr_t set credit_note_balance='$creditbalance_amount' where po_invoice_id='$ck'");

                    }
                }
            }
            /*end*/
            /* purpose:update debit balance in invoice*/
            if ($_POST['debit_amount'] != "") {
                foreach ($_POST['debitamount'] as $ck => $cv) {
                    if (isset($_POST['debittype'])) {
                        if ($_POST['debittype'][$ck] == 'invoice') {


                            $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='DEBIT' and  invoice_no =$ck and balance_amount!='0'");

                            if (count($credit_balance) > 0) {
                                $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                                $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                                $credit_debit_id = $credit_balance[0]->debitcredit_id;
                                \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                            }



                            $creditinv = \DB::select("select  po_invoice_id,debit_note_balance from p_po_invoice_hdr_t where po_invoice_id =$ck");
                            $debitbalance_amount = $creditinv[0]->debit_note_balance - $cv;
                            \DB::update("Update p_po_invoice_hdr_t set debit_note_balance='$debitbalance_amount' where po_invoice_id='$ck'");
                        } else if ($_POST['debittype'][$ck] == 'returntovendor') {
                            $credit_balance = \DB::select("select  return_header_id,balance_amount,paid_amount from p_return_header_t where  invoice_number =$ck and balance_amount!='0'");

                            if (count($credit_balance) > 0) {
                                $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                                $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                                $credit_debit_id = $credit_balance[0]->return_header_id;
                                \DB::update("Update p_return_header_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where return_header_id='$credit_debit_id'");

                            }



                            $creditinv = \DB::select("select  po_invoice_id,debit_note_balance from p_po_invoice_hdr_t where po_invoice_id =$ck");
                            $debitbalance_amount = $creditinv[0]->debit_note_balance - $cv;
                            \DB::update("Update p_po_invoice_hdr_t set debit_note_balance='$debitbalance_amount' where po_invoice_id='$ck'");

                        } else {
                            $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='DEBIT' and  debitcredit_id =$ck and balance_amount!='0'");

                            if (count($credit_balance) > 0) {
                                $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                                $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                                $credit_debit_id = $credit_balance[0]->debitcredit_id;
                                \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                            }
                        }

                    } else {

                        $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='DEBIT' and  invoice_no =$ck and balance_amount!='0'");

                        if (count($credit_balance) > 0) {
                            $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                            $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                            $credit_debit_id = $credit_balance[0]->debitcredit_id;
                            \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                        }



                        $creditinv = \DB::select("select  po_invoice_id,debit_note_balance from p_po_invoice_hdr_t where po_invoice_id =$ck");
                        $debitbalance_amount = $creditinv[0]->debit_note_balance - $cv;
                        \DB::update("Update p_po_invoice_hdr_t set debit_note_balance='$debitbalance_amount' where po_invoice_id='$ck'");

                    }
                }
            }
            /*end*/

            $invoiceid = $_POST['po_invoice_id'];
            $invhdrid = \DB::select("select  po_invoice_id,balance_amount,invoice_grand_total,paid_amount from p_po_invoice_hdr_t where po_invoice_id in ($invoiceid) and balance_amount>0");
            $payment_amount = $_POST['payment_amount'];
            $total_amount = (float) $_POST['debit_amount'] + (float) $_POST['advance_amount'];
            //$_POST['paymentamt'][$invid];
            foreach ($invhdrid as $tkey => $tvalue) {
                $balance_amount = $tvalue->balance_amount;
                $invoice_grand_total = $tvalue->invoice_grand_total;
                $paid_amount = $tvalue->paid_amount;
                $invid = $tvalue->po_invoice_id;
                $current_balance_amount = $balance_amount - $total_amount - $_POST['paymentamt'][$invid];

                if ($current_balance_amount <= 0) {
                    $bal = 0;
                    $balance_amount = $balance_amount - $_POST['paymentamt'][$invid];
                    //$receipt_amount=$receipt_amount-$balance_amount;
                    $total_amount = $total_amount - $balance_amount;
                    $paid_amount = $invoice_grand_total;

                    $pno = $data['payment_number'];
                    $pdate = $_POST['payment_date'];

                    \DB::update("Update p_po_invoice_hdr_t set paid_amount='$paid_amount',balance_amount='0',payment_status='1',payment_number='$pno',payment_date='$pdate' where po_invoice_id='$invid'");
                } else {


                    $paid_amount = $paid_amount + $total_amount + $_POST['paymentamt'][$invid];


                    \DB::update("Update p_po_invoice_hdr_t set paid_amount='$paid_amount',balance_amount='$current_balance_amount' where po_invoice_id='$invid'");
                    break;
                }
            }




        }

    } else {
        // dd("ghg"); 
        $paymenttype = $_POST['payment_type_id'];
        if ($paymenttype == 'CHEQUE') {
            //Cheque no count update
            $chequeno = $_POST['cheque_no'];
            $accno = $_POST['account_no'];
            $chequeupdate = \DB::update("UPDATE f_bank_cheque_lines_t set cheque_count='$chequeno' where bank_cheque_hdr_id='$accno'");
        }
        /* purpose:update balance amount in po*/
        if ($_POST['payment_source'] == "INVOICE" || $_POST['payment_source'] == "EXPENSE") {
            if (isset($_POST['po_invoice_id']) && $_POST['po_invoice_id'] != "") {
                $inviocebrkup = implode(",", $_POST['paymentamt']);
                $data['invoice_brkup'] = $inviocebrkup;
            }
            //dd($_POST);
            if (isset($_POST['po_hdr_id']) && $_POST['po_hdr_id'] != "") {
                $advanbrkup = implode(",", $_POST['popaymentamt']);
                $data['advance_brkup'] = $advanbrkup;
            }
            if (isset($_POST['po_hdr_id']) && $_POST['po_hdr_id'] != "") {
                $poid = explode(",", $_POST['po_hdr_id']);
                $advance_amount = $_POST['advance_amount'];
                foreach ($poid as $k => $v) {
                    $pohdrid = \DB::select("select  po_hdr_id,balance_amount from p_po_hdr_t where po_hdr_id =$v");
                    if (count($pohdrid) > 0) {
                        $balance_amount = $pohdrid[0]->balance_amount;
                    } else {
                        $balance_amount = 0;
                    }
                    if ($_POST['popaymentamt'][$v] == "" || $_POST['popaymentamt'][$v] == "0") {
                        $pocurrentbalance_amount = $balance_amount;
                    } else {
                        $pocurrentbalance_amount = $balance_amount - $_POST['popaymentamt'][$v];
                        if ($pocurrentbalance_amount == 0) {
                            \DB::update("Update p_po_hdr_t set balance_amount='$pocurrentbalance_amount',advance_status='1' where po_hdr_id='$v'");
                        } else {
                            \DB::update("Update p_po_hdr_t set balance_amount='$pocurrentbalance_amount' where po_hdr_id='$v'");
                        }
                    }
                }

            }


            /* purpose:update credit balance in invoice*/
            if ($_POST['credit_amount'] != "") {
                $creditbrkup = implode(",", $_POST['creditamount']);
                $data['credit_brkup'] = $creditbrkup;

                foreach ($_POST['creditamount'] as $ck => $cv) {
                    if (isset($_POST['debittype'])) {
                        if ($_POST['debittype'][$ck] == 'invoice') {

                            $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='CREDIT' and  invoice_no =$ck and balance_amount!='0'");

                            if (count($credit_balance) > 0) {
                                $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                                $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                                $credit_debit_id = $credit_balance[0]->debitcredit_id;
                                \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                            }




                            $creditinv = \DB::select("select  po_invoice_id,credit_note_balance from p_po_invoice_hdr_t where po_invoice_id =$ck");
                            $creditbalance_amount = $creditinv[0]->credit_note_balance - $cv;
                            \DB::update("Update p_po_invoice_hdr_t set credit_note_balance='$creditbalance_amount' where po_invoice_id='$ck'");
                        } else {
                            $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='CREDIT' and  debitcredit_id =$ck and balance_amount!='0'");

                            if (count($credit_balance) > 0) {
                                $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                                $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                                $credit_debit_id = $credit_balance[0]->debitcredit_id;
                                \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                            }

                        }
                    } else {
                        $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='CREDIT' and  invoice_no =$ck and balance_amount!='0'");

                        if (count($credit_balance) > 0) {
                            $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                            $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                            $credit_debit_id = $credit_balance[0]->debitcredit_id;
                            \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                        }




                        $creditinv = \DB::select("select  po_invoice_id,credit_note_balance from p_po_invoice_hdr_t where po_invoice_id =$ck");
                        $creditbalance_amount = $creditinv[0]->credit_note_balance - $cv;
                        \DB::update("Update p_po_invoice_hdr_t set credit_note_balance='$creditbalance_amount' where po_invoice_id='$ck'");

                    }
                }
            }
            /*end*/
            /* purpose:update credit balance in invoice*/
            if ($_POST['debit_amount'] != "") {
                $debitbrkup = implode(",", $_POST['debitamount']);
                $data['debit_brkup'] = $debitbrkup;

                foreach ($_POST['debitamount'] as $ck => $cv) {
                    if (isset($_POST['debittype'])) {
                        if ($_POST['debittype'][$ck] == 'invoice') {

                            $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='DEBIT' and  invoice_no =$ck and balance_amount!='0'");

                            if (count($credit_balance) > 0) {
                                $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                                $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                                $credit_debit_id = $credit_balance[0]->debitcredit_id;
                                \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                            }



                            $creditinv = \DB::select("select  po_invoice_id,debit_note_balance from p_po_invoice_hdr_t where po_invoice_id =$ck");
                            $debitbalance_amount = $creditinv[0]->debit_note_balance - $cv;
                            \DB::update("Update p_po_invoice_hdr_t set debit_note_balance='$debitbalance_amount' where po_invoice_id='$ck'");
                        } else if ($_POST['debittype'][$ck] == 'returntovendor') {

                            $credit_balance = \DB::select("select  return_header_id,balance_amount,paid_amount from p_return_header_t where  invoice_number =$ck and balance_amount!='0'");

                            if (count($credit_balance) > 0) {
                                $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                                $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                                $credit_debit_id = $credit_balance[0]->return_header_id;
                                \DB::update("Update p_return_header_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where return_header_id='$credit_debit_id'");

                            }



                            $creditinv = \DB::select("select  po_invoice_id,debit_note_balance from p_po_invoice_hdr_t where po_invoice_id =$ck");
                            $debitbalance_amount = $creditinv[0]->debit_note_balance - $cv;
                            \DB::update("Update p_po_invoice_hdr_t set debit_note_balance='$debitbalance_amount' where po_invoice_id='$ck'");
                        } else {
                            $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='DEBIT' and  debitcredit_id =$ck and balance_amount!='0'");

                            if (count($credit_balance) > 0) {
                                $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                                $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                                $credit_debit_id = $credit_balance[0]->debitcredit_id;
                                \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                            }
                        }
                    } else {
                        $credit_balance = \DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='DEBIT' and  invoice_no =$ck and balance_amount!='0'");

                        if (count($credit_balance) > 0) {
                            $credit_balance_amount = $credit_balance[0]->balance_amount - $cv;
                            $credit_paid_amount = $credit_balance[0]->paid_amount + $cv;
                            $credit_debit_id = $credit_balance[0]->debitcredit_id;
                            \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");

                        }



                        $creditinv = \DB::select("select  po_invoice_id,debit_note_balance from p_po_invoice_hdr_t where po_invoice_id =$ck");
                        $debitbalance_amount = $creditinv[0]->debit_note_balance - $cv;
                        \DB::update("Update p_po_invoice_hdr_t set debit_note_balance='$debitbalance_amount' where po_invoice_id='$ck'");

                    }
                }
                /*end*/
            }
            /*end*/
            if ($_POST['payment_source'] == "EXPENSE") {
                $expbrkup = implode(",", $_POST['paymentamt']);
                // dd($expbrkup);
                $data['expense_brkup'] = $expbrkup;
                $data['po_invoice_id'] = '';

            }
            //dd($data);
            $id = $this->model->insertRow($data);
            /** Purpose For Balance Amount And Paid Amount Update In Expense Table**/
            if ($_POST['payment_source'] == "EXPENSE") {
                $expenseid = $_POST['reference_id'];
                $expensehdrid = \DB::select("select  expense_id,balance_amount,expense_amount,paid_amount from f_expenses_t where expense_id in ($expenseid)");
                $payment_amount = $_POST['payment_amount'];
                $total_amount = (float) $_POST['debit_amount'] + (float) $_POST['advance_amount'];
                foreach ($expensehdrid as $tkey => $tvalue) {
                    $balance_amount = $tvalue->balance_amount;
                    $invoice_grand_total = $tvalue->expense_amount;
                    $paid_amount = $tvalue->paid_amount;
                    $expense_id = $tvalue->expense_id;
                    $current_balance_amount = $balance_amount - $_POST['paymentamt'][$expense_id] - $total_amount;
                    //$paid_amount=$paid_amount+$_POST['paymentamt'][$expense_id];
                    if ($current_balance_amount <= 0) {

                        $bala = $balance_amount - $_POST['paymentamt'][$expense_id];
                        $total_amount = $total_amount - $bala;
                        $paid_amount = $paid_amount + $balance_amount;

                        $pno = $data['payment_number'];
                        $pdate = $_POST['payment_date'];

                        \DB::update("Update f_expenses_t set paid_amount='$paid_amount',balance_amount='0',payment_status='1',payment_number='$pno',payment_date='$pdate' where expense_id='$expense_id'");
                    } else {

                        //$bala=$balance_amount-$_POST['paymentamt'][$expense_id];
                        //$total_amount=$total_amount-$bala;
                        $paid_amount = $paid_amount + $_POST['paymentamt'][$expense_id] + $total_amount;
                        $total_amount = 0;
                        \DB::update("Update f_expenses_t set paid_amount='$paid_amount',balance_amount='$current_balance_amount' where expense_id='$expense_id'");
                    }
                }

            }

            //dd($_POST);
            /** Purpose For Balance Amount And Paid Amount Update**/
            if ($_POST['payment_source'] != "EXPENSE" && $_POST['payment_source'] != "IMPREST" && $_POST['payment_source'] != "PROMOTIONAL") {
                $invoiceid = $_POST['po_invoice_id'];
                $invhdrid = \DB::select("select  po_invoice_id,balance_amount,invoice_grand_total,paid_amount from p_po_invoice_hdr_t where po_invoice_id in ($invoiceid) and balance_amount>0");
                $payment_amount = $_POST['payment_amount'];
                $total_amount = (float) $_POST['debit_amount'] + (float) $_POST['advance_amount'];
                // $_POST['paymentamt'][$invid];
                foreach ($invhdrid as $tkey => $tvalue) {
                    $balance_amount = $tvalue->balance_amount;
                    $invoice_grand_total = $tvalue->invoice_grand_total;
                    $paid_amount = $tvalue->paid_amount;
                    $invid = $tvalue->po_invoice_id;
                    $current_balance_amount = $balance_amount - $total_amount - $_POST['paymentamt'][$invid];

                    if ($current_balance_amount <= 0) {
                        $bal = 0;
                        $balance_amount = $balance_amount - $_POST['paymentamt'][$invid];
                        //$receipt_amount=$receipt_amount-$balance_amount;
                        $total_amount = $total_amount - $balance_amount;
                        $paid_amount = $invoice_grand_total;

                        $pno = $data['payment_number'];
                        $pdate = $_POST['payment_date'];

                        \DB::update("Update p_po_invoice_hdr_t set paid_amount='$paid_amount',balance_amount='0',payment_status='1',payment_number='$pno',payment_date='$pdate' where po_invoice_id='$invid'");
                    } else {


                        $paid_amount = $paid_amount + $total_amount + $_POST['paymentamt'][$invid];


                        \DB::update("Update p_po_invoice_hdr_t set paid_amount='$paid_amount',balance_amount='$current_balance_amount' where po_invoice_id='$invid'");
                        break;
                    }
                }
                $old_balance = $_POST['balance_amount'];
                $payment = $_POST['payment_amount'];
                $current_balance = $old_balance - $payment;
                \DB::update("UPDATE p_payments_t set balance_amount='$current_balance' where payment_id='$id'");
            } else {
                \DB::update("UPDATE p_payments_t set sender_information='" . $_POST['sender_information'] . "' where payment_id='$id'");
            }

            /** Purpose For Jouranl Entry Insert**/
            $paymt_no = "PAYMENT-" . $data['payment_number'];
            $paydate = $_POST['payment_date'];
            $org = \Session::get('organization');
            $loc = \Session::get('location');
            $compy = \Session::get('companyid');
            //Journal Header Insert
            $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$paymt_no','PAYMENT','$paydate','$id','APPROVED','$compy','$loc','$org')");
            $jid = DB::getPdo()->lastInsertId();

            if ($_POST['payment_source'] == "PROMOTIONAL") {
                $supplier_acc = \DB::table('m_customers_t')->where('customer_id', $_POST['supplier_id'])->get();
                $supplier_acc = $supplier_acc[0]->account_structure_id;
                $supp_name = "CUSTOMER";
                $emp_referenceid = "";
                $empl_name = "";
            } else if ($_POST['payment_source'] == "IMPREST") {

                $supplier_acc = \DB::table('f_account_setting_t')->where('module_name', 'hrms')->get();
                $supplier_acc = $supplier_acc[0]->imprest_account_id;
                $supp_name = "EMPLOYEE";
                $emp_referenceid = "";
                $empl_name = "";
            } else if ($_POST['payment_source'] == "EXPENSE") {
                if ($_POST['payment_type_id'] == "IMPREST") {
                    $supplier_acc = \DB::table('f_account_setting_t')->where('module_name', 'hrms')->get();
                    $supplier_acc = $supplier_acc[0]->imprest_account_id;
                    $emp_referenceid = $_POST['imprest_employee_id'];
                    $empl_name = "EMPLOYEE";
                    $expensetab = \DB::table('f_expenses_t')->where('expense_id', $_POST['reference_id'])->get();
                    if ($expensetab[0]->expense_type == "SUPPLIER") {
                        $supplier_acc = \DB::table('m_supplier_t')->where('supplier_id', $_POST['supplier_id'])->get();
                        $supplier_acc = $supplier_acc[0]->account_structure_id;
                        $referenceid = $_POST['supplier_id'];
                        $supp_name = "SUPPLIER";
                    } else if ($expensetab[0]->expense_type == "CUSTOMER") {
                        $supplier_acc = \DB::table('m_customers_t')->where('customer_id', $_POST['customer_id'])->get();
                        $supplier_acc = $supplier_acc[0]->account_structure_id;
                        $referenceid = $_POST['customer_id'];
                        $supp_name = "CUSTOMER";
                    } else {
                        $supplier_acc = \DB::table('f_account_setting_t')->where('module_name', 'hrms')->get();
                        $supplier_acc = $supplier_acc[0]->imprest_account_id;
                        $referenceid = $_POST['employee_id'];
                        $supp_name = "EMPLOYEE";
                    }
                } else {
                    $expensetab = \DB::table('f_expenses_t')->where('expense_id', $_POST['reference_id'])->get();
                    if ($expensetab[0]->expense_type == "SUPPLIER") {
                        $supplier_acc = \DB::table('m_supplier_t')->where('supplier_id', $_POST['supplier_id'])->get();
                        $supplier_acc = $supplier_acc[0]->account_structure_id;
                        $referenceid = $_POST['supplier_id'];
                        $supp_name = "SUPPLIER";
                        $emp_referenceid = "";
                        $empl_name = "";
                    } else if ($expensetab[0]->expense_type == "CUSTOMER") {
                        $supplier_acc = \DB::table('m_customers_t')->where('customer_id', $_POST['customer_id'])->get();
                        $supplier_acc = $supplier_acc[0]->account_structure_id;
                        $referenceid = $_POST['customer_id'];
                        $supp_name = "CUSTOMER";
                        $emp_referenceid = "";
                        $empl_name = "";
                    } else {
                        $supplier_acc = \DB::table('f_account_setting_t')->where('module_name', 'hrms')->get();
                        $supplier_acc = $supplier_acc[0]->imprest_account_id;
                        $referenceid = $_POST['employee_id'];
                        $supp_name = "EMPLOYEE";
                        $emp_referenceid = "";
                        $empl_name = "";
                    }
                }
            } else {
                $supplier_acc = \DB::table('m_supplier_t')->where('supplier_id', $_POST['supplier_id'])->get();
                $supplier_acc = $supplier_acc[0]->account_structure_id;
                $referenceid = $_POST['supplier_id'];
                $supp_name = "SUPPLIER";
                if ($_POST['payment_type_id'] == "IMPREST") {
                    $emp_referenceid = $_POST['imprest_employee_id'];
                    $empl_name = "EMPLOYEE";
                } else {
                    $emp_referenceid = "";
                    $empl_name = "";
                }
            }

            //Journal lINES Insert       
            $tkey = 0;
            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
            $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
            $journal_lines_data[$tkey]['reference_source'] = $supp_name;
            $journal_lines_data[$tkey]['reference_id'] = $referenceid;
            $journal_lines_data[$tkey]['account_id'] = $supplier_acc;
            $journal_lines_data[$tkey]['debit_amount'] = $_POST['payment_amount'];
            $journal_lines_data[$tkey]['credit_amount'] = '';
            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
            
            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
            $tkey++;
            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
            $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
            $journal_lines_data[$tkey]['reference_source'] = $empl_name;
            $journal_lines_data[$tkey]['reference_id'] = $emp_referenceid;
            $journal_lines_data[$tkey]['account_id'] = $_POST['account_code_id'];
            $journal_lines_data[$tkey]['debit_amount'] = '';
            $journal_lines_data[$tkey]['credit_amount'] = $_POST['payment_amount'];
            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
            
            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
           
            \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

            /*end*/
        }
    }

    /* bank charges auto payment generation */
    $pay_type = $_POST['payment_type_id'];
    $bnk_id = $_POST['bank_id'];
    $pmt_amt = $_POST['payment_amount'];
    $bank_charges = \DB::select("SELECT * FROM f_account_bankcharges_t WHERE from_value <= '$pmt_amt' AND to_value >= '$pmt_amt' AND bank_id = '$bnk_id' AND payment_type_id = '$pay_type' AND active = 'Yes'");
    if ($pay_type == "NEFT" && count($bank_charges) > 0) {

        $bcseqno = $this->Seqnoe('PMT-', 'p_payments_t', '', 'payment_count');
        $bcdata['payment_number'] = $bcseqno[0];
        $bcdata['payment_count'] = $bcseqno[1];

        $bcdata['payment_date'] = $_POST['payment_date'];
        $bcdata['payment_amount'] = $bank_charges[0]->bank_charges;
        $bcdata['account_code_id'] = '47';
        $bcdata['payment_type_id'] = 'ONLINE';
        $bcdata['remarks'] = 'CHARGES FOR PORD CUSTOMER PAYMENT :' . $data['payment_number'];
        $bcdata['payment_status'] = 'PARTIALLY PAID';
        $bcdata['account_no'] = '2';
        $bcdata['bank_id'] = '2';
        $bcdata['company_id'] = $comp = \Session::get('companyid');
        $bcdata['location_id'] = \Session::get('location');
        $bcdata['organization_id'] = \Session::get('organization');
        $bcdata['created_by'] = \Session::get('id');
        $bcdata['created_at'] = date('Y-m-d H:i:s');
        $bcdata['last_updated_by'] = \Session::get('id');
        $bcdata['updated_at'] = date('Y-m-d H:i:s');
        $bcdata['payment_source'] = 'DIRECTPAYMENT';
        $bcdata['batch_status'] = 'INITIATED';
        $bcdata['direct_accountcodeid'] = '377';

        $bcid = $this->model->insertRow($bcdata);

        /*journal insert*/

        $bcpaymt_no = "DIRECTPAYMENT-" . $bcdata['payment_number'];
        $bcpaydate = $_POST['payment_date'];
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        //Journal Header Insert
        $bcjournalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$bcpaymt_no','DIRECTPAYMENT','$bcpaydate','$bcid','APPROVED','$compy','$loc','$org')");
        $bcjid = DB::getPdo()->lastInsertId();

        //Journal lINES Insert       
        $bctkey = 0;
        $bcjournal_lines_data[$bctkey]['journal_entry_id'] = $bcjid;
        $bcjournal_lines_data[$bctkey]['journal_date'] = $bcpaydate;
        $bcjournal_lines_data[$bctkey]['reference_source'] = '';
        $bcjournal_lines_data[$bctkey]['reference_id'] = '0';
        $bcjournal_lines_data[$bctkey]['account_id'] = '377';
        $bcjournal_lines_data[$bctkey]['debit_amount'] = $bank_charges[0]->bank_charges;
        $bcjournal_lines_data[$bctkey]['credit_amount'] = '';
        $bcjournal_lines_data[$bctkey]['line_no'] = $bctkey + 1;
        $bcjournal_lines_data[$bctkey]['created_by'] = \Session::get('id');
        $bcjournal_lines_data[$bctkey]['created_at'] = date('Y-m-d H:i:s');
        $bcjournal_lines_data[$bctkey]['last_updated_by'] = \Session::get('id');
        $bcjournal_lines_data[$bctkey]['updated_at'] = date('Y-m-d H:i:s');
        ;
        $bcjournal_lines_data[$bctkey]['location_id'] = \Session::get('companyid');
        $bcjournal_lines_data[$bctkey]['company_id'] = \Session::get('location');
        $bctkey++;
        $bcjournal_lines_data[$bctkey]['journal_entry_id'] = $bcjid;
        $bcjournal_lines_data[$bctkey]['journal_date'] = $bcpaydate;
        $bcjournal_lines_data[$bctkey]['reference_source'] = '';
        $bcjournal_lines_data[$bctkey]['reference_id'] = '0';
        $bcjournal_lines_data[$bctkey]['account_id'] = '47';
        $bcjournal_lines_data[$bctkey]['debit_amount'] = '';
        $bcjournal_lines_data[$bctkey]['credit_amount'] = $bank_charges[0]->bank_charges;
        $bcjournal_lines_data[$bctkey]['line_no'] = $bctkey + 1;
        $bcjournal_lines_data[$bctkey]['created_by'] = \Session::get('id');
        $bcjournal_lines_data[$bctkey]['created_at'] = date('Y-m-d H:i:s');
        $bcjournal_lines_data[$bctkey]['last_updated_by'] = \Session::get('id');
        $bcjournal_lines_data[$bctkey]['updated_at'] = date('Y-m-d H:i:s');
        ;
        $bcjournal_lines_data[$bctkey]['location_id'] = \Session::get('companyid');
        $bcjournal_lines_data[$bctkey]['company_id'] = \Session::get('location');
        //   dd($journal_lines_data);
        \DB::table('f_journal_entry_lines_t')->insert($bcjournal_lines_data);

        /*end*/


    }
    /* end */


    if ($_POST['payment_source'] == "EXPENSE") {
        if ($_POST['employee_id'] != 0 && $_POST['employee_id'] != '') {
            $empid = $_POST['employee_id'];


        }

    }

    //  mail for employee function start - m vignesh

    if ($_POST['payment_type_id'] != "IMPREST" && $_POST['payment_type_id'] != "CASH") {
        if ($_POST['payment_source'] == "EXPENSE") {
            if ($_POST['employee_id'] != 0 && $_POST['employee_id'] != '') {
                $em_id = $_POST['employee_id'];
            } else {
                $em_id = '302';
            }
            $emp_type = \DB::select("select employee_type,group_type from hr_employee_t where employee_id='$em_id'");
            if ($emp_type[0]->group_type == '14') {
                $emp = \Session::get('id');
                $to_person = $_POST['employee_id'];
                $exp_amt = $_POST['payment_amount'];
                $total_amt = $_POST['invoice_amount'];
                $purpos = $_POST['remarks'];
                $exp_date = $_POST['payment_date'];

                if ($purpos != '') {

                    $purpose = " against $purpos";

                } else {

                    $purpose = "";
                }
                //dd($to_person);
                // Get the employee's email and name
                $user_mail = \DB::select("select user_mail,first_name from tb_users where employee_id='$to_person'");
                $prefix = \DB::select("select prefix from hr_employee_t where employee_id='$to_person'");
                if ($prefix[0]->prefix == "1") {
                    $pre = "Mr.";
                } else {
                    $pre = "Ms.";
                }
                if (!empty($user_mail) && !empty($user_mail[0]->user_mail)) {
                    $created_mail = $user_mail[0]->user_mail;
                    $emp_name = $user_mail[0]->first_name;
                } else {
                    $created_mail = "aspire@jrkresearch.com";
                    $emp_name = $user_mail[0]->first_name;
                }
                // dd($emp_name);
                // Get reporting manager email
                $man_id = \DB::select("select reporting_manager,first_name from hr_employee_t where employee_id='$emp'");
                $managr_id = $man_id[0]->reporting_manager;
                $regards_name = $man_id[0]->first_name;
                $managr_mail = \DB::select("select user_mail from tb_users where employee_id='$managr_id' and group_id !='15'");

                if (!empty($managr_mail) && !empty($managr_mail[0]->user_mail)) {
                    $manr_mail = $managr_mail[0]->user_mail;

                } else {

                    $manr_mail = "aspire@jrkresearch.com";
                }

                // Email details
                //$from_mail = !empty(\Session::get('user_email')) ? \Session::get('user_email') : "aspire@jrkresearch.com";
                $from_mail = "expenses@jrkresearch.com";
                $sub = "Payment Initiated - for $pre $emp_name";
                $to_mail = $created_mail;
                $cc_mail = "expenses@jrkresearch.com,payroll@jrkresearch.com";
                $cur_date = date("Y-m-d");
                $groupname = \DB::select("select group_type from hr_employee_t where employee_id='$to_person'");
                if ($groupname[0]->group_type == "14") {
                    $com_mail = "payroll@jrkresearch.com";
                } else {
                    $com_mail = "poornima_s@jrkresearch.com";
                }
                /*$msg = "<p>Dear $emp_name,<br><br>A payment of Rs.$exp_amt/- has been processed for expenses amounting to Rs.$total_amt/- dated $exp_date $purpose.<br>Credit with in your account next 2-3 working days.<br><br>Note: The disbursed amount may differ from the incurred expenses by the policy. If you need any clarification, please do not hesitate to contact $com_mail.<br><br>Regards, <br>Accounts Team";*/
                $msg = "<p>Dear $pre $emp_name,<br><br>A payment of Rs.$exp_amt/- has been processed on dated $exp_date $purpose.<br><br>The credited amount will be available in your account within the next 2-3 working days.<br><br>Note: The disbursed amount may differ from the incurred expenses by the policy. If you need any clarification, please do not hesitate to contact $com_mail.<br><br>Regards, <br>Accounts Team";
                // Send the email
                \Mail::send([], [], function ($message) use ($from_mail, $to_mail, $cc_mail, $sub, $msg) {
                    $message->from($from_mail)
                        ->to($to_mail)
                        ->cc(explode(',', $cc_mail))
                        ->subject($sub)
                        ->setBody($msg, 'text/html');
                });
            }
        }

    }
    // End of email section

    \DB::commit();
    return response()->json(array('status' => 'success', 'message' => 'Payment Saved', 'id' => $id));
} catch (\Illuminate\Database\QueryException $e) {
    $message = explode('(', $e->getMessage());
    $dbCode = rtrim($message[0], ']');
    $dbCode = trim($dbCode, '[');
    \DB::rollback();
    return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
}

  }

  public function saveblnce(Request $request)
  {

    $id = '';
    $data = $this->validatePost($request->all(), $this->table, 'header');
    /* Purpose for Auto Number*/
    if ($_POST['payment_number'] == "") {
      $seqno = $this->Seqnoe('PMT-', 'p_payments_t', '', 'payment_count');
      $data['payment_number'] = $seqno[0];
      $data['payment_count'] = $seqno[1];
    } else {
      $seqno[0] = $_POST['payment_number'];
    }
    /*End*/

    \DB::beginTransaction();
    try {
      $so_invoice = explode(",", $_POST['so_invoice_id']);
      foreach ($so_invoice as $k => $v) {
        $invociehdr = \DB::select("select  invoice_hdr_id,balance_amount,paid_amount from s_invoice_hdr_t where invoice_hdr_id =$v");
        // $balance_amount=str_replace("-","",$invociehdr[0]->balance_amount);;
        $paid_amount = $_POST['paymentamt'][$v];
        if ($_POST['paymentamt'][$v] == '') {
          $_POST['paymentamt'][$v] = 0;
        }
        $current_balance_amount = $invociehdr[0]->balance_amount + $_POST['paymentamt'][$v];
        $paid_amount = $paid_amount + $_POST['paymentamt'][$v];
        \DB::update("Update s_invoice_hdr_t set paid_amount='$paid_amount',balance_amount='$current_balance_amount' where invoice_hdr_id='$v'");

      }
      $id = $this->model->insertRow($data);
      $paymt_no = "PAYMENT-" . $data['payment_number'];
      $paydate = $_POST['payment_date'];
      $org = \Session::get('organization');
      $loc = \Session::get('location');
      $compy = \Session::get('companyid');
      $pay_name = "SALESINVOICE";

      $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$paymt_no','$pay_name','$paydate','$id','APPROVED','$compy','$loc','$org')");
      $jid = DB::getPdo()->lastInsertId();

      $tkey = 0;
      $supp_name = "CUSTOMER";
      $referenceid = $_POST['customer_id'];
      $supplier_acc = \DB::table('m_customers_t')->where('customer_id', $_POST['customer_id'])->get();
      $supplier_acc = $supplier_acc[0]->account_structure_id;
      $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
      $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
      $journal_lines_data[$tkey]['reference_source'] = $supp_name;
      $journal_lines_data[$tkey]['reference_id'] = $referenceid;
      $journal_lines_data[$tkey]['account_id'] = $supplier_acc;
      $journal_lines_data[$tkey]['debit_amount'] = $_POST['payment_amount'];
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
      $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
      $journal_lines_data[$tkey]['reference_source'] = "";
      $journal_lines_data[$tkey]['reference_id'] = '';
      $journal_lines_data[$tkey]['account_id'] = $_POST['account_code_id'];
      $journal_lines_data[$tkey]['debit_amount'] = '';
      $journal_lines_data[$tkey]['credit_amount'] = $_POST['payment_amount'];
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
      return response()->json(array('status' => 'success', 'message' => 'Payment Saved', 'id' => $id));
    } catch (\Illuminate\Database\QueryException $e) {
      $message = explode('(', $e->getMessage());
      $dbCode = rtrim($message[0], ']');
      $dbCode = trim($dbCode, '[');
      \DB::rollback();
      return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
    }

  }
  /*Karthigaa Purpose for Default Cheque Number*/
  function getchequeno($accno = null)
  {
    $sql = \DB::SELECT("select cheque_from_no,cheque_to_no from f_bank_cheque_lines_t where bank_cheque_hdr_id = '$accno' and cheque_status='ACTIVE'");
    $cheqno = \DB::select("SELECT cheque_no FROM p_payments_t WHERE account_no='$accno' and payment_type_id = 'cheque' order by payment_id desc limit 1");
    if (count($sql) > 0) {
      if (count($cheqno) > 0) {
        $data1 = (int) $cheqno[0]->cheque_no + 1;
        $end = $sql[0]->cheque_to_no;
      } else {
        $data1 = $sql[0]->cheque_from_no;
        $end = $sql[0]->cheque_to_no;
      }
    } else {
      $end = "";
      $data1 = "";
    }
    // dd($end);
    $data['chequeno'] = $data1;
    $data['endcheque'] = $end;

    return $data;
  }
  /*End*/
  public function getadvance($invid = null)
  {
    $sql = \DB::select("select p_po_invoice_hdr_t.bill_number,
                                sum(p_po_invoice_hdr_t.balance_amount) as balance_amount,
                                p_po_invoice_hdr_t.po_invoice_id,
                                sum(p_po_invoice_hdr_t.invoice_grand_total) as invoice_grand_total,
                                p_advance_payments_t.advance_amount
                                from p_po_invoice_hdr_t
                                left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id=p_po_invoice_hdr_t.po_number)
                                left join p_advance_payments_t on (p_advance_payments_t.po_hdr_id=p_po_hdr_t.po_hdr_id)
                                where p_po_invoice_hdr_t.po_invoice_id in ($invid)");

    if (count($sql) > 0) {
      $advance['invoice_grand_total'] = $sql[0]->invoice_grand_total;
      $advance['balance_amount'] = $sql[0]->balance_amount;
      $advance['advance_amount'] = $sql[0]->advance_amount;
      $advance['advance_deduction'] = round($sql[0]->invoice_grand_total - $sql[0]->advance_amount, 2);
    }
    // dd($advance);
    return $advance;
  }
  public function view($id = null)
  {
    $this->data['payment_id'] = $id;
    $this->data['row'] = $table = DB::table('p_payments_t')->select('p_payments_t.payment_number', 'p_payments_t.payment_id', 'tb_users.id', 'p_payments_t.*')
      ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_payments_t.supplier_id')
      ->leftJoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'p_payments_t.organization_id')
      ->leftJoin('tb_users', 'tb_users.id', '=', 'm_supplier_t.created_by')
      ->where('payment_id', $id)->get();
    $this->data['supplier_name'] = $this->idname('supplier_name', 'm_supplier_t', 'supplier_id', $this->data['row'][0]->supplier_id);
    $this->data['created_by'] = $this->idname('username', 'tb_users', 'id', $this->data['row'][0]->created_by);

    return view('paymentforinvoice.view', $this->data);

  }

  public function getaccountdetails($bankid = null)
  {
    $sql = \DB::select("select * from f_bank_account_lines_t where bank_account_hdr_id='$bankid'");
    //            dd($bankid);
    return $sql;
  }
  public function getcashaccount()
  {
    $sql = \DB::select("select cash_account_id from f_account_setting_t where module_name='cashaccount'");

    return $sql;
  }

  public function getimprestaccount()
  {
    $sql = \DB::select("select imprest_account_id from f_account_setting_t where module_name='hrms'");

    return $sql;
  }

  /*Expense Payment Call Function*/
  public function expenseindex(Request $request)
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

    $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');

    $wh = '';

    $org = \Session::get('organization');
    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $groupname = \Session::get('groupname');
    $grid_date = \Session::get('griddate');
    $gridenddate = \Session::get('gridenddate');
    if ($groupname == 'Superadmin' || $groupname == 'Admin') {
      $wh .= 'and f_expenses_t.company_id=' . $compy;
    } else {
      $wh .= " and ( ( f_expenses_t.expense_date < '$grid_date'  and (f_expenses_t.expense_status = 'APPROVED' and f_expenses_t.payment_status = 0)) or  ( f_expenses_t.expense_date BETWEEN '$grid_date' and '$gridenddate' ) )  ";
      $wh .= 'and f_expenses_t.company_id=' . $compy . ' and f_expenses_t.location_id=' . $loc;
    }


    $this->data['sum_inv_tot'] = \DB::select("SELECT
                    ROUND(SUM(f_expenses_t.expense_amount),2) as sum_inv_total,
                    ROUND(SUM(f_expenses_t.balance_amount),2) as sum_bal_total
                    FROM f_expenses_t
                    left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_expenses_t.expense_account_id) 
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
          left join m_customers_t on(m_customers_t.customer_id=f_expenses_t.customer_id)
          left join hr_employee_t on(hr_employee_t.employee_id=f_expenses_t.employee_id)
          LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
                    left join tb_users on (tb_users.id =f_expenses_t.created_by)
                    where 1=1 and f_expenses_t.expense_status='APPROVED'  and f_expenses_t.payment_request_status='2' and f_expenses_t.payment_status='0' $wh ");


    $table = \DB::table('f_expenses_t')->get();
    $this->data['datas'] = $table;
    return view('paymentforinvoice.expensetable', $this->data);
  }

  /*Expense Payment Jqgrid*/
  public function getpaymentExpenseData()
  {

    $wh = '';

    $org = \Session::get('organization');
    $loc = \Session::get('location');
    $compy = \Session::get('companyid');

    $groupname = \Session::get('groupname');
    $grid_date = \Session::get('griddate');
    $gridenddate = \Session::get('gridenddate');
    if ($groupname == '1' || $groupname == 'Admin') {
      $wh .= 'and f_expenses_t.company_id=' . $compy;
    } else {
      $wh .= " and ( ( f_expenses_t.expense_date < '$grid_date'  and (f_expenses_t.expense_status = 'APPROVED' and f_expenses_t.payment_status = 0)) or  ( f_expenses_t.expense_date BETWEEN '$grid_date' and '$gridenddate' ) )  ";
      $wh .= 'and f_expenses_t.company_id=' . $compy . ' and f_expenses_t.location_id=' . $loc;
    }

    $SQL = "SELECT f_expenses_t.expense_id,
                    f_expenses_t.expense_date,
                    f_expenses_t.expense_no,
                    f_expenses_t.expense_type,
                    f_expenses_t.expense_status,
                    f_expenses_t.invoice,
                    f_expenses_t.remarks,
                    f_expenses_t.balance_amount,
                    m_supplier_t.supplier_name,
                    m_customers_t.customer_name,
                    hr_employee_t.first_name as employee_name,
                    hr_employee_t.employee_number,
                    a_lookuplines_t.lookup_code as emp_type,
                    f_expenses_t.expense_amount,
                    DATEDIFF(CURDATE(), f_expenses_t.expense_date) as over_due,
                    (case when f_expenses_t.payment_request_status=0 then 'OPEN'  when f_expenses_t.payment_request_status=1 then 'INITIATED' else 'APPROVED' end) as requeststatus,
                    tb_users.first_name
                    FROM f_expenses_t
                    left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_expenses_t.expense_account_id) 
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
          left join m_customers_t on(m_customers_t.customer_id=f_expenses_t.customer_id)
          left join hr_employee_t on(hr_employee_t.employee_id=f_expenses_t.employee_id)
          LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
                    left join tb_users on (tb_users.id =f_expenses_t.created_by)
                    where 1=1 and f_expenses_t.expense_status='APPROVED'  and f_expenses_t.payment_request_status='2' and f_expenses_t.payment_status='0' $wh ORDER BY f_expenses_t.expense_id DESC";

    $result = \DB::select($SQL);
    return DataTables::of($result)->make(true);

  }

  /*Payment For Expense*/
  public function paymentforexpensecreate($expenseid = null)
  {
    // dd($expenseid);
    $this->data['pageModule'] = "paymentforexpense";
    $this->data[''] = url('paymentforexpense');
    $table = \DB::table('f_expenses_t')->where('expense_id', $expenseid)->get();
    $this->data['row'] = (object) array();
    /*Expense Type->Supplier Based*/
    if ($table[0]->expense_type == "SUPPLIER") {



      $this->data['credit_balamt'] = $creditsql = \DB::select("select 'invoice' as type,bill_number,balance_amount,po_invoice_id,credit_note,credit_note_balance from p_po_invoice_hdr_t where supplier_id=" . $table[0]->supplier_id . " and credit_note_balance!=0 union all SELECT 'debitcredit' as type,`debitcredit_no` as bill_number,`balance_amount`,`debitcredit_id` as po_invoice_id,0 as debit_note,balance_amount as debit_note_balance  FROM `f_debitcredit_t` WHERE `supplier_id`=" . $table[0]->supplier_id . " and balance_amount!=0 and source_type='CREDIT' and debitcredit_type='SUPPLIER'");
      $this->data['debit_balamt'] = $debitsql = \DB::select("select 'invoice' as type,bill_number,balance_amount,po_invoice_id,debit_note,debit_note_balance from p_po_invoice_hdr_t where supplier_id=" . $table[0]->supplier_id . " and debit_note_balance!=0 union all SELECT 'debitcredit' as type,`debitcredit_no` as bill_number,`balance_amount`,`debitcredit_id` as po_invoice_id,0 as debit_note,balance_amount as debit_note_balance  FROM `f_debitcredit_t` WHERE `supplier_id`=" . $table[0]->supplier_id . " and balance_amount!=0 and source_type='DEBIT' and debitcredit_type='SUPPLIER'");

      $this->data['ad_balamt'] = $sql1 = \DB::select("select po_number,balance_amount,advance_amount,po_hdr_id from p_po_hdr_t where supplier_id=" . $table[0]->supplier_id . " and balance_amount!=0 and advance_status=0 and advance_amount!=0 ");
      if (COUNT($sql1) > 0) {
        foreach ($sql1 as $k => $vv) {
          $pohdid = $vv->po_hdr_id . ',';
        }
        $pohdid = rtrim($pohdid, ',');
        $this->data['row']->po_hdr_id = $pohdid;

      }


      $supplierbankdetails = \DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*', 'f_bank_account_lines_t.*')
        ->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
        ->where('f_bank_account_hdr_t.supplierid', $table[0]->supplier_id)->where('f_bank_account_lines_t.active', 'Yes')->get();
      if (count($supplierbankdetails) > 0) {
        $this->data['row']->supplier_bank_id = $supplierbankdetails[0]->bank_name;
        $this->data['row']->supplier_account_no = $supplierbankdetails[0]->account_number;
        $this->data['row']->supplier_ifsc_code = $supplierbankdetails[0]->ifsc_code;
        $this->data['row']->supplier_account_name = $supplierbankdetails[0]->name_in_account;
        if ($supplierbankdetails[0]->favouring_name == '') {
          $this->data['row']->favouring_name = $supplierbankdetails[0]->name_in_account;
        } else {
          $this->data['row']->favouring_name = $supplierbankdetails[0]->favouring_name;
        }

      } else {
        $this->data['row']->supplier_bank_id = '';
        $this->data['row']->supplier_account_no = '';
        $this->data['row']->supplier_ifsc_code = '';
        $this->data['row']->supplier_account_name = '';
        $this->data['row']->favouring_name = '';
      }
      if ($table[0]->supplier_id != "") {
        $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $table[0]->supplier_id);
      } else {
        $this->data['supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', $table[0]->subcontract_supplier_id);
      }

    }
    /*Expense Type->Customer Based*/
    if ($table[0]->expense_type == "CUSTOMER") {
      $this->data['credit_balamt'] = \DB::select("select 'invoice' as type,invoice_number,balance_amount,invoice_hdr_id,credit_note,credit_note_balance from s_invoice_hdr_t where ship_to_customer_id=" . $table[0]->customer_id . " and credit_note_balance!=0 union all SELECT 'debitcredit' as type,`debitcredit_no` as bill_number,`balance_amount`,`debitcredit_id` as po_invoice_id,0 as debit_note,balance_amount as debit_note_balance  FROM `f_debitcredit_t` WHERE `customer_id`=" . $table[0]->customer_id . " and balance_amount!=0 and source_type='CREDIT' and debitcredit_type='CUSTOMER'");
      $this->data['debit_balamt'] = \DB::select("select 'invoice' as type,invoice_number,balance_amount,invoice_hdr_id,debit_note,debit_note_balance from s_invoice_hdr_t where ship_to_customer_id=" . $table[0]->customer_id . " and debit_note_balance!=0 union all SELECT 'debitcredit' as type,`debitcredit_no` as bill_number,`balance_amount`,`debitcredit_id` as po_invoice_id,0 as debit_note,balance_amount as debit_note_balance  FROM `f_debitcredit_t` WHERE `customer_id`=" . $table[0]->customer_id . " and balance_amount!=0 and source_type='DEBIT' and debitcredit_type='CUSTOMER'");
      //dd($table[0]->customer_id);
      /*deepika purpose:get advance amount from so*/
      $this->data['ad_balamt'] = \DB::select("select sales_order_no,balance_amount,advance_amount,sales_hdr_id from s_salesorder_hdr_t where ship_to_customer_id=" . $table[0]->customer_id . " and balance_amount!=0");



      $customerbankdetails = \DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*', 'f_bank_account_lines_t.*')
        ->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
        ->where('f_bank_account_hdr_t.customerid', $table[0]->customer_id)->where('f_bank_account_lines_t.active', 'Yes')->get();

      if (count($customerbankdetails) > 0) {
        $this->data['row']->supplier_bank_id = $customerbankdetails[0]->bank_name;
        $this->data['row']->supplier_account_no = $customerbankdetails[0]->account_number;
        $this->data['row']->supplier_ifsc_code = $customerbankdetails[0]->ifsc_code;
        $this->data['row']->supplier_account_name = $customerbankdetails[0]->name_in_account;
        if ($customerbankdetails[0]->favouring_name == '') {
          $this->data['row']->favouring_name = $customerbankdetails[0]->name_in_account;
        } else {
          $this->data['row']->favouring_name = $customerbankdetails[0]->favouring_name;
        }
      } else {
        $this->data['row']->supplier_bank_id = '';
        $this->data['row']->supplier_account_no = '';
        $this->data['row']->supplier_ifsc_code = '';
        $this->data['row']->supplier_account_name = '';
        $this->data['row']->favouring_name = '';
      }

      $this->data['supplier_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->customer_id);
    }

    /*Expense Type->Employee Based*/
    //  dd($table);
    if ($table[0]->expense_type == "EMPLOYEE") {
      $employeebankdetails = \DB::table('hr_emp_salary')->select('hr_emp_salary.*')
        ->where('hr_emp_salary.employee_id', $table[0]->employee_id)->get();

      if (count($employeebankdetails) > 0) {
        $this->data['row']->supplier_bank_id = $employeebankdetails[0]->bank_name;
        $this->data['row']->supplier_account_no = $employeebankdetails[0]->account_number;
        $this->data['row']->favouring_name = $employeebankdetails[0]->account_holder_name;
        $this->data['row']->supplier_ifsc_code = $employeebankdetails[0]->ifsc_code;
        $this->data['row']->supplier_account_name = $employeebankdetails[0]->account_holder_name;
      } else {
        $this->data['row']->supplier_bank_id = '';
        $this->data['row']->supplier_account_no = '';
        $this->data['row']->supplier_ifsc_code = '';
        $this->data['row']->supplier_account_name = '';
        $this->data['row']->favouring_name = '';
      }
      $this->data['supplier_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->employee_id);

    }
    //Invoice Amount
    $this->data['row']->po_invoice_id = $expenseid;
    $invamount = \DB::select("select sum(expense_amount) as inv_amt from f_expenses_t where expense_id in($expenseid)");
    $inv_amount = $this->data['row']->invoice_amount = round($invamount[0]->inv_amt, 2);

    $this->data['row']->payment_id = "";
    $this->data['row']->payment_number = "";
    $this->data['row']->payment_date = date('Y-m-d');
    $this->data['row']->cheque_date = date('Y-m-d');
    $payamt = $this->data['row']->payment_status = "";
    $this->data['row']->payment_amount = "";
    $this->data['row']->advance_amount = "";
    $this->data['row']->payment_type_id = "";
    $this->data['row']->payment_reference = "";
    $this->data['row']->cheque_no = "";
    $this->data['row']->remarks = "";
    $this->data['row']->reference_id = $expenseid;
    $this->data['row']->payment_source = "EXPENSE";
    $this->data['row']->expense_type = $table[0]->expense_type;
    $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', '');
    //$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
    $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');
    $this->data['imprest_employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
    $this->data['expense_number'] = $table[0]->expense_no;
    /*Update for Expense Balance Amount*/
    $this->data['expense_balamt'] = $sql = \DB::select("select invoice,balance_amount,expense_id from f_expenses_t where expense_id in ($expenseid)");
    $expno = "";
    $balamt = 0;
    foreach ($sql as $key => $value) {
      $expno .= $value->invoice . ",";
      $balamt += $value->balance_amount;
    }
    $this->data['row']->remarks = "";
    $this->data['row']->balance_amount = $balamt;
    $expense_no = rtrim($expno, ',');
    $this->data['expense_no'] = $expense_no;

    /*End for Expense Balance Amount*/
    $this->data['statement'] = 0;
    return view('paymentforinvoice.payexpenseform', $this->data);
  }

  /* Purpose For Direct Expense Payment For Employee*/
  public function createdirectexpense()
  {
    $this->data['pageModule'] = "paymentforexpense";
    $this->data['pageUrl'] = url('paymentforexpense');
    $this->data['row'] = (object) array();
    $this->data['row']->payment_id = "";
    $this->data['row']->payment_number = "";
    $this->data['row']->payment_date = date('Y-m-d');
    $this->data['row']->cheque_date = date('Y-m-d');
    $payamt = $this->data['row']->payment_status = "";
    $this->data['row']->payment_amount = "";
    $this->data['row']->payment_source = "DIRECTEXPENSE";
    $this->data['row']->payment_type_id = "";
    $this->data['row']->payment_reference = "";
    $this->data['row']->reference_id = "";
    $this->data['row']->cheque_no = "";
    $this->data['row']->sender_information = "";
    $this->data['row']->remarks = "";
    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', '');
    $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
    //$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
    $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');
    $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
    //dd($this->data['employee_id']);
    $this->data['statement'] = 0;
    $this->data['linedata'] = array();
    return view('paymentforinvoice.directpayexpenseform', $this->data);
  }

  /*End*/
  /* purpose for Direct Expense Save function*/
  public function directexpensesave(Request $request)
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
    ]);
    // Normalize "bulk_" keys once
    $form = $this->normalizeLineFormKeys($form);

    // Build header + lines
    $data = $this->validatePost($form, $this->table, 'header');

    \DB::beginTransaction();

    try {
      foreach ($_POST['bulk_employee_id'] as $k => $v) {


        if ($_POST['payment_source'] == "SALARYPAYMENT") {
          $ref_id = $_POST['bulk_reference_id'][$k];
          $check_pay = \DB::select("select * from  hr_employee_payroll_lists  where id =" . $ref_id);

          $update_amount = $_POST['bulk_payment_amount'][$k] + $check_pay[0]->balance_salary;
          if ($update_amount >= $check_pay[0]->net_salary) {
            \DB::update("UPDATE hr_employee_payroll_lists set payment_status='1' where id=" . $ref_id);
            \DB::update("UPDATE hr_employee_payroll_lists set balance_salary=$update_amount where id =" . $ref_id);
          } else {

            \DB::update("UPDATE hr_employee_payroll_lists set balance_salary=$update_amount where id =" . $ref_id);
          }
        }
        $seqno = $this->Seqnoe('PMT-', 'p_payments_t', '', 'payment_count');
        $linesdata['payment_number'] = $seqno[0];
        $linesdata['payment_count'] = $seqno[1];
        $linesdata['payment_date'] = $data['payment_date'];
        $linesdata['sender_information'] = $data['sender_information'];
        $linesdata['payment_type_id'] = $data['payment_type_id'];
        $linesdata['payment_reference'] = $data['payment_reference'];
        $linesdata['bank_id'] = $data['bank_id'];
        $linesdata['account_no'] = $data['account_no'];
        $linesdata['account_code_id'] = $_POST['account_code_id'];
        $linesdata['cheque_no'] = $data['cheque_no'];
        $linesdata['payment_source'] = $data['payment_source'];
        $linesdata['payment_status'] = $data['payment_status'];
        $linesdata['remarks'] = $_POST['remarks'];
        $linesdata['created_by'] = $data['created_by'];
        $linesdata['created_at'] = date('Y-m-d H:i:s');
        $linesdata['last_updated_by'] = $data['last_updated_by'];
        $linesdata['updated_at'] = date('Y-m-d H:i:s');
        $linesdata['company_id'] = \Session::get('companyid');
        $linesdata['location_id'] = \Session::get('location');
        $linesdata['employee_id'] = $_POST['bulk_employee_id'][$k];
        $linesdata['payment_amount'] = $_POST['bulk_payment_amount'][$k];
        $linesdata['remarks'] = $_POST['remarks'] . " " . $_POST['bulk_remarks'][$k];
        $linesdata['supplier_bank_id'] = $_POST['bulk_supplier_bank_id'][$k];
        $linesdata['supplier_account_name'] = $_POST['bulk_supplier_account_name'][$k];
        $linesdata['supplier_account_no'] = $_POST['bulk_supplier_account_no'][$k];
        $linesdata['supplier_ifsc_code'] = $_POST['bulk_supplier_ifsc_code'][$k];


        DB::table('p_payments_t')->insert($linesdata);
        $id = DB::getPdo()->lastInsertId();

        if ($_POST['payment_source'] == "SALARYPAYMENT") {
          $paymt_no = "SALARYPAYMENT-" . $linesdata['payment_number'];
          $pay_name = "SALARYPAYMENT";
        } else {
          $paymt_no = "IMPRESTPAYMENT-" . $linesdata['payment_number'];
          $pay_name = "IMPRESTPAYMENT";
        }
        $paydate = $_POST['payment_date'];
        //   dd($paydate);
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        //Journal Header Insert
        $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$paymt_no','$pay_name','$paydate','$id','APPROVED','$compy','$loc','$org')");
        $jid = DB::getPdo()->lastInsertId();

        $account_id_data = \DB::select("select * from f_account_setting_t where module_name='hrms'");
        //Journal lINES Insert       


        $tkey = 0;

        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
        $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";

        $journal_lines_data[$tkey]['reference_id'] = $_POST['bulk_employee_id'][$k];

        if ($_POST['payment_source'] != "SALARYPAYMENT") {
          $journal_lines_data[$tkey]['account_id'] = $account_id_data[0]->imprest_account_id;
        } else {
          $journal_lines_data[$tkey]['account_id'] = $_POST['bulk_account_code_id'][$k];
        }


        $journal_lines_data[$tkey]['debit_amount'] = $linesdata['payment_amount'];
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
        $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
        if ($_POST['payment_type_id'] == 'IMPREST') {
          $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
        } else {
          $journal_lines_data[$tkey]['reference_source'] = "";
        }
        $journal_lines_data[$tkey]['reference_id'] = $_POST['bulk_employee_id'][$k];

        $journal_lines_data[$tkey]['account_id'] = $_POST['account_code_id'];

        $journal_lines_data[$tkey]['debit_amount'] = '';
        $journal_lines_data[$tkey]['credit_amount'] = $linesdata['payment_amount'];
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


      $paymenttype = $_POST['payment_type_id'];
      if ($paymenttype == 'CHEQUE') {
        //Cheque no count update
        $chequeno = $_POST['cheque_no'];
        if ($chequeno != '') {
          $accno = $_POST['account_no'];

          $chequeupdate = \DB::update("UPDATE f_bank_cheque_lines_t set cheque_count='$chequeno' where bank_cheque_hdr_id='$accno'");
        }
      }

      \DB::commit();
      return response()->json(array('status' => 'success', 'message' => 'Payment Saved', 'id' => $id));
    } catch (\Illuminate\Database\QueryException $e) {
      $message = explode('(', $e->getMessage());
      $dbCode = rtrim($message[0], ']');
      $dbCode = trim($dbCode, '[');
      //dd($dbCode);
      \DB::rollback();
      return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
    }
  }


  // index page shown function 
  public function employeepayrolforpayindex(Request $request)
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

    $this->data['department_id'] = $this->jcombo('m_department_lines_t', 'department_line_id', 'sub_department_name', '');
    return view('paymentforinvoice.employeepayrolltable', $this->data);
  }

  public function releaseindex(Request $request)
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

    return view('paymentforinvoice.releasepayment', $this->data);
  }

  public function employeepayrolforpaygrid()
  {

    $wh = "";
    $comp = \Session::get('companyid');
    $emp = \Session::get('emp_id');
    if (isset($_GET['pagemode'])) {
      if ($_GET['pagemode'] == "approvereleasepaymentforemployee") {
        $wh .= 'and hr_employee_payroll_lists.acapprove_status="" and hr_employee_payroll_lists.ac_status=1';



      } else if ($_GET['pagemode'] == "releasepaymentfremp") {
        $wh .= 'and hr_employee_payroll_lists.payment_status=0 and hr_employee_payroll_lists.ac_status=2';
      }
    } else {
      $wh .= 'and hr_employee_payroll_lists.payment_status=0 and hr_employee_payroll_lists.acapprove_status="APPROVED" and hr_employee_payroll_lists.ac_status=1';
    }

    $emp_data = DB::table("hr_employee_t")->where('employee_id', $emp)->get();
    $dept = json_decode($emp_data[0]->department);

    if (in_array(28, $dept)) {
      $wh .= '';
    } else {
      if ($emp != "1") {
        $wh .= " ";
      }
    }


    $SQL = "SELECT hr_employee_payroll_lists.id,
      hr_employee_t.employee_number,
      hr_employee_t.first_name,
      hr_employee_payroll_lists.month,
      hr_employee_payroll_lists.year,
      hr_employee_payroll_lists.date,
      hr_employee_payroll_lists.basic_salary,
      hr_employee_payroll_lists.hra,
      (
    SELECT
        hr_emp_salary.bank_name
    FROM
        hr_emp_salary
    WHERE
        hr_emp_salary.employee_id = hr_employee_payroll_lists.employee_id
limit 1) AS bank_name,
      hr_employee_t.department,
      a_lookuplines_t.lookup_meaning,
      hr_employee_payroll_lists.da,
      hr_employee_payroll_lists.pf,
      hr_employee_payroll_lists.esi,
      hr_employee_payroll_lists.gross_salary,
      hr_employee_payroll_lists.net_salary,
      hr_employee_payroll_lists.total_days,
      hr_employee_payroll_lists.payment_status,
      (hr_employee_payroll_lists.net_salary-hr_employee_payroll_lists.balance_salary) as balance,
       (
      CASE WHEN hr_employee_payroll_lists.ac_status = 2 THEN 'Hold' WHEN hr_employee_payroll_lists.ac_status = 1 THEN 'Released' else 'Approved'
                        END
      ) 
        AS approved_status
        
      FROM
      hr_employee_payroll_lists
        left join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id left join a_lookuplines_t pay_type on pay_type.lookuplines_id=hr_employee_payproposal.payroll_type
        left join f_bank_account_hdr_t on f_bank_account_hdr_t.bank_account_hdr_id=hr_employee_payroll_lists.employee_id 
       
      LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id 
      left join a_lookuplines_t on a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type and a_lookuplines_t.lookup_type = 'EMPLOYEE_TYPE'
      WHERE 1 = 1 and hr_employee_payroll_lists.company_id=$comp  $wh ORDER BY hr_employee_payroll_lists.id DESC";

    $result = \DB::select($SQL);

    if (count($result) > 0) {
      foreach ($result as $k => $v) {
        if ($v->department != '' && $v->department != null) {
          $array = json_decode($v->department);
          $query = \DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
          $deptnames = json_decode(json_encode($query), true);
          $result[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
        } else {
          $result[$k]->department = "";
        }
      }
    }

    return DataTables::of($result)->make(true);

  }


  /* Purpose For Direct Expense Payment For Employee*/
  public function paymentforemployeecreate($payslipid = null)
  {
    //dd($payslipid);
    $this->data['pageModule'] = "paymentforemployee";
    $this->data['pageUrl'] = url('paymentforemployee');
    $table = \DB::table('hr_employee_payroll_lists')->select('employee_id', 'net_salary', 'id', 'balance_salary')->whereIn('id', explode(',', $payslipid))->get();
    $this->data['row'] = (object) array();
    $this->data['row']->payment_id = "";
    $this->data['row']->payment_number = "";
    $this->data['row']->payment_date = date('Y-m-d');
    $this->data['row']->cheque_date = date('Y-m-d');
    $payamt = $this->data['row']->payment_status = "";
    $this->data['row']->payment_amount = "";
    $this->data['row']->payment_source = "SALARYPAYMENT";
    $this->data['row']->reference_id = $payslipid;
    $this->data['row']->payment_type_id = "";
    $this->data['row']->sender_information = "";
    $this->data['row']->payment_reference = "";
    $this->data['row']->cheque_no = "";
    $this->data['row']->remarks = "";
    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', '');
    $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
    //$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
    $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');
    $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
    $this->data['imprest_employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
    $this->data['statement'] = 0;
    $this->data['linedata'] = $table;


    if (count($this->data['linedata']) >= 1) {
      foreach ($this->data['linedata'] as $key => $value) {

        $employeebankdetails = \DB::table('hr_emp_salary')->select('hr_emp_salary.*')
          ->where('hr_emp_salary.employee_id', $value->employee_id)->get();

        $salaryaccount = \DB::table('f_hr_account_setting_t')->select('f_hr_account_setting_t.salary_account_id')->groupBy('salary_account_id')->get();

        $this->data['linedata'][$key]->employee_id = $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $value->employee_id);
        //$this->data['linedata'][$key]->account_code_id = $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments','');
        $this->data['linedata'][$key]->account_code_id = $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');

        if (count($employeebankdetails) > 0) {
          if (isset($employeebankdetails[0]->bank_name)) {
            $this->data['linedata'][$key]->bank_name = $employeebankdetails[0]->bank_name;
          } else {
            $this->data['linedata'][$key]->bank_name = "";
          }
          if (isset($employeebankdetails[0]->account_holder_name)) {
            $this->data['linedata'][$key]->account_holder_name = $employeebankdetails[0]->account_holder_name;
          } else {
            $this->data['linedata'][$key]->account_holder_name = "";

          }
          if (isset($employeebankdetails[0]->account_holder_name)) {
            $this->data['linedata'][$key]->account_number = $employeebankdetails[0]->account_number;
          } else {
            $this->data['linedata'][$key]->account_number = "";

          }
          if (isset($employeebankdetails[0]->ifsc_code)) {
            $this->data['linedata'][$key]->ifsc_code = $employeebankdetails[0]->ifsc_code;
          } else {
            $this->data['linedata'][$key]->ifsc_code = "";
          }

        } else {

          $this->data['linedata'][$key]->bank_name = "";
          $this->data['linedata'][$key]->account_holder_name = "";
          $this->data['linedata'][$key]->account_number = "";
          $this->data['linedata'][$key]->ifsc_code = "";
        }
        $this->data['linedata'][$key]->net_amt = $value->net_salary - $value->balance_salary;
        //$this->data['linedata'][$key]->account_code_id = $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments',$salaryaccount[0]->salary_account_id);
        $this->data['linedata'][$key]->account_code_id = $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', $salaryaccount[0]->salary_account_id);
      }

    }

    return view('paymentforinvoice.payforemployeeform', $this->data);
  }
  public function getemployeebankdetails($employee_id = null)
  {
    $employeebankdetails = \DB::table('hr_emp_salary')->select('hr_emp_salary.*')->where('hr_emp_salary.employee_id', $employee_id)->get();
    return $employeebankdetails;
  }

  /** ajith purpose invocie balance for payment **/
  public function indexsalret(Request $request)
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


    return view('paymentforinvoice.salesrtntable', $this->data);
  }


  //  purpose for grid load data
  public function getsalesrtndetailsData()
  {
    $wh = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $org = \Session::get('organization');
    $groupname = \Session::get('groupname');


    $SQL = "SELECT * FROM(
	SELECT 
	f_debitcredit_t.debitcredit_id as so_rma_hdr_id,
	f_debitcredit_t.debitcredit_no as rma_ref_no,
	f_debitcredit_t.debitcredit_date as return_date,
	f_debitcredit_t.source_type as return_source,
	f_debitcredit_t.debitcredit_amount as total_amount,
	f_debitcredit_t.balance_amount as balance_amount,
	m_customers_t.customer_name as customer_name,
	m_supplier_t.supplier_name,
	f_debitcredit_t.debitcredit_type,
	f_debitcredit_t.updated_at    
	FROM f_debitcredit_t
	left JOIN m_customers_t on (m_customers_t.customer_id=f_debitcredit_t.customer_id)
	LEFT JOIN m_supplier_t on (m_supplier_t.supplier_id = f_debitcredit_t.supplier_id)
	WHERE f_debitcredit_t.debitcredit_status='APPROVED' and f_debitcredit_t.balance_amount>0
	UNION ALL 
	SELECT so_rma_hdr_t.so_rma_hdr_id,
	so_rma_hdr_t.rma_ref_no,
	so_rma_hdr_t.return_date,
	so_rma_hdr_t.return_source,
	so_rma_hdr_t.total_amount,
	so_rma_hdr_t.balance_amount,
	m_customers_t.customer_name,
	0 as supplier_name,
	'CUSTOMER' as debitcredit_type,
	so_rma_hdr_t.updated_at
	FROM so_rma_hdr_t left JOIN m_customers_t on (m_customers_t.customer_id=so_rma_hdr_t.customerid) WHERE so_rma_hdr_t.return_source='DIRECT' and so_rma_hdr_t.return_status='APPROVED' and so_rma_hdr_t.balance_amount>0) as v1 WHERE 1=1 $wh ORDER BY v1.updated_at DESC";


    $result = \DB::select($SQL);
    return DataTables::of($result)->make(true);

  }

  //Ajith purpose salesreturn balance for payment **/
  public function createblncesale($invid = null, $poid = null, $id = null)
  {
    // dd($_GET);
    $supplier_id = "";
    $source = $_GET['source'];
    // DEBIT DIRECT
    $this->data['pageModule'] = "paymentformcn";
    $this->data[''] = url('paymentformcn');
    $invid1 = explode(',', $invid);
    if ($source == 'DIRECT') {
      $table = \DB::table('so_rma_hdr_t')->whereIn('so_rma_hdr_id', $invid1)->get();
      $customerid = $table[0]->customerid;
      $cusid = 'f_bank_account_hdr_t.customerid';
    } else {
      $table = \DB::table('f_debitcredit_t')->whereIn('debitcredit_id', $invid1)->get();
      //  dd($table);
      if ($table[0]->customer_id != '0' || $table[0]->customer_id != '') {
        $customerid = $table[0]->customer_id;
        $cusid = 'f_bank_account_hdr_t.customerid';
      } else {
        // dd($table[0]->supplier_id);
        $customerid = $table[0]->supplier_id;
        $supplier_id = $table[0]->supplier_id;
        $cusid = 'f_bank_account_hdr_t.supplierid';
      }
    }

    // dd($table);
    $customerbankdetails = \DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*', 'f_bank_account_lines_t.*')
      ->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
      ->where($cusid, $customerid)->where('f_bank_account_lines_t.active', 'Yes')->get();
    // dd($customerbankdetails);
    $this->data['row'] = (object) array();
    $this->data['row']->payment_id = "";
    $this->data['row']->payment_number = "";
    $this->data['row']->payment_date = date('Y-m-d');
    $this->data['row']->cheque_date = date('Y-m-d');
    $payamt = $this->data['row']->payment_status = "";
    $this->data['row']->payment_amount = "";
    if (count($customerbankdetails) > 0) {
      $this->data['row']->customer_bank_id = $customerbankdetails[0]->bank_name;
      $this->data['row']->customer_account_no = $customerbankdetails[0]->account_number;
      $this->data['row']->customer_ifsc_code = $customerbankdetails[0]->ifsc_code;
      $this->data['row']->customer_account_name = $customerbankdetails[0]->name_in_account;
      if ($customerbankdetails[0]->favouring_name == '') {
        $this->data['row']->favouring_name = $customerbankdetails[0]->name_in_account;
      } else {
        $this->data['row']->favouring_name = $customerbankdetails[0]->favouring_name;
      }
    }
    //   dd("dfgh");
    if ($source == 'DIRECT') {
      $this->data['row']->payment_source = "RMA";
    } else {
      $this->data['row']->payment_source = $source;
    }

    //Advance
    //   dd($table);
    if ($source == 'DIRECT') {
      $balance = $this->data['row']->balance_amount = str_replace("-", "", $table[0]->total_amount);
    } else {
      $balance = $this->data['row']->balance_amount = str_replace("-", "", $table[0]->balance_amount);
    }
    $this->data['row']->payment_type_id = "";
    $this->data['row']->payment_reference = "";
    $this->data['row']->cheque_no = "";
    $this->data['row']->remarks = "";
    $this->data['row']->sender_information = "";

    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', '');



    if ($supplier_id != "") {
      $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $supplier_id);
    } else {
      $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', '');
    }
    if ($customerid != "") {
      $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', $customerid);
    } else {
      $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', '');
    }

    $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");

    //$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
    $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');
    $invno = "";
    $invoice_amt = 0;
    //   dd($table);
    if ($source == 'DIRECT') {
      foreach ($table as $key => $value) {
        $invno .= $value->rma_ref_no . ",";
        $b_a = str_replace("-", "", $value->total_amount);
        $invoice_amt = $invoice_amt + $b_a;
      }
    } else {
      foreach ($table as $key => $value) {
        $invno .= $value->debitcredit_no . ",";
        $b_a = str_replace("-", "", $value->debitcredit_amount);
        $invoice_amt = $invoice_amt + $b_a;
      }
    }

    $invoiceno = rtrim($invno, ',');
    $this->data['invoice_amt'] = $invoice_amt;
    $this->data['bill_number'] = $invoiceno;
    $this->data['invoice_balamt'] = $table;
    // dd($this->data['invoice_balamt']);
    $this->data['so_invoice_id'] = $invid;
    return view('paymentforinvoice.salestrnform', $this->data);
  }

  public function savermablnce(Request $request)
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
      'customer_bank_id',
      'customer_account_name',
      'customer_account_no',
      'customer_ifsc_code',
    ]);
    // Normalize "bulk_" keys once
    $form = $this->normalizeLineFormKeys($form);

    // Build header + lines
    $data = $this->validatePost($form, $this->table, 'header');
    /* Purpose for Auto Number*/
    if ($_POST['payment_number'] == "") {
      $seqno = $this->Seqnoe('PMT-', 'p_payments_t', '', 'payment_count');
      $data['payment_number'] = $seqno[0];
      $data['payment_count'] = $seqno[1];
    } else {
      $seqno[0] = $_POST['payment_number'];
    }
    /*End*/

    //  dd($_POST['payment_source']);
    if ($data['payment_source'] == "RMA") {
      $data['payment_source'] = 'RMA';
    } else {
      $data['payment_source'] = 'CREDIT/DEBIT';
    }

    \DB::beginTransaction();

    try {

      $so_invoice = explode(",", $_POST['so_invoice_id']);
      //  dd($so_invoice);
      $data['so_invoice_id'] = '';

      $data['reference_id'] = $_POST['so_invoice_id'];
      if ($data['payment_source'] == "RMA") {
        foreach ($so_invoice as $k => $v) {
          $invociehdr = \DB::select("select  so_rma_hdr_id,balance_amount,paid_amount,total_amount from so_rma_hdr_t where so_rma_hdr_id =$v");
          // $balance_amount=str_replace("-","",$invociehdr[0]->balance_amount);;
          // $paid_amount=$_POST['paymentamt'][$v];
          if ($_POST['paymentamt'][$v] == '') {
            $_POST['paymentamt'][$v] = 0;
          }
          $current_balance_amount = $invociehdr[0]->balance_amount - $_POST['paymentamt'][$v];
          $paid_amount = $invociehdr[0]->paid_amount + $_POST['paymentamt'][$v];
          \DB::update("Update so_rma_hdr_t set paid_amount='$paid_amount',balance_amount='$current_balance_amount',payment_status='1' where so_rma_hdr_id='$v'");

        }

      } else {
        // dd($so_invoice);
        foreach ($so_invoice as $k => $v) {
          // dd($v);
          $invociehdr = \DB::select("select  debitcredit_id,balance_amount,paid_amount,debitcredit_amount from f_debitcredit_t where debitcredit_id =$v");
          //  dd($_POST['paymentamt'][$v]);
          if ($_POST['paymentamt'][$v] == '') {
            $_POST['paymentamt'][$v] = 0;
          }
          $current_balance_amount = $invociehdr[0]->balance_amount - $_POST['paymentamt'][$v];
          $paid_amount = $invociehdr[0]->paid_amount + $_POST['paymentamt'][$v];
          //  dd($current_balance_amount);
          \DB::update("Update f_debitcredit_t set paid_amount='$paid_amount',balance_amount='$current_balance_amount' where debitcredit_id='$v'");
        }
      }


      $id = $this->model->insertRow($data);
      $paymt_no = "PAYMENT-" . $data['payment_number'];
      $paydate = $_POST['payment_date'];
      $org = \Session::get('organization');
      $loc = \Session::get('location');
      $compy = \Session::get('companyid');
      if ($data['payment_source'] == "RMA") {
        $pay_name = 'PAYMENT';
      } else {
        $pay_name = 'PAYMENT';
      }
      //  $pay_name="RMA";

      $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$paymt_no','$pay_name','$paydate','$id','APPROVED','$compy','$loc','$org')");
      $jid = DB::getPdo()->lastInsertId();
      $tkey = 0;
      if ($_POST['customer_id'] != '') {
        $supp_name = "CUSTOMER";
        $referenceid = $_POST['customer_id'];
        $supplier_acc = \DB::table('m_customers_t')->where('customer_id', $_POST['customer_id'])->get();
        //dd("hii");
        $supplier_acc = $supplier_acc[0]->account_structure_id;

        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
        $journal_lines_data[$tkey]['reference_source'] = $supp_name;
        $journal_lines_data[$tkey]['reference_id'] = $referenceid;
        $journal_lines_data[$tkey]['account_id'] = $supplier_acc;
        $journal_lines_data[$tkey]['debit_amount'] = $_POST['payment_amount'];
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
        $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
        $journal_lines_data[$tkey]['reference_source'] = "CUSTOMER";
        $journal_lines_data[$tkey]['reference_id'] = '';
        $journal_lines_data[$tkey]['account_id'] = $_POST['account_code_id'];
        $journal_lines_data[$tkey]['debit_amount'] = '';
        $journal_lines_data[$tkey]['credit_amount'] = $_POST['payment_amount'];
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
      if ($_POST['supplier_id'] != '') {

        $supp_name = "SUPPLIER";
        $referenceid = $_POST['supplier_id'];
        $supplier_acc = \DB::table('m_supplier_t')->where('supplier_id', $_POST['supplier_id'])->get();
        //dd("hii");
        $supplier_acc = $supplier_acc[0]->account_structure_id;

        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
        $journal_lines_data[$tkey]['reference_source'] = $supp_name;
        $journal_lines_data[$tkey]['reference_id'] = $referenceid;
        $journal_lines_data[$tkey]['account_id'] = $supplier_acc;
        $journal_lines_data[$tkey]['debit_amount'] = $_POST['payment_amount'];
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
        $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
        $journal_lines_data[$tkey]['reference_source'] = "CUSTOMER";
        $journal_lines_data[$tkey]['reference_id'] = '';
        $journal_lines_data[$tkey]['account_id'] = $_POST['account_code_id'];
        $journal_lines_data[$tkey]['debit_amount'] = '';
        $journal_lines_data[$tkey]['credit_amount'] = $_POST['payment_amount'];
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
      return response()->json(array('status' => 'success', 'message' => 'Payment Saved', 'id' => $id));
    } catch (\Illuminate\Database\QueryException $e) {
      $message = explode('(', $e->getMessage());
      $dbCode = rtrim($message[0], ']');
      $dbCode = trim($dbCode, '[');
      \DB::rollback();
      return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
    }

  }

  //row based Hold
  public function holdpaymentfremp()
  {

    $row_id = $_GET['row_id'];
    $reason = $_GET['reason'];
    $list_id = explode(',', $row_id);
    if ($row_id != '') {
      foreach ($list_id as $key => $value) {

        $query = DB::table('hr_employee_payroll_lists')->where('id', $value)->update(['ac_status' => 2, 'ac_reason' => $reason, 'acapprove_status' => '']);
        //auditlog
        $update['ac_status'] = 2;
        $update['ac_reason'] = $reason;
        $this->auditlog($value, "approvepayroll", "update", $update, "hr_employee_payroll_lists");
      }
    }
    return 1;
  }
  //row based Release
  public function releasedpayment()
  {

    $row_id = $_GET['row_id'];
    $list_id = explode(',', $row_id);
    if ($row_id != '') {
      foreach ($list_id as $key => $value) {

        $query = DB::table('hr_employee_payroll_lists')->where('id', $value)->update(['ac_status' => 1]);
        //auditlog
        $update['ac_status'] = 1;
        $this->auditlog($value, "approvepayroll", "update", $update, "hr_employee_payroll_lists");
      }
    }
    return 1;
  }

  public function approvepaymentfremp()
  {

    $row_id = $_GET['row_id'];
    $list_id = explode(',', $row_id);
    if ($row_id != '') {
      foreach ($list_id as $key => $value) {

        $query = DB::table('hr_employee_payroll_lists')->where('id', $value)->update(['acapprove_status' => 'APPROVED']);
        //auditlog
        $update['acapprove_status'] = 'APPROVED';
        $this->auditlog($value, "approvepayroll", "update", $update, "hr_employee_payroll_lists");
      }
    }
    return 1;
  }

  public function empexpenseindex(Request $request)
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

    // $this->data['suppliername']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name'); 
    $table = \DB::table('f_emp_expenses_t')->get();
    $this->data['datas'] = $table;
    return view('paymentforinvoice.empexpensetable', $this->data);
  }

  public function getpaymentempExpenseData()
  {

    $wh = '';
    $org = \Session::get('organization');
    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $wh .= $grid_data = $this->grid_statuscheck('f_emp_expenses_t', 'expense_date', 'expense_status', '=', "'APPROVED'");

    $result = \DB::select("select * from (SELECT f_emp_expenses_t.expense_id,
                    f_emp_expenses_t.expense_date,
                    f_emp_expenses_t.expense_no,
                    f_emp_expenses_t.expense_status,
                    f_emp_expenses_t.remarks,
                    f_emp_expenses_lines_t.balance_amounts,
                    f_emp_expenses_lines_t.expense_line_amount,
                    f_emp_expenses_lines_t.bill_no,
                    f_emp_expenses_lines_t.expense_line_id,
                    concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_name,
                    tb_users.first_name
                    FROM f_emp_expenses_lines_t
                    left join f_emp_expenses_t on f_emp_expenses_t.expense_id = f_emp_expenses_lines_t.expense_id
                    left join hr_employee_t on hr_employee_t.employee_id=f_emp_expenses_lines_t.employee_id
                    left join tb_users on (tb_users.id =f_emp_expenses_t.created_by)
                    where 1=1 and f_emp_expenses_t.expense_status='APPROVED' and f_emp_expenses_lines_t.payment_status='0' and f_emp_expenses_lines_t.payment_request_status='2' $wh )as v1 
                    where 1=1 ORDER BY v1.expense_id DESC");

    return DataTables::of($result)->make(true);

  }


  public function paymentforempexpensecreate($expenseid = null)
  {

    
    $this->data['pageModule'] = "paymentforempexpense";
    $this->data[''] = url('paymentforempexpense');
    $table = \DB::table('f_emp_expenses_lines_t')->leftjoin('f_emp_expenses_t', 'f_emp_expenses_t.expense_id', '=', 'f_emp_expenses_lines_t.expense_id')->whereIn('f_emp_expenses_lines_t.expense_line_id', explode(',', $expenseid))->get();

    //dd($expenseid);

    $this->data['row'] = (object) array();
    $this->data['row'] = (object) array();
    $this->data['row']->payment_id = "";
    $this->data['row']->payment_number = "";
    $this->data['row']->payment_date = date('Y-m-d');
    $this->data['row']->cheque_date = date('Y-m-d');
    $payamt = $this->data['row']->payment_status = "";
    $this->data['row']->payment_amount = "";
    $this->data['row']->payment_source = "EMPLOYEE EXPENSE";
    $this->data['row']->reference_id = $expenseid;
    $this->data['row']->payment_type_id = "";
    $this->data['row']->sender_information = "";
    $this->data['row']->payment_reference = "";
    $this->data['row']->cheque_no = "";
    $this->data['row']->remarks = "";
    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', '');
    $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");

    $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');
    $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
    $this->data['imprest_employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
    $this->data['statement'] = 0;
    $this->data['linedata'] = $table;


    if (count($this->data['linedata']) >= 1) {
      foreach ($this->data['linedata'] as $key => $value) {

        $employeebankdetails = \DB::table('hr_emp_salary')->select('hr_emp_salary.*')
          ->where('hr_emp_salary.employee_id', $value->employee_id)->get();

        $this->data['linedata'][$key]->employee_id = $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $value->employee_id);
        $this->data['linedata'][$key]->account_code_id = $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', '');

        if (count($employeebankdetails) > 0) {
          if (isset($employeebankdetails[0]->bank_name)) {
            $this->data['linedata'][$key]->bank_name = $employeebankdetails[0]->bank_name;
          } else {
            $this->data['linedata'][$key]->bank_name = "";
          }
          if (isset($employeebankdetails[0]->account_holder_name)) {
            $this->data['linedata'][$key]->account_holder_name = $employeebankdetails[0]->account_holder_name;
          } else {
            $this->data['linedata'][$key]->account_holder_name = "";

          }
          if (isset($employeebankdetails[0]->account_holder_name)) {
            $this->data['linedata'][$key]->account_number = $employeebankdetails[0]->account_number;
          } else {
            $this->data['linedata'][$key]->account_number = "";

          }
          if (isset($employeebankdetails[0]->ifsc_code)) {
            $this->data['linedata'][$key]->ifsc_code = $employeebankdetails[0]->ifsc_code;
          } else {
            $this->data['linedata'][$key]->ifsc_code = "";
          }

        } else {

          $this->data['linedata'][$key]->bank_name = "";
          $this->data['linedata'][$key]->account_holder_name = "";
          $this->data['linedata'][$key]->account_number = "";
          $this->data['linedata'][$key]->ifsc_code = "";
        }

        $salaryaccount = \DB::table('f_account_setting_t')->where('module_name', 'hrms')->get();
        $supplier_acc = $salaryaccount[0]->imprest_account_id;
        $this->data['linedata'][$key]->net_amt = $value->expense_line_amount;
        $this->data['linedata'][$key]->tds_amt = $value->tds_amount;
        $this->data['linedata'][$key]->pay_amt = $value->emp_exp_total;
        $this->data['linedata'][$key]->account_code_id = $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'account_name', $salaryaccount[0]->imprest_account_id);
      }

    }

    return view('paymentforinvoice.paymentempemployeeform', $this->data);
  }

  public function paymentempExpensesave(Request $request)
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
    ]);
    // Normalize "bulk_" keys once
    $form = $this->normalizeLineFormKeys($form);

    // Build header + lines
    $data = $this->validatePost($form, $this->table, 'header');

    \DB::beginTransaction();

    try {
      foreach ($_POST['bulk_employee_id'] as $k => $v) {

        $ref_id = $_POST['bulk_reference_id'][$k];

        $check_pay = \DB::select("select * from  f_emp_expenses_lines_t  where expense_line_id =" . $ref_id);
        $update_amount = $_POST['bulk_payment_amount'][$k] + $check_pay[0]->balance_amounts;
        $currentbal = $check_pay[0]->balance_amounts - $_POST['bulk_payment_amount'][$k] - $check_pay[0]->tds_amount;
        if ($currentbal <= 0) {
          \DB::update("UPDATE f_emp_expenses_lines_t set balance_amounts=$currentbal,payment_status=1 where expense_line_id =" . $ref_id);
        } else {
          \DB::update("UPDATE f_emp_expenses_lines_t set balance_amounts=$currentbal where expense_line_id =" . $ref_id);
        }
        $seqno = $this->Seqnoe('PMT-', 'p_payments_t', '', 'payment_count');
        $linesdata['payment_number'] = $seqno[0];
        $linesdata['payment_count'] = $seqno[1];
        $linesdata['payment_date'] = $data['payment_date'];
        $linesdata['sender_information'] = $_POST['bulk_sender_information'][$k];
        $linesdata['payment_type_id'] = $data['payment_type_id'];
        $linesdata['payment_reference'] = $data['payment_reference'];
        $linesdata['bank_id'] = $data['bank_id'];
        $linesdata['account_no'] = $data['account_no'];
        $linesdata['account_code_id'] = $_POST['account_code_id'];
        $linesdata['cheque_no'] = $data['cheque_no'];
        $linesdata['payment_source'] = $data['payment_source'];
        $linesdata['payment_status'] = $data['payment_status'];
        $linesdata['remarks'] = $_POST['remarks'];
        $linesdata['created_by'] = $data['created_by'];
        $linesdata['created_at'] = date('Y-m-d H:i:s');
        $linesdata['last_updated_by'] = $data['last_updated_by'];
        $linesdata['updated_at'] = date('Y-m-d H:i:s');
        $linesdata['company_id'] = \Session::get('companyid');
        $linesdata['location_id'] = \Session::get('location');
        $linesdata['employee_id'] = $_POST['bulk_employee_id'][$k];
        $linesdata['payment_amount'] = $_POST['bulk_payment_amount'][$k];
        $linesdata['remarks'] = $_POST['remarks'] . " " . $_POST['bulk_remarks'][$k];
        $msg = $_POST['bulk_supplier_account_name'][$k] . " " . $_POST['bulk_remarks'][$k];
        $linesdata['supplier_bank_id'] = $_POST['bulk_supplier_bank_id'][$k];
        $linesdata['supplier_account_name'] = $_POST['bulk_supplier_account_name'][$k];
        $linesdata['supplier_account_no'] = $_POST['bulk_supplier_account_no'][$k];
        $linesdata['supplier_ifsc_code'] = $_POST['bulk_supplier_ifsc_code'][$k];

        DB::table('p_payments_t')->insert($linesdata);
        $id = DB::getPdo()->lastInsertId();

        $paymt_no = "EMP/EXPENSE-" . $linesdata['payment_number'];
        $pay_name = "EMP/EXPENSE";
        $paydate = $_POST['payment_date'];
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        //Journal Header Insert
        $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$paymt_no','$pay_name','$paydate','$id','APPROVED','$compy','$loc','$org')");
        $jid = DB::getPdo()->lastInsertId();
        $account_id_data = \DB::select("select * from f_account_setting_t where module_name='hrms'");
        //Journal lINES Insert       

        $tkey = 0;

        $journal_lines_data[$tkey]['f_journal_entry_line_id'] = '';
        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
        $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
        $journal_lines_data[$tkey]['reference_id'] = $_POST['bulk_employee_id'][$k];
        $journal_lines_data[$tkey]['account_id'] = $_POST['bulk_account_code_id'][$k];
        $journal_lines_data[$tkey]['debit_amount'] = $linesdata['payment_amount'];
        $journal_lines_data[$tkey]['credit_amount'] = '';
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
        $tkey++;

        $journal_lines_data[$tkey]['f_journal_entry_line_id'] = '';
        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];

        if ($_POST['payment_type_id'] == 'IMPREST') {
          $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
        } else {
          $journal_lines_data[$tkey]['reference_source'] = "";
        }
        $journal_lines_data[$tkey]['reference_id'] = $_POST['bulk_employee_id'][$k];
        $journal_lines_data[$tkey]['account_id'] = $_POST['account_code_id'];
        $journal_lines_data[$tkey]['debit_amount'] = '';
        $journal_lines_data[$tkey]['credit_amount'] = $linesdata['payment_amount'];
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

        \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

      }


      $paymenttype = $_POST['payment_type_id'];
      if ($paymenttype == 'CHEQUE') {
        //Cheque no count update
        $chequeno = $_POST['cheque_no'];
        if ($chequeno != '') {
          $accno = $_POST['account_no'];

          $chequeupdate = \DB::update("UPDATE f_bank_cheque_lines_t set cheque_count='$chequeno' where bank_cheque_hdr_id='$accno'");
        }
      }

      //  mail for employee function start - m vignesh


      $emp = \Session::get('id');
      $to_persons = $_POST['bulk_employee_id'];
      $exps_amt = $_POST['bulk_expense_amount'];
      $total_amount = $_POST['bulk_expense_amount'];
      $purposes = $_POST['bulk_remarks'];
      $exp_date = $_POST['payment_date'];


      foreach ($to_persons as $key => $to_person) {
        $exp_amt = $exps_amt[$key];
        $purpos = $purposes[$key];
        $total_amt = $total_amount[$key];

        if ($purpos != '') {
          $purpose = " against $purpos";
        } else {
          $purpos = $_POST['remarks'];
          $purpose = " against $purpos";
        }

        // Get the employee's email and name
        $user_mail = \DB::select("select user_mail, first_name,group_id from tb_users where employee_id='$to_person' and active='Yes'");
        if ($user_mail[0]->group_id == "14") {
          if (!empty($user_mail) && !empty($user_mail[0]->user_mail)) {
            $created_mail = $user_mail[0]->user_mail;
            $emp_name = $user_mail[0]->first_name;
          } else {
            $created_mail = "aspire@jrkresearch.com";
            $emp_name = $user_mail[0]->first_name;
          }

          // Get reporting manager email
          $man_id = \DB::select("select reporting_manager,first_name from hr_employee_t where employee_id='$emp'");
          $managr_id = $man_id[0]->reporting_manager;
          $regards_name = $man_id[0]->first_name;
          $managr_mail = \DB::select("select user_mail from tb_users where employee_id='$managr_id' and group_id !='15'");

          if (!empty($managr_mail) && !empty($managr_mail[0]->user_mail)) {
            $manr_mail = $managr_mail[0]->user_mail;
          } else {
            $manr_mail = "aspire@jrkresearch.com";
          }

          // Email details
          //$from_mail = !empty(\Session::get('user_email')) ? \Session::get('user_email') : "aspire@jrkresearch.com";
          $from_mail = "expenses@jrkresearch.com";
          $sub = "Payment Initiated - for Mr. $emp_name";
          $to_mail = $created_mail;
          $cc_mail = "expenses@jrkresearch.com,payroll@jrkresearch.com";
          $cur_date = date("Y-m-d");
          $msg = "<p>Dear Mr.$emp_name,<br><br>A payment of Rs.$exp_amt/- has been processed on dated $exp_date $purpose.<br><br>The credited amount will be available in your account within the next 2-3 working days.<br><br>Note: 
 The disbursed amount may differ from the incurred expenses by the policy. If you need any clarification, please do not hesitate to contact payroll@jrkresearch.com.<br><br>Regards, <br>Accounts Team";
          // Send the email
          \Mail::send([], [], function ($message) use ($from_mail, $to_mail, $cc_mail, $sub, $msg) {
            $message->from($from_mail)
              ->to($to_mail)
              ->cc(explode(',', $cc_mail))
              ->subject($sub)
              ->setBody($msg, 'text/html');
          });
        }

      }

      // End of email section */

      \DB::commit();
      return response()->json(array('status' => 'success', 'message' => 'Payment Saved', 'id' => $id));
    } catch (\Illuminate\Database\QueryException $e) {
      $message = explode('(', $e->getMessage());
      $dbCode = rtrim($message[0], ']');
      $dbCode = trim($dbCode, '[');
      //dd($dbCode);
      \DB::rollback();
      return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
    }
  }


  public function balcloedit()
  {

    $sql = \DB::select("SELECT po_invoice_id,bill_number,invoice_date,bal_closure_remarks,created_by FROM p_po_invoice_hdr_t where po_invoice_id='" . $_GET['id'] . "'");
    if (isset($sql)) {
      if ($sql[0]->bill_number != '' || $sql[0]->invoice_date != '') {
        $data['bill_number'] = $sql[0]->bill_number;
        $data['invoice_date'] = $sql[0]->invoice_date;
        $data['bal_closure_remarks'] = $sql[0]->bal_closure_remarks;
        $data['created_by'] = $sql[0]->created_by;
        $data['update'] = 'update';
      } else if ($sql[0]->bill_number == '' && $sql[0]->invoice_date == '') {
        $data['bill_number'] = $sql[0]->bill_number;
        $data['invoice_date'] = $sql[0]->invoice_date;
        $data['bal_closure_remarks'] = $sql[0]->bal_closure_remarks;
        $data['created_by'] = $sql[0]->created_by;
        $data['update'] = 'create';
      }

    }
    return $data;

  }

  public function balcloseupdate()
  {
    //dd("fdgfd");
    $bal_amt = "0";
    $prd_id = $_GET['intprd_id'];
    $check = \DB::update("update p_po_invoice_hdr_t set bal_closure_remarks='" . $_GET['bal_closure_remarks'] . "',last_updated_by='" . $_GET['closed_by'] . "',balance_amount='" . $bal_amt . "' where po_invoice_id='" . $_GET['id'] . "'");

    //dd($check);        
    if ($check) {
      return 1;
    } else {
      return 0;
    }
  }

  public function balcloexpedit()
  {

    $sql = \DB::select("SELECT expense_id,expense_no,expense_date,bal_closure_remarks,created_by FROM f_expenses_t where expense_id='" . $_GET['id'] . "'");
    if (isset($sql)) {
      if ($sql[0]->expense_no != '' || $sql[0]->expense_date != '') {
        $data['expense_no'] = $sql[0]->expense_no;
        $data['expense_date'] = $sql[0]->expense_date;
        $data['bal_closure_remarks'] = $sql[0]->bal_closure_remarks;
        $data['created_by'] = $sql[0]->created_by;
        $data['update'] = 'update';
      } else if ($sql[0]->expense_no == '' && $sql[0]->expense_date == '') {
        $data['expense_no'] = $sql[0]->expense_no;
        $data['expense_date'] = $sql[0]->expense_date;
        $data['bal_closure_remarks'] = $sql[0]->bal_closure_remarks;
        $data['created_by'] = $sql[0]->created_by;
        $data['update'] = 'create';
      }

    }
    return $data;

  }

  public function balcloseexpupdate()
  {
    //dd("fdgfd");
    $bal_amt = "0";
    $pmt_sts = "1";
    $prd_id = $_GET['intprd_id'];
    $check = \DB::update("update f_expenses_t set bal_closure_remarks='" . $_GET['bal_closure_remarks'] . "',last_updated_by='" . $_GET['closed_by'] . "',balance_amount='" . $bal_amt . "',payment_status='" . $pmt_sts . "' where expense_id='" . $_GET['id'] . "'");

    //dd($check);        
    if ($check) {
      return 1;
    } else {
      return 0;
    }
  }

}
