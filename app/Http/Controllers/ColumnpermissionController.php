<?php

namespace App\Http\Controllers;

use App\Columnpermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ColumnpermissionController extends Controller
{
   
    public function index()
    {
        return view('columnpermission.table');
    }

   
        public function save(Request $request)
	{ 
          // dd($_POST);
            $type=$_POST['type'];
            \DB::update("update m_column_permission_t set active=0,action='' where module_name='$type'");
         foreach($_POST['columns'] as $val)
         {
            
             $action=$_POST['required'][$val];
             \DB::update("update m_column_permission_t set active=1,action='$action' where module_name='$type' and column_name='$val' ");
         }
         return redirect('columnpermission');
	}
        public function getcolumns(Request $request)
        {
            $data=\DB::table('m_column_permission_t')->where('module_name',$_GET['type'])->get();
          //  dd($data);
            return $data;
        }

}
