<?php

namespace App\Http\Controllers;
use App\Paymentterms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;


class PaymenttermsController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->model = new Paymentterms();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();
    }

    /*Karthigaa Purpose for Index Page*/
    public function index()
    {
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        $table = \DB::table('m_payment_terms_t')->get();
        $this->data['datas'] = json_encode($table);
        $this->data['pageMethod'] = "paymentterms";
        return view('paymentterms.form', $this->data);
    }


    // table data
    public function getGridData($type = null)
    {
        $wh = '';

        $compy = \Session::get('companyid');


        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  m_payment_terms_t.company_id=' . $compy;
        } else {
            $wh .= 'and  m_payment_terms_t.company_id=' . $compy;
        }


        $SQL = "SELECT m_payment_terms_t.payment_term_id,m_payment_terms_t.payment_term_name,m_payment_terms_t.description,m_payment_terms_t.active,m_payment_terms_t.payment_days,tb_users.first_name,tb_users.employee_id,tb_users.id as created_id FROM m_payment_terms_t left join tb_users on tb_users.id =m_payment_terms_t.created_by where 1=1 and m_payment_terms_t.company_id=$compy $wh order by m_payment_terms_t.payment_term_id DESC";



        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }


    // Purpose for Create Function
    public function create(Request $request)
    {
        // restrict illegal menu entry purpose - VIGNESH M

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

        $table = \DB::table('m_payment_terms_t')->get();
        $this->data['datas'] = json_encode($table);
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        return view('paymentterms.form', $this->data);

    }


    // save
    public function save(Request $request)
    {
        //dd($request);
        $edit_id = $request->input('payment_term_id');
        if ($edit_id == '') {
            $paymentterms = new PaymentTerms();
            $paymentterms->payment_term_name = $_POST['payment_term_name'];
            $paymentterms->description = $_POST['description'];
            $paymentterms->active = $_POST['active'];
            $paymentterms->payment_days = $_POST['payment_days'];
            $paymentterms->location_id = \Session::get('location');
            $paymentterms->organization_id = \Session::get('organization');
            $paymentterms->company_id = \Session::get('companyid');
            $paymentterms->created_by = \Session::get('id');
            $paymentterms->last_updated_by = \Session::get('id');
            $paymentterms->save();
            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";
            /**Auditlog**/
            $this->auditlog($edit_id, "paymentterms", $action, $_POST, "m_payment_terms_t");
            return response()->json(array('status' => 'success', 'message' => 'Payment Terms Saved Successfully', 'id' => $edit_id));
        } else {
            $action = "Edit";
            $edit_id = $_POST['payment_term_id'];
            PaymentTerms::find($edit_id)->update($_POST);
            /**Auditlog**/
            $this->auditlog($edit_id, "paymentterms", $action, $_POST, "m_payment_terms_t");
            return response()->json(array('status' => 'success', 'message' => 'Payment Terms Updated Successfully', 'id' => $edit_id));
        }


    }

    /*Karthigaa Purpose for Used Data Should Not Allow to Edit Function*/
    public function edit(Request $request, $id = null)
    {
        $column = array('payment_term_id', 'default_payment_terms_id', 'ar_payment_term_id', 'payment_term_id', 'default_payment_terms_id', 'payment_term_id', 'payment_term_id', 'payment_date');
        $table = array('s_quote_hdr_t', 'm_customers_t', 's_salesorder_hdr_t', 's_invoice_hdr_t', 'm_supplier_t', 'p_quotation_hdr_t', 'p_po_hdr_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();
            if (count($query) > 0) {
                $j = 1;
                return $j;
            }
        }
        return $j;
    }

    /*Karthigaa Purpose for Duplicate Validation Function*/
    public function getCheckname(Request $request)
    {
        $payment_term_id = $_GET['edit_id']; //dd($payment_term_id);

        if ($payment_term_id == '') {
            $whereData = [['payment_term_name', $_GET['payment_term_name']]];
            $department = DB::table('m_payment_terms_t')->where($whereData)->get();
        } else {
            $whereData = [['payment_term_name', $_GET['payment_term_name']], ['payment_term_id', '!=', $payment_term_id]];
            $department = DB::table('m_payment_terms_t')->where($whereData)->get();
        }

        if (count($department) > 0)
            return 1;
        else
            return 0;
    }
    /*End*/

    /* Purpose for Delete Function*/
    public function getRemove(Request $request, $id = null)
    {
        $column = array('payment_term_id', 'default_payment_terms_id', 'ar_payment_term_id', 'payment_term_id', 'default_payment_terms_id', 'payment_term_id', 'payment_term_id');
        $table = array('s_quote_hdr_t', 'm_customers_t', 's_salesorder_hdr_t', 's_invoice_hdr_t', 'm_supplier_t', 'p_quotation_hdr_t', 'p_po_hdr_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }
        if ($j == 0) {

            $query = \DB::table('m_payment_terms_t')->where('payment_term_id', $id)->delete();
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id, "paymentterms", $action, $id, "m_payment_terms_t");

        }

        return $j;

    }

}
