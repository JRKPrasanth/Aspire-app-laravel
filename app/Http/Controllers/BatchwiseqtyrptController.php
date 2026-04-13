<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BatchwiseqtyrptController extends Controller
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
      return view('batchwiseqtyrpt.batchwiseqty',$this->data);    
    }
	
	public function getbatchwiseqty(Request $request){

            $wh='';
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : NULL;
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : NULL;

            
            $groupname=\Session::get('groupname');
		
         if($groupname == "7"){
             $wh="  and (m_product_groups_t.group_name !='FINISHED GOODS' and m_product_groups_t.group_name !='SEMI FINISHED GOODS' and m_product_groups_t.group_name !='SERVICES' and m_product_groups_t.group_name !='ASSET')";
         }

		
              $SQL = "SELECT
    i_qoh_detail_t.qoh_trx_qty,
    CAST(
        i_qoh_detail_t.created_at AS DATE
    ) AS created_at,
    i_qoh_detail_t.batch_number,
    i_qoh_detail_t.qoh_source,
    m_products_t.concatenated_product,
    m_uom_codes_t.uom_code,
    m_product_subcategory_t.subcategory_name,
    m_product_category_t.category_name,
    m_product_groups_t.group_name,
    m_subinventory_t.subinventory_name,
    s_dispatch_hdr_t.dispatch_number,
    if(i_qoh_detail_t.job_id!=0,(select concat(batch_no,'-',w_jobcard_hdr_t.job_no) from w_jobcard_hdr_t where w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id),'')as job_no,
    if(i_qoh_detail_t.job_id!=0,(select m_products_t.concatenated_product from m_products_t join w_jobcard_hdr_t on w_jobcard_hdr_t.product_id=m_products_t.product_id where w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id),'')as parent_pro,
    m_sublocators_t.locator_code
FROM
    i_qoh_detail_t
 JOIN m_products_t ON m_products_t.product_id = i_qoh_detail_t.product_id
 JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = m_products_t.primary_uom_id
 JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id
 JOIN m_product_subcategory_t ON m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
 JOIN m_product_category_t ON m_product_category_t.product_category_id = m_products_t.product_category_id
 JOIN m_subinventory_t ON m_subinventory_t.subinventory_id = i_qoh_detail_t.subinventory_id
 JOIN m_sublocators_t ON m_sublocators_t.sublocator_id = i_qoh_detail_t.locator_id
 LEFt JOIN s_dispatch_hdr_t ON s_dispatch_hdr_t.so_dispatch_hdr_id = i_qoh_detail_t.so_dispatch_hdr_id
WHERE
    1 = 1 $wh and i_qoh_detail_t.company_id='1' and DATE(i_qoh_detail_t.created_at) >= ? AND DATE(i_qoh_detail_t.created_at) <= ?";


	$results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
		
		
	}



}
