<?php

namespace App\Http\Controllers;
use App\Purchasepricelist;
use App\Purchasepricelistlines;
use App\productsetting;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
use session;
use Yajra\DataTables\DataTables;

class PurchasepricelistController extends Controller
{
	public $module = "purchasepricelist";
	public function __construct()
	{
		$this->data = array('pageModule' => 'purchasepricelist', 'pageUrl' => url('purchasepricelist'));
		$this->data['urlmenu'] = $this->indexs();
		$this->table = "i_pricelist_hdr_t";
		$this->subtable = "i_pricelist_lines_t";
		$this->model = new Purchasepricelist;
		$this->submodel = new Purchasepricelistlines;

		$this->data['pageMethod'] = \Request::route()->getName();

		if ($this->data['pageMethod'] == "salespricelist") {
			$this->pageModule = "salespricelist";
			$this->data['pageModule'] = $this->pageModule;
		} else {
			$this->pageModule = "purchasepricelist";
			$this->data['pageModule'] = $this->pageModule;
		}
		$this->data['pageFormtype'] = 'ajax';
	}

	/*Grid Data Function*/
	public function getGridmfgData($id = null)
	{

		$wh = '';


		if ($id == null || $id == "null") {
			$type = "";
		} else {
			$type = " and price_list_type='$id' ";
		}

		if (isset($_GET['price_list_type'])) {
			$ty = $_GET['price_list_type'];
			$type = " and price_list_type= '$ty'";
		}

		$comp = \Session::get('companyid');
		$empid = \Session::get('emp_id');
		$loca = \DB::table('hr_employee_t')->select('location_id')->where('employee_id', $empid)->get();
		$locarray = json_decode($loca[0]->location_id);
		$location = "";
		foreach ($locarray as $k => $v) {
			$location .= $v . ',';
		}
		$loc = rtrim($location, ",");

		$groupname = \Session::get('groupname');
		if ($groupname == "1" || $groupname == "Admin") {
			$wh .= "and i_pricelist_hdr_t.company_id=$comp";
		} else {
			$wh .= "and i_pricelist_hdr_t.company_id=$comp  and i_pricelist_hdr_t.location_id in ($loc)";
		}

		$app_id = \Session::get('id');

		if ($_GET['pagemethod'] == 'purchasepricelistapproval') {
			$wh .= " and i_pricelist_hdr_t.savestatus='INITIATED' and json_contains(i_pricelist_hdr_t.approver_id ,'" . $app_id . "')=1 ";
		}
		if ($_GET['pagemethod'] == 'salespricelistapproval') {
			$wh .= " and i_pricelist_hdr_t.savestatus='INITIATED' and json_contains(i_pricelist_hdr_t.approver_id ,'" . $app_id . "')=1 ";
		}

		$SQL = "SELECT  tb_users.first_name,i_pricelist_hdr_t.active,i_pricelist_hdr_t.savestatus,pricelist_hdr_id,pricelist_name,description,start_date,end_date,price_list_type FROM i_pricelist_hdr_t left join `tb_users` on (tb_users.id=i_pricelist_hdr_t.created_by) where 1=1 $type $wh order by i_pricelist_hdr_t.pricelist_hdr_id desc";

		$result = \DB::select($SQL);
		return DataTables::of($result)->make(true);

	}


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


		if ($this->data['pageMethod'] == "salespricelist") {
			$this->pageModule = "salespricelist";
			$this->data['pageModule'] = $this->pageModule;
		} else if ($this->data['pageMethod'] == "purchasepricelistapproval") {
			$this->pageModule = "purchasepricelistapproval";
			$this->data['pageModule'] = $this->pageModule;
		} else if ($this->data['pageMethod'] == "salespricelistapproval") {
			$this->pageModule = "salespricelistapproval";
			$this->data['pageModule'] = $this->pageModule;
		} else {
			$this->pageModule = "purchasepricelist";
			$this->data['pageModule'] = $this->pageModule;
		}
		$this->data['urlname'] = \Request::route()->getName();

		if ($this->data['urlname'] == "salespricelist" || $this->data['urlname'] == "salespricelistapproval") {
			$this->data['index_data'] = "Sales";
		} else if ($this->data['urlname'] == "salespricelistcopy") {
			$this->data['index_data'] = "Sales";
		} else {
			$this->data['index_data'] = "Purchase";
		}

		$table = \DB::table('i_pricelist_hdr_t')->get();
		$this->data['datas'] = json_encode($table);
		$this->data['pageMethod'] = \Request::route()->getName();

		return view('purchasepricelist.table', $this->data);
	}

	/*Create Function*/
	public function create($id = null)
	{

		$this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
		$this->data['urlname'] = \Request::route()->getName();

		// dd($this->data['urlname']);
		if ($this->data['urlname'] == "salespricelist") {
			$this->data['pageMethod'] = "salespricelist";
			$this->data['url'] = "salespricelist";
			$sornam = "salespricelist";
		} else {
			$this->data['pageMethod'] = \Request::route()->getName();
			$this->data['url'] = "purchasepricelist";
			$sornam = "purchasepricelist";
		}


		if (isset($id)) {

			$this->data['id'] = $id;
			$table = \DB::table('i_pricelist_hdr_t')->where('pricelist_hdr_id', $id)->get();
			$this->data['row'] = $table[0];
			$tablelines = \DB::table('i_pricelist_lines_t')->where('pricelist_hdr_id', $id)->get();
			$this->data['linedata'] = array();
			$this->data['linedata'] = $tablelines;


			if ($this->data['row']->price_list_type == "Sales") {
				$groupname = "FINISHED GOODS";

			} else {
				$groupname = "RAW MATERIALS";
			}

			$prdgrpid = $this->productgroup($groupname);


			foreach ($this->data['linedata'] as $key => $value) {

				$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, $sornam);

			}
			//kannan product assign pricelist//
			if (isset($_GET['products'])) {
				$this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'purchasepricelist');
				$this->data['product_select'] = explode(',', $_GET['products']);
				foreach ($this->data['product_select'] as $val) {

					$key++;
					$this->data['linedata'][$key] = (object) array();
					$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $val, $sornam);
					$this->data['linedata'][$key]->batch_number = "";
					$this->data['linedata'][$key]->line_no = $key;
					$this->data['linedata'][$key]->active = '';
					$this->data['linedata'][$key]->pricelist_line_id = '';
					$this->data['linedata'][$key]->unit_price = '';
					$this->data['linedata'][$key]->std_price = '';
					$this->data['linedata'][$key]->comments = '';
				}
			}
			
		} else {
			if (isset($_GET['products'])) {
				$this->data['products'] = explode(',', $_GET['products']);
			} else {
				$this->data['products'] = array();
			}

			$pricedata = \DB::connection()->getSchemaBuilder()->getColumnListing('i_pricelist_hdr_t');
			$pricedatas = (object) array();
			foreach ($pricedata as $key => $value) {
				$pricedatas->$value = "";
			}
			$this->data['row'] = $pricedatas;
			if ($this->data['urlname'] == "purchasepricelist") {
				$this->data['row']->price_list_type = "Purchase";
				$groupname = "RAW MATERIALS";
				$prdgrpid = $this->productgroup($groupname);

				$this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'purchasepricelist');
				$this->data['productid'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'purchasepricelist');

			} else {
				$this->data['row']->price_list_type = "Sales";
				$groupname = "FINISHED GOODS";
				$prdgrpid = $this->productgroup($groupname);
				$this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salespricelist');
				$this->data['productid'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salespricelist');

				$this->data['batch_number'] = "";
			}

			$this->data['id'] = '';
			$this->data['linedata'] = array();



			//kannan product assign pricelist//
			if (isset($_GET['product'])) {
				$this->data['product_select'] = explode(',', $_GET['product']);
				foreach ($this->data['product_select'] as $key => $val) {
					$this->data['productlist'][$key] = (object) array();
					$this->data['productlist'][$key]->product_id = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $val);

				}
				$this->data['linedata'] = $this->data['productlist'];
			}


			$this->data['row']->description = "";
			$this->data['row']->active = "";
			$this->data['row']->start_date = "";
			$this->data['row']->end_date = "";
			$this->data['row']->pricelist_line_id = "";
			$this->data['row']->status = "";
			$this->data['id'] = '';
			$this->data['linedata'] = array();

		}


		$this->data['row']->start_date = date("Y-m-d");


		if (isset($_GET['sr'])) {
			$this->data['product_url'] = $_GET['sr'];
		} else {
			$this->data['product_url'] = '';
		}
		return view('purchasepricelist.form', $this->data);

	}
	/*End*/

	/*Edit Function*/
	public function edit(Request $request, $id = null)
	{

		$this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
		$this->data['urlname'] = \Request::route()->getName();

		if ($this->data['urlname'] == "salespricelistedit") {
			$this->data['pageMethod'] = "salespricelist";
			$this->data['url'] = "salespricelist";
		} else if ($this->data['urlname'] == "salespricelistcopyedit") {
			$this->data['pageMethod'] = "salespricelistcopy";
			$this->data['url'] = "salespricelistcopy";
		} else if ($this->data['urlname'] == "purchasepricelistcopyedit") {
			$this->data['pageMethod'] = "purchasepricelistcopy";
			$this->data['url'] = "purchasepricelistcopy";
		} else if ($this->data['urlname'] == "salespricelistapproval") {
			$this->data['pageMethod'] = "salespricelist";
			$this->data['url'] = "salespricelist";
		} else {
			$this->data['pageMethod'] = "purchasepricelist";
			$this->data['url'] = "purchasepricelist";
		}

		$this->data['id'] = $id;
		$table = \DB::table('i_pricelist_hdr_t')->where('pricelist_hdr_id', $id)->get();

		$this->data['row'] = $table[0];

		$tablelines = \DB::table('i_pricelist_lines_t')->where('pricelist_hdr_id', $id)->get();

		$this->data['linedata'] = array();
		$this->data['linedata'] = $tablelines;
		if ($this->data['row']->price_list_type == "Sales") {
			$groupname = "FINISHED GOODS";

		} else {
			$groupname = "RAW MATERIALS";
		}

		$prdgrpid = $this->productgroup($groupname);

		if ($this->data['urlname'] == "salespricelistedit" || $this->data['urlname'] == "salespricelistcopyedit") {

			foreach ($this->data['linedata'] as $key => $value) {
				$prdid = $value->product_id;

				$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, ' and product_id=' . $value->product_id);

				$this->data['linedata'][$key]->batch_number = $value->batch_number;
			}

			$this->data['productid'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salespricelist');
			$this->data['uomcodeid'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');

		} else {

			$html = '<table class="overflow-y preview so_price_table">
<thead >
<tr>
<th >Line No</th>
<th >Product</th>
<th >&nbsp;</th>
<th>Unit price</th>
<th>Std price</th>
<th >Start Date</th>
<th>End Date</th>
<th >Active</th>
<th>&nbsp;</th>
</tr>
</thead>
<tbody class="i_price_lines_body">';
			$pdt = productsetting::where('module_name', 'purchasepricelist')->pluck('product_group_id')->first();

			if ($pdt != null) {

				$condition = " and product_group_id='$pdt'";
			} else {

				$condition = "";
			}

			foreach ($this->data['linedata'] as $key => $value) {
				$product_id = $this->jcustomproductselect1('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, $condition);
				$html .= '<tr class="clone rcopy">

    <td>
        <input type="hidden" name="bulk_pricelist_line_id[]" class="form-control input-sm bulk_pricelist_line_id" value="{{$value->pricelist_line_id}}">
    </td>
    <td>
        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="' . ($key + 1) . '" readonly="readonly" >
    </td>
    <td>
        <select name="bulk_product_id[]" class="select2 bulk_product_id" required="required">' . $product_id . '</select>
    </td>
    <td>
        <i class="fa fa-search productsearch"></i></td>
    <td>
        <input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price input_unit_width" value="' . $value->unit_price . '" minlength="1">
    </td>
    <td>
        <input type="text" name="bulk_std_price[]" class="form-control input-sm bulk_std_price input_unit_width" value="' . $value->std_price . '" minlength="1" >
    </td>
    <td>
        <input name="bulk_start_date[]" type="text" class="form-control input-sm datepicker bulk_start_date"  value="' . $value->start_date . '">
    </td>
    <td>
        <input name="bulk_end_date[]" type="text" class="form-control input-sm datepicker bulk_end_date"  value="' . $value->end_date . '">
    </td>
    <td>
        <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active" readonly>';
				$html .= '<option value="Yes" <?php if($value->active=="Yes"){ echo "selected"; }?>>Yes</option>
            <option value="No" <?php if($value->active=="No"){ echo "selected"; }?>>No</option>
        </select>
    </td>
    <td>
        <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
        <input type="hidden" name="counter[]">
    </td>
</tr>';

			}
			$html .= '</tbody>
</table>';


			if (count($this->data['linedata']) > 0) {
				foreach ($this->data['linedata'] as $key => $value) {

					$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, ' and product_id=' . $value->product_id);
				}
				$this->data['productid'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'purchasepricelist');
				$this->data['uomcodeid'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
			} else {
				$this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'purchasepricelist');
			}

		}


		if (isset($_GET['sr'])) {
			$this->data['product_url'] = $_GET['sr'];
		} else {
			$this->data['product_url'] = '';
		}
		//kannan product assign pricelist from productcreation//
		if (isset($_GET['products'])) {
			$this->data['product_select'] = explode(',', $_GET['products']);

			$i = count($this->data['linedata']);
			$prdgrpid = $this->productgroup($groupname);

			foreach ($this->data['product_select'] as $key => $val) {
				$this->data['linedata'][$i] = (object) array();
				$this->data['linedata'][$i]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $val, ' and product_group_id=' . $prdgrpid[0]->product_group_id);
				$this->data['linedata'][$i]->line_no = $key;
				$this->data['linedata'][$i]->active = '';
				$this->data['linedata'][$i]->pricelist_line_id = '';
				$this->data['linedata'][$i]->unit_price = '';
				$this->data['linedata'][$i]->std_price = '';
				$this->data['linedata'][$i]->comments = '';
				$i++;
			}

		}

		return view('purchasepricelist.form', $this->data);
	}



	/*Save Function*/

public function save(Request $request)
{
    $id = '';

    // Get form data except unwanted fields
    $form = $request->except([
        '_token',
        'form_config',
        'form_data_json',
        'savestatus',
        'submit_type',
        'choosefile',
        'existing_file',
        'enable-masterdetail',
        'edit_id',
        'url'
    ]);

    // Normalize "bulk_" keys once
    $form = $this->normalizeLineFormKeys($form);

    // Build header data
    $data = $this->validatePost($form, $this->table, 'header');
	$data['active'] = $request->bulk_active[0];
	$data['start_date'] = $request->bulk_start_date[0];
	$data['end_date'] = $request->bulk_end_date[0];

    $lines_data = [];

    // Always drive loop by product array (real rows)
 	$products = $request->input('bulk_product_id', []);

	foreach ($products as $i => $pid) {

    if (empty($pid)) {
        continue; // skip empty/ghost rows
    }

    // Use same index $i to read other fields
    $lines_data['pricelist_line_id'][$i] = $request->pricelist_line_id[$i] ?? null;
    $lines_data['line_no'][$i]           = $request->bulk_line_no[$i] ?? null;
    $lines_data['product_id'][$i]        = $pid;
    $lines_data['batch_number'][$i]      = $request->bulk_batch_number[$i] ?? null;
    $lines_data['unit_price'][$i]        = $request->bulk_unit_price[$i] ?? null;
    $lines_data['std_price'][$i]         = $request->bulk_std_price[$i] ?? null;
    $lines_data['active'][$i]            = $request->bulk_active[$i] ?? null;
    $lines_data['start_date'][$i]        = $request->bulk_start_date[$i] ?? null;
    $lines_data['end_date'][$i]          = $request->bulk_end_date[$i] ?? null;

    // audit fields
    $lines_data['created_by'][$i]      = \Session::get('id');
    $lines_data['last_updated_by'][$i] = \Session::get('id');
    $lines_data['created_at'][$i]      = now();
    $lines_data['updated_at'][$i]      = now();
    $lines_data['location_id'][$i]     = \Session::get('location');
    $lines_data['company_id'][$i]      = \Session::get('companyid');
    $lines_data['organization_id'][$i] = \Session::get('organization');
}

		//dd($data,$lines_data);
    \DB::beginTransaction();
    try {

        $data['savestatus'] = $request->savestatus;

        if ($request->savestatus == 'INITIATED') {

            $t = 0;
            if ($request->price_list_type == 'Sales') {
                $approverid = $this->Approvaldatacheck('salesprice', $t);
            } else {
                $approverid = $this->Approvaldatacheck('purchaseprice', $t);
            }

            if ($approverid == "0") {
                $data['approver_id'] = \Session::get('id');
                $data['savestatus']  = "APPROVED";
            } else {
                $data['approver_id'] = $approverid;
            }
        }

        // Save header
        $id = $this->model->insertRow($data);

        // Save lines
        $lid = $this->submodel->subgridSave($lines_data, $id);

        \DB::commit();

        $action = "Create";
        $this->auditlog($id, "purchasepricelist", $action, $_POST, "pricelist_hdr_id");

        return response()->json([
            'status'  => 'success',
            'message' => 'Saved Successfully',
            'id'      => $id,
            'lid'     => $lid
        ]);

    } catch (\Illuminate\Database\QueryException $e) {

        \DB::rollback();

        $message = explode('(', $e->getMessage());
        $dbCode  = rtrim($message[0], ']');
        $dbCode  = trim($dbCode, '[');

        return response()->json([
            'status'  => 'error',
            'message' => 'DatabaseError:=>' . $dbCode . "\n"
        ]);
    }
}


	/*End*/
	/*Delete Function*/
	public function destroy(Request $request, $id = null, $type = null)
	{

		if ($type == "Sales") {
			$column = array('pricelist_id', 'quote_pricelist_id', 'pricelist_id', 'invoice_pricelist_id');
			$table = array('m_customers_t', 's_quote_hdr_t', 's_salesorder_hdr_t', 's_invoice_hdr_t');
		} else {
			$column = array('default_pricelist_id', 'quote_pricelist_id', 'po_pricelist_id', 'invoice_pricelist_id');
			$table = array('m_supplier_t', 'p_quotation_hdr_t', 'p_po_hdr_t', 'p_po_invoice_hdr_t');
		}
		for ($i = 0; $i < count($table); $i++) {
			$j = 0;
			$query = \DB::table($table[$i])->where($column[$i], $id)->get();
			if (count($query) > 0) {
				$j = 1;
				return $j;
				break;
			}
		}
		if ($j == 0) {
			$query = \DB::table('i_pricelist_hdr_t')->where('pricelist_hdr_id', $id)->delete();
			/**Auditlog**/
			$this->auditlog($id, "purchasepricelist", "delete", "", "i_pricelist_hdr_t");
		}
		return $j;



	}
	/*End*/
	public function tabledata()
	{

		$table = \DB::table('users')->get();
		$this->data['datas'] = $table;
		return $table;
	}
	/*View Function*/
	public function view($id = null)
	{
		$this->data['urlname'] = \Request::route()->getName();
		// dd($this->data['urlname']);
		if ($this->data['urlname'] == "salespricelistview") {
			$this->data['pageMethod'] = "salespricelist";
			$this->data['url'] = "salespricelist";
		} else if ($this->data['urlname'] == "salespricelistcopyview") {
			$this->data['pageMethod'] = "salespricelistcopy";
			$this->data['url'] = "salespricelistcopy";
		} else if ($this->data['urlname'] == "purchasepricelistcopyview") {
			$this->data['pageMethod'] = "purchasepricelistcopy";
			$this->data['url'] = "purchasepricelistcopy";
		} else {
			$this->data['pageMethod'] = "purchasepricelist";
			$this->data['url'] = "purchasepricelist";
		}

		if (isset($id)) {
			//$this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing('i_pricelist_hdr_t');  
			//$this->data['values']=$values=Purchasepricelist::find($id);

			//$date=$this->dateform($values['start_date']);

			$this->data['values'] = $values = \DB::table('i_pricelist_hdr_t')->select('i_pricelist_hdr_t.*', 'tb_users.username')
				->leftjoin('tb_users', 'tb_users.id', '=', 'i_pricelist_hdr_t.created_by')
				->where('pricelist_hdr_id', $id)->get();
			$this->data['pricelist_name'] = $values[0]->pricelist_name;
			$this->data['price_list_type'] = $values[0]->price_list_type;
			$this->data['description'] = $values[0]->description;
			$this->data['active'] = $values[0]->active;
			$this->data['username'] = $values[0]->username;
			$tablelines = \DB::table('i_pricelist_lines_t')->leftjoin('m_products_t', 'i_pricelist_lines_t.product_id', '=', 'm_products_t.product_id')->select('i_pricelist_lines_t.*', 'm_products_t.concatenated_product')->where('pricelist_hdr_id', $id)->get();
			$this->data['linedata'] = $tablelines;
			//dd($this->data);
			return view('purchasepricelist.view', $this->data);
		}




	}

	/*End*/




	public function design($id = null)
	{
		//$table = \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id',0)->get();
		$this->data['row'] = (object) array();
		//$this->data['row']->inquiry_no = "";
		$this->data['row']->pricelist_name = "";
		$this->data['row']->description = "";
		$this->data['row']->start_date = "";
		$this->data['row']->end_date = "";
		//$this->data['row']->remarks = "";
		$this->data['id'] = '';



		$this->data['product_id'] = $this->jCombo('products_final_t', 'product_id', 'concatenated_product', '');


		return view('purchasepricelist.form_design', $this->data);
	}


	function findPrimarykey($table)
	{
		$primaryKey = '';
		foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
			$primaryKey = $key->Field;
		}
		return $primaryKey;
	}

	/* code for getting product groupid */
	public function productgroup($name = null)
	{
		$sql = DB::select("select product_group_id from m_product_groups_t where group_name ='$name'");
		return $sql;
	}

	/*Pricelist Name Check Function*/
	public function pricelistcheckname(Request $request)
	{
		$edit_id = $_GET['edit_id'];

		if ($edit_id == '') {
			$price = \DB::table('i_pricelist_hdr_t')->where('pricelist_name', $_GET['pricelist_name'])->where('price_list_type', $_GET['price_list_type'])->get();
		} else {
			$whereData = [['pricelist_name', $_GET['pricelist_name']], ['price_list_type', $_GET['price_list_type']], ['pricelist_hdr_id', '!=', $edit_id]];
			$price = \DB::table('i_pricelist_hdr_t')->where($whereData)->get();
		}
		if (count($price) > 0)
			return 1;
		else
			return 0;


	}
	/*End*/
	public function salespricelistbatch($id = null)
	{
		$sql = DB::select("select qoh_detail_id,sum(qoh_trx_qty) as qoh from i_qoh_detail_t where product_id =" . $id . " group by batch_number");
		if (count($sql) > 0) {
			$qohid = "";
			foreach ($sql as $key => $val) {
				if ($val->qoh > 0) {
					$qohid .= $val->qoh_detail_id . ",";
				}
			}
			$qohid1 = trim($qohid, ",");
			return $qohid1;
		} else {
			return 0;
		}
	}
}