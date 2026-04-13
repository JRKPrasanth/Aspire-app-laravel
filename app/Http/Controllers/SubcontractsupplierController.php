<?php

namespace App\Http\Controllers;

use App\Subcontractsupplier;
use App\Subcontractsites;
use App\Supplieroverdue;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;


class SubcontractsupplierController extends Controller
{
     public function __construct() {
        $this->data = array();
        $this->model = new Subcontractsupplier();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data=array(
                    'pageModule'=> 'subcontractsupplier',
                    'pageUrl'	=>  url('subcontractsupplier'),
                     'pageMethod'=>$this->data['pageMethod']
                    
                  );
        $this->data['urlmenu']=$this->indexs(); 
    }

    /*Jqgrid Function*/
     public function getsubcontractsupplierData() {
        $wh = '';
        if ($_GET['_search'] == 'true') {
            $search_tables=array('m_suppliertypes_t','i_pricelist_hdr_t');
            $wh = $this->jqgridsearch('m_subcontract_supplier_t', $_GET['filters'],$search_tables);
        }

        $comp=\Session::get('companyid');
        $loc=\Session::get('location');
        $groupname=\Session::get('groupname');
        if($groupname=="Superadmin" || $groupname=="Admin")
        {
            $wh.="and m_subcontract_supplier_t.company_id=$comp";
        }
        else{
            $wh.="and m_subcontract_supplier_t.company_id=$comp  and m_subcontract_supplier_t.location_id=$loc";
        }
    
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(subcontract_supplier_id) AS count FROM m_subcontract_supplier_t LEFT JOIN m_suppliertypes_t ON m_suppliertypes_t.suppliertype_id = m_subcontract_supplier_t.supplier_type_id left join i_pricelist_hdr_t on i_pricelist_hdr_t.pricelist_hdr_id= m_subcontract_supplier_t.default_pricelist_id where 1=1 $wh");
        $count = $result[0]->count;
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;
        
        
        
        $SQL = "SELECT
    m_subcontract_supplier_t.subcontract_supplier_id,
    m_subcontract_supplier_t.subcontract_name,
    m_subcontract_supplier_t.subcontract_number,
    m_subcontract_supplier_t.gst_no,
    m_subcontract_supplier_t.supplier_type_id,
    m_suppliertypes_t.suppliertype_name,
    i_pricelist_hdr_t.pricelist_name
    FROM
    m_subcontract_supplier_t
    LEFT JOIN m_suppliertypes_t ON m_suppliertypes_t.suppliertype_id = m_subcontract_supplier_t.supplier_type_id
    left join i_pricelist_hdr_t on i_pricelist_hdr_t.pricelist_hdr_id= m_subcontract_supplier_t.default_pricelist_id
  where 1=1  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		 
		 $download_SQL = "SELECT
    m_subcontract_supplier_t.subcontract_supplier_id,
    m_subcontract_supplier_t.subcontract_name,
    m_subcontract_supplier_t.subcontract_number,
    m_subcontract_supplier_t.gst_no,
    m_subcontract_supplier_t.supplier_type_id,
    m_suppliertypes_t.suppliertype_name,
    i_pricelist_hdr_t.pricelist_name
    FROM
    m_subcontract_supplier_t
    LEFT JOIN m_suppliertypes_t ON m_suppliertypes_t.suppliertype_id = m_subcontract_supplier_t.supplier_type_id
    left join i_pricelist_hdr_t on i_pricelist_hdr_t.pricelist_hdr_id= m_subcontract_supplier_t.default_pricelist_id
  where 1=1  $wh ORDER BY $sidx $sord";
		 
		 $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }

//       dd($SQL);
        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }
    /*End*/

    /*Index Function*/
    public function index() {
        $this->data['opt'] = $this->jqgridselect('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name');
	$this->data['supplier']=$this->jqgridselect('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_name');
	$this->data['price'] = $this->jqgridselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name','',' and price_list_type="Purchase"');
        $table = \DB::table('m_subcontract_supplier_t')->get();
        $this->data['datas'] = json_encode($table);
        $this->data['pageMethod']='supplier'; 
         
        return view('subcontractsupplier.table', $this->data);
    }
    /*END*/
           public function gstduplicate($id) {
     $gst=\DB::select("select *  from m_subcontract_sites_t where gst_number='$id'");     
    if(count($gst) > 0){
        return 1;
    }else{
        return 0;
    }       
        
         
        
    }

    /*Create Function*/
      public function create($id = null) {
        
        $this->data = array('pageModule' => 'supplier', 'pageUrl' => url('supplier'));
        //dd($this->data);
        $this->data['row'] = (object) array();
        $this->data['row']->subcontract_supplier_id = "";
        $this->data['row']->subcontract_name = "";
        $this->data['row']->subcontract_number = "";
        $this->data['row']->supplier_type_id = "";
        $this->data['row']->contact_person = "";
        $this->data['row']->contact_number = "";
        $this->data['row']->gst_no = "";
        $this->data['row']->pan_number = "";
        //$this->data['row']->authentication = "";
        $this->data['row']->subcontract_alternate_name = "";
        $this->data['row']->payment_terms_id = "";
        $this->data['supplier_type_id'] = $this->jCombo('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name', '');
        $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '',' and source_type_id="Purchase"');
        $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t','insurance_term_id','insurance_term_name','');
    
        $this->data['default_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name','',' and price_list_type="Purchase"');
        $this->data['default_payment_terms_id'] = $this->jCombo('m_payment_terms_t','payment_term_id','payment_term_name','');
       // dd($this->data['default_payment_terms_id']);
        $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t','payment_method_id','payment_method_name','');
        //dd($this->data['default_payment_method_id']);
                $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                $this->data['tds_percentage'] = $this->jCombo('f_tds_slab_t','tds_slab_id','tds_percentage','');
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');

        $this->data['default_bank_id']=$this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','');
        $this->data['linedata'] = array();
        $this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','supplier')->get();
        $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_name', '');
        $this->data['country_id'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', '');
        $this->data['state_id'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', '');
        $this->data['city_id'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', '');
    $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
        //dd($this->data['city_id']);
            
   $this->data['tcs_percentage'] = $this->jCombo('f_tcs_slab_t','tcs_percentage','tcs_percentage','');
        $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');

                
        return view('subcontractsupplier.form', $this->data);
    }
    /*End*/

    /*Edit Function*/
     public function edit(Request $request, $id) 
    {
        
        
        $this->data = array('pageModule' => 'supplier', 'pageUrl' => url('supplier'));
        $this->data['id'] = $id;
       
        $table = \DB::table('m_subcontract_supplier_t')->where('subcontract_supplier_id', $id)->get();
        $this->data['row'] = $table[0];
           $this->data['tcs_percentage'] = $this->jCombo('f_tcs_slab_t','tcs_percentage','tcs_percentage',$table[0]->tcs_percentage);
        $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->tcs_account_id);

       //dd($table[0]);
    $supno=$this->data['row1'] = $table[0]->subcontract_number;
        
        $tablelines = \DB::table('m_subcontract_sites_t')->where('subcontract_supplier_id', $id)->get();
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
        // $this->data['row']->authentication = $table[0]->authentication;
        
        $this->data['supplier_type_id'] = $this->jCombo('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name', $table[0]->supplier_type_id);
        $this->data['default_pricelist_id'] =$this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$table[0]->default_pricelist_id,' and price_list_type="Purchase"');
        $this->data['default_payment_terms_id'] = $this->jCombo('m_payment_terms_t','payment_term_id','payment_term_name',$table[0]->default_payment_terms_id);
        $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->account_structure_id);
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->tds_account_id);
        $this->data['tds_percentage'] = $this->jCombo('f_tds_slab_t','tds_slab_id','tds_percentage',$table[0]->tds_percentage);
  $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $table[0]->delivery_terms_id,' and source_type_id="Purchase"');
        $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t','insurance_term_id','insurance_term_name',$table[0]->insurance_term_id);
  $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));  
    
        $this->data['customer_id']=$this->jCombo('m_customers_t','customer_id','customer_name',$table[0]->customer_id); //  dd($this->data['customer_id']);
        
        $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t','payment_method_id','payment_method_name',$table[0]->default_payment_method_id);
        $this->data['default_bank_id']=$this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name',$table[0]->default_bank_id);
        
        //$this->data['default_bank_id']=$this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name',$table[0]->default_bank_id);
        
 
        $this->data['country_id'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', $country);
        $this->data['state_id'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', $state);
        $this->data['city_id'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', $city);
        
        //dd("$this->data['city_id']");
     
    if(count($this->data['linedata']) >= 1)
    {    
    foreach($this->data['linedata'] as $key=>$value)
    { 
      $a = sprintf("%03d ",($key+1));
      $seqno=$supno.'/SS'.$a;
        $this->data['linedata'][$key] = (object)array();  
        $this->data['linedata'][$key]->subcontract_site_number = $seqno;
      $this->data['linedata'][$key]->subcontract_site_name = $value->subcontract_site_name;  
      $this->data['linedata'][$key]->site_type = $value->site_type;
      $this->data['linedata'][$key]->address = $value->address;
      $this->data['linedata'][$key]->pincode = $value->pincode;
      $this->data['linedata'][$key]->contact_number = $value->contact_number;
      $this->data['linedata'][$key]->contact_person= $value->contact_person;
                        $this->data['linedata'][$key]->contact_mail= $value->contact_mail;
      $this->data['linedata'][$key]->subcontract_site_id = $value->subcontract_site_id;
      $this->data['linedata'][$key]->gst_number = $value->gst_number; 
      $this->data['linedata'][$key]->primary_address = $value->primary_address; 
    }
    }
  
    
    $this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','supplier')->get(); 
    
        
        if(isset($_GET['status'])){
      $this->data['used_some']="readonly";
    }
                
        return view('subcontractsupplier.form', $this->data);
    }
    /*End*/
    /*Delete Function*/
    public function delete(Request $request,$id=null)
    {

        $count=0;
        $queryquote = \DB::table('p_po_hdr_t')->where('subcontract_supplier_id',$id)->count();
        if($queryquote >=1)
        {
         $count++;
        }
        if($count <= 0)
        {
            Supplier::destroy($id);
            $query = \DB::table('m_subcontract_sites_t')->where('subcontract_supplier_id',$id)->delete();
            /**Auditlog**/
            $this->auditlog($id,"subcontractsupplier","delete","","m_subcontract_sites_t");
            
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
    /*End*/
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
    
   

  

public function ChecknameData(Request $request)
    {


        $subcontract_supplier_id = $_REQUEST['edit_id'];  
        if($subcontract_supplier_id == '')
        {
            $group=\DB::table('m_subcontract_supplier_t')->where('subcontract_name',$_REQUEST['subcontract_name'])->get();
        }
        else
        {
            $whereData = [['subcontract_name', $_REQUEST['subcontract_name']],['subcontract_supplier_id', '!=', $subcontract_supplier_id]];

            $group=\DB::table('m_subcontract_supplier_t')->where($whereData)->get();
        }


        if(count($group)>0)
            return 1;
        else
            return 0;


    }



	
	



  /*Karthigaa purpose for delete function*/
	public function delete_old(Request $request,$id=null){
        	Supplier::destroy($id);
                \DB::table('m_subcontract_sites_t')->where('subcontract_supplier_id', $id)->delete();
                \DB::table('m_supplieroverdue_t')->where('subcontract_supplier_id', $id)->delete();
                return redirect('supplier');
	}

   /*View Function*/     
   public function show(request $request,$id=null){
        if(isset($id))
        { 
          $supplier=DB::table('m_subcontract_supplier_t')->select('m_subcontract_supplier_t.*','m_suppliertypes_t.suppliertype_name','i_pricelist_hdr_t.pricelist_name',
                  'm_payment_methods_t.payment_method_name','f_bank_account_hdr_t.bank_name','m_payment_terms_t.payment_term_name',
                  'm_customers_t.customer_name','m_delivery_terms_t.delivery_term_name','m_insurance_terms_t.insurance_term_name',
                  'supp_acc.concatenated_segments as supplier_account','tds_acc.concatenated_segments as tds_account','tb_users.username')
                  ->leftjoin('m_suppliertypes_t','m_suppliertypes_t.suppliertype_id','=','m_subcontract_supplier_t.supplier_type_id')
                  ->leftjoin('i_pricelist_hdr_t','i_pricelist_hdr_t.pricelist_hdr_id','=','m_subcontract_supplier_t.default_pricelist_id')
                  ->leftjoin('m_payment_methods_t','m_payment_methods_t.payment_method_id','=','m_subcontract_supplier_t.default_payment_method_id')
                  ->leftjoin('m_customers_t','m_customers_t.customer_id','=','m_subcontract_supplier_t.customer_id')
		  ->leftjoin('f_bank_account_hdr_t','f_bank_account_hdr_t.bank_account_hdr_id','=','m_subcontract_supplier_t.default_bank_id')
                  ->leftjoin('m_payment_terms_t','m_payment_terms_t.payment_term_id','=','m_subcontract_supplier_t.default_payment_terms_id')
                  ->leftjoin('m_delivery_terms_t','m_delivery_terms_t.delivery_terms_id','=','m_subcontract_supplier_t.delivery_terms_id')
                  ->leftjoin('m_insurance_terms_t','m_insurance_terms_t.insurance_term_id','=','m_subcontract_supplier_t.insurance_term_id')
                  ->leftjoin('f_account_structure_t as supp_acc','supp_acc.f_account_structure_id','=','m_subcontract_supplier_t.account_structure_id')
                  ->leftjoin('f_account_structure_t as tds_acc','tds_acc.f_account_structure_id','=','m_subcontract_supplier_t.tds_account_id')
                  ->leftjoin('tb_users','tb_users.id','=','m_subcontract_supplier_t.created_by')
                  ->where('subcontract_supplier_id',$id)->get();
//          dd($supplier);
          $this->data['subcontract_number']=$supplier[0]->subcontract_number;
          $this->data['subcontract_name']=$supplier[0]->subcontract_name;
          $this->data['subcontract_alternate_name']=$supplier[0]->subcontract_alternate_name;
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
             $this->data['overdue']=$supplier[0]->overdue;
             $this->data['calculation_id']=$supplier[0]->calculation_id;
             
//            $supplieroverdue=DB::table('m_supplieroverdue_t')->select('m_supplieroverdue_t.*')
//                  ->where('subcontract_supplier_id',$id)->get();
//            $this->data['supplieroverdue']=$supplieroverdue;
//           $this->data['before_day']=$supplieroverdue[0]->before_day;
//            $this->data['before_dis']=$supplieroverdue[0]->before_dis;
//             $this->data['after_day']=$supplieroverdue[0]->after_day;
//             $this->data['after_int']=$supplieroverdue[0]->after_int;

             $vlinesdata = \DB::table('m_subcontract_sites_t')
                                            ->leftjoin('m_countries_t','m_countries_t.country_id','=','m_subcontract_sites_t.country')
                                            ->leftjoin('m_states_t','m_states_t.state_id','=','m_subcontract_sites_t.state')
                                            ->leftjoin('m_cities_t','m_cities_t.city_id','=','m_subcontract_sites_t.city')
                                            ->where('m_subcontract_sites_t.subcontract_supplier_id',$id)->get();
//             dd($vlinesdata[0]->country_name);
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
            return view('subcontractsupplier.view',$this->data);
        }
    }
   /*End View Function*/

/*save FUnction*/
    public function resave(Request $request)
    { 
           // exit;
       // dd($_POST);
        $supplier = new Subcontractsupplier();
        $this->modelname = new Subcontractsupplier();  
        $Suppliersites = new Subcontractsites(); 
        $this->modelline = new Subcontractsites();

        $this->modelsuppoverdue= new Supplieroverdue();
//dd($this->modelsuppoverdue);
        $primary = self::findPrimarykey('m_subcontract_supplier_t');      
        $primaryline = self::findPrimarykey('m_subcontract_sites_t'); 

        $save_status = $_POST['savestatus'];
        $supplier->subcontract_supplier_id = $_POST['subcontract_supplier_id'];  
        $supplier->subcontract_name = $_POST['subcontract_name']; 
        $seqno=$this->Seqnoe('S','m_subcontract_supplier_t',"",'subcontract_count');
        $supplier->subcontract_number= $seqno[0]; 
        $supplier->subcontract_count= $seqno[1]; 
		
        $supplier->supplier_type_id = $_POST['supplier_type_id']; 
        $supplier->default_payment_method_id = $_POST['default_payment_method_id'];
        $supplier->default_payment_terms_id = $_POST['default_payment_terms_id'];
        $supplier->default_pricelist_id = $_POST['default_pricelist_id'];
//        $supplier->contact_person = $_POST['contact_person'];
//        $supplier->contact_number = $_POST['contact_number'];
        $supplier->subcontract_alternate_name = $_POST['subcontract_alternate_name'];
        $supplier->overdue = $_POST['overdue']; 
        $supplier->pan_number=$_POST['pan_number'];
        $supplier->default_bank_id=$_POST['default_bank_id'];
		
        // $supplier->supplier_status=$_POST['supplier_status'];
        $supplier->customer_id=$_POST['customer_id']; 
        $supplier->customer_name=$_POST['customer_name']; 
        $supplier->account_structure_id=$_POST['account_structure_id']; 
        $supplier->tds_account_id=$_POST['tds_account_id']; 
        $supplier->tcs_account_id=$_POST['tcs_account_id']; 
        $supplier->delivery_terms_id=$_POST['delivery_terms_id']; 
        $supplier->insurance_term_id=$_POST['insurance_term_id']; 
        $supplier->tds_applicable=$_POST['tds_applicable'];
        $supplier->tds_percentage=$_POST['tds_percentage'];
        $supplier->tcs_applicable=$_POST['tcs_applicable'];
        $supplier->tcs_percentage=$_POST['tcs_percentage'];
         $supplier->active=$_POST['active'];
         $supplier->created_by=$_POST['created_by'];
            $supplier->organization_id=\Session::get('organization');
            $supplier->location_id=\Session::get('location');
            $supplier->company_id=\Session::get('companyid');
      

       
        $id = $this->insertData($this->modelname, $primary, $supplier, $_POST['subcontract_supplier_id'],$save_status); 
        $edit_id= DB::getPdo()->lastInsertId();
        $action="Create"; 
          /**Auditlog**/
            $this->auditlog($edit_id,"subcontractsupplier",$action,$_POST,"m_subcontract_sites_t");
     /********* LineItems Save ********** */
        
            $oldid = \DB::table('m_subcontract_sites_t')->where('subcontract_supplier_id', $id)->get(); 
        //dd($oldid);
                     
        if ($oldid->isEmpty()) { 
            for ($i = 0; $i < count($_POST['counter']); $i++) {
                $data['subcontract_supplier_id'] = $id; 
                $data['subcontract_site_id'] = $_POST['bulk_subcontract_site_id'][$i] ? $_POST['bulk_subcontract_site_id'][$i] : 0;
                $data['subcontract_site_number'] = $_POST['bulk_subcontract_site_number'][$i];
                $data['subcontract_site_name'] = $_POST['bulk_subcontract_site_name'][$i];
                $data['address'] = $_POST['bulk_address'][$i];
                $data['country'] = $_POST['bulk_country'][$i];
                $data['state'] = $_POST['bulk_state'][$i];
                $data['city'] = $_POST['bulk_city'][$i];
                $data['pincode'] = $_POST['bulk_pincode'][$i];
                $data['contact_person'] = $_POST['bulk_contact_person'][$i];
                $data['contact_number'] = $_POST['bulk_contact_number'][$i];  
                $data['contact_mail'] = $_POST['bulk_contact_mail'][$i];  
                $data['gst_number'] = $_POST['bulk_gst_number'][$i];  
                $data['primary_address'] = $_POST['bulk_primary_address'][$i];  
                $data['active'] = $_POST['bulk_active'][$i];  
                
                  $data['organization_id']=\Session::get('organization');
                  $data['location_id']=\Session::get('location');
                  $data['company_id']=\Session::get('companyid');
				
                \DB::table('m_subcontract_sites_t')->insert($data);
            }                    
                  return response()->json(array('status' => 'success', 'message' =>$supplier->subcontract_number.  'Subcontract Supplier '.$save_status.' Successfully','id'=>$id));
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
//dd($_POST['bulk_gst_number'][$i]);
                    $data['subcontract_supplier_id'] = $id;
                    $data['subcontract_site_id'] = $_POST['bulk_subcontract_site_id'][$i] ? $_POST['bulk_subcontract_site_id'][$i] : 0;
                    $data['subcontract_site_number'] = $_POST['bulk_subcontract_site_number'][$i];
                    $data['subcontract_site_name'] = $_POST['bulk_subcontract_site_name'][$i];
                    $data['address'] = $_POST['bulk_address'][$i];
                    $data['country'] = $_POST['bulk_country'][$i];
                    $data['state'] = $_POST['bulk_state'][$i];
                    $data['city'] = $_POST['bulk_city'][$i];
                    $data['pincode'] = $_POST['bulk_pincode'][$i];
                    $data['contact_person'] = $_POST['bulk_contact_person'][$i];
                    $data['contact_number'] = $_POST['bulk_contact_number'][$i];
                    $data['contact_mail'] = $_POST['bulk_contact_mail'][$i];  
                    $data['gst_number'] = $_POST['bulk_gst_number'][$i];
                     $data['primary_address'] = $_POST['bulk_primary_address'][$i];  
                     $data['active'] = $_POST['bulk_active'][$i];  
                  $data['organization_id']=\Session::get('organization');
                  $data['location_id']=\Session::get('location');
                  $data['company_id']=\Session::get('companyid');
				
                    
                    if ($data['subcontract_site_id'] = $existingId[$i]) {
                        $this->modelline::find($data['subcontract_site_id'])->update($data);
                    } else {
                        \DB::table('m_subcontract_sites_t')->insert($data);
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
                    \DB::table('m_subcontract_sites_t')->where('supplier_site_id', $val)->delete();
                }
                for ($i = 0; $i < count($oldIds); $i++) {
                    $data['subcontract_supplier_id'] = $id;
                    $data['subcontract_supplier_id'] = $_POST['bulk_subcontract_supplier_id'][$i] ? $_POST['bulk_subcontract_supplier_id'][$i] : 0;
                    $data['subcontract_number'] = $_POST['bulk_subcontract_number'][$i];
                    $data['subcontract_name'] = $_POST['bulk_subcontract_name'][$i];
                    $data['address'] = $_POST['bulk_address'][$i];
                    $data['country'] = $_POST['bulk_country'][$i];
                    $data['state'] = $_POST['bulk_state'][$i];
                    $data['city'] = $_POST['bulk_city'][$i];
                    $data['pincode'] = $_POST['bulk_pincode'][$i];
                    $data['contact_person'] = $_POST['bulk_contact_person'][$i];
                    $data['contact_number'] = $_POST['bulk_contact_number'][$i];
                    $data['gst_number'] = $_POST['bulk_gst_number'][$i];
                     $data['primary_address'] = $_POST['bulk_primary_address'][$i];  
                     $data['organization_id']=\Session::get('organization');
                  $data['location_id']=\Session::get('location');
                  $data['company_id']=\Session::get('companyid');
                    
                    
                    $this->modelline::find($data['supplier_site_id'])->update($data);
                }
            }
                  return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id'=>$id));
        }
        /**************************** LineItems Save End **************************** */

        $table = \DB::table('m_subcontract_supplier_t')->get();
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
	
	function customerdetails($id = null) {
//dd("fg");
                $customer_data=array();

                                    $org=\Session::get('organization');
                                    $loc=\Session::get('location');
                                    $com=\Session::get('companyid');



                $hdr_data =\DB::select("SELECT * from m_customers_t where customer_id=$id ");

                $line_data = \DB::select("SELECT * from m_customer_sites_t where customer_id=$id ");
                $over_line_data = \DB::select("SELECT * from over_due_t where customer_id=$id  ");

                //dd($hdr_data);

                $customer_data['hdr_data']=$hdr_data;
                $customer_data['line_data']=$line_data;
                $customer_data['over_line_data']=$over_line_data;

                return $customer_data;
                        //dd($customer_data);

                }
	public function suppliernamechk($supplierid=null){
	
		$yes = 0;
//		if($supplierid!=""){
//			$tablesCheck=[];
//			$tableNew['table_name']="p_quotation_hdr_t";
//			$tableNew['column_name']="supplier_id";
//			$tableNew['value']=$supplierid;
//			$tablesCheck[]=$tableNew;
//                      
//			foreach($tablesCheck as $tableToCheck){
//				if($yes!=1){
//					$frieght = \DB::select("select ".$tableToCheck['column_name']." from ".$tableToCheck['table_name']." where ".$tableToCheck['column_name']."=".$tableToCheck['value']);
//					
//					if(count($frieght)>0){
//						$yes = 1;
//					}
//				}
//			}
//		}
		return $yes;
	}
}
