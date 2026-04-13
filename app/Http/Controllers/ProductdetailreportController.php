<?php

namespace App\Http\Controllers;

use App\Productbasedcostreport;
use Illuminate\Http\Request;
use DB;
class ProductdetailreportController extends Controller
{
    	public $module="Productbasedcostreport";
	
	    public function __construct(){
		
        $this->data=array();
        $this->data['pageFormtype']='ajax';
        $this->data['pageMethod']=\Request::route()->getName();
	
    }
	
	
    public function index()
    {
       return view('productdetailreport.productdetailreporttable');    
    }
          public function getproductbasedcost(){

                $wh='';
           
               if(isset($_GET['pq_filter']))
		{
		$data=json_decode($_GET['pq_filter']);
		$data=$data->data;
	     $wh.=$this->pqgridsearchsum('v1',$data);
		}
        $page = $_GET['pq_curpage'];
        $limit = $_GET['pq_rpp'];
      $sidx='';
        if (!$sidx)
            $sidx = 1;
        
         $SQL = "select * from (SELECT
                p_po_invoice_lines_t.po_invoice_lines_id,
                p_po_invoice_lines_t.product_id,
                sum(p_po_invoice_lines_t.qty*p_po_invoice_lines_t.unit_price) as price,
                m_products_t.concatenated_product
                FROM
                p_po_invoice_lines_t
                LEFT JOIN m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
                WHERE
                1 = 1 AND p_po_invoice_lines_t.company_id = 1 AND p_po_invoice_lines_t.location_id = 1
                group by product_id ) v1 where 1=1 $wh";
	   
              $result = \DB::select($SQL);
		$count = count($result);
		if( $count > 0 && $limit > 0)
		{
		$total_pages = ceil($count/$limit);
		} else {
		$total_pages = 0;
		}
		if ($page > $total_pages)
		$page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
      
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $SQL = "select * from (SELECT
                p_po_invoice_lines_t.po_invoice_lines_id,
                p_po_invoice_lines_t.product_id,
                sum(p_po_invoice_lines_t.qty*p_po_invoice_lines_t.unit_price) as price,
                m_products_t.concatenated_product
                FROM
                p_po_invoice_lines_t
                LEFT JOIN m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
                WHERE
                1 = 1 AND p_po_invoice_lines_t.company_id = 1 AND p_po_invoice_lines_t.location_id = 1
                group by product_id ) v1 where 1=1 $wh
                ORDER BY price
                DESC LIMIT $start , $limit";
	   
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->data=$result;
		$responce->curPage = $page;
		$responce->total = $total_pages;
		$responce->totalRecords = $count;
		echo json_encode($responce);
	}

public function productspecindex()
    {
       return view('productdetailreport.productspecdetails', $this->data);    
    }
	
      public function getproductspecdata(Request $request){

		  
		  
        $SQL = "select * from (SELECT i_quality_product_specs_lines_t.quality_product_specs_line_id,i_quality_product_specs_lines_t.quality_product_specs_hdr_id,i_quality_product_specs_lines_t.parameter,i_quality_product_specs_lines_t.spec_value_from,i_quality_product_specs_lines_t.spec_value_to,i_quality_product_specs_lines_t.uom,i_quality_product_specs_lines_t.comments,a_lookuplines_t.lookup_code,m_products_t.concatenated_product,m_product_category_t.category_name,m_product_groups_t.group_name FROM i_quality_product_specs_lines_t left join i_quality_product_specs_hdr_t on (i_quality_product_specs_hdr_t.quality_product_specs_hdr_id=i_quality_product_specs_lines_t.quality_product_specs_hdr_id) left join a_lookuplines_t on (a_lookuplines_t.lookuplines_id=i_quality_product_specs_lines_t.spec_criteria and a_lookuplines_t.lookup_type='SPEC_CRITERIA') left join m_products_t on (m_products_t.product_id=i_quality_product_specs_hdr_t.product_id) left join m_product_groups_t on (m_product_groups_t.product_group_id=m_products_t.product_group_id) left join m_product_category_t on (m_product_category_t.product_category_id=m_products_t.product_category_id) WHERE 1=1) v1 having v1.group_name !=''";
       
	
    $results = \DB::select($SQL);

    return response()->json(['data' => $results]);
		  
    }
	
	public function jobcostreportindex() {
		
       return view('productdetailreport.jobcostreport', $this->data);    
		
    }
	
	
          public function getjobcostreportget(Request $request){
                $wh='';
           
     $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
        
        $SQL = "SELECT
    *
FROM
    (
    SELECT
        f_journal_entry_t.journal_entry_id,
        f_journal_entry_t.journal_name,
        f_journal_entry_t.journal_date,
        f_journal_entry_t.journal_type,
        CONCAT(
            SUBSTRING(
                MONTHNAME(f_journal_entry_t.journal_date),
                1,
                3
            ),
            '-',
            YEAR(f_journal_entry_t.journal_date)
        ) AS journal_month,
        (
        SELECT
            w_jobcard_hdr_t.job_no
        FROM
            w_jobcard_hdr_t
        WHERE
            w_jobcard_hdr_t.w_jobs_hdr_id = f_journal_entry_t.journal_reference
        LIMIT 1
    ) AS job_no,
        (
        SELECT
            w_jobcard_hdr_t.batch_no
        FROM
            w_jobcard_hdr_t
        WHERE
            w_jobcard_hdr_t.w_jobs_hdr_id = f_journal_entry_t.journal_reference
        LIMIT 1
    ) AS batch,
       (
       SELECT
           w_jobcard_process_details_t.move_qty
       FROM
           w_jobcard_process_details_t
       WHERE
           w_jobcard_process_details_t.job_id = f_journal_entry_t.journal_reference
       LIMIT 1    
     ) AS j_qty,
       (
       SELECT
           w_qa_submitstage_trx_t.production_qty
       FROM
           w_qa_submitstage_trx_t
       WHERE
           w_qa_submitstage_trx_t.job_no = f_journal_entry_t.journal_reference
       LIMIT 1    
     ) AS jclose_qty,        
       
    CASE WHEN f_journal_entry_lines_t.reference_source = 'PRODUCT' THEN(
    SELECT
        m_products_t.concatenated_product
    FROM
        m_products_t
    WHERE
        m_products_t.product_id = f_journal_entry_lines_t.reference_id
    LIMIT 1
) WHEN f_journal_entry_lines_t.reference_source = 'EMPLOYEE' THEN(
    SELECT
        hr_employee_t.first_name
    FROM
        hr_employee_t
    WHERE
        hr_employee_t.employee_id = f_journal_entry_lines_t.reference_id
    LIMIT 1
) WHEN f_journal_entry_lines_t.reference_source='MACHINE' THEN (
	SELECT
    	w_machine_hdr_t.machine_name
    FROM
    	w_machine_hdr_t
    WHERE
    	w_machine_hdr_t.machine_hdr_id = f_journal_entry_lines_t.reference_id
    LIMIT 1
) WHEN f_journal_entry_lines_t.reference_source='' AND f_journal_entry_lines_t.reference_id=0 THEN (
	SELECT
    	f_account_structure_t.account_name
    FROM
    	f_account_structure_t
    WHERE
    	f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
    LIMIT 1
)ELSE ''
END AS name,
(
    SELECT
        w_materialreceive_line_t.batchnumber
    FROM
        w_materialreceive_line_t
    WHERE
        w_materialreceive_line_t.w_materialreceive_hdr_id = w_materialreceive_hdr_t.w_materialreceive_hdr_id AND w_materialreceive_line_t.product_id = f_journal_entry_lines_t.reference_id
    LIMIT 1
) AS batch_number,
(
    SELECT
        w_materialreceive_line_t.receiveqty
    FROM
        w_materialreceive_line_t
    WHERE
        w_materialreceive_line_t.w_materialreceive_hdr_id = w_materialreceive_hdr_t.w_materialreceive_hdr_id AND w_materialreceive_line_t.product_id = f_journal_entry_lines_t.reference_id
    LIMIT 1
) AS qty,
f_journal_entry_lines_t.reference_source,
f_journal_entry_lines_t.debit_amount,
f_journal_entry_lines_t.credit_amount
FROM
    f_journal_entry_t
JOIN f_journal_entry_lines_t ON f_journal_entry_lines_t.journal_entry_id = f_journal_entry_t.journal_entry_id
LEFT JOIN w_materialreceive_hdr_t ON w_materialreceive_hdr_t.w_jobs_hdr_id = f_journal_entry_t.journal_reference
WHERE
    f_journal_entry_t.journal_name LIKE '%JOB%' AND f_journal_entry_t.journal_date BETWEEN ? AND ?
) v1";
      
    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
			  
    }
    
	
	
    public function bankstmtuploadrptindex()
    {
       return view('productdetailreport.bankstmtuploadrpt', $this->data);    
    }
	
	
     public function getbankstmtuploadrpt(Request $request){

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
        
         $SQL = "select * from (SELECT
    f_bankstmtupload_t.*,
    (case when f_bankstmtupload_t.status = 1 then
    'BRS Done' else 'Yet to BRS' end)as brs_status,
    f_bank_account_hdr_t.bank_name,
    f_bank_account_lines_t.account_number
FROM
    f_bankstmtupload_t
JOIN f_bank_account_hdr_t ON(
        f_bank_account_hdr_t.bank_account_hdr_id = f_bankstmtupload_t.bank_id
    )
LEFT JOIN f_bank_account_lines_t ON(
        f_bank_account_lines_t.bank_account_line_id = f_bankstmtupload_t.account_no
    ) where 1=1 and f_bankstmtupload_t.value_date BETWEEN ? and ?)v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
		 
    }    

	
}
