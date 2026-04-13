@extends('layouts.header')
@section('content')
<?php error_reporting(0);?>

<style>
	/*
[class*="col-"] {
    margin-bottom: 10px;
}*/
.rem {
    font-size: 21px;
    color: red;
    padding: 3px;
}

	.panel-group .panel {
		border-radius: 5px;
		border-color: #EEEEEE;
        padding:0;
	}
	.panel-default > .panel-heading {
		color: #fff;
		background-color: #6f8be6fc;
		border-color: #EEEEEE;
                line-height:0.7;
	}
	.panel-title {
		font-size: 14px;
	}
	.panel-title > a {
		display: block;
		padding: 0px;
		text-decoration: none;
	}
	.short-full {
		float: right;
		color: #fff;
	}
	.panel-default > .panel-heading + .panel-collapse > .panel-body {
		border: solid 0px #EEEEEE;
               padding:0px;
	}

</style>
<body>
<span class="ui_close_btn"></span>
<div class="ajaxLoading"></div>
<div class="container main_container">
<div class="row">
<div class="col-lg-12 col-md-12">
</div>
</div>
	<div class="row">
<div class="col-lg-1">
</div>
<form method="post" action="{{ url('salesinvoicesave') }}" id="salesinvoice" class="salesinvoice" data-parsley-validate>
	<input type="hidden" id="decimal_point" value="" />
        <input type="hidden" value="" name="savestatus" id="savestatus" />
{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<strong>Sales Invoice</strong>
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{URL::to('salesinvoice')}}'"></a></span>
</div>
<div class="card-body card-block">
	<?php include("tools_menu.php");?>
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Invoice No</label>
			<div class="col-md-6">
			<input class="form-control invoice_hdr_id" id="invoice_hdr_id" name="invoice_hdr_id" size="16" type="hidden" value="{{ $row->invoice_hdr_id }}" readonly>
				<input type="text" id="invoice_number" name="invoice_number" class="form-control invoice_number" value="{{ $row->invoice_number }}" readonly>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Invoice Date</label>
			<div class="col-md-6">
			<div class="input-group  form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="dd-mm-yyyy">
			<input class="form-control invoice_date" id="invoice_date" name="invoice_date" size="16" type="text" value="{{ $row->invoice_date }}" readonly>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="invoice_date" value="{{ $row->invoice_date }}" />
			</div>
                        
<!--                        <div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control inquiry_date" id="inquiry_date" name="inquiry_date" size="16" type="text" value="{{ $row->inquiry_date }}" readonly>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="inquiry_date" value="{{ $row->inquiry_date }}" />
			</div>-->
                        
                        
			<div class="col-md-2 showline">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Invoice Type</label>
			<div class="col-md-6">
				<select type="invoice_type" name="invoice_type" id="invoice_type" class="form-control invoice_type" required readonly>
					<option value="">--select--</option>
					<option <?php if($row->invoice_type =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
					<option <?php if($row->invoice_type =="LABOUR") { echo "selected"; } else { echo ""; } ?> value="LABOUR">LABOUR</option>
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Invoice Status</label>
			<div class="col-md-6">
				<select type="invoice_status" name="invoice_status" id="invoice_status" class="form-control invoice_status" value="{{ $row->invoice_status }}" readonly>
					<option value="">--select--</option>
					<option <?php if($row->invoice_status =="DRAFT") { echo "selected"; } else { echo ""; } ?> value="DRAFT">DRAFT</option>
					<option <?php if($row->invoice_status =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
					<option <?php if($row->invoice_status =="APPROVED") { echo "selected"; } else { echo ""; } ?> value="APPROVED">APPROVED</option>
					<option <?php if($row->invoice_status=="REJECTED") { echo "selected"; } else { echo ""; } ?> value="REJECTED">REJECTED</option>
					<option <?php if($row->invoice_status=="CANCELLED") { echo "selected"; } else {  echo ""; } ?> value="CANCELLED">CANCELLED</option>
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>



<div class="form-group row">
	<label class="form-control-label col-md-4" for="pricelist_id"><span style="font-size:20px;color:red;">*</span>PriceList</label>
	<div class="col-md-6">
			<select name='pricelist_id'  class='pricelist_id select2' id="pricelist_id" required>
			{!! $pricelist !!}
			</select> 
		</div>
	<div class="col-md-1 showline">
					<span class="showspan"> <i class="fa fa-refresh jcr_quote_pricelist_id"></i></span>
				</div>
		
</div>
<div class="form-group row">
<label for="inputIsValid" class="form-control-label col-md-4">Source</label>
<div class="col-md-6">
<select name="source" rows="5" class="form-control source" data-show-subtext="true" data-live-search="true" readonly="">
<option value="">--select--</option>
<option <?php if($row->source =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
<option <?php if($row->source =="SALESQUOTE") { echo "selected"; } else { echo ""; } ?> value="SALESQUOTE">SALESQUOTE</option>
<option <?php if($row->source =="SALESORDER") { echo "selected"; } else { echo ""; } ?> value="SALESQUOTE">SALESORDER</option>
</select>
</div>
<div col-md-2>

</div>
</div>
<div class="form-group row">
	<label for="inputIsValid" class="form-control-label col-md-4">Reference No</label>
	<div class="col-md-6">
		<input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{{$row->reference_id }}" readonly="">
		<input type="text" id="reference_number" name="reference_number" class="form-control reference_number" value="{{ $row->reference_number }}" readonly="">
	</div>
	<div col-md-2>

	</div>
</div>

	</div>
                <div class="col-md-4">
                    <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-4"><span style="font-size:20px;color:red;">*</span> Customer Name</label>
                            <div class="col-md-6">
                                    <select name='ship_to_customer_id' rows='5' class='ship_to_customer_id select2' data-show-subtext="true" data-live-search="true"  required >
                                    {!! $ship_to_customer_id !!}
                                    </select>
                            </div>
                            <div class="col-md-2 showinline">
                                <span class="showspan"><i class="fa fa-search customersearch"></i> </span>
                                <span class="showspan"><i class="fa fa-refresh jcr_customer_id"></i></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-4">Bill To Address</label>
                                <div class="form-group hidethis" style="display:none;">
                                    <input type="text" name="bill_to_address_id" id="bill_to_address_id" value="{{ $bill_to_address_id }}" class="form-control bill_to_address_id">
                                </div>
                                <div class="col-md-6">
                                                <textarea name='billing_to_address_txt' rows='5' id='billing_to_address_txt' class='form-control billing_to_address_txt' readonly="true">{{$billing_to_address_txt}}</textarea>
                                </div>
                                <div class="col-md-2 showinline">
                                </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputIsValid" class="form-control-label col-md-4">Ship To Address</label>
                            <div class="form-group hidethis" style="display:none;">
                                <input type="text" name="ship_to_address_id" id="ship_to_address_id" value="{{ $ship_to_address_id }}" class="form-control ship_to_address_id" >
                            </div>
                            <div class="col-md-6">
                                    <textarea name='shipping_to_address_txt' rows='5' id='shipping_to_address_txt' class='form-control shipping_to_address_txt' readonly="true"><?php echo $shipping_to_address_txt; ?>             </textarea>
                            </div>
                            <div class="col-md-2 showinline">
                                <button type="button" class="btn shipto btn-success btn-xs" value="shipto">
                                <i class="fa fa-address-book" aria-hidden="true"></i> Change Address</button>
                                <input type="hidden" id="custype" value="" class="form-control custype">
                            </div>
                        </div>

                </div>
		<div class="col-md-4">
<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Project Name</label>
			<div class="col-md-6">
			<select name='project_id' rows='5' class='project_id select2' data-show-subtext="true" data-live-search="true" >
				{!! $project_id !!}
				</select>
			</div>
			<div class="col-md-2 showline">
					<span class="showspan"> <i class="fa fa-refresh jcr_project_id"></i></span>
				</div>
		</div>
		 <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Salesperson Name</label>
			<div class="col-md-6">
			<select name='salesperson_id' rows='5' class='salesperson_id select2' data-show-subtext="true" data-live-search="true" >
				{!! $salesperson_id!!}
				</select>
			</div>
			<div class="col-md-1 showline">
					<span class="showspan"> <i class="fa fa-refresh jcr_salesperson_id"></i></span>
				</div>
		</div>
             <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
			<div class="col-md-6">
			<textarea name="remarks" id="remarks" value="{{ $row->remarks }}" class="form-control remarks"></textarea>
			</div>
			<div class="col-md-2">
			</div>
		</div>

		<div class="form-group row">
	<label for="inputIsValid" class="form-control-label col-md-4">Invoice Tax Total</label>
	<div class="col-md-6">
	<input type="text" name="invoice_tax_total" id="invoice_tax_total" value="{{ $row->invoice_tax_total }}" class="form-control invoice_tax_total" readonly>
	</div>
	<div class="col-md-2">
	</div>
	</div>
              <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Invoice Grand Total</label>
			<div class="col-md-6">
				<input type="text" name="invoice_grand_total" id="invoice_grand_total" value="{{ $row->invoice_grand_total }}" class="form-control invoice_grand_total" readonly>
			</div>
			<div class="col-md-2">
			</div>
		</div>

			
	</div>
</div>
<!--deepika: Purpose For Additional Details Config -->
    <div class="col-lg-12 panel-group " id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="headingTwo">
				<h4 class="panel-title">
					<a class="collapsed" id="panel_add" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						<i class="short-full glyphicon glyphicon-plus"></i>
						Additional Details
					</a>
				</h4>
			</div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
            	<div class="col-md-6 panel-body">       
                     
	                <?php $i=0; $j=0; foreach($enabled_columns as $index=>$val) {  if($val->action=='1') $required="required"; else $required='';
	               	if($i!=$j) { $j=$i;   ?>
             	</div>
             	<div class="col-md-6 panel-body">       

                <?php } 
                if($val->column_name=='payment_term_id' && $val->active==1) { $i++; ?>   
                	<div class="form-group row panel-body payment_term_id_cfg" >
                        <label for="inputIsValid" class="form-control-label col-md-4">Payment Term <?php if($required != '' ) { ?>
                        <span style="font-style:20px;color:red;">*</span> <?php } ?> </label>
                        <div class="col-md-6">
                            <select name='payment_term_id' <?php echo $required; ?> class='form-control payment_term_id select2' data-show-subtext="true" data-live-search="true" >
                            	{!! $payment_term_id !!}     
                            </select>
                        </div>
                        <div class="col-md-2 showline">
                            <span class="showspan"> <i class="fa fa-refresh jcr_payment_term_id"></i></span>
                        </div>
                	</div>
	 			<?php } 
	 			if($val->column_name=='payment_method_id' && $val->active==1) { $i++; ?>   
		 			<div class="form-group row panel-body payment_method_id_cfg">
						<label for="inputIsValid" class="form-control-label col-md-4">Payment Method <?php if($required != '' ) { ?>
                        <span style="font-style:20px;color:red;">*</span> <?php } ?> </label>
						<div class="col-md-6">
							<select name='payment_method_id' <?php echo $required; ?> class='form-control payment_method_id select2' data-show-subtext="true" data-live-search="true" >
                            	{!! $payment_method_id !!}     
                            </select>
						</div>
						<div class="col-md-2 showline">
                            <span class="showspan"> <i class="fa fa-refresh jcr_payment_method_id"></i></span>
                        </div>
		 			</div>
	 			<?php } 
	 			if($val->column_name=='created_by' && $val->active==1) { $i++; ?>   
		 			<div class="form-group row panel-body created_by_cfg">
						<label for="inputIsValid" class="form-control-label col-md-4">Created By <?php if($required != '' ) { ?>
                        <span style="font-style:20px;color:red;">*</span> <?php } ?> </label>
						<div class="col-md-6">
							<select name='created_by' <?php echo $required; ?> class='form-control created_by' data-show-subtext="true" data-live-search="true" style="pointer-events:none;">
                            	{!! $created_by !!}     
                            </select>
						</div>
						<div class="col-md-2">
						</div>
		 			</div>
	 		
	 			<?php } } ?>
	 			
        		</div>
        	</div>

		</div>

	</div> 
</div>
      <!--*****************  End  *********** -->
<a href="javascript:void(0);" class="add_row btn-info" rel=".clone" style="width:7%;margin-left:1%"><i class="fa fa-plus"></i> New Item</a>
<a style="display:none" href="javascript:void(0);" id="btnAdd"  class="onclickrel btn-info"  rel=".rcopy" style="padding:4px;width:8%"><i class="fa fa-plus"></i> Relcopy</a>
<br>
<!-------------------------Linedata -------------------------------->
<div class="table-responsive subgrid_div">
<table class="table table-striped sales_invoice_table">
<thead>
<tr>

<th></th>
<th id="line">Line No</th>
<th id="prod">Product </th>
<th id="3"></th>
<th id="desc">Product Description</th>
<th id="5">Uom Code </th>
<th>SO Qty</th>
<th>Qty</th>
<th>Price</th>
<th>Discount(%)</th>
<th>Discount Amount</th>
<th id="tax">Tax Group</th>
<th id="taxamt">Tax Amount</th>
<th>Line Total</th>

	<th>QOH</th>
	<th>Comments</th>
<th></th>
<th></th>
</tr>
</thead>
<tbody class="so_inv_lines_body">
<?php if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)
<tr class="clone rcopy clonedInput">


<td><input type="hidden" name="bulk_invoice_line_id[]" class="form-control input-sm bulk_invoice_line_id" value="{{ $value->invoice_line_id }}"></td>
<td><input type="text" name="bulk_line_no[]" class=" input-sm bulk_line_no " value="{{ $key + 1 }}" readonly="readonly"></td>
<td id="blk">
    <select  name="bulk_product_id[]" class="select2 bulk_product_id  " parsley-validated  style="width:300px" required="required">{!! $value->product_id !!}</select>
</td>
<td><i class="fa fa-search productsearch"></i></td>
<td id="des">
<input type="text" name="bulk_description[]" class="form-control bulk_description" value="{{ $value->product_description }}" style="width:250px" required="required" >
</td>
<td>
<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id" style="width:90px">
{!! $value->uom_code_id !!}
</select>
</td>

<td>
<input type="text" name="bulk_salesorder_qty[]" class="form-control input-sm bulk_salesorder_qty input_qty_width" value="{{ $value->salesorder_qty }}" minlength="1" maxlength="4" style="width:90px" >
</td>

<td>
<input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{ $value->qty }}" minlength="1" maxlength="4" required="required" style="width:90px" >
</td>
<td>
<input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price input_qty_width" value="{{ $value->unit_price }}" required="required" style="width:90px" >
</td>
<td>
<input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage input_qty_width" value="{{ $value->discount_percentage }}"  style="width:90px">
</td>
<td>
<input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount input_qty_width" value="{{ $value->discount_amount }}"style="width:90px" readonly="true">
</td>
<td id="tax1">
<select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="form-control bulk_tax_group_id" style="width:80px">
{!! $tax_group_id !!}
</select>
</td>
<td id="taxamount">
<input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount input_qty_width" value="{{ $value->tax_amount }}" required="required" style="width:90px" >
</td>
<td>
<input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total input_qty_width" value="{{ $value->line_total }}"  style="width:120px">
</td>
	
	<td>
<input type="text" name="bulk_qoh" id="bulk_qoh" class="form-control bulk_qoh" value="{{ $value->qoh }}" >
</td>
	
	
	
<td>
<textarea name="bulk_comments[]" class="form-control input-sm bulk_comments input_qty_width" row="5" value="{{ $value->comments }}" ></textarea>
</td>

<td>
<a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
<input type="hidden" name="counter[]">
</td>
</tr>
@endforeach
<?php } if(count($linedata) < 1 ) { ?>
<tr class="cloneRow clone rcopy clonedInput">

    <td><input type="hidden" name="bulk_invoice_line_id[]" class="form-control input-sm bulk_invoice_line_id" value=""></td>
    <td><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no " value="1" readonly="readonly"  style="width:70px"></td>
    <td id="blk">
        <select name="bulk_product_id[]" class="select2 bulk_product_id"  parsley-validated required="required"  style="width:300px">{!! $product_id !!}</select>
    </td>
<td><i class="fa fa-search productsearch"></i></td>
<td id="des">
<input type="text" name="bulk_description[]" class="form-control bulk_description" value="" style="width:250px" required="required" >
</td>
<td>
<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="select2 bulk_uom_code_id"  style="width:90px">
{!! $uom_code_id !!}
</select>
</td>
<td>
<input type="text" name="bulk_salesorder_qty[]" class="form-control bulk_salesorder_qty" value="" style="width:80px" >
</td>
<td>
<input type="text" name="bulk_qty[]" class="form-control bulk_qty " value="" minlength="1" maxlength="4" required="required"  style="width:80px">
</td>
<td>
<input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price " value="" required="required"  style="width:80px" >
</td>
<td>
<input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage " value=""  style="width:70px">
</td>
<td>
<input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount " value=""  style="width:70px" readonly="true">
</td>

<td id="tax1">
<select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="select2 bulk_tax_group_id"  style="width:80px">
{!! $tax_group_id !!}
</select>
</td>
<td id="taxamount">
<input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount" value="" required="required"  style="width:100px" >
</td>
<td>
<input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total" value=""  style="width:120px" >
</td>
	
	<td>
<input type="text" name="bulk_qoh" id="bulk_qoh" class="form-control bulk_qoh" value="{{ $value->qoh }}" >
</td>
	
<td>
<textarea name="bulk_comments[]" class="form-control input-sm bulk_comments" value=""  row="5" style="width:200px" ></textarea>
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
</div>
<!-------------------------Linedata End-------------------------------->

<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">

			<?php 
			//dd($return_url);
			if($return_url == "salesinvoice") { ?>
			<button type="button" class="btn applychanges saveform" value="APPLYCHANGES">Apply Changes</button>
			<button type="button" class="btn save saveform" value="DRAFT">Draft</button>
            <button type="button" class="btn save saveform"   value="SAVENEW">Save and New</button>
			<button type="button" class="btn save saveform" value="SAVE">Save</button>
			<?php } else { ?>
				 <button type="button" name="submit" class="btn save saveform  approve" value="APPROVED">Approve</button>
	   <button type="button" name="submit" class="btn save   saveform reject" value="REJECTED">Reject</button>
	     <?php } ?>
<a class='btn cancel' onclick='location.href="{{ url($pageModule) }}"'>Cancel</a>
		</div>
	</div>

</div>







</div>

<!-- karthigaa purpose customer search jqgrid model-->
<div class="modal fade" id="customerModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
		<!--Moda Header-->
      <div class="modal-header">
		  <h4 class="modal-title"> Customer Details </h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
		<!-- Modal Body -->
	  <div class="modal-body">
	      <table id="customergrid"></table>
	  </div>
		 <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
	<!--end-->

	<!-- karthigaa purpose customer search jqgrid model-->
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
	<!--end-->
<input type="hidden" class="pdtindex" value="" />



</div>
	</form>
	<div class="col-lg-1">
	</div>
</div>
@extends('layouts.footer')
</div>
</body>
<style>
.invoice_type,.invoice_status,.organization_id,.source,.bulk_uom_code_id{
pointer-events:none;	
	}
</style>
<script>
$(document).ready(function(){
	


$(".additional").trigger();

$(".select2").select2();
$(".select2").css('width','100%');
	
$('.bulk_salesorder_qty,.bulk_tax_amount,.bulk_line_total,#bulk_qoh').attr('readonly',true);
var source='<?php echo $row->invoice_type; ?>';

		if(source=='LABOUR')
		{
				$('#prod,#blk').hide();
				$('#desc,#des').show();
				$('.bulk_product_id').removeAttr('required');
		}
		else {
			$('#prod,#blk').show();
			$('.bulk_product_id').attr('required');
			$('.bulk_description').removeAttr('required');
				$('#desc,#des').hide();

		}

	$(document).on('click','.approve',function(){
	alert();
	$("#invoice_status").val("APPROVED").change();
	});
	
		$(document).on('click','.reject',function(){
	
	$("#invoice_status").val("REJECTED").change();
	});
	
	
	

$(document).on('click','.saveform',function()
    {
            var btnval		= $(this).val();

            if(btnval == 'APPLYCHANGES')
                $("#invoice_status").val('DRAFT');
            else if(btnval == 'DRAFT')
                $("#invoice_status").val('DRAFT');
            else if(btnval == 'APPROVED')
                $("#invoice_status").val('APPROVED');
                else if(btnval == 'REJECTED')
               $("#invoice_status").val('REJECTED')
            else
               $("#invoice_status").val('INITIATED');


            if(btnval == 'APPLYCHANGES')
                var savestatus = 'APPLY CHANGES';
            else if(btnval == 'DRAFT')
                var savestatus = 'DRAFT';
            else if(btnval == 'SAVE' || btnval == 'SAVENEW')
                var savestatus = 'SAVE';
            else if(btnval == 'APPROVED')
                    var savestatus ='APPROVED';
            else if(btnval=='REJECTED')
                    var savestatus='REJECTED';
	
            $('#savestatus').val(savestatus);

            var url		= "{{ url('salesinvoicesave') }}";
            var red_url		="{{ url('salesinvoice') }}";
            var create_url	="{{ url('salesinvoicecreate') }}/0";
            validationrule('salesinvoice');
            var formdata	= $('#salesinvoice').serialize();
            var form = $('#salesinvoice');

            if(btnval != 'APPLYCHANGES')
            {
                form.parsley().validate();
                var form = $('#salesinvoice');
                form.parsley().validate();
                if (form.parsley().isValid())
                {
                	$(".ajaxLoading").show();
                    $.post(url,formdata,function(data)
                    {
						console.log(data);
                        var status      = data.status;
                        var msg         = data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('salesinvoicecreate') }}/"+id;
                        if(btnval !='SAVE' && btnval !='DRAFT' && btnval == 'APPROVED' && btnval == 'REJECT')
                        {
//alert();
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=create_url;
                            }, 1500);
                        }
                        else
                        {
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=red_url;
                            }, 1500);
                        }
                    });
                }
            }
            else
            {
            	$(".ajaxLoading").show();
            $.post(url,formdata,function(data)
            {

                    var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;
                    var edit_url	="{{ url('salesinvoicecreate') }}/"+id;
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=edit_url;
                            }, 1500);

                });
            }
    });



$(document).on('click','.jcr_customer_id',function()
{
    $(".ship_to_customer_id").jCombo("{{ URL::to('jcomboform?table=m_customers_t:customer_id:customer_name') }}&order_by=customer_name asc",
    {selected_value:""});
    $('.billing_to_address_txt').val('');
    $('.shipping_to_address_txt').val('');
});
	
$(document).on('click','.jcr_ship_to_address_id',function()
{
    $(".ship_to_address_id").jCombo("{{ URL::to('jcomboform?table=m_customer_sites_t:customer_site_id:customer_site_name') }}&order_by=customer_site_name asc",
    {selected_value:""});
});

$(document).on('click','.jcr_project_id',function(){
$(".project_id").jCombo("{{ URL::to('jcomboform?table=m_projects_t:project_id:project_name') }}&order_by=project_name asc",
{selected_value:""});
});

$(document).on('click','.jcr_pricelist_id',function(){
$(".pricelist_id").jCombo("{{ URL::to('jcomboform?table=i_pricelist_hdr_t:pricelist_hdr_id:pricelist_name') }}&order_by=pricelist_name asc",
{selected_value:""});
});

$(document).on('click','.jcr_salesperson_id',function(){
$(".salesperson_id").jCombo("{{ URL::to('jcomboform?table=s_salesperson_t:salesperson_id:salesperson_name') }}&order_by=salesperson_name asc",
{selected_value:""});
});

	$(document).on('click','.jcr_payment_term_id',function(){
$(".payment_term_id").jCombo("{{ URL::to('jcomboform?table=m_payment_terms_t:payment_term_id:payment_term_name') }}&order_by=payment_term_name asc",
{selected_value:""});
});
	
$(document).on('click','.jcr_payment_method_id',function(){
$(".payment_method_id").jCombo("{{ URL::to('jcomboform?table=m_payment_methods_t:payment_method_id:payment_method_name') }}&order_by=payment_method_name asc",
{selected_value:""});
});
var x=0;
var url ='decvalue';
$.get(url,function(data){

 var decimalpoints = data;
 $('#decimal_point').val(decimalpoints); //alert(decimalpoints);
});
 setTimeout(function(){
}, 1000);






$(document).on('change','.bulk_product_id',function()
{
var index=$(this).closest('tr').index();
var pid=$(this).val();

	
var type = 'so';
var plid=$('.pricelist_id').select2('val');
var cid=$('.customer_id option:selected').val();
var url="{{ url::to('productdetails_so') }}/"+pid+"/"+plid+"/"+type;
	
if(cid !='')
{
	
	if(plid !='')
	{
            if(pid !='')
            {
                    var pdtcount = 0;
                    $('.clone').each(function (ind, v)
                    {
                            var val = $(".bulk_product_id" + ind).select2('val');
                            if(index != ind) 
                            {
                            if(val == pid)
                            {
                                    pdtcount++;
                            }
                            }
                    });
                            if(pdtcount <= 0)
                            {
                            $.get(url,function(data)
                            {
                                    if(data.unit_price==0)
                                    {
                                        notyMsg("error",'There is no Pricelist For this Product');
                                        $('.bulk_uom_code_id'+index).select2('val','');
                                        $('.bulk_unit_price'+index).val(0);
                                        $('.bulk_tax_group_id'+index).val('');
                                        $(".bulk_product_id" + index).select2('val','');
                                    }
                                      
                                    $('.bulk_uom_code_id'+index).select2('val',[data.uom_code_id]);
                                    $('.bulk_unit_price'+index).val(data.unit_price);
                                    $('.bulk_tax_group_id'+index).val(data.tax_group_id);
                                    $('.bulk_qoh'+index).val(data.qoh_qty);
                                    calc_by_index(index);
                            });
                            }
                            else
                            {
                                    notyMsg('info','Product Already Selected');
                                    $(".bulk_product_id" + index).select2('val','');
                            }

            }
            else
            {
            $('.bulk_uom_code_id'+index).select2('val','');
            $('.bulk_unit_price'+index).val(0);
            $('.bulk_tax_group_id'+index).val('');
            $('.bulk_qoh'+index).val('');
            calc_by_index(index)
            }
	}
	else
	{
		$('.bulk_uom_code_id'+index).select2('val','');
		$('.bulk_unit_price'+index).val(0);
		$('.bulk_tax_group_id'+index).val('');
		$('.bulk_product_id'+index).select2('val','');
                $('.bulk_qoh'+index).val('');
		calc_by_index(index);
		alert('Please Select Pricelist !!!');
	}
}
else
{
alert('Please Select Customer !!!');
	$('.bulk_uom_code_id'+index).select2('val','');
	$('.bulk_unit_price'+index).val(0);
	$('.bulk_tax_group_id'+index).val('');
	$('.bulk_product_id'+index).select2('val','');
        $('.bulk_qoh'+index).val('');
}
	
});


$(document).on('change','.pricelist_id',function()
{
var cusid=$('.ship_to_customer_id').select2('val');
	if(cusid=="")
        {
           
                notyMsg("error","Please select Customer");
		$("#pricelist_id").select2("val", "");
                
        }
	$('.bulk_product_id').each(function(index)
	{
	var pid=$(this).val();
	var pricelistid=$('.pricelist_id option:selected').val();
	var url="{{ url::to('pricelistdetail') }}/"+pid+"/"+pricelistid;
		if(pricelistid !='')
		{
			if(pid !='')
			{
				$.get(url,function(data)
				{
					$('.bulk_unit_price'+index).val(data);
					calc_by_index(index);
				});
			}
			else
			{
				$('.bulk_unit_price'+index).val(0);
				calc_by_index(index);
			}
		}
		else
		{
		$('.bulk_unit_price'+index).val(0);
		calc_by_index(index);
		}
	});
});

	$(document).on('keyup change','.bulk_qty,.bulk_unit_price,.bulk_discount_percentage,.bulk_tax_group_id,.bulk_product_id',function()
{
		var index = $(this).closest("tr").index();
	    var unitprice = $('.bulk_unit_price'+index).val();
		var requiredqty = $('.bulk_qty'+index).val();
		var taxgrp = $('.bulk_tax_group_id'+index+' option:selected').attr('data-display');
	    taxgrp = taxgrp?taxgrp:0;
		var discountsperc = $('.bulk_discount_percentage'+index).val();
	    var disamout = (((requiredqty * unitprice) * discountsperc/100));
	    $('.bulk_discount_amount'+index).val(disamout);
	    var taxamount = ((requiredqty * unitprice) - disamout) * taxgrp /100;
	
		$('.bulk_tax_amount'+index).val(taxamount);
	    var subtot = ((requiredqty * unitprice) - disamout);
        var linetot=parseFloat(subtot + taxamount).toFixed($('#decimal_point').val());
		    $(".bulk_line_total"+index).val(linetot);
		/* Code for set linetotal values into header level field*/
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
			$('#invoice_tax_total').val(sumtax);
			$('#invoice_grand_total').val(sum);
		/* end */
		/* Code for calculating tot tax amount */
		var tax =0;
		$('.bulk_tax_amount').each(function(){
		   tax+= parseFloat($(this).val());
		});
		$('.quote_tax').val(tax);
		/* end */
});
	function calc_by_index(index)
{
var unit_price = $('.bulk_unit_price'+index).val();
var qty = $('.bulk_qty'+index).val();
var tax_group = $('.bulk_tax_group_id'+index).val();
var line_sub_total = parseFloat(unit_price * qty);
var tax_amount = parseFloat((line_sub_total*tax_group)/100);

$('.bulk_tax_amount'+index).val(tax_amount);

var linetot=parseFloat(line_sub_total+tax_amount).toFixed($('#decimal_point').val());

/* var discount=parseFloat((discountsperc*linetot)/100).toFixed($('#decimal_point').val());
$('.bulk_discountsamt'+index).val(discount);
var maintotcal = parseFloat(linetot-discount).toFixed($('#decimal_point').val());
*/
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
sum += parseFloat($(this).val());
});

$('.order_total').val(sum);

/* end */

/* Code for calculating tot tax amount */
var tax =0;
$('.bulk_tax_amount').each(function(){
tax+= parseFloat($(this).val());
});
$('.order_tax').val(tax);
/* end */

}





    $(document).on('keypress', '.bulk_unit_price,.bulk_qty','.bulk_discount_percentage', function(ev){
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) 
        {
            return true;
        }
        ev.preventDefault();
        return false;
    });

    var index = $('.clone').closest('tr').index();
    changeclassfields();
$(document).on('click','.add_row',function()
{   
	$('.onclickrel').trigger('click');
changeclassfields();

});

$(document).on('click','.remove',function()
{


	var index = $(this).closest('tr').index();

	var rowCount = $('.sales_invoice_table tbody tr').length;
	if(rowCount > 1)
	{
		$($(this).closest("tr")).remove();
                removeClass('bulk_line_no');
                removeClass('bulk_product_id');
                removeClass('bulk_uom_code_id');
                removeClass('bulk_qty');
                removeClass('bulk_unit_price');
                removeClass('bulk_salesorder_qty');
                removeClass('bulk_discount_percentage');
                removeClass('bulk_discount_amount');
                removeClass('bulk_line_subtotal');
                removeClass('bulk_tax_group_id');
                removeClass('bulk_tax_amount');
                removeClass('bulk_line_total');
                removeClass('bulk_promised_date');
                removeClass('bulk_comments');
	}
	else
	{
		alert("You Can't Delete Atleast One row should be there");
	}
	var sum = 0;
	var tax =0;

	$('.bulk_line_total').each(function(){

				sum += (isNaN(parseFloat($(this).val()))) ? 0 : parseFloat($(this).val());

	});
	$('.bulk_tax_amount').each(function(){

			 tax += (isNaN(parseFloat($(this).val()))) ? 0 : parseFloat($(this).val());
	});
	$('#invoice_grand_total').val(sum);
	$('#invoice_tax_total').val(tax);
});

$('.customersearch').click(function()
	{
	 $('#customerModal').modal('show');
	 $('#customerModal').width("100%");
	});
$(document).on('click','.productsearch',function()
	{
	var index = ($(this).closest('tr').index());
	 $('.pdtindex').val(index);
	 $('#productModal').modal('show');
	 $('#productModal').width("100%");
	});

/*deepika purpose:for customer search*/
var cusnameopt="{{ $cusnameopt }}";
var custypeopt="{{ $custypeopt }}";
var country="{{ $country }}";
var state="{{ $state }}";
var city="{{ $city }}";

	var mygrid = $("#customergrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mygrid.jqGrid('navButtonAdd',pagerSelector,options);
        mygrid.jqGrid('navButtonAdd','#'+mygrid[0].id+"_toppager",options);
    };
          mygrid.jqGrid({
          url: "{{ URL::to('getCustomergridData') }}",
datatype: "json",
mtype: "GET",
			height: 320,
			width: 1000,
             colModel: [
						{ name: "customer_id", label: "id",hidden:true, width:55},
						{ name: "pincode", label: "pincode",hidden:true, width:55},
						{ name: "contact_number", label: "contact_number",hidden:true, width:55},
						{ name: "customer_site_id", label: "id",hidden:true, width:55},
                                                { name: "customer_number", label: "Customer Number", width:55},
		 				{ name: "customer_name", label: "Customer Name",stype:'select', editoptions:{value:cusnameopt}, width:55},
                                                { name: "customer_type_id", label: "Customer Type", width:55,stype:'select', editoptions:{value:custypeopt}},
		 				{ name: "customer_site_name", label: "Customer Site Name", width:55,},
		 				{ name: "site_type", label: "Customer Site Type", width:55},
		 				{ name: "address", label: "Address", width:55},
		 				{ name: "city", label: "City", width:55,stype:'select', editoptions:{value:city}},
		 				{ name: "state", label: "State", width:55,stype:'select', editoptions:{value:state}},
		 				{ name: "country", label: "Country", width:55,stype:'select', editoptions:{value:country}}
				             ],

						iconSet: "fontAwesome",
						rowNum: 10,
						rowList: [10,20,100,1000],
						sortorder: "asc",
						viewrecords: true,
						gridview: true,
						rownumbers:true,
						caption: "Customer",
						pager: pagerSelector,
						toppager:true,
						searching: {
						defaultSearch: "cn"
						}
		   });
//	(".ui-search-toolbar").hide();
jQuery(mygrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	$('#gs_customergrid_customer_name').select2();
	$('#gs_customergrid_customer_type_id').select2();
	$('#gs_customergrid_city').select2();
	$('#gs_customergrid_state').select2();
	$('#gs_customergrid_country').select2();

	$('.customersearch').click(function(){
            $('#customerModal').modal('show');
            $('#customerModal').width("100%");
            var ct=$(this).val(); 
           
            $('.custype').val(ct);
            $(mygrid).jqGrid('setGridParam', {
                postData: {"site_type":null,"cid":null }
            }).trigger('reloadGrid');
	});

        $('.shipto').click(function()
        {
                var cid=$('.ship_to_customer_id').select2('val');
                if(cid!="")
                {
                    $('#customerModal').modal('show');
                    $('#customerModal').width("100%");
                }
                else
                {
                    notyMsg("error","Please select Customer");		   
                }
                
                var ct=$(this).val();
                $('.custype').val(ct);
                var custype= $('.custype').val();

                if(ct=="shipto")
                {
                    var site_type="SHIP_TO";
                }
                $(mygrid).jqGrid('setGridParam', {
                postData: {"site_type":site_type,"cid":cid }
                }).trigger('reloadGrid');
        });

mygrid.jqGrid('navGrid',pagerSelector,
{cloneToTop:true,edit:false,add:false,del:false,search:true});
myAddButton ({
caption:"Select Customer",
title:"Customer",
buttonicon :'ui-icon-plus',
        onClickButton:function()
        {
        var gr = jQuery(mygrid).jqGrid('getGridParam','selrow');
        var customerdata = jQuery(mygrid).jqGrid ('getRowData', gr, 'customer_id');

        if( customerdata.customer_id != false )
        {
            $('.ship_to_customer_id').val(customerdata.customer_id);
            $('.ship_to_address_id').val(customerdata.customer_site_id);
            $('.shipping_to_address_txt').val(" "+customerdata.customer_site_name+","+customerdata.address+","+customerdata.city+","+customerdata.state+"-"+customerdata.pincode+","+customerdata.country+"."+"Contact No:"+customerdata.contact_number);
            $('#customerModal').modal('hide');
        }
        else
        {
            alert('Please Select one row');
        }
        }
});
	/*end*/
        $(document).on('change','.ship_to_customer_id',function()
        {
            var customer_id = $('.ship_to_customer_id').val();        
            var url = "{{ URL::to('sodispatchaddress') }}/"+customer_id;
            if(customer_id == "")
            {
                $('#billing_to_address_txt').val('');
                $('#shipping_to_address_txt').val('');
            }
        $.get(url,function(data)
        {
            console.log(data);
            if(data.length != 0)
            {
                if(data[1] != '')
                {    
                    var result_data=data[1].split('~');
                    $('.shipping_to_address_txt').val(result_data[0]);
                    $('.ship_to_address_id').val(result_data[1]);
                }  
                else
                {
                    $('.shipping_to_address_txt').val('');
                    $('.ship_to_address_id').val('');
					notyMsg("info","There is no Ship to address for this customer...");
                }
                if(data[0] != '')
                {
                    var result_data=data[0].split('~');
                    $('.billing_to_address_txt').val(result_data[0]);
                    $('.bill_to_address_id').val(result_data[1]);
                }
                else
                {
                    $('.billing_to_address_txt').val('');
                    $('.bill_to_address_id').val('');
					notyMsg("info","There is no Bill to address for this customer...");
                }
            }
            else{
                 $('.bill_to_address,.ship_to_address').val('');
				notyMsg("info","There is no Bill to and Ship to address for this customer...");
            }
        });
        
         
        var url = "{{ URL::to('sopricelist') }}/"+customer_id;
        $.get(url,function(data)
        {
            console.log(data);
            $('.pricelist_id').select2('val',[data]);
        });
        
    });
	  
	
//karthigaa purpose for product search grid
	var mypdtgrid = $("#productgrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mypdtgrid.jqGrid('navButtonAdd',pagerSelector,options);
        mypdtgrid.jqGrid('navButtonAdd','#'+mypdtgrid[0].id+"_toppager",options);
    };
		var groupname="FINISHED GOODS";
                var grp=[];
                grp.push(groupname);

	var prdcatopt="{{ $prdcatopt}}";
	var prdnameopt="{{ $prdnameopt }}";
            mypdtgrid.jqGrid({
            url: "{{ URL::to('getProductgridData') }}?prggrp="+grp,
			datatype: "json",
			mtype: "GET",
			height: 320,
			width: 1000,
             colModel: [
			{ name: "product_code", label: "Product Code", width:55},
		 	{ name: "product_group_id", label: "Product Group", width:55},
			{ name: "product_category_id", label: "Product Category",stype:'select', editoptions:{value:prdcatopt}, width:55},
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
			caption: "Customer",
			pager: pagerSelector,
			toppager:true,
			searching: {
			defaultSearch: "cn"
			}
		   });
            jQuery(mypdtgrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
            $("#gs_productgrid_product_category_id").select2();
mypdtgrid.jqGrid('navGrid',pagerSelector,
{cloneToTop:true,edit:false,add:false,del:false,search:true});
myAddButton ({
caption:"Select Product",
title:"Product",
buttonicon :'ui-icon-plus',
		onClickButton:function()
		{
			var index = $('.pdtindex').val();
			var gr = jQuery(mypdtgrid).jqGrid('getGridParam','selrow');
			var product = jQuery(mypdtgrid).jqGrid ('getCell', gr, 'product_id');
			if(product != false )
			{
			$('.bulk_product_id'+index).select2('val',[product]);
			//$('.bulk_product_id'+index).trigger('change');
			$('#productModal').modal('hide');
			}
			else
			{
			alert('Please Select one row');
			}
		}
});

$('.invoice_date').datepicker({format: 'dd/mm/yyyy', autoClose: true})

function changeClassName(className)
{
$('.' + className).each(function (index)
{
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}

$(this).removeClass(className + '0');
$(this).addClass(className + index);
});
}

/************ karthigaa purpose to remove row action ********************/
function removeClass(className)
{
	var rowCount = $('.sales_invoice_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.sales_invoice_table tbody tr').find('.'+className).removeClass(className+i);
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
changeClassName('bulk_invoice_line_id');
changeClassName('bulk_line_no');
changeClassName('bulk_product_id');
changeClassName('bulk_uom_code_id');
changeClassName('bulk_qty');
changeClassName('bulk_unit_price');
changeClassName('bulk_salesorder_qty');
changeClassName('bulk_discount_percentage');
changeClassName('bulk_discount_amount');
changeClassName('bulk_line_subtotal');
changeClassName('bulk_tax_group_id');
changeClassName('bulk_tax_amount');
changeClassName('bulk_line_total');
changeClassName('bulk_promised_date');
changeClassName('bulk_comments');
changeClassName('bulk_qoh');

}
	});
	</script>
	@include('layouts.php_js_validation')
@endsection
