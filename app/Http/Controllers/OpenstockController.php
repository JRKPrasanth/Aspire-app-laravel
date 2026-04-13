<?php

namespace App\Http\Controllers;

use App\Openstock;
use Illuminate\Http\Request;
use Validator, Input, Redirect;
use Yajra\DataTables\DataTables;

class OpenstockController extends Controller
{

    public function getOpenstockData()
    {

        $wh = '';

        if (isset($_GET['batchname'])) {
            if ($_GET['batchname'] != "") {
                $wh = " and batch_name like '" . $_GET['batchname'] . "'";
            }
        }

        $SQL = "SELECT i_product_openstock_upload_t.product_openstock_upload_id,i_product_openstock_upload_t.item_name,i_product_openstock_upload_t.batch_number,i_product_openstock_upload_t.subinventory_name,i_product_openstock_upload_t.locator_code,i_product_openstock_upload_t.qty,i_product_openstock_upload_t.batch_name,i_product_openstock_upload_t.batch_date,i_product_openstock_upload_t.batch_status,i_product_openstock_upload_t.batch_comments from i_product_openstock_upload_t where 1=1 $wh ORDER BY i_product_openstock_upload_t.product_openstock_upload_id desc";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }

    public function index()
    {

        $batch = $type = '';
        $this->data['status'] = $this->data['message'] = '';
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND i_product_openstock_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'OPENSTOCK';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':
                    $upload = $this->UploadValidation($batch, $type, $message);
                    // dd($upload);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
                case 'load':
                    $upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
            }
        }

        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();

        return view('openstock.table', $this->data);
    }


    /*Create Function*/
    public function create($id = null)
    {
        $openstock = Openstock::find($id);
        $this->data['openstockdata'] = $openstock;
        if (isset($id)) {

        } else {
            $openstocks = \DB::connection()->getSchemaBuilder()->getColumnListing("i_product_openstock_upload_t");
            $openstock = array();
            foreach ($openstocks as $key => $val) {
                $openstock[$val] = "";
            }
        }
        $sql = \DB::select("select subinventory_id,subinventory_name from m_subinventory_t where subinventory_name='" . $openstock['subinventory_name'] . "'");

        $this->data['item_name'] = $this->jcombo("m_products_t", "concatenated_product", "concatenated_product", $openstock['item_name']);
        if ($sql) {
            $this->data['subinventory_name'] = $this->jcombo("m_subinventory_t", "subinventory_id", "subinventory_name", $sql[0]->subinventory_id);

        } else {
            $this->data['subinventory_name'] = $this->jcombo("m_subinventory_t", "subinventory_id", "subinventory_name", "");

        }

        $this->data['locator_code'] = $this->jcombo("m_sublocators_t", "locator_code", "locator_code", $openstock['locator_code']);


        return view('openstock.form', $this->data);
    }
    /*End*/
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*Save Function*/
    public function save(Request $request)
    {

        $openstock = new Openstock();
        $_POST['batch_status'] = 'UPLOADED';
        $sub_id = $_POST['subinventory_name'];
        // dd($_POST);
        $sub_name = $_POST['subinventory_name'];
        $su_name_sql = \DB::select("select subinventory_name from m_subinventory_t where subinventory_id=$sub_name");
        $su_name = \DB::select("select subinventory_name from m_subinventory_t where subinventory_id=$sub_id");
        $openstock->item_name = $_POST['item_name'];
        $openstock->subinventory_name = $su_name_sql[0]->subinventory_name;
        $openstock->locator_code = $_POST['locator_code'];
        $openstock->qty = $_POST['qty'];
        $openstock->cost = $_POST['cost'];
        $openstock->batch_name = $_POST['batch_name'];
        $openstock->batch_date = $_POST['batch_date'];
        $openstock->batch_status = $_POST['batch_status'];
        $openstock->batch_comments = '';
        $openstock->batch_number = $_POST['batch_name'];
        $id = $_POST['product_openstock_upload_id'];
        $_POST['subinventory_name'] = $su_name[0]->subinventory_name;
        $action = "Edit";
        /**Auditlog**/
        $this->auditlog($id, "openstockupload", $action, $_POST, "i_product_openstock_upload_t");
        // dd($_POST);
        Openstock::find($id)->update($_POST);
        return redirect('openstockupload')->with('success', 'your data Updated successfully');
    }
    /*End*/
    /**
     * Display the specified resource.
     *
     * @param  \App\Openstock  $openstock
     * @return \Illuminate\Http\Response
     */
    /*View Function*/
    public function show(Openstock $openstock, $id = null)
    {
        $this->data['columns'] = \DB::connection()->getSchemaBuilder()->getColumnListing("i_product_openstock_upload_t");
        $this->data['values'] = Openstock::find($id);

        return view('openstock.view', $this->data);
    }
    /*End*/


    /*deepika purpose:To Upload excel*/
    public function Uploadexcel(Request $request)
    {

        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        $data = array();
        $return = 'openstockupload';

        if ($extension == "csv") {

            $file = $request->file('choosefile');

            $handle = fopen($file, "r");
            $c = 0;
            $stockdata = array();
            // dd(($filesop = fgetcsv($handle, 1000, ","))!== false);
            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                // dd($c);
                if ($c > 0) {
                    //dd($filesop);  
                    $stockdata[$c]['item_name'] = trim($filesop[0]);

                    $stockdata[$c]['subinventory_name'] = strtoupper(trim($filesop[1]));
                    $stockdata[$c]['locator_code'] = strtoupper(trim($filesop[2]));
                    $stockdata[$c]['qty'] = strtoupper(trim($filesop[3]));
                    $stockdata[$c]['batch_date'] = date('Y-m-d');
                    $stockdata[$c]['batch_status'] = "UPLOADED";
                    $stockdata[$c]['batch_name'] = $_POST['batch_name'];
                    $stockdata[$c]['batch_number'] = strtoupper(trim($filesop[4]));
                    $stockdata[$c]['cost'] = strtoupper(trim($filesop[5]));
                    $stockdata[$c]['maufacture_date'] = strtoupper(trim($filesop[6]));
                    $stockdata[$c]['expiry_date'] = date("Y-m-d", strtotime($filesop[7]));
                    $id = \DB::table('i_product_openstock_upload_t')->insert($stockdata[$c]);
	
                }


                $c = $c + 1;
                if ($c == 901) {
                    return response()->json(array('status' => 'info', 'message' => 'Maximum 900 rows are Exceeded.Others will be skipped!!'));
                    ;
                }
            }


            //dd($id);
        } else {
            return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }


        return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));


    }
    /*validation for Open stock Upload*/
    function uploadValidation($valModname, $valBatch, $status)
    {

        $status['status'] = 'success';
        $status['message'] = '';

        $sql = "select * from i_product_openstock_upload_t where batch_status ='UPLOADED'  and batch_name='" . $valModname . "'";
        $result_pr = \DB::select($sql);

        if (!empty($result_pr)) {
            foreach ($result_pr as $key => $value) {
                $status['status'] = 'success';
                $status['message'] = '';
                $prddata = $this->Productdata($value->item_name, 'name');

                if ($prddata[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'] = 'Product name Not exist' . ' ,';
                }
                if ($prddata[0]->group_name == "FINISHED GOODS") {
                    if (empty($value->batch_number)) {
                        $status['status'] = 'error';
                        $status['message'] = 'Batch number not exist. Please enter Batch number' . ' ,';

                    }
                }

                if (empty($value->subinventory_name)) {
                    $prddata1 = $this->Productdata($prddata[0]->product_id, 'id');
                    $sub_id = $prddata1[0]->subinventory_id;
                    // $sub_name = \DB::select("select subinventory_name from m_subinventory_t where subinventory_id =$sub_id");
                    // dd($sub_name);
                    if ($prddata1[0]->subinventory_id == 0) {
                        $status['status'] = 'error';
                        $status['message'] = 'Subinventory name Not assigned in Product' . ' ,';
                    }
                } else {
                    $subinvsql = $this->Subinventorydata($value->subinventory_name, 'name');
                    // dd($value->subinventory_name);  
                    if ($subinvsql[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] = 'Subinventory name Not exist' . ' ,';
                        $subinventory_id = '';
                    } else {
                        // dd($subinvsql[0]->subinventory_id);
                        $subinventory_id = $subinvsql[0]->subinventory_id;
                    }
                }

                if (!empty($value->locator_code)) {

                    // $subinvsql=$this->Subinventorydata($value->subinventory_name,'name'); 
                    $locsql = $this->Locatordata($value->locator_code, 'name', $subinventory_id);

                    //dd($subinvsql[0]->subinventory_id);
                    if ($locsql[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] = 'Locator Code Not exist' . ' ,';
                    }

                    if ($locsql[0]->subinventory_id != $subinvsql[0]->subinventory_id) {
                        $status['status'] = 'error';
                        $status['message'] = 'Locator Code Not exist for this subinventory' . ' ,';
                    }

                } else {
                    $locator = $this->Productdata($prddata[0]->product_id, 'id');
                    // $locator_cde = \DB::select("select locator_code from m_sublocators_t where subinventory_id = $sub_id");

                    if ($locator[0]->locator_control != 'N') {
                        $locid = $locator[0]->sublocator_id;
                        if ($locid == 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Locator does not exist\n';
                        } else if ($locator[0]->locator_control == 'Y' && $locid == 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Locator does not exist for this product.. Please Assign Locator and upload\n';
                        }
                    }
                }

                if ($status['status'] == 'error') {
                    //$msg=$this->makeNumbered_string($status['message']);
                    $sql = "update i_product_openstock_upload_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE product_openstock_upload_id='" . $value->product_openstock_upload_id . "' ";
                    $result = \DB::update($sql);
                    $status['message'] = 'Uploaded data have some error';
                    // dd("Asd");
                } else {

                    $sql = "update i_product_openstock_upload_t set batch_status ='VALIDATED' , batch_comments='' where product_openstock_upload_id='" . $value->product_openstock_upload_id . "' ";
                    $result = \DB::update($sql);
                    $status['status'] == 'success';
                    $status['message'] = ' Uploaded data validated successfully  ';
                }
            }
            // dd($status);
            //FOR FINAL STAUS OF ALL READED ROWS FROM UPLOADED DATA
            return $status;
        } else {
            $status['status'] = 'info';
            $status['message'] = 'Batch Already Validated';

            return $status;
        }

    }
    /*End*/

    /*Load Function*/
    public function LoadMaster($loadModname, $valBatch, $status)
    {

        $status['status'] = '';
        $status['message'] = '';
        $sql = "select expiry_date,maufacture_date,product_openstock_upload_id,subinventory_name,item_name,locator_code,qty,batch_number,cost from i_product_openstock_upload_t  where  batch_status ='VALIDATED'  and batch_name='" . $loadModname . "'";
        // dd($sql);
        $result = \DB::select($sql);
        $data = array();
        $loadid = array();
        // dd($result);
        if (count($result) > 0) {
            // dd($result);
            $trsnsType = \DB::table('m_transaction_types_t')->where('transaction_type_code', 'INVENTORY OPEN STOCK')->get();
            $trsnsType = json_decode(json_encode($trsnsType), true);

            foreach ($result as $key => $value):
                // dd($value);
                $data['trx_source_type_id'] = $trsnsType[0]['transaction_source_id'];
                $data['trx_action_id'] = $trsnsType[0]['transaction_action_id'];
                $data['trx_type_id'] = $trsnsType[0]['transaction_type_id'];
                $data['trx_source_hdr_id'] = $value->product_openstock_upload_id;
                $data['trx_cost'] = $value->cost;
                $data['line_number'] = $key + 1;
                $prdid = $this->Productdata($value->item_name, 'name');
                $data['product_id'] = $prdid[0]->product_id;
                $subinvid = $this->Subinventorydata($value->subinventory_name, 'name');
                $data['subinventory_id'] = $subinvid[0]->subinventory_id;
                $locid = $this->Locatordata($value->locator_code, 'name', $data['subinventory_id']);
                $data['locator_id'] = $locid[0]->sublocator_id;
                $data['trx_qty'] = $value->qty;
                // dd($data['locator_id']);
                //  $data['trx_uom']= $value->primary_uom;
                $data['trx_uom'] = '';
                $data['trx_cost'] = '';
                $data['project_id'] = '';
                // $data['organization_id']= \Session::get('ss_defaultorg_id');
                $data['organization_id'] = '';
                //   $Poutlites = new \App\Http\Controllers\PoutlityController();
                //  $data['trx_date']= $Poutlites->CurentTransDate();
                $data['trx_date'] = date('Y-m-d');
                //  $data['created_by']=\Session::get('uid');
                $data['created_by'] = '';
                $data['created_at'] = date('Y-m-d');
                // dd($data);
                $sub_name = $data['subinventory_id'];
                $loc_cd = $data['locator_id'];

                try {

                    $id = \DB::table('m_material_trx_t')->insertGetId($data);
                    $sql = "UPDATE i_product_openstock_upload_t set batch_status='LOADED'  where product_openstock_upload_id = $value->product_openstock_upload_id";
                    \DB::update($sql);
                    $QOHdata['product_id'] = $prdid[0]->product_id;
                    $QOHdata['subinventory_id'] = $subinvid[0]->subinventory_id;
                    $QOHdata['locator_id'] = $locid[0]->sublocator_id;
                    $QOHdata['qoh_trx_qty'] = $value->qty;
                    $QOHdata['manufacturer_date'] = $value->maufacture_date;
                    $QOHdata['product_expire_date'] = $value->expiry_date;
                    $QOHdata['qoh_uom_code_id'] = '';
                    $QOHdata['cost'] = $value->cost;
                    $QOHdata['batch_number'] = $value->batch_number;
                    $QOHdata['qualitystatus'] = 1;
                    $QOHdata['organization_id'] = \Session::get('organization');
                    $QOHdata['company_id'] = \Session::get('companyid');
                    $QOHdata['location_id'] = \Session::get('location');
                    $QOHdata['create_trx_id'] = $id;
                    $QOHdata['qoh_source'] = "OPENSTOCK";
                    $QOHdata['created_at'] = date('Y-m-d H:i:s');
                    $QOHdata['updated_at'] = date('Y-m-d H:i:s');
                    $QOHdata['created_by'] = \Session::get('id');
                    $QOHdata['last_updated_by'] = \Session::get('id');
                    \DB::table('i_qoh_detail_t')->insert($QOHdata);
                    // add the additoal input fields like type, creadted by
                    // update the interface table with LOADED


                } catch (\Illuminate\Database\QueryException $e) {

                    $message = explode('(', $e->getMessage());
                    $dbCode = rtrim($message[0], ']');
                    $dbCode = trim($dbCode, '[');
                    $status['status'] = 'error';
                    $status['message'] = $dbCode;

                    return $status;
                }

            endforeach;
            $status['status'] = 'success';
            $status['message'] = 'Stock Moved Sucessfully';

            return $status;
            //  return true;

        } else {


            $sql = \DB::select("select * from i_product_openstock_upload_t where batch_name='" . $loadModname . "'");
            if ($sql[0]->batch_status == "UPLOADED") {
                $status['status'] = 'info';
                $status['message'] = 'Pls Validate the Batch First..!';
                return $status;
            } else if ($sql[0]->batch_status == "ERROR") {
                $status['status'] = 'error';
                $status['message'] = 'Batch Error..!';
                return $status;
            } else {
                $status['status'] = 'info';
                $status['message'] = 'Openstock Data already Loaded';
                return $status;
            }
        }


    }
    /*End*/
    /*Load Product Data*/
    public function Productdata($value = null, $type = null)
    {

        if ($type == 'name') {
            $cond = ' and concatenated_product="' . $value . '"';
        } else if ($type == 'id') {
            $cond = " and product_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,m_products_t.product_id,m_products_t.subinventory_id,m_products_t.sublocator_id,m_products_t.locator_control,m_product_groups_t.product_group_id,m_product_groups_t.group_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where 1=1 $cond");

        return $prdsql;
    }
    /*End*/
    /*Load Subinventory Data*/
    public function Subinventorydata($value = null, $type = null)
    {
        if ($type == 'name') { //dd($type);
            $cond = " and subinventory_name='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,subinventory_id from m_subinventory_t where 1=1 $cond");
        return $prdsql;
    }
    /*End*/

    /*locator data function*/
    public function Locatordata($value = null, $type = null, $subinve = null)
    {
        if ($type == 'name') {
            $cond = " and locator_code='" . $value . "' and subinventory_id='" . $subinve . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,subinventory_id,sublocator_id from m_sublocators_t where 1=1 $cond");	//dd($prdsql);
        return $prdsql;
    }
    /*end*/
    /*deepika purpose:to return status*/
    public function getvalidateload(Request $request)
    {

        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND i_product_openstock_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'OPENSTOCK';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':
                    $upload = $this->uploadValidation($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;
                case 'load':
                    $upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;
            }
        }
    }
    /*end*/
}
