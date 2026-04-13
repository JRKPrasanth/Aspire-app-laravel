<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class BomEstimateController extends Controller
{

public function index(Request $request)
{

$products = DB::table('m_products_t')
            ->select('product_id','concatenated_product')
            ->whereIn('product_group_id',[1,4])
            ->orderBy('concatenated_product')
            ->get();

$data = [];
$qty = 0;
$total_cost = 0;
$per_unit_cost = 0;

if($request->isMethod('post')){

$product_id = $request->product_id;
$qty = $request->production_qty;

$data = DB::select("
WITH RECURSIVE bom_tree AS
(
    SELECT
        h.assembly_product_id,
        l.component_product_id,
        l.component_qty,
        1 AS level,
        CAST(CONCAT(h.assembly_product_id, ',', l.component_product_id) AS CHAR(500)) AS path,
        CAST(LPAD(l.component_product_id,6,'0') AS CHAR(500)) AS sort_path
    FROM m_material_bom_hdr_t h
    JOIN m_material_bom_lines_t l
        ON h.material_bom_hdr_id = l.material_bom_hdr_id
    WHERE h.assembly_product_id = ?

    UNION ALL

    SELECT
        bt.component_product_id,
        l.component_product_id,
        bt.component_qty * l.component_qty,
        bt.level + 1,
        CONCAT(bt.path, ',', l.component_product_id),
        CONCAT(bt.sort_path,'.',LPAD(l.component_product_id,6,'0'))
    FROM bom_tree bt
    JOIN m_material_bom_hdr_t h
        ON h.assembly_product_id = bt.component_product_id
    JOIN m_material_bom_lines_t l
        ON l.material_bom_hdr_id = h.material_bom_hdr_id
    WHERE FIND_IN_SET(l.component_product_id, bt.path) = 0
)

SELECT
    bt.level,
    bt.assembly_product_id,
    bt.component_product_id,
    p.concatenated_product,

    (bt.component_qty * ?) AS required_qty,

    ROUND(IFNULL(q.cost,0),2) AS unit_cost,

    ROUND((bt.component_qty * ?) * IFNULL(q.cost,0),2) AS component_cost,

    bt.sort_path

FROM bom_tree bt

JOIN m_products_t p
    ON p.product_id = bt.component_product_id

LEFT JOIN
(
    SELECT iq.product_id, iq.cost
    FROM i_qoh_detail_t iq
    JOIN (
        SELECT product_id, MAX(qoh_detail_id) max_id
        FROM i_qoh_detail_t
        WHERE qoh_source IN ('PURCHASE_STOREMOVE','WIP Store Move')
        GROUP BY product_id
    ) x
    ON x.product_id = iq.product_id
    AND x.max_id = iq.qoh_detail_id
) q
ON q.product_id = bt.component_product_id

ORDER BY bt.sort_path;
",[$product_id,$qty,$qty]);

$parentCost = [];


/* Step 1: sum cost for each parent */
foreach($data as $row){

    if(!isset($parentCost[$row->assembly_product_id])){
        $parentCost[$row->assembly_product_id] = 0;
    }

    $parentCost[$row->assembly_product_id] += $row->component_cost;
}

/* Step 2: attach rolled cost to each row */
foreach($data as $key => $row){

    $data[$key]->parent_cost =
        $parentCost[$row->component_product_id] ?? 0;
    $data[$key]->per_qty_cost =
        ($data[$key]->parent_cost > 0 && $qty > 0)
        ? $data[$key]->parent_cost / $qty
        : 0;   
}

/* total finished product cost */
$total_cost = $parentCost[$product_id] ?? 0;

/* finished product per unit cost */
$per_unit_cost = ($qty > 0) ? $total_cost / $qty : 0;

}

return view('bom.estimate',compact(
'products','data','qty','total_cost','per_unit_cost'
));

}

}