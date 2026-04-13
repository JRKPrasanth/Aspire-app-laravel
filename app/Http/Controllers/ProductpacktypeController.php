<?php

namespace App\Http\Controllers;

use App\Productpacktype;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class ProductpacktypeController extends Controller
{


    /*Grid Data For Displaying Datas in Tables*/
    public function packtypegriddata()
    {
        $wh = '';

        $comp = \Session::get('companyid');

        $SQL = "SELECT
               tb_users.first_name,
               i_product_packs_types_t.product_packs_type_id,
               i_product_packs_types_t.product_pack_type_name,
               i_product_packs_types_t.description,
               i_product_packs_types_t.active
                FROM `i_product_packs_types_t` 
                left join `tb_users` on (tb_users.id=i_product_packs_types_t.created_by)
                 where 1=1 and i_product_packs_types_t.company_id=$comp $wh ORDER BY i_product_packs_types_t.product_packs_type_id desc";




        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }






    /*Main page Load Function & Create Form */
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

        $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
        $Productpacktype = \DB::connection()->getSchemaBuilder()->getColumnListing('i_product_packs_types_t');
        $Productpacktypes = (object) array();

        foreach ($Productpacktype as $key => $value) {
            $Productpacktypes->$value = "";
        }

        $this->data['row'] = $Productpacktypes;

        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();

        return view('Productpacktype.form', $this->data);
    }
    /*End*/


    /*Edit Function*/
    public function productpacktypeeditchk($id = null)
    {
        $prdgrpid = $id;

        $column = array('product_packtype_id');
        $table = array('m_products_t');

        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $prdgrpid)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }
        return $j;

    }
    /*End*/

    /*Save Function*/
    public function save(Request $request)
    {
        // dd($_POST);
        date_default_timezone_set("Asia/Calcutta");
        $Productpacktype = new Productpacktype();
        $edit_id = $request->input('edit_id');
        if ($edit_id == "") {
            $Productpacktype->product_pack_type_name = $_POST['product_pack_type_name'];
            $Productpacktype->description = $_POST['description'];
            $Productpacktype->active = $_POST['active'];
            $Productpacktype->created_by = $_POST['created_by'];
            $Productpacktype->updated_at = date('Y-m-d H:i:s');
            $Productpacktype->updated_by = \Session::get('id');
            $Productpacktype->created_at = date('Y-m-d H:i:s');
            $Productpacktype->organization_id = \Session::get('organization');
            $Productpacktype->company_id = \Session::get('companyid');
            $Productpacktype->location_id = \Session::get('location');
            $Productpacktype->save();
            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";
            /**Auditlog**/
            $this->auditlog($edit_id, "productpacktype", $action, $_POST, "i_product_packs_types_t");
            return response()->json(array('status' => 'success', 'message' => 'Product Pack Type Saved Successfully', 'id' => $edit_id));
        } else {
            Productpacktype::find($edit_id)->update($_POST);
            $action = "Edit";
            $edit_id = $_POST['edit_id'];
            /**Auditlog**/
            $this->auditlog($edit_id, "productpacktype", $action, $_POST, "i_product_packs_types_t");
            return response()->json(array('status' => 'success', 'message' => 'Product Pack Type Updated Successfully', 'id' => $edit_id));
        }
    }
    /*End*/



    /*Delete Function*/
    public function delete($del_id)
    {

        $column = array('product_packtype_id');
        $table = array('m_products_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $del_id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }
        if ($j == 0) {
            $query = \DB::table('i_product_packs_types_t')->where('product_packs_type_id', $del_id)->delete();
            $this->auditlog($del_id, "productpacktype", "Delete", "", "i_product_packs_types_t");
        }

        return $j;
    }
    /*End*/





    /*Duplicate Name Check*/
    public function packtypecheckname(Request $request)
    {
        $edit_id = $_GET['edit_id'];


        if ($edit_id == '') {
            $group = \DB::table('i_product_packs_types_t')->where('product_pack_type_name', $_GET['product_pack_type_name'])->get();
            // dd($group);
        } else {

            $whereData = [['product_pack_type_name', $_GET['product_pack_type_name']], ['product_packs_type_id', '!=', $edit_id]];

            $group = \DB::table('i_product_packs_types_t')->where($whereData)->get();

        }

        if (count($group) > 0) {

            return 1;
        } else {
            return 0;
        }
    }
    /*End*/

}
