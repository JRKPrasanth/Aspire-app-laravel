<?php

namespace App\Http\Controllers;
use App\Dispatch;
use App\Dispatchlines;
use App\Salesreplacement;
use App\Salesreplacementlines;
use App\Salespickorder;
use App\Salespickorderlines;
use App\Salesinvoice;
use App\Salesinvoicelines;
use App\Soorder;
use App\Soorderlines;
use App\Product;
use App\Models\User;
use App\Deliveryterms;
use App\uomcodes;
use App\monthendfreeze;
use Illuminate\Http\Request;
use Session;
use File;
use Validator, DB;
use Yajra\DataTables\DataTables;

class DispatchController extends Controller
{
	public function __construct()
	{

		$this->model = new Dispatch;
		$this->submodel = new Dispatchlines;

		$this->data['pageMethod'] = \Request::route()->getName();
		$this->data['pageFormtype'] = 'ajax';
		$this->table = "s_dispatch_hdr_t";
		$this->subtable = "s_dispatch_lines_t";
		$this->data = array(
			'pageModule' => 'dispatch',
			'pageUrl' => url($this->data['pageMethod']),
			'pageMethod' => $this->data['pageMethod']
		);
		$this->data['urlmenu'] = $this->indexs();
		if ($this->data['pageMethod'] == "salesinvoicefromdispatch") {
			$this->data['dispatch_status'] = "DISPATCH";
			$this->data['invoice'] = "INVOICE";
		} else {
			$this->data['invoice'] = "";
			$this->data['dispatch_status'] = "";

		}
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


		$this->data['freightcarrier'] = $this->jqgridselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name');
		$this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Sales"');
		$this->data['pageMethod'] = \Request::route()->getName();

		return view("dispatch.table", $this->data);

	}


	public function create($id = null)
	{

			
		$this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'dispatch')->get();
		$company_id = \Session::get('companyid');
		$monthend_freeze = \DB::select("SELECT * FROM a_monthendfreeze_t WHERE monthend_type='589' ORDER BY monthend_id DESC LIMIT 1");

		$mindate1 = date('Y-m-d', strtotime($monthend_freeze[0]->mindate1));
		$maxdate1 = date('Y-m-d', strtotime($monthend_freeze[0]->maxdate1));
		$this->data['mindate1'] = $mindate1;
		$this->data['maxdate1'] = $maxdate1;
		$this->data['mindate2'] = $monthend_freeze[0]->mindate2;
		$this->data['maxdate2'] = $monthend_freeze[0]->maxdate2;
		$this->data['monfrez_active'] = $monthend_freeze[0]->active;

		if (isset($_GET['status'])) {

			if ($_GET['status'] == "INVOICE") {
				$this->data['pageurl'] = "invoice";
				$this->data['redirecturl'] = "dispatchfrminvoice";
				$salesinvoice = Salesinvoice::find($id);
				$seqno = $this->Seqnoe('SOD', 's_dispatch_hdr_t', '', 'dispatch_count');
				$this->data['so_dispatch_hdr_id'] = "";
				$this->data['dispatch_number'] = "";
				$this->data['prepare_date'] = date('Y-m-d');
				$this->data['dispatch_date'] = date('Y-m-d');
				$this->data['dispatch_source'] = 'INVOICE';
				$this->data['dispatch_status'] = 'DISPATCHED';
				$this->data['soconvert_status'] = $salesinvoice->source;
				$sample = '';
				if ($salesinvoice->source == "SALES ORDER") {
					$dt = \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $salesinvoice->reference_source_id)->select('order_type_id')->get();
					if (count($dt) > 0) {
						if ($dt[0]->order_type_id == "SAMPLE") {
							$sample = "Yes";
						}
					}
				}
				
				$this->data['sample'] = $sample;
				$this->data['location_id'] = $this->jcombo("m_location_t", "location_id", "location_name", \Session::get('location'));
				$this->data['organization_id'] = $this->jcombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
				$this->data['preparer_id'] = $this->jcombo('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $salesinvoice->ar_frieghtcarriers_hdr_id, ' and source_type_id="Sales"');
				$this->data['pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $salesinvoice->invoice_pricelist_id, ' and price_list_type="Sales"');
				$this->data['ship_to_customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', $salesinvoice->ship_to_customer_id, 'and savestatus="SAVE"');
				$this->data['remarks'] = "";
				$this->data['pack_weight'] = "";
				$this->data['packaging_qty'] = "";
				$this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name|last_name', $salesinvoice->employee_id);
				$this->data['inv_type'] = $salesinvoice->invoice_type;
				$custaddress1 = new SoorderController;
				if ($salesinvoice->ship_to_customer_id != 0) {
					$this->data['deliver_to_location_txt'] = $custaddress1->soshipaddress($salesinvoice->ship_to_address_id);

					$this->data['deliver_to_location'] = $salesinvoice->ship_to_address_id;
				} else {
					$this->data['deliver_to_location_txt'] = $this->employeeaddress($salesinvoice->employee_id);
					$this->data['deliver_to_location'] = $salesinvoice->employee_id;
				}
				$this->data['reference_source_id'] = $id;
				$this->data['ar_sales_hdr_id'] = $salesinvoice->ar_sales_hdr_id;
				$sql = \DB::select("select invoice_number,invoice_hdr_id from s_invoice_hdr_t where invoice_hdr_id in ($id)");
				$soinvno = "";
				foreach ($sql as $key => $value) {
					$soinvno .= $value->invoice_number . ",";
				}
				$soinvoiceno = rtrim($soinvno, ',');
				$this->data['reference_no'] = $soinvoiceno;

				$tablelines = \DB::table('s_invoice_lines_t')->where('invoice_hdr_id', $id)->where('dispacth_status', '0')->get();

				$this->data['linedata'] = $tablelines;

				foreach ($this->data['linedata'] as $key => $value) {

					$this->data['linedata'][$key]->line_no = $key + 1;
					$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
					$this->data['linedata'][$key]->so_dispatch_hdr_id = "";
					$this->data['linedata'][$key]->so_dispatch_line_id = "";
					$this->data['linedata'][$key]->reference_hdr_id = $value->invoice_hdr_id;
					$this->data['linedata'][$key]->reference_line_id = $value->invoice_line_id;
					$this->data['linedata'][$key]->ar_sales_hdr_id = $value->reference_hdr_id;
					$this->data['linedata'][$key]->ar_sales_line_id = $value->reference_line_id;
					$this->data['linedata'][$key]->source_code = 'INVOICE';
					$this->data['linedata'][$key]->so_qty = $value->salesorder_qty;
					$this->data['linedata'][$key]->dispatch_qty = '';
					$this->data['linedata'][$key]->dispatched_qty = $value->qty;
					$this->data['linedata'][$key]->dispatcheds_qty = $value->dispacth_qty;
					$this->data['linedata'][$key]->comments = "";
					$this->data['linedata'][$key]->part_no = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $salesinvoice->ship_to_customer_id);

					$qoh_qty = \DB::select("select sum(f.qty-IFNULL(f.qtyy,0)) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='" . $value->product_id . "' and i_qoh_detail_t.company_id='" . $company_id . "' and i_qoh_detail_t.qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='" . $value->product_id . "' and i_reservation_detail_t.company_id='" . $company_id . "' GROUP by product_id)f");

					if (!empty($qoh_qty)) {
						$result[$key] = (object) array();
						if ($qoh_qty[0]->qoh_qty == NULL)
							$this->data['linedata'][$key]->qoh_qty = 0;
						else
							$this->data['linedata'][$key]->qoh_qty = $qoh_qty[0]->qoh_qty;
					} else {
						$this->data['linedata'][$key]->qoh_qty = "0";
					}

					if ($value->product_id != '') {
						$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);

					} else {
						$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'dispatch');
					}

				}

				$this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
			}
			if ($_GET['status'] == "PICKORDER") {
				$this->data['redirecturl'] = "";

				$this->data['pageurl'] = "pickorder";
				$sql_pick = \DB::select("select sales_order_no,sales_hdr_id from s_pickrelease_hdr_t where sales_hdr_id in ($id)");
				$sono = "";
				foreach ($sql_pick as $key => $value) {
					$sono .= $value->sales_order_no . ",";
				}
				$soorderno = rtrim($sono, ',');
				$this->data['reference_no'] = $soorderno;

				$salesorder = Soorder::find($id);
				$seqno = $this->Seqno('SOD', 's_dispatch_hdr_t', '');
				$this->data['so_dispatch_hdr_id'] = "";
				$this->data['dispatch_number'] = "";
				$this->data['prepare_date'] = date('Y-m-d');
				$this->data['dispatch_date'] = date('Y-m-d');
				$this->data['dispatch_source'] = 'PICK ORDER';
				$this->data['dispatch_status'] = 'OPEN';
				$this->data['soconvert_status'] = 'PICK ORDER';
				$this->data['location_id'] = $this->jcombo("m_location_t", "location_id", "location_name", \Session::get('location'));
				$this->data['organization_id'] = $this->jcombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
				$this->data['preparer_id'] = $this->jcombo('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $salesorder->freight_carrier_id, ' and source_type_id="Sales"');
				$this->data['ship_to_customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', $salesorder->ship_to_customer_id, 'and savestatus="SAVE"');
				$this->data['inv_type'] = "";
				$this->data['remarks'] = "";
				$this->data['pack_weight'] = "";
				$this->data['packaging_qty'] = "";
				$this->data['pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $salesorder->pricelist_id, ' and price_list_type="Sales"');
				$address = $this->$custaddress1($salesorder->ship_to_customer_id);
				$this->data['deliver_to_location'] = $address[0];
				$this->data['reference_source_id'] = $id;
				$this->data['ar_sales_hdr_id'] = $salesorder->sales_hdr_id;
				//	$this->data['reference_no']=$salesorder->sales_order_no;

				$sql = \DB::select("select sales_order_no,sales_hdr_id from s_salesorder_hdr_t where sales_hdr_id in ($id)");
				$sono = "";
				foreach ($sql as $key => $value) {
					$sono .= $value->sales_order_no . ",";
				}
				$soorderno = rtrim($sono, ',');
				$this->data['reference_no'] = $soorderno;

				$tablelines = \DB::select("select sl.sales_hdr_id,sl.part_no,sl.sales_line_id,sl.product_id,sum(sl.qty) as qty, sum(sl.dispatched_qty) as dispatched_qty,sl.uom_code_id from s_salesorder_lines_t sl WHERE  sl.sales_hdr_id in($id) group by sl.product_id");
				$this->data['linedata'] = $tablelines;
				foreach ($this->data['linedata'] as $key => $value) {
					$this->data['linedata'][$key]->line_no = $key + 1;
					$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
					$this->data['linedata'][$key]->part_no = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $salesinvoice->ship_to_customer_id);


					$this->data['linedata'][$key]->so_dispatch_hdr_id = "";
					$this->data['linedata'][$key]->so_dispatch_line_id = "";
					$this->data['linedata'][$key]->so_pickrelease_hdr_id = "";
					$this->data['linedata'][$key]->so_pickrelease_line_id = "";
					$this->data['linedata'][$key]->reference_hdr_id = $value->sales_hdr_id;
					$this->data['linedata'][$key]->reference_line_id = $value->sales_line_id;
					$this->data['linedata'][$key]->ar_sales_hdr_id = $value->sales_hdr_id;
					$this->data['linedata'][$key]->ar_sales_line_id = $value->sales_line_id;
					$this->data['linedata'][$key]->source_code = 'PICK ORDER';
					$this->data['linedata'][$key]->so_qty = $value->qty;
					$this->data['linedata'][$key]->dispatch_qty = '';
					$this->data['linedata'][$key]->dispatched_qty = $value->dispatched_qty;
					$this->data['linedata'][$key]->comments = "";

					$qoh_qty = \DB::select("select sum(f.qty-IFNULL(f.qtyy,0)) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='" . $value->product_id . "' and i_qoh_detail_t.company_id='" . $company_id . "' and i_qoh_detail_t.qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='" . $value->product_id . "' and i_reservation_detail_t.company_id='" . $company_id . "' GROUP by product_id)f");
					if (!empty($qoh_qty)) {
						$result[$key] = (object) array();
						$this->data['linedata'][$key]->qoh_qty = $qoh_qty[0]->qoh_qty;
					} else {
						$this->data['linedata'][$key]->qoh_qty = "0";
					}

					if ($value->product_id != '') {
						$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);


					} else {
						$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'dispatch');
					}
				}
				$this->data['sample'] = '';
			}
			if ($_GET['status'] == "SALESORDER") {
				$this->data['pageurl'] = "salesorder";
				$this->data['redirecturl'] = "dispatchfrmso";
				//dd("dfsgf");
				$salesorder = Soorder::find($id);
				//dd($salesorder);
				$seqno = $this->Seqno('SOD', 's_dispatch_hdr_t', '');
				$this->data['so_dispatch_hdr_id'] = "";
				// $this->data['dispatch_number']=$seqno;
				$this->data['dispatch_number'] = "";
				$this->data['prepare_date'] = date('Y-m-d');
				$this->data['dispatch_date'] = date('Y-m-d');
				$this->data['dispatch_source'] = 'SALES ORDER';
				$this->data['dispatch_status'] = 'OPEN';
				$this->data['soconvert_status'] = 'SALES ORDER';
				$sample = '';
				if ($salesorder->order_type_id == "SAMPLE") {
					$sample = "Yes";
				}
				$this->data['sample'] = $sample;

				$this->data['location_id'] = $this->jcombo("m_location_t", "location_id", "location_name", \Session::get('location'));
				$this->data['organization_id'] = $this->jcombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
				$this->data['preparer_id'] = $this->jcombo('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $salesorder->freight_carrier_id, ' and source_type_id="Sales"');
				$this->data['ship_to_customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', $salesorder->ship_to_customer_id, 'and savestatus="SAVE"');
				$this->data['inv_type'] = $salesorder->order_type_id;
				$this->data['remarks'] = "";
				$this->data['pack_weight'] = "";
				$this->data['packaging_qty'] = "";
				$this->data['pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $salesorder->pricelist_id, ' and price_list_type="Sales"');
				if ($salesorder->employee_id != 0) {
				$this->data['employee_id'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $salesorder->employee_id, 'and employee_id=' . $salesorder->employee_id);
				}else{
				$this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
				}
				$custaddress1 = new SoorderController;
				if ($salesorder->ship_to_customer_id != 0) {
					$this->data['deliver_to_location_txt'] = $custaddress1->soshipaddress($salesorder->ship_to_address_id);
					$this->data['deliver_to_location'] = $salesorder->ship_to_address_id;
				} else {
					$this->data['deliver_to_location_txt'] = $this->employeeaddress($salesorder->employee_id);
					$this->data['deliver_to_location'] = $salesorder->employee_id;
				}
				$this->data['reference_source_id'] = $id;
				$this->data['ar_sales_hdr_id'] = $id;
				// $this->data['reference_no']=$salesorder->sales_order_no;

				$sql = \DB::select("select sales_order_no,sales_hdr_id from s_salesorder_hdr_t where sales_hdr_id in ($id)");
				$sono = "";
				foreach ($sql as $key => $value) {
					$sono .= $value->sales_order_no . ",";
				}
				$soorderno = rtrim($sono, ',');
				$this->data['reference_no'] = $soorderno;

				$tablelines = \DB::select("select sl.sales_hdr_id,sl.part_no,sl.pending_qty,sl.sales_line_id,sl.product_id,sum(sl.qty) as qty, sum(sl.dispatched_qty) as dispatched_qty,sl.uom_code_id from s_salesorder_lines_t sl WHERE  sl.sales_hdr_id in($id) and sl.pending_qty !=0     group by sl.product_id HAVING sum(sl.qty - (sl.dispatched_qty + sl.invoiced_qty )) != 0 order by sl.sales_line_id asc");

				$this->data['linedata'] = $tablelines;
				foreach ($this->data['linedata'] as $key => $value) {

					$this->data['linedata'][$key]->line_no = $key + 1;
					$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);

					$this->data['linedata'][$key]->part_no = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $salesorder->ship_to_customer_id);
					$this->data['linedata'][$key]->so_dispatch_hdr_id = "";
					$this->data['linedata'][$key]->so_dispatch_line_id = "";
					$this->data['linedata'][$key]->so_pickrelease_hdr_id = "";
					$this->data['linedata'][$key]->so_pickrelease_line_id = "";
					$this->data['linedata'][$key]->reference_hdr_id = $value->sales_hdr_id;
					$this->data['linedata'][$key]->reference_line_id = $value->sales_line_id;
					$this->data['linedata'][$key]->ar_sales_hdr_id = $value->sales_hdr_id;
					$this->data['linedata'][$key]->ar_sales_line_id = $value->sales_line_id;
					$this->data['linedata'][$key]->source_code = 'SALES ORDER';
					$this->data['linedata'][$key]->so_qty = $value->qty;
					$this->data['linedata'][$key]->dispatch_qty = '';
					$this->data['linedata'][$key]->dispatched_qty = $value->dispatched_qty;
					$this->data['linedata'][$key]->comments = "";

					$qoh_qty = \DB::select("select sum(f.qty-IFNULL(f.qtyy,0)) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='" . $value->product_id . "' and i_qoh_detail_t.company_id='" . $company_id . "' and i_qoh_detail_t.qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='" . $value->product_id . "' and i_reservation_detail_t.company_id='" . $company_id . "' GROUP by product_id)f");

					if (!empty($qoh_qty)) {
						$result[$key] = (object) array();
						$this->data['linedata'][$key]->qoh_qty = $qoh_qty[0]->qoh_qty;
					} else {
						$this->data['linedata'][$key]->qoh_qty = "0";
					}
					if ($value->product_id != '') {
						$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
					} else {
						$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'dispatch');
					}
				}

			}
			if ($_GET['status'] == "dispatch") {

				$this->data['redirecturl'] = "dispatch";
				$this->data['so_dispatch_hdr_id'] = "";
				$this->data['dispatch_number'] = "";
				$this->data['prepare_date'] = date(\Session::get('p_date_format'), strtotime(date('Y-m-d')));
				$this->data['dispatch_date'] = date(\Session::get('p_date_format'), strtotime(date('Y-m-d')));
				$this->data['dispatch_source'] = 'DISPATCH';
				$this->data['dispatch_status'] = 'OPEN';
				$this->data['soconvert_status'] = '';
				$this->data['location_id'] = $this->jcombo("m_location_t", "location_id", "location_name", \Session::get('location'));
				$this->data['organization_id'] = $this->jcombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
				$this->data['preparer_id'] = $this->jcombo('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Sales"');
				$this->data['ship_to_customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', '', 'and savestatus="SAVE"');

				$this->data['remarks'] = "";
				$this->data['pack_weight'] = "";
				$this->data['packaging_qty'] = "";
				$this->data['pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', '', ' and price_list_type="Sales"');
				$this->data['deliver_to_location'] = '';
				$this->data['deliver_to_location_txt'] = '';
				$this->data['reference_source_id'] = '';
				$this->data['ar_sales_hdr_id'] = '';
				$this->data['inv_type'] = '';
				$this->data['part_no'] = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '', '');
				$this->data['reference_no'] = '';

				$this->data['linedata'] = array();

				$this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'dispatch');
				$this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
				$this->data['pageurl'] = \Request::route()->getName();
				$this->data['sample'] = '';

			}
			if ($_GET['status'] == "selfdispatch") {
				$this->data['sample'] = '';
				$this->data['redirecturl'] = "dispatch";
				$this->data['so_dispatch_hdr_id'] = "";
				$this->data['dispatch_number'] = "";
				$this->data['prepare_date'] = date(\Session::get('p_date_format'), strtotime(date('Y-m-d')));
				$this->data['dispatch_date'] = date(\Session::get('p_date_format'), strtotime(date('Y-m-d')));
				$this->data['dispatch_source'] = 'SELF DISPATCH';
				$this->data['dispatch_status'] = 'OPEN';
				$this->data['soconvert_status'] = '';
				$this->data['location_id'] = $this->jcombo("m_location_t", "location_id", "location_name", \Session::get('location'));
				$this->data['organization_id'] = $this->jcombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
				$this->data['preparer_id'] = $this->jcombo('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Sales"');
				$this->data['ship_to_customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', '', 'and savestatus="SAVE"');

				$this->data['remarks'] = "";
				$this->data['pack_weight'] = "";
				$this->data['packaging_qty'] = "";
				$this->data['pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', '', ' and price_list_type="Sales"');
				$this->data['deliver_to_location'] = '';
				$this->data['deliver_to_location_txt'] = '';
				$this->data['reference_source_id'] = '';
				$this->data['ar_sales_hdr_id'] = '';
				$this->data['part_no'] = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '', '');
				$this->data['reference_no'] = '';

				$this->data['linedata'] = array();

				$this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'dispatch');
				$this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
				$this->data['pageurl'] = \Request::route()->getName();


			}
			if ($_GET['status'] == "REPLACEMENT") {
				$this->data['pageurl'] = "salesorder";
				$this->data['redirecturl'] = "dispatchfrmso";

				$salesorder = Salesreplacement::find($id);
				$seqno = $this->Seqno('SOD', 's_dispatch_hdr_t', '');
				$this->data['so_dispatch_hdr_id'] = "";
				$this->data['dispatch_number'] = "";
				$this->data['prepare_date'] = date('Y-m-d');
				$this->data['dispatch_date'] = date('Y-m-d');
				$this->data['dispatch_source'] = 'REPLACEMENT';
				$this->data['dispatch_status'] = 'OPEN';
				$this->data['soconvert_status'] = $salesorder->source;
				$sample = '';
				if ($salesorder->order_type_id == "SAMPLE") {
					$sample = "Yes";
				}
				$this->data['sample'] = $sample;

				$this->data['location_id'] = $this->jcombo("m_location_t", "location_id", "location_name", \Session::get('location'));
				$this->data['organization_id'] = $this->jcombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
				$this->data['preparer_id'] = $this->jcombo('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $salesorder->freight_carrier_id, ' and source_type_id="Sales"');
				$this->data['ship_to_customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', $salesorder->ship_to_customer_id, 'and savestatus="SAVE"');
				$this->data['inv_type'] = $salesorder->order_type_id;
				$this->data['remarks'] = "";
				$this->data['pack_weight'] = "";
				$this->data['packaging_qty'] = "";
				$this->data['pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $salesorder->pricelist_id, ' and price_list_type="Sales"');
				$this->data['employee_id'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $salesorder->employee_id, 'and employee_id=' . $salesorder->employee_id);
				$custaddress1 = new SoorderController;

				$this->data['deliver_to_location_txt'] = $custaddress1->soshipaddress($salesorder->ship_to_address_id);
				$this->data['deliver_to_location'] = $salesorder->ship_to_address_id;
				$this->data['reference_source_id'] = $id;
				$this->data['ar_sales_hdr_id'] = $id;
				$sql = \DB::select("select replacement_number,replacement_hdr_id from s_replacement_hdr_t where replacement_hdr_id in ($id)");
				$sono = "";
				foreach ($sql as $key => $value) {
					$sono .= $value->replacement_number . ",";
				}
				$soorderno = rtrim($sono, ',');
				$this->data['reference_no'] = $soorderno;
				$tablelines = \DB::table('s_replacement_lines_t')->where('replacement_hdr_id', $id)->where('dispacth_status', '0')->get();
				$this->data['linedata'] = $tablelines;
				// dd($this->data['linedata']);
				foreach ($this->data['linedata'] as $key => $value) {

					$this->data['linedata'][$key]->line_no = $key + 1;
					$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);

					$this->data['linedata'][$key]->part_no = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $salesorder->ship_to_customer_id);
					$this->data['linedata'][$key]->so_dispatch_hdr_id = "";
					$this->data['linedata'][$key]->so_dispatch_line_id = "";
					$this->data['linedata'][$key]->so_pickrelease_hdr_id = "";
					$this->data['linedata'][$key]->so_pickrelease_line_id = "";
					$this->data['linedata'][$key]->reference_hdr_id = $value->replacement_hdr_id;
					$this->data['linedata'][$key]->reference_line_id = $value->replacement_line_id;
					$this->data['linedata'][$key]->ar_sales_hdr_id = $value->replacement_hdr_id;
					$this->data['linedata'][$key]->ar_sales_line_id = $value->replacement_line_id;
					$this->data['linedata'][$key]->source_code = 'REPLACEMENT';
					$this->data['linedata'][$key]->so_qty = $value->replacement;
					$this->data['linedata'][$key]->pending_qty = $value->replacement;
					$this->data['linedata'][$key]->qty = $value->replacement;
					$this->data['linedata'][$key]->dispatch_qty = '';
					$this->data['linedata'][$key]->dispatched_qty = $value->replacement;
					$this->data['linedata'][$key]->comments = "";

					$qoh_qty = \DB::select("select sum(f.qty-IFNULL(f.qtyy,0)) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='" . $value->product_id . "' and i_qoh_detail_t.company_id='" . $company_id . "' and i_qoh_detail_t.qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='" . $value->product_id . "' and i_reservation_detail_t.company_id='" . $company_id . "' GROUP by product_id)f");

					if (!empty($qoh_qty)) {
						$result[$key] = (object) array();
						$this->data['linedata'][$key]->qoh_qty = $qoh_qty[0]->qoh_qty;
					} else {
						$this->data['linedata'][$key]->qoh_qty = "0";
					}
					if ($value->product_id != '') {
						$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
					} else {
						$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'dispatch');
					}
				}
			}
		} else {

			/* purpose:edit option*/
			$this->data['sample'] = '';
			$dispatchdata = Dispatch::find($id);
			if ($dispatchdata->sample_count != 0 || $dispatchdata->exps_count != 0) {
				$this->data['sample'] = "Yes";
			}

			$this->data['redirecturl'] = "dispatch";
			$type = \DB::select("select * from s_salesorder_hdr_t where sales_hdr_id=" . $dispatchdata['reference_source_id']);
			if (count($type) > 0) {
				$this->data['inv_type'] = $type[0]->source;
				$this->data['employee_id'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $dispatchdata['employee_id'], 'and employee_id=' . $dispatchdata['employee_id']);
			}

			$this->data['so_dispatch_hdr_id'] = $id;
			$this->data['dispatch_number'] = $dispatchdata['dispatch_number'];
			$this->data['prepare_date'] = date(\Session::get('p_date_format'), strtotime($dispatchdata['prepare_date']));
			$this->data['dispatch_date'] = date(\Session::get('p_date_format'), strtotime($dispatchdata['dispatch_date']));
			$this->data['dispatch_source'] = $dispatchdata['dispatch_source'];
			$this->data['dispatch_status'] = $dispatchdata['dispatch_status'];
			$this->data['soconvert_status'] = $dispatchdata['dispatch_source'];
			$this->data['location_id'] = $this->jcombo("m_location_t", "location_id", "location_name", $dispatchdata['location_id']);
			$this->data['preparer_id'] = $this->jcombo('tb_users', 'id', 'username', $dispatchdata['preparer_id']);
			$this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $dispatchdata['freight_carrier_id'], ' and source_type_id="Sales"');

			$this->data['ship_to_customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', $dispatchdata['ship_to_customer_id'], 'and savestatus="SAVE"');

			$this->data['remarks'] = $dispatchdata['remarks'];
			$this->data['pack_weight'] = $dispatchdata['pack_weight'];
			$this->data['packaging_qty'] = $dispatchdata['packaging_qty'];

			$this->data['pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $dispatchdata['pricelist_id'], ' and price_list_type="Sales"');
			$this->data['deliver_to_location'] = $dispatchdata['deliver_to_location'];
			$custaddress1 = new SoorderController;
			//	dd($dispatchdata['deliver_to_location']);
			$this->data['deliver_to_location_txt'] = $custaddress1->soshipaddress($dispatchdata['deliver_to_location']);
			//dd("swxsddsss");
			$this->data['reference_source_id'] = $dispatchdata['reference_source_id'];
			$this->data['ar_sales_hdr_id'] = $dispatchdata['ar_sales_hdr_id'];
			$this->data['reference_no'] = $dispatchdata['reference_no'];
			$this->data['pageurl'] = \Request::route()->getName();
			$tablelines = \DB::select("select * from s_dispatch_lines_t  WHERE so_dispatch_hdr_id=" . $id);
			$this->data['linedata'] = $tablelines;

			foreach ($this->data['linedata'] as $key => $value) {
				$this->data['linedata'][$key]->line_no = $key + 1;
				$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
				$this->data['linedata'][$key]->part_no = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $dispatchdata['ship_to_customer_id']);
				$this->data['linedata'][$key]->so_dispatch_hdr_id = $value->so_dispatch_hdr_id;
				$this->data['linedata'][$key]->so_dispatch_line_id = $value->so_dispatch_line_id;
				$this->data['linedata'][$key]->so_pickrelease_hdr_id = "";
				$this->data['linedata'][$key]->so_pickrelease_line_id = "";
				$this->data['linedata'][$key]->reference_hdr_id = "";
				$this->data['linedata'][$key]->reference_line_id = "";
				$this->data['linedata'][$key]->ar_sales_hdr_id = "";
				$this->data['linedata'][$key]->ar_sales_line_id = "";
				$this->data['linedata'][$key]->source_code = 'DISPATCH';
				$this->data['linedata'][$key]->so_qty = $value->so_qty;
				$this->data['linedata'][$key]->dispatch_qty = $value->dispatch_qty;
				$this->data['linedata'][$key]->dispatched_qty = $value->dispatched_qty;
				$this->data['linedata'][$key]->comments = "";

				$qoh_qty = \DB::select("select sum(f.qty-IFNULL(f.qtyy,0)) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='" . $value->product_id . "' and i_qoh_detail_t.company_id='" . $company_id . "' and i_qoh_detail_t.qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='" . $value->product_id . "' and i_reservation_detail_t.company_id='" . $company_id . "' GROUP by product_id)f");

				if (!empty($qoh_qty)) {
					$result[$key] = (object) array();
					$this->data['linedata'][$key]->qoh_qty = $qoh_qty[0]->qoh_qty;
				} else {
					$this->data['linedata'][$key]->qoh_qty = "0";
				}

				if ($value->product_id != '') {
					$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);


				} else {
					$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'dispatch');
				}

				$data = \DB::table('s_dispatched_qty_t')->where('s_dispatched_qty_t.so_dispatch_line_id', $value->so_dispatch_line_id)->get();
				$p_line_no = '';
				$p_issue_qoh = '';
				$kit_pack_no = '';
				$p_box_no = '';
				$s_dispatched_qty_id = '';
				$batchno = '';
				$expdate = '';
				$manudate = '';
				$subinvid = '';
				$locid = '';
				foreach ($data as $k1 => $v1) {
					$p_issue_qoh .= $v1->issue_qoh . ",";
					$p_line_no .= $v1->line_id . ",";
					$kit_pack_no .= $v1->kit_pack_no . ",";
					$p_box_no .= $v1->box_no . ",";
					$s_dispatched_qty_id .= $v1->s_dispatched_qty_id . ",";
					$batchno .= $v1->batch_no . ",";
					$expdate .= $v1->expiry_date . ",";
					$manudate .= $v1->manufacture_date . ",";
					$subinvid .= $v1->subinventory_id . ",";
					$locid .= $v1->sublocator_id . ",";

				}
				$this->data['linedata'][$key]->p_line_no = rtrim($p_line_no, ',');
				$this->data['linedata'][$key]->p_issue_qoh = rtrim($p_issue_qoh, ',');
				$this->data['linedata'][$key]->p_kit_pack_no = rtrim($kit_pack_no, ',');
				$this->data['linedata'][$key]->p_box_no = rtrim($p_box_no, ',');
				$this->data['linedata'][$key]->s_dispatched_qty_id = rtrim($s_dispatched_qty_id, ',');
				$this->data['linedata'][$key]->p_batch_no = rtrim($batchno, ',');
				$this->data['linedata'][$key]->p_subinventory_id = rtrim($subinvid, ',');
				$this->data['linedata'][$key]->p_sublocator_id = rtrim($locid, ',');
				$this->data['linedata'][$key]->p_manu_date = rtrim($manudate, ',');
				$this->data['linedata'][$key]->p_exp_date = rtrim($expdate, ',');
			}
			/*end*/
		}
		return view("dispatch.form", $this->data);
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
			'dispatch_source_id',
			'deliver_to_location_txt',
			'enable-masterdetail',
		]);
		// Normalize "bulk_" keys once
		$form = $this->normalizeLineFormKeys($form);

		// Build header + lines
		$data = $this->validatePost($form, $this->table, 'header');

		$data['dispatch_date'] = date('Y-m-d', strtotime($_POST['dispatch_date']));
		$data['prepare_date'] = date('Y-m-d', strtotime($_POST['prepare_date']));
		$lines_data = $this->validatePost($form, $this->subtable, 'lines');
		$repl_type = '';
		if ($_POST['dispatch_status'] != 'DRAFT') {
			if ($_POST['dispatch_source'] == "INVOICE") {
				$replace_type = \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $_POST['reference_source_id'])->select('invoice_type')->get();
				if (count($replace_type) > 0) {
					$repl_type = $replace_type[0]->invoice_type;
				}
				foreach ($lines_data['reference_line_id'] as $k => $v) {
					$total_disqty = \DB::select(" select dispacth_qty from s_invoice_lines_t where invoice_line_id='" . $v . "' ");
					$total_disqty1 = ($total_disqty[0]->dispacth_qty) + ($lines_data['dispatch_qty'][$k]);
					\DB::select("update s_invoice_lines_t set dispacth_qty='" . $total_disqty1 . "' where invoice_line_id ='" . $v . "'");

					if ($total_disqty1 >= $lines_data['dispatched_qty'][$k]) {
						\DB::table('s_invoice_lines_t')->where('invoice_line_id', $v)->update(['dispacth_status' => 1]);
					}
				}

				$hdrtotal1 = \DB::select("select count(invoice_hdr_id) as qty from s_invoice_lines_t where `invoice_hdr_id`='" . $_POST['reference_source_id'] . "'");

				$hdrtotal2 = \DB::select("select count(invoice_hdr_id) as qty from  s_invoice_lines_t where `invoice_hdr_id`='" . $_POST['reference_source_id'] . "' and `dispacth_status`='1'");

				if ($hdrtotal1[0]->qty == $hdrtotal2[0]->qty) {

					\DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $_POST['reference_source_id'])->update(['dispatchstatus' => 1]);


				}
				$data['dispatch_status'] = "DISPATCHED";
			}
		}
	
		$ty = "";
		if ($_POST['dispatch_source'] == "SALES ORDER") {
			$order = \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $_POST['ar_sales_hdr_id'])->select('order_type_id')->groupBy('order_type_id')->get();

			$ty = $order[0]->order_type_id;

		}

		if ($_POST['dispatch_number'] == "") {
			if ($ty == "SAMPLE") {

				$seqno = $this->Seqnoe('SMOD', 's_dispatch_hdr_t', '', 'sample_count');
				$data['dispatch_number'] = $seqno[0];
				$data['sample_count'] = $seqno[1];
			} elseif ($ty == "EXPORT") {

				$seqno = $this->Seqnoe('DNEP', 's_dispatch_hdr_t', $ty, 'exp_count');

				$data['dispatch_number'] = $seqno[0];
				$data['exp_count'] = $seqno[1];

			} elseif ($ty == "EXPORT SAMPLE") {
				$seqno = $this->Seqnoe('DNEPS', 's_dispatch_hdr_t', $ty, 'exps_count');
				$data['dispatch_number'] = $seqno[0];
				$data['exps_count'] = $seqno[1];
			} else {
				$seqno = $this->Seqnoe('SOD', 's_dispatch_hdr_t', '', 'dispatch_count');
				$data['dispatch_number'] = $seqno[0];
				$data['dispatch_count'] = $seqno[1];
			}
		} else {
			$seqno = $_POST['dispatch_number'];
		}
	
		\DB::beginTransaction();
		try {
			$id = $this->model->insertRow($data);

			if ($_POST['so_dispatch_hdr_id'] == "") {
				$action = 'create';
			} else {
				$action = 'edit';
			}

			$this->auditlog($id, "Sales Dispatch", $action, $_POST, "s_dispatch_hdr_t");

			unset($lines_data['p_box_no']);
			unset($lines_data['p_s_dispatched_qty_id']);
			unset($lines_data['p_line_no']);
			unset($lines_data['p_kit_pack_no']);
			unset($lines_data['p_batch_no']);
			unset($lines_data['p_subinventory_id']);
			unset($lines_data['soorder_lineid']);
			unset($lines_data['p_qoh']);
			unset($lines_data['p_sublocator_id']);
			unset($lines_data['p_issue_qoh']);
			unset($lines_data['p_manu_date']);
			unset($lines_data['p_exp_date']);
			unset($lines_data['soorder_qty']);
			unset($lines_data['free_val']);
			$lid = $this->submodel->subgridSave($lines_data, $id);

			/*deepika purpose:dispatch qty save/update and update qty in so */
			\DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $_POST['reference_hdr_id'])->update(['order_status' => 'DISPATCH']);
			if ($_POST['dispatch_status'] != 'DRAFT') {
				if ($_POST['dispatch_source'] == "SELF DISPATCH") {
					$trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'DISPATCH ISSUE FROM STORE')->get();
					if (count($trsns) > 0) {
						$trx_source_type_id = $trsns[0]->transaction_source_id;
						$trx_action_id = $trsns[0]->transaction_action_id;
						$trx_type_id = $trsns[0]->transaction_type_id;
					} else {
						$trx_source_type_id = 0;
						$trx_action_id = 0;
						$trx_type_id = 0;
					}
				} else {
					$trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'DISPATCH RESERVE')->get();
					if (count($trsns) > 0) {
						$trx_source_type_id = $trsns[0]->transaction_source_id;
						$trx_action_id = $trsns[0]->transaction_action_id;
						$trx_type_id = $trsns[0]->transaction_type_id;
					} else {
						$trx_source_type_id = 0;
						$trx_action_id = 0;
						$trx_type_id = 0;
					}
				}
				
				
				foreach ($lid['id'] as $k => $v) {
					
					$arr_count = explode(',', $_POST['p_s_dispatched_qty_id'][$k]);
					$s_dispatched_qty_id = explode(',', $_POST['p_s_dispatched_qty_id'][$k]);
					$s_dispatched = $_POST['p_s_dispatched_qty_id'][$k];
					

					if ($_POST['p_line_no'][$k]) {
							
						foreach ($s_dispatched_qty_id as $kk1 => $vv1) {
			
							$box_no = explode('~', $_POST['p_box_no'][$k]);
							$free_val = explode(',', $_POST['free_val'][$k]);
							$kit_pack = explode('~', $_POST['p_kit_pack_no'][$k]);
							$qty_id = explode(',', $_POST['p_s_dispatched_qty_id'][$k]);
							$subinventory_id = explode(',', $_POST['p_subinventory_id'][$k]);
							$sublocator_id = explode(',', $_POST['p_sublocator_id'][$k]);
							$issue_qoh = explode(',', $_POST['p_issue_qoh'][$k]);
							$manu_date = explode(',', $_POST['p_manu_date'][$k]);
							$batch_no = explode(',', $_POST['p_batch_no'][$k]);
							$exp_date = explode(',', $_POST['p_exp_date'][$k]);

							$disdata['so_dispatch_hdr_id'] = $id;
							$disdata['so_dispatch_line_id'] = $v;
							$disdata['line_id'] = $kk1 + 1;
							$disdata['box_no'] = $box_no[$kk1];
							$disdata['free_val'] = $free_val[$kk1];
							$disdata['kit_pack_no'] = $kit_pack[$kk1] ?? 0;
							$disdata['batch_no'] = $batch_no[$kk1];
							$disdata['subinventory_id'] = $subinventory_id[$kk1];
							$disdata['sublocator_id'] = $sublocator_id[$kk1];
							$disdata['issue_qoh'] = $issue_qoh[$kk1];
							$disdata['manufacture_date'] = $manu_date[$kk1];
							$disdata['expiry_date'] = date('Y-m-d', strtotime($exp_date[$kk1]));
							$disdata['company_id'] = \Session::get('companyid');
							$disdata['location_id'] = \Session::get('location');
							$disdata['created_by'] = \Session::get('emp_id');
							
							if ($vv1 != "" && $vv1 != 0) {
								\DB::table('s_dispatched_qty_t')->where('s_dispatched_qty_id', $vv1)->update($disdata);
							} else {
								if ($issue_qoh[$kk1] != 0)
									\DB::table('s_dispatched_qty_t')->insert($disdata);
							}
							
							$matdata['trx_source_type_id'] = $trx_source_type_id;
							$matdata['trx_action_id'] = $trx_action_id;
							$matdata['trx_type_id'] = $trx_type_id;
							$matdata['trx_source_hdr_id'] = $id;
							$matdata['trx_source_line_id'] = $v;
							$matdata['line_number'] = $k + 1;
							$matdata['product_id'] = $_POST['bulk_product_id'][$k];
							$matdata['subinventory_id'] = $subinventory_id[$kk1];
							$matdata['locator_id'] = $sublocator_id[$kk1];
							$matdata['trx_qty'] = -$issue_qoh[$kk1];
							$matdata['trx_uom'] = $_POST['bulk_uom_code_id'][$k];
							if ($_POST['dispatch_source'] == "SELF DISPATCH") {
								$matdata['trx_reference'] = "SELF DISPATCH";
							} else {
								$matdata['trx_reference'] = "DISPATCH";
							}
							$matdata['company_id'] = \Session::get('companyid');
							$matdata['location_id'] = \Session::get('location');
							$matdata['created_by'] = \Session::get('emp_id');

							$qohdata['so_dispatch_hdr_id'] = $id;
							$qohdata['product_id'] = $_POST['bulk_product_id'][$k];
							$qohdata['subinventory_id'] = $subinventory_id[$kk1];
							$qohdata['locator_id'] = $sublocator_id[$kk1];
							$qohdata['batch_number'] = $batch_no[$kk1];
							$qohdata['qoh_trx_qty'] = -$issue_qoh[$kk1];
							$qohdata['company_id'] = \Session::get('companyid');
							$qohdata['location_id'] = \Session::get('location');
							$qohdata['created_by'] = \Session::get('emp_id');
							$qohdata['created_at'] = date('Y-m-d H:i:s');
							$qohdata['qoh_trx_date'] = date('Y-m-d', strtotime($_POST['dispatch_date']));
							$qohdata['qoh_source_id'] = $id;

							
							if ($_POST['dispatch_source'] == "SELF DISPATCH") {
								$qohdata['qoh_source'] = "SELF DISPATCH";
							} else {
								$qohdata['qoh_source'] = "DISPATCH";
							}
							if ($_POST['dispatch_source'] == "SELF DISPATCH") {
								if ($matdata['trx_qty'] != 0)
									$mtl = \DB::table('m_material_trx_t')->insertGetId($matdata);
								$qohdata['create_trx_id'] = $mtl;
								if ($qohdata['qoh_trx_qty'] != 0)
									\DB::table('i_qoh_detail_t')->insert($qohdata);
	
							} else {

								if ($matdata['trx_qty'] != 0)
									$mtl = \DB::table('m_material_trx_t')->insertGetId($matdata);
								$qohdata['create_trx_id'] = $mtl;
								if ($qohdata['qoh_trx_qty'] != 0)
									\DB::table('i_qoh_detail_t')->insert($qohdata);
							}

						}
						
						if (strpos($_POST['soorder_id'][$k], ',') !== false) {
					
							$sales_lineid = explode(',', $_POST['soorder_id'][$k]);
							$sowise_qty = explode(',', $_POST['sowise_qty'][$k]);
							$product_id = $_POST['bulk_product_id'][$k];
							$reference_line = explode(',', $_POST['reference_line_id'][$k]);
							foreach ($reference_line as $key1 => $value1) {
								$dispatch = \DB::table('s_salesorder_lines_t')->where('product_id', $product_id)->where('sales_line_id', $value1)->select('dispatched_qty', 'qty')->first();
								
								if ($dispatch) {

									    $dispatched_qty = (float) $dispatch->dispatched_qty;
									    $qty            = (float) $dispatch->qty;

									    if ($dispatched_qty == 0) {
									        $bulk_so_qty = $qty - $sowise_qty;
									        $disp_qty    = $sowise_qty;
									    } else {
									        $bulk_so_qty = $qty - ($dispatched_qty + $sowise_qty);
									        $disp_qty    = $dispatched_qty + $sowise_qty;
									    }

								\DB::table('s_salesorder_lines_t')->where('sales_line_id', $value1)->where('product_id', $product_id)->update(['dispatched_qty' => $disp_qty, 'pending_qty' => $bulk_so_qty]);
								}
							}

						} else {

						$sales_lineid = $_POST['soorder_id'][$k];
						$product_id   = (int) $_POST['bulk_product_id'][$k];

						// clean reference line
						$raw_reference   = $_POST['reference_line_id'][$k];
						$clean_reference = str_replace(['[', ']', '"'], '', $raw_reference);
						$reference_line  = array_map('intval', array_filter(explode(',', $clean_reference)));

						// cast input qty
						$sowise_qty = (float) $_POST['sowise_qty'][$k];

						// fetch row
						$dispatch = \DB::table('s_salesorder_lines_t')
							->where('product_id', $product_id)
							->whereIn('sales_line_id', $reference_line)
							->select('dispatched_qty', 'qty')
							->first();
						
						if ($dispatch) {

							$dispatched_qty = (float) $dispatch->dispatched_qty; 
							$qty            = (float) $dispatch->qty; 

							if ($dispatched_qty == 0) {
								
								$bulk_so_qty = $qty - $sowise_qty; 
								$disp_qty    = $sowise_qty; 
								

							} else {
								
								$bulk_so_qty = $qty - ($dispatched_qty + $sowise_qty);
								$disp_qty    = $dispatched_qty + $sowise_qty;
								
							}

							\DB::table('s_salesorder_lines_t')
								->where('product_id', $product_id)
								->whereIn('sales_line_id', $reference_line)
								->update([
									'dispatched_qty' => $bulk_so_qty,
									'pending_qty'    => $disp_qty
								]);



						}

								
									
								}
							}
						}

					
				if ($_POST['ar_sales_hdr_id'] != "" && $_POST['ar_sales_hdr_id'] != "0") {
				

					$sales_id = explode(",", $_POST['ar_sales_hdr_id']);
					foreach ($sales_id as $k => $v) {
			
						$check = 0;
						$sales_data = \DB::SELECT("select * from s_salesorder_lines_t where sales_hdr_id =$v");
						foreach ($sales_data as $k1 => $v1) {
							if ($v1->pending_qty > 0) {
								$check++;
							}
						}
							
						if ($check == 0) {
							\DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $v)->update(['order_status_id' => 'COMPLETED']);
						}
					}
				}
			} else {

				foreach ($lid['id'] as $k => $v) {
					$arr_count = explode(',', $_POST['p_s_dispatched_qty_id'][$k]);
					$s_dispatched_qty_id = explode(',', $_POST['p_s_dispatched_qty_id'][$k]);
					$s_dispatched = $_POST['p_s_dispatched_qty_id'][$k];

					if ($s_dispatched != '')
						$check = \DB::select('select * from s_dispatched_qty_t where s_dispatched_qty_id in(' . $s_dispatched . ')');
					else
						$check = array();

					if ($v != '')
						$oldid = \DB::select('select * from s_dispatched_qty_t where so_dispatch_line_id in(' . $v . ')');
					else
						$oldid = array();


					$existingId = $oldIds = $newIds = array();
					foreach ($oldid as $k1 => $v1) {
						$oldIds[] = $v1->s_dispatched_qty_id;
					}
					foreach ($s_dispatched_qty_id as $val1) {
						$newIds[] = $val1;
					}
					$arraydiff = array_diff($oldIds, $newIds);
					foreach ($arraydiff as $val1) {
						\DB::table('s_dispatched_qty_t')->where('s_dispatched_qty_id', $val1)->delete();
					}
					if ($_POST['p_line_no'][$k]) {

						foreach ($s_dispatched_qty_id as $kk1 => $vv1) {
							$box_no = explode('~', $_POST['p_box_no'][$k]);
							$kit_pack = explode('~', $_POST['p_kit_pack_no'][$k]);
							$qty_id = explode(',', $_POST['p_s_dispatched_qty_id'][$k]);
							$subinventory_id = explode(',', $_POST['p_subinventory_id'][$k]);
							$sublocator_id = explode(',', $_POST['p_sublocator_id'][$k]);
							$issue_qoh = explode(',', $_POST['p_issue_qoh'][$k]);
							$manu_date = explode(',', $_POST['p_manu_date'][$k]);
							$batch_no = explode(',', $_POST['p_batch_no'][$k]);
							$exp_date = explode(',', $_POST['p_exp_date'][$k]);
							$freeval = explode(',', $_POST['free_val'][$k]);
							$disdata['so_dispatch_hdr_id'] = $id;
							$disdata['so_dispatch_line_id'] = $v;
							$disdata['line_id'] = $kk1 + 1;
							$disdata['box_no'] = $box_no[$kk1];
							$disdata['kit_pack_no'] = $kit_pack[$kk1];
							$disdata['batch_no'] = $batch_no[$kk1];
							$disdata['subinventory_id'] = $subinventory_id[$kk1];
							$disdata['sublocator_id'] = $sublocator_id[$kk1];
							$disdata['issue_qoh'] = $issue_qoh[$kk1];
							$disdata['manufacture_date'] = $manu_date[$kk1];
							$disdata['free_val'] = $freeval[$kk1];
							$disdata['expiry_date'] = date('Y-m-d', strtotime($exp_date[$kk1]));
							$disdata['company_id'] = \Session::get('companyid');
							$disdata['location_id'] = \Session::get('location');
							$disdata['created_by'] = \Session::get('emp_id');

							if ($vv1 != "" && $vv1 != 0) {
								\DB::table('s_dispatched_qty_t')->where('s_dispatched_qty_id', $vv1)->update($disdata);
							} else {
								if ($issue_qoh[$kk1] != 0)
									\DB::table('s_dispatched_qty_t')->insert($disdata);
							}
						}
					}
				}
			}
			/*end*/
			\DB::commit();

			return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id, 'lid' => $lid));
		} catch (\Illuminate\Database\QueryException $e) {
			$message = explode('(', $e->getMessage());
			$dbCode = rtrim($message[0], ']');
			$dbCode = trim($dbCode, '[');
			dd($dbCode);
			\DB::rollback();

			return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
		}

	}

	public function show(Dispatch $dispatch, $id = null)
	{
		$disphdrdata = \DB::table('s_dispatch_hdr_t')->leftjoin('tb_users', 'tb_users.id', '=', 's_dispatch_hdr_t.preparer_id')->where('so_dispatch_hdr_id', $id)->get();
		$this->data['dispatch_number'] = $disphdrdata[0]->dispatch_number;
		$this->data['dispatch_source'] = $disphdrdata[0]->dispatch_source;
		$this->data['dispatch_status'] = $disphdrdata[0]->dispatch_status;
		$this->data['prepare_date'] = $disphdrdata[0]->prepare_date;
		$this->data['dispatch_date'] = $disphdrdata[0]->dispatch_date;
		if ($disphdrdata[0]->pricelist_id != 0)
			$this->data['pricelist_id'] = $this->idname("pricelist_name", "i_pricelist_hdr_t", "pricelist_hdr_id", $disphdrdata[0]->pricelist_id);
		else
			$this->data['pricelist_id'] = '';
		$this->data['pack_weight'] = $disphdrdata[0]->pack_weight;
		$this->data['packaging_qty'] = $disphdrdata[0]->packaging_qty;
		$this->data['location_id'] = $this->idname("location_name", "m_location_t", "location_id", $disphdrdata[0]->location_id);
		$custaddress1 = new SoorderController;
		if ($disphdrdata[0]->ship_to_customer_id != "") {
			$this->data['deliver_to_location_txt'] = $custaddress1->soshipaddress($disphdrdata[0]->deliver_to_location);
		} else {
			$this->data['deliver_to_location_txt'] = $this->employeeaddress($disphdrdata[0]->deliver_to_location);
		}
		// dd($this->data);
		/*$this->data['deliver_to_location_txt']=$custaddress1->soshipaddress($disphdrdata[0]->deliver_to_location);*/
		$this->data['deliver_to_location'] = $disphdrdata[0]->deliver_to_location;

		$this->data['preparer_id'] = $this->idname("username", "tb_users", "id", \Session::get("id"));
		$this->data['reference_no'] = $disphdrdata[0]->reference_no;

		$this->data['remarks'] = $disphdrdata[0]->remarks;
		$this->data['freight_carrier_id'] = $this->idname("carrier_name", "m_frieghtcarriers_hdr_t", "ar_frieghtcarriers_hdr_id", $disphdrdata[0]->freight_carrier_id, ' and source_type_id="Sales"');
		$this->data['ship_to_customer_id'] = $this->idname("customer_name", "m_customers_t", "customer_id", $disphdrdata[0]->ship_to_customer_id);
		$this->data['empname'] = $this->idname("first_name|last_name", "hr_employee_t", "employee_id", $disphdrdata[0]->employee_id);
		$a = \DB::table('m_sublocators_t')->where('subinventory_id', $id)->get();
		$tablelines = \DB::table('s_dispatch_lines_t')->where('so_dispatch_hdr_id', $id)->leftjoin('m_products_t', 'm_products_t.product_id', '=', 's_dispatch_lines_t.product_id')->select('s_dispatch_lines_t.*', 'm_products_t.*')->get();

		$this->data['displndata'] = $tablelines;
		//dd($tablelines);
		foreach ($this->data['displndata'] as $key => $value) {
			$this->data['displndata'][$key]->product_id = $this->idname("concatenated_product", "m_products_t", "product_id", $value->product_id);
			$this->data['displndata'][$key]->uom_code_id = $this->idname("uom_code", "m_uom_codes_t", "uom_code_id", $value->uom_code_id);
			$this->data['displndata'][$key]->batch_no = '';
			$sql = DB::table("s_dispatched_qty_t")->where('so_dispatch_line_id', $value->so_dispatch_line_id)->select('*')->get();
			$batch_no = '';
			$qty = '';
			if (count($sql) > 0) {
				foreach ($sql as $k => $v) {
					$batch_no .= $v->batch_no . ',';
					$qty .= $v->issue_qoh . ',';
				}
				$batch_no = rtrim($batch_no, ',');
				$qty = rtrim($qty, ',');
			}
			$this->data['displndata'][$key]->batch_no = $batch_no;
			$this->data['displndata'][$key]->dispatch_qty = $qty;
		}
		$this->data['return_url'] = $_GET['return'];
		return view("dispatch.view", $this->data);
	}

	function getCustomerdetails($id = null)
	{
		$sql = "SELECT
		m_customer_sites_t.contact_person,
		m_customer_sites_t.contact_number,
		m_customer_sites_t.contact_mail as email_id,
		m_customer_sites_t.customer_site_id
		FROM `m_customers_t`
		left join m_customer_sites_t ON
		m_customer_sites_t.customer_id=m_customers_t.customer_id
		where m_customers_t.customer_id='$id'";


		$result1 = \DB::select($sql);

		if (!empty($result1)) {

			foreach ($result1 as $key => $value) {
				$contactperson = explode(',', $value->contact_person);
				$contact_number = explode(',', $value->contact_number);
				$email = explode(',', $value->email_id);
				foreach ($email as $k => $v) {
					$result[] = array($value->customer_site_id, $contactperson[$k], $contact_number[$k], $v);
				}



			}

		} else {
			$result[] = array();

		}
		//dd($result);
		return $result;

	}

	public function dispatchproductqoh($id = null)
	{

		$details = [];
		$uom = \DB::table('m_products_t')->select('trx_uom_id')->where('product_id', $id)->get();
		$details['manufactpartno'] = '';
		if (isset($_GET['c_id'])) {
			$data = \DB::table('m_manufacturer_partno_t')->where('product_id', $id)->where('manufacturer_source_value_id', $_GET['c_id'])->get();
			if (count($data) > 0) {
				$details['manufactpartno'] = $data[0]->manufacturer_partno_id;
			}
		}

		$company_id = \Session::get('companyid');
		$dispatched = \DB::select("SELECT SUM(dispatch_qty) as dispatched_qty from s_dispatch_lines_t where product_id =$id");
		// $qoh = \DB::select(" SELECT SUM(qoh_trx_qty) as qoh_qty FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$id' GROUP BY i_qoh_detail_t.product_id ");
		$qoh = \DB::select("select sum(f.qty-IFNULL(f.qtyy,0)) as qty from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='" . $id . "' and i_qoh_detail_t.company_id='" . $company_id . "' and i_qoh_detail_t.qualitystatus=1  GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='" . $id . "' and i_reservation_detail_t.company_id='" . $company_id . "' GROUP by product_id)f");


		if ($uom->isNotEmpty()) {
			$uom_code_id = $uom[0]->trx_uom_id;
			if ($uom_code_id != 0) {
				$details['uomcode'] = $uom_code_id;
			} else {
				$details['uomcode'] = '';
			}
		} else {
			$details['uomcode'] = '';
		}
		if (count($qoh) > 0) {
			$qoh_qty = $qoh[0]->qty;
			if ($qoh_qty != 0) {
				$details['qoh_qty'] = $qoh_qty;
			} else {
				$details['qoh_qty'] = 0;
			}
		} else {
			$details['qoh_qty'] = 0;
		}

		return $details;

	}


public function dispatchproductexpiry(Request $request)
{
    $id = $request->id;
    $batch = $request->batch;

    $data = \DB::table('i_qoh_detail_t')
        ->select('product_expire_date','manufacturer_date')
        ->where('product_id',$id)
        ->where('batch_number',$batch)
        ->where('qoh_source','SUBINVENTORY TRANSFER RECEIVE')
        ->first();

    return response()->json([
        'manufacturer' => $data->manufacturer_date ?? '',
        'expiry' => $data->product_expire_date ?? ''
    ]);
}

	public function dispatchdata()
	{

		$wh = '';
		$col_name = '';

		if ($_GET['status'] != '') {
			$wh .= " and s_dispatch_hdr_t.dispatch_status='" . $_GET['status'] . "'";
			$col_name = "dispatch_status";
			$op = "=";
			$status_val = "'" . $_GET['status'] . "'";
		}
		if ($_GET['invoice'] != '') {
			$wh .= " and s_dispatch_hdr_t.dispatch_source!='" . $_GET['invoice'] . "' and invoicestatus=0";
		}

		if ($_GET['status'] == 'DISPATCH') {
			$wh .= " and s_dispatch_hdr_t.dispatch_source!='SELF DISPATCH'";
			$col_name = "dispatch_source";
			$op = "!=";
			$status_val = "'SELF DISPATCH'";
		}

		if ($col_name != '') {
			$wh .= $grid_data = $this->grid_statuscheck('s_dispatch_hdr_t', 'dispatch_date', $col_name, $op, $status_val);
		} else {
			$wh .= $grid_data = $this->grid_check('s_dispatch_hdr_t', 'dispatch_date');
		}

		//dd($wh);
		$SQL = "SELECT s_dispatch_hdr_t.`dispatch_number`,s_dispatch_hdr_t.total_qty,s_dispatch_hdr_t.freight_carrier_id,hr_employee_t.first_name, s_dispatch_hdr_t.`so_dispatch_hdr_id`, 
        s_invoice_hdr_t.reference_source_id,s_invoice_hdr_t.lr_no,s_invoice_hdr_t.invoice_hdr_id,s_invoice_hdr_t.invoice_type, s_invoice_hdr_t.source, s_dispatch_hdr_t.dispatch_source, 
        s_dispatch_hdr_t.dispatch_date, s_dispatch_hdr_t.dispatch_status, m_frieghtcarriers_hdr_t.carrier_name , m_customers_t.customer_name,m_customers_t.customer_id,s_salesorder_hdr_t.sales_order_no,s_dispatch_hdr_t.pack_weight, s_dispatch_hdr_t.packaging_qty
         FROM `s_dispatch_hdr_t`
          left join m_customers_t  on ( s_dispatch_hdr_t.ship_to_customer_id=m_customers_t.customer_id )
          left join hr_employee_t on (hr_employee_t.employee_id = s_dispatch_hdr_t.employee_id) 
          left join m_frieghtcarriers_hdr_t  on ( s_dispatch_hdr_t.freight_carrier_id=m_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id )
          left join s_invoice_hdr_t on( s_invoice_hdr_t.reference_source_id=s_dispatch_hdr_t.so_dispatch_hdr_id AND s_invoice_hdr_t.source='DISPATCH') 
          left join s_salesorder_hdr_t on (s_salesorder_hdr_t.sales_hdr_id=s_dispatch_hdr_t.ar_sales_hdr_id)
          where 1=1 AND s_dispatch_hdr_t.dispatch_date > '2025-04-01' $wh";

		$result = \DB::select($SQL);

		return DataTables::of($result)->make(true);
	}


	public function getPackslipprint($id = null)
	{

		require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

		$header = Dispatch::where('so_dispatch_hdr_id', $id)->get();
		$id = $header[0]->so_dispatch_hdr_id;
		if (!empty($header)) {

			$this->data['dispatch_number'] = $header[0]->dispatch_number;
			$this->data['dispatch_date'] = $header[0]->dispatch_date;
			$this->data['sales_hdr_id'] = $header[0]->ar_sales_hdr_id;
			$this->data['ship_to_customer_id'] = $header[0]->ship_to_customer_id;
			if ($header[0]->employee_id != 0) {
				$this->data['empname'] = $this->idname("first_name|last_name", "hr_employee_t", "employee_id", $header[0]->employee_id);
			} else {
				$this->data['empname'] = "";
			}
		} else {
			$this->data['dispatch_number'] = '';
			$this->data['dispatch_date'] = '';
			$this->data['sales_hdr_id'] = '';
			$this->data['ship_to_customer_id'] = '';
			$this->data['empname'] = "";
		}

		/***** order no and date *******/

		$order_details = \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $this->data['sales_hdr_id'])->get();
		//dd($order_details);
		if ($order_details->isNotEmpty()) {
			//dd($this->data);
			$this->data['sales_order_no'] = $order_details[0]->sales_order_no;
			$this->data['sales_order_date'] = $order_details[0]->sales_order_date;
		} else {

			$this->data['sales_order_no'] = '';
			$this->data['sales_order_date'] = '';
		}

		/*********** End ***************/

		$address_site = \DB::table('m_customer_sites_t')->where('customer_site_id', $header[0]->deliver_to_location)->get();

		if ($address_site->isNotEmpty()) {

			$this->data['address'] = $address_site[0]->address;
			$this->data['city'] = $this->getCity($address_site[0]->city);

			$this->data['ship_city'] = $this->getCity($address_site[0]->city);
			//$this->data['city_bill']=$this->data['city'][0]->city_name;
			$this->data['state'] = $this->getState($address_site[0]->state);
			$this->data['state_id_ship'] = $this->data['state'][0]->state_id;
			$this->data['state_name_ship'] = $this->data['state'][0]->state_name;
			$this->data['state_code_bill'] = $this->data['state'][0]->state_code;
			$this->data['country'] = $this->getCountry($address_site[0]->country);
			$this->data['ship_country'] = $this->getCountry($address_site[0]->country);
			//$this->data['country_bill']=$this->data['country'][0]->country_name;
			$this->data['pincode'] = $address_site[0]->pincode;
			$this->data['contact_number'] = $address_site[0]->contact_number;
			$this->data['ship_gst_no'] = $address_site[0]->gst_no;

			$this->data['ship_to_address_1'] = $this->data['address'] . "," . $this->data['city'] . "," . $this->data['state_name_ship'] . "," . $this->data['country'] . "," . $this->data['pincode'];

			$this->data['to_address'] = $address_site[0]->address;
			$this->data['city'] = $this->getCity($address_site[0]->city);

			$this->data['country'] = $this->getCountry($address_site[0]->country);

			$this->data['state'] = $this->getState($address_site[0]->state);
			if ($address_site[0]->state == 0 || $address_site[0]->state == '') {
				$this->data['state_name'] = '';
				$this->data['state_code'] = '';
			} else {
				$this->data['state_name'] = $this->data['state'][0]->state_name;
				$this->data['state_code'] = $this->data['state'][0]->state_code;
			}
			$this->data['pincode'] = $address_site[0]->pincode;


			$this->data['to_address'] = $this->data['to_address'] . ',' . $this->data['city'] . ',' . $this->data['state_name'] . ',' . $this->data['country'] . ',' . $address_site[0]->pincode;
		} else {
			$this->data['to_address'] = "";
		}
		//dd($id);
		$customer = \DB::table('m_customers_t')->where('customer_id', $header[0]->ship_to_customer_id)->get();
		//dd($customer);
		if ($customer->isNotEmpty()) {
			$this->data['customer_name'] = $customer[0]->customer_name;
		} else {
			$this->data['customer_name'] = '';
		}
		//dd($customer);

		//dd($header);
		if ($header) {
			$dt_format = \Session::get('php_dateformat');
			$header->inquiry_date = date($dt_format, strtotime($header[0]->inquiry_date));
			// $row->delivery_date=date($dt_format, strtotime($row->delivery_date));
			$this->data['header'] = $header;
			//dd($row);
		} else {
			$this->data['header'] = $this->model->getColumnTable('s_dispatch_hdr_t');

		}
		$totalqty = 0;
		$lines = \DB::table('s_dispatch_lines_t')->leftjoin('s_dispatched_qty_t', 's_dispatched_qty_t.so_dispatch_line_id', '=', 's_dispatch_lines_t.so_dispatch_line_id')->where('s_dispatched_qty_t.so_dispatch_hdr_id', $id)->get();

		//mpq_qty
//$lines=\DB::table('s_dispatch_lines_t')->where('s_dispatch_lines_t.so_dispatch_hdr_id',$id)->get();
//dd()
		foreach ($lines as $key => $val) {
			$lines[$key] = (object) array();
			//	dd($val);
			//$box=explode(",", $val->box_no);
			$arr = $this->getProduct($val->product_id);
			// foreach($box as $k=>$v){
			// $lines[$key]->box_nos[$k]=$v;	
			// }
			//dd($lines);issue_qoh
			$lines[$key]->issue_qoh = $val->issue_qoh;
			$lines[$key]->box_no = $val->box_no;
			$lines[$key]->batch_no = $val->batch_no;
			$lines[$key]->mpq_qty = $arr[0]->mpq_qty;
			$lines[$key]->product_id = $val->product_id;
			$lines[$key]->product = $arr[0]->concatenated_product;
			$lines[$key]->uom_code_id = $val->uom_code_id;
			//$lines[$key]->box_no = $val->box_no;
			$lines[$key]->uom_code = $arr[0]->uom_code;
			$lines[$key]->dispatch_qty = $val->dispatch_qty;
			$totalqty = $totalqty + $val->dispatch_qty;
			$lines[$key]->comments = $val->comments;
		}
		//dd($lines);

		/************************ date ******************************/

		$this->data['date'] = date('d/m/Y');

		/************************ End *******************************/

		/*********** company name with Address ************/

		$company_name = $this->getCompany($header[0]->company_id);
		$this->data['company_name'] = $company_name[0]->company_name;

		/********************* End ***********************/

		/*********** location based company address *******/

		/************************* location based addrss ************************/

		$address = $this->getLocationwiseaddress($header[0]->location_id);

		if ($address != 0) {
			$location_name = $address[0]->location_name;
			$this->data['location_name'] = $location_name;
			$this->data['address'] = $address[0]->address;
			$this->data['street_name'] = $address[0]->street_name;
			$this->data['location'] = $address[0]->location_name;
			$this->data['area'] = $address[0]->area;
			//GET COMPANY ADDRESS
			$this->data['company_gst_no'] = '';
			$this->data['pan_no'] = $address[0]->pan_no;
			$this->data['city'] = $this->getCity($address[0]->city_id);
			$this->data['loc_city'] = $this->getCity($address[0]->city_id);
			$this->data['state'] = $this->getState($address[0]->state_id);
			$this->data['state_no'] = $state = $this->data['state'][0]->state_id;
			$this->data['state_name'] = $state = $this->data['state'][0]->state_name;
			$this->data['state_code'] = $state = $this->data['state'][0]->state_code;
			$this->data['country'] = $this->getCountry($address[0]->country_id);
			$this->data['loc_country'] = $this->getCountry($address[0]->country_id);
			$this->data['gst_no'] = $address[0]->gst_no;
			$this->data['e_mail'] = $address[0]->e_mail;
			$this->data['state_code'] = $this->data['state'][0]->state_code;
			$this->data['pincode'] = $address[0]->pincode;
			$this->data['phone'] = $address[0]->Phone;
			$this->data['web'] = $address[0]->Web;
			$this->data['cin'] = $address[0]->CIN;
		}

		$this->data['company_address'] = $this->data['address'] . "," . $this->data['street_name'] . "," . $this->data['city'] . "," . $this->data['state_name'] . "," . $this->data['country'] . "," . $this->data['pincode'];

		$this->data['company_address'] = ucwords($this->data['company_address']);

		if ($address == 0) {
			array_push($l_error, "Check Organisation or company Details");
		}

		/********************* End ************************/

		/************************* Customer ************************************/

		$customer = $this->getCustomer($header[0]->ship_to_customer_id);
		if (!empty($customer)) {
			$this->data['customer_name'] = $customer[0]->customer_name;
			$this->data['cus_gst_no'] = '';
		} else {
			$this->data['customer_name'] = '';
			$this->data['cus_gst_no'] = '';
		}

		/*************************** end ***************************************/
		$this->data['company_logo'] = \Session::get('companylogo');
		/************************** bill  and ship to address  ****************/
		if ($header[0]->dispatch_source != "INVOICE") {
			$address_ship_bill = \DB::select("select m_customer_sites_t.* from s_invoice_hdr_t left join m_customer_sites_t on m_customer_sites_t.customer_site_id=s_invoice_hdr_t.bill_to_address_id where s_invoice_hdr_t.reference_source_id=" . $header[0]->so_dispatch_hdr_id . " and s_invoice_hdr_t.source='DISPATCH'");

		} else {

			$address_ship_bill = \DB::select("select m_customer_sites_t.* from s_dispatch_hdr_t left join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=s_dispatch_hdr_t.reference_source_id left join m_customer_sites_t on m_customer_sites_t.customer_site_id=s_invoice_hdr_t.bill_to_address_id where s_dispatch_hdr_t.so_dispatch_hdr_id=" . $header[0]->so_dispatch_hdr_id . " and s_dispatch_hdr_t.dispatch_source='INVOICE'");
		}

		if (count($address_ship_bill) > 0) {
			if ($address_ship_bill[0]->customer_site_id != null) {


				$this->data['address'] = $address_ship_bill[0]->address;

				$this->data['city'] = $this->getCity($address_ship_bill[0]->city);
				$this->data['bill_city'] = $this->getCity($address_ship_bill[0]->city);

				//$this->data['city_bill']=$this->data['city'][0]->city_name;
				$this->data['state'] = $this->getState($address_ship_bill[0]->state);
				$this->data['state_id_bill'] = $this->data['state'][0]->state_id;
				$this->data['state_name_ship'] = $this->data['state'][0]->state_name;
				$this->data['state_code_bill'] = $this->data['state'][0]->state_code;
				$this->data['country'] = $this->getCountry($address_ship_bill[0]->country);
				$this->data['bill_country'] = $this->getCountry($address_ship_bill[0]->country);
				//$this->data['country_bill']=$this->data['country'][0]->country_name;
				$this->data['pincode'] = $address_ship_bill[0]->pincode;
				$this->data['contact_number'] = $address_ship_bill[0]->contact_number;
				$this->data['bill_gst_no'] = $address_ship_bill[0]->gst_no;

				$this->data['bill_to_address_1'] = $this->data['address'] . "," . $this->data['city'] . "," . $this->data['state_name_ship'] . "," . $this->data['country'] . "," . $this->data['pincode'];

			} else {
				$this->data['address'] = "";
				$this->data['city'] = "";
				$this->data['bill_city'] = "";
				$this->data['state'] = "";
				$this->data['state_id_bill'] = "";
				$this->data['state_name_ship'] = "";
				$this->data['state_code_bill'] = "";
				$this->data['country'] = "";
				$this->data['bill_country'] = "";
				$this->data['pincode'] = "";
				$this->data['contact_number'] = "";
				$this->data['bill_gst_no'] = "";
				$this->data['bill_to_address_1'] = '';
			}
		} else {
			$this->data['address'] = "";
			$this->data['city'] = "";
			$this->data['bill_city'] = "";
			$this->data['state'] = "";
			$this->data['state_id_bill'] = "";
			$this->data['state_name_ship'] = "";
			$this->data['state_code_bill'] = "";
			$this->data['country'] = "";
			$this->data['bill_country'] = "";
			$this->data['pincode'] = "";
			$this->data['contact_number'] = "";
			$this->data['bill_gst_no'] = "";
			$this->data['bill_to_address_1'] = '';
		}
		$this->data['ship_to_address_1'] = '';
		$this->data['ship_country'] = '';
		$this->data['ship_city'] = '';
		/*************************** End *********************************/

		$this->data['lines'] = $lines;
		$this->data['totalitem'] = count($lines);
		$this->data['result'] = $lines;
		$this->data['totalqty'] = $totalqty;
		$this->data['print'] = "PRINT";
		$this->data['print_val'] = '1';
		return view('dispatch.packslip_print', $this->data);

	}

	function getFreight($id)
	{
		$sql = \DB::SELECT("select * from m_frieghtcarriers_hdr_t where ar_frieghtcarriers_hdr_id='" . $id . "'");
		if (!empty($sql))
			return $sql[0]->carrier_name;
		else
			return '';
	}

	public function dispatchprint($id)
	{

		require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
		
		$this->data['dispatch_hdr_id'] = $id;
		$lines = Dispatchlines::where('so_dispatch_hdr_id',$id)->get();
		$row = Dispatch::where('so_dispatch_hdr_id',$id)->get();
		//dd($id);

		$sample='';
			if($row[0]->dispatch_source == "SALES ORDER"){
				$dt = \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$row[0]->reference_source_id)->select('order_type_id')->get();
				if(count($dt) > 0 ){
						$this->data['order_type_id'] = $dt[0]->order_type_id;
					if($dt[0]->order_type_id == "SAMPLE"){
						$sample = "Yes";
					}
				}
			}else if($row[0]->dispatch_source == "INVOICE"){
				$invoice = \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id',$row[0]->reference_source_id)->Where('source','SALES ORDER')->get();
				$dt = \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$invoice[0]->reference_source_id)->select('order_type_id')->get();
				if(count($dt) > 0 ){
					if($dt[0]->order_type_id == "SAMPLE"){
						$sample = "Yes";
					}
				}
				
			}

			$this->data['sample']=  $sample;
			
			$pan = \DB::table('hr_emp_personal')->where('employee_id',$row[0]->employee_id)->get();
			if($pan->isNotEmpty())
		{
		    $this->data['pan_number'] 				  = $pan[0]->pan_number;
		}
		else{
		    $this->data['pan_number'] 				  = "";
		}
			
		 $comp = \DB::table('m_company_t')->where('company_id',$row[0]->company_id)->get();
		if($comp->isNotEmpty())
		{
		$this->data['gstno'] 				  = $comp[0]->gst_no;
		$this->data['panno'] 				  = $comp[0]->pan_no;
		$this->data['email_id'] 			  = $comp[0]->email_id;
		$this->data['website_address'] 		  = $comp[0]->website_address;
		$this->data['cin_no'] 				  = $comp[0]->cin_no;
		$this->data['excise_registration_no'] = $comp[0]->excise_registration_no;
		$this->data['tax_reg_no'] 			  = $comp[0]->tax_reg_no;
		
		}
		else
		{
		$this->data['gst_no'] 				  = "";
		$this->data['pan_no'] 				  = "";
		$this->data['email_id'] 			  = "";
		$this->data['cin_no'] 				  = "";
		$this->data['excise_registration_no'] = "";
		$this->data['tax_reg_no'] 			  = "";
		}
		$location=$this->getLocationwiseaddress();
		//d($location);
		$this->data['country'] = $this->getCountry($location[0]->country_id);
		$this->data['state'] = $this->getState($location[0]->state_id);
		$this->data['state_name']=$this->data['state'][0]->state_name;
		$this->data['loc_state_code']=$this->data['state'][0]->state_code;
		$this->data['state_code_no']=$this->data['state'][0]->state_code_no;
		$billaddress=$this->getCustomer($row[0]->ship_to_customer_id);
		if($billaddress!=0){
		$sup_address=$this->getCustomersite($billaddress[0]->customer_id);
		$dest=\DB::SELECT('select * from m_location_t where location_id='.$sup_address[0]->location_id.'');
		$this->data['destination'] = $dest[0]->location_name;
		$this->data['bcustomer']=$billaddress[0]->customer_name;
		$this->data['bcustomer_gst']=$sup_address[0]->gst_no;
		$this->data['employee_name']="";
		}else{
		$sup_address="";	
		$this->data['bcustomer']='';
		$this->data['bcustomer_gst']='';
		$this->data['destination']='';
		$emp=\DB::select("select concat(first_name,COALESCE(last_name,'')) as empname from hr_employee_t where employee_id=".$row[0]->employee_id);
		$this->data['employee_name']=$emp[0]->empname;
		}
		$location=\Session::get('location');
		$comp=\DB::SELECT('select * from m_location_t where location_id='.$location.'');
		
		$prepared_by = User::where('id',$row[0]->created_by)->pluck('first_name')->first();
		$this->data['prepared_by'] = $prepared_by;

		    $cus_id=$row[0]->ship_to_customer_id;
			$customer=$this->getCustomer($row[0]->ship_to_customer_id);
			if(!empty($customer)){
				$this->data['customer_name']=$customer[0]->customer_name;
				$this->data['cus_gst_no']="";
			}
			else
			{
				$this->data['customer_name']='';
				$this->data['cus_gst_no']='';
			}
	    if($row[0]->reference_source_id !=0)
	    {
			if($row[0]->dispatch_source =="SALES ORDER")
			{

			$sql1=\DB::table("s_salesorder_hdr_t")->where('sales_hdr_id',$row[0]->reference_source_id)->get();
				if($sql1->isNotEmpty())
				{
					$this->data['source_number']  =$sql1[0]->sales_order_no;
         			$this->data['source_date']    =$sql1[0]->sales_order_date;
         			$this->data['customer_po_number']    =$sql1[0]->customer_po_number;
					$deliveryterm =Deliveryterms::where('delivery_terms_id',$sql1[0]->ar_delivery_terms_id)->pluck('delivery_term_name')->first();
					$this->data['deliveryterm'] = $deliveryterm;

				}
				else
				{
					$this->data['source_number'] ='';
         			$this->data['source_date']   ='';
         			$this->data['customer_po_number']   ='';
					$this->data['deliveryterm']    ="";
				}
			}

			if($row[0]->dispatch_source =="INVOICE")
			{

			$sql1=\DB::table("s_invoice_hdr_t")->where('invoice_hdr_id',$row[0]->reference_source_id)->get();
				if($sql1->isNotEmpty())
				{
					$this->data['source_number']  =$sql1[0]->invoice_number;
         			$this->data['source_date']    =$sql1[0]->invoice_date;
         			$this->data['customer_po_number']  =$sql1[0]->reference_number;
					$deliveryterm =Deliveryterms::where('delivery_terms_id',$sql1[0]->delivery_term_id)->pluck('delivery_term_name')->first();
					$this->data['deliveryterm'] = $deliveryterm;
				}
				else
				{
					$this->data['source_number'] ='';
         			$this->data['source_date']   ='';
         			$this->data['customer_po_number']   ='';
					$this->data['deliveryterm']    ="";
				}
			}

				if($row[0]->dispatch_source =="PICK ORDER")
			{

			$sql1=\DB::table("s_pickrelease_hdr_t")->where('so_pickrelease_hdr_id',$row[0]->reference_source_id)->get();

				if($sql1->isNotEmpty())
				{
					$this->data['source_number']  =$sql1[0]->release_source;
         			$this->data['source_date']    =$sql1[0]->release_date;
         			$this->data['customer_po_number']  ='';
					$deliveryterm =Deliveryterms::where('delivery_terms_id',$sql1[0]->deliver_to_location)->pluck('delivery_term_name')->first();
					$this->data['deliveryterm'] = $deliveryterm;
				}
				else
				{
					$this->data['source_number'] ='';
         			$this->data['source_date']   ='';
         			$this->data['customer_po_number']   ='';
					$this->data['deliveryterm']    ="";
				}
			}

	    }
		else
		{
		 $this->data['source_number'] 		 ='';
         $this->data['source_date']  		 ='';
         $this->data['customer_po_number']   ='';
		 $this->data['deliveryterm']    	 ="";
		}

		if($row[0]->ship_to_customer_id!="0"){
				$customer_bill=\DB::table('m_customer_sites_t')->where('customer_site_id',$row[0]->deliver_to_location)->get();
				$bill_to_ship_address=$customer_bill;
		}else{
			$bill_to_ship_address=array();
		}

		$this->data['despatch_through'] = $this->getFreight($row[0]->freight_carrier_id);
		$this->data['dispatch_number'] = $row[0]->dispatch_number;
		$this->data['dispatch_date'] = $row[0]->dispatch_date;
		$this->data['packaging_qty'] = $row[0]->packaging_qty;
		$this->data['pack_weight'] = $row[0]->pack_weight;
		$this->data['remarks'] = $row[0]->remarks;
		
		$comp = \DB::table('m_company_t')->where('company_id',$row[0]->company_id)->get();
		if($comp->isNotEmpty())
		{
		$this->data['gst_no'] 				  = $comp[0]->gst_no;
		$this->data['pan_no'] 				  = $comp[0]->pan_no;
		$this->data['email_id'] 			  = $comp[0]->email_id;
		$this->data['cin_no'] 				  = $comp[0]->cin_no;
		$this->data['excise_registration_no'] = $comp[0]->excise_registration_no;
		$this->data['tax_reg_no'] 			  = $comp[0]->tax_reg_no;
		$this->data['website_address'] 			  = $comp[0]->website_address;
		}
		else
		{
		$this->data['gst_no'] 				  = "";
		$this->data['pan_no'] 				  = "";
		$this->data['email_id'] 			  = "";
		$this->data['cin_no'] 				  = "";
		$this->data['excise_registration_no'] = "";
		$this->data['tax_reg_no'] 			  = "";
		$this->data['website_address'] 			  = "";
		}

		$address=$this->getLocationwiseaddress($row[0]->location_id);
        
		if(!empty($address))
		{
			$location_name=$address[0]->location_name;
			$this->data['location_name_l']= $location_name;

			$address1=ucwords(strtolower($address[0]->address));
			$this->data['address_l']= ucwords(strtolower($address1));
			$street =ucwords(strtolower($address[0]->street_name));
			$this->data['street_name_l']= $street;
			$location=ucwords(strtolower($address[0]->location_name));
			$this->data['location_l']= $location;
			$this->data['pincode']= $address[0]->pincode;
			
			$area=ucwords(strtolower($address[0]->area));
			$this->data['area_l']= $area;
			//GET COMPANY ADDRESS
			$this->data['company_gst_no_l']= $address[0]->gst_no;
			$city_l=$this->data['city_l']=$this->getCity($address[0]->city_id);

			$city=$city_l;
			$state_l=$this->data['state_l']=$this->getState($address[0]->state_id);
			$state=$state_l[0]->state_name;
			$this->data['state_code']=$state_l[0]->state_code;
			$this->data['state_name']=ucwords(strtolower($state_l[0]->state_name));
			$country_l=$this->data['country_l']=ucwords(strtolower($this->getCountry($address[0]->country_id)));
			$country=$country_l;

		}
			$this->data['company_address'] = $address1.",".$city.",".$state.",".$country." - ".$this->data['pincode'];

        $company=$this->getCompany();
        if(!empty($company))
        {
        $company_name=$company[0]->company_name;
        $company_logo_name=$company[0]->company_logo_name;
        $company_contact_no=$company[0]->contact_no;
        $this->data['company_name']= $company_name;
        $this->data['company_logo_name']= $company_logo_name;
        $this->data['company_contact_no']= $company_contact_no;
        }

        /******************** end ************************/
		$emp_address=\DB::table('hr_emp_contact')->where('employee_id',$row[0]->employee_id)->get();
		
		if(count($bill_to_ship_address)>0){
			foreach($bill_to_ship_address as $key=>$value){
        

                $this->data['address']=$value->address;
                $this->data['city']=$this->getCity($value->city);
                $states=$this->getState($value->state);
				if($states !=0)
				{
				$this->data['state_id_ship']=$states[0]->state_code;
                $this->data['state_name_ship']=$states[0]->state_name;
                $this->data['state_code_bill']=$states[0]->state_code;
				}
				else
				{
				$this->data['state_id_ship']=$states;
                $this->data['state_name_ship']=$states;
                $this->data['state_code_bill']=$states;
				}
				if($bill_to_ship_address[0]->location_id!=0){
				$dest=\DB::SELECT('select * from m_location_t where location_id='.$bill_to_ship_address[0]->location_id.'');
				 $this->data['destination'] = $this->data['city'];
				}
				else{
					 $this->data['destination'] = "";
				}
                $this->data['country']=$this->getCountry($value->country);
                $this->data['pincode']=$value->pincode;
                $this->data['contact_number']=$value->contact_number;
                $this->data['alternative_contact_number']="";
                $this->data['ship_gst_no']=$value->gst_no;

                $this->data['ship_to_address_1']=$this->data['address'].",".$this->data['city'].",".$this->data['state_name_ship'].",".$this->data['country'].",".$this->data['pincode'];

            }
		
			}else if(count($emp_address)>0){
				// dd($emp_address);
				 $this->data['address']=$emp_address[0]->current_street_address;
				  $this->data['street']=$emp_address[0]->current_street;
				  if($emp_address[0]->current_flat_no!=""){
				  	$this->data['flat']=$emp_address[0]->current_flat_no.",";
				  }else{
				  	$this->data['flat']="";
				  }
				 
                $this->data['city']=$this->getCity($emp_address[0]->current_city);
                 $this->data['destination'] = $emp_address[0]->current_locality;
                $states=$this->getState($emp_address[0]->current_state);
                if($states !=0)
                {
                $this->data['state_id_ship']=$states[0]->state_code_no;
                $this->data['state_name_ship']=$states[0]->state_name;
                $this->data['state_code_bill']=$states[0]->state_code;
                }
                else
                {
                $this->data['state_id_ship']=$states;
                $this->data['state_name_ship']=$states;
                $this->data['state_code_bill']=$states; 
                }
                $this->data['country']=$this->getCountry($emp_address[0]->current_country);
                $this->data['pincode']=$emp_address[0]->current_postal_code;
				$empmobile=\DB::select('select * from hr_employee_t where employee_id='.$row[0]->employee_id);
				//dd($empmobile);
				$this->data['alternative_contact_number']=$empmobile[0]->alternative_telephone_number;
                $this->data['contact_number']=$empmobile[0]->work_telephone_number;
                 $this->data['contact_person']=$empmobile[0]->first_name;
                $this->data['ship_gst_no']="";
                
                 $this->data['ship_to_address_1']=$this->data['flat']."".$this->data['address'].",".$this->data['street'].",".$this->data['city'].",".$this->data['state_name_ship'].",".$this->data['country'].",".$this->data['pincode'];
// dd($this->data['ship_to_address_1']);
			}
	 else
	 {
	 	    $this->data['alternative_contact_number']=""; 
		 	$this->data['address']="";
			$this->data['city']="";
			//$this->data['city_bill']=$this->data['city'][0]->city_name;
			$this->data['state']="";
			$this->data['state_id_bill']="";
			$this->data['bill_name_ship']="";
			$this->data['state_code_bill']="";
			$this->data['country']="";
			//$this->data['country_bill']=$this->data['country'][0]->country_name;
			$this->data['pincode']="";
			$this->data['contact_number']="";

			$this->data['ship_gst_no']="";

			$this->data['ship_to_address_1']="";
				 $this->data['destination'] = "";
	 }
		$pdt = Product::get();
    	$uom = Uomcodes::get();
		$k=0;
		$dispatchlines =  array();
//dd($lines);
		foreach($lines as $key=>$value)
		{

			$disped = \DB::table('s_dispatched_qty_t')->where('so_dispatch_line_id',$value->so_dispatch_line_id)->groupBy('so_dispatch_line_id')->groupBy('batch_no')->groupBy('s_dispatched_qty_id')->get();
			// dd($value->so_dispatch_line_id);
			if($disped->isNotEmpty())
			{

				foreach($disped as $key1=>$val)
				{
					//dd($disped);
					$dispatchlines[$k] = (object) array();
					$dispatchlines[$k]->box_no = $val->box_no;
					$dispatchlines[$k]->kit_pack_no = $val->kit_pack_no;
					$dispatchlines[$k]->batch_no = $val->batch_no;
					$dispatchlines[$k]->manufacture_date = $val->manufacture_date;
					$expdate=explode('-',$val->expiry_date);
					$month=$expdate[1];
					$year=$expdate[0];
				    $expdate1=$month."/".$year;
					$dispatchlines[$k]->expiry_date = $expdate1;
					$dispatchlines[$k]->product_id = $value->product_id;
					$dispatchlines[$k]->product = $pdt->where('product_id',$value->product_id)->pluck('concatenated_product')->first();
					$dispatchlines[$k]->uom = $uom->where('uom_code_id',$value->uom_code_id)->pluck('uom_code')->first();
					$arr=$this->getProduct($value->product_id);
					if($arr['group_name']=="PROMOTIONAL ITEMS"){
					$dispatchlines[$k]->manufacture_date ="";
                    $dispatchlines[$k]->expiry_date="";					
					}
					$dispatchlines[$k]->hsn_code = $arr['hsn_code'];
					$dispatchlines[$k]->qty = $val->issue_qoh;
					if($val->issue_qoh!=0)
						$k++;
					else
						unset($dispatchlines[$k]);
				}

			}
		}

		$this->data['company_logo'] = \Session::get('companylogo');
		$this->data['linesdata']=$dispatchlines;

		  if(isset($_GET['mail']))
            {
            	if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
        Config::set('mail.username', \Session::get('user_email'));
        Config::set('mail.password', \Session::get('user_password'));
    }	

                $this->data['print']="PRINTS";



                \Mail::send('dispatch.printform',$this->data, function($message)
                {
            	  if(!empty($_GET['cc'])){

                  $cc=explode(',',$_GET['cc']);
                  $message->cc($cc);
              }else{
                 $cc=array();
              }

              		$msg=$_GET['msg'];


                      $message->to(explode(",",$_GET['mail']));
                    $message->subject("Dispatch". $this->data['dispatch_number']);

                     $message->setBody($msg);
                    //$message->from('Saipavan9010@gmail.com');
                    if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                   	$return=DB::table('s_dispatch_hdr_t')->where('so_dispatch_hdr_id',$this->data['dispatch_hdr_id'])->get();
                   	$message->attach('uploads/dispatch/D_'.stripslashes($this->data['dispatch_number']).'.pdf');
			if($return[0]->attachment_file!=''){
				$file_a=json_decode($return[0]->attachment_file);

				foreach($file_a as $k1=>$v1){

				 $message->attach('uploads/dispatch/D'.$this->data['dispatch_hdr_id'].'/'.$v1);
				}
			}
                    

                });
                            return 1;


            }
            $this->data['print']="PRINT";
        $this->data['print_val'] = '1';

		if(isset($_GET['mails'])){

          $this->data['print']="PRINTS";
          // dd($this->data);
			 return view('dispatch.printform', $this->data);
		 }
		//  dd($this->data['linesdata']);
		return view('dispatch.printform',$this->data);
	
	}

	public function dispatchfilesave(Request $request)
	{

		if ($request->hasfile('email_attachment')) {
			File::deleteDirectory(public_path('uploads/dispatch/D' . $_POST['dispatch_hdr_id']));

			foreach ($request->file('email_attachment') as $file) {
				$name = $file->getClientOriginalName();

				$file->move(public_path() . '/uploads/dispatch/D' . $_POST['dispatch_hdr_id'] . '/', $name);
				$data[] = $name;
			}
			$attachfile_name = json_encode($data);
			\DB::update("update s_dispatch_hdr_t set attachment_file='" . $attachfile_name . "' where so_dispatch_hdr_id=" . $_POST['dispatch_hdr_id']);
			return 1;
		} else {
			$var = File::deleteDirectory(public_path('uploads/dispatch/D' . $_POST['dispatch_hdr_id']));

			\DB::update("update s_dispatch_hdr_t set attachment_file='' where so_dispatch_hdr_id=" . $_POST['dispatch_hdr_id']);
			return 2;
		}

	}
	function getLocationwiseaddress($location = null)
	{
		$sql = array();
		$location = \Session::get('location');
		$sql = \DB::SELECT("select * from m_location_t where location_id=" . $location . "");
		if (!empty($sql)) {
			return $sql;
		}
		return 0;
	}

	function getBill_to_address($bill_to_id = null)
	{

		$bill_to_address = array();
		//$bill_to_address=\DB::select("select * from m_customer_sites_t where customer_id=".$bill_to_id." ");
		$bill_to_address = \DB::select("SELECT * FROM m_customer_sites_t WHERE customer_id=" . $bill_to_id . " and customer_site_number!=''");

		if (!empty($bill_to_address)) {
			return $bill_to_address;
		} else {
			return 0;
		}
	}

	function getCustomer($id = null)
	{

		$sql = array();
		$sql = \DB::SELECT('select * from m_customers_t where customer_id=' . $id . '');

		if (!empty($sql)) {
			return $sql;
		} else {
			return 0;
		}

	}
	function getCustomersite($id = null)
	{

		$sql = array();
		$sql = \DB::SELECT('select * from m_customer_sites_t where customer_id=' . $id . '');
		if (!empty($sql)) {
			return $sql;
		} else {
			return 0;
		}
	}

	function getCity($id = null)
	{

		$sql = array();

		$sql = \DB::SELECT('select city_id,city_name from m_cities_t where city_id=' . $id . '');

		if (!empty($sql)) {
			return $sql[0]->city_name;
		} else {

			return 0;
		}

	}
	function getState($id = null)
	{
		$sql = array();

		$sql = \DB::SELECT('select state_id,state_name,state_code,state_code_no from m_states_t where state_id=' . $id . '');
		if (!empty($sql)) {
			return $sql;

		} else {
			return 0;
		}

	}
	function getCountry($id = null)
	{
		$sql = array();
		$sql = \DB::SELECT('select country_id,country_name from m_countries_t where country_id =' . $id . '');
		if (!empty($sql)) {
			return $sql[0]->country_name;
		} else {
			return 0;
		}
	}

	function getCompany()
	{

		$location = Session::get('companyid');
		$company = \DB::SELECT("select company_id,company_name,company_logo_name,contact_no from m_company_t where company_id='$location'");

		if (!empty($company)) {
			return $company;
		} else {
			return 0;
		}
	}



	function getProduct($id = null)
	{
		$product = array();
		$product = \DB::table('m_products_t as pdt')
			->leftJoin('m_uom_codes_t as uom', 'uom.uom_code_id', '=', 'pdt.trx_uom_id')
			->leftJoin('f_gst_code_hdr_t as hsn', 'hsn.gst_code_hdr_id', '=', 'pdt.hsn_code')
			->leftJoin('m_product_groups_t as prdgrp', 'prdgrp.product_group_id', '=', 'pdt.product_group_id')
			->select('uom.uom_code', 'pdt.concatenated_product', 'pdt.hsn_code', 'hsn.classification_code', 'prdgrp.group_name', 'pdt.mpq_qty')
			->where('pdt.product_id', $id)->get();
		//dd($product);
		if ($product->isNotEmpty()) {

			$date = date('Y-m-d');
			$tax = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='" . $product[0]->hsn_code . "' and start_date<='$date' and end_date>='$date' and active='Yes'");

			if (!empty($tax)) {
				$product['tax_group_id'] = $tax[0]->tax_group_id;
			} else {
				$product['tax_group_id'] = 0;
			}
			$product['concat_segment'] = $product[0]->concatenated_product;
			$product['primary_uom_code'] = $product[0]->uom_code;
			$product['group_name'] = $product[0]->group_name;
			$product['hsn_code'] = $product[0]->classification_code;
			return $product;

		} else {
			return 0;
		}

	}

	public function dispatchqty($prdid = null, $soid = null)
	{
		$issue_qty = [];

		$company_id = \Session::get('companyid');
		if ($_GET['source'] != "REPLACEMENT") {
			$sql = \DB::select('SELECT s_salesorder_hdr_t.sales_hdr_id,s_salesorder_lines_t.sales_line_id,s_salesorder_lines_t.pending_qty,s_salesorder_lines_t.qty,s_salesorder_lines_t.dispatched_qty,s_salesorder_lines_t.invoiced_qty,s_salesorder_lines_t.free_qty,s_salesorder_lines_t.product_id, m_products_t.concatenated_product,s_salesorder_hdr_t.sales_order_no from s_salesorder_hdr_t left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id = s_salesorder_hdr_t.sales_hdr_id) left join m_products_t on m_products_t.product_id= s_salesorder_lines_t.product_id  where s_salesorder_hdr_t.sales_hdr_id in(' . $soid . ') and s_salesorder_lines_t.pending_qty != 0   and s_salesorder_lines_t.product_id=' . $prdid);
		} else {
			$sql = \DB::select('SELECT s_replacement_hdr_t.replacement_hdr_id,s_replacement_lines_t.replacement_line_id,s_replacement_lines_t.pending_qty,s_replacement_lines_t.qty,s_replacement_lines_t.dispatched_qty,s_replacement_lines_t.invoice_qty,s_replacement_lines_t.replacement,s_replacement_lines_t.product_id, m_products_t.concatenated_product,s_replacement_hdr_t.replacement_number from s_replacement_hdr_t left join s_replacement_lines_t on(s_replacement_lines_t.replacement_hdr_id = s_replacement_hdr_t.replacement_hdr_id) left join m_products_t on m_products_t.product_id= s_replacement_lines_t.product_id  where s_replacement_hdr_t.replacement_hdr_id in(' . $soid . ')   and s_replacement_lines_t.product_id=' . $prdid);

		}

		$subctgy = \DB::select("select m_products_t.product_subcategory_id as subctg from m_products_t LEFT JOIN i_qoh_detail_t on i_qoh_detail_t.product_id=m_products_t.product_id WHERE i_qoh_detail_t.product_id='$prdid'");

		if ($subctgy = 2) {
			$qoh_query = \DB::select("select * from (select sum(qoh_trx_qty) as qty FROM i_qoh_detail_t where product_id='$prdid' and i_qoh_detail_t.company_id='$company_id' and (i_qoh_detail_t.qualitystatus=1 or i_qoh_detail_t.qualitystatus=0) and (subinventory_id=3 or subinventory_id = 4) group by subinventory_id ) as qoh_q where qty > 0 ");
		} elseif ($subctgy = 76) {

			$qoh_query = \DB::select("select * from (select sum(qoh_trx_qty) as qty FROM i_qoh_detail_t where product_id='$prdid' and i_qoh_detail_t.company_id='$company_id' and (i_qoh_detail_t.qualitystatus=1 or i_qoh_detail_t.qualitystatus=0) and (subinventory_id=4 or subinventory_id =3) group by subinventory_id ) as qoh_q where qty > 0 ");
		}
		//dd(count($qoh_query));
		if (count($qoh_query) > 0) {
			//dd($qoh_query[0]->qty);
			if ($qoh_query[0]->qty > 0) {
				$qty = $qoh_query[0]->qty;
			} else {
				$qty = 0;
			}
		} else {
			$qty = 0;
		}
		$html1 = '';

		$html1 .= '<div id="preview-area" class="table-responsive"><table class="table table-bordered clone_table2"><thead class="table-light"><tr><th>Line No</th><th>Sales Order</th><th>Qty</th><th>Free Qty</th><th>Dispatched Qty</th><th>Invoice Qty</th><th>QOH</th><th>Dispatch Qty</th><th>Dispatch Free Qty</th><th></th></tr></thead><tbody>';
		$dispqty = 0;
		foreach ($sql as $k => $v) {
			if (isset($_GET['source'])) {
				if ($_GET['source'] == "INVOICE") {
					$dispqty = $v->invoiced_qty;
				} else if ($_GET['source'] == "SALES ORDER") {
					$dispqty = $v->qty;
				} else if ($_GET['source'] == "REPLACEMENT") {
					$dispqty = $v->replacement;
				}
			}
			if ($_GET['source'] != "REPLACEMENT") {
				$html1 .= '<tr>
				<td>
					<input type="text"  class="form-control input-sm line_no" value="' . ($k + 1) . '" readonly="readonly" style="width:68px !important;">
				</td>
				<td>
					<input type="hidden"  name="sales_hdr_id" class="form-control sales_hdr_id input_qty_width" value="' . $v->sales_hdr_id . '" style="width:100px !important;">
					<input type="hidden"  name="sales_line_id" class="form-control sales_line_id input_qty_width" value="' . $v->sales_line_id . '" style="width:100px !important;">
					<input type="text"  name="sales_order_no" class="form-control input-sm sales_order_no input_qty_width" readonly value="' . $v->sales_order_no . '" style="width:100px !important;">
				</td>
				<td>
					<input type="text"  name="qty" class="form-control qty qty' . $k . ' input_qty_width" readonly value="' . $v->qty . '" style="width:115px !important;">
				</td>
				<td>
					<input type="text"  name="so_free_qty" class="form-control so_free_qty so_free_qty' . $k . ' input_qty_width" readonly value="' . $v->free_qty . '" style="width:115px !important;">
				</td>
				<td>
					<input type="text"  name="" class="form-control so_dis_qty so_dis_qty' . $k . ' input_qty_width" readonly value="' . $v->dispatched_qty . '" style="width:100px !important;">
				</td>
								<td>
					<input type="text"  name="" class="form-control so_invoice_qty so_invoice_qty' . $k . ' input_qty_width" readonly value="' . $v->invoiced_qty . '" style="width:100px !important;">
				</td>
				<td>
					<input type="text"  name="" class="form-control qoh_qty qoh_qty' . $k . ' input_qty_width" readonly value="' . number_format((float) $qty, \Session::get('decimal'), '.', '') . '" style="width:100px !important;">
				</td>
			
				<td>
					<input type="text"  name="dis_issue_qty" class="form-control dis_issue_qty dis_issue_qty' . $k . ' input_qty_width" value="' . $dispqty . '" style="width:100px !important;">
				</td>
					<td>
					<input type="text"  name="free_qty" class="form-control free_qty free_qty' . $k . ' input_qty_width" value="' . $v->free_qty . '" style="width:100px !important;">
				</td>
				<td><a class="popremove popremove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a></td>
				</tr>';
			} else {
				$html1 .= '<tr>
				<td>
					<input type="text"  class="form-control input-sm line_no" value="' . ($k + 1) . '" readonly="readonly" style="width:68px !important;">
				</td>
				<td>
					<input type="hidden"  name="sales_hdr_id" class="form-control sales_hdr_id input_qty_width" value="' . $v->replacement_hdr_id . '" style="width:100px !important;">
					<input type="hidden"  name="sales_line_id" class="form-control sales_line_id input_qty_width" value="' . $v->replacement_line_id . '" style="width:100px !important;">
					<input type="text"  name="sales_order_no" class="form-control input-sm sales_order_no input_qty_width" readonly value="' . $v->replacement_number . '" style="width:100px !important;">
				</td>
				<td>
					<input type="text"  name="qty" class="form-control qty qty' . $k . ' input_qty_width" readonly value="' . $v->replacement . '" style="width:115px !important;">
				</td>
				<td>
					<input type="text"  name="" class="form-control so_dis_qty so_dis_qty' . $k . ' input_qty_width" readonly value="' . $v->dispatched_qty . '" style="width:100px !important;">
				</td>
								<td>
					<input type="text"  name="" class="form-control so_invoice_qty so_invoice_qty' . $k . ' input_qty_width" readonly value="' . $v->invoice_qty . '" style="width:100px !important;">
				</td>
				<td>
					<input type="text"  name="" class="form-control qoh_qty qoh_qty' . $k . ' input_qty_width" readonly value="' . number_format((float) $qty, \Session::get('decimal'), '.', '') . '" style="width:100px !important;">
				</td>
				<td>
					<input type="text"  name="dis_issue_qty" class="form-control dis_issue_qty dis_issue_qty' . $k . ' input_qty_width" value="' . $dispqty . '" style="width:100px !important;">
				</td>
				        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-row2">
            <i class="fas fa-minus-circle"></i>
          </button>
        </td>
				</tr>';
			}
		}
		$html1 .= '</tbody></table>
			<button class="qtyok btn btn-success" >Add Dispatch Qty</button>
			</div>';
		return $html1;
	}


	public function Dispatchlines($pid = null, $qty = null)
	{

		$issue_qty = [];
		$soqty = $_GET['qty'];
		$free_qty = $_GET['free_qty'];

		$company_id = \Session::get('companyid');
		$prdsql = \DB::select("select concat(product_code,'-',concatenated_product) as prdname from m_products_t where product_id=" . $pid);

		$subctgy = \DB::select("select m_products_t.product_subcategory_id as subctg from m_products_t LEFT JOIN i_qoh_detail_t on i_qoh_detail_t.product_id=m_products_t.product_id WHERE i_qoh_detail_t.product_id='$pid'");

		if ($subctgy = 2) {
			$sql = \DB::select("select sum(qoh_trx_qty) as qty,batch_number,locator_id,subinventory_id,manufacturer_date,product_expire_date,product_id,qoh_detail_id FROM i_qoh_detail_t where product_id='$pid' and i_qoh_detail_t.company_id='$company_id' and (i_qoh_detail_t.subinventory_id ='3' or i_qoh_detail_t.subinventory_id ='4') and (i_qoh_detail_t.qualitystatus=1 or i_qoh_detail_t.qualitystatus=0) GROUP by batch_number, subinventory_id");
		} elseif ($subctgy = 76) {
			$sql = \DB::select("select sum(qoh_trx_qty) as qty,batch_number,locator_id,subinventory_id,manufacturer_date,product_expire_date,product_id,qoh_detail_id FROM i_qoh_detail_t where product_id='$pid' and i_qoh_detail_t.company_id='$company_id' and (i_qoh_detail_t.subinventory_id ='4' or i_qoh_detail_t.subinventory_id ='3') and (i_qoh_detail_t.qualitystatus=1 or i_qoh_detail_t.qualitystatus=0) GROUP by batch_number, subinventory_id");

		}

		$dispqtyid = $_GET['dispid'];

		if ($qty != 0) {
			$issue_qty = explode(",", $qty);
		}
		$html = '';
		$html .= '
<div id="preview-area" class="table-responsive">

  <div class="card shadow-sm border-0 rounded-4 p-3 mb-3 bg-light">
    <div class="row align-items-center g-2">
      <div class="col-md-6 col-12">
        <label class="text-muted small mb-0">Product Name</label>
        <div class="fw-semibold text-dark h6 mb-0 productname">'
			. htmlspecialchars($prdsql[0]->prdname ?? '-', ENT_QUOTES, 'UTF-8') .
			'</div>

				<div class="divhide"  >So qty:   <span class="displaysoqty"  ></span></div>
      </div>
    </div>
  </div>

  <div class="mb-2">
    <button type="button" class="btn btn-primary btn-sm add-row1">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>
  
    	<table class="table table-bordered clone_table1" style="width: 150%;">
					<thead class="table-primary">
                    <tr>
				        <th>Line No</th>
				        <th>Type</th>
				        <th>Box No</th>
						<th>Kit Pack No</th>
                        <th>Batch NO</th>
                        <th>Subinventory</th>
                        <th>Sublocator</th>
                        <th>Manufacture Date</th>
                        <th>Expiry Date</th>
                        <th>Qoh</th>
                        <th>Issue Qoh</th>
                    </tr>
                    </thead>
                	<tbody class="clone_lines_body1 test"> ';
		if (count($sql) > 0) {
			foreach ($sql as $k => $v) {
				if ($v->qty <= 0) {
					unset($sql[$k]);
				}
			}
			$sql = array_values($sql);

			/*batch number Jcombco*/
			$batch = "";
			foreach ($sql as $key => $val) {
				$batch .= "'" . $val->batch_number . "'" . ",";
			}
			$batch1 = rtrim($batch, ",");
			if ($dispqtyid != "" && $dispqtyid != "undefined" && $dispqtyid != 0) {
				$dispqty = \DB::select("select * from s_dispatched_qty_t where s_dispatched_qty_id in($dispqtyid)");
				foreach ($dispqty as $key => $val) {
					$boxno = $val->box_no;
					$kitpackno = $val->kit_pack_no;
					$issqueqoh = $val->issue_qoh;
					$dispqtyid1 = $val->s_dispatched_qty_id;
					$bno = $val->batch_no;
					$val->expiry_date = date(\Session::get('p_date_format'), strtotime($val->expiry_date));
					$val->subinventory_name = $this->jcustomselectcomp('m_subinventory_t', 'subinventory_id', 'subinventory_name', $val->subinventory_id, '');
					$val->locator_code = $this->jcustomselectcomp('m_sublocators_t', 'sublocator_id', 'locator_code', $val->sublocator_id, '');
					$val->batchnumber = $this->jcustomselectcomp1('i_qoh_detail_t', 'batch_number', 'batch_number', $bno, ' and batch_number in(' . $batch1 . ')', 'batch_number');
					$subctgy = \DB::select("select m_products_t.product_subcategory_id as subctg from m_products_t LEFT JOIN i_qoh_detail_t on i_qoh_detail_t.product_id=m_products_t.product_id WHERE i_qoh_detail_t.product_id='$pid'");

					if ($subctgy = 76) {
						$sql = \DB::select("select sum(f.qty-IFNULL(f.qtyy,0)) as qty,batch_number,locator_id,subinventory_id,manufacturer_date,product_expire_date,qoh_detail_id from (select sum(qoh_trx_qty) as qty,0 as qtyy,batch_number,locator_id,subinventory_id,manufacturer_date,product_expire_date,product_id,qoh_detail_id FROM i_qoh_detail_t where product_id='$pid' and batch_number='$bno' and i_qoh_detail_t.company_id='$company_id' and i_qoh_detail_t.subinventory_id ='4' and i_qoh_detail_t.qualitystatus=1 GROUP by batch_number UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy, batch_number,locator_id,subinventory_id,0 as manufacturer_date,0 as product_expire_date,product_id as fdfd,reservation_detail_id from i_reservation_detail_t where i_reservation_detail_t.product_id='$pid' and i_reservation_detail_t.company_id='$company_id' and i_reservation_detail_t.subinventory_id = '4' GROUP by batch_number)f  where 1=1 and f.batch_number='$bno' group by batch_number");
					} else {
						$sql = \DB::select("select sum(f.qty-IFNULL(f.qtyy,0)) as qty,batch_number,locator_id,subinventory_id,manufacturer_date,product_expire_date,qoh_detail_id from (select sum(qoh_trx_qty) as qty,0 as qtyy,batch_number,locator_id,subinventory_id,manufacturer_date,product_expire_date,product_id,qoh_detail_id FROM i_qoh_detail_t where product_id='$pid' and batch_number='$bno' and i_qoh_detail_t.company_id='$company_id' and i_qoh_detail_t.subinventory_id ='3' and i_qoh_detail_t.qualitystatus=1 GROUP by batch_number UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy, batch_number,locator_id,subinventory_id,0 as manufacturer_date,0 as product_expire_date,product_id as fdfd,reservation_detail_id from i_reservation_detail_t where i_reservation_detail_t.product_id='$pid' and i_reservation_detail_t.company_id='$company_id' and i_reservation_detail_t.subinventory_id = '3' GROUP by batch_number)f  where 1=1 and f.batch_number='$bno' group by batch_number");
					}

					$qoh = 0;
					if (count($sql) > 0) {
						$qoh = $sql[0]->qty;
					}

					$html .= '<tr class="rcopy4"><td><input type="hidden"  class="form-control input-sm dispatched_qty_id dispatched_qty_id' . $key . '" value="' . $dispqtyid1 . '" ><input type="text"  class="form-control input-sm dis_line_no dis_line_no' . $key . '" value="' . ($key + 1) . '" readonly="readonly" > </td>';
					$html .= '<td>
				        <select id="free_type" name="free_type[]" class="select2 free_type free_type' . $key . '" >
						<option value="Order">Order</option>
						<option value="Free">Free</option>
						</select>
	                    </td>
	                    <td><input type="text"  name="box_no[]" class="form-control input-sm box_no box_no' . $key . ' input_qty_width" value=' . $boxno . '>
	                    </td>
	                    <td><input type="text"  name="kit_pack_no[]" class="form-control input-sm kit_pack_no kit_pack_no' . $key . ' input_qty_width" value=' . $kitpackno . ' ></td>
	                    <td> <input type="hidden"  class="form-control input-sm  qoh_id qoh_id' . $key . '" value="" >
	                         	<div class="batch' . $key . '" > <select id="batch_no"  name="batch_no[]" class="select2 input-sm batch_no batch_no' . $key . ' input_qty_width"> 
						<option value="' . $val->batchnumber . '" selected>' . $val->batchnumber . '</option>
						</select> </div></td>
						<div class="subread" >
						<td class="subread" > <select  id="subinventory_id" name="subinventory_id[]" class="select2 subinventory_id subinventory_id' . $key . '" >
						' . $val->subinventory_name . '
							</select> </td>
						</div>
						<div class="subread" >
						<td class="subread" > <select  id="sublocator_id" name="sublocator_id[]" class="select2 sublocator_id sublocator_id' . $key . '" >
							' . $val->locator_code . '
							</select></td>
						</div>
							<td><input type="text"  name="manufacture_date[]" class="form-control input-sm manufacture_date manufacture_date' . $key . ' input_qty_width" value="' . $val->manufacture_date . '" readonly ></td>
							<td><input type="text"  name="expiry_date[]" class="form-control input-sm expiry_date expiry_date' . $key . ' input_qty_width" value="' . $val->expiry_date . '" readonly ></td>
						<td><input type="text"  name="qoh[]" class="form-control input-sm qoh qoh' . $key . ' input_qty_width" value="' . $qoh . '" readonly ></td>
						<td><input type="text"  name="issue_qoh[]" data-index="' . $qoh . '" class="form-control input-sm issue_qoh issue_qoh' . $key . ' input_qty_width"  value=' . $issqueqoh . '></td>';

					$html .= '<td class="text-center">
        <button type="button" class="btn btn-sm btn-danger remove-row1">
          <i class="fas fa-minus-circle"></i>
        </button>
      </td>';
					$html .= '</tr>';

				}
			} else {
				foreach ($sql as $key => $val) {
					$boxno = "";
					$kitpackno = "";
					$issqueqoh = "";
					$dispqtyid1 = "";
					if ($dispqtyid != "" && $dispqtyid != "undefined") {
						$dispqty = \DB::select("select * from s_dispatched_qty_t where s_dispatched_qty_id in($dispqtyid) and batch_no='" . $val->batch_number . "'");
						if (count($dispqty) > 0) {
							$boxno = $dispqty[0]->box_no;
							$kitpackno = $dispqty[0]->kit_pack_no;
							$issqueqoh = $dispqty[0]->issue_qoh;
							$dispqtyid1 = $dispqty[0]->s_dispatched_qty_id;
						}
					}
					if ($val->qty > 0) {
						if ($val->qty < 0) {
							$qoh = 0;
						} else {
							$qoh = $val->qty;
						}
						$bno = trim($val->batch_number);
						$val->product_expire_date = date(\Session::get('p_date_format'), strtotime($val->product_expire_date));
						$val->subinventory_name = $this->jcustomselectcomp('m_subinventory_t', 'subinventory_id', 'subinventory_name', $val->subinventory_id, '');
						$val->locator_code = $this->jcustomselectcomp('m_sublocators_t', 'sublocator_id', 'locator_code', $val->locator_id, '');
						$val->batchnumber = $this->jcustomselectcomp1('i_qoh_detail_t', 'batch_number', 'batch_number', $bno, ' and batch_number in(' . $batch1 . ')', 'batch_number');

						$html .= '<tr class="rcopy4"><td><input type="hidden"  class="form-control input-sm dispatched_qty_id dispatched_qty_id' . $key . '" value="' . $dispqtyid1 . '" ><input type="text"  class="form-control input-sm dis_line_no dis_line_no' . $key . '" value="' . ($key + 1) . '" readonly="readonly" > </td>';
						$html .= '<td>
				        <select id="free_type" name="free_type[]" class="select2 free_type free_type' . $key . '" >
						<option value="Order">Order</option>
						<option value="Free">Free</option>
						</select>
	                    </td>
				<td>
				<input type="text"  name="box_no[]" class="form-control input-sm box_no box_no' . $key . ' input_qty_width" value=' . $boxno . '>
	                    </td>
	                    <td><input type="text"  name="kit_pack_no[]" class="form-control input-sm kit_pack_no kit_pack_no' . $key . ' input_qty_width" value=' . $kitpackno . ' ></td>
	                    <td> <input type="hidden"  class="form-control input-sm  qoh_id qoh_id' . $key . '" value="' . $val->qoh_detail_id . '" >
	                         	<div class="batch' . $key . '" > <select id="batch_no"  name="batch_no[]" class="select2 input-sm batch_no batch_no' . $key . ' input_qty_width"> 
						<option value="' . $val->batchnumber . '" selected>' . $val->batchnumber . '</option>
						</select> </div></td>
						<div class="subread" >
						<td class="subread" > <select  id="subinventory_id" name="subinventory_id[]" class="select2 subinventory_id subinventory_id' . $key . '" >
						' . $val->subinventory_name . '
							</select> </td>
						</div>
						<div class="subread" >
						<td class="subread" > <select  id="sublocator_id" name="sublocator_id[]" class="select2 sublocator_id sublocator_id' . $key . '" >
							' . $val->locator_code . '
							</select></td>
						</div>
							<td><input type="text"  name="manufacture_date[]" class="form-control input-sm manufacture_date manufacture_date' . $key . ' input_qty_width" value="' . $val->manufacturer_date . '" ></td>
							<td><input type="text"  name="expiry_date[]" class="form-control input-sm expiry_date expiry_date' . $key . ' input_qty_width" value="' . $val->product_expire_date . '" ></td>
						<td><input type="text"  name="qoh[]" class="form-control input-sm qoh qoh' . $key . ' input_qty_width" value="' . $qoh . '" readonly ></td>
						<td><input type="text"  name="issue_qoh[]" data-index="' . $qoh . '" class="form-control input-sm issue_qoh issue_qoh' . $key . ' input_qty_width"  value=' . $issqueqoh . '></td>';

						$html .= '<td class="text-center">
        <button type="button" class="btn btn-sm btn-danger remove-row1">
          <i class="fas fa-minus-circle"></i>
        </button>
      </td>';
						$html .= '</tr>';
					}
				}
			}
		} else {
			$html .= '<td></td><td colspan=4 > No Qoh for this product  </td>';
		}
		$html .= '</tbody></table><button type="button" class="btn btn-success addqohqty" id="addqohqty" style="text-align:center;"> Add QOH</button></div>';

		return $html;

	}

	public function employeeaddress($id = null)
	{
		$add = '';
		$sql = \DB::table('hr_emp_contact')->select('hr_emp_contact.current_flat_no', 'hr_emp_contact.current_street', 'hr_emp_contact.current_street_address', 'hr_emp_contact.current_postal_code', 'm_countries_t.country_name', 'm_states_t.state_name', 'm_cities_t.city_name')->leftjoin('m_countries_t', 'hr_emp_contact.current_country', "=", 'm_countries_t.country_id')->leftjoin('m_states_t', 'hr_emp_contact.current_state', "=", 'm_states_t.state_id')->leftjoin('m_cities_t', 'hr_emp_contact.current_city', "=", 'm_cities_t.city_id')->where('employee_id', $id)->get();
		if (count($sql) > 0) {
			$add = $sql[0]->current_flat_no . "," . $sql[0]->current_street_address . "," . $sql[0]->current_street . "," . $sql[0]->current_postal_code . "," . $sql[0]->city_name . "," . $sql[0]->state_name . "," . $sql[0]->country_name;
		}
		return $add;
	}
	/*deepika purpose:To get Product Qoh details*/
	public function productstockdetails($pid = null)
	{

		$company_id = Session::get('companyid');


		$sql = \DB::select("select sum(f.qty) as qoh_trx_qty,f.product_id as product_group_id,product_expire_date,manufacturer_date,subinventory_id,locator_id  from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id,product_expire_date,manufacturer_date,subinventory_id,locator_id  FROM i_qoh_detail_t where product_id='" . $pid . "' and batch_number='" . $_GET['batch_no'] . "' and company_id='" . $company_id . "'  and i_qoh_detail_t.subinventory_id !='5' GROUP by product_id)f");

		if (!empty($sql)) {
			if ($sql[0]->qoh_trx_qty != '') {
				if (($sql[0]->qoh_trx_qty) < 0) {
					$sql[0]->qoh_trx_qty = 0;
				}
				$sesdate = \Session::get('p_date_format');
				$expire = date($sesdate, strtotime($sql[0]->product_expire_date));
				$result = array($sql[0]->qoh_trx_qty, $expire, $sql[0]->manufacturer_date, $sql[0]->subinventory_id, $sql[0]->locator_id);
				return $result;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	// dispatch box qty & pack weight update

	public function Editsave()
	{

		$hdr_id = $_GET['hdr_id'];

		$check = \DB::update("update s_dispatch_hdr_t set pack_weight='" . $_GET['pack_wt'] . "',packaging_qty='" . $_GET['box_qty'] . "',freight_carrier_id='" . $_GET['carrier_name'] . "' where so_dispatch_hdr_id ='" . $_GET['hdr_id'] . "'");

		$inv_check = \DB::select("SELECT * FROM `s_invoice_hdr_t` WHERE reference_source_id LIKE '%$hdr_id%' and source='DISPATCH'");


		if ($inv_check > 0) {

			$check1 = \DB::update("update s_invoice_hdr_t set ar_frieghtcarriers_hdr_id='" . $_GET['carrier_name'] . "' where reference_source_id ='" . $_GET['hdr_id'] . "'");

			return 1;

		} else {

			return 1;

		}

	}

	/*end*/

	/** monthendfreeze index page function start **/
	public function monthendfreeze()
	{

		$this->data['type'] = $this->jcustomselect("a_lookuplines_t", "lookuplines_id", "lookup_code", '', 'and lookup_type="MONTH_END_FREEZE_TYPE"');
		$this->data['created_by'] = $this->jcombo("hr_employee_t", "employee_id", "first_name", \Session::get('emp_id'));

		return view('monthendfreeze.monthendfreezeform', $this->data);
	}
	/** monthendfreeze index page function end **/
	/** monthendfreeze grid load function start **/
	public function getmonthendfreezegriddata(Request $request)
	{
		//dd("hii");
		if ($request->ajax()) {
			$data = \DB::table('a_monthendfreeze_t')
				->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'a_monthendfreeze_t.monthend_type')
				->select([
					'a_monthendfreeze_t.*',
					'a_lookuplines_t.lookup_code',
					'a_lookuplines_t.lookuplines_id'
				]);

			return DataTables::of($data)
				->rawColumns(['actions'])
				->make(true);
		}
	}
	/** monthendfreeze grid load function end **/
	/** monthendfreeze save function start **/
	public function monthendfreezesave(Request $request)
	{

		$edit_id = $request->input('edit_id');
		//dd($edit_id);
		if ($edit_id == '') {

			$monthend = new monthendfreeze();
			$monthend->monthend_date = $request->input('monthend_date');
			$monthend->monthend_type = $request->input('monthend_type');
			$monthend->mindate1 = $request->input('monthend_date');
			$monthend->maxdate1 = $request->input('monthend_date');
			$monthend->active = $request->input('active');
			$monthend->mindate2 = '0';
			$monthend->maxdate2 = '0';
			$monthend->save();
			$name = $monthend->getKeyName();
			$id = $monthend->$name;
			$table = $monthend->getTable();
			$column = $monthend->getKeyName();
			$this->hrmssaveinsert($table, $column, $id, 1);
			// auditlog
			$this->auditlog($id, "monthendfreeze", "create", $_POST, "a_monthendfreeze_t");
			return 1;
		} else {
			$monthend = new monthendfreeze();
			$edit_id = $_POST['edit_id'];
			//dd($edit_id);
			//$_POST['location']=json_encode($_POST['location']);
			$_POST['monthend_date'] = $_POST['monthend_date'];
			$_POST['monthend_type'] = $_POST['monthend_type'];
			$_POST['mindate1'] = $_POST['monthend_date'];
			$_POST['maxdate1'] = $_POST['monthend_date'];
			$_POST['active'] = $_POST['active'];
			$_POST['mindate2'] = '0';
			$_POST['maxdate2'] = '0';

			monthendfreeze::find($edit_id)->update($_POST);
			$table = $monthend->getTable();
			$column = $monthend->getKeyName();
			$this->hrmssaveinsert($table, $column, $edit_id, 2);
			// auditlog
			$this->auditlog($edit_id, "monthendfreeze", "edit", $_POST, "a_monthendfreeze_t");
			return 2;
		}
	}

	/** monthendfreeze save function end **/
	public function monthendfreezedestroy(Request $request, $id = null)
	{

		$j = 0;

		if ($j == 0) {
			$query = DB::table('a_monthendfreeze_t')->where('monthend_id', $id)->delete();
			// auditlog
			$this->auditlog($id, "monthendfreeze", "create", $_GET, "a_monthendfreeze_t");

		}

		if ($j == 1)
			return response()->json(array('status' => 'error', 'message' => 'Failed'));
		else if ($j == 0)
			return response()->json(array('status' => 'success', 'message' => 'Delete Successfully'));

	}

}
