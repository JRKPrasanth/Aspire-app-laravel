<?php

namespace App\Http\Controllers;
use App\Paymentforinvoice;
use App\Imprest;
use App\Travel;
use Illuminate\Http\Request;
use Session;
use DB;
use Yajra\DataTables\DataTables;

class ImprestController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->model = new Imprest();
        $this->model1 = new Paymentforinvoice;
        $this->table = "p_payments_t";
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageModule'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();
    }
    public function travelamountindex(Request $request)
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

        $this->data['travel_date'] = date('Y-m-d');
        $this->data['employee'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number | first_name', \Session::get('emp_id'));
        $this->data['group_id'] = $this->jCombologin('m_position', 'position_id', 'position', '');
        return view('imprest.travel', $this->data);

    }
    public function savetravel(Request $request)
    {
        $edit_id = $request->input('edit_id');
        if ($edit_id == '') {

            $travel = new Travel();
            $travel->employee_id = $request->input('employee_id');
            $travel->group_id = $request->input('group_id');
            $travel->travel_date = $request->input('travel_date');
            $travel->description = $request->input('description');
            $travel->amount = $request->input('amount');
            $travel->active = $request->input('active');
            $travel->save();
            $name = $travel->getKeyName();
            $id = $travel->$name;
            $table = $travel->getTable();
            $column = $travel->getKeyName();
            $this->hrmssaveinsert($table, $column, $id, 1);
            //auditlog
            $this->auditlog($id, "travelamount", "create", $_POST, "hr_travel_amount_tbl");
            return 1;
        } else {

            $travel = new Travel();
            $edit_id = $_POST['edit_id'];
            Travel::find($edit_id)->update($_POST);
            /**Auditlog**/
            $this->auditlog($edit_id, "travelamount", "edit", $_POST, "hr_travel_amount_tbl");
            $table = $travel->getTable();
            $column = $travel->getKeyName();
            $this->hrmssaveinsert($table, $column, $edit_id, 2);
            return 2;
        }
    }


    public function travelamountgriddata(Request $request)
    {


        $wh = '';
        $comp = \Session::get('companyid');



        $SQL = "SELECT
          hr_travel_amount_tbl.*,
          m_position.position,
          CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_name
          FROM hr_travel_amount_tbl
          left join hr_employee_t on hr_employee_t.employee_id=hr_travel_amount_tbl.employee_id
          left join m_position on m_position.position_id=hr_travel_amount_tbl.group_id where 1=1 
          $wh";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }


    public function travelamountget($id = null)
    {
        $data = DB::table('hr_employee_t')->leftjoin('hr_travel_amount_tbl', 'hr_travel_amount_tbl.group_id', '=', 'hr_employee_t.position')->where('hr_employee_t.employee_id', $id)->get();
        if (count($data) > 0) {
            if ($data[0]->amount != '') {
                return $data[0]->amount;
            } {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function traveldelete(Request $request, $id = null)
    {


        $data = DB::table('hr_travel_amount_tbl')->where('travel_id', $id)->get();
        $query = DB::table('hr_travel_amount_tbl')->where('travel_id', $id)->delete();

        $this->auditlog($id, "TravelAmount", "delete", $data[0], "hr_travel_amount_tbl");
        return 2;

    }

    public function paymentimprestData(Request $request)
    {

        $company_id = Session::get('companyid');

        $SQL = "SELECT hr_imprest_tbl.imprest_number,hr_imprest_tbl.employee_id,report.employee_id as report_id,CONCAT(report.employee_number,'-',report.first_name) as report_number,hr_imprest_tbl.imprest_date,hr_imprest_tbl.
reason,hr_imprest_tbl.amount,hr_imprest_tbl.active,CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_number,hr_imprest_tbl.imprest_id,hr_imprest_tbl.status
FROM hr_imprest_tbl
LEFT JOIN hr_employee_t on hr_employee_t.employee_id=hr_imprest_tbl.employee_id
LEFT JOIN hr_employee_t as report on report.employee_id=hr_imprest_tbl.reporting_manager
WHERE 1=1 and hr_imprest_tbl.company_id=$company_id and hr_imprest_tbl.status='APPROVE'";

        $data = \DB::select($SQL);

        return DataTables::of($data)->make(true);

    }

    public function paymentesiData()
    {


        $company_id = Session::get('companyid');

        $SQL = "SELECT sum(amount) as company_amount,sum(amount_employee) as amount_employee,round(sum(amount)+sum(amount_employee)) as esi_amount,month,year,company_conribute_id,date FROM `hr_company_contribute_esi` where status=0 group by month,year";

        $data = \DB::select($SQL);

        return DataTables::of($data)->make(true);


    }

    // index page load function
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

        $logged_user = Session::get('emp_id');
        $result = DB::table('hr_employee_t')->where('employee_id', $logged_user)->get();
        $this->data['logged_id'] = $logged_user;
        $this->data['status'] = "INITIATED";
        $this->data['forwarded_id'] = $result[0]->reporting_manager;
        $this->data['employee'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number | first_name', \Session::get('emp_id'));
        $this->data['logged_id'] = $login_user = \Session::get('emp_id');
        $comp = \Session::get('companyid');
        $pageMethod = \Request::route()->getName();


        if ($pageMethod == "imprest") {

            return view('imprest.imprest', $this->data);

        } else {

            return view('imprest.approveimprest', $this->data);
        }

    }

    public function imprestgrid(Request $request)
    {

        $pageMethod = \Request::route()->getName();
        $comp = \Session::get('companyid');
        $wh = '';
        $logged_user = Session::get('emp_id');

        if ($pageMethod == "imprestgrid") {

            $wh = " and hr_imprest_tbl.employee_id=$logged_user";


        } else {

            $wh = "  and hr_imprest_tbl.status='PAYMENT'";

        }

        $SQL = "SELECT hr_imprest_tbl.imprest_number,hr_imprest_tbl.employee_id,report.employee_id as report_id,CONCAT(report.employee_number,'-',report.first_name) as report_number,hr_imprest_tbl.imprest_date,hr_imprest_tbl.
            reason,hr_imprest_tbl.amount,hr_imprest_tbl.active,CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_number,hr_imprest_tbl.imprest_id,hr_imprest_tbl.status
            FROM hr_imprest_tbl
            LEFT JOIN hr_employee_t on hr_employee_t.employee_id=hr_imprest_tbl.employee_id
            LEFT JOIN hr_employee_t as report on report.employee_id=hr_imprest_tbl.reporting_manager
            WHERE 1=1 and hr_imprest_tbl.company_id=$comp  $wh ";


        $data = \DB::select($SQL);

        return DataTables::of($data)->make(true);

    }
    public function imprestgriddata(Request $request)
    {

        $pageMethod = \Request::route()->getName();
        $comp = \Session::get('companyid');
        $wh = '';
        $logged_user = Session::get('emp_id');

        $wh = " and hr_imprest_tbl.reporting_manager=$logged_user  and hr_imprest_tbl.status='INITIATED'";


        $SQL = "SELECT hr_imprest_tbl.imprest_number,hr_imprest_tbl.employee_id,report.employee_id as report_id,CONCAT(report.employee_number,'-',report.first_name) as report_number,hr_imprest_tbl.imprest_date,hr_imprest_tbl.
            reason,hr_imprest_tbl.amount,hr_imprest_tbl.active,CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_number,hr_imprest_tbl.imprest_id,hr_imprest_tbl.status
            FROM hr_imprest_tbl
            LEFT JOIN hr_employee_t on hr_employee_t.employee_id=hr_imprest_tbl.employee_id
            LEFT JOIN hr_employee_t as report on report.employee_id=hr_imprest_tbl.reporting_manager
            WHERE 1=1 and hr_imprest_tbl.company_id=$comp $wh";


        $data = \DB::select($SQL);

        return DataTables::of($data)->make(true);

    }
    // save function
    public function store(Request $request)
    {
        $edit_id = $request->input('edit_id');
        if ($edit_id == '') {

            $imprest = new Imprest();
            $imprest->employee_id = $request->input('employee_id');
            $imprest->imprest_date = $request->input('imprest_date');
            $imprest->reason = $request->input('reason');
            $imprest->amount = $request->input('amount');
            $imprest->active = $request->input('active');
            $imprest->status = $request->input('status');
            $imprest->reporting_manager = $request->input('reporting_manager');
            $seqno = $this->Seqnoe('IMPREST', 'hr_imprest_tbl', '', 'imprest_count');
            $imprest->imprest_number = $seqno[0];
            $imprest->imprest_count = $seqno[1];
            $imprest->save();
            $name = $imprest->getKeyName();
            $id = $imprest->$name;
            $table = $imprest->getTable();
            $column = $imprest->getKeyName();
            $this->hrmssaveinsert($table, $column, $id, 1);
            //auditlog
            $this->auditlog($id, "imprest", "create", $_POST, "hr_imprest_tbl");
            return 1;
        } else {

            $imprest = new Imprest();
            $edit_id = $_POST['edit_id'];
            Imprest::find($edit_id)->update($_POST);
            /**Auditlog**/
            $this->auditlog($edit_id, "imprest", "edit", $_POST, "hr_imprest_tbl");
            $table = $imprest->getTable();
            $column = $imprest->getKeyName();
            $this->hrmssaveinsert($table, $column, $edit_id, 2);
            return 2;
        }
    }
    public function getRemove(Request $request, $id = null)
    {


        $data = DB::table('hr_imprest_tbl')->where('imprest_id', $id)->get();
        $query = DB::table('hr_imprest_tbl')->where('imprest_id', $id)->delete();
        $this->auditlog($id, "Imprest", "delete", $data[0], "hr_imprest_tbl");

        return 1;

    }

    public function imprestjournalcreate($id = null)
    {
        $data = DB::table('hr_imprest_tbl')->where('imprest_id', $id)->get();

        $this->data['row'] = (object) array();
        $this->data['row']->journal_entry_id = "";
        $this->data['row']->journal_name = "IMP-" . $data[0]->imprest_number;
        $this->data['row']->journal_date = date('Y-m-d');
        $this->data['row']->journal_type = "IMPREST";
        $this->data['row']->journal_category = "OTHERS";
        $this->data['row']->journal_reference = $id;
        $this->data['row']->journal_status = "";
        $this->data['aprvidenty'] = "";
        $this->data['id'] = '';
        $this->data['linedata'] = array();
        $this->data['account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');

        return view('journalentry.form', $this->data);
    }
    public function imprestexpensecreate($id = null)
    {

        $this->data['pageModule'] = 'expenses';
        $this->data['pageUrl'] = url('expenses');
        $data = DB::table('hr_imprest_tbl')->where('imprest_id', $id)->get();

        $this->data['row'] = (object) array();
        $this->data['row']->expense_id = "";
        $this->data['row']->expense_date = date('Y-m-d');
        $this->data['row']->expense_type = "";
        $this->data['row']->expense_amount = $data[0]->amount;
        $this->data['row']->expense_status = "INITIATED";
        $this->data['row']->source = "IMPREST";
        $this->data['row']->gst_code_id = "";
        $this->data['row']->concatenated_segments = "";
        $this->data['row']->tds_applicable = "";
        $this->data['row']->tds_amount = "";
        $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', '');
        $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
        $this->data['paid_through_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
        $this->data['row']->reverse_charge = "";
        $this->data['gst_treatment'] = $this->jcustomselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code', '', 'AND lookup_type="GST_TREATMENT"');
        $this->data['gst_code_id'] = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="HSN"');

        $this->data['tax_group_id'] = $this->jCombo('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
        $this->data['row']->gst_no = "";
        $this->data['row']->invoice = "";
        $this->data['row']->remarks = "";
        $this->data['row']->reference_id = $id;
        $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', '');
        $this->data['tds_prcnt'] = $this->jCombo('f_tds_slab_t', 'tds_percentage', 'tds_percentage', '');
        $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', '');
        $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', '');
        $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'first_name|last_name', '');
        $this->data['linedata'] = array();


        $this->data['aprvidenty'] = "";

        return view('expenses.form', $this->data);
    }

    // apporve data shown function
    public function approveimprest($id)
    {

        $this->data['edit_id'] = $id;
        $data = \DB::SELECT("select * from hr_imprest_tbl where imprest_id=$id");
        $this->data['employee'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number | first_name', $data[0]->employee_id);
        $this->data['reporting'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number | first_name', $data[0]->reporting_manager);
        $this->data['data'] = $data[0];
        return view('imprest.approveform', $this->data);

    }

    //approve imprest
    public function imprestapprovesave(Request $request)
    {
        $query = DB::table('hr_imprest_tbl')->where('imprest_id', $request->input('edit_id'))->update(['status' => $request->input('status')]);
        //auditlog
        $data['status'] = $request->input('status');
        $this->auditlog($request->input('edit_id'), "imprest", "approve", $data, "hr_imprest_tbl");
        return 1;
    }


    public function imprestreport(Request $request)
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


        return view('imprest.imprestreport', $this->data);
    }


    public function imprestreportData(Request $request)
    {


        $company_id = Session::get('companyid');
        $wh = " and hr_imprest_tbl.company_id=' $company_id' ";
        $log_id = Session::get('emp_id');

        $emp_data = DB::table("hr_employee_t")->where('employee_id', $log_id)->get();
        $dept = json_decode($emp_data[0]->department);

        if (in_array(28, $dept)) {
            $wh = "";
        } else {
            if ($log_id != "1") {
                $wh = "and hr_imprest_tbl.employee_id=$log_id or hr_imprest_tbl.reporting_manager='$log_id'";
            }
        }



        $query_result = \DB::select("SELECT hr_employee_t.department,hr_imprest_tbl.imprest_id,  hr_imprest_tbl.employee_id, hr_imprest_tbl.imprest_date,  hr_imprest_tbl.reason,hr_imprest_tbl.amount, hr_imprest_tbl.reporting_manager, hr_imprest_tbl.status,CONCAT(hr_employee_name.employee_number,'-', hr_employee_name.first_name) as employee_name,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as reporting_name FROM   hr_imprest_tbl  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_imprest_tbl.reporting_manager LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = hr_imprest_tbl.employee_id WHERE  1 = 1 $wh");

        if (count($query_result) > 0) {
            foreach ($query_result as $k => $v) {
                if ($v->department != '' && $v->department != null) {
                    $array = json_decode($v->department);
                    $query = DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
                    $deptnames = json_decode(json_encode($query), true);
                    $query_result[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
                } else {
                    $query_result[$k]->department = "";
                }
            }
        }

        return DataTables::of($query_result)->make(true);

    }

    public function paymentimprest(Request $request)
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

        return view('imprest.imprestpayment', $this->data);
    }





    public function paymentesi(Request $request)
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

        return view('imprest.esipayment', $this->data);
    }

    public function paymentpf(Request $request)
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


        return view('imprest.pfpayment', $this->data);
    }

    public function paymentpfData()
    {


        $company_id = Session::get('companyid');

        $SQL = "SELECT
	round(sum(amount)+sum(amount1)+sum(amount_employee)+sum(volunter_pf)+sum(edli_charges)+sum(admin_charges)) as
	pf_amount,date,month,year,employee_conribute_id,sum(amount) as amount_company,sum(amount1) as
	amount_company1,sum(amount_employee) as amount_employee,sum(volunter_pf) as v_pf,sum(edli_charges) as
	edli_charge,sum(admin_charges) as admin_charge FROM `hr_company_contribute_pf` where status=0 group by month,year";
        $data = \DB::select($SQL);

        return DataTables::of($data)->make(true);


    }

    public function imprestpaymentcreate()
    {
        if ($_GET['imp_id'] != "0") {
            $data = DB::table('hr_imprest_tbl')->where('imprest_id', $_GET['imp_id'])->get();
            $this->data['return'] = "paymentimprest";
            $this->data['row'] = (object) array();
            $this->data['row']->payment_id = "";
            $this->data['row']->payment_number = "";
            $imprestid = $_GET['imp_id'];
            $amount = \DB::select("select sum(amount) as amount from hr_imprest_tbl where imprest_id in($imprestid)");
            if ($amount == null) {
                $this->data['row']->invoice_amount = 0;
            } else {
                $imprest_amt = $amount[0]->amount;
                $this->data['row']->invoice_amount = round($imprest_amt, 2);
            }
            //$this->data['row']->invoice_amount =$data[0]->amount;
            $this->data['row']->payment_date = date('Y-m-d');
            $this->data['row']->cheque_date = date('Y-m-d');
            $payamt = $this->data['row']->payment_status = "";
            $this->data['row']->payment_amount = "";
            $this->data['row']->directpay = "No";
            $this->data['reference_id'] = $_GET['imp_id'];
            $this->data['row']->payment_source = "IMPREST";
            $this->data['row']->paid_amount = '';
            $this->data['employee_id'] = '';
            $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
            $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');

        } else {
            $this->data['return'] = "paymentimprest";
            $this->data['row'] = (object) array();
            $this->data['row']->payment_id = "";
            $this->data['row']->payment_number = "";
            $this->data['row']->invoice_amount = "";
            $this->data['row']->payment_date = date('Y-m-d');
            $this->data['row']->cheque_date = date('Y-m-d');
            $payamt = $this->data['row']->payment_status = "";
            $this->data['row']->payment_amount = "";
            $this->data['row']->directpay = "Yes";
            $this->data['reference_id'] = "";
            $this->data['row']->payment_source = "IMPREST";
            $this->data['row']->paid_amount = '';
            $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
            $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name', '');
        }
        return view('payments.imprestpayment', $this->data);


    }
    public function esipaymentcreate()
    {
        if ($_GET['pay_id'] != "0") {
            $data = DB::table('hr_company_contribute_esi')->where('company_conribute_id', $_GET['pay_id'])->get();
            $this->data['return'] = "paymentesi";
            $this->data['row'] = (object) array();
            $this->data['row']->payment_id = "";
            $this->data['row']->payment_number = "";
            $amount = \DB::select("SELECT round((sum(amount)+sum(amount_employee)),2) as esi_amount,round(sum(amount),2) as company_amount,round(sum(amount_employee),2) as amount_employee,month,year,date,company_conribute_id FROM `hr_company_contribute_esi` where status=0 and  month='" . $data[0]->month . "' and year='" . $data[0]->year . "'");

            if ($amount == null) {
                $this->data['row']->invoice_amount = 0;
                $this->data['row']->employee_amount = 0;
                $this->data['row']->company_amount = 0;
            } else {
                $esi_amt = $amount[0]->esi_amount;
                $this->data['row']->invoice_amount = round($esi_amt, 2);
                $this->data['row']->employee_amount = $amount[0]->amount_employee;
                $this->data['row']->company_amount = $amount[0]->company_amount;
            }
            //$this->data['row']->invoice_amount =$data[0]->amount;
            $this->data['row']->payment_date = date('Y-m-d');
            $this->data['row']->cheque_date = date('Y-m-d');
            $payamt = $this->data['row']->payment_status = "";
            $this->data['row']->payment_amount = "";
            $this->data['row']->directpay = "No";
            $this->data['reference_id'] = $_GET['pay_id'];
            $this->data['row']->payment_source = "ESI";
            $this->data['row']->paid_amount = '';
            $this->data['employee_id'] = '';
            $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
            $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');

        } else {
            $this->data['return'] = "paymentesi";
            $this->data['row'] = (object) array();
            $this->data['row']->payment_id = "";
            $this->data['row']->payment_number = "";
            $this->data['row']->invoice_amount = "";
            $this->data['row']->employee_amount = "";
            $this->data['row']->company_amount = "";
            $this->data['row']->payment_date = date('Y-m-d');
            $this->data['row']->cheque_date = date('Y-m-d');
            $payamt = $this->data['row']->payment_status = "";
            $this->data['row']->payment_amount = "";
            $this->data['row']->directpay = "Yes";
            $this->data['reference_id'] = "";
            $this->data['row']->payment_source = "ESI";
            $this->data['row']->paid_amount = '';
            $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
            $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name', '');
        }
        return view('payments.imprestpayment', $this->data);


    }
    public function pfpaymentcreate()
    {
        if ($_GET['pay_id'] != "0") {
            $data = DB::table('hr_company_contribute_pf')->where('employee_conribute_id', $_GET['pay_id'])->get();
            $this->data['return'] = "paymentpf";
            $this->data['row'] = (object) array();
            $this->data['row']->payment_id = "";
            $this->data['row']->payment_number = "";
            $amount = \DB::select("SELECT round((sum(amount)+sum(amount1)+sum(amount_employee)+sum(volunter_pf)+sum(edli_charges)+sum(admin_charges)),2) as pf_amount,date,month,year,employee_conribute_id,round(sum(amount),2) as amount_company,round(sum(amount1),2) as amount_company1,round(sum(amount_employee),2) as amount_employee,round(sum(volunter_pf),2) as v_pf,round(sum(edli_charges),2) as edli_charge,round(sum(admin_charges),2) as admin_charge FROM `hr_company_contribute_pf` where status=0 and month='" . $data[0]->month . "' and year='" . $data[0]->year . "'");

            if ($amount == null) {
                $this->data['row']->invoice_amount = 0;
            } else {
                $pf_amt = $amount[0]->pf_amount;
                $this->data['row']->invoice_amount = round($pf_amt, 2);
                $this->data['row']->employee_amount = $amount[0]->amount_employee;
                $this->data['row']->company_amount = round($amount[0]->amount_company, 2);
                $this->data['row']->company_amount1 = round($amount[0]->amount_company1, 2);
                $this->data['row']->v_pf = $amount[0]->v_pf;
                $this->data['row']->edli_charge = $amount[0]->edli_charge;
                $this->data['row']->admin_charge = $amount[0]->admin_charge;
            }
            //$this->data['row']->invoice_amount =$data[0]->amount;
            $this->data['row']->payment_date = date('Y-m-d');
            $this->data['row']->cheque_date = date('Y-m-d');
            $payamt = $this->data['row']->payment_status = "";
            $this->data['row']->payment_amount = "";
            $this->data['row']->directpay = "No";
            $this->data['reference_id'] = $_GET['pay_id'];
            $this->data['row']->payment_source = "PF";
            $this->data['row']->paid_amount = '';
            $this->data['employee_id'] = '';
            $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
            $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');

        } else {
            $this->data['return'] = "paymentpf";
            $this->data['row'] = (object) array();
            $this->data['row']->payment_id = "";
            $this->data['row']->payment_number = "";
            $this->data['row']->invoice_amount = "";
            $this->data['row']->payment_date = date('Y-m-d');
            $this->data['row']->cheque_date = date('Y-m-d');
            $payamt = $this->data['row']->payment_status = "";
            $this->data['row']->payment_amount = "";
            $this->data['row']->directpay = "Yes";
            $this->data['reference_id'] = "";
            $this->data['row']->payment_source = "PF";
            $this->data['row']->paid_amount = '';
            $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
            $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name', '');
        }

        return view('payments.imprestpayment', $this->data);


    }
    /*Karthigaa purpose for Save function these function renmae by kaviya on 29-05-2019  new function created below **/
    public function accountimprestsaveold(Request $request)
    {

        $employeeid = $_POST['employee_id'];
        if ($_POST['payment_source'] == "IMPREST") {
            DB::table('hr_imprest_tbl')->where('imprest_id', $_POST['reference_id'])->update(['status' => "PAYMENT"]);
            $query = DB::table('hr_imprest_tbl')->join('hr_emp_salary', 'hr_emp_salary.employee_id', '=', 'hr_imprest_tbl.employee_id')->where('imprest_id', $_POST['reference_id'])->select('hr_imprest_tbl.*', 'hr_emp_salary.*')->get();
        }
        if ($_POST['payment_source'] == "TRAVEL") {
            DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $_POST['reference_id'])->update(['status' => "PAYMENT"]);
            $query = DB::table('hr_employee_travel_claim_t')->join('hr_emp_salary', 'hr_emp_salary.employee_id', '=', 'hr_employee_travel_claim_t.employee_id')->where('travel_claim_id', $_POST['reference_id'])->select('hr_employee_travel_claim_t.*', 'hr_emp_salary.*')->get();
        }
        $id = '';
        $data = $this->validatePost($request->all(), $this->table, 'header');
        /*karthigaa Purpose for Auto Number*/
        if ($_POST['payment_number'] == "") {
            if ($employeeid != "") {
                $data['supplier_bank_id'] = $query[0]->bank_name;
                $data['supplier_ifsc_code'] = $query[0]->ifsc_code;
                $data['supplier_account_no'] = $query[0]->account_holder_name;
                $data['supplier_account_name'] = $query[0]->account_number;
            }
            $seqno = $this->Seqnoe('PMT-', 'p_payments_t', '', 'payment_count');
            $data['payment_number'] = $seqno[0];
            $data['payment_count'] = $seqno[1];
        } else {
            $seqno[0] = $_POST['payment_number'];
        }
        /*End*/

        \DB::beginTransaction();
        try {
            $id = $this->model1->insertRow($data);
            \DB::commit();
            //dd($_POST);
            //Cheque no count update
            $chequeno = $_POST['cheque_no'];
            if ($chequeno != '') {
                $accno = $_POST['account_no'];
                $chequeupdate = \DB::update("UPDATE f_bank_cheque_lines_t set cheque_count='$chequeno' where bank_cheque_hdr_id='$accno'");
            }
            if ($_POST['payment_source'] == "IMPREST") {
                $paymt_no = "IMPRESTPAYMENT-" . $data['payment_number'];
                $pay_name = "IMPRESTPAYMENT";
            }
            if ($_POST['payment_source'] == "TRAVEL") {
                $paymt_no = "TRAVELPAYMENT-" . $data['payment_number'];
                $pay_name = "TRAVELPAYMENT";
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

            if ($employeeid != "") {
                $journal_lines_data[$tkey]['reference_id'] = $_POST['employee_id'];
            } else {
                $journal_lines_data[$tkey]['reference_id'] = $query[0]->employee_id;
            }
            if ($_POST['payment_source'] == "IMPREST") {
                $journal_lines_data[$tkey]['account_id'] = $account_id_data[0]->imprest_account_id;
            }
            if ($_POST['payment_source'] == "TRAVEL") {
                $journal_lines_data[$tkey]['account_id'] = $account_id_data[0]->travelclaim_account_id;
            }


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
            if ($employeeid != "") {
                $journal_lines_data[$tkey]['reference_id'] = $_POST['employee_id'];
            } else {
                $journal_lines_data[$tkey]['reference_id'] = $query[0]->employee_id;
            }
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

    public function accountimprestsave(Request $request)
    {

        $employeeid = $_POST['employee_id'];
        if ($_POST['payment_source'] == "IMPREST") {
            DB::table('hr_imprest_tbl')->where('imprest_id', $_POST['reference_id'])->update(['status' => "PAYMENT"]);
            $query = DB::table('hr_imprest_tbl')->join('hr_emp_salary', 'hr_emp_salary.employee_id', '=', 'hr_imprest_tbl.employee_id')->where('imprest_id', $_POST['reference_id'])->select('hr_imprest_tbl.*', 'hr_emp_salary.*')->get();
        }
        if ($_POST['payment_source'] == "TRAVEL") {
            DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $_POST['reference_id'])->update(['status' => "PAYMENT"]);
            $query = DB::table('hr_employee_travel_claim_t')->join('hr_emp_salary', 'hr_emp_salary.employee_id', '=', 'hr_employee_travel_claim_t.employee_id')->where('travel_claim_id', $_POST['reference_id'])->select('hr_employee_travel_claim_t.*', 'hr_emp_salary.*')->get();
        }
        if ($_POST['payment_source'] == "ESI") {
            $query = DB::table('hr_company_contribute_esi')->where('company_conribute_id', $_POST['reference_id'])->get();
            DB::table('hr_company_contribute_esi')->where('month', $query[0]->month)->where('year', $query[0]->year)->update(['status' => "1"]);
        }
        if ($_POST['payment_source'] == "PF") {
            $query = DB::table('hr_company_contribute_pf')->where('employee_conribute_id', $_POST['reference_id'])->get();
            DB::table('hr_company_contribute_pf')->where('month', $query[0]->month)->where('year', $query[0]->year)->update(['status' => "1"]);
        }
        //dd('hi');
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
            'employee_amount',
            'company_amount',
            'company_amount1',
            'v_pf',
            'edli_charge',
            'admin_charge',
            'existing_file',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');

        /*karthigaa Purpose for Auto Number*/
        if ($_POST['payment_number'] == "") {
            if (count($query) > 0) {
                if ($employeeid != "") {
                    $data['supplier_bank_id'] = $query[0]->bank_name;
                    $data['supplier_ifsc_code'] = $query[0]->ifsc_code;
                    $data['supplier_account_no'] = $query[0]->account_holder_name;
                    $data['supplier_account_name'] = $query[0]->account_number;
                }
            }
            $seqno = $this->Seqnoe('PMT-', 'p_payments_t', '', 'payment_count');
            $data['payment_number'] = $seqno[0];
            $data['payment_count'] = $seqno[1];
        } else {
            $seqno[0] = $_POST['payment_number'];
        }
        /*End*/

        \DB::beginTransaction();
        try {
            //dd($_POST);
            $id = $this->model1->insertRow($data);
            \DB::commit();
            //dd($_POST);
            $chequeno = $_POST['cheque_no'];
            if ($chequeno != '') {
                $accno = $_POST['account_no'];
                $chequeupdate = \DB::update("UPDATE f_bank_cheque_lines_t set cheque_count='$chequeno' where bank_cheque_hdr_id='$accno'");
            }
            if ($_POST['payment_source'] == "IMPREST") {
                $paymt_no = "IMPRESTPAYMENT-" . $data['payment_number'];
                $pay_name = "IMPRESTPAYMENT";
            }
            if ($_POST['payment_source'] == "TRAVEL") {
                $paymt_no = "TRAVELPAYMENT-" . $data['payment_number'];
                $pay_name = "TRAVELPAYMENT";
            }
            if ($_POST['payment_source'] == "ESI") {
                $paymt_no = "ESIPAYMENT-" . $data['payment_number'] . "-" . $query[0]->month . "-" . $query[0]->year;
                $pay_name = "ESIPAYMENT";
            }
            if ($_POST['payment_source'] == "PF") {
                $paymt_no = "PFPAYMENT-" . $data['payment_number'] . "-" . $query[0]->month . "-" . $query[0]->year;
                $pay_name = "PFPAYMENT";
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
            if ($_POST['payment_source'] != "ESI" && $_POST['payment_source'] != "PF") {
                if ($employeeid != "") {
                    $journal_lines_data[$tkey]['reference_id'] = $_POST['employee_id'];
                } else {
                    $journal_lines_data[$tkey]['reference_id'] = $query[0]->employee_id;
                }
            } else {
                $journal_lines_data[$tkey]['reference_id'] = '';
            }
            if ($_POST['payment_source'] == "IMPREST") {
                $journal_lines_data[$tkey]['account_id'] = $account_id_data[0]->imprest_account_id;
            }
            if ($_POST['payment_source'] == "TRAVEL") {
                $journal_lines_data[$tkey]['account_id'] = $account_id_data[0]->travelclaim_account_id;
            }
            if ($_POST['payment_source'] == "ESI") {
                $account_id_esi = \DB::select("select * from f_hr_account_setting_t");
                if (count($account_id_esi) > 0) {
                    $journal_lines_data[$tkey]['account_id'] = $account_id_esi[0]->esi_employee_account_id;
                }
            }
            if ($_POST['payment_source'] == "PF") {
                $account_id_esi = \DB::select("select * from f_hr_account_setting_t");
                if (count($account_id_esi) > 0) {
                    $journal_lines_data[$tkey]['account_id'] = $account_id_esi[0]->pf_employee_account_id;
                }
            }

            if ($_POST['payment_source'] == "ESI" || $_POST['payment_source'] == "PF") {
                $journal_lines_data[$tkey]['debit_amount'] = $_POST['employee_amount'];
            } else {
                $journal_lines_data[$tkey]['debit_amount'] = $_POST['payment_amount'];
            }
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
            if ($_POST['payment_source'] != "ESI" && $_POST['payment_source'] != "PF") {
                if ($employeeid != "") {
                    $journal_lines_data[$tkey]['reference_id'] = $_POST['employee_id'];
                } else {
                    $journal_lines_data[$tkey]['reference_id'] = $query[0]->employee_id;
                }
            } else {
                $journal_lines_data[$tkey]['reference_id'] = '';
            }
            $journal_lines_data[$tkey]['account_id'] = $_POST['account_code_id'];
            $journal_lines_data[$tkey]['debit_amount'] = '';
            if ($_POST['payment_source'] == "ESI") {
                if ($_POST['penality_amount'] == '') {
                    $_POST['penality_amount'] = 0;
                }
                $journal_lines_data[$tkey]['credit_amount'] = $_POST['invoice_amount'] + $_POST['penality_amount'];
            } else if ($_POST['payment_source'] == "PF") {
                if ($_POST['penality_amount'] == '') {
                    $_POST['penality_amount'] = 0;
                }
                if ($_POST['discount_amount'] == '') {
                    $_POST['discount_amount'] = 0;
                }
                $journal_lines_data[$tkey]['credit_amount'] = $_POST['invoice_amount'] + $_POST['penality_amount'] - $_POST['discount_amount'];
            } else {
                $journal_lines_data[$tkey]['credit_amount'] = $_POST['payment_amount'];
            }
            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
            ;
            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
            if ($_POST['payment_source'] == "ESI") {
                $tkey++;
                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
                $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
                $journal_lines_data[$tkey]['reference_id'] = '';
                $account_id_esi = \DB::select("select * from f_hr_account_setting_t");
                if (count($account_id_esi) > 0) {
                    $journal_lines_data[$tkey]['account_id'] = $account_id_esi[0]->esi_company_account_id;
                }

                $journal_lines_data[$tkey]['debit_amount'] = $_POST['company_amount'];
                $journal_lines_data[$tkey]['credit_amount'] = '';
                $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                ;
                $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                if ($_POST['penality_amount'] != 0) {
                    $tkey++;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
                    $journal_lines_data[$tkey]['reference_id'] = '';

                    $journal_lines_data[$tkey]['account_id'] = '521';

                    $journal_lines_data[$tkey]['debit_amount'] = $_POST['penality_amount'];
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                }
            }
            if ($_POST['payment_source'] == "PF") {
                $tkey++;
                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
                $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
                $journal_lines_data[$tkey]['reference_id'] = '';
                $account_id_esi = \DB::select("select * from f_hr_account_setting_t");
                if (count($account_id_esi) > 0) {
                    $journal_lines_data[$tkey]['account_id'] = $account_id_esi[0]->pf_company_account_id;
                }

                $journal_lines_data[$tkey]['debit_amount'] = $_POST['company_amount'];
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
                $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
                $journal_lines_data[$tkey]['reference_id'] = '';
                $account_id_esi = \DB::select("select * from f_hr_account_setting_t");
                if (count($account_id_esi) > 0) {
                    $journal_lines_data[$tkey]['account_id'] = $account_id_esi[0]->pf_company1_account_id;
                }

                $journal_lines_data[$tkey]['debit_amount'] = $_POST['company_amount1'];
                $journal_lines_data[$tkey]['credit_amount'] = '';
                $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                ;
                $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                if ($_POST['v_pf'] != 0 && $_POST['v_pf'] != '') {
                    $tkey++;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
                    $journal_lines_data[$tkey]['reference_id'] = '';
                    $account_id_esi = \DB::select("select * from f_hr_account_setting_t");
                    if (count($account_id_esi) > 0) {
                        $journal_lines_data[$tkey]['account_id'] = $account_id_esi[0]->volunter_pf_account_id;
                    }

                    $journal_lines_data[$tkey]['debit_amount'] = $_POST['v_pf'];
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                }
                if ($_POST['edli_charge'] != 0 && $_POST['edli_charge'] != '') {
                    $tkey++;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
                    $journal_lines_data[$tkey]['reference_id'] = '';

                    $journal_lines_data[$tkey]['account_id'] = 131;


                    $journal_lines_data[$tkey]['debit_amount'] = $_POST['edli_charge'];
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                }
                if ($_POST['admin_charge'] != 0 && $_POST['admin_charge'] != '') {
                    $tkey++;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
                    $journal_lines_data[$tkey]['reference_id'] = '';

                    $journal_lines_data[$tkey]['account_id'] = 132;


                    $journal_lines_data[$tkey]['debit_amount'] = $_POST['admin_charge'];
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                }
                if ($_POST['discount_amount'] != 0 && $_POST['discount_amount'] != '') {
                    $tkey++;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";

                    $journal_lines_data[$tkey]['reference_id'] = '';


                    $journal_lines_data[$tkey]['account_id'] = '580';


                    $journal_lines_data[$tkey]['credit_amount'] = $_POST['discount_amount'];
                    $journal_lines_data[$tkey]['debit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

                }
                if ($_POST['penality_amount'] != 0) {
                    $tkey++;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['payment_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
                    $journal_lines_data[$tkey]['reference_id'] = '';
                    $journal_lines_data[$tkey]['account_id'] = '498';
                    $journal_lines_data[$tkey]['debit_amount'] = $_POST['penality_amount'];
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                }
            }

            \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

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
}
