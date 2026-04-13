<?php

namespace App\Http\Controllers;

use App\Purchasedc;
use App\Purchasedclines;
use Illuminate\Http\Request,DB;
use App\Http\Controllers\Controller;
use File;

class PurchasedcController extends Controller
{
   public function __construct()
    {
        $this->data=array();
        $this->model=new Purchasedc;
        $this->submodel=new Purchasedclines;
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='purchasedc';
        $this->table="p_dc_hdr_t";
        $this->subtable="p_dc_lines_t";
        $this->middleware('auth');
        $this->data=array(
                    'pageModule'=> 'purchasedc',
                    'pageUrl'	=>  url('purchasedc'),
                     'pageMethod'=>$this->data['pageMethod']
                  );
        $this->data['urlmenu']=$this->indexs();
    }
    /*Karthigaa Purpose For :Index Function to Call Table Blade*/
    public function index()
    {
        $this->data['suppliername']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
        
        $this->data['pageMethod']=\Request::route()->getName();
        return view("purchasedc.table",$this->data);
    }
 /* Karthigaa purpose for Display DC Data in JQgrid function */
    public function purchasedcData(){

        $wh='';
        if($_GET['_search']=='true'){
            $search_tables=array('m_supplier_t','p_grn_hdr_t');
        $wh=$this->jqgridsearch('p_dc_hdr_t',$_GET['filters'],$search_tables);

        }

        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $groupname=\Session::get('groupname');
        // if($groupname=='Superadmin' || $groupname=='Admin'){
        // $wh.='and  p_dc_hdr_t.company_id='.$compy;
        // }else{
        //     $wh.='and  p_dc_hdr_t.company_id='.$compy.' and p_dc_hdr_t.location_id='.$loc;
        // }
        $wh.=$grid_data=$this->grid_check('p_dc_hdr_t','dc_date');
   //     $wh.=$this->accyearcondition('p_dc_hdr_t.dc_date');
        // dd($wh);
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
        $result = \DB::select("SELECT COUNT(dc_hdr_id) AS count FROM p_dc_hdr_t left join p_grn_hdr_t on(
                        p_grn_hdr_t.grn_id=p_dc_hdr_t.`grn_id`) left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_dc_hdr_t.`supplier_id`) where 1=1 $wh");
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
                        p_dc_hdr_t.dc_hdr_id,
                        p_dc_hdr_t.dc_number,
                        p_dc_hdr_t.dc_date,
                        p_dc_hdr_t.dc_status,
                        p_grn_hdr_t.grn_number,
                        m_supplier_t.supplier_name,
                        m_supplier_t.supplier_id
                        FROM `p_dc_hdr_t`
                        left join p_grn_hdr_t on(
                        p_grn_hdr_t.grn_id=p_dc_hdr_t.`grn_id`)
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_dc_hdr_t.`supplier_id`) where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
$download_SQL = "SELECT
                        p_dc_hdr_t.dc_hdr_id,
                        p_dc_hdr_t.dc_number,
                        p_dc_hdr_t.dc_date,
                        p_dc_hdr_t.dc_status,
                        p_grn_hdr_t.grn_number,
                        m_supplier_t.supplier_name,
                        m_supplier_t.supplier_id
                        FROM `p_dc_hdr_t`
                        left join p_grn_hdr_t on(
                        p_grn_hdr_t.grn_id=p_dc_hdr_t.`grn_id`)
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_dc_hdr_t.`supplier_id`) where 1=1 $wh ORDER BY $sidx $sord";
        $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
        if(isset($_GET['download']))
        {
            return $result1;
        }

        $result = \DB::select( $SQL );
          // dd($result);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }
    
     /*Karthigaa Purpose For :GRN Index Function to Call Table Blade*/
    public function grntablefordc()
    {
        $this->data['suppliername']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
         $SQL = "SELECT
                p_qc_header_t.qc_header_id ,
                p_grn_hdr_t.grn_number,
                p_grn_hdr_t.dc_number,
                p_qc_header_t.qc_number,
                m_supplier_t.supplier_name,
                p_quality_spec_trx_lines_t.box_product_qty,
                p_quality_spec_trx_lines_t.reference_source_line_id,
                p_quality_spec_trx_lines_t.quality_status
                FROM
                `p_qc_header_t`
                LEFT JOIN p_qc_lines_t ON(p_qc_lines_t.qc_header_id = p_qc_header_t.qc_header_id)
                left join p_quality_spec_trx_lines_t on(p_quality_spec_trx_lines_t.reference_source_line_id=p_qc_lines_t.qc_line_id)
                LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_qc_header_t.`supplier_id`)
                LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_qc_header_t.grn_number
                WHERE 1 = 1 AND p_qc_header_t.return_status=0 and p_qc_lines_t.reject_qty!=0 and p_qc_header_t.qc_status !='INITIATED' and p_grn_hdr_t.invoice_created!='Yes'";
         $result = \DB::select( $SQL );
         $this->data['result']= json_encode($result);
        return view("purchasedc.qc_table",$this->data);
    }
/* Karthigaa purpose for Display GRN Data in JQgrid function */
       public function grnData(){
        $wh='';
        if($_GET['_search']=='true'){
            $search_tables=array('m_supplier_t','p_grn_hdr_t');
        $wh=$this->jqgridsearch('p_qc_header_t',$_GET['filters'],$search_tables);
        }
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $groupname=\Session::get('groupname');
        if($groupname=='Superadmin' || $groupname=='Admin'){
        $wh.='and  p_qc_header_t.company_id='.$compy;
        }else{
            $wh.='and  p_qc_header_t.company_id='.$compy.' and p_qc_header_t.location_id='.$loc;
        }
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
        $result = \DB::select("SELECT COUNT(qc_header_id) AS count FROM p_qc_header_t 
                            LEFT JOIN m_supplier_t ON (m_supplier_t.supplier_id = p_qc_header_t.supplier_id)
                            LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_qc_header_t.grn_number
                            where 1=1 $wh");
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
                p_qc_header_t.qc_header_id ,
                p_grn_hdr_t.grn_number AS grn_number,
                p_grn_hdr_t.dc_number AS dc_number,
                p_qc_header_t.qc_number AS qc_number,
                m_supplier_t.supplier_name,
                p_quality_spec_trx_lines_t.box_product_qty,
                p_quality_spec_trx_lines_t.reference_source_line_id,
                p_quality_spec_trx_lines_t.quality_status
                FROM
                `p_qc_header_t`
                LEFT JOIN p_qc_lines_t ON(p_qc_lines_t.qc_header_id = p_qc_header_t.qc_header_id)
                left join p_quality_spec_trx_lines_t on(p_quality_spec_trx_lines_t.reference_source_line_id=p_qc_lines_t.qc_line_id)
                LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_qc_header_t.`supplier_id`)
                LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_qc_header_t.grn_number
                WHERE 1 = 1 AND p_qc_header_t.return_status=0 and p_qc_lines_t.reject_qty!=0 and p_qc_header_t.qc_status !='INITIATED' and p_grn_hdr_t.invoice_created!='Yes' $wh ORDER BY $sidx $sord LIMIT $start , $limit";
           $download_SQL = "SELECT
                p_qc_header_t.qc_header_id ,
                p_grn_hdr_t.grn_number AS grn_number,
                p_grn_hdr_t.dc_number AS dc_number,
                p_qc_header_t.qc_number AS qc_number,
                m_supplier_t.supplier_name,
                p_quality_spec_trx_lines_t.box_product_qty,
                p_quality_spec_trx_lines_t.reference_source_line_id,
                p_quality_spec_trx_lines_t.quality_status
                FROM
                `p_qc_header_t`
                LEFT JOIN p_qc_lines_t ON(p_qc_lines_t.qc_header_id = p_qc_header_t.qc_header_id)
                left join p_quality_spec_trx_lines_t on(p_quality_spec_trx_lines_t.reference_source_line_id=p_qc_lines_t.qc_line_id)
                LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_qc_header_t.`supplier_id`)
                LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_qc_header_t.grn_number
                WHERE 1 = 1 AND p_qc_header_t.return_status=0 and p_qc_lines_t.reject_qty!=0 and p_qc_header_t.qc_status !='INITIATED' and p_grn_hdr_t.invoice_created!='Yes' $wh ORDER BY $sidx $sord";
                $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
        $result = \DB::select( $SQL );
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }
    
  /* Karthigaa purpose for Create function */  
       public function purchasedccreate($id=null){
        if(isset($id))
        {
            $this->data['row']=$gin_table=\DB::table('p_qc_header_t')
                                            ->select('p_grn_hdr_t.*','m_supplier_t.supplier_id',
                                                     'p_qc_header_t.dc_number',
                                                    'm_subcontract_supplier_t.subcontract_supplier_id',
                                                     'p_qc_header_t.qc_header_id',
                                                     'p_qc_header_t.qc_number',
                                                     'p_grn_hdr_t.grn_number',
                                                     'p_grn_hdr_t.grn_id',
                                                     'p_qc_header_t.dc_number')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_qc_header_t.supplier_id')
                ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_qc_header_t.subcontract_supplier_id')
                ->leftJoin('p_grn_hdr_t','p_grn_hdr_t.grn_id','=','p_qc_header_t.grn_number')
                ->where('qc_header_id',$id)->get();
                $this->data['row'][0]->dc_hdr_id="";
                $this->data['row'][0]->dc_date=date('Y-m-d');
                $this->data['row'][0]->dc_number=$gin_table[0]->dc_number;
                $this->data['row'][0]->qc_number=$gin_table[0]->qc_number;
                $this->data['row'][0]->qc_hdr_id ="";
                $this->data['row'][0]->dc_status="DRAFT";
		$this->data['row'][0]->remarks="";
                $this->data['supplier_id']= $this->jCombo('m_supplier_t','supplier_id','supplier_name',$gin_table[0]->supplier_id);
                $this->data['subcontract_supplier_id']= $this->jCombo('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_name',$gin_table[0]->subcontract_supplier_id);
             $qcspeclns = \DB::SELECT("SELECT p_quality_spec_trx_lines_t.*,sum(p_quality_spec_trx_lines_t.box_product_qty) as box_product_qty1,p_qc_lines_t.* FROM `p_quality_spec_trx_lines_t` left join p_qc_lines_t on p_quality_spec_trx_lines_t.reference_source_line_id=p_qc_lines_t.qc_line_id where p_quality_spec_trx_lines_t.reference_source_hdr_id='$id' and p_qc_lines_t.reject_qty!=0 group by p_quality_spec_trx_lines_t.reference_source_line_id");
            $this->data['linedata'] = $qcspeclns;
            if(count($this->data['linedata']) >= 1){
            $po_tax_total=0;
            $po_grand_total=0;
            foreach ($this->data['linedata'] as $key => $value)
            {
		        $this->data['linedata'][$key]=(object) array();
                $this->data['linedata'][$key]->dc_line_id="";
                $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);
                $this->data['linedata'][$key]->uom_code_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
                $this->data['linedata'][$key]->qty =$value->box_product_qty1;
                $this->data['linedata'][$key]->comments = $value->reason;
            }
        }
    }
    return view('purchasedc.form',$this->data);
    }
    /*End*/
    /*Purpose for Print*/
    public function getprint($id=null)
	{
        
	 	if(isset($id))
    	{

			$gin_table=\DB::table('p_dc_hdr_t')
                        ->select('m_supplier_t.supplier_id','m_subcontract_supplier_t.subcontract_supplier_id','p_dc_hdr_t.dc_number','p_grn_hdr_t.grn_id','p_grn_hdr_t.grn_id','p_dc_hdr_t.dc_hdr_id','p_dc_hdr_t.dc_date')
			->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_dc_hdr_t.supplier_id')
                        ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_dc_hdr_t.subcontract_supplier_id')
			->leftJoin('p_grn_hdr_t','p_grn_hdr_t.grn_id','=','p_dc_hdr_t.grn_id')
			->where('dc_hdr_id',$id)->get();

//		    dd($gin_table);
		    if(($gin_table->isNotEmpty()))
			{
                        
                $this->data['p_dc_hdr_t']=$id;
                $this->data['dc_date']=date(\Session::get('p_date_format'), strtotime($gin_table[0]->dc_date));
                
                 if($gin_table[0]->supplier_id !=""){
                $this->data['supplier_id']= $this->idname('supplier_name','m_supplier_t','supplier_id',$gin_table[0]->supplier_id);
                }else{
                $this->data['subcontract_supplier_id']= $this->idname('subcontract_name','m_subcontract_supplier_t','subcontract_supplier_id',$gin_table[0]->subcontract_supplier_id);
                }
			}
		    else
			{
		         $this->data['p_dc_hdr_t']='';
                $this->data['dc_date']='';
                $this->data['po_tax_total']='';
                $this->data['po_grand_total']='';
                $this->data['return_invoice_number']='';
                $this->data['supplier_id']= '';
                $this->data['supplier_id']= '';
			}



			$tablelines = \DB::table('p_dc_lines_t')->where('dc_hdr_id',$id)->get();
			//dd($tablelines);
			$this->data['linedata'] = $tablelines;

		 	if($tablelines->isNotEmpty())
			{
				foreach($this->data['linedata'] as $key => $value)
				{

    				$this->data['linedata'][$key]->product_id= $this->data['product_id'] = $this->idname('concatenated_product','m_products_t','product_id',$value->product_id);
    				$this->data['linedata'][$key]->uom_code_id =  $this->idname('uom_code','m_uom_codes_t','uom_code_id',$value->uom_code_id);
    				$this->data['linedata'][$key]->comments = $value->comments;
					$this->data['linedata'][$key]->qty = $value->qty;
					
					$batch=\DB::SELECT("select p_dc_hdr_t.qc_hdr_id,p_quality_spec_trx_lines_t.reference_source_hdr_id,p_quality_spec_trx_lines_t.batch_number FROM p_quality_spec_trx_lines_t JOIN p_dc_hdr_t ON (p_quality_spec_trx_lines_t.reference_source_hdr_id = p_dc_hdr_t.qc_hdr_id ) WHERE p_quality_spec_trx_lines_t.quality_status='Rejected' GROUP BY p_dc_hdr_t.qc_hdr_id");
                 
					$batch_data=json_decode(json_encode($batch), true);
					$this->data['linedata'][$key]->batch=implode(',', array_column($batch_data, 'batch_number'));
					
			      $date=\DB::SELECT("select p_dc_hdr_t.`grn_id`,p_grn_hdr_t.dc_date,p_grn_hdr_t.grn_id from p_dc_hdr_t join p_grn_hdr_t on (p_dc_hdr_t.grn_id=p_grn_hdr_t.grn_id) where p_dc_hdr_t.dc_hdr_id=$id group by p_dc_hdr_t.grn_id");
			
					if(count($date)>0){
                                            
				$this->data['linedata'][$key]->receive_date=  date("d-m-Y",strtotime($date[0]->dc_date));
						//dd($this->data['linedata'][$key]);
					}else{
					$this->data['linedata'][$key]->receive_date='';
					}
					//dd($this->data['linedata'][$key]);
				}
				
				
			}

 		}




		/********************* company *******************/
			$company=$this->getCompany();

			if(!empty($company))
			{
				
			$company_name=$company[0]->company_name;
			$company_logo_name=$company[0]->company_logo_name;
			$this->data['company_name']= $company_name;
			$this->data['company_logo_name']= $company_logo_name;
            $this->data['gst_no'] = $company[0]->gst_no;
			$this->data['pan_no'] = $company[0]->pan_no;
			}
			else
			{
			$this->data['company_name']= '';
			}
		/******************** end ************************/

        /******************* location *******************/

		$location=$this->getLocationaddress();

		if(!empty($location))
		{
			
			$this->data['location_name'] = $location[0]->location_name;
			$this->data['country'] = $this->getCountry($location[0]->country_id);
			$this->data['state'] = $this->getState($location[0]->state_id);
			$this->data['city'] = $this->getCity($location[0]->city_id);
			$this->data['area']=$location[0]->area;
			$this->data['pincode']=$location[0]->pincode;
			$this->data['street'] = $location[0]->street_name;
			$this->data['address'] = $location[0]->address;
		}
		else
		{
			$this->data['location_name'] = '';
			$this->data['country'] = '';
			
			$this->data['state_name']='';
			
			$this->data['city'] = '';
			$this->data['area']='';
			$this->data['pincode']='';
			$this->data['gst_no'] = '';
			$this->data['pan_no'] = '';
			$this->data['street'] = '';
			$this->data['address'] = '';
		}

    	$this->data['company_address']=$this->data['address'].",".$this->data['street'].",".$this->data['area'].",".$this->data['city'].", ".$this->data['state'].",".$this->data['country'].",".$this->data['pincode'];

        /************** End **********************/

       /****** supplier Name ********************/

	if(!empty($gin_table[0]->supplier_id))
	{
		
            $supplier=$this->getSupplier($gin_table[0]->supplier_id);

            $this->data['supplier_name'] = $supplier[0]->supplier_name;
		

        }
        else if(!empty($gin_table[0]->subcontract_supplier_id)){
            $supplier=$this->getSubSupplier($gin_table[0]->subcontract_supplier_id);

            $this->data['supplier_name'] = $supplier[0]->subcontract_name;
        }
        else
        {
        $this->data['supplier_name']='';
        $this->data['sup_gst_no'] ='';
        }

	  /*********** End **************************/

	  /*********** supplier Address *************/

		if(!empty($gin_table[0]->supplier_id))
		{
    		$supplier_address=$this->getSupplieraddress($gin_table[0]->supplier_id);
//                 dd($supplier_address);
    		if(!empty($supplier_address))
			{
    			$this->data['address']=$supplier_address[0]->address;
    			$this->data['state']=$this->getState_s($supplier_address[0]->state);
    			$this->data['state_name']=$this->data['state'][0]->state_name;
				//dd($this->data['state_name']);
    			$this->data['state_code']=$this->data['state'][0]->state_code;

    			$this->data['city']=$this->getCity($supplier_address[0]->city);
    			$this->data['country']=$this->getCountry($supplier_address[0]->country);
    			$this->data['pincode']=$supplier_address[0]->pincode;
                $this->data['sup_gst_no'] = $supplier_address[0]->gst_number;
    			$this->data['contact_number']=$supplier_address[0]->contact_number;
			}
			else
			{
    			$this->data['address']='';
    			$this->data['state']='';
    			$this->data['state_name']='';
    			$this->data['state_code']='';
    			$this->data['city']='';
    			$this->data['country']='';
    			$this->data['pincode']='';
    			$this->data['contact_number']='';
			}
	    }else{
               $supplier_address=$this->getSubSupplieraddress($gin_table[0]->subcontract_supplier_id);
//                 dd($supplier_address);
    		if(!empty($supplier_address))
			{
    			$this->data['address']=$supplier_address[0]->address;
    			$this->data['state']=$this->getState_s($supplier_address[0]->state);
    			$this->data['state_name']=$this->data['state'][0]->state_name;
				//dd($this->data['state_name']);
    			$this->data['state_code']=$this->data['state'][0]->state_code;

    			$this->data['city']=$this->getCity($supplier_address[0]->city);
    			$this->data['country']=$this->getCountry($supplier_address[0]->country);
    			$this->data['pincode']=$supplier_address[0]->pincode;
                $this->data['sup_gst_no'] = $supplier_address[0]->gst_number;
    			$this->data['contact_number']=$supplier_address[0]->contact_number;
			}
			else
			{
    			$this->data['address']='';
    			$this->data['state']='';
    			$this->data['state_name']='';
    			$this->data['state_code']='';
    			$this->data['city']='';
    			$this->data['country']='';
    			$this->data['pincode']='';
    			$this->data['contact_number']='';
			} 
            }

    	$this->data['supplier_address']=$this->data['address'].",".$this->data['state_name'].",".$this->data['city'].",".$this->data['country'].",".$this->data['pincode'];
//dd($this->data['supplier_address']);
    	/************** End *************************/

    	/************** date ***************/
       $this->data['date']= date(\Session::get('p_date_format'));
//		$this->data['date']=date('d/m/y');

	   /************ End ******************/



		$this->data['linedata']=$this->data['linedata'];
/*Start*/
          if(isset($_GET['mail']))
            {

                if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
        Config::set('mail.username', \Session::get('user_email'));
        Config::set('mail.password', \Session::get('user_password'));
    }

                $this->data['print']="PRINTS";


              $tomail ="'".str_replace(",","','",$_GET['mail'])."'";

                \Mail::send('purchasereturn.purchasereturn_print',$this->data, function($message)
                {
                  if(!empty($_GET['cc'])){

                  $cc=explode(',',$_GET['cc']);
                  $message->cc($cc);
              }else{
                 $cc=array();
              }

                    $msg=$_GET['msg'];


                      $message->to(explode(",",$_GET['mail']));
                    $message->subject("Purchase Return". $this->data['return_invoice_number']);

                     $message->setBody($msg);
                    //$message->from('Saipavan9010@gmail.com');
                    if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    $retuen=DB::table('p_return_header_t')->where('return_header_id',$this->data['return_header_id'])->get();
            if($retuen[0]->attachment_file!=''){
                $file_a=json_decode($retuen[0]->attachment_file);
                foreach($file_a as $k1=>$v1){
                 $message->attach('uploads/purchasereturn/P'.$this->data['return_header_id'].'/'.$v1);
                }
            }
                    $message->attach('uploads/purchasereturn/P_'.stripslashes($this->data['return_invoice_number']).'.pdf');

                });
                            return 1;


            }
            $this->data['print']="PRINT";
        $this->data['print_val'] = '1';

        if(isset($_GET['mails'])){

          $this->data['print']="PRINTS";
          // dd($this->data);
             return view('purchasedc.printblade', $this->data);
         }
/*End*/


		$this->data['print']="PRINT";
		$this->data['print_val'] = '1';
		return view('purchasedc.printblade',$this->data);
	}
/* Karthigaa purpose for Supplier State For Print*/
	function getState_s($state_id=null)
	{
		$state=\DB::table('m_states_t')->where('state_id',$state_id)->get();
		if(!empty($state))
		{
			return $state;
		}
		else
		{
			return 0;
		}

	}
/* Karthigaa purpose for Supplier For Print*/ 	
	function getSupplier($sup_id=null)
	{
		$supplier=\DB::table('m_supplier_t')->where('supplier_id',$sup_id)->get();
		if(!empty($supplier))
			{
				return $supplier;
			}
			else
			{
			    return 0;
			}
	}
    /* Karthigaa purpose for Supplier Address For Print*/ 	    
	function getSupplieraddress($sub_id=null)
		{
		$supplier_address=array();
                $supplier_address=\DB::select("select * from m_supplier_sites_t where supplier_id='$sub_id'");
		if(!empty($supplier_address))
		{
			return $supplier_address;
		}
	 	else
		{
			return 0;
		}

		}
    /* Karthigaa purpose for Subcontractor Supplier */ 
       function getSubSupplier($sup_id=null){
		$subsupplier=\DB::table('m_subcontract_supplier_t')->where('subcontract_supplier_id',$sup_id)->get();
		if(!empty($subsupplier)){
				return $subsupplier;
			}
                else
                {
                    return 0;
                }
	}
         /* Karthigaa purpose for Subcontractor Supplier Address*/ 
	function getSubSupplieraddress($sub_id=null){
		$subsupplier_address=array();
                $subsupplier_address=\DB::select("select * from m_subcontract_sites_t where subcontract_supplier_id='$sub_id'");
		if(!empty($subsupplier_address))
		{
			return $subsupplier_address;
		}
	 	else
		{
			return 0;
		}
	}
    /* Karthigaa purpose for Get Company*/ 
    function getCompany(){
			$sql=array();
			$company=\Session::get('companyid');
			$sql=\DB::SELECT("SELECT company_id,company_name,gst_no,pan_no,company_logo_name FROM `m_company_t` WHERE `company_id`=".$company."");
			if(!empty($sql))
			{
			return $sql;
			}
			else
			{
			return 0;
			}
		}	
	 /* Karthigaa purpose for Get Location Address*/ 
	function getLocationaddress(){
        $sql=array();
        $location=\Session::get('location');

        $sql=\DB::SELECT('select * from m_location_t where location_id='.$location.'');
        if(!empty($sql)){
            return $sql;
        }else{
            return 0;
        }
}
/*karthigaa Purpose for SAve Function*/
     public function purchasedcsave(Request $request)
        {
                $id='';
                $data = $this->validatePost($request->all(),$this->table,'header');
                $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');
                
                \DB::update("update p_qc_header_t set return_status='1' where qc_header_id='".$_POST['qc_hdr_id']."'");
                
                \DB::beginTransaction();
                try
                {
                   $id=$this->model->insertRow($data);
                   $lid=$this->submodel->subgridSave($lines_data,$id);
                   \DB::commit();
                    /**Auditlog**/
                        if($_POST['dc_hdr_id']==""){
                                    $action="create";
                                    }else{
                                    $action="edit";
                                    }
                        $this->auditlog($id,"purchasedc",$action,$_POST,"p_dc_hdr_t");
                    return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'lid' => $lid));
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
/*Karthigaa Purpose For View Function*/
public function purchasedcview($id=null){
        if(isset($id))
        {
        $this->data['row']=$gin_table=\DB::table('p_dc_hdr_t')->select('p_dc_hdr_t.*','p_qc_header_t.qc_number','m_supplier_t.supplier_id','p_dc_hdr_t.dc_number','p_grn_hdr_t.grn_number','p_grn_hdr_t.grn_id')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_dc_hdr_t.supplier_id')
                ->leftJoin('p_qc_header_t','p_qc_header_t.qc_header_id','=','p_dc_hdr_t.qc_hdr_id')
                ->leftJoin('p_grn_hdr_t','p_grn_hdr_t.grn_id','=','p_dc_hdr_t.grn_id')
                ->where('dc_hdr_id',$id)->get();
                $this->data['row'][0]->dc_hdr_id=$id;
                $dc_date=$gin_table[0]->dc_date;
                $this->data['row'][0]->dc_date=date(\Session::get('p_date_format'),strtotime($dc_date));
                $this->data['row'][0]->dc_status=$gin_table[0]->dc_status;
                $this->data['supplier_id']= $this->idname('supplier_name','m_supplier_t','supplier_id',$gin_table[0]->supplier_id);
            $tablelines = \DB::table('p_dc_lines_t')->where('dc_hdr_id',$id)->get();
            $this->data['linedata'] = $tablelines;
            if(count($this->data['linedata']) >= 1){
            foreach ($this->data['linedata'] as $key => $value){
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->idname('concatenated_product','m_products_t','product_id',$value->product_id);
                $this->data['linedata'][$key]->uom_code_id =  $this->idname('uom_code','m_uom_codes_t','uom_code_id',$value->uom_code_id);
                $this->data['linedata'][$key]->qty = $value->qty;
                $this->data['linedata'][$key]->comments = $value->comments;
            }
        }
}
    $this->data['url']=$_GET['return'];
      return view('purchasedc.view',$this->data);
    }



    	
	
}
