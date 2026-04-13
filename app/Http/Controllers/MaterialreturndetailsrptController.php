<?php

namespace App\Http\Controllers;
use yajra\datatables\datatables;
use App\materialreturndetailsrpt;
use Illuminate\Http\Request;
use DB;
class MaterialreturndetailsrptController extends Controller
{
   public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }
    public function index()
    {
    
return view('materialreturndetailsrpt.table', $this->data);       
    }

    
  public function getmrdrpt(Request $request)
	{

    $SQL = "SELECT * from (
SELECT w_qa_submitstage_line_t.batchno,
                            w_qa_submitstage_trx_t.production_qty as hdr_production_qty,
                            w_qa_submitstage_line_t.production_qty as line_production_qty,
                            w_qa_submitstage_line_t.return_qty,
                            w_jobcard_hdr_t.job_no,
                            line_products.concatenated_product as line_product,
                            hdr_products.concatenated_product as hdr_product,
                            hdr_uom_codes.uom_code as hdr_uom,
                            line_uom_codes.uom_code as line_uom,
                            m_subinventory_t.subinventory_name,
                           CONCAT(
            m_sublocators_t.locator_code,'-',
            m_sublocators_t.locator_name) as locator,
			'Open' as source
                    FROM w_qa_submitstage_trx_t
        LEFT JOIN w_qa_submitstage_line_t on (w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id=w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id)
        LEFT JOIN w_jobcard_hdr_t on (w_jobcard_hdr_t.w_jobs_hdr_id = w_qa_submitstage_trx_t.job_no)
        LEFT JOIN m_products_t as hdr_products on (hdr_products.product_id = w_qa_submitstage_trx_t.product_id)
        LEFT JOIN m_products_t as line_products on (line_products.product_id = w_qa_submitstage_line_t.product_id)
        LEFT JOIN m_uom_codes_t as hdr_uom_codes on (hdr_uom_codes.uom_code_id = w_qa_submitstage_line_t.uom_code_id)
        LEFT JOIN m_uom_codes_t as line_uom_codes on (line_uom_codes.uom_code_id = w_qa_submitstage_trx_t.uom_code_id)
        LEFT JOIN m_subinventory_t on (m_subinventory_t.subinventory_id = w_qa_submitstage_line_t.subinventory_id)
        LEFT JOIN m_sublocators_t on (m_sublocators_t.sublocator_id = w_qa_submitstage_line_t.sublocator_id)
    WHERE 1=1 and w_qa_submitstage_line_t.return_qty!=0 AND w_qa_submitstage_line_t.subinventory_id!=0
UNION ALL 
        SELECT w_material_return_lines.batchno,
            0 as hdr_production_qty,
            w_material_return_lines.production_qty as line_production_qty,
            w_material_return_lines.return_qty,
            w_jobcard_hdr_t.job_no,
            line_products.concatenated_product  as line_product,
            hdr_products.concatenated_product  as hdr_product,
            0 as hdr_uom,
            line_uom_codes.uom_code as line_uom,
            m_subinventory_t.subinventory_name,CONCAT(
            m_sublocators_t.locator_code,'-',
            m_sublocators_t.locator_name) as locator,
			'Received' as source
        FROM w_material_return_hdr
        LEFT JOIN w_material_return_lines on (w_material_return_lines.material_return_hdr_id=w_material_return_hdr.material_return_hdr_id)
        LEFT JOIN w_jobcard_hdr_t on (w_jobcard_hdr_t.w_jobs_hdr_id = w_material_return_hdr.job_no)
        LEFT JOIN m_products_t as hdr_products on (hdr_products.product_id = w_material_return_hdr.product_id)
        LEFT JOIN m_products_t as line_products on (line_products.product_id = w_material_return_lines.product_id)
        LEFT JOIN m_uom_codes_t as line_uom_codes on (line_uom_codes.uom_code_id = w_material_return_lines.uom_code_id)
         LEFT JOIN m_subinventory_t on (m_subinventory_t.subinventory_id = w_material_return_lines.subinventory_id)
        LEFT JOIN m_sublocators_t on (m_sublocators_t.sublocator_id = w_material_return_lines.sublocator_id)
WHERE w_material_return_lines.return_qty!=0) AS v1";

         $results = \DB::select($SQL);

         return DataTables::of($results)->make(true);
}
  

    
}
