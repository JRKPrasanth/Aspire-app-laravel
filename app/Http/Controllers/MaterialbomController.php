<?php
namespace App\Http\Controllers;

use App\Materialbom;
use App\Materialbomlines;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class MaterialbomController extends Controller
{
	/* purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/

	public $module = "materialbom";
	public function __construct()
	{
		$this->data = array();
		$this->model = new Materialbom();
		$this->model = new Materialbom;
		$this->submodel = new Materialbomlines;
		$this->data['pageMethod'] = \Request::route()->getName();
		$this->data['pageFormtype'] = 'ajax';
		$this->data['pageModule'] = 'materialbom';
		$this->table = "m_material_bom_hdr_t";
		$this->subtable = "m_material_bom_lines_t";
		$this->middleware('auth');
		$this->data['urlmenu'] = $this->indexs();
	}
	/*end*/
	/*deepika purpose:index function to redirect table blade*/
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


		$this->data['materialbom'] = $this->jqgridselect('m_material_bom_hdr_t', 'material_bom_hdr_id', 'assembly_product_id');
		$table = \DB::table('m_material_bom_hdr_t')->get();
		$this->data['datas'] = json_encode($table);
		$this->data['pageMethod'] = \Request::route()->getName();
		return view("materialbom.table", $this->data);
	}
	/*end*/

	/* purpose:to display material bom */
	public function getmaterialbomData()
	{
		$wh = '';

		$loc = "1";
		$compy = \Session::get('companyid');
		$groupname = \Session::get('groupname');

		if ($groupname == '1' || $groupname == 'Admin') {
			$wh .= 'and  m_material_bom_hdr_t.company_id=' . $compy;
		} else {
			$wh .= 'and  m_material_bom_hdr_t.company_id=' . $compy . ' and m_material_bom_hdr_t.location_id=' . $loc;
		}

		$app_id = \Session::get('id');

		if ($_GET['pagemethod'] == 'materialbomapproval') {
			$wh .= " and m_material_bom_hdr_t.savestatus='INITIATED' and json_contains(m_material_bom_hdr_t.approver_id ,'" . $app_id . "')=1 ";
		}

		if ($groupname == "8") {
			$emp_id = \Session::get('emp_id');
			$prd_id = \DB::select("SELECT GROUP_CONCAT(productmapping_lines_tbl.prd_id)as prd FROM `productmapping_hdr_tbl` join productmapping_lines_tbl on productmapping_lines_tbl.productmapping_id=productmapping_hdr_tbl.productmapping_id where productmapping_hdr_tbl.employee_id=$emp_id");
			if (isset($prd_id[0]->prd)) {
				$wh .= " and m_material_bom_hdr_t.`assembly_product_id` in (" . $prd_id[0]->prd . ")";
			} else {
				$wh .= " ";
			}
		}

		$SQL = "SELECT
m_material_bom_hdr_t.material_bom_hdr_id,
m_material_bom_hdr_t.assembly_product_id,
m_material_bom_hdr_t.savestatus,
m_uom_codes_t.uom_code,
m_projects_t.project_name,
m_material_bom_hdr_t.remarks,
m_products_t.concatenated_product,
m_products_t.product_code,
tb_users.first_name
FROM `m_material_bom_hdr_t`
left join m_products_t on(
m_products_t.product_id=m_material_bom_hdr_t.`assembly_product_id`) left join m_uom_codes_t on(m_uom_codes_t.uom_code_id=m_material_bom_hdr_t.uom_code_id) left join m_projects_t on(m_projects_t.project_id=m_material_bom_hdr_t.project_id) left join tb_users on(tb_users.id=m_material_bom_hdr_t.created_by) where 1=1 $wh";

		$result = \DB::select($SQL);

		return DataTables::of($result)->make(true);
	}


	public function create($id = null)
	{

		if (isset($id)) {
			$this->data['id'] = $id;
			$this->data['pagemode'] = "edit";
			$table = \DB::table('m_material_bom_hdr_t')->where('material_bom_hdr_id', $id)->get();
			$this->data['row'] = $table[0];
			$this->data['organization_id'] = $this->jcombo("m_organizations_t", "organization_id", "organization_name", $this->data['row']->organization_id);
			$this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $this->data['row']->project_id);
			$this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
			$group = $this->groupname('FINISHED GOODS', 'group');
			$pack = $this->groupname('PACKING MATERIALS', 'group');
			$raw = $this->groupname('RAW MATERIALS', 'group');
			$cate_inter = $this->groupname('INTERMEDIATE', 'group');
			$cate_semi = $this->groupname('SEMI FINISHED GOODS', 'group');
			$this->data['group'] = $group;
			$this->data['cate_semi'] = $cate_semi;

			$depart = \Session::get('groupname');
			$wh = '';
			if ($depart == "8") {
				$emp_id = \Session::get('emp_id');
				$prd_id = \DB::select("SELECT GROUP_CONCAT(productmapping_lines_tbl.prd_id)as prd FROM `productmapping_hdr_tbl` join productmapping_lines_tbl on productmapping_lines_tbl.productmapping_id=productmapping_hdr_tbl.productmapping_id where productmapping_hdr_tbl.employee_id=$emp_id");
				if (isset($prd_id[0]->prd)) {
					$wh .= " and product_id in (" . $prd_id[0]->prd . ")";
				} else {
					$wh .= " ";
				}
			}

			$this->data['assembly_product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $this->data['row']->assembly_product_id, "and product_group_id in($group,$cate_semi) $wh");
			$this->data['assembly_product_id1'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', "and product_group_id in($group,$cate_semi) $wh");
			$this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $this->data['row']->uom_code_id);
			$linestable = \DB::table('m_material_bom_lines_t')->where('material_bom_hdr_id', $id)->get();
			$this->data['compproductid'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'materialbom');
			$this->data['linedata'] = $linestable;

			foreach ($this->data['linedata'] as $key => $value) {
				$this->data['linedata'][$key]->component_product_id = $this->data['component_product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->component_product_id, 'and product_id=' . $value->component_product_id);
				$this->data['linedata'][$key]->component_type = $this->data['component_type'] = $this->jCombo('m_product_groups_t', 'product_group_id', 'group_name', $value->component_type);
				$this->data['linedata'][$key]->component_uom_code_id = $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->component_uom_code_id);
				$this->data['linedata'][$key]->process_level = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $value->process_level, 'and lookup_type="PROCESS_LEVEL"');
				$this->data['linedata'][$key]->process_name = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $value->process_name, 'and lookup_type="PROCESS_NAME"');
				$this->data['linedata'][$key]->machine_name = $this->jCombocomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', $value->machine_name);
			}

		} else {
			$this->data['pagemode'] = "create";
			$this->modelname = new Materialbom();
			$this->data['row'] = (object) array();
			$table = $this->modelname->getTableColumns();
			foreach ($table as $key => $val) {
				$this->data['row']->$val = '';
			}
			$this->data['organization_id'] = $this->jcombo("m_organizations_t", "organization_id", "organization_name", \Session::get('organization'));
			$this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
			$this->data['linedata'] = array();

			$this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
			$this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');
			$group = $this->groupname('FINISHED GOODS', 'group');
			$this->data['group'] = $group;
			$pack = $this->groupname('PACKING MATERIALS', 'group');
			$raw = $this->groupname('RAW MATERIALS', 'group');
			$cate_inter = $this->groupname('INTERMEDIATE', 'group');
			$cate_semi = $this->groupname('SEMI FINISHED GOODS', 'group');
			$this->data['cate_semi'] = $cate_semi;
			$this->data['component_product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'materialbom');
			$this->data['component_uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
			$this->data['component_type'] = $this->jCombo('m_product_groups_t', 'product_group_id', 'group_name', "");

			$depart = \Session::get('groupname');
			$wh = '';
			if ($depart == "8") {
				$emp_id = \Session::get('emp_id');
				$prd_id = \DB::select("SELECT GROUP_CONCAT(productmapping_lines_tbl.prd_id)as prd FROM `productmapping_hdr_tbl` join productmapping_lines_tbl on productmapping_lines_tbl.productmapping_id=productmapping_hdr_tbl.productmapping_id where productmapping_hdr_tbl.employee_id=$emp_id");
				if (isset($prd_id[0]->prd)) {
					$wh .= " and product_id in (" . $prd_id[0]->prd . ")";
				} else {
					$wh .= " ";
				}
			}
			$this->data['assembly_product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', "and product_group_id in($group,$cate_semi) $wh");
		}
		$this->data['process_level'] = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'and lookup_type="PROCESS_LEVEL"');
		$this->data['process_name'] = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'and lookup_type="PROCESS_NAME"');
		$this->data['machine_name'] = $this->jCombocomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '');
		$this->data['prdcatopt'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
		$this->data['prdgrpopt'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
		$this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'materialbom')->get();
		$this->data['return_url'] = \Request::route()->getName();

		return view("materialbom.form", $this->data);
	}

	public function save(Request $request)
	{
		
		$id = '';
		$form = $request->all();
		$form = $request->except([
			'_token',
			'form_config',
			'form_data_json',
			'savestatus',
			'submit_type',
			'choosefile',
			'existing_file',
			'productTable_length'
		]);
		// Normalize "bulk_" keys once
		$form = $this->normalizeLineFormKeys($form);

		// Build header + lines
		$data = $this->validatePost($form, $this->table, 'header');

		if (isset($_POST['process'])) {
			$process = implode(",", $_POST['process']);
			$data['process'] = $process;
		} else {
			$data['process'] = 0;
		}

		if ($_POST['savestatus'] == 'INITIATED') {
			$t = 0;
			$approverid = $this->Approvaldatacheck('materialbom', $t);

			if ($approverid == "0") {

				$data['approver_id'] = \Session::get('id');
				$data['savestatus'] = "APPROVED";
			} else {

				$data['approver_id'] = $approverid;

			}
		}

		        $lines_data = [];

        $lineCount = count($request->bulk_line_no);

        for ($i = 0; $i < $lineCount; $i++) {


        
            $lines_data['line_no'][$i] = $request->bulk_line_no[$i];
            $lines_data['component_product_id'][$i] = $request->bulk_component_product_id[$i];
            $lines_data['component_uom_code_id'][$i] = $request->bulk_component_uom_code_id[$i];
            $lines_data['component_type'][$i] = $request->bulk_component_type[$i] ?? 0;
            $lines_data['component_qty'][$i] = $request->bulk_component_qty[$i];
            $lines_data['process_level'][$i] = $request->bulk_process_level[$i];
            $lines_data['process_name'][$i] = $request->bulk_process_name[$i];
            $lines_data['machine_name'][$i] = $request->bulk_machine_name[$i];
            $lines_data['comments'][$i] = $request->bulk_comments[$i];
            // common audit fields
            $lines_data['created_by'][$i] = auth()->id();
            $lines_data['last_updated_by'][$i] = auth()->id();
            $lines_data['created_at'][$i] = now();
            $lines_data['updated_at'][$i] = now();
            $lines_data['location_id'][$i] = session('location');
            $lines_data['company_id'][$i] = session('companyid');
            $lines_data['organization_id'][$i] = session('organization');
        }
	//	dd($lines_data);
		\DB::beginTransaction();
		try {
			unset($lines_data['process']);
			$id = $this->model->insertRow($data);
			$lid = $this->submodel->subgridSave($lines_data, $id);
			/*vinobha purpose:audit log*/
			if ($_POST['material_bom_hdr_id'] == "") {
				$action = "create";
			} else {
				$action = "update";
			}
			$this->auditlog($id, "materialbom", $action, $data, "m_material_bom_hdr_t");
			/*end*/
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

	/**  * Display the specified resource. */
	public function show(Materialbom $materialbom, $id = null)
	{
		$data = Materialbom::find($id);
		$this->data['assembly_product_id'] = $this->idname("product_code|concatenated_product", "m_products_t", "product_id", $data['assembly_product_id']);
		$this->data['uom_code_id'] = $this->idname("uom_code", "m_uom_codes_t", "uom_code_id", $data['uom_code_id']);
		$this->data['project_id'] = $this->idname("project_name", "m_projects_t", "project_id", $data['project_id']);
		$this->data['active'] = $data['active'];
		$this->data['organization_name'] = $this->idname("organization_name", "m_organizations_t", "organization_id", $data['organization_id']);
		$this->data['remarks'] = $data['remarks'];
		$this->data['process'] = $data['process'];
		$this->data['created_by'] = $this->idname("username", "tb_users", "id", $data['created_by']);
		$a = \DB::table('m_material_bom_lines_t')->where('material_bom_hdr_id', $id)->get();
		//$linesdata = \DB::table('m_material_bom_lines_t')->leftjoin('m_material_bom_hdr_t','m_material_bom_hdr_t.material_bom_hdr_id','=','m_material_bom_lines_t.material_bom_hdr_id')->leftjoin('m_product_groups_t','m_product_groups_t.product_group_id','=','m_material_bom_lines_t.component_type')->leftjoin('m_products_t','m_products_t.product_id','=','m_material_bom_lines_t.component_product_id')->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','m_material_bom_lines_t.component_uom_code_id')->where('m_material_bom_lines_t.material_bom_hdr_id',$id)->get();
		$linesdata = \DB::table('m_material_bom_lines_t')->leftjoin('m_material_bom_hdr_t', 'm_material_bom_hdr_t.material_bom_hdr_id', '=', 'm_material_bom_lines_t.material_bom_hdr_id')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_material_bom_lines_t.component_type')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'm_material_bom_lines_t.component_product_id')->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'm_material_bom_lines_t.component_uom_code_id')->where('m_material_bom_lines_t.material_bom_hdr_id', $id)->orderBy('m_material_bom_lines_t.material_bom_line_id', 'asc')->get();
		$this->data['linesdata'] = $linesdata;
		return view("materialbom.view", $this->data);
	}
	/*end*/
	/**  * Remove the specified resource from storage.  */
	public function destroy($del_id)
	{
		$j = 0;

		/*   $column = array('locator_id','subinventory_id','locator_id','subinventory_id','sublocator_id','subinventory_id');
		   $table = array('i_qoh_detail_t','i_qoh_detail_t','i_reservation_detail_t','i_reservation_detail_t','m_products_t','m_products_t');
		   for($i=0; $i<count($table); $i++)
		   {

			   $query = \DB::table($table[$i])->where($column[$i],$del_id)->get();
			   if(count($query)>0)
			   {
				   $j=1;
				   break;
			   }
		   }*/

		if ($j == 0) {
			$query = \DB::table('m_material_bom_hdr_t')->where('material_bom_hdr_id', $del_id)->delete();
			$query1 = \DB::table('m_material_bom_lines_t')->where('material_bom_hdr_id', $del_id)->delete();
		}
		return $j;
		/*
		if($j == 1){
			 $status['status'] = 'info';
			 $status['message'] = 'Deletion Error Already Used In Somewhere!!!';
			return $status;

		} else if($j == 0){
			 $status['status'] = 'success';
			 $status['message'] = 'Deleted Successfully!!!';
			return $status;
		} */

	}

	/*deepika purpose:to get productuom*/
	public function productuomdetails($product_id = null)
	{
		$uom = \DB::table('m_products_t')->select('primary_uom_id')->where('product_id', $product_id)->get();

		$uom_code_id = $uom[0]->primary_uom_id;
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

	}
	/*end*/
	/*deepika purpose:to get group id based on groupname*/
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
	/*end*/
	/*deepika purpose:to get categoryname  based on category id*/
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
	/*end*/
	/*deepika purpose:to get assembly product duplicate in db*/
	public function materialbomchk(Request $request)
	{
		$edit_id = $_GET['edit_id'];
		if ($edit_id == '')
			$bom = \DB::table('m_material_bom_hdr_t')->where('assembly_product_id', $_GET['product_id'])->get();
		else {
			$whereData = [['assembly_product_id', $_GET['product_id']], ['material_bom_hdr_id', '!=', $edit_id]];

			$bom = \DB::table('m_material_bom_hdr_t')->where($whereData)->get();
		}
		if (count($bom) > 0)
			return 1;
		else
			return 0;
	}
	/*end*/
}
