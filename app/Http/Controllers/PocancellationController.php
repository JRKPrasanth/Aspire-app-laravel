<?php

namespace App\Http\Controllers;

use App\Pocancellation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PocancellationController extends Controller
{
   public $module="pocancellation";
	public function __construct()
	{
		$this->data=array(
                    'pageModule'=> 'pocancellation',
                    'pageUrl'	=>  url('pocancellation')
                  );
                $this->data['urlmenu']=$this->indexs(); 
		$this->table="p_po_hdr_t";
		$this->subtable="p_po_lines_t";
		$this->pageModule="Pocancellation";
	
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
	}
    public function index()
    {
     return view('pocancellation.table');
    }

  public function getPurchaseorderData(){

		$wh='';
		if($_GET['_search']=='true'){
		$wh=$this->jqgridsearch('p_po_hdr_t',$_GET['filters']);

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
               $SQL = "SELECT
                        p_po_hdr_t.po_hdr_id as po_hdr_id,
                        p_po_hdr_t.po_number as po_number,
                        p_po_hdr_t.po_date as po_date,
                        p_po_hdr_t.po_type as po_type,
                        p_po_hdr_t.po_grand_total as po_grand_total,
                        p_po_hdr_t.remarks as remarks,
                        p_po_hdr_t.po_status as status,
                        m_supplier_t.supplier_name as supplier_id
                        FROM `p_po_hdr_t`
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_po_hdr_t.`supplier_id`) where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        //
    }

 
    public function show(Pocancellation $pocancellation)
    {
        //
    }

    public function edit(Pocancellation $pocancellation)
    {
        //
    }

  
    public function update(Request $request, Pocancellation $pocancellation)
    {
        //
    }

  
    public function destroy(Pocancellation $pocancellation)
    {
        //
    }
}
