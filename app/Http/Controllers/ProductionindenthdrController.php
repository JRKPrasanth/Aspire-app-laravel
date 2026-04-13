<?php

namespace App\Http\Controllers;

use App\Productionindenthdr;
use App\Productionindentlines;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductionindenthdrController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->table = "w_requisition_indent_hdr_t";
        $this->subtable = "w_requisition_indent_lines_t";
        $this->pageModule = "productionindent";
        $this->model = new Productionindenthdr;
        $this->submodel = new Productionindentlines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';

        $this->data = array(
            'pageModule' => 'productionindent',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu'] = $this->indexs();


    }

    /*Jq grid Function*/

    public function getProductionindentData()
    {

        $wh = '';

        $SQL = "SELECT
                        w_requisition_indent_hdr_t.w_requisition_indent_hdr_id as w_requisition_indent_hdr_id,
                        w_requisition_indent_hdr_t.indent_no as indent_no,
                        w_requisition_indent_hdr_t.indent_date as indent_date,
                        w_requisition_indent_hdr_t.indent_status as indent_status,
                        hr_employee_t.first_name,
                        w_requisition_indent_hdr_t.remarks as remarks
                        FROM `w_requisition_indent_hdr_t`
                        left join hr_employee_t on(
                        hr_employee_t.employee_id=w_requisition_indent_hdr_t.`requestor_id`) where 1=1 $wh";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }


    public function index()
    {
        $this->data['pageMethod'] = \Request::route()->getName();
        return view('productionindent.table', $this->data);
    }
    /*End*/

    /*Create Function*/
    public function create($id = null)
    {

        //kaviya purpose production plan indent 
        $this->data['row'] = (object) array();
        $this->data['row']->w_requisition_indent_hdr_id = "";
        $this->data['row']->w_requisition_indent_line_id = "";
        $this->data['row']->indent_date = date(\Session::get('p_date_format'));
        $this->data['row']->indent_no = "";
        $this->data['row']->indent_status = "";
        $this->data['row']->indent_source = "REQUEST FROM PRODUCTION";
        $planhdr = \DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id=' . $_GET['production']);
        $this->data['row']->reference_no = $planhdr[0]->plan_no;
        $this->data['row']->remarks = "";
        $this->data['id'] = '';

        $this->data['requestor_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name', \Session::get('emp_id'));
        $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');
        $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', '');
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));


        $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', '');
        $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
        $this->data['return_url'] = "productionindent";
        $sql1 = \DB::SELECT("SELECT sum(w_productionplan_lines_t.qty) as qty,w_productionplan_lines_t.product_id,w_productionplan_lines_t.uom_code_id FROM w_productionplan_lines_t LEFT JOIN m_products_t ON m_products_t.product_id=w_productionplan_lines_t.product_id  WHERE w_productionplan_lines_t.productionplan_hdr_id=" . $_GET['production'] . " AND (m_products_t.product_group_id=2 or  m_products_t.product_group_id=3) group by w_productionplan_lines_t.product_id");

        $k = 0;
        foreach ($sql1 as $key => $value) {
            $productid = $value->product_id;
            $comp = \Session::get('companyid');
            $qoh = \DB::Select("SELECT sum(reserv_trx_qty) as qty,product_id as fdfd from i_reservation_detail_t where product_id=" . $productid . " and company_id=" . $comp . " and reference_no='" . $planhdr[0]->plan_no . "' GROUP by product_id");
            if (count($qoh) > 0) {
                $qoh_qty = $qoh[0]->qty;
            } else {
                $qoh_qty = 0;
            }
            if ($qoh_qty < $value->qty) {
                $this->data['linedata'][$k] = (object) array();

                $this->data['linedata'][$k]->product_id = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', $productid);
                $this->data['linedata'][$k]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                if ($qoh_qty > 0) {
                    $qty = $value->qty - $qoh_qty;
                } else {
                    $qty = $value->qty;
                }
                $qty1 = number_format($qty, \Session::get('decimal'), ".", "");
                $product = \DB::select("select product_id,primary_uom,trx_uom,uom_value from m_uom_conversion_t where product_id=" . $productid);
                if ($product != null) {
                    $uom_value = $product[0]->uom_value;
                    $conversion_qty = $qty1 / $uom_value;
                } else {
                    $conversion_qty = $qty1;

                }
                $this->data['linedata'][$k]->qty = $conversion_qty;
                $this->data['linedata'][$k]->need_by_date = date("Y-m-d");
                $k++;
            }

        }
        if (isset($_GET['status']) == 'MRP') {

            \DB::select("delete  from w_productionplan_hdr_t where  productionplan_hdr_id='" . $_GET['production'] . "'");
            \DB::select("delete  from w_productionplan_lines_t where  productionplan_hdr_id='" . $_GET['production'] . "'");
        }

        return view('productionindent.form', $this->data);
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
        /*karthigaa Purpose for Auto Number*/
        if ($_POST['indent_no'] == "") {
            $seqno = $this->Seqnoe('PIND-', 'w_requisition_indent_hdr_t', '', 'poreq_count');
            $data['indent_no'] = $seqno[0];
            $data['poreq_count'] = $seqno[1];
        } else {
            $seqno[0] = $_POST['indent_no'];
        }
        /*End*/

        \DB::beginTransaction();

        try {
            //dd($data); 

            $id = $this->model->insertRow($data);

            $lid = $this->submodel->subgridSave($lines_data, $id);

            if ($_POST['w_requisition_indent_hdr_id'] == "") {
                $save_s = "Saved";
                $action = "Create";
            } else {
                $save_s = "Updated";
                $action = "Edit";
            }

            /**Auditlog**/
            $this->auditlog($id, "productionindent", $action, $_POST, "w_requisition_indent_hdr_t");
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Production Indent ' . $save_s . ' Successfully', 'id' => $id, 'lid' => $lid, 'auto_no' => $seqno[0]));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }
    /*End*/
    /**
     * Display the specified resource.
     *
     * @param  \App\Productionindenthdr  $productionindenthdr
     * @return \Illuminate\Http\Response
     */

    /*View Function*/
    public function show(Productionindenthdr $productionindenthdr, $id = null)
    {

        $vdata = \DB::table('w_requisition_indent_hdr_t')->leftjoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'w_requisition_indent_hdr_t.organization_id')
            ->leftjoin('m_projects_t', 'm_projects_t.project_id', '=', 'w_requisition_indent_hdr_t.project_id')
            ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'w_requisition_indent_hdr_t.requestor_id')
            ->leftjoin('tb_users', 'tb_users.id', '=', 'w_requisition_indent_hdr_t.created_by')
            ->where('w_requisition_indent_hdr_id', $id)->get();

        $this->data['indent_no'] = $vdata[0]->indent_no;
        $this->data['indent_date'] = $vdata[0]->indent_date;
        $this->data['indent_status'] = $vdata[0]->indent_status;
        $this->data['indent_source'] = $vdata[0]->indent_source;
        $this->data['created_by'] = $vdata[0]->username;
        $this->data['requestor_id'] = $vdata[0]->first_name;
        $this->data['project_name'] = $vdata[0]->project_name;
        $this->data['organization_name'] = $vdata[0]->organization_name;
        $this->data['remarks'] = $vdata[0]->remarks;
        $a = \DB::table('w_requisition_indent_lines_t')->where('w_requisition_indent_hdr_id', $id)->get();

        $vlinesdata = \DB::table('w_requisition_indent_lines_t')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'w_requisition_indent_lines_t.product_id')
            ->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'w_requisition_indent_lines_t.uom_code_id')->where('w_requisition_indent_lines_t.w_requisition_indent_hdr_id', $id)->get();

        $this->data['vlinesdata'] = $vlinesdata;
        $this->data['uom_code'] = $vlinesdata[0]->uom_code;
        return view('productionindent.view', $this->data);
    }

    /*End*/

}
