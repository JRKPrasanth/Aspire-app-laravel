<?php
namespace App\Http\Controllers;

use App\Purchaseenquiry;
use App\Purchaseenquirylines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use File;
use Validator,DB;
use Config;
use Yajra\DataTables\DataTables;

class PurchaseenquiryController extends Controller {

    public function __construct() {
        $this->data = array();
        $this->table = "p_enquiry_hdr_t";
        $this->subtable = "p_enquiry_lines_t";
        $this->pageModule = "purchaseenquiry";
        $this->model = new Purchaseenquiry;
        $this->submodel = new Purchaseenquirylines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'purchaseenquiry',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
        
        $this->modelname = new Purchaseenquiry();
        $this->data['pageFormtype'] = 'ajax';

        if($this->data['pageMethod']=='purchaseenquirytopo' || $this->data['pageMethod']=='purchaseenquirytoquote' ||$this->data['pageMethod']=='purchasecopyenquiry')
	{
		$this->data['status']="INITIATED";
	}
        else{
            $this->data['status']="";
        }
    }
	
	// Purpose For :Index Function to Call Table Blade
    public function index() {
		
        $this->data['curlname']=\Request::route()->getName();
        $this->data['opt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $table = \DB::table('p_enquiry_hdr_t')->get();
        $this->data['datas'] = json_encode($table);
		
        return view('purchaseenquiry.table', $this->data);
    }
	
	
//  purpose for Display Data in JQgrid function 
    public function getenquiryData() {

        $wh = '';
        $col_name='';

          if($_GET['status']!='')
	  {
			 
		  $wh.=" and p_enquiry_hdr_t.enquiry_status='".$_GET['status']."'";
		  $col_name="enquiry_status";
		  $op="=";
		  $status_val="'".$_GET['status']."'";
	  }
        

        $loc="1";
        $compy=\Session::get('companyid');      
        $groupname=\Session::get('groupname');

            
     if($col_name != ''){
    $wh.=$grid_data=$this->grid_statuscheck('p_enquiry_hdr_t','enquiry_date',$col_name,$op,$status_val);
    } else {
       $wh.=$grid_data=$this->grid_check('p_enquiry_hdr_t','enquiry_date'); 
    }

		
        if($_GET['pagemethod'] == 'purchaseenquirytopo'){
        $SQL = "SELECT
                        p_enquiry_hdr_t.enquiry_hdr_id as enquiry_hdr_id,
                        p_enquiry_hdr_t.enquiry_number as enquiry_number,
                        p_enquiry_hdr_t.enquiry_date as enquiry_date,
                        p_enquiry_hdr_t.enquiry_type_id as enquiry_type_id,
                        p_enquiry_hdr_t.enquiry_status as enquiry_status,
                        p_enquiry_hdr_t.suppliersite_id,
                        m_supplier_sites_t.supplier_site_name,
                        p_enquiry_hdr_t.remarks as remarks,
                        p_enquiry_hdr_t.source,
                        m_supplier_t.supplier_name,
                        m_supplier_t.supplier_id
                        FROM `p_enquiry_hdr_t`
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_enquiry_hdr_t.`supplier_id`)
                        left join m_supplier_sites_t on(
                        m_supplier_sites_t.supplier_site_id=p_enquiry_hdr_t.`suppliersite_id`) where  NOT EXISTS
        (
        SELECT  * 
        FROM    p_po_hdr_t p
        WHERE   p.reference_id = p_enquiry_hdr_t.enquiry_hdr_id and p.source = 'ENQUIRY'
        ) and 1=1   $wh ";


			
        } else {
			
			
            $SQL = "SELECT
                        p_enquiry_hdr_t.enquiry_hdr_id as enquiry_hdr_id,
                        p_enquiry_hdr_t.enquiry_number as enquiry_number,
                        p_enquiry_hdr_t.enquiry_date as enquiry_date,
                        p_enquiry_hdr_t.enquiry_type_id as enquiry_type_id,
                        p_enquiry_hdr_t.enquiry_status as enquiry_status,
                        p_enquiry_hdr_t.suppliersite_id,
                        m_supplier_sites_t.supplier_site_name,
                        p_enquiry_hdr_t.remarks as remarks,
                        p_enquiry_hdr_t.source,
                        m_supplier_t.supplier_name,
                        m_supplier_t.supplier_id
                        FROM `p_enquiry_hdr_t`
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_enquiry_hdr_t.`supplier_id`)
                        left join m_supplier_sites_t on(
                        m_supplier_sites_t.supplier_site_id=p_enquiry_hdr_t.`suppliersite_id`) where 1=1   $wh";
           
    
        }
		
        $result = \DB::select($SQL);
		return DataTables::of($result)->make(true);
		
    }	
	
// Purpose For :Create Function to Call Form Blade
    public function create($id = null, $enqtype = null, $enquiry_status = null)
    {
       $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'purchaseenquiry')->get();
        if (isset($_GET['status']) && $_GET['status'] != 'COPYENQUIRY') {
			/*Convertion Of Requisition to Enquiry*/
            if ($_GET['status'] == "REQUISITION") {
                $requisition_data = \DB::table('p_requisition_hdr_t')->where('requisition_hdr_id', $id)->get();
                $requisition_lines_data = \DB::table('p_requisition_lines_t')->where('requisition_hdr_id', $id)->where('status',0)->get();
                $this->data['row'] = (object) array();
                $this->data['row']->enquiry_hdr_id = "";
                $this->data['row']->enquiry_number = "";
                $this->data['row']->enquiry_date = date('Y-m-d');
                $this->data['row']->reference_number = $requisition_data[0]->requisition_no;
                $this->data['row']->reference_id = $id;
                $this->data['row']->source = "REQUISITION";
                $this->data['row']->enquiry_type_id = "STANDARD";
                $this->data['row']->enquiry_status = 'DRAFT';
                $this->data['row']->remarks = "";
//                $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t','supplier_site_id','supplier_site_number|supplier_site_name','');
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', '');
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $requisition_data[0]->organization_id);
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $requisition_data[0]->created_by);
                $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $requisition_data[0]->project_id);
				$this->data['return_url']=\Request::route()->getName();
                $this->data['linedata'] = array();
                    $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '','purchaseenquiry');
                    $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
                    $this->data['part_no'] = $this->jCombocomp('m_manufacturer_partno_t','manufacturer_partno_id','part_no','');
				foreach ($requisition_lines_data as $key => $value) {
                $this->data['linedata'][$key] = (object) array();
                $this->data['linedata'][$key]->enquiry_line_id = '';
                $this->data['linedata'][$key]->product_id =$this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);
                $this->data['linedata'][$key]->uom_code_id =   $this->jcustomselect('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id,'and uom_code_id='.$value->uom_code_id);
                $this->data['linedata'][$key]->part_no = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');
                $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
				if($value->po_qty==0){
				$qty=$value->qty;	
				}else{
					
				$qty=ABS($value->balance_qty);	
				}
                $this->data['linedata'][$key]->qty = $qty;
            }
			
            }
            
        } 
		/*Purpose For Create Enquiry*/
		else if ($id == "0") {
			
            $this->data['row'] = (object) array();
            $this->data['row']->enquiry_hdr_id = "";
            $this->data['row']->enquiry_number = "";
            $this->data['row']->enquiry_date = date('Y-m-d');
            $this->data['row']->enquiry_type_id = "";
            $this->data['row']->enquiry_status = 'DRAFT';
            if ($enqtype != "LABOUR")
                $enqtype = "STANDARD";
            else
                $enqtype = $enqtype;

            $this->data['row']->enquiry_type_id = $enqtype;
            $this->data['row']->remarks = "";
            $this->data['row']->source = "ENQUIRY";
            $this->data['id'] = '';
            $this->data['linedata'] = array();
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', '');
            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name',\Session::get('organization'));
//             $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t','supplier_site_id','supplier_site_number|supplier_site_name', '');
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', '');
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');
            $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '','purchaseenquiry');
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['part_no'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');
            $this->data['supnameopt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
            $this->data['suptypeopt'] = $this->jqgridselect('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name');
            $this->data['country'] = $this->jqgridselect('m_countries_t', 'country_id', 'country_name');
            $this->data['state'] = $this->jqgridselect('m_states_t', 'state_id', 'state_name');
            $this->data['city'] = $this->jqgridselect('m_cities_t', 'city_id', 'city_name');
            $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
            $this->data['group']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
            $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
            
        }
		/*Purpose For Edit Enquiry*/
        else {
			$this->data['id'] = $id;
            $table = \DB::table('p_enquiry_hdr_t')->where('enquiry_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $tablelines = \DB::table('p_enquiry_lines_t')->where('enquiry_hdr_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
            $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_number|supplier_site_name',$table[0]->suppliersite_id);
            $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '','purchaseenquiry');
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['part_no'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');
            $this->data['supnameopt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
            $this->data['suptypeopt'] = $this->jqgridselect('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name');
            $this->data['country'] = $this->jqgridselect('m_countries_t', 'country_id', 'country_name');
            $this->data['state'] = $this->jqgridselect('m_states_t', 'state_id', 'state_name');
            $this->data['city'] = $this->jqgridselect('m_cities_t', 'city_id', 'city_name');
            $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
            $this->data['group']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
            $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
            $this->data['enquiry_status'] = $table[0]->enquiry_status;
            
            if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
//                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', $value->product_id,'purchaseenquiry');
//                $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                if($value->product_id!='0'){
                    $this->data['linedata'][$key]->product_id =$this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);    
                }else{
                    $this->data['linedata'][$key]->product_id =$this->jcustomselect('m_products_t','product_id','product_code|concatenated_product','','');    
                }
                if($value->uom_code_id!='0'){
                    $this->data['linedata'][$key]->uom_code_id =   $this->jcustomselect('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id,'and uom_code_id='.$value->uom_code_id);
                }else{
                    $this->data['linedata'][$key]->uom_code_id =   $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
                }
                
                $this->data['linedata'][$key]->part_no = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no);
                if ($this->data['linedata'][$key]->qty == 0) {
                    $this->data['linedata'][$key]->qty = "";
                }
                /* KARTHIGAA Purpose For COPY ENQUIRY */
                if (isset($_GET['status']) && $_GET['status'] == 'COPYENQUIRY') {
                    $this->data['linedata'][$key]->enquiry_line_id = "";
                    $this->data['linedata'][$key]->enquiry_hdr_id = "";
                    $this->data['row']->enquiry_hdr_id = '';
                    $this->data['copy_enquiry_number'] = $this->data['row']->enquiry_number;
                    $this->data['row']->enquiry_number = '';
                }
                /* End */
            }
        }
           
        }
	/* KARTHIGAA Purpose For COPY ENQUIRY */
        if (isset($_GET['status']) && $_GET['status'] == 'COPYENQUIRY') {
			$this->data['return_url'] ="purchasecopyenquiry";
        }
         else{
		$this->data['return_url']=\Request::route()->getName();
	}
	$this->data['enquiry_status']=$enqtype;
        return view('purchaseenquiry.form', $this->data);
    }

    /* Karthigaa purpose for Save function */
    public function save(Request $request) {
        $id = '';
        $data = $this->validatePost($request->all(), $this->table, 'header');
        $lines_data = $this->validatePost($request->all(), $this->subtable, 'lines');
		
		
        /* karthigaa Purpose for Auto Number */
        if ($_POST['enquiry_number'] == "") {
            $seqno = $this->Seqnoe('ENQ-', 'p_enquiry_hdr_t', $_POST['enquiry_type_id'],'poenq_count');
            $data['enquiry_number'] = $seqno[0];
            $data['poenq_count'] = $seqno[1];
        } else {
            $seqno[0] = $_POST['enquiry_number'];
        }
        /* End */
		 
						 
        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            \DB::commit();
            /**Auditlog**/
            if($_POST['enquiry_hdr_id']==""){
                        $action="create";
			}else{
			$action="edit";
			}
             $this->auditlog($id,"purchaseenquiry",$action,$_POST,"p_enquiry_hdr_t");
            
            return response()->json(array('status' => 'success', 'message' => 'Purchase Enquiry Saved', 'id' => $id, 'lid' => $lid, 'auto_no' => $seqno[0]));
        } catch (\Illuminate\Database\QueryException$e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
           // DD($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }

    /* Karthigaa purpose for Display hdr & Lines View function */
    public function view(request $request, $id = null) {
        if (isset($id)) {
            $vdata = \DB::table('p_enquiry_hdr_t')->leftjoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_enquiry_hdr_t.supplier_id')
                            ->leftjoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'p_enquiry_hdr_t.organization_id')
                            ->leftjoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_site_id', '=', 'p_enquiry_hdr_t.suppliersite_id')
                            ->leftjoin('m_projects_t', 'm_projects_t.project_id', '=', 'p_enquiry_hdr_t.project_id')
                            ->leftjoin('tb_users', 'tb_users.id', '=', 'p_enquiry_hdr_t.created_by')
                            ->where('enquiry_hdr_id', $id)->get();

            $this->data['enquiry_number'] = $vdata[0]->enquiry_number;
            $enq_date=$vdata[0]->enquiry_date;
            $this->data['enquiry_date'] =  date(\Session::get('p_date_format'), strtotime($enq_date));
            $this->data['source'] = $vdata[0]->source;
            $this->data['enquiry_status'] = $vdata[0]->enquiry_status;
            $this->data['enquiry_type_id'] = $vdata[0]->enquiry_type_id;
            $this->data['supplier_name'] = $vdata[0]->supplier_name;
            $this->data['supplier_site_name'] = $vdata[0]->supplier_site_name;
            $this->data['project_name'] = $vdata[0]->project_name;
            $this->data['organization_name'] = $vdata[0]->organization_name;
            $this->data['other_info'] = $vdata[0]->other_info; 
            $this->data['remarks'] = $vdata[0]->remarks;
            $this->data['username'] = $vdata[0]->username;
            $a = \DB::table('p_enquiry_lines_t')->where('enquiry_hdr_id', $id)->get();

            $vlinesdata = \DB::table('p_enquiry_lines_t')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'p_enquiry_lines_t.product_id')
                            ->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'p_enquiry_lines_t.uom_code_id')
                    -> leftjoin('m_manufacturer_partno_t','m_manufacturer_partno_t.manufacturer_partno_id','=','p_enquiry_lines_t.part_no')
                            ->where('p_enquiry_lines_t.enquiry_hdr_id', $id)->get();

            $this->data['vlinesdata'] = $vlinesdata;
            $this->data['uom_code'] = $vlinesdata[0]->uom_code;
            $this->data['part_no'] = $vlinesdata[0]->part_no;
            $promised_date=$vlinesdata[0]->promised_date;
            $this->data['promised_date'] =date(\Session::get('p_date_format'), strtotime($promised_date));
           $this->data['return_url']=$_GET['return'];
            return view('purchaseenquiry.view', $this->data);
        }
    }
/*Purpose For Enquiry Print*/
	public function poenquiryprint($id=null)
	{
		$row = \DB::table('p_enquiry_hdr_t')->where('enquiry_hdr_id',$id)->get();
        $sub_id=$row[0]->supplier_id;
        $this->data['enquiry_number']=$row[0]->enquiry_number;
        $this->data['enquiry_hdr_id']=$row[0]->enquiry_hdr_id;
        $enquiry_date=$row[0]->enquiry_date;
        $this->data['enquiry_date']= date(\Session::get('p_date_format'), strtotime($enquiry_date));
		$this->data['enquiry_type_id']=$row[0]->enquiry_type_id;
		$location=$this->getLocationwiseaddress();
		$this->data['location_name'] = $location[0]->location_name;
		$this->data['country'] = $this->getCountry($location[0]->country_id);
		$this->data['state'] = $this->getState($location[0]->state_id);
		$this->data['city'] = $this->getCity($location[0]->city_id);;
		$this->data['gst_no_l'] = $location[0]->gst_no;
		$this->data['pan_no'] = $location[0]->pan_no;
		$this->data['street'] = $location[0]->street_name;
		$companyaddress=$this->data['address'] = $location[0]->address;
		$this->data['e_mail']=$location[0]->e_mail;
		$this->data['Phone']=$location[0]->Phone;
		$this->data['pincode']=$location[0]->pincode;
                if($companyaddress == "null"){
                    $this->data['company_address']=$this->data['street']."".$this->data['city'].",".$this->data['state'].",".$this->data['country'];
                }else{
                    $this->data['company_address']=$this->data['address'].",".$this->data['street']."".$this->data['city'].",".$this->data['state'].",".$this->data['country'];                
                }
		$sup_address=$this->getSuppliersite($sub_id);
				if($sup_address!=0)
				{
				$this->data['baddress']=$sup_address[0]->address;
				$this->data['bcity']=$this->getCity($sup_address[0]->city);
				$this->data['bcountry']=$this->getCountry($sup_address[0]->country);
				$this->data['bstate']=$this->getState($sup_address[0]->state);
                $this->data['gst_number']=$sup_address[0]->gst_number;
                $this->data['pincode']=$sup_address[0]->pincode;
				}
				else
				{
				$this->data['baddress']="";
				$this->data['bcity']="";
				$this->data['bcountry']="";
				$this->data['bstate']="";
				$this->data['pincode']='';
				}

				$this->data['supplier_address']=$this->data['baddress'].",".$this->data['bcity'].",".$this->data['bstate'].",".$this->data['bcountry'].",".$this->data['pincode'];
				$sid=$this->getSupplier($row[0]->supplier_id);

                $this->data['supplier_name']=ucfirst(strtolower($sid[0]->supplier_name));
				$this->data['sup_gst_no']=$sid[0]->gst_no;

				//$this->data['bcustomer']=$sid[0]->supplier_name;
				//$this->data['bgst']=$sid[0]->gst_no;

		$lines = \DB::table('p_enquiry_lines_t')->where('enquiry_hdr_id',$id)->get();
		$this->data['subgrid'] = $lines;
		foreach($this->data['subgrid'] as $key=>$value)
		{
		if($row[0]->enquiry_type_id !="LABOUR")
		{
			if($value->product_id !='0')
			{
				$arr=$this->getProduct($value->product_id);
				$inquirylines[$key]['product'] = $arr[0]->concatenated_product;
				$inquirylines[$key]['uom'] = $arr[0]->uom_code;
				$inquirylines[$key]['pdt_description']=$value->product_description;
			}
			else
			{
				$inquirylines[$key]['product'] = '';
				$inquirylines[$key]['uom'] = 0;
				$inquirylines[$key]['pdt_description']=$value->product_description;
			}
		}
		else
		{
			$arr=$this->getUom($value->uom_code_id);
			$inquirylines[$key]['product'] = $value->product_description;
			$inquirylines[$key]['uom'] = $arr;
			$inquirylines[$key]['pdt_description']=$value->product_description;
		}
		$inquirylines[$key]['qty']=$value->qty;
		$inquirylines[$key]['comments']=$value->comments;
		}
		$terms_lines=array();
		$terms_lines==array("element_content"=>"");
		
		/***To Get company name ***/
			$company=$this->getCompany($row[0]->company_id);
            if(!empty($company))
			{
			$company_name=$company[0]->company_name;
			$company_logo_name=$company[0]->company_logo_name;
			$this->data['company_name']= $company_name;
            $this->data['company_logo_name']= $company_logo_name;
			$this->data['cin_no']= $company[0]->cin_no;
	        $this->data['gst_no']=$company[0]->gst_no;
			}
		/****** end ********/
$terms_condition=\DB::table('a_rpt_displayelements_hdr_t')->leftjoin('a_rpt_displayelements_lines_t', 'a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id', '=', 'a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id')->select('a_rpt_displayelements_lines_t.element_content')->where('a_rpt_displayelements_hdr_t.report_source',123)->where('a_rpt_displayelements_lines_t.element_name','TERMS&CONDITIONS')->get();
			
	 if(count($terms_condition)>0){
	$this->data['terms_condition']=$terms_condition;
	 }
	 else{
$this->data['terms_condition']=[];
	 }

		$this->data['terms']= $terms_lines;
		$this->data['result']  =$inquirylines;
		$this->data['print']="PRINT";
       /*Purpose For :Mail Function*/       
        if(isset($_GET['mail'])){
            if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
				Config::set('mail.username', \Session::get('user_email'));
				Config::set('mail.password', \Session::get('user_password'));
			}
          $this->data['print']="PRINTS";	
			\Mail::send('purchaseenquiry.enquiry_print',$this->data, function($message){
			if(!empty($_GET['cc'])){                         
                  $cc=explode(',',$_GET['cc']);
                  $message->cc($cc);
              }else{
                 $cc=array(); 
              }
              $msg=$_GET['msg'];
			  $message->to(explode(",",$_GET['mail']));
			  $message->setBody($msg);
			  $message->subject("Purchase Enquiry" . $this->data['enquiry_number']);
			  $row = \DB::table('p_enquiry_hdr_t')->where('enquiry_hdr_id',$this->data['enquiry_hdr_id'])->get();
				if($row[0]->attachment_file!=''){
					$file_a=json_decode($row[0]->attachment_file);
				foreach($file_a as $k1=>$v1){
				 $message->attach('uploads/purchaseenquiry/P'.$this->data['enquiry_hdr_id'].'/'.$v1);
				}
			}
            $message->attach('uploads/purchaseenquiry/P_'.stripslashes($this->data['enquiry_number']).'.pdf');
        });
         return 1;
        }		
		 if(isset($_GET['mails'])){
			$this->data['print']="PRINTS";
			 return view('purchaseenquiry.enquiry_print', $this->data);
		 }
		/*End Mail Function*/ 
		return view('purchaseenquiry.enquiry_print', $this->data);

	}
    /* Karthigaa purpose for delete function */
    public function delete(Request $request,$id=null,$type=null)
    {
        $source="ENQUIRY";
        $column = array('reference_id','reference_id');
        $table = array('p_quotation_hdr_t','p_po_hdr_t');
        $column1=array('source','source');
        
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->where($column1[$i],$source)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
        if($j==0){
            Purchaseenquiry::destroy($id);
            $query = \DB::table('p_enquiry_lines_t')->where('enquiry_hdr_id',$id)->delete();
             /**Auditlog**/
                    $action = "Delete";
                    $this->auditlog($id,"purchaseenquiry",$action,$id,"p_enquiry_hdr_t");
        }
     return $j;
    }
  
	/*Purpose For Attachment File Save*/
    public function purchaseenquiryfilesave(Request $request){
    if($request->hasfile('email_attachment')){
        File::deleteDirectory(public_path('uploads/purchaseenquiry/P'.$_POST['enquiry_hdr_id']));

                    foreach($request->file('email_attachment') as $file)
                {
                    $name=$file->getClientOriginalName();

                    $file->move(public_path().'/uploads/purchaseenquiry/P'.$_POST['enquiry_hdr_id'].'/', $name);
                    $data[] = $name;
                }
        $attachfile_name=json_encode($data);
          \DB::update("update p_enquiry_hdr_t set attachment_file='".$attachfile_name."' where enquiry_hdr_id=".$_POST['enquiry_hdr_id']);
        return 1;
            }else{
                $var = File::deleteDirectory(public_path('uploads/purchaseenquiry/P'.$_POST['enquiry_hdr_id']));
             
              \DB::update("update p_enquiry_hdr_t set attachment_file='' where enquiry_hdr_id=".$_POST['enquiry_hdr_id']);
              return 2;
            }
    }
	/*End Purpose For Attachment File Save*/
	/*Purpose To get Product Details For Print*/
	function getProduct($id=null){
        $product=array();
        $product=\DB::table('m_products_t as pdt')
			->leftJoin('m_uom_codes_t as uom','uom.uom_code_id', '=', 'pdt.primary_uom_id')
			->select('uom.uom_code','pdt.concatenated_product','pdt.hsn_code')
			->where('pdt.product_id',$id)->get();

        if($product->isNotEmpty()){

			$date=date('Y-m-d');
			$tax=\DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='".$product[0]->hsn_code."' and start_date<='$date' and end_date>='$date' and active='Yes'");

		   if(!empty($tax))
		   {
			  $product['tax_group_id']=$tax[0]->tax_group_id;
		   }
			else
			{
				$product['tax_group_id']=0;
			}
           $product['concat_segment']=$product[0]->concatenated_product;
           $product['primary_uom_code']=$product[0]->uom_code;
	   $product['hsn_code']=$product[0]->hsn_code;
        return $product;


        }else{
            return 0;
        }

    }
	/*Purpose To get Uom Value For Print*/
	function getUom($id=null)
	{
		$uom=\DB::table('m_uom_codes_t')->where('uom_code_id',$id)->get();
		if($uom->isNotEmpty())
		{
			$uomcode = $uom[0]->uom_code;
		}
		else
		{
		$uomcode = 0;
		}
		return $uomcode;
	}
	/*Purpose To get Company Details For Print*/
        function getCompany($company=null)
		{
			$sql=array();
			$sql=\DB::SELECT("SELECT company_id,company_name,company_logo_name,cin_no,gst_no FROM `m_company_t` WHERE `company_id`=".$company."");
			if(!empty($sql))
			{
			return $sql;
			}
			else
			{
			return 0;
			}
		}
	/*Purpose To get Location Details For Print*/
	function getLocationwiseaddress()
    {
        $sql=array();
        $location=\Session::get('location');        
        $sql=\DB::SELECT('select * from m_location_t where location_id='.$location.'');     
        if(!empty($sql)){
            return $sql;
        }else{
            return 0;
        }
    }
      /* Karthigaa Purpose for load uom code based on product */
    public function poenquiryuom($product_id = null) 
        {
        $query = \DB::table('m_products_t')->where('product_id', $product_id)->get();
        if (count($query) > 0) {
            $uom_code = $query[0]->primary_uom_id;
        }
        return $uom_code;
        }
        /*End*/
		 /* Karthigaa Purpose for load part Number based on product and Supplier */
        function getpartno()
        {
           $supplier_id = $_GET['supplier_id'];
           $product_id = $_GET['product_id'];
           $query_list = \DB::table('m_manufacturer_partno_t')->where('product_id',$product_id)->where('manufacturer_source_value_id',$supplier_id)->get();
           if(count($query_list)>0)
               $result = $query_list[0]->manufacturer_partno_id;
            else
                $result = '';
            return $result;
        }
	 /*End*/

	
}
