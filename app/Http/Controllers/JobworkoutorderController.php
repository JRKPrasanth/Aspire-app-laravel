<?php

namespace App\Http\Controllers;

use App\Jobworkoutorder;
use App\Jobworkoutorderlines;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JobworkoutorderController extends Controller
{
    public $module="jobworkoutorder";
	
		public function __construct()
	{
		$this->data=array();
                $this->data['urlmenu']=$this->indexs(); 
		$this->model 	= new Jobworkoutorder();
		$this->submodel = new Jobworkoutorderlines();
		$this->data['pageFormtype']='ajax';
                $this->data['pageModule']='jobworkoutorder';
                $this->data['pageMethod']=\Request::route()->getName();
                $this->table="w_jobworkoutorder_hdr_t";
		$this->subtable="w_jobworkoutorder_lines_t";
		$this->middleware('auth');

	}

     public function index()
	{
		
		return view('jobworkoutorder.table',$this->data);
	}

	
      public function getjobworkoutorderData()   {
		  
        $wh='';

		
	$sql="SELECT w_jobworkoutorder_hdr_t.jobworkoutorder_hdr_id,w_jobworkoutorder_hdr_t.joboutorder_no,m_subcontract_supplier_t.subcontract_name,
                w_jobworkoutorder_hdr_t.return_date,w_jobworkoutorder_hdr_t.remarks,tb_users.first_name
               from w_jobworkoutorder_hdr_t
               left join m_subcontract_supplier_t on(m_subcontract_supplier_t.subcontract_supplier_id=w_jobworkoutorder_hdr_t.subcontract_supplier_id)
               left join tb_users on(tb_users.id=w_jobworkoutorder_hdr_t.created_by) where 1=1 $wh";

           
	$result = \DB::select($sql);

	return DataTables::of($result)->make(true);	
		
    }
	

    
	/*Create Function*/
      public function create($id=null)
    {
	    $this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','workorder')->get();
		
	 if($id ==0)
		{          
		$this->modelname = new Jobworkoutorder();
		$this->data['row']= (object)array();
		$table = $this->modelname->getTableColumns();
		foreach($table as $key=>$val)
		{
		$this->data['row']->$val='';
		}
		$this->data['row']->joboutorder_status="INITIATED";
		$this->data['row']->return_date=date(\Session::get('p_date_format'));
	    $this->data['pagemode'] = "create";
		$this->data['id'] = '';
		$this->data['linedata'] = array();
		
		$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
        $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_number|subcontract_name','');
		$group1=$this->groupname('SEMI FINISHED GOODS','group');
		$group2=$this->groupname('FINISHED GOODS','group');
		
		$this->data['product_id'] = $this->jcustomproductselect('m_products_t','product_id','product_code|concatenated_product','','jobworkoutorder');
		$this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
		  $this->data['productid'] ="";
   	}
		else
		{
			$this->data['id'] = $id;
			$table = \DB::table('w_jobworkoutorder_hdr_t')->where('jobworkoutorder_hdr_id',$id)->get();
			$this->data['row'] = $table[0];
			$this->data['pagemode'] = "edit";
			$this->data['created_by'] = $this->jCombo('tb_users','id','username',$table[0]->created_by);
                        $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_number|subcontract_name',$table[0]->subcontract_supplier_id);
			$tablelines = \DB::table('w_jobworkoutorder_lines_t')->where('jobworkoutorder_hdr_id',$id)->get();
			$this->data['linedata'] = $tablelines;
            $this->data['productid'] = $this->jcustomproductselect('m_products_t','product_id','product_code|concatenated_product','','jobworkoutorder');
            if(count($this->data['linedata']) >= 1)
		    {
		     foreach ($this->data['linedata'] as $key => $value)
		      {
			 $group1=$this->groupname('SEMI FINISHED GOODS','group');
			 $group2=$this->groupname('FINISHED GOODS','group');
			 $this->data['linedata'][$key]->product_id=$this->data['product_id']= $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);
		     $this->data['linedata'][$key]->uomcode_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
		      }
		     }
			
			  	
		}
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
    	return view('jobworkoutorder.form',$this->data);
		}
		/*End*/
		/*Save Function*/
     public function save(Request $request) {
		
        $id = '';
        $data = $this->validatePost($request->all(), $this->table, 'header');
        $data['return_date']=date("Y-m-d",strtotime($data['return_date']));
        $lines_data = $this->validatePost($request->all(), $this->subtable, 'lines');
        /* karthigaa Purpose for Auto Number */
        if ($_POST['joboutorder_no'] == "") {
            $seqno = $this->Seqnoe('JWOO-', 'w_jobworkoutorder_hdr_t', '','jobworkoutorder_count');
            $data['joboutorder_no'] = $seqno[0];
            $data['jobworkoutorder_count'] = $seqno[1];
        } else {
            $seqno[0] = $_POST['joboutorder_no'];
        }
        /* End */
        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            \DB::commit();
            $edit_id= \DB::getPdo()->lastInsertId();
          $action="Create"; 
          /**Auditlog**/
            $this->auditlog($edit_id,"jobworkoutorder",$action,$_POST,"w_jobworkoutorder_hdr_t");
            return response()->json(array('status' => 'success', 'message' => 'JobWorkOutOrder Saved', 'id' => $id, 'lid' => $lid, 'auto_no' => $seqno[0]));
        } catch (\Illuminate\Database\QueryException$e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }            
/*End*/
/*View Function*/
public function show($id=null){
  if(isset($id)){
    $wo=\DB::table('w_jobworkoutorder_hdr_t')->select('w_jobworkoutorder_hdr_t.*','m_subcontract_supplier_t.subcontract_number','m_subcontract_supplier_t.subcontract_name','tb_users.username')
            ->leftjoin('m_subcontract_supplier_t','m_subcontract_supplier_t.subcontract_supplier_id','=','w_jobworkoutorder_hdr_t.subcontract_supplier_id')
            ->leftjoin('tb_users','tb_users.id','=','w_jobworkoutorder_hdr_t.created_by')
            ->where('jobworkoutorder_hdr_id',$id)->get();
//dd($wo);
    $this->data['joboutorder_no']=$wo[0]->joboutorder_no;
    $this->data['return_date']=$wo[0]->return_date;
    $this->data['joboutorder_status']=$wo[0]->joboutorder_status;
     $this->data['subcontract_name']=$wo[0]->subcontract_number."-".$wo[0]->subcontract_name;
        $this->data['username']=$wo[0]->username;
    $this->data['remarks']=$wo[0]->remarks;
       $vlinesdata = \DB::table('w_jobworkoutorder_lines_t')->leftjoin('m_products_t','m_products_t.product_id','=','w_jobworkoutorder_lines_t.product_id')->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','w_jobworkoutorder_lines_t.uom_code_id')->where('w_jobworkoutorder_lines_t.jobworkoutorder_hdr_id',$id)->get();
      $this->data['vlinesdata']=$vlinesdata;
  }  
return view('jobworkoutorder.view',$this->data);
  }
  /*End*/
  /*Delete Function*/
   public function delete($id=null)
    {
        $column = array('machine_id','machine_hdr_id');
        $table = array('w_machine_equipments_hdr_t','w_jobcard_hdr_t');   
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
        if($j==0){
            Machine::destroy($id);
            $query = \DB::table('w_machine_lines_t')->where('machine_hdr_id',$id)->delete();
            /**Auditlog**/
            $this->auditlog($id,"jobworkoutorder","delete","","w_machine_equipments_hdr_t");
        }
     return $j;
    
    }
    /*End*/
  	public function groupname($name = null,$type=null){
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
			
		}
	
	}
           public function uomcode($product_id = null){
		
		$uom = \DB::table('m_products_t')->select('trx_uom_id')->where ('product_id',$product_id)->get();
		if($uom->isNotEmpty())
		{
		
			$uomcode =$uom[0]->trx_uom_id;
		}
		else
		{
			$uomcode ='';
		}
		
		return $uomcode;
		
				
    }
	
    
}
