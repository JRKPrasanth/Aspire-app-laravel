<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Productbasedcostreport;
use Illuminate\Http\Request;

class ProductbasedcostreportController extends Controller
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
       return view('productbasedcostreport.productbasedcostrpt',$this->data);    
    }

		public function getproductbasedcost(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
        SELECT
        p_po_invoice_lines_t.po_invoice_lines_id,
        p_po_invoice_lines_t.product_id,
        sum(p_po_invoice_lines_t.qty*p_po_invoice_lines_t.unit_price) as price,
        p_po_invoice_lines_t.created_at,
        m_products_t.concatenated_product
        FROM
        p_po_invoice_lines_t
        LEFT JOIN m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
        WHERE
        1 = 1 AND p_po_invoice_lines_t.company_id = 1 AND p_po_invoice_lines_t.location_id = 1 AND (m_products_t.product_group_id = 2 or m_products_t.product_group_id = 3 or m_products_t.product_group_id = 12)
        AND date(p_po_invoice_lines_t.created_at) >= ? AND date(p_po_invoice_lines_t.created_at) <= ?
        group by product_id ORDER BY price DESC ) AS v1";

     $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
	
	
}
