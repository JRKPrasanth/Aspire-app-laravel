<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Salesorderdetailsrpt;
use Illuminate\Http\Request;
use DB;

class SalesorderdetailsrptController extends Controller
{
    public $module = "sodetailsrpt";
    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->pageModule = "Salesorderdetailsrpt";
        $this->model = new Salesorderdetailsrpt();
        $this->model = new Salesorderdetailsrpt;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    public function index()
    {
        if (isset($_GET['customer_id'])) {
            $where = " and s_salesorder_hdr_t.ship_to_customer_id='" . $_GET['customer_id'] . "'";
        } else {
            $where = '';
        }
        $this->data['customeropt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
        $this->data['pageMethod'] = 'salesorderdetailsrpt';
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        $SQL = "SELECT 
                s_salesorder_hdr_t.sales_hdr_id,
                s_salesorder_hdr_t.order_status_id,
                s_salesorder_hdr_t.sales_order_date,
                s_salesorder_hdr_t.sales_order_no,
				 case when s_salesorder_hdr_t.ship_to_customer_id!=0 then  concat(m_customers_t.customer_number,'-',m_customers_t.customer_name) else concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) end as ship_to_customer_id,
                s_salesorder_hdr_t.order_total
                FROM s_salesorder_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) 
				 left join hr_employee_t on (hr_employee_t.employee_id=s_salesorder_hdr_t.employee_id)
                where 1=1 $where and s_salesorder_hdr_t.company_id=$compy and s_salesorder_hdr_t.location_id=1 ORDER BY s_salesorder_hdr_t.sales_hdr_id desc";
        $result = \DB::select($SQL);
        $this->data['result'] = $result;
        if (isset($_GET['customer_id'])) {
            return view('salesorderdetailsrpt.report_table', $this->data);
        } else {
            $this->data['chartresult'] = "";
            $this->data['barchart'] = "";
            $this->data['drilldown'] = "";
            $this->data['statusdrilldown'] = "";
            return view('salesorderdetailsrpt.table1', $this->data);
        }

    }

    public function SodetailsreportData()
    {
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        if (isset($_GET)) {

            $date = " and s_salesorder_hdr_t.sales_order_date between '" . $_GET['from_date'] . "' and '" . $_GET['to_date'] . "' ";
        }
        $this->data['from'] = $_GET['from_date'];
        $this->data['to'] = $_GET['to_date'];
        $SQL = "SELECT
                s_salesorder_hdr_t.sales_hdr_id,
                s_salesorder_hdr_t.order_status_id,
                s_salesorder_hdr_t.sales_order_date,
                s_salesorder_hdr_t.delivery_date,
                s_salesorder_hdr_t.sales_order_no,
                m_customers_t.customer_name,
                s_salesorder_hdr_t.order_total
                FROM s_salesorder_hdr_t
                left join m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id)
                where 1=1  $date and s_salesorder_hdr_t.company_id=$compy and s_salesorder_hdr_t.location_id=$loc ORDER BY s_salesorder_hdr_t.sales_hdr_id desc";
        $result = \DB::select($SQL);
        $this->data['result'] = json_encode($result);
        $SQL1 = \DB::select("SELECT   s_salesorder_hdr_t.order_status_id as name,count(*) as y,s_salesorder_hdr_t.order_status_id as drilldown FROM s_salesorder_hdr_t where 1=1  $date and s_salesorder_hdr_t.company_id=$compy and s_salesorder_hdr_t.location_id=$loc group by  s_salesorder_hdr_t.order_status_id ORDER BY s_salesorder_hdr_t.sales_hdr_id desc");
        $sql2 = \DB::select("SELECT m_customers_t.customer_name as name,count(*) as y,m_customers_t.customer_name as drilldown FROM s_salesorder_hdr_t left join  m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) where 1=1  $date and s_salesorder_hdr_t.company_id=$compy and s_salesorder_hdr_t.location_id=$loc group by  s_salesorder_hdr_t.ship_to_customer_id ORDER BY s_salesorder_hdr_t.sales_hdr_id desc");
        $data = array();
        foreach ($sql2 as $k => $v) {
            $sql3 = \DB::select("SELECT s_salesorder_hdr_t.sales_order_no,s_salesorder_hdr_t.order_total,m_customers_t.customer_name FROM s_salesorder_hdr_t left join  m_customers_t on(m_customers_t.customer_id=s_salesorder_hdr_t.ship_to_customer_id) where 1=1  $date and s_salesorder_hdr_t.company_id=$compy and s_salesorder_hdr_t.location_id=$loc and m_customers_t.customer_name='" . $v->name . "' ORDER BY s_salesorder_hdr_t.sales_hdr_id desc");
            $drilldown_data = array();
            foreach ($sql3 as $k1 => $v1) {

                $drilldown_data[$k1][0] = $v1->sales_order_no;
                $drilldown_data[$k1][1] = floatval($v1->order_total);
            }
            $data[$k] = (object) array('name' => $v->name, 'id' => $v->name, 'data' => $drilldown_data);



        }


        $data1 = array();
        foreach ($SQL1 as $key => $value) {


            $postatussql1 = \DB::select("SELECT s_salesorder_hdr_t.sales_order_no,s_salesorder_hdr_t.order_total, s_salesorder_hdr_t.order_status_id FROM s_salesorder_hdr_t where 1=1  $date and s_salesorder_hdr_t.company_id=$compy and s_salesorder_hdr_t.location_id=$loc and  s_salesorder_hdr_t.order_status_id='" . $value->name . "' ORDER BY s_salesorder_hdr_t.sales_hdr_id desc");
            $drilldown_data1 = array();
            foreach ($postatussql1 as $key1 => $value1) {

                $drilldown_data1[$key1][0] = $value1->sales_order_no;
                $drilldown_data1[$key1][1] = floatval($value1->order_total);
            }
            $data1[$key] = (object) array('name' => $value->name, 'id' => $value->name, 'data' => $drilldown_data1);



        }

        $this->data['chartresult'] = json_encode($SQL1);
        $this->data['barchart'] = json_encode($sql2);
        $this->data['drilldown'] = json_encode($data);
        $this->data['statusdrilldown'] = json_encode($data1);

        return view('salesorderdetailsrpt.table1', $this->data);
    }


    public function getsodetailsData(Request $request)
    {

        $compy = \Session::get('companyid');
        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "SELECT * from (    SELECT
        s_salesorder_hdr_t.sales_hdr_id,
		'' AS department_name,
        s_salesorder_hdr_t.order_status_id,
        s_salesorder_hdr_t.order_type_id,
        s_salesorder_hdr_t.sales_order_date,
        s_salesorder_hdr_t.sales_order_no,
        s_salesorder_hdr_t.con_exc_rate,
        s_salesorder_hdr_t.tcs_applicable,
        CASE WHEN s_salesorder_hdr_t.ship_to_customer_id != 0 THEN m_customers_t.customer_name ELSE hr_employee_t.first_name
END AS ship_to_customer_id,
s_salesorder_hdr_t.order_total,
s_salesorder_hdr_t.order_tax,
ROUND(SUM(s_salesorder_lines_t.qty * s_salesorder_lines_t.unit_price) , 2) AS acsessable_value,
ROUND(
    (
        s_salesorder_hdr_t.order_total - s_salesorder_hdr_t.order_tax
    ),
    2
) AS assval,
hr_employee_t.department,
CASE WHEN s_salesorder_hdr_t.employee_id != 0 THEN(
    SELECT
        zone_name
    FROM
        `a_zone_master_t`
    WHERE
        zone_id = hr_employee_t.zone_id
) ELSE(
    SELECT
        customer_type
    FROM
        `m_customer_types_t`
    WHERE
        customer_type_id = m_customers_t.customer_type_id
)
END AS cus_type,
CASE WHEN s_salesorder_hdr_t.employee_id != 0 THEN(
    SELECT
        state_name
    FROM
        `m_states_t`
    WHERE
        state_id = hr_emp_contact.current_state
) ELSE(
    SELECT
        state_name
    FROM
        `m_states_t`
    WHERE
        state_id = m_customer_sites_t.state
)
END AS state,
CASE WHEN s_salesorder_hdr_t.employee_id != 0 THEN(
    SELECT
        city_name
    FROM
        `m_cities_t`
    WHERE
        city_id = hr_emp_contact.current_city
) ELSE(
    SELECT
        city_name
    FROM
        `m_cities_t`
    WHERE
        city_id = m_customer_sites_t.city
)
END AS city
FROM
    s_salesorder_hdr_t
LEFT JOIN m_customers_t ON(
        m_customers_t.customer_id = s_salesorder_hdr_t.ship_to_customer_id
    )
LEFT JOIN hr_employee_t ON
    (
        hr_employee_t.employee_id = s_salesorder_hdr_t.employee_id
    )
LEFT JOIN hr_emp_contact ON hr_emp_contact.employee_id = hr_employee_t.employee_id
LEFT JOIN m_customer_sites_t ON m_customer_sites_t.customer_site_id = s_salesorder_hdr_t.ship_to_address_id
LEFT JOIN s_salesorder_lines_t ON s_salesorder_lines_t.sales_hdr_id = s_salesorder_hdr_t.sales_hdr_id
WHERE
    1 = 1 AND s_salesorder_hdr_t.sales_order_date BETWEEN ? AND ? AND s_salesorder_hdr_t.company_id = $compy
  GROUP BY  s_salesorder_hdr_t.sales_hdr_id ) AS v1";


        $results = \DB::select($SQL, [$start_date, $end_date]);

        return response()->json(['data' => $results]);
    }

    /* purpose:so detail report index*/
    public function index1(Request $request)
    {

        if ($this->data['pageMethod'] == "soinvoicedatarpt") {

            return view('salesorderdetailsrpt.soinvoicedatarpt', $this->data);
        } else  {

            return view('salesorderdetailsrpt.sodatasrpt', $this->data);
        } 
    }

    public function targetindex()
    {

        return view('salesorderdetailsrpt.target', $this->data);

    }

    public function targetinvoice()
    {

        return view('salesorderdetailsrpt.targetinvoice', $this->data);

    }

    public function gettargetso(Request $request)
    {

        $wh = '';
        $whi1 = '';
        $wh1 = '';

        $originalDate = new \DateTime($request->start_date);
        $from = date_format($originalDate, "Y-m-d");
        $parts = explode('-', $from);
        $y = $parts[0];
        $m = $parts[1];
        $originalDate1 = new \DateTime($request->end_date);
        $to = date_format($originalDate1, "Y-m-d");

        $where = '';
        while ($originalDate < $originalDate1) {
            $year = (string) $originalDate->format('Y');
            $month = (string) $originalDate->format('m');
            $where .= "(s_target_tbl.month=$month and s_target_tbl.year=$year) or";
            $originalDate->modify('+31 days');
        }

        $where = substr($where, 0, -2);


        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        $depart = \Session::get('groupname');

        if ($depart == "14") {
            $emp_id = \Session::get('emp_id');
            $cus_id = \DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM
    `distributormapping_hdr_tbl` join distributormapping_lines_tbl on
    distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where
    distributormapping_hdr_tbl.employee_id=$emp_id");

            $wh1 .= " and s_target_tbl.customer_id in (" . $cus_id[0]->dis . ")";
            $wh .= " and v1.customer_id in (" . $cus_id[0]->dis . ")";
        }

        $SQL = "SELECT
    *
    FROM
    (
    SELECT
    m_states_t.state_name,
    m_cities_t.city_name,
    v1.*,
    IF (VALUE
    - taxable_amount > 0,
    ROUND(
    VALUE
    - taxable_amount,
    2
    ),
    0

    ) AS ytoach,
    IF(IF(
    VALUE
    - taxable_amount > 0,
    ROUND(((taxable_amount * 100) /
    VALUE
    ),
    2),
    ROUND(((taxable_amount * 100) /
    VALUE
    ),
    2)
    ) IS Null, 0.00, IF(
    VALUE
    - taxable_amount > 0,
    ROUND(((taxable_amount * 100) /
    VALUE
    ),
    2),
    ROUND(((taxable_amount * 100) /
    VALUE
    ),
    2)
    ) )AS ach,
    IF(
    VALUE
    - taxable_amount > 0,
    ROUND(
    100 -((taxable_amount * 100) /
    VALUE
    ), 2 ), 0.00) AS yetach
    FROM
    (
    SELECT

    (SELECT
    m_customers_t.customer_name
    FROM
    m_customers_t
    WHERE
    m_customers_t.customer_id = s_target_tbl.customer_id
    ) AS customer_name,
    (SELECT
    m_customers_t.customer_id
    FROM
    m_customers_t
    WHERE
    m_customers_t.customer_id = s_target_tbl.customer_id
    ) AS customer_id,
    (SELECT
    m_customers_t.active
    FROM
    m_customers_t
    WHERE
    m_customers_t.customer_id = s_target_tbl.customer_id
    ) AS active,
    (
    SELECT
    m_customer_types_t.customer_type
    FROM
    m_customers_t
    LEFT JOIN m_customer_types_t ON m_customer_types_t.customer_type_id = m_customers_t.customer_type_id
    WHERE
    m_customers_t.customer_id = s_target_tbl.customer_id
    ) AS customer_type_zone,
    s_target_tbl.value,
    s_target_tbl.customer_id AS ship_to_customer_id,

    if(ROUND(
    SUM(
    s_salesorder_lines_t.line_total - s_salesorder_lines_t.tax_amount
    ),
    2
    ) IS null, 0,
    ROUND(
    SUM(
    s_salesorder_lines_t.line_total - s_salesorder_lines_t.tax_amount
    ),
    2
    )) AS taxable_amount,
    concat(date_format(STR_TO_DATE(s_target_tbl.month, '%m'),'%b'),'-',RIGHT(s_target_tbl.year, 2)) as month
    FROM s_target_tbl

    left JOIN s_salesorder_hdr_t ON s_target_tbl.customer_id = s_salesorder_hdr_t.ship_to_customer_id AND
    month(s_salesorder_hdr_t.sales_order_date)=s_target_tbl.month AND
    year(s_salesorder_hdr_t.sales_order_date)=s_target_tbl.year and s_salesorder_hdr_t.sales_order_date BETWEEN '$from'
    AND '$to' AND s_salesorder_hdr_t.order_status_id in ('APPROVED','CLOSED','COMPLETED','DRAFT','INITIATED')
    left join s_salesorder_lines_t on s_salesorder_lines_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id
    WHERE
    $where $wh1
    GROUP BY
    s_target_tbl.customer_id,s_target_tbl.month
    ) v1
    left JOIN m_customer_sites_t ON m_customer_sites_t.customer_id = v1.ship_to_customer_id AND
    m_customer_sites_t.site_type = 'BILL_TO' AND m_customer_sites_t.active = 'YES' AND
    m_customer_sites_t.primary_address = 'Yes'
    left JOIN m_states_t ON m_states_t.state_id = m_customer_sites_t.state
    left JOIN m_cities_t ON m_cities_t.city_id = m_customer_sites_t.city
    )v1 where 1=1 AND v1.active='Yes' $wh";



        $result = \DB::select($SQL);


        return response()->json(['data' => $result]);

    }



    public function gettargetinvoice(Request $request)
    {

        $wh = '';
        $whi1 = '';
        $wh1 = '';
        $originalDate = new \DateTime($request->start_date);
        $from = date_format($originalDate, "Y-m-d");
        $parts = explode('-', $from);
        $y = $parts[0];
        $m = $parts[1];
        $originalDate1 = new \DateTime($request->end_date);
        $to = date_format($originalDate1, "Y-m-d");

        $where = '';
        while ($originalDate < $originalDate1) {
            $year = (string) $originalDate->format('Y');
            $month = (string) $originalDate->format('m');
            $where .= "(s_target_tbl.month=$month and s_target_tbl.year=$year) or";
            $originalDate->modify('+31 days');
        }

        $where = substr($where, 0, -2);

        //dd($where);
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        $depart = \Session::get('groupname');
        if ($depart == "14") {
            $emp_id = \Session::get('emp_id');
            $cus_id = \DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");

            $wh1 .= " and s_target_tbl.customer_id in (" . $cus_id[0]->dis . ")";
            $wh .= " and v1.customer_id in (" . $cus_id[0]->dis . ")";
        }

        $SQL = "select * from (SELECT
    m_states_t.state_name,
    m_cities_t.city_name,
    v1.*,        IF (VALUE
            		- assess_val > 0,
            		ROUND(
        		VALUE
            		- assess_val,
            	2
        	),
        0
          	
    ) AS ytoach,
 IF(IF(
    VALUE
        - assess_val > 0,
        concat(ROUND(((assess_val * 100) /
    VALUE
        ),
        2),' %'),
        concat(ROUND(((assess_val * 100) /
    VALUE
        ),
        2),' %')
) IS Null, concat(0.00, ' %'),     IF(
    VALUE
        - assess_val > 0,
        concat(ROUND(((assess_val * 100) /
    VALUE
        ),
        2),' %'),
        concat(ROUND(((assess_val * 100) /
    VALUE
        ),
        2),' %')
) )AS ach,
        IF(
    VALUE
        - assess_val > 0,
        concat(ROUND(
            100 -((assess_val * 100) /
        VALUE
            ), 2 ),' %'), concat(0.00,' %') ) AS yetach
FROM
    (
    SELECT
     
    (
    SELECT
        m_customers_t.customer_name
    FROM
        m_customers_t
    WHERE
        m_customers_t.customer_id = s_target_tbl.customer_id
) AS customer_name,
(
    SELECT
        m_customers_t.customer_id
    FROM
        m_customers_t
    WHERE
        m_customers_t.customer_id = s_target_tbl.customer_id
) AS customer_id,
 (SELECT
            m_customers_t.active
        FROM
            m_customers_t
        WHERE
            m_customers_t.customer_id = s_target_tbl.customer_id 
     ) AS active,
(SELECT
        m_customer_types_t.customer_type
    FROM
        m_customers_t LEFT JOIN m_customer_types_t ON m_customer_types_t.customer_type_id=m_customers_t.customer_type_id
    WHERE
        m_customers_t.customer_id = s_target_tbl.customer_id
) AS customer_type_zone,
s_target_tbl.value,
s_target_tbl.customer_id as ship_to_customer_id,

if(ROUND(
    SUM(
        s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount
    ),
    2
) IS null, 0,
    ROUND(
    SUM(
        s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount
    ),
    2
)) AS taxable_amount,
if(SUM(round((s_invoice_lines_t.qty * s_invoice_lines_t.unit_price) - (s_invoice_lines_t.qty * s_invoice_lines_t.unit_price * (s_invoice_lines_t.discount_percentage/100)),2))>0,SUM(round((s_invoice_lines_t.qty * s_invoice_lines_t.unit_price) - (s_invoice_lines_t.qty * s_invoice_lines_t.unit_price * (s_invoice_lines_t.discount_percentage/100)),2)),0) as assess_val,
concat(date_format(STR_TO_DATE(s_target_tbl.month, '%m'),'%b'),'-',RIGHT(s_target_tbl.year, 2)) as month
FROM s_target_tbl

left JOIN s_invoice_hdr_t ON s_target_tbl.customer_id = s_invoice_hdr_t.ship_to_customer_id AND month(s_invoice_hdr_t.invoice_date)=s_target_tbl.month AND year(s_invoice_hdr_t.invoice_date)
=s_target_tbl.year and s_invoice_hdr_t.invoice_date BETWEEN '$from' AND '$to' AND s_invoice_hdr_t.invoice_status in ('APPROVED','CLOSED','COMPLETED','INITIATED','DRAFT')
left join s_invoice_lines_t on s_invoice_lines_t.invoice_hdr_id=s_invoice_hdr_t.invoice_hdr_id     
WHERE
    $where $wh1
GROUP BY
    s_target_tbl.customer_id,s_target_tbl.month
) v1
left JOIN m_customer_sites_t ON m_customer_sites_t.customer_id = v1.ship_to_customer_id AND m_customer_sites_t.site_type = 'BILL_TO' AND m_customer_sites_t.active = 'YES' AND m_customer_sites_t.primary_address = 'Yes'
left JOIN m_states_t ON m_states_t.state_id = m_customer_sites_t.state
left JOIN m_cities_t ON m_cities_t.city_id = m_customer_sites_t.city
)v1 where 1=1 AND v1.active='Yes' $wh  ORDER BY $month ";


        $result = \DB::select($SQL);


        return response()->json(['data' => $result]);


    }


    public function getsodatasrpt(Request $request)
    {

        $loc = "1";
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        $wh1 = '';
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh1 = "and  s_salesorder_lines_t.company_id='$compy'";
        } else {
            $wh1 = " and   s_salesorder_lines_t.company_id='$compy' and  s_salesorder_lines_t.location_id='$loc'";
        }

        if ($groupname == "14") {
            $emp_id = \Session::get('emp_id');
            $cus_id = \DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");

            $wh1 = " and s_salesorder_hdr_t.ship_to_customer_id in ($cus_id[0]->dis)";
        }

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "SELECT * FROM (
SELECT
    s_salesorder_lines_t.*,
    hr_employee_t.department,
    CASE WHEN s_salesorder_hdr_t.employee_id != 0 THEN(
    SELECT
        zone_name
    FROM
        `a_zone_master_t`
    WHERE
        zone_id = hr_employee_t.zone_id
) ELSE(
    SELECT
        customer_type
    FROM
        `m_customer_types_t`
    WHERE
        customer_type_id = m_customers_t.customer_type_id
)
END AS cus_type,
CASE WHEN s_salesorder_hdr_t.employee_id != 0 THEN(
    SELECT
        state_name
    FROM
        `m_states_t`
    WHERE
        state_id = hr_emp_contact.current_state
) ELSE(
    SELECT
        state_name
    FROM
        `m_states_t`
    WHERE
        state_id = m_customer_sites_t.state
)
END AS state,
CASE WHEN s_salesorder_hdr_t.employee_id != 0 THEN(
    SELECT
        city_name
    FROM
        `m_cities_t`
    WHERE
        city_id = hr_emp_contact.current_city
) ELSE(
    SELECT
        city_name
    FROM
        `m_cities_t`
    WHERE
        city_id = m_customer_sites_t.city
)
END AS city,
ROUND(
    (
        s_salesorder_lines_t.qty * s_salesorder_lines_t.unit_price
    ) -(
        s_salesorder_lines_t.qty * s_salesorder_lines_t.unit_price *(
            s_salesorder_lines_t.discount_percentage / 100
        )
    ),
    2
) AS assessablevalue,
ROUND(
    (
        s_salesorder_lines_t.qty * s_salesorder_lines_t.unit_price
    ) - s_salesorder_lines_t.discount_amount,
    2
) AS accessable_value,
CONCAT(
    m_products_t.product_code,
    '-',
    concatenated_product
) AS prdname,
s_salesorder_hdr_t.sales_order_no,
s_salesorder_hdr_t.sales_order_date,
s_salesorder_hdr_t.order_status_id,
s_salesorder_hdr_t.order_total,
s_salesorder_hdr_t.order_tax,
s_salesorder_hdr_t.ship_to_customer_id,
s_salesorder_hdr_t.advance_amount,
s_salesorder_hdr_t.balance_amount,
s_salesorder_hdr_t.cash_discount,
s_salesorder_hdr_t.source,
s_salesorder_hdr_t.reference_number,
s_salesorder_hdr_t.customer_po_number,
CASE WHEN s_salesorder_hdr_t.ship_to_customer_id != 0 THEN m_customers_t.customer_number ELSE hr_employee_t.employee_number
END AS cuscode,
CASE WHEN s_salesorder_hdr_t.ship_to_customer_id != 0 THEN m_customers_t.customer_name ELSE hr_employee_t.first_name
END AS cusname,
i_pricelist_hdr_t.pricelist_name,
m_uom_codes_t.uom_code,
m_tax_group_t.tax_group_name,
f_gst_code_hdr_t.classification_code
FROM
    `s_salesorder_lines_t`
LEFT JOIN s_salesorder_hdr_t ON(
        s_salesorder_hdr_t.sales_hdr_id = s_salesorder_lines_t.sales_hdr_id
    )
LEFT JOIN hr_employee_t ON(
        hr_employee_t.employee_id = s_salesorder_hdr_t.employee_id
    )
LEFT JOIN m_customers_t ON
    (
        m_customers_t.customer_id = s_salesorder_hdr_t.ship_to_customer_id
    )
LEFT JOIN i_pricelist_hdr_t ON
    (
        i_pricelist_hdr_t.pricelist_hdr_id = s_salesorder_hdr_t.pricelist_id
    )
LEFT JOIN m_products_t ON(
        m_products_t.product_id = s_salesorder_lines_t.product_id
    )
LEFT JOIN m_uom_codes_t ON
    (
        m_uom_codes_t.uom_code_id = s_salesorder_lines_t.uom_code_id
    )
LEFT JOIN m_tax_group_t ON
    (
        m_tax_group_t.tax_group_id = s_salesorder_lines_t.tax_group_id
    )
LEFT JOIN f_gst_code_hdr_t ON
    (
        f_gst_code_hdr_t.gst_code_hdr_id = s_salesorder_lines_t.hsn_code
    )
LEFT JOIN m_customer_sites_t ON m_customer_sites_t.customer_site_id = s_salesorder_hdr_t.ship_to_address_id
LEFT JOIN hr_emp_contact ON hr_emp_contact.employee_id = s_salesorder_hdr_t.employee_id
WHERE
    1 = 1  and s_salesorder_hdr_t.sales_order_date between ? and ?
) AS v1";


        $results = \DB::select($SQL, [$start_date, $end_date]);


        if (count($results) > 0) {
            foreach ($results as $k => $v) {
                if ($v->department != '' && $v->department != null) {
                    $array = json_decode($v->department);
                    $query = \DB::table('m_department_lines_t')->whereIn('department_line_id', $array)->get();
                    $deptnames = json_decode(json_encode($query), true);
                    $results[$k]->department_name = implode(' , ', array_column($deptnames, 'sub_department_name'));
                } else {
                    $results[$k]->department_name = "";
                }
            }
        }


        return response()->json(['data' => $results]);

    }

    /*end*/



    public function getsoinvoicedatarpt(Request $request)
    {

        $emp_id = \Session::get('emp_id');

        $cus_id = \DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=?",[$emp_id]);

        $disti_ids = $cus_id[0]->dis ?? ''; 

        if ($disti_ids !== '') {
            $wh = " and s_invoice_hdr_t.ship_to_customer_id in ($disti_ids)";
        } else {
            $wh = "";
        }
        $eid = \Session::get('emp_id');
        if ($eid == '240') {
            $wh = " and s_invoice_hdr_t.ship_to_customer_id in (6,127,192)";
        }
        if($eid == '152'){
            $wh ='';
        }
        //dd($wh);
        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $SQL = "SELECT * from (
select v2.*,round((v2.trade_amount1),2) as trade_amount from (select v1.* ,round((v1.qty * v1.unit_price) - (v1.qty * v1.unit_price * (v1.discount_percentage/100)),2) as assessablevalue,round((v1.line_total-v1.tax_amount),2) as accessablevalu,round(sum(((qty*unit_price)-discount_amount)*(cash/100)),2) as cas_amount,
round(sum(((qty*unit_price)-discount_amount)*((trade_discount_pre-cash)/100)),2) as trade_amount1,
round(trade_discount_pre-cash,2) as trade_discount from(SELECT
s_invoice_lines_t.`invoice_line_id`,
s_invoice_lines_t.`invoice_hdr_id`,
s_invoice_lines_t.`line_no`,
s_invoice_lines_t.`product_id`,
s_invoice_lines_t.`part_no`,
s_invoice_lines_t.`description`,
s_invoice_lines_t.`uom_code_id`,
s_invoice_lines_t.`salesorder_qty`,
s_invoice_lines_t.`qty`,
s_invoice_lines_t.`free_qty`,
s_invoice_lines_t.`invoice_qty`,
s_invoice_lines_t.`dispacth_qty`,
s_invoice_lines_t.`dispacth_status`,
s_invoice_lines_t.comments,
if(m_customer_types_t.customer_type='EXPORT',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*s_invoice_lines_t.`unit_price`,2),round(s_invoice_lines_t.`unit_price`,2)) as `unit_price`,
s_invoice_lines_t.`discount_percentage`,
if(m_customer_types_t.customer_type='EXPORT',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*s_invoice_lines_t.`discount_amount`,2),round(s_invoice_lines_t.`discount_amount`,2)) as `discount_amount`,
s_invoice_lines_t.`tax_excemption`,
s_invoice_lines_t.`hsn_code`,
s_invoice_lines_t.`line_sub_total`,
s_invoice_lines_t.`tax_group_id`,
s_invoice_lines_t.`tax_amount`,
if(m_customer_types_t.customer_type='EXPORT',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*s_invoice_lines_t.`line_total`,2),s_invoice_lines_t.`line_total`) as `line_total`,
s_invoice_lines_t.`reference_line_id`,
s_invoice_lines_t.`reference_hdr_id`,
s_invoice_lines_t.`ar_sales_hdr_id`,
s_invoice_lines_t.`ar_sales_line_id`,
s_invoice_lines_t.`sales_order`,
s_invoice_lines_t.`sales_order_qty`,
s_invoice_lines_t.`sales_order_invoice`,
s_invoice_lines_t.`sales_order_invoiced`,
s_invoice_lines_t.`batch_number`,
s_invoice_lines_t.`std_price`,
concatenated_product AS prdname,
m_products_t.product_code as prodt_cod,
s_salesorder_hdr_t.sales_order_no,
s_salesorder_hdr_t.sales_order_date,
s_invoice_hdr_t.invoice_number,
s_invoice_hdr_t.delivery_date,
s_invoice_hdr_t.invoice_date,
s_invoice_hdr_t.invoice_type,
s_invoice_hdr_t.invoice_status,
if(m_customer_types_t.customer_type='EXPORT',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*s_invoice_hdr_t.invoice_grand_total,2),s_invoice_hdr_t.invoice_grand_total) as invoice_grand_total,
s_invoice_hdr_t.invoice_tax_total,
s_invoice_hdr_t.invoice_pricelist_id,
if(m_customer_types_t.customer_type='EXPORT',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*s_invoice_hdr_t.paid_amount,2),s_invoice_hdr_t.paid_amount) as paid_amount,
if(m_customer_types_t.customer_type='EXPORT',round((SELECT conversion_rate FROM `f_account_exchangerates_t` where from_currency_id=s_invoice_hdr_t.invoice_currency and s_invoice_hdr_t.invoice_date between from_date and to_date limit 1 )*s_invoice_hdr_t.balance_amount,2),s_invoice_hdr_t.balance_amount) as balance_amount,
s_invoice_hdr_t.transport_charges,
s_invoice_hdr_t.debit_note,
s_invoice_hdr_t.credit_note,
s_invoice_hdr_t.credit_note_balance,
s_invoice_hdr_t.debit_note_balance,
(CASE WHEN s_invoice_hdr_t.ship_to_customer_id != '0' THEN m_customers_t.customer_name
ELSE 
hr_employee_t.first_name
END
) AS cusname,
(CASE WHEN s_invoice_hdr_t.ship_to_customer_id != '0' THEN 
m_customers_t.customer_number
ELSE 
hr_employee_t.employee_number
END
) AS cuscod,
hr_employee_t.department,
i_pricelist_hdr_t.pricelist_name,
m_uom_codes_t.uom_code,
m_tax_group_t.tax_group_name,
if(SUBSTRING(m_tax_group_t.tax_group_name,1,3)='GST',round(s_invoice_lines_t.tax_amount/2,2),0) as cgst,
if(SUBSTRING(m_tax_group_t.tax_group_name,1,3)='GST',round(s_invoice_lines_t.tax_amount/2,2),0) as sgst,
if(SUBSTRING(m_tax_group_t.tax_group_name,1,3)='IGS',round(s_invoice_lines_t.tax_amount,2),0) as igst,
f_gst_code_hdr_t.classification_code,
s_invoice_hdr_t.source,
s_invoice_hdr_t.reference_number,
s_invoice_hdr_t.lr_no,
s_invoice_hdr_t.lr_date,
s_invoice_hdr_t.lr_status,
s_invoice_hdr_t.eway_billno,
s_invoice_hdr_t.eway_date,
s_invoice_hdr_t.shiped_status,
s_invoice_hdr_t.approved_date,
s_invoice_hdr_t.cheque_no,
s_invoice_hdr_t.cheque_amount,
s_invoice_hdr_t.cheque_date,
s_invoice_hdr_t.cheque_received_date,
s_invoice_hdr_t.irn_no,
s_invoice_hdr_t.irn_date,
s_invoice_hdr_t.tcs_calc_amount,
s_invoice_hdr_t.tcs_amount,
f_account_currency_t.currency_code,
s_invoice_hdr_t.invoice_currency,
COALESCE((select s_schemes_lines_t.schemes_type_value from s_schemes_lines_t where s_schemes_lines_t.schemes_hdr_id=s_invoice_hdr_t.cash_discount),0)as cash,
s_invoice_hdr_t.trade_discount_pre,
CASE WHEN s_invoice_hdr_t.employee_id != '0' THEN (SELECT state_name from m_states_t WHERE m_states_t.state_id=hr_emp_contact.current_state  ) ELSE (SELECT state_name from m_states_t WHERE m_states_t.state_id=m_customer_sites_t.state  ) END as statename,
CASE WHEN s_invoice_hdr_t.employee_id != '0' THEN (SELECT city_name from m_cities_t WHERE m_cities_t.city_id=hr_emp_contact.current_city  ) ELSE (SELECT city_name from m_cities_t WHERE m_cities_t.city_id=m_customer_sites_t.city  ) END as city_name,
(SELECT category_name  from m_product_category_t where m_product_category_t.product_category_id=m_products_t.product_category_id) as category_name,
(SELECT subcategory_name  from m_product_subcategory_t where m_product_subcategory_t.product_subcategory_id =m_products_t.product_subcategory_id) as subcategory_name,
m_customer_sites_t.gst_no,
CASE WHEN s_salesorder_hdr_t.employee_id != 0 THEN
(
    SELECT
        zone_name
    FROM
        `a_zone_master_t`
    WHERE
        zone_id = hr_employee_t.zone_id
) 

ELSE

(
    SELECT
        customer_type
    FROM
        `m_customer_types_t`
    WHERE
        customer_type_id = m_customers_t.customer_type_id
)

END AS customer_type
FROM
s_invoice_lines_t
  JOIN s_invoice_hdr_t ON s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id
 left JOIN i_pricelist_hdr_t ON
(
i_pricelist_hdr_t.pricelist_hdr_id = s_invoice_hdr_t.invoice_pricelist_id
)
 
 left JOIN m_customers_t ON m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
 left join m_customer_types_t on m_customer_types_t.customer_type_id=m_customers_t.customer_type_id
 left JOIN m_customer_sites_t ON m_customer_sites_t.customer_site_id = s_invoice_hdr_t.bill_to_address_id and m_customer_sites_t.site_type='BILL_TO'  
 left JOIN m_products_t ON m_products_t.product_id = s_invoice_lines_t.product_id
 left JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = s_invoice_lines_t.uom_code_id
 left JOIN m_tax_group_t ON
(
m_tax_group_t.tax_group_id = s_invoice_lines_t.tax_group_id
)
 left JOIN f_gst_code_hdr_t ON f_gst_code_hdr_t.gst_code_hdr_id = s_invoice_lines_t.hsn_code
 left JOIN f_account_currency_t ON f_account_currency_t.account_currency_id = s_invoice_hdr_t.invoice_currency
 left JOIN hr_employee_t ON hr_employee_t.employee_id = s_invoice_hdr_t.employee_id
 left JOIN s_salesorder_hdr_t ON s_salesorder_hdr_t.sales_hdr_id = s_invoice_hdr_t.ar_sales_hdr_id
 LEFT JOIN hr_emp_contact ON hr_emp_contact.employee_id = s_invoice_hdr_t.employee_id
WHERE s_invoice_hdr_t.invoice_date between ? and ? $wh ORDER BY cash DESC)v1 group by v1.invoice_line_id )v2) AS vikki";

        $results = \DB::select($SQL, [$start_date, $end_date]);

        return response()->json(['data' => $results]);
    }


 

  public function index2()
    {

        $data = DB::select("SELECT 
    invoice_number,
    customer_site_name,
    address,
    gst_no,
    invoice_type,
    invoice_date,
    product_id,
    concatenated_product,
    sales_order_no,
    salesorder_qty,
    batch_number,
    mfg_dt,
    exp_dt,
    qty,
    free_qty,
    unit_price,
    mrp,
    tax_excemption,
    CASE WHEN rn = 1 THEN hdr_tax ELSE '' END AS hdr_tax,
    CASE WHEN rn = 1 THEN total ELSE '' END AS total,
    discount_amount,
    CASE WHEN rn = 1 THEN disc_per ELSE '' END AS disc_per,
    classification_code,
    tax_amount,
    line_total,
    invoice_status,
    prc_unt_pri,
    prc_mrp,
    prc_batch,
    price_valid
FROM (
    SELECT 
    @rn := IF(@prev_inv = invh.invoice_number, @rn + 1, 1) AS rn,
        @prev_inv := invh.invoice_number,
    invh.invoice_number,
    cusl.customer_site_name,
    cusl.address,
    cusl.gst_no,
    invh.invoice_type,
    invh.invoice_date,
    invl.product_id,
    prd.concatenated_product,
    slsh.sales_order_no,
    invl.salesorder_qty,
    invl.free_qty,
    invl.batch_number,
    invl.qty,
    invl.discount_amount,
    invl.unit_price,
    invl.std_price AS mrp,
    invl.tax_excemption,
    invh.invoice_tax_total as hdr_tax,
    invh.invoice_grand_total as total,
    invh.trade_discount as disc,
    invh.trade_discount_pre as disc_per,
    gst.classification_code,
    invl.tax_amount,
    invl.line_total,
    invh.invoice_status,

    /* Price List Unit Price */
    round((
        SELECT pl.unit_price
        FROM i_pricelist_lines_t pl
        WHERE pl.pricelist_hdr_id = invh.invoice_pricelist_id
        AND pl.product_id = invl.product_id
        AND pl.batch_number = invl.batch_number
        AND pl.active = 'yes'
        ORDER BY pl.pricelist_line_id DESC
        LIMIT 1
    ),2) AS prc_unt_pri,

    /* Price List MRP */
    (
        SELECT pl.std_price
        FROM i_pricelist_lines_t pl
        WHERE pl.pricelist_hdr_id = invh.invoice_pricelist_id
        AND pl.product_id = invl.product_id
        AND pl.batch_number = invl.batch_number
        AND pl.active = 'yes'
        ORDER BY pl.pricelist_line_id DESC
        LIMIT 1
    ) AS prc_mrp,
        (
        SELECT pl.batch_number        
        FROM i_pricelist_lines_t pl
        WHERE pl.pricelist_hdr_id = invh.invoice_pricelist_id
        AND pl.product_id = invl.product_id
        AND pl.batch_number = invl.batch_number
        AND pl.active = 'yes'
        ORDER BY pl.pricelist_line_id DESC
        LIMIT 1
    ) AS prc_batch,
    (
        SELECT dspq.manufacture_date
        FROM s_dispatched_qty_t dspq
        WHERE dspq.so_dispatch_hdr_id = invl.reference_hdr_id
        AND dspq.so_dispatch_line_id = invl.reference_line_id
        AND dspq.batch_no = invl.batch_number
        ORDER BY dspq.so_dispatch_line_id DESC
        LIMIT 1
    ) AS mfg_dt,
    
    (
        SELECT dspq.expiry_date
        FROM s_dispatched_qty_t dspq
        WHERE dspq.so_dispatch_hdr_id = invl.reference_hdr_id
        AND dspq.so_dispatch_line_id = invl.reference_line_id
        AND dspq.batch_no = invl.batch_number
        ORDER BY dspq.so_dispatch_line_id DESC
        LIMIT 1    
    ) AS exp_dt,
CASE 
WHEN invl.unit_price <>
(
    SELECT pl.unit_price
    FROM i_pricelist_lines_t pl
    WHERE pl.pricelist_hdr_id = invh.invoice_pricelist_id
    AND pl.product_id = invl.product_id
    AND pl.batch_number = invl.batch_number
    AND pl.active='yes'
    ORDER BY pl.pricelist_line_id DESC
    LIMIT 1
)
THEN 'PRICE MISMATCH'
ELSE 'OK'
END AS price_valid    
    

FROM s_invoice_lines_t invl

LEFT JOIN s_invoice_hdr_t invh 
ON invh.invoice_hdr_id = invl.invoice_hdr_id

LEFT JOIN m_products_t prd 
ON prd.product_id = invl.product_id

LEFT JOIN f_gst_code_hdr_t gst 
ON gst.gst_code_hdr_id = invl.hsn_code

LEFT JOIN s_salesorder_hdr_t slsh 
ON slsh.sales_hdr_id = invh.ar_sales_hdr_id

LEFT JOIN m_customer_sites_t cusl 
ON cusl.customer_site_id = invh.bill_to_address_id
AND cusl.active='yes',

    (SELECT @rn := 0, @prev_inv := '') vars

    WHERE invh.invoice_status = 'INITIATED'

    ORDER BY invh.invoice_number, invl.invoice_line_id

) t
        ");

        return view('salesorderdetailsrpt.invoice_price_validation', compact('data'));
    }   


}

