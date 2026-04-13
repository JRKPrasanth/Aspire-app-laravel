<?php

namespace App\Http\Controllers;
use App\Jobworkoutorder;
use App\Jobworkoutorderlines;
use App\jobwoodispatch;
use App\Materialbom;
use App\Dispatch;
use App\Dispatchlines;
use App\Materialbomlines;
use Illuminate\Http\Request;

class JobwoodispatchController extends Controller
{
    public $module="jobworkoutorder";
	
		public function __construct()
	{
		$this->data=array();
                $this->data['urlmenu']=$this->indexs(); 
		$this->model 	= new Dispatch();
		$this->submodel = new Dispatchlines();
		$this->data['pageFormtype']='ajax';
                $this->data['pageModule']='jobwoodispatch';
                $this->data['pageMethod']=\Request::route()->getName();
               $this->table="s_dispatch_hdr_t";
		$this->subtable="s_dispatch_lines_t";
		$this->middleware('auth');

	}

	/*Jqgrid Function*/
	 public function getjobworkoutorderData()   {
        $wh='';
	if($_GET['_search']=='true')
	{
            $search_tables=array('m_subcontract_supplier_t','tb_users');
            $wh=$this->jqgridsearch('w_jobworkoutorder_hdr_t',$_GET['filters'],$search_tables);
	}
        $comp=\Session::get('companyid');
        $loc=\Session::get('location');
        $groupname=\Session::get('groupname');
        if($groupname=="Superadmin" || $groupname=="Admin")
        {
            $wh.="and w_jobworkoutorder_hdr_t.company_id=$comp";
        }
        else{
            $wh.="and w_jobworkoutorder_hdr_t.company_id=$comp  and w_jobworkoutorder_hdr_t.location_id=$loc";
        }

	$page = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx = $_GET['sidx'];
	$sord = $_GET['sord'];
	if(!$sidx) $sidx =1;

	$result = \DB::select("SELECT COUNT(jobworkoutorder_hdr_id) AS count FROM w_jobworkoutorder_hdr_t "
                  . "left join m_subcontract_supplier_t on(m_subcontract_supplier_t.subcontract_supplier_id=w_jobworkoutorder_hdr_t.subcontract_supplier_id)"
                . "left join tb_users on(tb_users.id=w_jobworkoutorder_hdr_t.created_by)"
                . "where 1=1 $wh");
	$count = $result[0]->count;
	if( $count > 0 && $limit > 0)
        {
            $total_pages = ceil($count/$limit);
	}
        else
        {
            $total_pages = 0;
	}

	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit;
	if($start <0) $start = 0;
		
	$sql="SELECT w_jobworkoutorder_hdr_t.jobworkoutorder_hdr_id,w_jobworkoutorder_hdr_t.joboutorder_no,m_subcontract_supplier_t.subcontract_name,"
                . "w_jobworkoutorder_hdr_t.return_date,w_jobworkoutorder_hdr_t.remarks,tb_users.username"
                . " from w_jobworkoutorder_hdr_t "
                . "left join m_subcontract_supplier_t on(m_subcontract_supplier_t.subcontract_supplier_id=w_jobworkoutorder_hdr_t.subcontract_supplier_id)"
                . "left join tb_users on(tb_users.id=w_jobworkoutorder_hdr_t.created_by)"
                . "where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		 
		 $download_SQL = "SELECT w_jobworkoutorder_hdr_t.jobworkoutorder_hdr_id,w_jobworkoutorder_hdr_t.joboutorder_no,m_subcontract_supplier_t.subcontract_name,"
                . "w_jobworkoutorder_hdr_t.return_date,w_jobworkoutorder_hdr_t.remarks,tb_users.username"
                . " from w_jobworkoutorder_hdr_t "
                . "left join m_subcontract_supplier_t on(m_subcontract_supplier_t.subcontract_supplier_id=w_jobworkoutorder_hdr_t.subcontract_supplier_id)"
                . "left join tb_users on(tb_users.id=w_jobworkoutorder_hdr_t.created_by)"
                . "where 1=1 $wh ORDER BY $sidx $sord";
		 
		 $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }

	
		$result = \DB::select($sql);
		
	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

	echo json_encode($responce);
		
		
    }
    /*End*/
    /*Main Page Load Function*/
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

		return view('jobwoodispatch.table',$this->data);
	}
      /*End*/  

      /*Create Function*/
     public function create($id=null)
    {
//         dd($id);
		$this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','dispatch')->get();
		$company_id = \Session::get('companyid');

                    $jobwoo=Jobworkoutorder::find($id);
                   
                    $jobwoohdrid=$jobwoo->jobworkoutorder_hdr_id;
                    $joboutorder_no=$jobwoo->joboutorder_no;
                    $subcontractor=$jobwoo->subcontract_supplier_id;
                        $this->data['so_dispatch_hdr_id']="";
			$this->data['dispatch_number']="";
			$this->data['prepare_date']=date(\Session::get('p_date_format'), strtotime(date('Y-m-d')));
			$this->data['dispatch_date']=date(\Session::get('p_date_format'), strtotime(date('Y-m-d')));
			$this->data['dispatch_source']='JOBWORKOUTORDER';
			$this->data['dispatch_status']='OPEN';
			$this->data['soconvert_status']='';
			$this->data['location_id']=$this->jcombo("m_location_t","location_id","location_name", \Session::get('location')); 
			$this->data['organization_id']=$this->jcombo('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
			$this->data['preparer_id']=$this->jcombo('tb_users','id','username',\Session::get('id'));
			$this->data['freight_carrier_id'] = $this->jcombo('m_frieghtcarriers_hdr_t','ar_frieghtcarriers_hdr_id','carrier_name','');
			$this->data['subcontractor']=$this->jcombo('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_name',$subcontractor);
                        $this->data['remarks']="";
			$this->data['pack_weight']="";
			$this->data['packaging_qty']="";
			$this->data['pricelist_id']=$this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name','',' and price_list_type="Sales"');
			$this->data['deliver_to_location']='';
			$this->data['deliver_to_location_txt']='';
                        $this->data['reference_source_id']='';
                        $this->data['ar_sales_hdr_id']='';
			$this->data['part_no']=$this->jcustomselecttool('m_manufacturer_partno_t','manufacturer_partno_id','part_no','','');
                        $this->data['reference_no']=$joboutorder_no;
		    
//		    $this->data['linedata'] = array();
//			
//			$this->data['product_id'] =$this->jCombo('m_products_t','product_id','concatenated_product','');	
//			$this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
			$this->data['pageurl']=\Request::route()->getName();
                        $jobproductid=\DB::select("SELECT
                                                jobwolines.product_id
                                                FROM
                                                w_jobworkoutorder_lines_t jobwolines
                                                LEFT JOIN w_jobworkoutorder_hdr_t jobwohdr on (jobwohdr.jobworkoutorder_hdr_id=jobwolines.jobworkoutorder_hdr_id)
                                                where jobwohdr.jobworkoutorder_hdr_id='$jobwoohdrid'");
                        $prd_id=$jobproductid[0]->product_id;
                        
                       $tablelines=\DB::select("SELECT
    bomhdr.assembly_product_id as assembly_product_id,
    bomlines.component_product_id as component_product_id,
    bomlines.component_uom_code_id as component_uom_code_id,
    bomlines.component_qty as component_qty
FROM
    m_material_bom_lines_t bomlines
    LEFT JOIN m_material_bom_hdr_t bomhdr on (bomhdr.material_bom_hdr_id=bomlines.material_bom_hdr_id)
    where bomhdr.assembly_product_id='$prd_id'");
                      // dd($tablelines);
		    $this->data['linedata'] = $tablelines;
			// dd($this->data['linedata']);
			foreach($this->data['linedata'] as $key=>$value)
			{
				$this->data['linedata'][$key]->line_no =$key+1;
				$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->component_uom_code_id);
                                $this->data['linedata'][$key]->part_no = "";
				$this->data['linedata'][$key]->so_dispatch_hdr_id ="";
				$this->data['linedata'][$key]->so_dispatch_line_id ="";
				$this->data['linedata'][$key]->so_pickrelease_hdr_id ="";
				$this->data['linedata'][$key]->so_pickrelease_line_id ="";	
				$this->data['linedata'][$key]->reference_hdr_id ="";
				$this->data['linedata'][$key]->reference_line_id ="";
				$this->data['linedata'][$key]->ar_sales_hdr_id ="";
				$this->data['linedata'][$key]->ar_sales_line_id	="";
				$this->data['linedata'][$key]->source_code ='JOB WORK OUT ORDER';
				$this->data['linedata'][$key]->bom_qty=$value->component_qty;
				$this->data['linedata'][$key]->dispatch_qty=$value->component_qty;
				$this->data['linedata'][$key]->dispatched_qty="";
				$this->data['linedata'][$key]->comments ="";	
                                $company_id=\Session::get('companyid');
                                $qoh_qty = \DB::select("select sum(f.qty-f.qtyy) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='".$value->component_product_id."' and i_qoh_detail_t.company_id='".$company_id."' GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='".$value->component_product_id."' and i_reservation_detail_t.company_id='".$company_id."' GROUP by product_id)f");
				if(!empty($qoh_qty)){
					$result[$key]=(object) array();
					if($qoh_qty[0]->qoh_qty == NULL)
						$this->data['linedata'][$key]->qoh_qty=0;
					else
						$this->data['linedata'][$key]->qoh_qty=$qoh_qty[0]->qoh_qty;
				}else{
				  	$this->data['linedata'][$key]->qoh_qty="0";
				}
//                                $this->data['linedata'][$key]->qoh_qty ="";	
				$this->data['linedata'][$key]->product_id = $this->data['product_id']=$this->jCombo('m_products_t','product_id','concatenated_product',$value->component_product_id);	
			}
// dd($this->data['linedata']);
		$this->data['product_id']=$this->jCombo('m_products_t','product_id','concatenated_product',"");
		$this->data['uomcode_id']=$this->jCombo('m_uom_codes_t','uom_code_id','uom_code',"");	
		$this->data['custypeopt']=$this->jqgridselect('m_customer_types_t','customer_type_id','customer_type');
		$this->data['country']=$this->jqgridselect('m_countries_t','country_id','country_name');
		$this->data['state']=$this->jqgridselect('m_states_t','state_id','state_name');
		$this->data['city']=$this->jqgridselect('m_cities_t','city_id','city_name');
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
//		dd($this->data);
		return view ("jobwoodispatch.form",$this->data);
    }
    /*End*/

    /*Save Function*/
        public function save(Request $request){
//            dd($_POST);
        $id='';
        $data = $this->validatePost($request->all(),$this->table,'header');	
		$data['dispatch_date']=date('Y-m-d',strtotime($_POST['dispatch_date']));
		$data['prepare_date']=date('Y-m-d',strtotime($_POST['prepare_date']));
		$lines_data = $this->validatePost($request->all(),$this->subtable,'lines');	
//		  dd($data);
		
        if ($_POST['dispatch_number'] =="")
        {
          	$seqno=$this->Seqnoe('SOD','s_dispatch_hdr_t','','dispatch_count');
            $data['dispatch_number'] =$seqno[0];
            $data['dispatch_count'] =$seqno[1];
        }
        else
        {
            $seqno = $_POST['dispatch_number'];
        }
//dd($lines_data);
		\DB::beginTransaction();
		try
		{
			$id=$this->model->insertRow($data);
			$action="Create";
          /**Auditlog**/
            $this->auditlog($id,"jobwoodispatch",$action,$_POST,"s_dispatch_hdr_t");
			$lid=$this->submodel->subgridSave($lines_data,$id);
                	\DB::commit();
			
			return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'lid' => $lid));
		}
		catch (\Illuminate\Database\QueryException $e)
		{
			$message = explode('(', $e->getMessage());
			$dbCode = rtrim($message[0], ']');
			$dbCode = trim($dbCode, '[');
			dd($dbCode);
			\DB::rollback();
			return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
		}
		
    }
    /*End*/
}
