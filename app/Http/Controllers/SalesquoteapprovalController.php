<?php

namespace App\Http\Controllers;

use App\Salesquoteapproval;
use App\Soquote;
use App\Soquotelines;
use Illuminate\Http\Request;

class SalesquoteapprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public $module="soquote";
	public function __construct()
	{
		$this->data=array();
		$this->table="s_quote_hdr_t";
		$this->subtable="s_quote_lines_t";
		$this->pageModule="soquote";
		$this->model=new Soquote;
		$this->submodel=new Soquotelines;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
                $this->data['urlmenu']=$this->indexs(); 
	}
    public function index()
    {
		$this->data['cusnameopt']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
       	        return view("Salesquoteapproval.table",$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null,$quotetype=null)
    {
        $this->data['id'] = $id;
		$table = \DB::table('s_quote_hdr_t')->where('quote_hdr_id',$id)->get();
		$this->data['row'] = $table[0];
		$tablelines = \DB::table('s_quote_lines_t')->where('quote_hdr_id',$id)->get();
		$this->data['linedata'] = $tablelines;

		$this->data['customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_name',$table[0]->customer_id);
		$this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',$table[0]->organization_id);
		$this->data['project_id'] = $this->jCombo('m_projects_t','project_id','project_name',$table[0]->project_id);
                $this->data['quote_pricelist_id'] = $this->jCombo('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name',$table[0]->project_id);
		$this->data['salesperson_id'] = $this->jCombo('s_salesperson_t','salesperson_id','salesperson_name',$table[0]->salesperson_id);
		$this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product','');
                $this->data['tax_group_id'] = '';
		$this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
		$this->data['cusnameopt']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');

		foreach ($this->data['linedata'] as $key => $value)
		{
			$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product',$value->product_id);
			$this->data['linedata'][$key]->uomcode_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);
			$this->data['linedata'][$key]->tax_group_id =  $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$value->tax_group_id);
		}
		return view("Salesquoteapproval.form",$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
		//dd($_GET['approve']);
		\DB::table('s_quote_hdr_t')->where('quote_hdr_id', $_POST['quote_hdr_id'])->update(['quote_status' => $_GET['approve'],'remarks'=>$_POST['remarks']]);
        $status['message'] = $_GET['approve'].' Successfully';
       
	return response()->json(array('status' => 'success', 'message' => $status['message'],'id' => $_POST['quote_hdr_id']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Salesquoteapproval  $salesquoteapproval
     * @return \Illuminate\Http\Response
     */
    public function show(Salesquoteapproval $salesquoteapproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Salesquoteapproval  $salesquoteapproval
     * @return \Illuminate\Http\Response
     */
    public function edit(Salesquoteapproval $salesquoteapproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salesquoteapproval  $salesquoteapproval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salesquoteapproval $salesquoteapproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Salesquoteapproval  $salesquoteapproval
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salesquoteapproval $salesquoteapproval)
    {
        //
    }
	
		public function soquoteapprovalgriddata()
	{
		$wh='';
		if($_GET['_search']=='true')
		{
		$wh=$this->jqgridsearch('s_quote_hdr_t',$_GET['filters']);
		}

		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(quote_hdr_id) AS count FROM s_quote_hdr_t where 1=1 $wh");
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
		$SQL = "SELECT s_quote_hdr_t.quote_hdr_id,s_quote_hdr_t.quote_no,s_quote_hdr_t.quote_name,s_quote_hdr_t.quote_date,s_quote_hdr_t.quote_type,s_quote_hdr_t.quote_pricelist_id,s_quote_hdr_t.quote_status,s_quote_hdr_t.salesperson_id,s_quote_hdr_t.remarks,m_customers_t.customer_name as customer_id FROM s_quote_hdr_t  left join m_customers_t on(m_customers_t.customer_id=s_quote_hdr_t.customer_id) where 1=1 and s_quote_hdr_t.quote_status='INITIATED' $wh ORDER BY $sidx $sord LIMIT $start , $limit";
			
			$download_SQL = "SELECT s_quote_hdr_t.quote_hdr_id,s_quote_hdr_t.quote_no,s_quote_hdr_t.quote_name,s_quote_hdr_t.quote_date,s_quote_hdr_t.quote_type,s_quote_hdr_t.quote_pricelist_id,s_quote_hdr_t.quote_status,s_quote_hdr_t.salesperson_id,s_quote_hdr_t.remarks,m_customers_t.customer_name as customer_id FROM s_quote_hdr_t  left join m_customers_t on(m_customers_t.customer_id=s_quote_hdr_t.customer_id) where 1=1 and s_quote_hdr_t.quote_status='INITIATED' $wh ORDER BY $sidx $sord";
			
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
