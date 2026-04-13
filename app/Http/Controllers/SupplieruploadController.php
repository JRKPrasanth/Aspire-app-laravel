<?php

namespace App\Http\Controllers;

use App\supplierupload;
use Illuminate\Http\Request;
use Validator,
Input,
Redirect,
DB;
use Yajra\DataTables\DataTables;


class SupplieruploadController extends Controller
{

      public function __construct() {
        $this->data = array();
        $this->model = new supplierupload();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data=array(
                    'pageModule'=> 'supplierupload',
                    'pageUrl'   =>  url('supplierupload'),
                     'pageMethod'=>$this->data['pageMethod']
                    
                  );
        $this->data['urlmenu']=$this->indexs(); 
        //dd($this->data['urlmenu']);

    }
     public function index(Request $request)
    {
        // restrict illegal menu entry purpose - VIGNESH M

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
                $filter = 'AND p_supplier_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
		if (isset($_GET['type'])) {
            $type = 'SUPPLIERUPLOAD';
            $message['status'] = 'success';
            switch ($_GET['type']) {
				
                case 'verify':$upload = $this->UploadValidation($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
					//dd($this->data);
				     break;
                case 'load':$upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
			}
        }
		
        return view('supplierupload.table', $this->data);
    }

    
   public function create($id=null)
    {
        if(isset($id)){
      	$supplierupload=Supplierupload::find($id);
        }else{
            $supplieruploads=\DB::connection()->getSchemaBuilder()->getColumnListing("p_supplier_upload_t");
            $supplierupload= array();
            foreach($supplieruploads as $key=>$val)
            {
                $supplierupload[$val]="";
            }
        }

	    $this->data['supplieruploaddata']=$supplierupload;
//		dd($supplierupload);
		 $this->data['supplier_type_id'] = $this->jCombo('m_suppliertypes_t', 'suppliertype_name', 'suppliertype_name', $supplierupload->supplier_type);
        $this->data['default_pricelist_id'] =$this->jcustomselect('i_pricelist_hdr_t','pricelist_name','pricelist_name',$supplierupload->default_pricelist,' and price_list_type="Purchase"');
        $this->data['default_payment_terms_id'] = $this->jCombo('m_payment_terms_t','payment_term_name','payment_term_name',$supplierupload->default_payment_terms);
		$this->data['frieghtcarriers_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'carrier_name', 'carrier_name', $supplierupload->freight_carrier,' and source_type_id="Purchase"');
        $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','concatenated_segments','concatenated_segments',$supplierupload->account_structure);
		$this->data['frieghtterm_id'] = $this->jcustomselect('m_frieghtterms_t', 'fob_point_name', 'fob_point_name', $supplierupload->freight_term,' and source_type_id="Purchase"');
		$this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_term_name', 'delivery_term_name', $supplierupload->delivery_term,' and source_type_id="Purchase"');
                $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t','insurance_term_name','insurance_term_name',$supplierupload->insurance_term);
                $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t','payment_method_name','payment_method_name',$supplierupload->default_payment_method);
		$this->data['tds_percentage'] = $this->jCombo('f_tds_slab_t','tds_percentage','tds_percentage',$supplierupload->tds_percentage);
		$this->data['default_bank_id'] = $this->jCombo('f_bank_account_hdr_t','bank_name','bank_name',$supplierupload->default_bank);
		$this->data['customer_name']=$supplierupload->convertcustosup;
		$this->data['tds_applicable']=$supplierupload->tds_applicable;
                
        $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_name', 'customer_name',$supplierupload->customer);
		$this->data['tds_account_id'] = $this->jCombo('f_account_structure_t','concatenated_segments','concatenated_segments',$supplierupload->tds_account);
         //dd($this->data);
        return view('supplierupload.form',$this->data);
    }

   
    public function save(Request $request)
    {

        $supplierupload=new Supplierupload();
           $batch_status='UPLOADED';
		   
            $data['supplier_name']=$_POST['supplier_name'];
            $data['supplier_alternate_name']=$_POST['supplier_alternate_name'];
            $data['supplier_type']=$_POST['supplier_type_id'];
            $data['default_payment_method']=$_POST['default_payment_method_id'];
            $data['default_payment_terms']=$_POST['default_payment_terms_id'];
            $data['default_pricelist']=$_POST['default_pricelist_id'];
            $data['default_bank']=$_POST['default_bank_id'];
            $data['customer']=$_POST['customer_id'];
            $data['convertcustosup']=$_POST['customer_name'];
            $data['account_structure']=$_POST['account_structure_id'];
            $data['insurance_term']=$_POST['insurance_term_id'];
            $data['delivery_term']=$_POST['delivery_terms_id'];
            $data['freight_term']=$_POST['frieghtterm_id'];
            $data['freight_carrier']=$_POST['frieghtcarriers_id'];
            $data['pan_number']=$_POST['pan_number'];
            $data['tds_applicable']=$_POST['tds_applicable'];
            $data['tds_percentage']=$_POST['tds_percentage'];
            $data['tds_account']=$_POST['tds_account_id'];
            $data['active']=$_POST['active'];
            $data['batch_name']=$_POST['batch_name'];
            $data['batch_date']=$_POST['batch_date'];
            $data['batch_status']= $batch_status;
            $data['batch_comments']="";
            $id=$_POST['supplier_upload_id'];
//		dd($data);
                Supplierupload::find($id)->update($data);
		  return response()->json(array('status' => 'success', 'message' => 'your data Updated successfully!!','id'=>$id));
    }

   public function show(Supplierupload $supplierupload,$id=null)
    {
        $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("p_supplier_upload_t");
        $this->data['values'] = Supplierupload::find($id);
        return view('supplierupload.view',$this->data);
    }

	public function getSupplieruploaddata(){

		
                $wh='';
	
                if(isset($_GET['batchname'])){
                if($_GET['batchname']!=""){
                $wh= " and batch_name like '" . $_GET['batchname'] . "'";	
                }}

                
                $SQL = "SELECT p_supplier_upload_t.*,p_supplier_upload_t.supplier_upload_id,p_supplier_upload_t.supplier_name,p_supplier_upload_t.supplier_number,p_supplier_upload_t.supplier_type,p_supplier_upload_t.default_payment_method,p_supplier_upload_t.batch_name,p_supplier_upload_t.batch_date,p_supplier_upload_t.batch_status,p_supplier_upload_t.batch_comments from p_supplier_upload_t where 1=1 $wh";

                $result = \DB::select($SQL);
				return DataTables::of($result)->make(true);
		
		}

                
 

    public function Uploadexcel(Request $request)
    {  
        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
		// dd($path,$extension);
        $data =array();
        $return = 'supplierupload';
	
        if($extension == "csv") 
        {
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;
           // dd($filesop);
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
		    {
				if($c>0){
	       		    // dd($filesop);
                        $supplierdata[$c]['supplier_name'] = strtoupper(trim($filesop[0]));
                        $supplierdata[$c]['supplier_alternate_name'] = strtoupper(trim($filesop[1]));
                        $supplierdata[$c]['supplier_type'] = strtoupper(trim($filesop[2]));
                        $supplierdata[$c]['default_payment_method'] = strtoupper(trim($filesop[3]));
                        $supplierdata[$c]['default_payment_terms'] = strtoupper(trim($filesop[4]));
                        $supplierdata[$c]['default_pricelist'] = strtoupper(trim($filesop[5]));
                        $supplierdata[$c]['default_bank'] = trim($filesop[6]);
                        $supplierdata[$c]['convertcustosup'] = strtoupper(trim($filesop[7]));
                        $supplierdata[$c]['customer'] = strtoupper(trim($filesop[8]));
                        $supplierdata[$c]['account_structure'] = trim($filesop[9]);
                        $supplierdata[$c]['insurance_term'] = trim($filesop[10]);
                        $supplierdata[$c]['delivery_term'] = trim($filesop[11]);
                        $supplierdata[$c]['freight_term'] = trim($filesop[12]);
                        $supplierdata[$c]['freight_carrier'] = trim($filesop[13]);
                        $supplierdata[$c]['pan_number'] = strtoupper(trim($filesop[14]));
                        $supplierdata[$c]['tds_applicable'] = strtoupper(trim($filesop[15]));
                        $supplierdata[$c]['tds_percentage'] = strtoupper(trim($filesop[16]));
                        $supplierdata[$c]['tds_account'] = strtoupper(trim($filesop[17]));
                        $supplierdata[$c]['active'] = strtoupper(trim($filesop[18]));
                        $supplierdata[$c]['batch_date'] = date('Y-m-d');
                        $supplierdata[$c]['batch_status'] = "UPLOADED";
                        $supplierdata[$c]['batch_name'] = $_POST['batch_name'];
                        $companyid = \Session::get('companyid');
			$locid = \Session::get('location');
                        $orgid=\Session::get('organization');
                      $supplierdata[$c]['company_id'] =$companyid;
                      $supplierdata[$c]['company_id']=$locid;
                      $supplierdata[$c]['organization_id']=$orgid;
			//insert record from csv 		
                }   
			$c = $c + 1;
		    }
                   
            $id = \DB::table('p_supplier_upload_t')->insert($supplierdata);
        } else {
			//return redirect()->route('supplierupload')->with('message', 'Please upload an valid CSV file!!');
    //return Redirect::to($return)->with('messagetext',$message)->with('msgstatus','error');    	
             return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }
         
     return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));
 ///   return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('success','success');    
         
         
}     
	
function uploadValidation($valModname, $valBatch, $status) {
                  $status['status'] = 'success';
                    $status['message']="";
    $sql = "select * from p_supplier_upload_t where batch_status ='UPLOADED'  and batch_name='" . $valModname . "'"; 
    $result_pr = \DB::select($sql); 
    // dd($result_pr);
    if (!empty($result_pr)) 
    { 
		foreach($result_pr as $key=>$value)
        {
			if(empty($value->supplier_name))
            {
                    $status['status'] = 'error';
                    $status['message'].= 'Supplier Name Empty.. Please enter supplier name' . ' ,';
         	}
		
			if(!empty($value->supplier_type))
            {
                $suppliertype = $this->suppliertype($value->supplier_type,"name");
				
               if ($suppliertype[0]->cnt <= 0) {
				  
                    $status['status'] = 'error';
                    $status['message'].= 'Supplier type not exist' . ' ,';
                }
			}
            else
            { 
				    $status['status'] = 'error';
                    $status['message'].= 'Supplier type Empty.. Please enter Supplier Types' . ' ,';
			}
			if(!empty($value->default_payment_method))
            {
                $paymentmethod = $this->paymentmethod($value->default_payment_method,"name");
               if ($paymentmethod[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Default Payment Method not exist' . ' ,';
                }
			}
           
			if(!empty($value->default_payment_terms))
            {
                $paymentterms = $this->paymentterm($value->default_payment_terms,"name");
               if ($paymentterms[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Default Payment Terms not exist' . ' ,';
                }
			}
           
	if(!empty($value->default_pricelist)){
                $pricelist = $this->pricelist($value->default_pricelist,"name",'Purchase');
               if ($pricelist[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Default Pricelist not exist' . ' ,';
                }
			}
            else
            { 
		  $status['status'] = 'error';
                    $status['message'].= 'Default Pricelist Empty.. Please enter Default Pricelist' . ' ,';
			}
                        
        if(!empty($value->account_structure)){
                $accstructure = $this->accstructure($value->account_structure,"name");
               if ($accstructure[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Account Structure not exist' . ' ,';
                }
			}
            else
            { 
		  $status['status'] = 'error';
                    $status['message'].= 'Account Structure Empty.. Please enter Account Structure' . ' ,';
			}
			
//			if(!empty($value->insurance_term)){
//                $insurance_term = $this->insuranceterm($value->insurance_term,"name");
//               if ($insurance_term[0]->cnt <= 0) {
//                    $status['status'] = 'error';
//                    $status['message'].= 'Insurance Term not exist' . ' ,';
//                }
//			}
//            else
//            { 
//		  $status['status'] = 'error';
//                    $status['message'].= 'Insurance Term Empty.. Please enter Insurance Term' . ' ,';
//			}
			
			if(!empty($value->delivery_term)){
                $delivery_term = $this->deliveryterm($value->delivery_term,"name",'Purchase');
               if ($delivery_term[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Delivery Term not exist' . ' ,';
                }
			}
            else
            { 
		  $status['status'] = 'error';
                    $status['message'].= 'Delivery Term Empty.. Please enter Delivery Term' . ' ,';
			}
			if(!empty($value->freight_term)){
                $freightterm = $this->freightterm($value->freight_term,"name",'Purchase');
               if ($freightterm[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Freight Term not exist' . ' ,';
                }
			}
            else
            { 
		  $status['status'] = 'error';
                    $status['message'].= 'Freight Term Empty.. Please enter Freight Term' . ' ,';
			}
			
			if(!empty($value->freight_carrier)){
                $freightcar = $this->freightcarrier($value->freight_carrier,"name",'Purchase');
               if ($freightcar[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Freight Carrier not exist' . ' ,';
                }
			}
            else
            { 
		  $status['status'] = 'error';
                    $status['message'].= 'Freight Carrier Empty.. Please enter Freight Carrier' . ' ,';
			}
			
			
//            else
//            { 
//		  $status['status'] = 'error';
//                    $status['message'].= 'Freight Carrier Empty.. Please enter Freight Carrier' . ' ,';
//			}
			
			if(!empty($value->default_bank)){
                $freightcar = $this->defaultbank($value->default_bank,"name");
               if ($freightcar[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Default Bank not exist' . ' ,';
                }
			}
            else
            { 
		  $status['status'] = 'error';
                    $status['message'].= 'Default Bank Empty.. Please enter Default Bank' . ' ,';
			}
			
			
                    if(!empty($value->tds_applicable))
                    {
						//dd($value->tds_applicable);
                        if($value->tds_applicable == "YES" )
                        {
                            $tdspercentage = $this->tdspercentage($value->tds_percentage,"name");    
                            if ($tdspercentage[0]->cnt <= 0) {
                                $status['status'] = 'error';
                                $status['message'].= 'TDS Percentage does not exist' . ' ,';
                            }
                       if(!empty($value->tds_account)){
                        $freightcar = $this->tdsaccount($value->tds_account,"name");
                        if ($freightcar[0]->cnt <= 0) {
                                $status['status'] = 'error';
                                $status['message'].= 'TDS Account not exist' . ' ,';
                        }
                    }
                        
                        }else if($value->tds_applicable == "NO" ){
                        }else{
                            $status['status'] = 'error';
                            $status['message'].= 'Please enter TDS Applicable is only (Yes/No) ' . ' ,';
                        }
                    }
               
			 if(!empty($value->convertcustosup))
                    {
						//dd($value->tds_applicable);
                        if($value->convertcustosup == "YES" )
                        {
                            if(!empty($value->customer)){
                                    $customer = $this->customer($value->customer,"name");
                                    if ($customer[0]->cnt <= 0) {
                                         $status['status'] = 'error';
                                         $status['message'].= 'Customer not exist';
                                     }
			}
                       }
                        else if($value->convertcustosup == "NO" ){
                        }else{
                            $status['status'] = 'error';
                            $status['message'].= 'Please enter Convert To Supplier is only (Yes/No) ' . ' ,';
                        }
                    }	
			
			
                        
		/*	if(!empty($value->default_bank))
            {
                $suppliertype = \DB::select("select count(*)  as cnt,suppliertype_id,suppliertype_name from m_suppliertypes_t where suppliertype_name =$value->supplier_type");
               if ($result[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'] = 'Supplier type not exist' . ' ,';
                }
			}*/
          
			
			
			if(!empty($value->active))
                {
                    if($value->active != "YES" && $value->active != "No") {
                        $status['status'] = 'error';
                        $status['message'].= 'Active must be yes or no ' . ' ,';
                    }
                }else{
                    $status['status'] = 'error';
                    $status['message'].= 'active cant be empty' . ' ,';
                }
				
         
			if($status['status'] == "error") 
			{
				$sql = "update p_supplier_upload_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE supplier_upload_id='" . $value->supplier_upload_id . "' ";
				$result = \DB::update($sql);
				$status['message']='Uploaded data have some error';  
			} 
			else
			{
    			$sql = "update p_supplier_upload_t set batch_status ='VALIDATED' , batch_comments='' where supplier_upload_id='" . $value->supplier_upload_id . "' ";
                $result = \DB::update($sql);
                $status['status']=='success';
                $status['message']='Uploaded Data Validated successfully';
				
			}
		}
		       
		
        //FOR FINAL STAUS OF ALL READED ROWS FROM UPLOADED DATA
	//   return json_encode( $status);
	   return $status;
    }
    else
    {
        $status['status']='info';
        $status['message']='Batch Already Validated';
		 return $status;
    }
	  
     }



public function LoadMaster($loadModname, $valBatch, $status){
    
    $status['status'] = '';
    $status['message'] = '';
    $sql = "select * from p_supplier_upload_t  where  batch_status ='VALIDATED'  and batch_name='" . $loadModname . "'";
	  $result = \DB::select($sql); 
	
    $data=array();
    $loadid=array();	
    if(count($result)>0){ 
    
         foreach($result  as $key=>$value):
           $data['supplier_name'] = $value->supplier_name;
	      $seqno=$this->Seqno('S','m_supplier_t',"");
           $data['supplier_number'] = $seqno;
         //          $data['supplier_number'] = "";
		   $data['supplier_alternate_name'] = $value->supplier_alternate_name;
		   $suptype=$this->suppliertype($value->supplier_type,'name');
		   $data['supplier_type_id'] =$suptype[0]->suppliertype_id ;
		   $paymentmethod=$this->paymentmethod($value->default_payment_method,'name');
		   $data['default_payment_method_id'] = $paymentmethod[0]->payment_method_id;
		   $paymentterm=$this->paymentterm($value->default_payment_terms,'name');
		   $data['default_payment_terms_id'] = $paymentterm[0]->payment_term_id;
		   $pricelist=$this->pricelist($value->default_pricelist,'name','Purchase');
		   $data['default_pricelist_id'] = $pricelist[0]->pricelist_hdr_id;
		   
		   $bank=$this->defaultbank($value->default_bank,'name');
		   $data['default_bank_id'] = $bank[0]->bank_account_hdr_id;
		   
		   //$data['default_bank_id'] = $value->default_bank;
		   $data['customer_name'] = $value->convertcustosup;
		   $data['pan_number'] = $value->pan_number;
                   $data['tds_applicable'] = $value->tds_applicable;
		   $customer=$this->customer($value->customer,'name');
		   $data['customer_id'] =$customer[0]->customer_id;
		   
		   $acc=$this->accstructure($value->account_structure,'name');
		   $data['account_structure_id'] =$acc[0]->f_account_structure_id;
		   
		   $insuranceterm=$this->insuranceterm($value->insurance_term,'name');
                   
                   if($insuranceterm!=null){
		   $data['insurance_term_id'] =$insuranceterm[0]->insurance_term_id;
                   }else{
                       $data['insurance_term_id'] ="";
                   }
		   $deliveryterm=$this->deliveryterm($value->delivery_term,'name','Purchase');
		   $data['delivery_terms_id'] =$deliveryterm[0]->delivery_terms_id;
		   
		   $freightterm=$this->freightterm($value->freight_term,'name','Purchase');
		   $data['frieghtterm_id'] =$freightterm[0]->frieghtterm_id;
		   
		   $freightcarrier=$this->freightcarrier($value->freight_carrier,'name','Purchase');
		   $data['frieghtcarriers_id'] =$freightcarrier[0]->ar_frieghtcarriers_hdr_id;
		   
		   $tdsaccount=$this->tdsaccount($value->tds_account,'name');
		   $data['tds_account_id'] =$tdsaccount[0]->f_account_structure_id;
		   $tdspercentage=$this->tdspercentage($value->tds_percentage,'name');
		   $data['tds_percentage'] =$tdspercentage[0]->tds_slab_id;
		   $data['active'] = $value->active;
		   
		   $data['organization_id'] =\Session::get('organization');
		   $data['company_id'] =\Session::get('companyid');
		   $data['location_id'] =\Session::get('location');
//		dd($data);
            try{
    			
                $id = \DB::table('m_supplier_t')->insert($data);					 
                $sql="UPDATE p_supplier_upload_t set batch_status='LOADED'  where supplier_upload_id = $value->supplier_upload_id";
                \DB::update($sql);
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
             $status['message']='Supplier Data Loaded Sucessfully';
            return $status;
            
    }else{
		$sql=\DB::select("select * from p_supplier_upload_t where batch_name='".$loadModname."'");
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
             $status['message']='Supplier Data already Loaded';
        return $status;
		}
    }


}

	public function suppliertype($value=null,$type=null){
    if($type=='name'){
        $cond=" and suppliertype_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,suppliertype_id,suppliertype_name from m_suppliertypes_t where 1=1 $cond");
    return 	$sql;
    }	
	public function paymentterm($value=null,$type=null){
    if($type=='name'){
        $cond=" and payment_term_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,payment_term_id,payment_term_name from m_payment_terms_t where 1=1 $cond");
    return $sql;
    }
	public function paymentmethod($value=null,$type=null){
    if($type=='name'){
        $cond=" and payment_method_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,payment_method_id,payment_method_name from m_payment_methods_t where 1=1 $cond");
    return 	$sql;
    }
	public function pricelist($value=null,$type=null,$types=null){
    if($type=='name'){
        $cond=" and pricelist_name='".$value."'";        
    }else{
        $cond="";
    }
		
    $sql=\DB::select("select count(*)  as cnt,pricelist_hdr_id,pricelist_name from i_pricelist_hdr_t where 1=1 $cond and price_list_type='".$types."'");
    // dd($sql);
    return 	$sql;
    }
	public function customer($value=null,$type=null){
    if($type=='name'){
        $cond=" and customer_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,customer_id,customer_name from m_customers_t where 1=1 $cond");
    return 	$sql;
    }
	
	
	
	public function accstructure($value=null,$type=null){
    if($type=='name'){
        $cond=" and concatenated_segments='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,f_account_structure_id,concatenated_segments from f_account_structure_t where 1=1 $cond");
    return 	$sql;
    }
	
	public function insuranceterm($value=null,$type=null){
    if($type=='name'){
        $cond=" and insurance_term_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,insurance_term_id,insurance_term_name from m_insurance_terms_t where 1=1 $cond");
    return 	$sql;
    }
	
	public function deliveryterm($value=null,$type=null,$types=null){
    if($type=='name'){
        $cond=" and delivery_term_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,delivery_terms_id,delivery_term_name from m_delivery_terms_t where 1=1 $cond and source_type_id='".$types."'");
    return 	$sql;
    }
	
	public function freightterm($value=null,$type=null,$types=null){
    if($type=='name'){
        $cond=" and fob_point_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,frieghtterm_id,fob_point_name from m_frieghtterms_t where 1=1 $cond and source_type_id='".$types."'");
    return 	$sql;
    }
	
	public function freightcarrier($value=null,$type=null,$types=null){
    if($type=='name'){
        $cond=" and carrier_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,ar_frieghtcarriers_hdr_id,carrier_name from m_frieghtcarriers_hdr_t where 1=1 $cond and source_type_id='".$types."'");
  
	return 	$sql;
    }
	
	public function tdspercentage($value=null,$type=null){
    if($type=='name'){
        $cond=" and tds_percentage='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,tds_slab_id,tds_percentage from f_tds_slab_t where 1=1 $cond");
    return 	$sql;
    }
	
	public function tdsaccount($value=null,$type=null){
    if($type=='name'){
        $cond=" and concatenated_segments='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,f_account_structure_id,concatenated_segments from f_account_structure_t where 1=1 $cond");
    return 	$sql;
    }
	
	public function defaultbank($value=null,$type=null){
    if($type=='name'){
        $cond=" and bank_name='".$value."'";	 
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,bank_account_hdr_id,bank_name from f_bank_account_hdr_t where 1=1 $cond");
    return 	$sql;
    }
	
/*deepika purpose:to return status*/
	    public function getSuppliervalidate(Request$request) {
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND p_supplier_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }if (isset($_GET['type'])) {
            $type = 'SUPPLIERUPLOAD';
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
