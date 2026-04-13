<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use DB;

class MachinerptController extends Controller
	
{ public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }
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

        $this->data['machine_id']=$this->jcombocomp('w_machine_hdr_t','machine_hdr_id','machine_code|machine_name','');
        // dd($this->data);
 return view('machinerpt.machinerpt', $this->data);       
    }
 
 public function index1(Request $request)
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

        $this->data['machine_id']=$this->jcombocomp('w_machine_hdr_t','machine_hdr_id','machine_code|machine_name','');
 return view('machinerpt.machinecapacityrpt', $this->data);       
    }
   

 public function machinedetails(Request $request) {
	


    $machine =  $request->machine_id ?? null;

    $SQL = "SELECT * from ( SELECT
    w_machine_hdr_t.machine_hdr_id,
    w_machine_hdr_t.assigned_to,
    w_machine_hdr_t.machine_code,
    w_machine_hdr_t.machine_name,
    w_machine_hdr_t.electricity_cost,
    w_machine_hdr_t.remarks,
    w_machine_lines_t.product_type_id,
    w_machine_lines_t.machine_capacity,
    tb_users.username,
    m_product_type_t.product_type
FROM
    w_machine_hdr_t
LEFT JOIN w_machine_lines_t ON
    (
        w_machine_lines_t.machine_hdr_id = w_machine_hdr_t.machine_hdr_id
    )
LEFT JOIN tb_users ON
    (
        tb_users.id = w_machine_hdr_t.created_by
    )
LEFT JOIN m_product_type_t ON
    (
        m_product_type_t.product_type_id = w_machine_lines_t.product_type_id
    )
WHERE
    1 = 1 AND w_machine_lines_t.machine_hdr_id = ?

    ) AS v1";

    $results = \DB::select($SQL, [$machine]);

    return response()->json(['data' => $results]);
}
 
 
  public function machinecapacitydetails(Request $request)
	{
		

		$machine = $request->machine_id;


    $SQL = "SELECT * from (
SELECT
    w_machine_equipments_hdr_t.machine_equipments_hdr_id,
        w_machine_hdr_t.machine_code,
        w_machine_hdr_t.machine_name,
    w_machine_equipments_hdr_t.remarks,
    CONCAT(
        m_products_t.concatenated_product,
        m_products_t.product_code
    ) AS product,
    w_machine_equipments_lines_t.range_from,
    w_machine_equipments_lines_t.comments,
    w_machine_equipments_lines_t.range_to,
    w_machine_equipments_lines_t.hours,
    tb_users.username,
    m_products_t.concatenated_product
FROM
    w_machine_equipments_hdr_t
LEFT JOIN w_machine_equipments_lines_t ON
    (
        w_machine_equipments_lines_t.machine_equipments_hdr_id = w_machine_equipments_hdr_t.machine_equipments_hdr_id
    )
LEFT JOIN w_machine_hdr_t ON
    (
        w_machine_hdr_t.machine_hdr_id = w_machine_equipments_hdr_t.machine_id
    )
LEFT JOIN tb_users ON
    (
        tb_users.id = w_machine_equipments_hdr_t.created_by
    )
LEFT JOIN m_products_t ON
    (
        m_products_t.product_id = w_machine_equipments_lines_t.product_id
    )
WHERE
    1 = 1 AND w_machine_equipments_hdr_t.machine_id = ?) AS v1";

     $results = \DB::select($SQL, [$machine]);

   return DataTables::of($results)->make(true);
}
 
  public function machineindex()
    {

           return view('machinereport.report',$this->data);


    }
 
 
 
 public function machinewiserptsearch(){
  $wh='';
	 $wh1='';
       
               if(isset($_GET['pq_filter']))
            {
    $data=json_decode($_GET['pq_filter']);
    $data=$data->data;
    $wh.=$this->pqgridsearchsum('v1',$data);   			   
    }
      $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];     
if(isset($_GET['product_id'])){
      $product_id =  $_GET['product_id'];
	  $frmdate =  $_GET['frmdate'];
	  $todate =  $_GET['todate'];
}
$sidx='';
	 
 if(!$sidx) $sidx =1;


	 $sql=\DB::select("select count(w_machine_hdr_t.machine_hdr_id) as count, w_qa_submitstage_trx_t.production_qty,w_machine_hdr_t.machine_name,m_products_t.concatenated_product,w_jobcard_hdr_t.hour from w_qa_submitstage_trx_t left join w_jobcard_hdr_t
on(w_jobcard_hdr_t.w_jobs_hdr_id=w_qa_submitstage_trx_t.job_no) left join m_products_t
on(m_products_t.product_id=w_qa_submitstage_trx_t.product_id) left join w_machine_hdr_t
on(w_machine_hdr_t.machine_hdr_id=w_jobcard_hdr_t.machine_hdr_id) where w_qa_submitstage_trx_t.product_id=$product_id and  w_qa_submitstage_trx_t.created_at between '$frmdate' and '$todate'");
	 
$count = count($sql);
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

                     
	 
$machinedetails = \DB::select("select count(w_machine_hdr_t.machine_hdr_id) as count, w_qa_submitstage_trx_t.production_qty,w_machine_hdr_t.machine_name,w_machine_hdr_t.machine_hdr_id,m_products_t.concatenated_product,w_jobcard_hdr_t.hour from w_qa_submitstage_trx_t left join w_jobcard_hdr_t
on(w_jobcard_hdr_t.w_jobs_hdr_id=w_qa_submitstage_trx_t.job_no) left join m_products_t
on(m_products_t.product_id=w_qa_submitstage_trx_t.product_id) left join w_machine_hdr_t
on(w_machine_hdr_t.machine_hdr_id=w_jobcard_hdr_t.machine_hdr_id) where   w_qa_submitstage_trx_t.product_id=$product_id and   w_qa_submitstage_trx_t.created_at between '$frmdate' and '$todate' ORDER BY $sidx DESC LIMIT $start , $limit");
	$production_qty = $machinedetails[0]->production_qty;
	 //dd($production_qty);
	 $sql =\DB::table('w_machine_equipments_lines_t')
					->where('w_machine_equipments_lines_t.product_id',$product_id)
					->select('w_machine_equipments_lines_t.*')
					->get();
	 foreach($sql as $key=>$value){
					if($value->range_from <= $production_qty && $value->range_to >= $production_qty){
				$hour = $value->hours;
						$machinedetails[0]->hour=$hour;
					}
	 }
	//dd($hour);
     $responce->rows[]='';
    $responce->data=$machinedetails;
    $responce->curPage = $page;
    $responce->total = $total_pages;
    $responce->totalRecords = $count;
    echo json_encode($responce);

}
 
 public function posupplierindex()
    {

           return view('posupplierreport.report',$this->data);


    }
 
 public function posupplierrptsearch(){
	 $wh='';
           
               if(isset($_GET['pq_filter']))
		{
		$data=json_decode($_GET['pq_filter']);
		$data=$data->data;
		$table=array('m_supplier_t','p_po_lines_t','m_products_t');
			
	     $wh.=$this->pqgridsearch('p_po_hdr_t',$data,$table);
		}
	 $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];     
if(isset($_GET['product_id'])){
      $product_id =  $_GET['product_id'];
                
}
      $sidx='';
        if (!$sidx)
            $sidx = 1;
              $result = \DB::select("SELECT COUNT(p_po_hdr_t.po_hdr_id) AS count FROM p_po_hdr_t left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id) left join p_po_lines_t on(p_po_hdr_t.po_hdr_id=p_po_lines_t.po_hdr_id) left join m_products_t on(p_po_lines_t.product_id=m_products_t.product_id) where 1=1 $wh");
		
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

        $compy=\Session::get('companyid');
        
$SQL ="SELECT p_po_hdr_t.po_hdr_id,
              p_po_hdr_t.po_number,
              p_po_hdr_t.supplier_id,
              m_supplier_t.supplier_name,
              p_po_hdr_t.po_date,
              p_po_lines_t.qty,
              p_po_lines_t.pending_qty,
              p_po_lines_t.promised_alternate_date,
              p_po_lines_t.product_id,
              m_products_t.concatenated_product
        FROM p_po_hdr_t
        left join p_po_lines_t on(p_po_lines_t.po_hdr_id=p_po_hdr_t.po_hdr_id)
        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id)
        left join m_products_t on(p_po_lines_t.product_id=m_products_t.product_id)
       where 1=1 $wh and p_po_lines_t.product_id =$product_id  ORDER BY $sidx LIMIT $start , $limit  ";
$result = \DB::select( $SQL );
	 //dd($result);
$download_SQL ="SELECT p_po_hdr_t.po_hdr_id,
              p_po_hdr_t.po_number,
              p_po_hdr_t.supplier_id,
              m_supplier_t.supplier_name,
              p_po_hdr_t.po_date,
              p_po_lines_t.qty,
              p_po_lines_t.pending_qty,
              p_po_lines_t.promised_alternate_date,
              p_po_lines_t.product_id,
              m_products_t.concatenated_product
        FROM p_po_hdr_t
        left join p_po_lines_t on(p_po_lines_t.po_hdr_id=p_po_hdr_t.po_hdr_id)
        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id)
        left join m_products_t on(p_po_lines_t.product_id=m_products_t.product_id)
       where 1=1 $wh and p_po_lines_t.product_id =$product_id  ORDER BY $sidx ";
	 //dd();
$result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
		$responce->rows[]='';
		$responce->data=$result;
		$responce->curPage = $page;
		$responce->total = $total_pages;
		$responce->totalRecords = $count;
		echo json_encode($responce);
	}
	
	public function bomhistoryindex()
    {
        $this->data['product_id']=$this->jComboprodsfg('m_products_t','product_id','product_code|concatenated_product','');
 return view('machinerpt.materialbomhistoryreport', $this->data);       
    }
 
   public function getmaterialbomhistoryreport(Request $request)
	{


		$product = $request->product_id;



    $SQL = "SELECT * from (SELECT * FROM(SELECT
    w_productionplan_lines_t.productionplan_hdr_id,
    w_productionplan_lines_t.parent_product,
    parent_products_t.concatenated_product AS parent_product_name,
    w_productionplan_lines_t.product_id,
    m_products_t.concatenated_product AS child_product_name,
    w_productionplan_lines_t.process_level,
    w_productionplan_lines_t.process_name,
    w_productionplan_lines_t.component_qty,
    DATE(
        m_material_bom_hdr_t.created_at
    ) AS bom_created_at,
    DATE(
        m_material_bom_hdr_t.updated_at
    ) AS bom_updated_at,
    DATE(
        w_productionplan_lines_t.created_at
    ) AS prod_created_at,
    DATE(
        w_productionplan_lines_t.updated_at
    ) AS prod_updated_at
FROM
    w_productionplan_lines_t
LEFT JOIN m_material_bom_hdr_t ON m_material_bom_hdr_t.assembly_product_id = w_productionplan_lines_t.parent_product
LEFT JOIN m_products_t AS parent_products_t
ON
    parent_products_t.product_id = w_productionplan_lines_t.parent_product
LEFT JOIN m_products_t ON m_products_t.product_id = w_productionplan_lines_t.product_id) v1
WHERE
    1 = 1 AND v1.parent_product = ? AND v1.prod_updated_at >= v1.bom_updated_at
GROUP BY
    v1.child_product_name 
UNION ALL
SELECT
    *
FROM
    (
    SELECT
        w_productionplan_lines_t.productionplan_hdr_id,
        w_productionplan_lines_t.parent_product,
        parent_products_t.concatenated_product AS parent_product_name,
        w_productionplan_lines_t.product_id,
        m_products_t.concatenated_product AS child_product_name,
        w_productionplan_lines_t.process_level,
        w_productionplan_lines_t.process_name,
        w_productionplan_lines_t.component_qty,
        DATE(
            m_material_bom_hdr_t.created_at
        ) AS bom_created_at,
        DATE(
            m_material_bom_hdr_t.updated_at
        ) AS bom_updated_at,
        DATE(
            w_productionplan_lines_t.created_at
        ) AS prod_created_at,
        DATE(
            w_productionplan_lines_t.updated_at
        ) AS prod_updated_at
    FROM
        w_productionplan_lines_t
    LEFT JOIN m_material_bom_hdr_t ON m_material_bom_hdr_t.assembly_product_id = w_productionplan_lines_t.parent_product
    LEFT JOIN m_products_t AS parent_products_t
    ON
        parent_products_t.product_id = w_productionplan_lines_t.parent_product
    LEFT JOIN m_products_t ON m_products_t.product_id = w_productionplan_lines_t.product_id
) v1
WHERE
    1 = 1 AND v1.parent_product = ? AND v1.prod_updated_at <= v1.bom_updated_at
GROUP BY
    v1.child_product_name 
ORDER BY
productionplan_hdr_id DESC) AS vikki";

     $results = \DB::select($SQL, [$product,$product]);

   return DataTables::of($results)->make(true);
}
	
 
}
