<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class OperationperformancelogController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    
    public function operationperformanceindex(Request $request)
    {

        $Emp_name = $request->input('emp_name');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

         if($Emp_name !=''){
        $employe= \DB::select("SELECT first_name FROM hr_employee_t WHERE employee_id ='$Emp_name'");
        
        $this->data['employee'] = $employe[0]->first_name;
         }else{
             $this->data['employee'] ='';
         }

        
        $this->data['employee_name'] = $this->jcustomselecttool('hr_employee_t', 'employee_id', 'first_name', ''," and `group_type` = '10' and active='Yes'");

        //  type wise summary table
        
       if (!empty($Emp_name)) {
           
            
        $this->data['prim_summary'] = \DB::select("select emp.yr_month,emp.type, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ))) wrk_hrs, subtime(SEC_TO_TIME(720000),SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours)))) idle_hours from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
)AS job_assigned_name,
       
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.variant_id
) AS prd_type
FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,

'MAJOR' AS type,
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
   'SUB' AS type,
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
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
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
            hr_employee_t.employee_id
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

    'MAJOR' as type,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
WHERE emp.job_assigned_name ='$Emp_name' GROUP BY emp.yr_month,emp.type ORDER BY SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");


     //  type wise summary 
     
        $this->data['product_summary'] = \DB::select("select emp.prd_type,emp.yr_month,emp.type, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs, subtime(SEC_TO_TIME(720000),SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours)))) idle_hours from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
)AS job_assigned_name,
       
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

'MAJOR' AS type,
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
(
    SELECT
        m_products_t.product_type_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS product_type_id,
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
   'SUB' AS type,
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
   '' AS product_type_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.employee_id AS job_assigned_name,
    'SUB' AS prd_type,
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
            hr_employee_t.employee_id
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

    'MAJOR' as type,
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
    '' AS product_type_id,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
    WHERE emp.job_assigned_name ='$Emp_name' GROUP BY emp.prd_type,emp.type,emp.yr_month ORDER BY SUBSTRING(yr_month, -2) + 0 ASC,STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");


        //  machine_summary table

        $this->data['machine_summary'] = \DB::select("select emp.machine_name,emp.yr_month,emp.type, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs, subtime(SEC_TO_TIME(720000),SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours)))) idle_hours from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
)AS job_assigned_name,
       
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.variant_id
) AS prd_type
FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,

'MAJOR' AS type,
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
        w_machine_hdr_t.machine_name
    FROM
        w_machine_hdr_t
    WHERE
        w_machine_hdr_t.machine_hdr_id = w_jobcard_process_details_t.machine_id
) AS machine_name,

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
   'SUB' AS type,
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   '' AS plan_reference_id,
   '' AS product_id,
   '' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS job_process,
   '' AS machine_time,
   '' AS range_to,
   'MANUAL' AS machine_name,
   '' AS variant_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
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
            hr_employee_t.employee_id
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

    'MAJOR' as type,
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
        (
        SELECT
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
WHERE emp.job_assigned_name ='$Emp_name' GROUP BY emp.machine_name, emp.yr_month,emp.type ORDER BY SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");

        //  pack wise summary 
        
              //  pack wise summary 
        
        $this->data['type_summary'] = \DB::select("select emp.p_pack_name,emp.yr_month,emp.type, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs, subtime(SEC_TO_TIME(720000),SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours)))) idle_hours from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
       
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.variant_id
) AS prd_type,
(
    SELECT
        m_product_variants_t.product_variant_name
    FROM
        m_product_variants_t
    WHERE
        m_product_variants_t.product_variant_id = t.variant_id
) AS p_pack_name
FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,

'MAJOR' AS type,
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
(
    SELECT
        m_products_t.product_pack_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS p_pack_id,
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
   'SUB' AS type,
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   '' AS plan_reference_id,
   '' AS product_id,
   '' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS range_to,
   '' AS variant_id,
   '' AS p_pack_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.first_name AS qa_assigned_name,
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
    'SUB' AS p_pack_name,
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
            hr_employee_t.employee_id
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.job_assigned_to
    ) AS job_assigned_name,

    '' as pro_type,
    '' as p_pack_name

    FROM
        (
        SELECT
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            w_jobcard_hdr_t.`reference_source_id`,
            w_jobcard_hdr_t.reference_source,
    'MAJOR' as type,
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
    '' as p_pack_id,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
        WHERE emp.job_assigned_name ='$Emp_name' GROUP BY emp.yr_month,emp.p_pack_name,emp.type ORDER BY SUBSTRING(yr_month, -2) + 0 ASC,STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");


    // product name summary
    
    $this->data['name_summary'] = \DB::select("select emp.product_name, emp.yr_month,emp.type, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs, subtime(SEC_TO_TIME(720000),SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours)))) idle_hours from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,

(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.variant_id
) AS prd_type

FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
    (
    SELECT
        m_products_t.concatenated_product
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS product_name,

'MAJOR' AS type,
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
   'SUB' AS product_name,
   'SUB' AS type,
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
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
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
            hr_employee_t.employee_id
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
        (
        SELECT
            m_products_t.concatenated_product
       FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS product_name,
    'MAJOR' as type,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
        WHERE emp.job_assigned_name ='$Emp_name' GROUP BY emp.product_name, emp.yr_month,emp.type ORDER BY SUBSTRING(yr_month, -2) + 0 ASC,STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");

            }
            
        return view('operationperformancelog.opeationperformlogdetailsrpt', $this->data);
        
    }
    
     public function Emptypedtls(Request $request){

    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $type = $request->input('type');
    $Emp_id = $request->input('emp_name');
       
        $daily_summary = \DB::select("select emp.machine_name,emp.product_name,emp.process_name,emp.empqty,emp.type, date(emp.yr_month) as date,CONCAT(LEFT(MONTHNAME(date(emp.yr_month)),3), '-', year(date(emp.yr_month))) as month_yr, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
       
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.variant_id
) AS prd_type

FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
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
date(w_jobcard_process_details_t.process_start_date) as yr_month,   

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
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
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
        w_machine_equipments_hdr_t.machine_id =w_jobcard_process_details_t.machine_id
    LIMIT 1
) AS range_to,

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
        m_products_t.product_variant_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS variant_id,
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
   '' AS product_name,
   'SUBACT' AS plan_no,
   'SUB' AS type,
   date(w_jobactivity_lines_t.start_datetime) as yr_month,
   '' AS plan_reference_id,
   '' AS production_qty,
   '' AS plan_qty,
   '' AS product_id,
   '' AS job_qty,
   '' AS batch_no,
   '' AS job_process,
   '' AS range_to,
   'MANUAL' AS machine_name,
   '' AS variant_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1),'%H:%i') AS starttime,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1),'%H:%i') AS endtime,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
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
            hr_employee_t.employee_id
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
     date(w_jobcard_hdr_t.job_date) as yr_month,
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
    w_jobcard_hdr_t.product_id,
        
    w_qa_submitstage_trx_t.production_qty as job_qty,
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
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,

    '' as variant_id,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
        WHERE emp.type='$type' AND emp.job_assigned_name ='$Emp_id'  GROUP BY emp.machine_name,emp.process_name,emp.product_name, emp.yr_month,emp.type order by emp.yr_month ASC");

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
        $htmlTable .= '<th class="text-center bg-primary-subtle">Date</th>';
        $htmlTable .= '<th class="text-center bg-primary-subtle">Machine Name</th>';
        $htmlTable .= '<th class="text-center bg-primary-subtle">Process</th>';
        $htmlTable .= '<th class="text-center bg-primary-subtle">Product Name</th>';
        $htmlTable .= '<th class="text-center bg-primary-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-primary-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->machine_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->process_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->product_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->empqty . '</td>';
                $htmlTable .= '<td class="text-center">' . date('H:i:s', strtotime($detail->wrk_hrs)) . '</td>';
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
        $htmlTable .= '<div class="col-md-4"><span class="text-danger">Total Work Hrs: ' . $monthlyTotalWorkHrs . '</span></div>';
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
    
    public function Emproductdtls(Request $request){
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $type = $request->input('type');
    $Emp_id = $request->input('emp_name');
    $product = $request->input('prodct');
       
       if($product == "DR.JRK'S 777"){
           
           $product = addslashes($product);
       }
       if($type =='SUB'){
           
           $wh = " emp.type='$type' AND emp.job_assigned_name ='$Emp_id'";
           
       }else{
           
           $wh= " emp.type='$type' AND emp.job_assigned_name ='$Emp_id' AND emp.prd_type = '$product'";
       }
           
       
       
       $daily_summary = \DB::select("select emp.machine_name,emp.process_name,emp.process_level,SUM(emp.empqty) as empqty,emp.type, date(emp.yr_month) as date,CONCAT(LEFT(MONTHNAME(date(emp.yr_month)),3), '-', year(date(emp.yr_month))) as month_yr, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT *,
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
       
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
date(w_jobcard_process_details_t.process_start_date) as yr_month,   

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
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
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
        w_machine_equipments_hdr_t.machine_id =w_jobcard_process_details_t.machine_id
    LIMIT 1
) AS range_to,

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
   '' AS product_name,
   'SUBACT' AS plan_no,
   'SUB' AS type,
   date(w_jobactivity_lines_t.start_datetime) as yr_month,
   '' AS plan_reference_id,
   '' AS production_qty,
   '' AS plan_qty,
   '' AS product_id,
   '' AS job_qty,
   '' AS batch_no,
   '' AS job_process,
   '' AS range_to,
   'MANUAL' AS machine_name,
   '' AS variant_id,
   '' AS product_type_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1),'%H:%i') AS starttime,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1),'%H:%i') AS endtime,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
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
            hr_employee_t.employee_id
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
     date(w_jobcard_hdr_t.job_date) as yr_month,
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
    w_jobcard_hdr_t.product_id,
        
    w_qa_submitstage_trx_t.production_qty as job_qty,
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
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,

    '' as variant_id,
    '' AS product_type_id,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
        WHERE $wh GROUP BY emp.yr_month,emp.machine_name,emp.process_name,emp.process_level order by emp.yr_month ASC");
      
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
        $htmlTable .= '<button class="btn btn-outline-primary btn-sm  text-start accordion-toggle" data-bs-toggle="collapse" data-bs-target="#collapse_' . $monthYear . '" aria-expanded="false" aria-controls="collapse_' . $monthYear . '">';
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
        $htmlTable .= '<th class="text-center bg-success-subtle">Date</th>';
        $htmlTable .= '<th class="text-center bg-success-subtle">Machine Name</th>';
        $htmlTable .= '<th class="text-center bg-success-subtle">Process</th>';
        $htmlTable .= '<th class="text-center bg-success-subtle">Process Level</th>';
        $htmlTable .= '<th class="text-center bg-success-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-success-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->machine_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->process_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->process_level . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->empqty . '</td>';
                $htmlTable .= '<td class="text-center">' . date('H:i:s', strtotime($detail->wrk_hrs)) . '</td>';
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
        $htmlTable .= '<div class="col-md-4"><span class="text-danger">Total Work Hrs: ' . $monthlyTotalWorkHrs . '</span></div>';
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


    public function EmpMachinedtls(Request $request){
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $type = $request->input('type');
    $Emp_id = $request->input('emp_name');
    $machine = $request->input('machine');
       
        $daily_summary = \DB::select("select emp.product_name,emp.process_name,emp.empqty,emp.type, date(emp.yr_month) as date,CONCAT(LEFT(MONTHNAME(date(emp.yr_month)),3), '-', year(date(emp.yr_month))) as month_yr, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
       
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.variant_id
) AS prd_type

FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
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
date(w_jobcard_process_details_t.process_start_date) as yr_month,   

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
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
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
        w_machine_equipments_hdr_t.machine_id =w_jobcard_process_details_t.machine_id
    LIMIT 1
) AS range_to,

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
        m_products_t.product_variant_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS variant_id,
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
   '' AS product_name,
   'SUBACT' AS plan_no,
   'SUB' AS type,
   date(w_jobactivity_lines_t.start_datetime) as yr_month,
   '' AS plan_reference_id,
   '' AS production_qty,
   '' AS plan_qty,
   '' AS product_id,
   '' AS job_qty,
   '' AS batch_no,
   '' AS job_process,
   '' AS range_to,
   'MANUAL' AS machine_name,
   '' AS variant_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1),'%H:%i') AS starttime,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1),'%H:%i') AS endtime,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
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
            hr_employee_t.employee_id
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
     date(w_jobcard_hdr_t.job_date) as yr_month,
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
    w_jobcard_hdr_t.product_id,
        
    w_qa_submitstage_trx_t.production_qty as job_qty,
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
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,

    '' as variant_id,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
        WHERE emp.type='$type' AND emp.job_assigned_name ='$Emp_id' AND emp.machine_name ='$machine' GROUP BY emp.process_name,emp.product_name, emp.yr_month,emp.type order by emp.yr_month ASC");

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
        $htmlTable .= '<button class="btn btn-outline-danger btn-sm  text-start accordion-toggle" data-bs-toggle="collapse" data-bs-target="#collapse_' . $monthYear . '" aria-expanded="false" aria-controls="collapse_' . $monthYear . '">';
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
        $htmlTable .= '<th class="text-center bg-danger-subtle">Process</th>';
        $htmlTable .= '<th class="text-center bg-danger-subtle">Product Name</th>';
        $htmlTable .= '<th class="text-center bg-danger-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-danger-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->process_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->product_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->empqty . '</td>';
                $htmlTable .= '<td class="text-center">' . date('H:i:s', strtotime($detail->wrk_hrs)) . '</td>';
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
        $htmlTable .= '<div class="col-md-4"><span class="text-danger">Total Work Hrs: ' . $monthlyTotalWorkHrs . '</span></div>';
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
    
    
 
 public function EmpCategorydtls(Request $request){
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $type = $request->input('type');
    $Emp_id = $request->input('emp_name');
    $category = $request->input('category');
       
       if($type =='SUB'){
            $wh = " emp.type='$type' AND emp.job_assigned_name ='$Emp_id'";
       }else{
           
           $wh= " emp.type='$type' AND emp.job_assigned_name ='$Emp_id' AND emp.p_pack_name ='$category'";
       }  
           
                   $daily_summary = \DB::select("select emp.machine_name,emp.product_name,emp.empqty,emp.type, date(emp.yr_month) as date,CONCAT(LEFT(MONTHNAME(date(emp.yr_month)),3), '-', year(date(emp.yr_month))) as month_yr, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
       
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.variant_id
) AS prd_type,
(
    SELECT
        m_product_variants_t.product_variant_name
    FROM
        m_product_variants_t
    WHERE
        m_product_variants_t.product_variant_id = t.variant_id
) AS p_pack_name


FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
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
date(w_jobcard_process_details_t.process_start_date) as yr_month,   

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
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
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
        w_machine_equipments_hdr_t.machine_id =w_jobcard_process_details_t.machine_id
    LIMIT 1
) AS range_to,

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
        m_products_t.product_variant_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS variant_id,
(
    SELECT
        m_products_t.product_pack_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS p_pack_id,
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
   '' AS product_name,
   'SUBACT' AS plan_no,
   'SUB' AS type,
   date(w_jobactivity_lines_t.start_datetime) as yr_month,
   '' AS plan_reference_id,
   '' AS production_qty,
   '' AS plan_qty,
   '' AS product_id,
   '' AS job_qty,
   '' AS batch_no,
   '' AS job_process,
   '' AS range_to,
   'MANUAL' AS machine_name,
   '' AS variant_id,
   '' AS p_pack_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1),'%H:%i') AS starttime,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1),'%H:%i') AS endtime,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
    '' AS p_pack_name,
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
            hr_employee_t.employee_id
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
     date(w_jobcard_hdr_t.job_date) as yr_month,
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
    w_jobcard_hdr_t.product_id,
        
    w_qa_submitstage_trx_t.production_qty as job_qty,
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
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,

    '' as variant_id,
    '' AS p_pack_id,
    '' as p_pack_name,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
        WHERE $wh GROUP BY emp.machine_name,emp.product_name, emp.yr_month,emp.type order by emp.yr_month ASC");

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
        $htmlTable .= '<button class="btn btn-outline-primary btn-sm  text-start accordion-toggle" data-bs-toggle="collapse" data-bs-target="#collapse_' . $monthYear . '" aria-expanded="false" aria-controls="collapse_' . $monthYear . '">';
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
        $htmlTable .= '<th class="text-center bg-info-subtle">Date</th>';
        $htmlTable .= '<th class="text-center bg-info-subtle">Machine Name</th>';
        $htmlTable .= '<th class="text-center bg-info-subtle">Product Name</th>';
        $htmlTable .= '<th class="text-center bg-info-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-info-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->machine_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->product_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->empqty . '</td>';
                $htmlTable .= '<td class="text-center">' . date('H:i:s', strtotime($detail->wrk_hrs)) . '</td>';
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
        $htmlTable .= '<div class="col-md-4"><span class="text-danger">Total Work Hrs: ' . $monthlyTotalWorkHrs . '</span></div>';
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

    public function Empprodtls(Request $request){
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $type = $request->input('type');
    $Emp_id = $request->input('emp_name');
    $product = $request->input('product');
       
       if($type == 'SUB'){
                   $daily_summary = \DB::select("select emp.machine_name,emp.process_name,emp.empqty,emp.type, date(emp.yr_month) as date,CONCAT(LEFT(MONTHNAME(date(emp.yr_month)),3), '-', year(date(emp.yr_month))) as month_yr,emp.starttime,emp.endtime,SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
       
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.variant_id
) AS prd_type

FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
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
date(w_jobcard_process_details_t.process_start_date) as yr_month,   

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
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
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
        w_machine_equipments_hdr_t.machine_id =w_jobcard_process_details_t.machine_id
    LIMIT 1
) AS range_to,

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
        m_products_t.product_variant_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS variant_id,
(
    SELECT
        m_products_t.product_pack_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS p_pack_id,
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
   '' AS product_name,
   'SUBACT' AS plan_no,
   'SUB' AS type,
   date(w_jobactivity_lines_t.start_datetime) as yr_month,
   '' AS plan_reference_id,
   '' AS production_qty,
   '' AS plan_qty,
   '' AS product_id,
   '' AS job_qty,
   '' AS batch_no,
   '' AS job_process,
   '' AS range_to,
   'MANUAL' AS machine_name,
   '' AS variant_id,
   '' AS p_pack_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1),'%H:%i') AS starttime,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1),'%H:%i') AS endtime,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
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
            hr_employee_t.employee_id
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
     date(w_jobcard_hdr_t.job_date) as yr_month,
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
    w_jobcard_hdr_t.product_id,
        
    w_qa_submitstage_trx_t.production_qty as job_qty,
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
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,

    '' as variant_id,
    '' AS p_pack_id,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
        WHERE emp.type='$type' AND emp.job_assigned_name ='$Emp_id' GROUP BY emp.machine_name,emp.process_name,emp.starttime,emp.yr_month,emp.type order by emp.yr_month ASC");
       }else{
        $daily_summary = \DB::select("select emp.machine_name,emp.process_name,emp.empqty,emp.type, date(emp.yr_month) as date,CONCAT(LEFT(MONTHNAME(date(emp.yr_month)),3), '-', year(date(emp.yr_month))) as month_yr,emp.starttime,emp.endtime,SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
       
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.variant_id
) AS prd_type

FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
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
date(w_jobcard_process_details_t.process_start_date) as yr_month,   

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
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
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
        w_machine_equipments_hdr_t.machine_id =w_jobcard_process_details_t.machine_id
    LIMIT 1
) AS range_to,

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
        m_products_t.product_variant_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS variant_id,
(
    SELECT
        m_products_t.product_pack_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS p_pack_id,
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
   '' AS product_name,
   'SUBACT' AS plan_no,
   'SUB' AS type,
   date(w_jobactivity_lines_t.start_datetime) as yr_month,
   '' AS plan_reference_id,
   '' AS production_qty,
   '' AS plan_qty,
   '' AS product_id,
   '' AS job_qty,
   '' AS batch_no,
   '' AS job_process,
   '' AS range_to,
   'MANUAL' AS machine_name,
   '' AS variant_id,
   '' AS p_pack_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1),'%H:%i') AS starttime,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1),'%H:%i') AS endtime,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
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
            hr_employee_t.employee_id
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
     date(w_jobcard_hdr_t.job_date) as yr_month,
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
    w_jobcard_hdr_t.product_id,
        
    w_qa_submitstage_trx_t.production_qty as job_qty,
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
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,

    '' as variant_id,
    '' AS p_pack_id,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
        WHERE emp.type='$type' AND emp.job_assigned_name ='$Emp_id' AND emp.product_name ='$product' GROUP BY emp.machine_name,emp.process_name,emp.starttime,emp.yr_month,emp.type order by emp.yr_month ASC");

        }

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
        $htmlTable .= '<button class="btn btn-outline-info btn-sm  text-start accordion-toggle" data-bs-toggle="collapse" data-bs-target="#collapse_' . $monthYear . '" aria-expanded="false" aria-controls="collapse_' . $monthYear . '">';
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
        $htmlTable .= '<th class="text-center bg-secondary-subtle">Date</th>';
        $htmlTable .= '<th class="text-center bg-secondary-subtle">Machine Name</th>';
        $htmlTable .= '<th class="text-center bg-secondary-subtle">Process</th>';
        $htmlTable .= '<th class="text-center bg-secondary-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-secondary-subtle">Start Time</th>';
        $htmlTable .= '<th class="text-center bg-secondary-subtle">End Time</th>';
        $htmlTable .= '<th class="text-center bg-secondary-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->machine_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->process_name . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->empqty . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->starttime . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->endtime . '</td>';
                $htmlTable .= '<td class="text-center">' . date('H:i:s', strtotime($detail->wrk_hrs)) . '</td>';
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
        $htmlTable .= '<div class="col-md-4"><span class="text-danger">Total Work Hrs: ' . $monthlyTotalWorkHrs . '</span></div>';
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
  
  
  // total work hrs report
  
    public function Emptotalwrk(Request $request){
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $Emp_id = $request->input('emp_name');
       
        $daily_summary = \DB::select("select emp.type,emp.machine_name,emp.product_name,emp.process_name,emp.empqty,emp.type, date(emp.yr_month) as date,CONCAT(LEFT(MONTHNAME(date(emp.yr_month)),3), '-', year(date(emp.yr_month))) as month_yr, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT
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
        hr_employee_t.employee_id
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
       
(
    SELECT
        m_product_type_t.product_type
    FROM
        m_product_type_t
    WHERE
        m_product_type_t.product_type_id = t.variant_id
) AS prd_type

FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
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
date(w_jobcard_process_details_t.process_start_date) as yr_month,   

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
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
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
        w_machine_equipments_hdr_t.machine_id =w_jobcard_process_details_t.machine_id
    LIMIT 1
) AS range_to,

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
        m_products_t.product_variant_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS variant_id,
(
    SELECT
        m_products_t.product_pack_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS p_pack_id,
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
   '' AS product_name,
   'SUBACT' AS plan_no,
   'SUB' AS type,
   date(w_jobactivity_lines_t.start_datetime) as yr_month,
   '' AS plan_reference_id,
   '' AS production_qty,
   '' AS plan_qty,
   '' AS product_id,
   '' AS job_qty,
   '' AS batch_no,
   '' AS job_process,
   '' AS range_to,
   'MANUAL' AS machine_name,
   '' AS variant_id,
   '' AS p_pack_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1),'%H:%i') AS starttime,
    time_format(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1),'%H:%i') AS endtime,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.employee_id AS job_assigned_name,
    '' AS prd_type,
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
            hr_employee_t.employee_id
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
     date(w_jobcard_hdr_t.job_date) as yr_month,
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
    w_jobcard_hdr_t.product_id,
        
    w_qa_submitstage_trx_t.production_qty as job_qty,
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
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,

    '' as variant_id,
    '' AS p_pack_id,
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' AND v1.job_no LIKE '%jobpr%') emp 
        WHERE emp.job_assigned_name ='$Emp_id'  GROUP BY emp.type,emp.machine_name,emp.process_name,emp.product_name, emp.yr_month,emp.type order by emp.yr_month ASC");

    

    $htmlTable = '<table id="machineSummary" class="display dataTable" style="width:100%; font-family: \'Saira Semi Condensed\', sans-serif;">';

// Store month-year as keys to avoid repetition
$uniqueMonthYears = [];

// Populate table rows with unique month-year values
foreach ($daily_summary as $value) {
    $monthYear = $value->month_yr;
    if (!isset($uniqueMonthYears[$monthYear])) {
        $uniqueMonthYears[$monthYear] = true;
        $monthlyTotalWorkHrsSeconds = 0;

        // Start the row with a button to toggle visibility
        $htmlTable .= '<tr>';
        $htmlTable .= '<td><button class="btn btn-info accordion-toggle" style="text-align: center; cursor: pointer;background:#af67dd;">' . $monthYear . '</button></td>';
        $htmlTable .= '</tr>';

        // Additional row for details, initially hidden
        $htmlTable .= '<tr class="accordion-details" data-month-year="' . $monthYear . '" style="display: none;"><td colspan="1">';
        $htmlTable .= '<table id="popuptbl_' . $monthYear . '" class="display dataTable" style="width:100%; display:none;"><thead><tr class="sticky-row"><th style="text-align: center;background: #ffa7dc">Date</th><th style="text-align: center;background: #ffa7dc">Type</th><th style="text-align: center;background: #ffa7dc">Machine Name</th><th style="text-align: center;background: #ffa7dc">Process</th><th style="text-align: center;background: #ffa7dc">Product Name</th><th style="text-align: center;background: #ffa7dc">Qty</th><th style="text-align: center;background: #ffa7dc">Work Hrs</th></tr></thead><tbody>';

        // Iterate through details for each month
        foreach ($daily_summary as $key => $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr class="accordion-toggle"><td style="text-align: center; cursor: pointer;width: 63px;">' . $detail->date . '</td><td style="text-align: center;">' . ($detail->type)  . '</td><td style="text-align: center;">' . ($detail->machine_name)  . '</td><td style="text-align: center;">' . ($detail->process_name)  . '</td><td style="text-align: center;">' . ($detail->product_name)  . '</td><td style="text-align: center;">' . ($detail->empqty)  . '</td><td style="text-align: center;">' . ($detail->wrk_hrs ?? '0') . '</td></tr>';
            
                
                // Splitting hours, minutes, and seconds and converting to seconds
                list($hours, $minutes, $seconds) = explode(':', $detail->wrk_hrs ?? '0');
                $monthlyTotalWorkHrsSeconds += $hours * 3600 + $minutes * 60 + $seconds;
            }
        }

        // Formatting seconds into HH:MM:SS
        $hours = floor($monthlyTotalWorkHrsSeconds / 3600);
        $minutes = floor(($monthlyTotalWorkHrsSeconds % 3600) / 60);
        $seconds = $monthlyTotalWorkHrsSeconds % 60;
        $monthlyTotalWorkHrs = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        // Display monthly totals
        $htmlTable .= '<h5 style="text-align: end;font-weight: 800;">Total Work Hrs: ' . $monthlyTotalWorkHrs . '</h5>';

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
  
    
}
