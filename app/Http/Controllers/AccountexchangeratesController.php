<?php

namespace App\Http\Controllers;

use App\Accountexchangerates;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;


class AccountexchangeratesController extends Controller
{
   public function __construct(){
            $this->data=array();
             $this->table="f_account_currency_t";
		$this->pageModule="accountexchangerates";
		$this->model=new Accountexchangerates;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageFormtype']='ajax';
		$this->data=array(
                    'pageModule'=> 'accountexchangerates',
                    'pageUrl'	=>  url('accountexchangerates')
                  );
                $this->data['urlmenu']=$this->indexs(); 
		$this->data['pageMethod']=\Request::route()->getName();
	}
    
/*Karthigaa purpose for display account Exchangerates data in JQgrid*/
	 public function getAccountexchangeratesData(){
		$wh='';
	 
	   $org=\Session::get('organization');
		$loc=\Session::get('location');
		$compy=\Session::get('companyid');
	    $wh.='and f_account_exchangerates_t.company_id='.$compy;
	 
         $SQL = "SELECT 
                        f_account_exchangerates_t.account_exchangerate_id,
                        f_account_exchangerates_t.from_currency_id,
                        f_account_currency_t.currency_code as from_currency_code,
                        f_account_exchangerates_t.to_currency_id,
                        tocurrency.currency_code as to_currency_code,
                        f_account_exchangerates_t.from_date,
                        f_account_exchangerates_t.to_date,
                        f_account_exchangerates_t.conversion_rate,
                        f_account_exchangerates_t.active,
                        f_account_exchangerates_t.created_at,
						tb_users.first_name
                        FROM f_account_exchangerates_t 
                        left join f_account_currency_t on (f_account_currency_t.account_currency_id=f_account_exchangerates_t.from_currency_id)
                        left join f_account_currency_t tocurrency on (tocurrency.account_currency_id=f_account_exchangerates_t.to_currency_id)
			left join tb_users on (tb_users.id=f_account_exchangerates_t.created_by)
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

        $acc_exchge=\DB::connection()->getSchemaBuilder()->getColumnListing('f_account_exchangerates_t');
	$acc_exchages=(object)array();
                
	foreach($acc_exchge as $key=>$value){
		$acc_exchages->$value="";
	}
     
	$this->data['row']=$acc_exchages;

    
    $f_currency="select account_currency_id,currency_code,currency_symbol from f_account_currency_t where active='Yes' and currency_code NOT IN ('INR')";
        $f_result = \DB::select($f_currency);
        $from="";
        $f_currency='';
        $f_currency .="<option value=''>--Please Select--</option>";
    foreach ($f_result as $row => $value) {
        $f_currencyid=$value->account_currency_id;
      $f_currencycode=$value->currency_code;
      $f_currencysymbol=$value->currency_symbol;
       
        $f_currency .="<option value='$f_currencyid'>$f_currencysymbol-$f_currencycode</option>";  
    }
    
    $t_currency="select account_currency_id,currency_code,currency_symbol from f_account_currency_t where active='Yes' and currency_code IN ('INR')";
        $t_result = \DB::select($t_currency);
        $from="";
        $t_currency='';
        $t_currency .="<option value=''>--Please Select--</option>";
    foreach ($t_result as $row => $value) {
        $t_currencyid=$value->account_currency_id;
      $t_currencycode=$value->currency_code;
      $t_currencysymbol=$value->currency_symbol;
       
        $t_currency .="<option value='$t_currencyid'>$t_currencysymbol-$t_currencycode</option>";  
    }
    

    
		$this->data['from_currency_id']=$f_currency;
		$this->data['to_currency_id']=$t_currency;

         $this->data['fromcurrencyopt'] = $this->jqgridselect('f_account_currency_t', 'account_currency_id', 'currency_code');
         $this->data['tocurrencyopt'] = $this->jqgridselect('f_account_currency_t', 'account_currency_id', 'currency_code');
         $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
         
		return view('accountexchangerates.form',$this->data);
    }
  
  public function save(Request $request){
        $edit_id = $request->input('edit_id');
        if($edit_id == ''){
          $str_date = $_POST['from_date'];
          $to_date = $_POST['to_date'];

            $accountexchange = new Accountexchangerates();
            $accountexchange->from_currency_id=$_POST['from_currency_id'];
            $accountexchange->to_currency_id=$_POST['to_currency_id'];
            $accountexchange->conversion_rate=$_POST['conversion_rate'];
            $accountexchange->from_date=date("Y-m-d", strtotime($str_date));
            $accountexchange->to_date=date("Y-m-d", strtotime($to_date));
            $accountexchange->company_id=\Session::get('companyid');
            $accountexchange->location_id=\Session::get('location');
            $accountexchange->organization_id=\Session::get('organization');
            $accountexchange->created_by=\Session::get('id');
	    $accountexchange->last_updated_by=\Session::get('id');
            $accountexchange->active=$_POST['active'];
            $accountexchange->save();
        return response()->json(array('status' => 'success', 'message' => 'Account Exchangerates Saved Successfully!!','id'=>$edit_id));
	}
		else{
                    $edit_id=$_POST['edit_id'];
                    $str_date = $_POST['from_date'];
                    $to_date = $_POST['to_date'];
                    $_POST['from_date'] = date("Y-m-d", strtotime($str_date));
                    $_POST['to_date'] = date("Y-m-d", strtotime($to_date));
                    Accountexchangerates::find($edit_id)->update($_POST);
		    return response()->json(array('status' => 'success', 'message' => 'Account Exchangerates Updated Successfully!!','id'=>$edit_id));
		}
    }

    /*Karthigaa purpose for delete function*/
  public function delete($del_id){
        $column = array('account_currency_id');
        $table = array('f_account_currency_t');
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
             $query = \DB::table('f_account_exchangerates_t')->where('account_exchangerate_id',$del_id)->delete();
        }
		return $j;
     }	

}
