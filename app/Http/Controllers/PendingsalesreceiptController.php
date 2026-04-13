<?php

namespace App\Http\Controllers;
use yajra\datatables\datatables;
use App\Pendingsalesreceipt;
use Illuminate\Http\Request;
use Session;

class PendingsalesreceiptController extends Controller
{
      public $module="pendingsalesreceipt";
	public function __construct()
	{
		$this->data=array();
                 $this->data=array();
		$this->table="s_receipts_t";
		$this->pageModule="pendingsalesreceipt";
                //$this->model=new Pendingsalesreceipt();
		$this->model=new Pendingsalesreceipt;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
                $this->table="s_receipts_t";
                $this->data['urlmenu']=$this->indexs(); 

	}
        
 /* Purpose for Pending Sales Invoice pqgrid*/  
       public function getpendingreceiptData(Request $request)
	{
		
        $compy=\Session::get('companyid');
	    $now = date('Y-m-d');


    $SQL = "SELECT * from (
                SELECT
                s_invoice_hdr_t.invoice_hdr_id,
                s_invoice_hdr_t.invoice_number,
                m_customers_t.customer_name,
                s_invoice_hdr_t.invoice_date,
                s_invoice_hdr_t.invoice_type,
                s_invoice_hdr_t.source,
                round(SUM((s_invoice_hdr_t.invoice_grand_total)+(s_invoice_hdr_t.debit_note)-(s_invoice_hdr_t.credit_note)),2) as invoice_grand_total,
                s_invoice_hdr_t.paid_amount,
                s_invoice_hdr_t.balance_amount,
                IF(s_invoice_hdr_t.paid_amount <= 0,
                    'UnPaid',
                    'Partial Paid'
                ) AS paid_status,
            IF(
                s_invoice_hdr_t.paid_amount <= 0,
                'text-danger',
                'text-warning'
            ) AS style
                FROM s_invoice_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) 
                where 1=1 AND s_invoice_hdr_t.invoice_date BETWEEN '2024-04-01' AND '$now' and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.company_id=$compy  GROUP BY invoice_hdr_id) AS v1";

    $results = \DB::select($SQL);

   return DataTables::of($results)->make(true);
}
	
        /*END*/
    public function index(){
		
    	 $this->data['customeropt'] = $this->jqgridselect('m_customers_t', 'customer_name', 'customer_name');
		
		 if(isset($_GET['customer_id']))
		 {
			 $customer_id = $_GET['customer_id'];
			 $days = $_GET['days'];
			 \Session::put('customer_id',$customer_id);
			 \Session::put('days',$days);
			 $where = " and invoice_status='APPROVED' and s_invoice_hdr_t.ship_to_customer_id='$customer_id'";
			  if(isset($_GET['days']))
       {
         $currentdate=date('Y-m-d');
             $current_date="\"".$currentdate."\"";
        if($_GET['days']==2)
        {
          $where.=" and DATEDIFF($current_date,s_invoice_hdr_t.due_date)<16 and DATEDIFF($current_date,s_invoice_hdr_t.due_date)>0";
        }
        elseif($_GET['days']==3)
        {
          $where.=" and DATEDIFF($current_date,s_invoice_hdr_t.due_date)<31 and DATEDIFF($current_date,s_invoice_hdr_t.due_date)>15";
        }
        elseif($_GET['days']==4)
        {
          $where.=" and DATEDIFF($current_date,s_invoice_hdr_t.due_date)<45 and DATEDIFF($current_date,s_invoice_hdr_t.due_date)>30";
        }
        elseif($_GET['days']==5)
        {
          $where.=" and DATEDIFF($current_date,s_invoice_hdr_t.due_date)>45";
        }
        elseif($_GET['days']==1)
        {
          $where.=" and DATEDIFF($current_date,s_invoice_hdr_t.due_date)<=0";
        }

       }
		 }
		else
		{
			$where='';

		}
		
		 $org=\Session::get('organization');
        $loc="1";
        $compy=\Session::get('companyid');
        
                $this->data['balance_amount_sum'] = \DB::select("SELECT 
                ROUND(SUM(s_invoice_hdr_t.balance_amount),2) as balance_amount_sum
                FROM s_invoice_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) 
                where 1=1 $where and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.company_id=$compy and s_invoice_hdr_t.location_id=$loc");
         
         $table = \DB::table('s_invoice_hdr_t')->get();
   	    $this->data['datas'] = $table;
		
		 if(isset($_GET['customer_id']))
		 {
			
         return view('pendingsalesreceipt.report',$this->data);
		 }
		else
		{
			 return view('pendingsalesreceipt.table',$this->data);
		}
        
    }
    
    
    public function getpendingreceiptreportData(){
        $customer_id = \Session::get('customer_id');
        $days = \Session::get('days');
        if($customer_id)
		 {
			 $customer_id = \Session::get('customer_id');
			 $where = " and invoice_status='APPROVED' and s_invoice_hdr_t.ship_to_customer_id='$customer_id'";
			  if($days)
       {
         $currentdate=date('Y-m-d');
             $current_date="\"".$currentdate."\"";
        if($days==2)
        {
          $where.=" and DATEDIFF($current_date,s_invoice_hdr_t.due_date)<16 and DATEDIFF($current_date,s_invoice_hdr_t.due_date)>0";
        }
        elseif($days==3)
        {
          $where.=" and DATEDIFF($current_date,s_invoice_hdr_t.due_date)<31 and DATEDIFF($current_date,s_invoice_hdr_t.due_date)>15";
        }
        elseif($days==4)
        {
          $where.=" and DATEDIFF($current_date,s_invoice_hdr_t.due_date)<45 and DATEDIFF($current_date,s_invoice_hdr_t.due_date)>30";
        }
        elseif($days==5)
        {
          $where.=" and DATEDIFF($current_date,s_invoice_hdr_t.due_date)>45";
        }
        elseif($days==1)
        {
          $where.=" and DATEDIFF($current_date,s_invoice_hdr_t.due_date)<=0";
        }

       }
		 }
		else
		{
			$where='';

		}
		
		$wh='';
           if(isset($_GET['pq_filter'])){
		$data=json_decode($_GET['pq_filter']);
		$data=$data->data;
		$table=array('m_customers_t');
	 $wh.=$this->pqgridsearch('s_invoice_hdr_t',$data,$table);
		}
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
      $sidx='';
        if (!$sidx)
            $sidx = 1;
		
		 $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        
                $SQL = "SELECT 
                s_invoice_hdr_t.invoice_number,
				s_invoice_hdr_t.invoice_hdr_id,
                m_customers_t.customer_name as ship_to_customer_id,
                s_invoice_hdr_t.invoice_date,
                s_invoice_hdr_t.invoice_type,
                s_invoice_hdr_t.source,
                s_invoice_hdr_t.invoice_grand_total,
                s_invoice_hdr_t.paid_amount,
                s_invoice_hdr_t.balance_amount,
                IF(s_invoice_hdr_t.paid_amount <= 0,
                    'UnPaid',
                    'Partital Paid'
                ) AS paid_status,
            IF(
                s_invoice_hdr_t.paid_amount <= 0,
                'text-danger',
                'text-warning'
            ) AS style
                FROM s_invoice_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) 
                where 1=1 $where and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.company_id=$compy and s_invoice_hdr_t.location_id=$loc $wh";
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
		
		$result1 = \DB::select($SQL);
		//$this->data['result']=json_encode($result);
		$result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
 	
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->data=$result;
		$responce->curPage = $page;
		$responce->total = $total_pages;
		$responce->totalRecords = $count;
		echo json_encode($responce);
    }
     
}
