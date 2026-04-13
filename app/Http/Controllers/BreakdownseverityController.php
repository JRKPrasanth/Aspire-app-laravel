<?php

namespace App\Http\Controllers;

use App\Breakdownseverity;
use Illuminate\Http\Request;
use DB;
use yajra\datatables\datatables;

class BreakdownseverityController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model = new Breakdownseverity();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='breakdown_severity';
        $this->table=" breakdown_severity";   
        $this->middleware('auth');
        //$this->data['urlmenu']=$this->indexs();
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


       // $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
                $table = \DB::table('breakdown_severity')->get();
                $this->data['datas']=json_encode($table);
                $this->data['pageMethod']="breakdownseverity";
          return view('breakdownseverity.form',$this->data);
    }

      public function create()
    {
        return view('breakdownsenerity.form');
    }


	public function severitygrids(Request $request)
{
    if ($request->ajax()) {
		
        $data = \DB::table('breakdown_severity')->select('breakdown_severity.*');

        return DataTables::of($data)->make(true);
    }
}
	
        
	 public function save($id=null,Request $request)
    {   
         $edit_id = $request->input('edit_id');

        if($edit_id == '')
        {
            
            $breakdownseverity= new Breakdownseverity();
            $breakdownseverity->severity_name = $request->input('severity_name');
            $breakdownseverity->description =  $request->input('description');
            $breakdownseverity->updated_at =  "";
            $breakdownseverity->created_at =  "";
            $breakdownseverity->save(); 

            return 1;
        }
        else
        {  
			$action="Edit";
            $edit_id=$_POST['edit_id'];
            breakdownseverity::find($edit_id)->update($_POST); 
            $this->auditlog($edit_id,"breakdownseverity",$action,$_POST,"breakdown_severity");
            return response()->json(array('status' => 'success', 'message' => 'Serverity Updated Successfully','id'=>$edit_id));
        }
       
    }


 
    public function destroy($id)
  {
          $del_id = $id;

        $column = array('breakdown_sevearity');
        $table = array('b_maintenance_t');
        for($i=0; $i<count($table); $i++)
        {
            
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$del_id)->get();
            
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }

        if($j==0)
        {
            $query =\DB::table('breakdown_severity')->where('breakdownseverity_id',$del_id)->delete();
           
        }
       
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
    }
	
}
