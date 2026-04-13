<?php

namespace App\Http\Controllers;

use App\Dcdetailsrpt;
use Illuminate\Http\Request;

class DcdetailsrptController extends Controller
{
   public function index()
    {
        $this->data['customeropt']=$this->jqgridselect('m_customers_t','customer_id','customer_name'); 
        $this->data['pageMethod']='deliverychallandetailsrpt'; 
       return view('deliverychallandetailsrpt.table',$this->data);
    }
    
       public function getdcdetailsData(){
      
                $wh='';
                $search_table=array();

                if($_GET['_search']=='true')
                {
                $wh=$this->jqgridsearch("s_dispatch_hdr_t",$_GET['filters']);
                }
                $page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
              $result = \DB::select("SELECT COUNT(so_dispatch_hdr_id) AS count FROM s_dispatch_hdr_t where 1=1 $wh");
		$count = $result[0]->count;
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

      $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        
               $SQL = "SELECT 
                s_dispatch_hdr_t.so_dispatch_hdr_id,
                s_dispatch_hdr_t.dispatch_number,
                s_dispatch_hdr_t.dispatch_date,
                s_dispatch_hdr_t.dispatch_status,
                m_customers_t.customer_name as ship_to_customer_id
                FROM s_dispatch_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_dispatch_hdr_t.ship_to_customer_id) 
                where 1=1 $wh and s_dispatch_hdr_t.company_id=$compy and s_dispatch_hdr_t.location_id=$loc ORDER BY $sidx $sord LIMIT $start , $limit";
               
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
}
