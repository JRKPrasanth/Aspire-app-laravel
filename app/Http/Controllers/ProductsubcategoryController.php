<?php

namespace App\Http\Controllers;
use App\productsubcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class ProductsubcategoryController extends Controller
{

  public function __construct()
  {

    $this->data = array(
      'pageModule' => 'Productsubcategory',
      'pageUrl' => url('productcategory')
    );
    $this->data['urlmenu'] = $this->indexs();
    $this->data['pageFormtype'] = 'ajax';
    $this->data['pageMethod'] = \Request::route()->getName();
  }


  /*Grid Data Load for Subcategory*/
  public function getProductsubcategoryData()
  {
    $wh = '';

    $comp = \Session::get('companyid');

    $SQL = "select tb_users.first_name,m_product_subcategory_t.product_subcategory_id,m_product_subcategory_t.subcategory_name,m_product_subcategory_t.description,m_product_subcategory_t.active,m_product_groups_t.group_name,m_product_category_t.category_name,m_product_groups_t.product_group_id as prdgrpid,m_product_category_t.product_category_id as prdcat FROM m_product_subcategory_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id=m_product_subcategory_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id=m_product_subcategory_t.product_category_id left join `tb_users` on (tb_users.id=m_product_subcategory_t.created_by) where 1=1 and m_product_subcategory_t.company_id=$comp $wh";


    $result = \DB::select($SQL);
    return DataTables::of($result)->make(true);

  }

  /*Main Page for Product Sub Category*/
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

    $com = \Session::get('companyid');
    $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
    $this->data['group'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
    $this->data['cat'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');

    $this->data['product_category_id'] = $this->jcustomselect("m_product_category_t", "product_category_id", "category_name", '', 'and company_id=' . $com);

    $this->data['product_group_id'] = $this->jcustomselect("m_product_groups_t", "product_group_id", "group_name", '', 'and company_id=' . $com);
    $this->data['pageMethod'] = \Request::route()->getName();

    return view('productsubcategory.form', $this->data);
  }
  /*End*/

  /*Save Function*/
  public function save(Request $request)
  {
    $productsubcat = new productsubcategory();

    $edit_id = $request->input('edit_id');

    if ($edit_id == '') {
      $productsubcat->subcategory_name = $_POST['subcategory_name'];
      $productsubcat->product_group_id = $_POST['product_group_id'];
      $productsubcat->product_category_id = $_POST['product_category_id'];
      $productsubcat->description = $_POST['description'];
      $productsubcat->active = $_POST['active'];
      $productsubcat->created_by = $_POST['created_by'];
      $productsubcat->last_updated_by = \Session::get('id');
      $productsubcat->updated_at = date('Y-m-d H:i:s');
      $productsubcat->created_at = date('Y-m-d H:i:s');
      $productsubcat->company_id = \Session::get('companyid');
      $productsubcat->organization_id = \Session::get('organization');
      $productsubcat->location_id = \Session::get('location');
      $productsubcat->save();
      $edit_id = DB::getPdo()->lastInsertId();
      $action = "Create";
      /**Auditlog**/
      $this->auditlog($edit_id, "productsubcategory", $action, $_POST, "m_product_subcategory_t");
      return response()->json(array('status' => 'success', 'message' => 'Product Subcategory Saved Successfully!!', 'id' => $edit_id));
    } else {
      Productsubcategory::find($edit_id)->update($_POST);
      $action = "Edit";
      /**Auditlog**/
      $this->auditlog($edit_id, "productsubcategory", $action, $_POST, "m_product_subcategory_t");
      return response()->json(array('status' => 'success', 'message' => 'Product Subcategory updated Successfully!!', 'id' => $edit_id));
    }



  }
  /*End*/

  /*Edit Function*/
  public function productsubcategoryedit($id = null)
  {
    $id = $_GET['id'];
    $column = array('product_subcategory_id');
    $table = array('m_products_t');

    for ($i = 0; $i < count($table); $i++) {
      $j = 0;
      $query = \DB::table($table[$i])->where($column[$i], $id)->get();
      if (count($query) > 0) {
        $j = 1;
        break;
      }
    }
    return $j;
  }
  /*End*/

  /*Delete Function*/
  public function destroy($del_id)
  {
    $column = array('product_subcategory_id');
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
      $query = \DB::table('m_product_subcategory_t')->where('product_subcategory_id', $del_id)->delete();
      /**Auditlog**/
      $this->auditlog($del_id, "productsubcategory", "delete", "", "m_product_subcategory_t");
    }
    return $j;

  }
  /*end*/

  /*deepika purpose:duplicate name function*/
  public function getCheckname(Request $request)
  {

    $edit_id = $_GET['edit_id'];
    $group_name = $_GET['product_group_id'];
    $product_category_id = $_GET['product_category_id'];

    if ($edit_id == '')
      $group = \DB::table('m_product_subcategory_t')->where('product_group_id', $group_name)->where('product_category_id', $product_category_id)->where('subcategory_name', $_GET['subcategory_name'])->get();
    else {
      $whereData = [['subcategory_name', $_GET['subcategory_name']], ['product_group_id', '=', $group_name], ['product_category_id', '=', $product_category_id], ['product_subcategory_id', '!=', $edit_id]];

      $group = \DB::table('m_product_subcategory_t')->where($whereData)->get();

    }
    if (count($group) > 0)
      return 1;
    else
      return 0;
  }
  /*End*/



}
