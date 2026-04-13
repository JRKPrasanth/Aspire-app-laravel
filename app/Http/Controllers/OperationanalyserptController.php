<?php

namespace App\Http\Controllers;
use session;    
use Illuminate\Http\Request;

class OperationanalyserptController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    
    public function Analysepro(Request $request)
    {

          $this->data['pro_name'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', ''," and (product_group_id='1' OR product_group_id='2') ");
          $this->data['pro_type'] = $this->jcomboprotypejoinselect('m_product_type_t', 'product_type_id', 'product_type', 'm_products_t','product_type_id','product_type_id','',''," and (`product_group_id` = '1' OR `product_group_id` = '2') ");
          $this->data['pro_varient'] = $this->jcomboprotypejoinselect('m_product_variants_t', 'product_variant_id', 'product_variant_name', 'm_products_t','variant_group_id','product_variant_id','',''," and m_products_t.variant_group_id != '' ");
          $this->data['process_name'] = $this->jcustomselecttool('a_lookuplines_t', 'lookup_code', 'lookup_code', ''," AND lookuphdr_id='22'");
         
            $start_date = $request->input('start_date');
            $end_date =   $request->input('end_date');
            $pro_name =   $request->input('pro_name');
            $pro_type =   $request->input('pro_type');
            $process =   $request->input('process_name');
            $pro_variant =   $request->input('pro_varient');

         $wh = '';
         $this->data['product_summary'] ='';
        if (is_array($pro_variant) && !empty($pro_variant)) {
            $pro_variant = array_map('intval', $pro_variant);
            $pro_variant_imp_vals = implode(',', $pro_variant);
            $wh = ' and emp.p_pack_id IN ('.$pro_variant_imp_vals.')';
        }
        
        if (!empty($pro_type)) {
            $wh = " and emp.product_type_id = '$pro_type'";
        }
        
        if (!empty($pro_name)) {
            $wh = " and emp.product_name = '$pro_name'";
        }
        
        if (!empty($process)) {
            $wh = " and emp.process_level = '$process'";
        }
        
        if (is_array($pro_variant) && !empty($pro_variant) && !empty($process)) {
            $pro_variant = array_map('intval', $pro_variant);
            $pro_variant_imp_vals = implode(',', $pro_variant);
            $wh = " and emp.p_pack_id IN ($pro_variant_imp_vals) AND emp.process_level = '$process'";
        }
        
        if (!empty($pro_type) && !empty($process)) {
            $wh = " and emp.product_type_id = '$pro_type' AND emp.process_level = '$process'";
        }
        
        if (!empty($pro_name) && !empty($process)) {
            $wh = " and emp.product_name = '$pro_name' AND emp.process_level = '$process'";
        }

   // dd($wh);
      if (!empty($start_date)) {
        
                 $this->data['product_summary'] = \DB::select("SELECT CONCAT(emp.prd_type, ' ', emp.product_variant) as prd_type,emp.batch_no,emp.pack_name,SUM(emp.empqty) as empqty,emp.yr_month from (SELECT * FROM
    (
    SELECT t.*,    
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
        m_product_type_t.product_type_id = t.product_type_id
) AS prd_type,

(
    SELECT
        m_product_variants_t.product_variant_id
    FROM
        m_product_variants_t
    WHERE
        m_product_variants_t.product_variant_id = t.p_pack_id
) AS varient_name,
        
(
    SELECT
        m_product_variants_t.product_variant_name
    FROM
        m_product_variants_t
    WHERE
        m_product_variants_t.product_variant_id = t.variant_id
) AS product_variant
FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
    (
    SELECT
        m_products_t.product_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS product_name,
CONCAT(LEFT(MONTHNAME(date(w_jobcard_process_details_t.process_start_date)),3), '-', year(date(w_jobcard_process_details_t.process_start_date))) as yr_month,             
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
w_jobcard_hdr_t.job_completion_date,
w_jobcard_hdr_t.batch_no,
w_jobcard_hdr_t.job_process,
w_jobcard_process_details_t.machine_time,
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
        m_products_t.product_variant_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS variant_id,
(
    SELECT
        m_products_t.product_type_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS product_type_id,
(
    SELECT
        m_products_t.variant_group_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS p_pack_id,
w_jobcard_process_details_t.process_level,w_jobcard_process_details_t.process_name,
date(w_jobcard_process_details_t.process_start_date) as process_date,
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
   '' AS product_name,
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   '' AS product_id,
   '' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS job_process,
   '' AS machine_time,
   '' AS pack_id,
   '' AS variant_id,
   '' AS product_type_id,
   '' AS p_pack_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS pack_name,
    '' AS prd_type,
    '' AS varient_name,
    '' AS product_varient
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  '$start_date' and '$end_date' 

union all

SELECT * FROM
        (
        SELECT t.*,
    '' as pack_name,
    '' as pro_type,
    '' as varient_name,
    '' AS product_varient

    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            w_jobcard_hdr_t.`reference_source_id`,
            w_jobcard_hdr_t.reference_source,
        (
        SELECT
            m_products_t.product_id
       FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS product_name,
    CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
    w_jobcard_hdr_t.product_id,
    w_qa_submitstage_trx_t.production_qty as job_qty,
    w_jobcard_hdr_t.job_completion_date,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.job_process,
    '' as machine_time,
    '' as pack_id,
    '' as variant_id,
    '' AS product_type_id,
    '' as p_pack_id,
    'PRODUCTION' as process_level,
    'PRODUCTION' as process_name,
    w_jobcard_hdr_t.job_date as process_date,
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
    ) AS v1) emp 
    where 1=1 $wh AND (emp.job_date BETWEEN '$start_date' AND '$end_date') GROUP BY emp.prd_type,emp.batch_no,emp.pack_name,emp.yr_month order by SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");

        // duration calculaton table
        
        $this->data['duration_summary'] = \DB::select("SELECT 
    emp.process_date,
    CONCAT(emp.prd_type, ' ', emp.product_varient) AS prd_type,
    emp.batch_no,
    emp.pack_name,
    DATE_FORMAT(emp.batch_sdate, '%d-%b') AS batch_sdate,
    DATE_FORMAT(emp.batch_edate, '%d-%b') AS batch_edate,
    DATEDIFF(emp.batch_edate, emp.batch_sdate) + 1 AS duration,
    emp.remark,
    CONCAT(LEFT(MONTHNAME(DATE(emp.batch_sdate)),3), '-', YEAR(DATE(emp.batch_sdate))) AS yr_month
FROM (
    SELECT * FROM (
        SELECT t.*,
        (SELECT i_product_packs.pack_value FROM i_product_packs WHERE i_product_packs.packing_id = t.pack_id) AS pack_name,
        (SELECT m_product_type_t.product_type FROM m_product_type_t WHERE m_product_type_t.product_type_id = t.product_type_id) AS prd_type,
        (SELECT m_product_variants_t.product_variant_name FROM m_product_variants_t WHERE m_product_variants_t.product_variant_id = t.variant_id) AS product_varient,
        (SELECT m_product_variants_t.product_variant_id FROM m_product_variants_t WHERE m_product_variants_t.product_variant_id = t.p_pack_id) AS varient_name
        FROM (
            SELECT
                h.job_no,
                h.job_date,
                h.reference_source_id,
                h.reference_source,

                (SELECT m_products_t.product_id FROM m_products_t WHERE m_products_t.product_id = h.product_id) AS product_name,

                CONCAT(LEFT(MONTHNAME(DATE(h.job_date)),3), '-', YEAR(DATE(h.job_date))) AS yr_month,

                -- ✅ FIXED: earliest start date for this job
                (
                    SELECT MIN(STR_TO_DATE(SUBSTRING_INDEX(d.start_time, ',', 1), '%Y-%m-%d'))
                    FROM w_jobcard_process_details_t d
                    WHERE d.job_id = h.w_jobs_hdr_id
                ) AS batch_sdate,

                -- ✅ FIXED: latest end date for this job
                (
                    SELECT MAX(STR_TO_DATE(SUBSTRING_INDEX(d.end_time, ',', 1), '%Y-%m-%d'))
                    FROM w_jobcard_process_details_t d
                    WHERE d.job_id = h.w_jobs_hdr_id
                ) AS batch_edate,

                (
                    SELECT w_qa_submitstage_trx_t.remarks
                    FROM w_qa_submitstage_trx_t
                    WHERE w_qa_submitstage_trx_t.job_no = h.w_jobs_hdr_id
                    LIMIT 1
                ) AS remark,

                h.product_id,
                p.move_qty AS job_qty,
                h.job_completion_date,
                h.batch_no,
                h.job_process,
                p.machine_time,

                (SELECT m_products_t.product_pack_id FROM m_products_t WHERE m_products_t.product_id = h.product_id) AS pack_id,
                (SELECT m_products_t.product_variant_id FROM m_products_t WHERE m_products_t.product_id = h.product_id) AS variant_id,
                (SELECT m_products_t.product_type_id FROM m_products_t WHERE m_products_t.product_id = h.product_id) AS product_type_id,
                (SELECT m_products_t.variant_group_id FROM m_products_t WHERE m_products_t.product_id = h.product_id) AS p_pack_id,

                p.process_level,
                p.process_name,
                DATE(p.process_date) AS process_date,

                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(p.jobassigned_to, ',', m_products_t.product_id),
                    ',', -1
                ) AS job_assigned_to

            FROM w_jobcard_process_details_t p
            JOIN w_jobcard_hdr_t h 
                ON h.w_jobs_hdr_id = p.job_id
            JOIN m_products_t 
                ON CHAR_LENGTH(p.jobassigned_to) - CHAR_LENGTH(REPLACE(p.jobassigned_to, ',', '')) >= m_products_t.product_id - 1
            WHERE 1=1
        ) AS t
        WHERE 1=1
    ) AS v1
    WHERE v1.process_date BETWEEN '$start_date' AND '$end_date'

    UNION ALL

    SELECT
        '' AS job_no,
        DATE(w_jobactivity_lines_t.start_datetime) AS job_date,
        '' AS reference_source_id,
        '' AS reference_source,
        '' AS product_name,
        '' AS yr_month,
        '' AS batch_sdate,
        '' AS batch_edate,
        '' AS remark,
        '' AS product_id,
        '' AS job_qty,
        '' AS job_completion_date,
        '' AS batch_no,
        '' AS job_process,
        '' AS machine_time,
        '' AS pack_id,
        '' AS variant_id,
        '' AS product_type_id,
        '' AS p_pack_id,
        w_jobactivity_lines_t.activity_name AS process_level,
        w_jobactivity_lines_t.activity_name AS process_name,
        DATE(w_jobactivity_lines_t.start_datetime) AS process_date,
        w_jobactivity_hdr_t.employee_id AS job_assigned_to,
        '' AS pack_name,
        '' AS prd_type,
        '' AS product_varient,
        '' AS varient_name
    FROM w_jobactivity_hdr_t
    LEFT JOIN w_jobactivity_lines_t 
        ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
    LEFT JOIN hr_employee_t 
        ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
    LEFT JOIN tb_users 
        ON tb_users.id = w_jobactivity_hdr_t.created_by
    WHERE DATE(w_jobactivity_lines_t.start_datetime) BETWEEN '$start_date' AND '$end_date'

) emp
WHERE 1=1 $wh
AND emp.batch_sdate BETWEEN '$start_date' AND '$end_date'
GROUP BY emp.process_date, emp.prd_type, emp.batch_no, emp.pack_name, emp.yr_month
ORDER BY SUBSTRING(yr_month, -2) + 0 DESC,
STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");


 // extra hours cal
    
     $this->data['extra_hrs_cal'] = \DB::select("select emp.prd_type,SUM(emp.job_qty) as job_qty, emp.yr_month, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs,CASE  WHEN SEC_TO_TIME(720000) > SEC_TO_TIME(SUM(TIME_TO_SEC(emp.working_hours))) THEN TIMEDIFF(SEC_TO_TIME(720000), SEC_TO_TIME(SUM(TIME_TO_SEC(emp.working_hours)))) ELSE '00:00:00' END AS idle_hrs, TIMEDIFF(SEC_TO_TIME(SUM(TIME_TO_SEC(emp.working_hours))), '200:00:00') AS extra_hrs  from (SELECT
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
        hr_employee_t.group_type
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
)AS job_assigned_name,
       
(
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
       hr_employee_t.employee_id = t.job_assigned_to
) AS prd_type
FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,

CONCAT(LEFT(MONTHNAME(date(w_jobcard_process_details_t.process_start_date)),3), '-', year(date(w_jobcard_process_details_t.process_start_date))) as yr_month,   
(
    SELECT
        w_productionplan_hdr_t.reference_id
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_reference_id,           
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
        m_products_t.product_variant_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS variant_id,
w_jobcard_process_details_t.process_level,w_jobcard_process_details_t.process_name,
date(w_jobcard_process_details_t.process_start_date) as process_date,

        
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
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   '' AS plan_reference_id,
   '' AS product_id,
   '' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS job_process,
   '' AS machine_time,
   '' AS range_to,
   '' AS variant_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.group_type AS job_assigned_name,
    hr_employee_t.first_name AS prd_type,
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
            hr_employee_t.group_type
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.job_assigned_to
    ) AS job_assigned_name,
    
    '' as pro_type

    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            w_jobcard_hdr_t.`reference_source_id`,
            w_jobcard_hdr_t.reference_source,

    CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,

    (
        SELECT
            w_productionplan_hdr_t.reference_id
        FROM
            w_productionplan_hdr_t
        WHERE
            w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
    ) AS plan_reference_id,

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
    '' as variant_id,
    'PRODUCTION' as process_level,
    'PRODUCTION' as process_name,
    w_jobcard_hdr_t.job_date as process_date,

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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' ) emp 
WHERE emp.job_assigned_name ='10' and emp.prd_type !='' GROUP BY emp.prd_type, emp.yr_month ORDER BY SUBSTRING(yr_month, -2) + 0 ASC,STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");


  }

        return view('operationperformancelog.analyserpt', $this->data);
   
  }
    
   // analyze report popup
 
     public function Analysepopup(Request $request){
               
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $prd_type= $request->input('prd_type');
    $batch = $request->input('batch');
    $unit = $request->input('unit');

      if($prd_type == "DR.JRK'S 777"){
           
           $prd_type = addslashes($prd_type);
       }
       
        $daily_summary = \DB::select("select date(emp.job_date)as date,emp.qa_assigned_name,emp.prd_type,emp.batch_no,emp.pack_name,SUM(emp.empqty) as empqty,emp.yr_month, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT * FROM
    (
    SELECT
        t.*,

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
        m_product_type_t.product_type_id = t.product_type_id
) AS prd_type

FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,

CONCAT(LEFT(MONTHNAME(date(w_jobcard_process_details_t.process_start_date)),3), '-', year(date(w_jobcard_process_details_t.process_start_date))) as yr_month,              
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
w_jobcard_hdr_t.batch_no,
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
        m_products_t.product_type_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS product_type_id,
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
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   '' AS product_id,
   '' AS job_qty,
   '' AS batch_no,
   '' AS pack_id,
   '' AS product_type_id,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    hr_employee_t.first_name AS qa_assigned_name,
    '' AS pack_name,
    '' AS prd_type
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  '$start_date' and '$end_date'

union all


SELECT
        *
    FROM
        (
        SELECT
            t.*,
        (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.qa_job_assigned_to
    ) AS qa_assigned_name,
    '' as pack_name,
    '' as pro_type

    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            w_jobcard_hdr_t.`reference_source_id`,
            w_jobcard_hdr_t.reference_source,

    CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
    
    w_jobcard_hdr_t.product_id,
    w_qa_submitstage_trx_t.production_qty as job_qty,
    w_jobcard_hdr_t.batch_no,
    '' as pack_id,
    '' AS product_type_id,
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
    ) AS v1) emp  
    where 1=1  AND emp.prd_type='$prd_type' AND emp.batch_no='$batch' AND emp.pack_name='$unit'  AND (emp.job_date BETWEEN '$start_date' AND '$end_date') GROUP BY emp.job_date,emp.qa_assigned_name,emp.prd_type,emp.batch_no,emp.pack_name order by emp.job_date ASC");

$htmlTable = '<table id="machineSummary" class="table table-bordered table-hover table-sm align-middle" style="width:100%; font-family: \'Saira Semi Condensed\', sans-serif;">';

$uniqueMonthYears = [];

foreach ($daily_summary as $value) {
    $monthYear = $value->yr_month;

    if (!isset($uniqueMonthYears[$monthYear])) {
        $uniqueMonthYears[$monthYear] = true;
        $monthlyTotalWorkHrsSeconds = 0;

        // Main row with Bootstrap 5 button
        $htmlTable .= '<tr>';
        $htmlTable .= '<td>';
        $htmlTable .= '<button class="btn btn-outline-primary btn-sm  accordion-toggle w-100 text-start" data-bs-toggle="collapse" data-target="#collapse_' . $monthYear . '" aria-expanded="false" aria-controls="collapse_' . $monthYear . '">';
        $htmlTable .= '<strong>' . $monthYear . '</strong>';
        $htmlTable .= '</button>';
        $htmlTable .= '</td>';
        $htmlTable .= '</tr>';

        // Collapsible row content
        $htmlTable .= '<tr class="accordion-details collapse-row">';
        $htmlTable .= '<td colspan="1" class="p-2">';

        $htmlTable .= '<div id="collapse_' . $monthYear . '" class="collapse" data-month-year="' . $monthYear . '">';
        $htmlTable .= '<div class="table-responsive">';
        $htmlTable .= '<table id="popuptbl_' . $monthYear . '" class="table table-bordered table-hover table-sm align-middle">';
        $htmlTable .= '<thead class="table-light">';
        $htmlTable .= '<tr>';
        $htmlTable .= '<th class="text-center bg-warning-subtle">Date</th>';
        $htmlTable .= '<th class="text-center bg-warning-subtle">Employee Name</th>';
        $htmlTable .= '<th class="text-center bg-warning-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-warning-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->yr_month == $monthYear) {
                $wrk_hrs = $detail->wrk_hrs ?? '00:00:00';
                list($h, $m, $s) = explode(':', $wrk_hrs);
                $monthlyTotalWorkHrsSeconds += $h * 3600 + $m * 60 + $s;

                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->qa_assigned_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->empqty . '</td>';
                $htmlTable .= '<td class="text-center">' . $wrk_hrs . '</td>';
                $htmlTable .= '</tr>';
            }
        }

        $h = floor($monthlyTotalWorkHrsSeconds / 3600);
        $m = floor(($monthlyTotalWorkHrsSeconds % 3600) / 60);
        $s = $monthlyTotalWorkHrsSeconds % 60;
        $monthlyTotalWorkHrs = sprintf('%02d:%02d:%02d', $h, $m, $s);

        $htmlTable .= '</tbody></table>';
        $htmlTable .= '<div class="text-end fw-bold mt-2">Total Work Hrs: ' . $monthlyTotalWorkHrs . '</div>';
        $htmlTable .= '</div>'; // table-responsive
        $htmlTable .= '</div>'; // collapse
        $htmlTable .= '</td>';
        $htmlTable .= '</tr>';
    }
}

$htmlTable .= '</table>';

    
    // JavaScript to manipulate the DOM
    $htmlTable .= '<script>
		$(document).ready(function () {
			$(".accordion-toggle").on("click", function () {
				var target = $(this).data("target");
				$(target).collapse("toggle");
			});
		});
    </script>';
    
    return $htmlTable;

} 

       // employee date wise hrs popup rpt
        
     public function employeehrspopup (Request $request){
           
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
		$employeeName = $request->input('employeeName');
           
            $daily_summary = \DB::select("select emp.prd_type,SUM(emp.job_qty) as job_qty, date(emp.yr_month) as date,CONCAT(LEFT(MONTHNAME(date(emp.yr_month)),3), '-', year(date(emp.yr_month))) as month_yr, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs,    CASE  WHEN SEC_TO_TIME(28800) > SEC_TO_TIME(SUM(TIME_TO_SEC(emp.working_hours))) THEN TIMEDIFF(SEC_TO_TIME(28800), SEC_TO_TIME(SUM(TIME_TO_SEC(emp.working_hours)))) ELSE '00:00:00' END AS idle_hours,CASE WHEN SEC_TO_TIME(SUM(TIME_TO_SEC(emp.working_hours))) > '08:00:00' THEN TIMEDIFF(SEC_TO_TIME(SUM(TIME_TO_SEC(emp.working_hours))), '08:00:00') ELSE '00:00:00' END AS extra_hrs  from (SELECT * FROM
    (
    SELECT t.*,
       
(
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
       hr_employee_t.employee_id = t.job_assigned_to
) AS prd_type
FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
date(w_jobcard_process_details_t.process_start_date) as yr_month,   
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
w_jobcard_hdr_t.job_completion_date,
w_jobcard_hdr_t.batch_no,
w_jobcard_hdr_t.job_process,
w_jobcard_process_details_t.machine_time,
(
    SELECT
        m_products_t.product_variant_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS variant_id,
w_jobcard_process_details_t.process_level,w_jobcard_process_details_t.process_name,
date(w_jobcard_process_details_t.process_start_date) as process_date,

        
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
) AS v1 WHERE
    1 = 1 AND v1.process_date BETWEEN '$start_date' AND '$end_date'

    UNION ALL 
    
   SELECT
   '' AS job_no,
   date(w_jobactivity_lines_t.start_datetime) AS job_date,
   '' AS reference_source_id,
   '' AS reference_source,
   date(w_jobactivity_lines_t.start_datetime) as yr_month,
   '' AS product_id,
   '0' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS job_process,
   '' AS machine_time,
   '' AS variant_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    hr_employee_t.first_name AS prd_type
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by  where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  '$start_date' and '$end_date' 

union all

SELECT * FROM
        (
        SELECT  t.*,
        '' as pro_type
    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            w_jobcard_hdr_t.`reference_source_id`,
            w_jobcard_hdr_t.reference_source,
    date(w_jobcard_hdr_t.job_date) as yr_month,
    w_jobcard_hdr_t.product_id,
    w_qa_submitstage_trx_t.production_qty as job_qty,
    w_jobcard_hdr_t.job_completion_date,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.job_process,
    '' as machine_time,
    '' as variant_id,
    'PRODUCTION' as process_level,
    'PRODUCTION' as process_name,
    w_jobcard_hdr_t.job_date as process_date,

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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date') emp 
WHERE emp.prd_type ='$employeeName'  GROUP BY emp.yr_month,emp.prd_type ORDER BY yr_month ASC");

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
        $htmlTable .= '<button class="btn btn-outline-success btn-sm  text-start accordion-toggle" data-bs-toggle="collapse" data-bs-target="#collapse_' . $monthYear . '" aria-expanded="false" aria-controls="collapse_' . $monthYear . '">';
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
        $htmlTable .= '<th class="text-center bg-primary-subtle">Date</th>';
        $htmlTable .= '<th class="text-center bg-primary-subtle">Work Hrs</th>';
        $htmlTable .= '<th class="text-center bg-primary-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-primary-subtle">Idle Hrs</th>';
        $htmlTable .= '<th class="text-center bg-primary-subtle">Extra Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . date('H:i:s', strtotime($detail->wrk_hrs)) . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->job_qty . '</td>';
                $htmlTable .= '<td class="text-center">' . date('H:i:s', strtotime($detail->idle_hours)) . '</td>';
                $htmlTable .= '<td class="text-center">' . date('H:i:s', strtotime($detail->extra_hrs)) . '</td>';
                $htmlTable .= '</tr>';

                list($h, $m, $s) = explode(':', $detail->wrk_hrs ?? '00:00:00');
                $monthlyTotalWorkHrsSeconds += $h * 3600 + $m * 60 + $s;

                list($h, $m, $s) = explode(':', $detail->idle_hours ?? '00:00:00');
                $monthlyTotalidleHrsSeconds += $h * 3600 + $m * 60 + $s;

                list($h, $m, $s) = explode(':', $detail->extra_hrs ?? '00:00:00');
                $monthlyTotalextraHrsSeconds += $h * 3600 + $m * 60 + $s;
            }
        }

        // Format total times
        $monthlyTotalWorkHrs = gmdate('H:i:s', $monthlyTotalWorkHrsSeconds);
        $monthlyTotalidleHrs = gmdate('H:i:s', $monthlyTotalidleHrsSeconds);
        $monthlyTotalextraHrs = gmdate('H:i:s', $monthlyTotalextraHrsSeconds);

        $htmlTable .= '</tbody></table>';

        // Total Summary Display
        $htmlTable .= '<div class="row text-center fw-bold mb-3">';
        $htmlTable .= '<div class="col-md-4"><span class="text-success">Total Work Hrs: ' . $monthlyTotalWorkHrs . '</span></div>';
        $htmlTable .= '<div class="col-md-4"><span class="text-primary">Total Idle Hrs: ' . $monthlyTotalidleHrs . '</span></div>';
        $htmlTable .= '<div class="col-md-4"><span class="text-danger">Total Extra Hrs: ' . $monthlyTotalextraHrs . '</span></div>';
        $htmlTable .= '</div>'; 

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