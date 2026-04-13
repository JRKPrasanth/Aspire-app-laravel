<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\customertypes;
use Yajra\DataTables\DataTables;

class CustomertypesController extends Controller
{

	public function __construct()
	{
			$this->data['pageFormtype']='ajax';
			$this->data=array();
			$this->data['urlmenu']=$this->indexs(); 
			$this->data['pageMethod']=\Request::route()->getName();
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

	$table = \DB::table('m_customer_types_t')->get();
	$this->data['account_structure']=$this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');

	$this->data['datas'] = $table;
	$this->data['pageMethod']="mcustomertypes";
	 $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
	return view('mcustomertypes.table',$this->data);
		
	}
	
   	public function getmcustomertypesData()
   	{

		$wh='';
                   
	   $com=\Session::get('companyid');
	
		$SQL = "SELECT m_customer_types_t.*,tb_users.id as created_id,tb_users.first_name,f_account_structure_t.concatenated_segments FROM m_customer_types_t left join tb_users on m_customer_types_t.created_by=tb_users.id  left join f_account_structure_t on f_account_structure_t.f_account_structure_id=m_customer_types_t.account_id where 1=1 and m_customer_types_t.company_id=$com  $wh ";

		$result = \DB::select( $SQL );

		return DataTables::of($result)->make(true);
		
	}
	
	public function create($id=null)
	{

	$Mcustomertypes=customertypes::find($id);
	$this->data['row']= (object) array();
	$this->data['row']->customer_type = "";
	$this->data['row']->description = "";
	return view('mcustomertypes.form',$this->data);
	}
	public function save(Request $request)
	{
   		   $customer_type_id = $request->input('customer_type_id');
        if($customer_type_id == '')
        {
			$mcustomertypes=new customertypes();
			$mcustomertypes->customer_type=$_POST['customer_type'];
			$mcustomertypes->gst_required=$_POST['gst_required'];
			$mcustomertypes->description=$_POST['description'];
			$mcustomertypes->active=$_POST['active'];
			$mcustomertypes->account_id=$_POST['account_id'];
			$mcustomertypes->company_id=\Session::get('companyid');
			$mcustomertypes->location_id=\Session::get('location');
			$mcustomertypes->organization_id=\Session::get('organization');
			$mcustomertypes->created_by=$_POST['created_by'];
			$mcustomertypes->save();
			$action="Create";
			$customer_type_id= DB::getPdo()->lastInsertId();
			/**Auditlog**/
			$this->auditlog($customer_type_id,"customertypes",$action,$_POST,"m_customers_t");
			return response()->json(array('status' => 'success', 'message' => 'Customer Type Saved Successfully!!','id'=>$customer_type_id));
		}
		else
		{
			$customer_type_id=$_POST['customer_type_id'];
			customertypes::find($customer_type_id)->update($_POST);
			$action="Edit";
			/**Auditlog**/
			$this->auditlog($customer_type_id,"customertypes",$action,$_POST,"m_customers_t");
			return response()->json(array('status' => 'success', 'message' => 'Customer Type Saved Successfully!!','id'=>$customer_type_id));
		}
	
	}
	
	public function getedit($id)
    {
        $column = array('customer_type_id');
        $table = array('m_customers_t');
        for($i=0; $i<count($table); $i++)
        {  
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
          
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
       
		return $j;
       
    }

	public function edit(Request $request, $id=null)
	{
	$this->data['id'] = $id;

	$table = \DB::table('m_customer_types_t')->where('customer_type_id',$id)->get();
	$this->data['row'] = $table[0];

	return view('mcustomertypes.form',$this->data);
	}
 public function getRemove($id=null)
    {

		$column = array('customer_type_id');
        $table = array('m_customers_t');
        for($i=0; $i<count($table); $i++)
        {  
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
          
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }

        if($j==0)
        {
             $query = \DB::table('m_customer_types_t')->where('customer_type_id',$id)->delete();
        } 
        //dd($j);
			/**Auditlog**/
			$action = "Delete";
			$this->auditlog($id,"customertypes",$action,'',"m_customers_t");
		return $j;

	}

	public function show(customertypes $Mcustomertypes,$id=null)
	{
	$this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("m_customer_types_t");
	$this->data['values'] = customertypes::find($id);
	//dd($this->data);
	return view('mcustomertypes.view',$this->data);
	}



    


	 public function getShow(Request $request,$id=null)
    {
    	
    	$column = array('customer_type_id');
        $table = array('m_customers_t');
        for($i=0; $i<count($table); $i++)
        {  
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
          
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
       
		return $j;
       
    }

	public function getCheckname(Request $request)
	{

			$edit_id = $_GET['edit_id'];
			if($edit_id == '')
					$department=DB::table('m_customer_types_t')->where('customer_type',$_GET['customer_type'])->get();
			else
			{
					$whereData = [['customer_type', $_GET['customer_type']],['customer_type_id', '!=', $edit_id]];

					$department=DB::table('m_customer_types_t')->where($whereData)->get();
			}

			if(count($department)>0)
					return 1;
			else
					return 0;


	}


}
