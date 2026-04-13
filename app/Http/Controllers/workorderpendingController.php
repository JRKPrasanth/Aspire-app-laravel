<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class workorderpendingController extends Controller
{
    
           public function __construct()
	{
		
	
            $this->data['pageMethod']=\Request::route()->getName();
             $this->data['pageFormtype']='ajax';
	}
    
	   public function index()
    {
      $this->data['pageMethod']=\Request::route()->getName();
         return view('workorderpending.index',$this->data);
        
    }
       
 
		public function getProductionplanData()
{
$wh='';
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');
		$groupname=\Session::get('groupname');
			$comloc="";
	    if($groupname=='Superadmin' || $groupname=='Admin'){
		$comloc.='and w_productionplan_hdr_t.company_id='.$compy;	
		}else{
			$comloc.='and w_productionplan_hdr_t.company_id='.$compy.' and w_productionplan_hdr_t.location_id='.$loc;		
		}			
			
			
if($_GET['_search']=='true')
{
	$table=array();
	$table[]='w_productionplan_lines_t';
$wh=$this->jqgridsearch('w_productionplan_hdr_t',$_GET['filters'],$table);
}
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
if(!$sidx) $sidx =1;
 $result = \DB::select("SELECT COUNT(w_productionplan_hdr_t.productionplan_hdr_id) AS count FROM w_productionplan_hdr_t left join w_productionplan_lines_t on(w_productionplan_hdr_t.productionplan_hdr_id = w_productionplan_lines_t.productionplan_hdr_id) where  1=1 $wh");
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
	
	    
	
$SQL="SELECT
    w_productionplan_lines_t.productionplan_hdr_id,w_productionplan_lines_t.job_card_status as job_card_status,m_products_t.product_code as product_id,w_productionplan_lines_t.product_id as prdid,w_productionplan_lines_t.qty as production_qty,w_productionplan_hdr_t.plan_no,w_productionplan_hdr_t.reference_id
FROM
    `w_productionplan_lines_t`
    LEFT JOIN w_productionplan_hdr_t ON w_productionplan_hdr_t.productionplan_hdr_id = w_productionplan_lines_t.productionplan_hdr_id
LEFT JOIN m_products_t ON m_products_t.product_id = w_productionplan_lines_t.product_id
WHERE
    (m_products_t.product_group_id = '4' and w_productionplan_lines_t.job_card_status!='1') $comloc
UNION ALL
SELECT
     w_productionplan_hdr_t.productionplan_hdr_id,w_productionplan_hdr_t.job_card_status as job_card_status,m_products_t.product_code as product_id,w_productionplan_hdr_t.product_id as prdid1,w_productionplan_hdr_t.production_qty as production_qty,w_productionplan_hdr_t.plan_no,w_productionplan_hdr_t.reference_id
FROM
    `w_productionplan_hdr_t`
LEFT JOIN m_products_t ON m_products_t.product_id = w_productionplan_hdr_t.product_id
WHERE
    (m_products_t.product_group_id = '4' and w_productionplan_hdr_t.job_card_status!='1') $comloc $wh  ORDER BY $sidx $sord LIMIT $start , $limit ";
	
$result = \DB::select( $SQL );
      $workorder=array();
      $pplanno=array();
      $product=array();
			$qty=0;
	error_reporting(0);	
        if(!empty($result))
        {
         $planno="";	
          foreach($result as $key1=>$value){
			 $key= $value->prdid;
			  
			  
				  $workorder[$key]['plan_no']=$workorder[$key]['plan_no'].','.$value->plan_no;
				  $workorder[$key]['productionplan_hdr_id']=$workorder[$key]['productionplan_hdr_id'].','.$value->productionplan_hdr_id;
				  $workorder[$key]['job_card_status']=$value->job_card_status;
				  $workorder[$key]['production_qty']=$workorder[$key]['production_qty']+$value->production_qty;
				  $workorder[$key]['product_id']=$value->product_id;
				  $workorder[$key]['prdid']=$value->prdid;
			   
             
            
        
        }
   foreach($workorder as $key1=>$value){

$value['plan_no']=substr($value['plan_no'], 1);

$value['productionplan_hdr_id']=substr($value['productionplan_hdr_id'], 1);

	$wo_order[]=$value;
        }


      }
$responce->rows[]='';
$responce->rows=$wo_order;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
echo json_encode($responce);
}
	
public function getBomData()
{
			
$wh='';
	 $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');
		$groupname=\Session::get('groupname');
			
	    if($groupname=='Superadmin' || $groupname=='Admin'){
		$wh.='and w_productionplan_hdr_t.company_id='.$compy;	
		}else{
			$wh.='and w_productionplan_hdr_t.company_id='.$compy.'and w_productionplan_hdr_t.location_id='.$loc;		
		}	
if($_GET['_search']=='true')
{
$wh=$this->jqgridsearch('w_productionplan_lines_t',$_GET['filters']);
}
if(isset($_GET['production_qty'])){
if($_GET['production_qty']!="" && $_GET['product_id']!=""){	
$wh =" and m_material_bom_hdr_t.assembly_product_id=".$_GET['product_id'];
}else{
	$wh ="";
}
}
$page = $_GET['page'];
$limit = $_GET['rows'];
$sidx = $_GET['sidx'];
$sord = $_GET['sord'];
if(!$sidx) $sidx =1;

$result = \DB::select("SELECT m_material_bom_hdr_t.*,count(m_material_bom_lines_t.material_bom_hdr_id) as count FROM `m_material_bom_hdr_t` left join m_material_bom_lines_t on m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id where  1=1 $wh ");
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

    $SQL="select m_material_bom_lines_t.component_qty,m_products_t.product_code as product_id,m_material_bom_lines_t.component_product_id,m_uom_codes_t.uom_code as uom_code_id from m_material_bom_lines_t left join m_material_bom_hdr_t on(m_material_bom_hdr_t.material_bom_hdr_id=m_material_bom_lines_t.material_bom_hdr_id) left join m_products_t on (m_products_t.product_id=m_material_bom_lines_t.component_product_id)
	left join m_uom_codes_t on(m_uom_codes_t.uom_code_id=m_material_bom_lines_t.component_uom_code_id) 	where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";

    $result = \DB::select( $SQL );
foreach($result as $key=>$value){
   // dd($value);
   $qoh=\DB::Select("select sum(qoh_trx_qty) as qoh from  i_qoh_detail_t where product_id='".$value->component_product_id."'");
	if($qoh[0]->qoh!=null){
  $result[$key]->qoh=$qoh[0]->qoh; 
        }else{
              $result[$key]->qoh=0;
        }
    $result[$key]->qty=($value->component_qty)*($_GET['production_qty']); 
  
}

	
$responce->rows[]='';
$responce->rows=$result;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
echo json_encode($responce);
}
     /**  public function index()
    {
    
          $data=\DB::select("SELECT w_productionplan_hdr_t.plan_no as wo_no,w_productionplan_hdr_t.production_qty,m_material_bom_lines_t.component_product_id,m_material_bom_lines_t.component_qty,m_products_t.product_code FROM `w_productionplan_hdr_t` left join m_material_bom_hdr_t on w_productionplan_hdr_t.product_id=m_material_bom_hdr_t.assembly_product_id left join m_material_bom_lines_t on m_material_bom_hdr_t.material_bom_hdr_id=m_material_bom_lines_t.material_bom_hdr_id left join m_products_t on m_products_t.product_id=m_material_bom_lines_t.component_product_id where m_products_t.product_group_id='4'");
      
          $workorder=array();
          $qty=array();
          
          foreach($data as $key=>$value){
           
              //$workorder[$value->product_code][]=$value->wo_no;
              $workorder[$value->component_product_id][]=$value->wo_no;
              //$qty[$value->product_code][]=(($value->production_qty) * ($value->component_qty));
              $qty[$value->component_product_id][]=(($value->production_qty) * ($value->component_qty));
            
              $workorder_name[$value->component_product_id]=$this->idname('product_code','m_products_t','product_id',$value->component_product_id);
            
        }
         $this->data['qty']=$qty;
         $this->data['workorder']=$workorder;
         $this->data['workorder_name']=$workorder_name;
        
        return view('workorderpending.view',$this->data);
        
    }**/
	
}
