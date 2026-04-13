<?php

namespace App\Http\Controllers;

use App\stockledgerreport;
use Illuminate\Http\Request;
use yajra\datatables\datatables;

class StockledgerreportController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    public function index()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id = 1');

        return view('stockledgerrpt.index', $this->data);
    }

    public function rawmaterialrptindex()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(4)');
        return view('stockledgerrpt.rmreport', $this->data);
    }
    public function productionrawmaterialrptindex()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(4)');
        return view('stockledgerrpt.productionrmreport', $this->data);
    }
    public function rawfinishedgoodsrptindex()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(4)');
        return view('stockledgerrpt.rfgrpt', $this->data);
    }
    public function extractrawfinishedgoodsrptindex()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(4)');
        return view('stockledgerrpt.extractrfgrpt', $this->data);
    }

    public function pmstockledgerreportindex()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(4)');
        return view('stockledgerrpt.pmstockledgerreport', $this->data);
    }
    public function optpmstockledgerreportindex()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(4)');
        return view('stockledgerrpt.optpmstockledgerreport', $this->data);
    }
    public function wipstockledgerreportindex()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(4)');
        return view('stockledgerrpt.wipreport', $this->data);
    }

    public function index2()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(1)');
        return view('stockledgerrpt.fgstockrpt', $this->data);
    }

    public function index3()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(2)');
        return view('stockledgerrpt.rmstockrpt', $this->data);
    }

    public function index4()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', ' and product_group_id in(1)');
        return view('stockledgerrpt.promotionalstockrpt', $this->data);
    }

    public function stagewiserpt()
    {
        return view('salesstockledgerrpt.stagewiserpt', $this->data);
    }

    public function getsalesstockledgerrpt()
    {

        $wh = '';

        if (isset($_GET['pq_filter'])) {
            $data = json_decode($_GET['pq_filter']);
            $data = $data->data;
            $wh .= $this->pqgridsearchsum('v1', $data);
        }
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];

        $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
        $product_id = isset($_GET['product_id']) && !empty($_GET['product_id']) ? $_GET['product_id'] : '';

        $sidx = '';
        if (!$sidx)
            $sidx = 1;

        $SQL = "select * from ( 
   select sum(i_qoh_detail_t.qoh_trx_qty) as opening_bal,m_products_t.product_code,m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,i_qoh_detail_t.batch_number,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date, i_qoh_detail_t.product_id,0  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal from i_qoh_detail_t  left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PRODUCTION STORE MOVE' or i_qoh_detail_t.qoh_source ='SFG STORE MOVE') and ( m_product_category_t.category_name = 'FINISHED' or m_product_category_t.category_name = 'STANDARD' ) and m_product_groups_t.product_group_id =1  and Date(i_qoh_detail_t.created_at) < '$start_date' and m_products_t.product_id = '$product_id' GROUP by m_products_t.product_id
    UNION all
               select 
                0 as opening_bal,m_products_t.product_code, m_products_t.concatenated_product, Date(i_qoh_detail_t.created_at) as date, i_qoh_detail_t.batch_number,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date, i_qoh_detail_t.product_id,sum(i_qoh_detail_t.qoh_trx_qty)  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal from i_qoh_detail_t left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PRODUCTION STORE MOVE' or i_qoh_detail_t.qoh_source ='SFG STORE MOVE') and i_qoh_detail_t.created_at BETWEEN '$start_date' AND '$end_date' and m_product_groups_t.product_group_id =1  and m_products_t.product_id = '$product_id' and ( m_product_category_t.category_name = 'FINISHED' or m_product_category_t.category_name = 'STANDARD' ) and m_product_groups_t.product_group_id =1  GROUP by i_qoh_detail_t.qoh_detail_id  
                UNION all 
                select 0 as opening_bal, m_products_t.product_code, m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,i_qoh_detail_t.batch_number,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date,i_qoh_detail_t.product_id,0 as stock_in ,ABS(sum(i_qoh_detail_t.qoh_trx_qty)) as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal from i_qoh_detail_t left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE i_qoh_detail_t.qoh_source='SHIPCONFIRM' and i_qoh_detail_t.created_at BETWEEN '$start_date' AND '$end_date' and ( m_product_category_t.category_name = 'FINISHED' or m_product_category_t.category_name = 'STANDARD' ) and m_product_groups_t.product_group_id =1  and m_products_t.product_id = '$product_id' and m_product_groups_t.product_group_id =1  GROUP by i_qoh_detail_t.qoh_detail_id
     UNION all 
     select 0 as opening_bal,m_products_t.product_code,m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,i_qoh_detail_t.batch_number,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date, i_qoh_detail_t.product_id,0  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,sum(i_qoh_detail_t.qoh_trx_qty) as closing_bal from i_qoh_detail_t  left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='SHIPCONFIRM'  or i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PRODUCTION STORE MOVE' or i_qoh_detail_t.qoh_source ='SFG STORE MOVE' ) and ( m_product_category_t.category_name = 'FINISHED' or m_product_category_t.category_name = 'STANDARD' ) and m_product_groups_t.product_group_id =1  and Date(i_qoh_detail_t.created_at) < '$end_date' and m_products_t.product_id = '$product_id'
    ) v1 where 1=1  $wh";


        $result = \DB::select($SQL);

        $count = count($result);
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;

        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $SQL = "select * from ( 
   select sum(i_qoh_detail_t.qoh_trx_qty) as opening_bal,m_products_t.product_code,m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,i_qoh_detail_t.batch_number, i_qoh_detail_t.product_id,0  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal from i_qoh_detail_t  left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PRODUCTION STORE MOVE' or i_qoh_detail_t.qoh_source ='SFG STORE MOVE') and ( m_product_category_t.category_name = 'FINISHED' or m_product_category_t.category_name = 'STANDARD' ) and m_product_groups_t.product_group_id =1  and Date(i_qoh_detail_t.created_at) < '$start_date' and m_products_t.product_id = '$product_id' GROUP by m_products_t.product_id
    UNION all
               select 
                0 as opening_bal,m_products_t.product_code, m_products_t.concatenated_product, Date(i_qoh_detail_t.created_at) as date, i_qoh_detail_t.batch_number, i_qoh_detail_t.product_id,sum(i_qoh_detail_t.qoh_trx_qty)  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal from i_qoh_detail_t left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PRODUCTION STORE MOVE' or i_qoh_detail_t.qoh_source ='SFG STORE MOVE') and i_qoh_detail_t.created_at BETWEEN '$start_date' AND '$end_date' and m_product_groups_t.product_group_id =1  and m_products_t.product_id = '$product_id' and ( m_product_category_t.category_name = 'FINISHED' or m_product_category_t.category_name = 'STANDARD' ) and m_product_groups_t.product_group_id =1  GROUP by i_qoh_detail_t.qoh_detail_id  
                UNION all 
                select 0 as opening_bal, m_products_t.product_code, m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,i_qoh_detail_t.batch_number,i_qoh_detail_t.product_id,0 as stock_in ,ABS(sum(i_qoh_detail_t.qoh_trx_qty)) as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal from i_qoh_detail_t left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE i_qoh_detail_t.qoh_source='SHIPCONFIRM' and i_qoh_detail_t.created_at BETWEEN '$start_date' AND '$end_date' and ( m_product_category_t.category_name = 'FINISHED' or m_product_category_t.category_name = 'STANDARD' ) and m_product_groups_t.product_group_id =1  and m_products_t.product_id = '$product_id' and m_product_groups_t.product_group_id =1  GROUP by i_qoh_detail_t.qoh_detail_id
     UNION all 
     select 0 as opening_bal,m_products_t.product_code,m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,i_qoh_detail_t.batch_number, i_qoh_detail_t.product_id,0  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,sum(i_qoh_detail_t.qoh_trx_qty) as closing_bal from i_qoh_detail_t  left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='SHIPCONFIRM'  or i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PRODUCTION STORE MOVE' or i_qoh_detail_t.qoh_source ='SFG STORE MOVE' ) and ( m_product_category_t.category_name = 'FINISHED' or m_product_category_t.category_name = 'STANDARD' ) and m_product_groups_t.product_group_id =1  and Date(i_qoh_detail_t.created_at) < '$end_date' and m_products_t.product_id = '$product_id' 
    ) v1 where 1=1 ORDER BY v1.closing_bal asc LIMIT $start , $limit";

        $result = \DB::select($SQL);
        //               dd($SQL); 
        foreach ($result as $k => $v) {
            $v->invoice_no = '';
            $dt = \DB::table('s_dispatch_hdr_t')->where('so_dispatch_hdr_id', $v->create_trx_id)->get();
            if (count($dt) > 0) {
                if ($dt[0]->dispatch_source != "INVOICE") {
                    $dis_id = $dt[0]->so_dispatch_hdr_id;
                } else {
                    $dis_id = $dt[0]->reference_source_id;
                }
                $data = \DB::table('s_invoice_hdr_t')->where('s_invoice_hdr_t.reference_source_id', $dis_id)->get();
                if (count($data) > 0) {
                    $v->invoice_no = $data[0]->invoice_number;
                }
            }
            if ($v->stock_in == 0) {
                $v->batch_number = '';
            }
        }

        $responce->rows[] = '';
        $responce->data = $result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
    }

    public function getrawmaterialstockledgerrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (
SELECT
    f.product_id,
    m_products_t.concatenated_product,
    (
    SELECT
        category_name
    FROM
        m_product_category_t
    WHERE
        m_product_category_t.product_category_id = m_products_t.product_category_id
) AS cate_name,
(
    SELECT
        subcategory_name
    FROM
        m_product_subcategory_t
    WHERE
        m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
) AS sub_cate,
batch_number,
manufacturer_date,
product_expire_date,
round(SUM(opening_stock + opening_balance),2) AS opening_balance,
round(SUM(qoh_in),2) AS qoh_in,
round(SUM(qoh_out+scrap_qty),2) AS qoh_out,
round(SUM(
    opening_stock + opening_balance + qoh_in - qoh_out
),2) AS closing
FROM
    (
        (
        SELECT
            `product_id`,
            batch_number,
            manufacturer_date,
            product_expire_date,
            SUM(qoh_trx_qty) AS opening_stock,
            0 AS opening_balance,
            0 AS qoh_in,
            0 AS qoh_out,
            0 as scrap_qty
        FROM
            i_qoh_detail_t
        WHERE
            qoh_source = 'OPENSTOCK' and subinventory_id=6
        GROUP BY
            product_id,
            batch_number
    )
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        SUM(qoh_trx_qty) AS opening_balance,
        0 AS qoh_in,
        0 AS qoh_out,
        0 as scrap_qty
    FROM
        i_qoh_detail_t
    WHERE
        DATE(created_at) < ? and qoh_source != 'MATERIAL RECEIVE' and qoh_source != 'Return Store Move' and subinventory_id=6 AND qoh_source != 'OPENSTOCK'
    GROUP BY
        product_id,
        batch_number
)
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        0 AS opening_balance,
        (
            CASE WHEN qoh_trx_qty > 0 THEN qoh_trx_qty ELSE 0
        END
) AS qoh_in,
(
    CASE WHEN qoh_trx_qty < 0 THEN ABS(qoh_trx_qty) ELSE 0
END
    ) AS qoh_out,0 as scrap_qty
FROM
    i_qoh_detail_t
WHERE
    DATE(created_at) >= ? AND DATE(created_at) <= ? and qoh_source != 'MATERIAL RECEIVE' and qoh_source != 'Return Store Move' and subinventory_id=6 AND qoh_source != 'OPENSTOCK'
GROUP BY
    qoh_detail_id
)
) f
JOIN m_products_t ON m_products_t.product_id = f.product_id AND m_products_t.product_group_id = 2
GROUP BY
    f.product_id,
    f.batch_number) AS v1";

        $results = \DB::select($SQL, [$start_date, $start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }

    public function getproductionrawmaterialstockledgerrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (SELECT
    f.product_id,
    m_products_t.concatenated_product,
    (
    SELECT
        category_name
    FROM
        m_product_category_t
    WHERE
        m_product_category_t.product_category_id = m_products_t.product_category_id
) AS cate_name,
(
    SELECT
        subcategory_name
    FROM
        m_product_subcategory_t
    WHERE
        m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
) AS sub_cate,
batch_number,
manufacturer_date,
product_expire_date,
ROUND(
    SUM(
        opening_stock + opening_balance
    ),
    2
) AS opening_balance,
ROUND(SUM(qoh_in),
2) AS qoh_in,
ROUND(SUM(qoh_out + scrap_qty),
2) AS qoh_out,
ROUND(
    SUM(
        opening_stock + opening_balance + qoh_in - qoh_out
    ),
    2
) AS closing
FROM
    (
        (
        SELECT
            `product_id`,
            batch_number,
            manufacturer_date,
            product_expire_date,
            SUM(qoh_trx_qty) AS opening_stock,
            0 AS opening_balance,
            0 AS qoh_in,
            0 AS qoh_out,
            0 AS scrap_qty
        FROM
            i_qoh_detail_t
        WHERE
            qoh_source = 'OPENSTOCK'
        GROUP BY
            product_id,
            batch_number
    )
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        SUM(qoh_trx_qty) AS opening_balance,
        0 AS qoh_in,
        0 AS qoh_out,
        0 AS scrap_qty
    FROM
        i_qoh_detail_t
    WHERE
        DATE(created_at) < ? AND qoh_source != 'MATERIAL RECEIVE' AND qoh_source != 'Return Store Move' AND subinventory_id = 6 AND qoh_source != 'OPENSTOCK'
    GROUP BY
        product_id,
        batch_number
)
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        0 AS opening_balance,
        (
            CASE WHEN qoh_trx_qty > 0 THEN qoh_trx_qty ELSE 0
        END
) AS qoh_in,
(
    CASE WHEN qoh_trx_qty < 0 THEN ABS(qoh_trx_qty) ELSE 0
END
    ) AS qoh_out,
    0 AS scrap_qty
FROM
    i_qoh_detail_t
WHERE
    DATE(created_at) >= ? AND DATE(created_at) <= ? AND qoh_source != 'MATERIAL RECEIVE' AND qoh_source != 'Return Store Move' AND subinventory_id = 6 AND qoh_source != 'OPENSTOCK'
GROUP BY
    qoh_detail_id
)
) f
JOIN m_products_t ON m_products_t.product_id = f.product_id AND m_products_t.product_group_id = 2
GROUP BY
    f.product_id,
    f.batch_number) AS v1";

        $results = \DB::select($SQL, [$start_date, $start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }

    public function getpmstockledgerrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (SELECT
    f.product_id,
    m_products_t.concatenated_product,
    (
    SELECT
        category_name
    FROM
        m_product_category_t
    WHERE
        m_product_category_t.product_category_id = m_products_t.product_category_id
) AS cate_name,
(
    SELECT
        subcategory_name
    FROM
        m_product_subcategory_t
    WHERE
        m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
) AS sub_cate,
batch_number,
manufacturer_date,
product_expire_date,
round(SUM(opening_stock + opening_balance),2) AS opening_balance,
round(SUM(qoh_in),2) AS qoh_in,
round(SUM(qoh_out+scrap_qty),2) AS qoh_out,
round(SUM(
    opening_stock + opening_balance + qoh_in - qoh_out
),2) AS closing
FROM
    (
        (
        SELECT
            `product_id`,
            batch_number,
            manufacturer_date,
            product_expire_date,
            SUM(qoh_trx_qty) AS opening_stock,
            0 AS opening_balance,
            0 AS qoh_in,
            0 AS qoh_out,
			0 as scrap_qty
        FROM
            i_qoh_detail_t
        WHERE
            qoh_source = 'OPENSTOCK'
        GROUP BY
            product_id,
            batch_number
    )
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        SUM(qoh_trx_qty) AS opening_balance,
        0 AS qoh_in,
        0 AS qoh_out,
		0 as scrap_qty
    FROM
        i_qoh_detail_t
    WHERE
        DATE(created_at) < ?  and qoh_source != 'MATERIAL RECEIVE' and qoh_source != 'Return Store Move' and subinventory_id=6 AND qoh_source != 'OPENSTOCK'
    GROUP BY
        product_id,
        batch_number
)
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        0 AS opening_balance,
        (
            CASE WHEN qoh_trx_qty > 0 THEN qoh_trx_qty ELSE 0
        END
) AS qoh_in,
(
    CASE WHEN qoh_trx_qty < 0 THEN ABS(qoh_trx_qty) ELSE 0
END
    ) AS qoh_out,0 as scrap_qty
FROM
    i_qoh_detail_t
WHERE
    DATE(created_at) >= ? AND DATE(created_at) <= ? and qoh_source != 'MATERIAL RECEIVE' and qoh_source != 'Return Store Move' and subinventory_id=6 AND qoh_source != 'OPENSTOCK'
GROUP BY
    qoh_detail_id
)
) f
JOIN m_products_t ON m_products_t.product_id = f.product_id AND m_products_t.product_group_id = 3
GROUP BY
    f.product_id,
    f.batch_number) AS v1";

        $results = \DB::select($SQL, [$start_date, $start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }

    public function getoptpmstockledgerrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (SELECT
    f.product_id,
    m_products_t.concatenated_product,
    (
    SELECT
        category_name
    FROM
        m_product_category_t
    WHERE
        m_product_category_t.product_category_id = m_products_t.product_category_id
) AS cate_name,
(
    SELECT
        subcategory_name
    FROM
        m_product_subcategory_t
    WHERE
        m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
) AS sub_cate,
batch_number,
manufacturer_date,
product_expire_date,
ROUND(
    SUM(
        opening_stock + opening_balance
    ),
    2
) AS opening_balance,
ROUND(SUM(qoh_in),
2) AS qoh_in,
ROUND(SUM(qoh_out + scrap_qty),
2) AS qoh_out,
ROUND(
    SUM(
        opening_stock + opening_balance + qoh_in - qoh_out
    ),
    2
) AS closing
FROM
    (
        (
        SELECT
            `product_id`,
            batch_number,
            manufacturer_date,
            product_expire_date,
            SUM(qoh_trx_qty) AS opening_stock,
            0 AS opening_balance,
            0 AS qoh_in,
            0 AS qoh_out,
            0 AS scrap_qty
        FROM
            i_qoh_detail_t
        WHERE
            qoh_source = 'OPENSTOCK'
        GROUP BY
            product_id,
            batch_number
    )
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        SUM(qoh_trx_qty) AS opening_balance,
        0 AS qoh_in,
        0 AS qoh_out,
        0 AS scrap_qty
    FROM
        i_qoh_detail_t
    WHERE
        DATE(created_at) < ? AND qoh_source != 'MATERIAL RECEIVE' AND qoh_source != 'Return Store Move' AND subinventory_id = 6 AND qoh_source != 'OPENSTOCK'
    GROUP BY
        product_id,
        batch_number
)
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        0 AS opening_balance,
        (
            CASE WHEN qoh_trx_qty > 0 THEN qoh_trx_qty ELSE 0
        END
) AS qoh_in,
(
    CASE WHEN qoh_trx_qty < 0 THEN ABS(qoh_trx_qty) ELSE 0
END
    ) AS qoh_out,
    0 AS scrap_qty
FROM
    i_qoh_detail_t
WHERE
    DATE(created_at) >= ? AND DATE(created_at) <= ? AND qoh_source != 'MATERIAL RECEIVE' AND qoh_source != 'Return Store Move' AND subinventory_id = 6 AND qoh_source != 'OPENSTOCK'
GROUP BY
    qoh_detail_id
)
) f
JOIN m_products_t ON m_products_t.product_id = f.product_id AND m_products_t.product_group_id = 3
GROUP BY
    f.product_id,
    f.batch_number) AS v1";

        $results = \DB::select($SQL, [$start_date, $start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }

    public function getsemifinishedgoodsrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (
SELECT
    f.product_id,
    m_products_t.concatenated_product,
    (
    SELECT
        category_name
    FROM
        m_product_category_t
    WHERE
        m_product_category_t.product_category_id = m_products_t.product_category_id
) AS cate_name,
(
    SELECT
        subcategory_name
    FROM
        m_product_subcategory_t
    WHERE
        m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
) AS sub_cate,
batch_number,
manufacturer_date,
product_expire_date,
round(SUM(opening_stock + opening_balance),2) AS opening_balance,
round(SUM(qoh_in),2) AS qoh_in,
round(SUM(qoh_out+scrap_qty),2) AS qoh_out,
round(SUM(
    opening_stock + opening_balance + qoh_in - qoh_out
),2) AS closing
FROM
    (
        (
        SELECT
            `product_id`,
            batch_number,
            manufacturer_date,
            product_expire_date,
            SUM(qoh_trx_qty) AS opening_stock,
            0 AS opening_balance,
            0 AS qoh_in,
            0 AS qoh_out,
            0 as scrap_qty
        FROM
            i_qoh_detail_t
        WHERE
            qoh_source = 'OPENSTOCK'
        GROUP BY
            product_id,
            batch_number
    )
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        SUM(qoh_trx_qty) AS opening_balance,
        0 AS qoh_in,
        0 AS qoh_out,
        0 as scrap_qty
    FROM
        i_qoh_detail_t
    WHERE
        DATE(created_at) < ?
    GROUP BY
        product_id,
        batch_number
)
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        0 AS opening_balance,
        (
            CASE WHEN qoh_trx_qty > 0 THEN qoh_trx_qty ELSE 0
        END
) AS qoh_in,
(
    CASE WHEN qoh_trx_qty < 0 THEN ABS(qoh_trx_qty) ELSE 0
END
    ) AS qoh_out,0 as scrap_qty
FROM
    i_qoh_detail_t
WHERE
    DATE(created_at) >= ? AND DATE(created_at) <= ?
GROUP BY
    qoh_detail_id
)
) f
JOIN m_products_t ON m_products_t.product_id = f.product_id AND m_products_t.product_group_id = 4 and (m_products_t.product_subcategory_id=21 or m_products_t.product_subcategory_id=22 or m_products_t.product_subcategory_id=23 or m_products_t.product_subcategory_id=58) 
GROUP BY
    f.product_id,
    f.batch_number) AS v1";

        $results = \DB::select($SQL, [$start_date, $start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }


    public function getextractsemifinishedgoodsrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (SELECT
    f.product_id,
    m_products_t.concatenated_product,
    (
    SELECT
        category_name
    FROM
        m_product_category_t
    WHERE
        m_product_category_t.product_category_id = m_products_t.product_category_id
) AS cate_name,
(
    SELECT
        subcategory_name
    FROM
        m_product_subcategory_t
    WHERE
        m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
) AS sub_cate,
batch_number,
manufacturer_date,
product_expire_date,
round(SUM(opening_stock + opening_balance),2) AS opening_balance,
round(SUM(qoh_in),2) AS qoh_in,
round(SUM(qoh_out+scrap_qty),2) AS qoh_out,
round(SUM(
    opening_stock + opening_balance + qoh_in - qoh_out
),2) AS closing
FROM
    (
        (
        SELECT
            `product_id`,
            batch_number,
            manufacturer_date,
            product_expire_date,
            SUM(qoh_trx_qty) AS opening_stock,
            0 AS opening_balance,
            0 AS qoh_in,
            0 AS qoh_out,
            0 as scrap_qty
        FROM
            i_qoh_detail_t
        WHERE
            qoh_source = 'OPENSTOCK'
        GROUP BY
            product_id,
            batch_number
    )
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        SUM(qoh_trx_qty) AS opening_balance,
        0 AS qoh_in,
        0 AS qoh_out,
        0 as scrap_qty
    FROM
        i_qoh_detail_t
    WHERE
        DATE(created_at) < ?
    GROUP BY
        product_id,
        batch_number
)
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        0 AS opening_balance,
        (
            CASE WHEN qoh_trx_qty > 0 THEN qoh_trx_qty ELSE 0
        END
) AS qoh_in,
(
    CASE WHEN qoh_trx_qty < 0 THEN ABS(qoh_trx_qty) ELSE 0
END
    ) AS qoh_out,0 as scrap_qty
FROM
    i_qoh_detail_t
WHERE
    DATE(created_at) >= ? AND DATE(created_at) <= ?
GROUP BY
    qoh_detail_id
)
) f
JOIN m_products_t ON m_products_t.product_id = f.product_id AND m_products_t.product_group_id = 4 and (m_products_t.product_subcategory_id=21 or m_products_t.product_subcategory_id=22 or m_products_t.product_subcategory_id=23 or m_products_t.product_subcategory_id=58) 
GROUP BY
    f.product_id,
    f.batch_number) AS v1";

        $results = \DB::select($SQL, [$start_date, $start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }

    public function getfgstockledgerrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (
SELECT
    f.product_id,
    m_products_t.concatenated_product,
    (
    SELECT
        category_name
    FROM
        m_product_category_t
    WHERE
        m_product_category_t.product_category_id = m_products_t.product_category_id
) AS cate_name,
(
    SELECT
        subcategory_name
    FROM
        m_product_subcategory_t
    WHERE
        m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
) AS sub_cate,
batch_number,
manufacturer_date,
product_expire_date,
round(SUM(opening_stock + opening_balance),2) AS opening_balance,
round(SUM(qoh_in),2) AS qoh_in,
round(SUM(qoh_out+scrap_qty),2) AS qoh_out,
round(SUM(
    opening_stock + opening_balance + qoh_in - qoh_out - scrap_qty
),2) AS closing
FROM
    (
        (
        SELECT
            `product_id`,
            batch_number,
            manufacturer_date,
            product_expire_date,
            SUM(qoh_trx_qty) AS opening_stock,
            0 AS opening_balance,
            0 AS qoh_in,
            0 AS qoh_out,
            0 as scrap_qty
        FROM
            i_qoh_detail_t
        WHERE
            qoh_source = 'OPENSTOCK' and (subinventory_id = 3 or subinventory_id = 4)
        GROUP BY
            product_id,
            batch_number
    )
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        SUM(qoh_trx_qty) AS opening_balance,
        0 AS qoh_in,
        0 AS qoh_out,
        0 as scrap_qty
    FROM
        i_qoh_detail_t
    WHERE
        DATE(created_at) < ? AND qoh_source != 'WIP Store Move' AND qoh_source != 'Return Store Move' AND qoh_source != 'OPENSTOCK' AND qoh_source != 'SALES RETURN-SCRAP' AND qoh_source != 'MATERIAL RECEIVE' and  (subinventory_id = 3 or subinventory_id = 4) 
    GROUP BY
        product_id,
        batch_number
)
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        0 AS opening_balance,
        (
            CASE WHEN qoh_trx_qty > 0 THEN qoh_trx_qty ELSE 0
        END
) AS qoh_in,
(
    CASE WHEN qoh_trx_qty < 0 THEN ABS(qoh_trx_qty) ELSE 0
END
    ) AS qoh_out,0 as scrap_qty
FROM
    i_qoh_detail_t
WHERE
    DATE(created_at) >= ? AND DATE(created_at) <= ? AND qoh_source != 'WIP Store Move' AND qoh_source != 'Return Store Move' AND qoh_source != 'SALES RETURN-SCRAP' AND qoh_source != 'OPENSTOCK' AND qoh_source != 'MATERIAL RECEIVE'  and (subinventory_id = 3 or subinventory_id = 4)
GROUP BY
    qoh_detail_id
)
) f
JOIN m_products_t ON m_products_t.product_id = f.product_id AND m_products_t.product_group_id = 1
GROUP BY
    f.product_id,
    f.batch_number) AS v1";

        $results = \DB::select($SQL, [$start_date, $start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }



    public function getpromotionalstockledgerrpt(Request $request)
    {
        $wh = '';
        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "select * from(SELECT
    f.product_id,
    m_products_t.concatenated_product,
    (
    SELECT
        category_name
    FROM
        m_product_category_t
    WHERE
        m_product_category_t.product_category_id = m_products_t.product_category_id
) AS cate_name,
(
    SELECT
        subcategory_name
    FROM
        m_product_subcategory_t
    WHERE
        m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
) AS sub_cate,
batch_number,
manufacturer_date,
product_expire_date,
round(SUM(opening_stock + opening_balance),2) AS opening_balance,
round(SUM(qoh_in),2) AS qoh_in,
round(SUM(qoh_out+scrap_qty),2) AS qoh_out,
round(SUM(
    opening_stock + opening_balance + qoh_in - qoh_out - scrap_qty
),2) AS closing
FROM
    (
        (
        SELECT
            `product_id`,
            batch_number,
            manufacturer_date,
            product_expire_date,
            SUM(qoh_trx_qty) AS opening_stock,
            0 AS opening_balance,
            0 AS qoh_in,
            0 AS qoh_out,
            0 as scrap_qty
        FROM
            i_qoh_detail_t
        WHERE
            qoh_source = 'OPENSTOCK' and (subinventory_id = 3 or subinventory_id = 4)
        GROUP BY
            product_id,
            batch_number
    )
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        SUM(qoh_trx_qty) AS opening_balance,
        0 AS qoh_in,
        0 AS qoh_out,
        0 as scrap_qty
    FROM
        i_qoh_detail_t
    WHERE
        DATE(created_at) < '$start_date' AND qoh_source != 'WIP Store Move' AND qoh_source != 'Return Store Move' AND qoh_source != 'OPENSTOCK' AND qoh_source != 'SALES RETURN-SCRAP' AND qoh_source != 'MATERIAL RECEIVE' and  (subinventory_id = 3 or subinventory_id = 4) 
    GROUP BY
        product_id,
        batch_number
)
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        0 AS opening_balance,
        (
            CASE WHEN qoh_trx_qty > 0 THEN qoh_trx_qty ELSE 0
        END
) AS qoh_in,
(
    CASE WHEN qoh_trx_qty < 0 THEN ABS(qoh_trx_qty) ELSE 0
END
    ) AS qoh_out,0 as scrap_qty
FROM
    i_qoh_detail_t
WHERE
    DATE(created_at) >= '$start_date' AND DATE(created_at) <= '$end_date' AND qoh_source != 'WIP Store Move' AND qoh_source != 'Return Store Move' AND qoh_source != 'SALES RETURN-SCRAP' AND qoh_source != 'OPENSTOCK' AND qoh_source != 'MATERIAL RECEIVE'  and (subinventory_id = 3 or subinventory_id = 4)
GROUP BY
    qoh_detail_id
)
) f
JOIN m_products_t ON m_products_t.product_id = f.product_id AND m_products_t.product_group_id = 12
GROUP BY
    f.product_id,
    f.batch_number)v1 where 1=1 $wh";


        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }


    public function getwipstockledgerrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (SELECT
    f.product_id,
    m_products_t.concatenated_product,
    (
    SELECT
        category_name
    FROM
        m_product_category_t
    WHERE
        m_product_category_t.product_category_id = m_products_t.product_category_id
) AS cate_name,
(
    SELECT
        subcategory_name
    FROM
        m_product_subcategory_t
    WHERE
        m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
) AS sub_cate,
batch_number,
manufacturer_date,
product_expire_date,
round(SUM(opening_stock + opening_balance),2) AS opening_balance,
round(SUM(qoh_in),2) AS qoh_in,
round(SUM(qoh_out+scrap_qty),2) AS qoh_out,
round(SUM(
    opening_stock + opening_balance + qoh_in - qoh_out-scrap_qty
),2) AS closing
FROM
    (
        (
        SELECT
            `product_id`,
            batch_number,
            manufacturer_date,
            product_expire_date,
            SUM(qoh_trx_qty) AS opening_stock,
            0 AS opening_balance,
            0 AS qoh_in,
            0 AS qoh_out,
            0 as scrap_qty
        FROM
            i_qoh_detail_t
        WHERE
            qoh_source = 'OPENSTOCK' and (subinventory_id = 5)
        GROUP BY
            product_id,
            batch_number
    )
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        SUM(ABS(qoh_trx_qty)) AS opening_balance,
        0 AS qoh_in,
        0 AS qoh_out,
        0 as scrap_qty
    FROM
        i_qoh_detail_t
    WHERE
        DATE(created_at) < ? AND ((qoh_source = 'PRODUCTION STORE MOVE'  or  ( qoh_source = 'SUBINVENTORY TRANSFER' and subinventory_id = 5)))
    GROUP BY
        product_id,
        batch_number
)
UNION ALL
    (
    SELECT
        product_id,
        batch_number,
        manufacturer_date,
        product_expire_date,
        0 AS opening_stock,
        0 AS opening_balance,
        (
            CASE WHEN qoh_trx_qty > 0 THEN qoh_trx_qty ELSE 0
        END
) AS qoh_in,
(
    CASE WHEN qoh_trx_qty < 0 THEN ABS(qoh_trx_qty) ELSE 0
END
    ) AS qoh_out,0 as scrap_qty
FROM
    i_qoh_detail_t
WHERE
    DATE(created_at) >= ? AND DATE(created_at) <= ? AND qoh_source != 'WIP Store Move' AND qoh_source != 'Return Store Move' AND qoh_source != 'SALES RETURN-SCRAP' AND qoh_source != 'OPENSTOCK' AND qoh_source != 'MATERIAL RECEIVE'  and (subinventory_id = 3 or subinventory_id = 4)
GROUP BY
    qoh_detail_id
)
) f
JOIN m_products_t ON m_products_t.product_id = f.product_id AND m_products_t.product_group_id = 1
GROUP BY
    f.product_id,
    f.batch_number) AS v1";

        $results = \DB::select($SQL, [$start_date, $start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }

    public function getrmstockledgerrpt()
    {
        //dd("fddfdf");
        $wh = '';
        $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
        $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
        $product_id = isset($_GET['product_id']) && !empty($_GET['product_id']) ? $_GET['product_id'] : '';

        if (isset($_GET['pq_filter'])) {
            $data = json_decode($_GET['pq_filter']);
            $data = $data->data;
            $wh .= $this->pqgridsearchsum('v1', $data);
        }
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];

        $sidx = '';
        if (!$sidx)
            $sidx = 1;

        $SQL = "select * from ( 
    select round(sum(i_qoh_detail_t.qoh_trx_qty),4) as opening_bal,m_products_t.product_code,m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,m_subinventory_t.subinventory_name,i_qoh_detail_t.batch_number, i_qoh_detail_t.product_id,0  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date from i_qoh_detail_t i_qoh_detail_t left join m_subinventory_t on (m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id) left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PURCHASE_STOREMOVE') and (m_product_groups_t.product_group_id =2 ) and Date(i_qoh_detail_t.created_at) = '$start_date' and m_products_t.product_id = '$product_id' GROUP by m_products_t.product_id,i_qoh_detail_t.batch_number 
    UNION all
               select 
                0 as opening_bal,m_products_t.product_code, m_products_t.concatenated_product, Date(i_qoh_detail_t.created_at) as date,m_subinventory_t.subinventory_name, i_qoh_detail_t.batch_number, i_qoh_detail_t.product_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4)  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date from i_qoh_detail_t i_qoh_detail_t left join m_subinventory_t on (m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id) left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PURCHASE_STOREMOVE') and i_qoh_detail_t.created_at BETWEEN '$start_date' AND '$end_date' and m_products_t.product_id = '$product_id'  and ( m_product_groups_t.product_group_id =2 ) GROUP by i_qoh_detail_t.qoh_detail_id,i_qoh_detail_t.batch_number  
                UNION all 
                select 0 as opening_bal, m_products_t.product_code, m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,m_subinventory_t.subinventory_name,i_qoh_detail_t.batch_number,i_qoh_detail_t.product_id,0 as stock_in ,ABS(round(sum(i_qoh_detail_t.qoh_trx_qty),4)) as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date from i_qoh_detail_t i_qoh_detail_t left join m_subinventory_t on (m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id) left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE i_qoh_detail_t.qoh_source='MATERIAL ISSUE' or i_qoh_detail_t.qoh_source='MATERIAL RECEIVE' or i_qoh_detail_t.qoh_source='CONSUMABLE' and i_qoh_detail_t.created_at BETWEEN '$start_date' AND '$end_date' and (m_product_groups_t.product_group_id =2 ) and m_products_t.product_id = '$product_id'  GROUP by i_qoh_detail_t.qoh_detail_id,i_qoh_detail_t.batch_number  
    UNION ALL
    
   select 0 as opening_bal,m_products_t.product_code,m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,m_subinventory_t.subinventory_name,i_qoh_detail_t.batch_number, i_qoh_detail_t.product_id,0  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4) as closing_bal,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date from i_qoh_detail_t i_qoh_detail_t left join m_subinventory_t on (m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id) left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PURCHASE_STOREMOVE' or i_qoh_detail_t.qoh_source='material return')  and (m_product_groups_t.product_group_id =2) and Date(i_qoh_detail_t.created_at) = '$end_date' and m_products_t.product_id = '$product_id' GROUP by m_products_t.product_id,i_qoh_detail_t.batch_number    
    
    ) v1 where 1=1 $wh";


        $result = \DB::select($SQL);

        $count = count($result);
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;

        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $SQL = "select * from ( 
    select round(sum(i_qoh_detail_t.qoh_trx_qty),4) as opening_bal,m_products_t.product_code,m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,m_subinventory_t.subinventory_name,i_qoh_detail_t.batch_number, i_qoh_detail_t.product_id,0  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date from i_qoh_detail_t i_qoh_detail_t left join m_subinventory_t on (m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id)  left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PURCHASE_STOREMOVE') and (m_product_groups_t.product_group_id =2 ) and Date(i_qoh_detail_t.created_at) = '$start_date' and m_products_t.product_id = '$product_id' GROUP by m_products_t.product_id,i_qoh_detail_t.batch_number 
    UNION all
               select 
                0 as opening_bal,m_products_t.product_code, m_products_t.concatenated_product, Date(i_qoh_detail_t.created_at) as date,m_subinventory_t.subinventory_name, i_qoh_detail_t.batch_number, i_qoh_detail_t.product_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4)  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date from i_qoh_detail_t i_qoh_detail_t left join m_subinventory_t on (m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id) left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PURCHASE_STOREMOVE') and i_qoh_detail_t.created_at BETWEEN '$start_date' AND '$end_date' and m_products_t.product_id = '$product_id'  and ( m_product_groups_t.product_group_id =2 ) GROUP by i_qoh_detail_t.qoh_detail_id,i_qoh_detail_t.batch_number  
                UNION all 
                select 0 as opening_bal, m_products_t.product_code, m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,m_subinventory_t.subinventory_name,i_qoh_detail_t.batch_number,i_qoh_detail_t.product_id,0 as stock_in ,ABS(round(sum(i_qoh_detail_t.qoh_trx_qty),4)) as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,0 as closing_bal,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date from i_qoh_detail_t i_qoh_detail_t left join m_subinventory_t on (m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id) left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE i_qoh_detail_t.qoh_source='MATERIAL ISSUE' or i_qoh_detail_t.qoh_source='MATERIAL RECEIVE' or i_qoh_detail_t.qoh_source='CONSUMABLE' and i_qoh_detail_t.created_at BETWEEN '$start_date' AND '$end_date' and (m_product_groups_t.product_group_id =2 ) and m_products_t.product_id = '$product_id'  GROUP by i_qoh_detail_t.qoh_detail_id,i_qoh_detail_t.batch_number  
    UNION ALL
    
   select 0 as opening_bal,m_products_t.product_code,m_products_t.concatenated_product,Date(i_qoh_detail_t.created_at) as date,m_subinventory_t.subinventory_name,i_qoh_detail_t.batch_number, i_qoh_detail_t.product_id,0  as stock_in ,0 as stock_out,i_qoh_detail_t.qoh_source,i_qoh_detail_t.create_trx_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4) as closing_bal,i_qoh_detail_t.manufacturer_date,i_qoh_detail_t.product_expire_date from i_qoh_detail_t i_qoh_detail_t left join m_subinventory_t on (m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id) left join m_products_t on(m_products_t.product_id = i_qoh_detail_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id = m_products_t.product_group_id)  LEFT JOIN m_product_category_t on(m_product_category_t.product_category_id = m_products_t.product_category_id) LEFT JOIN m_material_trx_t on(m_material_trx_t.material_trx_id = i_qoh_detail_t.create_trx_id) WHERE (i_qoh_detail_t.qoh_source='OPENSTOCK' or i_qoh_detail_t.qoh_source ='PURCHASE_STOREMOVE' or i_qoh_detail_t.qoh_source='material return')  and (m_product_groups_t.product_group_id =2) and Date(i_qoh_detail_t.created_at) = '$end_date' and m_products_t.product_id = '$product_id' GROUP by m_products_t.product_id,i_qoh_detail_t.batch_number    
    
    ) v1 where 1=1 $wh ORDER BY v1.closing_bal asc LIMIT $start , $limit";
        // dd($SQL);
        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->data = $result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
    }

    public function getjobnorpt($id = null)
    {
        $sql = \DB::select("select w_jobcard_hdr_t.w_jobs_hdr_id from w_jobcard_hdr_t left join w_productionplan_hdr_t on (w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) where w_productionplan_hdr_t.reference_no='" . $id . "' and w_jobcard_hdr_t.reference_source='PLAN'");
        if (count($sql) > 0) {
            return $sql[0]->w_jobs_hdr_id;
        } else {
            return 0;
        }
    }

    public function getjobproduct($id = null)
    {
        $sql = \DB::select("select * from w_workorder_lines_t where w_workorder_lines_t.workorder_hdr_id='" . $id . "'");
        if (count($sql) > 0) {
            return $sql[0]->product_id;
        } else {
            return 0;
        }
    }

    public function stagewiseget(Request $request)
    {
        $product = \DB::Select("select m_product_groups_t.group_name from m_products_t left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id) where product_id='" . $_GET['product_id'] . "'");
        if ($product[0]->group_name == "SEMI FINISHED GOODS") {
            if ($_GET['job_no'] != "") {
                $sql = \DB::select("SELECT changebom_hdr_t.*,w_productionplan_hdr_t.reference_id FROM `w_jobcard_hdr_t` left join w_productionplan_hdr_t on w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id 
left join w_workorder_lines_t on w_workorder_lines_t.workorder_line_id=w_productionplan_hdr_t.reference_id left join changebom_hdr_t on changebom_hdr_t.lineid=w_workorder_lines_t.workorder_line_id where w_jobcard_hdr_t.w_jobs_hdr_id='" . $_GET['job_no'] . "' and changebom_hdr_t.active='Yes'");
                if (count($sql) == 0) {
                    $bom_details = \DB::table('m_material_bom_hdr_t')
                        ->leftjoin('m_material_bom_lines_t', 'm_material_bom_lines_t.material_bom_hdr_id', '=', 'm_material_bom_hdr_t.material_bom_hdr_id')
                        ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'm_material_bom_lines_t.component_product_id')
                        ->leftjoin('m_product_groups_t', 'm_products_t.product_id', '=', 'm_product_groups_t.product_group_id')
                        ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_material_bom_lines_t.routing')
                        ->WHERE('m_material_bom_hdr_t.assembly_product_id', $_GET['product_id'])->where('m_material_bom_hdr_t.active', 'Yes')->select('m_material_bom_lines_t.*', 'm_material_bom_hdr_t.*', 'm_products_t.*', 'a_lookuplines_t.lookup_code')->groupBy('m_material_bom_lines_t.routing')->get();


                } else {
                    $bom_details = \DB::table('changebom_hdr_t')
                        ->leftjoin('changebom_lines_t', 'changebom_lines_t.changebom_hdr_id', '=', 'changebom_hdr_t.changebom_hdr_id')
                        ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'changebom_lines_t.component_product_id')
                        ->leftjoin('m_product_groups_t', 'm_products_t.product_id', '=', 'm_product_groups_t.product_group_id')
                        ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'changebom_lines_t.routing')
                        ->WHERE('changebom_hdr_t.assembly_product_id', $_GET['product_id'])->where('changebom_hdr_t.active', 'Yes')->select('changebom_lines_t.*', 'changebom_hdr_t.*', 'm_products_t.*', 'a_lookuplines_t.lookup_code')->groupBy('changebom_lines_t.routing')->get();

                }

            } else {
                $bom_details = \DB::table('m_material_bom_hdr_t')
                    ->leftjoin('m_material_bom_lines_t', 'm_material_bom_lines_t.material_bom_hdr_id', '=', 'm_material_bom_hdr_t.material_bom_hdr_id')
                    ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'm_material_bom_lines_t.component_product_id')
                    ->leftjoin('m_product_groups_t', 'm_products_t.product_id', '=', 'm_product_groups_t.product_group_id')
                    ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_material_bom_lines_t.routing')
                    ->WHERE('m_material_bom_hdr_t.assembly_product_id', $_GET['product_id'])->where('m_material_bom_hdr_t.active', 'Yes')->select('m_material_bom_lines_t.*', 'm_material_bom_hdr_t.*', 'm_products_t.*', 'a_lookuplines_t.lookup_code')->groupBy('m_material_bom_lines_t.routing')->get();

            }
        }
        $html = '';
        $html = '<div class="row">
		<div class="col-md-12">
      
        <table class="table" cellpadding="0" cellspacing="0">
            <tbody>


            <h2 class="heads1">Status Details</h2>
            
            <tr class="information">
                <td colspan="6">

					<table class="table">
					<thead>
					<th>Stage</th>
					<th>Material Issue</th>
					<th>Material Receive</th>
					</thead>
								<tbody>';
        if ($_GET['job_no'] != "") {
            foreach ($bom_details as $key => $value) {
                $materialissue = \DB::table('w_materialissue_hdr_t')->where('w_jobs_hdr_id', $_GET['job_no'])->where('routing_id', $value->routing)->get();
                if (count($materialissue) > 0) {
                    $status = "COMPLETED";
                    $labelColor = "label-success";
                } else {
                    $status = "PENDING";
                    $labelColor = "label-danger";
                }
                $materialreceive = \DB::table('w_materialreceive_hdr_t')->leftjoin('w_materialreceive_line_t', 'w_materialreceive_line_t.w_materialreceive_hdr_id', '=', 'w_materialreceive_hdr_t.w_materialreceive_hdr_id')->where('w_materialreceive_hdr_t.w_jobs_hdr_id', $_GET['job_no'])->where('w_materialreceive_line_t.routing_id', $value->routing)->groupBy('w_materialreceive_line_t.routing_id')->get();
                if (count($materialreceive) > 0) {
                    $status1 = "COMPLETED";
                    $labelColor1 = "label-success";
                } else {
                    $status1 = "PENDING";
                    $labelColor1 = "label-danger";
                }
                if ($value->lookup_code != "STAGE-1") {
                    if ($status == "PENDING" && $status1 == "PENDING") {
                        $status1 = "QUEUE";
                        $status = "QUEUE";
                        $labelColor = $labelColor1 = "label-warning";
                    }
                }
                $html .= '<tr>
									<td>
										' . $value->lookup_code . ' <br>
									</td>
									<td><span class="label ' . $labelColor . ' " >' . $status . '</span></td>
									<td><span class="label ' . $labelColor1 . ' ">' . $status1 . '</span></td>
								</tr>';
            }
        } else {
            $html .= '<tr><td> There is No Job created For this Workorder</td></tr>';
        }
        $html .= '</tbody>
					</table>
       </td>
            </tr>  
        </tbody></table>
		<br>
		</div>
    </div>';

        return $html;
    }


}
