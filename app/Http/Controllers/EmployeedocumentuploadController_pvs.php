<?php

namespace App\Http\Controllers;

use App\Employeedocumentupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;

class EmployeedocumentuploadController extends Controller
{
    	public function __construct()
	{
      
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
		
		}

    public function index()
    {
		
        return view('employeedocumentupload.table',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /** Employee Document check Load Form Start **/

    public function create($id = null)
    {
       
        $result  = Employeedocumentupload::find($id);
        $employee_documents=DB::table("hr_employee_document_tbl")
                ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_employee_document_tbl.first_name')
                ->select('hr_employee_t.first_name')->get();
                $query = DB::select("select first_name from hr_employee_document_tbl  where id='$id'");
                $id_name='';
                if(count($query)>0){
                    $id_name = $query[0]->first_name;
                }
        $employee_company_check_list=DB::table("m_company_check_list")->get();
        $employee_documents_check_list=DB::table("m_employee_doc_check_list")->get();
        $this->data['employee_name']      = $this->jCombo('hr_employee_t','employee_id','first_name',$id_name);
        $this->data['company_provision']      = $employee_company_check_list;
        $this->data['company_pro']      = $employee_documents_check_list;
        $this->data['employee_provision']      = $this->jCombo('m_employee_doc_check_list','id','emp_document','');
        $this->data['email']        =$result->email;
        $this->data['mobile_number']=$result->work_telephone_number;
        $this->data['employee_id']=$result->id;
        $this->data['company_provision_data']=$result->doc_field;
        /***company provision ***/
        $this->data['company_remarks']=$result->emp_rem;
        /**** employee provision **/
        $this->data['employee_provision_data']=$result->emp_photo;
        $this->data['employee_remarks']=$result->emp_remarks;
        return view('employeedocumentupload.form',$this->data);
    }
    /** Employee Document check Load Form End **/
   
   /** Employee Document check Save Start **/
    public function store(Request $request)
    { 
     //   dd($request);
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
            $files = Input::file('file_upload');
            if ($files!== null ) {
               $file_count = count($files);
            }
            else{
                $file_count = '';
            }
             $doc_field1 = '';
        $emp_rem1 =  '';
       
         
            if($document_name != '')
            {
                
                $file_name              =   (isset($files[$i]) && !empty($files[$i])) ? $files[$i]->getClientOriginalName() : "";
				if(isset($files[$i]) && !empty($files[$i])){
				  $destinationPath = './documentupload/'.$update_id.'/';
					   $file = $files[$i];
				 $uploadSuccess = $file->move($destinationPath, $file_name);
				}
                $employee_remarks       =   isset($request->input('employee_remarks')[$i]) ? $request->input('employee_remarks')[$i] : "";
                $result11[]              =   array($document_name,$file_name);
                $result12[]             =   array($document_name,$employee_remarks);
               
                
            }
        }
      
            $doc_field1 = json_encode($result11);
                $emp_rem1 =  json_encode($result12);
           
            DB::table('hr_employee_document_tbl')->where('id',$update_id)->update(array('doc_field' =>$doc_field,'emp_rem'=>$emp_rem,'emp_photo'=>$doc_field1,'emp_remarks'=>$emp_rem1));
            return 1;
        
    }
    /** Employee Document check Save End **/
   
    /** Jqgrid Employee Document Load Data Start **/
    public function employeedocumentgriddata()
    {
		
    $comp=\Session::get('companyid');
	$wh='';
		$search_tables=["hr_employee_document_tbl"];
		  if($_GET['_search']=='true'){
         $wh=$this->jqgridsearch("hr_employee_t",$_GET['filters'],$search_tables);
       }
	$page = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx = $_GET['sidx'];
	$sord = $_GET['sord'];
	if(!$sidx) $sidx =1;
	$result = \DB::select("SELECT COUNT(hr_employee_document_tbl.id) AS count FROM hr_employee_document_tbl as hr_employee_document_tbl  LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_document_tbl.employee_number  where 1=1 and hr_employee_document_tbl.company_id=$comp   $wh");
	$count = $result[0]->count;
	if( $count > 0 && $limit > 0)
        {
            $total_pages = ceil($count/$limit);
	}
        else
        {
            $total_pages = 0;
	}

	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit;
	if($start <0) $start = 0;
		
	$SQL = "SELECT hr_employee_t.first_name,
	             hr_employee_t.employee_number,
                hr_employee_document_tbl.id,
                hr_employee_document_tbl.work_telephone_number,
                hr_employee_document_tbl.email from hr_employee_document_tbl
                LEFT JOIN hr_employee_t ON hr_employee_t.employee_id =hr_employee_document_tbl.employee_number 
                 where 1=1 and hr_employee_document_tbl.company_id=$comp $wh ORDER BY $sidx $sord LIMIT $start,$limit";
	$result = \DB::select($SQL);
	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	echo json_encode($responce);
    }
    /** Jqgrid Employee Document Load Data End **/
}
