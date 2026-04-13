<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class PurchasedashboardController extends Controller
{

	  public function __construct(){
		  
	$this->data['pageMethod']=\Request::route()->getName();
	
	  }
	
  public function index(Request $request)
  {
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['supplier_name'] = $this->jcustomselecttool('m_supplier_t', 'supplier_id', 'supplier_name', '', "and active='yes' order by supplier_name ASC");

    $supplier_name = $request->input('supplier_name');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    if ($supplier_name != '') {
      $supplier = \DB::select("SELECT supplier_name from m_supplier_t where supplier_id='$supplier_name'");
      $this->data['supplier'] = $supplier[0]->supplier_name;
    }

    // product category wise 
    $this->data['category_wise'] = \DB::select("SELECT
            v1.category_name,
            DATE_FORMAT(v1.grn_date, '%b-%y') AS month_y,
            ROUND(SUM(v1.subtotal), 0) AS subtotal
        FROM
        (
        SELECT
            p_po_invoice_hdr_t.*,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1) AS other_tax_amt,
            ROUND((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1)), 2) AS other_tax_value,
            other_tax_group_t.tax_group_name AS other_tax_group_name,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1) AS transport_tax_amt,
            ROUND((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1)), 2) AS transport_tax_value,
            transport_tax_group_t.tax_group_name AS transport_tax_group_name,
            (p_po_invoice_lines_t.line_total) - (p_po_invoice_lines_t.tax_amount) AS subtotal,
            m_supplier_t.supplier_name,
            p_grn_hdr_t.grn_date,
            m_products_t.concatenated_product,
            m_product_category_t.category_name
        FROM
            p_po_invoice_hdr_t
            LEFT JOIN p_po_invoice_lines_t ON (p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id)
            LEFT JOIN m_supplier_t ON (m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id)
            LEFT JOIN m_supplier_sites_t ON (m_supplier_sites_t.supplier_id = m_supplier_t.supplier_id AND m_supplier_sites_t.primary_address = 'Yes')
            LEFT JOIN p_po_hdr_t ON (p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number)
            LEFT JOIN p_po_lines_t ON (p_po_invoice_hdr_t.po_number = p_po_lines_t.po_hdr_id AND p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
            LEFT JOIN p_grn_hdr_t ON (p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number)
            LEFT JOIN f_gst_code_hdr_t ON (f_gst_code_hdr_t.gst_code_hdr_id = p_po_invoice_lines_t.hsn_code)
            LEFT JOIN m_tax_group_t ON (m_tax_group_t.tax_group_id = p_po_invoice_lines_t.tax_group_id)
            LEFT JOIN m_tax_group_t AS other_tax_group_t ON (other_tax_group_t.tax_group_id = SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', 1))
            LEFT JOIN m_tax_group_t AS transport_tax_group_t ON (transport_tax_group_t.tax_group_id = SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', 1))
            LEFT JOIN m_products_t ON (m_products_t.product_id = p_po_invoice_lines_t.product_id)
            LEFT JOIN m_product_subcategory_t ON (m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id)
            LEFT JOIN m_product_groups_t ON (m_product_groups_t.product_group_id = m_products_t.product_group_id)
            LEFT JOIN m_product_category_t ON (m_product_category_t.product_category_id = m_products_t.product_category_id)
            LEFT JOIN m_uom_codes_t ON (m_uom_codes_t.uom_code_id = p_po_invoice_lines_t.uom_code_id)
        WHERE
            p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'
            AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL)
            AND DATE(p_grn_hdr_t.grn_date) BETWEEN '$start_date' AND '$end_date'
            AND m_supplier_t.supplier_id = '$supplier_name' 
    ) AS v1 GROUP BY category_name, DATE_FORMAT(v1.grn_date, '%b-%y') ORDER BY DATE_FORMAT(v1.grn_date, '%Y%m')");


    // chart

    $category_chart = $this->data['category_wise'];


    $primData = [];
    foreach ($category_chart as $item) {
      $primData[] = [
        'name' => $item->category_name,
        'value' => $item->subtotal,
        'month' => $item->month_y,
      ];
    }
    // dd ($primData);
    $this->data['prim_sumchart'] = json_encode($primData);

    //  product based 

    // product category wise 
    $this->data['product_wise'] = \DB::select("SELECT v1.category_name,
            v1.concatenated_product,
            DATE_FORMAT(v1.grn_date, '%b-%y') AS month_y,
            ROUND(SUM(v1.subtotal), 0) AS subtotal
        FROM
        (
        SELECT
            p_po_invoice_hdr_t.*,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1) AS other_tax_amt,
            ROUND((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1)), 2) AS other_tax_value,
            other_tax_group_t.tax_group_name AS other_tax_group_name,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1) AS transport_tax_amt,
            ROUND((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1)), 2) AS transport_tax_value,
            transport_tax_group_t.tax_group_name AS transport_tax_group_name,
            (p_po_invoice_lines_t.line_total) - (p_po_invoice_lines_t.tax_amount) AS subtotal,
            m_supplier_t.supplier_name,
            m_products_t.concatenated_product,
            p_grn_hdr_t.grn_date,
            m_product_category_t.category_name
        FROM
            p_po_invoice_hdr_t
            LEFT JOIN p_po_invoice_lines_t ON (p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id)
            LEFT JOIN m_supplier_t ON (m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id)
            LEFT JOIN m_supplier_sites_t ON (m_supplier_sites_t.supplier_id = m_supplier_t.supplier_id AND m_supplier_sites_t.primary_address = 'Yes')
            LEFT JOIN p_po_hdr_t ON (p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number)
            LEFT JOIN p_po_lines_t ON (p_po_invoice_hdr_t.po_number = p_po_lines_t.po_hdr_id AND p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
            LEFT JOIN p_grn_hdr_t ON (p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number)
            LEFT JOIN f_gst_code_hdr_t ON (f_gst_code_hdr_t.gst_code_hdr_id = p_po_invoice_lines_t.hsn_code)
            LEFT JOIN m_tax_group_t ON (m_tax_group_t.tax_group_id = p_po_invoice_lines_t.tax_group_id)
            LEFT JOIN m_tax_group_t AS other_tax_group_t ON (other_tax_group_t.tax_group_id = SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', 1))
            LEFT JOIN m_tax_group_t AS transport_tax_group_t ON (transport_tax_group_t.tax_group_id = SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', 1))
            LEFT JOIN m_products_t ON (m_products_t.product_id = p_po_invoice_lines_t.product_id)
            LEFT JOIN m_product_subcategory_t ON (m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id)
            LEFT JOIN m_product_groups_t ON (m_product_groups_t.product_group_id = m_products_t.product_group_id)
            LEFT JOIN m_product_category_t ON (m_product_category_t.product_category_id = m_products_t.product_category_id)
            LEFT JOIN m_uom_codes_t ON (m_uom_codes_t.uom_code_id = p_po_invoice_lines_t.uom_code_id)
        WHERE
            p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'
            AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL)
            AND DATE(p_grn_hdr_t.grn_date) BETWEEN '$start_date' AND '$end_date'
            AND m_supplier_t.supplier_id = '$supplier_name' 
    ) AS v1 GROUP BY category_name,concatenated_product, DATE_FORMAT(v1.grn_date, '%b-%y') ORDER BY DATE_FORMAT(v1.grn_date, '%Y%m')");

    //  product based chart

    $Product_chart = $this->data['product_wise'];

    $proData = [];

    foreach ($Product_chart as $item) {
      $proData[] = [
        'name' => $item->concatenated_product,
        'value' => $item->subtotal,
        'month' => $item->month_y,
      ];
    }

    // dd (json_encode ($groupData));
    $this->data['product_sumchart'] = json_encode($proData);


    return view('purchasedashboard.sub_reports', $this->data);
  }

  public function Productbase(Request $request)
  {
    $this->data['pageMethod'] = \Request::route()->getName();
    $wh = '';
    $wh1 = '';
    // hireachy query
    $depart = \Session::get('groupname');

    if ($depart == "7") {

      $wh = " AND group_name IN ('RAW MATERIALS','PACKING MATERIALS')";
    }


    $this->data['pro_group'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', "and active='yes' $wh order by group_name ASC");
    $this->data['pro_name'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', '', "and active='yes' order by concatenated_product  ASC");

    $product_id = $request->input('product_name');
    $product_grp = $request->input('product_group');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    if ($product_id != '') {
      $product = \DB::select("SELECT concatenated_product from m_products_t where product_id='$product_id'");
      $this->data['product'] = $product[0]->concatenated_product;
    }
    if ($product_grp != '') {
      $product_gp = \DB::select("SELECT group_name from m_product_groups_t where product_group_id='$product_grp'");
      $this->data['product_grp'] = $product_gp[0]->group_name;
    }

    if ($product_id != '') {
      $wh1 = " AND m_products_t.product_id = '$product_id'";
    }
    if ($product_grp != '') {
      $wh1 = " AND m_products_t.product_group_id = '$product_grp'";
    }
    if ($product_id != '' && $product_id != '') {
      $wh1 = " AND m_products_t.product_group_id = '$product_grp' AND m_products_t.product_id = '$product_id'";
    }

    // product category wise 
    $this->data['category_wise'] = \DB::select("SELECT
            v1.supplier_name,
            DATE_FORMAT(v1.grn_date, '%b-%y') AS month_y,
            v1.p_qty, 
            ROUND(SUM(v1.subtotal), 0) AS subtotal
        FROM
        (
        SELECT
            p_po_invoice_hdr_t.*,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1) AS other_tax_amt,
            ROUND((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1)), 2) AS other_tax_value,
            other_tax_group_t.tax_group_name AS other_tax_group_name,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1) AS transport_tax_amt,
            ROUND((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1)), 2) AS transport_tax_value,
            transport_tax_group_t.tax_group_name AS transport_tax_group_name,
            (p_po_invoice_lines_t.line_total) - (p_po_invoice_lines_t.tax_amount) AS subtotal,
            m_supplier_t.supplier_name,
            m_products_t.concatenated_product,
             p_grn_hdr_t.grn_date,
             ROUND((p_po_invoice_lines_t.qty), 0) as p_qty, 
            m_product_category_t.category_name
        FROM
            p_po_invoice_hdr_t
            LEFT JOIN p_po_invoice_lines_t ON (p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id)
            LEFT JOIN m_supplier_t ON (m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id)
            LEFT JOIN m_supplier_sites_t ON (m_supplier_sites_t.supplier_id = m_supplier_t.supplier_id AND m_supplier_sites_t.primary_address = 'Yes')
            LEFT JOIN p_po_hdr_t ON (p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number)
            LEFT JOIN p_po_lines_t ON (p_po_invoice_hdr_t.po_number = p_po_lines_t.po_hdr_id AND p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
            LEFT JOIN p_grn_hdr_t ON (p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number)
            LEFT JOIN f_gst_code_hdr_t ON (f_gst_code_hdr_t.gst_code_hdr_id = p_po_invoice_lines_t.hsn_code)
            LEFT JOIN m_tax_group_t ON (m_tax_group_t.tax_group_id = p_po_invoice_lines_t.tax_group_id)
            LEFT JOIN m_tax_group_t AS other_tax_group_t ON (other_tax_group_t.tax_group_id = SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', 1))
            LEFT JOIN m_tax_group_t AS transport_tax_group_t ON (transport_tax_group_t.tax_group_id = SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', 1))
            LEFT JOIN m_products_t ON (m_products_t.product_id = p_po_invoice_lines_t.product_id)
            LEFT JOIN m_product_subcategory_t ON (m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id)
            LEFT JOIN m_product_groups_t ON (m_product_groups_t.product_group_id = m_products_t.product_group_id)
            LEFT JOIN m_product_category_t ON (m_product_category_t.product_category_id = m_products_t.product_category_id)
            LEFT JOIN m_uom_codes_t ON (m_uom_codes_t.uom_code_id = p_po_invoice_lines_t.uom_code_id)
        WHERE
            p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'
            AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL)
            AND DATE(p_grn_hdr_t.grn_date) BETWEEN '$start_date' AND '$end_date'
            $wh1 
    ) AS v1 GROUP BY v1.supplier_name,v1.p_qty, DATE_FORMAT(v1.grn_date, '%b-%y') ORDER BY DATE_FORMAT(v1.grn_date, '%Y%m')");


    //dd($this->data['category_wise']);
    // chart

    $category_chart = $this->data['category_wise'];


    $primData = [];
    foreach ($category_chart as $item) {
      $primData[] = [
        'name' => $item->supplier_name,
        'value' => $item->subtotal,
        'month' => $item->month_y,
      ];
    }
    // dd (json_encode ($groupData));
    $this->data['prim_sumchart'] = json_encode($primData);


    // TOP VALUE BASED
    $this->data['category_wise_top'] = \DB::select("SELECT
            v1.supplier_name,
            DATE_FORMAT(v1.grn_date, '%b-%y') AS month_y,
            ROUND(SUM(v1.subtotal), 0) AS subtotal
        FROM
        (
        SELECT
            p_po_invoice_hdr_t.*,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1) AS other_tax_amt,
            ROUND((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1)), 2) AS other_tax_value,
            other_tax_group_t.tax_group_name AS other_tax_group_name,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1) AS transport_tax_amt,
            ROUND((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1)), 2) AS transport_tax_value,
            transport_tax_group_t.tax_group_name AS transport_tax_group_name,
            (p_po_invoice_lines_t.line_total) - (p_po_invoice_lines_t.tax_amount) AS subtotal,
            m_supplier_t.supplier_name,
            m_products_t.concatenated_product,
             p_grn_hdr_t.grn_date,
            m_product_category_t.category_name
        FROM
            p_po_invoice_hdr_t
            LEFT JOIN p_po_invoice_lines_t ON (p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id)
            LEFT JOIN m_supplier_t ON (m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id)
            LEFT JOIN m_supplier_sites_t ON (m_supplier_sites_t.supplier_id = m_supplier_t.supplier_id AND m_supplier_sites_t.primary_address = 'Yes')
            LEFT JOIN p_po_hdr_t ON (p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number)
            LEFT JOIN p_po_lines_t ON (p_po_invoice_hdr_t.po_number = p_po_lines_t.po_hdr_id AND p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
            LEFT JOIN p_grn_hdr_t ON (p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number)
            LEFT JOIN f_gst_code_hdr_t ON (f_gst_code_hdr_t.gst_code_hdr_id = p_po_invoice_lines_t.hsn_code)
            LEFT JOIN m_tax_group_t ON (m_tax_group_t.tax_group_id = p_po_invoice_lines_t.tax_group_id)
            LEFT JOIN m_tax_group_t AS other_tax_group_t ON (other_tax_group_t.tax_group_id = SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', 1))
            LEFT JOIN m_tax_group_t AS transport_tax_group_t ON (transport_tax_group_t.tax_group_id = SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', 1))
            LEFT JOIN m_products_t ON (m_products_t.product_id = p_po_invoice_lines_t.product_id)
            LEFT JOIN m_product_subcategory_t ON (m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id)
            LEFT JOIN m_product_groups_t ON (m_product_groups_t.product_group_id = m_products_t.product_group_id)
            LEFT JOIN m_product_category_t ON (m_product_category_t.product_category_id = m_products_t.product_category_id)
            LEFT JOIN m_uom_codes_t ON (m_uom_codes_t.uom_code_id = p_po_invoice_lines_t.uom_code_id)
        WHERE
            p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'
            AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL)
            AND DATE(p_grn_hdr_t.grn_date) BETWEEN '$start_date' AND '$end_date'
             $wh1 
    ) AS v1 GROUP BY supplier_name, DATE_FORMAT(v1.grn_date, '%b-%y') ORDER BY ROUND(SUM(v1.subtotal), 0) DESC");

    return view('purchasedashboard.product', $this->data);

  }

  // tp value and qty
  public function Topqtyvalue(Request $request)
  {
    $this->data['pageMethod'] = \Request::route()->getName();
    $wh = '';
    // hireachy query
    $depart = \Session::get('groupname');

    if ($depart == "7") {

      $wh = " AND group_name IN ('RAW MATERIALS','PACKING MATERIALS')";
    }

    $this->data['pro_group'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', "and active='yes' $wh order by group_name ASC");
    $this->data['pro_name'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', '', "and active='yes' order by concatenated_product  ASC");

    $product_id = $request->input('product_name');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $grid_date = \Session::get('griddate');
    $gridenddate = \Session::get('gridenddate');

    // dd($gridenddate);
    if ($product_id != '') {

      $this->data['top_qty'] = \DB::select("SELECT *From(SELECT  m_products_t.concatenated_product,
    ROUND(SUM((p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount)),0) as subtotal,
     ROUND(SUM(p_po_invoice_lines_t.qty), 0) as p_qty 
             
      FROM p_po_invoice_hdr_t
                        left join p_po_invoice_lines_t on (p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id)
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                        left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                        left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id =p_po_invoice_hdr_t.po_number)
                        left join p_po_lines_t on (p_po_invoice_hdr_t.po_number =p_po_lines_t.po_hdr_id and p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
                        left join p_grn_hdr_t on (p_grn_hdr_t.grn_id =p_po_invoice_hdr_t.grn_number)
                        left join f_gst_code_hdr_t on (f_gst_code_hdr_t.gst_code_hdr_id =p_po_invoice_lines_t.hsn_code)
                        left join m_tax_group_t on (m_tax_group_t.tax_group_id =p_po_invoice_lines_t.tax_group_id)
                        left join m_tax_group_t as other_tax_group_t on (other_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',1))
                        left join m_tax_group_t as transport_tax_group_t on (transport_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',1))
                        left join m_products_t on (m_products_t.product_id =p_po_invoice_lines_t.product_id)
                        left join m_product_subcategory_t on (m_product_subcategory_t.product_subcategory_id =m_products_t.product_subcategory_id)
                        left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id)
                        left join m_product_category_t on (m_product_category_t.product_category_id =m_products_t.product_category_id)
                        left join m_uom_codes_t on (m_uom_codes_t.uom_code_id =p_po_invoice_lines_t.uom_code_id)
                        where 1=1  and p_po_invoice_hdr_t.po_invoice_status='APPROVED'  and (m_tax_group_t.tax_group_name!='' or m_tax_group_t.tax_group_name IS NOT NULL) and m_products_t.product_group_id='$product_id' and date(p_po_invoice_hdr_t.invoice_date) BETWEEN  '$start_date' and '$end_date' GROUP BY concatenated_product order by  ROUND(SUM((p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount)),0) desc )v1");


    } else {

      $this->data['top_qty'] = \DB::select("SELECT * From (SELECT  m_products_t.concatenated_product,
     ROUND(SUM((p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount)),0) as subtotal,
     ROUND(SUM(p_po_invoice_lines_t.qty), 0) as p_qty 
             
      FROM p_po_invoice_hdr_t
      
                        left join p_po_invoice_lines_t on (p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id)
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                        left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                        left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id =p_po_invoice_hdr_t.po_number)
                        left join p_po_lines_t on (p_po_invoice_hdr_t.po_number =p_po_lines_t.po_hdr_id and p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
                        left join p_grn_hdr_t on (p_grn_hdr_t.grn_id =p_po_invoice_hdr_t.grn_number)
                        left join f_gst_code_hdr_t on (f_gst_code_hdr_t.gst_code_hdr_id =p_po_invoice_lines_t.hsn_code)
                        left join m_tax_group_t on (m_tax_group_t.tax_group_id =p_po_invoice_lines_t.tax_group_id)
                        left join m_tax_group_t as other_tax_group_t on (other_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',1))
                        left join m_tax_group_t as transport_tax_group_t on (transport_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',1))
                        left join m_products_t on (m_products_t.product_id =p_po_invoice_lines_t.product_id)
                        left join m_product_subcategory_t on (m_product_subcategory_t.product_subcategory_id =m_products_t.product_subcategory_id)
                        left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id)
                        left join m_product_category_t on (m_product_category_t.product_category_id =m_products_t.product_category_id)
                        left join m_uom_codes_t on (m_uom_codes_t.uom_code_id =p_po_invoice_lines_t.uom_code_id)
                        where 1=1 and m_product_groups_t.group_name IN ('RAW MATERIALS','PACKING MATERIALS') and p_po_invoice_hdr_t.po_invoice_status='APPROVED'  and (m_tax_group_t.tax_group_name!='' or m_tax_group_t.tax_group_name IS NOT NULL)  and date(p_po_invoice_hdr_t.invoice_date) BETWEEN  '$grid_date' and '$gridenddate' GROUP BY concatenated_product order by  ROUND(SUM((p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount)),0) desc )v1");

    }
    return view('purchasedashboard.topvalue', $this->data);


  }


  public function Pricetrend(Request $request)
  {

    $this->data['pageMethod'] = \Request::route()->getName();
    $wh = '';
    // hireachy query
    $depart = \Session::get('groupname');

    if ($depart == "7") {

      $wh = " AND group_name IN ('RAW MATERIALS','PACKING MATERIALS')";
    }

    $this->data['pro_group'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', "and active='yes' $wh order by group_name ASC");
    $this->data['pro_name'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', '', "and active='yes' order by concatenated_product  ASC");

    $product_name = $request->input('product_name');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');



    // product category wise 
    $this->data['price_trend'] = \DB::select("SELECT *From(SELECT  m_supplier_t.supplier_name, m_products_t.concatenated_product,
                     p_po_invoice_lines_t.unit_price as price,
                     DATE_FORMAT(p_grn_hdr_t.grn_date, '%b-%y') AS month_y
     
                       FROM p_po_invoice_hdr_t

                        left join p_po_invoice_lines_t on (p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id)
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                        left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                        left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id =p_po_invoice_hdr_t.po_number)
                        left join p_po_lines_t on (p_po_invoice_hdr_t.po_number =p_po_lines_t.po_hdr_id and p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
                        left join p_grn_hdr_t on (p_grn_hdr_t.grn_id =p_po_invoice_hdr_t.grn_number)
                        left join f_gst_code_hdr_t on (f_gst_code_hdr_t.gst_code_hdr_id =p_po_invoice_lines_t.hsn_code)
                        left join m_tax_group_t on (m_tax_group_t.tax_group_id =p_po_invoice_lines_t.tax_group_id)
                        left join m_tax_group_t as other_tax_group_t on (other_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',1))
                        left join m_tax_group_t as transport_tax_group_t on (transport_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',1))
                        left join m_products_t on (m_products_t.product_id =p_po_invoice_lines_t.product_id)
                        left join m_product_subcategory_t on (m_product_subcategory_t.product_subcategory_id =m_products_t.product_subcategory_id)
                        left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id)
                        left join m_product_category_t on (m_product_category_t.product_category_id =m_products_t.product_category_id)
                        left join m_uom_codes_t on (m_uom_codes_t.uom_code_id =p_po_invoice_lines_t.uom_code_id)
                        where 1=1  and p_po_invoice_hdr_t.po_invoice_status='APPROVED'  and (m_tax_group_t.tax_group_name!='' or m_tax_group_t.tax_group_name IS NOT NULL) 
                        and date(p_grn_hdr_t.grn_date) BETWEEN  '$start_date' and '$end_date' and m_products_t.product_id='$product_name' GROUP BY concatenated_product,p_grn_hdr_t.grn_date
                        ORDER BY DATE_FORMAT(p_grn_hdr_t.grn_date, '%Y%m') )v1");

	  
	 
	$selected_product_name = '';

	if (!empty($product_name)) {
    	$selected_product = DB::table('m_products_t')
        ->where('product_id', $product_name)
        ->value('concatenated_product'); // only name, not full object

    	$selected_product_name = $selected_product ?? '';
	} 

    // chart

    $price_chart = $this->data['price_trend'];


    $primData = [];
    foreach ($price_chart as $item) {
      $primData[] = [
        'name' => $item->supplier_name,
        'value' => $item->price,
        'month' => $item->month_y,
      ];
    }
	  
    // dd (json_encode ($groupData));
    $this->data['price_trend_chart'] = json_encode($primData);  
	$this->data['selected_product_name'] = $selected_product_name;
	  
	 return view('purchasedashboard.pricetrend', $this->data);

  }

  // price difference
  public function Pricediffer(Request $request)
  {

    $this->data['pageMethod'] = \Request::route()->getName();
    $wh = '';
    // hireachy query
    $depart = \Session::get('groupname');

    if ($depart == "7") {

      $wh = " AND group_name IN ('RAW MATERIALS','PACKING MATERIALS')";
    }

    $this->data['pro_group'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', "and active='yes' $wh order by group_name ASC");
    $this->data['pro_name'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', '', "and active='yes' order by concatenated_product  ASC");

    $product_name = $request->input('product_name');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');


    // product category wise 
    $this->data['price_differ'] = \DB::select("SELECT
            m_products_t.concatenated_product,
            old_supplier.supplier_name AS old_supplier,
            new_supplier.supplier_name AS new_supplier,
            MAX(p_po_invoice_lines_t.unit_price) AS old_price,
            MAX(new_price.unit_price) AS new_price,
            (MAX(p_po_invoice_lines_t.unit_price) - MAX(new_price.unit_price)) AS price_difference,
            CASE
                WHEN MAX(new_price.unit_price) > MAX(p_po_invoice_lines_t.unit_price) THEN 'Increased'
                WHEN MAX(new_price.unit_price) < MAX(p_po_invoice_lines_t.unit_price) THEN 'Decreased'
                WHEN MAX(new_price.unit_price) = MAX(p_po_invoice_lines_t.unit_price) THEN 'Match'
            END AS remarks
        FROM
            p_po_invoice_hdr_t
        LEFT JOIN p_po_invoice_lines_t ON (p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id)
        LEFT JOIN m_supplier_t AS old_supplier ON (old_supplier.supplier_id = p_po_invoice_hdr_t.supplier_id)
        LEFT JOIN m_supplier_sites_t ON (m_supplier_sites_t.supplier_id = old_supplier.supplier_id AND m_supplier_sites_t.primary_address = 'Yes')
        LEFT JOIN p_po_hdr_t ON (p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number)
        LEFT JOIN p_po_lines_t ON (p_po_invoice_hdr_t.po_number = p_po_lines_t.po_hdr_id AND p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
        LEFT JOIN m_tax_group_t ON (m_tax_group_t.tax_group_id = p_po_invoice_lines_t.tax_group_id)
        LEFT JOIN p_grn_hdr_t ON (p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number)
        LEFT JOIN m_products_t ON (m_products_t.product_id = p_po_invoice_lines_t.product_id)
        LEFT JOIN m_product_groups_t ON (m_product_groups_t.product_group_id = m_products_t.product_group_id)
        LEFT JOIN (
            SELECT
                MAX(p_po_invoice_hdr_t.po_invoice_id) AS latest_invoice_id,
                p_po_invoice_lines_t.product_id
            FROM
                p_po_invoice_hdr_t
            LEFT JOIN p_po_invoice_lines_t ON (p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id)
            WHERE
                p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'
            GROUP BY
                p_po_invoice_lines_t.product_id
        ) AS latest_invoice ON p_po_invoice_hdr_t.po_invoice_id = latest_invoice.latest_invoice_id
        LEFT JOIN p_po_invoice_lines_t AS new_price ON (new_price.po_invoice_id = latest_invoice.latest_invoice_id AND new_price.product_id = p_po_invoice_lines_t.product_id)
        LEFT JOIN m_supplier_t AS new_supplier ON (new_supplier.supplier_id = p_po_invoice_hdr_t.supplier_id)
        WHERE
            1=1
            AND p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'
            AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL)
        
            AND DATE(p_po_invoice_hdr_t.invoice_date) BETWEEN '$start_date' AND '$end_date'
            AND m_products_t.product_id='$product_name'
        GROUP BY
            m_products_t.concatenated_product, old_supplier.supplier_name, new_supplier.supplier_name
        HAVING
            COUNT(m_products_t.concatenated_product) >= 2
        ORDER BY
            DATE_FORMAT(p_grn_hdr_t.grn_date, '%Y%m') DESC");


    return view('purchasedashboard.pricediff', $this->data);

  }

  // packing material delay

  // tp value and qty
  public function Packdelay(Request $request)
  {

    $this->data['pageMethod'] = \Request::route()->getName();
    $wh = '';
    // hireachy query
    $depart = \Session::get('groupname');

    if ($depart == "7") {

      $wh = " AND group_name IN ('RAW MATERIALS','PACKING MATERIALS')";
    }

    $this->data['pro_group'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', "and active='yes' $wh order by group_name ASC");
    $this->data['pro_name'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', '', "and active='yes' order by concatenated_product  ASC");

    $product_id = $request->input('product_name');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $grid_date = \Session::get('griddate');
    $gridenddate = \Session::get('gridenddate');

    // dd($gridenddate);
    if ($product_id != '') {

      $this->data['pro_delay'] = \DB::select("SELECT
        v1.concatenated_product,
        DATE_FORMAT(v1.grn_date, '%b-%y') AS month_y,
        (v1.promised_date) AS req_date,
        (v1.grn_date) AS rec_date,
        DATEDIFF(MAX(v1.grn_date), MAX(v1.promised_date)) AS delay_days
    
        FROM
        (
        SELECT
            p_po_invoice_hdr_t.*,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1) AS other_tax_amt,
            ROUND((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1)), 2) AS other_tax_value,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1) AS transport_tax_amt,
            ROUND((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1)), 2) AS transport_tax_value,
            (p_po_invoice_lines_t.line_total) - (p_po_invoice_lines_t.tax_amount) AS subtotal,
            m_products_t.concatenated_product,
            p_grn_hdr_t.grn_date,
            p_po_lines_t.promised_date,
            m_product_category_t.category_name
        FROM
            p_po_invoice_hdr_t
            LEFT JOIN p_po_invoice_lines_t ON (p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id)
            left join p_po_lines_t on (p_po_invoice_hdr_t.po_number = p_po_lines_t.po_hdr_id)
            LEFT JOIN m_supplier_t ON (m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id)
            LEFT JOIN p_grn_hdr_t ON (p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number)
            LEFT JOIN p_po_hdr_t ON (p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number)
            LEFT JOIN m_products_t ON (m_products_t.product_id = p_po_invoice_lines_t.product_id)
            LEFT JOIN m_product_category_t ON (m_product_category_t.product_category_id = m_products_t.product_category_id)
        WHERE
            p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'
            AND m_products_t.product_id='$product_id'
            AND DATE(p_po_invoice_hdr_t.invoice_date) BETWEEN '$start_date' AND '$end_date'

    ) AS v1
    GROUP BY
     v1.concatenated_product ORDER BY DATE_FORMAT(v1.grn_date, '%Y%m')");


    } else {

      $this->data['pro_delay'] = \DB::select("SELECT
        v1.concatenated_product,
        DATE_FORMAT(v1.grn_date, '%b-%y') AS month_y,
        (v1.promised_date) AS req_date,
        (v1.grn_date) AS rec_date,
        DATEDIFF(MAX(v1.grn_date), MAX(v1.promised_date)) AS delay_days
    
    FROM
        (
        SELECT
            p_po_invoice_hdr_t.*,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1) AS other_tax_amt,
            ROUND((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1)), 2) AS other_tax_value,
            SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1) AS transport_tax_amt,
            ROUND((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1)), 2) AS transport_tax_value,
            (p_po_invoice_lines_t.line_total) - (p_po_invoice_lines_t.tax_amount) AS subtotal,
            m_products_t.concatenated_product,
            p_grn_hdr_t.grn_date,
            p_po_lines_t.promised_date,
            m_product_category_t.category_name
        FROM
            p_po_invoice_hdr_t
            LEFT JOIN p_po_invoice_lines_t ON (p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id)
            left join p_po_lines_t on (p_po_invoice_hdr_t.po_number = p_po_lines_t.po_hdr_id)
            LEFT JOIN m_supplier_t ON (m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id)
            LEFT JOIN p_grn_hdr_t ON (p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number)
            LEFT JOIN p_po_hdr_t ON (p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number)
            LEFT JOIN m_products_t ON (m_products_t.product_id = p_po_invoice_lines_t.product_id)
            LEFT JOIN m_product_category_t ON (m_product_category_t.product_category_id = m_products_t.product_category_id)
        WHERE
            p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'
            AND DATE(p_po_invoice_hdr_t.invoice_date) BETWEEN '$grid_date' AND '$gridenddate'

            ) AS v1
        GROUP BY
         v1.concatenated_product  ORDER BY DATE_FORMAT(v1.grn_date, '%Y%m')");

    }

    return view('purchasedashboard.packdelay', $this->data);


  }

  // product exception report

  public function Exceptionrpt(Request $request)
  {

    $this->data['pageMethod'] = \Request::route()->getName();
    $wh = '';
    // hireachy query
    $depart = \Session::get('groupname');

    if ($depart == "7") {

      $wh = " AND group_name IN ('RAW MATERIALS','PACKING MATERIALS','CONSUMABLES','PROMOTIONAL ITEMS')";
    }

    $this->data['pro_group'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', "and active='yes' $wh order by group_name ASC");

    $product_group = $request->input('product_group');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    $this->data['price_trend'] = \DB::select("SELECT v2.concatenated_product,v2.old_price,v2.new_price, ROUND((v2.new_price - v2.old_price) / v2.old_price * 100, 2) AS ratio,
     CASE WHEN v2.old_price =  v2.new_price THEN 'MATCH' WHEN  v2.old_price <  v2.new_price THEN 'INCREASED' ELSE 'DECREASED' END AS impact
    FROM (SELECT 
    v1.concatenated_product,
    (SELECT p1.unit_price 
     FROM p_po_invoice_lines_t p1
     LEFT JOIN p_po_invoice_hdr_t h1 ON p1.po_invoice_id = h1.po_invoice_id
     LEFT JOIN p_grn_hdr_t g1 ON g1.grn_id = h1.grn_number
     WHERE 
         p1.product_id = v1.product_id
         AND DATE(g1.grn_date) BETWEEN '$start_date' AND '$end_date'
         AND h1.po_invoice_status = 'APPROVED'
     ORDER BY DATE(g1.grn_date) DESC LIMIT 1 OFFSET 1) AS old_price,

    (SELECT p2.unit_price 
     FROM p_po_invoice_lines_t p2
     LEFT JOIN p_po_invoice_hdr_t h2 ON p2.po_invoice_id = h2.po_invoice_id
     LEFT JOIN p_grn_hdr_t g2 ON g2.grn_id = h2.grn_number
     WHERE 
         p2.product_id = v1.product_id
         AND DATE(g2.grn_date) BETWEEN '$start_date' AND '$end_date'
         AND h2.po_invoice_status = 'APPROVED'
     ORDER BY DATE(g2.grn_date) DESC LIMIT 1) AS new_price
FROM (
    SELECT DISTINCT 
        m_products_t.concatenated_product,
        m_products_t.product_id
    FROM 
        m_products_t
    JOIN 
        p_po_invoice_lines_t 
        ON m_products_t.product_id = p_po_invoice_lines_t.product_id
    JOIN 
        p_po_invoice_hdr_t 
        ON p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id
    JOIN 
        p_grn_hdr_t 
        ON p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number
    WHERE 
        DATE(p_grn_hdr_t.grn_date) BETWEEN '$start_date' AND '$end_date'
        AND p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'
        AND m_products_t.product_group_id = '$product_group'
    ) v1)v2 HAVING old_price !='' AND new_price !='' AND old_price != new_price");



    return view('purchasedashboard.proexception', $this->data);

  }

  // most spend  value and qty
  public function Mostspendpro(Request $request)
  {

    $this->data['pageMethod'] = \Request::route()->getName();
    $wh = '';
    // hireachy query
    $depart = \Session::get('groupname');

    if ($depart == "7") {

      $wh = " AND group_name IN ('RAW MATERIALS','PACKING MATERIALS','CONSUMABLES')";
    }

    $this->data['pro_group'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', "and active='yes' $wh order by group_name ASC");
    $this->data['pro_name'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', '', "and active='yes' order by concatenated_product  ASC");

    $product_id = $request->input('product_name');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $grid_date = \Session::get('griddate');
    $gridenddate = \Session::get('gridenddate');
    //  dd($product_id);
    // dd($gridenddate);


    $this->data['product_summary'] = \DB::select("SELECT * From (SELECT  m_product_category_t.category_name,DATE_FORMAT(p_grn_hdr_t.grn_date, '%b-%y') AS yr_month,
                         ROUND(SUM((p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount)),0) as subtotal,
                         ROUND(SUM(p_po_invoice_lines_t.qty), 0) as p_qty 
                                 
                          FROM p_po_invoice_hdr_t
      
                        left join p_po_invoice_lines_t on (p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id)
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                        left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                        left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id =p_po_invoice_hdr_t.po_number)
                        left join p_po_lines_t on (p_po_invoice_hdr_t.po_number =p_po_lines_t.po_hdr_id and p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
                        left join p_grn_hdr_t on (p_grn_hdr_t.grn_id =p_po_invoice_hdr_t.grn_number)
                        left join f_gst_code_hdr_t on (f_gst_code_hdr_t.gst_code_hdr_id =p_po_invoice_lines_t.hsn_code)
                        left join m_tax_group_t on (m_tax_group_t.tax_group_id =p_po_invoice_lines_t.tax_group_id)
                        left join m_tax_group_t as other_tax_group_t on (other_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',1))
                        left join m_tax_group_t as transport_tax_group_t on (transport_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',1))
                        left join m_products_t on (m_products_t.product_id =p_po_invoice_lines_t.product_id)
                        left join m_product_subcategory_t on (m_product_subcategory_t.product_subcategory_id =m_products_t.product_subcategory_id)
                        left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id)
                        left join m_product_category_t on (m_product_category_t.product_category_id =m_products_t.product_category_id)
                        left join m_uom_codes_t on (m_uom_codes_t.uom_code_id =p_po_invoice_lines_t.uom_code_id)
                        where 1=1 $wh and m_product_groups_t.product_group_id = '$product_id'  and p_po_invoice_hdr_t.po_invoice_status='APPROVED'  and (m_tax_group_t.tax_group_name!='' or m_tax_group_t.tax_group_name IS NOT NULL)  and date(p_grn_hdr_t.grn_date) BETWEEN  '$start_date' and '$end_date' GROUP BY m_product_category_t.category_name,DATE_FORMAT(p_grn_hdr_t.grn_date, '%b-%y') order by  p_grn_hdr_t.grn_date asc )v1");


    return view('purchasedashboard.mostspendlist', $this->data);


  }

  public function Mostvalproduct(Request $request)
  {

    $this->data['pageMethod'] = \Request::route()->getName();
    $wh = '';
    // hireachy query

    $category = $request->input('prd_type');
    $start_date = $request->input('start_date1');
    $end_date = $request->input('end_date1');
    $pro_group = $request->input('pro_grp');

    if ($start_date != '' && $end_date != '') {
      $start_date1 = $request->input('start_date1');
      $end_date1 = $request->input('end_date1');

    } else {

      $start_date1 = \Session::get('griddate');
      $end_date1 = \Session::get('gridenddate');

    }


    $procat_summary = \DB::select("SELECT * From (SELECT  m_products_t.concatenated_product as pro_name,MONTH(p_grn_hdr_t.grn_date) AS month,DATE_FORMAT(p_grn_hdr_t.grn_date, '%b-%y') AS yr_month,
                         ROUND(SUM((p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount)),0) as value,
                         ROUND(SUM(p_po_invoice_lines_t.qty), 0) as qty 
                                 
                          FROM p_po_invoice_hdr_t
      
                        left join p_po_invoice_lines_t on (p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id)
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_invoice_hdr_t.supplier_id)
                        left join m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
                        left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id =p_po_invoice_hdr_t.po_number)
                        left join p_po_lines_t on (p_po_invoice_hdr_t.po_number =p_po_lines_t.po_hdr_id and p_po_invoice_lines_t.product_id = p_po_lines_t.product_id)
                        left join p_grn_hdr_t on (p_grn_hdr_t.grn_id =p_po_invoice_hdr_t.grn_number)
                        left join f_gst_code_hdr_t on (f_gst_code_hdr_t.gst_code_hdr_id =p_po_invoice_lines_t.hsn_code)
                        left join m_tax_group_t on (m_tax_group_t.tax_group_id =p_po_invoice_lines_t.tax_group_id)
                        left join m_tax_group_t as other_tax_group_t on (other_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax,',',1))
                        left join m_tax_group_t as transport_tax_group_t on (transport_tax_group_t.tax_group_id =SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax,',',1))
                        left join m_products_t on (m_products_t.product_id =p_po_invoice_lines_t.product_id)
                        left join m_product_subcategory_t on (m_product_subcategory_t.product_subcategory_id =m_products_t.product_subcategory_id)
                        left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id)
                        left join m_product_category_t on (m_product_category_t.product_category_id =m_products_t.product_category_id)
                        left join m_uom_codes_t on (m_uom_codes_t.uom_code_id =p_po_invoice_lines_t.uom_code_id)
                        where 1=1 AND m_product_groups_t.product_group_id ='$pro_group' and m_product_category_t.category_name = '$category' AND p_po_invoice_hdr_t.po_invoice_status='APPROVED'  and (m_tax_group_t.tax_group_name!='' or m_tax_group_t.tax_group_name IS NOT NULL)  and date(p_grn_hdr_t.grn_date) BETWEEN  '$start_date1' and '$end_date1' GROUP BY m_products_t.concatenated_product,DATE_FORMAT(p_grn_hdr_t.grn_date, '%b-%y') ORDER BY DATE_FORMAT(p_grn_hdr_t.grn_date, '%Y-%m') ASC) v1  GROUP BY v1.pro_name,v1.month order by v1.month,v1.value DESC");


    usort($procat_summary, function ($a, $b) {
      $dateA = DateTime::createFromFormat('M-y', $a->yr_month);
      $dateB = DateTime::createFromFormat('M-y', $b->yr_month);
      return $dateA <=> $dateB; // Compare the DateTime objects
    });

    $htmlTable = '<div class="table-responsive">';
    $htmlTable .= '<table id="productSummary" class="table table-bordered table-hover table-striped align-middle" style="width:100%; font-family: \'Saira Semi Condensed\', sans-serif;">';

    $uniqueMonthYears = [];

    foreach ($procat_summary as $value) {
      $monthYear = $value->yr_month;

      if (!isset($uniqueMonthYears[$monthYear])) {
        $uniqueMonthYears[$monthYear] = true;
        $totalQty = 0;
        $totalValue = 0;

        // Filter current month's data
        $monthDetails = array_filter($procat_summary, function ($detail) use ($monthYear) {
          return $detail->yr_month == $monthYear;
        });

        // Sort by value descending
        usort($monthDetails, function ($a, $b) {
          return $b->value <=> $a->value;
        });

        // Totals
        foreach ($monthDetails as $detail) {
          $totalQty += $detail->qty;
          $totalValue += $detail->value;
        }

        // Month row with toggle button
        $htmlTable .= '<tr>';
        $htmlTable .= '<td class="text-center">';
        $htmlTable .= '<button class="btn btn-outline-primary btn-sm toggle-month" data-month="' . $monthYear . '">' . $monthYear . '</button>';
        $htmlTable .= '</td>';
        $htmlTable .= '</tr>';

        // Detail rows (hidden by default)
        $htmlTable .= '<tr class="month-detail-row" data-month="' . $monthYear . '" style="display: none;">';
        $htmlTable .= '<td colspan="1">';
        $htmlTable .= '<div class="table-responsive">';
        $htmlTable .= '<table id="popuptbl_' . $monthYear . '" class="table table-sm table-bordered table-striped">';
        $htmlTable .= '<thead class="table-success sticky-top">';
        $htmlTable .= '<tr>';
        $htmlTable .= '<th class="text-center" style="width: 50%;">Product Name</th>';
        $htmlTable .= '<th class="text-center">Qty</th>';
        $htmlTable .= '<th class="text-center">Value (Rs)</th>';
        $htmlTable .= '</tr>';
        $htmlTable .= '</thead><tbody>';

        foreach ($monthDetails as $detail) {
          $htmlTable .= '<tr>';
          $htmlTable .= '<td class="text-center">' . htmlspecialchars($detail->pro_name) . '</td>';
          $htmlTable .= '<td class="text-center">' . $detail->qty . '</td>';
          $htmlTable .= '<td class="text-center">' . $detail->value . '</td>';
          $htmlTable .= '</tr>';
        }

        // Totals row
        $htmlTable .= '<tr class="fw-bold bg-light">';
        $htmlTable .= '<td class="text-center">Total</td>';
        $htmlTable .= '<td class="text-center">' . $totalQty . '</td>';
        $htmlTable .= '<td class="text-center">' . $totalValue . '</td>';
        $htmlTable .= '</tr>';

        $htmlTable .= '</tbody></table></div>';
        $htmlTable .= '</td></tr>';
      }
    }

    $htmlTable .= '</table>';
    $htmlTable .= '</div>';


    // JavaScript to manipulate the DOM
    $htmlTable .= '<script>
$(document).ready(function() {
    $(".toggle-month").on("click", function() {
        var month = $(this).data("month");
        var detailRow = $(".month-detail-row[data-month=\'" + month + "\']");
        detailRow.toggle(); // Show/hide the detail row
    });
});
</script>';


    return $htmlTable;

  }


  public function Rejecteditems(Request $request)
  {


    $this->data['pageMethod'] = \Request::route()->getName();
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    if ($start_date != '' && $end_date != '') {

      $start_date = $request->input('start_date');
      $end_date = $request->input('end_date');

    } else {

      $start_date = \Session::get('griddate');
      $end_date = \Session::get('gridenddate');
    }

    $this->data['price_trend'] = \DB::select("SELECT  m_products_t.concatenated_product as pro_name,m_supplier_t.supplier_name, 
          p_return_lines_t.reason,DATE_FORMAT(p_qc_header_t.qc_date, '%b-%y') AS yr_month,p_qc_lines_t.total_box_qty,p_qc_lines_t.reject_qty,'REJECTED' AS status,
          ROUND(SUM((p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount)),0) as value

    FROM p_qc_lines_t
          
    left join p_qc_header_t on (p_qc_header_t.qc_header_id=p_qc_lines_t.qc_header_id)
    left join m_supplier_t on(m_supplier_t.supplier_id=p_qc_header_t.supplier_id)
    left join p_po_invoice_hdr_t on(p_po_invoice_hdr_t.po_number=p_qc_header_t.po_number)
    left join p_po_invoice_lines_t on(p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id)
    left join p_return_header_t on (p_return_header_t.qc_id=p_qc_header_t.qc_header_id)
        left join p_return_lines_t on (p_return_lines_t.return_header_id=p_return_header_t.return_header_id)
    left join m_products_t on (m_products_t.product_id =p_qc_lines_t.product_id) where p_qc_lines_t.reject_qty >0  and date(p_qc_header_t.qc_date) BETWEEN  '$start_date' and '$end_date' GROUP BY concatenated_product,supplier_name
    
    UNION ALL
    SELECT  m_products_t.concatenated_product as pro_name,m_supplier_t.supplier_name, 
          p_replacement_lines_t.comments as reason,DATE_FORMAT(p_replacement_hdr_t.replacement_date, '%b-%y') AS yr_month,p_grn_lines_t.qty AS total_box_qty,p_replacement_lines_t.qty as reject_qty,'REPLACEMENT' AS status,
          ROUND(SUM((p_po_invoice_lines_t.line_total)-(p_po_invoice_lines_t.tax_amount)),0) as value

    FROM p_replacement_lines_t
    left join p_replacement_hdr_t on p_replacement_hdr_t.replacement_hdr_id = p_replacement_lines_t.replacement_hdr_id 
    left join p_grn_lines_t on p_grn_lines_t.grn_id = p_replacement_hdr_t.grn_number AND p_grn_lines_t.product_id = p_replacement_lines_t.product_id
    left join m_supplier_t on(m_supplier_t.supplier_id=p_replacement_hdr_t.supplier_id)
    left join p_po_invoice_hdr_t on(p_po_invoice_hdr_t.po_number=p_replacement_hdr_t.po_number)
    left join p_po_invoice_lines_t on(p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id)
    left join m_products_t on (m_products_t.product_id =p_replacement_lines_t.product_id) 
    where  date(p_replacement_hdr_t.replacement_date) BETWEEN  '$start_date' and '$end_date' GROUP BY concatenated_product,supplier_name");



    return view('purchasedashboard.rejectedlist', $this->data);

  }
  // consumption report
  public function Consumptiorpt(Request $request)
  {

    $this->data['pageMethod'] = \Request::route()->getName();
    $product_group = $request->input('product_name');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    $wh = '';
    // hireachy query
    $depart = \Session::get('groupname');
    $wh1 = '';
    if ($depart == "7") {

      $wh = " AND group_name IN ('RAW MATERIALS','PACKING MATERIALS')";
    }

    if ($product_group == '2') {
      $wh1 = " AND qoh_source IN ('MATERIAL ISSUE', 'CONSUMABLE')";
    } else {
      $wh1 = " AND qoh_source IN ('MATERIAL ISSUE', 'CONSUMABLE', 'SUBINVENTORY TRANSFER')";
    }


    $this->data['pro_group'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', "and active='yes' $wh order by group_name ASC");
    $this->data['pro_name'] = $this->jcustomselecttool('m_products_t', 'product_id', 'concatenated_product', '', "and active='yes' order by concatenated_product  ASC");


    //  dd($product_id);
    // dd($gridenddate);


    $this->data['product_summary'] = \DB::select("SELECT m_products_t.concatenated_product as product,ROUND(SUM(i_qoh_detail_t.qoh_trx_qty)* -1, 2) AS qty,DATE_FORMAT(i_qoh_detail_t.created_at, '%b-%y') 
      AS yr_month FROM `i_qoh_detail_t`   
      LEFT JOIN m_products_t ON m_products_t.product_id = i_qoh_detail_t.product_id
      WHERE i_qoh_detail_t.subinventory_id='6' $wh1
      AND DATE(i_qoh_detail_t.created_at) BETWEEN '$start_date' AND '$end_date' AND m_products_t.product_group_id='$product_group' AND m_products_t.active='Yes' 
      GROUP BY i_qoh_detail_t.product_id,DATE_FORMAT(i_qoh_detail_t.created_at, '%b-%y') ORDER BY i_qoh_detail_t.created_at ASC");


    $this->data['value_summary'] = \DB::select("SELECT v1.product,v1.yr_month,ABS(v1.p_qty) as p_qty,ROUND(ABS(v1.p_qty) * v1.cost, 2) 
         AS value, v1.cost FROM (SELECT 
        m_products_t.concatenated_product AS product,qoh_source,
        ROUND(SUM(i_qoh_detail_t.qoh_trx_qty) * -1, 2) AS p_qty,
        i_qoh_detail_t.product_id,
        i_qoh_detail_t.batch_number,
        DATE_FORMAT(i_qoh_detail_t.created_at, '%b-%y') AS yr_month,
        (
            SELECT 
                cost 
            FROM 
                i_qoh_detail_t AS sub_qoh 
            WHERE 
                sub_qoh.product_id = i_qoh_detail_t.product_id 
                AND sub_qoh.qoh_source = 'PURCHASE_STOREMOVE'
                          GROUP BY 
        i_qoh_detail_t.product_id,
        i_qoh_detail_t.batch_number
            LIMIT 1
        ) AS cost
    FROM 
        `i_qoh_detail_t`
    LEFT JOIN 
        m_products_t 
        ON m_products_t.product_id = i_qoh_detail_t.product_id
    WHERE 
        i_qoh_detail_t.subinventory_id = '6' 
        $wh1
        AND DATE(i_qoh_detail_t.created_at) BETWEEN '$start_date' AND '$end_date' 
        AND m_products_t.product_group_id = '$product_group' 
        AND m_products_t.active = 'Yes'
    GROUP BY 
        i_qoh_detail_t.product_id,
        DATE_FORMAT(i_qoh_detail_t.created_at, '%b-%y')
    ORDER BY 
        i_qoh_detail_t.created_at ASC )v1");

    return view('purchasedashboard.consumptionreport', $this->data);

  }

// movement report
       public function Movementrpt(Request $request)
{
    $product_name = $request->input('product_name');
    $start_date   = $request->input('start_date');
    $end_date     = $request->input('end_date');

    $wh = '';
    $depart = \Session::get('groupname');
    if ($depart == "7") {
        $wh = " AND group_name IN ('RAW MATERIALS','PACKING MATERIALS','CONSUMABLES','PROMOTIONAL ITEMS')";
    }

    $this->data['pro_group'] = $this->jcustomselecttool(
        'm_product_groups_t',
        'product_group_id',
        'group_name',
        '',
        "and active='yes' $wh order by group_name ASC"
    );
    $this->data['pro_name'] = $this->jcustomselecttool(
        'm_products_t',
        'product_id',
        'concatenated_product',
        '',
        "and active='yes' order by concatenated_product ASC"
    );

    $this->data['product_summary'] = \DB::select("
        SELECT 
            trx_date,
            product_id,
            concatenated_product,
            supplier_name,
            unit_price,
            SUM(purchase_qty) AS purchase_qty,
            SUM(issue_qty) AS issue_qty,
            SUM(cons_qty) AS cons_qty
        FROM (
            SELECT 
                h.invoice_date AS trx_date,
                l.product_id,
                p.concatenated_product,
                s.supplier_name,
                ROUND(SUM(l.qty),2) AS purchase_qty,
                l.unit_price,
                0 AS issue_qty,
                0 AS cons_qty
            FROM p_po_invoice_lines_t l
            JOIN p_po_invoice_hdr_t h ON l.po_invoice_id = h.po_invoice_id
            JOIN m_products_t p ON p.product_id = l.product_id
            JOIN m_supplier_t s ON s.supplier_id = h.supplier_id
            WHERE l.product_id = ?
              AND h.invoice_date BETWEEN ? AND ?
              AND h.po_invoice_status NOT IN ('rejected','cancelled')
            GROUP BY h.invoice_date, l.product_id, p.concatenated_product, s.supplier_name, l.unit_price
            UNION ALL
            SELECT 
                h.mtl_issue_date AS trx_date,
                l.product_id,
                hd.concatenated_product,
                '' AS supplier_name,
                0 AS purchase_qty,
                NULL AS unit_price,
                ROUND(SUM(l.mtl_issue_qty),2) AS issue_qty,
                0 AS cons_qty
            FROM w_materialissue_line_t l
            JOIN w_materialissue_hdr_t h ON h.w_materialissue_hdr_id = l.w_materialissue_hdr_id
            JOIN m_products_t p ON p.product_id = l.product_id
            JOIN m_products_t hd ON hd.product_id = h.product_id
            WHERE l.product_id = ?
              AND h.mtl_issue_date BETWEEN ? AND ?
            GROUP BY h.mtl_issue_date, l.product_id, p.concatenated_product

            UNION ALL

            SELECT 
                h.consumable_date AS trx_date,
                l.product_id,
                p.concatenated_product,
                '' AS supplier_name,
                0 AS purchase_qty,
                NULL AS unit_price,
                0 AS issue_qty,
                ROUND(SUM(l.qty),2) AS cons_qty
            FROM i_consumable_lines_t l
            JOIN i_consumable_hdr_t h ON h.consumable_hdr_id = l.consumable_hdr_id
            JOIN m_products_t p ON p.product_id = l.product_id
            WHERE l.product_id = ?
              AND h.consumable_date BETWEEN ? AND ?
            GROUP BY h.consumable_date, l.product_id, p.concatenated_product
        ) t
        GROUP BY trx_date, product_id, concatenated_product, supplier_name, unit_price
        ORDER BY trx_date
    ", [
        $product_name, $start_date, $end_date,
        $product_name, $start_date, $end_date,
        $product_name, $start_date, $end_date,
    ]);

    return view('purchasedashboard.moverpt', $this->data);
} 
	
	//supplier summary
	
	public function supplierSummary(Request $request)
{

    $emp_id = Session::get('emp_id');
    $group = Session::get('groupname');
    
    $fullAccessUsers  = [152];
    $fullAccessGroups = [1,4];

    $canViewAll = in_array($emp_id, $fullAccessUsers) ||
              in_array($group, $fullAccessGroups);

    // ✅ Default to current financial year if no input provided
    $currentMonth = date('n');
    $currentYear = date('Y');

    if ($currentMonth >= 4) {
        $default_start = date('Y-04-01', strtotime($currentYear . '-04-01'));
        $default_end   = date('Y-m-d');
    } else {
        $default_start = date('Y-04-01', strtotime(($currentYear - 1) . '-04-01'));
        $default_end   = date('Y-m-d');
    }

    $start_date = $request->input('start_date', $default_start);
    $end_date   = $request->input('end_date', $default_end);

    $whereUser = $canViewAll ? "1=1" : "ms.created_by = $emp_id";
    // ✅ Summary Query
    $this->data['suppliersummary'] = DB::selectOne("
        SELECT 
            (SELECT COUNT(*) FROM m_supplier_t WHERE active = 'yes' and $whereUser ) AS total_suppliers,
            COUNT(CASE WHEN DATE(ms.created_at) BETWEEN ? AND ? THEN 1 END) AS new_suppliers_count,
            GROUP_CONCAT(
                CASE 
                    WHEN DATE(ms.created_at) BETWEEN ? AND ? THEN ms.supplier_name 
                END ORDER BY ms.created_at SEPARATOR ', ') AS new_supplier_names,
            GROUP_CONCAT( CASE WHEN DATE(ms.created_at) BETWEEN ? AND ? THEN tb.first_name END ORDER BY ms.created_at SEPARATOR ', ' ) AS created_user FROM m_supplier_t ms 
                JOIN tb_users tb ON tb.id=ms.created_by WHERE ms.active = 'yes' and $whereUser
    ", [$start_date, $end_date, $start_date, $end_date, $start_date, $end_date]);

    // ✅ List of new suppliers
    $query = DB::table('m_supplier_t')
    ->select('m_supplier_t.supplier_name','tb_users.first_name',
        DB::raw('DATE(m_supplier_t.created_at) as created_date'))
    ->join('tb_users', 'tb_users.id', '=', 'm_supplier_t.created_by')
    ->where('m_supplier_t.active', 'yes')
    ->whereBetween(DB::raw('DATE(m_supplier_t.created_at)'), [$start_date, $end_date]);
 
    if (!$canViewAll) {
    $query->where('m_supplier_t.created_by', $emp_id);
        }

    $this->data['newsupplier'] = $query->orderBy('m_supplier_t.created_at', 'ASC')->get();


    // ✅ Always pass date values to view
    $this->data['start_date'] = $start_date;
    $this->data['end_date'] = $end_date;

    return view('purchasedashboard.suppliersummary', $this->data);
}
	
	//product summary
	
	public function productSummary(Request $request)
{
    // ✅ Default to current financial year if no input provided
    $currentMonth = date('n');
    $currentYear = date('Y');

    if ($currentMonth >= 4) {
        $default_start = date('Y-04-01', strtotime($currentYear . '-04-01'));
        $default_end   = date('Y-m-d');
    } else {
        $default_start = date('Y-04-01', strtotime(($currentYear - 1) . '-04-01'));
        $default_end   = date('Y-m-d');
    }

    $start_date = $request->input('start_date', $default_start);
    $end_date   = $request->input('end_date', $default_end);

    // ✅ Summary Query
     $this->data['productsummary'] = DB::selectOne("
        SELECT 
            (SELECT COUNT(*) FROM m_products_t WHERE active = 'yes' AND product_group_id IN ('2','3')) AS total_products,
            COUNT(CASE WHEN DATE(ms.created_at) BETWEEN ? AND ? THEN 1 END) AS new_products_count,
            GROUP_CONCAT(
                CASE 
                    WHEN DATE(ms.created_at) BETWEEN ? AND ? THEN ms.concatenated_product 
                END ORDER BY ms.created_at SEPARATOR ', '
            ) AS new_product_name
        FROM m_products_t ms
        WHERE ms.active = 'yes' AND ms.product_group_id IN ('2','3')
    ", [$start_date, $end_date, $start_date, $end_date]);

    // ✅ List of new products
    	$this->data['newproduct'] = DB::table('m_products_t')
        ->select('concatenated_product', DB::raw('DATE(created_at) as created_date'))
        ->where('active', 'yes')
		->wherein('product_group_id', [2, 3])
        ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
        ->orderBy('created_at', 'ASC')
        ->get();

    // ✅ Always pass date values to view
    $this->data['start_date'] = $start_date;
    $this->data['end_date'] = $end_date;

    return view('purchasedashboard.productsummary', $this->data);
}	

		//product issue delayed
	
	public function productissuedelay(Request $request)
{
    // ✅ Default financial year dates
    $currentMonth = date('n');
    $currentYear = date('Y');

    if ($currentMonth >= 4) {
        $default_start = date('Y-04-01', strtotime($currentYear . '-04-01'));
        $default_end   = date('Y-m-d');
    } else {
        $default_start = date('Y-04-01', strtotime(($currentYear - 1) . '-04-01'));
        $default_end   = date('Y-m-d');
    }

    $start_date = $request->input('start_date', $default_start);
    $end_date   = $request->input('end_date', $default_end);

    // ✅ Query for delayed product issue report
    $this->data['productissuedelay'] = DB::select("
        SELECT 
            wj.job_no, 
            wj.job_date, 
            mp.concatenated_product,
            wml.mtl_issue_qty, 
            wml.updated_at, 
            CASE  
                WHEN wml.receive_status = 2 THEN 'Issued in Delay'  
                ELSE 'Normal'  
            END AS sts,
            (
                SELECT MIN(pgl.created_at) 
                FROM p_grn_lines_t pgl
                WHERE pgl.product_id = wml.product_id
                AND pgl.created_at BETWEEN ? AND ?
            ) AS rec_dt
        FROM 
            w_materialissue_line_t wml
        LEFT JOIN 
            w_materialissue_hdr_t wmh ON wmh.w_materialissue_hdr_id = wml.w_materialissue_hdr_id
        LEFT JOIN 
            m_products_t mp ON mp.product_id = wml.product_id
        LEFT JOIN 
            w_jobcard_hdr_t wj ON wj.w_jobs_hdr_id = wmh.w_jobs_hdr_id
        WHERE 
            wml.receive_status = 2 
            AND wml.created_at BETWEEN ? AND ?
        ORDER BY 
            wml.updated_at DESC
    ", [$start_date, $end_date, $start_date, $end_date]);

    // ✅ Pass filters to the view
    $this->data['start_date'] = $start_date;
    $this->data['end_date']   = $end_date;

    return view('purchasedashboard.productissuedelay', $this->data);
}


	
}