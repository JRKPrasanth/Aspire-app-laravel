<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class productionplananalyseController extends Controller
{
	/*deepika purpose:index function to redirect index blade*/
  public function index(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        
        $userAccess = json_decode($access, true);
        
        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }
        
        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');
               
        }

        // END

	      $this->data['pageMethod']=\Request::route()->getName();
	  if($this->data['pageMethod']=="productionplananalyse"){
	      $this->data['pageModule']="productionplananalyse";
	  }else{
		   $this->data['pageModule']="packingproductionplananalyse"; 
	  }
	      $this->data['urlmenu']=$this->indexs();
       return view('productionplananalyse.index',$this->data);
    }

	/* purpose:index function to redirect mrptable for mrpplan*/
	public function indexmrp()
    {
	      $this->data['pageMethod']=\Request::route()->getName();
       return view('productionplan.mrptable',$this->data);
    }
	/*end*/
/*deepika purpose:function to display product details using jqgrid*/	
		public function mrpplandata()
{
$wh='';
if($_GET['_search']=='true')
{
	$table=array();
	$table=array('m_products_t');
$wh=$this->jqgridsearch('m_products_t',$_GET['filters'],$table);
}
			$loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization'); 
        $groupname=\Session::get('groupname');
        if($groupname=='Superadmin' || $groupname=='Admin'){
        $wh.='and  m_products_t.company_id='.$compy;  
        }else{
            $wh.='and  m_products_t.company_id='.$compy.' and m_products_t.location_id='.$loc;      
        }  
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
if(!$sidx) $sidx =1;
 $result = \DB::select("SELECT COUNT(m_products_t.product_id) AS count FROM m_products_t  where  1=1 and (m_products_t.product_group_id=1 or m_products_t.product_group_id=4)  $wh");
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
$SQL = "SELECT m_products_t.product_id,m_uom_codes_t.uom_code,m_products_t.concatenated_product,m_products_t.product_code FROM m_products_t left join m_uom_codes_t on m_uom_codes_t.uom_code_id=m_products_t.primary_uom_id where  1=1 and (m_products_t.product_group_id=1 or m_products_t.product_group_id=4)  $wh ORDER BY m_products_t.concatenated_product ASC LIMIT $start , $limit";
$download_SQL = "SELECT m_products_t.product_id,m_uom_codes_t.uom_code,m_products_t.concatenated_product,m_products_t.product_code FROM m_products_t left join m_uom_codes_t on m_uom_codes_t.uom_code_id=m_products_t.primary_uom_id where  1=1 and (m_products_t.product_group_id=1 or m_products_t.product_group_id=4)  $wh ORDER BY m_products_t.concatenated_product";
 $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }

			
$result = \DB::select( $SQL );
$responce->rows[]='';
$responce->rows=$result;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
echo json_encode($responce);
}
	/*end*/
	
	/* purpose:function to display workorder details using jqgrid*/
	public function getWorkorderData(){

		$wh='';

		$loc="1";
        $compy=\Session::get('companyid');
        $org=\Session::get('organization'); 
        $groupname=\Session::get('groupname');
        $grid_date=\Session::get('griddate');
        $gridenddate=\Session::get('gridenddate');
		
        if($groupname=='1' || $groupname=='Admin'){
        $wh.='and  w_workorder_lines_t.company_id='.$compy;  
        }else{ 
            $wh .=" and ( ( w_workorder_hdr_t.workorder_date < '$grid_date'  and w_workorder_lines_t.plan_status=0) or  ( w_workorder_hdr_t.workorder_date BETWEEN '$grid_date' and '$gridenddate' ) )  ";
            $wh.='and  w_workorder_lines_t.company_id='.$compy.' and w_workorder_lines_t.location_id='.$loc;      
        }  
		
		if(isset($_GET['pagemodule'])){
			if($_GET['pagemodule']=="packingproductionplananalyse"){
			$wh.=" and m_product_groups_t.group_name='FINISHED GOODS' ";
	}else{
		$wh.=" and m_product_groups_t.group_name='SEMI FINISHED GOODS' ";
	}
		}
		
	
	
$SQL = "SELECT  w_workorder_hdr_t.workorder_hdr_id,w_workorder_hdr_t.workorder_no,w_workorder_hdr_t.workorder_date,w_workorder_lines_t.due_date,w_workorder_lines_t.workorder_line_id,m_products_t.concatenated_product,m_products_t.product_code,m_uom_codes_t.uom_code,w_workorder_lines_t.qty,w_workorder_lines_t.start_date,w_workorder_lines_t.end_date,tb_users.first_name  FROM `w_workorder_hdr_t` left join w_workorder_lines_t on(w_workorder_hdr_t.workorder_hdr_id=w_workorder_lines_t.workorder_hdr_id) left join m_products_t on(w_workorder_lines_t.product_id=m_products_t.product_id) left join m_uom_codes_t on(m_uom_codes_t.uom_code_id=w_workorder_lines_t.uom_code_id) left join tb_users on(tb_users.id=w_workorder_hdr_t.created_by) LEFT JOIN m_product_groups_t ON(m_products_t.product_group_id= m_product_groups_t.product_group_id) where 1=1 and w_workorder_lines_t.plan_status=0  $wh ";
		
$result = \DB::select( $SQL );
 return DataTables::of($result)->make(true);
		
}
	

	/* purpose:function to display workorder details using jqgrid*/	
public function getWorkorderproductData()
{
			
$wh='';
if($_GET['_search']=='true')
{
$wh.=$this->jqgridsearch('w_workorder_lines_t',$_GET['filters']);
}
if(isset($_GET['workorder_hdr_id'])){
if($_GET['workorder_hdr_id']!=""){	
$wh.= "and w_workorder_lines_t.workorder_hdr_id=".$_GET['workorder_hdr_id'];
}else{
	$wh ="";
}
}
	    $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization'); 
        $groupname=\Session::get('groupname');
        if($groupname=='Superadmin' || $groupname=='Admin'){
        $wh.='and  w_workorder_lines_t.company_id='.$compy;  
        }else{
            $wh.='and  w_workorder_lines_t.company_id='.$compy.' and w_workorder_lines_t.location_id='.$loc;      
        }  
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
if(!$sidx) $sidx =1;
$result = \DB::select("SELECT COUNT(workorder_hdr_id) AS count FROM w_workorder_lines_t where 1=1 $wh");
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

$SQL = "SELECT w_workorder_lines_t.workorder_line_id,w_workorder_lines_t.workorder_hdr_id,w_workorder_lines_t.qty,m_products_t.concatenated_product,m_products_t.product_code,m_uom_codes_t.uom_code FROM `w_workorder_lines_t` left join m_products_t on(w_workorder_lines_t.product_id=m_products_t.product_id) left join m_uom_codes_t on(m_uom_codes_t.uom_code_id=w_workorder_lines_t.uom_code_id) where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
$result = \DB::select( $SQL );
$responce->rows[]='';
$responce->rows=$result;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
echo json_encode($responce);
}
/*end*/	
}
