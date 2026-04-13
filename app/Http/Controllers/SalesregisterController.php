<?php

namespace App\Http\Controllers;

use App\Salesregister;
use Illuminate\Http\Request;

class SalesregisterController extends Controller
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
        if ($this->data['pageMethod'] == "soqtysuppliedrpt") {
            return view('salesregister.soqtytable', $this->data);
        } else {
            return view('salesregister.table', $this->data);
        }
    }



    public function getsalesqtysupplieddata(Request $request)
    {

        $compy = \Session::get('companyid');
        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "SELECT
    *
FROM
    (
    SELECT
        s_invoice_hdr_t.invoice_hdr_id,
        s_invoice_hdr_t.invoice_number,
        s_invoice_hdr_t.invoice_date,
        m_products_t.concatenated_product,
        (
            CASE WHEN s_invoice_hdr_t.ship_to_customer_id != '0' THEN CONCAT(
                m_customers_t.customer_number,
                '-',
                m_customers_t.customer_name
            ) ELSE hr_employee_t.first_name
        END
) AS customer_name,
s_invoice_lines_t.batch_number,
s_invoice_lines_t.qty,
s_invoice_lines_t.invoice_line_id,
s_invoice_lines_t.tax_amount
FROM
    s_invoice_hdr_t
LEFT JOIN s_invoice_lines_t ON(
        s_invoice_lines_t.invoice_hdr_id = s_invoice_hdr_t.invoice_hdr_id
    )
LEFT JOIN m_products_t ON(
        m_products_t.product_id = s_invoice_lines_t.product_id
    )
LEFT JOIN m_customers_t ON
    (
        m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
    )
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = s_invoice_hdr_t.employee_id
WHERE
    1 = 1 AND s_invoice_hdr_t.invoice_status = 'APPROVED' AND s_invoice_hdr_t.invoice_date BETWEEN ? AND ?
) AS v1";


        $results = \DB::select($SQL, [$start_date, $end_date]);


        return response()->json(['data' => $results]);
    }

    /* Purpose For Purchase Register*/
    public function getsalesregister(Request $request)
    {

        $compy = \Session::get('companyid');
        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $SQL = "SELECT * from ( select v2.*,round((v2.trade_amount1),2) as trade_amount from (select v1.* ,round((v1.line_total-v1.tax_amount),2) as accessablevalu,round(sum(((qty*unit_price)-discount_amount)*(cash/100)),2) as cas_amount,
                round(sum(((qty*unit_price)-discount_amount)*((trade_discount_pre-cash)/100)),2) as trade_amount1,
                round(trade_discount_pre-cash,2) as trade_discount from(SELECT
    s_invoice_lines_t.*,
    concatenated_product AS prdname,
    m_products_t.product_code as prodt_cod,
    s_salesorder_hdr_t.sales_order_no,
    s_salesorder_hdr_t.sales_order_date,
    s_invoice_hdr_t.invoice_number,
    s_invoice_hdr_t.delivery_date,
    s_invoice_hdr_t.invoice_date,
    s_invoice_hdr_t.invoice_type,
    s_invoice_hdr_t.invoice_status,
    s_invoice_hdr_t.invoice_grand_total,
    s_invoice_hdr_t.invoice_tax_total,
    s_invoice_hdr_t.invoice_pricelist_id,
    s_invoice_hdr_t.paid_amount,
    s_invoice_hdr_t.balance_amount,
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
i_pricelist_hdr_t.pricelist_name,
m_uom_codes_t.uom_code,
m_tax_group_t.tax_group_name,
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
f_account_currency_t.currency_code,
COALESCE((select s_schemes_lines_t.schemes_type_value from s_schemes_lines_t where s_schemes_lines_t.schemes_hdr_id=s_invoice_hdr_t.cash_discount),0)as cash,
s_invoice_hdr_t.trade_discount_pre,
 (SELECT state_name from m_states_t WHERE m_states_t.state_id=m_customer_sites_t.state and   m_customer_sites_t.customer_site_id = s_invoice_hdr_t.ship_to_customer_id ) as statename,
(SELECT city_name from m_cities_t WHERE m_cities_t.city_id=m_customer_sites_t.city and   m_customer_sites_t.customer_site_id = s_invoice_hdr_t.ship_to_customer_id ) as city_name,
(SELECT category_name  from m_product_category_t where m_product_category_t.product_category_id=m_products_t.product_category_id) as category_name,
(SELECT subcategory_name  from m_product_subcategory_t where m_product_subcategory_t.product_subcategory_id =m_products_t.product_subcategory_id) as subcategory_name,
m_customer_sites_t.gst_no,
(SELECT customer_type  from m_customer_types_t where m_customer_types_t.customer_type_id =m_customers_t.customer_type_id) as customer_type
FROM
    s_invoice_lines_t
LEFT JOIN s_invoice_hdr_t ON s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id
LEFT JOIN i_pricelist_hdr_t ON
    (
        i_pricelist_hdr_t.pricelist_hdr_id = s_invoice_hdr_t.invoice_pricelist_id
    )
LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
LEFT JOIN m_customer_sites_t ON m_customer_sites_t.customer_id = s_invoice_hdr_t.ship_to_customer_id and m_customer_sites_t.site_type='SHIP_TO' and m_customer_sites_t.primary_address='YES' and m_customer_sites_t.active='Yes'
LEFT JOIN m_products_t ON m_products_t.product_id = s_invoice_lines_t.product_id
LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = s_invoice_lines_t.uom_code_id
LEFT JOIN m_tax_group_t ON
    (
        m_tax_group_t.tax_group_id = s_invoice_lines_t.tax_group_id
    )
LEFT JOIN f_gst_code_hdr_t ON f_gst_code_hdr_t.gst_code_hdr_id = s_invoice_lines_t.hsn_code
LEFT JOIN f_account_currency_t ON f_account_currency_t.account_currency_id = s_invoice_hdr_t.invoice_currency_id
LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = s_invoice_hdr_t.employee_id
LEFT JOIN s_salesorder_hdr_t ON s_salesorder_hdr_t.sales_hdr_id = s_invoice_hdr_t.ar_sales_hdr_id
WHERE
    1 = 1  and  s_invoice_hdr_t.invoice_status='APPROVED' and s_invoice_hdr_t.invoice_date BETWEEN  ? and ?
ORDER BY cash  DESC)v1 group by v1.invoice_line_id ) v2 ) AS vikki";


        $result = \DB::select($SQL, [$start_date, $end_date]);

        foreach ($result as $key => $value) {

            $first = substr($result[$key]->tax_group_name, 0, 3);
            $tax_amt = $result[$key]->tax_amount;
            $result[$key]->cgst = 0;
            $result[$key]->igst = 0;
            $result[$key]->sgst = 0;

            if ($first == "GST") {
                $rate1 = ($tax_amt / 2);
                $rate = (round($rate1, 2));
                $result[$key]->cgst = $rate;
                $result[$key]->sgst = $rate;
            } elseif ($first == "IGS") {
                $result[$key]->igst = $tax_amt;
            } else {

            }
        }

        return response()->json(['data' => $result]);
    }


}
