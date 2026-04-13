<?php

namespace App\Http\Controllers;

use App\Manufacturerpartno;
use App\Manufacturerpartnolines;
use App\Http\Controllers\SoorderController;
use Illuminate\Http\Request;
use DB;
use App\Product;
use Yajra\DataTables\DataTables;

class ManufacturerpartnoController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->table = "m_products_t";
        $this->subtable = "m_manufacturer_partno_t";
        $this->pageModule = "manufacturerpartno";
        $this->model = new Manufacturerpartno;
        $this->submodel = new Manufacturerpartnolines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['urlmenu'] = $this->indexs();
    }

    /*Grid Data for loading datas in tables*/
    public function getGridmfgData()
    {
        $wh = '';


        $comp = \Session::get('companyid');

        $SQL = "SELECT m_products_t.product_id,tb_users.first_name,
          m_manufacturer_partno_t.product_id as manufacturer_product_id,
      m_products_t.`product_code`,
          m_products_t.`product_group_id`,
          m_product_groups_t.group_name as group_name,
          m_products_t.remarks,
          m_products_t.`concatenated_product` 
          FROM `m_products_t`  
          JOIN m_product_groups_t ON(m_products_t.`product_group_id`=m_product_groups_t.product_group_id)
          left JOIN m_manufacturer_partno_t  ON(m_products_t.`product_id`=m_manufacturer_partno_t.product_id) left join `tb_users` on (tb_users.id=m_manufacturer_partno_t.created_by) where 1=1 and m_products_t.company_id=$comp $wh group by m_products_t.product_id ORDER BY m_manufacturer_partno_t.product_id DESC";



        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }


    /*Index Function for Loading Main Form*/
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

        $this->data['opt'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
        $this->data['opt1'] = $this->jqgridselect('m_products_t', 'concatenated_product', 'concatenated_product');
        $this->data['pageMethod'] = \Request::route()->getName();

        return view('manufacturerpartno.table', $this->data);
    }
    /*End*/

    /*Create Function For Form*/
    public function create($id = null)
    {
        $mfgdatas = $this->model::find($id);
        $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
        $manufacturerpartno = \DB::table('m_products_t')
            ->select('m_products_t.*')
            ->where('m_products_t.product_id', '=', $id)->get();
        $productid = $manufacturerpartno[0]->product_id;
        $this->data['mfgdatas'] = $mfgdatas;
        $this->data['edit'] = 0;
        $this->data['id'] = $id;
        $this->data['linedata'] = array();
        //dd($id);
        $this->data['product_id'] = $this->jCombo("m_products_t", "product_id", "concatenated_product", $manufacturerpartno[0]->product_id);
        $this->data['product_group_id'] = $this->jCombo("m_product_groups_t", "product_group_id", "group_name", $manufacturerpartno[0]->product_group_id);
        $this->data['manufacturer_source_value_id'] = $this->jCombo("m_supplier_t", "supplier_id", "supplier_name", '');

        $this->data['pageMethod'] = "manufacturerpartno";


        return view('manufacturerpartno.form', $this->data);
    }
    /*End*/


    /*Edit Function*/
    public function edit(Manufacturerpartno $manufacturerpartno, $id = null)
    {
        $mfgdatas = $this->model::find($id);
        $manufacturerpartno = \DB::table('m_products_t')
            ->select('m_products_t.*')
            ->where('m_products_t.product_id', '=', $id)->get();
        $productid = $manufacturerpartno[0]->product_id;
        $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
        $this->data['mfgdatas'] = $mfgdatas;
        $mfgdata = \DB::table('m_manufacturer_partno_t')->where('product_id', $id)->get();
        $this->data['linedata'] = $mfgdata;
        $this->data['edit'] = 1;
        $this->data['product_id'] = $this->jCombo("m_products_t", "product_id", "concatenated_product", $id);
        $this->data['product_group_id'] = $this->jCombo("m_product_groups_t", "product_group_id", "group_name", $manufacturerpartno[0]->product_group_id);
        foreach ($this->data['linedata'] as $key => $value) {
            if ($value->manufacturer_source == "SUPPLIER") {
                $this->data['linedata'][$key]->manufacturer_source_value_id = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $value->manufacturer_source_value_id);
            } else {
                $this->data['linedata'][$key]->manufacturer_source_value_id = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', $value->manufacturer_source_value_id);
            }
        }
        $this->data['pageMethod'] = "manufacturerpartno";
        return view('manufacturerpartno.form', $this->data);
    }
    /*End*/


    /*Save Function For Data*/
    public function save(Request $request)
    {

        			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file','edit_id',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');

        \DB::beginTransaction();
        try {

            $id = $this->model->insertRow($data);

            $lid = $this->submodel->subgridSave($lines_data, $id);
            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";
            /**Auditlog**/
            $this->auditlog($edit_id, "manufacturerpartno", $action, $_POST, "m_manufacturer_partno_t");
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
    /*End*/

    /*View Function*/
    public function show(Manufacturerpartno $manufacturerpartno, $id = null)
    {
        $manufacturerpartno = \DB::table('m_products_t')
            ->leftjoin('m_manufacturer_partno_t', 'm_manufacturer_partno_t.product_id', '=', 'm_products_t.product_id')
            ->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')
            ->select('m_products_t.*', 'm_manufacturer_partno_t.*', 'm_product_groups_t.group_name')
            ->where('m_products_t.product_id', '=', $id)->get();

        $this->data['group_name'] = $manufacturerpartno[0]->group_name;
        $this->data['productid'] = $manufacturerpartno[0]->concatenated_product;
        $this->data['remarks'] = $manufacturerpartno[0]->remarks;
        foreach ($manufacturerpartno as $key => $value) {
            if (!empty($value->manufacturer_source_value_id != "")) {
                if ($value->manufacturer_source == "CUSTOMER") {
                    $sql = \DB::select("select customer_id,customer_name from m_customers_t where customer_id=" . $value->manufacturer_source_value_id);
                    if (count($sql) != 0) {
                        $manufacturerpartno[$key]->manufacturer_source_value_c = $sql[0]->customer_name;
                    } else {
                        $manufacturerpartno[$key]->manufacturer_source_value_c = "";
                    }
                } else {
                    $sql = \DB::select("select supplier_id,supplier_name from m_supplier_t where supplier_id=" . $value->manufacturer_source_value_id);
                    $manufacturerpartno[$key]->manufacturer_source_value_s = $sql[0]->supplier_name;
                }
            } else {
                $manufacturerpartno[$key]->manufacturer_source_value_s = "";
                $manufacturerpartno[$key]->manufacturer_source_value_c = "";

            }
        }
        $this->data['data'] = $manufacturerpartno;
        $this->data['pageMethod'] = "manufacturerpartno";
        return view('manufacturerpartno.view', $this->data);
    }
    /*End*/

    /*Delete Function*/
    public function delete(request $request, $id = null)
    {
        $column = array('part_no', 'part_no', 'part_no', 'part_no', 'part_no', 'manufacturer_partno_id', 'part_no');
        $table = array('s_inquiry_lines_t', 's_quote_lines_t', 's_salesorder_lines_t', 's_invoice_lines_t', 'p_enquiry_lines_t', 'p_quotation_lines_t', 'p_po_lines_t');

        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();

            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        if ($j == 0) {
            $query = \DB::table('m_manufacturer_partno_t')->where('product_id', $id)->delete();
            /**Auditlog**/
            $this->auditlog($id, "manufacturerpartno", "Delete", "", "m_manufacturer_partno_t");
        }
        return $j;

    }
    /*End*/

    /*Manufacturer Part Number Check Function*/
    public function manunocheck($id = null)
    {
        $sql = \DB::table('m_manufacturer_partno_t')->where('product_id', $id)->select('*')->get();
        $val = '';
        if (count($sql)) {
            $val = 1;
        } else {
            $val = 0;
        }
        return $val;
    }
    /*End*/

    /* find primary Key*/
    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }
    /*end*/
    /*Manufacture Part Number validation */
    function mfgsourcevalidate()
    {
        $mfgsrc = $_GET['manufacturersource'];
        $srcid = $_GET['srcid'];
        $productid = $_GET['productid'];

        $sql = \DB::select("select * from m_manufacturer_partno_t where manufacturer_source_value_id=" . $srcid . " and manufacturer_source='$mfgsrc' and product_id=" . $productid);

        if ($mfgsrc == 'CUSTOMER')
            $type = 'customer';
        else
            $type = 'supplier';

        if (count($sql) > 0) {
            return array(1, $type);
        } else
            return array(0, $type);
    }
    /*End*/
}
