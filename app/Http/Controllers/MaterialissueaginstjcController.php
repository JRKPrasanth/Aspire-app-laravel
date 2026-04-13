<?php
namespace App\Http\Controllers;
use App\materialissueline;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Config;
use session;
use Yajra\DataTables\DataTables;

    class MaterialissueaginstjcController extends Controller
    {
        
         public function __construct()
    {

            $this->data['urlmenu']=$this->indexs(); 
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['pageFormtype']='ajax';

    }
    
   public function index()
    {

         $this->data['pageurl']=$this->data['pageMethod'];
	     $this->data['subinventory_id'] =$this->jcustomselect('m_subinventory_t','subinventory_id','subinventory_name','5','and production_store="Yes"');
		 $this->data['locator_id']=$this->jcustomselect('m_sublocators_t','sublocator_id','locator_code','191',' and sublocator_id in(191,192)');

        return view('materialissueagstjc.table',$this->data);
    }
		

    /* purpose: to display jobcard data */   
     public function getmatissuejcData()   
    {
        $wh='';

        $loc="1";
        $compy=\Session::get('companyid');
        $org=\Session::get('organization'); 
        $groupname=\Session::get('groupname');


        $sql= "select * from(SELECT
    w_jobcard_hdr_t.w_jobs_hdr_id,
    w_jobcard_hdr_t.job_status  as job_status,
    w_jobcard_hdr_t.job_no,
    w_jobcard_hdr_t.job_date,
    w_jobcard_hdr_t.job_completion_date,
    w_jobcard_hdr_t.remarks,
    w_jobcard_hdr_t.job_adjusted_qty,
    w_jobcard_hdr_t.job_qty,
    w_jobcard_hdr_t.product_id,
    w_jobcard_hdr_t.reference_source_id,
w_jobcard_hdr_t.store_move_qty,
(select hr_employee_t.first_name from hr_employee_t where hr_employee_t.employee_id=w_jobcard_hdr_t.job_created_by) as first_name,
    w_jobcard_hdr_t.batch_no,
     m_products_t.concatenated_product,
     m_products_t.product_code,
    (select m_uom_codes_t.uom_code from m_uom_codes_t where m_uom_codes_t.uom_code_id= w_jobcard_hdr_t.uom_code_id) as uom_code,
    COALESCE(w_qa_submitstage_trx_t.production_qty,0) as production_qty,
	   (COALESCE(w_jobcard_hdr_t.job_adjusted_qty,0)-COALESCE(w_qa_submitstage_trx_t.production_qty,0)) as balancejob_qty,
    (select w_productionplan_hdr_t.plan_no from w_productionplan_hdr_t where w_productionplan_hdr_t.productionplan_hdr_id= w_jobcard_hdr_t.reference_source_id) as plan_no,
    w_jobcard_hdr_t.job_process,w_jobcard_hdr_t.company_id,w_jobcard_hdr_t.location_id
FROM
    w_jobcard_hdr_t
 JOIN m_products_t ON(
        w_jobcard_hdr_t.product_id = m_products_t.product_id
    )
    left join w_qa_submitstage_trx_t on(w_qa_submitstage_trx_t.job_no= w_jobcard_hdr_t.w_jobs_hdr_id)
        LEFT JOIN w_materialissue_hdr_t ON w_materialissue_hdr_t.w_jobs_hdr_id = w_jobcard_hdr_t.w_jobs_hdr_id
    LEFT JOIN w_materialissue_line_t ON w_materialissue_line_t.w_materialissue_hdr_id = w_materialissue_hdr_t.w_materialissue_hdr_id  
              
    where 1=1 and m_products_t.product_group_id='1' AND w_materialissue_hdr_t.reissue_status IS NULL AND  w_jobcard_hdr_t.job_status = 'MATERIAL RECEIVED' group by w_jobcard_hdr_t.w_jobs_hdr_id ORDER BY w_jobcard_hdr_t.w_jobs_hdr_id desc) as t1 $wh";


      $result = \DB::select($sql);
	  return DataTables::of($result)->make(true);
 }
 
 // reissue 
 
 
     public function create($id=null) {

        if(isset($id))
		{   

			$this->data['id'] = $id;

        	 $table = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id',$id)->get();
			 $this->data['organization_id']=$this->jcombo("m_organizations_t","organization_id","organization_name",\Session::get('organization')); 
			 $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$table[0]->uom_code_id);
			 $this->data['ass_product_id']=$this->jCombo('m_products_t','product_id','product_code|concatenated_product',$table[0]->product_id);
			 $this->data['group']=$this->groupname($table[0]->product_id);
		     $this->data['w_jobs_hdr_id']=$this->jCombocomp('w_jobcard_hdr_t','w_jobs_hdr_id','job_no',$table[0]->w_jobs_hdr_id);

		     $hdr_id = \DB::select("SELECT w_materialissue_hdr_id FROM w_materialissue_hdr_t WHERE w_jobs_hdr_id = ?", [$table[0]->w_jobs_hdr_id]);
		     $this->data['matissue_hdr_id'] = $hdr_id[0]->w_materialissue_hdr_id;
		      $this->data['source'] = $hdr_id[0]->w_materialissue_hdr_id;
		     //dd($this->data['matissue_hdr_id']);
			 $this->data['job_qty']=$table[0]->job_adjusted_qty;
             $this->data['batch_no']=$table[0]->batch_no;
		     $this->data['job_status']=$table[0]->job_status;
		 
			 date_default_timezone_set('Asia/Calcutta');
			 $this->data['mtl_issue_date']=date("d-m-Y H:i:s");
		
				$this->data['productid']= $this->jCombo('m_products_t','product_id','product_code|concatenated_product','');
				/*end*/
				
        	if($table[0]->bom_process=='0'){
	    
				$planlines=\DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$table[0]->reference_source_id)->where('parent_product',$table[0]->product_id)->get();
				}else{
				$planlines=\DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id',$table[0]->reference_source_id)->where('parent_product',$table[0]->product_id)->where('process_level',$table[0]->bom_process)->get();	
				}$this->data['process']=$planlines[0]->process_level;
	     	 $this->data['linedata'] = $planlines; 
			 if(count($this->data['linedata']) >= 1)
		    {
			foreach($this->data['linedata'] as $key => $value)
			{
			    $productid=$value->product_id;
				$sql2=\DB::select("SELECT w_materialissue_hdr_t.*,w_materialissue_line_t.*,sum(w_materialissue_line_t.mtl_issue_qty)as issuedqty FROM w_materialissue_hdr_t LEFT JOIN w_materialissue_line_t ON(w_materialissue_hdr_t.w_materialissue_hdr_id = w_materialissue_line_t.w_materialissue_hdr_id)  where w_materialissue_line_t.product_id=".$value->product_id." and  w_materialissue_hdr_t.w_jobs_hdr_id=".$table[0]->w_jobs_hdr_id." and w_materialissue_line_t.balance_qty != 0   GROUP BY w_materialissue_line_t.product_id");
		        $this->data['linedata'][$key]->product_id= $this->jCombo('m_products_t','product_id','product_code|concatenated_product',$value->product_id);
			    $this->data['linedata'][$key]->uom_code_id=$this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
				$this->data['linedata'][$key]->qty=$value->component_qty;
			    $this->data['linedata'][$key]->issue_qty=($value->component_qty)*($this->data['job_qty']);
            	if(count($sql2)>0){
					 $this->data['linedata'][$key]->balance_qty=($sql2[0]->issue_qty)-($sql2[0]->issuedqty);
					  $this->data['linedata'][$key]->issued_qty=$sql2[0]->issuedqty;
				}else{
					$this->data['linedata'][$key]->balance_qty=($value->component_qty)*($this->data['job_qty']);
					$this->data['linedata'][$key]->issued_qty=0;
				}
				if($productid==$table[0]->product_id){
            	       unset($this->data['linedata'][$key]);
                	   }
			}
		
		}
		
	 
	 }
	      return view("materialissueagstjc.form",$this->data);
	      
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
    
    
    /*Save Function*/
     public function save(Request $request)
    {
 //   dd("vikki");
    $matissue_hdr_id = $request->input('matissue_hdr_id');
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

    foreach ($bulk_product_ids as $index => $product_id) {
        // Check if the record exists
        $exists = \DB::table('w_materialissue_line_t')
            ->where('w_materialissue_hdr_id', $matissue_hdr_id)
            ->where('product_id', $product_id)
            ->exists();
    
    if ($exists) {
        // Fetch the existing record
        $existingRecord = \DB::table('w_materialissue_line_t')
            ->where('w_materialissue_hdr_id', $matissue_hdr_id)
            ->where('product_id', $product_id)
            ->first();

    // Get new values from request
    $new_subinventory_id = $bulk_subinventory_ids[$index] ?? null;
    $new_locator_id = $bulk_locator_ids[$index] ?? null;
    $new_batchnumber = $bulk_batchnumbers[$index] ?? null;
    $new_issueqty = $bulk_issueqtys[$index] ?? null;

    // Update the record with concatenated values (allowing duplicates)
    \DB::table('w_materialissue_line_t')
        ->where('w_materialissue_hdr_id', $matissue_hdr_id)
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

            'issueqty' => !empty($new_issueqty) ? 
                ($existingRecord->issueqty ? $existingRecord->issueqty . ',' . $new_issueqty : $new_issueqty) : 
                $existingRecord->issueqty,
            'mtl_issue_qty' => $existingRecord->mtl_issue_qty + $new_issueqty,    
            'receive_status' => '2',
            'last_updated_by' => \Session::get('id'),
            'updated_at' => date('Y-m-d'),
        ]);
        }else {
            
            $nextLineNo = \DB::table('w_materialissue_line_t')
            ->where('w_materialissue_hdr_id', $matissue_hdr_id)
            ->max('line_no'); // Get the highest existing line_no

        $nextLineNo = $nextLineNo ? $nextLineNo + 1 : 1;

            // Insert new row with all columns
            \DB::table('w_materialissue_line_t')->insert([
                'w_materialissue_hdr_id' => $matissue_hdr_id,
                'product_id' => $product_id,
                'uom_code_id' => $bulk_uom_code_ids[$index] ?? null,
                'qty' => $bulk_qtys[$index] ?? 0,
                'issue_qty' => $bulk_issue_qtys[$index] ?? 0,
                'issued_qty' => $bulk_issued_qtys[$index] ?? 0,
                'balance_qty' => $bulk_balance_qtys[$index] ?? 0,
                'mtl_issue_qty' => $bulk_mtl_issue_qtys[$index] ?? 0,
                'subinventory_id' => $bulk_subinventory_ids[$index] ?? null,
                'locator_id' => $bulk_locator_ids[$index] ?? null,
                'issueqty' => $bulk_issueqtys[$index] ?? null,
                'batchnumber' => $bulk_batchnumbers[$index] ?? null,
                'line_no' => $nextLineNo, 
                'comments' => $bulk_comments[$index] ?? null,
                'receive_status' => '2',
                 'created_by' => \Session::get('id'),
                 'created_at' => date('Y-m-d'),
                 'organization_id' => \Session::get('organization'),
                 'location_id' => \Session::get('location'),
                 'company_id' => \Session::get('companyid'),
                 'last_updated_by' => \Session::get('id'),
                 'updated_at' => date('Y-m-d'),
            ]);
        }
        
                    // Insert new row with all columns in qoh
            \DB::table('i_qoh_detail_t')->insert([
                
                'create_trx_id' => $matissue_hdr_id,
                'product_id' => $product_id,
                'qoh_uom_code_id' => $bulk_uom_code_ids[$index] ?? null,
                'subinventory_id' => $bulk_subinventory_ids[$index] ?? null,
                'locator_id' => $bulk_locator_ids[$index] ?? null,
                'qoh_trx_date' => date('Y-m-d'),
                'qoh_trx_qty' => $bulk_issueqtys[$index]*(-1) ?? null, 
                'qoh_source' => "MATERIAL ISSUE",
                'active' => "Yes",
                'qoh_source_id' => $matissue_hdr_id,
                'batch_number' => $bulk_batchnumbers[$index] ?? null,
                'job_id' => $job_id, 
                'created_by' => \Session::get('id'),
                'created_at' => date('Y-m-d'),
                'organization_id' => \Session::get('organization'),
                'location_id' => \Session::get('location'),
                'company_id' => \Session::get('companyid'),
                'last_updated_by' => \Session::get('id'),
                'updated_at' => date('Y-m-d'),

            ]);
            
    }
        DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id',$_POST['w_jobs_hdr_id'])->update(['job_status' => 'MATERIAL RE-ISSUED']);
    
                 // matirial issue  mail function start

                      $emp=\Session::get('id');
                      $prd_id = $_POST['product_id'];
                      $job_id = $_POST['w_jobs_hdr_id'];
                      $batch_no = $_POST['batch_no'];
                      $qty = $_POST['job_qty'];
                      $status = "RE-ISSUED";

                      $user_mail = \DB::select("select user_mail,first_name from tb_users where employee_id='$emp'");
                      if (!empty($user_mail) && !empty($user_mail[0]->user_mail)) {
                          $from_umail = $user_mail[0]->user_mail;
                      } else {
                          $from_umail = "aspire@jrkresearch.com";
                      }

                      $pro_name1 = \DB::SELECT("select concatenated_product from m_products_t where product_id='$prd_id'");
                      $created =\DB::SELECT("select job_no,job_date,job_created_by from w_jobcard_hdr_t where w_jobs_hdr_id ='$job_id'");
                      $created_at = $created[0]->job_created_by;
                      $job_no = $created[0]->job_no;
                      $job_date = $created[0]->job_date;
                      //dd($qty);
                      $created =\DB::SELECT("select job_no,job_date,job_created_by from w_jobcard_hdr_t where w_jobs_hdr_id ='$job_id'");
                      $created_at = $created[0]->job_created_by;
                      $job_no = $created[0]->job_no;
                      $job_date = $created[0]->job_date;
                      
                      $user_mail1 = \DB::select("select user_mail from tb_users where employee_id='$created_at' and active='Yes'");
                      if (!empty($user_mail1) && !empty($user_mail1[0]->user_mail)) {
                          $created_mail = $user_mail1[0]->user_mail;
                      } else {
                          
                          $created_mail = "aspire@jrkresearch.com";
                      }
      
                      $man_id = \DB::select("select reporting_manager from hr_employee_t where employee_id='$created_at'");
                      $managr_id = $man_id[0]->reporting_manager;
                      
                      $managr_mail = \DB::select("select user_mail from tb_users where employee_id ='$managr_id' and group_id !='15' and active='Yes'");
                     
                      if (!empty($managr_mail) && !empty($managr_mail[0]->user_mail)) {
                          $manr_mail = $managr_mail[0]->user_mail;
                      } else {
                          
                          $manr_mail = "aspire@jrkresearch.com";
                      }
                              

                      $from_mail = $from_umail;
                      $cr_mail = $created_mail;
                      $man_mail = $manr_mail;
                      $pro_name = $pro_name1[0]->concatenated_product;
                      $sub = "MATERIAL RE ISSUED FOR - $job_no ";
                      $to_mail = "$cr_mail,$man_mail";
                      $emp_name = $user_mail[0]->first_name;       
             
                      $msg = "<p>Dear Team,<br><br>The Request material has been Re issued,<br><br>Jobcard No - $job_no <br>Jobcard Date - $job_date <br>Batch No - $batch_no <br>Product Name - $pro_name<br>Qty - $qty<br>Status - RE-ISSUED<br><br>Regards, <br> $emp_name";

          if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
              Config::set('mail.username', \Session::get('user_email'));
              Config::set('mail.password', \Session::get('user_password'));
          }

          \Mail::send([], [], function ($message) use ($from_mail, $to_mail, $sub,$msg) {
              
              $message->from($from_mail)
                      ->to(explode(',', $to_mail))  
                      ->subject($sub)
                      ->setBody($msg, 'text/html');
          });
          
          // mail functrion end 
      DB::table('w_materialissue_hdr_t')->where('w_materialissue_hdr_id', $matissue_hdr_id)->update(['reissue_status' => 1]);      
    return response()->json(array('status' => 'success', 'message' => 'Material REIssued Successfully'));
}

// view function


	 public function show($id=null)
    {
        $data=\DB::table('w_materialissue_hdr_t')->leftjoin('w_jobcard_hdr_t','w_jobcard_hdr_t.w_jobs_hdr_id','=','w_materialissue_hdr_t.w_jobs_hdr_id')->leftjoin('m_products_t','m_products_t.product_id','=','w_materialissue_hdr_t.product_id')->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','w_materialissue_hdr_t.uom_code_id')->where('w_materialissue_hdr_t.w_materialissue_hdr_id',$id)->get();
        
          $this->data['job_no']=$data[0]->job_no; 
         $this->data['assembly_product']=$data[0]->product_code."-".$data[0]->concatenated_product; 
          $this->data['uom_code_id']=$data[0]->uom_code;  
          $this->data['job_date']=date(\Session::get('p_date_format'),strtotime($data[0]->job_date));  
           
          $this->data['job_qty']=$data[0]->job_qty; 
          $this->data['batch_no']=$data[0]->batch_no; 
          
          $this->data['job_status']=$data[0]->job_status;
          $this->data['job_process']=$data[0]->job_process;
          $this->data['mtl_issue_date']=date(\Session::get('p_date_format'),strtotime($data[0]->mtl_issue_date));   
          $this->data['job_created_by']=$this->idname("employee_number|first_name","hr_employee_t","employee_id",$data[0]->job_created_by);  
          $this->data['bom_product_id']=$this->idname("product_code|concatenated_product","m_products_t","product_id",$data[0]->bom_product_id);  
		  $assigned=explode(",",$data[0]->job_assigned_to);
		  $assingnedto="";
			foreach($assigned as $key1=>$value){
		   $assingnedto.=$this->idname("employee_number|first_name","hr_employee_t","employee_id",$value).",";
			}
		  $this->data['assigned_name']=rtrim($assingnedto,",");
		  $this->data['machine']=$this->idname("machine_name","w_machine_hdr_t","machine_hdr_id",$data[0]->machine_hdr_id);
          $this->data['machine_capacity']=$data[0]->machine_capacity;  
          $this->data['active']=$data[0]->active;  
         
          $this->data['remarks']=$data[0]->remarks;  

           $vlinesdata = \DB::table('w_materialissue_line_t')
    ->leftJoin('w_materialissue_hdr_t', 'w_materialissue_line_t.w_materialissue_hdr_id', '=', 'w_materialissue_hdr_t.w_materialissue_hdr_id')
    ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'w_materialissue_line_t.product_id')
    ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'w_materialissue_line_t.uom_code_id')
    ->leftJoin('m_sublocators_t', function ($join) {
        $join->on(\DB::raw('FIND_IN_SET(m_sublocators_t.sublocator_id, w_materialissue_line_t.locator_id)'), '>', \DB::raw('0'));
    })
    ->select(
        'w_materialissue_line_t.*',
        'm_products_t.*',
        'm_uom_codes_t.uom_code',
        \DB::raw('GROUP_CONCAT(m_sublocators_t.locator_code ORDER BY m_sublocators_t.sublocator_id SEPARATOR ", ") as locator_codes')
    )
    ->where('w_materialissue_hdr_t.w_materialissue_hdr_id', $id)
    ->groupBy('w_materialissue_line_t.w_materialissue_line_id') // Assuming line_id is the unique identifier for each line.
    ->get();

      $this->data['vlinesdata']=$vlinesdata;
		return view('materialissueagstjc.view',$this->data);
    }
    
    
    }

