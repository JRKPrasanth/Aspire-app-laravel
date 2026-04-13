<?php

namespace App\Http\Controllers;

use App\Salesorderapproval;
use App\Soorder;
use App\Soorderlines;
use Illuminate\Http\Request;
use DB;

class SalesorderapprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct()
	{

		$this->data=array();
		$this->table="s_salesorder_hdr_t";
		$this->subtable="s_salesorder_lines_t";
		$this->pageModule="Salesorderapproval";
		$this->model=new Soorder;
		$this->submodel=new Soorderlines;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';	
                $this->data['urlmenu']=$this->indexs(); 

	}
    public function index()
    {
       	$this->data['cusnameopt']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
		return view("Salesorderapproval.table",$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
      $row=$this->model::find($id);
		
		if(isset($_REQUEST['tp']))
		{
		$this->data['row']['order_type_id']=$_REQUEST['tp'];
		}
		else
		{
		$this->data['row']['order_type_id']='STANDARD';	
		}

		$this->data['row']['sales_hdr_id']='';
		$this->data['row']['sales_order_no']='';
		$this->data['row']['sales_order_date']='';
		
		$this->data['row']['order_status_id']='1';
		$this->data['row']['ar_quote_hdr_id']='';
		$this->data['row']['customer_id']='';
		$this->data['row']['customer_po_number']='';
		$this->data['row']['contact_person']='';
		$this->data['row']['contact_number']='';
		$this->data['row']['so_ref_no']='';
		$this->data['row']['salesperson_id']='';
		$this->data['row']['currency_code_id']='';
		$this->data['row']['company_id']='';
		$this->data['row']['organization_id']='';
		$this->data['row']['remarks']='';
		$this->data['row']['order_sub_total']='';
		$this->data['row']['order_tax']='';
		$this->data['row']['order_total']='';

		if(!empty($row))
		{
			$this->data['row']['sales_hdr_id']=$row->sales_hdr_id;
			$this->data['row']['sales_order_no']=$row->sales_order_no;
			$this->data['row']['sales_order_date']=$row->sales_order_date;
			$this->data['row']['order_type_id']=$row->order_type_id;
			$this->data['row']['order_status_id']=$row->order_status_id;
			$this->data['row']['ar_quote_hdr_id']=$row->ar_quote_hdr_id;
			$this->data['row']['customer_id']=$row->customer_id;
			$this->data['row']['customer_po_number']=$row->customer_po_number;
			$this->data['row']['contact_person']=$row->contact_person;
			$this->data['row']['contact_number']=$row->contact_number;
			$this->data['row']['so_ref_no']=$row->so_ref_no;
			$this->data['row']['salesperson_id']=$row->salesperson_id;
			$this->data['row']['currency_code_id']=$row->currency_code_id;
			$this->data['row']['company_id']=$row->company_id;
			$this->data['row']['organization_id']=$row->organization_id;
			$this->data['row']['remarks']=$row->remarks;
			$this->data['row']['order_sub_total']=$row->order_sub_total;
			$this->data['row']['order_tax']=$row->order_tax;
			$this->data['row']['order_total']=$row->order_total;
		}		
			
		
	//dd($this->data);	
		
		
		$this->data['subgrid']=$this->submodel->subgridRead($id);
		
		$this->data['salesperson']=$this->jCombo('s_salesperson_t','salesperson_id','salesperson_name','');
		$this->data['customer']=$this->jCombo('m_customers_t','customer_id','customer_name','');
		$this->data['currency']=$this->jCombo('m_currency_t','currency_id','currency_code','');
		$this->data['organization']=$this->jCombo('m_organizations_t','organization_id','organization_name','');
		$this->data['product']=$this->jCombo('m_products_t','product_id','concatenated_product','');
		$this->data['uom']=$this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
		$this->data['prdgrpopt']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
		
		return view('Salesorderapproval.form',$this->data);
    }

   
    public function save(Request $request)
    {

		
                \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $_POST['sales_hdr_id'])->update(['order_status_id' => $_GET['approve'],'remarks'=>$_POST['remarks']]);
		$sql = \DB::select("select * from s_salesorder_lines_t where sales_hdr_id ='".$_POST['sales_hdr_id']."'");
		
		foreach($sql as $key=>$value)
                {
                    $trsnsType=\DB::table('m_transaction_types_t')->where('transaction_type_code','SO APPROVE RESERVE')->get();
                    $trsnsType= json_decode( json_encode($trsnsType),true);
                    $data['trx_source_type_id']=$trsnsType[0]['transaction_type_id'];
                    $data['trx_action_id']=$trsnsType[0]['transaction_type_id'];
                    $data['trx_type_id']=$trsnsType[0]['transaction_type_id'];
                    $data['product_id']= $value->product_id;
                    // $prdid=$this->Productdata($value->item_name,'name');
                    //$data['product_id']= $prdid[0]->product_id;
                    //$subinvid=$this->Subinventorydata($value->subinventory_name,'name');
                    //$data['subinventory_id']= $subinvid[0]->subinventory_id;
                    //$locid=$this->Locatordata($value->locator_code,'name');
                    //$data['locator_id']= $locid[0]->sublocator_id;
                    //
                    $data['trx_qty']=-$value->qty;
                    $data['trx_cost']= $value->line_total;
                    $data['trx_uom']=$value->uom_code_id;
                    //$id = \DB::table('m_material_trx_t')->insertGetId($data);	

                    try
                    {

                        $id = \DB::table('m_material_trx_t')->insertGetId($data);					 

                        $QOHdata['product_id']=  $value->product_id;

                        $QOHdata['reserv_trx_qty']=-$value->qty;
                        $QOHdata['reserv_uom_code_id']=$value->uom_code_id;
                        $QOHdata['organization_id']='';
                        $QOHdata['create_trx_id']= $id;
                        \DB::table('i_reservation_detail_t')->insert($QOHdata);
                        // add the additoal input fields like type, creadted by
                        // update the interface table with LOADED
                        $status['status']='success';
                        $status['message']='Stock Moved Sucessfully';

                    }

                    catch(\Illuminate\Database\QueryException $e)
                    {

                        $message = explode('(', $e->getMessage());
                        $dbCode = rtrim($message[0], ']');
                        $dbCode = trim($dbCode, '[');
                        $status['status']='error';
                        $status['message']=$dbCode;

                        return $status;
                    }
		}
        $status['message'] = $_GET['approve'].' Successfully';
    //   dd
		
		
		
	return response()->json(array('status' => 'success', 'message' => $status['message'],'id' => $_POST['sales_hdr_id']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Salesorderapproval  $salesorderapproval
     * @return \Illuminate\Http\Response
     */
	
	public function Subinventorydata($value=null,$type=null){

if($type=='name'){
$cond=" and subinventory_name='".$value."'";	
}else{
$cond="";
}
$prdsql=\DB::select("select count(*)  as cnt,subinventory_id from m_subinventory_t where 1=1 $cond");	
return 	$prdsql;
}	
	
public function Locatordata($value=null,$type=null){

if($type=='name'){
$cond=" and locator_code='".$value."'";	
}else{
$cond="";
}
$prdsql=\DB::select("select count(*)  as cnt,subinventory_id,sublocator_id from m_sublocators_t where 1=1 $cond");	
return 	$prdsql;
}	
	
	
	
    public function show(Salesorderapproval $salesorderapproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Salesorderapproval  $salesorderapproval
     * @return \Illuminate\Http\Response
     */
    public function edit(Salesorderapproval $salesorderapproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salesorderapproval  $salesorderapproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salesorderapproval $salesorderapproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Salesorderapproval  $salesorderapproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salesorderapproval $salesorderapproval)
    {
        //
    }
	
	public function soorderapprovedata()
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
so.order_status_id,
so.`contact_number` as contact_number,
so.`contact_person`,
so.remarks,
sp.salesperson_name as salesperson_id,
ct.customer_name as customer_id,
sq.quote_no as ar_quote_hdr_id

FROM `s_salesorder_hdr_t` so
left join s_salesperson_t sp on(

    sp.salesperson_id=so.`salesperson_id`
    )
    left join m_customers_t ct on (
    ct.customer_id=so.`customer_id`
    )
    left join s_quote_hdr_t sq on (
    sq.quote_hdr_id=so.`ar_quote_hdr_id`
    ) where 1=1 and so.order_status_id='INITIATED' $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		
		$download_SQL = "SELECT
so.sales_hdr_id,
so.`sales_order_no`,
so.`sales_order_date`,
so.`order_type_id`,
so.order_status_id,
so.`contact_number` as contact_number,
so.`contact_person`,
so.remarks,
sp.salesperson_name as salesperson_id,
ct.customer_name as customer_id,
sq.quote_no as ar_quote_hdr_id

FROM `s_salesorder_hdr_t` so
left join s_salesperson_t sp on(

    sp.salesperson_id=so.`salesperson_id`
    )
    left join m_customers_t ct on (
    ct.customer_id=so.`customer_id`
    )
    left join s_quote_hdr_t sq on (
    sq.quote_hdr_id=so.`ar_quote_hdr_id`
    ) where 1=1 and so.order_status_id='INITIATED' $wh ORDER BY $sidx $sord";
		
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
}
