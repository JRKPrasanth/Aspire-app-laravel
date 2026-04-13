<?php

namespace App\Http\Controllers;

use App\Paymentbatches;
use App\Paymentbatcheslines;
use Illuminate\Http\Request;

class PaymentbatchesController extends Controller
{
     public function __construct() {
        $this->data = array();
        $this->table = "p_payment_batches_hdr_t";
        $this->subtable = "p_payment_batches_lines_t";
        $this->pageModule = "paymentbatches";
        $this->model = new Paymentbatches;
        $this->submodel = new Paymentbatcheslines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'paymentbatches',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->modelname = new Paymentbatches();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['urlmenu']=$this->indexs(); 
    }
    
    public function index()
    {
         $this->data['opt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
      $table = \DB::table('p_payment_batches_hdr_t')->get();
       $this->data['datas'] = $table;
       return view('paymentbatches.table',$this->data);
    }
    
     public function getPaymentbatchesData() {
        $wh = '';
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearch('p_payment_batches_hdr_t', $_GET['filters']);
        }
		 
		$loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');
		$groupname=\Session::get('groupname');
		if($groupname=='Superadmin' || $groupname=='Admin'){
		$wh.='and p_payment_batches_hdr_t.company_id='.$compy;	
		}else{
			$wh.='and p_payment_batches_hdr_t.company_id='.$compy.' and p_payment_batches_hdr_t.location_id='.$loc;		
		} 
		 
		 
		 
		 
		 
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(p_payment_batches_hdr_t.payment_batches_hdr_id) AS count FROM p_payment_batches_hdr_t where 1=1 $wh");
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
                p_payment_batches_hdr_t.payment_batches_hdr_id,
                p_payment_batches_hdr_t.payment_batch_name,
                p_payment_batches_hdr_t.batch_date,
                p_payment_batches_hdr_t.payment_batch_status,
                m_supplier_t.supplier_name as supplier_id 
                FROM p_payment_batches_hdr_t join m_supplier_t on(m_supplier_t.supplier_id=p_payment_batches_hdr_t.supplier_id) where 1=1  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		 
        //dd($SQL);
        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }
  
     public function create($id=null)
    {
		
		if($id ==null)
		{
		$this->modelname = new Paymentbatches();
		$this->data['row']= (object)array();
		$table = $this->modelname->getTableColumns();
		foreach($table as $key=>$val)
		{		
		$this->data['row']->$val='';
		}
                $this->data['row']->batch_date = date('Y-m-d');
                $this->data['row']->payment_batch_number  = date('Y-m-d');
		$this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name','');
		$this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t','supplier_site_id','supplier_site_name','');
               // dd($this->data['supplier_site_id']);
		$this->data['organization_id'] = $this->jCombologin('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
		$this->data['po_invoice_id'] = $this->jCombo('p_po_invoice_hdr_t','po_invoice_id','bill_number','');
                $this->data['supnameopt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
                $this->data['suptypeopt'] = $this->jqgridselect('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name');
                $this->data['country'] = $this->jqgridselectlogin('m_countries_t', 'country_id', 'country_name');
                $this->data['state'] = $this->jqgridselectlogin('m_states_t', 'state_id', 'state_name');
                $this->data['city'] = $this->jqgridselectlogin('m_cities_t', 'city_id', 'city_name');

		}
		else
		{		
		$row = \DB::table('p_payment_batches_hdr_t')->where('payment_batches_hdr_id',$id)->get();
		$this->data['row'] = $row[0];
		$this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name',$row[0]->supplier_id);
                $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t','supplier_site_id','supplier_site_name',$row[0]->suppliersite_id);
		$this->data['organization_id'] = $this->jCombologin('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
                $this->data['supnameopt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
            $this->data['suptypeopt'] = $this->jqgridselect('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name');
            $this->data['country'] = $this->jqgridselectlogin('m_countries_t', 'country_id', 'country_name');
            $this->data['state'] = $this->jqgridselectlogin('m_states_t', 'state_id', 'state_name');
            $this->data['city'] = $this->jqgridselectlogin('m_cities_t', 'city_id', 'city_name');
		}
		$tablelines = \DB::table('p_payment_batches_lines_t')->where('payment_batches_hdr_id',$id)->get();
		$this->data['linedata'] = $tablelines;
		if(count($this->data['linedata']) >= 1)
		{
		foreach ($this->data['linedata'] as $key => $value)
		{
	          $this->data['linedata'][$key]->po_invoice_id = $this->jCombo('p_po_invoice_hdr_t','po_invoice_id','bill_number',$value->po_invoice_id);
		}
		}
			
      return view('paymentbatches.form',$this->data);  
    }

  
   /* Karthigaa purpose for Save function */
    public function save(Request $request) {
//        dd($_POST);
        $id = '';
        $data = $this->validatePost($request->all(), $this->table, 'header');
        $lines_data = $this->validatePost($request->all(), $this->subtable, 'lines');
            /*karthigaa Purpose for Auto Number*/
                        if ($_POST['payment_batch_number'] =="")
				{
					$seqno=$this->Seqnoe('BAT-','p_payment_batches_hdr_t','','payment_batch_count');
					$data['payment_batch_number'] = $seqno[0];
                                        $data['payment_batch_count'] = $seqno[1];
				}
				else
				{
					$seqno[0] = $_POST['payment_batch_number'];
				}
			 /*End*/
        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Payment Batches Saved', 'id' => $id, 'lid' => $lid,'auto_no'=>$seqno[0]));
        } catch (\Illuminate\Database\QueryException$e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
   
   /*Karthigaa purpose for Display hdr & Lines View function*/
  public function show(request $request,$id=null)
    {
        if(isset($id))
        {
          $vdata=\DB::table('p_payment_batches_hdr_t')->leftjoin('m_supplier_t','m_supplier_t.supplier_id','=','p_payment_batches_hdr_t.supplier_id')
                                            ->leftjoin('m_organizations_t','m_organizations_t.organization_id','=','p_payment_batches_hdr_t.organization_id')
                                            ->leftjoin('m_supplier_sites_t','m_supplier_sites_t.supplier_site_id','=','p_payment_batches_hdr_t.suppliersite_id')
                                            ->where('payment_batches_hdr_id',$id)->get();

          $this->data['payment_batch_name']=$vdata[0]->payment_batch_name;
          $this->data['payment_batch_status']=$vdata[0]->payment_batch_status;
          $this->data['batch_date']=$vdata[0]->batch_date;
          $this->data['supplier_name']=$vdata[0]->supplier_name;
          $this->data['supplier_site_name']=$vdata[0]->supplier_site_name;
          $this->data['organization_name']=$vdata[0]->organization_name;

            $vlinesdata = \DB::table('p_payment_batches_lines_t')->leftjoin('p_po_invoice_hdr_t','p_po_invoice_hdr_t.po_invoice_id','=','p_payment_batches_lines_t.po_invoice_id')
                                                      ->where('p_payment_batches_lines_t.payment_batches_hdr_id',$id)->get();
            $this->data['vlinesdata']=$vlinesdata;
            $this->data['bill_number']=$vlinesdata[0]->bill_number;
            
            return view('paymentbatches.view',$this->data);

        }
    }

   
   public function delete($id=null){
		$count=0;
		$queryquote = \DB::table('p_payments_hdr_t')->where('payment_batches_hdr_id',$id)->count();
                //dd($queryquote);
		if($queryquote >=1){
		 $count++;
		}
		if($count <= 0){
			$query = \DB::table('p_payment_batches_hdr_t')->where('payment_batches_hdr_id',$id)->delete();
			$query = \DB::table('p_payment_batches_lines_t')->where('payment_batches_hdr_id',$id)->delete();
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
    public function getpoinvamount($id){
	$sql=\DB::table('p_po_invoice_hdr_t')->where('po_invoice_id',$id)->get();
	if($sql->isNotEmpty()){
		$amount = $sql[0]->invoice_grand_total;
               
	}
	else
	{
		$amount = '0.00';
	}
		return $amount;
	}
}
