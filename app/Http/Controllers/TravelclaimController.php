<?php

namespace App\Http\Controllers;

use App\travelclaim;
use Illuminate\Http\Request;
use Session;
use DB;
use Illuminate\Support\Facades\Input;
use yajra\datatables\datatables;

class TravelclaimController extends Controller
{

  public function __construct()
  {
    $this->data = array();
    $this->data['pageModule'] = \Request::route()->getName();
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['urlmenu'] = $this->indexs();
  }

  /** Jqgrid Travel Claim load data Start **/
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

    $logged_user = Session::get('emp_id');
    $this->data['logged_user'] = $logged_user;
    $logged_user = Session::get('emp_id');

    return view('travelclaim.table', $this->data);
  }



  /** Jqgrid Travel Claim Report load data Start **/
  public function travelreport_index(Request $request)
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

    $company_id = Session::get('companyid');
    $logged_user = Session::get('emp_id');
    $wh = '';
    /*if($logged_user==1)
    $wh .= " and hr_employee_travel_claim_t.company_id=' $company_id' ";
else
    $wh .= " and hr_employee_travel_claim_t.employee_id ='$logged_user' and hr_employee_travel_claim_t.company_id=' $company_id' ";*/

    $emp_data = DB::table("hr_employee_t")->where('employee_id', $logged_user)->get();
    $dept = json_decode($emp_data[0]->department);
    /* check if the user's department is payroll */
    if (in_array(28, $dept)) {
      $wh .= " and hr_employee_travel_claim_t.company_id='$company_id' ";
    } else {
      if ($logged_user != "1") {
        $wh .= "and hr_employee_travel_claim_t.employee_id=$logged_user and hr_employee_travel_claim_t.company_id=' $company_id' ";
      }
    }

    $query_result = \DB::select("SELECT 	
        hr_employee_travel_claim_t.travel_claim_id,
        hr_employee_travel_claim_t.employee_id,
        hr_employee_travel_claim_t.claim_title,
        hr_employee_travel_claim_t.description,
        hr_employee_travel_claim_t.travel_purpose,
        hr_employee_travel_claim_t.travel_date,
        hr_employee_travel_claim_t.travel_to_date,
        hr_employee_travel_claim_t.travel_mode,
        hr_employee_travel_claim_t.from_place,
        hr_employee_travel_claim_t.to_place,
        hr_employee_travel_claim_t.distance,
        hr_employee_travel_claim_t.rate,
        hr_employee_travel_claim_t.approve_amount, 
        hr_employee_t.department,
        CONCAT(hr_emp.employee_number,'-', hr_emp.first_name) as reporting_name,
        CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_name,
        (case 
        when hr_employee_travel_claim_t.status = 0 then 'Initiated'
        when hr_employee_travel_claim_t.status = 1 then 'Approved'
        when hr_employee_travel_claim_t.status = 2 then 'Rejected'
        end) as  approved_status  from hr_employee_travel_claim_t  LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_travel_claim_t.employee_id  LEFT JOIN hr_employee_t as hr_emp ON hr_emp.employee_id =hr_employee_travel_claim_t.forwarded_id   where 1=1 $wh order by travel_claim_id desc");
    $logged_user = Session::get('emp_id');
    $this->data['logged_user'] = $logged_user;

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

    $this->data['result'] = json_encode($query_result);

    return view('travelclaim.travelreport', $this->data);

  }



  public function traveljournalcreate($id = null)
  {
    $data = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $id)->get();

    $this->data['row'] = (object) array();
    $this->data['row']->journal_entry_id = "";
    $this->data['row']->journal_name = "TRAVEL";
    $this->data['row']->journal_date = date('Y-m-d');
    $this->data['row']->journal_type = "TRAVEL";
    $this->data['row']->journal_category = "OTHERS";
    $this->data['row']->journal_reference = $id;
    $this->data['row']->journal_status = "";
    $this->data['aprvidenty'] = "";
    $this->data['id'] = '';
    $this->data['linedata'] = array();
    $this->data['account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');

    return view('journalentry.form', $this->data);
  }
  public function travelexpensecreate($id = null)
  {


    $this->data['pageModule'] = 'expenses';
    $this->data['pageUrl'] = url('expenses');
    $data = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $id)->get();

    $this->data['row'] = (object) array();
    $this->data['row']->expense_id = "";
    $this->data['row']->expense_date = date('Y-m-d');
    $this->data['row']->expense_type = "";
    $this->data['row']->expense_amount = $data[0]->bill_amount;
    $this->data['row']->expense_status = "INITIATED";
    $this->data['row']->source = "TRAVEL";
    $this->data['row']->gst_code_id = "";
    $this->data['row']->concatenated_segments = "";
    $this->data['row']->tds_applicable = "";
    $this->data['row']->tds_amount = "";
    $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', '');
    $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
    $this->data['paid_through_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
    $this->data['row']->reverse_charge = "";
    $this->data['gst_treatment'] = $this->jcustomselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code', '', 'AND lookup_type="GST_TREATMENT"');
    $this->data['gst_code_id'] = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="HSN"');
    $this->data['tax_group_id'] = $this->jCombo('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
    $this->data['row']->gst_no = "";
    $this->data['row']->invoice = "";
    $this->data['row']->remarks = "";
    $this->data['row']->reference_id = $id;
    $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', '');
    $this->data['tds_prcnt'] = $this->jCombo('f_tds_slab_t', 'tds_percentage', 'tds_percentage', '');
    $this->data['employee_id'] = $this->jCombologin('hr_employee_t', 'employee_id', 'first_name|last_name', '');
    $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
    $this->data['aprvidenty'] = "";
    $this->data['linedata'] = array();
    return view('expenses.form', $this->data);
  }

  /** Jqgrid Travel Claim Report save load data Start **/
  public function create(travelclaim $travelclaim, $id = null)
  {
    $logged_user = Session::get('emp_id');
    $this->data['logged_user'] = $logged_user;

    if ($id == 0) {
      $logged_user = Session::get('emp_id');
      $result = DB::table('hr_employee_t')->where('employee_id', $logged_user)->get();
      $forwarded_id = $result[0]->reporting_manager;
      $logged_user = Session::get('emp_id');
      $this->data['edit_id'] = 0;
      $this->data['claim_title'] = '';
      $this->data['travel_date'] = '';
      $this->data['date1'] = '';
      $this->data['date2'] = '';
      $this->data['travel_purpose'] = '';
      $this->data['travel_mode'] = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', '', "and lookup_type='travelmode'");
      $this->data['description'] = '';
      $this->data['from_place'] = '';
      $this->data['to_place'] = '';
      $this->data['distance'] = '';
      $this->data['reason'] = '';
      $this->data['amount'] = '';
      $this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', '  employee_number | first_name', $logged_user);
      $this->data['forwarded_id'] = $this->jCombo('hr_employee_t', 'employee_id', ' employee_number |first_name', $forwarded_id);
      $this->data['bill_amount'] = '';
      return view('travelclaim.form', $this->data);
    } else {
      $result = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $id)->get();
      //dd($result);
      $forwarded_id = $result[0]->forwarded_id;
      $this->data['edit_id'] = $id;
      $this->data['claim_title'] = $result[0]->claim_title;
      $this->data['travel_date'] = date('d-m-Y', strtotime($result[0]->travel_date));
      $this->data['travel_to_date'] = date('d-m-Y', strtotime($result[0]->travel_date));
      $this->data['date1'] = date('d-m-Y H:i:s', strtotime($result[0]->date1));
      $this->data['date2'] = date('d-m-Y H:i:s', strtotime($result[0]->date2));
      $this->data['travel_purpose'] = $result[0]->travel_purpose;
      $this->data['travel_mode'] = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $result[0]->travel_mode, "and lookup_type='travelmode'");
      $this->data['description'] = $result[0]->description;
      $this->data['bill_copy'] = $result[0]->bill_copy;

      $this->data['from_place'] = $result[0]->from_place;
      $this->data['to_place'] = $result[0]->to_place;
      $this->data['distance'] = $result[0]->distance;
      $this->data['reason'] = $result[0]->bill_reason;
      $this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name | employee_number', $result[0]->employee_id);
      $this->data['forwarded_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name | employee_number', $forwarded_id);
      $this->data['bill_amount'] = $result[0]->bill_amount;

      return view('travelclaim.form', $this->data);

    }

  }
  /** Jqgrid Travel Claim Report save load data End **/


  /** Jqgrid Travel Claim Report status load data START **/
  public function approveindex(Request $request)
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

    return view('travelclaim.approveclaim', $this->data);
  }
  /** Jqgrid Travel Claim Report status load data END **/


  public function store(Request $request)
  {
    //dd($request);
    $edit_id = $request->input('edit_id');
    if ($edit_id == 0) {

      $travelclaim = new travelclaim();
      $travelclaim->employee_id = $request->input('employee_id');
      $travelclaim->claim_title = $request->input('claim_title');
      $travelclaim->travel_date = date('Y-m-d H:i:s', strtotime($request->input('travel_date')));
      $travelclaim->travel_purpose = $request->input('travel_purpose');
      $travelclaim->travel_mode = $request->input('travel_mode');
      $travelclaim->description = $request->input('description');
      $travelclaim->from_place = $request->input('from_place');
      $travelclaim->to_place = $request->input('to_place');
      $travelclaim->distance = $request->input('distance');
      $travelclaim->bill_amount = $request->input('bill_amount');
      $travelclaim->forwarded_id = $request->input('forwarded_id');
      $travelclaim->date1 = date('Y-m-d H:i:s', strtotime($request->input('date1')));
      $travelclaim->date2 = date('Y-m-d H:i:s', strtotime($request->input('date2')));

      for ($i = 0; $i < count($request->input('reason')); $i++) {
        $reason = $request->input('reason')[$i];
        $amount = $request->input('amount')[$i];

        $result[] = array($reason, $amount);
      }
      $image = $request->file('bill_copy');
      if ($image != "") {
        $name = rand(10, 100) . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path('/images/claimupload');
        $image->move($destinationPath, $name);
        $travelclaim->bill_copy = $name;
      } else {
        $image = '';
      }
      $travelclaim->bill_reason = json_encode($result);
      $travelclaim->approve_amount = 0;

      $travelclaim->save();
      $id = $travelclaim->travel_claim_id;

      $table = $travelclaim->getTable();
      $column = $travelclaim->getKeyName();
      $this->hrmssaveinsert($table, $column, $id, 1);
      // auditlog
      $this->auditlog($edit_id, "Travel Claim", "create", $_POST, "hr_employee_travel_claim_t");
      return 1;
    } else {
      $image_file = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $edit_id)->get();
      $old_image = $image_file[0]->bill_copy;
      $travelclaim = new travelclaim();
      $employee_id = $request->input('employee_id');
      $claim_title = $request->input('claim_title');
      $travel_date = date('d-m-Y', strtotime($request->input('travel_date')));
      $date1 = date('d-m-Y H:i:s', strtotime($request->input('date1')));
      $date2 = date('d-m-Y H:i:s', strtotime($request->input('date2')));
      $travel_purpose = $request->input('travel_purpose');
      $travel_mode = $request->input('travel_mode');
      $description = $request->input('description');
      $from_place = $request->input('from_place');
      $to_place = $request->input('to_place');
      $distance = $request->input('distance');
      $bill_amount = $request->input('bill_amount');
      $forwarded_id = $request->input('forwarded_id');
      for ($i = 0; $i < count($request->input('reason')); $i++) {
        $reason = $request->input('reason')[$i];
        $amount = $request->input('amount')[$i];
        $result[] = array($reason, $amount);
      }
      $bill_reason = json_encode($result);
      $image = $request->file('bill_copy');

      if ($image != "") {
        $name = rand(10, 100) . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/images/claimupload');
        $image->move($destinationPath, $name);
        $image = $name;
      } else {
        $image = $old_image;
      }

      $bill_reason = json_encode($result);
      $approve_amount = 0;

      $update_travel_calim = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $edit_id)
        ->update(array(
          'employee_id' => $employee_id,
          'claim_title' => $claim_title,
          'travel_date' => $travel_date,
          'travel_purpose' => $travel_purpose,
          'travel_mode' => $travel_mode
          ,
          'description' => $description,
          'from_place' => $from_place,
          'to_place' => $to_place,
          'distance' => $distance,
          'forwarded_id' => $forwarded_id,
          'bill_reason' => $bill_reason,
          'bill_copy' => $image,
          'bill_amount' => $bill_amount,
          'date1' => $date1,
          'date2' => $date2
        ));
      $table = $travelclaim->getTable();
      $column = $travelclaim->getKeyName();
      $this->hrmssaveinsert($table, $column, $edit_id, 2);
      // auditlog
      $this->auditlog($edit_id, "Travel Claim", "Update", $_POST, "hr_employee_travel_claim_t");
      return 2;
    }
  }


  public function show(travelclaim $travelclaim)
  {
    //
  }

  /** Jqgrid Travel Claim Report Approve load data START **/
  public function approvetravelclaim(Request $request, $id = null)
  {
    $claim_id = $id;
    $result = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $claim_id)->get();
    $forwarded_id = $result[0]->forwarded_id;
    $this->data['edit_id'] = $id;
    $this->data['claim_title'] = $result[0]->claim_title;
    $this->data['travel_date'] = $result[0]->travel_date;
    $this->data['travel_purpose'] = $result[0]->travel_purpose;
    $this->data['travel_mode'] = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $result[0]->travel_mode, "and lookup_type='travelmode'");
    $this->data['description'] = $result[0]->description;
    $this->data['bill_copy'] = $result[0]->bill_copy;
    $this->data['from_place'] = $result[0]->from_place;
    $this->data['to_place'] = $result[0]->to_place;
    $this->data['distance'] = $result[0]->distance;
    $this->data['reason'] = $result[0]->bill_reason;
    $this->data['date1'] = $result[0]->date1;
    $this->data['date2'] = $result[0]->date2;

    $this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name | employee_number', $result[0]->employee_id);
    $this->data['forwarded_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name | employee_number', $forwarded_id);
    $this->data['bill_amount'] = $result[0]->bill_amount;

    return view('travelclaim.approveform', $this->data);

  }

  public function claimapprove(travelclaim $travelclaim, Request $request)
  {
    $edit_id = $request->input('edit_id');
    $approve_amount = $request->input('approve_amount');
    $approve_date = date('Y-m-d');
    $approve_status = $request->input('status');

    $update_result = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $edit_id)->update(array('approve_amount' => $approve_amount, 'status' => $approve_status, 'approve_date' => $approve_date));
    return $approve_status;
  }


  /** Jqgrid Travel Claim table load data START **/
  public function employeetravelclaimgriddata()
  {

    $logged_user = Session::get('emp_id');

    $wh = "and hr_employee_travel_claim_t.employee_id ='$logged_user'";

    $SQL = "SELECT 	
                    hr_employee_travel_claim_t.travel_claim_id,
                    hr_employee_travel_claim_t.employee_id,
                    hr_employee_travel_claim_t.claim_title,
                    hr_employee_travel_claim_t.description,
                    hr_employee_travel_claim_t.travel_purpose,
                    hr_employee_travel_claim_t.travel_date,
                    hr_employee_travel_claim_t.travel_to_date,
                    hr_employee_travel_claim_t.travel_mode,
                    hr_employee_travel_claim_t.from_place,
                    hr_employee_travel_claim_t.to_place,
                    hr_employee_travel_claim_t.distance,
                    hr_employee_travel_claim_t.rate,
                    hr_employee_travel_claim_t.bill_amount,
                    hr_employee_travel_claim_t.bill_reason,
                    hr_employee_travel_claim_t.approve_amount,                    
		    hr_emp.first_name as reporting_name,
		    hr_emp_hr.first_name as approve_by_hr,
                    hr_employee_travel_claim_t.approve_by_hr_status,
					
                    
                    (case 
                        when hr_employee_travel_claim_t.approve_by_hr_status = 0 then 'Initiated'
                        when hr_employee_travel_claim_t.approve_by_hr_status = 1 then 'Approved'
                        when hr_employee_travel_claim_t.approve_by_hr_status = 2 then 'Rejected'
                    end) as approved_status_hr,
                    
                    hr_employee_travel_claim_t.status,
                    (case 
                        when hr_employee_travel_claim_t.status = 0 then 'Initiated'
                        when hr_employee_travel_claim_t.status = 1 then 'Approved'
                        when hr_employee_travel_claim_t.status = 2 then 'Rejected'
                    end) as  approved_status
                    
                    from hr_employee_travel_claim_t
                LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_travel_claim_t.employee_id 
                LEFT JOIN hr_employee_t as hr_emp ON hr_emp.employee_id =hr_employee_travel_claim_t.forwarded_id 
                LEFT JOIN hr_employee_t as hr_emp_hr ON hr_emp_hr.employee_id =hr_employee_travel_claim_t.approve_by_hr 
                LEFT JOIN hr_employee_t as hr_employee ON hr_employee.employee_id =hr_employee_travel_claim_t.forwarded_id 
                where 1=1 $wh ";

    $result = \DB::select($SQL);

    return DataTables::of($result)->make(true);
  }
  /** Jqgrid Travel Claim table load data End **/


  /** Jqgrid Travel Claim Approve table load data START **/
  public function employeetravelclaimapprovegriddata()
  {
    $logged_user = Session::get('emp_id');
    $wh = '';
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];

    $wh .= "and hr_employee_travel_claim_t.forwarded_id ='$logged_user' ";
    if (!$sidx)
      $sidx = 1;



    $result = \DB::select("SELECT COUNT(travel_claim_id) AS count FROM hr_employee_travel_claim_t as hr_employee_travel_claim_t where 1=1 $wh");
    $count = $result[0]->count;
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


    $SQL = "SELECT 	
                    hr_employee_travel_claim_t.travel_claim_id,
                    hr_employee_travel_claim_t.employee_id,
                    hr_employee_travel_claim_t.claim_title,
                    hr_employee_travel_claim_t.description,
                    hr_employee_travel_claim_t.travel_purpose,
                    hr_employee_travel_claim_t.travel_date,
                    hr_employee_travel_claim_t.travel_to_date,
                    hr_employee_travel_claim_t.travel_mode,
                    hr_employee_travel_claim_t.from_place,
                    hr_employee_travel_claim_t.to_place,
                    hr_employee_travel_claim_t.distance,
                    hr_employee_travel_claim_t.rate,
                    hr_employee_travel_claim_t.bill_amount,
                    hr_employee_travel_claim_t.bill_reason,
                    hr_employee_travel_claim_t.approve_amount,
                    hr_employee_travel_claim_t.status,
                    hr_employee_travel_claim_t.approve_by_hr,
                    hr_employee_travel_claim_t.approve_by_hr_status,
                    
                    (case 
                        when hr_employee_travel_claim_t.approve_by_hr_status = 0 then 'Initiated'
                        when hr_employee_travel_claim_t.approve_by_hr_status = 1 then 'Approved'
                        when hr_employee_travel_claim_t.approve_by_hr_status = 2 then 'Rejected'
                    end) as approved_status_hr,
                    
                    hr_employee_travel_claim_t.approve_by_hr,
                    (case 
                        when hr_employee_travel_claim_t.status = 0 then 'Initiated'
                        when hr_employee_travel_claim_t.status = 1 then 'Approved'
                        when hr_employee_travel_claim_t.status = 2 then 'Rejected'
                    end) as approved_status
                    
                    from hr_employee_travel_claim_t
                LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_travel_claim_t.employee_id 
                LEFT JOIN hr_employee_t as hr_employee ON hr_employee.employee_id =hr_employee_travel_claim_t.forwarded_id 
                where 1=1 $wh ORDER BY $sidx $sord LIMIT $start,$limit";


    $result = \DB::select($SQL);
    $responce->rows[] = '';
    $responce->rows = $result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;



    echo json_encode($responce);
  }
  /** Jqgrid Travel Claim Approve table load data End **/

  /** Jqgrid Travel Claim Delete table load data START **/
  public function destroy(Request $request)
  {
    $delete_id = Input::get('delete_id');
    $result = travelclaim::destroy($delete_id);
    // auditlog
    $this->auditlog($delete_id, "travelclaim", "delete", $result, "hr_employee_travel_claim_t");

    return 1;
  }

  public function traveldelete(Request $request, $id = null)
  {



    $column = array('travel_claim_id');
    $table = array('hr_employee_travel_claim_t');

    for ($i = 0; $i < count($table); $i++) {
      $j = 0;
      $query = DB::table($table[$i])->where($column[$i], $id)->where('status', 1)->orWhere('travel_claim_id', 1)->get();
      // auditlog
      $this->auditlog($id, "travelclaim", "delete", $query, "hr_employee_travel_claim_t");
      if (count($query) > 0) {
        $j = 1;
        break;
      }
    }

    if ($j == 0) {
      $query = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $id)->delete();
      // auditlog
      $this->auditlog($id, "travelclaim", "delete", $query, "hr_employee_travel_claim_t");
    }

    if ($j == 1)
      return 1;
    else if ($j == 0)
      return 2;
    else if ($j == 3)
      return 3;


    return 1;

  }

  public function travelapproveclaimgrid(Request $request)
  {

    $compy = \Session::get('companyid');
    $logged_user = \Session::get('emp_id');
    $status = $request->status ?? null;
    $wh = "";
    if ($status == 1) {
      $wh .= " and v1.status='PAYMENT' ";
      $op = "=";
      $status_val = "'PAYMENT'";
    } elseif ($status == 2) {
      $wh .= " and v1.status=1 ";
      $op = "=";
      $status_val = 1;
    } else {
      $wh .= " and v1.status=0 ";
      $op = "=";
      $status_val = 0;
    }
    $wh .= "and v1.forwarded_id ='$logged_user' and v1.company_id='$compy'";

    $SQL = "select * from (SELECT   
                    hr_employee_travel_claim_t.travel_claim_id,
                    hr_employee_travel_claim_t.employee_id,
                    hr_employee_travel_claim_t.forwarded_id,
                    hr_employee_travel_claim_t.company_id,
                    
                    hr_employee_travel_claim_t.claim_title,
                    hr_employee_travel_claim_t.description,
                    hr_employee_travel_claim_t.travel_purpose,
                    hr_employee_travel_claim_t.travel_date,
                    hr_employee_travel_claim_t.travel_to_date,
                    hr_employee_travel_claim_t.travel_mode,
                    hr_employee_travel_claim_t.from_place,
                    hr_employee_travel_claim_t.to_place,
                    hr_employee_travel_claim_t.distance,
                    hr_employee_travel_claim_t.rate,
                    hr_employee_travel_claim_t.approve_amount,
                    hr_employee_travel_claim_t.status,
                    hr_employee_travel_claim_t.location_id,
                    concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_name,
                    hr_employee.first_name as reporting_name,
                    (case 
                        when hr_employee_travel_claim_t.status = 0 then 'Initiated'
                        when hr_employee_travel_claim_t.status = 1 then 'Approved'
                        when hr_employee_travel_claim_t.status = 2 then 'Rejected'
                    end) as approved_status
                    from hr_employee_travel_claim_t
                LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_travel_claim_t.employee_id 
                LEFT JOIN hr_employee_t as hr_employee ON hr_employee.employee_id =hr_employee_travel_claim_t.forwarded_id)v1 where 1=1 $wh  ORDER by v1.travel_claim_id DESC";

    $result = \DB::select($SQL);
    return DataTables::of($result)->make(true);

  }



  public function travelreportgridgrid(Request $request)
  {

    $compy = \Session::get('companyid');
    $logged_user = Session::get('emp_id');
    $wh = '';

    $emp_data = DB::table("hr_employee_t")->where('employee_id', $logged_user)->get();
    $dept = json_decode($emp_data[0]->department);

    if (in_array(28, $dept)) {
      $wh = "";
    } else {
      if ($logged_user != "1") {
        $wh = "and v1.employee_id=$logged_user ";
      }
    }

    $SQL = "SELECT * from (SELECT   
     hr_employee_travel_claim_t.travel_claim_id,
     hr_employee_travel_claim_t.employee_id,
     hr_employee_travel_claim_t.forwarded_id,
     hr_employee_travel_claim_t.company_id,
     hr_employee_travel_claim_t.claim_title,
     hr_employee_travel_claim_t.description,
     hr_employee_travel_claim_t.travel_purpose,
     hr_employee_travel_claim_t.travel_date,
     hr_employee_travel_claim_t.travel_to_date,
     hr_employee_travel_claim_t.travel_mode,
     hr_employee_travel_claim_t.from_place,
     hr_employee_travel_claim_t.to_place,
     hr_employee_travel_claim_t.distance,
     hr_employee_travel_claim_t.rate,
     hr_employee_travel_claim_t.approve_amount, 
     hr_employee_t.department,
     CONCAT(hr_emp.employee_number,'-', hr_emp.first_name) as reporting_name,
     CONCAT(hr_employee_t.employee_number,'-', hr_employee_t.first_name) as employee_name,
     (case 
     when hr_employee_travel_claim_t.status = 0 then 'Initiated'
     when hr_employee_travel_claim_t.status = 1 then 'Approved'
     when hr_employee_travel_claim_t.status = 2 then 'Rejected'
     end) as  approved_status  from hr_employee_travel_claim_t  LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_travel_claim_t.employee_id  LEFT JOIN hr_employee_t as hr_emp ON hr_emp.employee_id =hr_employee_travel_claim_t.forwarded_id)v1 where 1=1  $wh ORDER by v1.travel_claim_id DESC";

    $query_result = \DB::select($SQL);
    $logged_user = Session::get('emp_id');
    $this->data['logged_user'] = $logged_user;

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

  public function travelclaimgriddata(Request $request)
  {

    $compy = \Session::get('companyid');
    $logged_user = \Session::get('emp_id');

    $wh = "and v1.employee_id ='$logged_user' and v1.company_id='$compy'";

    $SQL = "select * from (SELECT   
                    hr_employee_travel_claim_t.travel_claim_id,
                    hr_employee_travel_claim_t.employee_id,
                    hr_employee_travel_claim_t.forwarded_id,
                    hr_employee_travel_claim_t.company_id,
                    
                    hr_employee_travel_claim_t.claim_title,
                    hr_employee_travel_claim_t.description,
                    hr_employee_travel_claim_t.travel_purpose,
                    hr_employee_travel_claim_t.travel_date,
                    hr_employee_travel_claim_t.travel_to_date,
                    hr_employee_travel_claim_t.travel_mode,
                    hr_employee_travel_claim_t.from_place,
                    hr_employee_travel_claim_t.to_place,
                    hr_employee_travel_claim_t.distance,
                    hr_employee_travel_claim_t.rate,
                    hr_employee_travel_claim_t.approve_amount,
                    hr_employee_travel_claim_t.status,
                    concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_name,
                    hr_employee.first_name as reporting_name,
                    (case 
                        when hr_employee_travel_claim_t.status = 0 then 'Initiated'
                        when hr_employee_travel_claim_t.status = 1 then 'Approved'
                        when hr_employee_travel_claim_t.status = 2 then 'Rejected'
                    end) as approved_status
                    from hr_employee_travel_claim_t
                LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_travel_claim_t.employee_id 
                LEFT JOIN hr_employee_t as hr_employee ON hr_employee.employee_id =hr_employee_travel_claim_t.forwarded_id)v1 where 1=1 $wh  ORDER by v1.travel_claim_id DESC";

    $result = \DB::select($SQL);

    return DataTables::of($result)->make(true);

  }


  public function travelpaymentcreate()
  {
    //dd("dsds");
    if ($_GET['claim_id'] != "0") {
      $this->data['return'] = "paymenttravelclaim";
      $data = DB::table('hr_employee_travel_claim_t')->where('travel_claim_id', $_GET['claim_id'])->get();
      $this->data['row'] = (object) array();
      $this->data['row']->payment_id = "";
      $this->data['row']->payment_number = '';
      $this->data['row']->invoice_amount = $data[0]->approve_amount;
      $this->data['row']->payment_date = date('Y-m-d');
      $this->data['row']->cheque_date = date('Y-m-d');
      $payamt = $this->data['row']->payment_status = "";
      $this->data['row']->payment_amount = "";
      $this->data['row']->directpay = "No";
      $this->data['reference_id'] = $_GET['claim_id'];
      $this->data['row']->payment_source = "TRAVEL";
      $this->data['row']->paid_amount = '';
      $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
      $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
    } else {
      $this->data['return'] = "paymenttravelclaim";
      $this->data['row'] = (object) array();
      $this->data['row']->payment_id = "";
      $this->data['row']->payment_number = '';
      $this->data['row']->invoice_amount = "";
      $this->data['row']->payment_date = date('Y-m-d');
      $this->data['row']->cheque_date = date('Y-m-d');
      $payamt = $this->data['row']->payment_status = "";
      $this->data['row']->payment_amount = "";
      $this->data['row']->directpay = "Yes";
      $this->data['reference_id'] = "";
      $this->data['row']->payment_source = "TRAVEL";
      $this->data['row']->paid_amount = '';
      $this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
      $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
      $this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name', '');
    }
    return view('payments.imprestpayment', $this->data);


  }
}
