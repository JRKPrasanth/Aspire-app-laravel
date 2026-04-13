<?php

namespace App\Http\Controllers;
use session;    
use Illuminate\Http\Request;

class OveralloperationperformancerptController extends Controller
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
        $this->data['pageMethod'] = \Request::route()->getName();
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $machine_name = $request->input('machine_name');
        
        $this->data['machine_name'] = $this->jcustomselecttool('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', ''," and `machine_code` LIKE '%/EQ/%'");
    
    if($machine_name !=''){
       $this->data['machine'] = \DB::select("select machine_name from w_machine_hdr_t where machine_hdr_id='$machine_name'");
    }else{
          $this->data['machine'] = [];
            $this->data['machine'][0] = (object) ['machine_name' => 'No Machine Selected'];
    }
        //dd($this->data['machine']);
        if (!empty($machine_name)) {
        //  type wise summary 
     
     $this->data['product_summary'] = \DB::select("select emp.prd_type as job_assigned_name,SUM(emp.job_qty) as job_qty, emp.yr_month,emp.type, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs  from (SELECT
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
'MAJOR' AS type,       
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
   '0' AS job_qty,
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
    'SUB' AS type,
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
     'MAJOR' AS type,
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
WHERE emp.job_assigned_name ='10' and emp.prd_type !='' GROUP BY emp.prd_type,emp.type, emp.yr_month ORDER BY SUBSTRING(yr_month, -2) + 0 ASC,STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");

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

// machine wise

     $this->data['machine_summary'] = \DB::select("select emp.job_assigned_name, emp.yr_month,emp.type, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs  from (SELECT
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
        w_machine_hdr_t.machine_hdr_id
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
    hr_employee_t.first_name AS job_assigned_name,
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
            hr_employee_t.first_name
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
            w_machine_hdr_t.machine_hdr_id
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' ) emp 
WHERE emp.machine_name ='$machine_name' and emp.job_assigned_name !='' GROUP BY emp.job_assigned_name, emp.yr_month,emp.type ORDER BY SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");

    }
    
    return view('operationperformancelog.overallrpt', $this->data);

    }
    
       // employee date wise hrs popup rpt
        
     public function employeehrspopup(Request $request){
           
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

// Start building the HTML table
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

    public function Emppropopup(Request $request){
           
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $machine_id = $request->input('machine');
	$employee = $request->input('employee');
   // dd($end_date);
       
        $daily_summary = \DB::select("select emp.product_name,emp.empqty,date(emp.yr_month) as date,CONCAT(LEFT(MONTHNAME(date(emp.yr_month)),3), '-', year(date(emp.yr_month))) as month_yr,SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT
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
        w_machine_hdr_t.machine_hdr_id
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
   '' AS plan_qty,
   '' AS product_id,
   '0' AS job_qty,
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
    hr_employee_t.first_name AS job_assigned_name,
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
            hr_employee_t.first_name
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
            w_machine_hdr_t.machine_hdr_id
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' ) emp 
        WHERE emp.machine_name='$machine_id' AND emp.job_assigned_name ='$employee'  GROUP BY emp.machine_name,emp.product_name, emp.yr_month,emp.type order by emp.yr_month ASC");

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
    
 
 //emp process wise popup


  public function Emprocess(Request $request){
           
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $type = $request->input('type');
    $employee = $request->input('employeeName');
    //dd($end_date);
       
        $daily_summary = \DB::select("select emp.machine_name,emp.product_name,emp.empqty,emp.process_name,emp.type, date(emp.yr_month) as date,CONCAT(LEFT(MONTHNAME(date(emp.yr_month)),3), '-', year(date(emp.yr_month))) as month_yr, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT
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
    hr_employee_t.first_name AS job_assigned_name,
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
            hr_employee_t.first_name
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
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date' ) emp 
        WHERE emp.type='$type' AND emp.job_assigned_name ='$employee'  GROUP BY emp.machine_name,emp.product_name, emp.yr_month,emp.process_name,emp.type order by emp.yr_month ASC");

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
        $htmlTable .= '<th class="text-center bg-success-subtle">Date</th>';
        $htmlTable .= '<th class="text-center bg-success-subtle">Process</th>';
        $htmlTable .= '<th class="text-center bg-success-subtle">Machine Name</th>';
        $htmlTable .= '<th class="text-center bg-success-subtle">Product Name</th>';
        $htmlTable .= '<th class="text-center bg-success-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-success-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->process_name . '</td>';
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
 
}