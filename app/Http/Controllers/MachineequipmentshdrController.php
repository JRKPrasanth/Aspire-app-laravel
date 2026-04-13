<?php

namespace App\Http\Controllers;

use App\Machineequipmentshdr;
use App\Machineequipmentslines;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class MachineequipmentshdrController extends Controller{
	
   public $module="materialequipments";
	public function __construct()
	{
		$this->data=array();
		$this->model = new Machineequipmentshdr();
		$this->model=new Machineequipmentshdr;
		$this->submodel=new Machineequipmentslines;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
		$this->data['pageModule']='materialequipments';
		$this->table="w_machine_equipments_hdr_t";
		$this->subtable="w_machine_equipments_lines_t";
		$this->middleware('auth');
        $this->data['urlmenu']=$this->indexs(); 

	}/*end*/
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

      
		return view("materialequipments.table",$this->data);
    }

	/* purpose:to display data in jqgrid function */
   public function getmaterialequipmentsData()
	{
	   
		$wh='';


	    $loc="1";
        $compy=\Session::get('companyid');      
        $groupname=\Session::get('groupname');

	
		$SQL = "SELECT
    w_machine_equipments_hdr_t.`machine_equipments_hdr_id`,
    w_machine_hdr_t.machine_name,
    w_machine_equipments_hdr_t.remarks,
  	tb_users.first_name
FROM
    `w_machine_equipments_hdr_t`

 left join  w_machine_hdr_t ON(
 w_machine_hdr_t.machine_hdr_id=w_machine_equipments_hdr_t.machine_id
 ) left join tb_users on(tb_users.id=w_machine_equipments_hdr_t.created_by) where 1=1 $wh order by w_machine_equipments_hdr_t.machine_equipments_hdr_id DESC";


		$result = \DB::select( $SQL );
       return DataTables::of($result)->make(true);
	   
	}
	
    /*** Show the form for creating a new resource.  */
    public function create($id=null)
    {
			if(isset($_GET['pdt_id']))
			$pdt_id=$_GET['pdt_id'];
		else
			$pdt_id = 0;
		if(isset($id))
		{  
			$this->data['id'] = $id;
			$group=$this->groupname('FINISHED GOODS','group');
			$raw=$this->groupname('RAW MATERIALS','group');
			$cate_inter=$this->groupname('INTERMEDIATE','category');
			$cate_semi=$this->groupname('SEMI FINISHED GOODS','group');
			$this->data['group']=$group;
			$this->data['cate_semi']=$cate_semi;
			$this->data['pagemode'] = "edit";
			$table = \DB::table('w_machine_equipments_hdr_t')->where('machine_equipments_hdr_id',$id)->get();
			$this->data['row'] = $table[0];
			$this->data['organization_id']=$this->jcombo("m_organizations_t","organization_id","organization_name",$this->data['row']->organization_id); 
			 $this->data['created_by'] = $this->jCombo('tb_users','id','username',$table[0]->created_by);
	       
			 $this->data['machine_id'] = $this->jCombocomp('w_machine_hdr_t','machine_hdr_id','machine_name',$this->data['row']->machine_id);
			$linestable = \DB::table('w_machine_equipments_lines_t')->where('machine_equipments_hdr_id',$id)->get(); 
			$this->data['linedata'] = $linestable; 
			
			foreach($linestable as $key=>$value)
			{
				$this->data['linedata'][$key]->product_id=$this->jcustomselect('m_products_t','product_id','concatenated_product',$value->product_id,' and product_id='.$value->product_id);
			}
			//dd($this->data['linedata']);
			
			}  else{
			    $this->data['pagemode'] = "create";
		     	$this->modelname = new Machineequipmentshdr();
	        	$this->data['row']= (object)array();
			    $table = $this->modelname->getTableColumns();
		        foreach($table as $key=>$val)
		        {		
		          $this->data['row']->$val='';
		        }
	     	  $this->data['organization_id']=$this->jcombo("m_organizations_t","organization_id","organization_name",\Session::get('organization')); 
		
			 $this->data['linedata'] = array(); 
			
			 $this->data['machine_id'] = $this->jCombocomp('w_machine_hdr_t','machine_hdr_id','machine_name','');
			
			$group=$this->groupname('FINISHED GOODS','group');
			$raw=$this->groupname('RAW MATERIALS','group');
			$cate_inter=$this->groupname('INTERMEDIATE','category');
			$cate_semi=$this->groupname('SEMI FINISHED GOODS','group');
			$this->data['group']=$group;
			$this->data['cate_semi']=$cate_semi;
			 
			 $this->data['component_type'] = $this->jCombo('m_product_groups_t','product_group_id','group_name',"");
		 $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
		}
		$this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdgrpopt'] = $this->jqgridselect('m_product_groups_t','product_group_id','group_name');
		$this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','materialbom')->get();
		
        return view("materialequipments.form",$this->data);
    }

    /** Store a newly created data & update data in db  */
    public function save(Request $request)
    {

			$id='';
			$form = $request->all();
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
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
                     $lid=$this->submodel->subgridSave($lines_data,$id);
					if($_POST['machine_equipments_hdr_id']==""){
						$action="create";
						$msg="Saved Successfully";
					}else{
						$action="edit";
						$msg="Updated Successfully";
					}
					 $this->auditlog($id,'machinecapacity',$action,$data,'w_machine_equipments_hdr_t');
                     \DB::commit();
                     return response()->json(array('status' => 'success', 'message' =>$msg,'id' => $id,'lid' => $lid));
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

	

	 /** * Display the saved records   */
    public function show(Machineequipmentshdr $machineequipmentshdr,$id=null)
    {
     $data=Machineequipmentshdr::find($id);
		
          $this->data['machine_name']=$this->idname("machine_name","w_machine_hdr_t","machine_hdr_id",$data['machine_id']);   
          $this->data['organization_name']=$this->idname("organization_name","m_organizations_t","organization_id",$data['organization_id']);  
          $this->data['created_by']=$this->idname("username","tb_users","id",$data['created_by']);  
          $this->data['remarks']=$data['remarks'];  
            $a=\DB::table('w_machine_equipments_lines_t')->leftjoin('m_products_t','m_products_t.product_id',"=","w_machine_equipments_lines_t.product_id")->select('w_machine_equipments_lines_t.*','m_products_t.concatenated_product','m_products_t.product_id','m_products_t.product_code')->where('machine_equipments_hdr_id',$id)->get();

			$this->data['linesdata']=$a; 
        return view("materialequipments.view",$this->data);
    }
	/*end*/
	/*deepika purpose:to get group name or id based on type */	
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
/*end*/
/*deepika purpose:to get  category name using category id*/
	public function category($product_category_id=null){
	$category=\DB::table('m_product_category_t')->select('category_name')->where('product_category_id',$product_category_id)->get();
	if($category->isNotEmpty()){
	$category_name=$group[0]->category_name;
		return $category_name;
	}
		else
		{
		return 0;
		}
	}
	
 /*deepika purpose: get machine name based on product type*/
public function prdmachinedetails($id=null){
	$sql1=\DB::select('select w_machine_lines_t.*,w_machine_hdr_t.*,m_products_t.product_id,m_products_t.product_type_id from w_machine_hdr_t left join w_machine_lines_t on(w_machine_lines_t.machine_hdr_id=w_machine_hdr_t.machine_hdr_id)left join m_products_t on(m_products_t.product_type_id=w_machine_lines_t.product_type_id) where w_machine_lines_t.machine_hdr_id='.$id);
	$machineid="";
	foreach($sql1 as $key=>$value){
 $machineid.=$value->product_id.",";
	}
	$machineid1=rtrim($machineid,",");
	return $machineid1;
}
/*end*/  
}
