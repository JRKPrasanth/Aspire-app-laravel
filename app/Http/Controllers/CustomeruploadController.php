<?php

namespace App\Http\Controllers;

use App\customerupload;
use App\Http\Controllers\Supplierupload;
use Illuminate\Http\Request;
use Validator, Input, Redirect;
use DB;
use Yajra\DataTables\DataTables;

class CustomeruploadController extends Controller
{


    public function __construct()
    {

        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();

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

        $batch = $customerupload = '';
        $this->data['status'] = $this->data['message'] = '';
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND s_customerupload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'CUSTOMER';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':
                    $upload = $this->UploadValidation($batch, $type, $message);
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
        return view('customerupload.table', $this->data);
    }
    //Start Jqgrid data 
    public function getCustomeruploadData(Request $request)
    {

        $wh = '';


        if (isset($_GET['batchname'])) {
            if ($_GET['batchname'] != "") {
                $wh = " and batch_name like '" . $_GET['batchname'] . "'";
            }
        }

        $SQL = "SELECT * from s_customerupload_t where 1=1 $wh";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }


    public function create($id = null)
    {
        //Start create
        if (isset($id)) {
            $customerupload = customerupload::find($id);
        } else {
            $customeruploads = \DB::connection()->getSchemaBuilder()->getColumnListing("s_customerupload_t");
            $customerupload = array();
            foreach ($customeruploads as $key => $val) {
                $customerupload[$val] = "";
            }
        }
        //dd($customerupload);ar_frieghtcarriers_hdr
        $this->data['customeruploaddata'] = $customerupload;


        $this->data['customer_type'] = $this->jcombo("m_customer_types_t", "customer_type", "customer_type", $customerupload['customer_type']);
        $this->data['pricelist_name'] = $this->jcustomselect("i_pricelist_hdr_t", "pricelist_name", "pricelist_name", $customerupload['pricelist'], ' and price_list_type="Sales"');

        $this->data['default_payment_terms'] = $this->jcombo("m_payment_terms_t", "payment_term_name", "payment_term_name", $customerupload['default_payment_terms']);

        $this->data['default_payment_method'] = $this->jcombo("m_payment_methods_t", "payment_method_name", "payment_method_name", $customerupload['default_payment_method']);

        $this->data['sales_person'] = $this->jcombo("hr_employee_t", "first_name", "first_name", $customerupload['sales_person']);

        $this->data['customer_category'] = $this->jcustomselect("a_lookuplines_t", "lookup_meaning", "lookup_meaning", $customerupload['customer_category'], "and lookup_type='CUSTOMER_CATEGORY'");

        $this->data['credit_check'] = $this->jcustomselect("a_lookuplines_t", "lookup_meaning", "lookup_meaning", $customerupload['credit_check'], "and lookup_type='CREDIT_CHECK'");

        $this->data['ar_discount_hdr'] = $this->jcombo("m_discounts_hdr_t", "discount_name", "discount_name", $customerupload['ar_discount_hdr']);
        $this->data['account_code'] = $this->jcombo("f_account_structure_t", "concatenated_segments", "concatenated_segments", $customerupload['account_code']);

        $this->data['freight_terms'] = $this->jcustomselect("m_frieghtterms_t", "fob_point_name", "fob_point_name", $customerupload['freight_terms'], ' and source_type_id="Sales"');
        $this->data['delivery_term'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_term_name', 'delivery_term_name', $customerupload['delivery_term'], ' and source_type_id="Sales"');
        $this->data['default_bank'] = $this->jcombo('f_bank_account_hdr_t', 'bank_name', 'bank_name', $customerupload['default_bank']);
        $this->data['tds_percentage'] = $this->jCombo('f_tds_slab_t', 'tds_percentage', 'tds_percentage', $customerupload['tds_percentage']);
        $this->data['tds_account_code'] = $this->jCombo('f_account_structure_t', 'concatenated_segments', 'concatenated_segments', $customerupload['tds_account_code']);
        $this->data['ar_frieghtcarriers_hdr'] = $this->jcustomselect("m_frieghtcarriers_hdr_t", "carrier_name", "carrier_name", $customerupload['ar_frieghtcarriers_hdr'], ' and source_type_id="Sales"');
        return view('customerupload.form', $this->data);

    }
    //end


    /* Start insert data function  */
    public function save(Request $request)
    {
        // dd($_POST);
        $customerupload = new customerupload();

        $id = $_POST['customerupload_id'];

        $data['customer_name'] = $_POST['customer_name'];
        $data['customer_type'] = $_POST['customer_type'];
        $data['alternate_name'] = $_POST['alternate_name'];
        $data['pricelist'] = $_POST['pricelist_name'];
        $data['default_payment_terms'] = $_POST['default_payment_terms'];
        $data['default_payment_method'] = $_POST['default_payment_method'];
        $data['pan_no'] = $_POST['pan_no'];
        $data['account_code'] = $_POST['account_code'];
        $data['freight_terms'] = $_POST['freight_terms'];
        $data['delivery_term'] = $_POST['delivery_term'];
        $data['sales_person'] = $_POST['sales_person'];
        $data['customer_category'] = $_POST['customer_category'];
        $data['reward_opening_point'] = $_POST['reward_opening_point'];
        $data['reward_point'] = $_POST['reward_point'];
        $data['ar_discount_hdr'] = $_POST['ar_discount_hdr'];
        $data['maximum_credit'] = $_POST['maximum_credit'];
        $data['credit_check'] = $_POST['credit_check'];
        $data['ar_frieghtcarriers_hdr'] = $_POST['ar_frieghtcarriers_hdr'];
        $data['company_additional_info'] = $_POST['company_additional_info'];
        $data['line_of_business'] = $_POST['line_of_business'];
        $data['default_bank'] = $_POST['default_bank'];
        $data['active'] = $_POST['active'];
        $data['tds_applicable'] = $_POST['tds_applicable'];
        $data['tds_percentage'] = $_POST['tds_percentage'];
        $data['tds_account_code'] = $_POST['tds_account_code'];
        $data['batch_name'] = $_POST['batch_name'];
        $data['batch_date'] = $_POST['batch_date'];
        $data['batch_comments'] = "";
        $data['batch_status'] = "UPLOADED";

        // dd($data);

        customerupload::find($id)->update($data);

        return response()->json(array('status' => 'success', 'message' => 'Customer Updated successfully!!', 'id' => $id));

    }/* End insert data function  */


    /* Start Upload excel data function  */
    public function Uploadexcel(Request $request)
    {
        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        $data = array();
        $return = 'customerupload';
        if ($extension == "csv") {
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;
            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                if ($c > 0) {
                    $customerdata[$c]['customer_name'] = strtoupper(trim($filesop[0]));
                    $customerdata[$c]['alternate_name'] = strtoupper(trim($filesop[1]));
                    $customerdata[$c]['customer_type'] = strtoupper(trim($filesop[2]));
                    $customerdata[$c]['pricelist'] = strtoupper(trim($filesop[3]));
                    $customerdata[$c]['default_payment_terms'] = strtoupper(trim($filesop[4]));
                    $customerdata[$c]['default_payment_method'] = strtoupper(trim($filesop[5]));
                    $customerdata[$c]['pan_no'] = trim($filesop[6]);
                    $customerdata[$c]['account_code'] = trim($filesop[7]);
                    $customerdata[$c]['freight_terms'] = strtoupper(trim($filesop[8]));
                    $customerdata[$c]['sales_person'] = trim($filesop[9]);
                    $customerdata[$c]['delivery_term'] = strtoupper(trim($filesop[10]));
                    $customerdata[$c]['active'] = ucwords(trim($filesop[11]));
                    $customerdata[$c]['customer_category'] = strtoupper(trim($filesop[12]));
                    $customerdata[$c]['reward_opening_point'] = strtoupper(trim($filesop[13]));
                    $customerdata[$c]['reward_point'] = strtoupper(trim($filesop[14]));
                    $customerdata[$c]['ar_discount_hdr'] = strtoupper(trim($filesop[15]));
                    $customerdata[$c]['maximum_credit'] = strtoupper(trim($filesop[16]));
                    $customerdata[$c]['credit_check'] = ucwords(trim($filesop[17]));
                    $customerdata[$c]['ar_frieghtcarriers_hdr'] = strtoupper(trim($filesop[18]));
                    $customerdata[$c]['company_additional_info'] = strtoupper(trim($filesop[19]));
                    $customerdata[$c]['line_of_business'] = strtoupper(trim($filesop[20]));
                    $customerdata[$c]['default_bank'] = strtoupper(trim($filesop[21]));
                    $customerdata[$c]['tds_applicable'] = strtoupper(trim($filesop[22]));
                    $customerdata[$c]['tds_percentage'] = strtoupper(trim($filesop[23]));
                    $customerdata[$c]['tds_account_code'] = trim($filesop[24]);
                    $customerdata[$c]['batch_status'] = "UPLOADED";
                    $customerdata[$c]['batch_name'] = $_POST['batch_name'];
                    $customerdata[$c]['batch_date'] = date('Y-m-d');
                    //dd($customerdata);
                }
                $c = $c + 1;
            }
            $id = \DB::table('s_customerupload_t')->insert($customerdata);

        } else {
            return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }
        return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));
    }/* End Upload excel data function  */

    /* Start Upload validation data function  */
    function uploadValidation($valModname, $valBatch, $status)
    {

        $status['status'] = 'success';
        $status['message'] = ' ';

        $sql = "select * from s_customerupload_t where batch_status ='UPLOADED'  and batch_name='" . $valModname . "'";
        $result_pr = \DB::select($sql);
        // dd($result_pr);
        $comnfun = new SupplieruploadController;
        if (!empty($result_pr)) {
            foreach ($result_pr as $key => $value) {
                if (empty($value->customer_name)) {
                    $status['status'] = 'error';
                    $status['message'] .= 'Please enter Customer name ' . ' ,';
                } else {
                    $res = \DB::table('m_customers_t')->where('customer_name', $value->customer_name)->get();
                    if (count($res) > 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Customer name already exist ' . ' ,';
                    }
                }

                if (!empty($value->customer_type)) {
                    $prddata = $this->Customerdata($value->customer_type, 'name');
                    if ($prddata[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Customer type Not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Please enter Customer Type name ' . ' ,';
                }
                if (!empty($value->pricelist)) {
                    $pridt = $comnfun->pricelist($value->pricelist, 'name', 'Sales');
                    if ($pridt[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Pricelist name Not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Please enter Pricelist name' . ' ,';
                }
                if (!empty($value->default_payment_terms)) {
                    $pridt2 = $comnfun->paymentterm($value->default_payment_terms, 'name');
                    if ($pridt2[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Payment Terms Not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Please enter Payment Terms' . ' ,';
                }

                if (!empty($value->default_payment_method)) {
                    $pridt3 = $comnfun->paymentmethod($value->default_payment_method, 'name');
                    if ($pridt3[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Payment Method Not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Please enter Payment Method' . ' ,';
                }
                if (!empty($value->account_code)) {
                    $prddata1 = $this->accountcode($value->account_code, 'name');
                    if ($prddata1[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Account Code Not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Please enter Account Code ' . ' ,';
                }
                if (!empty($value->freight_terms)) {
                    $prddata1 = $this->freightterms($value->freight_terms, 'name', 'Sales');
                    if ($prddata1[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Freight Term Not exist' . ' ,';
                    }
                }
                if (!empty($value->delivery_term)) {
                    $prddata1 = $this->deliveryterm($value->delivery_term, 'name', 'Sales');
                    if ($prddata1[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Delivery Term Not exist' . ' ,';
                    }
                }
                if (!empty($value->sales_person)) {
                    $prddata1 = $this->Salespersondata($value->sales_person, 'name');
                    if ($prddata1[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Sales Person name Not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Please enter Sales Person name ' . ' ,';
                }
                if (!empty($value->customer_category)) {

                    $pridt4 = $this->custcategorycheck($value->customer_category, 'name', "CUSTOMER_CATEGORY");

                    if ($pridt4[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Customer category Not exist' . ' ,';
                    }
                }
                if (!empty($value->credit_check)) {
                    if ($value->credit_check != "Yes" && $value->credit_check != "No") {
                        $status['status'] = 'error';
                        $status['message'] .= 'Credit check must be Yes/No only' . ' ,';
                    }
                }
                if (!empty($value->tds_applicable)) {
                    if ($value->tds_applicable != "YES" && $value->tds_applicable != "NO") {
                        $status['status'] = 'error';
                        $status['message'] .= 'Tds Applicable must be YES/NO only' . ' ,';
                    } else if ($value->tds_applicable == "Yes") {
                        if (!empty($value->tds_percentage)) {
                            $pridt5 = $this->tdspercentage($value->tds_percentage, 'name');
                            if ($pridt5[0]->cnt <= 0) {
                                $status['status'] = 'error';
                                $status['message'] .= 'Tds Percentage Not exist' . ' ,';
                            }
                        } else {
                            $status['status'] = 'error';
                            $status['message'] .= 'Tds Percentage is empty' . ' ,';
                        }
                        if (!empty($value->tds_account_code)) {
                            $pridt5 = $this->tdsaccountcode($value->tds_account_code, 'name');
                            if ($pridt5[0]->cnt <= 0) {
                                $status['status'] = 'error';
                                $status['message'] .= 'Tds Account code Not exist' . ' ,';
                            }
                        } else {
                            $status['status'] = 'error';
                            $status['message'] .= 'Tds Account Code is empty' . ' ,';
                        }
                    }
                }

                if (!empty($value->ar_discount_hdr)) {
                    $discount = $this->ardiscount($value->ar_discount_hdr, 'dis_name');
                    if ($discount[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Discount name does not exist  ,';
                    }
                }
                if (!empty($value->ar_frieghtcarriers_hdr)) {
                    $pridt7 = $this->freightcrr($value->ar_frieghtcarriers_hdr, 'name');
                    if ($pridt7[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Frieght carriers Not exist' . ' ,';
                    }
                }
                if (!empty($value->default_bank)) {
                    $pridt7 = $this->defaultbank($value->default_bank, 'name');
                    if ($pridt7[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Default bank Not exist' . ' ,';
                    }
                }
                if (!empty($value->pan_no)) {
                    if (!preg_match('/[a-zA-z]{5}\d{4}[a-zA-Z]{1}/', $value->pan_no)) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Pan No should be proper ' . ' ,';
                    }
                }

                if ($status['status'] == 'error') {
                    $sql = "update s_customerupload_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE customerupload_id='" . $value->customerupload_id . "' ";
                    $result = \DB::update($sql);
                    $status['message'] = 'Uploaded data have some error';
                } else {
                    $sql = "update s_customerupload_t set batch_status ='VALIDATED' , batch_comments=' '  WHERE customerupload_id='" . $value->customerupload_id . "' ";
                    $result = \DB::update($sql);
                    $status['status'] = 'success';
                    $status['message'] = 'Customer Data Validated successfully  ';
                }

            }
            return $status;
        } else {
            $status['status'] = 'info';
            $status['message'] = 'Batch Already Validated';
            return $status;
        }


    } /* end Upload validation data function  */
    /*deepika purpose:to return status*/
    public function getCustomervalidate(Request $request)
    {
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND s_customerupload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'CUSTOMER';
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

    /* Start Load data function  */
    public function LoadMaster($loadModname, $valBatch, $status)
    {
        $comnfun = new SupplieruploadController;
        $status['status'] = '';
        $status['message'] = '';
        $sql = "select * from s_customerupload_t  where  batch_status ='VALIDATED'  and batch_name='" . $loadModname . "'";
        $result = \DB::select($sql);
        $data = array();
        $loadid = array();
        // dd($result);
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $seqno = $this->Seqnoe('C', 'm_customers_t', "", 'customer_count');
                $data['customer_number'] = $seqno[0];
                $data['customer_count'] = $seqno[1];
                $data['customer_name'] = $value->customer_name;
                $data['alternate_name'] = $value->alternate_name;
                $custype = $this->Customerdata($value->customer_type, 'name');
                $data['customer_type_id'] = $custype[0]->customer_type_id;
                $price = $comnfun->pricelist($value->pricelist, 'name', 'Sales');
                $data['pricelist_id'] = $price[0]->pricelist_hdr_id;
                $payterm = $comnfun->paymentterm($value->default_payment_terms, 'name');
                $data['default_payment_terms_id'] = $payterm[0]->payment_term_id;
                $paymethd = $comnfun->paymentmethod($value->default_payment_method, 'name');
                $data['default_payment_method_id'] = $paymethd[0]->payment_method_id;
                $data['pan_no'] = $value->pan_no;
                $account_code = $this->accountcode($value->account_code, 'name');
                $data['account_structure_id'] = $account_code[0]->f_account_structure_id;
                $frrterm = $this->freightterms($value->freight_terms, 'name', 'Sales');
                $data['frieghtterm_id'] = $frrterm[0]->frieghtterm_id;
                $sp = $this->Salespersondata($value->sales_person, 'name');
                $data['sales_person'] = $sp[0]->employee_id;
                $deliv = $this->deliveryterm($value->delivery_term, 'name', 'Sales');
                $data['delivery_terms_id'] = $deliv[0]->delivery_terms_id;
                $data['active'] = $value->active;
                $cuscat = $this->custcategory($value->customer_category, 'name', "CUSTOMER_CATEGORY");
                $data['customer_category'] = $cuscat[0]->lookuplines_id;
                $dis = $this->ardiscount($value->ar_discount_hdr, 'dis_name');
                $data['ar_discount_hdr_id'] = $dis[0]->ar_discount_hdr_id;
                $frr = $this->freightcrr($value->ar_frieghtcarriers_hdr, 'name');
                $data['ar_frieghtcarriers_hdr_id'] = $frr[0]->ar_frieghtcarriers_hdr_id;
                $def = $this->defaultbank($value->default_bank, 'name');
                $data['default_bank'] = $def[0]->bank_account_hdr_id;
                $def = $this->tdsaccountcode($value->tds_account_code, 'name');
                $data['tds_account_id'] = $def[0]->f_account_structure_id;
                $data['reward_opening_point'] = $value->reward_opening_point;
                $data['reward_point'] = $value->reward_point;
                $data['maximum_credit'] = $value->maximum_credit;
                $crd = $this->custcategory($value->credit_check, 'name', "CREDIT_CHECK");
                $data['credit_check'] = $crd[0]->lookuplines_id;
                $data['company_additional_info'] = $value->company_additional_info;
                $data['line_of_business'] = $value->line_of_business;
                $data['tds_applicable'] = $value->tds_applicable;
                $crd = $this->tdspercentage($value->tds_percentage, 'name');
                $data['tds_percentage'] = $crd[0]->tds_slab_id;
                $data['savestatus'] = "SAVE";
                $data['status'] = "CUSTOMER";
                $data['location_id'] = \Session::get('location');
                $data['company_id'] = \Session::get('companyid');
                $data['created_by'] = \Session::get('emp_id');
                try {
                    $id = \DB::table('m_customers_t')->insert($data);
                    $sql = "UPDATE s_customerupload_t set batch_status='LOADED'  where customerupload_id = $value->customerupload_id";
                    \DB::update($sql);

                } catch (\Illuminate\Database\QueryException $e) {
                    $message = explode('(', $e->getMessage());
                    $dbCode = rtrim($message[0], ']');
                    $dbCode = trim($dbCode, '[');
                    $status['status'] = 'error';
                    \DB::update("UPDATE s_customerupload_t set batch_status='ERROR'  where customerupload_id = $value->customerupload_id");
                    $status['message'] = $dbCode;

                    return $status;
                }

            }
            $status['status'] = 'success';
            $status['message'] = 'Customer Data Loaded Sucessfully';
            return $status;
            return true;
        } else {

            $sql = \DB::select("select * from s_customerupload_t where batch_name='" . $loadModname . "'");
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
                $status['message'] = 'Customer Data already Loaded';
                return $status;
            }
        }

    }
    /* end load data function  */

    /* Start Delivery term data function  */
    public function deliveryterm($value = null, $type = null, $deltype = null)
    {

        if ($type == 'name') {
            $cond = " and delivery_term_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and delivery_terms_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,delivery_terms_id,delivery_term_name from m_delivery_terms_t where 1=1 and source_type_id='" . $deltype . "' $cond");

        return $prdsql;
    }//end
/* Start Freight terms point name data function  */
    public function freightterms($value = null, $type = null, $deltype = null)
    {

        if ($type == 'name') {
            $cond = " and fob_point_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and frieghtterm_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,frieghtterm_id,fob_point_name from m_frieghtterms_t where 1=1 and source_type_id='" . $deltype . "' $cond");

        return $prdsql;
    }
    /* end Freight terms point name data function  */

    /* Start Bank statement name data function  */
    public function defaultbank($value = null, $type = null)
    {

        if ($type == 'name') {
            $cond = " and bank_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and bank_account_hdr_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,bank_account_hdr_id,bank_name from f_bank_account_hdr_t where 1=1 $cond");

        return $prdsql;
    }
    /* End Bank statement name data function  */

    /* Start Account code data function  */
    public function tdsaccountcode($value = null, $type = null)
    {

        if ($type == 'name') {
            $cond = " and concatenated_segments='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and f_account_structure_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,f_account_structure_id,concatenated_segments from f_account_structure_t where 1=1 $cond");

        return $prdsql;
    }
    /* End Bank statement data function  */

    /* Start TD Percentage data function  */
    public function tdspercentage($value = null, $type = null)
    {

        if ($type == 'name') {
            $cond = " and tds_percentage='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and tds_slab_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,tds_slab_id,tds_percentage from f_tds_slab_t where 1=1 $cond");

        return $prdsql;
    }
    /* End TD Percentage name data function  */

    /* Start Account Code data function  */
    public function accountcode($value = null, $type = null)
    {

        if ($type == 'name') {
            $cond = " and concatenated_segments='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and f_account_structure_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,f_account_structure_id,concatenated_segments from f_account_structure_t where 1=1 $cond");

        return $prdsql;
    }
    /* end Account Code  data function  */

    /* Start Customer data function  */
    public function Customerdata($value = null, $type = null)
    {

        if ($type == 'name') {
            $cond = " and customer_type='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and customer_type_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,customer_type_id,customer_type from m_customer_types_t where 1=1 $cond");

        return $prdsql;
    }
    /* end Customer data function  */

    /* Start Freight carrer data function  */
    public function freightcrr($value = null, $type = null)
    {

        if ($type == 'name') {
            $cond = " and carrier_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and ar_frieghtcarriers_hdr_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,carrier_name,ar_frieghtcarriers_hdr_id from m_frieghtcarriers_hdr_t where 1=1 $cond");

        return $prdsql;
    }
    /* end Freight carrer data function  */

    /* Start count data function  */
    public function ardiscount($value = null, $type = null)
    {

        if ($type == 'name') {
            $cond = " and default_discount_amount='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and ar_discount_hdr_id='" . $value . "'";
        } else if ($type == 'dis_name') {
            $cond = " and discount_name='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,ar_discount_hdr_id,default_discount_amount,discount_name from m_discounts_hdr_t where 1=1 $cond");

        return $prdsql;
    }
    /* end count data function  */

    /* Start Customer Category data function  */
    public function custcategory($value = null, $type = null, $lookuptype = null)
    {

        if ($type == 'name') {
            $cond = " and lookup_meaning='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and lookuplines_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,lookuplines_id,lookup_meaning from a_lookuplines_t where 1=1 $cond and lookup_type = '$lookuptype'");
        return $prdsql;
    }
    /* end Customer Category data function  */

    /* Start Customer Category check data function  */
    public function custcategorycheck($value = null, $type = null, $lookuptype = null)
    {
        //dd($value);
        if ($type == 'name') {
            $cond = " and lookup_meaning='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and lookuplines_id='" . $value . "'";
        } else {
            $cond = "";
        }
        //dd("select count(*)  as cnt,lookuplines_id,lookup_meaning from a_lookuplines_t where 1=1 $cond and lookup_type = '$lookuptype'");
        $prdsql = \DB::select("select count(*)  as cnt,lookuplines_id,lookup_meaning from a_lookuplines_t where 1=1 $cond and lookup_type = '$lookuptype'");
        return $prdsql;
    }
    /* End Customer Category check data function  */

    /* Start Sale person data function  */
    public function Salespersondata($value = null, $type = null)
    {

        if ($type == 'name') {
            $cond = " and first_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and employee_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $prdsql = \DB::select("select count(*)  as cnt,employee_id,first_name from hr_employee_t where 1=1 $cond");

        return $prdsql;
    }
    /* end Sale person function  */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\customerupload  $customerupload
     * @return \Illuminate\Http\Response
     */
    public function show(customerupload $customerupload, $id = null)
    {
        $this->data['columns'] = \DB::connection()->getSchemaBuilder()->getColumnListing("s_customerupload_t");
        $this->data['values'] = customerupload::find($id);

        return view('customerupload.view', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\customerupload  $customerupload
     * @return \Illuminate\Http\Response
     */
    public function edit(customerupload $customerupload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\customerupload  $customerupload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, customerupload $customerupload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\customerupload  $customerupload
     * @return \Illuminate\Http\Response
     */
    public function destroy(customerupload $customerupload)
    {
        //
    }




    /*end*/

}
