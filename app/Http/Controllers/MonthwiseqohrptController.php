<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class MonthwiseqohrptController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    public function monthwiseqohrpt(Request $request)
    {

        $product_group_id = $request->input('product_group_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $this->data['product_group_id'] = $this->jCombologin('m_product_groups_t', 'product_group_id', 'group_name', '');

        $start_month = date('Y-m', strtotime($start_date));
        $end_month = date('Y-m', strtotime($end_date));
		  $condition = "";
        
        if (!empty($product_group_id)) {
            $condition = " AND m_products_t.product_group_id ='$product_group_id'";
        }


        $this->data['prim_summary'] = \DB::select("SELECT
    i.product_id,
    m_products_t.concatenated_product AS product_name,
    i.batch_number AS batch_no,
    i.subinventory_id,
    i.locator_id,
    ROUND(SUM(i.qoh_trx_qty), 0) AS quantity,
    ROUND((
        SELECT cost
        FROM i_qoh_detail_t AS sub
        WHERE sub.product_id = i.product_id
          AND sub.batch_number = i.batch_number
          AND sub.cost IS NOT NULL
          AND sub.cost != 0
        ORDER BY sub.created_at ASC
        LIMIT 1
    ),2) AS cost,
    ROUND(
        SUM(i.qoh_trx_qty) * (
            SELECT cost
            FROM i_qoh_detail_t AS sub
            WHERE sub.product_id = i.product_id
              AND sub.batch_number = i.batch_number
              AND sub.cost IS NOT NULL
              AND sub.cost != 0
            ORDER BY sub.created_at ASC
            LIMIT 1
        ), 2
    ) AS value,

    CONCAT(
        SUBSTRING(MONTHNAME(DATE(i.created_at)), 1, 3),
        '-',
        YEAR(DATE(i.created_at)) % 100
    ) AS log_month,
    MONTH(DATE(i.created_at)) AS log_months,
    YEAR(DATE(i.created_at)) AS log_year

FROM i_qoh_detail_t i
LEFT JOIN m_products_t ON i.product_id = m_products_t.product_id

WHERE
    DATE_FORMAT(DATE(i.created_at), '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition

GROUP BY
    i.product_id,
    i.batch_number,
    i.subinventory_id,
    i.locator_id,
    log_month

ORDER BY
    log_year,
    log_months,
    i.batch_number ASC");
   
   //dd($this->data['prim_summary']);


        return view('monthwiseqohrpt.monthwisestockrpt_old', $this->data);
    }
	
}
