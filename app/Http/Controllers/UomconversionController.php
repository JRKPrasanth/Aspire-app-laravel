<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Uomconversion;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class UomconversionController extends Controller
{
	public function __construct()
	{

		$this->module = new Uomconversion();
		$this->data['pageFormtype'] = 'ajax';
		$this->data['pageMethod'] = \Request::route()->getName();
		$this->data['urlmenu'] = $this->indexs();
	}


	public function create($id = null, $type = null)
	{

		$this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));

		$uomconvdata = \DB::connection()->getSchemaBuilder()->getColumnListing('m_uom_conversion_t');
		$uomconvdatas = (object) array();
		foreach ($uomconvdata as $key => $value) {
			$uomconvdatas->$value = "";
		}
		$this->data['row'] = $uomconvdatas;
		$this->data['primary_uom'] = $this->jcombo("m_uom_codes_t", "uom_code_id", "uom_code", " ");
		$this->data['trx_uom'] = $this->jcombo("m_uom_codes_t", "uom_code_id", "uom_code", " ");
		$this->data['product_id'] = $this->jcombo("m_products_t", "product_id", "product_code|concatenated_product", "");

		$this->data['opt'] = $this->jqgridselect('m_uom_codes_t', 'uom_code_id', 'uom_code');
		$this->data['pdt'] = $this->jqgridselect("m_products_t", "product_id", "concatenated_product");
		$wh = '';
		$comp = \Session::get('companyid');
		$loc = \Session::get('location');
		$groupname = \Session::get('groupname');
		if ($groupname == "Superadmin" || $groupname == "Admin") {
			$wh .= "and m_uom_conversion_t.company_id=$comp";
		} else {
			$wh .= "and m_uom_conversion_t.company_id=$comp  and m_uom_conversion_t.location_id=$loc";
		}
		$SQL = "SELECT
		        tb_users.username,
		        m_uom_conversion_t.uom_conversion_id,
				m_uom_conversion_t.primary_uom,
				m_uom_conversion_t.active,
				m_products_t.concatenated_product,
				m_products_t.product_code,
				m_uom_conversion_t.product_id as product,
				m_uom_codes_t.uom_code,
				u2.uom_code as trx_code,
				m_uom_conversion_t.uom_value
				FROM `m_uom_conversion_t` 
				left join m_uom_codes_t on  m_uom_conversion_t.primary_uom = m_uom_codes_t.uom_code_id
				left join m_uom_codes_t u2 on m_uom_conversion_t.trx_uom=u2.uom_code_id left join m_products_t on m_products_t.product_id=m_uom_conversion_t.product_id left join `tb_users` on (tb_users.id=m_uom_conversion_t.created_by) where 1=1 $wh";

		$result = \DB::select($SQL);

		$this->data['result'] = json_encode($result);
		return view('uomconversion.form', $this->data);
	}



	/*Save Function*/
	public function save(Request $request)
	{
		date_default_timezone_set("Asia/Calcutta");
		$uomconvdata = new Uomconversion();
		$edit_id = $request->input('edit_id');

		if ($edit_id == '') {
			$uomconvdata->primary_uom = $_POST['primary_uom'];
			$uomconvdata->trx_uom = $_POST['trx_uom'];
			$uomconvdata->uom_value = $_POST['uom_value'];
			$uomconvdata->product_id = $_POST['product_id'];
			$uomconvdata->active = $_POST['active'];
			$uomconvdata->created_by = $_POST['created_by'];
			$uomconvdata->location_id = \Session::get('location');
			$uomconvdata->last_updated_by = \Session::get('id');
			$uomconvdata->company_id = \Session::get('companyid');
			$uomconvdata->organization_id = \Session::get('organization');
			$status['status'] = 'success';
			$status['message'] = 'Saved Successfully';
			$status['id'] = $edit_id;
			$status['type'] = 'create';
			$uomconvdata->save();
			$edit_id = DB::getPdo()->lastInsertId();
			$action = "Create";
			/**Auditlog**/
			$this->auditlog($edit_id, "uomconversion", $action, $_POST, "m_uom_conversion_t");
			return response()->json(array('status' => 'success', 'message' => 'Uom Conversion Saved Successfully', 'id' => $edit_id));

		} else {
			Uomconversion::find($edit_id)->update($_POST);
			$action = "Edit";
			/**Auditlog**/
			$this->auditlog($edit_id, "uomconversion", $action, $_POST, "m_uom_conversion_t");
			return response()->json(array('status' => 'success', 'message' => 'Uom Conversion Updated Successfully', 'id' => $edit_id));

		}
	}
	/*End*/

	/*Delete Function*/
	public function destroy($del_id)
	{

		$query = \DB::table('m_uom_conversion_t')->where('uom_conversion_id', $del_id)->delete();
		/**Auditlog**/
		$this->auditlog($del_id, "uomconversion", "delete", "", "m_uom_conversion_t");
		if ($query) {
			$status = 0;
			return $status;
		} else {
			$status = 1;
			return $status;
		}
	}
	/*End*/

	/*View Function*/
	public function view($id = null)
	{
		if (isset($id)) {
			$this->data['columns'] = \DB::connection()->getSchemaBuilder()->getColumnListing('m_uom_conversion_t');
			$this->data['values'] = Uomconversion::find($id);
			return view('uomconversion.view', $this->data);
		}

	}
	/*End*/

	/*Uom Conversion Check Name Function*/
	public function uomconversioncheckname(Request $request)
	{
		// dd($_GET);
		$edit_id = $_GET['edit_id'];
		if ($edit_id == '') {
			$group = \DB::table('m_uom_conversion_t')->where('product_id', $_GET['product_id'])->where('primary_uom', $_GET['primary_uom'])->where('trx_uom', $_GET['trx_uom'])->get();

		} else {
			$whereData = [['trx_uom', $_GET['trx_uom']], ['primary_uom', $_GET['primary_uom']], ['product_id', $_GET['product_id']], ['uom_conversion_id', '!=', $edit_id]];

			$group = \DB::table('m_uom_conversion_t')->where($whereData)->get();

		}
		// dd($group);


		if (count($group) > 0)
			return 1;
		else
			return 0;
	}
	/*End*/

	/*Get Primary Uom Function*/
	public function productprimaryuom($product_id = null)
	{
		$query = \DB::table('m_products_t')->select('primary_uom_id', 'trx_uom_id')->where('product_id', $product_id)->get();
		$uom_code = array();
		if (count($query) > 0) {
			$uom_code[0] = $query[0]->primary_uom_id;
			$uom_code[1] = $query[0]->trx_uom_id;

		}
		return $uom_code;
	}
	/*End*/


	public function getGridUomconData()
	{


		$SQL = "SELECT
		        tb_users.first_name,
		        m_uom_conversion_t.uom_conversion_id,
				m_uom_conversion_t.primary_uom,
				m_uom_conversion_t.active,
				m_products_t.concatenated_product,
				m_products_t.product_code,
				m_uom_conversion_t.product_id as product,
				m_uom_codes_t.uom_code,
				m_uom_codes_t.uom_code_id,
				u2.uom_code as trx_code,
				u2.uom_code_id as trx_id,
				m_uom_conversion_t.uom_value
				FROM `m_uom_conversion_t` 
				left join m_uom_codes_t on  m_uom_conversion_t.primary_uom = m_uom_codes_t.uom_code_id
				left join m_uom_codes_t u2 on m_uom_conversion_t.trx_uom=u2.uom_code_id left join m_products_t on m_products_t.product_id=m_uom_conversion_t.product_id left join `tb_users` on (tb_users.id=m_uom_conversion_t.created_by) where 1=1 ";


		$result = \DB::select($SQL);

		return DataTables::of($result)->make(true);


	}


}
