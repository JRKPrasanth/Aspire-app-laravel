<?php

namespace App\Http\Controllers;

use App\Employeedocumentupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Yajra\DataTables\DataTables;

class EmployeedocumentuploadController extends Controller
{
    	public function __construct()
	{
      
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
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

		
        return view('employeedocumentupload.table',$this->data);
    }

    public function create($id = null)
    {
       if($id!=0){
        $result  = Employeedocumentupload::find($id);
        $employee_documents=DB::table("hr_employee_document_tbl")
                ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_employee_document_tbl.first_name')
                ->select('hr_employee_t.first_name')->get();
                $query = DB::select("select first_name from hr_employee_document_tbl  where id='$id'");
                $id_name='';
                if(count($query)>0){
                    $id_name = $query[0]->first_name;
                }
                $this->data['id']=$id;
        $employee_company_check_list=DB::table("m_company_check_list")->get();
        $employee_documents_check_list=DB::table("m_employee_doc_check_list")->get();
        $this->data['employee_name']      = $this->jCombo('hr_employee_t','employee_id','first_name',$result->employee_number);
        $this->data['company_provision']      = $employee_company_check_list;
        $this->data['company_pro']      = $employee_documents_check_list;
        $this->data['employee_provision']      = $this->jCombo('m_employee_doc_check_list','id','emp_document','');
        $this->data['email']        =$result->email;
        $this->data['mobile_number']=$result->work_telephone_number;
        $this->data['employee_id']=$result->employee_number;
        $this->data['company_provision_data']=$result->doc_field;
        /***company provision ***/
        $this->data['company_remarks']=$result->emp_rem;
        /**** employee provision **/
        $this->data['employee_provision_data']=$result->emp_photo;
        $this->data['employee_remarks']=$result->emp_remarks;
        //dd($this->data);
    }else{
        $this->data['id']="";
        $emp=\DB::table("hr_employee_t")->where("employee_id",$_GET['empid'])->get();
 $this->data['employee_name']      = $this->jCombo('hr_employee_t','employee_id','first_name',$_GET['empid']);
  $employee_company_check_list=DB::table("m_company_check_list")->get();
        $employee_documents_check_list=DB::table("m_employee_doc_check_list")->get();
      
        $this->data['company_provision']      = $employee_company_check_list;
        $this->data['company_pro']      = $employee_documents_check_list;
        $this->data['employee_provision']      = $this->jCombo('m_employee_doc_check_list','id','emp_document','');
        $this->data['email']        =$emp[0]->email;
        $this->data['mobile_number']=$emp[0]->work_telephone_number;
        $this->data['employee_id']=$_GET['empid'];
        $this->data['company_provision_data']="";
        /***company provision ***/
        $this->data['company_remarks']="";
        /**** employee provision **/
        $this->data['employee_provision_data']="";
        $this->data['employee_remarks']="";
    }
        return view('employeedocumentupload.form',$this->data);
    
    }
    /** Employee Document check Load Form End **/
   public function show($id=null){
        $result  = Employeedocumentupload::find($id);
        $employee_documents=DB::table("hr_employee_document_tbl")
                ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_employee_document_tbl.first_name')
                ->select('hr_employee_t.first_name')->get();
               
                $this->data['id']=$id;
        $employee_company_check_list=DB::table("m_company_check_list")->get();
        $employee_documents_check_list=DB::table("m_employee_doc_check_list")->get();
        $this->data['employee_name']      = $this->idname('first_name','hr_employee_t','employee_id',$result->employee_number);
        $this->data['company_provision']      = $employee_company_check_list;
        $this->data['company_pro']      = $employee_documents_check_list;
        $this->data['employee_provision']      = $this->idname('emp_document','m_employee_doc_check_list','id','');
        $this->data['email']        =$result->email;
        $this->data['mobile_number']=$result->work_telephone_number;
        $this->data['employee_id']=$result->employee_number;
        $this->data['company_provision_data']=$result->doc_field;
        /***company provision ***/
        $this->data['company_remarks']=$result->emp_rem;
        /**** employee provision **/
        $this->data['employee_provision_data']=$result->emp_photo;
        $this->data['employee_remarks']=$result->emp_remarks;
    
   return view('employeedocumentupload.view',$this->data); 
   
   }
   /** Employee Document check Save Start **/
    public function store(Request $request)
    { 
     // dd($request);
        $employeedocumentupload = new Employeedocumentupload();
        $company_document= $request->input('company_doc');
        $provision_date= $request->input('provision_date');
        $document= $request->input('document');
        $update_id= $request->input('employee_id');
        
      $result=[];
      $result1=[];
        for($i=0;  $i <count($provision_date); $i++)
        {
            $company_doc1 = !empty($request->input('company_doc'.$i))? $request->input('company_doc'.$i) : "";
            
            
           $doc_field = '';
        $emp_rem = '';
			
            if($company_doc1 != '')
            {
          
                $date    =  isset($request->input('provision_date')[$i]) ? $request->input('provision_date')[$i]  : "";
                $company_remarks    =  isset($request->input('company_remarks')[$i]) ? $request->input('company_remarks')[$i] : "";
    
                $result[]          = array($company_doc1,$date);
                $result1[]          = array($company_doc1,$company_remarks);
               
            }
          
        }
        $doc_field = json_encode($result);
        $emp_rem =  json_encode($result1);
  
       $result11=[];
		$result12=[];
        for($i=0; $i<count($document); $i++)
        {
            $document_name = !empty($request->input('document')[$i])? $request->input('document')[$i] : "";
            //dd($document_name);
            $files = Input::file('file_upload');
            
            if ($files!== null ) {
               $file_count = count($files);
            }
            else{
                $file_count = '';
            }
             $doc_field1 = '';
        $emp_rem1 =  '';
       //dd($document_name);
        //  if($i==1){
        //       dd($document_name);
        //       }
        
            if($document_name != '')
            {
                
                $file_name              =   (isset($files[$i]) && !empty($files[$i])) ? $files[$i]->getClientOriginalName() : "";
                if(isset($_POST['ex_file'][$i])){
            $ex_file=$_POST['ex_file'][$i];    
            }else{
            $ex_file='';    
            }    
				if(isset($files[$i]) && !empty($files[$i]) && $ex_file==''){
				  $destinationPath = './documentupload/'.$update_id.'/';
					   $file = $files[$i];
				 $uploadSuccess = $file->move($destinationPath, $file_name);
			}
			else if(empty($files[$i]) && $ex_file!=''){
                $file_name              = $ex_file;  
				
            }else if (!empty($files[$i]) && $ex_file!=''){
               
				if(isset($files[$i]) && !empty($files[$i])){
				  $destinationPath = './documentupload/'.$update_id.'/';
					   $file = $files[$i];
				 $uploadSuccess = $file->move($destinationPath, $file_name);
				}else{
				$file_name              = $ex_file;      
				}
            }else{ 
				if(isset($files[$i]) && !empty($files[$i])){
				  $destinationPath = './documentupload/'.$update_id.'/';
					   $file = $files[$i];
				 $uploadSuccess = $file->move($destinationPath, $file_name);
				}
            }
				
				
                $employee_remarks       =   isset($request->input('employee_remarks')[$i]) ? $request->input('employee_remarks')[$i] : "";
                $result11[]              =   array($document_name,$file_name);
                $result12[]             =   array($document_name,$employee_remarks);
               
                
            }
        }
      
            $doc_field1 = json_encode($result11);
                $emp_rem1 =  json_encode($result12);
                if($_POST['edit_id']==""){
           $did=\DB::table('hr_employee_document_tbl')->insertGetId(
    ['doc_field' => $doc_field, 'emp_rem' => $emp_rem,'emp_photo'=>$doc_field1,'emp_remarks'=>$emp_rem1,'employee_number'=>$_POST['employee_name'],'work_telephone_number'=>$_POST['mobile'],'email'=>$_POST['mail'],'company_id'=>\Session::get('companyid'),'location_id'=>\Session::get('location'),'created_by'=>\Session::get('id'),'created_at'=>date('Y-m-d'),'updated_at'=>date('Y-m-d')]);

       }else{
            DB::table('hr_employee_document_tbl')->where('id',$_POST['edit_id'])->update(array('doc_field' =>$doc_field,'emp_rem'=>$emp_rem,'emp_photo'=>$doc_field1,'emp_remarks'=>$emp_rem1,'updated_at'=>date('Y-m-d')));
        }
            return 1;
        
    }
    /** Employee Document check Save End **/
   
    /** Jqgrid Employee Document Load Data Start **/
    public function employeedocumentgriddata(Request $request)
    {
		
    $comp=\Session::get('companyid');
	$wh='';
	
		
	$SQL = "SELECT hr_employee_t.first_name,
	             hr_employee_t.employee_number,
                 hr_employee_t.employee_id,
                 hr_employee_t.active,
                hr_employee_document_tbl.id as docid,
                hr_employee_t.work_telephone_number,
                hr_employee_t.email from hr_employee_t
                LEFT JOIN hr_employee_document_tbl ON hr_employee_t.employee_id =hr_employee_document_tbl.employee_number 
                 where 1=1 and hr_employee_t.company_id=$comp $wh ";
	
    $result = \DB::select($SQL);

    return DataTables::of($result)->make(true);

    }

}
