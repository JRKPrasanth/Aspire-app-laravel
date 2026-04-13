<?php

namespace App\Http\Controllers;

use App\Purchaserequisition;
use App\Purchaserequisitionlines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class PurchaserequisitionController extends Controller
{
     public $module="purchaserequisition";
	public function __construct()
	{
        $this->data=array();
		$this->table="p_requisition_hdr_t";
		$this->subtable="p_requisition_lines_t";
		$this->pageModule="purchaserequisition";
		$this->model=new Purchaserequisition;
		$this->submodel=new Purchaserequisitionlines;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
                
                    $this->data=array(
                            'pageModule'=> 'purchaserequisition',
                            'pageUrl'	=>  url($this->data['pageMethod']),
                                'pageMethod'=>$this->data['pageMethod']
                 );
                    $this->data['urlmenu']=$this->indexs(); 
		
	$this->modelname = new Purchaserequisition();
	$this->data['pageFormtype']='ajax';
	if($this->data['pageMethod']=='purchaserequisitionapprove' || $this->data['pageMethod']=="purchasecopyrequisition")	
	{
		$this->data['status']="INITIATED";
	}
       elseif($this->data['pageMethod']=="purchaserequisition")
	{
			$this->data['status']="";
	}
	else
	{
			$this->data['status']="APPROVED";
	}
}
	
    /* Purpose For :Index Function to Call Table Blade*/	
	
     public function index(){
        $this->data['opt'] = $this->jqgridselect('hr_employee_t', 'employee_id', 'first_name');
        $table = \DB::table('p_requisition_hdr_t')->get();
	$this->data['datas'] = $table;
	$this->data['pageMethod']=="purchaserequisition";
		 
     return view('purchaserequisition.table',$this->data);
    }
	
    /* purpose for Display Data in JQgrid function */
  	public function getPurchaserequisitionData(){
	 
		$wh='';
		$col_name='';
                if($_GET['page_status'] == 'purchasecopyrequisition')
                {
                   $wh .=" and (p_requisition_hdr_t.requisition_status='APPROVED' or p_requisition_hdr_t.requisition_status='INITIATED')";
                   $col_name="requisition_status";
		            $op="IN";
		            $status_val="('APPROVED','INITIATED')";
                }
               
                else if($_GET['page_status'] == 'purchaserequisitionapprove')
                {
                   $wh .=" and (p_requisition_hdr_t.requisition_status='INITIATED')";
                   $col_name="requisition_status";
		            $op="=";
		            $status_val="'INITIATED'";
                }
               else if($_GET['page_status'] == 'purchaseenquirytorequestion'||$_GET['page_status'] == 'purchaserequestiontoquote'||$_GET['page_status'] == 'purchaserequestiontopo')
                {
                   $wh .=" and (p_requisition_hdr_t.requisition_status='APPROVED')";
                   $col_name="requisition_status";
		            $op="=";
		            $status_val="'APPROVED'";
                }
                else if($_GET['page_status'] == 'purchaserequisition'){
                         $wh .=" and (p_requisition_hdr_t.requisition_status='APPROVED' or p_requisition_hdr_t.requisition_status='REJECTED' or p_requisition_hdr_t.requisition_status='INITIATED' or p_requisition_hdr_t.requisition_status='DRAFT')";
                $col_name="requisition_status";
		            $op="IN";
		            $status_val="('APPROVED','REJECTED','INITIATED','DRAFT')";
                    
                }


                $compy=\Session::get('companyid');		
				$groupname=\Session::get('groupname');
	
		
			if($col_name != ''){
    $wh.=$grid_data=$this->grid_statuscheck('p_requisition_hdr_t','requisition_date',$col_name,$op,$status_val);
    } else {
        $wh.=$grid_data=$this->grid_check('p_requisition_hdr_t','requisition_date');
    }

		
		if($_GET['page_status'] == 'purchaserequestiontopo'){
			
			 $SQL = "SELECT
                        p_requisition_hdr_t.requisition_hdr_id as requisition_hdr_id,
                        p_requisition_hdr_t.requisition_no as requisition_no,
                        p_requisition_hdr_t.requisition_date as requisition_date,
                        p_requisition_hdr_t.requisition_status as requisition_status,
                        p_requisition_hdr_t.requisition_source,
                        hr_employee_t.first_name,
                        m_projects_t.project_name,
                        p_requisition_hdr_t.remarks as remarks
                        FROM `p_requisition_hdr_t`
                        left join hr_employee_t on(
                        hr_employee_t.employee_id=p_requisition_hdr_t.`requestor_id`)
                        left join m_projects_t on(
                        m_projects_t.project_id=p_requisition_hdr_t.`project_id`)where p_requisition_hdr_t.convertpo_status=0 and 1=1 $wh";

		}else{
			  $SQL = "SELECT
                        p_requisition_hdr_t.requisition_hdr_id as requisition_hdr_id,
                        p_requisition_hdr_t.requisition_no as requisition_no,
                        p_requisition_hdr_t.requisition_date as requisition_date,
                        p_requisition_hdr_t.requisition_status as requisition_status,
                        p_requisition_hdr_t.requisition_source,
                        hr_employee_t.first_name,
                        m_projects_t.project_name,
                        p_requisition_hdr_t.remarks as remarks
                        FROM `p_requisition_hdr_t`
                        left join hr_employee_t on(
                        hr_employee_t.employee_id=p_requisition_hdr_t.`requestor_id`)
                        left join m_projects_t on(
                        m_projects_t.project_id=p_requisition_hdr_t.`project_id`)
						where 1=1 $wh";

		}
              
                        
		$result = \DB::select( $SQL );
		return DataTables::of($result)->make(true);
		
	}
	
    /* Purpose For :Create Function to Call Form Blade*/	
  	public function create($id=null,$requisition_status=null)
	{ 		
      	$this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','purchaserequisition')->get(); 
      	if($requisition_status !='COPYREQUISITION' && $requisition_status !='Indent' ) { 		
	if($id == "0"){ 
                    $this->data['row']= (object) array();
	            $this->data['row']->requisition_hdr_id = "";
	            $this->data['row']->requisition_date = date('Y-m-d');
	            $this->data['row']->requisition_no = "";
	            $this->data['row']->requisition_status = "DRAFT";
	            $this->data['row']->requisition_source = "INTERNAL";
                    $this->data['row']->remarks = "";
                    $this->data['row']->reference_no ="";
                    $this->data['id'] = '';
                    
		    $this->data['requestor_id'] = $this->jCombocomp('hr_employee_t','employee_id','first_name | last_name',\Session::get('emp_id'));
                    $this->data['project_id'] = $this->jCombo('m_projects_t','project_id','project_name','');
	            $this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',''); 
	            $this->data['created_by'] = $this->jCombo('tb_users','id','username',''); 
                    $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '','purchaserequisition');
                    $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
	            $this->data['supnameopt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
		    $this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
	            $this->data['group']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
		    $this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
	            $this->data['linedata'] = array(); 
	} 
        else
        {
                    $this->data['id'] = $id;
                    $table = \DB::table('p_requisition_hdr_t')->where('requisition_hdr_id',$id)->get(); 
                    $this->data['row'] = $table[0];
                    $tablelines = \DB::table('p_requisition_lines_t')->where('requisition_hdr_id',$id)->get();
                    $this->data['linedata'] = $tablelines;
                    $this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',$table[0]->organization_id);
                    $this->data['created_by'] = $this->jCombo('tb_users','id','username',$table[0]->created_by);
                    $this->data['requestor_id'] = $this->jCombocomp('hr_employee_t','employee_id','first_name | last_name',$table[0]->requestor_id);
                    $this->data['project_id'] = $this->jCombo('m_projects_t','project_id','project_name',$table[0]->project_id);
                    $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '','purchaserequisition');
                    $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
                    $this->data['supnameopt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
                    $this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
                    $this->data['group']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
                    $this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
                    $this->data['requisition_status'] = $table[0]->requisition_status;  
	}
	    }
            else if($requisition_status =='Indent') 
            { 
                $this->data['row']= (object) array();
                $this->data['row']->requisition_hdr_id = "";
                $this->data['row']->requisition_date = date('Y-m-d');
                $this->data['row']->requisition_no = "";
                $this->data['row']->requisition_status = "DRAFT";
                $this->data['row']->requisition_source = "INDENT";
                $this->data['row']->considerpo ="";
		$indenthdr=\DB::select('select * from w_requisition_indent_hdr_t where w_requisition_indent_hdr_id='.$_GET['reqindenthdrid']);
                
                $this->data['row']->reference_no = $indenthdr[0]->indent_no;
		$this->data['row']->remarks = "";
		$this->data['id'] = '';
                $this->data['requestor_id'] = $this->jCombocomp('hr_employee_t','employee_id','first_name',\Session::get('emp_id'));
		$this->data['project_id'] = $this->jCombo('m_projects_t','project_id','project_name',$indenthdr[0]->project_id);
                $this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',''); 
                $this->data['created_by'] = $this->jCombo('tb_users','id','username',''); 
                $this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product','');
		$this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
                $this->data['supnameopt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
		 $this->data['return_url']="productionindent";
		 $sql1=\DB::SELECT("SELECT w_requisition_indent_lines_t.qty,w_requisition_indent_lines_t.product_id,w_requisition_indent_lines_t.uom_code_id FROM w_requisition_indent_lines_t LEFT JOIN m_products_t ON m_products_t.product_id=w_requisition_indent_lines_t.product_id  WHERE w_requisition_indent_lines_t.w_requisition_indent_hdr_id=".$_GET['reqindenthdrid']." AND (m_products_t.product_group_id=2 or  m_products_t.product_group_id=3)");
		$k=0;
                $this->data['linedata']=$sql1;
                
                foreach($this->data['linedata'] as $key=>$value)
		{
		$this->data['linedata'][$k]=(object) array();
		$this->data['linedata'][$k]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', $value->product_id,'purchaserequisition');	
		$this->data['linedata'][$k]->uom_code_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);

		$qty=$value->qty;
		$qty1=round($qty,\Session::get('decimal'));
		$this->data['linedata'][$k]->qty=$qty1;
		$this->data['linedata'][$k]->indent_qty=$qty1;
		$this->data['linedata'][$k]->need_by_date=date('Y-m-d');
	    $comp=\Session::get('companyid');		
		$qoh1=\DB::Select("select sum(f.qty-f.qtyy) as qoh_qty,f.qtyy as resqty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='".$value->product_id."' and company_id='".$comp."' GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='".$value->product_id."' and company_id='".$comp."' GROUP by product_id)f");
		if(count($qoh1)>0){

		if($qoh1[0]->qoh_qty>0)
		{
					$this->data['linedata'][$k]->qoh=$qoh1[0]->qoh_qty;

				}else{
					$this->data['linedata'][$k]->qoh=0;
				}
	}else{
$this->data['linedata'][$k]->qoh=0;
	}
		$k++;
		}
		//dd($this->data);
            } 
	    else 
            {  
                $this->data['id'] = $id;
                $table = \DB::table('p_requisition_hdr_t')->where('requisition_hdr_id',$id)->get(); 
                $this->data['row'] = $table[0];
                $tablelines = \DB::table('p_requisition_lines_t')->where('requisition_hdr_id',$id)->get(); 
                $this->data['linedata'] = $tablelines;			
                $this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',$table[0]->organization_id);
	        $this->data['created_by'] = $this->jCombo('tb_users','id','username',$table[0]->created_by);
	        $this->data['requestor_id'] = $this->jCombocomp('hr_employee_t','employee_id','first_name',$table[0]->requestor_id);
		$this->data['project_id'] = $this->jCombo('m_projects_t','project_id','project_name',$table[0]->project_id);
	        $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '','purchaserequisition');
	        $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
		$this->data['supnameopt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
	        $this->data['group']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
	        $this->data['requisition_status'] = $table[0]->requisition_status;
            }
            
            	if(count($this->data['linedata']) >= 1 && $requisition_status !='Indent')
		{
		foreach ($this->data['linedata'] as $key => $value) 
                {

//                        $this->data['linedata'][$key]->product_id =  $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', $value->product_id,'purchaserequisition');	
//                        $this->data['linedata'][$key]->uom_code_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);	
        if($value->product_id!='0'){
                $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);

                    }else{
$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product','','');

                   }
                 if($value->uom_code_id!='0'){
                    $this->data['linedata'][$key]->uom_code_id =   $this->jcustomselect('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id,'and uom_code_id='.$value->uom_code_id);
                }else{
                    $this->data['linedata'][$key]->uom_code_id =   $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
                }
                        if($this->data['linedata'][$key]->qty== 0)
                        {
                                $this->data['linedata'][$key]->qty= "";
                        }
                }
            /*KARTHIGAA Purpose For COPY REQUISITION*/  
                    if($requisition_status=='COPYREQUISITION'){
                        $this->data['linedata'][$key]->requisition_line_id= "";
                        $this->data['linedata'][$key]->requisition_hdr_id= "";
                        $this->data['row']->requisition_hdr_id='';
                        $this->data['copy_requisition_no']=$this->data['row']->requisition_no;
                        $this->data['row']->requisition_no='';
                    }
                 /*End*/
		}             
        if(isset($_GET['approve_status']))
	{
	      	$this->data['return_url']="purchaserequisitionapprove";
        }  
        else
		{
            $this->data['return_url']="purchaserequisition";  
        }
             /*KARTHIGAA Purpose For COPY REQUISITION*/  
        if($requisition_status=='COPYREQUISITION')
        {
            $this->data['return_url']="purchasecopyrequisition";
        }else if($requisition_status=='Indent'){
		$this->data['return_url']="productionindent";
	}
                /*END*/ 
	
		return view('purchaserequisition.form',$this->data);

    }
  	

 /*Karthigaa purpose for Save function*/   
         public function save(Request $request){ 
                        $id='';
			$data = $this->validatePost($request->all(),$this->table,'header');	
			$lines_data = $this->validatePost($request->all(),$this->subtable,'lines');	
                         /*karthigaa Purpose for Auto Number*/
                         if ($_POST['requisition_no'] =="") 
				{
					$seqno=$this->Seqnoe('REQ-','p_requisition_hdr_t','','poreq_count');
					$data['requisition_no'] = $seqno[0];
                                        $data['poreq_count'] = $seqno[1];
				}
				else
				{
					$seqno[0] = $_POST['requisition_no'];
				}
                         /*End*/  
                       \DB::beginTransaction();
                     try
			{
				$id=$this->model->insertRow($data);
				$lid=$this->submodel->subgridSave($lines_data,$id);
				
				\DB::table('w_requisition_indent_hdr_t') ->where('indent_no',$_POST['reference_no'])->update(['indent_status' => 'CLOSED']);
				if($_POST['requisition_status']=="APPROVED")
				{
                                $save_s="Approved";
				}
				else if($_POST['requisition_status']=="REJECTED"){
					$save_s="Rejected";
				}
				 else if($_POST['requisition_hdr_id']==""){
				 	$save_s="Saved";
				 }
				else
				{
					$save_s="Updated";
				}
				
				\DB::commit();
                                /**Auditlog**/
                                if($_POST['requisition_hdr_id']==""){
                                            $action="create";
                                            }else{
                                            $action="edit";
                                            }
                                 $this->auditlog($id,"purchaserequisition",$action,$_POST,"p_requisition_hdr_t");
				if($_POST['requisition_status']== "INITIATED"){
					$notifcation = 'Purchase Requisition '.$seqno[0].' Initiated';
                                        $send_notification = $this->sendPopUpHomeNoty($id,"PENDING REQUSITION",$notifcation,'purchaserequisitionapprove');
		        }

		        if($_POST['requisition_status']=="APPROVED"){
		        	\DB::table('notifications_t')->where('reference_source_id',$_POST['requisition_hdr_id'])->where('reference_source','PENDING REQUSITION')->update(['read/unread' => 'read']);
		        }
		        
				return response()->json(array('status' => 'success', 'message' => 'Purchase Requisition '.$save_s.' Successfully','id' => $id,'lid' => $lid,'auto_no'=>$seqno[0]));
			}
			catch (\Illuminate\Database\QueryException$e)
			{  
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
				// dd($dbCode);
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}	

        }

 /*Karthigaa purpose for Display hdr & Lines View function*/ 
  public function show(request $request,$id=null)
    {
        if(isset($id))
        {
          $vdata=\DB::table('p_requisition_hdr_t')->leftjoin('m_organizations_t','m_organizations_t.organization_id','=','p_requisition_hdr_t.organization_id')
                                                  ->leftjoin('m_projects_t','m_projects_t.project_id','=','p_requisition_hdr_t.project_id')
                                                  ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','p_requisition_hdr_t.requestor_id')
                                                    ->leftjoin('tb_users', 'tb_users.id', '=', 'p_requisition_hdr_t.created_by')
                                                  ->where('requisition_hdr_id',$id)->get(); 
              
          $this->data['requisition_no']=$vdata[0]->requisition_no; 
          $this->data['requisition_date']=date('d-m-Y',strtotime($vdata[0]->requisition_date)); 
          $this->data['requisition_status']=$vdata[0]->requisition_status;  
          $this->data['requisition_source']=$vdata[0]->requisition_source;  
          $this->data['created_by']=$vdata[0]->username;
          $this->data['requestor_id']=$vdata[0]->first_name;  
          $this->data['project_name']=$vdata[0]->project_name;  
          $this->data['organization_name']=$vdata[0]->organization_name;  
          $this->data['remarks']=$vdata[0]->remarks;  
            $a=\DB::table('p_requisition_lines_t')->where('requisition_hdr_id',$id)->get();
          
         $vlinesdata = \DB::table('p_requisition_lines_t')->leftjoin('m_products_t','m_products_t.product_id','=','p_requisition_lines_t.product_id')
                                                      -> leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','p_requisition_lines_t.uom_code_id')
                                                      ->where('p_requisition_lines_t.requisition_hdr_id',$id)->get();
            
            $this->data['vlinesdata']=$vlinesdata; 
              $this->data['uom_code']=$vlinesdata[0]->uom_code;  
              
               $this->data['return_url']=$_GET['return'];  
            return view('purchaserequisition.view',$this->data);
            
        }
    }
/*Karthigaa purpose for Delete function*/ 
      public function delete(Request $request,$id=null)
	{ 
		$count=0;
		$queryquote = \DB::table('p_quotation_hdr_t')->where('reference_id',$id)->count(); 
		if($queryquote >=1)
		{
		 $count++;
		}
		if($count <= 0)
		{
			Purchaserequisition::destroy($id);
			$query = \DB::table('p_requisition_lines_t')->where('requisition_hdr_id',$id)->delete();
                     /**Auditlog**/
                    $action = "Delete";
                    $this->auditlog($id,"purchaserequisition",$action,$id,"p_requisition_hdr_t");
			if($query)
			{
				return 0;
			}
			else
			{
				return 1;
			}
		}
		else
		{
			return 2;
		}

	}  
      
        /*karthigaa purpose for load uom code based on product */
         public function porequom($product_id = null){
		$query = \DB::table('m_products_t')->where ('product_id',$product_id)->get();
		if(count($query)>0)
		{
			$uom_code=$query[0]->primary_uom_id;
		}
                return $uom_code;
        }
        
        public function considerpoqty($pid=null){
		$sql=\DB::select("select sum(ABS(balance_qty))as balqty from p_requisition_lines_t where product_id=".$pid." and status=0");
		if(isset($sql)){
		return $sql[0]->balqty;
		}else{
			return 0;
		}
		
	}
	
}
