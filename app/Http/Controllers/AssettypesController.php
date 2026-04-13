<?php

namespace App\Http\Controllers;

use App\assettypes;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AssettypesController extends Controller
{
     public function __construct()
	{
              $this->data=array(
              'pageModule'=> 'assettypes',
				  'pageMethod'=> 'assettypes',
              'pageUrl'	=>  url('assettypes')
              );
              $this->data['urlmenu']=$this->indexs(); 
		$this->model=new assettypes();
		$this->data['pageFormtype']='ajax';	
	}
 
        /* purpose for display account class data in JQgrid*/
	public function getAssettypesData(){
		$wh='';

	    $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
	    $wh.='and f_asset_types_t.company_id='.$compy;

		$SQL = "SELECT f_asset_types_t.*,tb_users.first_name FROM f_asset_types_t left join tb_users on (tb_users.id =f_asset_types_t.created_by) where 1=1 $wh  ORDER BY asset_type_id DESC";

		$result = \DB::select( $SQL );

		return DataTables::of($result)->make(true);
	}
        
    public function create(Request $request,$id=null){
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

        $acc_currncy=\DB::connection()->getSchemaBuilder()->getColumnListing('f_asset_types_t');
		$acc_currncys=(object)array();
                
	foreach($acc_currncy as $key=>$value){
		$acc_currncys->$value="";
	}
     
	$this->data['row']=$acc_currncys;
     $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));    
	return view('assettypes.form',$this->data);
		
	}
        
           	public function save(Request $request){
            
        $edit_id = $request->input('edit_id');
        if($edit_id == ''){
         $assettypes = new assettypes();
	  $assettypes->asset_type_name=$_POST['asset_type_name'];
         $assettypes->description=$_POST['description'];
          $assettypes->company_id=\Session::get('companyid');
          $assettypes->location_id=\Session::get('location');
          $assettypes->organization_id=\Session::get('organization');
		  $assettypes->created_by=\Session::get('id');
		  $assettypes->last_updated_by=\Session::get('id');
          $assettypes->active=$_POST['active'];
		 $assettypes->save();
        	return response()->json(array('status' => 'success', 'message' => 'Asset Types Saved Successfully!!','id'=>$edit_id));
	}
        else{
                $edit_id=$_POST['edit_id'];
               //dd($_POST);
                assettypes::find($edit_id)->update($_POST);
                return response()->json(array('status' => 'success', 'message' => 'Asset Types Updated Successfully!!','id'=>$edit_id));
        }
    }
    
     /*Karthigaa purpose for check Duplicate function*/
public function getCheckname(Request $request){
  
        $edit_id = $_GET['edit_id'];
     
        if($edit_id == '')
            $asset_type=\DB::table('f_asset_types_t')->where('asset_type_name',$_GET['asset_type_name'])->get();
       
        else
        {
            $whereData = [['asset_type_name', $_GET['asset_type_name']],['asset_type_id', '!=', $edit_id]];
           // dd($whereData);
            $asset_type=\DB::table('f_asset_types_t')->where($whereData)->get();
            //dd($asset_type);
        }
       // dd($asset_type);
        if(count($asset_type)>0)
            return 1;
        else
            return 0;
    }
    /*Karthigaa purpose for delete function*/
  public function delete($del_id){
        $column = array('asset_type_id');
        $table = array('f_depreciation_method_t');
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$del_id)->get();
//            dd($query);
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
       
        if($j==0)
        {
             $query = \DB::table('f_asset_types_t')->where('asset_type_id',$del_id)->delete();
        }
		return $j;
     }	

 
}
