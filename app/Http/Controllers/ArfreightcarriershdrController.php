<?php
namespace App\Http\Controllers;
use App\Arfreightcarriershdr;
use App\Arfreightcarrierslines;
use Illuminate\Http\Request;
use Validator, DB, session;
use Yajra\DataTables\DataTables;

class ArfreightcarriershdrController extends Controller
{

	public $module = "freightcarriershdr";

	public function __construct()
	{
		$this->data = array();

		$this->table = "m_frieghtcarriers_hdr_t";
		$this->subtable = "m_frieghtcarriers_lines_t";
		$this->pageModule = "freightcarriershdr";
		$this->model = new Arfreightcarriershdr;
		$this->submodel = new Arfreightcarrierslines;
		$this->data['pageModule'] = $this->pageModule;
		$this->data['pageMethod'] = \Request::route()->getName();
		$this->data['pageFormtype'] = 'ajax';
		if ($this->data['pageMethod'] == "purchasefreightcarriershdr") {
			$this->pageModule = "purchasefreightcarriershdr";
			$this->data['pageModule'] = $this->pageModule;
		} else {
			$this->pageModule = "freightcarriershdr";
			$this->data['pageModule'] = $this->pageModule;
		}
		$this->data['urlmenu'] = $this->indexs();

	}
	public function index(Request $request)
	{
		// restrict illegal menu entry purpose - VIGNESH M

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


		$this->data['urlname'] = \Request::route()->getName();

		if ($this->data['urlname'] == "freightcarriershdr") {
			$this->data['index_data'] = "Sales";
		} else {
			$this->data['index_data'] = "Purchase";
		}
		$table = \DB::table('m_frieghtcarriers_hdr_t')->get();
		$this->data['datas'] = $table;
		$tablelines = \DB::table('m_frieghtcarriers_lines_t')->get();

		$this->data['pageMethod'] = \Request::route()->getName();

		return view('freightcarriershdr.table', $this->data);
	}

	// table data
	public function getfreightGridData($type = null)
	{

		$wh = '';

		if ($type != '') {
			$wh .= " and m_frieghtcarriers_hdr_t.source_type_id='" . $type . "'";
		}

		$org = \Session::get('organization');
		$com = \Session::get('companyid');

		$SQL = "SELECT
m_frieghtcarriers_hdr_t.`ar_frieghtcarriers_hdr_id`,
m_frieghtcarriers_hdr_t.`carrier_name`,
m_frieghtcarriers_hdr_t.`remarks`,
m_frieghtcarriers_hdr_t.`source_type_id`,
m_frieghtcarriers_hdr_t.`organization_id`,
m_frieghtcarriers_hdr_t.`start_date`,
m_frieghtcarriers_hdr_t.`end_date`,
m_frieghtcarriers_hdr_t.`active`,
f_account_currency_t.currency_code,
m_location_t.location_name
FROM `m_frieghtcarriers_hdr_t` left join m_location_t on m_location_t.location_id = m_frieghtcarriers_hdr_t.location_id
left join f_account_currency_t on f_account_currency_t.account_currency_id = m_frieghtcarriers_hdr_t.default_currency
where  1=1 and m_frieghtcarriers_hdr_t.company_id=$com  $wh order by m_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id DESC";

		$result = \DB::select($SQL);

		return DataTables::of($result)->make(true);
	}



	public function create($id = null)
	{


		$urlName = \Request::route()->getName();
		$this->data['pageMethod'] = "freightcarriershdr";

		if ($urlName == "freightcarriershdrcreate") {
			$this->data['source_type_id'] = "Sales";
		} else {
			$this->data['source_type_id'] = "Purchase";
		}
		$this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'freightcarriershdr')->get();

		$this->data['url_type'] = $urlName;
		$this->data['urlName'] = '';

		if ($id == '0') {
			$this->data['row'] = (object) array();
			$this->data['row']->ar_frieghtcarriers_hdr_id = "";
			$this->data['row']->carrier_number = "";
			$this->data['row']->carrier_name = "";



			$this->data['row']->start_date = date("d-m-Y");
			$this->data['row']->end_date = "";
			$this->data['row']->remarks = "";
			$this->data['row']->active = "";
			$this->data['row']->save_status = "";

			$this->data['row']->description = "";

			$this->data['row']->default_currency = "";
			$this->data['row']->shipping_method = "";
			$this->data['row']->charging_uom = "";
			$this->data['row']->charging_rating = "";
			$this->data['row']->charging_rating_value = "";
			$this->data['row']->minimum_time = "";
			$this->data['row']->minimum_distance = "";
			$this->data['row']->reliablity_percentage = "";


			$this->data['id'] = '';
			$this->data['pagemode'] = 'create';
			$this->data['linedata'] = array();


			$this->data['default_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '');

			$linestable = \DB::table('m_frieghtcarriers_lines_t')->where('ar_frieghtcarriers_hdr_id', $id)->get();
			$this->data['linedata'] = $linestable;

			$this->data['country'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', '');
			$this->data['state'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', '');
			$this->data['city'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', '');
			$this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

			$location = \Session::get('location');
			$comp = \Session::get('companyid');
			$company = \DB::table('m_company_line_t')->where('companyid', $comp)->get();

			if (count($company) > 0) {
				$c = '';
				foreach ($company as $k => $y) {
					$c .= $y->locationid . ",";
				}

				$c = rtrim($c, ',');

				$this->data['location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $location, " and location_id in(" . $c . ")");
			} else {
				$this->data['location_id'] = $this->jCombologin('m_location_t', 'location_id', 'location_name', '');
			}


		} else {

			$this->data['id'] = $id;
			$this->data['pagemode'] = 'edit';

			$table = \DB::table('m_frieghtcarriers_hdr_t')->where('ar_frieghtcarriers_hdr_id', $id)->get();

			$this->data['row'] = $table[0];
			if ($this->data['row']->start_date != '0000-00-00')
				$this->data['row']->start_date = $this->data['row']->start_date;
			else
				$this->data['row']->start_date = '';
			if ($this->data['row']->end_date != '0000-00-00')
				$this->data['row']->end_date = $this->data['row']->end_date;
			else
				$this->data['row']->end_date = '';
			$this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
			$linestable = \DB::table('m_frieghtcarriers_lines_t')->where('ar_frieghtcarriers_hdr_id', $id)->get();
			$this->data['linedata'] = $linestable;

			foreach ($this->data['linedata'] as $key => $value) {
				$this->data['default_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $table[0]->default_currency);

				$this->data['linedata'][$key]->state = $this->jCombologin('m_states_t', 'state_id', 'state_name', $value->state);
				$this->data['linedata'][$key]->city = $this->jCombologin('m_cities_t', 'city_id', 'city_name', $value->city);
				$this->data['linedata'][$key]->country = $this->jCombologin('m_countries_t', 'country_id', 'country_name', $value->country);


			}

			$location = \Session::get('location');
			$comp = \Session::get('companyid');
			$company = \DB::table('m_company_line_t')->where('companyid', $comp)->get();


			if (count($company) > 0) {
				$c = '';
				foreach ($company as $k => $y) {
					$c .= $y->locationid . ",";
				}

				$c = rtrim($c, ',');

				$this->data['location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $table[0]->location_id, " and location_id in(" . $c . ")");
			} else {
				$this->data['location_id'] = $this->jCombologin('m_location_t', 'location_id', 'location_name', '');
			}
		}
		// dd($this->data);
		return view('freightcarriershdr.form', $this->data);

	}

	/* Save function */
	public function save(Request $request)
	{
	

		$form = $request->all();
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
		$data['active'] =$request->active;
		$lines_data = $this->validatePost($form, $this->subtable, 'lines');
		$data['organization_id'] = \Session::get('organization');
		$data['company_id'] = \Session::get('companyid');
		$data['location_id'] = \Session::get('location');
		$data['created_by'] = $_POST['created_by'];

		\DB::beginTransaction();

		$msg = "Saved Successfully";

		try {

			$id = $this->model->insertRow($data);
			$lid = $this->submodel->subgridSave($lines_data, $id);

			\DB::commit();

			$action = "Create";
			return response()->json(array('status' => 'success', 'message' => $msg, 'id' => $id, 'lid' => $lid));
		} catch (\Illuminate\Database\QueryException $e) {
			$message = explode('(', $e->getMessage());
			$dbCode = rtrim($message[0], ']');
			$dbCode = trim($dbCode, '[');

			\DB::rollback();
			$action = "Edit";
			return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
		}

	}

	//Edit data
	public function edit(Request $request, $id = null)
	{
		$this->data['pageModule'] = "freightcarriershdr";
		$this->data['pageUrl'] = url('freightcarriershdr');


		$this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'freightcarriershdr')->get();
		$this->data['urlName'] = \Request::route()->getName();

		if ($this->data['urlName'] == "purchasefreightcarriershdredit") {
			$this->data['source_type_id'] = "Purchase";
		} else {
			$this->data['source_type_id'] = "Sales";
		}

		$this->data['url_type'] = '';
		$table = \DB::table('m_frieghtcarriers_hdr_t')->where('ar_frieghtcarriers_hdr_id', $id)->get();
		$this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
		$this->data['row'] = $table[0];
		$str_date = $this->data['row']->start_date;
		$end_date = $this->data['row']->end_date;

		if ($str_date != '0000-00-00')
			$this->data['row']->start_date = date("m-d-Y", strtotime($str_date));
		else
			$this->data['row']->start_date = '';

		if ($end_date != '0000-00-00')
			$this->data['row']->end_date = date("m-d-Y", strtotime($end_date));
		else
			$this->data['row']->end_date = '';
		$this->data['row']->remarks = $table[0]->remarks;

		$this->data['row']->ar_frieghtcarriers_hdr_id = $id;


		$tablelines = \DB::table('m_frieghtcarriers_lines_t')->where('ar_frieghtcarriers_hdr_id', $id)->get();


		$this->data['default_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $this->data['row']->default_currency);
		$location = \Session::get('location');
		$comp = \Session::get('companyid');
		$company = \DB::table('m_company_line_t')->where('companyid', $comp)->get();

		if (count($company) > 0) {
			$c = '';
			foreach ($company as $k => $y) {
				$c .= $y->locationid . ",";
			}

			$c = rtrim($c, ',');

			$this->data['location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $this->data['row']->location_id, " and location_id in(" . $c . ")");
		} else {
			$this->data['location_id'] = $this->jCombologin('m_location_t', 'location_id', 'location_name', '');
		}

		$this->data['linedata'] = $tablelines;

		$this->data['country'] = $this->data['country'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', $tablelines[0]->country);

		$this->data['state'] = $this->data['state'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', $tablelines[0]->state);
		$this->data['city'] = $this->data['city'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', $tablelines[0]->city);

		foreach ($this->data['linedata'] as $key => $value) {

			$this->data['linedata'][$key]->country = $this->data['country'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', $value->country);

			$this->data['linedata'][$key]->state = $this->data['state'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', $value->state);

			$this->data['linedata'][$key]->city = $this->data['city'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', $value->city);

		}

		if (isset($_GET['status'])) {
			$this->data['used_some'] = "readonly";
		}
		$this->data['pagemode'] = 'edit';

		return view('freightcarriershdr.form', $this->data);
	}

	public function getedit($edit_id, $type)
	{
		echo $edit_id;
	}
	// View function
	public function show(arfreightcarriershdr $arfreightcarriershdr, $id = null)
	{
		$this->data['urlname'] = \Request::route()->getName();

		if ($this->data['urlname'] == "purchasefreightcarriershdrview") {
			$this->data['index_data'] = "Purchase";
		} else {
			$this->data['index_data'] = "Sales";
		}

		$this->data['columns'] = \DB::connection()->getSchemaBuilder()->getColumnListing("m_frieghtcarriers_hdr_t");
		$this->data['values'] = arfreightcarriershdr::find($id);

		$str_date = $this->data['values']->start_date;
		$end_date = $this->data['values']->end_date;

		//dd(\Session::get('p_date_format'));
		$this->data['start_date'] = date(\Session::get('p_date_format'), strtotime($str_date));
		$this->data['end_date'] = date(\Session::get('p_date_format'), strtotime($end_date));
		//$active=$this->data['values']->active;

		$vlinesdata = \DB::table('m_frieghtcarriers_lines_t')->select('m_frieghtcarriers_lines_t.*', 'm_countries_t.country_name', 'm_states_t.state_name', 'm_cities_t.city_name', 'tb_users.username')
			->leftjoin('m_countries_t', 'm_countries_t.country_id', '=', 'm_frieghtcarriers_lines_t.country')
			->leftjoin('m_states_t', 'm_states_t.state_id', '=', 'm_frieghtcarriers_lines_t.state')
			->leftjoin('m_cities_t', 'm_cities_t.city_id', '=', 'm_frieghtcarriers_lines_t.city')
			->leftjoin('tb_users', 'tb_users.id', '=', 'm_frieghtcarriers_lines_t.created_by')
			->where('m_frieghtcarriers_lines_t.ar_frieghtcarriers_hdr_id', $id)->get();

		$location = $this->data['values']->location_id;
		$locationid = DB::table("m_location_t")->where('location_id', $location)->get();

		$this->data['location_name'] = $locationid[0]->location_name;

		$defcurrency = $this->data['values']->default_currency;
		$cur = \DB::select("select currency_code from f_account_currency_t where account_currency_id='$defcurrency'"); //dd($cur[0]->currency_code);

		if (count($cur) > 0) {
			$this->data['currency_code'] = $cur[0]->currency_code;
		} else {
			$this->data['currency_code'] = '';
		}
		$this->data['values'] = arfreightcarriershdr::find($id);

		$user = \DB::table('tb_users')->where('id', $this->data['values']->created_by)->get();
		if (count($user) > 0) {
			$this->data['created_by'] = $user[0]->username;
		} else {
			$this->data['created_by'] = '';
		}

		$this->data['vlinesdata'] = $vlinesdata;
		$this->data['country_name'] = $vlinesdata[0]->country_name;
		$this->data['state_name'] = $vlinesdata[0]->state_name;
		$this->data['city_name'] = $vlinesdata[0]->city_name;


		if ($vlinesdata[0]->active == '1') {
			$this->data['active'] = 'Yes';
		} else {
			$this->data['active'] = 'No';
		}

		return view('freightcarriershdr.view', $this->data);
	}
	/* Delete  data function*/
	public function delete(Request $request, $id = null, $type = null)
	{
		if ($type == "Sales") {
			$column = array('ar_frieghtcarriers_hdr_id', 'frieghtcarriers_hdr_id', 'freight_carrier_id', 'ar_frieghtcarriers_hdr_id');
			$table = array('m_customers_t', 's_quote_hdr_t', 's_salesorder_hdr_t', 's_invoice_hdr_t');
		} else {
			$column = array('freight_carrier_id', 'freight_carrier_id');
			$table = array('p_quotation_hdr_t', 'p_po_hdr_t');
		}
		for ($i = 0; $i < count($table); $i++) {
			$j = 0;
			$query = \DB::table($table[$i])->where($column[$i], $id)->get();
			if (count($query) > 0) {
				$j = 1;
				break;
			}
		}
		if ($j == 0) {
			Arfreightcarriershdr::destroy($id);

			/**Auditlog**/
			$action = "Delete";
			$this->auditlog($id, "FREIGHT CARRIERS", $action, $id, "m_frieghtcarriers_hdr_t");
			$query = \DB::table('m_frieghtcarriers_lines_t')->where('ar_frieghtcarriers_hdr_id', $id)->delete();
		}
		return $j;
	}

	public function locationget()
	{
		$location = \Session::get('location');
		$comp = \Session::get('companyid');
		$company = \DB::table('m_company_line_t')->where('companyid', $comp)->get();

		if (count($company) > 0) {
			$c = '';
			foreach ($company as $k => $y) {
				$c .= $y->locationid . ",";
			}

			$c = rtrim($c, ',');

			return $c;
		} else {
			return 0;
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
	function findPrimarykeylines($tablelines)
	{
		$primaryKey = '';
		foreach (\DB::select("show columns from " . $tablelines . " where extra like '%auto_increment%'") as $key) {
			$primaryKey = $key->Field;
		}
		return $primaryKey;
	}
	function validateForm($request = null)
	{
		$form_config = json_decode(urldecode($request['form_data_json']), true);
		$forms = array();
		$forms['header'] = $form_config['header'];
		$forms['lines'] = $form_config['lines'];
		$rules = array();
		foreach ($forms as $form_type => $form_type_val) {
			foreach ($form_type_val as $form_field_name => $form) {
				if ($form['required'] == '') {
					$rules[$form_type][$form['field']] = 'required';
				} elseif ($form['required'] == 'alpa') {
					$rules[$form_type][$form['field']] = 'required|alpa';
				} elseif ($form['required'] == 'alpa_num') {
					$rules[$form_type][$form['field']] = 'required|alpa_num';
				} elseif ($form['required'] == 'alpa_dash') {
					$rules[$form_type][$form['field']] = 'required|alpa_dash';
				} elseif ($form['required'] == 'email') {
					$rules[$form_type][$form['field']] = 'required|email';
				} elseif ($form['required'] == 'numeric') {
					$rules[$form_type][$form['field']] = 'required|numeric';
				} elseif ($form['required'] == 'date') {
					$rules[$form_type][$form['field']] = 'required|date';
				} else if ($form['required'] == 'url') {
					$rules[$form_type][$form['field']] = 'required|active_url';
				} else {

				}

			}

		}

		return $rules;
	}

	public function carrierchck(Request $request)
	{

		$ar_frieghtcarriers_hdr_id = $_GET['ar_frieghtcarriers_hdr_id'];

		if ($ar_frieghtcarriers_hdr_id == '') {
			$frcarriersdata = DB::table('m_frieghtcarriers_hdr_t')->where('carrier_name', $_GET['carrier_name'])->where('source_type_id', $_GET['source_type_id'])->get();
		} else {
			$whereData = [['carrier_name', $_GET['carrier_name']], ['ar_frieghtcarriers_hdr_id', '!=', $ar_frieghtcarriers_hdr_id]];
			$frcarriersdata = DB::table('m_frieghtcarriers_hdr_t')->where($whereData)->where('source_type_id', $_GET['source_type_id'])->get();
		}
		if (count($frcarriersdata) > 0)
			return 1;
		else
			return 0;

	}
	public function freightcarnamechk($frightcarhdrid = null)
	{

		$yes = 0;
		if ($frightcarhdrid != "") {
			$tablesCheck = [];
			$tableNew['table_name'] = "p_quotation_hdr_t";
			$tableNew['column_name'] = "freight_carrier_id";
			$tableNew['value'] = $frightcarhdrid;
			$tablesCheck[] = $tableNew;

			$tableNew['table_name'] = "p_po_hdr_t";
			$tableNew['column_name'] = "freight_carrier_id";
			$tableNew['value'] = $frightcarhdrid;
			$tablesCheck[] = $tableNew;

			$tableNew['table_name'] = "m_customers_t";
			$tableNew['column_name'] = "ar_frieghtcarriers_hdr_id";
			$tableNew['value'] = $frightcarhdrid;
			$tablesCheck[] = $tableNew;

			$tableNew['table_name'] = "s_quote_hdr_t";
			$tableNew['column_name'] = "frieghtcarriers_hdr_id";
			$tableNew['value'] = $frightcarhdrid;
			$tablesCheck[] = $tableNew;

			$tableNew['table_name'] = "s_salesorder_hdr_t";
			$tableNew['column_name'] = "freight_carrier_id";
			$tableNew['value'] = $frightcarhdrid;
			$tablesCheck[] = $tableNew;

			$tableNew['table_name'] = "s_invoice_hdr_t";
			$tableNew['column_name'] = "ar_frieghtcarriers_hdr_id";
			$tableNew['value'] = $frightcarhdrid;
			$tablesCheck[] = $tableNew;


			foreach ($tablesCheck as $tableToCheck) {
				if ($yes != 1) {
					$frieght = \DB::select("select " . $tableToCheck['column_name'] . " from " . $tableToCheck['table_name'] . " where " . $tableToCheck['column_name'] . "=" . $tableToCheck['value']);

					if (count($frieght) > 0) {
						$yes = 1;
					}
				}
			}
		}
		return $yes;
	}


}
