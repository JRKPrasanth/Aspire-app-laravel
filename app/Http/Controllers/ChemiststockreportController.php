<?php

namespace App\Http\Controllers;

use App\Chemiststockreport;
use Illuminate\Http\Request;
use DB;

class ChemiststockreportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['stockist_dcr_id'] ='';
        $this->data['stockist_id'] =$this->jCombologin('app_stockist_t','stockist_id','stockist_name','');
        $this->data['month_id'] =$this->jCombologin('m_month_t','month_id','month_name','');
        $this->data['urlmenu']=$this->indexs(); 
        return view("chemiststockreport.form",$this->data);
    }

    public function stockreport($id=null,$month=null){

        $cus_data = DB::table('app_stockist_t')->where('stockist_id',$id)->select('super_stockist_id')->get();
        $l_month= $month -1;

        $la_data = DB::table('app_stockist_stk_rpt_t')
                ->leftjoin('m_products_t','m_products_t.product_id','=','app_stockist_stk_rpt_t.product_id')
                ->where('stockist_id',$id)->where('month_id',$l_month)->select('app_stockist_stk_rpt_t.direct_sale/adjt_qty as dir_sales','app_stockist_stk_rpt_t.direct_return/adjt_qty as dir_return','app_stockist_stk_rpt_t.*','m_products_t.concatenated_product as product_name')
                ->get();


        $cur_data = DB::table('app_stockist_stk_rpt_t')
            ->leftjoin('m_products_t','m_products_t.product_id','=','app_stockist_stk_rpt_t.product_id')
            ->where('stockist_id',$id)->where('month_id',$month)->select('app_stockist_stk_rpt_t.direct_sale/adjt_qty as dir_sales','app_stockist_stk_rpt_t.direct_return/adjt_qty as dir_return','app_stockist_stk_rpt_t.*','m_products_t.concatenated_product as product_name')
            ->get();


        $curmon_data=[];
        if(count($cur_data) > 0){
            foreach ($cur_data as $k => $v) {
                
                $curmon_data[] =array("stockist_stk_id" => $v->stockist_stk_id,"product_id"=>$v->product_id,"concatenated_product"=>$v->product_name,"open_qty"=>$la_data[$k]->closing_bal,"purchase_qty" => $v->purchase_qty, "sale_qty" => $v->sale_qty,"free_qty"=>$v->free_qty,"sales_return"=>$v->sales_return,"dir_return"=>$v->dir_return,"available_qty" => $v->available_soh ,"dir_sales"=>$v->dir_sales,"closing_bal"=>$v->closing_bal);            
            }
        }else{
            if(count($cus_data) >0){
                $cus_id =  $cus_data[0]->super_stockist_id;    
                $sql = \DB::select("select prod_data.stockist_name,prod_data.product_id,sum(prod_data.pob_qty) as sale_qty,sum(prod_data.qty) as pur_qty, prod_data.p_name as product_name from (select `app_stockist_dcr_t`.`stockist_name`, `app_stockist_detail_t`.`product_order` as product_id, sum(app_stockist_detail_t.pob_qty) as pob_qty,0 as qty,m_products_t.concatenated_product as p_name from `app_stockist_dcr_t` left join `app_stockist_detail_t` on `app_stockist_detail_t`.`stockist_dcr_id` = `app_stockist_dcr_t`.`stockist_dcr_id`  LEFT JOIN m_products_t on (m_products_t.product_id = app_stockist_detail_t.product_order) where `app_stockist_dcr_t`.`stockist_name` = '$id' group by `app_stockist_detail_t`.`product_order`
                    UNION ALL
                    select 0 as stockist_name, `s_invoice_lines_t`.`product_id`, 0 as pob_qty ,sum(s_invoice_lines_t.invoice_qty) as qty,0 as p_name from `s_invoice_hdr_t` left join `s_invoice_lines_t` on `s_invoice_lines_t`.`invoice_hdr_id` = `s_invoice_hdr_t`.`invoice_hdr_id` where `s_invoice_hdr_t`.`ship_to_customer_id` ='$cus_id' and month(`s_invoice_hdr_t`.`invoice_date`) = '$month' group by `s_invoice_lines_t`.`product_id`) as prod_data GROUP by prod_data.product_id");

                if(count($sql) >0){
                    foreach ($sql as $key => $value) {
                        $curmon_data[] =array("stockist_stk_id" => "","product_id"=>$value->product_id,"concatenated_product"=>$value->product_name,"open_qty"=>'',"purchase_qty" => $value->pur_qty, "sale_qty" => $value->sale_qty,"free_qty"=>'',"sales_return"=>'',"dir_return"=>'',"available_qty" => "" ,"dir_sales"=>'',"closing_bal"=>'');
                    }
                }
            }
        }

        return $curmon_data;       
        
    }

    public function save(Request $request){

        
        $date = date('Y-m-d' );
        foreach ($_POST['product_id'] as $key => $value) {
            if($_POST['stockist_stk_id'][$key] != ''){
                $data['stockist_id']=$_POST['stockist_id'];
                $data['date']=$date;
                $data['product_id']=$_POST['product'][$key];
                $data['month_id']=$_POST['month_id'];
                $data['opening_bal']=$_POST['open_qty'][$key];
                $data['purchase_qty']=$_POST['purchase_qty'][$key];
                $data['free_qty']=$_POST['free_qty'][$key];
                $data['sale_qty']=$_POST['sale_qty'][$key];
                $data['sales_return']=$_POST['sales_return'][$key];
                $data['direct_return/adjt_qty']=$_POST['dir_return'][$key];
                $data['available_soh']=$_POST['available_qty'][$key];
                $data['direct_sale/adjt_qty']=$_POST['dir_sales'][$key];
                $data['closing_bal']=$_POST['closing_bal'][$key];  
                $data['created_by']=\Session::get('id');  
                $data['organization_id']=\Session::get('organization');
                $data['location_id']=\Session::get('location');
                $data['company_id']=\Session::get('companyid');

                $id =  DB::table('app_stockist_stk_rpt_t')->where('stockist_stk_id',$_POST['stockist_stk_id'][$key])->update($data);

            }else{
                $data['stockist_id']=$_POST['stockist_id'];
                $data['date']=$date;
                $data['product_id']=$_POST['product'][$key];
                $data['month_id']=$_POST['month_id'];
                $data['opening_bal']=$_POST['open_qty'][$key];
                $data['purchase_qty']=$_POST['purchase_qty'][$key];
                $data['free_qty']=$_POST['free_qty'][$key];
                $data['sale_qty']=$_POST['sale_qty'][$key];
                $data['sales_return']=$_POST['sales_return'][$key];
                $data['direct_return/adjt_qty']=$_POST['dir_return'][$key];
                $data['available_soh']=$_POST['available_qty'][$key];
                $data['direct_sale/adjt_qty']=$_POST['dir_sales'][$key];
                $data['closing_bal']=$_POST['closing_bal'][$key];  
                $data['created_by']=\Session::get('id');  
                $data['organization_id']=\Session::get('organization');
                $data['location_id']=\Session::get('location');
                $data['company_id']=\Session::get('companyid');

                $id =  DB::table('app_stockist_stk_rpt_t')->insertGetid($data);
            }
            
        }
        
        return response()->json(array('status' => 'success', 'message' =>'Saved Successfully','id' => $id));

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
     * @param  \App\Chemiststockreport  $chemiststockreport
     * @return \Illuminate\Http\Response
     */
    public function show(Chemiststockreport $chemiststockreport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chemiststockreport  $chemiststockreport
     * @return \Illuminate\Http\Response
     */
    public function edit(Chemiststockreport $chemiststockreport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chemiststockreport  $chemiststockreport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chemiststockreport $chemiststockreport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chemiststockreport  $chemiststockreport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chemiststockreport $chemiststockreport)
    {
        //
    }
}
