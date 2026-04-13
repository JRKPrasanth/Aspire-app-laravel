<?php

namespace App\Http\Controllers;

use App\Pobyvendorrpt;
use Illuminate\Http\Request;

class PobyvendorrptController extends Controller
{
     public $module="pobyvendorrpt";
	public function __construct()
	{
		$this->data=array();
                 $this->data=array();
		$this->pageModule="pobyvendorrpt";
                $this->model=new Pobyvendorrpt();
		$this->model=new Pobyvendorrpt;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
                $this->data['urlmenu']=$this->indexs(); 
	}
     public function index()
    {
     $this->data['supplieropt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name'); 
        $this->data['pageMethod']='pobyvendorrpt'; 
		 
		  $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        
               $SQL = "SELECT 
                p_po_hdr_t.po_hdr_id,
                COUNT(p_po_hdr_t.po_number)as po_count,
                m_supplier_t.supplier_name as supplier_name,
				m_supplier_t.supplier_id,
                SUM(p_po_hdr_t.po_grand_total)as po_grand_total
                FROM p_po_hdr_t 
                left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id) 
                where 1=1 and p_po_hdr_t.company_id=$compy and p_po_hdr_t.location_id=$loc GROUP BY p_po_hdr_t.supplier_id ORDER BY p_po_hdr_t.po_hdr_id desc";
               
		$result = \DB::select( $SQL );
		$this->data['result']=$result;
       return view('pobyvendorrpt.table',$this->data);
    }
     public function getpobyvendorData(){
      
                $wh='';
                $search_table=array();

                if($_GET['_search']=='true')
                {
                $wh=$this->jqgridsearch("p_po_hdr_t",$_GET['filters']);
                }
                $page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
              $result = \DB::select("SELECT COUNT(po_hdr_id) AS count FROM p_po_hdr_t where 1=1 $wh");
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
                p_po_hdr_t.po_hdr_id,
                COUNT(p_po_hdr_t.po_number)as po_count,
                m_supplier_t.supplier_name as supplier_id,
                SUM(p_po_hdr_t.po_grand_total)as po_grand_total
                FROM p_po_hdr_t 
                left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id) 
                where 1=1 $wh and p_po_hdr_t.company_id=$compy and p_po_hdr_t.location_id=$loc GROUP BY p_po_hdr_t.supplier_id ORDER BY $sidx $sord LIMIT $start , $limit";
               
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
	  public function index1()
    {
   
        $this->data['pageMethod']='sobyvendorrpt'; 
		 
		  $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        
               $SQL = "SELECT 
                s_salesorder_hdr_t.sales_hdr_id,
                COUNT(s_salesorder_hdr_t.sales_order_no)as po_count,
                m_customers_t.customer_name as customer_name,
				m_customers_t.customer_id,
                SUM(s_salesorder_hdr_t.order_total)as order_total
                FROM s_salesorder_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) 
                where 1=1 and s_salesorder_hdr_t.company_id=$compy and s_salesorder_hdr_t.location_id=$loc GROUP BY s_salesorder_hdr_t.ship_to_customer_id ORDER BY s_salesorder_hdr_t.sales_hdr_id desc";
               
		$result = \DB::select( $SQL );
		$this->data['result']=$result;
       return view('pobyvendorrpt.so_table',$this->data);
    }
}
