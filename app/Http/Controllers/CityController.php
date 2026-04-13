<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\state;
use App\country;
use App\city;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class CityController extends Controller
{
public function __construct()
	{
	    $this->data=array(
         'pageModule'=> 'city',
         'pageUrl'	=>  url('city')
          );
                $this->data['urlmenu']=$this->indexs(); 
		$this->model=new city();
	    $this->data['pageFormtype']='ajax';
		$this->data['pageMethod']='city';
		$this->data['pageModule']='city';
	}
	/*Jqgrid Data for Loading datas in tables*/
public function getcityData(Request $request)
{
    if ($request->ajax()) {
        $query = \DB::table('m_cities_t')
            ->leftJoin('m_countries_t', 'm_countries_t.country_id', '=', 'm_cities_t.country_id')
            ->leftJoin('m_states_t', 'm_states_t.state_id', '=', 'm_cities_t.state_id')
            ->select(
                'm_cities_t.*',
                'm_countries_t.country_name','m_states_t.state_name'
            );

        return DataTables::of($query)->make(true);
    }
}
/*End*/

    /*Create Functon for Form*/
    public function create($id=null,$type=null)
    { 
		
         $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
      if(isset($id))
	  { 
		
		  $state =city::find($id);
		  
		  $this->data['row']=$state;            
		  $this->data['country_id']=$this->jCombologin("m_countries_t","country_id","country_name","");
	      $this->data['state_id']=$this->jCombologin("m_states_t","state_id","state_name","");	
		  
	  }
	 else
	 {
	   $city=\DB::connection()->getSchemaBuilder()->getColumnListing('m_cities_t');
    
	   $cities=(object)array();
      
		  foreach($city as $key=>$value)
			  {
		        $cities->$value="";
          	  }
		        $this->data['row']=$cities;
		 
	 $this->data['country_id']=$this->jCombologin("m_countries_t","country_id","country_name","");
	  $this->data['state_id']=$this->jCombologin("m_states_t","state_id","state_name","");	
         return view('city.form',$this->data);
    }
	}
	/*End*/

   
	
	/*Save Function*/
	 public function save(Request $request)
    {
        // dd($_POST);
	  $city=new city();
	  $edit_id = $request->input('edit_id');
		 
        if($edit_id == '')
        {
			
			$city->country_id = $_POST['country_id'];
            $city->state_id = $_POST['state_id'];
            $city->city_name =  $_POST['city_name'];
            
            
            $city->company_id = \Session::get('companyid');
            $city->location_id = \Session::get('loc_id');
            $city->save();
            $edit_id= DB::getPdo()->lastInsertId();
            $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"city_id",$action,$_POST,"m_cities_t");
           return response()->json(array('status' => 'success', 'message' => 'City Saved Successfully','id'=>$edit_id));
		  }
       else
        { 
		  
            city::find($edit_id)->update($_POST);
		  
            $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"city_id",$action,$_POST,"m_cities_t");  
			 return response()->json(array('status' => 'success', 'message' => 'City Updated Successfully','id'=>$edit_id,'csrf'=>'9YIoEPgs9Np9c8KVjcL879zlAc7dbFCFN1QgG7Ha'));
        }	
    }
    /*End*/

     /*Delete Function*/
    public function destroy($del_id)
    {
        $column = array('country_id','state_id','city_name');
        $table = array('m_cities_t');
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
             $query = \DB::table('m_cities_t')->where('city_id',$del_id)->delete();
              /**Auditlog**/
             $this->auditlog($del_id,"city_id","delete","","m_cities_t");
        }
		
        return $j;
     
    }
    /*End*/


}
