<?php
namespace App\Http\Controllers;
use App\Soquote;
use App\Soquotelines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\SoorderController;
use App\Http\Controllers\SalesinvoiceController;
use Validator, DB, Session;
use File, Config;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class SoquoteController extends Controller
{
	public $module = "soquote";
	public function __construct()
	{
		$this->data = array();
		$this->table = "s_quote_hdr_t";
		$this->subtable = "s_quote_lines_t";
		$this->pageModule = "soquote";
		$this->model = new Soquote;
		$this->submodel = new Soquotelines;
		$this->data['pageModule'] = $this->pageModule;
		$this->data['pageMethod'] = \Request::route()->getName();
		$this->data['pageFormtype'] = 'ajax';

		$this->data['pageMethod'] == 'soquote';
		$this->data['pageMethod'] == "copysalesquote";
		$this->data['urlmenu'] = $this->indexs();


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


		$route = \Request::route()->getName();
		if ($route == "soquote") {
			$this->data['route'] = $route;
			$this->data['status'] = "";
		} else if ($route == "soorderfromqo") {
			$this->data['route'] = $route;
			$this->data['status'] = "APPROVED";
		} else {
			$this->data['route'] = $route;
			$this->data['status'] = "INITIATED";
		}
		/************************************** Selected Customer in Index ***********************************/
		/*	$cust = \DB::select('select distinct customerid from s_quote_hdr_t');
			if(!empty($cust))
			{
			$ids = array(); 
			foreach($cust as $key=>$val)
			{
				$ids[] = $val->customerid; 
			} 
			$a= implode(",", $ids);
			$condition ="and customer_id IN($a)";
			//$condition =$ids;
			}
			else
			{
			$condition ="";	
			}

			$this->data['cusnameopt'] = $this->jqgridcustselect('m_customers_t','customer_name','customer_name',$condition); */

		$this->data['pageMethod'] = \Request::route()->getName();

		return view('soquote.table', $this->data);
	}

	public function soquotegriddata()
	{

		$wh = '';
		$col_name = '';
		$app_id = \Session::get('id');
		$com = \Session::get('companyid');

		if ($_GET['status'] != '') {
			if (isset($_GET['pageMethod'])) {
				if ($_GET['pageMethod'] != 'soorderfromqo') {
					$wh .= "and quote_status='" . $_GET['status'] . "'";
					$col_name = "quote_status";
					$op = "=";
					$status_val = "'" . $_GET['status'] . "'";
				}
			} else {
				$wh .= "and quote_status='" . $_GET['status'] . "'";
				$col_name = "quote_status";
				$op = "=";
				$status_val = "'" . $_GET['status'] . "'";
			}
		}

		if ($_GET['quotetype'] != '') {
			$quotetype = $_GET['quotetype'];

			if ($quotetype == 'copysalesquote') {
				$wh .= "and s_quote_hdr_t.quote_status!='DRAFT'";
				$col_name = "quote_status";
				$op = "!=";
				$status_val = "'DRAFT'";
			} elseif ($quotetype == 'salesquoteapproval') {
				$wh .= " and json_contains(s_quote_hdr_t.approver_id,'" . $app_id . "')=1 ";
			}
		} else {
			$quotetype = "";
		}

		if (isset($_GET['pageMethod'])) {
			if ($_GET['pageMethod'] == 'soorderfromqo') {

				$wh .= " and ( s_quote_hdr_t.order_status='0' and json_contains(s_quote_hdr_t.approver_id ,'0')=1 and s_quote_hdr_t.quote_status ='INITIATED') or (s_quote_hdr_t.order_status ='0' and s_quote_hdr_t.quote_status ='APPROVED')";
			}
		}


		if ($col_name != '') {
			$wh .= $grid_data = $this->grid_statuscheck('s_quote_hdr_t', 'quote_date', $col_name, $op, $status_val);
		} else {
			$wh .= $grid_data = $this->grid_check('s_quote_hdr_t', 'quote_date');
		}

		$SQL = "SELECT 
s_quote_hdr_t.`quote_hdr_id`,
s_quote_hdr_t.`quote_no`,
s_quote_hdr_t.quote_name,
s_quote_hdr_t.quote_date,
s_quote_hdr_t.quote_type,
s_quote_hdr_t.quote_status,
s_quote_hdr_t.remarks,
s_quote_hdr_t.savestatus,
m_customers_t.customer_name, 
m_customers_t.customer_id 
FROM `s_quote_hdr_t` 
left join m_customers_t on(
m_customers_t.customer_id=s_quote_hdr_t.customerid
) where 1=1 $wh and s_quote_hdr_t.company_id=$com ";

		$result = \DB::select($SQL);
		return DataTables::of($result)->make(true);

	}


	public function getCustomergridData()
	{

		$wh = '';
		if ($_GET['_search'] == 'true') {
			$wh = $this->jqgridsearch($_GET['filters']);
		}
		$wh .= "and (m_customers_t.savestatus='SAVE')";
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		$com = \Session::get('companyid');
		if (!$sidx)
			$sidx = 1;
		$result = \DB::select("SELECT COUNT(customer_id) AS count FROM m_customers_t where 1=1 $wh  and m_customers_t.company_id=$com");
		$count = $result[0]->count;
		if ($count > 0 && $limit > 0) {
			$total_pages = ceil($count / $limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages)
			$page = $total_pages;
		$start = $limit * $page - $limit;
		if ($start < 0)
			$start = 0;
		$SQL = "SELECT * FROM m_customers_t where 1=1 $wh  and m_customers_t.company_id=$com ORDER BY $sidx $sord LIMIT $start , $limit";
		$result = \DB::select($SQL);
		$responce->rows[] = '';
		$responce->rows = $result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}


	public function create($id = null, $quotetype = null)
	{

		$custaddress1 = new SoorderController;
		$this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'soquote')->get();
		$this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
		$this->data['country_new'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', '');
		$this->data['state_new'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', '');
		$this->data['city_new'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', '');
		$this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
		$this->data['edit_route'] = \Request::route()->getName();
		$this->data['return_url'] = \Request::route()->getName();
		if ($id == "0") {

			$sotable = $this->model->getTableColumns();
			$this->data['row'] = array();
			foreach ($sotable as $key => $val) {
				$this->data['row'][$val] = '';
			}
			$this->data['row']['quote_date'] = date("Y-m-d");
			$this->data['row']['quote_type'] = $quotetype;
			$this->data['row']['bill_to_address'] = '';
			$this->data['row']['ship_to_address'] = '';
			$this->data['row']['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', '');
			$this->data['row']['source'] = "STANDARD";
			$this->data['row']['frieghtterm_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', '', "and source_type_id='Sales'");
			$this->data['row']['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', '');
			$this->data['row']['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '', "and source_type_id='Sales'");
			$this->data['row']['frieghtcarriers_hdr_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', "and source_type_id='Sales'");
			$this->data['row']['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', '');
			$this->data['row']['currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '');
			$this->data['row']['customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', '', 'and savestatus="SAVE"');
			$this->data['row']['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
			$this->data['row']['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');
			$this->data['row']['quote_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', '', ' and price_list_type="Sales"');
			$this->data['row']['salesperson_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
			$this->data['row']['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
			$this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
			$this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
			$this->data['sac_code'] = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="SAC"');
			$this->data['part_no'] = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '', '');
			$this->data['linedata'] = array();
			$pricelist = '';
		} else {
			if ($this->data['pageMethod'] == 'soquote' || $this->data['pageMethod'] == 'salesquoteapproval') {
				$this->data['id'] = $id;
				$table = \DB::table('s_quote_hdr_t')->where('quote_hdr_id', $id)->get();
				$table = collect($table)->map(function ($x) {
					return (array) $x; })->toArray();
				$this->data['row'] = $table[0];
				$this->data['row']['attachfile_name'] = $table[0]['attachfile_name'];
				$this->data['row']['customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]['customerid'], 'and savestatus="SAVE"');
				$this->data['row']['quote_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]['quote_pricelist_id'], ' and price_list_type="Sales"');
				$this->data['row']['salesperson_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]['salesperson_id']);
				$this->data['row']['frieghtterm_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $table[0]['frieghtterm_id'], 'and source_type_id="Sales"');
				$this->data['row']['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $table[0]['payment_term_id']);
				$this->data['row']['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $table[0]['delivery_terms_id'], 'and source_type_id="Sales"');
				$this->data['row']['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $table[0]['payment_method_id']);
				$this->data['row']['frieghtcarriers_hdr_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $table[0]['frieghtcarriers_hdr_id'], 'and source_type_id="Sales"');
				$this->data['row']['currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $table[0]['currency_id']);
				$this->data['row']['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', $table[0]['discount_id']);
				$this->data['row']['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]['project_id']);
				$this->data['row']['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['row']['bill_to_address'] = $custaddress1->sobilladdress($table[0]['bill_to_address_id']);
				$this->data['row']['ship_to_address'] = $custaddress1->soshipaddress($table[0]['ship_to_address_id']);
				$this->data['row']['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
				$tablelines = \DB::table('s_quote_lines_t')->where('quote_hdr_id', $id)->get();

				$this->data['linedata'] = $tablelines;
				$quotecount = 0;
				foreach ($tablelines as $key => $value) {
					if ($this->data['row']['quote_type'] == "STANDARD") {
						$tax = $this->gettax($value->product_id);
						$unitprice = $this->pricelist($table[0]['customerid'], $value->product_id);

						if ($unitprice != "0.00") {
							$this->data['linedata'][$key] = (object) array();
							$this->data['linedata'][$key]->quote_line_id = $value->quote_line_id;
							$this->data['linedata'][$key]->quote_hdr_id = $id;
							if ($table[0]['customerid'] == "") {
								$this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
							} else {
								$this->data['product'] = $custaddress1->getcustomerpriceproduct($table[0]['customerid'], '');
							}
							if ($table[0]['customerid'] == "" && $value->product_id == "") {
								$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'soquote');
							} else if ($table[0]['customerid'] != "" && $value->product_id == "") {
								$this->data['linedata'][$key]->product_id = $custaddress1->getcustomerpriceproduct($table[0]['customerid'], '');
							} else {
								$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
							}

							$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
							$this->data['linedata'][$key]->part_no = $this->jcombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $this->data['row']['customerid']);
							$hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);
							$this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $hsn[0]->defalut_hsn_code, ' and gst_code_hdr_id in(' . $hsn[0]->hsn_code . ')');
							$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $tax['taxgroup_id']);
							$this->data['linedata'][$key]->qty = $value->qty;
							$this->data['linedata'][$key]->unit_price = $value->unit_price;
							$this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
							$this->data['linedata'][$key]->discount_amount = $value->discount_amount;
							$this->data['linedata'][$key]->tax_amount = $value->tax_amount;
							$this->data['linedata'][$key]->line_total = $value->line_total;
							$this->data['linedata'][$key]->promised_date = $value->promised_date;
							$this->data['linedata'][$key]->comments = $value->comments;
							$this->data['linedata'][$key]->tax_excemption = $value->tax_excemption;
						} else {

							$quotecount++;

							$this->data['linedata'][$key] = (object) array();
							$this->data['linedata'][$key]->quote_line_id = $value->quote_line_id;
							$this->data['linedata'][$key]->quote_hdr_id = $id;
							$this->data['linedata'][$key]->unit_price = $unitprice;
							if ($table[0]['customerid'] == "") {
								$this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
							} else {
								$this->data['product'] = $custaddress1->getcustomerpriceproduct($table[0]['customerid'], '');
							}
							if ($table[0]['customerid'] == "" && $value->product_id == "") {
								$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'soquote');
							} else if ($table[0]['customerid'] != "" && $value->product_id == "") {
								$this->data['linedata'][$key]->product_id = $custaddress1->getcustomerpriceproduct($table[0]['customerid'], '');
							} else {
								$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
							}

							$this->data['linedata'][$key]->part_no = $this->jcombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $this->data['row']['customer_id']);
							$this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="SAC"');
							$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
							$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
							$this->data['linedata'][$key]->product_description = $value->product_description;
							$this->data['linedata'][$key]->qty = $value->qty;
							$this->data['linedata'][$key]->tax_amount = '';
							$this->data['linedata'][$key]->line_total = '';
							$this->data['linedata'][$key]->promised_date = $value->promised_date;
							$this->data['linedata'][$key]->tax_excemption = 'No';
							$this->data['linedata'][$key]->comments = $value->comments;
							$this->data['linedata'][$key]->discount_percentage = '';
							$this->data['linedata'][$key]->discount_amount = '';
							$tax = $this->gettax($value->product_id);
							$unitprice = $this->pricelist($table[0]['customerid'], $value->product_id);
						}
					} else {
						$this->data['linedata'][$key] = (object) array();
						$this->data['linedata'][$key]->quote_line_id = $value->quote_line_id;
						$this->data['linedata'][$key]->quote_hdr_id = $id;

						if ($table[0]['customerid'] == "") {
							$this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
						} else {
							$this->data['product'] = $custaddress1->getcustomerpriceproduct($table[0]['customerid'], '');
						}
						if ($table[0]['customerid'] == "" && $value->product_id == "") {
							$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'soquote');
						} else if ($table[0]['customerid'] != "" && $value->product_id == "") {
							$this->data['linedata'][$key]->product_id = $custaddress1->getcustomerpriceproduct($table[0]['customerid'], '');
						} else {
							$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
						}


						$this->data['linedata'][$key]->part_no = $this->jcombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $this->data['row']['customer_id']);
						$this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code, 'and classification_name="SAC"');
						$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
						$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
						$this->data['linedata'][$key]->product_description = $value->product_description;
						$this->data['linedata'][$key]->qty = $value->qty;
						$this->data['linedata'][$key]->unit_price = $value->unit_price;
						$this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
						$this->data['linedata'][$key]->discount_amount = $value->discount_amount;
						$this->data['linedata'][$key]->tax_amount = $value->tax_amount;
						$this->data['linedata'][$key]->line_total = $value->line_total;
						$this->data['linedata'][$key]->promised_date = $value->promised_date;
						$this->data['linedata'][$key]->comments = $value->comments;
						$this->data['linedata'][$key]->tax_excemption = $value->tax_excemption;
					}
				}
			} else if ($this->data['pageMethod'] == 'copysalesquote') {
				$this->data['id'] = '';
				$table = \DB::table('s_quote_hdr_t')->where('quote_hdr_id', $id)->get();
				$table = collect($table)->map(function ($x) {
					return (array) $x; })->toArray();
				$this->data['row'] = $table[0];
				$this->data['row']['quote_hdr_id'] = '';
				$this->data['row']['quote_no'] = '';
				$this->data['row']['attachfile_name'] = $table[0]['attachfile_name'];
				$this->data['row']['customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]['customerid'], 'and savestatus="SAVE"');
				$this->data['row']['quote_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]['quote_pricelist_id'], ' and price_list_type="Sales"');
				$this->data['row']['salesperson_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]['salesperson_id']);
				$this->data['row']['frieghtterm_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $table[0]['frieghtterm_id'], 'and source_type_id="Sales"');
				$this->data['row']['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $table[0]['payment_term_id']);
				$this->data['row']['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $table[0]['delivery_terms_id'], 'and source_type_id="Sales"');
				$this->data['row']['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $table[0]['payment_method_id']);
				$this->data['row']['frieghtcarriers_hdr_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $table[0]['frieghtcarriers_hdr_id'], 'and source_type_id="Sales"');
				$this->data['row']['currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $table[0]['currency_id']);
				$this->data['row']['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', $table[0]['discount_id']);
				$this->data['row']['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]['project_id']);
				$this->data['row']['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['row']['bill_to_address'] = $custaddress1->sobilladdress($table[0]['bill_to_address_id']);
				$this->data['row']['ship_to_address'] = $custaddress1->soshipaddress($table[0]['ship_to_address_id']);
				$this->data['row']['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
				$tablelines = \DB::table('s_quote_lines_t')->where('quote_hdr_id', $id)->get();
				$this->data['linedata'] = $tablelines;
				$quotecount = 0;
				foreach ($tablelines as $key => $value) {
					if ($this->data['row']['quote_type'] == "STANDARD") {
						$tax = $this->gettax($value->product_id);
						$unitprice = $this->pricelist($table[0]['customerid'], $value->product_id);
						if ($unitprice != "0.00") {
							$this->data['linedata'][$key] = (object) array();
							$this->data['linedata'][$key]->quote_line_id = '';
							$this->data['linedata'][$key]->quote_hdr_id = '';


							if ($table[0]['customerid'] == "") {
								$this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
							} else {
								$this->data['product'] = $custaddress1->getcustomerpriceproduct($table[0]['customerid'], '');
							}
							if ($table[0]['customerid'] == "" && $value->product_id == "") {
								$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
							} else if ($table[0]['customerid'] != "" && $value->product_id == "") {
								$this->data['linedata'][$key]->product_id = $custaddress1->getcustomerpriceproduct($table[0]['customerid'], $value->product_id);
							} else {
								$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
							}


							$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
							$this->data['linedata'][$key]->part_no = $this->jcombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $this->data['row']['customerid']);
							$hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);
							$this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $hsn[0]->defalut_hsn_code, ' and gst_code_hdr_id in(' . $hsn[0]->hsn_code . ')');
							$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $tax['taxgroup_id']);
							$this->data['linedata'][$key]->qty = $value->qty;
							$this->data['linedata'][$key]->unit_price = $value->unit_price;
							$this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
							$this->data['linedata'][$key]->discount_amount = $value->discount_amount;
							$this->data['linedata'][$key]->tax_amount = $value->tax_amount;
							$this->data['linedata'][$key]->line_total = $value->line_total;
							$this->data['linedata'][$key]->promised_date = $value->promised_date;
							$this->data['linedata'][$key]->comments = $value->comments;
							$this->data['linedata'][$key]->tax_excemption = $value->tax_excemption;
						} else {
							$quotecount++;
							$this->data['linedata'][$key] = (object) array();
							$this->data['linedata'][$key]->quote_line_id = '';
							$this->data['linedata'][$key]->quote_hdr_id = $id;
							$this->data['linedata'][$key]->unit_price = $unitprice;

							if ($value->product_id != '') {
								$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
							} else {
								$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'soquote');
							}
							$this->data['linedata'][$key]->part_no = $this->jcombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $this->data['row']['customer_id']);
							$this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="SAC"');
							$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
							$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
							$this->data['linedata'][$key]->product_description = $value->product_description;
							$this->data['linedata'][$key]->qty = $value->qty;
							$this->data['linedata'][$key]->tax_amount = '';
							$this->data['linedata'][$key]->line_total = '';
							$this->data['linedata'][$key]->promised_date = $value->promised_date;
							$this->data['linedata'][$key]->tax_excemption = 'No';
							$this->data['linedata'][$key]->comments = $value->comments;
							$this->data['linedata'][$key]->discount_percentage = '';
							$this->data['linedata'][$key]->discount_amount = '';
							$tax = $this->gettax($value->product_id);
							$unitprice = $this->pricelist($table[0]['customerid'], $value->product_id);
						}
					} else {
						$this->data['linedata'][$key] = (object) array();
						$this->data['linedata'][$key]->quote_line_id = '';
						$this->data['linedata'][$key]->quote_hdr_id = '';


						if ($value->product_id != '') {
							$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);

						} else {
							$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'soquote');

						}



						$this->data['linedata'][$key]->part_no = $this->jcombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $this->data['row']['customer_id']);
						$this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code, 'and classification_name="SAC"');
						$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
						$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
						$this->data['linedata'][$key]->product_description = $value->product_description;
						$this->data['linedata'][$key]->qty = $value->qty;
						$this->data['linedata'][$key]->unit_price = $value->unit_price;
						$this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
						$this->data['linedata'][$key]->discount_amount = $value->discount_amount;
						$this->data['linedata'][$key]->tax_amount = $value->tax_amount;
						$this->data['linedata'][$key]->line_total = $value->line_total;
						$this->data['linedata'][$key]->promised_date = $value->promised_date;
						$this->data['linedata'][$key]->comments = $value->comments;
						$this->data['linedata'][$key]->tax_excemption = $value->tax_excemption;
					}
				}
			} else if ($this->data['pageMethod'] == 'salesquotefromenquiry') {
				$this->data['id'] = $id;
				$table = \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id', $id)->get();
				$sqtable = $this->model->getTableColumns();
				$this->data['row'] = array();
				foreach ($sqtable as $key => $val) {
					$this->data['row'][$val] = '';
				}
				/**************************Header Details ********************************/
				$this->data['row']['customerid'] = $table[0]->customerid;
				$this->data['row']['quote_date'] = $table[0]->inquiry_date;
				$this->data['row']['quote_type'] = $table[0]->inquiry_type;
				$this->data['row']['so_inquiry_hdr_id'] = $id;
				$this->data['row']['remarks'] = $table[0]->remarks;
				$this->data['row']['reference_id'] = $id;
				$this->data['row']['reference_number'] = $table[0]->inquiry_no;
				$this->data['row']['source'] = "INQUIRY";
				$address = $custaddress1->custaddress($table[0]->customerid);
				$this->data['row']['bill_to_address'] = $address[0];
				$this->data['row']['ship_to_address'] = $address[1];
				$this->data['row']['bill_to_address_id'] = $address[2];
				$this->data['row']['ship_to_address_id'] = $address[3];
				$customerdata = $this->getcustomerdatas($table[0]->customerid);
				$this->data['row']['customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_name', $table[0]->customerid, 'and savestatus="SAVE"');
				$this->data['row']['frieghtterm_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $customerdata[0]->frieghtterm_id, 'and source_type_id="Sales"');
				$this->data['row']['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', $customerdata[0]->ar_discount_hdr_id);
				$this->data['row']['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $customerdata[0]->default_payment_terms_id);
				$this->data['row']['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $customerdata[0]->default_payment_method_id);
				$this->data['row']['frieghtcarriers_hdr_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $customerdata[0]->ar_frieghtcarriers_hdr_id, 'and source_type_id="Sales"');
				$this->data['row']['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
				$this->data['row']['salesperson_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $customerdata[0]->sales_person);
				$this->data['row']['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $customerdata[0]->delivery_terms_id, 'and source_type_id="Sales"');
				$this->data['row']['currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '');
				$this->data['row']['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['row']['quote_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $customerdata[0]->pricelist_id, ' and price_list_type="Sales"');

				$this->data['row']['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
				/**************************Header Details End ********************************/

				/**************************Lines Details  ********************************/

				$linetable = \DB::table('s_inquiry_lines_t')->where('so_inquiry_hdr_id', $id)->orderBy('so_inquiry_lines_id', 'asc')->get();

				$this->data['linedata'] = array();
				$grandtotal = 0;
				$taxamount_total = 0;
				$quotecount = 0;
				$key5 = -1;

				foreach ($linetable as $key => $value) {
					if ($this->data['row']['quote_type'] == "STANDARD") {
						$tax = $this->gettax($value->product_id);
						$unitprice = $this->pricelist($table[0]->customerid, $value->product_id);

						if ($unitprice != "0.00") {
							$key5++;
							$this->data['linedata'][$key] = (object) array();
							$this->data['linedata'][$key]->quote_line_id = '';
							$this->data['linedata'][$key]->quote_hdr_id = $id;
							$this->data['linedata'][$key]->product_id = $custaddress1->getcustomerpriceproduct($table[0]->customerid, $value->product_id);

							if ($table[0]->customerid == "") {
								$this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
							} else {
								$this->data['product'] = $custaddress1->getcustomerpriceproduct($table[0]->customerid, '');
							}
							if ($table[0]->customerid == "" && $value->product_id == "") {
								$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'soquote');
							} else if ($table[0]->customerid != "" && $value->product_id == "") {
								$this->data['linedata'][$key]->product_id = $custaddress1->getcustomerpriceproduct($table[0]->customerid, '');
							} else {
								$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
							}


							$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
							$this->data['linedata'][$key]->part_no = $this->jcombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $this->data['row']['customerid']);
							$hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);
							$this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $hsn[0]->defalut_hsn_code, ' and gst_code_hdr_id in(' . $hsn[0]->hsn_code . ')');
							$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $tax['taxgroup_id']);
							$this->data['linedata'][$key]->qty = $value->required_qty;
							$discount = $custaddress1->customerdiscount($table[0]->customerid);
							$this->data['linedata'][$key]->unit_price = $unitprice;
							$this->data['linedata'][$key]->discount_percentage = $discount['dis_amt'];
							$this->data['linedata'][$key]->discount_amount = (($value->required_qty * $unitprice) * $discount['dis_amt']) / 100;
							$taxamount = (($value->required_qty * $unitprice) * $tax['display_name']) / 100;
							$linetotal = ($taxamount + ($value->required_qty * $unitprice));
							$taxamount_total += $taxamount;
							$grandtotal = $grandtotal + $linetotal;
							$this->data['linedata'][$key]->tax_amount = $taxamount;
							$this->data['linedata'][$key]->line_total = $linetotal;
							$this->data['linedata'][$key]->promised_date = $value->need_by_date;
							$this->data['linedata'][$key]->comments = $value->comments;
							$this->data['linedata'][$key]->tax_excemption = 'No';
						} else {
							$quotecount++;
							$this->data['linedata'][$key] = (object) array();
							$this->data['linedata'][$key]->quote_line_id = '';
							$this->data['linedata'][$key]->quote_hdr_id = $id;
							$this->data['linedata'][$key]->unit_price = $unitprice;

							if ($table[0]->customerid == "") {
								$this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
							} else {
								$this->data['product'] = $custaddress1->getcustomerpriceproduct($table[0]->customerid, '');
							}
							if ($value->product_id != "") {
								$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
							} else {
								$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id);
							}

							$this->data['linedata'][$key]->part_no = $this->jcombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $this->data['row']['customer_id']);
							$this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="SAC"');
							$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
							$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
							$this->data['linedata'][$key]->product_description = $value->product_description;
							$this->data['linedata'][$key]->qty = $value->required_qty;
							$this->data['linedata'][$key]->tax_amount = '';
							$this->data['linedata'][$key]->line_total = '';
							$this->data['linedata'][$key]->promised_date = $value->need_by_date;
							$this->data['linedata'][$key]->tax_excemption = 'No';
							$this->data['linedata'][$key]->comments = $value->comments;
							$this->data['linedata'][$key]->discount_percentage = '';
							$this->data['linedata'][$key]->discount_amount = '';
							$tax = $this->gettax($value->product_id);
							$unitprice = $this->pricelist($table[0]->customerid, $value->product_id);
						}
					} else {
						$this->data['linedata'][$key] = (object) array();
						$this->data['linedata'][$key]->quote_line_id = '';
						$this->data['linedata'][$key]->quote_hdr_id = $id;

						if ($value->product_id != "") {
							$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
						} else {
							$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'soquote');
						}

						$this->data['linedata'][$key]->part_no = $this->jcombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $this->data['row']['customer_id']);
						$this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="SAC"');
						$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
						$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
						$this->data['linedata'][$key]->product_description = $value->product_description;
						$this->data['linedata'][$key]->qty = $value->required_qty;
						$this->data['linedata'][$key]->tax_amount = '';
						$this->data['linedata'][$key]->line_total = '';
						$this->data['linedata'][$key]->promised_date = $value->need_by_date;
						$this->data['linedata'][$key]->tax_excemption = 'No';
						$this->data['linedata'][$key]->comments = $value->comments;
						$this->data['linedata'][$key]->discount_percentage = '';
						$this->data['linedata'][$key]->discount_amount = '';
						$tax = $this->gettax($value->product_id);
						$unitprice = $this->pricelist($table[0]->customerid, $value->product_id);
						if ($unitprice != "0.00") {
							$this->data['linedata'][$key]->unit_price = $unitprice;
						} else {
							$this->data['linedata'][$key]->unit_price = '';
						}
					}
				}
				$this->data['quotecount'] = $quotecount;
			}

		}

		return view('soquote.form', $this->data);
	}

	public function save(Request $request)
	{

		$so = new SoorderController;

		$form = $request->all();
		$dataupload = "";
		$form = $request->except([
			'_token',
			'form_config',
			'form_data_json',
			'submit_type',
			'choosefile',
			'existing_file',
			'custtype',
			'bill_to_address',
			'ship_to_address',
			'enable-masterdetail',
		]);
		// Normalize "bulk_" keys once
		$form = $this->normalizeLineFormKeys($form);

		// Build header + lines
		$data = $this->validatePost($form, $this->table, 'header');
		$lines_data = $this->validatePost($form, $this->subtable, 'lines');

		if ($data['quote_status'] != "DRAFT") {
			if ($data['quote_grand_total'] == 0) {
				$data['quote_grand_total'] = "null";
			}
			$approverid = $this->Approvaldatacheck('soquote', $data['quote_grand_total']);

			if ($approverid == "0") {
				$data['approver_id'] = \Session::get('id');
				$data['quote_status'] = "APPROVED";
			} else {
				$data['approver_id'] = $approverid;
			}
		}

		if ($_POST['quote_no'] == "") {
			$seqno = $this->Seqnoe('SOQ', 's_quote_hdr_t', $_POST['quote_type'], 'soquote_count');
			$data['quote_no'] = $seqno[0];
			$data['soquote_count'] = $seqno[1];
		} else {
			$seqno[0] = $_POST['quote_no'];
		}
		if ($data['quote_status'] == "INITIATED") {
			$quote_status = 'Saved' . ' Successfully';
		} else {
			$quote_status = $data['quote_status'] . ' Successfully';
		}

		if ($data['quote_status'] == "APPROVED" || $data['quote_status'] == "REJECTED")
			$msg = $data['quote_status'] . '  Successfully ';
		else
			$msg = 'Saved Successfully ';

		\DB::beginTransaction();
		try {
			$id = $this->model->insertRow($data);

			if ($_POST['quote_hdr_id'] == "") {
				$action = 'create';
			} else {
				$action = 'edit';
			}

			$this->auditlog($id, "Sales Quote", $action, $data, "s_quote_hdr_t");

			$lid = $this->submodel->subgridSave($lines_data, $id);


			$sales_id = $id;

			$sales_hdr_id = $request->input('quote_hdr_id');

			if ($sales_hdr_id == '') {
				if ($request->hasfile('choosefile')) {
					foreach ($request->file('choosefile') as $file) {
						$name = $file->getClientOriginalName();

						$file->move(public_path() . '/uploads/soquoteupload/S' . $sales_id . '/', $name);
						$dataupload[] = $name;
					}
				}
				$attachfile_name = json_encode($dataupload);
				//dd("update s_quote_hdr_t set attachfile_name='".$attachfile_name."' where quote_hdr_id='$sales_id'")
				\DB::update("update s_quote_hdr_t set attachfile_name='" . $attachfile_name . "' where quote_hdr_id='$sales_id'");
				$this->data['notymsg'] = "yes";
			} else {

				$existing_file = $request->input('existing_file');
				$choose_file = $request->file('choosefile');
				$existing_file = explode(",", $existing_file);


				if (count($choose_file) == 0 && count($existing_file) > 0) {
					if (count($existing_file) == 1 && $existing_file[0] == '') {

						\DB::update("update s_quote_hdr_t set attachfile_name='' where quote_hdr_id='$sales_id'");
					} else {
						$get_attach = DB::table('s_quote_hdr_t')->where('quote_hdr_id', $sales_id)->get();
						$attach_file = json_decode($get_attach[0]->attachfile_name);
						$attach_file1 = array();

						foreach ($attach_file as $k => $v) {
							$attach_file1[] = $v;
						}
						$array_diff = array_diff($attach_file1, $existing_file);

						if (count($array_diff) > 0) {
							foreach ($array_diff as $k => $v) {
								unlink(public_path() . '/uploads/soquoteupload/S' . $sales_id . '/' . $v);
							}
							$attachfile_name = json_encode($existing_file);

							\DB::update("update s_quote_hdr_t set attachfile_name='" . $attachfile_name . "' where quote_hdr_id='$sales_id'");
						}
					}
				} else if (count($choose_file) > 0 && count($existing_file) > 0) {

					foreach ($request->file('choosefile') as $file) {
						$name = $file->getClientOriginalName();

						$file->move(public_path() . '/uploads/soquoteupload/S' . $sales_id . '/', $name);
						$dataupload[] = $name;
					}

					$get_attach = DB::table('s_quote_hdr_t')->where('quote_hdr_id', $sales_id)->get();
					$attach_file = json_decode($get_attach[0]->attachfile_name);
					$attach_file1 = array();

					foreach ($attach_file as $k => $v) {
						$attach_file1[] = $v;
					}

					$array_diff = array_diff($attach_file1, $existing_file);

					if (count($array_diff) > 0) {
						foreach ($attach_file as $k => $v) {
							unlink(public_path() . '/uploads/soquoteupload/S' . $sales_id . '/' . $v);
						}
						$attachfile_name = array_merge($existing_file, $dataupload);
						$attachfile_name = json_encode($attachfile_name);
					} else {
						$attachfile_name = array_merge($attach_file1, $dataupload);
						$attachfile_name = json_encode($attachfile_name);
					}
					\DB::update("update s_quote_hdr_t set attachfile_name='" . $attachfile_name . "' where quote_hdr_id='$sales_id'");

				} else if (count($choose_file) > 0 && count($existing_file) == 0) {
					foreach ($request->file('choosefile') as $file) {
						$name = $file->getClientOriginalName();

						$file->move(public_path() . '/uploads/soquoteupload/S' . $sales_id . '/', $name);
						$dataupload[] = $name;
					}
					$attachfile_name = json_encode($dataupload);
					\DB::update("update s_quote_hdr_t set attachfile_name='" . $attachfile_name . "' where quote_hdr_id='$sales_id'");
				}
			}

			if ($data['quote_status'] == "INITIATED") {
				$noti = 'Sales quote ' . $seqno[0] . ' Initaited';
				$send_notification = $this->sendPopUpNotification($id, "SO QUOTATION APPROVAL", $noti, 'salesquoteapproval');
			}
			if ($data['quote_status'] == "APPROVED") {
				\DB::table('notifications_t')->where('reference_source_id', $_POST['quote_hdr_id'])->where('reference_source', 'SO QUOTATION APPROVAL')->update(['read/unread' => 'read']);
			}

			\DB::commit();

			return response()->json(array('status' => 'success', 'message' => $quote_status, 'id' => $id, 'lid' => $lid, 'auto_no' => $seqno[0]));
		} catch (\Illuminate\Database\QueryException $e) {
			$message = explode('(', $e->getMessage());
			$dbCode = rtrim($message[0], ']');
			$dbCode = trim($dbCode, '[');
			// dd($dbCode);
			\DB::rollback();

			return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
		}
	}
	public function getaddress($id = null)
	{
		/*
		$SQL = "SELECT site.customer_id, city.city_name, state.state_name, con.country_name,site.address,site.site_type,site.customer_site_name,site.contact_number,site.pincode,site.customer_site_id FROM `m_customer_sites_t` site left join m_countries_t con on ( con.country_id=site.`country` ) left join m_states_t state on ( state.state_id=site.`state`) left join m_cities_t city on( city.city_id=site.`city`) where site.customer_id='$id' group by site.site_type  order by site.customer_site_id desc";
		*/
		$SQL = "SELECT m_customer_sites_t.site_type,m_customer_sites_t.address,m_customer_sites_t.contact_number,m_customer_sites_t.customer_site_id,m_customer_sites_t.country,m_customer_sites_t.state,m_customer_sites_t.city ,m_customer_sites_t.customer_site_name,m_customer_sites_t.pincode,m_countries_t.country_name,m_states_t.state_name,m_cities_t.city_name FROM m_customers_t inner join m_customer_sites_t ON m_customer_sites_t.customer_id=m_customers_t.customer_id join m_countries_t on m_countries_t.country_id = m_customer_sites_t.country join m_states_t on m_states_t.state_id = m_customer_sites_t.state join m_cities_t on m_cities_t.city_id = m_customer_sites_t.city WHERE m_customer_sites_t.customer_site_id IN ( SELECT MAX(customer_site_id) FROM m_customer_sites_t where customer_id ='$id' GROUP BY m_customer_sites_t.site_type)";
		$result = \DB::select($SQL);
		$output = array();

		if (count($result) > 0 && count($result) == 1) {
			foreach ($result as $key => $value) {
				if ($value != '') {
					if ($value->site_type == "BILL_TO") {
						$output[0] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
						$output[1] = '';
					} else if ($value->site_type == "SHIP_TO") {
						$output[0] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
						$output[1] = '';
					} else {
						$output[0] = '';
						$output[1] = '';
					}
				}
			}

		} else if (count($result) > 0 && count($result) == 2) {
			foreach ($result as $key => $value) {
				if ($value != '') {
					if ($value->site_type == "BILL_TO") {
						$output[$key] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
					} else if ($value->site_type == "SHIP_TO") {
						$output[$key] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
					}

				}
			}

		} else if (count($result) > 2) {
			foreach ($result as $key => $value) {
				if ($value != '') {
					if ($value->site_type == "BILL_TO") {
						$output[$key] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
					} else if ($value->site_type == "SHIP_TO") {
						$output[$key] = $value->customer_site_name . "," . $value->address . "," . $value->city_name . "," . $value->state_name . "-" . $value->pincode . "," . $value->country_name . ". Contact No:" . $value->contact_number . "~" . $value->customer_site_id;
					}

				}
			}
		} else {
			$output[0] = '';
			$output[1] = '';
		}

		return $output;

	}

	public function soquoteupload(Request $request)
	{

		$salesquote_id = $_POST['salesid'];
		if ($request->hasfile('choosefile')) {
			foreach ($request->file('choosefile') as $file) {
				$name = $file->getClientOriginalName();

				$file->move(public_path() . '/uploads/soquoteupload/S' . $salesquote_id . '/', $name);
				$data[] = $name;
			}
		}
		$datas = \DB::select("select attachfile_name from s_quote_hdr_t where quote_hdr_id='$salesquote_id'");
		if ($datas[0]->attachfile_name != "") {
			$datas = json_decode($datas[0]->attachfile_name);
			$result = array_diff($datas, $_POST['file']);
			foreach ($result as $k => $v) {
				@unlink(public_path() . '/uploads/soquoteupload/S' . $salesquote_id . '/', $v);
			}
			$data = array_merge($_POST['file'], $data);

		}
		$attachfile_name = json_encode($data);
		\DB::update("update s_quote_hdr_t set attachfile_name='" . $attachfile_name . "' where quote_hdr_id='$salesquote_id'");
		$this->data['notymsg'] = "yes";
		return redirect('soquote');

	}

	public function soquoteuploaddata($id)
	{
		$data = \DB::select("select attachfile_name from s_quote_hdr_t where quote_hdr_id='$id'");

		if (!empty($data)) {
			$data = json_decode($data[0]->attachfile_name);
			return $data;
		}

	}
	public function delete($id = null)
	{

		$del_id = $id;

		//$soquotequery=DB::table('s_salesorder_hdr_t')->where('ar_quote_hdr_id',$quote_hdr_id)->get();
		$column = array('ar_quote_hdr_id');
		$table = array('s_salesorder_hdr_t');
		for ($i = 0; $i < count($table); $i++) {
			$j = 0;
			$query = DB::table($table[$i])->where($column[$i], $del_id)->get();
			//dd($query);
			if (count($query) > 0) {
				$j = 1;
				break;
			}
		}
		if ($j == 0) {
			/**Auditlog**/
			$action = "Delete";
			$this->auditlog($del_id, "Sale Quote", $action, $del_id, "s_quote_hdr_t");
			$query = DB::table('s_quote_hdr_t')->where('quote_hdr_id', $del_id)->delete();
		}

		if ($j == 1)
			return 1;
		else if ($j == 0)
			return 0;
	}
	function view($id = null, $msg = null)
	{


		$headerdata = \DB::table('s_quote_hdr_t as qh')
			->leftjoin('m_projects_t as p', 'qh.project_id', '=', 'p.project_id')
			->leftjoin('hr_employee_t as s', 'qh.salesperson_id', '=', 's.employee_id')
			->leftjoin('m_customers_t as c', 'qh.customerid', '=', 'c.customer_id')
			->select('p.project_name', 's.first_name', 'c.customer_name', 'c.customer_number', 'qh.*')
			->where('qh.quote_hdr_id', $id)
			->get();

		$this->data['quotedate'] = date(\Session::get('p_date_format'), strtotime($headerdata[0]->quote_date));
		$this->data['pricelist_name'] = $this->idname('pricelist_name', 'i_pricelist_hdr_t', 'pricelist_hdr_id', $headerdata[0]->quote_pricelist_id);
		$this->data['discount'] = $this->idname('discount_name', 'm_discounts_hdr_t', 'ar_discount_hdr_id', $headerdata[0]->discount_id);
		$this->data['currency'] = $this->idname('currency_code', 'f_account_currency_t', 'account_currency_id', $headerdata[0]->currency_id);
		$this->data['paymentterm'] = $this->idname('payment_term_name', 'm_payment_terms_t', 'payment_term_id', $headerdata[0]->payment_term_id);
		$this->data['paymentmethod'] = $this->idname('payment_method_name', 'm_payment_methods_t', 'payment_method_id', $headerdata[0]->payment_method_id);
		$this->data['freight'] = $this->idname('carrier_name', 'm_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', $headerdata[0]->frieghtcarriers_hdr_id);
		$this->data['freightterm'] = $this->idname('fob_point_name', 'm_frieghtterms_t', 'frieghtterm_id', $headerdata[0]->frieghtterm_id);
		$this->data['delivery'] = $this->idname('delivery_term_name', 'm_delivery_terms_t', 'delivery_terms_id', $headerdata[0]->delivery_terms_id);
		$linesdata = \DB::table('s_quote_hdr_t as qh')
			->leftjoin('s_quote_lines_t as ql', 'qh.quote_hdr_id', '=', 'ql.quote_hdr_id')
			->leftjoin('m_products_t as pr', 'ql.product_id', '=', 'pr.product_id')
			->leftjoin('m_uom_codes_t as uom', 'ql.uom_code_id', '=', 'uom.uom_code_id')
			->leftjoin('m_tax_group_t as tx', 'ql.tax_group_id', '=', 'tx.tax_group_id')
			->leftjoin('f_gst_code_hdr_t as hsn', 'ql.hsn_code', '=', 'hsn.gst_code_hdr_id')
			->leftjoin('m_manufacturer_partno_t as part', 'ql.part_no', '=', 'part.manufacturer_partno_id')
			->select('pr.concatenated_product', 'uom.uom_code', 'tx.tax_group_name', 'pr.product_code', 'ql.line_no', 'qh.quote_hdr_id', 'ql.product_description', 'ql.qty', 'ql.unit_price', 'ql.discount_percentage', 'ql.discount_amount', 'ql.tax_amount', 'ql.line_total', 'ql.promised_date', 'ql.tax_excemption', 'ql.comments', 'hsn.classification_code', 'part.part_no')
			->where('qh.quote_hdr_id', $id)
			->get();
		$this->data['headerdata'] = $headerdata[0];
		$this->data['headerdata']->bill_to_address_id = $this->addressget($headerdata[0]->bill_to_address_id);
		$this->data['headerdata']->ship_to_address_id = $this->addressget($headerdata[0]->ship_to_address_id);
		$this->data['linesdata'] = $linesdata;
		//dd($this->data);
		if ($msg == "welcome") {
			return $this->data;
		} else {
			// dd($headerdata);
			$this->data['return_url'] = $_GET['return'];
			return view('soquote.view', $this->data);
		}
	}
	public function getsocustomeralldata()
	{
		$id = $_GET['id'];

		if ($_GET['id'] != '') {
			$cusdata = \DB::select("select * from m_customers_t where customer_id=$id");
			return $cusdata;
		} else {
			return 0;
		}
	}

	public function getPrint($id = null)
	{

		/********************** header with Customer Address ************************/

		$header = \DB::table('s_quote_hdr_t as qh')
			->leftjoin('m_projects_t as p', 'qh.project_id', '=', 'p.project_id')
			->leftjoin('hr_employee_t as s', 'qh.salesperson_id', '=', 's.employee_id')
			->leftjoin('m_customers_t as c', 'qh.customerid', '=', 'c.customer_id')
			->select('p.project_name', 'qh.reference_id', 's.first_name', 'c.customer_name', 'c.customer_id', 'c.billing_address', 'c.contact_person', 'c.contact_number', 'qh.*')
			->where('qh.quote_hdr_id', $id)
			->get();

		if (!empty($header)) {
			$this->data['quote_hdr_id'] = $header[0]->quote_hdr_id;
			$this->data['customer_name'] = $header[0]->customer_name;
			$this->data['quote_no'] = $header[0]->quote_no;
			$this->data['quote_date'] = $header[0]->quote_date;
		} else {
			$this->data['quote_hdr_id'] = '';
			$this->data['customer_name'] = '';
			$this->data['quote_no'] = '';
			$this->data['quote_date'] = '';
		}

		/****************************** End ****************************************/


		/********************* inquiry no and date *********************************/
		if ($header[0]->reference_id != 0) {
			$inquiry = \DB::table('s_inquiry_hdr_t')
				->select('*')
				->where('so_inquiry_hdr_id', $header[0]->reference_id)
				->get();


			if (count($inquiry) > 0) {
				$this->data['inquiry_no'] = $inquiry[0]->inquiry_no;
				$this->data['inquiry_date'] = $inquiry[0]->inquiry_date;
			} else {
				$this->data['inquiry_no'] = '';
				$this->data['inquiry_date'] = '';
			}
		} else {
			$this->data['inquiry_no'] = '';
			$this->data['inquiry_date'] = '';
		}

		/*********************************  End ************************************/

		/*************************** Customer address ******************************/

		$customer_address = \DB::table('m_customer_sites_t')->where('customer_id', $header[0]->customer_id)->get();

		if (count($customer_address) > 0) {

			$this->data['address_c'] = $customer_address[0]->address;
			$this->data['city_c'] = $this->getCity($customer_address[0]->city);
			$this->data['state_c'] = $this->getState($customer_address[0]->state);
			$this->data['country_c'] = $this->getCountry($customer_address[0]->country);
			$this->data['pincode_c'] = $customer_address[0]->pincode;
			$this->data['contact_number_c'] = $customer_address[0]->contact_number;
			$this->data['contact_name_c'] = $customer_address[0]->contact_person;
			$this->data['gst_number'] = $customer_address[0]->gst_no;
		} else {
			$this->data['address_c'] = '';
			$this->data['city_c'] = '';
			$this->data['state_c'] = '';
			$this->data['country_c'] = '';
			$this->data['pincode_c'] = '';
			$this->data['contact_number_c'] = '';
			$this->data['contact_name_c'] = '';
			$this->data['gst_number'] = '';
		}

		/******************* end *****************************/



		/******************* lines ***************************/

		$linesdata = \DB::table('s_quote_hdr_t as qh')
			->leftjoin('s_quote_lines_t as ql', 'qh.quote_hdr_id', '=', 'ql.quote_hdr_id')
			->leftjoin('m_products_t as pr', 'ql.product_id', '=', 'pr.product_id')
			->leftjoin('m_uom_codes_t as uom', 'ql.uom_code_id', '=', 'uom.uom_code_id')
			->leftjoin('m_tax_group_t as tx', 'ql.tax_group_id', '=', 'tx.tax_group_id')
			->select('pr.concatenated_product', 'pr.hsn_code', 'uom.uom_code', 'tx.tax_group_id', 'tx.tax_group_name', 'tx.display_name', 'ql.uom_code_id', 'ql.line_no', 'qh.quote_hdr_id', 'ql.product_description', 'ql.tax_excemption', 'ql.qty', 'ql.unit_price', 'ql.discount_percentage', 'ql.discount_amount', 'ql.tax_amount', 'ql.line_total', 'ql.promised_date', 'ql.comments')
			->where('qh.quote_hdr_id', $id)
			->get();


		$sub_total = 0;
		$total_discount = 0;
		$tax_amount = 0;
		$tax_gst = 0;
		$rtotal = 0;

		foreach ($linesdata as $key => $value) {

			//$quotelines[$key]['hsn_code']=$value->hsn_code;
			$quotelines[$key]['lineno'] = $value->line_no;
			$quotelines[$key]['productid'] = $value->concatenated_product;
			$quotelines[$key]['hsn_code'] = $this->getHsncode($value->hsn_code);
			$quotelines[$key]['uom_code'] = $value->uom_code;
			$quotelines[$key]['tax_excemption'] = $value->tax_excemption;
			$quotelines[$key]['gst'] = $value->tax_group_name;
			$quotelines[$key]['qty'] = $value->qty;
			$quotelines[$key]['unitprice'] = $value->unit_price;
			$tax_amount += $value->tax_amount;
			$quotelines[$key]['tax_amount'] = $tax_amount;
			$total = $value->unit_price * $value->qty;
			$rtotal += ($value->unit_price * $value->qty) * 28 / 100;
			$quotelines[$key]['rowtotal'] = $total + $rtotal;
			//$quotelines[$key]['rowtotal']=$rtotal;
			$quotelines[$key]['total'] = $total;
			$quotelines[$key]['discount'] = $value->discount_amount;
			$sub_total += $quotelines[$key]['rowtotal'];
			$total_discount += $value->discount_amount;
			$quotelines[$key]['total_discount'] = $total_discount;
			$quotelines[$key]['sub_total'] = $sub_total;
			$quotelines[$key]['net_amount'] = $quotelines[$key]['sub_total'] - $quotelines[$key]['total_discount'];
			//$quotelines[$key]['frieght_amount']=0;
			$quotelines[$key]['other_tax'] = $header[0]->packaging_charges + $header[0]->transport_charges + $header[0]->unloading_charges + $header[0]->insurance_charges;
			$dis_amt = $quotelines[$key]['total'] * ($value->discount_percentage / 100);

			$amount = ($dis_amt * $value->qty);
			$quotelines[$key]['amount'] = $amount;
			$quotelines[$key]['discount'] = $dis_amt;
			$disc = $value->discount_amount * $value->qty;
			$tax_gst = $quotelines[$key]['gst'];
			$number = $tax_gst;
			$this->data['sgst_val'] = $number;
			$this->data['cgst_val'] = $number;
			$invoicelines[$key]['amount'] = $amount;
			$sub_total = $amount;

		}



		/************************ end ************************/


		/****************** location based address ************/
		$address = $this->getLocationwiseaddress($header[0]->location_id);

		if (!empty($address)) {
			$location_name = $address[0]->location_name;
			$this->data['location_name_l'] = $location_name;
			$address1 = $address[0]->address;
			$this->data['address_l'] = $address1;
			$street = $address[0]->street_name;
			$this->data['street_name_l'] = $street;
			$location = $address[0]->location_name;
			$this->data['location_l'] = $location;
			$area = $address[0]->area;
			$this->data['area_l'] = $area;
			$this->data['pincode'] = $address[0]->pincode;
			//GET COMPANY ADDRESS
			$this->data['company_gst_no_l'] = $address[0]->gst_no;
			$this->data['city_l'] = $this->getCity($address[0]->city_id);
			$this->data['state_l'] = $this->getState($address[0]->state_id);
			$this->data['country_l'] = $this->getCountry($address[0]->country_id);

		} else {

			$this->data['location_name_l'] = '';
			$this->data['address_l'] = '';
			$this->data['street_name_l'] = '';
			$this->data['location_l'] = '';
			$this->data['area_l'] = '';
			//GET COMPANY ADDRESS
			$this->data['company_gst_no_l'] = '';
			$this->data['city_l'] = '';
			$this->data['state_l'] = '';
			$this->data['country_l'] = '';
			$this->data['pincode'] = $address[0]->pincode;

		}

		$this->data['company_address'] = $this->data['address_l'] . "," . $this->data['city_l'] . "," . $this->data['state_l'] . "," . $this->data['country_l'] . "-" . $this->data['pincode'];
		/********************* end ***************************/


		/**************** location based company *************/

		$company = $this->getCompany($header[0]->company_id);
		if (!empty($company)) {
			$company_name = $company[0]->company_name;
			$gst_no = $company[0]->gst_no;
			$cin_no = $company[0]->cin_no;
			$this->data['company_name'] = $company_name;
			$this->data['gst_no'] = $gst_no;
			$this->data['cin_no'] = $cin_no;
			$this->data['contact_no'] = $company[0]->contact_no;
			$this->data['email_id'] = $company[0]->email_id;
			$this->data['website_address'] = $company[0]->website_address;
		}
		$this->data['company_logo'] = \Session::get('companylogo');

		/**************** end ********************************/

		/********************* Gst Tax Calculation ***********/

		$gstdata = \DB::select("select * from s_quote_lines_t where quote_hdr_id='" . $id . "' and tax_group_id!='0' group by tax_group_id");


		$gstvalue = array();
		$sgst = 0;
		$cgst = 0;
		$gsttotal = 0;
		$grand_total = 0;
		$tax_gst = 0;
		foreach ($gstdata as $gst_key => $gst_value) {



			//$lines=\DB::table("s_invoice_lines_t")->where('invoice_hdr_id',$id)->get();
			//dd($lines);
			$tax = \DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id', $gst_value->tax_group_id)->get();
			//dd($tax);
			if ($tax->isNotEmpty())
				$display_name = $tax[0]->display_name;

			$gst = $this->getGst($gst_value->tax_group_id, $gst_value->quote_hdr_id);

			//dd($gst[0]->tax_amount);
			$gstvalue[$gst_key]['sgcgst'] = array('SGST', 'CGST');
			$gstvalue[$gst_key]['per'] = "%";
			$gstvalue[$gst_key]['gst'] = $display_name / 2;

			$gstvalue[$gst_key]['sgst'] = $gstvalue[$gst_key]['gst'];
			$gstvalue[$gst_key]['cgst'] = $gstvalue[$gst_key]['gst'];
			$gstvalue[$gst_key]['tax_amount'] = $gst['tax_amount'];
			$gstvalue[$gst_key]['sgst_val'] = $gst['tax_amount'] / 2;

			$tax_gst += $gstvalue[$gst_key]['sgst_val'];
			$gstvalue[$gst_key]['cgst_val'] = $gst['tax_amount'] / 2;

			$gstvalue[$gst_key]['amount'] = $gst['amount'];
			$gstvalue[$gst_key]['gst_id'] = $gst_value->tax_group_id;
			$gstvalue[$gst_key]['gst_val'] = $gst['amount'] * ($display_name / 100);
			$gsttotal = $gstvalue[$gst_key]['gst_val'] + $gsttotal;

			$gstvalue[$gst_key]['sgst_tax'] = $gstvalue[$gst_key]['sgcgst'][0] . " " . $gstvalue[$gst_key]['sgst'] . "" . $gstvalue[$gst_key]['per'] . " : " . $gstvalue[$gst_key]['sgst_val'];

			$gstvalue[$gst_key]['cgst_tax'] = $gstvalue[$gst_key]['sgcgst'][1] . " " . $gstvalue[$gst_key]['cgst'] . "" . $gstvalue[$gst_key]['per'] . " : " . $gstvalue[$gst_key]['cgst_val'];


		}

		/************************** end **********************/

		$this->data['header'] = $header;
		$this->data['linesdata'] = $quotelines;
		$this->data['gst'] = $gstvalue;

		$this->data['print'] = "PRINT";
		$this->data['print_val'] = '1';
		$terms_condition = \DB::table('a_rpt_displayelements_hdr_t')->leftjoin('a_rpt_displayelements_lines_t', 'a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id', '=', 'a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id')->select('a_rpt_displayelements_lines_t.element_content')->where('a_rpt_displayelements_hdr_t.report_source', 119)->where('a_rpt_displayelements_lines_t.element_name', 'TERMS&CONDITIONS')->get();
		if (count($terms_condition) > 0) {
			$this->data['terms_condition'] = $terms_condition;
		} else {
			$this->data['terms_condition'] = [];
		}
		if (isset($_GET['mail'])) {

			if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
				Config::set('mail.username', \Session::get('user_email'));
				Config::set('mail.password', \Session::get('user_password'));
			}
			$this->data['print'] = "PRINTS";
			\Mail::send('soquote.arsalesquoterpt_print', $this->data, function ($message) {

				if (!empty($_GET['cc'])) {

					$cc = explode(',', $_GET['cc']);
					$message->cc($cc);
				} else {
					$cc = array();
				}
				$msg = $_GET['msg'];
				$tomail = rtrim($_GET['mail'], ",");
				$message->to(explode(",", $tomail));
				$message->setBody($msg);
				$message->subject("Sales Quote" . $this->data['quote_no']);
				$retuen = DB::table('s_quote_hdr_t')->where('quote_hdr_id', $this->data['quote_hdr_id'])->get();
				$message->attach('uploads/soquoteupload/S_' . stripslashes($this->data['quote_no']) . '.pdf');
				if ($retuen[0]->attachment_file != '') {
					$file_a = json_decode($retuen[0]->attachment_file);
					foreach ($file_a as $k1 => $v1) {
						$message->attach('uploads/soquoteupload/S' . $this->data['quote_hdr_id'] . '/' . $v1);
					}
				}

			});

			return 1;


		}



		if (isset($_GET['mails'])) {

			$this->data['print'] = "PRINTS";
			return view('soquote.printform', $this->data);
		}

		return view('soquote.printform', $this->data);

	}

	public function soquotefilesave(Request $request)
	{
		// dd($_POST);
		if ($request->hasfile('email_attachment')) {
			File::deleteDirectory(public_path('uploads/soquoteupload/S' . $_POST['quote']));

			foreach ($request->file('email_attachment') as $file) {
				$name = $file->getClientOriginalName();

				$file->move(public_path() . '/uploads/soquoteupload/S' . $_POST['quote'] . '/', $name);
				$data[] = $name;
			}
			$attachfile_name = json_encode($data);
			\DB::update("update s_quote_hdr_t set attachment_file='" . $attachfile_name . "' where quote_hdr_id=" . $_POST['quote']);
			return 1;
		} else {
			$var = File::deleteDirectory(public_path('uploads/soquoteupload/S' . $_POST['quote']));

			\DB::update("update s_quote_hdr_t set attachment_file='' where quote_hdr_id=" . $_POST['quote']);
			return 2;
		}

	}
	public function gettax($pid)
	{

		$pro_details = \DB::table("m_products_t")->select('trx_uom_id', 'hsn_code')->where('product_id', $pid)->get();

		if (count($pro_details) > 0) {
			$prd_data['uom_code_id'] = $pro_details[0]->trx_uom_id;
			$hsn_code = $pro_details[0]->hsn_code;
		} else {
			$prd_data['uom_code_id'] = 0;
			$hsn_code = 0;
		}

		$date = date('Y-m-d');
		$tax = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$hsn_code' and start_date<='$date' and end_date>='$date' and active='Yes'");

		if (count($tax) > 0) {
			$prd_data['taxgroup_id'] = $tax[0]->tax_group_id;

			$tax1 = \DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id', $tax[0]->tax_group_id)->get();
			$prd_data['display_name'] = $tax1[0]->display_name;
			return $prd_data;

		} else {
			$prd_data['uom_code_id'] = 0;
			$prd_data['taxgroup_id'] = 0;
			$prd_data['display_name'] = 1;
			return 1;
		}

	}

	function getGst3($tax_id = null)
	{

		$tax = DB::table('m_tax_group_t')->where('tax_group_id', $tax_id)->get();

		if (!empty($tax)) {

			return $tax;

		} else {
			return 0;
		}

	}

	function getHsncode($hsn_id = null)
	{
		$hsn_code = \DB::select("select * from f_gst_code_hdr_t where gst_code_hdr_id='$hsn_id'");
		if (!empty($hsn_code)) {
			return $hsn_code[0]->classification_code;
		} else {
			return 0;
		}
	}

	public function getcustomerdatas($id = null)
	{
		if ($id != "") {
			$cusdata = \DB::select("select * from m_customers_t where customer_id=$id");
			return $cusdata;
		}
	}

	function getGst($tax_qroup_id, $inv_hdr_id)
	{

		$sql = array();
		$location = \Session::get('ss_defaultloc_id');
		$sql = \DB::SELECT("select * from s_quote_lines_t where quote_hdr_id='" . $inv_hdr_id . "'  and tax_group_id='" . $tax_qroup_id . "'");

		if (!empty($sql)) {
			$hsn = array();
			$gst = array();
			$sub_total = 0;
			$tax_amt = 0;
			foreach ($sql as $key => $value) {


				//$hsn[]=$value->hsn_code;
				$dis_amt = ($value->unit_price) - ($value->unit_price * ($value->discount_percentage / 100));
				$amount = $dis_amt * $value->qty;
				$sub_total = $sub_total + $amount;
				$tax_amt += $value->tax_amount;
			}
			//$hsn_code=implode(' , ',$hsn);
			//$gst['hsn_code']=$hsn_code;
			//$gst['total']=$hsn_code;
			$gst['amount'] = $sub_total;
			$gst['tax_amount'] = $tax_amt;
			return $gst;
		} else {
			return 0;
		}
	}



	function pricelist_cust($customer_id)
	{

		$result = \DB::table('m_customers_t')->select('pricelist_id as pricelist_id')->where('customer_id', $customer_id)->get();

		if ($result->isNotEmpty()) {
			return $pricelist = $result[0]->pricelist_id;
		} else {
			return 0;
		}

	}

	function getLocationwiseaddress($location)
	{

		$sql = \DB::SELECT("select * from m_location_t where location_id=" . $location . "");
		if (!empty($sql)) {
			return $sql;
		}
		return 0;
	}

	function getInquiry($id = null)
	{

		$sql = array();
		$sql = \DB::SELECT("select * from s_inquiry_hdr_t where so_inquiry_hdr_id=1");

		if (!empty($sql)) {
			return $sql;
		} else {
			return 0;
		}
	}

	function getCompany($com = null)
	{
		$sql = array();

		$sql = \DB::SELECT("SELECT company_id,company_name,gst_no,cin_no,email_id,contact_no,website_address FROM `m_company_t` WHERE `company_id`=" . $com . "");
		if (!empty($sql)) {
			return $sql;
		} else {
			return 0;
		}
	}

	function pricelist($customer, $product_id)
	{
		$result = \DB::table('m_customers_t')->select('pricelist_id as pricelist_id')->where('customer_id', $customer)->get();
		//dd($result);
		if ($result->isNotEmpty()) {
			$pricelist = $result[0]->pricelist_id;
			$result_pri = \DB::table('i_pricelist_lines_t')->select('unit_price')->where('pricelist_hdr_id', $pricelist)->where('product_id', $product_id)->get();
			if ($result_pri->isNotEmpty()) {
				return $result_pri[0]->unit_price;
			} else {
				return '0.00';
			}

		} else {
			return '0.00';

		}
	}
	public function tabledata()
	{
		$table = \DB::table('users')->get();
		$this->data['datas'] = $table;
		return $table;
	}


	public function getshowcol(Request $request)
	{
		$this->modelname = new Soquote();
		$id = '';
		$data = $this->modelname->subgridRead($id);
		return $data['label_data'];

	}



	function customermaildetails($id = null)
	{
		if (isset($_GET['type'])) {
			if ($_GET['type'] == "customer") {


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
				$result = array();
				if (!empty($result1)) {
					//dd($result1);
					foreach ($result1 as $key => $value) {

						if ($value->email_id != "") {

							$contactperson = explode(',', $value->contact_person);
							$contact_number = explode(',', $value->contact_number);
							$email = explode(',', $value->email_id);
							foreach ($email as $k => $v) {
								$contact = '';
								$number = '';
								if (isset($contactperson[$k])) {
									$contact = $contactperson[$k];
								}
								if (isset($contact_number[$k])) {
									$number = $contact_number[$k];
								}
								$id = $value->customer_site_id;
								$result[] = array($id, $contact, $number, $v);

							}

						}

					}

				} else {
					$result[] = array();
				}

				return $result;
			} else {
				$sql = \DB::select("select employee_id,email,first_name,work_telephone_number from hr_employee_t where employee_id=" . $id);
				if (!empty($sql)) {

					foreach ($sql as $key => $value) {

						if ($value->email != "") {

							$contactperson = $value->first_name;
							$contact_number = $value->work_telephone_number;
							$email = $value->email;

							$contact = '';
							$number = '';
							if ($contactperson != "") {
								$contact = $contactperson;
							}
							if ($contact_number != "") {
								$number = $contact_number;
							}
							$id = $value->employee_id;
							$result[] = array($id, $contact, $number, $email);



						}

					}

				} else {
					$result[] = array();
				}

				return $result;
			}
		}
	}






	function findPrimarykey($table)
	{
		$primaryKey = '';
		foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
			$primaryKey = $key->Field;
		}
		return $primaryKey;
	}

	public function uomcode($product_id = null)
	{
		$uom = \DB::table('m_products_t')->select('trx_uom_id')->where('product_id', $product_id)->get();
		$qoh = \DB::table('i_qoh_detail_t')->select('qoh_trx_qty as sum(qoh_qty)')->where('product_id', $product_id)->get();

		$uom_code_id = $uom[0]->trx_uom_id;
		if ($uom->isNotEmpty()) {
			if ($uom_code_id != 0) {
				$uomcode = $uom_code_id;
			} else {
				$uomcode = '';
			}
			return $uomcode;
		} else {
			return '';
		}
		$qoh_qty = $qoh[0]->qoh_qty;
		if ($qoh->isNotEmpty()) {
			if ($qoh_qty != 0) {
				$uomcode = $qoh_qty;
			} else {
				$uomcode = '';
			}
			return $uomcode;
		} else {
			return '';
		}




	}

	public function uomcodes($product_id = null, $product = null)
	{
		$uom = \DB::table('m_products_t')->select('*')->where('product_id', $product_id)->get();

		// $qoh=\DB::table('i_qoh_detail_t')->select('sum(qoh_trx_qty) as qoh_qty')->where ('product_id',$product_id)->get();
		$org = \Session::get('organization');

		$qoh = \DB::Select("select sum(qoh_trx_qty) as qoh_qty from i_qoh_detail_t where product_id='" . $product_id . "' and subinventory_id='" . $uom[0]->subinventory_id . "' and locator_id='" . $uom[0]->sublocator_id . "' and organization_id='" . $org . "' group by product_id ");

		$data = array();


		if ($uom->isNotEmpty()) {
			$uom_code_id = $uom[0]->trx_uom_id;
			if ($uom_code_id != 0) {
				$data['uomcode'] = $uom_code_id;
				$data['sub'] = $uom[0]->subinventory_id;
				$data['subloc'] = $uom[0]->sublocator_id;
			} else {
				$data['uomcode'] = '';
				$data['sub'] = '';
				$data['subloc'] = "";
			}

		} else {
			$data['uomcode'] = "";
			$data['sub'] = '';
			$data['subloc'] = "";
		}

		if (!empty($qoh)) {

			if ($qoh[0]->qoh_qty != 0) {
				$data['qoh'] = $qoh[0]->qoh_qty;
			} else {
				$data['qoh'] = '';
			}

		} else {
			$data['qoh'] = '';
		}
		return $data;





	}




	public function getStatus($id = null, $type = null)
	{
		$soquote_id = $id;
		$soquotequery = DB::table('s_quote_hdr_t')->where('quote_hdr_id', $soquote_id)->get();

		if ($type == "savestatus")
			$status_type = $soquotequery[0]->savestatus;
		else if ($type == "statustype")
			$status_type = $soquotequery[0]->quote_status;


		return $status_type;

	}


	public function copyquotedata($id = null)
	{
		$this->data['id'] = $id;
		$table = \DB::table('s_quote_hdr_t')->where('quote_hdr_id', $id)->get();
		$this->data['row'] = $table[0];
		$this->data['row']->quote_no = "";
		$this->data['row']->quote_hdr_id = "";

		$tablelines = \DB::table('s_quote_lines_t')->where('quote_hdr_id', $id)->get();
		$this->data['linedata'] = $tablelines;
		$this->data['customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_name', $table[0]->customer_id, 'and savestatus="SAVE"');
		$this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
		$this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
		$this->data['quote_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->quote_pricelist_id, ' and price_list_type="Sales"');
		$this->data['salesperson_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->salesperson_id);
		$this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
		$this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
		$this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
		$this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_name', 'customer_name');
		$this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
		$this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');

		if (count($this->data['linedata']) >= 1) {
			foreach ($this->data['linedata'] as $key => $value) {
				$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'soquote');
				$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
				$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
			}
		}
		$route_edit = \Request::route()->getName();
		if ($route_edit == "soquotecreate") {
			$this->data['edit_route'] = $route_edit;
			//$this->data['status']="";
		} else {
			$this->data['edit_route'] = 'soquotecreate';
			//$this->data['status']="INITIATED";
		}

		return view('soquote.form', $this->data);

	}


	public function discountjcombo()
	{
		$compy = \Session::get('companyid');
		$select = \DB::select("select DISTINCT CONCAT (discount_name) as name,default_discount_amount ,ar_discount_hdr_id  as option from m_discounts_hdr_t where company_id=" . $compy . " and active='Yes'");
		$html = "<option value=''>-- Please Select --</option>";
		foreach ($select as $val) {

			$html .= "<option value='" . $val->option . "'  data-display='" . $val->default_discount_amount . "' >" . $val->name . "</option>";

		}
		return $html;
	}
	public function copysalesquote(Request $request)
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

		$this->data['route'] = 'copysalesquote';
		$this->data['status'] = "";
		$this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_name', 'customer_name');
		$this->data['pageMethod'] = \Request::route()->getName();
		return view('soquote.table', $this->data);
	}

	public function addressget($id = null)
	{
		$sql = "SELECT
m_customer_sites_t.address,
m_customer_sites_t.customer_site_name,
m_customer_sites_t.pincode,
m_countries_t.country_name,
m_states_t.state_name,
m_cities_t.city_name
FROM `m_customer_sites_t`
left join m_countries_t ON
m_customer_sites_t.country=m_countries_t.country_id
left join m_states_t ON m_customer_sites_t.state=m_states_t.state_id
left join m_cities_t ON m_customer_sites_t.city=m_cities_t.city_id
where m_customer_sites_t.customer_site_id='$id' ";
		$result = \DB::select($sql);
		$address = '';
		if (count($result) > 0) {
			if ($result[0]->pincode == 0) {
				$address = $result[0]->customer_site_name . ',' . $result[0]->address . ',' . $result[0]->city_name . ',' . $result[0]->state_name . ',' . $result[0]->country_name;
			} else {
				$address = $result[0]->customer_site_name . ',' . $result[0]->address . ',' . $result[0]->city_name . ',' . $result[0]->state_name . '-' . $result[0]->pincode . ',' . $result[0]->country_name;
			}
		}
		return $address;
	}

}
