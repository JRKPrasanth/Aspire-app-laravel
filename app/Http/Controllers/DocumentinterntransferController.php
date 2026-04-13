<?php

namespace App\Http\Controllers;

use App\documentinterntransfer;
use App\documentinterntransferlines;
use Illuminate\Http\Request;
use DB;
class DocumentinterntransferController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model=new documentinterntransfer();
        $this->submodel=new documentinterntransferlines();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlmenu']=$this->indexs();
        $this->table='a_doctransfer_hdr_t';
        $this->subtable='a_doctransfer_lines_t';
    }
    public function index()
    {
        return view('documentinterntransfer.index',$this->data);  
    }
    
    public function getdocumentinterntransferdata()
    {
     
      $comp=\Session::get('companyid');
        $wh='';
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];

       if($_GET['_search']=='true'){
         $wh.=$this->jqgridsearchnotab("v1",$_GET['filters']);
       }
    if(!$sidx) $sidx =1;
    $result = \DB::select("select * from (SELECT
    a_doctransfer_hdr_t.*,
    CONCAT(
        hr_employee_t.employee_number,
        '-',
        hr_employee_t.first_name
    ) AS employee_number,
    rcv_by.first_name as doc_received_by,
    CONCAT(
        tb_users.employee_number,
        '-',
        tb_users.first_name
    ) AS first_name
FROM
    a_doctransfer_hdr_t
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = a_doctransfer_hdr_t.emp_id
LEFT JOIN hr_employee_t as rcv_by ON rcv_by.employee_id = a_doctransfer_hdr_t.doc_receive_by
LEFT JOIN tb_users ON tb_users.id = a_doctransfer_hdr_t.created_by
WHERE
    1 = 1
ORDER BY
    a_doctransfer_hdr_t.doc_hdr_id
DESC ) as v1 where 1=1 $wh");

    $count = COUNT($result);
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
       
        
    if(isset($_GET['download']))
    {
        $result1=collect($result)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }
     
    $result = array_slice($result,$start,$limit);
    //dd($result);
    $responce->rows[]='';
    $responce->rows=$result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;

    echo json_encode($responce);
    }
    
    public function create($id=null)
    { 
        
        /*if($id!=0){
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('w_jobactivity_t')->where('job_activity_id',$id)->get();
            $this->data['row'] = $table[0];
        $this->data['employee_id']=$this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name',$table[0]->emp_id,'and group_type=10 and active="Yes"');
        $this->data['activity_name']=$this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code',$table[0]->activity_name,'and lookup_type="OPERATION_MANUAL_ACTIVITIES"');
         }else{
            
             $this->data['row'] = (object)array();
            $this->data['row']->job_activity_id='';
            $this->data['row']->duration='';
            $this->data['row']->remarks='';
        $this->data['employee_id']=$this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name','','and group_type=10 and active="Yes"');
        $this->data['activity_name']=$this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code','','and lookup_type="OPERATION_MANUAL_ACTIVITIES"');
        
         }*/
        
            if($id!=0){
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('w_jobactivity_hdr_t')->where('job_activity_id',$id)->get();
            $this->data['row'] = $table[0];
        $this->data['employee_id']=$this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name',$table[0]->employee_id,'and group_type=10 and active="Yes"');
          $this->data['linedata'] = \DB::table('w_jobactivity_lines_t')->where('job_activity_hdr_id',$id)->get();
if(count($this->data['linedata'] )>0)
{
         foreach($this->data['linedata']  as $key=>$value){
             
        $this->data['linedata'][$key]->activity_name=$this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code',$value->activity_name,'and lookup_type="OPERATION_MANUAL_ACTIVITIES"');
        $this->data['linedata'][$key]->start_datetime=$value->start_datetime;
        $this->data['linedata'][$key]->end_datetime=$value->end_datetime;
        $this->data['linedata'][$key]->duration=$value->duration;
        
        
         }
}
else
{
     
     
        $this->data['activity_name']=$this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code','','and lookup_type="OPERATION_MANUAL_ACTIVITIES"');
        $this->data['linedata']=array(); 
}
         }else{
            
             $this->data['row'] = (object)array();
            $this->data['row']->job_activity_id='';
            $this->data['row']->remarks='';
        $this->data['employee_id']=$this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name','','and group_type=10 and active="Yes"');
     
        $this->data['activity_name']=$this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code','','and lookup_type="OPERATION_MANUAL_ACTIVITIES"');
        $this->data['start_datetime']='';
        $this->data['end_datetime']='';
        $this->data['duration']='';
        $this->data['linedata']=array(); 
         }
        
        
    

       return view('documentinterntransfer.form',$this->data);  
    }
    
    public function save(Request $request)
    {
      //dd($_POST); 
        /*$edit_id = $request->input('job_activity_id');
		
		if($edit_id == '')
		{
			$jobactivity = new jobactivity();
			$jobactivity->emp_id = $request->input('employee_id');
			$jobactivity->activity_name = $request->input('activity_name');
			$jobactivity->start_datetime = $request->input('start_datetime');
			$jobactivity->end_datetime = $request->input('end_datetime');
			$jobactivity->remarks = $request->input('remarks');
			$jobactivity->duration = $request->input('duration');
			$jobactivity->created_at= date('Y-m-d h:i:s');
			$jobactivity->updated_at = date('Y-m-d h:i:s');
			//dd($jobactivity);
			$jobactivity->save();
			$id = $jobactivity->job_activity_id;
            $table = $jobactivity->getTable();
			$column = $jobactivity->getKeyName();
			$this->hrmssaveinsert($table,$column,$id,1);
// auditlog
            $this->auditlog($id,"jobactivity","create",$_POST,"w_jobactivity_t");
             $this->data['status']="success";
             $this->data['message']="Job Activity Saved Successfully";
       	return 1;
		}
		else
		{
			$data['emp_id'] = $request->input('employee_id');
			$data['activity_name'] = $request->input('activity_name');
			$data['start_datetime'] = $request->input('start_datetime');
			$data['end_datetime'] = $request->input('end_datetime');
			$data['remarks'] = $request->input('remarks');
			$data['duration'] = $request->input('duration');
		//	$data['created_at']= date('Y-m-d h:i:s');
			$data['updated_at'] = date('Y-m-d h:i:s');
			$update = DB::table('w_jobactivity_t')->where('job_activity_id',$edit_id)->update($data);
		    $jobactivity  = jobactivity::findOrFail($edit_id); 
		    $table = $jobactivity->getTable();
			$column = $jobactivity->getKeyName();
			$this->hrmssaveinsert($table,$column,$edit_id,2);
                           // auditlog
              $this->auditlog($edit_id,"jobactivity","edit",$_POST,"w_jobactivity_t");
              $this->data['status']="success";
             $this->data['message']="Job Activity Updated Successfully";
			return 2;
		}*/
		
		
		$id='';
        $data = $this->validatePost($request->all(),$this->table,'header');
      //  \DB::update("UPDATE `beatmapping_lines_tbl` SET `map_status`=1 where `beatmappinglines_id`=".$data['beatmappinglines_id']);
        $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');
      
        \DB::beginTransaction();
        try
        {
                if($_POST['job_activity_id'] == ''){
                $action = 'create';
                                $order_status="Saved Successfully";
               }else{
                $action = 'update';
                                  $order_status="Updated Successfully";
               }

           
   
               
                 $id=$this->model->insertRow($data);
            $lid=$this->submodel->subgridSave($lines_data,$id);
                
                $this->auditlog($id,"jobactivity",$action,$data,"w_jobactivity_hdr_t");

            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => $order_status));
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }
    
    public function show($id=null)
    {
         
    $headerdata = \DB::select("SELECT
    w_jobactivity_hdr_t.*,
    CONCAT(
        hr_employee_t.employee_number,
        '-',
        hr_employee_t.first_name
    ) AS employee_number,
    CONCAT(
        tb_users.employee_number,
        '-',
        tb_users.first_name
    ) AS first_name
FROM
    w_jobactivity_hdr_t
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by
WHERE w_jobactivity_hdr_t.job_activity_id = '$id'");
       $this->data['headerdata'] = $headerdata[0];
        $this->data['linesdata'] = \DB::select("select w_jobactivity_lines_t.* from w_jobactivity_lines_t left join w_jobactivity_hdr_t on w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id WHERE w_jobactivity_hdr_t.job_activity_id = '$id'");
    
       
                return view('documentinterntransfer.view',$this->data); 
    }
    
    public function destroy($id=null)
    {
                        /**Auditlog**/
        $action = "Delete";
        $this->auditlog($id,"jobactivity",$action,'',"w_jobactivity_hdr_t");
            $query = DB::table('w_jobactivity_hdr_t')->where('job_activity_id',$id)->delete();
            $query = DB::table('w_jobactivity_lines_t')->where('job_activity_hdr_id',$id)->delete();
            return 0;
    }
    
    public function soproductdetails($id=null){
      $employeedetails=\DB::table('hr_employee_t')->select('employee_id')->where('reporting_manager',$id)->get(); 
      if(count($employeedetails)>0){
          $empid="";
      foreach($employeedetails as $key=>$value){
          $empid.=$value->employee_id.",";
       }
     $empid1=trim($empid,",");
        $productdetails=\DB::table('productmapping_hdr_tbl')->leftjoin('productmapping_lines_tbl','productmapping_lines_tbl.productmapping_id','=','productmapping_hdr_tbl.productmapping_id')->select('productmapping_lines_tbl.prd_group_id','productmapping_lines_tbl.prd_category_id','productmapping_lines_tbl.prd_id')->WhereIn('employee_id',explode(",",$empid1))->get(); 
     return $productdetails;
      }else{
          
      return 0;    
      }
     
    }
    
    /*vj purpose: product details*/
  public function getselectproductgridData()
{
       $wh="";
  $comp=\Session::get('companyid');
    if(isset($_GET['prd_category_id']))
    {
            $wh.="";
            //and m_customers_t.city_id='".$_GET['town']."' and m_customers_t.state_id='".$_GET['state']."'
    }

     
    
        if($_GET['_search']=='true')
        {
            $wh.=$this->jqgridsearch('m_products_t',$_GET['filters'],'');
        }
   $wh.=" and m_products_t.active='Yes' and m_products_t.company_id=".$comp;
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;

       
            $result=\DB::select("SELECT m_products_t.* from m_products_t where 1=1 $wh");
      
      
       

        $count = count($result);
        if( $count > 0 && $limit > 0)
        {
            $total_pages = ceil($count/$limit);
        }
        else
        {
            $total_pages = 0;
        }
        if ($page > $total_pages)
        $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

       
            $SQL = "SELECT m_products_t.* from m_products_t where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
    
    $result = \DB::select($SQL);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
}
    
    /*vj purpose:get product name*/
    public function getproductname($id=null){
       $bp=\DB::select("select * from m_products_t where product_id=".$id);
       return $bp;
    }
    /*end*/
}
