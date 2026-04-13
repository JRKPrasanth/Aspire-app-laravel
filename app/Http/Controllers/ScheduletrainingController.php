<?php
namespace App\Http\Controllers;
use App\Scheduletraininghdr;
use App\Scheduletraininglines;
use App\Trainingfeedback;
use App\Employeecreate;
use App\topic;
use Illuminate\Http\Request;
use Validator, DB, session;
use Config;
use Datetime;
use Yajra\DataTables\DataTables;
class ScheduletrainingController extends Controller
{

    public $module = "Scheduletraininghdr";

    public function __construct()
    {
        $this->data = array();

        $this->table = "t_schedule_training_hdr_tbl";
        $this->subtable = "t_schedule_training_lines_tbl";
        $this->pageModule = "Scheduletraininghdr";
        $this->model = new Scheduletraininghdr;
        $this->submodel = new Scheduletraininglines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
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

        $this->data['urlname'] = \Request::route()->getName();
        $this->data['pageMethod'] = \Request::route()->getName();
        if ($this->data['pageMethod'] == "scheduletrainingfromrequest") {
            return view('Scheduletraining.reqtable', $this->data);
        } else {
            return view('Scheduletraining.table', $this->data);
        }
    }

    /* JQgrid start */
    public function getscheduletraininggriddata(Request $request)
    {

        if ($request->ajax()) {

            $data = \DB::table('t_schedule_training_hdr_tbl')
                ->leftJoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_schedule_training_hdr_tbl.topic_id')
                ->select([
                    't_schedule_training_hdr_tbl.schedule_training_hdr_id',
                    't_schedule_training_hdr_tbl.schedule_date',
                    't_schedule_training_hdr_tbl.start_time',
                    't_schedule_training_hdr_tbl.end_time',
                    't_schedule_training_hdr_tbl.schedule_type',
                    't_schedule_training_hdr_tbl.schedule_status',
                    't_schedule_training_hdr_tbl.remarks',
                    't_schedule_training_hdr_tbl.description',
                    't_schedule_training_hdr_tbl.trainer_type',
                    't_topic_tbl.topic_name',
                    \DB::raw("(CASE 
                    WHEN t_schedule_training_hdr_tbl.trainer_type = 'Internal' 
                    THEN (SELECT hr_employee_t.first_name 
                          FROM hr_employee_t 
                          WHERE hr_employee_t.employee_id = t_schedule_training_hdr_tbl.trainer_name)
                    ELSE t_schedule_training_hdr_tbl.exttrainer_name 
                END) AS trainer_name")
                ]);

            return DataTables::of($data)
                ->make(true);
        }


    }

    /* End */
    /**from request grid**/
    public function getrequesttraininggriddata(Request $request)
    {

        if ($request->ajax()) {

            $companyId = session('company_id'); // 

            $data = \DB::table('t_training_request_tbl')
                ->select([
                    't_training_request_tbl.training_request_id',
                    't_training_request_tbl.remarks',
                    't_training_request_tbl.request_type',
                    \DB::raw("(SELECT topic_name FROM t_topic_tbl WHERE t_topic_tbl.topic_id = t_training_request_tbl.topic_id) as topic_name"),
                    \DB::raw("(SELECT first_name FROM hr_employee_t WHERE hr_employee_t.employee_id = t_training_request_tbl.employee_id) as employee_name")
                ])

                ->where('t_training_request_tbl.company_id', $companyId)
                ->where('t_training_request_tbl.schedule_raised', 0)
                ->where('t_training_request_tbl.active', 'Yes')
                ->where('t_training_request_tbl.approve_status', 1);


            return DataTables::of($data)
                ->make(true);


        }
    }
    /**end**/
    /* Create function */
    public function create($id = null)
    {
        $urlName = \Request::route()->getName();
        $this->data['pageMethod'] = "scheduletraining";
        $compy = \Session::get('companyid');
        if ($id == '0') {
            $this->data['row'] = (object) array();
            if (isset($_GET['reqid'])) {
                $this->data['row']->schedule_training_hdr_id = "";
                $req_id = $_GET['reqid'];
                $table = \DB::table('t_training_request_tbl')->where('training_request_id', $_GET['reqid'])->get();
                $this->data['row']->schedule_type = "Request";
                $this->data['topic_id'] = $this->jCombo('t_topic_tbl', 'topic_id', 'topic_name', $table[0]->topic_id);
                $this->data['row']->reference_id = $_GET['reqid'];

                $departments = DB::table('t_department_topic_tbl')->select('department_id')->where('topic_id', $table[0]->topic_id)->get();
                $condition = ' 1=1 ';

                if (count($departments) > 0) {
                    foreach ($departments as $val) {
                        $dept[] = $val->department_id;
                    }

                    $depts = implode("','", $dept);
                    $cond = $this->getjsoncondition('hr_employee_t.department', $dept);
                    $condition .= " and $cond";
                    $departments_cond = " and m_department_lines_t.department_line_id in ('$depts')";
                } else {
                    $depts = "('')";
                    $condition .= " and employee_id in ('" . $depts . "')";
                    $departments_cond = '';
                }

                $departments = $this->jcustomselect('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', '', $departments_cond);
                $emp_ids = json_decode($table[0]->employee_id);

                $this->data['linedata'] = array();

                $empids = implode("','", $emp_ids);
                // $this->data['linedata'] = DB::select("SELECT '' as schedule_training_line_id,`employee_number` as emp_number,hr_employee_t.`first_name` as employee_name,hr_employee_t.`employee_id`,case when hr_employee_t.employee_id in ('$empids') then 1 else 0 end as checked from hr_employee_t where $condition and company_id='$compy' and active='Yes'");
                $this->data['linedata'] = DB::select("SELECT '' as schedule_training_line_id,`employee_number` as emp_number,hr_employee_t.`first_name` as employee_name,hr_employee_t.`employee_id` from hr_employee_t where hr_employee_t.employee_id in ('$empids') and company_id='$compy' and active='Yes'");

            } else {
                $this->data['row']->schedule_type = "Manual";
                $this->data['topic_id'] = $this->jCombo('t_topic_tbl', 'topic_id', 'topic_name', '');
                $this->data['row']->reference_id = "";
                $this->data['linedata'] = array();
                $this->data['departments'] = $this->jcustomselect('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', '', '');
                $this->data['employee_id'] = DB::select("SELECT `employee_number` as emp_number,hr_employee_t.`first_name` as employee_name,hr_employee_t.`employee_id` from hr_employee_t where company_id='$compy' and active='Yes'");
                $this->data['zone'] = $this->jcustomselect('a_zone_master_t', 'zone_id', 'zone_name', '', '');
            }

            $this->data['row']->schedule_training_hdr_id = "";
            $this->data['row']->schedule_date = date("Y-m-d");
            $this->data['row']->start_time = "";
            $this->data['row']->end_time = "";
            $this->data['row']->remarks = "";
            $this->data['row']->description = "";
            $this->data['row']->active = "";
            $this->data['row']->need_exam = "";
            $this->data['row']->trainer_type = "";
            $this->data['pagemode'] = 'create';

        } else {

            $this->data['id'] = $id;
            $this->data['pagemode'] = 'edit';
            $table = \DB::table('t_schedule_training_hdr_tbl')->where('schedule_training_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['topic_id'] = $this->jCombo('t_topic_tbl', 'topic_id', 'topic_name', $table[0]->topic_id);
            $linestable = \DB::table('t_schedule_training_lines_tbl')->where('schedule_training_hdr_id', $id)->get();
            $this->data['linedata'] = $linestable;
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->employee_id = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $value->employee_id);
            }


        }

        return view('Scheduletraining.form', $this->data);

    }
    public function getdepartmentsbytopic($id)
    {
        $departments = DB::table('t_department_topic_tbl')->select('department_id')->where('topic_id', $id)->get();
        // dd($departments);
        $departments_cond = ' and 1=1 ';
        $html = '';
        $compy = \Session::get('companyid');
        if (count($departments) > 0) {
            foreach ($departments as $val) {
                $dept[] = $val->department_id;
            }

            $depts = implode("','", $dept);
            $cond = $this->getjsoncondition('hr_employee_t.department', $dept);
            $departments_cond .= " and m_department_lines_t.department_line_id in ('$depts')";
        } else {
            $depts = "('')";
            $departments_cond .= " and department_id in ('" . $depts . "')";
        }

        $departments = $this->jcustomselect('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', '', $departments_cond);

        return $departments;

    }

    public function getemployeelistbydept($id)
    {

        $html = '';
        $condition = '1=1';
        $compy = \Session::get('companyid');

        if ($id > 0) {

            $dept = explode(',', $id);   // if coming as "12,28"
            $jsonConditions = [];

            foreach ($dept as $d) {
                $jsonConditions[] = "JSON_CONTAINS(hr_employee_t.department, '\"$d\"')";
            }

            $condition .= " AND (" . implode(' OR ', $jsonConditions) . ")";

        } else {

            $condition .= " AND 1=0";   // no department selected
        }

        // dd($condition);

        $employee_data = DB::select("SELECT `employee_number` as emp_number,hr_employee_t.`first_name` as employee_name,hr_employee_t.`employee_id` from hr_employee_t where $condition and company_id='$compy' and active='Yes'");

        foreach ($employee_data as $key => $value) {

            $html .= '<tr class="rcopy clone">

    <td>
        <input type="hidden" name="bulk_schedule_training_line_id[]" 
            class="form-control input-sm bulk_schedule_training_line_id" value="">
        <input type="text" name="bulk_line_no[]" 
            class="form-control input-sm bulk_line_no" 
            value="' . ($key + 1) . '" readonly>
    </td>

    <td>
        <input type="text" name="bulk_employee_number[]" 
            class="form-control input-sm bulk_employee_number" 
            value="' . $value->emp_number . '" readonly>
    </td>

    <td>
        <input type="hidden" name="bulk_employee_id[]" 
            class="form-control input-sm bulk_employee_id" 
            value="' . $value->employee_id . '">
        <input type="text" name="bulk_employee_name[]" 
            class="form-control input-sm bulk_employee_name" 
            value="' . $value->employee_name . '" readonly>
    </td>

    <td class="text-center">
        <input type="checkbox" 
            name="bulk_check[]" 
            class="bulk_check" 
            value="' . $value->employee_id . '">
        <input type="hidden" name="counter[]">
    </td>

</tr>';
        }


        return $html;
    }


    public function getemployeelistbyzone($id)
    {

        $html = '';
        $condition = '1=1';
        $compy = \Session::get('companyid');

        if ($id > 0) {

            $dept = explode(',', $id);   // if coming as "12,28"
            $jsonConditions = [];

            foreach ($dept as $d) {
                $jsonConditions[] = "JSON_CONTAINS(hr_employee_t.zone_id, \"$d\")";
            }

            $condition .= " AND (" . implode(' OR ', $jsonConditions) . ")";

        } else {

            $condition .= " AND 1=0";   // no department selected
        }

        // dd($condition);

        $employee_data = DB::select("SELECT `employee_number` as emp_number,hr_employee_t.`first_name` as employee_name,hr_employee_t.`employee_id` from hr_employee_t where $condition and company_id='$compy' and active='Yes'");

        foreach ($employee_data as $key => $value) {

            $html .= '<tr class="rcopy clone">

    <td>
        <input type="hidden" name="bulk_schedule_training_line_id[]" 
            class="form-control input-sm bulk_schedule_training_line_id" value="">
        <input type="text" name="bulk_line_no[]" 
            class="form-control input-sm bulk_line_no" 
            value="' . ($key + 1) . '" readonly>
    </td>

    <td>
        <input type="text" name="bulk_employee_number[]" 
            class="form-control input-sm bulk_employee_number" 
            value="' . $value->emp_number . '" readonly>
    </td>

    <td>
        <input type="hidden" name="bulk_employee_id[]" 
            class="form-control input-sm bulk_employee_id" 
            value="' . $value->employee_id . '">
        <input type="text" name="bulk_employee_name[]" 
            class="form-control input-sm bulk_employee_name" 
            value="' . $value->employee_name . '" readonly>
    </td>

    <td class="text-center">
        <input type="checkbox" 
            name="bulk_check[]" 
            class="bulk_check" 
            value="' . $value->employee_id . '">
        <input type="hidden" name="counter[]">
    </td>

</tr>';
        }


        return $html;
    }

    /* Save function */
    public function scheduletrainingsave(Request $request)
    {

        \DB::beginTransaction();

        try {

            /* ================= HEADER SAVE ================= */

            $form = $request->except([
                '_token',
                'form_config',
                'form_data_json',
                'save_status',
                'submit_type',
                'zone',
                'choosefile',
                'existing_file',
                'department_id',
                'enable-masterdetail',
            ]);

            $form = $this->normalizeLineFormKeys($form);
            $data = $this->validatePost($form, $this->table, 'header');
            $data['trainer_name'] = implode(',', $request->trainer_name);
            if ($request->trainer_name1 != "") {
                $data['trainer_name'] = $request->trainer_name1;
            }

            $id = $this->model->insertRow($data);
            $selectedEmployees = [];

            /* ================= LINE SAVE ================= */

            if (!empty($request->bulk_employee_id)) {

                foreach ($request->bulk_employee_id as $key => $empId) {

                    if (!empty($request->bulk_check) && in_array($empId, $request->bulk_check)) {

                        $lines_data = [
                            'schedule_training_hdr_id' => $id,
                            'employee_id' => $empId,
                            'organization_id' => \Session::get("organization"),
                            'company_id' => \Session::get("companyid"),
                            'location_id' => \Session::get("location"),
                            'created_by' => \Session::get("id"),
                            'last_updated_by' => \Session::get("id"),
                        ];

                        DB::table('t_schedule_training_lines_tbl')
                            ->insert($lines_data);

                        $selectedEmployees[] = $empId;
                    }
                }
            }

            /* ================= UPDATE REQUEST FLAG ================= */

            if ($data['schedule_type'] == "Request") {
                DB::table('t_training_request_tbl')
                    ->where('training_request_id', $data['reference_id'])
                    ->update(['schedule_raised' => '1']);
            }


            \DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Saved Successfully',
                'schedule_id' => $id
            ]);

        } catch (\Exception $e) {

            \DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

    }


    public function sendTrainingMail(Request $request)
    {


        try {

            $scheduleId = $request->schedule_id;

            if (!$scheduleId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Schedule ID missing'
                ], 400);
            }

            $details = Scheduletraininglines::where('schedule_training_hdr_id', $scheduleId)->get();
            $header = Scheduletraininghdr::find($scheduleId);

            date_default_timezone_set('Asia/Kolkata');

            $start = date('Ymd\THis', strtotime($header->start_time));
            $end = date('Ymd\THis', strtotime($header->end_time));

            $sentEmails = []; // prevent duplicate sending

            // ==========================
            // SEND TO EMPLOYEES
            // ==========================
            foreach ($details as $detail) {

                $employee = Employeecreate::find($detail->employee_id);

                if ($employee && !empty($employee->email)) {

                    $sentEmails[] = $employee->email;

                    $this->sendCalendarMail($employee->email, $employee->first_name, $header, $start, $end);
                }
            }

            // SEND TO TRAINER

            if ($header->trainer_type == 'Internal' && !empty($header->trainer_name)) {

                // Convert comma string to array
                $trainerIds = explode(',', $header->trainer_name);
                // dd($trainerIds);
                foreach ($trainerIds as $trainerId) {

                    $trainer = Employeecreate::find(trim($trainerId));

                    if ($trainer && !empty($trainer->email)) {

                        if (!in_array($trainer->email, $sentEmails)) {

                            $sentEmails[] = $trainer->email;
    
                            $this->sendCalendarMail(
                                $trainer->email,
                                $trainer->first_name,
                                $header,
                                $start,
                                $end
                            );
                        }
                    }
                }
            }

            $this->sendCalendarMail(
                'venkatesh_s@jrkresearch.com',
                'Venkatesh',
                $header,
                $start,
                $end
            );

            return response()->json([
                'status' => 'success'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    private function sendCalendarMail($email, $name, $header, $start, $end)
    {
        $topic = topic::find($header->topic_id);

        // Format description properly for Outlook
        $description = $header->description;
        $description = str_replace(["\r\n", "\r", "\n"], "\\n", $description);
        $description = addcslashes($description, ",;");

        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "PRODID:-//JRK Research//Outlook Meeting//EN\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "METHOD:REQUEST\r\n";

        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:" . uniqid() . "@jrkresearch.com\r\n";
        $ics .= "DTSTAMP:" . date('Ymd\THis') . "\r\n";
        $ics .= "DTSTART;TZID=Asia/Kolkata:$start\r\n";
        $ics .= "DTEND;TZID=Asia/Kolkata:$end\r\n";
        $ics .= "SUMMARY:" . $topic->topic_name . "\r\n";
        $ics .= "DESCRIPTION:" . $description . "\r\n";
        $ics .= "LOCATION:" . $header->schedule_type . "\r\n";
        $ics .= "STATUS:CONFIRMED\r\n";
        $ics .= "SEQUENCE:0\r\n";
        $ics .= "TRANSP:OPAQUE\r\n";
        $ics .= "ATTENDEE;CN=$name;ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:$email\r\n";
        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";

        \Mail::send([], [], function ($message) use ($email, $ics, $topic) {

            $message->to($email)

                ->from(\Session::get('user_email'))
                ->subject($topic->topic_name);
            $message->setBody('Training Program', 'text/plain');
            $message->addPart($ics, 'text/calendar; method=REQUEST; charset=UTF-8');
        });
    }


    //Edit data
    public function edit(Request $request, $id = null)
    {
        $this->data['pageModule'] = "freightcarriershdr";
        $this->data['pageUrl'] = url('freightcarriershdr');


        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'freightcarriershdr')->get();
        $this->data['urlName'] = \Request::route()->getName();

        if ($this->data['urlName'] == "purchasefreightcarriershdredit") {
            $this->data['source_type_id'] = "Purchase";
        } else {
            $this->data['source_type_id'] = "Sales";
        }

        $this->data['url_type'] = '';
        $table = \DB::table('m_frieghtcarriers_hdr_t')->where('ar_frieghtcarriers_hdr_id', $id)->get();
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
        $this->data['row'] = $table[0];
        $str_date = $this->data['row']->start_date;
        $end_date = $this->data['row']->end_date;

        if ($str_date != '0000-00-00')
            $this->data['row']->start_date = date("m-d-Y", strtotime($str_date));
        else
            $this->data['row']->start_date = '';

        if ($end_date != '0000-00-00')
            $this->data['row']->end_date = date("m-d-Y", strtotime($end_date));
        else
            $this->data['row']->end_date = '';
        $this->data['row']->remarks = $table[0]->remarks;
        $this->data['row']->description = $table[0]->description;

        $this->data['row']->ar_frieghtcarriers_hdr_id = $id;


        $tablelines = \DB::table('m_frieghtcarriers_lines_t')->where('ar_frieghtcarriers_hdr_id', $id)->get();


        $this->data['default_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $this->data['row']->default_currency);
        $location = \Session::get('location');
        $comp = \Session::get('companyid');
        $company = \DB::table('m_company_line_t')->where('companyid', $comp)->get();

        if (count($company) > 0) {
            $c = '';
            foreach ($company as $k => $y) {
                $c .= $y->locationid . ",";
            }

            $c = rtrim($c, ',');

            $this->data['location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $this->data['row']->location_id, " and location_id in(" . $c . ")");
        } else {
            $this->data['location_id'] = $this->jCombologin('m_location_t', 'location_id', 'location_name', '');
        }

        $this->data['linedata'] = $tablelines;

        $this->data['country'] = $this->data['country'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', $tablelines[0]->country);

        $this->data['state'] = $this->data['state'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', $tablelines[0]->state);
        $this->data['city'] = $this->data['city'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', $tablelines[0]->city);

        foreach ($this->data['linedata'] as $key => $value) {

            $this->data['linedata'][$key]->country = $this->data['country'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', $value->country);

            $this->data['linedata'][$key]->state = $this->data['state'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', $value->state);

            $this->data['linedata'][$key]->city = $this->data['city'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', $value->city);

        }

        if (isset($_GET['status'])) {
            $this->data['used_some'] = "readonly";
        }
        $this->data['pagemode'] = 'edit';

        return view('Scheduletraining.form', $this->data);
    }

    public function getedit($edit_id, $type)
    {
        echo $edit_id;
    }
    // View function
    public function feedback(Trainingfeedback $trainingfeedback, $id = null)
    {
        $this->data['urlname'] = \Request::route()->getName();
        $this->data['index_data'] = "Training";

        $vlinesdata = \DB::table('t_schedule_training_lines_tbl')->select('t_schedule_training_lines_tbl.*', 'hr_employee_t.first_name', 'hr_employee_t.email', 'tb_users.username', 't_schedule_training_hdr_tbl.*', 't_topic_tbl.topic_name')
            ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 't_schedule_training_lines_tbl.employee_id')
            ->leftjoin('tb_users', 'tb_users.id', '=', 't_schedule_training_lines_tbl.created_by')
            ->leftjoin('t_schedule_training_hdr_tbl', 't_schedule_training_hdr_tbl.schedule_training_hdr_id', '=', 't_schedule_training_lines_tbl.schedule_training_hdr_id')
            ->leftjoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_schedule_training_hdr_tbl.topic_id')
            ->where('t_schedule_training_lines_tbl.schedule_training_line_id', $id)->get();

        if (count($vlinesdata) > 0) {

            // dd($vlinesdata);
            $location = $vlinesdata[0]->location_id;
            $locationid = DB::table("m_location_t")->where('location_id', $location)->get();

            $this->data['location_name'] = $locationid[0]->location_name;


            $user = \DB::table('tb_users')->where('id', $vlinesdata[0]->created_by)->get();
            if (count($user) > 0) {
                $this->data['created_by'] = $user[0]->username;
            } else {
                $this->data['created_by'] = '';
            }

            $this->data['vlinesdata'] = $vlinesdata[0];

            $str_date = $this->data['vlinesdata']->start_time;
            $end_date = $this->data['vlinesdata']->end_time;

            $date_time_format = \Session::get('p_date_format') . ' ' . \Session::get('p_time_format');

            $this->data['start_time'] = date($date_time_format, strtotime($str_date));
            $this->data['end_time'] = date($date_time_format, strtotime($end_date));

            $this->data['active'] = 'Yes';
        } else {
            $this->data = '';

        }
        // dd($this->data);
        return view('Scheduletraining.feedback', $this->data);
    }

    public function feedbacksave(Request $request)
    {


        $data = $this->validatePost($request->all(), $this->table, 'header');

        \DB::beginTransaction();

        try {

            $id = $this->model->insertRow($data);
            //$this->auditlog($id,"FREIGHT CARRIERS",$action,$_POST,"m_frieghtcarriers_hdr_t");
            return response()->json(array('status' => 'success', 'message' => "Saved Successfully", 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            dd($dbCode);
            \DB::rollback();
            $action = "Edit";
            //	$this->auditlog($id,"FREIGHT CARRIERS",$action,$_POST,"m_frieghtcarriers_hdr_t");
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }


    }

    public function show(Scheduletraininghdr $Scheduletraininghdr, $id = null)
    {
        $this->data['urlname'] = \Request::route()->getName();
        $this->data['index_data'] = "Training";

$this->data['columns'] = \DB::connection()
    ->getSchemaBuilder()
    ->getColumnListing("t_schedule_training_hdr_tbl");

$hdr_data = \DB::table('t_schedule_training_hdr_tbl')
    ->select(
        't_schedule_training_hdr_tbl.*',
        't_topic_tbl.topic_name',
        \DB::raw("GROUP_CONCAT(hr_employee_t.first_name SEPARATOR ', ') as trainer_names")
    )
    ->leftJoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_schedule_training_hdr_tbl.topic_id')
    ->leftJoin('hr_employee_t', function ($join) {
        $join->whereRaw("FIND_IN_SET(hr_employee_t.employee_id, t_schedule_training_hdr_tbl.trainer_name)");
    })
    ->where('t_schedule_training_hdr_tbl.schedule_training_hdr_id', $id)
    ->groupBy('t_schedule_training_hdr_tbl.schedule_training_hdr_id')
    ->get();
        $this->data['values'] = $hdr_data[0];
        // 		dd($this->data['values']);
        $str_date = $this->data['values']->start_time;
        $end_date = $this->data['values']->end_time;

        $date_time_format = \Session::get('p_date_format') . ' ' . \Session::get('p_time_format');

        $this->data['start_time'] = date($date_time_format, strtotime($str_date));
        $this->data['end_time'] = date($date_time_format, strtotime($end_date));

        // 		$active=$this->data['values']->active;

        $vlinesdata = \DB::table('t_schedule_training_lines_tbl')->select('t_schedule_training_lines_tbl.*', 'hr_employee_t.first_name', 'tb_users.username', 't_schedule_training_lines_tbl.attend_status')
            ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 't_schedule_training_lines_tbl.employee_id')
            ->leftjoin('tb_users', 'tb_users.id', '=', 't_schedule_training_lines_tbl.created_by')
            ->where('t_schedule_training_lines_tbl.schedule_training_hdr_id', $id)->get();

        $location = $this->data['values']->location_id;
        $locationid = DB::table("m_location_t")->where('location_id', $location)->get();

        $this->data['location_name'] = $locationid[0]->location_name;


        $user = \DB::table('tb_users')->where('id', $this->data['values']->created_by)->get();
        if (count($user) > 0) {
            $this->data['created_by'] = $user[0]->first_name;
        } else {
            $this->data['created_by'] = '';
        }

        $this->data['vlinesdata'] = $vlinesdata;

        $this->data['active'] = 'Yes';

        // dd($this->data);
        return view('Scheduletraining.view', $this->data);
    }
    /* Delete  data function*/
    public function delete(Request $request, $id = null, $type = null)
    {
        if ($type == "Sales") {
            $column = array('ar_frieghtcarriers_hdr_id', 'frieghtcarriers_hdr_id', 'freight_carrier_id', 'ar_frieghtcarriers_hdr_id');
            $table = array('m_customers_t', 's_quote_hdr_t', 's_salesorder_hdr_t', 's_invoice_hdr_t');
        } else {
            $column = array('freight_carrier_id', 'freight_carrier_id');
            $table = array('p_quotation_hdr_t', 'p_po_hdr_t');
        }
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }
        if ($j == 0) {
            Arfreightcarriershdr::destroy($id);

            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id, "FREIGHT CARRIERS", $action, $id, "m_frieghtcarriers_hdr_t");
            $query = \DB::table('m_frieghtcarriers_lines_t')->where('ar_frieghtcarriers_hdr_id', $id)->delete();
        }
        return $j;
    }

    public function locationget()
    {
        $location = \Session::get('location');
        $comp = \Session::get('companyid');
        $company = \DB::table('m_company_line_t')->where('companyid', $comp)->get();

        if (count($company) > 0) {
            $c = '';
            foreach ($company as $k => $y) {
                $c .= $y->locationid . ",";
            }

            $c = rtrim($c, ',');

            return $c;
        } else {
            return 0;
        }
    }

    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }
    function findPrimarykeylines($tablelines)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $tablelines . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }
    function validateForm($request = null)
    {
        $form_config = json_decode(urldecode($request['form_data_json']), true);
        $forms = array();
        $forms['header'] = $form_config['header'];
        $forms['lines'] = $form_config['lines'];
        $rules = array();
        foreach ($forms as $form_type => $form_type_val) {
            foreach ($form_type_val as $form_field_name => $form) {
                if ($form['required'] == '') {
                    $rules[$form_type][$form['field']] = 'required';
                } elseif ($form['required'] == 'alpa') {
                    $rules[$form_type][$form['field']] = 'required|alpa';
                } elseif ($form['required'] == 'alpa_num') {
                    $rules[$form_type][$form['field']] = 'required|alpa_num';
                } elseif ($form['required'] == 'alpa_dash') {
                    $rules[$form_type][$form['field']] = 'required|alpa_dash';
                } elseif ($form['required'] == 'email') {
                    $rules[$form_type][$form['field']] = 'required|email';
                } elseif ($form['required'] == 'numeric') {
                    $rules[$form_type][$form['field']] = 'required|numeric';
                } elseif ($form['required'] == 'date') {
                    $rules[$form_type][$form['field']] = 'required|date';
                } else if ($form['required'] == 'url') {
                    $rules[$form_type][$form['field']] = 'required|active_url';
                } else {

                }

            }

        }

        return $rules;
    }

    public function carrierchck(Request $request)
    {

        $ar_frieghtcarriers_hdr_id = $_GET['ar_frieghtcarriers_hdr_id'];
        $source_type_id = $_GET['source_type_id'];

        if ($ar_frieghtcarriers_hdr_id == '') {
            $frcarriersdata = DB::table('m_frieghtcarriers_hdr_t')->where('carrier_name', $_GET['carrier_name'])->where('source_type_id', $_GET['source_type_id'])->get();
        } else {
            $whereData = [['carrier_name', $_GET['carrier_name']], ['ar_frieghtcarriers_hdr_id', '!=', $ar_frieghtcarriers_hdr_id]];
            $frcarriersdata = DB::table('m_frieghtcarriers_hdr_t')->where($whereData)->where('source_type_id', $_GET['source_type_id'])->get();
        }
        if (count($frcarriersdata) > 0)
            return 1;
        else
            return 0;

    }
    public function freightcarnamechk($frightcarhdrid = null)
    {

        $yes = 0;
        if ($frightcarhdrid != "") {
            $tablesCheck = [];
            $tableNew['table_name'] = "p_quotation_hdr_t";
            $tableNew['column_name'] = "freight_carrier_id";
            $tableNew['value'] = $frightcarhdrid;
            $tablesCheck[] = $tableNew;

            $tableNew['table_name'] = "p_po_hdr_t";
            $tableNew['column_name'] = "freight_carrier_id";
            $tableNew['value'] = $frightcarhdrid;
            $tablesCheck[] = $tableNew;

            $tableNew['table_name'] = "m_customers_t";
            $tableNew['column_name'] = "ar_frieghtcarriers_hdr_id";
            $tableNew['value'] = $frightcarhdrid;
            $tablesCheck[] = $tableNew;

            $tableNew['table_name'] = "s_quote_hdr_t";
            $tableNew['column_name'] = "frieghtcarriers_hdr_id";
            $tableNew['value'] = $frightcarhdrid;
            $tablesCheck[] = $tableNew;

            $tableNew['table_name'] = "s_salesorder_hdr_t";
            $tableNew['column_name'] = "freight_carrier_id";
            $tableNew['value'] = $frightcarhdrid;
            $tablesCheck[] = $tableNew;

            $tableNew['table_name'] = "s_invoice_hdr_t";
            $tableNew['column_name'] = "ar_frieghtcarriers_hdr_id";
            $tableNew['value'] = $frightcarhdrid;
            $tablesCheck[] = $tableNew;


            foreach ($tablesCheck as $tableToCheck) {
                if ($yes != 1) {
                    $frieght = \DB::select("select " . $tableToCheck['column_name'] . " from " . $tableToCheck['table_name'] . " where " . $tableToCheck['column_name'] . "=" . $tableToCheck['value']);

                    if (count($frieght) > 0) {
                        $yes = 1;
                    }
                }
            }
        }
        return $yes;
    }

    // popup 
    public function scheduledetails($id = null)
    {
        $schedule_detail = \DB::table('t_schedule_training_lines_tbl')
            ->where('schedule_training_hdr_id', $id)
            ->join('hr_employee_t', 't_schedule_training_lines_tbl.employee_id', '=', 'hr_employee_t.employee_id')
            ->select('schedule_training_line_id', 'hr_employee_t.employee_number as emp_number', 'hr_employee_t.first_name as employee_name', 'hr_employee_t.employee_id', 't_schedule_training_lines_tbl.attend_status')
            ->get();

        $html = "<table class='table table-bordered table-striped table-hover w-100'>";
        $html .= "<thead class='table-primary'><th>Line No</th><th>Employee Number</th><th>Employee Name</th><th>Attended Status</th></thead>";
        $html .= "<tbody>";

        foreach ($schedule_detail as $key => $val) {
            $html .= "<tr>";
            $html .= "<td><input type='text'   name='bulk_line_no[]' class='form-control bulk_line_no' value=" . ($key + 1) . " readonly></td>";

            $html .= "<td  style='display:none;'><input type='hidden' name='schedule_training_line_id[]' class='form-control schedule_training_line_id' value='$val->schedule_training_line_id'></td>";
            $html .= "<td><input type='text'   name='bulk_employee_id[]' class='form-control bulk_employee_id' value='$val->emp_number' readonly></td>";
            $html .= "<td><input type='text'   name='bulk_employee_name[]' class='form-control bulk_employee_name' value='$val->employee_name' readonly></td>";
            $html .= "<td>

                    <div class='form-group'>
                        <select name='bulk_attend_status[]' id='attend_status' style= 'text-align: center' class='form-control select2 bulk_attend_status' value='$val->attend_status' required>
                            <option value=''>-- Please Select --</option>
                            <option value='Yes'>Yes</option>
                            <option value='No'>No</option>
                        </select>
                    </div>
                </td>";
            $html .= "</tr>";

        }

        $html .= "</tbody></table>";
        return $html;
    }

    /*end*/
    public function updatestatus($hdrid = null)
    {
        $schedule_status = $_POST['schedule_status'];

        \DB::table('t_schedule_training_hdr_tbl')->where('schedule_training_hdr_id', $hdrid)->update(['schedule_status' => $schedule_status]);

        $sch_hdr = \DB::Select("select * from t_schedule_training_hdr_tbl where schedule_training_hdr_id =" . $hdrid);

        //dd($sch_hdr);

        $line_id = $_POST['schedule_training_line_id'];
        $attnd_status = $_POST['bulk_attend_status'];


        $lineStatus = array_combine($line_id, $attnd_status);

        //dd($lineStatus);

        foreach ($lineStatus as $line => $status) {
            \DB::table('t_schedule_training_lines_tbl')->where('schedule_training_line_id', $line)->update(['attend_status' => $status]);
        }

        return response()->json(['message' => 'Status updated successfully']);
    }


        public function reportindex(Request $request)
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

        $this->data['urlname'] = \Request::route()->getName();
        $this->data['pageMethod'] = \Request::route()->getName();

        return view('Scheduletraining.report', $this->data);
        
    }



  public function trainingreportdata(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT 
    t_topic_tbl.topic_name,
    hr_employee_t.first_name AS participant,
    a_zone_master_t.zone_name,
    m_area_t.area_name,
    MONTHNAME(t_schedule_training_hdr_tbl.start_time) AS month,
    YEAR(t_schedule_training_hdr_tbl.start_time) AS year,
    t_schedule_training_lines_tbl.attend_status,
    DATE(t_schedule_training_hdr_tbl.start_time) AS sdate,
    DATE(t_schedule_training_hdr_tbl.end_time) AS edate,
    
TIMEDIFF(t_schedule_training_hdr_tbl.end_time,
         t_schedule_training_hdr_tbl.start_time) AS duration,

    t_schedule_training_hdr_tbl.schedule_type,

CASE 
    WHEN t_schedule_training_hdr_tbl.trainer_type = 'Internal' 
    THEN (
        SELECT GROUP_CONCAT(h.first_name)
        FROM hr_employee_t h
        WHERE FIND_IN_SET(h.employee_id,
              t_schedule_training_hdr_tbl.trainer_name)
    )
    ELSE t_schedule_training_hdr_tbl.exttrainer_name
END AS trainer_name

FROM t_schedule_training_hdr_tbl

LEFT JOIN t_topic_tbl 
    ON t_topic_tbl.topic_id = t_schedule_training_hdr_tbl.topic_id

LEFT JOIN t_schedule_training_lines_tbl 
    ON t_schedule_training_lines_tbl.schedule_training_hdr_id = t_schedule_training_hdr_tbl.schedule_training_hdr_id

LEFT JOIN hr_employee_t 
    ON hr_employee_t.employee_id = t_schedule_training_lines_tbl.employee_id

LEFT JOIN a_zone_master_t 
    ON a_zone_master_t.zone_id = hr_employee_t.zone_id

LEFT JOIN m_area_t 
    ON CAST(
        JSON_UNQUOTE(
            JSON_EXTRACT(hr_employee_t.med_rep_area, '$[0]')
        ) AS UNSIGNED
    ) = m_area_t.area_id";

        $results = \DB::select($SQL);

        return DataTables::of($results)->make(true);
    }





}
