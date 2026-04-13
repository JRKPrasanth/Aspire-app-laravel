<?php

namespace App\Http\Controllers;

use App\Jobreportsettings;
use Illuminate\Http\Request;
use DB;
class JobreportsettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $value= DB::table('jobreport_settings_t')->get();
		if(count($value)>0){
		$this->data['jobreport_settings_id']=$value[0]->jobreport_settings_id;
		$this->data['pricelist_id']=$this->jcustomselect("i_pricelist_hdr_t","pricelist_hdr_id","pricelist_name",$value[0]->pricelist_id,'and price_list_type="Sales"'); 
		}else{
		$this->data['jobreport_settings_id']="";
		$this->data['pricelist_id']=$this->jcustomselect("i_pricelist_hdr_t","pricelist_hdr_id","pricelist_name",'','and price_list_type="Sales"'); 
	
		}
			$this->data['urlmenu']=$this->indexs();
        return view('jobreportsettings.table',$this->data); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		 $id=$_POST['jobreport_settings_id']; 
	   if($id == ''){ 
       $jobreport = new Jobreportsettings(); 
	   $jobreport->pricelist_id=$_POST['pricelist_id']; 	  
	   $jobreport->company_id=\Session::get('companyid'); 	  
	   $jobreport->location_id=\Session::get('location'); 	
	   $jobreport->organization_id=\Session::get('organization'); 	  
	   $jobreport->created_by=\Session::get('id'); 	
	   $jobreport->created_at=date("Y-m-d H:i:s"); 	  
	   $jobreport->updated_at=date("Y-m-d H:i:s"); 	  
	   $jobreport->last_updated_by=\Session::get('id');
	   $jobreport->save();
		    $id1= DB::getPdo()->lastInsertId();
            $action="Create";
            $msg="Data Saved successfully";
		     
	   }else{
		$action="Edit";
            $edit_id=$_POST['jobreport_settings_id'];
            Jobreportsettings::find($id)->update($_POST); 
		   $msg="Data Updated successfully";
	   }
		 $this->auditlog($id,"jobreportsettings",$action,$_POST,"jobreport_settings_t");
		   return redirect('jobreportsettings')->with('Success',$msg); 

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Jobreportsettings  $jobreportsettings
     * @return \Illuminate\Http\Response
     */
    public function show(Jobreportsettings $jobreportsettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Jobreportsettings  $jobreportsettings
     * @return \Illuminate\Http\Response
     */
    public function edit(Jobreportsettings $jobreportsettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Jobreportsettings  $jobreportsettings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jobreportsettings $jobreportsettings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Jobreportsettings  $jobreportsettings
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jobreportsettings $jobreportsettings)
    {
        //
    }
}
