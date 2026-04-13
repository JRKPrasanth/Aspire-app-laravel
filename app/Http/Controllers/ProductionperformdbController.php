<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductionperformdbController extends Controller
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

        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');
        $this->data['employee_name'] = $this->jcustomselecttool('hr_employee_t', 'employee_id', 'first_name', ''," and `group_type` = '8' and active='Yes'");
        $Emp_name = $request->input('emp_name');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

         if($Emp_name !=''){
        $employe= \DB::select("SELECT first_name FROM hr_employee_t WHERE employee_id ='$Emp_name'");
        
        $this->data['employee'] = $employe[0]->first_name;
         $employee = $employe[0]->first_name;
         }else{
             
             $this->data['employee'] ='';
              $employee = '';
         }

        //  type wise summary table
        
       
       if (!empty($Emp_name)) {
           
         
     //  type wise summary 
     
        $this->data['product_summary'] = \DB::select("select emp.prd_type,emp.yr_month,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs,
     CASE WHEN SEC_TO_TIME(720000) > SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) THEN
    TIMEDIFF(SEC_TO_TIME(720000), SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime))))) ELSE '00:00:00' END AS idle_hrs from (SELECT  *
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
            hr_employee_t.first_name
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
            CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
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
    ) AS v1)emp   WHERE 1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' 
 AND emp.job_assigned_name ='$employee' GROUP BY emp.prd_type,emp.yr_month ORDER BY SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");


        //  machine_summary table

        $this->data['machine_summary'] = \DB::select("select emp.machine_name,emp.yr_month,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs,
     CASE WHEN SEC_TO_TIME(720000) > SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) THEN
    TIMEDIFF(SEC_TO_TIME(720000), SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime))))) ELSE '00:00:00' END AS idle_hrs from (SELECT *
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
            hr_employee_t.first_name
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
            CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
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
    ) AS v1)emp   WHERE 1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' 
 AND emp.job_assigned_name ='$employee' GROUP BY emp.machine_name,emp.yr_month  ORDER BY SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");

		 //  dd($this->data['product_summary']);
        //  pack wise summary 
        
              //  pack wise summary 
        
        $this->data['type_summary'] = \DB::select("select emp.varient_name,emp.yr_month,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs,
     CASE WHEN SEC_TO_TIME(720000) > SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) THEN
    TIMEDIFF(SEC_TO_TIME(720000), SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime))))) ELSE '00:00:00' END AS idle_hrs from (SELECT *
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
            hr_employee_t.first_name
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
            CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
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
    ) AS v1)emp   WHERE 1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' 
 AND emp.job_assigned_name ='$employee' GROUP BY emp.varient_name,emp.yr_month ORDER BY SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");


    // product name summary
    
    $this->data['name_summary'] = \DB::select("select emp.product_name,emp.yr_month,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs,
     CASE WHEN SEC_TO_TIME(720000) > SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) THEN
    TIMEDIFF(SEC_TO_TIME(720000), SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime))))) ELSE '00:00:00' END AS idle_hrs from (SELECT *
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
            hr_employee_t.first_name
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
            CONCAT(LEFT(MONTHNAME(date(w_jobcard_hdr_t.job_date)),3), '-', year(date(w_jobcard_hdr_t.job_date))) as yr_month,
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
    ) AS v1)emp   WHERE 1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' 
 AND emp.job_assigned_name ='$employee' GROUP BY emp.product_name,emp.yr_month  ORDER BY SUBSTRING(yr_month, -2) + 0 ASC, STR_TO_DATE(CONCAT('01-', yr_month), '%d-%b-%y') ASC");
 

            }
            
        return view('productiondashboard.productionperformance', $this->data);
        
    }
    
    // for popup purpose querys

    
    public function Emproductdtls(Request $request){
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $Emp_id = $request->input('emp_name');
    $product = $request->input('prodct');
       
       if($product == "DR.JRK'S 777"){
           
           $product = addslashes($product);
       }
       
       if($Emp_id !=''){
           
           $wh= " AND emp.job_assigned_name ='$Emp_id' AND emp.prd_type = '$product'";
       }
           
       
       
       $daily_summary = \DB::select("select emp.machine_name,emp.month_yr,emp.emp_qty,date(emp.job_date) as date,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs from (SELECT *

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
            hr_employee_t.employee_id
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
        1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' $wh
         GROUP BY emp.machine_name,emp.month_yr order by emp.month_yr ASC");
      
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
    $Emp_id = $request->input('emp_name');
    $machine = $request->input('machine');
       
       
        $daily_summary = \DB::select("select emp.product_name,emp.month_yr,emp.emp_qty,date(emp.job_date) as date,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs
 from (SELECT
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
            hr_employee_t.employee_id
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
        1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' AND emp.job_assigned_name ='$Emp_id' AND emp.machine_name ='$machine'
         GROUP BY emp.product_name,emp.month_yr order by emp.month_yr ASC");

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
    $Emp_id = $request->input('emp_name');
    $category = $request->input('category');
       
           
            $daily_summary = \DB::select("select emp.machine_name,emp.product_name,emp.month_yr,emp.emp_qty,date(emp.job_date) as date,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs from (SELECT
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
            hr_employee_t.employee_id
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
        1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' AND emp.job_assigned_name ='$Emp_id' AND emp.varient_name ='$category'
         GROUP BY emp.machine_name,emp.product_name,emp.month_yr order by emp.month_yr ASC");


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
    

    public function Emppronamedtls(Request $request){
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $Emp_id = $request->input('emp_name');
    $product = $request->input('product');
       
    $daily_summary = \DB::select("select emp.machine_name,emp.month_yr,emp.emp_qty,date(emp.job_date) as date,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs from (SELECT
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
            hr_employee_t.employee_id
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
        1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' AND emp.job_assigned_name ='$Emp_id' AND emp.product_name='$product'
         GROUP BY emp.machine_name,emp.month_yr order by emp.month_yr ASC");
     
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
        $htmlTable .= '<th class="text-center bg-info-subtle">Qty</th>';
        $htmlTable .= '<th class="text-center bg-info-subtle">Work Hrs</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($daily_summary as $detail) {
            if ($detail->month_yr == $monthYear) {
                $htmlTable .= '<tr>';
                $htmlTable .= '<td class="text-center">' . $detail->date . '</td>';
                $htmlTable .= '<td class="text-center">' . $detail->machine_name . '</td>';
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
        $htmlTable .= '<div class="col-md-4"><span class="text-danger">Total Work Hrs: ' . $monthlyTotalWorkHrs . '</span></div>';
        $htmlTable .= '</div>'; // .row

        $htmlTable .= '</div>'; // .table-responsive
        $htmlTable .= '</div>'; // .collapse
        $htmlTable .= '</td></tr>';
    }
}

$htmlTable .= '</table>';

  } 
  

}
