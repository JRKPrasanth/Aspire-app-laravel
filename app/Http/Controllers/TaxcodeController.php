<?php

namespace App\Http\Controllers;

use App\Taxcode;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class TaxcodeController extends Controller
{
    public function __construct()
	{
            $this->data=array(
            'pageModule'=> 'Taxcode',
            'pageUrl'	=>  url('taxcode')
            );
            $this->data['urlmenu']=$this->indexs(); 
            $this->model=new Taxcode();
            $this->data['pageFormtype']='ajax';	
            $this->data['pageMethod']=\Request::route()->getName();
	}
 
	
	 public function getTaxcodeData(){
		$wh='';

         $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
	    $wh.='and f_tax_code_t.company_id='.$compy;
        
		$SQL = "SELECT 
                        f_tax_code_t.tax_code_id,
                        f_tax_code_t.tax_code_name,
                        f_tax_code_t.description,
                        f_tax_code_t.tax_code_percent,
                        f_tax_code_t.tax_category_id,
                        f_tax_category_t.tax_category_name,
                        f_tax_code_t.active,
                        tb_users.first_name
                         FROM f_tax_code_t left join f_tax_category_t on f_tax_code_t.tax_category_id=f_tax_category_t.tax_category_id left join tb_users on (tb_users.id =f_tax_code_t.created_by) where 1=1 $wh";

                
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

       
        $taxcode=\DB::connection()->getSchemaBuilder()->getColumnListing('f_tax_code_t');
	$taxcodes=(object)array();
        foreach($taxcode as $key=>$value){
                 $taxcodes->$value="";
         }
     $this->data['row']=$taxcodes;
     
     $this->data['tax_category_id'] = $this->jCombo('f_tax_category_t','tax_category_id','tax_category_name',$taxcodes->tax_category_id);
     $this->data['taxlocopt'] = $this->jqgridselect('f_tax_category_t','tax_category_id','tax_category_name');
     $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
    return view('taxcode.form',$this->data);
	   
}
        
     	public function save(Request $request){
			
        $edit_id = $request->input('edit_id');
        if($edit_id == ''){
	 $accountclass = new Taxcode();
	 $accountclass->tax_code_name=$_POST['tax_code_name'];
         $accountclass->tax_category_id=$_POST['tax_category_id'];
         $accountclass->description=$_POST['description'];
         $accountclass->tax_code_percent=$_POST['tax_code_percent'];
         $accountclass->active=$_POST['active'];
         $accountclass->company_id=\Session::get('companyid');
         $accountclass->location_id=\Session::get('location');
         $accountclass->organization_id=\Session::get('organization');
         $accountclass->created_by=\Session::get('id');
         $accountclass->last_updated_by=\Session::get('id');
	 $accountclass->save();
         return response()->json(array('status' => 'success', 'message' => 'Tax Code Saved Successfully','id'=>$edit_id));
	 }
        else{
        $edit_id=$_POST['edit_id'];
        Taxcode::find($edit_id)->update($_POST);
        return response()->json(array('status' => 'success', 'message' => 'Tax Code Updated Successfully','id'=>$edit_id));
        }
    }
/*Karthigaa purpose for check Duplicate function*/
public function getCheckname(Request $request){
        $edit_id = $_GET['edit_id'];
        if($edit_id == '')
            $tax_code=\DB::table('f_tax_code_t')->where('tax_code_name',$_GET['tax_code_name'])->get();
        else
        {
            $whereData = [['tax_code_name', $_GET['tax_code_name']],['tax_code_id', '!=', $edit_id]];
            $tax_code=\DB::table('f_tax_code_t')->where($whereData)->get();
        }
        if(count($tax_code)>0)
            return 1;
        else
            return 0;
    }
    /*Karthigaa purpose for delete function*/
  public function delete($del_id){
        $column = array('tax_code_name');
        $table = array('m_tax_group_lines_t');
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
             $query = \DB::table('f_tax_code_t')->where('tax_code_id',$del_id)->delete();
        }
		return $j;
     }	

}
