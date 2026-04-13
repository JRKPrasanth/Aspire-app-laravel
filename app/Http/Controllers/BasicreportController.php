<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DateTime;

class BasicreportController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();

        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = '';
        $this->middleware('auth');
    }
    public function sumoneindex()
    {
        return view('salesorderdetailsrpt.summaryone', $this->data);

    }

    public function invsumoneindex()
    {
        return view('salesorderdetailsrpt.invsummaryone', $this->data);

    }


    public function summarytwoindex()
    {
        return view('salesorderdetailsrpt.summarytwo', $this->data);

    }
    public function invoicesummarytwoindex()
    {
        return view('salesorderdetailsrpt.invsummarytwo', $this->data);

    }
    public function summarythreeindex()
    {
        return view('salesorderdetailsrpt.summarythree', $this->data);

    }

    public function invsummarythreeindex()
    {
        return view('salesorderdetailsrpt.summarythreeinv', $this->data);

    }
    public function summaryfourindex()
    {
        return view('salesorderdetailsrpt.summaryfour', $this->data);

    }
    public function summaryfourindexinv()
    {
        return view('salesorderdetailsrpt.summaryfourinv', $this->data);

    }

    public function gettargetvssummaryone(Request $request)
    {

        $fromDateStr = $request->start_date ?? null;
        $toDateStr = $request->end_date ?? null;


        try {
            $originalDate = new \DateTime($fromDateStr);
            $originalDate1 = new \DateTime($toDateStr);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format.'], 400);
        }

        $from = $originalDate->format("Y-m-d");
        $to = $originalDate1->format("Y-m-d");

        $org = \Session::get('organization');
        $loc = "1";
        $compy = \Session::get('companyid');

        $where = '';
        $tempDate = clone $originalDate;

        while ($tempDate <= $originalDate1) {
            $month = $tempDate->format("m");
            $year = $tempDate->format("Y");
            $where .= "(s_target_tbl.month = $month AND s_target_tbl.year = $year) OR ";
            $tempDate->modify("+1 month");
        }

        $where = rtrim($where, " OR ");


        $SQL = " SELECT * FROM (
        SELECT
            month_name,
            ROUND(SUM(target), 2) AS target,
            ROUND(SUM(order_val), 2) AS order_val,
            IF(SUM(target) != 0, ROUND(SUM(target) - SUM(order_val), 2), 0) AS yta,
            IF(SUM(target) != 0, ROUND((SUM(order_val) * 100) / SUM(target), 2), 0) AS ach,
            IF(SUM(target) != 0, ROUND(100 - ((SUM(order_val) * 100) / SUM(target)), 2), 0) AS ytach
        FROM (
            SELECT
                CONCAT(LPAD(s_target_tbl.month, 2, '0'), '-', s_target_tbl.year) AS month_name,
                COALESCE(ROUND(SUM(s_salesorder_lines_t.line_total - s_salesorder_lines_t.tax_amount), 2), 0) AS order_val,
                s_target_tbl.value AS target
            FROM s_target_tbl
            LEFT JOIN s_salesorder_hdr_t
                ON s_target_tbl.customer_id = s_salesorder_hdr_t.ship_to_customer_id
                AND MONTH(s_salesorder_hdr_t.sales_order_date) = s_target_tbl.month
                AND YEAR(s_salesorder_hdr_t.sales_order_date) = s_target_tbl.year
                AND s_salesorder_hdr_t.sales_order_date BETWEEN '$from' AND '$to'
                AND s_salesorder_hdr_t.order_status_id IN ('APPROVED','CLOSED','COMPLETED')
            LEFT JOIN s_salesorder_lines_t
                ON s_salesorder_lines_t.sales_hdr_id = s_salesorder_hdr_t.sales_hdr_id
            WHERE $where
            GROUP BY s_target_tbl.target_id
        ) AS v1
        GROUP BY month_name
    ) AS v1";

        $results = \DB::select($SQL);

        return response()->json(['data' => $results]);
    }


    public function getinvtargetvssummaryone(Request $request)
    {
        $wh = '';
        $whi1 = '';

        $originalDate = new \DateTime($request->start_date);
        $from = date_format($originalDate, "Y-m-d");
        $parts = explode('-', $from);
        $y = $parts[0];
        $m = $parts[1];
        $originalDate1 = new \DateTime($request->end_date);
        $to = date_format($originalDate1, "Y-m-d");


        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        $where = '';
        while ($originalDate <= $originalDate1) {
            $date = date_format($originalDate, "m");
            $date1 = date_format($originalDate, "mY");

            $year = (string) $originalDate->format('Y');
            $month = (string) $originalDate->format('m');
            $where .= "(s_target_tbl.month=$month and s_target_tbl.year=$year) or";



            $originalDate->modify("+1 months");
        }



        $where = substr($where, 0, -2);


        $SQL = "SELECT
    *
FROM
    (
    SELECT
        month_name,
        ROUND(SUM(target),
        2) AS target,
        ROUND(SUM(order_val),
        2) AS order_val,
        IF(
            SUM(target) != 0,
            ROUND(SUM(target) - SUM(order_val),
            2),
            0
        ) AS yta,
        IF(
            SUM(target) != 0,
            ROUND(
                (SUM(order_val) * 100) / SUM(target),
                2
            ),
            0
        ) AS ach,
        IF(
            SUM(target) != 0,
            ROUND(
                100 -(
                    (SUM(order_val) * 100) / SUM(target)
                ),
                2
            ),
            0
        ) AS ytach
    FROM
        (
        SELECT
            CONCAT(s_target_tbl.month
                ,
                '-',s_target_tbl.year
                
            ) AS month_name,
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
)) AS order_val,
                    s_target_tbl.value
                AS target
        FROM
            s_target_tbl left JOIN 
            s_invoice_hdr_t ON s_target_tbl.customer_id = s_invoice_hdr_t.ship_to_customer_id AND month(s_invoice_hdr_t.invoice_date)=s_target_tbl.month AND 
            year(s_invoice_hdr_t.invoice_date)=s_target_tbl.year and s_invoice_hdr_t.invoice_date  AND s_invoice_hdr_t.invoice_status in ('APPROVED','CLOSED','COMPLETED','INITIATED','DRAFT')
 left JOIN s_invoice_lines_t ON s_invoice_lines_t.invoice_hdr_id = s_invoice_hdr_t.invoice_hdr_id where $where
    GROUP BY
        
            s_target_tbl.target_id
    ) v1
GROUP BY
    month_name
) v1 where 1=1 $wh";


        $result = \DB::select($SQL);

        return response()->json(['data' => $result]);



    }


    public function gettargetvssummarytwo(Request $request)
    {


        $fromDateStr = $request->start_date ?? null;
        $toDateStr = $request->end_date ?? null;

        if (!$fromDateStr || !$toDateStr) {
            return response()->json(['error' => 'Date range is required.'], 400);
        }

        try {
            $originalDate = new \DateTime($fromDateStr);
            $originalDate1 = new \DateTime($toDateStr);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format.'], 400);
        }

        $from = $originalDate->format("Y-m-d");
        $to = $originalDate1->format("Y-m-d");

        $org = \Session::get('organization');
        $loc = "1";
        $compy = \Session::get('companyid');

        $where = '';
        while ($originalDate <= $originalDate1) {
            $date = date_format($originalDate, "m");
            $date1 = date_format($originalDate, "mY");

            $year = (string) $originalDate->format('Y');
            $month = (string) $originalDate->format('m');
            $where .= "(s_target_tbl.month=$month and s_target_tbl.year=$year) or";


            $select1 = "                   IF(
                
                    s_target_tbl.month
                 = $date,
                SUM(
                   COALESCE( s_salesorder_lines_t.line_total - s_salesorder_lines_t.tax_amount,0)
                ),
0
            ) AS o$date1 ,IF(
                
                    s_target_tbl.month
                 = $date,
                
                        s_target_tbl.value
                 ,
0
            ) t$date1 ,";

            $select2 = " IF(
            m = $date AND t$date1 != 0,
            ROUND(t$date1 - o$date1, 2),
0
        ) AS yta$date1 ,
        IF(
            m = $date AND t$date1 != 0,
            ROUND((o$date1 * 100) / t$date1,
            2),
0
        ) AS apre$date1,
        IF(
            m = $date AND t$date1 != 0,
            ROUND(100 -((o$date1 * 100) / t$date1),
            2),
0
        ) AS ytapre$date1 ,";

            $select3 = " SUM(t$date1) AS t$date1,
    SUM(o$date1) AS o$date1,
    SUM(yta$date1) AS yta$date1,
    SUM(apre$date1) AS apre$date1,
    SUM(ytapre$date1) AS ytapre$date1,";


            $select4 = " t$date1 +";
            $select5 = " o$date1 +";
            $select6 = " yta$date1 +";
            $select7 = " apre$date1 +";
            $select8 = " ytapre$date1 +";

            $originalDate->modify("+1 months");
        }

        $select4 = "0) as ttarget,";
        $select5 = "0) as torder,";
        $select6 = " 0) as tyta,";
        $select7 = " 0) as tapre,";
        $select8 = " 0) as tytapre";

        $where = substr($where, 0, -2);


        $sql = "select $select3 m_customer_types_t.customer_type,$select4 $select5 $select6 $select7 $select8
   
FROM
    (
    SELECT
        $select2 v1.*
        
    FROM
        (
        SELECT
            $select1
            s_target_tbl.customer_id as ship_to_customer_id,
            s_target_tbl.month AS m
           
        FROM
        s_target_tbl
            left JOIN s_salesorder_hdr_t ON s_target_tbl.customer_id = s_salesorder_hdr_t.ship_to_customer_id AND month(s_salesorder_hdr_t.sales_order_date)=s_target_tbl.month AND year(s_salesorder_hdr_t.sales_order_date)=s_target_tbl.year and s_salesorder_hdr_t.sales_order_date BETWEEN '$from' AND '$to' AND s_salesorder_hdr_t.order_status_id in ('APPROVED','CLOSED','COMPLETED')
 left JOIN s_salesorder_lines_t ON s_salesorder_lines_t.sales_hdr_id = s_salesorder_hdr_t.sales_hdr_id
WHERE $where
         GROUP BY
            s_target_tbl.customer_id,
            
                s_target_tbl.month
            
    ) v1
) v1
left  JOIN m_customers_t ON m_customers_t.customer_id = v1.ship_to_customer_id
left JOIN m_customer_types_t ON m_customer_types_t.customer_type_id = m_customers_t.customer_type_id
GROUP BY m_customers_t.customer_type_id";

        $results = \DB::select($sql);

        return response()->json(['data' => $results]);
    }

    public function gettargetvsinvsummarytwo(Request $request)
    {

        $wh = '';
        $whi1 = '';


        $originalDate = new \DateTime($request->start_date);
        $from = date_format($originalDate, "Y-m-d");
        $parts = explode('-', $from);
        $y = $parts[0];
        $m = $parts[1];
        $originalDate1 = new \DateTime($request->end_date);
        $to = date_format($originalDate1, "Y-m-d");


        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        $originalDate = new \DateTime($request->start_date);
        $from_month = date_format($originalDate, "m");
        $from_year = date_format($originalDate, "Y");

        $originalDate1 = new \DateTime($request->end_date);
        $to_month = date_format($originalDate1, "m");
        $to_year = date_format($originalDate1, "Y");

        $select1 = '';
        $select2 = '';
        $select3 = '';
        $select4 = 'Sum(';
        $select5 = 'Sum(';
        $select6 = 'Sum(';
        $select7 = 'Sum(';
        $select8 = 'Sum(';


        $where = '';
        while ($originalDate <= $originalDate1) {
            $date = date_format($originalDate, "m");
            $date1 = date_format($originalDate, "mY");

            $year = (string) $originalDate->format('Y');
            $month = (string) $originalDate->format('m');
            $where .= "(s_target_tbl.month=$month and s_target_tbl.year=$year) or";

            $select1 .= "                   IF(
                
                    s_target_tbl.month
                 = $date,
                SUM(
                   COALESCE( s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount,0)
                ),
0
            ) AS o$date1 ,IF(
                
                    s_target_tbl.month
                 = $date,
                
                        s_target_tbl.value
                 ,
0
            ) t$date1 ,";

            $select2 .= " IF(
            m = $date AND t$date1 != 0,
            ROUND(t$date1 - o$date1, 2),
0
        ) AS yta$date1 ,
        IF(
            m = $date AND t$date1 != 0,
            ROUND((o$date1 * 100) / t$date1,
            2),
0
        ) AS apre$date1,
        IF(
            m = $date AND t$date1 != 0,
            ROUND(100 -((o$date1 * 100) / t$date1),
            2),
0
        ) AS ytapre$date1 ,";

            $select3 .= " SUM(t$date1) AS t$date1,
    SUM(o$date1) AS o$date1,
    SUM(yta$date1) AS yta$date1,
    SUM(apre$date1) AS apre$date1,
    SUM(ytapre$date1) AS ytapre$date1,";


            $select4 .= " t$date1 +";
            $select5 .= " o$date1 +";
            $select6 .= " yta$date1 +";
            $select7 .= " apre$date1 +";
            $select8 .= " ytapre$date1 +";

            $originalDate->modify("+1 months");
        }

        $select4 .= "0) as ttarget,";
        $select5 .= "0) as torder,";
        $select6 .= " 0) as tyta,";
        $select7 .= " 0) as tapre,";
        $select8 .= " 0) as tytapre";

        $where = substr($where, 0, -2);


        $SQL = "select $select3 m_customer_types_t.customer_type,$select4 $select5 $select6 $select7 $select8
   
FROM
    (
    SELECT
        $select2 v1.*
        
    FROM
        (
        SELECT
            $select1
            s_target_tbl.customer_id as ship_to_customer_id,
            s_target_tbl.month AS m
           
        FROM
        s_target_tbl
            left JOIN s_invoice_hdr_t ON s_target_tbl.customer_id = s_invoice_hdr_t.ship_to_customer_id AND month(s_invoice_hdr_t.invoice_date)=s_target_tbl.month AND year(s_invoice_hdr_t.invoice_date)=
            s_target_tbl.year and s_invoice_hdr_t.invoice_date BETWEEN '$from' AND '$to' AND s_invoice_hdr_t.invoice_status in ('APPROVED','CLOSED','COMPLETED','INITIATED','DRAFT')
 left JOIN s_invoice_lines_t ON s_invoice_lines_t.invoice_hdr_id = s_invoice_hdr_t.invoice_hdr_id
WHERE $where
         GROUP BY
            s_target_tbl.customer_id,
            
                s_target_tbl.month
            
    ) v1
) v1
left  JOIN m_customers_t ON m_customers_t.customer_id = v1.ship_to_customer_id
left JOIN m_customer_types_t ON m_customer_types_t.customer_type_id = m_customers_t.customer_type_id
GROUP BY
    m_customers_t.customer_type_id";



        $result = \DB::select($SQL);
        return response()->json(['data' => $result]);

    }



    public function gettargetvssummarythree(Request $request)
    {

        $fromDateStr = $request->start_date ?? null;
        $toDateStr = $request->end_date ?? null;

        if (!$fromDateStr || !$toDateStr) {
            return response()->json(['error' => 'Date range required.'], 400);
        }

        try {
            $fromDate = new \DateTime($fromDateStr);
            $toDate = new \DateTime($toDateStr);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format.'], 400);
        }

        $from = $fromDate->format("Y-m-d");
        $to = $toDate->format("Y-m-d");

        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        $select1 = '';
        $select2 = '';
        $select3 = '';
        $select4 = 'SUM(';
        $select5 = 'SUM(';
        $select6 = 'SUM(';
        $select7 = 'SUM(';
        $select8 = 'SUM(';
        $where = '';

        $tempDate = clone $fromDate;
        while ($tempDate <= $toDate) {
            $month = $tempDate->format('m');
            $year = $tempDate->format('Y');
            $monYear = $tempDate->format('mY');

            $where .= "(s_target_tbl.month = $month AND s_target_tbl.year = $year) OR ";

            $select1 .= "
        IF(s_target_tbl.month = $month,
            COALESCE(SUM(s_salesorder_lines_t.line_total - s_salesorder_lines_t.tax_amount), 0),
        0) AS o$monYear,
        IF(s_target_tbl.month = $month, s_target_tbl.value, 0) AS t$monYear,";

            $select2 .= "
        IF(m = $month AND t$monYear != 0, ROUND(t$monYear - o$monYear, 2), 0) AS yta$monYear,
        IF(m = $month AND t$monYear != 0, ROUND((o$monYear * 100) / t$monYear, 2), 0) AS apre$monYear,
        IF(m = $month AND t$monYear != 0, ROUND(100 - ((o$monYear * 100) / t$monYear), 2), 0) AS ytapre$monYear,";

            $select3 .= "
        SUM(t$monYear) AS t$monYear,
        SUM(o$monYear) AS o$monYear,
        SUM(yta$monYear) AS yta$monYear,
        SUM(apre$monYear) AS apre$monYear,
        SUM(ytapre$monYear) AS ytapre$monYear,";

            $select4 .= "t$monYear + ";
            $select5 .= "o$monYear + ";
            $select6 .= "yta$monYear + ";
            $select7 .= "apre$monYear + ";
            $select8 .= "ytapre$monYear + ";

            $tempDate->modify("+1 month");
        }

        $where = rtrim($where, " OR ");
        $select4 .= "0) AS ttarget,";
        $select5 .= "0) AS torder,";
        $select6 .= "0) AS tyta,";
        $select7 .= "0) AS tapre,";
        $select8 .= "0) AS tytapre,";

        // Final SQL query
        $sql = "
SELECT
    $select3
    m_customer_types_t.customer_type,
    $select4
    $select5
    $select6
    $select7
    $select8
    m_states_t.state_name
FROM (
    SELECT
        $select2
        v1.*
    FROM (
        SELECT
            $select1
            s_target_tbl.customer_id AS ship_to_customer_id,
            s_target_tbl.month AS m
        FROM s_target_tbl
        LEFT JOIN s_salesorder_hdr_t
            ON s_target_tbl.customer_id = s_salesorder_hdr_t.ship_to_customer_id
            AND MONTH(s_salesorder_hdr_t.sales_order_date) = s_target_tbl.month
            AND YEAR(s_salesorder_hdr_t.sales_order_date) = s_target_tbl.year
            AND s_salesorder_hdr_t.sales_order_date BETWEEN '$from' AND '$to'
            AND s_salesorder_hdr_t.order_status_id IN ('APPROVED','CLOSED','COMPLETED')
        LEFT JOIN s_salesorder_lines_t
            ON s_salesorder_lines_t.sales_hdr_id = s_salesorder_hdr_t.sales_hdr_id
        WHERE $where
        GROUP BY s_target_tbl.customer_id, s_target_tbl.month
    ) v1
) v1
LEFT JOIN m_customers_t ON m_customers_t.customer_id = v1.ship_to_customer_id
LEFT JOIN m_customer_types_t ON m_customer_types_t.customer_type_id = m_customers_t.customer_type_id
LEFT JOIN m_customer_sites_t ON m_customer_sites_t.customer_id = v1.ship_to_customer_id
    AND m_customer_sites_t.active = 'Yes'
    AND m_customer_sites_t.site_type = 'BILL_TO'
    AND m_customer_sites_t.primary_address = 'Yes'
LEFT JOIN m_states_t ON m_states_t.state_id = m_customer_sites_t.state
GROUP BY m_states_t.state_id, m_customer_types_t.customer_type_id";


        $results = \DB::select($sql);

        return response()->json(['data' => $results]);

    }

    public function gettargetvssummarythreeinv(Request $request)
    {
        $wh = '';
        $whi1 = '';

        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        // Convert request dates to DateTime objects
        $originalDate = new \DateTime($request->start_date);
        $from = $originalDate->format("Y-m-d");

        $parts = explode('-', $from);
        $y = $parts[0];
        $m = $parts[1];

        $originalDate1 = new \DateTime($request->end_date);
        $to = $originalDate1->format("Y-m-d");

        $from_month = $originalDate->format("m");
        $from_year = $originalDate->format("Y");
        $to_month = $originalDate1->format("m");
        $to_year = $originalDate1->format("Y");

        $select1 = '';
        $select2 = '';
        $select3 = '';
        $select4 = 'Sum(';
        $select5 = 'Sum(';
        $select6 = 'Sum(';
        $select7 = 'Sum(';
        $select8 = 'Sum(';
        $where = '';
        while ($originalDate <= $originalDate1) {
            $date = date_format($originalDate, "m");
            $date1 = date_format($originalDate, "mY");

            $year = (string) $originalDate->format('Y');
            $month = (string) $originalDate->format('m');
            $where .= "(s_target_tbl.month=$month and s_target_tbl.year=$year) or";

            $select1 .= "                   IF(
                s_target_tbl.month
                 = $date,
                COALESCE(SUM(
                    s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount
                ),0),
0
            ) AS o$date1 ,IF(
                
                    s_target_tbl.month
                = $date,
                
                        s_target_tbl.value
                 ,
0
            ) t$date1 ,";

            $select2 .= " IF(
            m = $date AND t$date1 != 0,
            ROUND(t$date1 - o$date1, 2),
0
        ) AS yta$date1 ,
        IF(
            m = $date AND t$date1 != 0,
            ROUND((o$date1 * 100) / t$date1,
            2),
0
        ) AS apre$date1,
        IF(
            m = $date AND t$date1 != 0,
            ROUND(100 -((o$date1 * 100) / t$date1),
            2),
0
        ) AS ytapre$date1 ,";

            $select3 .= " SUM(t$date1) AS t$date1,
    SUM(o$date1) AS o$date1,
    SUM(yta$date1) AS yta$date1,
    SUM(apre$date1) AS apre$date1,
    SUM(ytapre$date1) AS ytapre$date1,";


            $select4 .= " t$date1 +";
            $select5 .= " o$date1 +";
            $select6 .= " yta$date1 +";
            $select7 .= " apre$date1 +";
            $select8 .= " ytapre$date1 +";

            $originalDate->modify("+1 months");
        }

        $select4 .= "0) as ttarget,";
        $select5 .= "0) as torder,";
        $select6 .= " 0) as tyta,";
        $select7 .= " 0) as tapre,";
        $select8 .= " 0) as tytapre";


        $where = substr($where, 0, -2);


        $sql = "select $select3 m_customer_types_t.customer_type,$select4 $select5 $select6 $select7 $select8 ,m_states_t.state_name
   
FROM
    (
    SELECT
        $select2 v1.*
        
    FROM
        (
        SELECT
            $select1
            s_target_tbl.customer_id as ship_to_customer_id,
            s_target_tbl.month
             AS m
           
        FROM
            
        s_target_tbl
            left JOIN s_invoice_hdr_t ON s_target_tbl.customer_id = s_invoice_hdr_t.ship_to_customer_id AND month(s_invoice_hdr_t.invoice_date)=s_target_tbl.month AND
            year(s_invoice_hdr_t.invoice_date)=s_target_tbl.year and s_invoice_hdr_t.invoice_date BETWEEN '$from' AND '$to' AND s_invoice_hdr_t.invoice_status in ('APPROVED','CLOSED','COMPLETED')
 left JOIN s_invoice_lines_t ON s_invoice_lines_t.invoice_hdr_id = s_invoice_hdr_t.invoice_hdr_id
WHERE $where
         GROUP BY
            s_target_tbl.customer_id,
            
                s_target_tbl.month
    ) v1
) v1
left JOIN m_customers_t ON m_customers_t.customer_id = v1.ship_to_customer_id
left JOIN m_customer_types_t ON m_customer_types_t.customer_type_id = m_customers_t.customer_type_id
left join m_customer_sites_t on m_customer_sites_t.customer_id=v1.ship_to_customer_id and m_customer_sites_t.active='Yes' and m_customer_sites_t.site_type='BILL_TO' and m_customer_sites_t.primary_address='Yes' join m_states_t on m_states_t.state_id=m_customer_sites_t.state
GROUP BY m_states_t.state_id";

        $results = \DB::select($sql);

        return response()->json(['data' => $results]);
    }


    public function gettargetvssummaryfour(Request $request)
    {

        $from = $request->start_date;
        $to = $request->end_date;


        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        $select1 = $select2 = $select3 = "";
        $select4 = $select5 = $select6 = $select7 = $select8 = "SUM(";
        $where = '';

        $loop_date = clone $from;

        while ($loop_date <= $to_date) {
            $month = $loop_date->format("m");
            $year = $loop_date->format("Y");
            $monyear = $loop_date->format("mY");

            $where .= "(s_target_tbl.month = $month AND s_target_tbl.year = $year) OR ";

            $select1 .= "
        IF(s_target_tbl.month = $month, COALESCE(SUM(s_salesorder_lines_t.line_total - s_salesorder_lines_t.tax_amount), 0), 0) AS o$monyear,
        IF(s_target_tbl.month = $month, s_target_tbl.value, 0) AS t$monyear,
    ";

            $select2 .= "
        IF(m = $month AND t$monyear != 0, ROUND(t$monyear - o$monyear, 2), 0) AS yta$monyear,
        IF(m = $month AND t$monyear != 0, ROUND((o$monyear * 100) / t$monyear, 2), 0) AS apre$monyear,
        IF(m = $month AND t$monyear != 0, ROUND(100 - ((o$monyear * 100) / t$monyear), 2), 0) AS ytapre$monyear,
    ";

            $select3 .= "
        SUM(t$monyear) AS t$monyear,
        SUM(o$monyear) AS o$monyear,
        SUM(yta$monyear) AS yta$monyear,
        SUM(apre$monyear) AS apre$monyear,
        SUM(ytapre$monyear) AS ytapre$monyear,
    ";

            $select4 .= "t$monyear + ";
            $select5 .= "o$monyear + ";
            $select6 .= "yta$monyear + ";
            $select7 .= "apre$monyear + ";
            $select8 .= "ytapre$monyear + ";

            $loop_date->modify("+1 month");
        }

        $where = rtrim($where, ' OR ');

        $select4 .= "0) AS ttarget,";
        $select5 .= "0) AS torder,";
        $select6 .= "0) AS tyta,";
        $select7 .= "0) AS tapre,";
        $select8 .= "0) AS tytapre";

        $sql = "SELECT
    $select3
    m_customer_types_t.customer_type,
    $select4 $select5 $select6 $select7 $select8,
    m_states_t.state_name,
    m_customers_t.customer_name
FROM (
    SELECT
        $select2
        v1.*
    FROM (
        SELECT
            $select1
            s_target_tbl.customer_id AS ship_to_customer_id,
            s_target_tbl.month AS m
        FROM s_target_tbl
        LEFT JOIN s_salesorder_hdr_t
            ON s_target_tbl.customer_id = s_salesorder_hdr_t.ship_to_customer_id
            AND MONTH(s_salesorder_hdr_t.sales_order_date) = s_target_tbl.month
            AND YEAR(s_salesorder_hdr_t.sales_order_date) = s_target_tbl.year
            AND s_salesorder_hdr_t.sales_order_date BETWEEN '$from' AND '$to'
            AND s_salesorder_hdr_t.order_status_id IN ('APPROVED', 'CLOSED', 'COMPLETED')
        LEFT JOIN s_salesorder_lines_t
            ON s_salesorder_lines_t.sales_hdr_id = s_salesorder_hdr_t.sales_hdr_id
        WHERE $where
        GROUP BY s_target_tbl.customer_id, s_target_tbl.month
    ) v1
) v1
LEFT JOIN m_customers_t ON m_customers_t.customer_id = v1.ship_to_customer_id
LEFT JOIN m_customer_types_t ON m_customer_types_t.customer_type_id = m_customers_t.customer_type_id
LEFT JOIN m_customer_sites_t 
    ON m_customer_sites_t.customer_id = v1.ship_to_customer_id
    AND m_customer_sites_t.active = 'Yes'
    AND m_customer_sites_t.site_type = 'BILL_TO'
    AND m_customer_sites_t.primary_address = 'Yes'
LEFT JOIN m_states_t 
    ON m_states_t.state_id = m_customer_sites_t.state
GROUP BY v1.ship_to_customer_id";



        $results = \DB::select($sql);

        return response()->json(['data' => $results]);

    }

    public function gettargetvssummaryfourinv(Request $request)
    {
        $wh = '';
        $whi1 = '';

        $loc = \Session::get('location');
        $compy = \Session::get('companyid');

        // Convert request dates to DateTime objects
        $originalDate = new \DateTime($request->start_date);
        $from = $originalDate->format("Y-m-d");

        $parts = explode('-', $from);
        $y = $parts[0];
        $m = $parts[1];

        $originalDate1 = new \DateTime($request->end_date);
        $to = $originalDate1->format("Y-m-d");

        $from_month = $originalDate->format("m");
        $from_year = $originalDate->format("Y");
        $to_month = $originalDate1->format("m");
        $to_year = $originalDate1->format("Y");

        $select1 = '';
        $select2 = '';
        $select3 = '';
        $select4 = 'Sum(';
        $select5 = 'Sum(';
        $select6 = 'Sum(';
        $select7 = 'Sum(';
        $select8 = 'Sum(';
        $where = '';
        while ($originalDate <= $originalDate1) {
            $date = date_format($originalDate, "m");
            $date1 = date_format($originalDate, "mY");

            $year = (string) $originalDate->format('Y');
            $month = (string) $originalDate->format('m');
            $where .= "(s_target_tbl.month=$month and s_target_tbl.year=$year) or";

            $select1 .= "                   IF(
                s_target_tbl.month
         = $date,
                COALESCE(SUM(
                    s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount
                ),0),
0
            ) AS o$date1 ,IF(
                s_target_tbl.month = $date,
              
                        s_target_tbl.value
                 ,
0
            ) t$date1 ,";

            $select2 .= " IF(
            m = $date AND t$date1 != 0,
            ROUND(t$date1 - o$date1, 2),
0
        ) AS yta$date1 ,
        IF(
            m = $date AND t$date1 != 0,
            ROUND((o$date1 * 100) / t$date1,
            2),
0
        ) AS apre$date1,
        IF(
            m = $date AND t$date1 != 0,
            ROUND(100 -((o$date1 * 100) / t$date1),
            2),
0
        ) AS ytapre$date1 ,";

            $select3 .= " SUM(t$date1) AS t$date1,
    SUM(o$date1) AS o$date1,
    SUM(yta$date1) AS yta$date1,
    SUM(apre$date1) AS apre$date1,
    SUM(ytapre$date1) AS ytapre$date1,";


            $select4 .= " t$date1 +";
            $select5 .= " o$date1 +";
            $select6 .= " yta$date1 +";
            $select7 .= " apre$date1 +";
            $select8 .= " ytapre$date1 +";

            $originalDate->modify("+1 months");
        }

        $select4 .= "0) as ttarget,";
        $select5 .= "0) as torder,";
        $select6 .= " 0) as tyta,";
        $select7 .= " 0) as tapre,";
        $select8 .= " 0) as tytapre";

        $where = substr($where, 0, -2);



        $sql = "select $select3 m_customer_types_t.customer_type,$select4 $select5 $select6 $select7 $select8 ,m_states_t.state_name,m_customers_t.customer_name
   
FROM
    (
    SELECT
        $select2 v1.*
        
    FROM
        (
        SELECT
            $select1
             s_target_tbl.customer_id as ship_to_customer_id,
            s_target_tbl.month
             AS m
           
        FROM
            
        s_target_tbl
            left JOIN s_invoice_hdr_t ON s_target_tbl.customer_id = s_invoice_hdr_t.ship_to_customer_id AND month(s_invoice_hdr_t.invoice_date)=s_target_tbl.month AND 
            year(s_invoice_hdr_t.invoice_date)=s_target_tbl.year and s_invoice_hdr_t.invoice_date BETWEEN '$from' AND '$to' AND s_invoice_hdr_t.invoice_status in ('APPROVED','CLOSED','COMPLETED')
 left JOIN s_invoice_lines_t ON s_invoice_lines_t.invoice_hdr_id = s_invoice_hdr_t.invoice_hdr_id
WHERE $where
         GROUP BY
            s_target_tbl.customer_id,
            
                s_target_tbl.month
    ) v1
) v1
left JOIN m_customers_t ON m_customers_t.customer_id = v1.ship_to_customer_id
left JOIN m_customer_types_t ON m_customer_types_t.customer_type_id = m_customers_t.customer_type_id
left join m_customer_sites_t on m_customer_sites_t.customer_id=v1.ship_to_customer_id and m_customer_sites_t.active='Yes' and m_customer_sites_t.site_type='BILL_TO' and m_customer_sites_t.primary_address='Yes' join m_states_t on m_states_t.state_id=m_customer_sites_t.state
GROUP BY v1.ship_to_customer_id";

        $results = \DB::select($sql);

        return response()->json(['data' => $results]);
    }


    public function getsummarycolumn()
    {
        $originalDate = new \DateTime($_GET['from_date']);
        $from_month = date_format($originalDate, "m");
        $from_year = date_format($originalDate, "Y");

        $originalDate1 = new \DateTime($_GET['to_date']);
        $to_month = date_format($originalDate1, "m");
        $to_year = date_format($originalDate1, "Y");
        if ($_GET['type'] == 'two') {
            $html[] = array('dataIndx' => "customer_type", 'title' => "Zone Code", 'width' => "17%");
        } else if ($_GET['type'] == 'three') {
            $html[] = array('dataIndx' => "customer_type", 'title' => "Zone Code", 'width' => "17%");
            $html[] = array('dataIndx' => "state_name", 'title' => "State", 'width' => "17%");
        } else {
            $html[] = array('dataIndx' => "customer_type", 'title' => "Zone Code", 'width' => "17%");
            $html[] = array('dataIndx' => "state_name", 'title' => "State", 'width' => "17%");
            $html[] = array('dataIndx' => "customer_name", 'title' => "Customer Name", 'width' => "17%");
        }



        while ($originalDate <= $originalDate1) {
            $date = date_format($originalDate, "M y");
            $date1 = "t" . date_format($originalDate, "mY");
            $date2 = "o" . date_format($originalDate, "mY");
            $date3 = "yta" . date_format($originalDate, "mY");
            $date4 = "apre" . date_format($originalDate, "mY");
            $date5 = "ytapre" . date_format($originalDate, "mY");
            $html[] = array('dataIndx' => "$date1", 'title' => "$date Target", 'width' => "17%");
            $html[] = array('dataIndx' => "$date2", 'title' => "$date Assb Value", 'width' => "17%");
            $html[] = array('dataIndx' => "$date3", 'title' => "$date Yet_to_Achv", 'width' => "17%");
            $html[] = array('dataIndx' => "$date4", 'title' => "$date Achd in %", 'width' => "17%");
            $html[] = array('dataIndx' => "$date5", 'title' => "$date YTA_in %", 'width' => "17%");
            $originalDate->modify("+1 months");
        }
        $html[] = array('dataIndx' => "ttarget", 'title' => "Total Target", 'width' => "17%");
        $html[] = array('dataIndx' => "torder", 'title' => "Total Assb Value", 'width' => "17%");
        $html[] = array('dataIndx' => "tyta", 'title' => "Total Yet_to_Achv", 'width' => "17%");
        $html[] = array('dataIndx' => "tapre", 'title' => "Total Achd in %", 'width' => "17%");
        $html[] = array('dataIndx' => "tytapre", 'title' => "Total YTA_in %", 'width' => "17%");




        return $html;

    }

}
