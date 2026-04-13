<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PoreportController extends Controller
{
    
    public function __construct()
	{
	$this->data=array();
$this->data['urlmenu']=$this->indexs(); 
		
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
		$this->data['pageModule']='movetoinventory';
		$this->middleware('auth');
	}
public function index(){
    $po=\DB::select("select * from p_po_hdr_t");
    $po_number=array();
    $grn_number=array();
    $QC_number=array();
    $invoice=array();
    $inventory=array();
    
    foreach($po as $k=>$v){
        $po_number[]=$v->po_number;
       $grn=\DB::select("select p_grn_hdr_t.grn_number,p_grn_hdr_t.grn_id,p_qc_header_t.qc_number,p_po_invoice_hdr_t.bill_number,p_po_invoice_hdr_t.invoice_grand_total from p_grn_hdr_t left join p_qc_header_t on p_qc_header_t.po_number=p_grn_hdr_t.po_number left join p_po_invoice_hdr_t on p_qc_header_t.po_number=p_po_invoice_hdr_t.po_number where p_qc_header_t.po_number='".$v->po_hdr_id."'");
     
       foreach($grn as $key=>$value){
       $grn_number[$v->po_number][]="Grn Number - ".$value->grn_number;
        $QC_number[$v->po_number][]="QC Number - ".$value->qc_number;
        $invoice[$v->po_number][]="Bill Number - ".$value->qc_number." - Invoice Total Amount ".$value->invoice_grand_total;
       $total=\DB::Select("select sum(qoh_trx_qty) as qty from i_qoh_detail_t where batch_number='". $value->grn_id."'");
       $inventory[$v->po_number][]="Move To Inventory Qty - ".$total[0]->qty;
      }
    }
 $this->data['po_number']=$po_number;   
 $this->data['grn_number']=$grn_number;   
 $this->data['qc_number']=$QC_number;   
 $this->data['invoice']=$invoice;   
 $this->data['inventory']=$inventory;   
 
return view('poreport.index',$this->data);
 
}



}
