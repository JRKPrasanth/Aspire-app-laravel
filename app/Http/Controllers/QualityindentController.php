<?php

namespace App\Http\Controllers;

use App\Qualityindent;
use App\Qualityindentlines;
use Illuminate\Http\Request;
use yajra\datatables\datatables;

class QualityindentController extends Controller
{

	public function __construct()
	{
		$this->data = array(
			'pageModule' => 'qualityindent',
			'pageUrl' => url('qualityindent')
		);
		$this->data['urlmenu'] = $this->indexs();
		$this->model = new Qualityindent();
		$this->submodel = new Qualityindentlines();
		$this->data['pageMethod'] = \Request::route()->getName();

		$this->table = 'w_quality_indent_hdr_t';
		$this->subtable = 'w_quality_indent_lines_t';
		$this->data['pageFormtype'] = 'ajax';
		$this->middleware('auth');
	}

	/*Jq grid For Quality Indent*/
	public function qualityindentdata(Request $request)
	{

		if ($request->ajax()) {
			$data = \DB::table('w_quality_indent_hdr_t')
				->select([
					'w_quality_indent_hdr_t.*',
				]);
			return DataTables::of($data)->make(true);
		}
	}
	/*End*/

	/*material Issue Data Jq grid*/
	public function indentmaterialissuedata()
	{

		$wh = '';


		if ($_GET['_search'] == 'true') {
			$wh = $this->jqgridsearch('w_quality_indent_hdr_t', $_GET['filters']);


		}



		$page = $_GET['page'];

		$limit = $_GET['rows'];

		$sidx = $_GET['sidx'];

		$sord = $_GET['sord'];

		if (!$sidx)
			$sidx = 1;
		$result = \DB::select("SELECT COUNT(w_quality_indent_hdr_t.quality_indent_hdr_id) AS count FROM w_quality_indent_hdr_t where 1=1 and issue_status=0 $wh");

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
		$SQL = " SELECT w_quality_indent_hdr_t.*  FROM w_quality_indent_hdr_t where 1=1 and issue_status=0 $wh ORDER BY $sidx $sord LIMIT $start , $limit";

		$download_SQL = " SELECT w_quality_indent_hdr_t.*  FROM w_quality_indent_hdr_t where 1=1 and issue_status=0 $wh ORDER BY $sidx $sord";
		$result1 = \DB::select($download_SQL);
		$result1 = collect($result1)->map(function ($x) {
			return (array) $x; })->toArray();
		if (isset($_GET['download'])) {
			return $result1;
		}


		$result = \DB::select($SQL);
		// dd($result);
		$responce->rows[] = '';
		$responce->rows = $result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		echo json_encode($responce);
	}
	/*End*/
	/*Main Page Load Function*/
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

		$this->data['pageMethod'] = "qualityindent";
		return view('Qualityindent.table', $this->data);
	}
	/*End*/

	/*Create Function*/
	public function create($id = null)
	{
		// dd($_GET['status']);
		if ($id == 0) {

			$this->data['row'] = (object) array();
			$this->data['row']->quality_indent_hdr_id = "";
			$this->data['row']->indent_name = "";
			$this->data['row']->indent_date = "";
			$this->data['row']->remarks = "";
			$this->data['pagemethod'] = 'qualityindent';
			$this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
			$this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'qualityindent');
			$this->data['uom_code_id'] = $this->jCombologin('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
			$this->data['linedata'] = array();
			$this->data['qc_id'] = '';
		} else if (isset($_GET['status'])) {
			if ($_GET['status'] == "qualityreduce") {

				$this->data['pagemethod'] = 'qualitycheck';
				$data = \DB::SELECT("SELECT * FROM `w_quality_indent_hdr_t` ORDER BY `quality_indent_hdr_id` DESC limit 1");

				$this->data['row'] = $data[0];
				$this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['linedata'] = $linedata = \DB::table('w_quality_indent_lines_t')->where('quality_indent_hdr_id', $data[0]->quality_indent_hdr_id)->get();

				foreach ($linedata as $key => $value) {
					$data_rems = \DB::SELECT("SELECT sum(used_qty) as used_qty FROM `w_quality_indent_used_t` where quality_indent_hdr_id='" . $data[0]->quality_indent_hdr_id . "' and product_id=$value->product_id");

					$data_rem = $data_rems[0]->used_qty;

					if ($data_rem == null || $data_rem == 0 || $data_rem == "") {
						$this->data['used_qty'][$key] = 0;
						$this->data['remaining_qty'][$key] = $value->qty;
					} else {
						$this->data['used_qty'][$key] = $data_rem;
						$this->data['remaining_qty'][$key] = $value->qty - $data_rem;
					}

					$this->data['product_id'][$key] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', $value->product_id, 'qualityindent');
					$this->data['uom_code_id'][$key] = $this->jCombologin('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
				}
				$this->data['qc_id'] = $id;
			} else if ($_GET['status'] == "indentmaterialissue") {

				$this->data['pagemethod'] = 'indentmaterialissue';
				$data = \DB::table('w_quality_indent_hdr_t')->where('quality_indent_hdr_id', $id)->get();
				$this->data['row'] = $data[0];
				$this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
				$this->data['linedata'] = $linedata = \DB::table('w_quality_indent_lines_t')->where('quality_indent_hdr_id', $id)->get();
				foreach ($linedata as $key => $value) {

					$this->data['used_qty'][$key] = '';
					$this->data['remaining_qty'][$key] = '';
					$qoh_data = \DB::SELECT("SELECT sum(qoh_trx_qty) as sum_qoh ,sum(scrap_qty) as scrap_qty FROM `i_qoh_detail_t` where product_id=$value->product_id");
					if (count($qoh_data) > 0) {
						$qoh = $qoh_data[0]->sum_qoh - $qoh_data[0]->scrap_qty;
						if ($qoh < 0) {
							$qoh = 0;
						}
						$this->data['qoh_qty'][$key] = $qoh;
					} else {
						$this->data['qoh_qty'][$key] = 0;
					}
					$this->data['product_id'][$key] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', $value->product_id);
					$this->data['uom_code_id'][$key] = $this->jCombologin('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
				}
				$this->data['qc_id'] = '';
			}
		} else {
			$data = \DB::table('w_quality_indent_hdr_t')->where('quality_indent_hdr_id', $id)->get();
			$this->data['pagemethod'] = 'qualityindent';
			$this->data['row'] = $data[0];
			$this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', $data[0]->created_by);

			$this->data['linedata'] = $linedata = \DB::table('w_quality_indent_lines_t')->where('quality_indent_hdr_id', $id)->get();
			foreach ($linedata as $key => $value) {

				$this->data['used_qty'][$key] = '';
				$this->data['remaining_qty'][$key] = '';
				$this->data['product_id'][$key] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', $value->product_id);
				$this->data['uom_code_id'][$key] = $this->jCombologin('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
			}
			$this->data['qc_id'] = '';

		}
		if (isset($_GET['pageMethod'])) {
			$this->data['pageMethod'] = $_GET['pageMethod'];
		}
		return view('Qualityindent.form', $this->data);
	}
	/*End*/

	/*Save Function*/
	public function save(Request $request)
	{


		$form = $request->all();
		$dataupload = "";
		$form = $request->except([
			'_token',
			'form_config',
			'form_data_json',
			'savestatus',
			'submit_type',
			'choosefile',
			'existing_file','qc_id',
		]);
		// Normalize "bulk_" keys once
		$form = $this->normalizeLineFormKeys($form);

		// Build header + lines
		$data = $this->validatePost($form, $this->table, 'header');
		$lines_data = $this->validatePost($form, $this->subtable, 'lines');

		\DB::beginTransaction();
		try {
			$id = $this->model->insertRow($data);
			if ($_POST['quality_indent_hdr_id'] == "") {
				$action = "Create";
			} else {
				$action = "Edit";
			}
			/**Auditlog**/
			$this->auditlog($id, "qualityindent", $action, $_POST, "w_quality_indent_hdr_t");
			$lid = $this->submodel->subgridSave($lines_data, $id);

			\DB::commit();
			if ($_POST['qc_id'] != '') {
				$used_qty = $_POST['bulk_used_qty'];
				$data_l = \DB::table('w_quality_indent_lines_t')->where('quality_indent_hdr_id', $_POST['quality_indent_hdr_id'])->get();
				foreach ($data_l as $key => $value) {

					$quality_indent_hdr_id = $_POST['quality_indent_hdr_id'];
					$qc_id = $_POST['qc_id'];
					$qty = $used_qty[$key];
					$p_id = $value->product_id;
					$o_id = $value->organization_id;
					$l_id = $value->location_id;
					$c_id = $value->company_id;
					$created_id = $value->created_by;
					$updated_id = $value->last_updated_by;
					$created_at = $value->created_at;
					$updated_at = $value->updated_at;
					\DB::table('w_quality_indent_used_t')->insert([
						['quality_indent_hdr_id' => $quality_indent_hdr_id, 'quality_spec_trx_hdr_id' => $qc_id, 'product_id' => $p_id, 'used_qty' => $qty, 'organization_id' => $o_id, 'location_id' => $l_id, 'company_id' => $c_id, 'created_by' => $created_id, 'last_updated_by' => $updated_id, 'created_at' => $created_at, 'updated_at' => $updated_at]
					]);
				}
			}
			return response()->json(array('status' => 'success', 'message' => "Saved Successfully", 'id' => $id, 'lid' => $lid));
		} catch (\Illuminate\Database\QueryException $e) {
			$message = explode('(', $e->getMessage());
			$dbCode = rtrim($message[0], ']');
			$dbCode = trim($dbCode, '[');
			dd($dbCode);
			\DB::rollback();
			return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
		}

	}
	/*End*/

	/*Material Issue Indent Save*/
	public function materialissueindentsave(Request $request)
	{
		$bulk_qty = $_POST['bulk_issue_qty'];
		$bulk_uom_code_id = $_POST['bulk_uom_code_id'];
		$bulk_quality_indent_lines_id = $_POST['bulk_quality_indent_lines_id'];
		$update_id = $_POST['quality_indent_hdr_id'];
		foreach ($_POST['bulk_product_id'] as $key => $value) {
			$trsnsType = \DB::table('m_transaction_types_t')->where('transaction_type_code', 'QUALITY INDENT')->get();
			$trsnsType = json_decode(json_encode($trsnsType), true);

			$data1['trx_source_type_id'] = $trsnsType[0]['transaction_type_id'];
			$data1['trx_action_id'] = $trsnsType[0]['transaction_type_id'];
			$data1['trx_type_id'] = $trsnsType[0]['transaction_type_id'];
			$data1['product_id'] = $value;
			$data1['trx_qty'] = -$bulk_qty[$key];
			$data1['trx_uom'] = $bulk_uom_code_id[$key];
			$data1['organization_id'] = \Session::get('organization');
			$data1['location_id'] = \Session::get('location');
			$data1['company_id'] = \Session::get('companyid');
			$data1['trx_source_hdr_id'] = $_POST['quality_indent_hdr_id'];
			$data1['trx_source_line_id'] = $bulk_quality_indent_lines_id[$key];
			$data1['trx_reference'] = "INDENT MATERIAL ISSUE";
			$id = \DB::table('m_material_trx_t')->insertGetId($data1);

			$QOHdata['product_id'] = $value;
			$QOHdata['qoh_trx_qty'] = -$bulk_qty[$key];
			$QOHdata['qoh_uom_code_id'] = $bulk_uom_code_id[$key];
			$QOHdata['organization_id'] = \Session::get('organization');
			$QOHdata['location_id'] = \Session::get('location');
			$QOHdata['company_id'] = \Session::get('companyid');
			$QOHdata['qoh_source'] = "INDENT MATERIAL ISSUE";
			$QOHdata['create_trx_id'] = $id;
			\DB::table('i_qoh_detail_t')->insert($QOHdata);

			$issue_qty = $bulk_qty[$key];
			\DB::table('w_quality_indent_lines_t')->where('quality_indent_hdr_id', $update_id)->where('quality_indent_lines_id', $bulk_quality_indent_lines_id[$key])->update(['issue_qty' => $issue_qty, 'issue_status' => '1']);
		}

		\DB::UPDATE("UPDATE `w_quality_indent_hdr_t` SET issue_status=1 where quality_indent_hdr_id=$update_id");
		/**Auditlog**/
		$this->auditlog($update_id, "qualityindent", "Edit", $_POST, "w_quality_indent_hdr_t");
		return response()->json(array('status' => 'success', 'message' => "Saved Successfully", 'id' => $_POST['quality_indent_hdr_id']));
	}
	/*End*/

	/*Indent Reduce Save*/

	public function indentreducesave(Request $request)
	{

		$used_qty = $_POST['used_qty'];
		$product_id = $_POST['product_id'];

		foreach ($product_id as $key => $value) {
			$qc_id = $_POST['quality_spec_trx_hdr_id'];
			$source = $_POST['source'];
			$qty = $used_qty[$key];
			$p_id = $value;
			$o_id = \Session::get('organization');
			$l_id = \Session::get('location');
			$c_id = \Session::get('companyid');
			\DB::table('w_quality_indent_used_t')->insert([
				['quality_spec_trx_hdr_id' => $qc_id, 'product_id' => $p_id, 'used_qty' => $qty, 'organization_id' => $o_id, 'location_id' => $l_id, 'company_id' => $c_id, 'source' => $source]
			]);
		}
		$edit_id = DB::getPdo()->lastInsertId();
		/**Auditlog**/
		$this->auditlog($edit_id, "qualityindent", "Create", $_POST, "w_quality_indent_used_t");
		return response()->json(array('status' => 'success', 'message' => "Saved Successfully"));
	}
	/*End*/

	/*Indent Material Get Function*/
	public function getindentquantity($id)
	{
		$source = $_GET['status'];
		$indent = \DB::SELECT("SELECT sum(issue_qty)as total_qty,product_id,uom_code_id FROM `w_quality_indent_lines_t`  where issue_status=1 group by w_quality_indent_lines_t.product_id");

		$table = '';
		if (count($indent) <= 0) {

			$table .= "No indent";

		} else {
			foreach ($indent as $key => $value) {
				$data_rems = \DB::SELECT("SELECT sum(used_qty) as used_qty FROM `w_quality_indent_used_t` where product_id='" . $value->product_id . "'");

				$data_rem = $data_rems[0]->used_qty;
				if ($data_rem == null || $data_rem == 0 || $data_rem == "") {
					$remaining_qty = $value->total_qty;
				} else {
					$remaining_qty = $value->total_qty - $data_rem;
				}
				if ($remaining_qty > 0) {
					$table .= "<tr><td class='readonlydiv'><input type='hidden' name='quality_spec_trx_hdr_id' value='" . $id . "' ><input type='hidden' name='source' value='" . $source . "' ><select  name='product_id[]' class='form-control product_id select2' id='product_id'>" . $this->jCombo('m_products_t', 'product_id', 'concatenated_product', $value->product_id) . "</select></td><td class='readonlydiv'><select   class='form-control uom_code_id select2' id='uom_code_id'>" . $this->jCombologin('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id) . "</select></td> <td> <input type='text' class='form-control input-sm remaining_qty" . $key . "' value=" . $remaining_qty . " readonly></td> <td><input type='text' name='used_qty[]' data-val='" . $key . "' class='form-control input-sm used_qty used_qty" . $key . "' value=''> </td></tr>";
				} else {
					$table .= $table;
				}
			}
		}
		return $table;
	}
	/*End*/

	/*Quality Issue Table*/
	public function indentmaterialissue()
	{

		$this->data['pageMethod'] = "indentmaterialissue";
		return view('Qualityindent.issuetable', $this->data);
	}
	/*End*/


	function view($id = null)
	{
		$this->data['closeredirect'] = \Request::route()->getName();

		if ($this->data['closeredirect'] == "salesinquiryview") {
			$this->data['closeredirect'] = "copysalesinquiry";
		}

		$headerdata = \DB::table('s_inquiry_hdr_t as ih')
			->leftjoin('m_projects_t as p', 'ih.project_id', '=', 'p.project_id')
			->leftjoin('m_customers_t as c', 'ih.customerid', '=', 'c.customer_id')
			->leftjoin('m_organizations_t as org', 'ih.organization_id', '=', 'org.organization_id')
			->select('p.project_name', 'c.customer_number', 'c.customer_name', 'ih.inquiry_no', 'ih.inquiry_date', 'ih.inquiry_type', 'org.organization_name', 'ih.remarks', 'ih.tender_id', 'ih.tender_ref_no', 'ih.submission_duedate', 'ih.emd_details', 'ih.tittle_of_work', 'ih.source_type_id')
			->where('ih.so_inquiry_hdr_id', $id)
			->get();
		$linesdata = \DB::table('s_inquiry_hdr_t as ih')
			->leftjoin('s_inquiry_lines_t as il', 'ih.so_inquiry_hdr_id', '=', 'il.so_inquiry_hdr_id')
			->leftjoin('m_products_t as pr', 'il.product_id', '=', 'pr.product_id')
			->leftjoin('m_uom_codes_t as uom', 'il.uom_code_id', '=', 'uom.uom_code_id')
			->leftjoin('m_manufacturer_partno_t as manu', 'il.part_no', '=', 'manu.manufacturer_partno_id')
			->select('pr.product_code', 'pr.concatenated_product', 'uom.uom_code', 'il.line_no', 'manu.part_no', 'ih.so_inquiry_hdr_id', 'il.product_description', 'il.required_qty', 'il.need_by_date')
			->where('ih.so_inquiry_hdr_id', $id)
			->get();
		$this->data['headerdata'] = $headerdata[0];
		$this->data['linesdata'] = $linesdata;

		return view('salesinquiry.view', $this->data);
	}









}
