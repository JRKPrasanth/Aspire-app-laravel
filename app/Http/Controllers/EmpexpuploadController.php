<?php

namespace App\Http\Controllers;

use App\empexpupload;
use App\Product;
use App\Expenseslines;
use Illuminate\Http\Request;
use Validator,
Input,
Redirect,
DB;
use Yajra\DataTables\DataTables;

class EmpexpuploadController extends Controller
{

    public function __construct()
    {
        $this->customermodel = new CustomeruploadController;
        $this->submodel = new Expenseslines;
        $this->data['urlmenu'] = $this->indexs();
    }

    /*Get Product  Grid */

    public function getempexpuploaddata()
    {

        $wh = '';


        if (isset($_GET['batchname'])) {
            if ($_GET['batchname'] != "") {
                $wh = " and batchname like '" . $_GET['batchname'] . "'";
            }
        }


        $SQL = "SELECT * from f_empexpupload_t where 1=1 $wh";

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

        $batch = $type = '';
        $this->data['status'] = $this->data['message'] = ''; //dd($_GET['batchname']);
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND f_empexpupload_t.batchname = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'EMPEXPUPLOAD';
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
                case 'load':
                    $upload = $this->LoadLineMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
            }
        }


        $this->data['pageMethod'] = \Request::route()->getName();


        return view('employeeexpensesupload.table', $this->data);
    }



    /* purpose:To Upload excel*/
    public function Uploadexcel(Request $request)
    {

        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        $data = array();
        $return = 'empexpupload';
        if ($extension == "csv") {
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;
            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                if ($c > 0) {
                    // dd($filesop);
                    $empexpdata[$c]['UserName'] = strtoupper(trim($filesop[0]));
                    $empexpdata[$c]['Zone'] = strtoupper(trim($filesop[1]));
                    $empexpdata[$c]['EmployeeName'] = strtoupper(trim($filesop[2]));
                    $empexpdata[$c]['EmployeeNumber'] = strtoupper(trim($filesop[3]));
                    $empexpdata[$c]['Designation'] = strtoupper(trim($filesop[4]));
                    $empexpdata[$c]['HQName'] = strtoupper(trim($filesop[5]));
                    $empexpdata[$c]['Startdate'] = strtoupper(trim($filesop[6]));
                    $empexpdata[$c]['Enddate'] = strtoupper(trim($filesop[7]));
                    $empexpdata[$c]['CourierandPostageCost'] = $filesop[8];
                    $empexpdata[$c]['DailyJointWorkAllowance'] = $filesop[9];
                    $empexpdata[$c]['DoctorGiftCost'] = $filesop[10];
                    $empexpdata[$c]['HillStationAllowances'] = $filesop[11];
                    $empexpdata[$c]['InternetandMobile'] = $filesop[12];
                    $empexpdata[$c]['JointWork'] = $filesop[13];
                    $empexpdata[$c]['Others'] = $filesop[15];
                    $empexpdata[$c]['Metro'] = $filesop[14];
                    $empexpdata[$c]['Other'] = (int) $filesop[8] + (int) $filesop[9] + (int) $filesop[11] + (int) $filesop[13] + (int) $filesop[14] + (int) $filesop[15];
                    $empexpdata[$c]['RailBusPassAllowance'] = $filesop[16];
                    $empexpdata[$c]['RetailGiftCost'] = $filesop[17];
                    $empexpdata[$c]['Giftcharges'] = (int) $filesop[10] + (int) $filesop[17];
                    $empexpdata[$c]['TravelActuals'] = $filesop[18];
                    $empexpdata[$c]['TravelAllowance'] = $filesop[19];
                    $empexpdata[$c]['VehicleLocaltravelAllowance'] = $filesop[20];
                    $empexpdata[$c]['Transit'] = $filesop[21];
                    $empexpdata[$c]['OSMeetingWithStay'] = $filesop[22];
                    $empexpdata[$c]['ExHq'] = $filesop[23];
                    $empexpdata[$c]['OsEx'] = $filesop[24];
                    $empexpdata[$c]['Hq'] = $filesop[25];
                    $empexpdata[$c]['Os'] = $filesop[26];
                    $empexpdata[$c]['tourandtravels'] = (int) $filesop[18] + (int) $filesop[19] + (int) $filesop[21] + (int) $filesop[22] + (int) $filesop[23] + (int) $filesop[24] + (int) $filesop[25] + (int) $filesop[26];
                    $empexpdata[$c]['Total'] = $filesop[27];
                    $empexpdata[$c]['Remarks'] = $filesop[28];
                    $empexpdata[$c]['choosefile'] = $filesop[29];
                    $empexpdata[$c]['batchdate'] = date('Y-m-d');
                    $empexpdata[$c]['batchstatus'] = "UPLOADED";
                    $empexpdata[$c]['batchname'] = $_POST['batch_name'];
                    //insert record from csv        
                }
                $c = $c + 1;
            }
            $id = \DB::table('f_empexpupload_t')->insert($empexpdata);
        } else {

            $message = "Please upload an valid CSV file";
            return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }

        return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));


    }
    /*end*/

    /*Validation For Uploaded File*/
    function uploadValidation($valModname, $valBatch, $status)
    {

        $status['status'] = 'success';
        $status['message'] = ' ';
        $sql = "select * from f_empexpupload_t where batchstatus ='UPLOADED'  and batchname='" . $valModname . "'";
        $result_pr = \DB::select($sql);
        //dd($result_pr);
        if (!empty($result_pr)) {
            foreach ($result_pr as $key => $value) {
                $status['message'] = '';
                $status['status'] = 'success';

                if (!empty($value->EmployeeName)) {
                    $employee_name = $this->valid($value->EmployeeName, "name");
                    if ($employee_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Employee Name does not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Employee Name Empty.. Please enter Employee Name' . ',';
                }

                if (!empty($value->EmployeeNumber)) {
                    $emp_num = $this->valid($value->EmployeeNumber, "id");
                    if ($emp_num[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Employee Number does not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Employee Number Empty.. Please enter Employee Number' . ',';
                }


                //  dd($status['message']);

                if ($status['status'] == "error") {
                    $sql = "update f_empexpupload_t set batchstatus ='ERROR' , batchcomments='" . $status['message'] . "\n'  WHERE expupl_id='" . $value->expupl_id . "' ";
                    $result = \DB::update($sql);
                    $status['message'] = 'Uploaded data have some error';
                } else {
                    $sql = "update f_empexpupload_t set batchstatus ='VALIDATED' , batchcomments='' where expupl_id='" . $value->expupl_id . "' ";
                    $result = \DB::update($sql);
                    $status['status'] == 'success';
                    $status['message'] = 'Expenses Data validated successfully';

                }
            }

            // dd($status);
            return $status;


        } else {
            $status['status'] == 'info';
            $status['message'] = 'Batch Already Validated';
            return $status;
        }


    }


    public function valid($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and first_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and employee_number='" . $value . "'";
        } else if ($type == 'eid') {
            $cond = " and employee_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt, employee_id, employee_number,first_name from hr_employee_t where 1=1 $cond");
        return $sql;
    }

    public function acc($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and account_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and concatenated_segments='" . $value . "'";
        }
        $sql = \DB::select("select count(*)  as cnt, f_account_structure_id, account_name from f_account_structure_t where 1=1 $cond");
        return $sql;
    }


    /*Load Function*/
    public function LoadMaster($loadModname, $valBatch, $status)
    {
        $comnfun = new EmpexpuploadController;
        $status['status'] = '';
        $status['message'] = '';
        $sql = "select * from f_empexpupload_t  where  batchstatus ='VALIDATED'  and batchname='" . $loadModname . "'";
        $result = \DB::select($sql); //dd($result);
        $data = array();
        //$data1=array();
        $loadid = array();
        $dt = date("Y-m-d");

        //$result1=array();
        // dd($result);    
        if (count($result) > 0) {
            foreach ($result as $key => $value) {


                $data['expense_date'] = $value->Enddate;
                $data['expense_status'] = 'INITIATED';
                $data['expense_type'] = 'EMPLOYEE';
                /*$data['employee_id'] = $value->employee_id;*/
                //dd($value->DCRPeriodTo);
                $dtd = date('F', strtotime($value->Enddate));
                $emp_id = $this->valid($value->EmployeeNumber, "id");
                $data['employee_id'] = $emp_id[0]->employee_id;

                $data['supplier_id'] = '0';
                $data['customer_id'] = '0';
                $data['reverse_charge'] = '';
                $data['invoice'] = 'EXPINV - ' . $dtd;
                $data['gst_code_id'] = '';
                $data['expense_amount'] = $value->Total;
                $data['expense_account_id'] = '';
                $data['remarks'] = '';
                $data['tax_group_id'] = '0';
                $data['pay_amount'] = '0';
                $data['company_id'] = \Session::get('companyid');
                $data['created_by'] = \Session::get('id');
                $data['location_id'] = \Session::get('location');
                $data['organization_id'] = \Session::get('organization');
                $data['created_at'] = date("Y-m-d");
                $data['last_updated_by'] = \Session::get('id');
                $data['updated_at'] = date("Y-m-d");
                $data['reference_id'] = '0';
                $data['source'] = '';
                $data['tds_applicable'] = 'NO';
                $data['tds_prcnt'] = '0';
                $data['tds_amount'] = '0';
                $data['tds_account_id'] = '0';
                $data['tax_amount'] = '0';
                $data['round_off'] = '0';
                $data['balance_amount'] = $value->Total;
                $data['paid_amount'] = '0';
                $data['payment_status'] = '0';
                $data['bill_date'] = $dt;

                $uploadid = $value->expupl_id;
                $loadid[$key] = $value->expupl_id;

                try {

                    $id = \DB::table('f_expenses_t')->insertGetId($data);

                    $in = implode(',', $loadid);

                    $sql = "UPDATE f_empexpupload_t set batchstatus='LOADED', expenses_id=$id  where expupl_id in ($uploadid)";

                    \DB::update($sql);

                } catch (\Illuminate\Database\QueryException $e) {
                    $message = explode('(', $e->getMessage());
                    $dbCode = rtrim($message[0], ']');
                    $dbCode = trim($dbCode, '[');
                    $error = explode(":", $dbCode);

                    $status['status'] = 'error';
                    $status['message'] = $error[2];
                    $comment = '"' . $error[2] . '"';

                    $in = implode(',', $loadid);
                    $sql = "UPDATE f_empexpupload_t set batchstatus='ERROR',batchcomments=$comment where expupl_id in ($uploadid)";
                    \DB::update($sql);
                    return $status;
                }

            }

            $status['status'] = 'success';
            $status['message'] = 'Expenses Data Loaded Sucessfully';
            return $status;
            return true;

        } else {

            $sql = \DB::select("select * from f_empexpupload_t where batchname='" . $loadModname . "'");
            if ($sql[0]->batchstatus == "UPLOADED") {
                $status['status'] = 'info';
                $status['message'] = 'Pls Validate the Batch First..!';
                return $status;
            } else if ($sql[0]->batchstatus == "ERROR") {
                $status['status'] = 'error';
                $status['message'] = 'Batch Error..!';
                return $status;
            } else {
                $status['status'] = 'info';
                $status['message'] = 'Expenses Data already Loaded';
                return $status;
            }
        }
    }
    /*End*/

    /* purpose:expense lines*/

    public function LoadLineMaster($loadModname, $valBatch, $status)
    {
        $comnfun = new EmpexpuploadController;
        $status['status'] = '';
        $status['message'] = '';
        $sql = "select * from f_empexpupload_t  where  batchstatus ='LOADED' and expenses_id != 'NULL'   and batchname='" . $loadModname . "' group by expenses_id";
        $result = \DB::select($sql); //dd($result);
        $data1 = array();
        $result1 = array();


        //  dd("jj");
        if (count($result) > 0) {
            foreach ($result as $key => $value) {


                $uploadid = $value->expupl_id;
                $loadid[$key] = $value->expupl_id;

                $key1 = 0;

                $expl_id = DB::select("select expense_line_id from f_expenses_lines_t order by `expense_line_id` desc limit 1");
                $expl_id1 = $expl_id[0]->expense_line_id + 1;


                $Giftcharges = $value->Giftcharges;

                if ($Giftcharges > 0) {
                    $acc_id = $this->acc('Gift Charges ' . $value->Zone, 'name');

                    $data1[$key1]['gst_code_id'] = '0';
                    $data1[$key1]['tax_group_id'] = '0';
                    $data1[$key1]['tax_amount'] = '0';
                    $data1[$key1]['remarks'] = $value->remarks;
                    $chsfl = $value->choosefile;
                    $data1[$key1]['choosefile'] = '["' . $chsfl . '"]';
                    $data1[$key1]['company_id'] = \Session::get('companyid');
                    $data1[$key1]['created_by'] = \Session::get('id');
                    $data1[$key1]['location_id'] = \Session::get('location');
                    $data1[$key1]['organization_id'] = \Session::get('organization');
                    $data1[$key1]['created_at'] = date("Y-m-d");
                    $data1[$key1]['last_updated_by'] = \Session::get('id');
                    $data1[$key1]['updated_at'] = date("Y-m-d");

                    $id = $value->expupl_id;
                    $exp_id = $value->expenses_id;
                    $data1[$key1]['expense_id'] = $exp_id;


                    $data1[$key1]['expense_account_id'] = $acc_id[0]->f_account_structure_id;
                    $data1[$key1]['expense_line_amount'] = $value->Giftcharges;
                    $key1++;


                }


                $tourandtravels = $value->tourandtravels;


                if ($tourandtravels > 0) {
                    $acc_id = $this->acc('Tours and Travels ' . $value->Zone, 'name');
                    $data1[$key1]['gst_code_id'] = '0';
                    $data1[$key1]['tax_group_id'] = '0';
                    $data1[$key1]['tax_amount'] = '0';
                    $data1[$key1]['remarks'] = $value->remarks;
                    $chsfl = $value->choosefile;
                    $data1[$key1]['choosefile'] = '["' . $chsfl . '"]';
                    $data1[$key1]['company_id'] = \Session::get('companyid');
                    $data1[$key1]['created_by'] = \Session::get('id');
                    $data1[$key1]['location_id'] = \Session::get('location');
                    $data1[$key1]['organization_id'] = \Session::get('organization');
                    $data1[$key1]['created_at'] = date("Y-m-d");
                    $data1[$key1]['last_updated_by'] = \Session::get('id');
                    $data1[$key1]['updated_at'] = date("Y-m-d");

                    $id = $value->expupl_id;
                    $exp_id = $value->expenses_id;
                    $data1[$key1]['expense_id'] = $exp_id;

                    $data1[$key1]['expense_account_id'] = $acc_id[0]->f_account_structure_id;
                    $data1[$key1]['expense_line_amount'] = $value->tourandtravels;
                    $key1++;


                }

                $Other = $value->Other;

                if ($Other > 0) {
                    $acc_id = $this->acc('Other Expenses ' . $value->Zone, 'name');
                    $data1[$key1]['gst_code_id'] = '0';
                    $data1[$key1]['tax_group_id'] = '0';
                    $data1[$key1]['tax_amount'] = '0';
                    $data1[$key1]['remarks'] = $value->remarks;
                    $chsfl = $value->choosefile;
                    $data1[$key1]['choosefile'] = '["' . $chsfl . '"]';
                    $data1[$key1]['company_id'] = \Session::get('companyid');
                    $data1[$key1]['created_by'] = \Session::get('id');
                    $data1[$key1]['location_id'] = \Session::get('location');
                    $data1[$key1]['organization_id'] = \Session::get('organization');
                    $data1[$key1]['created_at'] = date("Y-m-d");
                    $data1[$key1]['last_updated_by'] = \Session::get('id');
                    $data1[$key1]['updated_at'] = date("Y-m-d");

                    $id = $value->expupl_id;
                    $exp_id = $value->expenses_id;
                    $data1[$key1]['expense_id'] = $exp_id;

                    $data1[$key1]['expense_account_id'] = $acc_id[0]->f_account_structure_id;
                    $data1[$key1]['expense_line_amount'] = $value->Other;
                    $key1++;


                }


                $InternetandMobile = $value->InternetandMobile;

                if ($InternetandMobile > 0) {
                    $acc_id = $this->acc('Net and Mobile allowance ' . $value->Zone, 'name');
                    $data1[$key1]['gst_code_id'] = '0';
                    $data1[$key1]['tax_group_id'] = '0';
                    $data1[$key1]['tax_amount'] = '0';
                    $data1[$key1]['remarks'] = $value->remarks;
                    $chsfl = $value->choosefile;
                    $data1[$key1]['choosefile'] = '["' . $chsfl . '"]';
                    $data1[$key1]['company_id'] = \Session::get('companyid');
                    $data1[$key1]['created_by'] = \Session::get('id');
                    $data1[$key1]['location_id'] = \Session::get('location');
                    $data1[$key1]['organization_id'] = \Session::get('organization');
                    $data1[$key1]['created_at'] = date("Y-m-d");
                    $data1[$key1]['last_updated_by'] = \Session::get('id');
                    $data1[$key1]['updated_at'] = date("Y-m-d");

                    $id = $value->expupl_id;
                    $exp_id = $value->expenses_id;
                    $data1[$key1]['expense_id'] = $exp_id;

                    $data1[$key1]['expense_account_id'] = $acc_id[0]->f_account_structure_id;
                    $data1[$key1]['expense_line_amount'] = $value->InternetandMobile;
                    $key1++;



                }


                $RailBusPassAllowance = $value->RailBusPassAllowance;

                if ($RailBusPassAllowance > 0) {
                    $acc_id = $this->acc('Rail/Bus pass allowance ' . $value->Zone, 'name');
                    $data1[$key1]['gst_code_id'] = '0';
                    $data1[$key1]['tax_group_id'] = '0';
                    $data1[$key1]['tax_amount'] = '0';
                    $data1[$key1]['remarks'] = $value->remarks;
                    $chsfl = $value->choosefile;
                    $data1[$key1]['choosefile'] = '["' . $chsfl . '"]';
                    $data1[$key1]['company_id'] = \Session::get('companyid');
                    $data1[$key1]['created_by'] = \Session::get('id');
                    $data1[$key1]['location_id'] = \Session::get('location');
                    $data1[$key1]['organization_id'] = \Session::get('organization');
                    $data1[$key1]['created_at'] = date("Y-m-d");
                    $data1[$key1]['last_updated_by'] = \Session::get('id');
                    $data1[$key1]['updated_at'] = date("Y-m-d");

                    $id = $value->expupl_id;
                    $exp_id = $value->expenses_id;
                    $data1[$key1]['expense_id'] = $exp_id;

                    $data1[$key1]['expense_account_id'] = $acc_id[0]->f_account_structure_id;
                    $data1[$key1]['expense_line_amount'] = $value->RailBusPassAllowance;
                    $key1++;


                }

                $VehicleLocaltravelAllowance = $value->VehicleLocaltravelAllowance;

                if ($VehicleLocaltravelAllowance > 0) {
                    $acc_id = $this->acc('Vehicle allowance ' . $value->Zone, 'name');
                    $data1[$key1]['gst_code_id'] = '0';
                    $data1[$key1]['tax_group_id'] = '0';
                    $data1[$key1]['tax_amount'] = '0';
                    $data1[$key1]['remarks'] = $value->remarks;
                    $chsfl = $value->choosefile;
                    $data1[$key1]['choosefile'] = '["' . $chsfl . '"]';
                    $data1[$key1]['company_id'] = \Session::get('companyid');
                    $data1[$key1]['created_by'] = \Session::get('id');
                    $data1[$key1]['location_id'] = \Session::get('location');
                    $data1[$key1]['organization_id'] = \Session::get('organization');
                    $data1[$key1]['created_at'] = date("Y-m-d");
                    $data1[$key1]['last_updated_by'] = \Session::get('id');
                    $data1[$key1]['updated_at'] = date("Y-m-d");

                    $id = $value->expupl_id;
                    $exp_id = $value->expenses_id;
                    $data1[$key1]['expense_id'] = $exp_id;

                    $data1[$key1]['expense_account_id'] = $acc_id[0]->f_account_structure_id;
                    $data1[$key1]['expense_line_amount'] = $value->VehicleLocaltravelAllowance;


                }

                //dd($expl_id1);
                $file = $value->choosefile;
                $crdir = '';
                if ($file != '') {
                    $crdir = mkdir(public_path() . '/Uploads/expense/' . $expl_id1);
                    rename('exptmpfileupld/' . $file, public_path() . '/Uploads/expense/' . $expl_id1 . '/' . $file);
                }


                \DB::table('f_expenses_lines_t')->insert($data1);

                $data1 = array();


                $sql = "UPDATE f_empexpupload_t set batchstatus='LINELOADED'  where expupl_id in ($uploadid)";

                \DB::update($sql);


            }


            $status['status'] = 'success';
            $status['message'] = 'Expenses Line Data Loaded Sucessfully';
            return $status;
            return true;


        } else {

            $sql = \DB::select("select * from f_empexpupload_t where batchname='" . $loadModname . "'");
            if ($sql[0]->batchstatus == "UPLOADED") {
                $status['status'] = 'info';
                $status['message'] = 'Pls Validate the Batch First..!';
                return $status;
            } else if ($sql[0]->batchstatus == "ERROR") {
                $status['status'] = 'error';
                $status['message'] = 'Batch Error..!';
                return $status;
            } else {
                $status['status'] = 'info';
                $status['message'] = 'Expenses Line Data already Loaded';
                return $status;
            }
        }

    }

    /*end*/


    public function docsupload(Request $request)
    {

        if ($request->hasfile('files')) {

            foreach ($request->file('files') as $file) {
                $name = $file->getClientOriginalName();
                $file->move(public_path() . '/exptmpfileupld', $name);
                $dataupload[] = $name;
            }

        }
    }



    /* purpose:to return status*/
    public function getempexpvalidate(Request $request)
    {
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND f_empexpupload_t.batchname = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'EMPEXPUPLOAD';
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
                case 'lineload':
                    $upload = $this->LoadLineMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;

            }
        }
    }
    /*end*/
}