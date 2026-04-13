<?php

namespace App\Http\Controllers;

use App\Pricelistupload;
use App\Purchasepricelist;
use App\Purchasepricelistlines;
use Illuminate\Http\Request;
use Validator,
Input,
Redirect,
DB;
use Yajra\DataTables\DataTables;

class PricelistuploadController extends Controller
{
    public function __construct(){
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
		$this->data['pageMethod']=\Request::route()->getName();
        $this->table="i_pricelist_upload_t";
        $this->subtable="i_pricelist_lines_t";
        $this->pageModule="pricelistupload";
        $this->model=new Purchasepricelist;
        $this->submodel=new Purchasepricelistlines;
       
    }


     public function getPricelistuploadData(Request $request)
        {

		$wh='';


	if(isset($_GET['batchname'])){
	if($_GET['batchname']!=""){
	$wh= " and batch_name like '" . $_GET['batchname'] . "'";   
	}}


$SQL = "SELECT * from i_pricelist_upload_t where 1=1 $wh ORDER BY pricelist_upload_id desc";
		 
	$result = \DB::select($SQL);

	return DataTables::of($result)->make(true);
        }


        /*Main Page Load Function*/
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
                $filter = 'AND i_pricelist_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'PRICELISTUPLOAD';
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
        
        

        return view('pricelistupload.table', $this->data);
    }


    /*Create Function*/
   public function create($id=null)
    {
        $pricelistupload=Pricelistupload::find($id);

        if(isset($id)){
        $pricelistupload=Pricelistupload::find($id);
        }else{
            $pricelistuploads=\DB::connection()->getSchemaBuilder()->getColumnListing("i_pricelist_upload_t");
            $pricelistupload= array();
            foreach($pricelistuploads as $key=>$val)
            {
                $pricelistupload[$val]="";
            }
        }

        if($pricelistupload['pricelist_type'] == "Purchase"){
            $this->data['product_name']=$this->jcustomselect("m_products_t","concatenated_product","concatenated_product",$pricelistupload['product_name']," and product_group_id in('2','3')");    
//        dd($this->data['product_name']);
		}else{
            $this->data['product_name']=$this->jcustomselect("m_products_t","concatenated_product","concatenated_product",$pricelistupload['product_name']," and product_group_id in('1')");
      	    $productid=$this->productname($pricelistupload['product_name'],'name'); 
			$prdid=$productid[0]->product_id;
			$qohid=$this->salespricelistbatch($prdid,'','edit');
			   $this->data['batch_number'] = $this->jcustomselectcomp('i_qoh_detail_t','batch_number','batch_number',$pricelistupload['batch_number'],'and qoh_detail_id in ('.$qohid.')');

		}
        
        $this->data['pricelistuploaddata']=$pricelistupload;
        

        return view('pricelistupload.form',$this->data);
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

        $pricelistupload=new Pricelistupload();
           $batch_status='UPLOADED';
           
            $data['pricelist_name']=$_POST['pricelist_name'];
            $data['pricelist_type']=$_POST['pricelist_type'];
            $data['start_date']=$_POST['start_date'];
            $data['end_date']=$_POST['end_date'];
            $data['product_name']=$_POST['product_name'];
            $data['batch_number']=$_POST['batch_number'];
            $data['unit_price']=$_POST['unit_price'];
            $data['std_price']=$_POST['std_price'];
            $data['active']=$_POST['active'];
            $data['batch_name']=$_POST['batch_name'];
            $data['batch_date']=$_POST['batch_date'];
            $data['batch_status']= $batch_status;
            $data['batch_comments']="";
            $id=$_POST['pricelist_upload_id'];
            Pricelistupload::find($id)->update($data);
          return response()->json(array('status' => 'success', 'message' => 'your data Updated successfully!!','id'=>$id));
    }
/*End*/
   
/*deepika purpose:To Upload excel*/ 
public function Uploadexcel(Request $request){  

        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        // dd($path,$extension);
        $data =array();
        $return = 'pricelistupload';
        if($extension == "csv"){
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;
            // dd($filesop);
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
            {
                if($c>0){
                    // dd($filesop);
                        $pricedata[$c]['pricelist_name'] = strtoupper(trim($filesop[0]));
                        $pricedata[$c]['pricelist_type'] = ucwords(strtolower(trim($filesop[1])));
                        $pricedata[$c]['product_name'] = strtoupper(trim($filesop[2]));
                        $pricedata[$c]['batch_number'] = trim($filesop[3]);
                        $pricedata[$c]['unit_price'] = trim($filesop[4]);
                        $pricedata[$c]['std_price'] = strtoupper(trim($filesop[5]));
                        $pricedata[$c]['start_date'] = date('Y-m-d',strtotime(trim($filesop[6])));
                        $pricedata[$c]['end_date'] = date('Y-m-d',strtotime(trim($filesop[7])));
                        $pricedata[$c]['active'] = strtoupper(trim($filesop[8]));
                        $pricedata[$c]['batch_date'] = date('Y-m-d');
                        $pricedata[$c]['batch_status'] = "UPLOADED";
                        $pricedata[$c]['batch_name'] = $_POST['batch_name'];
                     
            //insert record from csv        
                }   
            $c = $c + 1;
            }

            $id = \DB::table('i_pricelist_upload_t')->insert($pricedata);   
        }else {

             return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
             //return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }
         
    // return response()->json(array('status' => 'success', 'message' => 'your data Updated successfully!!'));
   return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!')); 
         
         
}     
 /*Validation Function*/   
function uploadValidation($valModname, $valBatch, $status) {
        
    $status['status']='success';
    $status['message'] =' '; 
    $valname=urldecode($valModname);

    $sql = "select * from i_pricelist_upload_t where batch_status ='UPLOADED'  and batch_name='" . $valname . "'"; 
    $result_pr = \DB::select($sql); 
   
    if (!empty($result_pr)) 
    { 
        foreach($result_pr as $key=>$value)
        {
			$status['status']='success';
			$status['message'] =' ';
            if(empty($value->pricelist_name))
            {
                    $status['status'] = 'error';
                    $status['message'].= 'Pricelist Name Empty.. Please enter Pricelist name' . ' ,';
            }
			/* else{
                $pricename=$this->pricelistname($value->pricelist_name,'name');
                 if ($pricename[0]->cnt >0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Price list name already exist' . ' ,';
                }
            }*/
        
            if(empty($value->pricelist_type))
            {
                $status['status'] = 'error';
                $status['message'].= 'Pricelist type Empty.. Please enter Pricelist Type' . ' ,';
            }else{
                if($value->pricelist_type != "Purchase" && $value->pricelist_type != "Sales"){
                    $status['status'] = 'error';
                    $status['message'].= 'Price list type name not valid' . ' ,';
                }
            }

            if(!empty($value->product_name))
            {
                $productname = $this->productname($value->product_name,"name");
                 
                if($value->pricelist_type=="Purchase"){
                    if($productname[0]->group_name !="CONSUMABLES" && $productname[0]->group_name != "PACKING MATERIALS" && $productname[0]->group_name != "PROMOTIONAL ITEMS" && $productname[0]->group_name !="ACCESSORIES"  && $productname[0]->group_name !="SEMI FINISHED GOODS"){
						$status['status'] = 'error';
                        $status['message'].= 'Product Name should be RM or PM or Consumables or promotionl or accesssories or semi finished goods' . ' ,';
                    }
                }
                if($value->pricelist_type== "Sales"){
                    if($productname[0]->group_name !="FINISHED GOODS" && $productname[0]->group_name !="SAMPLE PRODUCTS" && $productname[0]->group_name != "PROMOTIONAL ITEMS" && $productname[0]->group_name !="ACCESSORIES" && $productname[0]->group_name !="CONSUMABLES" ){
                        $status['status'] = 'error';
                        $status['message'].= 'Product Name should be FG or Sample product' . ' ,';
                    }else if($productname[0]->group_name =="SEMI FINISHED GOODS" ){
                        $status['status'] = 'error';
                        $status['message'].= 'No Pricelist for Semi Finished Goods' . ' ,';
                    }
                }  
                if ($productname[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Productname not exist' . ' ,';
                }
            }else{
                 $status['status'] = 'error';
                 $status['message'].= 'Product Name Empty.. Please enter Product Name' . ' ,';
            }
			/* if($value->pricelist_type== "Sales"){
			 if($value->batch_number!=""){
          	$productname = $this->productname($value->product_name,"name");
				if($productname[0]->product_id!=null){
                $batchnumber = $this->salespricelistbatch($productname[0]->product_id,$value->batch_number,'valid');
           if ($batchnumber== 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Batch Number not exist' . ' ,';
                }
			}
			 }}*/
/*if(empty($value->unit_price)){
                 $status['status'] = 'error';
                 $status['message'].= 'Unit Price Empty.. Please enter Unit Price';
           }*/
            if($status['status'] == "error") 
            {
                $sql = "update i_pricelist_upload_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE pricelist_upload_id='" . $value->pricelist_upload_id . "' ";
                $result = \DB::update($sql);
                $status['message']='Uploaded data have some error';  

            } 
            else
            {
                $sql = "update i_pricelist_upload_t set batch_status ='VALIDATED' , batch_comments='' where pricelist_upload_id='" . $value->pricelist_upload_id . "' ";
                $result = \DB::update($sql);
                $status['status']=='success';
                $status['message']='Pricelist Data Validated successfully';
            }
        }
    // dd($status);
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
	$bname=urldecode($loadModname);
    $sql = "select * from i_pricelist_upload_t  where  batch_status ='VALIDATED'  and batch_name='" . $bname . "'";
      $result = \DB::select($sql); 
    
    $data=array();
    $data1=array();
    $loadid=array();    
    if(count($result)>0){ 
    
         foreach($result  as $key=>$value):
           $data['pricelist_name'] = $value->pricelist_name;
           $data['price_list_type'] =$value->pricelist_type;
           if($value->active == "YES" || $value->active == "Yes"){
                $data['active'] ="Yes";
           }else if($value->active == "NO" || $value->active == "No"){
                $data['active'] ="No";
           }
           
          $productname=$this->productname($value->product_name,'name');
           $productid=$this->productname($value->product_name,'name');
           if(count($productid)>0){
            $product_id=$productid[0]->product_id;
           }else{
               $product_id=0;
           }
		  $checkactive = \DB::table('i_pricelist_hdr_t')
                    ->select('*')->where('i_pricelist_hdr_t.pricelist_name','=',$value->pricelist_name)->get();

           $checkactive1 = \DB::table('i_pricelist_hdr_t')
                    ->leftjoin('i_pricelist_lines_t','i_pricelist_lines_t.pricelist_hdr_id','=','i_pricelist_hdr_t.pricelist_hdr_id')
                    ->select('*')->where('i_pricelist_hdr_t.pricelist_name','=',$value->pricelist_name)->where('i_pricelist_lines_t.product_id','=',$product_id)->get();
       if(count($checkactive1)>0){
		    $sql1="UPDATE i_pricelist_lines_t set active='Yes'  where pricelist_line_id =".$checkactive1[0]->pricelist_line_id;
                \DB::update($sql1); 
	   }      
           $data['organization_id'] =\Session::get('organization');
           $data['company_id'] =\Session::get('companyid');
           $data['location_id'] =\Session::get('location');
           $data['created_by'] =\Session::get('id');
           
           $data1['start_date'] =$value->start_date;
           $data1['end_date'] =$value->end_date;
           $data1['product_id']=$productname[0]->product_id;
           $data1['batch_number']=$value->batch_number;
           $data1['unit_price']=$value->unit_price;
           $data1['std_price']=$value->std_price;
           $data1['active']=$data['active'];
           $data1['removed_line_id']='';
           $data1['pricelist_line_id']='';
           $data1['line_no']=$key+1;
           
             // dd($data);

            \DB::beginTransaction();
            try{
                
                if(count($checkactive)==0){
                    $id = \DB::table('i_pricelist_hdr_t')->insertGetId($data);  
                } else{
					$id= $checkactive[0]->pricelist_hdr_id;
				}
    
                $data1['pricelist_hdr_id']=$id;
                $lid=$this->submodel->insertRow($data1);
               
                  \DB::commit();
                $sql="UPDATE i_pricelist_upload_t set batch_status='LOADED'  where pricelist_upload_id = $value->pricelist_upload_id";
                \DB::update($sql);
            }
            catch(\Illuminate\Database\QueryException $e)
            {
             
                $message = explode('(', $e->getMessage());
                $dbCode = rtrim($message[0], ']');
                $dbCode = trim($dbCode, '[');
               // dd($dbCode);
                 \DB::rollback();
                $status['status']='error';
                $status['message']=$dbCode;
                
                return $status;
            }
            
   
     endforeach;
        
             $status['status']='success';
             $status['message']='Pricelist Data Loaded Sucessfully';
            return $status;
       // return true;
            
    }else{
  $bname=urldecode($loadModname);
       $sql=\DB::select("select * from i_pricelist_upload_t where batch_name='".$bname."'");
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
             $status['message']='Pricelist Data already Loaded';
        return $status;
    }
    }


}
/*End*/

    public function productname($value=null,$type=null){
    if($type=='name'){
        $cond=' and concatenated_product="'.$value.'"';  
    }else{
        $cond="";
    }
    $sql=\DB::select("select count(*)  as cnt,m_products_t.product_id,m_products_t.product_group_id,m_product_groups_t.group_name,m_products_t.concatenated_product from m_products_t left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id) where 1=1 $cond");
 return  $sql;
    }
    
    /* code for getting product groupid */
    public function productgroup($name=null)
    {
       $sql = DB::select("select product_group_id from m_product_groups_t where group_name ='$name'");
        return $sql;
    }
	/*deepika purpose: get batch number*/
 
	public function salespricelistbatch($id=null,$batchnumber=null,$type=null){
		if($type=="valid"){
 $sql = DB::select("select qoh_detail_id,sum(qoh_trx_qty) as qoh from i_qoh_detail_t where product_id =".$id." and batch_number='$batchnumber' group by batch_number");
		}else{
	 $sql = DB::select("select qoh_detail_id,sum(qoh_trx_qty) as qoh from i_qoh_detail_t where product_id =".$id." group by batch_number");		
		}
if(count($sql)>0){
	$qohid="";
	foreach($sql as $key=>$val){
		if($val->qoh>0){
	$qohid.=$val->qoh_detail_id.",";	
	}
	}
	$qohid1=trim($qohid,",");
return $qohid1;	
}else{
return 0;	
}
}
   /*end*/ 
    public function pricelistname($value=null,$type=null){
        if($type=='name'){
            $cond=" and pricelist_name='".$value."'";    
        }else{
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,pricelist_hdr_id,pricelist_name from i_pricelist_hdr_t where 1=1 $cond");
        return  $sql;
    }
    /*deepika purpose:to return status*/
        public function getPricevalidate(Request$request) {
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND i_pricelist_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }if (isset($_GET['type'])) {
            $type = 'PRICELISTUPLOAD';
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
}
