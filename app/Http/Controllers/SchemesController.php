<?php

namespace App\Http\Controllers;

use App\Schemes;
use App\schemeslines;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class SchemesController extends Controller
{


    public $module = "schemes";
    public function __construct()
    {
        $this->data = array();
        $this->model = new Schemes();
        $this->model = new Schemes;
        $this->submodel = new schemeslines;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'schemes';
        $this->table = "s_schemes_hdr_t";
        $this->subtable = "s_schemes_lines_t";
        $this->middleware('auth');
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

        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        $table = \DB::table('s_schemes_hdr_t')->get();
        $this->data['datas'] = $table;
        $this->data['location'] = $this->jcombocomp("m_cities_t", "city_id", "city_name", '');

        return view('schemes.table', $this->data);
    }


    public function getGridData()
    {

        $wh = '';


        $app_id = \Session::get('id');
        $group_name = \Session::get('groupname');

        if ($_GET['pagemethod'] == 'schemesapproval') {
            $wh .= " and s_schemes_hdr_t.savestatus='INITIATED' and s_schemes_hdr_t.approvestatus='INITIATED' and json_contains(s_schemes_hdr_t.approver_id ,'" . $app_id . "')=1 ";
        } else if ($_GET['pagemethod'] == 'schemes') {
            if ($group_name == "1" || $group_name == "4" || $group_name == "17" || $app_id == '155') {
                $wh .= " ";
            } else {
                $wh .= " and s_schemes_hdr_t.created_by ='$app_id' ";
            }
        } else if ($_GET['pagemethod'] == 'schemeslevel2approval') {
            $wh .= " and s_schemes_hdr_t.savestatus='APPROVED' and s_schemes_hdr_t.approvestatus='INITIATED' and json_contains(s_schemes_hdr_t.approver_id_l2 ,'" . $app_id . "')=1 ";
        }


        $com = \Session::get('companyid');
        $loc = \Session::get('location');
        $org = \Session::get('organization');

        $SQL = "SELECT s_schemes_hdr_t.*,tb_users.*,lvl1app.first_name as lvl1appr,lvl2app.first_name as lvl2appr from s_schemes_hdr_t  left join tb_users on tb_users.id =s_schemes_hdr_t.created_by left join tb_users as lvl1app on lvl1app.id =s_schemes_hdr_t.level1approver and lvl1app.id !=0 left join tb_users as lvl2app on lvl2app.id =s_schemes_hdr_t.level2approver and lvl2app.id !=0 where 1=1 $wh";



        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }

    public function create_old($id)
    {
        $maindate = $this->dateform('date');
        $schemes = schemes::find($id);
        $this->data['location'] = $this->jcombocomp("m_cities_t", "city_id", "city_name", '');
        $this->data['linedata'] = array();
        $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', '');
        $this->data['maindate'] = $maindate;
        $this->data['schemes'] = $schemes;

        return view('schemes.form', $this->data);
    }
    //Create function
    public function create($id = null)
    {
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        if ($id != 0) {

            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('s_schemes_hdr_t')->where('schemes_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['row']->start_date = date('d-m-Y', strtotime($table[0]->start_date));
            $this->data['row']->end_date = date('d-m-Y', strtotime($table[0]->end_date));
            $this->data['location'] = $this->jcombocomp("m_cities_t", "city_id", "city_name", $table[0]->location);
            //$this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product','');
            $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', '', ' and product_group_id=5');
            $linestable = \DB::table('s_schemes_lines_t')->where('schemes_hdr_id', $id)->get();
            $this->data['linedata'] = $linestable;

            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', $value->product_id);
                $this->data['linedata'][$key]->line_product_id = $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', $value->line_pdt_id);
            }
        } else {

            $this->data['pagemode'] = "create";
            $this->modelname = new Schemes();
            $this->data['row'] = (object) array();
            $table = $this->modelname->getTableColumns();

            foreach ($table as $key => $val) {
                $this->data['row']->$val = '';
            }
            $this->data['row']->start_date = date('d-m-Y');
            $this->data['location'] = $this->jcombologin("m_cities_t", "city_id", "city_name", \Session::get('location'));
            $this->data['linedata'] = array();
            $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', '', ' and product_group_id=1');
            $this->data['gift_product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', '', ' and product_group_id=5');
        }

        $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
        $this->data['prdgrpopt'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'materialbom')->get();
        $this->data['return_url'] = \Request::route()->getName();
        return view('schemes.form', $this->data);
    }
    //End

    // Save Function
    public function save(Request $request) {
        
    $id = '';
    $form = $request -> except([
        '_token',
        'form_config',
        'form_data_json',
        'submit_type',
        'choosefile',
        'existing_file',
        'route_name',
        'enable-masterdetail',
    ]);

    $form = $this -> normalizeLineFormKeys($form);
    $data = $this -> validatePost($form, $this -> table, 'header');
    $data['start_date'] = date('Y-m-d', strtotime($form['start_date']));
    $data['end_date'] = date('Y-m-d', strtotime($form['end_date']));
    if ($form['savestatus'] == 'INITIATED') {

        $t = 0;
        $approverid = $this -> Approvaldatacheck('schemes', $t);
        $approveridl2 = $this -> Approvaldatacheck('schemeslevel2approval', $t);

        if ($approverid == "0" && $approveridl2 == "0") {
            $data['approver_id'] = \Session:: get('id');
            $data['savestatus'] = "APPROVED";
            $data['approver_id_l2'] = \Session:: get('id');
            $data['approvestatus'] = "INITIATED";
        } else {
            $data['approver_id'] = $approverid;
            $data['approvestatus'] = "INITIATED";
            $data['approver_id_l2'] = $approveridl2;
        }
    }

    if ($form['savestatus'] != 'INITIATED' && $form['approvestatus'] == 'INITIATED') {

        $t = 0;
        $approverid = $this -> Approvaldatacheck('schemes', $t);
        $approveridl2 = $this -> Approvaldatacheck('schemeslevel2approval', $t);

        if ($approverid == "0" && $approveridl2 == "0") {
            $data['approver_id'] = \Session:: get('id');
            $data['savestatus'] = "APPROVED";
            $data['approver_id_l2'] = \Session:: get('id');
            $data['approvestatus'] = "APPROVED";
        } else {
            $data['approver_id_l2'] = $approveridl2;
            $data['savestatus'] = "APPROVED";
        }
    }

    if ($request -> route_name == "schemeslevel2approvalcreate") {
        $data['level2approver'] = \Session:: get('id');
    } else {
        $data['level1approver'] = \Session:: get('id');
    }

    $lineCount = count($form['line_no'] ?? []);
    $lines_data = [];

    for ($i = 0; $i < $lineCount; $i++) {

        $lines_data[$i] = [

            'schemes_hdr_id' => null,
            'line_no'               => $form['line_no'][$i],
            'product_id'            => $form['product_id'][$i],
            'scheme_base'           => $form['scheme_base'][$i],
            'schemes_type'          => $form['schemes_type'][$i],
            'scheme_base_value_from'=> $form['scheme_base_value_from'][$i],
            'scheme_base_value_to'  => $form['scheme_base_value_to'][$i],
            'schemes_type_value'    => $form['schemes_type_value'][$i],
            'comments'              => $form['comments'][$i],
            'line_pdt_id'       =>     $form['line_product_id'][$i],
            'line_pdt_qty'           => $form['pdt_qty'][$i],
            // Required standard columns

            'created_by'      => \Session::get('id'),
            'created_at'      => now(),
            'last_updated_by' => \Session::get('id'),
            'updated_at'      => now(),
            'location_id'     => \Session::get('location'),
            'company_id'      => \Session::get('companyid'),
            'organization_id' => \Session::get('organization'),
        ];
    }

    \DB:: beginTransaction();

    try {

        // Save header
        $id = $this -> model -> insertRow($data);

        // Assign FK in each line
        foreach($lines_data as & $ln) {
            $ln['schemes_hdr_id'] = $id;
        }

         $lid =  \DB::table($this->subtable)->insert($lines_data);

        \DB:: commit();

        // Audit
        $this -> auditlog($id, "Schemes", "Create", $_POST, "s_schemes_hdr_t");

        return response() -> json([
            'status' => 'success',
            'message' => 'Saved Successfully',
            'id' => $id,
            'lid' => $lid
        ]);

    } catch (\Illuminate\Database\QueryException $e) {

        \DB:: rollback();
        $message = explode('(', $e -> getMessage());
        $dbCode = trim(trim($message[0], ']'), '[');

        return response() -> json([
            'status' => 'error',
            'message' => 'DatabaseError:=> '.$dbCode
        ]);
    }
}


    //Show function
    public function show(Schemes $schemes, $id = null)
    {
        $table = \DB::table('s_schemes_hdr_t')->where('schemes_hdr_id', $id)->get();
        $this->data['row'] = $table[0];
        $this->data['row']->start_date = date('d-m-Y', strtotime($table[0]->start_date));
        $this->data['row']->end_date = date('d-m-Y', strtotime($table[0]->end_date));
        // dd($table);
        if ($table[0]->location)
            $this->data['row']->location = $this->idname('city_name', 'm_cities_t', 'city_id', $table[0]->location);
        else
            $this->data['row']->location = '';

        $linestable = \DB::table('s_schemes_lines_t')->where('schemes_hdr_id', $id)->get();
        $this->data['linedata'] = $linestable;

        foreach ($this->data['linedata'] as $key => $value) {
            $this->data['linedata'][$key]->product_id = $this->idname('concatenated_product', 'm_products_t', 'product_id', $value->product_id);
        }

        return view('schemes.view', $this->data);
    }

    //Delete data
    public function destroy(Schemes $schemes, $id = null)
    {
        $count = 0;
        $queryquote = \DB::table('s_salesorder_hdr_t')->where('schemes_hdr_id', $id)->count();
        if ($queryquote >= 1) {
            $count++;
        }
        if ($count <= 0) {
            Schemes::destroy($id);
            $query = \DB::table('s_schemes_lines_t')->where('schemes_hdr_id', $id)->delete();
            if ($query) {
                /**Auditlog**/
                $action = "Delete";
                $this->auditlog($id, "Schemes", $action, $id, "s_schemes_hdr_t");
                return 0;
            } else {
                /**Auditlog**/
                $action = "Delete";
                $this->auditlog($id, "Schemes", $action, $id, "s_schemes_hdr_t");
                return 1;
            }
        } else {
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id, "Schemes", $action, $id, "s_schemes_hdr_t");
            return 2;
        }

    }



}
