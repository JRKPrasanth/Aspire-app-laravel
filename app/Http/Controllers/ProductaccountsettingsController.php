<?php

namespace App\Http\Controllers;

use App\Productaccountsettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class ProductaccountsettingsController extends Controller
{
  public $module = "productaccountsettings";
  public function __construct()
  {
    $this->data = array();

    $this->table = "f_product_accountsetting_t";
    $this->pageModule = "productaccountsettings";
    $this->model = new Productaccountsettings();
    $this->model = new Productaccountsettings;
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['pageFormtype'] = 'ajax';
    $this->table = "f_product_accountsetting_t";
    $this->data['urlmenu'] = $this->indexs();
  }

  /* Purpose For :Index Function to Call Table Blade*/
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

    $table = \DB::table('f_product_accountsetting_t')->get();
    $this->data['datas'] = $table;

    return view('productaccountsettings.table', $this->data);
  }


  public function getProductaccountsettingData()
  {

    $wh = '';
    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $groupname = \Session::get('groupname');
    if ($groupname == '1' || $groupname == 'Admin') {
      $wh .= 'and  f_product_accountsetting_t.company_id=' . $compy;
    } else {
      $wh .= 'and  f_product_accountsetting_t.company_id=' . $compy;

    }



    $SQL = "SELECT f_product_accountsetting_t.product_accountsetting_id,"
      . "m_product_groups_t.group_name,"
      . "m_product_category_t.category_name,"
      . "m_product_subcategory_t.subcategory_name,"
      . "tb_users.first_name"
      . " FROM f_product_accountsetting_t "
      . "left join m_product_groups_t on(m_product_groups_t.product_group_id=f_product_accountsetting_t.product_group_id)"
      . " left join m_product_category_t on(m_product_category_t.product_category_id=f_product_accountsetting_t.product_category_id)"
      . "left join m_product_subcategory_t on(m_product_subcategory_t.product_subcategory_id=f_product_accountsetting_t.product_subcategory_id)"
      . " left join tb_users on (tb_users.id=f_product_accountsetting_t.created_by)"
      . " where 1=1 $wh";

    $result = \DB::select($SQL);

    return DataTables::of($result)->make(true);
  }


  public function create($id = null)
  {
    $this->data['pageModule'] = 'productaccountsettings';
    $this->data['pageUrl'] = url('productaccountsettings');
    if ($id == null) {
      $this->data['row'] = (object) array();
      $this->data['row']->product_accountsetting_id = "";
      $this->data['row']->active = "";
      $this->data['group_name'] = "";
      $this->data['product_group_id'] = $this->jCombo('m_product_groups_t', 'product_group_id', 'group_name', '');
      $this->data['product_category_id'] = $this->jCombo('m_product_category_t', 'product_category_id', 'category_name', '');
      $this->data['product_subcategory_id'] = $this->jCombo('m_product_subcategory_t', 'product_subcategory_id', 'subcategory_name', '');
      $this->data['product_acccode_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
      $this->data['disc_acccode_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
      $this->data['control_acccode_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
      $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

    } else {
      $table = \DB::table('f_product_accountsetting_t')->where('product_accountsetting_id', $id)->get();
      $this->data['row'] = $table[0];

      $grouptable = \DB::table('m_product_groups_t')->where('product_group_id', $table[0]->product_group_id)->get();
      $this->data['group_name'] = $grouptable[0]->group_name;
      $this->data['product_group_id'] = $this->jCombo('m_product_groups_t', 'product_group_id', 'group_name', $table[0]->product_group_id);
      $this->data['product_category_id'] = $this->jCombo('m_product_category_t', 'product_category_id', 'category_name', $table[0]->product_category_id);
      $this->data['product_subcategory_id'] = $this->jCombo('m_product_subcategory_t', 'product_subcategory_id', 'subcategory_name', $table[0]->product_subcategory_id);
      $this->data['product_acccode_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->product_acccode_id);
      $this->data['disc_acccode_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->disc_acccode_id);
      $this->data['control_acccode_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->control_acccode_id);
      $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
    }
    return view('productaccountsettings.form', $this->data);
  }
  /*Karthigaa purpose for Save function*/
  public function save(Request $request)
  {
    $id = '';
    $data = $this->validatePost($request->all(), $this->table, 'header');
    \DB::beginTransaction();
    try {
      $id = $this->model->insertRow($data);
      \DB::commit();
      return response()->json(array('status' => 'success', 'message' => 'Product Account Settings Saved', 'id' => $id));
    } catch (\Illuminate\Database\QueryException $e) {
      $message = explode('(', $e->getMessage());
      $dbCode = rtrim($message[0], ']');
      $dbCode = trim($dbCode, '[');
      \DB::rollback();
      return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
    }

  }

  /*Karthigaa purpose for Display View function*/
  public function show(request $request, $id = null)
  {

    if (isset($id)) {
      $vdata = \DB::table('f_product_accountsetting_t')->select(
        'f_product_accountsetting_t.*',
        'm_product_groups_t.group_name',
        'm_product_category_t.category_name',
        'm_product_subcategory_t.subcategory_name',
        'subacc1.concatenated_segments as sub1',
        'subacc2.concatenated_segments as sub2',
        'subacc3.concatenated_segments as sub3',
        'tb_users.first_name'
      )
        ->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'f_product_accountsetting_t.product_group_id')
        ->leftjoin('m_product_category_t', 'm_product_category_t.product_category_id', '=', 'f_product_accountsetting_t.product_category_id')
        ->leftjoin('m_product_subcategory_t', 'm_product_subcategory_t.product_subcategory_id', '=', 'f_product_accountsetting_t.product_subcategory_id')
        ->leftjoin('f_account_structure_t as subacc1', 'subacc1.f_account_structure_id', '=', 'f_product_accountsetting_t.product_acccode_id')
        ->leftjoin('f_account_structure_t as subacc2', 'subacc2.f_account_structure_id', '=', 'f_product_accountsetting_t.disc_acccode_id')
        ->leftjoin('f_account_structure_t as subacc3', 'subacc3.f_account_structure_id', '=', 'f_product_accountsetting_t.control_acccode_id')
        ->leftjoin('tb_users', 'tb_users.id', '=', 'f_product_accountsetting_t.created_by')
        ->where('product_accountsetting_id', $id)->get();

      $this->data['group_name'] = $vdata[0]->group_name;
      $this->data['category_name'] = $vdata[0]->category_name;
      $this->data['subcategory_name'] = $vdata[0]->subcategory_name;
      $this->data['sub1'] = $vdata[0]->sub1;
      $this->data['sub2'] = $vdata[0]->sub2;
      $this->data['sub3'] = $vdata[0]->sub3;
      $this->data['active'] = $vdata[0]->active;
      $this->data['first_name'] = $vdata[0]->first_name;

      return view('productaccountsettings.view', $this->data);
    }
  }


}
