<?php
namespace App\Http\Controllers;
use App\salesinquiry;
use App\soinquirylines;
use Illuminate\Http\Request;
use App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use DB;
use Yajra\DataTables\DataTables;

class SalesinquiryController extends Controller
{
    public $module = "salesinquiry";
    public function __construct()
    {
        $this->data = array();
        $this->model = new Salesinquiry;
        $this->submodel = new Soinquirylines;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'salesinquiry';
        $this->table = "s_inquiry_hdr_t";
        $this->subtable = "s_inquiry_lines_t";
        $this->middleware('auth');
        $this->data['urlmenu'] = $this->indexs();
        $this->data['date_format'] = $this->dateform();
        if ($this->data['pageMethod'] == "salesinquiry") {
            $this->data['inquiry_status'] = "";
        } else if ($this->data['pageMethod'] == "SalesOrderFromEnquiry" || $this->data['pageMethod'] == "copysalesinquiry") {
            $this->data['inquiry_status'] = "INITIATED";
        } else {
            $this->data['inquiry_status'] = "INITIATED";
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

        $table = \DB::table('s_inquiry_hdr_t')->get();
        $this->data['datas'] = json_encode($table);
        /************************************** Selected Customer in Index***********************************/
        $cust = \DB::select('select distinct customerid from s_inquiry_hdr_t');
        if (!empty($cust)) {
            $ids = array();
            foreach ($cust as $key => $val) {
                $ids[] = $val->customerid;
            }
            $a = implode(",", $ids);
            $condition = "and customer_id IN($a)";
        } else {
            $condition = "";
        }

        $this->data['cusnameopt'] = $this->jqgridcustselect('m_customers_t', 'customer_id', 'customer_name', $condition);
        $this->data['cusnumber'] = $this->jqgridcustselect("m_customers_t", 'customer_id', 'customer_number', $condition);
        $this->data['pjtopt'] = $this->jqgridcustselect('m_projects_t', 'project_id', 'project_name', '');
        return view('salesinquiry.table', $this->data);
    }

    /** Save data Start **/
    public function create($id = null, $enquirytype = null, $quote_status = null)
    {
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'salesinquiry')->get();
        $this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salesinquiry');
       
        if ($id == '0') {

            if ($enquirytype != "LABOUR")
                $enquirytype = "STANDARD";
            else
                $enquirytype = $enquirytype;

            $this->modelname = new Salesinquiry();
            //$table = $this->modelname->getTableColumns();
            $this->data['row'] = (object) array();
            $this->data['row']->inquiry_type = $enquirytype;
            $table = $this->modelname->getTableColumns();
            foreach ($table as $key => $val) {
                $this->data['row']->$val = '';
            }
            $dataformate = $this->dateform();
            $this->data['row']->inquiry_no = "";
            $this->data['row']->source_type_id = $this->jcustomselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code', '', ' and lookup_type="ENQUIRY_SOURCE"');
            $this->data['row']->inquiry_type = $enquirytype;
            $this->data['row']->inquiry_date = date('Y-m-d');
            $this->data['id'] = '';
            $this->data['linedata'] = array();
            $this->data['customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', '156', 'and savestatus="SAVE" or status="QUICKCUSTOMER" and active="Yes"');
            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
            $this->data['created_by'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');
            $this->data['source_type_id'] = $this->jcustomselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code', '', ' and lookup_type="ENQUIRY_SOURCE"');
            $this->data['salespersons_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', '');
            $this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salesinquiry');
            $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['inquiry_status'] = 'DRAFT';
            $this->data['part_no'] = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '', '');
        } else {

            if ($this->data['pageMethod'] == 'copysalesinquiry') {
                $this->data['id'] = $id;
                $table = \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id', $id)->get();
                $this->data['row'] = $table[0];
                $this->data['inquiry_status'] = 'INITIATED';

                $this->data['row']->inquiry_no = "";
                $this->data['row']->so_inquiry_hdr_id = "";
                $tablelines = \DB::table('s_inquiry_lines_t')->where('so_inquiry_hdr_id', $id)->get();
                $this->data['linedata'] = $tablelines;
                if (count($this->data['linedata']) >= 1) {
                    foreach ($this->data['linedata'] as $key => $value) {
                        $this->data['linedata'][$key]->so_inquiry_lines_id = '';
                    }
                }

                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
                $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->customerid);
                $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
                $this->data['source_type_id'] = $this->jcustomselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code', $table[0]->source_type_id, ' and lookup_type="ENQUIRY_SOURCE"');
                $this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salesinquiry');
                $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
                ;
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
            } else {

                $this->data['id'] = $id;
                $table = \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id', $id)->get();
                $this->data['row'] = $table[0];
                $tablelines = \DB::table('s_inquiry_lines_t')->where('so_inquiry_hdr_id', $id)->get();

                $this->data['linedata'] = $tablelines;
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
                $this->data['pageMethod'] = 'salesinquiry';
                $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->customerid);

                $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
                $this->data['source_type_id'] = $this->jcustomselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code', $table[0]->source_type_id, ' and lookup_type="ENQUIRY_SOURCE"');
                $this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salesinquiry');
                $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
                $this->data['inquiry_status'] = $table[0]->inquiry_status;
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);

            }
        }
        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->part_no = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $table[0]->customerid);
                if ($value->product_id != "") {
                    $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id =' . $value->product_id);
                } else {
                    $this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'salesinquiry');
                }
                $this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);

            }
        }
        return view('salesinquiry.form', $this->data);
    }




    /** Sale Inquiry Manfacturpart load data Start **/
    public function manufacturepart($id = null, $id1 = null)
    {

        $data = \DB::table('m_manufacturer_partno_t')->where('product_id', $id)->where('manufacturer_source_value_id', $id1)->get();

        if (count($data) > 0) {
            return $data[0]->manufacturer_partno_id;
        } else {
            return 0;
        }
    }


    /** Sale Inquiry Save data Start **/
    public function save(Request $request)
    {

			$id='';
			$form = $request->all();
	        $form = $request->except([
        '_token','form_config','form_data_json','submit_type',
        'choosefile','existing_file','customerTable_length','productTable_length',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');

        $old_date = $_POST['submission_duedate'];
        $inquiry_date = $_POST['inquiry_date'];
        $data['submission_duedate'] = date("Y-m-d", strtotime($old_date));
        $data['inquiry_date'] = date("Y-m-d", strtotime($inquiry_date));

        if ($_POST['inquiry_no'] == "") {

            $seqno = $this->Seqnoe('SOINQ', 's_inquiry_hdr_t', $_POST['inquiry_type'], 'soinquiry_count');
            $data['inquiry_no'] = $seqno[0];
            $data['soinquiry_count'] = $seqno[1];
        } else {
            $seqno[0] = $_POST['inquiry_no'];

        }
        if ($_POST['inquiry_status'] == "INITIATED") {
            $inquiry_status = 'Saved Successfully';
        } else {
            $inquiry_status = 'Draft Saved Successfully';
        }

        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            \DB::commit();
            if ($_POST['inquiry_status'] == "INITIATED") {
                $this->salesinquirymailsend($id);
            }
            return response()->json(array('status' => 'success', 'message' => $inquiry_status, 'id' => $id, 'lid' => $lid, 'auto_no' => $seqno[0]));

        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }
	



    /** Sale Inquiry Print data Start **/
    public function getPrint($id = null)
    {
        $header = Salesinquiry::where('so_inquiry_hdr_id', $id)->get();
        $id = $header[0]->so_inquiry_hdr_id;
        $cus_id = $header[0]->customerid;

        $customer = \DB::table('m_customers_t')->where('customer_id', $cus_id)->get();
        $this->data['customer_name'] = $customer[0]->customer_name;



        $address_site = \DB::table('m_customer_sites_t')->where('customer_id', $id)->where('site_type', 'SHIP_TO')->where('active', 'Yes')->where('primary_address', 'YES')->get();
        if ($address_site->isNotEmpty()) {
            $this->data['to_address'] = $address_site[0]->address;
            $to_address = $this->data['to_address'];
            $this->data['city'] = $this->getCity($address_site[0]->city);
            $city_name = $this->data['city'][0]->city_name;
            $this->data['country'] = $this->getCountry($address_site[0]->country);
            $country_name = $this->data['country'][0]->country_name;
            $this->data['state'] = $this->getState($address_site[0]->state);
            $state_name = $this->data['state'][0]->state_name;
            $this->data['to_address'] = $this->data['to_address'] . "," . $city_name . "," . $state_name . "," . $country_name;
        } else {
            $this->data['to_address'] = "";
        }




        if ($header) {
            $dt_format = \Session::get('php_dateformat');
            $header->inquiry_date = date($dt_format, strtotime($header[0]->inquiry_date));
            $this->data['header'] = $header;
        } else {

            $this->data['header'] = $this->model->getColumnTable('s_inquiry_hdr_t');

        }

        $linesdata = \DB::table('s_inquiry_hdr_t as ih')
            ->leftjoin('s_inquiry_lines_t as il', 'ih.so_inquiry_hdr_id', '=', 'il.so_inquiry_hdr_id')
            ->leftjoin('m_products_t as pr', 'il.product_id', '=', 'pr.product_id')
            ->leftjoin('m_uom_codes_t as uom', 'il.uom_code_id', '=', 'uom.uom_code_id')
            ->select('pr.concatenated_product', 'pr.hsn_code', 'uom.uom_code', 'il.line_no', 'il.comments', 'ih.so_inquiry_hdr_id', 'il.product_description', 'il.required_qty', 'il.need_by_date', 'pr.product_code')
            ->where('ih.so_inquiry_hdr_id', $id)
            ->get();
        if ($linesdata->isNotEmpty()) {
            $this->data['product_id'] = $linesdata[0]->concatenated_product;
            $this->data['product_code'] = $linesdata[0]->product_code;

            $this->data['hsn_code'] = $linesdata[0]->hsn_code;
            $this->data['uom_code'] = $linesdata[0]->uom_code;
            $this->data['required_qty'] = $linesdata[0]->required_qty;
            $this->data['comments'] = $linesdata[0]->comments;
        } else {
            $this->data['product_id'] = '';
            $this->data['hsn_code'] = '';
            $this->data['uom_code'] = '';
            $this->data['required_qty'] = '';
            $this->data['comments'] = '';
        }


        $address = $this->getLocationwiseaddress($header[0]->location_id);

        if ($address != 0) {
            $location_name = $address[0]->location_name;
            $this->data['location_name'] = $location_name;
            $address1 = $address[0]->address;
            $this->data['address'] = $address1;
            $street = $address[0]->street_name;
            $this->data['street_name'] = $street;
            $location = $address[0]->location_name;
            $this->data['location'] = $location;
            $area = $address[0]->area;
            $this->data['area'] = $area;
            //GET COMPANY ADDRESS
            $this->data['company_gst_no'] = $address[0]->gst_no;

            $city = $this->data['city'] = $this->getCity($address[0]->city_id);
            $city_l = $city[0]->city_name;
            $state = $this->data['state'] = $this->getState($address[0]->state_id);
            $state_l = $state[0]->state_name;
            $country = $this->data['country'] = $this->getCountry($address[0]->country_id);
            $country_l = $country[0]->country_name;


        }

        $this->data['company_address'] = $address1 . "," . $street . "," . $area . "," . $city_l . "," . $state_l . "," . $country_l;

        if ($address == 0) {
            array_push($l_error, "Check Organisation or company Details");
        }

        /********************* company *******************/
        $company = $this->getCompany($header[0]->company_id);
        if (!empty($company)) {
            $company_name = $company[0]->company_name;
            $this->data['company_name'] = $company_name;
            $this->data['cin_no'] = $company[0]->cin_no;
            $this->data['gst_no'] = $company[0]->gst_no;
        }

        /******************** end ************************/
        $this->data['company_logo'] = \Session::get('companylogo');
        $this->data['lines'] = $linesdata;
        $this->data['print'] = "PRINT";
        $this->data['print_val'] = '1';

        $terms_condition = \DB::table('a_rpt_displayelements_hdr_t')->leftjoin('a_rpt_displayelements_lines_t', 'a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id', '=', 'a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id')->select('a_rpt_displayelements_lines_t.element_content')->where('a_rpt_displayelements_hdr_t.report_source', 141)->where('a_rpt_displayelements_lines_t.element_name', 'TERMS&CONDITIONS')->get();

        if (count($terms_condition) > 0) {
            $this->data['terms_condition'] = $terms_condition;
        } else {
            $this->data['terms_condition'] = [];
        }



        return view('salesinquiry.soinquiry_print', $this->data);
    }
    /** Sale Inquiry Print data End **/

    /** Sale Inquiry Save Column data Start **/
    public function saveshowcolumn(Request $request)
    {
        //dd($_POST);
        $type = $_GET['type'];
        $a = explode(',', $type);


        foreach ($a as $val) {

            // $action=$_POST['required'][$val];
            \DB::update("update m_showcolumn_permission_t set active=1,action='' where module_name='salesinquiry' and column_hide='$val' ");
        }
        $table = \DB::table('s_inquiry_hdr_t')->get();
        $this->data['datas'] = json_encode($table);
        $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');

        return 1;
    }
    /** Sale Inquiry Save Column data End **/

    /** Sale Inquiry Save Company load data Start **/
    function getCompany($company = null)
    {
        $sql = array();
        $sql = \DB::SELECT("SELECT company_id,company_name,cin_no,gst_no FROM `m_company_t` WHERE `company_id`=" . $company . "");
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    /** Sale Inquiry Save Company load data END **/



    /** Sale Inquiry Save load data Start **/
    function insertLines($model_name, $primaryline, $data, $id)
    {

        $input = Input::all();

        $oldid = \DB::table('s_inquiry_lines_t')->where('so_inquiry_hdr_id', $id)->get();

        if ($oldid->isEmpty()) {
            for ($i = 0; $i < count($_POST['counter']); $i++) {
                $data['so_inquiry_hdr_id'] = $id;
                $data['so_inquiry_lines_id'] = $_POST['bulk_so_inquiry_lines_id'][$i] ? $_POST['bulk_so_inquiry_lines_id'][$i] : 0;
                $data['line_no'] = 1;
                $data['product_id'] = $_POST['bulk_product_id'][$i];
                $data['uom_code_id'] = $_POST['bulk_uom_code_id'][$i];
                $data['required_qty'] = $_POST['bulk_required_qty'][$i];
                $data['need_by_date'] = $_POST['bulk_need_by_date'][$i];
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                \DB::table('s_inquiry_lines_t')->insert($data);
            }
        } else {

            $existingId = array();
            foreach ($oldid as $key => $value) {
                $oldIds[] = $value->$primaryline;
            }

            foreach ($_POST['bulk_' . $primaryline] as $val) {
                $newIds[] = $val;
            }
            $existingId = array_replace($newIds, $oldIds);
            $oldcount = count($oldIds);
            $newcount = count($newIds);
            if ($oldcount <= $newcount) {

                for ($i = 0; $i < $newcount; $i++) {
                    $data1['so_inquiry_hdr_id'] = $id;
                    $data1['so_inquiry_lines_id'] = $_POST['bulk_so_inquiry_lines_id'][$i] ? $_POST['bulk_so_inquiry_lines_id'][$i] : 0;
                    $data1['line_no'] = 1;
                    $data1['product_id'] = $_POST['bulk_product_id'][$i];
                    $data1['uom_code_id'] = $_POST['bulk_uom_code_id'][$i];
                    $data1['required_qty'] = $_POST['bulk_required_qty'][$i];
                    $data1['need_by_date'] = $_POST['bulk_need_by_date'][$i];
                    $data1['created_at'] = date('Y-m-d H:i:s');
                    $data1['updated_at'] = date('Y-m-d H:i:s');

                    if ($data['so_inquiry_lines_id'] = $existingId[$i]) {

                        $this->modelline::find($data1['so_inquiry_lines_id'])->update($data1);

                    } else {

                        \DB::table('s_inquiry_lines_t')->insert($data1);

                    }
                }
            } else {
                $arraydiff = array_diff($oldIds, $newIds);
                foreach ($arraydiff as $key) {
                    if (($key = array_search($key, $oldIds)) !== false) {
                        unset($oldIds[$key]);
                    }
                }

                foreach ($arraydiff as $val) {
                    \DB::table('s_inquiry_lines_t')->where('so_inquiry_lines_id', $val)->delete();
                }
                for ($i = 0; $i < count($oldIds); $i++) {
                    $data['so_inquiry_hdr_id'] = $id;
                    $data['so_inquiry_lines_id'] = $_POST['bulk_so_inquiry_lines_id'][$i] ? $_POST['bulk_so_inquiry_lines_id'][$i] : 0;
                    $data['line_no'] = 1;
                    $data['product_id'] = $_POST['bulk_product_id'][$i];
                    $data['uom_code_id'] = $_POST['bulk_uom_code_id'][$i];
                    $data['required_qty'] = $_POST['bulk_required_qty'][$i];
                    $data['need_by_date'] = $_POST['bulk_need_by_date'][$i];
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $this->modelline::find($data['so_inquiry_lines_id'])->update($data);
                }

            }

        }
    }
    /** Sale Inquiry Save load data End **/

    /** Sale Inquiry Desgin data Start **/
    public function design($id = null)
    {
        //$table = \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id',0)->get();
        $this->data['row'] = (object) array();
        $this->data['row']->inquiry_no = "";
        $this->data['row']->inquiry_category = "";
        $this->data['row']->so_inquiry_hdr_id = "";
        $this->data['row']->inquiry_type = "";
        $this->data['row']->inquiry_date = "";
        $this->data['row']->remarks = "";
        $this->data['id'] = '';
        $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', '');
        $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');
        $this->data['salespersons_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', '');
        $this->data['product_id'] = $this->jCombo('products_final_t', 'product_id', 'concatenated_product', '');
        $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
        return view('soinquiryhdr.form_design', $this->data);
    }


    public function getGridData()
    {
        $wh = '';

        $col_name = '';
        if ($_GET['status'] != '') {
            $wh .= "and inquiry_status='" . $_GET['status'] . "'";
            $col_name = "inquiry_status";
            $op = "=";
            $status_val = "'" . $_GET['status'] . "'";
        }
        if ($_GET['pageMethod'] == 'SalesOrderFromEnquiry') {
            $wh .= "and order_status='0'";
            $col_name = "order_status";
            $op = "=";
            $status_val = 0;
        }
        $com = \Session::get('companyid');

        if ($col_name != '') {
            $wh .= $grid_data = $this->grid_statuscheck('s_inquiry_hdr_t', 'inquiry_date', $col_name, $op, $status_val);
        } else {
            $wh .= $grid_data = $this->grid_check('s_inquiry_hdr_t', 'inquiry_date');
        }


        $SQL = "SELECT
			s_inquiry_hdr_t.so_inquiry_hdr_id as so_inquiry_hdr_id,
			s_inquiry_hdr_t.inquiry_no,
			s_inquiry_hdr_t.inquiry_date,
			s_inquiry_hdr_t.inquiry_type,
			s_inquiry_hdr_t.savestatus,
			s_inquiry_hdr_t.inquiry_status,
			s_inquiry_hdr_t.remarks,
			m_organizations_t.organization_name as organization_id,
			m_customers_t.customer_name,
			m_customers_t.customer_number,
			m_projects_t.project_name as project_id
			FROM `s_inquiry_hdr_t` s_inquiry_hdr_t
			left join m_customers_t cust on(
			cust.customer_id=s_inquiry_hdr_t.`customerid`)

			left join m_customers_t  on(
			m_customers_t.customer_id=s_inquiry_hdr_t.`customerid`)

			left join m_organizations_t on(
			m_organizations_t.organization_id=s_inquiry_hdr_t.`organization_id`)

			left join m_projects_t on
			(m_projects_t.project_id = s_inquiry_hdr_t.project_id)
			where 1=1 $wh and s_inquiry_hdr_t.company_id=$com order by s_inquiry_hdr_t.so_inquiry_hdr_id DESC";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }

    public function getshowcolumns(Request $request)
    {
        $this->modelname = new Salesinquiry();
        $id = '';
        $data = $this->modelname->subgridRead($id);
        return $data['label_data'];
    }
    /** Sale Inquiry Get Show Columns End **/

    /** Sale Inquiry Get Loaction Address Start **/
    function getLocationwiseaddress($location = null)
    {

        $sql = array();

        $sql = \DB::SELECT("SELECT * from `m_location_t` where `location_id`=" . $location . "");

        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    /** Sale Inquiry Get Loaction Address End **/

    /** Sale Inquiry Get Country NAME Start **/
    function getCountry($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT("SELECT country_id,country_name FROM `m_countries_t` WHERE `country_id`=" . $id . "");

        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    /** Sale Inquiry Get Country NAME END **/

    /** Sale Inquiry Get State NAME Start **/
    function getState($id = null)
    {

        $sql = array();
        $company = \Session::get('location');
        $sql = \DB::SELECT("SELECT state_id,state_name,state_code FROM `m_states_t` WHERE `state_id`=" . $id . "");
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    /** Sale Inquiry Get State NAME End **/

    /** Sale Inquiry Get City NAME Start **/
    function getCity($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT("SELECT city_name FROM `m_cities_t` WHERE `city_id`=" . $id . "");
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    /** Sale Inquiry Get City NAME End **/


    /** Sale Inquiry Find Primary Key Start **/
    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }
    /** Sale Inquiry Find Primary Key End **/

    /** Sale Inquiry Show Start **/
    function view($id = null)
    {
        $this->data['closeredirect'] = \Request::route()->getName();

        if ($this->data['closeredirect'] == "copysalesinquiryview") {
            $this->data['closeredirect'] = "copysalesinquiry";
        } else {
            $this->data['closeredirect'] = "salesinquiry";
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
    /** Sale Inquiry Show End **/

    /** Sale Inquiry Delete Start **/
    public function delete($id = null)
    {
        $count = 0;
        $queryquote = \DB::table('s_quote_hdr_t')->where('so_inquiry_hdr_id', $id)->count();
        if ($queryquote >= 1) {
            $count++;
        }
        if ($count <= 0) {
            $query = \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id', $id)->delete();
            $query = \DB::table('s_inquiry_lines_t')->where('so_inquiry_hdr_id', $id)->delete();
            if ($query) {
                return 0;
            } else {
                return 1;
            }
        } else {
            return 2;
        }
    }
    /** Sale Inquiry Delete End **/

    /** Sale Inquiry Status Start **/
    public function getStatus($id = null)
    {
        $sale_id = $id;
        $salesinquery = DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id', $sale_id)->get();
        $status_type = $salesinquery[0]->savestatus;
        return $status_type;

    }
    /** Sale Inquiry Status End **/

    /** Sale Inquiry Copy Inquiry data  Start **/
    public function copyinquirydata($id = null)
    {

        $this->data['id'] = $id;
        $table = \DB::table('s_quote_hdr_t')->where('quote_hdr_id', $id)->get();
        $this->data['row'] = $table[0];
        $this->data['row']->quote_no = "";
        $this->data['row']->quote_hdr_id = "";

        $tablelines = \DB::table('s_quote_lines_t')->where('quote_hdr_id', $id)->get();
        $this->data['linedata'] = $tablelines;
        $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', $table[0]->customer_id);
        $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
        $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
        //$this->data['quote_pricelist_id'] = $this->jCombo('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$table[0]->quote_pricelist_id);
        $this->data['quote_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->quote_pricelist_id, ' and price_list_type="Sales"');
        $this->data['salesperson_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', $table[0]->salesperson_id);
        $this->data['product_id'] = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salesinquiry');
        $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
        $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
        $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
        $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
        $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');

        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'salesinquiry');
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

        return view('salesinquiry.form', $this->data);

    }
    /** Sale Inquiry Copy Inquiry data End **/

    /** Sale Inquiry Copy Sale Inquiry data  Start **/
    public function copysalesinquiry()
    {
        $this->data['pageMethod'] = "copysalesinquiry";
        return view('salesinquiry.table', $this->data);
    }
    /** Sale Inquiry Copy Sale Inquiry data  End **/

    /** Sale Inquiry Get Product Name data  Start **/
    function getProduct($id = null)
    {
        $product = array();
        $product = \DB::table('m_products_t as pdt')
            ->leftJoin('m_uom_codes_t as uom', 'uom.uom_code_id', '=', 'pdt.trx_uom_id')
            ->select('uom.uom_code', 'pdt.concatenated_product', 'pdt.hsn_code')
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
            $product['hsn_code'] = $product[0]->hsn_code;

            return $product;

        } else {
            return 0;
        }

    }
    /** Sale Inquiry Get Product Name data End **/


    public function salesinquirydetailsindex()
    {

        //$this->data['pageMethod']='Supplierbasedcostrpt'; 
        return view('salesinquiry.salesinquirydetailsrpt', $this->data);

    }

    public function getsalesinquirydetails(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $wh = '';

        $groupname = \Session::get('groupname');

        if ($groupname == '1' || $groupname == '4' || $groupname == '11' || $groupname == '15') {
            $wh = " ";
        } else {
            $wh = " and s_inquiry_hdr_t.inquiry_status = 'INITIATED'";
        }


        $SQL = "SELECT * from (
SELECT
    s_inquiry_hdr_t.so_inquiry_hdr_id,
    s_inquiry_hdr_t.inquiry_no,
    s_inquiry_hdr_t.inquiry_date,
    s_inquiry_hdr_t.inquiry_type,
    s_inquiry_hdr_t.reference_number,
    s_inquiry_hdr_t.source,
    s_inquiry_hdr_t.remarks,
    s_inquiry_hdr_t.inquiry_status,
    s_inquiry_hdr_t.tender_id,
    s_inquiry_hdr_t.tender_ref_no,
    s_inquiry_hdr_t.submission_duedate,
    s_inquiry_hdr_t.emd_details,
    s_inquiry_hdr_t.title_of_work,
    s_inquiry_hdr_t.due_date,
    s_inquiry_hdr_t.tittle_of_work,
    s_inquiry_hdr_t.order_status,
    s_inquiry_hdr_t.attachment_file,
    s_inquiry_lines_t.part_no,
    s_inquiry_lines_t.product_description,
    s_inquiry_lines_t.required_qty,
    s_inquiry_lines_t.need_by_date,
    s_inquiry_lines_t.comments,
    m_customers_t.customer_name,
    m_products_t.concatenated_product,
    m_uom_codes_t.uom_code
FROM
    s_inquiry_hdr_t
LEFT JOIN s_inquiry_lines_t ON s_inquiry_hdr_t.so_inquiry_hdr_id = s_inquiry_lines_t.so_inquiry_hdr_id
LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_inquiry_hdr_t.customerid
LEFT JOIN m_products_t ON m_products_t.product_id = s_inquiry_lines_t.product_id
LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = s_inquiry_lines_t.uom_code_id where 1=1 $wh and s_inquiry_hdr_t.inquiry_date BETWEEN ? and ?) AS v1";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return response()->json(['data' => $results]);
    }

    public function salesinquiryupdatestatus($id = null)
    {
        \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id', $id)->update(['inquiry_status' => 'CLOSED']);
    }

}
