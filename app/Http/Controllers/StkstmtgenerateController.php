<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Stockstmtbank;
use App\Stockstmtbanklines;
use Session;
use DateTime;
use DB;

class StkstmtgenerateController extends Controller
{


    public function Create(Request $request)
    {
        
      $this->data['pageMethod'] = \Request::route()->getName();
      $select_month = $request->input('select_month'); 

              // Create a DateTime object from the selected month
            $date = new DateTime($select_month . '-01'); // Start with the first day of the month

            // Modify the DateTime object to the last day of the month
            $selected_date = $date->format('Y-m-t'); 

           // FG COSMECTICS     
           
   $this->data['fg_cosmetic_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='COSMETICS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
   $this->data['fg_cosmetic_mrate'] = \DB::select("SELECT ROUND(SUM(m_products_t.`Std Cost`),0) as std_cost FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='COSMETICS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
   $this->data['fg_cosmetic_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='COSMETICS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       

      $this->data['fg_cosmetic_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='COSMETICS' ORDER BY `stkline_id` DESC LIMIT 1");
      $this->data['fg_cosmetic_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='COSMETICS' ORDER BY `stkline_id` DESC LIMIT 1");
   
    if (!empty($this->data['fg_cosmetic_stk']) && $this->data['fg_cosmetic_stk'][0]->stock_on_date !== null) {
      $this->data['fg_cosmetic_stk1'] = $this->data['fg_cosmetic_stk'][0]->stock_on_date;
        } else {
            $this->data['fg_cosmetic_stk1'] = 0; 
        }
   if (!empty($this->data['fg_cosmetic_gt']) && $this->data['fg_cosmetic_gt'][0]->total_value !== null) {
        $this->data['fg_cosmetic_gt1'] = $this->data['fg_cosmetic_gt'][0]->total_value;
      } else {
        $this->data['fg_cosmetic_gt1'] = 0; 
      }
      
           // FG SASTRIC AYURVEDA 

  $this->data['fg_sasayur_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='SASTRIC AYURVEDA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['fg_sasayur_mrate'] = \DB::select("SELECT ROUND(SUM(m_products_t.`Std Cost`),0) as std_cost FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='SASTRIC AYURVEDA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
   $this->data['fg_sasayur_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='SASTRIC AYURVEDA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
   $this->data['fg_sas_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='SASTRIC AYURVEDA' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['fg_sas_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='SASTRIC AYURVEDA' ORDER BY `stkline_id` DESC LIMIT 1");
      if (!empty($this->data['fg_sas_stk']) && $this->data['fg_sas_stk'][0]->stock_on_date !== null) {
    $this->data['fg_sas_stk1'] = $this->data['fg_sas_stk'][0]->stock_on_date;
      } else {
          $this->data['fg_sas_stk1'] = 0; 
      }
 if (!empty($this->data['fg_sas_gt']) && $this->data['fg_sas_gt'][0]->total_value !== null) {
      $this->data['fg_sas_gt1'] = $this->data['fg_sas_gt'][0]->total_value;
    } else {
      $this->data['fg_sas_gt1'] = 0; 
    }
           // FG SASTRIC SIDDHA

  $this->data['fg_sassid_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='SASTRIC SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['fg_sassid_mrate'] = \DB::select("SELECT ROUND(SUM(m_products_t.`Std Cost`),0) as std_cost FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='SASTRIC SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['fg_sassid_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='SASTRIC SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
        
   $this->data['fg_sasid_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='SASTRIC SIDDHA' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['fg_sasid_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='SASTRIC SIDDHA' ORDER BY `stkline_id` DESC LIMIT 1");
      if (!empty($this->data['fg_sasid_stk']) && $this->data['fg_sasid_stk'][0]->stock_on_date !== null) {
    $this->data['fg_sasid_stk1'] = $this->data['fg_sasid_stk'][0]->stock_on_date;
      } else {
          $this->data['fg_sasid_stk1'] = 0; 
      }
 if (!empty($this->data['fg_sasid_gt']) && $this->data['fg_sasid_gt'][0]->total_value !== null) {
      $this->data['fg_sasid_gt1'] = $this->data['fg_sasid_gt'][0]->total_value;
    } else {
      $this->data['fg_sasid_gt1'] = 0; 
    }    
           // FG SIDDHA

  $this->data['fg_sidha_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['fg_sidha_mrate'] = \DB::select("SELECT ROUND(SUM(m_products_t.`Std Cost`),0) as std_cost FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['fg_sidha_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='FINISHED GOODS' and m_product_category_t.category_name='SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
   $this->data['fg_sidha_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='SIDDHA' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['fg_sidha_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='SIDDHA' ORDER BY `stkline_id` DESC LIMIT 1");
    
     if (!empty($this->data['fg_sidha_stk']) && $this->data['fg_sidha_stk'][0]->stock_on_date !== null) {
    $this->data['fg_sidha_stk1'] = $this->data['fg_sidha_stk'][0]->stock_on_date;
      } else {
          $this->data['fg_sidha_stk1'] = 0; 
      }
 if (!empty($this->data['fg_sidha_gt']) && $this->data['fg_sidha_gt'][0]->total_value !== null) {
      $this->data['fg_sidha_gt1'] = $this->data['fg_sidha_gt'][0]->total_value;
    } else {
      $this->data['fg_sidha_gt1'] = 0; 
    }
           // PACKING MATERIALS  -  CARTON

  $this->data['pm_carton_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='CARTON' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['pm_carton_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='CARTON' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['pm_carton_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='CARTON' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");    
    
   $this->data['pm_carton_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='CARTON' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['pm_carton_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='CARTON' ORDER BY `stkline_id` DESC LIMIT 1");
      if (!empty($this->data['pm_carton_stk']) && $this->data['pm_carton_stk'][0]->stock_on_date !== null) {
    $this->data['pm_carton_stk1'] = $this->data['pm_carton_stk'][0]->stock_on_date;
      } else {
          $this->data['pm_carton_stk1'] = 0; 
      }
 if (!empty($this->data['pm_carton_gt']) && $this->data['pm_carton_gt'][0]->total_value !== null) {
      $this->data['pm_carton_gt1'] = $this->data['pm_carton_gt'][0]->total_value;
    } else {
      $this->data['pm_carton_gt1'] = 0; 
    }
           // PACKING MATERIALS  -  CONTAINERS

  $this->data['pm_container_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='CONTAINERS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['pm_container_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='CONTAINERS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['pm_container_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='CONTAINERS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
   $this->data['pm_container_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='CONTAINERS' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['pm_container_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='CONTAINERS' ORDER BY `stkline_id` DESC LIMIT 1");
    
      if (!empty($this->data['pm_container_stk']) && $this->data['pm_container_stk'][0]->stock_on_date !== null) {
    $this->data['pm_container_stk1'] = $this->data['pm_container_stk'][0]->stock_on_date;
      } else {
          $this->data['pm_container_stk1'] = 0; 
      }
 if (!empty($this->data['pm_container_gt']) && $this->data['pm_container_gt'][0]->total_value !== null) {
      $this->data['pm_container_gt1'] = $this->data['pm_container_gt'][0]->total_value;
    } else {
      $this->data['pm_container_gt1'] = 0; 
    }
           // PACKING MATERIALS  -  INSERT

  $this->data['pm_insert_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='INSERT' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['pm_insert_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='INSERT' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['pm_insert_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='INSERT' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
        
     $this->data['pm_insert_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='INSERT' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['pm_insert_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='INSERT' ORDER BY `stkline_id` DESC LIMIT 1");
      if (!empty($this->data['pm_insert_stk']) && $this->data['pm_insert_stk'][0]->stock_on_date !== null) {
    $this->data['pm_insert_stk1'] = $this->data['pm_insert_stk'][0]->stock_on_date;
      } else {
          $this->data['pm_insert_stk1'] = 0; 
      }
 if (!empty($this->data['pm_insert_gt']) && $this->data['pm_insert_gt'][0]->total_value !== null) {
      $this->data['pm_insert_gt1'] = $this->data['pm_insert_gt'][0]->total_value;
    } else {
      $this->data['pm_insert_gt1'] = 0; 
    }      
        
           // PACKING MATERIALS  -  LABELS

  $this->data['pm_label_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='LABELS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['pm_label_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='LABELS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['pm_label_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='LABELS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
 
   $this->data['pm_label_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='LABELS' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['pm_label_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='LABELS' ORDER BY `stkline_id` DESC LIMIT 1");
      if (!empty($this->data['pm_label_stk']) && $this->data['pm_label_stk'][0]->stock_on_date !== null) {
    $this->data['pm_label_stk1'] = $this->data['pm_label_stk'][0]->stock_on_date;
      } else {
          $this->data['pm_label_stk1'] = 0; 
      }
 if (!empty($this->data['pm_label_gt']) && $this->data['pm_label_gt'][0]->total_value !== null) {
      $this->data['pm_label_gt1'] = $this->data['pm_label_gt'][0]->total_value;
    } else {
      $this->data['pm_label_gt1'] = 0; 
    }
                // PACKING MATERIALS  -  Other items

  $this->data['pm_otheritem_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='PROCESS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['pm_otheritem_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='PROCESS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['pm_otheritem_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='PROCESS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
 
  $this->data['pm_oitem_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='Other Items' ORDER BY `stkline_id` DESC LIMIT 1");
  $this->data['pm_oitem_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='Other Items' ORDER BY `stkline_id` DESC LIMIT 1");
     if (!empty($this->data['pm_oitem_stk']) && $this->data['pm_oitem_stk'][0]->stock_on_date !== null) {
    $this->data['pm_oitem_stk1'] = $this->data['pm_oitem_stk'][0]->stock_on_date;
      } else {
          $this->data['pm_oitem_stk1'] = 0; 
      }
 if (!empty($this->data['pm_oitem_gt']) && $this->data['pm_oitem_gt'][0]->total_value !== null) {
      $this->data['pm_oitem_gt1'] = $this->data['pm_oitem_gt'][0]->total_value;
    } else {
      $this->data['pm_oitem_gt1'] = 0; 
    }      
                    // PACKING MATERIALS  -  STICKER

  $this->data['pm_sticker_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='STICKER' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['pm_sticker_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='STICKER' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['pm_sticker_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='PACKING MATERIALS' and m_product_category_t.category_name='STICKER' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
 
   $this->data['pm_sticker_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='STICKER' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['pm_sticker_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='STICKER' ORDER BY `stkline_id` DESC LIMIT 1");
      if (!empty($this->data['pm_sticker_stk']) && $this->data['pm_sticker_stk'][0]->stock_on_date !== null) {
    $this->data['pm_sticker_stk1'] = $this->data['pm_sticker_stk'][0]->stock_on_date;
      } else {
          $this->data['pm_sticker_stk1'] = 0; 
      }
 if (!empty($this->data['pm_sticker_gt']) && $this->data['pm_sticker_gt'][0]->total_value !== null) {
      $this->data['pm_sticker_gt1'] = $this->data['pm_sticker_gt'][0]->total_value;
    } else {
      $this->data['pm_sticker_gt1'] = 0; 
    }
             // RAW MATERIALS  -  CHEMICALS

  $this->data['rm_chemical_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='CHEMICALS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['rm_chemical_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='CHEMICALS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['rm_chemical_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='CHEMICALS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
         
   $this->data['rm_chemical_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='CHEMICALS' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['rm_chemical_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='CHEMICALS' ORDER BY `stkline_id` DESC LIMIT 1");
          
         if (!empty($this->data['rm_chemical_stk']) && $this->data['rm_chemical_stk'][0]->stock_on_date !== null) {
    $this->data['rm_chemical_stk1'] = $this->data['rm_chemical_stk'][0]->stock_on_date;
      } else {
          $this->data['rm_chemical_stk1'] = 0; 
      }
 if (!empty($this->data['rm_chemical_gt']) && $this->data['rm_chemical_gt'][0]->total_value !== null) {
      $this->data['rm_chemical_gt1'] = $this->data['rm_chemical_gt'][0]->total_value;
    } else {
      $this->data['rm_chemical_gt1'] = 0; 
    }
           // RAW MATERIALS  -  LAB

  $this->data['rm_lab_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='LAB' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['rm_lab_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='LAB' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['rm_lab_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='LAB' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
  
   $this->data['rm_lab_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='LAB' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['rm_lab_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='LAB' ORDER BY `stkline_id` DESC LIMIT 1");
          
        if (!empty($this->data['rm_lab_stk']) && $this->data['rm_lab_stk'][0]->stock_on_date !== null) {
    $this->data['rm_lab_stk1'] = $this->data['rm_lab_stk'][0]->stock_on_date;
      } else {
          $this->data['rm_lab_stk1'] = 0; 
      }
 if (!empty($this->data['rm_lab_gt']) && $this->data['rm_lab_gt'][0]->total_value !== null) {
      $this->data['rm_lab_gt1'] = $this->data['rm_lab_gt'][0]->total_value;
    } else {
      $this->data['rm_lab_gt1'] = 0; 
    }
           // RAW MATERIALS  -  LEAF

  $this->data['rm_leaf_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='LEAF' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['rm_leaf_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='LEAF' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['rm_leaf_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='LEAF' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
   
   $this->data['rm_leaf_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='LEAF' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['rm_leaf_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='LEAF' ORDER BY `stkline_id` DESC LIMIT 1");
        if (!empty($this->data['rm_leaf_stk']) && $this->data['rm_leaf_stk'][0]->stock_on_date !== null) {
    $this->data['rm_leaf_stk1'] = $this->data['rm_leaf_stk'][0]->stock_on_date;
      } else {
          $this->data['rm_leaf_stk1'] = 0; 
      }
 if (!empty($this->data['rm_leaf_gt']) && $this->data['rm_leaf_gt'][0]->total_value !== null) {
      $this->data['rm_leaf_gt1'] = $this->data['rm_leaf_gt'][0]->total_value;
    } else {
      $this->data['rm_leaf_gt1'] = 0; 
    }  
         
           // RAW MATERIALS  -  OIL

  $this->data['rm_oil_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='OIL' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['rm_oil_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='OIL' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['rm_oil_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='OIL' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
   
   $this->data['rm_oil_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='OIL' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['rm_oil_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='OIL' ORDER BY `stkline_id` DESC LIMIT 1");
           
if (!empty($this->data['rm_oil_stk']) && $this->data['rm_oil_stk'][0]->stock_on_date !== null) {
    $this->data['rm_oil_stk1'] = $this->data['rm_oil_stk'][0]->stock_on_date;
      } else {
          $this->data['rm_oil_stk1'] = 0; 
      }
 if (!empty($this->data['rm_oil_gt']) && $this->data['rm_oil_gt'][0]->total_value !== null) {
      $this->data['rm_oil_gt1'] = $this->data['rm_oil_gt'][0]->total_value;
    } else {
      $this->data['rm_oil_gt1'] = 0; 
    }
           // RAW MATERIALS  -  RAW

  $this->data['rm_raw_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='RAW' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['rm_raw_mrate'] = \DB::select("SELECT ROUND(SUM((SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1 )),0) AS rate FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='RAW' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['rm_raw_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='RAW MATERIALS' and m_product_category_t.category_name='RAW' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
   
    $this->data['rm_raw_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='RAW' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['rm_raw_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='RAW' ORDER BY `stkline_id` DESC LIMIT 1");
         
         if (!empty($this->data['rm_raw_stk']) && $this->data['rm_raw_stk'][0]->stock_on_date !== null) {
    $this->data['rm_raw_stk1'] = $this->data['rm_raw_stk'][0]->stock_on_date;
      } else {
          $this->data['rm_raw_stk1'] = 0; 
      }
 if (!empty($this->data['rm_raw_gt']) && $this->data['rm_raw_gt'][0]->total_value !== null) {
      $this->data['rm_raw_gt1'] = $this->data['rm_raw_gt'][0]->total_value;
    } else {
      $this->data['rm_raw_gt1'] = 0; 
    }
           // SEMI FINISHED GOODS -  COSMETICS

  $this->data['sfg_cos_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='COSMETICS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['sfg_cos_mrate'] = \DB::select("SELECT ROUND(SUM(m_products_t.`Std Cost`),0) as std_cost FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='COSMETICS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['sfg_cos_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='COSMETICS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
   
   $this->data['sfg_cos_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='SEMI-COSMETICS' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['sfg_cos_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='SEMI-COSMETICS' ORDER BY `stkline_id` DESC LIMIT 1");
         
         if (!empty($this->data['sfg_cos_stk']) && $this->data['sfg_cos_stk'][0]->stock_on_date !== null) {
    $this->data['sfg_cos_stk1'] = $this->data['sfg_cos_stk'][0]->stock_on_date;
      } else {
          $this->data['sfg_cos_stk1'] = 0; 
      }
 if (!empty($this->data['sfg_cos_gt']) && $this->data['sfg_cos_gt'][0]->total_value !== null) {
      $this->data['sfg_cos_gt1'] = $this->data['sfg_cos_gt'][0]->total_value;
    } else {
      $this->data['sfg_cos_gt1'] = 0; 
    }
           // SEMI FINISHED GOODS -  SASTRIC AYURVEDA

  $this->data['sfg_sasauy_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='SASTRIC AYURVEDA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['sfg_sasauy_mrate'] = \DB::select("SELECT ROUND(SUM(m_products_t.`Std Cost`),0) as std_cost FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='SASTRIC AYURVEDA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['sfg_sasauy_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='SASTRIC AYURVEDA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
   
   $this->data['sfg_sas_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='SEMI-SASTRIC AYURVEDA' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['sfg_sas_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='SEMI-SASTRIC AYURVEDA' ORDER BY `stkline_id` DESC LIMIT 1");
       if (!empty($this->data['sfg_sas_stk']) && $this->data['sfg_sas_stk'][0]->stock_on_date !== null) {
    $this->data['sfg_sas_stk1'] = $this->data['sfg_sas_stk'][0]->stock_on_date;
      } else {
          $this->data['sfg_sas_stk1'] = 0; 
      }
 if (!empty($this->data['sfg_sas_gt']) && $this->data['sfg_sas_gt'][0]->total_value !== null) {
      $this->data['sfg_sas_gt1'] = $this->data['sfg_sas_gt'][0]->total_value;
    } else {
      $this->data['sfg_sas_gt1'] = 0; 
    }
           // SEMI FINISHED GOODS -  SASTRIC SIDDHA

  $this->data['sfg_sasida_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='SASTRIC SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['sfg_sasida_mrate'] = \DB::select("SELECT ROUND(SUM(m_products_t.`Std Cost`),0) as std_cost FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='SASTRIC SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['sfg_sasida_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='SASTRIC SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
      
   $this->data['sfg_sasid_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='SEMI-SASTRIC SIDDHA' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['sfg_sasid_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='SEMI-SASTRIC SIDDHA' ORDER BY `stkline_id` DESC LIMIT 1");
        
if (!empty($this->data['sfg_sasid_stk']) && $this->data['sfg_sasid_stk'][0]->stock_on_date !== null) {
    $this->data['sfg_sasid_stk1'] = $this->data['sfg_sasid_stk'][0]->stock_on_date;
      } else {
          $this->data['sfg_sasid_stk1'] = 0; 
      }
 if (!empty($this->data['sfg_sasid_gt']) && $this->data['sfg_sasid_gt'][0]->total_value !== null) {
      $this->data['sfg_sasid_gt1'] = $this->data['sfg_sasid_gt'][0]->total_value;
    } else {
      $this->data['sfg_sasid_gt1'] = 0; 
    }
           // SEMI FINISHED GOODS -  SIDDHA

  $this->data['sfg_sidha_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['sfg_sidha_mrate'] = \DB::select("SELECT ROUND(SUM(m_products_t.`Std Cost`),0) as std_cost FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['sfg_sidha_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='SEMI FINISHED GOODS' and m_product_category_t.category_name='SIDDHA' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
  
   $this->data['sfg_sidha_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='SEMI-SIDDHA' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['sfg_sidha_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='SEMI-SIDDHA' ORDER BY `stkline_id` DESC LIMIT 1");
   if (!empty($this->data['sfg_sidha_stk']) && $this->data['sfg_sidha_stk'][0]->stock_on_date !== null) {
    $this->data['sfg_sidha_stk1'] = $this->data['sfg_sidha_stk'][0]->stock_on_date;
      } else {
          $this->data['sfg_sidha_stk1'] = 0; 
      }
 if (!empty($this->data['sfg_sidha_gt']) && $this->data['sfg_sidha_gt'][0]->total_value !== null) {
      $this->data['sfg_sidha_gt1'] = $this->data['sfg_sidha_gt'][0]->total_value;
    } else {
      $this->data['sfg_sidha_gt1'] = 0; 
    }  
    
        // CONSUMABLES -  PROCESSING GODOWN

  $this->data['pro_godown_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='CONSUMABLES' and m_product_category_t.category_name='PROCESS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['pro_godown_mrate'] = \DB::select("SELECT ROUND(SUM(m_products_t.`Std Cost`),0) as std_cost FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='CONSUMABLES' and m_product_category_t.category_name='PROCESS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['pro_godown_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and m_product_groups_t.group_name='CONSUMABLES' and m_product_category_t.category_name='PROCESS' and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
  
   $this->data['pro_godown_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='PROCESSING GODOWN' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['pro_godown_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='PROCESSING GODOWN' ORDER BY `stkline_id` DESC LIMIT 1");
   if (!empty($this->data['pro_godown_stk']) && $this->data['pro_godown_stk'][0]->stock_on_date !== null) {
    $this->data['pro_godown_stk1'] = $this->data['pro_godown_stk'][0]->stock_on_date;
      } else {
          $this->data['pro_godown_stk1'] = 0; 
      }
 if (!empty($this->data['pro_godown_gt']) && $this->data['pro_godown_gt'][0]->total_value !== null) {
      $this->data['pro_godown_gt1'] = $this->data['pro_godown_gt'][0]->total_value;
    } else {
      $this->data['pro_godown_gt1'] = 0; 
    } 
    
        // OTHER PROMOTIONAL AND ACCESSARY ITEMS

  $this->data['access_item_qoh'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),0) AS qoh FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and ( m_product_groups_t.group_name='ACCESSORIES' OR m_product_groups_t.group_name='PROMOTIONAL ITEMS') and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");           
  $this->data['access_item_mrate'] = \DB::select("SELECT ROUND(SUM(m_products_t.`Std Cost`),0) as std_cost FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and ( m_product_groups_t.group_name='ACCESSORIES' OR m_product_groups_t.group_name='PROMOTIONAL ITEMS') and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");        
  $this->data['access_item_total'] = \DB::select("SELECT ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * (SELECT iqoh.cost from i_qoh_detail_t as iqoh where iqoh.product_id = i_qoh_detail_t.product_id and iqoh.batch_number=i_qoh_detail_t.batch_number and iqoh.qoh_detail_id <= i_qoh_detail_t.qoh_detail_id and iqoh.cost!=0 limit 1),2) AS total FROM m_products_t LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id LEFT JOIN i_qoh_detail_t ON  i_qoh_detail_t.product_id = m_products_t.product_id WHERE 1 = 1 and ( m_product_groups_t.group_name='ACCESSORIES' OR m_product_groups_t.group_name='PROMOTIONAL ITEMS') and DATE(i_qoh_detail_t.created_at) <= '$selected_date'");       
  
   $this->data['access_item_stk'] = \DB::select("SELECT stock_on_date FROM `f_stkstmt_gne_lines_t` where particulars='OTHER PROMOTIONAL AND ACCESSARY ITEMS' ORDER BY `stkline_id` DESC LIMIT 1");
   $this->data['access_item_gt'] = \DB::select("SELECT total_value FROM `f_stkstmt_gne_lines_t` where particulars='OTHER PROMOTIONAL AND ACCESSARY ITEMS' ORDER BY `stkline_id` DESC LIMIT 1");
   if (!empty($this->data['access_item_stk']) && $this->data['access_item_stk'][0]->stock_on_date !== null) {
    $this->data['access_item_stk1'] = $this->data['access_item_stk'][0]->stock_on_date;
      } else {
          $this->data['access_item_stk1'] = 0; 
      }
 if (!empty($this->data['access_item_gt']) && $this->data['access_item_gt'][0]->total_value !== null) {
      $this->data['access_item_gt1'] = $this->data['access_item_gt'][0]->total_value;
    } else {
      $this->data['access_item_gt1'] = 0; 
    }  
// totals

  $this->data['grand_total'] = \DB::select("SELECT SUM(total_value) as total FROM `f_stkstmt_gne_lines_t` ORDER BY `stkline_id` DESC LIMIT 21");
  $this->data['grand_total_qoh_bank'] = \DB::select("SELECT SUM(stock_on_date) as stock FROM `f_stkstmt_gne_lines_t` ORDER BY `stkline_id` DESC LIMIT 21");
// Less:Trade Creditors
 $this->data['trade_credit'] = \DB::select("select * from(select round(SUM(credit_amount-debit_amount+opening+asd+asd1),2)as balance from (SELECT f_journal_entry_lines_t.reference_id, 
COALESCE( (select COALESCE(sum(p_po_invoice_hdr_t.invoice_grand_total),0) as balance_amount from p_po_invoice_hdr_t where p_po_invoice_hdr_t.supplier_id=f_journal_entry_lines_t.reference_id and p_po_invoice_hdr_t.created_by='' and p_po_invoice_hdr_t.invoice_date <= '$selected_date'),0) as asd,
COALESCE( (select COALESCE(sum(f_expenses_t.expense_amount),0) as balance_amount1 from f_expenses_t where f_expenses_t.supplier_id=f_journal_entry_lines_t.reference_id and f_expenses_t.expense_no='Opening Balance' and f_expenses_t.bill_date <= '$selected_date'),0) as asd1,
(COALESCE((SELECT sum(v.credit_amount-v.debit_amount) FROM `f_journal_entry_lines_t` as v join f_journal_entry_t on f_journal_entry_t.journal_entry_id=v.journal_entry_id where v.reference_source='SUPPLIER' and v.reference_id=f_journal_entry_lines_t.reference_id and f_journal_entry_t.journal_date <= '$selected_date' ),0)) as opening,sum(debit_amount) as debit_amount,sum(credit_amount) as credit_amount FROM `f_journal_entry_lines_t` join f_journal_entry_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id   where reference_source='SUPPLIER' and f_journal_entry_t.journal_date <= '$selected_date' group by f_journal_entry_lines_t.reference_id)f join m_supplier_t on m_supplier_t.supplier_id=f.reference_id)v1 ");

// net value
    $grand_total=$this->data['grand_total'];
    $trade_credit = $this->data['trade_credit'];
    $this->data['net_value'] = $grand_total[0]->total - $trade_credit[0]->balance;
 
// total A
 $this->data['margin_one'] = \DB::select("SELECT sum_margin FROM `f_stkstmt_gne_hdr_t` ORDER BY skt_id DESC LIMIT 1");
if (!empty($this->data['margin_one']) && $this->data['margin_one'][0]->sum_margin !== null) {
 $this->data['total_a'] =  $this->data['net_value'] - $this->data['margin_one'][0]->sum_margin ;
 }else{
      $this->data['total_a'] = "0";
 }
// sundry debtor
 $this->data['sundry_debtor'] = \DB::select("select * from(SELECT * FROM ( SELECT ROUND((SUM( COALESCE(debit_amount,0) - COALESCE(credit_amount,0) + opening + asd)), 2) AS cus_balance
FROM (SELECT m_customers_t.customer_id as reference_id, COALESCE((SELECT COALESCE(SUM(s_invoice_hdr_t.invoice_grand_total),0) AS balance_amount
FROM s_invoice_hdr_t WHERE s_invoice_hdr_t.ship_to_customer_id = m_customers_t.customer_id AND s_invoice_hdr_t.created_by = '' AND s_invoice_hdr_t.invoice_date <= '$selected_date'),0) AS asd,
            (
                COALESCE(
                    (
                    SELECT
                        SUM(v.debit_amount - v.credit_amount)
                    FROM
                        `f_journal_entry_lines_t` AS v
                    JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = v.journal_entry_id
                    WHERE
                        v.reference_source = 'CUSTOMER' AND v.reference_id = m_customers_t.customer_id AND f_journal_entry_t.journal_date <= '$selected_date'
                ),
                0
                )
            ) AS opening,
            SUM(debit_amount) AS debit_amount,
            SUM(credit_amount) AS credit_amount
        FROM
            m_customers_t 
         left join   `f_journal_entry_lines_t` on f_journal_entry_lines_t.reference_id=m_customers_t.customer_id and f_journal_entry_lines_t.reference_source='CUSTOMER' AND f_journal_entry_lines_t.journal_date <= '$selected_date'
        left JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id 

        where 
        m_customers_t.active = 'Yes' and m_customers_t.customer_id not in (2,35,45,46,56,62,63,75)

        GROUP BY

            m_customers_t.customer_id
    ) f
JOIN m_customers_t ON m_customers_t.customer_id = f.reference_id
union all 

select (opening-de-cr)as bala from (SELECT (select f_account_structure_t.account_name from f_account_structure_t where f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id)as name,COALESCE(
                    (
                    SELECT
                        SUM(v.debit_amount - v.credit_amount)
                    FROM
                        `f_journal_entry_lines_t` AS v
                    JOIN f_journal_entry_t as n ON n.journal_entry_id = v.journal_entry_id
                    WHERE
                         v.account_id = f_journal_entry_lines_t.account_id AND n.journal_date  <= '$selected_date' and n.journal_type LIKE 'MANUAL' and n.journal_category='OTHERS' 
                ),
                0
                )
             AS opening,sum(f_journal_entry_lines_t.debit_amount) as de,sum(f_journal_entry_lines_t.credit_amount) as cr  FROM `f_journal_entry_t` join f_journal_entry_lines_t on f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id WHERE `journal_type` LIKE 'MANUAL' and journal_category='OTHERS'  and f_journal_entry_lines_t.account_id in (0) and f_journal_entry_t.journal_date <= '$selected_date' group by f_journal_entry_lines_t.account_id)d

) v1
WHERE
    1 = 1)v1");

// total B
 $this->data['margin_two'] = \DB::select("SELECT margin_two FROM `f_stkstmt_gne_hdr_t` ORDER BY skt_id DESC LIMIT 1");
 if (!empty($this->data['margin_two']) && $this->data['margin_two'][0]->margin_two !== null) {
 $this->data['total_b'] =  $this->data['sundry_debtor'][0]->cus_balance - $this->data['margin_two'][0]->margin_two ;
}else{
     $this->data['total_b'] =  "0";
}

        return view('stockstmtbank.form', $this->data);      
    }
    
    
   //save function 
    
        public function save(Request $request)
    {

        
        \DB::beginTransaction();
        
        
        
          try {
              
        $status = "DRAFT-1"; 
               
        // Get the latest status from the database
        $status_db = \DB::select("SELECT status FROM `f_stkstmt_gne_hdr_t` ORDER BY `skt_id` DESC LIMIT 1");

        if($status_db && isset($status_db[0]->status)){
            
           $status_db1 = $status_db[0]->status;
            
           $interval = substr($status_db1, 6, 7) + 1;
 
        //  dd($interval);
          
           $status = "DRAFT-" . $interval;
           
          if ($status_db1 == "FINAL") {
            $status = "DRAFT-1"; // If status is FINAL, reset to DRAFT-1
        } 
           
 
        }  // Set $status based on $status_al

        
       // dd($status);

             $stockhead = new Stockstmtbank();
            // header save
             $stockhead->generate_date = date('Y-m-d');
             $select_date = $_POST['select_date'];
             $date = new DateTime($select_date . '-01');
             $selected_date = $date->format('Y-m-t'); 
             $stockhead->select_date = $selected_date;

            // dd($stockhead->select_date );
             $stockhead->status = $status;
             $stockhead->grand_total = $_POST['ovrall_total'];
             $stockhead->total_stock = $_POST['ovrall_total_qoh_bank'];
             $stockhead->total_value_sum	 = $_POST['sum_tl_val'];
             $stockhead->trade_creditors = $_POST['trade_sum'];
             $stockhead->net_value = $_POST['net_sum'];
             $stockhead->sum_margin = $_POST['margin_sum'];
             $stockhead->total_a = $_POST['totala_sum'];
             $stockhead->sundry_debtor = $_POST['sun_debtor'];
             $stockhead->margin_two = $_POST['margin2_sum'];
             $stockhead->total_b = $_POST['totalb_sum'];
             $stockhead->drawing_power = $_POST['draw_power'];
             $stockhead['created_by'] = \Session::get('id');
             $stockhead['last_updated_by'] = \Session::get('id');
             $stockhead['updated_at'] = date('Y-m-d h:s:i');
             $stockhead['created_at'] = date('Y-m-d h:s:i');
             $stockhead['organization_id'] = \Session::get('organization');
             $stockhead['location_id'] = \Session::get('location');
             
             $stockhead->save();
     
             $stockheadId = $stockhead->skt_id;
          // lines save on by one
             $stocklines = new Stockstmtbanklines();
            
             //cosmetics
             $stocklines->particulars = $_POST['cosmetics'];
             $stocklines->stkid = $stockheadId;
             $stocklines->stock_on_date = $_POST['stkbank_cos'];
             $stocklines->unit_weight = $_POST['unit_cos'];
             $stocklines->mar_purc_rate = $_POST['marate_cos'];
             $stocklines->total_value = $_POST['totalbank_cos'];
             $stocklines['created_by'] = \Session::get('id');
             $stocklines['created_at'] = date('Y-m-d h:s:i');
             $stocklines['company_id'] = \Session::get('companyid');
             $stocklines['updated_at'] = date('Y-m-d h:s:i');
        
             $stocklines->save();

                //SASTRIC AYURVEDA
                
             $stockline = new Stockstmtbanklines();
            
             $stockline->particulars = $_POST['sastric_ayurvedha'];
             $stockline->stkid = $stockheadId;
             $stockline->stock_on_date = $_POST['stkbank_sacayv'];
             $stockline->unit_weight = $_POST['unit_sacayv'];
             $stockline->mar_purc_rate = $_POST['marate_sacayv'];
             $stockline->total_value = $_POST['totalbank_sacayv'];
             $stockline['created_by'] = \Session::get('id');
             $stockline['created_at'] = date('Y-m-d h:s:i');
             $stockline['company_id'] = \Session::get('companyid');
             $stockline['updated_at'] = date('Y-m-d h:s:i');
        
             $stockline->save();
        
            //SASTRIC SIDDHA
                
                $stkline_sidha = new Stockstmtbanklines();
               
                $stkline_sidha->particulars = $_POST['sastric_siddha'];
                $stkline_sidha->stkid = $stockheadId;
                $stkline_sidha->stock_on_date = $_POST['stkbank_sacsid'];
                $stkline_sidha->unit_weight = $_POST['unit_sacsid'];
                $stkline_sidha->mar_purc_rate = $_POST['marate_sacsid'];
                $stkline_sidha->total_value = $_POST['totalbank_sacsid'];
                $stkline_sidha['created_by'] = \Session::get('id');
                $stkline_sidha['created_at'] = date('Y-m-d h:s:i');
                $stkline_sidha['company_id'] = \Session::get('companyid');
                $stkline_sidha['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sidha->save();
        // SIDDHA
                
                $stkline_sidhaa = new Stockstmtbanklines();
               
                $stkline_sidhaa->particulars = $_POST['siddha'];
                $stkline_sidhaa->stkid = $stockheadId;
                $stkline_sidhaa->stock_on_date = $_POST['stkbank_sida'];
                $stkline_sidhaa->unit_weight = $_POST['unit_sida'];
                $stkline_sidhaa->mar_purc_rate = $_POST['marate_sida'];
                $stkline_sidhaa->total_value = $_POST['totalbank_sida'];
                $stkline_sidhaa['created_by'] = \Session::get('id');
                $stkline_sidhaa['created_at'] = date('Y-m-d h:s:i');
                $stkline_sidhaa['company_id'] = \Session::get('companyid');
                $stkline_sidhaa['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sidhaa->save();
        
        // CARTON
                
                $stkline_carton = new Stockstmtbanklines();
               
                $stkline_carton->particulars = $_POST['carton'];
                $stkline_carton->stkid = $stockheadId;
                $stkline_carton->stock_on_date = $_POST['stkbank_carton'];
                $stkline_carton->unit_weight = $_POST['unit_carton'];
                $stkline_carton->mar_purc_rate = $_POST['marate_carton'];
                $stkline_carton->total_value = $_POST['totalbank_carton'];
                $stkline_carton['created_by'] = \Session::get('id');
                $stkline_carton['created_at'] = date('Y-m-d h:s:i');
                $stkline_carton['company_id'] = \Session::get('companyid');
                $stkline_carton['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_carton->save();
        
        // containers
                
                $stkline_containers = new Stockstmtbanklines();
               
                $stkline_containers->particulars = $_POST['containers'];
                $stkline_containers->stkid = $stockheadId;
                $stkline_containers->stock_on_date = $_POST['stkbank_container'];
                $stkline_containers->unit_weight = $_POST['unit_container'];
                $stkline_containers->mar_purc_rate = $_POST['marate_container'];
                $stkline_containers->total_value = $_POST['totalbank_container'];
                $stkline_containers['created_by'] = \Session::get('id');
                $stkline_containers['created_at'] = date('Y-m-d h:s:i');
                $stkline_containers['company_id'] = \Session::get('companyid');
                $stkline_containers['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_containers->save();
                
                // insert
                
                $stkline_insert = new Stockstmtbanklines();
               
                $stkline_insert->particulars = $_POST['insert'];
                $stkline_insert->stkid = $stockheadId;
                $stkline_insert->stock_on_date = $_POST['stkbank_insert'];
                $stkline_insert->unit_weight = $_POST['unit_insert'];
                $stkline_insert->mar_purc_rate = $_POST['marate_insert'];
                $stkline_insert->total_value = $_POST['totalbank_insert'];
                $stkline_insert['created_by'] = \Session::get('id');
                $stkline_insert['created_at'] = date('Y-m-d h:s:i');
                $stkline_insert['company_id'] = \Session::get('companyid');
                $stkline_insert['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_insert->save();
                
                // lebel
                
                $stkline_lebel = new Stockstmtbanklines();
               
                $stkline_lebel->particulars = $_POST['labels'];
                $stkline_lebel->stkid = $stockheadId;
                $stkline_lebel->stock_on_date = $_POST['stkbank_label'];
                $stkline_lebel->unit_weight = $_POST['unit_label'];
                $stkline_lebel->mar_purc_rate = $_POST['marate_label'];
                $stkline_lebel->total_value = $_POST['totalbank_label'];
                $stkline_lebel['created_by'] = \Session::get('id');
                $stkline_lebel['created_at'] = date('Y-m-d h:s:i');
                $stkline_lebel['company_id'] = \Session::get('companyid');
                $stkline_lebel['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_lebel->save();
                
                // oitem
                
                $stkline_oitem = new Stockstmtbanklines();
               
                $stkline_oitem->particulars = $_POST['oitem'];
                $stkline_oitem->stkid = $stockheadId;
                $stkline_oitem->stock_on_date = $_POST['stkbank_oitem'];
                $stkline_oitem->unit_weight = $_POST['unit_oitem'];
                $stkline_oitem->mar_purc_rate = $_POST['marate_oitem'];
                $stkline_oitem->total_value = $_POST['totalbank_oitem'];
                $stkline_oitem['created_by'] = \Session::get('id');
                $stkline_oitem['created_at'] = date('Y-m-d h:s:i');
                $stkline_oitem['company_id'] = \Session::get('companyid');
                $stkline_oitem['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_oitem->save();
                
                // sticker
                
                $stkline_sticker = new Stockstmtbanklines();
               
                $stkline_sticker->particulars = $_POST['sticker'];
                $stkline_sticker->stkid = $stockheadId;
                $stkline_sticker->stock_on_date = $_POST['stkbank_sticker'];
                $stkline_sticker->unit_weight = $_POST['unit_sticker'];
                $stkline_sticker->mar_purc_rate = $_POST['marate_sticker'];
                $stkline_sticker->total_value = $_POST['totalbank_sticker'];
                $stkline_sticker['created_by'] = \Session::get('id');
                $stkline_sticker['created_at'] = date('Y-m-d h:s:i');
                $stkline_sticker['company_id'] = \Session::get('companyid');
                $stkline_sticker['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sticker->save();
                
                // chemicals
                
                $stkline_chemicals = new Stockstmtbanklines();
               
                $stkline_chemicals->particulars = $_POST['chemicals'];
                $stkline_chemicals->stkid = $stockheadId;
                $stkline_chemicals->stock_on_date = $_POST['stkbank_chemical'];
                $stkline_chemicals->unit_weight = $_POST['unit_chemical'];
                $stkline_chemicals->mar_purc_rate = $_POST['marate_chemical'];
                $stkline_chemicals->total_value = $_POST['totalbank_chemical'];
                $stkline_chemicals['created_by'] = \Session::get('id');
                $stkline_chemicals['created_at'] = date('Y-m-d h:s:i');
                $stkline_chemicals['company_id'] = \Session::get('companyid');
                $stkline_chemicals['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_chemicals->save();
                
                // lab
                
                $stkline_lab = new Stockstmtbanklines();
               
                $stkline_lab->particulars = $_POST['lab'];
                $stkline_lab->stkid = $stockheadId;
                $stkline_lab->stock_on_date = $_POST['stkbank_lab'];
                $stkline_lab->unit_weight = $_POST['unit_lab'];
                $stkline_lab->mar_purc_rate = $_POST['marate_lab'];
                $stkline_lab->total_value = $_POST['totalbank_lab'];
                $stkline_lab['created_by'] = \Session::get('id');
                $stkline_lab['created_at'] = date('Y-m-d h:s:i');
                $stkline_lab['company_id'] = \Session::get('companyid');
                $stkline_lab['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_lab->save();
                
                // leaf
                
                $stkline_leaf = new Stockstmtbanklines();
               
                $stkline_leaf->particulars = $_POST['leaf'];
                $stkline_leaf->stkid = $stockheadId;
                $stkline_leaf->stock_on_date = $_POST['stkbank_leaf'];
                $stkline_leaf->unit_weight = $_POST['unit_leaf'];
                $stkline_leaf->mar_purc_rate = $_POST['marate_leaf'];
                $stkline_leaf->total_value = $_POST['totalbank_leaf'];
                $stkline_leaf['created_by'] = \Session::get('id');
                $stkline_leaf['created_at'] = date('Y-m-d h:s:i');
                $stkline_leaf['company_id'] = \Session::get('companyid');
                $stkline_leaf['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_leaf->save();
                
                // oil
                
                $stkline_oil = new Stockstmtbanklines();
               
                $stkline_oil->particulars = $_POST['oil'];
                $stkline_oil->stkid = $stockheadId;
                $stkline_oil->stock_on_date = $_POST['stkbank_oil'];
                $stkline_oil->unit_weight = $_POST['unit_oil'];
                $stkline_oil->mar_purc_rate = $_POST['marate_oil'];
                $stkline_oil->total_value = $_POST['totalbank_oil'];
                $stkline_oil['created_by'] = \Session::get('id');
                $stkline_oil['created_at'] = date('Y-m-d h:s:i');
                $stkline_oil['company_id'] = \Session::get('companyid');
                $stkline_oil['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_oil->save();
                
                // raw
                
                $stkline_raw = new Stockstmtbanklines();
               
                $stkline_raw->particulars = $_POST['raw'];
                $stkline_raw->stkid = $stockheadId;
                $stkline_raw->stock_on_date = $_POST['stkbank_raw'];
                $stkline_raw->unit_weight = $_POST['unit_raw'];
                $stkline_raw->mar_purc_rate = $_POST['marate_raw'];
                $stkline_raw->total_value = $_POST['totalbank_raw'];
                $stkline_raw['created_by'] = \Session::get('id');
                $stkline_raw['created_at'] = date('Y-m-d h:s:i');
                $stkline_raw['company_id'] = \Session::get('companyid');
                $stkline_raw['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_raw->save();
                
                // SFG COSMETICS
                
                $stkline_sfg_cos = new Stockstmtbanklines();
               
                $stkline_sfg_cos->particulars = $_POST['semi_cosmetics'];
                $stkline_sfg_cos->stkid = $stockheadId;
                $stkline_sfg_cos->stock_on_date = $_POST['stkbank_sfg_cos'];
                $stkline_sfg_cos->unit_weight = $_POST['unit_sfg_cos'];
                $stkline_sfg_cos->mar_purc_rate = $_POST['marate_sfg_cos'];
                $stkline_sfg_cos->total_value = $_POST['totalbank_sfg_cos'];
                $stkline_sfg_cos['created_by'] = \Session::get('id');
                $stkline_sfg_cos['created_at'] = date('Y-m-d h:s:i');
                $stkline_sfg_cos['company_id'] = \Session::get('companyid');
                $stkline_sfg_cos['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sfg_cos->save();
                
                // SFG SASTRIC AYURVEDA
                
                $stkline_sfg_sacayu = new Stockstmtbanklines();
               
                $stkline_sfg_sacayu->particulars = $_POST['semi_sasay'];
                $stkline_sfg_sacayu->stkid = $stockheadId;
                $stkline_sfg_sacayu->stock_on_date = $_POST['stkbank_sfg_sacayu'];
                $stkline_sfg_sacayu->unit_weight = $_POST['unit_sfg_sacayu'];
                $stkline_sfg_sacayu->mar_purc_rate = $_POST['marate_sfg_sacayu'];
                $stkline_sfg_sacayu->total_value = $_POST['totalbank_sfg_sacayu'];
                $stkline_sfg_sacayu['created_by'] = \Session::get('id');
                $stkline_sfg_sacayu['created_at'] = date('Y-m-d h:s:i');
                $stkline_sfg_sacayu['company_id'] = \Session::get('companyid');
                $stkline_sfg_sacayu['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sfg_sacayu->save();
                
                // SFG SASTRIC SIDDHA
                
                $stkline_sfg_sacsid = new Stockstmtbanklines();
               
                $stkline_sfg_sacsid->particulars = $_POST['semi_sassidha'];
                $stkline_sfg_sacsid->stkid = $stockheadId;
                $stkline_sfg_sacsid->stock_on_date = $_POST['stkbank_sfg_sacsid'];
                $stkline_sfg_sacsid->unit_weight = $_POST['unit_sfg_sacsid'];
                $stkline_sfg_sacsid->mar_purc_rate = $_POST['marate_sfg_sacsid'];
                $stkline_sfg_sacsid->total_value = $_POST['totalbank_sfg_sacsid'];
                $stkline_sfg_sacsid['created_by'] = \Session::get('id');
                $stkline_sfg_sacsid['created_at'] = date('Y-m-d h:s:i');
                $stkline_sfg_sacsid['company_id'] = \Session::get('companyid');
                $stkline_sfg_sacsid['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sfg_sacsid->save();
                
                // SFG SIDDHA
                
                $stkline_sfg_sidha = new Stockstmtbanklines();
               
                $stkline_sfg_sidha->particulars = $_POST['semi_sidha'];
                $stkline_sfg_sidha->stkid = $stockheadId;
                $stkline_sfg_sidha->stock_on_date = $_POST['stkbank_sfg_sidha'];
                $stkline_sfg_sidha->unit_weight = $_POST['unit_sfg_sidha'];
                $stkline_sfg_sidha->mar_purc_rate = $_POST['marate_sfg_sidha'];
                $stkline_sfg_sidha->total_value = $_POST['totalbank_sfg_sidha'];
                $stkline_sfg_sidha['created_by'] = \Session::get('id');
                $stkline_sfg_sidha['created_at'] = date('Y-m-d h:s:i');
                $stkline_sfg_sidha['company_id'] = \Session::get('companyid');
                $stkline_sfg_sidha['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sfg_sidha->save();
                
                                // SFG PROCESSING GODOWN
                
                $stkline_pro_godown = new Stockstmtbanklines();
               
                $stkline_pro_godown->particulars = $_POST['pro_godown'];
                $stkline_pro_godown->stkid = $stockheadId;
                $stkline_pro_godown->stock_on_date = $_POST['stkbank_pro_godown'];
                $stkline_pro_godown->unit_weight = $_POST['unit_pro_godown'];
                $stkline_pro_godown->mar_purc_rate = $_POST['marate_pro_godown'];
                $stkline_pro_godown->total_value = $_POST['totalbank_pro_godown'];
                $stkline_pro_godown['created_by'] = \Session::get('id');
                $stkline_pro_godown['created_at'] = date('Y-m-d h:s:i');
                $stkline_pro_godown['company_id'] = \Session::get('companyid');
                $stkline_pro_godown['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_pro_godown->save();
                
                                // SFG - OTHER PROMOTIONAL AND ACCESSARY ITEMS
                
                $stkline_access_item = new Stockstmtbanklines();
               
                $stkline_access_item->particulars = $_POST['access_item'];
                $stkline_access_item->stkid = $stockheadId;
                $stkline_access_item->stock_on_date = $_POST['stkbank_access_item'];
                $stkline_access_item->unit_weight = $_POST['unit_access_item'];
                $stkline_access_item->mar_purc_rate = $_POST['marate_access_item'];
                $stkline_access_item->total_value = $_POST['totalbank_access_item'];
                $stkline_access_item['created_by'] = \Session::get('id');
                $stkline_access_item['created_at'] = date('Y-m-d h:s:i');
                $stkline_access_item['company_id'] = \Session::get('companyid');
                $stkline_access_item['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_access_item->save();
        /***********************sop Lines save end *********************/
        
        \DB::commit();
        
        
        return response()->json(array('status' => 'success', 'message' => 'Saved Successfully'));

        
        } catch (\Illuminate\Database\QueryException $e) {
        $message = explode('(', $e->getMessage());
        $dbCode = rtrim($message[0], ']');
        $dbCode = trim($dbCode, '[');
        
        \DB::rollback();
        return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
        
 
 

    }
    
    
    public function finalsave(Request $request)
    {

        
        \DB::beginTransaction();
        
        
        
          try {
              


             $stockhead = new Stockstmtbank();
            // header save
             $stockhead->generate_date = date('Y-m-d');
             $select_date = $_POST['select_date'];
             $date = new DateTime($select_date . '-01');
             $selected_date = $date->format('Y-m-t'); 
             $stockhead->select_date = $selected_date;
             $stockhead->status = "FINAL";
             $stockhead->grand_total = $_POST['ovrall_total'];
             $stockhead->total_stock = $_POST['ovrall_total_qoh_bank'];
             $stockhead->total_value_sum	 = $_POST['sum_tl_val'];
             $stockhead->trade_creditors = $_POST['trade_sum'];
             $stockhead->net_value = $_POST['net_sum'];
             $stockhead->sum_margin = $_POST['margin_sum'];
             $stockhead->total_a = $_POST['totala_sum'];
             $stockhead->sundry_debtor = $_POST['sun_debtor'];
             $stockhead->margin_two = $_POST['margin2_sum'];
             $stockhead->total_b = $_POST['totalb_sum'];
             $stockhead->drawing_power = $_POST['draw_power'];
             $stockhead['created_by'] = \Session::get('id');
             $stockhead['last_updated_by'] = \Session::get('id');
             $stockhead['updated_at'] = date('Y-m-d h:s:i');
             $stockhead['created_at'] = date('Y-m-d h:s:i');
             $stockhead['organization_id'] = \Session::get('organization');
             $stockhead['location_id'] = \Session::get('location');
             
             $stockhead->save();
     
             $stockheadId = $stockhead->skt_id;
          // lines save on by one
             $stocklines = new Stockstmtbanklines();
            
             //cosmetics
             $stocklines->particulars = $_POST['cosmetics'];
             $stocklines->stkid = $stockheadId;
             $stocklines->stock_on_date = $_POST['stkbank_cos'];
             $stocklines->unit_weight = $_POST['unit_cos'];
             $stocklines->mar_purc_rate = $_POST['marate_cos'];
             $stocklines->total_value = $_POST['totalbank_cos'];
             $stocklines['created_by'] = \Session::get('id');
             $stocklines['created_at'] = date('Y-m-d h:s:i');
             $stocklines['company_id'] = \Session::get('companyid');
             $stocklines['updated_at'] = date('Y-m-d h:s:i');
        
             $stocklines->save();

                //SASTRIC AYURVEDA
                
             $stockline = new Stockstmtbanklines();
            
             $stockline->particulars = $_POST['sastric_ayurvedha'];
             $stockline->stkid = $stockheadId;
             $stockline->stock_on_date = $_POST['stkbank_sacayv'];
             $stockline->unit_weight = $_POST['unit_sacayv'];
             $stockline->mar_purc_rate = $_POST['marate_sacayv'];
             $stockline->total_value = $_POST['totalbank_sacayv'];
             $stockline['created_by'] = \Session::get('id');
             $stockline['created_at'] = date('Y-m-d h:s:i');
             $stockline['company_id'] = \Session::get('companyid');
             $stockline['updated_at'] = date('Y-m-d h:s:i');
        
             $stockline->save();
        
            //SASTRIC SIDDHA
                
                $stkline_sidha = new Stockstmtbanklines();
               
                $stkline_sidha->particulars = $_POST['sastric_siddha'];
                $stkline_sidha->stkid = $stockheadId;
                $stkline_sidha->stock_on_date = $_POST['stkbank_sacsid'];
                $stkline_sidha->unit_weight = $_POST['unit_sacsid'];
                $stkline_sidha->mar_purc_rate = $_POST['marate_sacsid'];
                $stkline_sidha->total_value = $_POST['totalbank_sacsid'];
                $stkline_sidha['created_by'] = \Session::get('id');
                $stkline_sidha['created_at'] = date('Y-m-d h:s:i');
                $stkline_sidha['company_id'] = \Session::get('companyid');
                $stkline_sidha['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sidha->save();
        // SIDDHA
                
                $stkline_sidhaa = new Stockstmtbanklines();
               
                $stkline_sidhaa->particulars = $_POST['siddha'];
                $stkline_sidhaa->stkid = $stockheadId;
                $stkline_sidhaa->stock_on_date = $_POST['stkbank_sida'];
                $stkline_sidhaa->unit_weight = $_POST['unit_sida'];
                $stkline_sidhaa->mar_purc_rate = $_POST['marate_sida'];
                $stkline_sidhaa->total_value = $_POST['totalbank_sida'];
                $stkline_sidhaa['created_by'] = \Session::get('id');
                $stkline_sidhaa['created_at'] = date('Y-m-d h:s:i');
                $stkline_sidhaa['company_id'] = \Session::get('companyid');
                $stkline_sidhaa['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sidhaa->save();
        
        // CARTON
                
                $stkline_carton = new Stockstmtbanklines();
               
                $stkline_carton->particulars = $_POST['carton'];
                $stkline_carton->stkid = $stockheadId;
                $stkline_carton->stock_on_date = $_POST['stkbank_carton'];
                $stkline_carton->unit_weight = $_POST['unit_carton'];
                $stkline_carton->mar_purc_rate = $_POST['marate_carton'];
                $stkline_carton->total_value = $_POST['totalbank_carton'];
                $stkline_carton['created_by'] = \Session::get('id');
                $stkline_carton['created_at'] = date('Y-m-d h:s:i');
                $stkline_carton['company_id'] = \Session::get('companyid');
                $stkline_carton['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_carton->save();
        
        // containers
                
                $stkline_containers = new Stockstmtbanklines();
               
                $stkline_containers->particulars = $_POST['containers'];
                $stkline_containers->stkid = $stockheadId;
                $stkline_containers->stock_on_date = $_POST['stkbank_container'];
                $stkline_containers->unit_weight = $_POST['unit_container'];
                $stkline_containers->mar_purc_rate = $_POST['marate_container'];
                $stkline_containers->total_value = $_POST['totalbank_container'];
                $stkline_containers['created_by'] = \Session::get('id');
                $stkline_containers['created_at'] = date('Y-m-d h:s:i');
                $stkline_containers['company_id'] = \Session::get('companyid');
                $stkline_containers['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_containers->save();
                
                // insert
                
                $stkline_insert = new Stockstmtbanklines();
               
                $stkline_insert->particulars = $_POST['insert'];
                $stkline_insert->stkid = $stockheadId;
                $stkline_insert->stock_on_date = $_POST['stkbank_insert'];
                $stkline_insert->unit_weight = $_POST['unit_insert'];
                $stkline_insert->mar_purc_rate = $_POST['marate_insert'];
                $stkline_insert->total_value = $_POST['totalbank_insert'];
                $stkline_insert['created_by'] = \Session::get('id');
                $stkline_insert['created_at'] = date('Y-m-d h:s:i');
                $stkline_insert['company_id'] = \Session::get('companyid');
                $stkline_insert['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_insert->save();
                
                // lebel
                
                $stkline_lebel = new Stockstmtbanklines();
               
                $stkline_lebel->particulars = $_POST['labels'];
                $stkline_lebel->stkid = $stockheadId;
                $stkline_lebel->stock_on_date = $_POST['stkbank_label'];
                $stkline_lebel->unit_weight = $_POST['unit_label'];
                $stkline_lebel->mar_purc_rate = $_POST['marate_label'];
                $stkline_lebel->total_value = $_POST['totalbank_label'];
                $stkline_lebel['created_by'] = \Session::get('id');
                $stkline_lebel['created_at'] = date('Y-m-d h:s:i');
                $stkline_lebel['company_id'] = \Session::get('companyid');
                $stkline_lebel['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_lebel->save();
                
                // oitem
                
                $stkline_oitem = new Stockstmtbanklines();
               
                $stkline_oitem->particulars = $_POST['oitem'];
                $stkline_oitem->stkid = $stockheadId;
                $stkline_oitem->stock_on_date = $_POST['stkbank_oitem'];
                $stkline_oitem->unit_weight = $_POST['unit_oitem'];
                $stkline_oitem->mar_purc_rate = $_POST['marate_oitem'];
                $stkline_oitem->total_value = $_POST['totalbank_oitem'];
                $stkline_oitem['created_by'] = \Session::get('id');
                $stkline_oitem['created_at'] = date('Y-m-d h:s:i');
                $stkline_oitem['company_id'] = \Session::get('companyid');
                $stkline_oitem['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_oitem->save();
                
                // sticker
                
                $stkline_sticker = new Stockstmtbanklines();
               
                $stkline_sticker->particulars = $_POST['sticker'];
                $stkline_sticker->stkid = $stockheadId;
                $stkline_sticker->stock_on_date = $_POST['stkbank_sticker'];
                $stkline_sticker->unit_weight = $_POST['unit_sticker'];
                $stkline_sticker->mar_purc_rate = $_POST['marate_sticker'];
                $stkline_sticker->total_value = $_POST['totalbank_sticker'];
                $stkline_sticker['created_by'] = \Session::get('id');
                $stkline_sticker['created_at'] = date('Y-m-d h:s:i');
                $stkline_sticker['company_id'] = \Session::get('companyid');
                $stkline_sticker['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sticker->save();
                
                // chemicals
                
                $stkline_chemicals = new Stockstmtbanklines();
               
                $stkline_chemicals->particulars = $_POST['chemicals'];
                $stkline_chemicals->stkid = $stockheadId;
                $stkline_chemicals->stock_on_date = $_POST['stkbank_chemical'];
                $stkline_chemicals->unit_weight = $_POST['unit_chemical'];
                $stkline_chemicals->mar_purc_rate = $_POST['marate_chemical'];
                $stkline_chemicals->total_value = $_POST['totalbank_chemical'];
                $stkline_chemicals['created_by'] = \Session::get('id');
                $stkline_chemicals['created_at'] = date('Y-m-d h:s:i');
                $stkline_chemicals['company_id'] = \Session::get('companyid');
                $stkline_chemicals['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_chemicals->save();
                
                // lab
                
                $stkline_lab = new Stockstmtbanklines();
               
                $stkline_lab->particulars = $_POST['lab'];
                $stkline_lab->stkid = $stockheadId;
                $stkline_lab->stock_on_date = $_POST['stkbank_lab'];
                $stkline_lab->unit_weight = $_POST['unit_lab'];
                $stkline_lab->mar_purc_rate = $_POST['marate_lab'];
                $stkline_lab->total_value = $_POST['totalbank_lab'];
                $stkline_lab['created_by'] = \Session::get('id');
                $stkline_lab['created_at'] = date('Y-m-d h:s:i');
                $stkline_lab['company_id'] = \Session::get('companyid');
                $stkline_lab['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_lab->save();
                
                // leaf
                
                $stkline_leaf = new Stockstmtbanklines();
               
                $stkline_leaf->particulars = $_POST['leaf'];
                $stkline_leaf->stkid = $stockheadId;
                $stkline_leaf->stock_on_date = $_POST['stkbank_leaf'];
                $stkline_leaf->unit_weight = $_POST['unit_leaf'];
                $stkline_leaf->mar_purc_rate = $_POST['marate_leaf'];
                $stkline_leaf->total_value = $_POST['totalbank_leaf'];
                $stkline_leaf['created_by'] = \Session::get('id');
                $stkline_leaf['created_at'] = date('Y-m-d h:s:i');
                $stkline_leaf['company_id'] = \Session::get('companyid');
                $stkline_leaf['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_leaf->save();
                
                // oil
                
                $stkline_oil = new Stockstmtbanklines();
               
                $stkline_oil->particulars = $_POST['oil'];
                $stkline_oil->stkid = $stockheadId;
                $stkline_oil->stock_on_date = $_POST['stkbank_oil'];
                $stkline_oil->unit_weight = $_POST['unit_oil'];
                $stkline_oil->mar_purc_rate = $_POST['marate_oil'];
                $stkline_oil->total_value = $_POST['totalbank_oil'];
                $stkline_oil['created_by'] = \Session::get('id');
                $stkline_oil['created_at'] = date('Y-m-d h:s:i');
                $stkline_oil['company_id'] = \Session::get('companyid');
                $stkline_oil['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_oil->save();
                
                // raw
                
                $stkline_raw = new Stockstmtbanklines();
               
                $stkline_raw->particulars = $_POST['raw'];
                $stkline_raw->stkid = $stockheadId;
                $stkline_raw->stock_on_date = $_POST['stkbank_raw'];
                $stkline_raw->unit_weight = $_POST['unit_raw'];
                $stkline_raw->mar_purc_rate = $_POST['marate_raw'];
                $stkline_raw->total_value = $_POST['totalbank_raw'];
                $stkline_raw['created_by'] = \Session::get('id');
                $stkline_raw['created_at'] = date('Y-m-d h:s:i');
                $stkline_raw['company_id'] = \Session::get('companyid');
                $stkline_raw['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_raw->save();
                
                // SFG COSMETICS
                
                $stkline_sfg_cos = new Stockstmtbanklines();
               
                $stkline_sfg_cos->particulars = $_POST['semi_cosmetics'];
                $stkline_sfg_cos->stkid = $stockheadId;
                $stkline_sfg_cos->stock_on_date = $_POST['stkbank_sfg_cos'];
                $stkline_sfg_cos->unit_weight = $_POST['unit_sfg_cos'];
                $stkline_sfg_cos->mar_purc_rate = $_POST['marate_sfg_cos'];
                $stkline_sfg_cos->total_value = $_POST['totalbank_sfg_cos'];
                $stkline_sfg_cos['created_by'] = \Session::get('id');
                $stkline_sfg_cos['created_at'] = date('Y-m-d h:s:i');
                $stkline_sfg_cos['company_id'] = \Session::get('companyid');
                $stkline_sfg_cos['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sfg_cos->save();
                
                // SFG SASTRIC AYURVEDA
                
                $stkline_sfg_sacayu = new Stockstmtbanklines();
               
                $stkline_sfg_sacayu->particulars = $_POST['semi_sasay'];
                $stkline_sfg_sacayu->stkid = $stockheadId;
                $stkline_sfg_sacayu->stock_on_date = $_POST['stkbank_sfg_sacayu'];
                $stkline_sfg_sacayu->unit_weight = $_POST['unit_sfg_sacayu'];
                $stkline_sfg_sacayu->mar_purc_rate = $_POST['marate_sfg_sacayu'];
                $stkline_sfg_sacayu->total_value = $_POST['totalbank_sfg_sacayu'];
                $stkline_sfg_sacayu['created_by'] = \Session::get('id');
                $stkline_sfg_sacayu['created_at'] = date('Y-m-d h:s:i');
                $stkline_sfg_sacayu['company_id'] = \Session::get('companyid');
                $stkline_sfg_sacayu['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sfg_sacayu->save();
                
                // SFG SASTRIC SIDDHA
                
                $stkline_sfg_sacsid = new Stockstmtbanklines();
               
                $stkline_sfg_sacsid->particulars = $_POST['semi_sassidha'];
                $stkline_sfg_sacsid->stkid = $stockheadId;
                $stkline_sfg_sacsid->stock_on_date = $_POST['stkbank_sfg_sacsid'];
                $stkline_sfg_sacsid->unit_weight = $_POST['unit_sfg_sacsid'];
                $stkline_sfg_sacsid->mar_purc_rate = $_POST['marate_sfg_sacsid'];
                $stkline_sfg_sacsid->total_value = $_POST['totalbank_sfg_sacsid'];
                $stkline_sfg_sacsid['created_by'] = \Session::get('id');
                $stkline_sfg_sacsid['created_at'] = date('Y-m-d h:s:i');
                $stkline_sfg_sacsid['company_id'] = \Session::get('companyid');
                $stkline_sfg_sacsid['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sfg_sacsid->save();
                
                // SFG SIDDHA
                
                $stkline_sfg_sidha = new Stockstmtbanklines();
               
                $stkline_sfg_sidha->particulars = $_POST['semi_sidha'];
                $stkline_sfg_sidha->stkid = $stockheadId;
                $stkline_sfg_sidha->stock_on_date = $_POST['stkbank_sfg_sidha'];
                $stkline_sfg_sidha->unit_weight = $_POST['unit_sfg_sidha'];
                $stkline_sfg_sidha->mar_purc_rate = $_POST['marate_sfg_sidha'];
                $stkline_sfg_sidha->total_value = $_POST['totalbank_sfg_sidha'];
                $stkline_sfg_sidha['created_by'] = \Session::get('id');
                $stkline_sfg_sidha['created_at'] = date('Y-m-d h:s:i');
                $stkline_sfg_sidha['company_id'] = \Session::get('companyid');
                $stkline_sfg_sidha['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_sfg_sidha->save();
                
                                                // SFG PROCESSING GODOWN
                
                $stkline_pro_godown = new Stockstmtbanklines();
               
                $stkline_pro_godown->particulars = $_POST['pro_godown'];
                $stkline_pro_godown->stkid = $stockheadId;
                $stkline_pro_godown->stock_on_date = $_POST['stkbank_pro_godown'];
                $stkline_pro_godown->unit_weight = $_POST['unit_pro_godown'];
                $stkline_pro_godown->mar_purc_rate = $_POST['marate_pro_godown'];
                $stkline_pro_godown->total_value = $_POST['totalbank_pro_godown'];
                $stkline_pro_godown['created_by'] = \Session::get('id');
                $stkline_pro_godown['created_at'] = date('Y-m-d h:s:i');
                $stkline_pro_godown['company_id'] = \Session::get('companyid');
                $stkline_pro_godown['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_pro_godown->save();
                
                                // SFG - OTHER PROMOTIONAL AND ACCESSARY ITEMS
                
                $stkline_access_item = new Stockstmtbanklines();
               
                $stkline_access_item->particulars = $_POST['access_item'];
                $stkline_access_item->stkid = $stockheadId;
                $stkline_access_item->stock_on_date = $_POST['stkbank_access_item'];
                $stkline_access_item->unit_weight = $_POST['unit_access_item'];
                $stkline_access_item->mar_purc_rate = $_POST['marate_access_item'];
                $stkline_access_item->total_value = $_POST['totalbank_access_item'];
                $stkline_access_item['created_by'] = \Session::get('id');
                $stkline_access_item['created_at'] = date('Y-m-d h:s:i');
                $stkline_access_item['company_id'] = \Session::get('companyid');
                $stkline_access_item['updated_at'] = date('Y-m-d h:s:i');
           
                $stkline_access_item->save();
        /***********************sop Lines save end *********************/
        
        \DB::commit();
        
        
        return response()->json(array('status' => 'success', 'message' => 'Saved Successfully'));

        
        } catch (\Illuminate\Database\QueryException $e) {
        $message = explode('(', $e->getMessage());
        $dbCode = rtrim($message[0], ']');
        $dbCode = trim($dbCode, '[');
        
        \DB::rollback();
        return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
    
    
    
    
    
    
    
    
}