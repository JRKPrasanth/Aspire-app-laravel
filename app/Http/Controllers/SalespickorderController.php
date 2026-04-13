<?php
namespace App\Http\Controllers;

use App\Salespickorder;
use App\Salespickorderlines;
use App\Soorder;
use App\Soorderlines;
use Illuminate\Http\Request;

class SalespickorderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
	public function __construct()
	{
		$this->data=array();
		$this->model=new Salespickorder;
		$this->submodel=new Salespickorderlines;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
		$this->table="s_pickrelease_hdr_t";
		$this->subtable="s_pickrelease_lines_t";
		$this->subtable1="s_pickrelease_sub_lines_t";
		$this->middleware('auth');
        $this->data['urlmenu']=$this->indexs(); 
	}
	public function pick(){
		$this->data['prdopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
        $this->data['grpopt']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
        $this->data['catopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
        $this->data['cusopt']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
       	$this->data['priceopt']=$this->jqgridselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name');
	    $url=\Request::route()->getName();

	    return view("pickorder.prdtable",$this->data);
	}

    public function index()
    {
	   $this->data['customer']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
       $this->data['prepareropt']=$this->jqgridselect('tb_users','id','username');
       $this->data['cusopt']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
       $this->data['priceopt']=$this->jqgridselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name');
      
        $this->data['prdopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
        $this->data['grpopt']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
        $this->data['catopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
        
	   $url=\Request::route()->getName();

		if($url=="dispatchfrmpickorder"){
			$this->data['url']="dispatchfrmpickorder";
			$this->data['status']="RELEASED";
		}
		else if($url=="invoicefrompickorder"){
			$this->data['url']="invoicefrompickorder";
			$this->data['status']="RELEASED";
		}
		else{
			$this->data['url']="pickorder";
			$this->data['status']="APPROVED";
		}
		
		return view("pickorder.table",$this->data);
		
    }
		

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null){
		$company_id = \Session::get('companyid');
		$this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','pickorder')->get();
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
		$this->data['cusnameopt']=$this->jqgridselect('m_customers_t','customer_id','customer_name');		
		$this->data['custypeopt']=$this->jqgridselect('m_customer_types_t','customer_type_id','customer_type');
		$this->data['country']=$this->jqgridselect('m_countries_t','country_id','country_name');
		$this->data['state']=$this->jqgridselect('m_states_t','state_id','state_name');
		$this->data['city']=$this->jqgridselect('m_cities_t','city_id','city_name');
    	$this->data['pageurl'] = "pickorder";
	    $salesorder=Soorder::find($id);	
	    
		$this->data['prepare_date']=date('d-m-Y');
		$this->data['release_status']='PICK ORDER';
		$this->data['release_date']=date('d-m-Y');
		$this->data['release_source']='DIRECT';
		$this->data['location_id']=$this->jcombo("m_location_t","location_id","location_name", \Session::get('location')); 
		$this->data['organization_id']=$this->jcombo('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
		$this->data['preparer_id']=$this->jcombo('tb_users','id','username',\Session::get('id'));
		$this->data['freight_carrier_id'] = $this->jcombo('m_frieghtcarriers_hdr_t','ar_frieghtcarriers_hdr_id','carrier_name',$salesorder->freight_carrier_id);
		$this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_number|customer_name',$salesorder->ship_to_customer_id);
		$this->data['remarks']="";
		$this->data['pricelist_id']=$this->jcombo('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$salesorder->pricelist_id,' and price_list_type="Sales"');
		$address = $this->custaddress($salesorder->ship_to_customer_id);
		//dd($address);
		$this->data['deliver_to_location']=$address[1];
        $this->data['ar_sales_hdr_id']=$salesorder->sales_hdr_id;
					
		$sql=\DB::select("select sales_order_no,sales_hdr_id from s_salesorder_hdr_t where sales_hdr_id in ($id)");
		$sono="";
		foreach($sql as $key=>$value){
			$sono.=$value->sales_order_no.",";
		}
		$soorderno=rtrim($sono,',');
        $this->data['reference_no']=$soorderno;

	    $tablelines=\DB::select("select sl.sales_hdr_id,sl.sales_line_id,sl.product_id,sum(sl.qty) as qty, sum(sl.dispatched_qty) as dispatched_qty,sl.uom_code_id from s_salesorder_lines_t sl WHERE  sl.sales_hdr_id in($id) group by sl.product_id");
	    $this->data['linedata'] = $tablelines;
		// dd($this->data['linedata']);
		foreach($this->data['linedata'] as $key=>$value)
		{
			$this->data['linedata'][$key]->line_no =$key+1;
			$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);			
			$this->data['linedata'][$key]->so_pickrelease_hdr_id ="";
			$this->data['linedata'][$key]->so_pickrelease_line_id ="";	
			$this->data['linedata'][$key]->ar_sales_hdr_id =$value->sales_hdr_id;
			$this->data['linedata'][$key]->ar_sales_line_id	=$value->sales_line_id;
			$this->data['linedata'][$key]->so_qty=$value->qty;
			$this->data['linedata'][$key]->release_qty='';
			$this->data['linedata'][$key]->comments ="";	

			$this->data['linedata'][$key]->picked_qty=$value->qty - $value->dispatched_qty;
			
			$qoh_qty = \DB::select("select sum(f.qty-f.qtyy) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id='".$value->product_id."' and i_qoh_detail_t.company_id='".$company_id."' GROUP by product_id UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id='".$value->product_id."' and i_reservation_detail_t.company_id='".$company_id."' GROUP by product_id)f");

			
			if(!empty($qoh_qty)){
				$result[$key]=(object) array();
				$this->data['linedata'][$key]->qoh_qty=$qoh_qty[0]->qoh_qty;
			}else{
			   $this->data['linedata'][$key]->qoh_qty="0";
			}

			$this->data['linedata'][$key]->product_id = $this->data['product_id']=$this->jCombo('m_products_t','product_id','concatenated_product',$value->product_id);	
		}
		return view("pickorder.form",$this->data);
    }

    public function create_old($soid=null,$type=null)
    {
    	$soid1 = explode(",",$soid);
		$salesorderdata=Soorder::find($soid);
		$this->data['so_pickrelease_hdr_id']="";
		$this->data['release_reference_no']="";
		$this->data['pickrelease_count']="";
                
		$this->data['release_source']='ORDER';
		$this->data['ar_sales_hdr_id']=$soid;
		$this->data['release_date']=date('Y-m-d');
		$this->data['so_ref_no']=$salesorderdata->so_ref_no;
		$this->data['customer_po_number']=$salesorderdata->customer_po_number;
		$this->data['release_status']='OPEN';
		$this->data['location_id']=$this->jCombo('m_customers_t','customer_id','customer_number|customer_name','');
		$this->data['organization_id']=$this->jCombo('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
		$this->data['preparer_id']=$this->jCombo('tb_users','id','username',\Session::get('id'));
		$this->data['remarks']="";
		$this->data['pack_weight']="";
		$this->data['packaging_qty']="";
		$this->data['bill_to_address_id']=$this->Siteaddress($salesorderdata->ship_to_customer_id);
		//dd($salesorderdata->ship_to_customer_id);
		$address = $this->custaddress($salesorderdata->ship_to_customer_id);
		$this->data['billing_to_address_txt']=$address[0];
		$this->data['ship_to_address_id']="";
		$this->data['shipping_to_address_txt']=$address[1];
		$this->data['pricelist_id']=$this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$salesorderdata->pricelist_id,' and price_list_type="Sales"');
		$this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_number|customer_name',$salesorderdata->ship_to_customer_id);
		$this->data['deliver_to_location']=$this->jCombo('m_customers_t','customer_id','customer_number|customer_name','');

		//dd("select sl.sales_hdr_id,sl.sales_line_id,sl.product_id,sum(sl.remaining_qty) as soqty,sl.uom_code_id,sp.remaining_qty from s_salesorder_lines_t sl left join s_pickrelease_sub_lines_t sp on(sl.sales_hdr_id=sp.ar_sales_hdr_id) WHERE  sl.sales_hdr_id in($soid) group by sl.product_id");
		
		$solines=\DB::select("select ph.so_pickrelease_hdr_id, sl.sales_hdr_id, sl.sales_line_id, sl.product_id, sum(sl.remaining_qty) as soqty, sl.qty,sum(sl.qty) as pdtqty, sl.uom_code_id,sp.remaining_qty from s_salesorder_lines_t sl left join s_pickrelease_sub_lines_t sp on(sl.sales_hdr_id=sp.ar_sales_hdr_id) left join s_pickrelease_lines_t ph on(ph.so_pickrelease_line_id=sp.so_pickrelease_line_id) WHERE sl.sales_hdr_id in($soid) group by sl.product_id");
		//dd($solines);
		//dd($solines);
		//$solines=\DB::select("select sl.ar_sales_hdr_id,sl.ar_sales_line_id,sl.product_id,sum(sl.remaining_qty) as soqty,sl.uom_code_id,sp.remaining_qty from s_pickrelease_lines_t sl left join s_pickrelease_sub_lines_t sp on(sl.ar_sales_hdr_id=sp.ar_sales_hdr_id) WHERE  sl.ar_sales_hdr_id in(19) group by sl.product_id");
		foreach ($solines as $key => $value)
		{
			//dd($value);
		$this->data['linedata'][$key]=(object) array();
		$this->data['linedata'][$key]->line_no =$key+1;
            //$this->data['product']=$this->jcustomproductselect('m_products_t','product_id','concatenated_product','','soorder');
		$this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t','product_id','concatenated_product',$value->product_id,'soorder');
		$this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
		$this->data['linedata'][$key]->so_pickrelease_hdr_id ='';
		$this->data['linedata'][$key]->so_pickrelease_line_id ='';
		$this->data['linedata'][$key]->ar_sales_hdr_id =$value->sales_hdr_id;
		$piqty=$this->getpickqty($soid,$value->product_id);
		$this->data['linedata'][$key]->ar_sales_line_id =$value->sales_line_id;
		$pdtqty = $this->getsalesqty($soid,$value->product_id);
		$this->data['linedata'][$key]->so_qty =$pdtqty;
		$this->data['linedata'][$key]->release_qty ="";
    	$picksql =\DB::table('s_pickrelease_sub_lines_t')->where('ar_sales_hdr_id',$value->sales_hdr_id)->where('ar_sales_line_id',$value->sales_line_id)->get();
		//dd($picksql);	
      	if($picksql->isNotEmpty())
			{
				$tot = 0;
                                
				foreach($picksql as $key1=>$val)
				{
					if($val->remaining_qty != '')
					{
						$tot = $tot + $val->remaining_qty;
					}
				}
			}
			else
			{
					$tot = 0;
			  }
			//dd($piqty);
		//$this->data['linedata'][$key]->picked_qty =$tot;
		$this->data['linedata'][$key]->picked_qty =$piqty;
		$this->data['linedata'][$key]->invoiced_qty ="";
		//$this->data['linedata'][$key]->remaining_qty ="";
		$this->data['linedata'][$key]->comments ="";
		$this->data['linedata'][$key]->remarks ="";

		/*	$pid=$value->product_id;
				$stock_qty=\DB::select("select sum(qoh_trx_qty) as qoh  from i_qoh_detail_t where product_id='$pid' ");
			
			if(!empty($stock_qty)){
				if(is_null($stock_qty[0]->qoh))
				{
				$this->data['linedata'][$key]->qoh=0;
				}
				else
				{
					if($stock_qty[0]->qoh < 0)
					{
						$qoqty = 0;
					}
					else
					{
						$qoqty = $stock_qty[0]->qoh;
					}
				$this->data['linedata'][$key]->qoh=$qoqty;
				}
			}
			else{
			$this->data['linedata'][$key]->qoh=0;
			}
			*/
			//$pid=$value->product_id;
			$qoh_qty=\DB::select("SELECT ( SELECT SUM(qoh_trx_qty) FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$value->product_id') as qoh ,( SELECT SUM(-reserv_trx_qty) FROM i_reservation_detail_t WHERE i_reservation_detail_t.product_id = '$value->product_id') as res,(SELECT qoh - res ) as ava FROM i_qoh_detail_t WHERE i_qoh_detail_t.product_id = '$value->product_id'  GROUP BY i_qoh_detail_t.product_id ");

if(!empty($qoh_qty)){
	$result[$key]=(object) array();
$this->data['linedata'][$key]->qoh=$qoh_qty[0]->qoh;
$this->data['linedata'][$key]->res_qty=$qoh_qty[0]->res;
$this->data['linedata'][$key]->ava_qty=$qoh_qty[0]->ava;
}else{
   $this->data['linedata'][$key]->qoh="0";
$this->data['linedata'][$key]->res_qty="0";
$this->data['linedata'][$key]->ava_qty="0"; 
}
			//dd($this->data['linedata']);
	
		}
		$sql=\DB::select("select sales_order_no,sales_hdr_id from s_salesorder_hdr_t where sales_hdr_id in ($soid)");

	$sono="";
		foreach($sql as $key=>$value){
		$sono.=$value->sales_order_no.",";
	}
		$soorderno=rtrim($sono,',');
		$this->data['sales_order_no']=$soorderno;
			$this->data['cusnameopt']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
			$this->data['custypeopt']=$this->jqgridselect('m_customer_types_t','customer_type_id','customer_type');
			$this->data['country']=$this->jqgridselect('m_countries_t','country_id','country_name');
			$this->data['state']=$this->jqgridselect('m_states_t','state_id','state_name');
			$this->data['city']=$this->jqgridselect('m_cities_t','city_id','city_name');
	//dd($this->data);
       return view("pickorder.form",$this->data);
    }
  function getsalesqty($soid,$pdt_id)
  {
	  $soqty= \DB::select("select sum(qty) as qty from s_salesorder_lines_t where sales_hdr_id in($soid) and product_id='$pdt_id'");
	  return $soqty[0]->qty;
  }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	
	function custaddress($id =null)
	{
            $SQL = "SELECT m_customer_sites_t.site_type,m_customer_sites_t.address,m_customer_sites_t.contact_number,m_customer_sites_t.customer_site_id,m_customer_sites_t.country,m_customer_sites_t.state,m_customer_sites_t.city
,m_customer_sites_t.customer_site_name,m_customer_sites_t.pincode,m_countries_t.country_name,m_states_t.state_name,m_cities_t.city_name
FROM m_customers_t 
inner join m_customer_sites_t ON
m_customer_sites_t.customer_id=m_customers_t.customer_id
join m_countries_t on m_countries_t.country_id = m_customer_sites_t.country
join m_states_t on m_states_t.state_id = m_customer_sites_t.state
join m_cities_t on m_cities_t.city_id = m_customer_sites_t.city
WHERE m_customer_sites_t.customer_site_id IN (
   SELECT MAX(customer_site_id)
   FROM m_customer_sites_t where customer_id ='$id'
   GROUP BY m_customer_sites_t.site_type
)";
		
		$download_SQL = "SELECT m_customer_sites_t.site_type,m_customer_sites_t.address,m_customer_sites_t.contact_number,m_customer_sites_t.customer_site_id,m_customer_sites_t.country,m_customer_sites_t.state,m_customer_sites_t.city
,m_customer_sites_t.customer_site_name,m_customer_sites_t.pincode,m_countries_t.country_name,m_states_t.state_name,m_cities_t.city_name
FROM m_customers_t 
inner join m_customer_sites_t ON
m_customer_sites_t.customer_id=m_customers_t.customer_id
join m_countries_t on m_countries_t.country_id = m_customer_sites_t.country
join m_states_t on m_states_t.state_id = m_customer_sites_t.state
join m_cities_t on m_cities_t.city_id = m_customer_sites_t.city
WHERE m_customer_sites_t.customer_site_id IN (
   SELECT MAX(customer_site_id)
   FROM m_customer_sites_t where customer_id ='$id'
   GROUP BY m_customer_sites_t.site_type
)";
		
		$result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }

		
		
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
                            $output[0]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number."~".$value->customer_site_id;
                            $output[1] = '';
                        }
                        else if($value->site_type == "SHIP_TO")
                        {
                            $output[0]  =$value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number."~".$value->customer_site_id;
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
                            $output[$key]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number."~".$value->customer_site_id;
                        }
                        else if($value->site_type == "SHIP_TO")
                        {
                            $output[$key]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number."~".$value->customer_site_id;
                        }
                        
                    }
                }
                
            }
		else if(count($result) > 2)
			{
				 foreach($result as $key=>$value)
                {
                    if($value != '')
                    {
                        if($value->site_type == "BILL_TO")
                        {   
                            $output[$key]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number."~".$value->customer_site_id;
                        }
                        else if($value->site_type == "SHIP_TO")
                        {
                            $output[$key]  = $value->customer_site_name.",".$value->address.",".$value->city_name.",".$value->state_name."-".$value->pincode.",".$value->country_name.". Contact No:".$value->contact_number."~".$value->customer_site_id;
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
    public function save(Request $request){

		// dd($_POST);
		$id='';
		$data = $this->validatePost($request->all(),$this->table,'header');
		$lines_data = $this->validatePost($request->all(),$this->subtable,'lines');
		// dd($lines_data);

		\DB::beginTransaction();
		try{
			$id=$this->model->insertRow($data);
			unset($lines_data['sowise_qty']);
			unset($lines_data['soorder_id']);
			unset($lines_data['soorder_lineid']);
			unset($lines_data['sototqty']);
			unset($lines_data['soorder_qty']);
			unset($lines_data['p_line_no']);
			unset($lines_data['p_box_no']);
			unset($lines_data['p_kit_pack_no']);
			unset($lines_data['p_batch_no']);
			unset($lines_data['p_subinventory_id']);
			unset($lines_data['p_sublocator_id']);
			unset($lines_data['p_issue_qoh']);
			$lid=$this->submodel->subgridSave($lines_data,$id);
			foreach($lid['id'] as $k => $v){
				$line['so_pickrelease_line_id'] = $v;
				$line['ar_sales_hdr_id'] = $_POST['bulk_ar_sales_hdr_id'][$k];
				$line['ar_sales_line_id'] = $_POST['bulk_ar_sales_line_id'][$k];
				$line['product_id'] = $_POST['bulk_product_id'][$k];
				$line['so_qty'] = $_POST['bulk_so_qty'][$k];
				$line['pickorder_qty'] = $_POST['sowise_qty'][$k];
				$line['location_id'] = \Session::get('location');
				$line['company_id'] = \Session::get('companyid');
				$line['created_by'] = \Session::get('emp_id');

				\DB::table('s_pickrelease_sub_lines_t')->insert($line);
			}
			
			\DB::commit();
			
			return response()->json(array('status' => 'success', 'message' => $data['release_reference_no'].' Saved Successfully','id' => $id,'lid' => $lid));
		}
		catch (\Illuminate\Database\QueryException $e){
			$message = explode('(', $e->getMessage());
			$dbCode = rtrim($message[0], ']');
			$dbCode = trim($dbCode, '[');
			// dd($dbCode);
			\DB::rollback();
			return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Salespickorder  $salespickorder
     * @return \Illuminate\Http\Response
     */
    public function show(Salespickorder $salespickorder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Salespickorder  $salespickorder
     * @return \Illuminate\Http\Response
     */
    public function edit(Salespickorder $salespickorder)
    {

        return view("pickorder.form",$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salespickorder  $salespickorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salespickorder $salespickorder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Salespickorder  $salespickorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salespickorder $salespickorder)
    {
        //
    }


	public function soorderproductgriddata()
	{

	$wh='';
	if($_GET['_search']=='true')
	{

	$wh=$this->jqgridsearch('s_salesorder_hdr_t',$_GET['filters']);

	}
	$page = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx = $_GET['sidx'];
	$sord = $_GET['sord'];
	if(!$sidx) $sidx =1;

	$result = \DB::select("SELECT COUNT(sales_hdr_id) AS count FROM s_salesorder_hdr_t where 1=1 $wh");
	$count = $result[0]->count;
	if( $count > 0 && $limit > 0) {
	$total_pages = ceil($count/$limit);
	} else {
	$total_pages = 0;
	}

	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit;
	if($start <0) $start = 0;

        
	$SQL = "SELECT
so.sales_hdr_id,
so.`sales_order_no`,
so.`sales_order_date`,
so.`order_type_id`,
so.`contact_number` as contact_number,
so.`contact_person`,pr.pricelist_name as sales_pricelist_id,
sp.salesperson_name as salesperson_id,
ct.customer_name as ship_to_customer_id,
sq.quote_no as ar_quote_hdr_id,sol.sales_line_id,
sol.product_id,sol.qty,sol.uom_code_id,
prd.concatenated_product,concat(cs.`customer_site_name`,',',cs.`address`,',',mc.city_name,',',st.state_name,'-',cs.`pincode`,',',c.country_name,',',cs.`contact_number`) as billingaddress
FROM `s_salesorder_hdr_t` so
left join s_salesorder_lines_t sol on(so.sales_hdr_id=sol.sales_hdr_id)
left join i_pricelist_hdr_t pr on(so.pricelist_id=pr.pricelist_hdr_id)
left join s_salesperson_t sp on(

    sp.salesperson_id=so.`salesperson_id`
    )
    left join m_customers_t ct on (
    ct.customer_id=so.`ship_to_customer_id`
    )left join m_products_t prd on(prd.product_id=sol.product_id) left join
	`m_customer_sites_t` cs  on(cs.customer_site_id=so.bill_to_address_id) left join m_countries_t c ON(cs.`country`=c.country_id) left join m_states_t st on(st.state_id=cs.`state`) left join m_cities_t mc ON(mc.city_id=cs.city)
    left join s_quote_hdr_t sq on (
    sq.quote_hdr_id=so.`ar_quote_hdr_id`
    ) where 1=1 and so.order_status_id='APPROVED' $wh ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = \DB::select( $SQL );


	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

	echo json_encode($responce);

}	

	public function sopickprodqohdata()
	{

		$wh='';
		$search_table=[];
		$search_table[]='m_products_t';
		$search_table[]='s_salesorder_hdr_t';
		$search_table[]='m_customers_t';
		$search_table[]='i_pricelist_hdr_t';
		if($_GET['_search']=='true')
		{
			$wh=$this->jqgridsearch('i_qoh_detail_t',$_GET['filters'],$search_table);
		}

		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(i_qoh_detail_t.product_id) AS count FROM i_qoh_detail_t left join m_products_t on(i_qoh_detail_t.product_id=m_products_t.product_id) left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) LEFT JOIN s_salesorder_lines_t ON( s_salesorder_lines_t.product_id = m_products_t.product_id ) LEFT JOIN s_salesorder_hdr_t ON( s_salesorder_hdr_t.sales_hdr_id = s_salesorder_lines_t.sales_hdr_id ) LEFT JOIN m_customers_t ON(m_customers_t.customer_id = s_salesorder_hdr_t.ship_to_customer_id) LEFT JOIN i_pricelist_hdr_t ON(i_pricelist_hdr_t.pricelist_hdr_id = s_salesorder_hdr_t.pricelist_id) where 1=1 $wh");

		$count = $result[0]->count;
		if( $count > 0 && $limit > 0) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}

		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
			
		$SQL = "SELECT SUM(f.qty - f.qtyy) AS qty, f.product_id,s_salesorder_lines_t.sales_line_id,s_salesorder_hdr_t.sales_order_no,m_customers_t.customer_name,i_pricelist_hdr_t.pricelist_name,s_salesorder_hdr_t.pricelist_id,s_salesorder_hdr_t.ship_to_customer_id,s_salesorder_hdr_t.bill_to_address_id ,s_salesorder_hdr_t.sales_hdr_id, s_salesorder_lines_t.product_id as sales_prd, m_products_t.concatenated_product, m_product_groups_t.group_name FROM ( SELECT SUM(qoh_trx_qty) AS qty, 0 AS qtyy, product_id FROM i_qoh_detail_t GROUP BY product_id UNION ALL SELECT 0 AS qty, SUM(reserv_trx_qty) AS qtyy, product_id AS fdfd FROM i_reservation_detail_t GROUP BY product_id ) f LEFT JOIN m_products_t ON( m_products_t.product_id = f.product_id ) LEFT JOIN m_product_groups_t ON( m_products_t.product_group_id = m_product_groups_t.product_group_id ) LEFT JOIN s_salesorder_lines_t ON( s_salesorder_lines_t.product_id = m_products_t.product_id ) LEFT JOIN s_salesorder_hdr_t ON( s_salesorder_hdr_t.sales_hdr_id = s_salesorder_lines_t.sales_hdr_id ) LEFT JOIN m_customers_t ON(m_customers_t.customer_id = s_salesorder_hdr_t.ship_to_customer_id) LEFT JOIN i_pricelist_hdr_t ON(i_pricelist_hdr_t.pricelist_hdr_id = s_salesorder_hdr_t.pricelist_id) WHERE m_product_groups_t.group_name = 'FINISHED GOODS' AND s_salesorder_lines_t.product_id = m_products_t.product_id GROUP BY f.product_id ORDER BY $sidx $sord LIMIT $start , $limit";
		
		$result = \DB::select( $SQL );
		// dd($result);
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		echo json_encode($responce);

}



function getpickqty($sohdrid=null,$prdid=null){

		$solines=\DB::select("select * from s_salesorder_lines_t WHERE  sales_hdr_id in($sohdrid) and product_id in($prdid)");

		$su=$solines[0]->sales_hdr_id;

	$sql=\DB::select("select so.sales_hdr_id,so.sales_order_no,ps.remaining_qty,ps.so_qty, ps.pickoredr_qty as pickoredr_qty,ps.so_pickrelease_line_id,sl.invoiced_qty from s_salesorder_hdr_t so left join s_pickrelease_sub_lines_t ps on(ps.ar_sales_hdr_id=so.sales_hdr_id) left join s_salesorder_lines_t sl on(sl.sales_hdr_id=so.sales_hdr_id) where so.sales_hdr_id='$su'  and ps.product_id='$prdid' ");

	if(!empty($sql))
	{
			$b=explode(",",$sql[0]->pickoredr_qty);
	}
	$qty = 0;
		foreach ($solines as $key => $value)
		{

			$sql=\DB::select("select so.sales_hdr_id,so.sales_order_no,ps.remaining_qty,ps.so_qty, ps.pickoredr_qty as pickoredr_qty,ps.so_pickrelease_line_id,sl.invoiced_qty from s_salesorder_hdr_t so left join s_pickrelease_sub_lines_t ps on(ps.ar_sales_hdr_id=so.sales_hdr_id) left join s_salesorder_lines_t sl on(sl.sales_hdr_id=so.sales_hdr_id) where so.sales_hdr_id='$value->sales_hdr_id'");
			$a=explode(",",$sql[0]->pickoredr_qty);

				//$qty = $qty + $this->getPickedqty($sql[0]->sales_hdr_id,$prdid);
				$qty = $qty + $this->getPickedqty($sql[0]->sales_hdr_id,$prdid);
		
		}
	return $qty;
}
public function Picksublines($sohdrid=null,$prdid=null){
$index=$_GET['index'];
	//dd($prdid);
		$solines=\DB::select("select * from s_salesorder_lines_t WHERE  sales_hdr_id in($sohdrid) and product_id in($prdid)");

		$su=$solines[0]->sales_hdr_id;

		$sub=\DB::select("select pickoredr_qty from s_pickrelease_sub_lines_t where ar_sales_hdr_id='$su'");
	    $html = "<table>";
	    $html .= "<tr><thead style='background:#00224e;color:#FFF'><th style='width:100px;'>S.No</th><th style='width:150px;'>SO No</th><th style='width:150px;'>So Qty</th><th style='width:150px;'>Picked Qty</th><th style='width:150px;'>Invoiced Qty</th><th style='width:150px;'>Allocate Qty</th></thead></tr>";
		
	$i=0;
			$sql=\DB::select("select so.sales_hdr_id,so.sales_order_no,ps.remaining_qty,ps.so_qty, ps.pickoredr_qty as pickoredr_qty,ps.so_pickrelease_line_id,sl.invoiced_qty from s_salesorder_hdr_t so left join s_pickrelease_sub_lines_t ps on(ps.ar_sales_hdr_id=so.sales_hdr_id) left join s_salesorder_lines_t sl on(sl.sales_hdr_id=so.sales_hdr_id) where so.sales_hdr_id='$su'");
//dd($sql);	
/*
foreach($sql as $key1=>$val)
{
	$c[$key1] =(object)array();
	$c[$key1]=explode(',', $val->pickoredr_qty);
}
dd(array_flatten($c));
foreach ($c as $k=>$subArray) {
	$sumArray = array();
  foreach ($subArray as $id=>$value) {
    $sumArray[$id]+= $value;
  }
}
dd($sumArray);
			$b=explode(",",$sql[0]->pickoredr_qty);
*/

foreach($sql as $key1=>$val)
{
	$c[$key1] =(object)array();
	$c[$key1]=explode(',', $val->pickoredr_qty);
}

$newarr=array();
foreach($c as $key1=>$value)
{
  foreach($value as $key=>$secondValue)
   {
       if(!isset($newarr[$key1]))
        {
           $newarr[$key1]=0;
        }
				if (is_numeric($secondValue))
				{
				   $newarr[$key1]+= $secondValue;
				} elseif(is_numeric($secondValue)) {
					 $newarr[$key1]+= $secondValue;
  // do some error handling...
				}
      // $newarr[$key]+=$secondValue;
   }
}
	$sql=\DB::select("select so.sales_hdr_id,so.sales_order_no,ps.remaining_qty,ps.so_qty, ps.pickoredr_qty as pickoredr_qty,ps.so_pickrelease_line_id,sl.invoiced_qty from s_salesorder_hdr_t so left join s_pickrelease_sub_lines_t ps on(ps.ar_sales_hdr_id=so.sales_hdr_id) left join s_salesorder_lines_t sl on(sl.sales_hdr_id=so.sales_hdr_id) where so.sales_hdr_id='$su'  and ps.product_id='$prdid' ");
//return $sql;
	if(!empty($sql))
	{
			$b=explode(",",$sql[0]->pickoredr_qty);
	}
	///dd($solines);

	
	//dd($i);
	
		foreach ($solines as $key => $value)
		{
		
			//$l=$key;
	      $html .="<tr>";
		  $html .="<td>".($key+1)."</td>";
	//$i=$key+0;
			$sql=\DB::select("select so.sales_hdr_id,so.sales_order_no,ps.remaining_qty,ps.so_qty, ps.pickoredr_qty as pickoredr_qty,ps.so_pickrelease_line_id,sl.invoiced_qty from s_salesorder_hdr_t so left join s_pickrelease_sub_lines_t ps on(ps.ar_sales_hdr_id=so.sales_hdr_id) left join s_salesorder_lines_t sl on(sl.sales_hdr_id=so.sales_hdr_id) where so.sales_hdr_id='$value->sales_hdr_id'");
//return $sql;
			$a=explode(",",$sql[0]->pickoredr_qty);

//foreach()
			//$b=$a[1];
			//$sql=\DB::select("SELECT ar_sales_hdr_id,ar_sales_line_id, pickoredr_qty FROM s_pickrelease_sub_lines_t ORDER BY ar_sales_hdr_id")
			// $sql[] = '("'.mysql_real_escape_string($row['text']).'", '.$row['category_id'].')';

			$html .="<td style='padding:2px'>"."<input type='hidden' class='saleshdrid saleshdrid$index$key' form-control' value='".$sql[0]->sales_hdr_id."'>".$sql[0]->sales_order_no."</td>";
			$html .="<td style='padding:2px'><input type='hidden' class='qty' value=".$value->qty.">".$value->qty."</td>";
			if(!empty($sql))
			{
				//$html .="<td style='padding:2px'>".$newarr[$key]."</td>";
				$html .="<td style='padding:2px'><inputy type='hidden' class='picked_qty' value=".$this->getPickedqty($sql[0]->sales_hdr_id,$prdid).">".$this->getPickedqty($sql[0]->sales_hdr_id,$prdid)."</td>";
			}
			else
			{
				$html .="<td style='padding:2px'>"."0"."</td>";
			}
			$html .="<td style='padding:2px'><input type='hidden' class='invoiced_qty' value=".$this->getInvoicedqty($sql[0]->sales_hdr_id,$prdid).">".$this->getInvoicedqty($sql[0]->sales_hdr_id,$prdid)."</td>";
			$html .="<td style='padding:2px'><input type='text' class='allocateqty form-control' value=''></td>";
			$html .="</tr>";


			/*
		$this->data['sublinedata'][$key]=(object) array();
		$this->data['sublinedata'][$key]->line_no =$key+1;
		$this->data['sublinedata'][$key]->product_id = $this->jCombo('m_products_t','product_id','concatenated_product',$value->product_id);
		$this->data['sublinedata'][$key]->so_pickrelease_hdr_id ='';
		$this->data['sublinedata'][$key]->so_pickrelease_line_id ='';
		$this->data['sublinedata'][$key]->ar_sales_hdr_id =$value->sales_hdr_id;
		$this->data['sublinedata'][$key]->ar_sales_line_id =$value->sales_line_id;
		$this->data['sublinedata'][$key]->so_qty =$value->soqty;
		$this->data['sublinedata'][$key]->remaining_qty =$value->remaining_qty;
		*/

		
		}
	
	$html .="</table>";
	//$this->data['data']=$solines;
	return $html;
}
	
function getPickedqty($soid,$pdt_id)
{
	$sql = \DB::table('s_pickrelease_sub_lines_t')->where('product_id',$pdt_id)->get();
	$totqty=0;
	foreach($sql as $key=>$value)
	{
		 $soids=explode(",", $value->ar_sales_hdr_id);			
		 $qtys=explode(",", $value->pickoredr_qty);		
		 foreach($soids as $key1=>$val)
		 {
			 if($soid == $val)
			 {
				 $totqty=$totqty+$qtys[$key1];
			 }
		 }
	}
	return ($totqty);
}
	
function getInvoicedqty($soid,$pdt_id)
{
	
	$sql = \DB::table('s_pickrelease_sub_lines_t')->where('product_id',$pdt_id)->get();
	$totqty=0;
	foreach($sql as $key=>$value)
	{
		 $soids=explode(",", $value->ar_sales_hdr_id);		
		 $invqty = \DB::table('s_invoice_hdr_t')->whereIn('SOURCE',['PICK ORDER','SALES ORDER','DISPATCH'])->get();
		 //dd($invqty);
		 $qtys=0;
		if($invqty->isNotEmpty())
		{
			foreach($invqty as $ke=>$valu)
			{
			 foreach($soids as $key1=>$val)
			 {
				 if($valu->source == "SALES ORDER")
				 {
					 $orderno = \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id',$valu->invoice_hdr_id)->get();
					 if($orderno->isNotEmpty())
					 {
						 if($soid == $orderno[0]->reference_source_id)
						 {
						 $invdqty = \DB::table('s_invoice_lines_t')->where('invoice_hdr_id',$valu->invoice_hdr_id)->where('product_id',$pdt_id)->get();

						 if($invdqty->isNotEmpty())
						 {
							 $qtys = $invdqty[0]->qty;
						 }
						 else
						 {
							$qtys =0; 
						 }
							 $totqty=$qtys;
						 }
						 
					 }
					 else
					 {
						
						 $totqty=$qtys;
					 }
				 
				 }
				 if($valu->source == "PICK ORDER")
				 {
					
					 $totqty=$qtys;
				 }
				 if($valu->source == "DISPATCH")
				 {
					
					 $totqty=$qtys;
				 }
				 else
				 {
					$totqty=$qtys; 
				 }
			 }
			}
		}
		else
		{
		 	$totqty=0;
		}
	}
	
	return ($totqty);
}

public function Siteaddress($customerid=null){
	$sql=\DB::Select("select customer_site_id,customer_id,customer_site_name,site_type,address,city,state,country,pincode,contact_number from m_customer_sites_t where customer_id=".$customerid);

}

	public function pickorderdata()
	{

	$wh='';
	if($_GET['_search']=='true')
	{
		$wh=$this->jqgridsearch('s_pickrelease_hdr_t',$_GET['filters']);
	}
	$page = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx = $_GET['sidx'];
	$sord = $_GET['sord'];
	if(!$sidx) $sidx =1;

	$result = \DB::select("SELECT COUNT(so_pickrelease_hdr_id) AS count FROM s_pickrelease_hdr_t where 1=1 $wh");
	$count = $result[0]->count;
	if( $count > 0 && $limit > 0) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}

	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit;
	if($start <0) $start = 0;

    $SQL="SELECT
    s_pickrelease_hdr_t.so_pickrelease_hdr_id,
    s_pickrelease_hdr_t.release_source,
    s_pickrelease_hdr_t.release_date,
    s_pickrelease_hdr_t.release_status,
    i_pricelist_hdr_t.pricelist_name AS pricelist_id,
    m_customers_t.customer_name AS ship_to_customer_id,
    tb_users.username AS preparer_id,
    concat(cs.`customer_site_name`,',',cs.`address`,',',mc.city_name,',',st.state_name,'-',cs.`pincode`,',',c.country_name,',',cs.`contact_number`) as bill_to_address_id
FROM
    s_pickrelease_hdr_t
LEFT JOIN i_pricelist_hdr_t ON
    (
        i_pricelist_hdr_t.pricelist_hdr_id = s_pickrelease_hdr_t.pricelist_id
    )
LEFT JOIN m_customers_t ON
    (
        m_customers_t.customer_id = s_pickrelease_hdr_t.ship_to_customer_id
    )
LEFT JOIN tb_users ON
    (
        tb_users.id = s_pickrelease_hdr_t.preparer_id
    )
    LEFT JOIN `m_customer_sites_t` cs ON
    (
        cs.customer_site_id = s_pickrelease_hdr_t.ship_to_customer_id
    )
LEFT JOIN m_countries_t c ON
    (cs.`country` = c.country_id)
LEFT JOIN m_states_t st ON
    (st.state_id = cs.`state`)
LEFT JOIN m_cities_t mc ON
    (mc.city_id = cs.city)
WHERE
    1 = 1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
   
	$result = \DB::select( $SQL );

	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

	echo json_encode($responce);

}

	public function pickordercheck($id=null)
	{
		$exp_count=explode(',',$id);
		foreach($exp_count as $k2=>$v2){
		$data=\DB::SELECT("SELECT * FROM s_pickrelease_sub_lines_t where ar_sales_hdr_id like '%,".$v2.",%' OR ar_sales_hdr_id LIKE '%,".$v2."' OR ar_sales_hdr_id LIKE '".$v2.",%' OR ar_sales_hdr_id='".$v2."' group by product_id");
		
		$j=0;
		if(count($data)>0){
		foreach($data as $key=>$value)
		{   
			$total=0;
		$overalltotal=0;
			$product_id=$value->product_id;
			$data_product=\DB::SELECT("SELECT * FROM s_salesorder_lines_t where sales_hdr_id=".$v2." and product_id=".$product_id);
			$qty=$data_product[0]->qty;
			$data_qty=\DB::SELECT("SELECT * FROM s_pickrelease_sub_lines_t where( ar_sales_hdr_id like '%,".$v2.",%' OR ar_sales_hdr_id LIKE '%,".$v2."' OR ar_sales_hdr_id LIKE '".$v2.",%' OR ar_sales_hdr_id='".$v2."') and s_pickrelease_sub_lines_t.product_id=".$product_id);

			
            foreach($data_qty as $k=>$v){
            	$sales_id=explode(',',$v->ar_sales_hdr_id);
			    $pick_qty=explode(',',$v->pickoredr_qty);
			  foreach($sales_id as $k1=>$v1){
              if($v1==$v2)
              {
                   $total=$total+$pick_qty[$k1];
              }
                }
            }
          
            $overalltotal=$qty-$total;
           
            if($overalltotal==0){
              return 1;
            }
            else{
             return 0; 
            }
		}
	}
		
			
		}
		
		
		
	}
}
