<?php

namespace App\Http\Controllers;

use App\Jobtitle;
use App\Employeeallowance;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;

class JobtitleController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model=new Jobtitle();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageModule']=\Request::route()->getName();
        $this->data['urlmenu']=$this->indexs();
    }
    /** employee position index page  start**/
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

     
        return view('employeejobtitle.form',$this->data);
    }
	  /** employee position index page  end**/
        /** employee poisition grid load  start**/

        //ajima

      public function employeejobtitlegrid (Request $request) {  
		  

		  
    $SQL = "SELECT * FROM m_job_title where 1=1 ORDER BY job_title_id DESC";
    
        
        $result = \DB::select( $SQL );
        
		return DataTables::of($result)->make(true);

    }
    	
	
    /** employee poisition grid load end **/
     /** employee position save start**/
    public function save(Request $request)
    {
        
        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
            
            $jobtitle = new Jobtitle();
          
            $jobtitle->job_title_name =  $request->input('job_title_name');
            $jobtitle->job_description =  $request->input('job_description');
            $jobtitle->active = $request->input('active');
            $jobtitle->save(); 
            
            $name = $jobtitle->getKeyName();
            $id = $jobtitle->$name; 
            $table = $jobtitle->getTable();
            $column = $jobtitle->getKeyName();
            $this->hrmssaveinsert($table,$column,$id,1);
            // auditlog
              $this->auditlog($id,"employeeposition","create",$_POST,"m_job_title");
            return 1;
        }
        else
        { 
            $jobtitle = new Jobtitle();
            $edit_id=$_POST['edit_id'];
            Jobtitle::find($edit_id)->update($_POST); 
          /**Auditlog**/
            $this->auditlog($edit_id,"employeeposition","edit",$_POST,"m_job_title");
            $table = $jobtitle->getTable();
            $column = $jobtitle->getKeyName();
            $this->hrmssaveinsert($table,$column,$edit_id,2);
            return 2;
        }
    }
        /** employee position save end**/
        /** employee position duplicate name check start**/
    
      public function getCheckname(Request $request)
    {
       
        $edit_id = $_GET['edit_id'];
  
        if($edit_id == ''){
            $department=DB::table('m_job_title')->where('job_title_name','like',$_GET['job_title'])->get();
		    
        }
        else
        {              
            $whereData = [['job_title_name','like',$_GET['job_title']],['job_title_id', '!=', $edit_id]];
            $department=DB::table('m_job_title')->where($whereData)->get();
           
        }
      
        if(count($department)>0)
            return 1;
        else
            return 0;
        
        
    }
 
      /** employee position delete start**/
    public function getRemove($id = null)
    {
	
          try {

        // Try to delete
        $deleted = \DB::table('m_job_title')->where('job_title_id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Delete failed. Please try again.'
            ], 500);
        }

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
		
    }

	    public function indextype(Request $request)
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

        $this->data['employee_type'] = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','',' and lookup_type="EMPLOYEE_TYPE"');
      
        return view('employeejobtitle.form1',$this->data);
    }
    /** employee allowance index page  end**/
        /** employee allowance save start**/
 public function allowancesave(Request $request)
    {
        
        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
            
            $empallow = new Employeeallowance();
            $empallow->allowance_id = $request->input('allowance_id');
            $empallow->allowance_name =  $request->input('allowance_name');
            $empallow->employee_type =  $request->input('employee_type');
            $empallow->type =  $request->input('type');
            $empallow->active       = $request->input('active');
            
            $empallow->save(); 
            $name = $empallow->getKeyName();
            $id = $empallow->$name; 
            $table = $empallow->getTable();
            $column = $empallow->getKeyName();
            $this->hrmssaveinsert($table,$column,$id,1);
               /**Auditlog**/
            $this->auditlog($id,"employeetypeallowance","create",$_POST,"m_allowance_tbl");
            return 1;
        }
        else
        { 
          
            $empallow = new Employeeallowance();
            $edit_id=$_POST['edit_id'];
            Employeeallowance::find($edit_id)->update($_POST); 
            $table = $empallow->getTable();
            $column = $empallow->getKeyName();
            $this->hrmssaveinsert($table,$column,$edit_id,2);
               /**Auditlog**/
            $this->auditlog($edit_id,"employeetypeallowance","edit",$_POST,"m_allowance_tbl");
            return 2;
        }
    }
          /** employee allowance save end**/
          /** employee allowance name duplicate check start**/
       public function getChecknameallowance(Request $request)
    {
       
        $edit_id = $_GET['edit_id'];
        $employee_type = $_GET['employee_type'];
        
        if($edit_id == ''){
            $department=DB::table('m_allowance_tbl')->where('employee_type',$_GET['employee_type'])->where('allowance_name','like',$_GET['allowance_name'])->get();
		}
        else
        {              
            $whereData = [['allowance_name','like',$_GET['allowance_name']],['allowance_id', '!=', $edit_id]];
            $department=DB::table('m_allowance_tbl')->where($whereData)->where('employee_type',$_GET['employee_type'])->get();
        }
       
        if(count($department)>0)
            return 1;
        else
            return 0;
        
        
    }
   /** employee allowance name duplicate check end**/
  
	    public function employeeallowancegrid(Request $request) { 
		
		    if ($request->ajax()) {
        $data = \DB::table('m_allowance_tbl')
        ->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_allowance_tbl.employee_type')
            ->select(['m_allowance_tbl.*','a_lookuplines_t.lookup_code']);

        return DataTables::of($data) ->make(true);
    }

    }
	
 /** employee allowance grid load end**/
        /** employee allowance delete start**/
    public function getRemoveallowance(Request $request,$id=null)
    {
      
  
            $query = DB::table('m_allowance_tbl')->where('allowance_id',$id)->delete();
                  // auditlog
              $this->auditlog($id,"employeeallowance","delete",'',"m_allowance_tbl");
            return 0;
    }
	
	     /** employee allowance delete end**/
}
