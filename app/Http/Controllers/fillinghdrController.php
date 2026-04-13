<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Workorderlines;
use App\fillinghdr;
use App\fillinglines;
class FillinghdrController extends Controller
{
   	public function __construct()
	{
		$this->data=array();
                $this->data['urlmenu']=$this->indexs(); 
		$this->model=new fillinghdr;
		$this->submodel=new fillinglines;
		$this->table = "w_jobcard_hdr_t";
        $this->subtable = "w_fillings_lines_t";
        $this->pageModule = "filling";
	}
        
        
    public function index()
    {
      	$this->data['uomopt']=$this->jqgridselect('m_uom_codes_t','uom_code_id','uom_code');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
        $this->data['pageMethod']=\Request::route()->getName();
		
		return view("fillinghdr.table",$this->data);
    }
	
	public function create($id=null)
    {
    	$jobcardfp =\DB::select('select * from w_workorder_lines_t where workorder_line_id in('.$id.')');
		$this->data['row']= (object)array();		
		$this->data['linedata']= (object)array();		
	    $this->data['row']->w_jobs_hdr_id = '';
	    $this->data['row']->seq_count = '';
	    $this->data['row']->job_no = '';
	    $this->data['row']->job_completion_date  = '';
	    $this->data['row']->start_date = date("Y-m-d");
	    $this->data['row']->end_date ='';
	    $this->data['row']->job_adjusted_qty ='';
	    $this->data['row']->job_date = date("d-m-Y");
	    $this->data['row']->reference_source ='FILLING PROCESS';
	    $this->data['row']->remarks ='';
	    $this->data['row']->reference_source_id ='';
     	$this->data['row']->batch_no ='';
		$this->data['row']->machine_capacity ='';
		$this->data['row']->job_process ='';
		$this->data['machine_hdr_id'] = $this->jCombo('w_machine_hdr_t','machine_hdr_id','machine_name','');	 
		$this->data['row']->job_status="";	 
	if(isset($_GET['prdsub_id'])){
		  $this->data['product_id']=$this->jCombo('i_product_subassembly_t','product_sub_assembly_id','sub_assembly_concate',$_GET['prdsub_id']);
	}
	    $this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
	    $this->data['job_assigned_to'] = $this->jCombo('hr_employee_t','employee_id','first_name','');
	    $this->data['job_created_by'] = $this->jCombo('hr_employee_t','employee_id','first_name',\Session::get('emp_id'));
    	 $this->data['linedata'] = array();
		$totaljob=0;
		foreach ($jobcardfp as $key => $value) {
			$this->data['linedata'][$key] = (object) array();
			$this->data['linedata'][$key]->line_no = $key+1;
			$this->data['linedata'][$key]->fillings_lines_id ='';
			//$this->data['linedata'][$key]->fillings_hdr_id ='';
			$sql=\DB::select('select product_pack_id,product_id from m_products_t where product_id='.$value->product_id);
			$uomcon=\DB::select('select product_id,uom_conversion_id,uom_value from m_uom_conversion_t where product_id='.$value->product_id);
			$totaljob +=$uomcon[0]->uom_value * $value->qty;
			$this->data['linedata'][$key]->total_qty=$uomcon[0]->uom_value * $value->qty;
			$this->data['linedata'][$key]->product_id =$this->jCombo('m_products_t','product_id','concatenated_product',$value->product_id); 
			$this->data['linedata'][$key]->uom_code_id =$this->jCombo('m_uom_codes_t','uom_code_id','uom_code', $value->uom_code_id);
			$this->data['linedata'][$key]->qty = $value->qty;
			$this->data['linedata'][$key]->comments = '';
			$this->data['linedata'][$key]->reference_source = 'WORKORDER';
			$this->data['linedata'][$key]->reference_hdr_id = $value->workorder_hdr_id;
			$this->data['linedata'][$key]->reference_line_id = $value->workorder_line_id;
		}
		$this->data['row']->job_qty = $totaljob;
    	return view('fillinghdr.form',$this->data);
    }
     public function save(Request $request)
            {
		
			   $id='';
		     $jobcard=new fillinghdr();
             $this->model = new fillinghdr();
  	         $jobcardlines=new fillinglines();
             $this->submodel = new fillinglines();
	          $primary            = self::findPrimarykey('w_jobcard_hdr_t');
		      $primaryline        = self::findPrimarykey('w_fillings_lines_t');
              
               if ($_POST['job_no'] =="")
                {
				   $seqno1=$this->Seqno('B','w_jobcard_hdr_t','');
                $jobcard->batch_no = $seqno1;
			if($_POST['job_process']=='FILLING'){
                $seqno=$this->Seqno('JOBFP','w_jobcard_hdr_t','');
                $jobcard->job_no = $seqno;
                }else if($_POST['job_process']=='PACKING')
			
			{
			    $seqno=$this->Seqno('JOBPK','w_jobcard_hdr_t','');
                $jobcard->job_no = $seqno;
			}else if($_POST['job_process']=='LABEL PRINTING'){
			    $seqno=$this->Seqno('JOBLP','w_jobcard_hdr_t','');
                $jobcard->job_no = $seqno;
			}
			   }else
               {
                  $seqno = $_POST['job_no'];
                  $seqno1 = $_POST['batch_no'];
					
			   }
				   
	           
  $compy=\Session::get('companyid');
  $loc=\Session::get('location');
  $org=\Session::get('organization');
		
		  if($_POST['w_jobs_hdr_id']!="")
        {
            $jobcard->w_jobs_hdr_id=$_POST['w_jobs_hdr_id'];
	    }
	 	$jobcard->job_date=$_POST['job_date'];
		$jobcard->job_completion_date=$_POST['job_completion_date'];
		$jobcard->product_id=$_POST['product_id'];
	 	$jobcard->job_process=$_POST['job_process'];
	 	$jobcard->job_qty=$_POST['job_qty'];
	 	$jobcard->start_date=$_POST['start_date'];
	 	$jobcard->end_date=$_POST['end_date'];
	 	$jobcard->remarks=$_POST['remarks'];
	 	$jobcard->job_status=$_POST['job_status'];
     	$jobcard->job_created_by=$_POST['job_created_by'];
	 	$jobcard->reference_source=$_POST['reference_source'];
	 	$jobcard->reference_source_id=$_POST['reference_source_id'];
	 	$jobcard->machine_hdr_id=$_POST['machine_hdr_id'];
	 	$jobcard->machine_capacity=$_POST['machine_capacity'];
	 	$assigned_to=implode(",",$_POST['job_assigned_to']);
	 	$jobcard->job_assigned_to=$assigned_to;
    	$jobcard->remarks=$_POST['remarks'];
    	$jobcard->organization_id=$org;
    	$jobcard->location_id=$loc;
    	$jobcard->company_id=$compy;
            //dd($jobcard);    
                	if($_POST['w_jobs_hdr_id'] =="")
		{
        $id=$this->insertData($this->model,$primary,$jobcard,$_POST['w_jobs_hdr_id']); 
			//dd($id);			
		}
		else
		{
			$data=\DB::table('w_jobcard_hdr_t')
    ->where($primary, $_POST['w_jobs_hdr_id'])
    ->update([
             'job_no' => $_POST['job_no'],
             'w_jobs_hdr_id'       => $_POST['w_jobs_hdr_id'],
             'product_id'       => $_POST['product_id'],
             'job_date'       => $_POST['job_date'],
             'job_completion_date'       => $_POST['job_completion_date'],
             'start_date'       => $_POST['start_date'],
             'end_date'       => $_POST['end_date'],
             'remarks'       => $_POST['remarks'],
             'batch_no'       => $_POST['batch_no'],
             'job_status'       => $_POST['job_status'],
             'job_qty'       => $_POST['job_qty'],
             'job_process'       => $_POST['job_process'],
              'job_created_by'       => $_POST['job_created_by'],
             'machine_hdr_id'       => $_POST['machine_hdr_id'],
             'job_assigned_to'       => $assigned_to,
             'organization_id'       => $_POST['organization_id'],
             'reference_source'       => $_POST['reference_source'],
             'machine_capacity'       => $_POST['machine_capacity'],
            ]);
			$id = $_POST['w_jobs_hdr_id'];
		}
               
                    
	    $oldid=\DB::table('w_fillings_lines_t')->where('fillings_hdr_id',$id)->get();

	
            if($oldid->isEmpty())
            {

                for($i=0;$i<count($_POST['counter']);$i++)
                {
                            	//dd($_POST['bulk_fillings_lines_id']);
                     $data['fillings_hdr_id']=$id;
                     $data['fillings_lines_id']=$_POST['bulk_fillings_lines_id'][$i]?$_POST['bulk_fillings_lines_id'][$i]:0;
                     $data['line_no']=$_POST['bulk_line_no'][$i];
                     $data['product_id']=$_POST['bulk_product_id'][$i];
                     $data['uom_code_id']=$_POST['bulk_uom_code_id'][$i];
                     $data['qty']=$_POST['bulk_qty'][$i];
                     $data['comments']=$_POST['bulk_comments'][$i];
                     $data['reference_source']=$_POST['bulk_reference_source'][$i];
                     $data['reference_hdr_id']=$_POST['bulk_reference_hdr_id'][$i];
                     $data['reference_line_id']=$_POST['bulk_reference_line_id'][$i];
					$data['total_qty']=$_POST['bulk_total_qty'][$i];
                     $data['company_id']=$compy;
                     $data['location_id']=$loc;
                     $data['organization_id']=$org;
                   // dd($data);
                    \DB::table('w_fillings_lines_t')->insert($data);

                }
                    return response()->json(array('status' => 'success', 'message' => 'Filling Jobcard Saved','id' => $id));

            } 
            else {
            $existingId = array();
            foreach ($oldid as $key=> $value)
            {
                $oldIds[]= $value->$primaryline;
            }

            foreach ($_POST['bulk_'.$primaryline] as $val)
            {
                $newIds[]= $val;
            }

            $existingId=  array_replace($newIds, $oldIds);

            $oldcount=count($oldIds);
            $newcount=count($newIds);

            if($oldcount <= $newcount)
            {
                for($i=0;$i<$newcount;$i++)
                {
					
                    $data['fillings_hdr_id']=$id;
                    $data['fillings_lines_id']=$_POST['bulk_fillings_lines_id'][$i]?$_POST['bulk_fillings_lines_id'][$i]:0;

                    $data['line_no']=$_POST['bulk_line_no'][$i];
                    $data['product_id']=$_POST['bulk_product_id'][$i];
                    $data['uom_code_id']=$_POST['bulk_uom_code_id'][$i];
                    $data['qty']=$_POST['bulk_qty'][$i];
                    $data['comments']=$_POST['bulk_comments'][$i];
                    $data['reference_source']=$_POST['bulk_reference_source'][$i];
                    $data['reference_hdr_id']=$_POST['bulk_reference_hdr_id'][$i];
					$data['reference_line_id']=$_POST['bulk_reference_line_id'][$i];
					$data['total_qty']=$_POST['bulk_total_qty'][$i];
                    $data['company_id']=$compy;
                    $data['location_id']=$loc;
                    $data['orgnization_id']=$org;
                    if($data['fillings_lines_id']=$existingId[$i])
                    {
                        $this->submodel::find($data['fillings_lines_id'])->update($data);
                    }
					else
					{
                        \DB::table('w_fillings_lines_t')->insert($data);
                    }
                    
                }
               
            } else {
   
                $arraydiff=  array_diff($oldIds, $newIds);
                
                foreach ($arraydiff as $key)
                {
                    if (($key = array_search($key, $oldIds)) !== false)
                    {
                        unset($oldIds[$key]);
                    }
                }
                foreach($arraydiff as $val)
                {
                    \DB::table('w_fillings_lines_t')->where('fillings_lines_id',$val)->delete();
                }
                
                for($i=0;$i<count($oldIds);$i++)
                {
                    $data['fillings_hdr_id']=$id;
                    $data['fillings_lines_id']= $_POST['fillings_lines_id'][$i]? $_POST['fillings_lines_id'][$i] :0;

                    $data['line_no']   = $_POST['bulk_line_no'][$i];
                    $data['product_id']     = $_POST['bulk_product_id'][$i];
                    $data['uom_code_id']              = $_POST['bulk_uom_code_id'][$i];
                    $data['qty']                = $_POST['bulk_qty'][$i];
                    $data['comments']                = $_POST['bulk_comments'][$i];
                    $data['reference_source']                  = $_POST['bulk_reference_source'][$i];
                    $data['reference_hdr_id']                   = $_POST['bulk_reference_hdr_id'][$i];
		            $data['reference_line_id']					= $_POST['bulk_reference_line_id'][$i];
					$data['total_qty']=$_POST['bulk_total_qty'][$i];
                    $data['organization_id']                = $org;
                    $data['company_id']           = $compy;
                    $data['location_id']         = $loc;
                     $this->submodel::find($data['fillings_lines_id'])->update($data);
                }
            }
        } 
			
			\DB::commit();
    			    return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'jobno'=>$seqno));
                
                

            }

	public function getfillingData_old()   
    {
		
        $wh='';
	if($_GET['_search']=='true')
	{
        $wh=$this->jqgridsearch('w_productionplan_hdr_t',$_GET['filters']);
	}
    

	$page = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx = $_GET['sidx'];
	$sord = $_GET['sord'];
	if(!$sidx) $sidx =1;

	$result = \DB::select("SELECT COUNT(productionplan_hdr_id) AS count FROM w_productionplan_hdr_t where 1=1 $wh");
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
		
	$sql="SELECT
		    w_productionplan_hdr_t.productionplan_hdr_id,
		    w_productionplan_hdr_t.plan_no,
		    w_productionplan_hdr_t.plan_date,
		    w_productionplan_hdr_t.production_qty,
		    w_productionplan_hdr_t.remarks,
		    i_product_subassembly_t.sub_assembly_concate AS product_id,
		    m_uom_codes_t.uom_code AS uom_code_id
		FROM
		    w_productionplan_hdr_t
		LEFT JOIN i_product_subassembly_t ON(
		        i_product_subassembly_t.product_sub_assembly_id= w_productionplan_hdr_t.product_id
		    )
		LEFT JOIN m_uom_codes_t ON
		    (
		        w_productionplan_hdr_t.uom_code_id = m_uom_codes_t.uom_code_id
		    ) where 1=1 and  w_productionplan_hdr_t.plan_status <>'APPROVED' $wh ORDER BY productionplan_hdr_id desc";
		               
	$result = \DB::select($sql);
		
	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

	echo json_encode($responce);
		
		
    }
        
        
    public function getfillinggriddata()   
    {
    	$wh='';
		if($_GET['_search']=='true')
		{
			$table=array();
			$table[]='w_workorder_hdr_t';
			$wh=$this->jqgridsearch('w_workorder_lines_t',$_GET['filters'],$table);
		}
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(w_workorder_lines_t.workorder_line_id) AS count FROM w_workorder_lines_t left join w_workorder_hdr_t on(w_workorder_hdr_t.workorder_hdr_id = w_workorder_lines_t.workorder_hdr_id) where  1=1 $wh");
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
		$SQL = "SELECT  w_workorder_hdr_t.workorder_hdr_id,i_product_subassembly_t.sub_assembly_concate,i_product_subassembly_t.product_sub_assembly_id,w_workorder_hdr_t.workorder_no,w_workorder_hdr_t.workorder_date,w_workorder_hdr_t.due_date,w_workorder_lines_t.workorder_line_id,m_products_t.product_id,m_products_t.concatenated_product as product_id,m_uom_codes_t.uom_code as uom_code_id,w_workorder_lines_t.qty,w_workorder_lines_t.start_date,w_workorder_lines_t.end_date FROM `w_workorder_hdr_t` left join w_workorder_lines_t on(w_workorder_hdr_t.workorder_hdr_id=w_workorder_lines_t.workorder_hdr_id) left join m_products_t on(w_workorder_lines_t.product_id=m_products_t.product_id) left join i_product_subassembly_t on(i_product_subassembly_t.product_sub_assembly_id=m_products_t.product_sub_assembly_id) left join m_uom_codes_t on(m_uom_codes_t.uom_code_id=w_workorder_lines_t.uom_code_id) where 1=1 and w_workorder_lines_t.plan_status=0 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		$result = \DB::select( $SQL );
		// dd($result);
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
   function findPrimarykey( $table )
  	{
  	$primaryKey = '';
  	foreach(\DB::select("show columns from ".$table." where extra like '%auto_increment%'") as $key)
  	{
  	$primaryKey = $key->Field;
  	}
  	return $primaryKey;
  	}     
}
