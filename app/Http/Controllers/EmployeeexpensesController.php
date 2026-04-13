<?php

namespace App\Http\Controllers;
use App\Empexpenses;
use App\Empexpenseslines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;


class EmployeeexpensesController extends Controller
{
    public $module = "employeeexpenses";
    public function __construct()
    {
        $this->data = array();
        $this->table = "f_emp_expenses_t";
        $this->subtable = "f_emp_expenses_lines_t";
        $this->pageModule = "gstcode";
        $this->model = new Empexpenses;
        $this->submodel = new Empexpenseslines;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->table = "f_emp_expenses_t";
        if ($this->data['pageMethod'] == "empexpenseapproval") {
            $this->data['status'] = "INITIATED";
        } else {
            $this->data['status'] = "";
        }

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

        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $table = \DB::table('f_emp_expenses_t')->get();
        $this->data['datas'] = $table;

        return view('employeeexpenses.table', $this->data);
    }

    public function expenseindex()
    {
        return view('employeeexpenses.extable', $this->data);
    }

    public function getexpenserptdata()
    {

        $wh = '';

        if (isset($_GET['pq_filter'])) {
            $data = json_decode($_GET['pq_filter']);
            $data = $data->data;
            $table = array('f_emp_expenses_t', 'f_emp_expenses_lines_t');

            $wh .= $this->pqgridsearch('f_emp_expenses_t', $data, $table);
        }
        //                dd($wh);
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        // $sord = $_GET['sord'];

        $sidx = '';
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(expense_id) AS count FROM f_emp_expenses_t left join tb_users on (tb_users.id =f_emp_expenses_t.created_by) where 1=1 $wh");
        // dd($result);
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

        $compy = \Session::get('companyid');

        $SQL = "SELECT f_emp_expenses_t.expense_id,
                    f_emp_expenses_t.expense_no,
                    f_emp_expenses_t.expense_date,
                    f_emp_expenses_t.expense_type,
                     f_emp_expenses_t.invoice,
                      f_emp_expenses_t.bill_date,
                     f_emp_expenses_t.tds_applicable,
                     f_emp_expenses_t.round_off,
                    f_emp_expenses_t.expense_status,
                    f_emp_expenses_t.remarks,
                    m_supplier_t.supplier_name as supplier_id,
                    f_emp_expenses_t.expense_amount,
                    tb_users.username
                    FROM f_emp_expenses_t
                    left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_emp_expenses_t.expense_account_id) 
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_emp_expenses_t.supplier_id)
                    left join tb_users on (tb_users.id =f_emp_expenses_t.created_by)
                    where 1=1 $wh ORDER BY $sidx ";
        $result = \DB::select($SQL);
        // dd($result);
        $responce->rows[] = '';
        $responce->data = $result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
    }



    public function getempExpenseindexData()
    {

        $wh = '';
        $wh1 = '';
        if ($_GET['status'] != '') {
            $wh1 = " and  f_emp_expenses_t.expense_status='" . $_GET['status'] . "'";
            $wh .= $grid_data = $this->grid_statuscheck('v1', 'expense_date', 'expense_status', '=', "'" . $_GET['status'] . "'");
        } else {
            $wh .= $grid_data = $this->grid_check('v1', 'expense_date');
        }

        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        $SQL = "select * from (SELECT f_emp_expenses_t.expense_id,
				            f_emp_expenses_t.expense_no,
				            f_emp_expenses_lines_t.bill_no,
                    f_emp_expenses_t.expense_date,
                    f_emp_expenses_t.expense_status,
                    f_emp_expenses_lines_t.remarks,
                    f_emp_expenses_t.expense_amount,f_emp_expenses_t.company_id,f_emp_expenses_t.location_id,
                    tb_users.first_name
                    FROM f_emp_expenses_t
                    left join tb_users on (tb_users.id =f_emp_expenses_t.created_by)
                     left join f_emp_expenses_lines_t ON f_emp_expenses_lines_t.expense_id = f_emp_expenses_t.expense_id
                    where 1=1 $wh1 GROUP BY f_emp_expenses_t.expense_id) as v1 where 1=1 $wh ORDER BY v1.expense_id DESC";


        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }




    public function create($id = null, $aprv = null)
    {
        //$this->data =array('pageModule'=>'expenses','pageUrl'=>url('expenses'));
        $this->data['pageModule'] = 'empexpenses';
        $this->data['pageUrl'] = url('empexpenses');

        if ($id == null) {
            $this->data['row'] = (object) array();
            $this->data['row']->expense_id = "";
            $this->data['row']->expense_date = date('Y-m-d');
            $this->data['row']->bill_date = date('Y-m-d');
            $this->data['row']->expense_type = "";
            $this->data['row']->expense_amount = "";
            $this->data['row']->expense_status = "INITIATED";
            $this->data['row']->gst_code_id = "";
            $this->data['row']->source = "";
            $this->data['row']->reverse_charge = "";
            $this->data['row']->tds_applicable = "";
            $this->data['row']->tds_prcnt = "";
            $this->data['row']->tds_amount = "";
            $this->data['row']->concatenated_segments = "";
            $this->data['row']->invoice = "";
            $this->data['row']->reference_id = "";
            $this->data['row']->remarks = "";
            $this->data['row']->round_off = "";
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', '');
            $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', '');
            $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
            //$this->data['employee_id']= $this->jCombologin('hr_employee_t','employee_id','employee_number|first_name','');
            $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['tds_account_id'] = $this->jcustomselecttool('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '', 'and concatenated_segments like "%TDS%" ');
            $this->data['gst_code_id'] = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="HSN"');
            $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
            $this->data['tds_prcnt'] = $this->jCombo('f_tds_slab_t', 'tds_percentage', 'tds_percentage', '');

            $this->data['linedata'] = array();

        } else {
            $table = \DB::table('f_emp_expenses_t')->where('expense_id', $id)->get();
            $tablelines = \DB::table('f_emp_expenses_lines_t')->where('expense_id', $id)->get();
            $this->data['linedata'] = $tablelines;



            $this->data['row'] = $table[0];
            $this->data['row']->reference_id = "";
        }
        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->employee_id = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $value->employee_id);
                $this->data['linedata'][$key]->expense_account_id = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->expense_account_id);
                $this->data['linedata'][$key]->tds_account_id = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->tds_account_id);
                $this->data['linedata'][$key]->tds_prcnt = $this->jCombo('f_tds_slab_t', 'tds_percentage', 'tds_percentage', $value->tds_percentage);
            }
        }
        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }
        return view('employeeexpenses.form', $this->data);
    }
    public function expensespaycreate($id = null)
    {

        $this->data = array('pageModule' => 'employeeexpenses', 'pageUrl' => url('employeeexpenses'));


        $table = \DB::table('f_emp_expenses_t')->where('expense_id', $id)->get();
        //        dd($table);
        $this->data['row'] = (object) array();
        $this->data['row']->expense_id = "";
        $this->data['row']->expense_number = "";
        $this->data['row']->expense_date = date('Y-m-d');
        $this->data['row']->expense_type = $table[0]->expense_type;
        $this->data['row']->expense_amount = $table[0]->expense_amount;
        $this->data['row']->expense_status = "INITIATED";
        $this->data['row']->source = $table[0]->source;
        $this->data['row']->gst_code_id = $table[0]->gst_code_id;
        $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $table[0]->supplier_id);
        $this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name|last_name', $table[0]->employee_id);
        $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
        $this->data['row']->reverse_charge = "";
        $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $table[0]->tax_group_id);
        $this->data['row']->invoice = $table[0]->invoice;
        $this->data['row']->pay_amount = "";
        $this->data['row']->remarks = $table[0]->remarks;

        return view('employeeexpenses.expensepayform', $this->data);
    }

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
            'existing_file',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');

        \DB::beginTransaction();
        if ($_POST['expense_status'] == "APPROVED") {
            if ($_POST['expense_no'] == "") {

                $seqno = $this->Seqnoe('EMP/EXP-', 'f_emp_expenses_t', $_POST['expense_id'], 'expenses_count');
                $data['expense_no'] = $seqno[0];
                $data['expenses_count'] = $seqno[1];
            }
        }
        $data['balance_amount'] = $data['expense_amount'];


        try {

            if (!empty($_POST['expense_id'])) {
                $id = $_POST['expense_id'];

                // Update header record
                \DB::table($this->table)->where('expense_id', $id)->update($data);

            } else {
                $id = $this->model->insertRow($data);
            }

            unset($lines_data['existing_file']);
            $lid = $this->submodel->subgridSave($lines_data, $id);



            foreach ($lid['id'] as $k => $v) {
                if ($request->hasfile('bulk_choosefile' . $k) && $_POST['bulk_existing_file'][$k] == '') {
                    foreach ($request->file('bulk_choosefile' . $k) as $file) {
                        $name = $file->getClientOriginalName();
                        $file->move(public_path() . '/Uploads/empexpense/' . $v . '/', $name);
                        $dataupload[$v][] = $name;
                    }
                    $attachfile_name = json_encode($dataupload[$v]);
                    \DB::update("update f_emp_expenses_lines_t set choosefile='" . $attachfile_name . "' where expense_line_id=" . $v);

                } else if (isset($_POST['bulk_existing_file'][$k])) {

            if ($_POST['bulk_existing_file'][$k] != '') {

                $filess = explode(",", $_POST['bulk_existing_file'][$k]);

                if ($request->hasFile('bulk_choosefile' . $k)) {

                    foreach ($request->file('bulk_choosefile' . $k) as $file) {

                        $name = $file->getClientOriginalName();

                        $destinationPath = public_path('Uploads/empexpense/' . $v);

                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }

                        $file->move($destinationPath, $name);

                        $dataupload[$v][] = $name;
                        $filess[] = $name;
                    }
                }

                $upfile = json_encode($filess);

                \DB::update("update f_emp_expenses_lines_t set choosefile=? where expense_line_id=?", [$upfile, $v]);

            } else {

                \DB::update("update f_emp_expenses_lines_t set choosefile='' where expense_line_id=?", [$v]);

            }
        }

            }


            /*Karthigaa Purpose For Journal Entry Insert*/
            if ($_POST['expense_status'] == "APPROVED") {
                $expensedate = $_POST['expense_date'];
                $org = \Session::get('organization');
                $loc = \Session::get('location');
                $compy = \Session::get('companyid');
                $name = $data['expense_no'];
                //Journal Header Insert
                $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$name','EMPLOYEE EXPENSES','$expensedate','$id','APPROVED','$compy','$loc','$org')");
                $jid = DB::getPdo()->lastInsertId();


                //Journal Lines Insert

                $tkey = 0;
                foreach ($_POST['bulk_employee_id'] as $key => $employee_id) {
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['expense_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
                    $journal_lines_data[$tkey]['reference_id'] = $employee_id;
                    $journal_lines_data[$tkey]['account_id'] = $_POST['bulk_expense_account_id'][$key];
                    $journal_lines_data[$tkey]['debit_amount'] = $_POST['bulk_expense_line_amount'][$key];
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

                    $tkey++;
                }


                foreach ($_POST['bulk_employee_id'] as $keyy => $vall) {

                    $supplier_acc = \DB::table('f_account_setting_t')->where('module_name', 'hrms')->get();
                    $supplier_acc = $supplier_acc[0]->imprest_account_id;
                    //	$sec_id=$_POST['employee_id'];

                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['expense_date'];
                    $journal_lines_data[$tkey]['reference_source'] = 'EMPLOYEE';
                    $journal_lines_data[$tkey]['reference_id'] = $vall;
                    $journal_lines_data[$tkey]['account_id'] = $supplier_acc;
                    $journal_lines_data[$tkey]['debit_amount'] = '';
                    $journal_lines_data[$tkey]['credit_amount'] = $_POST['bulk_emp_exp_total'][$keyy];
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    //dd($journal_lines_data); 
                    $tkey++;
                }

                // purpose of if tds Applicable then entry for journal line - VIGNESH M
                $bulk_tds_account_ids = array_filter($_POST['bulk_tds_account_id'], function ($value) {
                    return !empty($value);
                });
                //  dd($bulk_tds_account_ids);
                if (!empty($bulk_tds_account_ids)) {
                    foreach ($_POST['bulk_tds_account_id'] as $key => $val) {

                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['expense_date'];
                        $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
                        $journal_lines_data[$tkey]['reference_id'] = $_POST['bulk_employee_id'][$key];
                        $journal_lines_data[$tkey]['account_id'] = $val;
                        $journal_lines_data[$tkey]['debit_amount'] = '';
                        $journal_lines_data[$tkey]['credit_amount'] = $_POST['bulk_tds_amount'][$key];
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $total = 0;


                        $tkey++;
                    }
                }


                if (!empty($_POST['round_off'])) {
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['expense_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = '';
                    $roundoff_acc = \DB::select("select roundoff_account_id from f_account_setting_t where module_name='roundoff'");
                    $journal_lines_data[$tkey]['account_id'] = $roundoff_acc[0]->roundoff_account_id;
                    if ($_POST['round_off'] > 0) {
                        $journal_lines_data[$tkey]['debit_amount'] = ABS($_POST['round_off']);
                        $journal_lines_data[$tkey]['credit_amount'] = "";
                    } else {
                        $journal_lines_data[$tkey]['debit_amount'] = "";
                        $journal_lines_data[$tkey]['credit_amount'] = ABS($_POST['round_off']);
                    }

                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;

                }

                \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
            }

            /*End*/
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Employee Expenses Saved Successfully', 'id' => $id));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            dd($dbCode);
            $dbCode = trim($dbCode, '[');

            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    /*Karthigaa purpose for Display View function*/
    public function show(request $request, $id = null)
    {
        if (isset($id)) {
            $vdata = \DB::table('f_emp_expenses_t')->select('f_emp_expenses_t.*', 'tb_users.username')
                ->leftjoin('tb_users', 'tb_users.id', '=', 'f_emp_expenses_t.created_by')
                ->where('expense_id', $id)->get();

            $this->data['expense_no'] = $vdata[0]->expense_no;
            $this->data['expense_date'] = $vdata[0]->expense_date;
            $this->data['username'] = $vdata[0]->username;


            $this->data['expense_amount'] = $vdata[0]->expense_amount;

            $this->data['remarks'] = $vdata[0]->remarks;

            $vlinesdata = \DB::table('f_emp_expenses_lines_t')->select('f_emp_expenses_lines_t.*', 'tds_expense.concatenated_segments as tds_account', 'expense.concatenated_segments as expense_account', 'hr_employee_t.employee_number', 'hr_employee_t.first_name')
                ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'f_emp_expenses_lines_t.employee_id')
                ->leftjoin('f_account_structure_t as expense', 'expense.f_account_structure_id', '=', 'f_emp_expenses_lines_t.expense_account_id')
                ->leftjoin('f_account_structure_t as tds_expense', 'tds_expense.f_account_structure_id', '=', 'f_emp_expenses_lines_t.tds_account_id')
                ->where('expense_id', $id)->get();
            $this->data['vlinesdata'] = $vlinesdata;
            $this->data['expense_account'] = $vlinesdata[0]->expense_account;
            $this->data['expense_line_amount'] = $vlinesdata[0]->expense_line_amount;

            return view('employeeexpenses.view', $this->data);
        }
    }
    public function expenseapproval($id = null, $aprv = null)
    {
        $this->data['id'] = $id;
        $table = \DB::table('f_emp_expenses_t')->where('expense_id', $id)->get();
        $this->data['row'] = $table[0];

        $tablelines = \DB::table('f_emp_expenses_lines_t')->where('expense_id', $id)->get();
        $this->data['linedata'] = $tablelines;

        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->employee_id = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $value->employee_id);
                $this->data['linedata'][$key]->expense_account_id = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->expense_account_id);
                $this->data['linedata'][$key]->tds_account_id = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->tds_account_id);
                $this->data['linedata'][$key]->tds_prcnt = $this->jCombo('f_tds_slab_t', 'tds_percentage', 'tds_percentage', $value->tds_percentage);


            }
        }
        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }

        //        	dd($this->data);	
        return view('employeeexpenses.form', $this->data);
    }
    /*ajith Purpose for Duplicate Validation Function*/
    public function expansenamechk(Request $request)
    {
        //  dd($request);
        $expense_id = $_REQUEST['expense_id'];
        $invoice = $_REQUEST['invoice'];
        $expense_type = $_REQUEST['expense_type'];
        $bill_date = date("Y-m-d", strtotime($_REQUEST['bill_date']));
        // dd($bill_date);
        $employee_id = $_REQUEST['employee_id'];
        $supplier_id = $_REQUEST['supplier_id'];
        $customer_id = $_REQUEST['customer_id'];
        if ($expense_type == 'CUSTOMER') {
            $id = $customer_id;
            $colname = 'customer_id';
        } else if ($expense_type == 'SUPPLIER') {
            $id = $supplier_id;
            $colname = 'supplier_id';
        } else if ($expense_type == 'EMPLOYEE') {
            $id = $employee_id;
            $colname = 'employee_id';
        }
        if ($expense_id == '') {
            $group = \DB::table('f_emp_expenses_t')->where('invoice', $invoice)->where($colname, $id)->where('bill_date', $bill_date)->get();
            //  dd($group);
        } else {
            $group = \DB::table('f_emp_expenses_t')->where('invoice', $invoice)->where($colname, $id)->where('bill_date', $bill_date)->where('expense_id', $expense_id)->get();
            // $whereData = [['v', $_REQUEST['payment_method_name']],['payment_method_id', '!=', $payment_method_id]];
            // $group=\DB::table('f_emp_expenses_t')->where($whereData)->get();
        }
        // dd("fg");
        if (count($group) > 0)
            return 1;
        else
            return 0;
    }


}
