<?php

namespace App\Http\Controllers;
use session;    
use Illuminate\Http\Request;

class ProductiondashboardController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    
    public function Analyseproduction(Request $request)
    {
        
        $wh='';

          $this->data['pro_name'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', ''," and  `product_group_id` = '4' ");
          $this->data['pro_subcat'] = $this->jcustomselecttool('m_product_subcategory_t', 'product_subcategory_id', 'subcategory_name', ''," and  `product_group_id` = '4' ");
          $this->data['pro_type'] = $this->jcomboprotypejoinselect('m_product_type_t', 'product_type_id', 'product_type', 'm_products_t','product_type_id','product_type_id','',''," and `product_group_id` = '4' ");
          $this->data['pro_varient'] = $this->jcustommultiselect('m_product_variants_t', 'product_variant_id', 'product_variant_name', '','');
    
            $start_date = $request->input('start_date');
            $end_date =   $request->input('end_date');
            $pro_name =   $request->input('pro_name');
            $pro_type =   $request->input('pro_type');
            $pro_subcat =   $request->input('pro_subcat');
            $pro_variant =   $request->input('pro_varient');
        
            if(is_array($pro_variant) !=''){
         
            $pro_variant = array_map('intval', $pro_variant);
            $pro_variant_imp_vals = implode(',', $pro_variant);
            $wh .= ' and emp.varient_id IN ('.$pro_variant_imp_vals.')';
           }
           
        if($pro_type !=''){
           
            $wh = " and emp.type_id = '$pro_type' ";
        }
        if($pro_name !=''){
            
            $wh = " and emp.p_product_id = '$pro_name' ";
        }
        if($pro_subcat !=''){
            
            $wh = " and emp.product_subcategory_id = '$pro_subcat' ";
        }
    
      if (!empty($start_date)) {
        
                 $this->data['product_summary'] = \DB::select("select emp.prd_type,emp.batch_no,emp.pack_name,ROUND(emp.emp_qty ,2) as empqty,CONCAT(LEFT(MONTHNAME(date(emp.job_date)),3), '-', year(date(emp.job_date))) as yr_month from (SELECT
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
            hr_employee_t.group_type
        FROM
            hr_employee_t
        WHERE
            hr_employee_t.employee_id = t.qa_job_assigned_to
    ) AS group_type,
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
            m_product_subcategory_t.product_subcategory_id
        FROM
            m_product_subcategory_t
        WHERE
            m_product_subcategory_t.product_subcategory_id = t.subcat_id
      ) AS product_subcategory_id,
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
            m_products_t.product_subcategory_id
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
      ) AS subcat_id,
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
    ) AS v1) emp 
    WHERE
        1 = 1   $wh AND emp.job_date BETWEEN '$start_date' AND '$end_date' AND emp.group_type='8' GROUP BY emp.prd_type,emp.batch_no,emp.pack_name,emp.job_date order by emp.job_date ASC");

  }
        return view('productiondashboard.productionanalyserpt', $this->data);
   
  }
  
     // analyze report popup
 
    public function Analysepopup(Request $request){
               
      
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $prd_type= $request->input('prd_type');
    $batch = $request->input('batch');
    
           if($prd_type == "DR.JRK'S 777"){
           
           $prd_type = addslashes($prd_type);
       }
    
        $daily_summary = \DB::select("select emp.qa_assigned_name,date(emp.job_date) as date,emp.prd_type,emp.batch_no,emp.pack_name,ROUND(emp.emp_qty ,2) as empqty,CONCAT(LEFT(MONTHNAME(date(emp.job_date)),3), '-', year(date(emp.job_date))) as yr_month,SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(emp.endtime, emp.starttime)))) AS  wrk_hrs from (SELECT
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
    ) AS group_type,
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
    ) AS v1) emp 
    WHERE
        1 = 1 AND emp.job_date BETWEEN '$start_date' AND '$end_date' AND emp.job_no LIKE '%jobpr%' AND emp.prd_type='$prd_type' AND emp.batch_no='$batch' GROUP BY emp.job_date,qa_assigned_name,emp.prd_type,emp.batch_no,emp.pack_name order by emp.job_date ASC");

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
                $htmlTable .= '<td class="text-center">' .  date('H:i:s', strtotime($wrk_hrs)) . '</td>';
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

}
