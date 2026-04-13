<?php

namespace App\Http\Controllers;

use App\Soorder;
use App\Soorderlines;
use App\Socancellation;
use Illuminate\Http\Request;

class SocancellationController extends Controller
{
   
	 public function __construct(){
	 $this->data=array();
		$this->table="s_salesorder_hdr_t";
		$this->subtable="s_salesorder_lines_t";
		$this->pageModule="socancellation";
		$this->model=new Soorder;
		$this->submodel=new Soorderlines;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
                $this->data['urlmenu']=$this->indexs(); 
     }
    public function index()
    {
        return view ("socancellation.table");
    }

    
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
		
		//dd($this->submodel->subgridRead($id));
		$this->data['salesperson']=$this->jCombo('s_salesperson_t','salesperson_id','salesperson_name','');
		$this->data['customer']=$this->jCombo('m_customers_t','customer_id','customer_name','');
		$this->data['currency']=$this->jCombo('m_currency_t','currency_id','currency_code','');
		$this->data['organization']=$this->jCombo('m_organizations_t','organization_id','organization_name','');
		$this->data['product']=$this->jCombo('m_products_t','product_id','concatenated_product','');
		$this->data['uom']=$this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
		$this->data['prdgrpopt']=$this->jqgridselect('m_product_groups_t','product_group_id','group_name');
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
		
		//$pickqty=array();
		$rowdata = $this->submodel->subgridRead($id);
		//dd($rowdata['rowData']);
		foreach($rowdata['rowData'] as $key=>$value){
			$sql=\DB::select("SELECT  
`s_salesorder_hdr_t`.`sales_hdr_id`,
sol.sales_line_id,
prl.product_id,
prl.picked_qty
FROM `s_salesorder_hdr_t` 
left join s_salesorder_lines_t sol on(
sol.sales_hdr_id=s_salesorder_hdr_t.`sales_hdr_id`
)
left join s_pickrelease_lines_t prl on(
prl.ar_sales_line_id=sol.sales_line_id
) where 
prl.ar_sales_line_id='".$value['sales_line_id']."'");
			//dd($value);
			$this->data['subgrid']['rowData'][$key]=(object) array();
		$this->data['subgrid']['rowData'][$key]->qty=$sql[0]->picked_qty;
		$this->data['subgrid']['rowData'][$key]->picked_qty=1;
		$pickqty[$key] = $sql[0]->picked_qty;
		}
		$this->data['pickqty'] = $pickqty;
		$this->data['subgrid']=$this->submodel->subgridRead($id);
		//dd($this->data['subgrid']);
		//dd($this->data);
        return view ("socancellation.form",$this->data);
    }

   
   public function save(Request $request)
    {
	 
	   if($_POST['picked_qty']==0){
	   
        \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $_POST['sales_hdr_id'])->update(['order_status_id' => $_GET['cancelled'],'remarks'=>$_POST['remarks']]);
        $status['message'] = $_GET['cancelled'].' Successfully';
	   }
	   else {
	       \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $_POST['sales_hdr_id'])->update(['order_status_id' => "Partially Cancelled",'remarks'=>$_POST['remarks']]);
        $status['message'] = $_GET['cancelled'].' Successfully';
	   }
	    
	return response()->json(array('status' => 'success', 'message' => $status['message'],'id' => $_POST['sales_hdr_id']));
    }
    public function show(Socancellation $socancellation)
    {
        //
    }

   
    public function edit(Socancellation $socancellation)
    {
        //
    }

   
    public function update(Request $request, Socancellation $socancellation)
    {
        //
    }

   
    public function destroy(Socancellation $socancellation)
    {
        //
    }
	
	public function socancellationdata()
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
s_salesorder_hdr_t.sales_hdr_id,
s_salesorder_hdr_t.`sales_order_no`,
s_salesorder_hdr_t.`sales_order_date`,
s_salesorder_hdr_t.`order_type_id`,
s_salesorder_hdr_t.order_status_id,
s_salesorder_hdr_t.`contact_number` as contact_number,
s_salesorder_hdr_t.`contact_person`,
sp.salesperson_name as salesperson_id,
ct.customer_name as customer_id,
sq.quote_no as ar_quote_hdr_id

FROM `s_salesorder_hdr_t`
left join s_salesperson_t sp on(

    sp.salesperson_id=s_salesorder_hdr_t.`salesperson_id`
    )
    left join m_customers_t ct on (
    ct.customer_id=s_salesorder_hdr_t.`customer_id`
    )
    left join s_quote_hdr_t sq on (
    sq.quote_hdr_id=s_salesorder_hdr_t.`ar_quote_hdr_id`
    ) where 1=1 and s_salesorder_hdr_t.order_status_id='INITIATED' $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		
		$download_SQL = "SELECT
s_salesorder_hdr_t.sales_hdr_id,
s_salesorder_hdr_t.`sales_order_no`,
s_salesorder_hdr_t.`sales_order_date`,
s_salesorder_hdr_t.`order_type_id`,
s_salesorder_hdr_t.order_status_id,
s_salesorder_hdr_t.`contact_number` as contact_number,
s_salesorder_hdr_t.`contact_person`,
sp.salesperson_name as salesperson_id,
ct.customer_name as customer_id,
sq.quote_no as ar_quote_hdr_id

FROM `s_salesorder_hdr_t`
left join s_salesperson_t sp on(

    sp.salesperson_id=s_salesorder_hdr_t.`salesperson_id`
    )
    left join m_customers_t ct on (
    ct.customer_id=s_salesorder_hdr_t.`customer_id`
    )
    left join s_quote_hdr_t sq on (
    sq.quote_hdr_id=s_salesorder_hdr_t.`ar_quote_hdr_id`
    ) where 1=1 and s_salesorder_hdr_t.order_status_id='INITIATED' $wh ORDER BY $sidx $sord";
		
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
