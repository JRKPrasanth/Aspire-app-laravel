<?php

namespace App\Http\Controllers;

use App\targets;
use App\srtargets;
use App\branchtargets;
use App\targetupload;
use Illuminate\Support\Facades\Schema;
use DB,Illuminate\Support\Facades\Redirect;;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TargetsController extends Controller
{
   public function __construct()
    {
        $this->data=array();
        $this->model=new targets();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlmenu']=$this->indexs();
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

        $batch = '';
        $type = '';
        $this->data['status'] = $this->data['message'] = ''; 
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND target_upload_tbl.batch_name = "' . $_GET['batchname'] . '"'; 
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'TARGETUPLOAD';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':$upload = $this->UploadValidation($batch, $type, $message);
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

        
        return view('targetsupload.distributortargets',$this->data);	
    }
    
    
      public function Uploadexcel(Request $request){  

        $path = $request->file('choosefile');
               // dd($_FILES);

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        $data =array();
        $return = 'targetupload';
        if($extension == "csv"){
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
                if($c>0){
               //  dd($filesop);
                    $productdata[$c]['stockist_name'] = strtoupper(trim($filesop[0]));
                    $productdata[$c]['Year'] = strtoupper(trim($filesop[1]));
                    $productdata[$c]['month'] = strtoupper(trim($filesop[2]));
                    $productdata[$c]['target_qty'] = strtoupper(trim($filesop[3]));
                  
                    $productdata[$c]['batch_date'] = date('Y-m-d');
                    $productdata[$c]['batch_status'] = "UPLOADED";
                    $productdata[$c]['batch_name'] = $_POST['batch_name'];     
                  //  dd($productdata);
            //insert record from csv        
                }  
            $c = $c + 1;
            }
            $id = \DB::table('target_upload_tbl')->insert($productdata);    
        }else {

            $message = "Please upload an valid CSV file";
            // return Redirect::to($return)->with('messagetext',$message)->with('msgstatus','error');      
            return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }
         
    
    // return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');    
        return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));
         
         
    } 
    
    
	
	public function targetuploaddata() {
		
        $wh='';


        $SQL = "SELECT * FROM target_upload_tbl where 1=1 $wh";
		
        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }


    public function create()
    {
        //
    }
    
    //     public function getawdvalidate(Request $request) {
       
    //         if (isset($_GET['batchname'])) 
    //         {
    //             if (!empty($_GET['batchname'])) 
    //             {
    //                 $filter = 'AND target_upload_tbl.batch_no = "' . $_GET['batchname'] . '"';
    //                 $batch = $_GET['batchname'];
    //             }
    //         }

    //         if (isset($_GET['type'])) 
    //         {
    //             $message['status'] = 'success';
    //             switch ($_GET['type']) 
    //             {
    //                             $message['status'] = 'success';

    //                 case 'verify':
                        
    //                     $upload = $this->uploadValidation($batch, $message);
    //                     $this->data['status'] = $upload['status'];
    //                     $this->data['message'] = $upload['message'];
    //                     return $upload;
    //                     break;
                    
    //                 case 'load':$upload = $this->LoadMaster($batch, $message);
    //                     $this->data['status'] = $upload['status'];
    //                     $this->data['message'] = $upload['message'];
    //                     return $upload;
    //                     break;
    //             }
    //         }
    // }

     public function getawdvalidate(Request$request) 
    {
        if (isset($_GET['batchname'])) 
        {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND target_upload_tbl.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) 
        {
            $type = 'TARGETUPLOAD';
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
    
  // load function
     public function LoadMaster($loadModname, $valBatch, $status) {
    $status['status'] = '';
    $status['message'] = '';

    // Query to get the validated batch data
    $sql = "select * from target_upload_tbl where batch_status ='VALIDATED' and batch_name='" . $loadModname . "'";
    $result = \DB::select($sql);
    $data = array();
    $loadid = array();

    if(count($result) > 0) { 
        foreach($result as $key => $value) {
            // Get stockist customer_id
            $stockist = $this->stockist($value->stockist_name, "name");
            $customer_id = $stockist[0]->customer_id;

            // Check if a record already exists in targets
            $existingTarget = \DB::table('s_target_tbl')
                ->where('customer_id', $customer_id)
                ->where('month', $value->month)
                ->where('Year', $value->Year)
                ->first();

            if($existingTarget) {
                // Update the existing record
                \DB::table('s_target_tbl')
                    ->where('target_id', $existingTarget->target_id)
                    ->update(['value' => $value->target_qty, 'updated_at' => \Session::get('id')]);
            } else {
                // Insert a new record
                $targets = new targets();
                $targets->customer_id = $customer_id;
                $targets->value =  $value->target_qty;
                $targets->month =  $value->month;
                $targets->Year =  $value->Year;
                $targets->company_id = \Session::get('companyid');
                $targets->created_by = \Session::get('id');
                $targets->save();
            }

            // Update target_upload_tbl
            $sql = "update target_upload_tbl set batch_status ='LOADED', batch_comments='' where targets_id='" . $value->targets_id . "'";
            \DB::update($sql);
        }

        $status['status'] = 'success';
        $status['message'] = 'Data Loaded Successfully';
        return $status;
    } else {
        // Check the batch status if no validated records found
        $sql = \DB::select("select * from target_upload_tbl where batch_no='".$loadModname."'");
        if($sql[0]->batch_status == "UPLOADED") {
            $status['status'] = 'info';
            $status['message'] = 'Please Validate the Batch First..!';
            return $status;
        } else if($sql[0]->batch_status == "ERROR") {
            $status['status'] = 'error';
            $status['message'] = 'Batch Error..!';
            return $status;
        } else {
            $status['status'] = 'info';
            $status['message'] = 'Data already Loaded';
            return $status;
        }
    }
}
	

public function stockist($value=null,$type=null){
        if($type=='name'){
            $cond=' and customer_name="'.$value.'"';     
        }else {
            $cond='';
        }
        $sql=\DB::select('select count(*)  as cnt,customer_id,customer_name from m_customers_t where 1=1 and active="Yes" '.$cond);
        return  $sql;
    }
    
public function state($value=null,$type=null){
        if($type=='name'){
            $cond=' and state_name="'.$value.'"';     
        }else {
            $cond='';
        }
        $sql=\DB::select('select count(*)  as cnt,state_id,state_name from m_states_t where 1=1 '.$cond);
        return  $sql;
    }
public function town($value=null,$type=null){
        if($type=='name'){
            $cond=' and city_name="'.$value.'"';     
        }else {
            $cond='';
        }
        $sql=\DB::select('select count(*)  as cnt,city_id,city_name from m_cities_t where 1=1  '.$cond);
        return  $sql;
    }
// public function product($value=null,$type=null){
//         if($type=='name'){
//             $cond=' and concatenated_product="'.$value.'"';     
//         }else {
//             $cond='';
//         }
//         $sql=\DB::select('select count(*)  as cnt,product_id,concatenated_product from m_products_t where 1=1 and active="Yes" '.$cond);
//         return  $sql;
//     }
    
    public function employee($value=null,$type=null){
        if($type=='name'){
            $cond=' and employee_name="'.$value.'"';     
        }else {
            $cond='';
        }
        $sql=\DB::select('select count(*)  as cnt,employee_id,employee_name from employee_tbl where 1=1 and active="Yes" '.$cond);
        return  $sql;
    }
    public function branch($value=null,$type=null){
        if($type=='name'){
            $cond=' and warhouse_name="'.$value.'"';     
        }else {
            $cond='';
        }
        $sql=\DB::select('select count(*)  as cnt,branch_id,warhouse_name from branch_tbl where 1=1 and active="Yes" '.$cond);
        return  $sql;
    }

function uploadValidation($valModname,$valBatch,$status) {
	$status['status']='success';
        $status['message'] =' ';
     //   $sql = "select * from target_upload_tbl where batch_status !='VALIDATED' and  batch_status !='LOADED' and batch_no='" . $batchno . "'"; 
             $sql = "select * from target_upload_tbl where batch_status ='UPLOADED'  and batch_name='" . $valModname . "'"; 

        $result_pr = \DB::select($sql); 
	if (!empty($result_pr)) 
        { 
            $check=0;
            
            foreach($result_pr as $key=>$value)
            {
                   $status['status'] ='success';
                   $status['message'] =' ';
                 // dd($value);
                
                       	if(!empty($value->stockist_name))
                {
                    $stockist =$this->stockist($value->stockist_name,"name");
                  
					if ($stockist[0]->cnt <= 0) 
					{
                        $status['status'] = 'error';
                        $status['message'].= 'Stockist Name does not exist'.' , ' ;
                    }
                }
				else
				{
                    $status['status'] = 'error';
                    $status['message'].= 'Stockist Name Empty.. Please enter Stockist Name' . ' , ';                    
                }
                       
                   
                // dd($status);
				
    //           	if(!empty($value->city_name))
    //             {
    //                 $town =$this->town($value->city_name,"name");
                  
				// 	if ($town[0]->cnt <= 0) 
				// 	{
    //                     $status['status'] = 'error';
    //                     $status['message'].= 'City Name does not exist'.' , ' ;
    //                 }
    //             }
				// else
				// {
    //                 $status['status'] = 'error';
    //                 $status['message'].= 'City Name Empty.. Please enter City Name' . ' , ';                    
    //             }

                // if(!empty($value->state_name))
                // {
                //     $state =$this->state($value->state_name,"name");
                  
                //     if ($state[0]->cnt <= 0) 
                //     {
                //         $status['status'] = 'error';
                //         $status['message'].= 'State Name does not exist'.' , ' ;
                //     }
                // }
                // else
                // {
                //     $status['status'] = 'error';
                //     $status['message'].= 'State Name Empty.. Please enter State Name' . ' , ';                    
                // }
				
				//               	if(!empty($value->product_name))
    //             {
    //                 $product =$this->product($value->product_name,"name");
                  
				// 	if ($product[0]->cnt <= 0) 
				// 	{
    //                     $status['status'] = 'error';
    //                     $status['message'].= 'Product Name does not exist'.' , ' ;
    //                 }
    //             }
				// else
				// {
    //                 $status['status'] = 'error';
    //                 $status['message'].= 'Product Name Empty.. Please enter Product Name' . ' , ';                    
    //             }
				
              

                if(empty($value->target_qty))
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Target Qty Empty.. Please enter Target Qty' . ' , ';                    
                }
 if(empty($value->month))
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Month Empty.. Please enter Month' . ' , ';                    
                }
 if(empty($value->Year))
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Year Empty.. Please enter Year' . ' , ';                    
                }

                
				$status['message']=rtrim($status['message'],',');
                              
				if($status['status'] == "error") 
                {
                                    $check=1;
                    $sql = "update target_upload_tbl set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE targets_id='" . $value->targets_id . "' ";
                    $result = \DB::update($sql);
                    $status['message']='Target data have some error';  
                } 
                else
                {
                    $sql = "update target_upload_tbl set batch_status ='VALIDATED' , batch_comments='' where targets_id='" . $value->targets_id . "' ";
                    $result = \DB::update($sql);
                    $status['status']='success';
                    $status['message']='Target Data validated successfully';
                }
				
          
        }
  
		 return $status;
       
	}
       $status['status']='error';
        $status['message'] ='NO DATA TO VALIDATE';
         return $status;
     }
     
      public function awdedit($id=null)
   {
    
      $sql = \DB::table('target_upload_tbl')->where('targets_id',$id)->get();
 // dd($sql);
 
     
      $this->data['targets_id']=$sql[0]->targets_id;
   /*   $this->data['stockist_name']=$sql[0]->stockist_name;
      $this->data['city_name']=$sql[0]->city_name;
      $this->data['state_name']=$sql[0]->state_name;
      $this->data['product_name']=$sql[0]->product_name;*/
      $this->data['target_qty']=$sql[0]->target_qty;
      $this->data['month']=$sql[0]->month;
      $this->data['Year']=$sql[0]->Year;
      $this->data['source']=$sql[0]->source;
      if($sql[0]->source=="SRTargets"){
            $this->data['stockist_name']=$this->jcombo('employee_tbl','employee_name','employee_name',$sql[0]->stockist_name);
      }else if($sql[0]->source=="BRANCH TARGET"){
           $this->data['stockist_name']=$this->jcombo('branch_tbl','warhouse_name','warhouse_name',$sql[0]->stockist_name);
      }
      else{
          $this->data['stockist_name']=$this->jcombo('awd_tbl','bp_name','bp_name',$sql[0]->stockist_name);
      }
      
      $this->data['city_name']=$this->jcombo('m_cities_t','city_name','city_name',$sql[0]->city_name);
      $this->data['state_name']=$this->jcombo('m_states_t','state_name','state_name',$sql[0]->state_name);
     $this->data['product_name']=$this->jcombo('m_products_t','concatenated_product','concatenated_product',$sql[0]->product_name);
      //$this->data['printer_id']=$this->jcombo('printer_tbl','printer_name','printer_name',$sql[0]->printer_id);
  //    $this->data['model_id']=$this->jcombo('machine_model','machine_model_name','machine_model_name',$sql[0]->model_id);

       $this->data['pageMethod']=""; 
      

       return view('targetsupload.edit', $this->data);
}

  public function save(Request $request)
    {
      
      

          $id=$data['targets_id']=$_POST['edit_id'];
            $data['target_qty']=$_POST['target_qty'];
            $data['month']=$_POST['month'];
            $data['Year']=$_POST['Year'];
            $data['stockist_name']=$_POST['stockist_name'];
            $data['city_name']=$_POST['city_name'];
            $data['state_name']=$_POST['state_name'];
            $data['product_name']=$_POST['product_name'];
            $data['batch_status']="UPLOADED";
            $data['batch_comments']="";

//      			dd($data);
                targetupload::find($id)->update($data);
			
          return response()->json(array('status' => 'success', 'message' => 'your data Updated successfully!!','id'=>$id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $path = Request::file('file_upload');
            $extension = $path->getClientOriginalExtension();
            $data =array();
            $file_path = $path->getPathName();
            $handle = fopen($file_path, "r");
            $c = 0;
            $columns_name = Schema::getColumnListing('product_upload_tbl');
		
            $array_count=16;
	
            if ($extension == "csv") 
            {
                $batch_no=$batch['batch_no']="BATCH_".date('Y-m-d')."_".date('Hi');
                $source=$_POST['source'];
             //   $id = \DB::table('hr_upload_details_t')->insertGetId(['batch_no' =>$batch_no,'source'=>$source,'status'=>'0']);
                $insert['batch_no']=$batch_no;
                $list = '';
            
                while (($filesop = fgetcsv($handle, 10000, ",")) !== false) 
                {
			
                    $insert=[];
                    if($c >= 2) 
                    {
					$insert['stockist_name']=$filesop[0];
					$insert['city_name']=$filesop[1];
					$insert['state_name']=$filesop[2];
					$insert['product_name']=$filesop[3];
					$insert['Year']=$filesop[4];
					$insert['month']=$filesop[5];
					$insert['target_qty']=$filesop[6];
                    $insert['batch_no']     = $batch_no;
                    $insert['batch_status']     ="UPLOADED";
                    $insert['created_by'] = \Session::get('id');
                    $insert['created_at'] = date('Y-m-d');
                    $insert['company_id'] =  \Session::get('companyid');
                    $insert['location_id'] =  \Session::get('location');
                    $insert['source'] =$_POST['source'];
                                
                            \DB::table('target_upload_tbl')->insertGetid($insert);
                          dd($insert);
                    }
                    
                    $c++;
                }
                
		 $returns="distributortargets"; 

               return Redirect::to($returns)->with('message','Upload Successfull')->with('status', 'info');
        } 
        else 
        {
            return Redirect::to($returns)->with('message', 'Upload a Valid File Extension')->with('status', 'error');
        }
             
    }

 public function getdistributorvalidate(Request$request) 
    {
        if (isset($_GET['batchname'])) 
        {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND i_productupload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) 
        {
            $type = 'TARGETUPLOAD';
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
    /**
     * Display the specified resource.
     *
     * @param  \App\targets  $targets
     * @return \Illuminate\Http\Response
     */
    public function show(targets $targets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\targets  $targets
     * @return \Illuminate\Http\Response
     */
    public function edit(targets $targets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\targets  $targets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, targets $targets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\targets  $targets
     * @return \Illuminate\Http\Response
     */
    public function destroy(targets $targets)
    {
        //
    }
}
