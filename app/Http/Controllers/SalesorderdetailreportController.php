<?php

namespace App\Http\Controllers;

use App\Productbasedcostreport;
use Illuminate\Http\Request;

class SalesorderdetailreportController extends Controller
{
     public $module="Productbasedcostreport";
	
    public function index()
    {
       return view('salesorderdetailreport.salesorderdetailreporttable');    
    }
          public function getproductbasedcost(){

                $wh='';
           
               if(isset($_GET['pq_filter']))
		{
		$data=json_decode($_GET['pq_filter']);
		$data=$data->data;
	     $wh.=$this->pqgridsearchsum('v1',$data);
		}
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
      $sidx='';
        if (!$sidx)
            $sidx = 1;
        
         $SQL = "select * from (SELECT
                p_po_invoice_lines_t.po_invoice_lines_id,
                p_po_invoice_lines_t.product_id,
                sum(p_po_invoice_lines_t.qty*p_po_invoice_lines_t.unit_price) as price,
                m_products_t.concatenated_product
                FROM
                p_po_invoice_lines_t
                LEFT JOIN m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
                WHERE
                1 = 1 AND p_po_invoice_lines_t.company_id = 1 AND p_po_invoice_lines_t.location_id = 1
                group by product_id ) v1 where 1=1 $wh";
	   
       
        
              $result = \DB::select($SQL);
		$count = count($result);
		if( $count > 0 && $limit > 0)
		{
		$total_pages = ceil($count/$limit);
		} else {
		$total_pages = 0;
		}
		if ($page > $total_pages)
		$page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
      
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $SQL = "select * from (SELECT
                p_po_invoice_lines_t.po_invoice_lines_id,
                p_po_invoice_lines_t.product_id,
                sum(p_po_invoice_lines_t.qty*p_po_invoice_lines_t.unit_price) as price,
                m_products_t.concatenated_product
                FROM
                p_po_invoice_lines_t
                LEFT JOIN m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
                WHERE
                1 = 1 AND p_po_invoice_lines_t.company_id = 1 AND p_po_invoice_lines_t.location_id = 1
                group by product_id ) v1 where 1=1 $wh
                ORDER BY price
                DESC LIMIT $start , $limit";
	   
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->data=$result;
		$responce->curPage = $page;
		$responce->total = $total_pages;
		$responce->totalRecords = $count;
		echo json_encode($responce);
	}
}
