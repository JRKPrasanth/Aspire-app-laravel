<?php

namespace App\Http\Controllers;

use App\jobactivity;
use App\jobactivitylines;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class JobactivityController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model=new jobactivity();
        $this->submodel=new jobactivitylines();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlmenu']=$this->indexs();
        $this->table='w_jobactivity_hdr_t';
        $this->subtable='w_jobactivity_lines_t';
    }
    public function index()
    {
        return view('jobactivity.index',$this->data);  
    }
    
    public function getjobactivitydata()
    {
     
      $comp=\Session::get('companyid');
        $wh='';

    $result = \DB::select("select * from (SELECT
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
WHERE
    1 = 1
ORDER BY
    w_jobactivity_hdr_t.job_activity_id
DESC ) as v1 where 1=1 $wh");

return DataTables::of($result)->make(true);
		
    }
    
    public function create($id=null)
    { 

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
        $this->data['linedata'][$key]->product=$this->jcustomselect('m_products_t','product_id','concatenated_product',$value->product,'and active="Yes"');
        $this->data['linedata'][$key]->machine=$this->jcustomselect('w_machine_hdr_t','machine_hdr_id','machine_name',$value->machine,'and active="Yes"');
        $this->data['linedata'][$key]->start_datetime=$value->start_datetime;
        $this->data['linedata'][$key]->end_datetime=$value->end_datetime;
        $this->data['linedata'][$key]->duration=$value->duration;
        $this->data['linedata'][$key]->batch_no=$value->batch;
        $this->data['linedata'][$key]->qty=$value->qty;
        
        
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
         $this->data['product']=$this->jcustomselect('m_products_t','product_id','concatenated_product','','and active="Yes" and product_group_id="1" order by concatenated_product asc');
         $this->data['machine']=$this->jcustomselect('w_machine_hdr_t','machine_hdr_id','machine_name','','and active="Yes"');
        $this->data['start_datetime']='';
        $this->data['end_datetime']='';
        $this->data['duration']='';
        $this->data['batch_no']='';
        $this->data['qty']='';
        $this->data['linedata']=array(); 
         }
        
       return view('jobactivity.form',$this->data);  
		
    }
    
    public function save(Request $request)
    {
     
			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file','beatmappinglines_id',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');
      
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
    
       
                return view('jobactivity.view',$this->data); 
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
