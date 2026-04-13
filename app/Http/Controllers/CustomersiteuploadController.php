<?php

namespace App\Http\Controllers;

use App\customersiteupload;
use App\Http\Controllers\Suppliersiteupload;
use Illuminate\Http\Request;
use Validator,
Input,
Redirect,
DB;
use Yajra\DataTables\DataTables;

class CustomersiteuploadController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->table = "s_customersites_upload_t";
        $this->pageModule = "customersiteupload";
        $this->model = new Customersiteupload;
        $this->suppliersitemodel = new SuppliersiteuploadController;
        $this->data['pageModule'] = $this->pageModule;
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

        $batch = $type = '';
        $this->data['status'] = $this->data['message'] = '';
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND s_customersites_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'CUSTOMERSITEUPLOAD';
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

        return view('customersiteupload.table', $this->data);
    }


    public function getCustomersiteuploadData(Request $request)
    {

        $wh = '';



        if (isset($_GET['batchname'])) {
            if ($_GET['batchname'] != "") {
                $wh = " and batch_name like '" . $_GET['batchname'] . "'";
            }
        }


        $SQL = "SELECT s_customersites_upload_t.customersite_upload_id,s_customersites_upload_t.customer_name,s_customersites_upload_t.customer_site_name,s_customersites_upload_t.customer_site_number,s_customersites_upload_t.address,s_customersites_upload_t.site_type,s_customersites_upload_t.country,s_customersites_upload_t.state,s_customersites_upload_t.city,s_customersites_upload_t.contact_number,s_customersites_upload_t.batch_name,s_customersites_upload_t.batch_date,s_customersites_upload_t.batch_status,s_customersites_upload_t.batch_comments from s_customersites_upload_t where 1=1 $wh";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }

    public function create($id = null)
    {
        $customersiteupload = Customersiteupload::find($id);

        $this->data['customersiteuploaddata'] = $customersiteupload;
        if (isset($id)) {

        } else {
            $customersiteuploads = \DB::connection()->getSchemaBuilder()->getColumnListing("s_customersites_upload_t");
            $customersiteupload = array();
            foreach ($customersiteuploads as $key => $val) {
                $customersiteupload[$val] = "";
            }
        }
        // dd($customersiteupload['state']);
        $this->data['country'] = $this->jCombologin("m_countries_t", "country_name", "country_name", $customersiteupload['country']);
        $this->data['state'] = $this->jCombologin("m_states_t", "state_name", "state_name", $customersiteupload['state']);
        $this->data['city'] = $this->jCombologin("m_cities_t", "city_name", "city_name", $customersiteupload['city']);

        return view('customersiteupload.form', $this->data);
    }

    public function save(Request $request)
    {

        $customersiteupload = new Customersiteupload();
        $_POST['batch_status'] = 'UPLOADED';

        // dd($_POST);

        // $data['customer_site_number']=$_POST['customer_site_number'];
        $data['customer_name'] = $_POST['customer_name'];
        $data['customer_site_name'] = $_POST['customer_site_name'];
        $data['site_type'] = $_POST['site_type'];
        $data['address'] = $_POST['address'];
        $data['country'] = $_POST['country'];
        $data['state'] = $_POST['state'];
        $data['city'] = $_POST['city'];
        $data['gst_no'] = $_POST['gst_no'];
        $data['tan_no'] = $_POST['tan_no'];
        $data['pincode'] = $_POST['pincode'];
        $data['contact_number'] = $_POST['contact_number'];
        $data['contact_person'] = $_POST['contact_person'];
        $data['contact_email'] = $_POST['contact_email'];
        $data['primary_address'] = $_POST['primary_address'];
        $data['active'] = $_POST['active'];

        $data['batch_name'] = $_POST['batch_name'];
        $data['batch_date'] = $_POST['batch_date'];
        $data['batch_status'] = $_POST['batch_status'];
        $data['batch_comments'] = "";

        $id = $_POST['customersite_upload_id'];
        // dd($data);
        customersiteupload::find($id)->update($data);
        return response()->json(array('status' => 'success', 'message' => 'your data Updated successfully!!'));

        // return redirect('customersiteupload')->with('success','your data Updated successfully');
    }

    public function show(Customersiteupload $customersiteupload, $id = null)
    {
        $this->data['columns'] = \DB::connection()->getSchemaBuilder()->getColumnListing("s_customersites_upload_t");
        $this->data['values'] = Customersiteupload::find($id);

        return view('customersiteupload.view', $this->data);
    }



    /*deepika purpose:To Upload excel*/
    public function Uploadexcel(Request $request)
    {

        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        // dd($path,$extension);
        $data = array();
        $return = 'customersiteupload';
        if ($extension == "csv") {
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;
            // dd($filesop);
            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                if ($c > 0) {
                    // dd($filesop);
                    $customerdata[$c]['customer_name'] = strtoupper(trim($filesop[0]));
                    $customerdata[$c]['customer_site_name'] = strtoupper(trim($filesop[1]));
                    $customerdata[$c]['site_type'] = strtoupper(trim($filesop[2]));
                    $customerdata[$c]['address'] = strtoupper(trim($filesop[3]));
                    $customerdata[$c]['country'] = strtoupper(trim($filesop[4]));
                    $customerdata[$c]['state'] = (trim($filesop[5]));
                    $customerdata[$c]['city'] = (trim($filesop[6]));
                    $customerdata[$c]['gst_no'] = strtoupper(trim($filesop[7]));
                    $customerdata[$c]['tan_no'] = strtoupper(trim($filesop[8]));
                    $customerdata[$c]['pincode'] = trim($filesop[9]);
                    $customerdata[$c]['contact_number'] = trim($filesop[10]);
                    $customerdata[$c]['contact_person'] = strtoupper(trim($filesop[11]));
                    $customerdata[$c]['contact_email'] = trim($filesop[12]);
                    $customerdata[$c]['primary_address'] = strtoupper(trim($filesop[13]));
                    $customerdata[$c]['active'] = ucwords(strtolower(trim($filesop[14])));
                    $customerdata[$c]['batch_date'] = date('Y-m-d');
                    $customerdata[$c]['batch_status'] = "UPLOADED";
                    $customerdata[$c]['batch_name'] = $_POST['batch_name'];

                    //insert record from csv 		
                }
                $c = $c + 1;
            }

            $id = \DB::table('s_customersites_upload_t')->insert($customerdata);
        } else {

            return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }


        return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));


    }
    /*end*/
    /* Start upload data */
    function uploadValidation($valModname, $valBatch, $status)
    {
        $status['status'] = 'success';
        $status['message'] = ' ';

        $sql = "select * from s_customersites_upload_t where batch_status ='UPLOADED'  and batch_name='" . $valModname . "'";
        $result_pr = \DB::select($sql);
        // dd($result_pr[0]->site_type);
        if (!empty($result_pr)) {
            $status['status'] = 'success';
            $status['message'] = ' ';
            foreach ($result_pr as $key => $value) {
                if (!empty($value->customer_name)) {
                    $customer = $this->customer($value->customer_name, "name");

                    if ($customer[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'customer Name not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'customer Name Empty.. Please enter customer name' . ' ,';

                }
                if (!empty($value->site_type)) {
                    if ($value->site_type != "BILL_TO" && $value->site_type != "SHIP_TO") {
                        $status['status'] = 'error';
                        $status['message'] .= 'customer site Should be (BILL_TO or SHIP_TO)' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'customer site Empty.. Please enter customer site' . ' ,';
                }

                if (!empty($value->primary_address)) {
                    $customer = $this->customer($value->customer_name, "name");
                    $sql_chk = \DB::SELECT("select * from m_customer_sites_t where customer_id='" . $customer[0]->customer_id . "'");
                    if (count($sql_chk) > 0) {
                        if ($value->primary_address == "YES") {
                            $sql_primary = \DB::SELECT("select * from m_customer_sites_t where site_type='" . $value->site_type . "' and customer_id='" . $customer[0]->customer_id . "' and primary_address ='Yes'");
                            // dd($sql_primary);
                            if (count($sql_primary) > 0) {
                                $status['status'] = 'error';
                                $status['message'] .= 'Already Primary Address Yes Exists in this Customer,Choose No for This Customer Site' . ' ,';
                            }
                        }
                    } else if ($value->primary_address != "YES" && $value->primary_address != "NO") {
                        $status['status'] = 'error';
                        $status['message'] .= 'Primary Address Should be Yes.' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Primary Address Empty.. Please enter Primary Address has Yes or No.' . ' ,';
                }
                if (!empty($value->country)) {

                    $country = $this->suppliersitemodel->country($value->country, "name");
                    if ($country[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Country not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Country Empty.. Please enter Country name' . ' ,';
                }

                if (!empty($value->state)) {
                    $state = $this->suppliersitemodel->state($value->state, "name");
                    if ($state[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'State not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'State Empty.. Please enter State name' . ' ,';
                }


                if (!empty($value->city)) {
                    $city = $this->suppliersitemodel->city($value->city, "name");
                    if ($city[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'City not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'City Empty.. Please enter City name';
                }

                if ($status['status'] == "error") {
                    $sql = "update s_customersites_upload_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE customersite_upload_id='" . $value->customersite_upload_id . "' ";
                    $result = \DB::update($sql);
                    $status['message'] = 'Uploaded data have some error';
                } else {
                    $sql = "update s_customersites_upload_t set batch_status ='VALIDATED' , batch_comments='' where customersite_upload_id='" . $value->customersite_upload_id . "' ";
                    $result = \DB::update($sql);
                    $status['status'] == 'success';
                    $status['message'] = 'Customer site Data validated successfully';
                }
            }

            return $status;
        } else {
            $status['status'] = 'info';
            $status['message'] = 'Batch Already Validated';
            return $status;
        }

    }
    /* End upload data */

    /* Start Load data */
    public function LoadMaster($loadModname, $valBatch, $status)
    {

        $status['status'] = '';
        $status['message'] = '';
        $sql = "select * from s_customersites_upload_t  where  batch_status ='VALIDATED'  and batch_name='" . $loadModname . "'";
        $result = \DB::select($sql);
        $data = array();
        $loadid = array();
        // dd($result);
        if (count($result) > 0) {

            foreach ($result as $key => $value):
                $customer = $this->customer($value->customer_name, 'name');
                $data['customer_id'] = $customer[0]->customer_id;
                $custno = $this->customer($customer[0]->customer_id, 'id');
                $custno1 = $custno[0]->customer_number;
                $sql = \DB::select("select * from m_customer_sites_t where customer_id=" . $customer[0]->customer_id);
                $cnt = count($sql);
                $a = sprintf("%03d ", $cnt + 1);
                $seqno = $custno1 . '/CS' . $a;
                $data['customer_site_number'] = $seqno;
                $data['site_type'] = $value->site_type;
                $data['address'] = $value->address;
                $country = $this->suppliersitemodel->country($value->country, 'name');
                $data['country'] = $country[0]->country_id;
                $state = $this->suppliersitemodel->state($value->state, 'name');
                $data['state'] = $state[0]->state_id;
                $city = $this->suppliersitemodel->city($value->city, 'name');
                $data['city'] = $city[0]->city_id;
                if ($value->site_type = "BILL_TO") {
                    $data['customer_site_name'] = $custno1 . "B-" . $value->city;
                } else {
                    $data['customer_site_name'] = $custno1 . "S-" . $value->city;
                }
                $data['pincode'] = $value->pincode;
                $data['contact_person'] = $value->contact_person;
                $data['contact_number'] = $value->contact_number;
                $data['gst_no'] = $value->gst_no;
                $data['tan_no'] = $value->tan_no;
                $data['primary_address'] = $value->primary_address;
                $data['active'] = $value->active;
                $data['contact_mail'] = $value->contact_email;
                $data['organization_id'] = \Session::get('organization');
                $data['company_id'] = \Session::get('companyid');
                $data['location_id'] = \Session::get('location');
                try {

                    $id = \DB::table('m_customer_sites_t')->insert($data);
                    $sql = "UPDATE s_customersites_upload_t set batch_status='LOADED'  where customersite_upload_id = $value->customersite_upload_id";
                    \DB::update($sql);


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
            $status['message'] = 'Customer Site Data loaded Sucessfully';
            return $status;
        } else {

            $sql = \DB::select("select * from s_customersites_upload_t where batch_name='" . $loadModname . "'");
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
                $status['message'] = 'Customer site Data already Loaded';
                return $status;
            }
        }


    }/* end upload data */
    public function customer($value = null, $type = null)
    {
        //Customer data
        if ($type == 'name') {
            $cond = " and customer_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and customer_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,customer_id,customer_name,customer_number from m_customers_t where 1=1 $cond");
        return $sql;
    }

    /*deepika purpose:to return status*/
    public function getCustomersitevalidate(Request $request)
    {
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND s_customersites_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'CUSTOMERSITEUPLOAD';
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

    public function edit(customersiteupload $customersiteupload)
    {
        //
    }


    public function update(Request $request, customersiteupload $customersiteupload)
    {
        //
    }


    public function destroy(customersiteupload $customersiteupload)
    {
        //
    }
}
