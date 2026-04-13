<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use App\Http\Requests;
use PDO;
use DB;

class BoilerHelper{

	public function getUserUniqueToken(){
		do {
			$token = md5(rand());
			$tokenDetails = DB::table('tb_users')
							->select('*')
							->where('m_token', $token)
							->first();
		} while ($tokenDetails!=null);
		return $token;
	}

	public function getTokenUser($token){
		$users = DB::table('tb_users')
						->select('*')
						->where('m_token', $token)
						->first();
		if($users==null){
			$data['message']="Invalid Token";
			response()->json($data)->send();
			die();
		}else{
			return $users;
		}
	}

	/*public function getUser($posted_data){
		date_default_timezone_set('Asia/Kolkata');
		$data = DB::table('tb_users')
						->select('*')
						->where('username', $posted_data['username'])
						->first();
		$status = password_verify($posted_data['password'], $data->password);
		if($data!=null){
			if($status == true) {
				if(isset($posted_data['fcm_token'])){
					$update['fcm_token']=$posted_data['fcm_token'];
					if(trim($data->m_token)==''){
						$update['m_token']=$this->getUserUniqueToken();
					}
					DB::table('tb_users')
			            ->where('id', $data->id)
			            ->update($update);
					$data =  DB::table('tb_users')
							->select('*')
							->where('username', $posted_data['username'])
							->first();
				}
				return $data;
			}else{
				$data2['message']="Invalid Username/Password";
				return $data2;
			}
		}
	}*/

	public function getUser($posted_data){
		
		$username=$posted_data['username'];
			$password=$posted_data['password'];
		
		date_default_timezone_set('Asia/Kolkata');
		
		
		$data=DB::table('tb_users')
						->select('*')
						->where('username', $posted_data['username'])
						->first();

			$status = password_verify($posted_data['password'], $data->password);
			
					
		// $status = password_verify($posted_data['password'], $data->password);
			if($data!=null){
			if($status == true) {
				if(isset($posted_data['fcm_token'])){
					$update['fcm_token']=$posted_data['fcm_token'];
					if(trim($data->m_token)==''){
						$update['m_token']=$this->getUserUniqueToken();
					}					
					DB::table('tb_users')
			            ->where('id', $data->id)
			            ->update($update);				
					$data =  DB::table('tb_users')
							->select('*')
							->where('username', $posted_data['username'])
							->first();
				}
				$data->message = "Login Success";
				return $data;
			}
		}else{
			$data2['message']="Invalid Username/Password";
			return $data2;
		}
	}

	public function saleOrderList($emp_id){
	
		$saleOrderList=DB::select("select s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,if(s_salesorder_hdr_t.ship_to_customer_id=0,hr_employee_t.first_name,m_customers_t.customer_name)as customer_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_hdr_t.source,s_salesorder_hdr_t.order_total,s_salesorder_hdr_t.order_tax,i_pricelist_hdr_t.pricelist_hdr_id,i_pricelist_hdr_t.pricelist_name from s_salesorder_hdr_t left join m_customers_t on m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id left join i_pricelist_hdr_t on i_pricelist_hdr_t.pricelist_hdr_id=s_salesorder_hdr_t.pricelist_id left join hr_employee_t on hr_employee_t.employee_id=s_salesorder_hdr_t.employee_id where order_status_id='DRAFT' and  (s_salesorder_hdr_t.approver_id like '[".$emp_id.",%' or s_salesorder_hdr_t.approver_id like ',".$emp_id."%]' or s_salesorder_hdr_t.approver_id like '[".$emp_id."]' or s_salesorder_hdr_t.approver_id like '%,".$emp_id.",%')");					
							
		
	}
	
	public function apisaleOrderList($emp_id){
	
// 		$saleOrderList=DB::select("select s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,if(s_salesorder_hdr_t.ship_to_customer_id=0,hr_employee_t.first_name,m_customers_t.customer_name)as customer_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_hdr_t.source,s_salesorder_hdr_t.order_total,s_salesorder_hdr_t.order_tax,i_pricelist_hdr_t.pricelist_hdr_id,i_pricelist_hdr_t.pricelist_name from s_salesorder_hdr_t left join m_customers_t on m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id left join i_pricelist_hdr_t on i_pricelist_hdr_t.pricelist_hdr_id=s_salesorder_hdr_t.pricelist_id left join hr_employee_t on hr_employee_t.employee_id=s_salesorder_hdr_t.employee_id where   order_status_id='INITIATED' and  (s_salesorder_hdr_t.approver_id ='".$emp_id."')");	
		$saleOrderList=DB::select("select s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,if(s_salesorder_hdr_t.ship_to_customer_id=0,hr_employee_t.first_name,m_customers_t.customer_name)as customer_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_hdr_t.source,s_salesorder_hdr_t.order_total,s_salesorder_hdr_t.order_tax,i_pricelist_hdr_t.pricelist_hdr_id,i_pricelist_hdr_t.pricelist_name from s_salesorder_hdr_t left join m_customers_t on m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id left join i_pricelist_hdr_t on i_pricelist_hdr_t.pricelist_hdr_id=s_salesorder_hdr_t.pricelist_id left join hr_employee_t on hr_employee_t.employee_id=s_salesorder_hdr_t.employee_id where   (order_status_id='INITIATED' or order_status_id='DRAFT')   and  (s_salesorder_hdr_t.approver_id like '[".$emp_id.",%' or s_salesorder_hdr_t.approver_id like ',".$emp_id."%]' or s_salesorder_hdr_t.approver_id like '[".$emp_id."]' or s_salesorder_hdr_t.approver_id like '%,".$emp_id.",%')");	
							
		
		return $saleOrderList;
	}
	
		public function saleOrderListnew($emp_id){
	
		$saleOrderList=DB::select("select s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,if(s_salesorder_hdr_t.ship_to_customer_id=0,hr_employee_t.first_name,m_customers_t.customer_name)as customer_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_hdr_t.source,s_salesorder_hdr_t.order_total,s_salesorder_hdr_t.order_tax,i_pricelist_hdr_t.pricelist_hdr_id,i_pricelist_hdr_t.pricelist_name from s_salesorder_hdr_t left join m_customers_t on m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id left join i_pricelist_hdr_t on i_pricelist_hdr_t.pricelist_hdr_id=s_salesorder_hdr_t.pricelist_id left join hr_employee_t on hr_employee_t.employee_id=s_salesorder_hdr_t.employee_id where      (s_salesorder_hdr_t.created_by ='".$emp_id."')");					
							
		
		return $saleOrderList;
	}
		public function apisaleOrderListnew($emp_id){
	
		$saleOrderList=DB::select("select s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,if(s_salesorder_hdr_t.ship_to_customer_id=0,hr_employee_t.first_name,m_customers_t.customer_name)as customer_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_hdr_t.source,s_salesorder_hdr_t.order_total,s_salesorder_hdr_t.order_tax,i_pricelist_hdr_t.pricelist_hdr_id,i_pricelist_hdr_t.pricelist_name from s_salesorder_hdr_t left join m_customers_t on m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id left join i_pricelist_hdr_t on i_pricelist_hdr_t.pricelist_hdr_id=s_salesorder_hdr_t.pricelist_id left join hr_employee_t on hr_employee_t.employee_id=s_salesorder_hdr_t.employee_id where      s_salesorder_hdr_t.created_by ='".$emp_id."' or  (s_salesorder_hdr_t.approver_id like '[".$emp_id.",%' or s_salesorder_hdr_t.approver_id like ',".$emp_id."%]' or s_salesorder_hdr_t.approver_id like '[".$emp_id."]' or s_salesorder_hdr_t.approver_id like '%,".$emp_id.",%')");					
							
		
		return $saleOrderList;
	}
	
	public function soCancelList(){
		$soCancelList= DB::table('s_salesorder_hdr_t')
							->where('order_status_id','APPROVED')
							->join('m_customers_t','m_customers_t.customer_id','=','s_salesorder_hdr_t.ship_to_customer_id')
							->leftJoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','s_salesorder_hdr_t.pricelist_id')
							->orderBy('s_salesorder_hdr_t.sales_hdr_id', 'desc')
							->get();
		return $soCancelList;
	}

	public function purchaseQualityList(){
		$purchaseQualityList= DB::table('p_qc_header_t')
							->where('qc_status','INITIATED')
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_qc_header_t.supplier_id')
							->orderBy('p_qc_header_t.qc_header_id', 'desc')
							->get();
		return $purchaseQualityList;
	}

	public function saleOrder($data){
		$saleOrder = DB::table('s_salesorder_hdr_t')
							->leftjoin('m_customers_t','m_customers_t.customer_id','=','s_salesorder_hdr_t.ship_to_customer_id')
							->leftJoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','s_salesorder_hdr_t.pricelist_id')
							->where('sales_hdr_id','=',$data['sales_hdr_id'])
							->first();
		$saleOrder->item_order = DB::table('s_salesorder_lines_t')
							->join('m_products_t','m_products_t.product_id','=','s_salesorder_lines_t.product_id')
							->where('sales_hdr_id','=',$data['sales_hdr_id'])
							->get();
		return $saleOrder;
	}

	public function soCancel($data){
		$soCancel = DB::table('s_salesorder_hdr_t')
							->leftJoin('m_customers_t','m_customers_t.customer_id','=','s_salesorder_hdr_t.ship_to_customer_id')
							->leftJoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','s_salesorder_hdr_t.pricelist_id')
							->where('sales_hdr_id','=',$data['sales_hdr_id'])
							->first();
		$soCancel->item_order = DB::table('s_salesorder_lines_t')
							->join('m_products_t','m_products_t.product_id','=','s_salesorder_lines_t.product_id')
							->where('sales_hdr_id','=',$data['sales_hdr_id'])
							->get();
		return $soCancel;
	}

	public function saleInvoice($data){
		$saleInvoice = DB::table('s_invoice_hdr_t')
							->join('m_customers_t','m_customers_t.customer_id','=','s_invoice_hdr_t.ship_to_customer_id')
							->leftJoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','s_invoice_hdr_t.invoice_pricelist_id')
							->where('invoice_hdr_id','=',$data['invoice_hdr_id'])
							->first();
		$saleInvoice->item_order = DB::table('s_invoice_lines_t')
							->join('m_products_t','m_products_t.product_id','=','s_invoice_lines_t.product_id')
							->where('invoice_hdr_id','=',$data['invoice_hdr_id'])
							->get();
		return $saleInvoice;
	}

	public function purchaseInvoice($data){
		$purchaseInvoice = DB::table('p_po_invoice_hdr_t')
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_po_invoice_hdr_t.supplier_id')
							->leftJoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','p_po_invoice_hdr_t.invoice_pricelist_id')
							->where('po_invoice_id','=',$data['po_invoice_id'])
							->first();
		$purchaseInvoice->item_order = DB::table('p_po_invoice_lines_t')
							->join('m_products_t','m_products_t.product_id','=','p_po_invoice_lines_t.product_id')
							->where('po_invoice_id','=',$data['po_invoice_id'])
							->get();
		return $purchaseInvoice;
	}

	public function purchaseReturn($data){
		$purchaseReturnList = DB::table('p_return_header_t')
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_return_header_t.supplier_id')
							->where('return_header_id','=',$data['return_header_id'])
							->first();
		$purchaseReturnList->item_order = DB::table('p_return_lines_t')
							->join('m_products_t','m_products_t.product_id','=','p_return_lines_t.product_id')
							->where('return_header_id','=',$data['return_header_id'])
							->get();
		return $purchaseReturnList;
	}

	public function purchaseOrder($data){
		$purchaseOrder = DB::table('p_po_hdr_t')
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_po_hdr_t.supplier_id')
							->leftJoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','p_po_hdr_t.po_pricelist_id')
							->where('po_hdr_id','=',$data['po_hdr_id'])
							->first();
		$purchaseOrder->item_order = DB::table('p_po_lines_t')
							->join('m_products_t','m_products_t.product_id','=','p_po_lines_t.product_id')
							->where('po_hdr_id','=',$data['po_hdr_id'])
							->get();
		return $purchaseOrder;
	}
	
	public function purchaseQuality($data){
		$purchaseQuality = DB::table('p_qc_header_t')
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_qc_header_t.supplier_id')
							->where('qc_header_id','=',$data['qc_header_id'])
							->first();
		$purchaseQuality->item_order = DB::table('p_po_lines_t')
							->join('m_products_t','m_products_t.product_id','=','p_po_lines_t.product_id')
							->where('qc_header_id','=',$data['qc_header_id'])
							->get();
		return $purchaseQuality;
	}
	
	public function poCancel($data){
		$poCancelList = DB::table('p_po_hdr_t')
							->leftjoin('m_supplier_t','m_supplier_t.supplier_id','=','p_po_hdr_t.supplier_id')
							->leftJoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','p_po_hdr_t.po_pricelist_id')
							->where('po_hdr_id','=',$data['po_hdr_id'])
							->first();

		$poCancelList->item_order = DB::table('p_po_lines_t')
							->join('m_products_t','m_products_t.product_id','=','p_po_lines_t.product_id')
							->where('po_hdr_id','=',$data['po_hdr_id'])
							->get();

		return $poCancelList;
	}

	public function purchaseReq($data){
		$purchaseReq = DB::table('p_requisition_hdr_t')
							->where('requisition_hdr_id','=',$data['requisition_hdr_id'])
							->first();
		$purchaseReq->item_order = DB::table('p_requisition_lines_t')
							->join('m_products_t','m_products_t.product_id','=','p_requisition_lines_t.product_id')
							->where('p_requisition_lines_t.requisition_hdr_id','=',$data['requisition_hdr_id'])
							->get();
		return $purchaseReq;
	}

	public function purchaseQuote($data){
		$purchaseQuoteList = DB::table('p_quotation_hdr_t')
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_quotation_hdr_t.supplier_id')
							->leftJoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','p_quotation_hdr_t.quote_pricelist_id')
							->where('quotation_hdr_id','=',$data['quotation_hdr_id'])
							->first();
		$purchaseQuoteList->item_order = DB::table('p_quotation_lines_t')
							->join('m_products_t','m_products_t.product_id','=','p_quotation_lines_t.product_id')
							->where('quotation_hdr_id','=',$data['quotation_hdr_id'])
							->get();
		return $purchaseQuoteList;
	}

	public function salesQuote($data){
		$salesQuote = DB::table('s_quote_hdr_t')
							->join('m_customers_t','m_customers_t.customer_id','=','s_quote_hdr_t.customerid')
							->leftJoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','s_quote_hdr_t.quote_pricelist_id')
							->where('quote_hdr_id','=',$data['quote_hdr_id'])
							->first();

		$salesQuote->item_order = DB::table('s_quote_lines_t')
							->join('m_products_t','m_products_t.product_id','=','s_quote_lines_t.product_id')
							->where('quote_hdr_id','=',$data['quote_hdr_id'])
							->get();
		return $salesQuote;
	}

	public function purchaseOrderList(){
		$purchaseOrderList= DB::table('p_po_hdr_t')
							->where('po_status','INITIATED')	
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_po_hdr_t.supplier_id')	
							->get();
		return $purchaseOrderList;
	}

	public function poCancelList(){
		$poCancelList= DB::table('p_po_hdr_t')
							->where('po_status','APPROVED')
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_po_hdr_t.supplier_id')	
							->get();
		return $poCancelList;
	}


	public function purchaseReqList(){
		$purchaseReqList= DB::table('p_requisition_hdr_t')
							->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','p_requisition_hdr_t.requestor_id')	
							->leftjoin('p_requisition_lines_t','p_requisition_lines_t.requisition_hdr_id','=','p_requisition_hdr_t.requisition_hdr_id')
							->select('p_requisition_hdr_t.*','hr_employee_t.*')
							->selectRaw('p_requisition_lines_t.qty as qty')
							->where('p_requisition_hdr_t.requisition_status','INITIATED')
							->groupBY('p_requisition_hdr_t.requisition_hdr_id')
							->get();
		return $purchaseReqList;
	}

	public function purchaseQuoteList(){
		$purchaseQuoteList= DB::table('p_quotation_hdr_t')
							->where('quote_status','INITIATED')	
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_quotation_hdr_t.supplier_id')	
							->get();
		return $purchaseQuoteList;
	}

	public function salesQuoteList(){
		$salesQuoteList= DB::table('s_quote_hdr_t')
							->where('quote_status','INITIATED')	
							->join('m_customers_t','m_customers_t.customer_id','=','s_quote_hdr_t.customerid')	
							->get();
		return $salesQuoteList;
	}

	public function saleInvoiceList(){
		$saleInvoiceList= DB::table('s_invoice_hdr_t')
							->where('invoice_status','INITIATED')		
							->join('m_customers_t','m_customers_t.customer_id','=','s_invoice_hdr_t.ship_to_customer_id')	
							->orderBy('s_invoice_hdr_t.invoice_hdr_id', 'desc')			
							->get();
		return $saleInvoiceList;
	}

	public function purchaseInvoiceList(){
		$purchaseInvoiceList= DB::table('p_po_invoice_hdr_t')
							->where('po_invoice_status','INITIATED')	
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_po_invoice_hdr_t.supplier_id')	
							->get();
		return $purchaseInvoiceList;
	}

	public function purchaseReturnList(){
		$purchaseReturnList= DB::table('p_return_header_t')
							->join('m_supplier_t','m_supplier_t.supplier_id','=','p_return_header_t.supplier_id')	
							->where('p_return_header_t.p_return_status','INITIATED')
							->get();
		return $purchaseReturnList;
	}

	public function sendNotification($data,$userDetails){
		define('FIREBASE_API_KEY', 'AAAA6yke-Eo:APA91bHOjlO2JnvUkTVeCmw3PMBCXhuAzFhVelDWblIXQpR8LKWHHJ3nD4To8H0Ew9cegRkliu30F37BdCgXOYuZli2tJdT-yDpcd525nxX6UDEHtQbCR0eMrLqpcpg0opertUkp4_fcGAnrX69tXuy4k8F1FFrlPg');
		$tb_users = DB::table('tb_users')
						->select('*')
						->get();
		$message = [];
		foreach($tb_users as $key=>$user){
			$res['slug']="shipping_notices";
			$res['data']=$user;
			$message[] = $this->fcmMsgNotification($user->fcm_token,$res);
		}
		return $message;
	}

	public function fcmMsgNotification($token,$res){
		$fields = array(
			'to' => $token,
			'data' => $res
		);
		// Set POST variables
		$url = 'https://fcm.googleapis.com/fcm/send';
		$headers = array(
			'Authorization: key=' . FIREBASE_API_KEY,
			'Content-Type: application/json'
		);
		// Open connection
		$ch = curl_init();
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		// Execute post
		$result = curl_exec($ch);
		if ($result === FALSE) {
		die('Curl failed: ' . curl_error($ch));
		}
		// Close connection
		curl_close($ch);
		// echo $result;
		return $result;
	}
	
}
?>
