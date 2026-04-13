<?php
namespace App\Http\Controllers;
use App\Paymentmethods;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;


class PaymentmethodsController extends Controller
{

 public function __construct()
    {
        $this->data=array();
        $this->model = new Paymentmethods();
        $this->data['pageFormtype']='ajax';
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlmenu']=$this->indexs(); 
        //dd($this->model);

    }

	
	public function index(Request $request){
	    
	// restrict illegal menu entry purpose - VIGNESH M

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

	    $this->data['urlname'] =\Request::route()->getName();
        $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
	    $table = \DB::table('m_payment_methods_t')->get();
       	$this->data['datas']=json_encode($table);
		
	    return view('paymentmethods.form',$this->data);
		
	    }


 
   public function getPaymentmethodsData($type=null){
   
	   $wh='';

                   $org=\Session::get('organization'); 
                  $com=\Session::get('companyid');
         

	   
    $SQL = "SELECT m_payment_methods_t.* ,tb_users.first_name,tb_users.employee_id,tb_users.id as created_id FROM m_payment_methods_t left join tb_users on tb_users.id =m_payment_methods_t.created_by where 1=1 and m_payment_methods_t.company_id=$com $wh order by m_payment_methods_t.payment_method_id DESC";


	   
              
    $result = \DB::select( $SQL );
return DataTables::of($result)->make(true);
	   
  }



 // Save Function
	
     public function save(Request $request){
        $payment_method_id = $request->input('payment_method_id');
        
          if($payment_method_id == '')
          {
                  $paymentmethods = new Paymentmethods();
                  //dd($paymentmethods);
                  $paymentmethods->organization_id=\Session::get('organization'); 
                  $paymentmethods->company_id=\Session::get('companyid');
                  $paymentmethods->location_id=\Session::get('location');
                  $paymentmethods->created_by=$_POST['created_by'];
                  $paymentmethods->payment_method_name=$_POST['payment_method_name'];
                  $paymentmethods->description=$_POST['description'];
                  $paymentmethods->active=$_POST['active'];

            $paymentmethods->save();
            $payment_method_id= \DB::getPdo()->lastInsertId();
            $action="Create";
            /**Auditlog**/
            $this->auditlog($payment_method_id,"paymentmethods",$action,$_POST,"m_payment_methods_t");
            return response()->json(array('status' => 'success', 'message' => 'Payment Methods Saved Successfully','id'=>$payment_method_id));
        }
        else
        {
            $action="Edit";
            $payment_method_id=$_POST['payment_method_id'];
            Paymentmethods::find($payment_method_id)->update($_POST);
            /**Auditlog**/
            $this->auditlog($payment_method_id,"paymentmethods",$action,$_POST,"m_payment_methods_t");
            return response()->json(array('status' => 'success', 'message' => 'Payment Methods Updated Successfully','id'=>$payment_method_id));
        }
        
  }
  /* end */

/*Karthigaa Purpose for Delete Function*/
    public function delete(request $request,$id=null)
    {
       $column = array('payment_method_id','default_payment_method_id','ar_payment_method_id','payment_method_id','default_payment_method_id','default_payment_method_id','default_payment_method_id');
         $table = array('s_quote_hdr_t','m_customers_t','s_salesorder_hdr_t','s_invoice_hdr_t','m_supplier_t','p_quotation_hdr_t','p_po_hdr_t');

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
             $query = \DB::table('m_payment_methods_t')->where('payment_method_id',$id)->delete();
        }
    /**Auditlog**/
    $action = "Delete";
    $this->auditlog($id,"paymentmethods",$action,$id,"m_payment_methods_t");
  	return $j;
    }


    /*Purpose for Used Data Should Not Allow to Edit Function*/
    public function getedit($edit_id,$pay_type)
    {   

         $column = array('payment_method_id','default_payment_method_id','ar_payment_method_id','payment_method_id','default_payment_method_id','default_payment_method_id','default_payment_method_id');
         $table = array('s_quote_hdr_t','m_customers_t','s_salesorder_hdr_t','s_invoice_hdr_t','m_supplier_t','p_quotation_hdr_t','p_po_hdr_t');

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
    /* end */

      

        /*Karthigaa Purpose for Duplicate Validation Function*/
	public function Checkname(Request $request){
            $payment_method_id = $_REQUEST['edit_id'];  
        if($payment_method_id == '')
        {
            $group=\DB::table('m_payment_methods_t')->where('payment_method_name',$_REQUEST['payment_method_name'])->get();
        }
        else
        {
            $whereData = [['payment_method_name', $_REQUEST['payment_method_name']],['payment_method_id', '!=', $payment_method_id]];
            $group=\DB::table('m_payment_methods_t')->where($whereData)->get();
        }
        if(count($group)>0)
            return 1;
        else
            return 0;
    }

    /* end */

}
