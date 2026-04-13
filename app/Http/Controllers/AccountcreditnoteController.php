<?php

namespace App\Http\Controllers;

use App\Accountcreditnote;
use App\Accountcreditnotelines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class AccountcreditnoteController extends Controller
{
   public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->model=new Accountcreditnote;
        $this->submodel=new Accountcreditnotelines;
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='Accountcreditnote';
        $this->table="f_creditnote_hdr_t";
        $this->subtable="f_creditnote_lines_t";
        $this->middleware('auth');

    }   
     public function index()
    {
        $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
//       $this->data['invoiceno']=$this->jqgridselect('s_invoice_hdr_t','invoice_hdr_id','invoice_number'); 
       
         $this->data['pageMethod']='accountcreditnote'; 
        return view("accountcreditnote.table",$this->data);
    }

  
        public function getaccountcreditData(){

        $wh='';
        if($_GET['_search']=='true'){
        $wh=$this->jqgridsearch('f_creditnote_hdr_t',$_GET['filters']);
        }
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
        $result = \DB::select("SELECT COUNT(creditnote_hdr_id) AS count FROM f_creditnote_hdr_t where 1=1 $wh");

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
                        f_creditnote_hdr_t.creditnote_hdr_id,
                        f_creditnote_hdr_t.credit_number,
                        f_creditnote_hdr_t.credit_date,
                        f_creditnote_hdr_t.credit_status,
                        f_creditnote_hdr_t.invoice_number AS invoice_number,
                        f_creditnote_hdr_t.invoice_date AS invoice_date,
                        m_customers_t.customer_name AS customerid
                        FROM `f_creditnote_hdr_t`
                        LEFT JOIN m_customers_t ON(m_customers_t.customer_id = f_creditnote_hdr_t.`customerid`)
                        WHERE 1 = 1  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
                $result = \DB::select( $SQL );
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
             
        echo json_encode($responce);
    } 
     public function soinvoicetable()
    {
        $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
        $this->data['invoicenoopt'] = $this->jqgridselect('s_invoice_hdr_t', 'invoice_hdr_id', 'invoice_number');
        return view("accountcreditnote.soinvoicetable",$this->data);
    }
    
     public function salesinvoiceData() {
        $wh = '';
//        dd($_GET['_search']);
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearch('so_rma_hdr_t', $_GET['filters']);
        }
//        dd($wh);
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(so_rma_hdr_id) AS count FROM so_rma_hdr_t where 1=1 $wh");
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
        
         $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');
        
        
        $SQL = "SELECT
                s_invoice_hdr_t.invoice_number as reference_source_id,
                so_rma_hdr_t.remarks,
                so_rma_hdr_t.return_status,
                so_rma_hdr_t.rma_ref_no,
                so_rma_hdr_t.`return_date`,
                so_rma_hdr_t.`so_rma_hdr_id`,
                m_customers_t.customer_name as ship_to_customer_id
                FROM `so_rma_hdr_t`
                left join so_rma_lines_t on(so_rma_hdr_t.so_rma_hdr_id=so_rma_lines_t.so_rma_hdr_id)
                left join s_invoice_hdr_t on(s_invoice_hdr_t.invoice_hdr_id=so_rma_hdr_t.reference_source_id)
                JOIN m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.`ship_to_customer_id`)
                where 1=1 and so_rma_hdr_t.company_id=$compy and so_rma_hdr_t.location_id=$loc  $wh GROUP BY so_rma_hdr_t.so_rma_hdr_id ORDER BY $sidx $sord LIMIT $start , $limit";
        
        
        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }
  public function accountcreditnotecreate($id=null)
    {
    if(isset($id))
    { 
    $this->data['row']=$invoice_table=\DB::table('so_rma_hdr_t')->select('m_customers_t.customer_id',
                                                                            'm_customers_t.customer_name',
                                                                            'm_customer_sites_t.customer_site_id',
                                                                            'm_customer_sites_t.customer_site_name',
                                                                            's_invoice_hdr_t.invoice_hdr_id',
                                                                            's_invoice_hdr_t.invoice_date',
                                                                            'so_rma_hdr_t.so_rma_hdr_id',
                                                                            'so_rma_hdr_t.rma_ref_no',
                                                                            'so_rma_hdr_t.ship_to_address_id',
                                                                            's_invoice_hdr_t.invoice_number')
                ->leftJoin('s_invoice_hdr_t', 's_invoice_hdr_t.invoice_hdr_id', '=', 'so_rma_hdr_t.reference_source_id')
                ->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 'so_rma_hdr_t.customerid')
                ->leftJoin('m_customer_sites_t', 'm_customer_sites_t.customer_site_id', '=', 'so_rma_hdr_t.ship_to_address_id')
                ->where('so_rma_hdr_t.so_rma_hdr_id',$id)->get();
        $this->data['row'][0]->creditnote_hdr_id="";
        $this->data['row'][0]->credit_date=date('Y-m-d');
        $this->data['row'][0]->credit_number ="";
        $this->data['row'][0]->credit_status="DRAFT"; 
                
    $this->data['so_rma_hdr_id']= $this->jCombo('so_rma_hdr_t','so_rma_hdr_id','rma_ref_no',$invoice_table[0]->so_rma_hdr_id);                
    $this->data['customerid']= $this->jCombo('m_customers_t','customer_id','customer_name',$invoice_table[0]->customer_id);
    $this->data['ship_to_address_id'] = $invoice_table[0]->ship_to_address_id;
            $query2 = "SELECT site.customer_id, city.city_name, state.state_name, con.country_name,site.address,site.site_type,site.customer_site_name,site.contact_number,site.pincode,site.customer_site_id FROM `m_customer_sites_t` site left join m_countries_t con on ( con.country_id=site.`country` ) left join m_states_t state on ( state.state_id=site.`state`) left join m_cities_t city on( city.city_id=site.`city`) where site.customer_site_id='" . $invoice_table[0]->ship_to_address_id . "'";
            $result2 = \DB::select($query2);

            if (count($result2) > 0)
                $ship_to_address = $result2[0]->customer_site_name . "," . $result2[0]->address . "," . $result2[0]->city_name . "," . $result2[0]->state_name . "," . $result2[0]->country_name . "," . $result2[0]->contact_number . "," . $result2[0]->pincode;
            else
                $ship_to_address = '';
             $this->data['ship_to_address'] = $ship_to_address;
             
    $invhdrid=$invoice_table[0]->invoice_hdr_id;
    $this->data['linedata'] = \DB::table('s_invoice_lines_t')->where('invoice_hdr_id',$invhdrid)->get();
	if(count($this->data['linedata']) >= 1){
            foreach ($this->data['linedata'] as $key => $value) 
            {
                $this->data['linedata'][$key]=(object) array();
                $this->data['linedata'][$key]->creditnote_line_id ="";
		$unitprice = $this->pricelistorder($value->invoice_hdr_id,$value->product_id);
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product',$value->product_id);   
                $this->data['linedata'][$key]->uom_code_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id); 
                $invlineid=$value->invoice_line_id;
                $returnqty=\DB::select("select return_qty from so_rma_lines_t where reference_line_id ='$invlineid'");
                if( count($returnqty) > 0){
                    $return_qty=$returnqty[0]->return_qty;
                }else{
                    $return_qty=0;
                }
//                dd($return_qty);
                $tax = $this->gettax($value->product_id);

		$taxamount = (($return_qty * $unitprice) * $tax['display_name'] )/ 100;
                $linetotal = ($taxamount + ($return_qty * $unitprice));
    $this->data['linedata'][$key]->return_qty =$return_qty; 
    $this->data['linedata'][$key]->unit_price = $unitprice; 
    $this->data['linedata'][$key]->tax_group_id=$this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$tax['taxgroup_id']);
    $this->data['linedata'][$key]->tax_amount=$taxamount;
    $this->data['linedata'][$key]->line_total=$linetotal;
    $this->data['linedata'][$key]->comments="";
            }
        }
}
      
      return view('accountcreditnote.form',$this->data);
    }   
   
public function accountcreditnoteedit($id=null){
        if(isset($id)){ 
            $this->data['row']=$credit_table=\DB::table('f_creditnote_hdr_t')->select('m_customers_t.customer_id','m_customer_sites_t.customer_site_id','f_creditnote_hdr_t.ship_to_address_id','f_creditnote_hdr_t.so_rma_hdr_id','f_creditnote_hdr_t.invoice_number','f_creditnote_hdr_t.credit_date','f_creditnote_hdr_t.invoice_date','f_creditnote_hdr_t.credit_number','f_creditnote_hdr_t.credit_status')
               ->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 'f_creditnote_hdr_t.customerid')
               ->leftJoin('m_customer_sites_t', 'm_customer_sites_t.customer_site_id', '=', 'f_creditnote_hdr_t.ship_to_address_id')
               ->where('creditnote_hdr_id',$id)->get();
            
                $this->data['row'][0]->creditnote_hdr_id=$id;
                $this->data['row'][0]->credit_date=$credit_table[0]->credit_date;
          $this->data['row'][0]->credit_number=$credit_table[0]->credit_number;
          $this->data['row'][0]->credit_status=$credit_table[0]->credit_status;
          $this->data['row'][0]->invoice_date =$credit_table[0]->invoice_date ;
          $this->data['row'][0]->invoice_number =$credit_table[0]->invoice_number ; 
          $this->data['so_rma_hdr_id']= $this->jCombo('so_rma_hdr_t','so_rma_hdr_id','rma_ref_no',$credit_table[0]->so_rma_hdr_id);                
          $this->data['customerid']= $this->jCombo('m_customers_t','customer_id','customer_name',$credit_table[0]->customer_id);
//          $this->data['ship_to_address_id']= $this->jCombo('m_customer_sites_t','customer_site_id','customer_site_name',$credit_table[0]->customer_site_id);
          
          $this->data['ship_to_address_id'] = $credit_table[0]->ship_to_address_id;
          $query2 = "SELECT site.customer_id, city.city_name, state.state_name, con.country_name,site.address,site.site_type,site.customer_site_name,site.contact_number,site.pincode,site.customer_site_id FROM `m_customer_sites_t` site left join m_countries_t con on ( con.country_id=site.`country` ) left join m_states_t state on ( state.state_id=site.`state`) left join m_cities_t city on( city.city_id=site.`city`) where site.customer_site_id='" . $credit_table[0]->ship_to_address_id . "'";
            $result2 = \DB::select($query2);

            if (count($result2) > 0)
                $ship_to_address = $result2[0]->customer_site_name . "," . $result2[0]->address . "," . $result2[0]->city_name . "," . $result2[0]->state_name . "," . $result2[0]->country_name . "," . $result2[0]->contact_number . "," . $result2[0]->pincode;
            else
                $ship_to_address = '';
             $this->data['ship_to_address'] = $ship_to_address;
             
          $this->data['credit_status'] = $credit_table[0]->credit_status; 
             $tablelines = \DB::table('f_creditnote_lines_t')->where('creditnote_hdr_id',$id)->get();
                $this->data['linedata'] = $tablelines;
//                dd($tablelines);
            if(count($this->data['linedata']) >= 1){
            foreach ($this->data['linedata'] as $key => $value) 
            {
                $this->data['linedata'][$key]->creditnote_line_id=$value->creditnote_line_id;
                $this->data['linedata'][$key]->creditnote_hdr_id=$value->creditnote_hdr_id; 
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product',$value->product_id);   
                $this->data['linedata'][$key]->uom_code_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id); 
                 $this->data['linedata'][$key]->unit_price = $value->unit_price; 
                 $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$value->tax_group_id);
                 $this->data['linedata'][$key]->tax_amount = $value->tax_amount; 
                 $this->data['linedata'][$key]->line_total = $value->line_total; 
            }
        }
    }
   return view('accountcreditnote.form',$this->data);
}
    public function accountcreditnoteview($id=null)
    {
        if(isset($id))
        { 
          
            $this->data['row']=$credit_table=\DB::table('f_creditnote_hdr_t')->select('m_customers_t.customer_id','m_customer_sites_t.customer_site_id','f_creditnote_hdr_t.ship_to_address_id','f_creditnote_hdr_t.invoice_number','f_creditnote_hdr_t.invoice_date','f_creditnote_hdr_t.credit_status','f_creditnote_hdr_t.credit_date','f_creditnote_hdr_t.credit_number')
                ->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 'f_creditnote_hdr_t.customerid')
                ->leftJoin('m_customer_sites_t', 'm_customer_sites_t.customer_site_id', '=', 'f_creditnote_hdr_t.ship_to_address_id')
                ->where('creditnote_hdr_id',$id)->get();
           // dd($credit_table);
                $this->data['row'][0]->creditnote_hdr_id=$id;
                $this->data['row'][0]->credit_date=$credit_table[0]->credit_date;
                $this->data['row'][0]->credit_number=$credit_table[0]->credit_number;
                $this->data['row'][0]->credit_status=$credit_table[0]->credit_status;
                $this->data['row'][0]->invoice_date=$credit_table[0]->invoice_date;
                $this->data['row'][0]->invoice_number=$credit_table[0]->invoice_number;
                $this->data['customerid']= $this->idname('customer_name','m_customers_t','customer_id',$credit_table[0]->customer_id);
//               $this->data['ship_to_address_id']= $this->idname('customer_site_name','m_customer_sites_t','customer_site_id',$credit_table[0]->customer_site_id);
                $this->data['ship_to_address_id'] = $credit_table[0]->ship_to_address_id;
  $query2 = "SELECT site.customer_id, city.city_name, state.state_name, con.country_name,site.address,site.site_type,site.customer_site_name,site.contact_number,site.pincode,site.customer_site_id FROM `m_customer_sites_t` site left join m_countries_t con on ( con.country_id=site.`country` ) left join m_states_t state on ( state.state_id=site.`state`) left join m_cities_t city on( city.city_id=site.`city`) where site.customer_site_id='" . $credit_table[0]->ship_to_address_id . "'";
            $result2 = \DB::select($query2);

            if (count($result2) > 0)
                $ship_to_address = $result2[0]->customer_site_name . "," . $result2[0]->address . "," . $result2[0]->city_name . "," . $result2[0]->state_name . "," . $result2[0]->country_name . "," . $result2[0]->contact_number . "," . $result2[0]->pincode;
            else
                $ship_to_address = '';
             $this->data['ship_to_address'] = $ship_to_address;


             $tablelines = \DB::table('f_creditnote_lines_t')->where('creditnote_hdr_id',$id)->get();
            $this->data['linedata'] = $tablelines;
            if(count($this->data['linedata']) >= 1)
        {
            foreach ($this->data['linedata'] as $key => $value) 
            {
               $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->idname('concatenated_product','m_products_t','product_id',$value->product_id);
                $this->data['linedata'][$key]->uom_code_id =  $this->idname('uom_code','m_uom_codes_t','uom_code_id',$value->uom_code_id);  
                    $this->data['linedata'][$key]->return_qty = $value->return_qty;
                    $this->data['linedata'][$key]->unit_price = $value->unit_price;
                $this->data['linedata'][$key]->tax_group_id = $this->idname('tax_group_name','m_tax_group_t','tax_group_id',$value->tax_group_id); 
                $this->data['linedata'][$key]->tax_amount = $value->tax_amount;
                $this->data['linedata'][$key]->line_total = $value->line_total;
                $this->data['linedata'][$key]->comments = $value->comments;
                 
            }
        }
}

      return view('accountcreditnote.view',$this->data);
    }function custaddress($id =null)
	{
            $SQL = "SELECT site.customer_id, city.city_name, state.state_name, con.country_name,site.address,site.site_type,site.customer_site_name,site.contact_number,site.pincode,site.customer_site_id FROM `m_customer_sites_t` site left join m_countries_t con on ( con.country_id=site.`country` ) left join m_states_t state on ( state.state_id=site.`state`) left join m_cities_t city on( city.city_id=site.`city`) where site.primary_address='Yes' and site.customer_id='$id'";
            $result = \DB::select($SQL);
            $output = array();

            if(count($result)>0 && count($result) == 1)
            {
                foreach($result as $key=>$value)
                {
                    if($value != '')
                    {
                        if($value->site_type == "BILL_TO")
                        {
                            $output[0]  = $value->customer_site_name.",<br>".$value->address.",<br>".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.".<br> Contact No:".$value->contact_number."~".$value->customer_site_id;
                            $output[1] = '';
                        }
                        else if($value->site_type == "SHIP_TO")
                        {
                            $output[0]  =$value->customer_site_name.",<br>".$value->address.",<br>".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.".<br> Contact No:".$value->contact_number."~".$value->customer_site_id;
                            $output[1] = '';
                        }
                        else
                        {
                            $output[0] ='';
                            $output[1] ='';
                        }
                    }
                }

            }
            else if(count($result)>0 && count($result) == 2)
            {
                foreach($result as $key=>$value)
                {
                    if($value != '')
                    {
                        if($value->site_type == "BILL_TO")
                        {
                            $output[$key]  = $value->customer_site_name.",<br>".$value->address.",<br>".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.".<br> Contact No:".$value->contact_number."~".$value->customer_site_id;
                        }
                        else if($value->site_type == "SHIP_TO")
                        {
                            $output[$key]  = $value->customer_site_name.",<br>".$value->address.",<br>".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.".<br> Contact No:".$value->contact_number."~".$value->customer_site_id;
                        }

                    }
                }

            }
            else
            {
                $output[0] ='';
                $output[1] ='';
            }
                return $output;


	}
    
    function pricelistorder($inv_hdr_id,$p_id){
	$result=\DB::table('s_invoice_lines_t')->select('unit_price as unit_price')->where('invoice_hdr_id',$inv_hdr_id)->where('product_id',$p_id)->get();
        if($result->isNotEmpty()){
	$unit_price=$result[0]->unit_price;
	}
	else {
	$unit_price= '0.00';
	}
	return $unit_price;
	}
  public function taxgroup($pid=null){
	$pro_details=\DB::table("m_products_t")->select('trx_uom_id','hsn_code')->where('product_id',$pid)->get();
    
        if($pro_details->isNotEmpty()){
            $prd_data['uom_code_id']=$pro_details[0]->trx_uom_id;
            $hsn_code=$pro_details[0]->hsn_code;
            $date=date('Y-m-d');
            $tax=\DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$hsn_code' and start_date<='$date' and end_date>='$date' and active='Yes'");
        if(!empty($tax))
        {
          return  $prd_data['tax_group_id']=$tax[0]->tax_group_id; 
        }
	else {
	return 0;
	}
	}
	else
	{
	return 0;	
	}
	}
        public function gettax($pid)
	{
	 $pro_details=\DB::table("m_products_t")->select('trx_uom_id','hsn_code')->where('product_id',$pid)->get();

	if($pro_details->isNotEmpty())
	{
	
	$prd_data['uom_code_id']=$pro_details[0]->trx_uom_id;
	$hsn_code=$pro_details[0]->hsn_code;
	$date=date('Y-m-d');
	$tax=\DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$hsn_code' and start_date<='$date' and end_date>='$date' and active='Yes'");
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
    	 return $prd_data;
	}
	}
	else
	{
 	 $prd_data['uom_code_id']=0;
	 $prd_data['taxgroup_id']=0; 
	 $prd_data['display_name']=1;
	 return $prd_data;
	}
	}
	
     public function accountcreditnotesave(Request $request)
        {
//        dd($_POST);
                $id='';
                $data = $this->validatePost($request->all(),$this->table,'header');      
                $lines_data = $this->validatePost($request->all(),$this->subtable,'lines'); 
                if ($_POST['credit_number'] ==""){
                        $seqno=$this->Seqno('CR-','f_creditnote_hdr_t',$_POST['credit_number']);
                    }
                    else{
                       $seqno=$_POST['credit_number'];
                    }
                   
                \DB::beginTransaction();
                try
                {
                    $data['credit_number']=$seqno;
                     $id=$this->model->insertRow($data);
                     $lid=$this->submodel->subgridSave($lines_data,$id);
                     
/*Karthigaa Purpose For Journal Entry Insert*/
    if($_POST['credit_status']=="INITIATED"){
                   $creditdate=$_POST['credit_date'];
                   $org=\Session::get('organization');
                    $loc=\Session::get('location');
                    $compy=\Session::get('companyid');
                   $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('CREDIT NOTE','$creditdate','$id','OPEN','$compy','$loc','$org')");
                   $jid = DB::getPdo()->lastInsertId();

                   $invoiceid=DB::table('f_creditnote_lines_t')->where('creditnote_hdr_id',$id)->get();
//                   dd($invoiceid);
                     //<**----Credit Net Account--------------------------------***------>    
                   //TAX
              $taxamt=0;
                   foreach($invoiceid as $tkey => $tvalue) { 
                       $sum_taxamt=$taxamt+$tvalue->tax_amount;
                   }
                   $cussiteid=$_POST['ship_to_address_id'];
                   $state=\DB::select("SELECT state FROM m_customer_sites_t  WHERE `customer_site_id`='$cussiteid'");
                   $stateid=$state[0]->state;
              $tax=$_POST['bulk_tax_group_id'];
             $taxgroup= \DB::table('m_tax_group_lines_t')->where('tax_group_id',$tax)->get();
	      foreach($taxgroup as $gkey => $gvalue) {  
                 if($stateid==31){
                   $taxamount=$sum_taxamt/2;
                   $inv_acc1['journal_entry_id']   = $jid; 
                   $inv_acc1['account_id']   =$gvalue->tax_account_id;
                    $inv_acc1['debit_amount']=$taxamount;
                    $inv_acc1['credit_amount']   =0 ;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc1);
                      }
                  
                   else{
                     $taxamount=$sum_taxamt;
                     $inv_acc1['journal_entry_id']   = $jid; 
                     $inv_acc1['account_id']   =$gvalue->tax_account_id;
                    $inv_acc1['debit_amount']=$taxamount;
                    $inv_acc1['credit_amount']   = 0;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc1);  
                   }
              }
              
               //<**----Credit Net Account--------------------------------***------>                  
            foreach($invoiceid as $key => $value) {
                   $inv_acc['journal_entry_id']   = $jid; 
                        $pid=$value->product_id;
                        $prdacc=\DB::select("select account_code_id from m_products_t where product_id=$pid");
                   $inv_acc['account_id']   =$prdacc[0]->account_code_id;
                    $inv_acc['debit_amount']   =$value->line_total-$value->tax_amount;
                    $inv_acc['credit_amount']   = 0;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc);
                   }
            //<**----Debit Account---------***------>                  
            $sum_linetotal=0;
                    foreach($invoiceid as $tkey => $tvalue) { 
                        $sum_linetotal=$sum_linetotal+$tvalue->line_total;
                    }
                   $inv_acc2['journal_entry_id']   = $jid; 
                       $cid=$_POST['customerid'];
                       $cusacc=\DB::select("select account_structure_id from m_customers_t where customer_id=$cid");
                    $inv_acc2['account_id']=$cusacc[0]->account_structure_id;
                    $inv_acc2['debit_amount']   = 0;
                    $inv_acc2['credit_amount']   =$sum_linetotal;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc2);
                   /*End*/
         
            }
        /*End*/
                     \DB::commit();
                     return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'lid' => $lid,'auto_no'=>$seqno));
                }
                catch (\Illuminate\Database\QueryException $e)
                {
                     $message = explode('(', $e->getMessage());
                     $dbCode = rtrim($message[0], ']');
                     $dbCode = trim($dbCode, '[');
                    // dd($dbCode);
                     \DB::rollback();
                     return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
                }

        }
   
   
 
  
   
}
