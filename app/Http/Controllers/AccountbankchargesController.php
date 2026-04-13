<?php

namespace App\Http\Controllers;

use App\Accountbankcharges;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;


class AccountbankchargesController extends Controller
{
	
   public function __construct(){
            $this->data=array();
             $this->table="f_bank_account_hdr_t";
		$this->pageModule="accountbankcharges";
		$this->model=new Accountbankcharges;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageFormtype']='ajax';
		$this->data=array(
                    'pageModule'=> 'accountbankcharges',
                    'pageUrl'	=>  url('accountbankcharges')
                  );
                $this->data['urlmenu']=$this->indexs(); 
		$this->data['pageMethod']=\Request::route()->getName();
	}
    
	
/*Vj purpose for display account Bankcharges data in JQgrid*/
 public function getAccountbankchargesData(){
	 
		$wh='';
	    $org=\Session::get('organization');
		$loc=\Session::get('location');
		$compy=\Session::get('companyid');
	    $wh.='and f_account_bankcharges_t.company_id='.$compy;

         $SQL = "SELECT 
                        f_account_bankcharges_t.account_bankcharge_id,
                        f_account_bankcharges_t.bank_id,
                        f_bank_account_hdr_t.bank_name,
                        f_account_bankcharges_t.payment_type_id,
                        f_account_bankcharges_t.from_value,
                        f_account_bankcharges_t.to_value,
                        f_account_bankcharges_t.bank_charges,
                        f_account_bankcharges_t.active,
                        f_account_bankcharges_t.created_at,
			tb_users.username
                        FROM f_account_bankcharges_t 
                        left join f_bank_account_hdr_t on (f_bank_account_hdr_t.bank_account_hdr_id=f_account_bankcharges_t.bank_id) and f_bank_account_hdr_t.bank_source = 'Company Account'
			left join tb_users on (tb_users.id=f_account_bankcharges_t.created_by)
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

        $acc_bankcharge=\DB::connection()->getSchemaBuilder()->getColumnListing('f_account_bankcharges_t');
	$acc_bankcharges=(object)array();
                
	foreach($acc_bankcharge as $key=>$value){
		$acc_bankcharges->$value="";
	}
     
	$this->data['row']=$acc_bankcharges;
    
    $f_currency="select bank_account_hdr_id,bank_name from f_bank_account_hdr_t where active='Yes' and bank_source ='Company Account'";
        $f_result = \DB::select($f_currency);
        $from="";
        $f_currency='';
        $f_currency .="<option value=''>--Please Select--</option>";
    foreach ($f_result as $row => $value) {
        $f_currencyid=$value->bank_account_hdr_id;
      $f_currencycode=$value->bank_name;
       
        $f_currency .="<option value='$f_currencyid'>$f_currencycode</option>";  
    }
    
    $t_currency="select payment_method_id,payment_method_name from m_payment_methods_t where active='Yes'";
        $t_result = \DB::select($t_currency);
        $from="";
        $t_currency='';
        $t_currency .="<option value=''>--Please Select--</option>";
    foreach ($t_result as $row => $value) {
        $t_currencyid=$value->payment_method_id;
      $t_currencycode=$value->payment_method_name;
       if($t_currencycode == "NEFT"){
        $t_currency .="<option selected='selected' value='$t_currencycode'>$t_currencycode</option>";  
       }else{
        $t_currency .="<option value='$t_currencycode'>$t_currencycode</option>";   
       }
    }

    
    $this->data['bank_id']=$f_currency;
    $this->data['payment_type_id']=$t_currency;

         $this->data['fromcurrencyopt'] = $this->jqgridselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name');
         $this->data['tocurrencyopt'] = $this->jqgridselect('m_payment_methods_t', 'payment_method_name', 'payment_method_name');
         $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

         
		return view('accountbankcharges.form',$this->data);
		 
    }
  
  public function save(Request $request){
        $edit_id = $request->input('edit_id');
        if($edit_id == ''){

            $accountbankcharge = new Accountbankcharges();
            $accountbankcharge->bank_id=$_POST['bank_id'];
            $accountbankcharge->payment_type_id=$_POST['payment_type_id'];
            $accountbankcharge->bank_charges=$_POST['bank_charges'];
            $accountbankcharge->from_value=$_POST['from_value'];
            $accountbankcharge->to_value=$_POST['to_value'];
            $accountbankcharge->company_id=\Session::get('companyid');
            $accountbankcharge->location_id=\Session::get('location');
            $accountbankcharge->organization_id=\Session::get('organization');
            $accountbankcharge->created_by=\Session::get('id');
	        $accountbankcharge->last_updated_by=\Session::get('id');
            $accountbankcharge->active=$_POST['active'];
            $accountbankcharge->save();
        return response()->json(array('status' => 'success', 'message' => 'Account Bankcharges Saved Successfully!!','id'=>$edit_id));
	}
		else{
                    $edit_id=$_POST['edit_id'];
                    Accountbankcharges::find($edit_id)->update($_POST);
		    return response()->json(array('status' => 'success', 'message' => 'Account Bankcharges Updated Successfully!!','id'=>$edit_id));
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
             $query = \DB::table('f_account_bankcharges_t')->where('account_bankcharge_id',$del_id)->delete();
        }
		return $j;
     }	

}
