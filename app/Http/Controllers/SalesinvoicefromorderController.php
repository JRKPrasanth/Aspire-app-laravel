<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\salesinvoicefromorder;


class SalesinvoicefromorderController extends Controller
{

public $module="Salesinvoicefromorder";
  public function __construct()
  {
    $this->data=array(
      'pageModule' => 'salesinvoicefromorder',
      'pageUrl' => url('salesinvoicefromorder')
    );
$this->data['urlmenu']=$this->indexs(); 

  }
    public function index()
    {
      //dd("dgd");
      $table = \DB::table('s_salesorder_hdr_t')->get();
  		$this->data['datas'] = $table;
      //dd($this->data);
      return view("salesinvoice.sotable",$this->data);
    }




    public function getGridData(){

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
so.`sales_hdr_id`,
so.`sales_order_no`,
so.`sales_order_date`,
so.order_type_id,
cust.customer_name as customer_id
FROM `s_salesorder_hdr_t` so
left join m_customers_t cust on (
cust.`customer_id`=so.`customer_id`
) where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		
		$download_SQL = "SELECT
so.`sales_hdr_id`,
so.`sales_order_no`,
so.`sales_order_date`,
so.order_type_id,
cust.customer_name as customer_id
FROM `s_salesorder_hdr_t` so
left join m_customers_t cust on (
cust.`customer_id`=so.`customer_id`
) where 1=1 $wh ORDER BY $sidx $sord";
		
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

  //dd($SQL);exit;
  echo json_encode($responce);


  }







}
