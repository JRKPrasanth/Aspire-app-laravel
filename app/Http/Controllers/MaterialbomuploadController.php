<?php

namespace App\Http\Controllers;

use App\Materialbomupload;
use App\Materialbom;
use App\Materialbomlines;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;



class MaterialbomuploadController extends Controller
{

	public function __construct(){
        $this->data=array();
        
        $this->table="w_bomupload_t";
        $this->pageModule="Materialbomupload";
		$this->model=new Materialbom;
        $this->submodel=new Materialbomlines;
		$this->data['pageMethod']=\Request::route()->getName();
       
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

      		   $batch = $type = '';
        $this->data['status'] = $this->data['message'] = '';
		        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND w_bomupload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
		if (isset($_GET['type'])) {
            $type = 'BOMUPLOAD';
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

        return view('wbomupload.table', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function groupname($name = null,$type=null)
    {
        if($type=="group"){
        $group= \DB::table('m_product_groups_t')->where ('group_name',$name)->get();
            if($group->isNotEmpty())
        {
        
            $group_id=$group[0]->product_group_id;
            return $group_id;
        }
        else
        {
        return 0;
        }
            
        }else{
        $category=\DB::table('m_product_category_t')->where('category_name',$name)->get();  
        if($category->isNotEmpty())
        {
        
            $category=$category[0]->product_category_id;
            return $category;
        }
        else
        {
        return 0;
        }
        }
                
    }

    public function getprdtypeid($name =null){
        $prod = \DB::table('m_products_t')->where('m_products_t.concatenated_product',$name)
            ->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','m_products_t.primary_uom_id')
            ->select('m_uom_codes_t.uom_code')->get();
        $product_id ='';
        if(count($prod)>0){
            $product_id =$prod[0]->uom_code;
        }
        return $product_id;
    }

      public function create($id=null)
    {
        if(isset($id)){

      	     $bomupload=Materialbomupload::find($id);
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('w_bomupload_t')->where('bom_upload_id',$id)->get();
            $this->data['row'] = $table[0];
			if($table[0]->process=='Yes'){
			$this->data['row']->process=1;	
			}else{
			$this->data['row']->process="";	
			}
            $this->data['organization_id']=$this->jcombo("m_organizations_t","organization_name","organization_name",$table[0]->organization_id); 
            $this->data['bom_product']=$this->jcustomselect("m_products_t","concatenated_product","concatenated_product",$table[0]->bom_product," and product_group_id in(1,4)"); 
             $this->data['component_product']=$this->jcombo("m_products_t","concatenated_product","concatenated_product",$table[0]->component_product); 
            $group=$this->groupname('FINISHED GOODS','group');
            $pack=$this->groupname('PACKING MATERIALS','group');
            $raw=$this->groupname('RAW MATERIALS','group');
            $cate_inter=$this->groupname('INTERMEDIATE','group');
            $cate_semi=$this->groupname('SEMI FINISHED GOODS','group');
            $this->data['group']=$group;
            $this->data['cate_semi']=$cate_semi;
            $this->data['bom_uom_code'] = $this->jCombo('m_uom_codes_t','uom_code','uom_code',$table[0]->bom_uom_code);
            $this->data['component_uom_code'] = $this->jCombo('m_uom_codes_t','uom_code','uom_code',$table[0]->component_uom_code);
            $this->data['process_level'] = $this->jcustomselect('a_lookuplines_t','lookup_meaning','lookup_meaning',$table[0]->process_level," and lookup_type ='PROCESS_LEVEL'");
            $this->data['process_name'] = $this->jcustomselect('a_lookuplines_t','lookup_meaning','lookup_meaning',$table[0]->process_name," and lookup_type ='PROCESS_NAME'");
            $this->data['machine'] = $this->jCombocomp('w_machine_hdr_t','machine_name','machine_name',$table[0]->machine);

           
        }else{
            $bomuploads=\DB::connection()->getSchemaBuilder()->getColumnListing("w_bomupload_t");
            $bomupload= array();
            foreach($bomuploads as $key=>$val)
            {
                $bomupload[$val]="";
            }
        }

      
            $this->data['product_name']=$this->jcustomselect("m_products_t","concatenated_product","concatenated_product",$bomupload['product_name']," and product_group_id in('1')");
     
        
	    $this->data['bomuploaddata']=$bomupload;
		

        return view('wbomupload.form',$this->data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {

        $bomupload=new Materialbomupload();
           $batch_status='UPLOADED';
		   
            $data['bom_product']=$_POST['bom_product'];
            $data['bom_uom_code']=$_POST['bom_uom_code'];
            $data['bom_remarks']=$_POST['bom_remarks'];
            $data['component_product']=$_POST['component_product'];
            $data['component_uom_code']=$_POST['component_uom_code'];
            $data['component_qty']=$_POST['component_qty'];
            $data['comments']=$_POST['comments'];
            if(isset($_POST['process'])){
                $data['process']=$_POST['process'][0];
                $data['process_level']=$_POST['process_level'];
                $data['process_name']=$_POST['process_name'];
                $data['machine']=$_POST['machine'];
            }else{
                $data['process']='';
                $data['process_level']='';
                $data['process_name']='';
                $data['machine']='';
            }
            
        	$data['batch_name']=$_POST['batch_name'];
			$data['batch_date']=$_POST['batch_date'];
			$data['batch_status']= $batch_status;
			$data['batch_comments']='';
			$data['company_id']=\Session::get('companyid');
			$data['organization_id']=\Session::get('organization');
			$data['location_id']=\Session::get('location');
			$data['created_by']=\Session::get('id');
			$data['created_at']=date('Y-m-d H:i:s');
			$data['last_updated_by']=\Session::get('id');
			$data['updated_at']=date('Y-m-d H:i:s');
			$id=$_POST['bom_upload_id'];
       	Materialbomupload::find($id)->update($data);
		  return response()->json(array('status' => 'success', 'message' => 'your data Updated successfully!!','id'=>$id));
    }
	
	public function getMaterialbomuploadData(){

		$wh='';
	
	
	if(isset($_GET['batchname'])){
	if($_GET['batchname']!=""){
	$wh= " and batch_name like '" . $_GET['batchname'] . "'";	
	}}


	$SQL = "SELECT * from w_bomupload_t where 1=1 $wh";
	$result = \DB::select($SQL);
	return DataTables::of($result)->make(true);

		}
	

/* purpose:To Upload excel*/	
public function Uploadexcel(Request $request){  

        $path = $request->file('choosefile');
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$extension = $path->getClientOriginalExtension();
		// dd($path,$extension);
		$data =array();
        $return = 'bomupload';
        if($extension == "csv"){
            $file = $request->file('choosefile');
    		$handle = fopen($file, "r");
            $c = 0;
            // dd($filesop);
            while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
		    {
				if($c>0){
	       		    // dd($filesop);
                        $bomdata[$c]['bom_product'] =(trim($filesop[0]));
                        $bomdata[$c]['bom_uom_code'] = strtoupper(trim($filesop[1]));
					     $bomdata[$c]['process'] =ucfirst(trim($filesop[2]));
					     $bomdata[$c]['active'] =ucfirst(trim($filesop[3]));
                        $bomdata[$c]['bom_remarks'] = strtoupper(trim($filesop[4]));
					    $bomdata[$c]['component_product'] =(trim($filesop[5]));
                        $bomdata[$c]['component_uom_code'] =(trim($filesop[6]));
                        $bomdata[$c]['component_qty'] = (trim($filesop[7]));
                        $bomdata[$c]['process_level'] = strtoupper(trim($filesop[8]));
                        $bomdata[$c]['process_name'] = strtoupper(trim($filesop[9]));
					    $bomdata[$c]['machine'] = strtoupper(trim($filesop[10]));
					    $bomdata[$c]['comments'] = trim($filesop[11]);
                        $bomdata[$c]['company_id']=\Session::get('companyid');
						$bomdata[$c]['organization_id']=\Session::get('organization');
						$bomdata[$c]['location_id']=\Session::get('location');
						$bomdata[$c]['created_by']=\Session::get('id');
						$bomdata[$c]['created_at']=date('Y-m-d H:i:s');
						$bomdata[$c]['last_updated_by']=\Session::get('id');
						$bomdata[$c]['updated_at']=date('Y-m-d H:i:s');
                        $bomdata[$c]['batch_date'] = date('Y-m-d');
                        $bomdata[$c]['batch_status'] = "UPLOADED";
                        $bomdata[$c]['batch_name'] = $_POST['batch_name'];
                      
			//insert record from csv 		
                }   
			$c = $c + 1;
		    }

            $id = \DB::table('w_bomupload_t')->insert($bomdata);	
        }else {

			 return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
       }
 
   return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!')); 
         
         
}     
	
function uploadValidation($valModname, $valBatch, $status) {
        
    $status['status']='success';
    $status['message'] ='';

    $sql = "select * from w_bomupload_t where batch_status ='UPLOADED'  and batch_name='" . $valModname . "'"; 
    $result_pr = \DB::select($sql); 
    if (!empty($result_pr)) 
    { 
		foreach($result_pr as $key=>$value)
        {
            $status['status']='success';
            $status['message'] ='';
			if(empty($value->bom_product))
            {
                    $status['status'] = 'error';
                    $status['message'].= 'Bom Product Name Empty.. Please enter Bom Product name' . ' ,';
         	}else{
				$productname=$this->productname($value->bom_product,'name');
                if ($productname[0]->cnt <=0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Bom Product Not exist' . ' ,';
                }else{
                    if($productname[0]->product_group_id != 1 && $productname[0]->product_group_id != 4 ){
                        $status['status'] = 'error';
                        $status['message'].= 'Bom Product should be FG of SFG' . ' ,';
                    }
                }
			}
		
			if(empty($value->bom_uom_code))
            {
			    $status['status'] = 'error';
                $status['message'].= 'Bom Uom Code Empty.. Please enter Bom Uom Code' . ' ,';
			}else{
				$uomname=$this->uomname($value->bom_uom_code,'name');
                if ($uomname[0]->cnt <=0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Bom Uom Code not exist' . ' ,';
                }else{
                    if($productname[0]->primary_uom_id != $uomname[0]->uom_code_id){
                        $status['status'] = 'error';
                        $status['message'].= 'Bom Uom Code not for this product' . ' ,';
                    }
                }
            }

			/*if(!empty($value->component_product))
            {
                $compproductname = $this->productname($value->component_product,"name");
                if ($compproductname[0]->cnt <= 0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Component Product name not exist' . ' ,';
                }else{
                    if($compproductname[0]->product_group_id != 1 && $compproductname[0]->product_group_id != 4 && $compproductname[0]->product_group_id != 2 && $compproductname[0]->product_group_id != 3 ){
                        $status['status'] = 'error';
                        $status['message'].= 'Component Product should be RM or PM or FG or SFG' . ' ,';
                    }
                }
			}else{
				 $status['status'] = 'error';
                 $status['message'].= 'Component Product Name Empty.. Please Enter Component Product Name' . ' ,';
			}
           
		    if(empty($value->component_uom_code))
            {
			    $status['status'] = 'error';
                $status['message'].= 'Component Uom Code Empty.. Please enter Component Uom Code' . ' ,';
			}else{
				$uomname1=$this->uomname($value->component_uom_code,'name');
                if ($uomname1[0]->cnt <=0) {
                    $status['status'] = 'error';
                    $status['message'].= 'Component Uom Code not exist' . ' ,';
                }else {
                    if($compproductname[0]->primary_uom_id != $uomname1[0]->uom_code_id){
                        $status['status'] = 'error';
                        $status['message'].= 'Component Uom Code not for this product' . ' ,';
                    }
                }
            }*/
            if($value->process == "Yes"){
                if(empty($value->process_level))
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Process Level Empty.. Please enter Process Level' . ' ,';
                }else{
                    $processlevel=$this->processlevel($value->process_level,'name');
                   if ($productname[0]->cnt <=0) {
                        $status['status'] = 'error';
                        $status['message'].= 'Process Level not exist' . ' ,';
                    }
                }
                if(empty($value->process_name))
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Process name Empty.. Please enter Process name' . ' ,';
                }else{
                    $processname=$this->processname($value->process_name,'name');
                   if ($processname[0]->cnt <=0) {
                        $status['status'] = 'error';
                        $status['message'].= 'Process name not exist' . ' ,';
                    }
                }
                
                if(empty($value->machine))
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Machine name Empty.. Please enter Machine name' . ' ,';
                }else{
                    $machinename=$this->machine($value->machine,'name');
                   if ($machinename[0]->cnt <=0) {
                        $status['status'] = 'error';
                        $status['message'].= 'Machine name not exist' . ' ,';
                    }
                }
            }
			
			
			if($status['status'] == "error") 
			{
				$sql = "update w_bomupload_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE bom_upload_id='" . $value->bom_upload_id . "' ";
				$result = \DB::update($sql);
				$status['message']='Uploaded have some error';  
			} 
			else
			{
    			$sql = "update w_bomupload_t set batch_status ='VALIDATED' , batch_comments='' where bom_upload_id='" . $value->bom_upload_id . "' ";
                $result = \DB::update($sql);
                $status['status']=='success';
                $status['message']='Material Bom Data Validated successfully';
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



public function LoadMaster($loadModname, $valBatch, $status){
    
    $status['status'] = '';
    $status['message'] = '';
    $sql = "select * from w_bomupload_t  where  batch_status ='VALIDATED'  and batch_name='" . $loadModname . "'";
	  $result = \DB::select($sql); 
	
    $data=array();
    $data1=array();
    $loadid=array();	
    if(count($result)>0){ 
    
         foreach($result  as $key=>$value):
           $data['organization_id'] =\Session::get('organization');
		   $data['company_id'] =\Session::get('companyid');
		   $data['location_id'] =\Session::get('location');
		   $data['created_by']=\Session::get('id');
			$data['created_at']=date('Y-m-d H:i:s');
			$data['last_updated_by']=\Session::get('id');
			$data['updated_at']=date('Y-m-d H:i:s');
			$data['active']='Yes';
		   $productname=$this->productname($value->bom_product,'name');
		   $uomname=$this->uomname($value->bom_uom_code,'name');
		   $compproductname=$this->productname($value->component_product,'name');
		  // dd($compproductname);
		   $compuomname=$this->uomname($value->component_uom_code,'name');
		  // dd($compuomname);
		   $machine=$this->machine($value->machine,'name');
           $data['assembly_product_id'] =$productname[0]->product_id;
           $data['uom_code_id'] =$uomname[0]->uom_code_id;
           if($value->process=="Yes" && $compproductname[0]->product_id ==null && $compuomname[0]->uom_code_id==null){
		    $data1['component_product_id'] ='';
           $data1['component_uom_code_id'] ='';
           }else{
           $data1['component_product_id'] =$compproductname[0]->product_id;
           $data1['component_uom_code_id'] =$compuomname[0]->uom_code_id;       
           }
		   $data1['machine_name']=$machine[0]->machine_hdr_id;
		   $data1['component_qty']=$value->component_qty;
		   $data1['process_level']=$value->process_level;
		if($value->process=="Yes"){
			$data['process']=1;
		}else{
			$data['process']=0;
		}
		   
		   $data1['process_name']=$value->process_name;
		   $data1['comments']=$value->comments;
	       $data1['removed_line_id']='';
		   $data1['material_bom_line_id']='';
		   $data1['line_no']=$key+1;
            $data1['organization_id'] =\Session::get('organization');
		   $data1['company_id'] =\Session::get('companyid');
		   $data1['location_id'] =\Session::get('location');
		  	$data1['created_by']=\Session::get('id');
			$data1['created_at']=date('Y-m-d H:i:s');
			$data1['last_updated_by']=\Session::get('id');
			$data1['updated_at']=date('Y-m-d H:i:s');
             \DB::beginTransaction();
            try{
    			 $bomdata = \DB::table('m_material_bom_hdr_t')
                ->where('m_material_bom_hdr_t.assembly_product_id','=',$productname[0]->product_id)->get();
				if(count($bomdata)==0){
                $id = \DB::table('m_material_bom_hdr_t')->insertGetId($data);
					$data1['material_bom_hdr_id']=$id;
				}else{
				$data1['material_bom_hdr_id']=$bomdata[0]->	material_bom_hdr_id;
				}
				
                $lid=$this->submodel->insertRow($data1);
               
				  \DB::commit();
                $sql="UPDATE w_bomupload_t set batch_status='LOADED'  where bom_upload_id = $value->bom_upload_id";
                \DB::update($sql);
            }
            catch(\Illuminate\Database\QueryException $e)
            {
             
                $message = explode('(', $e->getMessage());
                $dbCode = rtrim($message[0], ']');
                $dbCode = trim($dbCode, '[');
               
				 \DB::rollback();
                $status['status']='error';
                $status['message']=$dbCode;
				
                return $status;
            }
            
   
     endforeach;
		
		     $status['status']='success';
             $status['message']='Bom Product Data Loaded Sucessfully';
            return $status;
        return true;
            
    }else{

       $sql=\DB::select("select * from w_bomupload_t where batch_name='".$loadModname."'");
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
             $status['message']='Bom Product Data already Loaded';
        return $status;
    }
    }


}
    
    public function productname($value=null,$type=null){
        if($type=='name'){
            $cond=" and concatenated_product='".$value."'";	 
        }else{
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,product_id,concatenated_product,product_group_id,primary_uom_id from m_products_t where 1=1 $cond");
        return 	$sql;
    }
	
	public function uomname($value=null,$type=null){
        if($type=='name'){
            $cond=" and uom_code='".$value."'";	 
        }else{
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,uom_code_id,uom_code from m_uom_codes_t where 1=1 $cond");
        return 	$sql;
    }
	public function processlevel($value=null,$type=null){
        if($type=='name'){
            $cond=" and lookup_code='".$value."'";	 
        }else{
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,lookup_code,lookup_type from a_lookuplines_t where 1=1 $cond");
        return 	$sql;
    }
	public function processname($value=null,$type=null){
        if($type=='name'){
            $cond=" and lookup_code='".$value."'";	 
        }else{
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,lookup_code,lookup_type from a_lookuplines_t where 1=1 $cond");
        return 	$sql;
    }
	public function machine($value=null,$type=null){
        if($type=='name'){
            $cond=" and machine_name='".$value."'";	 
        }else{
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,machine_hdr_id,machine_name from w_machine_hdr_t where 1=1 $cond");
        return 	$sql;
    }
		/*deepika purpose:to return status*/
	    public function getBomvalidate(Request $request) {
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND i_pricelist_upload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }if (isset($_GET['type'])) {
            $type = 'BOMUPLOAD';
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
