<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use yajra\datatables\datatables;

class ProductionreportController extends Controller
{
	
		public function __construct()
	{

		$this->data['pageMethod']=\Request::route()->getName();

	}
	
    public function pendingsoindex()
{
$comp=\Session::get('companyid');	
$SQL =\DB::select("SELECT s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,s_salesorder_hdr_t.ship_to_customer_id,concat(m_customers_t.customer_number,'-',m_customers_t.customer_name)as cus_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_lines_t.qty,s_salesorder_lines_t.pending_qty,s_salesorder_lines_t.delivery_date,s_salesorder_lines_t.product_id,m_products_t.concatenated_product as product_name FROM `s_salesorder_hdr_t` left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id) left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) left join m_products_t on(s_salesorder_lines_t.product_id=m_products_t.product_id) WHERE s_salesorder_lines_t.delivery_date < CURDATE() and s_salesorder_hdr_t.company_id=".$comp." ORDER BY s_salesorder_hdr_t.sales_hdr_id ASC");
$this->data['result']=json_encode($SQL);	
return view('productionreport.table',$this->data);		
}

public function pendingsoindex1()
{
$comp=\Session::get('companyid');	
$SQL =\DB::select("SELECT s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,s_salesorder_hdr_t.ship_to_customer_id,concat(m_customers_t.customer_number,'-',m_customers_t.customer_name)as cus_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_lines_t.qty,s_salesorder_lines_t.pending_qty,s_salesorder_lines_t.delivery_date,s_salesorder_lines_t.product_id,m_products_t.concatenated_product as product_name FROM `s_salesorder_hdr_t` left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id) left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) left join m_products_t on(s_salesorder_lines_t.product_id=m_products_t.product_id) WHERE s_salesorder_lines_t.delivery_date < CURDATE() and s_salesorder_hdr_t.company_id=".$comp." ORDER BY s_salesorder_hdr_t.sales_hdr_id ASC");
$this->data['result']=json_encode($SQL);	
return view('productionreport.table1',$this->data);		
}


public function scheduleindex()
{
$comp=\Session::get('companyid');	
$SQL =\DB::select("SELECT w_productionplan_hdr_t.plan_date,w_jobcard_hdr_t.job_status,m_products_t_jrk.concatenated_product,i_product_packs.pack_name,w_jobcard_hdr_t.batch_no,m_material_bom_lines_t.process_name,w_productionplan_hdr_t.production_qty  FROM `w_productionplan_hdr_t` left join w_jobcard_hdr_t on(w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.w_jobs_hdr_id) left join m_products_t_jrk on(m_products_t_jrk.product_id=w_productionplan_hdr_t.productionplan_hdr_id) left join i_product_packs on(w_productionplan_hdr_t.productionplan_hdr_id=i_product_packs.packing_id) left join w_jobcard_hdr_t on(w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.w_jobs_hdr_id) left join m_material_bom_lines_t on(w_productionplan_hdr_t.productionplan_hdr_id=m_material_bom_lines_t.material_bom_line_id) WHERE w_productionplan_hdr_t.productionplan_hdr_id=".$comp." ORDER BY w_productionplan_hdr_t.productionplan_hdr_id ASC");
$this->data['result']=json_encode($SQL);	
return view('productionreport.scheduletable',$this->data);		
}



public function scheduleqty(){
         $wh='';
        if(isset($_GET['pq_filter']))
         {
         $data=json_decode($_GET['pq_filter']);
         $data=$data->data;
         $table=array('m_customers_t','s_salesorder_lines_t','m_products_t');

      $wh.=$this->pqgridsearch('s_salesorder_hdr_t',$data,$table);
         }

            $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
      $sidx='';
        if (!$sidx)
            $sidx = 1;
              $result = \DB::select("SELECT w_productionplan_hdr_t.plan_date,w_jobcard_hdr_t.job_status,m_products_t_jrk.concatenated_product,i_product_packs.pack_name,w_jobcard_hdr_t.batch_no,m_material_bom_lines_t.process_name,w_productionplan_hdr_t.production_qty  FROM `w_productionplan_hdr_t` left join w_jobcard_hdr_t on(w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.w_jobs_hdr_id) left join m_products_t_jrk on(m_products_t_jrk.product_id=w_productionplan_hdr_t.productionplan_hdr_id) left join i_product_packs on(w_productionplan_hdr_t.productionplan_hdr_id=i_product_packs.packing_id) left join w_jobcard_hdr_t on(w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.w_jobs_hdr_id) left join m_material_bom_lines_t on(w_productionplan_hdr_t.productionplan_hdr_id=m_material_bom_lines_t.material_bom_line_id) where 1=1 $wh");
		
              $count = $result[0]->count;
		if( $count > 0 && $limit > 0)
		{
		$total_pages = ceil($count/$limit);
		} else {
		$total_pages = 0;
		}
		if ($page > $total_pages)
		$page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;

        $compy=\Session::get('companyid');
        
$SQL ="SELECT w_productionplan_hdr_t.plan_date,w_jobcard_hdr_t.job_status,m_products_t_jrk.concatenated_product,i_product_packs.pack_name,w_jobcard_hdr_t.batch_no,m_material_bom_lines_t.process_name,w_productionplan_hdr_t.production_qty  FROM `w_productionplan_hdr_t` left join w_jobcard_hdr_t on(w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.w_jobs_hdr_id) left join m_products_t_jrk on(m_products_t_jrk.product_id=w_productionplan_hdr_t.productionplan_hdr_id) left join i_product_packs on(w_productionplan_hdr_t.productionplan_hdr_id=i_product_packs.packing_id) left join w_jobcard_hdr_t on(w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.w_jobs_hdr_id) left join m_material_bom_lines_t on(w_productionplan_hdr_t.productionplan_hdr_id=m_material_bom_lines_t.material_bom_line_id)
       where 1=1 $wh and w_productionplan_hdr_t.productionplan_hdr_id =$compy  ORDER BY $sidx LIMIT $start , $limit  ";

$result = \DB::select( $SQL );

		$responce->rows[]='';
		$responce->data=$result;
		$responce->curPage = $page;
		$responce->total = $total_pages;
		$responce->totalRecords = $count;
		echo json_encode($responce);
	}




public function mcwiseindex()
{
$comp=\Session::get('companyid');	
$SQL =\DB::select("SELECT s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,s_salesorder_hdr_t.ship_to_customer_id,concat(m_customers_t.customer_number,'-',m_customers_t.customer_name)as cus_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_lines_t.qty,s_salesorder_lines_t.pending_qty,s_salesorder_lines_t.delivery_date,s_salesorder_lines_t.product_id,m_products_t.concatenated_product as product_name FROM `s_salesorder_hdr_t` left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id) left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) left join m_products_t on(s_salesorder_lines_t.product_id=m_products_t.product_id) WHERE s_salesorder_lines_t.delivery_date < CURDATE() and s_salesorder_hdr_t.company_id=".$comp." ORDER BY s_salesorder_hdr_t.sales_hdr_id ASC");
$this->data['result']=json_encode($SQL);	
return view('productionreport.mcwisetable',$this->data);		
}



public function employeerptindex()
{
$comp=\Session::get('companyid');	
$SQL =\DB::select("SELECT s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,s_salesorder_hdr_t.ship_to_customer_id,concat(m_customers_t.customer_number,'-',m_customers_t.customer_name)as cus_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_lines_t.qty,s_salesorder_lines_t.pending_qty,s_salesorder_lines_t.delivery_date,s_salesorder_lines_t.product_id,m_products_t.concatenated_product as product_name FROM `s_salesorder_hdr_t` left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id) left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) left join m_products_t on(s_salesorder_lines_t.product_id=m_products_t.product_id) WHERE s_salesorder_lines_t.delivery_date < CURDATE() and s_salesorder_hdr_t.company_id=".$comp." ORDER BY s_salesorder_hdr_t.sales_hdr_id ASC");
$this->data['result']=json_encode($SQL);	
return view('productionreport.employeerpttable',$this->data);		
}


public function mcdtlsindex()
{
$comp=\Session::get('companyid');	
$SQL =\DB::select("SELECT s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,s_salesorder_hdr_t.ship_to_customer_id,concat(m_customers_t.customer_number,'-',m_customers_t.customer_name)as cus_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_lines_t.qty,s_salesorder_lines_t.pending_qty,s_salesorder_lines_t.delivery_date,s_salesorder_lines_t.product_id,m_products_t.concatenated_product as product_name FROM `s_salesorder_hdr_t` left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id) left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) left join m_products_t on(s_salesorder_lines_t.product_id=m_products_t.product_id) WHERE s_salesorder_lines_t.delivery_date < CURDATE() and s_salesorder_hdr_t.company_id=".$comp." ORDER BY s_salesorder_hdr_t.sales_hdr_id ASC");
$this->data['result']=json_encode($SQL);	
return view('productionreport.mcdtlstable',$this->data);		
}
 public function jcconsolidateindex(){
      
     $pname= \DB::select("SELECT *  FROM `a_lookuplines_t` WHERE `lookup_type`='PROCESS_NAME'  ORDER BY `a_lookuplines_t`.`lookuplines_id` ASC");

   $html=array();
      $i=7;

foreach($pname as $val)
{
       $ii=str_replace(' ','_',$val->lookup_code);
        $html[$i]=array('dataIndx'=> "p_$ii", 'title'=> "$val->lookup_code", 'width'=> '15%');
        //$html[$i]['colModel']=$html;
        $i++;
   
}

      
   
        $this->data['datacolumn'] = json_encode($html);
        
        
   // dd($this->data);
        
      
  
 return view('productionreport.jcconsolidatereporttable',$this->data);		   
}
	
  public function getjcconsolidatedata(Request $request)
	{

		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

       $pname= \DB::select("SELECT lookup_code  FROM `a_lookuplines_t` WHERE `lookup_type`='PROCESS_NAME'  ORDER BY `a_lookuplines_t`.`lookuplines_id` ASC");
	
   if(count($pname)>0){
      foreach($pname as $kk=>$vv){
          $proceess_name=$vv->lookup_code;
           $ii=str_replace(' ','_',$vv->lookup_code);
          
          $select=" ,(select sum(qoh_trx_qty) from i_qoh_detail_t where i_qoh_detail_t.product_id=w_jobcard_hdr_t.product_id and i_qoh_detail_t.job_id=w_jobcard_hdr_t.w_jobs_hdr_id and i_qoh_detail_t.job_process_name='$proceess_name') as p_$ii";
          
      }
  }


    $SQL = "SELECT * from (select * from (select v1.*,
            if(v1.job_adjusted_qty<=v1.moved_qty,'Completed','Incompleted') as status,
            (v1.job_adjusted_qty - v1.moved_qty) as bal_qty,
            (case when date(v1.end_date)=date(v1.start_date) then '1'
            when ISNULL(v1.start_date) then '0'
            else 
            DATEDIFF(v1.end_date,v1.start_date) end ) as duration
            from (select 
    w_jobcard_hdr_t.job_no,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.w_jobs_hdr_id,
    COALESCE((select sum(qoh_trx_qty) from i_qoh_detail_t where i_qoh_detail_t.product_id=w_jobcard_hdr_t.product_id and i_qoh_detail_t.job_id=w_jobcard_hdr_t.w_jobs_hdr_id and i_qoh_detail_t.job_process='FINALPROCESS'),0) as moved_qty,
    (select plan_no from w_productionplan_hdr_t where w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as plan_no,
    (select plan_date from w_productionplan_hdr_t where w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as plan_date,
    (SELECT m_products_t.product_code from m_products_t WHERE m_products_t.product_id=w_jobcard_hdr_t.product_id) as product_code,
    (SELECT m_products_t.concatenated_product from  m_products_t WHERE m_products_t.product_id=w_jobcard_hdr_t.product_id) as product_name,
    w_jobcard_hdr_t.job_date,
    w_jobcard_hdr_t.job_completion_date,
    (w_jobcard_hdr_t.job_adjusted_qty) as job_adjusted_qty,
    (select min(process_start_date) from w_jobcard_process_details_t where w_jobcard_hdr_t.w_jobs_hdr_id=w_jobcard_process_details_t.job_id) as start_date,
    (select max(process_end_date) from w_jobcard_process_details_t where w_jobcard_hdr_t.w_jobs_hdr_id=w_jobcard_process_details_t.job_id) as end_date,
    (select sum(w_productionplan_hdr_t.production_qty) from w_productionplan_hdr_t where w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as plan_qty,
    w_jobcard_hdr_t.job_status
    $select from w_jobcard_hdr_t left join m_products_t on(m_products_t.product_id=w_jobcard_hdr_t.product_id) where 1=1  and m_products_t.product_group_id=1 and w_jobcard_hdr_t.job_date BETWEEN ? and ?)v1)v2) AS v1";

     $results = \DB::select($SQL, [$start_date,$end_date]);

   return DataTables::of($results)->make(true);
}
	
	
  public function jobcardstatusdetailedrpt()
{
$comp=\Session::get('companyid');	
$SQL =\DB::select("SELECT s_salesorder_hdr_t.sales_hdr_id,s_salesorder_hdr_t.sales_order_no,s_salesorder_hdr_t.ship_to_customer_id,concat(m_customers_t.customer_number,'-',m_customers_t.customer_name)as cus_name,s_salesorder_hdr_t.sales_order_date,s_salesorder_lines_t.qty,s_salesorder_lines_t.pending_qty,s_salesorder_lines_t.delivery_date,s_salesorder_lines_t.product_id,m_products_t.concatenated_product as product_name FROM `s_salesorder_hdr_t` left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id) left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) left join m_products_t on(s_salesorder_lines_t.product_id=m_products_t.product_id) WHERE s_salesorder_lines_t.delivery_date < CURDATE() and s_salesorder_hdr_t.company_id=".$comp." ORDER BY s_salesorder_hdr_t.sales_hdr_id ASC");
$this->data['result']=json_encode($SQL);	

return view('productionreport.jobcardstatusdetailedrpt',$this->data);		
}

  public function getjobcardstatusdetaileddata(Request $request)
	{

		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "SELECT * from (
    SELECT v1.*,
 (case
 when jobdate < '2021-09-13' then 'Closed'
 when job_status = 'CLOSED' then 'Cancelled'
 when moved_qty=0 and store_move_qty=0 then 'Open'
 when jobbalqty=0 then 'Completed' 
 else 'Moved' end) as status 
from 
(SELECT 
w_jobcard_hdr_t.w_jobs_hdr_id,
(select plan_no from w_productionplan_hdr_t where w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as plan_no,
(select plan_date from w_productionplan_hdr_t where w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as plan_date,
(SELECT m_products_t.product_code from m_products_t WHERE m_products_t.product_id=w_jobcard_hdr_t.product_id) as product_code,
(SELECT m_products_t.concatenated_product from  m_products_t WHERE m_products_t.product_id=w_jobcard_hdr_t.product_id) as product_name,
w_jobcard_hdr_t.job_date,
(w_jobcard_hdr_t.job_date)as jobdate,
w_jobcard_hdr_t.job_completion_date,
(select w_productionplan_hdr_t.production_qty from w_productionplan_hdr_t where w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as plan_qty,
w_jobcard_hdr_t.job_no,
w_jobcard_hdr_t.batch_no,
w_jobcard_hdr_t.job_qty,
w_jobcard_hdr_t.job_adjusted_qty,
COALESCE((select sum(move_qty) from w_jobcard_process_details_t as pre where pre.job_id=w_jobcard_process_details_t.job_id and pre.process_level=w_jobcard_process_details_t.process_level and  pre.job_process_id<w_jobcard_process_details_t.job_process_id),0) as moved_qty,
COALESCE((w_jobcard_hdr_t.job_qty-COALESCE((select sum(move_qty) from w_jobcard_process_details_t as pre where pre.job_id=w_jobcard_process_details_t.job_id and pre.process_level=w_jobcard_process_details_t.process_level and  pre.job_process_id<w_jobcard_process_details_t.job_process_id),0)
-move_qty),0)as jobbalqty,
COALESCE(w_jobcard_process_details_t.move_qty,0) as store_move_qty,
w_jobcard_process_details_t.process_level,
w_jobcard_process_details_t.process_name,
w_jobcard_process_details_t.process_start_date,
w_jobcard_process_details_t.process_end_date,
TIMEDIFF(w_jobcard_process_details_t.process_end_date,w_jobcard_process_details_t.process_start_date) as duration,
w_jobcard_process_details_t.working_hrs,
w_jobcard_hdr_t.job_status,
(SELECT m_uom_codes_t.uom_code from m_uom_codes_t WHERE m_uom_codes_t.uom_code_id=w_jobcard_hdr_t.uom_code_id) as uom
FROM `w_jobcard_hdr_t` 
left join w_jobcard_process_details_t on w_jobcard_process_details_t.job_id=w_jobcard_hdr_t.w_jobs_hdr_id
left join m_products_t on m_products_t.product_id=w_jobcard_hdr_t.product_id
WHERE 1=1 and  m_products_t.product_group_id=1 and w_jobcard_hdr_t.job_date BETWEEN ? and ? order by w_jobcard_process_details_t.job_process_id)v1) AS vikki";

     $results = \DB::select($SQL, [$start_date,$end_date]);

   return DataTables::of($results)->make(true);
}

}
