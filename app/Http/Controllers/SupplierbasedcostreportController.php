<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Supplierbasedcostreport;
use Illuminate\Http\Request;

class SupplierbasedcostreportController extends Controller
{

  public function __construct()
  {
    $this->data = array();
    $this->data['urlmenu'] = $this->indexs();
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['pageFormtype'] = 'ajax';

  }
  public function index()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.Supplierbasedcostrpt', $this->data);

  }


  public function getsupplierbasedcost(Request $request)
  {

    $SQL = "SELECT * from (
        SELECT
        SUM(round(p_po_invoice_hdr_t.invoice_grand_total)) AS price,
        m_supplier_t.supplier_name
        FROM
        p_po_invoice_hdr_t
        LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id)
        WHERE 1 = 1 AND p_po_invoice_hdr_t.supplier_id != 0 AND p_po_invoice_hdr_t.company_id = 1 AND p_po_invoice_hdr_t.location_id = 1
        GROUP BY
        p_po_invoice_hdr_t.supplier_id ORDER BY price DESC) AS v1";

    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);
  }



  public function supplierdetailsindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.supplierdetailsrpt', $this->data);

  }


  public function getsupplierdetails(Request $request)
  {

    $SQL = "SELECT * from (
 SELECT
    m_supplier_t.supplier_id,
    m_supplier_t.supplier_number,
    m_supplier_t.supplier_name,
    m_suppliertypes_t.suppliertype_name,
    m_payment_terms_t.payment_term_name,
    m_supplier_sites_t.address,
    m_cities_t.city_name,
    m_states_t.state_name,
    m_countries_t.country_name,
    m_supplier_sites_t.pincode,
    m_supplier_sites_t.contact_person,
    m_supplier_sites_t.contact_number,
    m_supplier_sites_t.contact_mail,
    m_supplier_t.pan_number,
    m_supplier_sites_t.gst_number
FROM
    `m_supplier_t`
LEFT JOIN m_supplier_sites_t ON m_supplier_t.supplier_id = m_supplier_sites_t.supplier_id
LEFT JOIN m_suppliertypes_t ON m_supplier_t.supplier_type_id = m_suppliertypes_t.suppliertype_id
LEFT JOIN m_payment_terms_t ON m_supplier_t.default_payment_terms_id = m_payment_terms_t.payment_term_id
LEFT JOIN m_countries_t ON m_supplier_sites_t.country = m_countries_t.country_id
LEFT JOIN m_states_t ON m_supplier_sites_t.state = m_states_t.state_id
LEFT JOIN m_cities_t ON m_supplier_sites_t.city = m_cities_t.city_id  ORDER BY supplier_number DESC) AS v1";

    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);
  }


  public function expiryprdnearbyindex(Request $request)
  {
    // restrict illegal menu entry purpose - RATHI R

    $url = $request->path();

    $Controller = new Controller();
    $access = $Controller->Accessdined();

    $userAccess = json_decode($access, true);

    // Flatten the $userAccess array
    $flatUserAccess = [];
    foreach ($userAccess as $accessItem) {
      if (is_array($accessItem)) {
        $flatUserAccess = array_merge($flatUserAccess, $accessItem);
      } else {
        $flatUserAccess[] = $accessItem;
      }
    }

    if (!in_array($url, $flatUserAccess)) {
      return view('accessresticted.view');

    }

    // END


    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.expiryprdnearbyrpt', $this->data);

  }

  public function getexpiryprdnearby(Request $request)
  {

    $wh = '';
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';

    $wh1 = "";

    if (isset($start_date)) {

      $wh1 .= " and DATE(v1.created_at) <= '$start_date'";

    }

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');


    $SQL = "SELECT * from (SELECT
    f.product_id,
    f.concatenated_product,
    f.group_name,
    f.product_group_id,
    batch_number,
    locator_name,
    locator_code,
    product_expire_date,
    manufacturer_date,
    created_at,
    d,
    
   ( CASE
    WHEN (diff > 0 AND diff <= 3) THEN 'Less than 90 Days' 
    WHEN (diff > 3 AND diff <= 6) THEN 'Less than 180 Days'
    WHEN (diff < 0) THEN 'Expired'
    ELSE
    'Above 180 Days'
    END ) as expiry
FROM
    (
    SELECT
        i_qoh_detail_t.product_id,
        m_products_t.concatenated_product,
        m_product_groups_t.group_name,
        m_product_groups_t.product_group_id,
        ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),
        2) d,
        i_qoh_detail_t.product_expire_date,
        i_qoh_detail_t.manufacturer_date,
        m_sublocators_t.locator_name,
        m_sublocators_t.locator_code,
        i_qoh_detail_t.batch_number,
        DATE(i_qoh_detail_t.created_at) as created_at,
        DATE(i_qoh_detail_t.product_expire_date) dd,
        TIMESTAMPDIFF(MONTH,CURDATE(), i_qoh_detail_t.product_expire_date) diff
    FROM
        `i_qoh_detail_t`
        LEFT JOIN m_products_t ON m_products_t.product_id = i_qoh_detail_t.product_id
        LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id
        LEFT JOIN m_sublocators_t ON i_qoh_detail_t.locator_id = m_sublocators_t.sublocator_id
    WHERE
        i_qoh_detail_t.product_id != 0 and i_qoh_detail_t.subinventory_id != 0 and i_qoh_detail_t.locator_id != 0 AND m_product_groups_t.group_name = 'FINISHED GOODS'
    GROUP BY
        i_qoh_detail_t.product_id,
        i_qoh_detail_t.batch_number,
        i_qoh_detail_t.locator_id) f
        WHERE
        d > 0 ) as v1 where 1=1 $wh1 $wh  ORDER BY product_id ASC";


    $result = \DB::select($SQL);

    return DataTables::of($result)->make(true);

  }


  public function purchaseinvoverduesindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.purchaseinvoverduerpt', $this->data);

  }

  public function getpurchaseinvoverdue(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    //dd($start_date);

    $SQL = "SELECT * from (
SELECT
    m_supplier_t.supplier_id,
    m_supplier_t.supplier_name,
    p_po_invoice_hdr_t.bill_number,
    p_po_invoice_hdr_t.po_invoice_status,
    p_po_invoice_hdr_t.invoice_date,
    p_po_invoice_hdr_t.invoice_grand_total,
    p_po_invoice_hdr_t.due_date,
    CONCAT(LEFT(MONTHNAME(p_po_invoice_hdr_t.due_date),3), '-', year(p_po_invoice_hdr_t.due_date)) as due_month,
    p_po_invoice_hdr_t.paid_amount,
    p_po_invoice_hdr_t.balance_amount
FROM
    p_po_invoice_hdr_t   
LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id
WHERE p_po_invoice_hdr_t.balance_amount > 0 AND p_po_invoice_hdr_t.due_date <= ?) AS v1";

    $results = \DB::select($SQL, [$start_date]);

    return response()->json(['data' => $results]);
  }


  public function customerdetailsindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.customerdetailsrpt', $this->data);

  }


  public function getcustomerdetails(Request $request)
  {



    $SQL = "SELECT * FROM (
SELECT
        m_customers_t.customer_id,
    m_customers_t.customer_name,
    m_customers_t.customer_number,
    m_customers_t.pan_no,
    m_customers_t.active,
    m_customers_t.tcs_applicable,
    m_customer_types_t.customer_type,
    m_customer_sites_t.customer_site_name,
    m_customer_sites_t.site_type,
    m_customer_sites_t.address,
    m_cities_t.city_name,
    m_states_t.state_name,
    m_countries_t.country_name,
    m_customer_sites_t.pincode,
    m_customer_sites_t.contact_number,
    m_customer_sites_t.contact_person,
    m_customer_sites_t.contact_mail,
    m_customer_sites_t.gst_no
FROM 
m_customers_t
LEFT JOIN m_customer_sites_t ON m_customer_sites_t.customer_id = m_customers_t.customer_id 
LEFT JOIN m_customer_types_t ON m_customers_t.customer_type_id = m_customer_types_t.customer_type_id
LEFT JOIN m_cities_t ON m_customer_sites_t.city = m_cities_t.city_id
LEFT JOIN m_states_t ON m_customer_sites_t.state = m_states_t.state_id
LEFT JOIN m_countries_t ON m_customer_sites_t.country = m_countries_t.country_id) AS v1";


    $results = \DB::select($SQL);


    return response()->json(['data' => $results]);
  }


  public function dispatchdetailsindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.dispatchdetailsrpt', $this->data);

  }


  public function getdispatchdetails(Request $request)
  {


    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * FROM (
    SELECT
    s_dispatch_hdr_t.so_dispatch_hdr_id,
    s_dispatch_hdr_t.dispatch_number,
    s_dispatch_hdr_t.dispatch_date,
    s_dispatch_hdr_t.dispatch_status,
    s_dispatch_hdr_t.reference_no,
    s_dispatch_hdr_t.pack_weight,
    s_dispatch_hdr_t.packaging_qty,
    m_products_t.concatenated_product,
    (CASE WHEN s_dispatch_hdr_t.ship_to_customer_id != 0 THEN
    m_customers_t.customer_name
    ELSE
    hr_employee_t.first_name
    END) as customer_name,
    s_dispatch_lines_t.so_qty,
    s_dispatch_lines_t.dispatch_qty,
    s_dispatched_qty_t.batch_no,
    s_dispatched_qty_t.sublocator_id,
    s_dispatched_qty_t.subinventory_id,
    s_dispatched_qty_t.issue_qoh,
    s_dispatched_qty_t.free_val
FROM
    `s_dispatch_hdr_t`
LEFT JOIN s_dispatch_lines_t ON s_dispatch_hdr_t.so_dispatch_hdr_id = s_dispatch_lines_t.so_dispatch_hdr_id
LEFT JOIN m_products_t ON s_dispatch_lines_t.product_id = m_products_t.product_id
LEFT JOIN m_customers_t ON s_dispatch_hdr_t.ship_to_customer_id = m_customers_t.customer_id
LEFT JOIN hr_employee_t ON s_dispatch_hdr_t.employee_id = hr_employee_t.employee_id
LEFT JOIN s_dispatched_qty_t ON s_dispatch_lines_t.so_dispatch_line_id = s_dispatched_qty_t.so_dispatch_line_id where 1=1 and s_dispatch_hdr_t.dispatch_date BETWEEN  ? and ?) AS v1";


    $results = \DB::select($SQL, [$start_date, $end_date]);


    return response()->json(['data' => $results]);
  }


  public function transporttrackindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.transporttrackrpt', $this->data);

  }


  public function gettransporttrack(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
    SELECT
    s_invoice_hdr_t.invoice_hdr_id,
    s_invoice_hdr_t.invoice_number,
    s_invoice_hdr_t.invoice_date,
    s_invoice_hdr_t.invoice_type,
    s_invoice_hdr_t.invoice_status,
    s_invoice_hdr_t.invoice_grand_total,
    s_invoice_hdr_t.lr_no,
    s_invoice_hdr_t.lr_date,
    s_invoice_hdr_t.lr_status,
    s_invoice_hdr_t.delivery_date,
    s_dispatch_hdr_t.dispatch_number,
    s_dispatch_hdr_t.packaging_qty,
    s_dispatch_hdr_t.pack_weight,
    (CASE WHEN s_invoice_hdr_t.ship_to_customer_id > 0 THEN
    m_states_t.state_name
     ELSE
     state.state_name
     END) as state_name,
    (CASE WHEN s_invoice_hdr_t.ship_to_customer_id > 0 THEN
    m_customers_t.customer_name
    ELSE 
    hr_employee_t.first_name
    END) as customer_name,
    m_frieghtcarriers_hdr_t.carrier_name
    FROM s_invoice_hdr_t
    LEFT JOIN s_dispatch_hdr_t ON s_invoice_hdr_t.reference_source_id = s_dispatch_hdr_t.so_dispatch_hdr_id
    LEFT JOIN m_customers_t ON s_invoice_hdr_t.ship_to_customer_id = m_customers_t.customer_id
    LEFT JOIN hr_employee_t ON s_invoice_hdr_t.employee_id = hr_employee_t.employee_id
    LEFT JOIN m_frieghtcarriers_hdr_t ON s_invoice_hdr_t.ar_frieghtcarriers_hdr_id = m_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id
    LEFT JOIN m_customer_sites_t ON s_dispatch_hdr_t.deliver_to_location = m_customer_sites_t.customer_site_id
    LEFT JOIN m_states_t ON m_customer_sites_t.state = m_states_t.state_id
    LEFT JOIN hr_emp_contact ON s_dispatch_hdr_t.deliver_to_location = hr_emp_contact.employee_id
    LEFT JOIN m_states_t as state ON hr_emp_contact.permanent_state = state.state_id
    WHERE 1=1 and s_invoice_hdr_t.invoice_date BETWEEN  ? and ?) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
  }

  public function salesorderindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.salesordertemp', $this->data);

  }

  public function getsalesorder(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "SELECT * from (
SELECT
    s_invoice_hdr_t.invoice_hdr_id,
    s_invoice_hdr_t.invoice_number,
    s_invoice_hdr_t.invoice_date,
    s_invoice_hdr_t.invoice_type,
    s_invoice_hdr_t.invoice_status,
    round((s_invoice_hdr_t.invoice_grand_total-s_invoice_hdr_t.invoice_tax_total),0) as Assbl,
    round(s_invoice_hdr_t.invoice_grand_total,0) as Inv_val,
    s_invoice_hdr_t.lr_no,
    s_invoice_hdr_t.lr_date,
    s_invoice_hdr_t.lr_status,
    s_invoice_hdr_t.delivery_date,
    s_dispatch_hdr_t.dispatch_date,
    s_dispatch_hdr_t.dispatch_number,
    s_dispatch_hdr_t.packaging_qty,
    s_dispatch_hdr_t.pack_weight,
    s_salesorder_hdr_t.sales_order_no,
    s_salesorder_hdr_t.sales_order_date,
    round(s_salesorder_hdr_t.order_total,0) as ord_val,
    datediff(s_invoice_hdr_t.invoice_date,s_salesorder_hdr_t.sales_order_date)+1 as TAT_days_Ord_Inv,
    datediff(s_invoice_hdr_t.invoice_date,s_dispatch_hdr_t.dispatch_date)+1 as TAT_days_Inv_Disp,
    datediff(s_invoice_hdr_t.delivery_date,s_invoice_hdr_t.lr_date)+1 as TAT_days_Disp_Dlvy,
    date_format(s_salesorder_hdr_t.sales_order_date, '%b') as month,
    (CASE WHEN DATE_FORMAT(CURDATE(), '%Y%m') = DATE_FORMAT(s_salesorder_hdr_t.sales_order_date, '%Y%m') THEN
    concat(year(s_salesorder_hdr_t.sales_order_date)-1,'-',year(s_salesorder_hdr_t.sales_order_date))
    WHEN YEAR(CURDATE() + INTERVAL 9 MONTH) = YEAR(s_salesorder_hdr_t.sales_order_date + INTERVAL 9 MONTH) THEN
    concat(year(s_salesorder_hdr_t.sales_order_date),'-',year(s_salesorder_hdr_t.sales_order_date)+1)
    END)as Fn_Year,
	(select                
    hr_employee_t.first_name from hr_employee_t WHERE s_salesorder_hdr_t.created_by=hr_employee_t.employee_id ) as created_by,               
	(select                
    hr_employee_t.first_name from hr_employee_t WHERE s_salesorder_hdr_t.last_updated_by=hr_employee_t.employee_id ) as approved_by,
    (CASE WHEN s_invoice_hdr_t.ship_to_customer_id > 0 THEN
    m_states_t.state_name
     ELSE
     state.state_name
     END) as state_name,
    (CASE WHEN s_invoice_hdr_t.ship_to_customer_id > 0 THEN
    m_customers_t.customer_name
    ELSE 
    hr_employee_t.first_name
    END) as customer_name,
    m_frieghtcarriers_hdr_t.carrier_name
    FROM s_invoice_hdr_t
    LEFT JOIN s_dispatch_hdr_t ON s_invoice_hdr_t.reference_source_id = s_dispatch_hdr_t.so_dispatch_hdr_id
    LEFT JOIN s_salesorder_hdr_t ON s_invoice_hdr_t.ar_sales_hdr_id = s_salesorder_hdr_t.sales_hdr_id
    LEFT JOIN m_customers_t ON s_invoice_hdr_t.ship_to_customer_id = m_customers_t.customer_id
    LEFT JOIN hr_employee_t ON s_invoice_hdr_t.employee_id = hr_employee_t.employee_id
    LEFT JOIN m_frieghtcarriers_hdr_t ON s_invoice_hdr_t.ar_frieghtcarriers_hdr_id = m_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id
    LEFT JOIN m_customer_sites_t ON s_dispatch_hdr_t.deliver_to_location = m_customer_sites_t.customer_site_id
    LEFT JOIN m_states_t ON m_customer_sites_t.state = m_states_t.state_id
    LEFT JOIN hr_emp_contact ON s_dispatch_hdr_t.deliver_to_location = hr_emp_contact.employee_id
    LEFT JOIN m_states_t as state ON hr_emp_contact.permanent_state = state.state_id
    WHERE 1=1 and s_salesorder_hdr_t.sales_order_date BETWEEN  ? and ? AND s_salesorder_hdr_t.order_type_id!='sample') AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
  }

  public function samplesorderindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.samplesordertemp', $this->data);

  }

  public function getsamplesorder(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "SELECT * from (
SELECT
    s_invoice_hdr_t.invoice_hdr_id,
    s_invoice_hdr_t.invoice_number,
    s_invoice_hdr_t.invoice_date,
    s_invoice_hdr_t.invoice_type,
    s_invoice_hdr_t.invoice_status,
    round((s_invoice_hdr_t.invoice_grand_total-s_invoice_hdr_t.invoice_tax_total),0) as Assbl,
    round(s_invoice_hdr_t.invoice_grand_total,0) as Inv_val,
    s_invoice_hdr_t.lr_no,
    s_invoice_hdr_t.lr_date,
    s_invoice_hdr_t.lr_status,
    s_invoice_hdr_t.delivery_date,
    s_dispatch_hdr_t.dispatch_date,
    s_dispatch_hdr_t.dispatch_number,
    s_dispatch_hdr_t.packaging_qty,
    s_dispatch_hdr_t.pack_weight,
    s_salesorder_hdr_t.sales_order_no,
    s_salesorder_hdr_t.sales_order_date,
    round(s_salesorder_hdr_t.order_total,0) as ord_val,
    datediff(s_invoice_hdr_t.invoice_date,s_salesorder_hdr_t.sales_order_date)+1 as TAT_days_Ord_Inv,
    datediff(s_invoice_hdr_t.invoice_date,s_dispatch_hdr_t.dispatch_date)+1 as TAT_days_Inv_Disp,
    datediff(s_invoice_hdr_t.delivery_date,s_invoice_hdr_t.lr_date)+1 as TAT_days_Disp_Dlvy,
    date_format(s_salesorder_hdr_t.sales_order_date, '%b') as month,
    concat(year(s_salesorder_hdr_t.sales_order_date)-1,'-',year(s_salesorder_hdr_t.sales_order_date)) as Fn_Year,
	(select                
    hr_employee_t.first_name from hr_employee_t WHERE s_salesorder_hdr_t.created_by=hr_employee_t.employee_id ) as created_by,               
	(select                
    hr_employee_t.first_name from hr_employee_t WHERE s_salesorder_hdr_t.last_updated_by=hr_employee_t.employee_id ) as approved_by,
    (CASE WHEN s_invoice_hdr_t.ship_to_customer_id > 0 THEN
    m_states_t.state_name
     ELSE
     state.state_name
     END) as state_name,
    (CASE WHEN s_invoice_hdr_t.ship_to_customer_id > 0 THEN
    m_customers_t.customer_name
    ELSE 
    hr_employee_t.first_name
    END) as customer_name,
    m_frieghtcarriers_hdr_t.carrier_name
    FROM s_invoice_hdr_t
    LEFT JOIN s_dispatch_hdr_t ON s_invoice_hdr_t.reference_source_id = s_dispatch_hdr_t.so_dispatch_hdr_id
    LEFT JOIN s_salesorder_hdr_t ON s_invoice_hdr_t.ar_sales_hdr_id = s_salesorder_hdr_t.sales_hdr_id
    LEFT JOIN m_customers_t ON s_invoice_hdr_t.ship_to_customer_id = m_customers_t.customer_id
    LEFT JOIN hr_employee_t ON s_invoice_hdr_t.employee_id = hr_employee_t.employee_id
    LEFT JOIN m_frieghtcarriers_hdr_t ON s_invoice_hdr_t.ar_frieghtcarriers_hdr_id = m_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id
    LEFT JOIN m_customer_sites_t ON s_dispatch_hdr_t.deliver_to_location = m_customer_sites_t.customer_site_id
    LEFT JOIN m_states_t ON m_customer_sites_t.state = m_states_t.state_id
    LEFT JOIN hr_emp_contact ON s_dispatch_hdr_t.deliver_to_location = hr_emp_contact.employee_id
    LEFT JOIN m_states_t as state ON hr_emp_contact.permanent_state = state.state_id
    WHERE 1=1 and s_salesorder_hdr_t.sales_order_date BETWEEN  ? and ? AND s_salesorder_hdr_t.order_type_id='sample') AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
  }

  public function distributortargetindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.distributortargetrpt', $this->data);

  }

  public function getdistributortarget(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
   SELECT
    targets_id,
    stockist_name,
    LEFT(
        MONTHNAME(STR_TO_DATE(month, '%m')),
        3
    ) AS mon,
    month,
    Year,
    target_qty,
    batch_date
FROM
    target_upload_tbl
WHERE
    1 = 1 and batch_date BETWEEN ? and ? ) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
  }


  public function einvoicerptforhdrindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.einvoicerptforhdr', $this->data);

  }


  public function geteinvoicerptforhdr(Request $request)
  {

    $wh = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT
    *
FROM
    (
    SELECT
        s_invoice_hdr_t.invoice_hdr_id,
        IF(
            s_invoice_hdr_t.invoice_type = 'EXPORT INVOICE' OR s_invoice_hdr_t.invoice_type = 'EXPORT SAMPLE',
            'EXPWOP',
            'B2B'
        ) AS supplier_type_code,
        'No' AS igst_on_intra,
        'Tax Invoice' AS doc_type,
        s_invoice_hdr_t.invoice_tax_total,
        m_customer_sites_t.state,
        s_invoice_hdr_t.invoice_number AS doc_number,
        DATE_FORMAT(
            s_invoice_hdr_t.invoice_date,
            '%d/%m/%Y'
        ) AS doc_date,
        m_customers_t.customer_name AS buyer_legal_name,
        m_customers_t.customer_name AS buyer_trade_name,
        m_customers_t.customer_name AS ship_legal_name,
        m_customers_t.customer_name AS ship_trade_name,
        IF(
            s_invoice_hdr_t.invoice_type = 'EXPORT INVOICE' OR s_invoice_hdr_t.invoice_type = 'EXPORT SAMPLE',
            ROUND(
                (
                SELECT
                    conversion_rate
                FROM
                    `f_account_exchangerates_t`
                WHERE
                    from_currency_id = s_invoice_hdr_t.invoice_currency AND s_invoice_hdr_t.invoice_date BETWEEN from_date AND to_date
                LIMIT 1
            ) *(
                ROUND(
                    s_invoice_hdr_t.invoice_grand_total - s_invoice_hdr_t.invoice_tax_total - s_invoice_hdr_t.tcs_amount,
                    2
                )
            ),
            2
            ),
            (
                ROUND(
                    s_invoice_hdr_t.invoice_grand_total - s_invoice_hdr_t.invoice_tax_total - s_invoice_hdr_t.tcs_amount,
                    2
                )
            )
        ) AS tot_tax_value,
        (
            CASE WHEN m_customer_sites_t.state != 31 THEN ROUND(
                s_invoice_hdr_t.invoice_tax_total,
                2
            )
        END
) AS igst_amt,
m_customer_sites_t.gst_no AS buyer_gst,
m_states_t.state_name AS buyer_pos,
m_customer_sites_t.address AS buyer_addr1,
'' AS buyer_addr2,
m_cities_t.city_name AS buyer_location,
m_customer_sites_t.pincode AS buyer_pincode,
m_states_t.state_name AS buyer_state,
m_customer_sites_t.contact_number AS buyer_ph_number,
m_customer_sites_t.contact_mail AS buyer_email,
shipping_site.gst_no as ship_gst,
shipping_site.address as ship_addr1,
'' AS ship_addr2,
ship_state.state_name as shipp_state,
ship_city.city_name as shipp_city,
shipping_site.pincode as ship_pincode,
shipping_site.contact_number as ship_ph_number,
shipping_site.contact_mail as ship_email,        
'' AS cess_amt,
'' AS state_cess_amt,
'' AS discount,
s_invoice_hdr_t.tcs_amount AS other_charges,
s_invoice_hdr_t.round_off,
IF(
    s_invoice_hdr_t.invoice_type = 'EXPORT INVOICE' OR s_invoice_hdr_t.invoice_type = 'EXPORT SAMPLE',
    ROUND(
        (
        SELECT
            conversion_rate
        FROM
            `f_account_exchangerates_t`
        WHERE
            from_currency_id = s_invoice_hdr_t.invoice_currency AND s_invoice_hdr_t.invoice_date BETWEEN from_date AND to_date
        LIMIT 1
    ) * ROUND(
        s_invoice_hdr_t.invoice_grand_total,
        2
    ),
    2
    ),
    ROUND(
        s_invoice_hdr_t.invoice_grand_total,
        2
    )
) AS tot_inv_value,
'' AS exp_duty_amt,
'' AS error_list
FROM
    s_invoice_hdr_t
LEFT JOIN m_customers_t ON s_invoice_hdr_t.ship_to_customer_id = m_customers_t.customer_id
LEFT JOIN s_dispatch_hdr_t ON s_dispatch_hdr_t.so_dispatch_hdr_id = s_invoice_hdr_t.reference_source_id        
LEFT JOIN hr_employee_t ON s_invoice_hdr_t.employee_id = hr_employee_t.employee_id
LEFT JOIN m_customer_sites_t ON s_invoice_hdr_t.bill_to_address_id = m_customer_sites_t.customer_site_id
LEFT JOIN m_customer_sites_t as shipping_site ON shipping_site.customer_site_id = s_dispatch_hdr_t.deliver_to_location
LEFT JOIN m_states_t as ship_state ON ship_state.state_id = shipping_site.state
LEFT JOIN m_cities_t as ship_city ON ship_city.city_id = shipping_site.city        
LEFT JOIN m_states_t ON m_customer_sites_t.state = m_states_t.state_id
LEFT JOIN m_cities_t ON m_cities_t.city_id = m_customer_sites_t.city
WHERE
    (
        s_invoice_hdr_t.invoice_type = 'STANDARD' OR s_invoice_hdr_t.invoice_type = 'EXPORT INVOICE' OR s_invoice_hdr_t.invoice_type = 'EXPORT SAMPLE'
    ) AND s_invoice_hdr_t.invoice_status = 'APPROVED' AND s_invoice_hdr_t.invoice_date BETWEEN '$start_date' AND '$end_date'
) AS v1
WHERE
    1 = 1 $wh  ORDER BY invoice_hdr_id DESC ";

    $result1 = \DB::select($SQL);

    foreach ($result1 as $key => $value) {

      $first = $result1[$key]->state;
      $tax_amt = $result1[$key]->invoice_tax_total;

      if ($first == "31") {
        $rate1 = ($tax_amt / 2);
        $rate = (round($rate1, 2));
        $result1[$key]->cgst = $rate;
        $result1[$key]->sgst = $rate;
        $result1[$key]->igst = 0;
      } else {
        $result1[$key]->igst = 0;
        $result1[$key]->sgst = 0;
        $result1[$key]->cgst = 0;
      }

    }
    return response()->json(['data' => $result1]);

  }

  public function geteinvoicerptforcrd(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';



    $SQL = "SELECT * from (
 SELECT *, (CASE WHEN state != 31 THEN
     round(item_total-taxable_value,2)
     END) as igst_amt, round(item_total-taxable_value,2) AS invoice_tax_total  FROM (SELECT
    so_rma_hdr_t.so_rma_hdr_id,
    'B2B' AS supplier_type_code,
    'No' AS igst_on_intra,
    'Credit Note' AS doc_type,
    m_customer_sites_t.state,
    so_rma_hdr_t.rma_ref_no AS doc_number,
    DATE_FORMAT(
        so_rma_hdr_t.return_date,
        '%d/%m/%Y'
    ) AS doc_date,
    m_customers_t.customer_name AS buyer_legal_name,
    m_customers_t.customer_name AS buyer_trade_name,
    m_customer_sites_t.gst_no AS buyer_gst,
    m_states_t.state_name AS buyer_pos,
    m_customer_sites_t.address AS buyer_addr1,
    '' AS buyer_addr2,
    m_cities_t.city_name AS buyer_location,
    m_customer_sites_t.pincode AS buyer_pincode,
    m_states_t.state_name AS buyer_state,
    m_customer_sites_t.contact_number AS buyer_ph_number,
    m_customer_sites_t.contact_mail AS buyer_mail,
    so_rma_lines_t.line_no AS s_no,
    m_products_t.concatenated_product AS prd_desc,
    REPLACE(f_gst_code_hdr_t.classification_code, '.', '') AS hsn_cde,
    so_rma_lines_t.returnqty AS quantity,
    UPPER(m_uom_codes_t.code_meaning) AS unit,
    so_rma_lines_t.rate AS unit_price,
    ROUND((so_rma_lines_t.returnqty * so_rma_lines_t.rate),2) AS gross_amt,
    ROUND(so_rma_lines_t.discount_amount,2) AS discount,
    '' AS pre_tax_value,
    ROUND(((so_rma_lines_t.returnqty * so_rma_lines_t.rate) - so_rma_lines_t.discount_amount),2) AS taxable_value,
    m_tax_group_t.display_name AS gst_rate,
    m_tax_group_t.tax_group_name,
    '' AS cess_rate,
    '' AS cess_amt_adval,
    '' AS cess_non_adval_amt,
    '' AS state_cess_rate,
    '' AS state_cess_adval_amt,
    '' AS state_cess_non_adval_amt,
    '0' AS other_charges,
    so_rma_lines_t.total_amount AS item_total,
    '' AS tot_taxable_value,
    '' AS tot_sgst_amt,
    '' AS tot_cgst_amt,
    '' AS tot_igst_amt,
    '' AS tot_cess_amt,
    '' AS tot_state_cess_amt,
    '' AS tot_discount,
    '' AS tot_other_charges,
    '' AS round_off,
    so_rma_hdr_t.total_amount as tot_inv_value,
    '' AS error_list
    FROM
    so_rma_hdr_t
    LEFT JOIN so_rma_lines_t ON so_rma_hdr_t.so_rma_hdr_id = so_rma_lines_t.so_rma_hdr_id
LEFT JOIN m_customers_t ON so_rma_hdr_t.customerid = m_customers_t.customer_id
LEFT JOIN m_customer_sites_t ON so_rma_hdr_t.ship_to_address_id = m_customer_sites_t.customer_site_id
LEFT JOIN m_states_t ON m_customer_sites_t.state = m_states_t.state_id
LEFT JOIN m_cities_t ON m_cities_t.city_id = m_customer_sites_t.city
LEFT JOIN m_products_t ON m_products_t.product_id = so_rma_lines_t.product_id
LEFT JOIN f_gst_code_hdr_t ON m_products_t.defalut_hsn_code = f_gst_code_hdr_t.gst_code_hdr_id
LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = so_rma_lines_t.uom_code_id
LEFT JOIN m_tax_group_t ON so_rma_lines_t.tax_group_id = m_tax_group_t.tax_group_id
WHERE so_rma_hdr_t.return_date BETWEEN ? and ?)v1 ) AS vikki";

    $result1 = \DB::select($SQL, [$start_date, $end_date]);
    foreach ($result1 as $key => $value) {

      $first = $result1[$key]->state;
      $tax_amt = $result1[$key]->invoice_tax_total;

      if ($first == "31") {
        $rate1 = ($tax_amt / 2);
        $rate = (round($rate1, 2));
        $result1[$key]->cgst = $rate;
        $result1[$key]->sgst = $rate;
        $result1[$key]->igst = 0;
      } else {
        $result1[$key]->cgst = 0;
        $result1[$key]->sgst = 0;
        $result1[$key]->igst = 0;
      }

    }
    return response()->json(['data' => $result1]);
  }


  public function gsthsnrptindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.gsthsnrpt', $this->data);

  }


  public function getgsthsnrpt(Request $request)
  {

    $month = $request->month ?: '';
    $year = $request->year ?: '';

    $dat = " and month(s_invoice_hdr_t.invoice_date)='$month' and year(s_invoice_hdr_t.invoice_date)='$year'";

    $SQL = "SELECT * from (SELECT
    f_gst_code_hdr_t.classification_code,
    m_uom_codes_t.uom_code,
    s_invoice_lines_t.hsn_code,
    SUM(s_invoice_lines_t.qty) AS qty,
    ROUND(SUM(
        s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount
    ),2) AS taxable_value,
    m_tax_group_t.tax_group_name,
    m_tax_group_t.display_name as 'tax_group_percent',
    CONCAT(
        LEFT(
            MONTHNAME(s_invoice_hdr_t.invoice_date),
            3
        ),
        '-',
        YEAR(s_invoice_hdr_t.invoice_date)
    ) AS month_year,
    IF(
        SUBSTRING(
            m_tax_group_t.tax_group_name,
            1,
            3
        ) = 'GST',
        ROUND(
            SUM(
               s_invoice_lines_t.tax_amount / 2
            ),
            2
        ),
        0
    ) AS cgst,
    IF(
        SUBSTRING(
            m_tax_group_t.tax_group_name,
            1,
            3
        ) = 'GST',
        ROUND(
            SUM(
                s_invoice_lines_t.tax_amount / 2
            ),
            2
        ),
        0
    ) AS sgst,
    IF(
        SUBSTRING(
            m_tax_group_t.tax_group_name,
            1,
            3
        ) = 'IGS',
        ROUND(
            SUM(s_invoice_lines_t.tax_amount),
            2
        ),
        0
    ) AS igst
FROM
    `s_invoice_lines_t`
LEFT JOIN f_gst_code_hdr_t ON f_gst_code_hdr_t.gst_code_hdr_id = s_invoice_lines_t.hsn_code
LEFT JOIN s_invoice_hdr_t ON s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id
LEFT JOIN m_tax_group_t ON m_tax_group_t.tax_group_id = s_invoice_lines_t.tax_group_id
LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = s_invoice_lines_t.uom_code_id
WHERE 1=1
    $dat AND s_invoice_hdr_t.invoice_status = 'APPROVED' AND s_invoice_hdr_t.invoice_type = 'STANDARD'
GROUP BY
    f_gst_code_hdr_t.classification_code,
    s_invoice_lines_t.tax_group_id ORDER BY classification_code DESC) as v1";

    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);

  }


  public function einvoicerptforlineindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.einvoicerptforline', $this->data);

  }

  public function geteinvoicerptforline(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
    SELECT
    s_invoice_hdr_t.invoice_hdr_id,
    'Tax Invoice' AS doc_type,
    s_invoice_hdr_t.invoice_number AS doc_number,
    DATE_FORMAT(
        s_invoice_hdr_t.invoice_date,
        '%d/%m/%Y'
    ) AS doc_date,
    s_invoice_lines_t.line_no AS s_no,
    m_products_t.concatenated_product AS prd_desc,
    REPLACE(f_gst_code_hdr_t.classification_code, '.', '') AS hsn_cde,
    s_invoice_lines_t.qty AS quantity,
    s_invoice_lines_t.free_qty,
    'BOX' AS unit,
    if(s_invoice_hdr_t.invoice_type = 'EXPORT INVOICE'  OR s_invoice_hdr_t.invoice_type = 'EXPORT SAMPLE',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*ROUND(
        s_invoice_lines_t.unit_price,
        2
    ),2),ROUND(
        s_invoice_lines_t.unit_price,
        2
    )) as unit_price,
    if(s_invoice_hdr_t.invoice_type = 'EXPORT INVOICE'  OR s_invoice_hdr_t.invoice_type = 'EXPORT SAMPLE',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*ROUND(
        s_invoice_lines_t.qty * s_invoice_lines_t.unit_price,
        2
    ),2),ROUND(
        s_invoice_lines_t.qty * s_invoice_lines_t.unit_price,
        2
    )) AS gross_amt,
    if(s_invoice_hdr_t.invoice_type = 'EXPORT INVOICE'  OR s_invoice_hdr_t.invoice_type = 'EXPORT SAMPLE',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*ROUND(
        (
            (
                100 -(
                    (
                        s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount
                    ) /(
                        s_invoice_lines_t.qty * s_invoice_lines_t.unit_price
                    )
                ) * 100
            ) *(
                s_invoice_lines_t.qty * s_invoice_lines_t.unit_price
            )
        ) / 100,
        2
    ),2),ROUND(
        (
            (
                100 -(
                    (
                        s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount
                    ) /(
                        s_invoice_lines_t.qty * s_invoice_lines_t.unit_price
                    )
                ) * 100
            ) *(
                s_invoice_lines_t.qty * s_invoice_lines_t.unit_price
            )
        ) / 100,
        2
    )) AS discount,
    '' AS pre_tax_value,
    if(s_invoice_hdr_t.invoice_type = 'EXPORT INVOICE'  OR s_invoice_hdr_t.invoice_type = 'EXPORT SAMPLE',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*ROUND(
        s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount,
        2
    ),2),ROUND(
        s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount,
        2
    )) AS taxable_value,
    m_tax_group_t.display_name AS gst_rate,
    m_tax_group_t.tax_group_name,
    ROUND(
        s_invoice_lines_t.tax_amount,
        2
    ) AS tax_amount,
    s_invoice_lines_t.batch_number AS batch_name,
    '' AS batch_expiry_dt,
    '' AS warranty_dt,
    '' AS cess_rate,
    '' AS cess_amt_adval,
    '' AS cess_non_adval_amt,
    '' AS state_cess_rate,
    '' AS state_cess_adval_amt,
    '' AS state_cess_non_adval_amt,
    '' AS other_charges,
    if(s_invoice_hdr_t.invoice_type = 'EXPORT INVOICE'  OR s_invoice_hdr_t.invoice_type = 'EXPORT SAMPLE',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*ROUND(
    s_invoice_lines_t.line_total,
    2
    ),2),ROUND(
    s_invoice_lines_t.line_total,
    2
    )) AS item_total,
    '' AS error_list
FROM
    s_invoice_hdr_t
LEFT JOIN s_invoice_lines_t ON s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id
LEFT JOIN m_products_t ON s_invoice_lines_t.product_id = m_products_t.product_id
LEFT JOIN f_gst_code_hdr_t ON s_invoice_lines_t.hsn_code = f_gst_code_hdr_t.gst_code_hdr_id
LEFT JOIN m_tax_group_t ON s_invoice_lines_t.tax_group_id = m_tax_group_t.tax_group_id
WHERE
    (
        s_invoice_hdr_t.invoice_type = 'STANDARD' OR s_invoice_hdr_t.invoice_type = 'EXPORT INVOICE' OR s_invoice_hdr_t.invoice_type = 'EXPORT SAMPLE'
    ) AND s_invoice_hdr_t.invoice_status = 'APPROVED' AND s_invoice_hdr_t.invoice_date  BETWEEN ? AND ?) AS v1";

    $result = \DB::select($SQL, [$start_date, $end_date]);
    foreach ($result as $key => $value) {

      $first = substr($result[$key]->tax_group_name, 0, 3);
      $tax_amt = $result[$key]->tax_amount;

      if ($first == "GST") {
        $rate1 = ($tax_amt / 2);
        $rate = (round($rate1, 2));
        $result[$key]->cgst = $rate;
        $result[$key]->sgst = $rate;
        $result[$key]->igst = 0;
      } elseif ($first == "IGS") {
        $result[$key]->igst = $tax_amt;
        $result[$key]->cgst = 0;
        $result[$key]->sgst = 0;
      } else {
        $result[$key]->cgst = 0;
        $result[$key]->sgst = 0;
        $result[$key]->igst = 0;
      }

    }

    return response()->json(['data' => $result]);
  }



  public function salesreturndetailsindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.salesreturndetailsrpt', $this->data);

  }

  public function getsalesreturndetails(Request $request)
  {

    $wh1 = '';
    $groupname = \Session::get('groupname');

    if ($groupname == "14") {
      $emp_id = \Session::get('emp_id');
      $cus_id = \DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");

      $wh1 = " and so_rma_hdr_t.customerid in ($cus_id[0]->dis)";
    }

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "SELECT * from (
       SELECT
    so_rma_hdr_t.so_rma_hdr_id,
    so_rma_hdr_t.rma_ref_no,
    m_customers_t.customer_name,
    m_products_t.concatenated_product,
    so_rma_hdr_t.total_amount as grand_total,
    so_rma_hdr_t.created_at,
    so_rma_hdr_t.return_date,
    CONCAT(LEFT(MONTHNAME(so_rma_hdr_t.return_date),3), '-', year(so_rma_hdr_t.return_date)) as return_month,
    so_rma_hdr_t.return_status,
    so_rma_hdr_t.return_source,
    so_rma_hdr_t.reference_no,
    so_rma_hdr_t.remarks,
    so_rma_hdr_t.paid_amount,
    so_rma_lines_t.invoice_qty,
    so_rma_lines_t.return_qty,
    so_rma_lines_t.returnqty,
    so_rma_lines_t.batch_number,
    so_rma_lines_t.expiry_date,
    so_rma_lines_t.manufracture_date,
    so_rma_lines_t.rate,
    so_rma_lines_t.buy_discount,
    so_rma_lines_t.total_amount,
    m_tax_group_t.tax_group_name,
        round((so_rma_lines_t.total_amount - ((so_rma_lines_t.rate * so_rma_lines_t.return_qty) - (so_rma_lines_t.discount_amount))),2) as tax_amount,
        round((so_rma_lines_t.rate * so_rma_lines_t.return_qty) - (so_rma_lines_t.discount_amount),2) as taxable_amount,
        if(SUBSTRING(m_tax_group_t.tax_group_name,1,3)='GST',round((so_rma_lines_t.total_amount - ((so_rma_lines_t.rate * so_rma_lines_t.return_qty) - (so_rma_lines_t.discount_amount)))/2,2),0) as cgst,
		if(SUBSTRING(m_tax_group_t.tax_group_name,1,3)='GST',round((so_rma_lines_t.total_amount - ((so_rma_lines_t.rate * so_rma_lines_t.return_qty) - (so_rma_lines_t.discount_amount)))/2,2),0) as sgst,
		if(SUBSTRING(m_tax_group_t.tax_group_name,1,3)='IGS',round((so_rma_lines_t.total_amount - ((so_rma_lines_t.rate * so_rma_lines_t.return_qty) - (so_rma_lines_t.discount_amount))),2),0) as igst,
		f_gst_code_hdr_t.classification_code,
        so_rma_lines_t.discount_amount
    FROM
        so_rma_hdr_t
    LEFT JOIN so_rma_lines_t ON so_rma_hdr_t.so_rma_hdr_id = so_rma_lines_t.so_rma_hdr_id
    LEFT JOIN m_products_t ON so_rma_lines_t.product_id = m_products_t.product_id 
    LEFT JOIN m_customers_t ON so_rma_hdr_t.customerid = m_customers_t.customer_id 
    left JOIN m_tax_group_t ON m_tax_group_t.tax_group_id = so_rma_lines_t.tax_group_id
    left JOIN f_gst_code_hdr_t ON f_gst_code_hdr_t.gst_code_hdr_id = m_products_t.defalut_hsn_code
    where 1=1 $wh1 and so_rma_hdr_t.return_date BETWEEN ? AND ?) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);

  }


  public function movegrnstatusindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.movegrnstatusrpt', $this->data);

  }

  public function getmovegrnstatus(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
 SELECT
    m_supplier_t.supplier_name,
    p_po_hdr_t.po_number,
    p_grn_hdr_t.grn_id,
    p_grn_hdr_t.grn_number,
    p_grn_hdr_t.grn_date,
    p_grn_hdr_t.created_at,
    p_grn_lines_t.qty,
    p_grn_lines_t.receive_qty,
    i_qoh_detail_t.cost,
       round( (p_grn_lines_t.receive_qty * COALESCE(i_qoh_detail_t.cost, 0)),2) as price,
    (
        CASE WHEN (i_qoh_detail_t.grn_id = p_grn_hdr_t.grn_id AND i_qoh_detail_t.product_id = p_grn_lines_t.product_id AND i_qoh_detail_t.qoh_source = 'PURCHASE_STOREMOVE') THEN 'MOVED' ELSE 'NOT MOVED'
    END
) AS inventory_status,
m_products_t.concatenated_product,
m_product_groups_t.group_name
FROM
    p_grn_hdr_t
LEFT JOIN p_grn_lines_t ON p_grn_hdr_t.grn_id = p_grn_lines_t.grn_id
LEFT JOIN m_products_t ON p_grn_lines_t.product_id = m_products_t.product_id
LEFT JOIN p_po_hdr_t ON p_grn_hdr_t.po_number = p_po_hdr_t.po_hdr_id
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.grn_id = p_grn_hdr_t.grn_id AND i_qoh_detail_t.product_id = p_grn_lines_t.product_id AND i_qoh_detail_t.qoh_source = 'PURCHASE_STOREMOVE' 
LEFT JOIN m_supplier_t ON p_grn_hdr_t.supplier_id = m_supplier_t.supplier_id
LEFT JOIN m_product_groups_t ON m_products_t.product_group_id = m_product_groups_t.product_group_id where 1=1 and p_grn_hdr_t.grn_date BETWEEN ? and ?) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
  }

  public function jobactivitydetailsindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.jobactivitydetailsrpt', $this->data);

  }

  public function getjobactivitydetails(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';



    $SQL = "SELECT * from (SELECT
    w_jobactivity_hdr_t.job_activity_id,
    w_jobactivity_hdr_t.remarks,
    w_jobactivity_hdr_t.created_at,
    w_jobactivity_lines_t.activity_name,
    w_jobactivity_lines_t.start_datetime,
    w_jobactivity_lines_t.end_datetime,
    (w_jobactivity_lines_t.duration) as duration,
    CONCAT(
        hr_employee_t.employee_number,
        '-',
        hr_employee_t.first_name
    ) AS employee_number,
    CONCAT(
        tb_users.employee_number,
        '-',
        tb_users.first_name
    ) AS first_name
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  ? and ?) AS v1 having v1.start_datetime !=''";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return DataTables::of($results)->make(true);
  }



  public function operationemployeeactivityindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.operationemployeeactivityrpt', $this->data);

  }

  public function getoperationemployeeactivity(Request $request)
  {
    $wh = '';


    $loc = "1";
    $compy = \Session::get('companyid');
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (SELECT
    *,
        time_format((v1.working_hours - v1.machour),'%H:%i') AS devhrs  
FROM
    (
    SELECT
        t.*, time_format(((t.actualhrs/t.range_to) * t.job_qty),'%H:%i') as machour,
        (
        SELECT
            w_workorder_hdr_t.shift
        FROM
            w_workorder_hdr_t
        WHERE
            w_workorder_hdr_t.workorder_hdr_id = t.plan_reference_id
    ) AS shift,
    (
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.qa_job_assigned_to
) AS qa_assigned_name,
(
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = t.job_assigned_to
) AS job_assigned_name,
       
(
    SELECT
        i_product_packs.pack_name
    FROM
        i_product_packs
    WHERE
        i_product_packs.packing_id = t.pack_id
) AS pack_name
FROM
    (
    SELECT
        w_jobcard_hdr_t.job_no,
        w_jobcard_hdr_t.job_date,
        w_jobcard_hdr_t.`reference_source_id`,
        w_jobcard_hdr_t.reference_source,
        (
        SELECT
            m_products_t.product_code
        FROM
            m_products_t
        WHERE
            m_products_t.product_id = w_jobcard_hdr_t.product_id
    ) AS product_code,
    (
    SELECT
        m_products_t.concatenated_product
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS product_name,
(
    SELECT
        w_productionplan_hdr_t.plan_no
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_no,
'MAJOR' AS type,
CONCAT(LEFT(MONTHNAME(date(w_jobcard_process_details_t.process_date)),3), '-', year(date(w_jobcard_process_details_t.process_date))) as yr_month,   
(
    SELECT
        w_productionplan_hdr_t.plan_date
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_date,
(
    SELECT
        w_productionplan_hdr_t.reference_id
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_reference_id,
(
    SELECT
        COALESCE(
            w_productionplan_hdr_t.production_qty,
            0
        ) AS production_qty
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS production_qty,
(
    SELECT
        w_productionplan_hdr_t.plan_qty
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS plan_qty,
(
    SELECT
        w_productionplan_hdr_t.plan_qty
    FROM
        w_productionplan_hdr_t
    WHERE
        w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id
) AS pending_qty,               
w_jobcard_hdr_t.product_id,
w_jobcard_process_details_t.move_qty as job_qty,
w_jobcard_hdr_t.job_completion_date,
w_jobcard_hdr_t.batch_no,
w_jobcard_hdr_t.job_process,
w_jobcard_process_details_t.machine_time,
(
    SELECT
        w_machine_equipments_lines_t.range_to
    FROM
        w_machine_equipments_hdr_t
    LEFT JOIN w_machine_equipments_lines_t ON
        (
            w_machine_equipments_lines_t.machine_equipments_hdr_id = w_machine_equipments_hdr_t.machine_equipments_hdr_id
        )
    WHERE
        w_machine_equipments_hdr_t.machine_id =w_jobcard_process_details_t.machine_id
    LIMIT 1
) AS range_to,
(
    SELECT
        w_machine_hdr_t.machine_code
    FROM
        w_machine_hdr_t
    WHERE
        w_machine_hdr_t.machine_hdr_id = w_jobcard_process_details_t.machine_id
) AS machine_code,
(
    SELECT
        w_machine_hdr_t.machine_name
    FROM
        w_machine_hdr_t
    WHERE
        w_machine_hdr_t.machine_hdr_id = w_jobcard_process_details_t.machine_id
) AS machine_name,
(
    SELECT
        tb_users.first_name
    FROM
        tb_users
    WHERE
        tb_users.employee_id = w_jobcard_process_details_t.calibration_checked_by
) AS calibration_checked,
(
    SELECT
        m_products_t.product_pack_id
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = w_jobcard_hdr_t.product_id
) AS pack_id,
w_jobcard_process_details_t.process_level,w_jobcard_process_details_t.process_name,
date(w_jobcard_process_details_t.process_start_date) as process_date,

SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        w_jobcard_process_details_t.jobassigned_to,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
) AS qa_job_assigned_to,
time_format(time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.start_time,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
)),'%H:%i') AS starttime,
time_format(time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.end_time,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
)),'%H:%i') AS endtime,
        
time_format(subtime(
        time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.end_time,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
)),
time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.start_time,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
))),'%H:%i') AS working_hours,
        
time_format(time(SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.actual_hrs,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
)),'%H:%i') AS actualhrs,
SUBSTRING_INDEX(
    SUBSTRING_INDEX(
        w_jobcard_process_details_t.emp_qty,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
) AS empqty,
SUBSTRING_INDEX(
    SUBSTRING_INDEX(
       w_jobcard_process_details_t.jobassigned_to,
        ',',
        m_products_t.product_id
    ),
    ',',
    -1
) AS job_assigned_to
FROM
  w_jobcard_process_details_t join      
    `w_jobcard_hdr_t` on(w_jobcard_hdr_t.w_jobs_hdr_id=w_jobcard_process_details_t.job_id)

JOIN m_products_t ON CHAR_LENGTH(
       w_jobcard_process_details_t.jobassigned_to
    ) - CHAR_LENGTH(
    REPLACE
        (
            w_jobcard_process_details_t.jobassigned_to,
            ',',
            ''
        )
) >= m_products_t.product_id -1
WHERE
   1=1
) AS t
WHERE
    1 = 1
) AS v1
WHERE
    1 = 1 AND v1.process_date BETWEEN '$start_date' AND '$end_date' 
    
    UNION ALL 
    
   SELECT
   '' AS job_no,
   date(w_jobactivity_lines_t.start_datetime) AS job_date,
   '' AS reference_source_id,
   '' AS reference_source,
   '' AS product_code,
   '' AS product_name,
   'SUBACT' AS plan_no,
   'SUB' AS type,
   CONCAT(LEFT(MONTHNAME(date(w_jobactivity_lines_t.start_datetime)),3), '-', year(date(w_jobactivity_lines_t.start_datetime))) as yr_month,
   date(w_jobactivity_lines_t.start_datetime) AS plan_date,
   '' AS plan_reference_id,
   '' AS production_qty,
   '' AS plan_qty,
   '' AS pending_qty,
   '' AS product_id,
   '' AS job_qty,
   '' AS job_completion_date,
   '' AS batch_no,
   '' AS job_process,
   '' AS machine_time,
   '' AS range_to,
   '' AS machine_code,
   'MANUAL' AS machine_name,
   '' AS calibration_checked,
   '' AS pack_id,
   w_jobactivity_lines_t.activity_name AS process_level,
   w_jobactivity_lines_t.activity_name AS process_name,
   date(w_jobactivity_lines_t.start_datetime) AS process_date,
   w_jobactivity_hdr_t.employee_id AS qa_job_assigned_to,
    time_format(time(SUBSTRING_INDEX(time(w_jobactivity_lines_t.start_datetime),'.',1)),'%H:%i') AS starttime,
    time_format(time(SUBSTRING_INDEX(time(w_jobactivity_lines_t.end_datetime),'.',1)),'%H:%i') AS endtime,
    time_format(subtime(time(SUBSTRING_INDEX(time(w_jobactivity_lines_t.end_datetime),'.',1)),time(SUBSTRING_INDEX(time(w_jobactivity_lines_t.start_datetime),'.',1))),'%H:%i') as working_hours,
    '' AS actualhrs,
    '' AS empqty,
    w_jobactivity_hdr_t.employee_id as job_assigned_to,
    '' AS machour,
    '' AS shift,
    hr_employee_t.first_name AS qa_assigned_name,
    hr_employee_t.first_name AS job_assigned_name,
    '' AS pack_name,
    '' AS devhrs
FROM
    w_jobactivity_hdr_t
LEFT JOIN w_jobactivity_lines_t ON w_jobactivity_hdr_t.job_activity_id = w_jobactivity_lines_t.job_activity_hdr_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = w_jobactivity_hdr_t.employee_id
LEFT JOIN tb_users ON tb_users.id = w_jobactivity_hdr_t.created_by where 1=1 and date(w_jobactivity_lines_t.start_datetime) BETWEEN  '$start_date' and '$end_date') as v3 where 1=1 $wh having v3.plan_date !='' ";

    $result = \DB::select($SQL);

    return DataTables::of($result)->make(true);

  }



  public function paymentdetailsindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.paymentdetailsrpt', $this->data);

  }


  public function getpaymentdetails(Request $request)
  {

    $wh1 = '';

    $groupname = \Session::get('groupname');

    if ($groupname == '1' || $groupname == 'Admin') {
      $wh1 = "and p_payments_t.company_id='1'";
    } else {
      $wh1 = " and p_payments_t.company_id='1' and p_payments_t.location_id='1'";
    }
    $emp = \Session::get('emp_id');

    if ($emp == '157' || $emp == '159' || $emp == '1') {
      $wh1 = '';
    } else {
      $wh1 = " and p_payments_t.payment_source!='SALARYPAYMENT'";
    }

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $download_SQL = "SELECT * from (SELECT 
                p_payments_t.*,
                CONCAT(LEFT(MONTHNAME(p_payments_t.payment_date),3), '-', year(p_payments_t.payment_date)) as payment_month,
                p_po_invoice_hdr_t.bill_number,
                p_po_invoice_hdr_t.invoice_date,
                p_po_invoice_hdr_t.supplier_invoice_date,
                p_po_invoice_hdr_t.invoice_grand_total,
                m_supplier_t.supplier_name,
                hr_employee_t.first_name,
                imp.first_name as imprest_employee_name,
                f_bank_account_hdr_t.bank_name,
                m_customers_t.customer_name
                FROM p_payments_t 
                left join m_supplier_t on(m_supplier_t.supplier_id=p_payments_t.supplier_id)
                left join hr_employee_t on (hr_employee_t.employee_id=p_payments_t.employee_id)
                left join hr_employee_t imp on (imp.employee_id=p_payments_t.imprest_employee_id)
                left join m_customers_t on (m_customers_t.customer_id=p_payments_t.customer_id)
                left join p_po_invoice_hdr_t on(p_po_invoice_hdr_t.po_invoice_id=p_payments_t.po_invoice_id)
                left join f_bank_account_hdr_t on(f_bank_account_hdr_t.bank_account_hdr_id=p_payments_t.bank_id)
                where 1=1 and (p_payments_t.batch_status !='INITIATED' OR (p_payments_t.batch_status ='INITIATED' and p_payments_t.payment_type_id='CASH' OR p_payments_t.payment_type_id='IMPREST' OR p_payments_t.payment_type_id='ONLINE')) $wh1 and p_payments_t.payment_date BETWEEN  '$start_date' and '$end_date' ) as v1";

    $result1 = \DB::select($download_SQL);

    if (count($result1) > 0) {
      foreach ($result1 as $k => $v) {
        if ($v->po_invoice_id != '' && $v->po_invoice_id != null) {
          $query = \DB::select("SELECT * FROM `p_po_invoice_hdr_t` WHERE `po_invoice_id` IN ($v->po_invoice_id)");
          $poinvoices = json_decode(json_encode($query), true);
          $result1[$k]->bill_number = implode(', ', array_column($poinvoices, 'bill_number'));
          $result1[$k]->invoice_date = implode(', ', array_column($poinvoices, 'invoice_date'));
          $result1[$k]->supplier_invoice_date = implode(', ', array_column($poinvoices, 'supplier_invoice_date'));
          $result1[$k]->invoice_grand_total = implode(', ', array_column($poinvoices, 'invoice_grand_total'));
        } else {
          $result1[$k]->bill_number = "";
          $result1[$k]->invoice_date = "";
          $result1[$k]->supplier_invoice_date = "";
          $result1[$k]->invoice_grand_total = "";
        }
      }
    }

    return response()->json(['data' => $result1]);

  }


  public function productstoremoveindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.productstoremoverpt', $this->data);

  }


  public function getproductstoremove(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';



    $SQL = "SELECT * from (SELECT
	w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id,
    m_products_t.concatenated_product,
    w_jobcard_hdr_t.job_no,
    w_qa_submitstage_trx_t.reference_no,
    i_quality_spec_trx_hdr_t.created_at as qa_app_date,
    w_qa_submitstage_trx_t.production_qty as transfer_qty,
    DATE(w_qa_submitstage_trx_t.created_at) as prd_date,
    w_qa_submitstage_trx_t.moveto_subinventory,
    w_qa_submitstage_trx_t.store_move,
    i_qoh_detail_t.qoh_trx_qty as receive_qty,
    DATE(m_material_trx_t.trx_date) as receive_date,
    i_qoh_detail_t.batch_number,
    tb_users.first_name as received_by
FROM
    w_qa_submitstage_trx_t
LEFT JOIN m_products_t ON w_qa_submitstage_trx_t.product_id = m_products_t.product_id
LEFT JOIN i_qoh_detail_t ON w_qa_submitstage_trx_t.job_no = i_qoh_detail_t.job_id
LEFT JOIN w_jobcard_hdr_t ON w_qa_submitstage_trx_t.job_no = w_jobcard_hdr_t.w_jobs_hdr_id
LEFT JOIN m_material_trx_t ON i_qoh_detail_t.create_trx_id = m_material_trx_t.material_trx_id
LEFT JOIN tb_users ON m_material_trx_t.created_by = tb_users.id
LEFT JOIN i_quality_spec_trx_hdr_t ON
	(
		i_quality_spec_trx_hdr_t.job_hdr_id = w_jobcard_hdr_t.w_jobs_hdr_id and i_quality_spec_trx_hdr_t.qatrx_date >= '2024-04-01'
	)
WHERE
    m_products_t.product_group_id = 4 AND i_qoh_detail_t.qoh_source = 'PRODUCTION STORE MOVE' AND (
        w_qa_submitstage_trx_t.moveto_subinventory = 'Yes' OR w_qa_submitstage_trx_t.moveto_subinventory = 'No'
    ) and DATE(m_material_trx_t.trx_date) BETWEEN ? and ?  ORDER BY prd_date DESC) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return DataTables::of($results)->make(true);
  }


  public function inventoryageingindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    $groupname = \Session::get('groupname');
    if ($groupname == '7') {
      $this->data['product_group_id'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', ' and product_group_id != 1 and product_group_id != 4 and product_group_id != 6 and product_group_id != 14 and product_group_id != 17');
    } else if ($groupname == '11') {
      $this->data['product_group_id'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', ' and (product_group_id = 1 or product_group_id = 4)');
    } else {
      $this->data['product_group_id'] = $this->jcombocomp('m_product_groups_t', 'product_group_id', 'group_name', '');
    }
    return view('supplierbasedcostreport.inventoryageingrpt', $this->data);

  }

  public function getinventoryageing(Request $request)
  {
    $wh = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $groupname = \Session::get('groupname');

    $to_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $product_group_id = $request->product_group_id ?? null;

    if (isset($product_group_id)) {
      $wh .= ' and f.product_group_id=' . $product_group_id;
    }

    $SQL = "SELECT * FROM (SELECT
    f.product_id,
    f.concatenated_product,
    f.group_name,
    f.product_group_id,
    batch_number,
    locator_name,
    d,
    dd,
    IF(diff = 0, d, 0) AS today,
    IF(diff > 0 AND diff <= 45, d, 0) AS 45day,
    IF(diff > 45 AND diff <= 90, d, 0) AS 90day,
    IF(diff > 90 AND diff <= 180, d, 0) AS 180day,
    IF(diff > 180, d, 0) AS `>180day`
FROM
    (
    SELECT
        i_qoh_detail_t.product_id,
        m_products_t.concatenated_product,
        m_product_groups_t.group_name,
        m_product_groups_t.product_group_id,
        ROUND(SUM(i_qoh_detail_t.qoh_trx_qty),
        2) d,
        m_sublocators_t.locator_name,
        i_qoh_detail_t.batch_number,
        DATE(i_qoh_detail_t.created_at) dd,
        DATEDIFF('$to_date', i_qoh_detail_t.created_at) diff
    FROM
        `i_qoh_detail_t`
        LEFT JOIN m_products_t ON m_products_t.product_id = i_qoh_detail_t.product_id
        LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id = m_products_t.product_group_id
        LEFT JOIN m_sublocators_t ON i_qoh_detail_t.locator_id = m_sublocators_t.sublocator_id
    WHERE
        i_qoh_detail_t.product_id != 0 and i_qoh_detail_t.subinventory_id != 0 and i_qoh_detail_t.locator_id != 0 and i_qoh_detail_t.created_at <= '$to_date'
    GROUP BY
        i_qoh_detail_t.product_id,
        i_qoh_detail_t.batch_number,
        i_qoh_detail_t.locator_id) f
    WHERE
        d > 0 $wh ORDER BY `180day` DESC)v1 having v1.dd !=''";


    $result = \DB::select($SQL);

    return DataTables::of($result)->make(true);
  }


  public function slowmovingproductindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    $groupname = \Session::get('groupname');
    if ($groupname == '7') {
      $this->data['product_group_id'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', ' and product_group_id != 1 and product_group_id != 4 and product_group_id != 6 and product_group_id != 14 and product_group_id != 17');
    } else if ($groupname == '11') {
      $this->data['product_group_id'] = $this->jcustomselecttool('m_product_groups_t', 'product_group_id', 'group_name', '', ' and (product_group_id = 1 or product_group_id = 4)');
    } else {
      $this->data['product_group_id'] = $this->jcombocomp('m_product_groups_t', 'product_group_id', 'group_name', '');
    }
    return view('supplierbasedcostreport.slowmovingproduct', $this->data);

  }

  public function getslowmovingproduct(Request $request)
  {
    $wh = '';
    $wh1 = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    $groupname = \Session::get('groupname');

    $to_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : NULL;
    $product_group_id = $request->product_group_id ?? null;

    if (isset($product_group_id)) {
      $wh1 .= ' and g.product_group_id=' . $product_group_id;
    }
    //dd($to_date);

    $SQL = "SELECT 
    p.product_id, 
    p.concatenated_product, 
    g.product_group_id,
    g.group_name AS product_group, 
    c.category_name AS product_category, 
    s.subcategory_name AS product_subcategory, 
    l.locator_name, 
    l.locator_code,
    q.batch_number,
    q.manufacturer_date,
    q.product_expire_date,
    MAX(q.qoh_trx_date) AS last_transaction_date
FROM m_products_t p
LEFT JOIN i_qoh_detail_t q ON p.product_id = q.product_id
LEFT JOIN m_product_groups_t g ON p.product_group_id = g.product_group_id
LEFT JOIN m_product_category_t c ON p.product_category_id = c.product_category_id
LEFT JOIN m_product_subcategory_t s ON p.product_subcategory_id = s.product_subcategory_id
LEFT JOIN m_sublocators_t l ON q.locator_id = l.sublocator_id

WHERE p.active = 'Yes' AND qoh_trx_date !=''
    AND p.company_id = 1
     $wh1

GROUP BY p.product_id, p.concatenated_product, g.group_name, c.category_name, 
         s.subcategory_name, l.locator_name, l.locator_code, q.batch_number, 
         q.manufacturer_date, q.product_expire_date

HAVING MAX(q.qoh_trx_date) IS NULL OR MAX(q.qoh_trx_date) < DATE_SUB('$to_date', INTERVAL 3 MONTH)";


    $result = \DB::select($SQL);

    return DataTables::of($result)->make(true);
  }


  public function prdaccstructurerptindex(Request $request)
  {
    // restrict illegal menu entry purpose - RATHI R

    $url = $request->path();

    $Controller = new Controller();
    $access = $Controller->Accessdined();

    $userAccess = json_decode($access, true);

    // Flatten the $userAccess array
    $flatUserAccess = [];
    foreach ($userAccess as $accessItem) {
      if (is_array($accessItem)) {
        $flatUserAccess = array_merge($flatUserAccess, $accessItem);
      } else {
        $flatUserAccess[] = $accessItem;
      }
    }

    if (!in_array($url, $flatUserAccess)) {
      return view('accessresticted.view');

    }

    // END

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.prdaccstructurerpt', $this->data);

  }

  public function getprdaccstructurerpt(Request $request)
  {

    if ($request->ajax()) {
      $data = \DB::table('m_products_t')
        ->leftJoin('m_product_groups_t', 'm_products_t.product_group_id', '=', 'm_product_groups_t.product_group_id')
        ->leftJoin('f_account_structure_t as prd_control_f_account_structure_t', 'm_products_t.control_account_id', '=', 'prd_control_f_account_structure_t.f_account_structure_id')
        ->leftJoin('f_account_structure_t as prd_account_f_account_structure_t', 'm_products_t.account_code_id', '=', 'prd_account_f_account_structure_t.f_account_structure_id')
        ->leftJoin('f_account_structure_t as prd_disc_f_account_structure_t', 'm_products_t.disc_account_code', '=', 'prd_disc_f_account_structure_t.f_account_structure_id')
        ->select([
            'm_products_t.product_id',
            'm_products_t.concatenated_product',
            'm_product_groups_t.group_name',
            'm_products_t.control_account_id',
            'm_products_t.account_code_id',
            'm_products_t.disc_account_code',
            'prd_control_f_account_structure_t.concatenated_segments as prd_control_account_id',
            'prd_control_f_account_structure_t.account_name as prd_control_account_name',
            'prd_account_f_account_structure_t.concatenated_segments as prd_account_code_id',
            'prd_account_f_account_structure_t.account_name as prd_account_name',
            'prd_disc_f_account_structure_t.concatenated_segments as prd_disc_account_code',
            'prd_disc_f_account_structure_t.account_name as prd_disc_account_name'
          ]);


      return DataTables::of($data)
        ->rawColumns(['actions'])
        ->make(true);
    }
  }



  public function cusaccstructurerptindex(Request $request)
  {
    // restrict illegal menu entry purpose - RATHI R

    $url = $request->path();

    $Controller = new Controller();
    $access = $Controller->Accessdined();

    $userAccess = json_decode($access, true);

    // Flatten the $userAccess array
    $flatUserAccess = [];
    foreach ($userAccess as $accessItem) {
      if (is_array($accessItem)) {
        $flatUserAccess = array_merge($flatUserAccess, $accessItem);
      } else {
        $flatUserAccess[] = $accessItem;
      }
    }

    if (!in_array($url, $flatUserAccess)) {
      return view('accessresticted.view');

    }

    // END

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.cusaccstructurerpt', $this->data);

  }


  public function getcusaccstructurerpt(Request $request)
  {

    if ($request->ajax()) {
      $data = \DB::table('m_customers_t')
        ->leftJoin('f_account_structure_t', 'm_customers_t.account_structure_id', '=', 'f_account_structure_t.f_account_structure_id')
        ->leftJoin('m_customer_types_t', 'm_customers_t.customer_type_id', '=', 'm_customer_types_t.customer_type_id')
        ->select([
            'm_customers_t.customer_id',
            'm_customers_t.customer_name',
            'm_customer_types_t.customer_type',
            'f_account_structure_t.concatenated_segments'
          ]);


      return DataTables::of($data)

        ->rawColumns(['actions'])
        ->make(true);
    }
  }

  public function supaccstructurerptindex(Request $request)
  {
    // restrict illegal menu entry purpose - RATHI R

    $url = $request->path();

    $Controller = new Controller();
    $access = $Controller->Accessdined();

    $userAccess = json_decode($access, true);

    // Flatten the $userAccess array
    $flatUserAccess = [];
    foreach ($userAccess as $accessItem) {
      if (is_array($accessItem)) {
        $flatUserAccess = array_merge($flatUserAccess, $accessItem);
      } else {
        $flatUserAccess[] = $accessItem;
      }
    }

    if (!in_array($url, $flatUserAccess)) {
      return view('accessresticted.view');

    }

    // END

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.supaccstructurerpt', $this->data);

  }

  public function getsupaccstructurerpt(Request $request)
  {

    if ($request->ajax()) {
      $data = \DB::table('m_supplier_t')
        ->leftJoin('f_account_structure_t', 'm_supplier_t.account_structure_id', '=', 'f_account_structure_t.f_account_structure_id')
        ->leftJoin('m_suppliertypes_t', 'm_supplier_t.supplier_type_id', '=', 'm_suppliertypes_t.suppliertype_id')
        ->select([
            'm_supplier_t.supplier_id',
            'm_supplier_t.supplier_name',
            'm_suppliertypes_t.suppliertype_name',
            'f_account_structure_t.concatenated_segments'
          ]);


      return DataTables::of($data)

        ->rawColumns(['actions'])
        ->make(true);
    }
  }

  public function empaccstructurerptindex(Request $request)
  {
    // restrict illegal menu entry purpose - RATHI R

    $url = $request->path();

    $Controller = new Controller();
    $access = $Controller->Accessdined();

    $userAccess = json_decode($access, true);

    // Flatten the $userAccess array
    $flatUserAccess = [];
    foreach ($userAccess as $accessItem) {
      if (is_array($accessItem)) {
        $flatUserAccess = array_merge($flatUserAccess, $accessItem);
      } else {
        $flatUserAccess[] = $accessItem;
      }
    }

    if (!in_array($url, $flatUserAccess)) {
      return view('accessresticted.view');

    }

    // END

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.empaccstructurerpt', $this->data);

  }


  public function getempaccstructurerpt(Request $request)
  {

    if ($request->ajax()) {
      $data = \DB::table('hr_employee_t')

        ->leftJoin('hr_employee_payproposal', 'hr_employee_t.employee_id', '=', 'hr_employee_payproposal.employee_id')
        ->leftJoin('m_allowance_tbl', 'hr_employee_payproposal.employee_type', '=', 'm_allowance_tbl.employee_type')
        ->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_employee_payproposal.employee_type')
        ->leftJoin('f_hr_account_allowance_setting_lines_t', 'f_hr_account_allowance_setting_lines_t.allowance_id', '=', 'm_allowance_tbl.allowance_id')
        ->leftJoin('f_account_structure_t', 'f_account_structure_t.f_account_structure_id', '=', 'f_hr_account_allowance_setting_lines_t.account_structure_id')

        ->select([
          'hr_employee_t.employee_id',
          'hr_employee_t.employee_number',
          'a_lookuplines_t.lookup_code',
          'hr_employee_t.first_name',
          'hr_employee_t.active',
          'hr_employee_payproposal.allowance',
          'm_allowance_tbl.allowance_name',
          'm_allowance_tbl.type',
          'f_account_structure_t.concatenated_segments'
        ]);

      return DataTables::of($data)

        ->rawColumns(['actions'])
        ->make(true);
    }
  }


  public function expaccstructurerptindex(Request $request)
  {
    // restrict illegal menu entry purpose - RATHI R

    $url = $request->path();

    $Controller = new Controller();
    $access = $Controller->Accessdined();

    $userAccess = json_decode($access, true);

    // Flatten the $userAccess array
    $flatUserAccess = [];
    foreach ($userAccess as $accessItem) {
      if (is_array($accessItem)) {
        $flatUserAccess = array_merge($flatUserAccess, $accessItem);
      } else {
        $flatUserAccess[] = $accessItem;
      }
    }

    if (!in_array($url, $flatUserAccess)) {
      return view('accessresticted.view');

    }

    // END

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.expaccstructurerpt', $this->data);

  }


  public function getexpaccstructurerpt(Request $request)
  {

    if ($request->ajax()) {
      $data = \DB::table('f_account_structure_t')
        ->select([
            'f_account_structure_id',
            'account_name',
            'concatenated_segments'
          ])

        ->where(function ($query) {
          $query->where('concatenated_segments', 'like', '%expense%')
            ->orWhere('concatenated_segments', 'like', '%exp %');
        });
      return DataTables::of($data)

        ->rawColumns(['actions'])
        ->make(true);
    }
  }


  public function pricelistdetailsindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.pricelistrpt', $this->data);

  }

  public function getpricelistdetails(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : NULL;
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : NULL;

    $wh = '';

    $dept = \Session::get('groupname');
    if ($dept == '7') {
      $wh = " and v1.price_list_type='Purchase'";
    } else if ($dept == '11') {
      $wh = " and v1.price_list_type='Sales'";
    } else {
      $wh = " ";
    }

    $SQL = "SELECT * from (
    SELECT * from (SELECT
    i_pricelist_hdr_t.pricelist_hdr_id,
    i_pricelist_hdr_t.pricelist_name,
    i_pricelist_hdr_t.price_list_type,
    i_pricelist_hdr_t.description,
    i_pricelist_lines_t.batch_number,
    i_pricelist_lines_t.unit_price,
    i_pricelist_lines_t.std_price,
    i_pricelist_lines_t.active,
    i_pricelist_lines_t.start_date,
    i_pricelist_lines_t.end_date,
    m_products_t.concatenated_product
    
FROM
    i_pricelist_hdr_t
LEFT JOIN i_pricelist_lines_t ON i_pricelist_hdr_t.pricelist_hdr_id = i_pricelist_lines_t.pricelist_hdr_id
LEFT JOIN m_products_t ON i_pricelist_lines_t.product_id = m_products_t.product_id) as v1 where 1=1 AND v1.start_date BETWEEN ? AND ? $wh  ORDER BY v1.batch_number DESC) AS vikki";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
  }

  public function productdetailsindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.productdetailsrpt', $this->data);

  }

  public function getproductdetails(Request $request)
  {

    $SQL = "SELECT * from (SELECT
    m_products_t.product_id,
    m_products_t.product_code,
    m_products_t.barcode_number,
    m_products_t.gross_weight,
    m_products_t.product_classification,
    m_products_t.group_classification,
    m_products_t.net_weight,
    m_products_t.concatenated_product,
    m_products_t.product_alternate_name,
    m_products_t.active,
    m_products_t.min_order_qty,
    m_products_t.min_stock_level2,
    m_products_t.min_stock_level3,
    m_products_t.max_order_qty,
    m_products_t.re_order_level,
    m_products_t.mpq_qty,
    m_products_t.expiry_days,
    m_products_t.tax_credit,
    m_products_t.locator_control,
    m_products_t.qc_type,
    m_products_t.qc_check,
    m_products_t.qc_no_of_days,
    m_products_t.`Std Cost` as std_cost,
    m_products_t.product_status,
    m_products_t.batch_no,
    m_products_t.created_at,
    m_product_groups_t.group_name,
    m_product_category_t.category_name,
    m_product_subcategory_t.subcategory_name,
    m_product_variants_t.product_variant_name,
    i_product_packs.pack_name,
    m_uom_codes_t.uom_code,
    i_product_packs_types_t.product_pack_type_name,
    m_product_type_t.product_type,
    (case when m_product_image_t.choosefile IS NOT NULL then
    'Yes' else
    'No' end) as prd_attach,
    f_gst_code_hdr_t.classification_code,
    m_subinventory_t.subinventory_name,
    m_sublocators_t.locator_code,
    (case WHEN i_quality_product_specs_hdr_t.product_id > 0 THEN
     'Yes' ELSE
     'No' END) as spec_status,
    tb_users.first_name,
    m_tax_group_t.tax_group_name
FROM
    m_products_t
LEFT JOIN m_product_groups_t ON m_products_t.product_group_id = m_product_groups_t.product_group_id
LEFT JOIN m_product_category_t ON m_products_t.product_category_id = m_product_category_t.product_category_id
LEFT JOIN m_product_subcategory_t ON m_products_t.product_subcategory_id = m_product_subcategory_t.product_subcategory_id
LEFT JOIN m_product_variants_t ON m_products_t.product_variant_id = m_product_variants_t.product_variant_id
LEFT JOIN i_product_packs ON m_products_t.product_pack_id = i_product_packs.packing_id
LEFT JOIN m_uom_codes_t ON m_products_t.primary_uom_id = m_uom_codes_t.uom_code_id
LEFT JOIN i_product_packs_types_t ON m_products_t.product_packtype_id = i_product_packs_types_t.product_packs_type_id
LEFT JOIN m_product_type_t ON m_products_t.product_type_id = m_product_type_t.product_type_id
LEFT JOIN m_product_image_t ON m_products_t.product_id = m_product_image_t.product_id
LEFT JOIN f_gst_code_hdr_t ON m_products_t.defalut_hsn_code = f_gst_code_hdr_t.gst_code_hdr_id
LEFT JOIN f_gst_code_lines_t ON f_gst_code_lines_t.gst_code_hdr_id = f_gst_code_hdr_t.gst_code_hdr_id
LEFT JOIN m_tax_group_t ON m_tax_group_t.tax_group_id = f_gst_code_lines_t.tax_group_id
LEFT JOIN m_subinventory_t ON m_products_t.subinventory_id = m_subinventory_t.subinventory_id
LEFT JOIN m_sublocators_t ON m_products_t.sublocator_id = m_sublocators_t.sublocator_id
LEFT JOIN tb_users ON m_products_t.created_by = tb_users.id 
LEFT JOIN i_quality_product_specs_hdr_t ON m_products_t.product_id = i_quality_product_specs_hdr_t.product_id GROUP BY product_id ) as v1";

    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);

  }


  public function productassetdetailsindex(Request $request)
  {
    // restrict illegal menu entry purpose - RATHI R

    $url = $request->path();

    $Controller = new Controller();
    $access = $Controller->Accessdined();

    $userAccess = json_decode($access, true);

    // Flatten the $userAccess array
    $flatUserAccess = [];
    foreach ($userAccess as $accessItem) {
      if (is_array($accessItem)) {
        $flatUserAccess = array_merge($flatUserAccess, $accessItem);
      } else {
        $flatUserAccess[] = $accessItem;
      }
    }

    if (!in_array($url, $flatUserAccess)) {
      return view('accessresticted.view');

    }

    // END


    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.productassetdetailsrpt', $this->data);

  }

  public function getproductassetdetails(Request $request)
  {
    $wh = '';

    $loc = \Session::get('location');
    $compy = \Session::get('companyid');


    $SQL = "SELECT * from (SELECT
    m_products_t.product_id,
    m_products_t.product_code,
    m_products_t.concatenated_product,
    m_products_t.product_alternate_name,
    m_products_t.active,
    m_products_t.tax_credit,
    m_products_t.locator_control,
    m_products_t.product_status,
    m_product_groups_t.group_name,
    m_product_category_t.category_name,
    m_product_subcategory_t.subcategory_name,
    m_product_variants_t.product_variant_name,
    m_product_type_t.product_type,
    f_gst_code_hdr_t.classification_code,
    m_subinventory_t.subinventory_name,
    m_sublocators_t.locator_code,
    tb_users.first_name as created_by,
    asset_product_config.asset_config_id,
    asset_product_config.brand_name,
    asset_product_config.area,
    asset_product_config.qty,
    asset_product_config.asset_number,
    asset_product_config.life_period,
    m_uom_codes_t.uom_code,
    asset_product_config.serial_number,
    asset_product_config.warrenty_from,
    asset_product_config.warrenty,
    asset_product_config.purchase_date,
    m_supplier_t.supplier_name,
    asset_product_config.replace_date,
    m_department_lines_t.sub_department_name,
    hr_employee_t.first_name as assigned_to,
    f_asset_types_t.asset_type_name,
    f_asset_category_t.asset_category_name,
    asset_product_config.asset_status,
    asset_product_config.work_group,
    asset_product_config.system_name,
    asset_product_config.operating_system,
    asset_product_config.windows_key,
    asset_product_config.processor_name,
    asset_product_config.hdd_size,
    asset_product_config.ram_size,
    asset_product_config.printer_name,
    asset_product_config.monitor,
    asset_product_config.anti_virus,
    asset_product_config.ip_address,
    asset_product_config.ms_office,
    asset_product_config.ms_office_key,
    asset_product_config.add_software,
    asset_product_config.mouse,
    asset_product_config.keyboard,
    asset_product_config.network_type,
    asset_product_config.cd_dvd_drive,
    asset_product_config.location,
    asset_product_config.po_number,
    asset_product_config.po_invoice_number,
    asset_product_config.capacity,
    asset_product_config.remarks
FROM
    asset_product_config
LEFT JOIN m_products_t ON m_products_t.product_id = asset_product_config.product_id    
LEFT JOIN m_product_groups_t ON m_products_t.product_group_id = m_product_groups_t.product_group_id
LEFT JOIN m_product_category_t ON m_products_t.product_category_id = m_product_category_t.product_category_id
LEFT JOIN m_product_subcategory_t ON m_products_t.product_subcategory_id = m_product_subcategory_t.product_subcategory_id
LEFT JOIN m_product_variants_t ON m_products_t.product_variant_id = m_product_variants_t.product_variant_id
LEFT JOIN m_product_type_t ON m_products_t.product_type_id = m_product_type_t.product_type_id
LEFT JOIN f_gst_code_hdr_t ON m_products_t.defalut_hsn_code = f_gst_code_hdr_t.gst_code_hdr_id
LEFT JOIN m_subinventory_t ON m_products_t.subinventory_id = m_subinventory_t.subinventory_id
LEFT JOIN m_sublocators_t ON m_products_t.sublocator_id = m_sublocators_t.sublocator_id
LEFT JOIN tb_users ON m_products_t.created_by = tb_users.id
LEFT JOIN m_uom_codes_t ON asset_product_config.uom = m_uom_codes_t.uom_code_id
LEFT JOIN m_supplier_t ON asset_product_config.supplier_id = m_supplier_t.supplier_id
LEFT JOIN m_department_lines_t ON asset_product_config.department = m_department_lines_t.department_line_id
LEFT JOIN hr_employee_t ON asset_product_config.assigned_to = hr_employee_t.employee_id
LEFT JOIN f_asset_types_t ON asset_product_config.asset_type = f_asset_types_t.asset_type_id
LEFT JOIN f_asset_category_t ON asset_product_config.asset_category = f_asset_category_t.asset_category_id ) as v1 where 1=1 $wh  ORDER BY v1.product_id ASC";

    $result = \DB::select($SQL);

    return DataTables::of($result)->make(true);
  }


  public function smartuploaddetailsindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.smartuploaddetailsrpt', $this->data);

  }


  public function getsmartuploaddetails(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
    SELECT
        hr_employee_t.employee_number,
        m_products_t.product_code,
        s_invoice_lines_t.batch_number,
        s_invoice_lines_t.qty,
        s_invoice_hdr_t.lr_no,
        s_invoice_hdr_t.invoice_date,
        s_invoice_hdr_t.invoice_number,
        hr_employee_t.first_name,
        m_products_t.concatenated_product
    FROM
        `s_invoice_hdr_t`
    LEFT JOIN s_invoice_lines_t ON s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id
    LEFT JOIN m_products_t ON s_invoice_lines_t.product_id = m_products_t.product_id
    LEFT JOIN hr_employee_t ON s_invoice_hdr_t.employee_id = hr_employee_t.employee_id
    WHERE
    s_invoice_hdr_t.invoice_type = 'SAMPLE' AND s_invoice_hdr_t.employee_id > 0 and s_invoice_hdr_t.invoice_date BETWEEN  ? AND ?) AS vikki";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
  }

  public function wippmstockdetailsindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.wippmstockdetailsrpt', $this->data);

  }

  public function getwippmstockdetails(Request $request)
  {

    $SQL = "SELECT * from (SELECT
    i_qoh_detail_t.product_id,
    w_jobcard_hdr_t.w_jobs_hdr_id,
    i_qoh_detail_t.qoh_source,
    w_jobcard_hdr_t.job_no,
    m_product_groups_t.group_name,
    m_product_category_t.category_name,
    m_product_subcategory_t.subcategory_name,
    m_products_t.concatenated_product,
    i_qoh_detail_t.batch_number,
    ROUND(
        SUM(i_qoh_detail_t.qoh_trx_qty) * -1
    ) qty
FROM
    `i_qoh_detail_t`
LEFT JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id = i_qoh_detail_t.job_id
LEFT JOIN m_products_t ON i_qoh_detail_t.product_id = m_products_t.product_id
LEFT JOIN m_product_groups_t ON m_products_t.product_group_id = m_product_groups_t.product_group_id
LEFT JOIN m_product_category_t ON m_products_t.product_category_id = m_product_category_t.product_category_id
LEFT JOIN m_product_subcategory_t ON m_products_t.product_subcategory_id = m_product_subcategory_t.product_subcategory_id
WHERE
    i_qoh_detail_t.qoh_source = 'Material Issue' AND(
        w_jobcard_hdr_t.job_status = 'Material Issued' OR w_jobcard_hdr_t.job_status = 'Material Received'
    ) AND m_products_t.product_group_id = '3'
GROUP BY
    w_jobcard_hdr_t.job_no,
    i_qoh_detail_t.product_id,
    i_qoh_detail_t.batch_number
ORDER BY
    `i_qoh_detail_t`.`product_id` ASC ) as v1 ";

    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);

  }

  public function einvoicerptforcrdindex()
  {

    $this->data['pageMethod'] = 'Supplierbasedcostrpt';
    return view('supplierbasedcostreport.einvoicerptforcrd', $this->data);

  }

}
