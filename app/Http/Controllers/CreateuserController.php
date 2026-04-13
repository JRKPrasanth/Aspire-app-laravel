<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Createuser;
use App\Http\Controllers\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Database\Eloquent\Model, Input;

class CreateuserController extends Controller
{

  public function __construct()
  {

    $this->data = array();
    $this->table = "a_m_users_t";
    $this->pageModule = "user";
    $this->model = new Createuser;
    $this->data['pageModule'] = $this->pageModule;
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['pageFormtype'] = 'ajax';
    $this->data = array(
      'pageModule' => 'createuser',
      'pageUrl' => url($this->data['pageMethod']),
      'pageMethod' => $this->data['pageMethod']
    );
    $this->modelname = new Createuser();
    $this->data['pageFormtype'] = 'ajax';
    $this->data['urlmenu'] = $this->indexs();

  }

  public function index()
  {


    $data = \DB::select("SELECT *  FROM `tb_logs` WHERE auditID='319472'
 ORDER BY `auditID` DESC");

    $data = \DB::select("select * from a_user_access_t limit 1");
    $this->data['pageMethod'] = \Request::route()->getName();
    $table = \DB::table('tb_users')->get();
    $this->data['data'] = $table;

    return view("createuser.table", $this->data);

  }

  public function create($id = null)
  {

    if (isset($_GET['pagemethod'])) {
      $this->data['pageMethod'] = $_GET['pagemethod'];
    }
    $this->data['pageModule'] = "user";

    if (!empty($id)) {

      $this->data['row'] = (object) array();
      $user_id = \DB::select("select * from tb_users where id='$id'");
      $this->data['row']->group_id = $this->jCombologin('a_m_group_t', 'group_id', 'group_name', $user_id[0]->group_id);
      $this->data['row']->org_id = $this->jCombologin('m_organizations_t', 'organization_id', 'organization_name', $user_id[0]->org_id);
      $locid = json_decode($user_id[0]->loc_id);

      if ($locid !== null) {

        $locationid = implode(",", $locid);

      } else {
        $locationid = '';
      }
      $dep = json_decode($user_id[0]->admindept_id);
      if ($dep !== null) {
        $deptid = implode(",", $dep);


      } else {
        $deptid = '';
      }
      $comp = \Session('companyid');
      $sqlhdr = \DB::select("select * from m_company_t where company_id=" . $comp);
      $sql = \DB::select("select * from m_company_line_t where companyid=" . $sqlhdr[0]->company_id);

      $locarr = '';

      foreach ($sql as $key => $value) {
        $locarr .= $value->locationid . ',';
      }

      $arr = rtrim($locarr, ',');

      $this->data['row']->loc_id = $this->jcustommultiselect1('m_location_t', 'location_id', 'location_name', $locationid, 'and location_id in(' . $arr . ')');
      $this->data['row']->comp_id = $this->jCombocomp('m_company_t', 'company_id', 'company_name', \Session('companyid'));
      $this->data['row']->admindept_id = $this->jcustommultiselect('m_department_lines_t', 'department_line_id', 'sub_department_name', $deptid, '');
      $this->data['row']->first_name = $user_id[0]->first_name;
      $this->data['row']->username = $user_id[0]->username;
      $this->data['row']->last_name = $user_id[0]->last_name;
      $this->data['row']->active = $user_id[0]->active;
      $this->data['row']->email = $user_id[0]->email;
      $this->data['row']->mobile_no = $user_id[0]->mobile_no;
      $this->data['row']->user_id = $user_id[0]->id;
      $this->data['row']->avatar = $user_id[0]->avatar;
      $this->data['row']->user_mail = $user_id[0]->user_mail;
      $this->data['row']->employee_id = $user_id[0]->employee_id;
    } else {

      $this->data['row'] = (object) array();
      $this->data['row']->group_id = $this->jCombologin('a_m_group_t', 'group_id', 'group_name', '');
      $this->data['row']->org_id = $this->jCombologin('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
      $comp = \Session('companyid');
      $sqlhdr = \DB::select("select * from m_company_t where company_id=" . $comp);
      $sql = \DB::select("select * from m_company_line_t where companyid=" . $sqlhdr[0]->company_id);
      $locarr = '';
      foreach ($sql as $key => $value) {
        $locarr .= $value->locationid . ',';
      }
      $arr = rtrim($locarr, ',');

      $this->data['row']->loc_id = $this->jcustommultiselect1('m_location_t', 'location_id', 'location_name', '', 'and location_id in(' . $arr . ')');
      $this->data['row']->comp_id = $this->jcustomselect('m_company_t', 'company_id', 'company_name', \Session('companyid'), '');
      $this->data['row']->admindept_id = $this->jcustomselect('m_department_lines_t', 'department_line_id', 'sub_department_name', '', "");
      $this->data['row']->first_name = "";
      $this->data['row']->username = "";
      $this->data['row']->active = "";
      $this->data['row']->last_name = "";
      $this->data['row']->email = "";
      $this->data['row']->mobile_no = "";
      $this->data['row']->user_id = "";
      $this->data['row']->user_mail = "";
      $this->data['row']->employee_id = "";

    }

    return view("createuser.form", $this->data);
  }


  public function save(Request $request)
  {

    if ($_POST['user_id'] == "") {

      $createuser = new Createuser();
      $createuser->employee_number = $request->input('username');
      $createuser->username = $request->input('username');
      $createuser->first_name = $request->input('first_name');
      $createuser->last_name = $request->input('last_name');
      $createuser->email = $request->input('email');
      $createuser->company_id = $request->input('company_id');
      $createuser->org_id = \Session::get('organization');
      $createuser->loc_id = json_encode($request->input('loc_id'));
      $createuser->admindept_id = json_encode($request->input('admindept_id'));
      $createuser->group_id = $group_id = $request->input('group_id');
      if ($request->input('password') == null) {
        $createuser->password = "welcome123";
      } else {
        $createuser->password = $request->input('password');

      }
      $createuser->mobile_no = $request->input('mobile_no');
      $createuser->active = $request->input('active');
      $createuser->employee_id = $request->input('employee_id');
      $createuser->updated_at = "";
      $createuser->created_at = "";

      $image = $request->file('avatar');
      if ($image != "") {
        $name = $createuser->username . rand(10, 100) . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/images/profile_images');
        $image->move($destinationPath, $name);
        $createuser->photo = $name;
      }

      /*** user table ****/
      $user_details = array(
        'group_id' => $createuser->group_id,
        'username' => $createuser->username,
        'password' => bcrypt($createuser->password),
        'email' => $createuser->email,
        'first_name' => $createuser->first_name,
        'last_name' => $createuser->last_name,
        'company_id' => $createuser->company_id,
        'loc_id' => $createuser->loc_id,
        'admindept_id' => $createuser->admindept_id,
        'org_id' => $createuser->org_id,
        'avatar' => $createuser->photo,
        'mobile_no' => $createuser->mobile_no,
        'employee_id' => $createuser->employee_id,
        'active' => $createuser->active
      );
      /**********/

      $data['user_id'] = \DB::table('tb_users')->insertGetId($user_details);

      $group_access = DB::table('a_group_menu_access_t')->where('group_id', $group_id)->get();
      if (count($group_access) > 0) {
        $data['menus'] = $group_access[0]->menus;
        $data['permission'] = $group_access[0]->permission;
        $data['group_id'] = $group_id;
      } else {
        $data['menus'] = "";
        $data['permission'] = "";
        $data['group_id'] = $group_id;
      }
      $user_access = DB::table('a_user_access_t')->insert($data);

      return response()->json(['status' => 'success', 'message' => 'Saved successfully']);

    } else {

      $input_data = $request->all();
      $createuser = new Createuser();
      $createuser->username = $request->input('username');
      $createuser->employee_number = $request->input('username');
      $createuser->first_name = $request->input('first_name');
      $createuser->user_mail = $request->input('user_mail');
      $createuser->user_password = $request->input('user_password');
      $createuser->last_name = $request->input('last_name');
      $createuser->email = $request->input('email');
      $createuser->company_id = $request->input('company_id');
      $createuser->org_id = \Session::get('organization');
      $createuser->loc_id = json_encode($request->input('loc_id'));
      $createuser->admindept_id = json_encode($request->input('admindept_id'));
      $createuser->group_id = $group_id = $request->input('group_id');
      $createuser->active = $request->input('active');
      $createuser->mobile_no = $request->input('mobile_no');
      $createuser->employee_id = $request->input('employee_id');
      $createuser->updated_at = "";
      $createuser->created_at = "";

      $user = \DB::table('tb_users')->where('id', $_POST['user_id'])->get();
      $image = $request->file('avatar');

      if ($image != "") {

        $image_path = public_path('/images/profile_images/' . $user[0]->avatar);

        if ($user[0]->avatar != null) {
          unlink($image_path);
        }

        $name = $createuser->username . rand(10, 100) . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path('/images/profile_images');
        $image->move($destinationPath, $name);
        $photo = $name;

      } else {

        $photo = $user[0]->avatar;
      }
      $password = $request->input('password');
      if ($password != null) {
        $password = bcrypt($_POST['password']);
      } else {
        $password = $user[0]->password;
      }

      $createuser->photo = $photo;
      $createuser->password = $password;
      $user_details = array(
        'group_id' => $createuser->group_id,
        'username' => $createuser->username,
        'password' => $password,
        'email' => $createuser->email,
        'first_name' => $createuser->first_name,
        'active' => $createuser->active,
        'last_name' => $createuser->last_name,
        'company_id' => $createuser->company_id,
        'loc_id' => $createuser->loc_id,
        'admindept_id' => $createuser->admindept_id,
        'org_id' => $createuser->org_id,
        'avatar' => $createuser->photo,
        'mobile_no' => $createuser->mobile_no,
        'employee_id' => $createuser->employee_id,
        'user_mail' => $createuser->user_mail,
        'user_password' => $createuser->user_password
      );

      $tb_user = \DB::table('tb_users')->where('id', $_POST['user_id'])->update($user_details);

      $group_access = DB::table('a_group_menu_access_t')->where('group_id', $group_id)->get();
      $data['menus'] = $group_access[0]->menus;
      $data['permission'] = $group_access[0]->permission;
      $data['group_id'] = $group_id;
      $data['user_id'] = $_POST['user_id'];
      $check_exist = \DB::table('a_user_access_t')->where('user_id', $data['user_id'])->get();

      return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
    }
  }

  public function show(Createuser $createuser, $id = null)
  {
    if (isset($id)) {
      $user = DB::table('tb_users')->leftjoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'tb_users.org_id')
        ->leftjoin('m_location_t', 'm_location_t.location_id', '=', 'tb_users.loc_id')
        ->leftjoin('m_company_t', 'm_company_t.company_id', '=', 'tb_users.company_id')
        ->leftjoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'tb_users.admindept_id')
        ->leftjoin('a_m_group_t', 'a_m_group_t.group_id', '=', 'tb_users.group_id')
        ->select('tb_users.*', 'm_location_t.location_id', 'm_company_t.company_id', 'm_department_lines_t.sub_department_name', 'm_department_lines_t.department_line_id', 'a_m_group_t.group_id')
        ->where('id', $id)->get();
      $this->data['user_name'] = $user[0]->username;
      $this->data['first_name'] = $user[0]->first_name;
      $this->data['last_name'] = $user[0]->last_name;
      $this->data['email'] = $user[0]->email;
      $this->data['mobile_no'] = $user[0]->mobile_no;
      $location = json_decode($user[0]->loc_id);
      $l_tion = '';
      foreach ($location as $key => $value) {
        $l_tion .= $value . ",";
      }
      // dd($user[0]);
      $l_tion = rtrim($l_tion);
      $this->data['org_id'] = $this->idname("organization_name", "m_organizations_t", "organization_id", $user[0]->org_id);
      $this->data['loc_id'] = $this->idname("location_name", "m_location_t", "location_id", $l_tion);
      $this->data['company_id'] = $this->idname("company_name", "m_company_t", "company_id", $user[0]->company_id);
      $this->data['admindept_id'] = $user[0]->sub_department_name;
      $this->data['group_id'] = $this->idname("group_name", "a_m_group_t", "group_id", $user[0]->group_id);
      // dd($this->data);
      return view('createuser.view', $this->data);
    }
  }



  // Table Data
  public function userdata(Request $request)
  {
    if ($request->ajax()) {
      $query = \DB::table('tb_users')
        ->join('a_m_group_t', 'tb_users.group_id', '=', 'a_m_group_t.group_id')
        ->select('tb_users.id', 'tb_users.username', 'tb_users.email', 'tb_users.mobile_no', 'a_m_group_t.group_name');

      return DataTables::of($query)->make(true);
    }
  }


  public function edit(Createuser $createuser)
  {
    //
  }

  public function update(Request $request, Createuser $createuser)
  {
    //
  }


  public function productsyncfgold(Createuser $createuser)
  {
    $data = \DB::select("select * from i_qoh_detail_t join w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id and w_jobcard_hdr_t.temp_status=0 join m_products_t ON m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=1 where i_qoh_detail_t.job_id< 17899 and date(i_qoh_detail_t.created_at)>='2021-04-01' group by i_qoh_detail_t.job_id order by date(i_qoh_detail_t.created_at) asc LIMIT 400");
    //dd($data);
//$data=array();

    //dd($data);
//$data= \DB::select("SELECT i_qoh_detail_t.batch_number, w_jobcard_hdr_t.*,m_products_t.*  FROM `i_qoh_detail_t` JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id JOIN m_products_t on
//m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=1 WHERE i_qoh_detail_t.`job_process` != ''  and i_qoh_detail_t.qoh_source!='Return Store Move' limit 8,20000");
    //dd(($data));
    $employee_details = \DB::table('hr_employee_payproposal')->select('hr_employee_payproposal.employee_id', "hr_employee_payproposal.gross_pay")->get();
    // dd($data);
    $i = 351;
    $j = 500;
    $employee_details = collect($employee_details);
    foreach ($data as $key => $job_value) {
      /* Accounts Entry Start :Isac Naveen*/
      //  $products=\DB::table('m_products_t')->whereIn('product_id',$_POST['bulk_product_id'])->get();
      // $collection = collect($products);

      $id = $job_value->w_jobs_hdr_id;

      \DB::select("update w_jobcard_hdr_t set temp_status=1 where w_jobs_hdr_id='$id'");

      $receive_data = \DB::select("SELECT w_materialreceive_line_t.*,m_products_t.*  FROM `w_materialreceive_hdr_t`  join w_materialreceive_line_t on w_materialreceive_line_t.w_materialreceive_hdr_id= w_materialreceive_hdr_t.w_materialreceive_hdr_id join m_products_t ON m_products_t.product_id=w_materialreceive_line_t.product_id WHERE w_materialreceive_hdr_t.w_jobs_hdr_id =$id");
      //dd($receive_data);

      if (count($receive_data) > 0) {

        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();

        //dd($products);


        $date = $job_value->job_completion_date;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $jobid = $id;
        $batch_number = $job_value->batch_number;
        $job_no = $job_value->job_no;
        $journal_name = "Material Receive-" . $job_no;

        $pro = $job_value->product_id;
        $plan_id = $job_value->reference_source_id;
        $process = '';
        $pp = $job_value->bom_process;
        $process_name = \DB::select("SELECT *  FROM `w_productionplan_lines_t` WHERE `productionplan_hdr_id` = $plan_id and process_level!='' group by process_level order by process_level asc");
        if ($pp == 'FINALPROCESS') {
          $c = count($process_name) - 1;
          $process = $process_name[$c]->process_level;
        } else {
          foreach ($process_name as $p) {
            //echo $p->process_level;
            if ($p->process_level != $pp) {

              $process = $p->process_level;
              //  echo $process;
            } else {
              break;
            }
          }
        }
        //dd($process);
        //dd($pp);                  /*Journal Header Insert*/

        $m_check = \DB::select("select * from f_journal_entry_t where journal_name='$journal_name' and journal_type='MATERIAL RECEIVE' and journal_reference='$jobid'");
        $jid = 0;
        if (count($m_check) <= 0) {
          $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','MATERIAL RECEIVE','$date','$jobid','APPROVED','$compy','$loc','$org')");
          $jid = DB::getPdo()->lastInsertId();
        }
        $tkey = 1;
        $total = 0;
        $journal_lines_data = array();

        foreach ($receive_data as $key => $value) {


          $batchnumber = explode(',', $value->batchnumber);
          $receiveqty = explode(',', $value->receiveqty);
          // dd($receiveqty);
          $pro_id = $value->product_id;

          $product_cost = 0;

          if ($value->product_group_id != 1) {
            foreach ($batchnumber as $k => $v) {

              $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id' and cost > 0 and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

              if (count($cost) > 0) {

                if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                  $receiveqty[$k] = $value->receive_qty;
                $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
              } else {

                $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id'  and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

                if (count($cost) > 0) {

                  $qoh_id = $cost[0]->qoh_detail_id;

                  $cost = \DB::select("select * from i_qoh_detail_t where qoh_detail_id<$qoh_id and cost >0  and product_id='$pro_id'  and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

                  if (count($cost) > 0) {

                    if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                      $receiveqty[$k] = $value->receive_qty;
                    $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                  } else {
                    $product_cost += 0;
                  }
                } else {
                  $product_cost += 0;
                }


              }
            }

          } else {
            foreach ($batchnumber as $k => $v) {
              $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and job_id<'$id' and job_process='$process' and product_id='$pro_id' and cost > 0 and job_process!='' and job_process!=0 ORDER BY qoh_detail_id  DESC");

              if (count($cost) > 0) {

                $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
              } else {
                $product_cost += 0;
                $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v'  and job_process='$process' and product_id='$pro_id' and cost > 0 ORDER BY `qoh_detail_id` desc ");
                if (count($cost) > 0) {

                  $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                } else {
                  $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and qoh_source='OPENSTOCK' and product_id='$pro_id' and cost > 0");
                  if (count($cost) > 0) {

                    $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                  }
                }

              }
            }
          }

          $total += $product_cost;
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $job_value->job_completion_date;
          $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
          $journal_lines_data[$tkey]['reference_id'] = $pro_id;
          if ($value->product_group_id == 1) {
            $journal_lines_data[$tkey]['account_id'] = $value->control_account_id;
          } else {
            $journal_lines_data[$tkey]['account_id'] = $value->account_code_id;
          }
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $product_cost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $job_value->job_completion_date . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $job_value->job_completion_date . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
          $tkey++;


        }
        $tkey = 0;
        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $job_value->job_completion_date;
        $journal_lines_data[$tkey]['reference_source'] = "";
        $journal_lines_data[$tkey]['reference_id'] = "";
        if ($job_value->product_group_id == 1) {
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->packingcontrol_accid;
        } else {
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->production;
        }
        $journal_lines_data[$tkey]['debit_amount'] = $total;
        $journal_lines_data[$tkey]['credit_amount'] = '';
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = $job_value->job_completion_date . " 12:12:00";
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = $job_value->job_completion_date . " 12:12:00";
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

        ksort($journal_lines_data);
        //  dd($journal_lines_data);

        if ($jid != 0) {
          \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
        }
      }
      /* Accounts Entry End :Isac Naveen*/

      $qa_submit = \DB::select("SELECT * FROM `w_qa_submitstage_trx_t` WHERE `job_no`='$id'");

      if (count($qa_submit) > 0) {
        $journal_lines_data = array();
        $start_times = explode(",", ($qa_submit[0]->start_time));
        $end_times = explode(",", ($qa_submit[0]->end_time));
        sort($start_times);
        rsort($end_times);


        $date = $qa_submit[0]->qatrx_date;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $jobid = $id;

        $journal_name = "Job Card Close-" . $job_no;
        /*Journal Header Insert*/

        $m_check = \DB::select("select * from f_journal_entry_t where journal_name='$journal_name' and journal_type='JOB CARD CLOSE' and journal_reference='$jobid'");
        $jid = 0;
        if (count($m_check) <= 0) {

          $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','JOB CARD CLOSE','$date','$jobid','APPROVED','$compy','$loc','$org')");
          $jid = DB::getPdo()->lastInsertId();
        }

        $acc_data = \DB::table('f_journal_entry_t')->join('f_journal_entry_lines_t', 'f_journal_entry_lines_t.journal_entry_id', '=', 'f_journal_entry_t.journal_entry_id')->select('f_journal_entry_lines_t.reference_id', 'f_journal_entry_lines_t.debit_amount', 'f_journal_entry_lines_t.credit_amount')->where('journal_type', 'MATERIAL RECEIVE')->where('journal_reference', $id)->get();
        $acc_collection = collect($acc_data);

        // $products=\DB::table('m_products_t')->whereIn('product_id',$_POST['bulk_product_id'])->get();
        //$collection = collect($products);

        $qa_data = \DB::select("SELECT w_qa_submitstage_line_t.*,m_products_t.*  FROM `w_qa_submitstage_line_t` join m_products_t on m_products_t.product_id=w_qa_submitstage_line_t.product_id WHERE w_qa_submitstage_line_t.`qa_submitstage_trx_hdr_id` ='" . $qa_submit[0]->qa_submitstage_trx_hdr_id . "'");


        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();
        $tkey = 1;
        $overallcost = 0;



        foreach ($qa_data as $k1 => $val1) {

          error_reporting(0);
          $filtered = $acc_collection->where('reference_id', $val1->product_id);
          $filtered->all();
          $credit_amount = 0;
          foreach ($filtered as $v1) {
            $credit_amount += $v1->credit_amount;
            // break;
          }





          $pro_qty = $val1->production_qty + $val1->exceed_qty;
          $return_cost = 0;
          if ($val1->return_qty != '' && $val1->return_qty != 0) {

            $cost = round($credit_amount / $val1->qty, 2);

            $return_cost = ($val1->return_qty) * ($cost);

            // $overallcost+=$credit_amount-$cost;


            /*Journal Entry For Return material start*/
            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
            $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
            $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
            $journal_lines_data[$tkey]['reference_id'] = $val1->product_id;
            $journal_lines_data[$tkey]['account_id'] = $val1->account_code_id;
            $journal_lines_data[$tkey]['debit_amount'] = $return_cost;
            $journal_lines_data[$tkey]['credit_amount'] = '';
            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
            /*Journal Entry For Return material end*/
            $tkey++;



          }
          if ($val1->scrap_qty != '' && $val1->scrap_qty != 0) {

            // $pro_cost=($credit_amount)-($return_cost);
            // $pro_qty=$pro_qty+$val1->scrap_qty;
            $cost = ($credit_amount / $val1->qty) * $val1->scrap_qty;

            $overallcost += $credit_amount - $return_cost - $cost;

            /*Journal Entry For scrap material start*/
            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
            $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
            $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
            $journal_lines_data[$tkey]['reference_id'] = $val1->product_id;
            $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->scrab;
            $journal_lines_data[$tkey]['debit_amount'] = $cost;
            $journal_lines_data[$tkey]['credit_amount'] = '';
            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
            /*Journal Entry For scrap material end*/
            $tkey++;
          } else {

            $overallcost += ($credit_amount) - ($return_cost);
          }
        }

        //dd($filtered);

        /* Journal Entry For Production Control Account */

        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
        $journal_lines_data[$tkey]['reference_source'] = "";
        $journal_lines_data[$tkey]['reference_id'] = '';
        if ($job_value->product_group_id == 1) {

          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->packingcontrol_accid;
        } else {
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->production;
        }
        $journal_lines_data[$tkey]['debit_amount'] = '';
        $journal_lines_data[$tkey]['credit_amount'] = $acc_data[0]->debit_amount;
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
        /*Journal Entry For scrap material end*/
        $tkey++;
        /*Journal Entry For Labour Start*/


        $month = $month = date('m', strtotime($qa_submit[0]->qatrx_date));
        $year = date('Y', strtotime($qa_submit[0]->qatrx_date));
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        //dd($val1);
        $empl = explode(',', ($qa_submit[0]->job_assigned_to));
        $empl_working = explode(',', ($qa_submit[0]->working_hrs));

        $start_timess = explode(",", ($qa_submit[0]->start_time));
        $end_timess = explode(",", ($qa_submit[0]->end_time));


        foreach ($empl as $k => $evalue) {
          $jc_date = $qa_submit[0]->qatrx_date;
          $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal`  where employee_id='$evalue' and date(updated_at)<='$jc_date'");

          if (count($filtered) > 0) {
            $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
          } else {

            $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue' and date(updated_at)<='$jc_date' limit 1");

            if (count($filtered) > 0) {
              $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
            } else {

              $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue'  limit 1");
              if (count($filtered) > 0) {
                $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
              } else {

                $hour_cost = 0;

              }

            }

          }




          $start = strtotime($start_timess[$k]);
          $end = strtotime($end_timess[$k]);
          $total_hours = ($end - $start) / 60;


          $overallcost += $total_cost = round($total_hours * ($hour_cost / 60), 2);

          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
          $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
          $journal_lines_data[$tkey]['reference_id'] = $evalue;
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->labour;
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $total_cost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

          $tkey++;




        }

        /*Journal Entry For Labour End*/

        /* Journal Entry For Electricity start */

        $to_time = strtotime($start_times[0]);
        $from_time = strtotime($end_times[0]);
        $machine_time = round((abs($to_time - $from_time) / 60) / 60, 2);

        $machine_details = \DB::table('w_jobcard_hdr_t')->join('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'w_jobcard_hdr_t.machine_hdr_id')->select('w_machine_hdr_t.machine_hdr_id', 'w_machine_hdr_t.electricity_cost')->where('w_jobcard_hdr_t.w_jobs_hdr_id', $id)->get();
        //dd($machine_details);
        $overallcost += $cost = round($machine_details[0]->electricity_cost * $machine_time, 2);
        if ($cost > 0) {
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
          $journal_lines_data[$tkey]['reference_source'] = "MACHINE";
          $journal_lines_data[$tkey]['reference_id'] = $machine_details[0]->machine_hdr_id;
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->eletricity;
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $cost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

          $tkey++;
        }


        /* Journal Entry For Electricity end */

        /* Journal Entry For WIP Account */
        $tkey = 0;
        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
        $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
        $journal_lines_data[$tkey]['reference_id'] = $job_value->product_id;
        $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->wip;
        $journal_lines_data[$tkey]['debit_amount'] = $overallcost;
        $journal_lines_data[$tkey]['credit_amount'] = '';
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

        ksort($journal_lines_data);

        //dd($journal_lines_data);

        if ($jid != 0) {

          \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);


          $cost = round($overallcost / $qa_submit[0]->production_qty, 2);
          if ($qa_submit[0]->production_qty == 0) {
            $cost = 0;
          }
          \DB::select("update i_qoh_detail_t set cost='$cost' where job_id='$id' and product_id='$pro'");


          /* Journal Entry For Production Product When -> Quality Check is No */


          $journal_name = "Quality Approve";
          $date = $qa_submit[0]->qatrx_date;
          $org = \Session::get('organization');
          $loc = \Session::get('location');
          $compy = \Session::get('companyid');
          $jobid = $id;

          $journal_name = "Quality Approve-" . $job_no;
          /*Journal Header Insert*/
          $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','QUALITY APPROVE','$date','$jobid','APPROVED','$compy','$loc','$org')");
          $jid = DB::getPdo()->lastInsertId();

          //   $products=\DB::table('m_products_t')->where('product_id',$_POST['product_id'])->get();

          $tkey = 0;
          $journal_lines_data = array();
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
          $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
          $journal_lines_data[$tkey]['reference_id'] = $job_value->product_id;

          // if($job_value->product_group_id==1)
          // {
          // $journal_lines_data[$tkey]['account_id']=$job_value->control_account_id;
          // }
          // else
          // {
          // $journal_lines_data[$tkey]['account_id']=$job_value->account_code_id;
          // }


          if ($pp != 'FINALPROCESS') {
            $journal_lines_data[$tkey]['account_id'] = $product[0]->control_account_id;
          } else {
            $journal_lines_data[$tkey]['account_id'] = $product[0]->account_code_id;
          }


          $journal_lines_data[$tkey]['debit_amount'] = $overallcost;
          $journal_lines_data[$tkey]['credit_amount'] = '';
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

          $tkey++;
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
          $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
          $journal_lines_data[$tkey]['reference_id'] = $job_value->product_id;
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->wip;
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $overallcost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

          \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);


        }

        /*End*/


      }
    }
    if (count($data) > 0) {
      dd(count($data) . " FG Entries Sync Completed");
    } else {
      dd("There Is No More FG Entries to Sync");
    }
    //return redirect('company');


  }


  public function productsyncfg(Createuser $createuser)
  {
    $data = \DB::select("select w_jobcard_hdr_t.product_id,w_jobcard_hdr_t.w_jobs_hdr_id,w_jobcard_hdr_t.job_completion_date,w_jobcard_hdr_t.batch_no,w_jobcard_hdr_t.job_no from w_jobcard_hdr_t  join m_products_t ON m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=1  where w_jobcard_hdr_t.job_date>'2024-04-01' and w_jobcard_hdr_t.temp_status=0 and (w_jobcard_hdr_t.job_status='MATERIAL RECEIVED' or w_jobcard_hdr_t.job_status='QA SUBMITTED' or w_jobcard_hdr_t.job_status='CLOSED') ");
    //dd($data);
//$data=array();

    //dd($data);
//$data= \DB::select("SELECT i_qoh_detail_t.batch_number, w_jobcard_hdr_t.*,m_products_t.*  FROM `i_qoh_detail_t` JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id JOIN m_products_t on
//m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=1 WHERE i_qoh_detail_t.`job_process` != ''  and i_qoh_detail_t.qoh_source!='Return Store Move' limit 8,20000");
    //dd(($data));
    $employee_details = \DB::table('hr_employee_payproposal')->select('hr_employee_payproposal.employee_id', "hr_employee_payproposal.gross_pay")->get();
    // dd($data);
    $i = 351;
    $j = 500;
    $employee_details = collect($employee_details);
    foreach ($data as $key => $job_value) {
      /* Accounts Entry Start :Isac Naveen*/
      //  $products=\DB::table('m_products_t')->whereIn('product_id',$_POST['bulk_product_id'])->get();
      // $collection = collect($products);

      $id = $job_value->w_jobs_hdr_id;
      //$id = '7309';

      \DB::select("update w_jobcard_hdr_t set temp_status=1 where w_jobs_hdr_id='$id'");

      $receive_data = \DB::select("SELECT w_materialreceive_line_t.*,m_products_t.*  FROM `w_materialreceive_hdr_t`  join w_materialreceive_line_t on w_materialreceive_line_t.w_materialreceive_hdr_id= w_materialreceive_hdr_t.w_materialreceive_hdr_id join m_products_t ON m_products_t.product_id=w_materialreceive_line_t.product_id WHERE w_materialreceive_hdr_t.w_jobs_hdr_id = $id ");

      //dd($receive_data);
      if (count($receive_data) > 0) {

        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();

        //dd($production_acc);


        $date = $job_value->job_completion_date;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $jobid = $id;
        $batch_number = $job_value->batch_no;
        $job_no = $job_value->job_no;
        $journal_name = "Material Receive-" . $job_no;

        $pro = $job_value->product_id;
        $process = '';
        // $pp=$job_value->bom_process;
// $process_name=\DB::select("select m_material_bom_lines_t.* from m_material_bom_hdr_t JOIN m_material_bom_lines_t on m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id where m_material_bom_hdr_t.assembly_product_id=$pro group by m_material_bom_lines_t.process_level order by m_material_bom_lines_t.process_level asc");
// if($pp=='FINALPROCESS')
// {
//   $c=count($process_name)-1;
//   $process=$process_name[$c]->process_level;
// }
// else
// {
// foreach($process_name as $p)
// {
//   //echo $p->process_level;
//     if($p->process_level!=$pp)
//     {

        //         $process=$p->process_level;
//     //  echo $process;
//     }
//     else
//     {
//       break;
//     }
// }
// }  
        //dd($pro);
        //dd($pp);                  /*Journal Header Insert*/
        $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','MATERIAL RECEIVE','$date','$jobid','APPROVED','$compy','$loc','$org')");
        $jid = DB::getPdo()->lastInsertId();
        $tkey = 1;
        $total = 0;
        $journal_lines_data = array();
        //dd($journal_lines_data);
        foreach ($receive_data as $key => $value) {


          $batchnumber = explode(',', $value->batchnumber);
          $receiveqty = explode(',', $value->receiveqty);

          $pro_id = $value->product_id;
          //dd($pro_id);  
          $product_cost = 0;

          if ($value->product_group_id != 1) {
            foreach ($batchnumber as $k => $v) {
              $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id' and cost > 0 and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");
              //echo "cost1";
                 //dd($cost);

              if (count($cost) > 0) {

                if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                  $receiveqty[$k] = $value->receive_qty;
                $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                //dd($product_cost);
              } else {

                $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id'  and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

                if (count($cost) > 0) {

                  $qoh_id = $cost[0]->qoh_detail_id;

                  $cost = \DB::select("select * from i_qoh_detail_t where qoh_detail_id<$qoh_id and cost >0  and product_id='$pro_id'  and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

                  if (count($cost) > 0) {

                    if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                      $receiveqty[$k] = $value->receive_qty;
                    $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                  } else {
                    $product_cost += 0;
                  }
                } else {
                  $product_cost += 0;
                }

              }
            }

          } else if ($value->product_group_id = 1 && $value->primary_uom_id = 6) {
            foreach ($batchnumber as $k => $v) {
              $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id' and cost > 0 and (qoh_source='OPENSTOCK' or (qoh_source='JOB STORE MOVE' and job_process='FINALPROCESS') or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");
              //echo "cost2";
              //dd($cost);

              if (count($cost) > 0) {

                if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                  $receiveqty[$k] = $value->receive_qty;
                $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
              } else {

                $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id'  and (qoh_source='OPENSTOCK' or (qoh_source='JOB STORE MOVE' and job_process='FINALPROCESS') or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");
               // dd($cost);
                if (count($cost) > 0) {

                  $qoh_id = $cost[0]->qoh_detail_id;

                  $cost = \DB::select("select * from i_qoh_detail_t where qoh_detail_id<$qoh_id and cost >0  and product_id='$pro_id'  and (qoh_source='OPENSTOCK' or (qoh_source='JOB STORE MOVE' and job_process='FINALPROCESS') or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

                  if (count($cost) > 0) {

                    if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                      $receiveqty[$k] = $value->receive_qty;
                    $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                  } else {
                    $product_cost += 0;
                  }
                } else {
                  $product_cost += 0;
                }


              }
            }
          } else {
            //dd($process);
            foreach ($batchnumber as $k => $v) {
              $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and job_id<'$id' and job_process='$process' and product_id='$pro_id' and cost > 0 and job_process!='' and job_process!=0 ORDER BY qoh_detail_id  DESC");
              //echo "cost3";
              //dd($cost);
              if (count($cost) > 0) {

                $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
              } else {
                $product_cost += 0;
                $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v'  and job_process='$process' and product_id='$pro_id' and cost > 0 ORDER BY `qoh_detail_id` desc ");
                if (count($cost) > 0) {

                  $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                } else {
                  $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and qoh_source='OPENSTOCK' and product_id='$pro_id' and cost > 0");
                  if (count($cost) > 0) {

                    $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                  }
                }

              }
            }
          }


          $total += $product_cost;
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $job_value->job_completion_date;
          $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
          $journal_lines_data[$tkey]['reference_id'] = $pro_id;
          $journal_lines_data[$tkey]['product_qty'] = $value->receive_qty;
          $journal_lines_data[$tkey]['batch_number'] = $value->batchnumber;
          if ($value->product_group_id == 1) {
            $journal_lines_data[$tkey]['account_id'] = $value->control_account_id;
          } else {
            $journal_lines_data[$tkey]['account_id'] = $value->account_code_id;
          }
          $journal_lines_data[$tkey]['debit_amount'] = '';
          //dd($product_cost);
          $journal_lines_data[$tkey]['credit_amount'] = $product_cost;
          //dd($journal_lines_data[$tkey]['credit_amount']);
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $job_value->job_completion_date . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $job_value->job_completion_date . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
          $tkey++;
          //dd($journal_lines_data);

        }
        $tkey = 0;
        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $job_value->job_completion_date;
        $journal_lines_data[$tkey]['reference_source'] = "";
        $journal_lines_data[$tkey]['reference_id'] = "";
        $journal_lines_data[$tkey]['product_qty'] = $value->receive_qty;
        $journal_lines_data[$tkey]['batch_number'] = $value->batchnumber;

        $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->packingcontrol_accid;


        $journal_lines_data[$tkey]['debit_amount'] = $total;
        $journal_lines_data[$tkey]['credit_amount'] = '';
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = $job_value->job_completion_date . " 12:12:00";
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = $job_value->job_completion_date . " 12:12:00";
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

        ksort($journal_lines_data);
        //dd($journal_lines_data);
        \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

        //dd($journal_lines_data);
      }
      /* Accounts Entry End :Isac Naveen*/

    }
    //dd("dss");
    $data = \DB::select("SELECT * FROM `w_jobcard_process_details_t` join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=w_jobcard_process_details_t.job_id where jo_status=0 ");

    //dd($data);

    foreach ($data as $job_value) {


      $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();

      \DB::select("update w_jobcard_process_details_t set jo_status=1 where job_process_id='" . $job_value->job_process_id . "'");

      //dd($products);

      $id = $job_value->job_id;
      $date = $job_value->process_date;
      $org = \Session::get('organization');
      $loc = \Session::get('location');
      $compy = \Session::get('companyid');
      $jobid = $id;
      $batch_number = $job_value->batch_no;
      $job_no = $job_value->job_no;
      $pp = $job_value->process_level;

      $journal_name = "Store Move-" . $job_no . "-$pp";

      $pro = $job_value->product_id;
      $process = '';

      $plan_id = $job_value->reference_source_id;

      $process_name = \DB::select("SELECT *  FROM `w_productionplan_lines_t` WHERE `productionplan_hdr_id` = $plan_id and process_level!='' group by process_level order by process_level asc");
      if ($pp == 'FINALPROCESS') {
        $c = count($process_name) - 1;
        $process = $process_name[$c]->process_level;
      } else if ($pp != 'PROCESS-1') {
        foreach ($process_name as $p) {
          //echo $p->process_level;
          if ($p->process_level != $pp) {

            $process = $p->process_level;
            //  echo $process;
          } else {
            break;
          }
        }
      }




      //dd($process);  

      $qa_submit = 0;

      if ($qa_submit == 0) {
        $journal_lines_data = array();
        $start_times = explode(",", ($job_value->process_start_date));
        $end_times = explode(",", ($job_value->process_end_date));
        sort($start_times);
        rsort($end_times);



        /*Journal Header Insert*/
        $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','JOB CARD STORE MOVE','$date','$jobid','APPROVED','$compy','$loc','$org')");
        $jid = DB::getPdo()->lastInsertId();

        $pac_data = \DB::select("SELECT m_material_bom_lines_t.component_product_id,m_material_bom_lines_t.component_qty,w_materialreceive_line_t.receive_qty,f_journal_entry_lines_t.credit_amount FROM `w_jobcard_hdr_t` join m_material_bom_hdr_t on m_material_bom_hdr_t.assembly_product_id=w_jobcard_hdr_t.product_id join m_material_bom_lines_t on m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id and m_material_bom_lines_t.process_level='$pp' join w_materialreceive_hdr_t on w_materialreceive_hdr_t.w_jobs_hdr_id=w_jobcard_hdr_t.w_jobs_hdr_id join w_materialreceive_line_t on w_materialreceive_line_t.w_materialreceive_hdr_id=w_materialreceive_hdr_t.w_materialreceive_hdr_id and w_materialreceive_line_t.product_id=m_material_bom_lines_t.component_product_id join f_journal_entry_t on f_journal_entry_t.journal_reference=w_jobcard_hdr_t.w_jobs_hdr_id and f_journal_entry_t.journal_type='MATERIAL RECEIVE' join f_journal_entry_lines_t on f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id and f_journal_entry_lines_t.reference_id=m_material_bom_lines_t.component_product_id where w_jobcard_hdr_t.w_jobs_hdr_id=$jobid");

        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();
        $tkey = 1;
        $overallcost = 0;
        $cost = 0;

        //dump($pac_data);
        foreach ($pac_data as $val) {
          $cost += round((($val->component_qty) * ($job_value->move_qty)) * (($val->credit_amount / $val->receive_qty)), 2);
        }

        //dump($cost);

        $overallcost += $cost;
        //echo "$jobid - $journal_name - $overallcost<br>";
        //dd($filtered);

        /* Journal Entry For Production Control Account */

        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $date;
        $journal_lines_data[$tkey]['reference_source'] = "";
        $journal_lines_data[$tkey]['reference_id'] = '';
        $journal_lines_data[$tkey]['product_qty'] = $job_value->move_qty;
        $journal_lines_data[$tkey]['batch_number'] = $job_value->batch_no;

        $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->packingcontrol_accid;


        $journal_lines_data[$tkey]['debit_amount'] = '';
        $journal_lines_data[$tkey]['credit_amount'] = $cost;
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = $date . " 12:12:00";
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = $date . " 12:12:00";
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
        /*Journal Entry For scrap material end*/
        $tkey++;

        $product = \DB::select("select * from m_products_t where product_id='" . $job_value->product_id . "'");

        //dd($process);

        if ($process != '') {
          $process_cost = \DB::select("select cost from i_qoh_detail_t where job_id='$id' and product_id='$pro' and job_process='$process'");

          $process_cost = $process_cost[0]->cost * $job_value->move_qty;
          $overallcost += $process_cost;
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $date;
          $journal_lines_data[$tkey]['reference_source'] = "";
          $journal_lines_data[$tkey]['reference_id'] = '';
          $journal_lines_data[$tkey]['product_qty'] = $job_value->move_qty;
          $journal_lines_data[$tkey]['batch_number'] = $job_value->batch_no;

          $journal_lines_data[$tkey]['account_id'] = $product[0]->control_account_id;


          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $process_cost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $date . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $date . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
          /*Journal Entry For scrap material end*/
          $tkey++;
        }



        /*Journal Entry For Labour Start*/


        $month = $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        //dd($val1);
        $empl = explode(',', ($job_value->jobassigned_to));
        $empl_working = explode(',', ($job_value->working_hrs));
        $start_timess = explode(",", ($job_value->start_time));
        $end_timess = explode(",", ($job_value->end_time));

        foreach ($empl as $k => $evalue) {
          $jc_date = $date;
          $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal`  where employee_id='$evalue' and date(updated_at)<='$jc_date'");

          if (count($filtered) > 0) {
            $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
          } else {

            $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue' and date(updated_at)<='$jc_date' limit 1");

            if (count($filtered) > 0) {
              $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
            } else {

              $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue'  limit 1");
              if (count($filtered) > 0) {
                $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
              } else {

                //$hour_cost = 0;

                $filtered = \DB::select("SELECT * FROM f_expenses_t LEFT JOIN f_expenses_lines_t ON f_expenses_t.expense_id = f_expenses_lines_t.expense_id WHERE f_expenses_t.employee_id='$evalue' AND f_expenses_t.expense_type='EMPLOYEE' AND f_expenses_lines_t.expense_account_id='407' AND date(f_expenses_t.expense_date) <= '$jc_date' ORDER BY f_expenses_t.expense_id DESC LIMIT 1");

                if (count($filtered) > 0) {
                  $hour_cost = ($filtered[0]->expense_amount / $days) / 8;
                } else {

                  $hour_cost = 0;
                }

              }

            }

          }




          $start = strtotime($start_timess[$k]);
          $end = strtotime($end_timess[$k]);
          $total_hours = ($end - $start) / 60;


          $overallcost += $total_cost = round($total_hours * ($hour_cost / 60), 2);

          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $date;
          $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
          $journal_lines_data[$tkey]['reference_id'] = $evalue;
          $journal_lines_data[$tkey]['product_qty'] = $job_value->working_hrs;
          $journal_lines_data[$tkey]['batch_number'] = '';
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->labour;
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $total_cost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $date . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $date . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

          $tkey++;




        }

        /*Journal Entry For Labour End*/

        /* Journal Entry For Electricity start */

        $to_time = strtotime($start_times[0]);
        $from_time = strtotime($end_times[0]);
        $machine_time = round((abs($to_time - $from_time) / 60) / 60, 2);
        $mac_id = $job_value->machine_id;

        $machine_details = \DB::select("SELECT electricity_cost,machine_hdr_id FROM `w_machine_hdr_t` where machine_hdr_id='$mac_id'");
        //dd($machine_details);
        $overallcost += $cost = round($machine_details[0]->electricity_cost * $machine_time, 2);
        echo "$jobid - $journal_name - $overallcost - $job_value->move_qty <br>";
        if ($cost > 0) {
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $date;
          $journal_lines_data[$tkey]['reference_source'] = "MACHINE";
          $journal_lines_data[$tkey]['reference_id'] = $machine_details[0]->machine_hdr_id;
          $journal_lines_data[$tkey]['product_qty'] = $machine_time;
          $journal_lines_data[$tkey]['batch_number'] = '';
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->eletricity;
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $cost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $date . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $date . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

          $tkey++;
        }


        /* Journal Entry For Electricity end */

        /* Journal Entry For WIP Account */
        $tkey = 0;
        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $date;
        $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
        $journal_lines_data[$tkey]['reference_id'] = $job_value->product_id;
        $journal_lines_data[$tkey]['product_qty'] = $job_value->move_qty;
        $journal_lines_data[$tkey]['batch_number'] = $job_value->batch_no;
        if ($pp != 'FINALPROCESS') {
          $journal_lines_data[$tkey]['account_id'] = $product[0]->control_account_id;
        } else {
          $journal_lines_data[$tkey]['account_id'] = $product[0]->account_code_id;
        }
        $journal_lines_data[$tkey]['debit_amount'] = $overallcost;
        $journal_lines_data[$tkey]['credit_amount'] = '';
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = $date . " 12:12:00";
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = $date . " 12:12:00";
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

        ksort($journal_lines_data);

       // dd($journal_lines_data);
       \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

        //dd($cost);
        $cost = round($overallcost / $job_value->move_qty, 2);
        if ($job_value->move_qty == 0) {
          $cost = 0;
        }
        \DB::select("update i_qoh_detail_t set cost='$cost' where job_id='$id' and product_id='$pro' and job_process='$pp'");





      }
    }

    $data = \DB::select("select w_jobcard_hdr_t.product_id,w_jobcard_hdr_t.w_jobs_hdr_id,w_jobcard_hdr_t.job_completion_date,w_jobcard_hdr_t.batch_no,w_jobcard_hdr_t.job_no from w_jobcard_hdr_t  join m_products_t ON m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=1  where w_jobcard_hdr_t.job_date>'2023-04-01' and  w_jobcard_hdr_t.qa_status=0 and ( w_jobcard_hdr_t.job_status='QA SUBMITTED' or w_jobcard_hdr_t.job_status='CLOSED') LIMIT 150");

    foreach ($data as $val) {

      $id = $val->w_jobs_hdr_id;
      \DB::select("update w_jobcard_hdr_t set qa_status=1 where w_jobs_hdr_id='$id'");

      $qa_submit = \DB::select("SELECT * FROM `w_qa_submitstage_trx_t` WHERE `job_no`='$id'");

      if (count($qa_submit) > 0) {
        $journal_lines_data = array();
        $start_times = explode(",", ($qa_submit[0]->start_time));
        $end_times = explode(",", ($qa_submit[0]->end_time));
        sort($start_times);
        rsort($end_times);


        $date = $qa_submit[0]->qatrx_date;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $jobid = $id;
        $job_no = $val->job_no;
        $journal_name = "Job Card Close-" . $job_no;
        /*Journal Header Insert*/
        $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','JOB CARD CLOSE','$date','$jobid','APPROVED','$compy','$loc','$org')");
        $jid = DB::getPdo()->lastInsertId();


        $acc_data = \DB::table('f_journal_entry_t')->join('f_journal_entry_lines_t', 'f_journal_entry_lines_t.journal_entry_id', '=', 'f_journal_entry_t.journal_entry_id')->select('f_journal_entry_lines_t.reference_id', 'f_journal_entry_lines_t.debit_amount', 'f_journal_entry_lines_t.credit_amount')->where('journal_type', 'MATERIAL RECEIVE')->where('journal_reference', $id)->get();
        $acc_collection = collect($acc_data);

        // $products=\DB::table('m_products_t')->whereIn('product_id',$_POST['bulk_product_id'])->get();
        //$collection = collect($products);

        $qa_data = \DB::select("SELECT w_qa_submitstage_line_t.*,m_products_t.*  FROM `w_qa_submitstage_line_t` join m_products_t on m_products_t.product_id=w_qa_submitstage_line_t.product_id WHERE w_qa_submitstage_line_t.`qa_submitstage_trx_hdr_id` ='" . $qa_submit[0]->qa_submitstage_trx_hdr_id . "'");


        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();
        $tkey = 0;
        $overallcost = 0;



        foreach ($qa_data as $k1 => $val1) {

          error_reporting(0);
          $filtered = $acc_collection->where('reference_id', $val1->product_id);
          $filtered->all();
          $credit_amount = 0;
          foreach ($filtered as $v1) {
            $credit_amount += $v1->credit_amount;
            // break;
          }





          $pro_qty = $val1->production_qty + $val1->exceed_qty;
          $return_cost = 0;
          if ($val1->return_qty != '' && $val1->return_qty != 0) {

            $cost = round($credit_amount / $val1->qty, 2);

            $return_cost = ($val1->return_qty) * ($cost);

            $overallcost += $return_cost;


            /*Journal Entry For Return material start*/
            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
            $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
            $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
            $journal_lines_data[$tkey]['reference_id'] = $val1->product_id;
            $journal_lines_data[$tkey]['product_qty'] = $val1->return_qty;
            $journal_lines_data[$tkey]['batch_number'] = $job_value->batch_no;
            $journal_lines_data[$tkey]['account_id'] = $val1->account_code_id;
            $journal_lines_data[$tkey]['debit_amount'] = $return_cost;
            $journal_lines_data[$tkey]['credit_amount'] = '';
            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
            /*Journal Entry For Return material end*/
            $tkey++;



          }
          if ($val1->scrap_qty != '' && $val1->scrap_qty != 0) {

            // $pro_cost=($credit_amount)-($return_cost);
            // $pro_qty=$pro_qty+$val1->scrap_qty;
            $cost = ($credit_amount / $val1->qty) * $val1->scrap_qty;

            $overallcost += $cost;

            /*Journal Entry For scrap material start*/
            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
            $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
            $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
            $journal_lines_data[$tkey]['reference_id'] = $val1->product_id;
            $journal_lines_data[$tkey]['product_qty'] = $val1->scrap_qty;
            $journal_lines_data[$tkey]['batch_number'] = $job_value->batch_no;
            $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->scrab;
            $journal_lines_data[$tkey]['debit_amount'] = $cost;
            $journal_lines_data[$tkey]['credit_amount'] = '';
            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
            /*Journal Entry For scrap material end*/
            $tkey++;
          }

        }

        //dd($filtered);

        /* Journal Entry For Production Control Account */

        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->qatrx_date;
        $journal_lines_data[$tkey]['reference_source'] = "";
        $journal_lines_data[$tkey]['reference_id'] = '';
        $journal_lines_data[$tkey]['product_qty'] = '';
        $journal_lines_data[$tkey]['batch_number'] = '';

        $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->packingcontrol_accid;


        $journal_lines_data[$tkey]['debit_amount'] = '';
        $journal_lines_data[$tkey]['credit_amount'] = $overallcost;
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->qatrx_date . " 12:12:00";
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
        /*Journal Entry For scrap material end*/
        $tkey++;




        ksort($journal_lines_data);

        //dump($journal_lines_data);
        \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);


      }
      //dump($journal_lines_data);
    }
    if (count($data) > 0) {
      dd(count($data) . " FG Entries Sync Completed");
    } else {
      dd("There Is No More FG Entries to Sync");
    }
    //return redirect('company');


  }

  public function productsyncsfg(Createuser $createuser)
  {
    $data = \DB::select("select * from i_qoh_detail_t join w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id and w_jobcard_hdr_t.temp_status=0 and w_jobcard_hdr_t.product_id=i_qoh_detail_t.product_id join m_products_t ON m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=4 where  date(i_qoh_detail_t.created_at)>='2022-04-01' group by i_qoh_detail_t.job_id  order by date(i_qoh_detail_t.created_at) asc LIMIT 500");
    //dd($data);
//$data=array();

    //dd($data);
//$data= \DB::select("SELECT i_qoh_detail_t.batch_number, w_jobcard_hdr_t.*,m_products_t.*  FROM `i_qoh_detail_t` JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id JOIN m_products_t on
//m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=1 WHERE i_qoh_detail_t.`job_process` != ''  and i_qoh_detail_t.qoh_source!='Return Store Move' limit 8,20000");
    //dd(($data));
    $employee_details = \DB::table('hr_employee_payproposal')->select('hr_employee_payproposal.employee_id', "hr_employee_payproposal.gross_pay")->get();
    // dd($data);
    $i = 351;
    $j = 500;
    $employee_details = collect($employee_details);
    foreach ($data as $key => $job_value) {
      /* Accounts Entry Start :Isac Naveen*/
      //  $products=\DB::table('m_products_t')->whereIn('product_id',$_POST['bulk_product_id'])->get();
      // $collection = collect($products);

      $id = $job_value->w_jobs_hdr_id;

      \DB::select("update w_jobcard_hdr_t set temp_status=1 where w_jobs_hdr_id='$id'");

      $receive_data = \DB::select("SELECT w_materialreceive_line_t.*,m_products_t.*  FROM `w_materialreceive_hdr_t`  join w_materialreceive_line_t on w_materialreceive_line_t.w_materialreceive_hdr_id= w_materialreceive_hdr_t.w_materialreceive_hdr_id join m_products_t ON m_products_t.product_id=w_materialreceive_line_t.product_id WHERE w_materialreceive_hdr_t.w_jobs_hdr_id =$id");


      if (count($receive_data) > 0) {

        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();

        //dd($products);


        $date = $job_value->job_completion_date;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $jobid = $id;
        $batch_number = $job_value->batch_number;
        $job_no = $job_value->job_no;
        $journal_name = "Material Receive-" . $job_no;

        $pro = $job_value->product_id;
        $process = '';
        $pp = $job_value->bom_process;
        $process_name = \DB::select("select m_material_bom_lines_t.* from m_material_bom_hdr_t JOIN m_material_bom_lines_t on m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id where m_material_bom_hdr_t.assembly_product_id=$pro group by m_material_bom_lines_t.process_level order by m_material_bom_lines_t.process_level asc");
        if ($pp == 'FINALPROCESS') {
          $c = count($process_name) - 1;
          $process = $process_name[$c]->process_level;
        } else {
          foreach ($process_name as $p) {
            //echo $p->process_level;
            if ($p->process_level != $pp) {

              $process = $p->process_level;
              //  echo $process;
            } else {
              break;
            }
          }
        }
        //dd($pp);                  /*Journal Header Insert*/

        $m_check = \DB::select("select * from f_journal_entry_t where journal_name='$journal_name' and journal_type='MATERIAL RECEIVE' and journal_reference='$jobid'");
        $jid = 0;
        if (count($m_check) <= 0) {
          $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','MATERIAL RECEIVE','$date','$jobid','APPROVED','$compy','$loc','$org')");
          $jid = DB::getPdo()->lastInsertId();
        }
        $tkey = 1;
        $total = 0;
        $journal_lines_data = array();

        foreach ($receive_data as $key => $value) {


          $batchnumber = explode(',', $value->batchnumber);
          $receiveqty = explode(',', $value->receiveqty);
          // dd($receiveqty);
          $pro_id = $value->product_id;

          $product_cost = 0;

          if ($value->product_group_id != 1) {
            foreach ($batchnumber as $k => $v) {
              $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id' and cost > 0 and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

              if (count($cost) > 0) {

                if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                  $receiveqty[$k] = $value->receive_qty;
                $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
              } else {

                $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id'  and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

                if (count($cost) > 0) {

                  $qoh_id = $cost[0]->qoh_detail_id;

                  $cost = \DB::select("select * from i_qoh_detail_t where qoh_detail_id<$qoh_id and cost >0  and product_id='$pro_id'  and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

                  if (count($cost) > 0) {

                    if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                      $receiveqty[$k] = $value->receive_qty;
                    $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                  } else {
                    $product_cost += 0;
                  }
                } else {
                  $product_cost += 0;
                }


              }
            }
          } else {
            foreach ($batchnumber as $k => $v) {
              $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and job_id<'$id' and job_process='$process' and product_id='$pro_id' and cost > 0 and job_process!='' and job_process!=0 ORDER BY qoh_detail_id  DESC");

              if (count($cost) > 0) {

                $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
              } else {
                $product_cost += 0;
                $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v'  and job_process='$process' and product_id='$pro_id' and cost > 0 ORDER BY `qoh_detail_id` desc ");
                if (count($cost) > 0) {

                  $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                } else {
                  $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and qoh_source='OPENSTOCK' and product_id='$pro_id' and cost > 0");
                  if (count($cost) > 0) {

                    $product_cost += round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                  }
                }

              }
            }
          }

          $total += $product_cost;
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $job_value->job_completion_date;
          $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
          $journal_lines_data[$tkey]['reference_id'] = $pro_id;
          $journal_lines_data[$tkey]['product_qty'] = $value->receive_qty;
          $journal_lines_data[$tkey]['batch_number'] = $value->batchnumber;
          if ($value->product_group_id == 1) {
            $journal_lines_data[$tkey]['account_id'] = $value->control_account_id;
          } else {
            $journal_lines_data[$tkey]['account_id'] = $value->account_code_id;
          }
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $product_cost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $job_value->job_completion_date . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $job_value->job_completion_date . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
          $tkey++;


        }
        $tkey = 0;
        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $job_value->job_completion_date;
        $journal_lines_data[$tkey]['reference_source'] = "";
        $journal_lines_data[$tkey]['reference_id'] = "";
        $journal_lines_data[$tkey]['product_qty'] = "";
        $journal_lines_data[$tkey]['batch_number'] = "";
        if ($job_value->product_group_id == 1) {
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->packingcontrol_accid;
        } else {
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->production;
        }
        $journal_lines_data[$tkey]['debit_amount'] = $total;
        $journal_lines_data[$tkey]['credit_amount'] = '';
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = $job_value->job_completion_date . " 12:12:00";
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = $job_value->job_completion_date . " 12:12:00";
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

        ksort($journal_lines_data);
        //  dd($journal_lines_data);

        if ($jid != 0) {
          \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
        }
      }
      /* Accounts Entry End :Isac Naveen*/

      $qa_submit = \DB::select("SELECT *,date(updated_at) as d FROM `w_qa_submitstage_trx_t` WHERE `job_no`='$id'");

      if (count($qa_submit) > 0) {
        $journal_lines_data = array();
        $start_times = explode(",", ($qa_submit[0]->start_time));
        $end_times = explode(",", ($qa_submit[0]->end_time));
        sort($start_times);
        rsort($end_times);


        $date = $qa_submit[0]->d;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $jobid = $id;

        $journal_name = "Job Card Close-" . $job_no;
        /*Journal Header Insert*/

        $m_check = \DB::select("select * from f_journal_entry_t where journal_name='$journal_name' and journal_type='JOB CARD CLOSE' and journal_reference='$jobid'");
        $jid = 0;
        if (count($m_check) <= 0) {

          $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','JOB CARD CLOSE','$date','$jobid','APPROVED','$compy','$loc','$org')");
          $jid = DB::getPdo()->lastInsertId();
        }


        $acc_data = \DB::table('f_journal_entry_t')->join('f_journal_entry_lines_t', 'f_journal_entry_lines_t.journal_entry_id', '=', 'f_journal_entry_t.journal_entry_id')->select('f_journal_entry_lines_t.reference_id', 'f_journal_entry_lines_t.debit_amount', 'f_journal_entry_lines_t.credit_amount')->where('journal_type', 'MATERIAL RECEIVE')->where('journal_reference', $id)->get();
        $acc_collection = collect($acc_data);

        // $products=\DB::table('m_products_t')->whereIn('product_id',$_POST['bulk_product_id'])->get();
        //$collection = collect($products);

        $qa_data = \DB::select("SELECT w_qa_submitstage_line_t.*,m_products_t.*  FROM `w_qa_submitstage_line_t` join m_products_t on m_products_t.product_id=w_qa_submitstage_line_t.product_id WHERE w_qa_submitstage_line_t.`qa_submitstage_trx_hdr_id` ='" . $qa_submit[0]->qa_submitstage_trx_hdr_id . "'");


        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();
        $tkey = 1;
        $overallcost = 0;



        foreach ($qa_data as $k1 => $val1) {

          error_reporting(0);
          $filtered = $acc_collection->where('reference_id', $val1->product_id);
          $filtered->all();
          $credit_amount = 0;
          foreach ($filtered as $v1) {
            $credit_amount += $v1->credit_amount;
            // break;
          }





          $pro_qty = $val1->production_qty + $val1->exceed_qty;
          $return_cost = 0;
          $bat_no = '';
          $bat_n01 = '';
          if ($val1->return_qty != '' && $val1->return_qty != 0) {

            $cost = round($credit_amount / $val1->qty, 2);

            $return_cost = ($val1->return_qty) * ($cost);

            // $overallcost+=$credit_amount-$cost;


            /*Journal Entry For Return material start*/
            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
            $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->d;
            $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
            $journal_lines_data[$tkey]['reference_id'] = $val1->product_id;
            $journal_lines_data[$tkey]['product_qty'] = $val1->return_qty;
            $journal_lines_data[$tkey]['batch_number'] = $bat_no;
            $journal_lines_data[$tkey]['account_id'] = $val1->account_code_id;
            $journal_lines_data[$tkey]['debit_amount'] = $return_cost;
            $journal_lines_data[$tkey]['credit_amount'] = '';
            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->d . " 12:12:00";
            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->d . " 12:12:00";
            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
            /*Journal Entry For Return material end*/
            $tkey++;



          }
          if ($val1->scrap_qty != '' && $val1->scrap_qty != 0) {

            // $pro_cost=($credit_amount)-($return_cost);
            // $pro_qty=$pro_qty+$val1->scrap_qty;
            $cost = ($credit_amount / $val1->qty) * $val1->scrap_qty;

            $overallcost += $credit_amount - $return_cost - $cost;

            /*Journal Entry For scrap material start*/
            $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
            $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->d;
            $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
            $journal_lines_data[$tkey]['reference_id'] = $val1->product_id;
            $journal_lines_data[$tkey]['product_qty'] = $val1->scrap_qty;
            $journal_lines_data[$tkey]['batch_number'] = $bat_no;
            $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->scrab;
            $journal_lines_data[$tkey]['debit_amount'] = $cost;
            $journal_lines_data[$tkey]['credit_amount'] = '';
            $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
            $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->d . " 12:12:00";
            $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
            $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->d . " 12:12:00";
            $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
            $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
            /*Journal Entry For scrap material end*/
            $tkey++;
          } else {

            $overallcost += ($credit_amount) - ($return_cost);
          }
        }


        //dd($filtered);

        /* Journal Entry For Production Control Account */

        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->d;
        $journal_lines_data[$tkey]['reference_source'] = "";
        $journal_lines_data[$tkey]['reference_id'] = '';
        $journal_lines_data[$tkey]['product_qty'] = "";
        $journal_lines_data[$tkey]['batch_number'] = "";
        if ($job_value->product_group_id == 1) {

          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->packingcontrol_accid;
        } else {
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->production;
        }
        $journal_lines_data[$tkey]['debit_amount'] = '';
        $journal_lines_data[$tkey]['credit_amount'] = $acc_data[0]->debit_amount;
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->d . " 12:12:00";
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->d . " 12:12:00";
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
        /*Journal Entry For scrap material end*/
        $tkey++;
        /*Journal Entry For Labour Start*/


        $month = $month = date('m', strtotime($qa_submit[0]->d));
        $year = date('Y', strtotime($qa_submit[0]->d));
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        //dd($val1);
        $empl = explode(',', ($qa_submit[0]->job_assigned_to));
        $empl_working = explode(',', ($qa_submit[0]->working_hrs));
        $start_timess = explode(",", ($qa_submit[0]->start_time));
        $end_timess = explode(",", ($qa_submit[0]->end_time));
        $bat_no1 = $qa_submit[0]->batch_no;

        foreach ($empl as $k => $evalue) {
          $jc_date = $qa_submit[0]->d;
          $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal`  where employee_id='$evalue' and date(updated_at)<='$jc_date'");

          if (count($filtered) > 0) {
            $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
          } else {

            $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue' and date(updated_at)<='$jc_date' limit 1");

            if (count($filtered) > 0) {
              $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
            } else {

              $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue'  limit 1");
              if (count($filtered) > 0) {
                $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
              } else {

                //$hour_cost = 0;

                $filtered = \DB::select("SELECT * FROM f_expenses_t LEFT JOIN f_expenses_lines_t ON f_expenses_t.expense_id = f_expenses_lines_t.expense_id WHERE f_expenses_t.employee_id='$evalue' AND f_expenses_t.expense_type='EMPLOYEE' AND f_expenses_lines_t.expense_account_id='407' AND date(f_expenses_t.expense_date) <= '$jc_date' ORDER BY f_expenses_t.expense_id DESC LIMIT 1");

                if (count($filtered) > 0) {
                  $hour_cost = ($filtered[0]->expense_amount / $days) / 8;
                } else {

                  $hour_cost = 0;
                }

              }

            }

          }

          $start = strtotime($start_timess[$k]);
          $end = strtotime($end_timess[$k]);
          $total_hours = ($end - $start) / 60;


          $overallcost += $total_cost = round($total_hours * ($hour_cost / 60), 2);
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->d;
          $journal_lines_data[$tkey]['reference_source'] = "EMPLOYEE";
          $journal_lines_data[$tkey]['reference_id'] = $evalue;
          $journal_lines_data[$tkey]['product_qty'] = $qa_submit[0]->working_hrs;
          $journal_lines_data[$tkey]['batch_number'] = "";
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->labour;
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $total_cost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->d . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->d . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

          $tkey++;




        }

        /*Journal Entry For Labour End*/

        /* Journal Entry For Electricity start */

        $to_time = strtotime($start_times[0]);
        $from_time = strtotime($end_times[0]);
        $machine_time = round((abs($to_time - $from_time) / 60) / 60, 2);

        $machine_details = \DB::table('w_jobcard_hdr_t')->join('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'w_jobcard_hdr_t.machine_hdr_id')->select('w_machine_hdr_t.machine_hdr_id', 'w_machine_hdr_t.electricity_cost')->where('w_jobcard_hdr_t.w_jobs_hdr_id', $id)->get();
        //dd($machine_details);
        $overallcost += $cost = round($machine_details[0]->electricity_cost * $machine_time, 2);
        echo "$id - $overallcost<br>";
        if ($cost > 0) {
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->d;
          $journal_lines_data[$tkey]['reference_source'] = "MACHINE";
          $journal_lines_data[$tkey]['reference_id'] = $machine_details[0]->machine_hdr_id;
          $journal_lines_data[$tkey]['product_qty'] = $machine_time;
          $journal_lines_data[$tkey]['batch_number'] = "";
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->eletricity;
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $cost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->d . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->d . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

          $tkey++;
        }


        /* Journal Entry For Electricity end */
        /* Journal Entry For WIP Account */
        $tkey = 0;
        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
        $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->d;
        $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
        $journal_lines_data[$tkey]['reference_id'] = $job_value->product_id;
        $journal_lines_data[$tkey]['product_qty'] = $qa_submit[0]->production_qty;
        $journal_lines_data[$tkey]['batch_number'] = $bat_no1;
        $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->wip;
        $journal_lines_data[$tkey]['debit_amount'] = $overallcost;
        $journal_lines_data[$tkey]['credit_amount'] = '';
        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->d . " 12:12:00";
        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
        $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->d . " 12:12:00";
        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

        ksort($journal_lines_data);
        //exit;
//dd($journal_lines_data);

        if ($jid != 0) {
          \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);



          $cost = $overallcost / $qa_submit[0]->production_qty;
          if ($qa_submit[0]->production_qty == 0) {
            $cost = 0;
          }
          \DB::select("update i_qoh_detail_t set cost='$cost' where job_id='$id' and product_id='$pro'");


          /* Journal Entry For Production Product When -> Quality Check is No */


          $journal_name = "Quality Approve";
          $date = $qa_submit[0]->d;
          $org = \Session::get('organization');
          $loc = \Session::get('location');
          $compy = \Session::get('companyid');
          $jobid = $id;
          $bat_no1 = $qa_submit[0]->batch_no;

          $journal_name = "Quality Approve-" . $job_no;
          /*Journal Header Insert*/
          $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','QUALITY APPROVE','$date','$jobid','APPROVED','$compy','$loc','$org')");
          $jid = DB::getPdo()->lastInsertId();

          //   $products=\DB::table('m_products_t')->where('product_id',$_POST['product_id'])->get();

          $tkey = 0;
          $journal_lines_data = array();
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->d;
          $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
          $journal_lines_data[$tkey]['reference_id'] = $job_value->product_id;
          $journal_lines_data[$tkey]['product_qty'] = $qa_submit[0]->production_qty;
          $journal_lines_data[$tkey]['batch_number'] = $bat_no1;

          if ($job_value->product_group_id == 1) {
            $journal_lines_data[$tkey]['account_id'] = $job_value->control_account_id;
          } else {
            $journal_lines_data[$tkey]['account_id'] = $job_value->account_code_id;
          }

          $journal_lines_data[$tkey]['debit_amount'] = $overallcost;
          $journal_lines_data[$tkey]['credit_amount'] = '';
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->d . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->d . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

          $tkey++;
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $qa_submit[0]->d;
          $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
          $journal_lines_data[$tkey]['reference_id'] = $job_value->product_id;
          $journal_lines_data[$tkey]['product_qty'] = $qa_submit[0]->production_qty;
          $journal_lines_data[$tkey]['batch_number'] = $bat_no1;
          $journal_lines_data[$tkey]['account_id'] = $production_acc[0]->wip;
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $overallcost;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = $qa_submit[0]->d . " 12:12:00";
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = $qa_submit[0]->d . " 12:12:00";
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

          \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

        }


        /*End*/


      }
    }
    if (count($data) > 0) {
      dd(count($data) . " SFG Entries Sync Completed");
    } else {
      dd("There Is No More SFG Entries to Sync");
    }
    //dd(count($data)." Entries Sync Completed");

    //return redirect('company');

  }

  public function shipmentssync(Createuser $createuser)
  {

    $data = \DB::select("SELECT s_dispatch_hdr_t.*,s_invoice_hdr_t.invoice_number,s_invoice_hdr_t.invoice_type,s_invoice_hdr_t.invoice_date FROM `s_invoice_hdr_t` join s_dispatch_hdr_t ON s_invoice_hdr_t.reference_source_id like concat('%',s_dispatch_hdr_t.so_dispatch_hdr_id ,'%') and s_dispatch_hdr_t.dispatch_status='SHIPPED' and s_dispatch_hdr_t.temp_status=0 group by s_dispatch_hdr_t.so_dispatch_hdr_id limit 500");

    //  dd(($data));

    foreach ($data as $val) {
      $id = $val->so_dispatch_hdr_id;
      $dis_data1 = \DB::select("SELECT s_dispatched_qty_t.issue_qoh,s_dispatched_qty_t.batch_no,s_dispatch_lines_t.product_id,m_products_t.account_code_id FROM s_dispatch_lines_t JOIN s_dispatched_qty_t on (s_dispatched_qty_t.so_dispatch_hdr_id=s_dispatch_lines_t.so_dispatch_hdr_id and s_dispatched_qty_t.so_dispatch_line_id=s_dispatch_lines_t.so_dispatch_line_id) JOIN m_products_t on m_products_t.product_id=s_dispatch_lines_t.product_id where s_dispatch_lines_t.so_dispatch_hdr_id='$id' and s_dispatched_qty_t.issue_qoh!=0 ORDER by s_dispatch_lines_t.product_id asc");
      //Invoice Entry    
      $alrjo = \DB::select("select * from f_journal_entry_t where journal_type='SHIPMENT CONFIRM' and journal_reference=" . $id);

      //dd($alrjo);

      if (COUNT($alrjo) <= 0) {
        //dd($dis_data1);
        $ship_invno = "SHIP-" . $val->invoice_number;
        $invdate = $val->invoice_date;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        \DB::select("update s_dispatch_hdr_t set temp_status=1 where so_dispatch_hdr_id='$id'");
        $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$ship_invno','SHIPMENT CONFIRM','$invdate','$id','APPROVED','$compy','$loc','$org')");

        $jid = \DB::getPdo()->lastInsertId();

        $product = '';
        $acc_id = '';
        $rate = 0;
        $total = 0;
        $tkey = 1;
        $journal_lines_data = array();
        foreach ($dis_data1 as $key => $value) {
          $product = $value->product_id;
          $acc_id = $value->account_code_id;
          $rate = 0;
          $bat = $value->batch_no;

          $cost = \DB::select("select i_qoh_detail_t.cost from i_qoh_detail_t where product_id='$product' and batch_number='$bat' and (job_process='FINALPROCESS' or qoh_source='OPENSTOCK' or qoh_source='PURCHASE_STOREMOVE')");
          if (count($cost) > 0) {
            $rate = $cost[0]->cost * $value->issue_qoh;
            $total = $total + $rate;
          }
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $val->invoice_date;
          $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
          $journal_lines_data[$tkey]['reference_id'] = $product;
          $journal_lines_data[$tkey]['account_id'] = $acc_id;
          $journal_lines_data[$tkey]['debit_amount'] = '';
          $journal_lines_data[$tkey]['credit_amount'] = $rate;
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
          ;
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
          $journal_lines_data[$tkey]['product_qty'] = $value->issue_qoh;
          $journal_lines_data[$tkey]['batch_number'] = $value->batch_no;

          $tkey++;


        }

        $accntsettings = \DB::table('f_account_setting_t')->where('module_name', '=', 'salesaccount')->select('sales_account_id', 'cogs_account_id')->get();
        if ($val->invoice_type == "SAMPLE") {
          $cogs_account_id = 85;
        } else {
          $cogs_account_id = $accntsettings[0]->cogs_account_id;
        }
        if ($total > 0) {
          $tkey = 0;
          $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
          $journal_lines_data[$tkey]['journal_date'] = $val->invoice_date;
          $journal_lines_data[$tkey]['reference_source'] = "";
          $journal_lines_data[$tkey]['reference_id'] = "";
          $journal_lines_data[$tkey]['account_id'] = $cogs_account_id;

          $journal_lines_data[$tkey]['debit_amount'] = $total;
          $journal_lines_data[$tkey]['credit_amount'] = '';
          $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
          $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
          $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
          $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
          ;
          $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
          $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
          $journal_lines_data[$tkey]['product_qty'] = "";
          $journal_lines_data[$tkey]['batch_number'] = "";

          \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
        }
        \DB::select("update s_dispatch_hdr_t set temp_status=1 where so_dispatch_hdr_id='$id'");

      } else {
        \DB::select("update s_dispatch_hdr_t set temp_status=1 where so_dispatch_hdr_id='$id'");
      }
    }
    if (count($data) > 0) {
      dd(count($data) . " Shipment Confirm Entries Sync Completed");
    } else {
      dd("There Is No More Shipment Confirm Entries to Sync");
    }
    //dd(count($data)." Entries Sync Completed");

  }

  public function shipmentsrevsync()
    {
        $compy=\Session::get('companyid'); 
 
    $data=\DB::select("SELECT * FROM `so_rma_hdr_t` where return_status='APPROVED' and payment_status='0' and company_id = $compy and return_date >= '2025-04-01' limit 4000");
      
     //dd(count($data));
      
foreach($data as $val)
{
    
 $id=$val->so_rma_hdr_id;
 $id1=$val->reference_source_id; 

 $dis_data1 = \DB::select("SELECT so_rma_hdr_t.so_rma_hdr_id,so_rma_hdr_t.rma_ref_no,so_rma_hdr_t.return_date,so_rma_hdr_t.reference_source_id,so_rma_lines_t.*,m_products_t.account_code_id FROM `so_rma_lines_t` join m_products_t on m_products_t.product_id=so_rma_lines_t.product_id 
join so_rma_hdr_t ON so_rma_hdr_t.so_rma_hdr_id=so_rma_lines_t.so_rma_hdr_id where so_rma_hdr_t.return_date >='2025-04-01' and so_rma_hdr_t.so_rma_hdr_id='$id'");


     //dd($id1);
                    //Invoice Entry    
$alrjo=\DB::select("select * from f_journal_entry_t where journal_type='SHIPMENT CONFIRM' and journal_date >='2025-04-01' and journal_reference='$id1'");
//dd($alrjo);
if(COUNT($alrjo)>=0){
    //dd('test');
       //dd($dis_data1);
        $ship_invno = "SHIP-RTN-".$val->reference_no."-".$val->rma_ref_no;
        $invdate=$val->return_date;
       $org=\Session::get('organization');
       $loc=\Session::get('location');
       $compy=\Session::get('companyid'); 
       \DB::select("update so_rma_hdr_t set payment_status=1 where so_rma_hdr_id='$id'");
        $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$ship_invno','SHIPMENT RETURN','$invdate','$id','APPROVED','$compy','$loc','$org')");

        $jid = \DB::getPdo()->lastInsertId();

$product='';
$acc_id='';
$rate=0;
$total=0;
$tkey=1;
$journal_lines_data=array();
 foreach ($dis_data1 as $key => $value) 
 {
          $product=$value->product_id; 
          $acc_id=$value->account_code_id;
$rate=0;
          $bat=$value->batch_number;
        //dd($bat);
       $cost= \DB::select("SELECT COALESCE((SELECT round(sum(cost)/count(i_qoh_detail_t.qoh_detail_id),2) FROM i_qoh_detail_t WHERE qoh_source = 'SALES RETURN-MOVETOINVENTORY' AND cost > 0 AND product_id = '$product' AND batch_number = '$value->batch_number' AND company_id = '$compy' AND DATE(created_at) <= '$invdate' GROUP BY product_id, batch_number ),( SELECT round(sum(cost)/count(i_qoh_detail_t.qoh_detail_id),2) FROM i_qoh_detail_t WHERE product_id = '$product' AND batch_number = '$value->batch_number' AND company_id = '$compy' AND cost > 0 AND DATE(created_at) <= '$invdate' ORDER BY created_at DESC LIMIT 1)) AS rate");
        //dd($cost);
if(count($cost)>0)
{
        $rate=$cost[0]->rate*$value->returnqty;
        //dd($rate);
        $total=$total+$rate;
}
            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$val->return_date;
            $journal_lines_data[$tkey]['reference_source']="PRODUCT";
            $journal_lines_data[$tkey]['reference_id']=$product;
            $journal_lines_data[$tkey]['product_qty']=$value->returnqty;
            $journal_lines_data[$tkey]['batch_number']=$value->batch_number;
            $journal_lines_data[$tkey]['account_id']=$acc_id;
            $journal_lines_data[$tkey]['debit_amount']=$rate;
            $journal_lines_data[$tkey]['credit_amount']='';
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('location');
            $journal_lines_data[$tkey]['company_id']=\Session::get('companyid');

   $tkey++;


 }

$accntsettings = \DB::table('f_account_setting_t')->where('module_name','=','salesaccount')->select('sales_account_id','cogs_account_id','sample_account_id')->get();

$type= $val->reference_no;
//dd($type);

$cogs_account_id = str_contains($type, 'SOINVSM')
    ? $accntsettings[0]->sample_account_id
    : $accntsettings[0]->cogs_account_id;

//$cogs_account_id = $accntsettings[0]->cogs_account_id;
    //dd($cogs_account_id);
if($total>0)
{
             $tkey=0; 
            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$val->return_date;
            $journal_lines_data[$tkey]['reference_source']="";
            $journal_lines_data[$tkey]['reference_id']="";
            $journal_lines_data[$tkey]['product_qty']='';
            $journal_lines_data[$tkey]['batch_number']='';
            $journal_lines_data[$tkey]['account_id']=$cogs_account_id;
            
            $journal_lines_data[$tkey]['debit_amount']='';
            $journal_lines_data[$tkey]['credit_amount']=$total;
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('location');
            $journal_lines_data[$tkey]['company_id']=\Session::get('companyid');

    
}
//dd($journal_lines_data);
 \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);  
  

}
}

if(count($data)>0){
dd(count($data)." Shipment Return Entries Sync Completed");
}else{
dd("There Is No More Shipment Return Entries to Sync");    
}
//dd(count($data)." Entries Sync Completed");
     
}  


  public function qohprocessinsert()
  {
    dd("dsf");

    $jobprotbl = \DB::select("select * from w_jobcard_process_details_t left join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=w_jobcard_process_details_t.job_id where  date(w_jobcard_process_details_t.process_date) between '2021-09-13' and '2021-09-27' and  w_jobcard_process_details_t.qohinsrt=0 order by w_jobcard_process_details_t.job_process_id ASC");
    //   dd($jobprotbl);
    foreach ($jobprotbl as $key => $value) {
      //  dd($value);
      $jobid = $value->w_jobs_hdr_id;
      //   $jobprotbl=\DB::select("select * from w_jobcard_process_details_t where job_id='$jobid' and qohinsrt=0 order by job_process_id DESC");

      $pid = $value->product_id;
      //dd($pid);
      $prcs = $value->process_level;

      $bomtbl = \DB::select("select m_material_bom_lines_t.process_level,m_material_bom_lines_t.process_name from m_material_bom_lines_t left join m_material_bom_hdr_t on m_material_bom_hdr_t.material_bom_hdr_id=m_material_bom_lines_t.material_bom_hdr_id where m_material_bom_hdr_t.assembly_product_id=$pid group by `m_material_bom_lines_t`.`process_level` order by `m_material_bom_lines_t`.`material_bom_line_id` ASC");
      //  dd($bomtbl);
      $process = '';
      $process_name = '';
      foreach ($bomtbl as $k => $v) {
        if ($prcs == $v->process_level) {
          break;
        } else {
          $process = $v->process_level;
          $process_name = $v->process_name;
        }
      }
      // dd($process);
      if ($process != '') {

        $group = \DB::select('select * from m_products_t where product_id=' . $pid);
        //dd($group);
        $trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'JOB ISSUE')->get();
        $trsns = json_decode(json_encode($trsns), true);

        /* insert data into mtl transaction tbl */
        $data2['trx_source_type_id'] = $trsns[0]['transaction_source_id'];
        $data2['trx_action_id'] = $trsns[0]['transaction_action_id'];
        $data2['trx_type_id'] = $trsns[0]['transaction_type_id'];
        $data2['trx_source_hdr_id'] = $jobid;
        $data2['trx_source_line_id'] = "";
        $data2['line_number'] = 1;
        $data2['product_id'] = $pid;
        $data2['trx_uom'] = $group[0]->primary_uom_id;
        $data2['trx_date'] = date('Y-m-d');
        $data2['created_by'] = \Session::get('id');
        $data2['created_at'] = date('Y-m-d');
        $data2['organization_id'] = \Session::get('organization');
        $data2['location_id'] = \Session::get('location');
        $data2['company_id'] = \Session::get('companyid');
        $data2['subinventory_id'] = $value->subinventory_id;
        $data2['locator_id'] = $value->locator_id;
        $data2['trx_qty'] = -$value->move_qty;
        $mtlid = \DB::table('m_material_trx_t')->insertGetId($data2);

        $dataqoh1['job_id'] = $jobid;
        $dataqoh1['product_id'] = $pid;
        $dataqoh1['qoh_uom_code_id'] = $group[0]->primary_uom_id;
        $dataqoh1['create_trx_id'] = $mtlid;
        $dataqoh1['qoh_source'] = "JOB ISSUE";
        $dataqoh1['job_process'] = $process;
        $dataqoh1['job_process_name'] = $process_name;
        $dataqoh1['created_by'] = \Session::get('id');
        $dataqoh1['created_at'] = date('Y-m-d');
        $dataqoh1['organization_id'] = \Session::get('organization');
        $dataqoh1['location_id'] = \Session::get('location');
        $dataqoh1['company_id'] = \Session::get('companyid');
        $dataqoh1['qualitystatus'] = 1;
        $dataqoh1['create_trx_id'] = $mtlid;
        $dataqoh1['qoh_trx_qty'] = -$value->move_qty;
        $dataqoh1['subinventory_id'] = $value->subinventory_id;
        $dataqoh1['job_move_date'] = date('Y-m-d', strtotime($value->process_date));
        $dataqoh1['locator_id'] = $value->locator_id;
        $dataqoh1['batch_number'] = $value->batch_no;
        $qohid = \DB::table('i_qoh_detail_t')->insertGetId($dataqoh1);
        //echo ($qohid."-");

      }
      // }   
      //  }  
      \DB::select("update w_jobcard_process_details_t set qohinsrt=1 where job_process_id=" . $value->job_process_id);

    }
    // \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id',$jobid)->update(['store_move_qty' => $mvqty1]);

    dd("over");
  }

  public function productsyncfgcost(Createuser $createuser)
  {
    $data = \DB::select("SELECT w_jobcard_process_details_t.*,w_jobcard_hdr_t.batch_no,w_jobcard_hdr_t.job_no,w_jobcard_hdr_t.product_id,m_products_t.concatenated_product FROM `w_jobcard_process_details_t` join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=w_jobcard_process_details_t.job_id join m_products_t on m_products_t.product_id=w_jobcard_hdr_t.product_id where w_jobcard_process_details_t.cost_status=0 and w_jobcard_process_details_t.jo_status=1 ");

    //dd($data);

    foreach ($data as $job_value) {


      $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();

      \DB::select("update w_jobcard_process_details_t set cost_status=1 where job_process_id='" . $job_value->job_process_id . "'");

      //dd($products);

      $id = $job_value->job_id;
      $date = $job_value->process_date;
      $org = \Session::get('organization');
      $loc = \Session::get('location');
      $compy = \Session::get('companyid');
      $jobid = $id;
      $productid = $job_value->product_id;
      $productname = $job_value->concatenated_product;
      $batch_number = $job_value->batch_no;
      $job_no = $job_value->job_no;
      // $journal_name="Material Receive-".$job_no;

      $pro = $job_value->product_id;

      //dd($pp);                  /*Journal Header Insert*/
      $check = \DB::select("select * from r_job_cost_hdr_tbl where job_id='$jobid'");
      if (count($check) <= 0) {
        $journalhdr = \DB::insert("insert into r_job_cost_hdr_tbl(job_id,job_number,job_date,batch_number,product_id,product_name)values('$jobid','$job_no','$date','$batch_number','$productid','$productname')");

        $jid = \DB::getPdo()->lastInsertId();
      } else {
        $jid = $check[0]->r_job_cost_hdr_id;
      }

      $pro = $job_value->product_id;
      $process = '';
      $pp = $job_value->process_level;
      $process_name = \DB::select("select m_material_bom_lines_t.* from m_material_bom_hdr_t JOIN m_material_bom_lines_t on m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id where m_material_bom_hdr_t.assembly_product_id=$pro group by m_material_bom_lines_t.process_level order by m_material_bom_lines_t.process_level asc");
      if ($pp == 'FINALPROCESS') {
        $c = count($process_name) - 1;
        $process = $process_name[$c]->process_level;
        $p_name = $process_name[$c]->process_name;
      } else if ($pp != 'PROCESS-1') {
        foreach ($process_name as $p) {
          //echo $p->process_level;
          if ($p->process_level != $pp) {

            $process = $p->process_level;
            $p_name = $p->process_name;
            //  echo $process;
          } else {
            break;
          }
        }
      }




      //dd($process);  

      $qa_submit = 0;

      if ($qa_submit == 0) {
        $journal_lines_data = array();
        $start_times = explode(",", ($job_value->process_start_date));
        $end_times = explode(",", ($job_value->process_end_date));
        sort($start_times);
        rsort($end_times);



        /*Journal Header Insert*/
        // $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','JOB CARD STORE MOVE','$date','$jobid','APPROVED','$compy','$loc','$org')");
        //    $jid =DB::getPdo()->lastInsertId();

        $pac_data = \DB::select("SELECT concatenated_product,product_group_id,m_material_bom_lines_t.component_product_id,m_material_bom_lines_t.component_qty,w_materialreceive_line_t.receive_qty,w_materialreceive_line_t.batchnumber,f_journal_entry_lines_t.credit_amount FROM `w_jobcard_hdr_t` join m_material_bom_hdr_t on m_material_bom_hdr_t.assembly_product_id=w_jobcard_hdr_t.product_id join m_material_bom_lines_t on m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id and m_material_bom_lines_t.process_level='$pp' join m_products_t on m_products_t.product_id=m_material_bom_lines_t.component_product_id join w_materialreceive_hdr_t on w_materialreceive_hdr_t.w_jobs_hdr_id=w_jobcard_hdr_t.w_jobs_hdr_id join w_materialreceive_line_t on w_materialreceive_line_t.w_materialreceive_hdr_id=w_materialreceive_hdr_t.w_materialreceive_hdr_id and w_materialreceive_line_t.product_id=m_material_bom_lines_t.component_product_id join f_journal_entry_t on f_journal_entry_t.journal_reference=w_jobcard_hdr_t.w_jobs_hdr_id and f_journal_entry_t.journal_type='MATERIAL RECEIVE' join f_journal_entry_lines_t on f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id and f_journal_entry_lines_t.reference_id=m_material_bom_lines_t.component_product_id where w_jobcard_hdr_t.w_jobs_hdr_id=$jobid");

        //dd($pac_data);

        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();
        $tkey = 0;
        $overallcost = 0;
        $cost = 0;
        //dd("select cost from i_qoh_detail_t where job_id='$id' and product_id='$pro' and job_process='$process'");
        if ($process != '') {
          $process_cost = \DB::select("select cost from i_qoh_detail_t where job_id='$id' and product_id='$pro' and job_process='$process'");

          if (count($process_cost) == 0) {
            $c = count($process_name) - 2;
            $process = $process_name[$c]->process_level;
            $p_name = $process_name[$c]->process_name;
            $process_cost = \DB::select("select cost from i_qoh_detail_t where job_id='$id' and product_id='$pro' and job_process='$process'");

            if (count($process_cost) == 0) {
              $c = count($process_name) - 3;
              $process = $process_name[$c]->process_level;
              $p_name = $process_name[$c]->process_name;
              $process_cost = \DB::select("select cost from i_qoh_detail_t where job_id='$id' and product_id='$pro' and job_process='$process'");

            }
            if (count($process_cost) == 0) {
              $c = count($process_name) - 4;
              $process = $process_name[$c]->process_level;
              $p_name = $process_name[$c]->process_name;
              $process_cost = \DB::select("select cost from i_qoh_detail_t where job_id='$id' and product_id='$pro' and job_process='$process'");

            }
            if (count($process_cost) == 0) {
              $c = count($process_name) - 5;
              $process = $process_name[$c]->process_level;
              $p_name = $process_name[$c]->process_name;
              $process_cost = \DB::select("select cost from i_qoh_detail_t where job_id='$id' and product_id='$pro' and job_process='$process'");

            }
            if (count($process_cost) == 0) {
              $c = count($process_name) - 6;
              $process = $process_name[$c]->process_level;
              $p_name = $process_name[$c]->process_name;
              $process_cost = \DB::select("select cost from i_qoh_detail_t where job_id='$id' and product_id='$pro' and job_process='$process'");

            }
            if (count($process_cost) == 0) {
              $c = count($process_name) - 7;
              $process = $process_name[$c]->process_level;
              $p_name = $process_name[$c]->process_name;
              $process_cost = \DB::select("select cost from i_qoh_detail_t where job_id='$id' and product_id='$pro' and job_process='$process'");

            }
            if (count($process_cost) == 0) {
              $c = count($process_name) - 8;
              $process = $process_name[$c]->process_level;
              $p_name = $process_name[$c]->process_name;
              $process_cost = \DB::select("select cost from i_qoh_detail_t where job_id='$id' and product_id='$pro' and job_process='$process'");

            }

          }

          $process_cost1 = $process_cost[0]->cost * $job_value->move_qty;
          $overallcost += $process_cost1;
          $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
          $journal_lines_data[$tkey]['type'] = "PRODUCT";
          $journal_lines_data[$tkey]['product_id'] = $pro;
          $journal_lines_data[$tkey]['product_name'] = $job_value->concatenated_product . "-" . $process . "-" . $p_name;
          $journal_lines_data[$tkey]['batch_number'] = $job_value->batch_no;
          $journal_lines_data[$tkey]['product_group'] = 1;
          $journal_lines_data[$tkey]['rate'] = $process_cost[0]->cost;
          $journal_lines_data[$tkey]['total'] = $process_cost1;
          $journal_lines_data[$tkey]['qty'] = $job_value->move_qty;
          $tkey++;
        }


        foreach ($pac_data as $val) {
          $cost = round((($val->component_qty) * ($job_value->move_qty)) * (($val->credit_amount / $val->receive_qty)), 2);
          $pro_id = $val->component_product_id;

          //dd($cost);

          $overallcost += $cost;
          //dd($filtered);

          /* Journal Entry For Production Control Account */

          $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
          $journal_lines_data[$tkey]['type'] = "PRODUCT";
          $journal_lines_data[$tkey]['product_id'] = $pro_id;
          $journal_lines_data[$tkey]['product_name'] = $val->concatenated_product;
          $journal_lines_data[$tkey]['batch_number'] = $val->batchnumber;
          $journal_lines_data[$tkey]['product_group'] = $val->product_group_id;
          $journal_lines_data[$tkey]['rate'] = round((($val->credit_amount / $val->receive_qty)), 2);
          $journal_lines_data[$tkey]['total'] = $cost;
          $journal_lines_data[$tkey]['qty'] = round((($val->component_qty) * ($job_value->move_qty)), 2);
          $tkey++;
          /*Journal Entry For scrap material end*/
        }





        /*Journal Entry For Labour Start*/


        $month = $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        //dd($val1);
        $empl = explode(',', ($job_value->jobassigned_to));
        $empl_working = explode(',', ($job_value->working_hrs));

        $start_timess = explode(",", ($job_value->start_time));
        $end_timess = explode(",", ($job_value->end_time));


        foreach ($empl as $k => $evalue) {
          $jc_date = $date;
          $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal`  where employee_id='$evalue' and date(updated_at)<='$jc_date'");
          $empname = \DB::select("SELECT * FROM `hr_employee_t`  where employee_id='$evalue'");

          if (count($filtered) > 0) {

            $hour_cost = ($filtered[0]->gross_pay / $days) / 8;

          } else {

            $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue' and date(updated_at)<='$jc_date' limit 1");

            if (count($filtered) > 0) {
              $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
            } else {

              $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue'  limit 1");
              if (count($filtered) > 0) {
                $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
              } else {

                //$hour_cost = 0;
                $filtered = \DB::select("SELECT * FROM f_expenses_t LEFT JOIN f_expenses_lines_t ON f_expenses_t.expense_id = f_expenses_lines_t.expense_id WHERE f_expenses_t.employee_id='$evalue' AND f_expenses_t.expense_type='EMPLOYEE' AND f_expenses_lines_t.expense_account_id='407' AND date(f_expenses_t.expense_date) <= '$jc_date' ORDER BY f_expenses_t.expense_id DESC LIMIT 1");

                if (count($filtered) > 0) {
                  $hour_cost = ($filtered[0]->expense_amount / $days) / 8;
                } else {

                  $hour_cost = 0;
                }

              }

            }

          }


          if (count($empname) > 0) {

            $start = strtotime($start_timess[$k]);
            $end = strtotime($end_timess[$k]);
            $total_hours = ($end - $start) / 60;


            $overallcost += $total_cost = round($total_hours * ($hour_cost / 60), 2);

            $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
            $journal_lines_data[$tkey]['type'] = "EMPLOYEE";
            $journal_lines_data[$tkey]['product_id'] = $evalue;
            $journal_lines_data[$tkey]['product_name'] = $empname[0]->employee_number . '-' . $empname[0]->first_name;
            $journal_lines_data[$tkey]['batch_number'] = '';
            $journal_lines_data[$tkey]['product_group'] = '';
            $journal_lines_data[$tkey]['rate'] = $hour_cost;
            $journal_lines_data[$tkey]['total'] = $total_cost;
            $journal_lines_data[$tkey]['qty'] = $total_hours;

            $tkey++;



          }
        }

        /*Journal Entry For Labour End*/

        /* Journal Entry For Electricity start */

        $to_time = strtotime($start_times[0]);
        $from_time = strtotime($end_times[0]);
        $machine_time = round((abs($to_time - $from_time) / 60) / 60, 2);
        $mac_id = $job_value->machine_id;

        $machine_details = \DB::select("SELECT * FROM `w_machine_hdr_t` where machine_hdr_id='$mac_id'");
        //dd($machine_details);
        $overallcost += $cost = round($machine_details[0]->electricity_cost * $machine_time, 2);
        if ($cost > 0) {
          $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
          $journal_lines_data[$tkey]['type'] = "MACHINE";
          $journal_lines_data[$tkey]['product_id'] = $machine_details[0]->machine_hdr_id;
          $journal_lines_data[$tkey]['product_name'] = $machine_details[0]->machine_code . '-' . $machine_details[0]->machine_name;
          $journal_lines_data[$tkey]['batch_number'] = '';
          $journal_lines_data[$tkey]['product_group'] = '';
          $journal_lines_data[$tkey]['rate'] = $machine_details[0]->electricity_cost;
          $journal_lines_data[$tkey]['total'] = $cost;
          $journal_lines_data[$tkey]['qty'] = $machine_time;

          $tkey++;
        }

        \DB::table('r_job_cost_lines_tbl')->insert($journal_lines_data);


        /* Journal Entry For Electricity end */



      }
    }
    if (count($data) > 0) {
      dd(count($data) . " FG Entries Sync Completed");
    } else {
      dd("There Is No More FG Entries to Sync");
    }
    //return redirect('company');


  }
  public function productsyncfgcostold(Createuser $createuser)
  {
    $data = \DB::select("select w_jobcard_hdr_t.bom_process,w_jobcard_hdr_t.product_id,w_jobcard_hdr_t.w_jobs_hdr_id,w_jobcard_hdr_t.job_no,w_jobcard_hdr_t.batch_no,w_jobcard_hdr_t.job_completion_date,m_products_t.concatenated_product from i_qoh_detail_t join w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id and w_jobcard_hdr_t.cost_status=0 and temp_status=1 join m_products_t ON m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=1 where i_qoh_detail_t.job_id<17899 group by i_qoh_detail_t.job_id LIMIT 800");
    //dd($data);

    //$data= \DB::select("SELECT i_qoh_detail_t.batch_number, w_jobcard_hdr_t.*,m_products_t.*  FROM `i_qoh_detail_t` JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id JOIN m_products_t on
//m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=1 WHERE i_qoh_detail_t.`job_process` != ''  and i_qoh_detail_t.qoh_source!='Return Store Move' limit 8,20000");

    $employee_details = \DB::table('hr_employee_payproposal')->select('hr_employee_payproposal.employee_id', "hr_employee_payproposal.gross_pay")->get();

    $i = 351;
    $j = 500;
    $employee_details = collect($employee_details);
    foreach ($data as $key => $job_value) {
      /* Accounts Entry Start :Isac Naveen*/
      //  $products=\DB::table('m_products_t')->whereIn('product_id',$_POST['bulk_product_id'])->get();
      // $collection = collect($products);

      $id = $job_value->w_jobs_hdr_id;
      DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->update(['cost_status' => '1']);


      $pid = $job_value->product_id;
      //dd($pid);
      $prcs = $job_value->bom_process;

      $bomtbl = \DB::select("select m_material_bom_lines_t.process_level,m_material_bom_lines_t.process_name from m_material_bom_lines_t left join m_material_bom_hdr_t on m_material_bom_hdr_t.material_bom_hdr_id=m_material_bom_lines_t.material_bom_hdr_id where m_material_bom_hdr_t.assembly_product_id=$pid group by `m_material_bom_lines_t`.`process_level` order by `m_material_bom_lines_t`.`material_bom_line_id` ASC");
      //  dd($bomtbl);
      $process = '';
      $process_name = '';
      foreach ($bomtbl as $k => $v) {
        if ($prcs == $v->process_level) {
          break;
        } else {
          $process = $v->process_level;
          $process_name = $v->process_name;
        }
      }



      $receive_data = \DB::select("SELECT w_materialreceive_line_t.*,m_products_t.*  FROM `w_materialreceive_hdr_t`  join w_materialreceive_line_t on w_materialreceive_line_t.w_materialreceive_hdr_id= w_materialreceive_hdr_t.w_materialreceive_hdr_id join m_products_t ON m_products_t.product_id=w_materialreceive_line_t.product_id WHERE w_materialreceive_hdr_t.w_jobs_hdr_id =$id");

      //dd($receive_data);

      if (count($receive_data) > 0) {

        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();

        //dd($products);


        $date = $job_value->job_completion_date;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $jobid = $id;
        $productid = $job_value->product_id;
        $productname = $job_value->concatenated_product;
        $batch_number = $job_value->batch_no;
        $job_no = $job_value->job_no;
        $journal_name = "Material Receive-" . $job_no;

        $pro = $job_value->product_id;

        //dd($pp);                  /*Journal Header Insert*/
        $journalhdr = \DB::insert("insert into r_job_cost_hdr_tbl(job_id,job_number,job_date,batch_number,product_id,product_name)values('$jobid','$job_no','$date','$batch_number','$productid','$productname')");

        $jid = DB::getPdo()->lastInsertId();
        $tkey = 1;
        $total = 0;
        $journal_lines_data = array();

        foreach ($receive_data as $key => $value) {

          if ($value->product_group_id != 1) {
            $batchnumber = explode(',', $value->batchnumber);
            $receiveqty = explode(',', $value->receiveqty);
            // dd($receiveqty);
            $pro_id = $value->product_id;

            $product_cost = 0;


            foreach ($batchnumber as $k => $v) {
              $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id' and cost > 0 and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc ");

              if (count($cost) > 0) {

                if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                  $receiveqty[$k] = $value->receive_qty;
                $pcost = $cost[0]->cost;
                $product_cost = round(($cost[0]->cost) * ($receiveqty[$k]), 2);
              } else {


                $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id'  and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

                if (count($cost) > 0) {

                  $qoh_id = $cost[0]->qoh_detail_id;

                  $cost = \DB::select("select * from i_qoh_detail_t where qoh_detail_id<$qoh_id and cost >0  and product_id='$pro_id'  and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

                  if (count($cost) > 0) {

                    if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                      $receiveqty[$k] = $value->receive_qty;
                    $pcost = $cost[0]->cost;
                    $product_cost = round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                  } else {
                    $product_cost = 0;
                    $pcost = 0;
                  }
                } else {
                  $product_cost = 0;
                  $pcost = 0;
                }


              }



              $total += $product_cost;
              $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
              $journal_lines_data[$tkey]['type'] = "PRODUCT";
              $journal_lines_data[$tkey]['product_id'] = $pro_id;
              $journal_lines_data[$tkey]['product_name'] = $value->concatenated_product;
              $journal_lines_data[$tkey]['batch_number'] = $value->batchnumber;
              $journal_lines_data[$tkey]['product_group'] = $value->product_group_id;
              $journal_lines_data[$tkey]['rate'] = $pcost;
              $journal_lines_data[$tkey]['total'] = $product_cost;
              $journal_lines_data[$tkey]['qty'] = $receiveqty[$k];

              $tkey++;

            }
          } else {

            $receiveqty = $value->receive_qty;
            // dd($receiveqty);
            $pro_id = $value->product_id;
            $v = $job_value->batch_no;

            $product_cost = 0;



            $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id' and job_process='$process' and cost > 0 ORDER BY `qoh_detail_id` desc ");

            if (count($cost) > 0) {


              $pcost = $cost[0]->cost;
              $product_cost = round(($cost[0]->cost) * ($receiveqty), 2);
            } else {
              $product_cost = 0;
              $pcost = 0;
            }



            $total += $product_cost;
            $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
            $journal_lines_data[$tkey]['type'] = "PRODUCT";
            $journal_lines_data[$tkey]['product_id'] = $pro_id;
            $journal_lines_data[$tkey]['product_name'] = $value->concatenated_product;
            $journal_lines_data[$tkey]['batch_number'] = $value->batchnumber;
            $journal_lines_data[$tkey]['product_group'] = $value->product_group_id;
            $journal_lines_data[$tkey]['rate'] = $pcost;
            $journal_lines_data[$tkey]['total'] = $product_cost;
            $journal_lines_data[$tkey]['qty'] = $receiveqty;
            $tkey++;


          }

        }
        \DB::table('r_job_cost_lines_tbl')->insert($journal_lines_data);

      }
      /* Accounts Entry End :Isac Naveen*/

      $qa_submit = \DB::select("SELECT * FROM `w_qa_submitstage_trx_t` WHERE `job_no`='$id'");

      if (count($qa_submit) > 0) {
        $journal_lines_data = array();
        $start_times = explode(",", ($qa_submit[0]->start_time));
        $end_times = explode(",", ($qa_submit[0]->end_time));
        sort($start_times);
        rsort($end_times);


        $date = $qa_submit[0]->qatrx_date;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $jobid = $id;

        $journal_name = "Job Card Close-" . $job_no;
        /*Journal Header Insert*/
        // $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','JOB CARD CLOSE','$date','$jobid','APPROVED','$compy','$loc','$org')");
        // $jid = DB::getPdo()->lastInsertId();


        $acc_data = \DB::table('f_journal_entry_t')->join('f_journal_entry_lines_t', 'f_journal_entry_lines_t.journal_entry_id', '=', 'f_journal_entry_t.journal_entry_id')->select('f_journal_entry_lines_t.reference_id', 'f_journal_entry_lines_t.debit_amount', 'f_journal_entry_lines_t.credit_amount')->where('journal_type', 'MATERIAL RECEIVE')->where('journal_reference', $id)->get();
        $acc_collection = collect($acc_data);

        // $products=\DB::table('m_products_t')->whereIn('product_id',$_POST['bulk_product_id'])->get();
        //$collection = collect($products);

        $qa_data = \DB::select("SELECT w_qa_submitstage_line_t.*,m_products_t.*  FROM `w_qa_submitstage_line_t` join m_products_t on m_products_t.product_id=w_qa_submitstage_line_t.product_id WHERE w_qa_submitstage_line_t.`qa_submitstage_trx_hdr_id` ='" . $qa_submit[0]->qa_submitstage_trx_hdr_id . "'");


        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();
        $tkey = 1;
        $overallcost = 0;



        foreach ($qa_data as $k1 => $val1) {

          error_reporting(0);
          // $filtered = $acc_collection->where('reference_id',$val1->product_id);
          //         $filtered->all();
          //          $credit_amount=0;
          //         foreach ($filtered as  $v1) {
          //             $credit_amount+=$v1->credit_amount;
          //           // break;
          //         }


          $return_pro = $val1->product_id;
          $return_batch = $val1->batchno;
          $pro_qty = $val1->production_qty + $val1->exceed_qty;
          $return_cost = 0;
          if ($val1->return_qty != '' && $val1->return_qty != 0) {


            $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$return_batch' and product_id='$return_pro' and cost > 0 and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc ");

            if (count($cost) > 0) {

              $cost = $cost[0]->cost;
            } else {
              $cost = 0;
            }




            $return_cost = ($val1->return_qty) * ($cost);

            $total -= $return_cost;
            /*Journal Entry For Return material start*/
            $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
            $journal_lines_data[$tkey]['type'] = "PRODUCT";
            $journal_lines_data[$tkey]['product_id'] = $return_pro;
            $journal_lines_data[$tkey]['product_name'] = $val1->concatenated_product;
            $journal_lines_data[$tkey]['batch_number'] = $return_batch;
            $journal_lines_data[$tkey]['product_group'] = $value->product_group_id;
            $journal_lines_data[$tkey]['rate'] = $cost;
            $journal_lines_data[$tkey]['total'] = $return_cost * -1;
            $journal_lines_data[$tkey]['qty'] = $val1->return_qty;
            /*Journal Entry For Return material end*/
            $tkey++;



          }

        }
        \DB::table('r_job_cost_lines_tbl')->insert($journal_lines_data);
        /*Journal Entry For Labour Start*/
        $journal_lines_data = array();
        $month = $month = date('m', strtotime($qa_submit[0]->qatrx_date));
        $year = date('Y', strtotime($qa_submit[0]->qatrx_date));
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        //dd($val1);
        $empl = explode(',', ($qa_submit[0]->job_assigned_to));
        $empl_working = explode(',', ($qa_submit[0]->working_hrs));
        $start_timess = explode(",", ($qa_submit[0]->start_time));
        $end_timess = explode(",", ($qa_submit[0]->end_time));


        foreach ($empl as $k => $evalue) {
          $jc_date = $qa_submit[0]->qatrx_date;
          $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal`  where employee_id='$evalue' and date(updated_at)<='$jc_date'");
          $empname = \DB::select("SELECT * FROM `hr_employee_t`  where employee_id='$evalue'");

          if (count($filtered) > 0) {
            $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
          } else {

            $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue' and date(updated_at)<='$jc_date' limit 1");

            if (count($filtered) > 0) {
              $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
            } else {

              $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue'  limit 1");
              if (count($filtered) > 0) {
                $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
              } else {

                $hour_cost = 0;

              }

            }

          }



          $start = strtotime($start_timess[$k]);
          $end = strtotime($end_timess[$k]);
          $total_hours = ($end - $start) / 60;




          //         $total_hours=explode(".",$empl_working[$k]);
//         $time=0;
//         if(isset($total_hours[1]))
//         {
//             $time=((($total_hours[1])*100)/60)*0.1;
//         }
// $total_hours=($total_hours[0])+($time);
          $total += $total_cost = round($total_hours * ($hour_cost / 60), 2);

          $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
          $journal_lines_data[$tkey]['type'] = "EMPLOYEE";
          $journal_lines_data[$tkey]['product_id'] = $evalue;
          $journal_lines_data[$tkey]['product_name'] = $empname[0]->first_name;
          $journal_lines_data[$tkey]['batch_number'] = '';
          $journal_lines_data[$tkey]['product_group'] = '';
          $journal_lines_data[$tkey]['rate'] = $hour_cost;
          $journal_lines_data[$tkey]['total'] = $total_cost;
          $journal_lines_data[$tkey]['qty'] = $total_hours;

          $tkey++;

        }

        /*Journal Entry For Labour End*/

        /* Journal Entry For Electricity start */

        $to_time = strtotime($start_times[0]);
        $from_time = strtotime($end_times[0]);
        $machine_time = round((abs($to_time - $from_time) / 60) / 60, 2);

        $machine_details = \DB::table('w_jobcard_hdr_t')->join('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'w_jobcard_hdr_t.machine_hdr_id')->select('w_machine_hdr_t.machine_hdr_id', 'w_machine_hdr_t.machine_code', 'w_machine_hdr_t.machine_name', 'w_machine_hdr_t.electricity_cost')->where('w_jobcard_hdr_t.w_jobs_hdr_id', $id)->get();
        //dd($machine_details);
        $total += $cost = round($machine_details[0]->electricity_cost * $machine_time, 2);
        if ($cost > 0) {
          $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
          $journal_lines_data[$tkey]['type'] = "MACHINE";
          $journal_lines_data[$tkey]['product_id'] = $machine_details[0]->machine_hdr_id;
          $journal_lines_data[$tkey]['product_name'] = $machine_details[0]->machine_code . '-' . $machine_details[0]->machine_name;
          $journal_lines_data[$tkey]['batch_number'] = '';
          $journal_lines_data[$tkey]['product_group'] = '';
          $journal_lines_data[$tkey]['rate'] = $machine_details[0]->electricity_cost;
          $journal_lines_data[$tkey]['total'] = $cost;
          $journal_lines_data[$tkey]['qty'] = $machine_time;

          $tkey++;
        }

        \DB::table('r_job_cost_lines_tbl')->insert($journal_lines_data);
        /* Journal Entry For Electricity end */

        /* Journal Entry For WIP Account */


        $proqty = $qa_submit[0]->production_qty;
        $cost = round($total / $qa_submit[0]->production_qty, 2);
        if ($qa_submit[0]->production_qty == 0) {
          $cost = 0;
        }
        // DB::table('i_qoh_detail_t')->where('job_id', $id)->where('product_id', $pro)->update(['cost' => $cost]);
        DB::table('r_job_cost_hdr_tbl')->where('r_job_cost_hdr_id', $jid)->update(['rate' => $cost, 'total' => $total, 'qty' => $proqty]);

      }
    }
    if (count($data) > 0) {
      dd(count($data) . " FG Entries Sync Completed");
    } else {
      dd("There Is No More FG Entries to Sync");
    }
    //return redirect('company');


  }
  public function productsyncsfgconst(Createuser $createuser)
  {
    $data = \DB::select("select w_jobcard_hdr_t.product_id,w_jobcard_hdr_t.w_jobs_hdr_id,w_jobcard_hdr_t.job_no,w_jobcard_hdr_t.batch_no,w_jobcard_hdr_t.job_completion_date,m_products_t.concatenated_product from i_qoh_detail_t join w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id and w_jobcard_hdr_t.cost_status=0 and w_jobcard_hdr_t.temp_status=1  join m_products_t ON m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=4 group by i_qoh_detail_t.job_id order by i_qoh_detail_t.job_id  asc LIMIT 500");
    //dd($data);

    //$data= \DB::select("SELECT i_qoh_detail_t.batch_number, w_jobcard_hdr_t.*,m_products_t.*  FROM `i_qoh_detail_t` JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id JOIN m_products_t on
//m_products_t.product_id=w_jobcard_hdr_t.product_id and m_products_t.product_group_id=1 WHERE i_qoh_detail_t.`job_process` != ''  and i_qoh_detail_t.qoh_source!='Return Store Move' limit 8,20000");

    $employee_details = \DB::table('hr_employee_payproposal')->select('hr_employee_payproposal.employee_id', "hr_employee_payproposal.gross_pay")->get();

    $i = 351;
    $j = 500;
    $employee_details = collect($employee_details);
    foreach ($data as $key => $job_value) {

      //dd($job_value);
      /* Accounts Entry Start :Isac Naveen*/
      //  $products=\DB::table('m_products_t')->whereIn('product_id',$_POST['bulk_product_id'])->get();
      // $collection = collect($products);

      $id = $job_value->w_jobs_hdr_id;
      DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->update(['cost_status' => '1']);


      $receive_data = \DB::select("SELECT w_materialreceive_line_t.*,m_products_t.*  FROM `w_materialreceive_hdr_t`  join w_materialreceive_line_t on w_materialreceive_line_t.w_materialreceive_hdr_id= w_materialreceive_hdr_t.w_materialreceive_hdr_id join m_products_t ON m_products_t.product_id=w_materialreceive_line_t.product_id WHERE w_materialreceive_hdr_t.w_jobs_hdr_id =$id");
      //dd($job_value);
//dd($receive_data);

      if (count($receive_data) > 0) {

        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();




        $date = $job_value->job_completion_date;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $jobid = $id;
        $productid = $job_value->product_id;
        $productname = $job_value->concatenated_product;
        $batch_number = $job_value->batch_no;
        $job_no = $job_value->job_no;
        $journal_name = "Material Receive-" . $job_no;

        // dd($batch_number);


        $pro = $job_value->product_id;

        //dd($pp);                  /*Journal Header Insert*/
        $journalhdr = \DB::insert("insert into r_job_cost_hdr_tbl(job_id,job_number,job_date,batch_number,product_id,product_name)values('$jobid','$job_no','$date','$batch_number','$productid','$productname')");

        $jid = DB::getPdo()->lastInsertId();
        $tkey = 1;
        $total = 0;
        $journal_lines_data = array();

        foreach ($receive_data as $key => $value) {


          $batchnumber = explode(',', $value->batchnumber);
          $receiveqty = explode(',', $value->receiveqty);
          // dd($receiveqty);
          $pro_id = $value->product_id;

          $product_cost = 0;


          foreach ($batchnumber as $k => $v) {
            $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id' and cost > 0 and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc ");

            if (count($cost) > 0) {

              if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                $receiveqty[$k] = $value->receive_qty;
              $pcost = $cost[0]->cost;
              $product_cost = round(($cost[0]->cost) * ($receiveqty[$k]), 2);
            } else {



              $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$v' and product_id='$pro_id'  and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

              if (count($cost) > 0) {

                $qoh_id = $cost[0]->qoh_detail_id;

                $cost = \DB::select("select * from i_qoh_detail_t where qoh_detail_id<$qoh_id and cost >0  and product_id='$pro_id'  and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc limit 1");

                if (count($cost) > 0) {

                  if (!isset($receiveqty[$k]) || $receiveqty[$k] == '')
                    $receiveqty[$k] = $value->receive_qty;
                  $pcost = $cost[0]->cost;
                  $product_cost = round(($cost[0]->cost) * ($receiveqty[$k]), 2);
                } else {
                  $product_cost = 0;
                  $pcost = 0;
                }
              } else {
                $product_cost = 0;
                $pcost = 0;
              }



            }



            $total += $product_cost;
            $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
            $journal_lines_data[$tkey]['type'] = "PRODUCT";
            $journal_lines_data[$tkey]['product_id'] = $pro_id;
            $journal_lines_data[$tkey]['product_name'] = $value->concatenated_product;
            $journal_lines_data[$tkey]['batch_number'] = $v;
            $journal_lines_data[$tkey]['product_group'] = $value->product_group_id;
            $journal_lines_data[$tkey]['qty'] = round(($receiveqty[$k]), 2);
            $journal_lines_data[$tkey]['rate'] = round(($product_cost / ($receiveqty[$k])), 2);
            $journal_lines_data[$tkey]['total'] = $product_cost;

            $tkey++;

          }

        }

        //dd($journal_lines_data)

        \DB::table('r_job_cost_lines_tbl')->insert($journal_lines_data);

      }
      /* Accounts Entry End :Isac Naveen*/

      $qa_submit = \DB::select("SELECT * FROM `w_qa_submitstage_trx_t` WHERE `job_no`='$id'");
      $journal_lines_data = array();

      if (count($qa_submit) > 0) {
        $journal_lines_data = array();
        $start_times = explode(",", ($qa_submit[0]->start_time));
        $end_times = explode(",", ($qa_submit[0]->end_time));
        sort($start_times);
        rsort($end_times);


        $date = $qa_submit[0]->qatrx_date;
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $jobid = $id;

        $journal_name = "Job Card Close-" . $job_no;
        /*Journal Header Insert*/
        // $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','JOB CARD CLOSE','$date','$jobid','APPROVED','$compy','$loc','$org')");
        // $jid = DB::getPdo()->lastInsertId();


        $acc_data = \DB::table('f_journal_entry_t')->join('f_journal_entry_lines_t', 'f_journal_entry_lines_t.journal_entry_id', '=', 'f_journal_entry_t.journal_entry_id')->select('f_journal_entry_lines_t.reference_id', 'f_journal_entry_lines_t.debit_amount', 'f_journal_entry_lines_t.credit_amount')->where('journal_type', 'MATERIAL RECEIVE')->where('journal_reference', $id)->get();
        $acc_collection = collect($acc_data);

        // $products=\DB::table('m_products_t')->whereIn('product_id',$_POST['bulk_product_id'])->get();
        //$collection = collect($products);

        $qa_data = \DB::select("SELECT w_qa_submitstage_line_t.*,m_products_t.*  FROM `w_qa_submitstage_line_t` join m_products_t on m_products_t.product_id=w_qa_submitstage_line_t.product_id WHERE w_qa_submitstage_line_t.`qa_submitstage_trx_hdr_id` ='" . $qa_submit[0]->qa_submitstage_trx_hdr_id . "'");


        $production_acc = \DB::table('f_account_setting_t')->where('module_name', 'production')->get();
        $tkey = 1;
        $overallcost = 0;



        foreach ($qa_data as $k1 => $val1) {

          error_reporting(0);
          // $filtered = $acc_collection->where('reference_id',$val1->product_id);
          //         $filtered->all();
          //          $credit_amount=0;
          //         foreach ($filtered as  $v1) {
          //             $credit_amount+=$v1->credit_amount;
          //           // break;
          //         }


          $return_pro = $val1->product_id;
          $return_batch = $val1->batchno;
          $pro_qty = $val1->production_qty + $val1->exceed_qty;
          $return_cost = 0;
          if ($val1->return_qty != '' && $val1->return_qty != 0) {


            $cost = \DB::select("select * from i_qoh_detail_t where batch_number='$return_batch' and product_id='$return_pro' and cost > 0 and (qoh_source='OPENSTOCK'  or qoh_source='PRODUCTION STORE MOVE' or qoh_source='WIP Store Move' or qoh_source='PURCHASE_STOREMOVE'  ) ORDER BY `qoh_detail_id` desc ");

            if (count($cost) > 0) {

              $cost = $cost[0]->cost;
            } else {
              $cost = 0;
            }




            $return_cost = ($val1->return_qty) * ($cost);

            $total -= $return_cost;
            /*Journal Entry For Return material start*/
            $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
            $journal_lines_data[$tkey]['type'] = "PRODUCT";
            $journal_lines_data[$tkey]['product_id'] = $return_pro;
            $journal_lines_data[$tkey]['product_name'] = $val1->concatenated_product;
            $journal_lines_data[$tkey]['batch_number'] = $return_batch;
            $journal_lines_data[$tkey]['product_group'] = $value->product_group_id;
            $journal_lines_data[$tkey]['rate'] = $cost;
            $journal_lines_data[$tkey]['total'] = $return_cost;
            $journal_lines_data[$tkey]['qty'] = $val1->return_qty;
            /*Journal Entry For Return material end*/
            $tkey++;



          }

        }
        \DB::table('r_job_cost_lines_tbl')->insert($journal_lines_data);
        /*Journal Entry For Labour Start*/

        $month = $month = date('m', strtotime($qa_submit[0]->qatrx_date));
        $year = date('Y', strtotime($qa_submit[0]->qatrx_date));
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        //dd($val1);
        $empl = explode(',', ($qa_submit[0]->job_assigned_to));
        $empl_working = explode(',', ($qa_submit[0]->working_hrs));


        $start_timess = explode(",", ($qa_submit[0]->start_time));
        $end_timess = explode(",", ($qa_submit[0]->end_time));




        foreach ($empl as $k => $evalue) {
          $jc_date = $qa_submit[0]->qatrx_date;
          $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal`  where employee_id='$evalue' and date(updated_at)<='$jc_date'");
          $empname = \DB::select("SELECT * FROM `hr_employee_t`  where employee_id='$evalue'");

          if (count($filtered) > 0) {
            $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
          } else {

            $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue' and date(updated_at)<='$jc_date' limit 1");

            if (count($filtered) > 0) {
              $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
            } else {

              $filtered = \DB::select("SELECT * FROM `hr_employee_payproposal_before`  where employee_id='$evalue'  limit 1");
              if (count($filtered) > 0) {
                $hour_cost = ($filtered[0]->gross_pay / $days) / 8;
              } else {

                //$hour_cost = 0;

                $filtered = \DB::select("SELECT * FROM f_expenses_t LEFT JOIN f_expenses_lines_t ON f_expenses_t.expense_id = f_expenses_lines_t.expense_id WHERE f_expenses_t.employee_id='$evalue' AND f_expenses_t.expense_type='EMPLOYEE' AND f_expenses_lines_t.expense_account_id='407' AND date(f_expenses_t.expense_date) <= '$jc_date' ORDER BY f_expenses_t.expense_id DESC LIMIT 1");

                if (count($filtered) > 0) {
                  $hour_cost = ($filtered[0]->expense_amount / $days) / 8;
                } else {

                  $hour_cost = 0;
                }

              }

            }

          }



          $start = strtotime($start_timess[$k]);
          $end = strtotime($end_timess[$k]);
          $total_hours = ($end - $start) / 60;




          //         $total_hours=explode(".",$empl_working[$k]);
//         $time=0;
//         if(isset($total_hours[1]))
//         {
//             $time=((($total_hours[1])*100)/60)*0.1;
//         }
// $total_hours=($total_hours[0])+($time);
          $total += $total_cost = round($total_hours * ($hour_cost / 60), 2);

          $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
          $journal_lines_data[$tkey]['type'] = "EMPLOYEE";
          $journal_lines_data[$tkey]['product_id'] = $evalue;
          $journal_lines_data[$tkey]['product_name'] = $empname[0]->employee_number . '-' . $empname[0]->first_name;
          $journal_lines_data[$tkey]['batch_number'] = '';
          $journal_lines_data[$tkey]['product_group'] = '';
          $journal_lines_data[$tkey]['rate'] = $hour_cost;
          $journal_lines_data[$tkey]['total'] = $total_cost;
          $journal_lines_data[$tkey]['qty'] = $total_hours;

          $tkey++;

        }

        /*Journal Entry For Labour End*/

        /* Journal Entry For Electricity start */

        $to_time = strtotime($start_times[0]);
        $from_time = strtotime($end_times[0]);
        $machine_time = round((abs($to_time - $from_time) / 60) / 60, 2);

        $machine_details = \DB::table('w_jobcard_hdr_t')->join('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'w_jobcard_hdr_t.machine_hdr_id')->select('w_machine_hdr_t.machine_hdr_id', 'w_machine_hdr_t.machine_code', 'w_machine_hdr_t.machine_name', 'w_machine_hdr_t.electricity_cost')->where('w_jobcard_hdr_t.w_jobs_hdr_id', $id)->get();
        //dd($machine_details);
        $total += $cost = round($machine_details[0]->electricity_cost * $machine_time, 2);
        if ($cost > 0) {
          $journal_lines_data[$tkey]['r_job_cost_hdr_id'] = $jid;
          $journal_lines_data[$tkey]['type'] = "MACHINE";
          $journal_lines_data[$tkey]['product_id'] = $machine_details[0]->machine_hdr_id;
          $journal_lines_data[$tkey]['product_name'] = $machine_details[0]->machine_code . '-' . $machine_details[0]->machine_name;
          $journal_lines_data[$tkey]['batch_number'] = '';
          $journal_lines_data[$tkey]['product_group'] = '';
          $journal_lines_data[$tkey]['rate'] = $machine_details[0]->electricity_cost;
          $journal_lines_data[$tkey]['total'] = $cost;
          $journal_lines_data[$tkey]['qty'] = $machine_time;

          $tkey++;
        }

        \DB::table('r_job_cost_lines_tbl')->insert($journal_lines_data);
        /* Journal Entry For Electricity end */

        /* Journal Entry For WIP Account */


        $proqty = $qa_submit[0]->production_qty;
        $cost = round($total / $qa_submit[0]->production_qty, 2);
        if ($qa_submit[0]->production_qty == 0) {
          $cost = 0;
        }
        //DB::table('i_qoh_detail_t')->where('job_id', $id)->where('product_id', $pro)->update(['cost' => $cost]);
        DB::table('r_job_cost_hdr_tbl')->where('r_job_cost_hdr_id', $jid)->update(['rate' => $cost, 'total' => $total, 'qty' => $proqty]);

      }
    }
    if (count($data) > 0) {
      dd(count($data) . " SFG Entries Sync Completed");
    } else {
      dd("There Is No More SFG Entries to Sync");
    }
    //dd(count($data)." Entries Sync Completed");

    //return redirect('company');

  }

}
