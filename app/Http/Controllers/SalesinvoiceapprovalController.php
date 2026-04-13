<?php

namespace App\Http\Controllers;

use App\Salesinvoiceapproval;
use Illuminate\Http\Request;

class SalesinvoiceapprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['cusnameopt']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
        return view("Salesinvoiceapproval.table",$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null,$labid=null)
    {
        $this->data['id'] = $id;
                  $this->data['labour'] =$labid;
                  //dd($labid);
		$table = \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id',$id)->get();
		$this->data['row'] = $table[0];
//                dd($this->data['row']);
		$tablelines = \DB::table('s_invoice_lines_t')->where('invoice_hdr_id',$id)->get();
        	$this->data['linedata'] = $tablelines;
		$this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',$table[0]->organization_id);
		$this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_name',$table[0]->ship_to_customer_id);
                $this->data['ship_to_address_id'] = $this->jCombo('m_customer_sites_t','customer_site_id','customer_site_name',$table[0]->ship_to_address_id);
                $this->data['salesperson_id'] = $this->jCombo('s_salesperson_t','salesperson_id','salesperson_name',$table[0]->salesperson_id);
		$this->data['project_id'] = $this->jCombo('m_projects_t','project_id','project_name',$table[0]->project_id);
		$this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product','');
		$this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
                $this->data['tax_group_id'] = '';
		$this->data['cusnameopt']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
                $this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');
		foreach ($this->data['linedata'] as $key => $value)
		{
      $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name','');
		$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product',$value->product_id);
		$this->data['linedata'][$key]->uom_code_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
		}
		return view("Salesinvoiceapproval.form",$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $_POST['invoice_hdr_id'])->update(['invoice_status' => $_GET['approve'],'remarks'=>$_POST['remarks']]);
        $status['message'] = $_GET['approve'].' Successfully';

	return response()->json(array('status' => 'success', 'message' => $status['message'],'id' => $_POST['invoice_hdr_id']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Salesinvoiceapproval  $salesinvoiceapproval
     * @return \Illuminate\Http\Response
     */
    public function show(Salesinvoiceapproval $salesinvoiceapproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Salesinvoiceapproval  $salesinvoiceapproval
     * @return \Illuminate\Http\Response
     */
    public function edit(Salesinvoiceapproval $salesinvoiceapproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salesinvoiceapproval  $salesinvoiceapproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salesinvoiceapproval $salesinvoiceapproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Salesinvoiceapproval  $salesinvoiceapproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salesinvoiceapproval $salesinvoiceapproval)
    {
        //
    }

	 public function getSalesinvoiceapproveData(){
		$wh='';
		if($_GET['_search']=='true')
		{
		$wh=$this->jqgridsearch('s_invoice_hdr_t',$_GET['filters']);
		}

		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(invoice_hdr_id) AS count FROM s_invoice_hdr_t where 1=1 $wh");
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
iv.`invoice_hdr_id`,
iv.`invoice_type`,
iv.`invoice_number`,
iv.`invoice_date`,
iv.invoice_status,
iv.remarks,
cust.customer_name as ship_to_customer_id
FROM `s_invoice_hdr_t` iv
left join m_customers_t cust on(
cust.customer_id=iv.`ship_to_customer_id`
) where 1=1 and iv.invoice_status='INITIATED' $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		 
		 $download_SQL = "SELECT
iv.`invoice_hdr_id`,
iv.`invoice_type`,
iv.`invoice_number`,
iv.`invoice_date`,
iv.invoice_status,
iv.remarks,
cust.customer_name as ship_to_customer_id
FROM `s_invoice_hdr_t` iv
left join m_customers_t cust on(
cust.customer_id=iv.`ship_to_customer_id`
) where 1=1 and iv.invoice_status='INITIATED' $wh ORDER BY $sidx $sord";$result1 = \DB::select( $download_SQL );
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
