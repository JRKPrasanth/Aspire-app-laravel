<?php 
namespace App\Http\Controllers;
use App\Helpers\BoilerHelper;

use App\Http\Controllers\Controller;
use App\User;
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

	public function saleOrderList(Request $request){
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
	$customer['product_list']=DB::table('i_pricelist_lines_t')->leftjoin('m_products_t','m_products_t.product_id','=','i_pricelist_lines_t.product_id')->select('m_products_t.product_code','m_products_t.concatenated_product','i_pricelist_lines_t.product_id')->where('i_pricelist_lines_t.pricelist_hdr_id',$customerdata[0]->pricelist_id)->where('i_pricelist_lines_t.active','Yes')->GroupBy('i_pricelist_lines_t.product_id')->get();	
	
		$customer['message']="success";
return response()->json($customer);
}

public function createsoorderlist(Request $request){

	$customer['product_list']=DB::table('i_pricelist_lines_t')->leftjoin('m_products_t','m_products_t.product_id','=','i_pricelist_lines_t.product_id')->select('m_products_t.product_code','m_products_t.concatenated_product','i_pricelist_lines_t.product_id')->where('i_pricelist_lines_t.pricelist_hdr_id',176)->where('i_pricelist_lines_t.active','Yes')->GroupBy('i_pricelist_lines_t.product_id')->OrderBy('m_products_t.concatenated_product','asc')->get();	
	
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
dd($id);
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
   //   dd($user_details);
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
      
      $bill_id=DB::table('m_customer_sites_t')->where('customer_id',$posted_data['ship_to_customer_id'])
      ->where('site_type','BILL_TO')->where('active','Yes')->get();
     //	dd($bill_id);
     		if(!empty($bill_id))
		{
      $data['bill_to_address_id']=$bill_id[0]->customer_site_id;
      }
      else
      {
          $data['bill_to_address_id']='';  
      }
      
         $ship_id=DB::table('m_customer_sites_t')->where('customer_id',$posted_data['ship_to_customer_id'])
         ->where('site_type','SHIP_TO')->where('active','Yes')->get();
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
      if($posted_data['contact_person']!=null){
        $data['contact_person']=$posted_data['contact_person'];
      }else{
        $data['contact_person']=0;
      }
      $data['invoice_currency']=37;
      $data['freight_carrier_id']=$posted_data['freight_carrier_id'];
            $data['pricelist_id']=$posted_data['pricelist_id'];
            $data['discount_id']=$posted_data['discount_id'];
      $data['ship_to_customer_id']=$posted_data['ship_to_customer_id'];
      $data['delivery_date']=$posted_data['delivery_date'];
      //$data['tax_amount']=$posted_data['tax_amount'];
    // dd($posted_data['ship_to_customer_id']);
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
      foreach($posted_data['so_lines'] as $key=>$value){
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
           
           $schm=array();
           
          foreach($scheme as $sk=>$sv){
              $schm = $this->schemesavailables($value['product_id'],$value['qty'],$value['unit_price'],$sv);
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
           
           	$approverid= \DB::select("SELECT reporting_manager FROM `hr_employee_t` where employee_id=".$user_details->employee_id);
			
	        
				
	        $approver=$approverid[0]->reporting_manager;
	        
	        
           $update = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$id)->update(['order_total'=>$tot,'qty_total'=>$qty_total,'order_tax'=>$tax_tot,'approver_id'=>$approver]);

           
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
           
           	$approverid= \DB::select("SELECT reporting_manager FROM `hr_employee_t` where employee_id=".$user_details->employee_id);
			
	        
				
	        $approver=$approverid[0]->reporting_manager;
	        
	        
           $update = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$id)->update(['order_total'=>$tot,'qty_total'=>$qty_total,'order_tax'=>$tax_tot,'approver_id'=>$approver]);

           
      $datas['message']="Sales order created succesfully";
      return $datas;
     }
}
?>