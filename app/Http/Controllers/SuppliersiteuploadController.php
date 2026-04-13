<?php

namespace App\Http\Controllers;

use App\suppliersiteupload;
use Illuminate\Http\Request;
use Validator,
Input,
Redirect,
DB;
use Yajra\DataTables\DataTables;


class SuppliersiteuploadController extends Controller
{
	
   public function __construct(){
	   
         $this->data['urlmenu']=$this->indexs(); 
	    $this->data['pageMethod']=\Request::route()->getName();
	   
    }
	
	
    public function index(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI

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
                $filter = 'AND p_suppliersites_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
		if (isset($_GET['type'])) {
            $type = 'SUPPLIERSITEUPLOAD';
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
		
        return view('suppliersiteupload.table', $this->data);
    }
   
   public function create($id=null)
    {
      	$suppliersiteupload=Suppliersiteupload::find($id);

	    $this->data['suppliersiteuploaddata']=$suppliersiteupload;
	    if(isset($id)){
   
	    }else{
	    $suppliersiteuploads=\DB::connection()->getSchemaBuilder()->getColumnListing("p_suppliersites_upload_t");
		$suppliersiteupload=array();
		foreach($suppliersiteuploads as $key=>$val)
		{
			$suppliersiteupload[$val]="";
		}
		}

        $this->data['country']=$this->jCombologin("m_countries_t","country_name","country_name",$suppliersiteupload['country']);
		    $this->data['state']=$this->jCombologin("m_states_t","state_name","state_name",$suppliersiteupload['state']);
        $this->data['city']=$this->jCombologin("m_cities_t","city_name","city_name",$suppliersiteupload['city']);
//        $this->data['org_locationcode_id']=$this->jCombologin("m_location_t","location_code","location_code",$suppliersiteupload['org_locationcode_id']);
		
        // dd($suppliersiteupload);
        return view('suppliersiteupload.form',$this->data);
    }

  public function save(Request $request)
    {
        $suppliersiteupload=new Suppliersiteupload();
            
        $_POST['batch_status']='UPLOADED';
        $_POST['batch_comments']='';
	  // dd($_POST);
      
        $data['supplier_name']=$_POST['supplier_name'];
		// $data['supplier_site_number']=$_POST['supplier_name'];
		$data['supplier_site_name']=$_POST['supplier_site_name'];
        $data['address']=$_POST['address'];
        $data['country']=$_POST['country'];
        $data['state']=$_POST['state'];
		$data['city']=$_POST['city'];
        $data['pincode']=$_POST['pincode'];
        $data['contact_number']=$_POST['contact_number'];
        $data['contact_person']=$_POST['contact_person'];
		$data['mail_id']=$_POST['mail_id'];
		$data['gst_number']=$_POST['gst_number'];
		$data['tan_number']=$_POST['tan_number'];
		$data['primary_address']=$_POST['primary_address'];
		$data['active']=$_POST['active'];
		$data['batch_name']=$_POST['batch_name'];
		$data['batch_date']=$_POST['batch_date'];
		$data['batch_status']= $_POST['batch_status'];
		$data['batch_comments']=$_POST['batch_comments'];
		
        $id=$_POST['suppliersite_upload_id'];
        // dd($data);
		Suppliersiteupload::find($id)->update($data);
        
        return response()->json(array('status' => 'success', 'message' => 'your data Updated successfully!!'));
        // return redirect('suppliersiteupload')->with('success','your data Updated successfully');


    }


   
      public function show(Suppliersiteupload $suppliersiteupload,$id=null)
    {
		$this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("p_suppliersites_upload_t");
		$this->data['values'] = Suppliersiteupload::find($id);
		
        return view('suppliersiteupload.view',$this->data);
    }

   
  
// table data
	
	public function getSuppliersiteuploaddata(){
			$wh='';
				
			if(isset($_GET['batchname'])){
			if($_GET['batchname']!=""){
			$wh= " and batch_name like '" . $_GET['batchname'] . "'";	
			}}


			$SQL = "SELECT p_suppliersites_upload_t.*,p_suppliersites_upload_t.suppliersite_upload_id,p_suppliersites_upload_t.supplier_name,p_suppliersites_upload_t.supplier_site_name,p_suppliersites_upload_t.supplier_site_number,p_suppliersites_upload_t.address,p_suppliersites_upload_t.country,p_suppliersites_upload_t.state,p_suppliersites_upload_t.city,p_suppliersites_upload_t.contact_number,p_suppliersites_upload_t.batch_name,p_suppliersites_upload_t.batch_date,p_suppliersites_upload_t.batch_status,p_suppliersites_upload_t.batch_comments from p_suppliersites_upload_t where 1=1 $wh";
		
			$result = \DB::select($SQL);
		   return DataTables::of($result)->make(true);

		}
	

public function Uploadexcel(Request $request){  

        $path = $request->file('choosefile');
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$extension = $path->getClientOriginalExtension();
		// dd($path,$ extension);
		$data =array();
        $return = 'suppliersiteupload';
        if($extension == "csv"){
            $file = $request->file('choosefile');
    		$handle = fopen($file, "r");
            $c = 0;
            
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
		    {
                if($c>0){
	       		    // dd($filesop);
                        $supplierdata[$c]['supplier_name'] = strtoupper(trim($filesop[0]));
                        $supplierdata[$c]['supplier_site_name'] = strtoupper(trim($filesop[1]));
                        $supplierdata[$c]['address'] = strtoupper(trim($filesop[2]));
                        $supplierdata[$c]['country'] = trim($filesop[3]);
                        $supplierdata[$c]['state'] = trim($filesop[4]);
                        $supplierdata[$c]['city'] = trim($filesop[5]);
                        $supplierdata[$c]['pincode'] = strtoupper(trim($filesop[6]));
                        $supplierdata[$c]['contact_person'] = trim($filesop[7]);
                        $supplierdata[$c]['contact_number'] = strtoupper(trim($filesop[8]));
			$supplierdata[$c]['mail_id'] = trim($filesop[9]);
                        $supplierdata[$c]['gst_number'] = strtoupper(trim($filesop[10]));
                        $supplierdata[$c]['tan_number'] = strtoupper(trim($filesop[11]));
                        $supplierdata[$c]['primary_address'] = strtoupper(trim($filesop[12]));
                        $supplierdata[$c]['active'] = strtoupper(trim($filesop[13]));
                        $supplierdata[$c]['batch_date'] = date('Y-m-d');
                        $supplierdata[$c]['batch_status'] = "UPLOADED";
                        $supplierdata[$c]['batch_name'] = $_POST['batch_name'];
                        $companyid = \Session::get('companyid');
			$locid = \Session::get('location');
                        $orgid=\Session::get('organization');
                        $supplierdata[$c]['company_id'] =$companyid;
                        $supplierdata[$c]['location_id']=$locid;
                        $supplierdata[$c]['organization_id']=$orgid;
                      
			//insert record from csv 		
                }   
			$c = $c + 1;
		    }
            // dd($supplierdata);

            $id = \DB::table('p_suppliersites_upload_t')->insert($supplierdata);	
        }else {
           return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!')); 	
        }
         
    
     return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));
         
         
} 
	/*end*/
function uploadValidation($valModname, $valBatch, $status) {
        
    $status['status']='success';
    $status['message'] =' ';

    $sql = "select * from p_suppliersites_upload_t where batch_status ='UPLOADED'  and batch_name='" . $valModname . "'"; 
    $result_pr = \DB::select($sql); 
    // dd($result_pr);
    if (!empty($result_pr)) 
    { 
		foreach($result_pr as $key=>$value)
        {
			 $status['status']='success';
			$status['message'] =' ';
			if(!empty($value->supplier_name))
            {
                  $supplier =$this->supplier($value->supplier_name,"name");
				if ($supplier[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Supplier Name not exist' . ' ,';
                }
         	}else{
				  $status['status'] = 'error';
                    $status['message'].= 'Supplier Name Empty.. Please enter supplier name' . ' ,';
				
			}
		
		
			if(!empty($value->country))
            {
                $country = $this->country($value->country,"name");
               if ($country[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Country not exist' . ' ,';
                }
			}else{
				$status['status'] = 'error';
                    $status['message'].= 'Country Empty.. Please enter Country name' . ' ,';
			}
           
			if(!empty($value->state))
            {
                $state = $this->state($value->state,"name");
               if ($state[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'State not exist' . ' ,';
                }
			}else{
				$status['status'] = 'error';
                    $status['message'].= 'State Empty.. Please enter State name' . ' ,';
			}
           
           
			if(!empty($value->city))
            {
                $city = $this->city($value->city,"name");
               if ($city[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'City not exist' . ' ,';
                }
			}else{
				$status['status'] = 'error';
                    $status['message'].= 'City Empty.. Please enter City name';
			}
            
            if(!empty($value->org_locationcode_id))
            {
                $org_locationcode_id = $this->locationcode($value->org_locationcode_id,"code");
                // dd($org_locationcode_id);
                if ($org_locationcode_id[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Location does not exist' . ' ,';
                }
            }

		
			if($status['status'] == "error") 
			{
				$sql = "update p_suppliersites_upload_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE suppliersite_upload_id='" . $value->suppliersite_upload_id . "' ";
				$result = \DB::update($sql);
				$status['message']='Uploaded data have some error';  
			} 
			else
			{
    			$sql = "update p_suppliersites_upload_t set batch_status ='VALIDATED' , batch_comments='' where suppliersite_upload_id='" . $value->suppliersite_upload_id . "' ";
                $result = \DB::update($sql);
                $status['status']=='success';
                $status['message']='Supplier Data Validated successfully';
			}
		}
	// dd($status);
        //FOR FINAL STAUS OF ALL READED ROWS FROM UPLOADED DATA
	   return $status;
    }
    else
    {$status['status']='info';
        $status['message']='Batch Already Validated';
		 return $status;
    }

}

public function Supplierno($table,$column,$id)
	{
		if($id != ''){
			$seqno = \DB::table($table)->where('supplier_id','!=',$id)->orderBy($column,'desc')->get();
		}else{
			$seqno = \DB::table($table)->orderBy($column,'desc')->get();
		}
		if(count($seqno)>0)
	    {
	        $seqno = $count = $seqno[0]->{$column};
	    }
	    else
	    {
	        $seqno = $count = 99;
	    }
	    $seqno = sprintf('%04d',$seqno);
	    $prd_no = $count;
	    
	    return $prd_no;
	}
        
	public function LoadMaster($loadModname, $valBatch, $status){
    
    $status['status'] = '';
    $status['message'] = '';
    $sql = "select * from p_suppliersites_upload_t  where  batch_status ='VALIDATED'  and batch_name='" . $loadModname . "'";
    $result = \DB::select($sql); 
    $data=array();
    $loadid=array();	
//	 dd($result);
    if(count($result)>0){ 
    
         foreach($result  as $key=>$value):
             $supplier=$this->supplier($value->supplier_name,'name');
               $data['supplier_id'] = $supplier[0]->supplier_id;
                    $supplier1=$this->supplier($supplier[0]->supplier_id,'id');
                    $ssupno=$supplier1[0]->supplier_number;
                        $sql=\DB::select("select * from m_supplier_sites_t where supplier_id=".$supplier[0]->supplier_id);
//                        dd($sql);
                        $cnt=count($sql);
                        $a = sprintf("%03d ",$cnt+1);
			 $sseqno=$ssupno.'/SS'.$a;
                   $data['supplier_site_number'] = $sseqno;
           	   $data['supplier_site_name'] =$value->supplier_site_name;
		    //$data['supplier_site_name'] = $supno."B-".$value->city;
		   $data['address'] =$value->address;
		   $country=$this->country($value->country,'name');
		   $data['country'] = $country[0]->country_id;
		   $state=$this->state($value->state,'name');
		   $data['state'] = $state[0]->state_id;
		   $city=$this->city($value->city,'name');
		   $data['city'] = $city[0]->city_id;
		   $data['pincode'] = $value->pincode;
		   $data['contact_person'] = $value->contact_person;
		   $data['contact_number'] = $value->contact_number;
		   $data['contact_mail'] = $value->mail_id;
		   $data['gst_number'] = $value->gst_number;
		   $data['tan_no'] = $value->tan_number;
		   $data['primary_address'] = $value->primary_address;
		   $data['active'] = $value->active;
		   $data['organization_id'] =\Session::get('organization');
		   $data['company_id'] =\Session::get('companyid');
       $data['location_id'] =\Session::get('location');
     //  if($value->org_locationcode_id != ""){
	//	    $data['org_locationcode_id'] =$value->org_locationcode_id;
    //  }else{
    //      $data['org_locationcode_id'] =\Session::get('location');
    //  }
		
            try{
    				 
                $id = \DB::table('m_supplier_sites_t')->insert($data);					 
                $sql="UPDATE p_suppliersites_upload_t set batch_status='LOADED'  where suppliersite_upload_id = $value->suppliersite_upload_id";
                \DB::update($sql);
                 // add the additoal input fields like type, creadted by
                // update the interface table with LOADED
                 
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
                 $status['message']='Supplier Site Data loaded Sucessfully';
              return $status;
      //  return true;
            
    }else{

       $sql=\DB::select("select * from p_suppliersites_upload_t where batch_name='".$loadModname."'");
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
             $status['message']='Supplier Site Data already Loaded';
        return $status;
		}
    }


}



public function getsuppliernumber($stateid=null){
     $seqno=$this->Seqno('S','m_supplier_t',"");
//     dd($seqno);
//    $sql=\DB::select("select ");
}

    public function locationcode($value=null,$type=null){
        if($type=='code'){
            $cond=" and location_code='".$value."'";     
        }else if($type=='id'){
            $cond=" and location_id='".$value."'";
        }
            else{
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,location_id,location_code,location_name from m_location_t where 1=1 $cond");
        return  $sql;
    }
    
	public function supplier($value=null,$type=null){
    if($type=='name'){
        $cond=" and supplier_name='".$value."'";	 
    }else if($type=='id'){
        $cond=" and supplier_id='".$value."'";
    }
		else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,m_supplier_t.supplier_id,m_supplier_t.supplier_name,m_supplier_t.supplier_number,m_supplier_t.supplier_type_id,m_suppliertypes_t.suppliertype_name from m_supplier_t LEFT JOIN m_suppliertypes_t on (m_suppliertypes_t.suppliertype_id=m_supplier_t.supplier_type_id) where 1=1 $cond");
    
    return $sql;
    }
	public function country($value=null,$type=null){
    if($type=='name'){
        $cond=" and country_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,country_id,country_name from m_countries_t where 1=1 $cond");
    return 	$sql;
    }
	public function city($value=null,$type=null){
    if($type=='name'){
        $cond=" and city_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,city_id,city_name from m_cities_t where 1=1 $cond");
    return 	$sql;
    }
	public function state($value=null,$type=null){
    if($type=='name'){
        $cond=" and state_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,state_id,state_name,state_code,state_code_no from m_states_t where 1=1 $cond");
   // dd($sql);
    return 	$sql;
    }
	
/*deepika purpose:to return status*/
	    public function getSuppliersitevalidate(Request$request) {
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND p_suppliersites_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }if (isset($_GET['type'])) {
            $type = 'SUPPLIERSITEUPLOAD';
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
