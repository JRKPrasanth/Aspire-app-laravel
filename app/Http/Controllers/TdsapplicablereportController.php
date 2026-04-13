<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class TdsapplicablereportController extends Controller
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
    
              $this->data['tds_apply'] = \DB::select("SELECT v1.supplier_name,v1.suppliertype_name as zone,ROUND(v1.total,2) as total,ROUND(v1.tds_amt,2) as tds_apply_amt  
FROM 
(SELECT
    m_supplier_t.supplier_name,m_suppliertypes_t.suppliertype_name,SUM(p_po_invoice_hdr_t.invoice_grand_total) - 5000000 as tds_amt,
    SUM(p_po_invoice_hdr_t.invoice_grand_total) AS total, (CASE WHEN SUM(p_po_invoice_hdr_t.invoice_grand_total) >= '5000000' THEN 		SUM(p_po_invoice_hdr_t.invoice_grand_total)-5000000 ELSE 0 END) as tds_apply_amt
    FROM
`p_po_invoice_hdr_t`
    LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id
    LEFT JOIN m_suppliertypes_t ON m_suppliertypes_t.suppliertype_id = m_supplier_t.supplier_type_id
    WHERE
        p_po_invoice_hdr_t.invoice_date BETWEEN '$start_date' AND '$end_date' and p_po_invoice_hdr_t.po_invoice_status='approved'
    GROUP BY
        p_po_invoice_hdr_t.supplier_id HAVING  m_supplier_t.supplier_name !=''
    ORDER BY
        `tds_apply_amt`
    DESC)v1");
    
}else{
          $this->data['tds_apply'] = \DB::select("SELECT v1.supplier_name,v1.suppliertype_name as zone,ROUND(v1.total,2) as total,ROUND(v1.tds_amt,2) as tds_apply_amt  
FROM 
(SELECT
    m_supplier_t.supplier_name,m_suppliertypes_t.suppliertype_name,SUM(p_po_invoice_hdr_t.invoice_grand_total) - 5000000 as tds_amt,
    SUM(p_po_invoice_hdr_t.invoice_grand_total) AS total, (CASE WHEN SUM(p_po_invoice_hdr_t.invoice_grand_total) >= '5000000' THEN 		SUM(p_po_invoice_hdr_t.invoice_grand_total)-5000000 ELSE 0 END) as tds_apply_amt
    FROM
`p_po_invoice_hdr_t`
    LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id
    LEFT JOIN m_suppliertypes_t ON m_suppliertypes_t.suppliertype_id = m_supplier_t.supplier_type_id
    WHERE
        p_po_invoice_hdr_t.invoice_date BETWEEN '$grid_date' AND '$gridenddate' and p_po_invoice_hdr_t.po_invoice_status='approved'
    GROUP BY
        p_po_invoice_hdr_t.supplier_id HAVING  m_supplier_t.supplier_name !=''
    ORDER BY
        `tds_apply_amt`
    DESC)v1");

}

       return view('tdsapplyreport.report', $this->data);
     
   }
    
    
}