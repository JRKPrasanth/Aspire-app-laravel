<?php

namespace App\Http\Controllers;

use App\Accountcurrency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class AccountcurrencyController extends Controller
{
   
        public function __construct(){
            $this->data=array();
             $this->table="f_account_currency_t";
		$this->pageModule="accountcurrency";
		$this->model=new Accountcurrency;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageFormtype']='ajax';
		$this->data=array(
                    'pageModule'=> 'accountcurrency',
                    'pageUrl'	=>  url('accountcurrency')
                  );
                $this->data['urlmenu']=$this->indexs(); 
		$this->data['pageMethod']=\Request::route()->getName();
	}
  
	 public function getAccountcurrencyData(){
		$wh='';

	 
	    $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
	    $wh.='and f_account_currency_t.company_id='.$compy;

		$SQL = "SELECT 
                        f_account_currency_t.*,
                        tb_users.first_name 
                        FROM 
                        f_account_currency_t 
                        left join tb_users on tb_users.id =f_account_currency_t.created_by 
                        where 1=1 $wh";

	 	
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

        $acc_currncy=\DB::connection()->getSchemaBuilder()->getColumnListing('f_account_currency_t');
	$acc_currncys=(object)array();
	foreach($acc_currncy as $key=>$value){
		$acc_currncys->$value="";
	}
     
	$this->data['row']=$acc_currncys;
        $user=\Session::get('id');
         $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $user);
	return view('accountcurrency.form',$this->data);
	}
 

    
    	public function save(Request $request){
//             dd($_POST);
        $edit_id = $request->input('edit_id');
        if($edit_id == ''){
          
	  $accountcurrency = new Accountcurrency();
	  $accountcurrency->currency_code=$_POST['currency_code'];
          $accountcurrency->company_id=\Session::get('companyid');
          $accountcurrency->location_id=\Session::get('location');
          $accountcurrency->organization_id=\Session::get('organization');
          $accountcurrency->active=$_POST['active'];
         $accountcurrency->created_by=\Session::get('id');
          $accountcurrency->last_updated_by=\Session::get('id');
             
         
          $accountcurrency->save();
          
          return response()->json(array('status' => 'success', 'message' => 'Account Currency Saved Successfully!!','id'=>$edit_id));
	}
        else{
                $edit_id=$_POST['edit_id'];

                Accountcurrency::find($edit_id)->update($_POST);
                return response()->json(array('status' => 'success', 'message' => 'Account Currency Updated Successfully!!','id'=>$edit_id));
        }
    }
 /*Karthigaa purpose for check Duplicate function*/
public function getCheckname(Request $request){
        
    $edit_id = $_GET['edit_id'];
        
        if($edit_id == '')
            $acc_class=\DB::table('f_account_currency_t')->where('currency_code',$_GET['currency_code'])->get();
        else
        {
            $whereData = [['currency_code', $_GET['currency_code']],['account_currency_id', '!=', $edit_id]];
            $acc_class=\DB::table('f_account_currency_t')->where($whereData)->get();
        }
        if(count($acc_class)>0)
            return 1;
        else
            return 0;
    }
    /*Karthigaa purpose for delete function*/
  public function delete($del_id){
        $column = array('from_currency_id');
        $table = array('f_account_exchangerates_t');
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
             $query = \DB::table('f_account_currency_t')->where('account_currency_id',$del_id)->delete();
        }
		return $j;
     }	

  
}
