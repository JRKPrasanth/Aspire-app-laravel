<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Adminreport;
use Illuminate\Http\Request;

class AdminreportController extends Controller
{
     public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }
    
    public function index()
    {
        return view('adminreport.table',$this->data);      
    }
   
	public function getuserpermissionrptdata(Request $request)
	{
		
			// query called in migration method - VIGNESH M 
		
		if ($request->ajax()) {
			$query = \DB::table('view_user_permission_logs'); 

			return datatables()->of($query)->make(true);
		}

	}


}
