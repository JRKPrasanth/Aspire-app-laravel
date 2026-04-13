<?php

namespace App\Http\Controllers;

use App\Productpack;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class ProductpackController extends Controller
{


    public function productpackgriddata()
    {
        $wh = '';

        $comp = \Session::get('companyid');


        $SQL = "SELECT
     tb_users.first_name,i_product_packs.packing_id,i_product_packs.pack_name,i_product_packs.description,i_product_packs.active
                FROM `i_product_packs` left join `tb_users` on (tb_users.id=i_product_packs.created_by) 
                 where 1=1 and i_product_packs.company_id=$comp $wh ORDER BY i_product_packs.packing_id DESC";



        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }



    /*Main Function for loading Pages & Create Form*/
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
        $Productpack = \DB::connection()->getSchemaBuilder()->getColumnListing('i_product_packs');
        $Productpacks = (object) array();

        foreach ($Productpack as $key => $value) {
            $Productpacks->$value = "";
        }

        $this->data['row'] = $Productpacks;

        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();

        return view('Productpack.form', $this->data);

    }
    /*End*/


    /*Edit Check Function*/
    public function productpackeditchk($id = null)
    {
        $prdgrpid = $id;

        $column = array('product_pack_id');
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
        $Productpack = new Productpack();
        $edit_id = $request->input('edit_id');
        if ($edit_id == "") {
            $Productpack->pack_name = $_POST['pack_name'];
            $Productpack->description = $_POST['description'];
            $Productpack->active = $_POST['active'];
            $Productpack->created_by = $_POST['created_by'];
            $Productpack->organization_id = \Session::get('organization');
            $Productpack->company_id = \Session::get('companyid');
            $Productpack->location_id = \Session::get('location');
            $Productpack->save();
            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";
            /**Auditlog**/
            $this->auditlog($edit_id, "productpack", $action, $_POST, "i_product_packs");
            return response()->json(array('status' => 'success', 'message' => 'Product Pack Saved Successfully', 'id' => $edit_id));
        } else {
            Productpack::find($edit_id)->update($_POST);
            $action = "Edit";
            $edit_id = $_POST['edit_id'];
            /**Auditlog**/
            $this->auditlog($edit_id, "productpack", $action, $_POST, "i_product_packs");
            return response()->json(array('status' => 'success', 'message' => 'Product Pack Updated Successfully', 'id' => $edit_id));
        }
    }
    /*End*/


    /*Delete Function*/
    public function delete($del_id)
    {

        $column = array('product_pack_id');
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
            $query = \DB::table('i_product_packs')->where('packing_id', $del_id)->delete();
            /**Auditlog**/
            $this->auditlog($del_id, "productpack", "Delete", "", "i_product_packs");
        }

        return $j;
    }
    /*End*/

    /*Duplicate Validate*/
    public function productpackcheckname(Request $request)
    {
        $edit_id = $_GET['edit_id'];


        if ($edit_id == '') {
            $group = \DB::table('i_product_packs')->where('pack_name', $_GET['pack_name'])->get();
            // dd($group);
        } else {

            $whereData = [['pack_name', $_GET['pack_name']], ['packing_id', '!=', $edit_id]];

            $group = \DB::table('i_product_packs')->where($whereData)->get();

        }

        if (count($group) > 0) {

            return 1;
        } else {
            return 0;
        }
    }
    /*End*/



}
