<?php

namespace App\Http\Controllers;

use App\Productbasedcostreport;
use Illuminate\Http\Request;
use DB;
use yajra\datatables\datatables;

class QohreportController extends Controller
{
    public $module = "Productbasedcostreport";

    public function index()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';


        if ($this->data['pageMethod'] == "qohrpt") {
            $this->data['pageMethod'] = "RAW MATERIALS";
            $this->data['locator'] = "STORE";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 5;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 1;
        } elseif ($this->data['pageMethod'] == "verduraqohrpt") {
            $this->data['pageMethod'] = "RAW MATERIALS";
            $this->data['locator'] = "STORE";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 6;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 2;
        } elseif ($this->data['pageMethod'] == "finishedgoodrpt") {
            $this->data['pageMethod'] = "FINISHED GOODS";
            $this->data['locator'] = "finished goods";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 3;
        } elseif ($this->data['pageMethod'] == "samplegoodrpt") {
            $this->data['pageMethod'] = "FINISHED GOODS";
            $this->data['locator'] = "sample goods";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 0;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 4;
        } elseif ($this->data['pageMethod'] == "wiprpt") {
            $this->data['pageMethod'] = "FINISHED GOODS";
            $this->data['locator'] = "WIP goods";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 1;
            //$this->data['jobqty']=1;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 5;

        } elseif ($this->data['pageMethod'] == "semifinishedgoodrpt") {
            $this->data['pageMethod'] = "SEMI FINISHED GOODS";
            $this->data['locator'] = "semi goods";
            $this->data['subcategory'] = 2;
            $this->data['loccode'] = 0;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 6;
        } elseif ($this->data['pageMethod'] == "pggoodrpt") {
            $this->data['pageMethod'] = "SEMI FINISHED GOODS";
            $this->data['locator'] = "Semi Extract Goods";
            $this->data['subcategory'] = 1;
            $this->data['loccode'] = 0;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 7;
        } elseif ($this->data['pageMethod'] == "wpggoodrpt") {
            $this->data['pageMethod'] = "SEMI FINISHED GOODS";
            $this->data['locator'] = "WIP goods";
            $this->data['subcategory'] = 2;
            $this->data['loccode'] = 1;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 8;
        } elseif ($this->data['pageMethod'] == "controlsamplesrpt") {
            $this->data['pageMethod'] = "FINISHED GOODS";
            $this->data['locator'] = "Control Sample Goods";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 2;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 9;
        } elseif ($this->data['pageMethod'] == "packingmaterialstockrpt") {
            $this->data['pageMethod'] = "PACKING MATERIALS";
            $this->data['locator'] = "STORE";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 3;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 10;
        } elseif ($this->data['pageMethod'] == "verdurapackingmaterialstockrpt") {
            $this->data['pageMethod'] = "PACKING MATERIALS";
            $this->data['locator'] = "STORE";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 4;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 11;
        } elseif ($this->data['pageMethod'] == "consumablerpt") {
            $this->data['pageMethod'] = "CONSUMABLES";
            $this->data['locator'] = "STORE";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 0;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 12;
        } elseif ($this->data['pageMethod'] == 'promotionalrpt') {
            $this->data['pageMethod'] = 'PROMOTIONAL ITEMS';
            $this->data['locator'] = "sample goods";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 0;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 13;
        } elseif ($this->data['pageMethod'] == 'accessoriesrpt') {
            $this->data['pageMethod'] = "ACCESSORIES";
            $this->data['locator'] = "Accessories";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 0;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 14;
        } elseif ($this->data['pageMethod'] == 'labqohrpt') {
            $this->data['pageMethod'] = "RAW MATERIALS";
            $this->data['locator'] = "LABORATORY";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 8;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 15;
        } elseif ($this->data['pageMethod'] == "wpmgoodrpt") {
            $this->data['pageMethod'] = "PACKING MATERIALS";
            $this->data['locator'] = "WIP PM Goods";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 9;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 16;
        } elseif ($this->data['pageMethod'] == "labpmqohrpt") {
            $this->data['pageMethod'] = "PACKING MATERIALS";
            $this->data['locator'] = "LABORATORY";
            $this->data['subcategory'] = 0;
            $this->data['loccode'] = 10;
            //$this->data['jobqty']=0;
            $this->data['wipenable'] = 0;
            $this->data['pageTitle'] = 17;
        }


        if ($this->data['pageMethod'] == 'lossrpt') {
            $data_grid = \DB::SELECT("SELECT * FROM `i_product_packs` group by pack_value order by pack_name asc");


            if (count($data_grid) > 0) {
                foreach ($data_grid as $p_name) {

                    $p_name = $p_name->pack_name;

                    $html[] = array('dataIndx' => "qty" . $p_name, 'align' => "right", 'title' => $p_name, 'minWidth' => "12%");
                }
            }


            $this->data['datacolumn'] = json_encode($html);
            return view('inventoryreport.lossreporttable', $this->data);
        } else if ($this->data['pageMethod'] == 'allproductstockrpt') {
            return view('inventoryreport.productqohreporttable', $this->data);
        } else if ($this->data['pageMethod'] == 'negativeproductstockrpt') {
            return view('inventoryreport.productnegativeqohreporttable', $this->data);
        } else if ($this->data['pageMethod'] == 'allproductstockwithaccrpt') {
            return view('inventoryreport.productqohwithaccreporttable', $this->data);
        } else if ($this->data['pageMethod'] == 'batchrpt') {

            $data_grid = \DB::SELECT("SELECT * FROM `i_product_packs` group by pack_value order by pack_name asc");


            if (count($data_grid) > 0) {
                foreach ($data_grid as $p_name) {

                    $p_name = $p_name->pack_name;

                    $html[] = array('dataIndx' => "qty" . $p_name, 'align' => "right", 'title' => $p_name, 'minWidth' => "12%");
                }
            }


            $this->data['datacolumn'] = json_encode($html);
            return view('inventoryreport.batchdetailstable', $this->data);

        } else {
            //dd($this->data);
            return view('inventoryreport.qohreporttable', $this->data);
        }


    }

    public function getproductionlossdata(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (SELECT
    w_jobs_hdr_id,
    sfgqty,
    job_no,
	  month,
    product_code,
    concatenated_product,
   (select m_product_category_t.category_name FROM m_product_category_t join m_products_t on  m_product_category_t.product_category_id=m_products_t.product_category_id LIMIT 1) category_name,
   issuetolab,
    batch_no,
    job_adjusted_qty,
    remarks,
    job_date,
    job_completion_date,
    created_at,
    manufacturer_date,
    product_expire_date,
    ABS(
        ROUND((job_adjusted_qty - sfgqty),
        2)
    ) AS production_loss,
    
ROUND(SUM(production_qty),
2) AS filledkg,
ROUND(SUM(scrap_qty),
2) AS other_loss,
ROUND(SUM(scrap_qty),
2) AS diffqty,
ABS(
    ROUND(
        (job_adjusted_qty - sfgqty) + SUM(scrap_qty),
        2
    )
) AS total_loss
FROM
    (
    SELECT
        job.job_no AS fgjob,
        w_qa_submitstage_trx_t.production_qty AS fg_qty,
        (select round(sum(production_qty),2) from w_qa_submitstage_trx_t where w_qa_submitstage_trx_t.product_id=w_jobcard_hdr_t.product_id and w_qa_submitstage_trx_t.batch_no=w_jobcard_hdr_t.batch_no limit 1)as sfgqty,
        w_qa_submitstage_trx_t.reference_no,
        w_qa_submitstage_line_t.production_qty,
        w_qa_submitstage_line_t.scrap_qty,
		 (SELECT  pack_value FROM `i_product_packs` where i_product_packs.packing_id=m_pro.product_pack_id limit 1) as pack_name,m_pro.product_pack_id,
        w_jobcard_hdr_t.`job_no`,
		date_format(w_jobcard_hdr_t.job_date,'%b-%y') as month,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.job_completion_date,
        w_jobcard_hdr_t.product_id,
        w_jobcard_hdr_t.job_qty,
        round(sum(w_jobcard_hdr_t.job_adjusted_qty),2) as job_adjusted_qty,
        w_jobcard_hdr_t.batch_no,
        m_products_t.product_code,
        m_products_t.concatenated_product,
        m_products_t.product_group_id,
        m_products_t.product_variant_id,
        if(m_products_t.product_variant_id=1,0.3,0.5)as issuetolab,
        w_qa_submitstage_trx_t.remarks,
        w_jobcard_hdr_t.w_jobs_hdr_id,
        m_products_t.product_category_id,
        (select created_at from i_quality_spec_trx_hdr_t where i_quality_spec_trx_hdr_t.job_hdr_id = w_jobcard_hdr_t.w_jobs_hdr_id limit 1) as created_at,
         (select manufacturer_date from i_qoh_detail_t where i_qoh_detail_t.batch_number = w_jobcard_hdr_t.batch_no limit 1) as manufacturer_date,
        (select product_expire_date from i_qoh_detail_t where i_qoh_detail_t.batch_number = w_jobcard_hdr_t.batch_no limit 1) as product_expire_date
        
    FROM
        w_jobcard_hdr_t
    JOIN m_products_t ON
        (
            m_products_t.product_id = w_jobcard_hdr_t.product_id
        )
    JOIN(
        SELECT * FROM
            w_jobcard_hdr_t
        WHERE
            job_no like '%JOBOP%'
    ) job
ON
    job.batch_no = w_jobcard_hdr_t.batch_no
JOIN w_qa_submitstage_trx_t ON w_qa_submitstage_trx_t.job_no = job.w_jobs_hdr_id
JOIN w_qa_submitstage_line_t ON w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id = w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id AND w_qa_submitstage_line_t.product_id = w_jobcard_hdr_t.product_id
JOIN m_products_t AS m_pro
ON
    m_pro.product_id = job.product_id

WHERE
    m_products_t.product_group_id = 4 and w_jobcard_hdr_t.job_date between ? and ?
GROUP BY
    job.job_no
ORDER BY
    w_jobcard_hdr_t.batch_no ASC
) f
GROUP BY
    f.w_jobs_hdr_id) AS v1";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }


    public function getbatchdetailsdata(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "SELECT * from (SELECT
    *
FROM
    (
    SELECT
        w_jobs_hdr_id,
        sfgqty,
        job_no,
        month,
        product_code,
        concatenated_product,
        (
        SELECT
            m_product_category_t.category_name
        FROM
            m_product_category_t
        JOIN m_products_t ON m_product_category_t.product_category_id = m_products_t.product_category_id
        LIMIT 1
    ) category_name,
    issuetolab,
    batch_no,
    job_adjusted_qty,
    remarks,
    job_date,
    job_completion_date,
    created_at,
    manufacturer_date,
    product_expire_date,
    ABS(
        ROUND((job_adjusted_qty - sfgqty), 2)
    ) AS production_loss,
    ROUND(SUM(production_qty), 2) AS filledkg,
    ROUND(SUM(scrap_qty), 2) AS other_loss,
    ROUND(SUM(scrap_qty), 2) AS diffqty,
    ABS(
        ROUND(
            (job_adjusted_qty - sfgqty) + SUM(scrap_qty),
            2
        )
    ) AS total_loss
FROM
    (
    SELECT
        job.job_no AS fgjob,
        w_qa_submitstage_trx_t.production_qty AS fg_qty,
        (
        SELECT
            ROUND(SUM(production_qty),
            2)
        FROM
            w_qa_submitstage_trx_t
        WHERE
            w_qa_submitstage_trx_t.product_id = w_jobcard_hdr_t.product_id AND w_qa_submitstage_trx_t.batch_no = w_jobcard_hdr_t.batch_no
        LIMIT 1
    ) AS sfgqty,
    w_qa_submitstage_trx_t.reference_no,
    w_qa_submitstage_line_t.production_qty,
    w_qa_submitstage_line_t.scrap_qty,
    (
    SELECT
        pack_value
    FROM
        `i_product_packs`
    WHERE
        i_product_packs.packing_id = m_pro.product_pack_id
    LIMIT 1
) AS pack_name,
m_pro.product_pack_id,
w_jobcard_hdr_t.`job_no`,
DATE_FORMAT(w_jobcard_hdr_t.job_date, '%b-%y') AS month,
w_jobcard_hdr_t.job_date,
w_jobcard_hdr_t.job_completion_date,
w_jobcard_hdr_t.product_id,
w_jobcard_hdr_t.job_qty,
ROUND(
    SUM(
        w_jobcard_hdr_t.job_adjusted_qty
    ),
    2
) AS job_adjusted_qty,
w_jobcard_hdr_t.batch_no,
m_products_t.product_code,
m_products_t.concatenated_product,
m_products_t.product_group_id,
m_products_t.product_variant_id,
IF(
    m_products_t.product_variant_id = 1,
    0.3,
    0.5
) AS issuetolab,
w_qa_submitstage_trx_t.remarks,
w_jobcard_hdr_t.w_jobs_hdr_id,
m_products_t.product_category_id,
(
    SELECT
        created_at
    FROM
        i_quality_spec_trx_hdr_t
    WHERE
        i_quality_spec_trx_hdr_t.job_hdr_id = w_jobcard_hdr_t.w_jobs_hdr_id AND i_quality_spec_trx_hdr_t.created_at > '2024-04-01'
    LIMIT 1
) AS created_at,
(
    SELECT
        manufacturer_date
    FROM
        i_qoh_detail_t
    WHERE
        i_qoh_detail_t.batch_number = w_jobcard_hdr_t.batch_no
    LIMIT 1
) AS manufacturer_date,
(
    SELECT
        product_expire_date
    FROM
        i_qoh_detail_t
    WHERE
        i_qoh_detail_t.batch_number = w_jobcard_hdr_t.batch_no
    LIMIT 1
) AS product_expire_date
FROM
    w_jobcard_hdr_t
JOIN m_products_t ON(
        m_products_t.product_id = w_jobcard_hdr_t.product_id
    )
JOIN(
    SELECT
        *
    FROM
        w_jobcard_hdr_t
    WHERE
        job_no LIKE '%JOBOP%'
) job
ON
    job.batch_no = w_jobcard_hdr_t.batch_no
JOIN w_qa_submitstage_trx_t ON w_qa_submitstage_trx_t.job_no = job.w_jobs_hdr_id
JOIN w_qa_submitstage_line_t ON w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id = w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id AND w_qa_submitstage_line_t.product_id = w_jobcard_hdr_t.product_id
JOIN m_products_t AS m_pro
ON
    m_pro.product_id = job.product_id
WHERE
    m_products_t.product_group_id = 4 AND w_jobcard_hdr_t.job_date BETWEEN ? AND ?
GROUP BY
    job.job_no
ORDER BY
    w_jobcard_hdr_t.batch_no ASC
) f
GROUP BY
    f.w_jobs_hdr_id
) v1) AS vikki";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }


    public function materialagingrpt()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        // dd($this->data['pageMethod']);
        return view('inventoryreport.table', $this->data);
    }

    /*deepika purpose:consumable material report*/

    public function consumableindex()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        return view('inventoryreport.consumabletable', $this->data);
    }
    public function getconsumabledata()
    {
        $wh = '';
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $decimal = \Session::get('decimal');

        if (isset($_GET['pq_filter'])) {
            $data = json_decode($_GET['pq_filter']);

            $data = $data->data;

            $wh .= $this->pqgridsearch('i_consumable_lines_t', $data);

        }
        $group = $_GET['group'];
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $loc = $_GET['locator'];
        $ctgy = $_GET['subcategory'];
        $cod = $_GET['loccode'];
        //$jqy=$_GET['jobqty'];

        $sidx = '';
        $compy = \Session::get('companyid');
        if (!$sidx)
            $sidx = 1;
        $result = "select * from i_consumable_lines_t left join m_products_t on (m_products_t.product_id=i_consumable_lines_t.product_id) left join m_subinventory_t on(m_subinventory_t.subinventory_id=i_consumable_lines_t.subinventory) left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_consumable_lines_t.sublocator) left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=i_consumable_lines_t.accounts_structure_id)  $wh";
        $result = \DB::select($result);


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



        $SQL = "SELECT i_consumable_lines_t.batch_no,i_consumable_lines_t.comments,round(i_consumable_lines_t.qoh,2) as qoh,round(i_consumable_lines_t.qty,2) as qty,m_products_t.product_code,m_products_t.concatenated_product,f_account_structure_t.concatenated_segments,m_subinventory_t.subinventory_name,m_sublocators_t.locator_code FROM `i_consumable_lines_t` left join m_products_t on (m_products_t.product_id=i_consumable_lines_t.product_id) left join m_subinventory_t on(m_subinventory_t.subinventory_id=i_consumable_lines_t.subinventory) left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_consumable_lines_t.sublocator) left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=i_consumable_lines_t.accounts_structure_id) $wh ORDER BY $sidx DESC LIMIT $start , $limit";
        $download_SQL = "SELECT i_consumable_lines_t.batch_no,i_consumable_lines_t.comments,round(i_consumable_lines_t.qoh,2) as qoh,round(i_consumable_lines_t.qty,2) as qty,m_products_t.product_code,m_products_t.concatenated_product,f_account_structure_t.concatenated_segments,m_subinventory_t.subinventory_name,m_sublocators_t.locator_code FROM `i_consumable_lines_t` left join m_products_t on (m_products_t.product_id=i_consumable_lines_t.product_id) left join m_subinventory_t on(m_subinventory_t.subinventory_id=i_consumable_lines_t.subinventory) left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_consumable_lines_t.sublocator) left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=i_consumable_lines_t.accounts_structure_id) $wh ORDER BY $sidx DESC";
        $result1 = \DB::select($download_SQL);
        $result1 = collect($result1)->map(function ($x) {
            return (array) $x; })->toArray();
        if (isset($_GET['download'])) {
            return $result1;
        }

        $result = \DB::select($SQL);


        $responce->rows[] = '';
        $responce->data = array_values($result);
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
    }
    /*end*/	/*end*/

    public function getproductqohreport(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : NULL;



        $SQL = "SELECT
        v1.product_id,
        v1.created_at,
        v1.subinventory_id,
        v1.qoh,
        v1.manufacturer_date,
        v1.product_expire_date,
        v1.concatenated_product,
        v1.product_alternate_name,
        v1.product_classification,
        ROUND(IF(v1.std_cost = 0, v1.rate, v1.std_cost),2) AS std_cost,
        v1.locator_name,
        v1.locator_code,
        v1.batch_number,
        v1.product_group_id,
        v1.product_category_id,
        v1.subcategory_name,
        v1.rate,
        ROUND(IF(v1.total_std_cost = 0, v1.qoh * v1.rate, v1.total_std_cost),2) AS total_std_cost,
        v1.totalrate

FROM
    (
    SELECT
        i_qoh_detail_t.product_id,
        i_qoh_detail_t.created_at,
        m_products_t.subinventory_id,
        ROUND(
            SUM(i_qoh_detail_t.qoh_trx_qty),
            2
        ) AS qoh,
        i_qoh_detail_t.manufacturer_date,
        i_qoh_detail_t.product_expire_date,
        m_products_t.concatenated_product,
        m_products_t.product_alternate_name,
        m_products_t.product_classification,
        m_products_t.`Std Cost` as std_cost,
        m_sublocators_t.locator_name,
        m_sublocators_t.locator_code,
        i_qoh_detail_t.batch_number,
        m_product_groups_t.group_name AS product_group_id,
        m_product_category_t.category_name AS product_category_id,
        m_product_subcategory_t.subcategory_name,
       ( case 
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY' OR c.qoh_source = 'OPENSTOCK') 
limit 1),2)
else
       
        ROUND(
            (
                SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end ) AS totalrate,
        
        ( case 
         when (m_products_t.product_group_id=1 or m_products_t.product_group_id=4)  and m_products_t.product_subcategory_id !=74 and m_products_t.product_subcategory_id !=75 and m_products_t.product_subcategory_id !=76 and m_products_t.product_subcategory_id !=77 then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select m.`Std Cost` from m_products_t as m where m.product_id=i_qoh_detail_t.product_id  order by i_qoh_detail_t.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY' OR c.qoh_source = 'OPENSTOCK') 
limit 1),2)
else
       
        ROUND(
            (
                SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end ) AS total_std_cost,
        
            ( case 
        
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round( (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round( (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY'OR c.qoh_source = 'OPENSTOCK') 
limit 1),2)
else
       
        ROUND(
            (
                 (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end ) AS rate
    FROM
        m_products_t
    LEFT JOIN m_product_groups_t ON
        (
            m_product_groups_t.product_group_id = m_products_t.product_group_id
        )
    LEFT JOIN m_product_category_t ON
        (
            m_product_category_t.product_category_id = m_products_t.product_category_id
        )
    LEFT JOIN m_product_subcategory_t ON
        (
            m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
        )
    LEFT JOIN i_qoh_detail_t ON
        (
            i_qoh_detail_t.product_id = m_products_t.product_id
        )
    LEFT JOIN m_subinventory_t ON
        (
            m_subinventory_t.subinventory_id = i_qoh_detail_t.subinventory_id
        )
    LEFT JOIN m_sublocators_t ON
        (
            m_sublocators_t.sublocator_id = i_qoh_detail_t.locator_id
        )
    WHERE
        1 = 1 and m_products_t.company_id=1 and m_products_t.active!='No' and i_qoh_detail_t.subinventory_id!=0 and i_qoh_detail_t.locator_id!=0 and (i_qoh_detail_t.qualitystatus=1 or i_qoh_detail_t.qualitystatus=0) and DATE(i_qoh_detail_t.created_at) <= ? 
    GROUP BY
        i_qoh_detail_t.product_id,
        i_qoh_detail_t.batch_number,
        i_qoh_detail_t.subinventory_id,
        i_qoh_detail_t.locator_id
        
) v1 where  1 = 1 and v1.qoh > 0";

        $results = \DB::select($SQL, [$start_date]);

        return response()->json(['data' => $results]);


    }
    /*end*/


    public function getproductqohwithaccreport(Request $request)
    {


        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT
        v1.product_id,
        v1.created_at,
        v1.subinventory_id,
        v1.qoh,
        v1.manufacturer_date,
        v1.product_expire_date,
        v1.concatenated_product,
        v1.product_alternate_name,
        v1.concatenated_segments,
        v1.account_code,
        ROUND(IF(v1.std_cost = 0, v1.rate, v1.std_cost),2) AS std_cost,
        v1.locator_name,
        v1.locator_code,
        v1.batch_number,
        v1.product_group_id,
        v1.product_category_id,
        v1.subcategory_name,
        v1.rate,
        ROUND(IF(v1.total_std_cost = 0, v1.qoh * v1.rate, v1.total_std_cost),2) AS total_std_cost,
        v1.totalrate
FROM
    (
    SELECT
        i_qoh_detail_t.product_id,
        i_qoh_detail_t.created_at,
        m_products_t.subinventory_id,
        ROUND(
            SUM(i_qoh_detail_t.qoh_trx_qty),
            2
        ) AS qoh,
        i_qoh_detail_t.manufacturer_date,
        i_qoh_detail_t.product_expire_date,
        m_products_t.concatenated_product,
        f_account_structure_t.concatenated_segments,
        acc_code.concatenated_segments as account_code,
        m_products_t.product_alternate_name,
        m_products_t.`Std Cost` as std_cost,
        m_sublocators_t.locator_name,
        m_sublocators_t.locator_code,
        i_qoh_detail_t.batch_number,
        m_product_groups_t.group_name AS product_group_id,
        m_product_category_t.category_name AS product_category_id,
        m_product_subcategory_t.subcategory_name,
       ( case 
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY') 
limit 1),2)
else
       
        ROUND(
            (
                SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end ) AS totalrate,
        
        ( case 
         when (m_products_t.product_group_id=1 or m_products_t.product_group_id=4)  and m_products_t.product_subcategory_id !=74 and m_products_t.product_subcategory_id !=75 and m_products_t.product_subcategory_id !=76 and m_products_t.product_subcategory_id !=77 then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select m.`Std Cost` from m_products_t as m where m.product_id=i_qoh_detail_t.product_id  order by i_qoh_detail_t.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY') 
limit 1),2)
else
       
        ROUND(
            (
                SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end ) AS total_std_cost,
        
            ( case 
        
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round( (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round( (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY') 
limit 1),2)
else
       
        ROUND(
            (
                 (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end ) AS rate
    FROM
        m_products_t
    LEFT JOIN m_product_groups_t ON
        (
            m_product_groups_t.product_group_id = m_products_t.product_group_id
        )
    LEFT JOIN m_product_category_t ON
        (
            m_product_category_t.product_category_id = m_products_t.product_category_id
        )
    LEFT JOIN m_product_subcategory_t ON
        (
            m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
        )
    LEFT JOIN f_account_structure_t ON(
    m_products_t.control_account_id = f_account_structure_t.f_account_structure_id
        )
    LEFT JOIN f_account_structure_t as acc_code ON(
    m_products_t.account_code_id = acc_code.f_account_structure_id
        )    
    LEFT JOIN i_qoh_detail_t ON
        (
            i_qoh_detail_t.product_id = m_products_t.product_id
        )
    LEFT JOIN m_subinventory_t ON
        (
            m_subinventory_t.subinventory_id = i_qoh_detail_t.subinventory_id
        )
    LEFT JOIN m_sublocators_t ON
        (
            m_sublocators_t.sublocator_id = i_qoh_detail_t.locator_id
        )
    WHERE
        1 = 1 and m_products_t.company_id=1 and i_qoh_detail_t.subinventory_id!=0 and i_qoh_detail_t.locator_id!=0 and (i_qoh_detail_t.qualitystatus=1 or i_qoh_detail_t.qualitystatus=0) and DATE(i_qoh_detail_t.created_at) BETWEEN ? AND ?
    GROUP BY
        i_qoh_detail_t.product_id,
        i_qoh_detail_t.batch_number,
        i_qoh_detail_t.subinventory_id,
        i_qoh_detail_t.locator_id
        
) v1
WHERE 1 = 1 and v1.qoh > 0 ";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return response()->json(['data' => $results]);

    }
    /*end*/


    public function getrmqohreport(Request $request)
    {

        $wh = '';

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
      //  $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
        $compy = \Session::get('companyid');
        $wh1 = "";

        if (isset($start_date)) {

            $wh1.=" and DATE(i_qoh_detail_t.created_at) <= '$start_date'";

        }

        $group = $request->group;
        $loc = $request->locator;
        $ctgy = $request->subcategory;
        $cod = $request->loccode;
        $wipenable = $request->wipenable;


        if ($ctgy == 1) {
            $nonext = "and m_product_subcategory_t.subcategory_name='extract'";
        } elseif ($ctgy == 2) {
            $nonext = "and m_product_subcategory_t.subcategory_name='semi product'";
        } elseif ($ctgy == 0) {
            $nonext = "";
        }
        if ($cod == 1) {
            $lcod = "and m_sublocators_t.locator_code='WIP-1-1'";
        } elseif ($cod == 2) {
            $lcod = "and m_sublocators_t.locator_code='CS-1-1' or m_products_t.active='No'";
        } elseif ($cod == 3) {
            $lcod = "and m_sublocators_t.locator_code != 'VDP-PM-1' and m_sublocators_t.locator_code != 'PM-NO-Stk'";
        } elseif ($cod == 4) {
            $lcod = "and m_sublocators_t.locator_code='VDP-PM-1'";
        } elseif ($cod == 5) {
            $lcod = "and m_sublocators_t.locator_code != 'VDR-RM-1' and  m_sublocators_t.locator_code != 'PM-NO-Stk'";
        } elseif ($cod == 6) {
            $lcod = "and m_sublocators_t.locator_code='VDR-RM-1'";
        } elseif ($cod == 8) {
            $lcod = "and m_sublocators_t.locator_code='1-1-1'";
        } elseif ($cod == 9) {
            $lcod = "and m_sublocators_t.locator_code='WIP-PM-1'";
        } elseif ($cod == 10) {
            $lcod = "and m_sublocators_t.locator_code='R-1-1'";
        } else {
            $lcod = "";
        }



if($wipenable==1){
  $SQL = "select * from (SELECT i_qoh_detail_t.product_id,m_products_t.subinventory_id,ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) / COUNT(i_qoh_detail_t.job_process)) as qoh,m_products_t.product_classification,m_products_t.concatenated_product,m_products_t.product_alternate_name,m_sublocators_t.locator_name,m_sublocators_t.locator_code,i_qoh_detail_t.batch_number,i_qoh_detail_t.manufacturer_date, i_qoh_detail_t.product_expire_date,i_qoh_detail_t.qoh_trx_date as recent_transaction_date,m_product_groups_t.group_name as product_group_id,m_product_category_t.category_name as product_category_id,m_product_subcategory_t.subcategory_name,  ROUND(
            (
                 (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and 		iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) as cost, ROUND(
            (
                SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) as total_cost from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) left join m_product_subcategory_t on(m_product_subcategory_t.product_subcategory_id=m_products_t.product_subcategory_id) left join i_qoh_detail_t on(i_qoh_detail_t.product_id=m_products_t.product_id) LEFT JOIN m_subinventory_t on(m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id) LEFT JOIN m_sublocators_t ON(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) where 1=1 AND m_products_t.active = 'Yes' and m_products_t.company_id=$compy and m_product_groups_t.group_name='$group' and m_sublocators_t.locator_name='$loc' $nonext $lcod and (i_qoh_detail_t.qoh_source = 'job store move' OR i_qoh_detail_t.qoh_source = 'SUBINVENTORY TRANSFER' OR i_qoh_detail_t.qoh_source = 'JOB ISSUE') group by  i_qoh_detail_t.product_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id,i_qoh_detail_t.batch_number) v1 where 1=1 and v1.qoh > 0 $wh ";
} else{
    $SQL = "select * from (SELECT i_qoh_detail_t.product_id,m_products_t.subinventory_id,round(sum(i_qoh_detail_t.qoh_trx_qty),2) as qoh,m_products_t.product_classification,m_products_t.concatenated_product,m_products_t.product_alternate_name,m_sublocators_t.locator_name,m_sublocators_t.locator_code,i_qoh_detail_t.batch_number,i_qoh_detail_t.manufacturer_date, i_qoh_detail_t.product_expire_date,i_qoh_detail_t.qoh_trx_date as recent_transaction_date,m_product_groups_t.group_name as product_group_id,m_product_category_t.category_name as product_category_id,m_product_subcategory_t.subcategory_name,  ( case 
        
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round( (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round( (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY'OR c.qoh_source = 'OPENSTOCK') 
limit 1),2)
else
        ROUND(
            (
                 (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end ) AS cost,
        
       ROUND(
            (
                SUM(i_qoh_detail_t.qoh_trx_qty) *(
                SELECT
                    ( case 
        
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round( (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round( (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY'OR c.qoh_source = 'OPENSTOCK') 
limit 1),2)
else
        ROUND(
            (
                 (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end )
                FROM
                    i_qoh_detail_t AS iqoh
                WHERE
                    iqoh.product_id = i_qoh_detail_t.product_id AND iqoh.batch_number = i_qoh_detail_t.batch_number AND iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id AND iqoh.cost != 0 ORDER BY iqoh.qoh_detail_id DESC
                LIMIT 1
            )
            ),
            2
        ) AS total_cost from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) left join m_product_subcategory_t on(m_product_subcategory_t.product_subcategory_id=m_products_t.product_subcategory_id) left join i_qoh_detail_t on(i_qoh_detail_t.product_id=m_products_t.product_id) LEFT JOIN m_subinventory_t on(m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id) LEFT JOIN m_sublocators_t ON(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) where 1=1 AND m_products_t.active = 'Yes' and m_products_t.company_id=$compy and m_product_groups_t.group_name='$group' and m_sublocators_t.locator_name='$loc' $nonext $lcod and (i_qoh_detail_t.qualitystatus=1 or i_qoh_detail_t.qualitystatus=0) $wh1 group by  i_qoh_detail_t.product_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id,i_qoh_detail_t.batch_number) v1 where 1=1 and v1.qoh > 0 $wh ";
}



        $results = \DB::select($SQL);

        return response()->json(['data' => $results]);

    }



    public function getmaterialagingrpt()
    {

        $wh = '';

        if (isset($_GET['pq_filter'])) {
            $data = json_decode($_GET['pq_filter']);
            $data = $data->data;
            $wh .= $this->pqgridsearchsum('v1', $data);
        }
        $compy = \Session::get('companyid');
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx = '';
        if (!$sidx)
            $sidx = 1;
        $result = "select * from(SELECT m_products_t.product_id as id,m_products_t.concatenated_product,m_products_t.product_code,m_products_t.product_pack_id,m_products_t.min_order_qty,m_product_groups_t.group_name,m_product_groups_t.product_group_id,i_product_packs.pack_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)  left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) left join i_product_packs on(i_product_packs.packing_id=m_products_t.product_pack_id) where 1=1 and m_product_groups_t.group_name='FINISHED GOODS' and m_products_t.company_id=$compy) v1 where 1=1  $wh";
        $result = \DB::select($result);
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

        $org = \Session::get('organization');
        $loc = \Session::get('location');


        $SQL = "select * from(SELECT m_products_t.product_id as id,m_products_t.concatenated_product,m_products_t.product_code,m_products_t.product_pack_id,m_products_t.min_order_qty,m_product_groups_t.group_name,m_product_groups_t.product_group_id,i_product_packs.pack_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)  left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) left join i_product_packs on(i_product_packs.packing_id=m_products_t.product_pack_id) where 1=1 and m_product_groups_t.group_name='FINISHED GOODS' and m_products_t.company_id=$compy) v1 where 1=1  $wh ORDER BY $sidx DESC LIMIT $start , $limit";
        $result = \DB::select($SQL);

        foreach ($result as $key => $val) {
            $qoh_qty = \DB::select("select sum(f.qty-IFNULL(f.qtyy,0)) as qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$val->id'  and i_qoh_detail_t.company_id=$compy GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where i_reservation_detail_t.product_id = '$val->id' and i_reservation_detail_t.company_id=$compy GROUP by product_id)f");



            if (!empty($qoh_qty)) {

                if ($qoh_qty[0]->qty <= 0) {
                    $qoh = 0;
                } else {
                    $qoh = $qoh_qty[0]->qty;
                }
                $result[$key]->qty = $qoh;



            } else {
                $result[$key]->qty = "0";

            }
            //print_r($result[$key]->qty);
        }
        //  dd($result);    

        $responce->rows[] = '';
        $responce->data = $result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
    }

    /*vj purpose:sfg qc yta report*/

    public function sfgqcytaqohrptindex()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        return view('inventoryreport.sfgqcytarpt', $this->data);
    }

    public function getsfgqcytaqohrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';

        $SQL = "SELECT
	m_products_t.product_id,
    m_products_t.concatenated_product,
    m_product_groups_t.group_name,
    m_product_category_t.category_name,
    m_product_subcategory_t.subcategory_name,
    m_products_t.group_classification,
    w_jobcard_hdr_t.job_no,
    w_jobcard_hdr_t.job_status,
    w_jobcard_hdr_t.batch_no,
   	w_qa_submitstage_trx_t.production_qty,
    w_qa_submitstage_trx_t.manufacturer_date,
    w_qa_submitstage_trx_t.product_expire_date,
    m_sublocators_t.locator_code
FROM
    m_products_t
LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id
LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id
LEFT JOIN m_product_subcategory_t ON m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
LEFT JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.product_id = m_products_t.product_id
LEFT JOIN w_qa_submitstage_trx_t ON w_qa_submitstage_trx_t.job_no = w_jobcard_hdr_t.w_jobs_hdr_id
LEFT JOIN m_sublocators_t ON m_sublocators_t.sublocator_id = w_qa_submitstage_trx_t.sublocator_id
WHERE
    m_products_t.product_group_id = 4 AND m_product_subcategory_t.subcategory_name = 'SEMI PRODUCT' AND (w_jobcard_hdr_t.job_status = 'QA SUBMITTED'  OR w_jobcard_hdr_t.job_status = 'REWORK' OR w_jobcard_hdr_t.job_status = 'SCRAP') AND CASE WHEN w_qa_submitstage_trx_t.store_move = 'Yes'  THEN w_qa_submitstage_trx_t.qa_status = 'INITIATED' or w_qa_submitstage_trx_t.qa_status = 'APPROVED' END AND w_qa_submitstage_trx_t.storemove_status = 0";

        $results = \DB::select($SQL);

        return response()->json(['data' => $results]);

    }



    public function getproductnegativeqohreport(Request $request)
    {


        $wh = '';
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $decimal = \Session::get('decimal');

        $wh1 = "";

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';


        if (isset($start_date)) {

            $wh1 .= " and DATE(i_qoh_detail_t.created_at) <= '$start_date'";

        }



        $result = "SELECT
        v1.product_id,
        v1.created_at,
        v1.subinventory_id,
        v1.qoh,
        v1.manufacturer_date,
        v1.product_expire_date,
        v1.concatenated_product,
        v1.product_alternate_name,
        v1.product_classification,
        ROUND(IF(v1.std_cost = 0, v1.rate, v1.std_cost),2) AS std_cost,
        v1.locator_name,
        v1.locator_code,
        v1.batch_number,
        v1.product_group_id,
        v1.product_category_id,
        v1.subcategory_name,
        v1.rate,
        ROUND(IF(v1.total_std_cost = 0, v1.qoh * v1.rate, v1.total_std_cost),2) AS total_std_cost,
        v1.totalrate

FROM
    (
    SELECT
        i_qoh_detail_t.product_id,
        i_qoh_detail_t.created_at,
        m_products_t.subinventory_id,
        ROUND(
            SUM(i_qoh_detail_t.qoh_trx_qty),
            2
        ) AS qoh,
        i_qoh_detail_t.manufacturer_date,
        i_qoh_detail_t.product_expire_date,
        m_products_t.concatenated_product,
        m_products_t.product_alternate_name,
        m_products_t.product_classification,
        m_products_t.`Std Cost` as std_cost,
        m_sublocators_t.locator_name,
        m_sublocators_t.locator_code,
        i_qoh_detail_t.batch_number,
        m_product_groups_t.group_name AS product_group_id,
        m_product_category_t.category_name AS product_category_id,
        m_product_subcategory_t.subcategory_name,
       ( case 
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY' OR c.qoh_source = 'OPENSTOCK') 
limit 1),2)
else
       
        ROUND(
            (
                SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end ) AS totalrate,
        
        ( case 
         when (m_products_t.product_group_id=1 or m_products_t.product_group_id=4)  and m_products_t.product_subcategory_id !=74 and m_products_t.product_subcategory_id !=75 and m_products_t.product_subcategory_id !=76 and m_products_t.product_subcategory_id !=77 then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select m.`Std Cost` from m_products_t as m where m.product_id=i_qoh_detail_t.product_id  order by i_qoh_detail_t.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round(SUM(i_qoh_detail_t.qoh_trx_qty) * (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY' OR c.qoh_source = 'OPENSTOCK') 
limit 1),2)
else
       
        ROUND(
            (
                SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end ) AS total_std_cost,
        
            ( case 
        
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=5  then round( (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and c.qoh_trx_qty >0 order by c.qoh_detail_id desc limit 1),2)
        when m_products_t.product_group_id=1 and i_qoh_detail_t.subinventory_id=3  then round( (select c.cost from i_qoh_detail_t as c where c.product_id=i_qoh_detail_t.product_id and c.batch_number=i_qoh_detail_t.batch_number and c.cost>0 and (c.job_process = 'FINALPROCESS' OR c.qoh_source='SALES RETURN-MOVETOINVENTORY'OR c.qoh_source = 'OPENSTOCK') 
limit 1),2)
else
       
        ROUND(
            (
                 (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and 
        iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1
        ) 
            ),
            2
        ) end ) AS rate
    FROM
        m_products_t
    LEFT JOIN m_product_groups_t ON
        (
            m_product_groups_t.product_group_id = m_products_t.product_group_id
        )
    LEFT JOIN m_product_category_t ON
        (
            m_product_category_t.product_category_id = m_products_t.product_category_id
        )
    LEFT JOIN m_product_subcategory_t ON
        (
            m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
        )
    LEFT JOIN i_qoh_detail_t ON
        (
            i_qoh_detail_t.product_id = m_products_t.product_id
        )
    LEFT JOIN m_subinventory_t ON
        (
            m_subinventory_t.subinventory_id = i_qoh_detail_t.subinventory_id
        )
    LEFT JOIN m_sublocators_t ON
        (
            m_sublocators_t.sublocator_id = i_qoh_detail_t.locator_id
        )
    WHERE
        1 = 1 and m_products_t.company_id=$compy and m_products_t.active!='No' and i_qoh_detail_t.subinventory_id!=0 and i_qoh_detail_t.locator_id!=0 and (i_qoh_detail_t.qualitystatus=1 or i_qoh_detail_t.qualitystatus=0) $wh1
    GROUP BY
        i_qoh_detail_t.product_id,
        i_qoh_detail_t.batch_number,
        i_qoh_detail_t.subinventory_id,
        i_qoh_detail_t.locator_id
        
) v1
WHERE
    1 = 1 and v1.qoh < 0 $wh ";


        $result = \DB::select($result);

        return response()->json(['data' => $result]);


    }

}
