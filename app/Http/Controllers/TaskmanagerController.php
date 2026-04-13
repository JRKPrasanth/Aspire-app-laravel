<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Taskmanager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use DB, File, Session;
use Config;

class TaskmanagerController extends Controller
{

    public function __construct()
    {
        $this->model = new Taskmanager();
        $this->data = array();
        //$this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlname'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'Taskmanager';
        $this->table = "a_taskmanager_t";
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

        $this->model = new Taskmanager();
        $this->data = array();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlname'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'Taskmanager';
        $this->table = "a_taskmanager_t";
        //dd($this->data['pageMethod']=\Request::route()->getName());
        return view("taskmanager.table", $this->data);

    }

    // table data
    public function gettaskmanData(Request $request)
    {
        $pageMethod = \Request::route()->getName();
        $logged_user = Session::get('emp_id');
        $groupname = Session::get('group_id');

        $employee = DB::table('hr_employee_t')->where('employee_id', $logged_user)->first();

        $emp_depart = json_decode($employee->department, true);
        $emp_depart_array = is_array($emp_depart) ? $emp_depart : [$emp_depart];
        $emp_depart_str = implode(',', $emp_depart_array);
        //dd($emp_depart);
        $design_access_departments = [49, 50, 91, 97, 42];

        $query = DB::table('a_taskmanager_t')
            ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'a_taskmanager_t.assigned_to')
            ->leftJoin('hr_employee_t as hod', 'hod.employee_id', '=', 'a_taskmanager_t.department_lead')
            ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'a_taskmanager_t.department')
            ->leftJoin('tb_users', 'tb_users.id', '=', 'a_taskmanager_t.created_by')
            ->select([
                'a_taskmanager_t.ticket_number',
                DB::raw("CASE WHEN a_taskmanager_t.status = 'APPROVED' THEN 'CLOSED' ELSE UPPER(a_taskmanager_t.status) END as status"),
                'a_taskmanager_t.taskmanager_id',
                'a_taskmanager_t.end_date',
                'a_taskmanager_t.start_date',
                'a_taskmanager_t.task',
                'a_taskmanager_t.created_by',
                DB::raw("CASE WHEN a_taskmanager_t.assigned_to > 0 THEN hr_employee_t.first_name ELSE hod.first_name END as first_name"),
                'm_department_lines_t.sub_department_name',
                'tb_users.first_name',
            ]);

        $status = $request->get('status');
        //dd($status);
        // Access Control and Filtering
        if (in_array(49, $emp_depart_array) || in_array(50, $emp_depart_array) || in_array(91, $emp_depart_array) || in_array(97, $emp_depart_array) || in_array(42, $emp_depart_array)) {
            if ($status == "approval") {
                $query->where('a_taskmanager_t.status', 'INITIATED')
                    ->whereIn('a_taskmanager_t.department', $design_access_departments);
            } elseif ($status == "finalapproval") {
                $query->where('a_taskmanager_t.status', 'Completed')
                    ->whereIn('a_taskmanager_t.department', $design_access_departments);
            } elseif ($status == "update") {
                if(in_array($groupname, ['1', '15','4'])){
                $query->whereIn('a_taskmanager_t.status', ['ALLOCATED', 'Pending', 'Completed'])
                    ->where('a_taskmanager_t.assigned_to', $logged_user);
                }else{
                $query->whereIn('a_taskmanager_t.status', ['ALLOCATED', 'Pending', 'Completed']);
                }
            } elseif (!in_array($groupname, ['1', '15','4'])) {
                $query->where(function ($q) use ($design_access_departments, $logged_user) {
                    $q->whereIn('a_taskmanager_t.department', $design_access_departments)
                        ->orWhere('a_taskmanager_t.created_by', $logged_user);
                });
            }
        } else {
            if ($status == "approval") {
                if (!in_array($groupname, ['1', '15'])) {
                    $query->where('a_taskmanager_t.status', 'INITIATED')
                        ->whereIn('a_taskmanager_t.department', $emp_depart_array);
                }
            } elseif ($status == "finalapproval") {
                if (!in_array($groupname, ['1', '15'])) {
                    $query->where('a_taskmanager_t.status', 'Completed')
                        ->whereIn('a_taskmanager_t.department', $emp_depart_array);
                }
            } elseif ($status == "update") {
                if (!in_array($groupname, ['1', '15','4'])) {
                    $query->whereIn('a_taskmanager_t.status', ['ALLOCATED', 'Pending', 'Completed'])
                        ->where('a_taskmanager_t.assigned_to', $logged_user);
                }
            } elseif (!in_array($groupname, ['1', '15'])) {
                $query->where(function ($q) use ($emp_depart_array, $logged_user) {
                    $q->whereIn('a_taskmanager_t.department', $emp_depart_array)
                        ->orWhere('a_taskmanager_t.created_by', $logged_user);
                });
            }
        }

        return DataTables::of($query)
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create($id = null)
    {
        $this->data['pageMethod'] = "taskmanager";
        $taskmanager = Taskmanager::find($id);
        $sql = \Session::get('id');

        if ($id == '0') {
            $this->data['row'] = (object) array();
            $this->data['row']->taskmanager_id = "";
            $this->data['row']->ticket_number = "";
            $this->data['row']->department = $this->jcustomselect('m_department_lines_t', 'department_line_id', 'sub_department_name', '', ' and parent_class_id=0');
            $this->data['row']->assigned_to = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', '', ' and active="Yes" and group_type!="14" and employee_id NOT IN(1,265,156,151,38,394,21,169,141,160,157,218,152,159,541,155,206)');
            $this->data['row']->start_date = date('Y-m-d');
            $this->data['row']->end_date = date('Y-m-d');
            $this->data['row']->department_lead = $this->jcomboreportingjoinselect('m', 'employee_id', 'employee_number|first_name', 'e', 'employee_id', 'reporting_manager', '', '', '');
            $this->data['row']->task = "";
            $this->data['row']->status = "";
            $this->data['row']->admin = $this->jCombologin('hr_employee_t', 'employee_id', 'first_name', '');
            $this->data['row']->description = "";
            $this->data['row']->created_by = $this->jCombologin('tb_users', 'id', 'first_name', $sql);
            $this->data['row']->team_type = "";
            $this->data['row']->assigned_type = "";
            $this->data['row']->priority = "";
            $this->data['row']->ticket_category = $this->jCombologin('a_task_category_t', 'task_category_id', 'category_name', '');
            $this->data['row']->ticket_subcategory = $this->jCombologin('a_task_subcategory_t', 'task_subcategory_id', 'subcategory_name', '');
        } else {
            $sql = \DB::table('a_taskmanager_t')->where('taskmanager_id', $id)->get();
            //dd($sql);
            $this->data['productdata'] = $taskmanager;
            $this->data['row'] = $sql[0];
            $this->data['row']->taskmanager_id = $id;
            $this->data['row']->department = $this->jcustomselect('m_department_lines_t', 'department_line_id', 'sub_department_name', $sql[0]->department, ' and parent_class_id=0');
            //$this->data['row']->assigned_to=$this->jCombologin('hr_employee_t','employee_id','first_name',$sql[0]->assigned_to);
            $this->data['row']->assigned_to = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $sql[0]->assigned_to, ' and active="Yes" and group_type!="14" and employee_id NOT IN(1,265,156,151,38,394,21,169,141,160,157,218,152,159,541,155,206)');
            $this->data['row']->admin = $this->jCombologin('hr_employee_t', 'employee_id', 'first_name', $sql[0]->admin);
            //$this->data['row']->department_lead=$this->jCombologin('hr_employee_t','employee_id','first_name',$sql[0]->department_lead);
            $this->data['row']->department_lead = $this->jcomboreportingjoinselect('m', 'employee_id', 'employee_number|first_name', 'e', 'employee_id', 'reporting_manager', '', $sql[0]->department_lead, '');
            $this->data['row']->created_by = $this->jCombologin('tb_users', 'id', 'first_name', $sql[0]->created_by);
            $this->data['row']->ticket_number = $sql[0]->ticket_number;
            $start_date = date('d-m-Y', strtotime($sql[0]->start_date));
            $end_date = date('d-m-Y', strtotime($sql[0]->end_date));
            $this->data['row']->start_date = $start_date;
            $this->data['row']->end_date = $end_date;
            $this->data['row']->team_type = $sql[0]->team_type;
            $this->data['row']->assigned_type = $sql[0]->assigned_type;
            $this->data['row']->priority = $sql[0]->priority;
            $this->data['row']->choosefile = $sql[0]->choosefile;
            $this->data['row']->ticket_category = $this->jCombologin('a_task_category_t', 'task_category_id', 'category_name', $sql[0]->ticket_category);
            $this->data['row']->ticket_subcategory = $this->jCombologin('a_task_subcategory_t', 'task_subcategory_id', 'subcategory_name', $sql[0]->ticket_subcategory);
            //dd($this->data['row']);
        }

        if (isset($_GET['status'])) {
            if ($_GET['status'] == "approve")
                $this->data['pageMethod'] = "taskmanagerapproval";
            else if ($_GET['status'] == "update")
                $this->data['pageMethod'] = "taskmanagerupdate";
            else
                $this->data['pageMethod'] = "taskmanagerfinalapproval";
        }

        return view('taskmanager.form', $this->data);
    }

    public function store(Request $request)
    {

    }

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
        $data['description'] = trim($_POST['description']);
        $data['start_date'] = date("Y-m-d", strtotime($request->input('start_date')));
        $data['end_date'] = date("Y-m-d", strtotime($request->input('end_date')));
        if ($request->input('taskmanager_id') == '') {
            $seqno = $this->Seqnoe("TICKET", 'a_taskmanager_t', '', 'ticket_count');
            $data['ticket_number'] = $seqno[0];
            $data['ticket_count'] = $seqno[1];
        }
        //dd($request->file('choosefile'));
        \DB::beginTransaction();
        try {


            $id = $this->model->insertRow($data);
            //  dd($id);
            if ($request->input('taskmanager_id') == '') {

                $dataupload = [];
                if ($request->hasfile('choosefile')) {
                    foreach ($request->file('choosefile') as $file) {
                        $name = $file->getClientOriginalName();
                        $date = date('Y-m-d H:i:s');
                        //$name = $name . '&' . $date;


                        $file->move(public_path() . '/uploads/task_manager/' . $id . '/', $name);
                        $dataupload[] = $name;
                    }
                }
                $attachfile_name = json_encode($dataupload);
                \DB::update("update a_taskmanager_t set choosefile='" . $attachfile_name . "' where taskmanager_id='$id'");
                $this->data['notymsg'] = "yes";
            } else {
                // dd("FDgfdg");
                $existing_file = $request->input('existing_file');
                $choose_file = $request->file('choosefile');
                $existing_file = explode(",", $existing_file);
                //dd(count($existing_file));
                if (count((array) $choose_file) == 0 && count($existing_file) > 0) {
                    //dd('test2');
                    if (count($existing_file) == 1 && $existing_file[0] == '') {

                        \DB::update("update a_taskmanager_t set choosefile='' where taskmanager_id='$id'");
                    } else {
                        $get_attach = DB::table('a_taskmanager_t')->where('taskmanager_id', $id)->get();
                        $attach_file = json_decode($get_attach[0]->choosefile);
                        //dd($attach_file);
                        $attach_file1 = array();

                        foreach ($attach_file as $k => $v) {
                            $attach_file1[] = $v;
                        }
                        $array_diff = array_diff($attach_file1, $existing_file);

                        if (count($array_diff) > 0) {
                            foreach ($array_diff as $k => $v) {
                                // unlink(public_path().'/uploads/product_image/'.$id.'/'.$v);
                            }
                            $attachfile_name = json_encode($existing_file);

                            \DB::update("update a_taskmanager_t set choosefile='" . $attachfile_name . "' where taskmanager_id='$id'");
                        }
                    }
                } else if (count($choose_file) > 0 && count($existing_file) > 0) {
                    //dd('test3');
                    foreach ($request->file('choosefile') as $file) {
                        $name = $file->getClientOriginalName();

                        $date = date('Y-m-d H:i:s');
                        //$name = $name . '&' . $date;


                        $file->move(public_path() . '/uploads/task_manager/' . $id . '/', $name);
                        $dataupload[] = $name;
                    }

                    $get_attach = DB::table('a_taskmanager_t')->where('taskmanager_id', $id)->get();
                    $attach_file = json_decode($get_attach[0]->choosefile);
                    $attach_file1 = array();

                    foreach ($attach_file as $k => $v) {
                        $attach_file1[] = $v;
                    }

                    $array_diff = array_diff($attach_file1, $existing_file);

                    if (count($array_diff) > 0) {
                        foreach ($attach_file as $k => $v) {
                            // unlink(public_path().'/uploads/product_image/'.$id.'/'.$v);
                        }
                        $attachfile_name = array_merge($existing_file, $dataupload);
                        $attachfile_name = json_encode($attachfile_name);
                    } else {
                        $attachfile_name = array_merge($attach_file1, $dataupload);
                        $attachfile_name = json_encode($attachfile_name);
                    }
                    \DB::update("update a_taskmanager_t set choosefile='" . $attachfile_name . "' where taskmanager_id='$id'");

                } else if (count($choose_file) > 0 && count($existing_file) == 0) {
                    //dd('test4');
                    dd($request->file('choosefile'));

                    foreach ($request->file('choosefile') as $file) {
                        $name = $file->getClientOriginalName();


                        $date = date('Y-m-d H:i:s');
                        //$name = $name . '&' . $date;

                        $file->move(public_path() . '/uploads/task_manager/' . $id . '/', $name);
                        $dataupload[] = $name;
                    }
                    $attachfile_name = json_encode($dataupload);
                    \DB::update("update a_taskmanager_t set choosefile='" . $attachfile_name . "' where taskmanager_id='$id'");
                }
            }


            \DB::commit();

            /*Purpose For Notifications*/
            if ($_POST['status'] == "INITIATED") {

                //dd($id);
                $main = DB::select("SELECT a_taskmanager_t.department,a_taskmanager_t.created_by,a_taskmanager_t.last_updated_by, a_taskmanager_t.ticket_number, a_taskmanager_t.assigned_to, a_taskmanager_t.start_date, a_taskmanager_t.end_date, a_taskmanager_t.department_lead, a_taskmanager_t.task, a_taskmanager_t.priority, CASE WHEN a_taskmanager_t.status = 'APPROVED' THEN 'CLOSED' ELSE upper(a_taskmanager_t.status) end as status, a_task_category_t.category_name, a_task_subcategory_t.subcategory_name FROM a_taskmanager_t LEFT JOIN a_task_category_t ON a_taskmanager_t.ticket_category = a_task_category_t.task_category_id LEFT JOIN a_task_subcategory_t ON a_taskmanager_t.ticket_subcategory = a_task_subcategory_t.task_subcategory_id WHERE a_taskmanager_t.taskmanager_id= '$id'");
                $user_clear = \DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS full_name", "email"))->where('employee_id', '=', $main[0]->created_by)->get();
                if ($main[0]->assigned_to != '') {
                    $assigned = $main[0]->assigned_to;
                    $assign_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email, reporting_manager FROM hr_employee_t WHERE employee_id = '$assigned'");
                    $assign_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email FROM tb_users WHERE employee_id = '$assigned'");
                    $head = $assign_mail[0]->reporting_manager;
                    $assign_head_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM hr_employee_t WHERE employee_id = '$head'");
                    $assign_head_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM tb_users WHERE employee_id = '$head'");
                } else {
                    $lead = $main[0]->department_lead;
                    $lead_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email,reporting_manager FROM hr_employee_t WHERE employee_id = '$lead'");
                    $lead_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email FROM tb_users WHERE employee_id = '$lead'");
                    $lead_head = $lead_mail[0]->reporting_manager;
                    $lead_head_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM hr_employee_t WHERE employee_id = '$lead_head'");
                    $lead_head_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM tb_users WHERE employee_id = '$lead_head'");
                }
                // dd($user_clear);
                //   $_POST['mail']=$getmail[0]->email;
                $task_man['ticket_number'] = $main[0]->ticket_number;
                $task_man['start_date'] = $main[0]->start_date;
                $task_man['end_date'] = $main[0]->end_date;
                $task_man['user_clear'] = $user_clear[0]->full_name;
                $task_man['task'] = $main[0]->task;
                $task_man['priority'] = $main[0]->priority;
                $task_man['status'] = $main[0]->status;
                $task_man['task_category'] = $main[0]->category_name;
                $task_man['task_subcategory'] = $main[0]->subcategory_name;
                if ($main[0]->assigned_to != '') {
                    $to_mail = $assign_usr_mail[0]->email;
                    $cc_mail = $assign_head_usr_mail[0]->email;
                } else {
                    $to_mail = $lead_usr_mail[0]->email;
                    $cc_mail = $lead_head_usr_mail[0]->email;
                }

                $dept_cc = $main[0]->department;

                $tk_num_sub = $main[0]->ticket_number;
                Session::put('tk_num_sub', $tk_num_sub);
                Session::put('to_email', $to_mail);
                Session::put('cc_email', $cc_mail);
                Session::put('dept_cc', $dept_cc);
                //dd($task_man);
                //dd($_POST['cc']);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));

                }
                if (\Session::get('user_email') != '') {
                    \Mail::send('taskmanager.mail', $task_man, function ($message) {

                        if (!empty(Session::get('cc_email'))) {
                            $cc_mail = Session::get('cc_email');
                        } else {
                            $cc_mail = \Session::get('user_email');
                        }
                        $de_cc = Session::get('dept_cc');
                        $message->cc($cc_mail);
                        $message->cc("aspire@jrkresearch.com");
                        if ($de_cc == "49" || $de_cc == "91") {
                            $message->cc("content@jrkresearch.com");
                        }
                        $message->cc("gayathri_rajagopal@jrkresearch.com");
                        if (!empty(Session::get('to_email'))) {
                            $to_email = Session::get('to_email');
                        } else {
                            $to_email = \Session::get('user_email');
                        }

                        $message->to($to_email);
                        $message->from(\Session::get('user_email'));
                        if (!empty(Session::get('tk_num_sub'))) {
                            $tk_num_sub = Session::get('tk_num_sub');
                        } else {
                            $tk_num_sub = " ";
                        }

                        $message->subject($tk_num_sub . " - WebOps INITIATED");
                    });
                }



                $da = \DB::select("select * from a_taskmanager_t where taskmanager_id='" . $id . "'");
                $notifcation = 'TASK INITIATED';
                $send_notification = $this->sendPopUpHomeNoty($id, "TASK INITIATED", $notifcation, 'taskmanager', $da[0]->assigned_to);
            } else if ($_POST['status'] == "REJECTED") {

                $main = DB::select("SELECT a_taskmanager_t.department,a_taskmanager_t.created_by,a_taskmanager_t.last_updated_by, a_taskmanager_t.ticket_number, a_taskmanager_t.assigned_to, a_taskmanager_t.start_date, a_taskmanager_t.end_date, a_taskmanager_t.department_lead, a_taskmanager_t.task, a_taskmanager_t.priority, CASE WHEN a_taskmanager_t.status = 'APPROVED' THEN 'CLOSED' ELSE upper(a_taskmanager_t.status) end as status, a_task_category_t.category_name, a_task_subcategory_t.subcategory_name FROM a_taskmanager_t LEFT JOIN a_task_category_t ON a_taskmanager_t.ticket_category = a_task_category_t.task_category_id LEFT JOIN a_task_subcategory_t ON a_taskmanager_t.ticket_subcategory = a_task_subcategory_t.task_subcategory_id WHERE a_taskmanager_t.taskmanager_id= '$id'");
                $user_clear = \DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS full_name", "email"))->where('employee_id', '=', $main[0]->last_updated_by)->get();
                if ($main[0]->assigned_to != '') {
                    $assigned = $main[0]->created_by;
                    $assign_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email,reporting_manager FROM hr_employee_t WHERE employee_id = '$assigned'");
                    $assign_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email FROM tb_users WHERE employee_id = '$assigned'");
                    $head = $assign_mail[0]->reporting_manager;
                    $assign_head_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM hr_employee_t WHERE employee_id = '$head'");
                    $assign_head_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM tb_users WHERE employee_id = '$head'");
                } else {
                    $lead = $main[0]->created_by;
                    $lead_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email,reporting_manager FROM hr_employee_t WHERE employee_id = '$lead'");
                    $lead_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email FROM tb_users WHERE employee_id = '$lead'");
                    $lead_head = $lead_mail[0]->reporting_manager;
                    $lead_head_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM hr_employee_t WHERE employee_id = '$lead_head'");
                    $lead_head_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM tb_users WHERE employee_id = '$lead_head'");
                }
                // dd($user_clear);
                //   $_POST['mail']=$getmail[0]->email;
                $task_man['ticket_number'] = $main[0]->ticket_number;
                $task_man['start_date'] = $main[0]->start_date;
                $task_man['end_date'] = $main[0]->end_date;
                $task_man['user_clear'] = $user_clear[0]->full_name;
                $task_man['task'] = $main[0]->task;
                $task_man['priority'] = $main[0]->priority;
                $task_man['status'] = $main[0]->status;
                $task_man['task_category'] = $main[0]->category_name;
                $task_man['task_subcategory'] = $main[0]->subcategory_name;
                if ($main[0]->assigned_to != '') {
                    $to_mail = $assign_usr_mail[0]->email;
                    $cc_mail = $assign_head_usr_mail[0]->email;
                } else {
                    $to_mail = $lead_usr_mail[0]->email;
                    $cc_mail = $lead_head_usr_mail[0]->email;
                }

                $dept_cc = $main[0]->department;
                $tk_num_sub = $main[0]->ticket_number;
                Session::put('tk_num_sub', $tk_num_sub);
                Session::put('to_email', $to_mail);
                Session::put('cc_email', $cc_mail);
                Session::put('dept_cc', $dept_cc);
                //dd($task_man);
                //dd($_POST['cc']);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));

                }
                if (\Session::get('user_email') != '') {
                    \Mail::send('taskmanager.mail', $task_man, function ($message) {

                        if (!empty(Session::get('cc_email'))) {
                            $cc_mail = Session::get('cc_email');
                        } else {
                            $cc_mail = \Session::get('user_email');
                        }
                        $de_cc = Session::get('dept_cc');
                        $message->cc($cc_mail);
                        $message->cc("aspire@jrkresearch.com");
                        if ($de_cc == "49" || $de_cc == "91") {
                            $message->cc("content@jrkresearch.com");
                        }

                        if (!empty(Session::get('to_email'))) {
                            $to_email = Session::get('to_email');
                        } else {
                            $to_email = \Session::get('user_email');
                        }

                        $message->to($to_email);
                        $message->from(\Session::get('user_email'));
                        if (!empty(Session::get('tk_num_sub'))) {
                            $tk_num_sub = Session::get('tk_num_sub');
                        } else {
                            $tk_num_sub = " ";
                        }

                        $message->subject($tk_num_sub . " - WebOps REJECTED");
                    });
                }


                $da = \DB::select("select * from a_taskmanager_t where taskmanager_id='" . $id . "'");
                $notifcation = 'TASK REJECTED';
                $send_notification = $this->sendPopUpHomeNoty($id, "TASK REJECTED", $notifcation, 'taskmanager', $da[0]->assigned_to);
            } else if ($_POST['status'] == "Completed") {


                $main = DB::select("SELECT a_taskmanager_t.department,a_taskmanager_t.created_by,a_taskmanager_t.last_updated_by, a_taskmanager_t.ticket_number, a_taskmanager_t.assigned_to, a_taskmanager_t.start_date, a_taskmanager_t.end_date, a_taskmanager_t.department_lead, a_taskmanager_t.task, a_taskmanager_t.priority, CASE WHEN a_taskmanager_t.status = 'APPROVED' THEN 'CLOSED' ELSE upper(a_taskmanager_t.status) end as status, a_task_category_t.category_name, a_task_subcategory_t.subcategory_name FROM a_taskmanager_t LEFT JOIN a_task_category_t ON a_taskmanager_t.ticket_category = a_task_category_t.task_category_id LEFT JOIN a_task_subcategory_t ON a_taskmanager_t.ticket_subcategory = a_task_subcategory_t.task_subcategory_id WHERE a_taskmanager_t.taskmanager_id= '$id'");
                $user_clear = \DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS full_name", "email"))->where('employee_id', '=', $main[0]->last_updated_by)->get();
                if ($main[0]->assigned_to != '') {
                    $assigned = $main[0]->created_by;
                    $assign_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email,reporting_manager FROM hr_employee_t WHERE employee_id = '$assigned'");
                    $assign_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email FROM tb_users WHERE employee_id = '$assigned'");
                    $head = $assign_mail[0]->reporting_manager;
                    $assign_head_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM hr_employee_t WHERE employee_id = '$head'");
                    $assign_head_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM tb_users WHERE employee_id = '$head'");

                } else {
                    $lead = $main[0]->created_by;
                    $lead_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email,reporting_manager FROM hr_employee_t WHERE employee_id = '$lead'");
                    $lead_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email FROM tb_users WHERE employee_id = '$lead'");
                    $lead_head = $lead_mail[0]->reporting_manager;
                    $lead_head_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM hr_employee_t WHERE employee_id = '$lead_head'");
                    $lead_head_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM tb_users WHERE employee_id = '$lead_head'");

                }
                // dd($user_clear);
                //   $_POST['mail']=$getmail[0]->email;
                $task_man['ticket_number'] = $main[0]->ticket_number;
                $task_man['start_date'] = $main[0]->start_date;
                $task_man['end_date'] = $main[0]->end_date;
                $task_man['user_clear'] = $user_clear[0]->full_name;
                $task_man['task'] = $main[0]->task;
                $task_man['priority'] = $main[0]->priority;
                $task_man['status'] = $main[0]->status;
                $task_man['task_category'] = $main[0]->category_name;
                $task_man['task_subcategory'] = $main[0]->subcategory_name;
                if ($main[0]->assigned_to != '') {
                    $to_mail = $assign_usr_mail[0]->email;
                    $cc_mail = $assign_head_usr_mail[0]->email;
                } else {
                    $to_mail = $lead_usr_mail[0]->email;
                    $cc_mail = $lead_head_usr_mail[0]->email;
                }

                $dept_cc = $main[0]->department;
                $tk_num_sub = $main[0]->ticket_number;
                Session::put('tk_num_sub', $tk_num_sub);
                Session::put('to_email', $to_mail);
                Session::put('cc_email', $cc_mail);
                Session::put('dept_cc', $dept_cc);
                //dd($task_man);
                //dd($_POST['cc']);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));

                }
                if (\Session::get('user_email') != '') {
                    \Mail::send('taskmanager.mail', $task_man, function ($message) {

                        if (!empty(Session::get('cc_email'))) {
                            $cc_mail = Session::get('cc_email');
                        } else {
                            $cc_mail = \Session::get('user_email');
                        }
                        $de_cc = Session::get('dept_cc');
                        $message->cc($cc_mail);
                        $message->cc("aspire@jrkresearch.com");
                        if ($de_cc == "49" || $de_cc == "91") {
                            $message->cc("content@jrkresearch.com");
                        }

                        if (!empty(Session::get('to_email'))) {
                            $to_email = Session::get('to_email');
                        } else {
                            $to_email = \Session::get('user_email');
                        }

                        $message->to($to_email);
                        $message->from(\Session::get('user_email'));
                        if (!empty(Session::get('tk_num_sub'))) {
                            $tk_num_sub = Session::get('tk_num_sub');
                        } else {
                            $tk_num_sub = " ";
                        }

                        $message->subject($tk_num_sub . " - WebOps COMPLETED");
                    });
                }


                $da = \DB::select("select * from a_taskmanager_t where taskmanager_id='" . $id . "'");
                $notifcation = 'TASK Completed';
                $send_notification = $this->sendPopUpHomeNoty($id, "TASK Completed", $notifcation, 'taskmanager', $da[0]->assigned_to);
            } else if ($_POST['status'] == "APPROVED") {

                $main = DB::select("SELECT a_taskmanager_t.department,a_taskmanager_t.created_by,a_taskmanager_t.last_updated_by, a_taskmanager_t.ticket_number, a_taskmanager_t.assigned_to, a_taskmanager_t.start_date, a_taskmanager_t.end_date, a_taskmanager_t.department_lead, a_taskmanager_t.task, a_taskmanager_t.priority, CASE WHEN a_taskmanager_t.status = 'APPROVED' THEN 'CLOSED' ELSE upper(a_taskmanager_t.status) end as status, a_task_category_t.category_name, a_task_subcategory_t.subcategory_name FROM a_taskmanager_t LEFT JOIN a_task_category_t ON a_taskmanager_t.ticket_category = a_task_category_t.task_category_id LEFT JOIN a_task_subcategory_t ON a_taskmanager_t.ticket_subcategory = a_task_subcategory_t.task_subcategory_id WHERE a_taskmanager_t.taskmanager_id= '$id'");
                $user_clear = \DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS full_name", "email"))->where('employee_id', '=', $main[0]->last_updated_by)->get();
                if ($main[0]->assigned_to != '') {
                    $assigned = $main[0]->created_by;
                    $assign_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email,reporting_manager FROM hr_employee_t WHERE employee_id = '$assigned'");
                    $assign_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email FROM tb_users WHERE employee_id = '$assigned'");
                    $head = $assign_mail[0]->reporting_manager;
                    $assign_head_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM hr_employee_t WHERE employee_id = '$head'");
                    $assign_head_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM tb_users WHERE employee_id = '$head'");

                } else {
                    $lead = $main[0]->created_by;
                    $lead_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email,reporting_manager FROM hr_employee_t WHERE employee_id = '$lead'");
                    $lead_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email FROM tb_users WHERE employee_id = '$lead'");
                    $lead_head = $lead_mail[0]->reporting_manager;
                    $lead_head_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM hr_employee_t WHERE employee_id = '$lead_head'");
                    $lead_head_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM tb_users WHERE employee_id = '$lead_head'");

                }
                // dd($user_clear);
                //   $_POST['mail']=$getmail[0]->email;
                $task_man['ticket_number'] = $main[0]->ticket_number;
                $task_man['start_date'] = $main[0]->start_date;
                $task_man['end_date'] = $main[0]->end_date;
                $task_man['user_clear'] = $user_clear[0]->full_name;
                $task_man['task'] = $main[0]->task;
                $task_man['priority'] = $main[0]->priority;
                $task_man['status'] = $main[0]->status;
                $task_man['task_category'] = $main[0]->category_name;
                $task_man['task_subcategory'] = $main[0]->subcategory_name;
                if ($main[0]->assigned_to != '') {
                    $to_mail = $assign_usr_mail[0]->email;
                    $cc_mail = $assign_head_usr_mail[0]->email;
                } else {
                    $to_mail = $lead_usr_mail[0]->email;
                    $cc_mail = $lead_head_usr_mail[0]->email;
                }

                $dept_cc = $main[0]->department;

                $tk_num_sub = $main[0]->ticket_number;
                Session::put('tk_num_sub', $tk_num_sub);
                Session::put('to_email', $to_mail);
                Session::put('cc_email', $cc_mail);
                Session::put('dept_cc', $dept_cc);
                //dd($task_man);
                //dd($_POST['cc']);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));

                }
                if (\Session::get('user_email') != '') {
                    \Mail::send('taskmanager.mail', $task_man, function ($message) {
                        if (!empty(Session::get('cc_email'))) {
                            $cc_mail = Session::get('cc_email');
                        } else {
                            $cc_mail = \Session::get('user_email');
                        }
                        $de_cc = Session::get('dept_cc');
                        $message->cc($cc_mail);
                        $message->cc("aspire@jrkresearch.com");
                        if ($de_cc == "49" || $de_cc == "91") {
                            $message->cc("content@jrkresearch.com");
                        }
                        $message->cc("gayathri_rajagopal@jrkresearch.com");
                        if (!empty(Session::get('to_email'))) {
                            $to_email = Session::get('to_email');
                        } else {
                            $to_email = \Session::get('user_email');
                        }

                        $message->to($to_email);
                        $message->from(\Session::get('user_email'));
                        if (!empty(Session::get('tk_num_sub'))) {
                            $tk_num_sub = Session::get('tk_num_sub');
                        } else {
                            $tk_num_sub = " ";
                        }

                        $message->subject($tk_num_sub . " - WebOps CLOSED");
                    });
                }

                $da = \DB::select("select * from a_taskmanager_t where taskmanager_id='" . $id . "'");
                \DB::table('notifications_t')->where('reference_source_id', $_POST['taskmanager_id'])->where('reference_source', 'Task APPROVAL')->update(['read/unread' => 'read']);
                $notifcation = 'TASK APPROVED';
                $send_notification = $this->sendPopUpHomeNoty($id, "TASK APPROVED", $notifcation, 'taskmanager', $da[0]->assigned_to);
            } else if ($_POST['status'] == "ALLOCATED") {

                $main = DB::select("SELECT a_taskmanager_t.department,a_taskmanager_t.created_by,a_taskmanager_t.last_updated_by, a_taskmanager_t.ticket_number, a_taskmanager_t.assigned_to, a_taskmanager_t.start_date, a_taskmanager_t.end_date, a_taskmanager_t.department_lead, a_taskmanager_t.task, a_taskmanager_t.priority, CASE WHEN a_taskmanager_t.status = 'APPROVED' THEN 'CLOSED' ELSE upper(a_taskmanager_t.status) end as status, a_task_category_t.category_name, a_task_subcategory_t.subcategory_name FROM a_taskmanager_t LEFT JOIN a_task_category_t ON a_taskmanager_t.ticket_category = a_task_category_t.task_category_id LEFT JOIN a_task_subcategory_t ON a_taskmanager_t.ticket_subcategory = a_task_subcategory_t.task_subcategory_id WHERE a_taskmanager_t.taskmanager_id= '$id'");
                $user_clear = \DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS full_name", "email"))->where('employee_id', '=', $main[0]->last_updated_by)->get();
                if ($main[0]->assigned_to != '') {
                    $assigned = $main[0]->assigned_to;
                    $assign_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email,reporting_manager FROM hr_employee_t WHERE employee_id = '$assigned'");
                    $assign_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email FROM tb_users WHERE employee_id = '$assigned'");
                    $head = $assign_mail[0]->reporting_manager;
                    $assign_head_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM hr_employee_t WHERE employee_id = '$head'");
                    $assign_head_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM tb_users WHERE employee_id = '$head'");

                } else {
                    $lead = $main[0]->department_lead;
                    $lead_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email,reporting_manager FROM hr_employee_t WHERE employee_id = '$lead'");
                    $lead_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS full_name, email FROM tb_users WHERE employee_id = '$lead'");
                    $lead_head = $lead_mail[0]->reporting_manager;
                    $lead_head_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM hr_employee_t WHERE employee_id = '$lead_head'");
                    $lead_head_usr_mail = DB::select("SELECT CONCAT(employee_number, '-', first_name) AS report_name, email FROM tb_users WHERE employee_id = '$lead_head'");

                }
                // dd($user_clear);
                //   $_POST['mail']=$getmail[0]->email;
                $task_man['ticket_number'] = $main[0]->ticket_number;
                $task_man['start_date'] = $main[0]->start_date;
                $task_man['end_date'] = $main[0]->end_date;
                $task_man['user_clear'] = $user_clear[0]->full_name;
                $task_man['task'] = $main[0]->task;
                $task_man['priority'] = $main[0]->priority;
                $task_man['status'] = $main[0]->status;
                $task_man['task_category'] = $main[0]->category_name;
                $task_man['task_subcategory'] = $main[0]->subcategory_name;
                if ($main[0]->assigned_to != '') {
                    $to_mail = $assign_usr_mail[0]->email;
                    $cc_mail = $assign_head_usr_mail[0]->email;
                } else {
                    $to_mail = $lead_usr_mail[0]->email;
                    $cc_mail = $lead_head_usr_mail[0]->email;
                }

                $dept_cc = $main[0]->department;

                $tk_num_sub = $main[0]->ticket_number;
                Session::put('tk_num_sub', $tk_num_sub);
                Session::put('to_email', $to_mail);
                Session::put('cc_email', $cc_mail);
                Session::put('dept_cc', $dept_cc);
                //dd($task_man);
                //dd($_POST['cc']);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));

                }
                if (\Session::get('user_email') != '') {
                    \Mail::send('taskmanager.mail', $task_man, function ($message) {
                        if (!empty(Session::get('cc_email'))) {
                            $cc_mail = Session::get('cc_email');
                        } else {
                            $cc_mail = \Session::get('user_email');
                        }
                        $de_cc = Session::get('dept_cc');
                        $message->cc($cc_mail);
                        $message->cc("aspire@jrkresearch.com");
                        if ($de_cc == "49" || $de_cc == "91") {
                            $message->cc("content@jrkresearch.com");
                        }
                        $message->cc("gayathri_rajagopal@jrkresearch.com");
                        if (!empty(Session::get('to_email'))) {
                            $to_email = Session::get('to_email');
                        } else {
                            $to_email = \Session::get('user_email');
                        }

                        $message->to($to_email);
                        $message->from(\Session::get('user_email'));
                        if (!empty(Session::get('tk_num_sub'))) {
                            $tk_num_sub = Session::get('tk_num_sub');
                        } else {
                            $tk_num_sub = " ";
                        }

                        $message->subject($tk_num_sub . " - WebOps ALLOCATED");
                    });
                }



                $da = \DB::select("select * from a_taskmanager_t where taskmanager_id='" . $id . "'");
                \DB::table('notifications_t')->where('reference_source_id', $_POST['taskmanager_id'])->where('reference_source', 'Task ALLOCATE')->update(['read/unread' => 'read']);
                $notifcation = 'TASK ALLOCATED';
                $send_notification = $this->sendPopUpHomeNoty($id, "TASK ALLOCATED", $notifcation, 'taskmanager', $da[0]->assigned_to);
            }
            /*End Purpose For Notifications*/

            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            dd($dbCode);
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    public function show($id = null)
    {
        $sql = \DB::table('a_taskmanager_t')->where('taskmanager_id', $id)->get();
        if ($sql->isNotEmpty()) {
            $this->data['taskmanager'] = $sql[0];
            if ($sql[0]->department) {
                $this->data['department'] = $this->idname('sub_department_name', 'm_department_lines_t', 'department_line_id', $sql[0]->department);
            } else {
                $this->data['department'] = '';
            }

            if ($sql[0]->assigned_to) {
                $this->data['assigned_to'] = $this->idname('first_name', 'hr_employee_t', 'employee_id', $sql[0]->assigned_to);
            } else {
                $this->data['assigned_to'] = '';
            }

            if ($sql[0]->department_lead) {
                $this->data['department_lead'] = $this->idname('first_name', 'hr_employee_t', 'employee_id', $sql[0]->department_lead);
            } else {
                $this->data['department_lead'] = '';
            }

            if ($sql[0]->created_by) {
                $this->data['created_by'] = $this->idname('first_name', 'tb_users', 'id', $sql[0]->created_by);
            } else {
                $this->data['created_by'] = '';
            }
            if ($sql[0]->admin) {
                $this->data['admin'] = $this->idname('first_name', 'hr_employee_t', 'employee_id', $sql[0]->admin);
            } else {
                $this->data['admin'] = '';
            }
        }

        return view('taskmanager.view', $this->data);
    }


    public function edit(Taskmanager $taskmanager)
    {

    }


    public function update(Request $request, Taskmanager $taskmanager)
    {

    }

    public function destroy($id = null)
    {
        $count = 0;
        if ($count <= 0) {
            $query = \DB::table('a_taskmanager_t')->where('taskmanager_id', $id)->delete();
            if ($query) {
                return 0;
            } else {
                return 1;
            }

        } else {
            return 2;
        }
    }


    // table data   


    public function leaddepartment()
    {
        $result = DB::table('hr_employee_t')->where('employee_id', $_GET['department_lead'])->get();
        //dd($result);
        $emp_depart = \Session::get('dept_id');
        $emp_depart = $result[0]->department;
        $emp_type = $result[0]->employee_type;

        $reporting = DB::table('hr_employee_t')->where('employee_id', $_GET['department_lead'])->get();
        $report = $reporting[0]->employee_id;
        //dd($reporting[0]->department);
        $values = json_decode($reporting[0]->department, true);
        // Check for successful decoding
        if (is_array($values)) {
            // Access individual elements
            $values[0];

            // Loop through the values
            /*foreach ($values as $value) {
                echo $value . "\n"; // Outputs each value
            }*/
        } else {
            echo "Invalid JSON format.";
        }

        $reporting = $this->jcustomselect('m_department_lines_t', 'department_line_id', 'sub_department_name', $values[0], ' and parent_class_id=0');


        //$data['week_off']=$week_off;
        $data['department'] = $reporting;

        return json_encode($data);
    }

    public function dashboardindex(Request $request)
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


        $this->data = array();
        //dd(\Session::all());
        $sid = \Session::get('id');
        $eid = \Session::get('emp_id');
        $groupname = \Session::get('groupname');
        $emp_data = DB::table("hr_employee_t")->where('employee_id', $eid)->get();
        $department = $emp_data[0]->department;
        //dd($department);
        $emp_depart_array = json_decode($department, true); // Convert to PHP array

        if (is_array($emp_depart_array)) {
            $emp_depart = implode(',', $emp_depart_array); // Convert to comma-separated string
        }
        $design_access_departments = "49, 50, 91, 97";
        $wh = '';
        $dept = json_decode($emp_data[0]->department);
        /* check user's department is r&d/design */
        if (in_array(49, $emp_depart_array) || in_array(50, $emp_depart_array) || in_array(91, $emp_depart_array) || in_array(97, $emp_depart_array)) {
            $wh .= ' and a_taskmanager_t.department IN (' . $design_access_departments . ')';
        } else {
            $wh .= ' and a_taskmanager_t.department IN (' . $emp_depart . ')';
        }

        if ($groupname == '1' || $groupname == "15") {
            $this->data['totalTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t");

            $this->data['actTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'INITIATED'");

            $this->data['inactTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'ALLOCATED'");

            $this->data['maleTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'Pending'");

            $this->data['femaleTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'Completed'");

            $this->data['officeTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'APPROVED'");

            $this->data['factoryTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'REJECTED'");

            $this->data['marketingTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE priority = 'High' and status = 'INITIATED'");

            $this->data['task_detail'] = \DB::select("SELECT a_taskmanager_t.ticket_number, a_taskmanager_t.start_date, a_taskmanager_t.end_date, a_taskmanager_t.team_type, a_taskmanager_t.assigned_type, (case WHEN a_taskmanager_t.status = 'APPROVED' THEN 'CLOSED' ELSE a_taskmanager_t.status END) as status, a_taskmanager_t.task, a_taskmanager_t.priority, a_taskmanager_t.description, a_task_category_t.category_name, a_task_subcategory_t.subcategory_name, m_department_lines_t.sub_department_name, a_to.first_name as assigned_to, d_lead.first_name as dept_lead, c_by.first_name as created_by FROM a_taskmanager_t LEFT JOIN a_task_category_t ON a_task_category_t.task_category_id = a_taskmanager_t.ticket_category LEFT JOIN a_task_subcategory_t ON a_task_subcategory_t.task_subcategory_id = a_taskmanager_t.ticket_subcategory LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = a_taskmanager_t.department LEFT JOIN hr_employee_t as a_to ON a_to.employee_id = a_taskmanager_t.assigned_to LEFT JOIN hr_employee_t as d_lead ON d_lead.employee_id = a_taskmanager_t.department_lead LEFT JOIN hr_employee_t as c_by ON c_by.employee_id = a_taskmanager_t.created_by WHERE 1=1 ORDER BY a_taskmanager_t.taskmanager_id DESC");

        } else {
            $this->data['totalTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE 1=1 $wh");

            $this->data['actTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'INITIATED' $wh");

            $this->data['inactTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'ALLOCATED' $wh");

            $this->data['maleTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'Pending' $wh");

            $this->data['femaleTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'Completed' $wh");

            $this->data['officeTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'APPROVED' $wh");

            $this->data['factoryTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE status = 'REJECTED' $wh");

            $this->data['marketingTask_count'] = \DB::select("SELECT COUNT(taskmanager_id) as count FROM a_taskmanager_t WHERE priority = 'High' and status = 'INITIATED' $wh");

            $this->data['task_detail'] = \DB::select("SELECT a_taskmanager_t.ticket_number, a_taskmanager_t.start_date, a_taskmanager_t.end_date, a_taskmanager_t.team_type, a_taskmanager_t.assigned_type, (case WHEN a_taskmanager_t.status = 'APPROVED' THEN 'CLOSED' ELSE a_taskmanager_t.status END) as status, a_taskmanager_t.task, a_taskmanager_t.priority, a_taskmanager_t.description, a_task_category_t.category_name, a_task_subcategory_t.subcategory_name, m_department_lines_t.sub_department_name, a_to.first_name as assigned_to, d_lead.first_name as dept_lead, c_by.first_name as created_by FROM a_taskmanager_t LEFT JOIN a_task_category_t ON a_task_category_t.task_category_id = a_taskmanager_t.ticket_category LEFT JOIN a_task_subcategory_t ON a_task_subcategory_t.task_subcategory_id = a_taskmanager_t.ticket_subcategory LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = a_taskmanager_t.department LEFT JOIN hr_employee_t as a_to ON a_to.employee_id = a_taskmanager_t.assigned_to LEFT JOIN hr_employee_t as d_lead ON d_lead.employee_id = a_taskmanager_t.department_lead LEFT JOIN hr_employee_t as c_by ON c_by.employee_id = a_taskmanager_t.created_by WHERE 1=1 $wh ORDER BY a_taskmanager_t.taskmanager_id DESC");

        }



        return view('taskmanager.taskmanager_dashboard', $this->data);
    }

}
