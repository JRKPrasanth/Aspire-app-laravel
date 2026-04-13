<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class MachinemaintenancelogController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    public function machinelogdtsindex(Request $request)
    {

        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');

        $machine_name = $request->input('machine_name');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        
        
        $this->data['machine_name'] = $this->jcustomselecttool('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', ''," and `machine_code` LIKE '%/EQ/%'");


        $start_month = date('Y-m', strtotime($start_date));
        $end_month = date('Y-m', strtotime($end_date));

        //    dd($end_month);

        //  type wise summary table
        $this->data['prim_summary'] = \DB::select("SELECT
        ROUND(SUM(b_machine_log_t.quantity),0) AS total_quantity,
        sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00)))) as total_running_hours, 
        w_machine_hdr_t.machine_name,
      round(SUM(b_machine_log_t.quantity) / sum((CAST(LEFT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) + CAST(RIGHT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) / 60)),0) AS opt_hrs,
       subtime(sec_to_time((200*60)*60) , sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))) AS total_idle_hours,
        CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
        MONTH(b_machine_log_t.date)  log_months,
        YEAR(b_machine_log_t.date)  log_year,
        process_dept,
        machine_id
    FROM
        b_machine_log_t INNER JOIN w_machine_hdr_t
        ON b_machine_log_t.machine_id = w_machine_hdr_t.machine_hdr_id
    WHERE
        DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
        AND b_machine_log_t.machine_id = '$machine_name' 
    GROUP BY
        machine_id, log_month
        
         union all 
        
      SELECT
        '1' AS total_quantity,
        '00:00:00' AS total_running_hours,
         w_machine_hdr_t.machine_name,
        '0' AS opt_hrs,
        time_format(SEC_TO_TIME( SUM( TIME_TO_SEC(if(time(SUBSTRING_INDEX(pm_start_time,'.',1))>time(SUBSTRING_INDEX(pm_end_time,'.',1)),subtime(time_format(addtime('12:00',time(SUBSTRING_INDEX(pm_end_time,'.',1))),'%H:%i'),time(SUBSTRING_INDEX(pm_start_time,'.',1))),time_format(subtime(time(SUBSTRING_INDEX(pm_end_time,'.',1)),time(SUBSTRING_INDEX(pm_start_time,'.',1))),'%H:%i'))))),'%H:%i:%s') as total_idle_hours,
        CONCAT(SUBSTRING(MONTHNAME(machine_pm_detail_t.done_on_date), 1, 3), '-', YEAR(machine_pm_detail_t.done_on_date) % 100) AS log_month,
        MONTH(machine_pm_detail_t.done_on_date)  log_months,
        YEAR(machine_pm_detail_t.done_on_date)  log_year,
        'PREVENTIVE' as process_dept,
         machine_id
    FROM
        machine_pm_detail_t INNER JOIN w_machine_hdr_t
        ON machine_pm_detail_t.machine_id = w_machine_hdr_t.machine_hdr_id
    WHERE
        DATE_FORMAT(machine_pm_detail_t.done_on_date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
        AND machine_pm_detail_t.machine_id = '$machine_name'
    GROUP BY
        machine_id, log_month 
        union all
   SELECT
    COUNT(b_maintenance_t.issue_date) AS total_quantity,
    '00:00:00' AS total_running_hours,
    w_machine_hdr_t.machine_name,
    '0' AS opt_hrs,
    time_format(SEC_TO_TIME( SUM( TIME_TO_SEC(if(time(SUBSTRING_INDEX(start_date,'.',1))>time(SUBSTRING_INDEX(end_date,'.',1)),subtime(time_format(addtime('12:00',time(SUBSTRING_INDEX(end_date,'.',1))),'%H:%i'),time(SUBSTRING_INDEX(start_date,'.',1))),time_format(subtime(time(SUBSTRING_INDEX(end_date,'.',1)),time(SUBSTRING_INDEX(start_date,'.',1))),'%H:%i'))))),'%H:%i:%s') as total_idle_hours,
    CONCAT(SUBSTRING(MONTHNAME(b_maintenance_t.issue_date), 1, 3), '-', YEAR(b_maintenance_t.issue_date) % 100) AS log_month,
    MONTH(b_maintenance_t.issue_date) AS log_months,
    YEAR(b_maintenance_t.issue_date) AS log_year,  
    'BREAKDOWN' AS process_dept,
    b_maintenance_t.machine_id
FROM b_maintenance_t
INNER JOIN w_machine_hdr_t ON b_maintenance_t.machine_id = w_machine_hdr_t.machine_hdr_id
WHERE
    DATE_FORMAT(b_maintenance_t.issue_date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
    AND b_maintenance_t.machine_id = '$machine_name'
GROUP BY
    b_maintenance_t.machine_id, log_month 

    order by log_year,log_months ASC");


     //  type wise summary chart
     
        $this->data['prim_sumchart'] = \DB::select("SELECT

    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS name,
    process_dept,
    MONTH(b_machine_log_t.date)  log_months,
    YEAR(b_machine_log_t.date)  log_year,
   CONCAT(HOUR(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), '.', LPAD(MINUTE(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), 1, '0')) as count
FROM
    b_machine_log_t
WHERE
    DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
    AND b_machine_log_t.machine_id = '$machine_name'
GROUP BY name ORDER BY 
    log_year,log_months ASC");


        $primData = [];
        foreach ($this->data['prim_sumchart'] as $item) {
            $primData[] = [
                'name' => $item->name,
                'value' => $item->count,
                'dept' => $item->process_dept,
            ];
        }
        // dd (json_encode ($groupData));
        $this->data['prim_sumchart'] = json_encode($primData);

        //  category summary table

        $this->data['type_summary'] = \DB::select("SELECT
    ROUND(SUM(b_machine_log_t.quantity),0) AS total_quantity,
        sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00)))) as total_running_hours, 
    m_product_variants_t.product_variant_name AS category,
    round(SUM(b_machine_log_t.quantity) / sum((CAST(LEFT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) + CAST(RIGHT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) / 60)),0) AS opt_hrs,
       subtime(sec_to_time((200*60)*60) , sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))) AS total_idle_hours,
    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
    MONTH(b_machine_log_t.date)  log_months,
    YEAR(b_machine_log_t.date)  log_year,
    machine_id
FROM
    b_machine_log_t
    INNER JOIN m_products_t ON b_machine_log_t.product_id = m_products_t.product_id
    INNER JOIN m_product_variants_t ON m_products_t.product_variant_id = m_product_variants_t.product_variant_id
WHERE
    DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
    AND b_machine_log_t.machine_id = '$machine_name'
GROUP BY
    machine_id, log_month,category order by log_year,log_months");

//dd( $this->data['type_summary']);
        //  category summary chart
        
        $this->data['type_sumchart'] = \DB::select("SELECT
     m_product_variants_t.product_variant_name AS name,
    CONCAT(HOUR(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), '.', LPAD(MINUTE(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), 1, '0')) as count,
    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
    MONTH(b_machine_log_t.date)  log_months,
    YEAR(b_machine_log_t.date)  log_year

FROM
    b_machine_log_t
    INNER JOIN m_products_t ON b_machine_log_t.product_id = m_products_t.product_id
    INNER JOIN m_product_variants_t ON m_products_t.product_variant_id = m_product_variants_t.product_variant_id
WHERE
    DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
    AND b_machine_log_t.machine_id = '$machine_name'
GROUP BY
    log_months,name order by log_year,log_months ASC");


        $typeData = [];
        foreach ($this->data['type_sumchart'] as $item) {
            $typeData[] = [
                'name' => $item->name,
                'value' => $item->count,
                'month' => $item->log_month,
            ];
        }
        // dd (json_encode ($groupData));
        $this->data['type_sumchart'] = json_encode($typeData);


        // product wise  table    
        $this->data['product_summary'] = \DB::select("
SELECT
    ROUND(SUM(b_machine_log_t.quantity),0) AS total_quantity,
        sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00)))) as total_running_hours,
    m_products_t.concatenated_product AS product_name,
    round(SUM(b_machine_log_t.quantity) / sum((CAST(LEFT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) + CAST(RIGHT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) / 60)),0) AS opt_hrs,
       subtime(sec_to_time((200*60)*60) , sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))) AS total_idle_hours,
    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
    MONTH(b_machine_log_t.date)  log_months,
    YEAR(b_machine_log_t.date)  log_year,
    machine_id
FROM
    b_machine_log_t
    LEFT JOIN w_machine_hdr_t ON b_machine_log_t.machine_id = w_machine_hdr_t.machine_hdr_id
    LEFT  JOIN m_products_t ON b_machine_log_t.product_id = m_products_t.product_id
    LEFT  JOIN i_product_packs ON m_products_t.product_pack_id = i_product_packs.packing_id
WHERE
    DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
    AND b_machine_log_t.machine_id = '$machine_name'
GROUP BY
    machine_id, log_month,product_name ORDER by log_year,log_months");

    //  product summary chart
        $this->data['product_sumchart'] = \DB::select("SELECT
	m_products_t.concatenated_product AS name,
    CONCAT(HOUR(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), '.', LPAD(MINUTE(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), 1, '0')) as count,
    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
    MONTH(b_machine_log_t.date)  log_months,
    YEAR(b_machine_log_t.date)  log_year

FROM
    b_machine_log_t
    INNER JOIN w_machine_hdr_t ON b_machine_log_t.machine_id = w_machine_hdr_t.machine_hdr_id
    INNER JOIN m_products_t ON b_machine_log_t.product_id = m_products_t.product_id
    INNER JOIN i_product_packs ON m_products_t.product_pack_id = i_product_packs.packing_id
WHERE
    DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
    AND b_machine_log_t.machine_id = '$machine_name'
GROUP BY
    name, log_month
ORDER BY
    log_year,log_months ASC");

        $productData = [];
        foreach ($this->data['product_sumchart'] as $item) {
            $productData[] = [
                'name' => $item->name,
                'value' => $item->count,
                'month' => $item->log_month,
            ];
        }
        // dd (json_encode ($groupData));
        $this->data['product_sumchart'] = json_encode($productData);

        //  product line chart
        
        $this->data['product_linechart'] = \DB::select("SELECT
	m_products_t.concatenated_product AS name,
    SUM(b_machine_log_t.quantity) AS count,
    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
    MONTH(b_machine_log_t.date)  log_months,
    YEAR(b_machine_log_t.date)  log_year

FROM
    b_machine_log_t
    INNER JOIN w_machine_hdr_t ON b_machine_log_t.machine_id = w_machine_hdr_t.machine_hdr_id
    INNER JOIN m_products_t ON b_machine_log_t.product_id = m_products_t.product_id
    INNER JOIN i_product_packs ON m_products_t.product_pack_id = i_product_packs.packing_id
WHERE
    DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
    AND b_machine_log_t.machine_id = '$machine_name'
GROUP BY
    name, log_month
ORDER BY
    log_year,log_months ASC");

        $proData = [];
        foreach ($this->data['product_linechart'] as $item) {
            $proData[] = [
                'name' => $item->name,
                'value' => $item->count,
                'month' => $item->log_month,
            ];
        }
        // dd (json_encode ($groupData));
        $this->data['product_linechart'] = json_encode($proData);


        // Breakdown summary  table  
        
        $this->data['break_summary'] = \DB::select("
 SELECT
    COUNT(b_maintenance_t.issue_date) AS total_quantity,
    '00:00:00' AS total_running_hours,
 b_maintenance_t.causes as machine_name,
  time_format(SEC_TO_TIME( SUM( TIME_TO_SEC(if(time(SUBSTRING_INDEX(start_date,'.',1))>time(SUBSTRING_INDEX(end_date,'.',1)),subtime(time_format(addtime('12:00',time(SUBSTRING_INDEX(end_date,'.',1))),'%H:%i'),time(SUBSTRING_INDEX(start_date,'.',1))),time_format(subtime(time(SUBSTRING_INDEX(end_date,'.',1)),time(SUBSTRING_INDEX(start_date,'.',1))),'%H:%i'))))),'%H:%i:%s') as total_idle_hours,
    CONCAT(SUBSTRING(MONTHNAME(b_maintenance_t.issue_date), 1, 3), '-', YEAR(b_maintenance_t.issue_date) % 100) AS log_month,
    MONTH(b_maintenance_t.issue_date)  log_months,
    YEAR(b_maintenance_t.issue_date)  log_year,
    'BREAKDOWN' as process_dept,
    b_maintenance_t.request_remark,
    b_maintenance_t.machine_id
FROM b_maintenance_t
INNER JOIN w_machine_hdr_t ON b_maintenance_t.machine_id = w_machine_hdr_t.machine_hdr_id
WHERE
    DATE_FORMAT(b_maintenance_t.issue_date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
    AND b_maintenance_t.machine_id = '$machine_name'
GROUP BY
    b_maintenance_t.machine_id, log_month order by log_year,log_months");

        //  Break down summary chart
        $this->data['break_sumchart'] = \DB::select("SELECT
         MONTHNAME(b_maintenance_t.issue_date) as name,
 FORMAT(
        SUM(
            TIME_TO_SEC(
                IF(
                    TIME(SUBSTRING_INDEX(start_date, '.', 1)) > TIME(SUBSTRING_INDEX(end_date, '.', 1)),
                    SUBTIME(
                        TIME_FORMAT(ADDTIME('12:00:00', TIME(SUBSTRING_INDEX(end_date, '.', 1))), '%H:%i'),
                        TIME(SUBSTRING_INDEX(start_date, '.', 1))
                    ),
                    SUBTIME(
                        TIME(SUBSTRING_INDEX(end_date, '.', 1)),
                        TIME(SUBSTRING_INDEX(start_date, '.', 1))
                    )
                )
            )
        ) / 3600, 
        2 
    ) AS count,
     MONTH(b_maintenance_t.issue_date)  log_months,
     YEAR(b_maintenance_t.issue_date)  log_year
       FROM
       b_maintenance_t
       WHERE
       DATE_FORMAT(b_maintenance_t.issue_date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
       AND b_maintenance_t.machine_id = '$machine_name'

       GROUP BY name ORDER BY 
         log_year,log_months ASC");


        $breakData = [];
        foreach ($this->data['break_sumchart'] as $item) {
            $breakData[] = [
                'name' => $item->name,
                'value' => $item->count,
            ];
        }
        // dd (json_encode ($groupData));
        $this->data['break_sumchart'] = json_encode($breakData);

        // preventive breakdown chart

        $this->data['preven_chart'] = \DB::select("SELECT


CONCAT(SUBSTRING(MONTHNAME(machine_pm_detail_t.done_on_date), 1, 3), '-', YEAR(machine_pm_detail_t.done_on_date) % 100) AS name,
    FORMAT(
        SUM(
            TIME_TO_SEC(
                IF(
                    TIME(SUBSTRING_INDEX(pm_start_time, '.', 1)) > TIME(SUBSTRING_INDEX(pm_end_time, '.', 1)),
                    SUBTIME(
                        TIME_FORMAT(ADDTIME('12:00:00', TIME(SUBSTRING_INDEX(pm_end_time, '.', 1))), '%H:%i'),
                        TIME(SUBSTRING_INDEX(pm_start_time, '.', 1))
                    ),
                    SUBTIME(
                        TIME(SUBSTRING_INDEX(pm_end_time, '.', 1)),
                        TIME(SUBSTRING_INDEX(pm_start_time, '.', 1))
                    )
                )
            )
        ) / 3600,
        2 
    ) AS count,
'PREVENTIVE' as process_dept,
     MONTH(machine_pm_detail_t.done_on_date)  log_months,
     YEAR(machine_pm_detail_t.done_on_date)  log_year
FROM
machine_pm_detail_t
WHERE
DATE_FORMAT(machine_pm_detail_t.done_on_date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
AND machine_pm_detail_t.machine_id = '$machine_name'
GROUP BY
name

UNION ALL

        SELECT
       CONCAT(SUBSTRING(MONTHNAME(b_maintenance_t.issue_date), 1, 3), '-', YEAR(b_maintenance_t.issue_date) % 100) AS name,
 FORMAT(
        SUM(
            TIME_TO_SEC(
                IF(
                    TIME(SUBSTRING_INDEX(start_date, '.', 1)) > TIME(SUBSTRING_INDEX(end_date, '.', 1)),
                    SUBTIME(
                        TIME_FORMAT(ADDTIME('12:00:00', TIME(SUBSTRING_INDEX(end_date, '.', 1))), '%H:%i'),
                        TIME(SUBSTRING_INDEX(start_date, '.', 1))
                    ),
                    SUBTIME(
                        TIME(SUBSTRING_INDEX(end_date, '.', 1)),
                        TIME(SUBSTRING_INDEX(start_date, '.', 1))
                    )
                )
            )
        ) / 3600, 
        2 
    ) AS count,
         'BREAKDOWN' as process_dept,
     MONTH(b_maintenance_t.issue_date)  log_months,
     YEAR(b_maintenance_t.issue_date)  log_year
       FROM
       b_maintenance_t
       WHERE
       DATE_FORMAT(b_maintenance_t.issue_date, '%Y-%m') BETWEEN '$start_month' AND '$end_month'
       AND b_maintenance_t.machine_id = '$machine_name'

       GROUP BY name ORDER BY 
         log_year,log_months ASC");


        $prevenData = [];
        foreach ($this->data['preven_chart'] as $item) {
            $prevenData[] = [
                'name' => $item->name,
                'value' => $item->count,
                'dept' => $item->process_dept,
            ];
        }
        // dd (json_encode ($groupData));
        $this->data['preven_chart'] = json_encode($prevenData);

        return view('machinemaintenancelog.machinelogdetailsrpt', $this->data);
    }
}
