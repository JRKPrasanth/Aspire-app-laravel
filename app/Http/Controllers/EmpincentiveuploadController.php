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

class EmpincentiveuploadController extends Controller
{
    public function __construct(){
        $this->customermodel=new CustomeruploadController;
        $this->submodel=new Expenseslines;
         $this->data['urlmenu']=$this->indexs(); 
    }
	
    /*Get Product  Grid */
     public function getempinsuploaddata()
    {

        $wh='';

            
        if(isset($_GET['batchname'])){
        if($_GET['batchname']!=""){
        $wh= " and batchname like '" . $_GET['batchname'] . "'";   
        }}
      
        $SQL = "SELECT * from f_empinsupload_t where 1=1 $wh";
		 
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
                $filter = 'AND f_empinsupload_t.batchname = "' . $_GET['batchname'] . '"'; 
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'EMPINSUPLOAD';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':$upload = $this->UploadValidation($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
                case 'load':$upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
                case 'load':$upload = $this->LoadLineMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;    
            }
        }


        $this->data['pageMethod']=\Request::route()->getName();

        
        return view('empincentiveupld.table', $this->data);
    }
/*End*/
    

    /* purpose:To Upload excel*/ 
    public function Uploadexcel(Request $request){  
        
        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        $data =array();
        $return = 'empinsupload';
        if($extension == "csv"){
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
                if($c>0){
                    // dd($filesop);
                    $empinsdata[$c]['EmployeeNumber'] = strtoupper(trim($filesop[0]));
                    $empinsdata[$c]['EmployeeName'] = strtoupper(trim($filesop[1]));
                    $empinsdata[$c]['BillNo'] = $filesop[2];
                    $empinsdata[$c]['BillDate'] = $filesop[3];
                    $empinsdata[$c]['Zone'] = $filesop[4];
                    $empinsdata[$c]['incentive_amount'] = $filesop[5];
                    $empinsdata[$c]['tds_applicable'] = $filesop[6];
                    $empinsdata[$c]['Files'] = $filesop[7];
                    $empinsdata[$c]['tds_rate'] = $filesop[8];
                    $empinsdata[$c]['tds_amount'] = $filesop[9];
                    $empinsdata[$c]['amount'] = $filesop[10];
                    $empinsdata[$c]['tds_account'] = $filesop[11];
                    $empinsdata[$c]['Remarks'] = $filesop[12];
                    $empinsdata[$c]['type'] = $filesop[13];
                    $empinsdata[$c]['batchdate'] = date('Y-m-d');
                    $empinsdata[$c]['batchstatus'] = "UPLOADED";
                    $empinsdata[$c]['batchname'] = $_POST['batch_name'];                      
            //insert record from csv        
                }  
            $c = $c + 1;
            }
            $id = \DB::table('f_empinsupload_t')->insert($empinsdata);    
        }else {

            $message = "Please upload an valid CSV file";
            // return Redirect::to($return)->with('messagetext',$message)->with('msgstatus','error');      
            return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }
         
    
    // return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');    
        return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));
         
    } 
    /*end*/

/*Validation For Uploaded File*/
    function uploadValidation($valModname, $valBatch, $status) {
        
        $status['status']='success';
        $status['message'] =' ';
        $sql = "select * from f_empinsupload_t where batchstatus ='UPLOADED'  and batchname='" . $valModname . "'"; 
        $result_pr = \DB::select($sql); 
        //dd($result_pr);
        if (!empty($result_pr)) 
        { 
            foreach($result_pr as $key=>$value)
            {
                $status['message']='';
                $status['status']='success';
        
                    if(!empty($value->EmployeeName))
                    {
                        $employee_name = $this->valid($value->EmployeeName,"name");
                        if ($employee_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'].= 'Employee Name does not exist' . ' ,';
                        }
                    }else{
                        $status['status'] = 'error';
                        $status['message'].= 'Employee Name Empty.. Please enter Employee Name' . ',';
                    }

                    if(!empty($value->EmployeeNumber))
                    {
                        $emp_num = $this->valid($value->EmployeeNumber,"id");
                        if ($emp_num[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'].= 'Employee Number does not exist' . ' ,';
                        }
                    }else{
                        $status['status'] = 'error';
                        $status['message'].= 'Employee Number Empty.. Please enter Employee Number' . ',';
                    }
                    
 
           //  dd($status['message']);
             
                if($status['status'] == "error") 
                {
                    $sql = "update f_empinsupload_t set batchstatus ='ERROR' , batchcomments='" . $status['message'] . "\n'  WHERE id ='" . $value->id . "' ";
                    $result = \DB::update($sql);
                    $status['message']='Uploaded data have some error';  
                } 
                else
                {
                    $sql = "update f_empinsupload_t set batchstatus ='VALIDATED' , batchcomments='' where id='" . $value->id . "' ";
                    $result = \DB::update($sql);
                    $status['status']=='success';
                    $status['message']='Incentive Data validated successfully';
                    
                }
            }
           
        // dd($status);
           return $status;


}

        else
        {
            $status['status']=='info';
            $status['message']='Batch Already Validated';
            return $status;
        }
        

    }
     
    
    public function valid($value=null,$type=null){
        if($type=='name'){
            $cond=" and first_name='".$value."'";     
        }else if($type=='id'){
            $cond=" and employee_number='".$value."'";
        }else if($type=='eid'){
            $cond=" and employee_id='".$value."'";
        }else{
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt, employee_id, employee_number,first_name from hr_employee_t where 1=1 $cond");
        return  $sql;
    }
    
       public function acc($value=null,$type=null){
           //dd($value);
           
        if($type=='name'){
            $cond=" and account_name='".$value."'";
        }else if($type=='id'){
            $cond=" and concatenated_segments='".$value."'";
        }
        $sql=\DB::select("select count(*)  as cnt, f_account_structure_id, account_name from f_account_structure_t where 1=1 $cond");
        return $sql;
    }
    

/*Load Function*/
     public function LoadMaster($loadModname, $valBatch, $status){
         
        $status['status'] = '';
        $status['message'] = '';
        $sql = "SELECT * FROM f_empinsupload_t WHERE batchstatus = 'VALIDATED' AND batchname = '" . $loadModname . "'";
        $result = \DB::select($sql);
        //dd($result);
    if (count($result) > 0) {
        $loadid = [];
        $totalBalance = 0;
        $endDate = null;
    
        foreach ($result as $value) {
            $loadid[] = $value->id;
            $totalBalance += $value->amount; // Aggregate the balance amount
            $endDate = $value->batchdate; // Get the Enddate (assuming it's the same for all records)
        }

    // Prepare data for single header row
    $data = [
        'expense_no' => '',
        'expenses_count' => '',
        'expense_date' => $endDate,
        'expense_status' => 'INITIATED',
        'expense_amount' => $totalBalance,
        'remarks' => '',
        'pay_amount' => '0',
        'company_id' => \Session::get('companyid'),
        'created_by' => \Session::get('id'),
        'location_id' => \Session::get('location'),
        'organization_id' => \Session::get('organization'),
        'created_at' => date("Y-m-d"),
        'last_updated_by' => \Session::get('id'),
        'updated_at' => date("Y-m-d"),
        'reference_id' => '0',
        'paid_amount' => '0',
        'payment_status' => '0',
        'balance_amount' => $totalBalance, 
        'tax_amount' => '0',
        'round_off' => '0',
        'source' => '',
    ];

    try {
        // Insert a single row into the header table
        $id = \DB::table('f_emp_expenses_t')->insertGetId($data);

        // Update all matching rows in f_empinsupload_t
        $in = implode(',', $loadid);
        $sql = "UPDATE f_empinsupload_t SET batchstatus = 'LOADED', expenses_id = $id WHERE id IN ($in)";
        \DB::update($sql);

        $status['status'] = 'success';
        $status['message'] = 'Incentive Data Loaded Successfully';
        return $status;
    } catch (\Illuminate\Database\QueryException $e) {
        $message = explode('(', $e->getMessage());
        $dbCode = rtrim($message[0], ']');
        $dbCode = trim($dbCode, '[');
        $error = explode(":", $dbCode);

        $status['status'] = 'error';
        $status['message'] = $error[2];
        $comment = '"' . $error[2] . '"';

        $in = implode(',', $loadid);
        $sql = "UPDATE f_empinsupload_t SET batchstatus = 'ERROR', batchcomments = $comment WHERE id IN ($in)";
        \DB::update($sql);
        return $status;
    }
} else {
    $sql = \DB::select("SELECT * FROM f_empinsupload_t WHERE batchname = '" . $loadModname . "'");
    if ($sql[0]->batchstatus == "UPLOADED") {
        $status['status'] = 'info';
        $status['message'] = 'Please Validate the Batch First..!';
    } elseif ($sql[0]->batchstatus == "ERROR") {
        $status['status'] = 'error';
        $status['message'] = 'Batch Error..!';
    } else {
        $status['status'] = 'info';
        $status['message'] = 'Incentive Data already Loaded';
    }
    return $status;
}
    }
/*End*/

/* purpose:expense lines*/

    public function LoadLineMaster($loadModname, $valBatch, $status) {
    $status['status'] = '';
    $status['message'] = '';
    $sql = "SELECT * FROM f_empinsupload_t WHERE batchstatus = 'LOADED' AND expenses_id IS NOT NULL AND batchname = '" . $loadModname . "'";
    $result = \DB::select($sql);
    $data1 = [];
    $loadid = [];

    if (count($result) > 0) {
    foreach ($result as $key => $value) {
        $uploadid = $value->id;
        $loadid[] = $uploadid;

        $chsfl = $value->Files;
        $emp_id = $this->valid($value->EmployeeNumber, "id");
       
     if ($value->type == "Mobile Allowance") {
            $acc_id = $this->acc($value->Zone . ' - Mobile Allowance', 'name');
        
            if ($value->Zone == "Local Conveyance") {
                $acc_id[0]->f_account_structure_id = "388";
            }
        } else {
            $acc_id = $this->acc('PERFORMANCE EARNINGS - ' . $value->Zone, 'name');
        }

        // Fetch f_account_structure_id for tds_account
        $tds_acc_result = \DB::select("SELECT f_account_structure_id  FROM f_account_structure_t WHERE concatenated_segments = '$value->tds_account' ");
        $tds_account_id = $tds_acc_result[0]->f_account_structure_id ?? 0;
         // dd($tds_account_id);
        $data1[] = [
            'bill_no' => $value->BillNo,
            'bill_date' => $value->BillDate,
            'choosefile' => '["' . $chsfl . '"]',
            'employee_id' => $emp_id[0]->employee_id,
            'remarks' => $value->Remarks,
            'tds_percentage' => $value->tds_rate,
            'tds_applicable' => strtoupper($value->tds_applicable),
            'tds_amount' => $value->tds_amount,
            'emp_exp_total' => $value->amount,
            'tds_account_id' => $tds_account_id,
            'payment_request_status' => '0',
            'payment_status' => '0',
            'balance_amounts' => '0',
            'company_id' => \Session::get('companyid'),
            'created_by' => \Session::get('id'),
            'location_id' => \Session::get('location'),
            'organization_id' => \Session::get('organization'),
            'created_at' => date("Y-m-d"),
            'last_updated_by' => \Session::get('id'),
            'updated_at' => date("Y-m-d"),
            'expense_id' => $value->expenses_id,
            'expense_account_id' => $acc_id[0]->f_account_structure_id,
            'expense_line_amount' => $value->incentive_amount,
        ];
    }

        // Bulk insert all rows at once
        if (!empty($data1)) {
            \DB::table('f_emp_expenses_lines_t')->insert($data1);
        }

        // Update all rows in `f_empinsupload_t`
        $uploadIds = implode(',', $loadid);
        $sql = "UPDATE f_empinsupload_t SET batchstatus = 'LINELOADED' WHERE id IN ($uploadIds)";
        \DB::update($sql);

        $status['status'] = 'success';
        $status['message'] = 'Incentive Line Data Loaded Successfully';
        return $status;
    } else {
        $sql = \DB::select("SELECT * FROM f_empinsupload_t WHERE batchname = '" . $loadModname . "'");
        if ($sql[0]->batchstatus == "UPLOADED") {
            $status['status'] = 'info';
            $status['message'] = 'Please Validate the Batch First..!';
        } elseif ($sql[0]->batchstatus == "ERROR") {
            $status['status'] = 'error';
            $status['message'] = 'Batch Error..!';
        } else {
            $status['status'] = 'info';
            $status['message'] = 'Incentive Line Data already Loaded';
        }
        return $status;
    }
}


/*end*/    


    public function docsupload(Request $request) {
        
    if($request->hasfile('files')){ 
    
        foreach($request->file('files') as $file)
            {
                $name=$file->getClientOriginalName();
                $file->move(public_path().'/exptmpfileupld', $name);
                $dataupload[] = $name;
            }

        }
    }
    
        
    
    /* purpose:to return status*/
    public function getempinsvalidate(Request $request) 
    {
        
              if (isset($_GET['batchname'])) 
        {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND f_empexpupload_t.batchname = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) 
        {
            $type = 'EMPINSUPLOAD';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':$upload = $this->uploadValidation($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;
                case 'load':$upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;
                case 'lineload':$upload = $this->LoadLineMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;    
                /*case 'fileupload':$upload = $this->FileUpload($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;        */
            }
        }
      
      
      
    }

    
    
    
}