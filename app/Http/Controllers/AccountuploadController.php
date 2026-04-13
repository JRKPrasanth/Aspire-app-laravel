<?php

namespace App\Http\Controllers;

use App\Openstock;
use Illuminate\Http\Request;
use Validator, Input, Redirect ; 
use Yajra\DataTables\DataTables;

class AccountuploadController extends Controller
{

    /*Jqgrid Function*/
    public function getAccountuploadData()
        {

            $wh='';  

            if(isset($_GET['batchname'])){
            if($_GET['batchname']!=""){
            $wh= " and batch_name like '" . $_GET['batchname'] . "'";   
            }} 
		

            $SQL = "SELECT * from f_account_upload_t where 1=1 $wh";
            $result = \DB::select($SQL);

			return DataTables::of($result)->make(true);
        }
	

        /*Index Function For Load Value for table*/
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

		   $batch = $type = '';
        $this->data['status'] = $this->data['message'] = '';
		        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND i_product_openstock_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
		if (isset($_GET['type'])) {
            $type = 'OPENSTOCK';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':$upload = $this->UploadValidation($batch, $type, $message);
					// dd($upload);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
                case 'load':$upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
			}
        }   

        $this->data['pageMethod']=\Request::route()->getName();
		$this->data['urlmenu']=$this->indexs(); 
		
        return view('accountupload.table', $this->data);
    }

    /*Create Function*/
    public function create($id=null)
    {
      	$openstock=Openstock::find($id);
	    $this->data['openstockdata']=$openstock;
	    if(isset($id)){
   
	    }else{
	    $openstocks=\DB::connection()->getSchemaBuilder()->getColumnListing("i_product_openstock_upload_t");
        $openstock=array();
		foreach($openstocks as $key=>$val)
		{
			$openstock[$val]="";
		}
		}
        $sql=\DB::select("select subinventory_id,subinventory_name from m_subinventory_t where subinventory_name='".$openstock['subinventory_name']."'");

        $this->data['item_name']=$this->jcombo("m_products_t","concatenated_product","concatenated_product",$openstock['item_name']);
        if($sql){
		  $this->data['subinventory_name']=$this->jcombo("m_subinventory_t","subinventory_id","subinventory_name",$sql[0]->subinventory_id);

        }
        else{
            $this->data['subinventory_name']=$this->jcombo("m_subinventory_t","subinventory_id","subinventory_name","");

        }
        
        $this->data['locator_code']=$this->jcombo("m_sublocators_t","locator_code","locator_code",$openstock['locator_code']);
		 
         
        return view('openstock.form',$this->data);
    }
/*End*/
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*Save Function*/
    public function save(Request $request)
    {

        $openstock=new Openstock();
           $_POST['batch_status']='UPLOADED';
		   $sub_id = $_POST['subinventory_name'];
           // dd($_POST);
           $sub_name = $_POST['subinventory_name'];
           $su_name_sql=\DB::select("select subinventory_name from m_subinventory_t where subinventory_id=$sub_name");
           $su_name=\DB::select("select subinventory_name from m_subinventory_t where subinventory_id=$sub_id");
			$openstock->item_name=$_POST['item_name'];
			$openstock->subinventory_name=$su_name_sql[0]->subinventory_name;
			$openstock->locator_code=$_POST['locator_code'];
			$openstock->qty=$_POST['qty'];
            $openstock->cost=$_POST['cost'];
			$openstock->batch_name=$_POST['batch_name'];
			$openstock->batch_date=$_POST['batch_date'];
			$openstock->batch_status= $_POST['batch_status'];
			$openstock->batch_comments='';
			$openstock->batch_number=$_POST['batch_name'];
			$id=$_POST['product_openstock_upload_id'];
            $_POST['subinventory_name']=$su_name[0]->subinventory_name;
            $action="Edit"; 
            /**Auditlog**/
            $this->auditlog($id,"openstockupload",$action,$_POST,"i_product_openstock_upload_t");
            // dd($_POST);
			Openstock::find($id)->update($_POST);
		  return redirect('openstockupload')->with('success','your data Updated successfully');
    }
/*End*/
    /**
     * Display the specified resource.
     *
     * @param  \App\Openstock  $openstock
     * @return \Illuminate\Http\Response
     */
    /*View Function*/
    public function show(Openstock $openstock,$id=null)
    {
		$this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("i_product_openstock_upload_t");
		$this->data['values'] = Openstock::find($id);
		
        return view('openstock.view',$this->data);
    }
/*End*/
    

/*deepika purpose:To Upload excel*/	
public function Uploadexcel(Request $request){  

        $path = $request->file('choosefile');
    //dd();
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$extension = $path->getClientOriginalExtension();
		// dd($path,$extension);
		$data =array();
        $return = 'openstockupload';
    
        if($extension == "csv"){
            
            $file = $request->file('choosefile');
           
    		$handle = fopen($file, "r");
            $c = 0;
             $stockdata=array();
            // dd(($filesop = fgetcsv($handle, 1000, ","))!== false);
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
		    {
               				if($c>0){
	       		    // dd($filesop);  
                    $stockdata[$c]['journal_name'] = trim($filesop[0]);
                    $stockdata[$c]['debit_amount'] = strtoupper(trim($filesop[1]));
                    $stockdata[$c]['credit_amount'] = strtoupper(trim($filesop[2]));
                    $stockdata[$c]['journal_date'] =  date("Y-m-d", strtotime($filesop[3])); 
                    $stockdata[$c]['batch_date'] = date('Y-m-d');
                    $stockdata[$c]['batch_status'] = "UPLOADED";
                    $stockdata[$c]['batch_name'] = $_POST['batch_name'];
                    
                      $id = \DB::table('f_account_upload_t')->insert($stockdata[$c]);
			       //insert record from csv 		
                }   
			// dd($c);
            
            $c = $c + 1;
            if($c==501)
            {
                return response()->json(array('status' => 'info', 'message' => 'Maximum 500 rows are Exceeded.Others will be skipped!!'));;
            }
		    }

            
            //dd($id);
        }else {
 return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }
         
    
  return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));
         
         
}     
/*validation for Open stock Upload*/	
function uploadValidation($valModname, $valBatch, $status) {



     
    $status['status']='success';
    $status['message'] ='';
$batch_name=trim($_GET['batchname']);
    $sql = "select * from f_account_upload_t where batch_status !='LOADED'"; 
    $result_pr = \DB::select($sql); 


    if (!empty($result_pr)) 
    { 
            $credit_amount=0;
            $debit_amount=0;
		foreach($result_pr as $key=>$value)
        {
            $status['status']='success';
             $status['message'] ='';
	        $prddata=$this->accountstructuredata($value->journal_name,'concatenated_segments'); 
        if($key==1)
		
			
			
            if(count($prddata)<=0)
            {
				$status['status'] = 'error';
                $status['message'] = 'Account Structure name Not exist' . ' ,';
			}
			if($value->debit_amount!='')
            $debit_amount=$debit_amount+$value->debit_amount;
		if($value->credit_amount!='')
            $credit_amount=$credit_amount+$value->credit_amount;
			
			if($status['status'] == 'error') 
			{
				//$msg=$this->makeNumbered_string($status['message']);
				$sql = "update f_account_upload_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE account_upload_id='" . $value->account_upload_id . "' ";
				$result = \DB::update($sql);
				$status['message']='Uploaded data have some error';  
                // dd("Asd");
			} 
			else
			{
                
				$sql = "update f_account_upload_t set batch_status ='VALIDATED' , batch_comments='' where account_upload_id='" . $value->account_upload_id . "' ";
                $result = \DB::update($sql);
                $status['status']=='success';
                $status['message']=' Uploaded data validated successfully  ';
			}
			
		}
		

        //FOR FINAL STAUS OF ALL READED ROWS FROM UPLOADED DATA
	   return $status;
    }
    else
    {
        $status['status']='info';
        $status['message']='Batch Already Validated';
        
		 return $status;
    }

}
/*End*/

/*Load Function*/
public function LoadMaster($loadModname, $valBatch, $status){

    
    
    $status['status'] = '';
    $status['message'] = '';
    $sql = "select f_account_upload_t.* from f_account_upload_t  where  batch_status ='VALIDATED' "; 

	$result = \DB::select($sql); 
    $data=array();
    $loadid=array();	

    if(count($result)>0){ 
                    $datas_header= array('journal_date'=>date('Y-m-d'),'journal_status'=>'APPROVED','journal_type'=>"OPENING BALANCE",'created_by'=>\Session::get('emp_id'),'created_at'=>'2019-04-01');
                    $user_id = \DB::table('f_journal_entry_t')->insertGetId($datas_header); 
        foreach($result  as $key=>$value):

            
            try{
				$prddata=$this->accountstructuredata($value->journal_name,'concatenated_segments'); 
        
            if(count($prddata)>0)
            {
				
				$prddata_id=$prddata[0]->f_account_structure_id;
			}else{
				$prddata_id='';
			}
                  $datas = array('line_no'=>($key+1),'credit_amount'=>$value->credit_amount,'debit_amount'=>$value->debit_amount,'reference_source'=>'OPENING BALANCE','account_id'=>$prddata_id,'journal_date'=>$value->journal_date,'journal_entry_id'=>$user_id,'created_by'=>\Session::get('emp_id'),'created_at'=>date('Y-m-d'));
				
                    $user = \DB::table('f_journal_entry_lines_t')->insertGetId($datas); 
               
            
            }
            catch(\Illuminate\Database\QueryException $e)
            {
             
                $message = explode('(', $e->getMessage());
                $dbCode = rtrim($message[0], ']');
                $dbCode = trim($dbCode, '[');
                $status['status']='error';
                $status['message']=$dbCode;
                            
                return $status;
            }
            
        endforeach;
        $status['status']='success';
        $status['message']='Stock Moved Sucessfully';
	
    return $status;
      //  return true;
            
    }else{

      
        $sql=\DB::select("select * from f_account_upload_t where batch_name='".$loadModname."'");
		
		if($sql[0]->batch_status=="UPLOADED"){
             $status['status']='info';
             $status['message']='Pls Validate the Batch First..!';
        return $status;
		}else if($sql[0]->batch_status=="ERROR"){
			$status['status']='error';
             $status['message']='Batch Error..!';
        return $status;
		}else{
			$status['status']='info';
             $status['message']='Account Data already Loaded';
        return $status;
    }
    }

    
}
/*End*/
/*Load Product Data*/
public function accountstructuredata($value=null,$type=null){

    
    $prdsql=\DB::select("select * from  f_account_structure_t where concatenated_segments='$value'");	

    return 	$prdsql;
}
/*End*/	
/*Load Subinventory Data*/
public function Subinventorydata($value=null,$type=null){
    if($type=='name') 
    { //dd($type);
        $cond=" and subinventory_name='".$value."'";	
    }
    else
    {
        $cond="";
    } 
    $prdsql=\DB::select("select count(*)  as cnt,subinventory_id from m_subinventory_t where 1=1 $cond");	
    return 	$prdsql; 
}	
/*End*/	

/*locator data function*/
public function Locatordata($value=null,$type=null,$subinve=null){
    if($type=='name'){
        $cond=" and locator_code='".$value."' and subinventory_id='".$subinve."'";	 
    }else{
        $cond="";
    }
    $prdsql=\DB::select("select count(*)  as cnt,subinventory_id,sublocator_id from m_sublocators_t where 1=1 $cond");	//dd($prdsql);
    return 	$prdsql;
    }	
	/*end*/
		/*deepika purpose:to return status*/
	    public function getaccountvalidate(Request$request) {

        if (isset($_GET['batchname'])) { 
            if (!empty($_GET['batchname'])) {
                $filter = 'AND f_account_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        } if (isset($_GET['type'])) {
            $type = 'OPENSTOCK';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':$upload = $this->uploadValidation($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;
                case 'load':$upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;
            }
        }
    }
/*end*/
}
