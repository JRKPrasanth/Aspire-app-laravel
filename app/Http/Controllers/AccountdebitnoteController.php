<?php

namespace App\Http\Controllers;

use App\Accountdebitnote;
use App\Accountdebitnotelines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;


class AccountdebitnoteController extends Controller
{
  public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->model=new Accountdebitnote;
        $this->submodel=new Accountdebitnotelines;
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='accountdebitnote';
        $this->table="f_debitnote_hdr_t";
        $this->subtable="f_debitnote_lines_t";
        $this->middleware('auth');

    } 
    public function index()
    {
       $this->data['suppliername']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name'); 
       $this->data['invoiceno']=$this->jqgridselect('p_po_invoice_hdr_t','po_invoice_id','bill_number'); 
       $this->data['ponumber']=$this->jqgridselect('p_po_hdr_t','po_hdr_id','po_number'); 
         $this->data['pageMethod']='accountdebitnote'; 
        return view("accountdebitnote.table",$this->data);
    }
       public function getaccountdebitData(){
		   
		   
		   

        $wh='';
        if($_GET['_search']=='true'){
        $wh=$this->jqgridsearch('f_debitnote_hdr_t',$_GET['filters']);
        }
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
		   
		   
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
		$groupname=\Session::get('groupname');
		if($groupname=='Superadmin' || $groupname=='Admin'){
		$wh.='and f_debitnote_hdr_t.company_id='.$compy;	
		}else{
			$wh.='and f_debitnote_hdr_t.company_id='.$compy.' and f_debitnote_hdr_t.location_id='.$loc;		
		}   
		   
		   
        $result = \DB::select("SELECT COUNT(f_debitnote_hdr_t.debitnote_hdr_id) AS count FROM f_debitnote_hdr_t where 1=1  $wh");

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
 //  $SQL = "SELECT  FROM f_debitnote_hdr_t $wh ORDER BY $sidx $sord LIMIT $start , $limit";
   $SQL = "SELECT
                        f_debitnote_hdr_t.debitnote_hdr_id,
                        f_debitnote_hdr_t.debit_number,
                        f_debitnote_hdr_t.debit_date,
                        f_debitnote_hdr_t.debit_status,
                        p_po_invoice_hdr_t.bill_number AS invoice_number,
                        p_po_invoice_hdr_t.invoice_date AS invoice_date,
                        p_po_hdr_t.po_number AS po_number,
                        p_po_hdr_t.po_date AS po_date,
                        m_supplier_t.supplier_name AS supplier_id
                        FROM `f_debitnote_hdr_t`
                        LEFT JOIN p_po_hdr_t ON(p_po_hdr_t.po_hdr_id = f_debitnote_hdr_t.`po_number`)
                        LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = f_debitnote_hdr_t.`supplier_id`)
                        LEFT JOIN p_po_invoice_hdr_t ON(p_po_invoice_hdr_t.po_invoice_id = f_debitnote_hdr_t.`invoice_number`)
                        WHERE 1 = 1 AND p_po_invoice_hdr_t.po_invoice_status = 'APPROVED'  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
                $result = \DB::select( $SQL );
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
             
        echo json_encode($responce);
    } 
	
	
     public function poinvoicetable()
    {
        $this->data['suppliername']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name'); 
        $this->data['ponumber']=$this->jqgridselect('p_po_hdr_t','po_hdr_id','po_number'); 
        return view("accountdebitnote.poinvoicetable",$this->data);
    }
    public function poinvoiceData(){
//        dd($_GET['filters']);
		
		
		
		
		
		
		
        $wh='';
        if($_GET['_search']=='true'){
        $wh=$this->jqgridsearch('p_po_invoice_hdr_t',$_GET['filters']);
        }
//        dd($wh);
		
		
		$loc=\Session::get('location');
        $compy=\Session::get('companyid');
		$groupname=\Session::get('groupname');
		if($groupname=='Superadmin' || $groupname=='Admin'){
		$wh.='and p_po_invoice_hdr_t.company_id='.$compy;	
		}else{
			$wh.='and p_po_invoice_hdr_t.company_id='.$compy.' and p_po_invoice_hdr_t.location_id='.$loc;		
		}
		
		
		
		
		
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
//        dd("SELECT COUNT(po_invoice_id) AS count FROM p_po_invoice_hdr_t where 1=1 $wh");
        $result = \DB::select("SELECT COUNT(p_po_invoice_hdr_t.po_invoice_id) AS count FROM p_po_invoice_hdr_t where 1=1  $wh");

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
                $SQL = "SELECT p_po_invoice_hdr_t.po_invoice_id,"
                        . "p_po_invoice_hdr_t.bill_number AS bill_number,"
                        . "p_po_invoice_hdr_t.invoice_date AS invoice_date,"
                        . "p_po_hdr_t.po_number AS po_number,"
                        . "p_po_hdr_t.po_date AS po_date,"
                        . "m_supplier_t.supplier_name AS supplier_id "
                        . "FROM `p_po_invoice_hdr_t`"
                        . "left JOIN p_po_invoice_lines_t ON(p_po_invoice_hdr_t.po_invoice_id = p_po_invoice_lines_t.`po_invoice_id`)"
                        . "LEFT JOIN p_po_hdr_t ON(p_po_hdr_t.po_hdr_id = p_po_invoice_hdr_t.`po_number`)"
                        . "LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_po_invoice_hdr_t.`supplier_id`) "
                        . "WHERE 1 = 1 AND p_po_invoice_hdr_t.po_invoice_status = 'APPROVED' AND p_po_invoice_lines_t.reject_qty!=0  $wh GROUP BY p_po_invoice_hdr_t.po_invoice_id  ORDER BY $sidx $sord LIMIT $start , $limit";
//               dd($SQL);
                $result = \DB::select( $SQL );
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
             
        echo json_encode($responce);
    } 
  public function accountdebitnotecreate($id=null)
    {
    if(isset($id))
    { 
    $this->data['row']=$invoice_table=\DB::table('p_po_invoice_hdr_t')->select('p_po_hdr_t.po_number',
                                                                                'p_po_hdr_t.po_hdr_id',
                                                                                'p_po_hdr_t.po_number',
                                                                                'p_po_hdr_t.po_date',
                                                                                'm_supplier_t.supplier_id',
                                                                                'm_supplier_t.supplier_name',
                                                                                'm_supplier_sites_t.supplier_site_id',
                                                                                'm_supplier_sites_t.supplier_site_name',
                                                                                'p_po_invoice_hdr_t.po_invoice_id',
                                                                                'p_po_invoice_hdr_t.invoice_date',
                                                                                'p_po_invoice_hdr_t.bill_number')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_po_invoice_hdr_t.supplier_id')
                ->leftJoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_site_id', '=', 'p_po_invoice_hdr_t.suppliersite_id')
                ->leftJoin('p_po_hdr_t','p_po_hdr_t.po_hdr_id','=','p_po_invoice_hdr_t.po_number')
                ->where('p_po_invoice_hdr_t.po_invoice_id',$id)->get();
//        dd($invoice_table);
                $this->data['row'][0]->debitnote_hdr_id="";
                $this->data['row'][0]->debit_date=date('Y-m-d');
                $this->data['row'][0]->debit_number ="";
                $this->data['row'][0]->debit_status="DRAFT"; 
                
    $this->data['supplier_id']= $this->jCombo('m_supplier_t','supplier_id','supplier_name',$invoice_table[0]->supplier_id);
    $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t','supplier_site_id','supplier_site_name',$invoice_table[0]->supplier_site_id);
    
//    $invhdrid=$invoice_table[0]->po_invoice_id;
//    dd($invhdrid);
    $this->data['linedata'] = \DB::table('p_po_invoice_lines_t')->where('po_invoice_id',$id)->get();
//    dd( $this->data['linedata']);
            $grandtotal = 0;
	    $taxamount_total =0;
	if(count($this->data['linedata']) >= 1)
            {
            $po_tax_total=0;
            $po_grand_total=0;
            foreach ($this->data['linedata'] as $key => $value) 
            {
                $this->data['linedata'][$key]=(object) array();
                $this->data['linedata'][$key]->debitnote_line_id ="";
		$unitprice = $this->pricelistorder($value->po_invoice_id,$value->product_id);
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product',$value->product_id);   
                $this->data['linedata'][$key]->uom_code_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id); 
                $reject_qty=$value->reject_qty;
                $tax = $this->gettax($value->product_id);
		$taxamount = (($value->reject_qty * $unitprice) * $tax['display_name'] )/ 100;
                $linetotal = ($taxamount + ($value->reject_qty * $unitprice));
        	$po_tax_total += $taxamount;
//               $po_grand_total = $grandtotal + $linetotal;
//                dd($value);
    $this->data['linedata'][$key]->reject_qty =$value->reject_qty; 
    $this->data['linedata'][$key]->unit_price = $unitprice; 
    $this->data['linedata'][$key]->tax_group_id=$this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$tax['taxgroup_id']);
    $this->data['linedata'][$key]->tax_amount=$taxamount;
    $this->data['linedata'][$key]->line_total=$linetotal;
    $this->data['linedata'][$key]->comments="";

//    $this->data['linedata'][$key]->taxgroup_id =  $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$this->taxgroup($value->product_id));
            }
        }
//            $this->data['row'][0]->po_tax_total=$po_tax_total;
//          $this->data['row'][0]->po_grand_total=$po_grand_total;
}
      
      return view('accountdebitnote.form',$this->data);
    }   
   
public function accountdebitnoteedit($id=null){
        if(isset($id)){ 
            $this->data['row']=$debit_table=\DB::table('f_debitnote_hdr_t')->select('p_po_hdr_t.po_number','p_po_hdr_t.po_hdr_id','m_supplier_t.supplier_id','m_supplier_sites_t.supplier_site_id','p_po_hdr_t.po_number','p_po_hdr_t.po_date','f_debitnote_hdr_t.invoice_number','f_debitnote_hdr_t.debit_date','p_po_invoice_hdr_t.po_invoice_id','p_po_invoice_hdr_t.invoice_date','p_po_invoice_hdr_t.bill_number','f_debitnote_hdr_t.debit_number','f_debitnote_hdr_t.debit_status')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'f_debitnote_hdr_t.supplier_id')
                    ->leftJoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_site_id', '=', 'f_debitnote_hdr_t.suppliersite_id')
                ->leftJoin('p_po_hdr_t','p_po_hdr_t.po_hdr_id','=','f_debitnote_hdr_t.po_number')
                ->leftJoin('p_po_invoice_hdr_t','p_po_invoice_hdr_t.po_invoice_id','=','f_debitnote_hdr_t.invoice_number')
                ->where('debitnote_hdr_id',$id)->get();
            
                $this->data['row'][0]->debitnote_hdr_id=$id;
                $this->data['row'][0]->debit_date=$debit_table[0]->debit_date;
          $this->data['row'][0]->debit_number=$debit_table[0]->debit_number;
          $this->data['row'][0]->debit_status=$debit_table[0]->debit_status;
          $this->data['row'][0]->invoice_date =$debit_table[0]->invoice_date ;
          $this->data['supplier_id']= $this->jCombo('m_supplier_t','supplier_id','supplier_name',$debit_table[0]->supplier_id);
          $this->data['suppliersite_id']= $this->jCombo('m_supplier_sites_t','supplier_site_id','supplier_site_name',$debit_table[0]->supplier_site_id);
          $this->data['debit_status'] = $debit_table[0]->debit_status; 
             $tablelines = \DB::table('f_debitnote_lines_t')->where('debitnote_hdr_id',$id)->get();
                $this->data['linedata'] = $tablelines;
//                dd($tablelines);
            if(count($this->data['linedata']) >= 1){
            foreach ($this->data['linedata'] as $key => $value) 
            {
                $this->data['linedata'][$key]->debitnote_line_id=$value->debitnote_line_id;
                $this->data['linedata'][$key]->debitnote_hdr_id=$value->debitnote_hdr_id; 
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product',$value->product_id);   
                $this->data['linedata'][$key]->uom_code_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id); 
                 $this->data['linedata'][$key]->unit_price = $value->unit_price; 
                 $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t','tax_group_id','tax_group_name',$value->tax_group_id);
                 $this->data['linedata'][$key]->tax_amount = $value->tax_amount; 
                 $this->data['linedata'][$key]->line_total = $value->line_total; 
            }
        }
    }
   return view('accountdebitnote.form',$this->data);
}
    public function accountdebitnoteview($id=null)
    {
        if(isset($id))
        { 
          
            $this->data['row']=$debit_table=\DB::table('f_debitnote_hdr_t')->select('p_po_hdr_t.po_number','p_po_hdr_t.po_hdr_id','m_supplier_t.supplier_id','m_supplier_sites_t.supplier_site_id','p_po_hdr_t.po_number','p_po_hdr_t.po_date','p_po_invoice_hdr_t.bill_number','p_po_invoice_hdr_t.invoice_date','f_debitnote_hdr_t.debit_status','f_debitnote_hdr_t.debit_date','f_debitnote_hdr_t.debit_number')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'f_debitnote_hdr_t.supplier_id')
                ->leftJoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_site_id', '=', 'f_debitnote_hdr_t.suppliersite_id')
                ->leftJoin('p_po_hdr_t','p_po_hdr_t.po_hdr_id','=','f_debitnote_hdr_t.po_number')
                ->leftJoin('p_po_invoice_hdr_t', 'p_po_invoice_hdr_t.po_invoice_id', '=', 'f_debitnote_hdr_t.invoice_number')
                ->where('debitnote_hdr_id',$id)->get();
                $this->data['row'][0]->debitnote_hdr_id=$id;
                $this->data['row'][0]->debit_date=$debit_table[0]->debit_date;
                $this->data['row'][0]->debit_number=$debit_table[0]->debit_number;
                $this->data['row'][0]->debit_status=$debit_table[0]->debit_status;
                $this->data['row'][0]->invoice_date=$debit_table[0]->invoice_date;
                $this->data['row'][0]->bill_number=$debit_table[0]->bill_number;
                $this->data['supplier_id']= $this->idname('supplier_name','m_supplier_t','supplier_id',$debit_table[0]->supplier_id);
               $this->data['suppliersite_id']= $this->idname('supplier_site_name','m_supplier_sites_t','supplier_site_id',$debit_table[0]->supplier_site_id);
             $tablelines = \DB::table('f_debitnote_lines_t')->where('debitnote_hdr_id',$id)->get();
            $this->data['linedata'] = $tablelines;
            if(count($this->data['linedata']) >= 1)
        {
            foreach ($this->data['linedata'] as $key => $value) 
            {
               $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->idname('concatenated_product','m_products_t','product_id',$value->product_id);
                $this->data['linedata'][$key]->uom_code_id =  $this->idname('uom_code','m_uom_codes_t','uom_code_id',$value->uom_code_id);  
                    $this->data['linedata'][$key]->reject_qty = $value->reject_qty;
                    $this->data['linedata'][$key]->unit_price = $value->unit_price;
                $this->data['linedata'][$key]->tax_group_id = $this->idname('tax_group_name','m_tax_group_t','tax_group_id',$value->tax_group_id); 
                $this->data['linedata'][$key]->tax_amount = $value->tax_amount;
                $this->data['linedata'][$key]->line_total = $value->line_total;
                $this->data['linedata'][$key]->comments = $value->comments;
                 
            }
        }
}

      return view('accountdebitnote.view',$this->data);
    }
    
    function pricelistorder($po_hdr_id,$p_id){
	$result=\DB::table('p_po_lines_t')->select('unit_price as unit_price')->where('po_hdr_id',$po_hdr_id)->where('product_id',$p_id)->get();
        if($result->isNotEmpty()){
	$unit_price=$result[0]->unit_price;
	}
	else {
	$unit_price= '0.00';
	}
	return $unit_price;
	}
  public function taxgroup($pid=null){
	$pro_details=\DB::table("m_products_t")->select('trx_uom_id','hsn_code')->where('product_id',$pid)->get();
    
        if($pro_details->isNotEmpty()){
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
        public function gettax($pid)
	{
	 $pro_details=\DB::table("m_products_t")->select('trx_uom_id','hsn_code')->where('product_id',$pid)->get();

	if($pro_details->isNotEmpty())
	{
	
	$prd_data['uom_code_id']=$pro_details[0]->trx_uom_id;
	$hsn_code=$pro_details[0]->hsn_code;
	$date=date('Y-m-d');
	$tax=\DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$hsn_code' and start_date<='$date' and end_date>='$date' and active='Yes'");
	   if(!empty($tax))
	   {
	  $prd_data['taxgroup_id']=$tax[0]->tax_group_id; 
	   
	   $tax1=\DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id',$tax[0]->tax_group_id)->get();
	   $prd_data['display_name']=$tax1[0]->display_name;
	   return $prd_data;
	  
	   }
	else
	{
	
	 $prd_data['uom_code_id']=$pro_details[0]->trx_uom_id;
	 $prd_data['taxgroup_id']=0; 
	 $prd_data['display_name']=1;
    	 return $prd_data;
	}
	}
	else
	{
 	 $prd_data['uom_code_id']=0;
	 $prd_data['taxgroup_id']=0; 
	 $prd_data['display_name']=1;
	 return $prd_data;
	}
	}
	
     public function accountdebitnotesave(Request $request)
        {
//        dd($_POST);
                $id='';
                $data = $this->validatePost($request->all(),$this->table,'header');      
                $lines_data = $this->validatePost($request->all(),$this->subtable,'lines'); 
                if ($_POST['debit_number'] ==""){
                        $seqno=$this->Seqno('DB-','f_debitnote_hdr_t',$_POST['debit_number']);
                    }
                    else{
                       $seqno=$_POST['debit_number'];
                    }
                   
                \DB::beginTransaction();
                try
                {
                    $data['debit_number']=$seqno;
                     $id=$this->model->insertRow($data);
                     $lid=$this->submodel->subgridSave($lines_data,$id);
                     
/*Karthigaa Purpose For Journal Entry Insert*/
    if($_POST['debit_status']=="INITIATED"){
                   $debitdate=$_POST['debit_date'];
                   $org=\Session::get('organization');
                    $loc=\Session::get('location');
                    $compy=\Session::get('companyid');
                   $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('DEBIT NOTE','$debitdate','$id','OPEN','$compy','$loc','$org')");
                   $jid = DB::getPdo()->lastInsertId();

                   $invoiceid=DB::table('f_debitnote_lines_t')->where('debitnote_hdr_id',$id)->get();
//                   dd($invoiceid);
                     //<**----Credit Net Account--------------------------------***------>    
                   //TAX
                   $taxamt=0;
                   foreach($invoiceid as $tkey => $tvalue) { 
                       $sum_taxamt=$taxamt+$tvalue->tax_amount;
                   }
                   $supsiteid=$_POST['suppliersite_id'];
                   $state=\DB::select("SELECT state FROM m_supplier_sites_t  WHERE `supplier_site_id`='$supsiteid'");
                   $stateid=$state[0]->state;

              $tax=$_POST['bulk_tax_group_id'];
             $taxgroup= \DB::table('m_tax_group_lines_t')->where('tax_group_id',$tax)->get();
	      foreach($taxgroup as $gkey => $gvalue) {  
                 if($stateid==31){
                   $taxamount=$sum_taxamt/2;
                   $inv_acc1['journal_entry_id']   = $jid; 
                   $inv_acc1['account_id']   =$gvalue->tax_account_id;
                    $inv_acc1['debit_amount']=0;
                    $inv_acc1['credit_amount']   = $taxamount;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc1);
                      }
                  
                   else{
                     $taxamount=$sum_taxamt;
                     $inv_acc1['journal_entry_id']   = $jid; 
                     $inv_acc1['account_id']   =$gvalue->tax_account_id;
                    $inv_acc1['debit_amount']=0;
                    $inv_acc1['credit_amount']   =$taxamount;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc1);  
                   }
              }
               //<**----Credit Net Account--------------------------------***------>                  
            foreach($invoiceid as $key => $value) {
                   $inv_acc['journal_entry_id']   = $jid; 
                        $pid=$value->product_id;
                        $prdacc=\DB::select("select account_code_id from m_products_t where product_id=$pid");
                   $inv_acc['account_id']   =$prdacc[0]->account_code_id;
                    $inv_acc['debit_amount']   =0;
                    $inv_acc['credit_amount']   = $value->line_total-$value->tax_amount;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc);
                   }
            //<**----Debit Account---------***------>                  
            $sum_linetotal=0;
            foreach($invoiceid as $tkey => $tvalue) { 
                $sum_linetotal=$sum_linetotal+$tvalue->line_total;
            }
                   $inv_acc2['journal_entry_id']   = $jid; 
                       $sid=$_POST['supplier_id'];
                       $supacc=\DB::select("select account_structure_id from m_supplier_t where supplier_id=$sid");
                    $inv_acc2['account_id']=$supacc[0]->account_structure_id;
                    $inv_acc2['debit_amount']   = $sum_linetotal;
                    $inv_acc2['credit_amount']   =0;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc2);
         
            }
        /*End*/
                     \DB::commit();
                     return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'lid' => $lid,'auto_no'=>$seqno));
                }
                catch (\Illuminate\Database\QueryException $e)
                {
                     $message = explode('(', $e->getMessage());
                     $dbCode = rtrim($message[0], ']');
                     $dbCode = trim($dbCode, '[');
//                     dd($dbCode);
                     \DB::rollback();
                     return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
                }

        }
   
   
 
  
   
}
