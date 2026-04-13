<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Productbasedcostreport;
use Illuminate\Http\Request;

class productbasedquantityrpt extends Controller
{
	
   public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }
	
    public function index()
    {
       return view('productbasedquantityreport.productbasedquantityrpt',$this->data);    
    }
	
  	public function getproductbasedqty(Request $request)
	{

    $SQL = "SELECT * from (
        SELECT
                p_po_invoice_lines_t.po_invoice_lines_id,
                p_po_invoice_lines_t.product_id,
                p_po_invoice_lines_t.qty,
                m_products_t.concatenated_product
                FROM
                p_po_invoice_lines_t
                LEFT JOIN m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
                WHERE
                1 = 1 AND p_po_invoice_lines_t.company_id = '1' AND p_po_invoice_lines_t.location_id = '1'
                group by product_id ORDER BY qty DESC) AS v1";

    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);

}
	
}
