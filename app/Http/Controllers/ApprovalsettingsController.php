<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Approvalsettings;
use App\Approvalsettingsline;
use Illuminate\Http\Request;

class ApprovalsettingsController extends Controller
{

    public $module = "materialbom";
    public function __construct()
    {
        $this->data = array();
        $this->model = new Approvalsettings;
        $this->submodel = new Approvalsettingsline;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'approvalsettings';
        $this->table = "m_approvalsettings_hdr_t";
        $this->subtable = "m_approvalsettings_line_t";
        $this->middleware('auth');
        $this->data['urlmenu'] = $this->indexs();
    }
    public function index()
    {

        return view('Approvalsettings.table', $this->data);
    }


    public function create($id = null)
    {

        if (isset($id)) {
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('m_approvalsettings_hdr_t')->where('approvalsettings_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['created_by'] = $this->jcombo("tb_users", "id", "username", \Session::get('id'));
            $this->data['module_name'] = $this->jcustomselect("a_lookuplines_t", "lookup_code", "lookup_code", $table[0]->module_name, 'and lookup_type="APPROVAL_MODULE"');
            $linestable = \DB::table('m_approvalsettings_line_t')->where('approvalsettings_hdr_id', $id)->get();
            $this->data['linedata'] = $linestable;
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->approver_id = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $value->approver_id);
            }

        } else {
            $this->data['pagemode'] = "create";
            $this->modelname = new Approvalsettings();
            $this->data['row'] = (object) array();
            $table = $this->modelname->getTableColumns();
            foreach ($table as $key => $val) {
                $this->data['row']->$val = '';
            }
            $this->data['created_by'] = $this->jcombo("tb_users", "id", "username", \Session::get('id'));
            $this->data['module_name'] = $this->jcustomselect("a_lookuplines_t", "lookup_code", "lookup_code", '', 'and lookup_type="APPROVAL_MODULE"');


            $this->data['linedata'] = array();
            $this->data['approver_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
        }
        return view("Approvalsettings.form", $this->data);
    }

    public function save(Request $request)
    {



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
        try {

            $id = $this->model->create($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');

            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }

    public function show(Approvalsettings $approvalsettings, $id = null)
    {
        $data = Approvalsettings::find($id);

        $this->data['module_name'] = $data['module_name'];
        $this->data['created_by'] = $this->idname("username", "tb_users", "id", $data['created_by']);

        $linesdata = \DB::table('m_approvalsettings_line_t')
            ->leftjoin('m_approvalsettings_hdr_t', 'm_approvalsettings_hdr_t.approvalsettings_hdr_id', '=', 'm_approvalsettings_line_t.approvalsettings_hdr_id')
            ->leftjoin('tb_users', 'tb_users.id', '=', 'm_approvalsettings_line_t.approver_id')
            ->where('m_approvalsettings_hdr_t.approvalsettings_hdr_id', $id)->get();
        foreach ($linesdata as $key => $value) {
            $this->data['approver'] = $this->idname("username", "tb_users", "id", $value->approver_id);

        }
        $this->data['linesdata'] = $linesdata;
        return view("Approvalsettings.view", $this->data);
    }


    public function edit(Approvalsettings $approvalsettings)
    {
        //
    }


    public function update(Request $request, Approvalsettings $approvalsettings)
    {
        //
    }

    public function destroy(Approvalsettings $approvalsettings)
    {

    }

    // data	
    public function getApprovalsettingsData(Request $request)
    {
        if ($request->ajax()) {
            $query = \DB::table('m_approvalsettings_hdr_t')
                ->select('m_approvalsettings_hdr_t.*');

            return DataTables::of($query)->make(true);
        }
    }


    public function approvalsettingschk(Request $request)
    {

        $edit_id = $_GET['edit_id'];
        if ($edit_id == '')
            $bom = \DB::table('m_approvalsettings_hdr_t')->where('module_name', $_GET['module_id'])->get();
        else {
            $whereData = [['module_name', $_GET['module_id']], ['approvalsettings_hdr_id', '!=', $edit_id]];

            $bom = \DB::table('m_approvalsettings_hdr_t')->where($whereData)->get();
        }


        if (count($bom) > 0)
            return 1;
        else
            return 0;


    }
}
