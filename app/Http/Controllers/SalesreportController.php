<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use DB;

class SalesreportController extends Controller{
	
	      public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }
	
	
    public function pendingsoindex(){
	
	return view('salesreport.table',$this->data);		
	}


	public function getsopendingqty(Request $request)
		{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (SELECT s_salesorder_hdr_t.sales_hdr_id,
    s_salesorder_hdr_t.sales_order_no,
    REPLACE(s_salesorder_hdr_t.order_type_id,'STANDARD', 'SALES') as order_type_id,
    s_salesorder_hdr_t.ship_to_customer_id,
    case when s_salesorder_hdr_t.ship_to_customer_id!=0 then  concat(m_customers_t.customer_number,'-',m_customers_t.customer_name) else concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) end as customer_name,
    s_salesorder_hdr_t.sales_order_date,
    s_salesorder_hdr_t.order_status_id,
    s_salesorder_lines_t.qty,
    s_salesorder_lines_t.pending_qty,
    s_salesorder_lines_t.free_qty,
    s_salesorder_lines_t.delivery_date,
    s_salesorder_lines_t.product_id,
    m_products_t.concatenated_product
    FROM s_salesorder_hdr_t
    left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id)
    left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) 
	left join hr_employee_t on (hr_employee_t.employee_id=s_salesorder_hdr_t.employee_id)
    left join m_products_t on(s_salesorder_lines_t.product_id=m_products_t.product_id)
    where 1=1 AND s_salesorder_hdr_t.sales_order_date BETWEEN ? And ? and s_salesorder_hdr_t.order_status_id !='COMPLETED' and s_salesorder_hdr_t.order_status_id !='CLOSED' 
    and s_salesorder_hdr_t.order_status_id !='CANCELLED' and s_salesorder_hdr_t.order_status_id !='REJECTED' and s_salesorder_lines_t.pending_qty > 0) AS v1";


    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
	
        
	public function Salesorderdetailsreport($sid=null){
		$so=\DB::Select("SELECT s_salesorder_hdr_t.sales_order_no,s_salesorder_hdr_t.sales_hdr_id,s_salesorder_lines_t.product_id,s_salesorder_lines_t.qty,m_products_t.concatenated_product as product_name FROM `s_salesorder_hdr_t` left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id) left join m_products_t on(s_salesorder_lines_t.product_id=m_products_t.product_id) where s_salesorder_hdr_t.order_type_id='STANDARD' and s_salesorder_hdr_t.sales_hdr_id='".$sid."'");
	
	$sodispatch=\DB::select('SELECT s_dispatch_hdr_t.so_dispatch_hdr_id,s_dispatch_hdr_t.`dispatch_number`,s_dispatch_hdr_t.reference_source_id,s_dispatch_hdr_t.reference_no,s_dispatch_hdr_t.dispatch_source,s_dispatch_lines_t.product_id,s_dispatch_lines_t.dispatch_qty,s_dispatch_lines_t.so_qty,s_dispatch_lines_t.dispatched_qty,m_products_t.concatenated_product as product_name,s_dispatch_hdr_t.reference_source_id,s_invoice_hdr_t.invoice_number,s_invoice_hdr_t.invoice_hdr_id FROM `s_dispatch_hdr_t` left join s_dispatch_lines_t on(s_dispatch_lines_t.so_dispatch_hdr_id=s_dispatch_hdr_t.so_dispatch_hdr_id) left join s_salesorder_hdr_t on(s_dispatch_hdr_t.reference_source_id=s_salesorder_hdr_t.sales_hdr_id) and s_dispatch_hdr_t.dispatch_source="SALES ORDER" left join m_products_t on(s_dispatch_lines_t.product_id=m_products_t.product_id)
	left join s_invoice_hdr_t on(s_invoice_hdr_t.invoice_hdr_id=s_dispatch_hdr_t.reference_source_id) and s_dispatch_hdr_t.dispatch_source="INVOICE"
	WHERE 1=1 and s_dispatch_hdr_t.ar_sales_hdr_id="'.$sid.'"');
	//dd($sodispatch);
	
		$invoice =\DB::select('SELECT 
soinv.invoice_hdr_id,
soinv.invoice_number,
soinv.source,
soinv.reference_number,
soinvln.salesorder_qty,
sodisp.dispatch_number,
sodisp.so_dispatch_hdr_id,
soinv.invoice_date,
soinv.created_at,
soinvln.product_id,
soinv.reference_source_id,
m_products_t.concatenated_product as product_name,
soinvln.qty
FROM s_invoice_hdr_t soinv 
LEFT JOIN s_invoice_lines_t soinvln on (soinv.invoice_hdr_id=soinvln.invoice_hdr_id)
left join m_products_t on(soinvln.product_id=m_products_t.product_id)
LEFT JOIN s_salesorder_hdr_t sohdr on (sohdr.sales_hdr_id=soinv.reference_source_id) AND soinv.source="SALES ORDER"  
LEFT JOIN s_dispatch_hdr_t sodisp on (sodisp.so_dispatch_hdr_id=soinv.reference_source_id) AND soinv.source="DISPATCH"
 where  soinv.ar_sales_hdr_id="'.$sid.'"');
//dd($invoice);	
	$soreturn=\DB::select("SELECT so_rma_hdr_t.so_rma_hdr_id,so_rma_hdr_t.rma_ref_no,so_rma_hdr_t.reference_source_id,so_rma_lines_t.product_id,so_rma_lines_t.return_qty,s_salesorder_hdr_t.sales_hdr_id,s_invoice_hdr_t.invoice_number FROM `so_rma_hdr_t` left join so_rma_lines_t on(so_rma_lines_t.so_rma_hdr_id=so_rma_hdr_t.so_rma_hdr_id) left join s_invoice_hdr_t on(s_invoice_hdr_t.invoice_hdr_id=so_rma_hdr_t.reference_source_id) left join s_salesorder_hdr_t on(s_salesorder_hdr_t.sales_hdr_id=s_invoice_hdr_t.ar_sales_hdr_id) where s_salesorder_hdr_t.sales_hdr_id='".$sid."'");
	
	
		$data=array();
	foreach($so as $key=>$val)
	{
		
		$id=$val->product_id;
		$data['SO_QTY'][$id][]=$val->qty;
		
			$array_key = array_column($sodispatch,'product_id');
			$array_key_val=array_keys($array_key,$id);
		$i=0;

			foreach($array_key_val as $array_key)
			{
				if(!empty($sodispatch)!=""){
				$so_dispatch_hdr_id=$sodispatch[$array_key]->so_dispatch_hdr_id;
				
				$dispatch_number=$sodispatch[$array_key]->dispatch_number;
				$dispatch_qty=$sodispatch[$array_key]->dispatch_qty;
					$reference_hdr_id=$sodispatch[$array_key]->reference_source_id;
					$reference_number=$sodispatch[$array_key]->reference_no;
					$source=$sodispatch[$array_key]->dispatch_source;
				}else{
				$so_dispatch_hdr_id="";
				$dispatch_number="";
				$dispatch_qty="";
				$reference_hdr_id="";
				$reference_number="";
				$source="";
				}
				if($source=="INVOICE"){
			$html="<a href='".url("soinvoice_view/$reference_hdr_id")."' target='_blank'> $reference_number </a> =><a href='".url("dispatch_view/$so_dispatch_hdr_id")."' target='_blank'> $dispatch_number </a> => $dispatch_qty";	
				}else if($source=="SALES ORDER"){
						$html="<a href='".url("so_view/$reference_hdr_id?close=soorder")."' target='_blank'> $reference_number </a> => <a href='".url("dispatch_view/$so_dispatch_hdr_id")."' target='_blank'> $dispatch_number </a> => $dispatch_qty";	
				}else{
				$html="";	
				}
			$data['DISPATCH_QTY'][$id][$i]=$html;
				$i++;
			}

		//Invoice
		$array_key = array_column($invoice,'product_id');
		
			$array_key_val=array_keys($array_key,$id);
		$i=0;
			foreach($array_key_val as $array_key)
			{
				$so_dispatch_hdr_id=$invoice[$array_key]->so_dispatch_hdr_id;
				$reference_source_id=$invoice[$array_key]->reference_source_id;
				$reference_number=$invoice[$array_key]->reference_number;
				$so_qty=$invoice[$array_key]->salesorder_qty;
				$qty=$invoice[$array_key]->qty;
				$invoice_number=$invoice[$array_key]->invoice_number;
				$invoice_hdr_id=$invoice[$array_key]->invoice_hdr_id;
				$source=$invoice[$array_key]->source;
			if($source=="DISPATCH"){
			$html="<a href='".url("dispatch_view/$reference_source_id")."' target='_blank'> $reference_number </a> => <a href='".url("soinvoice_view/$invoice_hdr_id")."' target='_blank'> $invoice_number </a> =>$qty";	
			}else if($source=="SALES ORDER"){
			$html="<a href='".url("so_view/$reference_source_id?close=soorder")."' target='_blank'> $reference_number </a> => <a href='".url("soinvoice_view/$invoice_hdr_id")."' target='_blank'> $invoice_number </a> =>$qty";	
			}else{
				$html="";
			}
			$data['Invoice_QTY'][$id][$i]=$html;
				$i++;
			}
		
		// Return
		//Invoice
		$array_key = array_column($soreturn,'product_id');
		
			$array_key_val=array_keys($array_key,$id);
		$i=0;
			foreach($array_key_val as $array_key)
			{
				
				$invoice_hdr_id=$soreturn[$array_key]->reference_source_id;
				
				$invoice_number=$soreturn[$array_key]->invoice_number;
				$receive_qty=$soreturn[$array_key]->return_qty;
				$return_invoice_number=$soreturn[$array_key]->rma_ref_no;
				$return_header_id=$soreturn[$array_key]->so_rma_hdr_id;
			
			$html="<a href='".url("soinvoice_view/$invoice_hdr_id")."' target='_blank'> $invoice_number </a> => <a href='".url("soinvoice_view/$return_header_id")."' target='_blank'> $return_invoice_number </a> =>$receive_qty";	
			
			$data['Return_QTY'][$id][$i]=$html;
				$i++;
			}
    }
	
	
	
$this->data['data']=$data;	
$this->data['soorder']=$so;

return view('salesreport.view',$this->data);	
}

    public function salesvssamplereport(){
        
     return view('salesreport.salesvssample',$this->data);
             
             
    }
	
    public function salesvssamplereportdata(){
            
    if($_GET['type']=='month')
    {
            $start_date=date('Y-m-d',strtotime($_GET['start_date']));
            $end_date=date('Y-m-d',strtotime($_GET['end_date']));
            
    
            
            
    $data= \DB::select("select invoice_date,m,y,customer_type_id,customer_type,concatenated_product,round(sum(sample),2)as sample,round(sum(qty),2) as qty,concat(round((sum(sample)/sum(qty))*100,2),'%') as ra from (SELECT invoice_date,month(invoice_date) as m,year(invoice_date) as y,m_customer_types_t.customer_type_id,m_customer_types_t.customer_type,REPLACE(m_products_t.concatenated_product,i_product_packs.pack_name,'')concatenated_product,sum((s_invoice_lines_t.qty*i_product_packs.pack_value)/1000)as sample,0 as qty FROM `s_invoice_hdr_t`   join hr_employee_t on hr_employee_t.employee_id=s_invoice_hdr_t.employee_id join s_invoice_lines_t on s_invoice_lines_t.invoice_hdr_id=s_invoice_hdr_t.invoice_hdr_id join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id join i_product_packs on i_product_packs.packing_id=m_products_t.product_pack_id join m_customer_types_t on m_customer_types_t.customer_type_id=hr_employee_t.zone_id where invoice_date BETWEEN '$start_date' and '$end_date' and invoice_type='SAMPLE' and invoice_status='APPROVED' group by m_products_t.product_id,m_customer_types_t.customer_type_id,month(invoice_date),year(invoice_date)

union all 

SELECT invoice_date,month(invoice_date) as m,year(invoice_date) as y,m_customers_t.customer_type_id,m_customer_types_t.customer_type,REPLACE(m_products_t.concatenated_product,i_product_packs.pack_name,'')concatenated_product,0 as sample,sum((s_invoice_lines_t.qty*i_product_packs.pack_value)/1000)as qty FROM `s_invoice_hdr_t`   join m_customers_t on m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id join s_invoice_lines_t on s_invoice_lines_t.invoice_hdr_id=s_invoice_hdr_t.invoice_hdr_id join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id join i_product_packs on i_product_packs.packing_id=m_products_t.product_pack_id join m_customer_types_t on m_customer_types_t.customer_type_id=m_customers_t.customer_type_id where invoice_date BETWEEN '$start_date' and '$end_date' and invoice_type='STANDARD' and invoice_status='APPROVED' group by m_products_t.product_id,m_customers_t.customer_type_id,month(invoice_date),year(invoice_date))v where sample!=0 or qty!=0 group by m,y");
      
$html='<h4 class="text-center text-white bg-danger fw-bold">Month Wise Sales VS Sample</h4><table  id="month"><thead><th>Month</th><th>Sample</th><th>Sales</th><th>Ratio</th></thead><tbody>';      
    
    $sample=0;
    $qty=0;
           
    foreach($data as $val)
    {
     $sample+=$val->sample;
     $qty+=$val->qty;
     $html.="<tr><td><a href='#' data-from='".date('01-m-Y',strtotime($val->invoice_date))."' data-to='".date('t-m-Y',strtotime($val->invoice_date))."' class='zonedata'>".date('M-y',strtotime($val->invoice_date))."</a></td><td>".number_format($val->sample,2)."</td><td>".str_replace(',','',number_format($val->qty,2))."</td><td>".$val->ra."</td></tr>";
     
        
    }
    
    $html.="<tr style='
    background-color: #fdc1e5;
    /* font-weight: 900; */
    /* font-weight: bolder; */
    color: black;'><td><a href='#' data-from='".date('01-m-Y',strtotime($start_date))."' data-to='".date('t-m-Y',strtotime($end_date))."' class='zonedata'>Total</a></td><td>".str_replace(',','',number_format($sample,2))."</td><td>".str_replace(',','',number_format($qty,2))."</td><td>".round(($sample/$qty)*100)."%</td></tr>";
     
    
    $html.="</tbody></table>";
           
        return $html; 
    }
    else if($_GET['type']=='zone')
    {
            $start_date=date('Y-m-01',strtotime($_GET['start_date']));
            $end_date=date('Y-m-t',strtotime($_GET['end_date']));
            $month=date('M-y',strtotime($_GET['start_date']));
            $month_end=date('M-y',strtotime($_GET['end_date']));
            
    
            
            
    $data= \DB::select("select invoice_date,m,y,customer_type_id,customer_type,concatenated_product,round(sum(sample),2)as sample,round(sum(qty),2) as qty,concat(round((sum(sample)/sum(qty))*100,2),'%') as ra from (SELECT invoice_date,month(invoice_date) as m,year(invoice_date) as y,m_customer_types_t.customer_type_id,m_customer_types_t.customer_type,REPLACE(m_products_t.concatenated_product,i_product_packs.pack_name,'')concatenated_product,sum((s_invoice_lines_t.qty*i_product_packs.pack_value)/1000)as sample,0 as qty FROM `s_invoice_hdr_t`   join hr_employee_t on hr_employee_t.employee_id=s_invoice_hdr_t.employee_id join s_invoice_lines_t on s_invoice_lines_t.invoice_hdr_id=s_invoice_hdr_t.invoice_hdr_id join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id join i_product_packs on i_product_packs.packing_id=m_products_t.product_pack_id join m_customer_types_t on m_customer_types_t.customer_type_id=hr_employee_t.zone_id where invoice_date BETWEEN '$start_date' and '$end_date' and invoice_status='APPROVED' and invoice_type='SAMPLE' group by m_products_t.product_id,m_customer_types_t.customer_type_id,month(invoice_date),year(invoice_date)

union all 

SELECT invoice_date,month(invoice_date) as m,year(invoice_date) as y,m_customers_t.customer_type_id,m_customer_types_t.customer_type,REPLACE(m_products_t.concatenated_product,i_product_packs.pack_name,'')concatenated_product,0 as sample,sum((s_invoice_lines_t.qty*i_product_packs.pack_value)/1000)as qty FROM `s_invoice_hdr_t`   join m_customers_t on m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id join s_invoice_lines_t on s_invoice_lines_t.invoice_hdr_id=s_invoice_hdr_t.invoice_hdr_id join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id join i_product_packs on i_product_packs.packing_id=m_products_t.product_pack_id join m_customer_types_t on m_customer_types_t.customer_type_id=m_customers_t.customer_type_id where invoice_date BETWEEN '$start_date' and '$end_date' and invoice_status='APPROVED' and invoice_type='STANDARD' group by m_products_t.product_id,m_customers_t.customer_type_id,month(invoice_date),year(invoice_date))v where sample!=0 or qty!=0 group by customer_type_id");
      
$html="<h4 class='text-center text-white bg-danger fw-bold'>Zone Wise Sales VS Sample of $month to $month_end</h4><table  id='zone'><thead><th>Zone</th><th>Sample</th><th>Sales</th><th>Ratio</th></thead><tbody>";      
           
    $sample=0;
    $qty=0;
           
    foreach($data as $val)
    {
     $sample+=$val->sample;
     $qty+=$val->qty;
     
     $html.="<tr><td><a href='#' data-from='".date('01-m-Y',strtotime($start_date))."' data-to='".date('t-m-Y',strtotime($end_date))."' class='subcatdata' data-zone='".$val->customer_type_id."'>".$val->customer_type."</a></td><td>".number_format($val->sample,2)."</td><td>".str_replace(',','',number_format($val->qty,2))."</td><td>".$val->ra."</td></tr>";
     
        
    }
    
    $html.="<tr style='
    background-color: #fdc1e5;
    /* font-weight: 900; */
    /* font-weight: bolder; */
    color: black;'><td><a href='#' data-from='".date('01-m-Y',strtotime($start_date))."' data-to='".date('t-m-Y',strtotime($end_date))."' class='subcatdata' data-zone='0'>Total</a></td><td>".str_replace(',','',number_format($sample,2))."</td><td>".str_replace(',','',number_format($qty,2))."</td><td>".round(($sample/$qty)*100)."%</td></tr>";
     
    
    
    $html.="</tbody></table>";
           
        return $html;  
    }
    else if($_GET['type']=='sub_cat')
    {
            $start_date=date('Y-m-01',strtotime($_GET['start_date']));
            $end_date=date('Y-m-t',strtotime($_GET['end_date']));
            $month=date('M-y',strtotime($_GET['start_date']));
            $month_end=date('M-y',strtotime($_GET['end_date']));
    $wh='';
    if($_GET['zone']!=0)
    {
    $wh="customer_type_id='".$_GET['zone']."' and ";
    }
            
            
    $data= \DB::select("select subcategory_name,invoice_date,m,y,customer_type_id,customer_type,concatenated_product,round(sum(sample),2)as sample,round(sum(qty),2) as qty,concat(round((sum(sample)/sum(qty))*100,2),'%') as ra from (SELECT m_product_subcategory_t.subcategory_name,invoice_date,month(invoice_date) as m,year(invoice_date) as y,m_customer_types_t.customer_type_id,m_customer_types_t.customer_type,REPLACE(m_products_t.concatenated_product,i_product_packs.pack_name,'')concatenated_product,sum((s_invoice_lines_t.qty*i_product_packs.pack_value)/1000)as sample,0 as qty FROM `s_invoice_hdr_t`   join hr_employee_t on hr_employee_t.employee_id=s_invoice_hdr_t.employee_id join s_invoice_lines_t on s_invoice_lines_t.invoice_hdr_id=s_invoice_hdr_t.invoice_hdr_id join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id  join m_product_subcategory_t on m_product_subcategory_t.product_subcategory_id=m_products_t.product_subcategory_id  join i_product_packs on i_product_packs.packing_id=m_products_t.product_pack_id join m_customer_types_t on m_customer_types_t.customer_type_id=hr_employee_t.zone_id where invoice_date BETWEEN '$start_date' and '$end_date' and invoice_status='APPROVED' and invoice_type='SAMPLE' group by m_products_t.product_id,m_customer_types_t.customer_type_id,month(invoice_date),year(invoice_date)

union all 

SELECT m_product_subcategory_t.subcategory_name, invoice_date,month(invoice_date) as m,year(invoice_date) as y,m_customers_t.customer_type_id,m_customer_types_t.customer_type,REPLACE(m_products_t.concatenated_product,i_product_packs.pack_name,'')concatenated_product,0 as sample,sum((s_invoice_lines_t.qty*i_product_packs.pack_value)/1000)as qty FROM `s_invoice_hdr_t`   join m_customers_t on m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id join s_invoice_lines_t on s_invoice_lines_t.invoice_hdr_id=s_invoice_hdr_t.invoice_hdr_id join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id join m_product_subcategory_t on m_product_subcategory_t.product_subcategory_id=m_products_t.product_subcategory_id join i_product_packs on i_product_packs.packing_id=m_products_t.product_pack_id join m_customer_types_t on m_customer_types_t.customer_type_id=m_customers_t.customer_type_id where invoice_date BETWEEN '$start_date' and '$end_date' and invoice_status='APPROVED' and invoice_type='STANDARD' group by m_products_t.product_id,m_customers_t.customer_type_id,month(invoice_date),year(invoice_date))v where $wh  (sample!=0 or qty!=0) group by subcategory_name");
      
$html="<h4 class='text-center text-white bg-danger fw-bold'>Sub Category Wise Sales VS Sample of $month to $month_end</h4><table  id='sub_cat'><thead><th>Sub Category</th><th>Sample</th><th>Sales</th><th>Ratio</th></thead><tbody>";      
           
    $sample=0;
    $qty=0;
           
    foreach($data as $val)
    {
     $sample+=$val->sample;
     $qty+=$val->qty;
     $bac='';
    $ra= $val->ra;
     if($val->ra=='')
     {
         $bac='style="
    background: #95b2db;
    color: white;
    
"';
$ra='100.00%';
     }
    $customer_type_id= $val->customer_type_id;
     if($_GET['zone']!=0)
    {
       $customer_type_id=0; 
    }
     
     
     $html.="<tr><td><a href='#' data-from='".date('01-m-Y',strtotime($start_date))."' data-to='".date('t-m-Y',strtotime($end_date))."' class='productdata' data-zone='".$customer_type_id."' data-sub='".$val->subcategory_name."'>".$val->subcategory_name."</a></td><td>".str_replace(',','',number_format($val->sample,2))."</td><td >".str_replace(',','',number_format($val->qty,2))."</td><td $bac>".$ra."</td></tr>";
     
        
    }
    
     $html.="<tr><td><a href='#' data-from='".date('01-m-Y',strtotime($start_date))."' data-to='".date('t-m-Y',strtotime($end_date))."' class='productdata' data-zone='".$_GET['zone']."' data-sub='0'>Total</a></td><td>".str_replace(',','',number_format($sample,2))."</td><td >".str_replace(',','',number_format($qty,2))."</td><td >".round(($sample/$qty)*100)."%</td></tr>";
     
    
    $html.="</tbody></table>";
           
        return $html;  
    }
     else if($_GET['type']=='product')
    {
            $start_date=date('Y-m-01',strtotime($_GET['start_date']));
            $end_date=date('Y-m-t',strtotime($_GET['end_date']));
            $month=date('M-y',strtotime($_GET['start_date']));
            $month_end=date('M-y',strtotime($_GET['end_date']));
     
     $wh='';
    if($_GET['zone']!=0)
    {
    $wh="customer_type_id='".$_GET['zone']."' and ";
    }
    if($_GET['sub_name']!=0)
    {
    $wh="subcategory_name='".$_GET['sub_name']."' and ";
    }
            
    
            
            
    $data= \DB::select("select subcategory_name,invoice_date,m,y,customer_type_id,customer_type,concatenated_product,round(sum(sample),2)as sample,round(sum(qty),2) as qty,concat(round((sum(sample)/sum(qty))*100,2),'%') as ra from (SELECT m_product_subcategory_t.subcategory_name,invoice_date,month(invoice_date) as m,year(invoice_date) as y,m_customer_types_t.customer_type_id,m_customer_types_t.customer_type,REPLACE(m_products_t.concatenated_product,i_product_packs.pack_name,'')concatenated_product,sum((s_invoice_lines_t.qty*i_product_packs.pack_value)/1000)as sample,0 as qty FROM `s_invoice_hdr_t`   join hr_employee_t on hr_employee_t.employee_id=s_invoice_hdr_t.employee_id join s_invoice_lines_t on s_invoice_lines_t.invoice_hdr_id=s_invoice_hdr_t.invoice_hdr_id join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id  join m_product_subcategory_t on m_product_subcategory_t.product_subcategory_id=m_products_t.product_subcategory_id  join i_product_packs on i_product_packs.packing_id=m_products_t.product_pack_id join m_customer_types_t on m_customer_types_t.customer_type_id=hr_employee_t.zone_id where invoice_date BETWEEN '$start_date' and '$end_date' and invoice_status='APPROVED' and invoice_type='SAMPLE' group by m_products_t.product_id,m_customer_types_t.customer_type_id,month(invoice_date),year(invoice_date)

union all 

SELECT m_product_subcategory_t.subcategory_name, invoice_date,month(invoice_date) as m,year(invoice_date) as y,m_customers_t.customer_type_id,m_customer_types_t.customer_type,REPLACE(m_products_t.concatenated_product,i_product_packs.pack_name,'')concatenated_product,0 as sample,sum((s_invoice_lines_t.qty*i_product_packs.pack_value)/1000)as qty FROM `s_invoice_hdr_t`   join m_customers_t on m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id join s_invoice_lines_t on s_invoice_lines_t.invoice_hdr_id=s_invoice_hdr_t.invoice_hdr_id join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id join m_product_subcategory_t on m_product_subcategory_t.product_subcategory_id=m_products_t.product_subcategory_id join i_product_packs on i_product_packs.packing_id=m_products_t.product_pack_id join m_customer_types_t on m_customer_types_t.customer_type_id=m_customers_t.customer_type_id where invoice_date BETWEEN '$start_date' and '$end_date' and invoice_status='APPROVED' and invoice_type='STANDARD' group by m_products_t.product_id,m_customers_t.customer_type_id,month(invoice_date),year(invoice_date))v where $wh  (sample!=0 or qty!=0) group by concatenated_product");
      
$html="<h4 class='text-center text-white bg-danger fw-bold'>Product Wise Sales VS Sample of $month to $month_end</h4><table  id='product'><thead><th>Product</th><th>Sample</th><th>Sales</th><th>Ratio</th></thead><tbody>";      
           
    foreach($data as $val)
    {
     $bac='';
    $ra= $val->ra;
     if($val->ra=='')
     {
         $bac='style="
    background: #6e00ff94;
    color: white;
    
"';
$ra='100.00%';
     }
     
     $html.="<tr><td>".$val->concatenated_product."</td><td>".str_replace(',','',number_format($val->sample,2))."</td><td >".str_replace(',','',number_format($val->qty,2))."</td><td $bac>".$ra."</td></tr>";
     
        
    }
    $html.="</tbody></table>";
           
        return $html;  
    }       
           
             
         }
}
