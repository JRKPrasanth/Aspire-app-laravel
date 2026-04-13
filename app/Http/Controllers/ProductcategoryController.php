<?php
namespace App\Http\Controllers;
use App\Productgroup;
use App\Productcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class ProductcategoryController extends Controller
{
    public function __construct()
    {
        $this->data = array(
            'pageModule' => 'Productcategory',
            'pageUrl' => url('productcategory')
        );
        $this->data['urlmenu'] = $this->indexs();
        $this->model = new Productcategory();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageMethod'] = 'productcategory';
        $this->data['pageModule'] = 'productcategory';
    }


    public function getProductcategoryData()
    {

        $wh = '';


        $comp = \Session::get('companyid');

        $SQL = "SELECT tb_users.first_name,m_product_category_t.product_category_id,m_product_category_t.category_name,m_product_category_t.description,m_product_category_t.active,m_product_groups_t.product_group_id as group_id,m_product_groups_t.group_name as group_name FROM m_product_category_t left join m_product_groups_t on m_product_groups_t.product_group_id=m_product_category_t.product_group_id left join `tb_users` on (tb_users.id=m_product_category_t.created_by)  where 1=1 and m_product_category_t.company_id=$comp $wh ORDER BY product_category_id desc";


        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }


    /*Create Function*/
    public function create(Request $request, $id = null, $type = null)
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

        if (isset($id)) {
            $productcategory = Productcategory::find($id);

            $this->data['row'] = $productcategory;
            $this->data['product_group_id'] = $this->jcombo("m_product_groups_t", "product_group_id", "group_name", $this->data['row']->product_group_id);

            if ($type == 'g') {
                return $productcategory;
            }


        } else {
            $productcategorydata = \DB::connection()->getSchemaBuilder()->getColumnListing('m_product_category_t');
            $productcategorydatas = (object) array();
            foreach ($productcategorydata as $key => $value) {
                $productcategorydatas->$value = "";
            }
            $this->data['row'] = $productcategorydatas;
            $this->data['product_group_id'] = $this->jcombo("m_product_groups_t", "product_group_id", "group_name", "");
        }
        $this->data['opt'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');

        $this->data['pageMethod'] = "productcategory";


        return view('productcategory.form', $this->data);
    }
    /*End*/


    /*Save Function*/
    public function save(Request $request)
    {
        // dd($_POST);
        $productcate = new productcategory();
        $edit_id = $request->input('edit_id');
        if ($edit_id == '') {
            $productcate->category_name = $_POST['category_name'];
            $productcate->product_group_id = $_POST['product_group_id'];
            $productcate->description = $_POST['description'];
            $productcate->active = $_POST['active'];
            $productcate->created_by = $_POST['created_by'];
            $productcate->company_id = \Session::get('companyid');
            $productcate->location_id = \Session::get('location');
            $productcate->save();
            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";
            /**Auditlog**/
            $this->auditlog($edit_id, "productcategory", $action, $_POST, "m_product_category_t");
            return response()->json(array('status' => 'success', 'message' => 'Product Category Saved Successfully', 'id' => $edit_id));
        } else {
            Productcategory::find($edit_id)->update($_POST);
            $action = "Edit";
            /**Auditlog**/
            $this->auditlog($edit_id, "productcategory", $action, $_POST, "m_product_category_t");
            return response()->json(array('status' => 'success', 'message' => 'Product Category Updated Successfully', 'id' => $edit_id));
        }

    }
    /*End*/

    /*Delete Function*/
    public function getRemove(Request $request, $id = null)
    {

        $column = array('product_category_id');
        $table = array('m_products_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }
        if ($j == 0) {
            $query = \DB::table('m_product_category_t')->where('product_category_id', $id)->delete();
            /**Auditlog**/
            $this->auditlog($id, "productcategory", "Delete", "", "m_product_category_t");
        }

        return $j;

    }

    /*End Delete Function*/

    /*Checking Product Category Name*/
    public function productcategoryeditchk($id = null, $grpid = null)
    {

        $prdgrpid = $id = $_GET['id'];

        $column = array('product_category_id');
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
