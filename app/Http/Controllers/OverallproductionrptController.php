<?php

namespace App\Http\Controllers;
use session;    
use Illuminate\Http\Request;

class OverallproductionrptController extends Controller
    {

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    
    public function Overallrpt(Request $request)
    {

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $machine_name = $request->input('machine_name');
        
        $this->data['machine_name'] = $this->jcustomselecttool('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', ''," and `machine_code` LIKE '%/EQ/%'");
    
    if($machine_name !=''){
       $this->data['machine'] = \DB::select("select machine_name from w_machine_hdr_t where machine_hdr_id='$machine_name'");
       $machine = $this->data['machine'][0]->machine_name;
    }else{
          $this->data['machine'] = [];
            $this->data['machine'][0] = (object) ['machine_name' => 'No Machine Selected'];
            $machine='';
    }


// extra hours cal
if (!empty($start_date)) {
    
     $this->data['extra_hrs_cal'] = \DB::select("select emp.qa_assigned_name,emp.month_yr,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs,
     CASE WHEN SEC_TO_TIME(720000) > SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) THEN
    TIMEDIFF(SEC_TO_TIME(720000), SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime))))) ELSE '00:00:00' END AS idle_hrs,
    TIMEDIFF(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))), '200:00:00') AS extra_hrs from (SELECT
        *
    FROM
        (
        SELECT
            t.*,ROUND(((t.actualhrs/t.range_to) * t.job_qty),2) as machour,
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
            hr_employee_t.group_type
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.job_assigned_to
    ) AS job_assigned_name,
    (
    SELECT
        i_product_packs.pack_value
    FROM
        i_product_packs
    WHERE
        i_product_packs.packing_id = t.pack_id
) AS pack_name,
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.type_id
) AS prd_type,

(
    SELECT
        m_product_variants_t.product_variant_name
    FROM
        m_product_variants_t
    WHERE
        m_product_variants_t.product_variant_id = t.varient_id
) AS varient_name
    
    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as month_yr,
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
            m_products_t.product_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS p_product_id,
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
            m_products_t.product_variant_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS varient_id,


             (
        SELECT
            m_products_t.product_type_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS type_id,

                   (
        SELECT
            m_products_t.product_pack_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS pack_id,

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
            ROUND(COALESCE(
                w_productionplan_hdr_t.production_qty,
                0 , 2)
            ) AS production_qty
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS production_qty,
    (
        SELECT
            ROUND(w_productionplan_hdr_t.plan_qty, 2)
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
    w_qa_submitstage_trx_t.job_date as batch_sdate,
    w_qa_submitstage_trx_t.qatrx_date as batch_edate,
    w_qa_submitstage_trx_t.remarks as remark,
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
            w_machine_equipments_lines_t.hours
        FROM
            w_machine_equipments_hdr_t
        LEFT JOIN w_machine_equipments_lines_t ON
            (
                w_machine_equipments_lines_t.machine_equipments_hdr_id = w_machine_equipments_hdr_t.machine_equipments_hdr_id
            )
        WHERE
            w_machine_equipments_hdr_t.machine_id =w_jobcard_hdr_t.machine_hdr_id
        LIMIT 1
    ) AS std_hrs,
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
    ) AS v1) emp    WHERE
        1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' AND  emp.job_assigned_name ='8'
         GROUP BY emp.qa_assigned_name,emp.month_yr ORDER BY SUBSTRING(month_yr, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', month_yr), '%d-%b-%y') ASC");

    
     $this->data['machine_summary'] = \DB::select("select emp.qa_assigned_name,emp.month_yr,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs from (SELECT
        *
    FROM
        (
        SELECT
            t.*,ROUND(((t.actualhrs/t.range_to) * t.job_qty),2) as machour,
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
            hr_employee_t.group_type
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.job_assigned_to
    ) AS job_assigned_name,
    (
    SELECT
        i_product_packs.pack_value
    FROM
        i_product_packs
    WHERE
        i_product_packs.packing_id = t.pack_id
) AS pack_name,
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.type_id
) AS prd_type,

(
    SELECT
        m_product_variants_t.product_variant_name
    FROM
        m_product_variants_t
    WHERE
        m_product_variants_t.product_variant_id = t.varient_id
) AS varient_name
    
    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as month_yr,
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
            m_products_t.product_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS p_product_id,
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
            m_products_t.product_variant_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS varient_id,


             (
        SELECT
            m_products_t.product_type_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS type_id,

                   (
        SELECT
            m_products_t.product_pack_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS pack_id,

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
            ROUND(COALESCE(
                w_productionplan_hdr_t.production_qty,
                0 , 2)
            ) AS production_qty
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS production_qty,
    (
        SELECT
            ROUND(w_productionplan_hdr_t.plan_qty, 2)
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
    w_qa_submitstage_trx_t.job_date as batch_sdate,
    w_qa_submitstage_trx_t.qatrx_date as batch_edate,
    w_qa_submitstage_trx_t.remarks as remark,
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
            w_machine_equipments_lines_t.hours
        FROM
            w_machine_equipments_hdr_t
        LEFT JOIN w_machine_equipments_lines_t ON
            (
                w_machine_equipments_lines_t.machine_equipments_hdr_id = w_machine_equipments_hdr_t.machine_equipments_hdr_id
            )
        WHERE
            w_machine_equipments_hdr_t.machine_id =w_jobcard_hdr_t.machine_hdr_id
        LIMIT 1
    ) AS std_hrs,
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
    ) AS v1) emp    WHERE
        1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' AND emp.machine_name='$machine' AND emp.job_assigned_name ='8'
         GROUP BY emp.qa_assigned_name,emp.month_yr ORDER BY SUBSTRING(month_yr, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', month_yr), '%d-%b-%y') ASC");

    }
    
    return view('productiondashboard.overallproductionrpt', $this->data);


    }
    
       // employee date wise hrs popup rpt
        
       public function employeehrspopup($employeeName,Request $request){
           
      
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
           
            $daily_summary = \DB::select("select date(emp.job_date) as date,emp.month_yr, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs,CASE WHEN SEC_TO_TIME(28800) > SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) THEN
    TIMEDIFF(SEC_TO_TIME(28800), SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime))))) ELSE '00:00:00' END AS idle_hrs,
    TIMEDIFF(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))), '08:00:00') AS extra_hrs  from (SELECT *
    FROM
        (
        SELECT
            t.*,ROUND(((t.actualhrs/t.range_to) * t.job_qty),2) as machour,
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
            hr_employee_t.group_type
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.job_assigned_to
    ) AS job_assigned_name,
    (
    SELECT
        i_product_packs.pack_value
    FROM
        i_product_packs
    WHERE
        i_product_packs.packing_id = t.pack_id
) AS pack_name,
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.type_id
) AS prd_type,

(
    SELECT
        m_product_variants_t.product_variant_name
    FROM
        m_product_variants_t
    WHERE
        m_product_variants_t.product_variant_id = t.varient_id
) AS varient_name
    
    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as month_yr,
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
            m_products_t.product_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS p_product_id,
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
            m_products_t.product_variant_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS varient_id,


             (
        SELECT
            m_products_t.product_type_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS type_id,

                   (
        SELECT
            m_products_t.product_pack_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS pack_id,

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
            ROUND(COALESCE(
                w_productionplan_hdr_t.production_qty,
                0 , 2)
            ) AS production_qty
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS production_qty,
    (
        SELECT
            ROUND(w_productionplan_hdr_t.plan_qty, 2)
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
    w_qa_submitstage_trx_t.job_date as batch_sdate,
    w_qa_submitstage_trx_t.qatrx_date as batch_edate,
    w_qa_submitstage_trx_t.remarks as remark,
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
            w_machine_equipments_lines_t.hours
        FROM
            w_machine_equipments_hdr_t
        LEFT JOIN w_machine_equipments_lines_t ON
            (
                w_machine_equipments_lines_t.machine_equipments_hdr_id = w_machine_equipments_hdr_t.machine_equipments_hdr_id
            )
        WHERE
            w_machine_equipments_hdr_t.machine_id =w_jobcard_hdr_t.machine_hdr_id
        LIMIT 1
    ) AS std_hrs,
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
            w_machine_hdr_t.machine_hdr_id
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
    ) AS v1) emp    WHERE
        1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' AND  emp.qa_assigned_name ='$employeeName'
         GROUP BY emp.qa_assigned_name,emp.job_date order by emp.job_date ASC");

//dd($daily_summary);
$htmlTable = '<table id="machineSummary" class="display dataTable" style="width:100%; font-family: \'Saira Semi Condensed\', sans-serif;">';

// Store month-year as keys to avoid repetition
$uniqueMonthYears = [];

// Populate table rows with unique month-year values
foreach ($daily_summary as $value) {
    $monthYear = $value->month_yr;
    if (!isset($uniqueMonthYears[$monthYear])) {
        $uniqueMonthYears[$monthYear] = true;
        $monthlyTotalWorkHrsSeconds = 0;
         $monthlyTotalidleHrsSeconds = 0;
          $monthlyTotalextraHrsSeconds = 0;
          
        $htmlTable .= '<tr><td><button class="btn btn-success accordion-toggle" style="text-align: center; cursor: pointer;">' . $monthYear . '</button></td></tr>';
        // Additional row for details, initially hidden
        $htmlTable .= '<tr class="accordion-details" data-month-year="' . $monthYear . '" style="display: none;"><td colspan="1">';
        $htmlTable .= '<table id="popuptbl_' . $monthYear . '" class="display dataTable" style="width:100%; display:none;"><thead><tr class="sticky-row"><th style="text-align: center;background: #b3d1ff">Date</th><th style="text-align: center;background: #b3d1ff">Work Hrs</th><th style="text-align: center;background: #b3d1ff">Qty</th><th style="text-align: center;background: #b3d1ff">Idle Hrs</th><th style="text-align: center;background: #b3d1ff">Extra Hrs</th></tr></thead><tbody>';
      
        foreach ($daily_summary as $key => $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr class="accordion-toggle"><td style="text-align: center; cursor: pointer;">' . $detail->date . '</td><td style="text-align: center;">' . ($detail->wrk_hrs) . '</td><td style="text-align: center;">' . ($detail->job_qty) . '</td><td style="text-align: center;">' . ( $detail->idle_hours) . '</td><td style="text-align: center;">' . ($detail->extra_hrs) . '</td></tr>';
                // Splitting hours, minutes, and seconds and converting to seconds
                list($hours, $minutes, $seconds) = explode(':', $detail->wrk_hrs ?? '0');
                $monthlyTotalWorkHrsSeconds += $hours * 3600 + $minutes * 60 + $seconds;
                
                                // Splitting hours, minutes, and seconds and converting to seconds
                list($hours, $minutes, $seconds) = explode(':', $detail->idle_hours ?? '0');
                $monthlyTotalidleHrsSeconds += $hours * 3600 + $minutes * 60 + $seconds;
                
                
                                // Splitting hours, minutes, and seconds and converting to seconds
                list($hours, $minutes, $seconds) = explode(':', $detail->extra_hrs ?? '0');
                $monthlyTotalextraHrsSeconds += $hours * 3600 + $minutes * 60 + $seconds;
            }
        }

        // Formatting seconds into HH:MM:SS
        $hours = floor($monthlyTotalWorkHrsSeconds / 3600);
        $minutes = floor(($monthlyTotalWorkHrsSeconds % 3600) / 60);
        $seconds = $monthlyTotalWorkHrsSeconds % 60;
        $monthlyTotalWorkHrs = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $hours = floor($monthlyTotalidleHrsSeconds / 3600);
        $minutes = floor(($monthlyTotalidleHrsSeconds % 3600) / 60);
        $seconds = $monthlyTotalidleHrsSeconds % 60;
        $monthlyTotalidleHrs = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        
        $hours = floor($monthlyTotalextraHrsSeconds / 3600);
        $minutes = floor(($monthlyTotalextraHrsSeconds % 3600) / 60);
        $seconds = $monthlyTotalextraHrsSeconds % 60;
        $monthlyTotalextraHrs = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        
        // Display monthly totals
        $htmlTable .= '<div class="row">';
        $htmlTable .= '<div class="col-md-4">';
        $htmlTable .= '<h5 style="text-align: center;font-weight: 800;"><span>Total Work Hrs: ' . $monthlyTotalWorkHrs . '</span></h5>';
        $htmlTable .= '</div>';
        $htmlTable .= '<div class="col-md-4">';
        $htmlTable .= '<h5 style="text-align: center;font-weight: 800;">Total Idle Hrs: ' . $monthlyTotalidleHrs . '</h5>';
        $htmlTable .= '</div>';
        $htmlTable .= '<div class="col-md-4">';
        $htmlTable .= '<h5 style="text-align: center;font-weight: 800;">Total Extra Hrs: ' . $monthlyTotalextraHrs . '</h5>';
        $htmlTable .= '</div>';
        $htmlTable .= '</div>';
        $htmlTable .= '</tbody></table>';
        $htmlTable .= '</td></tr>';
    }
 }

$htmlTable .= '</table>';

// JavaScript to manipulate the DOM
$htmlTable .= '<script>
$(document).ready(function(){
    $(".accordion-toggle").click(function(){
        var monthYear = $(this).closest("tr").next(".accordion-details").data("month-year");
        var detailsRow = $(this).closest("tr").next(".accordion-details");
        if (!detailsRow.hasClass("loaded")) {
            detailsRow.addClass("loaded");
            // Simulate AJAX call for demo purposes, replace with actual AJAX call
            setTimeout(function(){
                // Inject HTML content
                detailsRow.find("table").show();
            }, 0); 
        }
        detailsRow.toggle(); 
    });
});
</script>';


    return $htmlTable;

    }
    

//emp product wise popup


public function Emppropopup($employee,Request $request){
           
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $machine_id = $request->input('machine');

       
        $daily_summary = \DB::select("select emp.product_name,emp.emp_qty,date(emp.job_date) as date,CONCAT(LEFT(MONTHNAME(date(emp.job_date)),3), '-', year(date(emp.job_date))) as month_yr,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS wrk_hrs  from (SELECT
        *
    FROM
        (
        SELECT
            t.*,ROUND(((t.actualhrs/t.range_to) * t.job_qty),2) as machour,
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
            hr_employee_t.group_type
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.job_assigned_to
    ) AS job_assigned_name,
    (
    SELECT
        i_product_packs.pack_value
    FROM
        i_product_packs
    WHERE
        i_product_packs.packing_id = t.pack_id
) AS pack_name,
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.type_id
) AS prd_type,

(
    SELECT
        m_product_variants_t.product_variant_name
    FROM
        m_product_variants_t
    WHERE
        m_product_variants_t.product_variant_id = t.varient_id
) AS varient_name
    
    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as month_yr,
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
            m_products_t.product_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS p_product_id,
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
            m_products_t.product_variant_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS varient_id,


             (
        SELECT
            m_products_t.product_type_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS type_id,

                   (
        SELECT
            m_products_t.product_pack_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS pack_id,

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
            ROUND(COALESCE(
                w_productionplan_hdr_t.production_qty,
                0 , 2)
            ) AS production_qty
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS production_qty,
    (
        SELECT
            ROUND(w_productionplan_hdr_t.plan_qty, 2)
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
    w_qa_submitstage_trx_t.job_date as batch_sdate,
    w_qa_submitstage_trx_t.qatrx_date as batch_edate,
    w_qa_submitstage_trx_t.remarks as remark,
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
            w_machine_equipments_lines_t.hours
        FROM
            w_machine_equipments_hdr_t
        LEFT JOIN w_machine_equipments_lines_t ON
            (
                w_machine_equipments_lines_t.machine_equipments_hdr_id = w_machine_equipments_hdr_t.machine_equipments_hdr_id
            )
        WHERE
            w_machine_equipments_hdr_t.machine_id =w_jobcard_hdr_t.machine_hdr_id
        LIMIT 1
    ) AS std_hrs,
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
            w_machine_hdr_t.machine_hdr_id
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
    ) AS v1) emp    WHERE
        1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' AND  emp.machine_name ='$machine_id' AND emp.qa_assigned_name='$employee'
         GROUP BY emp.product_name,emp.qa_assigned_name,emp.job_date order by emp.job_date ASC");


 	$htmlTable = '<table id="machineSummary" class="table table-bordered table-sm align-middle" style="width:100%; font-family: \'Saira Semi Condensed\', sans-serif;">';

$uniqueMonthYears = [];

foreach ($daily_summary as $value) {
    $monthYear = $value->month_yr;

    if (!isset($uniqueMonthYears[$monthYear])) {
        $uniqueMonthYears[$monthYear] = true;
        $monthlyTotalWorkHrsSeconds = 0;
        $monthlyTotalidleHrsSeconds = 0;
        $monthlyTotalextraHrsSeconds = 0;

        // Main row: Toggle Button
        $htmlTable .= '<tr><td>';
        $htmlTable .= '<button class="btn btn-outline-warning btn-sm  text-start accordion-toggle" data-bs-toggle="collapse" data-bs-target="#collapse_' . $monthYear . '" aria-expanded="false" aria-controls="collapse_' . $monthYear . '">';
        $htmlTable .= '<strong>' . $monthYear . '</strong>';
        $htmlTable .= '</button>';
        $htmlTable .= '</td></tr>';

        // Collapsible detail row
        $htmlTable .= '<tr><td colspan="1" class="p-2">';
        $htmlTable .= '<div id="collapse_' . $monthYear . '" class="collapse accordion-details" data-month-year="' . $monthYear . '">';
        $htmlTable .= '<div class="table-responsive">';
        $htmlTable .= '<table class="table table-bordered table-hover table-sm">';
        $htmlTable .= '<thead class="table-light">';
        $htmlTable .= '<tr>';
        $htmlTable .= '<th class="text-center bg-danger-subtle">Date</th>';
        $htmlTable .= '<th class="text-center bg-danger-subtle">Product Name</th>';
        $htmlTable .= '<th class="text-center bg-danger-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-danger-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->product_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->empqty . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->wrk_hrs . '</td>';
                $htmlTable .= '</tr>';

                list($h, $m, $s) = explode(':', $detail->wrk_hrs ?? '00:00:00');
                $monthlyTotalWorkHrsSeconds += $h * 3600 + $m * 60 + $s;

            }
        }

        // Format total times
        $monthlyTotalWorkHrs = gmdate('H:i:s', $monthlyTotalWorkHrsSeconds);

        $htmlTable .= '</tbody></table>';

        // Total Summary Display
        $htmlTable .= '<div class="row text-center fw-bold mb-3">';
        $htmlTable .= '<div class="col-md-4"><span class="text-success">Total Work Hrs: ' . $monthlyTotalWorkHrs . '</span></div>';
        $htmlTable .= '</div>'; // .row

        $htmlTable .= '</div>'; // .table-responsive
        $htmlTable .= '</div>'; // .collapse
        $htmlTable .= '</td></tr>';
    }
}

$htmlTable .= '</table>';


// JavaScript to manipulate the DOM
$htmlTable .= '<script>
$(document).ready(function(){
    $(".accordion-toggle").on("click", function(){
        const target = $(this).data("bs-target");
        $(target).collapse("toggle");
    });
});
</script>';


    return $htmlTable;
    
    }    
}

