<?php

namespace App\Http\Controllers;
use App\Interviewprocess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Input;
use yajra\datatables\datatables;
use DB;

class InterviewprocessController extends Controller
{
    	public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs();
	 }
         /*** funcation for show form index start **/
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

        $interview_process     = Schema::getColumnListing('m_interview_steps');
        $this->data['row']= (object)array();
        foreach($interview_process as $key=>$value)
        {
            $this->data['row']->{$value}= '';
        }
      
        return view('interviewprocess.form',$this->data);
    }
         /*** funcation for show form index end **/
/*** save function start **/
    public function store(Request $request)
    {
         $edit_id =$request->input('edit_id');
       
        if($edit_id == "")
        {
            
            $interviewprocess = new Interviewprocess();
            $interviewprocess->interview_process = $request->input('interview_process');
            $interviewprocess->remarks       = $request->input('remarks');
            $interviewprocess->active       = $request->input('active');
			
            $result = $interviewprocess->save();
			$id = $interviewprocess->interview_steps_id;
			   // auditlog
              $this->auditlog($id,"interviewprocess","create",$_POST,"m_interview_steps");
			$table = $interviewprocess->getTable();
			$column = $interviewprocess->getKeyName();
			$this->hrmssaveinsert($table,$column,$id,1);
            return  1;
        }
        else
        {
            $interviewprocess  = Interviewprocess::findOrFail($edit_id); 
            $input_data = $request->all();
            $interviewprocess->fill($input_data)->save();
               // auditlog
              $this->auditlog($edit_id,"interviewprocess","edit",$_POST,"m_interview_steps");
			$table = $interviewprocess->getTable();
			$column = $interviewprocess->getKeyName();
			$this->hrmssaveinsert($table,$column,$edit_id,2);
            return 2;
        }
        
       
    }

   /*** save function end **/

     public function interviewprocessgrid(Request $request) { 
		
		    if ($request->ajax()) {
        $data = \DB::table('m_interview_steps')
            ->select(['*']);

        return DataTables::of($data) ->make(true);
    }

    }

/** remove function start **/
        public function getRemove(Request $request,$id=null)
        {
           
        
            $column = array('interview_process');
            $table = array('hr_schedule_interview');
            
            for($i=0; $i<count($table); $i++)
            {
                $j=0;
                $query = DB::table($table[$i])->where($column[$i],$id)->get();
                
                if(count($query)>0)
                {
                    $j=1;
                    break;
                }
            }
        
        if($j==0)
        {
            $query = DB::table('m_interview_steps')->where('interview_steps_id',$id)->delete();
                        // auditlog
              $this->auditlog($id,"interviewprocess","delete",'',"m_interview_steps");
            
        }
        if($j>0)
            return 1;
        else if($j == 0)
            return 2;
       
        }
        /** remove function end **/
    /*** interview step name duplicate check function start ***/
    public function getCheckname(Request $request)
    {
       
        $edit_id = $_GET['edit_id'];
        
        if($edit_id == '')
        {
            $department=DB::table('m_interview_steps')->where('interview_process',$_GET['interview_process'])->get();
        }
        else
        {              
            $whereData = [['interview_process', $_GET['interview_process']],['interview_steps_id', '!=', $edit_id]];
            $department=DB::table('m_interview_steps')->where($whereData)->get();
        }
        
        
        if(count($department)>0)
            return 1;
        else
            return 0;
        
        
    }
    /*** interview step name duplicate check function end ***/
   
}
