<?php

namespace App\Http\Controllers;
use App\Expenses;
use App\Expenseslines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class ExpensesController extends Controller
{
    public $module = "expenses";
    public function __construct()
    {
        $this->data = array();
        $this->table = "f_expenses_t";
        $this->subtable = "f_expenses_lines_t";
        $this->pageModule = "gstcode";
        $this->model = new Expenses;
        $this->submodel = new Expenseslines;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->table = "f_expenses_t";
        if ($this->data['pageMethod'] == "expenseapproval") {
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
        $table = \DB::table('f_expenses_t')->get();
        $this->data['datas'] = $table;
        return view('expenses.table', $this->data);
    }
    public function expenseindex()
    {
        return view('expenses.extable', $this->data);
    }

    public function getexpenserptdata()
    {

        $wh = '';

        if (isset($_GET['pq_filter'])) {
            $data = json_decode($_GET['pq_filter']);
            $data = $data->data;
            $table = array('f_expenses_t', 'f_expenses__lines_t');

            $wh .= $this->pqgridsearch('f_expenses_t', $data, $table);
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
        $result = \DB::select("SELECT COUNT(expense_id) AS count FROM f_expenses_t left join tb_users on (tb_users.id =f_expenses_t.created_by) where 1=1 $wh");
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

        $SQL = "SELECT f_expenses_t.expense_id,
                    f_expenses_t.expense_no,
                    f_expenses_t.expense_date,
                    f_expenses_t.expense_type,
                     f_expenses_t.invoice,
                      f_expenses_t.bill_date,
                     f_expenses_t.tds_applicable,
                     f_expenses_t.round_off,
                    f_expenses_t.expense_status,
                    f_expenses_t.remarks,
                    m_supplier_t.supplier_name as supplier_id,
                    f_expenses_t.expense_amount,
                    tb_users.username
                    FROM f_expenses_t
                    left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_expenses_t.expense_account_id) 
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
                    left join tb_users on (tb_users.id =f_expenses_t.created_by)
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



    public function getExpenseindexData()
    {

        $wh = '';
        if ($_GET['status'] != '') {
            $wh = " and  f_expenses_t.expense_status='" . $_GET['status'] . "'";
            $wh .= $grid_data = $this->grid_statuscheck('f_expenses_t', 'expense_date', 'expense_status', '=', "'" . $_GET['status'] . "'");
        } else {
            $wh .= $grid_data = $this->grid_check('f_expenses_t', 'expense_date');
        }


        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');


        $SQL = "SELECT f_expenses_t.expense_id,
				    f_expenses_t.expense_no,
                    f_expenses_t.expense_date,
                    f_expenses_t.expense_type,
                    f_expenses_t.expense_status,
                    f_expenses_t.remarks,
                         f_expenses_t.invoice,
                      f_expenses_t.bill_date,
                      f_expenses_t.credit_taken,
                      CONCAT(LEFT(MONTHNAME(f_expenses_t.credit_date),3),'-',YEAR(f_expenses_t.credit_date))as credit_date,
                   m_supplier_t.supplier_name,
					m_customers_t.customer_name,
					hr_employee_t.first_name,
                    a_lookuplines_t.lookup_code as emp_type,
                    f_expenses_t.expense_amount,
                    CASE WHEN b.first_name = a.first_name  AND f_expenses_t.expense_status != 'APPROVED' and f_expenses_t.expense_status != 'REJECTED' THEN
                        'Yet to Approve'
                        WHEN f_expenses_t.expense_status != 'APPROVED' and f_expenses_t.expense_status != 'REJECTED' THEN
                        'Yet to Approve'
                        WHEN f_expenses_t.expense_status = 'REJECTED' THEN
                        a.first_name
               			ELSE
               			a.first_name
               			END as approvedby,
               			b.first_name as createdby
                    FROM f_expenses_t
                    left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_expenses_t.expense_account_id) 
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
					left join m_customers_t on(m_customers_t.customer_id=f_expenses_t.customer_id)
					left join hr_employee_t on(hr_employee_t.employee_id=f_expenses_t.employee_id)
                    LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_employee_t.employee_type
                    left join hr_employee_t as b on (b.employee_id =f_expenses_t.created_by)
                    LEFT JOIN hr_employee_t AS a ON(a.employee_id = f_expenses_t.last_updated_by)
                    where 1=1 $wh ORDER BY f_expenses_t.expense_id DESC";



        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }

    public function create($id = null, $aprv = null)
    {

        $this->data['pageModule'] = 'expenses';
        $this->data['pageUrl'] = url('expenses');

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
            $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['gst_code_id'] = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="HSN"');
            $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
            $this->data['tds_prcnt'] = $this->jCombo('f_tds_slab_t', 'tds_percentage', 'tds_percentage', '');

            $this->data['linedata'] = array();

        } else {
            $table = \DB::table('f_expenses_t')->where('expense_id', $id)->get();
            $tablelines = \DB::table('f_expenses_lines_t')->where('expense_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
            $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->customer_id);
            $this->data['tds_prcnt'] = $this->jCombo('f_tds_slab_t', 'tds_percentage', 'tds_percentage', $table[0]->tds_prcnt);
            $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->expense_account_id);
            $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->tds_account_id);
            $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $table[0]->tax_group_id);
            $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->employee_id);

            $this->data['row'] = $table[0];
            $this->data['row']->reference_id = "";
        }
        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->expense_account_id = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->expense_account_id);
                $this->data['linedata'][$key]->tax_group_id = $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                $this->data['linedata'][$key]->gst_code_id = $this->data['gst_code_id'] = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->gst_code_id, 'and classification_name="HSN"');
            }
        }
        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }
        return view('expenses.form', $this->data);
    }


    public function expensespaycreate($id = null)
    {

        $this->data = array('pageModule' => 'expenses', 'pageUrl' => url('expenses'));
        $this->data['pageMethod'] = \Request::route()->getName();

        $table = \DB::table('f_expenses_t')->where('expense_id', $id)->get();

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

        return view('expenses.expensepayform', $this->data);
    }

    /* purpose for Save function*/
    public function save(Request $request)
    {

        $id = '';
        $form = $request->all();
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
        ]);
       // dd($request->hasFile('bulk_choosefile'));
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');
        unset($lines_data['reverse_charge']);
        //dd("ss");
        \DB::beginTransaction();
        if ($_POST['expense_status'] == "APPROVED") {
            if ($_POST['expense_no'] == "") {
                $seqno = $this->Seqnoe('EXP-', 'f_expenses_t', $_POST['expense_id'], 'expenses_count');
                $data['expense_no'] = $seqno[0];
                $data['expenses_count'] = $seqno[1];
            }
        }
        $data['balance_amount'] = $data['expense_amount'];

        /**  purpose update expnese in travel and imprest **/
        if ($_POST['source'] == "IMPREST") {
            $query = DB::table('hr_imprest_tbl')->where('imprest_id', $_POST['reference_id'])->update(['status' => "EXPENSE"]);
        }
        if ($_POST['source'] == "TRAVEL") {
            $query = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $_POST['reference_id'])->update(['status' => "EXPENSE"]);
        }

        try {


            if (!empty($_POST['expense_id'])) {
                $id = $_POST['expense_id'];

                // Update header record
                \DB::table($this->table)->where('expense_id', $id)->update($data);


            } else {
                $id = $this->model->insertRow($data);
            }

            unset($lines_data['existing_file']);

            if (empty($_POST['expense_id'])) {
                unset($lines_data['expense_line_id']);
            }
            
            $lid = $this->submodel->subgridSave($lines_data, $id);

                $dataupload = [];
$files = $request->file('bulk_choosefile');
$isNewExpense = empty($request->input('expense_id'));

if ($isNewExpense) {
    $lineIds = DB::table('f_expenses_lines_t')
        ->where('expense_id', $id)
        ->orderBy('expense_line_id', 'asc')
        ->pluck('expense_line_id')
        ->toArray();
} else {
    $lineIds = $request->input('bulk_expense_line_id', []);
}

if (!empty($files) && !empty($lineIds)) {

    foreach ($lineIds as $rowIndex => $expenseLineId) {

        // if no new uploads for this row, skip (keep whatever is already stored)
        if (empty($files[$rowIndex])) {
            continue;
        }

        // ✅ REPLACE: ignore old list, start fresh
        $finalFiles = [];

        foreach ($files[$rowIndex] as $file) {

            if (!$file || !$file->isValid()) continue;

            $name = $file->getClientOriginalName();
            $path = public_path("Uploads/expense/{$expenseLineId}");

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file->move($path, $name);
            $finalFiles[] = $name;
        }

        // ✅ Save ONLY newly uploaded files
        DB::table('f_expenses_lines_t')
            ->where('expense_line_id', $expenseLineId)
            ->update([
                'choosefile' => json_encode(array_values(array_unique($finalFiles)))
            ]);
    }
}


            if (isset($_POST['reverse_charge'])) {
                $reverse_charge = $_POST['reverse_charge'];
                $charge = implode(",", $reverse_charge);
                \DB::update("update f_expenses_t set reverse_charge='" . $charge . "' where expense_id='" . $id . "'");

            }

            /* Purpose For Journal Entry Insert*/
            if ($_POST['expense_status'] == "APPROVED") {

                $expensedate = $_POST['expense_date'];
                $org = \Session::get('organization');
                $loc = \Session::get('location');
                $compy = \Session::get('companyid');
                $name = $data['expense_no'];
                //Journal Header Insert
                $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$name','EXPENSES','$expensedate','$id','APPROVED','$compy','$loc','$org')");
                $jid = DB::getPdo()->lastInsertId();
                if ($_POST['expense_type'] == "SUPPLIER") {
                    $supplier_acc = \DB::table('m_supplier_t')->where('supplier_id', $_POST['supplier_id'])->get();
                    $supplier_acc = $supplier_acc[0]->account_structure_id;
                    $sec_id = $_POST['supplier_id'];
                    $sec_name = "SUPPLIER";
                } elseif ($_POST['expense_type'] == "CUSTOMER") {
                    $supplier_acc = \DB::table('m_customers_t')->where('customer_id', $_POST['customer_id'])->get();
                    $supplier_acc = $supplier_acc[0]->account_structure_id;
                    $sec_id = $_POST['customer_id'];
                    $sec_name = "CUSTOMER";
                } elseif ($_POST['expense_type'] == "EMPLOYEE") {
                    $supplier_acc = \DB::table('f_account_setting_t')->where('module_name', 'hrms')->get();
                    $supplier_acc = $supplier_acc[0]->imprest_account_id;
                    $sec_id = $_POST['employee_id'];
                    $sec_name = "EMPLOYEE";
                }
                //Journal Lines Insert

                $tkey = 0;
                foreach ($_POST['bulk_expense_account_id'] as $key => $val) {
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['expense_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = '';
                    $journal_lines_data[$tkey]['account_id'] = $val;
                    $journal_lines_data[$tkey]['debit_amount'] = $_POST['bulk_expense_line_amount'][$key];
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $total = 0;
                    $tax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $_POST['bulk_tax_group_id'][$key])->get();
                    if (!isset($_POST['reverse_charge'])) {


                        foreach ($tax_details as $taxval) {

                            $rate = $_POST['bulk_tax_amount'][$key];
                            $count = count($tax_details);

                            if ($rate > 0) {
                                $tkey++;
                                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                                $journal_lines_data[$tkey]['journal_date'] = $_POST['expense_date'];
                                $journal_lines_data[$tkey]['reference_source'] = "";
                                $journal_lines_data[$tkey]['reference_id'] = '';
                                $journal_lines_data[$tkey]['account_id'] = $taxval->input_tax_account_id;

                                $rate = $_POST['bulk_tax_amount'][$key];

                                $rate = round($rate / $count, 2);

                                $journal_lines_data[$tkey]['debit_amount'] = $rate;
                                $journal_lines_data[$tkey]['credit_amount'] = '';
                                $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                                $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                                $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                                $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                                $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                                $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                                $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                            }
                        }

                    }

                    $tkey++;
                }

                if ($_POST['tds_applicable'] == "YES") {
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['expense_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = '';
                    $journal_lines_data[$tkey]['account_id'] = $_POST['tds_account_id'];
                    $journal_lines_data[$tkey]['debit_amount'] = '';
                    //$empense_amt = $_POST['bulk_expense_amount'];
                    $journal_lines_data[$tkey]['credit_amount'] = $_POST['tds_amount'];
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;

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
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;

                }

                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                $journal_lines_data[$tkey]['journal_date'] = $_POST['expense_date'];
                $journal_lines_data[$tkey]['reference_source'] = $sec_name;
                $journal_lines_data[$tkey]['reference_id'] = $sec_id;
                $journal_lines_data[$tkey]['account_id'] = $supplier_acc;
                $journal_lines_data[$tkey]['debit_amount'] = '';
                //$empense_amt = $_POST['bulk_expense_amount'];
                $journal_lines_data[$tkey]['credit_amount'] = $_POST['expense_amount'];
                $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

                \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

                if (isset($_POST['reverse_charge'])) {
                    $expensedate = $_POST['expense_date'];
                    $org = \Session::get('organization');
                    $loc = \Session::get('location');
                    $compy = \Session::get('companyid');
                    $name = $data['expense_no'];
                    //Journal Header Insert
                    $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('REV-$name','REVERSE','$expensedate','$id','APPROVED','$compy','$loc','$org')");
                    $jid = DB::getPdo()->lastInsertId();

                    $journal_lines_data = array();
                    $tkey = -1;
                    foreach ($_POST['bulk_expense_account_id'] as $key => $val) {

                        foreach ($tax_details as $taxval) {
                            $tkey++;
                            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                            $journal_lines_data[$tkey]['journal_date'] = $_POST['expense_date'];
                            $journal_lines_data[$tkey]['reference_source'] = "";
                            $journal_lines_data[$tkey]['reference_id'] = '';
                            $journal_lines_data[$tkey]['account_id'] = $taxval->input_tax_account_id;

                            $rate = $_POST['bulk_tax_amount'][$key];
                            $count = count($tax_details);


                            $rate = round($rate / $count, 2);


                            $journal_lines_data[$tkey]['debit_amount'] = $rate;
                            $journal_lines_data[$tkey]['credit_amount'] = '';
                            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                            $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                            $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        }
                        foreach ($tax_details as $taxval) {
                            $tkey++;
                            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                            $journal_lines_data[$tkey]['journal_date'] = $_POST['expense_date'];
                            $journal_lines_data[$tkey]['reference_source'] = "";
                            $journal_lines_data[$tkey]['reference_id'] = '';
                            $journal_lines_data[$tkey]['account_id'] = $taxval->output_tax_account_id;

                            $rate = $_POST['bulk_tax_amount'][$key];
                            $count = count($tax_details);


                            $rate = round($rate / $count, 2);


                            $journal_lines_data[$tkey]['debit_amount'] = '';
                            $journal_lines_data[$tkey]['credit_amount'] = $rate;
                            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                            $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                            $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        }
                    }

                    \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

                }


            }

            /*End*/
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Expenses Saved Successfully', 'id' => $id));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            dd($dbCode);
            $dbCode = trim($dbCode, '[');

            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    /* purpose for Display View function*/
    public function show(request $request, $id = null)
    {
        if (isset($id)) {
            $vdata = \DB::table('f_expenses_t')->select(
                'f_expenses_t.*',
                'm_supplier_t.supplier_id',
                'tb_users.username',
                'm_supplier_t.supplier_name',
                'm_customers_t.customer_name',
                'tds.concatenated_segments as tds_account',
                'hr_employee_t.first_name',
                'f_tds_slab_t.tds_percentage'
            )
                ->leftjoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'f_expenses_t.supplier_id')
                ->leftjoin('m_customers_t', 'm_customers_t.customer_id', '=', 'f_expenses_t.customer_id')
                ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'f_expenses_t.employee_id')
                ->leftjoin('f_account_structure_t as tds', 'tds.f_account_structure_id', '=', 'f_expenses_t.tds_account_id')
                ->leftjoin('f_tds_slab_t', 'f_tds_slab_t.tds_slab_id', '=', 'f_expenses_t.tds_prcnt')
                ->leftjoin('tb_users', 'tb_users.id', '=', 'f_expenses_t.created_by')
                ->where('expense_id', $id)->get();

            $this->data['expense_no'] = $vdata[0]->expense_no;
            $this->data['expense_date'] = $vdata[0]->expense_date;
            $this->data['username'] = $vdata[0]->username;
            $this->data['expense_type'] = $vdata[0]->expense_type;

            $this->data['expense_amount'] = $vdata[0]->expense_amount;
            $this->data['employee_name'] = $vdata[0]->first_name;
            $this->data['supplier_name'] = $vdata[0]->supplier_name;
            $this->data['customer_name'] = $vdata[0]->customer_name;


            $this->data['invoice'] = $vdata[0]->invoice;
            $this->data['remarks'] = $vdata[0]->remarks;
            $this->data['tds_applicable'] = $vdata[0]->tds_applicable;
            $this->data['tds_percentage'] = $vdata[0]->tds_percentage;
            $this->data['tds_prcnt'] = $vdata[0]->tds_prcnt;
            $this->data['tds_amount'] = $vdata[0]->tds_amount;
            $this->data['tds_account'] = $vdata[0]->tds_account;
            $vlinesdata = \DB::table('f_expenses_lines_t')->select(
                'f_expenses_lines_t.*',
                'expense.concatenated_segments as expense_account',
                'm_tax_group_t.tax_group_id',
                'm_tax_group_t.tax_group_name',
                'f_gst_code_hdr_t.classification_code'
            )
                ->leftjoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'f_expenses_lines_t.tax_group_id')
                ->leftjoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'f_expenses_lines_t.gst_code_id')
                ->leftjoin('f_account_structure_t as expense', 'expense.f_account_structure_id', '=', 'f_expenses_lines_t.expense_account_id')
                ->where('expense_id', $id)->get();
            $this->data['vlinesdata'] = $vlinesdata;
            $this->data['expense_account'] = $vlinesdata[0]->expense_account;
            $this->data['tax_group_name'] = $vlinesdata[0]->tax_group_name;
            $this->data['expense_line_amount'] = $vlinesdata[0]->expense_line_amount;
            $this->data['gst_code_id'] = $vlinesdata[0]->classification_code;

            return view('expenses.view', $this->data);
        }
    }
    public function expenseapproval($id = null, $aprv = null)
    {
        $this->data['id'] = $id;
        $table = \DB::table('f_expenses_t')->where('expense_id', $id)->get();
        $this->data['row'] = $table[0];

        $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $table[0]->supplier_id);
        $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->employee_id);
        $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->customer_id);
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->tds_account_id);
        $this->data['tds_prcnt'] = $this->jCombo('f_tds_slab_t', 'tds_percentage', 'tds_percentage', $table[0]->tds_prcnt);
        $tablelines = \DB::table('f_expenses_lines_t')->where('expense_id', $id)->get();
        $this->data['linedata'] = $tablelines;

        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->expense_account_id = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->expense_account_id);
                $this->data['linedata'][$key]->tax_group_id = $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                $this->data['linedata'][$key]->gst_code_id = $this->data['gst_code_id'] = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->gst_code_id, 'and classification_name="HSN"');
            }
        }
        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }

        //        	dd($this->data);	
        return view('expenses.form', $this->data);
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
            $group = \DB::table('f_expenses_t')->where('invoice', $invoice)->where($colname, $id)->where('bill_date', $bill_date)->get();
            //  dd($group);
        } else {
            $group = \DB::table('f_expenses_t')->where('invoice', $invoice)->where($colname, $id)->where('bill_date', $bill_date)->where('expense_id', $expense_id)->get();

        }

        if (count($group) > 0)
            return 1;
        else
            return 0;
    }


}
