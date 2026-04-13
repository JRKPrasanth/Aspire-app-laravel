@extends('layouts.header')
@section('content')
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
<form method="post" action="" id="soquoteapproval" data-parsley-validate>
{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<strong>Sales Quote</strong>
		<span class="ui_close_btn"><a href="{{ URL::to('salesquoteapproval')}}" class="collapse-close pull-right btn-danger"></a></span>
</div>

<div class="card-body card-block">
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quote No</label>
			<div class="col-md-6">
			<input class="form-control quote_hdr_id" id="quote_hdr_id" name="quote_hdr_id" size="16" type="hidden" value="{{ $row->quote_hdr_id }}" readonly>
				<input type="text" id="quote_no" name="quote_no" class="form-control quote_no" value="{{ $row->quote_no }}" required>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quote Date</label>
			<div class="col-md-6">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control quote_date" id="quote_date" name="quote_date" size="16" type="text" value="{{ $row->quote_date }}" readonly>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="quote_date" value="{{ $row->quote_date }}" />
			</div>
			<div class="col-md-2 showline">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quote Type</label>
			<div class="col-md-6">
				<select type="text" name="quote_type" id="quote_type" class="form-control quote_type" >
					<option value="">--select--</option>
					<option <?php if($row->quote_type =="STANDARD") { echo "selected"; } else { echo ""; } ?> value="STANDARD">STANDARD</option>
					<option <?php if($row->quote_type =="LABOUR") { echo "selected"; } else { echo ""; } ?> value="LABOUR">LABOUR</option>
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quote Status</label>
			<div class="col-md-6">
				<select type="text" name="quote_status" id="quote_status" class="form-control quote_status" value="{{ $row->quote_status }}">
				<option value="">--select--</option>
				<option <?php if($row->quote_status =="INITIATED") { echo "selected"; } else { echo ""; } ?> value="INITIATED">INITIATED</option>
				<option <?php if($row->quote_status =="APPROVED") { echo "selected"; } else { echo ""; } ?> value="APPROVED">APPROVED</option>
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Customer</label>
			<div class="col-md-6">
				<select name='customer_id' rows='5' class='form-control customer_id' data-show-subtext="true" data-live-search="true"  required >
				{!! $customer_id !!}
				</select>
			</div>
			<div class="col-md-2 showinline">
			</div>
		</div>
	</div>



	<div class="col-md-4">
            <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quote Name</label>
			<div class="col-md-6">
				<input type="text" name="quote_name" id="quote_name" value="{{ $row->quote_name }}" class="form-control quote_name">
			</div>
			<div class="col-md-2">
			</div>
		</div>
             <div class="form-group row" style="display:none">
			<label for="inputIsValid" class="form-control-label col-md-4">Pricelist Name</label>
			<div class="col-md-6">
			<select name='quote_pricelist_id' rows='5' class='form-control quote_pricelist_id' data-show-subtext="true" data-live-search="true" >
				<option value="1">Symtec All Pricelist</option>
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
            <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
			<div class="col-md-6">
				<input type="text" name="remarks" id="remarks" value="{{ $row->remarks }}" class="form-control remarks">
			</div>
			<div class="col-md-2">
			</div>
		</div>

	</div>
		<div class="col-md-4">
           <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Currency Code</label>
			<div class="col-md-6">
			<select name='currency_id' rows='5' class='form-control currency_id' data-show-subtext="true" data-live-search="true" >
				<option >---Select---</option>
				</select>
			</div>
			<div class="col-md-2 showline">
				</div>
		</div>
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
			<label for="inputIsValid" class="form-control-label col-md-4">Quote Tax Total</label>
			<div class="col-md-6">
				<input type="text" name="quote_tax_total" id="quote_tax_total" value="{{ $row->quote_tax_total }}" class="form-control quote_tax_total">
			</div>
			<div class="col-md-2">
			</div>
		</div>
              <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Quote Grand Total</label>
			<div class="col-md-6">
				<input type="text" name="quote_grand_total" id="quote_grand_total" value="{{ $row->quote_grand_total }}" class="form-control quote_grand_total">
			</div>
			<div class="col-md-2">
			</div>
		</div>

	</div>
</div>


<br>
<!-------------------------Linedata -------------------------------->
<div class="table-responsive subgrid_div">
<table class="table table-striped so_quote_table table_scroll">
<thead>
<tr>
<th></th>
<th style="width: 130px;">Line No</th>
<th class="pdtdiv" style="width: 225px;">Product </th>
<th></th>
<th class="pdtdes_div">Product Description</th>
<th style="width: 116px;">Uom Code </th>
<th style="width: 102px;">Qty</th>
<th style="width: 71px;">Price</th>
<th style="width: 76px;">Discount(%)</th>
<th style="width: 79px;">Discount Amount</th>
<th style="width: 87px;">Tax Group</th>
<th style="width: 100px;">Tax Amount</th>
<th style="width: 100px;">Line Total</th>
<th style="width: 158px;">Promised Date</th>
<th style="width: 147px;">Comments</th>
<>
<th></th>
<th></th>
</tr>
</thead>
<tbody class="so_inq_lines_body">
<?php if(count($linedata)>=1) { ?>
@foreach($linedata as $key=>$value)
<tr class="clone clonedInput">
<td><input type="hidden" name="bulk_quote_line_id[]" class="form-control input-sm bulk_quote_line_id" value="{{ $value->quote_line_id }}" ></td>
<td><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly" style="width:50px"></td>
<td class="pdtdiv"><select name="bulk_product_id[]" class="form-control bulk_product_id  parsley-validated" required="required"  style="width:250px">{!! $value->product_id !!}</select></td><!--<td><i class="fa fa-search productsearch"></i></td>-->
<td class="pdtdes_div">
<input type="text" name="bulk_product_description[]" class="form-control input-sm bulk_product_description input_qty_width" value="{{ $value->product_description }}"    style="width:150px">
</td>
<td>
<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id"  style="width:95px">
{!! $value->uomcode_id !!}
</select>
</td>
<td>
<input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{ $value->qty }}" required="required"  style="width:75px">
</td>
<td>
<input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price input_qty_width" value="{{ $value->unit_price }}" required="required" style="width:115px" >
</td>
<td>
<input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage input_qty_width" value="{{ $value->discount_percentage }}" style="width:50px">
</td>
<td>
<input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount input_qty_width" value="{{ $value->discount_amount }}" style="width:80px">
</td>
<td>
<select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="form-control bulk_tax_group_id" style="width:80px">
{!! $value->tax_group_id !!}
</select>
</td>
<td>
<input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount input_qty_width" value="{{ $value->tax_amount }}" required="required" style="width:80px">
</td>
<td>
<input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total input_qty_width" value="{{ $value->line_total }}" required="required" style="width:100px" >
</td>
<td>
<input type="date" name="bulk_promised_date[]" class="form-control input-sm bulk_promised_date" value="{{ $value->promised_date }}"  style="width:120px">
</td>
<td>
<input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments input_qty_width" value="{{ $value->comments }}" style="width:150px">
</td>

<td>
<input type="hidden" name="counter[]">
</td>
</tr>
@endforeach
<?php } if(count($linedata) < 1 ) { ?>
<tr class="cloneRow clone clonedInput">
<td><input type="hidden" name="bulk_quote_line_id[]" class="form-control input-sm bulk_quote_line_id" value=""></td>
<td><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly"  style="width:50px"></td>
<td class="pdtdiv"><select name="bulk_product_id[]" class="form-control bulk_product_id  parsley-validated" required="required"  style="width:250px">{!! $product_id !!}</select></td>
<td><i class="fa fa-search productsearch"></i></td>
<td class="pdtdes_div">
<input type="text" name="bulk_product_description[]" class="form-control input-sm bulk_product_description input_qty_width" value=""   style="width:150px">
</td>
<td>
<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id"  style="width:95px">
{!! $uom_code_id !!}
</select>
</td>
<td>
<input type="text" name="bulk_qty[]" class="form-control bulk_qty " value="" required="required"  style="width:75px">
</td>
<td>
<input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price " value="" required="required"  style="width:115px" >
</td>
<td>
<input type="text" name="bulk_discount_percentage[]" class="form-control input-sm bulk_discount_percentage " value=""  style="width:50px">
</td>
<td>
<input type="text" name="bulk_discount_amount[]" class="form-control input-sm bulk_discount_amount " value=""  style="width:80px" >
</td>

<td>
<select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="form-control bulk_tax_group_id"  style="width:80px">
{!! $tax_group_id !!}
</select>
</td>
<td>
<input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount" value="" required="required"  style="width:80px" >
</td>
<td>
<input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total" value="" required="required"  style="width:100px" >
</td>
<td>
<input type="date" name="bulk_promised_date[]" class="form-control input-sm bulk_promised_date" value=""  style="width:120px">
</td>
<td>
<input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value=""  style="width:150px" >
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
		<input type="hidden" name="submit_type" class="submit_type" value="" />
		<div class="form-group text-center actionbtn">
			<button name="approve" type="submit" class="btn save approve" value="APPROVED">Approve</button>
			<button name="reject" type="button" class="btn cancel approve" value="REJECTED">Reject</button>
                        <a class='btn cancel' onclick="location.href = '{{url('salesquoteapproval')}}'">Cancel</a>

		</div>
	</div>
</div>
</div>
</div>
	</form>
	<div class="col-lg-1">
	</div>
</div>
@extends('layouts.footer')
</div>
</body>
	<script>

$(document).ready(function(){
$("#soquoteapproval input[name!='remarks'],#soquoteapproval select").attr('readonly','readonly');
$("#soquoteapproval input,#soquoteapproval select").prop('required',false);
$('select').css('pointer-events', 'none');
	    $(document).on('click','.approve',function(e){
             var approve= $(this).val();
                e.preventDefault();
                var data;
                data = $("#soquoteapproval").serialize();
			var indexurl="{{URL::to('salesquoteapproval')}}";
               var url="{{URL::to('soquoteapprovalsave')}}?approve="+approve;
                $.post(url, data, function(data1)
                {
					var status=data1.status;
					var msg=data1.message;
                   notyMessage(status,msg,indexurl);
                });
            });

var index = $('.clone').closest('tr').index();
changeClassName('bulk_quote_line_id');
changeClassName('bulk_line_no');
changeClassName('bulk_product_id');
changeClassName('bulk_uom_code_id');
changeClassName('bulk_qty');
changeClassName('bulk_unit_price');
changeClassName('bulk_discount_percentage');
changeClassName('bulk_discount_amount');
changeClassName('bulk_line_subtotal');
changeClassName('bulk_tax_group_id');
changeClassName('bulk_tax_amount');
changeClassName('bulk_line_total');
changeClassName('bulk_promised_date');
changeClassName('bulk_comments');



<?php if($row->quote_type =="STANDARD") { ?>
//$('.pdtdiv').removeClass('hide');
$('.pdtdes_div').addClass('hide');
<?php } else { ?>
$('.pdtdiv').addClass('hide');
$('.bulk_product_id').removeAttr('required');
//$('.pdtdes_div').removeClass('hide');
<?php } ?>

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
	var rowCount = $('.so_quote_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.so_quote_table tbody tr').find('.'+className).removeClass(className+i);
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
		});
	</script>
@include('layouts.php_js_validation')
@endsection
