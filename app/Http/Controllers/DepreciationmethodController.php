<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;
use App\Depreciationmethod;


class DepreciationmethodController extends Controller
{
    public $module = "depreciationmethod";
    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->table = "f_depreciation_method_t";
        $this->pageModule = "depreciationmethod";
        $this->model = new Depreciationmethod();
        $this->model = new Depreciationmethod;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->table = "f_depreciation_method_t";

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

        $this->data['depreciation_method_name'] = $this->jqgridcustselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code', 'AND lookup_type="DEPRECIATION_METHOD"');
        $this->data['assettype'] = $this->jqgridselect('f_asset_types_t', 'asset_type_id', 'asset_type_name');
        $this->data['productid'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $this->data['pohdrid'] = $this->jqgridselect('p_po_hdr_t', 'po_hdr_id', 'po_number');
        $table = \DB::table('f_depreciation_method_t')->get();
        $this->data['datas'] = $table;

        return view('depreciationmethod.table', $this->data);
    }

    public function index1()
    {

        return view('depreciationmethod.report_table', $this->data);
    }


    public function getdepricisionData(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "select * from (SELECT (select a_lookuplines_t.lookup_code from a_lookuplines_t where  a_lookuplines_t.lookuplines_id=f_depreciation_method_t.`depreciation_method_name`) as depreciation_method_name,
(select f_asset_types_t.asset_type_name from f_asset_types_t where f_asset_types_t.asset_type_id=f_depreciation_method_t.asset_type_id) as asset_type_name ,
(select f_asset_category_t.asset_category_name from f_asset_category_t where f_asset_category_t.asset_category_id=f_depreciation_method_t.asset_category_id) as asset_category_name,
(select p_po_hdr_t.po_number from p_po_hdr_t where p_po_hdr_t.po_hdr_id=f_depreciation_method_t.po_hdr_id)as po_number,
(select m_products_t.concatenated_product from m_products_t where m_products_t.product_id=f_depreciation_method_t.product_id) as product_name,
f_depreciation_method_t.unit_price,
f_depreciation_method_t.depreciation_method_id,
f_depreciation_method_t.salvage,
 f_depreciation_method_t.created_at,
f_depreciation_method_t.salvage_percentage,
f_depreciation_method_t.salvage_value,
f_depreciation_method_t.useful_life,
f_depreciation_method_t.depreciable_base,
f_depreciation_method_t.depreciation_value,
(select tb_users.username from tb_users where tb_users.id=f_depreciation_method_t.created_by) as username
FROM `f_depreciation_method_t`  WHERE 1=1 and f_depreciation_method_t.created_at between ? and ?)v1";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return response()->json(['data' => $results]);
    }


    public function getdepreciationData()
    {
        $wh = '';

        $compy = \Session::get('companyid');
        $wh .= 'and f_depreciation_method_t.company_id=' . $compy;


        $SQL = "SELECT 
                    f_depreciation_method_t.depreciation_method_id,
                    a_lookuplines_t.lookup_code,
                    f_depreciation_method_t.salvage_value,
                    f_asset_types_t.asset_type_name,
                    p_po_hdr_t.po_number,
                    m_products_t.concatenated_product,
                    f_asset_types_t.asset_type_name,
                    f_depreciation_method_t.depreciation_value,
					tb_users.username
                    FROM f_depreciation_method_t
                    left join f_asset_types_t on(f_asset_types_t.asset_type_id=f_depreciation_method_t.asset_type_id)
                    left join a_lookuplines_t on(a_lookuplines_t.lookuplines_id=f_depreciation_method_t.depreciation_method_name)
                    left join p_po_hdr_t on(p_po_hdr_t.po_hdr_id=f_depreciation_method_t.po_hdr_id)
                    left join m_products_t on(m_products_t.product_id=f_depreciation_method_t.product_id)
					left join tb_users on (tb_users.id =f_depreciation_method_t.created_by)
                    where 1=1 $wh ";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }
    public function create($id = null)
    {

        //$this->data =array('pageModule'=>'depreciationmethod','pageUrl'=>url('depreciationmethod'));
        $this->data['pageModule'] = 'depreciationmethod';
        $this->data['pageUrl'] = url('depreciationmethod');

        if ($id == null) {
            $this->data['row'] = (object) array();
            $this->data['row']->depreciation_method_id = "";
            $this->data['row']->asset_type_id = "";
            $this->data['depreciation_method_name'] = $this->jcustomselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code', '', 'AND lookup_type="DEPRECIATION_METHOD"');
            $this->data['row']->useful_life = "";
            $this->data['row']->salvage = "";
            $this->data['row']->salvage_value = "";
            $this->data['row']->salvage_percentage = "";
            $this->data['row']->depreciation_value = "";
            $this->data['row']->unit_price = "";
            $this->data['row']->depreciable_base = "";

            $sql = \DB::select("select po_hdr_id from p_po_lines_t left join m_products_t on m_products_t.product_id=p_po_lines_t.product_id where m_products_t.product_group_id=17 group by p_po_lines_t.po_hdr_id ");
            $po_hdr_id = '';
            foreach ($sql as $key => $val) {
                $po_hdr_id .= $val->po_hdr_id . ",";

            }
            $po_id = rtrim($po_hdr_id, ",");

            //  $this->data['po_hdr_id'] = $this->jCombocomp('p_po_hdr_t','po_hdr_id','po_number','');

            $this->data['po_hdr_id'] = $this->jcustomselecttool('p_po_hdr_t', 'po_hdr_id', 'po_number', '', ' and po_hdr_id in ' . "(" . $po_id . ")");
            $this->data['product_id'] = "";
            $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'depreciationmethod');

            $this->data['asset_type_id'] = $this->jCombo('f_asset_types_t', 'asset_type_id', 'asset_type_name', '');
            $this->data['asset_category_id'] = $this->jCombo('f_asset_category_t', 'asset_category_id', 'asset_category_name', '');
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        } else {

            $table = \DB::table('f_depreciation_method_t')->where('depreciation_method_id', $id)->get();
            //                        dd($table);

            $sql = \DB::select("select po_hdr_id from p_po_lines_t left join m_products_t on m_products_t.product_id=p_po_lines_t.product_id where m_products_t.product_group_id=17 group by p_po_lines_t.po_hdr_id ");
            $po_hdr_id = '';
            foreach ($sql as $key => $val) {
                $po_hdr_id .= $val->po_hdr_id . ",";

            }
            $po_id = rtrim($po_hdr_id, ",");
            $this->data['po_hdr_id'] = $this->jcustomselecttool('p_po_hdr_t', 'po_hdr_id', 'po_number', $table[0]->po_hdr_id, ' and po_hdr_id in ' . "(" . $po_id . ")");

            //$this->data['po_hdr_id'] = $this->jCombocomp('p_po_hdr_t','po_hdr_id','po_number',$table[0]->po_hdr_id);
            $this->data['depreciation_method_name'] = $this->jcustomselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code', $table[0]->depreciation_method_name, 'AND lookup_type="DEPRECIATION_METHOD"');
            $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $table[0]->product_id, 'depreciationmethod');

            $this->data['asset_type_id'] = $this->jCombo('f_asset_types_t', 'asset_type_id', 'asset_type_name', $table[0]->asset_type_id);
            $this->data['asset_category_id'] = $this->jCombo('f_asset_category_t', 'asset_category_id', 'asset_category_name', $table[0]->asset_category_id);
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['row'] = $table[0];


        }
        //	dd($this->data);
        return view('depreciationmethod.form', $this->data);
    }
    public function getpodetails($po_hdrid = null)
    {
        $sql = \DB::select("select product_id from p_po_lines_t where po_hdr_id='$po_hdrid'");
        $product = '';
        foreach ($sql as $key => $val) {
            $product .= $val->product_id . ",";

        }
        $prd1 = rtrim($product, ",");
        $productdata = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', '', ' and product_group_id=17 and product_id in ' . "(" . $prd1 . ")");
        return $productdata;
    }
    public function getprice($prdid = null)
    {
        $sql = \DB::select("select unit_price from p_po_lines_t where product_id='$prdid' ");
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }

    /* purpose for Save function*/
    public function save(Request $request)
    {

        $id = '';
        $form = $request->all();
        $dataupload = "";
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Depreciation Method Saved', 'id' => $id));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }
    /* purpose for Display View function*/
    public function show(request $request, $id = null)
    {
        if (isset($id)) {
            $vdata = \DB::table('f_depreciation_method_t')
                ->select('f_depreciation_method_t.*', 'f_asset_types_t.asset_type_name', 'f_asset_category_t.asset_category_name', 'm_products_t.concatenated_product', 'p_po_hdr_t.po_number', 'a_lookuplines_t.lookup_code')
                ->leftjoin('f_asset_types_t', 'f_asset_types_t.asset_type_id', '=', 'f_depreciation_method_t.asset_type_id')
                ->leftjoin('f_asset_category_t', 'f_asset_category_t.asset_category_id', '=', 'f_depreciation_method_t.asset_category_id')
                ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'f_depreciation_method_t.product_id')
                ->leftjoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'f_depreciation_method_t.po_hdr_id')
                ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'f_depreciation_method_t.depreciation_method_name')
                ->where('depreciation_method_id', $id)->get();

            $this->data['depreciation_method_name'] = $vdata[0]->lookup_code;
            $this->data['salvage'] = $vdata[0]->salvage;
            $this->data['useful_life'] = $vdata[0]->useful_life;
            $this->data['salvage_value'] = $vdata[0]->salvage_value;
            $this->data['salvage_percentage'] = $vdata[0]->salvage_percentage;
            $this->data['depreciation_value'] = $vdata[0]->depreciation_value;
            $this->data['unit_price'] = $vdata[0]->unit_price;
            $this->data['po_number'] = $vdata[0]->po_number;
            $this->data['concatenated_product'] = $vdata[0]->concatenated_product;
            $this->data['asset_type_name'] = $vdata[0]->asset_type_name;
            $this->data['asset_category_name'] = $vdata[0]->asset_category_name;
            $this->data['depreciable_base'] = $vdata[0]->depreciable_base;

            return view('depreciationmethod.view', $this->data);
        }
    }


}
