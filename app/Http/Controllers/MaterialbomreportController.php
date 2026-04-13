<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use yajra\datatables\datatables;
use DB;

class MaterialbomreportController extends Controller
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
        if ($this->data['pageMethod'] == "wipdatasrpt") {
            return view('materialbomreport.wipdatasrpt', $this->data);
        } else if ($this->data['pageMethod'] == "packingdatasrpt") {
            return view('materialbomreport.packingdatarpt', $this->data);
        } else if ($this->data['pageMethod'] == "packingcostrpt") {
            return view('materialbomreport.packingcostrpt', $this->data);
        } else if ($this->data['pageMethod'] == "workorderreport") {
            return view('materialbomreport.wotable', $this->data);
        } else if ($this->data['pageMethod'] == "operationreport") {
            return view('materialbomreport.opntable', $this->data);
        } else if ($this->data['pageMethod'] == "operationreportnew") {
            return view('materialbomreport.operationtable', $this->data);
        } else if ($this->data['pageMethod'] == "productionreportnew") {
            return view('materialbomreport.productiontable', $this->data);
        }else if($this->data['pageMethod']=="mnthrpt"){
            return view('materialbomreport.mnthrpttable',$this->data);    
        } else if ($this->data['pageMethod'] == "consolempactreportnew") {
            return view('materialbomreport.consolempacttable', $this->data);
        } else if ($this->data['pageMethod'] == "subinventorytransferreport") {
            return view('materialbomreport.subinvtransferrpttable', $this->data);
        } else {
            return view('materialbomreport.table', $this->data);
        }
    }


    public function workorderreportdata(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (SELECT
    w_workorder_hdr_t.workorder_hdr_id,
    w_workorder_hdr_t.workorder_no,
    w_workorder_hdr_t.workorder_date,
    w_workorder_lines_t.workorder_line_id,
    m_products_t.concatenated_product,
    m_products_t.product_code,
    COALESCE(
        w_productionplan_hdr_t.production_qty,
        0
    ) AS production_qty,
    w_productionplan_hdr_t.plan_qty,
    w_productionplan_hdr_t.pending_qty,
    w_workorder_lines_t.qty,
    w_productionplan_hdr_t.plan_no,
    w_productionplan_hdr_t.start_date,
    w_productionplan_hdr_t.end_date,
    CASE WHEN w_jobcard_hdr_t.job_status IS NULL THEN 'OPEN' WHEN w_jobcard_hdr_t.job_status = 'OPEN' THEN 'JOB RAISED' ELSE w_jobcard_hdr_t.job_status
END AS jobstatus,
COALESCE(
    w_jobcard_hdr_t.job_adjusted_qty,
    0
) AS job_adjusted_qty
FROM
    w_workorder_hdr_t
LEFT JOIN w_workorder_lines_t ON
    (
        w_workorder_lines_t.workorder_hdr_id = w_workorder_hdr_t.workorder_hdr_id
    )
LEFT JOIN w_productionplan_hdr_t ON
    (
        w_productionplan_hdr_t.reference_id = w_workorder_hdr_t.workorder_hdr_id AND w_workorder_lines_t.product_id = w_productionplan_hdr_t.product_id
    )
LEFT JOIN m_products_t ON
    (
        m_products_t.product_id = w_workorder_lines_t.product_id
    )
LEFT JOIN w_jobcard_hdr_t ON
    (
        w_jobcard_hdr_t.reference_source_id = w_productionplan_hdr_t.productionplan_hdr_id AND w_jobcard_hdr_t.product_id = w_productionplan_hdr_t.product_id
    ) WHERE w_workorder_hdr_t.workorder_date BETWEEN ? AND ?
GROUP BY
    w_workorder_lines_t.workorder_line_id) AS v1";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }

    public function getmaterialbomreport(Request $request)
    {

        $SQL = "SELECT * from (
SELECT m_material_bom_hdr_t.material_bom_hdr_id,
                       p1.concatenated_product as assembly_product,
                       p2.concatenated_product as component_product,
                         m_material_bom_hdr_t.total_component_qty,
                        m_material_bom_hdr_t.remarks,
                        m_material_bom_hdr_t.process ,
                        m_material_bom_hdr_t.active ,
                        u1.code_meaning as uom_code,
                        u2.code_meaning as uom_code_remain,
                        m_material_bom_lines_t.component_qty,
                        w_machine_hdr_t.machine_name,
                        m_material_bom_lines_t.process_level,
                        m_material_bom_lines_t.process_name,
                        m_material_bom_lines_t.comments
                        FROM m_material_bom_hdr_t
      
 left join m_material_bom_lines_t on (m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id)
 left join m_uom_codes_t as u1 on(u1.uom_code_id=m_material_bom_hdr_t.uom_code_id)
 left join m_projects_t on(m_material_bom_hdr_t.project_id=m_projects_t.project_id )
 left join w_machine_hdr_t on(w_machine_hdr_t.machine_hdr_id=m_material_bom_lines_t.machine_name)
 
 left join m_products_t as p1 on(p1.product_id=m_material_bom_hdr_t.assembly_product_id)
 left join m_products_t as p2 on(p2.product_id=m_material_bom_lines_t.component_product_id)
 left join m_uom_codes_t as u2 on(u2.uom_code_id =m_material_bom_lines_t.component_uom_code_id)) AS v1";

        $results = \DB::select($SQL);

        return DataTables::of($results)->make(true);
    }


    public function getwipdatasrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (SELECT
    w_jobcard_hdr_t.`job_no`,
    w_jobcard_hdr_t.`job_date`,
    w_jobcard_hdr_t.`job_completion_date`,
    w_jobcard_hdr_t.machine_capacity,
    w_jobcard_hdr_t.job_qty,
    (
        CASE WHEN w_jobcard_hdr_t.job_status = 'QA SUBMITTED' THEN 'COMPLETED' ELSE w_jobcard_hdr_t.job_status
    END
) AS job_status,
w_jobcard_hdr_t.batch_no,
w_jobcard_hdr_t.job_adjusted_qty,
w_jobcard_hdr_t.job_process,
w_jobcard_hdr_t.bom_process,
w_jobcard_hdr_t.kitpack_no,
w_jobcard_hdr_t.hour,
w_productionplan_hdr_t.plan_no,
prd1.concatenated_product AS prdname,
prds.subcategory_name AS subctgy,
m_uom_codes_t.uom_code,
w_qa_submitstage_trx_t.reference_no,
w_qa_submitstage_trx_t.production_qty,
w_qa_submitstage_trx_t.qa_status,
CONCAT(
    prd2.product_code,
    '-',
    prd2.concatenated_product
) AS bomproduct,
CONCAT(
    w_machine_hdr_t.machine_code,
    '-',
    w_machine_hdr_t.machine_name
) AS macname
FROM
    `w_jobcard_hdr_t`
LEFT JOIN m_products_t AS prd1
ON
    (
        prd1.product_id = w_jobcard_hdr_t.product_id
    )
LEFT JOIN m_uom_codes_t ON
    (
        m_uom_codes_t.uom_code_id = w_jobcard_hdr_t.uom_code_id
    )
LEFT JOIN m_product_subcategory_t AS prds
ON
    (
        prds.product_group_id = prd1.product_group_id
    )
LEFT JOIN w_productionplan_hdr_t ON
    (
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    )
LEFT JOIN w_qa_submitstage_trx_t ON
    (
        w_qa_submitstage_trx_t.job_no = w_jobcard_hdr_t.w_jobs_hdr_id
    )
LEFT JOIN w_machine_hdr_t ON
    (
        w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    )
LEFT JOIN m_products_t AS prd2
ON
    (
        prd2.product_id = w_jobcard_hdr_t.bom_product_id
    )
WHERE
    1 = 1 AND prd1.product_group_id = 4 AND w_jobcard_hdr_t.`job_completion_date` >= ? AND w_jobcard_hdr_t.`job_completion_date` <= ? and   w_jobcard_hdr_t.company_id='1' and  w_jobcard_hdr_t.location_id='1') AS v1";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }


    /* purpose: operation report*/
    public function operationreportdata(Request $request)
	{

		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "SELECT * from (select *,round((v1.working_hours-v1.machour),3) as devhrs from(SELECT
t.*,((t.actualhrs/t.range_to) * t.job_adjusted_qty) as machour,(select w_workorder_hdr_t.shift from w_workorder_hdr_t
where w_workorder_hdr_t.workorder_hdr_id=t.plan_reference_id) as shift,(select hr_employee_t.first_name from
hr_employee_t where hr_employee_t.employee_id=t.qa_job_assigned_to) as qa_assigned_name,(select hr_employee_t.first_name
from hr_employee_t where hr_employee_t.employee_id=t.job_assigned_to) as job_assigned_name,(select
i_product_packs.pack_name from i_product_packs where i_product_packs.packing_id=t.pack_id)as pack_name from(select
w_jobcard_hdr_t.job_no,w_jobcard_hdr_t.job_date,w_jobcard_hdr_t.`reference_source_id`,w_jobcard_hdr_t.reference_source,(select
m_products_t.product_code from m_products_t where m_products_t.product_id=w_jobcard_hdr_t.product_id) as
product_code,(select m_products_t.concatenated_product from m_products_t where
m_products_t.product_id=w_jobcard_hdr_t.product_id) as product_name,(select w_productionplan_hdr_t.plan_no from
w_productionplan_hdr_t WHERE w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as
plan_no,(select w_productionplan_hdr_t.plan_date from w_productionplan_hdr_t WHERE
w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as plan_date,(select
w_productionplan_hdr_t.reference_id from w_productionplan_hdr_t WHERE
w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as plan_reference_id,(select
COALESCE(w_productionplan_hdr_t.production_qty,0) AS production_qty from w_productionplan_hdr_t WHERE
w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as production_qty,(select
w_productionplan_hdr_t.plan_qty from w_productionplan_hdr_t WHERE
w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as plan_qty,(select
w_productionplan_hdr_t.plan_qty from w_productionplan_hdr_t WHERE
w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id) as
pending_qty,w_jobcard_hdr_t.product_id,w_jobcard_hdr_t.job_qty,w_jobcard_hdr_t.job_adjusted_qty,w_jobcard_hdr_t.job_completion_date,w_jobcard_hdr_t.batch_no,w_jobcard_hdr_t.job_process,(select
w_machine_equipments_lines_t.range_to from w_machine_equipments_hdr_t left join w_machine_equipments_lines_t
on(w_machine_equipments_lines_t.machine_equipments_hdr_id=w_machine_equipments_hdr_t.machine_equipments_hdr_id) where
w_machine_equipments_hdr_t.machine_id = w_jobcard_hdr_t.machine_hdr_id limit 1) as range_to,(select
w_machine_hdr_t.machine_code from w_machine_hdr_t where w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id)
as machine_code,(select w_machine_hdr_t.machine_name from w_machine_hdr_t where w_machine_hdr_t.machine_hdr_id =
w_jobcard_hdr_t.machine_hdr_id) as machine_name,(select m_products_t.product_pack_id from m_products_t where
m_products_t.product_id=w_jobcard_hdr_t.product_id) as
pack_id,w_jobcard_hdr_t.bom_process,w_qa_submitstage_trx_t.reference_no,w_qa_submitstage_trx_t.production_qty as
job_completed_qty,w_qa_submitstage_trx_t.total_working_hrs,SUBSTRING_INDEX(SUBSTRING_INDEX(w_qa_submitstage_trx_t.job_assigned_to,
',',m_product_category_t.product_category_id),',',-1) AS
qa_job_assigned_to,SUBSTRING_INDEX(SUBSTRING_INDEX(w_qa_submitstage_trx_t.working_hrs,',',m_product_category_t.product_category_id),',',-1)
AS
working_hours,SUBSTRING_INDEX(SUBSTRING_INDEX(w_qa_submitstage_trx_t.actual_hrs,',',m_product_category_t.product_category_id),',',-1)
AS
actualhrs,SUBSTRING_INDEX(SUBSTRING_INDEX(w_qa_submitstage_trx_t.emp_qty,',',m_product_category_t.product_category_id),',',-1)
AS
empqty,SUBSTRING_INDEX(SUBSTRING_INDEX(w_jobcard_hdr_t.job_assigned_to,',',m_product_category_t.product_category_id),',',-1)
AS job_assigned_to FROM `w_jobcard_hdr_t` JOIN w_qa_submitstage_trx_t ON(w_qa_submitstage_trx_t.job_no =
w_jobcard_hdr_t.w_jobs_hdr_id) JOIN m_product_category_t on CHAR_LENGTH(w_qa_submitstage_trx_t.job_assigned_to) -
CHAR_LENGTH(REPLACE(w_qa_submitstage_trx_t.job_assigned_to, ',', '')) >= m_product_category_t.product_category_id -1
WHERE w_jobcard_hdr_t.bom_process != '') as t where 1=1) as v1 where 1=1 AND v1.plan_date BETWEEN ? AND ?) AS vikki";

     $results = \DB::select($SQL, [$start_date,$end_date]);

   return DataTables::of($results)->make(true);
}
	

    public function operationreportdetails()
    {
        $wh = '';
        if (isset($_GET['pq_filter'])) {
            $data = json_decode($_GET['pq_filter']);
            $data = $data->data;
            $wh .= $this->pqgridsearchsum('v1', $data);
        }

        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx = '';
        if (!$sidx)
            $sidx = 1;

        $originalDate = new \DateTime($_GET['from_date']);
        $from = date_format($originalDate, "Y-m-d");

        $originalDate = new \DateTime($_GET['to_date']);
        $to = date_format($originalDate, "Y-m-d");

        /*$wh1=" AND w_productionplan_hdr_t.plan_date BETWEEN '$from' AND '$to' ";*/


        $result = \DB::select("SELECT
    *,
    ROUND(
        (v1.working_hours - v1.machour),
        3
    ) AS devhrs
FROM
    (
    SELECT
        t.*,((t.actualhrs/t.range_to) * t.job_qty) as machour,
        (
        SELECT
            w_workorder_hdr_t.shift
        FROM
            w_workorder_hdr_t
        WHERE
            w_workorder_hdr_t.workorder_hdr_id = t.plan_reference_id
    ) AS shift,
    (
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.qa_job_assigned_to
) AS qa_assigned_name,
(
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
(
    SELECT
        i_product_packs.pack_name
    FROM
        i_product_packs
    WHERE
        i_product_packs.packing_id = t.pack_id
) AS pack_name
FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
        (
        SELECT
            m_products_t.product_code
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS product_code,
    (
    SELECT
        m_products_t.concatenated_product
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS product_name,
(
    SELECT
        w_productionplan_hdr_t.plan_no
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_no,
(
    SELECT
        w_productionplan_hdr_t.plan_date
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_date,
(
    SELECT
        w_productionplan_hdr_t.reference_id
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_reference_id,
(
    SELECT
        COALESCE(
            w_productionplan_hdr_t.production_qty,
            0
        ) AS production_qty
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS production_qty,
(
    SELECT
        w_productionplan_hdr_t.plan_qty
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_qty,
(
    SELECT
        w_productionplan_hdr_t.plan_qty
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS pending_qty,
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
w_jobcard_hdr_t.job_completion_date,
w_jobcard_hdr_t.batch_no,
w_jobcard_hdr_t.job_process,
w_jobcard_process_details_t.machine_time,
(
    SELECT
        w_machine_equipments_lines_t.range_to
    FROM
        w_machine_equipments_hdr_t
    LEFT JOIN w_machine_equipments_lines_t ON
        (
            w_machine_equipments_lines_t.machine_equipments_hdr_id = w_machine_equipments_hdr_t.machine_equipments_hdr_id
        )
    WHERE
        w_machine_equipments_hdr_t.machine_id =w_jobcard_process_details_t.machine_id
    LIMIT 1
) AS range_to,
(
    SELECT
        w_machine_hdr_t.machine_code
    FROM
        w_machine_hdr_t
    WHERE
        w_machine_hdr_t.machine_hdr_id = w_jobcard_process_details_t.machine_id
) AS machine_code,
(
    SELECT
        w_machine_hdr_t.machine_name
    FROM
        w_machine_hdr_t
    WHERE
        w_machine_hdr_t.machine_hdr_id = w_jobcard_process_details_t.machine_id
) AS machine_name,
(
    SELECT
        m_products_t.product_pack_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS pack_id,
w_jobcard_process_details_t.process_level,w_jobcard_process_details_t.process_name,w_jobcard_process_details_t.process_date,

SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        w_jobcard_process_details_t.jobassigned_to,
        ',',
        m_product_category_t.product_category_id
    ),
    ',',
    -1
) AS qa_job_assigned_to,
SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.start_time,
        ',',
        m_product_category_t.product_category_id
    ),
    ',',
    -1
) AS starttime,
SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.end_time,
        ',',
        m_product_category_t.product_category_id
    ),
    ',',
    -1
) AS endtime,
SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        w_jobcard_process_details_t.working_hrs,
        ',',
        m_product_category_t.product_category_id
    ),
    ',',
    -1
) AS working_hours,
SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.actual_hrs,
        ',',
        m_product_category_t.product_category_id
    ),
    ',',
    -1
) AS actualhrs,
SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        w_jobcard_process_details_t.emp_qty,
        ',',
        m_product_category_t.product_category_id
    ),
    ',',
    -1
) AS empqty,
SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.jobassigned_to,
        ',',
        m_product_category_t.product_category_id
    ),
    ',',
    -1
) AS job_assigned_to
FROM
  w_jobcard_process_details_t join      
    `w_jobcard_hdr_t` on(w_jobcard_hdr_t.w_jobs_hdr_id=w_jobcard_process_details_t.job_id)

JOIN m_product_category_t ON CHAR_LENGTH(
       w_jobcard_process_details_t.jobassigned_to
    ) - CHAR_LENGTH(
    REPLACE
        (
            w_jobcard_process_details_t.jobassigned_to,
            ',',
            ''
        )
) >= m_product_category_t.product_category_id -1
WHERE
   1=1
) AS t
WHERE
    1 = 1
) AS v1
WHERE
    1 = 1 AND v1.process_date BETWEEN '$from' AND '$to' $wh");
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


        if (isset($_GET['download'])) {

            $result1 = collect($result)->map(function ($x) {
                return (array) $x; })->toArray();
            return $result1;
        }

        $result = array_slice($result, $start, $limit);

        $responce->rows[] = '';
        $responce->data = $result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
    }

    public function productionreportdetails()
    {
        $wh = '';
        if (isset($_GET['pq_filter'])) {
            $data = json_decode($_GET['pq_filter']);
            $data = $data->data;
            $wh .= $this->pqgridsearchsum('v1', $data);
        }

        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        $sidx = '';
        if (!$sidx)
            $sidx = 1;

        $originalDate = new \DateTime($_GET['from_date']);
        $from = date_format($originalDate, "Y-m-d");

        $originalDate = new \DateTime($_GET['to_date']);
        $to = date_format($originalDate, "Y-m-d");


        $result = \DB::select("SELECT
        *,
        ROUND(
            (v1.working_hours - v1.machour),
            3
        ) AS devhrs
    FROM
        (
        SELECT
            t.*,((t.actualhrs/t.range_to) * t.job_qty) as machour,
            (
            SELECT
                w_workorder_hdr_t.shift
            FROM
                w_workorder_hdr_t
            WHERE
                w_workorder_hdr_t.workorder_hdr_id = t.plan_reference_id
        ) AS shift,
        (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.qa_job_assigned_to
    ) AS qa_assigned_name,
    (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.job_assigned_to
    ) AS job_assigned_name
    
    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            w_jobcard_hdr_t.`reference_source_id`,
            w_jobcard_hdr_t.reference_source,
            (
            SELECT
                m_products_t.product_code
            FROM
                m_products_t
            WHERE
                m_products_t.product_id = w_jobcard_hdr_t.product_id
        ) AS product_code,
        (
        SELECT
            m_products_t.concatenated_product
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS product_name,
    (
        SELECT
            w_productionplan_hdr_t.plan_no
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS plan_no,
    (
        SELECT
            w_productionplan_hdr_t.plan_date
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS plan_date,
    (
        SELECT
            w_productionplan_hdr_t.reference_id
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS plan_reference_id,
    (
        SELECT
            COALESCE(
                w_productionplan_hdr_t.production_qty,
                0
            ) AS production_qty
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS production_qty,
    (
        SELECT
            w_productionplan_hdr_t.plan_qty
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS pending_qty,
    SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        w_qa_submitstage_trx_t.production_qty,
        ',',
        m_product_category_t.product_category_id
    ),
    ',',
    -1
    ) AS emp_qty,
    w_jobcard_hdr_t.product_id,
    w_qa_submitstage_trx_t.jobcard_qty as plan_qty,    
    w_qa_submitstage_trx_t.production_qty as job_qty,
    w_jobcard_hdr_t.job_completion_date,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.job_process,
    
    (
        SELECT
            w_machine_equipments_lines_t.range_to
        FROM
            w_machine_equipments_hdr_t
        LEFT JOIN w_machine_equipments_lines_t ON
            (
                w_machine_equipments_lines_t.machine_equipments_hdr_id = w_machine_equipments_hdr_t.machine_equipments_hdr_id
            )
        WHERE
            w_machine_equipments_hdr_t.machine_id =w_jobcard_hdr_t.machine_hdr_id
        LIMIT 1
    ) AS range_to,
    (
        SELECT
            w_machine_hdr_t.machine_code
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_code,
    (
        SELECT
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,
    
    SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            w_qa_submitstage_trx_t.job_assigned_to,
            ',',
            m_product_category_t.product_category_id
        ),
        ',',
        -1
    ) AS qa_job_assigned_to,
    
    time(SUBSTRING_INDEX(
        SUBSTRING_INDEX(
           w_qa_submitstage_trx_t.start_time,
            ',',
            m_product_category_t.product_category_id
        ),
        ',',
        -1
    )) AS starttime,
    time(SUBSTRING_INDEX(
        SUBSTRING_INDEX(
           w_qa_submitstage_trx_t.end_time,
            ',',
            m_product_category_t.product_category_id
        ),
        ',',
        -1
    )) AS endtime,
    SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            w_qa_submitstage_trx_t.working_hrs,
            ',',
            m_product_category_t.product_category_id
        ),
        ',',
        -1
    ) AS working_hours,

    SUBSTRING_INDEX(
        SUBSTRING_INDEX(
           w_qa_submitstage_trx_t.actual_hrs,
            ',',
            m_product_category_t.product_category_id
        ),
        ',',
        -1
    ) AS actualhrs,
    
    SUBSTRING_INDEX(
        SUBSTRING_INDEX(
           w_qa_submitstage_trx_t.job_assigned_to,
            ',',
            m_product_category_t.product_category_id
        ),
        ',',
        -1
    ) AS job_assigned_to
    FROM
      w_qa_submitstage_trx_t join      
        `w_jobcard_hdr_t` on(w_jobcard_hdr_t.w_jobs_hdr_id=w_qa_submitstage_trx_t.job_no)
    
    JOIN m_product_category_t ON CHAR_LENGTH(
           w_qa_submitstage_trx_t.job_assigned_to
        ) - CHAR_LENGTH(
        REPLACE
            (
                w_qa_submitstage_trx_t.job_assigned_to,
                ',',
                ''
            )
    ) >= m_product_category_t.product_category_id -1
    WHERE
       1=1
    ) AS t
    WHERE
        1 = 1
    ) AS v1
    WHERE
        1 = 1 AND v1.job_date BETWEEN '$from' AND '$to' AND v1.job_no LIKE '%jobpr%' $wh");

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


        if (isset($_GET['download'])) {

            $result1 = collect($result)->map(function ($x) {
                return (array) $x; })->toArray();
            return $result1;
        }

        $result = array_slice($result, $start, $limit);

        $responce->rows[] = '';
        $responce->data = $result;
        $responce->curPage = $page;
        $responce->total = $total_pages;
        $responce->totalRecords = $count;
        echo json_encode($responce);
    }

    public function monthrptdetails(Request $request)
{
    try {
        // Validate & normalize dates
        $fromRaw = $request->input('from') ?? $request->input('from_date');
        $toRaw   = $request->input('to')   ?? $request->input('to_date');

        if (!$fromRaw || !$toRaw) {
            return response()->json(['data' => []]);
        }

        $from = date('Y-m-d', strtotime($fromRaw));
        $to   = date('Y-m-d', strtotime($toRaw));

        // Your original SQL, with :from and :to bindings
        $sql = <<<SQL
SELECT
    main.concatenated_product,
    main.product_classification,
    TRIM(main.main_prod_name) AS product_name,
    TRIM(pack.pack_size) AS pack_size,
    main.job_date,
    main.batch_no,
    main.plnd_qty,
    main.batch_size,

    pack.oprn_plnd_qty,
    pack.filled_qty,

    main.RM_date,
    pack.PM_date,
    main.pur_rem,
    pack.opr_rem,

    main.prdn_schd_date,
    main.prdn_date,
    main.QC_date,

    main.fllng_schd_strt_date,
    main.fllng_schd_end_date,

    pack.fllng_actl_strt_date,
    pack.fllng_actl_end_date,
    
    pack.pckg_actl_strt_date,
    pack.pckg_actl_end_date,

    /* Date differences */
    DATEDIFF(pack.fllng_actl_strt_date, main.QC_date) AS NoOfDay_qc_fllng,
    DATEDIFF(pack.pckg_actl_end_date, pack.fllng_actl_strt_date) AS opn_bdr,
    DATEDIFF(pack.pckg_actl_end_date, main.prdn_date) AS total_bdr

FROM
(
    /* =============================== *
       MAIN PRODUCTION JOB PART
       =============================== */
    SELECT
        j.batch_no,
        j.job_date,
        j.job_qty AS plnd_qty,
        qa.production_qty AS batch_size,
        p.concatenated_product,
        p.product_classification,

        /* Extract main production product name */
        CASE
            WHEN (LENGTH(p.concatenated_product)
                - LENGTH(REPLACE(p.concatenated_product,' ',''))) >= 2
            THEN TRIM(
                LEFT(
                    p.concatenated_product,
                    LENGTH(p.concatenated_product)
                    - LENGTH(SUBSTRING_INDEX(p.concatenated_product,' ',-2)) - 1
                )
            )
            ELSE p.concatenated_product
        END AS main_prod_name,

        DATE(qs.created_at) AS QC_date,

        /* RM Issue date */
        DATE(
            CASE WHEN mi.source='materialissue'
            THEN mi.mtl_issue_date END
        ) AS RM_date,

        
        mi.remarks AS pur_rem,

        wpp.plan_date AS prdn_schd_date,
        j.job_date AS prdn_date,

        /* filling schedule dates */
        wpp.start_date AS fllng_schd_strt_date,
        wpp.end_date AS fllng_schd_end_date

    FROM w_jobcard_hdr_t j
    JOIN m_products_t p ON p.product_id=j.product_id
    JOIN w_qa_submitstage_trx_t qa ON qa.job_no=j.w_jobs_hdr_id
    JOIN i_quality_spec_trx_hdr_t qs ON qs.qa_submitstage_trx_hdr_id=qa.qa_submitstage_trx_hdr_id
    JOIN w_materialissue_hdr_t mi ON mi.w_jobs_hdr_id=j.w_jobs_hdr_id
    JOIN w_productionplan_hdr_t wpp ON wpp.productionplan_hdr_id=j.reference_source_id

    WHERE p.product_group_id=4
      AND qs.created_at > '2025-04-01'
      AND wpp.plan_date BETWEEN ? AND ?
) main


/* ============================================================
   PACKING PART (DEDUPED — FIXED)
   ============================================================ */
JOIN
(
    SELECT
        j.w_jobs_hdr_id AS job_id,
        j.batch_no,
        j.remarks AS opr_rem,
        /* PM Issue date */
        
        CASE WHEN mi.source='packingmaterialissue' THEN DATE(mi.mtl_issue_date) END as PM_date,
        
        jp.concatenated_product AS pack_product_name,
        TRIM(SUBSTRING_INDEX(jp.concatenated_product, ' ', -2)) AS pack_size,
    
        MIN(CASE WHEN proc.process_level='process-1' THEN DATE(proc.process_start_date) END)
            AS fllng_actl_strt_date,
        MAX(CASE WHEN proc.process_level='process-1' THEN DATE(proc.process_end_date) END)
            AS fllng_actl_end_date,

        /* Avoid duplicates by taking max qty */
        sum(CASE WHEN proc.process_level='process-1' THEN qa_p.jobcard_qty END) AS oprn_plnd_qty,
        sum(CASE WHEN proc.process_level='process-1' THEN qa_p.production_qty END) AS filled_qty,

        MIN(CASE WHEN proc.process_level='process-2' THEN DATE(proc.process_start_date) END)
            AS pckg_actl_strt_date,

        MAX(CASE WHEN proc.process_level='finalprocess' THEN DATE(proc.process_end_date) END)
            AS pckg_actl_end_date

    FROM w_jobcard_hdr_t j
    JOIN m_products_t jp ON jp.product_id = j.product_id
    JOIN w_qa_submitstage_trx_t qa_p ON qa_p.job_no = j.w_jobs_hdr_id
    LEFT JOIN w_jobcard_process_details_t proc ON proc.job_id = j.w_jobs_hdr_id
    JOIN w_materialissue_hdr_t mi ON mi.w_jobs_hdr_id=j.w_jobs_hdr_id
    JOIN w_productionplan_hdr_t wpp ON wpp.productionplan_hdr_id=j.reference_source_id
    WHERE jp.product_group_id = 1 AND wpp.plan_date BETWEEN ? AND ?

    /* ❗ The correct grouping (prevents duplicates) */
    GROUP BY  j.batch_no, jp.concatenated_product
) pack
ON pack.batch_no = main.batch_no
AND pack.pack_product_name LIKE CONCAT(main.concatenated_product, '%') 
AND pack.pack_size NOT LIKE '%compression%'

ORDER BY main.batch_no, pack.pack_size
SQL;

        // execute with bindings
        $rows = DB::select($sql, [$from, $to, $from, $to]);

        // Convert stdClass rows to array if DataTables expects plain objects it's fine;
        // we will return as-is in JSON under "data"
        return response()->json(['data' => $rows]);

    } catch (\Exception $e) {
    return response($e->getMessage() . ' LINE: ' . $e->getLine(), 500);
    }

}    
        
	
    public function consolempactreportdetails(Request $request)
    {
        $wh = '';

        $loc = "1";
        $compy = \Session::get('companyid');
        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
		
        $SQL = "SELECT * FROM (SELECT
    *,
        time_format((v1.working_hours - v1.machour),'%H:%i') AS devhrs  
FROM
    (
    SELECT
        t.*, time_format(((t.actualhrs/t.range_to) * t.job_qty),'%H:%i') as machour,
        (
        SELECT
            w_workorder_hdr_t.shift
        FROM
            w_workorder_hdr_t
        WHERE
            w_workorder_hdr_t.workorder_hdr_id = t.plan_reference_id
    ) AS shift,
    (
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.qa_job_assigned_to
) AS qa_assigned_name,
(
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
       
(
    SELECT
        i_product_packs.pack_name
    FROM
        i_product_packs
    WHERE
        i_product_packs.packing_id = t.pack_id
) AS pack_name
FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
        (
        SELECT
            m_products_t.product_code
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS product_code,
    (
    SELECT
        m_products_t.concatenated_product
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS product_name,
(
    SELECT
        w_productionplan_hdr_t.plan_no
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_no,
'MAJOR' AS type,
CONCAT(LEFT(MONTHNAME(date(w_jobcard_process_details_t.process_start_date)),3), '-', year(date(w_jobcard_process_details_t.process_start_date))) as yr_month,   
(
    SELECT
        w_productionplan_hdr_t.plan_date
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_date,
(
    SELECT
        w_productionplan_hdr_t.reference_id
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_reference_id,
(
    SELECT
        COALESCE(
            w_productionplan_hdr_t.production_qty,
            0
        ) AS production_qty
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS production_qty,
(
    SELECT
        w_productionplan_hdr_t.plan_qty
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_qty,
(
    SELECT
        w_productionplan_hdr_t.plan_qty
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS pending_qty,               
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
w_jobcard_hdr_t.job_completion_date,
w_jobcard_hdr_t.batch_no,
w_jobcard_hdr_t.job_process,
w_jobcard_process_details_t.machine_time,
(
    SELECT
        w_machine_equipments_lines_t.range_to
    FROM
        w_machine_equipments_hdr_t
    LEFT JOIN w_machine_equipments_lines_t ON
        (
            w_machine_equipments_lines_t.machine_equipments_hdr_id = w_machine_equipments_hdr_t.machine_equipments_hdr_id
        )
    WHERE
        w_machine_equipments_hdr_t.machine_id =w_jobcard_process_details_t.machine_id
    LIMIT 1
) AS range_to,
(
    SELECT
        w_machine_hdr_t.machine_code
    FROM
        w_machine_hdr_t
    WHERE
        w_machine_hdr_t.machine_hdr_id = w_jobcard_process_details_t.machine_id
) AS machine_code,
(
    SELECT
        w_machine_hdr_t.machine_name
    FROM
        w_machine_hdr_t
    WHERE
        w_machine_hdr_t.machine_hdr_id = w_jobcard_process_details_t.machine_id
) AS machine_name,
(
    SELECT
        m_products_t.product_pack_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS pack_id,
w_jobcard_process_details_t.process_level,w_jobcard_process_details_t.process_name,
date(w_jobcard_process_details_t.process_start_date) as process_date,

SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        w_jobcard_process_details_t.jobassigned_to,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
) AS qa_job_assigned_to,

DATE_FORMAT(
    STR_TO_DATE(
        SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                w_jobcard_process_details_t.start_time,
                ',',
                m_products_t.product_id
            ),
            ',',
            -1
        ),
        '%Y-%m-%d %H:%i:%s'
    ),
'%d-%m-%Y') startdt,

DATE_FORMAT(
    STR_TO_DATE(
        SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                w_jobcard_process_details_t.end_time,
                ',',
                m_products_t.product_id
            ),
            ',',
            -1
        ),
        '%Y-%m-%d %H:%i:%s'
    ),
'%d-%m-%Y') enddt,

time_format(time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.start_time,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
)),'%H:%i') AS starttime,
time_format(time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.end_time,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
)),'%H:%i') AS endtime,
        
time_format(subtime(CASE 
        WHEN       
 time_format(time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.end_time,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
)),'%H') = '00' THEN 
    
        time_format(addtime(time_format(time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.end_time,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
)),'%H:%i'), '24:00'),'%H:%i') ELSE
      time_format(time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.end_time,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
)),'%H:%i') END,
time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.start_time,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
))),'%H:%i') AS working_hours,
        
time_format(time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.actual_hrs,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
)),'%H:%i') AS actualhrs,
SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        w_jobcard_process_details_t.emp_qty,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
) AS empqty,
SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.jobassigned_to,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
) AS job_assigned_to
FROM
  w_jobcard_process_details_t join      
    `w_jobcard_hdr_t` on(w_jobcard_hdr_t.w_jobs_hdr_id=w_jobcard_process_details_t.job_id)

JOIN m_products_t ON CHAR_LENGTH(
       w_jobcard_process_details_t.jobassigned_to
    ) - CHAR_LENGTH(
    REPLACE
        (
            w_jobcard_process_details_t.jobassigned_to,
            ',',
            ''
        )
) >= m_products_t.product_id -1
WHERE
   1=1
) AS t
WHERE
    1 = 1
) AS v1
WHERE
    1 = 1 AND v1.process_date BETWEEN '$start_date' AND '$end_date'

    UNION ALL 
    
   SELECT
   '' AS job_no,
   date(w_jobactivity_lines_t.start_datetime) AS job_date,
   '' AS reference_source_id,
   '' AS reference_source,
   '' AS product_code,
   '' AS product_name,
   'SUBACT' AS plan_no,
   'SUB' AS type,
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   date(w_jobactivity_lines_t.start_datetime) AS plan_date,
   '' AS plan_reference_id,
   '' AS production_qty,
   '' AS plan_qty,
   '' AS pending_qty,
   '' AS product_id,
   '' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS job_process,
   '' AS machine_time,
   '' AS range_to,
   '' AS machine_code,
   'MANUAL' AS machine_name,
   '' AS pack_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
   DATE_FORMAT(
    SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1),
    '%d-%m-%Y'
        ) startdt,

    DATE_FORMAT(
    SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1),
    '%d-%m-%Y'
    ) enddt,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1),'%H:%i') AS starttime,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1),'%H:%i') AS endtime,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.first_name AS qa_assigned_name,
    hr_employee_t.first_name AS job_assigned_name,
    '' AS pack_name,
    '' AS devhrs
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  '$start_date' and '$end_date' 

union all


SELECT
        *,
        ROUND(
            (v1.working_hours - v1.machour),
            3
        ) AS devhrs
    FROM
        (
        SELECT
            t.*,((t.actualhrs/t.range_to) * t.job_qty) as machour,
            (
            SELECT
                w_workorder_hdr_t.shift
            FROM
                w_workorder_hdr_t
            WHERE
                w_workorder_hdr_t.workorder_hdr_id = t.plan_reference_id
        ) AS shift,
        (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.qa_job_assigned_to
    ) AS qa_assigned_name,
    (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.job_assigned_to
    ) AS job_assigned_name,

    '' as pack_name

    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            w_jobcard_hdr_t.`reference_source_id`,
            w_jobcard_hdr_t.reference_source,
            (
            SELECT
                m_products_t.product_code
            FROM
                m_products_t
            WHERE
                m_products_t.product_id = w_jobcard_hdr_t.product_id
        ) AS product_code,
        (
        SELECT
            m_products_t.concatenated_product
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS product_name,
    (
        SELECT
            w_productionplan_hdr_t.plan_no
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS plan_no,
    'MAJOR' as type,
    CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,

    (
        SELECT
            w_productionplan_hdr_t.plan_date
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS plan_date,
    (
        SELECT
            w_productionplan_hdr_t.reference_id
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS plan_reference_id,
    (
        SELECT
            COALESCE(
                w_productionplan_hdr_t.production_qty,
                0
            ) AS production_qty
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS production_qty,
    
    w_qa_submitstage_trx_t.jobcard_qty as plan_qty,

    (
        SELECT
            w_productionplan_hdr_t.plan_qty
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS pending_qty,

    
    w_jobcard_hdr_t.product_id,
        
    w_qa_submitstage_trx_t.production_qty as job_qty,
    w_jobcard_hdr_t.job_completion_date,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.job_process,
    '' as machine_time,
    (
        SELECT
            w_machine_equipments_lines_t.range_to
        FROM
            w_machine_equipments_hdr_t
        LEFT JOIN w_machine_equipments_lines_t ON
            (
                w_machine_equipments_lines_t.machine_equipments_hdr_id = w_machine_equipments_hdr_t.machine_equipments_hdr_id
            )
        WHERE
            w_machine_equipments_hdr_t.machine_id =w_jobcard_hdr_t.machine_hdr_id
        LIMIT 1
    ) AS range_to,
    (
        SELECT
            w_machine_hdr_t.machine_code
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_code,
    (
        SELECT
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,

    '' as pack_id,
    'PRODUCTION' as process_level,
    'PRODUCTION' as process_name,
    w_jobcard_hdr_t.job_date as process_date,
    
    SUBSTRING_INDEX(
        SUBSTRING_INDEX(
            w_qa_submitstage_trx_t.job_assigned_to,
            ',',
            m_products_t.product_id
        ),
        ',',
        -1
    ) AS qa_job_assigned_to,
    DATE_FORMAT(
    STR_TO_DATE(
        SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                w_qa_submitstage_trx_t.start_time,
                ',',
                m_products_t.product_id
            ),
            ',',
            -1
        ),
        '%Y-%m-%d %H:%i:%s'
    ),
'%d-%m-%Y') startdt,
    DATE_FORMAT(
    STR_TO_DATE(
        SUBSTRING_INDEX(
            SUBSTRING_INDEX(
                w_qa_submitstage_trx_t.end_time,
                ',',
                m_products_t.product_id
            ),
            ',',
            -1
        ),
        '%Y-%m-%d %H:%i:%s'
    ),
'%d-%m-%Y') enddt,
    time_format(time(SUBSTRING_INDEX(
        SUBSTRING_INDEX(
           w_qa_submitstage_trx_t.start_time,
            ',',
            m_products_t.product_id
        ),
        ',',
        -1
    )), '%H:%i') AS starttime,

    time_format(time(SUBSTRING_INDEX(
        SUBSTRING_INDEX(
           w_qa_submitstage_trx_t.end_time,
            ',',
            m_products_t.product_id
        ),
        ',',
        -1
    )),'%H:%i') AS endtime,

    time_format(subtime(time(SUBSTRING_INDEX(
        SUBSTRING_INDEX(
           w_qa_submitstage_trx_t.end_time,
            ',',
            m_products_t.product_id
        ),
        ',',
        -1
    )),
    time(SUBSTRING_INDEX(
        SUBSTRING_INDEX(
           w_qa_submitstage_trx_t.start_time,
            ',',
            m_products_t.product_id
        ),
        ',',
        -1
    ))),'%H:%i') AS working_hours,

    time_format(SUBSTRING_INDEX(
        SUBSTRING_INDEX(
           w_qa_submitstage_trx_t.actual_hrs,
            ',',
            m_products_t.product_id
        ),
        ',',
        -1
    ),'%H:%i') AS actualhrs,

    SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        w_qa_submitstage_trx_t.production_qty,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
    ) AS empqty,
    
    SUBSTRING_INDEX(
        SUBSTRING_INDEX(
           w_qa_submitstage_trx_t.job_assigned_to,
            ',',
            m_products_t.product_id
        ),
        ',',
        -1
    ) AS job_assigned_to
    FROM
      w_qa_submitstage_trx_t join      
        `w_jobcard_hdr_t` on(w_jobcard_hdr_t.w_jobs_hdr_id=w_qa_submitstage_trx_t.job_no)
    
    JOIN m_products_t ON CHAR_LENGTH(
           w_qa_submitstage_trx_t.job_assigned_to
        ) - CHAR_LENGTH(
        REPLACE
            (
                w_qa_submitstage_trx_t.job_assigned_to,
                ',',
                ''
            )
    ) >= m_products_t.product_id -1
    WHERE
       1=1
    ) AS t
    WHERE
        1 = 1
    ) AS v1
    WHERE
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%')AS vikki HAVING vikki.plan_date !='0000-00-00'";

        $result = \DB::select($SQL);

		 return DataTables::of($result)->make(true);
    }


    public function getpackingdatasrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (SELECT
    w_jobcard_hdr_t.`job_no`,
    w_jobcard_hdr_t.`job_date`,
    w_jobcard_hdr_t.`job_completion_date`,
    w_jobcard_hdr_t.machine_capacity,
    w_jobcard_hdr_t.job_qty,
    (
        CASE WHEN w_jobcard_hdr_t.job_status = 'QA SUBMITTED' THEN 'COMPLETED' ELSE w_jobcard_hdr_t.job_status
    END
) AS job_status,
w_jobcard_hdr_t.batch_no,
w_jobcard_hdr_t.job_adjusted_qty,
w_jobcard_hdr_t.job_process,
w_jobcard_hdr_t.bom_process,
w_jobcard_hdr_t.kitpack_no,
w_jobcard_hdr_t.hour,
w_productionplan_hdr_t.plan_no,
CONCAT(
    prd1.product_code,
    '-',
    prd1.concatenated_product
) AS prdname,
m_uom_codes_t.uom_code,
w_qa_submitstage_trx_t.reference_no,
w_qa_submitstage_trx_t.production_qty,
w_qa_submitstage_trx_t.qa_status,
CONCAT(
    prd2.product_code,
    '-',
    prd2.concatenated_product
) AS bomproduct,
CONCAT(
    w_machine_hdr_t.machine_code,
    '-',
    w_machine_hdr_t.machine_name
) AS macname
FROM
    `w_jobcard_hdr_t`
LEFT JOIN m_products_t AS prd1
ON
    (
        prd1.product_id = w_jobcard_hdr_t.product_id
    )
LEFT JOIN m_uom_codes_t ON
    (
        m_uom_codes_t.uom_code_id = w_jobcard_hdr_t.uom_code_id
    )
LEFT JOIN w_productionplan_hdr_t ON
    (
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    )
LEFT JOIN w_qa_submitstage_trx_t ON
    (
        w_qa_submitstage_trx_t.job_no = w_jobcard_hdr_t.w_jobs_hdr_id
    )
LEFT JOIN w_machine_hdr_t ON
    (
        w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    )
LEFT JOIN m_products_t AS prd2
ON
    (
        prd2.product_id = w_jobcard_hdr_t.bom_product_id
    )
WHERE
    1 = 1 AND prd1.product_group_id = 1 AND w_jobcard_hdr_t.company_id = '1' AND w_jobcard_hdr_t.location_id = '1' AND w_jobcard_hdr_t.job_completion_date BETWEEN ? AND ?) AS v1";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }



    public function getpackingcostrpt(Request $request)
    {

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (SELECT
    i_qoh_detail_t.`qoh_detail_id`,
    w_jobcard_hdr_t.job_no,
    w_jobcard_hdr_t.job_date,
    w_jobcard_hdr_t.job_completion_date,
    w_jobcard_hdr_t.job_qty,
    w_jobcard_hdr_t.job_adjusted_qty,
    (
        CASE WHEN w_jobcard_hdr_t.job_status = 'QA SUBMITTED' THEN 'COMPLETED' ELSE w_jobcard_hdr_t.job_status
    END
) AS job_status,
w_jobcard_hdr_t.job_process,
w_jobcard_hdr_t.bom_process,
w_jobcard_hdr_t.hour,
w_jobcard_hdr_t.batch_no,
i_qoh_detail_t.cost,
CONCAT(
    m_products_t.product_code,
    '-',
    m_products_t.concatenated_product
) AS prd,
i_qoh_detail_t.qoh_trx_qty,
w_productionplan_hdr_t.plan_no,
m_uom_codes_t.uom_code
FROM
    `i_qoh_detail_t`
LEFT JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id = i_qoh_detail_t.job_id
LEFT JOIN m_products_t ON m_products_t.product_id = i_qoh_detail_t.product_id
LEFT JOIN m_uom_codes_t ON
    (
        m_uom_codes_t.uom_code_id = w_jobcard_hdr_t.uom_code_id
    )
LEFT JOIN w_productionplan_hdr_t ON w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
WHERE
    1 = 1 AND i_qoh_detail_t.`job_id` != 0 AND w_jobcard_hdr_t.job_date BETWEEN ? AND ?
GROUP BY
    i_qoh_detail_t.job_id
ORDER BY
    `w_jobcard_hdr_t`.`job_no` ASC) AS v1";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return DataTables::of($results)->make(true);
    }

    /* purpose:Subinventory report*/
    public function subinventorytransferreportdata(Request $request)
    {
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT
        * 
    FROM
        (SELECT
            i_subinventory_transfer_hdr_t.subtransfer_no,
            i_subinventory_transfer_hdr_t.trx_date,
            tb_users.username,
            (SELECT
                m_products_t.product_code 
            FROM
                m_products_t 
            WHERE
                m_products_t.product_id = i_subinventory_transfer_lines_t.product_id LIMIT 1) AS product_code,
            (SELECT
                m_products_t.concatenated_product 
            FROM
                m_products_t 
            WHERE
                m_products_t.product_id = i_subinventory_transfer_lines_t.product_id LIMIT 1) AS concatenated_product,
            (SELECT
                m_product_groups_t.group_name 
            FROM
                m_product_groups_t 
            WHERE
                i_subinventory_transfer_lines_t.product_group_id = m_product_groups_t.product_group_id LIMIT 1) AS group_name,
            i_subinventory_transfer_hdr_t.to_subinv_id,
            i_subinventory_transfer_lines_t.subinventory_transfer_line_id,
            i_subinventory_transfer_lines_t.active,
            i_subinventory_transfer_lines_t.batch_no,
            i_subinventory_transfer_lines_t.from_transfer_qty,
            i_subinventory_transfer_lines_t.to_transfer_qty,
            i_subinventory_transfer_lines_t.qoh,
            i_subinventory_transfer_lines_t.manufacture_date,
            i_subinventory_transfer_lines_t.product_expire_date,
            i_subinventory_transfer_lines_t.receive_qty,
            i_subinventory_transfer_lines_t.remarks,
            (SELECT
                m_subinventory_t.subinventory_name 
            FROM
                m_subinventory_t 
            WHERE
                m_subinventory_t.subinventory_id = i_subinventory_transfer_hdr_t.frm_subinv_id LIMIT 1) AS from_subinventory,
            (SELECT
                m_subinventory_t.subinventory_name 
            FROM
                m_subinventory_t 
            WHERE
                m_subinventory_t.subinventory_id = i_subinventory_transfer_hdr_t.to_subinv_id LIMIT 1) AS to_subinventory,
            (SELECT
                m_sublocators_t.locator_code 
            FROM
                m_sublocators_t 
            WHERE
                m_sublocators_t.sublocator_id = i_subinventory_transfer_lines_t.from_loc LIMIT 1) from_sublocator,
            (SELECT
                m_sublocators_t.locator_code 
            FROM
                m_sublocators_t 
            WHERE
                m_sublocators_t.sublocator_id = i_subinventory_transfer_lines_t.to_loc LIMIT 1) AS to_locator,
            (SELECT
                m_material_trx_t.trx_date 
            FROM
                m_material_trx_t 
            WHERE
                m_material_trx_t.trx_source_hdr_id = i_subinventory_transfer_hdr_t.subinventory_transfer_hdr_id 
                AND m_material_trx_t.trx_reference = 'SUBINVENTORY TRANSFER' LIMIT 1) AS receive_date 
        FROM
            i_subinventory_transfer_lines_t 
        LEFT JOIN
            i_subinventory_transfer_hdr_t 
                ON i_subinventory_transfer_hdr_t.subinventory_transfer_hdr_id = i_subinventory_transfer_lines_t.subinventory_transfer_hdr_id 
        LEFT JOIN
            `tb_users` 
                ON (
                    tb_users.id = i_subinventory_transfer_lines_t.created_by
                ) 
        WHERE
            (
                1 = 1
            ) 
            AND (
                i_subinventory_transfer_hdr_t.trx_date BETWEEN ? AND ?
            ) 
        GROUP BY
            i_subinventory_transfer_lines_t.subinventory_transfer_line_id 
        ORDER BY
            NULL) AS v1 ";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
		
    }

}
