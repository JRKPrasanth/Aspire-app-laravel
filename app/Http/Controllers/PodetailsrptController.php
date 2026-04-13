<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Podetailsrpt;
use Illuminate\Http\Request;

class PodetailsrptController extends Controller
{
     public $module="podetailsrpt";
	public function __construct()
	{
		$this->data=array();
                 $this->data['urlmenu']=$this->indexs(); 
		$this->pageModule="podetailsrpt";
                $this->model=new Podetailsrpt();
		$this->model=new Podetailsrpt;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
	}
    public function index()
    {
		if(isset($_GET['supplier_id']))
		{
		  $where=" and p_po_hdr_t.supplier_id='".$_GET['supplier_id']."'";
		}
		else {
		  $where='';
		}

     $this->data['supplieropt']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
        $this->data['pageMethod']='podetailsrpt';
		  $org=\Session::get('organization');
        $loc="1";
        $compy=\Session::get('companyid');

               $SQL = "SELECT
                p_po_hdr_t.po_hdr_id,
                p_po_hdr_t.po_status,
                p_po_hdr_t.po_date,
                p_po_hdr_t.delivery_date,
                p_po_hdr_t.po_number,
                m_supplier_t.supplier_name,
                p_po_hdr_t.po_grand_total
                FROM p_po_hdr_t
                left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id)
                where 1=1 $where and p_po_hdr_t.company_id=$compy and p_po_hdr_t.location_id=$loc ORDER BY p_po_hdr_t.po_hdr_id desc";
               $result = \DB::select( $SQL );
		 $this->data['result']=json_encode($result);
     if(isset($_GET['supplier_id']))
     {
       return view('podetailsrpt.report_table',$this->data);
     }else {
		  $this->data['chartresult']="";
		 $this->data['barchart']="";
		 $this->data['drilldown']="";
		 $this->data['statusdrilldown']="";
            
                 
       return view('podetailsrpt.table',$this->data);
     }
    }
	
	
public function PodetailsreportData(){
        $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
if(isset($_GET)){	
	$date=" and p_po_hdr_t.po_date between '".$_GET['from_date']."' and '".$_GET['to_date']."' ";
}
		 $this->data['from']=$_GET['from_date'];
		 $this->data['to']=$_GET['to_date'];
               $SQL = "SELECT
                p_po_hdr_t.po_hdr_id,
                p_po_hdr_t.po_status,
                p_po_hdr_t.po_date,
                p_po_hdr_t.delivery_date,
                p_po_hdr_t.po_number,
                m_supplier_t.supplier_name,
                p_po_hdr_t.po_grand_total
                FROM p_po_hdr_t
                left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id)
                where 1=1  $date and p_po_hdr_t.company_id=$compy and p_po_hdr_t.location_id=$loc ORDER BY p_po_hdr_t.po_hdr_id desc";
               $result = \DB::select( $SQL );
		 $this->data['result']=json_encode($result);
     $SQL1 = \DB::select("SELECT   p_po_hdr_t.po_status as name,count(*) as y,p_po_hdr_t.po_status as drilldown FROM p_po_hdr_t where 1=1  $date and p_po_hdr_t.company_id=$compy and p_po_hdr_t.location_id=$loc group by  p_po_hdr_t.po_status ORDER BY p_po_hdr_t.po_hdr_id desc");
	$sql2=\DB::select("SELECT m_supplier_t.supplier_name as name,count(*) as y,m_supplier_t.supplier_name as drilldown FROM p_po_hdr_t left join  m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id) where 1=1  $date and p_po_hdr_t.company_id=$compy and p_po_hdr_t.location_id=$loc group by  p_po_hdr_t.supplier_id ORDER BY p_po_hdr_t.po_hdr_id desc");
		 $data=array();
		 foreach($sql2 as $k=>$v)
		 {
			 
			 
			$sql3=\DB::select("SELECT p_po_hdr_t.po_number,p_po_hdr_t.po_grand_total,m_supplier_t.supplier_name FROM p_po_hdr_t left join  m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id) where 1=1  $date and p_po_hdr_t.company_id=$compy and p_po_hdr_t.location_id=$loc and m_supplier_t.supplier_name='".$v->name."' ORDER BY p_po_hdr_t.po_hdr_id desc"); 
			 $drilldown_data=array();
			 foreach($sql3 as $k1=>$v1)
			 {
				
				    $drilldown_data[$k1][0] = $v1->po_number;
                    $drilldown_data[$k1][1] = floatval($v1->po_grand_total);
			 }
			 $data[$k]=(object)array('name' => $v->name,'id'=>$v->name,'data'=>$drilldown_data);
			
			 
			 
		 }
		
		
		 $data1=array();
		 foreach($SQL1 as $key=>$value)
		 {
			 
			 
			$postatussql1=\DB::select("SELECT p_po_hdr_t.po_number,p_po_hdr_t.po_grand_total, p_po_hdr_t.po_status FROM p_po_hdr_t where 1=1  $date and p_po_hdr_t.company_id=$compy and p_po_hdr_t.location_id=$loc and  p_po_hdr_t.po_status='".$value->name."' ORDER BY p_po_hdr_t.po_hdr_id desc"); 
			 $drilldown_data1=array();
			 foreach($postatussql1 as $key1=>$value1)
			 {
				
				    $drilldown_data1[$key1][0] = $value1->po_number;
                    $drilldown_data1[$key1][1] = floatval($value1->po_grand_total);
			 }
			 $data1[$key]=(object)array('name' => $value->name,'id'=>$value->name,'data'=>$drilldown_data1);
			
			 
			 
		 }
		 
		  $this->data['chartresult']=json_encode($SQL1);
		 $this->data['barchart']=json_encode($sql2);
		 $this->data['drilldown']=json_encode($data);
		 $this->data['statusdrilldown']=json_encode($data1);
		
       return view('podetailsrpt.table',$this->data);
    }


	public function getpodetailsData(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
     SELECT
                p_po_hdr_t.po_hdr_id,
                p_po_hdr_t.po_status,
                p_po_hdr_t.po_date,
                p_po_hdr_t.delivery_date,
                p_po_hdr_t.po_number,
                m_supplier_t.supplier_name,
                p_po_hdr_t.po_grand_total
                FROM p_po_hdr_t
                left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id)
                where 1=1  and p_po_hdr_t.po_date >= ? AND p_po_hdr_t.po_date <= ?) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}	
	
	
	/*deepika purpose:po detail report index*/
	public function index1(){
		if($this->data['pageMethod']=="pinvdatasrpt"){
	return view('podetailsrpt.poinvoicerpt',$this->data);	
		}else if($this->data['pageMethod']=="pinvdataswithbnorpt"){
	return view('podetailsrpt.poinvoicebnorpt',$this->data);		    
		}else{
		return view('podetailsrpt.podatasrpt',$this->data);		
		}
	}
	

		public function getpodatasrpt(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
SELECT
    p_po_lines_t.*,
    ROUND(
        p_po_lines_t.qty * p_po_lines_t.unit_price,
        2
    ) AS accessable_value,
    CONCAT(
        m_products_t.product_code,
        '-',
        concatenated_product
    ) AS prdname,
    p_po_hdr_t.po_number,
    p_po_hdr_t.po_date,
    p_po_hdr_t.po_status,
    p_po_hdr_t.po_grand_total,
    p_po_hdr_t.po_tax_total,
    p_po_hdr_t.po_pricelist_id,
    p_po_hdr_t.advance_amount,
    p_po_hdr_t.balance_amount,
    p_po_hdr_t.round_off,
    p_po_hdr_t.source,
    p_po_hdr_t.reference_number,
    p_po_hdr_t.supplier_reference_no,
    CONCAT(
        m_supplier_t.supplier_number,
        '-',
        m_supplier_t.supplier_name
    ) AS supname,
    i_pricelist_hdr_t.pricelist_name,
    m_uom_codes_t.uom_code,
    m_tax_group_t.tax_group_name,
    f_gst_code_hdr_t.classification_code
FROM
    `p_po_lines_t`
LEFT JOIN p_po_hdr_t ON(
        p_po_hdr_t.po_hdr_id = p_po_lines_t.po_hdr_id
    )
LEFT JOIN m_supplier_t ON
    (
        m_supplier_t.supplier_id = p_po_hdr_t.supplier_id
    )
LEFT JOIN i_pricelist_hdr_t ON
    (
        i_pricelist_hdr_t.pricelist_hdr_id = p_po_hdr_t.po_pricelist_id
    )
LEFT JOIN m_products_t ON(
        m_products_t.product_id = p_po_lines_t.product_id
    )
LEFT JOIN m_uom_codes_t ON
    (
        m_uom_codes_t.uom_code_id = p_po_lines_t.uom_code_id
    )
LEFT JOIN m_tax_group_t ON
    (
        m_tax_group_t.tax_group_id = p_po_lines_t.tax_group_id
    )
LEFT JOIN f_gst_code_hdr_t ON
    (
        f_gst_code_hdr_t.gst_code_hdr_id = p_po_lines_t.hsn_code
    )
WHERE
    1 = 1 AND p_po_hdr_t.po_date >= ? AND p_po_hdr_t.po_date <= ? ) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
	
	
	public function getpinvdatasrpt(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
 SELECT
    p_po_invoice_lines_t.*,
    m_products_t.concatenated_product AS prdname,
    m_products_t.tax_credit,
    p_po_invoice_hdr_t.transport_charges,
    p_po_invoice_hdr_t.bill_number,
    p_po_invoice_hdr_t.invoice_date,
    p_po_invoice_hdr_t.po_invoice_status,
    p_po_invoice_hdr_t.invoice_grand_total,
    p_po_invoice_hdr_t.invoice_tax_total,
    p_po_invoice_hdr_t.invoice_pricelist_id,
    p_po_invoice_hdr_t.supplier_invoice_no,
    p_po_invoice_hdr_t.supplier_invoice_date,
    p_po_invoice_hdr_t.dc_number,
    p_po_invoice_hdr_t.dc_date,
    p_po_invoice_hdr_t.paid_amount,
    p_po_invoice_hdr_t.balance_amount,
    p_po_invoice_hdr_t.debit_note,
    p_po_invoice_hdr_t.credit_note,
    p_po_invoice_hdr_t.credit_note_balance,
    p_po_invoice_hdr_t.debit_note_balance,
    m_supplier_t.supplier_name AS supname,
    i_pricelist_hdr_t.pricelist_name,
    m_uom_codes_t.uom_code,
    m_tax_group_t.tax_group_name,
    f_gst_code_hdr_t.classification_code,
    p_po_hdr_t.po_number,
    p_po_hdr_t.po_date,
    p_po_lines_t.promised_date as date,
    p_grn_hdr_t.grn_number,
    p_grn_hdr_t.grn_date
FROM
    `p_po_invoice_lines_t`
LEFT JOIN p_po_invoice_hdr_t ON p_po_invoice_hdr_t.po_invoice_id = p_po_invoice_lines_t.po_invoice_id
LEFT JOIN i_pricelist_hdr_t ON
    (
        i_pricelist_hdr_t.pricelist_hdr_id = p_po_invoice_hdr_t.invoice_pricelist_id
    )
LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id
LEFT JOIN m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = p_po_invoice_lines_t.uom_code_id
LEFT JOIN m_tax_group_t ON
    (
        m_tax_group_t.tax_group_id = p_po_invoice_lines_t.tax_group_id
    )
LEFT JOIN f_gst_code_hdr_t ON f_gst_code_hdr_t.gst_code_hdr_id = p_po_invoice_lines_t.hsn_code
LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number
LEFT JOIN p_po_lines_t ON p_po_lines_t.po_hdr_id = p_po_invoice_hdr_t.po_number AND p_po_lines_t.product_id = p_po_invoice_lines_t.product_id
LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number
WHERE
    1 = 1 AND p_po_invoice_hdr_t.invoice_date >= ? AND p_po_invoice_hdr_t.invoice_date <= ?) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
	
	
	public function getpinvdataswithbnorpt(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
 SELECT
    p_po_invoice_lines_t.*,
    CONCAT(
        m_products_t.product_code,
        '-',
        concatenated_product
    ) AS prdname,
    p_po_invoice_hdr_t.bill_number,
    p_po_invoice_hdr_t.invoice_date,
    p_po_invoice_hdr_t.po_invoice_status,
    p_po_invoice_hdr_t.invoice_grand_total,
    p_po_invoice_hdr_t.invoice_tax_total,
    p_po_invoice_hdr_t.invoice_pricelist_id,
    p_po_invoice_hdr_t.supplier_invoice_no,
    p_po_invoice_hdr_t.supplier_invoice_date,
    p_po_invoice_hdr_t.dc_number,
    p_po_invoice_hdr_t.dc_date,
    p_po_invoice_hdr_t.paid_amount,
    p_po_invoice_hdr_t.balance_amount,
    p_po_invoice_hdr_t.debit_note,
    p_po_invoice_hdr_t.credit_note,
    p_po_invoice_hdr_t.credit_note_balance,
    p_po_invoice_hdr_t.debit_note_balance,
    CONCAT(
        m_supplier_t.supplier_number,
        '-',
        m_supplier_t.supplier_name
    ) AS supname,
    i_pricelist_hdr_t.pricelist_name,
    m_uom_codes_t.uom_code,
    m_tax_group_t.tax_group_name,
    f_gst_code_hdr_t.classification_code,
    p_po_hdr_t.po_number,
    p_po_hdr_t.po_date,
    p_grn_hdr_t.grn_number,
    i_qoh_detail_t.batch_number
FROM
    `p_po_invoice_lines_t`
LEFT JOIN p_po_invoice_hdr_t ON p_po_invoice_hdr_t.po_invoice_id = p_po_invoice_lines_t.po_invoice_id
LEFT JOIN i_pricelist_hdr_t ON
    (
        i_pricelist_hdr_t.pricelist_hdr_id = p_po_invoice_hdr_t.invoice_pricelist_id
    )
LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id
LEFT JOIN m_products_t ON m_products_t.product_id = p_po_invoice_lines_t.product_id
LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = p_po_invoice_lines_t.uom_code_id
LEFT JOIN m_tax_group_t ON
    (
        m_tax_group_t.tax_group_id = p_po_invoice_lines_t.tax_group_id
    )
LEFT JOIN f_gst_code_hdr_t ON f_gst_code_hdr_t.gst_code_hdr_id = p_po_invoice_lines_t.hsn_code
LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.po_number
LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_po_invoice_hdr_t.grn_number
LEFT JOIN i_qoh_detail_t ON p_po_invoice_hdr_t.grn_number = i_qoh_detail_t.grn_id AND p_po_invoice_lines_t.product_id = i_qoh_detail_t.product_id 
WHERE
    1 = 1 AND i_qoh_detail_t.qoh_source = 'PURCHASE_STOREMOVE' AND p_po_invoice_hdr_t.invoice_date >= ? AND p_po_invoice_hdr_t.invoice_date <= ? GROUP BY batch_number) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
	
}
