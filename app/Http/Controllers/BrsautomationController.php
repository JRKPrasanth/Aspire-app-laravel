<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BrsautomationController  extends Controller
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
          
    // restrict menu illegal entry purpose - VIGNESH M

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

         return view('brsautomation.index', $this->data);
 
 
    }
    
	public function getbrsData() {
	                   
	            $wh='';
	            $grid_date=\Session::get('griddate');
                $gridenddate=\Session::get('gridenddate');
                $loc=\Session::get('location');
                $compy=\Session::get('companyid');
           
    $SQL = "SELECT 
    v1.id, 
    v1.date, 
    v1.number,
    v1.name, 
    v1.amount, 
    v1.narration, 
    v1.type, 
    v1.payment_mode, 
    v1.status, 
    b.bankstmt_id, 
    b.bank_id,
    f_bank_account_hdr_t.bank_name,
    b.date AS bank_date, 
    b.narration AS bank_narration, 
    CASE 
        WHEN v1.type = 'payment' THEN b.debit
        ELSE b.credit
    END AS bank_amount
FROM 
(
    SELECT 
        p.payment_id AS id, 
        p.payment_date AS date, 
        p.payment_number as number,
        s.supplier_name AS name, 
        p.payment_amount AS amount, 
        p.remarks AS narration, 
        p.payment_type_id AS payment_mode,
        'payment' AS type,
        CASE 
            WHEN b.debit = p.payment_amount AND b.narration = p.remarks AND b.date = p.payment_date THEN 'MATCH'
            WHEN b.debit = p.payment_amount AND b.narration LIKE CONCAT('%', p.remarks, '%') AND DATEDIFF(b.date, p.payment_date) <> 5 THEN 'PARTIAL MATCH'
            ELSE 'MISMATCH'
        END AS status,
        b.bankstmt_id
    FROM p_payments_t p
    LEFT JOIN f_bankstmtupload_t b 
        ON b.debit > 0 
        AND b.debit = p.payment_amount 
        AND (b.narration = p.remarks OR b.narration LIKE CONCAT('%', p.remarks, '%'))
    LEFT JOIN m_supplier_t s ON p.supplier_id = s.supplier_id 
    WHERE (p.stmtid = '0' OR p.stmtid IS NULL) AND p.payment_type_id NOT IN ('CASH','IMPREST') AND p.cancel_status IS NULL 
    GROUP BY p.payment_id

    UNION ALL

    SELECT 
        r.receipt_id AS id, 
        r.receipt_date AS date, 
        r.receipt_number as number,
        c.customer_name AS name, 
        r.receipt_amount AS amount, 
        r.remarks AS narration, 
         r.receipt_type_id AS payment_mode,
        'receipt' AS type,
        CASE 
            WHEN b.credit = r.receipt_amount AND b.narration = r.remarks AND b.date = r.receipt_date THEN 'MATCH'
            WHEN b.credit = r.receipt_amount AND b.narration LIKE CONCAT('%', r.remarks, '%') AND DATEDIFF(b.date, r.receipt_date) <> 5 THEN 'PARTIAL MATCH'
            ELSE 'MISMATCH'
        END AS status,
        b.bankstmt_id
    FROM s_receipts_t r
    LEFT JOIN f_bankstmtupload_t b 
        ON b.credit > 0 
        AND b.credit = r.receipt_amount 
        AND (b.narration = r.remarks OR b.narration LIKE CONCAT('%', r.remarks, '%'))
    LEFT JOIN m_customers_t c ON r.customer_id = c.customer_id
    WHERE (r.stmtid = '0' OR r.stmtid IS NULL) AND receipt_type_id != 'CASH' AND cheque_cancel_status != 'Cancelled'
    GROUP BY r.receipt_id
) v1

LEFT JOIN f_bankstmtupload_t b ON v1.bankstmt_id = b.bankstmt_id
LEFT JOIN f_bank_account_hdr_t  ON f_bank_account_hdr_t.bank_account_hdr_id  = b.bank_id
WHERE v1.narration != 'Being imprest transfer' $wh GROUP BY v1.id
ORDER BY 
    CASE v1.status
        WHEN 'MATCH' THEN 1
        WHEN 'PARTIAL MATCH' THEN 2
        WHEN 'MISMATCH' THEN 3
        ELSE 4
    END,
    v1.date DESC";

   
        $result = \DB::select( $SQL );
		
		return DataTables::of($result)->make(true);
	}
		

public function brsupdatesave(Request $request) {
    
    
    
    try {
     
        $ids = explode(',', $request->id);
        $bank_ids = explode(',', $request->bank_id);
        $type = $request->type;
       //dd($ids);
        if (empty($ids)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid ID provided.']);
        }

        // Update the records based on ID and Type
        if ($type == "payment"){
        
        $updatedRows = \DB::table('p_payments_t') ->whereIn('payment_id', $ids)->update(['stmtid' => $bank_ids]);
        
        $Bankupdate = \DB::table('f_bankstmtupload_t') ->whereIn('bankstmt_id', $bank_ids)->update(['status' => "1"]);


        }else{
    
            $updatedRows = \DB::table('s_receipts_t') ->whereIn('receipt_id', $ids) ->update(['stmtid' => $bank_ids]);
            
            $Bankupdate = \DB::table('f_bankstmtupload_t') ->whereIn('bankstmt_id', $bank_ids)->update(['status' => "1"]);
            
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
