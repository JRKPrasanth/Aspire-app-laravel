<?php

namespace App\Http\Controllers;

use App\Floatingpointsettings;
use Illuminate\Http\Request; 
use DB;


class FloatingpointsettingsController extends Controller
{
   
    public function index()
    {  
		$fpvalue= DB::table('settings_tbl')->get();   
		$this->data['decimal']=$fpvalue[0]->decimal_points;
		$this->data['settings_tbl_id']=$fpvalue[0]->settings_tbl_id;
		$this->data['date_format']=$this->jcombologin("date_formats_tbl","date_formats_id","javascript_format",$fpvalue[0]->date_format); 
		$this->data['time_format']=$this->jcombologin("time_formats_tbl","time_formats_id","display_format",$fpvalue[0]->time_format);
		$this->data['urlmenu']=$this->indexs();
        return view('floatingpointsettings.table',$this->data); 
    }


    public function save(Request $request,$id=null)
    { 
	 
       $fpvalues = new Floatingpointsettings(); 
	   $fpvalues->decimal_points=$_POST['decimal_points']; 	  
	   $fpvalues->date_format=$_POST['date_format']; 	  
	   $fpvalues->time_format=$_POST['time_format']; 	  
	   $id=$_POST['settings_tbl_id']; 
		
		\DB::table('settings_tbl')
            ->where('settings_tbl_id', $id)
				->update(['decimal_points' => $_POST['decimal_points'],'date_format' => 	$_POST['date_format'],'time_format'=>$_POST['time_format']]);		
	     return redirect('floatingpointsettings')->with('Success','your data Updated successfully'); 
		  
    }

  

   
}
