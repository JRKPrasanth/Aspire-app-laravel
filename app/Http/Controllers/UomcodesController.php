<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\uomcodes;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class UomcodesController extends Controller
{  
	public function __construct()
	{
	  $this->module=new Uomcodes();
	  $this->data['pageFormtype']='ajax';
	  $this->data['pageMethod']=\Request::route()->getName();
          $this->data['urlmenu']=$this->indexs(); 
	}
	
    public function getGridUomData()
    {

        $wh=''; 

        $comp=\Session::get('companyid');
        $loc=\Session::get('location');
        $groupname=\Session::get('groupname');
        if($groupname=="1" || $groupname=="Admin")
        {
            $wh.="and m_uom_codes_t.company_id=$comp";
        }
        else{
            $wh.="and m_uom_codes_t.company_id=$comp  and m_uom_codes_t.location_id=$loc";
        }

           
            $SQL = "SELECT m_uom_codes_t.uom_code_id,
            m_uom_codes_t.uom_code,
            m_uom_codes_t.code_meaning,
            m_uom_codes_t.active,
            tb_users.first_name
            FROM m_uom_codes_t left join `tb_users` on (tb_users.id=m_uom_codes_t.created_by) where 1=1 $wh";
		
            $result = \DB::select($SQL);

		   return DataTables::of($result)->make(true);
    }   
	


    /*Create Functon for Form*/
    public function create($id=null,$type=null)
    { 
         $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
      if(isset($id))
	  { 
		  $uomcodes =Uomcodes::find($id);
		  $this->data['row']=$uomcodes; 
		  if($type=='g')
			{
			  return $uomcodes;
		    }
	  }
	 else
	 {
	   $uomcodedata=\DB::connection()->getSchemaBuilder()->getColumnListing('m_uom_codes_t');
	   $uomcodesdatas=(object)array();
		  foreach($uomcodedata as $key=>$value)
			  {
		        $uomcodesdatas->$value="";
          	  }
		        $this->data['row']=$uomcodesdatas;
		 } 

         return view('uomcodes.form',$this->data);
    }


    /*Edit Function*/
    public function getedit($edit_id)
    {
       
        $column = array('primary_uom','trx_uom','primary_uom_id','trx_uom_id');
        $table = array('m_uom_conversion_t','m_uom_conversion_t','m_products_t','m_products_t');
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
        $uomcodsdtl = new Uomcodes(); 
		date_default_timezone_set("Asia/Calcutta");
		
		$edit_id = $request->input('edit_id'); 
		
        if($edit_id == '')
        {
        $uomcodsdtl->uom_code=$_POST['uom_code'];
        $uomcodsdtl->code_meaning=$_POST['code_meaning'];
        $uomcodsdtl->active=$_POST['active'];
        $uomcodsdtl->created_by=$_POST['created_by'];
        $uomcodsdtl->last_updated_by = \Session::get('id');
        $uomcodsdtl->location_id=\Session::get('location');
        $uomcodsdtl->organization_id=\Session::get('organization');
        $uomcodsdtl->company_id=\Session::get('companyid');
          $uomcodsdtl->save();
          $edit_id= DB::getPdo()->lastInsertId();
            $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"uomcodes",$action,$_POST,"m_uom_codes_t"); 
			 return response()->json(array('status' => 'success', 'message' => 'Uom Codes Saved Successfully','id'=>$edit_id));
          //return $status;
        }
        else
        { 
            Uomcodes::find($edit_id)->update($_POST);
            $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"uomcodes",$action,$_POST,"m_uom_codes_t");  
			 return response()->json(array('status' => 'success', 'message' => 'Uom Codes Updated Successfully','id'=>$edit_id,'csrf'=>'9YIoEPgs9Np9c8KVjcL879zlAc7dbFCFN1QgG7Ha'));
        }	
    }
    /*End*/

     /*Delete Function*/
    public function destroy($del_id)
    {
        
        $column = array('primary_uom','trx_uom','primary_uom_id','trx_uom_id');
        $table = array('m_uom_conversion_t','m_uom_conversion_t','m_products_t','m_products_t');
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
             $query = \DB::table('m_uom_codes_t')->where('uom_code_id',$del_id)->delete();
              /**Auditlog**/
             $this->auditlog($del_id,"uomcodes","delete","","m_uom_codes_t");
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
            $uom=\DB::table('m_uom_codes_t')->where('uom_code',$_REQUEST['uom_code'])->get();
        else
        {
            $whereData = [['uom_code', $_REQUEST['uom_code']],['uom_code_id', '!=', $edit_id]];
            
            $uom=\DB::table('m_uom_codes_t')->where($whereData)->get();
        }
        
        
        if(count($uom)>0)
            return 1;
        else
            return 0;
        
        
    }
	/*end*/

   



}
