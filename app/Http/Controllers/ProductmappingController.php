<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\productmapping;
use App\productmappinglines;
use Illuminate\Http\Request;
use DB;
class ProductmappingController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->model = new productmapping();
        $this->submodel = new productmappinglines();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageModule'] = "productmapping";
        $this->data['urlmenu'] = $this->indexs();
        $this->table = 'productmapping_hdr_tbl';
        $this->subtable = 'productmapping_lines_tbl';
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

        return view('productmapping.index', $this->data);
    }

    public function getproductmappingdata(Request $request)
    {

        $comp = \Session::get('companyid');
        if ($request->ajax()) {

            $data = \DB::table('productmapping_hdr_tbl')
                ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'productmapping_hdr_tbl.employee_id')
                ->leftJoin('tb_users', 'tb_users.id', '=', 'productmapping_hdr_tbl.created_by')
                ->select(
                    'productmapping_hdr_tbl.*',
                    \DB::raw("CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_number"),
                    \DB::raw("CONCAT(tb_users.employee_number,'-',tb_users.first_name) as first_name")
                )
                ->where('productmapping_hdr_tbl.company_id', $comp)
                ->groupBy('productmapping_hdr_tbl.productmapping_id')
                ->orderBy('productmapping_hdr_tbl.productmapping_id', 'DESC');

            return DataTables::of($data)
                ->rawColumns(['actions'])
                ->make(true);

        }

    }

    public function create($id = null)
    {

        if ($id != 0) {
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('productmapping_hdr_tbl')->where('productmapping_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['employee_id'] = $this->jcombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->employee_id);
            $this->data['linedata'] = \DB::table('productmapping_lines_tbl')->where('productmapping_id', $id)->get();
            if (count($this->data['linedata']) > 0) {
                foreach ($this->data['linedata'] as $key => $value) {



                    $prd_group_id = $value->prd_group_id;
                    $prd_category_id = $value->prd_category_id;
                    $this->data['linedata'][$key]->prd_group_id = $this->jCombologin('m_product_groups_t', 'product_group_id', 'group_name', $value->prd_group_id);
                    $this->data['linedata'][$key]->prd_category_id = $this->jcustomselecttool('m_product_category_t', 'product_category_id', 'category_name', $value->prd_category_id, ' and product_group_id =' . $prd_group_id);

                    $this->data['linedata'][$key]->prd_id = $value->prd_id;
                    $product = \DB::table('m_products_t')->where('product_id', $value->prd_id)->get();


                    if (count($product) <= 0) {
                        unset($this->data['linedata'][$key]);
                    } else {
                        $this->data['linedata'][$key]->product = $product[0]->product_code . "-" . $product[0]->concatenated_product;
                    }
                }
            } else {


                $this->data['prd_group_id'] = $this->jCombologin('m_product_groups_t', 'product_group_id', 'group_name', '');
                $this->data['prd_category_id'] = $this->jCombologin('m_product_category_t', 'product_category_id', 'category_name', '', '');
                $this->data['linedata'] = array();
            }
        } else {

            $this->data['row'] = (object) array();
            $this->data['row']->productmapping_id = '';
            $this->data['row']->description = '';
            $this->data['employee_id'] = $this->jcombo('hr_employee_t', 'employee_id', 'employee_number|first_name', '');

            $this->data['prd_group_id'] = $this->jCombologin('m_product_groups_t', 'product_group_id', 'group_name', '');
            $this->data['prd_category_id'] = $this->jCombologin('m_product_category_t', 'product_category_id', 'category_name', '', '');
            //   dd( $this->data['prd_category_id']);
            $this->data['linedata'] = array();
        }





        return view('productmapping.form', $this->data);
    }

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
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');

        \DB::beginTransaction();
        try {
            if ($_POST['productmapping_id'] == '') {
                $action = 'create';
                $order_status = "Saved Successfully";
            } else {
                $action = 'update';
                $order_status = "Updated Successfully";
            }




            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);

            $this->auditlog($id, "productmapping", $action, $data, "productmapping_hdr_tbl");

            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => $order_status));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    public function show($id = null)
    {

        $headerdata = \DB::table('productmapping_hdr_tbl as qh')
            ->leftjoin('hr_employee_t as p', 'qh.employee_id', '=', 'p.employee_id')
            ->select('qh.*', 'p.first_name')
            ->where('qh.productmapping_id', $id)
            ->get();
        $this->data['headerdata'] = $headerdata[0];
        $this->data['linesdata'] = \DB::table('productmapping_lines_tbl as qh')
            ->leftjoin('m_product_groups_t as p', 'qh.prd_group_id', '=', 'p.product_group_id')
            ->leftjoin('m_product_category_t as s', 'qh.prd_category_id', '=', 's.product_category_id')
            ->leftjoin('m_products_t as pl', 'qh.prd_id', '=', 'pl.product_id')
            ->select('qh.*', 's.category_name', 'pl.concatenated_product', 'pl.product_code', 'p.group_name')
            ->where('qh.productmapping_id', $id)
            ->get();


        return view('productmapping.view', $this->data);
    }

    public function destroy($id = null)
    {
        /**Auditlog**/
        $action = "Delete";
        $this->auditlog($id, "productmapping", $action, '', "productmapping_hdr_tbl");
        $query = DB::table('productmapping_hdr_tbl')->where('productmapping_id', $id)->delete();
        $query = DB::table('productmapping_lines_tbl')->where('productmapping_id', $id)->delete();

        return 0;
    }

    public function soproductdetails($id = null)
    {
        $employeedetails = \DB::table('hr_employee_t')->select('employee_id')->where('reporting_manager', $id)->get();
        if (count($employeedetails) > 0) {
            $empid = "";
            foreach ($employeedetails as $key => $value) {
                $empid .= $value->employee_id . ",";
            }
            $empid1 = trim($empid, ",");
            $productdetails = \DB::table('productmapping_hdr_tbl')->leftjoin('productmapping_lines_tbl', 'productmapping_lines_tbl.productmapping_id', '=', 'productmapping_hdr_tbl.productmapping_id')->select('productmapping_lines_tbl.prd_group_id', 'productmapping_lines_tbl.prd_category_id', 'productmapping_lines_tbl.prd_id')->WhereIn('employee_id', explode(",", $empid1))->get();
            return $productdetails;
        } else {

            return 0;
        }

    }

    /*vj purpose: product details*/
    public function getselectproductgridData()
    {
        $wh = "";

        $comp = \Session::get('companyid');

        $wh .= " and m_products_t.active='Yes' and m_products_t.company_id=" . $comp;

        $result = \DB::select("SELECT m_products_t.* from m_products_t where 1=1 $wh");
        return DataTables::of($result)->make(true);

    }

    /*vj purpose:get product name*/
    public function getproductname($id = null)
    {
        $bp = \DB::select("select * from m_products_t where product_id=" . $id);
        return $bp;
    }
    /*end*/
}
