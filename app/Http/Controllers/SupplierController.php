<?php

namespace App\Http\Controllers;
use App\Supplier;
use App\Suppliersites;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Config;
use session;
use Yajra\DataTables\DataTables;


class SupplierController extends Controller {
    public function __construct() {
        $this->data = array();
        $this->model = new Supplier();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data=array(
                    'pageModule'=> 'supplier',
                    'pageUrl'	=>  url('supplier'),
                     'pageMethod'=>$this->data['pageMethod']
                    
                  );
        $this->data['urlmenu']=$this->indexs(); 
    }

    public function index(Request $request) {
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

        $this->data['opt'] = $this->jqgridselect('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name');
		$this->data['supplier']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
		 $this->data['price'] = $this->jqgridselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name','',' and price_list_type="Purchase"');
		
        $table = \DB::table('m_supplier_t')->get();
		
        $this->data['datas'] = json_encode($table);
		
		$this->data['pageMethod']=\Request::route()->getName();
         
        return view('supplier.table', $this->data);
		
    }

    
       public function gstduplicate($id) {
     $gst=\DB::select("select *  from m_supplier_sites_t where gst_number='$id'");     
    if(count($gst) > 0){
        return 1;
    }else{
        return 0;
    }       
  
    }
	
	
public function gst_value($id) {
     $state_code=\DB::select("select *  from m_states_t where state_id='$id'");     
    return $state_code[0]->state_code_no;      
     }
	public function gstvalidation($id) {
     $gst=\DB::select("select  * from  m_suppliertypes_t where suppliertype_id='$id'");     
    return $gst[0]->gst_required;      
     }
    
 public function suppliertypegst($typeid) {
     $gst=\DB::select("select gst_required  from m_suppliertypes_t where suppliertype_id='$typeid'");     
//     dd($gst);
    return $gst;      
     }   
    
    public function edit(Request $request, $id) 
    {
        
        
        $this->data['pageModule'] ='supplier';
        $this->data['pageUrl'] =url('supplier');
       
        $this->data['id'] = $id;
       
        $table = \DB::table('m_supplier_t')->where('supplier_id', $id)->get();
        $this->data['row'] = $table[0];
       //dd($table[0]);
		$supno=$this->data['row1'] = $table[0]->supplier_number;
        
        $tablelines = \DB::table('m_supplier_sites_t')->where('supplier_id', $id)->get();
        $this->data['linedata'] = $tablelines;
        if($tablelines->isNotEmpty())
		{
			$country = $tablelines[0]->country;
			$state   = $tablelines[0]->state;
			$city    = $tablelines[0]->city;
		}
		else
		{
			$country = '';
			$state   = '';
			$city    = '';
		}
        
         $this->data['row']->pan_number = $table[0]->pan_number;
         $this->data['row']->msme_status = $table[0]->msme_status;
         $this->data['row']->active = $table[0]->active;
        // $this->data['row']->authentication = $table[0]->authentication;
        $this->data['frieghtcarriers_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $table[0]->frieghtcarriers_id,' and source_type_id="Purchase"');
        $this->data['frieghtterm_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $table[0]->frieghtterm_id,' and source_type_id="Purchase"');
        $this->data['supplier_type_id'] = $this->jCombo('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name', $table[0]->supplier_type_id);
        $this->data['default_pricelist_id'] =$this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$table[0]->default_pricelist_id,' and price_list_type="Purchase"');
        $this->data['default_payment_terms_id'] = $this->jCombo('m_payment_terms_t','payment_term_id','payment_term_name',$table[0]->default_payment_terms_id);
        $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->account_structure_id);
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->tds_account_id);
        $this->data['tds_percentage'] = $this->jCombo('f_tds_slab_t','tds_percentage','tds_percentage',$table[0]->tds_percentage);
	$this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $table[0]->delivery_terms_id,' and source_type_id="Purchase"');
        $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t','insurance_term_id','insurance_term_name',$table[0]->insurance_term_id);
	$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));	
		
        $this->data['customer_id']=$this->jcustomselect('m_customers_t','customer_id','customer_name',$table[0]->customer_id," and active='Yes'"); //  dd($this->data['customer_id']);
        
        $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t','payment_method_id','payment_method_name',$table[0]->default_payment_method_id);
        $this->data['default_bank_id']=$this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name',$table[0]->default_bank_id);
        
        //$this->data['default_bank_id']=$this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name',$table[0]->default_bank_id);
      // dd($table); 
   $this->data['tcs_percentage'] = $this->jCombo('f_tcs_slab_t','tcs_percentage','tcs_percentage',$table[0]->tcs_percentage);
        $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->tcs_account_id);

        $this->data['country_id'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', $country);
        $this->data['state_id'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', $state);
        $this->data['city_id'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', $city);
        
        //dd($this->data['linedata']);
     
		if(count($this->data['linedata']) >= 1)
		{    
		foreach($this->data['linedata'] as $key=>$value)
		{ 
			$a = sprintf("%03d ",($key+1));
			$seqno=$supno.'/SS'.$a;
		    $this->data['linedata'][$key] = (object)array();	
		    $this->data['linedata'][$key]->supplier_site_number = $seqno;
			$this->data['linedata'][$key]->supplier_site_name = $value->supplier_site_name;  
			$this->data['linedata'][$key]->site_type = $value->site_type;
			$this->data['linedata'][$key]->address = $value->address;
			$this->data['linedata'][$key]->pincode = $value->pincode;
			$this->data['linedata'][$key]->contact_number = $value->contact_number;
			$this->data['linedata'][$key]->contact_person= $value->contact_person;
        $this->data['linedata'][$key]->country = $this->jCombologin('m_countries_t', 'country_id', 'country_name', $value->country);
        $this->data['linedata'][$key]->state = $this->jCombologin('m_states_t', 'state_id', 'state_name', $value->state);
        $this->data['linedata'][$key]->city= $this->jCombologin('m_cities_t', 'city_id', 'city_name', $value->city);
						$this->data['linedata'][$key]->contact_mail= $value->contact_mail;
			$this->data['linedata'][$key]->supplier_site_id = $value->supplier_site_id;
			$this->data['linedata'][$key]->gst_number = $value->gst_number; 
			$this->data['linedata'][$key]->tan_no = $value->tan_no; 
			$this->data['linedata'][$key]->primary_address = $value->primary_address; 
			$this->data['linedata'][$key]->active = $value->active; 
		}
		}
	
		
		$this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','supplier')->get(); 
	
        if(isset($_GET['status'])){
			$this->data['used_some']="readonly";
		}
                $this->data['url']="supplieredit";
                             		$this->data['return_url']=\Request::route()->getName();

                
        return view('supplier.form', $this->data);
    }

    public function create($id = null) {
        
        $this->data['pageModule'] ='supplier';
        $this->data['pageUrl'] =url('supplier');
        //dd($this->data);
        $this->data['row'] = (object) array();
        $this->data['row']->supplier_id = "";
        $this->data['row']->supplier_name = "";
        $this->data['row']->supplier_number = "";
        $this->data['row']->supplier_type_id = "";
        $this->data['row']->contact_person = "";
        $this->data['row']->contact_number = "";
        $this->data['row']->gst_no = "";
        $this->data['row']->pan_number = "";
        $this->data['row']->active = "";
        $this->data['row']->msme_status = "";
        //$this->data['row']->authentication = "";
        $this->data['row']->supplier_alternate_name = "";
        $this->data['row']->payment_terms_id = "";
        $this->data['supplier_type_id'] = $this->jCombo('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name', '');
        $this->data['frieghtterm_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name','', 'and source_type_id="Purchase"');
        $this->data['frieghtcarriers_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '',' and source_type_id="Purchase"');
        $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '',' and source_type_id="Purchase"');
        $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t','insurance_term_id','insurance_term_name','');
		
        $this->data['default_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name','',' and price_list_type="Purchase"');
        $this->data['default_payment_terms_id'] = $this->jCombo('m_payment_terms_t','payment_term_id','payment_term_name','');
       // dd($this->data['default_payment_terms_id']);
        $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t','payment_method_id','payment_method_name','');
        $this->data['tds_percentage'] = $this->jCombo('f_tds_slab_t','tds_percentage','tds_percentage','');
                       // $this->data['tds_percentage'] = $this->jCombo('f_tds_slab_t','tds_slab_id','tds_percentage','');

                        $this->data['tcs_percentage'] = $this->jCombo('f_tcs_slab_t','tcs_percentage','tcs_percentage','');
        $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');

        //dd($this->data['default_payment_method_id']);
                $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');

        $this->data['default_bank_id']=$this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','');
        $this->data['linedata'] = array();
        $this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','supplier')->get();
        $this->data['customer_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_name', ''," and active='Yes'");
        $this->data['country_id'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', '');
        $this->data['state_id'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', '');
        $this->data['city_id'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', '');
		$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
        //dd($this->data['city_id']);
             $this->data['url']="supplier";
	        
        return view('supplier.form', $this->data);
    }

    public function ChecknameData(Request $request)
        {


        $supplier_id = $_REQUEST['edit_id'];  
        if($supplier_id == '')
        {
            $group=\DB::table('m_supplier_t')->where('supplier_name',$_REQUEST['supplier_name'])->get();
        }
        else
        {
            $whereData = [['supplier_name', $_REQUEST['supplier_name']],['supplier_id', '!=', $supplier_id]];

            $group=\DB::table('m_supplier_t')->where($whereData)->get();
        }


        if(count($group)>0)
            return 1;
        else
            return 0;


    }



	
	

    public function delete(Request $request,$id=null)
        {

        $count=0;
        $queryquote = \DB::table('p_po_hdr_t')->where('supplier_id',$id)->count();
        if($queryquote >=1)
        {
         $count++;
        }
        if($count <= 0)
        {
            Supplier::destroy($id);
            $query = \DB::table('m_supplier_sites_t')->where('supplier_id',$id)->delete();
            
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

  /*Karthigaa purpose for delete function*/
	public function delete_old(Request $request,$id=null){
        	Supplier::destroy($id);
                \DB::table('m_supplier_sites_t')->where('supplier_id', $id)->delete();
                return redirect('supplier');
	}

        
   public function show(request $request,$id=null){
        if(isset($id))
        { 
			//        $this->data['tcs_percentage'] = $this->jCombo('f_tcs_slab_t','tcs_percentage','tcs_percentage','');

          $supplier=DB::table('m_supplier_t')->select('m_supplier_t.*','m_suppliertypes_t.suppliertype_name','i_pricelist_hdr_t.pricelist_name',
                  'm_payment_methods_t.payment_method_name','f_bank_account_hdr_t.bank_name','m_payment_terms_t.payment_term_name',
                  'm_customers_t.customer_name','m_delivery_terms_t.delivery_term_name','m_insurance_terms_t.insurance_term_name','f_tcs_slab_t.tcs_percentage as tcsper',
                  'supp_acc.concatenated_segments as supplier_account','tds_acc.concatenated_segments as tds_account','tcs_acc.concatenated_segments as tcs_account','tb_users.username','m_frieghtcarriers_hdr_t.carrier_name','m_frieghtterms_t.fob_point_name as freight_term')
                  ->leftjoin('m_suppliertypes_t','m_suppliertypes_t.suppliertype_id','=','m_supplier_t.supplier_type_id')
                  ->leftjoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','m_supplier_t.default_pricelist_id')
                  ->leftjoin('m_payment_methods_t','m_payment_methods_t.payment_method_id','=','m_supplier_t.default_payment_method_id')
                  ->leftjoin('m_customers_t','m_customers_t.customer_id','=','m_supplier_t.customer_id')
		  ->leftjoin('f_bank_account_hdr_t','f_bank_account_hdr_t.bank_account_hdr_id','=','m_supplier_t.default_bank_id')
                  ->leftjoin('m_payment_terms_t','m_payment_terms_t.payment_term_id','=','m_supplier_t.default_payment_terms_id')
                  ->leftjoin('m_delivery_terms_t','m_delivery_terms_t.delivery_terms_id','=','m_supplier_t.delivery_terms_id')
                  ->leftjoin('m_insurance_terms_t','m_insurance_terms_t.insurance_term_id','=','m_supplier_t.insurance_term_id')
                  ->leftjoin('f_account_structure_t as supp_acc','supp_acc.f_account_structure_id','=','m_supplier_t.account_structure_id')
                  ->leftjoin('f_account_structure_t as tds_acc','tds_acc.f_account_structure_id','=','m_supplier_t.tds_account_id')
                  ->leftjoin('f_account_structure_t as tcs_acc','tcs_acc.f_account_structure_id','=','m_supplier_t.tcs_account_id')
                  ->leftjoin('tb_users','tb_users.id','=','m_supplier_t.created_by')
                  ->leftjoin('f_tcs_slab_t','f_tcs_slab_t.tcs_percentage','=','m_supplier_t.tcs_percentage')
                  ->leftjoin('m_frieghtterms_t','m_frieghtterms_t.frieghtterm_id','=','m_supplier_t.frieghtterm_id')
                  ->leftjoin('m_frieghtcarriers_hdr_t','m_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id','=','m_supplier_t.frieghtcarriers_id')
                  ->where('supplier_id',$id)->get();
//          dd($supplier);
          $this->data['supplier_number']=$supplier[0]->supplier_number;
          $this->data['tcs_applicable']=$supplier[0]->tcs_applicable;
          $this->data['tcsper']=$supplier[0]->tcsper;
          $this->data['supplier_name']=$supplier[0]->supplier_name;
          $this->data['tcs_account']=$supplier[0]->tcs_account;
          $this->data['supplier_alternate_name']=$supplier[0]->supplier_alternate_name;
          $this->data['suppliertype_name']=$supplier[0]->suppliertype_name;
          $this->data['pricelist_name']=$supplier[0]->pricelist_name;
          $this->data['payment_term_name']=$supplier[0]->payment_term_name;
          $this->data['payment_method_name']=$supplier[0]->payment_method_name;
          $this->data['delivery_term_name']=$supplier[0]->delivery_term_name;
          $this->data['insurance_term_name']=$supplier[0]->insurance_term_name;
          $this->data['supplier_account']=$supplier[0]->supplier_account;
          $this->data['tds_account']=$supplier[0]->tds_account;
          $this->data['contact_person']=$supplier[0]->contact_person;
          $this->data['contact_number']=$supplier[0]->contact_number;
          $this->data['gst_no']=$supplier[0]->gst_no;
            $this->data['active']=$supplier[0]->active;
            $this->data['pan_number']=$supplier[0]->pan_number;
            $this->data['supplier_status']=$supplier[0]->supplier_status;
            $this->data['customer_name']=$supplier[0]->customer_name;
            $this->data['bank_name']=$supplier[0]->bank_name;
            $this->data['tds_applicable']=$supplier[0]->tds_applicable;
            $this->data['tds_percentage']=$supplier[0]->tds_percentage;
             $this->data['created_by']=$supplier[0]->username;
             $this->data['carrier_name']=$supplier[0]->carrier_name;
             $this->data['freight_term']=$supplier[0]->freight_term;
             


             $vlinesdata = \DB::table('m_supplier_sites_t')
                                            ->leftjoin('m_countries_t','m_countries_t.country_id','=','m_supplier_sites_t.country')
                                            ->leftjoin('m_states_t','m_states_t.state_id','=','m_supplier_sites_t.state')
                                            ->leftjoin('m_cities_t','m_cities_t.city_id','=','m_supplier_sites_t.city')
                                            ->where('m_supplier_sites_t.supplier_id',$id)->get();
             $this->data['vlinesdata']=$vlinesdata;
             $this->data['country_name']=$vlinesdata[0]->country_name;
             $this->data['state_name']=$vlinesdata[0]->state_name;
             $this->data['city_name']=$vlinesdata[0]->city_name;
              $this->data['gst_number']=$vlinesdata[0]->gst_number;
              $this->data['pincode']=$vlinesdata[0]->pincode;
              $this->data['primary_address']=$vlinesdata[0]->primary_address;
              $this->data['site_type']=$vlinesdata[0]->site_type;
              $this->data['active']=$vlinesdata[0]->active;
             
            //dd($this->data);
            return view('supplier.view',$this->data);
        }
    }
	
    public function getsupplierData() {
        $wh = '';
        $wh1 = '';


		$loc="1";
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');


  
    $groupname=\Session::get('groupname');
      if($groupname=='1' || $groupname=='Admin'){
    $wh.=' and  m_supplier_t.company_id='.$compy;  
    }else{
      $wh.=' and m_supplier_t.company_id='.$compy.' and m_supplier_t.location_id='.$loc;    
    }
    
       $app_id=\Session::get('id');

       if ($_GET['pagemethod']=='supplierapproval') {
			$wh.=" and m_supplier_t.savestatus='INITIATED' and json_contains(m_supplier_t.approver_id ,'".$app_id."')=1 ";
		}
        
        
        $SQL = "SELECT m_supplier_t.*,f_account_structure_t.f_account_structure_id,f_account_structure_t.concatenated_segments,
    m_suppliertypes_t.suppliertype_name,
    i_pricelist_hdr_t.pricelist_name,
	m_payment_methods_t.payment_method_id,m_payment_methods_t.payment_method_name,
	tb_users.username,m_payment_terms_t.payment_term_name,m_delivery_terms_t.delivery_term_name,m_insurance_terms_t.insurance_term_name,
	f_bank_account_hdr_t.bank_name
    FROM
    m_supplier_t
    LEFT JOIN m_suppliertypes_t ON m_suppliertypes_t.suppliertype_id = m_supplier_t.supplier_type_id
    left join i_pricelist_hdr_t on i_pricelist_hdr_t.pricelist_hdr_id= m_supplier_t.default_pricelist_id
	left join  f_account_structure_t on f_account_structure_t.f_account_structure_id = m_supplier_t.account_structure_id
	left join  m_payment_methods_t on m_payment_methods_t.payment_method_id = m_supplier_t.default_payment_method_id
	left join  tb_users on tb_users.id = m_supplier_t.created_by
	left join  m_payment_terms_t on m_payment_terms_t.payment_term_id = m_supplier_t.default_payment_terms_id
	left join  m_delivery_terms_t on m_delivery_terms_t.delivery_terms_id = m_supplier_t.delivery_terms_id
	left join  m_insurance_terms_t on m_insurance_terms_t.insurance_term_id = m_supplier_t.insurance_term_id
	left join  f_bank_account_hdr_t on f_bank_account_hdr_t.bank_account_hdr_id = m_supplier_t.default_bank_id
  where 1=1  $wh ORDER BY m_supplier_t.supplier_id DESC";

        $result = \DB::select($SQL);

		return DataTables::of($result)->make(true);
    }

    public function resave(Request $request)
    { 
		
          // dd("hii");
        $supplier = new Supplier();
        $this->modelname = new Supplier();  
        $Suppliersites = new Suppliersites(); 
        $this->modelline = new Suppliersites();


        $primary = self::findPrimarykey('m_supplier_t');      
        $primaryline = self::findPrimarykey('m_supplier_sites_t'); 
		
        $save_status = $_POST['savestatus'];
        $supplier->supplier_id = $_POST['supplier_id'];  
		$supplier->last_updated_by=\Session::get('id');
		$upid=\Session::get('id');
        $supplier->supplier_name = $_POST['supplier_name']; 
        $supplier->savestatus = $_POST['savestatus']; 
        //dd($_POST['savestatus']);
          if($_POST['savestatus']=='INITIATED')
            {
        $t=0;
              	$approverid= $this->Approvaldatacheck('supplier',$t);
              //	dd($approverid);
	        if($approverid == "0" ){
			
	        	 $supplier->approver_id=\Session::get('id');
	        	 $supplier->savestatus = "APPROVED";
	        }else{
				
	        	$supplier->approver_id=$approverid;

	        }  
            }
        //dd($supplier);
        $seqno=$this->Seqnoe('S','m_supplier_t',"",'supplier_count');
        $supplier->supplier_number= $seqno[0]; 
        $supplier->supplier_count= $seqno[1]; 
		
        $supplier->supplier_type_id = $_POST['supplier_type_id']; 
        $supplier->default_payment_method_id = $_POST['default_payment_method_id'];
        $supplier->default_payment_terms_id = $_POST['default_payment_terms_id'];
        $supplier->default_pricelist_id = $_POST['default_pricelist_id'];
//        $supplier->contact_person = $_POST['contact_person'];
//        $supplier->contact_number = $_POST['contact_number'];
        $supplier->supplier_alternate_name = $_POST['supplier_alternate_name'];
        $supplier->pan_number=$_POST['pan_number'];
        $supplier->msme_status=$_POST['msme_status'];
        //attachment - vignesh m
            if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $filename = time() . '_' . $file->getClientOriginalName();
        
        if (empty($_POST['supplier_id']) || $_POST['supplier_id'] == "0") {
            $supplier->save();
            $_POST['supplier_id'] = $supplier->supplier_id;  // Retrieve the new ID
        } else {

            $supplier = Supplier::find($_POST['supplier_id']);
        }
        
        // Define the upload path based on supplier_id
        $relativePath = 'uploads/msme_supplier/' . $_POST['supplier_id'];
        $destinationPath = public_path($relativePath);
        
        // Create directory if it doesn't exist
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        
        // Move the file to the destination folder
        $file->move($destinationPath, $filename);
        
        // Update the attachment field and save to the database
        $supplier->attachment = $filename;
        $supplier->save();  // This ensures the attachment filename is stored in DB
    } else if (!empty($_POST['supplier_id'])) {
        $supplier = Supplier::find($_POST['supplier_id']);
    }
    
        $supplier->default_bank_id=$_POST['default_bank_id'];
		
        //$supplier->supplier_status=$_POST['supplier_status'];
        $supplier->customer_id=$_POST['customer_id']; 
        $supplier->customer_name=$_POST['customer_name']; 
        $supplier->account_structure_id=$_POST['account_structure_id']; 
        $supplier->tds_account_id=$_POST['tds_account_id']; 
        $supplier->tcs_account_id=$_POST['tcs_account_id']; 
        $supplier->delivery_terms_id=$_POST['delivery_terms_id']; 
        $supplier->insurance_term_id=$_POST['insurance_term_id']; 
        $supplier->tds_applicable=$_POST['tds_applicable'];
        $supplier->tcs_applicable=$_POST['tcs_applicable'];
        $supplier->tds_percentage=$_POST['tds_percentage'];
        $supplier->tcs_percentage=$_POST['tcs_percentage'];
        $supplier->active=$_POST['active'];
        $supplier->created_by=$_POST['created_by'];
        $supplier->organization_id=\Session::get('organization');
        $supplier->location_id=\Session::get('location');
        $supplier->company_id=\Session::get('companyid');
        $supplier->frieghtterm_id=$_POST['frieghtterm_id'];
        $supplier->frieghtcarriers_id=$_POST['frieghtcarriers_id'];
   //dd($supplier);
            /**Auditlog**/
        $id = $this->insertData($this->modelname, $primary, $supplier, $_POST['supplier_id'],"supplier"); 
		if(($_POST['supplier_id']!="0") || ($_POST['supplier_id']!=""))
		{
			$sql=\DB::table('m_supplier_t')->where('supplier_id',$_POST['supplier_id'])->update(['last_updated_by' => $upid]);
                        
		}
		
		if($_POST['savestatus']=='INITIATED')
        {
            $dat = \DB::select("SELECT m_supplier_t.supplier_number, m_supplier_t.supplier_name,m_supplier_t.savestatus,m_supplier_t.msme_status, m_suppliertypes_t.suppliertype_name,i_pricelist_hdr_t.pricelist_name,f_account_structure_t.concatenated_segments,tb_users.first_name, tb_users.email FROM m_supplier_t LEFT JOIN m_suppliertypes_t ON m_supplier_t.supplier_type_id = m_suppliertypes_t.suppliertype_id LEFT JOIN i_pricelist_hdr_t ON m_supplier_t.default_pricelist_id = i_pricelist_hdr_t.pricelist_hdr_id LEFT JOIN f_account_structure_t ON m_supplier_t.account_structure_id = f_account_structure_t.f_account_structure_id LEFT JOIN tb_users ON m_supplier_t.created_by = tb_users.id WHERE m_supplier_t.supplier_id='".$id."'");
                            
            $uid = \Session::get('id');                
            $from_user = \DB::select("select first_name, email from tb_users where id= $uid");
                            
                            $pur['supplier_number']=$dat[0]->supplier_number;
                            $pur['supplier_name']=$dat[0]->supplier_name;
                            $pur['msme_status']=$dat[0]->msme_status;
                			$pur['suppliertype_name'] = $dat[0]->suppliertype_name;
                			$pur['pricelist_name'] = $dat[0]->pricelist_name;
                			$pur['concatenated_segments'] = $dat[0]->concatenated_segments;
                			$pur['user_clear']=$from_user[0]->first_name;
                            $pur['savestatus']=$dat[0]->savestatus;
                            
                    $po_num_sub = $dat[0]->supplier_name;
                    Session::put('po_num_sub', $po_num_sub);
                    $po_email = $from_user[0]->email;
				    Session::put('po_email', $po_email);
				if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));
                    //dd('user_email');
                 } 
            
            if(\Session::get('user_email')!=''){         
                    //dd($prd);
            
             $to_mail_id = "expenses@jrkresearch.com";  
            
             \Mail::send('supplier.mail',$pur,function($message)
                            use($to_mail_id)
                            {
                            //dd($to_mail_id);    
                            $message->to($to_mail_id);
                            $message->cc('aspire@jrkresearch.com');
                            
                            if(!empty(Session::get('po_email'))){
                            $po_email = Session::get('po_email');
                            }else{
                            $po_email = \Session::get('user_email');
                            }
                            $message->from($po_email);
                            if(!empty(Session::get('po_num_sub'))){
                            $po_num_sub = Session::get('po_num_sub');
                            }else{
                            $po_num_sub = " ";
                            }
                            
                            $message->subject($po_num_sub." - SUPPLIER INITIATED");                    
                            });
                }
                
        }else if($_POST['savestatus']=='APPROVED')
        {
            
                            $dat = \DB::select("SELECT m_supplier_t.supplier_number, m_supplier_t.supplier_name,m_supplier_t.savestatus,m_supplier_t.msme_status, m_suppliertypes_t.suppliertype_name,i_pricelist_hdr_t.pricelist_name,f_account_structure_t.concatenated_segments,tb_users.first_name, tb_users.email FROM m_supplier_t LEFT JOIN m_suppliertypes_t ON m_supplier_t.supplier_type_id = m_suppliertypes_t.suppliertype_id LEFT JOIN i_pricelist_hdr_t ON m_supplier_t.default_pricelist_id = i_pricelist_hdr_t.pricelist_hdr_id LEFT JOIN f_account_structure_t ON m_supplier_t.account_structure_id = f_account_structure_t.f_account_structure_id LEFT JOIN tb_users ON m_supplier_t.last_updated_by = tb_users.id WHERE m_supplier_t.supplier_id='".$id."'");
                            
                            $to_mail = \DB::select("SELECT * FROM m_supplier_t LEFT JOIN tb_users ON m_supplier_t.created_by = tb_users.id WHERE m_supplier_t.supplier_id='".$id."'");
                            
                            $pur['supplier_number']=$dat[0]->supplier_number;
                            $pur['supplier_name']=$dat[0]->supplier_name;
                            $pur['msme_status']=$dat[0]->msme_status;
                			$pur['suppliertype_name'] = $dat[0]->suppliertype_name;
                			$pur['pricelist_name'] = $dat[0]->pricelist_name;
                			$pur['concatenated_segments'] = $dat[0]->concatenated_segments;
                			$pur['user_clear']=$dat[0]->first_name;
                            $pur['savestatus']=$dat[0]->savestatus;
                            
                            $po_num_sub = $dat[0]->supplier_name;
                    Session::put('po_num_sub', $po_num_sub);
                    $po_email = $dat[0]->email;
				    Session::put('po_email', $po_email);
				if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));
                    //dd('user_email');
                 } 
            
            if(\Session::get('user_email')!=''){         
                    //dd($prd);
            
             //$to_mail_id = "uma_p@jrkresearch.com";  
             $to_mail_id = $to_mail[0]->email;
             
             if(isset($to_mail_id)){
                 $to_mail_id = $to_mail[0]->email;
             }else{
                $to_mail_id = "aspire@jrkresearch.com";  
             }
            
             \Mail::send('supplier.mail',$pur,function($message)
                            use($to_mail_id)
                            {
                            //dd($to_mail_id);    
                            $message->to($to_mail_id);
                            $message->cc('aspire@jrkresearch.com');
                            
                            if(!empty(Session::get('po_email'))){
                            $po_email = Session::get('po_email');
                            }else{
                            $po_email = \Session::get('user_email');
                            }
                            $message->from($po_email);
                            if(!empty(Session::get('po_num_sub'))){
                            $po_num_sub = Session::get('po_num_sub');
                            }else{
                            $po_num_sub = " ";
                            }
                            
                            $message->subject($po_num_sub." - SUPPLIER APPROVED");                    
                            });
                }
        }
		
		
        /********* LineItems Save ********** */
        
            $oldid = \DB::table('m_supplier_sites_t')->where('supplier_id', $id)->get(); 
                     
        if ($oldid->isEmpty()) { 
            for ($i = 0; $i < count($_POST['counter']); $i++) {
                $data['supplier_id'] = $id; 
                $data['supplier_site_id'] = $_POST['bulk_supplier_site_id'][$i] ? $_POST['bulk_supplier_site_id'][$i] : 0;
                $data['supplier_site_number'] = $_POST['bulk_supplier_site_number'][$i];
                $data['supplier_site_name'] = $_POST['bulk_supplier_site_name'][$i];
                $data['address'] = $_POST['bulk_address'][$i];
                $data['country'] = $_POST['bulk_country'][$i];
                $data['state'] = $_POST['bulk_state'][$i];
                $data['city'] = $_POST['bulk_city'][$i];
                $data['pincode'] = $_POST['bulk_pincode'][$i];
                $data['contact_person'] = $_POST['bulk_contact_person'][$i];
                $data['contact_number'] = $_POST['bulk_contact_number'][$i];  
                $data['contact_mail'] = $_POST['bulk_contact_mail'][$i];  
                $data['gst_number'] = $_POST['bulk_gst_number'][$i];  
                $data['tan_no'] = $_POST['bulk_tan_no'][$i];  
                $data['primary_address'] = $_POST['bulk_primary_address'][$i];  
                $data['active'] = $_POST['bulk_active'][$i];  
                $data['last_updated_by'] = \Session::get('id');  
                  $data['organization_id']=\Session::get('organization');
                  $data['location_id']=\Session::get('location');
                  $data['company_id']=\Session::get('companyid');
				
                \DB::table('m_supplier_sites_t')->insert($data);
            }                    
                  return response()->json(array('status' => 'success', 'message' =>$supplier->supplier_number.  'Supplier '.$save_status.' Successfully','id'=>$id));
        }
        
      else 
      { 
            $existingId = array();
            foreach ($oldid as $key => $value) {
                $oldIds[] = $value->$primaryline;
            }
          

            foreach ($_POST['bulk_' . $primaryline] as $val) {
                $newIds[] = $val;
            }
            $existingId = array_replace($newIds, $oldIds);
            $oldcount = count($oldIds);
            $newcount = count($newIds);
            if ($oldcount <= $newcount) {
                for ($i = 0; $i < $newcount; $i++) {
            //dd($_POST['bulk_tan_no'][$i]);
                    $data['supplier_id'] = $id;
                    $data['supplier_site_id'] = $_POST['bulk_supplier_site_id'][$i] ? $_POST['bulk_supplier_site_id'][$i] : 0;
                    $data['supplier_site_number'] = $_POST['bulk_supplier_site_number'][$i];
                    $data['supplier_site_name'] = $_POST['bulk_supplier_site_name'][$i];
                    $data['address'] = $_POST['bulk_address'][$i];
                    $data['country'] = $_POST['bulk_country'][$i];
                    $data['state'] = $_POST['bulk_state'][$i];
                    $data['city'] = $_POST['bulk_city'][$i];
                    $data['pincode'] = $_POST['bulk_pincode'][$i];
                    $data['contact_person'] = $_POST['bulk_contact_person'][$i];
                    $data['contact_number'] = $_POST['bulk_contact_number'][$i];
                    $data['contact_mail'] = $_POST['bulk_contact_mail'][$i];  
                    $data['gst_number'] = $_POST['bulk_gst_number'][$i];
					 $data['tan_no'] = $_POST['bulk_tan_no'][$i];  
                     $data['primary_address'] = $_POST['bulk_primary_address'][$i];  
                     $data['active'] = $_POST['bulk_active'][$i];  
                  $data['organization_id']=\Session::get('organization');
                  $data['location_id']=\Session::get('location');
                  $data['company_id']=\Session::get('companyid');
                  $data['last_updated_by'] = \Session::get('id');
                   // dd($existingId[$i]);
                    if ($data['supplier_site_id'] = $existingId[$i]) {
                        
                        $this->modelline::find($data['supplier_site_id'])->update($data);
                    } else {
                        \DB::table('m_supplier_sites_t')->insert($data);
                    }
                }
            } else {
                $arraydiff = array_diff($oldIds, $newIds);
                foreach ($arraydiff as $key) {
                    if (($key = array_search($key, $oldIds)) !== false) {
                        unset($oldIds[$key]);
                    }
                }

                foreach ($arraydiff as $val) {
                    \DB::table('m_supplier_sites_t')->where('supplier_site_id', $val)->delete();
                }
                for ($i = 0; $i < count($oldIds); $i++) {
                    $data['supplier_id'] = $id;
                    $data['supplier_site_id'] = $_POST['bulk_supplier_site_id'][$i] ? $_POST['bulk_supplier_site_id'][$i] : 0;
                    $data['supplier_site_number'] = $_POST['bulk_supplier_site_number'][$i];
                    $data['supplier_site_name'] = $_POST['bulk_supplier_site_name'][$i];
                    $data['address'] = $_POST['bulk_address'][$i];
                    $data['country'] = $_POST['bulk_country'][$i];
                    $data['state'] = $_POST['bulk_state'][$i];
                    $data['city'] = $_POST['bulk_city'][$i];
                    $data['pincode'] = $_POST['bulk_pincode'][$i];
                    $data['contact_person'] = $_POST['bulk_contact_person'][$i];
                    $data['contact_number'] = $_POST['bulk_contact_number'][$i];
                    $data['contact_mail'] = $_POST['bulk_contact_mail'][$i];  
                    $data['gst_number'] = $_POST['bulk_gst_number'][$i];
                    $data['tan_no'] = $_POST['bulk_tan_no'][$i];  
                    $data['primary_address'] = $_POST['bulk_primary_address'][$i];  
                    $data['active'] = $_POST['bulk_active'][$i];  
                    $data['organization_id']=\Session::get('organization');
                    $data['location_id']=\Session::get('location');
                    $data['company_id']=\Session::get('companyid');
                    $data['last_updated_by'] = \Session::get('id');
                    
                    $this->modelline::find($data['supplier_site_id'])->update($data);
                
                }
            }
                  return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id'=>$id));
        }
        /**************************** LineItems Save End **************************** */

        $table = \DB::table('m_supplier_t')->get();
        $this->data['datas'] = json_encode($table); //dd($this->data['datas']);

        
       // return redirect('supplier')->with('success', 'your data Updated successfully');
    }

    function findPrimarykey($table) {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }
	
	function customerdetailsforsupplier($id = null) {

                $customer_data=array();

                                    $org=\Session::get('organization');
                                    $loc=\Session::get('location');
                                    $com=\Session::get('companyid');
                $hdr_data =\DB::select("SELECT * from m_customers_t where customer_id=$id ");
                $line_data = \DB::select("SELECT * from m_customer_sites_t where customer_id=$id and primary_address='YES' and active='Yes' ");
                $customer_data['hdr_data']=$hdr_data;
                $customer_data['line_data']=$line_data;
                return $customer_data;
                      

                }
	public function suppliernamechk($supplierid=null){
	
		$yes = 0;
		if($supplierid!=""){
			$tablesCheck=[];
			$tableNew['table_name']="p_quotation_hdr_t";
			$tableNew['column_name']="supplier_id";
			$tableNew['value']=$supplierid;
			$tablesCheck[]=$tableNew;
                        
                        $tableNew['table_name']="p_po_hdr_t";
			$tableNew['column_name']="supplier_id";
			$tableNew['value']=$supplierid;
			$tablesCheck[]=$tableNew;
                        
                        $tableNew['table_name']="p_enquiry_hdr_t";
			$tableNew['column_name']="supplier_id";
			$tableNew['value']=$supplierid;
			$tablesCheck[]=$tableNew;
                        

			foreach($tablesCheck as $tableToCheck){
				if($yes!=1){
					$frieght = \DB::select("select ".$tableToCheck['column_name']." from ".$tableToCheck['table_name']." where ".$tableToCheck['column_name']."=".$tableToCheck['value']);
					
					if(count($frieght)>0){
						$yes = 1;
					}
                                      
				}
			}
		}
                
		return $yes;
	}
	
	
	
	
	

}
