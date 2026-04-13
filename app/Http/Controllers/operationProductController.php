<?php

namespace App\Http\Controllers;
use session;    
use Illuminate\Http\Request;

class operationProductController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    
    public function Productbase(Request $request)
    {
        
        $this->data['pro_name'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', ''," and (`product_group_id` = '1' OR `product_group_id` = '2') ");
        $this->data['pro_type'] = $this->jcomboprotypejoinselect('m_product_type_t', 'product_type_id', 'product_type', 'm_products_t','product_type_id','product_type_id','',''," and (`product_group_id` = '1' OR `product_group_id` = '2') ");
         
        $start_date = $request->input('start_date');
        $end_date =   $request->input('end_date');
        $pro_name =   $request->input('pro_name');
        $pro_type =   $request->input('pro_type');

        if($pro_name !=''){
            
            $wh = " WHERE emp.prd_id = '$pro_name'";
            
        }else{
            
            $wh = " WHERE emp.prd_type = '$pro_type' ";
        }

      if (!empty($start_date)) {
        
                 $this->data['product_summary'] = \DB::select("select emp.qa_assigned_name,emp.product_name,emp.yr_month, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs, subtime(SEC_TO_TIME(720000),SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours)))) idle_hours from (SELECT * FROM
    (
    SELECT t.*,
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
        m_product_type_t.product_type_id
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
        m_products_t.product_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS prd_id,
'MAJOR' AS type,
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
   '' AS prd_id,
   'SUB' AS type,
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
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    hr_employee_t.first_name AS qa_assigned_name,
    '' AS prd_type
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  '$start_date' and '$end_date' 

union all

SELECT * FROM
        (
        SELECT t.*,
        (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.qa_job_assigned_to
    ) AS qa_assigned_name

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
            m_products_t.product_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS prd_id,
    'MAJOR' as type,
    CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
    w_jobcard_hdr_t.product_id,
    w_qa_submitstage_trx_t.production_qty as job_qty,
    w_jobcard_hdr_t.job_completion_date,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.job_process,
    '' as machine_time,
    '' as pack_id,
    '' as variant_id,
    '' AS prd_type,
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
    ) AS v1
    WHERE
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date') emp $wh GROUP BY emp.qa_assigned_name,emp.product_name,emp.yr_month HAVING emp.qa_assigned_name!='0' ORDER BY SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");
    
    // machine summary
    $this->data['machine_summary'] = \DB::select("select emp.machine_name,emp.yr_month, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs, subtime(SEC_TO_TIME(720000),SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours)))) idle_hours from (SELECT * FROM
    (
    SELECT t.*,
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
        m_product_type_t.product_type_id
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
        m_products_t.product_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS prd_id,
'MAJOR' AS type,
CONCAT(LEFT(MONTHNAME(date(w_jobcard_process_details_t.process_start_date)),3), '-', year(date(w_jobcard_process_details_t.process_start_date))) as yr_month,               
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
w_jobcard_hdr_t.job_completion_date,
w_jobcard_hdr_t.batch_no,
w_jobcard_hdr_t.job_process,
w_jobcard_process_details_t.machine_time,
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
   '' AS product_name,
   '' AS prd_id,
   'SUB' AS type,
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   '' AS product_id,
   '' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS job_process,
   '' AS machine_time,
   'MANUAL' AS machine_name,
   '' AS pack_id,
   '' AS variant_id,
   '' AS product_type_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    hr_employee_t.first_name AS qa_assigned_name,
    '' AS prd_type
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  '$start_date' and '$end_date' 

union all

SELECT * FROM
        (
        SELECT t.*,
        (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.qa_job_assigned_to
    ) AS qa_assigned_name

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
            m_products_t.product_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS prd_id,
    'MAJOR' as type,
    CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
    w_jobcard_hdr_t.product_id,
    w_qa_submitstage_trx_t.production_qty as job_qty,
    w_jobcard_hdr_t.job_completion_date,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.job_process,
    '' as machine_time,
        (
        SELECT
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,
    '' as pack_id,
    '' as variant_id,
    '' AS prd_type,
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
    ) AS v1
    WHERE
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date') emp $wh GROUP BY emp.machine_name ORDER BY SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");
        
        // target wise report
        
        $this->data['target_summary'] = \DB::select("select emp.job_assigned_name,emp.machine_name,emp.product_name,emp.empqty,emp.range_to,emp.process_name,emp.yr_month, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs,    sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(emp.std_hrs, '.', 1),2,0),
    RPAD(SUBSTRING_INDEX(emp.std_hrs, '.', -1),2,0),00)))) as std_hrs, SEC_TO_TIME(
        SUM(
            TIME_TO_SEC(
                MAKETIME(
                    LPAD(SUBSTRING_INDEX(emp.std_hrs, '.', 1), 2, '0'),
                    RPAD(SUBSTRING_INDEX(emp.std_hrs, '.', -1), 2, '0'),
                    0
                )
            )
        ) - SUM(TIME_TO_SEC(emp.working_hours))
    ) AS hrs_diff  from (SELECT * FROM
    (
    SELECT t.*,
    (
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.qa_job_assigned_to
) AS job_assigned_name,
(
    SELECT
        m_product_type_t.product_type_id
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
        m_products_t.product_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS prd_id,
'MAJOR' AS type,
CONCAT(LEFT(MONTHNAME(date(w_jobcard_process_details_t.process_start_date)),3), '-', year(date(w_jobcard_process_details_t.process_start_date))) as yr_month,               
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
   '' AS product_name,
   '' AS prd_id,
   'SUB' AS type,
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   '' AS product_id,
   '' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS job_process,
   '' AS machine_time,
   'MANUAL' AS machine_name,
   '' AS std_hrs,
   '' AS range_to,
   '' AS pack_id,
   '' AS variant_id,
   '' AS product_type_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    hr_employee_t.first_name AS job_assigned_name,
    '' AS prd_type
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  '$start_date' and '$end_date' 

union all

SELECT * FROM
        (
        SELECT t.*,
        (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.qa_job_assigned_to
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
            m_products_t.concatenated_product
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS product_name,
            (
        SELECT
            m_products_t.product_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS prd_id,
    'MAJOR' as type,
    CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
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
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,
    '' as pack_id,
    '' as variant_id,
    '' AS prd_type,
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
    ) AS v1
    WHERE
        1 = 1 AND v1.job_date BETWEEN '$start_date' AND '$end_date') emp 
        $wh GROUP BY emp.job_assigned_name,emp.machine_name,emp.process_name,emp.product_name,emp.empqty,emp.range_to,emp.yr_month ORDER BY SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");
        
  }
        return view('operationperformancelog.productbased', $this->data);
   
  }
    
    //emp process wise popup

   public function Producttype(Request $request){
           
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $emp_name = $request->input('emp_name');
    $product = $request->input('product');

    
        $daily_summary = \DB::select("select emp.machine_name,emp.product_name,emp.empqty,emp.process_name,emp.type, date(emp.job_date) as date,CONCAT(LEFT(MONTHNAME(date(emp.job_date)),3), '-', year(date(emp.job_date))) as month_yr, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT * FROM
    (
    SELECT t.*,
    (
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.qa_job_assigned_to
) AS job_assigned_name,
(
    SELECT
        m_product_type_t.product_type_id
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
        m_products_t.product_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS prd_id,
'MAJOR' AS type,
CONCAT(LEFT(MONTHNAME(date(w_jobcard_process_details_t.process_start_date)),3), '-', year(date(w_jobcard_process_details_t.process_start_date))) as yr_month,               
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
   '' AS product_name,
   '' AS prd_id,
   'SUB' AS type,
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   '' AS product_id,
   '' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS job_process,
   '' AS machine_time,
   'MANUAL' AS machine_name,
   '' AS std_hrs,
   '' AS range_to,
   '' AS pack_id,
   '' AS variant_id,
   '' AS product_type_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    hr_employee_t.first_name AS job_assigned_name,
    '' AS prd_type
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  '$start_date' and '$end_date' 

union all

SELECT * FROM
        (
        SELECT t.*,
        (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.qa_job_assigned_to
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
            m_products_t.concatenated_product
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS product_name,
            (
        SELECT
            m_products_t.product_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS prd_id,
    'MAJOR' as type,
    CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
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
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,
    '' as pack_id,
    '' as variant_id,
    '' AS prd_type,
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
    ) AS v1)emp
        WHERE emp.job_date BETWEEN '$start_date' AND '$end_date' AND emp.job_assigned_name ='$emp_name' AND emp.product_name ='$product' GROUP BY emp.job_date,emp.machine_name,emp.product_name,emp.process_name,emp.type order by emp.job_date ASC");

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
        $htmlTable .= '<th class="text-center bg-danger-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-danger-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->process_name . '</td>';
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
    
     public function Machinepopup(Request $request){
               
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $product = $request->input('pro_name');
    $prd_type= $request->input('pro_type');
    $machine = $request->input('machine');

    if($product !=''){
        
        $wh = " AND emp.product_name ='$product'";
        
    }else{
        
        $wh = " AND emp.prd_type = '$prd_type'";
    }
    
        $daily_summary = \DB::select("select emp.job_assigned_name,emp.product_name,emp.empqty,emp.process_name, date(emp.job_date) as date,CONCAT(LEFT(MONTHNAME(date(emp.job_date)),3), '-', year(date(emp.job_date))) as month_yr, SEC_TO_TIME( SUM( TIME_TO_SEC( emp.working_hours ) ) ) wrk_hrs from (SELECT * FROM
    (
    SELECT t.*,
    (
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.qa_job_assigned_to
) AS job_assigned_name,
(
    SELECT
        m_product_type_t.product_type_id
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
        m_products_t.product_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS prd_id,
'MAJOR' AS type,
CONCAT(LEFT(MONTHNAME(date(w_jobcard_process_details_t.process_start_date)),3), '-', year(date(w_jobcard_process_details_t.process_start_date))) as yr_month,               
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
   '' AS product_name,
   '' AS prd_id,
   'SUB' AS type,
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   '' AS product_id,
   '' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS job_process,
   '' AS machine_time,
   'MANUAL' AS machine_name,
   '' AS std_hrs,
   '' AS range_to,
   '' AS pack_id,
   '' AS variant_id,
   '' AS product_type_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(subtime(time(SUBSTRING_INDEX(w_jobactivity_lines_t.end_datetime,'.',1)),time(SUBSTRING_INDEX(w_jobactivity_lines_t.start_datetime,'.',1))),'%H:%i') as working_hours,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    hr_employee_t.first_name AS job_assigned_name,
    '' AS prd_type
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  '$start_date' and '$end_date' 

union all

SELECT * FROM
        (
        SELECT t.*,
        (
        SELECT
            hr_employee_t.first_name
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.qa_job_assigned_to
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
            m_products_t.concatenated_product
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS product_name,
            (
        SELECT
            m_products_t.product_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS prd_id,
    'MAJOR' as type,
    CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
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
            w_machine_hdr_t.machine_name
        FROM
            w_machine_hdr_t
        WHERE
            w_machine_hdr_t.machine_hdr_id = w_jobcard_hdr_t.machine_hdr_id
    ) AS machine_name,
    '' as pack_id,
    '' as variant_id,
    '' AS prd_type,
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
    ) AS v1)emp
        WHERE emp.job_date BETWEEN '$start_date' AND '$end_date' AND emp.machine_name ='$machine' $wh GROUP BY emp.job_date,emp.job_assigned_name,emp.product_name,emp.empqty,emp.process_name order by emp.job_date ASC");

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
        $htmlTable .= '<th class="text-center bg-warning-subtle">Date</th>';
        $htmlTable .= '<th class="text-center bg-warning-subtle">Employee Name</th>';
        $htmlTable .= '<th class="text-center bg-warning-subtle">Process</th>';
        $htmlTable .= '<th class="text-center bg-warning-subtle">Product Name</th>';
        $htmlTable .= '<th class="text-center bg-warning-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-warning-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->job_assigned_name . '</td>';
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
 
}