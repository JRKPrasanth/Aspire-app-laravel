<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Targetmis;
use Illuminate\Http\Request;
use Session;
use DB;
use Illuminate\Support\Facades\Input;


class TargetmisController extends Controller
{
    
    
        public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	 }
	 
	 
	 
    
    public function index(Request $request){
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

        
         $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
         
            
            $this->data['pageMethod'] = \Request::route()->getName();

        
         return view('targetmis.form', $this->data);
        
        
    }
    

public function gettargetData(Request $request)
{

	//dd("hii");
    if ($request->ajax()) {
        $data = \DB::table('sd_targetname_t')
            ->select(['*']);
        return DataTables::of($data)
            ->rawColumns(['actions'])
            ->make(true);
    }
}
	
	public function save(Request $request)  
	{ 

			$target = new Targetmis();


            $target->f_year = $_POST['fy_year'];
            $target->target_name = $_POST['target'];
            $target->active = $_POST['status'];
            $target->created_by = $_POST['created_by'];
            $target['created_at'] = date('Y-m-d h:s:i');
            $target['updated_at'] = date('Y-m-d h:s:i');
            $target['last_updated_by'] = \Session::get('id');
            $target['organization_id'] = \Session::get('organization');
            $target['location_id'] = \Session::get('loc_id');

        
            $target->save();

        // Return success response
        return response()->json(['status' => 'success', 'message' => 'Saved Successfully']);

        // If an error occurs, return error response
        $errorMessage = $e->getMessage(); // You may customize the error message as per your requirement
        return response()->json(['status' => 'error', 'message' => $errorMessage]);
    
}

    
    
    
    
    
    
}