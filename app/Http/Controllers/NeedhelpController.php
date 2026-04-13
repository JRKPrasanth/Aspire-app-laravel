<?php

namespace App\Http\Controllers;

use App\Needhelp;
use App\Needhelplines;
use Illuminate\Http\Request;
use DB;
use Validator, Input, Redirect, Session;

class NeedhelpController extends Controller
{

    public function __construct()

    {
        $this->data = array();
        $this->table = "a_sop_t";
        $this->pageModule = "needhelp";
        $this->model = new Needhelp;
        $this->submodel = new Needhelplines;
        $this->data['pageFormtype'] = 'ajax';
        $this->table = "a_sop_t";
        $this->subtable = "a_sop_line_t";
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'needhelp',
            'pageUrl'   =>  url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu'] = $this->indexs();
    }


    public function index()
    {
        $this->data['help_rec'] = DB::table('a_sop_t')
            ->select('a_sop_t.*', 'p.menus_name AS primary_menu', 's.menus_name AS submenu', 'm.menus_name AS menu')
            ->leftJoin('tb_menus AS p', 'a_sop_t.primary_menu', '=', 'p.menus_id')
            ->leftJoin('tb_menus AS s', 'a_sop_t.submenu', '=', 's.menus_id')
            ->leftJoin('tb_menus AS m', 'a_sop_t.menu', '=', 'm.menus_id')
            ->get();
            $this->data['pageMethod'] = \Request::route()->getName();
        return view('needhelp.table', $this->data);
    }
  //---- END ------

    public function create($id = null)
    {
        if ($id == 0) {

            $this->data['primary_menu'] = $this->jcustomselecttool("tb_menus", "menus_id", "menus_name", "menus_id", " and parent_id=0");
            $this->data['submenu'] = $this->jCombo('tb_menus', 'menus_id', 'menus_name', '');
            $this->data['menu'] = $this->jCombo('tb_menus', 'menus_id', 'menus_name', '');
            $this->data['url'] = $this->jCombo('tb_menus','controller_name','controller_name', '');
            
            $table = \DB::table('a_sop_t')->get();
            $this->data['data'] = $table;
            $this->data['row'] = (object)[];
            $this->data['row']->sop_id = "";

            $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['pageMethod'] = "createsop";
            $this->data['linedata'] = [];

            return view('needhelp.form', $this->data);
        } else {

            $table = \DB::table('a_sop_t')->where('sop_id', $id)->get();

            $this->data['primary_menu'] = $this->jcustomselecttool("tb_menus", "menus_id", "menus_name", $table[0]->primary_menu, " and parent_id=0");
            $this->data['submenu'] = $this->jCombo('tb_menus', 'menus_id', 'menus_name', $table[0]->submenu);
            $this->data['menu'] = $this->jCombo('tb_menus', 'menus_id', 'menus_name', $table[0]->menu);
            $this->data['url'] = $this->jCombo('tb_menus', 'controller_name', 'controller_name', $table[0]->url);
            $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['data'] = $table;
            $this->data['row'] = (object)[];

            $this->data['row']->url = $table[0]->url;
             $this->data['row']->sop_id = $table[0]->sop_id;
            $this->data['pageMethod'] = "sopedit";


            $tablelines = \DB::table('a_sop_line_t')->where('sopid', $id)->get();
            $this->data['linedata'] = $tablelines;
        }
        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->activities = $value->activities;
            }
        }

        return view('needhelp.form', $this->data);
    }
// -------END-----------

      public function save(Request $request)
        {
            
        $data = $this->validatePost($request->all(), $this->table, 'header');
        $lines_data = $this->validatePost($request->all(), $this->subtable, 'lines');
        
        \DB::beginTransaction();
        try {
            $needhelp = new Needhelp();
            $edit_id = $request->input('sop_id');
            
        //dd($edit_id);
        
        if ($edit_id =="") {
        
            $needhelp->primary_menu = $_POST['primary_menu_id'];
            $needhelp->submenu = $_POST['sub_menu_id'];
            $needhelp->menu = $_POST['menu_id'];
            $needhelp->url = $_POST['url'];
            $needhelp->created_by = $_POST['created_by'];
        
            $needhelp['last_updated_by'] = \Session::get('id');
            $needhelp['updated_at'] = date('Y-m-d h:s:i');
            $needhelp['created_at'] = date('Y-m-d h:s:i');
            $needhelp['organization_id'] = \Session::get('organization');
            $needhelp['location_id'] = \Session::get('location');
            $needhelp['company_id'] = \Session::get('companyid');
        
            $needhelp->save();
            $id = $needhelp->sop_id;
        } else {
            $need['primary_menu'] = $_POST['primary_menu_id'];
            $need['submenu'] = $_POST['sub_menu_id'];
            $need['menu'] = $_POST['menu_id'];
            $need['url'] = $_POST['url'];
            $need['created_by'] = $_POST['created_by'];
            
            $update = \DB::table('a_sop_t')->where('sop_id', $edit_id)->update($need);
            
            $id = $edit_id;

        }
        
        /******************sop Lines save***************************/
        
        $sop_line_id = $_POST['bulk_sop_line_id'];
        
        $check = \DB::table('a_sop_line_t')->whereIn('sop_line_id', $sop_line_id)->select('*')->get();
        $oldid = \DB::table('a_sop_line_t')->where('sopid', $id)->get();
        
        if ($oldid->isNotEmpty()) {
        
            $existingId = array();
            $oldIds = array();
            $newIds = array();
        
            foreach ($oldid as $key => $value) {
                $oldIds[] = $value->sop_line_id;

            }
            foreach ($_POST['bulk_sop_line_id'] as $val) {
                $newIds[] = $val;
            }
            $existingId =  array_replace($newIds, $oldIds);
            $oldcount = count($oldIds);
            $newcount = count($newIds);
        
        
            $arraydiff =  array_diff($oldIds, $newIds);
        
            foreach ($arraydiff as $key) {
                if (($key = array_search($key, $oldIds)) !== false) {
                    unset($oldIds[$key]);
                }
            }
        
            foreach ($arraydiff as $val) {
                \DB::table('a_sop_line_t')->where('sop_line_id', $val)->delete();
            }
        
        
            foreach ($sop_line_id as $key => $value) {
        
                $dataup1['sopid'] = $id;
                $dataup1['activities'] = $_POST['bulk_activities'][$key];
                $dataup1['line_no'] = $_POST['bulk_line_no'][$key];
                $dataup1['active'] = $_POST['bulk_active'][$key];
                $dataup1['created_by'] = \Session::get('id');
                $dataup1['last_updated_by'] = \Session::get('id');
                $dataup1['updated_at'] = date('Y-m-d h:s:i');
                $dataup1['created_at'] = date('Y-m-d h:s:i');
                $dataup1['organization_id'] = \Session::get('organization');
                $dataup1['location_id'] = \Session::get('location');
                $dataup1['company_id'] = \Session::get('companyid');
        
                if ($value != "") {
                     $sop_line_id = $_POST['sop_line_id'][$key];
                    \DB::table('a_sop_line_t')->where('sop_line_id', $value)->update($dataup1);
                } else {
                    \DB::table('a_sop_line_t')->insert($dataup1);
                }
            }
        } else {
        
                foreach ($sop_line_id as $key => $value) {
                $dataup2['sopid'] = $id;
                $dataup2['activities'] = $_POST['bulk_activities'][$key];
                $dataup2['line_no'] = $_POST['bulk_line_no'][$key];
                $dataup2['active'] = $_POST['bulk_active'][$key];
                $dataup2['created_by'] = \Session::get('id');
                $dataup2['last_updated_by'] = \Session::get('id');
                $dataup2['updated_at'] = date('Y-m-d h:s:i');
                $dataup2['created_at'] = date('Y-m-d h:s:i');
                $dataup2['organization_id'] = \Session::get('organization');
                $dataup2['location_id'] = \Session::get('location');
                $dataup2['company_id'] = \Session::get('companyid');
        
                \DB::table('a_sop_line_t')->insert($dataup2);
            }
        }
        
        /***********************sop Lines save end *********************/
        
        \DB::commit();
        
        
        return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id));

        
        } catch (\Illuminate\Database\QueryException $e) {
        $message = explode('(', $e->getMessage());
        $dbCode = rtrim($message[0], ']');
        $dbCode = trim($dbCode, '[');
        
        \DB::rollback();
        return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
        }
        
    // -------END-----------


    function view($id = null)
    {

    $headerdata = \DB::table('a_sop_t')
        ->select('a_sop_t.*', 'p.menus_name AS primary_menu', 's.menus_name AS submenu', 'm.menus_name AS menu','c.first_name AS created')
        ->leftJoin('tb_menus AS p', 'a_sop_t.primary_menu', '=', 'p.menus_id')
        ->leftJoin('tb_menus AS s', 'a_sop_t.submenu', '=', 's.menus_id')
        ->leftJoin('tb_menus AS m', 'a_sop_t.menu', '=', 'm.menus_id')
        ->leftJoin('hr_employee_t AS c', 'a_sop_t.created_by', '=', 'c.employee_id')
        ->where('sop_id', $id)
        ->get();


        $linesdata  = \DB::table('a_sop_line_t')
            ->select('a_sop_line_t.activities', 'a_sop_line_t.active')
            ->where('a_sop_line_t.sopid', $id)
            ->get();

        $this->data['headerdata'] = $headerdata[0];
        $this->data['linesdata'] = $linesdata;

        return view('needhelp.view', $this->data);
    }


    public function delete(Request $request, $id = null)
    {
        if ($id === null) {
            return 2;
        }
        $query = \DB::table('a_sop_line_t')->where('sopid', $id)->delete();
        $query = \DB::table('a_sop_t')->where('sop_id', $id)->delete();


        if ($query) {
            return 1;
        } else {
            return 2;
        }
    }
// -------END-----------


}
