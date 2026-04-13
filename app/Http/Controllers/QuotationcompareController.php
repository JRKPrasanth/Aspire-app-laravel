<?php

namespace App\Http\Controllers;

use App\Quotationcompare;
use Illuminate\Http\Request;

class QuotationcompareController extends Controller
{
  
	  
	  public function __construct()
  {
        $this->data['urlmenu']=$this->indexs();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageModule']=\Request::route()->getName();
  }
	
    public function index(Request $request)
    {
        

        
        
                $startdate =  $request->input('start_date');    
                $this->data['data'] ='';
                if( $startdate !='') {
                   // dd("hii");
                $enddate=    $request->input('end_date');               
                $supplier = $request->input('supplier_id'); 
                $product=$request->input('product_id');   
        
             $wh="";

        if($supplier!="9999"){
            $wh.= "and p_quotation_hdr_t.supplier_id='$supplier' ";
         // dd($_GET['supplier']);   
        }
        if($product!="9999"){
            $wh.= "and p_quotation_lines_t.product_id='$product' ";
        }
        


       $this->data['data']=\DB::select("SELECT
                                        p_quotation_hdr_t.`quotation_no`,
                                        p_quotation_hdr_t.`reference_number`,
                                        p_quotation_hdr_t.`quotation_date`,
                                        p_quotation_hdr_t.transport_charges,
                                        p_quotation_hdr_t.insurance_charges,
                                        p_quotation_hdr_t.packing_charges,
                                        p_quotation_hdr_t.unloading_charges,
                                        p_quotation_lines_t.product_id,
                                        m_products_t.concatenated_product as product_name,
                                        p_quotation_lines_t.unit_price,
                                        p_quotation_lines_t.discount_amount,
                                        m_supplier_t.supplier_name
                                        FROM `p_quotation_hdr_t`
                                        left join m_supplier_t on m_supplier_t.supplier_id=p_quotation_hdr_t.supplier_id
                                        LEFT JOIN p_quotation_lines_t on (p_quotation_lines_t.quotation_hdr_id=p_quotation_hdr_t.quotation_hdr_id)
                                        LEFT JOIN m_products_t on (m_products_t.product_id=p_quotation_lines_t.product_id)
                                        WHERE 1=1 $wh and p_quotation_hdr_t.quotation_date between '".$startdate."' and  '".$enddate."'
                                       GROUP by p_quotation_lines_t.quotation_line_id");
                
                    // dd($this->data['data']);
                }
                
      
  return view('quotationcompare.index', $this->data);
  
    }

      function getEnquirysupplier($id=null) {
        $sql = \DB::SELECT('select * from p_enquiry_hdr_t where enquiry_hdr_id=' . $id . ' group by supplier_id');;
        if (!empty($sql)) {
            $data = "";
            $data = '<option value="">----select ---</option>';
            
      
            foreach ($sql as $key => $value) {
                $sql_pdt = \DB::SELECT('select * from m_supplier_t where supplier_id=' . $value->supplier_id);
                $data.="<option value='" . $sql[0]->supplier_id . "'>" . $sql_pdt[0]->supplier_name . "</option>";
            }return $data;
        }
    }
     function getEnquiryproduct($id=null) {
        $sql = \DB::SELECT('select * from p_enquiry_lines_t where enquiry_hdr_id=' . $id);

        if (!empty($sql)) {
            $data = "";
            $data = '<option value="">----select ---</option>';
          
            foreach ($sql as $key => $value) {
                $sql_pdt = \DB::SELECT('select * from m_products_t where product_id=' . $value->product_id);
                $data.="<option value='" . $sql_pdt[0]->product_id . "'>" . $sql_pdt[0]->concatenated_product . "</option>";
            }return $data;
        }
    }
    
      function getQuotecomparechart($enqid=null,$prdid=null) {
          if($prdid !=0)
          {
              
          $condition = "and p_quotation_lines_t.product_id='$prdid'";
          }
 else {
      $condition = "";
 }
 
    $sql = \DB::SELECT("SELECT 
p_quotation_hdr_t.quotation_hdr_id,
p_quotation_lines_t.quotation_line_id,
p_quotation_hdr_t.quotation_no,
m_supplier_t.supplier_name,
p_quotation_lines_t.product_id as product_id,
m_products_t.concatenated_product as quote_product,
p_quotation_lines_t.unit_price as price,
p_quotation_lines_t.discount_percentage as discount,
p_quotation_lines_t.discount_amount,
p_quotation_lines_t.line_total
FROM `p_quotation_hdr_t` 
JOIN p_quotation_lines_t on(p_quotation_lines_t.quotation_hdr_id=p_quotation_hdr_t.quotation_hdr_id)
LEFT JOIN m_supplier_t on(m_supplier_t.supplier_id=p_quotation_hdr_t.supplier_id)
left JOIN m_products_t on(m_products_t.product_id=p_quotation_lines_t.product_id) where reference_id=$enqid $condition order by price");
  
        if (!empty($sql)) {
            $price = "";
            $discount = "";
            $price = array();
            $discount = array();

            foreach ($sql as $key => $value) {
                $price[]= $value->price;
                $supplier_name[] = $value->supplier_name;
                $quotation_no[] = $value->quotation_no;
                $disp_all[] = $value->quotation_no . '<br>Product: ' . $value->quote_product . '<br>Price: ' . $value->price . '<br>Disc%: ' . $value->discount . '<br>DiscAmt: ' . $value->discount_amount . '<br>LineTotal: ' . $value->line_total;
                $disp1[] = $value->quotation_no . '<br>' . $value->price;
                $discount[] = $value->discount;
                $quote_product[0] = $value->quote_product;
            }
            $chart_price = $price;
            $chart_discount = $discount;
            $data['price'] = $chart_price;
            $data['chart_supplier'] = $supplier_name;
            $data['chart_discount'] = $chart_discount;
            $data['chart_quotation'] =$quotation_no;
            $data['chart_display_all'] = $disp_all;
            $data['chart_disp1'] = $disp1;
            $data['quote_product'] = $quote_product[0];
        }if (empty($sql)) {
            $data['price'] = "";
            $data['chart_supplier'] = "";
            $data['chart_discount'] = "";
            $data['chart_quotation'] = "";
            $data['chart_display_all'] = "";
            $data['chart_disp1'] = "";
        }return $data;
    }
    
    

  public function quotecomparsiondata()
    {
             $wh="";
            $enqid=$_GET['enqsearch'];
        $startdate=date('Y-m-d',strtotime($_GET['startdate']));
        $enddate=date('Y-m-d',strtotime($_GET['enddate']));
                    $supplier=$_GET['supplier'];
                    $product=$_GET['product'];
        if($supplier!="9999"){
            $wh.= "and p_quotation_hdr_t.supplier_id='$supplier' ";
         // dd($_GET['supplier']);   
        }
        if($product!="9999"){
            $wh.= "and p_quotation_lines_t.product_id='$product' ";
        }
        
//dd($)

        $sql=$this->data['vdata']=\DB::select("SELECT 
                                        p_quotation_hdr_t.`quotation_no`,
                                        p_quotation_hdr_t.`reference_number`,
                                        p_quotation_hdr_t.`quotation_date`,
                                        p_quotation_hdr_t.transport_charges,
                                        p_quotation_hdr_t.insurance_charges,
                                        p_quotation_hdr_t.packing_charges,
                                        p_quotation_hdr_t.unloading_charges,
                                        p_quotation_lines_t.product_id,
                                        m_products_t.concatenated_product as product_name,
                                        p_quotation_lines_t.unit_price,
                                        p_quotation_lines_t.discount_amount,
                                        m_supplier_t.supplier_name
                                        FROM `p_quotation_hdr_t`
                                        left join m_supplier_t on m_supplier_t.supplier_id=p_quotation_hdr_t.supplier_id
                                        LEFT JOIN p_quotation_lines_t on (p_quotation_lines_t.quotation_hdr_id=p_quotation_hdr_t.quotation_hdr_id)
                                        LEFT JOIN m_products_t on (m_products_t.product_id=p_quotation_lines_t.product_id)
                                        WHERE 1=1 $wh and p_quotation_hdr_t.quotation_date between '".$startdate."' and  '".$enddate."'
                                       GROUP by p_quotation_lines_t.quotation_line_id");
        //dd($sql);
     
                $html='';
	  if(count($sql)>0){
                foreach($sql as $key=>$val)
		{
                        $html.='<tr class="table'.$key.'"><body>
					<td ><input type="text" name="bulk_line_no[]" class="input-sm bulk_line_no" value="'.($key+1).'" readonly="readonly" style="width: 68px !important;text-align: center;"></td>
                                        <td >'.$val->quotation_date.'</td>					
                                        <td >'.$val->quotation_no.'</td>
                                        <td >'.$val->supplier_name.'</td>
                                        <td >'.$val->reference_number.'</td>
                                        <td >'.$val->product_name.'</td>
                                        <td >'.$val->unit_price.'</td>
                                            <td >'.$val->discount_amount.'</td>
					<td >'.$val->transport_charges.'</td>
                                        <td>'.$val->insurance_charges.'</td>
                                        <td>'.$val->packing_charges.'</td>
                                            <td>'.$val->unloading_charges.'</td>
					
					</td></body>
					</tr>';
                   }
	  }else{
		   $html.='<tr class="table"><body><td colspan="10"> There is No Quotation Details</td></body>
					</tr>';
	  }
			   return $html;
		 
	
    }
  
}
