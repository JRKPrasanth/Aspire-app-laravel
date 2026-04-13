<?php
namespace App\Http\Controllers;
use App\Soorder;
use App\Schemes;
use App\Soorderlines;
use App\schemeslines;
use App\customersites;
use App\Deliveryterms;
use Illuminate\Http\Request;
use Validator,DB,Input;
use Session;
use Config;
use DateTime,File;
use Yajra\DataTables\DataTables;

class SoorderController extends Controller
{
	public function __construct()
	{
            $this->data=array();
            $this->table="s_salesorder_hdr_t";
            $this->subtable="s_salesorder_lines_t";
            $this->pageModule="soorder";
            $this->model=new Soorder;
            $this->submodel=new Soorderlines;
            $this->data['pageModule']=$this->pageModule;
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['pageFormtype']='ajax';
            $this->data['notymsg']="no";
		    $this->data['urlmenu']=$this->indexs();
		
            if($this->data['pageMethod']=="soorder")
            {
                $this->data['status_type']="";
            }
            else if($this->data['pageMethod']=="salesorderapproval")
            {
                $this->data['status_type']="INITIATED";
            }
            else if($this->data['pageMethod']=="socancellation")
            {
                $this->data['status_type']="APPROVED";
            }
            else if($this->data['pageMethod']=="pickorder" || $this->data['pageMethod']=="invoicefromorder")
            {
                $this->data['status_type']="APPROVED";
            }
			else if($this->data['pageMethod']=="workorderfromso")
            {
                $this->data['status_type']="APPROVED";
            }
			else if($this->data['pageMethod']=="SalesOrderFromEnquiry")
            {
                $this->data['status_type']="INITIATED";
            }else 
            {
                $this->data['status_type']="APPROVED";
            }
		
	
	}
	//main page load function
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

        $this->data['customer']=$this->jqgridselect('m_customers_t','customer_id','customer_name'); 
		$this->data['salesperson']=$this->jqgridselect('hr_employee_t','employee_id','first_name');
		$this->data['price'] = $this->jqgridselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name');
       	$this->data['opt']='';
		$this->data['pageMethod']=\Request::route()->getName();
		
        return view('soorder.table',$this->data);
    }

    //get employee address
    public function employeeaddress($id=null){
		$add = '';
		$sql = \DB::table('hr_emp_contact')->select('hr_emp_contact.current_street','hr_emp_contact.current_flat_no','hr_emp_contact.current_street_address','hr_emp_contact.current_postal_code','m_countries_t.country_name','m_states_t.state_name','m_cities_t.city_name')->leftjoin('m_countries_t','hr_emp_contact.current_country',"=",'m_countries_t.country_id')->leftjoin('m_states_t','hr_emp_contact.current_state',"=",'m_states_t.state_id')->leftjoin('m_cities_t','hr_emp_contact.current_city',"=",'m_cities_t.city_id')->where('employee_id',$id)->get();
		if(count($sql) > 0 ){
			$add = $sql[0]->current_flat_no.",".$sql[0]->current_street.",".$sql[0]->current_street_address.",".$sql[0]->city_name.",".$sql[0]->current_postal_code.",".$sql[0]->state_name.",".$sql[0]->country_name;
		//	dd($add);
		}
		return $add;
	}
	
	//jqgrid load function
    public function soordergriddata()
	{

		$page_mth = \Request::route()->getName();
		$wh='';
		$app_id=\Session::get('id');
		$col_name='';
		if($_GET['pagemethod']=='invoicefromorder')
		{
		 	$wh.=" and ( s_salesorder_hdr_t.invoice_status=0 and json_contains(s_salesorder_hdr_t.approver_id ,'0')=1 and s_salesorder_hdr_t.order_status_id='INITIATED') or (s_salesorder_hdr_t.invoice_status=0 and  s_salesorder_hdr_t.order_status_id='APPROVED')";
		
		    
		
		}else if ($_GET['pagemethod']=='salesorderapproval') {
		    $groupname=\Session::get('groupname');
		    if($groupname=="14" || $groupname=="4")
		{
		    $emp_id=\Session::get('emp_id');
            $cus_id=\DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");
		    if($cus_id[0]->dis !=''){
		    $wh.=" and s_salesorder_hdr_t.ship_to_customer_id in (".$cus_id[0]->dis.")";
		    }else{
		    $wh.=" ";      
		    }
		}

			$wh.=" and json_contains(s_salesorder_hdr_t.approver_id ,'".$app_id."')=1 ";
		}else if ($_GET['pagemethod']=='dispatchfrmso') {
			$wh.=" and  (s_salesorder_hdr_t.order_status_id='APPROVED')";
			$col_name="order_status_id";
		$op="=";
		$status_val="'APPROVED'";
		}

		if($_GET['status']!='')
		{
			if(isset($_GET['pagemethod'])){
				if($_GET['pagemethod']!='invoicefromorder'){
					$wh.=" and s_salesorder_hdr_t.order_type_id !='LABOUR' and s_salesorder_hdr_t.order_status_id='".$_GET['status']."'";
					$col_name="order_status_id";
		            $op="=";
		            $status_val="'".$_GET['status']."'";
				}
			}else{
				$wh.=" and s_salesorder_hdr_t.order_type_id !='LABOUR' and s_salesorder_hdr_t.order_status_id='".$_GET['status']."'";
				$col_name="order_status_id";
		            $op="=";
		            $status_val="'".$_GET['status']."'";
			}		
		}else{
			if($_GET['pagemethod']=='copysoorder'){
			$wh.=" AND s_salesorder_hdr_t.order_status_id!='DRAFT'";	
			$col_name="order_status_id";
		            $op="!=";
		            $status_val="'DRAFT'";
		}
		}
	
		
		$depart=\Session::get('groupname');
		$compy=\Session::get('companyid');
		$loc=\Session::get('location');

		if($depart=="14")
		{
		    $emp_id=\Session::get('emp_id');
		    
            $cus_id=\DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");
		
		    $wh.=" and s_salesorder_hdr_t.ship_to_customer_id in (".$cus_id[0]->dis.")";
		}
		
	if($col_name != ''){
    $wh.=$grid_data=$this->grid_statuscheck('s_salesorder_hdr_t','sales_order_date',$col_name,$op,$status_val);
    } else {
        $wh.=$grid_data=$this->grid_check('s_salesorder_hdr_t','sales_order_date');
    }

		$SQL = "SELECT
		    s_salesorder_hdr_t.sales_hdr_id,
		    s_salesorder_hdr_t.`sales_order_no`,
		    s_salesorder_hdr_t.`sales_order_date`,
		    s_salesorder_hdr_t.`order_type_id`,
		    s_salesorder_hdr_t.`order_status`,
		    s_salesorder_hdr_t.`bill_to_address_id`,
		    s_salesorder_hdr_t.`order_status_id`,
		    s_salesorder_hdr_t.`proforma_invoice`,
		    s_salesorder_hdr_t.`savestatus`,
		    s_salesorder_hdr_t.`invoice_status`,
		    s_salesorder_hdr_t.`contact_number`,
		    s_salesorder_hdr_t.`contact_person`,
		    s_salesorder_hdr_t.`bill_to_address_id`,
		    round(s_salesorder_hdr_t.order_total,2) as order_total,
		    hr_employee_t.first_name,
		    b.first_name as created_by,
            A.first_name as last_updated_by,
		    m_customers_t.customer_name,
		    s_quote_hdr_t.quote_no,
			s_salesorder_hdr_t.`ship_to_customer_id`,
		    i_pricelist_hdr_t.pricelist_name,
		    CONCAT(
		        cs.`customer_site_name`,
		        ',',
		        cs.`address`,
		        ',',
		        mc.city_name,
		        ',',
		        st.state_name,
		        '-',
		        cs.`pincode`,
		        ',',
		        c.country_name,
		        ',',
		        cs.`contact_number`
		    ) AS billingaddress,
		      CONCAT(
		        mcs.`customer_site_name`,
		        ',',
		        mcs.`address`,
		        ',',
		        mc.city_name,
		        ',',
		        st.state_name,
		        '-',
		        mcs.`pincode`,
		        ',',
		        c.country_name,
		        ',',
		        mcs.`contact_number`
		    ) AS shippingaddress
			    FROM
			    `s_salesorder_hdr_t`
			LEFT JOIN hr_employee_t ON
			    (
			        hr_employee_t.employee_id = s_salesorder_hdr_t.`employee_id`
			    )
			left join hr_employee_t A on (A.employee_id = s_salesorder_hdr_t.last_updated_by)
            left join hr_employee_t b on (b.employee_id = s_salesorder_hdr_t.created_by)    
			LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_salesorder_hdr_t.`ship_to_customer_id`
			LEFT JOIN s_quote_hdr_t ON(
			        s_quote_hdr_t.quote_hdr_id = s_salesorder_hdr_t.`ar_quote_hdr_id`
			    )
			LEFT JOIN i_pricelist_hdr_t ON
			    (
			        i_pricelist_hdr_t.pricelist_hdr_id = s_salesorder_hdr_t.pricelist_id
			    )
			LEFT JOIN `m_customer_sites_t` cs ON
			    (
			        cs.customer_site_id = s_salesorder_hdr_t.bill_to_address_id
			    )
			    
			    LEFT JOIN `m_customer_sites_t` mcs ON
			    (
			        mcs.customer_site_id = s_salesorder_hdr_t.ship_to_address_id
			    )
	    	    
			LEFT JOIN m_countries_t c ON
			    (cs.`country` = c.country_id)
			LEFT JOIN m_states_t st ON
			    (st.state_id = cs.`state`)
			LEFT JOIN m_cities_t mc ON
			    (mc.city_id = cs.city)
				
				where 1=1 $wh order by s_salesorder_hdr_t.sales_hdr_id DESC";
	

		$result = \DB::select($SQL);

		return DataTables::of($result)->make(true);

	}

	/*create function*/
    public function create()
    {
	dd(Session::get("decimal"));
        return view('soorder.form',$this->data);
    }
    
    public function store(Request $request)
	{
		
			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file','custype','enable-masterdetail'
    ]);
			// Normalize "bulk_" keys once
		$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
		$data = $this->validatePost($form, $this->table, 'header');
		
	  	$data['order_status']='INITIATED';

		$lines_data = $this->validatePost($form, $this->subtable, 'lines');
		
	    if($data['order_status_id'] != "DRAFT" && $data['order_status_id'] != "REJECTED"){

			//dd($data['order_total']);
	    	if($data['order_total'] == ""){
	        	$data['order_total'] = "0";
	        }

	    	$approverid= $this->Approvaldatacheck('soorder',$data['order_total']);
			
	        if($approverid == "0" ){
			
	        	 $data['approver_id']=\Session::get('id');
	        	 $data['order_status_id'] = "APPROVED";
	        }else{
				
	        	$data['approver_id']=$approverid;
	        }
	    }
	    
		if($_POST['source']== "ENQUIRY"){
			 \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id', $_POST['reference_id'])->update(['order_status' => '1']);
		}
		
		if($_POST['source']== "QUOTE"){
			 \DB::table('s_quote_hdr_t')->where('quote_hdr_id', $_POST['reference_id'])->update(['order_status' => '1']);
		}
		if ($data['order_status_id'] == "INITIATED")
		{
			$order_status = 'Saved'.' Successfully';
		}
		else
		{
			$order_status = $data['order_status_id'].' Successfully';
		}
		
	    if ($_POST['sales_order_no'] =="")
	    {

              if($_POST['order_type_id'] =="EXPORT")
	        {
	           $seqno=$this->Seqnoe('EPO','s_salesorder_hdr_t',$_POST['order_type_id'],'salesexport_count');
	           $data['sales_order_no'] = $seqno[0];
               $data['salesexport_count'] = $seqno[1];
               
	        }
	        elseif($_POST['order_type_id'] =="EXPORT SAMPLE"){
	        	$seqno=$this->Seqnoe('EPOS','s_salesorder_hdr_t',$_POST['order_type_id'],'salesexports_count');
	        	$data['sales_order_no'] = $seqno[0];
                $data['salesexports_count'] = $seqno[1];
	        }else{
	        $seqno=$this->Seqnoe('SO','s_salesorder_hdr_t',$_POST['order_type_id'],'salesorder_count');
	        $data['sales_order_no'] = $seqno[0];
	        $data['salesorder_count'] = $seqno[1];
			}
	    }
	    else
	    {      
	        $seqno[0] = $_POST['sales_order_no']; 
	        /*****/
	        \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $_POST['sales_hdr_id'])->update(['order_status_id' => $_POST['order_status_id']]);		
		
	    }
	 
	    
	    \DB::beginTransaction();
	    try
	    {
			   $lines_data['pending_qty']=$lines_data['qty'];

			   if($_POST['sales_hdr_id'] == ''){
			   	$action = 'create';
			   }else{
			   	$action = 'edit';
			   }

			array_walk_recursive($lines_data, function (&$value) {
				if ($value === null || $value === '') {
					$value = 0;
				}
			});

	        $id=$this->model->insertRow($data);
	     
	        $lid=$this->submodel->subgridSave($lines_data,$id);
	        	
	        	$this->auditlog($id,"Sales Order",$action,$data,"s_salesorder_hdr_t");

	        $sales_id=$id;
	        
	        $sales_hdr_id = $request->input('sales_hdr_id');
	        if($sales_hdr_id == '')
	        {
	            if($request->hasfile('choosefile'))
	            {
	                foreach($request->file('choosefile') as $file)
	                {
	                    $name=$file->getClientOriginalName();

	                    $file->move(public_path().'/uploads/salesorderupload/SO'.$sales_id.'/', $name);  
	                    $dataupload[] = $name;  
	                }
	            }
	             $attachfile_name=json_encode($dataupload);	
	            \DB::update("update s_salesorder_hdr_t set attachfile_name='".$attachfile_name."' where sales_hdr_id='$sales_id'");
	            $this->data['notymsg']="yes"; 
	        }
	        else
	        {
	           
				$existing_file = $request->input('existing_file') ?? '';
				$choose_file   = $request->file('choosefile') ?? [];   

				$existing_file = explode(",", $existing_file);

				// CASE: No new file & existing file present
				if (count($choose_file) == 0 && count($existing_file) > 0) {

					// CASE: Existing file is empty string
					if (count($existing_file) == 1 && $existing_file[0] == '') {

						\DB::update("UPDATE s_salesorder_hdr_t 
									SET attachfile_name = '' 
									WHERE sales_hdr_id = ?", [$sales_id]);
					}


	                else
	                {
	                $get_attach = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$sales_id)->get();
	                $attach_file = json_decode($get_attach[0]->attachfile_name);
	                $attach_file1 =array();
	            
	                foreach($attach_file as $k=>$v)
	                {
	                    $attach_file1[]=$v;
	                }
	                $array_diff = array_diff($attach_file1,$existing_file);
	                
	                if(count($array_diff)>0)
	                {
	                    foreach($array_diff as $k =>$v)
	                    {
	                         unlink(public_path().'/uploads/salesorderupload/SO'.$sales_id.'/'.$v);  
	                    }
	                    $attachfile_name=json_encode($existing_file);
	                   
	                    \DB::update("update s_salesorder_hdr_t set attachfile_name='".$attachfile_name."' where sales_hdr_id='$sales_id'");
	                } 
	                }
	            }
	            else if(count($choose_file)>0 && count($existing_file)>0)
	            {
	                
	                 foreach($request->file('choosefile') as $file)
	                {
	                    $name=$file->getClientOriginalName();

	                    $file->move(public_path().'/uploads/salesorderupload/SO'.$sales_id.'/', $name);  
	                    $dataupload[] = $name;  
	                }
	                
	                $get_attach = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$sales_id)->get();
	                $attach_file = json_decode($get_attach[0]->attachfile_name);
	                $attach_file1 =array();
	            
	                foreach($attach_file as $k=>$v)
	                {
	                    $attach_file1[]=$v;
	                }
	                
	                $array_diff = array_diff($attach_file1,$existing_file);
	                
	                if(count($array_diff)>0)
	                {
	                    foreach($attach_file as $k =>$v)
	                    {
	                         unlink(public_path().'/uploads/salesorderupload/SO'.$sales_id.'/'.$v);  
	                    }
						$attachfile_name = array_merge($existing_file,$dataupload);
	                    $attachfile_name = json_encode($attachfile_name);
	                }
	                else
	                {
	                    $attachfile_name = array_merge($attach_file1,$dataupload);
	                    $attachfile_name = json_encode($attachfile_name);
	                }
	                
	                
	                \DB::update("update s_salesorder_hdr_t set attachfile_name='".$attachfile_name."' where sales_hdr_id='$sales_id'");
	                
	            }
	            else if(count($choose_file)>0 && count($existing_file)==0)
	            {
	                foreach($request->file('choosefile') as $file)
	                {
	                    $name=$file->getClientOriginalName();

	                    $file->move(public_path().'/uploads/salesorderupload/SO'.$sales_id.'/', $name);  
	                    $dataupload[] = $name;  
	                }
	                $attachfile_name=json_encode($dataupload);
	                \DB::update("update s_salesorder_hdr_t set attachfile_name='".$attachfile_name."' where sales_hdr_id='$sales_id'");
	            }
	        }


			if ($data['order_status_id'] == "INITIATED")
		{
			$noti_msg = "Sales Order ".$data['sales_order_no']. " Initiated";
			 $send_notification = $this->sendPopUpNotification($id,"SO APPROVAL",$noti_msg,"salesorderapproval");
		}
			
		if ($data['order_status_id'] == "APPROVED" || $data['order_status_id'] == "REJECTED")
		{
			$noti_msg = "Sales Order ".$data['sales_order_no']." ".$_POST['order_status_id'];
			 $send_notification = $this->sendPopUpHomeNoty($id,"SO APPROVED",$noti_msg,"soorder");
			
			\DB::table('notifications_t')->where('reference_source_id',$_POST['sales_hdr_id'])->where('reference_source','SO APPROVAL')->update(['read/unread' => 'read']);

		}
		  
			\DB::commit();
	        return response()->json(array('status' => 'success', 'message' => $order_status,'id' => $id,'lid' => $lid,'auto_no'=>$data['sales_order_no']));
	    }
	    catch(\Illuminate\Database\QueryException $e)
	    {
	        $message = explode('(', $e->getMessage());
	        $dbCode = rtrim($message[0], ']');
	        $dbCode = trim($dbCode, '[');
	        \DB::rollback();
	        return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
	    }

	}

	/*view function*/
	public function show($id=null,$msg = null)
    {
    	$this->data['pageMethod']=\Request::route()->getName();
		
            $headerdata = \DB::table('s_salesorder_hdr_t as qh')
            ->leftjoin('m_projects_t as p', 'qh.project_id', '=', 'p.project_id')
            ->leftjoin('hr_employee_t as s', 'qh.salesperson_id', '=', 's.employee_id')
            ->leftjoin('m_customers_t as c', 'qh.ship_to_customer_id', '=', 'c.customer_id')
            ->leftjoin('i_pricelist_hdr_t as pl','qh.pricelist_id','=','pl.pricelist_hdr_id')
            ->leftjoin('m_payment_methods_t as pm','qh.ar_payment_method_id','=','pm.payment_method_id')
            ->leftjoin('m_payment_terms_t as pt','qh.ar_payment_term_id','=','pt.payment_term_id')
            ->leftjoin('m_frieghtcarriers_hdr_t as fc','qh.freight_carrier_id','=','fc.ar_frieghtcarriers_hdr_id')
            ->leftjoin('m_frieghtterms_t as ft','qh.ar_frieghtterm_id','=','ft.frieghtterm_id')
            ->leftjoin('m_delivery_terms_t as dt','qh.ar_delivery_terms_id','=','dt.delivery_terms_id')
            ->leftjoin('m_discounts_hdr_t as dis','qh.discount_id','=','dis.ar_discount_hdr_id')
             ->select('p.project_name','pl.pricelist_name','pm.payment_method_name','dt.delivery_term_name','fc.carrier_name','ft.fob_point_name','pt.payment_term_name', 's.first_name','c.customer_name','qh.*','dis.discount_name','qh.employee_id')
		  ->where('qh.sales_hdr_id',$id)
            ->get();
		
	  $linesdata  = \DB::table('s_salesorder_hdr_t as qh')
            ->leftjoin('s_salesorder_lines_t as ql', 'qh.sales_hdr_id', '=', 'ql.sales_hdr_id')
            ->leftjoin('m_products_t as pr', 'ql.product_id', '=', 'pr.product_id')
            ->leftjoin('m_uom_codes_t as uom', 'ql.uom_code_id', '=', 'uom.uom_code_id')
            ->leftjoin('m_tax_group_t as tx', 'ql.tax_group_id', '=', 'tx.tax_group_id')
            ->leftjoin('f_gst_code_hdr_t as gst', 'ql.hsn_code', '=', 'gst.gst_code_hdr_id')
       ->select('pr.concatenated_product','pr.product_code','ql.free_qty', 'uom.uom_code','tx.tax_group_name','ql.line_no','qh.sales_hdr_id','ql.qty','ql.tax_excemption','ql.unit_price','ql.discount_percentage','ql.discount_amount','ql.tax_amount','ql.line_total','ql.comments','ql.product_description','gst.classification_code')
		  ->where('qh.sales_hdr_id',$id)
            ->get(); 
	  $this->data['headerdata']=$headerdata[0];
		if($headerdata[0]->employee_id!=0){
		$this->data['empname']=$this->idname("employee_number|first_name","hr_employee_t","employee_id",$headerdata[0]->employee_id);
		}else{
			$this->data['empname']="";
		}
		$this->data['row_id'] = $id;
	  $this->data['linesdata']=$linesdata;
	  $this->data['close']="soorder";
	  if($msg == "welcome" ){
	  	return $this->data;
	  }else if(isset($_GET['report'])){
			return view('soorder.report_view',$this->data);
		}else{

            $this->data['return_url']=$_GET['return'];
            //dd($this->data);
			return view('soorder.view',$this->data);
		}


    }

    /*view function*/
    function view($id=null,$msg=null)
	{

		
	  $headerdata = \DB::table('s_quote_hdr_t as qh')
            ->leftjoin('m_projects_t as p', 'qh.project_id', '=', 'p.project_id')
            ->leftjoin('hr_employee_t as s', 'qh.salesperson_id', '=', 's.employee_id')
            ->leftjoin('m_customers_t as c', 'qh.customerid', '=', 'c.customer_id')
       ->select('p.project_name', 's.first_name','c.customer_name','c.customer_number','qh.*')
		  ->where('qh.quote_hdr_id',$id)
            ->get();
		
		$this->data['quotedate']=date(\Session::get('p_date_format'),strtotime($headerdata[0]->quote_date));
		$this->data['pricelist_name']=$this->idname('pricelist_name','i_pricelist_hdr_t','pricelist_hdr_id',$headerdata[0]->quote_pricelist_id);
		$this->data['discount']=$this->idname('discount_name','m_discounts_hdr_t','ar_discount_hdr_id',$headerdata[0]->discount_id);
		$this->data['currency']=$this->idname('currency_code','f_account_currency_t','account_currency_id',$headerdata[0]->currency_id);
		$this->data['paymentterm']=$this->idname('payment_term_name','m_payment_terms_t','payment_term_id',$headerdata[0]->payment_term_id);
		$this->data['paymentmethod']=$this->idname('payment_method_name','m_payment_methods_t','payment_method_id',$headerdata[0]->payment_method_id);
		$this->data['freight']=$this->idname('carrier_name','m_frieghtcarriers_hdr_t','ar_frieghtcarriers_hdr_id',$headerdata[0]->frieghtcarriers_hdr_id);
		$this->data['freightterm']=$this->idname('fob_point_name','m_frieghtterms_t','frieghtterm_id',$headerdata[0]->frieghtterm_id);
		$this->data['delivery']=$this->idname('delivery_term_name','m_delivery_terms_t','delivery_terms_id',$headerdata[0]->delivery_terms_id);
		//dd($headerdata);
	  $linesdata  = \DB::table('s_quote_hdr_t as qh')
            ->leftjoin('s_quote_lines_t as ql', 'qh.quote_hdr_id', '=', 'ql.quote_hdr_id')
            ->leftjoin('m_products_t as pr', 'ql.product_id', '=', 'pr.product_id')
            ->leftjoin('m_uom_codes_t as uom', 'ql.uom_code_id', '=', 'uom.uom_code_id')
            ->leftjoin('m_tax_group_t as tx', 'ql.tax_group_id', '=', 'tx.tax_group_id')
            ->leftjoin('f_gst_code_hdr_t as hsn', 'ql.hsn_code', '=', 'hsn.gst_code_hdr_id')
            ->leftjoin('m_manufacturer_partno_t as part', 'ql.part_no', '=', 'part.manufacturer_partno_id')
       ->select('pr.concatenated_product','pr.product_code', 'uom.uom_code','tx.tax_group_name', 		'ql.line_no','qh.quote_hdr_id','ql.product_description','ql.qty','ql.unit_price','ql.discount_percentage','ql.discount_amount','ql.tax_amount','ql.line_total','ql.promised_date','ql.tax_excemption','ql.comments','hsn.classification_code','part.part_no')
		  ->where('qh.quote_hdr_id',$id)
            ->get();

	  $this->data['headerdata']=$headerdata[0];

	  $this->data['headerdata']->bill_to_address_id = $this->addressget($headerdata[0]->bill_to_address_id);
	  $this->data['headerdata']->ship_to_address_id = $this->addressget($headerdata[0]->ship_to_address_id);
	  $this->data['linesdata']=$linesdata;

	  	if($msg == "welcome"){
			return $this->data;
		}else{
	 
			 $this->data['return_url']=$_GET['return'];
			return view('soquote.view',$this->data);
		}
	}

	/*print function*/
    public function getPrint($id=null) {
	
		require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
		
	$headerdata = \DB::table('s_salesorder_hdr_t as qh')
		->leftjoin('m_projects_t as p', 'qh.project_id', '=', 'p.project_id')
		->leftjoin('hr_employee_t as s', 'qh.salesperson_id', '=', 's.employee_id')
		->leftjoin('m_customers_t as c', 'qh.ship_to_customer_id', '=', 'c.customer_id')
		->leftjoin('i_pricelist_hdr_t as pl','qh.pricelist_id','=','pl.pricelist_hdr_id')
		->select('qh.sales_hdr_id','p.project_name','pl.pricelist_name','qh.ship_to_customer_id','qh.company_id','qh.customer_po_number','qh.ar_delivery_terms_id','qh.remarks', 's.first_name','qh.reference_number','qh.freight_carrier_id','qh.ar_payment_term_id','c.customer_name','qh.bill_to_address_id','qh.ship_to_address_id','qh.sales_order_no','qh.so_ref_no','qh.sales_order_date','qh.order_type_id','qh.customer_po_number','qh.remarks','qh.order_tax','qh.order_total','qh.location_id','qh.company_id','qh.employee_id','qh.last_updated_by','qh.order_status_id')
		->where('qh.sales_hdr_id',$id)
        ->get();
	    
       // dd($headerdata);
		/********************************* header ********************************/
		//$this->data['despatch_through'] = $this->getFreight($row[0]->freight_carrier_id);
		if(!empty($headerdata))
		{
            if($headerdata[0]->customer_name!=null){
			$this->data['customer_name']=$headerdata[0]->customer_name;
			}else{
			$emp=\DB::select("select concat(first_name,COALESCE(last_name,'')) as empname from hr_employee_t where employee_id=".$headerdata[0]->employee_id);
			$this->data['customer_name']=$emp[0]->empname;	
			}
			$this->data['sales_hdr_id']=$headerdata[0]->sales_hdr_id;
			$this->data['sales_order_no']=$headerdata[0]->sales_order_no;
			$this->data['sales_order_date']=$headerdata[0]->sales_order_date;
			$this->data['so_ref_no']=$headerdata[0]->so_ref_no;
			$this->data['customer_po_number']=$headerdata[0]->customer_po_number;
			$this->data['order_type_id']=$headerdata[0]->order_type_id;	
			$this->data['order_status_id']=$headerdata[0]->order_status_id;
			$this->data['reference_number']=$headerdata[0]->reference_number;				
			//$this->data['customer_po_number']=$headerdata[0]->customer_po_number;				
			$this->data['remarks']=$headerdata[0]->remarks;				
			$this->data['despatch_through'] = $this->getFreight($headerdata[0]->freight_carrier_id);
			$this->data['payment_term'] = $this->getPaymentterm($headerdata[0]->ar_payment_term_id);
			$deliveryterm =Deliveryterms::where('delivery_terms_id',$headerdata[0]->ar_delivery_terms_id)->pluck('delivery_term_name')->first(); 
			$this->data['deliveryterm'] = $deliveryterm;
			//dd($this->data);
			$comp = \DB::table('m_company_t')->where('company_id',$headerdata[0]->company_id)->get();
			if($comp->isNotEmpty())
			{
			$this->data['gst_no'] 				  = $comp[0]->gst_no;	
			$this->data['pan_no'] 				  = $comp[0]->pan_no;	
			$this->data['email_id'] 			  = $comp[0]->email_id;	
			$this->data['cin_no'] 				  = $comp[0]->cin_no;	
			$this->data['excise_registration_no'] = $comp[0]->excise_registration_no;	
			$this->data['tax_reg_no'] 			  = $comp[0]->tax_reg_no;	
			$this->data['website_address'] 			  = $comp[0]->website_address;	
			}
			else
			{
			$this->data['gst_no'] 				= "";	
			$this->data['pan_no'] 				= "";	
			$this->data['email_id'] 				= "";	
			$this->data['cin_no'] 				= "";	
			$this->data['excise_registration_no'] = "";	
			$this->data['tax_reg_no'] 			= "";	
			$this->data['website_address'	] 			= "";	
			}
				
		}
		else
		{
			$this->data['customer_name']	  ='';
			$this->data['sales_order_no']     ='';
			$this->data['sales_order_date']   ='';
			$this->data['so_ref_no']		  ='';
			$this->data['customer_po_number'] ='';
			$this->data['order_status_id'] ='';
			$this->data['despatch_through']   ='';
			$this->data['payment_term']       ='';
			$this->data['reference_number']   ='';
			$this->data['remarks']   		  ='';
			$this->data['deliveryterm']       ='';
			$this->data['customer_po_number'] ='';
			$this->data['gst_no'] 				= "";	
			$this->data['pan_no'] 				= "";	
			$this->data['email_id'] 				= "";	
			$this->data['cin_no'] 				= "";	
			$this->data['excise_registration_no'] = "";	
			$this->data['tax_reg_no'] 			= "";	
		
		}
		
		/******************************* End ************************************/
		
		if($headerdata[0]->last_updated_by !=''){
		$reviewd_accepted = \DB::table('hr_employee_t')->where('employee_id',$headerdata[0]->last_updated_by)->get();
		$this->data['reviewd_accepted_by'] = $reviewd_accepted[0]->first_name;
        }
		
		/*********************** Bill To Address ********************************/

		$customer_bill=\DB::table('m_customer_sites_t')->where('customer_site_id',$headerdata[0]->ship_to_address_id)->get();
		$emp_address=\DB::table('hr_emp_contact')->where('employee_id',$headerdata[0]->employee_id)->get();
	//	dd($emp_address);
		if($headerdata[0]->ship_to_customer_id!=0){
		if(count($customer_bill) > 0)
		{
			if($customer_bill[0]->site_type=="SHIP_TO")
			{
			 $address=$this->data['address']=$customer_bill[0]->address;
			 $this->data['city']=$this->getCity($customer_bill[0]->city);
				if($this->data['city']!=0){
			 $city=$this->data['city_b']=$this->data['city'][0]->city_name;
				}else{ $city=$this->data['city_b']="";}
			 $this->data['state']=$this->getState($customer_bill[0]->state);
				if($this->data['state']!=0){
			 $state=$this->data['state_s']=$this->data['state'][0]->state_name;
				}else{ $state=$this->data['state_s']="";}
			 $this->data['country']=$this->getCountry($customer_bill[0]->country);
			 $country=$this->data['country_b']=$this->data['country'][0]->country_name;
			 $pincode=$this->data['pincode']=$customer_bill[0]->pincode;
			 $gst_no=$this->data['bcgst_no']=$customer_bill[0]->gst_no;
			 $contact=$this->data['contact_number']=$customer_bill[0]->contact_number; 
			$this->data['ship_to_address']=$address.",".$city.",".$state.",".$country.",".$pincode;
			if($customer_bill[0]->location_id!=0){
			 $dest=\DB::SELECT('select * from m_location_t where location_id='.$customer_bill[0]->location_id.'');
			 $this->data['destination'] = $dest[0]->location_name;
			
			}
				else{
					 $this->data['destination'] = '';
				}

			}
			else
			{
			 $this->data['address']='';
			 $this->data['city']='';
			 $this->data['state_s']='';
			 $this->data['country']='';
			 $this->data['pincode']='';
			 $this->data['bcgst_no']='';
			 $this->data['contact_number']='';
			 $this->data['ship_to_address']='';
			 $this->data['destination'] = '';

			}
		}}else if(count($emp_address)>0){
					 if($emp_address[0]->current_flat_no!=''){
                	$this->data['current_flat_no']=$emp_address[0]->current_flat_no.",";
                }else{
                	$this->data['current_flat_no']="";
                }
                if($emp_address[0]->current_street!=''){
                	$this->data['current_street']=$emp_address[0]->current_street.",";
                }else{
                	$this->data['current_street']="";
                }
				 $this->data['address']=$this->data['current_flat_no']."".$this->data['current_street']."".$emp_address[0]->current_street_address;
                $this->data['city']=$this->getCity($emp_address[0]->current_city);
				$city=$this->data['city'][0]->city_name;
                 $this->data['destination'] = $emp_address[0]->current_locality;
                $states=$this->getState($emp_address[0]->current_state);
                if($states !=0)
                {
                $this->data['state_id_ship']=$states[0]->state_code_no;
                $this->data['state_s']=$states[0]->state_name;
                $this->data['state_code_bill']=$states[0]->state_code;
                }
                else
                {
                $this->data['state_id_ship']=$states;
                $this->data['state_s']=$states;
                $this->data['state_code_bill']=$states; 
                }
                // if($emp_address[0]->current_flat_no!=''){
                // 	$this->data['current_flat_no']=$states[0]->current_flat_no.",";
                // }else{
                // 	$this->data['current_flat_no']="";
                // }
                // if($emp_address[0]->current_street!=''){
                // 	$this->data['current_street']=$states[0]->current_street.",";
                // }else{
                // 	$this->data['current_street']="";
                // }
                $this->data['country']=$this->getCountry($emp_address[0]->current_country);
				$country=$this->data['country'][0]->country_name;
                $this->data['pincode']=$emp_address[0]->current_postal_code;
				$empmobile=\DB::select('select * from hr_employee_t where employee_id='.$headerdata[0]->employee_id);
                $this->data['contact_number']=$empmobile[0]->work_telephone_number;
                 $this->data['contact_person']=$empmobile[0]->first_name;
                 $this->data['bcgst_no']='';
              
                $this->data['ship_to_address']=$this->data['address'].",".$city.",".$this->data['state_s'].",".$country.",".$this->data['pincode']; 

			}
		else
		{
			 $this->data['ship_to_address']='';
			 $this->data['address']='';
			 $this->data['city']='';
			 $this->data['state_s']='';
			 $this->data['country']='';
			 $this->data['pincode']='';
			 $this->data['bcgst_no']='';
			 $this->data['contact_number']='';
			 $this->data['ship_to_address']='';
			 $this->data['destination'] = '';
			 $this->data['state_s'] = '';
		}
		/**************************** End ***************************************/
			
			
		/********************* location based company address ***********************/
			
		$address=$this->getLocationwiseaddress($headerdata[0]->location_id);
		//dd($address);
		if(!empty($address))
		{
			$location_name=$address[0]->location_name;
			$this->data['location_name_l']= $location_name;
			$address1=$address[0]->address;
			$this->data['address_l']= $address1;
			$street =$address[0]->street_name;
			$this->data['street_name_l']= $street;
			$location=$address[0]->location_name;
			$this->data['location_l']= $location;
			$area=$address[0]->area;
			$this->data['area_l']= $area;
			$comppincode=$address[0]->pincode;
			$this->data['comppincode']= $comppincode;
			
			$this->data['company_gst_no_l']= $address[0]->gst_no;
			$city_l=$this->data['city_l']=$this->getCity($address[0]->city_id);
			$city=$city_l[0]->city_name;
			$state_l=$this->data['state_l']=$this->getState($address[0]->state_id);
			$state=$state_l[0]->state_name;
			$this->data['state_code']=$state_l[0]->state_code_no;
			$this->data['state_name']=$state_l[0]->state_name;
			$country_l=$this->data['country_l']=$this->getCountry($address[0]->country_id);
			$country=$country_l[0]->country_name;
			
		}
			$this->data['company_address'] = $address1.",".$area.",".$city.",".$state.",".$country."-".$comppincode;
			
			
		/******************************** end *************************************/
		
		
		/************************* location based company *************************/
		
		$company=$this->getCompany($headerdata[0]->company_id);
		//dd($company);
		if(!empty($company))
		{
			$company_name=$company[0]->company_name;
			$this->data['company_name']= $company_name;
			$this->data['companycontact_no']="+91-44-".$company[0]->contact_no;
			
		}
		
		/******************************** end *************************************/
			$this->data['company_logo']=\Session::get('companylogo');
	    /******************************* Lines ************************************/
	
			$linesdata  = \DB::table('s_salesorder_hdr_t as qh')
			->leftjoin('s_salesorder_lines_t as ql', 'qh.sales_hdr_id', '=', 'ql.sales_hdr_id')
			->leftjoin('m_products_t as pr', 'ql.product_id', '=', 'pr.product_id')
			->leftjoin('m_uom_codes_t as uom', 'ql.uom_code_id', '=', 'uom.uom_code_id')
			->leftjoin('m_tax_group_t as tx', 'ql.tax_group_id', '=', 'tx.tax_group_id')
			->select('pr.concatenated_product', 'uom.uom_code','tx.tax_group_name','ql.free_qty', 'ql.line_no','qh.sales_hdr_id','ql.qty','ql.unit_price','ql.tax_excemption','ql.discount_percentage','ql.discount_amount','ql.tax_amount','ql.line_total','ql.comments')
			->where('qh.sales_hdr_id',$id)
			->get(); 
			$totqty = 0;
			foreach($linesdata as $key=>$value)
			{
			
				if(!empty($value))
				{	
					$lines[$key]['line_no']=$value->line_no;
					$lines[$key]['product']=$value->concatenated_product;
					$lines[$key]['qty']=$value->qty;
					$totqty = $totqty + $value->qty;
					$lines[$key]['unit_price']=$value->unit_price;
					$lines[$key]['uom_code']=$value->uom_code;
					$lines[$key]['free_qty']=$value->free_qty;
					$lines[$key]['amount']=$lines[$key]['qty']*$lines[$key]['unit_price'];
				}
				else
				{
					$lines[$key]['line_no']='';
					$lines[$key]['product']='';
					$lines[$key]['qty']='';
					$lines[$key]['uom_code']="";
					$lines[$key]['free_qty']="";
					$totqty = $totqty + 0;
					$lines[$key]['unit_price']='';
					$lines[$key]['amount']='';
				}
			}
			$this->data['linedata']=$lines;
			$this->data['totqty']=$totqty;
			//dd($this->data['line']);
			
			$this->data['print']="PRINT";
	   		$this->data['print_val'] = '1';
			
			  $terms_condition=\DB::table('a_rpt_displayelements_hdr_t')->leftjoin('a_rpt_displayelements_lines_t', 'a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id', '=', 'a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id')->select('a_rpt_displayelements_lines_t.element_content')->where('a_rpt_displayelements_hdr_t.report_source',120)->where('a_rpt_displayelements_lines_t.element_name','TERMS&CONDITIONS')->get();
			
	 	if(count($terms_condition)>0){
			$this->data['terms_condition']=$terms_condition;
		 }
		 else{
			$this->data['terms_condition']=[];
	 	}
		   if(isset($_GET['mail']))
            {
           /*     if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
        Config::set('mail.username', \Session::get('user_email'));
        Config::set('mail.password', \Session::get('user_password'));
    }   */
// dd(\Session::get('user_password'));
                $this->data['print']="PRINTS";



                \Mail::send('soorder.soorder_print',$this->data, function($message)
                {
                  if(!empty($_GET['cc'])){

                  $cc=explode(',',$_GET['cc']);
                  $message->cc($cc);
              }else{
                 $cc=array();
              }

                    $msg=$_GET['msg'];
                    // dd($_GET['mail']);
                      $message->to(explode(",",$_GET['mail']));
                      // $message->to("i5techerp@gmail.com");
                    $message->subject("Salesorder". $this->data['sales_order_no']);

                     $message->setBody($msg);
                   $return=DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$this->data['sales_hdr_id'])->get();
            $message->attach('Uploads/salesorderupload/SO_'.stripslashes($this->data['sales_order_no']).'.pdf');
            if($return[0]->attachfile_name!=''){
                $file_a=json_decode($return[0]->attachfile_name);

                foreach($file_a as $k1=>$v1){

                 $message->attach('Uploads/salesorderupload/SO'.$this->data['sales_hdr_id'].'/'.$v1);
                }
            }
                    

                });
                            return 1;


            }

			 if(isset($_GET['mails'])){

          $this->data['print']="PRINTS";
           // dd($this->data);
             return view('soorder.soorder_print', $this->data);
         }
	return view('soorder.soorder_print',$this->data);
	
	}
	
	
	/*print function*/
    public function getproformastdprint($id=null)
	{
	
	require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
		
	$headerdata = \DB::table('s_salesorder_hdr_t as qh')
		->leftjoin('m_projects_t as p', 'qh.project_id', '=', 'p.project_id')
		->leftjoin('hr_employee_t as s', 'qh.salesperson_id', '=', 's.employee_id')
		->leftjoin('m_customers_t as c', 'qh.ship_to_customer_id', '=', 'c.customer_id')
		->leftjoin('i_pricelist_hdr_t as pl','qh.pricelist_id','=','pl.pricelist_hdr_id')
		->leftjoin('s_schemes_lines_t','s_schemes_lines_t.schemes_hdr_id','=','qh.cash_discount')
		->select('qh.sales_hdr_id','p.project_name','pl.pricelist_hdr_id','pl.pricelist_name','qh.ship_to_customer_id','qh.company_id','qh.customer_po_number','qh.ar_delivery_terms_id','qh.remarks','qh.trade_discount','qh.trade_discount_pre','qh.tcs_applicable','qh.tcs_amount', 's.first_name','qh.reference_number','qh.freight_carrier_id','qh.ar_payment_term_id','c.customer_name','qh.bill_to_address_id','qh.ship_to_address_id','qh.sales_order_no','qh.so_ref_no','qh.sales_order_date','qh.order_type_id','qh.order_status_id','qh.customer_po_number','qh.remarks','qh.order_tax','qh.order_total','qh.location_id','qh.company_id','qh.employee_id','s_schemes_lines_t.schemes_type_value')
		->where('qh.sales_hdr_id',$id)
        ->get();
	    
       // dd($headerdata);
		/********************************* header ********************************/
		//$this->data['despatch_through'] = $this->getFreight($row[0]->freight_carrier_id);
		if(!empty($headerdata))
		{
            if($headerdata[0]->customer_name!=null){
			$this->data['customer_name']=$headerdata[0]->customer_name;
			}else{
			$emp=\DB::select("select concat(first_name,COALESCE(last_name,'')) as empname from hr_employee_t where employee_id=".$headerdata[0]->employee_id);
			$this->data['customer_name']=$emp[0]->empname;	
			}
			$this->data['sales_hdr_id']=$headerdata[0]->sales_hdr_id;
			$this->data['sales_order_no']=$headerdata[0]->sales_order_no;
			$this->data['sales_order_date']=$headerdata[0]->sales_order_date;
			$this->data['so_ref_no']=$headerdata[0]->so_ref_no;
			$this->data['customer_po_number']=$headerdata[0]->customer_po_number;
			$this->data['order_type_id']=$headerdata[0]->order_type_id;	
		    $this->data['order_status_id']=$headerdata[0]->order_status_id;
			$this->data['reference_number']=$headerdata[0]->reference_number;				
			//$this->data['customer_po_number']=$headerdata[0]->customer_po_number;	
			$this->data['schemes_type_value']=$headerdata[0]->schemes_type_value;
			$this->data['trade_discount']=$headerdata[0]->trade_discount;
            $this->data['trade_discount_pre']=$headerdata[0]->trade_discount_pre;
            $this->data['tcs_applicable']=$headerdata[0]->tcs_applicable;
            $this->data['tcs_amount']=$headerdata[0]->tcs_amount;
			$this->data['remarks']=$headerdata[0]->remarks;				
			$this->data['despatch_through'] = $this->getFreight($headerdata[0]->freight_carrier_id);
			$this->data['payment_term'] = $this->getPaymentterm($headerdata[0]->ar_payment_term_id);
			//$deliveryterm =Deliveryterms::where('delivery_terms_id',$headerdata[0]->ar_delivery_terms_id)->pluck('delivery_term_name')->first(); 
			//$this->data['deliveryterm'] = $deliveryterm;
			$deliveryterm =Deliveryterms::where('delivery_terms_id',$headerdata[0]->ar_delivery_terms_id)->select('delivery_term_name','remarks')->get(); 
            if(count($deliveryterm)>0)
            {
            $this->data['deliveryterm'] = $deliveryterm[0]->delivery_term_name;
            $this->data['del_remarks'] = $deliveryterm[0]->remarks;
            }else{
            $this->data['deliveryterm'] = '';
            $this->data['del_remarks'] = '';  
            }
			//dd($this->data);
			$comp = \DB::table('m_company_t')->where('company_id',$headerdata[0]->company_id)->get();
			if($comp->isNotEmpty())
			{
			$this->data['cmp_gst_no'] 				  = $comp[0]->gst_no;	
			$this->data['pan_no'] 				  = $comp[0]->pan_no;	
			$this->data['email_id'] 			  = $comp[0]->email_id;	
			$this->data['cin_no'] 				  = $comp[0]->cin_no;	
			$this->data['excise_registration_no'] = $comp[0]->excise_registration_no;	
			$this->data['tax_reg_no'] 			  = $comp[0]->tax_reg_no;	
			$this->data['website_address'] 			  = $comp[0]->website_address;	
			}
			else
			{
			$this->data['cmp_gst_no'] 				= "";	
			$this->data['pan_no'] 				= "";	
			$this->data['email_id'] 				= "";	
			$this->data['cin_no'] 				= "";	
			$this->data['excise_registration_no'] = "";	
			$this->data['tax_reg_no'] 			= "";	
			$this->data['website_address'	] 			= "";	
			}
				
		}
		else
		{
			$this->data['customer_name']	  ='';
			$this->data['sales_order_no']     ='';
			$this->data['sales_order_date']   ='';
			$this->data['order_status_id']    ='';
			$this->data['so_ref_no']		  ='';
			$this->data['customer_po_number'] ='';
			$this->data['despatch_through']   ='';
			$this->data['payment_term']       ='';
			$this->data['reference_number']   ='';
			$this->data['remarks']   		  ='';
			$this->data['deliveryterm']       ='';
			$this->data['customer_po_number'] ='';
			$this->data['gst_no'] 				= "";	
			$this->data['pan_no'] 				= "";	
			$this->data['email_id'] 				= "";	
			$this->data['cin_no'] 				= "";	
			$this->data['excise_registration_no'] = "";	
			$this->data['tax_reg_no'] 			= "";
			$this->data['schemes_type_value']="";
			$this->data['trade_discount']="";
            $this->data['trade_discount_pre']="";
		
		}
		
		if(isset($id)){
        $currency_type=DB::table('s_salesorder_hdr_t')->leftjoin('f_account_currency_t','f_account_currency_t.account_currency_id','=','s_salesorder_hdr_t.invoice_currency')
        ->select('f_account_currency_t.currency_code')->where('sales_hdr_id',$id)->get(); 
        $this->data['invoice_currency']=$currency_type[0]->currency_code;
        }
		
		/******************************* End ************************************/
		
		/*********************** Bill To Address ********************************/

		$customer_bill=\DB::table('m_customer_sites_t')->where('customer_site_id',$headerdata[0]->ship_to_address_id)->get();
		$emp_address=\DB::table('hr_emp_contact')->where('employee_id',$headerdata[0]->employee_id)->get();
	//	dd($emp_address);
		if($headerdata[0]->ship_to_customer_id!=0){
		if(count($customer_bill) > 0)
		{
			if($customer_bill[0]->site_type=="SHIP_TO")
			{
			 $address=$this->data['address']=$customer_bill[0]->address;
			 $this->data['city']=$this->getCity($customer_bill[0]->city);
				if($this->data['city']!=0){
			 $city=$this->data['city_b']=$this->data['city'][0]->city_name;
				}else{ $city=$this->data['city_b']="";}
			 $this->data['state']=$this->getState($customer_bill[0]->state);
				if($this->data['state']!=0){
			 $state=$this->data['state_name_ship']=$this->data['state'][0]->state_name;
				}else{ $state=$this->data['state_name_ship']="";}
			 $this->data['country']=$this->getCountry($customer_bill[0]->country);
			 $country=$this->data['country_b']=$this->data['country'][0]->country_name;
			 $pincode=$this->data['pincode']=$customer_bill[0]->pincode;
			 $gst_no=$this->data['ship_gst_no']=$customer_bill[0]->gst_no;
			 $contact=$this->data['contact_number']=$customer_bill[0]->contact_number; 
			$this->data['ship_to_address_1']=$address.",".$city.",".$state.",".$country.",".$pincode;
			if($customer_bill[0]->location_id!=0){
			 $dest=\DB::SELECT('select * from m_location_t where location_id='.$customer_bill[0]->location_id.'');
			 $this->data['destination'] = $dest[0]->location_name;
			
			}
				else{
					 $this->data['destination'] = '';
				}

			}
			else
			{
			 $this->data['address']='';
			 $this->data['city']='';
			 $this->data['state_name_ship']='';
			 $this->data['country']='';
			 $this->data['pincode']='';
			 $this->data['ship_gst_no']='';
			 $this->data['contact_number']='';
			 $this->data['ship_to_address_1']='';
			 $this->data['destination'] = '';

			}
		}}else if(count($emp_address)>0){
					 if($emp_address[0]->current_flat_no!=''){
                	$this->data['current_flat_no']=$emp_address[0]->current_flat_no.",";
                }else{
                	$this->data['current_flat_no']="";
                }
                if($emp_address[0]->current_street!=''){
                	$this->data['current_street']=$emp_address[0]->current_street.",";
                }else{
                	$this->data['current_street']="";
                }
				 $this->data['address']=$this->data['current_flat_no']."".$this->data['current_street']."".$emp_address[0]->current_street_address;
                $this->data['city']=$this->getCity($emp_address[0]->current_city);
				$city=$this->data['city'][0]->city_name;
                 $this->data['destination'] = $emp_address[0]->current_locality;
                $states=$this->getState($emp_address[0]->current_state);
                if($states !=0)
                {
                $this->data['state_id_ship']=$states[0]->state_code_no;
                $this->data['state_name_ship']=$states[0]->state_name;
                $this->data['state_code_bill']=$states[0]->state_code;
                }
                else
                {
                $this->data['state_id_ship']=$states;
                $this->data['state_name_ship']=$states;
                $this->data['state_code_bill']=$states; 
                }
                // if($emp_address[0]->current_flat_no!=''){
                // 	$this->data['current_flat_no']=$states[0]->current_flat_no.",";
                // }else{
                // 	$this->data['current_flat_no']="";
                // }
                // if($emp_address[0]->current_street!=''){
                // 	$this->data['current_street']=$states[0]->current_street.",";
                // }else{
                // 	$this->data['current_street']="";
                // }
                $this->data['country']=$this->getCountry($emp_address[0]->current_country);
				$country=$this->data['country'][0]->country_name;
                $this->data['pincode']=$emp_address[0]->current_postal_code;
				$empmobile=\DB::select('select * from hr_employee_t where employee_id='.$headerdata[0]->employee_id);
                $this->data['contact_number']=$empmobile[0]->work_telephone_number;
                 $this->data['contact_person']=$empmobile[0]->first_name;
                 $this->data['ship_gst_no']='';
              
                $this->data['ship_to_address_1']=$this->data['address'].",".$city.",".$this->data['state_s'].",".$country.",".$this->data['pincode']; 

			}
		else
		{
			 $this->data['ship_to_address_1']='';
			 $this->data['address']='';
			 $this->data['city']='';
			 $this->data['state_name_ship']='';
			 $this->data['country']='';
			 $this->data['pincode']='';
			 $this->data['ship_gst_noship_gst_no']='';
			 $this->data['contact_number']='';
			 $this->data['ship_to_address_1']='';
			 $this->data['destination'] = '';
			 $this->data['state_s'] = '';
		}
		/**************************** End ***************************************/
			
			
		/********************* location based company address ***********************/
			
		$address=$this->getLocationwiseaddress($headerdata[0]->location_id);
		//dd($address);
		if(!empty($address))
		{
			$location_name=$address[0]->location_name;
			$this->data['location_name_l']= $location_name;
			$address1=$address[0]->address;
			$this->data['address_l']= $address1;
			$street =$address[0]->street_name;
			$this->data['street_name_l']= $street;
			$location=$address[0]->location_name;
			$this->data['location_l']= $location;
			$area=$address[0]->area;
			$this->data['area_l']= $area;
			$comppincode=$address[0]->pincode;
			$this->data['comppincode']= $comppincode;
			
			$this->data['company_gst_no_l']= $address[0]->gst_no;
			$city_l=$this->data['city_l']=$this->getCity($address[0]->city_id);
			$city=$city_l[0]->city_name;
			$state_l=$this->data['state_l']=$this->getState($address[0]->state_id);
			$state=$state_l[0]->state_name;
			$this->data['state_code']=$state_l[0]->state_code_no;
			$this->data['state_name']=$state_l[0]->state_name;
			$country_l=$this->data['country_l']=$this->getCountry($address[0]->country_id);
			$country=$country_l[0]->country_name;
			
		}
			$this->data['company_address'] = $address1.",".$city.",".$state.",".$country."-".$comppincode;
			
			
		/******************************** end *************************************/
		
		
		/************************* location based company *************************/
		
		$company=$this->getCompany($headerdata[0]->company_id);
		//dd($company);
		if(!empty($company))
		{
			$company_name=$company[0]->company_name;
			$this->data['company_name']= $company_name;
			$this->data['comp_contact_no']="+91-44-".$company[0]->contact_no;
			
		}
		
		/******************************** end *************************************/
			$this->data['company_logo']=\Session::get('companylogo');
	    /******************************* Lines ************************************/
	
			$linesdata  = \DB::table('s_salesorder_hdr_t as qh')
			->leftjoin('s_salesorder_lines_t as ql', 'qh.sales_hdr_id', '=', 'ql.sales_hdr_id')
			->leftjoin('m_products_t as pr', 'ql.product_id', '=', 'pr.product_id')
			->leftjoin('m_uom_codes_t as uom', 'ql.uom_code_id', '=', 'uom.uom_code_id')
			->leftjoin('m_tax_group_t as tx', 'ql.tax_group_id', '=', 'tx.tax_group_id')
			->select('pr.product_id','pr.concatenated_product', 'uom.uom_code','tx.tax_group_name','ql.tax_group_id','ql.free_qty', 'ql.line_no','qh.sales_hdr_id','qh.pricelist_id','ql.qty','ql.unit_price','ql.tax_excemption','ql.discount_percentage','ql.discount_amount','ql.tax_amount','ql.line_total','ql.comments')
			->where('qh.sales_hdr_id',$id)
			->get(); 
			$totqty = 0;
			
			$subtotal =0;
            $sub_total = 0;
            $tax_amount=0;
            $X=0;
            $Y=0;
            $total=0;
            $tot_desc=0;
            $key=0;
            $qtytotal=0;
			
			
			foreach($linesdata as $ke=>$value)
			{
			
				if(!empty($value))
				{	
				    $bomprd1="";
                    $bomprdqty1="";
					$lines[$key]['line_no']=$value->line_no;
					$lines[$key]['product']=$value->concatenated_product;
					$lines[$key]['qty']=$value->qty;
					$tax=\DB::table("m_tax_group_t")->select('display_name','tax_group_name')->where('tax_group_id',$value->tax_group_id)->get();
					if(count($tax)>0){
                    $lines[$key]['gst_tax']=$tax[0]->display_name;
                    $lines[$key]['gst_per']=$tax[0]->tax_group_name;
                    $lines[$key]['gst_per_gst']=explode(" ",$tax[0]->tax_group_name);
                    $lines[$key]['gst_igst']=$lines[$key]['gst_per_gst'][0];
                    }else{
                    $lines[$key]['gst_tax']="";   
                    $lines[$key]['gst_per']="";   
                    $lines[$key]['gst_per_gst']="";   
                    $lines[$key]['gst_igst']="";  
                    }
					$totqty = $totqty + $value->qty;
					$lines[$key]['unit_price']=$value->unit_price;
					$lines[$key]['uom_code']=$value->uom_code;
					$lines[$key]['free_qty']=$value->free_qty;
					//$lines[$key]['amount']=$lines[$key]['qty']*$lines[$key]['unit_price'];
					$date = date('Y-m-d');
                    $mrp=\DB::select("SELECT * from i_pricelist_lines_t WHERE product_id ='$value->product_id' AND pricelist_hdr_id ='$value->pricelist_id' AND active = 'YES' AND start_date <= '$date' AND end_date >= '$date' ORDER BY pricelist_line_id DESC");
                    if(count($mrp) > 0){
                    $lines[$key]['mrp_price']=$mrp[0]->std_price;
                    }else{
                    $lines[$key]['mrp_price']='';
                    }
                    if($value->product_id !='0')
                    {
                    $arr=$this->getProduct($value->product_id);    
                    $bomprd="";
	                $bomprdqty="";    
                    if($arr['primary_uom_code']=="SET"){
	                $bom=\DB::select('select m_material_bom_hdr_t.assembly_product_id,m_material_bom_lines_t.*,m_products_t.concatenated_product from m_material_bom_hdr_t left join m_material_bom_lines_t on (m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id) left join m_products_t on(m_products_t.product_id=m_material_bom_lines_t.component_product_id) where m_material_bom_hdr_t
	                .assembly_product_id='.$value->product_id.' and m_products_t.product_group_id="1"');	
	
                    if(count($bom)>0){
		            foreach($bom as $bk=>$bv){
			        if($bv->component_product_id!=$value->product_id){
			        $bomprd.=$bv->concatenated_product.",";
			        $bomprdqty.=$bv->component_qty*$value->qty.",";
			        }
		            }
		            $bomprd1=rtrim($bomprd,",");
		            $bomprdqty1=rtrim($bomprdqty,",");
	                }
	                }
                    $lines[$key]['bomprd1']=explode(",",$bomprd1);
                    $lines[$key]['bomprdqty1']=explode(",",$bomprdqty1);    
                    $tax=\DB::table("m_tax_group_t")->select('display_name','tax_group_name')->where('tax_group_id',$value->tax_group_id)->get();
                    if(count($tax)>0){
                    $lines[$key]['gst_per']=$tax[0]->tax_group_name;
                    }else{
                    $lines[$key]['gst_per']="";    
                    }
                    $lines[$key]['hsn_code']=$arr['hsn_code'];
                    if($arr['product_group_id']!='12'){
                    $lines[$key]['batch_mfg']="( HSN:".$lines[$key]['hsn_code']." - ".$lines[$key]['gst_per']." )";
                    }else{
                    $lines[$key]['batch_mfg']=" HSN:".$lines[$key]['hsn_code'];
                    }
                    }
                    if($lines[$key]['gst_tax']=="28")
                    {
                    $X+=$value->tax_amount;
                    $tot_tax_amt_x=$X/2;
                    }
                    else
                    {
                    $Y+=$value->tax_amount;
                    $tot_tax_amt_y=$Y/2;
                    }
				}
				else
				{
					$lines[$key]['line_no']='';
					$lines[$key]['product']='';
					$lines[$key]['qty']='';
					$lines[$key]['uom_code']="";
					$lines[$key]['free_qty']="";
					$totqty = $totqty + 0;
					$lines[$key]['unit_price']='';
					//$lines[$key]['amount']='';
					$lines[$key]['mrp_price']='';
					$lines[$key]['batch_mfg']='';
					$lines[$key]['bomprd1']='';
		            $lines[$key]['bomprdqty']='';
				}
			        $lines[$key]['discount_amount']=$value->discount_amount;
                    $tot_desc+=$lines[$key]['discount_amount'];
                    $lines[$key]['discount_per']=$value->discount_percentage;
                    $lines[$key]['tax_amount']=$value->tax_amount;
                    $lines[$key]['tax_amount_1']=$lines[$key]['tax_amount']/2;
                    $lines[$key]['tax_amount_2']=$lines[$key]['tax_amount']/2;
                    $total+=$value->unit_price * $value->qty;
                    $total1=($value->unit_price * $value->qty) - (($value->qty *$value->unit_price) * ($value->discount_percentage/100));
                    $lines[$key]['amount']=$total1;
        
                    $dis_amt=($value->unit_price)-($value->unit_price *($value->discount_percentage/100));
    
                    $lines[$key]['taxable_amount']=$lines[$key]['amount']-$lines[$key]['discount_amount'];
                    $amount=$dis_amt*$value->qty;

                    $subtotal=$amount+$subtotal;
                    $sub_total=$sub_total+$amount;
                    $tot_tax_amt=$X+$Y;
                    $qtytotal +=$value->qty; 

                    $key++;	
				
			}
			
			if(!empty($tot_tax_amt_y))
            {
            $this->data['tot_tax_amt_y']=$tot_tax_amt_y;
            }
            else
            {
            $this->data['tot_tax_amt_y']='';
            }
        
            $this->data['gst_amt_x']=$X;
            $this->data['gst_amt_y']=$Y;
            if(!empty($tot_tax_amt_x)){
            $this->data['tot_tax_amt_x']=$tot_tax_amt_x;
            }
            else
            {
            $this->data['tot_tax_amt_x']='';
            }
        	$this->data['tot_amt_aftr_tax']=$lines[$ke]['amount']+$tot_tax_amt-$tot_desc;
            $this->data['total']=$lines[$ke]['amount'];
            $this->data['tot_desc']=$tot_desc;
            $this->data['tot_tax_amt']=$tot_tax_amt;		
			
			$this->data['linedata']=$lines;
			$this->data['totqty']=$totqty;
			$this->data['sub_total']  =$subtotal;
            $this->data['qtytotal']  =$qtytotal;
            $line_total=$subtotal+$tot_tax_amt;
            $this->data['line_total']  =$line_total;
			
			$gstdata=\DB::select("select * from s_salesorder_lines_t where sales_hdr_id='".$id."' and tax_group_id!='0' group by hsn_code");
            // dd($gstdata);
            $this->trd = $headerdata[0]->trade_discount_pre;
            //$this->trd ='';
            
            $gstvalue=array();
            $sgst=0;
            $cgst=0;
            $gsttotal=0;
            $grand_total=0;
        // dd($gstdata);
            foreach($gstdata  as $gst_key=>$gst_value)
            {   

            $arr_sgcgst=array('sgst','cgst');
            $arr=$this->getProduct($gst_value->product_id);
    
            $tax=\DB::table("m_tax_group_t")->select('display_name','tax_group_name')->where('tax_group_id',$gst_value->tax_group_id)->get();
    
            if($tax->isNotEmpty()){
                $display_name=$tax[0]->display_name;
                $gstvalue[$gst_key]['tax_group_name']=$tax[0]->tax_group_name;/* important */
            }else{
                $gstvalue[$gst_key]['tax_group_name']='';    
            }
        
            $gst=$this->getGst($gst_value->tax_group_id,$gst_value->hsn_code,$id);
    
            $gstvalue[$gst_key]['gst']=$display_name;/* important */
            
    
            $gstvalue[$gst_key]['sgcgst']=$display_name/2;
    
            $gstvalue[$gst_key]['hsn']=$arr['hsn_code'];
    
            $gstvalue[$gst_key]['amount']=$gst['amount'];
            $gstvalue[$gst_key]['gst_id']=$gst_value->tax_group_id;
            $gstvalue[$gst_key]['gst_val']=$gst['amount']*($display_name/100); /* important */

            $gstvalue[$gst_key]['sgst_val']=$gst['amount']*($display_name/100)/2;
        
            $gstvalue[$gst_key]['cgst_val']=$gst['amount']*($display_name/100)/2;
    
            $this->data['sgcgstt']=$arr_sgcgst;
        
            $gsttotal=$gstvalue[$gst_key]['gst_val']+$gsttotal;
        
            }
            $ka=$gsttotal/2;
        
            $grand_total=$gsttotal+$sub_total;
            // dd($gsttotal);
            //Maruthu Purpose to GST calculation End
            $this->data['gst']          = $gstvalue;
            $this->data['gsttotal']     = $gsttotal;
            $this->data['value']        = $lines;
            $this->data['sub_total']    = $subtotal;
            $this->data['subtotal']     = $sub_total;
            $this->data['id']           = $id;
        			
			
			//dd($this->data['line']);
			
			$this->data['print']="PRINT";
	   		$this->data['print_val'] = '1';
			
			  $terms_condition=\DB::table('a_rpt_displayelements_hdr_t')->leftjoin('a_rpt_displayelements_lines_t', 'a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id', '=', 'a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id')->select('a_rpt_displayelements_lines_t.element_content')->where('a_rpt_displayelements_hdr_t.report_source',120)->where('a_rpt_displayelements_lines_t.element_name','TERMS&CONDITIONS')->get();
			
	 	if(count($terms_condition)>0){
			$this->data['terms_condition']=$terms_condition;
		 }
		 else{
			$this->data['terms_condition']=[];
	 	}
		   if(isset($_GET['mail']))
            {
           /*     if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
        Config::set('mail.username', \Session::get('user_email'));
        Config::set('mail.password', \Session::get('user_password'));
    }   */
// dd(\Session::get('user_password'));
                $this->data['print']="PRINTS";



                \Mail::send('soorder.soorder_print',$this->data, function($message)
                {
                  if(!empty($_GET['cc'])){

                  $cc=explode(',',$_GET['cc']);
                  $message->cc($cc);
              }else{
                 $cc=array();
              }

                    $msg=$_GET['msg'];
                    // dd($_GET['mail']);
                      $message->to(explode(",",$_GET['mail']));
                      // $message->to("i5techerp@gmail.com");
                    $message->subject("Salesorder". $this->data['sales_order_no']);

                     $message->setBody($msg);
                   $return=DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$this->data['sales_hdr_id'])->get();
            $message->attach('Uploads/salesorderupload/SO_'.stripslashes($this->data['sales_order_no']).'.pdf');
            if($return[0]->attachfile_name!=''){
                $file_a=json_decode($return[0]->attachfile_name);

                foreach($file_a as $k1=>$v1){

                 $message->attach('Uploads/salesorderupload/SO'.$this->data['sales_hdr_id'].'/'.$v1);
                }
            }
                    

                });
                            return 1;


            }

			 if(isset($_GET['mails'])){

          $this->data['print']="PRINTS";
           // dd($this->data);
             return view('soorder.soorder_print', $this->data);
         }
	return view('soorder.proformastdprint',$this->data);
	
	}
	
	
	/*export proforma print function*/
    public function getproformaexpprint($id=null)
	{
	$headerdata = \DB::table('s_salesorder_hdr_t as qh')
		->leftjoin('m_projects_t as p', 'qh.project_id', '=', 'p.project_id')
		->leftjoin('hr_employee_t as s', 'qh.salesperson_id', '=', 's.employee_id')
		->leftjoin('m_customers_t as c', 'qh.ship_to_customer_id', '=', 'c.customer_id')
		->leftjoin('i_pricelist_hdr_t as pl','qh.pricelist_id','=','pl.pricelist_hdr_id')
		->leftjoin('s_schemes_lines_t','s_schemes_lines_t.schemes_hdr_id','=','qh.cash_discount')
		->select('qh.sales_hdr_id','p.project_name','pl.pricelist_hdr_id','pl.pricelist_name','qh.ship_to_customer_id','qh.company_id','qh.customer_po_number','qh.ar_delivery_terms_id','qh.remarks','qh.trade_discount','qh.trade_discount_pre', 's.first_name','qh.reference_number','qh.freight_carrier_id','qh.ar_payment_term_id','c.customer_name','qh.bill_to_address_id','qh.ship_to_address_id','qh.sales_order_no','qh.so_ref_no','qh.sales_order_date','qh.order_type_id','qh.order_status_id','qh.customer_po_number','qh.transport_mode','qh.port_of_discharge','qh.remarks','qh.order_tax','qh.order_total','qh.location_id','qh.company_id','qh.employee_id','s_schemes_lines_t.schemes_type_value')
		->where('qh.sales_hdr_id',$id)
        ->get();
	    
       // dd($headerdata);
		/********************************* header ********************************/
		//$this->data['despatch_through'] = $this->getFreight($row[0]->freight_carrier_id);
		if(!empty($headerdata))
		{
            if($headerdata[0]->customer_name!=null){
			$this->data['customer_name']=$headerdata[0]->customer_name;
			}else{
			$emp=\DB::select("select concat(first_name,COALESCE(last_name,'')) as empname from hr_employee_t where employee_id=".$headerdata[0]->employee_id);
			$this->data['customer_name']=$emp[0]->empname;	
			}
			$this->data['sales_hdr_id']=$headerdata[0]->sales_hdr_id;
			$this->data['sales_order_no']=$headerdata[0]->sales_order_no;
			$this->data['sales_order_date']=$headerdata[0]->sales_order_date;
			$this->data['so_ref_no']=$headerdata[0]->so_ref_no;
			$this->data['customer_po_number']=$headerdata[0]->customer_po_number;
			$this->data['transport_mode']=$headerdata[0]->transport_mode;
			$this->data['port_of_discharge']=$headerdata[0]->port_of_discharge;
			$this->data['order_type_id']=$headerdata[0]->order_type_id;	
		    $this->data['order_status_id']=$headerdata[0]->order_status_id;
			$this->data['reference_number']=$headerdata[0]->reference_number;				
			//$this->data['customer_po_number']=$headerdata[0]->customer_po_number;	
			$this->data['schemes_type_value']=$headerdata[0]->schemes_type_value;
			$this->data['trade_discount']=$headerdata[0]->trade_discount;
            $this->data['trade_discount_pre']=$headerdata[0]->trade_discount_pre;
			$this->data['remarks']=$headerdata[0]->remarks;				
			$this->data['despatch_through'] = $this->getFreight($headerdata[0]->freight_carrier_id);
			$this->data['payment_term'] = $this->getPaymentterm($headerdata[0]->ar_payment_term_id);
			//$deliveryterm =Deliveryterms::where('delivery_terms_id',$headerdata[0]->ar_delivery_terms_id)->pluck('delivery_term_name')->first(); 
			//$this->data['deliveryterm'] = $deliveryterm;
			$deliveryterm =Deliveryterms::where('delivery_terms_id',$headerdata[0]->ar_delivery_terms_id)->select('delivery_term_name','remarks')->get(); 
            if(count($deliveryterm)>0)
            {
            $this->data['deliveryterm'] = $deliveryterm[0]->delivery_term_name;
            $this->data['del_remarks'] = $deliveryterm[0]->remarks;
            }else{
            $this->data['deliveryterm'] = '';
            $this->data['del_remarks'] = '';  
            }
			//dd($this->data);
			$comp = \DB::table('m_company_t')->where('company_id',$headerdata[0]->company_id)->get();
			if($comp->isNotEmpty())
			{
			$this->data['cmp_gst_no'] 				  = $comp[0]->gst_no;	
			$this->data['pan_no'] 				  = $comp[0]->pan_no;	
			$this->data['email_id'] 			  = $comp[0]->email_id;	
			$this->data['cin_no'] 				  = $comp[0]->cin_no;	
			$this->data['excise_registration_no'] = $comp[0]->excise_registration_no;	
			$this->data['tax_reg_no'] 			  = $comp[0]->tax_reg_no;	
			$this->data['website_address'] 			  = $comp[0]->website_address;	
			}
			else
			{
			$this->data['cmp_gst_no'] 				= "";	
			$this->data['pan_no'] 				= "";	
			$this->data['email_id'] 				= "";	
			$this->data['cin_no'] 				= "";	
			$this->data['excise_registration_no'] = "";	
			$this->data['tax_reg_no'] 			= "";	
			$this->data['website_address'	] 			= "";	
			}
				
		}
		else
		{
			$this->data['customer_name']	  ='';
			$this->data['sales_order_no']     ='';
			$this->data['sales_order_date']   ='';
			$this->data['order_status_id']    ='';
			$this->data['so_ref_no']		  ='';
			$this->data['customer_po_number'] ='';
			$this->data['transport_mode'] ='';
			$this->data['port_of_discharge'] ='';
			$this->data['despatch_through']   ='';
			$this->data['payment_term']       ='';
			$this->data['reference_number']   ='';
			$this->data['remarks']   		  ='';
			$this->data['deliveryterm']       ='';
			$this->data['customer_po_number'] ='';
			$this->data['gst_no'] 				= "";	
			$this->data['pan_no'] 				= "";	
			$this->data['email_id'] 				= "";	
			$this->data['cin_no'] 				= "";	
			$this->data['excise_registration_no'] = "";	
			$this->data['tax_reg_no'] 			= "";
			$this->data['schemes_type_value']="";
			$this->data['trade_discount']="";
            $this->data['trade_discount_pre']="";
		
		}
		
		if(isset($id)){
        $currency_type=DB::table('s_salesorder_hdr_t')->leftjoin('f_account_currency_t','f_account_currency_t.account_currency_id','=','s_salesorder_hdr_t.invoice_currency')
        ->select('f_account_currency_t.currency_code')->where('sales_hdr_id',$id)->get(); 
        $this->data['invoice_currency']=$currency_type[0]->currency_code;
        }
        
        $acc_details=DB::table('f_bank_account_hdr_t')->leftjoin('f_bank_account_lines_t','f_bank_account_hdr_t.bank_account_hdr_id','=','f_bank_account_lines_t.bank_account_hdr_id')
        ->select('f_bank_account_hdr_t.bank_name','f_bank_account_lines_t.branch_name','f_bank_account_lines_t.ifsc_code','f_bank_account_lines_t.name_in_account','f_bank_account_lines_t.account_number')->where('f_bank_account_hdr_t.bank_account_hdr_id',10)->get(); 
        $this->data['name_in_acc']=$acc_details[0]->name_in_account;
        $this->data['acc_no']=$acc_details[0]->account_number;
        $this->data['ifsc_code']=$acc_details[0]->ifsc_code;
        $this->data['bank_name']=$acc_details[0]->bank_name;
        $this->data['bank_branch']=$acc_details[0]->branch_name.',';
		
		/******************************* End ************************************/
		
		/*********************** Bill To Address ********************************/

		$customer_bill=\DB::table('m_customer_sites_t')->where('customer_site_id',$headerdata[0]->ship_to_address_id)->get();
		$emp_address=\DB::table('hr_emp_contact')->where('employee_id',$headerdata[0]->employee_id)->get();
	//	dd($emp_address);
		if($headerdata[0]->ship_to_customer_id!=0){
		if(count($customer_bill) > 0)
		{
			if($customer_bill[0]->site_type=="SHIP_TO")
			{
			 $customer_name_s=$this->data['customer_name_s']=$customer_bill[0]->customer_site_name;
			 $address=$this->data['address']=$customer_bill[0]->address;
			 $this->data['city']=$this->getCity($customer_bill[0]->city);
				if($this->data['city']!=0){
			 $city=$this->data['city_b']=$this->data['city'][0]->city_name;
				}else{ $city=$this->data['city_b']="";}
			 $this->data['state']=$this->getState($customer_bill[0]->state);
				if($this->data['state']!=0){
			 $state=$this->data['state_name_ship']=$this->data['state'][0]->state_name;
				}else{ $state=$this->data['state_name_ship']="";}
			 $this->data['country']=$this->getCountry($customer_bill[0]->country);
			 $country=$this->data['country_b']=$this->data['country'][0]->country_name;
			 $pincode=$this->data['pincode']=$customer_bill[0]->pincode;
			 $gst_no=$this->data['ship_gst_no']=$customer_bill[0]->gst_no;
			 $contact=$this->data['contact_number']=$customer_bill[0]->contact_number; 
			 $mail_s=$this->data['mail_s']=$customer_bill[0]->contact_mail;
			$this->data['ship_to_address_1']=$address.",".$city.",".$state.",".$country.",".$pincode;
			if($customer_bill[0]->location_id!=0){
			 $dest=\DB::SELECT('select * from m_location_t where location_id='.$customer_bill[0]->location_id.'');
			 $this->data['destination'] = $dest[0]->location_name;
			
			}
				else{
					 $this->data['destination'] = '';
				}

			}
			else
			{
			 $this->data['address']='';
			 $this->data['city']='';
			 $this->data['state_name_ship']='';
			 $this->data['country']='';
			 $this->data['pincode']='';
			 $this->data['ship_gst_no']='';
			 $this->data['contact_number']='';
			 $this->data['ship_to_address_1']='';
			 $this->data['destination'] = '';
			 $this->data['customer_name_s']='';
			 $this->data['mail_s']='';

			}
		}}else if(count($emp_address)>0){
					 if($emp_address[0]->current_flat_no!=''){
                	$this->data['current_flat_no']=$emp_address[0]->current_flat_no.",";
                }else{
                	$this->data['current_flat_no']="";
                }
                if($emp_address[0]->current_street!=''){
                	$this->data['current_street']=$emp_address[0]->current_street.",";
                }else{
                	$this->data['current_street']="";
                }
				 $this->data['address']=$this->data['current_flat_no']."".$this->data['current_street']."".$emp_address[0]->current_street_address;
                $this->data['city']=$this->getCity($emp_address[0]->current_city);
				$city=$this->data['city'][0]->city_name;
                 $this->data['destination'] = $emp_address[0]->current_locality;
                $states=$this->getState($emp_address[0]->current_state);
                if($states !=0)
                {
                $this->data['state_id_ship']=$states[0]->state_code_no;
                $this->data['state_name_ship']=$states[0]->state_name;
                $this->data['state_code_bill']=$states[0]->state_code;
                }
                else
                {
                $this->data['state_id_ship']=$states;
                $this->data['state_name_ship']=$states;
                $this->data['state_code_bill']=$states; 
                }
                // if($emp_address[0]->current_flat_no!=''){
                // 	$this->data['current_flat_no']=$states[0]->current_flat_no.",";
                // }else{
                // 	$this->data['current_flat_no']="";
                // }
                // if($emp_address[0]->current_street!=''){
                // 	$this->data['current_street']=$states[0]->current_street.",";
                // }else{
                // 	$this->data['current_street']="";
                // }
                $this->data['country']=$this->getCountry($emp_address[0]->current_country);
				$country=$this->data['country'][0]->country_name;
                $this->data['pincode']=$emp_address[0]->current_postal_code;
				$empmobile=\DB::select('select * from hr_employee_t where employee_id='.$headerdata[0]->employee_id);
                $this->data['contact_number']=$empmobile[0]->work_telephone_number;
                 $this->data['contact_person']=$empmobile[0]->first_name;
                 $this->data['ship_gst_no']='';
              
                $this->data['ship_to_address_1']=$this->data['address'].",".$city.",".$this->data['state_s'].",".$country.",".$this->data['pincode']; 

			}
		else
		{
			 $this->data['ship_to_address_1']='';
			 $this->data['address']='';
			 $this->data['city']='';
			 $this->data['state_name_ship']='';
			 $this->data['country']='';
			 $this->data['pincode']='';
			 $this->data['ship_gst_noship_gst_no']='';
			 $this->data['contact_number']='';
			 $this->data['ship_to_address_1']='';
			 $this->data['destination'] = '';
			 $this->data['state_s'] = '';
			 $this->data['customer_name_s']='';
			 $this->data['mail_s']='';
		}
		
		$customer_bill_con=\DB::table('m_customer_sites_t')->where('customer_site_id',$headerdata[0]->bill_to_address_id)->get();
		if($headerdata[0]->ship_to_customer_id!=0){
		if(count($customer_bill_con) > 0)
		{
			if($customer_bill_con[0]->site_type=="BILL_TO")
			{
			 $customer_name_con=$this->data['customer_name_con']=$customer_bill_con[0]->customer_site_name;   
			 $address_con=$this->data['address_con']=$customer_bill_con[0]->address;
			 $this->data['city_con']=$this->getCity($customer_bill_con[0]->city);
				if($this->data['city_con']!=0){
			 $city_con=$this->data['city_b_con']=$this->data['city_con'][0]->city_name;
				}else{ $city_con=$this->data['city_b_con']="";}
			 $this->data['state_con']=$this->getState($customer_bill_con[0]->state);
				if($this->data['state_con']!=0){
			 $state_con=$this->data['state_name_ship_con']=$this->data['state_con'][0]->state_name;
				}else{ $state_con=$this->data['state_name_ship_con']="";}
			 $this->data['country_con']=$this->getCountry($customer_bill_con[0]->country);
			 $country_con=$this->data['country_b_con']=$this->data['country_con'][0]->country_name;
			 $pincode_con=$this->data['pincode_con']=$customer_bill_con[0]->pincode;
			 $gst_no_con=$this->data['ship_gst_no_con']=$customer_bill_con[0]->gst_no;
			 $contact_con=$this->data['contact_number_con']=$customer_bill_con[0]->contact_number;
			 $mail_con=$this->data['mail_con']=$customer_bill_con[0]->contact_mail;
			$this->data['ship_to_address_1_con']=$address_con.",".$city_con.",".$state_con.",".$country_con.",".$pincode_con;
			if($customer_bill_con[0]->location_id!=0){
			 $dest_con=\DB::SELECT('select * from m_location_t where location_id='.$customer_bill_con[0]->location_id.'');
			 $this->data['destination_con'] = $dest_con[0]->location_name;
			
			}
				else{
					 $this->data['destination_con'] = '';
				}

			}
			else
			{
			 $this->data['address_con']='';
			 $this->data['city_con']='';
			 $this->data['state_name_ship_con']='';
			 $this->data['country_con']='';
			 $this->data['pincode_con']='';
			 $this->data['ship_gst_no_con']='';
			 $this->data['contact_number_con']='';
			 $this->data['mail_con']='';
			 $this->data['ship_to_address_1_con']='';
			 $this->data['destination_con'] = '';

			}
		}}
		else
		{
			 $this->data['ship_to_address_1_con']='';
			 $this->data['address_con']='';
			 $this->data['city_con']='';
			 $this->data['state_name_ship_con']='';
			 $this->data['country_con']='';
			 $this->data['pincode_con']='';
			 $this->data['ship_gst_no_con']='';
			 $this->data['contact_number_con']='';
			 $this->data['mail_con']='';
			 $this->data['ship_to_address_1_con']='';
			 $this->data['destination_con'] = '';
			 $this->data['state_s_con'] = '';
		}
		
		
		/**************************** End ***************************************/
			
			
		/********************* location based company address ***********************/
			
		$address=$this->getLocationwiseaddress($headerdata[0]->location_id);
		//dd($address);
		if(!empty($address))
		{
			$location_name=$address[0]->location_name;
			$this->data['location_name_l']= $location_name;
			$address1=$address[0]->address;
			$this->data['address_l']= $address1;
			$street =$address[0]->street_name;
			$this->data['street_name_l']= $street;
			$location=$address[0]->location_name;
			$this->data['location_l']= $location;
			$area=$address[0]->area;
			$this->data['area_l']= $area;
			$comppincode=$address[0]->pincode;
			$this->data['comppincode']= $comppincode;
			
			$this->data['company_gst_no_l']= $address[0]->gst_no;
			$city_l=$this->data['city_l']=$this->getCity($address[0]->city_id);
			$city=$city_l[0]->city_name;
			$state_l=$this->data['state_l']=$this->getState($address[0]->state_id);
			$state=$state_l[0]->state_name;
			$this->data['state_code']=$state_l[0]->state_code_no;
			$this->data['state_name']=$state_l[0]->state_name;
			$country_l=$this->data['country_l']=$this->getCountry($address[0]->country_id);
			$country=$country_l[0]->country_name;
			
		}
			$this->data['company_address'] = $address1.",".$city.",".$state.",".$country."-".$comppincode;
			
			$acc_details=DB::table('f_bank_account_hdr_t')->leftjoin('f_bank_account_lines_t','f_bank_account_hdr_t.bank_account_hdr_id','=','f_bank_account_lines_t.bank_account_hdr_id')
            ->select('f_bank_account_hdr_t.bank_name','f_bank_account_lines_t.branch_name','f_bank_account_lines_t.ifsc_code','f_bank_account_lines_t.name_in_account','f_bank_account_lines_t.account_number')->where('f_bank_account_hdr_t.bank_account_hdr_id',10)->get(); 
            $this->data['name_in_acc']=$acc_details[0]->name_in_account;
            $this->data['acc_no']=$acc_details[0]->account_number;
            $this->data['ifsc_code']=$acc_details[0]->ifsc_code;
            $this->data['bank_name']=$acc_details[0]->bank_name;
            $this->data['bank_branch']=$acc_details[0]->branch_name.','.$city."-".$comppincode;
            $this->data['bank_state']=$state.",".$country;
            
            $this->data['receipt_pre_carriage']=$city;
            $this->data['port_of_loading']=$city.' / '.$country;
    		$this->data['country_of_origin']=$country;
    		//$this->data['country_of_finaldest']=$country;
    		
		/******************************** end *************************************/
		
		
		/************************* location based company *************************/
		
		$company=$this->getCompany($headerdata[0]->company_id);
		//dd($company);
		if(!empty($company))
		{
			$company_name=$company[0]->company_name;
			$this->data['company_name']= $company_name;
			$this->data['comp_contact_no']="+91-44-".$company[0]->contact_no;
			
		}
		
		/******************************** end *************************************/
			$this->data['company_logo']=\Session::get('companylogo');
	    /******************************* Lines ************************************/
	
			$linesdata  = \DB::table('s_salesorder_hdr_t as qh')
			->leftjoin('s_salesorder_lines_t as ql', 'qh.sales_hdr_id', '=', 'ql.sales_hdr_id')
			->leftjoin('m_products_t as pr', 'ql.product_id', '=', 'pr.product_id')
			->leftjoin('m_uom_codes_t as uom', 'ql.uom_code_id', '=', 'uom.uom_code_id')
			->leftjoin('m_tax_group_t as tx', 'ql.tax_group_id', '=', 'tx.tax_group_id')
			->select('pr.product_id','pr.concatenated_product', 'uom.uom_code','tx.tax_group_name','ql.tax_group_id','ql.free_qty', 'ql.line_no','qh.sales_hdr_id','ql.qty','ql.unit_price','ql.tax_excemption','ql.discount_percentage','ql.discount_amount','ql.tax_amount','ql.line_total','ql.comments')
			->where('qh.sales_hdr_id',$id)
			->get(); 
			$totqty = 0;
			
			$subtotal =0;
            $sub_total = 0;
            $tax_amount=0;
            $X=0;
            $Y=0;
            $total=0;
            $tot_desc=0;
            $key=0;
            $qtytotal=0;
			
			
			foreach($linesdata as $ke=>$value)
			{
			
				if(!empty($value))
				{	
				    $bomprd1="";
                    $bomprdqty1="";
					$lines[$key]['line_no']=$value->line_no;
					$lines[$key]['product']=$value->concatenated_product;
					$lines[$key]['qty']=$value->qty;
					$tax=\DB::table("m_tax_group_t")->select('display_name','tax_group_name')->where('tax_group_id',$value->tax_group_id)->get();
					if(count($tax)>0){
                    $lines[$key]['gst_tax']=$tax[0]->display_name;
                    $lines[$key]['gst_per']=$tax[0]->tax_group_name;
                    $lines[$key]['gst_per_gst']=explode(" ",$tax[0]->tax_group_name);
                    $lines[$key]['gst_igst']=$lines[$key]['gst_per_gst'][0];
                    }else{
                    $lines[$key]['gst_tax']="";   
                    $lines[$key]['gst_per']="";   
                    $lines[$key]['gst_per_gst']="";   
                    $lines[$key]['gst_igst']="";  
                    }
					$totqty = $totqty + $value->qty;
					$lines[$key]['unit_price']=$value->unit_price;
					$lines[$key]['uom_code']=$value->uom_code;
					$lines[$key]['free_qty']=$value->free_qty;
					//$lines[$key]['amount']=$lines[$key]['qty']*$lines[$key]['unit_price'];
					$date = date('Y-m-d');
                    $mrp=\DB::select("SELECT * from i_pricelist_lines_t WHERE product_id ='$value->product_id' AND active = 'YES' AND start_date <= '$date' AND end_date >= '$date' ORDER BY pricelist_line_id DESC");
                    if(count($mrp) > 0){
                    $lines[$key]['mrp_price']=$mrp[0]->std_price;
                    }else{
                    $lines[$key]['mrp_price']='';
                    }
                    if($value->product_id !='0')
                    {
                    $arr=$this->getProduct($value->product_id);    
                    $bomprd="";
	                $bomprdqty="";    
                    if($arr['primary_uom_code']=="SET"){
	                $bom=\DB::select('select m_material_bom_hdr_t.assembly_product_id,m_material_bom_lines_t.*,m_products_t.concatenated_product from m_material_bom_hdr_t left join m_material_bom_lines_t on (m_material_bom_lines_t.material_bom_hdr_id=m_material_bom_hdr_t.material_bom_hdr_id) left join m_products_t on(m_products_t.product_id=m_material_bom_lines_t.component_product_id) where m_material_bom_hdr_t
	                .assembly_product_id='.$value->product_id.' and m_products_t.product_group_id="1"');	
	
                    if(count($bom)>0){
		            foreach($bom as $bk=>$bv){
			        if($bv->component_product_id!=$value->product_id){
			        $bomprd.=$bv->concatenated_product.",";
			        $bomprdqty.=$bv->component_qty*$value->qty.",";
			        }
		            }
		            $bomprd1=rtrim($bomprd,",");
		            $bomprdqty1=rtrim($bomprdqty,",");
	                }
	                }
                    $lines[$key]['bomprd1']=explode(",",$bomprd1);
                    $lines[$key]['bomprdqty1']=explode(",",$bomprdqty1);    
                    $tax=\DB::table("m_tax_group_t")->select('display_name','tax_group_name')->where('tax_group_id',$value->tax_group_id)->get();
                    if(count($tax)>0){
                    $lines[$key]['gst_per']=$tax[0]->tax_group_name;
                    }else{
                    $lines[$key]['gst_per']="";    
                    }
                    $lines[$key]['hsn_code']=$arr['hsn_code'];
                    if($arr['product_group_id']!='12'){
                    $lines[$key]['batch_mfg']="( H.S Code:".$lines[$key]['hsn_code']." )";
                    }else{
                    $lines[$key]['batch_mfg']=" H.S Code:".$lines[$key]['hsn_code'];
                    }
                    }
                    if($lines[$key]['gst_tax']=="28")
                    {
                    $X+=$value->tax_amount;
                    $tot_tax_amt_x=$X/2;
                    }
                    else
                    {
                    $Y+=$value->tax_amount;
                    $tot_tax_amt_y=$Y/2;
                    }
				}
				else
				{
					$lines[$key]['line_no']='';
					$lines[$key]['product']='';
					$lines[$key]['qty']='';
					$lines[$key]['uom_code']="";
					$lines[$key]['free_qty']="";
					$totqty = $totqty + 0;
					$lines[$key]['unit_price']='';
					//$lines[$key]['amount']='';
					$lines[$key]['mrp_price']='';
					$lines[$key]['batch_mfg']='';
					$lines[$key]['bomprd1']='';
		            $lines[$key]['bomprdqty']='';
				}
			        $lines[$key]['discount_amount']=$value->discount_amount;
                    $tot_desc+=$lines[$key]['discount_amount'];
                    $lines[$key]['discount_per']=$value->discount_percentage;
                    $lines[$key]['tax_amount']=$value->tax_amount;
                    $lines[$key]['tax_amount_1']=$lines[$key]['tax_amount']/2;
                    $lines[$key]['tax_amount_2']=$lines[$key]['tax_amount']/2;
                    $total+=$value->unit_price * $value->qty;
                    $total1=($value->unit_price * $value->qty) - (($value->qty *$value->unit_price) * ($value->discount_percentage/100));
                    $lines[$key]['amount']=$total1;
        
                    $dis_amt=($value->unit_price)-($value->unit_price *($value->discount_percentage/100));
    
                    $lines[$key]['taxable_amount']=$lines[$key]['amount']-$lines[$key]['discount_amount'];
                    $amount=$dis_amt*$value->qty;

                    $subtotal=$amount+$subtotal;
                    $sub_total=$sub_total+$amount;
                    $tot_tax_amt=$X+$Y;
                    $qtytotal +=$value->qty; 

                    $key++;	
				
			}
			
			if(!empty($tot_tax_amt_y))
            {
            $this->data['tot_tax_amt_y']=$tot_tax_amt_y;
            }
            else
            {
            $this->data['tot_tax_amt_y']='';
            }
        
            $this->data['gst_amt_x']=$X;
            $this->data['gst_amt_y']=$Y;
            if(!empty($tot_tax_amt_x)){
            $this->data['tot_tax_amt_x']=$tot_tax_amt_x;
            }
            else
            {
            $this->data['tot_tax_amt_x']='';
            }
        	$this->data['tot_amt_aftr_tax']=$lines[$ke]['amount']+$tot_tax_amt-$tot_desc;
            $this->data['total']=$lines[$ke]['amount'];
            $this->data['tot_desc']=$tot_desc;
            $this->data['tot_tax_amt']=$tot_tax_amt;		
			
			$this->data['linedata']=$lines;
			$this->data['totqty']=$totqty;
			$this->data['sub_total']  =$subtotal;
            $this->data['qtytotal']  =$qtytotal;
            $line_total=$subtotal+$tot_tax_amt;
            $this->data['line_total']  =$line_total;
			
			$gstdata=\DB::select("select * from s_salesorder_lines_t where sales_hdr_id='".$id."' and tax_group_id!='0' group by hsn_code");
            // dd($gstdata);
            $this->trd = $headerdata[0]->trade_discount_pre;
            //$this->trd ='';
            
            $gstvalue=array();
            $sgst=0;
            $cgst=0;
            $gsttotal=0;
            $grand_total=0;
        // dd($gstdata);
            foreach($gstdata  as $gst_key=>$gst_value)
            {   

            $arr_sgcgst=array('sgst','cgst');
            $arr=$this->getProduct($gst_value->product_id);
    
            $tax=\DB::table("m_tax_group_t")->select('display_name','tax_group_name')->where('tax_group_id',$gst_value->tax_group_id)->get();
    
            if($tax->isNotEmpty()){
                $display_name=$tax[0]->display_name;
                $gstvalue[$gst_key]['tax_group_name']=$tax[0]->tax_group_name;/* important */
            }else{
                $gstvalue[$gst_key]['tax_group_name']='';    
            }
        
            $gst=$this->getGst($gst_value->tax_group_id,$gst_value->hsn_code,$id);
    
            $gstvalue[$gst_key]['gst']=$display_name;/* important */
            
    
            $gstvalue[$gst_key]['sgcgst']=$display_name/2;
    
            $gstvalue[$gst_key]['hsn']=$arr['hsn_code'];
    
            $gstvalue[$gst_key]['amount']=$gst['amount'];
            $gstvalue[$gst_key]['gst_id']=$gst_value->tax_group_id;
            $gstvalue[$gst_key]['gst_val']=$gst['amount']*($display_name/100); /* important */

            $gstvalue[$gst_key]['sgst_val']=$gst['amount']*($display_name/100)/2;
        
            $gstvalue[$gst_key]['cgst_val']=$gst['amount']*($display_name/100)/2;
    
            $this->data['sgcgstt']=$arr_sgcgst;
        
            $gsttotal=$gstvalue[$gst_key]['gst_val']+$gsttotal;
        
            }
            $ka=$gsttotal/2;
        
            $grand_total=$gsttotal+$sub_total;
            // dd($gsttotal);
            //Maruthu Purpose to GST calculation End
            $this->data['gst']          = $gstvalue;
            $this->data['gsttotal']     = $gsttotal;
            $this->data['value']        = $lines;
            $this->data['sub_total']    = $subtotal;
            $this->data['subtotal']     = $sub_total;
            $this->data['id']           = $id;
        			
			
			//dd($this->data['line']);
			
			$this->data['print']="PRINT";
	   		$this->data['print_val'] = '1';
			
			  $terms_condition=\DB::table('a_rpt_displayelements_hdr_t')->leftjoin('a_rpt_displayelements_lines_t', 'a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id', '=', 'a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id')->select('a_rpt_displayelements_lines_t.element_content')->where('a_rpt_displayelements_hdr_t.report_source',120)->where('a_rpt_displayelements_lines_t.element_name','TERMS&CONDITIONS')->get();
			
	 	if(count($terms_condition)>0){
			$this->data['terms_condition']=$terms_condition;
		 }
		 else{
			$this->data['terms_condition']=[];
	 	}
		   if(isset($_GET['mail']))
            {
           /*     if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
        Config::set('mail.username', \Session::get('user_email'));
        Config::set('mail.password', \Session::get('user_password'));
    }   */
// dd(\Session::get('user_password'));
                $this->data['print']="PRINTS";



                \Mail::send('soorder.soorder_print',$this->data, function($message)
                {
                  if(!empty($_GET['cc'])){

                  $cc=explode(',',$_GET['cc']);
                  $message->cc($cc);
              }else{
                 $cc=array();
              }

                    $msg=$_GET['msg'];
                    // dd($_GET['mail']);
                      $message->to(explode(",",$_GET['mail']));
                      // $message->to("i5techerp@gmail.com");
                    $message->subject("Salesorder". $this->data['sales_order_no']);

                     $message->setBody($msg);
                   $return=DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$this->data['sales_hdr_id'])->get();
            $message->attach('Uploads/salesorderupload/SO_'.stripslashes($this->data['sales_order_no']).'.pdf');
            if($return[0]->attachfile_name!=''){
                $file_a=json_decode($return[0]->attachfile_name);

                foreach($file_a as $k1=>$v1){

                 $message->attach('Uploads/salesorderupload/SO'.$this->data['sales_hdr_id'].'/'.$v1);
                }
            }
                    

                });
                            return 1;


            }

			 if(isset($_GET['mails'])){

          $this->data['print']="PRINTS";
           // dd($this->data);
             return view('soorder.soorder_print', $this->data);
         }
	return view('soorder.proformaexpprint',$this->data);
	
	}
	

	public function salesorderupload(Request $request)
    {
	        $sales_id=$_POST['salesid'];
             
        if($request->hasfile('choosefile'))
        {
            foreach($request->file('choosefile') as $file)
            {
                $name=$file->getClientOriginalName();

                $file->move(public_path().'/uploads/salesorderupload/SO'.$sales_id.'/', $name);  
                $data[] = $name;  
            }
        }
    
        $datas=\DB::select("select attachfile_name from s_salesorder_hdr_t where sales_hdr_id='$sales_id'");
 

		if($datas[0]->attachfile_name!=""){          
	          $datas=json_decode($datas[0]->attachfile_name);
	          $result=array_diff($datas,$_POST['file']);
			foreach($result as $k=>$v){
			     @unlink(public_path().'/uploads/salesorderupload/SO'.$sales_id.'/',$v);  
			}
			$data=array_merge($_POST['file'],$data);            

		}
   		$attachfile_name=json_encode($data);	
        \DB::update("update s_salesorder_hdr_t set attachfile_name='".$attachfile_name."' where sales_hdr_id='$sales_id'");
        $this->data['notymsg']="yes";
     
       return redirect('soorder'); 
    }
     public function sofilesave(Request $request){
  
   
    if($request->hasfile('email_attachment')){
        File::deleteDirectory(public_path('Uploads/salesorderupload/SO'.$_POST['sohdrid']));

                foreach($request->file('email_attachment') as $file)
                {

                    $name=$file->getClientOriginalName();

                    $file->move(public_path().'/Uploads/salesorderupload/SO'.$_POST['sohdrid'].'/', $name);
                    $data[] = $name;
                }
        $attachfile_name=json_encode($data);
          \DB::update("update s_salesorder_hdr_t set attachfile_name='".$attachfile_name."' where sales_hdr_id=".$_POST['sohdrid']);
        return 1;
            }else{
                $var = File::deleteDirectory(public_path('Uploads/salesorderupload/SO'.$_POST['sohdrid']));
             
              \DB::update("update s_salesorder_hdr_t set attachfile_name='' where sales_hdr_id=".$_POST['sohdrid']);
              return 2;
            }

    }
        
    public function salesorderuploaddata($id)
    {
        $data=\DB::select("select attachfile_name from s_salesorder_hdr_t where sales_hdr_id='$id'");
        
        if(!empty($data)){
            $data=json_decode($data[0]->attachfile_name);
           return  $data;
        }
        
    }
	
	
	/*get frieght carrier details*/
	function getFreight($id)
	{
		$sql=\DB::SELECT("select * from m_frieghtcarriers_hdr_t where ar_frieghtcarriers_hdr_id='".$id."'");
		if(!empty($sql))
			return $sql[0]->carrier_name;
		else
			return '';
	}
	/*get payment term detail*/
	function getPaymentterm($id)
	{
		$sql=\DB::SELECT("select * from m_payment_terms_t where payment_term_id='".$id."'");
		if(!empty($sql))
			return $sql[0]->payment_term_name;
		else
			return '';
	}

	/*for so cancellation*/
    public function cancel($id = null, $type=null)
    {

    	if($type!="" ){
			$data=\DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $id)->get();
			$noti_msg = "Sales Order ".$data[0]->sales_order_no." ".$type;
			 $send_notification = $this->sendPopUpHomeNoty($id,"SO APPROVED",$noti_msg,"soorder");
    		\DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $id)->update(['order_status_id' => $type]);
        		// $status['message'] = $type.' Successfully!!!';
        		
			$so_line_data=\DB::table('s_salesorder_lines_t')->where('sales_hdr_id', $id)->get();
	   		$check=0;
	   		foreach($so_line_data as $key=>$value){
		
			   if($value->qty != $value->pending_qty){
				 $check++;  
			   }
	   		}
			
		   if($check!=0){
			     \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $id)->update(['order_status_id' => "CLOSED"]);
		   }
		   else{
			    \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$id)->update(['order_status_id' => "CANCELLED"]);
		   }

	   		return response()->json(array('status' => 'success', 'message' => 'SO Cancelled','id' => $id));
    	}

    }

    /**/
    public function download($names=null,$sales_id=null)
    {
	  
    	$file_path =public_path('/uploads/salesorderupload/SO'.$sales_id.'/'.$names);
     	$name = basename($names);
        $headers = ['Content-Type: application/pdf'];

    	return response()->download($file_path, $name);
  	}		
           

    public function addressget($id=null)
	{
		$sql = "SELECT
			m_customer_sites_t.address,
			m_customer_sites_t.customer_site_name,
			m_customer_sites_t.pincode,
			m_countries_t.country_name,
			m_states_t.state_name,
			m_cities_t.city_name
			FROM `m_customer_sites_t`
			left join m_countries_t ON
			m_customer_sites_t.country=m_countries_t.country_id
			left join m_states_t ON m_customer_sites_t.state=m_states_t.state_id
			left join m_cities_t ON m_customer_sites_t.city=m_cities_t.city_id
			where m_customer_sites_t.customer_site_id='$id' ";
		
		 $result = \DB::select($sql);
		 $address='';
		 if (count($result)>0) {
		 	if($result[0]->pincode==0){
		 		$address = $result[0]->customer_site_name.','.$result[0]->address.','. $result[0]->city_name.','. $result[0]->state_name.','.$result[0]->country_name;
		 	}
		 	else{
		 		$address = $result[0]->customer_site_name.','.$result[0]->address.','. $result[0]->city_name.','. $result[0]->state_name.'-'. $result[0]->pincode.','.$result[0]->country_name;
		 	}
		}
 	
 		return $address;
	}

    public function update($id=null,$order_type=null,$as = null)
    { 
    	
		$this->data['product']=$this->jcustomproductselect('m_products_t','product_id','concatenated_product','','soorder');
        $this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','salesorder')->get();   
      	$this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name','');
		if($order_type !="LABOUR" && $order_type !="SAMPLE" && $order_type !="EXPORT SAMPLE" && $order_type !="EXPORT" && $order_type!="COPYSO")
		{
			$order_type = "STANDARD"; 
		}
		else
		{ 
			$order_type = $order_type; 
		}
		if($order_type =="EXPORT" || $order_type =="EXPORT SAMPLE"){
		    $proforma_invoice = "YES";
		}else{
		    $proforma_invoice = "";
		}
		$row=$this->model::find($id);

		$this->data['row']['order_type_id']=$order_type;
		$this->data['row']['sales_hdr_id']      ='';
		$this->data['row']['attachfile_name']      ='';
		$this->data['row']['sales_order_no']    ='';
		$this->data['row']['sales_order_date']  =date("Y-m-d");
		$this->data['row']['delivery_date']  =date("Y-m-d");
		$this->data['row']['order_status_id']   ='';
		$this->data['row']['proforma_invoice']   =$proforma_invoice;
		$this->data['row']['con_exc_rate']='';
		$this->data['row']['ar_quote_hdr_id']   ='';
		$this->data['row']['ship_to_customer_id'] ='';
		$this->data['row']['pricelist_id']      ='';
		$this->data['row']['schemes_hdr_id']      ='';
		$this->data['row']['customer_po_number']='';
		$this->data['row']['contact_person']    ='';
		$this->data['row']['contact_number']    ='';
		$this->data['row']['so_ref_no']         ='';
		$this->data['row']['packaging_charges'] ='';
		$this->data['row']['insurance_charges'] ='';
		$this->data['row']['transport_charges'] ='';
		$this->data['row']['other_frieght_amount'] ='';
		$this->data['row']['other_tax_amount'] ='';
		$this->data['row']['schemes'] = $this->jCombocomp('s_schemes_hdr_t','schemes_hdr_id','schemes_name',"");	
		$this->data['row']['cash_discount'] = $this->jCombocomp('s_schemes_hdr_t','schemes_hdr_id','schemes_name',"");	
		$this->data['row']['salesperson_id']    =$this->jCombo('hr_employee_t','employee_id','employee_number|first_name','');
		
		$depart=\Session::get('groupname');
		 $wh='';
		 $wh1='';
		if($depart=="14")
		{
		    $emp_id=\Session::get('emp_id');
            $cus_id=\DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");
		    if($order_type != 'SAMPLE'){
		    $wh=" and customer_id in (".$cus_id[0]->dis.")";
		    }else{
		    $wh1=" and employee_id = '$emp_id'";      
		    }
		}
		
		
		$this->data['row']['employee_id']    =$this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name','',$wh1);
		$this->data['row']['ar_frieghtcarriers_hdr_id']    =$this->jcustomselect('m_frieghtcarriers_hdr_t','ar_frieghtcarriers_hdr_id','carrier_name','','and source_type_id="Sales"');
		$this->data['row']['ar_delivery_terms_id']=$this->jcustomselect('m_delivery_terms_t','delivery_terms_id','delivery_term_name','',' and source_type_id="Sales"');
		$this->data['row']['ar_frieghtterm_id']=$this->jcustomselect('m_frieghtterms_t','frieghtterm_id','fob_point_name','','and source_type_id="Sales"');
		$this->data['row']['ar_payment_term_id']=$this->jCombo('m_payment_terms_t','payment_term_id','payment_term_name','');
		if($order_type =="EXPORT" || $order_type =="EXPORT SAMPLE"){
		$this->data['row']['invoice_currency']=$this->jCombo('f_account_currency_t','account_currency_id','currency_code','');
		}else{
		$this->data['row']['invoice_currency']=$this->jCombo('f_account_currency_t','account_currency_id','currency_code','37');
		}    
		$this->data['row']['ar_payment_method_id']=$this->jCombo('m_payment_methods_t','payment_method_id','payment_method_name','');

		$this->data['row']['transport_mode']=$this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code','','and lookup_type="TRANSPORT_MODE"');
		$this->data['row']['port_of_discharge']=$this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code','','and lookup_type="PORT_OF_DISCHARGE"');
		$this->data['row']['currency_code_id']  ='';
		$this->data['row']['company_id']        ='';
		$this->data['row']['organization_id']   ='';
		$this->data['row']['remarks']           ='';
		$this->data['row']['order_sub_total']   ='';
		$this->data['row']['order_tax']         ='';
		$this->data['row']['order_total']       ='';
		$this->data['row']['qty_total']       ='';
		$this->data['row']['reference_id']      = "";
		$this->data['row']['reference_number']  = "";
		$this->data['row']['source']            = $order_type;
		$this->data['row']['trade_discount'] = "";
        $this->data['row']['trade_discount_pre'] = "";
        $this->data['row']['tcs_applicable'] = "";
        $this->data['row']['tcs_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');;
        $this->data['row']['tcs_amount'] = "";
        $this->data['row']['tcs_calc_amount'] = "";
        $this->data['row']['tcs_prcnt'] = "";
		$this->data['row']['bill_to_address_id']= '';
		$this->data['row']['ship_to_address_id'] = '';
		$this->data['row']['bill_to_address']= '';
		$this->data['row']['ship_to_address']= '';
		
		$this->data['row']['project_id']= $this->jCombo('m_projects_t','project_id','project_name','');                
        $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
		$this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
		
		$this->data['salesperson']=$this->jCombo('hr_employee_t','employee_id','employee_number|first_name','');

		
		
		$this->data['customer']=$this->jcustomselect('m_customers_t','customer_id','customer_number|customer_name','','and savestatus="SAVE"'. $wh);
		$this->data['currency']=$this->jCombo('f_account_currency_t','account_currency_id','currency_code','37');
		$this->data['organization']=$this->jCombo('m_organizations_t','organization_id','organization_name','');
		$this->data['product']=$this->jcustomproductselect('m_products_t','product_id','concatenated_product','','soorder');

		$this->data['uom']=$this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
		
		$this->data['hsn_code']=$this->jcustomselect('f_gst_code_hdr_t','gst_code_hdr_id','classification_code','',' and classification_name="HSN"');
		if($depart=="14")
		{
		    if($order_type != 'SAMPLE'){
		        $this->data['pricelist']=$this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$this->data['row']['pricelist_id'],' and price_list_type="Sales"');
		
		    }else{
		        $this->data['pricelist']=$this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$this->data['row']['pricelist_id'],' and price_list_type="Sales" and pricelist_hdr_id="176"');
		    }
		}else{
		    $this->data['pricelist']=$this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$this->data['row']['pricelist_id'],' and price_list_type="Sales"');
		}

		$this->data['schemes_hdr_id']=$this->jCombocomp('s_schemes_hdr_t','schemes_hdr_id','schemes_name',$this->data['row']['schemes_hdr_id']);

			
		if(!empty($row))
		{
			if($order_type=="COPYSO"){
				
		    $this->data['pageload'] ="copysoorder";
			$this->data['row']['sales_hdr_id']="";
			$this->data['row']['sales_order_no']="";
			$this->data['row']['reference_id']= $row->sales_hdr_id;
			$this->data['row']['reference_number'] = $row->sales_order_no;
			$this->data['row']['order_status_id']='DRAFT';
			$this->data['row']['proforma_invoice']='NO';
		    $this->data['row']['con_exc_rate']='';
			$this->data['row']['transport_mode']='';
			$this->data['row']['port_of_discharge']='';

			}else{
            $this->data['pageload'] ="soorderedit";
			$this->data['row']['sales_hdr_id']=$row->sales_hdr_id;
			$this->data['row']['sales_order_no']=$row->sales_order_no;
			$this->data['row']['reference_id']= $row->reference_id;
			$this->data['row']['reference_number'] = $row->reference_number;
			$this->data['row']['order_status_id']=$row->order_status_id;
			$this->data['row']['proforma_invoice']=$row->proforma_invoice;
			$this->data['row']['con_exc_rate']=$row->con_exc_rate;

			$this->data['row']['transport_mode']=$this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code',$row->transport_mode,'and lookup_type="TRANSPORT_MODE"');
			$this->data['row']['port_of_discharge']=$this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code',$row->port_of_discharge,'and lookup_type="PORT_OF_DISCHARGE"');

			    
			}
          
			$this->data['row']['bill_to_address_id']= $row->bill_to_address_id;
			$this->data['row']['ship_to_address_id']= $row->ship_to_address_id;
			$this->data['row']['sales_order_date']=$row->sales_order_date;
            $this->data['row']['delivery_date']=$row->delivery_date;
			$this->data['row']['order_type_id']=$row->order_type_id;
			$this->data['row']['order_status_id']=$row->order_status_id;
			$this->data['row']['proforma_invoice']=$row->proforma_invoice;
			$this->data['row']['ar_quote_hdr_id']=$row->ar_quote_hdr_id;			
			$this->data['row']['schemes_hdr_id']=$row->schemes_hdr_id;                        
			$this->data['row']['source'] = $row->source;
			$this->data['row']['trade_discount'] = $row->trade_discount;
			$this->data['row']['trade_discount_pre'] = $row->trade_discount_pre;
			$this->data['row']['tcs_applicable'] =  $row->tcs_applicable;
            $this->data['row']['tcs_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments', $row->tcs_account_id);;
            $this->data['row']['tcs_amount'] =  $row->tcs_amount;
            $this->data['row']['tcs_calc_amount'] =  $row->tcs_calc_amount;
            $this->data['row']['tcs_prcnt'] =  $row->tcs_prcnt;
			$this->data['row']['company_id']=$row->company_id;
			$this->data['row']['organization_id']=$row->organization_id;
			$this->data['row']['remarks']=$row->remarks;
			$this->data['row']['order_sub_total']=$row->order_sub_total;
			$this->data['row']['order_tax']=$row->order_tax;
			$this->data['row']['order_total']=$row->order_total;
			$this->data['row']['qty_total']=$row->qty_total;
			$this->data['row']['customer_po_number']=$row->customer_po_number;
			$this->data['row']['contact_person']=$row->contact_person;
			$this->data['row']['contact_number']=$row->contact_number;
			$this->data['row']['so_ref_no']=$row->so_ref_no;
			$this->data['row']['attachfile_name']=$row->attachfile_name;
			$this->data['row']['packaging_charges']=$row->packaging_charges;
			$this->data['row']['insurance_charges']=$row->insurance_charges;
			$this->data['row']['transport_charges']=$row->transport_charges;
			$this->data['row']['other_frieght_amount']=$row->other_frieght_amount;
			$this->data['row']['other_tax_amount']=$row->other_tax_amount;
			$this->data['row']['ar_payment_method_id']=$row->ar_payment_method_id;
			$this->data['row']['packaging_charges_tax'] =$row->packaging_charges_tax;
			$this->data['row']['insurance_charges_tax'] =$row->insurance_charges_tax;
			$this->data['row']['transport_charges_tax'] =$row->transport_charges_tax;
			$this->data['row']['other_frieght_amount_tax'] =$row->other_frieght_amount_tax;
			$this->data['row']['other_tax_amount_tax'] =$row->other_tax_amount_tax;
			$this->data['row']['employee_id']    =$this->jCombo('hr_employee_t','employee_id','employee_number|first_name',$row->employee_id);
			$this->data['customer']=$this->jcustomselect('m_customers_t','customer_id','customer_number|customer_name',$row->ship_to_customer_id,'and savestatus="SAVE"'); 
			$this->data['customer_id']=$row->ship_to_customer_id; 

			$this->data['row']['salesperson_id']    =$this->jCombo('hr_employee_t','employee_id','employee_number|first_name',$row->salesperson_id);
			$this->data['row']['currency_code_id']=$row->currency_code_id;			
			$this->data['row']['ar_frieghtterm_id']=$row->ar_frieghtterm_id;
			$this->data['row']['pricelist_id']=$row->pricelist_id;                        
			$this->data['row']['freight_carrier_id'] = $row->freight_carrier_id; 
			$this->data['pricelist']=$this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$row->pricelist_id,' and price_list_type="Sales"');
			$this->data['row']['ar_frieghtterm_id']=$this->jcustomselect('m_frieghtterms_t','frieghtterm_id','fob_point_name',$row->ar_frieghtterm_id,'and source_type_id="Sales"');
					
		    $this->data['row']['project_id'] = $this->jCombo('m_projects_t','project_id','project_name',$row->project_id);
					
		    $this->data['row']['ar_payment_term_id']=$this->jCombo('m_payment_terms_t','payment_term_id','payment_term_name',$row->ar_payment_term_id);
					
			$this->data['row']['ar_delivery_terms_id']=$this->jcustomselect('m_delivery_terms_t','delivery_terms_id','delivery_term_name',$row->ar_delivery_terms_id,'and source_type_id="Sales"');
			$this->data['discount']=$this->jCombodiscount('m_discounts_hdr_t','ar_discount_hdr_id','discount_name',$row->discount_id);		
			$this->data['row']['ar_frieghtcarriers_hdr_id']=$this->jcustomselect('m_frieghtcarriers_hdr_t','ar_frieghtcarriers_hdr_id','carrier_name',$row->freight_carrier_id,'and source_type_id="Sales"');
			$this->data['row']['invoice_currency']=$this->jCombo('f_account_currency_t','account_currency_id','currency_code',$row->invoice_currency);
					
			$this->data['row']['ar_payment_method_id']=$this->jCombo('m_payment_methods_t','payment_method_id','payment_method_name',$row->ar_payment_method_id);
			
			$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
			if($row->ship_to_customer_id > 0){
            $this->data['row']['bill_to_address']=$this->sobilladdress($row->bill_to_address_id);
            $this->data['row']['ship_to_address']= $this->soshipaddress($row->ship_to_address_id);   
			}else{
			$this->data['row']['bill_to_address']=$this->employeeaddress($row->employee_id);
            $this->data['row']['ship_to_address']= $this->employeeaddress($row->employee_id);	
			} 

            $this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',$row->organization_id);
            $this->data['row']['schemes'] = $this->jCombocomp('s_schemes_hdr_t','schemes_hdr_id','schemes_name',"");
            $this->data['row']['cash_discount'] = $this->jCombocomp('s_schemes_hdr_t','schemes_hdr_id','schemes_name',"");


		}
        else
        {
            $this->data['pageload'] ="soordercreate";
           	$this->data['part_no']=$this->jcustomselecttool('m_manufacturer_partno_t','manufacturer_partno_id','part_no','','');
			$this->data['discount']=$this->jCombodiscount('m_discounts_hdr_t','ar_discount_hdr_id','discount_name','');	
		}
			
        if($this->data['pageMethod'] =='soorderapproved')
        {
            $this->data['row']['salesperson_id']    =$this->jCombo('hr_employee_t','employee_id','employee_number|first_name',$row->salesperson_id);
        }

		$this->data['linedata'] = array();
      	
      	$tablelines = \DB::table('s_salesorder_lines_t')->where('sales_hdr_id',$id)->get();

      	foreach($tablelines as $key=>$value){

      		$this->data['linedata'][$key]=(object) array();
			if($order_type=="COPYSO"){
			$this->data['linedata'][$key]->sales_line_id ="";
			}else{
			$this->data['linedata'][$key]->sales_line_id = $value->sales_line_id;
			}
			if($row->ship_to_customer_id == "0"){
    			$this->data['product'] =$this->jcustomproductselect('m_products_t','product_id','product_code|concatenated_product','','soorder');
    		}
    		else{
    			$this->data['product'] = $this->getcustomerpriceproduct($row->ship_to_customer_id,'');
    		}

    		if($row->ship_to_customer_id == "0" && $value->product_id=="0" ){
    			$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t','product_id','product_code|concatenated_product','','soorder');
    		}
    		else if($row->ship_to_customer_id!= "0" && $value->product_id=="0" ){
    			$this->data['linedata'][$key]->product_id = $this->getcustomerpriceproduct($row->ship_to_customer_id,$value->product_id);	
    		}
    		else{
    			$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);	
    		}

         	$this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
			$this->data['linedata'][$key]->manufacturer_partno_id = $this->jcombocomp('m_manufacturer_partno_t','manufacturer_partno_id','part_no',$value->part_no,'and product_id='.$value->product_id.' and manufacturer_source_value_id='.$this->data['customer_id']);   
            $hsn=\DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=".$value->product_id);
            if(count($hsn)>0){
            	$this->data['linedata'][$key]->hsn_code =  $this->jcustomselect('f_gst_code_hdr_t','gst_code_hdr_id','classification_code',$value->hsn_code,' and gst_code_hdr_id in('.$hsn[0]->hsn_code.')');	
            }else{
            	$this->data['linedata'][$key]->hsn_code =  $this->jcustomselect('f_gst_code_hdr_t','gst_code_hdr_id','classification_code',$value->hsn_code,' ');
            }
	        
			$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$value->tax_group_id);
            $this->data['linedata'][$key]->qty = $value->qty;
            $this->data['linedata'][$key]->free_qty = $value->free_qty;
            $this->data['linedata'][$key]->unit_price = $value->unit_price;
            $this->data['linedata'][$key]->discount_percentage=$value->discount_percentage;
            $this->data['linedata'][$key]->discount_amount=$value->discount_amount;
            $this->data['linedata'][$key]->tax_amount=$value->tax_amount;
			$this->data['linedata'][$key]->line_total=$value->line_total;
	        $this->data['linedata'][$key]->delivery_date=$value->delivery_date;
			$this->data['linedata'][$key]->comments=$value->comments;
			$this->data['linedata'][$key]->tax_excemption=$value->tax_excemption;

			$qoh=DB::SELECT("select sum(f.qty) as qty,f.product_id from (select sum(qoh_trx_qty)as qty,product_id FROM i_qoh_detail_t GROUP by product_id UNION ALL SELECT sum(reserv_trx_qty) as qty ,product_id from i_reservation_detail_t GROUP by product_id)f where f.product_id=".$value->product_id." GROUP BY f.product_id");
			
			$tqoh_qty = 0;
			if(count($qoh)>0){
				if($qoh[0]->qty > 0){
					$tqoh_qty = $qoh[0]->qty;
				}
			}

			$this->data['linedata'][$key]->qoh_qty=$tqoh_qty;
      	}

		$this->data['return_url']=\Request::route()->getName();
        
        $this->data['pageModule'] = $this->pageModule;
            
        $this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','salesorder')->get();
            
        $this->data['country_new']=$this->jCombologin('m_countries_t','country_id','country_name','');
        $this->data['state_new']=$this->jCombologin('m_states_t','state_id','state_name','');
        $this->data['city_new']=$this->jCombologin('m_cities_t','city_id','city_name','');

		return view('soorder.form',$this->data);
    }
	
	/*get discount percentage for enquiry conversion function*/
	public function customerdiscount($cusid){
		$sql = DB::table('m_customers_t')
				->leftjoin('m_discounts_hdr_t','m_discounts_hdr_t.ar_discount_hdr_id','=','m_customers_t.ar_discount_hdr_id')
				->where('customer_id',$cusid)->select('m_discounts_hdr_t.ar_discount_hdr_id','m_discounts_hdr_t.discount_name','m_discounts_hdr_t.default_discount_amount')->get();
		if(count($sql) >0){
			$dis['dis_id'] = $sql[0]->ar_discount_hdr_id;
			$dis['dis_name'] = $sql[0]->discount_name;
			$dis['dis_amt'] = $sql[0]->default_discount_amount;
		}else{
			$dis['dis_id'] = 0;
			$dis['dis_name'] = '';
			$dis['dis_amt'] = 0;
		}

		return $dis;
	}


	public function enquiryupdate($id=null)
	{
	    $this->data['id'] = $id;
	    $table = \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id',$id)->get();
		$sotable = $this->model->getTableColumns();
		$this->data['product']=$this->jcustomproductselect('m_products_t','product_id','concatenated_product','','soorder');
		$this->data['row']= array();
		foreach($sotable as $key=>$val)
		{
			$this->data['row'][$val]='';
		}	
	   
		$this->data['row']['sales_order_date']=$table[0]->inquiry_date;
		$this->data['row']['order_status_id']='INITIATED';
		$address = $this->custaddress($table[0]->customerid);
		$this->data['row']['bill_to_address']=$address[0];
		$this->data['row']['ship_to_address']= $address[1];
		$this->data['row']['ship_to_address_id']= $address[3];
		$this->data['row']['bill_to_address_id']= $address[2];
		$this->data['row']['project_id']= $this->jCombo('m_projects_t','project_id','project_name',$table[0]->project_id);
		$this->data['row']['order_type_id']=$table[0]->inquiry_type;
		$this->data['row']['remarks']=$table[0]->remarks;
		$pricelist= $this->pricelist_cust($table[0]->customerid);
		
		$this->data['pricelist']= $this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$pricelist,' and price_list_type="Sales"');
	    $this->data['customer']=$this->jcustomselect('m_customers_t','customer_id','customer_number|customer_name',$table[0]->customerid,'and savestatus="SAVE"');
		$this->data['customer_id']=$table[0]->customerid; 
		$customerdata=$this->getcustomerdatas($table[0]->customerid);
		$this->data['row']['salesperson_id']=$this->jCombo('hr_employee_t','employee_id','employee_number|first_name',$customerdata[0]->sales_person);
	    $this->data['row']['ar_delivery_terms_id']= $this->jcustomselect('m_delivery_terms_t','delivery_terms_id','delivery_term_name','','and source_type_id="Sales"');
	    $this->data['row']['ar_payment_method_id']= $this->jCombo('m_payment_methods_t','payment_method_id','payment_method_name',$customerdata[0]->default_payment_method_id);
		$this->data['row']['ar_frieghtcarriers_hdr_id']= $this->jcustomselect('m_frieghtcarriers_hdr_t','ar_frieghtcarriers_hdr_id','carrier_name',$customerdata[0]->ar_frieghtcarriers_hdr_id,'and source_type_id="Sales"');
		$this->data['row']['ar_payment_term_id']= $this->jCombo('m_payment_terms_t','payment_term_id','payment_term_name',$customerdata[0]->default_payment_terms_id);
		$this->data['row']['ar_frieghtterm_id']= $this->jcustomselect('m_frieghtterms_t','frieghtterm_id','fob_point_name','','and source_type_id="Sales"');
		$this->data['discount']=$this->jCombodiscount('m_discounts_hdr_t','ar_discount_hdr_id','discount_name',$customerdata[0]->ar_discount_hdr_id);
		
    	$this->data['row']['invoice_currency']=$this->jCombo('f_account_currency_t','account_currency_id','currency_code','');
		$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
		$this->data['row']['reference_id']= $id;
		$this->data['row']['reference_number'] = $table[0]->inquiry_no;
		$this->data['row']['source'] = 'ENQUIRY';
		/*address popup*/
		$this->data['country_new']=$this->jCombologin('m_countries_t','country_id','country_name','');
		/*end*/
		/*other tax popup*/
		$this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name','');
		/*end*/
		/*lines data*/
		 $this->data['linedata'] = array();
      $tablelines = \DB::table('s_inquiry_lines_t')->where('so_inquiry_hdr_id',$id)->get();
			$grandtotal = 0;
 	        $taxamount_total =0;
                $key=-1;
                $enqcount=0;
		foreach($tablelines as $key1=>$value){

            if($this->data['row']['order_type_id']=="STANDARD"){
					$tax = $this->gettax($value->product_id);
					$unitprice = $this->pricelist($table[0]->customerid,$value->product_id);
                if($unitprice!="0.00")
                {
                    $key++;
                    $this->data['linedata'][$key]=(object) array();
						$this->data['linedata'][$key]->sales_line_id = '';
                    $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
					$this->data['linedata'][$key]->manufacturer_partno_id = $this->jcombocomp('m_manufacturer_partno_t','manufacturer_partno_id','part_no',$value->part_no,'and product_id='.$value->product_id.' and manufacturer_source_value_id='.$this->data['customer_id']);   
                $hsn=\DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=".$value->product_id);
	        $this->data['linedata'][$key]->hsn_code =  $this->jcustomselect('f_gst_code_hdr_t','gst_code_hdr_id','classification_code',$hsn[0]->defalut_hsn_code,' and gst_code_hdr_id in('.$hsn[0]->hsn_code.')');
           $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$tax['taxgroup_id']);
           $this->data['linedata'][$key]->qty = $value->required_qty;
           $this->data['linedata'][$key]->free_qty = 0;
					$discount  = $this->customerdiscount($table[0]->customerid);

            $this->data['linedata'][$key]->unit_price = $unitprice;

            $this->data['linedata'][$key]->discount_percentage=$discount['dis_amt'];
            $this->data['linedata'][$key]->discount_amount=(($value->required_qty * $unitprice ) * $discount['dis_amt'])/100;
            
                               $taxamount = (($value->required_qty * $unitprice) * $tax['display_name'] )/ 100;
			$linetotal = ($taxamount + ($value->required_qty * $unitprice));
                                	$taxamount_total += $taxamount;
                                        $grandtotal = $grandtotal + $linetotal;
			       $this->data['linedata'][$key]->tax_amount=$taxamount;
					$this->data['linedata'][$key]->line_total=$linetotal;
			        $this->data['linedata'][$key]->delivery_date=$value->need_by_date;
					$this->data['linedata'][$key]->comments=$value->comments;
					$this->data['linedata'][$key]->tax_excemption='No';

					$qoh=DB::SELECT("select sum(f.qty) as qty,f.product_id from (select sum(qoh_trx_qty)as qty,product_id FROM i_qoh_detail_t GROUP by product_id UNION ALL SELECT sum(reserv_trx_qty) as qty ,product_id from i_reservation_detail_t GROUP by product_id)f where f.product_id=".$value->product_id." GROUP BY f.product_id");
					
					$tqoh_qty = 0;
					if(count($qoh)>0){
						if($qoh[0]->qty > 0){
							$tqoh_qty = $qoh[0]->qty;
						}
					}
					$this->data['linedata'][$key]->qoh_qty=$tqoh_qty;
					
					if($table[0]->customerid == ""){
            			$this->data['product'] =$this->jcustomproductselect('m_products_t','product_id','product_code|concatenated_product','','soquote');
            		}
            		else{
            			$this->data['product'] = $this->getcustomerpriceproduct($table[0]->customerid,'');
            		}
            		if($table[0]->customerid == "" && $value->product_id=="" ){
            			$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t','product_id','product_code|concatenated_product','','soquote');
            		}
            		else if($table[0]->customerid != "" && $value->product_id=="" ){
            			$this->data['linedata'][$key]->product_id = $this->getcustomerpriceproduct($table[0]->customerid,$value->product_id);	
            		}
            		else{
            			$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);	
            		}
                }
                else
                {
                 $enqcount++;   
                }

               }
                else{
                    $this->data['linedata'][$key]=(object) array();
					$this->data['linedata'][$key]->sales_line_id = '';
					if($table[0]->customerid == ""){
            			$this->data['product'] =$this->jcustomproductselect('m_products_t','product_id','product_code|concatenated_product','','soquote');
            		}
            		else{
            			$this->data['product'] = $this->getcustomerpriceproduct($table[0]->customerid,'');
            		}
            		if($table[0]->customerid == "" && $value->product_id=="" ){
            			$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t','product_id','product_code|concatenated_product','','soquote');
            		}
            		else if($table[0]->customerid != "" && $value->product_id=="" ){
            			$this->data['linedata'][$key]->product_id = $this->getcustomerpriceproduct($table[0]->customerid,$value->product_id);	
            		}
            		else{
            			$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);	
            		}

                    $this->data['linedata'][$key]->product_description  =  $value->product_description;
					$this->data['linedata'][$key]->manufacturer_partno_id = $this->jcombocomp('m_manufacturer_partno_t','manufacturer_partno_id','part_no',$value->part_no,'and product_id='.$value->product_id.' and manufacturer_source_value_id='.$this->data['customer_id']);
					$this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
					$this->data['linedata'][$key]->qty = $value->required_qty;
					$this->data['linedata'][$key]->unit_price = '';
					$this->data['linedata'][$key]->tax_amount='';
					$this->data['linedata'][$key]->line_total='';
					$this->data['linedata'][$key]->delivery_date=$value->need_by_date;
					$this->data['linedata'][$key]->comments=$value->comments;
					$this->data['linedata'][$key]->hsn_code =  $this->jcustomselect('f_gst_code_hdr_t','gst_code_hdr_id','classification_code','','and classification_name="SAC"');
					$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name','');
					$unitprice = $this->pricelist($table[0]->customerid,$value->product_id);
					if($unitprice!="0.00")
                	{
                		$this->data['linedata'][$key]->unit_price = $unitprice;
					}else{
						$this->data['linedata'][$key]->unit_price = '';
					}
                }
                }
                $this->data['quotecount']=$enqcount;
		/*end*/

		$this->data['return_url'] = 'soordercreate';
        $this->data['route']='convertenquiry';
        $this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','salesorder')->get();
  $this->data['pageMethod']=\Request::route()->getName();
 //dd($this->data);
		
		return view('soorder.form',$this->data);        
	}
	
	public function quoteupdate($id=null)
	{
		
		$this->data['pageMethod']="soorderfromqo";
		$this->data['id'] = $id;
		$table = \DB::table('s_quote_hdr_t')->where('quote_hdr_id',$id)->get();
		$this->data['product']=$this->jcustomproductselect('m_products_t','product_id','concatenated_product','','soorder');
		$this->data['customer']=$this->jcustomselect('m_customers_t','customer_id','customer_number|customer_name',$table[0]->customerid,'and savestatus="SAVE"');
		$this->data['customer_id']=$table[0]->customerid; 
		$this->data['row']['sales_hdr_id'] = '';
		$this->data['row']['sales_order_no']="";
		$this->data['row']['sales_order_date']=$table[0]->quote_date;
        $this->data['row']['delivery_date']=date('Y-m-d');
		$this->data['row']['order_status_id']='INITIATED';
		$this->data['row']['ar_quote_hdr_id']=$id;
		$this->data['row']['customer_id']=$table[0]->customerid;
		$this->data['row']['ship_to_customer_id']=$table[0]->customerid;
		$this->data['row']['ship_to_address_id']= $table[0]->ship_to_address_id;
		$this->data['row']['bill_to_address_id']=$table[0]->bill_to_address_id;
		$this->data['row']['bill_to_address']=$this->sobilladdress($table[0]->bill_to_address_id);
		$this->data['row']['ship_to_address']= $this->soshipaddress($table[0]->ship_to_address_id);
		$this->data['row']['order_type_id']=$table[0]->quote_type;
		$this->data['row']['customer_po_number']='';
		$this->data['row']['contact_person']='';
		$this->data['row']['contact_number']='';
		$this->data['row']['so_ref_no']='';
		$this->data['row']['salesperson_id']=$table[0]->salesperson_id;
		$this->data['row']['currency_code_id']=$table[0]->currency_id;
		$this->data['row']['company_id']="";
		$this->data['row']['organization_id']=$table[0]->organization_id;
		$this->data['row']['freight_carrier_id']="";
		$this->data['row']['remarks']=$table[0]->remarks;
		$this->data['row']['order_sub_total']=$table[0]->quote_sub_total;
		$this->data['row']['order_tax']=$table[0]->quote_tax_total;
		$this->data['row']['project_id']= $this->jCombo('m_projects_t','project_id','project_name',$table[0]->project_id);
		$this->data['row']['ar_payment_term_id']= $this->jCombo('m_payment_terms_t','payment_term_id','payment_term_name',$table[0]->payment_term_id);				
		$this->data['row']['ar_frieghtterm_id']= $this->jcustomselect('m_frieghtterms_t','frieghtterm_id','fob_point_name',$table[0]->frieghtterm_id,'and source_type_id="Sales"');
		$this->data['row']['ar_delivery_terms_id']= $this->jcustomselect('m_delivery_terms_t','delivery_terms_id','delivery_term_name',$table[0]->delivery_terms_id,'and source_type_id="Sales"');
		$this->data['row']['ar_payment_method_id']= $this->jCombo('m_payment_terms_t','payment_term_id','payment_term_name',$table[0]->payment_term_id);
		$this->data['row']['ar_frieghtcarriers_hdr_id']= $this->jcustomselect('m_frieghtcarriers_hdr_t','ar_frieghtcarriers_hdr_id','carrier_name',$table[0]->frieghtcarriers_hdr_id,'and source_type_id="Sales"');
		$this->data['discount']=$this->jCombodiscount('m_discounts_hdr_t','ar_discount_hdr_id','discount_name',$table[0]->discount_id);
		$this->data['row']['order_total']=$table[0]->quote_grand_total;

		$this->data['row']['invoice_currency']=$this->jCombo('f_account_currency_t','account_currency_id','currency_code',$table[0]->currency_id);
        $this->data['pricelist'] = $this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$table[0]->quote_pricelist_id,' and price_list_type="Sales"');

        $this->data['row']['salesperson_id']=$this->jCombo('hr_employee_t','employee_id','employee_number|first_name',$table[0]->salesperson_id);
		$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
		$this->data['row']['reference_id']= $id;
		$this->data['row']['reference_number'] = $table[0]->quote_no;
		$this->data['row']['source'] = 'QUOTE'; //don't change these->kaviya
		$this->data['row']['packaging_charges'] = $table[0]->packaging_charges;
		$this->data['row']['unloading_charges'] = $table[0]->unloading_charges;
		$this->data['row']['transport_charges'] = $table[0]->transport_charges;
		$this->data['row']['insurance_charges'] = $table[0]->insurance_charges;
        $this->data['row']['packaging_charges_tax'] = $table[0]->packaging_charges_tax;
		$this->data['row']['insurance_charges_tax'] = $table[0]->insurance_charges_tax;
		$this->data['row']['transport_charges_tax'] = $table[0]->transport_charges_tax;
		$this->data['row']['other_frieght_amount_tax'] =$table[0]->other_frieght_amount_tax;
		$this->data['row']['other_tax_amount_tax'] =$table[0]->other_tax_amount_tax;
		$this->data['row']['other_frieght_amount'] =$table[0]->other_frieght_amount;
		$this->data['row']['other_tax_amount'] =$table[0]->other_tax_amount;
		
		$tablelines = \DB::table('s_quote_lines_t')->where('quote_hdr_id',$id)->get();
		$this->data['subgrid']['label_data'] = $table;
		$tablefield = $this->submodel->getTableColumns();
		foreach($tablefield as $key=>$val)
		{
			$data[$val]=$val;
		}
		$this->data['subgrid']=$this->submodel->subgridRead($id);
		$this->data['linedata'] = array();
		foreach($tablelines as $key=>$value)
		{ 
			$this->data['linedata'][$key]=(object) array();
			$this->data['linedata'][$key]->sales_hdr_id = $id;
			$this->data['linedata'][$key]->sales_line_id = '';
			$this->data['linedata'][$key]->free_qty = '';
			$this->data['linedata'][$key]->manufacturer_partno_id = $this->jcombocomp('m_manufacturer_partno_t','manufacturer_partno_id','part_no',$value->part_no,'and product_id='.$value->product_id.' and manufacturer_source_value_id='.$this->data['row']['customer_id']);
			$this->data['linedata'][$key]->line_no = $value->line_no;
            $this->data['linedata'][$key]->product_description = $value->product_description;
			$this->data['linedata'][$key]->qty = $value->qty;
			$this->data['linedata'][$key]->unit_price = $value->unit_price;
			$this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
			$this->data['linedata'][$key]->discount_amount = $value->discount_amount;
			$this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
			$hsn=\DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=".$value->product_id);
			if(count($hsn) > 0 ){
				$de_code = $hsn[0]->defalut_hsn_code;
				$hsn_code = $hsn[0]->hsn_code;
			}else{
				$hsn_code= 0;
				$de_code= 0;
			}
			$this->data['linedata'][$key]->hsn_code =  $this->jcustomselect('f_gst_code_hdr_t','gst_code_hdr_id','classification_code',$de_code,' and gst_code_hdr_id in('.$hsn_code.')');

			$this->data['linedata'][$key]->tax_excemption = $value->tax_excemption; 
			$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$value->tax_group_id); 
			$this->data['linedata'][$key]->tax_amount = $value->tax_amount;
			$this->data['linedata'][$key]->line_total = $value->line_total;
			$this->data['linedata'][$key]->line_sub_total = $value->line_subtotal;
			$this->data['linedata'][$key]->promised_date = $value->promised_date;
			
			$qoh=DB::SELECT("select sum(f.qty) as qty,f.product_id from (select sum(qoh_trx_qty)as qty,product_id FROM i_qoh_detail_t GROUP by product_id UNION ALL SELECT sum(reserv_trx_qty) as qty ,product_id from i_reservation_detail_t GROUP by product_id)f where f.product_id=".$value->product_id." GROUP BY f.product_id");
					
					$tqoh_qty = 0;
					if(count($qoh)>0){
						if($qoh[0]->qty > 0){
							$tqoh_qty = $qoh[0]->qty;
						}
					}
					$this->data['product'] = $this->getcustomerpriceproduct($table[0]->customerid,'');
			$this->data['linedata'][$key]->qoh_qty = $tqoh_qty;
			if($table[0]->customerid == ""){
    			$this->data['product'] =$this->jcustomproductselect('m_products_t','product_id','product_code|concatenated_product','','soquote');
    		}
    		else{
    			$this->data['product'] = $this->getcustomerpriceproduct($table[0]->customerid,'');
    		}
    		if($table[0]->customerid == "" && $value->product_id=="" ){
    			$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t','product_id','product_code|concatenated_product','','soquote');
    		}
    		else if($table[0]->customerid != "" && $value->product_id=="" ){
    			$this->data['linedata'][$key]->product_id = $this->getcustomerpriceproduct($table[0]->customerid,$value->product_id);	
    		}
    		else{
    			$this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);	
    		}
			
		}

		$this->data['currency_code_id']=$this->jCombo('f_account_currency_t','account_currency_id','currency_code',$table[0]->currency_id);

		$this->data['currency']=$this->jCombo('f_account_currency_t','account_currency_id','currency_code','');
		$this->data['organization']=$this->jCombo('m_organizations_t','organization_id','organization_name','');
		
		$this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name','');
        

        $this->data['return_url'] = 'soordercreate';
        $this->data['route']='convertquote';
        $this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','salesorder')->get();
            
		// dd($this->data);
		
		return view('soorder.form',$this->data);
                
	}

	public function getordcustomeralldata()
    { 
    	$id=$_GET['id']; 
       
        if($_GET['id']!='')
        {
           $cusdata=\DB::select("select * from m_customers_t where customer_id=$id");   
           return $cusdata; 
        }
        else
        {
        return 0;
        }
       
    }
    
    public function destroy(Soorder $soorder)
    {
      
    }


	

	function invoicedqty($id,$order_status)
	{
		if($order_status !="DISPATCH")	
		{
			$source = "SALES ORDER";
		}
		else
		{
			$source = "DISPATCH";	
		}
		$sql =\DB::select("SELECT
	    s_invoice_hdr_t.*,
	    sum(qty) as qty
		FROM
		    s_invoice_hdr_t LEFT JOIN s_invoice_lines_t
		ON
		    s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id
		WHERE
	    s_invoice_hdr_t.reference_source_id = '$id' AND s_invoice_hdr_t.source = '$source'");
		if($sql[0]->invoice_hdr_id !== NULL)
		{
			
			$qty = $sql[0]->qty;
		}
		else
		{
			$qty = 0;
		}
		
		return $qty;
	}

// COMMON FUNCTIONS


//MULTI ARRAY FIELDS INTO A SINGLE ROWS CONVERSION


	function detailview_converttolinerow($data)
	{

		$data_only_bulk=array();
		foreach($data as $key=>$value)
		{
		$key1 = $key;
		$data_only_bulk[$key]=$value;
		}

		$cnt=count($data_only_bulk);
		$line_data_array=array();

		for($i=0;$i<$cnt;$i++)
		{

		foreach($data_only_bulk as $key=>$value)
		{

		$cnt1=count($value);
		for($j=0;$j<$cnt1;$j++)
		{
		$key=$key;
		@$line_data_array[$j][$key]=$value[$j];

		}

		}

		}

		return($line_data_array);

	}

	function convert_column_to_row($data)
	{

		$data_only_bulk=array();


		foreach($data as $key=>$value)
		{
		$key1 = $key;
		$data_only_bulk[$key]=$value;
		}

		$cnt=count($data_only_bulk);
		$line_data_array=array();

		for($i=0;$i<$cnt;$i++)
		{

		foreach($data_only_bulk as $key=>$value)
		{

		$cnt1=count($value);

		foreach($value as $key1=>$value1)
		{
			$line_data_array[$key1][$key]=$value1;
		}

		}

		}

		return($line_data_array);

	}

//MULTI ARRAY FIELDS INTO A SINGLE ROWS CONVERSION

	function Configtabledata($table=null)
	{

		$new_field_data=array();

		$get_table_columns="SHOW COLUMNS FROM $table";
		$col_data=\DB::select($get_table_columns);

		if(!empty($col_data))
		{
			// HEADER AND LABEL DETAILS
		$new_field_data['tablePimarykey']='';

			foreach($col_data as $key=>$field)
			{
				$table_field_key=$field->Key;
				$field=$field->Field;

				$new_field_data['field_data'][$field]=$field;


				if($table_field_key=="PRI")
				{
				$new_field_data['tablePimarykey']=$field;
				}

				//dd($table_field_key,$new_field_data);


			}
		}

		return $new_field_data;

	}

	function validateForm($request=null)
	{

		$form_config=json_decode(urldecode($request['form_data_json']),true);

		$forms = array();

		//$header_chk=array_key_exists("header",$form_config);
		//$lines_chk=array_key_exists("lines",$form_config);

		$forms['header']=$form_config['header'];
		$forms['lines']=$form_config['lines'];


		/*
		$forms = $form_config['header'];
		$rules = array();

		foreach($forms as $form_field_name=>$form)
		{
			if($form['required']=='')
			{
			$rules[$form['field']] = 'required';
			}
			elseif ($form['required'] == 'alpa')
			{
			$rules[$form['field']] = 'required|alpa';
			}
			elseif ($form['required'] == 'alpa_num')
			{
			$rules[$form['field']] = 'required|alpa_num';
			}
			elseif ($form['required'] == 'alpa_dash')
			{
			$rules[$form['field']]='required|alpa_dash';
			}
			elseif ($form['required'] == 'email')
			{
			$rules[$form['field']] ='required|email';
			}
			elseif ($form['required'] == 'numeric')
			{
			$rules[$form['field']] = 'required|numeric';
			}
			elseif ($form['required'] == 'date')
			{
			$rules[$form['field']]='required|date';
			}
			else if($form['required'] == 'url')
			{
			$rules[$form['field']] = 'required|active_url';
			}
			else
			{

			}

		}
		*/

		//dd($forms);

		//$forms = $form_config['header'];
		$rules = array();

		foreach($forms as $form_type=>$form_type_val)
		{
			foreach($form_type_val as $form_field_name=>$form)
			{
				if($form['required']=='')
				{
				$rules[$form_type][$form['field']] = 'required';
				}
				elseif ($form['required'] == 'alpa')
				{
				$rules[$form_type][$form['field']] = 'required|alpa';
				}
				elseif ($form['required'] == 'alpa_num')
				{
				$rules[$form_type][$form['field']] = 'required|alpa_num';
				}
				elseif ($form['required'] == 'alpa_dash')
				{
				$rules[$form_type][$form['field']]='required|alpa_dash';
				}
				elseif ($form['required'] == 'email')
				{
				$rules[$form_type][$form['field']] ='required|email';
				}
				elseif ($form['required'] == 'numeric')
				{
				$rules[$form_type][$form['field']] = 'required|numeric';
				}
				elseif ($form['required'] == 'date')
				{
				$rules[$form_type][$form['field']]='required|date';
				}
				else if($form['required'] == 'url')
				{
				$rules[$form_type][$form['field']] = 'required|active_url';
				}
				else
				{

				}

			}

		}

		return $rules;
	}

	function validateListError( $rules )
	{
		$errMsg = \Lang::get('core.note_error') ;
		$errMsg .= '<hr /> <ul>';
		foreach($rules as $key=>$val)
		{
			$errMsg .= '<li>'.$key.' : '.$val[0].'</li>';
		}
		$errMsg .= '</li>';
		
		return $errMsg;
	}


	function parseFormdata($data=null)
	{
		$new_data = array();

		if(!empty($data))
		{
				foreach($data as $key=>$val)
				{
					$bulk_field_check=preg_match("/\[\]|bulk_/",$key);

					if(!$bulk_field_check)
					{
						if($key!='form_data_json' && $key!='removed_line_id' && $key!='_token' && $key!='enable-masterdetail' && $key!='submit_type' && $key!='counter')
						{
						$new_data['header'][$key]=$val;
						}
						else
						{
						$new_data['others'][$key]=$val;
						}
					}
					else
					{
						$field_bracket_removed= preg_replace("/\[\]/", "", $key);
						$field_bulk_removed = preg_replace("/\[\]|bulk_/", "", $key);
						$new_data['lines'][$field_bulk_removed]=$val;
					}
				}
		}

		return $new_data;
	}

	public function quotedatum(Request $request)
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

		$this->data['status']="APPROVED";
		$this->data['pageMethod']="soorderfromqo";
		$this->data['opt']='';
        $this->data['customer']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
		
		return view('soorder.qotable',$this->data);

    }

	public function getcustomerdatas($id=null)
	{ 
	  if($id!="")
	  {
	   $cusdata=\DB::select("select * from m_customers_t where customer_id=$id");
		 return $cusdata; 
	  } 
	}
	
	
	function getLocationwiseaddress($location=null)
	{
		
		$sql=\DB::SELECT("select * from m_location_t where location_id=".$location."");
		if(!empty($sql))
		{
			return $sql;
		}
			return 0;
	}
	
	function getCompany($company=null)
	{
		$sql=array();
		
		$sql=\DB::SELECT("SELECT company_id,company_name,contact_no FROM `m_company_t` WHERE `company_id`=".$company."");
		if(!empty($sql))
		{
		return $sql;
		}else{
		return 0;
		}
	}
	
	function getCity($id=null)
	{
		 $city=array();
		 $city=\DB::select("select * from m_cities_t where city_id=".$id."");
		 if(!empty($city))
		 {
		   return $city;
		 }
		 else
		 {
		   return 0;
		 }
		
	}
	
	function getState($id=null)
	{
		
		$state=array();
		$state=\DB::select("select state_name,state_code_no,state_code from m_states_t where state_id=".$id."");
		
		if(!empty($state))
		{
		 return $state;
		}
		else
		{
			return 0;
		}
	}
	
	function getCountry($id=null)
	{
		$country=array();
		$country=\DB::select("select country_name from m_countries_t where country_id=".$id."");
		
		if(!empty($country))
		{
			return $country;
		}
		else
		{
			return 0;
		}
	}
	
	
	public function gettax($pid)
	{
		
	 	$pro_details=\DB::table("m_products_t")->select('trx_uom_id','hsn_code')->where('product_id',$pid)->get();

		if(!empty($pro_details))
		$prd_data['uom_code_id']=$pro_details[0]->trx_uom_id;
		$hsn_code=explode(',',$pro_details[0]->hsn_code);
			
		$date=date('Y-m-d');
		$tax=\DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$hsn_code[0]' and start_date<='$date' and end_date>='$date' and active='Yes'");
			$prd_data['hsn_code']=$hsn_code[0];
	   if(!empty($tax))
	   {
		  $prd_data['taxgroup_id']=$tax[0]->tax_group_id; 
		   
		   $tax1=\DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id',$tax[0]->tax_group_id)->get();
		   $prd_data['display_name']=$tax1[0]->display_name;
		   return $prd_data;
		  
	   }
		else
		{
			 $prd_data['uom_code_id']=$pro_details[0]->trx_uom_id;
			 $prd_data['taxgroup_id']=0; 
			 $prd_data['display_name']=1;
			return 1;
		}
		
	}
	
	function pricelist_cust($customer_id)
	{
		$result=\DB::table('m_customers_t')->select('pricelist_id as pricelist_id')->where('customer_id',$customer_id)->get();
	
		if($result->isNotEmpty())
		{
		  return $pricelist=$result[0]->pricelist_id;
		}
		else
		{
		return 0;	
		}
		
	}

	function pricelist($customer,$product_id){
		$result=\DB::table('m_customers_t')->select('pricelist_id as pricelist_id')->where('customer_id',$customer)->get();
		//dd($result);
			if($result->isNotEmpty())
			{
			  $pricelist=$result[0]->pricelist_id;
			  $result_pri=\DB::table('i_pricelist_lines_t')->select('unit_price')->where('pricelist_hdr_id',$pricelist)->where('product_id',$product_id)->get();
				if($result_pri->isNotEmpty())
				{
					return $result_pri[0]->unit_price;
				}
				else
				{
					return '0.00';
				}
				
			}
			else
			{
				return '0.00';
				
			}
	}

	public function pricelistdetail_so($pid=null,$pricelistid)
	{
		$unitprice = $this->pricelistdetails($pid,$pricelistid);
		return $unitprice;
	}

   	public function getStatus($id=null,$type = null)
    {
          //dd("sdf");
        $soorder_id = $id;
        $soorderquery=DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$soorder_id)->get();

        if($type == "savestatus")
            $status_type= $soorderquery[0]->savestatus;
        else if($type == "statustype")
            $status_type= $soorderquery[0]->order_status_id;

        return $status_type;

    }

   	public function delete($id=null)
	{
        $del_id = $id;
        $column = array('invoice_hdr_id','so_dispatch_hdr_id');
        $table = array('s_invoice_hdr_t','s_dispatch_hdr_t');
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = DB::table($table[$i])->where($column[$i],$del_id)->get();
            //dd($query);
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }

        if($j==0)
        {
           				/**Auditlog**/
		$action = "Delete";
		$this->auditlog($del_id,"Sale Order",$action,'',"s_salesorder_hdr_t,s_salesorder_lines_t");
           	$query = DB::table('s_salesorder_hdr_t')->where('sales_hdr_id',$del_id)->delete();
            $query1 = DB::table('s_salesorder_lines_t')->where('sales_hdr_id',$del_id)->delete();

            //dd($query1);
        }
     if($j == 1)
            return 1;
        else if($j == 0)
            return 0;
    }
        
   	public function getaddress($id =null)
	{
		$SQL="SELECT m_customer_sites_t.state,m_customer_sites_t.city ,m_customer_sites_t.customer_site_name,m_customer_sites_t.pincode,m_countries_t.country_name
		,m_states_t.state_name,m_cities_t.city_name,m_customers_t.*,m_customer_sites_t.site_type,m_customer_sites_t.address,m_customer_sites_t.contact_number,m_customer_sites_t
		.customer_site_id,m_customer_sites_t.country,m_customer_sites_t.contact_person FROM m_customers_t inner join m_customer_sites_t ON
		m_customer_sites_t.customer_id=m_customers_t.customer_id left join m_countries_t on m_countries_t.country_id = m_customer_sites_t.country
		left join m_states_t on m_states_t.state_id = m_customer_sites_t.state left join m_cities_t on m_cities_t.city_id = m_customer_sites_t.city where 
		m_customers_t.customer_id =$id and m_customer_sites_t.primary_address='YES' and m_customer_sites_t.active='Yes' order by m_customer_sites_t.customer_site_id asc";
       
        $result = \DB::select($SQL);
//	dd($result);
        $output = array();
          	$output[1]='';
          	$output[0]='';
          	$output['ar_frieghtcarriers_hdr_id'] = '';
            	$output['default_payment_terms_id'] ='';
            	$output['default_payment_method_id'] = '';
            	$output['sales_person'] ='';
            	$output['ar_discount_hdr_id'] = '';
            	$output['pricelist_id'] = '';
            	$output['productid']='<option value="">-- Please Select --</option>';
        if(count($result)>0)
        { 
        	foreach($result as $key=>$value)
            {
				$output['frieghtterm_id']=$value->frieghtterm_id;
				$output['delivery_terms_id']=$value->delivery_terms_id;
            	if($value->site_type == "BILL_TO")
                {  

$out=explode(",",$value->contact_person);			
$output['contact_person']=$out[0];			
$out=explode(",",$value->contact_number);			
$output['contact_number']=$out[0];			
					if($value->pincode!=0 && $value->contact_number==''){
                        $output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name."~".$value->customer_site_id;
                    }else if($value->pincode!=0 && $value->contact_number!=''){
                        $output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number."~".$value->customer_site_id;
                      
                    }else if($value->pincode==0 && $value->contact_number!=''){
						$output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name.". Contact No:".$value->contact_number."~".$value->customer_site_id;
					}else if($value->pincode==0 && $value->contact_number==''){
						$output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."~".$value->customer_site_id;
					}
				}
                else if($value->site_type == "SHIP_TO")
                {
                	if($value->pincode!=0 && $value->contact_number==''){
                        $output[1]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name."~".$value->customer_site_id;
                    }else if($value->pincode!=0 && $value->contact_number!=''){
                        $output[1]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number."~".$value->customer_site_id;
                      
                    }else if($value->pincode==0 && $value->contact_number!=''){
						$output[1]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name.". Contact No:".$value->contact_number."~".$value->customer_site_id;
					}else if($value->pincode==0 && $value->contact_number==''){
						$output[1]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."~".$value->customer_site_id;
					}
                      
                }

                $output['ar_frieghtcarriers_hdr_id'] = $value->ar_frieghtcarriers_hdr_id;
                $output['contact_person'] = $value->contact_person;
                $output['contact_number'] = $value->contact_number;
            	$output['default_payment_terms_id'] = $value->default_payment_terms_id;
            	$output['default_payment_method_id'] = $value->default_payment_method_id;
            	$output['sales_person'] = $value->sales_person;
            	$output['ar_discount_hdr_id'] = '';
            	$output['pricelist_id'] = $value->pricelist_id;
            	$pricelist = $value->pricelist_id;

            }   
            if(isset($_GET['condition'])){
                $c=$_GET['condition']; 	
				$emp_id=session::get('emp_id');
    $con=\DB::select("SELECT menus,menus1,menus2 FROM `a_product_menu_access_t` where user_id='$emp_id' and process_id='$c'");

     if($con){
        $group=  json_decode($con[0]->menus);
		
		$cat=  json_decode($con[0]->menus1);
		$sub_cat=  json_decode($con[0]->menus2);
        $condition=" ";
     
    } else {
        $condition=" and m_products_t.product_group_id=''";
    }

if($con){
    $productid = DB::table('i_pricelist_lines_t')->groupBy('i_pricelist_lines_t.product_id')->join('m_products_t', 'm_products_t.product_id', '=', 'i_pricelist_lines_t.product_id')
                    ->where('i_pricelist_lines_t.pricelist_hdr_id',$pricelist)->whereIn('m_products_t.product_group_id',$group)->where('m_products_t.product_status','APPROVED')->where('m_products_t.active','Yes')
                    ->whereIn('m_products_t.product_category_id',$cat)->whereIn('m_products_t.product_subcategory_id',$sub_cat)->get();
					//dd($productid);
}
else{
	$productid=\DB::select("SELECT  m_products_t.concatenated_product,m_products_t.product_id FROM i_pricelist_lines_t JOIN m_products_t ON
	i_pricelist_lines_t.product_id=m_products_t.product_id WHERE i_pricelist_lines_t.pricelist_hdr_id='$pricelist' and m_products_t.product_status='APPROVED' and m_products_t.active='Yes' and i_pricelist_lines_t.active='Yes' 
	$condition GROUP BY m_products_t.product_id");
    
}
}
          //  dd($productid);
        $output['productid']='<option value="">-- Please Select --</option>';
if(isset($_GET['pid'])){
            		$pid = $_GET['pid'];
            	
        foreach ($productid as $key => $value) {
            if($value->product_id==$pid)
                $select="selected";
            else
                $select='';

            $output['productid'].="<option value=".$value->product_id." ".$select.">".$value->concatenated_product."</option>";
        }
            	
        }
            		
        }
        
        return $output;           
                
	}
	function schemesdata($id=null,$product_id=null){
		$sql =\DB::table('s_schemes_hdr_t')
			->leftjoin('s_schemes_lines_t','s_schemes_lines_t.schemes_hdr_id','=','s_schemes_hdr_t.schemes_hdr_id')
			->where('s_schemes_hdr_t.schemes_hdr_id',$id)->where('s_schemes_lines_t.product_id',$product_id)
			->select('s_schemes_lines_t.scheme_base','s_schemes_lines_t.scheme_base_value_from','s_schemes_lines_t.scheme_base_value_to','s_schemes_lines_t.schemes_type','s_schemes_lines_t.schemes_type_value','s_schemes_lines_t.schemes_lines_id')->get();
		return $sql;
	}
	
	function custaddress($id =null)
	{
	//dd($id);
        $SQL = "SELECT site.customer_id, city.city_name, state.state_name, con.country_name,site.address,site.site_type,site.customer_site_name,site.contact_number,site.pincode,site.customer_site_id,site.primary_address FROM `m_customer_sites_t` site left join m_countries_t con on ( con.country_id=site.`country` ) left join m_states_t state on ( state.state_id=site.`state`) left join m_cities_t city on( city.city_id=site.`city`) where site.customer_id='$id' and site.primary_address='Yes' and site.active='Yes' ";
        $result = \DB::select($SQL);
        //  dd(($result));
        $output = array();
        $output[0] ='';
        $output[1] ='';
        $output[2] ='';
        $output[3] ='';
        if(count($result)>0 )
        {  
            foreach($result as $key=>$value)
            {
                if($value != '')
                {
                    if($value->site_type == "BILL_TO")
                    {   
							if($value->pincode!=0 && $value->contact_number==''){
                    $output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name;
							}
					else if($value->pincode!=0 && $value->contact_number!=''){
                    $output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number;
                  
                }
			else if($value->pincode==0 && $value->contact_number!=''){
				$output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name.". Contact No:".$value->contact_number;
			}	else if($value->pincode==0 && $value->contact_number==''){
				$output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name;
			}
                   
                        $output[2] = $value->customer_site_id;
                    }
                   else if($value->site_type == "SHIP_TO")
                    {

                       	if($value->pincode!=0 && $value->contact_number==''){
                    $output[1]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name;}
					else if($value->pincode!=0 && $value->contact_number!=''){
                    $output[1]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number;
                  
                }
			else if($value->pincode==0 && $value->contact_number!=''){
				$output[1]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name.". Contact No:".$value->contact_number;
			}	else if($value->pincode==0 && $value->contact_number==''){
				$output[1]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name;
			}
						 $output[3] = $value->customer_site_id;
                    }
                   
                }
            }
            
        }
         // dd($output);
        return $output;            
                
	}
	
	function custaddresstype($id =null,$type= null)
	{
		
        $SQL = "SELECT site.customer_id, city.city_name, state.state_name, con.country_name,site.address,site.site_type,site.customer_site_name,site.contact_number,site.pincode,site.customer_site_id,site.primary_address FROM `m_customer_sites_t` site left join m_countries_t con on ( con.country_id=site.`country` ) left join m_states_t state on ( state.state_id=site.`state`) left join m_cities_t city on( city.city_id=site.`city`) where site.customer_id='$id' and site.primary_address='YES' and site.active='Yes' and site.customer_site_id='$type'  ";
        $result = \DB::select($SQL);
        $output = array();
        $output[0] ='';
                       
        if(count($result)>0 )
        {  
            foreach($result as $key=>$value)
            {
                if($value != '')
                {                         
					if($value->pincode!=0 && $value->contact_number==''){
                    	$output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name;
							}
					else if($value->pincode!=0 && $value->contact_number!=''){
                        $output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number;
                      
                    }
					else if($value->pincode==0 && $value->contact_number!=''){
						$output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name.". Contact No:".$value->contact_number;
					}	else if($value->pincode==0 && $value->contact_number==''){
						$output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name;
					}  
                }
            }
            
        }
      
        return $output;            
                
	}
        
    public function getpricelist(Request $request,$id =null)
	{
            
	    $result = DB::table('m_customers_t')->where('customer_id',$id)->get();
            if(count($result)>0)
                $price_list_id = $result[0]->pricelist_id;
            else
                $price_list_id= '';
            
            return $price_list_id;
            
	}
   
   public function soorderqtycheck($id){
		if($_GET['status']=="invoice"){
			$id_e=explode(',',$id);
                            
			$result = DB::table('s_salesorder_lines_t')->whereIn('sales_hdr_id',$id_e)->get();
			if(count($id_e)>1){
                $disres = DB::table('s_salesorder_lines_t')->select('discount_percentage','product_id',\DB::raw('COUNT(*) as `count`'))->whereIn('sales_hdr_id',$id_e)->groupBy('product_id','discount_percentage')->having('count', '>', '1')->get();
			if(count($disres)<= 0){
				return "order";
			}
                            }
			$check=0;
			foreach($result as $k=>$v){
				if($v->invoiced_qty<$v->qty){
					$check++;
				}
			}
			
			return $check;
		}
		else{
			$id_e=explode(',',$id);
			$result = DB::table('s_salesorder_lines_t')->whereIn('sales_hdr_id',$id_e)->get();
			if(count($id_e)>1){
                $disres = DB::SELECT("select count(*) as count1 from(select `discount_percentage`, `product_id`, COUNT(*) as `count11` from `s_salesorder_lines_t` where `sales_hdr_id` in ($id) group by `product_id`, `discount_percentage` ) as f group by f.product_id");
    if(count($disres)>0){
	  foreach($disres as $k=>$v){
		  if($v->count1>1){
				return "order";
		  }
	  }
			}
                            }
                            $check=0;
			foreach($result as $k=>$v){
				if($v->dispatched_qty<$v->qty){
				$check++;
				}
			}
			return $check;
		}
	}
    
    public function salesreport(){
		$this->data['sales']=$this->jcustomselect('s_salesorder_hdr_t','sales_hdr_id','sales_order_no','','');
		   return view('soorder.report',$this->data);
	}

	public function salesreportsearch($sid){
		
		$result['sales'] = DB::SELECT('SELECT 
			sohdr.sales_hdr_id ,
			sohdr.sales_order_no ,
			sohdr.source,
			sohdr.reference_number ,
			sohdr.sales_order_date,
			sohdr.created_at 
			FROM s_salesorder_hdr_t sohdr 
			LEFT JOIN s_inquiry_hdr_t soinq on (soinq.so_inquiry_hdr_id=sohdr.reference_id) AND sohdr.source="INQUIRY"
			LEFT JOIN s_quote_hdr_t soquote  on (soquote.quote_hdr_id=sohdr.reference_id)  AND sohdr.source="QUOTE"
			LEFT JOIN s_invoice_hdr_t soinv  on (soinv.reference_id=sohdr.sales_hdr_id)  AND soinv.source="SALES ORDER"
			WHERE sohdr.order_status_id="APPROVED" and sohdr.order_type_id!="LABOUR" and sohdr.sales_hdr_id='.$sid);
					$result['invoice'] =DB::SELECT('SELECT 
			soinv.invoice_hdr_id ,
			soinv.invoice_number ,
			soinv.source ,
			soinv.reference_number ,
			sodisp.dispatch_number ,
			soinv.invoice_date ,
			soinv.created_at 
			FROM s_invoice_hdr_t soinv 
			LEFT JOIN s_salesorder_hdr_t sohdr on (sohdr.sales_hdr_id=soinv.reference_id) AND soinv.source="SALES ORDER"  
			LEFT JOIN s_dispatch_hdr_t sodisp on (sodisp.reference_source_id=soinv.invoice_hdr_id) AND sodisp.dispatch_source="INVOICE"
			 where  soinv.reference_id='.$sid.'   AND soinv.source="SALES ORDER"');
		$result['dispatch'] =DB::SELECT('SELECT
			sodisp.so_dispatch_hdr_id,
			sodisp.dispatch_number,
			sodisp.dispatch_source ,
			sodisp.reference_no ,
			soinv.invoice_number,
			sodisp.dispatch_date,
			sodisp.created_at
			FROM s_dispatch_hdr_t sodisp 
			LEFT JOIN s_salesorder_hdr_t sohdr on (sohdr.sales_hdr_id=sodisp.reference_source_id) AND sodisp.dispatch_source="SALES ORDER"
			LEFT JOIN s_invoice_hdr_t soinv on (soinv.reference_source_id=sodisp.so_dispatch_hdr_id) AND soinv.source="DISPATCH"
			where sodisp.reference_source_id='.$sid.'  AND sodisp.dispatch_source="SALES ORDER"');
	
	}
  
   	public function soshipaddress($id =null)
	{
		
		 $query2 = "SELECT site.customer_id, city.city_name, state.state_name, con.country_name,site.address,site.site_type,site.customer_site_name,site.contact_number,site.pincode,site.customer_site_id FROM `m_customer_sites_t` site left join m_countries_t con on ( con.country_id=site.`country` ) left join m_states_t state on ( state.state_id=site.`state`) left join m_cities_t city on( city.city_id=site.`city`) where site.customer_site_id=".$id;
        $result2 = \DB::select($query2);
        if(count($result2)>0){
			
			if($result2[0]->contact_number!='' && $result2[0]->pincode!='0'){
            $ship_to_address = $result2[0]->customer_site_name.",".$result2[0]->address.",".$result2[0]->city_name.",".$result2[0]->state_name.",".$result2[0]->country_name.",".$result2[0]->contact_number.",".$result2[0]->pincode;
			}
			else if($result2[0]->contact_number=='' && $result2[0]->pincode=='0'){
				$ship_to_address = $result2[0]->customer_site_name.",".$result2[0]->address.",".$result2[0]->city_name.",".$result2[0]->state_name;
			}
			else if($result2[0]->contact_number=='' && $result2[0]->pincode!='0'){
				 $ship_to_address = $result2[0]->customer_site_name.",".$result2[0]->address.",".$result2[0]->city_name.",".$result2[0]->state_name.",".$result2[0]->country_name.",".$result2[0]->pincode;
			}
			else if($result2[0]->contact_number!='' && $result2[0]->pincode=='0'){
            $ship_to_address = $result2[0]->customer_site_name.",".$result2[0]->address.",".$result2[0]->city_name.",".$result2[0]->state_name.",".$result2[0]->country_name.",".$result2[0]->pincode;
			}

		}
        else{
            $ship_to_address = '';
		}
		
		return $ship_to_address;
	}

	public function sobilladdress($id =null)
    {
		 $query1 = "SELECT site.customer_id, city.city_name, state.state_name, con.country_name,site.address,site.site_type,site.customer_site_name,site.contact_number,site.pincode,site.customer_site_id FROM `m_customer_sites_t` site left join m_countries_t con on ( con.country_id=site.`country` ) left join m_states_t state on ( state.state_id=site.`state`) left join m_cities_t city on( city.city_id=site.`city`) where site.customer_site_id=".$id;
        $result1 = \DB::select($query1);
       
        if(count($result1)>0){
			if($result1[0]->contact_number!='' && $result1[0]->pincode!='0'){
            $bill_to_address = $result1[0]->customer_site_name.",".$result1[0]->address.",".$result1[0]->city_name.",".$result1[0]->state_name.",".$result1[0]->country_name.",".$result1[0]->contact_number.",".$result1[0]->pincode;
			}
			else if($result1[0]->contact_number=='' && $result1[0]->pincode=='0'){
				$bill_to_address = $result1[0]->customer_site_name.",".$result1[0]->address.",".$result1[0]->city_name.",".$result1[0]->state_name;
			}
			else if($result1[0]->contact_number=='' && $result1[0]->pincode!='0'){
				 $bill_to_address = $result1[0]->customer_site_name.",".$result1[0]->address.",".$result1[0]->city_name.",".$result1[0]->state_name.",".$result1[0]->country_name.",".$result1[0]->pincode;
			}
			else if($result1[0]->contact_number!='' && $result1[0]->pincode=='0'){
            $bill_to_address = $result1[0]->customer_site_name.",".$result1[0]->address.",".$result1[0]->city_name.",".$result1[0]->state_name.",".$result1[0]->country_name.",".$result1[0]->pincode;
			}
		}
        else{
            $bill_to_address = '';
		}
		return $bill_to_address;
		
	}
    
    public function getsoprdunitprice(Request $request,$id =null)
	{
	    $result = DB::table('m_customers_t')->where('customer_id',$id)->get();
            
            if(count($result)>0)
            {
                $price_list_id = $result[0]->pricelist_id;
                $price_list  = DB::table('i_pricelist_lines_t')->where('pricelist_hdr_id',$price_list_id)->get();
                $product_id = array();
                if(count($price_list)>0)
                {
                    foreach($price_list as $key=>$value)
                    {                          
                        $product_id[$value->product_id] = $value->unit_price; 
                    }
                }   
            }
            
        return $product_id;
	}
        

// COMMON FUNCTIONS
/*Purpose For Customer Based Product Load Function**/
  public function getcustomerpriceproduct($id=null,$pdtid=null)
    {
      if($id!='')
      {
        $productid=\DB::select("select i_pricelist_lines_t.pricelist_hdr_id,i_pricelist_lines_t.product_id,m_customers_t.customer_id from i_pricelist_lines_t left join i_pricelist_hdr_t on (i_pricelist_hdr_t.pricelist_hdr_id=i_pricelist_lines_t.pricelist_hdr_id) left join m_customers_t on (m_customers_t.pricelist_id=i_pricelist_hdr_t.pricelist_hdr_id) where m_customers_t.customer_id= $id");

	foreach($productid as $key=>$value){
             $prdoducts[] =$value->product_id;
           }

           $productsplit= implode(",",$prdoducts);
           $prd= \DB::select("select product_id,concatenated_product from m_products_t where product_id in($productsplit)");
          $this->data['product_id']='<option>-- Please Select --</option>';
          foreach($prd as $key=>$value)
          {
              $this->data['product_id'].=$this->jcustomdataselect('m_products_t','product_id','product_code|concatenated_product',$pdtid,'and product_id='.$value->product_id);
          }
		 
          return $this->data['product_id'];
      }
       else
       {
         return 0;
       }
    }


public function schemesavailable(Request $request){

$product=$request->product;
$qty=$request->qty;
$unitprice=$request->unitprice;
$schemes_type=$request->schemes_type;

$qty_based=\DB::select("select * from s_schemes_lines_t where schemes_hdr_id='$schemes_type' and product_id='$product' and scheme_base='Quantity Based' and scheme_base_value_from<='$qty' ");

//dd($qty_based);

$value_based=\DB::select("select * from s_schemes_lines_t where schemes_hdr_id='$schemes_type' and product_id='$product' and scheme_base='Price Based' and scheme_base_value_from<='$qty' and scheme_base_value_to >='$qty'");

$data=0;
if(count($qty_based)) {
    $data=array();
$data['sch_data']=$qty_based;
$data['qty']=$qty;
return $data;
}
else if(count($value_based))
{
return $value_based;
}
else
return $data;
}
/*deepika purpose: update order Status*/
public function soorderupdatestatus($id=null){
 \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $id)->update(['order_status_id' => 'CLOSED']);	
}
/* end*/

function getProduct($id=null){
        $product=array();
        $product=\DB::table('m_products_t as pdt')
    ->leftJoin('m_uom_codes_t as uom','uom.uom_code_id', '=', 'pdt.trx_uom_id')
    ->select('uom.uom_code','pdt.concatenated_product','pdt.hsn_code','pdt.product_code','pdt.product_group_id')
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
            $parts = explode(',', $product[0]->hsn_code);
            $hsn = \DB::table('f_gst_code_hdr_t')->whereIn('gst_code_hdr_id',$parts)->get()->toArray();
            $ARRAY=array_column($hsn,'classification_code');
            $hsnvalue=implode(',',$ARRAY);
           $product['concat_segment']=$product[0]->concatenated_product;
           $product['product_group_id']=$product[0]->product_group_id;
           $product['primary_uom_code']=$product[0]->uom_code;
            $product['hsn_code']=$hsnvalue;
            $product['product_code']=$product[0]->product_code;
    
    
        return $product;
        
        
        }else{
            return 0;
        }
    
    }
    
      function getGst($gst,$hsn,$po_id)
{
    $sql=array();
    $location=\Session::get('ss_defaultloc_id');
    $sql=\DB::SELECT("select * from s_salesorder_lines_t where sales_hdr_id='".$po_id."' and tax_group_id='".$gst."' and hsn_code='".$hsn."'");

    $hsn=array();
    $gst=array();
    $sub_total=0;
       foreach($sql as $key=>$value)
        {
            $ass = $value->unit_price * $value->qty;
            $dis_amt = $ass * $value->discount_percentage / 100;
            $amount = $ass - $dis_amt;
            if($this->trd != ''){
                $trade_amt = $amount * $this->trd / 100;    
            }else{
                $trade_amt = 0;
            }
            
            $amount1 = $amount - $trade_amt;
            $sub_total=$sub_total+$amount1;
        }
    
    $gst['amount']=$sub_total;
    return $gst;
}

public function conversionexchangecurrency($id=null){
        $date = date('Y-m-d');
        $comp = \Session::get('companyid');
        $currency_rate = 0;
        //$data = \DB::table('f_account_exchangerates_t')->whereDate('from_date','<=',$date)->whereDate('to_date','>=',$date)->where('from_currency_id',$id)->where('to_currency_id',37)->where('active','Yes')->where('company_id',$comp)->select('*')->get();
        $data = \DB::select("SELECT * FROM f_account_exchangerates_t WHERE from_date <= CURRENT_DATE()  AND to_date >= CURRENT_DATE() AND active ='Yes' AND from_currency_id = '".$id."'   ORDER BY account_exchangerate_id DESC LIMIT 1");
        if(count($data) > 0){
            
            $currency_rate = $data[0]->conversion_rate;
        }

        return $currency_rate;
    }
    
	
public function pricelistindex()
    {
		$this->data['pageMethod']=\Request::route()->getName();
		$emp_id=\Session::get('emp_id');
		$job_title = \DB::select("SELECT job_title FROM `hr_employee_t` WHERE employee_id='$emp_id'");
		$this->data['job_id'] = $job_title[0]->job_title;
        return view('soorder.pricelistdownload',$this->data);
    }    

	
}
