<?php

namespace App\Http\Controllers;

use App\Formsixteenupld;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Yajra\DataTables\DataTables;

class FormsixteenupldController extends Controller
{
     public function __construct() {
        $this->data = array();
      
        $this->table = "f_formsixteen_upload_t";
        $this->pageModule = "formsixteenupld";
        $this->model = new Formsixteenupld;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'formsixteenupld',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->modelname = new Formsixteenupld();
        $this->data['pageFormtype'] = 'ajax';
          $this->data['urlmenu']=$this->indexs(); 
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

       $table = \DB::table('f_formsixteen_upload_t')->get();
       $this->data['datas'] = $table;
       return view('formsixteenupld.table',$this->data);
    }


    
   /*  purpose for Display Data in JQgrid function */
    public function getFormsixteenupldData() {
		
        $wh = '';

		$org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');

          
        $SQL = "select * from (SELECT
        hr_employee_t.employee_id,
        hr_employee_t.email,
    hr_employee_t.employee_number,
    hr_employee_t.first_name,
    hr_employee_t.active,
    emp_type_t.lookup_code AS emp_type_name,
    m_position.position AS position_name,
    m_job_title.job_title_name
FROM
    hr_employee_t
LEFT JOIN m_position ON
    (
        m_position.position_id = hr_employee_t.position
    )
LEFT JOIN m_job_title ON
    (
        m_job_title.job_title_id = hr_employee_t.job_title
    )
LEFT JOIN a_lookuplines_t AS emp_type_t
ON
    (
        emp_type_t.lookuplines_id = hr_employee_t.employee_type
    )
WHERE
    hr_employee_t.active = 'Yes' AND hr_employee_t.date_of_leaving IS NULL AND employee_id NOT IN(1) )v1 where 1=1 $wh";
	
        $result = \DB::select($SQL);
		return DataTables::of($result)->make(true);
		
    }
	
	
      public function create($id = null) {
      if ($id == null) {
            $this->data['row'] = (object) array();
            $this->data['row']->bank_account_hdr_id = "";
            $this->data['row']->bank_name = "";
            $this->data['row']->bank_type = "";
            $this->data['row']->active = "";
             $this->data['row']->bank_source = "";
            $this->data['id'] = '';
          // dd($this->data);
            }
        else {
            $this->data['id'] = $id;
            $table = \DB::table('f_formsixteen_upload_t')->where('formsixteenupld_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['row']->bank_name = $table[0]->bank_name;
            $this->data['row']->bank_type = $table[0]->bank_type;
            $this->data['row']->bank_source = $table[0]->bank_source;
            $this->data['row']->active = $table[0]->active;
            
        }
		
              return view('formsixteenupld.form', $this->data);
    }
   
         /* Karthigaa purpose for Save function */
    public function save(Request $request) {
       
        $id = '';
       
        $data = $this->validatePost($request->all(), $this->table, 'header');
        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Form16 File Upload Successfully', 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException$e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
             dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
  
 /*Karthigaa purpose for Display hdr & Lines View function*/ 
  public function show(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*','tb_users.username','m_supplier_t.supplier_name','m_customers_t.customer_name','m_company_t.company_name')
                  ->leftjoin('tb_users','tb_users.id','=','f_bank_account_hdr_t.created_by')
                  ->leftjoin('m_supplier_t','m_supplier_t.supplier_id','=','f_bank_account_hdr_t.supplierid')
                  ->leftjoin('m_customers_t','m_customers_t.customer_id','=','f_bank_account_hdr_t.customerid')
                  ->leftjoin('m_company_t','m_company_t.company_id','=','f_bank_account_hdr_t.companyid')
		  ->where('bank_account_hdr_id',$id)->get(); 
          $this->data['bank_name']=$vdata[0]->bank_name;  
          $this->data['bank_type']=$vdata[0]->bank_type;
           $this->data['bank_source']=$vdata[0]->bank_source;
           $this->data['supplier_name']=$vdata[0]->supplier_name;
           $this->data['customer_name']=$vdata[0]->customer_name;
           $this->data['company_name']=$vdata[0]->company_name;
          $this->data['active']=$vdata[0]->active;
		  $this->data['username']=$vdata[0]->username;
          //dd($this->data['account_type']);
          return view('formsixteenupld.view',$this->data);
        }
    }

   public function delete($id=null){
			$query = \DB::table('f_formsixteen_upload_t')->where('formsixteenupld_hdr_id',$id)->delete();
			if($query){
				return 0;
			}
			else{
				return 1;
			}
	}
	
	
	public function form16upldedit()
    {

    $sql=\DB::select("select emp_id,acc_year,choosefile,remarks from f_formsixteen_upload_t where emp_id='".$_GET['id']."'");
          if(isset($sql)){
              $data['emp_id']=$sql[0]->emp_id;
              $data['acc_year']=$sql[0]->acc_year;
              $data['choosefile']=$sql[0]->choosefile;
              $data['remarks']=$sql[0]->remarks;
              $data['update']='update';
          }else{
              $data['emp_id']='';
              $data['acc_year']='';
              $data['choosefile']='';
              $data['remarks']='';
              $data['update']='create'; 
          }
              
          
        return $data;

    }
    
public function form16upldupdate(Request $request)
{
    $acc_year = $request->input('acc_year', '');
    $id = $request->input('id');
    $remarks = $request->input('remarks');
    $user_mail = $request->input('e_mail');
    $name = $request->input('emp_name');
    $org = \Session::get('organization');
    $loc = \Session::get('location');
    $compy = \Session::get('companyid'); 
    $created_by = \Session::get('id');
    $last_updated_by = \Session::get('id');
    $timestamp = date('Y-m-d H:i:s');

$employee = \DB::table('hr_employee_t')->select('first_name')->where('employee_id', $id)->first();

// Check if the employee exists
if ($employee) {
    $emp_name = $employee->first_name;
}
    $existingRecord = \DB::table('f_formsixteen_upload_t')
        ->where('emp_id', $id)
        ->where('acc_year', $acc_year)
        ->first();

    if ($existingRecord) {
        // Update the existing record
        $dataToUpdate = [
            'remarks' => $remarks,
            'last_updated_by' => $last_updated_by,
            'updated_at' => $timestamp,
        ];

        if ($request->hasFile('choosefile')) {
            $file = $request->file('choosefile');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Create the directory if it doesn't exist
            $destinationPath = public_path('uploads/form16/' . $existingRecord->formsixteenupld_hdr_id);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the file to the directory
            $file->move($destinationPath, $filename);

            $dataToUpdate['choosefile'] = $filename;
        }

        \DB::table('f_formsixteen_upload_t')
            ->where('emp_id', $id)
            ->where('acc_year', $acc_year)
            ->update($dataToUpdate);

        return response()->json(['success' => true, 'message' => 'Record updated successfully']);
    } else {
        // Insert a new record
        if ($request->hasFile('choosefile')) {
            $file = $request->file('choosefile');
            $filename = time() . '_' . $file->getClientOriginalName();

            $recordId = \DB::table('f_formsixteen_upload_t')->insertGetId([
                'emp_id' => $id,
                'acc_year' => $acc_year,
                'choosefile' => $filename,
                'remarks' => $remarks,
                'organization_id' => $org,
                'created_by' => $created_by,
                'created_at' => $timestamp,
                'last_updated_by' => $last_updated_by,
                'updated_at' => $timestamp,
                'location_id' => $loc,
                'company_id' => $compy,
            ]);

            // Create the directory for the new record ID
            $destinationPath = public_path('uploads/form16/' . $recordId);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the file to the newly created directory
            $file->move($destinationPath, $filename);

            // Email Sending Section
            try {
                
                $employee = $emp_name;
                $from_mail = "expenses@jrkresearch.com";
                $to_mail = $user_mail;
                $sub = "FORM 16 is in Aspire";

              $msg = "<p>Dear $employee<br><br>The Form 16 is now available in our Aspire application in PDF.<br>You may download the same for the year from the Aspire at the given path.<br><br>HRMS -> My Profile -> Form16 Download<br><br>Please reply to this mail for any clarifications.<br><br>Regards <br>Accounts Dept.,";

                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {

                    \Config::set('mail.username', \Session::get('user_email'));
                    \Config::set('mail.password', \Session::get('user_password'));
                }

                \Mail::send([], [], function ($message) use ($from_mail, $to_mail, $sub, $msg) {
                    $message->from($from_mail)
                            ->to($to_mail)
                            ->subject($sub)
                            ->setBody($msg, 'text/html');
                });

                return response()->json(['success' => true, 'message' => 'Record inserted successfully and mail sent']);
            } catch (\Exception $e) {
                \Log::error('Mail send error: ' . $e->getMessage());
                return response()->json(['success' => true, 'message' => 'Record inserted successfully, but mail not sent']);
            }
        } else {
            return response()->json(['success' => false, 'error' => 'No file uploaded']);
        }
    }
}


    
}
