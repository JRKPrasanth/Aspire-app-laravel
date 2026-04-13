<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecondarydataController extends Controller
{
  
    public function ssdb_dashboard(Request $request,$id=null)
    {
        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        $this->data['pageMethod']=\Request::route()->getName();
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

     $this->data=array();
     
       
    
       $date=new \DateTime(date('Y-m-01'));
     $date->modify('-1 year');
    
     $date_string=(string)$date->format('Y-m-d');
     for ($i = 1; $i <= 12; $i++) {
         $date->modify('+1 month');
    
     $date_string=(string)$date->format('Y-m-d'); 
    $months_name[] = date("M-y", strtotime( $date_string));
    $months_number[] = date("n", strtotime( $date_string));
   
        }
     $date=new \DateTime(date('Y-m-01'));
     $date=(string)$date->modify('-11 month')->format('Y-m-d');
$this->data['column']=json_encode($months_name);


 $this->data['raw_product'] = $this->jcustomselect('m_products_t','product_id','concatenated_product','',"and product_group_id='2'" );
 
  $this->data['fg_product'] = $this->jcustomselect('m_products_t','product_id','concatenated_product','',"and product_group_id='1'" );
  
  
    $this->data['sfg_product'] = $this->jcustomselect('m_products_t','product_id','concatenated_product','',"and product_group_id='4'" );
  
  
  $this->data['customer'] = $this->jcustomselect('m_customers_t','customer_id','customer_name','',"" );
  
  $this->data['supplier'] = $this->jcustomselect('m_supplier_t','supplier_id','supplier_name','',"" );

$sid=\Session::get('id');
$grid_min_date=\Session::get('js_griddate');
$grid_min_date = date("Y-m-d", strtotime($grid_min_date));
$dsql=\DB::table('a_dashboard_access_t')->where('user_id',$sid)->get();
$das_access=array();
if(count($dsql)>0)
{
    $das_access=json_decode($dsql[0]->dashboard_option);
}
//dd($das_access);
$this->data['das_access']=$das_access;
     
     /*PO Invoice Status*/
     
     $this->data['po_invoice_status']=\DB::select("select po_invoice_status as name,round(sum(invoice_grand_total),2) as y from p_po_invoice_hdr_t where month(invoice_date)='06' and year(invoice_date)='2020' group by po_invoice_status");
     foreach($this->data['po_invoice_status'] as $ind=>$val)
     {
         $this->data['po_invoice_status'][$ind]->y=floatval($val->y);
     }
     
     $this->data['po_invoice_status']=json_encode($this->data['po_invoice_status']);
     
     /*PO Status*/
      $this->data['po_status']=\DB::select("SELECT po_status as name,round(sum(po_grand_total),2) as y FROM `p_po_hdr_t` where month(po_date)='06' and year(po_date)='2020' group by po_status");
     foreach($this->data['po_status'] as $ind=>$val)
     {
         $this->data['po_status'][$ind]->y=floatval($val->y);
     }
     
     $this->data['po_status']=json_encode($this->data['po_status']);
     
     
    foreach($das_access as $val)
    {
     /*Purchase Order Dashboard*/
     
     /* PO Draft and Initiated Count */
     if($val=='PODraft')
     {
     
     $this->data['po_draft_count']=\DB::select("select count(po_hdr_id) as count from p_po_hdr_t where po_status='DRAFT' and date(created_at) >= '$grid_min_date'");
     }
     else if($val=='POApproval')
     {
     $this->data['po_initiated_count']=\DB::select("select count(po_hdr_id) as count from p_po_hdr_t where  po_status='INITIATED' and date(created_at) >= '$grid_min_date'");
     }
     else if($val=='GRNPending')
     {
     /* Grn Count */
     $this->data['grn_count']=\DB::select("SELECT count(p_gin_hdr_id)as count FROM `p_gin_hdr_t` WHERE `grn_status` = 0 and date(created_at) >= '$grid_min_date'");
     }
     else if($val=='POQualityPending')
     {
     /* quality Count */
     //$this->data['quality_count']=\DB::select("SELECT count(grn_line_id) as count  FROM `p_grn_lines_t` WHERE `qc_status` = 0");
     $this->data['quality_count']=\DB::select("SELECT count(p_grn_hdr_t.grn_id) as count FROM `p_grn_hdr_t` left join p_grn_lines_t on p_grn_hdr_t.grn_id = p_grn_lines_t.grn_id WHERE 1=1 and p_grn_hdr_t.quality_check='Yes'  and p_grn_hdr_t.grn_status='INITIATED' and p_grn_hdr_t.master_grn_id=0 and p_grn_lines_t.qc_status=0 and date(p_grn_hdr_t.created_at) >= '$grid_min_date'");
     }
     else if($val=='')
     {
     /* quality approve Count */
     $this->data['quality_approve_count']=\DB::select("SELECT count(qc_header_id) as count FROM `p_qc_header_t` where qc_status='INITIATED'");
     
     }
     else if($val=='InvoiceDraft')
     {
      /* PO Invoice Draft and Initiated Count */
     
     $this->data['invoice_draft_count']=\DB::select("SELECT count(po_invoice_id) as count FROM `p_po_invoice_hdr_t` where po_invoice_status='DRAFT' and date(created_at) >= '$grid_min_date'");
     }
     else if($val=='InvoicePending')
     {
     
      //$this->data['invoice_count']=\DB::select("SELECT count(grn_id) as count FROM `p_grn_hdr_t` where invoice_created!='Yes' order by grn_id desc ");
      
      $this->data['invoice_count'] =\DB::select("SELECT count(po_invoice_id) as count FROM `p_po_invoice_hdr_t` where po_invoice_status='DRAFT' or po_invoice_status='INITIATED' and date(created_at) >= '$grid_min_date'");
     
     }
     else if($val=='POInvoiceApproval')
     {
     $this->data['invoice_initiated_count']=\DB::select("SELECT count(po_invoice_id) as count FROM `p_po_invoice_hdr_t` where  po_invoice_status='INITIATED'");
     }
     else if($val=='ProductApproval')
     {
     $this->data['product_initiated_count']=\DB::select("SELECT COUNT(product_id) as count FROM m_products_t WHERE product_status = 'INITIATED'");
     }
     else if($val=='BOMApproval')
     {
     $this->data['bom_initiated_count']=\DB::select("SELECT COUNT(material_bom_hdr_id) as count FROM m_material_bom_hdr_t WHERE savestatus = 'INITIATED'");
     }
     else if($val=='SupplierApproval')
     {
     $this->data['supplier_initiated_count']=\DB::select("SELECT COUNT(supplier_id) as count FROM m_supplier_t WHERE savestatus = 'INITIATED'");
     }
     else if($val=='PurchasePricelistApproval')
     {
     $this->data['purpricelist_initiated_count']=\DB::select("SELECT COUNT(pricelist_hdr_id) AS count FROM i_pricelist_hdr_t WHERE price_list_type = 'Purchase' and savestatus = 'INITIATED'");
     }
     else if($val=='GRNOverdue')
     {
    /*Pending Qty*/
    
    $this->data['pending_qty']=\DB::select("select p_po_hdr_t.po_number,(select m_supplier_t.supplier_name from m_supplier_t where m_supplier_t.supplier_id=p_po_hdr_t.supplier_id) as supplier_name,(select m_products_t.concatenated_product from m_products_t where m_products_t.product_id=p_po_lines_t.product_id) as product_name,p_po_lines_t.pending_qty,p_po_lines_t.promised_date,DATEDIFF(CURRENT_DATE(),p_po_lines_t.promised_date) as due from p_po_lines_t join p_po_hdr_t on p_po_hdr_t.po_hdr_id=p_po_lines_t.po_hdr_id where p_po_lines_t.pending_qty>0 and p_po_hdr_t.po_status='APPROVED' and p_po_hdr_t.po_type='STANDARD' and p_po_lines_t.promised_date<=CURRENT_DATE() order by p_po_lines_t.promised_date desc");
    
    
     }
     else if($val=='PurchaseInvoiceOverdue')
     {
    /*Payment Pending*/
    
    $this->data['payment_pending']=\DB::select("SELECT bill_number,invoice_date,(select m_supplier_t.supplier_name from m_supplier_t where m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id) as supplier_name,balance_amount,due_date,DATEDIFF(CURRENT_DATE(),p_po_invoice_hdr_t.due_date) as due FROM `p_po_invoice_hdr_t` where po_invoice_status='APPROVED' and due_date <= CURRENT_DATE() and balance_amount >0 order by due_date desc");
    
     }
     else if($val=='PurchaseBySupplier')
     {
    
    /* Overall Invoice  for last 12 months*/
    
     $this->data['overall_supplier_invoice']=\DB::select("SELECT month(invoice_date) as month,round(sum(invoice_grand_total)/100000,2) as total FROM `p_po_invoice_hdr_t` where po_invoice_status='APPROVED' and invoice_date BETWEEN '$date' and CURRENT_DATE() group by month(invoice_date)");
     
     
     
    $overall_invoice=collect($this->data['overall_supplier_invoice']);
    $overall_invoice_data=array();
    foreach($months_number as $val)
    {
        
         $inv_data=$overall_invoice->where('month',$val);
        $inv_data->all();
        //dd($prd_data);
        if(count($inv_data)>0)
        {
            foreach($inv_data as $v)
            {
          $overall_invoice_data[]=floatval($v->total);
            
                break;
            }
        }
        else
        {
           $overall_invoice_data[]=0; 
        }
        
        
        }
    
    $this->data['overall_invoice_data']=json_encode($overall_invoice_data);
     
     
     
     }
     else if($val=='PurchaseByProducts')
     {
       $this->data['overall_product_invoice']=\DB::select("SELECT month(invoice_date) as month,round(sum(invoice_grand_total-invoice_tax_total)/100000,2) as total FROM `p_po_invoice_hdr_t` where po_invoice_status='APPROVED' and invoice_date BETWEEN '$date' and CURRENT_DATE() group by month(invoice_date)");
     
    $overall_product=collect($this->data['overall_product_invoice']);
    $overall_product_data=array();
    foreach($months_number as $val)
    {
        $prd_data=$overall_product->where('month',$val);
        $prd_data->all();
        //dd($prd_data);
        if(count($prd_data)>0)
        {
            foreach($prd_data as $v)
            {
          $overall_product_data[]=floatval($v->total);
            
                break;
            }
        }
        else
        {
           $overall_product_data[]=0; 
        }
        
         
        
        }
    
    $this->data['overall_product_data']=json_encode($overall_product_data);
     } else if($val=='PurchaseByProductsQty')
     {
    
    /*Purchase by product quantity*/
    
    
    $this->data['overall_product_qty_invoice']=\DB::select("SELECT month(p_po_invoice_hdr_t.invoice_date) as month,sum(p_po_invoice_lines_t.qty) as total FROM `p_po_invoice_hdr_t` join p_po_invoice_lines_t on p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id where p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.invoice_date BETWEEN '$date' and CURRENT_DATE() group by month(p_po_invoice_hdr_t.invoice_date)");
     
    $overall_product_qty=collect($this->data['overall_product_qty_invoice']);
    $overall_product_qty_data=array();
    foreach($months_number as $val)
    {
        $prd_data=$overall_product_qty->where('month',$val);
        $prd_data->all();
        //dd($prd_data);
        if(count($prd_data)>0)
        {
            foreach($prd_data as $v)
            {
          $overall_product_qty_data[]=floatval($v->total);
            
                break;
            }
        }
        else
        {
           $overall_product_qty_data[]=0; 
        }
        
         
        
        }
    
    $this->data['overall_product_qty_data']=json_encode($overall_product_qty_data);
    
    
}
     else if($val=='SODraft')
     {
    
     /*Sales Order Dashboard*/
     
     /* So Draft and Initiated Count */
     $wh='';
     $depart=\Session::get('groupname');
		if($depart=="14")
		{
		    $emp_id=\Session::get('emp_id');
            $cus_id=\DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");
		
		    $wh.=" and ship_to_customer_id in (".$cus_id[0]->dis.")";
		}
     
     $this->data['so_draft_count']=\DB::select("select count(sales_hdr_id) as count from s_salesorder_hdr_t where order_status_id='DRAFT' $wh");
     
     }
     else if($val=='SOApproval')
     {
         $wh='';
     $depart=\Session::get('groupname');
		if($depart=="14")
		{
		    $emp_id=\Session::get('emp_id');
            $cus_id=\DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");
		
		    $wh.=" and ship_to_customer_id in (".$cus_id[0]->dis.")";
		}
     $this->data['so_initiated_count']=\DB::select("select count(sales_hdr_id) as count from s_salesorder_hdr_t where  order_status_id='INITIATED' $wh");
     }
     else if($val=='DispatchDraft')
     {
     /* Grn Count */
     $this->data['dispatch_count']=\DB::select("SELECT COUNT(so_dispatch_hdr_id) as count FROM `s_dispatch_hdr_t` where dispatch_status='DRAFT'");
     
}
     else if($val=='SOInvoiceDraft')
     {
     
     
     
      /* PO Invoice Draft and Initiated Count */
     
     $wh='';
     $depart=\Session::get('groupname');
		if($depart=="14")
		{
		    $emp_id=\Session::get('emp_id');
            $cus_id=\DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");
		
		    $wh.=" and ship_to_customer_id in (".$cus_id[0]->dis.")";
		}
     
     $this->data['so_invoice_draft_count']=\DB::select("SELECT count(invoice_hdr_id) as count FROM `s_invoice_hdr_t` where invoice_status='DRAFT' $wh");
     
     }
     else if($val=='SInvoicePending')
     {
      //$this->data['so_invoice_count']=\DB::select("select count(so_dispatch_hdr_id) as count from s_dispatch_hdr_t where invoicestatus='0'");
      $wh='';
     $depart=\Session::get('groupname');
		if($depart=="14")
		{
		    $emp_id=\Session::get('emp_id');
            $cus_id=\DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");
		
		    $wh.=" and ship_to_customer_id in (".$cus_id[0]->dis.")";
		}
      $this->data['so_invoice_count']=\DB::select("SELECT count(invoice_hdr_id) as count FROM `s_invoice_hdr_t` where invoice_status='DRAFT' or invoice_status='INITIATED' $wh");
     }
     else if($val=='SOInvoiceApproval')
     {
     $wh='';
     $depart=\Session::get('groupname');
		if($depart=="14")
		{
		    $emp_id=\Session::get('emp_id');
            $cus_id=\DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");
		
		    $wh.=" and ship_to_customer_id in (".$cus_id[0]->dis.")";
		}
     $this->data['so_invoice_initiated_count']=\DB::select("SELECT count(invoice_hdr_id) as count FROM `s_invoice_hdr_t` where invoice_status='INITIATED' $wh");
     }
     else if($val=='CustomerApproval')
     {
     $this->data['customer_initiated_count']=\DB::select("SELECT COUNT(customer_id) as count FROM `m_customers_t` WHERE savestatus='INITIATED'");
     }
     else if($val=='SalesPricelistApproval')
     {
     $this->data['salpricelist_initiated_count']=\DB::select("SELECT COUNT(pricelist_hdr_id) AS count FROM i_pricelist_hdr_t WHERE price_list_type = 'Sales' and savestatus = 'INITIATED'");
     }
     else if($val=='SOReturnApproval')
     {
     $this->data['soreturn_initiated_count']=\DB::select("SELECT COUNT(so_rma_hdr_id) as count FROM `so_rma_hdr_t` WHERE return_status = 'INITIATED'");
     }
     else if($val=='ShipConfirmPending')
     {
     $this->data['shipconfirmpen_count']=\DB::select("SELECT COUNT(so_dispatch_hdr_id) as count FROM `s_dispatch_hdr_t` WHERE dispatch_status = 'DISPATCHED' AND dispatch_status != 'SHIPPED'");
     }
     else if($val=='DispatchOverdue')
     {
    /*Pending Qty*/
    
    $this->data['so_pending_qty']=\DB::select("select s_salesorder_hdr_t.sales_order_no,(select m_customers_t.customer_name from m_customers_t where m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) as customer_name,(select m_products_t.concatenated_product from m_products_t where m_products_t.product_id=s_salesorder_lines_t.product_id)as product_name,s_salesorder_lines_t.pending_qty,s_salesorder_lines_t.delivery_date,DATEDIFF(CURRENT_DATE(),s_salesorder_lines_t.delivery_date) as due from s_salesorder_lines_t join s_salesorder_hdr_t on s_salesorder_hdr_t.sales_hdr_id=s_salesorder_lines_t.sales_hdr_id where s_salesorder_hdr_t.order_status='APPROVED' and s_salesorder_lines_t.pending_qty>0 and s_salesorder_lines_t.delivery_date <=CURRENT_DATE() order by s_salesorder_lines_t.delivery_date desc");
    
     }
     else if($val=='SalesInvoiceOverdue')
     {
    
    /*Payment Pending*/
    $wh='';
     $depart=\Session::get('groupname');
		if($depart=="14")
		{
		    $emp_id=\Session::get('emp_id');
            $cus_id=\DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");
		
		    $wh.=" and ship_to_customer_id in (".$cus_id[0]->dis.")";
		}
    
    $this->data['so_payment_pending']=\DB::select("SELECT invoice_number,invoice_date,(select m_customers_t.customer_name from m_customers_t where m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) as customer_name,balance_amount,due_date,DATEDIFF(CURRENT_DATE(),due_date) as due  FROM `s_invoice_hdr_t` where invoice_status='APPROVED' and due_date<=CURRENT_DATE() and balance_amount>0 $wh");
     }
     else if($val=='SalesByCustomer')
     {
  
    
    /* Overall Invoice  for last 12 months*/
    
     $this->data['overall_customer_invoice']=\DB::select("SELECT month(invoice_date) as month,round(sum(invoice_grand_total)/100000,2) as total FROM `s_invoice_hdr_t` where invoice_status='APPROVED' and invoice_date BETWEEN '$date' and CURRENT_DATE() group by month(invoice_date)");
     
     
    
    $so_overall_invoice=collect($this->data['overall_customer_invoice']);
    $so_overall_invoice_data=array();
    foreach($months_number as $val)
    {
      
         $inv_data=$so_overall_invoice->where('month',$val);
        $inv_data->all();
        //dd($prd_data);
        if(count($inv_data)>0)
        {
            foreach($inv_data as $v)
            {
          $so_overall_invoice_data[]=floatval($v->total);
            
                break;
            }
        }
        else
        {
           $so_overall_invoice_data[]=0; 
        }
        
        
        }
    
    $this->data['so_overall_invoice_data']=json_encode($so_overall_invoice_data);
     
     
     
     
     }
     else if($val=='SalesByProducts')
     {
       $this->data['so_overall_product_invoice']=\DB::select("SELECT month(invoice_date) as month,round(sum(invoice_grand_total-invoice_tax_total)/100000,2) as total FROM `s_invoice_hdr_t` where invoice_status='APPROVED' and invoice_date BETWEEN '$date' and CURRENT_DATE() group by month(invoice_date)");
     
    $so_overall_product=collect($this->data['so_overall_product_invoice']);
    $so_overall_product_data=array();
    foreach($months_number as $val)
    {
        $prd_data=$so_overall_product->where('month',$val);
        $prd_data->all();
        //dd($prd_data);
        if(count($prd_data)>0)
        {
            foreach($prd_data as $v)
            {
          $so_overall_product_data[]=floatval($v->total);
            
                break;
            }
        }
        else
        {
           $so_overall_product_data[]=0; 
        }
        
       
        
        }
    
   
    $this->data['so_overall_product_data']=json_encode($so_overall_product_data);
    
    
}

    
    
     
     /* Production and operation details */
 else if($val=='ProductionMaterialIssuePending')
     {   
     /*Material Issue Pending*/
     
     $this->data['production_material_issue_pending']=\DB::select("SELECT count(w_jobs_hdr_id) as count FROM `w_jobcard_hdr_t` where job_status='OPEN' and bom_process=''");
     }else if($val=="OperationMaterialIssuePending"){
     $this->data['operation_material_issue_pending']=\DB::select("SELECT count(w_jobs_hdr_id) as count FROM `w_jobcard_hdr_t` where job_status='OPEN' and bom_process!=''");
     }
    else if($val=='ProductionMaterialAcknowledgementPending')
     {
     /*Material Acknowledgement pending*/
     
     $this->data['production_material_ack_pending']=\DB::select("SELECT count(w_jobs_hdr_id) as count FROM `w_jobcard_hdr_t` where job_status='MATERIAL ISSUED' and bom_process=''");
     }else if($val=="OperationMaterialAcknowledgementPending"){
     $this->data['operation_material_ack_pending']=\DB::select("SELECT count(w_jobs_hdr_id) as count FROM `w_jobcard_hdr_t` where job_status='MATERIAL ISSUED' and bom_process!=''");
     }else if($val=='ProductionCompletionPending')
     {
      /*Job Card Completion  pending*/
     
     $this->data['production_comp_pending']=\DB::select("SELECT count(w_jobs_hdr_id) as count FROM `w_jobcard_hdr_t` where job_status='MATERIAL RECEIVED' and bom_process=''");
     }else if($val=="OperationCompletionPending"){
     $this->data['operation_comp_pending']=\DB::select("SELECT count(w_jobs_hdr_id) as count FROM `w_jobcard_hdr_t` where job_status='MATERIAL RECEIVED' and bom_process!=''");
     
     
     }else if($val=="ProductionQualityPending"){
     /*Quality Check  pending*/
     
     //$this->data['production_quality_pending']=\DB::select("select COUNT(qa_submitstage_trx_hdr_id) as count from w_qa_submitstage_trx_t join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=w_qa_submitstage_trx_t.job_no where w_qa_submitstage_trx_t.quality_check='YES' and w_qa_submitstage_trx_t.qa_status='0' and w_jobcard_hdr_t.bom_process=''");
     $this->data['production_quality_pending']=\DB::select("SELECT COUNT(w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id) AS count FROM w_qa_submitstage_trx_t LEFT JOIN i_quality_spec_trx_hdr_t ON  w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id = i_quality_spec_trx_hdr_t.qa_submitstage_trx_hdr_id left join m_products_t on m_products_t.product_id=w_qa_submitstage_trx_t.product_id where 1=1 and m_products_t.product_group_id = 4 AND w_qa_submitstage_trx_t.qa_status = 'INITIATED' and w_qa_submitstage_trx_t.quality_check='Yes' and w_qa_submitstage_trx_t.qc_status=0");
     }else if($val=="OperationQualityPending"){
     //$this->data['operation_quality_pending']=\DB::select("select COUNT(qa_submitstage_trx_hdr_id) as count from w_qa_submitstage_trx_t join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=w_qa_submitstage_trx_t.job_no where w_qa_submitstage_trx_t.quality_check='YES' and w_qa_submitstage_trx_t.qa_status='0' and w_jobcard_hdr_t.bom_process!=''");
     $this->data['operation_quality_pending']=\DB::select("SELECT COUNT(w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id) AS count FROM w_qa_submitstage_trx_t LEFT JOIN i_quality_spec_trx_hdr_t ON  w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id = i_quality_spec_trx_hdr_t.qa_submitstage_trx_hdr_id left join m_products_t on m_products_t.product_id=w_qa_submitstage_trx_t.product_id where 1=1 and m_products_t.product_group_id = 1 AND w_qa_submitstage_trx_t.qa_status = 'INITIATED' and w_qa_submitstage_trx_t.quality_check='Yes' and w_qa_submitstage_trx_t.qc_status=0");
     }else if($val=="ProductionQualityApprovePending"){
     
     /*Quality Check Approval  pending*/
     
     //$this->data['production_quality_app_pending']=\DB::select("SELECT count(quality_spec_trx_hdr_id) as count FROM `i_quality_spec_trx_hdr_t` join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=i_quality_spec_trx_hdr_t.job_hdr_id where i_quality_spec_trx_hdr_t.qa_status='INITIATED' and w_jobcard_hdr_t.bom_process=''");
     //$this->data['production_quality_app_pending']=\DB::select("SELECT COUNT(w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id) AS count FROM w_qa_submitstage_trx_t LEFT JOIN i_quality_spec_trx_hdr_t ON  w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id = i_quality_spec_trx_hdr_t.qa_submitstage_trx_hdr_id left join m_products_t on m_products_t.product_id=w_qa_submitstage_trx_t.product_id and m_products_t.product_group_id = 4 where 1=1  AND w_qa_submitstage_trx_t.qa_status = 'INITIATED' and w_qa_submitstage_trx_t.quality_check='Yes' and w_qa_submitstage_trx_t.qc_status=0");
     $this->data['production_quality_app_pending']=\DB::select("SELECT COUNT(w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id) AS count FROM w_qa_submitstage_trx_t LEFT JOIN i_quality_spec_trx_hdr_t ON  w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id = i_quality_spec_trx_hdr_t.qa_submitstage_trx_hdr_id left join m_products_t on m_products_t.product_id=w_qa_submitstage_trx_t.product_id where 1=1 and m_products_t.product_group_id = 4 and i_quality_spec_trx_hdr_t.qa_status='INITIATED'  AND w_qa_submitstage_trx_t.qa_status = 'INITIATED' and (i_quality_spec_trx_hdr_t.qualitycheck_status=1 or i_quality_spec_trx_hdr_t.qualityanalytical_status=1)");
     }else if($val=="OperationQualityApprovePending"){
     //$this->data['operation_quality_app_pending']=\DB::select("SELECT count(quality_spec_trx_hdr_id) as count FROM `i_quality_spec_trx_hdr_t` join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=i_quality_spec_trx_hdr_t.job_hdr_id where i_quality_spec_trx_hdr_t.qa_status='INITIATED' and w_jobcard_hdr_t.bom_process!=''");
     $this->data['operation_quality_app_pending']=\DB::select("SELECT COUNT(w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id) AS count FROM w_qa_submitstage_trx_t LEFT JOIN i_quality_spec_trx_hdr_t ON  w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id = i_quality_spec_trx_hdr_t.qa_submitstage_trx_hdr_id left join m_products_t on m_products_t.product_id=w_qa_submitstage_trx_t.product_id where 1=1 and m_products_t.product_group_id = 1 and i_quality_spec_trx_hdr_t.qa_status='INITIATED'  AND w_qa_submitstage_trx_t.qa_status = 'INITIATED' and (i_quality_spec_trx_hdr_t.qualitycheck_status=1 or i_quality_spec_trx_hdr_t.qualityanalytical_status=1)");
     }
     /*Operation process open job cards*/
     else if($val=="OperationOpenJobcard"){
      $operation_process_data=\DB::select("SELECT bom_process as name,count(w_jobs_hdr_id) as y FROM `w_jobcard_hdr_t` where bom_process!='' and (job_status='OPEN' or job_status='MATERIAL ISSUED' or job_status='MATERIAL RECEIVED') group by bom_process");
     
     foreach($operation_process_data as $ind=>$val)
     {
       $operation_process_data[$ind]->y=floatval($val->y);  
     }
     $this->data['operation_process_data']=json_encode($operation_process_data);
     }else if($val=="OperationProductQty"){
     /*Operation qty data*/
 
      $this->data['operation_product_qty']=\DB::select("select month(job_completion_date) as month,round(sum(COALESCE((select w_qa_submitstage_trx_t.production_qty from w_qa_submitstage_trx_t where w_qa_submitstage_trx_t.job_no=w_jobcard_hdr_t.w_jobs_hdr_id limit 1),0)),2) as total from w_jobcard_hdr_t where bom_process!='' and job_completion_date between '$date' and CURRENT_DATE() group by month(job_completion_date)");
     
     
     
    $operation_product_qty=collect($this->data['operation_product_qty']);
    $overall_operation_qty=array();
    foreach($months_number as $val)
    {
        
         $inv_data=$operation_product_qty->where('month',$val);
        $inv_data->all();
        //dd($prd_data);
        if(count($inv_data)>0)
        {
            foreach($inv_data as $v)
            {
          $overall_operation_qty[]=floatval($v->total);
            
                break;
            }
        }
        else
        {
           $overall_operation_qty[]=0; 
        }
        
        
        }
    
    $this->data['overall_operation_qty']=json_encode($overall_operation_qty);
    
     }else if($val=="ProductionProductQty"){
    
     /*Production qty data*/
     
      $this->data['production_product_qty']=\DB::select("select month(job_completion_date) as month,round(sum(COALESCE((select w_qa_submitstage_trx_t.production_qty from w_qa_submitstage_trx_t where w_qa_submitstage_trx_t.job_no=w_jobcard_hdr_t.w_jobs_hdr_id limit 1 ),0)),2) as total from w_jobcard_hdr_t where bom_process='' and job_completion_date between '$date' and CURRENT_DATE() group by month(job_completion_date)");
      
      
      
     // dd($this->data['production_product_qty']);
     
     
     
    $production_product_qty=collect($this->data['production_product_qty']);
    $overall_production_qty=array();
    foreach($months_number as $val)
    {
        
         $inv_data=$production_product_qty->where('month',$val);
        $inv_data->all();
        //dd($prd_data);
        if(count($inv_data)>0)
        {
            foreach($inv_data as $v)
            {
          $overall_production_qty[]=floatval($v->total);
            
                break;
            }
        }
        else
        {
           $overall_production_qty[]=0; 
        }
        
        
        }
    
   // dd($overall_production_qty);
    
    $this->data['overall_production_qty']=json_encode($overall_production_qty);
     }
     
     /* Operation Open Job Card Details */
     
     else if($val=="OperationJobCardDetails"){
      $this->data['operation_job_data']=\DB::select("select job_no,job_date,(select m_products_t.concatenated_product from m_products_t where m_products_t.product_id=w_jobcard_hdr_t.product_id) as product_name,job_adjusted_qty,bom_process,job_status from w_jobcard_hdr_t where  bom_process!='' and (job_status='OPEN' or job_status='MATERIAL ISSUED' or job_status='MATERIAL RECEIVED')");
      
     }else if($val=="ProductionJobCardDetails"){
       $this->data['production_job_data']=\DB::select("select job_no,job_date,(select m_products_t.concatenated_product from m_products_t where m_products_t.product_id=w_jobcard_hdr_t.product_id) as product_name,job_adjusted_qty,job_status from w_jobcard_hdr_t where  bom_process='' and (job_status='OPEN' or job_status='MATERIAL ISSUED' or job_status='MATERIAL RECEIVED')");
     }
    // }
     else if($val=="ExpenseDraft"){
          $this->data['exp_draft']=\DB::select("SELECT count(expense_id) as count FROM `f_expenses_t` WHERE expense_status='DRAFT'");
     }else if($val=="ExpenseApproval"){
          $this->data['exp_initiate']=\DB::select("SELECT count(expense_id) as count FROM `f_expenses_t` WHERE expense_status='INITIATED'");
     }else if($val=="PaymentOverduePending"){
          $this->data['payment_due']=\DB::select("SELECT count(po_invoice_id) as count FROM `p_po_invoice_hdr_t` where po_invoice_status='APPROVED' and due_date <=CURRENT_DATE() and balance_amount > 0");
         // $this->data['payment_due']=\DB::select("SELECT COUNT(po_invoice_id) AS count FROM p_po_invoice_hdr_t where p_po_invoice_hdr_t.balance_amount!=0  and p_po_invoice_hdr_t.po_invoice_status='APPROVED'");
     }else if($val=="PaymentBatchPending"){
            //$this->data['payment_batch']=\DB::select("SELECT count(payment_id) as count FROM `p_payments_t`  where batch_status='INITIATED' and payment_type_id!='CHEQUE' and payment_type_id!='CASH' and payment_type_id!='IMPREST' and payment_type_id!='' ");
            $this->data['payment_batch'] = \DB::select("SELECT count(payment_id) as count FROM p_payments_t where 1=1 and batch_status !='GENERATED' and (payment_type_id='NEFT' OR payment_type_id='CHEQUE' OR payment_type_id='IMPS' OR payment_type_id='RTGS') and payment_source!='SALARYPAYMENT'");
     }else if($val=="PaymentBRSPending"){
            //$this->data['payment_brs']=\DB::select("SELECT count(payment_id) as count FROM `p_payments_t` where stmtid='0' and payment_type_id!='CHEQUE' and payment_type_id!='CASH' and payment_type_id!='IMPREST' and payment_type_id!=''");
            $this->data['payment_brs']=\DB::select("SELECT count(payment_id) as count FROM p_payments_t where 1=1 and batch_status !='GENERATED' and (payment_type_id='NEFT' OR payment_type_id='CHEQUE' OR payment_type_id='IMPS' OR payment_type_id='RTGS' OR payment_type_id !='CASH' OR payment_type_id !='IMPREST' ) AND bank_date = '' AND payment_amount != 0");
     }else if($val=="ReceiptBRSPending"){
             //$this->data['receipt_brs']=\DB::select("SELECT count(receipt_id) as count FROM `s_receipts_t` where stmtid='0'");
             $this->data['receipt_brs']=\DB::select("SELECT count(receipt_id) as count FROM `s_receipts_t` where stmtid='0' AND `receipt_type_id` != 'CASH'");
     }else if($val=="Consumables"){
             //$this->data['receipt_brs']=\DB::select("SELECT count(receipt_id) as count FROM `s_receipts_t` where stmtid='0'");
             $this->data['consumables']=\DB::select("SELECT COUNT(consumable_hdr_id) AS count FROM `i_consumable_hdr_t` WHERE status='APPROVED' AND date(created_at) >= '$grid_min_date'");         
     }else if($val=="ConsumablesApprovalPending"){
             //$this->data['receipt_brs']=\DB::select("SELECT count(receipt_id) as count FROM `s_receipts_t` where stmtid='0'");
             $this->data['consumables_pending']=\DB::select("SELECT COUNT(consumable_hdr_id) AS count FROM `i_consumable_hdr_t` WHERE status='INITIATED' AND date(created_at) >= '$grid_min_date'");                  
     }else if($val=="AdvanceSet-OffPO"){
             $this->data['advancesetoff_po']=\DB::select("SELECT COUNT(p_po_hdr_t.po_hdr_id) AS count FROM p_po_hdr_t left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.`supplier_id`) where p_po_hdr_t.advance_status=0 and p_po_hdr_t.po_status!='DRAFT' and p_po_hdr_t.po_status!='INITIATED' and p_po_hdr_t.balance_amount>0");
     }else if($val=="FinishedGoods"){   
               $this->data['fg_product_count']=\DB::select("SELECT count(product_id) as count FROM `m_products_t` where product_group_id='1'");
     }else if($val=="RawMaterials"){     
                $this->data['raw_product_count']=\DB::select("SELECT count(product_id) as count FROM `m_products_t` where product_group_id='2'");
     }else if($val=="PackingMaterials"){
                 $this->data['pack_product_count']=\DB::select("SELECT count(product_id) as count FROM `m_products_t` where product_group_id='3'");
     }else if($val=="SemiFinishedGoods"){      
                  $this->data['sfg_product_count']=\DB::select("SELECT count(product_id) as count FROM `m_products_t` where product_group_id='4'");
     }else if($val=="SampleProducts"){    
                   $this->data['sp_product_count']=\DB::select("SELECT count(product_id) as count FROM `m_products_t` where product_group_id='6'");
     }else if($val=="PromotionalItems"){     
                    $this->data['pi_product_count']=\DB::select("SELECT count(product_id) as count FROM `m_products_t` where product_group_id='12'");
     }else if($val=="CLBalance"){
         /*Leave Balance Count*/
                    $this->data['cl_balance_count']=\DB::select("SELECT causal_leave  FROM Leave_balance_tbl WHERE employee_id = $sid");
     }else if($val=="ELBalance"){
                    $this->data['el_balance_count']=\DB::select("SELECT earn_leave  FROM Leave_balance_tbl WHERE employee_id = $sid");
     }else if($val=="Comp-OffBalance"){
                    $this->data['compoff_balance_count']=\DB::select("SELECT comp_off_leave  FROM Leave_balance_tbl WHERE employee_id = $sid");
     }else if($val=="LeaveApproval"){
                    $this->data['leave_initiated']=\DB::select("SELECT COUNT(leave_id) as count FROM `hr_leaves_t` WHERE employee_id=$sid AND leave_status='INITIATED'");
     }else if($val=="LeaveApprovePending"){
                    $this->data['report_approve_pending']=\DB::select("SELECT COUNT(leave_id) as count FROM `hr_leaves_t` WHERE forwarded_id=$sid AND leave_status='INITIATED'");
     }else if($val=="ROLFGDetails"){       
                    
            /*ROL FG Report*/
            
            $this->data['rol_fg']=\DB::select("SELECT
    *
FROM
    (
    SELECT
        f.product_id,
        f.concatenated_product,
        f.product_subcategory_id,
        f.product_category_id,
        f.min_order_qty,
        COALESCE((f.max_order_qty),0) as max_order_qty,
        m_product_subcategory_t.subcategory_name,
        m_product_category_t.category_name,
        SUM(f.so_qty) AS so_qty,
        SUM(Stock) AS stock,
        IF(
            SUM(Stock - so_qty - min_order_qty) > 0,
            0,
            SUM(Stock - so_qty - min_order_qty)
        ) AS FG_Req,
        SUM(job_qty) AS job_qty,
        SUM(wip + job_qty) AS wip,
        IF(
            SUM(
                Stock + wip - so_qty - min_order_qty
            ) > 0,
            0,
            SUM(
                Stock + wip - so_qty - min_order_qty
            )
        ) AS due,
        (
            CASE WHEN(stock + SUM(wip)) > max_order_qty THEN 0 WHEN SUM((Stock - so_qty - min_order_qty)
        ) *(-1) >(max_order_qty - Stock - wip) THEN SUM((Stock - so_qty - min_order_qty) 
) *(-1) when SUM((Stock - so_qty - min_order_qty)
        ) > 0 then 0 ELSE(max_order_qty - Stock - wip)
END) AS batchqty
FROM
    (
    SELECT
        0 AS job_qty,
        m_products_t.product_id,
        COALESCE(
            (
                CASE WHEN SUM(i_qoh_detail_t.qoh_trx_qty) > 0 THEN SUM(i_qoh_detail_t.qoh_trx_qty) ELSE 0
            END
        ),
        0
) AS Stock,
m_products_t.concatenated_product,
0 AS wip,
0 AS so_qty,
m_products_t.product_subcategory_id,
m_products_t.product_category_id,
m_products_t.min_order_qty,
m_products_t.re_order_level AS max_order_qty
FROM
    m_products_t
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.qoh_source != 'WIP Store Move' AND i_qoh_detail_t.qoh_source != 'MATERIAL RECEIVE' AND i_qoh_detail_t.qoh_source != 'SALES RETURN-SCRAP' AND i_qoh_detail_t.qoh_source != 'Return store move' AND(
        i_qoh_detail_t.subinventory_id = 3 OR i_qoh_detail_t.subinventory_id = 4
    )
WHERE
    m_products_t.product_group_id = 1
GROUP BY
    m_products_t.product_id
UNION ALL
SELECT
    0 AS job_qty,
    m_products_t.product_id,
    0 AS Stock,
    m_products_t.concatenated_product,
    COALESCE(
        (
            CASE WHEN SUM(i_qoh_detail_t.qoh_trx_qty) > 0 THEN SUM(i_qoh_detail_t.qoh_trx_qty) ELSE 0
        END
    ),
    0
) AS wip,
0 AS so_qty,
m_products_t.product_subcategory_id,
m_products_t.product_category_id,
0 AS min_order_qty,
m_products_t.re_order_level AS max_order_qty
FROM
    m_products_t
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.subinventory_id = 5 AND i_qoh_detail_t.locator_id = 191
WHERE
    m_products_t.product_group_id = 1
GROUP BY
    m_products_t.product_id,
    i_qoh_detail_t.batch_number
UNION ALL
SELECT
    0 AS job_qty,
    m_products_t.product_id,
    0 AS Stock,
    m_products_t.concatenated_product,
    0 AS wip,
    COALESCE(
        SUM(
            s_salesorder_lines_t.qty - s_salesorder_lines_t.dispatched_qty
        ),
        0
    ) AS so_qty,
    m_products_t.product_subcategory_id,
    m_products_t.product_category_id,
    0 AS min_order_qty,
    m_products_t.re_order_level AS max_order_qty
FROM
    m_products_t
LEFT JOIN s_salesorder_lines_t ON s_salesorder_lines_t.product_id = m_products_t.product_id
LEFT JOIN s_salesorder_hdr_t ON s_salesorder_hdr_t.sales_hdr_id = s_salesorder_lines_t.sales_hdr_id AND s_salesorder_hdr_t.order_status_id = 'APPROVED'
WHERE
    m_products_t.product_group_id = 1 AND s_salesorder_lines_t.pending_qty > 0 AND s_salesorder_hdr_t.order_status_id = 'APPROVED'
GROUP BY
    m_products_t.product_id
UNION ALL
SELECT
    COALESCE(
        SUM(
            w_jobcard_hdr_t.job_adjusted_qty
        ),
        0
    ) AS job_qty,
    m_products_t.product_id,
    0 AS Stock,
    m_products_t.concatenated_product,
    0 AS wip,
    0 AS so_qty,
    m_products_t.product_subcategory_id,
    m_products_t.product_category_id,
    0 AS min_order_qty,
    m_products_t.max_order_qty
FROM
    m_products_t
LEFT JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.product_id = m_products_t.product_id AND(
        w_jobcard_hdr_t.job_status = 'MATERIAL ISSUED' OR w_jobcard_hdr_t.job_status = 'OPEN'
    ) AND w_jobcard_hdr_t.bom_process = 'PROCESS-1'
WHERE
    m_products_t.product_group_id = 1
GROUP BY
    m_products_t.product_id
) f
LEFT JOIN m_product_subcategory_t ON m_product_subcategory_t.product_subcategory_id = f.product_subcategory_id
LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = f.product_category_id
GROUP BY
    f.product_id) AS g
WHERE
    1 = 1");
     }else if($val=="ROLRMDetails"){
             /*ROL RM Report*/
            
            $this->data['rol_rm']=\DB::select("select concatenated_product,min_stock_level3,stock,stock-min_stock_level3 as order_qty from( SELECT concatenated_product,min_stock_level3,(select sum(i_qoh_detail_t.qoh_trx_qty) from i_qoh_detail_t where i_qoh_detail_t.product_id=m_products_t.product_id) as stock  FROM `m_products_t` WHERE `product_group_id` = 1)f where stock-min_stock_level3 <0");
     }else if($val=="ROLPMDetails"){
            
             /*ROL PM Report*/
            
            $this->data['rol_pm']=\DB::select("select concatenated_product,min_stock_level3,stock,stock-min_stock_level3 as order_qty from( SELECT concatenated_product,min_stock_level3,(select sum(i_qoh_detail_t.qoh_trx_qty) from i_qoh_detail_t where i_qoh_detail_t.product_id=m_products_t.product_id) as stock  FROM `m_products_t` WHERE `product_group_id` = 3)f where stock-min_stock_level3 <0");
     
     }
    }
     $this->data['pageMethod']="ssdb_dashboard";
        return view('secondarydata.ssdb_dashboard',$this->data);
    }

    public function index1($id=null)
    {
			$this->data['pageMethod']=\Request::route()->getName();
      //dd("dfg");
     // dd($id);
      $month=array("01","02","03","04","05","06","07","08","09","10","11","12");
    $qty='';
    $unit_price='';
  
    foreach ($month as $key => $val) {
    //  dd($val);
         $data=\DB::select("SELECT ROUND(sum(p_po_lines_t.qty),2) as qty,ROUND(sum(p_po_lines_t.line_sub_total),2) as unit_price,p_po_hdr_t.supplier_id FROM `p_po_lines_t` left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id=p_po_lines_t.po_hdr_id) where p_po_hdr_t.supplier_id='$id' and month(p_po_lines_t.created_at)='$val' ");
        // dd($data);
        //  dd("SELECT sum(p_po_lines_t.qty) as qty,sum(p_po_lines_t.line_sub_total) as unit_price,p_po_hdr_t.supplier_id FROM `p_po_lines_t` left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id=p_po_lines_t.po_hdr_id) where p_po_hdr_t.supplier_id='$id' and month(p_po_lines_t.created_at)='$val' ");
   // //  echo $data;
if(count($data)>0){
  if($data[0]->qty!=''){
$qty.=$data[0]->qty;
}else{
  $qty.=0;
}
if($data[0]->unit_price!=''){
$unit_price.=$data[0]->unit_price;
  }
  else{
 $unit_price.=0;   
  }
}
  else{
$qty.=0;
$unit_price.=0;
  }
  if($key!=11){
  $qty.=",";
  $unit_price.=",";
}
   
   }
  // dd($unit_price);
$this->data['qty']=$qty;

   $this->data['unit_price']=$unit_price;
  // dd($this->data);
        return $this->data;
    }
public function dashboard_secondarydata()
{
        $date=new \DateTime(date('Y-m-01'));
     $date->modify('-1 year');
    	$this->data['pageMethod']=\Request::route()->getName();
     $date_string=(string)$date->format('Y-m-d');
     for ($i = 1; $i <= 12; $i++) {
         $date->modify('+1 month');
    
     $date_string=(string)$date->format('Y-m-d'); 
    $months_name[] = date("M-y", strtotime( $date_string));
    $months_number[] = date("n", strtotime( $date_string));
   
        }
     $date=new \DateTime(date('Y-m-01'));
     $date=(string)$date->modify('-11 month')->format('Y-m-d');
     
    if($_GET['type']=="customer")
    {
        
        $customer_id=$_GET['customer_id'];
        $data=\DB::select("SELECT month(invoice_date) as month,round(sum(invoice_grand_total)/100000,2) as total FROM `s_invoice_hdr_t` where invoice_status='APPROVED' and invoice_date BETWEEN '$date' and CURRENT_DATE() and ship_to_customer_id='$customer_id' group by month(invoice_date)");
    }
    
    if($_GET['type']=="fg_product")
    {
        
        $product_id=$_GET['product_id'];
        $data=\DB::select("SELECT month(s_invoice_hdr_t.invoice_date) as month,round(sum(s_invoice_lines_t.qty-s_invoice_lines_t.unit_price)/100000,2) as total FROM `s_invoice_hdr_t` join  s_invoice_lines_t on s_invoice_lines_t.invoice_hdr_id=s_invoice_hdr_t.invoice_hdr_id where s_invoice_hdr_t.invoice_status='APPROVED' and s_invoice_hdr_t.invoice_date BETWEEN '$date' and CURRENT_DATE() and s_invoice_lines_t.product_id='$product_id' group by month(s_invoice_hdr_t.invoice_date)");
    }
    
    
       if($_GET['type']=="raw_product")
    {
        
        $product_id=$_GET['product_id'];
        $data=\DB::select("SELECT month(p_po_invoice_hdr_t.invoice_date) as month,round(sum(p_po_invoice_lines_t.qty-p_po_invoice_lines_t.unit_price)/100000,2) as total FROM `p_po_invoice_hdr_t` join p_po_invoice_lines_t on p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id where p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.invoice_date BETWEEN '$date' and CURRENT_DATE() and p_po_invoice_lines_t.product_id='$product_id' group by month(p_po_invoice_hdr_t.invoice_date)");
    }
    
    
          if($_GET['type']=="supplier")
    {
        
        $supplier_id=$_GET['supplier_id'];
        $data=\DB::select("SELECT month(invoice_date) as month,round(sum(invoice_grand_total)/100000,2) as total FROM `p_po_invoice_hdr_t` where po_invoice_status='APPROVED' and invoice_date BETWEEN '$date' and CURRENT_DATE() and supplier_id='$supplier_id' group by month(invoice_date)");
    }
    
     if($_GET['type']=="raw_product_qty")
    {
        
        $product_id=$_GET['product_id'];
        $data=\DB::select("SELECT month(p_po_invoice_hdr_t.invoice_date) as month,sum(p_po_invoice_lines_t.qty) as total FROM `p_po_invoice_hdr_t` join p_po_invoice_lines_t on p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id where p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.invoice_date BETWEEN '$date' and CURRENT_DATE() and p_po_invoice_lines_t.product_id='$product_id' group by month(p_po_invoice_hdr_t.invoice_date)");
        
      $product_data=  \DB::select("SELECT (select m_uom_codes_t.uom_code from m_uom_codes_t where m_uom_codes_t.uom_code_id=m_products_t.primary_uom_id) as uom FROM `m_products_t` where product_id='$product_id'");
        
    }
    
    
       if($_GET['type']=="fg_op_qty")
    {
        
        $product_id=$_GET['product_id'];
        $data=\DB::select("select month(job_completion_date) as month,round(sum(COALESCE((select w_qa_submitstage_trx_t.production_qty from w_qa_submitstage_trx_t where w_qa_submitstage_trx_t.job_no=w_jobcard_hdr_t.w_jobs_hdr_id limit 1),0)),2) as total from w_jobcard_hdr_t where bom_process!='' and job_completion_date between '$date' and CURRENT_DATE() and product_id='$product_id' group by month(job_completion_date)");
        
      $product_data=  \DB::select("SELECT (select m_uom_codes_t.uom_code from m_uom_codes_t where m_uom_codes_t.uom_code_id=m_products_t.primary_uom_id) as uom FROM `m_products_t` where product_id='$product_id'");
        
    }
    
           if($_GET['type']=="sfg_qty")
    {
        
        $product_id=$_GET['product_id'];
        $data=\DB::select("select month(job_completion_date) as month,round(sum(COALESCE((select w_qa_submitstage_trx_t.production_qty from w_qa_submitstage_trx_t where w_qa_submitstage_trx_t.job_no=w_jobcard_hdr_t.w_jobs_hdr_id limit 1),0)),2) as total from w_jobcard_hdr_t where bom_process='' and job_completion_date between '$date' and CURRENT_DATE() and product_id='$product_id' group by month(job_completion_date)");
        
      $product_data=  \DB::select("SELECT (select m_uom_codes_t.uom_code from m_uom_codes_t where m_uom_codes_t.uom_code_id=m_products_t.primary_uom_id) as uom FROM `m_products_t` where product_id='$product_id'");
        
    }
    
    
   $data=collect($data);
   $invoice_data=array();
    foreach($months_number as $val)
    {
        $prd_data=$data->where('month',$val);
        $prd_data->all();
        //dd($prd_data);
        if(count($prd_data)>0)
        {
            foreach($prd_data as $v)
            {
          $invoice_data[]=floatval($v->total);
            
                break;
            }
        }
        else
        {
           $invoice_data[]=0; 
        }
        
        
        }
        
         if($_GET['type']=="raw_product_qty" || $_GET['type']=="sfg_qty" || $_GET['type']=="fg_op_qty")
    {
        $data['chart']=$invoice_data;
        $data['uom']=$product_data[0]->uom;
        return $data;
    }
        
   
     return $invoice_data;
     
}    
    
}

