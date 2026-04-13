<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use DB;
use Yajra\DataTables\DataTables;

class TransportcalculationController extends Controller
{
    
   public function index(Request $request){
       
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
		$this->data['pageMethod']=\Request::route()->getName();
	   
        $carrier_id = $request->input('freight_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        $this->data['frieght']= $this->jcustomselect('m_frieghtcarriers_hdr_t','ar_frieghtcarriers_hdr_id','carrier_name',''," and source_type_id ='Sales'");
       
        $this->data['transport_detail']= \DB::select("SELECT 
    v1.invoice_number,
    v1.invoice_date,
    v1.lr_no,
    v1.booking_from,
    v1.invoice_grand_total,
    v1.ship_to_customer_id,
    v1.cus_state,
    v1.employee_id,
    v1.emp_state,
    v1.packaging_qty,
    v1.docket_charge,
    v1.booking_to,
    v1.state_name,
    v1.pack_weight,
    v1.distance,
    v1.rate,
    v1.rov,
    CASE WHEN v1.state_name ='Delhi' THEN '75' ELSE 0 END AS green_tax,
    CASE 
        WHEN v1.rov > 125 THEN ROUND(v1.rov, 0) 
        ELSE 125 
    END AS rov_charge,
    v1.metro_city AS metro,
    CASE 
        WHEN v1.metro_city = 'No' THEN ROUND(v1.pack_weight * 1, 0) 
        ELSE 0 
    END AS metro_city,
    COALESCE(s_essmatrix_t.cost, 0) AS ess_value, 
    ROUND(v1.amount, 0) AS amount,
    -- Debug columns
    s_essmatrix_t.distance_start,
    s_essmatrix_t.distance_end,
    s_essmatrix_t.weight_start,
    s_essmatrix_t.weight_end,
    CASE 
        WHEN v1.distance BETWEEN s_essmatrix_t.distance_start AND s_essmatrix_t.distance_end
             AND v1.pack_weight BETWEEN s_essmatrix_t.weight_start AND s_essmatrix_t.weight_end 
        THEN 'Match'
        ELSE 'No Match'
    END AS match_status
FROM (
    SELECT 
        s_invoice_hdr_t.invoice_number,
        s_invoice_hdr_t.invoice_date,
        s_invoice_hdr_t.lr_no,
        s_invoice_hdr_t.ship_to_customer_id,
        ROUND(s_invoice_hdr_t.invoice_grand_total, 0) as invoice_grand_total,
        s_invoice_hdr_t.invoice_grand_total * (0.1 / 100) AS rov,
        s_invoice_hdr_t.employee_id,
        hr_emp_contact.permanent_state AS emp_state,
        'Tamil Nadu' AS booking_from,
        s_transportcalc_t.distance,
        s_transportcalc_t.value AS rate,
        s_dispatch_hdr_t.pack_weight,
        s_dispatch_hdr_t.packaging_qty, 
        '175' AS docket_charge,
        m_customer_sites_t.state AS cus_state,
        CASE 
            WHEN s_invoice_hdr_t.ship_to_customer_id IS NULL OR s_invoice_hdr_t.ship_to_customer_id = '0'
            THEN hr_emp_contact.permanent_state
            ELSE m_customer_sites_t.state 
        END AS booking_to,
        m_states_t.state_name,
        s_dispatch_hdr_t.pack_weight * s_transportcalc_t.value AS amount,
        s_transportcalc_t.metro_city
    FROM 
        s_invoice_hdr_t
    LEFT JOIN 
        m_customer_sites_t 
        ON m_customer_sites_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
    LEFT JOIN 
        hr_emp_contact 
        ON hr_emp_contact.employee_id = s_invoice_hdr_t.employee_id
    LEFT JOIN 
        s_dispatch_hdr_t 
        ON s_dispatch_hdr_t.dispatch_number = s_invoice_hdr_t.reference_number
    LEFT JOIN 
        s_transportcalc_t 
        ON s_transportcalc_t.state = 
            (CASE 
                WHEN s_invoice_hdr_t.ship_to_customer_id IS NULL OR s_invoice_hdr_t.ship_to_customer_id = '0'
                THEN hr_emp_contact.permanent_state 
                ELSE m_customer_sites_t.state 
            END)
    LEFT JOIN 
        m_states_t 
        ON m_states_t.state_id = 
            (CASE 
                WHEN s_invoice_hdr_t.ship_to_customer_id IS NULL OR s_invoice_hdr_t.ship_to_customer_id = '0'
                THEN hr_emp_contact.permanent_state 
                ELSE m_customer_sites_t.state 
            END)
    WHERE 
        s_invoice_hdr_t.lr_date BETWEEN '$start_date' AND '$end_date' 
        AND s_invoice_hdr_t.ar_frieghtcarriers_hdr_id = '$carrier_id'  
        AND s_transportcalc_t.from_freight = 'EMAA' GROUP BY s_invoice_hdr_t.invoice_number,s_invoice_hdr_t.invoice_date) v1
LEFT JOIN 
    s_essmatrix_t 
    ON v1.distance BETWEEN s_essmatrix_t.distance_start AND s_essmatrix_t.distance_end
    AND v1.pack_weight BETWEEN s_essmatrix_t.weight_start AND s_essmatrix_t.weight_end
ORDER BY 
    v1.invoice_date ASC");
           
       
       
       	return view('transportcalculation.table', $this->data);
   }    
    
    
}
    