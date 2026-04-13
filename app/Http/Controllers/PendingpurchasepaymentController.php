<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Pendingpurchasepayment;
use Illuminate\Http\Request;
use Session;

class PendingpurchasepaymentController extends Controller
{
      public $module="pendingpurchasepayment";
	public function __construct()
	{
		$this->data=array();
                 $this->data=array();
		$this->table="p_payments_t";
		$this->pageModule="pendingpurchasepayment";
                $this->model=new Pendingpurchasepayment();
		$this->model=new Pendingpurchasepayment;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
                $this->table="p_payments_t";
                $this->data['urlmenu']=$this->indexs(); 

	}
    public function index(){
        
		 if(isset($_GET['supplier_id']))
		 {
       $supplier_id = $_GET['supplier_id'];
       $days = $_GET['days'];
       \Session::put('supplier_id',$supplier_id);
       \Session::put('days',$days);
      $where = " and p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.supplier_id='$supplier_id'";
       if(isset($_GET['days']))
       {
         $currentdate=date('Y-m-d');
             $current_date="\"".$currentdate."\"";
        if($_GET['days']==2)
        {
          $where.=" and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)<16 and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)>0";
        }
        elseif($_GET['days']==3)
        {
          $where.=" and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)<31 and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)>15";
        }
        elseif($_GET['days']==4)
        {
          $where.=" and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)<45 and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)>30";
        }
        elseif($_GET['days']==5)
        {
          $where.=" and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)>45";
        }
        elseif($_GET['days']==1)
        {
          $where.=" and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)<=0";
        }

       }

		 }
		else
		{
			$where='';

		}

    	 $this->data['supplieropt'] = $this->jqgridselect('m_supplier_t', 'supplier_name', 'supplier_name');
         $this->data['bill_number']=$this->jqgridselect('p_po_invoice_hdr_t','bill_number','bill_number');

		  $org=\Session::get('organization');
        $loc="1";
        $compy=\Session::get('companyid');

               $this->data['balance_amount_sum'] = \DB::select("SELECT
                ROUND(SUM(p_po_invoice_hdr_t.balance_amount),2) as balance_amount_sum
                FROM p_po_invoice_hdr_t
                left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                left join p_po_hdr_t on(p_po_hdr_t.po_hdr_id=p_po_invoice_hdr_t.po_number)
                where 1=1 $where and p_po_invoice_hdr_t.balance_amount!=0 and p_po_invoice_hdr_t.company_id=$compy and p_po_invoice_hdr_t.location_id=$loc ");

         $table = \DB::table('p_po_invoice_hdr_t')->get();
   	 $this->data['datas'] = $table;
		 if(isset($_GET['supplier_id']))
		 {

         return view('pendingpurchasepayment.report',$this->data);
		 }
		else
		{
			return view('pendingpurchasepayment.table',$this->data);
		}
    }
    
	
        
       public function getpendingpaymentData(Request $request)
	{
		
        $compy=\Session::get('companyid');
	    $now = date('Y-m-d');


    $SQL = "SELECT * from (
 SELECT
                p_po_invoice_hdr_t.po_invoice_id,
				p_po_invoice_hdr_t.bill_number,
				p_po_invoice_hdr_t.po_invoice_status,
                m_supplier_t.supplier_name,
                p_po_hdr_t.po_number,
                p_po_invoice_hdr_t.invoice_date,
               round(SUM((p_po_invoice_hdr_t.invoice_grand_total)+(p_po_invoice_hdr_t.credit_note)-(p_po_invoice_hdr_t.debit_note)),2) as invoice_grand_total,
                p_po_invoice_hdr_t.paid_amount,
                p_po_invoice_hdr_t.balance_amount,
                p_po_invoice_hdr_t.payment_status,
                IF(p_po_invoice_hdr_t.paid_amount <= 0,
                    'UnPaid',
                    'Partial Paid'
                ) AS paid_status,
            IF(
                p_po_invoice_hdr_t.paid_amount <= 0,
                'text-danger',
                'text-warning'
            ) AS style
                FROM p_po_invoice_hdr_t
                left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                left join p_po_hdr_t on(p_po_hdr_t.po_hdr_id=p_po_invoice_hdr_t.po_number)
                where 1=1 and p_po_invoice_hdr_t.balance_amount!=0 and p_po_invoice_hdr_t.company_id=$compy and p_po_invoice_hdr_t.location_id='1' AND p_po_invoice_hdr_t.invoice_date BETWEEN '2024-04-01' AND '$now'  GROUP BY po_invoice_id) AS v1";

    $results = \DB::select($SQL);

   return DataTables::of($results)->make(true);
}
	
	
	public function getpaymentpendingreportData(){
	    //dd(\Session::all());
	    $supplier_id = \Session::get('supplier_id');
	    $days = \Session::get('days');
            
		 if($supplier_id)
		 {
		     $wh1='';
           
               if(isset($_GET['pq_filter']))
		{
		$data=json_decode($_GET['pq_filter']);
		$data=$data->data;
		$table=array('m_supplier_t','p_po_hdr_t');
			
	     $wh1.=$this->pqgridsearch('p_po_invoice_hdr_t',$data,$table);
		}
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
        /*$page = 1;
        $limit = 100;*/
      $sidx='';
        if (!$sidx)
            $sidx = 1;
		     
       $supplier_id = \Session::get('supplier_id');
       
      $where = " and p_po_invoice_hdr_t.po_invoice_status='APPROVED' and p_po_invoice_hdr_t.supplier_id='$supplier_id'";
       if($days)
       {
         $currentdate=date('Y-m-d');
             $current_date="\"".$currentdate."\"";
        if($days==2)
        {
          $where.=" and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)<16 and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)>0";
        }
        elseif($days==3)
        {
          $where.=" and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)<31 and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)>15";
        }
        elseif($days==4)
        {
          $where.=" and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)<45 and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)>30";
        }
        elseif($days==5)
        {
          $where.=" and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)>45";
        }
        elseif($days==1)
        {
          $where.=" and DATEDIFF($current_date,p_po_invoice_hdr_t.due_date)<=0";
        }

       }


		 }
		else
		{
			$where='';

		}

    	 $this->data['supplieropt'] = $this->jqgridselect('m_supplier_t', 'supplier_name', 'supplier_name');
         $this->data['bill_number']=$this->jqgridselect('p_po_invoice_hdr_t','bill_number','bill_number');

		  $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');

               $SQL = "SELECT
                p_po_invoice_hdr_t.bill_number,
                p_po_invoice_hdr_t.po_invoice_id as po_invoice_id,
                m_supplier_t.supplier_name as supplier_id,
                p_po_hdr_t.po_number,
                p_po_invoice_hdr_t.invoice_date,
                p_po_invoice_hdr_t.invoice_grand_total,
                p_po_invoice_hdr_t.paid_amount,
                p_po_invoice_hdr_t.balance_amount,
                p_po_invoice_hdr_t.payment_status,
                IF(p_po_invoice_hdr_t.paid_amount <= 0,
                    'UnPaid',
                    'Partital Paid'
                ) AS paid_status,
            IF(
                p_po_invoice_hdr_t.paid_amount <= 0,
                'text-danger',
                'text-warning'
            ) AS style
                FROM p_po_invoice_hdr_t
                left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                left join p_po_hdr_t on(p_po_hdr_t.po_hdr_id=p_po_invoice_hdr_t.po_number)
                where 1=1 $where and p_po_invoice_hdr_t.balance_amount!=0 and p_po_invoice_hdr_t.company_id=$compy and p_po_invoice_hdr_t.location_id=$loc $wh1";

    $result = \DB::select( $SQL );
$count = COUNT($result);
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
		
		if(isset($_GET['download']))
    {
    	                 $result1 = $result;
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }

	   	
		$result= array_slice($result, $start , $limit);
		$responce->rows[]='';
		$responce->data=$result;
		$responce->curPage = $page;
		$responce->total = $total_pages;
		$responce->totalRecords = $count;
		echo json_encode($responce);
		//json_encode($responce,true);
		
		//$this->data['result']=json_encode($result);

	}
}
