<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Materialreturn;
use App\Materialreturnlines;
use App\qasubmitstage;
use App\jobcard;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class materialreturnController extends Controller
{
		/*deepika purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/
    public function __construct()
  {
    $this->data=array();
    $this->data['pageMethod']=\Request::Route()->getName();
    $this->model 	= new Materialreturn();
	$this->submodel = new Materialreturnlines();
    $this->table='w_material_return_hdr';
    $this->subtable='w_material_return_lines';
    $this->data['pageFormtype']='ajax';
    $this->middleware('auth');
		 $this->data['urlmenu']=$this->indexs(); 
  }

	
  public function index()
  {
	
    return view('materialreturn.table',$this->data);
 }

	
	public function getmaterialreturnData()
	{
	
	   $wh='';


		$loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization'); 
        $groupname=\Session::get('groupname');
        if($groupname=='1' || $groupname=='Admin'){
        $wh.='and  w_jobcard_hdr_t.company_id='.$compy;  
        }else{
            $wh.='and  w_jobcard_hdr_t.company_id='.$compy.' and w_jobcard_hdr_t.location_id='.$loc;      
        }

  $SQL = "SELECT
    w_qa_submitstage_trx_t.batch_no,
    w_jobcard_hdr_t.w_jobs_hdr_id,
    w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id,
    w_qa_submitstage_line_t.product_id,
    m_products_t.concatenated_product,
    w_qa_submitstage_line_t.production_qty,
    w_qa_submitstage_line_t.return_qty,
    w_jobcard_hdr_t.job_adjusted_qty,
    w_jobcard_hdr_t.job_no,
    w_jobcard_hdr_t.job_status,
    w_qa_submitstage_trx_t.reference_no,
    w_jobcard_hdr_t.job_date
FROM
    w_qa_submitstage_trx_t
LEFT JOIN w_qa_submitstage_line_t ON(
        w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id = w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id
    )
LEFT JOIN w_qa_materialreturn_t ON
    (
        w_qa_materialreturn_t.qa_submitstage_line_id = w_qa_submitstage_line_t.qa_submitstage_trx_line_id
    )
LEFT JOIN w_jobcard_hdr_t ON(
        w_jobcard_hdr_t.w_jobs_hdr_id = w_qa_submitstage_trx_t.job_no
    )
LEFT JOIN m_products_t ON m_products_t.product_id = w_jobcard_hdr_t.product_id
WHERE
    w_qa_materialreturn_t.subinventory_id = '0' AND w_qa_submitstage_line_t.return_qty != '0' AND w_qa_materialreturn_t.return_status != 1 $wh
GROUP BY
    w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id
ORDER BY
    w_jobcard_hdr_t.job_date
DESC";
	
$result=\DB::select($SQL);
return DataTables::of($result)->make(true);
}

	
public function create($id=null){

 $this->data['id'] = $id;
 $table = \DB::table('w_qa_submitstage_trx_t')->leftjoin('w_jobcard_hdr_t','w_jobcard_hdr_t.w_jobs_hdr_id','=','w_qa_submitstage_trx_t.job_no')->where('qa_submitstage_trx_hdr_id',$id)->get();
 // dd($table[0]);
 $this->data['row'] = $table[0];
 $this->data['job_no'] = $table[0]->job_no;
 $this->data['batch_no'] = $table[0]->batch_no;
 $this->data['qa_status'] = $table[0]->qa_status;
 $this->data['subinventory_id'] =$this->jcustomselect('m_subinventory_t','subinventory_id','subinventory_name',$table[0]->subinventory_id,'and production_store="Yes"');
	$this->data['sublocator_id'] =$this->jCombo('m_sublocators_t','sublocator_id','locator_code',$table[0]->sublocator_id);
$this->data['pagemode']="edit";
 $this->data['product_id'] = $this->jCombo('m_products_t','product_id','product_code|concatenated_product',$table[0]->product_id);
 $this->data['uom_code_id']=$this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$table[0]->uom_code_id);
 $this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',$table[0]->organization_id);
 $this->data['verifier'] =  $this->jCombo('hr_employee_t','employee_id','employee_number|first_name',$table[0]->verifier);
$this->data['pageurl'] = "materialreturn";


$mtllines=\DB::table('w_qa_materialreturn_t')
        ->leftjoin('w_qa_submitstage_trx_t','w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id','=','w_qa_materialreturn_t.qa_submitsatge_hdr_id')
        ->leftjoin('w_materialreceive_hdr_t','w_materialreceive_hdr_t.w_jobs_hdr_id','=','w_qa_submitstage_trx_t.job_no')->leftjoin('w_qa_submitstage_line_t','w_qa_submitstage_line_t.qa_submitstage_trx_line_id','=','w_qa_materialreturn_t.qa_submitstage_line_id')
            ->leftJoin('w_materialreceive_line_t', function($join){
            $join->on('w_materialreceive_line_t.w_materialreceive_hdr_id', '=', 'w_materialreceive_hdr_t.w_materialreceive_hdr_id');
            $join->on('w_materialreceive_line_t.product_id','=','w_qa_materialreturn_t.product_id');
        })    
	->select('w_qa_submitstage_trx_t.job_no as jobno','w_qa_materialreturn_t.return_qty','w_materialreceive_line_t.batchnumber as batchnumber','w_qa_materialreturn_t.*','w_qa_materialreturn_t.qa_submitstage_line_id as qa_submitstage_trx_line_id','w_qa_submitstage_line_t.qty','w_qa_submitstage_line_t.production_qty','w_qa_submitstage_line_t.comments')
        ->where('w_qa_materialreturn_t.qa_submitsatge_hdr_id','=',$id)
        ->where('w_qa_materialreturn_t.subinventory_id','=','0')
        ->where('w_qa_materialreturn_t.return_qty','!=','0')
        ->where('w_qa_materialreturn_t.return_status','!=','1')->get();

$this->data['linedata'] = $mtllines;


	         $this->data['productid']= $this->jCombo('m_products_t','product_id','product_code|concatenated_product','');
	     	 $this->data['linedata'] = $mtllines;
			foreach ($this->data['linedata'] as $key => $value) {
				
				$productid=$value->product_id;
				$bno=$value->batch_no;
	   	$bno1=\DB::select("select * from i_qoh_detail_t where batch_number='".$bno."' group by batch_number");
	   	$b="<option value=''>--Please Select--</option>";
	   	foreach($bno1 as $k1=>$v1){
			  $b.= "<option value='".$v1->batch_number."' selected='selected'>".$v1->batch_number."</option>";
		}
					$sql=\DB::select("select * from m_products_t where product_id=".$value->product_id);
					/*deepika purpose:set subinventorydetails based on batch number*/
					$qohdata=\DB::select("select locator_id from i_qoh_detail_t where product_id=".$value->product_id." and subinventory_id=6 and batch_number='".$bno1[0]->batch_number."' and qoh_trx_qty>0 order by qoh_detail_id desc");
					
		     if(count($qohdata)>0){
			 $this->data['linedata'][$key]->sublocator_id=$this->jcustomselect('m_sublocators_t','sublocator_id','locator_code',$qohdata[0]->locator_id,' and subinventory_id=6');	 
			 }else{
				  $this->data['linedata'][$key]->sublocator_id=$this->jcustomselect('m_sublocators_t','sublocator_id','locator_code','',' and subinventory_id=6');
			 }
			 /*end*/
				 $this->data['linedata'][$key]->product_id= $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$productid,' and product_id='.$productid);
				  $this->data['linedata'][$key]->uom_code_id=$this->jcustomselect('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id,' and uom_code_id='.$value->uom_code_id);
				$this->data['linedata'][$key]->batchno=$b;
						$this->data['linedata'][$key]->subinventory_id=$this->jcustomselect('m_subinventory_t','subinventory_id','subinventory_name','6',' and subinventory_id=6');

						$comp=\Session::get('companyid');
						$this->data['linedata'][$key]->return_qty=$value->return_qty;
				}

	 return view('materialreturn.form',$this->data);
	}

		/* purpose:to save material return*/
		public function save(Request $request)
		{
			
		
		  $form = $request->all();
	      $form = $request->except([
        '_token','form_config','form_data_json','save_status','submit_type',
        'choosefile','existing_file','reference_no','enable-masterdetail',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');

         \DB::beginTransaction();
            try
            {
                 $id=$this->model->insertRow($data);
				
			     $this->auditlog($id,"materialreturn",'create',$data,"w_material_return_hdr");
				
				$qalineid="";
				 foreach($_POST['bulk_qa_submitstage_trx_line_id'] as $k => $v){
				$qalineid.=$v.",";
           $tt= \DB::table('w_qa_submitstage_line_t')->where('qa_submitstage_trx_line_id',$v)->update(['material_return_status' => '1']);
           \DB::table('w_qa_materialreturn_t')->where('qa_materialreturn_id',$_POST['bulk_qa_materialreturn_id'][$k])->update(['return_status' => '1']);
        }
				$qalineid1=rtrim($qalineid,",");
  
				 $qaline=\DB::table('w_qa_submitstage_line_t')->whereIn('qa_submitstage_trx_line_id',explode(",",$qalineid1))->get();
				$qalinecnt=count($qaline);        
				$qaline1=\DB::table('w_qa_submitstage_line_t')->whereIn('qa_submitstage_trx_line_id',explode(",",$qalineid1))->where('material_return_status','1')->get();
                $qalinecnt1=count($qaline1);
				  if($qalinecnt1==$qalinecnt){
					 \DB::table('w_qa_submitstage_trx_t')->where('qa_submitstage_trx_hdr_id',$qaline1[0]->qa_submitstage_trx_hdr_id)->update(['material_return_status' => '1']);  
				  }
				  unset($lines_data['line_no']);
				  unset($lines_data['qa_submitstage_trx_line_id']);
				
                 $lid=$this->submodel->subgridSave($lines_data,$id);
			
			   $result =\DB::select("SELECT * FROM w_material_return_lines where material_return_hdr_id = '$id'");
	           $trsns =\DB::table('m_transaction_types_t')->where('transaction_type_name','RETURN QTY STORE MOVE')->get();
	           
				foreach($result as $k=>$v){

			if($v->subinventory_id!="" && ($v->return_qty!="")){
						
				/* insert data into mtl transaction tbl */
			$mtdata['trx_source_type_id']=$trsns[0]->transaction_source_id;
			$mtdata['trx_action_id']=$trsns[0]->transaction_action_id;
			$mtdata['trx_type_id']=$trsns[0]->transaction_type_id;
			$mtdata['trx_source_hdr_id']=$id;
			$mtdata['trx_source_line_id']='';
			$mtdata['line_number']=$k+1;
			$mtdata['trx_reference']= "materil return";
			$mtdata['product_id']=$v->product_id;
			$mtdata['trx_qty']= $v->return_qty;
			$mtdata['trx_uom']=$v->uom_code_id;
			$mtdata['trx_date']= date('Y-m-d');
			$mtdata['created_by']= \Session::get('id');
			$mtdata['created_at']= date('Y-m-d');
			$mtdata['updated_at']= date('Y-m-d');
			$mtdata['last_updated_by']= \Session::get('id');
            $mtdata['subinventory_id']=$v->subinventory_id;
			$mtdata['locator_id']=$v->sublocator_id;
			$mtdata['organization_id']=\Session::get('organization');
			$mtdata['company_id']=\Session::get('companyid');
			$mtdata['location_id']=\Session::get('location');
			
			$mtlid = \DB::table('m_material_trx_t')->insertGetId($mtdata);
					
			/* insert data into qoh detail tbl */

			$qohdata['product_id'] = $v->product_id;
			$qohdata['qoh_trx_qty']= $v->return_qty;
			$qohdata['qoh_uom_code_id']=$v->uom_code_id;
			$qohdata['create_trx_id']=$mtlid;
			$qohdata['subinventory_id']=$v->subinventory_id;
			$qohdata['locator_id']=$v->sublocator_id;
			$qohdata['batch_number']=$v->batchno;
			$qohdata['job_id']=$_POST['job_no'];
			$qohdata['qoh_trx_date']= date('Y-m-d');
            $qohdata['qoh_source_id']=$id;
			$qohdata['qoh_source']="material return";
			$qohdata['organization_id']=\Session::get('organization');
			$qohdata['company_id']=\Session::get('companyid');
			$qohdata['location_id']=\Session::get('location');
			$qohdata['created_by']= \Session::get('id');
			$qohdata['created_at']= date('Y-m-d');
			$qohdata['updated_at']= date('Y-m-d');
			$qohdata['last_updated_by']= \Session::get('id');

			$qohid = \DB::table('i_qoh_detail_t')->insertGetId($qohdata);
				}
				}

                 \DB::commit();

     
                 return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id));
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
/*end*/
/*deepika purpose:to view qasubmit*/
public function show(qasubmitstage $qasubmitstage,$id=null){

        if(isset($id)){
     $data=DB::table('w_qa_submitstage_trx_t')->leftjoin('m_products_t','m_products_t.product_id','=','w_qa_submitstage_trx_t.product_id')->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','w_qa_submitstage_trx_t.uom_code_id')->leftjoin('w_jobcard_hdr_t','w_jobcard_hdr_t.w_jobs_hdr_id','=','w_qa_submitstage_trx_t.job_no')->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','w_qa_submitstage_trx_t.verifier')
		 ->leftjoin('m_subinventory_t','m_subinventory_t.subinventory_id','=','w_qa_submitstage_trx_t.subinventory_id')->leftjoin('m_sublocators_t','m_sublocators_t.sublocator_id','=','w_qa_submitstage_trx_t.sublocator_id')->where('w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id',$id)->get();
       $this->data['header']=$data[0];
       $this->data['pageurl']=$_GET['pageurl'];
      $linesdata = \DB::table('w_qa_submitstage_line_t')->leftjoin('m_products_t','m_products_t.product_id','=','w_qa_submitstage_line_t.product_id')->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','m_uom_codes_t.uom_code_id') ->leftjoin('m_subinventory_t','m_subinventory_t.subinventory_id','=','w_qa_submitstage_line_t.subinventory_id')->leftjoin('m_sublocators_t','m_sublocators_t.sublocator_id','=','w_qa_submitstage_line_t.sublocator_id')->where('w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id',$id)->groupBy('w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id')->get(); 
      $this->data['linesdata']=$linesdata;
      	return view('qasubmitstage.view',$this->data);
    	}
}
/*end*/
/*purpose:to show employee hour details based on machine capacity*/
    public function employeedetails($id=null){
		
	$sql = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id',$id)->get();
	$jobassid=explode(",",$sql[0]->job_assigned_to);
	$jobhr=$sql[0]->hour;
	

		$html = "<table class='emp_class fixed_table' style='padding:5px;border-spacing: 10px;'>";
	    $html .= "<thead style='background:rgba(0, 18, 103, 0.89);color:#fff;'><th >S.No</th><th >Employee Name</th><th >Actual Hours</th><th >Start Time</th><th >End Time</th><th >Working Hours</th><th></th></thead><tbody  class='emp_class_body'>";
		foreach($jobassid as $key=>$val){
		$sel = $this->jCombo('hr_employee_t','employee_id','first_name',$val);	
			   $res = \DB::table('hr_employee_t')->select('first_name')->where('employee_id',$val)->get();
		  $html .="<tr class='emp_clone rcopy1'>";
		
			
				$html .="<td style='width:11%'><input type='text' name='line_no' class='form-control line_no' value=".($key+1)."></td>";
			$html .="<td style='width:34%'>"."<input type='hidden' class='form-control w_jobs_hdr_id".$key."' form-control' value='".$sql[0]->w_jobs_hdr_id."</td>";
			$html .="<td ><div class='form-group'>
	<select name='job_assigned_to[]' class='form-control job_assigned_to'>".$sel."</select>
			</div>
			</td>";
				$html .="<td style='width:11%'><input type='text' name='actual_hrs[]' class='actual_hrs form-control actual_hrs".$key."' value='".$jobhr."'></td>";
				$html .="<td style='width:18%'><input type='text' name='start_time[]' class='timepicker start_time form-control start_time".$key."' value='' style='border:1px solid #07234e;'></td>";
				$html .="<td style='width:18%'><input type='text' name='end_time[]' class='end_time timepicker form-control end_time".$key."' value='' style='border:1px solid #07234e;'></td>";
				$html .="<td style='width:16%'><input type='text' name='working_hrs[]' class='working_hrs form-control working_hrs".$key."' value=''></td>
				 <td style='width:8%'>
                    <a class='empremove empremove0'><i class='fa btn-xs fa-2x fa-minus-circle rem' aria-hidden='true'></i></a>
                    <input type='hidden' name='counter1[]'>
                </td>";
				$html .="</tr>";
		}
		$html .="</tbody></table>";
		return $html;
	}
/*end*/	
}