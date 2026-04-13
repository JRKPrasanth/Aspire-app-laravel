<?php

namespace App\Http\Controllers;

use App\Attendanceimport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DateTime;
use DB;
use Yajra\DataTables\DataTables;

class AttendanceimportController extends Controller
{
  public function __construct()
  {
    $this->data['pageModule'] = \Request::route()->getName();
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['urlmenu'] = $this->indexs();
  }

  /** index page to load function start **/
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


    return view('attendanceimport.table', $this->data);
  }
  /** index page to load function end **/
  public function attendanceimportdatagrid()
  {


    $SQL = "SELECT
                    hr_emp_attendence.*,
                    hr_employee_t.*, 'Initiated' as upload_status
                    FROM
                    hr_emp_attendence
                    LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_number = hr_emp_attendence.emp_id
                    WHERE
                    1 = 1 and upload_status=0";

    $data = \DB::select($SQL);
    return DataTables::of($data)->make(true);

  }
  /*** attendance upload **/
  public function attendanceupload(Request $request)
  {
    $datas['uploaded_by'] = \Session::get('empid');
    $datas['date'] = date('Y-m-d');

    if ($request->hasFile('file_upload')) {

      $file = $request->file('file_upload');

      // Prepare destination folder
      $destinationPath = public_path('Uploads/attendance');
      if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0777, true);
      }

      // Build filename
      $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $extension = $file->getClientOriginalExtension();
      $timestamp = time();
      $newfilename = $originalName . "_" . $timestamp . "." . $extension;

      $datas['file_name'] = $newfilename;

      // Insert and get file ID
      $file_id = DB::table('attendance_import_details')->insertGetId($datas);

      // Move uploaded file
      $file->move($destinationPath, $newfilename);

      // Read CSV
      if ($extension == "csv") {

        $csvPath = $destinationPath . '/' . $newfilename;
        $handle = fopen($csvPath, "r");
        $row = 0;

        while (($fileRow = fgetcsv($handle, 1000, ",")) !== false) {

          if ($row != 0) { // Skip header
            $empNumber = $fileRow[0];

            $employee = DB::table('hr_employee_t')
              ->where('employee_number', $empNumber)
              ->first();

            if ($employee) {

              $data = [
                'file_id' => $file_id,
                'month' => $request->month,
                'year' => $request->year,
                'atten_type' => 3,
                'emp_id' => $empNumber,
                'bio_id' => $employee->biometric_empno,
                'atten_date' => $fileRow[1],
                'check_in' => $fileRow[2],
                'check_out' => $fileRow[3],
                'company_id' => \Session::get('companyid'),
                'location_id' => \Session::get('location'),
                'created_by' => \Session::get('id'),
                'created_date' => date('Y-m-d'),
                'upload_status' => "0",
              ];

              // Calculate working hours
              $checkInParts = explode(' ', $data['check_in']);
              $checkOutParts = explode(' ', $data['check_out']);

              $startTime = strtotime($checkInParts[1]);
              $endTime = strtotime($checkOutParts[1]);

              $data['working_hours'] = $endTime - $startTime;

              $id = DB::table('hr_emp_attendence')->insertGetId($data);

              // audit log
              $this->auditlog($id, "attendanceimport", "create", $data, "hr_emp_attendence");
            }
          }
          $row++;
        }

        fclose($handle);
        return 1;
      }

      return 2; // Not CSV
    }

    return 0; // No file uploaded
  }

  /*** attendance upload approval **/
  public function monthapproval(Request $request)
  {
    $month = $_GET['month'];
    $year = $_GET['year'];
    $query_data = DB::table("hr_emp_attendence")->where('month', $month)->where('year', $year)->where('upload_status', 0)->get();
    if (count($query_data) > 0) {
      foreach ($query_data as $key => $value) {
        // auditlog
        $this->auditlog($value->atten_id, "attendanceimportupdate", "approve", $_GET, "hr_emp_attendence");
      }
    }
    $query = DB::table("hr_emp_attendence")->where('month', $month)->where('year', $year)->update(["upload_status" => 1]);

    $result = 1;
    return $result;
  }
}
