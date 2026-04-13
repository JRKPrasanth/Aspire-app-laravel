<?php

namespace App\Http\Controllers;

use App\Workorder;
use App\Productionplan;
use App\Productionplanlines;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use DB;
use Session;
use Config;

class ProductionplanController extends Controller
{

    public $module = "Productionplan";
    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->model = new Productionplan();
        $this->submodel = new Productionplanlines();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'Productionplan';
        $this->data['url'] = 'productionplan';
        $this->table = "w_productionplan_hdr_t";
        $this->subtable = "w_productionplan_lines_t";
        $this->middleware('auth');
        if ($this->data['pageMethod'] == 'productionplan') {
            $this->data['status'] = "APPROVED";
        } else {

            $this->data['status'] = '';
        }

    }
    /*end*/
    /* purpose:index function to redirect table blade*/
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


        $this->data['pageMethod'] = \Request::route()->getName();
        if ($this->data['pageMethod'] == 'planapproval') {
            $this->data['status'] = "INITIATED";
            $this->data['pageurl'] = "planapproval";
            $this->data['type'] = "sfg";
        } else if ($this->data['pageMethod'] == 'packingplanapproval') {
            $this->data['status'] = "INITIATED";
            $this->data['pageurl'] = "packingplanapproval";
            $this->data['type'] = "fg";
        } else if ($this->data['pageMethod'] == 'productionplan') {
            $this->data['status'] = "APPROVED";
            $this->data['type'] = "sfg";
            $this->data['pageurl'] = "productionplan";
        } else if ($this->data['pageMethod'] == "packingjobcard") {
            $this->data['status'] = "APPROVED";
            $this->data['type'] = "fg";
            $this->data['pageurl'] = "packingjobcard";
        }

        return view("productionplan.table", $this->data);
    }


    /*  purpose:to get Productionplan details*/
    public function getproductionplanData()
    {

        $wh = '';

        if ($_GET['status'] != '') {

            $wh .= " and  w_productionplan_hdr_t.plan_status='" . $_GET['status'] . "'";
            $op = "=";
            $status_val = "'" . $_GET['status'] . "'";
            $wh .= $grid_data = $this->grid_statuscheck('w_productionplan_hdr_t', 'plan_date', 'plan_status', $op, $status_val);
        } else {
            $wh .= $grid_data = $this->grid_check('w_productionplan_hdr_t', 'plan_date');
        }

        if (isset($_GET)) {
            if ($_GET['type'] == 'fg') {
                $wh .= " and m_product_groups_t.group_name='FINISHED GOODS' and w_productionplan_hdr_t.job_card_status!=1";
            } else {
                $wh .= " and m_product_groups_t.group_name='SEMI FINISHED GOODS' ";
            }
        }

        $loc = "1";
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $groupname = \Session::get('groupname');

	
        $sql = "SELECT
    w_productionplan_hdr_t.productionplan_hdr_id,
    w_productionplan_hdr_t.plan_no,
    w_productionplan_hdr_t.batch_no,
    w_productionplan_hdr_t.plan_date,
    w_productionplan_hdr_t.start_date,
    w_productionplan_hdr_t.end_date,
	 w_productionplan_hdr_t.plan_status,
    w_productionplan_hdr_t.production_qty,
    w_productionplan_hdr_t.plan_qty,
    w_productionplan_hdr_t.pending_qty,
    w_productionplan_hdr_t.remarks,
    m_products_t.concatenated_product,
    m_products_t.product_code,
    m_uom_codes_t.uom_code
FROM
    w_productionplan_hdr_t
LEFT JOIN m_products_t ON(
        m_products_t.product_id= w_productionplan_hdr_t.product_id
    )
	LEFT JOIN m_product_groups_t ON(
        m_products_t.product_group_id= m_product_groups_t.product_group_id
    )
LEFT JOIN m_uom_codes_t ON
    (
        w_productionplan_hdr_t.uom_code_id = m_uom_codes_t.uom_code_id
    ) where 1=1 $wh ORDER BY w_productionplan_hdr_t.productionplan_hdr_id DESC";


        $result = \DB::select($sql);

        return DataTables::of($result)->make(true);

    }



    /* purpose: to create productionplan based on workorder */
    public function create($id = null)
    {
		
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'workorder')->get();
        if (isset($_GET['source'])) {

            if ($_GET['source'] == "WORKORDER") {

                $salesorder = Workorder::find($id);
                $this->modelname = new Productionplan();
                $this->data['row'] = (object) array();
                $table = $this->modelname->getTableColumns();
                foreach ($table as $key => $val) {
                    $this->data['row']->$val = '';
                }
                $this->data['row']->plan_date = date(\Session::get('p_date_format'));
                $this->data['jobtype'] = "";
                $sql = \DB::select("select w_workorder_hdr_t.workorder_no,w_workorder_hdr_t.workorder_hdr_id,w_workorder_lines_t.workorder_line_id,w_workorder_lines_t.due_date from w_workorder_lines_t left join w_workorder_hdr_t on w_workorder_hdr_t.workorder_hdr_id=w_workorder_lines_t. workorder_hdr_id where workorder_line_id in ($id)");
                $wono = "";

                $wolineid = "";
                foreach ($sql as $key => $value) {
                    $wono .= $value->workorder_no . ",";
                    $wolineid .= $value->workorder_line_id . ",";
                    $woid = $value->workorder_hdr_id;
                    $wodate = $value->due_date;
                }
                $woorderno = rtrim($wono, ',');
                $woorderlnid = rtrim($wolineid, ',');
                $this->data['row']->reference_no = $woorderno;
                $this->data['row']->plan_status = '';
                $this->data['row']->workorder_due_date = date(\Session::get('p_date_format'), strtotime($wodate));
                $this->data['row']->reference_id = $woid;
                $this->data['row']->reference_line_id = $woorderlnid;
                $this->data['pagemode'] = "create";
                $this->data['close'] = $_GET['pagemodule'];
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
                $tablelines = \DB::select("select wl.workorder_hdr_id,wl.workorder_line_id,wl.product_id,sum(wl.qty) as qty, wl.uom_code_id from w_workorder_lines_t wl WHERE  wl.workorder_line_id in($id) group by wl.product_id");
                $this->data['linedata'] = array();

                $this->data['productdata'] = $tablelines;
                $decimal = \Session::get("decimal");
                $this->data['quantity'] = round($tablelines[0]->qty, $decimal);

                foreach ($this->data['productdata'] as $key => $value) {

                    $pdtid = $value->product_id;

                    if ($pdtid != 0) {

                        $this->data['assproductid'] = $pdtid;
                        $this->data['productid'] = $this->jcombo('m_products_t', 'product_id', 'concatenated_product', $pdtid);
                        $uom_code = \DB::select("select primary_uom_id from m_products_t where product_id='" . $pdtid . "'");
                        if (count($uom_code) > 0) {
                            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $uom_code[0]->primary_uom_id);
                        } else {
                            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $uom_code[0]->primary_uom_id);
                        }

                    }
                    $group = \DB::select("select product_group_id from m_products_t where product_id='" . $value->product_id . "'");
                    if (count($group) > 0)
                        $this->data['productdata'][$key]->group = $group[0]->product_group_id;
                    else
                        $this->data['productdata'][$key]->group = 0;
                }
                ;
                $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', '');
                $this->data['pagemodule'] = "PRODUCTION ANALYSE";
                $this->data['returnurl'] = "PRODUCTION ANALYSE";
                $source = "PRODUCTION ANALYSE";
                $this->data['lines'] = $this->bomdetails($this->data['productdata'][0]->product_id, $this->data['productdata'][0]->workorder_hdr_id, $this->data['productdata'][0]->qty, $source);

            }
            if ($_GET['source'] == "MRPPLAN") {
                $this->modelname = new Productionplan();
                $this->data['row'] = (object) array();
                $table = $this->modelname->getTableColumns();
                foreach ($table as $key => $val) {
                    $this->data['row']->$val = '';
                }
                $this->data['row']->plan_date = date(\Session::get('p_date_format'));

                $this->data['row']->reference_no = '';
                $this->data['row']->plan_status = '';
                $this->data['row']->reference_id = '';
                $this->data['row']->reference_line_id = '';
                $this->data['pagemode'] = "mrpplan";
                $this->data['close'] = "mrpplan";


                $this->data['linedata'] = array();
                $this->data['quantity'] = '';

                $this->data['productid'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', '', 'and (product_group_id="1" or product_group_id="4") order by concatenated_product asc');


                $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');

                $this->data['jobtype'] = "";
                $this->data['pagemodule'] = "MRP PLAN";
                $this->data['returnurl'] = "MRP PLAN";
                $this->data['organization_id'] = '';
            }



        } else if ($id == 0) {

            $this->modelname = new Productionplan();
            $this->data['row'] = (object) array();
            $table = $this->modelname->getTableColumns();
            foreach ($table as $key => $val) {
                $this->data['row']->$val = '';
            }
            $this->data['row']->plan_date = date(\Session::get('p_date_format'));


            $this->data['id'] = '';
            $this->data['linedata'] = array();
            $this->data['jobtype'] = "";
            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
            $this->data['row']->product_id = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', '', 'and product_group_id=1');
            $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', '');
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
        } else {
            $this->data['pagemodule'] = "CREATE JOBCARD";
            $this->data['returnurl'] = "PRODUCTION PLAN";
            $this->data['pagemode'] = "edit";
            if (isset($_GET['jobtype'])) {
                if ($_GET['jobtype'] == "production") {
                    $this->data['close'] = "productionplan";
                    $this->data['jobtype'] = "production";
                } else {
                    $this->data['close'] = "packingjobcard";
                    $this->data['jobtype'] = "packingjobcard";
                }
            }
            $this->data['id'] = $id;
            $table = \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['row']->start_date = $table[0]->start_date;
            $this->data['row']->end_date = $table[0]->end_date;

            $this->data['productid'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', $table[0]->product_id);
            $this->data['assproductid'] = $table[0]->product_id;

            $this->data['row']->reference_line_id = "";

            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
            $this->data['lines'] = $this->jobbomdetails($table[0]->productionplan_hdr_id, $table[0]->product_id, $this->data['jobtype']);
            $this->data['linedata'] = $table;

            if (count($this->data['linedata']) >= 1) {
                foreach ($table as $key => $value) {
                    $cmp = \Session::get('companyid');
                    $qoh = \DB::Select("select sum(f.qty-f.qtyy) as qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='" . $value->product_id . "' and company_id='" . $cmp . "' GROUP by product_id,batch_number, subinventory_id, locator_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='" . $value->product_id . "' and company_id='" . $cmp . "' GROUP by product_id, batch_number, subinventory_id, locator_id)f");
                    if (isset($qoh[0]->qty)) {
                        $qoh = $qoh[0]->qty;
                        if ($qoh < 0)
                            $qoh = "0";
                    } else {
                        $qoh = "0";
                    }
                    $this->data['linedata'][$key]->product = $value->product_id;

                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcombo('m_products_t', 'product_id', 'concatenated_product', $value->product_id);

                    $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);

                    $this->data['linedata'][$key]->qoh = $qoh;
                    $this->data['linedata'][$key]->jobcard_status = $value->job_card_status;

                }

            }

        }

        return view("productionplan.form", $this->data);
    }


    /* purpose:to display bom details from material bom based on fg*/




    /* purpose:to display bom details from material bom based on sfg*/
    public function bomsubdetails($pid = null, $production_qty = null, $plan_hdr_id = null, $source = null, $process = 0, $planno = null)
    {
        $sql1 = \DB::table('m_material_bom_hdr_t as bomh')
            ->leftjoin('m_material_bom_lines_t as boml', 'bomh.material_bom_hdr_id', '=', 'boml.material_bom_hdr_id')
            ->select('bomh.material_bom_hdr_id', 'bomh.assembly_product_id', 'boml.component_product_id', 'boml.component_uom_code_id', 'boml.component_qty')
            ->where('bomh.assembly_product_id', $pid)
            ->get();
        $prdassgroup = \DB::select("select m_products_t.product_group_id,m_product_groups_t.group_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where product_id='" . $pid . "'");
        $html = '';
        $decimal = \Session::get("decimal");
        if (count($sql1) > 0) {
            foreach ($sql1 as $key1 => $val1) {

                $comp = \Session::get('companyid');
                $group = \DB::select("select m_products_t.product_group_id,m_product_groups_t.group_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where product_id='" . $val1->component_product_id . "'");
                if ($group[0]->group_name == 'SEMI FINISHED GOODS' || $group[0]->group_name == 'FINISHED GOODS') {
                    $qoh1 = \DB::Select("select sum(f.qty-f.qtyy) as qoh_qty,f.qtyy as resqty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id,qualitystatus FROM i_qoh_detail_t where product_id='" . $val1->component_product_id . "' and company_id='" . $comp . "' and qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd,0 as qualitystatus from i_reservation_detail_t where product_id='" . $val1->component_product_id . "' and company_id='" . $comp . "' GROUP by product_id)f  ");
                } else {
                    $qoh1 = \DB::Select("select sum(f.qty-f.qtyy) as qoh_qty,f.qtyy as resqty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='" . $val1->component_product_id . "' and company_id='" . $comp . "' GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='" . $val1->component_product_id . "' and company_id='" . $comp . "' GROUP by product_id)f  ");
                }
                $resqoh1 = \DB::select("SELECT sum(reserv_trx_qty) as qty,product_id from i_reservation_detail_t where product_id='" . $val1->component_product_id . "' and company_id='" . $comp . "' and reference_no='$planno' GROUP by product_id ");
                if (!empty($resqoh1)) {
                    if ($resqoh1[0]->qty != "") {
                        $resqoh = number_format($resqoh1[0]->qty, $decimal, ".", "");
                    } else {
                        $resqoh = 0;
                    }
                } else {
                    $resqoh = 0;
                }
                if (isset($qoh1[0]->qoh_qty)) {
                    if ($qoh1[0]->qoh_qty > 0) {
                        $qoh = number_format($qoh1[0]->qoh_qty, $decimal, ".", "");
                    } else {
                        $qoh = "0";
                    }
                } else {
                    $qoh = "0";
                }

                $totqoh = $resqoh + $qoh;
                if ($totqoh <= 0) {
                    $capacity = "0";
                } else {
                    if ($val1->component_qty != '') {

                        $capacity = $totqoh / $val1->component_qty;
                    } else {
                        $capacity = "0";
                    }

                }

                $group1 = \DB::select("select product_group_id from m_products_t where product_id='" . $val1->component_product_id . "'");
                $group1 = $group1[0]->product_group_id;

                $prod_qty1 = number_format(($production_qty) * $val1->component_qty, $decimal, ".", "");
                $actual_qty1 = 0;
                $prd1 = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', $val1->component_product_id);
                $uom1 = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $val1->component_uom_code_id);
                $product = $this->productname($val1->component_product_id);
                $uom = $this->uomname($val1->component_uom_code_id);
                $html .= '
			
			<tr class="sub' . $pid . '  inter_sub' . $val1->component_product_id . '" data-id="' . $val1->component_product_id . '" data-parent="' . $pid . '">';

                $html .= '<td>
                    <input type="hidden" name="bulk_productionplan_line_id[]" class="form-control input-sm bulk_productionplan_line_id" value="">
					<input type="hidden" name="bulk_parent_product[]" class="form-control input-sm bulk_parent_product" value="' . $pid . '">';
                if ($group1 == 4 && $source != 'PLAN APPROVAL' && $source != 'PRODUCTION ANALYSE') {
                    $html .= '  <input type="radio" value="' . $val1->component_product_id . '" class="job" name="job[]" data-hdr="' . $plan_hdr_id . '" data-sub="line" data-qty="' . $prod_qty1 . '" data-process="0" ></td>';
                }
                $html .= '<td><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="' . ($key1 + 1) . '" readonly="readonly" style="width: 68px !important;">
					</td>';



                $html .= '<td class="pdtdiv">
					<input type="hidden" name="bulk_product_id[]" class="input-sm bulk_product_id" value="' . $val1->component_product_id . '" readonly="readonly" style="color:black;">
					<input type="text"  title="' . $product . '" class="input-sm bulk_product_id" value="' . $product . '" readonly="readonly" style="color:black;">
					</td>
					<td>
					<input type="hidden" name="bulk_uom_code_id[]" class="input-sm bulk_uom_code_id" value="' . $val1->component_uom_code_id . '" readonly="readonly" >
					<input type="text"  title="' . $uom . '" class="input-sm bulk_uom_code_id" value="' . $uom . '" readonly="readonly" style="color:black;width:80px;">
					</td>
					<td>
					<input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="' . $prod_qty1 . '" required="required" readonly="readonly" style="width:80px;">
					</td>';
                if ($source != 'PLAN APPROVAL' && $source != 'PRODUCTION ANALYSE' && $source != "MRP PLAN") {
                    if ($group1 == 4 || $group1 == 1) {
                        $html .= '<td>
					<input type="text" name="bulk_actual_qty[]" class="input-sm bulk_actual_qty input_qty_width" value="' . $actual_qty1 . '" style="color:black;" readonly>
					</td>
					<td>
					<input type="text" name="bulk_pending_qty[]" class="input-sm bulk_pending_qty input_qty_width" value="' . $prod_qty1 . '" style="color:black;" readonly>
					</td>';
                    } else {
                        $html .= '<td></td><td></td>';
                    }
                }
                if ($prdassgroup[0]->group_name == "FINISHED GOODS") {
                    $html .= '<td></td>';
                }
                $html .= '<td>
				        <input type="text" name="bulk_qoh[]" class="form-control input-sm bulk_qoh input_qty_width" value="' . round($qoh, \Session::get("decimal")) . '" readonly="readonly" style="width:80px;">
		<input type="hidden" name="counter[]">			
</td>';
                if ($source == 'PLAN APPROVAL' || $source == 'CREATE JOB') {
                    $html .= '<td>
				<input type="text"  class="input-sm bulk_reserveqoh input_qty_width" value="' . $resqoh . '" style="color:black;width:80px;" readonly>
					
					</td>';
                }
                if ($source != 'PLAN APPROVAL' && $source != 'PRODUCTION ANALYSE' && $source != "MRP PLAN") {
                    $process = 0;
                    $html .= '<td>
        <input type="text"  class="form-control input-sm bulk_capacity_qty' . $pid . $process . ' input_qty_width bulk_capacity_qty" value=' . round($capacity, \Session::get("decimal")) . ' minlength="1" maxlength="4"  readonly>
    </td>';
                }

                $html .= '</tr>';

                if ($group1 == "4") {

                    $html .= $this->bomsubdetails($val1->component_product_id, $prod_qty1, $plan_hdr_id, $source, $process, $planno);
                }

            }
        } else {
            $html .= '<tr class="sub' . $pid . '  inter_sub0" data-id="0"  data-parent="' . $pid . '"><td></td><td></td><td></td><td><input type="hidden" class="nobom"/>No Bom for these product</td></tr>';
        }
        return $html;

    }
    /*end*/

    /* purpose: to save production plan */
    public function save(Request $request)
    {
	
        // ---- Gather inputs (no $_POST) ----
        $productId = $request->input('product_id');
        $productionQty = (float) $request->input('production_qty', 0);
        $referenceLineIds = $request->filled('reference_line_id')
            ? array_filter(explode(',', (string) $request->input('reference_line_id')))
            : [];
        $planStatus = $request->input('plan_status');               // e.g. REJECTED / APPROVED / INITIATED
        $existingHdrId = (string) $request->input('productionplan_hdr_id', '');
        $incomingPlanNo = (string) $request->input('plan_no', '');
        $referenceId = $request->input('reference_id');

        // ---- Normalize form payload ----
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
            'reference_line_id'
        ]);
        $form = $this->normalizeLineFormKeys($form);

        // ---- Validate payload into header + lines ----
        $data = $this->validatePost($form, $this->table, 'header');
		
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');

        // ---- Dates (dd/mm/yyyy or similar) -> Y-m-d ----
        $data['start_date'] = \Carbon\Carbon::parse(str_replace('/', '-', $data['start_date']))->format('Y-m-d');
        $data['end_date'] = \Carbon\Carbon::parse(str_replace('/', '-', $data['end_date']))->format('Y-m-d');

		$data['product_id'] = $request->input('product_id');
		$data['uom_code_id'] = $request->input('uom_code_id');
        // ---- Plan number (generate if blank) ----
        if ($incomingPlanNo === '') {
            $seqno = $this->Seqno('WOPN', 'w_productionplan_hdr_t', '');
            $data['plan_no'] = $seqno;
        } else {
            $seqno = $incomingPlanNo;
            $data['plan_no'] = $incomingPlanNo;
        }

        // ---- Derived fields ----
        $data['pending_qty'] = $data['production_qty'] ?? $productionQty;

        \DB::beginTransaction();
        try {
            // ---- Insert header (your insertRow should return new PK) ----
            $id = $this->model->insertRow($data);
            if (!$id) {
                throw new \RuntimeException('Insert failed: header id missing.');
            }

            // ---- Build some frequently-used values ----
            $companyId = \Session::get('companyid');
            $organization = \Session::get('organization');
            $location = \Session::get('location');
            $userId = \Session::get('id');

            // ---- Create/Update audit log ----
            if ($_POST['productionplan_hdr_id'] == '') {
                $lid = $this->checkbom($productId, $productionQty, $id);
                $action = "create";
            } else {
                $action = "update";
            }
			
            /* purpose:audit log*/
            $this->auditlog($id, "productionplan", $action, $data, "w_productionplan_hdr_t");

            // ---- Update related Work Order line plan_status if we have reference lines ----
            if (!empty($referenceLineIds) && $productId) {
                if ($planStatus === "REJECTED") {
                    \DB::table('w_workorder_lines_t')
                        ->whereIn('workorder_line_id', $referenceLineIds)
                        ->where('product_id', $productId)
                        ->update(['plan_status' => '0']);

                    // Clear any reservations tied to this plan number
                    \DB::table('i_reservation_detail_t')
                        ->where('reference_no', $seqno)
                        ->delete();
                } elseif (in_array($planStatus, ["APPROVED", "INITIATED"], true)) {
                    \DB::table('w_workorder_lines_t')
                        ->whereIn('workorder_line_id', $referenceLineIds)
                        ->where('product_id', $productId)
                        ->update(['plan_status' => '1']);
                }
            }

            // ---- Mark notifications as read for this reference (column name is unusual but preserved) ----
            if ($referenceId) {
                \DB::table('notifications_t')
                    ->where('reference_source_id', $referenceId)
                    ->where('reference_source', '=', 'WORKORDER FROM SO')
                    ->update(['read/unread' => 'read']);
            }

            // ---- Fetch plan header + lines we just created ----
            $planLines = \DB::select(
                "SELECT * FROM w_productionplan_lines_t WHERE productionplan_hdr_id = ?",
                [$id]
            );
            $planHdr = \DB::select(
                "SELECT * FROM w_productionplan_hdr_t WHERE productionplan_hdr_id = ?",
                [$id]
            );
            if (empty($planHdr)) {
                throw new \RuntimeException('Unable to load production plan header after insert.');
            }

            // ---- Transaction types for PRODUCTION RESERVE ----
            $trxRow = \DB::table('m_transaction_types_t')
                ->where('transaction_type_name', 'PRODUCTION RESERVE')
                ->first();

            // ---- Product group of the plan product (guarded) ----
            $planProductGroup = \DB::select("
            SELECT p.product_group_id, g.group_name
            FROM m_products_t p
            LEFT JOIN m_product_groups_t g ON g.product_group_id = p.product_group_id
            WHERE p.product_id = ?
        ", [$planHdr[0]->product_id]);

            // ---- Reserve only when INITIATED ----
            if ($planStatus === "INITIATED") {
                // Build an SFG availability map for quick lookups
                $sfgCheck = [];
                $sfgCheck[$planHdr[0]->product_id] = 0;

                foreach ($planLines as $line) {
                    // group for this component
                    $group = \DB::select("
                    SELECT p.product_group_id, g.group_name
                    FROM m_products_t p
                    LEFT JOIN m_product_groups_t g ON g.product_group_id = p.product_group_id
                    WHERE p.product_id = ?
                ", [$line->product_id]);

                    $groupName = !empty($group) ? ($group[0]->group_name ?? null) : null;

                    // QOH for component (with or without qualitystatus filter)
                    if ($groupName === 'SEMI FINISHED GOODS') {
                        $qohRow = \DB::select("
                        SELECT SUM(f.qty - f.qtyy) AS qty, f.product_id FROM (
                            SELECT SUM(qoh_trx_qty) AS qty, 0 AS qtyy, product_id, qualitystatus
                              FROM i_qoh_detail_t
                             WHERE product_id = ? AND company_id = ? AND qualitystatus = 1
                          GROUP BY product_id
                            UNION ALL
                            SELECT 0 AS qty, SUM(reserv_trx_qty) AS qtyy, product_id, 0 AS qualitystatus
                              FROM i_reservation_detail_t
                             WHERE product_id = ? AND company_id = ?
                          GROUP BY product_id
                        ) f
                    ", [$line->product_id, $companyId, $line->product_id, $companyId]);
                    } else {
                        $qohRow = \DB::select("
                        SELECT SUM(f.qty - f.qtyy) AS qty, f.product_id FROM (
                            SELECT SUM(qoh_trx_qty) AS qty, 0 AS qtyy, product_id
                              FROM i_qoh_detail_t
                             WHERE product_id = ? AND company_id = ?
                          GROUP BY product_id
                            UNION ALL
                            SELECT 0 AS qty, SUM(reserv_trx_qty) AS qtyy, product_id
                              FROM i_reservation_detail_t
                             WHERE product_id = ? AND company_id = ?
                          GROUP BY product_id
                        ) f
                    ", [$line->product_id, $companyId, $line->product_id, $companyId]);
                    }

                    $qoh = (!empty($qohRow) && isset($qohRow[0]->qty)) ? (float) $qohRow[0]->qty : 0.0;
                    if ($qoh < 0)
                        $qoh = 0.0;

                    // Parent product QOH (guard) — only if parent != plan product
                    $parentQoh = 0.0;
                    if (!empty($line->parent_product) && $planHdr[0]->product_id != $line->parent_product) {
                        $parQohRow = \DB::select("
                        SELECT SUM(f.qty - f.qtyy) AS qty, f.product_id FROM (
                            SELECT SUM(qoh_trx_qty) AS qty, 0 AS qtyy, product_id, qualitystatus
                              FROM i_qoh_detail_t
                             WHERE product_id = ? AND company_id = ? AND qualitystatus = 1
                          GROUP BY product_id
                            UNION ALL
                            SELECT 0 AS qty, SUM(reserv_trx_qty) AS qtyy, product_id, 0 AS qualitystatus
                              FROM i_reservation_detail_t
                             WHERE product_id = ? AND company_id = ?
                          GROUP BY product_id
                        ) f
                    ", [$line->parent_product, $companyId, $line->parent_product, $companyId]);

                        if (!empty($parQohRow) && isset($parQohRow[0]->qty)) {
                            $parentQoh = (float) $parQohRow[0]->qty;
                        }
                    }
                    $sfgCheck[$line->parent_product ?? 0] = $parentQoh;
                    $sfgCheck[$line->product_id] = $qoh;

                    // If plan product group is NOT finished goods, apply reservation logic
                    $planGroupName = (!empty($planProductGroup) ? ($planProductGroup[0]->group_name ?? null) : null);

                    if ($planGroupName !== 'FINISHED GOODS') {
                        $needsParent = ($sfgCheck[$line->parent_product ?? 0] ?? 0) <= 0;

                        if ($needsParent && $qoh > 0) {
                            // Reserve min(requested, available)
                            $reserveQty = (float) $line->qty;
                            if ($reserveQty > $qoh) {
                                $reserveQty = $qoh;
                            } else {
                                // Mark line as fully satisfied
                                \DB::update(
                                    "UPDATE w_productionplan_lines_t SET status = '1' WHERE productionplan_line_id = ?",
                                    [$line->productionplan_line_id]
                                );
                            }

                            // Fetch product defaults (subinventory/locator)
                            $prodMeta = \DB::select("
                            SELECT p.product_group_id, p.product_id, g.group_name, p.subinventory_id, p.sublocator_id
                              FROM m_products_t p
                              LEFT JOIN m_product_groups_t g ON g.product_group_id = p.product_group_id
                             WHERE p.product_id = ?
                        ", [$line->product_id]);

                            if (empty($prodMeta)) {
                                // No metadata -> skip reservation for this line
                                continue;
                            }

                            $product = $prodMeta[0]->product_id;
                            $subinventory = $prodMeta[0]->subinventory_id ?? null;
                            $locator = $prodMeta[0]->sublocator_id ?? null;

                            // Material trx insert
                            $data1 = [
                                'trx_source_type_id' => $trxRow->transaction_source_id ?? 0,
                                'trx_action_id' => $trxRow->transaction_action_id ?? 0,
                                'trx_type_id' => $trxRow->transaction_type_id ?? 0,
                                'trx_source_hdr_id' => $id,
                                'trx_source_line_id' => $line->productionplan_line_id,
                                'line_number' => $line->line_no,
                                'product_id' => $product,
                                'trx_qty' => $reserveQty,
                                'trx_reference' => 'MATERIAL PLAN',
                                'trx_uom' => $line->uom_code_id,
                                'trx_date' => date('Y-m-d'),
                                'created_by' => $userId,
                                'company_id' => $companyId,
                                'organization_id' => $organization,
                                'location_id' => $location,
                                'created_at' => date('Y-m-d H:i:s'),
                                'last_updated_by' => $userId,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                            $mtlId = \DB::table('m_material_trx_t')->insertGetId($data1);

                            // Reservation detail insert
                            $reser = [
                                'create_trx_id' => $mtlId,
                                'reference_no' => $planHdr[0]->plan_no,
                                'reference_source' => 'MATERIAL PLAN',
                                'product_id' => $product,
                                'subinventory_id' => $subinventory,
                                'locator_id' => $locator,
                                'reserv_trx_qty' => $reserveQty,
                                'company_id' => $companyId,
                                'location_id' => $location,
                                'organization_id' => $organization,
                                'created_by' => $userId,
                                'created_at' => date('Y-m-d H:i:s'),
                                'last_updated_by' => $userId,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                            \DB::table('i_reservation_detail_t')->insert($reser);
                        }
                    } // end if planGroupName !== 'FINISHED GOODS'
                } // foreach planLines
            } // if INITIATED

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Saved Successfully',
                'id' => $id,
                'auto_no' => $seqno,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            return response()->json([
                'status' => 'error',
                'message' => 'DatabaseError:=>' . $dbCode . "\n",
            ]);
        } catch (\Throwable $e) {
            \DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'ServerError: ' . $e->getMessage(),
            ], 500);
        }
    }

    /* purpose:to save planlines based on bom */
	public function checkbom($productid = null, $production_qty = null, $id = null)
	{
		
    $comp    = \Session::get('companyid');
    $loc     = \Session::get('location');
    $org     = \Session::get('organization');
    $loginid = \Session::get('id');

    // Pull everything we need in one go (no N+1; no $group[0])
    $rows = \DB::table('m_material_bom_hdr_t as bomh')
        ->leftJoin('m_material_bom_lines_t as boml', 'bomh.material_bom_hdr_id', '=', 'boml.material_bom_hdr_id')
        ->leftJoin('m_products_t as prod', 'prod.product_id', '=', 'boml.component_product_id')
        ->leftJoin('m_product_groups_t as grp', 'grp.product_group_id', '=', 'prod.product_group_id')
        ->select(
            'bomh.material_bom_hdr_id',
            'bomh.assembly_product_id',
            'boml.component_product_id',
            'boml.component_uom_code_id',
            'boml.component_qty',
            'prod.product_group_id',
            'grp.group_name',
            'boml.process_level',
            'boml.process_name',
            'boml.machine_name'
        )
        ->where('bomh.assembly_product_id', $productid)
        ->get();

    if ($rows->isEmpty()) {
        return true; // nothing to insert/expand
    }

    foreach ($rows as $row) {
        // FG/SFG need qualitystatus=1, others don't
        $isFgOrSfg = in_array($row->group_name, ['SEMI FINISHED GOODS', 'FINISHED GOODS'], true);

        $qohQuery = \DB::table('i_qoh_detail_t')
            ->selectRaw('COALESCE(SUM(qoh_trx_qty),0) as qoh_qty')
            ->where('product_id', $row->component_product_id)
            ->where('company_id', $comp);

        if ($isFgOrSfg) {
            $qohQuery->where('qualitystatus', 1);
        }

        $qohRow = $qohQuery->first();
        $qoh    = max(0, (float)($qohRow->qoh_qty ?? 0));

        $componentQty = (float)$row->component_qty;
        $total_qty    = (float)$production_qty * $componentQty;

        \DB::table('w_productionplan_lines_t')->insert([
            'productionplan_hdr_id' => $id,
            'parent_product'        => $productid,
            'product_id'            => $row->component_product_id,
            'uom_code_id'           => $row->component_uom_code_id,
            'qty'                   => $total_qty,
            'qoh'                   => $qoh,
            'company_id'            => $comp,
            'location_id'           => $loc,
            'organization_id'       => $org,
            'created_by'            => $loginid,
            'last_updated_by'       => $loginid,
            'created_at'            => now(),
            'updated_at'            => now(),
            'pending_qty'           => $total_qty,
            'process_level'         => $row->process_level,
            'process_name'          => $row->process_name,
            'component_qty'         => $componentQty,
            'machine_name'          => $row->machine_name,
        ]);

        // Recurse only for SFG (your original intent)
        if ($row->group_name === 'SEMI FINISHED GOODS') {
            $this->checkbom($row->component_product_id, $total_qty, $id);
        }
    }

    return true;
}


    /*end*/
    /* purpose: to show productionplan */
    public function planview($id = null)
    {
        $this->data['id'] = $id;
        $table = \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id', $id)->get();
        $this->data['row'] = $table[0];
        $this->data['productid'] = $this->idname('product_code|concatenated_product', 'm_products_t', 'product_id', $table[0]->product_id);
        $this->data['uom'] = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $table[0]->uom_code_id);
        $this->data['organization_id'] = $this->idname('organization_name', 'm_organizations_t', 'organization_id', $table[0]->organization_id);

        if ($_GET['pageurl'] == "packingplanapproval") {

            $tablelines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $id)->where('parent_product', $table[0]->product_id)->get();
        } else {

            $tablelines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $id)->get();
        }
        $this->data['linedata'] = $tablelines;
        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->idname('product_code|concatenated_product', 'm_products_t', 'product_id', $value->product_id);
                $this->data['linedata'][$key]->uom_code_id = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $value->uom_code_id);
            }
        }

        $this->data['row']->uom_code_id = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $table[0]->uom_code_id);
        return view("productionplan.view", $this->data);
    }
    /*end*/
    /*deepika purpose: approval function*/
    public function approval($id = null)
    {
        $table = \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id', $id)->get();
        $wohdr = \DB::table('w_workorder_hdr_t')->where('workorder_hdr_id', $table[0]->reference_id)->get();
        $this->data['row'] = $table[0];
        $this->data['row']->start_date = date(\Session::get('p_date_format'), strtotime($table[0]->start_date));
        $this->data['row']->end_date = date(\Session::get('p_date_format'), strtotime($table[0]->end_date));
        $tablelines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $id)->get();
        $this->data['linedata'] = $tablelines;
        $this->data['productid'] = $this->jcombo('m_products_t', 'product_id', 'product_code|concatenated_product', $table[0]->product_id);
        $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $table[0]->uom_code_id);
        $this->data['quantity'] = $table[0]->production_qty;
        $this->data['assproductid'] = $table[0]->product_id;
        $this->data['row']->reference_line_id = "";
        $sfgcheck = array();
        $sfgcheck[$table[0]->product_id] = 0;
        $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
        foreach ($this->data['linedata'] as $key => $value) {
            $this->data['linedata'][$key]->productionplan_line_id = $value->productionplan_line_id;
            $this->data['linedata'][$key]->productionplan_hdr_id = $value->productionplan_hdr_id;

            $this->data['linedata'][$key]->production_qty = $value->qty;
            $this->data['linedata'][$key]->job_card_status = "1";
            $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
            $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id);
            $this->data['linedata'][$key]->product = $value->product_id;
        }
        $this->data['pagemode'] = "approval";
        $this->data['pagemodule'] = "PRODUCTION PLAN";
        if (isset($_GET['pageurl'])) {
            if ($_GET['pageurl'] == "packingplanapproval") {
                $this->data['close'] = "packingplanapproval";
                $jobtype = "packing";
            } else {
                $this->data['close'] = "planapproval";
                $jobtype = "production";
            }
        }

        $this->data['url'] = "planapproval";
        $this->data['returnurl'] = "PLAN APPROVAL";
        $this->data['status'] = "1";
        if ($_GET['pageurl'] == "packingplanapproval") {
            $sql1 = \DB::SELECT("SELECT w_productionplan_lines_t.qty,w_productionplan_lines_t.product_id,w_productionplan_lines_t.uom_code_id,w_productionplan_lines_t.parent_product FROM w_productionplan_lines_t  WHERE w_productionplan_lines_t.productionplan_hdr_id=" . $table[0]->productionplan_hdr_id . "  and parent_product=" . $table[0]->product_id . " order by w_productionplan_lines_t.product_id DESC");
        } else {
            $sql1 = \DB::SELECT("SELECT w_productionplan_lines_t.qty,w_productionplan_lines_t.product_id,w_productionplan_lines_t.uom_code_id,w_productionplan_lines_t.parent_product FROM w_productionplan_lines_t  WHERE w_productionplan_lines_t.productionplan_hdr_id=" . $table[0]->productionplan_hdr_id . "   order by w_productionplan_lines_t.product_id DESC");
        }
        $K = 0;
        $comp = \Session::get('companyid');
        /* purpose: to check qoh based on product group*/
        //	dd($sql1);
        $fgqoh = \DB::Select("select sum(f.qty-f.qtyy) as qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id,qualitystatus FROM i_qoh_detail_t where product_id='" . $table[0]->product_id . "' and company_id='" . $comp . "' and qualitystatus=1 and subinventory_id=5 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd,0 as qualitystatus from i_reservation_detail_t where product_id='" . $table[0]->product_id . "' and company_id='" . $comp . "' and subinventory_id=5 GROUP by product_id)f");
        $fgqohdata = 1;
        if (count($fgqoh) > 0) {
            if ($fgqoh[0]->qty > 0) {
                $fgqohdata = 0;
            }
        }
        if ($fgqohdata == "1") {
            foreach ($sql1 as $key => $value) {
                $qoh = array();
                $resvqoh = array();
                $otherresvqoh = $parentqoh = array();
                if ($value->product_id != 0) {


                    $group = \DB::select("select m_products_t.product_group_id,m_product_groups_t.group_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where product_id='" . $value->product_id . "'");
                    $parentqoh = \DB::Select("select sum(f.qty-f.qtyy) as qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id,qualitystatus FROM i_qoh_detail_t where product_id='" . $value->parent_product . "' and company_id='" . $comp . "' and qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd,0 as qualitystatus from i_reservation_detail_t where product_id='" . $value->parent_product . "' and company_id='" . $comp . "' GROUP by product_id)f");
                    if ($group[0]->group_name == 'SEMI FINISHED GOODS' || $group[0]->group_name == 'FINISHED GOODS') {
                        $qoh = \DB::Select("select sum(f.qty-f.qtyy) as qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id,qualitystatus FROM i_qoh_detail_t where product_id='" . $value->product_id . "' and company_id='" . $comp . "' and qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd,0 as qualitystatus from i_reservation_detail_t where product_id='" . $value->product_id . "' and company_id='" . $comp . "' GROUP by product_id)f");
                        $resvqoh = \DB::select("SELECT sum(reserv_trx_qty) as qty,product_id from i_reservation_detail_t where product_id='" . $value->product_id . "'and company_id='" . $comp . "' and reference_no='" . $table[0]->plan_no . "' GROUP by product_id ");
                        $otherresvqoh = \DB::select("SELECT sum(reserv_trx_qty) as qty,product_id from i_reservation_detail_t where product_id='" . $value->product_id . "'and company_id='" . $comp . "' and reference_no!='" . $table[0]->plan_no . "' and reference_source='MATERIAL PLAN' GROUP by product_id ");

                    } else {

                        $qoh = \DB::Select("select sum(f.qty-f.qtyy) as qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='" . $value->product_id . "' and company_id='" . $comp . "' GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='" . $value->product_id . "' and company_id='" . $comp . "' GROUP by product_id)f");
                        $resvqoh = \DB::select("SELECT sum(reserv_trx_qty) as qty,product_id from i_reservation_detail_t where product_id='" . $value->product_id . "'and company_id='" . $comp . "' and reference_no='" . $table[0]->plan_no . "' GROUP by product_id ");
                        $otherresvqoh = \DB::select("SELECT sum(reserv_trx_qty) as qty,product_id from i_reservation_detail_t where product_id='" . $value->product_id . "'and company_id='" . $comp . "' and reference_no='" . $table[0]->plan_no . "' and reference_source='MATERIAL PLAN' GROUP by product_id ");

                    }


                    if ($qoh[0]->qty > 0) {
                        $q_qty = $qoh[0]->qty;
                        $sfgcheck[$value->product_id] = $qoh[0]->qty;
                    } else if (count($resvqoh) > 0) {

                        $q_qty = $resvqoh[0]->qty;
                        $sfgcheck[$value->product_id] = $resvqoh[0]->qty;
                    } else if (count($otherresvqoh) > 0) {

                        $q_qty = $otherresvqoh[0]->qty;
                        $sfgcheck[$value->product_id] = $otherresvqoh[0]->qty;
                    } else {
                        $q_qty = 0;
                        $sfgcheck[$value->product_id] = 0;
                    }
                }
                if (count($parentqoh) > 0) {

                    $sfgcheck[$value->parent_product] = $parentqoh[0]->qty;
                } else {

                    $sfgcheck[$value->parent_product] = 0;
                }
                if ($group[0]->group_name == 'RAW MATERIALS' || $group[0]->group_name == 'PACKING MATERIALS') {

                    if ($sfgcheck[$value->parent_product] <= 0 || $sfgcheck[$value->parent_product] == null) {
                        $resvqoh = 0;
                        $opresvqoh = 0;
                        if (!empty($resvqoh)) {
                            if ($resvqoh[0]->qty != null) {
                                if ($resvqoh[0]->qty != "") {
                                    $resvqoh = $resvqoh[0]->qty;
                                }
                            }
                        }
                        if (!empty($otherresvqoh)) {
                            if ($otherresvqoh[0]->qty != null) {
                                if ($otherresvqoh[0]->qty != "") {
                                    $opresvqoh = $otherresvqoh[0]->qty;
                                }
                            }
                        }
                        if ($q_qty <= 0 && $resvqoh <= 0 && $opresvqoh) {
                            $K++;
                        }
                    }
                }
            }
        }
        $this->data['jobtype'] = '';
        $this->data['approval'] = $K;
        /*end*/
        $this->data['lines'] = $this->approveplandetails($table[0]->product_id, $id, $table[0]->production_qty, $this->data['returnurl'], $table[0]->plan_no, $jobtype);

        return view("productionplan.form", $this->data);
    }
	
    /*end*/
    /*deepika purpose:to display bom details from material bom based on fg*/
    public function approveplandetails($id = null, $pid = null, $production_qty, $source, $planno, $jobtype)
    {
        $decimal = \Session::get("decimal");
        $production_qty = number_format($production_qty, $decimal, ".", "");
        $prdassgroup = \DB::select("select m_products_t.product_group_id,m_product_groups_t.group_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where product_id='" . $id . "'");
        $planhdr = \DB::table('w_productionplan_hdr_t')->where('product_id', $id)->where('productionplan_hdr_id', $pid)->get();
        $comp = \Session::get('companyid');
        $sql = \DB::select("SELECT w_productionplan_lines_t.*,m_product_groups_t.group_name,m_products_t.product_group_id,concat(m_products_t.product_code,'-',m_products_t.concatenated_product) as productname,(select uom_code from m_uom_codes_t where m_uom_codes_t.uom_code_id=w_productionplan_lines_t.uom_code_id) as uom_code,coalesce(res.other_resqty,0) as other_resqty,coalesce(res.resqty,0) as resqty,(select sum(i_qoh_detail_t.qoh_trx_qty) from i_qoh_detail_t where i_qoh_detail_t.product_id=w_productionplan_lines_t.product_id) as qoh,(select sum(i_qoh_detail_t.qoh_trx_qty) from i_qoh_detail_t where i_qoh_detail_t.product_id=w_productionplan_lines_t.product_id and i_qoh_detail_t.subinventory_id=5) as qoh1 FROM `w_productionplan_lines_t` left join m_products_t on(m_products_t.product_id=w_productionplan_lines_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) left join (SELECT sum(i_reservation_detail_t.reserv_trx_qty) as resqty,v1.other_resqty,i_reservation_detail_t.product_id from i_reservation_detail_t left join (SELECT sum(reserv_trx_qty) as other_resqty,product_id from i_reservation_detail_t where i_reservation_detail_t.company_id=$comp and i_reservation_detail_t.reference_no!='$planno' GROUP by i_reservation_detail_t.product_id)v1 on v1.product_id=i_reservation_detail_t.product_id where i_reservation_detail_t.company_id=$comp and i_reservation_detail_t.reference_no='$planno' GROUP by i_reservation_detail_t.product_id) as res on res.product_id=w_productionplan_lines_t.product_id where w_productionplan_lines_t.productionplan_hdr_id=$pid group by w_productionplan_lines_t.productionplan_line_id");
        $raw_data = collect($sql);
        $filtered = $raw_data->where('parent_product', $id);
        $filtered->all();
    $html = '<div class="table-responsive">
<table class="collaptable table table-sm table-hover align-middle mb-0">
  <thead class="table-dark position-sticky top-0" style="z-index:1"></thead>
  <tr  style="background-color:#455986;">
  <th>&nbsp;</th>
    <th>Line No</th>
    <th style="width:30%">Product</th>
    <th>Uom</th>
    <th>Production Qty</th>';
        if ($prdassgroup[0]->group_name == "FINISHED GOODS") {
            $html .= '<th>Process</th>';
        }
        $html .= '<th>Qoh</th>';
        if ($source == 'PLAN APPROVAL') {
            $html .= '  <th>Reserve Qoh</th>';
            $html .= '<th>Other Plan Reserve Qoh</th>';
        }
        $html .= ' </tr>';
        if (count($filtered) > 0) {

            foreach ($filtered as $key => $val) {

                $comp = \Session::get('companyid');
                $process = $this->processname($val->process_level);
                if ($val->product_id != 0) {
                    $group = \DB::select("select m_products_t.product_group_id,m_product_groups_t.group_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where product_id='" . $val->product_id . "'");
                    if ($group[0]->group_name == 'SEMI FINISHED GOODS') {
                        $qoh1 = \DB::Select("select sum(f.qty-f.qtyy) as qoh_qty,f.qtyy as resqty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id,qualitystatus FROM i_qoh_detail_t where product_id='" . $val->product_id . "' and company_id='" . $comp . "' and qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd,0 as qualitystatus from i_reservation_detail_t where product_id='" . $val->product_id . "' and company_id='" . $comp . "' GROUP by product_id)f  ");
                    } else if ($group[0]->group_name == 'FINISHED GOODS') {
                        $qoh1 = \DB::Select("select sum(f.qty-f.qtyy) as qoh_qty,f.qtyy as resqty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id,qualitystatus FROM i_qoh_detail_t where product_id='" . $val->product_id . "' and company_id='" . $comp . "' and qualitystatus=1 and subinventory_id=5 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd,0 as qualitystatus from i_reservation_detail_t where product_id='" . $val->product_id . "' and company_id='" . $comp . "' and subinventory_id=5 GROUP by product_id)f  ");
                    } else {
                        $qoh1 = \DB::Select("select sum(f.qty-f.qtyy) as qoh_qty,f.qtyy as resqty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='" . $val->product_id . "' and company_id='" . $comp . "' GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='" . $val->product_id . "' and company_id='" . $comp . "' GROUP by product_id)f  ");
                    }
                    if (isset($qoh1[0]->qoh_qty)) {
                        if ($qoh1[0]->qoh_qty > 0)
                            $qoh = number_format($qoh1[0]->qoh_qty, $decimal, ".", "");
                        else
                            $qoh = "0";
                    } else {
                        $qoh = "0";
                    }
                    $otherresvqoh = \DB::select("SELECT sum(reserv_trx_qty) as qty,product_id from i_reservation_detail_t where product_id='" . $val->product_id . "' and reference_no!='$planno' and reference_source='MATERIAL PLAN' and company_id='$comp' GROUP by product_id ");
                    $opresqoh = 0;
                    if (isset($otherresvqoh[0]->qty)) {
                        if ($otherresvqoh[0]->qty > 0) {
                            $opresqoh = number_format($otherresvqoh[0]->qty, $decimal, ".", "");
                        }
                    }
                    $resvqoh = \DB::select("SELECT sum(reserv_trx_qty) as qty,product_id from i_reservation_detail_t where product_id='" . $val->product_id . "' and reference_no='$planno' and company_id='$comp' GROUP by product_id ");
                    if (isset($resvqoh[0]->qty)) {
                        $resqoh = number_format($resvqoh[0]->qty, $decimal, ".", "");
                    } else {
                        $resqoh = 0;
                    }
                    $prd = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', $val->product_id);
                    $uom = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $val->uom_code_id);
                    $productname = $this->productname($val->product_id);
                    $uomname = $this->uomname($val->uom_code_id);
                    $group = \DB::select("select product_group_id from m_products_t where product_id='" . $val->product_id . "'");
                    $group = $group[0]->product_group_id;
                    $prod_qty = number_format($val->component_qty * $production_qty, $decimal, ".", "");
                }
                $html .= '<tr class="table' . $key . '" data-id="' . $val->productionplan_line_id . '" data-parent="">
					<td>';
                $html .= '<input type="hidden" name="bulk_productionplan_line_id[]" class="form-control input-sm bulk_productionplan_line_id" value="">
					<input type="hidden" name="bulk_parent_product[]" class="form-control input-sm bulk_parent_product" value="' . $id . '">
</td>
					<td ><input type="text" name="bulk_line_no[]" class="input-sm bulk_line_no" value="' . ($key + 1) . '" readonly="readonly" >
					</td>';
                $html .= '<td class="pdtdiv"><input type="hidden" id="bulk_product_id" class="bulk_product_id" value="' . $val->product_id . '">
					<input type="hidden" name="bulk_product_id[]" class="input-sm bulk_product_id" value="' . $val->product_id . '" readonly="readonly" style="color:black;">
					<input type="text"  class="input-sm bulk_product_id" value="' . $productname . '" title="' . $productname . '" readonly="readonly" style="color:black;">
					</td>
					<td>
					<input type="hidden" id="bulk_uom_code_id" class="bulk_uom_code_id" value="' . $val->uom_code_id . '">
					<input type="text" name="bulk_uom_code_id[]" class="input-sm uom" value="' . $uomname . '" readonly="readonly" ">
					</td>
					<td>
					<input type="text" name="bulk_qty[]" class="input-sm bulk_qty input_qty_width" value="' . $prod_qty . '"  readonly>
					<input type="hidden" name="bulk_component_qty[]" class="input-sm bulk_component_qty input_qty_width" value="' . $val->component_qty . '" >
					<input type="hidden" name="bulk_process_level[]" class="input-sm bulk_process_level input_qty_width" value="' . $val->process_level . '">
					<input type="hidden" name="bulk_process_name[]" class="input-sm bulk_process_name input_qty_width" value="' . $val->process_name . '">
					</td>';
                if ($prdassgroup[0]->group_name == "FINISHED GOODS") {
                    $html .= '<td>
					<input type="text" name="bulk_process_level[]" class="input-sm bulk_process_level" value="' . $process . '" readonly="readonly"  title="' . $process . '">
					</td>';
                }
                $html .= '<td>
				<input type="text"  name="bulk_qoh[]" class="input-sm bulk_qoh input_qty_width" value="' . $qoh . '"  readonly>
					<input type="hidden" name="counter[]">
					</td>';
                if ($source == 'PLAN APPROVAL') {
                    $html .= '	<td>
				<input type="text"  class="input-sm bulk_reserveqoh input_qty_width" value="' . $resqoh . '" style="color:black;width:80px;" readonly>
						</td><td>
				<input type="text"  class="input-sm bulk_reserveqoh input_qty_width" value="' . $opresqoh . '" style="color:black;width:80px;" readonly>
					</td>';
                }
                $html .= '</tr>';
                if ($prdassgroup[0]->group_name == "SEMI FINISHED GOODS") {
                    if ($group == "4") {
                        $prc = "";
                        $html1 = $this->jobbomsubdetails($val->product_id, $prod_qty, $planhdr[0]->productionplan_hdr_id, $source, $prc, $planno, $jobtype, $raw_data, $val->productionplan_line_id);
                        $html .= $html1;
                    }
                }
            }
        } else {
            $html .= "<tr><td></td><td></td><td></td><td></td><td><input type='hidden' class='nobom'/>No Bom for these product</td></tr>";

        }
        $html .= '</table>';
        return $html;
    }
    /*end*/

  public function bomdetails($id = null, $wohdrid = null, $production_qty = null, $source = null)
{
    $decimal = \Session::get('decimal');
    $production_qty = number_format($production_qty, $decimal, '.', '');

    $planno = '';
    $plan_hdr_id = 0;

    // Product group for the assembly (controls whether we show Process column)
    $prdassgroup = \DB::select("
        select m_products_t.product_group_id, m_product_groups_t.group_name
        from m_products_t
        left join m_product_groups_t on m_product_groups_t.product_group_id = m_products_t.product_group_id
        where product_id = ?
    ", [$id]);
    $assemblyGroupName = $prdassgroup[0]->group_name ?? null;

    // BOM header + lines
    $sql = \DB::table('m_material_bom_hdr_t as bomh')
        ->leftJoin('m_material_bom_lines_t as boml', 'bomh.material_bom_hdr_id', '=', 'boml.material_bom_hdr_id')
        ->select('bomh.*', 'boml.*')
        ->where('bomh.assembly_product_id', $id)
        ->get();

    // ---------- Bootstrap 5 table (responsive + sticky header) ----------
    $html = '<div class="table-responsive">
<table class="collaptable table table-sm table-hover align-middle mb-0">
  <thead class="table-dark position-sticky top-0" style="z-index:1">
    <tr>
      <th style="width:36px">&nbsp;</th>
      <th class="text-nowrap">Line No</th>
      <th class="text-nowrap" style="width:30%">Product</th>
      <th class="text-nowrap">UOM</th>
      <th class="text-end text-nowrap">Production Qty</th>';

    if ($assemblyGroupName === 'FINISHED GOODS') {
        $html .= '<th class="text-nowrap">Process</th>';
    }

    $html .= '<th class="text-end text-nowrap">QOH</th>';

    if ($source === 'PLAN APPROVAL') {
        $html .= '<th class="text-end text-nowrap">Reserve QOH</th>';
    }

    $html .= '</tr>
  </thead>
  <tbody>';

    if (count($sql) > 0) {
        foreach ($sql as $key => $val) {
            $comp = \Session::get('companyid');

            // Component group (to decide QOH query flavor)
            $componentGroup = \DB::select("
                select m_products_t.product_group_id, m_product_groups_t.group_name
                from m_products_t
                left join m_product_groups_t on m_product_groups_t.product_group_id = m_products_t.product_group_id
                where product_id = ?
            ", [$val->component_product_id]);

            $componentGroupName = $componentGroup[0]->group_name ?? null;

            // -------- QOH (and reservations) calculation ----------
            $qoh1 = [];
            if ($val->component_product_id != 0) {
                if ($componentGroupName === 'SEMI FINISHED GOODS') {
                    $qoh1 = \DB::select("
                        select sum(f.qty - f.qtyy) as qoh_qty, f.qtyy as resqty, f.product_id
                        from (
                            select sum(qoh_trx_qty) as qty, 0 as qtyy, product_id, qualitystatus
                            from i_qoh_detail_t
                            where product_id = ? and company_id = ? and qualitystatus = 1
                            group by product_id, batch_number, subinventory_id, locator_id
                            union all
                            select 0 as qty, sum(reserv_trx_qty) as qtyy, product_id as fdfd, 0 as qualitystatus
                            from i_reservation_detail_t
                            where product_id = ? and company_id = ?
                            group by product_id, batch_number, subinventory_id, locator_id
                        ) f
                    ", [$val->component_product_id, $comp, $val->component_product_id, $comp]);
                } elseif ($componentGroupName === 'FINISHED GOODS') {
                    $qoh1 = \DB::select("
                        select sum(f.qty - f.qtyy) as qoh_qty, f.qtyy as resqty, f.product_id
                        from (
                            select sum(qoh_trx_qty) as qty, 0 as qtyy, product_id, qualitystatus
                            from i_qoh_detail_t
                            where product_id = ? and company_id = ? and qualitystatus = 1 and subinventory_id = 5
                            group by product_id, batch_number, subinventory_id, locator_id
                            union all
                            select 0 as qty, sum(reserv_trx_qty) as qtyy, product_id as fdfd, 0 as qualitystatus
                            from i_reservation_detail_t
                            where product_id = ? and company_id = ? and subinventory_id = 5
                            group by product_id, batch_number, subinventory_id, locator_id
                        ) f
                    ", [$val->component_product_id, $comp, $val->component_product_id, $comp]);
                } else {
                    $qoh1 = \DB::select("
                        select sum(f.qty - f.qtyy) as qoh_qty, f.qtyy as resqty, f.product_id
                        from (
                            select sum(qoh_trx_qty) as qty, 0 as qtyy, product_id
                            from i_qoh_detail_t
                            where product_id = ? and company_id = ?
                            group by product_id, batch_number, subinventory_id, locator_id
                            union all
                            select 0 as qty, sum(reserv_trx_qty) as qtyy, product_id as fdfd
                            from i_reservation_detail_t
                            where product_id = ? and company_id = ?
                            group by product_id, batch_number, subinventory_id, locator_id
                        ) f
                    ", [$val->component_product_id, $comp, $val->component_product_id, $comp]);
                }
            }

            if (isset($qoh1[0]->qoh_qty) && $qoh1[0]->qoh_qty > 0) {
                $qoh = number_format($qoh1[0]->qoh_qty, $decimal, '.', '');
            } else {
                $qoh = '0';
            }

            // Reserve QOH for current plan (only shown in PLAN APPROVAL)
            $resqoh = 0;
            if ($val->component_product_id != 0) {
                $resvqoh = \DB::select("
                    select sum(reserv_trx_qty) as qty, product_id
                    from i_reservation_detail_t
                    where product_id = ? and reference_no = ? and company_id = ?
                    group by product_id, batch_number, subinventory_id, locator_id
                ", [$val->component_product_id, $planno, $comp]);

                $resqoh = isset($resvqoh[0]->qty)
                    ? number_format($resvqoh[0]->qty, $decimal, '.', '')
                    : 0;

                // Lookups / display names
                $productname = $this->productname($val->component_product_id);
                $uomname     = $this->uomname($val->component_uom_code_id);
                $process     = $this->processname($val->process_level);

                // Component product group id (for recursion rule below)
                $componentGroupIdRow = \DB::select("
                    select product_group_id from m_products_t where product_id = ?
                ", [$val->component_product_id]);
                $componentGroupId = $componentGroupIdRow[0]->product_group_id ?? null;

                // Line production qty
                $prod_qty = number_format($val->component_qty * $production_qty, $decimal, '.', '');
            }

            // ---------- Row ----------
            $html .= '<tr class="table' . $key . '" data-id="' . $val->component_product_id . '" data-parent="">
                <td></td>
                <td>
                    <input type="text" name="bulk_line_no[]" class="form-control form-control-sm bg-transparent border-0 p-0"
                        value="' . ($key + 1) . '" readonly>
                    <input type="hidden" name="bulk_productionplan_line_id[]" class="form-control form-control-sm bulk_productionplan_line_id" value="">
                    <input type="hidden" name="bulk_parent_product[]" class="form-control form-control-sm bulk_parent_product" value="' . $id . '">
                </td>

                <td class="pdtdiv">
                    <input type="hidden" id="bulk_product_id" class="bulk_product_id" value="' . $val->component_product_id . '">
                    <input type="hidden" name="bulk_product_id[]" class="form-control form-control-sm bg-transparent border-0 p-0 bulk_product_id"
                        value="' . $val->component_product_id . '" readonly>
                    <input type="text" class="form-control form-control-sm bg-transparent border-0 p-0"
                        value="' . $productname . '" title="' . $productname . '" readonly>
                </td>

                <td>
                    <input type="hidden" id="bulk_uom_code_id" class="bulk_uom_code_id" value="' . $val->uom_code_id . '">
                    <input type="text" name="bulk_uom_code_id[]" class="form-control form-control-sm bg-transparent border-0 p-0 uom"
                        value="' . $uomname . '" readonly>
                </td>

                <td>
                    <input type="text" name="bulk_qty[]" class="form-control form-control-sm text-end bg-transparent border-0 p-0 input_qty_width"
                        value="' . $prod_qty . '" readonly>
                    <input type="hidden" name="bulk_component_qty[]" class="form-control form-control-sm bg-transparent border-0 p-0 bulk_component_qty input_qty_width"
                        value="' . $val->component_qty . '">
                    <input type="hidden" name="bulk_process_level[]" class="form-control form-control-sm bg-transparent border-0 p-0 bulk_process_level input_qty_width"
                        value="' . $val->process_level . '">
                    <input type="hidden" name="bulk_process_name[]" class="form-control form-control-sm bg-transparent border-0 p-0 bulk_process_name input_qty_width"
                        value="' . $val->process_name . '">
                </td>';

            if ($assemblyGroupName === 'FINISHED GOODS') {
                $html .= '<td>
                    <input type="text" name="bulk_process_level[]" class="form-control form-control-sm bg-transparent border-0 p-0"
                        value="' . $process . '" title="' . $process . '" readonly>
                </td>';
            }

            $html .= '<td>
                    <input type="text" name="bulk_qoh[]" class="form-control form-control-sm text-end bg-transparent border-0 p-0 bulk_qoh input_qty_width"
                        value="' . $qoh . '" readonly>
                    <input type="hidden" name="counter[]">
                </td>';

            if ($source === 'PLAN APPROVAL') {
                $html .= '<td>
                    <input type="text" class="form-control form-control-sm text-end bg-transparent border-0 p-0 bulk_reserveqoh input_qty_width"
                        value="' . $resqoh . '" readonly>
                </td>';
            }

            $html .= '</tr>';

            // --------- Optional recursion for SFG components ----------
            if ($assemblyGroupName === 'SEMI FINISHED GOODS') {
                if ((string)$componentGroupId === '4') {
                    $prc = '';
                    $html1 = $this->bomsubdetails($val->component_product_id, $prod_qty, $plan_hdr_id, $source, $prc, $planno);
                    $html .= $html1;
                }
            }
        }
    } else {
        $html .= "<tr><td></td><td></td><td></td><td></td><td><input type='hidden' class='nobom'/>No Bom for these product</td></tr>";
    }

    $html .= '</tbody></table></div>';

    // (Your original minification)
    if (strpos($html, '<pre>') !== false) {
        $replace = [
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            "/<\?php/"                  => '<?php ',
            "/\r/"                      => '',
            "/>\n</"                    => '><',
            "/>\s+\n</"                 => '><',
            "/>\n\s+</"                 => '><',
        ];
    } else {
        $replace = [
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            "/\n([\S])/"                => '$1',
            "/\r/"                      => '',
            "/\n/"                      => '',
            "/\t/"                      => '',
            "/ +/"                      => ' ',
        ];
    }
    $buffer = preg_replace(array_keys($replace), array_values($replace), $html);

    ini_set('zlib.output_compression', 'On');
    return $buffer;
}


    /* purpose:to display bom details from material bom based on fg,sfg to create job card*/

    public function jobbomdetails($id = null, $product_id = null, $jbtype = null)
    {
        $plan = \DB::select("select * from w_productionplan_hdr_t where productionplan_hdr_id=" . $id);
        $plan_hdr_id = $plan[0]->productionplan_hdr_id;
        $production_qty = $plan[0]->production_qty;
        $plan_no = $plan[0]->plan_no;
        $source = "CREATE JOB";
        $comp = \Session::get('companyid');

        $sql = \DB::select("
        SELECT 
            w_productionplan_lines_t.*,
            m_product_groups_t.group_name,
            m_products_t.product_group_id,
            concat(m_products_t.product_code,'-',m_products_t.concatenated_product) as productname,
            (select uom_code from m_uom_codes_t where m_uom_codes_t.uom_code_id=w_productionplan_lines_t.uom_code_id) as uom_code,
            coalesce(res.other_resqty,0) as other_resqty,
            coalesce(res.resqty,0) as resqty,
            (select sum(i_qoh_detail_t.qoh_trx_qty) from i_qoh_detail_t where i_qoh_detail_t.product_id=w_productionplan_lines_t.product_id) as qoh,
            (select sum(i_qoh_detail_t.qoh_trx_qty) from i_qoh_detail_t where i_qoh_detail_t.product_id=w_productionplan_lines_t.product_id and i_qoh_detail_t.subinventory_id=5) as qoh1
        FROM w_productionplan_lines_t
        left join m_products_t on (m_products_t.product_id=w_productionplan_lines_t.product_id)
        left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id)
        left join (
            SELECT 
                sum(i_reservation_detail_t.reserv_trx_qty) as resqty,
                v1.other_resqty,
                i_reservation_detail_t.product_id
            from i_reservation_detail_t
            left join (
                SELECT sum(reserv_trx_qty) as other_resqty, product_id 
                from i_reservation_detail_t 
                where i_reservation_detail_t.company_id=$comp and i_reservation_detail_t.reference_no!='$plan_no'
                GROUP by i_reservation_detail_t.product_id
            ) v1 on v1.product_id=i_reservation_detail_t.product_id
            where i_reservation_detail_t.company_id=$comp and i_reservation_detail_t.reference_no='$plan_no'
            GROUP by i_reservation_detail_t.product_id
        ) as res on res.product_id=w_productionplan_lines_t.product_id
        where w_productionplan_lines_t.productionplan_hdr_id=$plan_hdr_id
        group by w_productionplan_lines_t.productionplan_line_id
        order by w_productionplan_lines_t.process_level asc
    ");

        $raw_data = collect($sql);
        $filtered = $raw_data->where('parent_product', $product_id);
        $filtered->all();

        // --- Bootstrap 5 Table Header (responsive, sticky header) ---
        $html = '<div class="table-responsive">
<table class="table table-sm table-hover align-middle mb-0 collaptable">
  <thead class="table-dark position-sticky top-0" style="z-index:1">
    <tr>
      <th style="width:36px"></th>
      <th class="text-nowrap">Line No</th>
      <th class="text-nowrap" style="width:30%">Product</th>
      <th class="text-nowrap">UOM</th>
      <th class="text-end text-nowrap">Production Qty</th>
      <th class="text-end text-nowrap">Actual Qty</th>
      <th class="text-end text-nowrap">Pending Qty</th>';

        if ($jbtype == "packingjobcard") {
            $html .= '
      <th class="text-nowrap">Process</th>
      <th class="text-nowrap">Process Name</th>';
        }

        $html .= '
      <th class="text-end text-nowrap">QOH</th>
      <th class="text-end text-nowrap">Reserve QOH</th>
      <th class="text-end text-nowrap">Other Plan Reserve</th>
      <th class="text-end text-nowrap">Qty Capacity</th>
    </tr>
  </thead>
  <tbody>';

        $processs = array();
        $i = 0;
        $qoh = 0;
        $qoh1 = 0;

        foreach ($filtered as $key => $val) {

            $decimal = \Session::get('decimal');

            if ($val->group_name == 'FINISHED GOODS') {
                if ($val->qoh1 > 0) {
                    $qoh1 = $val->qoh1 - $val->resqty;
                }
            } else {
                if ($val->qoh > 0) {
                    $qoh1 = $val->qoh - $val->resqty;
                }
            }

            if ($val->resqty == 0 && $qoh1 == 0) {
                $totqoh = number_format($val->other_resqty, $decimal);
            } else {
                $totqoh = number_format($val->resqty + $qoh1, $decimal, ".", "");
            }

            if ($totqoh <= 0) {
                $capacity = "0";
            } else {
                if ($val->component_qty != '') {
                    $capacity = number_format(
					((float)$val->component_qty > 0) ? ((float)$totqoh / (float)$val->component_qty) : 0,
					$decimal,
					".",
					""
				);
                } else {
                    $capacity = "0";
                }
            }

            $prod_qty = number_format($val->component_qty * $production_qty, $decimal, ".", "");

            $actualqty = $val->production_qty;
            if ($actualqty == 0 || $actualqty == "") {
                $pendqty = $prod_qty;
            } else {
                $pendqty = $val->pending_qty;
            }

            $process = $this->processname($val->process_level);
            $productname = $val->productname;
            $productname1 = $this->productname($val->parent_product);
            $uomname = $val->uom_code;
            $uomname1 = $this->uomname($plan[0]->uom_code_id);

            $group1 = \DB::select("select product_group_id from m_products_t where product_id='" . $val->parent_product . "'");
            $group = $val->product_group_id;
            $group1 = $group1[0]->product_group_id;

            $actual_qty = 0;

            if (!array_key_exists($val->parent_product . $val->process_level, $processs)) {
                $planlines1 = \DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id=' . $id . ' and product_id=' . $val->parent_product);

                $actualqty1 = $val->production_qty;
                if ($actualqty1 == 0 || $actualqty1 == "") {
                    $pendqty1 = $production_qty;
                } else {
                    $pendqty1 = $val->pending_qty;
                }

                // --- Parent row (header-ish) ---
                $html .= '<tr class="table' . $key . ' bg-body-secondary" data-id="' . $val->parent_product . $process . '" data-parent="">
                <td class="text-center"></td>
                <td class="fw-semibold">';

                /* deepika purpose: to create job based on jobtype packing/production */
                $prodqty1 = 0;
                if ($jbtype == "packingjobcard") {
                    $qadata = \DB::table('w_qa_submitstage_trx_t')
                        ->leftjoin('w_jobcard_hdr_t', 'w_jobcard_hdr_t.w_jobs_hdr_id', '=', 'w_qa_submitstage_trx_t.job_no')
                        ->where('w_jobcard_hdr_t.bom_process', $process)
                        ->where('w_jobcard_hdr_t.reference_source_id', $plan[0]->productionplan_hdr_id)
                        ->select(
                            \DB::raw('sum(w_qa_submitstage_trx_t.production_qty) as prodqty'),
                            \DB::raw('sum(w_jobcard_hdr_t.job_adjusted_qty) as jobcard_qty')
                        )
                        ->get();

                    if ($qadata[0]->prodqty != null) {
                        $prodqty = $qadata[0]->prodqty;
                        $jobqty = $qadata[0]->jobcard_qty;
                        $prodqty1 = $prodqty;
                    } else {
                        $jobdata = \DB::table('w_jobcard_hdr_t')
                            ->leftjoin('w_productionplan_hdr_t', 'w_productionplan_hdr_t.productionplan_hdr_id', '=', 'w_jobcard_hdr_t.reference_source_id')
                            ->where('w_jobcard_hdr_t.bom_process', $process)
                            ->where('w_jobcard_hdr_t.reference_source_id', $plan[0]->productionplan_hdr_id)
                            ->select(\DB::raw('sum(w_jobcard_hdr_t.job_adjusted_qty) as job_adjusted_qty'), 'w_productionplan_hdr_t.productionplan_hdr_id')
                            ->get();

                        if (count($jobdata) > 0) {
                            $prodqty1 = $jobdata[0]->job_adjusted_qty;
                        }
                    }

                    if ($process == "FINALPROCESS") {
                        $jobcard_status = $plan[0]->job_card_status;
                    } else if ($process != "FINALPROCESS") {
                        $jobcard_status = $val->job_card_status;
                    } else {
                        $jobcard_status = 0;
                    }
                } else {
                    $jobcard_status = $plan[0]->job_card_status;
                    $prodqty1 = 0;
                }

                if ($group1 == 1) {
                    if ($jbtype == "packingjobcard") {
                        if ($process == "FINALPROCESS") {
                            $html .= '<input type="radio" value="' . $val->parent_product . '" class="form-check-input job job' . $i . '" name="job[]" data-hdr="' . $plan[0]->productionplan_hdr_id . '" data-sub="hdr" data-qty="' . $production_qty . '" data-process="' . $process . '" data-key="' . $i . '" data-pendingqty="' . $pendqty1 . '" data-jobcardstatus="' . $jobcard_status . '" data-prodqty="' . $prodqty1 . '" data-qoh="' . $qoh1 . '">';
                        }
                    }
                } else if ($group1 == 4) {
                    if ($jbtype == "production") {
                        $html .= '<input type="radio" value="' . $val->parent_product . '" class="form-check-input job job' . $i . '" name="job[]" data-hdr="' . $plan[0]->productionplan_hdr_id . '" data-sub="hdr" data-qty="' . $production_qty . '" data-process="' . $process . '" data-key="' . $i . '" data-pendingqty="' . $pendqty . '" data-jobcardstatus="' . $jobcard_status . '" data-prodqty="' . $prodqty1 . '" data-qoh="0">';
                    }
                }

                $html .= '<input type="hidden" name="bulk_productionplan_line_id[]" class="form-control form-control-sm bulk_productionplan_line_id" value="">
                </td>';

                $html .= '<td class="pdtdiv">
                    <input type="hidden" id="bulk_product_id" class="bulk_product_id" value="' . $val->parent_product . '">
                    <input type="hidden" name="bulk_product_id[]" class="form-control form-control-sm bg-transparent border-0 p-0 bulk_product_id" value="' . $val->parent_product . '" readonly>
                    <input type="text" title="' . $productname1 . '" class="form-control form-control-sm bg-transparent border-0 p-0" value="' . $productname1 . '" readonly>
                </td>
                <td>
                    <input type="hidden" id="bulk_uom_code_id" class="bulk_uom_code_id" value="' . $val->uom_code_id . '">
                    <input type="text" name="bulk_uom_code_id[]" class="form-control form-control-sm bg-transparent border-0 p-0 uom" value="' . $uomname1 . '" readonly>
                </td>
                <td>
                    <input type="text" name="bulk_qty[]" class="form-control form-control-sm text-end bg-transparent border-0 p-0 input_qty_width" value="' . $production_qty . '" readonly>
                </td>';

                if ($group == 4 || $group == 1) {
                    $html .= '<td>
                        <input type="text" name="bulk_actual_qty[]" class="form-control form-control-sm text-end bg-transparent border-0 p-0 input_qty_width" value="' . $actualqty1 . '" readonly>
                    </td>
                    <td>
                        <input type="text" name="bulk_pending_qty[]" class="form-control form-control-sm text-end bg-transparent border-0 p-0 input_qty_width" value="' . $pendqty1 . '" readonly>
                    </td>';
                } else {
                    $html .= '<td></td><td></td>';
                }

                if ($jbtype == "packingjobcard") {
                    $html .= '<td>
                        <input type="text" name="bulk_process_level[]" class="form-control form-control-sm bg-transparent border-0 p-0 bulk_process_level" value="' . $process . '" readonly>
                    </td>
                    <td>
                        <input type="text" name="bulk_process_name[]" class="form-control form-control-sm bg-transparent border-0 p-0 bulk_process_name" value="' . $val->process_name . '" readonly>
                    </td>';
                }

                $html .= '<td>
                    <input type="text" class="form-control form-control-sm text-end bg-transparent border-0 p-0 bulk_qoh input_qty_width" value="' . $qoh1 . '" readonly>
                    <input type="hidden" name="counter[]">
                </td>
                <td></td>
                <td></td>
                <td>
                    <input type="text" class="form-control form-control-sm text-end bg-transparent border-0 p-0 bulk_capacity_qty input_qty_width bulk_capacity_qty' . $key . '" value="' . $capacity . '" readonly>
                </td>
            </tr>';

                $processs[$val->parent_product . $val->process_level] = "0";
                $i++;
            }

            if ($val->product_id != 0) {

                $html .= '<tr class="table' . $key . '" data-id="' . $val->productionplan_line_id . '" data-parent="' . $val->parent_product . $process . '" data-process="' . $process . '">
                <td>';

                if ($group == 4) {
                    if (!array_key_exists($val->process_level, $processs)) {
                        if ($jbtype == "production") {
                            $html .= '<input type="radio" value="' . $val->product_id . '" class="form-check-input job" name="job[]" data-hdr="' . $plan_hdr_id . '" data-sub="line" data-qty="' . $prod_qty . '" data-process="0" data-pendingqty="' . $pendqty . '" data-jobcardstatus="' . $val->job_card_status . '">';
                        }
                    }
                }

                $html .= '<input type="hidden" name="bulk_productionplan_line_id[]" class="form-control form-control-sm bulk_productionplan_line_id" value="">
                <input type="hidden" name="bulk_parent_product[]" class="form-control form-control-sm bulk_parent_product" value="' . $id . '">
            </td>';

                $html .= '<td><input type="text" name="bulk_line_no[]" class="form-control form-control-sm bg-transparent border-0 p-0 bulk_line_no" value="' . ($key + 1) . '" readonly></td>';

                $html .= '<td class="pdtdiv">
                    <input type="hidden" id="bulk_product_id" class="bulk_product_id" value="' . $val->product_id . '">
                    <input type="hidden" name="bulk_product_id[]" class="form-control form-control-sm bg-transparent border-0 p-0 bulk_product_id" value="' . $val->product_id . '" readonly>
                    <input type="text" title="' . $productname . '" class="form-control form-control-sm bg-transparent border-0 p-0" value="' . $productname . '" readonly>
                </td>
                <td>
                    <input type="hidden" id="bulk_uom_code_id" class="bulk_uom_code_id" value="' . $val->uom_code_id . '">
                    <input type="text" name="bulk_uom_code_id[]" class="form-control form-control-sm bg-transparent border-0 p-0 uom" value="' . $uomname . '" readonly>
                </td>
                <td>
                    <input type="text" name="bulk_qty[]" class="form-control form-control-sm text-end bg-transparent border-0 p-0 input_qty_width" value="' . $prod_qty . '" readonly>
                </td>';

                if ($group == 4 || $group == 1) {
                    $html .= '<td>
                        <input type="text" name="bulk_actual_qty[]" class="form-control form-control-sm text-end bg-transparent border-0 p-0 input_qty_width" value="' . $actualqty . '" readonly>
                    </td>
                    <td>
                        <input type="text" name="bulk_pending_qty[]" class="form-control form-control-sm text-end bg-transparent border-0 p-0 input_qty_width" value="' . $pendqty . '" readonly>
                    </td>';
                } else {
                    $html .= '<td></td><td></td>';
                }

                if ($jbtype == "packingjobcard") {
                    $html .= '<td></td><td></td>';
                }

                $html .= '<td>
                    <input type="text" class="form-control form-control-sm text-end bg-transparent border-0 p-0 bulk_qoh input_qty_width" value="' . number_format($qoh1, $decimal, ".", "") . '" readonly>
                    <input type="hidden" name="counter[]">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-end bg-transparent border-0 p-0 bulk_qoh input_qty_width" value="' . number_format($val->resqty, $decimal, ".", "") . '" readonly>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-end bg-transparent border-0 p-0 bulk_resqoh input_qty_width" value="' . number_format($val->other_resqty, $decimal, ".", "") . '" readonly>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm text-end bg-transparent border-0 p-0 bulk_capacity_qty' . $product_id . $process . ' input_qty_width bulk_capacity_qty" value="' . number_format($capacity, \Session::get("decimal"), ".", "") . '" readonly>
                </td>
            </tr>';

                if ($group == "4") {
                    if ($_GET['jobtype'] == "production") {
                        $html1 = $this->jobbomsubdetails($val->product_id, $prod_qty, $plan_hdr_id, $source, $process, $plan_no, $jbtype, $raw_data, $val->productionplan_line_id);
                        $html .= $html1;
                    }
                }
            }
        }

        // Close table + wrapper
        $html .= '</tbody></table></div>';

        // Minification (kept from your original)
        if (strpos($html, '<pre>') !== false) {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/" => '<?php ',
                "/\r/" => '',
                "/>\n</" => '><',
                "/>\s+\n</" => '><',
                "/>\n\s+</" => '><',
            );
        } else {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/\n([\S])/" => '$1',
                "/\r/" => '',
                "/\n/" => '',
                "/\t/" => '',
                "/ +/" => ' ',
            );
        }
        $buffer = preg_replace(array_keys($replace), array_values($replace), $html);

        ini_set('zlib.output_compression', 'On'); // If you like to enable GZip, too!
        return $buffer;
    }





    /* purpose:to display bom details from plan lines based on sfg*/
    public function
        jobbomsubdetails(
        $pid = null,
        $production_qty = null,
        $plan_hdr_id = null,
        $source = null,
        $process = 0,
        $planno = null,
        $jbtype = null,
        $raw_data,
        $line_id
    ) {
        $comp = \Session::get('companyid');



        $html = '';

        $sub_row = $raw_data->where('parent_product', $pid)->groupBy('product_id');
        $sub_row->all();
        $sql = array();
        foreach ($sub_row as $key => $value) {
            $sql[] = $value[0];
            # code...
        }

        //	$sub_row->toArray();
//dd($sub_row);	
//		$sub_row = $sub_row->groupBy('product_id');
//$sub_row->all();

        $decimal = \Session::get("decimal");
        if (count($sql) > 0) {
            foreach ($sql as $key1 => $val1) {




                /*	 if($val1->group_name=='SEMI FINISHED GOODS' || $val1->group_name=='FINISHED GOODS'){
                   $qoh1=\DB::Select("select sum(f.qty-f.qtyy) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id,qualitystatus FROM i_qoh_detail_t where product_id='".$val1->product_id."' and company_id='".$comp."' and qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd,0 as qualitystatus from i_reservation_detail_t where product_id='".$val1->product_id."' and company_id='".$comp."' and reference_no='$planno' GROUP by product_id)f ");
                   }else{

                      $qoh1=\DB::Select("select sum(f.qty - f.qtyy) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='".$val1->product_id."' and company_id='".$comp."' GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='".$val1->product_id."' and company_id='".$comp."' and reference_no='$planno' GROUP by product_id)f "); 
                   }*/

                //$qoh1=0;

                if ($val1->group_name == 'FINISHED GOODS') {
                    if ($val->qoh1 > 0) {
                        $qoh1 = $val1->qoh1 - $val1->resqty;
                    } else {
                        $qoh1 = 0;
                    }
                } else {
                    if ($val1->qoh1 > 0) {
                        $qoh1 = $val1->qoh - $val1->resqty;
                    } else {
                        $qoh1 = 0;
                    }
                }


                if ($val1->resqty == 0 && $qoh1 == 0) {
                    $totqoh = number_format($val1->other_resqty, $decimal, ".", "");
                } else {
                    $totqoh = number_format($val1->resqty + $qoh1, $decimal, ".", "");
                }
                if ($totqoh <= 0) {
                    $capacity = "0";
                } else {
                    if ($val1->component_qty != '') {

                        $capacity = number_format($totqoh / $val1->component_qty, $decimal, ".", "");
                    } else {
                        $capacity = "0";
                    }

                }

                $group1 = $val1->product_group_id;

                $prod_qty1 = number_format($production_qty * $val1->component_qty, $decimal, ".", "");
                $actual_qty1 = $val1->production_qty;
                if ($actual_qty1 == 0 || $actual_qty1 == "") {
                    $pendqty1 = $prod_qty1;
                } else {
                    $pendqty1 = $val1->pending_qty;
                }
                //$prd1=$this->jCombo('m_products_t','product_id','concatenated_product',$val1->product_id);
                //$uom1=$this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$val1->uom_code_id);
                $product = $val1->productname;
                $uom = $val1->uom_code;
                $html .= '
			
			<tr class="sub' . $pid . '  inter_sub' . $val1->product_id . '" data-id="' . $val1->productionplan_line_id . '" data-parent="' . $line_id . '">';

                $html .= '<td>
                  <input type="hidden" name="bulk_productionplan_line_id[]" class="form-control input-sm bulk_productionplan_line_id" value="">
					<input type="hidden" name="bulk_parent_product[]" class="form-control input-sm bulk_parent_product" value="' . $pid . '">';

                if ($group1 == 4 && $source != 'PLAN APPROVAL') {
                    if ($jbtype == 'production') {
                        $html .= '  <input type="radio" value="' . $val1->product_id . '" class="job" name="job[]" data-hdr="' . $plan_hdr_id . '" data-sub="line" data-qty="' . $prod_qty1 . '" data-process="0" data-pendingqty="' . $pendqty1 . '" data-jobcardstatus="' . $val1->job_card_status . '"></td>';
                    }
                }
                $html .= '<td><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="' . ($key1 + 1) . '" readonly="readonly" style="width: 68px !important;">
					</td>';



                $html .= '<td class="pdtdiv">
					<input type="hidden" name="bulk_product_id[]" class="input-sm bulk_product_id" value="' . $val1->product_id . '" readonly="readonly" style="color:black;">
					<input type="text"  title="' . $product . '" class="input-sm bulk_product_id" value="' . $product . '" readonly="readonly" style="color:black;">
					</td>
					<td>
					<input type="hidden" name="bulk_uom_code_id[]" class="input-sm bulk_uom_code_id" value="' . $val1->uom_code_id . '" readonly="readonly" >
					<input type="text"  title="' . $uom . '" class="input-sm bulk_uom_code_id" value="' . $uom . '" readonly="readonly" style="color:black;width:80px;">
					</td>
					<td>
					<input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="' . $prod_qty1 . '" required="required" readonly="readonly" style="width:80px;">
					</td>';
                if ($source != 'PLAN APPROVAL' && $source != 'PRODUCTION ANALYSE') {
                    if ($group1 == 4 || $group1 == 1) {
                        $html .= '<td>
					<input type="text" name="bulk_actual_qty[]" class="input-sm bulk_actual_qty input_qty_width" value="' . $actual_qty1 . '" style="color:black;" readonly>
					</td>
					<td>
					<input type="text" name="bulk_pending_qty[]" class="input-sm bulk_pending_qty input_qty_width" value="' . $pendqty1 . '" style="color:black;" readonly>
					</td>';
                    } else {
                        $html .= '<td></td><td></td>';
                    }
                }
                if ($jbtype == "packingjobcard") {
                    $html .= '<td></td>';
                }
                $html .= '<td>
						<input type="hidden" name="bulk_component_qty[]" class="input-sm bulk_component_qty input_qty_width" value="' . $val1->component_qty . '" >
					<input type="hidden" name="bulk_process_level[]" class="input-sm bulk_process_level input_qty_width" value="' . $val1->process_level . '">
					<input type="hidden" name="bulk_process_name[]" class="input-sm bulk_process_name input_qty_width" value="' . $val1->process_name . '">

				        <input type="text" name="bulk_qoh[]" class="form-control input-sm bulk_qoh input_qty_width" value="' . number_format($qoh1, $decimal, ".", "") . '" readonly="readonly" style="width:80px;">
		<input type="hidden" name="counter[]">			
</td>';
                if ($source == 'PLAN APPROVAL' || $source == 'CREATE JOB') {
                    $html .= '<td>
				<input type="text"  class="input-sm bulk_reserveqoh input_qty_width" value="' . number_format($val1->resqty, $decimal, ".", "") . '" style="color:black;width:80px;" readonly>
					
					</td><td>
				<input type="text"  class="input-sm bulk_reserveqoh input_qty_width" value="' . number_format($val1->other_resqty, $decimal, ".", "") . '" style="color:black;width:80px;" readonly>
					</td>';
                }
                if ($source != 'PLAN APPROVAL' && $source != 'PRODUCTION ANALYSE') {
                    $process = 0;
                    $html .= '<td>
        <input type="text"  class="form-control input-sm bulk_capacity_qty' . $pid . $process . ' input_qty_width bulk_capacity_qty" value=' . number_format($capacity, \Session::get("decimal"), ".", "") . '  readonly>
    </td>';
                }

                $html .= '</tr>';

                if ($group1 == "4") {

                    $html .= $this->jobbomsubdetails($val1->product_id, $prod_qty1, $plan_hdr_id, $source, $process, $planno, $jbtype, $raw_data, $val1->productionplan_line_id);
                }

            }
        } else {
            $html .= '<tr class="sub' . $pid . '  inter_sub0" data-id="0"  data-parent="' . $line_id . '"><td></td><td></td><td></td><td><input type="hidden" class="nobom"/>No Bom for these product</td></tr>';
        }
        return $html;

    }
    /*end*/
    /*purpose:to get product uom and qty based on uom conversion */
    public function planproductdetails($id = null, $wid = null)
    {
        $pid = $id;
        $prddetails = \DB::select("select p.primary_uom_id,wl.workorder_hdr_id,wl.workorder_line_id,wl.product_id,sum(wl.qty) as qty, wl.uom_code_id from w_workorder_lines_t wl left join m_products_t p on(p.product_id=wl.product_id) WHERE  wl.workorder_line_id in($wid) and wl.product_id='$pid' group by wl.product_id");
        if (!empty($prddetails)) {
            $this->data['productdata'] = $prddetails;
            foreach ($this->data['productdata'] as $key => $value) {
                $uom_conversion = \DB::select("select * from m_uom_conversion_t where product_id='" . $value->product_id . "'");
                if (count($uom_conversion) > 0) {
                    $total = $value->qty * $uom_conversion[0]->uom_value;
                } else {
                    $total = 0;
                }
                $data['uom_code_id'] = $value->primary_uom_id;
                $data['production_qty'] = $total;
            }

        } else {
            $data['uom_code_id'] = 0;
            $data['production_qty'] = 0;
        }
        return $data;
    }/*end*/
    /*deepika purpose:to get product name */
    public function productname($id = null)
    {
        $sql = \DB::select("select product_id,concat(product_code,'-',concatenated_product) as product from m_products_t where product_id=" . $id);
        return $sql[0]->product;
    }
    /*end*/
    /*deepika purpose:to uom code */
    public function uomname($id = null)
    {
        $sql = \DB::select("select uom_code_id,uom_code from m_uom_codes_t where uom_code_id=" . $id);
        if (count($sql) > 0) {
            return $sql[0]->uom_code;
        } else {
            return 0;
        }

    }
    /*end*/
    /*deepika purpose:to get process name */
    public function processname($id = null)
    {
        $sql = \DB::select("select lookup_code from a_lookuplines_t where lookup_code='" . $id . "' and lookup_type='PROCESS_LEVEL'");
        if (!empty($sql)) {
            return $sql[0]->lookup_code;
        } else {
            return "";
        }
    }
    /*end*/
    /*purpose:to  get qoh based on bom details*/
    public function productionplanlines($pqty = null, $pid = null)
    {
        $sql = "SELECT m_material_bom_lines_t.component_product_id  as product_id,m_material_bom_lines_t.process_level,m_material_bom_lines_t.component_qty,m_material_bom_hdr_t.assembly_product_id  FROM `m_material_bom_hdr_t` left join m_material_bom_lines_t on m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id where  1=1  and m_material_bom_hdr_t.assembly_product_id='$pid' and m_material_bom_lines_t.process_level='" . $_GET['process'] . "'";
        $result = \DB::select($sql);

        $availableqoh = array();
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $qoh_qty = \DB::select("SELECT ( SELECT SUM(qoh_trx_qty) FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '" . $value->product_id . "') as qoh ,( SELECT IFNULL( SUM(reserv_trx_qty),0) FROM i_reservation_detail_t WHERE i_reservation_detail_t.product_id = '" . $value->product_id . "') as res,(SELECT qoh - IFNULL(res,0) ) as avail_qty FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '" . $value->product_id . "'  GROUP BY i_qoh_detail_t.product_id ");

                $total = $value->component_qty * $pqty;

                if (!empty($qoh_qty) && $total > 0) {
                    $availableqoh[$key]['qoh'] = $qoh_qty[0]->avail_qty;
                    $availableqoh[$key]['product_id'] = $value->product_id;
                    $availableqoh[$key]['qty'] = $total;
                    $availableqoh[$key]['process_level'] = $value->process_level;
                } else {
                    $availableqoh[$key]['qoh'] = 0;
                    $availableqoh[$key]['product_id'] = $value->product_id;
                    $availableqoh[$key]['qty'] = $total;
                    $availableqoh[$key]['process_level'] = $value->process_level;
                }

            }
            return $availableqoh;
        } else {
            return $availableqoh;
        }
    }
    /*end*/

    public function planschedulemail($ids, Request $request)
{
    try {

        if (empty($ids)) {
            return response()->json(['status' => false, 'message' => 'No plans selected']);
        }

        $from_umail = "cheran_senguttuvan@jrkresearch.com";

        $to_mail = "cheran_senguttuvan@jrkresearch.com,drabbas@jrkresearch.com,aruna_v@jrkresearch.com,hemashree_k@jrkresearch.com,labs@jrkresearch.com,operations@jrkresearch.com,uma_p@jrkresearch.com,purchase_rm@jrkresearch.com,purchase_pm@jrkresearch.com";

        $cc_mail = "gayathri_rajagopal@jrkresearch.com,aspire@jrkresearch.com";

        // convert to array
        $idArray = array_filter(explode(",", $ids));

        // fetch plan details
        $plans = DB::table('w_productionplan_hdr_t')
            ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'w_productionplan_hdr_t.product_id')
            ->select(
                'w_productionplan_hdr_t.productionplan_hdr_id',
                'w_productionplan_hdr_t.plan_no',
                'm_products_t.concatenated_product',
                'w_productionplan_hdr_t.plan_date',
                'w_productionplan_hdr_t.workorder_due_date',
                'w_productionplan_hdr_t.start_date',
                'w_productionplan_hdr_t.end_date',
                'w_productionplan_hdr_t.production_qty',
                'w_productionplan_hdr_t.email_status'
            )
            ->whereIn('w_productionplan_hdr_t.productionplan_hdr_id', $idArray)
            ->get();

        if ($plans->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No data found']);
        }

        // check if already mailed
        $alreadySent = $plans->where('email_status', 1)->count();

        if ($alreadySent > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Mail already sent for selected plan(s)'
            ]);
        }

        // ✉️ SEND MAIL
        Mail::send('productionplan.planschedule', ['plans' => $plans], function ($message) use ($from_umail, $to_mail, $cc_mail) {
            $message->from($from_umail)
                ->to(explode(',', $to_mail))
                ->cc(explode(',', $cc_mail))
                ->subject("Production Plan Schedule Details");
        });

        // update email status
        DB::table('w_productionplan_hdr_t')
            ->whereIn('productionplan_hdr_id', $idArray)
            ->update([
                'email_status' => 1
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Plan Schedule Mail Sent Successfully'
        ]);

    } catch (\Exception $e) {

        \Log::error($e);

        return response()->json([
            'status' => false,
            'message' => 'Mail sending failed'
        ]);
    }
}



    /*purpose:to  get update plan status when close material plan*/
    public function updatestatus($id = null)
    {
        $job = \DB::table('w_jobcard_hdr_t')->where('reference_source_id', $id)->get();
        $jobcnt = 0;
        if (!empty($job)) {
            $jobcnt = count($job);
        }
        if ($jobcnt == 0) {
            $sql = \DB::update('update w_productionplan_hdr_t set plan_status="CLOSED" where productionplan_hdr_id=' . $id);
            \DB::table('i_reservation_detail_t')->where('reference_no', $_GET['planno'])->delete();
            if ($sql == true) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
    /*end*/
}