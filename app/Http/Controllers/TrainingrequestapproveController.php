<?php
namespace App\Http\Controllers;
use App\topic;
use App\departmentTopic;
use App\Trainingrequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class TrainingrequestapproveController extends Controller
{

    public function __construct()
    {

        $this->data = array(
            'pageModule' => 'TrainingrequestapproveController',
            'pageUrl' => url('trainingrequestapprove')
        );
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageMethod'] = \Request::route()->getName();
    }


    /*Grid Data Load for Subcategory*/
    public function gettrainingrequestapprovegrid(Request $request)
    {
        if ($request->ajax()) {

            $data = \DB::table('t_training_request_tbl')
                ->leftJoin('t_department_topic_tbl', 't_department_topic_tbl.department_topic_id', '=', 't_training_request_tbl.department_topic_id')
                ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 't_training_request_tbl.created_by')
                ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 't_department_topic_tbl.department_id')
                ->leftJoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_department_topic_tbl.topic_id')
                ->leftJoin('tb_users', 'tb_users.id', '=', 't_training_request_tbl.created_by')
                ->select([
                    'tb_users.first_name',
                    't_training_request_tbl.topic_id',
                    't_training_request_tbl.department_id',
                    't_training_request_tbl.remarks',
                    't_training_request_tbl.request_type',
                    't_training_request_tbl.active',
                    't_training_request_tbl.department_topic_id',
                    't_topic_tbl.topic_name',
                    'm_department_lines_t.sub_department_name',
                    't_training_request_tbl.training_request_id',
                    't_training_request_tbl.employee_id',
                    \DB::raw("CONCAT(hr_employee_t.first_name, '-', hr_employee_t.employee_number) as employee_name"),
                ])
                ->where('t_training_request_tbl.active', 'Yes')
                ->where('t_training_request_tbl.approve_status', 0);

            return DataTables::of($data)
                ->rawColumns(['actions'])
                ->make(true);

        }
    }
    /*end*/

    /*Main Page for Topic*/
    public function index(Request $request, $id = null, $type = null)
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
        $this->data['emp_id'] = \Session::get('emp_id');
        $com = \Session::get('companyid');
        $this->data['dept_id'] = \Session::get('dept_id');
        $this->data['pageMethod'] = \Request::route()->getName();

        return view('trainingrequest.approvetable', $this->data);
    }



    public function create($id = null, $type = null)
    {

        if ($id != '') {

            $request_data = \DB::table('t_training_request_tbl')->where('training_request_id', $id)->get();

            $com = \Session::get('companyid');

            $this->data['dept_id'] = \Session::get('dept_id');
            $dept_ids = json_decode(\Session::get('dept_id'));
            $dept1 = implode("','", $dept_ids);

            $this->data['emp_id'] = \Session::get('emp_id');

            $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['country_id'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', '');
            //dd($request_data);
            $this->data['training_request_id'] = $id;
            if (count($request_data) > 0) {
                $this->data['department_topic'] = $request_data[0]->department_topic_id;
                $this->data['request_type'] = $request_data[0]->request_type;
                $employee_id = implode(',', json_decode($request_data[0]->employee_id));
                // dd($employee_id);
                $this->data['employee_id'] = $this->jcustommultiselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $employee_id, '');
                // dd($this->data['employee_id']);
                $this->data['active'] = $request_data[0]->active;
            }


            $this->data['department_topic_id'] = \DB::select("select t_department_topic_tbl.department_topic_id,t_department_topic_tbl.topic_id,t_department_topic_tbl.department_id,t_topic_tbl.topic_name 
                                                from t_department_topic_tbl left join t_topic_tbl on t_topic_tbl.topic_id=t_department_topic_tbl.topic_id 
                                                where t_department_topic_tbl.department_id='" . $dept1 . "'");
            // dd($this->data);
            // $this->data['employee_id']=$this->jcombo('hr_employee_t','employee_id','first_name','');
            $this->data['department_id'] = $this->jcombo('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', '');
            $this->data['pageMethod'] = \Request::route()->getName();
        }
        // 		dd($this->data);
        return view('trainingrequest.approveform', $this->data);
    }
    /*End*/


    /*save Function*/
    public function approve_save($id)
    {
        if (isset($_GET['val'])) {
            if ($_GET['val'] == 'REJECTED') {
                $status = 2;
            } else {
                $status = 1;
            }

        } else {
            $status = 0;
        }

        if (isset($_GET['reject_reason'])) {
            $reject_reason = $_GET['reject_reason'];
        } else {
            $reject_reason = '';
        }
        Trainingrequest::find($id)->update(['approve_status' => $status, 'reject_reason' => $reject_reason]);

        /**Auditlog**/
        $this->auditlog($id, "trqiningrequest", "approve", "", "t_training_request_tbl");

        return response()->json(array('status' => 'success', 'message' => 'Saved Successfully!!'));

    }
    /*end*/

    /*deepika purpose:duplicate name function*/
    public function getCheckname(Request $request)
    {


        return 0;
    }
    /*End*/



}
