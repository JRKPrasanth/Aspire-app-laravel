<?php

namespace App\Http\Controllers;

use App\Salesquotefromenquiry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesquotefromenquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $table = \DB::table('s_inquiry_hdr_t')->get();
      $this->data['datas'] = $table;
      //dd($this->data);
      return view("salesquotefromenquiry.table",$this->data);
    }


    public function getSalesquotefromenquiryData(){
    //  dd("test");
  $wh='';
  if($_GET['_search']=='true')
  {
  $wh=$this->jqgridsearch('s_inquiry_hdr_t',$_GET['filters']);
  }

  $page = $_GET['page'];
  $limit = $_GET['rows'];
  $sidx = $_GET['sidx'];
  $sord = $_GET['sord'];
  if(!$sidx) $sidx =1;
  $result = \DB::select("SELECT COUNT(so_inquiry_hdr_id) AS count FROM s_inquiry_hdr_t where 1=1 $wh");
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
  $SQL= "SELECT
inq.`so_inquiry_hdr_id`,
inq.`inquiry_no`,
inq.`inquiry_date`,
inq.inquiry_type,
cust.customer_name as customerid
FROM `s_inquiry_hdr_t` inq
left join m_customers_t cust on(
    cust.customer_id=inq.`customerid`
) where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		
		$download_SQL = "SELECT
inq.`so_inquiry_hdr_id`,
inq.`inquiry_no`,
inq.`inquiry_date`,
inq.inquiry_type,
cust.customer_name as customerid
FROM `s_inquiry_hdr_t` inq
left join m_customers_t cust on(
    cust.customer_id=inq.`customerid`
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
  echo json_encode($responce);
  }











    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Salesquotefromenquiry  $salesquotefromenquiry
     * @return \Illuminate\Http\Response
     */
    public function show(Salesquotefromenquiry $salesquotefromenquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Salesquotefromenquiry  $salesquotefromenquiry
     * @return \Illuminate\Http\Response
     */
    public function edit(Salesquotefromenquiry $salesquotefromenquiry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salesquotefromenquiry  $salesquotefromenquiry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salesquotefromenquiry $salesquotefromenquiry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Salesquotefromenquiry  $salesquotefromenquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salesquotefromenquiry $salesquotefromenquiry)
    {
        //
    }

}
