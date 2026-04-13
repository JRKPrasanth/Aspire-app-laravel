<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CreditupdateController extends Controller
{
 
		public function __construct()
	{
		$this->data=array();
        $this->data=array();
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
        $this->data['urlmenu']=$this->indexs(); 
	}
	
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

         return view('creditupdate.index', $this->data);
    }
    
	
		public function getcreditData() {
	                   
	            $wh='';
	            $grid_date=\Session::get('griddate');
                $gridenddate=\Session::get('gridenddate');
                $loc=\Session::get('location');
                $compy=\Session::get('companyid');

    $SQL = "SELECT * from (SELECT 
    q1.id,
    q1.bill_number,
    q1.gst_number,
    q1.invoice_date,
    ROUND(q1.total_tax_value,2) AS total_tax_value,
    ROUND (q1.cgst,2) AS cgst,
    ROUND (q1.sgst,2) AS sgst,
    ROUND (q1.igst,2) AS igst,
    q1.supplier_name,
    q1.monyr,
    q1.type,
    CASE 
        WHEN q2.bill_number IS NOT NULL 
             AND q1.gst_number = q2.gst_number 
             AND q1.invoice_date = q2.invoice_date 
             AND q1.total_tax_value = q2.total_tax_value 
        THEN 'MATCH'
        ELSE 'MISMATCH'
    END AS status
FROM (
    -- First Query
SELECT 
    v1.id,
    v1.bill_number,
    v1.gst_number,
    v1.invoice_date,
    CASE 
    WHEN v1.trans_id = '8' OR v1.unload_id = '8' OR v1.insure_id = '8' OR v1.pack_id = '8' OR v1.otax_id = '8' OR v1.freight_id = '8' 
    THEN v1.tax_value 
    ELSE (v1.tax_value + v1.transport_amt) 
    END AS total_tax_value,
    v1.supplier_name,
    v1.monyr,
    v1.type,
    CASE 
        WHEN v1.trans_id = '8' OR v1.unload_id = '8' OR v1.insure_id = '8' OR v1.pack_id = '8' OR v1.otax_id = '8' OR v1.freight_id = '8' 
            THEN v1.cgst1 
        ELSE (v1.cgst1 + v1.cgst2 + v1.cgst3 + v1.cgst4 + v1.cgst5 + v1.cgst6 + v1.cgst7) 
    END AS cgst,
    CASE 
        WHEN v1.trans_id = '8' OR v1.unload_id = '8' OR v1.insure_id = '8' OR v1.pack_id = '8' OR v1.otax_id = '8' OR v1.freight_id = '8' 
            THEN v1.sgst1 
        ELSE (v1.sgst1 + v1.sgst2 + v1.sgst3 + v1.sgst4 + v1.sgst5 + v1.sgst6 + v1.sgst7) 
    END AS sgst,
        CASE 
        WHEN v1.trans_id = '8' OR v1.unload_id = '8' OR v1.insure_id = '8' OR v1.pack_id = '8' OR v1.otax_id = '8' OR v1.freight_id = '8' 
            THEN v1.igst1 
        ELSE (v1.igst1 + v1.igst2 + v1.igst3 + v1.igst4 + v1.igst5 + v1.igst6 + v1.igst7)
    END AS igst
FROM (SELECT 
        p_po_invoice_hdr_t.po_invoice_id AS id,
        p_po_invoice_hdr_t.bill_number,
        m_supplier_sites_t.gst_number as gst_number,
        'Purchase' as type,p_po_invoice_hdr_t.company_id,p_po_invoice_hdr_t.location_id,
        p_po_invoice_hdr_t.invoice_date,
        m_supplier_t.supplier_name,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1) + 
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', -1) as transport_amt,
        ROUND((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1)), 2) as transport_tax_value,
        transport_tax_group_t.tax_group_name as transport_tax_group_name,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', 1) as trans_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', 1) as unload_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', 1) as insure_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', 1) as pack_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', 1) as otax_id,
        SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', 1) as freight_id,
        CONCAT(LEFT(MONTHNAME(p_grn_hdr_t.grn_date), 3), '-', YEAR(p_grn_hdr_t.grn_date)) as monyr,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM((p_po_invoice_lines_t.tax_amount) / 2), 2)
            ELSE 0 
        END AS cgst1,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst2,          
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.unloading_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst3,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.insurance_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst4,  
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.packing_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst5,  
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst6, 
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.other_freight_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS cgst7,                                                                     
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM((p_po_invoice_lines_t.tax_amount) / 2), 2)
            ELSE 0 
        END AS sgst1,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst2,   
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.unloading_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst3,  
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.insurance_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst4,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.packing_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst5,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst6,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN
                ROUND(((p_po_invoice_hdr_t.other_freight_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', -1))) / 2, 2)
            ELSE 0 
        END AS sgst7, 
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN ROUND(SUM((p_po_invoice_lines_t.tax_amount)), 2)
            ELSE 0 
        END AS igst1,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.transport_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst2,   
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.unloading_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.unloading_charges_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst3,  
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.insurance_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.insurance_charges_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst4,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.packing_charges) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.packing_charges_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst5,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.other_tax_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_tax_amount_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst6,
        CASE 
            WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN
                ROUND(((p_po_invoice_hdr_t.other_freight_amount) - (SUBSTRING_INDEX(p_po_invoice_hdr_t.other_frieght_amount_tax, ',', -1))), 2)
            ELSE 0 
        END AS igst7, 
        m_tax_group_t.tax_group_name as tax_group_name,
        ROUND(SUM((p_po_invoice_lines_t.line_total - p_po_invoice_lines_t.tax_amount)), 2) AS tax_value
    FROM 
        p_po_invoice_hdr_t
    LEFT JOIN 
        p_po_invoice_lines_t ON p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id
    LEFT JOIN 
        m_supplier_t ON m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id
    LEFT JOIN 
        m_tax_group_t as transport_tax_group_t on (transport_tax_group_t.tax_group_id = SUBSTRING_INDEX(p_po_invoice_hdr_t.transport_charges_tax, ',', 1))
    LEFT JOIN 
        p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number
    LEFT JOIN 
        p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number
    LEFT JOIN 
        m_tax_group_t ON m_tax_group_t.tax_group_id = p_po_invoice_lines_t.tax_group_id
    LEFT JOIN 
        m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
    LEFT JOIN 
        m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
    WHERE 
        p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'  AND p_po_invoice_hdr_t.credit_taken IS NULL 
        AND m_products_t.tax_credit = 'yes'
        AND (m_tax_group_t.tax_group_name != '' OR m_tax_group_t.tax_group_name IS NOT NULL) 
        AND DATE(p_po_invoice_hdr_t.invoice_date) BETWEEN '$grid_date' and '$gridenddate' 
    GROUP BY 
        p_po_invoice_hdr_t.po_invoice_id,p_po_invoice_hdr_t.bill_number, p_po_invoice_hdr_t.invoice_date, m_supplier_t.supplier_name
    HAVING m_tax_group_t.tax_group_name != 'NO TAX' 
    UNION All
    SELECT 
     f_expenses_t.expense_id AS id,
     f_expenses_t.invoice as bill_number,
     m_supplier_sites_t.gst_number as gst_number,

     'Expense' as type,f_expenses_t.company_id,f_expenses_t.location_id,
     f_expenses_t.bill_date as invoice_date,
     m_supplier_t.supplier_name,
     '0' as transport_amt,
     '0' as transport_tax_value,
     '0' as transport_tax_group_name,
     ''  trans_id,
     '' as unload_id,
     '' as insure_id,
     '' as pack_id,
     '' as otax_id,
     '' as freight_id,
   CONCAT(LEFT(MONTHNAME(f_expenses_t.expense_date), 3), '-', YEAR(f_expenses_t.expense_date)) as monyr,
        CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM(f_expenses_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS cgst1,
     '0' as cgst2,
     '0' as cgst3,
     '0' as cgst4,
     '0' as cgst5,
     '0' as cgst6,
     '0' as cgst7,
    CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'GST' THEN ROUND(SUM(f_expenses_lines_t.tax_amount) / 2, 2) 
        ELSE 0 
    END AS sgst1,
     '0' as sgst2,
 '0' as sgst3,
 '0' as sgst4,
 '0' as sgst5,
 '0' as sgst6,
 '0' as sgst7,
               CASE 
        WHEN SUBSTRING(m_tax_group_t.tax_group_name, 1, 3) = 'IGS' THEN ROUND(SUM(f_expenses_lines_t.tax_amount), 2) 
        ELSE 0 
    END AS igst1,
     '0' as igst2,
 '0' as igst3,
 '0' as igst4,
 '0' as igst5,
 '0' as igst6,
 '0' as igst7,
       m_tax_group_t.tax_group_name,
  SUM(f_expenses_lines_t.expense_line_amount) as tax_value
FROM 
    f_expenses_t
LEFT JOIN 
    f_expenses_lines_t ON f_expenses_lines_t.expense_id = f_expenses_t.expense_id
LEFT JOIN 
    m_tax_group_t ON m_tax_group_t.tax_group_id = f_expenses_lines_t.tax_group_id
LEFT JOIN 
    m_supplier_t ON m_supplier_t.supplier_id = f_expenses_t.supplier_id
LEFT JOIN 
m_supplier_sites_t on(m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id and m_supplier_sites_t.primary_address='Yes')
LEFT JOIN 
f_account_structure_t as accountstructure_lines  on(accountstructure_lines.f_account_structure_id=f_expenses_lines_t.expense_account_id)
WHERE 
    f_expenses_t.expense_status = 'APPROVED'  AND f_expenses_t.credit_taken IS NULL  AND m_tax_group_t.tax_group_name != 'NO TAX'
    AND f_expenses_t.bill_date BETWEEN '$grid_date' and '$gridenddate' 
GROUP BY 
    f_expenses_t.expense_id,f_expenses_t.expense_no ,f_expenses_t.bill_date, m_supplier_t.supplier_name  HAVING m_tax_group_t.tax_group_name != 'NO TAX')v1)q1
  LEFT JOIN (
    -- Second Query
    SELECT   
a_gstb2b_t.id ,
    a_gstb2b_t.invoice_number AS bill_number,
    a_gstb2b_t.supplier_gstin AS gst_number,
    a_gstb2b_t.invoice_date,
    a_gstb2b_t.taxable_value AS total_tax_value
FROM 
    a_gstb2b_t
LEFT JOIN 
    p_po_invoice_hdr_t ON p_po_invoice_hdr_t.bill_number = a_gstb2b_t.invoice_number 
    AND p_po_invoice_hdr_t.invoice_date = a_gstb2b_t.invoice_date
LEFT JOIN 
    m_supplier_sites_t ON m_supplier_sites_t.gst_number = a_gstb2b_t.supplier_gstin
WHERE 
    a_gstb2b_t.invoice_date BETWEEN '$grid_date' AND '$gridenddate' 
GROUP BY 
    a_gstb2b_t.id,a_gstb2b_t.invoice_number, a_gstb2b_t.invoice_date, a_gstb2b_t.supplier_gstin
) q2  
ON q1.bill_number = q2.bill_number 
AND q1.gst_number = q2.gst_number 
AND q1.invoice_date = q2.invoice_date)v2 where 1=1  $wh ";

       
        $result = \DB::select( $SQL );
		return DataTables::of($result)->make(true);
			
	}
		

public function creditupdatesave(Request $request) {
    //dd("hii");
    
    try {
        $po_ids = explode(',', $request->id); // Convert IDs to an array
        $type = $request->type;

        if (empty($po_ids)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid ID provided.']);
        }

        $credit_taken = "Yes";
        $credit_date = date('Y-m-d',strtotime($request->credit_date)); 

        // Update the records based on ID and Type
        if ($type == "Purchase"){
        
        $updatedRows = \DB::table('p_po_invoice_hdr_t')
            ->whereIn('po_invoice_id', $po_ids)
            ->update(['credit_taken' => $credit_taken, 'credit_date' => $credit_date]);

        }else{
    
            $updatedRows = \DB::table('f_expenses_t')
            ->whereIn('expense_id', $po_ids)
            ->update(['credit_taken' => $credit_taken, 'credit_date' => $credit_date]);
            }

        if ($updatedRows > 0) {
            return response()->json(['status' => 'success', 'message' => 'Updated Successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No records updated.']);
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage()]);
    }
}

  
}
