<?php
namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\country;
use App\Http\Controllers\Controller;
use DB;

class CountryController extends Controller
{  
	public function __construct()
	{
		
	  $this->module=new country();
	  
	  $this->data['pageFormtype']='ajax';
	  $this->data['pageMethod']=\Request::route()->getName();
          $this->data['urlmenu']=$this->indexs(); 
	}
	/*Jqgrid Data for Loading datas in tables*/
 
public function getcountryData(Request $request)
{
    if ($request->ajax()) {
        $query = \DB::table('m_countries_t')
            ->select('m_countries_t.*');

        return DataTables::of($query)->make(true);
    }
}
    /*Create Functon for Form*/
    public function create($id=null,$type=null)
    { 
		//dd($id);
         $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
      if(isset($id))
	  { 
		  $country =country::find($id);
		 
		  $this->data['row']=$country; 
		  if($type=='g')
			{
			  return $country;
		    }
	  }
	 else
	 {
	   $countrydata=\DB::connection()->getSchemaBuilder()->getColumnListing('m_countries_t');
	   $countrydatas=(object)array();
		  foreach($countrydata as $key=>$value)
			  {
		        $countrydatas->$value="";
          	  }
		        $this->data['row']=$countrydatas;
		 } 
	
		 //$this->data['opt']=$this->jqgridselect('m_uom_codes_t','uom_code_id','uom_code');
         return view('country.form',$this->data);
    }
	/*End*/

    /*Edit Function*/
     public function getedit($edit_id)
    {
       
        $column = array('country_id','country_name','active');
        $table = array('m_countries_t');
        for($i=0; $i<count($table); $i++)
        {  
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$edit_id)->get();
          
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
       
        return $j;
       
    }
    /*End*/
	
	/*Save Function*/
    public function save(Request $request)
    {  
	   
        $country = new country(); 
		date_default_timezone_set("Asia/Calcutta");
		
		$edit_id = $request->input('edit_id'); 
		//dd($_POST);
        if($edit_id == '')
        {
        $country->country_name=$_POST['country_name'];
        $country->last_updated_by = \Session::get('id');
        $country->location_id=\Session::get('loc_id');
        $country->organization_id=\Session::get('organization');
        $country->company_id=\Session::get('companyid');
		
        $country->save();
        $edit_id= DB::getPdo()->lastInsertId();
            $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"country",$action,$_POST,"m_countries_t"); 
			 return response()->json(array('status' => 'success', 'message' => 'Country Saved Successfully','id'=>$edit_id));
          //return $status;
        }
        else
        { 
            country::find($edit_id)->update($_POST);
            $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"country",$action,$_POST,"m_countries_t");  
			 return response()->json(array('status' => 'success', 'message' => 'Country Updated Successfully','id'=>$edit_id,'csrf'=>'9YIoEPgs9Np9c8KVjcL879zlAc7dbFCFN1QgG7Ha'));
        }	
    }
    /*End*/

     /*Delete Function*/
    public function destroy($del_id)
    {
       // dd($del_id);
        $column = array('country_id','country_name','active');
        $table = array('m_countries_t');
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
       
        if($j==1)
        {
             $query = \DB::table('m_countries_t')->where('country_id',$del_id)->delete();
              /**Auditlog**/
             $this->auditlog($del_id,"country_id","delete","","m_countries_t");
        }
        return $j;
     
    }
    /*End*/
	
    
    
	
	

   



}
