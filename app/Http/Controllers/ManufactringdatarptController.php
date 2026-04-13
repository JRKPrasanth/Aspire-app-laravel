<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class ManufactringdatarptController extends Controller
{

 
      public function index(Request $request) {
     
			$this->data['pageMethod']=\Request::route()->getName();
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            
    
              $this->data['manufact_data'] = \DB::select("SELECT v1.cat_grp,v1.uom_code,ROUND((sum(v1.qty)-(sum(v1.qty)*0.15)),0) as qty_rel,ROUND(SUM(v1.qty), 0) AS qty,ROUND(SUM(v1.total), 2) AS total FROM (SELECT
            CASE WHEN m_product_category_t.category_name ='COSMETICS' THEN 'PERSONAL CARE PRODUCTS' ELSE 'SIDDHA MEDICINE' END AS cat_grp,
            m_uom_codes_t.uom_code, 
            'P_Product' AS e_type,
            r_job_cost_hdr_tbl.qty,
            r_job_cost_hdr_tbl.total
        FROM
            `r_job_cost_hdr_tbl`
        LEFT JOIN m_products_t ON m_products_t.concatenated_product = r_job_cost_hdr_tbl.product_name
        LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id  = m_products_t.product_category_id 
        LEFT JOIN m_product_subcategory_t ON m_product_subcategory_t.product_subcategory_id  = m_products_t.product_subcategory_id 
        LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id  = m_products_t.product_group_id 
        LEFT JOIN m_product_variants_t ON m_product_variants_t.product_variant_id  = m_products_t.product_variant_id 
        LEFT JOIN m_product_type_t ON m_product_type_t.product_type_id  = m_products_t.product_type_id 
        LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = m_products_t.primary_uom_id
        JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id = r_job_cost_hdr_tbl.job_id
        JOIN w_productionplan_hdr_t ON w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id  where 1=1 and  r_job_cost_hdr_tbl.job_date and  r_job_cost_hdr_tbl.job_date between '$start_date' and '$end_date'
        AND m_product_subcategory_t.subcategory_name ='SEMI PRODUCT' ORDER BY `r_job_cost_hdr_tbl`.`r_job_cost_hdr_id` ASC)v1 GROUP BY v1.cat_grp,v1.uom_code");
    


       return view('manufactringdata.report', $this->data);
     
   }
    
    
}