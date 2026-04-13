<?php

namespace App\Http\Controllers;

use App\Tdsslab;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TdsslabController extends Controller
{
    public function __construct()
	{
              $this->data=array(
              'pageModule'=> 'Tdsslab',
              'pageUrl'	=>  url('tdsslab')
              );
              $this->data['urlmenu']=$this->indexs(); 
		$this->model=new Tdsslab();
		$this->data['pageFormtype']='ajax';	
                $this->data['pageMethod']=\Request::route()->getname();
	}
	

		 public function getTdsslabData(){
			 
		$wh='';
	    $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
	    $wh.='and f_tds_slab_t.company_id='.$compy;
	

		$SQL = "SELECT  f_tds_slab_t.*,tb_users.first_name FROM f_tds_slab_t left join tb_users on (tb_users.id =f_tds_slab_t.created_by) where 1=1 $wh";

	 
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

        $tdsslab=\DB::connection()->getSchemaBuilder()->getColumnListing('f_tds_slab_t');
	$tdsslabs=(object)array();
                
	foreach($tdsslab as $key=>$value){
		$tdsslabs->$value="";
	}
     
	$this->data['row']=$tdsslabs;
        $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
       
	return view('tdsslab.form',$this->data);
			  
	}
        public function save(Request $request){
            
        $edit_id = $request->input('edit_id');
        if($edit_id == ''){
                      $str_date = $_POST['start_date'];
                      $end_date = $_POST['end_date'];

	  $tdsslab = new Tdsslab();
	  $tdsslab->tds_percentage=$_POST['tds_percentage'];
          $tdsslab->tds_type=$_POST['tds_type'];
          $tdsslab->start_date=date("Y-m-d", strtotime($str_date));
          $tdsslab->end_date=date("Y-m-d", strtotime($end_date));
          $tdsslab->company_id=\Session::get('companyid');
          $tdsslab->location_id=\Session::get('location');
          $tdsslab->organization_id=\Session::get('organization');
          $tdsslab->created_by=\Session::get('id');
          $tdsslab->active=$_POST['active'];
         // dd($_POST);
		 $tdsslab->save();
                 
        	return response()->json(array('status' => 'success', 'message' => 'TDS Saved Successfully!!','id'=>$edit_id));
	}
        else{
                $edit_id=$_POST['edit_id'];
                $str_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
                $_POST['start_date'] = date("Y-m-d", strtotime($str_date));
                $_POST['end_date'] = date("Y-m-d", strtotime($end_date));

                Tdsslab::find($edit_id)->update($_POST);
                return response()->json(array('status' => 'success', 'message' => 'TDS Updated Successfully!!','id'=>$edit_id));
        }
    }
    /*Karthigaa purpose for check Duplicate function*/
public function getCheckname(Request $request){
        $edit_id = $_GET['edit_id'];
        if($edit_id == '')
            $acc_class=\DB::table('f_tds_slab_t')->where('tds_percentage',$_GET['tds_percentage'])->get();
        else
        {
            $whereData = [['tds_percentage', $_GET['tds_percentage']],['tds_slab_id', '!=', $edit_id]];
            $acc_class=\DB::table('f_tds_slab_t')->where($whereData)->get();
        }
        if(count($acc_class)>0)
            return 1;
        else
            return 0;
    }
     /*Karthigaa purpose for delete function*/
  public function delete($del_id){
        $column = array('tds_percentage');
        $table = array('m_supplier_t');
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
             $query = \DB::table('f_tds_slab_t')->where('tds_slab_id',$del_id)->delete();
        }
		return $j;
     }
 public function getedit($edit_id)
  {
 
        $column = array('tds_percentage');
        $table = array('m_supplier_t');
       
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
}
