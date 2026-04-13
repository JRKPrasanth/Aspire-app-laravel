<?php

namespace App\Http\Controllers;

use App\Machineequipmentshdr;
use App\Machineequipmentslines;
use Illuminate\Http\Request;

class MachineequipmentshdrController extends Controller
{
	public $module = "materialequipments";
	public function __construct()
	{
		$this->data = array();
		$this->model = new Machineequipmentshdr();
		$this->model = new Machineequipmentshdr;
		$this->submodel = new Machineequipmentslines;
		$this->data['pageMethod'] = \Request::route()->getName();
		$this->data['pageFormtype'] = 'ajax';
		$this->data['pageModule'] = 'materialequipments';
		$this->table = "w_machine_equipments_hdr_t";
		$this->subtable = "w_machine_equipments_lines_t";
		$this->middleware('auth');

	}
	public function index()
	{

		return view("materialequipments.table", $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($id = null)
	{
		$pdt_id = request('pdt_id', 0);

		// ⬇️  make these available for BOTH create & edit
		$group = $this->groupname('FINISHED GOODS', 'group');
		$raw = $this->groupname('RAW MATERIALS', 'group');
		$cate_inter = $this->groupname('INTERMEDIATE', 'category');
		$cate_semi = $this->groupname('SEMI FINISHED', 'category');

		$this->data['group'] = $group;
		$this->data['cate_semi'] = $cate_semi;
		// (store others too if you need them in the view)

		if ($id) {                         // EDIT
			$this->data['id'] = $id;
			$this->data['pagemode'] = "edit";

			$hdr = \DB::table('w_machine_equipments_hdr_t')
				->where('machine_equipments_hdr_id', $id)
				->first();

			$this->data['row'] = $hdr;

			$this->data['organization_id'] =
				$this->jcombo("m_organizations_t", "organization_id", "organization_name", $hdr->organization_id);

			$this->data['assembly_product'] =
				$this->jCombo('m_products_t', 'product_id', 'concatenated_product', $hdr->assembly_product);

			$this->data['machine_id'] =
				$this->jCombocomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', $hdr->machine_id);

			$this->data['linedata'] =
				\DB::table('w_machine_equipments_lines_t')
					->where('machine_equipments_hdr_id', $id)
					->get();

		} else {                           // CREATE
			$this->data['pagemode'] = "create";
			$this->modelname = new Machineequipmentshdr();
			$this->data['row'] = (object) [];

			$table = $this->modelname->getTableColumns();
			foreach ($table as $key => $val) {
				$this->data['row']->$val = '';
			}

			$this->data['organization_id'] =
				$this->jcombo("m_organizations_t", "organization_id", "organization_name", \Session::get('organization'));

			$this->data['linedata'] = [];
			$this->data['machine_id'] =
				$this->jCombocomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '');

			$this->data['component_type'] =
				$this->jCombo('m_product_groups_t', 'product_group_id', 'group_name', "");

			$this->data['assembly_product'] =
				$this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', $pdt_id, '');
		}

		$this->data['prdcatopt'] =
			$this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');

		$this->data['prdgrpopt'] =
			$this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');

		$this->data['enabled_columns'] =
			\DB::table('m_column_permission_t')->where('module_name', 'materialbom')->get();

		return view("materialequipments.form", $this->data);
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
			$id = $this->model->insertRow($data);
			$lid = $this->submodel->subgridSave($lines_data, $id);
			\DB::commit();
			return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id, 'lid' => $lid));
		} catch (\Illuminate\Database\QueryException $e) {
			$message = explode('(', $e->getMessage());
			$dbCode = rtrim($message[0], ']');
			$dbCode = trim($dbCode, '[');
			//dd($dbCode);
			\DB::rollback();
			return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
		}
	}
	public function getmaterialequipmentsData()
	{
		$wh = '';
		if ($_GET['_search'] == 'true') {
			$search_tables = array('m_products_t', 'w_machine_hdr_t');

			$wh = $this->jqgridsearch('w_machine_equipments_hdr_t', $_GET['filters'], $search_tables);
		}
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if (!$sidx)
			$sidx = 1;

		$result = \DB::select("SELECT COUNT(machine_equipments_hdr_id) AS count FROM w_machine_equipments_hdr_t left join m_products_t on(m_products_t.product_id=w_machine_equipments_hdr_t.assembly_product) left join  w_machine_hdr_t ON(
 w_machine_hdr_t.machine_hdr_id=w_machine_equipments_hdr_t.machine_id
 ) where 1=1 $wh");
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
		$SQL = "SELECT
    w_machine_equipments_hdr_t.`machine_equipments_hdr_id`,
    w_machine_equipments_hdr_t.assembly_product,
   
    w_machine_hdr_t.machine_name,
    w_machine_equipments_hdr_t.remarks,
    m_products_t.concatenated_product
FROM
    `w_machine_equipments_hdr_t`
LEFT JOIN m_products_t ON
    (
        m_products_t.product_id = w_machine_equipments_hdr_t.`assembly_product`
    )
 left join  w_machine_hdr_t ON(
 w_machine_hdr_t.machine_hdr_id=w_machine_equipments_hdr_t.machine_id
 ) where 1=1 $wh ORDER BY $sidx $sord  LIMIT $start , $limit";

		$result = \DB::select($SQL);
		$responce->rows[] = '';
		$responce->rows = $result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}


	public function groupname($name = null, $type = null)
	{
		if ($type == "group") {
			$group = \DB::table('m_product_groups_t')->where('group_name', $name)->get();
			if ($group->isNotEmpty()) {

				$group_id = $group[0]->product_group_id;
				return $group_id;
			} else {
				return 0;
			}

		} else {
			$category = \DB::table('m_product_category_t')->where('category_name', $name)->get();
			if ($category->isNotEmpty()) {

				$category = $category[0]->product_category_id;
				return $category;
			} else {
				return 0;
			}
		}

	}

	public function category($product_category_id = null)
	{
		$category = \DB::table('m_product_category_t')->select('category_name')->where('product_category_id', $product_category_id)->get();
		if ($category->isNotEmpty()) {
			$category_name = $group[0]->category_name;
			return $category_name;
		} else {
			return 0;
		}
	}


	public function edit(Machineequipmentshdr $machineequipmentshdr)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Machineequipmentshdr  $machineequipmentshdr
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Machineequipmentshdr $machineequipmentshdr)
	{
		//
	}
	public function prdmachinedetails($id = null)
	{
		$sql = \DB::select('select * from m_products_t where product_id=' . $id);
		$sql1 = \DB::select('select w_machine_lines_t.*,w_machine_hdr_t.* from w_machine_hdr_t left join w_machine_lines_t on(w_machine_lines_t.machine_hdr_id=w_machine_hdr_t.machine_hdr_id) where w_machine_lines_t.product_type_id=' . $sql[0]->product_type_id);
		$machineid = "";
		foreach ($sql1 as $key => $value) {
			$machineid = $sql1[0]->machine_hdr_id . ",";
		}
		$machineid1 = rtrim($machineid, ",");
		return $machineid1;
	}

}
