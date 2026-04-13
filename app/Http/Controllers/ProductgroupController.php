<?php

namespace App\Http\Controllers;
use App\Productgroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;


class ProductgroupController extends Controller
{
    public function __construct()
    {
        $this->data['pageFormtype'] = 'ajax';
        $this->data['urlmenu'] = $this->indexs();
    }


    //purpose for dynamic data for jqgrid*/

    public function getProductgroupData()
    {

        $wh = '';
        $comp = \Session::get('companyid');


        $SQL = "SELECT tb_users.first_name,m_product_groups_t.product_group_id,m_product_groups_t.group_name,m_product_groups_t.description,m_product_groups_t.active FROM m_product_groups_t left join `tb_users` on (tb_users.id=m_product_groups_t.created_by) where 1=1 and m_product_groups_t.company_id=$comp and m_product_groups_t.company_id=$comp $wh ORDER BY m_product_groups_t.product_group_id desc";


        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }


    /*Create  Function Product Group*/
    public function create(Request $request, $id = nulL)
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

        $productgrpdata = \DB::connection()->getSchemaBuilder()->getColumnListing('m_product_groups_t');
        $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));

        $productgrpdatas = (object) array();
        foreach ($productgrpdata as $key => $value) {
            $productgrpdatas->$value = "";
        }
        $this->data['pageMethod'] = \Request::route()->getName();

        $this->data['row'] = $productgrpdatas;

        return view('productgroup.form', $this->data);
    }
    /*End Function*/

    /*Edit Function */
    public function getedit($edit_id)
    {
        $column = array('product_group_id', 'product_group_id', 'product_group_id');
        $table = array('m_product_category_t', 'm_products_t', 'm_product_subcategory_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $edit_id)->get();

            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        return $j;

    }
    /*End Edit Function */


    /*Save Function*/

    public function save(Request $request)
    {
        date_default_timezone_set("Asia/Calcutta");
        $productgrp = new Productgroup();

        $edit_id = $request->input('edit_id');

        if ($edit_id == '') {

            $productgrp->group_name = $_POST['group_name'];
            $productgrp->description = $_POST['description'];
            $productgrp->active = $_POST['active'];
            $productgrp->created_by = $_POST['created_by'];
            $productgrp->updated_at = date('Y-m-d H:i:s');
            $productgrp->last_updated_by = \Session::get('id');
            $productgrp->created_at = date('Y-m-d H:i:s');
            $productgrp->organization_id = \Session::get('organization');
            $productgrp->company_id = \Session::get('companyid');
            $productgrp->location_id = \Session::get('location');
            $productgrp->save();
            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";
            /**Auditlog**/
            $this->auditlog($edit_id, "productgroup", $action, $_POST, "m_product_groups_t");
            return response()->json(array('status' => 'success', 'message' => 'Product Group Saved Successfully', 'id' => $edit_id));
        } else {
            $edit_id = $_POST['edit_id'];

            Productgroup::find($edit_id)->update($_POST);
            $action = "Edit";
            /**Auditlog**/
            $this->auditlog($edit_id, "productgroup", $action, $_POST, "m_product_groups_t");

            return response()->json(array('status' => 'success', 'message' => 'Product Group Updated Successfully', 'id' => $edit_id));
        }

    }

    /*End Save Function*/

    /* deepika :purpose for check duplicate entry*/
    public function Checkname(Request $request)
    {

        $edit_id = $_REQUEST['edit_id'];
        if ($edit_id == '')
            $group = \DB::table('m_product_groups_t')->where('group_name', $_REQUEST['group_name'])->get();
        else {
            $whereData = [['group_name', $_REQUEST['group_name']], ['product_group_id', '!=', $edit_id]];

            $group = \DB::table('m_product_groups_t')->where($whereData)->get();
        }


        if (count($group) > 0)
            return 1;
        else
            return 0;


    }
    /*end*/

    /*Delete Function*/
    public function delete($del_id)
    {

        $column = array('product_group_id', 'product_group_id', 'product_group_id');
        $table = array('m_product_category_t', 'm_products_t', 'm_product_subcategory_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $del_id)->get();

            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        if ($j == 0) {
            $query = \DB::table('m_product_groups_t')->where('product_group_id', $del_id)->delete();
            /**Auditlog**/
            $this->auditlog($del_id, "productgroup", "delete", "", "m_product_groups_t");
        }

        return $j;
    }

    /*End */

}
