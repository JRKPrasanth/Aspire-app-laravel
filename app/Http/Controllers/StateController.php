<?php
namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\state;
use App\country;
use App\Http\Controllers\Controller;
use DB;

class StateController extends Controller
{
public function __construct()
	{
	    $this->data=array(
         'pageModule'=> 'state',
         'pageUrl'	=>  url('state')
          );
                $this->data['urlmenu']=$this->indexs(); 
		$this->model=new state();
	    $this->data['pageFormtype']='ajax';
		$this->data['pageMethod']='state';
		$this->data['pageModule']='state';
	}
	/*Jqgrid Data for Loading datas in tables*/
 public function getstatedata(Request $request)
{
    if ($request->ajax()) {
    $query = \DB::table('m_states_t')
        ->leftJoin('m_countries_t', 'm_countries_t.country_id', '=', 'm_states_t.country_id')
        ->select(
            'm_countries_t.country_id',
            'm_countries_t.country_name',
            'm_states_t.state_id',
            'm_states_t.state_name',
            'm_states_t.state_code'
        );

        return DataTables::of($query)->make(true);
    }
}

    /*Create Functon for Form*/
    public function create($id=null,$type=null)
    { 
		
         $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
      if(isset($id))
	  { 
		
		  $state =state::find($id);
		  
		  $this->data['row']=$state;            
		  $this->data['country_id']=$this->jCombologin("m_countries_t","country_id","country_name",$state);
		  
		  if($type=='g')
			{
			  return $uomcodes;
		    }
	  }
	 else
	 {
	   $state=\DB::connection()->getSchemaBuilder()->getColumnListing('m_states_t');
	   $states=(object)array();
		  foreach($state as $key=>$value)
			  {
		        $states->$value="";
          	  }
		        $this->data['row']=$states;
		 } 
	 $this->data['country_id']=$this->jCombologin("m_countries_t","country_id","country_name","");
		
         return view('state.form',$this->data);
    }
	/*End*/

    /*Edit Function*/
    public function getedit($edit_id)
    {
       
        $column = array('state_name','country_id','state_id');
        //dd($column);
        $table = array('m_states_t');
      // dd(count($table));
        for($i=0; $i<count($table); $i++)
        {  
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$edit_id)->get();
          //dd($query);
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
        //dd($_POST);
	  $state=new state();
	  $edit_id = $request->input('edit_id');
		 
        if($edit_id == '')
        {
			
			$state->state_name = $_POST['state_name'];
            $state->country_id = $_POST['country_id'];
            $state->state_code =  $_POST['state_code'];
            
            
            $state->company_id = \Session::get('companyid');
            $state->location_id = \Session::get('loc_id');
            $state->save();
            $edit_id= DB::getPdo()->lastInsertId();
            $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"state_id",$action,$_POST,"m_states_t");
           return response()->json(array('status' => 'success', 'message' => 'State Saved Successfully','id'=>$edit_id));
		  }
       else
        { 
            state::find($edit_id)->update($_POST);
		  
            $action="Edit";
            $state->state_name = $_POST['state_name'];
            $state->country_id = $_POST['country_id'];
            $state->state_code =  $_POST['state_code'];
            
            
            $state->company_id = \Session::get('companyid');
            $state->location_id = \Session::get('location');
            /**Auditlog**/
            $this->auditlog($edit_id,"state_id",$action,$_POST,"m_states_t");  
			 return response()->json(array('status' => 'success', 'message' => 'State Updated Successfully','id'=>$edit_id,'csrf'=>'9YIoEPgs9Np9c8KVjcL879zlAc7dbFCFN1QgG7Ha'));
        }	
    }
    /*End*/

     /*Delete Function*/
    public function destroy($del_id)
    {
       // dd($del_id);
        $column = array('country_id','state_nmae','state_id');
        $table = array('m_states_t');
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
             $query = \DB::table('m_states_t')->where('state_id',$del_id)->delete();
              /**Auditlog**/
             $this->auditlog($del_id,"state_id","delete","","m_states_t");
        }
		
        return $j;
     
    }
    /*End*/
	
    /*View Function*/
    public function view($id=null)
    {
        if(isset($id))
        {
            $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing('m_uom_codes_t');
            $this->data['values']=Uomcodes::find($id);
            return view('uomcodes.view',$this->data);
        } 

    }
	/*End*/
    
	
	/* deepika :purpose for check duplicate entry*/	
	public function getCheckname(Request $request)
    {
       
        $edit_id = $_REQUEST['edit_id'];
		
        if($edit_id == '')
            $uom=\DB::table('m_states_t')->where('country_id',$_REQUEST['country_id'])->get();
        else
        {
            $whereData = [['state_name', $_REQUEST['state_name']],['country_id', '!=', $edit_id]];
            
            $uom=\DB::table('m_states_t')->where($whereData)->get();
        }
        
        
        if(count($uom)>0)
            return 1;
        else
            return 0;
        
        
    }
	/*end*/

   



}
