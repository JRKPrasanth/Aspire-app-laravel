<?php 
namespace App\Http\Controllers;
use App\Helpers\BoilerHelper;

use App\Http\Controllers\Controller;
use App\User;
use App\jobcard;
use Socialize;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use DB;
use Excel;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Worksheet_Drawing;


class APIController extends Controller {

	private $helperService;

	public function __construct(BoilerHelper $helperService) {
		// parent::__construct();
		$this->helperService = $helperService;
            $this->model=new jobcard();
	} 

	/*public function mobileLogin(Request $request){
		$posted_data= $request->json()->all();
//dd($posted_data);
		if((isset($posted_data['username']))&&(isset($posted_data['password']))){
			//dd($posted_data);
			$data = $this->helperService->getUser($posted_data);

		}else{
			$data['message']="Username/Password Missing";
		}
		return response()->json($data);
	}*/

	public function mobileLogin(Request $request){
		$posted_data= $request->json()->all();
		 $remember ='false';
		 
		 if (\Auth::attempt(array('username'=>$posted_data['username'], 'password'=> $posted_data['password'] ,'active'=>"Yes" ), $remember )) {
		 	if(\Auth::check()){
		 		$data = (object)array();

		 		$row = User::find(\Auth::user()->id);
				$id=$row['id'];
	            $token = md5(rand());
	            \DB::update("update tb_users set m_token='$token' where id='$id'");
				$data = User::find(\Auth::user()->id);
				if(isset($posted_data['fcm_token'])){
			$update['fcm_token']=$posted_data['fcm_token'];
			DB::table('tb_users')
	            ->where('id', $id)
	            ->update($update);
			$data['message']="Success";
		}
	         // 	$this->setEmployeeGeoLocation($data['employee_id'],1,$posted_data['geo_location'],$posted_data['battery']);
				$data['message']="SUCCESS";

		 	}
		 }else{


			$data['message']="Invalid Username or Password";
		}

		return response()->json($data);
	}

	public function updateFCMToken(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['fcm_token'])){
			$update['fcm_token']=$posted_data['fcm_token'];
			DB::table('tb_users')
	            ->where('id', $user_details->id)
	            ->update($update);
			$data['message']="Success";
		}else{
			$data['message']="FCM Token Missing";
		}
		return response()->json($data);
	}

	public function sendNotification(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data['notification'] = $this->helperService->sendNotification($posted_data,$user_details);
		return response()->json($data);
	}

	public function saleOrder(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->saleOrder($posted_data);
		return response()->json($data);
	}

	public function soCancel(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->soCancel($posted_data);
		return response()->json($data);
	}

	public function purchaseOrder(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->purchaseOrder($posted_data);
		return response()->json($data);
	}

	public function purchaseQuality(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->purchaseQuality($posted_data);
		return response()->json($data);
	}

	public function poCancel(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->poCancel($posted_data);
		return response()->json($data);
	}

	public function purchaseReq(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->purchaseReq($posted_data);
		return response()->json($data);
	}

	public function purchaseQuote(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->purchaseQuote($posted_data);
		return response()->json($data);
	}


	public function salesQuote(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->salesQuote($posted_data);
		return response()->json($data);
	}

	public function saleOrderList(Request $request)
	{
	    $posted_data= $request->json()->all();
		$token = $request->header('token');
	    $user_details = $this->helperService->getTokenUser($token);
	    $emp_id = $user_details->employee_id;
// 		$data['sale_order_list'] = $this->helperService->saleOrderList($emp_id);
		$data['sale_order_list'] = $this->helperService->apisaleOrderList($emp_id);
	    return response()->json($data);
	}
	
	
		public function saleOrderListnew(Request $request){
	    	$posted_data= $request->json()->all();
		    $token = $request->header('token');
	    	$user_details = $this->helperService->getTokenUser($token);
	        $emp_id = $user_details->employee_id;
		    $data['sale_order_list'] = $this->helperService->saleOrderListnew($emp_id);
		    return response()->json($data);
	    }
	

	public function soCancelList(Request $request){
		$data['so_cancel_list'] = $this->helperService->soCancelList();
		return response()->json($data);
	}

	public function saleOrderUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['order_status_id'])){
			$update['order_status_id']=$posted_data['order_status_id'];
			DB::table('s_salesorder_hdr_t')
	            ->where('sales_hdr_id', $posted_data['sales_hdr_id'])
	            ->update($update);
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}

	public function soCancelUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['order_status_id'])){
			$update['order_status_id']=$posted_data['order_status_id'];
			DB::table('s_salesorder_hdr_t')
	            ->where('sales_hdr_id', $posted_data['sales_hdr_id'])
	            ->update($update);
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}

	public function purchaseOrderList(Request $request){
		$data['purchase_order_list'] = $this->helperService->purchaseOrderList();
		return response()->json($data);
	}
	
	public function poCancelList(Request $request){
		$data['purchase_cancel_list'] = $this->helperService->poCancelList();
		return response()->json($data);
	}

	public function purchaseReqList(Request $request){
		$data['purchase_req_list'] = $this->helperService->purchaseReqList();
		return response()->json($data);
	}

	public function purchaseQuoteList(Request $request){
		$data['purchase_quote_list'] = $this->helperService->purchaseQuoteList();
		return response()->json($data);
	}

	public function salesQuoteList(Request $request){
		$data['sales_quote_list'] = $this->helperService->salesQuoteList();
		return response()->json($data);
	}

	public function purchaseOrderUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['po_status'])){
			$update['po_status']=$posted_data['po_status'];
			DB::table('p_po_hdr_t')
	            ->where('po_hdr_id', $posted_data['po_hdr_id'])
	            ->update($update);
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}

	public function purchaseQualityUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['qc_status'])){
			$update['qc_status']=$posted_data['qc_status'];
			DB::table('p_qc_header_t')
	            ->where('qc_header_id', $posted_data['qc_header_id'])
	            ->update($update);
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}

	public function poCancelUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['po_status'])){
			$update['po_status']=$posted_data['po_status'];
			DB::table('p_po_hdr_t')
	            ->where('po_hdr_id', $posted_data['po_hdr_id'])
	            ->update($update);
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}
	
	public function purchaseReqUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['requisition_status'])){
			$update['requisition_status']=$posted_data['requisition_status'];
			DB::table('p_requisition_hdr_t')
	            ->where('requisition_hdr_id', $posted_data['requisition_hdr_id'])
	            ->update($update);
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}

public function createsoorder(Request $request){
	//$posted_data= $_GET;
	$customer_id = $request->header('customerid');
	
//	dd($customer_id);
	
	$customerdata=DB::table('m_customers_t')
	->leftjoin('hr_employee_t','m_customers_t.sales_person','=','hr_employee_t.employee_id')
	->leftjoin('m_customer_sites_t','m_customer_sites_t.customer_id','=','m_customers_t.customer_id','and m_customer_sites_t.site_type="BILL_TO" and m_customer_sites_t.active="Yes" and primary_address="YES" ')
	->leftjoin('m_cities_t','m_customer_sites_t.city','=','m_cities_t.city_id')
	->leftjoin('m_discounts_hdr_t','m_discounts_hdr_t.ar_discount_hdr_id','=','m_customers_t.ar_discount_hdr_id')
	->leftjoin('m_states_t','m_customer_sites_t.state','=','m_states_t.state_id')
	->leftjoin('m_countries_t','m_customer_sites_t.country','=','m_countries_t.country_id')
	->leftjoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','m_customers_t.pricelist_id')
	->leftjoin('m_payment_methods_t','m_customers_t.default_payment_method_id','=','m_payment_methods_t.payment_method_id')
	->leftjoin('m_payment_terms_t','m_customers_t.default_payment_terms_id','=','m_payment_terms_t.payment_term_id','and source_type_id="Sales"')
	->select('hr_employee_t.employee_number','hr_employee_t.first_name','m_payment_terms_t.payment_term_id','m_payment_terms_t.payment_term_name','m_payment_methods_t.payment_method_name','m_payment_methods_t.payment_method_id','m_customers_t.*','i_pricelist_hdr_t.pricelist_name','m_customer_sites_t.customer_site_id','m_customer_sites_t.city as city_id','m_customer_sites_t.state as state_id','m_customer_sites_t.country as country_id','m_customer_sites_t.address','m_customer_sites_t.customer_site_name','m_cities_t.city_name','m_states_t.state_name','m_countries_t.country_name','m_customer_sites_t.pincode')
->selectRaw('COALESCE(m_discounts_hdr_t.default_discount_amount,0) as discount_amount')	->where('m_customers_t.customer_id',$customer_id)->where('m_customer_sites_t.site_type',"BILL_TO")->get();
	
$customer['customerdata']=$customerdata;

// if($customerdata[0]->default_discount_amount==null)
// {
// 	$customer['discount_amount']=0;
// }
// else
// {
//   	$customer['discount_amount']=$customerdata[0]->default_discount_amount;
  
// }
    	$customer['invoice_currency']=DB::table('f_account_currency_t')->select('account_currency_id','currency_code')->get();
	$customer['product_list']=DB::table('i_pricelist_lines_t')->leftjoin('m_products_t','m_products_t.product_id','=','i_pricelist_lines_t.product_id')->select('m_products_t.product_code','m_products_t.concatenated_product','i_pricelist_lines_t.product_id')->where('i_pricelist_lines_t.pricelist_hdr_id',$customerdata[0]->pricelist_id)->where('i_pricelist_lines_t.active','Yes')->where('m_products_t.active','Yes')->GroupBy('i_pricelist_lines_t.product_id')->get();	
	
		$customer['message']="success";
return response()->json($customer);
}

public function createsoorderlist(Request $request){

	$customer['product_list']=DB::table('i_pricelist_lines_t')->leftjoin('m_products_t','m_products_t.product_id','=','i_pricelist_lines_t.product_id')->select('m_products_t.product_code','m_products_t.concatenated_product','i_pricelist_lines_t.product_id')->where('i_pricelist_lines_t.pricelist_hdr_id',176)->where('i_pricelist_lines_t.active','Yes')->where('m_products_t.active','Yes')->GroupBy('i_pricelist_lines_t.product_id')->OrderBy('m_products_t.concatenated_product','asc')->get();	
	
		$customer['message']="success";
return response()->json($customer);
}

public function soorderproductlist(Request $request){
	$posted_data= $request->json()->all();
	$product_id = $request->header('productid');
	$pricelist_id = $request->header('pricelistid');
	$customer_site_id = $request->header('customersiteid');
 	$cuurnt_date=date('Y-m-d');
	//dd($cuurnt_date);
	$pricelist_tbl=DB::table('i_pricelist_lines_t')
	->leftjoin('m_products_t','m_products_t.product_id','=','i_pricelist_lines_t.product_id')
	->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','m_products_t.trx_uom_id')
	
	->select('m_uom_codes_t.uom_code','m_products_t.*','i_pricelist_lines_t.unit_price','i_pricelist_lines_t.std_price')
	->where('i_pricelist_lines_t.pricelist_hdr_id',$pricelist_id)
	->where('i_pricelist_lines_t.product_id',$product_id)->where('i_pricelist_lines_t.active','Yes')
	->where('i_pricelist_lines_t.end_date','>=',$cuurnt_date)
	->OrderBy('i_pricelist_lines_t.pricelist_line_id','desc')->limit(1)->get();	
//

	$productdetails['pricelist_tbl']=$pricelist_tbl;
	
	if(count($pricelist_tbl)>0){   
	 $tax=$this->taxdetails($pricelist_tbl[0]->defalut_hsn_code,$customer_site_id,'Sales');
//	dd($tax);
	 if(COUNT($tax)>0){
                $tax1=\DB::table("m_tax_group_t")->select('display_name','tax_group_name','tax_group_id')
                ->where('tax_group_id',$tax['tax_group_id'])->get();
                $productdetails['taxgroup']=$tax1;     
	 }else{
	                 $productdetails['taxgroup']=$tax1;  
	     }
	     $productdetails['message']="success";
	}else{
	    $productdetails=array();
	     $productdetails['message']="PriceList Has Been Expiry This Product";
	}
//	dd($productdetails);
		
return response()->json($productdetails);
}

public function customerlist(Request $request){

			$token = $request->header('token');
           $user_details = $this->helperService->getTokenUser($token);
           $emp_id = $user_details->employee_id;
           
          $SQL = "SELECT m_customers_t.customer_name,m_customers_t.customer_id,m_customers_t.customer_number FROM m_customers_t join distributormapping_lines_tbl on distributormapping_lines_tbl.disti_id = m_customers_t.customer_id join distributormapping_hdr_tbl on distributormapping_hdr_tbl.distributormapping_id = distributormapping_lines_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id = '$emp_id' group by m_customers_t.customer_id";
         $customer = \DB::select($SQL);
// $customer=DB::table('m_customers_t')->select('customer_name','customer_id','customer_number')->get();
$custo['customerlist']=$customer;
		$custo['message']="Customer List";
return response()->json($custo);
    
}
public function salesordersave(Request $request){
    $posted_data= $request->json()->all();
    $data['data_all']==json_encode($posted_data);
               $id = DB::table('s_sales_dummy')->insertGetId($data);
//dd($id);
			$token = $request->header('token');
           $user_details = $this->helperService->getTokenUser($token);
           $data['sales_hdr_id']=$posted_data['sales_hdr_id'];
           $data['salesorder_count']=$posted_data['salesorder_count'];
           $data['sales_order_no']=$posted_data['sales_order_no'];
           $data['sales_order_date']=$posted_data['sales_order_date'];
           $data['contact_number']=$posted_data['contact_number'];
           $data['contact_person']=$posted_data['contact_person'];
           $data['bill_to_address_id']=$posted_data['bill_to_address_id'];
           $data['delivery_date']=$posted_data['delivery_date'];
           $data['pricelist_id']=$posted_data['pricelist_id'];
           $data['ar_delivery_terms_id']=$posted_data['ar_delivery_terms_id'];
           $data['ar_payment_method_id']=$posted_data['ar_payment_method_id'];
           $data['freight_carrier_id']=$posted_data['freight_carrier_id'];
           $data['currency_code_id']=$posted_data['currency_code_id'];
           $data['ship_to_customer_id']=$posted_data['ship_to_customer_id'];
        //   $data['invoice_currency']=$posted_data['invoice_currency'];
        
           $data['savestatus']=$posted_data['savestatus'];
           $data['order_sub_total']=$posted_data['order_sub_total'];
           $data['order_tax']=$posted_data['order_tax'];
           $data['order_total']=$posted_data['order_total'];
           $data['balance_amount']=$posted_data['balance_amount'];
           $data['order_type_id']=$posted_data['order_type_id'];
           $data['order_status']=$posted_data['order_status'];
           $data['order_status_id']=$posted_data['order_status_id'];
           $data['source']=$posted_data['source'];   
           $data['ship_to_address_id']=$posted_data['ship_to_address_id'];
           $data['customer_po']=$posted_data['customer_po'];
           $data['salesperson_id']=$posted_data['salesperson_id'];
           $data['invoice_status']=$posted_data['invoice_status'];
            
            $data['cash_discount']='';  
           $data['reference_id']='';
           $data['reference_number']='';
           $data['other_tax_amount']=0;
           $data['other_frieght_amount']=0;
           $data['packaging_charges']=0;
           $data['insurance_charges']=0;
           $data['remarks']='';
           $data['project_id']='';
           $data['customer_po_number']='';
           $data['so_ref_no']='';
           
           $data['qty_total']='';
           
    //       $data['project_id']=$posted_data['project_id'];
    //       $data['discount_id']=$posted_data['discount_id'];
           
    //       $data['qoh_qty']=$posted_data['qoh_qty'];
           $data['unloading_charges']=0;
           $data['transport_charges']=0;
           $data['ar_frieghtterm_id']=0;
           $data['ar_payment_term_id']=0;
       //    $data['workorder_status']=$posted_data['workorder_status'];
           
         
     //      $data['approver_id']=$posted_data['approver_id'];
    //       $data['approve_status']=$posted_data['approve_status'];
           $data['employee_id']=$posted_data['employee_id'];
           $data['other_tax_amount_tax']=0;
           $data['other_frieght_amount_tax']=0;
           $data['transport_charges_tax']=0;
           $data['insurance_charges_tax']=0;
           $data['packaging_charges_tax']=0;
           
        //   $data['advance_amount']=$posted_data['advance_amount'];
        //   $data['employee_customer']=$posted_data['employee_customer'];
           
         //  $data['advance_status']=$posted_data['advance_status'];
          
           
           $data['location_id']=$posted_data['location_id'];
           $data['updated_at']=$posted_data['updated_at'];
           $data['last_updated_by']=$posted_data['last_updated_by'];
           $data['created_at']=$posted_data['created_at'];
           $data['created_by']=$posted_data['created_by'];
           $data['organization_id']=$posted_data['organization_id'];
           $data['company_id']=$posted_data['company_id'];
           
           $id = DB::table('s_salesorder_hdr_t_dummy')->insertGetId($data);
           
           foreach($posted_data['so_lines'] as $key=>$value){
             $linedata['sales_line_id'][$key]='';
           $linedata['sales_hdr_id'][$key]=$id;
           $linedata['line_no'][$key]=$key+1;
           $linedata['product_id'][$key]=$value['product_id'];
           $linedata['part_no'][$key]='';
           $linedata['product_description'][$key]='';
           $linedata['uom_code_id'][$key]=$value['uom_code_id'];
           $linedata['qty'][$key]=$value['qty'];
           $linedata['unit_price'][$key]=$value['unit_price'];
           $linedata['discount_percentage']=$posted_data['discount_percentage'];
           $linedata['discount_amount'][$key]=$value['discount_amount'];
           //$linedata['tax_excemption'][$key]=$value['tax_excemption'];
           $linedata['hsn_code'][$key]=$value['hsn_code'];
           $linedata['tax_group_id'][$key]=$value['tax_group_id'];
           $linedata['tax_amount'][$key]=$value['tax_amount'];
           $linedata['pending_qty'][$key]=$value['qty'];
           $linedata['line_total'][$key]=$value['line_total'];
           $linedata['comments'][$key]='';
           $linedata['delivery_date'][$key]=$value['delivery_date'];
           
           $linedata['line_sub_total'][$key]=$value['line_sub_total'];
           
           
           $linedata['location_id'][$key]=$value['location_id'];
           $linedata['updated_at'][$key]=$value['updated_at'];
           $linedata['last_updated_by'][$key]=$value['last_updated_by'];
           $linedata['created_at'][$key]=$value['created_at'];
           $linedata['created_by'][$key]=$value['created_by'];
           $linedata['organization_id'][$key]=$value['organization_id'];
           $linedata['company_id'][$key]=$value['company_id'];  
               
           }
           $id1 = DB::table('s_salesorder_lines_t')->insertGetId($linedata);
           $data2['message']="Save Successfully";

 return response()->json($data2);
}
	public function purchaseQuoteUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['quote_status'])){
			$update['quote_status']=$posted_data['quote_status'];
			DB::table('p_quotation_hdr_t')
	            ->where('quotation_hdr_id', $posted_data['quotation_hdr_id'])
	            ->update($update);
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}

	public function salesQuoteUpdate(Request $request){		
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['quote_status'])){
			$update['quote_status']=$posted_data['quote_status'];
			DB::table('s_quote_hdr_t')
	            ->where('quote_hdr_id', $posted_data['quote_hdr_id'])
	            ->update($update);
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}

	public function saleInvoiceList(Request $request){
		$data['sale_invoice_list'] = $this->helperService->saleInvoiceList();
		return response()->json($data);
	}

	public function saleInvoiceUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');

		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['invoice_status'])){
			$update['invoice_status']=$posted_data['invoice_status'];
			
			DB::table('s_invoice_hdr_t')
	            ->where('invoice_hdr_id', $posted_data['invoice_hdr_id'])
	            ->update($update);
        	$app_data = DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $posted_data['invoice_hdr_id'])->select('invoice_status')->get();
        	$st = $app_data[0]->invoice_status;
        	
        	if($st == "APPROVED"){
        		$inv_number = $this->invoice_number($posted_data);
        		$invupdate['invoice_number'] = $inv_number[0];
            	$invupdate['invoice_count'] = $inv_number[1];

    			DB::table('s_invoice_hdr_t')
		            ->where('invoice_hdr_id', $posted_data['invoice_hdr_id'])
		            ->update($invupdate);
        	}	
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}

	public function invoice_number($data){
		if($data['invoice_number'] == null ){
			
			$seqno = \DB::table('s_invoice_hdr_t')->where('invoice_status',$data['invoice_status'])->orderBy('invoice_count','desc')->get();
			
			if(count($seqno)>0)
            {
                $seqno = $count = $seqno[0]->invoice_count;
            }else{
            	$seqno = $count = 0;
            }	

            if($data['invoice_type'] == "LABOUR")
        	{

        		$seqname = 'SOINVL';
        	}
        	if($data['invoice_type'] =="STANDARD")
	        {
	        	
	            $seqname = 'SOINVS';
	        }
	        $seqno = sprintf('%03d',$seqno+1);
	        $count = $count+1;
	        $out_put[0] = $seqname.$seqno;
	        $out_put[1] = $count;

	        return $out_put;
		}
		
	}

	public function saleInvoice(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->saleInvoice($posted_data);
		return response()->json($data);
	}

	public function purchaseInvoiceList(Request $request){
		$data['purchase_invoice_list'] = $this->helperService->purchaseInvoiceList();
		return response()->json($data);
	}

	public function purchaseQualityList(Request $request){
		$data['purchase_quality_list'] = $this->helperService->purchaseQualityList();
		return response()->json($data);
	}

	public function purchaseReturnList(Request $request){
		$data['purchase_return_list'] = $this->helperService->purchaseReturnList();
		return response()->json($data);
	}

	public function purchaseInvoice(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->purchaseInvoice($posted_data);
		return response()->json($data);
	}
	
	public function purchaseReturn(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data = $this->helperService->purchaseReturn($posted_data);
		return response()->json($data);
	}

	public function purchaseInvoiceUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['po_invoice_status'])){
			$update['po_invoice_status']=$posted_data['po_invoice_status'];
			DB::table('p_po_invoice_hdr_t')
	            ->where('po_invoice_id', $posted_data['po_invoice_id'])
	            ->update($update);
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}

	public function purchaseReturnUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		if(isset($posted_data['p_return_status'])){
			$update['p_return_status']=$posted_data['p_return_status'];
			DB::table('p_return_header_t')
	            ->where('return_header_id', $posted_data['return_header_id'])
	            ->update($update);
		}else{
			$data['message']="Status Missing";
		}
		return response()->json($posted_data);
	}

	public function leavetype(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data=DB::table('a_lookuplines_t')
						->select('lookup_meaning','lookuplines_id')
						->where('lookup_type','leave_type' )			
						->get();
						$area_list['leavetype']=$data;
		return response()->json($area_list);
	}
		public function leavemode(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->helperService->getTokenUser($token);
		$data=DB::table('a_lookuplines_t')
						->select('lookup_meaning','lookuplines_id')
						->where('lookup_type','leave_mode' )			
						->get();
						$area_list['leavemode']=$data;
		return response()->json($area_list);
	}
	public function forwardto(Request $request){
		
		$posted_data= $request->json()->all();
		$token = $request->header('token');

		$user_details = $this->helperService->getTokenUser($token);
		
	$datas1=DB::table('hr_employee_t')
						->select('reporting_manager')
						->where('employee_id',$user_details->employee_id)			
						->get();
		$datas=DB::table('hr_employee_t')
						->select('employee_id','first_name')
						->where('employee_id',$datas1[0]->reporting_manager)			
						->get();
						if(count($datas)>0){
						$area_list['forwardto']=$datas;	
					}else{
$area_list['forwardto']='';
					}
						
		return response()->json($area_list);
					}
	
		public function leaverequest(Request $request){
			$posted_data= $request->json()->all();
			$token = $request->header('token');
           $user_details = $this->helperService->getTokenUser($token);
           $data['employee_id']=$user_details->employee_id;
           $data['start_date']=$posted_data['request_date'];
           $data['end_date']=$posted_data['end_date'];
           $data['start_date_time']=$posted_data['request_date_time'];
           $data['end_date_time']=$posted_data['end_date_time'];
           $data['no_of_days']=$posted_data['no_of_days'];
           $data['no_of_hrs']=$posted_data['no_hrs'];
           $data['leave_type']=$posted_data['leave_type'];
           $data['leave_mode']=$posted_data['leave_mode'];
           $data['leave_reason']=$posted_data['reason'];
           $data['leave_status']=$posted_data['leave_status'];
           $data['forwarded_id']=$posted_data['forwarded_to'];
           $data['created_at'] = date('Y-m-d h:i:s');
           $data['updated_at'] = date('Y-m-d h:i:s');
           $data['last_updated_by'] = "1";
           $data['company_id'] =1;
           $data['location_id'] =1;
           $data['created_by'] = $user_details->employee_id;
$id = DB::table('hr_leaves_t')->insertGetId($data);

$datas1=DB::table('tb_users')
						->select('fcm_token')
						->where('employee_id',$user_details->reporting_manager)			
						->get();

        $notification['body']="LEAVE REQUEST";
        $notification['title']=$user_details->employee_number;
        $notification['icon']="";
        $notification['sound']="default";
        $count['body']="LEAVE REQUEST";
        $count['title']=$user_details->employee_number;
        $count['count']="28";
        $count['sound']="default";
        // dd($datas1[0]->fcm_token);
//$send_notification = $this->sendPopUpNotification($id,"LEAVE REQUEST","LEAVE REQUEST","leavelist");
$send_notification = $this->fcmMsgNotification($datas1[0]->fcm_token,$notification,$count);

$data2['message']="Leave send Successfully";

 return response()->json($data2);
	}
	public function leavelist(Request $request){

		    $posted_data= $request->json()->all();
			$token = $request->header('token');
            $user_details = $this->helperService->getTokenUser($token);
            $empId =$user_details->employee_id;
     		$data=DB::table('hr_leaves_t')
     		->select('tb_users.username as employee_id','tb_users.first_name as username','hr_leaves_t.*','a_lookuplines_t.lookup_meaning','mode.lookup_meaning as leavemode','reporting.username as reportname')
     		->leftjoin('tb_users', 'hr_leaves_t.employee_id', '=', 'tb_users.employee_id')
     		->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_leaves_t.leave_type')
     		->leftjoin('a_lookuplines_t as mode', 'mode.lookuplines_id', '=', 'hr_leaves_t.leave_mode')
     		->leftjoin('tb_users as reporting', 'reporting.employee_id', '=', 'hr_leaves_t.forwarded_id')
     		->where('hr_leaves_t.forwarded_id',$empId)
     		->where('hr_leaves_t.leave_status','INITIATED')
     		->groupBy('hr_leaves_t.leave_id')
     		->get();
     		//dd($data);
     		$leavelist['leavelist']=$data;
		return response()->json($leavelist);
	}
	   public function leavedataupdate(Request $request){
     	    $posted_data= $request->json()->all();
			$token = $request->header('token');
            $user_details = $this->helperService->getTokenUser($token);
             $leaveid= $posted_data['leave_id']; //
     
             $update['leave_status']= $posted_data['status'];
            // dd($missid);
            // dd($update);
           DB::table('hr_leaves_t')
	            ->where('leave_id',$leaveid)
	            ->update($update);
            $datas['message']="Update Succesfully";

                return $datas;
     }
     
    public function saveso(Request $request){
        $posted_data= $request->json()->all();
        $data=[];
        //   $posted_data= $request->json()->all();
        // $data['data_all']=json_encode($posted_data);
        //           $id = DB::table('s_sales_dummy')->insertGetId($data);
        //dd($id);
        
        $token = $request->header('token');
        $user_details = $this->helperService->getTokenUser($token);
        $data['created_by']=$user_details->employee_id;
        $companyid=$user_details->company_id;
        //dd($user_details);
        $data['company_id']=$companyid;
        $data['location_id']=1;//$user_details->location_id;
        $data['created_at'] = date('Y-m-d h:i:s');
        $data['updated_at'] = date('Y-m-d h:i:s');
    //   foreach($posted_data as $k=>$v){
    //       if($v==null){
    //           $posted_data[$k]='';
    //       }
    //   }
        $data['ar_delivery_terms_id']=$posted_data['ar_delivery_terms_id'];
        $data['ar_payment_method_id']=$posted_data['ar_payment_method_id'];
      
        $bill_id=DB::table('m_customer_sites_t')->where('customer_id',$posted_data['ship_to_customer_id'])->where('site_type','BILL_TO')->where('active','Yes')->get();
     //	dd($bill_id);
        if(!empty($bill_id))
		{
            $data['bill_to_address_id']=$bill_id[0]->customer_site_id;
        }
        else
        {
            $data['bill_to_address_id']='';  
        }
      
        $ship_id=DB::table('m_customer_sites_t')->where('customer_id',$posted_data['ship_to_customer_id'])->where('site_type','SHIP_TO')->where('active','Yes')->get();
     	if(!empty($ship_id))
		{
            $data['ship_to_address_id']=$ship_id[0]->customer_site_id;
        }
        else
        {
            $data['ship_to_address_id']='';  
        }
    
      	$data['order_status_id'] = "DRAFT";
      	$data['order_type_id'] = "STANDARD";
      	$data['source'] = "STANDARD";

      	$seqno=$this->Seqnoe('SO','s_salesorder_hdr_t',$data['order_type_id'],'salesorder_count');
	    $data['sales_order_no'] = $seqno[0];
	    $data['salesorder_count'] = $seqno[1];
	    
        $data['contact_number']=$posted_data['contact_number'];
        $data['sales_order_date']=date('Y-m-d',strtotime($posted_data['sales_order_date']));
        if($posted_data['contact_person']!=null)
        {
            $data['contact_person']=$posted_data['contact_person'];
        }
        else
        {
            $data['contact_person']=0;
        }
        $data['invoice_currency']=37;
        $data['freight_carrier_id']=$posted_data['freight_carrier_id'];
        $data['pricelist_id']=$posted_data['pricelist_id'];
        $data['discount_id']=$posted_data['discount_id'];
        $data['ship_to_customer_id']=$posted_data['ship_to_customer_id'];
        $data['delivery_date']=$posted_data['delivery_date'];
      //$data['tax_amount']=$posted_data['tax_amount'];
    //  dd($posted_data['ship_to_customer_id']);
        $schemes = \DB::table('m_customers_t')->where('customer_id',$posted_data['ship_to_customer_id'])->get();
        $scheme = array();
    
        if(count($schemes)>0){
            $scheme = explode(',',$schemes[0]->schemes);
        }
    // dd($scheme);
   // $id=1;
        $id = DB::table('s_salesorder_hdr_t')->insertGetId($data);

        $tot=0;
        $tax_tot=0;
        $qty_total=0;
        foreach($posted_data['so_lines'] as $key=>$value)
        {
            $linedata=array();
            
            $linedata['sales_hdr_id']=$id;
            $linedata['line_no']=$key+1;
            $linedata['product_id']=$value['product_id'];
            $linedata['part_no']='';
            $linedata['product_description']='';
            $linedata['uom_code_id']=$value['uom_code_id'];
            $linedata['qty']=$value['qty'];
            $qty_total=$qty_total+$value['qty'];
            $linedata['unit_price']=$value['unit_price'];
            $linedata['delivery_date']=date('Y-m-d',strtotime($value['delivery_date']));
            $linedata['hsn_code']=$value['hsn_code'];
            $linedata['tax_group_id']=$value['tax_group_id'];
            $linedata['tax_amount']=$value['tax_amount'];
            $linedata['discount_percentage']=$value['discount_percentage'];
            $linedata['discount_amount']=$value['discount_amount'];
            $linedata['pending_qty']=0;
            $linedata['line_total']=$value['amount']+$value['tax_amount'];
            
            $tot=$value['amount']+$value['tax_amount']+$tot;
            $tax_tot=$tax_tot+$value['tax_amount'];
            $linedata['comments']='';
            // $linedata['delivery_date']=$value['delivery_date'];
            $linedata['location_id']=1;
            $linedata['updated_at']=date('Y-m-d h:i:s');
            $linedata['last_updated_by']=$user_details->employee_id;
            $linedata['created_at']=date('Y-m-d h:i:s');
            $linedata['created_by']=$user_details->employee_id;
            $linedata['organization_id']=1;
            $linedata['company_id']=1;  
           
            $schm=$schm1=array();
           
            foreach($scheme as $sk=>$sv)
            {
                $schm1 = $this->schemesavailables($value['product_id'],$value['qty'],$value['unit_price'],$sv);
                if(count($schm1)>0)
            {
                $schm=$schm1;
            }
            //   print_r($schm);
            }
          // dd($schm);
            if(count($schm)>0)
            {
                if($schm['sch_data'][0]->schemes_type!='Gift')
                {
                // $linedata['discount_percentage'] = $schm['sch_data'][0]->schemes_type_value;
                }
                else
                {
                    $from_qty=$schm['sch_data'][0]->scheme_base_value_from;
                    $free_qty=$schm['qty']/$from_qty;
                    $linedata['free_qty'] = $free_qty;
                }
            }
            else
            {
                $linedata['free_qty'] =0; 
            }
        //   dd($linedata);
            $id1 = DB::table('s_salesorder_lines_t')->insertGetId($linedata);
        }
           
           	// $approverid= \DB::select("SELECT reporting_manager FROM `hr_employee_t` where employee_id=".$user_details->employee_id);
			
	    if($tot == ""){
	        $tot = "0";
	    }
	        
	    $approverid= $this->APIApprovaldatacheck('soorder',$tot,$user_details->employee_id);
			
        if($approverid == "0" ){
		     $approver = $user_details->employee_id;
        	 $order_status_id = "APPROVED";
        	 $update = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$id)->update(['order_total'=>$tot,'qty_total'=>$qty_total,'order_tax'=>$tax_tot,'approver_id'=>$approver,'order_status_id'=>$order_status_id]);
        }else{
        	$approver = $approverid;
        	//dd($approver);
        	$update = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$id)->update(['order_total'=>$tot,'qty_total'=>$qty_total,'order_tax'=>$tax_tot,'approver_id'=>$approver]);
        }
				
	       // $approver=$approverid[0]->reporting_manager;
	        
        //   $update = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$id)->update(['order_total'=>$tot,'qty_total'=>$qty_total,'order_tax'=>$tax_tot,'approver_id'=>$approver]);

       $datas['message']="Sales order created succesfully";
       return $datas;
     }
     
     
public function schemesavailables($product,$qty,$unitprice,$schemes_type){

$qty_based=\DB::select("select * from s_schemes_lines_t where schemes_hdr_id='$schemes_type' and product_id='$product' and scheme_base='Quantity Based' and scheme_base_value_from<='$qty' ");

//dd($qty_based);

$value_based=\DB::select("select * from s_schemes_lines_t where schemes_hdr_id='$schemes_type' and product_id='$product' and scheme_base='Price Based' and scheme_base_value_from<='$qty' and scheme_base_value_to >='$qty'");

$data=array();
if(count($qty_based)) {
    $data=array();
$data['sch_data']=$qty_based;
$data['qty']=$qty;
return $data;
}
else if(count($value_based))
{
return $value_based;
}
else
return $data;
}

//vignesh purpose: save new sample so
public function savesonew(Request $request){
      $posted_data= $request->json()->all();
      $data=[];
     
      $token = $request->header('token');
      $user_details = $this->helperService->getTokenUser($token);
      $data['created_by']=$emp_id=$user_details->employee_id;
      $companyid=$user_details->company_id;
   
      $data['company_id']=$companyid;
      $data['location_id']=1;//$user_details->location_id;
      $data['created_at'] = date('Y-m-d h:i:s');
      $data['updated_at'] = date('Y-m-d h:i:s');
   
      $data['ar_delivery_terms_id']=0;
      $data['ar_payment_method_id']=0;
      
		
      $data['bill_to_address_id']=$emp_id;
      
      $data['ship_to_address_id']=$emp_id;
      $data['employee_id'] = $emp_id;
      
      
      	 $data['order_status_id'] = "DRAFT";
      	 $data['order_type_id'] = "SAMPLE";
      	 $data['order_status'] = "INITIATED";
      	 $data['source'] = "SAMPLE";
      	 
      	 $seqno=$this->Seqnoe('SO','s_salesorder_hdr_t',$data['order_type_id'],'salesorder_count');
	        $data['sales_order_no'] = $seqno[0];
	        $data['salesorder_count'] = $seqno[1];
	    

      $data['contact_number']="";
      $data['sales_order_date']=date('Y-m-d');
      $data['contact_person']="";
      
      $data['invoice_currency']=37;
      $data['freight_carrier_id']=0;
      $data['pricelist_id']=176;
      
      $data['discount_id']=0;
      $data['ship_to_customer_id']=0;
      $data['delivery_date']=$posted_data['delivery_date'];
      
      $id = DB::table('s_salesorder_hdr_t')->insertGetId($data);
      
$tot=0;
$tax_tot=0;
$qty_total=0;
      foreach($posted_data['so_lines'] as $key=>$value){
          $linedata=array();
          
           $linedata['sales_hdr_id']=$id;
           $linedata['line_no']=$key+1;
           $linedata['product_id']=$value['product_id'];
           $linedata['part_no']='';
           $linedata['product_description']='';
           $linedata['uom_code_id']=1;
           $linedata['qty']=$value['qty'];
           $qty_total=$qty_total+$value['qty'];
           $linedata['unit_price']=0;
           $linedata['delivery_date']=date('Y-m-d',strtotime($value['delivery_date']));
           $linedata['hsn_code']=0;
           $linedata['tax_group_id']=$value['tax_group_id'];
           $linedata['tax_amount']=$value['tax_amount'];
           $linedata['discount_percentage']=0;
           $linedata['discount_amount']=$value['discount_amount'];
           $linedata['pending_qty']=0;
           $linedata['line_total']=$value['amount']+$value['tax_amount'];
           
           $tot=$value['amount']+$value['tax_amount']+$tot;
           $tax_tot=$tax_tot+$value['tax_amount'];
           $linedata['comments']='';
          // $linedata['delivery_date']=$value['delivery_date'];
           $linedata['location_id']=1;
           $linedata['updated_at']=date('Y-m-d h:i:s');
           $linedata['last_updated_by']=$user_details->employee_id;
           $linedata['created_at']=date('Y-m-d h:i:s');
           $linedata['created_by']=$user_details->employee_id;
           $linedata['organization_id']=1;
           $linedata['company_id']=1;  
          
           $linedata['free_qty'] =0; 
          
        
           $id1 = DB::table('s_salesorder_lines_t')->insertGetId($linedata);
           }
           
             if($tot == ""){
	        	$tot = "0";
	        }
	        
	        
	    	$approverid= $this->APIApprovaldatacheck('soorder',$tot,$user_details->employee_id);
			
	        if($approverid == "0" ){
			    $approver = $user_details->employee_id;
	        	 $order_status_id = "APPROVED";
	        	 $update = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$id)->update(['order_total'=>$tot,'qty_total'=>$qty_total,'order_tax'=>$tax_tot,'approver_id'=>$approver,'order_status_id'=>$order_status_id]);
	        }else{
	        	$approver = $approverid;
	        	$update = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$id)->update(['order_total'=>$tot,'qty_total'=>$qty_total,'order_tax'=>$tax_tot,'approver_id'=>$approver]);
	        }
           
            //   	$approverid= \DB::select("SELECT reporting_manager FROM `hr_employee_t` where employee_id=".$user_details->employee_id);
			// $approver=$approverid[0]->reporting_manager;
	        //   $update = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$id)->update(['order_total'=>$tot,'qty_total'=>$qty_total,'order_tax'=>$tax_tot,'approver_id'=>$approver]);

           
      $datas['message']="Sales order created succesfully";
      return $datas;
     }
     
    public function APIApprovaldatacheck($module=null,$total=null,$empid=null){
            $wh="";
        
            if($module=='poquote'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Purchase Quotation Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='poinvoice'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Purchase Invoice Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='poorder'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Purchaseorder Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='soinvoice'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Sales Invoice Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='soquote'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Sales Quote Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='soorder'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Sales Order Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='product'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Product Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='supplier'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Supplier Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='materialbom'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='BOM Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='schemes'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Schemes Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='customers'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Customer Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='purchaseprice'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Purchase Pricelist Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }else if($module=='salesprice'){
                $wh.= " and m_approvalsettings_hdr_t.module_name='Sales Pricelist Approval' and $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
            }
            $sql_chk = \DB::SELECT("select m_approvalsettings_hdr_t.*,m_approvalsettings_line_t.* from m_approvalsettings_hdr_t left join m_approvalsettings_line_t on (m_approvalsettings_hdr_t.approvalsettings_hdr_id=m_approvalsettings_line_t.approvalsettings_hdr_id) Where 1=1 $wh and m_approvalsettings_line_t.approve_required='Yes'");
            
            if($empid!=null){
                if($module=='soorder')
                {
                    $approverid= \DB::select("SELECT reporting_manager,reporting_manager1 FROM `hr_employee_t` where employee_id=".$empid);
    			    $approver=$approverid[0]->reporting_manager;
    			    $approver1=$approverid[0]->reporting_manager1;
    			    $data[]=(int)$approver;
    			    $data[]=(int)$approver1;
                }
                else
                {                
                    $approverid= \DB::select("SELECT reporting_manager FROM `hr_employee_t` where employee_id=".$empid);
    			    $approver=$approverid[0]->reporting_manager;
    			    $data[]=(int)$approver;
                }

                    foreach($sql_chk as $val)
                    {
                        $data[]=$val->approver_id;
                    }
                    return json_encode($data);
                
            }else{
                if(count($sql_chk)>0){
                    foreach($sql_chk as $val)
                    {
                        $data[]=$val->approver_id;
                    }
                    return json_encode($data);
                }else{
                    return 0;
                }
            }
        
        
    }
public function joblist(Request $request){
      $posted_data= $request->json()->all();
      $data=[];
     
      $token = $request->header('token');
      $jobtype = $request->header('type');
      $user_details = $this->helperService->getTokenUser($token);
      
      $wh=" and  w_productionplan_hdr_t.plan_status='APPROVED'";
		
		if($jobtype=="production"){
		    $wh.=" and m_product_groups_t.group_name='SEMI FINISHED GOODS' ";	    
		}else{
		    $wh.=" and m_product_groups_t.group_name='FINISHED GOODS' and w_productionplan_hdr_t.job_card_status!=1";
		}
	    
		
      
      
      	$download_SQL = "SELECT
    w_productionplan_hdr_t.productionplan_hdr_id,
    w_productionplan_hdr_t.plan_no,
    w_productionplan_hdr_t.batch_no,
    w_productionplan_hdr_t.plan_date,
    w_productionplan_hdr_t.start_date,
    w_productionplan_hdr_t.end_date,
	 w_productionplan_hdr_t.plan_status,
    w_productionplan_hdr_t.production_qty,
    w_productionplan_hdr_t.plan_qty,
    w_productionplan_hdr_t.pending_qty,
    w_productionplan_hdr_t.remarks,
    m_products_t.concatenated_product,
    m_products_t.product_code,
    m_uom_codes_t.uom_code
FROM
    w_productionplan_hdr_t
LEFT JOIN m_products_t ON(
        m_products_t.product_id= w_productionplan_hdr_t.product_id
    )
	LEFT JOIN m_product_groups_t ON(
        m_products_t.product_group_id= m_product_groups_t.product_group_id
    )
LEFT JOIN m_uom_codes_t ON
    (
        w_productionplan_hdr_t.uom_code_id = m_uom_codes_t.uom_code_id
    ) where 1=1 $wh ORDER BY productionplan_hdr_id desc";
		
		$result1 = \DB::select( $download_SQL );
        $result['data']=collect($result1)->map(function($x){ return (array) $x; })->toArray();
      return $result;
}


public function createjoblist(Request $request){
      $posted_data= $request->json()->all();
      $data=[];
     
      $token = $request->header('token');
      $user_details = $this->helperService->getTokenUser($token);
      $id = $request->header('id');
      
		$wh=" and  w_productionplan_hdr_t.plan_status='APPROVED' and w_productionplan_hdr_t.productionplan_hdr_id=$id";
	
      	$download_SQL = "SELECT
    w_productionplan_hdr_t.productionplan_hdr_id,
    w_productionplan_hdr_t.plan_no,
    w_productionplan_hdr_t.batch_no,
    w_productionplan_hdr_t.plan_date,
    w_productionplan_hdr_t.start_date,
    w_productionplan_hdr_t.end_date,
	 w_productionplan_hdr_t.plan_status,
    round(w_productionplan_hdr_t.production_qty,3) as production_qty,
    w_productionplan_hdr_t.plan_qty,
    w_productionplan_hdr_t.pending_qty,
    w_productionplan_hdr_t.remarks,
    w_productionplan_hdr_t.product_id,
    m_products_t.concatenated_product
FROM
    w_productionplan_hdr_t
    join m_products_t on m_products_t.product_id = w_productionplan_hdr_t.product_id
where 1=1 $wh ORDER BY productionplan_hdr_id";
		
		$result1 = \DB::select( $download_SQL );
	$lines=	\DB::select("SELECT productionplan_hdr_id,m_products_t.product_id,m_products_t.concatenated_product,round(w_productionplan_lines_t.qty,3) as qty,w_productionplan_lines_t.qoh,w_productionplan_lines_t.pending_qty  FROM `w_productionplan_lines_t` join m_products_t on m_products_t.product_id=w_productionplan_lines_t.product_id and m_products_t.product_group_id=4 WHERE `productionplan_hdr_id` = $id and parent_product='".$result1[0]->product_id."'");
// 	$lines=	\DB::select("SELECT productionplan_hdr_id,m_products_t.product_id,m_products_t.concatenated_product,w_productionplan_lines_t.qty,w_productionplan_lines_t.qoh,w_productionplan_lines_t.pending_qty  FROM `w_productionplan_lines_t` join m_products_t on m_products_t.product_id=w_productionplan_lines_t.product_id  WHERE `productionplan_hdr_id` = $id and parent_product='".$result1[0]->product_id."'"); 
		
	$result1[0]->lines=$lines;
		
		
        $result['data']=collect($result1)->map(function($x){ return (array) $x; })->toArray();
      return $result;
}

public function createjobcard(Request $request){
      $posted_data= $request->json()->all();
      $data=[];
     
      $token = $request->header('token');
      $user_details = $this->helperService->getTokenUser($token);
      $id = $request->header('id');
      $prdid = $request->header('productid');
      $jobtype = $request->header('jobtype');
      $pqty = $request->header('qty');
      $prs = $request->header('prs');
      $sub = $request->header('sub');
      $orgid = $user_details->org_id;
    //   echo "SELECT productionplan_hdr_id,m_products_t.product_id,m_products_t.concatenated_product,w_productionplan_lines_t.qty,w_productionplan_lines_t.qoh,w_productionplan_lines_t.pending_qty  FROM `w_productionplan_lines_t` join m_products_t on m_products_t.product_id=w_productionplan_lines_t.product_id WHERE `productionplan_hdr_id` = $id and parent_product='".$prdid."'";die;
      $lines=	\DB::select("SELECT productionplan_hdr_id,m_products_t.product_id,m_products_t.concatenated_product,w_productionplan_lines_t.qty,w_productionplan_lines_t.qoh,w_productionplan_lines_t.pending_qty  FROM `w_productionplan_lines_t` join m_products_t on m_products_t.product_id=w_productionplan_lines_t.product_id WHERE `productionplan_hdr_id` = $id and parent_product='".$prdid."'");
        // dd($lines);
        $cmp=$user_details->company_id;
        $emp=$user_details->employee_id;
        $qohcheck = 1;
		foreach($lines as $lk=>$lv){
		    $prd = $lv->product_id;
		    $qoh= \DB::Select("select sum(f.qty-f.qtyy) as qty,f.product_id from 
		                            (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id 
		                                    FROM i_qoh_detail_t 
		                                    where product_id='".$prd."' and company_id='".$cmp."' 
		                                    GROUP by product_id,batch_number, subinventory_id, locator_id   
		                            UNION ALL 
	                                SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd 
	                                    from i_reservation_detail_t 
		                                where product_id='".$prd."' and company_id='".$cmp."' 
		                                GROUP by product_id, batch_number, subinventory_id, locator_id
		                          )f");
			if(isset($qoh[0]->qty)){
                $qoh= $qoh[0]->qty;
                if($qoh < 0 )$qoh=0;
            }else{
                $qoh=0;
            }
            if($qoh<=0){
                $qohcheck = 0;
            }
		}
		if($qohcheck!=0 || 1==1){
		    $data['message'] = "Success";
		    
            $this->modelname = new jobcard();
            
            $table = $this->modelname->getTableColumns();
            foreach($table as $key=>$val)
            {
                $data[$val]='';
            }
            $data['job_date'] = date("Y-m-d");
            if($sub=='hdr'){
                /*purpose: to create job card based on production plan hdr*/
                $planhdr= \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id',$id)->get();
                $planlines= \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$id)->where('parent_product',$prdid)->where('process_level','')->get();
                $planlinesnoprd= \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$id)->where('parent_product',$prdid)->where('product_id','=','0')->get();
                    
                if(count($planlinesnoprd)>0){
                    $data['noprd']="1";   
                }
                $group=\DB::table('m_products_t')->select('m_products_t.*','m_product_groups_t.*')->leftjoin('m_product_groups_t','m_product_groups_t.product_group_id','=','m_products_t.product_group_id')->where('m_products_t.product_id',$planhdr[0]->product_id)->get();
                if(isset($prs) && $prs!='' && $prs!=null){
                    if($prs=='FINALPROCESS'){
                        $this->data['kitpack_no']=$group[0]->mpq_qty;
                    }else{
                        $this->data['kitpack_no']=0;    
                    }
                }
                // dd($group);
                $data['group']=$group[0]->group_name;
                $sub_data= \DB::table('w_productionplan_lines_t')->select('w_productionplan_lines_t.product_id','m_product_groups_t.group_name')->leftjoin('m_products_t','m_products_t.product_id','=','w_productionplan_lines_t.product_id')->leftjoin('m_product_groups_t','m_product_groups_t.product_group_id','=','m_products_t.product_group_id')->where('w_productionplan_lines_t.productionplan_hdr_id',$planhdr[0]->productionplan_hdr_id)->where('w_productionplan_lines_t.parent_product',$planhdr[0]->product_id)->where('m_product_groups_t.group_name','SEMI FINISHED GOODS')->get();
                $a_in='';
                if(count($sub_data)>0){
                    foreach($sub_data as $key1=>$value1){
                        $a_in.="'$value1->product_id'".",";
                    }
                    $b_in=rtrim($a_in,',');
                    $comp=$cmp;
                    $product=\DB::select('select product_id,concatenated_product from m_products_t where product_id in('.$b_in.')');
                    $i=0;
                    $html = array();
                    foreach($product as $pk =>$pv){
                        
                        $qoh=\DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh  from i_qoh_detail_t where product_id=".$pv->product_id."   and company_id=".$comp." and subinventory_id='5' and locator_id='191' group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0" );      
                        if(count($qoh)>0){
                            foreach($qoh as $qk=>$qv){
                                $html[$i] = (object) array();
                                $prdname=$qv->batch_number."-".$pv->concatenated_product;
                                $html[$i]->product_name= $prdname;
                                $html[$i]->product_id= $pv->product_id;
                                $i++;
                            }                   
                        }
                    }
                    $data['bom_product_id']=$html;
                }else{
                    $product=\DB::select('select product_id,concat(product_code,'-',concatenated_product) as product_name from m_products_t');
                    $data['bom_product_id']=$product;
                }
                
                $prod=\DB::select("select product_id,concat(product_code,'-',concatenated_product) as product_name from m_products_t where product_id=".$planhdr[0]->product_id);
                
                $data['product_id']=$prod;
                $uom=\DB::select('select uom_code_id,uom_code from m_uom_codes_t where uom_code_id='.$planhdr[0]->uom_code_id);
                $data['uom_code_id']=$uom;
                $machineid=$this->getprdtype($planhdr[0]->product_id);
                
                if($machineid!=""){
                    $machine_hdr_id=\DB::select('select machine_hdr_id,machine_name from w_machine_hdr_t where machine_hdr_id in ('.$machineid.')');
                    $data['machine_hdr_id'] = $machine_hdr_id;
                }else{
                    $data['machine_hdr_id'] = "";
                }
                /***** vimala purpose : assign hour based on range start **/
                $hourdetail=    $this->machinehourdetails($machineid,$planhdr[0]->product_id,$planhdr[0]->production_qty);
                $data['hour'] = $hourdetail;
                /**** purpose : assign hour based on range end **/
                $data['machine_capacity']=""; 
                $data['seq_count'] = '';
                $data['job_qty']=$pqty;
                $data['bom_process']=$prs;
                 if($jobtype=="packingjobcard"){
                    if($prs==""){
                        $data['bom_process']=0;
                    }else{
                        $data['bom_process']=$prs;
                    }
                }
                // if($jobtype=="packingjobcard"){
                //     $data['bom_process']=0;
                // }
                if(count($planlines)>0){
                    $data['job_process']=$planlines[0]->process_name;
                }else{
                    $data['job_process']="";   
                }
                /*deepika purpose: to get batch no based on process level*/
                // dd($group[0]->group_name);
                if($group[0]->group_name!="FINISHED GOODS"){
                    $data['job_completed_qty']=0;
                    $data['job_adjusted_qty']=$pqty;
                    $jobprsdata=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                    if(count($jobprsdata)>0){
                        if($jobprsdata[0]->w_jobs_hdr_id!=null){
                            $qadata=\DB::table('w_qa_submitstage_trx_t')->where('job_no',$jobprsdata[0]->w_jobs_hdr_id)->get();
                            $data['process_completed_qty']=$jobprsdata[0]->job_adjusted_qty1;
                            $data['job_adjusted_qty']=$pqty;
                            if(count($qadata)>0){
                                $data['job_completed_qty']=$qadata[0]->production_qty;
                            }else{
                                $data['job_completed_qty']=0;
                            }
                        }
                    }
                }else{
                    if(($prs!="") && ($prs!='0')){
                        $jobprsdata=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='".$prs."' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                        if($prs=="PROCESS-2"){
                            $jobprs=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-1' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                        }else if($prs=="PROCESS-3"){
                            $jobprs=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-2' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                        }else if($prs=="PROCESS-4"){
                            $jobprs=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-3' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                        }else if($prs=="FINALPROCESS"){
                            $jobprs=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-4' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                            if($jobprs[0]->w_jobs_hdr_id==null){
                                $job=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." group by bom_process order by w_jobs_hdr_id desc");    
                                $jobprs=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='".$job[0]->bom_process."' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");   
                            }
                        }else{
                            $jobprs=array();
                            $data['job_adjusted_qty']=$pqty;
                            $data['job_completed_qty']=$pqty;
                        }
                        if(count($jobprs)>0){
                            $data['batch_no']=$jobprs[0]->batch_no;
                            $data['job_adjusted_qty']=$jobprs[0]->job_adjusted_qty1;
                            $data['job_completed_qty']=$jobprs[0]->job_adjusted_qty1;
                        }else{
                            $data['batch_no']="";
                            $data['job_adjusted_qty']=$pqty;
                            $data['job_completed_qty']=$pqty;
                        }
                        if(count($jobprsdata)>0){
                            $qadata=\DB::table('w_qa_submitstage_trx_t')->where('job_no',$jobprsdata[0]->w_jobs_hdr_id)->get();
                            $data['process_completed_qty']=$jobprsdata[0]->job_adjusted_qty1;
                            $data['job_adjusted_qty']=$pqty;
                            if(count($qadata)>0){
                                $data['job_completed_qty']=$qadata[0]->production_qty;
    						    $data['process_completed_qty']=$qadata[0]->production_qty;
                            }else{
                                $data['job_completed_qty']=0;
    						    $data['process_completed_qty']=0;
                            }
                        }else{
                            $data['process_completed_qty']=0;
                        }
                    }
                }
                $data['job_status']="OPEN";
                $data['reference_source'] ='PLAN';
                $data['reference_source_id'] =$planhdr[0]->productionplan_hdr_id;
                if(isset($pqty)){
                    $data['quantity_capacity']=str_replace(",","",$pqty);  
                }
            }else{
                /*purpose: to create job card based on production plan lines*/
                $planlines= \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$id)->where('product_id',$prdid)->get();
                $group=\DB::table('m_products_t')->select('m_products_t.*','m_product_groups_t.*')->leftjoin('m_product_groups_t','m_product_groups_t.product_group_id','=','m_products_t.product_group_id')->where('m_products_t.product_id',$prdid)->get();
                $this->data['group']=$group[0]->group_name;
                 if(isset($prs)){
                    if($prs=='FINALPROCESS'){
                        $data['kitpack_no']=$group[0]->mpq_qty;
                    }else{
                        $data['kitpack_no']=0;    
                    }
                }
                
                $planhdr1=\DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id',$id)->get();
                // dd($planlines);
                $sub_data= \DB::table('w_productionplan_lines_t')->select('w_productionplan_lines_t.product_id','m_product_groups_t.group_name')->leftjoin('m_products_t','m_products_t.product_id','=','w_productionplan_lines_t.product_id')->leftjoin('m_product_groups_t','m_product_groups_t.product_group_id','=','m_products_t.product_group_id')->where('w_productionplan_lines_t.productionplan_hdr_id',$planhdr1[0]->productionplan_hdr_id)->where('w_productionplan_lines_t.parent_product',$planlines[0]->product_id)->where('m_product_groups_t.group_name','SEMI FINISHED GOODS')->get();
                // dd($sub_data);
                $a_in='';
                // dd('k');
                if(count($sub_data)>0){
                    foreach($sub_data as $key1=>$value1){
                        $a_in.="'$value1->product_id'".",";
                    }
                    $b_in=rtrim($a_in,',');
                    $comp=$cmp;
                    $product=\DB::select('select product_id,concatenated_product from m_products_t where product_id in('.$b_in.')');
                    $i=0;
                    $html = array();
                    foreach($product as $pk =>$pv){
                        
                        $qoh=\DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh  from i_qoh_detail_t where product_id=".$pv->product_id."   and company_id=".$comp." group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0" );      
                        if(count($qoh)>0){
                            foreach($qoh as $qk=>$qv){
                                $html[$i] = (object) array();
                                $prdname=$qv->batch_number."-".$pv->concatenated_product;
                                $html[$i]->product_name= $prdname;
                                $html[$i]->product_id= $pv->product_id;
                                $i++;
                            }                   
                        }
                    }
                    $data['bom_product_id']=$html;
                }else{
                    $productt=\DB::select("select product_id,concat(product_code,'-',concatenated_product) as product_name from m_products_t");
                    $data['bom_product_id']=$productt;
                }
                
                $prod=\DB::select("select product_id,concat(product_code,'-',concatenated_product) as product_name from m_products_t where product_id=".$planlines[0]->product_id);
                $data['product_id']=$prod;
                $uom=\DB::select('select uom_code_id,uom_code from m_uom_codes_t where uom_code_id='.$planlines[0]->uom_code_id);
                $data['uom_code_id']=$uom;
                $machineid=$this->getprdtype($planlines[0]->product_id);
                
                if($machineid!=""){
                    $machine_hdr_id=\DB::select('select machine_hdr_id,machine_name from w_machine_hdr_t where machine_hdr_id in ('.$machineid.')');
                    $data['machine_hdr_id'] = $machine_hdr_id;
                }else{
                    $data['machine_hdr_id'] = "";
                }
                /***** vimala purpose : assign hour based on range start **/
                $hourdetail=    $this->machinehourdetails($machineid,$prdid,$planlines[0]->qty);
                $data['hour'] = $hourdetail;
                
                if($planlines[0]->production_qty!=0){
                    $data['job_qty']=$planlines[0]->pending_qty;
                    $data['job_adjusted_qty']=$planlines[0]->pending_qty;
                }else{
                    $data['job_qty']=$planlines[0]->qty;
                    $data['job_adjusted_qty']=$planlines[0]->qty;
                }
                $planlines1= \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$id)->where('parent_product',$prdid)->where('process_level',$prs)->get();
                if(!isset($planlines1)){
                    $data['job_process']=$planlines1[0]->process_name;
                }else{
                    $data['job_process']="";
                }
                $data['job_completed_qty']=0;
                /***** vimala purpose : assign hour based on range start **/
                $hourdetail=    $this->machinehourdetails($machineid,$prdid,$planlines[0]->qty);
                $data['hour'] = $hourdetail;
                 /**** purpose : assign hour based on range end **/
                $data['machine_capacity']="";
                /*deepika purpose: to get batch no based on process level*/
                if(isset($prs)){
                    if($group[0]->group_name=="FINISHED GOODS"){
                        if(($prs!="") && ($prs!='0')){
                            $jobprsdata=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='".$prs."' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                            if($prs=="PROCESS-2"){
                                $jobprs=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-1' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                            }else if($prs=="PROCESS-3"){
                                $jobprs=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-2' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                            }else if($prs=="PROCESS-4"){
                                $jobprs=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-3' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                            }else if($prs=="FINALPROCESS"){
                                $jobprs=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-4' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");
                                if($jobprs[0]->w_jobs_hdr_id==null){
                                    $job=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." group by bom_process order by w_jobs_hdr_id desc");    
                                    $jobprs=\DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='".$job[0]->bom_process."' and product_id=".$planhdr[0]->product_id." and reference_source_id=".$id." order by w_jobs_hdr_id desc");   
                                }
                            }else{
                                $jobprs=array();
                                $data['job_adjusted_qty']=$_GET['pqty'];
                                $data['job_completed_qty']=$_GET['pqty'];
                            }
                            if(count($jobprs)>0){
                                $data['batch_no']=$jobprs[0]->batch_no;
                                $data['job_adjusted_qty']=$jobprs[0]->job_adjusted_qty1;
                                $data['job_completed_qty']=$jobprs[0]->job_adjusted_qty1;
                            }else{
                                $data['batch_no']="";
                                $data['job_adjusted_qty']=$_GET['pqty'];
                                $data['job_completed_qty']=$_GET['pqty'];
                            }
                            if(count($jobprsdata)>0){
                                $data['process_completed_qty']=$jobprsdata[0]->job_adjusted_qty1;
                                $data['job_adjusted_qty']=$_GET['pqty'];
                            }else{
                                $data['process_completed_qty']=0;
                            }
                        }
                    }
                }else{
                    $data['batch_no']=""; 
                    $data['process_completed_qty']=0;
                }
                /*end*/
                $data['job_status']="OPEN";    
                $data['reference_source'] ='PLAN';
                $data['reference_source_id'] =$id;
                $data['bom_process']=$prs;
                if(isset($_GET['capacityqty'])){
                    $this->data['quantity_capacity']=$_GET['capacityqty'];  
                }
            }
            $data['organization_id'] = \DB::select('select organization_id,organization_name from m_organizations_t where organization_id='.$orgid);
            
            if($jobtype=='packingjobcard'){
                $wh1 = " and group_type=10";
            }else{
                $wh1 = " and group_type=8"; 
            }
            $data['job_assigned_to'] = \DB::select("select employee_id,concat(employee_number,'-',first_name) as employee_name from hr_employee_t where 1=1 $wh1");
            $data['job_created_by'] = \DB::select("select employee_id,concat(employee_number,'-',first_name) as employee_name from hr_employee_t where 1=1 and employee_id=$emp");
            $data['qa_submitstage_trx_hdr_id'] = "";
            
            $data['pagemode'] = "create";
		}else{
		    $data['message'] = "Qoh Not Available,Can't able to create Jobcard !!";
		}
// 		$result['data']=collect($data)->map(function($x){ return (array) $x; })->toArray();
      return $data;
}
    public function submitjobcard(Request $request){
        $posted_data= $request->json()->all();
        $data=[];
            // dd('ii');
        $token = $request->header('token');
        $jobtype = $request->header('jobtype');
        $user_details = $this->helperService->getTokenUser($token);
        
        $orgid = $user_details->org_id;
        $cmp = $user_details->company_id;
        $emp = $user_details->employee_id;
        $loc = $user_details->employee_id;
        $bomproduct = "";
        $primary  = self::findPrimarykey('w_jobcard_hdr_t');
        $jobcard=new jobcard();
        
        if($jobtype=="packingjobcard"){
            $seqno=$this->Seqno('JOBOP','w_jobcard_hdr_t','');
         }else{
            $seqno=$this->Seqno('JOBPR','w_jobcard_hdr_t','');
         }
        
        $jobcard->job_no = $seqno;
        $month=date('m');
        $year=date('y');
        // dd($posted_data);
        $sql=\DB::select('select * from w_jobcard_hdr_t where product_id='.$posted_data['product_id'].' order by w_jobs_hdr_id desc');
        if(count($sql)>0){
            $seq=$sql[0]->seq_count;
        }else{
            $seq=0;
        }
        
        $cat=\DB::table('m_products_t')->select('m_products_t.*','m_product_category_t.*')->leftjoin('m_product_category_t','m_product_category_t.product_category_id','=','m_products_t.product_category_id')->where('m_products_t.product_id',$posted_data['product_id'])->get();
        $catname="";
        if(count($cat)>0){
            $catname=$cat[0]->category_name;
        }   
        if($catname=="COSMETICS"){
            $seqno1=$this->BatchSeqno("V","/".$year,$seq,'w_jobcard_hdr_t','');
        }else{
            $seqno1=$this->BatchSeqno("","/".$year,$seq,'w_jobcard_hdr_t','');
        }
        
        if (isset($posted_data['batch_no']) && $posted_data['batch_no']!=""){
            $jobcard->batch_no = $posted_data['batch_no']; 
        }else{
            $jobcard->batch_no = $seqno1;
        }
        $sequence=explode("/",$seqno1);
        \DB::beginTransaction();
        try
        {
            if(isset($posted_data['w_jobs_hdr_id']) && ($posted_data['w_jobs_hdr_id']!="" || $posted_data['w_jobs_hdr_id']!="0"))
            {
                $jobcard->w_jobs_hdr_id=$posted_data['w_jobs_hdr_id'];
            }
            // dd($posted_data);
            $jobcard->job_date=$posted_data['job_date'];
            $jobcard->reworksrc='';
            if($posted_data['job_process']==null){$posted_data['job_process']='';}
            $jobcard->job_process=$posted_data['job_process'];
            if($posted_data['job_completion_date']==null){$posted_data['job_completion_date']='';}
            $jobcard->job_completion_date=date('Y-m-d',strtotime($posted_data['job_completion_date']));
            $jobcard->remarks=$posted_data['remarks'];
            $jobcard->product_id=$posted_data['product_id'];
            $jobcard->uom_code_id=$posted_data['uom_id'];
            $jobcard->job_qty=$posted_data['job_qty'];
            if(isset($posted_data['kitpack_no'])){
                $jobcard->kitpack_no=$posted_data['kitpack_no'];
            }else{
                $jobcard->kitpack_no="";
            }
            $jobcard->job_status=$posted_data['job_status'];
            $jobcard->bom_product_id=$bomproduct;
            $jobcard->job_adjusted_qty=$posted_data['job_adjusted_qty'];
            $jobcard->job_created_by=$posted_data['created_by_id'];
            $jobcard->reference_source=$posted_data['reference_source'];
            $jobcard->reference_source_id=$posted_data['reference_source_id'];
            $jobcard->machine_hdr_id=$posted_data['machine_id'];
            if($posted_data['machine_capacity']==null){$posted_data['machine_capacity']='';}
            $jobcard->machine_capacity=$posted_data['machine_capacity'];
            $jobcard->hour=$posted_data['hour'];
            // $assigned_to=implode(",",$posted_data['assigned_to_id']);
            $assigned_to=$posted_data['assigned_to_id'];
            $jobcard->job_assigned_to=$assigned_to;
            if($posted_data['remarks']==null){$posted_data['remarks']='';}
            $jobcard->remarks=$posted_data['remarks'];
            $jobcard->bom_process='';
            $jobcard->organization_id=$orgid;
            $jobcard->company_id=$cmp;
            $jobcard->location_id=$loc;     
            // dd($jobcard,$cmp);
            if($posted_data['w_jobs_hdr_id']==null){$posted_data['w_jobs_hdr_id']='';}
            $id=$this->mobinsertData($this->model,$primary,$jobcard,$posted_data['w_jobs_hdr_id'],'jobcard',$user_details); 
            // dd($id);
            if($catname=="COSMETICS"){
                $sequenceno=$sequence[1];       
            }else{
                $sequenceno=$sequence[0];   
            }                       
            \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->update(['seq_count' => $sequenceno]); 
            
            $p_id=explode(",",$posted_data['reference_source_id']);
            $product_id=$posted_data['product_id'];
            if($posted_data['job_adjusted_qty']==null || $posted_data['job_adjusted_qty']==''){$posted_data['job_adjusted_qty']=0;}
            if($jobtype!="packingjobcard"){
                foreach($p_id as $key=>$value)
                {   
                    $plan=\DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id='.$value.' and product_id='.$product_id);
                    if(count($plan)>0){
                        $planqty=$plan[0]->plan_qty+$posted_data['job_adjusted_qty'];
                        if($plan[0]->pending_qty==0){
                            $pendqty=$posted_data['job_adjusted_qty'];
                        }else{
                            $pendqty=$plan[0]->production_qty-$planqty;      
                        }
                        if($posted_data['bom_process']=='' ||  $posted_data['bom_process']=="FINALPROCESS"){
                            \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id',$value)->where('product_id',$posted_data['product_id'])->update(['plan_qty' => $planqty,'pending_qty'=>$pendqty]);
                        }
                    }
                    if($posted_data['bom_process']==''){
                        $planlines=\DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$value)->where('product_id',$posted_data['product_id'])->get();
                    }else{
                        $planlines=\DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$value)->where('parent_product',$posted_data['product_id'])->where('process_level',$posted_data['bom_process'])->get();        
                    }
                    if(count($planlines)>0){
                        $qty=$plan[0]->production_qty;
                        $prdqty=$planlines[0]->production_qty+$posted_data['job_adjusted_qty'];
                        if($planlines[0]->pending_qty==0){
                            $pendingqty=$planlines[0]->pending_qty-$posted_data['job_adjusted_qty'];
                        }else{
                            $pendingqty=$qty-$prdqty;
                        }
                        if($posted_data['bom_process']==''){
                            \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$value)->where('product_id',$posted_data['product_id'])->update(['production_qty' => $prdqty,'pending_qty'=>$pendingqty]);
                        }else{
                            \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$value)->where('parent_product',$posted_data['product_id'])->where('process_level',$posted_data['bom_process'])->update(['production_qty' => $prdqty,'pending_qty'=>$pendingqty]);        
                        }
                    }
                    if($posted_data['bom_process']=="FINALPROCESS"){
                        \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id',$value)->where('product_id',$product_id)->update(['job_card_status' => 1,'status'=>1]);
                    }else if($posted_data['bom_process']!="" && $posted_data['bom_process']!="FINALPROCESS" && $posted_data['bom_process']!="0"){
                        \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$value)->where('parent_product',$product_id)->where('process_level',$posted_data['bom_process'])->update(['job_card_status' => 1]);
                    }
                }
            }else{
                foreach($p_id as $key=>$value){
                    \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id',$value)->where('product_id',$product_id)->update(['job_card_status' => 1]);             
                 }
            }
                
            /*deepika purpose:jobcard reserve qoh*/
            $jobhdr=\DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id='.$id); 
            $planhdr=\DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id='.$jobhdr[0]->reference_source_id); 
            if($posted_data['reference_source']!="REWORK" && $posted_data['reference_source']!="RETURNREWORK"){
                $plan=\DB::table('w_productionplan_lines_t')->where('parent_product',$posted_data['product_id'])->where('productionplan_hdr_id',$jobhdr[0]->reference_source_id)->get();
                // dd($jobhdr[0]->reference_source_id,$posted_data['product_id']);
                foreach($plan as $key=>$value){
                    $comp=$cmp;
                    $opreserveqoh=\DB::select("SELECT reserv_trx_qty,product_id,reference_no from i_reservation_detail_t where product_id='".$value->product_id."' and company_id='".$comp."' and reference_no!='".$planhdr[0]->plan_no."' GROUP by reference_no ");   
                    $ref_no="";
                    foreach($opreserveqoh as $rk =>$rval){
                        $plandata=\DB::select("SELECT * FROM w_productionplan_hdr_t where plan_no='".$rval->reference_no."'");
                        if(count($plandata)>0){
                            $ref_no.=$plandata[0]->productionplan_hdr_id.",";
                        }
                    }
                    $ref_no1=rtrim($ref_no,",");
                    $refarray=explode(",",$ref_no1);
                    $jobcheckdata=\DB::table('w_jobcard_hdr_t')->select('reference_source_id')->whereIn('reference_source_id',explode(",",$ref_no1))->get();
                    $jobarray=array();
                    $job="";
                    foreach($jobcheckdata as $jk =>$jval){
                        $job.=$jval->reference_source_id.",";
                    }
                    $job1=rtrim($job,",");
                    $jobarray=explode(",",$job1);
                    $refdata=array_diff($refarray,$jobarray);
                    $refno="";
                    foreach($refdata as $dk =>$dval){
                        $plandata=\DB::select("SELECT * FROM w_productionplan_hdr_t where productionplan_hdr_id='".$dval."'");
                        if(count($plandata)>0){
                            $refno.="'".$plandata[0]->plan_no."'".",";
                        }
                    }
                    $refno1=rtrim($refno,",");
                    $rqty11=0;
                    if($refno1!=""){
                        $rqty1="";
                        $refno11=explode(",",$refno1);
                    }
                    
                    $trsnsType=\DB::table('m_transaction_types_t')->where('transaction_type_code','JOB CARD RESERVE')->get();
                    $trsnsType= json_decode( json_encode($trsnsType),true);
                    $data1['trx_source_type_id']=$trsnsType[0]['transaction_source_id'];
                    $data1['trx_action_id']=$trsnsType[0]['transaction_action_id'];
                    $data1['trx_type_id']=$trsnsType[0]['transaction_type_id'];
                    $data1['product_id']= $value->product_id;
                    $data1['trx_qty']=$value->component_qty*$posted_data['job_adjusted_qty'];
                    $data1['trx_source_hdr_id']= $id;
                    $data1['trx_reference']="JOB CARD RESERVE";
                    $data1['trx_uom']=$value->uom_code_id;
                    $prd=\DB::select("select * from m_products_t where product_id=".$value->product_id); 
                    if(!empty($prd[0]->subinventory_id )){
                        $data1['subinventory_id']=$prd[0]->subinventory_id;
                    }else{
                        $data1['subinventory_id']=0;
                    }
                    if(!empty($prd[0]->locator_id )){
                        $data1['locator_id']=$prd[0]->sublocator_id;
                    }else{
                        $data1['locator_id']=0;
                    }
                    $mtlid = \DB::table('m_material_trx_t')->insertGetId($data1);   
                    $data1['trx_qty']=-($value->component_qty*$posted_data['job_adjusted_qty']);
                    $id1 = \DB::table('m_material_trx_t')->insertGetId($data1);                  
    
                    $QOHdata['product_id']=  $value->product_id;
                    $QOHdata['reserv_trx_qty']=$value->component_qty*$posted_data['job_adjusted_qty'];
                    $QOHdata['reserv_uom_code_id']=$value->uom_code_id;
                    $QOHdata['reference_no']=$jobcard->job_no;
                    $QOHdata['reference_source']='JOBCARD RESERVE';
                    if(!empty($prd[0]->subinventory_id)){
                        $QOHdata['subinventory_id']=$prd[0]->subinventory_id;
                    }else{
                        $QOHdata['subinventory_id']=0;
                    }
                    if(!empty($prd[0]->sublocator_id)){
                        $QOHdata['locator_id']=$prd[0]->sublocator_id;
                    }else{
                        $QOHdata['locator_id']=0;
                    }
                    $QOHdata['organization_id']=$orgid;
                    $QOHdata['company_id']=$cmp;
                    $QOHdata['location_id']=$loc;
                    
                    $QOHdata['create_trx_id']= $mtlid;
                    \DB::table('i_reservation_detail_t')->insert($QOHdata);
                    $res=\DB::select('select * from i_reservation_detail_t where product_id='.$value->product_id.' and reference_no="'.$planhdr[0]->plan_no.'" and reference_source="MATERIAL PLAN"');
                    if(count($res)>0){
                        $QOHdata['reference_no']=$planhdr[0]->plan_no;
                        $QOHdata['reference_source']="MATERIAL PLAN";
                        $QOHdata['reserv_trx_qty']=-($value->component_qty*$posted_data['job_adjusted_qty']);
                        $QOHdata['create_trx_id']= $id1;
                        \DB::table('i_reservation_detail_t')->insert($QOHdata);
                    }else{
                        $tqty=$value->component_qty*$posted_data['job_adjusted_qty'];
                        $res_qty=0;
                        $resbal_qty=0;
                        $i=0;
                        if($refno1!=""){    
                            foreach($refno11 as $qk=>$qval){
                                $opreserveqty1=\DB::select("SELECT reserv_trx_qty,product_id,reference_no from i_reservation_detail_t where product_id='".$value->product_id."' and company_id='".$comp."' and reference_no=$qval GROUP by reference_no order by reference_no asc");   
                                if(count($opreserveqty1)>0){
                                    $rqty1=$opreserveqty1[0]->reserv_trx_qty;
                                }else{
                                    $rqty1=0;   
                                }
                                if($tqty<=$rqty1){
                                    $res_qty=$tqty; 
                                    break;
                                }else if($tqty>$rqty1){
                                    $res_qty=$rqty1;
                                    $resbal_qty=$res_qty+$resbal_qty;
                                }
                                $QOHdata1[$qk]['product_id']=  $value->product_id;
                                $QOHdata1[$qk]['reserv_uom_code_id']=$value->uom_code_id;
                                $QOHdata1[$qk]['reference_no']=str_replace("'","",$qval);
                                $QOHdata1[$qk]['reference_source']="MATERIAL PLAN";
                                $QOHdata1[$qk]['reserv_trx_qty']=-$res_qty;
                                $QOHdata1[$qk]['create_trx_id']= $id1;
                                $QOHdata1[$qk]['organization_id']=$orgid;
                                $QOHdata1[$qk]['company_id']=$cmp;
                                $QOHdata1[$qk]['location_id']=$loc;
                                
                                \DB::table('i_reservation_detail_t')->insert($QOHdata1[$qk]);
                            }
                        }
                    }
                }
            }
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'jobno'=>$seqno));
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
    
    function findPrimarykey( $table )
    {
    $primaryKey = '';
    foreach(\DB::select("show columns from ".$table." where extra like '%auto_increment%'") as $key)
    {
    $primaryKey = $key->Field;
    }
    return $primaryKey;
    }
 public function getprdtype($id=null){
   $sql=\DB::table('m_products_t')->where('m_products_t.product_id',$id)->get();

        if($sql->isNotEmpty()){
        $sql1=\DB::table('w_machine_hdr_t as m')->leftjoin('w_machine_lines_t as ml','m.machine_hdr_id','=','ml.machine_hdr_id')
            ->select('m.machine_hdr_id','ml.product_type_id','m.capacity','m.assigned_to')->where('ml.product_type_id',$sql[0]->product_type_id)->get();
        }
        if(!empty($sql1)){
            $machid="";
            foreach($sql1 as $key=>$value){
                $machid .=$value->machine_hdr_id.",";
            }
            $machid=rtrim($machid,',');
        return $machid;
        }else{
            return 0;
    }
    }
  public function machinehourdetails($machineid=null,$product_id=null,$production_qty=null){
    $sql =\DB::table('w_machine_equipments_hdr_t')
                    ->leftjoin('w_machine_equipments_lines_t','w_machine_equipments_lines_t.machine_equipments_hdr_id','=','w_machine_equipments_hdr_t.machine_equipments_hdr_id')
                    ->where('w_machine_equipments_hdr_t.machine_id',$machineid)
                    ->select('w_machine_equipments_hdr_t.*','w_machine_equipments_lines_t.*')
                    ->get();
            if($sql->isNotEmpty()){
                foreach($sql as $key=>$value){
                    if($value->range_from <= $production_qty && $value->range_to >= $production_qty){
                $hour = $value->hours;
                         return $hour;
            }
            }
            }else{
                $hour =0;
                 return $hour;
            }  
}
function mobinsertData($model_name,$primary,$data,$id,$module,$user_details)
{
    // dd($id);
if($id == '')
{
$data->save();
$id=\DB::getPdo()->lastInsertId();
$this->mobauditlog($id,$module,'create',$data,'',$user_details);
}
else
{
$model_name::find($id)->update($posted_data);
$this->mobauditlog($id,$module,'edit',$data,'',$user_details);
}
    return $id;
}

public function mobauditlog($id=null,$module=null,$action=null,$note=null,$table_name=null,$user_details=null){


        $orgid = $user_details->org_id;
        $cmp = $user_details->company_id;
        $emp = $user_details->employee_id;
        $loc = $user_details->employee_id;
        
      $data['ipaddress']=$_SERVER['REMOTE_ADDR'];
      $data['primary_id']=$id;
      $data['module']=$module;
      $data['action']=$action;
      $data['note']=json_encode($note);
      $data['table_name']=$table_name;
      $data['company_id']=$cmp;
      $data['location_id']=$loc;
      $data['organization_id']=$orgid;
      $data['created_by']=$emp;
      $data['created_at']=date('Y-m-d H:i:s');
      $data['last_updated_by']=$emp;
      $data['last_updated_at']=date('Y-m-d H:i:s');
          \DB::table('tb_logs')->insert($data);
}

public function productionjobcardstatus(Request $request){
     $posted_data= $request->json()->all();
     $token = $request->header('token');
     $user_details = $this->helperService->getTokenUser($token);
     $wh='';
     $prdgrp="";
    // $wh.=$grid_data=$this->grid_check('t1','job_completion_date'); 
     
     $prdgrp.=" and m_products_t.product_group_id='4' ";  
    
     $result=\DB::select("select * from(SELECT
    w_jobcard_hdr_t.w_jobs_hdr_id,
    case when w_jobcard_hdr_t.job_status='QA SUBMITTED' then 'COMPLETED' else w_jobcard_hdr_t.job_status end as job_status,
    w_jobcard_hdr_t.job_no,
    w_jobcard_hdr_t.job_date,
    w_jobcard_hdr_t.job_completion_date,
    w_jobcard_hdr_t.remarks,
    w_jobcard_hdr_t.job_adjusted_qty,
    w_jobcard_hdr_t.job_qty,
    w_jobcard_hdr_t.product_id,
    w_jobcard_hdr_t.reference_source_id,
w_jobcard_hdr_t.store_move_qty,
(select hr_employee_t.first_name from hr_employee_t where hr_employee_t.employee_id=w_jobcard_hdr_t.job_created_by) as first_name,
    w_jobcard_hdr_t.batch_no,
     m_products_t.concatenated_product,
     m_products_t.product_code,
    (select m_uom_codes_t.uom_code from m_uom_codes_t where m_uom_codes_t.uom_code_id= w_jobcard_hdr_t.uom_code_id) as uom_code,
    COALESCE(w_qa_submitstage_trx_t.production_qty,0) as production_qty,
	   (COALESCE(w_jobcard_hdr_t.job_adjusted_qty,0)-COALESCE(w_qa_submitstage_trx_t.production_qty,0)) as balancejob_qty,
    (select w_productionplan_hdr_t.plan_no from w_productionplan_hdr_t where w_productionplan_hdr_t.productionplan_hdr_id= w_jobcard_hdr_t.reference_source_id) as plan_no,
    w_jobcard_hdr_t.job_process,w_jobcard_hdr_t.company_id,w_jobcard_hdr_t.location_id
FROM
    w_jobcard_hdr_t
 JOIN m_products_t ON(
        w_jobcard_hdr_t.product_id = m_products_t.product_id
    )


    left join w_qa_submitstage_trx_t on(w_qa_submitstage_trx_t.job_no= w_jobcard_hdr_t.w_jobs_hdr_id)
    
    where 1=1 $prdgrp group by w_jobcard_hdr_t.w_jobs_hdr_id ORDER BY w_jobcard_hdr_t.w_jobs_hdr_id desc) as t1  where 1=1 ");
	
	// $result['data']=collect($result)->map(function($x){ return (array) $x; })->toArray();
	$result1['data']=$result;
      return $result1;
}

}
?>