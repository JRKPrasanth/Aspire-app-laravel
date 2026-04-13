<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockmovementController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }

  public function index1()
    {
        $groups = DB::table('m_product_groups_t')
            ->select('product_group_id as id', 'group_name')
            ->orderBy('group_name')
            ->get();
        $subs = DB::table('m_subinventory_t')
          ->select('subinventory_id as id', 'subinventory_name')
          ->orderBy('subinventory_name')
          ->get();  
        //dd($subs);
        return view('batchwiseqtyrpt.stockmovement',compact('groups'),compact('subs'));
    }        
public function getstockmovement(Request $request){

            $wh='';
            
            $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ?  date("Y-m-d", strtotime($_GET['start_date'])) : '';
            $end_date =  isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
            $group_id  = $request->input('group_id');
            $subinventory_id = $request->input('subinventory_id');
            //dd($subinventory_id);
            
            $groupname=\Session::get('groupname');
         if($groupname == "7"){
             $wh.="  and (m_product_groups_t.group_name !='FINISHED GOODS' and m_product_groups_t.group_name !='SEMI FINISHED GOODS' and m_product_groups_t.group_name !='SERVICES' and m_product_groups_t.group_name !='ASSET')";
         }
            
            if(isset($_GET['pq_filter'])){
		$data=json_decode($_GET['pq_filter']);
		$data=$data->data;
		$table=array('m_subinventory_t','m_products_t','m_product_groups_t','m_product_subcategory_t','m_product_category_t','m_sublocators_t','m_product_variants_t');
                $wh.=$this->pqgridsearch('i_qoh_detail_t',$data,$table);
	    }
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx='';
        if (!$sidx)
            $sidx = 1;
  $org=\Session::get('organization');
      $loc=\Session::get('location');
      $compy=\Session::get('companyid');
              $result = \DB::select("SELECT * FROM (
SELECT 
    p.product_classification,
    COALESCE(v.product_variant_name, ' ') as product_variant_name,

    round(SUM(CASE WHEN DATE(d.created_at) <  '$start_date' THEN d.qoh_trx_qty ELSE 0 END),2) AS opening_stock,

    round(SUM(CASE WHEN DATE(d.created_at) BETWEEN '$start_date' AND '$end_date'
                  AND d.qoh_source IN ('PURCHASE_STOREMOVE','OPENSTOCK','material return','SUBINVENTORY TRANSFER RECEIVE')
             THEN d.qoh_trx_qty ELSE 0 END),2) AS purchase_qty,

    round(SUM(CASE WHEN DATE(d.created_at) BETWEEN '$start_date' AND '$end_date'
                  AND d.qoh_source IN ('SALES','DISPATCH','MATERIAL ISSUE','CONSUMABLE','SUBINVENTORY TRANSFER')
             THEN d.qoh_trx_qty ELSE 0 END),2)*-1 AS sales_qty,

    round((
        SUM(CASE WHEN DATE(d.created_at) <  '$start_date' THEN d.qoh_trx_qty ELSE 0 END) +
        SUM(CASE WHEN DATE(d.created_at) BETWEEN '$start_date' AND '$end_date'
                      AND d.qoh_source IN ('PURCHASE_STOREMOVE','OPENSTOCK','material return','SUBINVENTORY TRANSFER RECEIVE')
                 THEN d.qoh_trx_qty ELSE 0 END) -
        SUM(CASE WHEN DATE(d.created_at) BETWEEN '$start_date' AND '$end_date'
                      AND d.qoh_source IN ('SALES','DISPATCH','MATERIAL ISSUE','CONSUMABLE','SUBINVENTORY TRANSFER')
                 THEN d.qoh_trx_qty ELSE 0 END)*-1
    ),2) AS closing_stock,
1 AS sort_order
FROM i_qoh_detail_t d
JOIN m_products_t p ON p.product_id = d.product_id
JOIN m_product_groups_t g ON g.product_group_id = p.product_group_id
LEFT JOIN m_product_variants_t v ON v.product_variant_id = p.product_variant_id
WHERE d.company_id = $compy and g.product_group_id=$group_id and d.subinventory_id=$subinventory_id
GROUP BY p.product_classification,COALESCE(v.product_variant_name, ' ')

UNION ALL

SELECT
    'Grand Total' product_classification,
    '' AS product_variant_name,
    round(SUM(opening_stock),2) AS opening_stock,
    round(SUM(purchase_qty),2) AS purchase_qty,
    round(SUM(sales_qty),2) AS sales_qty,
    round(SUM(opening_stock + purchase_qty - sales_qty),2) AS closing_stock,
    2 AS sort_order
FROM (
    SELECT 
        p.product_id,
    	COALESCE(v.product_variant_name, ' ') AS product_variant_name,
        SUM(CASE WHEN DATE(d.created_at) <  '$start_date' THEN d.qoh_trx_qty ELSE 0 END) AS opening_stock,
        SUM(CASE WHEN DATE(d.created_at) BETWEEN '$start_date' AND '$end_date'
                      AND d.qoh_source IN ('PURCHASE_STOREMOVE','OPENSTOCK','material return','SUBINVENTORY TRANSFER RECEIVE')
                 THEN d.qoh_trx_qty ELSE 0 END) AS purchase_qty,
        SUM(CASE WHEN DATE(d.created_at) BETWEEN '$start_date' AND '$end_date'
                      AND d.qoh_source IN ('SALES','DISPATCH','MATERIAL ISSUE','CONSUMABLE','SUBINVENTORY TRANSFER')
                 THEN d.qoh_trx_qty ELSE 0 END)*-1 AS sales_qty
    FROM i_qoh_detail_t d
    JOIN m_products_t p ON p.product_id = d.product_id
    JOIN m_product_groups_t g ON g.product_group_id = p.product_group_id
    LEFT JOIN m_product_variants_t v ON v.product_variant_id = p.product_variant_id
    WHERE d.company_id = $compy and g.product_group_id=$group_id and d.subinventory_id=$subinventory_id
    GROUP BY p.product_classification,COALESCE(v.product_variant_name, ' ')
) grand
)final
ORDER BY sort_order, product_classification");


		$count = count($result);
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
    
       
               
    if(isset($_GET['download']))
    {
         // $result1 = \DB::select( $download_SQL );
        $result1=collect($result)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }


                $result = array_slice($result,$start,$limit);
		$responce->rows[]='';
		$responce->data=$result;
		$responce->curPage = $page;
		$responce->total = $total_pages;
		$responce->totalRecords = $count;
		echo json_encode($responce);
	}
	
public function getstockbreakup(Request $request)
{
    $classification   = $request->input('classification');
    $metricType       = $request->input('metric_type');
    $start_date       = $request->filled('start_date') ? date("Y-m-d", strtotime($request->input('start_date'))) : null;
    $end_date         = $request->filled('end_date')   ? date("Y-m-d", strtotime($request->input('end_date')))   : null;
    $group_id         = $request->input('group_id');
    $subinventory_id  = $request->input('subinventory_id');
    $compy            = \Session::get('companyid');

    $query = \DB::table('i_qoh_detail_t as d')
        ->join('m_products_t as p', 'p.product_id', '=', 'd.product_id')
        ->join('m_product_groups_t as g', 'g.product_group_id', '=', 'p.product_group_id')
        ->where('d.company_id', $compy)
        ->where('g.product_group_id', $group_id)
        ->where('d.subinventory_id', $subinventory_id);

    // filter if not grand total
    if ($classification !== 'Grand Total') {
        $query->where('p.product_classification', $classification);
    }

    // ðŸ”¹ Apply metric-based filters
    if ($metricType === 'opening') {
        if ($start_date) {
            $query->whereDate('d.created_at', '<', $start_date);
        }
    } elseif ($metricType === 'closing') {
        if ($end_date) {
            $query->whereDate('d.created_at', '<=', $end_date);
        }
    } else {
        if ($start_date && $end_date) {
            $query->whereBetween(\DB::raw('DATE(d.created_at)'), [$start_date, $end_date]);
        }
        if ($metricType === 'purchase') {
            $query->whereIn('d.qoh_source', [
                'PURCHASE_STOREMOVE','OPENSTOCK','MATERIAL RETURN','SUBINVENTORY TRANSFER RECEIVE'
            ]);
        } elseif ($metricType === 'sales') {
            $query->whereIn('d.qoh_source', [
                'SALES','DISPATCH','MATERIAL ISSUE','CONSUMABLE','SUBINVENTORY TRANSFER'
            ]);
        }
    }

    $breakup = $query->select(
        'd.created_at',
        'd.qoh_source',
        'd.qoh_trx_qty',
        'p.concatenated_product',
        'p.product_classification'
    )
    ->orderBy('d.created_at')
    ->get();

    return response()->json($breakup);
}
	
}