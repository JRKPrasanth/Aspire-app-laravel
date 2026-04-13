<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class B2creportController extends Controller
{

	  public function __construct()
  {

    $this->data = array();
    $this->data['pageMethod'] = \Request::route()->getName();

  }
	
	public function index(){

        return view('b2report.b2creport', $this->data);	
    
        }

	public function getb2creport(Request $request){

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

 
       $result1=\DB::select("SELECT 
        s_invoice_hdr_t.invoice_date,
        s_invoice_hdr_t.invoice_number,
        COALESCE(m_customers_t.customer_name, hr_employee_t.first_name) AS cusname,
        m_customer_sites_t.gst_no,
        CASE 
        WHEN s_invoice_hdr_t.employee_id != '0' THEN (SELECT state_name FROM m_states_t WHERE m_states_t.state_id = hr_emp_contact.current_state) 
        ELSE (SELECT state_name FROM m_states_t WHERE m_states_t.state_id = m_customer_sites_t.state) 
    END AS statename,
    m_tax_group_t.tax_group_name,
    
     ROUND(SUM(s_invoice_lines_t.line_total - s_invoice_lines_t.tax_amount), 2) AS accessablevalu,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM(s_invoice_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS cgst,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM(s_invoice_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS sgst,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN ROUND(SUM(s_invoice_lines_t.tax_amount), 2) 
        ELSE 0 
    END AS igst
FROM 
    s_invoice_lines_t
JOIN 
    s_invoice_hdr_t ON s_invoice_hdr_t.invoice_hdr_id = s_invoice_lines_t.invoice_hdr_id
LEFT JOIN 
    m_customers_t ON m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
LEFT JOIN 
    m_customer_sites_t ON m_customer_sites_t.customer_site_id = s_invoice_hdr_t.bill_to_address_id 
  AND m_customer_sites_t.site_type = 'BILL_TO' AND m_customer_sites_t.active = 'Yes'
LEFT JOIN 
    m_tax_group_t ON m_tax_group_t.tax_group_id = s_invoice_lines_t.tax_group_id
LEFT JOIN 
    hr_employee_t ON hr_employee_t.employee_id = s_invoice_hdr_t.employee_id
LEFT JOIN 
    hr_emp_contact ON hr_emp_contact.employee_id = s_invoice_hdr_t.employee_id
WHERE  
    s_invoice_hdr_t.invoice_date BETWEEN '$start_date' AND '$end_date' AND s_invoice_hdr_t.invoice_type='STANDARD' AND s_invoice_hdr_t.invoice_status ='APPROVED' AND m_customer_sites_t.gst_no =''
GROUP BY s_invoice_hdr_t.invoice_date,s_invoice_hdr_t.invoice_number,m_customers_t.customer_name, m_tax_group_t.tax_group_name ORDER BY s_invoice_hdr_t.invoice_date DESC");

    return response()->json(['data' => $result1]);
}
	
}

