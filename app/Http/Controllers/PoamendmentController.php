<?php

namespace App\Http\Controllers;
use App\Purchaseorder;
use App\Purchaseorderlines;
use App\Poamendment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Validator,DB;
use App\Http\Controllers\Controller;

class PoamendmentController extends Controller
{
    public $module="purchaseorder";

	public function __construct()
	{
            $this->data=array();
            $this->data['urlmenu']=$this->indexs(); 
             $this->table="p_po_hdr_t";
		$this->subtable="p_po_lines_t";
		$this->pageModule="purchaseorder";
		$this->model=new Purchaseorder;
		$this->submodel=new Purchaseorderlines;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();

		      $this->data['pageFormtype']='ajax';
                $this->data['pageMethod']=='purchasecopypo';
                $this->data['pageMethod']=="poapproval" ;
                  $this->data['pageMethod']=="pocancellation" ;
                   $this->data['pageMethod']=="purchaseorder" ;
                    $this->data['pageMethod']=="purchasequtoetopocreate";

		$this->data=array(
                    'pageModule'=> 'purchaseorder',
                    'pageUrl'	=>  url('purchaseorder'),
                     'pageMethod'=>$this->data['pageMethod']

                  );   
                	       if($this->data['pageMethod']=='poapproval')
                            {
                                $this->data['status']="INITIATED";
                                $this->data['pageMethod']=='poapproval';
                            }
                            else if($this->data['pageMethod']=="purchasecopypo")
                            {
                                 $this->data['status']="INITIATED";
                                $this->data['pageMethod']=='purchasecopypo';

                            }
                            else if($this->data['pageMethod']=="purchaseorder" )
                            {
                                    $this->data['status']="";
                                  $this->data['pageMethod']=='purchaseorder';
                            }
                            else if($this->data['pageMethod']=="poamendment" )
                            {
                                    $this->data['status']="APPROVED";
                                  $this->data['pageMethod']=='purchaseorder';
                            }
                            else
                            {
                                    $this->data['status']="APPROVED";
                            }


	}
     public function index(){
                $this->data['opt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
                $table = \DB::table('p_po_hdr_t')->get();
	            $this->data['datas'] = $table;

           if(\Request::route()->getName()=="poapproval")
           {
               $this->data['id']=$ids=1;
           }
            else if(\Request::route()->getName()=="pocancellation")
            {
             $this->data['id']=$ids=3;
            }
             else if(\Request::route()->getName()=="purchasecopypo")
            {
             $this->data['id']=$ids=4;
            }
            else if(\Request::route()->getName()=="poamendment")
            {
             $this->data['id']=$ids=5;
            }
           else
           {
             $this->data['id'] =$ids=2;
           }
        $this->data['pageMethod']=\Request::route()->getName();
    // return view('poamendment.table',$this->data);
		  return view('purchaseorder.table',$this->data);
    }
    public function getPurchaseorderData($id=null){
      // dd($_GET['status']);
    $wh='';
    //dd($_GET);
    if($_GET['status']!='')

	  {
		  $wh=" and  p_po_hdr_t.po_status='".$_GET['status']."'";
	  }


		if($_GET['_search']=='true'){
		$wh .=$this->jqgridsearch('p_po_hdr_t',$_GET['filters']);
		}



		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(po_hdr_id) AS count FROM p_po_hdr_t where 1=1 $wh");
//dd($result);
		$count = $result[0]->count;
		if( $count > 0 && $limit > 0)
		{
		$total_pages = ceil($count/$limit);
		} else {
		$total_pages = 0;
		}
		if ($page > $total_pages)
		$page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
                $SQL = "SELECT
                        p_po_hdr_t.po_hdr_id as po_hdr_id,
                        p_po_hdr_t.po_number as po_number,
                        p_po_hdr_t.po_date as po_date,
                        p_po_hdr_t.po_type as po_type,
                        p_po_hdr_t.po_grand_total as po_grand_total,
                        p_po_hdr_t.po_status as po_status,
                        p_po_hdr_t.reference_number as reference_number,
                        p_po_hdr_t.supplier_id as sub_id,
                        IF(p_po_hdr_t.quality_status = 1,'QUALITY CHECKED','PENDING')
                        AS quality_status,
                        p_po_hdr_t.remarks as remarks,
                        m_supplier_t.supplier_name as supplier_id
                        FROM `p_po_hdr_t`
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_po_hdr_t.`supplier_id`) where 1=1  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
     //dd($SQL);


		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
     /*Karthigaa Purpose for create and update mode*/
   public function create($id=null,$potype=null,$ids=null)
	{
       
        $this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','purchaseorder')->get();
        $this->data['ids']=$ids;
        $this->data['supnameopt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
        $this->data['suptypeopt']=$this->jqgridselect('m_suppliertypes_t','suppliertype_id','suppliertype_name');
        $this->data['country']=$this->jqgridselect('m_countries_t','country_id','country_name');
        $this->data['state']=$this->jqgridselect('m_states_t','state_id','state_name');
        $this->data['city']=$this->jqgridselect('m_cities_t','city_id','city_name');
        $this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
        $this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
	
  
		//$this->data['id'] = $id;
		$table = \DB::table('p_po_hdr_t')->where('po_hdr_id',$id)->get();
            
		$this->data['row'] = $table[0];
                 $this->data['row']->source ="PO";
                 $this->data['row']->reference_id =$table[0]->po_hdr_id;
                 $this->data['row']->reference_number =$table[0]->po_number;
                   $this->data['row']->po_hdr_id='';
                 $this->data['row']->po_number="A".$table[0]->po_number;
                 //dd($this->data['row']->po_number);
		$tablelines = \DB::table('p_po_lines_t')->where('po_hdr_id',$id)->get();
		$this->data['linedata'] = $tablelines;
	   
		$this->data['row']->supplier_reference_no=$table[0]->supplier_reference_no;
                $this->data['currency'] =  $this->jCombo('f_account_currency_t','account_currency_id','currency_code',$table[0]->currency);
		$this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name',$table[0]->supplier_id);
                $this->data['freight_terms_id'] = $this->jCombo('m_frieghtterms_t','frieghtterm_id','fob_point_name',$table[0]->freight_terms_id);
                $this->data['ship_to_location_id'] = $this->jCombo('m_location_t','location_id','location_code|location_name',$table[0]->ship_to_location_id);
                $this->data['bill_to_location_id'] = $this->jCombo('m_location_t','location_id','location_code|location_name',$table[0]->bill_to_location_id);
                $this->data['suppliersite_id'] = $this->jcustomselect('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_name',$table[0]->suppliersite_id,' and supplier_id="'.$table[0]->supplier_id.'"');
                $this->data['freight_carrier_id'] = $this->jCombo('m_frieghtcarriers_hdr_t','ar_frieghtcarriers_hdr_id','carrier_name',$table[0]->freight_carrier_id);
                $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id','payment_method_name',$table[0]->default_payment_method_id);
                $this->data['delivery_terms_id'] = $this->jCombo('m_delivery_terms_t','delivery_terms_id','delivery_term_name',$table[0]->delivery_terms_id);
                $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t','payment_term_id','payment_term_name',$table[0]->payment_term_id);
                $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t','insurance_term_id','insurance_term_name',$table[0]->insurance_term_id);
		$this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',$table[0]->organization_id);
                $this->data['created_by'] = $this->jCombo('tb_users','id','username','',$table[0]->created_by);
		$this->data['project_id'] = $this->jCombo('m_projects_t','project_id','project_name',$table[0]->project_id);
                $this->data['po_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$table[0]->po_pricelist_id,' and price_list_type="Purchase"');
		$this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '','purchaseorder');
                
		$this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
                $this->data['supnameopt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
                $this->data['suptypeopt']=$this->jqgridselect('m_suppliertypes_t','suppliertype_id','suppliertype_name');

                $this->data['country']=$this->jqgridselect('m_countries_t','country_id','country_name');
                $this->data['state']=$this->jqgridselect('m_states_t','state_id','state_name');
                $this->data['city']=$this->jqgridselect('m_cities_t','city_id','city_name');
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');

                $acptqty=\DB::select("select
                        sum(pplt.accept_qty) as sum
                        from p_po_invoice_hdr_t pit
                        left join p_po_invoice_lines_t pplt on pplt.po_invoice_id = pit.po_invoice_id where pit.po_number=$id group by pplt.product_id ");

		if(count($this->data['linedata']) >= 1)
        {
			foreach ($this->data['linedata'] as $key => $value)
            {
                  $this->data['linedata'][$key]->po_line_id= "";
                                 $this->data['linedata'][$key]->po_hdr_id= "";          
			$hsn=\DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=".$value->product_id);
				if(!empty($hsn))
				{
					
				$this->data['linedata'][$key]->hsn_code =  $this->jcustomselect('f_gst_code_hdr_t','gst_code_hdr_id','classification_code',$value->hsn_code,' and gst_code_hdr_id in('.$hsn[0]->hsn_code.')');
				}
				else
				{
					$this->data['linedata'][$key]->hsn_code =  $this->jcustomselect('f_gst_code_hdr_t','gst_code_hdr_id','classification_code',$value->hsn_code,'');
				}
					$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->getpriceproduct($table[0]->po_pricelist_id,$value->product_id);
				$this->data['linedata'][$key]->uom_code_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
				
				
				$this->data['linedata'][$key]->tax_group_id =  $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$value->tax_group_id);
                                $this->data['linedata'][$key]->part_no =  $this->jCombocomp('m_manufacturer_partno_t','manufacturer_partno_id','part_no',$value->part_no);
                                     if($id=="3"){
                                         if(empty($acptqty)){
                                         $this->data['linedata'][$key]->received_qty="";}
                                         else{
                                $this->data['linedata'][$key]->received_qty =$acptqty[$key]->sum;
                                         }
                                 }
				
                                  if($this->data['linedata'][$key]->qty== 0){
                                    $this->data['linedata'][$key]->qty= "";
                            }
                            
                             
			}
		}


	   	
                 
                 if($ids==5)
                {
                   $this->data['return_url']= "poamendment";
                }
                
                 else
                {
                   $this->data['return_url']= "purchaseorder";
                }
                
	 //  dd($this->data['linedata']);
    /*END*/
		return view('poamendment.form',$this->data);
        }
 /*Karthigaa purpose for Save function*/
         public function save(Request $request)
		 {
         //dd($_POST);
                        $id='';
			$data = $this->validatePost($request->all(),$this->table,'header');
                        $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');
                          unset($lines_data['reverse_charge']);
                     
                         /*karthigaa Purpose for Auto Number*/
//                        if ($_POST['po_number'] =="")
//				{
//					$seqno=$this->Seqnoe('APO-','p_po_hdr_t',$_POST['po_type'],'po_count');
//					$data['po_number'] = $seqno[0];
//                                        $data['po_count'] = $seqno[1];
//				}
//				else
//				{
					$seqno[0] = $_POST['po_number'];
//				}
			 /*End*/

			\DB::beginTransaction();
			try
			{
				//dd($lines_data);
				$id=$this->model->insertRow($data);
                                 $pohdrid=$_POST['reference_id'];
                       \DB::update("update p_po_hdr_t set amendment_status='2' where po_hdr_id='$pohdrid'");
                          //Reverse Charge
                                $reverse_charge=$_POST['reverse_charge'];
                                $charge=implode(",",$reverse_charge);
                            if(isset($_POST['reverse_charge']))
                            {
                               \DB::update("update  p_po_hdr_t set reverse_charge='".$charge."' where po_hdr_id='".$id."'");
                            }  
				$lid=$this->submodel->subgridSave($lines_data,$id);
                                
				\DB::commit();
				return response()->json(array('status' => 'success', 'message' => 'Purchase Order Saved','id' => $id,'lid' => $lid,'auto_no'=>$seqno[0]));
			}
			catch (\Illuminate\Database\QueryException$e)
			{
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
                                dd($dbCode);
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}

        }
  
}
