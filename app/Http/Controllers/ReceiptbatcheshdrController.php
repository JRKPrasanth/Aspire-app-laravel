<?php

namespace App\Http\Controllers;

use App\receiptbatcheshdr;
use App\receiptbatcheslines;
use Illuminate\Http\Request;
use App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use DB;
class ReceiptbatcheshdrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	
	public function __construct()
	{
		$this->data=array();
		//$this->model = new Salesinquiry();
		$this->model=new Receiptbatcheshdr;
		$this->submodel=new Receiptbatcheslines;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
		$this->data['pageModule']='receiptbatcheshdr';
		$this->table="s_receipt_batches_hdr_t";
		$this->subtable="s_receipt_batches_lines_t";
		$this->middleware('auth');
		$this->data['date_format'] = $this->dateform();
                $this->data['urlmenu']=$this->indexs(); 
	}
    public function index()
    {
       $table = \DB::table('s_receipt_batches_hdr_t')->get(); //dd($table);
		$this->data['datas'] = json_encode($table);
		
		$this->data['cusnameopt'] = $this->jqgridcustselect('m_customers_t','customer_id','customer_name','');
		return view('receiptbatcheshdr.table',$this->data);
    }

   
    public function create($id)
    {
		$this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','salesinquiry')->get();
		if($id =='0')
		{
		$this->modelname = new Receiptbatcheshdr();
		//$table = $this->modelname->getTableColumns();
		$this->data['row']= (object)array();
		$table = $this->modelname->getTableColumns();
		foreach($table as $key=>$val)
		{		
		$this->data['row']->$val='';
		}
                $this->data['row']->batch_date = date('Y-m-d');
		$this->data['customerid'] = $this->jCombo('m_customers_t','customer_id','customer_name','');
		$this->data['customer_siteid'] = $this->jCombo('m_customer_sites_t','customer_site_id','customer_site_name','');
		$this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
			
		$this->data['invoice_hdr_id'] = $this->jCombocomp('s_invoice_hdr_t','invoice_hdr_id','invoice_number','');
		}
		else
		{		
		$row = \DB::table('s_receipt_batches_hdr_t')->where('s_receipt_batch_hdr_id',$id)->get();
		$this->data['row'] = $row[0];
		$this->data['customerid'] = $this->jCombo('m_customers_t','customer_id','customer_name',$row[0]->customerid);
		$this->data['customer_siteid'] = $this->jCombo('m_customer_sites_t','customer_site_id','customer_site_name',$row[0]->customer_siteid);
		$this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
		}
		$tablelines = \DB::table('s_receipt_batches_lines_t')->where('s_receipt_batch_hdr_id',$id)->get();
		$this->data['linedata'] = $tablelines;
		if(count($this->data['linedata']) >= 1)
		{
		foreach ($this->data['linedata'] as $key => $value)
		{
			$this->data['linedata'][$key]->invoice_hdr_id = $this->jCombocomp('s_invoice_hdr_t','invoice_hdr_id','invoice_number',$value->invoice_hdr_id);
		}
		}
		$this->data['cusnameopt']=$this->jqgridselect('m_customers_t','customer_id','customer_name');
		$this->data['prdcatopt']=$this->jqgridselect('m_product_category_t','product_category_id','category_name');
		$this->data['prdnameopt']=$this->jqgridselect('m_products_t','product_id','concatenated_product');      
		$this->data['stateopt']=$this->jqgridselect('m_states_t','state_id','state_name');      
		$this->data['cityopt']=$this->jqgridselect('m_cities_t','city_id','city_name');      
		$this->data['countryopt']=$this->jqgridselect('m_countries_t','country_id','country_name');      
		$this->data['custypeopt']=$this->jqgridselect('m_customer_types_t','customer_type_id','customer_type'); 
		
			
      return view('receiptbatcheshdr.form',$this->data);  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
	public function save(Request $request)
	{
			$id='';
			$data = $this->validatePost($request->all(),$this->table,'header');				    
			$lines_data = $this->validatePost($request->all(),$this->subtable,'lines');	

			if ($_POST['savestatus'] == "INITIATED")
			{
				$inquiry_status = 'Saved Successfully';
			}
			else
			{
				$inquiry_status = $_POST['savestatus'].' Successfully';
			}
			//\DB::beginTransaction();
			try
			{
				//dd($data);
				 $id=$this->model->insertRow($data);
				 $lid=$this->submodel->subgridSave($lines_data,$id);
				
				// \DB::commit();
				 return response()->json(array('status' => 'success', 'message' => $inquiry_status,'id' => $id,'lid' => $lid,'auto_no'=>$id));
			}
			catch (\Illuminate\Database\QueryException $e)
			{
				 $message = explode('(', $e->getMessage());
				 $dbCode = rtrim($message[0], ']');
				 $dbCode = trim($dbCode, '[');
                                 dd($dbCode);
				 \DB::rollback();
				 return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}

	}
   
    /*Karthigaa purpose for Display hdr & Lines View function*/
  public function show(request $request,$id=null)
    {
        if(isset($id)){
          $vdata=\DB::table('s_receipt_batches_hdr_t')->leftjoin('m_customers_t','m_customers_t.customer_id','=','s_receipt_batches_hdr_t.customerid')
                                            ->leftjoin('m_organizations_t','m_organizations_t.organization_id','=','s_receipt_batches_hdr_t.organization_id')
                                            ->leftjoin('m_customer_sites_t','m_customer_sites_t.customer_site_id','=','s_receipt_batches_hdr_t.customer_siteid')
                                            ->where('s_receipt_batch_hdr_id',$id)->get();

          $this->data['receipt_batch_name']=$vdata[0]->receipt_batch_name;
          $this->data['receipt_batch_status']=$vdata[0]->receipt_batch_status;
          $this->data['batch_date']=$vdata[0]->batch_date;
          $this->data['customer_name']=$vdata[0]->customer_name;
          $this->data['customer_site_name']=$vdata[0]->customer_site_name;
          $this->data['organization_name']=$vdata[0]->organization_name;

            $vlinesdata = \DB::table('s_receipt_batches_lines_t')->leftjoin('s_invoice_hdr_t','s_invoice_hdr_t.invoice_hdr_id','=','s_receipt_batches_lines_t.invoice_hdr_id')
                                                      ->where('s_receipt_batches_lines_t.s_receipt_batch_hdr_id',$id)->get();
            $this->data['vlinesdata']=$vlinesdata;
            $this->data['invoice_number']=$vlinesdata[0]->invoice_number;
            
            return view('receiptbatcheshdr.view',$this->data);

        }
    }

     public function delete($id=null){
		$count=0;
		$queryquote = \DB::table('s_receipts_hdr_t')->where('receipt_batch_hdr_id',$id)->count();
                //dd($queryquote);
		if($queryquote >=1){
		 $count++;
		}
		if($count <= 0){
			$query = \DB::table('s_receipt_batches_hdr_t')->where('s_receipt_batch_hdr_id',$id)->delete();
			$query = \DB::table('s_receipt_batches_lines_t')->where('s_receipt_batch_hdr_id',$id)->delete();
			if($query){
				return 0;
			}
			else{
				return 1;
			}
		}
		else{
			return 2;
		}
	}
  
  
	
	public function getBatchData()
	{
		$wh='';
		if($_GET['_search']=='true')
		{
		$wh=$this->jqgridsearch('s_receipt_batches_hdr_t',$_GET['filters']);
		}
		$loc=\Session::get('location');
        $compy=\Session::get('companyid');
        
	    $groupname=\Session::get('groupname');
	
		if($groupname=='Superadmin' || $groupname=='Admin'){
		$wh.='and s_receipt_batches_hdr_t.company_id='.$compy;	
		}else{
			$wh.='and s_receipt_batches_hdr_t.company_id='.$compy.' and s_receipt_batches_hdr_t.location_id='.$loc;		
		}
		
		
		
		
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(s_receipt_batches_hdr_t.s_receipt_batch_hdr_id) AS count FROM s_receipt_batches_hdr_t where 1=1 $wh");
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
		s_receipt_batches_hdr_t.s_receipt_batch_hdr_id as s_receipt_batch_hdr_id,
		s_receipt_batches_hdr_t.receipt_batch_name,
		
		s_receipt_batches_hdr_t.customer_siteid,
		s_receipt_batches_hdr_t.receipt_batch_status,
		s_receipt_batches_hdr_t.organization_id,
		m_customers_t.customer_name as customerid
		FROM `s_receipt_batches_hdr_t` s_receipt_batches_hdr_t
		left join m_customers_t m_customers_t on(
		m_customers_t.customer_id=s_receipt_batches_hdr_t.`customerid`)

		where 1=1 $wh ORDER BY $sidx $sord  LIMIT $start , $limit";

		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
	public function getinvamount($id)
	{
	$sql=\DB::table('s_invoice_hdr_t')->where('invoice_hdr_id',$id)->get();	
	if($sql->isNotEmpty())
	{
		$amount = $sql[0]->invoice_grand_total;
	}
	else
	{
		$amount = '0.00';
	}
		return $amount;
	}
}
