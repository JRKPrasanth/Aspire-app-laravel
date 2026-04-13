<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class SalesreturnsummaryController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }
    public function salesreturnsummaryindex(Request $request)
    {

        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');


        $start_month = date('Y-m', strtotime($start_date));
        $end_month = date('Y-m', strtotime($end_date));

        //    dd($end_month);

        //  sales vs return order
        
        $this->data['prim_summary'] = \DB::select("SELECT
    t.cus_name,t.log_month,sum(t.no_of_inv) sales ,sum(t.no_of_rtn) rtn, round((sum(t.no_of_rtn)/sum(t.no_of_inv))*100,2) rtnper
FROM
    (
(SELECT
    m_customers_t.customer_name cus_name,
    CONCAT(SUBSTRING(MONTHNAME(s_invoice_hdr_t.invoice_date), 1, 3), '-', YEAR(s_invoice_hdr_t.invoice_date) % 100) AS log_month,
    MONTH(s_invoice_hdr_t.invoice_date) as mnth,
    YEAR(s_invoice_hdr_t.invoice_date) as year,                                                                                                  
    COUNT(s_invoice_hdr_t.invoice_number) no_of_inv,
    '' as no_of_rtn
FROM
    `s_invoice_hdr_t`
LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id

WHERE
    s_invoice_hdr_t.invoice_date BETWEEN '$start_date' AND '$end_date' AND s_invoice_hdr_t.invoice_status = 'approved' AND s_invoice_hdr_t.invoice_type = 'standard'
GROUP BY
     cus_name,log_month
ORDER BY
    mnth, cus_name)

union all

 (SELECT
        m_customers_t.customer_name cus_name,
        CONCAT(SUBSTRING(MONTHNAME(so_rma_hdr_t.return_date), 1, 3), '-', YEAR(so_rma_hdr_t.return_date) % 100) AS log_month,
        MONTH(so_rma_hdr_t.return_date) as mnth,
        YEAR(so_rma_hdr_t.return_date) as year,
        '' as no_of_inv, 
        COUNT(so_rma_hdr_t.rma_ref_no) no_of_rtn
    FROM
        so_rma_hdr_t
    LEFT JOIN m_customers_t ON m_customers_t.customer_id = so_rma_hdr_t.customerid
    WHERE
        so_rma_hdr_t.return_date BETWEEN '$start_date' AND '$end_date' AND so_rma_hdr_t.return_status = 'approved'
    GROUP BY
        cus_name,
        log_month
    ORDER BY
        mnth,
        cus_name) ) t 

GROUP BY t.cus_name, t.log_month
        ORDER BY t.cus_name,t.year, t.mnth");



        //  sales vs return qty

        $this->data['type_summary'] = \DB::select("SELECT
    t.cus_name,t.log_month,sum(t.qty) sales ,sum(t.rtn_qty) rtn, round((sum(t.rtn_qty)/sum(t.qty))*100,2) rtnper
FROM
    (
(SELECT
    m_customers_t.customer_name cus_name,
    CONCAT(SUBSTRING(MONTHNAME(s_invoice_hdr_t.invoice_date), 1, 3), '-', YEAR(s_invoice_hdr_t.invoice_date) % 100) AS log_month,
    MONTH(s_invoice_hdr_t.invoice_date) as mnth,
    YEAR(s_invoice_hdr_t.invoice_date) as year,                                                                                                  
    sum(s_invoice_lines_t.qty) qty,
    '' as rtn_qty
FROM
    `s_invoice_hdr_t`
LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
LEFT JOIN s_invoice_lines_t ON s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id 

WHERE
    s_invoice_hdr_t.invoice_date BETWEEN '$start_date' AND '$end_date' AND s_invoice_hdr_t.invoice_status = 'approved' AND s_invoice_hdr_t.invoice_type = 'standard'
GROUP BY
     cus_name,log_month
ORDER BY
    mnth, cus_name)

union all

 (SELECT
        m_customers_t.customer_name cus_name,
        CONCAT(SUBSTRING(MONTHNAME(so_rma_hdr_t.return_date), 1, 3), '-', YEAR(so_rma_hdr_t.return_date) % 100) AS log_month,
        MONTH(so_rma_hdr_t.return_date) as mnth,
        YEAR(so_rma_hdr_t.return_date) as year,
        '' as qty, 
        sum(so_rma_lines_t.return_qty) rtn_qty
    FROM
        so_rma_hdr_t
    LEFT JOIN m_customers_t ON m_customers_t.customer_id = so_rma_hdr_t.customerid
  	LEFT JOIN so_rma_lines_t ON so_rma_hdr_t.so_rma_hdr_id = so_rma_lines_t.so_rma_hdr_id
    WHERE
        so_rma_hdr_t.return_date BETWEEN '$start_date' AND '$end_date' AND so_rma_hdr_t.return_status = 'approved'
    GROUP BY
        cus_name,
        log_month
    ORDER BY
        mnth,
        cus_name) ) t 

GROUP BY t.cus_name, t.log_month
        ORDER BY t.cus_name,t.year, t.mnth ");


    //sales vs return value

        $this->data['value_summary'] = \DB::select("SELECT
    t.cus_name,t.log_month,round(sum(t.tot_val),0) sales ,round(sum(t.rtn_val),0) rtn, round((sum(t.rtn_val)/sum(t.tot_val))*100,2) rtnper
FROM
    (
(SELECT
    m_customers_t.customer_name cus_name,
    CONCAT(SUBSTRING(MONTHNAME(s_invoice_hdr_t.invoice_date), 1, 3), '-', YEAR(s_invoice_hdr_t.invoice_date) % 100) AS log_month,
    MONTH(s_invoice_hdr_t.invoice_date) as mnth,
    YEAR(s_invoice_hdr_t.invoice_date) as year,                                                                                                  
    sum(s_invoice_lines_t.line_total) tot_val,
    '' as rtn_val
FROM
    `s_invoice_hdr_t`
LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
LEFT JOIN s_invoice_lines_t ON s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id 

WHERE
    s_invoice_hdr_t.invoice_date BETWEEN '$start_date' AND '$end_date' AND s_invoice_hdr_t.invoice_status = 'approved' AND s_invoice_hdr_t.invoice_type = 'standard'
GROUP BY
     cus_name,log_month
ORDER BY
    mnth, cus_name)

union all

 (SELECT
        m_customers_t.customer_name cus_name,
        CONCAT(SUBSTRING(MONTHNAME(so_rma_hdr_t.return_date), 1, 3), '-', YEAR(so_rma_hdr_t.return_date) % 100) AS log_month,
        MONTH(so_rma_hdr_t.return_date) as mnth,
        YEAR(so_rma_hdr_t.return_date) as year,
        '' as tot_val, 
        sum(so_rma_lines_t.total_amount) rtn_val
    FROM
        so_rma_hdr_t
    LEFT JOIN m_customers_t ON m_customers_t.customer_id = so_rma_hdr_t.customerid
  	LEFT JOIN so_rma_lines_t ON so_rma_hdr_t.so_rma_hdr_id = so_rma_lines_t.so_rma_hdr_id
    WHERE
        so_rma_hdr_t.return_date BETWEEN '$start_date' AND '$end_date' AND so_rma_hdr_t.return_status = 'approved'
    GROUP BY
        cus_name,
        log_month
    ORDER BY
        mnth,
        cus_name) ) t 

GROUP BY t.cus_name, t.log_month
        ORDER BY t.cus_name,t.year, t.mnth ");


        return view('salesreturnsummary.salesreturnsumrpt', $this->data);
    }
}
