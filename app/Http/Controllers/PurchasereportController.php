<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Purchaseorderlines;
use Yajra\DataTables\DataTables;

class PurchasereportController extends Controller
{
	public function __construct()
	{
		$this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
	}
	
	
public function index($id=null){	
$poorder=\DB::select("SELECT
    p_po_hdr_t.po_hdr_id,
    p_po_hdr_t.po_number,
    p_po_lines_t.product_id,
    p_po_lines_t.qty,
    p_po_lines_t.pending_qty,
    p_po_lines_t.received_qty,
    CONCAT( m_products_t.product_code,' ',m_products_t.concatenated_product) as product_name
FROM
    p_po_hdr_t
LEFT JOIN p_po_lines_t ON p_po_hdr_t.po_hdr_id = p_po_lines_t.po_hdr_id
LEFT JOIN m_products_t ON p_po_lines_t.product_id = m_products_t.product_id
WHERE
    p_po_hdr_t.po_hdr_id = '$id'");
$grn=\DB::select('SELECT
    po.po_hdr_id,
    po.po_number,
    grn.grn_id,
    grn.grn_number,
    grnl.product_id,
    grnl.receive_qty,
    m_products_t.concatenated_product
    
FROM
    p_po_hdr_t po
LEFT JOIN p_grn_hdr_t grn ON
    (grn.po_number = po.po_hdr_id)
LEFT JOIN p_grn_lines_t grnl ON
    (grn.grn_id = grnl.grn_id)
LEFT JOIN m_products_t ON m_products_t.product_id = grnl.product_id    
WHERE
    po.po_type != "LABOUR" AND po.po_hdr_id = "'.$id.'"');

	//dd(array_search('10',array_column($grn,'product_id')));




$quality=\DB::select('SELECT
    po.po_hdr_id,
     po.po_number,
    qc.qc_header_id,
    qc.qc_number,
    p_grn_hdr_t.grn_number,
    qc.grn_number as grn_id,
    qcl.accept_qty,
    qcl.reject_qty,
    qcl.total_box_qty,
    qcl.approval_status,
    qc.qc_status,
	qcl.product_id
FROM
    p_po_hdr_t po
LEFT JOIN p_qc_header_t qc ON
    (qc.po_number = po.po_hdr_id)
LEFT JOIN p_qc_lines_t qcl ON
    (
        qc.qc_header_id = qcl.qc_header_id
    )
LEFT JOIN p_quality_spec_trx_lines_t trx ON
    trx.reference_source_line_id = qcl.qc_line_id
left join p_grn_hdr_t on p_grn_hdr_t.grn_id=qc.grn_number    
    
    
WHERE
    po.po_type != "LABOUR" AND po.po_hdr_id = "'.$id.'"');
	
	
$invoice=\DB::select('SELECT
    po.po_hdr_id,
     po.po_number,
    inv.po_invoice_id,
    inv.bill_number,
    invl.product_id,
    invl.qty,
   
    grn.grn_number,
    grn.grn_id

FROM
    p_po_hdr_t po
LEFT JOIN p_po_invoice_hdr_t inv ON
    inv.po_number = po.po_hdr_id
LEFT JOIN p_po_invoice_lines_t invl ON
    
        invl.po_invoice_id = inv.po_invoice_id
    
LEFT JOIN p_grn_hdr_t grn ON
    grn.grn_id = inv.grn_number
  
    
    
WHERE
    po.po_type != "LABOUR" AND po.po_hdr_id = "'.$id.'"');
	
	
	
	
$return_invoice=\DB::select("SELECT
    p_return_header_t.return_header_id,
    p_return_header_t.return_invoice_number,
    p_return_lines_t.product_id,
     p_return_lines_t.reject_qty,
     p_po_invoice_hdr_t.bill_number,
     p_po_invoice_hdr_t.po_invoice_id,

    p_po_hdr_t.po_hdr_id,
    p_po_hdr_t.po_number
FROM
    p_po_hdr_t

LEFT JOIN `p_return_header_t` ON p_po_hdr_t.po_hdr_id = p_return_header_t.po_number
LEFT JOIN p_return_lines_t ON p_return_header_t.return_header_id = p_return_lines_t.return_header_id
LEFT JOIN `p_po_invoice_hdr_t` ON p_po_invoice_hdr_t.po_invoice_id = p_return_header_t.invoice_number
WHERE
    p_po_hdr_t.po_status != 'LABOUR' AND p_po_hdr_t.po_hdr_id = '".$id."'");	


	$data=array();
	foreach($poorder as $key=>$val)
	{
		
		$id=$val->product_id;
		$data['PO_QTY'][$id][]=$val->qty;
		$data['Pending_QTY'][$id][]=$val->pending_qty;
		
			$array_key = array_column($grn,'product_id');
		
			$array_key_val=array_keys($array_key,$id);

		$i=0;

			foreach($array_key_val as $array_key)
			{
				$grn_id=$grn[$array_key]->grn_id;
				
				$grn_number=$grn[$array_key]->grn_number;
				$receive_qty=$grn[$array_key]->receive_qty;
			
			$html="<a href='".url("grn_view/$grn_id/2")."' target='_blank'> $grn_number </a> => $receive_qty";	
			
			$data['GRN_QTY'][$id][$i]=$html;
			$data['receive_QTY'][$id][$i]=$receive_qty;
				$i++;
			}
		//Quality
			$array_key = array_column($quality,'product_id');
		
			$array_key_val=array_keys($array_key,$id);
		$i=0;
			foreach($array_key_val as $array_key)
			{
				
				$grn_id=$quality[$array_key]->grn_id;
				
				$grn_number=$quality[$array_key]->grn_number;
				$receive_qty=$quality[$array_key]->total_box_qty;
				$qc_number=$quality[$array_key]->qc_number;
				$qc_header_id=$quality[$array_key]->qc_header_id;
			
			$html="<a href='".url("grn_view/$grn_id/2")."' target='_blank'> $grn_number </a> => <a href='".url("quality_view/$qc_header_id/2")."' target='_blank'> $qc_number </a> =>$receive_qty";	
			
			$data['QC_QTY'][$id][$i]=$html;
				$i++;
			}
		//Invoice
		$array_key = array_column($invoice,'product_id');
		
			$array_key_val=array_keys($array_key,$id);
		$i=0;
			foreach($array_key_val as $array_key)
			{
				
				$grn_id=$invoice[$array_key]->grn_id;
				
				$grn_number=$invoice[$array_key]->grn_number;
				$receive_qty=$invoice[$array_key]->qty;
				$bill_number=$invoice[$array_key]->bill_number;
				$po_invoice_id=$invoice[$array_key]->po_invoice_id;
			
			$html="<a href='".url("grn_view/$grn_id/2")."' target='_blank'> $grn_number </a> => <a href='".url("invoice_view/$po_invoice_id")."' target='_blank'> $bill_number </a> =>$receive_qty";	
			
			$data['Invoice_QTY'][$id][$i]=$html;
				$i++;
			}
		
		// Return
		//Invoice
		$array_key = array_column($return_invoice,'product_id');
		
			$array_key_val=array_keys($array_key,$id);
		$i=0;
			foreach($array_key_val as $array_key)
			{
				
				$grn_id='';
				
				$invoice_number=$return_invoice[$array_key]->bill_number;
				$invoice_id=$return_invoice[$array_key]->po_invoice_id;
				$receive_qty=$return_invoice[$array_key]->reject_qty;
				$return_invoice_number=$return_invoice[$array_key]->return_invoice_number;
				$return_header_id=$return_invoice[$array_key]->return_header_id;
			
			$html="<a href='".url("invoice_view/$invoice_id")."' target='_blank'> $invoice_number </a> => <a href='".url("purchasereturn_view/$return_header_id")."' target='_blank'> $return_invoice_number </a> =>$receive_qty";	
			
			$data['Return_QTY'][$id][$i]=$html;
				$i++;
			}
    }

$this->data['data']=$data;	
$this->data['poorder']=$poorder;

return view('purchasereport.view',$this->data);
}

	
	public function pendingindex()
{
		
	$comp=\Session::get('companyid');	

	return view('purchasereport.table',$this->data);		
}

	
  	public function getpopendingqty(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
       SELECT p_po_hdr_t.po_hdr_id,
              p_po_hdr_t.po_number,
              p_po_hdr_t.supplier_id,
              p_po_hdr_t.po_type,
              p_po_hdr_t.po_status,
              m_supplier_t.supplier_name,
              p_po_hdr_t.po_date,
              p_po_lines_t.qty,
              p_po_lines_t.pending_qty,
              p_po_lines_t.promised_alternate_date,
              p_po_lines_t.product_id,
              m_products_t.concatenated_product,
              m_product_groups_t.group_name
        FROM p_po_hdr_t
        left join p_po_lines_t on(p_po_lines_t.po_hdr_id=p_po_hdr_t.po_hdr_id)
        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id)
        left join m_products_t on(p_po_lines_t.product_id=m_products_t.product_id)
        LEFT JOIN m_product_groups_t ON (m_products_t.product_group_id = m_product_groups_t.product_group_id)
       where 1=1 and (p_po_hdr_t.po_status='APPROVED' or p_po_hdr_t.po_status='INITIATED') and p_po_lines_t.pending_qty>0 and p_po_hdr_t.company_id='1' and p_po_hdr_t.po_date >= ? AND p_po_hdr_t.po_date <= ?
    ) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
	
	
public function Poinvoicepaymentreport()
{
$comp=\Session::get('companyid');	
$SQL =\DB::select("SELECT `po_invoice_id`,`bill_number`,`invoice_date`,`supplier_id`,concat(m_supplier_t.supplier_number,'-',m_supplier_t.supplier_name)as sup_name,`paid_amount`,`balance_amount`,`due_date` FROM `p_po_invoice_hdr_t` left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id) WHERE `po_invoice_status`='APPROVED' AND `due_date` < CURDATE() and p_po_invoice_hdr_t.company_id=".$comp." ORDER BY p_po_invoice_hdr_t.po_invoice_id ASC");
$this->data['result']=json_encode($SQL);	
return view('purchasereport.table',$this->data);		
}
	public function purchaseapprovedreport()
{
		return view('purchasereport.approvetable');
	}
	public function soorderapprovegriddata()
{
		
		$wh='';
		if($_GET['_search']=='true')
		{
		$wh=$this->jqgridsearch('p_po_hdr_t',$_GET['filters']);
		}
		$com=\Session::get('companyid');
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(po_hdr_id) AS count  FROM `p_po_hdr_t`  left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id)  WHERE 1=1   $wh and p_po_hdr_t.company_id=$com and  po_status='APPROVED'");
		$count = $result[0]->count;
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
		$SQL = "SELECT p_po_hdr_t.po_hdr_id,p_po_hdr_t.po_number,p_po_hdr_t.supplier_id,concat(m_supplier_t.supplier_number,'-',m_supplier_t.supplier_name)as sup_name,p_po_hdr_t.po_date,p_po_hdr_t.po_status FROM `p_po_hdr_t`  left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id)  WHERE 1=1 $wh and p_po_hdr_t.company_id=$com and po_status='APPROVED'  ORDER BY $sidx $sord  LIMIT $start , $limit";

		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	
}

	public function productwisepoindex(){	

	return view('purchasereport.productwisepo',$this->data);		
	}

	
 	public function getproductwisepo(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


    $SQL = "SELECT * from (
    SELECT p_po_hdr_t.po_hdr_id,
              p_po_hdr_t.po_number,
              p_po_hdr_t.po_grand_total,
              p_po_hdr_t.supplier_id,
              m_supplier_t.supplier_name as supplier_name,
              p_po_hdr_t.po_date,
              p_po_hdr_t.po_status,
              p_po_lines_t.qty,
              p_po_lines_t.line_total,
              p_po_lines_t.product_id,
              p_po_lines_t.unit_price,
              m_products_t.concatenated_product,
              m_product_groups_t.group_name,
              p_grn_hdr_t.dc_number,
              m_uom_codes_t.uom_code,
              p_grn_hdr_t.dc_date,
              p_po_invoice_hdr_t.bill_number,
              p_po_invoice_hdr_t.supplier_invoice_date
              FROM p_po_hdr_t
              left join p_po_lines_t on(p_po_lines_t.po_hdr_id=p_po_hdr_t.po_hdr_id)
              left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.supplier_id)
              left join m_products_t on(p_po_lines_t.product_id=m_products_t.product_id)
              left join m_uom_codes_t on(m_uom_codes_t.uom_code_id=m_products_t.primary_uom_id)
              left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)
              left join p_grn_hdr_t on(p_grn_hdr_t.po_number=p_po_hdr_t.po_hdr_id)
              left join p_po_invoice_hdr_t on(p_po_invoice_hdr_t.po_number=p_po_hdr_t.po_hdr_id)
              where 1=1 and p_po_hdr_t.company_id='1' and p_po_hdr_t.po_date >= ? AND p_po_hdr_t.po_date <= ?) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
}
	
}
