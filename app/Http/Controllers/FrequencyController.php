<?php

namespace App\Http\Controllers;
use DB;
use App\frequency;
use Illuminate\Http\Request;
use yajra\datatables\datatables;
	
class FrequencyController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model = new frequency();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='frequency_tbl';
        $this->table=" frequency_tbl";   
        $this->middleware('auth');
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

        $this->data['pageMethod']="frequency";
        return view('frequency.form',$this->data);
    }

    
    public function save($id=null,Request $request)
    {   
        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
            
            $frequency= new frequency();
            $frequency->frequency_name = $request->input('frequency_name');
          
            $frequency->description =  $request->input('description');
            $frequency->updated_at =  "";
            $frequency->created_at =  "";
            
            $frequency->save(); 

			 return response()->json(array('status' => 'success', 'message' => 'Frequency Saved Successfully','id'=>$edit_id));
        }
        else
        {  
			$action="Edit";
            $edit_id=$_POST['edit_id'];
            frequency::find($edit_id)->update($_POST); 
            $this->auditlog($edit_id,"frequency",$action,$_POST,"frequency_tbl");
            return response()->json(array('status' => 'success', 'message' => 'Frequency Updated Successfully','id'=>$edit_id));
   
        }
       
    }

    
    public function frequencygrid(Request $request)
    {


        if ($request->ajax()) {

    
          $data = \DB::table('frequency_tbl')->select('*')->get();
    
            return DataTables::of($data)->make(true);
        }

    }
  
    
    public function destroy($id=null)
  

     {

    $column = array('capacity');

        $table = array('w_machine_hdr_t'); //machine_hdr_t
        for($i=0; $i<count($table); $i++)
        {
            // dd($id);
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
        if($j==0)
        {
           // dd("fdg");
            $query = \DB::table('frequency_tbl')->where('frequency_id',$id)->delete();
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id,"frequency",$action,$id,"frequency_tbl");
            
        }

	return response()->json(array('status' => 'success', 'message' => 'Deleted Successfully'));

    }  
}
