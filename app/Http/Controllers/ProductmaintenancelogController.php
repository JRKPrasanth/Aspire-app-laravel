<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ProductmaintenancelogController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    public function productlog(Request $request)
    {
        $machine_name = \Session::get('griddate');
        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');

        $product_id = $request->input('product_id');
        $product_varient = $request->input('product_varient');
        $product_type = $request->input('product_type');
        $unit = $request->input('unit');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
       // dd($product_type);
        $this->data['product_id'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', '',"and (product_group_id='1' or product_group_id='4')");
        $this->data['product_type'] = $this->jcustomselecttool('m_product_type_t', 'product_type_id', 'product_type', ''," ORDER BY product_type_id ASC");
        $this->data['product_varient'] = $this->jCombologin('m_product_variants_t', 'product_variant_id', 'product_variant_name', '');
        $this->data['unit'] = $this->jCombologin('i_product_packs', 'packing_id', 'pack_name', '');


        $this->data['pro_type'] = \DB::select("SELECT m_product_type_t.product_type_id,m_product_type_t.product_type FROM m_product_type_t
  LEFT JOIN m_products_t ON m_product_type_t.product_type_id = m_products_t.product_type_id WHERE (m_products_t.product_group_id='1' or m_products_t.product_group_id='4') GROUP BY product_type ORDER BY product_type_id ASC");

        $start_month = date('Y-m', strtotime($start_date));
        $end_month = date('Y-m', strtotime($end_date));


      $condition = "";
      $name = "";
        
        if (!empty($product_id)) {
            $condition = " AND m_products_t.product_id ='$product_id'";
            $name = "m_products_t.concatenated_product as machine_name,";
        }
        
        if (!empty($product_type)) {
            $condition = " AND m_product_type_t.product_type_id='$product_type'";
            $name = "m_product_type_t.product_type as machine_name,";
        }
        
        if (!empty($product_varient)) {
            $condition = " AND m_product_variants_t.product_variant_id='$product_varient'";
            $name = "m_product_variants_t.product_variant_name as machine_name,";
        }
        
        if (!empty($product_type) && !empty($product_varient)) {
            $condition = " AND m_product_type_t.product_type_id='$product_type' AND m_product_variants_t.product_variant_id='$product_varient' ";
            $name = "m_product_type_t.product_type as machine_name,";
        }
        
        if (!empty($product_type) && !empty($product_id)) {
            $condition = " AND m_product_type_t.product_type_id='$product_type' AND m_products_t.product_id ='$product_id' ";
            $name = "m_product_type_t.product_type as machine_name,";
        }
        
        if (!empty($product_type) && !empty($product_varient) && !empty($unit)) {
            $condition = " AND m_product_type_t.product_type_id='$product_type' AND m_product_variants_t.product_variant_id='$product_varient' AND i_product_packs.packing_id='$unit'";
            $name = "m_product_type_t.product_type as machine_name,";
        }



        //  type wise summary table
        
        $this->data['prim_summary'] = \DB::select("
        SELECT
        ROUND(SUM(b_machine_log_t.quantity),0) AS total_quantity,
        sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00)))) as total_running_hours, 
        $name
        subtime(sec_to_time((200*60)*60) , sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))) AS total_idle_hours,
        round(SUM(b_machine_log_t.quantity) / sum((CAST(LEFT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) + CAST(RIGHT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) / 60)),0) AS opt_hrs,      
        CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
        MONTH(b_machine_log_t.date)  log_months,
        YEAR(b_machine_log_t.date)  log_year,
        process_dept,
       b_machine_log_t.product_id
    FROM
        b_machine_log_t LEFT JOIN m_products_t
        ON b_machine_log_t.product_id = m_products_t.product_id LEFT JOIN m_product_type_t ON
        m_products_t.product_type_id = m_product_type_t.product_type_id LEFT JOIN m_product_variants_t on 
        m_products_t.product_variant_id=m_product_variants_t.product_variant_id
        LEFT JOIN i_product_packs on m_products_t.product_pack_id=i_product_packs.packing_id 
        WHERE  DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition GROUP BY process_dept,log_month ORDER BY log_year,log_months ASC");

   //dd($this->data['prim_summary']);

     //  type wise summary chart
        $this->data['prim_sumchart'] = \DB::select("SELECT
       
        CONCAT(HOUR(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), '.', LPAD(MINUTE(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), 1, '0')) as count,
        process_dept as name,
       CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
        MONTH(b_machine_log_t.date)  log_months,
        YEAR(b_machine_log_t.date)  log_year
      
        FROM
        b_machine_log_t LEFT JOIN m_products_t
        ON b_machine_log_t.product_id = m_products_t.product_id LEFT JOIN m_product_type_t ON
        m_products_t.product_type_id = m_product_type_t.product_type_id LEFT JOIN m_product_variants_t on 
        m_products_t.product_variant_id=m_product_variants_t.product_variant_id
        LEFT JOIN i_product_packs on m_products_t.product_pack_id=i_product_packs.packing_id 
        WHERE  DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition  GROUP BY process_dept,log_month ORDER BY log_year,log_months ASC ");
    //dd ($this->data['prim_sumchart']);
    
            $primData = [];
          
            foreach ($this->data['prim_sumchart'] as $item) {
                $primData[] = [
                    'name' => $item->name,
                    'value' => $item->count,
                    'month' => $item->log_month,

                ];
            }
      
          $this->data['prim_sumchart'] = json_encode($primData);

        //  Category summary table

        $this->data['type_summary'] = \DB::select("SELECT
    ROUND(SUM(b_machine_log_t.quantity),0) AS total_quantity,
    sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00)))) as total_running_hours, 
        m_product_variants_t.product_variant_name AS category,
        subtime(sec_to_time((200*60)*60) , sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))) AS total_idle_hours,
        round(SUM(b_machine_log_t.quantity) / sum((CAST(LEFT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) + CAST(RIGHT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) / 60)),0) AS opt_hrs,  
    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
    MONTH(b_machine_log_t.date)  log_months,
    YEAR(b_machine_log_t.date)  log_year,
    b_machine_log_t.product_id
   FROM
        b_machine_log_t LEFT JOIN m_products_t
        ON b_machine_log_t.product_id = m_products_t.product_id LEFT JOIN m_product_type_t ON
        m_products_t.product_type_id = m_product_type_t.product_type_id LEFT JOIN m_product_variants_t on 
        m_products_t.product_variant_id=m_product_variants_t.product_variant_id
        LEFT JOIN i_product_packs on m_products_t.product_pack_id=i_product_packs.packing_id 
   WHERE
    DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition GROUP BY category, log_month order by log_year,log_months ASC");


        //  category summary chart
        
        $this->data['type_sumchart'] = \DB::select("SELECT
        
  CONCAT(HOUR(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), '.', LPAD(MINUTE(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), 1, '0')) as count,
    m_product_variants_t.product_variant_name AS name,
    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
    MONTH(b_machine_log_t.date)  log_months,
    YEAR(b_machine_log_t.date)  log_year

FROM
        b_machine_log_t LEFT JOIN m_products_t
        ON b_machine_log_t.product_id = m_products_t.product_id LEFT JOIN m_product_type_t ON
        m_products_t.product_type_id = m_product_type_t.product_type_id LEFT JOIN m_product_variants_t on 
        m_products_t.product_variant_id=m_product_variants_t.product_variant_id
        LEFT JOIN i_product_packs on m_products_t.product_pack_id=i_product_packs.packing_id 
WHERE
    DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition GROUP BY log_month order by log_year,log_months ASC");


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
        m_products_t.concatenated_product AS product_name,
        sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00)))) as total_running_hours, 
        subtime(sec_to_time((200*60)*60) , sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))) AS total_idle_hours,
        round(SUM(b_machine_log_t.quantity) / sum((CAST(LEFT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) + CAST(RIGHT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) / 60)),0) AS opt_hrs, 
        CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
        MONTH(b_machine_log_t.date)  log_months,
        YEAR(b_machine_log_t.date)  log_year,
        b_machine_log_t.product_id
    FROM
        b_machine_log_t
        b_machine_log_t LEFT JOIN m_products_t
        ON b_machine_log_t.product_id = m_products_t.product_id LEFT JOIN m_product_type_t ON
        m_products_t.product_type_id = m_product_type_t.product_type_id LEFT JOIN m_product_variants_t on 
        m_products_t.product_variant_id=m_product_variants_t.product_variant_id
        LEFT JOIN i_product_packs on m_products_t.product_pack_id=i_product_packs.packing_id 
    WHERE    
       DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition GROUP BY m_products_t.product_id, log_month order by log_year,log_months ASC");

    //  product summary chart
    
        $this->data['product_sumchart'] = \DB::select("SELECT
	m_products_t.concatenated_product AS name,
    CONCAT(HOUR(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), '.', LPAD(MINUTE(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), 1, '0')) as count,
    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
        MONTH(b_machine_log_t.date)  log_months,
        YEAR(b_machine_log_t.date)  log_year
    
    FROM
        b_machine_log_t
        b_machine_log_t LEFT JOIN m_products_t
        ON b_machine_log_t.product_id = m_products_t.product_id LEFT JOIN m_product_type_t ON
        m_products_t.product_type_id = m_product_type_t.product_type_id LEFT JOIN m_product_variants_t on 
        m_products_t.product_variant_id=m_product_variants_t.product_variant_id
        LEFT JOIN i_product_packs on m_products_t.product_pack_id=i_product_packs.packing_id 
    WHERE
        DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition
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
        b_machine_log_t LEFT JOIN m_products_t
        ON b_machine_log_t.product_id = m_products_t.product_id LEFT JOIN m_product_type_t ON
        m_products_t.product_type_id = m_product_type_t.product_type_id LEFT JOIN m_product_variants_t on 
        m_products_t.product_variant_id=m_product_variants_t.product_variant_id
        LEFT JOIN i_product_packs on m_products_t.product_pack_id=i_product_packs.packing_id 
    WHERE
     DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition
    GROUP BY
        name, log_month
    ORDER BY
        log_year,log_months ASC");
   // dd($this->data['product_linechart']);

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


        // machine  summary  table  
        
        $this->data['machine_summary'] = \DB::select("SELECT
    ROUND(SUM(b_machine_log_t.quantity), 0) AS total_quantity,
    w_machine_hdr_t.machine_name,
    sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00)))) as total_running_hours, 
    subtime(sec_to_time((200*60)*60) , sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))) AS total_idle_hours,
    round(SUM(b_machine_log_t.quantity) / sum((CAST(LEFT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) + CAST(RIGHT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) / 60)),0) AS opt_hrs, 
    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
    MONTH(b_machine_log_t.date)  log_months,
    YEAR(b_machine_log_t.date)  log_year,
    b_machine_log_t.machine_id
FROM
    w_machine_hdr_t
INNER JOIN b_machine_log_t ON w_machine_hdr_t.machine_hdr_id = b_machine_log_t.machine_id
LEFT JOIN m_products_t ON b_machine_log_t.product_id = m_products_t.product_id
LEFT JOIN m_product_type_t ON m_products_t.product_type_id = m_product_type_t.product_type_id
LEFT JOIN m_product_variants_t ON m_products_t.product_variant_id = m_product_variants_t.product_variant_id
LEFT JOIN i_product_packs ON m_products_t.product_pack_id = i_product_packs.packing_id
WHERE
    DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition
    
GROUP BY
    b_machine_log_t.machine_id,log_month order by log_year,log_months ASC");
        
        //  Machine summary chart
        
    $this->data['machine_sumchart'] = \DB::select("SELECT
    CONCAT(HOUR(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), '.', LPAD(MINUTE(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), 1, '0')) as count,
    w_machine_hdr_t.machine_name as name,
    MONTH(b_machine_log_t.date)  log_month,
    YEAR(b_machine_log_t.date)  log_year,
    CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_months,
    b_machine_log_t.machine_id
FROM
    w_machine_hdr_t
INNER JOIN b_machine_log_t ON w_machine_hdr_t.machine_hdr_id = b_machine_log_t.machine_id
LEFT JOIN m_products_t ON b_machine_log_t.product_id = m_products_t.product_id
LEFT JOIN m_product_type_t ON m_products_t.product_type_id = m_product_type_t.product_type_id
LEFT JOIN m_product_variants_t ON m_products_t.product_variant_id = m_product_variants_t.product_variant_id
LEFT JOIN i_product_packs ON m_products_t.product_pack_id = i_product_packs.packing_id
WHERE
    DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition
    
GROUP BY
    name,log_month order by log_year,log_month ASC");


        $breakData = [];
        foreach ($this->data['machine_sumchart'] as $item) {
            $breakData[] = [
                'name' => $item->name,
                'value' => $item->count,
                'month' => $item->log_months,
            ];
        }
        // dd (json_encode ($groupData));
        $this->data['machine_sumchart'] = json_encode($breakData);


// unit summary table
  $this->data['unit_summary'] = \DB::select("SELECT
        ROUND(SUM(b_machine_log_t.quantity),0) AS total_quantity,
        i_product_packs.pack_name AS product_name,
        sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00)))) as total_running_hours, 
        subtime(sec_to_time((200*60)*60) , sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))) AS total_idle_hours,
        round(SUM(b_machine_log_t.quantity) / sum((CAST(LEFT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) + CAST(RIGHT(b_machine_log_t.running_hours, 2) AS DECIMAL(5, 2)) / 60)),0) AS opt_hrs, 
        CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_month,
        MONTH(b_machine_log_t.date)  log_months,
        YEAR(b_machine_log_t.date)  log_year

    FROM
        b_machine_log_t
        b_machine_log_t LEFT JOIN m_products_t
        ON b_machine_log_t.product_id = m_products_t.product_id LEFT JOIN m_product_type_t ON
        m_products_t.product_type_id = m_product_type_t.product_type_id LEFT JOIN m_product_variants_t on 
        m_products_t.product_variant_id=m_product_variants_t.product_variant_id
        LEFT JOIN i_product_packs on m_products_t.product_pack_id=i_product_packs.packing_id 
    WHERE    
       DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition GROUP BY product_name,log_month order by log_year,log_months ASC");

// end

        // unit chart

        $this->data['unit_chart'] = \DB::select("SELECT
        CONCAT(HOUR(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), '.', LPAD(MINUTE(sec_to_time(sum(time_to_sec(MAKETIME(LPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', 1),2,0),RPAD(SUBSTRING_INDEX(b_machine_log_t.running_hours, '.', -1),2,0),00))))), 1, '0')) as count,
        i_product_packs.pack_name AS name,
        MONTH(b_machine_log_t.date) AS log_month,
        ROUND(SUM(b_machine_log_t.quantity),0) AS total_quantity,
        CONCAT(SUBSTRING(MONTHNAME(b_machine_log_t.date), 1, 3), '-', YEAR(b_machine_log_t.date) % 100) AS log_months,
        MONTH(b_machine_log_t.date)  log_month,
        YEAR(b_machine_log_t.date)  log_year

    FROM
        b_machine_log_t
        b_machine_log_t LEFT JOIN m_products_t
        ON b_machine_log_t.product_id = m_products_t.product_id LEFT JOIN m_product_type_t ON
        m_products_t.product_type_id = m_product_type_t.product_type_id LEFT JOIN m_product_variants_t on 
        m_products_t.product_variant_id=m_product_variants_t.product_variant_id
        LEFT JOIN i_product_packs on m_products_t.product_pack_id=i_product_packs.packing_id 
    WHERE    
       DATE_FORMAT(b_machine_log_t.date, '%Y-%m') BETWEEN '$start_month' AND '$end_month' $condition GROUP BY name,log_month order by log_year,log_month ASC");


        $prevenData = [];
        foreach ($this->data['unit_chart'] as $item) {
            $prevenData[] = [
                'name' => $item->name,
                'value' => $item->count,
                'month' => $item->log_months,
                'qty' => $item->total_quantity,
            ];
        }
        // dd (json_encode ($groupData));
        $this->data['unit_chart'] = json_encode(value: $prevenData);


    return view('machinemaintenancelog.productlogdts', $this->data);
    }
}
