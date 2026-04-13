@extends('layouts.header')
@section('content')
<?php error_reporting(0);?>
<body>
<span class="ui_close_btn"></span>
<div class="container main_container">

	<!------------------------- breadcrumbs start here --------------------------->
<div class="row">
<div class="col-lg-12 col-md-12">
<!--div class='steps'>
<div class='step complete'>Dashboard</div>
<div class='step active'>Sales</div>
<div class='step'>Sales-Create</div>
<div class='step'>Step 4</div>
</div-->
</div>
</div>
	<!---------------------------------------------------------------------------->

<div class="row">
<div class="col-lg-1">
</div>
<form method="post" action="" id="soinvoiceapproval" data-parsley-validate>
	<input type="hidden" id="decimal_point" value="" />
{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<strong>Sales Invoice</strong>
<span class="ui_close_btn"><a href="{{ URL::to('salesinvoiceapproval')}}" class="collapse-close pull-right btn-danger"></a></span>
</div>

<div class="card-body card-block">
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Invoice No</label>
			<div class="col-md-6">
			<input class="form-control invoice_hdr_id" id="invoice_hdr_id" name="invoice_hdr_id" size="16" type="hidden" value="{{ $row->invoice_hdr_id }}" readonly>
				<input type="text" id="invoice_number" name="invoice_number" class="form-control invoice_number" value="{{ $row->invoice_number }}" required>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Invoice Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control invoice_date" id="invoice_date" name="invoice_date" size="16" type="text" value="{{ $row->invoice_date }}" readonly>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="invoice_date" value="{{ $row->invoice_date }}" />
			</div>
			<div class="col-md-2 showline">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Invoice Type</label>
			<div class="col-md-6">
				<select type="invoice_type" name="invoice_type" id="invoice_type" class="form-control invoice_type" required>
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
				<select type="invoice_status" name="invoice_status" id="invoice_status" class="form-control invoice_status" value="{{ $row->invoice_status }}">
					<option value="">--select--</option>
					<option <?php if($row->invoice_status =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
					<option <?php if($row->invoice_status =="APPROVED") { echo "selected"; } else { echo ""; } ?> value="APPROVED">APPROVED</option>
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>



	</div>



	<div class="col-md-4">
<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Customer Name</label>
			<div class="col-md-6">
				<select name='ship_to_customer_id' rows='5' class='form-control ship_to_customer_id' data-show-subtext="true" data-live-search="true"  required >
				{!! $ship_to_customer_id !!}
				</select>
			</div>
			<div class="col-md-2 showinline">
			</div>
		</div>
          <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Ship To Address</label>
			<div class="col-md-6">
				<select name='ship_to_address_id' rows='5' class='form-control ship_to_address_id' data-show-subtext="true" data-live-search="true"  required >
				{!! $ship_to_address_id !!}
				</select>
			</div>
			<div class="col-md-2 showinline">

			</div>
		</div>
             <div class="form-group row" style="display:none">
			<label for="inputIsValid" class="form-control-label col-md-4">Pricelist Name</label>
			<div class="col-md-6">
			<select name='invoice_pricelist_id' rows='5' class='form-control invoice_pricelist_id' data-show-subtext="true" data-live-search="true" >

				</select>
			</div>
			<div class="col-md-1 showline">
				</div>
		</div>


		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Project Name</label>
			<div class="col-md-6">
			<select name='project_id' rows='5' class='form-control project_id' data-show-subtext="true" data-live-search="true" >
				{!! $project_id !!}
				</select>
			</div>
			<div class="col-md-2 showline">
				</div>
		</div>



		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Organization</label>
			<div class="col-md-6">
				<select name='organization_id' rows='5' class='form-control organization_id'  data-show-subtext="true" data-live-search="true" readonly  >
					{!! $organization_id !!}
				</select>
			</div>
			<div class="col-md-2 showline">
			</div>
		</div>


	</div>
		<div class="col-md-4">

		 <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Salesperson Name</label>
			<div class="col-md-6">
			<select name='salesperson_id' rows='5' class='form-control salesperson_id' data-show-subtext="true" data-live-search="true" >
				{!! $salesperson_id!!}
				</select>
			</div>
			<div class="col-md-1 showline">
				</div>
		</div>
             <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
			<div class="col-md-6">
				<input type="text" name="remarks" id="remarks" value="{{ $row->remarks }}" class="form-control remarks">
			</div>
			<div class="col-md-2">
			</div>
		</div>

		<div class="form-group row">
	<label for="inputIsValid" class="form-control-label col-md-4">Invoice Tax Total</label>
	<div class="col-md-6">
	<input type="text" name="invoice_tax_total" id="invoice_tax_total" value="{{ $row->invoice_tax_total }}" class="form-control invoice_tax_total">
	</div>
	<div class="col-md-2">
	</div>
	</div>


              <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Invoice Grand Total</label>
			<div class="col-md-6">
				<input type="text" name="invoice_grand_total" id="invoice_grand_total" value="{{ $row->invoice_grand_total }}" class="form-control invoice_grand_total">
			</div>
			<div class="col-md-2">
			</div>
		</div>

	</div>
</div>
<br>
<!-------------------------Linedata -------------------------------->
<div class="table-responsive subgrid_div">
<table class="table table-striped sales_invoice_table">
<thead>
<tr>
<th></th>
<th id="line">Line No</th>
<th id="prod">Product </th>
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
<th>Promised Date</th>
<th>Comments</th>
<th></th>
<th></th>
</tr>
</thead>
<tbody class="so_inv_lines_body">
<?php if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)
<tr class="clone clonedInput">
<td><input type="hidden" name="bulk_invoice_line_id[]" class="form-control input-sm bulk_invoice_line_id" value="{{ $value->invoice_line_id }}"></td>
<td><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly"></td>
<td id="blk"><select  name="bulk_product_id[]" class="form-control bulk_product_id  parsley-validated" style="width:300px" required="required">{!! $value->product_id !!}</select></td>
<td id="des">
<input type="text" name="bulk_description[]" class="form-control bulk_description" value="{{ $value->product_description }}" style="width:120px" required="required" >
</td>
<td>
<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id" style="width:90px">
{!! $value->uom_code_id !!}
</select>
</td>

<td>
<input type="text" name="bulk_salesorder_qty[]" class="form-control input-sm bulk_salesorder_qty input_qty_width" value="{{ $value->salesorder_qty }}" minlength="1" maxlength="4" required="required" style="width:90px" >
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
<input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount input_qty_width" value="{{ $value->discount_amount }}"style="width:90px" >
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
<input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total input_qty_width" value="{{ $value->line_total }}" required="required"  style="width:120px">
</td>
<td>
<input type="date" name="bulk_promised_date[]" class="form-control input-sm bulk_promised_date" value=""  style="width:120px">
</td>
<td>
<input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments input_qty_width" value="{{ $value->comments }}" >
</td>

<td>
<input type="hidden" name="counter[]">
</td>
</tr>
@endforeach
<?php } if(count($linedata) < 1 ) { ?>
<tr class="cloneRow clone clonedInput">
<td><input type="hidden" name="bulk_invoice_line_id[]" class="form-control input-sm bulk_invoice_line_id" value=""></td>
<td><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly"  style="width:70px"></td>
<td id="blk"><select name="bulk_product_id[]" class="form-control bulk_product_id  parsley-validated" required="required"  style="width:300px">{!! $product_id !!}</select></td>
<td id="des">
<input type="text" name="bulk_description[]" class="form-control bulk_description" value="" style="width:120px" required="required" >
</td>
<td>
<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id"  style="width:90px">
{!! $uom_code_id !!}
</select>
</td>
<td>
<input type="text" name="bulk_salesorder_qty[]" class="form-control bulk_salesorder_qty" value="" style="width:80px" required="required" >
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
<input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount " value=""  style="width:70px" >
</td>

<td id="tax1">
<select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="form-control bulk_tax_group_id"  style="width:80px">
{!! $tax_group_id !!}
</select>
</td>
<td id="taxamount">
<input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount" value="" required="required"  style="width:100px" >
</td>
<td>
<input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total" value="" required="required"  style="width:120px" >
</td>
<td>
<input type="date" name="bulk_promised_date[]" class="form-control input-sm bulk_promised_date" value=""  style="width:120px">
</td>
<td>
<input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value=""  style="width:200px" >
</td>

<td>
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
		<input type="hidden" name="submit_type" class="submit_type" value="" />
		<div class="form-group text-center actionbtn">
			<button name="approve" type="submit" class="btn save approve" value="APPROVED">Approve</button>
			<button name="reject" type="button" class="btn cancel approve" value="REJECTED">Reject</button>
                        <a class='btn cancel' onclick="location.href = '{{url('salesinvoiceapproval')}}'">Cancel</a>

		</div>
	</div>
</div>
</div>
</div>
	</form>
</div>
@extends('layouts.footer')
</div>
</body>
	<script>




$(document).ready(function(){

/*deepika purpose:to set readonly & Disable select option all form fields*/

$("#soinvoiceapproval text,select,input[name!='remarks']").attr('readonly','readonly');
	$('select').css('pointer-events', 'none');
/*end*/
	    $(document).on('click','.approve',function(e){
                e.preventDefault();
                var data;
	        	var approve=$(this).val();
                data = $("#soinvoiceapproval").serialize();
               var url="{{URL::to('salesinvoiceapprovalsave')}}?approve="+approve;
               var indexurl="{{URL::to('salesinvoiceapproval')}}";
                $.post(url, data, function(data1)
                {
                    var status=data1['status'];
                    var msg=data1['message'];
                   notyMessage(status,msg,indexurl);
                });


            });
		if(<?php echo $labour; ?> == 2)
		{
				$('#prod,#blk').hide();
				$('#desc,#des').show();
				$('.bulk_product_id').removeAttr('required');
		}
		else if(<?php echo $labour; ?> == 1){
			$('#prod,#blk').show();
			$('.bulk_product_id').attr('required');
			$('.bulk_description').removeAttr('required');
				$('#desc,#des').hide();

		}




var index = $('.clone').closest('tr').index();
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
});

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
	</script>
@endsection
