<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class OtherDashboardaccessController extends Controller
{
    public $module = "OtherDashboardaccess";
    public function __construct()
    {
        $this->data = array(
            'pageModule' => 'OtherDashboardaccess',
            'pageUrl'    =>  url('OtherDashboardaccess')
        );
        $this->data['urlmenu'] = $this->indexs();
        $this->pageModule = "OtherDashboardaccess";

        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }




    public function index()
    {
        return view('otherdashboardaccess.table', $this->data);
    }

	// Table Data
		public function otherDashboardData(Request $request)
{
    if ($request->ajax()) {
        $query = \DB::table('a_other_dashboard_access_t')
            ->join('tb_users', 'a_other_dashboard_access_t.user_id', '=', 'tb_users.id')
            ->select('a_other_dashboard_access_t.a_dashboard_access_id as id', 'tb_users.username','tb_users.first_name');

        return DataTables::of($query)->make(true);
    }
}
	
    public function create($id = null)
    {

        if ($id != null && $id != 0) {
            $hrmshtml = "";
            $salhtml = "";
            $purhtml = "";
            $oprhtml = "";
            $mainhtml = "";
            $qtyhtml = "";
            $acchtml = "";


            $access_data =  \DB::select("SELECT * FROM `a_other_dashboard_access_t` where a_dashboard_access_id='$id'");
            $access = json_decode($access_data[0]->dashboard_option);
            //dd($access);

            $hrms = array("Leave Balance Detail","Leave Balance Detail Search","Organogram Search","EmailButton","Total Empolyees", "HO/CO Empolyees", "Factory Empolyees", "Marketing Empolyees", "Male Empolyees", "Female Empolyees", "Active Empolyees", "Inactive Empolyees","Zone Wise Employee Count", "Employees List", "Group Vice Employees Count", "Zone Wise Customer Count", "Zone Wise Customer List","Marketing Table","Total Openings","Opening Department","Total Candidate","On Board","Total Openings Marketing","Opening Position Marketing","Total Candidate Marketing","On Board Marketing","Interview Count");
            $sales = array("");
            $purchase = array("");
            $operation = array("");
            $maintain = array("");
            $quality = array("");
            $accounts = array("");

            $this->data['user_id'] = $this->jCombo('tb_users', 'id', 'username', $access_data[0]->user_id);
            $this->data['a_dashboard_access_id'] = $id;


            $hrmshtml .= "<div class='col-md-3 headmenu'>  <div class='table-responsive'> <table class='table hrmsTable'> <thead><tr><th>sno</th><th>Hrms Menu Name</th><th><input type='checkbox' class='hrms check_head' name='hrmscheckall'  value='hrms'></th></tr> </thead> <tbody>";
            $salhtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table salTable'> <thead><tr><th>sno</th><th>Sales Menu Name</th><th><input type='checkbox' class='sales check_head' name='salcheckall'  value='sal'></th></tr> </thead> <tbody>";
            $purhtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table purTable'> <thead><tr><th>sno</th><th>Purchase Menu Name</th><th><input type='checkbox' class='purchase check_head' name='purcheckall'  value='pur'></th></tr> </thead> <tbody>";
            $oprhtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table oprTable'> <thead><tr><th>sno</th><th>Operation Menu Name</th><th><input type='checkbox' class='operation check_head' name='oprcheckall'  value='opr'></th></tr> </thead> <tbody>";
            $mainhtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table mainTable'> <thead><tr><th>sno</th><th>Maintenance Menu Name</th><th><input type='checkbox' class='maintain check_head' name='maincheckall'  value='main'></th></tr> </thead> <tbody>";
            $qtyhtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table qtyTable'> <thead><tr><th>sno</th><th>Quality Menu Name</th><th><input type='checkbox' class='quality check_head' name='qtycheckall'  value='qty'></th></tr> </thead> <tbody>";
            $acchtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table accTable'> <thead><tr><th>sno</th><th>Accounts Menu Name</th><th><input type='checkbox' class='accounts check_head' name='acccheckall'  value='acc'></th></tr> </thead> <tbody>";

            /*hrms menu*/
            foreach ($hrms as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);
                $checked = "";
                if (in_array($val, $access)) {
                    $checked = "checked='true'";
                }


                $hrmshtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' $checked class='headhrms header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $hrmshtml .= "</tbody>  </table>     </div></div>";
            $this->data['hrmshtml'] = $hrmshtml;

            /*end*/
            /*sales menu*/
            foreach ($sales as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);

                $checked = '';
                if (in_array($val, $access)) {
                    $checked = "checked='true'";
                }


                $salhtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' $checked class='headsal header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $salhtml .= "</tbody>  </table>     </div></div>";
            $this->data['salhtml'] = $salhtml;

            /*end*/
            /*purchase menu*/
            foreach ($purchase as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);


                $checked = '';
                if (in_array($val, $access)) {
                    $checked = "checked='true'";
                }


                $purhtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' $checked class='headpur header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $purhtml .= "</tbody>  </table>     </div></div>";
            $this->data['purhtml'] = $purhtml;

            /*end*/
            /*operation menu*/
            foreach ($operation as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);


                $checked = '';
                if (in_array($val, $access)) {
                    $checked = "checked='true'";
                }


                $oprhtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' $checked class='headopr header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $oprhtml .= "</tbody>  </table>     </div></div>";
            $this->data['oprhtml'] = $oprhtml;

            /*end*/
            /*maintanance menu*/
            foreach ($maintain as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);

                $checked = '';
                if (in_array($val, $access)) {
                    $checked = "checked='true'";
                }
                $mainhtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' $checked class='headmain header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $mainhtml .= "</tbody>  </table>     </div></div>";
            $this->data['mainhtml'] = $mainhtml;


            /*end*/
            /*quality menu*/
            foreach ($quality as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);

                $checked = '';
                if (in_array($val, $access)) {
                    $checked = "checked='true'";
                }

                $qtyhtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' $checked class='headqty header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $qtyhtml .= "</tbody>  </table>     </div></div>";
            $this->data['qtyhtml'] = $qtyhtml;
            /*end*/
            /*accounts menu*/
            foreach ($accounts as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);


                $checked = '';
                if (in_array($val, $access)) {
                    $checked = "checked='true'";
                }


                $acchtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' $checked class='headacc header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $acchtml .= "</tbody>  </table>     </div></div>";
            $this->data['acchtml'] = $acchtml;

            /*end*/
        } else {

            $hrmshtml = "";
            $salhtml = "";
            $purhtml = "";
            $oprhtml = "";
            $mainhtml = "";
            $qtyhtml = "";
            $acchtml = "";


            $hrms = array("Leave Balance Detail","Leave Balance Detail Search","Organogram Search","EmailButton","Total Empolyees", "HO/CO Empolyees", "Factory Empolyees", "Marketing Empolyees", "Male Empolyees", "Female Empolyees", "Active Empolyees", "Inactive Empolyees","Zone Wise Employee Count", "Employees List", "Group Vice Employees Count", "Zone Wise Customer Count", "Zone Wise Customer List","Marketing Table","Total Request","Open Requirement","Closed Requirement","Total Proposal","Accepted Proposal","Rejected Proposal","Open Request","Closed Request","Bar Chart","Open Position","Closed Position");
            $sales = array("");
            $purchase = array("");
            $operation = array("");
            $maintain = array("");
            $quality = array("");
            $accounts = array("");

            $this->data['user_id'] = $this->jCombo('tb_users', 'id', 'username', '');
            $this->data['a_dashboard_access_id'] = '';


            $hrmshtml .= "<div class='col-md-3 headmenu'>  <div class='table-responsive'> <table class='table hrmsTable'> <thead><tr><th>sno</th><th>Hrms Menu Name</th><th><input type='checkbox' class='hrms check_head' name='hrmscheckall'  value='hrms'></th></tr> </thead> <tbody>";
            $salhtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table salTable'> <thead><tr><th>sno</th><th>Sales Menu Name</th><th><input type='checkbox' class='sales check_head' name='salcheckall'  value='sal'></th></tr> </thead> <tbody>";
            $purhtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table purTable'> <thead><tr><th>sno</th><th>Purchase Menu Name</th><th><input type='checkbox' class='purchase check_head' name='purcheckall'  value='pur'></th></tr> </thead> <tbody>";
            $oprhtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table oprTable'> <thead><tr><th>sno</th><th>Operation Menu Name</th><th><input type='checkbox' class='operation check_head' name='oprcheckall'  value='opr'></th></tr> </thead> <tbody>";
            $mainhtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table mainTable'> <thead><tr><th>sno</th><th>Maintenance Menu Name</th><th><input type='checkbox' class='maintain check_head' name='maincheckall'  value='main'></th></tr> </thead> <tbody>";
            $qtyhtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table qtyTable'> <thead><tr><th>sno</th><th>Quality Menu Name</th><th><input type='checkbox' class='quality check_head' name='qtycheckall'  value='qty'></th></tr> </thead> <tbody>";
            $acchtml .= "<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table accTable'> <thead><tr><th>sno</th><th>Accounts Menu Name</th><th><input type='checkbox' class='accounts check_head' name='acccheckall'  value='acc'></th></tr> </thead> <tbody>";


            /*hrms menu*/

            foreach ($hrms as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);
                $hrmshtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' class='headhrms header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $hrmshtml .= "</tbody>  </table>     </div></div>";
            $this->data['hrmshtml'] = $hrmshtml;

            /*end*/
            /*sales menu*/
            foreach ($sales as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);
                $salhtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' class='headsal header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $salhtml .= "</tbody>  </table>     </div></div>";
            $this->data['salhtml'] = $salhtml;

            /*end*/
            foreach ($purchase as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);
                $purhtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' class='headpur header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $purhtml .= "</tbody>  </table>     </div></div>";
            $this->data['purhtml'] = $purhtml;

            /*end*/
            foreach ($operation as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);
                $oprhtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' class='headopr header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $oprhtml .= "</tbody>  </table>     </div></div>";
            $this->data['oprhtml'] = $oprhtml;

            /*end*/
            /*maintanance menu*/
            foreach ($maintain as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);
                $mainhtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' class='headmain header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $mainhtml .= "</tbody>  </table>     </div></div>";
            $this->data['mainhtml'] = $mainhtml;


            /*end*/
            /*quality menu*/
            foreach ($quality as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);
                $qtyhtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' class='headqty header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $qtyhtml .= "</tbody>  </table>     </div></div>";
            $this->data['qtyhtml'] = $qtyhtml;

            /*end*/
            /*accounts menu*/
            foreach ($accounts as $k => $v) {
                $checks = '';
                $sno = $k + 1;
                $val = str_replace(' ', '', $v);
                $acchtml .= "<tr><td>" . $sno . "</td><td><a class='main_menu headmenus head" . $val . "'  data-value=" . $val . ">" . $v . "</a></td><td><input type='checkbox' class='headacc header" . $val . "' name='menu_id[]' myval=" . $v . "  " . $checks . " value=" . $val . "></td>  </tr> ";
            }
            $acchtml .= "</tbody>  </table>     </div></div>";
            $this->data['acchtml'] = $acchtml;
            /*end*/
        }

        return view('otherdashboardaccess.form', $this->data);
    }

    public function save(Request $request)
    {

        if (!empty($_POST['a_dashboard_access_id'])) {

            $data['user_id'] = $_POST['user_id'];


            $data['dashboard_option'] =  json_encode($_POST['menu_id']);
            $data['company_id'] = \Session::get('companyid');
            $data['location_id'] = \Session::get('location');
            $data['last_updated_by'] = \Session::get('id');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $result = \DB::table('a_other_dashboard_access_t')->where('a_dashboard_access_id', "=", $_POST['a_dashboard_access_id'])->update($data);

		return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
        } else {

            $data['user_id'] = $_POST['user_id'];


            $data['dashboard_option'] =  json_encode($_POST['menu_id']);
            $data['company_id'] = \Session::get('companyid');
            $data['location_id'] = \Session::get('location');
            $data['created_by'] = \Session::get('id');
            $data['last_updated_by'] = \Session::get('id');
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $result = $inserted_id = \DB::table('a_other_dashboard_access_t')->insertGetId($data);

return response()->json(['status' => 'success', 'message' => 'Saved successfully']);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Groupmenuaccess  $groupmenuaccess
     * @return \Illuminate\Http\Response
     */
    public function show(Groupmenuaccess $groupmenuaccess)
    {
        //
    }


    public function edit($id = null)
    {
        $data = \DB::table("a_group_menu_access_t")->select("*")->where("a_group_menu_access_id", $id)->get();
        $this->data['group_name'] = $this->jcombologin("a_m_group_t", "group_id", "group_name", "");
        $this->data['header'] = \DB::table("tb_menus")->select("*")->where("parent_id", "=", "0")->get();





        return view('groupaccessmenu.form', $this->data);
    }

    public function update(Request $request, Groupmenuaccess $groupmenuaccess)
    {
        //
    }


    public function destroy(Groupmenuaccess $groupmenuaccess)
    {
        //
    }
}
