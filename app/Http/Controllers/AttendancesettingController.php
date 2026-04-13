<?php

namespace App\Http\Controllers;

use App\Attendancesetting;
use Illuminate\Http\Request, DB, DateTime;
use Yajra\DataTables\DataTables;

class AttendancesettingController extends Controller
{
  public function __construct()
  {
    $this->data = array();
    $this->model = new Attendancesetting;
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['pageFormtype'] = 'ajax';
    $this->data['pageModule'] = \Request::route()->getName();
    $this->middleware('auth');
    $this->data['urlmenu'] = $this->indexs();
  }

  /** Attendance Setting Jqgrid load data Start **/
  public function index()
  {
    return view('attendancesetting.table');
  }
  /** Attendance Setting Jqgrid load data End **/

  public function attendenceimportindex()
  {
    return view('attendancesetting.attenimport');
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

    $table = \DB::table('hr_attendance_settings_t')->get();
    return view('attendancesetting.form', $this->data);
  }
  /** Attendance Setting Save Start **/
  public function save(Request $request)
  {
    $edit = $request->input('edit_id');
    if ($edit == '') {
      $attendancesetting = new Attendancesetting();
      $attendancesetting->ot_formula = $request->input('ot_formula');
      $attendancesetting->employee_type = $request->input('employee_type');
      $attendancesetting->min_ot = $request->input('min_ot');
      $attendancesetting->max_ot = $request->input('max_ot');
      $attendancesetting->employee_type = $request->input('employee_type');
      $attendancesetting->attendance_rules = json_encode($request->input('attendance_rules'));
      $attendancesetting->rules_data = json_encode($request->input('rules_data'));
      $attendancesetting->save();
      $id = $attendancesetting->attendance_settings_id;
      $table = $attendancesetting->getTable();
      $column = $attendancesetting->getKeyName();
      $this->hrmssaveinsert($table, $column, $id, 1);
      // auditlog
      $this->auditlog($id, "Attendancesetting", "create", $_POST, "hr_attendance_settings_t");
      return 1;
    } else {
      /** Attendance Setting End Start **/
      $attendancesetting = Attendancesetting::findOrFail($edit);
      $rules_data = json_encode($request->input('rules_data'));
      $ot_formula = $request->input('ot_formula');
      $min_ot = $request->input('min_ot');
      $max_ot = $request->input('max_ot');
      $department_name = $request->input('employee_type');
      $attendance_rules = json_encode($request->input('attendance_rules'));

      $result = DB::table('hr_attendance_settings_t')->where('attendance_settings_id', $edit)->update([
        'attendance_rules' => $attendance_rules,
        'rules_data' => $rules_data,
        'ot_formula' => $ot_formula,
        'min_ot' => $min_ot,
        'max_ot' => $max_ot,
        'employee_type' => $department_name
      ]);
      $table = $attendancesetting->getTable();
      $column = $attendancesetting->getKeyName();
      // auditlog
      $this->auditlog($edit, "Attendancesetting", "Edit", $_POST, "hr_attendance_settings_t");
      $this->hrmssaveinsert($table, $column, $edit, 2);
      return 2;
      /** Attendance Setting End End **/
    }

  }


  public function attedancesettinggriddata(Request $request)
  {


    $comp = \Session::get('companyid');
    $wh = "and hr_attendance_settings_t.company_id='$comp'";

    $SQL = "SELECT
			hr_attendance_settings_t.*,
			
			(CASE  WHEN hr_attendance_settings_t.ot_formula = '1' THEN 'OT Applicable'

                        WHEN hr_attendance_settings_t.ot_formula = '2' THEN 'OT NOT Apllicable'
                        END ) as ot,
                        a_lookuplines_t.lookup_code
			FROM
			hr_attendance_settings_t left join  a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_attendance_settings_t.employee_type
			WHERE 1=1 
			$wh";

    $data = \DB::select($SQL);

    return DataTables::of($data)->make(true);

  }




  public function destroy(Request $request, $id = null)
  {

    $j = 0;

    if ($j == 0) {
      $query = DB::table('hr_attendance_settings_t')->where('attendance_settings_id', $id)->delete();
      // auditlog
      $this->auditlog($id, "Attendancesetting", "Delete", $query, "hr_attendance_settings_t");
    }

    if ($j == 1) {
      // auditlog
      $this->auditlog($id, "Attendancesetting", "Delete", $query, "hr_attendance_settings_t");
      return 1;
    } else if ($j == 0) {
      return 2;
    } else if ($j == 3) {
      $this->auditlog($id, "Attendancesetting", "Delete", $query, "hr_attendance_settings_t");
      return 3;
    }
  }



  /** Attendance Setting Upload Excel data Start **/
  public function attendanceuploadexcel(Request $request)
  {
    $datas = $_POST;
    unset($datas['removed_line_id']);
    $datas['uploaded_by'] = \Session::get('empid');
    $dates['date'] = date('Y-m-d');
    $updates = array();
    $file = $request->file('atten_file');

    $destinationPath = './uploads/attendance/';
    $filename = $file->getClientOriginalName();
    $extension = $file->getClientOriginalExtension(); //if you need extension of the file
    $date = new DateTime();
    $fname = $date->getTimestamp();
    $newfilename = $filename . '_' . $fname . '.' . $extension;
    $datas['file_name'] = $newfilename;


    $path = $_FILES['atten_file']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $data = array();
    $file = $_FILES['atten_file']['tmp_name'];
    $handle = fopen($file, "r");
    $c = 0;


    if ($ext == "csv") {
      $id = \DB::table('hr_attendance_import_details_tbl')->insertGetId($datas);

      $attendencedata['file_id'] = $id;
      $attendencedata['month'] = $datas['month'];
      $attendencedata['year'] = $datas['year'];
      $attendencedata['atten_type'] = 3;
      while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
        if ($c != 0) {
          $attendencedata['emp_id'] = $filesop[0];
          $attendencedata['atten_date'] = date('Y-m-d', strtotime($filesop[1]));
          $attendencedata['check_in'] = date('H:i:s', strtotime($filesop[2]));
          $attendencedata['check_out'] = date('H:i:s', strtotime($filesop[3]));
          $attendencedata['upload_status'] = "0";
          $to_time = strtotime($filesop[3]);
          $from_time = strtotime($filesop[2]);
          $attendencedata['working_hours'] = $time = $to_time - $from_time;
          $value = date('H:i:s', $attendencedata['working_hours']);
          $value = strtotime($value);
          $datas = \DB::table('shift_timing')->where('shift_name', 'like', 'upload')->get();

          if (count($datas) > 0) {
            $start_time = $datas[0]->start_time;
            $end_time = $datas[0]->end_time;
            $end_times = strtotime($end_time);
            $start_times = strtotime($start_time);
            $to_time = $end_times - $start_times;
          } else {
            $time = "09:00:00";
            $to_time = strtotime($time);
          }

          if ($value < $to_time)
            $ot = 000;
          else
            $ot = $value - $to_time;

          if ($ot == 0) {
            $attendencedata['ot'] = $ot;
          } else {
            $attendencedata['ot'] = $ot;
          }

          $attendencedata['company_id'] = \Session::get('companyid');
          $attendencedata['location_id'] = \Session::get('location');
          $attendencedata['organization_id'] = \Session::get('organization');
          $attendencedata['created_by'] = \Session::get('id');
          $attendencedata['created_date'] = date('Y-m-d');
          \DB::table('hr_emp_attendence_upload')->insert($attendencedata);
        }
        $c++;
      }
      $file_upload = $request->file('atten_file');
      $file_upload->move($destinationPath, $newfilename);
      // $uploadSuccess = $file->move($destinationPath, $newfilename);
    } else {
      return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
    }
    return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));

  }


  public function attendenceimportapprove($month = null, $year = null)
  {
    $comp = \Session::get('companyid');
    $data = \DB::select("select * from hr_emp_attendence_upload where month='$month' and year='$year' and company_id='$comp' and upload_status='0'");
    if (count($data) > 0) {
      foreach ($data as $key => $value) {
        $datas = \DB::table('hr_emp_attendence_upload')->where('atten_id', '=', $value->atten_id)->get();
        $collection['emp_id'] = $datas[0]->emp_id;
        $collection['atten_date'] = $datas[0]->atten_date;
        $collection['check_in'] = $datas[0]->check_in;
        $collection['check_out'] = $datas[0]->check_out;
        $collection['working_hours'] = $datas[0]->working_hours;
        $collection['ot'] = $datas[0]->ot;
        $collection['created_by'] = \Session::get('id');
        $collection['created_date'] = date('Y-m-d');
        $collection['company_id'] = $datas[0]->company_id;
        $collection['location_id'] = $datas[0]->location_id;
        $collection['organization_id'] = $datas[0]->organization_id;
        $collection['upload_status'] = "1";
        $collection['month'] = $datas[0]->month;
        $collection['year'] = $datas[0]->year;
        \DB::table('hr_emp_attendence')->insert($collection);
        \DB::update("update hr_emp_attendence_upload set upload_status=1 where atten_id='$value->atten_id'");
        // auditlog
        $this->auditlog($value->atten_id, "Attendancesetting", "Update", $collection, "hr_employee_advance_t");
      }
      return response()->json(array('status' => 'success', 'message' => 'Your data Approved successfully!!'));
    } else {
      return response()->json(array('status' => 'error', 'message' => 'No data to Approve'));
    }
  }

  public function emp_typecheck()
  {
    $id = $_GET['id'];
    $em_ty = \DB::select("SELECT employee_type FROM hr_attendance_settings_t WHERE employee_type=$id");
    if (empty($em_ty)) {
      return 1;
    } else {
      return 2;
    }
  }
  /** Attendance Setting Employee Type Check data End **/
}