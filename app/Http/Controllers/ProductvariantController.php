<?php

namespace App\Http\Controllers;

use App\Productvariant;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class ProductvariantController extends Controller
{
    public function __construct()
    {
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
    }

    /*Grid Data For displaying in Tables*/
    public function getproductvariantData()
    {

        $wh = '';


        $comp = \Session::get('companyid');

        $SQL = "SELECT m_product_variants_t.*,tb_users.first_name FROM m_product_variants_t left join `tb_users` on (tb_users.id=m_product_variants_t.created_by) where 1=1 and m_product_variants_t.company_id=$comp $wh";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);
    }


    /*Main page Load Function & Create Form */
    public function create(Request $request, $id = null)
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

        $Productvariant = Productvariant::find($id);
        $this->data['row'] = (object) array();
        $sql = \Session::get('id');
        $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', $sql);
        $this->data['row']->product_variant_name = "";
        $this->data['row']->variant_description = "";

        $this->data['row']->active = "";
        $this->data['pageMethod'] = \Request::route()->getName();

        return view('productvariant.form', $this->data);
    }
    /*End*/

    /*Duplicate Check Function*/
    public function getProductvariantcheckname(Request $request)
    {

        $product_variant_id = $_REQUEST['product_variant_id'];

        if ($product_variant_id == '')
            $uom = \DB::table('m_product_variants_t')->where('product_variant_name', $_REQUEST['product_variant_name'])->get();
        else {
            $whereData = [['product_variant_name', $_REQUEST['product_variant_name']], ['product_variant_id', '!=', $product_variant_id]];

            $uom = \DB::table('m_product_variants_t')->where($whereData)->get();
        }


        if (count($uom) > 0)
            return 1;
        else
            return 0;


    }
    /*End*/


    /*Save Function*/
    public function save(Request $request)
    {
        date_default_timezone_set("Asia/Calcutta");
        $product_variant_id = $request->input('product_variant_id');

        if ($product_variant_id == '') {

            $Productvariant = new Productvariant();
            $Productvariant->product_variant_name = $_POST['product_variant_name'];
            $Productvariant->variant_description = $_POST['variant_description'];
            $Productvariant->active = $_POST['active'];
            $Productvariant->created_by = \Session::get('id');
            $Productvariant->updated_at = date('Y-m-d H:i:s');
            $Productvariant->created_at = date('Y-m-d H:i:s');
            $Productvariant->last_updated_by = \Session::get('id');
            $Productvariant->organization_id = \Session::get('organization');
            $Productvariant->company_id = \Session::get('companyid');
            $Productvariant->location_id = \Session::get('location');
            $Productvariant->save();
            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";
            /**Auditlog**/
            $this->auditlog($edit_id, "productvariant", $action, $_POST, "m_product_variants_t");

            return response()->json(array('status' => 'success', 'message' => 'Product Variant Saved Successfully', 'id' => $product_variant_id));

        } else {
            // dd($_POST);
            $product_variant_id = $_POST['product_variant_id'];
            Productvariant::find($product_variant_id)->update($_POST);
            $action = "Edit";
            $edit_id = $_POST['product_variant_id'];
            /**Auditlog**/
            $this->auditlog($edit_id, "productvariant", $action, $_POST, "m_product_variants_t");
            return response()->json(array('status' => 'success', 'message' => 'Product Variant Updated Successfully', 'id' => $product_variant_id));

        }
    }
    /*End*/

    /*Delete Function*/
    public function delete($id = null)
    {
        $count = 0;
        $queryquote = \DB::table('m_products_t')->where('product_variant_id', $id)->count();
        if ($queryquote >= 1) {
            $count++;
        }
        if ($count <= 0) {
            $query = \DB::table('m_product_variants_t')->where('product_variant_id', $id)->delete();
            /**Auditlog**/
            $this->auditlog($id, "productvariant", "delete", "", "m_product_variants_t");
            if ($query) {
                return 0;
            } else {
                return 1;
            }
        } else {
            return 2;
        }
    }
    /*End*/

    /*Edit Function*/
    public function productvarianteditchk($id = null)
    {
        $prdgrpid = $id;

        $column = array('product_variant_id');
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
}
