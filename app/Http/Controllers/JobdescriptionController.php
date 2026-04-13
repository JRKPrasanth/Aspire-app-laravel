<?php

namespace App\Http\Controllers;

use App\Jobdescription;
use App\description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Input;
use DB;
use Yajra\DataTables\DataTables;

class JobdescriptionController extends Controller
{
    public function __construct()
    {
        $this->data['pageModule'] = \Request::route()->getName();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();
    }
    /**Job Description Approve Page Load Start **/
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

        // dd("hai");
        $this->data['pageMethod'] = \Request::route()->getName();

        return view('jobdescription.table', $this->data);

    }
    /**Job Description  Page Load End **/

    public function indexhr(Request $request)
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

        return view('jobdescription.tablehr', $this->data);
    }

    /**Job Description  Delete Start **/
    public function jobdescriptionfile()
    {

        $del_id = $_GET['del_id'];
        $desc = DB::table('hr_description_t')->where('description_id', '=', $del_id)->get();
        // auditlog
        $this->auditlog($del_id, "Job Description", "Delete", $_GET, "hr_description_t");
        return $desc[0]->file;
    }
    /**Job Description  Delete End **/


    /**Job Description  Form  Start **/
    public function description(Request $request)
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

        return view('jobdescription.formdsc', $this->data);
    }

    public function create(Request $request)
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

        $job_description = Schema::getColumnListing('hr_job_description');
        $this->data['row'] = (object) array();
        foreach ($job_description as $key => $value) {
            $this->data['row']->{$value} = '';
        }
        $this->data['row']->min_salary = '';
        $this->data['row']->max_salary = '';
        $this->data['row']->min_experience = '';
        $this->data['row']->max_experience = '';
        $this->data['row']->no_of_persons = '';
        $this->data['row']->reqired_skills = '';
        $this->data['row']->active = '';
        $this->data['row']->year = '';
        $this->data['row']->month = '';
        $this->data['row']->interview_process_id = '';
        $this->data['row']->description_name = '';
        $this->data['row']->reqired_skills = '';
        $user_id = Session('emp_id');
        $dept_id = Session('dept_id');
        $this->data['row']->employee = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number | first_name', $user_id);
        $this->data['row']->department = $this->jCombo('m_department_lines_t', 'department_line_id', 'sub_department_name', $dept_id);
        $this->data['row']->desc_id = $this->jCombo('hr_description_t', 'description_id', 'description_name', '');
        $this->data['row']->jobtitle = $this->jcustomselect('m_job_title', 'job_title_id', 'job_title_name', '', '');
        $this->data['row']->position_id = $this->jcustomselect('m_position', 'position_id', 'position', '', '');
        $this->data['row']->interview_process = $this->jCombo('m_interview_steps', 'interview_steps_id', 'interview_process', '');

        return view('jobdescription.form', $this->data);
    }


    public function createnew($id)
    {
        $this->data['row'] = $row = DB::table('hr_job_description')->where('description_id', $id)->get();
        $this->data['row']->description_id = $row[0]->description_id;
        $this->data['row']->description_name = $row[0]->description_name;
        $this->data['row']->reqired_skills = $row[0]->reqired_skills;
        $this->data['row']->min_salary = $row[0]->min_salary;
        $this->data['row']->max_salary = $row[0]->max_salary;
        $this->data['row']->min_experience = $row[0]->min_experience;
        $this->data['row']->max_experience = $row[0]->max_experience;
        $this->data['row']->no_of_persons = $row[0]->no_of_persons;
        $this->data['row']->reqired_skills = $row[0]->reqired_skills;
        $this->data['row']->active = $row[0]->active;
        $this->data['row']->year = $row[0]->year;
        $this->data['row']->month = $row[0]->month;

        $this->data['row']->employee = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number | first_name', $row[0]->team_leads_id);
        $this->data['row']->department = $this->jCombo('m_department_lines_t', 'department_line_id', 'sub_department_name', $row[0]->department);
        $this->data['row']->desc_id = $this->jCombo('hr_description_t', 'description_id', 'description_name', $row[0]->desc_id);
        $this->data['row']->jobtitle = $this->jcustomselect('m_job_title', 'job_title_id', 'job_title_name', $row[0]->job_title, '');
        $this->data['row']->position_id = $this->jcustomselect('m_position', 'position_id', 'position', $row[0]->position_id, '');
        //dd($row[0]->interview_process);
        $this->data['row']->interview_process = $this->jCombo('m_interview_steps', 'interview_steps_id', 'interview_process', '');
        $this->data['row']->interview_process_id = $row[0]->interview_process;
        return view('jobdescription.form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $edit_id = $request->input('edit_id');

        if ($edit_id == "") {
            $jobdescription = new Jobdescription();
            $jobdescription->description_name = $request->input('description_name');
            $jobdescription->department = $request->input('department');
            $jobdescription->job_title = $request->input('job_title');
            $jobdescription->position_id = $request->input('position_id');
            $jobdescription->reqired_skills = $request->input('reqired_skills');
            $jobdescription->month = $request->input('month');
            $jobdescription->year = $request->input('year');
            $jobdescription->min_salary = $request->input('min_salary');
            $jobdescription->max_salary = $request->input('max_salary');
            $jobdescription->max_experience = $request->input('max_experience');
            $jobdescription->min_experience = $request->input('min_experience');
            $jobdescription->interview_process = json_encode($request->input('int_pro'));
            $jobdescription->team_leads_id = $request->input('team_leads_id');
            $jobdescription->desc_id = $request->input('desc_id');
            $jobdescription->active = $request->input('active');
            $emp = DB::table('hr_employee_t')->where('employee_id', $request->input('team_leads_id'))->get();
            $jobdescription->reporting_id = $emp[0]->reporting_manager;
            $jobdescription->approve_status = "0";
            $jobdescription->no_of_persons = $request->input('no_of_persons');
            $result = $jobdescription->save();
            $id = $jobdescription->description_id;
            $table = $jobdescription->getTable();
            $column = $jobdescription->getKeyName();
            $this->hrmssaveinsert($table, $column, $id, 1);
            // auditlog
            $this->auditlog($id, "Job Description", "create", $_POST, "hr_description_t");
            return 1;

        } else {
            $Jobdescription = Jobdescription::findOrFail($edit_id);
            $input_data = $request->all();
            $Jobdescription->interview_process = json_encode($request->input('int_pro'));
            $Jobdescription->fill($input_data)->save();
            $table = $Jobdescription->getTable();
            $column = $Jobdescription->getKeyName();
            $this->hrmssaveinsert($table, $column, $edit_id, 2);
            // auditlog
            $this->auditlog($edit_id, "Job Description", "Update", $Jobdescription, "hr_description_t");
            return 2;

        }
    }

    public function storedesc(Request $request)
    {
        $edit_id = $request->input('edit_id');

        if ($edit_id == "") {

            $Jobdescription = new description();
            $Jobdescription->description_name = $request->input('description_name');
            $Jobdescription->active = $request->input('active');
            $input_data = $request->all();
            $image = $request->file('file');

            if ($image != "") {
                $name = uniqid() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/descriptionupload');
                $image->move($destinationPath, $name);
                $input_data['file'] = $name;
            }

            $Jobdescription->fill($input_data)->save();
            $id = $Jobdescription->description_id;
            $table = $Jobdescription->getTable();
            $column = $Jobdescription->getKeyName();
            $this->hrmssaveinsert($table, $column, $id, 1);
            //auditlog
            $this->auditlog($id, "Description", "create", $Jobdescription, "hr_description_t");
            return 1;

        } else {
            $Jobdescription = description::findOrFail($edit_id);

            $file_upload = $request->file('file');

            if ($file_upload != "") {
                $old_file = $Jobdescription->file;

                $name = uniqid() . '.' . $file_upload->getClientOriginalExtension();
                $destinationPath = public_path('/descriptionupload');
                $file_upload->move($destinationPath, $name);
                $Jobdescription->file = $name;
                if ($old_file != '') {
                    $myPublicFolder = public_path();
                    $old_file = public_path() . '/descriptionupload/' . $old_file;
                    unlink($old_file);
                }
            }

            $input_data = $request->all();
            $Jobdescription->fill($input_data)->save();
            $table = $Jobdescription->getTable();
            $column = $Jobdescription->getKeyName();
            $this->hrmssaveinsert($table, $column, $edit_id, 2);
            //auditlog
            $this->auditlog($edit_id, "Description", "Update", $_POST, "hr_description_t");

            return 2;

        }
    }
    /** Description Name Dupliacate Name check Data Start **/
    public function getCheckname(Request $request)
    {

        $edit_id = $_GET['edit_id'];
        $description_name = $_GET['description_name'];

        if ($edit_id == '') {
            $department = DB::table('hr_description_t')->where('description_name', 'like', $_GET['description_name'])->get();
        } else {
            $whereData = [['description_name', 'like', $_GET['description_name']], ['description_id', '!=', $edit_id]];
            $department = DB::table('hr_description_t')->where($whereData)->get();
        }

        if (count($department) > 0)
            return 1;
        else
            return 0;
    }
    /** Description Name Dupliacate Name check Data End **/

    /**JQgrid Job Description Load Data Start **/
    public function jobdescriptiongriddata(Request $request)
    {

        $comp = \Session::get('companyid');
        $emp_id = \Session::get('emp_id');
        $wh = '';
        $approve_status = $_GET['status'];

        $wh .= "and approve_status='$approve_status'";

        if ($approve_status == "0") {
            $wh .= " and hr_job_description.reporting_id='$emp_id'";
        }

        $SQL = "SELECT m_department_lines_t.sub_department_name,
    m_job_title.job_title_name,
    hr_job_description.reqired_skills,
    hr_job_description.description_name,
    hr_job_description.description_id,
    hr_job_description.active,
    hr_job_description.desc_id
    from hr_job_description 
    LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_job_description.department
    LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_job_description.job_title where 1=1  and hr_job_description.company_id='$comp' $wh ";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }



    public function remove(Request $request, $id = null)
    {


        $column = array('job_description_name');
        $table = array('hr_schedule_interview');

        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = DB::table($table[$i])->where($column[$i], $id)->get();
            // auditlog
            $this->auditlog($id, "Job Description", "Delete", $_GET, "hr_description_t");
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        if ($j == 0) {
            $query = DB::table('hr_job_description')->where('description_id', $id)->delete();
            // auditlog
            $this->auditlog($id, "Job Description", "Delete", $_GET, "hr_description_t");

        }
        if ($j == 1)
            return 1;
        else if ($j == 0)
            return 2;
        else if ($j == 3)
            return 3;
    }
    public function removedes(Request $request, $id = null)
    {

        $column = array('desc_id');
        $table = array('hr_job_description');

        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = DB::table($table[$i])->where($column[$i], $id)->get();
            // auditlog
            $this->auditlog($id, "Job Description", "Delete", $_GET, "hr_description_t");
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        if ($j == 0) {
            $query = DB::table('hr_description_t')->where('description_id', $id)->delete();
            // auditlog
            $this->auditlog($id, "Job Description", "Delete", $_GET, "hr_description_t");

        }
        if ($j == 1)
            return 1;
        else if ($j == 0)
            return 2;
    }

    public function jobdescriptionformgriddata()
    {

        $comp = \Session::get('companyid');
        $wh = '';


        $SQL = "SELECT 
            m_department_lines_t.sub_department_name,
            m_department_lines_t.department_line_id as department_id,
            m_job_title.job_title_name,
            m_position.position,
            hr_job_description.reqired_skills,
            hr_job_description.active,
            hr_job_description.description_name,
            hr_job_description.description_id,
            hr_job_description.reqired_skills,
            hr_job_description.month,
            hr_job_description.year,
            hr_job_description.min_salary,
            hr_job_description.max_salary,
            hr_job_description.min_experience,
            hr_job_description.team_leads_id,
            hr_job_description.max_experience,
            hr_job_description.no_of_persons,
            hr_job_description.job_title,
            hr_job_description.desc_id,
            hr_job_description.interview_process
            from hr_job_description 
            LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_job_description.department
            LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_job_description.job_title
            LEFT JOIN m_position ON m_position.position_id = hr_job_description.position_id where 1=1  and hr_job_description.company_id='$comp'  $wh ";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }

    /** Jqgrid Description Load Data Start **/
    public function descriptionformgriddata()
    {
        $comp = \Session::get('companyid');

        $wh = '';



        $SQL = "SELECT 
            hr_description_t.description_id,
            hr_description_t.description_name,
            hr_description_t.file,
            hr_description_t.active
            from hr_description_t   where 1=1  and hr_description_t.company_id='$comp' $wh";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }
    /** Jqgrid Description Load Data End **/

    public function getRemove(Request $request)
    {

        $delete_id = Input::get('del_id');
        $j = 0;

        if ($j == 0) {
            $query = DB::table('hr_job_description')->where('description_id', $delete_id)->delete();
            // auditlog
            $this->auditlog($delete_id, "Job Description", "create", $_GET, "hr_job_description");
        }

        if ($j == 1)
            return 1;
        else if ($j == 0)
            return 2;
        else if ($j == 3)
            return 3;


        return 1;

    }

    public function descriptionapprove($status = null, $id = null)
    {
        $description = $id;
        $status = $status;
        $approve_status = ($status == "approve") ? 1 : 2;
        $update_description = DB::table('hr_job_description')->where('description_id', $description)->update(['approve_status' => $approve_status]);
        // auditlog
        $this->auditlog($id, "Job Description", "Update", $status, "hr_job_description");
        return $approve_status;

    }
    public function descriptionapprovehr($status = null, $id = null)
    {
        $description = $id;
        $status = $status;
        
        $approve_status = ($status == "approve") ? 3 : 4;
        $update_description = DB::table('hr_job_description')->where('description_id', $description)->update(['approve_status' => $approve_status]);
        // auditlog
        $this->auditlog($id, "Job Description", "Update", $status, "hr_job_description");
        return $approve_status;

    }


}
