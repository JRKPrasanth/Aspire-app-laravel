<?php

namespace App\Http\Controllers;

use App\Machinelog;
use App\Ginlines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Validator, DB;
use yajra\datatables\datatables;
use App\Http\Controllers\Controller;


class MachinelogController extends Controller
{
    public $module = "machinelog";
    public function __construct()
    {
        $this->data = array();
        $this->table = "b_machine_log_t";
        //$this->subtable="p_gin_lines_t";
        $this->pageModule = "machinelog";
        $this->model = new Machinelog;
        //$this->submodel=new Ginlines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'machinelog',
            'pageUrl' => url('machinelog'),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu'] = $this->indexs();
    }
    /*Karthigaa Purpose For :Index Function to Call Table Blade*/
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

        $this->data['machine_id'] = $this->jqgridselect('w_machine_hdr_t', 'machine_hdr_id', 'machine_name');
        $this->data['product_id'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $table = \DB::table('b_machine_log_t')->get();
        $this->data['datas'] = json_encode($table);
        $this->data['pageMethod'] = "machinelog";
        return view('machinelog.table', $this->data);
    }
    /* Karthigaa purpose for Display Data in JQgrid function */

    /*jk grid index */

    public function getmachinelogData(Request $request)
    {

        if ($request->ajax()) {
            $data = \DB::table('b_machine_log_t')
                ->leftJoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'b_machine_log_t.machine_id')
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'b_machine_log_t.product_id')
                ->leftJoin('tb_users', 'tb_users.id', '=', 'b_machine_log_t.created_by')
                ->select([
                    'b_machine_log_t.id',
                    'tb_users.first_name',
                    'm_products_t.concatenated_product',
                    'b_machine_log_t.batch_number',
                    'w_machine_hdr_t.machine_name',
                    'b_machine_log_t.remarks',
                    'b_machine_log_t.process_dept',
                    'b_machine_log_t.date',
                    'b_machine_log_t.quantity',
                    'b_machine_log_t.damages',
                    'b_machine_log_t.running_hours'
                ]);

            return DataTables::of($data)->make(true);
        }

    }

    /*end*/


    /*Karthigaa Purpose For Create Function*/
    public function machinelogcreate($id = null)
    {
        $row = $this->model::find($id);
        $this->data['row']['id'] = '';
        $this->data['row']['process_dept'] = '';
        //$this->data['row']['machine_id']='';
        //$this->data['row']['product_id']='';
        $this->data['row']['batch_number'] = '';
        $this->data['row']['quantity'] = '';
        $this->data['row']['running_hours'] = '';
        $this->data['row']['created_at'] = date('Y-m-d');
        $this->data['row']['date'] = date('Y-m-d');
        $this->data['row']['damages'] = '';
        $this->data['row']['remarks'] = '';

        $this->data['machine_id'] = $this->jcustomselectcomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '', " and machine_code like '%jrk/eq%' ");
        $this->data['product_id'] = $this->jcustomselectcomp(' m_products_t', 'product_id', 'concatenated_product', '', "and product_group_id IN (1,4)");
        $this->data['macnameopt'] = $this->jqgridselect('w_machine_hdr_t', 'machine_hdr_id', 'machine_name');

        $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');

        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        return view('machinelog.form', $this->data);
    }

    /*jk save ftn */
    public function save(Request $request)
    {
        $machnlog = new machinelog();

        $edit_id = $request->input('id');
        //dd($edit_id);
        if ($edit_id == '') {
            $machnlog->process_dept = $_POST['process_dept'];
            $machnlog->machine_id = $_POST['machine_id'];
            $machnlog->product_id = $_POST['product_id'];
            $machnlog->remarks = $_POST['remarks'];
            $machnlog->date = date('Y-m-d', strtotime($_POST['date']));
            $machnlog->quantity = $_POST['quantity'];
            $machnlog->batch_number = $_POST['batch_number'];
            $machnlog->damages = $_POST['damages'];
            $machnlog->running_hours = $_POST['running_hours'];
            $machnlog->created_by = $_POST['created_by'];
            $machnlog->last_updated_by = \Session::get('id');
            $machnlog->updated_at = date('Y-m-d H:i:s');
            $machnlog->created_at = date('Y-m-d H:i:s');
            $machnlog->company_id = \Session::get('companyid');
            $machnlog->organization_id = \Session::get('organization');
            $machnlog->location_id = \Session::get('loc_id');
            $machnlog->save();
            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";
            /**Auditlog**/
            $this->auditlog($edit_id, "machinelog", $action, $_POST, "b_machine_log_t");
            return response()->json(array('status' => 'success', 'message' => 'Machine Log Saved Successfully!!', 'id' => $edit_id));
        } else {

            $machine = Machinelog::find($edit_id);
            //dd($machine);
            $machine->update($_POST);
            $action = "Edit";
            /**Auditlog**/
            $this->auditlog($edit_id, "machinelog", $action, $_POST, "b_machine_log_t");
            return response()->json(array('status' => 'success', 'message' => 'Machine Log updated Successfully!!', 'id' => $edit_id));
        }



    }
    /*end*/
    /*Karthigaa Purpose For Edit Function*/
    public function machinelogedit($id = null)
    {

        $row = \DB::select("SELECT * FROM b_machine_log_t where id='$id'");
        $this->data['row']['id'] = $row[0]->id;
        $this->data['row']['process_dept'] = $row[0]->process_dept;
        $this->data['row']['batch_number'] = $row[0]->batch_number;
        $this->data['row']['quantity'] = $row[0]->quantity;
        $this->data['row']['damages'] = $row[0]->damages;
        $this->data['row']['running_hours'] = $row[0]->running_hours;
        $this->data['row']['date'] = $row[0]->date;
        $this->data['row']['remarks'] = $row[0]->remarks;

        $this->data['machine_id'] = $this->jcustomselectcomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', $row[0]->machine_id, " and machine_code like '%jrk/eq%' ");
        $this->data['product_id'] = $this->jcustomselectcomp(' m_products_t', 'product_id', 'concatenated_product', $row[0]->product_id, "and product_group_id IN (1,4)");

        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        return view('machinelog.form', $this->data);

    }


    /*Karthigaa Purpose For View Function*/
    public function machinelogview($id = null)
    {

        $this->data['id'] = $id;

        $this->data['ginvdata'] = $table = DB::table('b_machine_log_t')->select('w_machine_hdr_t.machine_name', 'm_products_t.concatenated_product', 'tb_users.first_name', 'b_machine_log_t.*')
            ->leftJoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'b_machine_log_t.machine_id')
            ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'b_machine_log_t.product_id')
            ->leftJoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'b_machine_log_t.organization_id')
            ->leftJoin('tb_users', 'tb_users.id', '=', 'b_machine_log_t.created_by')
            ->where('b_machine_log_t.id', $id)->get();
        $this->data['machine_id'] = $this->idname('machine_name', 'w_machine_hdr_t', 'machine_hdr_id', $this->data['ginvdata'][0]->machine_id);
        $this->data['product_id'] = $this->idname('concatenated_product', 'm_products_t', 'product_id', $this->data['ginvdata'][0]->product_id);
        $this->data['created_by'] = $this->idname('username', 'tb_users', 'id', $this->data['ginvdata'][0]->created_by);

        return view('machinelog.view', $this->data);

    }

    public function machinelogindex(Request $request)
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

        $this->data['pageMethod'] = 'machinelog';
        return view('machinelog.machinelogdetailsrpt', $this->data);

    }


    public function getmachinelogdetails(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;


        $SQL = "SELECT * from (
SELECT
    b_machine_log_t.id,
    tb_users.first_name,
    m_products_t.concatenated_product,
    b_machine_log_t.batch_number,
    w_machine_hdr_t.machine_name,
    b_machine_log_t.remarks,
    b_machine_log_t.process_dept,
    b_machine_log_t.date,
    b_machine_log_t.quantity,
    b_machine_log_t.damages,
    b_machine_log_t.running_hours
FROM
    b_machine_log_t
LEFT JOIN w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id = b_machine_log_t.machine_id
LEFT JOIN m_products_t ON m_products_t.product_id = b_machine_log_t.product_id
LEFT JOIN tb_users ON tb_users.id = b_machine_log_t.created_by
WHERE
    1 = 1 AND b_machine_log_t.date BETWEEN ? AND ?) AS v1";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return response()->json(['data' => $results]);
    }


    public function machinelogindex1(Request $request)
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


        $this->data['pageMethod'] = 'machinelog';
        return view('machinelog.machineconsolidatedreport', $this->data);

    }

    public function getmachineconsolidatedreport(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;


        $SQL = "SELECT * from (SELECT
    b_machine_log_t.id AS id_no,
    tb_users.first_name AS first_name,
    m_products_t.concatenated_product AS concatenated_product,
    b_machine_log_t.batch_number AS batch_number,
    w_machine_hdr_t.machine_name AS machine_name,
    b_machine_log_t.remarks AS remarks,
    b_machine_log_t.process_dept AS dept,
    '' AS department_name,
    b_machine_log_t.date AS date,
    CONCAT(DATE_FORMAT(b_machine_log_t.date, '%b'), '-', DATE_FORMAT(b_machine_log_t.date, '%y')) AS month,
    b_machine_log_t.quantity AS quantity,
    b_machine_log_t.damages AS damages,
    ROUND(b_machine_log_t.running_hours, 2) AS running_hours,
    8 - ROUND(b_machine_log_t.running_hours, 2) AS idle_hrs,
    '' AS brkdwn_srv_time,
    '' AS causes,
    '' AS corrective_action,
    '' AS preventive_action,
    '' AS approve_remarks,
    '' AS error_code,
    '' AS start_date,
    '' AS end_date,
    '' AS request_request_on,
    '' AS closed_engineer_on,
    '' AS dur_in_days,
    '' AS files,
    '' AS breakdown_name,
    '' AS severity_name
FROM
    b_machine_log_t
LEFT JOIN w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id = b_machine_log_t.machine_id
LEFT JOIN m_products_t ON m_products_t.product_id = b_machine_log_t.product_id
LEFT JOIN tb_users ON tb_users.id = b_machine_log_t.created_by
WHERE
    b_machine_log_t.date BETWEEN ? AND ?

UNION ALL

SELECT
    b_maintenance_t.ticket_number AS id_no,
    hr_employee_t.first_name AS first_name,
    '' AS concatenated_product,
    '' AS batch_number,
    w_machine_hdr_t.machine_name AS machine_name,
    b_maintenance_t.request_remark AS remarks,
    'BREAKDOWN' AS dept,
    m_department_lines_t.sub_department_name AS department_name,
    b_maintenance_t.issue_date AS DATE,
    CONCAT(DATE_FORMAT(b_maintenance_t.issue_date, '%b'), '-', DATE_FORMAT(b_maintenance_t.issue_date, '%y')) AS month,
    '1' AS quantity,
    '' AS damages,
    '' AS running_hours,
    IF(
        DATEDIFF(b_maintenance_t.request_request_on, DATE(b_maintenance_t.issue_date)) != '',
        (DATEDIFF(b_maintenance_t.request_request_on, DATE(b_maintenance_t.issue_date)) + 1) * 8,
        ''
    ) AS idle_hrs,
    TIME_FORMAT(TIMEDIFF(b_maintenance_t.end_date, b_maintenance_t.start_date), '%H:%i') AS brkdwn_srv_time,
    b_maintenance_t.causes,
    b_maintenance_t.corrective_action,
    b_maintenance_t.preventive_action,
    b_maintenance_t.approve_remarks,
    b_maintenance_t.error_code,
    b_maintenance_t.start_date,
    b_maintenance_t.end_date,
    b_maintenance_t.request_request_on,
    b_maintenance_t.closed_engineer_on,
    DATEDIFF(b_maintenance_t.request_request_on, DATE(b_maintenance_t.issue_date)) AS dur_in_days,
    b_maintenance_t.files,
    m_breakdowntype_t.breakdown_name,
    breakdown_severity.severity_name
FROM
    b_maintenance_t
LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = b_maintenance_t.department_id
LEFT JOIN m_breakdowntype_t ON m_breakdowntype_t.breakdowntype_id = b_maintenance_t.break_type_id
LEFT JOIN breakdown_severity ON breakdown_severity.breakdownseverity_id = b_maintenance_t.breakdown_sevearity
LEFT JOIN w_machine_hdr_t ON b_maintenance_t.machine_id = w_machine_hdr_t.machine_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = b_maintenance_t.issue_created_by
WHERE
    b_maintenance_t.issue_date BETWEEN ? AND ? ORDER BY DATE DESC
) AS v1";

        $results = \DB::select($SQL, [$start_date, $end_date, $start_date, $end_date]);

        return response()->json(['data' => $results]);
    }


}
