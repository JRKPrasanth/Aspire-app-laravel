<?php

namespace App\Http\Controllers;

use App\notification;
use Illuminate\Http\Request;
use DB;
class NotificationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['user_id'] = $this->jcustommultiselect('tb_users','id','first_name','','');   
       
        return view('notificationsetting.table',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		if($_POST['id']==""){
		
            $data['module_type']=$_POST['type'];
            $data['user_id']=json_encode($_POST['user_id']);
            $data['duration']=$_POST['duration'];
            $data['email_duration']=isset($_POST['email_duration'])? $_POST['email_duration'] : '';
            $data['email_user_id']=isset($_POST['email_user_id'])? json_encode($_POST['email_user_id']) : '';
            
            \DB::table('nofication_user_tbl')->insert($data);	        
            return 1;

	    } else{
		
	        $data['user_id']=json_encode($_POST['user_id']);
            $data['module_type']=$_POST['type'];
            $data['duration']=$_POST['duration'];
            $data['email_duration']=isset($_POST['email_duration'])? $_POST['email_duration'] : '';
            $data['email_user_id']=isset($_POST['email_user_id'])? json_encode($_POST['email_user_id']) : '';
     
    		\DB::table('nofication_user_tbl')->where('id',$_POST['id'] )->update($data);            
            return 2;
	    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data =\DB::select("select * from notification_module_t where module_id='$id'");
		$html='';
		if(!empty($data)){
	        foreach($data as $k=>$v){
		          
		        $html.='<div class="col-md-3">
                        <div class="productsetting">
                            <h3 for="inputIsValid" class="productsettingtitle">'.$v->module_name.'
                            <span class="ui_close_btn"><a class="fa fa-cogs config" data-value="'.$v->module_name.'" ></a></span>
                            </h3>
                        </div>
                        </div>';			
		    }
		}

		return $html;		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, notification $notification)
    {


        $datas=\DB::select("select * from nofication_user_tbl where module_type='".$_GET['type']."'");
        $noty=\DB::select("select * from notification_module_t where module_name='".$_GET['type']."'");

        if(count($datas) > 0){
			$data['id']=$datas[0]->id;
			$data['user_id']=implode(',',json_decode($datas[0]->user_id));
			$data['email_user_id']= (($datas[0]->email_user_id)!='') ? implode(',',json_decode($datas[0]->email_user_id)) : '' ;
			$data['email_duration']=$datas[0]->email_duration;
			$data['duration']=$datas[0]->duration;
			
		}else{
		    
			$data['id']='';
            $data['user_id']='';
            $data['email_user_id']='';
            $data['email_duration']='';
            $data['duration']='';
		}

        if(count($noty) > 0){
            $folder =$noty[0]->folder_name;

            if($folder == ""){
                $data['display'] = 1;
            }else{
                $data['display'] = 2;
            }
        }

		return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(notification $notification)
    {
        //
    }
	
	public function Productstock(){
	$data=\DB::select("select sum(pq.qoh) as qoh,pq.product_id,pq.concatenated_product,(CASE WHEN SUM(pq.`qoh`) <  sum(pq.min_stock_level1)
    THEN
    sum(pq.min_stock_level1)
     WHEN SUM(pq.`qoh`)< sum(pq.min_stock_level2)
     THEN
    sum(pq.min_stock_level2) 
     WHEN  SUM(pq.`qoh`) <  sum(pq.min_stock_level3)
     then
    sum(pq.min_stock_level3)
     END) as stock_level from (SELECT
sum(`qoh_trx_qty`) as qoh,
0 as min_stock_level1,
0 as min_stock_level2,
0 as min_stock_level3,
i_qoh_detail_t.product_id   ,
 m_products_t.concatenated_product
FROM
    `i_qoh_detail_t` left join m_products_t on(m_products_t.product_id=i_qoh_detail_t.product_id and (m_products_t.min_order_qty!=0 or m_products_t.min_stock_level2!=0 or m_products_t.min_stock_level3!=0)) where 1=1 and m_products_t.product_group_id not in(1,4) group by i_qoh_detail_t.product_id
   UNION ALL
  SELECT 0 as qoh,m_products_t.min_order_qty as min_stock_level1,m_products_t.min_stock_level2,m_products_t.min_stock_level3,m_products_t.product_id,m_products_t.concatenated_product FROM m_products_t      where 1=1 and m_products_t.product_group_id not in(1,4) and (m_products_t.min_order_qty!=0 or m_products_t.min_stock_level2!=0 or m_products_t.min_stock_level3!=0) group by m_products_t.product_id      
) as pq  where 1=1  group by pq.product_id
");	
	
if($data)
{
	$this->data['data']=$data;	
	       $notification_time= \DB::table('nofication_user_tbl')
						->select('*')
						->where('module_type', 'STOCK LEVEL(RM)')->get();
						if($notification_time){
	 $email_user_id=implode(',',json_decode($notification_time[0]->email_user_id));    
		    $user_id=implode(',',json_decode($notification_time[0]->user_id));
            $email_users=\DB::select("select * from tb_users where id IN ($email_user_id) ");
            $users=\DB::select("select * from tb_users where id IN ($user_id) ");
 if(count($users) > 0){
			
		        foreach($users as $k=>$v){
    			     $notydata['reference_source']="STOCK LEVEL(RM)"; 				 
    			     $notydata['reference_source_id']=""; 				 
    			     $notydata['reference_url']="rawquantityonhand";				 
    			     $notydata['user_id']=$v->id; 				 
    			     $notydata['date']=date('Y-m-d'); 				 
    			     $notydata['read/unread']="unread"; 				 
    			     $notydata['description']="Stock Level is Low for Rawmaterial product.Check those Product"; 				                 $notydata['company_id']=\Session::get('companyid'); 				 
    			     $notydata['location_id']=\Session::get('location'); 				 
    			     $notydata['organization_id']=\Session::get('organization'); 				 
    			     $notydata['created_by']=\Session::get('id');; 				 
    			     $notydata['created_at']= date("Y-m-d h:i:sa");				 
    			     $notydata['last_updated_by']=\Session::get('id'); 				 
    			     $notydata['updated_at']= date("Y-m-d h:i:sa");				 
                   DB::table('notifications_t')->insert($notydata);
            		
			    }
            }
	     if(count($email_users) > 0){
			
		        foreach($email_users as $k=>$v){
    			           				 
                    \Mail::send('notifications.stockleveltable',$this->data, function($message) use ($v)
                    {    		
                        $to=$v->email;                               
                		$message->to($to);
                		$message->subject("Stock Level Report For Raw Material");
            			//$message->from('Saipavan9010@gmail.com');
            			if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    });
            		
			    }
            }
	
}
}

$data=\DB::select("select sum(pq.qoh) as qoh,pq.product_id,pq.concatenated_product,(CASE WHEN SUM(pq.`qoh`) <  sum(pq.min_stock_level1)
    THEN
    sum(pq.min_stock_level1)
     WHEN SUM(pq.`qoh`)< sum(pq.min_stock_level2)
     THEN
    sum(pq.min_stock_level2) 
     WHEN  SUM(pq.`qoh`) <  sum(pq.min_stock_level3)
     then
    sum(pq.min_stock_level3)
     END) as stock_level from (SELECT
    sum(`qoh_trx_qty`) as qoh,
    0 as min_stock_level1,
    0 as min_stock_level2,
    0 as min_stock_level3,
    i_qoh_detail_t.product_id   ,
     m_products_t.concatenated_product
FROM
    `i_qoh_detail_t` left join m_products_t on(m_products_t.product_id=i_qoh_detail_t.product_id and (m_products_t.min_order_qty!=0 or m_products_t.min_stock_level2!=0 or m_products_t.min_stock_level3!=0)) where 1=1 and m_products_t.product_group_id  in(1,4) group by i_qoh_detail_t.product_id
   UNION ALL
  SELECT 0 as qoh,m_products_t.min_order_qty as min_stock_level1,m_products_t.min_stock_level2,m_products_t.min_stock_level3,m_products_t.product_id,m_products_t.concatenated_product FROM m_products_t      where 1=1 and m_products_t.product_group_id  in(1,4) and (m_products_t.min_order_qty!=0 or m_products_t.min_stock_level2!=0 or m_products_t.min_stock_level3!=0) group by m_products_t.product_id      
) as pq  where 1=1  group by pq.product_id
");	
	
if($data)
{
	$this->data['data']=$data;	
	       $notification_time= \DB::table('nofication_user_tbl')
						->select('*')
						->where('module_type', 'STOCK LEVEL(FG)')->get();
						if($notification_time){
	 $email_user_id=implode(',',json_decode($notification_time[0]->email_user_id));    
		    $user_id=implode(',',json_decode($notification_time[0]->user_id));
            $email_users=\DB::select("select * from tb_users where id IN ($email_user_id) ");
            $users=\DB::select("select * from tb_users where id IN ($user_id) ");
 if(count($users) > 0){
			
		        foreach($users as $k=>$v){
    			     $notydata['reference_source']="STOCK LEVEL(FG)"; 				 
    			     $notydata['reference_source_id']=""; 				 
    			     $notydata['reference_url']="fgquantityonhand";				 
    			     $notydata['user_id']=$v->id; 				 
    			     $notydata['date']=date('Y-m-d'); 				 
    			     $notydata['read/unread']="unread"; 				 
    			     $notydata['description']="Stock Level is Low for Finished product.Check those Product"; 				                 $notydata['company_id']=\Session::get('companyid'); 				 
    			     $notydata['location_id']=\Session::get('location'); 				 
    			     $notydata['organization_id']=\Session::get('organization'); 				 
    			     $notydata['created_by']=\Session::get('id');; 				 
    			     $notydata['created_at']= date("Y-m-d h:i:sa");				 
    			     $notydata['last_updated_by']=\Session::get('id'); 				 
    			     $notydata['updated_at']= date("Y-m-d h:i:sa");				 
                   DB::table('notifications_t')->insert($notydata);
            		
			    }
            }
	     if(count($email_users) > 0){
			
		        foreach($email_users as $k=>$v){
    			           				 
                    \Mail::send('notifications.stockleveltable',$this->data, function($message) use ($v)
                    {    		
                        $to=$v->email;                               
                		$message->to($to);
                		$message->subject("Stock Level Report For Finished Goods");
            			//$message->from('Saipavan9010@gmail.com');
            			if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    });
            		
			    }
            }
	
    }		
    }		
	$comp=\Session::get('companyid');	
$data =\DB::select("SELECT s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,s_salesorder_hdr_t.ship_to_customer_id,concat(m_customers_t.customer_number,'-',m_customers_t.customer_name)as cus_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_lines_t.qty,s_salesorder_lines_t.pending_qty,s_salesorder_lines_t.delivery_date,s_salesorder_lines_t.product_id,m_products_t.concatenated_product FROM `s_salesorder_hdr_t` left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id) left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) left join m_products_t on(s_salesorder_lines_t.product_id=m_products_t.product_id) WHERE s_salesorder_lines_t.delivery_date < CURDATE() and s_salesorder_hdr_t.company_id=".$comp." ORDER BY s_salesorder_hdr_t.sales_hdr_id ASC");	
	
if($data)
{
	$this->data['data']=$data;	
	       $notification_time= \DB::table('nofication_user_tbl')
						->select('*')
						->where('module_type', 'PRODUCT OVERDUE (SALES)')->get();
if($notification_time) {
	 $email_user_id=implode(',',json_decode($notification_time[0]->email_user_id));    
		    $user_id=implode(',',json_decode($notification_time[0]->user_id));
            $email_users=\DB::select("select * from tb_users where id IN ($email_user_id) ");
            $users=\DB::select("select * from tb_users where id IN ($user_id) ");
 if(count($users) > 0){
			
		        foreach($users as $k=>$v){
    			     $notydata['reference_source']="PRODUCT OVERDUE (SALES)"; 				 
    			     $notydata['reference_source_id']=""; 				 
    			     $notydata['reference_url']="sopendingqtyrpt";				 
    			     $notydata['user_id']=$v->id; 				 
    			     $notydata['date']=date('Y-m-d'); 				 
    			     $notydata['read/unread']="unread"; 				 
    			     $notydata['description']="Products are overdue in Sales Order"; 
					 $notydata['company_id']=\Session::get('companyid'); 				 
    			     $notydata['location_id']=\Session::get('location'); 				 
    			     $notydata['organization_id']=\Session::get('organization'); 				 
    			     $notydata['created_by']=\Session::get('id');; 				 
    			     $notydata['created_at']= date("Y-m-d h:i:sa");				 
    			     $notydata['last_updated_by']=\Session::get('id'); 				 
    			     $notydata['updated_at']= date("Y-m-d h:i:sa");				 
                   DB::table('notifications_t')->insert($notydata);
            		
			    }
            }
	     if(count($email_users) > 0){
			
		        foreach($email_users as $k=>$v){
    			           				 
                    \Mail::send('notifications.sopendingqtytable',$this->data, function($message) use ($v)
                    {    		
                        $to=$v->email;                               
                		$message->to($to);
                		$message->subject("Product Overdue Report For Sales Order");
            			//$message->from('Saipavan9010@gmail.com'); 
            			if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    });
            		
			    }
            }
}
}	

$data =\DB::select("SELECT p_po_hdr_t.po_hdr_id,p_po_hdr_t.po_number,p_po_hdr_t.supplier_id,concat(m_supplier_t.supplier_number,'-',m_supplier_t.supplier_name)as sup_name,p_po_hdr_t.po_date,p_po_lines_t.qty,p_po_lines_t.pending_qty,p_po_lines_t.promised_alternate_date,p_po_lines_t.product_id,m_products_t.concatenated_product FROM `p_po_hdr_t` left join p_po_lines_t on(p_po_lines_t.po_hdr_id=p_po_hdr_t.po_hdr_id) left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id) left join m_products_t on(p_po_lines_t.product_id=m_products_t.product_id) WHERE p_po_lines_t.promised_alternate_date < CURDATE() and p_po_hdr_t.company_id=".$comp." ORDER BY p_po_hdr_t.po_hdr_id ASC");	

if($data)
{
	$this->data['data']=$data;	
	       $notification_time= \DB::table('nofication_user_tbl')
						->select('*')
						->where('module_type', 'PRODUCT OVERDUE (PURCHASE)')->get();
						if($notification_time){
	 $email_user_id=implode(',',json_decode($notification_time[0]->email_user_id));    
		    $user_id=implode(',',json_decode($notification_time[0]->user_id));
            $email_users=\DB::select("select * from tb_users where id IN ($email_user_id) ");
            $users=\DB::select("select * from tb_users where id IN ($user_id) ");
 if(count($users) > 0){
			
		        foreach($users as $k=>$v){
    			     $notydata['reference_source']="PRODUCT OVERDUE (PURCHASE)"; 				 
    			     $notydata['reference_source_id']=""; 				 
    			     $notydata['reference_url']="popendingqtyrpt";				 
    			     $notydata['user_id']=$v->id; 				 
    			     $notydata['date']=date('Y-m-d'); 				 
    			     $notydata['read/unread']="unread"; 				 
    			     $notydata['description']="Products are overdue in Purchase Order"; 
					 $notydata['company_id']=\Session::get('companyid'); 				 
    			     $notydata['location_id']=\Session::get('location'); 				 
    			     $notydata['organization_id']=\Session::get('organization'); 				 
    			     $notydata['created_by']=\Session::get('id');; 				 
    			     $notydata['created_at']= date("Y-m-d h:i:sa");				 
    			     $notydata['last_updated_by']=\Session::get('id'); 				 
    			     $notydata['updated_at']= date("Y-m-d h:i:sa");				 
                   DB::table('notifications_t')->insert($notydata);
            		
			    }
            }
	     if(count($email_users) > 0){
			
		        foreach($email_users as $k=>$v){
    			           				 
                    \Mail::send('notifications.popendingqtytable',$this->data, function($message) use ($v)
                    {    		
                        $to=$v->email;                               
                		$message->to($to);
                		$message->subject("Product Overdue Report For Purchase Order");
            			//$message->from('Saipavan9010@gmail.com'); 
            			if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    });
            		
			    }
            }
	
}	

}

$data =\DB::select("SELECT p_po_invoice_hdr_t.`po_invoice_id`,p_po_invoice_hdr_t.`bill_number`,p_po_invoice_hdr_t.`invoice_date`,p_po_invoice_hdr_t.`supplier_id`,concat(m_supplier_t.supplier_number,'-',m_supplier_t.supplier_name)as sup_name,p_po_invoice_hdr_t.`paid_amount`,p_po_invoice_hdr_t.`balance_amount`,p_po_invoice_hdr_t.`due_date`,p_po_invoice_hdr_t.invoice_grand_total FROM `p_po_invoice_hdr_t` left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id) WHERE `po_invoice_status`='APPROVED' AND `due_date` < CURDATE() and p_po_invoice_hdr_t.company_id=".$comp." ORDER BY p_po_invoice_hdr_t.po_invoice_id ASC");	

if($data)
{
	$this->data['data']=$data;	
	       $notification_time= \DB::table('nofication_user_tbl')
						->select('*')
						->where('module_type', 'PAYABLES')->get();
						if($notification_time){
	 $email_user_id=implode(',',json_decode($notification_time[0]->email_user_id));    
		    $user_id=implode(',',json_decode($notification_time[0]->user_id));
            $email_users=\DB::select("select * from tb_users where id IN ($email_user_id) ");
            $users=\DB::select("select * from tb_users where id IN ($user_id) ");
 if(count($users) > 0){
			
		        foreach($users as $k=>$v){
    			     $notydata['reference_source']="PAYABLES"; 				 
    			     $notydata['reference_source_id']=""; 				 
    			     $notydata['reference_url']="pendingpurchasepayment";				 
    			     $notydata['user_id']=$v->id; 				 
    			     $notydata['date']=date('Y-m-d'); 				 
    			     $notydata['read/unread']="unread"; 				 
    			     $notydata['description']="Purchase Invoice Payment Details"; 
					 $notydata['company_id']=\Session::get('companyid'); 				 
    			     $notydata['location_id']=\Session::get('location'); 				 
    			     $notydata['organization_id']=\Session::get('organization'); 				 
    			     $notydata['created_by']=\Session::get('id');; 				 
    			     $notydata['created_at']= date("Y-m-d h:i:sa");				 
    			     $notydata['last_updated_by']=\Session::get('id'); 				 
    			     $notydata['updated_at']= date("Y-m-d h:i:sa");				 
                   DB::table('notifications_t')->insert($notydata);
            		
			    }
            }
	     if(count($email_users) > 0){
			
		        foreach($email_users as $k=>$v){
    			           				 
                    \Mail::send('notifications.poinvoicepaymenttable',$this->data, function($message) use ($v)
                    {    		
                        $to=$v->email;                               
                		$message->to($to);
                		$message->subject("Purchase Invoice Payment Details Report");
            		//	$message->from('Saipavan9010@gmail.com');     
            		if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    });
            		
			    }
            }
	
}	

}
$data =\DB::select("SELECT s_invoice_hdr_t.`invoice_hdr_id`,s_invoice_hdr_t.`invoice_number`,s_invoice_hdr_t.`invoice_date`,s_invoice_hdr_t.`ship_to_customer_id`,concat(m_customers_t.customer_number,'-',m_customers_t.customer_name)as cus_name,s_invoice_hdr_t.`paid_amount`,s_invoice_hdr_t.`balance_amount`,s_invoice_hdr_t.`due_date`,s_invoice_hdr_t.invoice_grand_total FROM `s_invoice_hdr_t` left join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) WHERE `invoice_status`='APPROVED' AND `due_date` < CURDATE() and s_invoice_hdr_t.company_id=".$comp." ORDER BY s_invoice_hdr_t.invoice_hdr_id ASC");	

if($data)
{
	$this->data['data']=$data;	
	       $notification_time= \DB::table('nofication_user_tbl')
						->select('*')
						->where('module_type', 'RECEIVABLES')->get();
						if($notification_time){
	 $email_user_id=implode(',',json_decode($notification_time[0]->email_user_id));    
		    $user_id=implode(',',json_decode($notification_time[0]->user_id));
            $email_users=\DB::select("select * from tb_users where id IN ($email_user_id) ");
            $users=\DB::select("select * from tb_users where id IN ($user_id) ");
 if(count($users) > 0){
			
		        foreach($users as $k=>$v){
    			     $notydata['reference_source']="RECEIVABLES"; 				 
    			     $notydata['reference_source_id']=""; 				 
    			     $notydata['reference_url']="pendingsalesreceipt";				 
    			     $notydata['user_id']=$v->id; 				 
    			     $notydata['date']=date('Y-m-d'); 				 
    			     $notydata['read/unread']="unread"; 				 
    			     $notydata['description']="Sales Invoice Payment Details"; 
					 $notydata['company_id']=\Session::get('companyid'); 				 
    			     $notydata['location_id']=\Session::get('location'); 				 
    			     $notydata['organization_id']=\Session::get('organization'); 				 
    			     $notydata['created_by']=\Session::get('id');; 				 
    			     $notydata['created_at']= date("Y-m-d h:i:sa");				 
    			     $notydata['last_updated_by']=\Session::get('id'); 				 
    			     $notydata['updated_at']= date("Y-m-d h:i:sa");				 
                   DB::table('notifications_t')->insert($notydata);
            		
			    }
            }
	     if(count($email_users) > 0){
			
		        foreach($email_users as $k=>$v){
    			           				 
                    \Mail::send('notifications.soinvoicepaymenttable',$this->data, function($message) use ($v)
                    {    		
                        $to=$v->email;                               
                		$message->to($to);
                		$message->subject("Sales Invoice Payment Details Report");
            			//$message->from('Saipavan9010@gmail.com'); 
            			if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    });
            		
			    }
            }
	
}	

}
	 //return view('notifications.stockleveltable',$this->data);	
	}
	
}
