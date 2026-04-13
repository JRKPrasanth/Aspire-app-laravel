<?php

namespace App\Http\Controllers;

use App\Assetcategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AssetcategoryController extends Controller
{
    public function __construct()
	{
              $this->data=array(
              'pageModule'=> 'assetcategory',
			  'pageMethod'=> 'assetcategory',
              'pageUrl'	=>  url('assetcategory')
              );
              $this->data['urlmenu']=$this->indexs(); 
		$this->model=new Assetcategory();
		$this->data['pageFormtype']='ajax';	
	}
	
    /* purpose for display account class data in JQgrid*/
	 public function getAssetcategoryData(){
		 
		$wh='';
	 
	    $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
	    $wh.='and f_asset_category_t.company_id='.$compy;
	 

		$SQL = "SELECT  f_asset_category_t.asset_category_id,f_asset_category_t.asset_category_name,f_asset_category_t.description,f_asset_category_t.active,f_asset_types_t.asset_type_id,f_asset_types_t.asset_type_name,tb_users.first_name FROM f_asset_category_t left join f_asset_types_t on (f_asset_category_t.asset_type_id=f_asset_types_t.asset_type_id) left join tb_users on (tb_users.id =f_asset_category_t.created_by) where 1=1 $wh  ORDER BY f_asset_category_t.asset_category_id DESC";
	 
	 
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

           
        $acc_currncy=\DB::connection()->getSchemaBuilder()->getColumnListing('f_asset_category_t');
	$acc_currncys=(object)array();
                
	foreach($acc_currncy as $key=>$value){
		$acc_currncys->$value="";
	}
     
	$this->data['row']=$acc_currncys;
         $this->data['asset_type_id'] = $this->jCombo('f_asset_types_t', 'asset_type_id','asset_type_name', '');
         $this->data['opt'] = $this->jqgridselect('f_asset_types_t','asset_type_id','asset_type_name');
		 $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));    
		return view('assetcategory.form',$this->data);
		   
	}
public function save(Request $request){
            
        $edit_id = $request->input('edit_id');
        if($edit_id == ''){
          $assetcategory = new Assetcategory();
	  $assetcategory->asset_category_name=$_POST['asset_category_name'];
          $assetcategory->description=$_POST['description'];
          $assetcategory->asset_type_id=$_POST['asset_type_id'];
          $assetcategory->company_id=\Session::get('companyid');
          $assetcategory->location_id=\Session::get('location');
          $assetcategory->organization_id=\Session::get('organization');
          $assetcategory->active=$_POST['active'];
          $assetcategory->created_by=\Session::get('id');
          $assetcategory->last_updated_by=\Session::get('id');
            $assetcategory->save();
            return response()->json(array('status' => 'success', 'message' => 'Asset Category Saved Successfully!!','id'=>$edit_id));
	}
        else{
            $edit_id=$_POST['edit_id'];
            Assetcategory::find($edit_id)->update($_POST);
            return response()->json(array('status' => 'success', 'message' => 'Asset Category Updated Successfully!!','id'=>$edit_id));
        }
    }
      /*Karthigaa purpose for check Duplicate function*/
public function getCheckname(Request $request){
        $edit_id = $_GET['edit_id'];
        if($edit_id == '')
            $acc_class=\DB::table('f_asset_category_t')->where('asset_category_name',$_GET['asset_category_name'])->get();
        else
        {
            $whereData = [['asset_category_name', $_GET['asset_category_name']],['asset_category_id', '!=', $edit_id]];
            $acc_class=\DB::table('f_asset_category_t')->where($whereData)->get();
        }
        if(count($acc_class)>0)
            return 1;
        else
            return 0;
    }
    
        
}
