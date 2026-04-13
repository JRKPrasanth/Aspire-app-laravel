<?php

namespace App\Http\Controllers;
use App\Batchconversion;
use Illuminate\Http\Request;
use Session;
use DB;
use Yajra\DataTables\DataTables;

class VerdurabatchconversionController extends Controller
{
    public $module="Batchconversion";

		public function __construct()
	{
		$this->data=array();
		$this->model 	= new Batchconversion();
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
		$this->data['pageModule']='Batchconversion';
		$this->table="i_batch_conversion_t";
		$this->middleware('auth');
        $this->data['urlmenu']=$this->indexs(); 

	}
	
    	// create
	    public function create(Request $request,$id=null)
        {
  
            if(isset($_GET['approval'])){
	    	$id = $_GET['approval'];
	       	$this->data['id'] = $id;
	
			 $table = \DB::table('i_batch_conversion_t')->where('id',$id)->get();
             $this->data['row'] = $table[0];
	         $this->data['batch_number']= $this->jcustomselect('i_batch_conversion_t','from_batch','from_batch',$table[0]->from_batch," and from_batch='".$table[0]->from_batch."'");
	         $this->data['to_batch_number']= $this->jcustomselect('i_batch_conversion_t','to_batch','to_batch',$table[0]->to_batch," and to_batch='".$table[0]->to_batch."'");
		     $this->data['subinventory_id']=$this->jcustomselect('m_subinventory_t','subinventory_id','subinventory_name',$table[0]->from_subinventory," and subinventory_id='".$table[0]->from_subinventory."'");
			 $this->data['qoh'] =$table[0]->qoh;
			 $this->data['type'] =$table[0]->type;
			 $this->data['qty'] =$table[0]->qty;
			 $this->data['conversion_date'] =$table[0]->convert_date;
			 $this->data['con_id'] =$table[0]->id;
			 $this->data['conversion_number'] =$table[0]->bc_number;
			 $this->data['sublocator_id']=$this->jcustomselect('m_sublocators_t','sublocator_id','locator_code',$table[0]->from_locator," and sublocator_id='".$table[0]->from_locator."'");
             $this->data['product_id']= $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$table[0]->product_id," and product_id='".$table[0]->product_id."'");

       
        }else{
		
             $this->data['product_id']= $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',''," and (product_group_id='2' or product_group_id='3')");

            // $this->data['batch_number']= $this->jcustomselect('i_qoh_detail_t','batch_number','batch_number','',' and qoh_trx_qty>0');
           	 $this->data['subinventory_id']=$this->jcustomselect('m_subinventory_t','subinventory_id','subinventory_name',''," and subinventory_id='6'");
			 $this->data['sublocator_id']=$this->jcustomselect('m_sublocators_t','sublocator_id','locator_code',''," and subinventory_id='6'");
             $seqno=$this->Seqnoe('BC','i_batch_conversion_t','','id');
             $this->data['conversion_number'] = $seqno[0];
             $this->data['conversion_date'] = date('Y-m-d');
             $this->data['qoh'] ='';
			 $this->data['qty'] ='';
			 $this->data['c_date'] ='';
			 $this->data['c_num'] ='';
			 
    }
    
	    	return view('verdurabatchconversion.form',$this->data);
	    	
     }

 
        public function save(Request $request)
        
         {
             
           $batch_conversion = new Batchconversion();

            $t=1;

            $approverid= $this->Approvaldatacheck('batch_conversion',$t);
              //	dd($approverid);
	        if($approverid == "0" ){
			
	        	 $data['approver_id']=\Session::get('id');
	        }else{
				
	        	$data['approver_id']=$approverid;

	        } 
	        
	       // dd($data['approver_id']);
	        
        $batch_conversion->convert_date = date('Y-m-d',strtotime($request->input('conversion_date')));
        //dd($batch_conversion->convert_date );
        $batch_conversion->bc_number = $request->input('conversion_number');
        $batch_conversion->product_id = $request->input('product_id');
        $batch_conversion->type = $request->input('type');
        $batch_conversion->from_batch = $request->input('batch_number');
        $batch_conversion->from_subinventory = $request->input('from_inv');
        $batch_conversion->from_locator = $request->input('from_loc');
        $batch_conversion->qty = $request->input('from_qty');
        $batch_conversion->from_qty = $request->input('frompack_qty');
        $batch_conversion->to_qty = $request->input('to_qty');
        $batch_conversion->qoh = $request->input('from_qoh');
        $batch_conversion->to_batch = $request->input('to_batch');
        $batch_conversion->to_subinventory = $request->input('to_inv');
        $batch_conversion->to_locator = $request->input('to_loc');
        $batch_conversion->status = "INITIATED";
        $batch_conversion->active = "Yes";
        $batch_conversion->approver_id = $data['approver_id'];
        $batch_conversion->created_by = \Session::get('id');
        $batch_conversion->last_updated_by = \Session::get('id');
        $batch_conversion['updated_at'] = date('Y-m-d h:s:i');
        $batch_conversion['created_at'] = date('Y-m-d h:s:i');
        $batch_conversion->organization_id = \Session::get('organization');
        $batch_conversion->location_id = \Session::get('location');
        $batch_conversion->company_id = \Session::get('companyid');

        $batch_conversion->save();
        return response()->json(array('status' => 'success', 'message' => 'Batch Converted Successfully!!'));
        
 }

// approval purpose

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

    	$this->data['pageMethod']=\Request::Route()->getName();
    	//dd($this->data['pageMethod']);

       return view('verdurabatchconversion.table',$this->data);
    }

/*end*/
		/* purpose:to display data in jqgrid function */
 	   public function getconversionData(Batchconversion $Batchconversion)   
     {
		 
         $wh='';
         $wh1='';

    $app_id=\Session::get('id');
   $wh1=" and i_batch_conversion_t.status='INITIATED' and json_contains(i_batch_conversion_t.approver_id ,'".$app_id."')=1 ";

        $loc=\Session::get('location');
        $compy=\Session::get('companyid');      
        $groupname=\Session::get('groupname');
        if($groupname=='1' || $groupname=='Admin'){
        $wh.='and  i_batch_conversion_t.company_id='.$compy;  
        }else{
            $wh.='and  i_batch_conversion_t.company_id='.$compy.' and i_batch_conversion_t.location_id='.$loc;      
        }   

		

    	$sql = "SELECT
        i_batch_conversion_t.*,
        tb_users.username,
        tb_users.first_name,
        m_subinventory_t.subinventory_name, 
        m_sublocators_t.locator_name
    FROM
        i_batch_conversion_t
    LEFT JOIN tb_users ON
        (
            tb_users.id = i_batch_conversion_t.created_by
        )
    
    LEFT JOIN m_subinventory_t ON i_batch_conversion_t.from_subinventory = m_subinventory_t.subinventory_id
    LEFT JOIN m_sublocators_t ON i_batch_conversion_t.from_locator = m_sublocators_t.sublocator_id
    WHERE
        1 = 1  $wh $wh1 GROUP BY i_batch_conversion_t.id ";

		$result = \DB::select($sql);

		   return DataTables::of($result)->make(true);
    }
	
	// approved function
    public function Approve(Request $request )
    
    {

          $id = $_POST['con_id'];
          $bc_num = $_POST['conversion_number'];
          $type = $_POST['type'];
          $from_loc = $_POST['from_loc'];
          $date = date('Y-m-d',strtotime($_POST['conversion_date']));
          $cur_date = date('Y-m-d');
          $from_inv = $_POST['from_inv'];
          $qoh = $_POST['from_qoh'];
          $to_batch = $_POST['to_batch'];
          $to_loc = $_POST['to_loc'];
          $to_inv = $_POST['to_inv'];
          $created_by = \Session::get('id');
          $updated_by = \Session::get('id');
          $updated_at = date('Y-m-d h:s:i');
          $created_at = date('Y-m-d h:s:i');
          $org_id = \Session::get('organization');
          $location_id = \Session::get('location');
          $company_id = \Session::get('companyid');
          $product_id = $_POST['product_id'];
          $from_batch = $_POST['batch_number'];
          $qty = $_POST['from_qty'];
          $qty1 = $qty * (-1);
        

            $uom = \DB::select("select trx_uom_id from m_products_t where product_id='$product_id'");
            $uom_id = $uom[0]->trx_uom_id;
            
            
            $group = \DB::select("select product_group_id from m_products_t where product_id='$product_id'");
            $group_id = $group[0]->product_group_id;
            
            $account = \DB::select("select account_code_id from m_products_t where product_id='$product_id'");
            $acc_id = $account[0]->account_code_id;
             
            $cost = \DB::select("select cost from i_qoh_detail_t where product_id='$product_id' and batch_number='$from_batch' and qoh_source='PURCHASE_STOREMOVE' order by qoh_detail_id desc limit 1");
            $cost_fg = $cost[0]->cost;
           // dd($cost_fg);
            
           // $manf_date = \DB::select("select manufacturer_date from i_qoh_detail_t where product_id='$product_id' and batch_number='$from_batch' and qoh_source='PURCHASE_STOREMOVE' order by qoh_detail_id desc limit 1");
            $manf_date_fg = "0000-00-00";
            
        //    $exp_date = \DB::select("select product_expire_date from i_qoh_detail_t where product_id='$product_id' and batch_number='$from_batch' and qoh_source='PURCHASE_STOREMOVE' order by qoh_detail_id desc limit 1");
            $exp_date_fg =  "0000-00-00";

         // dbt or crd amunt cal
          
          $amount = $qty * $cost_fg;
        
        // materal trax tbl insert

            $insert = \DB::insert("insert into m_material_trx_t(trx_source_type_id,trx_source_hdr_id,trx_source_line_id,trx_type_id,trx_action_id,
            line_number,product_id,subinventory_id,locator_id,trx_qty,trx_uom,trx_date,period_id,
            trx_reference,gl_codecombination_id,trx_cost,currency_code,project_id,created_by,created_at,
            last_updated_by,updated_at,company_id,location_id,organization_id)values('88','$id','0','0','0','0','$product_id','$from_inv','$from_loc',
            '$qty1','0','$date','0','0','0','0','0','0','$created_by','$created_at','$updated_by','$updated_at','$org_id','$location_id','$company_id')");
 
             $lastInsertId = \DB::getPdo()->lastInsertId();
            // qoh tbl insert 

            $insert_qoh1 = \DB::insert("insert into i_qoh_detail_t(product_id,qoh_trx_qty,qoh_trx_date,batch_number,qoh_source,qoh_source_id,subinventory_id,qoh_uom_code_id,product_expire_date,manufacturer_date,cost,create_trx_id,
            locator_id,created_by,created_at,last_updated_by,updated_at,company_id,location_id,organization_id)values('$product_id','$qty1','$date','$from_batch','VERDURA BATCH CONVERSION','$id','$from_inv','$uom_id','$exp_date_fg','$manf_date_fg','$cost_fg','$lastInsertId','$from_loc','$created_by','$created_at','$updated_by','$updated_at','$org_id','$location_id','$company_id')");


            $insert = \DB::insert("insert into m_material_trx_t(trx_source_type_id,trx_source_hdr_id,trx_source_line_id,trx_type_id,trx_action_id,
            line_number,product_id,subinventory_id,locator_id,trx_qty,trx_uom,trx_date,period_id,
            trx_reference,gl_codecombination_id,trx_cost,currency_code,project_id,created_by,created_at,
            last_updated_by,updated_at,company_id,location_id,organization_id)values('88','$id','0','0','0','0','$product_id','$to_inv','$to_loc',
            '$qty','0','$date','0','0','0','0','0','0','$created_by','$created_at','$updated_by','$updated_at','$org_id','$location_id','$company_id')");

            $lastInsertId1 = \DB::getPdo()->lastInsertId();

            $insert_qoh2 = \DB::insert("insert into i_qoh_detail_t(product_id,qoh_trx_qty,qoh_trx_date,batch_number,qoh_source,qoh_source_id,subinventory_id,qoh_uom_code_id,product_expire_date,manufacturer_date,cost,create_trx_id,
            locator_id,created_by,created_at,last_updated_by,updated_at,company_id,location_id,organization_id)values('$product_id','$qty','$date','$to_batch','VERDURA BATCH CONVERSION','$id','$to_inv','$uom_id','$exp_date_fg','$manf_date_fg','$cost_fg','$lastInsertId1','$to_loc','$created_by','$created_at','$updated_by','$updated_at','$org_id','$location_id','$company_id')");
            
            // journal hdr insert
            $insert_journal = \DB::insert("insert into f_journal_entry_t(journal_name,journal_date,journal_type,journal_reference,journal_status,
            created_by,created_at,
            last_updated_by,updated_at,company_id,location_id,organization_id)values('VERDURA BATCH CONVERSION -$bc_num','$cur_date','VERDURA BATCH CONVERSION',
           '$id','APPROVED','$created_by','$created_at','$updated_by','$updated_at','$company_id','$location_id','$org_id')");
 
             $last_jid = \DB::getPdo()->lastInsertId();
          // jornal line 1 insert
             $insert_journal = \DB::insert("insert into f_journal_entry_lines_t(journal_entry_id,journal_date,reference_source,reference_id,status,
            line_no,account_id,debit_amount,created_by,created_at,
            last_updated_by,updated_at,company_id,location_id,organization_id,product_qty,batch_number)values('$last_jid','$cur_date','PRODUCT',
           '$product_id','APPROVED','1','$acc_id','$amount','$created_by','$created_at','$updated_by','$updated_at','$company_id','$location_id','$org_id','$qty','$from_batch')");
            // jornal line 2 insert
            
            $insert_journal1 = \DB::insert("insert into f_journal_entry_lines_t(journal_entry_id,journal_date,reference_source,reference_id,status,
            line_no,account_id,credit_amount,created_by,created_at,
            last_updated_by,updated_at,company_id,location_id,organization_id,product_qty,batch_number)values('$last_jid','$cur_date','PRODUCT',
           '$product_id','APPROVED','2','$acc_id','$amount','$created_by','$created_at','$updated_by','$updated_at','$company_id','$location_id','$org_id','$qty','$to_batch')");
            
             DB::table('i_batch_conversion_t')->where('id',$id)->update(['status'=>'APPROVED']);
             return response()->json(array('status' => 'success', 'message' => 'Batch Converted Approved!!'));
 
         }



  }