<?php

namespace App\Http\Controllers;

use App\Materialrequirement;
use App\Materialrequirmentlines;
use Illuminate\Http\Request;
use DB;

class MaterialrequirementController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model=new Materialrequirement;
        $this->submodel=new Materialrequirmentlines;
        $this->table = "qc_materialreq_hdr_t";
        $this->subtable = "qc_materialreq_lines_t";
        $this->pageModule = "filling";
        $this->data['urlmenu']=$this->indexs(); 
    }

    /*Jqgrid Function*/
     public function getmaterialreqdata()   
    {
        
        $wh='';
        if($_GET['_search']=='true')
        {
             $search_tables=array("i_qoh_detail_t","tb_users");
            $wh=$this->jqgridsearch('qc_materialreq_hdr_t',$_GET['filters'],$search_tables);
            
        }

        $comp=\Session::get('companyid');
        $loc=\Session::get('location');
        $groupname=\Session::get('groupname');
        if($groupname=="Superadmin" || $groupname=="Admin")
        {
            $wh.="and qc_materialreq_hdr_t.company_id=$comp";
        }
        else{
            $wh.="and qc_materialreq_hdr_t.company_id=$comp  and qc_materialreq_hdr_t.location_id=$loc";
        }
        

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;

        $result = \DB::select("SELECT COUNT(qc_materialreq_hdr_t.qc_material_req_id) AS count FROM qc_materialreq_hdr_t left join i_qoh_detail_t on(i_qoh_detail_t.qoh_detail_id =qc_materialreq_hdr_t.batch_no ) left join `tb_users` on (tb_users.id=qc_materialreq_hdr_t.created_by) where 1=1 $wh");
		 
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
            
        $sql="SELECT   tb_users.username,qc_materialreq_hdr_t.active,qc_materialreq_hdr_t.qc_material_req_id,i_qoh_detail_t.batch_number, qc_materialreq_hdr_t.start_date, qc_materialreq_hdr_t.end_date,qc_materialreq_hdr_t.remarks FROM qc_materialreq_hdr_t left join i_qoh_detail_t on(i_qoh_detail_t.qoh_detail_id =qc_materialreq_hdr_t.batch_no ) left join `tb_users` on (tb_users.id=qc_materialreq_hdr_t.created_by)  where 1=1 $wh ORDER BY qc_material_req_id desc";
                 
		 
		$download_SQL = "SELECT   tb_users.username,qc_materialreq_hdr_t.active,qc_materialreq_hdr_t.qc_material_req_id,i_qoh_detail_t.batch_number, qc_materialreq_hdr_t.start_date, qc_materialreq_hdr_t.end_date,qc_materialreq_hdr_t.remarks FROM qc_materialreq_hdr_t left join i_qoh_detail_t on(i_qoh_detail_t.qoh_detail_id =qc_materialreq_hdr_t.batch_no ) left join `tb_users` on (tb_users.id=qc_materialreq_hdr_t.created_by)  where 1=1 $wh ORDER BY qc_material_req_id";
			
			
			$result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }

        $result = \DB::select($sql);
           // dd($result); 
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
        
        
    }
    /*End*/
/*Loading Index Function Data*/
    public function index()
    {
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['batchopt']=$this->jqgridselect('w_productionplan_hdr_t','productionplan_hdr_id','batch_no');

        return view("materialrequirement.table",$this->data);
    }
/*End*/
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /*Create Function*/
    public function create($id=null)
    {

        $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
        $this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
        $this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
        $this->data['prdgrpopt']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');

        if( $id != 0 ){
            $this->data['linedata']=array();
            $table = \DB::table('qc_materialreq_hdr_t')->where('qc_material_req_id',$id)->get();
            $this->data['row'] = $table[0]; 
            $this->data['row']->batch_no = $this->jCombocomp('w_productionplan_hdr_t','productionplan_hdr_id','batch_no',$table[0]->batch_no);
            $this->data['row']->employee_id = $this->jCombo('hr_employee_t','employee_id','first_name',$table[0]->employee_id);
            $tablelines = \DB::table('qc_materialreq_lines_t')->where('qc_material_req_id',$id)->get();            
            $this->data['linedata'] = $tablelines;            
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->product_id = $this->jCombo('m_products_t','product_id','product_code|concatenated_product',$value->product_id);
                $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
            }
        }else{
            $this->data['linedata']=array();
            $this->data['row']=(object) array();
            $this->data['row']->qc_material_req_id='';
            $this->data['row']->batch_no= $this->jCombocomp('w_productionplan_hdr_t','productionplan_hdr_id','batch_no','');
            $this->data['row']->employee_id= $this->jCombo('hr_employee_t','employee_id','first_name','');
            $this->data['row']->start_date=date('d-m-Y');
            $this->data['row']->end_date='';
            $this->data['row']->remarks='';
            $this->data['row']->active='';

            $this->data['product_id'] = $this->jCombo('m_products_t','product_id','product_code|concatenated_product','');
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');            
       
        }

        return view("materialrequirement.form",$this->data);
    }
/*End*/
/*Edit Function*/
    public function getedit($edit_id)
    {
        $sql = \DB::table('qc_materialreq_hdr_t')->where('qc_material_req_id',$edit_id)->select('save_status')->get();
        if($sql[0]->save_status == "SAVE"){
            $j=1;
        }else if($sql[0]->save_status == "DRAFT"){
            $j=0;
        }
        
        return $j;
    }

/*End */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*UOM Value*/
    public function uomvalue($id=null){
        $sql=\DB::table('m_products_t')->where('product_id',$id)->select('*')->get();
//        $qoh = \DB::select("SELECT ( SELECT SUM(qoh_trx_qty) FROM i_qoh_detail_t WHERE product_id = '$id') AS qoh,
//                ( SELECT SUM(reserv_trx_qty) FROM i_reservation_detail_t WHERE product_id = '$id') AS res,
//                (select (qoh ) - (res)  ) as available_qty");
        
        $qoh=\DB::select("select sum(f.qty-f.qtyy) as qty,"
                . "f.product_id from (select sum(qoh_trx_qty)as qty,"
                . "0 as qtyy,product_id FROM i_qoh_detail_t "
                . "where product_id='$id' "
                . "GROUP by product_id  "
                . " UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='$id' GROUP by product_id)f");
        $result=[];
        if($qoh[0]->qty != ''){
            $result['available_qty']=$qoh[0]->qty;
        }else{
            $result['available_qty']=0;
        }
        if(count($sql) > 0){
            $grp = $sql[0]->product_group_id;
            if($grp == 1){
                $result['uom'] = $sql[0]->trx_uom_id;
            }else{
                $result['uom'] = $sql[0]->primary_uom_id;
            }
        }else{
            $result['uom']='';
        }
        return $result;
    }
/*End*/

/*Save Function*/
    public function save(Request $request)
    {
        $id='';
    
        $data = $this->validatePost($request->all(),$this->table,'header');        
        $lines_data = $this->validatePost($request->all(),$this->subtable,'lines'); 
        \DB::beginTransaction();
        try
        {
            
             $id=$this->model->insertRow($data); 
             $lid=$this->submodel->subgridSave($lines_data,$id);
             $edit_id= DB::getPdo()->lastInsertId();
            $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"materialrequirement",$action,$_POST,"qc_materialreq_hdr_t"); 
             \DB::commit();
             
             return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'lid' => $lid));
        }
        catch (\Illuminate\Database\QueryException $e)
        {
             $message = explode('(', $e->getMessage());
             $dbCode = rtrim($message[0], ']');
             $dbCode = trim($dbCode, '[');

             \DB::rollback();
             return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
/*End*/
    /**
     * Display the specified resource.
     *
     * @param  \App\Materialrequirement  $materialrequirement
     * @return \Illuminate\Http\Response
     */
    /*View Function*/
    public function show($id=null)
    {
        $table = \DB::table('qc_materialreq_hdr_t')->select('qc_materialreq_hdr_t.*','tb_users.username')
                ->leftjoin('tb_users', 'tb_users.id', '=', 'qc_materialreq_hdr_t.created_by')
                ->where('qc_material_req_id',$id)->get();           
        $tablelines = \DB::table('qc_materialreq_lines_t')
                    ->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'qc_materialreq_lines_t.uom_code_id')
                    ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'qc_materialreq_lines_t.product_id')
                    ->where('qc_material_req_id',$id)
                    ->select('qc_materialreq_lines_t.*','m_products_t.concatenated_product','m_uom_codes_t.uom_code')
                    ->get();

        if(count($table) > 0){
            $this->data['batch_no'] =$this->idname('batch_no','w_productionplan_hdr_t','productionplan_hdr_id',$table[0]->batch_no);
            $this->data['emloyee_name'] =$this->idname('first_name','hr_employee_t','employee_id',$table[0]->employee_id);
            $this->data['start_date'] = date('d-m-Y', strtotime($table[0]->start_date));
            $this->data['end_date'] =  date('d-m-Y', strtotime($table[0]->end_date));
            $this->data['remarks'] = $table[0]->remarks;       
            $this->data['active'] = $table[0]->active;     
            $this->data['created_by'] = $table[0]->username;     
            
        }
        $this->data['tablelines'] = $tablelines;

        return view("materialrequirement.view",$this->data);
    }
/*End*/
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Materialrequirement  $materialrequirement
     * @return \Illuminate\Http\Response
     */
  /*Delete Function*/
    public function destroy(Materialrequirement $materialrequirement,$id=null)
    {
        $count=0;
       
            Materialrequirement::destroy($id);
            $query = \DB::table('qc_materialreq_lines_t')->where('qc_material_req_id',$id)->delete();
            /**Auditlog**/
             $this->auditlog($id,"materialrequirement","delete","","qc_materialreq_hdr_t");

            if($query)
            {
                return 0;
            }
            else
            {
                return 1;
            }
      
    }
    /*End*/
    
   
    
}
