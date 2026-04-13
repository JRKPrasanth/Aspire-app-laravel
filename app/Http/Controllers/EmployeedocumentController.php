<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Employeedocument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller,DB;

class EmployeedocumentController extends Controller
{
  
    
    public function __construct()
    {
        $this->data=array();
        $this->model=new Employeedocument();
        $this->data['urlmenu']=$this->indexs(); 
         $this->data['pageMethod']=\Request::route()->getName();
    }
/*** employee document index pae function start **/
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
 
        $table = \DB::table('m_employee_doc_check_list')->get();
        $this->data['datas'] = $table;
       
        return view('employeedocument.form',$this->data);
    }
    /*** employee document index pae function end **/
        /*** employee document save function start **/
    public function save(Request $request)
    {
        
        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
           
            $position = new Employeedocument();
            $position->emp_document = $_POST['emp_document'];
            $position->active       = $request->input('active');
            $position->save(); 
            $name = $position->getKeyName();
            $id = $position->$name; 
            $table = $position->getTable();
            $column = $position->getKeyName();
            $this->hrmssaveinsert($table,$column,$id,1);
            // auditlog
              $this->auditlog($id,"employeedocument","create",$_POST,"m_employee_doc_check_list");
            return 1;
        }
        else
        { 
            $position = new Employeedocument();
            $edit_id=$_POST['edit_id'];
            Employeedocument::find($edit_id)->update($_POST); 
              // auditlog
              $this->auditlog($edit_id,"employeedocument","edit",$_POST,"m_employee_doc_check_list");
            $table = $position->getTable();
            $column = $position->getKeyName();
            $this->hrmssaveinsert($table,$column,$edit_id,2);
            return 2;
        }
    }

        /*** employee document save function end **/
   

   
   //** remove function for employee document start ***/
     public function getRemove(Request $request,$id=null)
    {
      
  
        $j=0;
        if($j==0)
        {
            $query = DB::table('m_employee_doc_check_list')->where('id',$id)->delete();
                  // auditlog
              $this->auditlog($id,"employeedocument","delete",'',"m_employee_doc_check_list");
        }
       
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
    }
       //** remove function for employee document end ***/
       //**employee document name duplicate check function start ***/
    public function getCheckname(Request $request)
    {
        
        $edit_id = $_GET['edit_id'];
        if($edit_id == '')
		{
			
            $query=DB::table('m_employee_doc_check_list')->where('emp_document',$_GET['emp_document'])->get();
        }
        else
        {              
            $whereData = [['emp_document', $_GET['emp_document']],['id', '!=', $edit_id]];
            $query=DB::table('m_employee_doc_check_list')->where($whereData)->get();
        }
       
        if(count($query)>0)
            return 1;
        else
            return 0;
    }
	    //**employee document name duplicate check function end ***/
        //**employee document data grid function start***/

    public function employeedocumentgrid(Request $request) { 
		
		    if ($request->ajax()) {
        $data = \DB::table('m_employee_doc_check_list')
            ->select(['*']);

        return DataTables::of($data) ->make(true);
    }

    }

    

}
