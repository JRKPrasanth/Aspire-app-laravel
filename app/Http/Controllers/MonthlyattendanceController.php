<?php

namespace App\Http\Controllers;

use App\Monthlyattendance;
use Illuminate\Http\Request;
use DB, Session, DateTime;
use Yajra\DataTables\DataTables;

class MonthlyattendanceController extends Controller
{
  /** index page to monthly load function start **/
  public function monthlyuploadindex(Request $request)
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

    $this->data['pageMethod'] = \Request::route()->getName();

    $this->data['pageModule'] = \Request::route()->getName();
    $this->data['urlmenu'] = $this->indexs();
    return view('monthlyentry.upload', $this->data);
  }
  /*** attendance upload **/
  public function attendancemonthupload(Request $request)
  {
    $datas['uploaded_by'] = \Session::get('empid');
    $dates['date'] = date('Y-m-d');

    if (!is_null($request->file('file_upload'))) {
      $updates = array();
      $file = $request->file('file_upload');
      $destinationPath = './Uploads/monthupload/';
      $filename = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension(); //if you need extension of the file
      $date = new DateTime();
      $fname = $date->getTimestamp();
      $newfilename = $filename . '_' . $fname . '.' . $extension;
      $datas['file_name'] = $newfilename;
      $id = \DB::table('attendance_import_details')->insertGetId($datas);
      $path = $_FILES['file_upload']['name'];
      $ext = pathinfo($path, PATHINFO_EXTENSION);
      $data = array();
      $file = $_FILES['file_upload']['tmp_name'];
      $handle = fopen($file, "r");
      $c = 0;
      $attendencedata['file_id'] = $id;
      $attendencedata['month'] = $request->input('month');
      $attendencedata['year'] = $request->input('year');

      if ($ext == "csv") {
        while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
          if ($c != 0) {

            $check_data = DB::table('hr_employee_t')->where('employee_number', $filesop[0])->get();

            if (count($check_data) > 0) {
              $attendencedata['employee_id'] = $check_data[0]->employee_id;
              $originalDate = new DateTime($filesop[1]);
              $newDate = date_format($originalDate, "Y-m-d");
              $attendencedata['start_date'] = $newDate;
              $originalDate1 = new DateTime($filesop[2]);
              $newDate1 = date_format($originalDate1, "Y-m-d");
              $attendencedata['end_date'] = $newDate1;
              $attendencedata['no_of_days'] = $filesop[3];
              $attendencedata['no_of_days_employee'] = $filesop[4];
              $attendencedata['no_of_present_days'] = $filesop[5];
              $attendencedata['loan_deduction'] = $filesop[6];
              $attendencedata['c_l'] = $filesop[7];
              $attendencedata['s_l'] = $filesop[8];
              $attendencedata['e_l'] = $filesop[9];
              $attendencedata['ot_hours'] = $filesop[10];
              $attendencedata['company_id'] = \Session::get('companyid');
              $attendencedata['location_id'] = "1";
              $attendencedata['created_by'] = \Session::get('id');
              $attendencedata['upload_status'] = 0;
              $attendencedata['created_at'] = date('Y-m-d');

              $id = \DB::table('hr_monthly_attendance_upload')->insertGetId($attendencedata);
              // auditlog
              $this->auditlog($id, "monthlyupload", "create", $_POST, "hr_monthly_attendance_upload");
            }
          }
          $c++;
        }
        return 1;
      } else {
        return 2;
      }

      $uploadSuccess = $file->move($destinationPath, $newfilename);
    }
  }
  /*** attendance upload approval **/
  public function monthuploadapproval(Request $request)
  {
    $month = $_GET['month'];
    $year = $_GET['year'];
    $query_data = DB::table("hr_monthly_attendance_upload")->where('month', $month)->where('year', $year)->where('upload_status', 0)->get();
    if (count($query_data) > 0) {
      foreach ($query_data as $key => $value) {
        $attendencedata['month'] = $request->input('month');
        $attendencedata['year'] = $request->input('year');
        $attendencedata['employee_id'] = $value->employee_id;
        $attendencedata['start_date'] = $value->start_date;
        $attendencedata['end_date'] = $value->end_date;
        $attendencedata['no_of_days'] = $value->no_of_days;
        $attendencedata['no_of_days_employee'] = $value->no_of_days_employee;
        $attendencedata['no_of_present_days'] = $value->no_of_present_days;
        if (strtolower($value->loan_deduction) == "yes") {
          $loan = 1;
        } else {
          $loan = 0;
        }
        $attendencedata['loan_deduction'] = $loan;
        $attendencedata['c_l'] = $value->c_l;
        $attendencedata['s_l'] = $value->s_l;
        $attendencedata['e_l'] = $value->e_l;
        $attendencedata['ot_hours'] = $value->ot_hours;
        $attendencedata['company_id'] = \Session::get('companyid');
        $attendencedata['location_id'] = "1";
        $attendencedata['created_by'] = \Session::get('id');
        $attendencedata['upload_file'] = 1;
        $attendencedata['created_at'] = date('Y-m-d');

        $id = \DB::table('hr_monthly_attendance')->insertGetId($attendencedata);
        // auditlog
        $this->auditlog($value->monthly_atten_id, "attendanceuploadapprove", "approve", $_GET, "hr_monthly_attendance_upload");

      }
    }
    $query = DB::table("hr_monthly_attendance_upload")->where('month', $month)->where('year', $year)->update(["upload_status" => 1]);

    $result = 1;
    return $result;
  }

  public function monthlyuploadgrid()
  {

	 $comp = \Session::get('companyid');
    $wh= "  and hr_monthly_attendance_upload.company_id='$comp'";

    $SQL = "SELECT
                    hr_monthly_attendance_upload.*,
                    hr_employee_t.*, 'Initiated' as upload_status
                    FROM
                    hr_monthly_attendance_upload
                    LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_monthly_attendance_upload.employee_id
                    WHERE
                    1 = 1 and upload_status=0 $wh ";
	  
    $data = \DB::select($SQL);
	  
        return DataTables::of($data)->make(true);
  }

  // index page load function
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

    $this->data['pageMethod'] = \Request::route()->getName();

    $this->data['pageModule'] = \Request::route()->getName();
    $this->data['urlmenu'] = $this->indexs();

    return view('monthlyentry.form', $this->data);
  }


  public function monthlytblData(Request $request)
  {


    $comp = \Session::get('companyid');
    $SQL = "SELECT
                    hr_monthly_attendance.monthly_atten_id,
                    hr_monthly_attendance.employee_id,
                    concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name ,
                    hr_monthly_attendance.start_date,
                    hr_monthly_attendance.end_date	,
                    hr_monthly_attendance.no_of_days,
                    hr_monthly_attendance.no_of_present_days,
                    hr_monthly_attendance.loan_deduction,
					(case 
                        when hr_monthly_attendance.loan_deduction = 0 then 'No'
                        when hr_monthly_attendance.loan_deduction = 1 then 'Yes'
                    end) as loan_status
                    FROM
                        hr_monthly_attendance
                    LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_monthly_attendance.employee_id
                   
                    WHERE
                        1 = 1  and hr_monthly_attendance.company_id=$comp order by monthly_atten_id desc";

    $data = \DB::select($SQL);


    return DataTables::of($data)->make(true);

  }


  //save function
  public function store(Request $request)
  {

    $edit_id = $request->input('edit_id');
    if ($edit_id == '') {
      $monthlyattendance = new Monthlyattendance();
      $monthlyattendance->employee_id = $employee_id = $emp_id = $request->input('employee_id');
      $monthlyattendance->start_date = $start_date = $data['start_date'] = date("Y-m-d", strtotime($request->input('start_date')));
      $monthlyattendance->end_date = $end_date = $data['end_date'] = date("Y-m-d", strtotime($request->input('end_date')));
      $monthlyattendance->no_of_days = $data['no_of_days'] = $request->input('no_of_days');

      $monthlyattendance->loan_deduction = $loan_deduction = $request->input('loan_deduction');
      $monthlyattendance->no_of_present_days = $data['no_of_present_days'] = $request->input('no_of_present_days');
      $monthlyattendance->ot_hours = $ot_hours = $request->input('ot_hours');
      $monthlyattendance->c_l = $cl = $request->input('c_l');
      $monthlyattendance->s_l = $sl = $request->input('s_l');
      $monthlyattendance->e_l = $el = $request->input('e_l');


      $mot = explode("-", $start_date);
      $d_date = $mot[2];
      $m_date = $mot[1];
      $y_date = $mot[0];
      $year = $year3 = $y_date;
      $month = $m_date;
      $monthlyattendance->month = $month;
      $monthlyattendance->year = $year;

      $query_count = DB::table('hr_employee_t')->where('employee_id', $employee_id)->get();
      $emp_type = $query_count[0]->employee_type;
      $check = DB::table('hr_employee_payproposal')->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', 'hr_employee_payproposal.payroll_type')->where('hr_employee_payproposal.employee_id', $employee_id)->where('hr_employee_payproposal.employee_type', $emp_type)->where('a_lookuplines_t.lookup_code', 'MONTHLY ENTRY')->get();

      if (count($check) > 0) {
        $update_check = Db::table("hr_employee_payroll_lists")->where('employee_id', $emp_id)->where('month', 'like', '%' . $month . '%')->where('year', $year)->get();

        if (count($update_check) > 0) {
          return 2;
        } else {
          $source = $check[0]->lookuplines_id;
          // save monthly attendance
          $monthlyattendance->save();
          $id = $monthlyattendance->monthly_atten_id;
          $table = $monthlyattendance->getTable();
          $column = $monthlyattendance->getKeyName();
          $this->hrmssaveinsert($table, $column, $id, 1);
          // auditlog
          $this->auditlog($id, "monthly", "create", $_POST, "hr_monthly_attendance");
          $location_id = Session::get('location');
          $grade_details = DB::table("hr_employee_payproposal")->where('employee_id', $employee_id)->get();
          $total_days = $data['no_of_days'];
          $working_days = $data['no_of_present_days'];

          if (count($grade_details) > 0) {

            $gross_pay = $grade_details[0]->gross_pay;
            $basic_pay = $grade_details[0]->basic_pay;
            $hra = $grade_details[0]->hra;
            $da = $grade_details[0]->da;
            $allowance = $grade_details[0]->allowance;
            $volunter_pf = $grade_details[0]->volunter_pf;
            if ($volunter_pf == '') {
              $volunter_pf = 0;
            }
            if ($total_days != $working_days) {
              $total_basic = $basic_pay / $total_days;
              $total_hra = $hra / $total_days;
              $total_da = $da / $total_days;
              $basic_pay = $total_basic * $working_days;
              $hra = $total_hra * $working_days;
              $da = $total_da * $working_days;
              $allow = json_decode($allowance);

              $allow_en = [];
              foreach ($allow as $k => $v) {
                foreach ($v as $k1 => $v1) {
                  $total_allow = $v1 / $total_days;
                  $add = ($total_allow * $working_days);
                  $allow_en[$k][$k1] = (string) round($add, 2);
                }
              }
              $allowance = json_encode($allow_en);

            }
            $gratuity = $grade_details[0]->gratuity;
            $bonus_amount = $grade_details[0]->bonus_amount;
            $annual_allowance = $grade_details[0]->annual_allowance;
            $length = \DB::select("SELECT COUNT(DISTINCT to_value) as length FROM `hr_tax_master`");
            $length = $length[0]->length;
            $limit = 0;
            $tax_value = 0;
            $tax = 0;

            //Checking Presentage For Annual  Income for Basic Pay
            for ($i = 1; $i <= $length; $i++) {

              $tax = DB::table("hr_tax_master")->where('from_value', $limit)->orderBy('date', 'DESC')->get();

              $from_value = $tax[0]->from_value;
              $to_value = $tax[0]->to_value;
              $anu_pay = ($basic_pay + $tax_value) * 12;
              if ($anu_pay >= $from_value && $anu_pay <= $to_value) {
                $bas_pay = $basic_pay + $tax_value;
                $precentage = $tax[0]->precentage;
                $tax = ($bas_pay * $precentage) / 100;
                $i = $length + 1;
              } else {
                $limit = $to_value + 1;
              }
              //If Tax is zero No deduction 

              //end of tax calculation

            }

            $gross = $check[0]->gross_pay;
            $total = $gross / $total_days;
            $gross_salary = $total * $working_days;

            $pf_value = $basic_pay + $da;
            $esi_value = $gross_salary;
            //ESI query
            $esi_detatil = $esi = DB::table("m_emp_esi")
              ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
              ->select('m_emp_esi.limitto', 'm_emp_esi.employeer_contribute', 'm_emp_esi.company_contribute')->where('lookup_code', 'ESI')->get();
            //PF query
            $pf_detatil = DB::table("m_emp_esi")
              ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
              ->select('m_emp_esi.limitto', 'm_emp_esi.employeer_contribute', 'm_emp_esi.company_contribute')->where('lookup_code', 'PF')->get();


            if ($grade_details[0]->esi == 1) {
              if (count($esi_detatil) > 0) {
                $limit = $esi_detatil[0]->limitto;

                if ($esi_value > $limit) {
                  $emp_con = $limit * $esi_detatil[0]->employeer_contribute / 100;
                  $com_con = $limit * $esi_detatil[0]->company_contribute / 100;

                } else {
                  $emp_con = $esi_value * $esi_detatil[0]->employeer_contribute / 100;
                  $com_con = $esi_value * $esi_detatil[0]->company_contribute / 100;
                }
                $esi = $emp_con;
                $esi_contribute['emp_id'] = $emp_id;
                $esi_contribute['month'] = $month;
                $esi_contribute['year'] = $year3;
                $esi_contribute['amount'] = round($com_con);
                $esi_contribute['amount_employee'] = round($emp_con);
                $esi_contribute['date'] = date('Y-m-d');

                $query_contribute = DB::table('hr_company_contribute_esi')->insertGetId($esi_contribute);
                // auditlog
                $this->auditlog($query_contribute, "monthly", "create", $esi_contribute, "hr_company_contribute_esi");
              }
            } else {
              $esi = 0;
            }

            //PF calculation and deduction
            if ($grade_details[0]->pf == 1) {
              if (count($pf_detatil) > 0) {
                $limit = $pf_detatil[0]->limitto;
                if ($pf_value > $limit) {
                  $pf = $limit * $pf_detatil[0]->employeer_contribute / 100;
                  $emp_con1 = $pf;
                  $com_con1 = $limit * $pf_detatil[0]->company_contribute / 100;

                } else {
                  $pf = $pf_value * $pf_detatil[0]->employeer_contribute / 100;
                  $emp_con1 = $pf;
                  $com_con1 = $pf_value * $pf_detatil[0]->company_contribute / 100;
                }
                $pf = $volunter_pf + $pf;
                $pf_contribute['emp_id'] = $emp_id;
                $pf_contribute['volunter_pf'] = $volunter_pf;
                $pf_contribute['month'] = $month;
                $pf_contribute['year'] = $year3;
                $pf_contribute['amount'] = round($com_con1);
                $pf_contribute['amount_employee'] = round($emp_con1);
                $pf_contribute['date'] = date('Y-m-d');

                $query_contribute = DB::table('hr_company_contribute_pf')->insertGetId($pf_contribute);
                // auditlog
                $this->auditlog($query_contribute, "monthly", "create", $pf_contribute, "hr_company_contribute_pf");
              }
            } else {
              $pf = 0;
            }


            //        IF Professional tax 
            $professional_taxs = \DB::select("select hr_professional_tax_lines.* from m_location_t JOIN hr_professional_tax_hdr ON hr_professional_tax_hdr.ptax_state_id=m_location_t.state_id JOIN hr_professional_tax_lines ON hr_professional_tax_lines.ptax_id=hr_professional_tax_hdr.ptax_id where m_location_t.location_id='$location_id'");

            $professional_tax = 0;
            if ($grade_details[0]->pt == 1) {
              if (count($professional_taxs) > 0) {

                foreach ($professional_taxs as $index => $value) {
                  if ($gross >= $value->from_value && $gross <= $value->to_value) {
                    $professional_tax = $value->deduction_amount;
                    break;
                  } else {
                    $professional_tax = 0;
                  }
                }
              } else {
                $professional_tax = 0;
              }
            } else {
              $professional_tax = 0;
            }

            $tax_amount = $tax;
            $net_salary = round($gross_salary - $tax_amount, 2);
            $net_salary = round($gross_salary - $pf - $esi - $tax_amount - $professional_tax, 2);
            $loan_deduction_amt = 0;
            //IF DEDUCTION AMOUNT
            $total_loan_amount = 0;


            if ($loan_deduction == 1) {

              $deduction = DB::table("hr_employee_advance_t")->where('employee_id', $emp_id)->where('remaining_amount', '!=', 0)->where('paid_status', 0)->where('approved_status', '=', 1)->get();

              if (!empty($deduction)) {
                foreach ($deduction as $key => $value) {

                  $permonth_amount = $deduction[0]->amount / $deduction[0]->emi;
                  $paid_amount1 = $value->remaining_amount - $permonth_amount;

                  if ($value->remaining_amount < $permonth_amount)
                    $paid_amount1 = $value->remaining_amount;

                  $loan_deduction_amt = $value->remaining_amount;

                  $loan_deduction_amt = $permonth_amount;
                  $paid_amount = round($deduction[0]->paid_amount + round($permonth_amount));
                  $remaining_amount = round($value->remaining_amount - $loan_deduction_amt);
                  $net_salary = round($net_salary - $loan_deduction_amt);
                  $total_loan_amount = round($total_loan_amount + $loan_deduction_amt);
                  $employee_advance_id = $value->advance_id;
                  $emp_advance = array(
                    "paid_amount" => $paid_amount,
                    "remaining_amount" => $remaining_amount,
                  );

                  $advance = DB::table('hr_employee_advance_t')->where('advance_id', $employee_advance_id)->limit(1)->update($emp_advance);
                  // auditlog
                  $this->auditlog($employee_advance_id, "monthly", "update", $emp_advance, "hr_employee_advance_t");
                  if ($paid_amount1 == $value->remaining_amount) {
                    $query1 = DB::table("hr_employee_advance_t")->where('advance_id', $employee_advance_id)->update(['paid_status' => 1]);
                    // auditlog
                    $update['paid_status'] = 1;
                    $this->auditlog($employee_advance_id, "monthly", "update", $update, "hr_employee_advance_t");
                  }
                  $current_date = date('Y-m-d');
                  $deductions_data = DB::table("hr_employee_advance_t")->where('employee_id', $employee_id)->where('paid_status', 0)->where('approved_status', 1)->get();

                  $deductions = array(
                    'ref_id' => $deductions_data[0]->advance_id,
                    'employee_id' => $deductions_data[0]->employee_id,
                    'deduction_date' => $current_date,
                    'emi_amount' => round($loan_deduction_amt, 2),
                    'total_amount' => round($deductions_data[0]->amount, 2),
                    'till_paid_amount' => $deductions_data[0]->paid_amount,
                    'till_remaining_amount' => $deductions_data[0]->remaining_amount,
                    'amount_pay' => $paid_amount1,
                    'company_id' => \Session::get('companyid'),
                    'location_id' => "1",
                    'organization_id' => \Session::get('organization'),
                    'created_at' => date('Y-m-d'),
                    'created_by' => \Session::get('id'),
                  );


                  if ($permonth_amount == $value->remaining_amount) {


                    $query2 = DB::table("hr_employee_advancedeductions_t")->where('paid_status', 0)->where('employee_id', $emp_id)->where('ref_id', $employee_advance_id)->update(['paid_status' => 1]);
                    // auditlog
                    $update['paid_status'] = 1;
                    $this->auditlog($employee_advance_id, "monthly", "update", $update, "hr_employee_advancedeductions_t");
                  }

                  $employee_deductions = DB::table('hr_employee_advancedeductions_t')->insertGetId($deductions);
                  // auditlog
                  $this->auditlog($employee_deductions, "monthly", "update", $deductions, "hr_employee_advancedeductions_t");
                }
              }
            }
            if ($ot_hours != '') {
              $ot_amount = ($basic_pay / $total_days) / 8 * 1 * $ot_hours;
            } else {
              $ot_amount = 0;
            }
            // check leave for an  employee
            $leave_policy = DB::table('hr_employee_t')->where('employee_id', $emp_id)->get();
            if (count($leave_policy) > 0) {
              $casual_leave = $leave_policy[0]->c_l;
              $sick_leave = $leave_policy[0]->s_l;
              $earn_leave = $leave_policy[0]->e_l;
            } else {
              $casual_leave = 0;
              $sick_leave = 0;
              $earn_leave = 0;
            }

            $r_casual_leave = $casual_leave - $cl;
            $update_query = DB::table('hr_employee_t')->where('employee_id', $emp_id)->update(['c_l' => $r_casual_leave]);
            // auditlog
            $data['rem_c_l'] = $r_casual_leave;
            $data['actual_c_l'] = $casual_leave;
            $data['taken_c_l'] = $cl;
            //aufitlog
            $this->auditlog($emp_id, "monthly", "update", $data, "hr_employee_t");

            $r_sick_leave = $sick_leave - $sl;
            $update_query = DB::table('hr_employee_t')->where('employee_id', $emp_id)->update(['s_l' => $r_sick_leave]);
            // auditlog
            $data2['rem_s_l'] = $r_sick_leave;
            $data2['actual_s_l'] = $sick_leave;
            $data2['taken_s_l'] = $sl;
            //aufitlog
            $this->auditlog($emp_id, "monthly", "update", $data2, "hr_employee_t");

            $r_earn_leave = $earn_leave - $el;
            $update_query = DB::table('hr_employee_t')->where('employee_id', $emp_id)->update(['e_l' => $r_earn_leave]);
            // auditlog
            $data1['rem_e_l'] = $r_earn_leave;
            $data1['actual_e_l'] = $earn_leave;
            $data1['taken_e_l'] = $el;
            //aufitlog
            $this->auditlog($emp_id, "monthly", "update", $data1, "hr_employee_t");

            $data_pay = array(
              'employee_id' => $emp_id,
              'month' => $month,
              'year' => $year,
              'basic_salary' => round($basic_pay, 2),
              'hra' => round($hra, 2),
              'da' => round($da, 2),
              'pf' => round($pf, 2),
              'esi' => round($esi, 2),
              'allowance' => $allowance,
              'annual_allowance' => round($annual_allowance),
              'gratuity' => round($gratuity),
              'gross_salary' => round($gross_salary, 2),
              'net_salary' => round($net_salary, 2),
              'date' => date('Y-m-d'),
              'review_status' => 0,
              'total_days' => $total_days,
              'attendance_days' => $working_days,
              'tax_amount' => round($tax_amount),
              'cl' => $cl,
              'el' => $el,
              'sl' => $sl,
              'ot' => round($ot_amount, 2),
              'loan_deduction' => round($total_loan_amount, 2),
              'atten_status' => $source,
              'company_id' => \Session::get('companyid'),
              'location_id' => "1",
              'organization_id' => \Session::get('organization'),
              'created_at' => date('Y-m-d'),
              'created_by' => \Session::get('id')
            );
            $update = DB::table('hr_employee_payroll_lists')->insertGetId($data_pay);
            //auditlog
            $this->auditlog($update, "payrollgenerate", "update", $data, "hr_employee_payroll_lists");


          }
        }

        return 1;
      } else {
        return 3;
      }


    }

  }




  // check employee payroll type
  public function employeecheckpayrolltype($id)
  {
    $query = DB::table('hr_employee_payproposal')->where('employee_id', $id)->get();
    // pay proposal not mean
    if (count($query) > 0) {
      $query_data = DB::table('hr_employee_payproposal')->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', 'hr_employee_payproposal.payroll_type')->where('a_lookuplines_t.lookup_code', 'MONTHLY ENTRY')->where('employee_id', $id)->get();
      // payroll type is not monthly
      if (count($query_data) > 0) {
        return 3;
      } else {
        return 2;
      }
    } else {
      return 1;
    }
  }
  // check sl,el,cl
  public function checkclslel($id, $source)
  {
    $query = DB::table('hr_employee_t')->select('c_l', 'e_l', 's_l')->where('employee_id', $id)->get();
    if (count($query) > 0) {
      return $query[0]->$source;
    } else {
      return 0;
    }
  }
  // delete function
  public function destroy(Request $request, $id = null)
  {



    $data = DB::table('hr_monthly_attendance')->where('monthly_atten_id', $id)->get();

    $emp_id = $data[0]->employee_id;
    $j = 0;
    $column = array('employee_id');
    $table = array('hr_employee_payroll_lists');
    for ($i = 0; $i < count($table); $i++) {
      $j = 0;
      $query = DB::table($table[$i])->where($column[$i], $emp_id)->where('approved_status', 1)->where('month', $data[0]->month)->where('year', $data[0]->year)->get();
      //dd($query);
      if (count($query) > 0) {
        $j = 1;
        break;
      }
    }
    if ($j == 0) {

      $query = DB::table('hr_monthly_attendance')->where('monthly_atten_id', $id)->delete();
      // auditlog
      $this->auditlog($id, "monthly", "delete", $data[0], "hr_monthly_attendance");

      $payroll_data = DB::table('hr_employee_payroll_lists')->where('employee_id', $emp_id)->where('month', $data[0]->month)->where('year', $data[0]->year)->get();

      $query = DB::table('hr_employee_payroll_lists')->where('employee_id', $emp_id)->where('month', $data[0]->month)->where('year', $data[0]->year)->delete();
      // auditlog
      $this->auditlog($payroll_data[0]->id, "monthly", "delete", $payroll_data[0], "hr_employee_payroll_lists");

      //cl,sl,el leave again update

      $pay['pay_s_l'] = $pay_s_l = $payroll_data[0]->sl;
      $pay['pay_c_l'] = $pay_c_l = $payroll_data[0]->cl;
      $pay['pay_e_l'] = $pay_e_l = $payroll_data[0]->el;


      $emp_data = DB::table('hr_employee_t')->where('employee_id', $emp_id)->get();

      $pay['emp_s_l'] = $emp_s_l = $emp_data[0]->s_l;
      $pay['emp_c_l'] = $emp_c_l = $emp_data[0]->c_l;
      $pay['emp_e_l'] = $emp_e_l = $emp_data[0]->e_l;
      $pay['update_s_l'] = $update_s_l = $pay_s_l + $emp_s_l;
      $pay['update_c_l'] = $update_c_l = $pay_c_l + $emp_c_l;
      $pay['update_e_l'] = $update_e_l = $pay_e_l + $emp_e_l;
      $update_query = DB::table('hr_employee_t')->where('employee_id', $emp_id)->update(['c_l' => $update_c_l, 's_l' => $update_s_l, 'e_l' => $update_e_l]);
      // auditlog
      $this->auditlog($emp_id, "monthly", "update", $pay, "hr_employee_t");
    }

    if ($j == 1)
      return 1;
    else if ($j == 0)
      return 0;

  }


  //ajima
  public function monthlyattendancedata()
  {
    //dd($_GET['filters']);
    $compy = \Session::get('companyid');
    $logged_user = \Session::get('emp_id');
    $wh = "";
    $wh .= "and v1.company_id='$compy'";

    if ($_GET['_search'] == 'true') {
      $wh .= $this->jqgridsearchnotab('v1', $_GET['filters']);
    }

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if (!$sidx)
      $sidx = 1;
    $result = \DB::select("select * from (SELECT
                    hr_monthly_attendance.monthly_atten_id,
                    hr_monthly_attendance.company_id,
                    hr_monthly_attendance.employee_id,
                    concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name ,
                    hr_monthly_attendance.start_date,
                    hr_monthly_attendance.end_date  ,
                    hr_monthly_attendance.no_of_days,
                    hr_monthly_attendance.no_of_present_days,
                    hr_monthly_attendance.loan_deduction,
                    (case 
                        when hr_monthly_attendance.loan_deduction = 0 then 'No'
                        when hr_monthly_attendance.loan_deduction = 1 then 'Yes'
                    end) as loan_status
                    FROM
                        hr_monthly_attendance
                    LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_monthly_attendance.employee_id) v1 where 1=1 $wh GROUP BY v1.monthly_atten_id");
    $count = count($result);
    if ($limit == 0)
      $limit = $count;

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
    $comp = \Session::get('companyid');
    $SQL = "select * from (SELECT
                    hr_monthly_attendance.monthly_atten_id,
                    hr_monthly_attendance.employee_id,
                      hr_monthly_attendance.company_id,
                    concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name ,
                    hr_monthly_attendance.start_date,
                    hr_monthly_attendance.end_date  ,
                    hr_monthly_attendance.no_of_days,
                    hr_monthly_attendance.no_of_present_days,
                    hr_monthly_attendance.loan_deduction,
                    (case 
                        when hr_monthly_attendance.loan_deduction = 0 then 'No'
                        when hr_monthly_attendance.loan_deduction = 1 then 'Yes'
                    end) as loan_status
                    FROM
                        hr_monthly_attendance
                    LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_monthly_attendance.employee_id)v1 where 1=1  $wh ORDER by v1.monthly_atten_id $sord LIMIT $start , $limit";


    $download_SQL = "select * from ( SELECT
                    hr_monthly_attendance.monthly_atten_id,
                    hr_monthly_attendance.employee_id,
                      hr_monthly_attendance.company_id,
                    concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name ,
                    hr_monthly_attendance.start_date,
                    hr_monthly_attendance.end_date  ,
                    hr_monthly_attendance.no_of_days,
                    hr_monthly_attendance.no_of_present_days,
                    hr_monthly_attendance.loan_deduction,
                    (case 
                        when hr_monthly_attendance.loan_deduction = 0 then 'No'
                        when hr_monthly_attendance.loan_deduction = 1 then 'Yes'
                    end) as loan_status
                    FROM
                        hr_monthly_attendance
                    LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_monthly_attendance.employee_id)v1 where 1=1 $wh ORDER by v1.monthly_atten_id $sord";
    $result1 = \DB::select($download_SQL);
    $result1 = collect($result1)->map(function ($x) {
      return (array) $x; })->toArray();
    if (isset($_GET['download'])) {
      return $result1;
    }

    $result = \DB::select($SQL);


    // dd($SQL);
    $responce->rows[] = '';
    $responce->rows = $result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;

    echo json_encode($responce);
  }

}
