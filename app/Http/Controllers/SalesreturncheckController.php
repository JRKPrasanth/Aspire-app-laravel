<?php

namespace App\Http\Controllers;
use DB;
use App\Salesreturncheck;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SalesreturncheckController extends Controller
{

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


        $table = \DB::table('so_rma_hdr_t')->get();

        $a = 3;
        $this->data['subinventory_id'] = $this->jcustomselect('m_subinventory_t', 'subinventory_id', 'subinventory_name', $a, '');
        $this->data['sublocator_id'] = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', $a, '');
        $this->data['datas'] = $table;
        $this->data['pageMethod'] = "salesreturncheck";
        $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
        $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $this->data['urlmenu'] = $this->indexs();

        return view('salesreturncheck.table', $this->data);
    }

    public function getSalesreturncheckData()
    {
        $wh = '';
        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');

        $wh .= $grid_data = $this->grid_datecheck('so_rma_hdr_t', 'return_date', 'return_status');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');

        $SQL = "SELECT
                so_rma_hdr_t.`so_rma_hdr_id`,
                 so_rma_lines_t.`so_rma_line_id`,
                so_rma_hdr_t.`rma_ref_no`,
                so_rma_hdr_t.`return_date`,
                so_rma_hdr_t.remarks,
                so_rma_lines_t.return_qty,
                so_rma_hdr_t.return_status,
                 so_rma_lines_t.reason_code,
                m_customers_t.customer_name,
                 m_products_t.product_id as productid,
                m_products_t.concatenated_product
                FROM `so_rma_hdr_t`
                left join so_rma_lines_t on(so_rma_hdr_t.so_rma_hdr_id=so_rma_lines_t.so_rma_hdr_id)
                left JOIN m_customers_t  on(
                m_customers_t.customer_id=so_rma_hdr_t.`customerid`
                )
                left JOIN m_products_t  on(
                so_rma_lines_t.product_id=m_products_t.`product_id`
                )
                where so_rma_lines_t.check_status=0 and so_rma_hdr_t.return_status='APPROVED' and so_rma_hdr_t.company_id=$compy and so_rma_hdr_t.location_id=$loc AND DATE(so_rma_hdr_t.return_date) BETWEEN '$grid_date' AND '$gridenddate' $wh";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);
    }


    public function dispatchreturndata($lid = null)
    {

        $data = \DB::table('so_rma_lines_t')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'so_rma_lines_t.product_id')
            ->whereIn('so_rma_line_id', explode(",", $lid))->get();
            
        $html1 = '';
        $html1 .= '<div id="preview-area" class="table-responsive" style="height: 300px;"><table class="table table-bordered clone_table"><thead class="table-light"><tr><th>Line No</th><th>Product</th><th>Batch Number</th><th>Return Qty</th><th>Qty</th></tr></thead><tbody>';
        $a = 0;
        foreach ($data as $key => $value) {


            $batch    = !empty($value->batch_number)   ? explode(",", $value->batch_number)   : [];
            $return   = !empty($value->returnqty)       ? explode(",", $value->returnqty)       : [];
            $stretqty = !empty($value->st_return_qty)   ? explode(",", $value->st_return_qty)   : [];


            foreach ($batch as $k => $v) {

                $ret = isset($return[$k]) ? (float)$return[$k] : 0;
                $str = isset($stretqty[$k]) ? (float)$stretqty[$k] : 0;

                if ($ret != 0) {

                    $rtnqty = $ret - $str;
                    
                    $html1 .= '<tr>
				<td>
					<input type="text" name="line_no[]" class="form-control input-sm line_no" value="' . ($a + 1) . '" readonly="readonly" style="width:68px !important;"> 
				</td>
				<td>
					<input type="text"  class="form-control input-sm product product' . $a . ' input_qty_width" readonly value="' . $value->concatenated_product . '" >
					<input type="hidden" name="product_id[]"  class="product_id product_id' . $a . '" value="' . $value->product_id . '">
					<input type="hidden" name="so_rma_line_id[]"  class="so_rma_line_id so_rma_line_id' . $a . '" value="' . $value->so_rma_line_id . '">
					<input type="hidden" name="so_rma_hdr_id[]"  class="so_rma_hdr_id so_rma_hdr_id' . $a . '" value="' . $value->so_rma_hdr_id . '">
					<input type="hidden" name="manufracture_date[]"  class="manufracture_date manufracture_date' . $a . '" value="' . $value->manufracture_date . '">
					<input type="hidden" name="expiry_date[]"  class="expiry_date expiry_date' . $a . '" value="' . $value->expiry_date . '">

				</td>
				<td>
					<input type="text" name="batch_number[]"  class="form-control input-sm batch_number batch_number' . $a . ' input_qty_width" readonly value="' . $v . '" style="width:100px !important;">
				</td>
				<td>
					<input type="text" name="returnqty[]"  class="form-control returnqty returnqty' . $a . ' input_qty_width"  data-index="' . $a . '" value="' . $rtnqty . '" style="width:100px !important;" readonly>
				</td>
				<td>
					<input type="text" name="returnedqty[]"  class="form-control returnedqty returnedqty' . $a . ' input_qty_width"  data-index="' . $a . '" value="' . $rtnqty . '" style="width:100px !important;">
				</td>
				</tr>';

                    $a = $a + 1;

                }
            }
        }
        $html1 .= '</tbody></table></div>';
        return $html1;
    }

    public function movetoinventory1()
    {

        $loc = \Session::get('location');
        $com = \Session::get('companyid');
        $org = \Session::get('organization');
        $subinventory_id = $_POST['subinventory_id'];
        $locid = $_POST['sublocator_id'];
        $trx_date = date('Y-m-d');

        if ($_POST['stat'] == 'MOVETOINVENTORY') {
            foreach ($_POST['batch_number'] as $k => $v) {
                $prdid = $_POST['product_id'][$k];
                $lid = $_POST['so_rma_line_id'][$k];
                $hdr_id = $_POST['so_rma_hdr_id'][$k];

                $srma = \DB::select("select * from so_rma_lines_t where so_rma_line_id='$lid'");
                if ($srma[0]->return_qty == $srma[0]->st_return_qty) {
                    \DB::update("update so_rma_lines_t set check_status=1 where so_rma_line_id='$lid'");
                }
                $status = 1;

                $ret_qty = $_POST['returnedqty'][$k];
                $source = $_POST['stat'];
                $mfg_date = $_POST['manufracture_date'][$k];
                $exp_date = $_POST['expiry_date'][$k];

                $trx = $this->trans($prdid, $ret_qty, $hdr_id, $lid, $source, 'STORE MOVE', $subinventory_id, $locid);
                $ins = DB::table('i_qoh_detail_t')->insert(['product_id' => $prdid, 'qoh_trx_qty' => $ret_qty, 'batch_number' => $v, 'manufacturer_date' => $mfg_date, 'qoh_trx_date' => $trx_date, 'qoh_source_id' => $hdr_id, 'product_expire_date' => $exp_date, 'qoh_source' => 'SALES RETURN-MOVETOINVENTORY', 'subinventory_id' => $subinventory_id, 'locator_id' => $locid, 'organization_id' => $org, 'location_id' => $loc, 'company_id' => $com, 'create_trx_id' => $trx, 'qualitystatus' => 1, 'qoh_uom_code_id' => $srma[0]->uom_code_id]);
                $stqty = $srma[0]->st_return_qty + $ret_qty;
                if ($srma[0]->return_qty != $stqty) {
                    $status = 0;
                }
                \DB::update("update so_rma_lines_t set st_return_qty=$stqty where so_rma_line_id='$lid'");
                \DB::update("update so_rma_lines_t set check_status='$status' where so_rma_line_id='$lid'");

            }
            $msg = "Move To Inventory ";
        } else if ($_POST['stat'] == 'REWORK') {
            foreach ($_POST['batch_number'] as $k => $v) {
                $prdid = $_POST['product_id'][$k];
                $lid = $_POST['so_rma_line_id'][$k];
                $source = $_POST['stat'];
                $ret_qty = $_POST['returnedqty'][$k];
                $hdr_id = $_POST['so_rma_hdr_id'][$k];

                $srma = \DB::select("select * from so_rma_lines_t where so_rma_line_id='$lid'");
                if ($srma[0]->return_qty == $srma[0]->st_return_qty) {
                    \DB::update("update so_rma_lines_t set check_status=1 where so_rma_line_id='$lid'");
                }
                $status = 1;


                $trx = $this->trans($prdid, $ret_qty, $hdr_id, $lid, $source, 'REWORK', $subinventory_id, $locid);
                $ins = DB::table('i_qoh_detail_t')->insert(['product_id' => $prdid, 'rework_qty' => $ret_qty, 'qoh_source' => 'SALES RETURN-REWORK', 'subinventory_id' => $subinventory_id, 'batch_number' => $v, 'locator_id' => $locid, 'qoh_trx_date' => $trx_date, 'qoh_source_id' => $hdr_id, 'organization_id' => $org, 'location_id' => $loc, 'company_id' => $com, 'create_trx_id' => $trx, 'qoh_uom_code_id' => $srma[0]->uom_code_id]);
                $stqty = $srma[0]->st_return_qty + $ret_qty;
                if ($srma[0]->return_qty != $stqty) {
                    $status = 0;
                }
                \DB::update("update so_rma_lines_t set st_return_qty=$stqty where so_rma_line_id='$lid'");
                \DB::update("update so_rma_lines_t set check_status='$status' where so_rma_line_id='$lid'");

            }
            $msg = "Rework";
        } else {
            foreach ($_POST['batch_number'] as $k => $v) {
                $prdid = $_POST['product_id'][$k];
                $lid = $_POST['so_rma_line_id'][$k];
                $ret_qty = $_POST['returnedqty'][$k];
                $source = $_POST['stat'];
                $hdr_id = $_POST['so_rma_hdr_id'][$k];
                $srma = \DB::select("select * from so_rma_lines_t where so_rma_line_id='$lid'");
                if ($srma[0]->return_qty == $srma[0]->st_return_qty) {
                    \DB::update("update so_rma_lines_t set check_status=1 where so_rma_line_id='$lid'");
                }
                $status = 1;

                $trx = $this->trans($prdid, $ret_qty, $hdr_id, $lid, $source, 'SCRAP STORE MOVE', $subinventory_id, $locid);
                $ins = DB::table('i_qoh_detail_t')->insert(['product_id' => $prdid, 'scrap_qty' => $ret_qty, 'qoh_source' => 'SALES RETURN-SCRAP', 'subinventory_id' => $subinventory_id, 'locator_id' => $locid, 'batch_number' => $v, 'organization_id' => $org, 'location_id' => $loc, 'qoh_trx_date' => $trx_date, 'qoh_source_id' => $hdr_id, 'company_id' => $com, 'create_trx_id' => $trx, 'qoh_uom_code_id' => $srma[0]->uom_code_id]);
                $stqty = $srma[0]->st_return_qty + $ret_qty;
                if ($srma[0]->return_qty != $stqty) {
                    $status = 0;
                }
                \DB::update("update so_rma_lines_t set st_return_qty=$stqty where so_rma_line_id='$lid'");
                \DB::update("update so_rma_lines_t set check_status='$status' where so_rma_line_id='$lid'");

            }
            $msg = "Scrap";
        }
        return response()->json(array('status' => 'success', 'message' => 'Qty ' . $msg . ' Successfully'));

    }

    public function dispatchreturndatadetails($invoiceid = null, $pid = null)
    {
        $data = \DB::select("SELECT * FROM `s_dispatched_qty_t` left join s_dispatch_hdr_t on s_dispatch_hdr_t.so_dispatch_hdr_id=s_dispatched_qty_t.so_dispatch_hdr_id  left join s_dispatch_lines_t on  s_dispatch_lines_t.so_dispatch_line_id=s_dispatched_qty_t.so_dispatch_line_id  left join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=s_dispatch_hdr_t.reference_source_id where s_dispatched_qty_t.issue_qoh!=0 and (s_dispatch_hdr_t.dispatch_source='INVOICE' or s_dispatch_hdr_t.dispatch_source='SALES ORDER') and s_dispatch_hdr_t.reference_source_id= $invoiceid and s_dispatch_lines_t.product_id=$pid");
        if (count($data) > 0) {
            if ($data[0]->s_dispatched_qty_id != null) {

                $check = $data;

            }
        } else {

            $check = \DB::select("SELECT s_dispatched_qty_t.* FROM s_invoice_hdr_t  left join s_dispatch_hdr_t on s_dispatch_hdr_t.so_dispatch_hdr_id=s_invoice_hdr_t.reference_source_id left join s_dispatch_lines_t ON s_dispatch_lines_t.so_dispatch_hdr_id=s_dispatch_hdr_t.so_dispatch_hdr_id LEFT JOIN s_dispatched_qty_t ON s_dispatched_qty_t.so_dispatch_line_id=s_dispatch_lines_t.so_dispatch_line_id   where s_invoice_hdr_t.source='DISPATCH' and s_invoice_hdr_t.invoice_hdr_id=" . $invoiceid . " and s_dispatch_lines_t.product_id=" . $pid . " and s_dispatched_qty_t.issue_qoh!=0");

        }

        $html1 = '';
        $html1 .= '<div id="preview-area" class="table-responsive" style="height: 300px;"><table class="table table-bordered clone_table"><thead class="table-light"><tr><th>Line No</th><th>Batch Number</th><th>Issue Qty</th><th>Returned Qty</th><th>Return Qty</th></tr></thead><tbody>';
        $i = 0;
        if ($_GET['bulk_returnqty'] != 0) {
            $return_value = explode(',', $_GET['bulk_returnqty']);
            foreach ($check as $k => $value) {
                if ($value->issue_qoh != 0) {
                    $i++;
                    if ($value->return_qty != '') {
                        $return = $value->return_qty;
                    } else {
                        $return = 0;
                    }
                    if ($return != $value->issue_qoh) {
                        $html1 .= '<tr>
				<td>
					<input type="text"  class="form-control input-sm line_no" value="' . ($i) . '" readonly="readonly" style="width:68px !important;"> 
				</td>
				<td>
					<input type="text"  class="form-control input-sm batch_number batch_number' . $k . ' input_qty_width" readonly value="' . $value->batch_no . '" style="width:100px !important;">
					<input type="hidden" class="dispatched_qty dispatched_qty' . $k . '" value="' . $value->s_dispatched_qty_id . '">
					
				</td>
				<td>
					<input type="text"  class="form-control issue_qty issue_qty' . $k . ' input_qty_width"  readonly value="' . $value->issue_qoh . '" style="width:100px !important;">
				</td>
				<td>
					<input type="text"  class="form-control returnedqty returnedqty' . $k . ' input_qty_width"  readonly value="' . $return . '" style="width:100px !important;">
				</td>
				<td>
					<input type="text"  class="form-control returnqty returnqty' . $k . ' input_qty_width"  data-index="' . $k . '" value="' . $return_value[$k] . '" style="width:100px !important;">
				</td></tr>';
                    }
                }
            }
        } else {
            foreach ($check as $k => $value) {
                if ($value->issue_qoh != 0) {
                    $i++;
                    if ($value->return_qty != '') {
                        $return = $value->return_qty;
                    } else {
                        $return = 0;
                    }
                    if ($return != $value->issue_qoh) {
                        $html1 .= '<tr>
				<td>
					<input type="text"  class="form-control input-sm line_no" value="' . ($i) . '" readonly="readonly" style="width:68px !important;"> 
				</td>
				<td>
					<input type="text"  class="form-control input-sm batch_number batch_number' . $k . ' input_qty_width" readonly value="' . $value->batch_no . '" style="width:100px !important;">
					<input type="hidden" class="dispatched_qty dispatched_qty' . $k . '" value="' . $value->s_dispatched_qty_id . '">
					
				</td>
				<td>
					<input type="text"  class="form-control issue_qty issue_qty' . $k . ' input_qty_width"  readonly value="' . $value->issue_qoh . '" style="width:100px !important;">
				</td>
				<td>
					<input type="text"  class="form-control returnedqty returnedqty' . $k . ' input_qty_width"  readonly value="' . $return . '" style="width:100px !important;">
				</td>
				<td>
					<input type="text"  class="form-control returnqty returnqty' . $k . ' input_qty_width"  data-index="' . $k . '" value="" style="width:100px !important;">
				</td></tr>';
                    }
                }
            }
        }
        $html1 .= '</tbody></table>
			<button class="qtyok btn btn-large vie"  type="button">Add Return Quantity</button>
			</div>';
        return $html1;
    }
    public function movetoinventory($lid = null, $prdid = null, $subid = null, $locid = null)
    {
        $loc = \Session::get('location');
        $com = \Session::get('companyid');
        $org = \Session::get('organization');
        $batch = explode(",", $_GET['batch_number']);
        $return_qty = explode(",", $_GET['return_qty']);
        if ($lid != '') {
            $srma = \DB::select("select * from so_rma_lines_t where so_rma_line_id='$lid'");
            if ($srma[0]->return_qty == $srma[0]->st_return_qty) {
                \DB::update("update so_rma_lines_t set check_status=1 where so_rma_line_id='$lid'");
            }
            $status = 1;

            if ($_GET['source'] == 'MOVETOINVENTORY') {
                foreach ($batch as $k => $v) {
                    $trx = $this->trans($prdid, $return_qty[$k], $_GET['hdr_id'], $lid, $_GET['source'], 'STORE MOVE', $subid, $locid);
                    $ins = DB::table('i_qoh_detail_t')->insert(['product_id' => $prdid, 'qoh_trx_qty' => $return_qty[$k], 'batch_number' => $v, 'qoh_source' => 'SALES RETURN-MOVETOINVENTORY', 'subinventory_id' => $subid, 'locator_id' => $locid, 'organization_id' => $org, 'location_id' => $loc, 'company_id' => $com, 'create_trx_id' => $trx, 'qualitystatus' => 1, 'qoh_uom_code_id' => $srma[0]->uom_code_id]);
                    $stqty = $srma[0]->st_return_qty + $return_qty[$k];
                    if ($srma[0]->return_qty != $stqty) {
                        $status = 0;
                    }
                    \DB::update("update so_rma_lines_t set st_return_qty=$stqty where so_rma_line_id='$lid'");
                }
                \DB::update("update so_rma_lines_t set check_status='$status' where so_rma_line_id='$lid'");
                $msg = "Move To Inventory ";
            } else if ($_GET['source'] == 'REWORK') {
                foreach ($batch as $k => $v) {
                    $trx = $this->trans($prdid, $return_qty[$k], $_GET['hdr_id'], $lid, $_GET['source'], 'REWORK', $subid, $locid);
                    $ins = DB::table('i_qoh_detail_t')->insert(['product_id' => $prdid, 'rework_qty' => $return_qty[$k], 'qoh_source' => 'SALES RETURN-REWORK', 'subinventory_id' => $subid, 'batch_number' => $v, 'locator_id' => $locid, 'organization_id' => $org, 'location_id' => $loc, 'company_id' => $com, 'create_trx_id' => $trx, 'qoh_uom_code_id' => $srma[0]->uom_code_id]);
                    $stqty = $srma[0]->st_return_qty + $return_qty[$k];
                    if ($srma[0]->return_qty != $stqty) {
                        $status = 0;
                    }
                    \DB::update("update so_rma_lines_t set st_return_qty=$stqty where so_rma_line_id='$lid'");
                }
                \DB::update("update so_rma_lines_t set check_status='$status' where so_rma_line_id='$lid'");
                $msg = "Rework";
            } else {
                foreach ($batch as $k => $v) {
                    $trx = $this->trans($prdid, $return_qty[$k], $_GET['hdr_id'], $lid, $_GET['source'], 'SCRAP STORE MOVE', $subid, $locid);
                    $ins = DB::table('i_qoh_detail_t')->insert(['product_id' => $prdid, 'scrap_qty' => $return_qty[$k], 'qoh_source' => 'SALES RETURN-SCRAP', 'subinventory_id' => $subid, 'locator_id' => $locid, 'batch_number' => $v, 'organization_id' => $org, 'location_id' => $loc, 'company_id' => $com, 'create_trx_id' => $trx, 'qoh_uom_code_id' => $srma[0]->uom_code_id]);
                    $stqty = $srma[0]->st_return_qty + $return_qty[$k];
                    if ($srma[0]->return_qty != $stqty) {
                        $status = 0;
                    }
                    \DB::update("update so_rma_lines_t set st_return_qty=$stqty where so_rma_line_id='$lid'");
                }
                \DB::update("update so_rma_lines_t set check_status='$status' where so_rma_line_id='$lid'");
                $msg = "Scrap";
            }
            return response()->json(array('status' => 'success', 'message' => 'Qty ' . $msg . ' Successfully'));
        }
    }
    function trans($pdt_id, $qty, $hdr_id, $lineid, $type, $type_name, $subid, $locid)
    {
        //	dd($pdt_id,$qty,$hdr_id,$lineid,$type,$type_name,$subid,$locid);
        $trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', $type_name)->get();
        $mtdata['trx_source_type_id'] = $trsns[0]->transaction_source_id;

        $mtdata['trx_action_id'] = $trsns[0]->transaction_action_id;
        $mtdata['trx_type_id'] = $trsns[0]->transaction_type_id;
        $mtdata['trx_source_hdr_id'] = $hdr_id;
        $mtdata['trx_source_line_id'] = $lineid;
        $mtdata['line_number'] = '1';
        $mtdata['product_id'] = $pdt_id;
        $mtdata['trx_qty'] = $qty;
        $mtdata['trx_uom'] = '';
        $mtdata['trx_date'] = date('Y-m-d');
        $mtdata['created_by'] = \Session::get('id');
        $mtdata['created_at'] = date('Y-m-d');
        $mtdata['subinventory_id'] = $subid;
        $mtdata['locator_id'] = $locid;
        $mtdata['organization_id'] = \Session::get('organization');

        $mtlid = \DB::table('m_material_trx_t')->insertGetId($mtdata);
        return $mtlid;
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Salesreturncheck $salesreturncheck)
    {
        //
    }


}
