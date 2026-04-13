<?php
namespace App\Http\Controllers;

use App\Materialreceivehdr;
use App\Materialreceivelines;
use Illuminate\Http\Request;
use Redirect;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class MaterialreackknowledgeController extends Controller
{
	/*deepika purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/
	public function __construct()
{
   $this->data=array();
   $this->data['urlmenu']=$this->indexs(); 
   $this->model=new Materialreceivehdr;
   $this->submodel=new Materialreceivelines;
   $this->data['pageMethod']=\Request::route()->getName();
   $this->data['pageFormtype']='ajax';
   $this->data['pageModule']='materialreceive';
   $this->table="w_materialreceive_hdr_t";
   $this->subtable="w_materialreceive_line_t";
	 if($this->data['pageMethod']=="materialreceive"){
		//$this->data['status']='OPEN';
		$this->data['status']='';
		}
	else {
		$this->data['status']='';
	}
	
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
  
    return view('matreacknowledgement.table',$this->data);
   }



        public function show($id=null)
        {
        $data=DB::table('w_materialreceive_hdr_t')->leftjoin('m_products_t','m_products_t.product_id','=','w_materialreceive_hdr_t.product_id')->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','w_materialreceive_hdr_t.uom_code_id')->leftjoin('w_jobcard_hdr_t','w_jobcard_hdr_t.w_jobs_hdr_id','=','w_materialreceive_hdr_t.w_jobs_hdr_id')->where('w_materialreceive_hdr_t.w_materialreceive_hdr_id',$id)->get();
       $this->data['product_code'] = $data[0]->product_code;
       $this->data['concatenated_product'] = $data[0]->concatenated_product;
       $this->data['uom_code'] = $data[0]->uom_code;
       $this->data['job_no'] = $data[0]->job_no;
       $this->data['batch_no'] = $data[0]->batch_no;
       $this->data['job_status'] = $data[0]->job_status;
       $this->data['job_process'] = $data[0]->job_process;
       $this->data['mtl_receive_date'] = $data[0]->mtl_receive_date;
       $this->data['job_qty'] = $data[0]->job_qty;
       $this->data['remarks'] = $data[0]->remarks;
       
       $received_by = \DB::select("SELECT tb_users.first_name, w_materialreceive_hdr_t.created_by FROM w_materialreceive_hdr_t LEFT JOIN tb_users ON w_materialreceive_hdr_t.created_by = tb_users.id where w_materialreceive_hdr_t.w_materialreceive_hdr_id = $id");
       $this->data['received_by'] = $received_by[0]->first_name;
 $linesdata = \DB::table('w_materialreceive_line_t')->leftjoin('m_products_t','m_products_t.product_id','=','w_materialreceive_line_t.product_id')->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','m_uom_codes_t.uom_code_id')->leftjoin('m_sublocators_t','m_sublocators_t.sublocator_id','=','w_materialreceive_line_t.locator_id')->where('w_materialreceive_line_t.w_materialreceive_hdr_id',$id)->groupBy('w_materialreceive_line_t.w_materialreceive_line_id')->get(); 
 $this->data['linesdata']=$linesdata;
 //dd($linesdata);
if(isset($_GET)){
           $this->data['pageurl']=$_GET['pageurl'];
          }
    
		return view('materialreceive.view',$this->data);


        }

    public function getmaterialrereceiveData(Request $request)
    {


  $sql = "SELECT
    w_jobcard_hdr_t.w_jobs_hdr_id,
    w_jobcard_hdr_t.job_no,
    w_jobcard_hdr_t.job_date,
    w_jobcard_hdr_t.job_adjusted_qty,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.job_status,
    m_products_t.concatenated_product,
    m_products_t.product_code,
    m_uom_codes_t.uom_code,
	w_materialissue_hdr_t.receive_status,
	w_materialissue_hdr_t.w_materialissue_hdr_id,
	w_productionplan_hdr_t.plan_no,
	w_jobcard_hdr_t.job_process
FROM
    `w_jobcard_hdr_t`
LEFT JOIN m_products_t ON (m_products_t.product_id = w_jobcard_hdr_t.product_id)
LEFT JOIN m_product_groups_t ON(m_products_t.product_group_id= m_product_groups_t.product_group_id)
LEFT JOIN m_uom_codes_t ON (m_uom_codes_t.uom_code_id = w_jobcard_hdr_t.uom_code_id) 
LEFT JOIN w_materialissue_hdr_t ON (w_materialissue_hdr_t.w_jobs_hdr_id = w_jobcard_hdr_t.w_jobs_hdr_id) 
LEFT JOIN w_materialissue_line_t ON (w_materialissue_hdr_t.w_materialissue_hdr_id = w_materialissue_line_t.w_materialissue_hdr_id) 
LEFT JOIN w_productionplan_hdr_t ON (w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id) 
where 1=1  and m_product_groups_t.group_name='FINISHED GOODS' AND w_jobcard_hdr_t.job_status ='MATERIAL RE-ISSUED' GROUP BY w_jobcard_hdr_t.w_jobs_hdr_id";
 
	
		 
	$result = \DB::select($sql);
	return DataTables::of($result)->make(true);
		
}

	
   public function create($id=null){
       
       $this->data['id'] = $id;

     $table = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id',$id)->get();
     $this->data['organization_id']=$this->jcombo("m_organizations_t","organization_id","organization_name",\Session::get('organization')); 
     $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$table[0]->uom_code_id);
     $this->data['ass_product_id']=$this->jCombo('m_products_t','product_id','product_code|concatenated_product',$table[0]->product_id);
     $this->data['group']=$this->groupname($table[0]->product_id);
     $this->data['w_jobs_hdr_id']=$this->jCombocomp('w_jobcard_hdr_t','w_jobs_hdr_id','job_no',$table[0]->w_jobs_hdr_id);

     $hdr_id = \DB::select("SELECT w_materialreceive_hdr_id FROM w_materialreceive_hdr_t WHERE w_jobs_hdr_id = ?", [$table[0]->w_jobs_hdr_id]);
     $this->data['w_materialreceive_hdr_id'] = $hdr_id[0]->w_materialreceive_hdr_id ;

     $m_hdr_id = \DB::select("SELECT w_materialissue_hdr_id FROM w_materialissue_hdr_t WHERE w_jobs_hdr_id = ?", [$table[0]->w_jobs_hdr_id]);
     $mat_hdr_id = $m_hdr_id[0]->w_materialissue_hdr_id ;
     //dd($this->data['matissue_hdr_id']);
     $this->data['job_qty']=$table[0]->job_adjusted_qty;
     $this->data['batch_no']=$table[0]->batch_no;
     $this->data['job_status']=$table[0]->job_status;
 
     date_default_timezone_set('Asia/Calcutta');
     $this->data['mtl_issue_date']=date("d-m-Y H:i:s");

        $this->data['productid']= $this->jCombo('m_products_t','product_id','product_code|concatenated_product','');

        /*end*/
        


        $planlines=\DB::table('w_materialissue_line_t')->where('w_materialissue_hdr_id',$mat_hdr_id)->where('receive_status', 2)->get();
        $this->data['linedata'] = $planlines; 
        if(count($this->data['linedata']) >= 1)
    {
    foreach($this->data['linedata'] as $key => $value)
    {
        $productid=$value->product_id;

        $this->data['linedata'][$key]->product_id= $this->jCombo('m_products_t','product_id','product_code|concatenated_product',$value->product_id);
        $this->data['linedata'][$key]->uom_code_id=$this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
        $this->data['linedata'][$key]->qty=$value->qty;
        $this->data['linedata'][$key]->process_level = $this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code','','and lookup_type="PROCESS_LEVEL"');
        $this->data['linedata'][$key]->issue_qty=$value->issue_qty;
        $this->data['linedata'][$key]->process_name=$this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code','','and lookup_type="PROCESS_NAME"');
        $this->data['linedata'][$key]->reference_source_hdr_id=$value->w_materialissue_hdr_id;
        $this->data['linedata'][$key]->reference_source_line_id=0;
        $this->data['linedata'][$key]->reference_source='PACKINGMATERIALISSUE';	
    
             $this->data['linedata'][$key]->balance_qty=($value->issue_qty)-($value->issued_qty);
              $this->data['linedata'][$key]->issued_qty=$value->issued_qty;
              $this->data['linedata'][$key]->mtl_issue_qty=$value->mtl_issue_qty;
              $this->data['linedata'][$key]->reference_source_hdr_id=$value->w_materialissue_hdr_id;
               $this->data['linedata'][$key]->receive_status=$value->receive_status;
               $this->data['linedata'][$key]->reference_source_line_id=$value->w_materialissue_line_id;
        $this->data['linedata'][$key]->reference_source='PACKINGMATERIALISSUE';	
      

            
        
        if($productid==$table[0]->product_id){
               unset($this->data['linedata'][$key]);
               }
    }

}





return view("matreacknowledgement.form",$this->data);
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
    public function save(Request $request)
    {
        
    $materialreceive_hdr_id = $request->input('w_materialreceive_hdr_id');
    $job_id = $request->input('w_jobs_hdr_id');
    $bulk_product_ids = $request->input('bulk_product_id');
    $bulk_uom_code_ids = $request->input('bulk_uom_code_id');
    $bulk_qtys = $request->input('bulk_qty');
    $bulk_issue_qtys = $request->input('bulk_issue_qty');
    $bulk_issued_qtys = $request->input('bulk_issued_qty');
    $bulk_balance_qtys = $request->input('bulk_balance_qty');
    $bulk_mtl_issue_qtys = $request->input('bulk_mtl_issue_qty');
    $bulk_subinventory_ids = $request->input('bulk_subinventory_id');
    $bulk_locator_ids = $request->input('bulk_locator_id');
    $bulk_issueqtys = $request->input('bulk_issueqty');
    $bulk_batchnumbers = $request->input('bulk_batchnumber');
    $bulk_comments = $request->input('bulk_comments');
    $bulk_recive_qtys = $request->input('bulk_receive_qty');   
    $bulk_recived_qtys = $request->input('bulk_receiveqty');
    $bulk_job_qtys= $request->input('job_qty');
  //  $bulk_process_level = $request->input('process_level');
  //  $bulk_process_name= $request->input('process_name');

    $hdr_id  =\DB::Select("select w_materialissue_hdr_id from w_materialissue_hdr_t where w_jobs_hdr_id='$job_id'");
    $matissue_hdr_id = $hdr_id[0]->w_materialissue_hdr_id;

    foreach ($bulk_product_ids as $index => $product_id) {
        // Check if the record exists
        $exists = \DB::table('w_materialreceive_line_t')
            ->where('w_materialreceive_hdr_id', $materialreceive_hdr_id)
            ->where('product_id', $product_id)
            ->exists();
    
    if ($exists) {
        // Fetch the existing record
        $existingRecord = \DB::table('w_materialreceive_line_t')
            ->where('w_materialreceive_hdr_id', $materialreceive_hdr_id)
            ->where('product_id', $product_id)
            ->first();

    // Get new values from request
    $new_subinventory_id = $bulk_subinventory_ids[$index] ?? null;
    $new_locator_id = $bulk_locator_ids[$index] ?? null;
    $new_batchnumber = $bulk_batchnumbers[$index] ?? null;
    $new_issueqty = $bulk_recived_qtys[$index] ?? null;

    $mtlln=\DB::Select("select * from w_materialissue_line_t where product_id='$product_id' and w_materialissue_hdr_id='$matissue_hdr_id'");

    foreach($mtlln as $k => $v){
        DB::table('w_materialissue_line_t')->where('product_id',$v->product_id)->where('w_materialissue_hdr_id',$v->w_materialissue_hdr_id)->update(['receive_qty' => $v->issueqty,'receive_status' => 1]);
    }

    // Update the record with concatenated values (allowing duplicates)
    \DB::table('w_materialreceive_line_t')
        ->where('w_materialreceive_hdr_id', $materialreceive_hdr_id)
        ->where('product_id', $product_id)
        ->update([
            'subinventory_id' => !empty($new_subinventory_id) ? 
                ($existingRecord->subinventory_id ? $existingRecord->subinventory_id . ',' . $new_subinventory_id : $new_subinventory_id) : 
                $existingRecord->subinventory_id,

            'locator_id' => !empty($new_locator_id) ? 
                ($existingRecord->locator_id ? $existingRecord->locator_id . ',' . $new_locator_id : $new_locator_id) : 
                $existingRecord->locator_id,

            'batchnumber' => !empty($new_batchnumber) ? 
                ($existingRecord->batchnumber ? $existingRecord->batchnumber . ',' . $new_batchnumber : $new_batchnumber) : 
                $existingRecord->batchnumber,
                
           'receive_qty' => $existingRecord->receive_qty + $new_issueqty,
           'receiveqty' => $existingRecord->receiveqty + $new_issueqty,
           'last_updated_by' => \Session::get('id'),
           'updated_at' => date('Y-m-d'),
        ]);
        }else {
            
            $nextLineNo = \DB::table('w_materialreceive_line_t')
            ->where('w_materialreceive_hdr_id', $materialreceive_hdr_id)
            ->max('line_no'); 

            $mtlln=\DB::Select("select * from w_materialissue_line_t where product_id='$product_id' and w_materialissue_hdr_id='$matissue_hdr_id'");

            foreach($mtlln as $k => $v){
                DB::table('w_materialissue_line_t')->where('product_id',$v->product_id)->where('w_materialissue_hdr_id',$v->w_materialissue_hdr_id)->update(['receive_qty' => $v->issueqty,'receive_status' => 1]);
            }
             $nextLineNo = $nextLineNo ? $nextLineNo + 1 : 1;

            // Insert new row with all columns
            \DB::table('w_materialreceive_line_t')->insert([
                'w_materialreceive_hdr_id' => $materialreceive_hdr_id,
                'product_id' => $product_id,
                'uom_code_id' => $bulk_uom_code_ids[$index] ?? null,
                'qty' => $bulk_qtys[$index] ?? 0,
                'issue_qty' => $bulk_issue_qtys[$index] ?? 0,
                'mtl_issue_qty' => $bulk_mtl_issue_qtys[$index] ?? 0,
                'subinventory_id' => $bulk_subinventory_ids[$index] ?? null,
                'locator_id' => $bulk_locator_ids[$index] ?? null,
                'issueqty' => $bulk_issueqtys[$index] ?? null,
                'receive_qty' => $bulk_recive_qtys[$index] ?? 0,
                'receiveqty' => $bulk_recived_qtys[$index] ?? 0,
                'batchnumber' => $bulk_batchnumbers[$index] ?? null,
                'line_no' => $nextLineNo, 
                'created_by' => \Session::get('id'),
                'created_at' => date('Y-m-d'),
                'organization_id' => \Session::get('organization'),
                'location_id' => \Session::get('location'),
                'company_id' => \Session::get('companyid'),
                'last_updated_by' => \Session::get('id'),
                'updated_at' => date('Y-m-d'),
                'job_qty' => $bulk_job_qtys[$index] ?? null,   
               // 'process_level' => $bulk_process_level[$index] ?? 0,
              //  'process_name' => $bulk_process_name[$index] ?? 0,
                'reference_source' => 'PACKINGMATERIALISSUE',
                'reference_source_hdr_id' => $matissue_hdr_id,
                'reference_source_line_id' => $mtlln[0]->w_materialissue_line_id,

            ]);
        }
    }
    DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id',$_POST['w_jobs_hdr_id'])->update(['job_status' => 'MATERIAL RECEIVED']);
    return response()->json(array('status' => 'success', 'message' => 'Material RE RECEIVED Successfully'));

    }

  /*deepika purpose: subinventory receive details based on material issues*/
	public function prdsubinventoryreceivedetails($index1=null,$pid=null){
		$html="";
		$product= $pid;
		$index=$index1;
		if(isset($_GET)){
			
			$jobid= $_GET['jobid'];
			if($_GET['src']=='mtlissue'){
			$mtlline=$_GET['refsrcline'];
				$this->data['mtl_receive_date']=date("d-m-Y H:i:s");
			 $mtlhdr=\DB::table('w_materialissue_hdr_t')->where('w_jobs_hdr_id',$jobid)->get();
				if(isset($mtlhdr)){
					$mtlhdrid="";
				foreach($mtlhdr as $k=>$v){
					$mtlhdrid.=$v->w_materialissue_hdr_id.",";
				}
					$mtlhdrid1=rtrim($mtlhdrid,",");
				}
				
				$group=\DB::table('m_products_t')->select('m_products_t.*','m_product_groups_t.*')->leftjoin('m_product_groups_t','m_product_groups_t.product_group_id','=','m_products_t.product_group_id')->where('m_products_t.product_id',$product)->get();
		if($group[0]->group_name!="FINISHED GOODS"){
			
				$sql1=\DB::Select("select product_id,w_materialissue_hdr_id,subinventory_id,locator_id,issueqty,batchnumber from w_materialissue_line_t where product_id=".$product." and  w_materialissue_hdr_id in(".$mtlhdrid1.")");
		
		}else{
			 $table = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id',$jobid)->get();
			 $job_hdr_id=$jobid;
			if(($table[0]->bom_process!="") && ($table[0]->bom_process!='0')){
					 if($table[0]->bom_process=="PROCESS-2"){
						 	$jobprs=\DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-1' and product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." order by w_jobs_hdr_id desc");
						}else if($table[0]->bom_process=="PROCESS-3"){
						 $jobprs=\DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-2' and product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." order by w_jobs_hdr_id desc");
						}else if($table[0]->bom_process=="PROCESS-4"){
							 	 $jobprs=\DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-3' and product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." order by w_jobs_hdr_id desc");
						}else if($table[0]->bom_process=="FINALPROCESS"){
						 	 $jobprs=\DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-4' and product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." order by w_jobs_hdr_id desc");
						}else if($jobprs[0]->w_jobs_hdr_id==null){
                    $job=\DB::select("select * from w_jobcard_hdr_t where product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." group by bom_process order by w_jobs_hdr_id desc");    
                $jobprs=\DB::select("select * from w_jobcard_hdr_t where bom_process='".$job[0]->bom_process."' and product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." order by w_jobs_hdr_id desc");   
                
                    }
						else{
						 $jobprs=array();
						  $job_hdr_id=0;
						  
					 }
						if(count($jobprs)>0){
						 $job_hdr_id=$jobprs[0]->w_jobs_hdr_id;
					}else{
								  $job_hdr_id=0;
					}
					
					}
					
					$sql1=\DB::table('w_qa_submitstage_trx_t')->where('job_no',$job_hdr_id)->where('product_id',$product)->select('job_no','batch_no as batchnumber','product_id','production_qty as issueqty','subinventory_id','sublocator_id as locator_id')->get();
					
					if(count($sql1) > 0){
					   $sql1=\DB::table('w_qa_submitstage_trx_t')->where('job_no',$job_hdr_id)->where('product_id',$product)->select('job_no','batch_no as batchnumber','product_id','production_qty as issueqty','subinventory_id','sublocator_id as locator_id')->get(); 
					}else{
					
					$jobid= $_GET['jobid'];
					$mtlhdr=\DB::table('w_materialissue_hdr_t')->where('w_jobs_hdr_id',$jobid)->get();
				if(isset($mtlhdr)){
					$mtlhdrid="";
				foreach($mtlhdr as $k=>$v){
					$mtlhdrid.=$v->w_materialissue_hdr_id.",";
				}
					$mtlhdrid1=rtrim($mtlhdrid,",");
				}
				
			$sql1=\DB::Select("select product_id,w_materialissue_hdr_id,subinventory_id,locator_id,issueqty,batchnumber from w_materialissue_line_t where product_id=".$product." and  w_materialissue_hdr_id in(".$mtlhdrid1.")");	
					}
	}
	
	$bno=array();
	$batch_data=array();			
	$issueqty=array();
		foreach($sql1 as $key=>$value){
			
		$bno=explode(",",$value->batchnumber);
		$issueqty=explode(",",$value->issueqty);
		foreach($bno as $k=>$v)
		{
		if(isset($batch_data[$v]))
		{
			$batch_data[$v]=$batch_data[$v]+$issueqty[$k];
			
		}
			else
			{
			$batch_data[$v]=$issueqty[$k];	
			}
			
		}
			
	}
				$batch_data1=array();
		foreach($batch_data as $k=>$v)
		{
			$batch_data1[]=$v;
		}
	$subinv=explode(",",$sql1[0]->subinventory_id);
	$subloc=explode(",",$sql1[0]->locator_id);
	$issqty=explode(",",$sql1[0]->issueqty);
	$bno=explode(",",$sql1[0]->batchnumber);
		//dd($sql1);
			$html.= "<table class='sub_class' style='display:block;width:100%;overflow-y:auto;'>";
	    $html .= "<thead style='background:#00224e;color:#FFF'><th >S.No</th><th >Lot Number</th><th style='display:none;'>Subinventory</th><th style='display:none;'>Locator</th><th style='width:150px;display:none;'>Qoh</th><th style=''>Qty</th><th style=''>Receive Qty</th><th></th></thead><tbody  class='subinv_class_body'>";
		 $subid="";
		 if(!empty($subinv)){
		 foreach($subinv as $k=>$v){
			
		 
	
		$sub = $this->jCombo('m_subinventory_t','subinventory_id','subinventory_name',$v);
		$loc=$this->jCombo('m_sublocators_t','sublocator_id','locator_code',$subloc[$k]);
		$batch=$this->jcustomselectcomp('i_qoh_detail_t','batch_number','batch_number',$bno[$k],'and product_id='.$product.' and qoh_trx_qty>0 and batch_number!=""');
		$html .="<tr class='subinv_clone rcopy1'>";
		$html .="<td style='padding:2px'><input type='hidden' class='product' value=".$product." ><input type='hidden' class='index1' value=".$index." ><input type='text' class='form-control line_no' value='1'></td>
		<td class='batchno' style='padding:2px;pointer-events:none;'><select class='form-control batch_number'>".$batch."</select></td>";
	    $html .="<td style='padding:2px;pointer-events:none;display:none;'>
    	<select class='form-control subinventory_id' >".$sub."</select>
			
		</td>";
		 $html .="<td style='padding:2px;pointer-events:none;display:none;'>
	    <select class='form-control locator_id' >".$loc."</select>
			
			</td>";
				$html .="<td style='padding:2px;display:none;'><input type='hidden' class='form-control qoh' value='' readonly></td>";
				
				$html .="<td style='padding:2px;pointer-events:none;' class='mtlissqty'><input type='text' class='form-control mtlqty' value=".$batch_data1[$k].">
				</td> 
				<td style='padding:2px;' class='mtlrcvqty'><input type='text' class='form-control receiveqty' value=".$batch_data1[$k]."></td>";
				/**<td> <a class='subinvremove subinvremove0'><i class='fa btn-xs fa-2x fa-minus-circle rem' aria-hidden='true'></i></a>
                    <input type='hidden'>
                </td>";*/
			$html .="</tr>";
	
		
		}
		 }
			$html .="</tbody></table>";
			}else{
				$product= $product;
				$grp=$this->groupname($product);
				$comp=\Session::get('companyid');
				$sql=array();
				if($grp!=0 || $grp!=""){
					if($grp=='SEMI FINISHED GOODS'){
						$sql=\DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh   from i_qoh_detail_t where product_id=".$product." and batch_number!='' and qualitystatus=1 and company_id=".$comp." group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0" );      
        //	$sql=\DB::select("select * from i_qoh_detail_t where product_id=".$product."  and qoh_trx_qty>0 and batch_number!='' and qualitystatus=1 and company_id=$comp");
					}else if($grp=='RAW MATERIALS' || $grp=='PACKING MATERIALS' || $grp=='FINISHED GOODS'){
					//$sql=\DB::select("select * from i_qoh_detail_t where product_id=".$product."  and qoh_trx_qty>0 and batch_number!='' and company_id=$comp");
					$sql=\DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh   from i_qoh_detail_t where product_id=".$product." and batch_number!=''  and company_id=".$comp." group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0" );      
                
					}
				}
				
									
		if(count($sql)>0){
		
			$html.= "<table class='sub_class' style='display:block;width:100%;overflow-y:auto;'>";
	    $html .= "<thead style='background:#00224e;color:#FFF'><th >S.No</th><th >Batch Number</th><th >Subinventory</th><th >Locator</th><th style='width:150px;'>Qoh</th><th >Receive Qty</th><th></th></thead><tbody  class='subinv_class_body'>";
		 $subid="";
		 $batchno="";
		 if(!empty($sql)){
		 foreach($sql as $k=>$v){
			 $subid .=$v->subinventory_id.",";
			 $batchno .="'".$v->batch_number."',";
		 }
		 }
		 $subinv_id=rtrim($subid,",");
		 $batchno1=rtrim($batchno,",");
		 $str = implode(',',array_unique(explode(',', $subinv_id)));
		 $bno1 = implode(',',array_unique(explode(',', $batchno1)));
		$sub = $this->jcustomselect('m_subinventory_t','subinventory_id','subinventory_name','','and subinventory_id in('.$str.')');
			  $loc=$this->jcustomselect('m_sublocators_t','sublocator_id','locator_code','',' and subinventory_id=5');
			   if($_GET['src1']=="PACKINGMATERIALRECEIVE" && $_GET['src']=="mtlreceive"){
			   $batch=$this->jcustomselectcomp1('i_qoh_detail_t','batch_number','batch_number','','and product_id='.$product.' and batch_number in('.$bno1.')  and subinventory_id=5','batch_number,subinventory_id');
			   }else{
				 $batch=$this->jcustomselectcomp1('i_qoh_detail_t','batch_number','batch_number','','and product_id='.$product.' and batch_number in('.$bno1.')  and subinventory_id=5','batch_number,subinventory_id');   
			   }
		  $html .="<tr class='subinv_clone rcopy1'>";
				$html .="<td style='padding:2px'><input type='hidden' class='product' value=".$product." ><input type='hidden' class='index1' value=".$index." ><input type='text' class='form-control line_no' value='1' ></td>
				<td class='batchno'><select class='form-control batch_number'>".$batch."</select></td>
				
				";
			$html .="<td>
	<select class='form-control subinventory_id' >".$sub."</select>
			
			</td>";
		 $html .="<td style='padding:2px;'>
	<select class='form-control locator_id'>".$loc."</select>
			
			</td>";
				$html .="<td style='padding:2px'><input style='width:100px;' type='text' class='form-control qoh' value='' readonly></td>";
				$html .="<td style='padding:2px' class='mtlrcvqty'><input type='text' class='form-control receiveqty' value='' ></td> <td> <a class='subinvremove subinvremove0'><i class='fa btn-xs fa-2x fa-minus-circle rem' aria-hidden='true'></i></a>
                    <input type='hidden'>
                </td>";
			$html .="</tr>";
		$html .="</tbody></table>";
		}
		 else{
			 $html.="No QOH Available";
		 }
				
			}
		
	}
		return $html;
}
	

public function groupname($id = null)
    {
	
		$group= \DB::table('m_products_t')->leftjoin('m_product_groups_t','m_product_groups_t.product_group_id','=','m_products_t.product_group_id')->where('m_products_t.product_id',$id)->get();
			if(count($group)>0)
		{
			$group_id=$group[0]->group_name;
			return $group_id;
		}
		else
		{
		return 0;
		}
	
	}
	public function getprcsdetails($pid=null,$fgpid=null){
	    $bomprocess=\DB::table('m_material_bom_hdr_t')->leftjoin('m_material_bom_lines_t','m_material_bom_lines_t.material_bom_hdr_id','=','m_material_bom_hdr_t.material_bom_hdr_id')->select('m_material_bom_lines_t.process_level','m_material_bom_lines_t.process_name')->where('m_material_bom_hdr_t.active','Yes')->where('m_material_bom_hdr_t.assembly_product_id',$fgpid)->where('m_material_bom_lines_t.component_product_id',$pid)->get();        
	$data=array();
	if(count($bomprocess)>0){
	  $data['process_level']=$bomprocess[0]->process_level;
	  $data['process_name']=$bomprocess[0]->process_name;
	  return $data;
	}else{
	    return 0;
	}
	    
	}

 
	public function matrerecivedetails($index1=null,$pid=null){
		$html="";
		$product= $pid;
		$index=$index1;
		if(isset($_GET)){
			
			$jobid= $_GET['jobid'];
			if($_GET['src']=='mtlissue'){
			$mtlline=$_GET['refsrcline'];
				$this->data['mtl_receive_date']=date("d-m-Y H:i:s");
			 $mtlhdr=\DB::table('w_materialissue_hdr_t')->where('w_jobs_hdr_id',$jobid)->get();
				if(isset($mtlhdr)){
					$mtlhdrid="";
				foreach($mtlhdr as $k=>$v){
					$mtlhdrid.=$v->w_materialissue_hdr_id.",";
				}
					$mtlhdrid1=rtrim($mtlhdrid,",");
				}
				
				$group=\DB::table('m_products_t')->select('m_products_t.*','m_product_groups_t.*')->leftjoin('m_product_groups_t','m_product_groups_t.product_group_id','=','m_products_t.product_group_id')->where('m_products_t.product_id',$product)->get();
		if($group[0]->group_name!="FINISHED GOODS"){
			
				$sql1=\DB::Select("select product_id,w_materialissue_hdr_id,subinventory_id,locator_id,issueqty,batchnumber from w_materialissue_line_t where product_id=".$product." and  w_materialissue_hdr_id in(".$mtlhdrid1.")");
		
		}else{
			 $table = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id',$jobid)->get();
			 $job_hdr_id=$jobid;
			if(($table[0]->bom_process!="") && ($table[0]->bom_process!='0')){
					 if($table[0]->bom_process=="PROCESS-2"){
						 	$jobprs=\DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-1' and product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." order by w_jobs_hdr_id desc");
						}else if($table[0]->bom_process=="PROCESS-3"){
						 $jobprs=\DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-2' and product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." order by w_jobs_hdr_id desc");
						}else if($table[0]->bom_process=="PROCESS-4"){
							 	 $jobprs=\DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-3' and product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." order by w_jobs_hdr_id desc");
						}else if($table[0]->bom_process=="FINALPROCESS"){
						 	 $jobprs=\DB::select("select * from w_jobcard_hdr_t where bom_process='PROCESS-4' and product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." order by w_jobs_hdr_id desc");
						}else if($jobprs[0]->w_jobs_hdr_id==null){
                    $job=\DB::select("select * from w_jobcard_hdr_t where product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." group by bom_process order by w_jobs_hdr_id desc");    
                $jobprs=\DB::select("select * from w_jobcard_hdr_t where bom_process='".$job[0]->bom_process."' and product_id=".$table[0]->product_id." and reference_source_id=".$table[0]->reference_source_id." order by w_jobs_hdr_id desc");   
                
                    }
						else{
						 $jobprs=array();
						  $job_hdr_id=0;
						  
					 }
						if(count($jobprs)>0){
						 $job_hdr_id=$jobprs[0]->w_jobs_hdr_id;
					}else{
								  $job_hdr_id=0;
					}
					
					}
					
					$sql1=\DB::table('w_qa_submitstage_trx_t')->where('job_no',$job_hdr_id)->where('product_id',$product)->select('job_no','batch_no as batchnumber','product_id','production_qty as issueqty','subinventory_id','sublocator_id as locator_id')->get();
					
					if(count($sql1) > 0){
					   $sql1=\DB::table('w_qa_submitstage_trx_t')->where('job_no',$job_hdr_id)->where('product_id',$product)->select('job_no','batch_no as batchnumber','product_id','production_qty as issueqty','subinventory_id','sublocator_id as locator_id')->get(); 
					}else{
					
					$jobid= $_GET['jobid'];
					$mtlhdr=\DB::table('w_materialissue_hdr_t')->where('w_jobs_hdr_id',$jobid)->get();
				if(isset($mtlhdr)){
					$mtlhdrid="";
				foreach($mtlhdr as $k=>$v){
					$mtlhdrid.=$v->w_materialissue_hdr_id.",";
				}
					$mtlhdrid1=rtrim($mtlhdrid,",");
				}
			
			$sql1=\DB::Select("select product_id,w_materialissue_hdr_id,subinventory_id,locator_id,issueqty,batchnumber from w_materialissue_line_t where product_id=".$product." and  w_materialissue_hdr_id in(".$mtlhdrid1.")");	
					}
	}

	$bno=array();
	$batch_data=array();			
	$issueqty=array();
		foreach($sql1 as $key=>$value){
			
		$bno=explode(",",$value->batchnumber);
		$issueqty=explode(",",$value->issueqty);
		foreach($bno as $k=>$v)
		{
		if(isset($batch_data[$v]))
		{
			$batch_data[$v]=$batch_data[$v]+$issueqty[$k];
			
		}
			else
			{
			$batch_data[$v]=$issueqty[$k];	
			}
			
		}
			
	}
		
		$batch_data1=array();
		foreach($batch_data as $k=>$v)
		{
			$batch_data1[]=$v;
		}
		
	$subinv=explode(",",$sql1[0]->subinventory_id);
	$subloc=explode(",",$sql1[0]->locator_id);
	$issqty=explode(",",$sql1[0]->issueqty);
	$bno=explode(",",$sql1[0]->batchnumber);
		//dd($sql1);
			$html.= "<table class='sub_class' style='display:block;width:100%;overflow-y:auto;'>";
	    $html .= "<thead style='background:#00224e;color:#FFF'><th >S.No</th><th >Lot Number</th><th style='display:none;'>Subinventory</th><th style='display:none;'>Locator</th><th style='width:150px;display:none;'>Qoh</th><th style=''>Qty</th><th style=''>Receive Qty</th><th></th></thead><tbody  class='subinv_class_body'>";
		 $subid="";
		 if(!empty($subinv)){
		 foreach($subinv as $k=>$v){
			
		 
	
		$sub = $this->jCombo('m_subinventory_t','subinventory_id','subinventory_name',$v);
		$loc=$this->jCombo('m_sublocators_t','sublocator_id','locator_code',$subloc[$k]);
		$batch=$this->jcustomselectcomp('i_qoh_detail_t','batch_number','batch_number',$bno[$k],'and product_id='.$product.' and qoh_trx_qty>0 and batch_number!=""');
		$html .="<tr class='subinv_clone rcopy1'>";
		$html .="<td style='padding:2px'><input type='hidden' class='product' value=".$product." ><input type='hidden' class='index1' value=".$index." ><input type='text' class='form-control line_no' value='1'></td>
		<td class='batchno' style='padding:2px;pointer-events:none;'><select class='form-control batch_number'>".$batch."</select></td>";
	    $html .="<td style='padding:2px;pointer-events:none;display:none;'>
    	<select class='form-control subinventory_id' >".$sub."</select>
			
		</td>";
		 $html .="<td style='padding:2px;pointer-events:none;display:none;'>
	    <select class='form-control locator_id' >".$loc."</select>
			
			</td>";
				$html .="<td style='padding:2px;display:none;'><input type='hidden' class='form-control qoh' value='' readonly></td>";
				$value = isset($batch_data1[$k]) ? $batch_data1[$k] : 0;
				$html .="<td style='padding:2px;pointer-events:none;' class='mtlissqty'><input type='text' class='form-control mtlqty' value=".$value.">
				</td> 
				<td style='padding:2px;' class='mtlrcvqty'><input type='text' class='form-control receiveqty' value=".$value."></td>";
				/**<td> <a class='subinvremove subinvremove0'><i class='fa btn-xs fa-2x fa-minus-circle rem' aria-hidden='true'></i></a>
                    <input type='hidden'>
                </td>";*/
			$html .="</tr>";
	
		
		}
		 }
			$html .="</tbody></table>";
			}else{
				$product= $product;
				$grp=$this->groupname($product);
				$comp=\Session::get('companyid');
				$sql=array();
				if($grp!=0 || $grp!=""){
					if($grp=='SEMI FINISHED GOODS'){
						$sql=\DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh   from i_qoh_detail_t where product_id=".$product." and batch_number!='' and qualitystatus=1 and company_id=".$comp." group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0" );      
        //	$sql=\DB::select("select * from i_qoh_detail_t where product_id=".$product."  and qoh_trx_qty>0 and batch_number!='' and qualitystatus=1 and company_id=$comp");
					}else if($grp=='RAW MATERIALS' || $grp=='PACKING MATERIALS' || $grp=='FINISHED GOODS'){
					//$sql=\DB::select("select * from i_qoh_detail_t where product_id=".$product."  and qoh_trx_qty>0 and batch_number!='' and company_id=$comp");
					$sql=\DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh   from i_qoh_detail_t where product_id=".$product." and batch_number!=''  and company_id=".$comp." group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0" );      
                
					}
				}
				
									
		if(count($sql)>0){
		
			$html.= "<table class='sub_class' style='display:block;width:100%;overflow-y:auto;'>";
	    $html .= "<thead style='background:#00224e;color:#FFF'><th >S.No</th><th >Batch Number</th><th >Subinventory</th><th >Locator</th><th style='width:150px;'>Qoh</th><th >Receive Qty</th><th></th></thead><tbody  class='subinv_class_body'>";
		 $subid="";
		 $batchno="";
		 if(!empty($sql)){
		 foreach($sql as $k=>$v){
			 $subid .=$v->subinventory_id.",";
			 $batchno .="'".$v->batch_number."',";
		 }
		 }
		 $subinv_id=rtrim($subid,",");
		 $batchno1=rtrim($batchno,",");
		 $str = implode(',',array_unique(explode(',', $subinv_id)));
		 $bno1 = implode(',',array_unique(explode(',', $batchno1)));
		$sub = $this->jcustomselect('m_subinventory_t','subinventory_id','subinventory_name','','and subinventory_id in('.$str.')');
			  $loc=$this->jcustomselect('m_sublocators_t','sublocator_id','locator_code','',' and subinventory_id=5');
			   if($_GET['src1']=="PACKINGMATERIALRECEIVE" && $_GET['src']=="mtlreceive"){
			   $batch=$this->jcustomselectcomp1('i_qoh_detail_t','batch_number','batch_number','','and product_id='.$product.' and batch_number in('.$bno1.')  and subinventory_id=5','batch_number,subinventory_id');
			   }else{
				 $batch=$this->jcustomselectcomp1('i_qoh_detail_t','batch_number','batch_number','','and product_id='.$product.' and batch_number in('.$bno1.')  and subinventory_id=5','batch_number,subinventory_id');   
			   }
		  $html .="<tr class='subinv_clone rcopy1'>";
				$html .="<td style='padding:2px'><input type='hidden' class='product' value=".$product." ><input type='hidden' class='index1' value=".$index." ><input type='text' class='form-control line_no' value='1' ></td>
				<td class='batchno'><select class='form-control batch_number'>".$batch."</select></td>
				
				";
			$html .="<td>
	<select class='form-control subinventory_id' >".$sub."</select>
			
			</td>";
		 $html .="<td style='padding:2px;'>
	<select class='form-control locator_id'>".$loc."</select>
			
			</td>";
				$html .="<td style='padding:2px'><input style='width:100px;' type='text' class='form-control qoh' value='' readonly></td>";
				$html .="<td style='padding:2px' class='mtlrcvqty'><input type='text' class='form-control receiveqty' value='' ></td> <td> <a class='subinvremove subinvremove0'><i class='fa btn-xs fa-2x fa-minus-circle rem' aria-hidden='true'></i></a>
                    <input type='hidden'>
                </td>";
			$html .="</tr>";
		$html .="</tbody></table>";
		}
		 else{
			 $html.="No QOH Available";
		 }
				
			}
		
	}
		return $html;
 }  
    
    
}
