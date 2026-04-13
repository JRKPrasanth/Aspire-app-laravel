<?php

namespace App\Http\Controllers;

use App\purchasereturn;
use App\Purchasereturnlines;
use Illuminate\Http\Request,DB;
use App\Http\Controllers\Controller;
use File;
use Config;

class PurchasereturnController extends Controller
{
   public function __construct()
    {
        $this->data=array();
        $this->model=new purchasereturn;
        $this->submodel=new Purchasereturnlines;
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='purchasereturn';
        $this->table="p_return_header_t";
        $this->subtable="p_return_lines_t";
        $this->middleware('auth');
        $this->data['pageMethod']=="purchasereturnapproval" ;

        $this->data=array(
                    'pageModule'=> 'purchasereturn',
                    'pageUrl'	=>  url('purchasereturn'),
                     'pageMethod'=>$this->data['pageMethod']

                  );
        $this->data['urlmenu']=$this->indexs();
     if($this->data['pageMethod']=="purchasereturnapproval")
           {
              // $this->data['pageMethod']="poinvoiceapproval";
               $this->data['status']="INITIATED";
           }
        else
          {
               $this->data['status']="";
          }
    }
    /*Karthigaa Purpose For :Index Function to Call Table Blade*/
    public function index()
    {
        $this->data['suppliername']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
        $this->data['invoiceno']=$this->jqgridselect('p_po_invoice_hdr_t','po_invoice_id','bill_number');
        $this->data['pageMethod']=\Request::route()->getName();
        return view("purchasereturn.table",$this->data);
    }
 /* Karthigaa purpose for Display Purchase Return Data in JQgrid function */
    public function purchasereturnData(){
        $wh='';
             if($_GET['status']!=''){
          $wh=" and  v1.p_return_status='".$_GET['status']."'";
      }
        if($_GET['_search']=='true'){
                        $wh=$this->jqgridsearchnotab('v1',$_GET['filters']);
        }

        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $groupname=\Session::get('groupname');
        if($groupname=='Superadmin' || $groupname=='Admin'){
        $wh.=' and v1.company_id='.$compy;
        }else{
            $wh.=' and v1.company_id='.$compy.' and v1.location_id='.$loc;
        }


        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
       
        $result = \DB::select("select * from (SELECT
                        p_return_header_t.return_header_id,
                        p_return_header_t.return_date,
                        p_return_header_t.return_invoice_number,
                        p_return_header_t.p_return_status,
                        p_grn_hdr_t.grn_number,
                        p_grn_hdr_t.dc_number,
                        p_po_hdr_t.po_number,
                        p_po_hdr_t.po_date,
                        m_supplier_t.supplier_name,
                        m_supplier_t.supplier_id,
                        p_return_header_t.invoice_number,
                        p_return_header_t.company_id,
                        p_return_header_t.location_id
                        FROM `p_return_header_t`
                        left join p_po_hdr_t on(
                        p_po_hdr_t.po_hdr_id=p_return_header_t.`po_number`)
                        left join p_po_invoice_hdr_t on(
                        p_po_invoice_hdr_t.po_invoice_id=p_return_header_t.`invoice_number`)
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_return_header_t.`supplier_id`) left join p_grn_hdr_t on p_grn_hdr_t.grn_id=p_return_header_t.grn_number  where 1=1 ) v1 where 1=1 $wh");
       
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
                $SQL = "select * from (SELECT
                        p_return_header_t.return_header_id,
                        p_return_header_t.return_date,
                        p_return_header_t.return_invoice_number,
                        p_return_header_t.p_return_status,
                        p_grn_hdr_t.grn_number,
                        p_grn_hdr_t.dc_number,
                        p_po_hdr_t.po_number,
                        p_po_hdr_t.po_date,
                        m_supplier_t.supplier_name,
                        m_supplier_t.supplier_id,
                        p_return_header_t.invoice_number,
                        p_return_header_t.company_id,
                        p_return_header_t.location_id
                        FROM `p_return_header_t`
                        left join p_po_hdr_t on(
                        p_po_hdr_t.po_hdr_id=p_return_header_t.`po_number`)
                        left join p_po_invoice_hdr_t on(
                        p_po_invoice_hdr_t.po_invoice_id=p_return_header_t.`invoice_number`)
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_return_header_t.`supplier_id`) left join p_grn_hdr_t on p_grn_hdr_t.grn_id=p_return_header_t.grn_number  where 1=1 ) v1 where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
 $download_SQL = "select * from (SELECT
                        p_return_header_t.return_header_id,
                        p_return_header_t.return_date,
                        p_return_header_t.return_invoice_number,
                        p_return_header_t.p_return_status,
                        p_grn_hdr_t.grn_number,
                        p_grn_hdr_t.dc_number,
                        p_po_hdr_t.po_number,
                        p_po_hdr_t.po_date,
                        m_supplier_t.supplier_name,
                        m_supplier_t.supplier_id,
                         p_return_header_t.invoice_number,
                        p_return_header_t.company_id,
                        p_return_header_t.location_id
                        FROM `p_return_header_t`
                        left join p_po_hdr_t on(
                        p_po_hdr_t.po_hdr_id=p_return_header_t.`po_number`)
                        left join p_po_invoice_hdr_t on(
                        p_po_invoice_hdr_t.po_invoice_id=p_return_header_t.`invoice_number`)
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_return_header_t.`supplier_id`) left join p_grn_hdr_t on p_grn_hdr_t.grn_id=p_return_header_t.grn_number  where 1=1 )v1 where 1=1 $wh ORDER BY $sidx $sord";
                        $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }



        $result = \DB::select( $SQL );
          // dd($result);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }
      /*Karthigaa Purpose For :Index Function to Call Invoice Table Blade*/
    public function invoicetable()
    {
        $this->data['suppliername']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name');
        return view("purchasereturn.qc_table",$this->data);
    }
 /* Karthigaa purpose for Display Invoice Data in JQgrid function */
       public function invoiceData(){

        $wh='';
        if($_GET['_search']=='true'){
        $wh =$this->jqgridsearchnotab('v1',$_GET['filters']);
        }

        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $groupname=\Session::get('groupname');
        if($groupname=='Superadmin' || $groupname=='Admin'){
        $wh.='and  v1.company_id='.$compy;
        }else{
            $wh.='and  v1.company_id='.$compy.' and v1.location_id='.$loc;
        }


        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
        $result = \DB::select("select * from (SELECT p_qc_header_t.qc_header_id ,p_po_invoice_hdr_t.po_invoice_id,
                        p_po_invoice_hdr_t.bill_number AS bill_number,p_grn_hdr_t.grn_number AS grn_number,
                        p_grn_hdr_t.dc_number AS dc_number,p_po_hdr_t.po_number AS po_number,
                        p_po_hdr_t.po_date AS po_date,p_qc_header_t.qc_number AS qc_number,
                        m_supplier_t.supplier_name,m_subcontract_supplier_t.subcontract_name,
                        p_quality_spec_trx_lines_t.box_product_qty,p_quality_spec_trx_lines_t.reference_source_line_id,
                        p_qc_lines_t.reject_qty,p_quality_spec_trx_lines_t.quality_status,p_grn_hdr_t.company_id,p_grn_hdr_t.location_id
                        FROM `p_grn_hdr_t`
                        LEFT JOIN p_qc_header_t ON(p_qc_header_t.grn_number = p_grn_hdr_t.grn_id)
                        LEFT JOIN p_qc_lines_t ON(p_qc_lines_t.qc_header_id = p_qc_header_t.qc_header_id)
                        LEFT JOIN p_quality_spec_trx_lines_t ON(p_quality_spec_trx_lines_t.reference_source_line_id = p_qc_lines_t.qc_line_id)
                        LEFT JOIN p_po_hdr_t ON(p_po_hdr_t.po_hdr_id = p_qc_header_t.`po_number`)
                        LEFT JOIN m_subcontract_supplier_t ON(m_subcontract_supplier_t.subcontract_supplier_id = p_qc_header_t.`subcontract_supplier_id`)
                        LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_qc_header_t.`supplier_id`)
                        LEFT JOIN p_po_invoice_hdr_t ON(p_po_invoice_hdr_t.grn_number = p_grn_hdr_t.grn_id)
                        WHERE 1 = 1 AND p_grn_hdr_t.invoice_created = 'Yes' AND p_qc_header_t.return_status = 0 AND p_qc_lines_t.reject_qty != 0 AND p_po_invoice_hdr_t.po_invoice_status = 'APPROVED' AND p_quality_spec_trx_lines_t.quality_status='Rejected' )v1 where 1=1 $wh
                        GROUP BY v1.grn_number");

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
                $SQL = "select * from (SELECT p_qc_header_t.qc_header_id ,p_po_invoice_hdr_t.po_invoice_id,
                        p_po_invoice_hdr_t.bill_number AS bill_number,p_grn_hdr_t.grn_number AS grn_number,
                        p_grn_hdr_t.dc_number AS dc_number,p_po_hdr_t.po_number AS po_number,
                        p_po_hdr_t.po_date AS po_date,p_qc_header_t.qc_number AS qc_number,
                        m_supplier_t.supplier_name,m_subcontract_supplier_t.subcontract_name,
                        p_quality_spec_trx_lines_t.box_product_qty,p_quality_spec_trx_lines_t.reference_source_line_id,
                        p_qc_lines_t.reject_qty,p_quality_spec_trx_lines_t.quality_status,p_grn_hdr_t.company_id,p_grn_hdr_t.location_id
                        FROM `p_grn_hdr_t`
                        LEFT JOIN p_qc_header_t ON(p_qc_header_t.grn_number = p_grn_hdr_t.grn_id)
                        LEFT JOIN p_qc_lines_t ON(p_qc_lines_t.qc_header_id = p_qc_header_t.qc_header_id)
                        LEFT JOIN p_quality_spec_trx_lines_t ON(p_quality_spec_trx_lines_t.reference_source_line_id = p_qc_lines_t.qc_line_id)
                        LEFT JOIN p_po_hdr_t ON(p_po_hdr_t.po_hdr_id = p_qc_header_t.`po_number`)
                        LEFT JOIN m_subcontract_supplier_t ON(m_subcontract_supplier_t.subcontract_supplier_id = p_qc_header_t.`subcontract_supplier_id`)
                        LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_qc_header_t.`supplier_id`)
                        LEFT JOIN p_po_invoice_hdr_t ON(p_po_invoice_hdr_t.grn_number = p_grn_hdr_t.grn_id)
                        WHERE 1 = 1 AND p_grn_hdr_t.invoice_created = 'Yes' AND p_qc_header_t.return_status = 0 AND p_qc_lines_t.reject_qty != 0 AND p_po_invoice_hdr_t.po_invoice_status = 'APPROVED' AND p_quality_spec_trx_lines_t.quality_status='Rejected' )v1 where 1=1 $wh
                        GROUP BY v1.grn_number  ORDER BY $sidx $sord LIMIT $start , $limit";
        $result = \DB::select( $SQL );
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }

 /*Karthigaa Purpose for create and update Function*/
    public function purchasereturncreate($id=null,$aprv=null)
    {
          if($aprv!="")
                    {
                       $this->data['aprvidenty']=$aprv;
                    }else
                    {
                     $this->data['aprvidenty']="";
                    }

        if(isset($id))
        {
            $this->data['row']=$invoice_table=\DB::table('p_qc_header_t')->select('p_po_hdr_t.po_number',
        'p_po_hdr_t.po_hdr_id',
        'm_supplier_t.supplier_id',
        'm_subcontract_supplier_t.subcontract_supplier_id',
        'p_po_hdr_t.po_number',
        'p_po_hdr_t.po_date',
        'p_qc_header_t.dc_number',
        'p_qc_header_t.qc_header_id',
        'p_qc_header_t.qc_number',
        'p_grn_hdr_t.grn_number',
        'p_grn_hdr_t.grn_id',
         'p_po_invoice_hdr_t.po_invoice_id',
         'p_po_invoice_hdr_t.bill_number',
        'p_qc_header_t.dc_number')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_qc_header_t.supplier_id')
                ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_qc_header_t.subcontract_supplier_id')
                ->leftJoin('p_po_hdr_t','p_po_hdr_t.po_hdr_id','=','p_qc_header_t.po_number')
                ->leftJoin('p_grn_hdr_t','p_grn_hdr_t.grn_id','=','p_qc_header_t.grn_number')
                ->leftJoin('p_po_invoice_hdr_t','p_po_invoice_hdr_t.grn_number','=','p_grn_hdr_t.grn_id')
                ->where('qc_header_id',$id)->get();
           
                $this->data['row'][0]->return_header_id="";
                $this->data['row'][0]->return_date=date('Y-m-d');
                $this->data['row'][0]->return_invoice_number="";
                $this->data['row'][0]->p_return_status="";
                $this->data['row'][0]->invoice_number=$invoice_table[0]->po_invoice_id;
                $this->data['supplier_id']= $this->jCombo('m_supplier_t','supplier_id','supplier_name','');
                $this->data['subcontract_supplier_id']= $this->jCombo('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_name','');
                if($invoice_table[0]->supplier_id !=""){
                    $this->data['supplier_id']= $this->jCombo('m_supplier_t','supplier_id','supplier_name',$invoice_table[0]->supplier_id);
                }
                else{
                    $this->data['subcontract_supplier_id']= $this->jCombo('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_name',$invoice_table[0]->subcontract_supplier_id);
                }
             
             $qcspeclns = \DB::SELECT("SELECT p_quality_spec_trx_lines_t.*,sum(p_quality_spec_trx_lines_t.box_product_qty) as box_product_qty1,p_qc_lines_t.* FROM `p_quality_spec_trx_lines_t` left join p_qc_lines_t on p_quality_spec_trx_lines_t.reference_source_line_id=p_qc_lines_t.qc_line_id where p_quality_spec_trx_lines_t.reference_source_hdr_id='$id' and p_qc_lines_t.reject_qty!=0 group by p_quality_spec_trx_lines_t.reference_source_line_id");
//             $qcspeclns = \DB::SELECT("SELECT p_quality_spec_trx_lines_t.*,SUM(p_quality_spec_trx_lines_t.box_product_qty) AS box_product_qty1,p_qc_lines_t.* FROM  `p_quality_spec_trx_lines_t` LEFT JOIN p_qc_lines_t ON p_quality_spec_trx_lines_t.reference_source_line_id = p_qc_lines_t.qc_line_id WHERE p_qc_lines_t.reject_qty != 0 and p_qc_lines_t.qc_header_id='$id' and p_quality_spec_trx_lines_t.quality_status='Rejected' group by p_quality_spec_trx_lines_t.reference_source_line_id ");
//            dd($qcspeclns);
             $this->data['linedata'] = $qcspeclns;
            $grandtotal = 0;
	    $taxamount_total =0;

            if(count($this->data['linedata']) >= 1){
            $po_tax_total=0;
            $po_grand_total=0;
            foreach ($this->data['linedata'] as $key => $value)
            {
		$this->data['linedata'][$key]=(object) array();
                $this->data['linedata'][$key]->return_line_id="";
                $po_hdr_id=$invoice_table[0]->po_invoice_id;
		$unitprice = $this->podetails($po_hdr_id,$value->product_id);
                
                $discountperc = $this->discountdetails($po_hdr_id,$value->product_id);
                $product=$value->product_id;
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);
                $this->data['linedata'][$key]->uom_code_id =  $this->jcustomselect('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id,'and uom_code_id='.$value->uom_code_id);
                $reject_qty=$value->box_product_qty1;

                $polinedata = \DB::table('p_po_invoice_lines_t')->where('po_invoice_id',$po_hdr_id)->where('product_id',$product)->get();
                
                $tax_group_id=$polinedata[0]->tax_group_id;
               $tax1=\DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id',$tax_group_id)->get();
                $display=$tax1[0]->display_name;
                       $total=$unitprice*$reject_qty;
                       $dis_amt=($total*$discountperc)/100;
                       $dis_minus=$total-$dis_amt;

	$taxamount = (($value->reject_qty * $dis_minus) * $display )/ 100;
	$linetotal = ($taxamount + ($value->reject_qty * $dis_minus));

	$po_tax_total += $taxamount;
	$po_grand_total = $grandtotal + $linetotal;

	$this->data['linedata'][$key]->tax_group_id=$this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$tax_group_id);
        $this->data['linedata'][$key]->tax_amount=$taxamount;
        $this->data['linedata'][$key]->line_total=$linetotal;
        $this->data['linedata'][$key]->reject_qty =$value->box_product_qty1;
        $this->data['linedata'][$key]->qty =$value->qty;
        $this->data['linedata'][$key]->unit_price = $unitprice;
        $this->data['linedata'][$key]->discount_percentage =$discountperc;
        $this->data['linedata'][$key]->discount_amount =$dis_amt;
        $this->data['linedata'][$key]->taxgroup_id =  $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$this->taxgroup($value->product_id));

                 $this->data['linedata'][$key]->reason = $value->reason;

            }
                    

        }

            $this->data['row'][0]->po_tax_total=$po_tax_total;
          $this->data['row'][0]->po_grand_total=$po_grand_total;
            $this->data['row'][0]->po_tax_total="";
          $this->data['row'][0]->po_grand_total="";
}

      return view('purchasereturn.form',$this->data);
    }
    	/*Karthigaa Purpose For Edit Function*/
       public function purchasereturnedit($id=null,$aprv=null)
    	{
            if($aprv!="")
                    {
                       $this->data['aprvidenty']=$aprv;
                    }else
                    {
                     $this->data['aprvidenty']="";
                    }
       if(isset($id))
        {

		$this->data['row']=$return_table=\DB::table('p_return_header_t')
                        ->select('p_return_header_t.*','p_po_invoice_hdr_t.bill_number','p_po_invoice_hdr_t.po_invoice_id','p_return_header_t.invoice_number','p_po_hdr_t.po_number','p_po_hdr_t.po_hdr_id','m_supplier_t.supplier_id','p_po_hdr_t.po_number','p_po_hdr_t.po_date','p_return_header_t.dc_number','p_grn_hdr_t.grn_number','p_grn_hdr_t.grn_id','p_return_header_t.return_date','p_po_invoice_hdr_t.po_invoice_id','p_po_invoice_hdr_t.dc_number','p_qc_header_t.qc_header_id','p_qc_header_t.qc_number','p_return_header_t.return_invoice_number','p_return_header_t.po_tax_total','p_return_header_t.po_grand_total','p_return_header_t.p_return_status')
		->leftJoin('p_po_invoice_hdr_t','p_po_invoice_hdr_t.po_invoice_id','=','p_return_header_t.invoice_number')
                ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_return_header_t.subcontract_supplier_id')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_return_header_t.supplier_id')
		->leftJoin('p_po_hdr_t','p_po_hdr_t.po_hdr_id','=','p_return_header_t.po_number')
		->leftJoin('p_grn_hdr_t','p_grn_hdr_t.grn_id','=','p_return_header_t.grn_number')
		->leftJoin('p_qc_header_t','p_qc_header_t.qc_header_id','=','p_return_header_t.qc_id')
		->where('return_header_id',$id)->get();
//                dd($return_table);
		$this->data['row'][0]->return_header_id=$id;
		$this->data['row'][0]->return_date=$return_table[0]->return_date;
		$this->data['row'][0]->po_tax_total=$return_table[0]->po_tax_total;
		$this->data['row'][0]->po_grand_total=$return_table[0]->po_grand_total;
		$this->data['row'][0]->return_invoice_number=$return_table[0]->return_invoice_number;
		$this->data['row'][0]->p_return_status=$return_table[0]->p_return_status;
                $this->data['row'][0]->invoice_number=$return_table[0]->invoice_number;
                $this->data['row'][0]->bill_number=$return_table[0]->bill_number;
                if($return_table[0]->supplier_id !=""){
                    $this->data['supplier_id']= $this->jCombo('m_supplier_t','supplier_id','supplier_name',$return_table[0]->supplier_id);
                    $this->data['subcontract_supplier_id']= $this->jCombo('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_name','');
                }
                else{
                    $this->data['supplier_id']= $this->jCombo('m_supplier_t','supplier_id','supplier_name','');
                    $this->data['subcontract_supplier_id']= $this->jCombo('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_name',$return_table[0]->subcontract_supplier_id);
                }
		
		$this->data['p_return_status'] = $return_table[0]->p_return_status;



		$tablelines = \DB::table('p_return_lines_t')->where('return_header_id',$id)->get();
               // dd($tablelines);
		$this->data['linedata'] = $tablelines;

		if(count($this->data['linedata']) >= 1)
		{
		foreach ($this->data['linedata'] as $key => $value)
		{

                $this->data['linedata'][$key]->return_header_id=$value->return_header_id;
		$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);
		$this->data['linedata'][$key]->uom_code_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);

		$this->data['linedata'][$key]->unit_price = $value->unit_price;
		$this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
		$this->data['linedata'][$key]->discount_amount = $value->discount_amount;
		$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$value->tax_group_id);
		$this->data['linedata'][$key]->tax_amount = $value->tax_amount;
		$this->data['linedata'][$key]->line_total = $value->line_total;

		}
		}
		}

      return view('purchasereturn.form',$this->data);
    }
/*End Purpose For Edit Function*/
    /*Karthigaa Purpose For Save Function*/
     public function purchasereturnsave(Request $request)
        {
                $id='';
                $data = $this->validatePost($request->all(),$this->table,'header');
                $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');

                if ($_POST['return_invoice_number'] ==""){
                $seqno=$this->Seqno('RTV-','p_return_header_t',$_POST['return_invoice_number']);

                            }
                    else{
                       $seqno=$_POST['return_invoice_number'];
                    }
                    \DB::update("update p_qc_header_t set return_status='1' where qc_header_id='".$_POST['qc_id']."'");
                \DB::beginTransaction();
                try
                {
                    $data['return_invoice_number']=$seqno;
                     $id=$this->model->insertRow($data);
                     $lid=$this->submodel->subgridSave($lines_data,$id);
                     
                     $returnhdr=DB::table('p_return_header_t')->where('return_header_id',$id)->get();
                     $po_id=$returnhdr[0]->po_number;
                    /*Karthigaa Purpose For Journal Entry Insert*/
					
                     if($po_id!=""){
    if($_POST['p_return_status']=="APPROVED"){
	
                   $returndate=$_POST['return_date'];
                   $org=\Session::get('organization');
                    $loc=\Session::get('location');
                    $compy=\Session::get('companyid');
                  $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('DEBIT NOTE".$_POST['invoice_number']."','DEBIT NOTE','$returndate','$id','APPROVED','$compy','$loc','$org')");
                  $jid = DB::getPdo()->lastInsertId();
 $po_details=\DB::table('m_products_t')->whereIn("product_id",$_POST['bulk_product_id'])->get();
 $collection = collect($po_details);
 $supacc=\DB::table('m_supplier_t')->where('supplier_id',$_POST['supplier_id'])->get();
 $reverse_charge=\DB::table('p_po_invoice_hdr_t')->where('bill_number',$_POST['invoice_number'])->get();
$total=0;
$tkey=1;
          foreach($_POST['bulk_product_id'] as $key=>$value){
			  
			   $filtered = $collection->where('product_id',$value);
                   $filtered->all();
			  $unit_price=$_POST['bulk_unit_price'][$key];
			  $discount_percentage=$_POST['bulk_discount_percentage'][$key];
			  $qty=$_POST['bulk_reject_qty'][$key];
			  $tax_group=$_POST['bulk_tax_group_id'][$key];
			  
            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['return_date'];
            $journal_lines_data[$tkey]['reference_source']="PRODUCT";
            $journal_lines_data[$tkey]['reference_id']=$value;
            $journal_lines_data[$tkey]['account_id']=$filtered[0]->control_account_id;
            $rate=$unit_price-(($unit_price*$discount_percentage)/100);
            $rate=    $rate*$qty ; 
            $tax_details=array();
            if($filtered[0]->tax_credit!="Yes")
            {
				 $tax_deta=\DB::table('m_tax_group_t')->where('tax_group_id',$tax_group)->get();
           
                    $taxval=(float)$tax_deta[0]->display_name;
                    $rate=    $rate+(($rate*$taxval)/100) ;     
            }
            else
            {
                $tax_details=\DB::table('m_tax_group_lines_t')->join('f_tax_code_t','f_tax_code_t.tax_code_id','=','m_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*','f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id',$tax_group)->get();
            }
		$total=$total+$rate;
            $journal_lines_data[$tkey]['debit_amount']='';
            $journal_lines_data[$tkey]['credit_amount']=$rate;
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');
			$tkey++;
            if($reverse_charge[0]->reverse_charge=="")
            {
            foreach($tax_details as $taxval)
            {
				 $rate=$unit_price-(($unit_price*$discount_percentage)/100);
                $rate=    $rate*$qty ; 
               
                
                        $taxval1=(float)$taxval->tax_code_percent;
                        $rate=    ($rate*$taxval1)/100 ;  
						if($rate >0)
						{
                $total=$total+$rate;
                $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$_POST['return_date'];
                $journal_lines_data[$tkey]['reference_source']="PRODUCT";
                $journal_lines_data[$tkey]['reference_id']=$value;
                $journal_lines_data[$tkey]['account_id']=$taxval->input_tax_account_id;
                  
                
                $journal_lines_data[$tkey]['debit_amount']='';
                $journal_lines_data[$tkey]['credit_amount']=$rate;
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
				   $tkey++;
            }}
            }
         


        }
		       $tkey=0;
		        $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                $journal_lines_data[$tkey]['journal_date']=$_POST['return_date'];
                $journal_lines_data[$tkey]['reference_source']="SUPPLIER";
                $journal_lines_data[$tkey]['reference_id']=$_POST['supplier_id'];
                $journal_lines_data[$tkey]['account_id']=$supacc[0]->account_structure_id;
                $journal_lines_data[$tkey]['debit_amount']=$total;
                $journal_lines_data[$tkey]['credit_amount']='';
                $journal_lines_data[$tkey]['line_no']=$tkey+1;
                $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 
				ksort($journal_lines_data);
 \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 
 $journal_lines_data=array();
 
   if($reverse_charge[0]->reverse_charge!="")
                {
            $inv_no="REVERSE CHARGE-".$_POST['return_invoice_number'];
            $invdate=$_POST['return_date'];
            $org=\Session::get('organization');
            $loc=\Session::get('location');
            $compy=\Session::get('companyid'); 
               
                $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$inv_no','DEBIT NOTE RETURN','$invdate','$id','APPROVED','$compy','$loc','$org')");
                $jid = DB::getPdo()->lastInsertId();
                $tkey=0;
                $total=0;
                $journal_lines_data=array();
             foreach($_POST['bulk_product_id'] as $key=>$value){
			  
			   $filtered = $collection->where('product_id',$value);
                   $filtered->all();
			  $unit_price=$_POST['bulk_unit_price'][$key];
			  $discount_percentage=$_POST['bulk_discount_percentage'][$key];
			  $qty=$_POST['bulk_reject_qty'][$key];
			  $tax_group=$_POST['bulk_tax_group_id'][$key];
			  $tax_details=\DB::table('m_tax_group_lines_t')->join('f_tax_code_t','f_tax_code_t.tax_code_id','=','m_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*','f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id',$tax_group)->get();
            foreach($tax_details as $taxval)
			{
                       $rate=$unit_price-(($unit_price*$discount_percentage)/100);
                        $rate=    $rate*$qty ; 
                       
                        
                                $taxval1=(float)$taxval->tax_code_percent;
                               $rate=    ($rate*$taxval1)/100 ; 
if($rate > 0)
{	
                        $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                        $journal_lines_data[$tkey]['journal_date']=$_POST['return_date'];
                        $journal_lines_data[$tkey]['reference_source']="PRODUCT";
                        $journal_lines_data[$tkey]['reference_id']=$value;
                        $journal_lines_data[$tkey]['account_id']=$taxval->output_tax_account_id;
                            
                        
                        $journal_lines_data[$tkey]['debit_amount']=$rate;
                        $journal_lines_data[$tkey]['credit_amount']='';
                        $journal_lines_data[$tkey]['line_no']=$tkey+1;
                        $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                        $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                        $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                        $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id']=\Session::get('location');  
                        $tkey++;
                    }
					}
             }  

  foreach($_POST['bulk_product_id'] as $key=>$value){
			  
			   $filtered = $collection->where('product_id',$value);
                   $filtered->all();
			  $unit_price=$_POST['bulk_unit_price'][$key];
			  $discount_percentage=$_POST['bulk_discount_percentage'][$key];
			  $qty=$_POST['bulk_reject_qty'][$key];
			  $tax_group=$_POST['bulk_tax_group_id'][$key];
			  $tax_details=\DB::table('m_tax_group_lines_t')->join('f_tax_code_t','f_tax_code_t.tax_code_id','=','m_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*','f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id',$tax_group)->get();
            foreach($tax_details as $taxval)
			{
                       $rate=$unit_price-(($unit_price*$discount_percentage)/100);
                        $rate=    $rate*$qty ; 
                       
                        
                                $taxval1=(float)$taxval->tax_code_percent;
                               $rate=    ($rate*$taxval1)/100 ; 
if($rate > 0)
{	
                        $journal_lines_data[$tkey]['journal_entry_id']=$jid;
                        $journal_lines_data[$tkey]['journal_date']=$_POST['return_date'];
                        $journal_lines_data[$tkey]['reference_source']="PRODUCT";
                        $journal_lines_data[$tkey]['reference_id']=$value;
                        $journal_lines_data[$tkey]['account_id']=$taxval->input_tax_account_id;
                            
                        
                        $journal_lines_data[$tkey]['debit_amount']='';
                        $journal_lines_data[$tkey]['credit_amount']=$rate;
                        $journal_lines_data[$tkey]['line_no']=$tkey+1;
                        $journal_lines_data[$tkey]['created_by']=\Session::get('id');
                        $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
                        $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
                        $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id']=\Session::get('location');  
                        $tkey++;
                    }
					}
             }
			  \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 
			 }
				
				
                }
 
 
            }
      
        /*End*/

                     \DB::commit();

                    if($_POST['p_return_status']== "INITIATED"){
                        $noti_msg = "Purchase Returned".$id;
                        $send_notification = $this->sendPopUpHomeNoty($id,"PO RETURN APPROVAL",$noti_msg,'purchasereturn');
                    }
                    /**Auditlog**/
                        if($_POST['return_header_id']==""){
                                    $action="create";
                                    }else{
                                    $action="edit";
                                    }
                        $this->auditlog($id,"purchasereturn",$action,$_POST,"p_return_header_t");

                     return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'lid' => $lid,'auto_no'=>$seqno));
                }
                catch (\Illuminate\Database\QueryException $e)
                {
                     $message = explode('(', $e->getMessage());
                     $dbCode = rtrim($message[0], ']');
                     $dbCode = trim($dbCode, '[');
                     \DB::rollback();
                     return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
                }

        }
/*End Purpose For Save Function*/
        
/*Karthigaa Purpose For View Function*/
public function purchasereturnview($id=null){
        if(isset($id))
        {

            $this->data['row']=$gin_table=\DB::table('p_return_header_t')->select('p_return_header_t.*','p_qc_header_t.qc_number','p_po_invoice_hdr_t.bill_number','p_po_hdr_t.po_number','p_po_hdr_t.po_hdr_id','m_supplier_t.supplier_id','p_po_hdr_t.po_number','p_po_hdr_t.po_date','p_return_header_t.dc_number','p_grn_hdr_t.grn_number','p_grn_hdr_t.grn_id','p_return_header_t.return_date','p_return_header_t.po_tax_total','p_return_header_t.po_grand_total','p_return_header_t.return_invoice_number')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_return_header_t.supplier_id')
                ->leftJoin('p_po_hdr_t','p_po_hdr_t.po_hdr_id','=','p_return_header_t.po_number')
                    ->leftJoin('p_qc_header_t','p_qc_header_t.qc_header_id','=','p_return_header_t.qc_id')
                ->leftJoin('p_grn_hdr_t','p_grn_hdr_t.grn_id','=','p_return_header_t.grn_number')
                    ->leftJoin('p_po_invoice_hdr_t','p_po_invoice_hdr_t.po_invoice_id','=','p_return_header_t.invoice_number')
                ->where('return_header_id',$id)->get();
//            dd($gin_table);
                $this->data['row'][0]->return_header_id=$id;
                $return_date=$gin_table[0]->return_date;
                $this->data['row'][0]->return_date=date(\Session::get('p_date_format'),strtotime($return_date));
                $po_date=$gin_table[0]->po_date;
                $this->data['row'][0]->po_date=date(\Session::get('p_date_format'),strtotime($po_date));
                $this->data['row'][0]->return_status=$gin_table[0]->p_return_status;
                $this->data['row'][0]->po_tax_total=$gin_table[0]->po_tax_total;
                $this->data['row'][0]->bill_number=$gin_table[0]->invoice_number;
                $this->data['row'][0]->qc_number=$gin_table[0]->qc_number;
                $this->data['row'][0]->po_grand_total=$gin_table[0]->po_grand_total;
                $this->data['row'][0]->return_invoice_number=$gin_table[0]->return_invoice_number;
               $this->data['supplier_id']= $this->idname('supplier_name','m_supplier_t','supplier_id',$gin_table[0]->supplier_id);
             $tablelines = \DB::table('p_return_lines_t')->where('return_header_id',$id)->get();
        $this->data['linedata'] = $tablelines;
            if(count($this->data['linedata']) >= 1)
        {
            foreach ($this->data['linedata'] as $key => $value)
            {
               $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->idname('concatenated_product','m_products_t','product_id',$value->product_id);
                $this->data['linedata'][$key]->uom_code_id =  $this->idname('uom_code','m_uom_codes_t','uom_code_id',$value->uom_code_id);
                    $this->data['linedata'][$key]->reject_qty = $value->reject_qty;
                    $this->data['linedata'][$key]->unit_price = $value->unit_price;
                $this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
                $this->data['linedata'][$key]->discount_amount = $value->discount_amount;
                $this->data['linedata'][$key]->tax_group_id = $this->idname('tax_group_name','m_tax_group_t','tax_group_id',$value->tax_group_id);
                 $this->data['linedata'][$key]->qty = $value->qty;
                $this->data['linedata'][$key]->tax_amount = $value->tax_amount;
                $this->data['linedata'][$key]->line_total = $value->line_total;
                $this->data['linedata'][$key]->reason = $value->reason;

            }
        }
}
    $this->data['url']=$_GET['return'];
      return view('purchasereturn.view',$this->data);
    }
/*End Purpose For View Function*/
    
    /*Karthigaa Purpose For Purchase Return Approval Function*/
    public function purchasereturnapproval($id=null,$aprv=null)
    	{
         if($aprv!=""){
                $this->data['aprvidenty']=$aprv;
                }else
                    {
                     $this->data['aprvidenty']="";
                    }
       if(isset($id))
        {

        $this->data['row']=$return_table=\DB::table('p_return_header_t')
        ->select('p_po_invoice_hdr_t.bill_number','p_po_hdr_t.po_number','p_po_hdr_t.po_hdr_id','m_supplier_t.supplier_id','m_subcontract_supplier_t.subcontract_supplier_id','p_po_hdr_t.po_number','p_po_hdr_t.po_date','p_return_header_t.dc_number','p_grn_hdr_t.grn_number','p_grn_hdr_t.grn_id','p_return_header_t.return_date','p_po_invoice_hdr_t.po_invoice_id','p_po_invoice_hdr_t.dc_number','p_qc_header_t.qc_header_id','p_qc_header_t.qc_number','p_return_header_t.return_invoice_number','p_return_header_t.po_tax_total','p_return_header_t.po_grand_total','p_return_header_t.p_return_status')
		->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_return_header_t.supplier_id')
                ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_return_header_t.subcontract_supplier_id')
		->leftJoin('p_po_hdr_t','p_po_hdr_t.po_hdr_id','=','p_return_header_t.po_number')
		->leftJoin('p_grn_hdr_t','p_grn_hdr_t.grn_id','=','p_return_header_t.grn_number')
		->leftJoin('p_qc_header_t','p_qc_header_t.qc_header_id','=','p_return_header_t.qc_id')
		->leftJoin('p_po_invoice_hdr_t','p_po_invoice_hdr_t.po_invoice_id','=','p_return_header_t.invoice_number')
		->where('return_header_id',$id)->get();
		$this->data['row'][0]->return_header_id=$id;
		$this->data['row'][0]->return_date=$return_table[0]->return_date;
		$this->data['row'][0]->po_tax_total=$return_table[0]->po_tax_total;
		$this->data['row'][0]->po_grand_total=$return_table[0]->po_grand_total;
		$this->data['row'][0]->return_invoice_number=$return_table[0]->return_invoice_number;
		$this->data['row'][0]->p_return_status=$return_table[0]->p_return_status;
                $this->data['row'][0]->invoice_number=$return_table[0]->bill_number;
		
                $this->data['supplier_id']= $this->jCombo('m_supplier_t','supplier_id','supplier_name','');
                $this->data['subcontract_supplier_id']= $this->jCombo('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_name','');
                if($return_table[0]->supplier_id !=""){
                    $this->data['supplier_id']= $this->jCombo('m_supplier_t','supplier_id','supplier_name',$return_table[0]->supplier_id);
                }
                else{
                    $this->data['subcontract_supplier_id']= $this->jCombo('m_subcontract_supplier_t','subcontract_supplier_id','subcontract_name',$return_table[0]->subcontract_supplier_id);
                }
                
		$this->data['p_return_status'] = $return_table[0]->p_return_status;
		$tablelines = \DB::table('p_return_lines_t')->where('return_header_id',$id)->get();
		$this->data['linedata'] = $tablelines;
		if(count($this->data['linedata']) >= 1)
		{
		foreach ($this->data['linedata'] as $key => $value)
		{
                $this->data['linedata'][$key]->return_header_id=$value->return_header_id;
		$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t','product_id','product_code|concatenated_product',$value->product_id,'and product_id='.$value->product_id);
		$this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id,'and uom_code_id='.$value->uom_code_id);
		$this->data['linedata'][$key]->unit_price = $value->unit_price;
		$this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
		$this->data['linedata'][$key]->discount_amount = $value->discount_amount;
		$this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$value->tax_group_id);
		$this->data['linedata'][$key]->tax_amount = $value->tax_amount;
		$this->data['linedata'][$key]->line_total = $value->line_total;
		}
		}
		}
            $this->data['pageMethod']="purchasereturnapproval";
      return view('purchasereturn.form',$this->data);
    }

/*Karthigaa Purpose for Print*/
    public function getprint($id=null)
	{
	 if(isset($id))
    	{

			$gin_table=\DB::table('p_return_header_t')->select('p_po_hdr_t.po_number','m_subcontract_supplier_t.subcontract_supplier_id','p_po_hdr_t.po_hdr_id','m_supplier_t.supplier_id','p_po_hdr_t.po_number','p_po_hdr_t.po_date','p_return_header_t.dc_number','p_grn_hdr_t.grn_number','p_grn_hdr_t.grn_id','p_return_header_t.return_header_id','p_return_header_t.return_date','p_return_header_t.po_tax_total','p_return_header_t.po_grand_total','p_return_header_t.return_invoice_number')
			->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_return_header_t.supplier_id')
                        ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_return_header_t.subcontract_supplier_id')
			->leftJoin('p_po_hdr_t','p_po_hdr_t.po_hdr_id','=','p_return_header_t.po_number')
			->leftJoin('p_grn_hdr_t','p_grn_hdr_t.grn_id','=','p_return_header_t.grn_number')
			->where('return_header_id',$id)->get();

		    // dd($gin_table);
		    if(($gin_table->isNotEmpty()))
			{
                $this->data['return_header_id']=$id;
                $this->data['return_date']=date(\Session::get('p_date_format'), strtotime($gin_table[0]->return_date));
                $this->data['po_tax_total']=$gin_table[0]->po_tax_total;
                $this->data['po_grand_total']=$gin_table[0]->po_grand_total;
                $this->data['return_invoice_number']=$gin_table[0]->return_invoice_number;
                $this->data['supplier_id']= $this->idname('supplier_name','m_supplier_t','supplier_id',$gin_table[0]->supplier_id);
                $this->data['subcontract_supplier_id']= $this->idname('subcontract_name','m_subcontract_supplier_t','subcontract_supplier_id',$gin_table[0]->subcontract_supplier_id);

			}
		    else
			{
		$this->data['return_header_id']='';
                $this->data['return_date']='';
                $this->data['po_tax_total']='';
                $this->data['po_grand_total']='';
                $this->data['return_invoice_number']='';
                $this->data['supplier_id']= '';
			}



			$tablelines = \DB::table('p_return_lines_t')->where('return_header_id',$id)->get();
			$this->data['linedata'] = $tablelines;

		 	if($tablelines->isNotEmpty())
			{
			    $tot=0;
			    $tax=0;
				foreach($this->data['linedata'] as $key => $value)
				{

    				$this->data['linedata'][$key]->product_id= $this->data['product_id'] = $this->idname('concatenated_product','m_products_t','product_id',$value->product_id);
    				$this->data['linedata'][$key]->uom_code_id =  $this->idname('uom_code','m_uom_codes_t','uom_code_id',$value->uom_code_id);
    				$this->data['linedata'][$key]->reject_qty = $value->reject_qty;
    				$this->data['linedata'][$key]->unit_price = $value->unit_price;
    				$this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
    				$this->data['linedata'][$key]->discount_amount = $value->discount_amount;
    				$this->data['linedata'][$key]->tax_group_id = $this->idname('tax_group_name','m_tax_group_t','tax_group_id',$value->tax_group_id);
    				// $this->data['linedata'][$key]->display_name = $this->idname('display_name','m_tax_group_t','tax_group_id',$value->tax_group_id);
    				
    			
    			//	dd($this->data['linedata'][$key]->display_name);
    				$this->data['linedata'][$key]->qty = $value->qty;
    				$this->data['linedata'][$key]->tax_amount = $value->tax_amount;
    				$tax=$value->tax_amount+$tax;
    				$this->data['linedata'][$key]->line_total = $value->line_total;
    				$this->data['linedata'][$key]->reason = $value->reason;
    			    $this->data['linedata'][$key]->amount = $value->reject_qty * $value->unit_price;
    			    $tot=$value->reject_qty * $value->unit_price+$tot;
				}
			$this->data['tot']=$tot;
			$this->data['tax']=$tax;
			}

 		}




		/********************* company *******************/
			$company=$this->getCompany();

			if(!empty($company))
			{
			$company_name=$company[0]->company_name;
			$company_logo_name=$company[0]->company_logo_name;
			$this->data['company_name']= $company_name;
			$this->data['company_logo_name']= $company_logo_name;
                        $this->data['gst_no'] = $company[0]->gst_no;
			$this->data['pan_no'] = $company[0]->pan_no;
			}
			else
			{
			$this->data['company_name']= '';
			}
		/******************** end ************************/

        /******************* location *******************/

		$location=$this->getLocationaddress();

		if(!empty($location))
		{
			$this->data['location_name'] = $location[0]->location_name;
			$this->data['country'] = $this->getCountry($location[0]->country_id);
			$this->data['state_l'] = $this->getState($location[0]->state_id);
			$this->data['state']=$this->data['state_l'][0]->state_name;
			//$this->data['area_code']=$this->data['state_l'][0]->state_code;
			$this->data['city'] = $this->getCity($location[0]->city_id);
			$this->data['area']=$location[0]->area;
			$this->data['pincode']=$location[0]->pincode;
			$this->data['street'] = $location[0]->street_name;
			$this->data['address'] = $location[0]->address;
		}
		else
		{
			$this->data['location_name'] = '';
			$this->data['country'] = '';
			$this->data['state_l'] = '';
			$this->data['state']='';
			//$this->data['area_code']=$this->data['state_l'][0]->state_code;
			$this->data['city'] = '';
			$this->data['area']='';
			$this->data['pincode']='';
			$this->data['gst_no'] = '';
			$this->data['pan_no'] = '';
			$this->data['street'] = '';
			$this->data['address'] = '';
		}

    	$this->data['company_address']=$this->data['address'].", ".$this->data['street'].", ".$this->data['area'].", ".$this->data['city'].",  ".$this->data['state'].", ".$this->data['country'].", ".$this->data['pincode'];

        /************** End **********************/

       /****** supplier Name ********************/

	if(!empty($gin_table[0]->supplier_id))
	{
            $supplier=$this->getSupplier($gin_table[0]->supplier_id);

            $this->data['supplier_name'] = $supplier[0]->supplier_name;
            //$this->data['sup_gst_no'] = $supplier[0]->gst_no;

        }
         else if(!empty($gin_table[0]->subcontract_supplier_id)){
            $supplier=$this->getSubSupplier($gin_table[0]->subcontract_supplier_id);

            $this->data['supplier_name'] = $supplier[0]->subcontract_name;
        }
        else
        {
        $this->data['supplier_name']='';
        $this->data['sup_gst_no'] ='';
        }

	  /*********** End **************************/

	  /*********** supplier Address *************/

		if(!empty($gin_table[0]->supplier_id))
		{
    		$supplier_address=$this->getSupplieraddress($gin_table[0]->supplier_id);
                // dd($supplier_address);
    		if(!empty($supplier_address))
			{
    			$this->data['address']=$supplier_address[0]->address;
    			$this->data['state']=$this->getState_s($supplier_address[0]->state);
    			$this->data['state_name']=$this->data['state'][0]->state_name;
    			$this->data['state_code']=$this->data['state'][0]->state_code;

    			$this->data['city']=$this->getCity($supplier_address[0]->city);
    			$this->data['country']=$this->getCountry($supplier_address[0]->country);
    			$this->data['pincode']=$supplier_address[0]->pincode;
                        $this->data['sup_gst_no'] = $supplier_address[0]->gst_number;
    			$this->data['contact_number']=$supplier_address[0]->contact_number;
			}
			else
			{
    			$this->data['address']='';
    			$this->data['state']='';
    			$this->data['state_name']='';
    			$this->data['state_code']='';
    			$this->data['city']='';
    			$this->data['country']='';
    			$this->data['pincode']='';
    			$this->data['contact_number']='';
			}
	    }
            else{
               $supplier_address=$this->getSubSupplieraddress($gin_table[0]->subcontract_supplier_id);
//                 dd($supplier_address);
    		if(!empty($supplier_address))
			{
    			$this->data['address']=$supplier_address[0]->address;
    			$this->data['state']=$this->getState_s($supplier_address[0]->state);
    			$this->data['state_name']=$this->data['state'][0]->state_name;
				//dd($this->data['state_name']);
    			$this->data['state_code']=$this->data['state'][0]->state_code;

    			$this->data['city']=$this->getCity($supplier_address[0]->city);
    			$this->data['country']=$this->getCountry($supplier_address[0]->country);
    			$this->data['pincode']=$supplier_address[0]->pincode;
                $this->data['sup_gst_no'] = $supplier_address[0]->gst_number;
    			$this->data['contact_number']=$supplier_address[0]->contact_number;
			}
			else
			{
    			$this->data['address']='';
    			$this->data['state']='';
    			$this->data['state_name']='';
    			$this->data['state_code']='';
    			$this->data['city']='';
    			$this->data['country']='';
    			$this->data['pincode']='';
    			$this->data['contact_number']='';
			} 
            }

    	$this->data['supplier_address']=$this->data['address'].",".$this->data['state_name'].",".$this->data['city'].",".$this->data['country'].",".$this->data['pincode'];

    	/************** End *************************/

    	/************** date ***************/
       $this->data['date']= date(\Session::get('p_date_format'));
		$this->data['linedata']=$this->data['linedata'];
        /*Mail Start*/
          if(isset($_GET['mail']))
            {
                if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
        Config::set('mail.username', \Session::get('user_email'));
        Config::set('mail.password', \Session::get('user_password'));
    }

                $this->data['print']="PRINTS";


              $tomail ="'".str_replace(",","','",$_GET['mail'])."'";

                \Mail::send('purchasereturn.purchasereturn_print',$this->data, function($message)
                {
                  if(!empty($_GET['cc'])){

                  $cc=explode(',',$_GET['cc']);
                  $message->cc($cc);
              }else{
                 $cc=array();
              }

                    $msg=$_GET['msg'];


                      $message->to(explode(",",$_GET['mail']));
                    $message->subject("Purchase Return". $this->data['return_invoice_number']);

                     $message->setBody($msg);
                    //$message->from('Saipavan9010@gmail.com');
                    if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                    $retuen=DB::table('p_return_header_t')->where('return_header_id',$this->data['return_header_id'])->get();
            if($retuen[0]->attachment_file!=''){
                $file_a=json_decode($retuen[0]->attachment_file);
                foreach($file_a as $k1=>$v1){
                 $message->attach('uploads/purchasereturn/P'.$this->data['return_header_id'].'/'.$v1);
                }
            }
                    $message->attach('uploads/purchasereturn/P_'.stripslashes($this->data['return_invoice_number']).'.pdf');

                });
                            return 1;


            }
            $this->data['print']="PRINT";
        $this->data['print_val'] = '1';

        if(isset($_GET['mails'])){

          $this->data['print']="PRINTS";
             return view('purchasereturn.purchasereturn_print', $this->data);
         }
        /*End*/
		$this->data['print']="PRINT";
		$this->data['print_val'] = '1';
		return view('purchasereturn.purchasereturn_print',$this->data);
	}

    /*Karthigaa Purpose For File Attachment Save Function*/
public function purchasefilesave(Request $request){
    if($request->hasfile('email_attachment')){
      File::deleteDirectory(public_path('uploads/purchasereturn/P'.$_POST['return_header_id']));

                      foreach($request->file('email_attachment') as $file)
                  {
                      $name=$file->getClientOriginalName();

                      $file->move(public_path().'/uploads/purchasereturn/P'.$_POST['return_header_id'].'/', $name);
                      $data[] = $name;
                  }
      $attachfile_name=json_encode($data);
        \DB::update("update p_return_header_t set attachment_file='".$attachfile_name."' where return_header_id=".$_POST['return_header_id']);
      return 1;
        }else{
             $var = File::deleteDirectory(public_path('uploads/purchasereturn/P'.$_POST['return_header_id']));
             $attachfile_name = "";
              \DB::update("update p_return_header_t set attachment_file='".$attachfile_name."' where return_header_id=".$_POST['return_header_id']);
              return 2;
        }

    }
/*End*/
    /*Karthigaa Purpose For Mail Function*/
   function getSuppliermaildetails($id=null) {
                $sql="SELECT
                    m_supplier_sites_t.contact_person,
                    m_supplier_sites_t.contact_number,
                    m_supplier_sites_t.contact_mail as email_id,
                    m_supplier_sites_t.supplier_site_id
                    FROM `m_supplier_t`
                    left join m_supplier_sites_t ON
                    m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id
                    where m_supplier_t.supplier_id='$id'";
                    $result1=\DB::select($sql);
                        if(!empty($result1))
                        {
                        foreach($result1 as $key => $value)
                        {
                                   $contactperson=explode(',',$value->contact_person);
                                   $contact_number=explode(',',$value->contact_number);
                                   $email=explode(',',$value->email_id);
                                  foreach($email as $k=>$v){
                                      $result[]= array($value->supplier_site_id,$contactperson[$k],$contact_number[$k],$v);
                                  } 
                        }
                        }
                        else {
                        $result[]= array();
                        }
                        return $result;
                }
    /*Karthigaa Purpose to get Unit Price From Invoice for Print*/
    function podetails($po_hdr_id,$p_id){
	$result=\DB::table('p_po_invoice_lines_t')->select('p_po_invoice_lines_t.unit_price')->where('po_invoice_id',$po_hdr_id)->where('product_id',$p_id)->get();
            if($result->isNotEmpty()){
            $unit_price=$result[0]->unit_price;
            }
            else {
            $unit_price= '0.00';
           }
            return $unit_price;
	}
     /*Karthigaa Purpose to get Discount Amount From Invoice for Print*/
        function discountdetails($po_hdr_id,$p_id){
	$result=\DB::table('p_po_invoice_lines_t')->select('p_po_invoice_lines_t.discount_percentage')->where('po_invoice_id',$po_hdr_id)->where('product_id',$p_id)->get();
	if($result->isNotEmpty()){
	$discount_percentage=$result[0]->discount_percentage;
        }
	else {
	$discount_percentage= '0.00';
        }
	return $discount_percentage;
	}
    /*Karthigaa Purpose to get Tax*/
	public function taxgroup($pid=null){
	$pro_details=\DB::table("m_products_t")->select('trx_uom_id','hsn_code')->where('product_id',$pid)->get();
        if($pro_details->isNotEmpty())
	{
            $prd_data['uom_code_id']=$pro_details[0]->trx_uom_id;
            $hsn_code=$pro_details[0]->hsn_code;
            $date=date('Y-m-d');
            $tax=\DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$hsn_code' and start_date<='$date' and end_date>='$date' and active='Yes'");
                if(!empty($tax))
                {
                  return  $prd_data['tax_group_id']=$tax[0]->tax_group_id;
                }
                else {
                return 0;
                }
                }
                else
                {
                return 0;
                }
        }
        
/*Karthigaa Purpose to get SubContract Supplier for Print*/
       function getSubSupplier($sup_id=null)
	{

		$subsupplier=\DB::table('m_subcontract_supplier_t')->where('subcontract_supplier_id',$sup_id)->get();

		if(!empty($subsupplier))
			{
				return $subsupplier;
			}
			else
			{
			    return 0;
			}
	}
/*Karthigaa Purpose to get SubContract Supplier Address for Print*/
        function getSubSupplieraddress($sub_id=null){
	    $subsupplier_address=array();
    	    $subsupplier_address=\DB::select("select * from m_subcontract_sites_t where subcontract_supplier_id='$sub_id'");
		if(!empty($subsupplier_address))
		{
			return $subsupplier_address;
		}
	 	else
		{
			return 0;
		}

		}
/*Karthigaa Purpose to get Company State for Print*/
    	function getState($state_id=null)
	{
		$state=\DB::table('m_states_t')->where('state_id',$state_id)->get();
		if(!empty($state))
		{
			return $state;
		}
		else
		{
			return 0;
		}

	}
        /*Karthigaa Purpose to get Supplier State for Print*/
	function getState_s($state_id=null)
	{
		$state=\DB::table('m_states_t')->where('state_id',$state_id)->get();
		if(!empty($state))
		{
			return $state;
		}
		else
		{
			return 0;
		}
	}

/*Karthigaa Purpose to get Company City for Print*/
	function getCity($city_id=null)
	{
		$city=\DB::table('m_cities_t')->where('city_id',$city_id)->get();
		if(!empty($city))
		{
			return $city[0]->city_name;
		}
		else
		{
			return 0;
		}

	}
/*Karthigaa Purpose to get Company Country for Print*/
	function getCountry($country_id=null)
	{
		$country=\DB::table('m_countries_t')->where('country_id',$country_id)->get();
		if(!empty($country))
		{
			return $country[0]->country_name;
		}
		else
		{
			return 0;
		}

	}
/*Karthigaa Purpose to get Company Details for Print*/
	function getCompany()
		{
			$sql=array();
			$company=\Session::get('companyid');
			$sql=\DB::SELECT("SELECT company_id,company_name,gst_no,pan_no,company_logo_name FROM `m_company_t` WHERE `company_id`=".$company."");
			if(!empty($sql))
			{
			return $sql;
			}
			else
			{
			return 0;
			}
		}
/*Karthigaa Purpose to get Locaion Address for Print*/
	function getLocationaddress(){
        $sql=array();
        $location=\Session::get('location');

        $sql=\DB::SELECT('select * from m_location_t where location_id='.$location.'');
        if(!empty($sql)){
            return $sql;
        }else{
            return 0;
        }
    }
/*Karthigaa Purpose to get Supplier Location Address for Print*/
		function getSupplieraddress($sub_id=null)
		{
		$supplier_address=array();
    	        $supplier_address=\DB::select("select * from m_supplier_sites_t where supplier_id='$sub_id'");
		if(!empty($supplier_address))
		{
			return $supplier_address;
		}
	 	else
		{
			return 0;
		}
		}
/*Karthigaa Purpose to get Supplier for Print*/
	function getSupplier($sup_id=null)
	{

		$supplier=\DB::table('m_supplier_t')->where('supplier_id',$sup_id)->get();

		if(!empty($supplier))
			{
				return $supplier;
			}
			else
			{
			    return 0;
			}
	}
}
