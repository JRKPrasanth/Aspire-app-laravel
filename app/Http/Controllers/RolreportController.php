<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Rolreport;
use DB;
use Illuminate\Http\Request;

class RolreportController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }
  public function index()
    {
      return view('rolreport.rolrpt',$this->data);    
    }
  public function fgindex()
    {
      return view('rolreport.rolfgrpt',$this->data);    
    }
	 public function sfgindex()
    {
      return view('rolreport.rolsfgrpt',$this->data);    
    }
    public function rolrmindex()
    {
      return view('rolreport.rolrmrpt',$this->data);    
    }
    public function rolvrmindex()
    {
      return view('rolreport.rolvrmrpt',$this->data);    
    }
    public function rolpmindex()
    {
      return view('rolreport.rolpmrpt',$this->data);    
    }
    
    public function movementindex(Request $request)
    {

                $start_date =  $request->input('start_date');    
                $end_date=    $request->input('end_date'); 
                $start_date=  date("Y-m-d", strtotime($start_date));
                $end_date=    date("Y-m-d", strtotime($end_date));
                // dd($start_date);
                $this->data['inward'] ='';
		
                if( $start_date !='') {
                
                              
                $supplier =   $request->input('supplier_id'); 
                $id=$request->input('product_id');   
        
        
            $opng=\DB::select("SELECT round(sum(qoh_trx_qty),2)as qty FROM `i_qoh_detail_t` where product_id='$id' and subinventory_id='6'  and (qoh_source='PURCHASE_STOREMOVE' OR qoh_source='CONSUMABLE' OR qoh_source='MATERIAL ISSUE' OR qoh_source='OPENSTOCK') AND date(created_at) < '$end_date'");
            $this->data['opng'] = $opng[0]->qty;
            
            $inward_cnt =\DB::select("SELECT round(sum(i_qoh_detail_t.qoh_trx_qty),2) as qty  FROM `i_qoh_detail_t` join p_grn_hdr_t on p_grn_hdr_t.grn_id=i_qoh_detail_t.grn_id where date(p_grn_hdr_t.created_at) between '$start_date' and '$end_date' and i_qoh_detail_t.product_id='$id' and i_qoh_detail_t.grn_id!=''");
            $this->data['inv_ct'] = $inward_cnt[0]->qty;
    
            $outward_cnt =\DB::select("SELECT round(sum(w_qa_submitstage_line_t.production_qty),2) as production_qty FROM `w_qa_submitstage_line_t` join w_qa_submitstage_trx_t on w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id=w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=w_qa_submitstage_trx_t.job_no where date(w_qa_submitstage_line_t.created_at) between '$start_date' and '$end_date' and w_qa_submitstage_line_t.product_id='$id' and w_qa_submitstage_line_t.production_qty > 0");
            $this->data['out_ct'] = $outward_cnt[0]->production_qty;
            
            $con_cnt =\DB::select("SELECT round(sum(i_qoh_detail_t.qoh_trx_qty),2)*-1 as qty FROM `i_qoh_detail_t` where date(i_qoh_detail_t.created_at) between '$start_date' and '$end_date'and i_qoh_detail_t.product_id='$id' and i_qoh_detail_t.subinventory_id='6' and i_qoh_detail_t.qoh_source='consumable' ");
            $this->data['cons_ct'] = $con_cnt[0]->qty;
            
            $aval_stck =\DB::select("SELECT round(sum(qoh_trx_qty),2)as qty FROM `i_qoh_detail_t` where product_id='$id' and subinventory_id='6' AND date(created_at) < '$end_date'");
            $this->data['aval_stck'] = $aval_stck[0]->qty;
        
        
             $this->data['inward'] =\DB::select("SELECT round(sum(i_qoh_detail_t.qoh_trx_qty),2) as qoh_trx_qty ,date(p_grn_hdr_t.created_at) as date,(select m_supplier_t.supplier_name from m_supplier_t where m_supplier_t.supplier_id=p_grn_hdr_t.supplier_id)as supplier_name FROM `i_qoh_detail_t` join p_grn_hdr_t on p_grn_hdr_t.grn_id=i_qoh_detail_t.grn_id where date(p_grn_hdr_t.created_at) between '$start_date' and '$end_date' and i_qoh_detail_t.product_id='$id' and i_qoh_detail_t.grn_id!='' AND i_qoh_detail_t.qoh_source ='PURCHASE_STOREMOVE' group by p_grn_hdr_t.supplier_id, date");
             $this->data['out_ward']=\DB::select("SELECT round(sum(w_qa_submitstage_line_t.production_qty),2) as production_qty,date(w_qa_submitstage_line_t.created_at)as idate,(select m_products_t.concatenated_product from m_products_t where m_products_t.product_id=w_jobcard_hdr_t.product_id)as product FROM `w_qa_submitstage_line_t` join w_qa_submitstage_trx_t on w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id=w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=w_qa_submitstage_trx_t.job_no where date(w_qa_submitstage_line_t.created_at) between '$start_date' and '$end_date' and w_qa_submitstage_line_t.product_id='$id' and w_qa_submitstage_line_t.production_qty > 0 group by w_jobcard_hdr_t.product_id, idate");
             $this->data['consumb'] =\DB::select("SELECT round(sum(i_qoh_detail_t.qoh_trx_qty),2)*-1 as cqty, date(i_qoh_detail_t.created_at) as cdate, (select m_products_t.concatenated_product from m_products_t where m_products_t.product_id=i_qoh_detail_t.product_id)as product FROM `i_qoh_detail_t` where date(i_qoh_detail_t.created_at) between '$start_date' and '$end_date'and i_qoh_detail_t.product_id='$id' and i_qoh_detail_t.subinventory_id='6' and i_qoh_detail_t.qoh_source='consumable' group by cdate ");
        
                }
                
       return view('rolreport.movement',$this->data);    
    }
    
    
    
    
    
    
    
    public function movementdataindex()
    {
       return view('rolreport.movementdata',$this->data);    
    }
    
public function movementanalysisdata()
{
    //dd($_GET);
    
    $start_date = isset($_GET['startdate']) && !empty($_GET['startdate']) ?   date("Y-m-d", strtotime($_GET['startdate'])) : '';
    $end_date =  isset($_GET['enddate']) && !empty($_GET['enddate']) ? date("Y-m-d", strtotime($_GET['enddate'])) : '';
    $id=$_GET['product'];
    
    $stock=\DB::select("SELECT round(sum(qoh_trx_qty),2)as qty FROM `i_qoh_detail_t` where product_id='$id' and subinventory_id='6'");
    
if($start_date=='2019-04-01'){

    $opng=\DB::select("SELECT round(sum(qoh_trx_qty),2)as qty FROM `i_qoh_detail_t` where product_id='$id' and subinventory_id='6' and created_at like '%2019%' and qoh_source='openstock'");
}else if($start_date<='2024-04-04'){
    $opng=\DB::select("SELECT round(sum(qoh_trx_qty),2)as qty FROM `i_qoh_detail_t` where product_id='$id' and subinventory_id='6'  and qoh_source='openstock'");
}else
       {
        $opng=\DB::select("SELECT round(sum(qoh_trx_qty),2)as qty FROM `i_qoh_detail_t` where product_id='$id' and subinventory_id='6' and created_at < '$start_date'");
       } 


    $inward=\DB::select("SELECT round(sum(i_qoh_detail_t.qoh_trx_qty),2) as qoh_trx_qty ,date(p_grn_hdr_t.created_at) as date,(select m_supplier_t.supplier_name from m_supplier_t where m_supplier_t.supplier_id=p_grn_hdr_t.supplier_id)as supplier_name FROM `i_qoh_detail_t` join p_grn_hdr_t on p_grn_hdr_t.grn_id=i_qoh_detail_t.grn_id where date(p_grn_hdr_t.created_at) between '$start_date' and '$end_date' and i_qoh_detail_t.product_id='$id' and i_qoh_detail_t.grn_id!='' group by p_grn_hdr_t.supplier_id, date");
    $outward=\DB::select("SELECT round(sum(w_qa_submitstage_line_t.production_qty),2) as production_qty,date(w_qa_submitstage_line_t.created_at)as idate,(select m_products_t.concatenated_product from m_products_t where m_products_t.product_id=w_jobcard_hdr_t.product_id)as product FROM `w_qa_submitstage_line_t` join w_qa_submitstage_trx_t on w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id=w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=w_qa_submitstage_trx_t.job_no where date(w_qa_submitstage_line_t.created_at) between '$start_date' and '$end_date' and w_qa_submitstage_line_t.product_id='$id' and w_qa_submitstage_line_t.production_qty > 0 group by w_jobcard_hdr_t.product_id, idate");
    $consumb=\DB::select("SELECT round(sum(i_qoh_detail_t.qoh_trx_qty),2) as cqty, date(i_qoh_detail_t.created_at) as cdate, (select m_products_t.concatenated_product from m_products_t where m_products_t.product_id=i_qoh_detail_t.product_id)as product FROM `i_qoh_detail_t` where date(i_qoh_detail_t.created_at) between '$start_date' and '$end_date'and i_qoh_detail_t.product_id='$id' and i_qoh_detail_t.subinventory_id='6' and i_qoh_detail_t.qoh_source='consumable' group by cdate ");

$html='<table class="table table-stripped"><thead><th>S.No</th><th>Date</th><th>Supplier Name</th><th>Qty.</th></thead><tbody>';

foreach($inward as $k=>$val)
{
    $html.="<tr><td>".($k+1)."</td><td>".$val->date."</td><td>".$val->supplier_name."</td><td>".$val->qoh_trx_qty."</td></tr>";
}

$inward=collect($inward)->map(function($x){ return (array) $x; })->toArray();

    $data['inward_total']= round(array_sum(array_column($inward, 'qoh_trx_qty')),2);
    //dd($data['inward_total']);

$html.='<tr  style="font-weight:bold"><td></td><td>Total</td><td></td><td>'.$data['inward_total'].'</td></td></tbody></html>';

$data['inward']=$html;
$html='<table class="table table-stripped"><thead><th>S.NO</th><th>Date</th><th>Product Name</th><th>Qty.</th></thead><tbody>';

foreach($outward as $k=>$val)
{
    $html.="<tr><td>".($k+1)."</td><td>".$val->idate."</td><td>".$val->product."</td><td>".$val->production_qty."</td></tr>";
}

$outward=collect($outward)->map(function($x){ return (array) $x; })->toArray();

    $data['outward_total']= round(array_sum(array_column($outward, 'production_qty')),2); 


$html.='<tr  style="font-weight:bold"><td></td><td>Total</td><td></td><td>'.$data['outward_total'].'</td></td></tbody></html>';
$data['outward']=$html;

$html='<table class="table table-stripped"><thead><th>S.NO</th><th>Date</th><th>Product Name</th><th>Qty.</th></thead><tbody>';

//consumable
foreach($consumb as $k=>$val)
{
    $html.="<tr><td>".($k+1)."</td><td>".$val->cdate."</td><td>".$val->product."</td><td>".$val->cqty."</td></tr>";
}

$consumb=collect($consumb)->map(function($x){ return (array) $x; })->toArray();

    $data['consumb_total']= round(array_sum(array_column($consumb, 'cqty')),2); 

//dd($data['consumb_total']);

$html.='<tr  style="font-weight:bold"><td></td><td>Total</td><td></td><td>'.$data['consumb_total'].'</td></td></tbody></html>';
$data['consumb']=$html;
    
    //dd($data['consumb']);
 
    $data['stock']=$stock[0]->qty;

    //$consum=$data['inward_total']-$data['outward_total']-$data['stock'];

    //$data['consumable']=$consumb[0]->cqty;
    //dd($data['consumable']);

    $data['open']=$opng[0]->qty;

    return $data;
    
}

public function movementdata1()
{
    //dd($_GET);
    

    $start_date = isset($_GET['startdate']) && !empty($_GET['startdate']) ?   date("Y-m-d", strtotime($_GET['startdate'])) : '';
    $end_date =  isset($_GET['enddate']) && !empty($_GET['enddate']) ? date("Y-m-d", strtotime($_GET['enddate'])) : '';
    $id=$_GET['product'];
    
    //dd($sql);
     $result =\DB::SELECT("
SELECT c.*, round((@cqty:=@cqty+c.opn_stk+c.inward-c.outward),2) as qoh from( 
select v.* from(
SELECT '$start_date' as date,'' as supplier_name,'' as consumed, round(sum(qoh_trx_qty),2) as opn_stk, '' as inward, '' as outward FROM `i_qoh_detail_t` where product_id='$id' and subinventory_id='6' and created_at < '$start_date'

UNION ALL

SELECT date(p_grn_hdr_t.created_at) as date,(select m_supplier_t.supplier_name from m_supplier_t where m_supplier_t.supplier_id=p_grn_hdr_t.supplier_id)as supplier_name, '' as consumed, '' as opn_stk, round(sum(i_qoh_detail_t.qoh_trx_qty),2) as inward, '' as outward FROM `i_qoh_detail_t` join p_grn_hdr_t on p_grn_hdr_t.grn_id=i_qoh_detail_t.grn_id where date(p_grn_hdr_t.created_at) between '$start_date' and '$end_date' and i_qoh_detail_t.product_id='$id' and i_qoh_detail_t.grn_id!='' group by date, p_grn_hdr_t.supplier_id

 UNION ALL   

SELECT date(w_qa_submitstage_line_t.created_at)as idate, '' as supplier_name, (select m_products_t.concatenated_product from m_products_t where m_products_t.product_id=w_jobcard_hdr_t.product_id)as consumed,'' as opn_stk,'' as inward,round(sum(w_qa_submitstage_line_t.production_qty),2) as outward FROM `w_qa_submitstage_line_t` join w_qa_submitstage_trx_t on w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id=w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=w_qa_submitstage_trx_t.job_no where date(w_qa_submitstage_line_t.created_at) between '$start_date' and '$end_date' and w_qa_submitstage_line_t.product_id='$id' and w_qa_submitstage_line_t.production_qty > 0 group by idate, w_jobcard_hdr_t.product_id )v ORDER BY v.date) c , (SELECT @cqty:=0) cum
") ;
    
$html='<table class="table table-stripped"><thead><th>S.No</th><th>Date</th><th>Supplier Name</th><th>Consumed Product</th><th>Opn Stock</th><th>Inward</th><th>Outward</th><th>In Stock</th></thead><tbody>';        

foreach($result as $k=>$val)
{
    $html.="<tr><td>".($k+1)."</td><td>".$val->date."</td><td>".$val->supplier_name."</td><td>".$val->consumed."</td><td>".$val->opn_stk."</td><td>".$val->inward."</td><td>".$val->outward."</td><td>".$val->qoh."</td></tr>";
}
 
 $result=collect($result)->map(function($x){ return (array) $x; })->toArray();

    $data['qoh_total']= round(array_sum(array_column($result, 'qoh')),2);
    $data['opn_total']= round(array_sum(array_column($result, 'opn_stk')),2);
//    dd($data['qoh_total']);

$html.='<tr  style="font-weight:bold"><td></td><td>Total</td><td></td><td></td><td>'.$data['opn_total'].'</td><td></td><td></td><td>'.$data['qoh_total'].'</td></td></tbody></html>';

$data['result']=$html;

 
 return $data;
    
}

public function movementdata(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
    $id = $request->product ?? null;

    $SQL = "SELECT * from (
  SELECT c.*, ROUND((@cqty:=@cqty + c.opn_stk + c.inward - c.outward), 2) as qoh 
        FROM (
            SELECT v.* FROM (
                SELECT ? as date, '' as supplier_name, '' as consumed, 
                       ROUND(SUM(qoh_trx_qty), 2) as opn_stk, '' as inward, '' as outward 
                FROM i_qoh_detail_t 
                WHERE product_id = ? AND subinventory_id = '6' AND created_at < ?

                UNION ALL

                SELECT DATE(p_grn_hdr_t.created_at) as date,
                       (SELECT m_supplier_t.supplier_name 
                        FROM m_supplier_t 
                        WHERE m_supplier_t.supplier_id = p_grn_hdr_t.supplier_id) as supplier_name, 
                       '' as consumed, '' as opn_stk, 
                       ROUND(SUM(i_qoh_detail_t.qoh_trx_qty), 2) as inward, '' as outward 
                FROM i_qoh_detail_t 
                JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = i_qoh_detail_t.grn_id 
                WHERE DATE(p_grn_hdr_t.created_at) BETWEEN ? AND ? 
                  AND i_qoh_detail_t.product_id = ? 
                  AND i_qoh_detail_t.grn_id != '' 
                GROUP BY date, p_grn_hdr_t.supplier_id

                UNION ALL

                SELECT DATE(w_qa_submitstage_line_t.created_at) as date, 
                       '' as supplier_name,
                       (SELECT m_products_t.concatenated_product 
                        FROM m_products_t 
                        WHERE m_products_t.product_id = w_jobcard_hdr_t.product_id) as consumed,
                       '' as opn_stk, '' as inward,
                       ROUND(SUM(w_qa_submitstage_line_t.production_qty), 2) as outward 
                FROM w_qa_submitstage_line_t 
                JOIN w_qa_submitstage_trx_t 
                    ON w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id = w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id 
                JOIN w_jobcard_hdr_t 
                    ON w_jobcard_hdr_t.w_jobs_hdr_id = w_qa_submitstage_trx_t.job_no 
                WHERE DATE(w_qa_submitstage_line_t.created_at) BETWEEN ? AND ? 
                  AND w_qa_submitstage_line_t.product_id = ? 
                  AND w_qa_submitstage_line_t.production_qty > 0 
                GROUP BY date, w_jobcard_hdr_t.product_id
            ) v 
            ORDER BY v.date
        ) c, (SELECT @cqty:=0) cum
    ) AS v1";

    $results = DB::select($SQL, [
        $start_date, $id, $start_date,
        $start_date, $end_date, $id,
        $start_date, $end_date, $id,
    ]);


    return response()->json(['data' => $results]);
}

    
public function getrolreport(Request $request){

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "SELECT * from (SELECT
                m_products_t.concatenated_product,
                m_products_t.min_order_qty,
                m_products_t.min_stock_level2,
                m_products_t.min_stock_level3,
                m_products_t.max_order_qty,
                m_products_t.re_order_level,
                m_product_subcategory_t.subcategory_name,
                m_product_category_t.category_name,
                m_product_groups_t.group_name,
                sum(i_qoh_detail_t.qoh_trx_qty) as qoh
            FROM
                m_products_t
                LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id
                LEFT JOIN m_product_subcategory_t ON m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
                LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id
                  LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id 
                  group by m_products_t.product_id ) as v1";
               
                $result = \DB::select( $SQL );
           
      foreach ($result as $key => $value) {

       if($result[$key]->qoh!=null)	{

        if($result[$key]->qoh < $result[$key]->min_order_qty)
        {
        	$result[$key]->critical_level="level1";
        }
        elseif ($result[$key]->qoh > $result[$key]->min_order_qty && $result[$key]->qoh < $result[$key]->min_stock_level2 ) 
        {
        	$result[$key]->critical_level="level2";
        }
        elseif ($result[$key]->qoh > $result[$key]->min_stock_level2 && $result[$key]->qoh <= $result[$key]->min_stock_level3)
        {
        	$result[$key]->critical_level="level3";
        }
        else{
        	$result[$key]->critical_level="max level";
        }
    } else{
        	$result[$key]->critical_level="";
        }

                 }   


	  return response()->json(['data' => $result]);
    }
	
	
public function getrolfgreport(Request $request){

    $SQL = "SELECT
    *
FROM
    (
    SELECT
        f.product_id,
        f.concatenated_product,
        f.product_subcategory_id,
        f.product_category_id,
        f.min_order_qty,
        COALESCE((f.max_order_qty),
        0) AS max_order_qty,
        m_product_subcategory_t.subcategory_name,
        m_product_category_t.category_name,
        SUM(f.so_qty) AS so_qty,
        SUM(f.free_qty) AS free_qty,
        SUM(Stock) AS stock,
        IF(
            SUM(Stock - (so_qty+free_qty)) > 0,
            0,
            SUM(Stock - (so_qty+free_qty)) *(-1)
        ) AS FG_Req,
        IF(
            SUM(so_qty + free_qty) = 0 && Stock < min_order_qty || Stock < SUM(so_qty + free_qty + min_order_qty),
            SUM(Stock - (so_qty + free_qty)) *(-1) + max_order_qty,
            0
        ) AS FG_Req1,
        SUM(job_qty) AS job_qty,
        SUM(wip + job_qty) AS wip,
        IF(
            SUM(
                Stock + wip - (so_qty + free_qty) - min_order_qty
            ) > 0,
            0,
            SUM(
                Stock + wip - (so_qty + free_qty) - min_order_qty
            )
        ) AS due,
        IF(
            (
                IF(
                    SUM(so_qty + free_qty) = 0 && Stock < min_order_qty || Stock < SUM(so_qty + free_qty + min_order_qty),
                    SUM(Stock - (so_qty+free_qty)) *(-1) + max_order_qty,
                    0
                )
            ) > SUM(wip + job_qty),
            (
                IF(
                    SUM(so_qty + free_qty) = 0 && Stock < min_order_qty || Stock < SUM(so_qty + free_qty + min_order_qty),
                    SUM(Stock - (so_qty+free_qty)) *(-1) + max_order_qty,
                    0
                )
            ) - SUM(wip + job_qty),
            0
        ) AS batchqty
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
    0 AS free_qty,
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
    m_products_t.product_group_id = 1 AND m_products_t.active = 'yes'
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
0 AS free_qty,
m_products_t.product_subcategory_id,
m_products_t.product_category_id,
0 AS min_order_qty,
m_products_t.re_order_level AS max_order_qty
FROM
    m_products_t
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.subinventory_id = 5 AND i_qoh_detail_t.locator_id = 191
WHERE
    m_products_t.product_group_id = 1 AND m_products_t.active = 'yes'
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
    COALESCE(sum(s_salesorder_lines_t.free_qty),0) as free_qty,
    m_products_t.product_subcategory_id,
    m_products_t.product_category_id,
    0 AS min_order_qty,
    m_products_t.re_order_level AS max_order_qty
FROM
    m_products_t
LEFT JOIN s_salesorder_lines_t ON s_salesorder_lines_t.product_id = m_products_t.product_id
LEFT JOIN s_salesorder_hdr_t ON s_salesorder_hdr_t.sales_hdr_id = s_salesorder_lines_t.sales_hdr_id AND (s_salesorder_hdr_t.order_status_id = 'APPROVED' OR s_salesorder_hdr_t.order_status_id = 'INITIATED')
WHERE
    m_products_t.product_group_id = 1 AND m_products_t.active = 'yes' AND s_salesorder_lines_t.pending_qty > 0 AND (s_salesorder_hdr_t.order_status_id = 'APPROVED' OR s_salesorder_hdr_t.order_status_id = 'INITIATED')
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
    0 AS free_qty,
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
    m_products_t.product_group_id = 1 AND m_products_t.active = 'yes'
GROUP BY
    m_products_t.product_id
) f
LEFT JOIN m_product_subcategory_t ON m_product_subcategory_t.product_subcategory_id = f.product_subcategory_id
LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = f.product_category_id
GROUP BY
    f.product_id
) AS g";
    
    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);
    }

	
		public function getrolsfgreport(Request $request){

         

   $SQL = "SELECT * FROM
    (
    SELECT
        f.product_id,
        f.concatenated_product,
        m_product_subcategory_t.subcategory_name,
        m_product_category_t.category_name,
        f.min_order_qty,
        f.re_order_level,
        round(SUM(Stock),2) AS Stock,
        round(sum(Stock-min_order_qty),2) as SFG_Req
        
    FROM
        (
        SELECT
            m_products_t.product_id,
            COALESCE((case when SUM(i_qoh_detail_t.qoh_trx_qty)>0 then SUM(i_qoh_detail_t.qoh_trx_qty) else 0 end),0) AS Stock,
            m_products_t.concatenated_product,
            m_products_t.product_subcategory_id,
            m_products_t.product_category_id,
            m_products_t.min_order_qty,
            m_products_t.re_order_level
        FROM
            m_products_t
        LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.subinventory_id = 5 and i_qoh_detail_t.locator_id = 194
        WHERE
            m_products_t.product_group_id = 4 and m_products_t.product_subcategory_id!= 21 and m_products_t.product_subcategory_id!= 22 and m_products_t.product_subcategory_id!= 23 and m_products_t.product_subcategory_id!= 58
        GROUP BY
            m_products_t.product_id  
    ) f left join m_product_subcategory_t on m_product_subcategory_t.product_subcategory_id=f.product_subcategory_id left join m_product_category_t on m_product_category_t.product_category_id=f.product_category_id
GROUP BY
    f.product_id
) as g";
			
	    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);		
        
    }

public function getrolrmreport(Request $request){

         

        $SQL = "SELECT
    *
FROM
    (
    SELECT
        f.product_id,
        f.concatenated_product,
        m_product_category_t.category_name,
        f.min_order_qty,
        ROUND(SUM(Stock),
        2) AS qoh,
        (
            CASE WHEN SUM(Stock - min_order_qty) > 0 THEN 0 WHEN SUM(Stock - min_order_qty) < 0 THEN ROUND(
                SUM((Stock - min_order_qty) *(-1)),
                2
            ) ELSE 0
        END
) AS re_order_qty,
(
    CASE WHEN SUM(Stock - min_order_qty) > 0 THEN CASE WHEN min_order_qty > f.re_order_level THEN min_order_qty ELSE f.re_order_level
END WHEN SUM(Stock - min_order_qty) < 0 THEN CASE WHEN min_order_qty > f.re_order_level THEN min_order_qty ELSE f.re_order_level
END ELSE f.re_order_level
END
) AS re_order_level,
SUM(POQty) AS POQty,
SUM(QCQty) AS QCQty,        
(
    CASE WHEN SUM(re_order_level - (POQty + QCQty)) > 0 AND ROUND(
        SUM((Stock - min_order_qty) *(-1)),
        2
    ) > 0 THEN CASE WHEN min_order_qty > f.re_order_level THEN SUM(min_order_qty - (POQty + QCQty)) ELSE SUM(f.re_order_level - (POQty + QCQty))
END ELSE 0
END
) AS yet_to_order
FROM
    (
    SELECT
        m_products_t.product_id,
        0 AS Stock,
        m_products_t.concatenated_product,
        m_products_t.product_category_id,
        m_products_t.min_order_qty,
        m_products_t.re_order_level,
        0 AS POQty,
        0 AS QCQty
    FROM
        m_products_t
    LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.subinventory_id = 6 AND i_qoh_detail_t.locator_id !=  210
    WHERE
        m_products_t.product_group_id = 2
    GROUP BY
        m_products_t.product_id
    UNION ALL
SELECT
    m_products_t.product_id,
    COALESCE(
        (
            CASE WHEN SUM(i_qoh_detail_t.qoh_trx_qty) > 0 THEN ROUND(
                SUM(i_qoh_detail_t.qoh_trx_qty),
                2
            ) ELSE 0
        END
    ),
    0
) AS Stock,
m_products_t.concatenated_product,
m_products_t.product_category_id,
0 AS min_order_qty,
0 AS re_order_level,
0 AS POQty,
0 AS QCQty        
FROM
    m_products_t
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.subinventory_id = 6 AND i_qoh_detail_t.locator_id !=  210
WHERE
    m_products_t.product_group_id = 2
GROUP BY
    m_products_t.product_id,
    i_qoh_detail_t.batch_number
UNION ALL
SELECT
    m_products_t.product_id,
    0 AS Stock,
    m_products_t.product_category_id,
    m_products_t.concatenated_product,
    0 AS min_order_qty,
    0 AS re_order_level,
    IF(
        p_po_lines_t.pending_qty < 0,
        0,
        ROUND(
            SUM(p_po_lines_t.pending_qty),
            2
        )
    ) AS POQty,
    0 AS QCQty
FROM
    m_products_t
LEFT JOIN p_po_lines_t ON p_po_lines_t.product_id = m_products_t.product_id
LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_po_lines_t.po_hdr_id        
WHERE
    m_products_t.product_group_id = 2 AND p_po_hdr_t.po_status != 'CLOSED' AND p_po_hdr_t.po_status != 'COMPLETED' AND p_po_hdr_t.po_status != 'CANCELLED' AND p_po_hdr_t.po_status != 'REJECTED' AND p_po_hdr_t.po_date >= '2024-04-01'
GROUP BY
    m_products_t.product_id
UNION ALL
        SELECT
    m_products_t.product_id,
    0 AS Stock,
    m_products_t.product_category_id,
    m_products_t.concatenated_product,
    0 AS min_order_qty,
    0 AS re_order_level,
    0 AS POQty,
    IF(
        p_qc_lines_t.inventory_status = 0 AND p_qc_lines_t.po_hdr_id > 0,
        ROUND(
            SUM(p_qc_lines_t.total_box_qty),
            2
        ),
        0
    ) AS QCQty
FROM
    m_products_t
LEFT JOIN p_qc_lines_t ON p_qc_lines_t.product_id = m_products_t.product_id
WHERE
    m_products_t.product_group_id = 2 
GROUP BY
    m_products_t.product_id
    
    
    ) f
LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = f.product_category_id
GROUP BY
    f.product_id
) AS a";

   
      $results = \DB::select($SQL);

    return response()->json(['data' => $results]);
	
    }
	
    
	public function getrolvrmreport(Request $request){

        $SQL = "SELECT
    *
FROM
    (
    SELECT
        f.product_id,
        f.concatenated_product,
        m_product_category_t.category_name,
        f.min_order_qty,
        ROUND(SUM(Stock),
        2) AS qoh,
        (
            CASE WHEN SUM(Stock - min_order_qty) > 0 THEN 0 WHEN SUM(Stock - min_order_qty) < 0 THEN ROUND(
                SUM((Stock - min_order_qty) *(-1)),
                2
            ) ELSE 0
        END
) AS re_order_qty,
(
    CASE WHEN SUM(Stock - min_order_qty) > 0 THEN CASE WHEN min_order_qty > f.re_order_level THEN min_order_qty ELSE f.re_order_level
END WHEN SUM(Stock - min_order_qty) < 0 THEN CASE WHEN min_order_qty > f.re_order_level THEN min_order_qty ELSE f.re_order_level
END ELSE f.re_order_level
END
) AS re_order_level,
SUM(POQty) AS POQty,
(
    CASE WHEN SUM(re_order_level - POQty) > 0 AND ROUND(
        SUM((Stock - min_order_qty) *(-1)),
        2
    ) > 0 THEN CASE WHEN min_order_qty > f.re_order_level THEN SUM(min_order_qty - POQty) ELSE SUM(f.re_order_level - POQty)
END ELSE 0
END
) AS yet_to_order
FROM
    (
    SELECT
        m_products_t.product_id,
        0 AS Stock,
        m_products_t.concatenated_product,
        m_products_t.product_category_id,
        m_products_t.min_order_qty,
        m_products_t.re_order_level,
        0 AS POQty
    FROM
        m_products_t
    LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.subinventory_id = 6 AND i_qoh_detail_t.locator_id =  210
    WHERE
        m_products_t.product_group_id = 2 AND i_qoh_detail_t.locator_id =  210
    GROUP BY
        m_products_t.product_id
    UNION ALL
SELECT
    m_products_t.product_id,
    COALESCE(
        (
            CASE WHEN SUM(i_qoh_detail_t.qoh_trx_qty) > 0 THEN ROUND(
                SUM(i_qoh_detail_t.qoh_trx_qty),
                2
            ) ELSE 0
        END
    ),
    0
) AS Stock,
m_products_t.concatenated_product,
m_products_t.product_category_id,
0 AS min_order_qty,
0 AS re_order_level,
0 AS POQty
FROM
    m_products_t
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.subinventory_id = 6 AND i_qoh_detail_t.locator_id =  210
WHERE
    m_products_t.product_group_id = 2 AND i_qoh_detail_t.locator_id =  210
GROUP BY
    m_products_t.product_id,
    i_qoh_detail_t.batch_number
UNION ALL
SELECT
    m_products_t.product_id,
    0 AS Stock,
    m_products_t.product_category_id,
    m_products_t.concatenated_product,
    0 AS min_order_qty,
    0 AS re_order_level,
    IF(
        p_po_lines_t.pending_qty < 0,
        0,
        ROUND(
            SUM(p_po_lines_t.pending_qty),
            2
        )
    ) AS POQty
FROM
    m_products_t
LEFT JOIN p_po_lines_t ON p_po_lines_t.product_id = m_products_t.product_id 
LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_po_lines_t.po_hdr_id
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.subinventory_id = 6 AND i_qoh_detail_t.locator_id = 210
WHERE
    m_products_t.product_group_id = 2 AND i_qoh_detail_t.locator_id = 210 AND p_po_hdr_t.po_status != 'CLOSED' AND p_po_hdr_t.po_status != 'COMPLETED' AND p_po_hdr_t.po_status != 'CANCELLED' AND p_po_hdr_t.po_status != 'REJECTED'
GROUP BY
    m_products_t.product_id
) f
LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = f.product_category_id
GROUP BY
    f.product_id
) AS a";

     $results = \DB::select($SQL);

    return response()->json(['data' => $results]);
		
    }    
    

    public function getrolpmreport(Request $request){

        $SQL = "SELECT
    *
FROM
    (
    SELECT
        f.product_id,
        f.concatenated_product,
        m_product_category_t.category_name,
        f.min_order_qty,
        ROUND(SUM(Stock),2) AS qoh,
       ( CASE WHEN SUM(Stock - min_order_qty) > 0 THEN 0 WHEN SUM(Stock - min_order_qty) < 0 THEN ROUND(SUM((Stock - min_order_qty)* (-1)),2) ELSE 0 END) as re_order_qty,
        
       ( CASE WHEN SUM(Stock - min_order_qty) > 0 THEN CASE WHEN min_order_qty > f.re_order_level THEN min_order_qty else f.re_order_level END WHEN SUM(Stock - min_order_qty) < 0 THEN CASE WHEN min_order_qty > f.re_order_level THEN min_order_qty else f.re_order_level END else 
        f.re_order_level END ) as re_order_level,
        sum(POQty) as POQty,
        sum(QCQty) as QCQty,
         (CASE WHEN SUM(re_order_level - (POQty + QCQty)) > 0 AND ROUND(SUM((Stock - min_order_qty)* (-1)),2) > 0 THEN 
          
          CASE WHEN min_order_qty > f.re_order_level THEN SUM(min_order_qty - (POQty + QCQty)) ELSE SUM(f.re_order_level - (POQty + QCQty)) END ELSE 0 END) as yet_to_order
        
    FROM
        (
            
            
SELECT
    m_products_t.product_id,
    0 AS Stock,
    m_products_t.concatenated_product,

m_products_t.product_category_id,
m_products_t.min_order_qty,
m_products_t.re_order_level,
           0 as POQty,
            0 AS QCQty
FROM
    m_products_t
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND i_qoh_detail_t.subinventory_id = 6
WHERE
    m_products_t.product_group_id = 3
GROUP BY

    m_products_t.product_id
            
            UNION ALL
            
        SELECT
            m_products_t.product_id,
            COALESCE(
                (
                    CASE WHEN (SUM(i_qoh_detail_t.qoh_trx_qty) > 0 AND i_qoh_detail_t.subinventory_id = '6' ) THEN ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),2) ELSE 0
                END
            ),
            0
    ) AS Stock,
    m_products_t.concatenated_product,
    m_products_t.product_category_id,
    0 as min_order_qty,
    0 as re_order_level,
           0 as POQty,
           0  AS QCQty
FROM
    m_products_t
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = m_products_t.product_id AND
        i_qoh_detail_t.subinventory_id = 6
WHERE
    m_products_t.product_group_id = 3 AND i_qoh_detail_t.locator_id != '204' AND i_qoh_detail_t.locator_id != '0'
GROUP BY
    m_products_t.product_id,
            i_qoh_detail_t.batch_number
            
            
            
            
UNION ALL
SELECT

    m_products_t.product_id,
    0 AS Stock,
            m_products_t.product_category_id,
    m_products_t.concatenated_product,
    0 AS min_order_qty,
    0 as re_order_level,
            IF(
            p_po_lines_t.pending_qty < 0,
            0,
            ROUND(SUM(p_po_lines_t.pending_qty),2)) AS POQty,
            0 AS QCQty
FROM
    m_products_t
LEFT JOIN p_po_lines_t ON p_po_lines_t.product_id = m_products_t.product_id
LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_po_lines_t.po_hdr_id 
WHERE
    m_products_t.product_group_id = 3 AND p_po_hdr_t.po_status != 'CLOSED' AND p_po_hdr_t.po_status != 'COMPLETED' AND p_po_hdr_t.po_status != 'CANCELLED' AND p_po_hdr_t.po_status != 'REJECTED' AND p_po_hdr_t.po_date >= '2024-04-01'
GROUP BY
    m_products_t.product_id

UNION ALL
SELECT

    m_products_t.product_id,
    0 AS Stock,
            m_products_t.product_category_id,
    m_products_t.concatenated_product,
    0 AS min_order_qty,
    0 as re_order_level,
    0 AS POQty,
    IF(
        p_qc_lines_t.inventory_status = 0 AND p_qc_lines_t.po_hdr_id > 0 AND p_qc_lines_t.approval_status='approved',
        ROUND(
            SUM(p_qc_lines_t.total_box_qty),
            2
        ),
        0
    ) AS QCQty
FROM
    m_products_t
LEFT JOIN p_qc_lines_t ON p_qc_lines_t.product_id = m_products_t.product_id
WHERE
    m_products_t.product_group_id = 3 
GROUP BY
    m_products_t.product_id            
            
) f

LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = f.product_category_id
GROUP BY
    f.product_id
) AS a";

    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);
    
    }
	
}
