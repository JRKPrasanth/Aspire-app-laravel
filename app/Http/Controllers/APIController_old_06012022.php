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
		 
		 if (\Auth::attempt(array('username'=>$posted_data['username'], 'password'=> $posted_data['password'] ), $remember )) {
		 	if(\Auth::check()){
		 		$data = (object)array();

		 		$row = User::find(\Auth::user()->id);
				$id=$row['id'];
	            $token = md5(rand());
	            \DB::select("update tb_users set m_token='$token' where id='$id'");
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
		$data['sale_order_list'] = $this->helperService->saleOrderList();
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

}
?>