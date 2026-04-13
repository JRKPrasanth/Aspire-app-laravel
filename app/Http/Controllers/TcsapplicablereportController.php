<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class TcsapplicablereportController extends Controller
{

 
      public function index(Request $request) {
     
      $this->data['pageMethod']=\Request::route()->getName();

     // restrict illegal entry purpose for menus - VIGNESH M

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
         	$grid_date=\Session::get('griddate');
            $gridenddate=\Session::get('gridenddate');

            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            
        if( $start_date !=''){
           
            $this->data['s_date'] =$start_date;
            $this->data['e_date'] =$end_date;
            
        }else{
            
            $this->data['s_date'] =$grid_date;
            $this->data['e_date'] =$gridenddate;
        }
            
        if( $start_date !=''){
    
              $this->data['tcs_apply'] = \DB::select("SELECT v1.customer_name,v1.customer_type as zone,ROUND(v1.total,2) as total,ROUND(v1.tcs_amt,2) as tcs_apply_amt   FROM (SELECT
    m_customers_t.customer_name,m_customer_types_t.customer_type,SUM(s_invoice_hdr_t.invoice_grand_total) - 5000000 as tcs_amt,
    SUM(
        s_invoice_hdr_t.invoice_grand_total
    ) AS total,
    (
        CASE WHEN SUM(
            s_invoice_hdr_t.invoice_grand_total
        ) >= '5000000' THEN SUM(
            s_invoice_hdr_t.invoice_grand_total
        ) -5000000 ELSE 0
    END
    ) tcs_apply_amt
    FROM
        `s_invoice_hdr_t`
    LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
    LEFT JOIN m_customer_types_t ON m_customer_types_t.customer_type_id = m_customers_t.customer_type_id
    WHERE
        s_invoice_hdr_t.invoice_date BETWEEN '$start_date' AND '$end_date' and s_invoice_hdr_t.invoice_status='approved'
    GROUP BY
        s_invoice_hdr_t.ship_to_customer_id HAVING  m_customers_t.customer_name !=''
    ORDER BY
        `tcs_apply_amt`
    DESC)v1");
    
}else{
          $this->data['tcs_apply'] = \DB::select("SELECT v1.customer_name,v1.customer_type as zone,ROUND(v1.total,2) as total,ROUND(v1.tcs_amt,2) as tcs_apply_amt   FROM (SELECT
    m_customers_t.customer_name,m_customer_types_t.customer_type,SUM(s_invoice_hdr_t.invoice_grand_total) - 5000000 as tcs_amt,
    SUM(
        s_invoice_hdr_t.invoice_grand_total
    ) AS total,
    (
        CASE WHEN SUM(
            s_invoice_hdr_t.invoice_grand_total
        ) >= '5000000' THEN SUM(
            s_invoice_hdr_t.invoice_grand_total
        ) -5000000 ELSE 0
    END
    ) tcs_apply_amt
    FROM
        `s_invoice_hdr_t`
    LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_invoice_hdr_t.ship_to_customer_id
    LEFT JOIN m_customer_types_t ON m_customer_types_t.customer_type_id = m_customers_t.customer_type_id
    WHERE
        s_invoice_hdr_t.invoice_date BETWEEN '$grid_date' AND '$gridenddate' and s_invoice_hdr_t.invoice_status='approved'
    GROUP BY
        s_invoice_hdr_t.ship_to_customer_id HAVING  m_customers_t.customer_name !=''
    ORDER BY
        `tcs_apply_amt`
    DESC)v1");

}

       return view('tcsapplyreport.report', $this->data);
     
   }
    
    
}