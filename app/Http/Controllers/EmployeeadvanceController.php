<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Employeeadvance;
use Illuminate\Http\Request;
use DB, Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Input;



class EmployeeadvanceController extends Controller
{
  /*** advance page load function start ***/
  public function __construct()
  {
    $this->data['pageModule'] = \Request::route()->getName();
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['urlmenu'] = $this->indexs();

  }

  public function employeeindex(Request $request)
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
    $this->data['urlmenu'] = $this->indexs();

    return view('advance.employeetable', $this->data);
  }

  public function getemployeegenData(Request $request)
  {
    $app_id = \Session::get('id');

    $result = \DB::select("SELECT * FROM (SELECT * FROM hr_employee_t where 1=1 and hr_employee_t.releive_date_actual!='null' and (hr_employee_t.employee_restatus ='0' OR hr_employee_t.employee_restatus IS NULL))v1");



    return DataTables::of($result)->make(true);
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

    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['urlmenu'] = $this->indexs();
    $logged_user = Session::get('emp_id');
    $this->data['logged_user'] = $logged_user;
    $reporting_details = DB::table('hr_employee_t')->where('employee_id', $logged_user)->get();
    $reporting_id = $reporting_details[0]->reporting_manager;
    $this->data['reporting_id'] = $reporting_id;
    /**** Gross Salary ***/
    $employee_salary = DB::table('hr_schedule_interview')
      ->leftjoin('hr_emp_offer_letter', 'hr_emp_offer_letter.employee_id', '=', 'hr_schedule_interview.name_of_the_candidate')
      ->where('hr_schedule_interview.employee_id', $logged_user)->get();
    $gross_salary = 0;
    if (count($employee_salary) > 0) {
      $gross_salary = $employee_salary[0]->gross_pay;
    }
    $this->data['gross_salary'] = $gross_salary;
    /** advance got or not **/
    $employee = DB::table('hr_employee_advance_t')->where('employee_id', $logged_user)->orderBy('hr_employee_advance_t.advance_id', 'desc')->get();
    $paid_stauts = 2;
    $remaining_amount = 0;
    if (count($employee) > 0) {
      $paid_stauts = $employee[0]->paid_status;
      $remaining_amount = $employee[0]->remaining_amount;
    }
    $this->data['paid_status'] = $paid_stauts;
    $this->data['remaining_amount'] = $remaining_amount;
    /*****/

    /*** Check Edit possible or not ***/
    $salary_details = DB::table('hr_employee_advance_t')->where('employee_id', $logged_user)->get();
    $approved_status = 0;
    if (count($salary_details) > 0) {
      $approved_status = $salary_details[0]->approved_status;
    }
    $this->data['approved_status'] = $approved_status;

    $this->data['urlmenu'] = $this->indexs();

    return view('advance.form', $this->data);
  }

  public function employeeadvancegriddata(Request $request)
  {


    $logged_user = Session::get('emp_id');
    $comp = Session::get('companyid');
    $wh = " and hr_employee_advance_t.employee_id ='$logged_user' and hr_employee_advance_t.company_id='$comp'";
    $SQL = "SELECT 	hr_employee_advance_t.advance_id, hr_employee_advance_t.employee_id, hr_employee_advance_t.effective_date, hr_employee_advance_t.approved_status, hr_employee_advance_t.advance_date,hr_employee_advance_t.mode,hr_employee_advance_t.ref_no, hr_employee_advance_t.emi, hr_employee_advance_t.amount, concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name )as first_name, hr_employee_advance_t.advance_from,hr_employee_advance_t.cheque_number, hr_employee_advance_t.paid_amount, hr_employee_advance_t.remaining_amount,hr_employee_advance_t.forwarded_id as forwarded_id1,hr_employee_advance_t.advance_reason,hr_employee_advance_t.approved_status as approved_id, hr_employee.first_name as forwarded_id, (case   when hr_employee_advance_t.approved_status = 0 then 'Initiated' when hr_employee_advance_t.approved_status = 1 then 'Approved'   when hr_employee_advance_t.approved_status = 2 then 'Rejected'   end) as approved_status_name from hr_employee_advance_t LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_advance_t.employee_id   LEFT JOIN hr_employee_t as hr_employee ON hr_employee.employee_id =hr_employee_advance_t.forwarded_id  where 1=1 $wh order by advance_id desc";

    $data = \DB::select($SQL);


    return DataTables::of($data)->make(true);

  }

  /*** save function start */
  public function store(Request $request)
  {
    $employee_advance = new Employeeadvance();
    $edit_id = $request->input('edit_id');
    if ($edit_id == '') {
      $employee_advance->employee_id = $request->input('employee_id');
      $employee_advance->advance_date = $request->input('advance_date');
      $employee_advance->effective_date = $request->input('effective_date');
      $employee_advance->mode = $request->input('mode');
      $employee_advance->amount = $request->input('amount');
      $employee_advance->emi = $request->input('emi');
      $employee_advance->advance_reason = $request->input('advance_reason');
      $employee_advance->forwarded_id = $request->input('forwarded_id');
      $employee_advance->remaining_amount = $request->input('amount');
      $employee_advance->cheque_number = $request->input('cheque_number');
      $employee_advance->advance_from = $request->input('advance_from');
      $employee_advance->emi_amount = $request->input('emi_amount');
      $image = $request->file('loan_document');
      if ($image != "") {
        $name = $employee_advance->employee_id . "_" . rand(10, 100) . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/advanceupload');
        $image->move($destinationPath, $name);
        $employee_advance->loan_document = $name;
      }
      $employee_advance->save();
      $id = $employee_advance->advance_id;
      $table = $employee_advance->getTable();
      $column = $employee_advance->getKeyName();
      $this->hrmssaveinsert($table, $column, $id, 1);
      // auditlog
      $this->auditlog($id, "advancecreate", "create", $_POST, "hr_employee_advance_t");
      return 2;
    } else {
      $image = $request->file('loan_document');
      if ($image != "") {
        $name = $request->file('employee_id') . "_" . rand(10, 100) . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/advanceupload');
        $image->move($destinationPath, $name);
        $employee_advance->loan_document = $name;
      }
      $employee_advance = Employeeadvance::where('advance_id', $edit_id)->firstOrFail();
      dd($employee_advance);
      $input_data = $request->all();
      $employee_advance->fill($input_data)->save();
      $table = $employee_advance->getTable();
      $column = $employee_advance->getKeyName();
      $this->hrmssaveinsert($table, $column, $edit_id, 2);
      // auditlog
      $this->auditlog($edit_id, "advancecreate", "edit", $_POST, "hr_employee_advance_t");
      return 1;
    }
  }
  /** end **/

  /*** Approve Approve function start */
  public function approve(Employeeadvance $employeeadvance)
  {
    $this->data['pageMethod'] = \Request::route()->getName();

    return view('advance.table', $this->data);
  }



  public function approveData(Request $request)
  {


    $logged_user = Session::get('emp_id');
    $comp_id = Session::get('companyid');
    $wh = "and hr_employee_advance_t.forwarded_id ='$logged_user' and hr_employee_advance_t.company_id='$comp_id' and hr_employee_advance_t.approved_status=0 ";
    $SQL = "SELECT  hr_employee_advance_t.advance_id,   hr_employee_advance_t.employee_id, hr_employee_advance_t.advance_date, hr_employee_advance_t.effective_date,  hr_employee_advance_t.mode,  hr_employee_advance_t.ref_no, hr_employee_advance_t.emi, hr_employee_advance_t.amount, CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name, hr_employee_advance_t.paid_amount, hr_employee_advance_t.remaining_amount, hr_employee_advance_t.forwarded_id as forwarded_id1, hr_employee_advance_t.advance_reason,hr_employee.first_name as forwarded_id, hr_employee_advance_t.approved_status as approved_id,(case when hr_employee_advance_t.approved_status = 0 then 'Initiated' when hr_employee_advance_t.approved_status = 1 then 'Approved' when hr_employee_advance_t.approved_status = 2 then 'Rejected'  end) as approved_status  from hr_employee_advance_t LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_advance_t.employee_id  LEFT JOIN hr_employee_t as hr_employee ON hr_employee.employee_id =hr_employee_advance_t.forwarded_id  where 1=1 $wh ORDER BY hr_employee_advance_t.advance_id desc";

    $data = \DB::select($SQL);


    return DataTables::of($data)->make(true);

  }


  /*** Approve advance function start */
  public function advancededuction(Employeeadvance $employeeadvance)
  {
    $this->data['pageMethod'] = \Request::route()->getName();

    return view('advance.deducttable', $this->data);
  }


  public function advancedeductionData(Request $request)
  {


    $logged_user = Session::get('emp_id');
    $comp_id = Session::get('companyid');
    $wh = "and hr_employee_advance_t.forwarded_id ='$logged_user' and hr_employee_advance_t.company_id='$comp_id' and hr_employee_advance_t.approved_status=1 and hr_employee_advance_t.paid_status=0";

    $SQL = "SELECT  hr_employee_advance_t.advance_id, hr_employee_advance_t.employee_id, hr_employee_advance_t.advance_date,  hr_employee_advance_t.mode,  hr_employee_advance_t.ref_no, hr_employee_advance_t.emi, hr_employee_advance_t.amount, concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name, hr_employee_advance_t.paid_amount, hr_employee_advance_t.remaining_amount, hr_employee_advance_t.forwarded_id as forwarded_id1, hr_employee_advance_t.advance_reason,hr_employee.first_name as forwarded_id, hr_employee_advance_t.approved_status as approved_id,(case when hr_employee_advance_t.approved_status = 0 then 'Initiated' when hr_employee_advance_t.approved_status = 1 then 'Approved'  when hr_employee_advance_t.approved_status = 2 then 'Rejected'  end) as approved_status  from hr_employee_advance_t LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_advance_t.employee_id  LEFT JOIN hr_employee_t as hr_employee ON hr_employee.employee_id =hr_employee_advance_t.forwarded_id  where 1=1 $wh ORDER BY hr_employee_advance_t.advance_id desc";

    $data = \DB::select($SQL);


    return DataTables::of($data)->make(true);

  }


  /*** Approve status function start */
  public function getstatus()
  {
    $advance_id = Input::get('advance_id');
    /** check existing **/
    $check_existing = DB::table('hr_employee_advancedeductions_t')->where('ref_id', $advance_id)->get();
    if (count($check_existing) == 0)
      $result = 1;
    else
      $result = 2;
    /******/
    $approved_status = '';
    $check_existing = DB::table('hr_employee_advance_t')->where('advance_id', $advance_id)->get();
    if (count($check_existing) > 0) {
      $approved_status = $check_existing[0]->approved_status;
    }
    $return[0] = $result;
    $return[1] = $approved_status;
    return $return;
  }
  /*** Approve status function End */

  /*** Approve status changes function start */
  public function statuschanges()
  {

    $status = $_GET['status'];
    $advance_id = $_GET['id'];

    if ($status == '1') {
      $status_s = "Approved";
      $update_status = DB::update("UPDATE hr_employee_advance_t SET approved_status=$status WHERE advance_id=$advance_id");
      // auditlog
      $this->auditlog($advance_id, "status", "Update", $update_status, "hr_employee_advance_t");
    } else if ($status == '2') {
      $status_s = "Rejected";
      $update_status = DB::update("UPDATE hr_employee_advance_t SET approved_status=$status WHERE advance_id=$advance_id");
      // auditlog
      $this->auditlog($advance_id, "status", "Update", $update_status, "hr_employee_advance_t");
    } else if ($status == '3') {
      $status_s = "Deducted";
      $update_status = DB::update("UPDATE hr_employee_advance_t SET approved_status=$status WHERE advance_id=$advance_id");
      // auditlog
      $this->auditlog($advance_id, "status", "Update", $update_status, "hr_employee_advance_t");
    }
    $result[0] = 1;
    $result[1] = $status_s;
    return $result;
  }
  /*** Approve status changes function End */

  /*** Delete function start */
  public function destroy($id = null)
  {


    $column = array('ref_id');
    $table = array('hr_employee_advancedeductions_t');
    $j = 0;
    for ($i = 0; $i < count($table); $i++) {

      $query = DB::table($table[$i])->where($column[$i], $id)->get();
      if (count($query) > 0) {
        $j = 1;
        break;
      }
    }
    if ($j == 0) {
      $query1 = DB::table('hr_employee_advance_t')->where('advance_id', $id)->get();
      if (count($query1) > 0) {
        $approved_status = $query1[0]->approved_status;
        if ($approved_status == 0)
          $j = 0;
        else
          $j = 1;

      }
    }

    if ($j == 0) {
      $query = DB::table('hr_employee_advance_t')->where('advance_id', $id)->delete();
      // auditlog
      $this->auditlog($id, "Advancecreate", "delete", $query, "hr_employee_advance_t");
      return 2;
    }
    if ($j == 1) {

      return 1;
    }

  }
  /*** Delete function End */

  /*** Approve form  edit start */
  public function approveform($id = null)
  {
    $check_existing = DB::table('hr_employee_advance_t')->where('advance_id', $id)->get();
    $this->data['advance_id'] = $id;
    $this->data['employee_id'] = $check_existing[0]->employee_id;
    $this->data['advance_from'] = $check_existing[0]->advance_from;
    $this->data['advance_date'] = $check_existing[0]->advance_date;
    $this->data['effective_date'] = $check_existing[0]->effective_date;
    $this->data['mode'] = $check_existing[0]->mode;
    $this->data['amount'] = $check_existing[0]->amount;
    $this->data['emi'] = $check_existing[0]->emi;
    $this->data['emi_amount'] = $check_existing[0]->emi_amount;
    $this->data['advance_reason'] = $check_existing[0]->advance_reason;
    $this->data['forwarded_id'] = $check_existing[0]->forwarded_id;
    $this->data['cheque_number'] = $check_existing[0]->cheque_number;
    $this->data['advance_deduct_status'] = \Request::route()->getName();
    if (\Request::route()->getName() == "advancededuct") {
      $this->data['paid_amount'] = $check_existing[0]->paid_amount;
      $this->data['remaining_amount'] = $check_existing[0]->remaining_amount;
      $this->data['date'] = date('Y-m-d');
    } else {
      $this->data['paid_amount'] = '';
      $this->data['remaining_amount'] = '';
    }

    return view('advance.approveform', $this->data);

  }
  /*** Approve form  edit end */

  /*** Approve form  Create form page open start */
  public function create()
  {
    $this->data['pageMethod'] = \Request::route()->getName();

    return view('advance.advancededuction', $this->data);
  }
  /*** Approve form  Create form page open end */

  /*** Deduction Details start */
  public function deductiondetails()
  {
    $employee_id = Input::get('employee_id');

    $result = DB::table('hr_employee_advance_t')->where('employee_id', $employee_id)->where('paid_status', 0)->get();

    if (count($result) > 0) {
      if ($result[0]->approved_status == 0) {
        $amount = 0;
        $emi = 0;
        $paid_amount = 0;
        $remaining_amount = 0;
        $emi_amount = 0;
        $emi = 0;
        $result = 0;
        $status = "Initiated";
        $advance_id = "";
      } else if ($result[0]->approved_status == 2) {
        $amount = 0;
        $emi = 0;
        $paid_amount = 0;
        $remaining_amount = 0;
        $emi_amount = 0;
        $emi = 0;
        $result = 0;
        $status = "Rejected";
        $advance_id = "";
      } else if ($result[0]->approved_status == 1) {

        $amount = $result[0]->amount;
        $emi = $result[0]->emi;
        $paid_amount = $result[0]->paid_amount;
        $remaining_amount = $result[0]->remaining_amount;
        $advance_id = $result[0]->advance_id;
        $emi_amount = $amount / $emi;
        $emi = $result[0]->emi;
        $result = 1;
        $status = "Approved";

      }
    } else {
      $amount = 0;
      $emi = 0;
      $paid_amount = 0;
      $remaining_amount = 0;
      $emi_amount = 0;
      $emi = 0;
      $result = 2;
      $status = "No Entries";
      $advance_id = "";
    }
    // dd(array('result'=>$result,'amount'=>$amount,'emi'=>$emi,'paid_amount'=>$paid_amount,'remaining_amount'=>$remaining_amount,'emi_amount'=>$emi_amount,'status'=>$status,'advance_id'=>$advance_id));
    return array('result' => $result, 'amount' => $amount, 'emi' => $emi, 'paid_amount' => $paid_amount, 'remaining_amount' => $remaining_amount, 'emi_amount' => $emi_amount, 'status' => $status, 'advance_id' => $advance_id);

  }
  /*** Deduction Details end */

  /*** Deduction Save start */
  public function deductionsave(Request $request)
  {
    //  dd($request);
    $advance_id = $request->input('edit_id');
    $employee_id = $request->input('employee_id');
    $amount_pay = $request->input('amount_pay');
    $till_remaining_amount = $request->input('till_remaining_amount');

    $result = DB::table('hr_employee_advance_t')->where('advance_id', $advance_id)->get();
    if (count($result) > 0) {
      $paying_amount = $till_remaining_amount - $amount_pay;

    }

    if ($amount_pay == $till_remaining_amount) {
      $amount_pay = $request->input('amount_pay');
      $deduction_date = $request->input('deduction_date');
      $total_amount = $request->input('amount');
      $till_paid_amount = $request->input('till_paid_amount');
      $till_remaining_amount = $till_remaining_amount - $amount_pay;
      $paid_status = 1;
    } else {
      $amount_pay = $request->input('amount_pay');
      $deduction_date = $request->input('deduction_date');
      $total_amount = $request->input('amount');
      $till_paid_amount = $request->input('till_paid_amount');
      $till_remaining_amount = $till_remaining_amount - $amount_pay;
      $paid_status = 0;
    }
    $till_paid_amount = $till_paid_amount + $amount_pay;
    $created_by = \Session::get('id');
    $updated_by = \Session::get('id');
    $created_at = date('Y-m-d');
    $updated_at = date('Y-m-d');
    $location_id = \Session::get('location');
    $company_id = \Session::get('companyid');
    $organization_id = \Session::get('organization');

    $take_paid = DB::table('hr_employee_advance_t')->where('advance_id', $advance_id)->get();
    $total_payees = $take_paid[0]->paid_amount + $amount_pay;

    $advance = DB::table('hr_employee_advance_t')->where('advance_id', $advance_id)
      ->update(array('remaining_amount' => $till_remaining_amount, 'paid_amount' => $total_payees, 'paid_status' => $paid_status));
    // auditlog
    $this->auditlog($advance_id, "advancecreate", "Advance", $advance, "hr_employee_advance_t");

    $advance_deductions = DB::table('hr_employee_advancedeductions_t')->insert(['ref_id' => $advance_id, 'employee_id' => $employee_id, 'deduction_date' => $deduction_date, 'amount_pay' => $amount_pay, 'till_paid_amount' => $till_paid_amount, 'till_remaining_amount' => $till_remaining_amount, 'paid_status' => $paid_status, 'created_at' => $created_at, 'updated_at' => $updated_at, 'location_id' => $location_id, 'company_id' => $company_id, 'organization_id' => $organization_id, 'total_amount' => $total_amount]);
    // auditlog
    $this->auditlog($advance_id, "advancecreate", "Advance Deduction", $advance_deductions, "hr_employee_advance_t");

    if ($till_remaining_amount == 0) {

      $advance = DB::table('hr_employee_advance_t')->where('advance_id', $advance_id)
        ->update(array('paid_status' => $paid_status));
      // auditlog
      $this->auditlog($advance_id, "Advance Deduction", "Advance status", $advance, "hr_employee_advance_t");

      $advance_deductions = DB::table('hr_employee_advancedeductions_t')->where('ref_id', $advance_id)
        ->update(array('paid_status' => $paid_status));
      // auditlog
      $this->auditlog($advance_id, "Advance Deduction", "Advance Deduction status", $advance_deductions, "hr_employee_advance_t");
    }
    return 1;

  }


  public function addvancereport(Request $request)
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

    return view('advance.advancereport', $this->data);
  }


  public function approverptData(Request $request)
  {


    $company_id = Session::get('companyid');
    $wh = '';
    $log_id = Session::get('emp_id');
    $gname = Session::get('groupname');

    $emp_data = DB::table("hr_employee_t")->where('employee_id', $log_id)->get();
    $dept = json_decode($emp_data[0]->department);

    if (in_array(28, $dept) || $gname == '1') {
      $wh = "and hr_employee_advance_t.company_id='$company_id'";
      // $wh.='';
    } else {
      if ($log_id != "1") {
        // $wh.="and hr_employee_advance_t.employee_id=$log_id"; 
        $wh = "and (hr_employee_advance_t.employee_id=' $log_id' or hr_employee_advance_t.forwarded_id='$log_id') ";
      }
    }

    // if($log_id!=1)

    $query_result = \DB::select("SELECT hr_employee_t.date_of_joining,hr_employee_t.department,CONCAT(report.employee_number,'-', report.first_name) as report_name,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as full_name,hr_employee_advance_t.*,CASE
            WHEN hr_employee_advance_t.approved_status = 0 THEN 'INTIATED'
            WHEN hr_employee_advance_t.approved_status = 1 THEN 'APPROVED' END AS app_status
            ,
            CASE WHEN hr_employee_advance_t.advance_from = 1 THEN 'Loan (EMI)'
            WHEN hr_employee_advance_t.advance_from = 2 THEN 'Advance (Salary)' END AS adv_type,
            CASE
            WHEN hr_employee_advance_t.paid_status = 0 THEN 'NOT PAID'
            WHEN hr_employee_advance_t.paid_status = 1 THEN 'PAID' END AS paidnew_status from hr_employee_advance_t
            left join hr_employee_t on hr_employee_t.employee_id = hr_employee_advance_t.employee_id
            left join hr_employee_t as report on report.employee_id = hr_employee_advance_t.forwarded_id
            where 1=1 $wh ");


    if (count($query_result) > 0) {
      foreach ($query_result as $k => $v) {
        if ($v->department != '' && $v->department != null) {
          $array = json_decode($v->department);
          $query = DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
          $deptnames = json_decode(json_encode($query), true);
          $query_result[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
        } else {
          $query_result[$k]->department = "";
        }
      }
    }

    return DataTables::of($query_result)->make(true);

  }



  /*** Advance Deduction Month Report start */
  public function advancemonthreport(Request $request)
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

    return view('advance.advancemonthreport', $this->data);
  }


  public function advancemonthreportData(Request $request)
  {

    $company_id = Session::get('companyid');
    $wh = '';
    $log_id = Session::get('emp_id');

    $emp_data = DB::table("hr_employee_t")->where('employee_id', $log_id)->get();
    $dept = json_decode($emp_data[0]->department);

    if (in_array(28, $dept)) {
      $wh = "and hr_employee_advance_t.company_id='$company_id'";
      // $wh.='';
    } else {
      if ($log_id != "1") {

        $wh = "and (hr_employee_advance_t.employee_id=' $log_id' or hr_employee_advance_t.forwarded_id='$log_id') ";
      }
    }


    $query_result = \DB::select("SELECT hr_employee_t.date_of_joining,hr_employee_t.department,CONCAT(report.employee_number,'-', report.first_name) as report_name,CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as full_name,hr_employee_advance_t.advance_date,hr_employee_advance_t.effective_date,hr_employee_advancedeductions_t.*,CASE
            WHEN hr_employee_advance_t.paid_status = 0 THEN 'NOT PAID'
            WHEN hr_employee_advance_t.paid_status = 1 THEN 'PAID' END AS paidnew_status from hr_employee_advancedeductions_t
            left join hr_employee_t on hr_employee_t.employee_id = hr_employee_advancedeductions_t.employee_id
            LEFT JOIN hr_employee_advance_t ON hr_employee_advance_t.employee_id = hr_employee_advancedeductions_t.employee_id
            left join hr_employee_t as report on report.employee_id = hr_employee_advance_t.forwarded_id
            where 1=1 $wh ");


    if (count($query_result) > 0) {
      foreach ($query_result as $k => $v) {
        if ($v->department != '' && $v->department != null) {
          $array = json_decode($v->department);
          $query = DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
          $deptnames = json_decode(json_encode($query), true);
          $query_result[$k]->department = implode(' , ', array_column($deptnames, 'sub_department_name'));
        } else {
          $query_result[$k]->department = "";
        }
      }
    }


    return DataTables::of($query_result)->make(true);

  }



  /*** Advance Deduction Report status start */
  public function reporting_name($reporting_id, $status)
  {

    $result = DB::table('hr_employee_t')->where('employee_id', $reporting_id)->get();
    if ($status == 0)
      $pay_status = "Pending";
    else
      $pay_status = "Finished";
    return array($result[0]->first_name . '-' . $result[0]->last_name, $pay_status);
  }
  /*** Advance Deduction Report status end */
  public function employeestatusupdate($emp_status, $advance_id, $emp_number)
  {
    $update_status = DB::update("UPDATE hr_employee_t SET employee_restatus=$emp_status WHERE employee_id=$advance_id");
    $this->auditlog($advance_id, "employee_restatus", "Update", $update_status, "hr_employee_t");
    return 1;
  }

}
