@extends('layouts.header')
@section('content')

<style type="text/css">
/*styles for removing edit button in sweet alert*/
    .sweet-alert p .apply.btn-lg{
       display: none;
    }
</style>
<?php 
error_reporting(0); if($row->source=='STANDARD' && $row->po_hdr_id=='' && !isset($copy_po_number))
{
    $head=" ( New )";
}
else if($row->po_hdr_id !='')
{
    $head=" ( ".$row->po_number." )";
}
else if(isset($copy_po_number))
{
    $head=" ( Copy From ".$copy_po_number." )";
}
else
{
    $head=" ( Convert From ".$row->source." )";
}
?>
 <?php include('tools_menu.php'); ?> <h3 class="heads">Purchase Order {{$head}}
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ url($return_url) }}"'></a></span>
</h3>
<style type="text/css">
    /*Resoultion For Screen Width  */
@media only screen and (min-width: 1500px) {
    .bulk_line_no {width: 50px !important;}
    .bulk_product_id {width: 280px !important;}
    .bulk_product_description {width: 130px !important;}
    .bulk_uom_code_id {width: 110px !important;}
    .bulk_qty {width: 60px !important;}
    .bulk_received_qty {width: 60px !important;}
    .bulk_unit_price {width: 100px !important;}
    .bulk_discount_percentage {width: 80px !important;}
    .bulk_discount_amount {width: 100px !important;}
    .bulk_tax_group_id {width: 100px !important;}
    .bulk_tax_amount {width: 100px !important;}
    .bulk_hsn_code{width:100px !important;}
    .bulk_part_no {width: 110px !important;}
    .bulk_line_total {width: 100px !important;}
    .bulk_promised_date {width: 100px !important;}
    .bulk_comments {width: 120px !important;}
    .bulk_promised_alternate_date  {width: 105px !important;}
    .bulk_qoh_qty {width: 100px !important;}
    }
    @media only screen and (min-width: 2000px) {
    .bulk_line_no {width: 50px !important;}
    .bulk_product_id {width: 310px !important;}
    .bulk_product_description {width: 230px !important;}
    .bulk_uom_code_id {width: 210px !important;}
    .bulk_qty {width: 120px !important;}
    .bulk_received_qty {width: 120px !important;}
    .bulk_unit_price {width: 100px !important;}
    .bulk_discount_percentage {width: 80px !important;}
    .bulk_discount_amount {width: 100px !important;}
    .bulk_tax_group_id {width: 100px !important;}
    .bulk_tax_amount {width: 100px !important;}
    .bulk_hsn_code{width:100px !important;}
    .bulk_part_no {width: 110px !important;}
    .bulk_line_total {width: 100px !important;}
    .bulk_promised_date {width: 100px !important;}
    .bulk_comments {width: 220px !important;}
    .bulk_promised_alternate_date  {width: 105px !important;}
    .bulk_qoh_qty {width: 100px !important;}
    }
    .bulk_line_no{width: 50px;}
    .bulk_product_id{width: 280px;}
    .bulk_part_no {width: 110px ;}
    .bulk_product_description{width: 120px;}
    .bulk_uom_code_id{width: 110px;}
    .bulk_qty{width: 55px;}
    .bulk_unit_price {width: 75px;}
    .bulk_discount_percentage{width: 75px;}
    .bulk_discount_amount{width: 100px;}
    .bulk_tax_group_id{width: 120px;}
    .bulk_tax_amount{width: 120px;}
    .bulk_hsn_code{width:100px;}
    .bulk_line_total{width: 120px;}
    .bulk_promised_date{width: 100px;}
    .bulk_comments{width: 120px;}
    .bulk_promised_alternate_date{width:105px;}
    .bulk_qoh_qty {width: 100px !important;}
input#choosefile {
    background: #9baff1;
    /* border-radius: 12px; */
    font-size: 11px;
    font-weight: 900;
    width: 14.4em;
    color: #fff;
    padding: 6px 1px;
    /* word-wrap: normal; */
}
#file_choosen{
  margin: 4px 0px;
  width: 10em;
  text-align: center;
    border: 0px solid #375a80;
}

#file_choosen>tbody{
    display: block;
    max-height: 120px;
    overflow-y:auto;

}

span.note,span.note>.files{
    font-size: 13px;
    font-family: sans-serif;

}
span.note img{
  width:16px;
  height: 16px;
}
/* modal jqgrid popup styles  */
.modal-open .ui-jqgrid .ui-jqgrid-pager {
    border-left: 0 none !important;
    border-right: 0 none !important;
    border-bottom: 0 none !important;
    border-top: 0 none;
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    height: auto;
    min-height: 28px;
    white-space: nowrap;
    overflow: hidden;
    /*font-size:11px; */
    z-index: 100
}

.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pager-control,.modal-open 
.ui-jqgrid .ui-jqgrid-pager .ui-pager-control {
    position: relative;
    border-left: 0;
    border-bottom: 0;
    border-top: 0;
    height: 28px;
}

.modal-open .ui-jqgrid .ui-pg-table {
    position: relative;
    padding: 1px 0;
    width: auto;
    margin: 0;
}

.modal-open .ui-jqgrid .ui-pg-table td {
  width: auto !important;
    font-weight: normal;
    vertical-align: middle;
    padding: 0px 1px;
}

.modal-open .ui-jqgrid .ui-pg-button {
    height: auto
}

.modal-open .ui-jqgrid .ui-pg-button span {
    display: block;
    margin: 2px;
    float: left;
}

.modal-open .ui-jqgrid .ui-pg-button:hover {
    padding: 0;
}

.modal-open .ui-jqgrid .ui-state-disabled:hover {
    padding: 0px;
}

.modal-open .ui-jqgrid .ui-pg-input,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-input {
    height: 14px;
    width: auto;
    font-size: .9em;
    margin: 0;
    line-height: inherit;
    border: none;
    padding: 3px 2px
}

.modal-open .ui-jqgrid .ui-pg-selbox,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-selbox {
    font-size: .9em;
    line-height: inherit;
    display: block;
    
    margin: 0;
    padding: 3px 0px;
    border: none;
}

.modal-open .ui-jqgrid .ui-separator {
    height: 18px;
    border-left: 2px solid #ccc;
}

.modal-open .ui-separator-li {
    height: 2px;
    border: none;
    border-top: 2px solid #ccc;
    margin: 0;
    padding: 0;
    width: 100%
}

.modal-open .ui-jqgrid .dropdownmenu {
    padding: 3px 0 3px 0;
    margin-left: 4px;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pg-div,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-div {
    padding: 1px 0;
    float: left;
    position: relative;
    line-height: 20px;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pg-button,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-button {
    cursor: pointer;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pg-div span.ui-icon,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pg-div span.ui-icon {
    float: left;
    margin: 0px;
    width: 18px;
}

.modal-open .ui-jqgrid td input,
.modal-open .ui-jqgrid td select,
.modal-open .ui-jqgrid td textarea {
    margin: 0;
    padding-top: 5px;
    padding-bottom: 5px;
}

.modal-open .ui-jqgrid td textarea {
    width: auto;
    height: auto;
}

.modal-open .ui-jqgrid .ui-jqgrid-toppager {
    width: 100% !important;
    border-left: 0 none !important;
    border-right: 0 none !important;
    border-top: 0 none !important;
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    white-space: nowrap;
    overflow: hidden;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-pager-table,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-pager-table {
    width: 100%;
    margin-top: 1px;
    table-layout: fixed;
    /*height: 100%;*/
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-paging-info,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-paging-info {
    font-weight: normal;
    height: auto;
    margin-top: 3px;
    margin-right: 4px;
    display: inline;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-paging-pager,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-paging-pager {
    table-layout: auto;
    height: 100%;
}

.modal-open .ui-jqgrid .ui-jqgrid-pager .navtable,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .navtable {
    float: left;
    table-layout: auto;
    /*height: 100%;*/
}
@media only screen and (min-width: 1700px) {
.modal-open .ui-jqgrid .ui-jqgrid-pager .ui-paging-info,
.modal-open .ui-jqgrid .ui-jqgrid-toppager .ui-paging-info {
    font-weight: normal;
    height: auto;
    margin-top: 3px;
    margin-right: -25em;
    display: inline;
}
}


</style>
<!-- karthigaa purpose supplier search jqgrid model-->
<div class="modal fade" id="supplierModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
		<!--Moda Header-->
      <div class="modal-header">
		  <h4 class="modal-title"> Supplier Details </h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
		<!-- Modal Body -->
	  <div class="modal-body">
	      <table id="suppliergrid"></table>
	  </div>
		 <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<!--end-->
<div class="ajaxLoading"></div>
<form method="post" action="" id="po_form" class="po_form" data-parsley-validate>
{{ csrf_field() }}
<div class="card">
<div class="card-header">
    <div class="col-md-12">
        
        <div class="col-md-3">
            <label class="align_left"> PO Date:<?php echo date(\Session::get('p_date_format'),strtotime($row->po_date));?></label></br>
            <label class="align_left">PO Type:{{ $row->po_type }}   </label>
        </div>
        <div class="col-md-3">
        <label class="align_left">Reference No.:{{$row->reference_number}} <?php if($ids=='1'){?> <i class="fa fa-plus viewquote"></i><?php }?></label>    
        <label class="align_left">  Source:<span class='source_span span_color'>{{$row->source}}</span></label>
        </div>
        <div class="col-md-3">
            <label class="align_left">  PO Tax Total:<span class='tax_total_span span_color' id="tax_total_span">{{ $row->po_tax_total }}</span></label></br>
            <label class="align_left">PO Grand Total:<span class='grand_total_span span_color' id="grand_total_span">{{ $row->po_grand_total }}</span>   </label>        
	</div>

        <div class="col-md-3">
            <label class="align_left">Created By:<span class='create_by span_color'></span></label></br>
        
        </div>
       </div>
</div>
<div class="card-body card-block">
	<!------------------------------------- Body content start here ---------------------------->
<div class="row">
<div class="row">
<div class="col-md-12">
<div class="col-md-4 form-group row" style="display:none;">
<label for="inputIsValid" class="form-control-label col-md-4">PO Number</label>
<div class="col-md-6">
	<input class="form-control po_hdr_id" id="po_hdr_id" name="po_hdr_id" size="16" type="hidden" value="{{ $row->po_hdr_id }}" readonly>
	<input type="text" id="po_number" name="po_number" class="form-control po_number" value="{{ $row->po_number }}" readonly>
</div>
<div class="col-md-2">

</div>
</div>

        <div class="col-md-4 form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;" >&#42;</span>Supplier Name</label>
            <div class="col-md-6 supplier_div">

                <select id="suppliername" name='supplier_id' rows='5'  class='form-control supplier_id select2' tabindex="1" data-show-subtext="true" data-live-search="true" required>
                    {!! $supplier_id !!}
            </select>
            </div>
            <div class="col-md-2 showinline ichide">
            <span class="showspan"><i class="fa fa-search suppliersearch"></i> </span>
            <span class="showspan"><i class="fa fa-refresh jcr_supplier_id"></i></span>
            </div>
        </div>
    
        <div class="form-group col-md-4 ">
            <label for="inputIsValid" class="form-control-label col-md-4">Supplier Site </label>
            <div class="col-md-6 ssite_div">
                    <select name='suppliersite_id' rows='5' tabindex="2" class='suppliersite_id select2'>
            {!! $suppliersite_id !!}
                    </select>
            </div>
            <div class="col-md-2 showinline ichide">
            <span class="showspan"><i class="fa fa-refresh jcr_suppliersite_id"></i></span>
            </div>
        </div>
        <div class="form-group col-md-4 row">
                                <label for="inputIsValid" class="form-control-label col-md-6">Is this transaction applicable for reverse charge?</label>
                           <div class="col-md-6">
                                        <div class="c-checkbox">
                                                <input type="checkbox" name="reverse_charge[]" value="1" <?php if($row->reverse_charge =="1") { echo "checked"; } else { echo ""; } ?> class="reverse_charge">
                                                <span class="check_mark"></span>
                                        </div>
                          </div>
        </div>
</div>
			<div class="col-md-12">
            <div class="col-md-4 form-group row">
                <label for="delivery_date" class="form-control-label col-md-4">Delivery Date</label>
                <div class="col-md-6 delivery_date_div">
                <input type='text' name="delivery_date" id="delivery_date" rows='5' tabindex="3" class='form-control delivery_date datepicker' data-link-format="yyyy-mm-dd" value="{{$row->delivery_date}}">
                </div>
                <div class="col-md-2">
                </div>
            </div>
            <div class="col-md-4 form-group">  
                <label for="inputIsValid" class="form-control-label col-md-4"><span class="reqstar" style="color:red;" >&#42;</span>Pricelist Term</label>
                <div class="col-md-6 pricelist_div" id="pricediv">
                <select name='po_pricelist_id' rows='5' tabindex="4" class='po_pricelist_id select2' required>
                        {!! $po_pricelist_id !!}
                </select>
                </div>
                <div class="col-md-2 showinline ichide">
                </div>
            </div> 

<div class="col-md-4 form-group ">
<label class="form-control-label col-md-4" for="customer_id">File Upload</label>
  <div class="col-md-4 ">
            
       
		<?php 
              
                if($return_url == "poapproval")
                    { $link ="download"; 
                  
                    }
                    else
                    {
                        $link='';
                    }
                
                if($pageMethod=="purchaseorder" && $row->po_hdr_id == '') 
		{  
		?>    
	  <input id="choosefile" class="GetFileSizeNameAndType" name="choosefile[]"  type="file" onchange="example()"  multiple/>
	  <table id="file_choosen" border="1" >
		  <tbody id="fp">

		  </tbody>
	  </table>
		<?php  
		} 
		else
		{   ?>
		   <input id="choosefile" class="GetFileSizeNameAndType" name="choosefile[]"  type="file" onchange="example()" multiple/>
	  <table id="file_choosen" border="1" >
		  <tbody id="fp">

				<?php 

					if($row->attachfile_name != "" || $row->attachfile_name != NULL) {
					$dataupload=json_decode($row->attachfile_name);
						?>
			  <input type="hidden" value="{{implode(",",$dataupload)}}" name="existing_file"  id="existing_file" />

			   <?php
                if($ids == 1 || $ids == 3)  $link = "download"; else $link="";
			   foreach($dataupload as $k=>$v)
				{ ?>
			  <tr>
                              <td><span class="note" ><br /> File:<span class="files"><a {{$link}} href="{{URL::to('')}}/Uploads/purchaseorder/PO{{ $row->po_hdr_id }}/{{$v}}">{{$v}}</a></span>&nbsp;<img src="{{URL::to('')}}/images/cancel.png" data-value="{{$v}}" class="delete_user"></span></td></tr>

		<?php } }else{ ?>

		<?php } }
?>

		  </tbody>
	  </table>

  </div>
  <div class="col-md-2">

  </div>
</div>

<div class="form-group row" style="display:none;">
<label for="inputIsValid" class="form-control-label col-md-4"> PO Date</label>
<div class="col-md-6">
<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
	<input class="form-control po_date datepicker" id="po_date"  name="po_date" size="16" type="text" value="{{ $row->po_date }}" readonly>
	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
</div>
<input type="hidden" id="po_date" value="{{ $row->po_date }}" />
</div>
<div class="col-md-1 showline">
</div>
</div>

<div class="form-group row" style="display:none;">
<label for="inputIsValid" class="form-control-label col-md-4">PO Type</label>
<div class="col-md-6">
<select type="text" name="po_type" id="po_type" class="form-control po_type" readonly>
	<option value="">--select--</option>
	<option <?php if($row->po_type =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
	<option <?php if($row->po_type =="LABOUR") { echo "selected"; } else { echo ""; } ?> value="LABOUR">LABOUR</option>
</select>
</div>
<div class="col-md-2">
</div>
</div>

<div class="form-group row" style="display:none;">
<label for="inputIsValid" class="form-control-label col-md-4">PO Status</label>
<div class="col-md-6">
<select type="text" name="po_status" id="po_status" class="form-control po_status" readonly>
	<option value="">--Please Select--</option>
	<option <?php if($row->po_status =="DRAFT") { echo "selected"; } else { echo ""; } ?> value="DRAFT">DRAFT</option>
	<option <?php if($row->po_status =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
	<option <?php if($row->po_status =="APPROVED") { echo "selected"; } else { echo ""; } ?> value="APPROVED">APPROVED</option>
	<option <?php if($row->po_status =="REJECTED") { echo "selected"; } else { echo ""; } ?> value="REJECTED">REJECTED</option>
	<option <?php if($row->po_status =="CANCELLED") { echo "selected"; } else { echo ""; } ?> value="CANCELLED">CANCELLED</option>
	<option <?php if($row->po_status =="CLOSED") { echo "selected"; } else { echo ""; } ?> value="CLOSED">CLOSED</option>
	<option <?php if($row->po_status =="COMPLETED") { echo "selected"; } else { echo ""; } ?> value="COMPLETED">COMPLETED</option>
</select>
</div>
</div>
<div class="form-group row" style="display:none;">
<label for="inputIsValid" class="form-control-label col-md-4">Amendment Status</label>
<div class="col-md-6">
<select type="text" name="amendment_status" id="amendment_status" class="form-control amendment_status" readonly>
	<option value="">--Please Select--</option>
	<option <?php if($row->amendment_status =="1") { echo "selected"; } else { echo ""; } ?> value="1">INITIATED</option>
	
</select>
</div>
</div>

                            


    

    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Organization Name</label>
        <div class="col-md-6">
            <select name='organization_id' rows='5' tabindex="6" class='form-control organization_id'>
                {!! $organization_id !!}
            </select>
        </div>
        <div class="col-md-2 showinline">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Created By</label>
        <div class="col-md-6">
            <select name='created_by' rows='5' class='form-control created_by' tabindex="7" data-show-subtext="true" data-live-search="true">
                {!! $created_by !!}
            </select>
        </div>
        <div class="col-md-2 showline">
        </div>
    </div>


    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Reference No</label>
        <div class="col-md-6">
            <input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{{$row->reference_id}}" readonly>
            <input type="text" id="reference_number" name="reference_number" class="form-control reference_number" value="{{$row->reference_number}}" readonly>
        </div>
        <div class="col-md-2">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">Source</label>
        <div class="col-md-6">
            <select name='source' rows='5' class='form-control source' data-show-subtext="true" data-live-search="true" readonly>
                <option value="">--select--</option>
                <option <?php if($row->source =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
                <option <?php if($row->source =="REQUISITION") { echo "selected"; } else { echo ""; } ?> value="REQUISITION">REQUISITION</option>
                <option <?php if($row->source =="ENQUIRY") { echo "selected"; } else { echo ""; } ?> value="ENQUIRY">ENQUIRY</option>
                <option <?php if($row->source =="QUOTATION") { echo "selected"; } else { echo ""; } ?> value="QUOTATION">QUOTATION</option>
                <option <?php if($row->source =="PO") { echo "selected"; } else { echo ""; } ?> value="PO">PO</option>

            </select>
        </div>
        <div class="col-md-2">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">PO Tax Total</label>
        <div class="col-md-6">
            <input type="text" name="po_tax_total" id="po_tax_total" value="{{ $row->po_tax_total }}" class="form-control po_tax_total" readonly>
        </div>
        <div class="col-md-2">
        </div>
    </div>
    <div class="form-group row" style="display:none;">
        <label for="inputIsValid" class="form-control-label col-md-4">PO Grand Total</label>
        <div class="col-md-6">
            <input type="text" name="po_grand_total" id="po_grand_total" value="{{ $row->po_grand_total }}" class="form-control po_grand_total" readonly>
        </div>
        <div class="col-md-2">
        </div>
    </div>

                                </div>
</div>

</div>
<div class="row">

				<div class="col-md-12">
                    <h5 class="myheaders">Additional Details</h5>



    <?php $i=0; $j=0; foreach($enabled_columns as $index=>$val) {   if($val->action=='1') $required="required"; else $required='';?>
        <?php if($i!=$j) { $j=$i;?>

    <?php } ?>
        <?php if($val->column_name=='payment_term_id' && $val->active==1) { $i++; ?>
            <div class="form-group col-md-4">
                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Payment Term</label>
                <div class="col-md-6 payment_div">
                    <select name='payment_term_id' rows='5' <?php echo $required;?> tabindex="7" class='form-control payment_term_id select2' data-show-subtext="true" data-live-search="true" required > {!! $payment_term_id !!}
                    </select>
                </div>
                <div class="col-md-2 showinline ichide">
                    <span class="showspan"><i class="fa fa-refresh jcr_payment_term_id"></i></span>
                </div>
            </div>
            <?php } ?>

 

           <?php if($val->column_name=='freight_terms_id' && $val->active==1) { $i++; ?>
            <div class="form-group col-md-4">
                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Freight Term</label>
                <div class="col-md-6 payment_div">
                    <select name='freight_terms_id' rows='5' <?php echo $required;?> tabindex="8" class='form-control freight_terms_id select2' data-show-subtext="true" data-live-search="true" >{!! $freight_terms_id !!}
                    </select>
                </div>
                <div class="col-md-2 showinline ichide">
                    <span class="showspan"><i class="fa fa-refresh jcr_freight_terms_id"></i></span>
                </div>
            </div>
            <?php } ?>
          <?php if($val->column_name=='freight_carrier_id' && $val->active==1) { $i++; ?>
            <div class="form-group col-md-4">
                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Freight Carriers</label>
                <div class="col-md-6 payment_div">
                    <select name='freight_carrier_id' rows='5' <?php echo $required;?> tabindex="9" class='form-control freight_carrier_id select2' data-show-subtext="true" data-live-search="true"  >{!! $freight_carrier_id !!}
                    </select>
                </div>
                <div class="col-md-2 showinline ichide">
                    <span class="showspan"><i class="fa fa-refresh jcr_freight_carrier_id"></i></span>
                </div>
            </div>
            <?php } ?>
                <?php if($val->column_name=='delivery_terms_id' && $val->active==1) { $i++; ?>
                    <div class="form-group col-md-4">
                        <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Delivery Term</label>
                        <div class="col-md-6 delivery_div">
                            <select name='delivery_terms_id' rows='5' <?php echo $required;?>  tabindex="6" class='form-control delivery_terms_id select2' data-show-subtext="true" data-live-search="true" required > {!! $delivery_terms_id !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline ichide">
                            <span class="showspan"><i class="fa fa-refresh jcr_delivery_terms_id"></i></span>
                        </div>
                    </div>
                    <?php } ?>

                        <?php if($val->column_name=='project_id' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Project Name</label>
                                <div class="col-md-6 project_div">
                                    <select name='project_id' tabindex="5" rows='5' <?php echo $required;?> class='form-control project_id select2' data-show-subtext="true" data-live-search="true" > {!! $project_id !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline ichide">
                                    <span class="showspan"> <i class="fa fa-refresh jcr_project_id"></i></span>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if($val->column_name=='currency' && $val->active==1){ $i++; ?>
                    
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Currency</label>
                                <div class="col-md-6 project_div">
                                    <select name='currency' rows='5' tabindex="10" <?php echo $required;?> class='form-control currency select2 currency_jcombo' data-show-subtext="true" data-live-search="true" > {!! $currency !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline currency">
                                  <span class="showspan"> <i class="fa fa-refresh jcr_currency_id"></i></span>
                                </div>
                            </div>
                            
                        <?php } ?>
                    <?php if($val->column_name=='supplier_reference_no' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Supplier Ref No</label>
                                <div class="col-md-6 project_div">
                                    <input  name='supplier_reference_no' tabindex="16" rows='5' type="text" id="supplier_reference_no" class="form-control supplier_reference_no" value="{{$row->supplier_reference_no}}">
                                </div>
                                <div class="col-md-2 showinline">
                                   
                                </div>
                            </div>
                                     <?php } ?>
                    
                   
                    
                    

                        <?php if($val->column_name=='bill_to_address_id' && $val->active==1) { $i++; ?>
                    <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Bill to Address</label>
                                <div class="col-md-6 project_div">
                                    <textarea  name='bill_to_address'  rows='5'  id="bill_to_address" <?php echo $required;?> tabindex="12" class="form-control bill_to_address" readonly >{{$bill_to_address}}</textarea>
                                </div>
                                <div class="col-md-2 showinline">
                                   
                                </div>
                            </div>
                            
                                     <?php } ?>
                     <?php if($val->column_name=='ship_to_address_id' && $val->active==1) { $i++; ?>
                    <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Ship to Address</label>
                                <div class="col-md-6 project_div">
                                    <textarea  name='ship_to_address' rows='5'  id="ship_to_address" <?php echo $required;?> tabindex="14" class="form-control ship_to_address" readonly>{{$ship_to_address}}</textarea>
                                </div>
                                <div class="col-md-2 showinline">
                                   
                                </div>
                            </div>
                            
                                     <?php } ?>




                        <?php if($val->column_name=='transport_charges' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Transport Charges</label>
                                <div class="col-md-6 ">
                                    <input type="text" name="transport_charges" tabindex="20" id="transport_charges" <?php echo $required;?> value="{{ $row->transport_charges }}" class="form-control transport_charges charges" readonly>
                                <input type="hidden" name="packing_charges_tax" id="packing_charges_tax" value="{{ $row->packing_charges_tax}}" class="form-control  packing_charges_tax">
                                <input type="hidden" name="insurance_charges_tax" id="insurance_charges_tax" value="{{ $row->insurance_charges_tax}}" class="form-control  insurance_charges_tax"  >       
                                <input type="hidden" name="transport_charges_tax" id="transport_charges_tax" value="{{ $row->transport_charges_tax}}" class="form-control  transport_charges_tax"   >      
                                <input type="hidden" name="unloading_charges_tax" id="unloading_charges_tax" value="{{ $row->unloading_charges_tax}}" class="form-control  unloading_charges_tax">                    

                                </div>
                                <div class="col-md-2 showinline ">
                                    <span class="showspan  packingtax" data-value="Transport Charges" data-at="2">  <i class="fa fa-plus"></i></span>
                                </div>
                            </div>

                                     <?php } ?>
                        <?php if($val->column_name=='unloading_charges' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Unloading Charges</label>
                                <div class="col-md-6">
                                    <input type="text" name="unloading_charges" tabindex="19" id="unloading_charges" <?php echo $required;?> value="{{ $row->unloading_charges }}" class="form-control unloading_charges charges" readonly>
                                </div>
                                <div class="col-md-2 showinline ">
                                    <span class="showspan  packingtax" data-value="Unloading Charges" data-at="6">  <i class="fa fa-plus"></i></span>
                                </div>
                            </div>

                             <?php } ?>
                        <?php if($val->column_name=='insurance_charges' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Insurance Charges</label>
                                <div class="col-md-6">
                                    <input type="text" name="insurance_charges" tabindex="18" id="insurance_charges" <?php echo $required;?> value="{{ $row->insurance_charges }}" class="form-control insurance_charges charges" readonly>
                                </div>
                                <div class="col-md-2 showinline ">
                                    <span class="showspan  packingtax" data-value="Insurance Charges" data-at="3">  <i class="fa fa-plus"></i></span>
                                </div>
                            </div>

                      <?php } ?>
                       
                        <?php if($val->column_name=='packing_charges' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Packing Charges</label>
                                <div class="col-md-6">
                                    <input type="text" name="packing_charges" tabindex="17" id="packing_charges" <?php echo $required;?> value="{{ $row->packing_charges }}" class="form-control packing_charges charges" readonly>
                                </div>
                                <div class="col-md-2 showinline ">
                                    <span class="showspan  packingtax" data-value="Packing Charges" data-at="1">  <i class="fa fa-plus"></i></span>
                                </div>
                            </div>
                        <!--</div>-->
         <?php } ?>
                        <?php if($val->column_name=='ship_to_location_id' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Ship To Location</label>
                                <div class="col-md-6 shiftoloc_div">
                                    <select name='ship_to_location_id' data-location="2" tabindex="13"  rows='5' <?php echo $required;?> class='form-control ship_to_location_id select2' data-show-subtext="true" data-live-search="true" > {!! $ship_to_location_id !!}
                                    </select>
                                </div>
                                 <div class="col-md-2 showinline ichide">
                            <span class="showspan"><i class="fa fa-refresh jcr_ship_to_location_id"></i></span>
                        </div>
                            </div>

     <?php } ?>
					<?php if($val->column_name=='default_payment_method_id' && $val->active==1) { $i++; ?>
                    <div class="form-group col-md-4">
                        <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Payment Method</label>
                        <div class="col-md-6 delivery_div">
                            <select name='default_payment_method_id' tabindex="15" rows='5' <?php echo $required;?> class='form-control default_payment_method_id select2' data-show-subtext="true" data-live-search="true" >
                            {!! $default_payment_method_id !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline ichide">
                            <span class="showspan"><i class="fa fa-refresh jcr_default_payment_method_id"></i></span>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($val->column_name=='insurance_term_id' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Insurance Term</label>
                                <div class="col-md-6 insurance_div">
                                 <select name='insurance_term_id' rows='5'  tabindex="9" class='form-control insurance_term_id select2' data-show-subtext="true" data-live-search="true" > {!! $insurance_term_id !!}
                                    </select>
                                </div>
                               <div class="col-md-2 showinline ichide">
                                    <span class="showspan"> <i class="fa fa-refresh jcr_insurance_term_id"></i></span>
                                </div>
                            </div>
                    <?php } ?>


                        <?php if($val->column_name=='bill_to_location_id' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Bill To Location</label>
                                <div class="col-md-6 billtoloc_div">
                                 <select name='bill_to_location_id' data-location="1" rows='5' tabindex="11" <?php echo $required;?> class='form-control bill_to_location_id select2' data-show-subtext="true" data-live-search="true" > {!! $bill_to_location_id !!}
                                    </select>
                                </div>
                                <div class="col-md-2 showinline ichide">
                                    <span class="showspan"><i class="fa fa-refresh jcr_bill_to_location_id"></i></span>
                                </div>
                            </div>
    
                           
    
    

         <?php } ?>
     
                        <?php if($val->column_name=='other_frieght_amount' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Other Freight Amount</label>
                                <div class="col-md-6">
                                    <input type="text" name="other_frieght_amount" tabindex="21" id="other_frieght_amount" <?php echo $required;?> value="{{ $row->other_frieght_amount }}" class="form-control other_frieght_amount charges" readonly>
                                    <input type="hidden" name="other_frieght_amount_tax" id="other_frieght_amount_tax" value="{{ $row->other_frieght_amount_tax}}" class="form-control  other_frieght_amount_tax">                    
                                </div>
                                <div class="col-md-2 showinline">
                                    <span class="showspan  packingtax" data-value="Other Freight Amount" data-at="5">  <i class="fa fa-plus"></i></span>
                                </div>
                            </div>
                            <!--</div>-->
                            <?php } ?>
          <?php if($val->column_name=='other_tax_amount' && $val->active==1) { $i++; ?>
                            <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?> <span style="color:red;">*</span><?php } ?>Other Tax Amount</label>
                                <div class="col-md-6">
                                    <input type="text" name="other_tax_amount" tabindex="22" id="other_tax_amount" <?php echo $required;?> value="{{ $row->other_tax_amount }}" class="form-control other_tax_amount charges" readonly>
                                    <input type="hidden" name="other_tax_amount_tax" id="other_tax_amount_tax" value="{{ $row->other_tax_amount_tax}}" class="form-control  other_tax_amount_tax">         
                                </div>
                                <div class="col-md-2 showinline">
                                    <span class="showspan  packingtax" data-value="Other Tax Amount" data-at="4">  <i class="fa fa-plus"></i></span>
                                </div>
                            </div>
                            </div>
                            <?php } ?>

                             <?php if($val->column_name=='remarks' && $val->active==1) { $i++; ?>
                                    <div class="form-group col-md-4">
                                        <label for="inputIsValid" class="form-control-label col-md-4"><?php if($required != '' ) { ?>  <span class='req' style="color:red;">*</span><?php } ?> <span class='req' style="color:red;">*</span>  Remarks</label>
                                        <div class="col-md-6"> <?php //dd($row->remarks); ?>
                                            <input type="text" name="remarks" tabindex="7" id="remarks" value="{{ $row->remarks }}" class="form-control remarks">
                                        </div>
                                        <div class="col-md-2">
                                        </div>
                                    </div>
    <?php }  }?>


</div>
</div>



<div class="row"  style="margin-top: 22px;">
<div class="col-md-12">

<a href="javascript:void(0);"  class="add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>



 <div id="preview-area" class="chandru">
    <table class="overflow-y preview po_table">

<thead>
<tr>
    <th>Line No</th>
    <th class="pdtdiv" >Product</th>
      <th >&nbsp;</th>
      <th class="hidepart">Supplier Part No</th>
    <th class="pdtdes_div" >Product Description</th>
    <th>Uom Code </th>
<th>Qty</th>
<?php if($ids=='3') { ?>
<th>Received Qty</th>
<?php } ?>
<th></th>
<th>Price</th>
<th>Discount(%)</th>
<th>Discount Amount</th>
<?php if($row->po_type=="STANDARD"){?>
<th> HSN Code </th>
<?php } else{?>
<th> SAC Code </th>
<?php }?>
<th>Tax Group</th>
<th>Tax Amount</th>
<th>Line Total</th>
<th>Qoh Qty</th>
<th>Promised Date</th>
<th>Promised Alternate Date</th>
<th>Comments</th>
<th></th>

</tr>
</thead>
<tbody class="po_lines_body">
<?php if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)

<tr class="rcopy clone" class="approve">
    <td>
        <input type="hidden" name="bulk_po_line_id[]" class="form-control input-sm bulk_po_line_id" value="{{ $value->po_line_id }}">
        <!--<input type="hidden" class="form-control input-sm bulk_min_order" value="">-->
        <!--<input type="hidden" class="form-control input-sm bulk_max_order" value="">-->
    </td>
    <td>
        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
    </td>
    <td class="pdtdiv">
        <select name="bulk_product_id[]" id="bulk_product_id" class="select2 bulk_product_id  parsley-validated" required="required">{!! $value->product_id !!}</select>
    </td>


  <td><i class="fa fa-search productsearch"></i></td>
  <td class="partno hidepart" style="pointer-events:none">
        <select name="bulk_part_no[]" class="select2 bulk_part_no ">{!! $value->part_no !!}</select>
  </td>
  <td class="pdtdes_div">
        <input type="text" name="bulk_product_description[]" class="form-control input-sm bulk_product_description" value="{{ $value->product_description }}">
  </td>
    
    <td class="uomdiv">
        <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id">
            {!! $value->uom_code_id !!}
        </select>
    </td>
    <td>
        <input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty " value="{{ $value->qty }}" required="required">
    </td>
    <?php if($ids=="3")  { ?>
        <td>
            <input type="text" class="form-control input-sm bulk_received_qty " value="{{$value->received_qty}}" required="required">
        </td>
        <?php }  ?>
        <td><i class="fa fa-rupee productprice"></i></td>
            <td>
                <input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price " value="{{ $value->unit_price }}" readonly required="required">
            </td>
            <td>
                <input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage " value="{{ $value->discount_percentage }}">
            </td>
            <td>
                <input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount " value="{{ $value->discount_amount }}">
            </td>
	 <td class="hsn">
            <select name="bulk_hsn_code[]" id="bulk_hsn_code" class="select2 bulk_hsn_code" required  > {!! $value->hsn_code !!}</select>
        </td>
            <td class="">
                <select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="select2 bulk_tax_group_id" required="required">
                    {!! $value->tax_group_id !!}
                </select>
            </td>
            <td>
                <input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount " value="{{ $value->tax_amount }}" required="required">
            </td>
            <td>
                <input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total " value="{{ $value->line_total }}" required="required">
            </td>
            <td><input type="text" name="bulk_qoh_qty[]" class="form-control  bulk_qoh_qty" value="{{ $value->qoh_qty }}" readonly ></td>
            <td class="prodate">
                <input type="text" name="bulk_promised_date[]" class="form-control datepicker input-sm bulk_promised_date"  required value="{{ $value->promised_date }}">
            </td>
			<td class="prodate">
                <input type="text" name="bulk_promised_alternate_date[]" class="form-control datepicker input-sm bulk_promised_alternate_date" value="{{ $value->promised_alternate_date }}">
            </td>
            <td>
                <textarea name="bulk_comments[]" class="form-control input-sm bulk_comments ">{{ $value->comments }}</textarea>
            </td>

            <td>
                <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                <input type="hidden" name="counter[]">
            </td>
</tr>
@endforeach
<?php } if(count($linedata) < 1 ) {  ?>
    <tr class="rcopy clone">
        <td>
            <input type="hidden" name="bulk_po_line_id[]" class="form-control input-sm bulk_po_line_id" value="">
			 <!--<input type="hidden" class="form-control input-sm bulk_min_order" value="">-->
        <!--<input type="hidden" class="form-control input-sm bulk_max_order" value="">-->
        </td>
        <td>
            <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
        </td>
        <td class="pdtdiv">
            <select name="bulk_product_id[]" id="bulk_product_id" class="select2 bulk_product_id  parsley-validated" required="required">{!! $product_id!!}</select>
        </td>
        <td><i class="fa fa-search productsearch"></i></td>
       <td class="partno hidepart" style="pointer-events:none">
        <select name="bulk_part_no[]" class="select2 bulk_part_no ">{!! $part_no !!}</select>
    </td>
        <td class="pdtdes_div">
            <input type="text" name="bulk_product_description[]" class="form-control input-sm bulk_product_description " value="">
        </td>
        <td class="uomdiv">
            <select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id">
                {!! $uom_code_id !!}
            </select>
        </td>
        <td>
            <input type="text" name="bulk_qty[]" class="form-control bulk_qty " value="" required="required">
        </td>
        <?php if($ids =="3")  { ?>
            <td>
                <input type="text" name="bulk_received_qty[]" class="form-control input-sm bulk_received_qty " value="" required="required">
            </td>
            <?php }  ?>
            <td><i class="fa fa-rupee productprice"></i></td>
                <td>
                    <input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price " readonly value="" required="required">
                </td>
                <td>
                    <input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage "  value="">
                </td>
                <td>
                    <input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount " value="">
                </td>
 <td class="hsn">
            <select name="bulk_hsn_code[]" id="bulk_hsn_code" class="select2 bulk_hsn_code" required>{!! $hsn_code !!}</select>
        </td>
                <td class="">
                    <select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="select2 bulk_tax_group_id" required="required">
                        {!! $tax_group_id !!}
                    </select>
                </td>
                <td>
                    <input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount" value="">
                </td>
                <td>
                    <input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total" value="" required="required">
                </td>
                <td><input type="text" name="bulk_qoh_qty[]" class="form-control  bulk_qoh_qty" value="" readonly></td>
                <td class="prodate">
                    <input type="text" name="bulk_promised_date[]" class="form-control datepicker input-sm bulk_promised_date" required  value="">
                </td>
				<td class="prodate">
                <input type="text" name="bulk_promised_alternate_date[]" class="form-control datepicker input-sm bulk_promised_alternate_date" value="{{ $value->po_alternate_date }}">
            </td>
                <td>
                    <textarea name="bulk_comments[]" class="form-control input-sm bulk_comments" value=""></textarea>
                </td>

                <td>
                    <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                    <input type="hidden" name="counter[]">
                </td>
    </tr>


<?php } ?>
</tbody>
</table>
<input type="hidden" name="enable-masterdetail" value="true">
<input  type="hidden" class="form-control input-sm bulk_hidden_date datepicker" value="">
</div>

</div>
</div>

<!-------------------------Linedata End-------------------------------->
<div class="row">
	<div class="col-lg-12 col-md-12">
		<input type="hidden" name="submit_type" class="submit_type" value="" />
		<div class="form-group text-center actionbtn">
                   <?php if($ids=="2")
                    { ?>
            <button name="apply" type="button" class="btn saveform applychanges draft" value="APPLYCHANGES">Draft</button>
           <!-- <button type="button" class="btn save saveform" value="DRAFT">Draft</button>-->
           <button name="submit" type="button" class="btn save saveform" value="SAVE">Submit</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVENEW">Submit and New</button>
            
             <?php }
              else if($ids=="4")
                    { ?>
            <button name="apply" type="button" class="btn saveform applychanges draft" value="APPLYCHANGES">Draft</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVE">Submit</button>
             <button name="submit" type="button" class="btn save saveform" value="SAVENEW">Submit and New</button>
            
             <?php } else if ($ids=="3") { ?>
			          <button   type="button" class="btn save saveform cancel" value="Canceled">Po Cancel</button>
            <?php } else if($ids=="1"){  ?>
                        <button   type="button" class="btn save saveform" value="Approved">Approve</button>
			<button   type="button" class="btn save saveform reject" value="Rejected">Reject</button>
             <?php  } else {  ?>
                       <button name="apply" type="button" class="btn saveform applychanges draft" value="APPLYCHANGES">Draft</button>
            <!--<button type="button" class="btn save saveform" value="DRAFT">Draft</button>-->
            <button name="submit" type="button" class="btn save saveform" value="SAVE">Submit</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVENEW">Submit and New</button>
            
                   <?php  } ?>
            <a class='btn cancel' onclick='location.href="{{ url($return_url) }}"'>Cancel</a>
		</div>
	</div>
</div>


</div>

</div>
</div>


	<!-- karthigaa purpose Product search jqgrid model-->
<div class="modal fade" id="productModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
		<!--Moda Header-->
      <div class="modal-header">
		  <h4 class="modal-title"> Product Details </h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
		<!-- Modal Body -->
	  <div class="modal-body">
	      <table id="productgrid"></table>
	  </div>
		 <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<!--product price -->
<div id="productprice" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Product Price</h4>
</div>
<div class="modal-body ">
<div class="table-responsive">
<table class="table">
<thead>
<th>Supplier Name</th>
<th>Date</th>
<th>PO Number</th>
<th>Unit Price</th>
<th>Product</th>
</thead>
<tbody class="mcontent5">
</tbody>
</table>
</div>


</div>

</div>

</div>
</div>
	<!--end-->
        
        <!-- karthigaa purpose other Charges modal-->
<div class="modal fade" id="taxModal"  style="overflow-y:hidden;"> 
  <div class="modal-dialog" style="width:100%;">
    <div class="modal-content">
    <!--Moda Header-->
      <div class="modal-header">
      <h4 class="modal-title popheader"></h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <!-- Modal Body -->
    <div class="modal-body">
      <div class="taxdetail">
      </div>
    </div>
    
     <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<input type="hidden" class="pdtindex" value="" />

<!--<div id="preloader">
       <img src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">
    </div>-->
  </div>
  </div>
  	</form>
	<script>
/*Purpose For Required Validation*/
$(".select2").change(function() {
            $(this).parsley().validate();
 });


function example(){
       $("#file_choosen").css({"border-color": "rgb(20, 46, 120)", 
             "border-width":"1px", 
             "border-style":"solid"});        // alert('success');
       }
  
  /*Karthigaa Purpose For Readonly Fields*/
   <?php   if($ids == "1" || $ids == "3"){   ?>
     $('.bulk_unit_price').css('readonly', true);
     $("#choosefile").attr("disabled", true); 
     $('.currency').hide();
    var ids = "{{$ids}}";
    $('input').attr('readonly', true);
    $('select').attr('readonly', true);
    $('select').css('pointer-events', 'none');
    $('#fp').css('pointer-events', 'none');
    if(ids ==1){
         $('.reverse_charge').attr('disabled',true);
    }
    if(ids == 3)
    {
        
        $('.currency').hide();
        $('.bulk_promised_date,.bulk_promised_alternate_date').css('pointer-events','none');
    }
    $('.delivery_div,.payment_div,.project_div,.supplier_div,.hsn,.partno,.insurance_div,.uomdiv,.shiftoloc_div,.billtoloc_div,.delivery_date_div,.ssite_div,.pdtdiv,.taxgroup').css('pointer-events','none');
    $('.ichide').hide();
    $('.add_row,.remove').hide();
    $('.productsearch').hide();
    $('.remarks,.bulk_comments').attr('readonly',false);
    <?php }else{?>
        $('#fp').css('pointer-events', '');
    <?php } ?>
        
    <?php if ($ids=="5"){ ?>
            $('.partno').css('pointer-events', 'none');
    <?php }?>        
       /*End Purpose For Readonly Fields*/    
       
       /**Karthigaa Purpose For Labour Condition**/
    <?php if ($ids=="4"){ ?>
    <?php if($row->po_type=="LABOUR") {  ?>
            $('.productprice').css('display','none');
        <?php } ?>
      $('.po_status').val('');
      <?php }?>
          
   <?php if($row->po_type=="LABOUR") {  ?>
    <?php if($ids != 3){ ?>
    $('.bulk_unit_price').attr("readonly",false);
    <?php } ?>
    $(".po_pricelist_id").removeAttr('required');
    $('.hidepart,.reqstar').hide();
    <?php } else{ ?>
        $('.taxgroup,.uomdiv').css('pointer-events','none');
    <?php }?>
      /*End Purpose For Labour Condition*/ 


    
$(document).ready(function(){
	var decimal = "<?php echo \Session('decimal'); ?>";
    <?php
      if($return_url=="purchasequtoetopo")
      { ?>
          $('.pricelist_div').css('pointer-events','none');
     <?php  }

         if($return_url=="poapproval")
         { ?>
           $('.pricelist_div,.ssite_div,.shiftoloc_div,.pdtdiv,.taxgroup,.prodate,.billtoloc_div,.delivery_date_div').css('pointer-events','none');

      <?php   }

         ?>
 $('.pricelist_div').css('pointer-events','none');
$('.viewquote').click(function(){
	var quoteid=$('.reference_id').val();
	if(quoteid!=""){
		var url="{{ URL::to('purchasequotationview')}}/"+quoteid;
	  window.open(url);
}
});


/**********Up/down/left/right arrow navigation start*******/
		$('input').keyup(function (e) {
	        if (e.which == 39) { // right arrow
	          $(this).closest('td').next().find('input').focus();
	 
	        } else if (e.which == 37) { // left arrow
	          $(this).closest('td').prev().find('input').focus();
	 
	        } else if (e.which == 40) { // down arrow
	          $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
	 
	        } else if (e.which == 38) { // up arrow
	          $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
	        }
      	});


    /*Karthigaa Purpose For Default Organization & User*/
var organization = '<?php echo Session::get('organization'); ?>' ;
$('.organization_id').val(organization).change();
var user = '<?php echo Session::get('id'); ?>' ;
$('.created_by').val(user).change();
$(".create_by").html($('.created_by option:selected').text());
$('.org').html($('.organization_id option:selected').text());

	$('.bulk_tax_amount,.bulk_line_total').attr('readonly',true);
  /*Validation*/
	$(document).on('keypress','.bulk_qty,.extracharge2,.extracharge1,.extracharge4,.extracharge6,.extracharge3,.extracharge5,.bulk_unit_price,.bulk_discount_percentage,.transport_charges,.unloading_charges,.insurance_charges,.packing_charges,.other_freight_amount', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*End*/
    /*copy past validation*/
    $('.bulk_qty,.bulk_discount_percentage').bind("cut copy paste", function(e) {
        e.preventDefault();
            });
    /*copy past validation*/
    /*discount percentage*/
$('.bulk_discount_percentage').keyup(function(){
  if ($(this).val() > 100){
    notyMsg("info","Should not exist more than 100");
    $(this).val('');
   
  }
});
/*discount percentage*/

    
    $(document).on('change','.bulk_promised_date0',function(){
        var promised_date=$(this).val();
        var hide_date=$('.bulk_hidden_date').val();
        
 $( ".bulk_promised_date" ).each(function( indexs ) {
        var dates=$('.bulk_promised_date'+indexs).val(); 
        if(hide_date ==dates || dates=="" || dates=="undefined" ){
            $('.bulk_promised_date'+indexs).val(promised_date);
        }
            $('.bulk_hidden_date').val(promised_date);
        });
    });


  $(document).on('change','.bulk_promised_alternate_date0',function(){
    var promised_alert_date=$(this).val();
        var hide_date=$('.bulk_hidden_date').val();
        $( ".bulk_promised_alternate_date" ).each(function( indexs ) {
            var dates=$('.bulk_promised_alternate_date'+indexs).val();
        if(hide_date ==dates || dates=="" ){
            $('.bulk_promised_alternate_date'+indexs).val(promised_alert_date);
        }
    $('.bulk_hidden_date').val(promised_alert_date);
            });
        });


/*Karthigaa Purpose for Jcombo Refresh*/
$(document).on('click','.jcr_supplier_id',function()
{
$(".supplier_id").jCombo("{{ URL::to('jcomboform?table=m_supplier_t:supplier_id:supplier_number|supplier_name') }}&order_by=supplier_name asc",
{selected_value:""});
  
  $('.suppliersite_id').select2('val',['']);
  $('.po_pricelist_id').select2('val',['']);
  
});

$(document).on('click','.jcr_project_id',function(){
$(".project_id").jCombo("{{ URL::to('jcomboform?table=m_projects_t:project_id:project_name') }}&order_by=project_name asc",
{selected_value:""});
});
	
$(document).on('click','.jcr_currency_id',function(){
$(".currency_jcombo").jCombo("{{ URL::to('jcomboform?table=f_account_currency_t:account_currency_id:currency_code') }}&order_by=currency_code asc",
{selected_value:""});
});

	$(document).on('click','.jcr_default_payment_method_id',function(){
            $(".default_payment_method_id").jCombo("{{ URL::to('jcomboform?table=m_payment_methods_t:payment_method_id:payment_method_name') }}&order_by=payment_method_name asc",
            {selected_value:""});
            });

	$(document).on('click','.jcr_freight_carrier_id',function()
        {
            var condition ="source_type_id='Purchase'";
            $(".freight_carrier_id").jCombo("{{ URL::to('jcomboform?table=m_frieghtcarriers_hdr_t:ar_frieghtcarriers_hdr_id:carrier_name') }}&order_by=carrier_name asc"+'&parent='+condition,
            {selected_value:""});
        });

$(document).on('click','.jcr_insurance_term_id',function(){
    $(".insurance_term_id").jCombo("{{ URL::to('jcomboform?table=m_insurance_terms_t:insurance_term_id:insurance_term_name') }}&order_by=insurance_term_name asc",
    {selected_value:""});
    });

	$(document).on('click','.jcr_freight_terms_id',function(){
            var condition ="source_type_id='Purchase'";
$(".freight_terms_id").jCombo("{{ URL::to('jcomboform?table=m_frieghtterms_t:frieghtterm_id:fob_point_name') }}&order_by=fob_point_name asc"+'&parent='+condition,
{selected_value:""});
});


$(document).on('click','.jcr_suppliersite_id',function()
{
  var supplier = $('.supplier_id').val();

    if(supplier!='')
    {
    var condition ='supplier_id='+supplier;
    $(".suppliersite_id").jCombo("{{ URL::to('jcomboform?table=m_supplier_sites_t:supplier_site_id:supplier_site_number|supplier_site_name') }}&order_by=supplier_site_name asc"+'&parent='+condition,
    {selected_value:""});
    }
    else{

    }
});

	$(document).on('click','.jcr_bill_to_location_id',function(){
 var loc = "<?php echo $comp_location; ?>";       
 var condition ='location_id in '+'('+loc+')';
$(".bill_to_location_id").jCombo("{{ URL::to('jcomboform?table=m_location_t:location_id:location_name') }}&order_by=location_name asc"+'&parent='+condition,
{selected_value:""});
});
    

$(document).on('click','.jcr_ship_to_location_id',function(){
 var loc = "<?php echo $comp_location; ?>";       
 var condition ='location_id in '+'('+loc+')';
$(".ship_to_location_id").jCombo("{{ URL::to('jcomboform?table=m_location_t:location_id:location_name') }}&order_by=location_name asc"+'&parent='+condition,
{selected_value:""});
});


$(document).on('click','.jcr_payment_term_id',function(){
$(".payment_term_id").jCombo("{{ URL::to('jcomboform?table=m_payment_terms_t:payment_term_id:payment_term_name') }}&order_by=payment_term_name asc",
{selected_value:""});
});

$(document).on('click','.jcr_delivery_terms_id',function(){
    var condition =' source_type_id="Purchase"';
$(".delivery_terms_id").jCombo("{{ URL::to('jcomboform?table=m_delivery_terms_t:delivery_terms_id:delivery_term_name') }}&order_by=delivery_term_name asc"+'&parent='+condition,
{selected_value:""});
});
$(document).on('click','.jcr_po_pricelist_id',function(){

	var condition =' price_list_type="Purchase"';
	$(".po_pricelist_id").jCombo("{{ URL::to('jcomboform?table=i_pricelist_hdr_t:pricelist_hdr_id:pricelist_name') }}&order_by=pricelist_name asc"+'&parent='+condition,
	{selected_value:""});
});
/*End*/


$('.source,.po_type,.organization_id,.created_by,.po_status').attr('readonly','readonly').css('pointer-events','none');
<?php if($row->po_type !="LABOUR")
{ ?> 
$(document).on('change','.po_pricelist_id',function(){
                            var po_pricelist_id=$('.po_pricelist_id option:selected').val();
                            if(po_pricelist_id != ''){
                                $.get("{{URL::to('getpriceproduct')}}/"+po_pricelist_id+'/0?condition=purchaseorder',function(data)
                                {
                                     $('.bulk_product_id').each(function(index){
                                           var val=$(this).val();
                                      $('.bulk_product_id').html(data);
                                      $('.bulk_product_id'+index).val(val);
                                });    
                                 });
                            }
                          pricelistchange();
                });
<?php } ?>
/*Karthigaa Purpose For Supplier Based Price load*/
    $(document).on('change','.supplier_id',function(){
            var supplier_id=$('.supplier_id option:selected').val();
            if(supplier_id!=''){     
        $.get("{{ URL::to('supplierpricelist') }}/"+supplier_id,function(suppdata){
        var data = $.trim(suppdata);
        if(data !=0)
        {
            var condition ="supplier_id="+supplier_id;
                $(".suppliersite_id").jCombo("{{ URL::to('jcomboform?table=m_supplier_sites_t:supplier_site_id:supplier_site_number|supplier_site_name') }}&order_by=supplier_site_name asc"+'&parent='+condition,
                {selected_value:suppdata['supplier_site_id'].toString()});
			 setTimeout(function(){
			$(".po_pricelist_id").val(suppdata['price_list']).change();
           }, 500);
            $('.payment_term_id').val(suppdata['default_payment_terms_id']).change();
            $('.delivery_terms_id').val(suppdata['delivery_terms_id']).change();
            $('.default_payment_method_id').val(suppdata['default_payment_method_id']).change();
            $('.insurance_term_id').val(suppdata['insurance_term_id']).change();
            $('.freight_terms_id').val(suppdata['frieghtterm_id']).change();
            $('.freight_carrier_id').val(suppdata['frieghtcarriers_id']).change();
                pricelistchange();
        }
        else
        {

            $(".po_pricelist_id").val('').change();
        }
    });
    }
  

});
   /*End*/
  

     
    
     
/*karthigaa purpose for load product based details */
$(document).on('change','.bulk_product_id',function(event){
        var index=$(this).closest('tr').index();
        var product_id=$(this).select2('val');
        var type=$('.po_type option:selected').val();
        var plid=$('.po_pricelist_id option:selected').val();
        var supplierid=$('.supplier_id option:selected').val();
        var suppsiteid=$('.suppliersite_id option:selected').val();
        $('.hsn').css("pointer-events","auto");

if(product_id!=null){
        var url="{{ url::to('productdetails') }}/"+product_id+"/"+plid+"/"+suppsiteid+"/"+type+"?supplier_id="+supplierid+"&source=SUPPLIER";
            if(supplierid !=''){
                    if(plid !='') {
                        if(product_id !='' && product_id != '-- Please Select --'){
                            var pdtcount = 0;
                            var pdtcount = pdtcheck(product_id,index);
                            if(pdtcount <= 0){
                                  $.get(url,function(data){   
                                        $('.bulk_uom_code_id'+index).val(data.uom_code_id).change();
                                        $('.bulk_part_no'+index).select2('val',[data['part_no']]);
                                        var hsnid=data['multihsn'];
                                        if(hsnid!=''){
                                            <?php if($row->po_type == "STANDARD") {  ?>	
                                                  var condition="classification_name='HSN' and gst_code_hdr_id in("+hsnid+")";
                                            <?php   } else { ?>		
                                                  var condition="classification_name='SAC' and gst_code_hdr_id in("+hsnid+")";		
                                            <?php }  ?>
                                            var hsn=data['hsn_code'];	
                                            $(".bulk_hsn_code"+index).jCombo("{{ URL::to('jcomboform?table=f_gst_code_hdr_t:gst_code_hdr_id:classification_code') }}&order_by=classification_code asc"+'&parent='+condition,
                                            {selected_value:hsn.toString()});
                                        }		
                                        if(data.unit_price =="0" || data.unit_price ==""){
                                            $('.bulk_unit_price'+index).val('');
                                            notyMsgs('info','Pricelist Not Assigned for this Product (or) Date has Expired ');
                                        } else { 
                                            var price=parseFloat(data.unit_price).toFixed("{{\Session::get('decimal')}}");
                                            $('.bulk_unit_price'+index).val(price);
					}
                                        if(data.qoh_qty > 0)
						{
							$('.bulk_qoh_qty'+index).val(data.qoh_qty);
						}
						else
						{
							$('.bulk_qoh_qty'+index).val(0);
						}
                                });
                            }
                            else
                            {
                                notyMsgs('info','Product Already Selected');
                                rowdataEmpty(index);
                                $(".bulk_product_id" + index).select2('val',['']);
                                event.preventDefault();
                            }
                        }       
                        else
                        {
                            $('.bulk_part_no'+index).select2('val',['']);
                            rowdataEmpty(index);
                            calc_by_index(index)
                        }
                    }
                    else
                    {
                        rowdataEmpty(index);
                        calc_by_index(index);
                        notyMsg('info','Please Select Pricelist !!!');
                        $(".bulk_product_id" + index).val('');
                        event.preventDefault();
                    }
            }
            else
            {
            notyMsg('info','Please Select Supplier !!!');
            $(".bulk_product_id" + index).val('');
            event.preventDefault();
            }
       }
});
   

/*deepika purpose: to load tax based on hsn code*/
<?php if($return_url != "purchaserequestiontopo"){?>
$(document).on('change','.bulk_hsn_code',function(){
		var hsnid=$(this).val();
                if(hsnid)
                {
                    var index=$(this).closest('tr').index();
                    var suppsiteid=$('.suppliersite_id option:selected').val();
                    var mtype="PURCHASE";
                    if(hsnid!="" && suppsiteid!=""){
                    var url="{{ URL::to('taxdetails')}}/"+hsnid+"/"+suppsiteid+"/"+mtype;
                       }
        		$.get(url,function(data){
        		if(data['tax_group_id']==0)
				   {
                                        if($.trim(data['tax_group_id_expiry'])=="expiry"){
                                           notyMsgs('info','Tax Group expired  for this product');
                                       }
                                       else if($.trim(data['tax_group_id_expiry'])=="location")
                                       {
                                       notyMsgs('info','Tax not assigned for this Location');
                                       }
                                       else
                                       {
                                       notyMsgs('info','Tax Group not assigned for this product');
                                       }	
				   }
                         else{
                                $('.bulk_tax_group_id'+index).select2('val',[data.tax_group_id]);
    			     }
			
		});
            }
	});
<?php } ?>
	/*end*/
        /*karthigaa purpose for load product based All Details */
    function pricelistchange()
	{
	    var plid=$('.po_pricelist_id').val();
            var supplierid=$('.supplier_id option:selected').val();
            var type=$('.po_type option:selected').val();
        if(type=="STANDARD"){
        if(plid != null || plid != ''){
    		$('.bulk_product_id').each(function(index){
    			var product_id=$(this).val();
                        var type=$('.po_type option:selected').val();
                        var suppsiteid=$('.suppliersite_id option:selected').val();
                        if(plid!="" && product_id != ''&& product_id != null){
                            var url="{{ url::to('productdetails') }}/"+product_id+"/"+plid+"/"+suppsiteid+"/"+type+"?supplier_id="+supplierid+"&source=SUPPLIER";
                if(plid!="" || product_id != "" && product_id != null){
        			$.get(url,function(data)
        			{
                                             setTimeout(function(){ 
                                        $(".bulk_product_id"+index).val(data.product_id).change();
                                        $(".bulk_hsn_code"+index).val(data.hsn_code).change();
								   }, 1000);
        				$('.bulk_uom_code_id'+index).val(data.uom_code_id).change();
                                            if(data.unit_price =="0" || data.unit_price =="")
                                            {
                                                setTimeout(function () {   
                                             notyMsgs('info','Pricelist Not Assigned For this Product !!!');
                                              $('.bulk_unit_price'+index).val('');
                                              $('.bulk_line_total'+index).val('');
                                              $('.bulk_tax_amount'+index).val('');
                                              $('.bulk_hsn_code'+index).select2('val',['']);
                                              $('.bulk_tax_group_id'+index).select2('val',['']); 
                                              $('.bulk_uom_code_id'+index).select2('val',['']); 

                                               }, 1000);
                                            }
                                            else
                                            {
                                               $('.bulk_unit_price'+index).val(data.unit_price);
                                            }
                                            if(data.qoh_qty > 0)
						{
							$('.bulk_qoh_qty'+index).val(data.qoh_qty);
						}
						else
						{
							$('.bulk_qoh_qty'+index).val(0);
						}
                    			if($.trim(data.tax_group_id)==0){
                                                if($.trim(data.tax_group_id_expiry)=="expiry"){
                                                              notyMsgs('info','Tax Group expired  for this product');
                                                         $('.bulk_tax_group_id'+index).val(data.tax_group_id).change();
                                                }
                                                   else if($.trim(data.tax_group_id_expiry)=="location"){
                                                              notyMsgs('info','Tax not assigned for this Location');
                                                }
                                                else{
                                                         notyMsgs('info','Tax Group not assigned for this product');
                                                         $('.bulk_tax_group_id'+index).val(data.tax_group_id).change();
                                                }	
        				   }
                                        else
                                        {
                                        $('.bulk_tax_group_id'+index).val(data.tax_group_id).change();
                                        }
                                    calc_by_index(index);
        			});
                             }
			else{
                                        $('.bulk_hsn_code'+index).select2('val',['']); 
					$('.bulk_tax_group_id'+index).select2('val',['']); 
                                        $('.bulk_uom_code_id'+index).select2('val',['']); 
					$('.bulk_unit_price'+index).val('');
                                         $('.bulk_line_total'+index).val('');
                                         $('.bulk_tax_amount'+index).val('');
				}
                            }
    		});
        }
        }
        else{
            var condition="classification_name='SAC'";
    $(".bulk_hsn_code"+index).jCombo("{{ URL::to('jcomboform?table=f_gst_code_hdr_t:gst_code_hdr_id:classification_code') }}&order_by=classification_code asc"+'&parent='+condition,
    {selected_value:''});
        }
	}  
       
//         $('.po_pricelist_id').trigger('change');
/*Karthigaa Purpose for Load Bill TO,Ship TO Location*/     
$(document).on('change','.bill_to_location_id,.ship_to_location_id',function(){
            var status = $(this).attr('data-location');
            if(status ==1)
                var location_id =  $('.bill_to_location_id').select2('val');
            else
                var location_id =  $('.ship_to_location_id').select2('val');
            var url="{{URL::to('getaddress')}}?location_id="+location_id;
            $.get(url,function(data)
            {
                var data = $.trim(data);    
                if(data != '')
                {
                    if(status == 1)
                        $('.bill_to_address').val(data);
                    else
                        $('.ship_to_address').val(data);
                }
                else
                {
                    if(status == 1)
                        $('.bill_to_address').val('');
                    else
                        $('.ship_to_address').val('');
                }
            });
            
        });   
    /*End*/
    /*Karthigaa Purpose for Add Sum of other charges into total*/
function total_amount(){
			var sum = 0;
			var sumtax = 0;
                        var sumall = 0;
                        var charge= 0;
                        $(".charges").each(function(){
                               charge += +$(this).val();
                           });
			$('.bulk_line_total').each(function(){
				sum += parseFloat($(this).val());
			});
			$('.bulk_tax_amount').each(function(){
				sumtax += parseFloat($(this).val());
			});
            sumall = Number(charge) + Number(sum);
			$('#quote_tax_total').val(sumtax);
			$('#quote_grand_total').val(sumall);
			$(".tax_total_span").html(sumtax);
			$(".grand_total_span").html(sumall);
    }
    /* end */

	/**** To Empty the Rowdata when product Empty ********/
	function rowdataEmpty(index)
	{
	$(".bulk_product_id" + index).val('').change;
	$(".bulk_uom_code_id" + index).val('').change();
	$(".bulk_qty" + index).val('');
	$(".bulk_unit_price" + index).val('');
	$(".bulk_discount_percentage" + index).val('');
	$(".bulk_discount_amount" + index).val('');
	$(".bulk_tax_group_id" + index).val('').change();
	$(".bulk_tax_amount" + index).val('');
        $(".bulk_line_sub_total" + index).val('');
        $(".bulk_hsn_code" + index).val('').change();;
	$(".bulk_line_total" + index).val('');
        $(".bulk_part_no" + index).val('');
	$(".bulk_promised_date" + index).val('');
        $(".bulk_promised_alternate_date" + index).val('');
	$(".bulk_comments" + index).val('');
	$(".bulk_qty" + index).trigger('change');
        
	}
/**** To Empty the Rowdata when product Empty End********/

/** Karthigaa purpose tax for other charges **/
  $(document).on('click','.packingtax',function(){
    var type=$(this).attr('data-value');
  var type_id=$(this).attr('data-at');
  $('.popheader').html(type+" Tax Details");
  $('#taxModal').modal('show');
  $('#taxModal').width("48%").css('margin','auto');
     if(type_id=="1"){
     var val_char=$('.packing_charges_tax').val();
 } 
      if(type_id=="2"){
     var val_char=$('.transport_charges_tax').val();
 } 
      if(type_id=="3"){
     var val_char=$('.insurance_charges_tax').val();
 } 
        if(type_id=="4"){
             var val_char=$('.other_tax_amount_tax').val();
         } 
        if(type_id=="5"){
             var val_char=$('.other_frieght_amount_tax').val();
        } 
        if(type_id=="6"){
           var val_char=$('.unloading_charges_tax').val();
       } 
    var text_data ="{!! $tax_group_id_pop!!}";
    var data='';
    
      <?php if($return_url=='poapproval')
         {  ?>
      data+='<div class="col-md-6"><div class="form-group row"> <label for="inputIsValid" class="form-control-label col-md-4">'+type+'</label><div class="col-md-6"><input type="text"  class="form-control extracharge'+type_id+'" value="" readonly></div><div class="col-md-2"></div></div></div><div class="col-md-6"><div class="form-group row"> <label for="inputIsValid" class="form-control-label col-md-4">Tax Group</label><div class="col-md-6" style="pointer-events:none"><select class="select2 form-control tax_details'+type_id+'  tax_detailsse">'+text_data+'</select></div><div class="col-md-2"></div></div></div> <div class="col-md-12" style="width:100%;margin:auto;text-align:center"><button type="button" class="btn ok " value="'+type_id+'">Ok</button></div>';
    <?php } else {?>
        data+='<div class="col-md-6"><div class="form-group row"> <label for="inputIsValid" class="form-control-label col-md-4">'+type+'</label><div class="col-md-6"><input type="text"  class="form-control extracharge'+type_id+'" value=""></div><div class="col-md-2"></div></div></div><div class="col-md-6"><div class="form-group row"> <label for="inputIsValid" class="form-control-label col-md-4">Tax Group</label><div class="col-md-6"><select class="select2 form-control tax_details'+type_id+'  tax_detailsse">'+text_data+'</select></div><div class="col-md-2"></div></div></div> <div class="col-md-12" style="width:100%;margin:auto;text-align:center"><button type="button" class="btn ok taxchargesave" value="'+type_id+'">Ok</button></div>';
    <?php } ?>
  
 
      $('.taxdetail').html(data);
    
if(val_char!=''){
var dat=val_char.split(",");
  
  $('.extracharge'+type_id).val(dat[1]);
  $('.tax_details'+type_id).val(dat[0]).change();
}
    
  });
 /** Karthigaa purpose tax for other charges Save **/ 
     $(document).on('click','.taxchargesave',function(){
        var type=$(this).val();
        var taxgrp = $('.tax_details'+type+' option:selected').attr('data-display');
        var taxgrp_v = $('.tax_details'+type+' option:selected').val();
        var charge = parseFloat($('.extracharge'+type).val());
         taxgrp = taxgrp?taxgrp:0;
        var amount = parseFloat((charge) * taxgrp/100).toFixed("{{\Session::get('decimal')}}");

     if(taxgrp_v==0){
     notyMsg("info","Please select Tax Group");
   }
    charge = isNaN(charge) ? '' : charge;
   if(charge==''){
      notyMsg("info","Please fill Amount");
       $('#taxModal').modal('show');
  }
    if(taxgrp_v!=0 && charge!=''){
         var a_c=(parseFloat(amount)+parseFloat(charge)).toFixed("{{\Session::get('decimal')}}");;
      var tax_group_value=taxgrp_v+","+charge;
 if(type=="1"){
     $('.packing_charges').val(a_c);
     $('.packing_charges_tax').val(tax_group_value);
 } 
      if(type=="2"){
     $('.transport_charges').val(a_c);
     $('.transport_charges_tax').val(tax_group_value);
 } 
      if(type=="3"){
     $('.insurance_charges').val(a_c);
     $('.insurance_charges_tax').val(tax_group_value);
 } 
            if(type=="4"){
          
     $('.other_tax_amount').val(a_c);
     $('.other_tax_amount_tax').val(tax_group_value);
//$('.other_tax_amount_tax').val(a_c);
 } 
        if(type=="5"){
     $('.other_frieght_amount').val(a_c);
     $('.other_frieght_amount_tax').val(tax_group_value);
 } 
  if(type=="6"){
     $('.unloading_charges').val(a_c);
     $('.unloading_charges_tax').val(tax_group_value);
 } 
       $('#taxModal').modal('hide');
    }
   else{
      if(type=="1"){
     $('.packing_charges').val(0);
     $('.packing_charges_tax').val('');
 } 
      if(type=="2"){
     $('.transport_charges').val(0);
     $('.transport_charges_tax').val('');
 } 
      if(type=="3"){
     $('.insurance_charges').val(0);
     $('.insurance_charges_tax').val('');
 } 
            if(type=="4"){
     $('.other_tax_amount').val(0);
     $('.other_tax_amount_tax').val('');
 } 
        if(type=="5"){
     $('.other_frieght_amount').val(0);
     $('.other_frieght_amount_tax').val('');
 } 
   if(type=="6"){
     $('.unloading_charges').val(0);
     $('.unloading_charges_tax').val('');
 } 
   }

  });
   /** end **/

	/*Karthigaa Code for lines level calulaton process*/
$(document).on('keyup change','.bulk_qty,.bulk_unit_price,.bulk_tax_group_id,.bulk_discount_percentage,.bulk_product_id,.transport_charges, .unloading_charges, .insurance_charges, .packing_charges,.other_freight_amount',function(){
		var index = $(this).closest("tr").index();
	var unitprice = $('.bulk_unit_price'+index).val();
	var requiredqty = $('.bulk_qty'+index).val();
	var taxgrp = $('.bulk_tax_group_id'+index+' option:selected').attr('data-display');
	taxgrp = taxgrp?taxgrp:0;
	var discountsperc = $('.bulk_discount_percentage'+index).val();
	var disamout = (((requiredqty * unitprice) * discountsperc/100));
	$('.bulk_discount_amount'+index).val(disamout);
        
	var taxamount = ((requiredqty * unitprice) - disamout) * taxgrp /100;
        $('.bulk_tax_amount'+index).val(taxamount.toFixed(decimal));
        //alert(taxamount);
	var subtot = ((requiredqty * unitprice) - disamout);
        var linetot=parseFloat(subtot + taxamount).toFixed(decimal);
	$(".bulk_line_total"+index).val(linetot);
		/* Code for set linetotal values into header level field*/
			var sum = 0;
			var sumtax = 0;
                        var sumall = 0;
                        var charge= 0;
                        $(".charges").each(function(){
                               charge += +$(this).val();
                           });

			$('.bulk_line_total').each(function(){
				sum += parseFloat($(this).val());
			});
			$('.bulk_tax_amount').each(function(){
				sumtax += parseFloat($(this).val());
			});
                          sumall = Number(charge) + Number(sum);
			$('#po_tax_total').val(sumtax);
			$('#po_grand_total').val(sumall);
      $(".tax_total_span").html(sumtax);
      $(".grand_total_span").html(sumall);
		/* end */
		/* Code for calculating tot tax amount */
		var tax =0;
		$('.bulk_tax_amount').each(function(){
		   tax+= parseFloat($(this).val());
		});
		$('.po_tax_total').val(tax);
                $(".tax_total_span").html(tax);
		/* end */
});
/** Karthigaa purpose Discount Amount Based Discount Precentage Calculation **/
$(document).on('keyup change','.bulk_discount_amount',function()
{
    var index = $(this).closest("tr").index();
    var unitprice = (isNaN($('.bulk_unit_price'+index).val()) || $('.bulk_unit_price'+index).val() == '') ? 0 : $('.bulk_unit_price'+index).val();
    var requiredqty = (isNaN($('.bulk_qty'+index).val()) || $('.bulk_qty'+index).val() == '' ) ? 0 : $('.bulk_qty'+index).val();
    var taxgrp = $('.bulk_tax_group_id'+index+' option:selected').attr('data-display');
	
    taxgrp = taxgrp?taxgrp:0;
    var discountsperc = (isNaN($('.bulk_discount_percentage'+index).val()) || $('.bulk_discount_percentage'+index).val() == '') ? 0 : $('.bulk_discount_percentage'+index).val(); 
    var discountamt = (isNaN($('.bulk_discount_amount'+index).val())  || $('.bulk_discount_amount'+index).val() == '') ? 0 : $('.bulk_discount_amount'+index).val(); 
    
    var disamout = discountamt;
    var disamt;
    
    if(discountamt != 0.00){
       
     disamt = ((discountamt * 100)/(requiredqty * unitprice));
    }
    else{
          disamt = 0;
     }
    
    $('.bulk_discount_percentage'+index).val(disamt.toFixed(decimal));
    $('.bulk_discount_amount'+index).val(disamout);
    var subtot = ((requiredqty * unitprice) - disamout);
    var taxamount = (subtot) * taxgrp /100;
    $('.bulk_tax_amount'+index).val(taxamount);
    
    var linetot=parseFloat(subtot + taxamount).toFixed(decimal);
        $(".bulk_line_total"+index).val(linetot);
    /* Code for set linetotal values into header level field*/
        var sum = 0;
        var sumtax = 0;
                 var sumall = 0;
                        var charge= 0;
                        $(".charges").each(function(){
                               charge += +$(this).val();
                           });
        $('.bulk_line_total').each(function()
        {
            sum += parseFloat($(this).val());
        });
        $('.bulk_tax_amount').each(function()
        {
            sumtax += parseFloat($(this).val());
        });
                   sumall = Number(charge) + Number(sum);
        $('#quote_tax_total').val(sumtax);
        $('#quote_grand_total').val(sumall);
        $(".tax_total_span").html(sumtax);
            $(".grand_total_span").html(sumall);
    /* end */
    /* Code for calculating tot tax amount */
    var tax =0;
    $('.bulk_tax_amount').each(function(){
       tax+= parseFloat($(this).val());
    });
    $('.quote_tax').val(tax);
    $(".grand_total_span").html(tax);
    /* end */
});
/* end */
function calc_by_index(index){

        var unit_price = $('.bulk_unit_price'+index).val();
        var qty = $('.bulk_qty'+index).val();
        var tax_group = $('.bulk_tax_group_id'+index+' option:selected').attr('data-display');
        var line_sub_total = parseFloat(unit_price * qty);
        var tax_amount = parseFloat((line_sub_total*tax_group)/100);
        $('.bulk_tax_amount'+index).val(tax_amount);
        var linetot=parseFloat(line_sub_total+tax_amount).toFixed(decimal);
        $(".bulk_line_sub_total"+index).val(line_sub_total);
        $(".bulk_line_total"+index).val(linetot);

/* Code for set linetotal values to header level via keyup*/
        var lsbt = 0;
        $('.bulk_line_sub_total').each(function()
        {
        lsbt += parseFloat($(this).val());
        });
        $('.order_sub_total').val(lsbt);

        var sum = 0;
        $('.bulk_line_total').each(function()
        {

        sum += parseFloat(isNaN($(this).val())?0:($(this).val()));
        });
        $('#grand_total_span').html(sum);
/* end */
/* Code for calculating tot tax amount */
        var tax =0;
        $('.bulk_tax_amount').each(function(){
        tax+= parseFloat($(this).val());
        });
        $('.po_tax_total').val(tax);
/* end */
}
    /*End*/
    
/*Karthigaa Purpose For Add New Row*/    
var data ="{{\Session::get('j_date_format')}}";
	$(".add_row").on('click',function(){
  var form = $('#po_form');
  form.parsley().destroy();
});
changeclassfields();
$(".add_row").relCopy(data);
$('.add_row').click(function(){
     changeclassfields();
	var rowCount = $('.po_table tbody tr').length;
	var index  = Number(rowCount) - 1;
	var dateval = $('.bulk_promised_date0').val();
	var altdateval = $('.bulk_promised_alternate_date0').val();
    $('.bulk_promised_date'+index).val(dateval);
	$('.bulk_promised_alternate_date'+index).val(altdateval);
      
       <?php if($row->po_type !="LABOUR"){ ?> 
         <?php if($return_url!="purchaserequestiontopo"){ ?>
                 <?php if($return_url=="purchaseorder"){ ?>
            <?php if($row->check!="1" && $row->check!=null) { ?>
            var ind=$(".clone").last('tr').index();
            $('.bulk_product_id'+ind).html('');
            $('.bulk_product_id'+ind).html("<? echo $productid ?>");
            $('.bulk_uom_code_id'+ind).html("<? echo $uomcodeid ?>");
            $('.bulk_part_no'+ind).html("<? echo $partno ?>");
              
            <?php }}?>
                 <?php } }?>
});
  /*End*/
  /*Karthigaa Purpose For Remove Row*/   
$(document).on('click','.remove',function(){
	var index = $(this).closest('tr').index();
	var rowCount = $('.po_table tbody tr').length;
	if(rowCount > 1){
		$($(this).closest("tr")).remove();
                removeclassfields();
	}
	else{
		notyMsg('info',"You Can't Delete Atleast One row should be there");
	}
         var sum = 0;
        var sumtax = 0;
        $('.bulk_line_total').each(function()
        {
            sum += parseFloat($(this).val());
        });
        $('.bulk_tax_amount').each(function()
        {
            sumtax += parseFloat($(this).val());
        });
        $('#po_tax_total').val(sumtax);
        $('#po_grand_total').val(sum);
});
/*End*/
var index = $('.clone').closest('tr').index();
changeclassfields();

/*Karthigaa Purpose for Supplier Search*/
$('.suppliersearch').click(function()
	{
	 $('#supplierModal').modal('show');
	 $('#supplierModal').width("100%");
	$(mygrid).trigger("reloadGrid", [{current: true}]);
	});
var supnameopt="{{ $supnameopt }}";
var suptypeopt="{{ $suptypeopt }}";
var supsitopt="{{$supsitopt}}";
var country="{{ $country }}";
var state="{{ $state }}";
var city="{{ $city }}";
	var mygrid = $("#suppliergrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mygrid.jqGrid('navButtonAdd',pagerSelector,options);
        mygrid.jqGrid('navButtonAdd','#'+mygrid[0].id+"_toppager",options);
    };
          mygrid.jqGrid({
          url: "{{ URL::to('getSuppliergridData') }}",
            datatype: "json",
            mtype: "GET",
			height: 320,
			width: 1000,
            colModel: [
			{ name: "supplierid", label: "id",hidden:true},
			{ name: "supplier_site_id", label: "id",hidden:true},
			{ name: "supplier_number", label: "Supplier Number",},
		 	{ name: "supplier_name", label: "Supplier Name", editoptions:{value:supnameopt}},
                        { name: "suppliertype_name", label: "Supplier Type", editoptions:{value:suptypeopt}},
		 	{ name: "supplier_site_name", label: "Supplier Site Name"},
                        { name: "address", label: "Address"},
                        { name: "country_name", label: "Country", editoptions:{value:country}},
                        { name: "state_name", label: "State",editoptions:{value:state}},
                        { name: "city_name", label: "City",editoptions:{value:city}},
                        
                    ],

                        iconSet: "fontAwesome",
                        rowNum: 10,
                        rowList: [10,20,100,1000],
                        sortorder: "asc",
                        viewrecords: true,
                        gridview: true,
                        rownumbers:true,
                        caption: "",
                        pager: pagerSelector,
                        toppager:true,
                        searching: {
                        defaultSearch: "cn"
                        }
		   });
                        jQuery(mygrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
                        jQuery("#gs_suppliergrid_supplier_id").select2();
                        jQuery("#gs_suppliergrid_supplier_type_id").select2();
                        jQuery("#gs_suppliergrid_country").select2();
                        jQuery("#gs_suppliergrid_state").select2();
                        jQuery("#gs_suppliergrid_city").select2();
                        mygrid.jqGrid('navGrid',pagerSelector,
                        {cloneToTop:true,edit:false,add:false,del:false,search:true});
                        $('#refresh_suppliergrid_top').find('.fa-refresh').addClass('supplier_refresh');
$('.supplier_refresh').click(function(){
     $('input[id*="gs_"]').val("");
     $('select[id*="gs_"]').select2('val',['']);
});

                        myAddButton ({
                        caption:"Select Supplier",
                        title:"Supplier",
                        buttonicon :'ui-icon-plus',
		onClickButton:function()
		{
		var gr = $(mygrid).jqGrid('getGridParam','selrow');
		var supplier = $(mygrid).jqGrid ('getCell', gr, 'supplierid');
		var address = $(mygrid).jqGrid ('getCell', gr, 'address');
		if( supplier != false )
		{
		$('.supplier_id').val(supplier).change();
		$('#supplierModal').modal('hide');
		}
		else
		{
		notyMsg('info','Please Select one row');
		}
		}
});
 
/*Karthigaa Purpose For Product Search*/
   $(document).on('click','.productsearch',function()
    {
        var supplier_id=$('.supplier_id option:selected').val();
	if(supplier_id!=''){
      $('.pdtbtn').parent('div').html('');
	 var index = ($(this).closest('tr').index());
	 $('.pdtindex').val(index);
	 $('#productModal').modal('show');
	 $('#productModal').width("100%");


	
    var mypdtgrid = $("#productgrid"),

    pagerSelector = "#pager",
    myAddButton = function(options) {
        mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
    };
		var groupname="'RAW MATERIALS'";
	var gname="'PACKING MATERIALS'";
        var pricelist_id=$(".po_pricelist_id option:selected").val();
       
		<?php { ?>
 var grp=[];
 grp.push(groupname);
	grp.push(gname);
<?php } ?>
	var prdcatopt="{{ $prdcatopt}}";
	var prdnameopt="{{ $prdnameopt }}";
        var group="{{$group}}";
            mypdtgrid.jqGrid({
                 url: "{{ URL::to('getProductgridData') }}?prggrp="+grp+"&pricelist_id="+pricelist_id,
//            url: "{{ URL::to('getProductgridData') }}?prggrp="+grp,
			datatype: "json",
			mtype: "GET",
			height: 320,
			width: 1000,
             colModel: [
			{ name: "product_code", label: "Product Code", width:55},
                        { name: "group_name", label: "Product Group", width:55},
			{ name: "category_name", label: "Product Category", width:55},
		 	{ name: "concatenated_product", label: "Product Name",stype:'text', editoptions:{value:prdnameopt}, width:55},
			{ name: "product_id", label: "id",hidden:true, width:55}
		  ],
                        iconSet: "fontAwesome",
			rowNum: 10,
			rowList: [10,20,100,1000],
			sortorder: "asc",
			viewrecords: true,
			gridview: true,
			rownumbers:true,
			caption: "",
			pager: pagerSelector,
			toppager:true,
			searching: {
			defaultSearch: "cn"
			}
		   });
jQuery(mypdtgrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_productgrid_product_group_id").select2();
jQuery("#gs_productgrid_product_category_id").select2();

mypdtgrid.jqGrid('navGrid',pagerSelector,
{cloneToTop:true,edit:false,add:false,del:false,search:true});
 $('#refresh_productgrid_top').find('.fa-refresh').addClass('ui-icon-refresh');
 $('.ui-icon-refresh').hide();
/*  $('.ui-icon-refresh').click(function(){
           $('input[id*="gs_"]').val("");
            $('select[id*="gs_"]').select2('val',['']);
            var url = "{{URL::to('productgroupid')}}?module_name=purchaseorder";
            $.get(url, function(data) 
            {
                var condition = 'product_group_id in('+data+')';
            $(".bulk_product_id").jCombo("{{ URL::to('jcomboform?table=m_products_t:product_id:concatenated_product') }}&order_by=product_id asc"+'&parent='+condition,
            {selected_value:""});
                
            });
         
        });*/
myAddButton ({
caption:"Select Product",
title:"Product",
buttonicon :'ui-icon-plus pdtbtn',
		onClickButton:function()
		{
			var index = $('.pdtindex').val();
			var gr = jQuery(mypdtgrid).jqGrid('getGridParam','selrow');
			var product_id = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'product_id');
                        if(product_id != false )
			{
				var pdtcount = pdtcheck(product_id,index);
				if(pdtcount <= 0)
				{
				$('.bulk_product_id'+index).val(product_id);
				$('.bulk_product_id'+index).trigger('change');
					
				$('#productModal').modal('hide');
				}
				else
				{
                                        var msg = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'concatenated_product');
					var message  = '<span style="color:#fdff65">'+msg+'</span>'+' Product Already Selected';
					notyMsgs('info',message);
					rowdataEmpty(index);
					$('#productModal').modal('hide');
				}
			}
			else
			{
			notyMsg('error','Please Select one row');
			}
		}
});

$('#refresh_productgrid_top > div > span').addClass('refreshprd');
		
$(".refreshprd").click(function () {
	     var po_pricelist_id=$('.po_pricelist_id option:selected').val();
            $(".bulk_product_id").each(function(index){
              if(index!=0)
              $(this).closest('tr').remove();
            });
if(po_pricelist_id !=''){
        $.get("{{URL::to('getpriceproduct')}}/"+po_pricelist_id+'/0'+"&condition='purchaseorder'",function(data){
			
        if($.trim(data)=='<option value="">-- Please Select --</option>')
        {
                 notyMsg("info","No Product for these Pricelist");
        }
        $('.bulk_product_id').html(data);
        });
    }
    else{
            notyMsg("info","Please select Pricelist");
    }
        });	
   }
    else{
        notyMsg("error","Please Select Supplier");
    }
});
/*Karthigaa Purpose For File Attachments*/
  $(document).on('change','.GetFileSizeNameAndType',function(){
        var fi = document.getElementById('choosefile'); // GET THE FILE INPUT AS VARIABLE.
        var totalFileSize = 0;
        // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
        if (fi.files.length > 0)
        {
            // RUN A LOOP TO CHECK EACH SELECTED FILE.
            for (var i = 0; i <= fi.files.length - 1; i++)
            {
                //ACCESS THE SIZE PROPERTY OF THE ITEM OBJECT IN FILES COLLECTION. IN THIS WAY ALSO GET OTHER PROPERTIES LIKE FILENAME AND FILETYPE
                var fsize = fi.files.item(i).size;
                totalFileSize = totalFileSize + fsize;
                document.getElementById('fp').innerHTML =
                document.getElementById('fp').innerHTML
                +
                '<tr><td><span class="note" ><br /> File:<span class="files">' + fi.files.item(i).name+'</span>&nbsp;<img src="{{URL::to('')}}/images/cancel.png" class="delete_user"></span></td></tr>';
            }
        }
        //document.getElementById('divTotalSize').innerHTML = "Total File(s) Size is <b>" + Math.round(totalFileSize / 1024) + "</b> KB";
        /*file upload validation*/
         $('#choosefile').change(function(){

               var fp = $("#choosefile");

               var lg = fp[0].files.length; // get length

               var items = fp[0].files;

               var fileSize = 0;

           

           if (lg > 0) {

               for (var i = 0; i < lg; i++) {

                   fileSize = fileSize+items[i].size; // get file size

               }

               if(fileSize > 10485760 ) {

                    notyMsg('info','File size must not be more than 10MB');

                    $('#choosefile').val('');

               }

           }

        });
        /*file upload validation*/
    });
     $(document).on('click','.delete_user',function(){
        var po_hdr = '{{$row->po_hdr_id}}';
        if(po_hdr != '')
        {
        var existing_value = $('#existing_file').val();
        var delete_value = $(this).attr('data-value');
            removeValue(existing_value,delete_value);
        }
        $(this).parent().parent().remove();
    }); 
    function removeValue(existing_value, delete_value) 
    {
         list = existing_value.split(',');
        list.splice(list.indexOf(delete_value), 1);
        var values = list.join(',');
        if(values != '')
        $('#existing_file').val(values);
    else
         $('#existing_file').val('');
    }
 /*End File Attachments*/   
/*Karthigaa Purpose For To See Previous Product Po History Popup*/
$(document).on('click','.productprice',function(){
var index=$(this).closest('tr').index();
var product_id = $('.bulk_product_id' + index).val();
if(product_id!=''){
     $.get("{{URL::to('productprice')}}/"+product_id,function(data)
        { 
        data = jQuery.parseJSON(data);
        if (data != 0) {
            $('#productprice').modal('show');
            $('.modal-dialog').width('80%');
            $('.mcontent5').html('');
    $.each(data, function (key) {
        $('.mcontent5').append('<tr>\n\
        <td width="220px"><input type="text" name="supplier" class="form-control ' + key + '" value="' + data[key].supplier_name + '" readonly/></td>\n\
        <td width="150px"><input type="text" name="po_date" class="form-control ' + key + '" value="' + data[key].po_date + '" readonly/></td>\n\
        <td width="150px"><input type="text" name="po_number" class="form-control ' + key + '" value="' + data[key].po_number + '" readonly/></td>\n\
        <td width="120px"><input type="text" name="unit_price" class="form-control ' + key + '" value="' + data[key].unit_price + '" readonly/></td>\n\
        <td width="360px"><input type="text" name="product" class="form-control ' + key + '" value="' + data[key].concatenated_product + '" readonly/></td></tr>');
    });
        $("#productprice").modal({backdrop: "static"});
    } 
        else 
        {
        notyMsg('error','There is no previous PO in this Product');
        }
        });
    
    }
    else{
        notyMsg("error","Please Select Product");
    }
});
/*End*/
/*karthigaa purpose for hide product in labour condition*/
<?php if($row->po_type =="STANDARD")
{ ?>
$('.pdtdes_div').addClass('hide');
<?php }
else { ?>
$('.bulk_product_id').removeAttr('required');
$('.bulk_product_description').attr('required',true);
<?php } ?>
/*End*/
function gst_qty(){
	$(".bulk_qty").each(function(){
		$(this).trigger('change');
	});
    }
     $('.seq').hide();
/*Karthigaa Purpose For Submit Function*/                 
$(document).on('click','.saveform',function() {
      $('#panel_add').trigger('click'); //for expanding according
	  gst_qty();
          var potype ="{{ $row->po_type }}";
         
        var btnval = $(this).val();
                if(btnval == 'APPLYCHANGES')
                {
                     $('.remarks').attr('required',false);
                $("#po_status").val('DRAFT');
                }
             else if(btnval=='Approved')
             {
                  $('.remarks').attr('required',false);
                $("#po_status").val('APPROVED');
             }
                 else if(btnval=='Rejected')
                 {
                     $('.seq').show();
                     $('.remarks').attr('required',true);
                $("#po_status").val('REJECTED');
                 }
                     else if(btnval=='Canceled')
                     {
                          $('.remarks').attr('required',false);
                $("#po_status").val('CANCELLED');
                     }
            else
            {
                $("#po_status").val('INITIATED');
            }
	<?php if($ids == "3")  { ?>
	var totalqty = 0;
	var recqty = 0;
	$('.bulk_qty').each(function(data)
	{
		totalqty += parseFloat($(this).val());
		recqty += parseFloat($('.bulk_received_qty'+index).val());
	});
	if(recqty < totalqty)
	{
		 $("#po_status").val('CLOSED');
	}
	if(recqty == totalqty)
	{
		 $("#po_status").val('COMPLETED');
	}
	if(recqty==0)
	{
		$("#po_status").val('CANCELLED');
	}
	<?php } ?>
        $('#savestatus').val(btnval);

	var url			="{{ URL::to('purchaseordersave') }}";
        var red_url		="{{ URL::to($return_url) }}";
        if(potype=='LABOUR'){
                var create_url	="{{ url('purchaseordercreate') }}/0/LABOUR/2";
	   }else if(potype='STANDARD'){
                var create_url	="{{ url('purchaseordercreate') }}/0/STANDARD/2";
	   }
	validationrule('po_form');
	var form = $('#po_form');
	if(btnval != 'APPLYCHANGES')
	{
		form.parsley().validate();
		var form = $('#po_form');
                qtyrequired();
		form.parsley().validate();

		if (form.parsley().isValid())
		{
		 $('.ajaxLoading').show();
                 change_date();
                 var formdata	= $('#po_form').serialize();
		 var form_data = new FormData(document.getElementById('po_form'));   
          $.ajax({
                  url: url,
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,   // tell jQuery not to set contentType
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                }
                                        //update progressbar

                                }, true);
                        }
          return xhr;

                }
                }).done(function(data)
		{
			var status  = data.status;
				var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
				var id      = data.id;
				var auto_no = data.auto_no;
            if(btnval !='SAVE' && btnval !='Approved'&& btnval !='Rejected'&& btnval !='Canceled')
           {
                   notyMsg(status,msg);
                   $('.ajaxLoading').hide();
                   window.location.href=create_url;
           }
           else
           {
                   notyMsg(status,msg);
                   $('.ajaxLoading').hide();
                   window.location.href=red_url;
                   
           }
		});  
		}
	}
	else
	{
		$('.ajaxLoading').show();
                change_date();
          	var formdata	= $('#po_form').serialize();
              
                
                var form_data = new FormData(document.getElementById('po_form'));   
                $.ajax({
                  url: url,
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,   // tell jQuery not to set contentType
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                }
                                        //update progressbar

                                }, true);
                        }
          return xhr;

                }
                }).done(function(data)
		{
					
			var status = data.status;
                        var msg     = '<span style="color:#090065">'+data.auto_no+'</span>  '+data.message;
                        var id     = data.id;
                                var edit_url	="{{ URL::to('purchaseordercreate') }}/"+id;
                                notyMsg(status,msg);
                             $('.ajaxLoading').hide();
                             window.location.href=edit_url;
                               
		});
        }
});
/*Karthigaa Purpose For Price not Assign POPup From Enquiry Products*/
    <?php if(isset($pocount) && $pocount!=0){   ?>
                      setTimeout(function () {
                     swal({
      title: "Price not assigned for {{$pocount}} Products",
      text: "You want to add price for this product",
      type: "warning",
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes",
      cancelButtonText: "No",
      closeOnCancel:!1
    }, function(e) {
    if(e == true)
      {
    var id=$(".po_pricelist_id").val();
      var url ="{{ URL::to('purchasepricelistedit') }}/" +id;
       window.open(url);
      }
      else
      {
        swal("Cancelled");
      }
    });
     }, 500);
        <?php
        }
        ?>
                /*End*/
	});
        
  /*Purpose For Qty 0 Submit Validation*/          
 function qtyrequired(){
         $(".bulk_qty").each(function(index){
          var req=$(this).val();
            if(req==0){
                $(".bulk_qty").val('');
            }
            });
        }
        
function changeClassName(className)
{
$('.' + className).each(function (index)
{
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}
else if( className == "bulk_promised_date" && index >0 ){
	var dates0=$('.bulk_promised_date0').val();
        
    $('.bulk_hidden_date').val(dates0);
	var dates=$('.bulk_promised_date'+index).val();
        
       if(dates0 == dates || dates =="undefined" || dates=="" ) {
           $(this).val(dates0);
           
            }
	$('.dates'+index).css("pointer-events","none");
}
$(this).removeClass(className + '0');
$(this).addClass(className + index);
});
}
/************ karthigaa purpose to remove row action ********************/
function removeClass(className)
{
	var rowCount = $('.po_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.po_table tbody tr').find('.'+className).removeClass(className+i);
	}
	$('.' + className).each(function (index)
	{
		if (className == "bulk_line_no")
		{
		$(this).val(index + 1).attr("readonly", 1);
		}
		$(this).addClass(className + index);
	});
}
function changeclassfields(){
    changeClassName('bulk_po_line_id');
    changeClassName('bulk_line_no');
    changeClassName('bulk_product_id');
    changeClassName('bulk_uom_code_id');
    changeClassName('bulk_qty');
    changeClassName('bulk_unit_price');
    changeClassName('bulk_part_no');
    changeClassName('partno');
    changeClassName('bulk_discount_percentage');
    changeClassName('bulk_discount_amount');
    changeClassName('bulk_line_subtotal');
    changeClassName('bulk_tax_group_id');
    changeClassName('bulk_tax_amount');
    changeClassName('bulk_line_total');
    changeClassName('bulk_promised_date');
    changeClassName('bulk_promised_alternate_date');
    changeClassName('bulk_comments');
    changeClassName('bulk_received_qty');
    changeClassName('bulk_part_number');
    changeClassName('bulk_min_order');
    changeClassName('bulk_max_order');
    changeClassName('bulk_hsn_code');
    changeClassName('bulk_qoh_qty');
    }
function removeclassfields(){
        removeClass('bulk_line_no');
        removeClass('bulk_product_id');
        removeClass('bulk_uom_code_id');
        removeClass('bulk_qty');
        removeClass('bulk_unit_price');
        removeClass('bulk_discount_percentage');
        removeClass('bulk_discount_amount');
        removeClass('bulk_line_subtotal');
        removeClass('bulk_tax_group_id');
        removeClass('bulk_tax_amount');
        removeClass('bulk_line_total');
        removeClass('bulk_part_no');
        removeClass('partno');
        removeClass('bulk_promised_date');
        removeClass('bulk_promised_alternate_date');
        removeClass('bulk_comments');
        removeClass('bulk_received_qty');
        removeClass('bulk_part_number');
        removeClass('bulk_min_order');
        removeClass('bulk_max_order');
        removeClass('bulk_hsn_code');
        removeClass('bulk_qoh_qty');
    }
</script>
<script>
$(window).on('load',function(){
       //preloader
       var preLoder = $("#preloader");
       preLoder.fadeOut(500);
       var backtoTop = $('.back-to-top')
       backtoTop.fadeOut(100);
   });

    function preventBack() {
    window.history.forward();
}
 window.onunload = function() {
    null;
};
setTimeout("preventBack()", 0);


 $(function () {
     $('input[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){
                var column = "table ." + $(this).attr("name");
                      $(column).css("display","none");
            }
            else if($(this).prop("checked") == false){
                   var column = "table ." + $(this).attr("name");
                      $(column).css("display","");
            }
        });

   });



</script>

@include('layouts.php_js_validation')
@endsection
