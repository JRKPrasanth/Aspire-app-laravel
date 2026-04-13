@extends('layouts.header')
@section('content')

<body>
<span class="ui_close_btn"></span>
<div class="container">

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

<style>
.so_row
{
height: 830px;
}
</style>
<div class="row so_row">
<div class="col-lg-1"></div>
<form method="post" action="" class="socancellation" id="socancellation" data-parsley-validate>
<div class="col-lg-12">
<div class="card">
<div class="card-header">
<strong>Sales Order  Cancel</strong>
	<span class="ui_close_btn"><a href="{{ URL::to('socancellation')}}" class="collapse-close pull-right btn-danger"></a></span>
</div>
<div class="card-body card-block">

{{ csrf_field()}}

<div class="col-lg-6 col-md-6">
<div class="form-group row">
<label class="form-control-label col-md-5" for="sales_hdr_id">Order No</label>
<div class="col-md-5">
<input type="hidden" name="sales_hdr_id" id="sales_hdr_id" class="form-control sales_hdr_id" value="{{ $row['sales_hdr_id'] }}">
<input type="text" name="sales_order_no" id="sales_order_no" class="form-control sales_order_no col-md-7" value="{{ $row['sales_order_no'] }}" data-parsley-type="alphanum" data-parsley-trigger="keyup" required >
</div>
</div>
<div class="form-group row">
<label class="form-control-label col-md-5" for="sales_order_date">Order Date</label>
<div class="col-md-5">
<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
<input class="form-control sales_order_date" id="sales_order_date" name="sales_order_date" size="16" type="text" value="{{ $row['sales_order_date'] }}" readonly>
<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
</div>
</div>
</div>
<div class="form-group row">
<label for="Order Type" class="form-control-label col-md-5">Order Type</label>
<div class="col-md-5">
<input type="text" name="order_type_id"  id="order_type_id" class="form-control order_type_id" value="{{ $row['order_type_id'] }}">
</div>
</div>


<div class="form-group row">
<label class="form-control-label col-md-5" for="so_ref_no">So Reference Number</label>
<div class="col-md-5">
<input type="text" name="so_ref_no" id="so_ref_no" class="form-control so_ref_no" value="{{ $row['so_ref_no'] }}">
</div>
</div>


<div class="form-group row">
<label class="form-control-label col-md-5" for="order_status_id">Order Status</label>
<div class="col-md-5">
<select name='order_status_id' rows='5' id="order_status_id" class="form-control">
<option value="DRAFT">DRAFT</option>
<option value="INITIATED">INITIATED</option>
<option value="APPROVED">APPROVED</option>
<option value="REJECTED">REJECTED</option>
<option value="CANCELLED">CANCELLED</option>
</select>
</div>
</div>


</div>

<div class="col-lg-6 col-md-6">

<div class="form-group row">
<label class="form-control-label col-md-5" for="customer_id">Customer</label>
<div class="col-md-5">
<select name='customer_id'  class='form-control customer_id' id="customer_id" >
{!! config('global.CONT')->jCombo('m_customers_t','customer_id','customer_name',$row['customer_id'])!!}
</select>
</div>
<div class="col-md-2 showinline">
</div>
</div>

<div class="form-group row">
<label class="form-control-label col-md-5" for="organization_id">Organization</label>
<div class="col-md-5">
<select name='organization_id' rows='5' class='form-control organization_id' id="organization_id">
{!! config('global.CONT')->jCombo('m_organizations_t','organization_id','organization_name',$row['organization_id'])!!}
</select>
</div>
</div>

<div class="form-group row">
<label class="form-control-label col-md-5" for="remarks">Remarks</label>
<div class="col-md-5">
<input type="text" name="remarks" id="remarks" class="form-control remarks" value="{{ $row['remarks'] }}">
</div>
</div>

<div class="form-group row">
<label class=" form-control-label col-md-5" for="customer_po_number">Customer Po Number</label>

<div class="col-md-5">
<input type="text" name="customer_po_number" id="customer_po_number" class="form-control customer_po_number" value="{{ $row['customer_po_number'] }}">
</div>


</div>

</div>


<!-------------------------Linedata -------------------------------->

<div class="table-responsive subgrid_div">
<table class="table table-striped so_order_table">
<thead>
<tr>
@foreach ($subgrid['label_data'] as $col_key=>$col_name)

@if($col_key!='sales_hdr_id'&&$col_key!='line_sub_total'&&$col_key!='tax_amount' &&$col_key!='updated_at')

<th>@if($col_key!='sales_line_id' && $col_key!='sales_hdr_id') {{ $col_name }} @endif</th>
@endif

@endforeach
<th>Picked Qty</th>

<th></th>


</tr>
</thead>

<tbody class="so_order_table_body">

@foreach ($subgrid['rowData'] as $row_key=>$row)

<tr class="clone clonedInput">

<td><input type="hidden" name="bulk_sales_line_id[]" class="form-control input-sm bulk_sales_line_id" value="{{ $row['sales_line_id'] }}"></td>

<td><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $row['line_no'] }}" readonly="readonly"></td>

<td>
<select name="bulk_product_id[]" class="form-control bulk_product_id" required="required">
{!! config('global.CONT')->jCombo('m_products_t','product_id','concatenated_product',$row['product_id']); !!}
</select>

</td>
<td>
<select name="bulk_uom_code_id[]" id="bulk_uom_code_id" class="form-control bulk_uom_code_id" style="width: 75px;" readonly>
{!! config('global.CONT')->jCombo('m_uom_codes_t','uom_code_id','uom_code',$row['uom_code_id']); !!}
</select>
</td>

<td><input type="text" name="bulk_qty[]" class="form-control input-sm bulk_qty input_qty_width" value="{{ $row['qty'] }}" minlength="1" maxlength="4"></td>


<td><input type="text" name="bulk_unit_price[]" class="form-control input-sm bulk_unit_price" value="{{ $row['unit_price'] }}" minlength="1" maxlength="4"></td>

<!--<td><input type="text" name="bulk_line_sub_total[]" class="form-control input-sm bulk_line_sub_total" value="{{ $row['line_sub_total'] }}"></td>
-->
<td><input type="text" name="bulk_line_total[]" class="form-control input-sm bulk_line_total" value="{{ $row['line_total'] }}"></td>

<td>
<select name="bulk_tax_group_id[]" id="bulk_tax_group_id" class="form-control bulk_tax_group_id" style="width: 70px;">
{!! config('global.CONT')->jCombo('m_tax_group_t','tax_group_id','tax_group_name',$row['tax_group_id']); !!}
</select>
</td>

<!--<td><input type="text" name="bulk_tax_amount[]" class="form-control input-sm bulk_tax_amount" value="{{ $row['tax_amount'] }}"></td>	-->

<td><input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="{{ $row['comments'] }}"></td>

<td>
<input type="text" name="picked_qty" class="form-control input-sm picked_qty" value="{{ $pickqty[$row_key] }}">
</td>

<td>
<input type="hidden" name="counter[]">
</td>

</tr>

@endforeach

</tbody>


</table>

<input type="hidden" name="enable-masterdetail" value="true">
</div>
<!-------------------------Linedata End-------------------------------->

<br>
<div class="row">
  <div class="col-lg-12 col-md-12">
   <div class="form-group text-center">
     <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
     <button type="button"  name="cancelled" class='btn save cancelled' value="CANCELLED">CANCEL ORDER</button>
     <a class='btn cancel' onclick="location.href='{{ URL::To('salesorderapproval') }}'">Cancel</a>
   </div>
 </div>
</div>
</div>
</div>
</div>
@extends('layouts.footer')
	</form>
</div>

<script>
$(document).ready(function()
{
	/*deepika purpose:to set readonly & Disable select option all form fields*/

$("#socancellation text,select,input[name!='remarks']").attr('readonly','readonly');
	$('select').css('pointer-events', 'none');
/*end*/
	 $(document).on('click','.cancelled',function(e){
//ar picqty=$(".picked_qty"+index).val();

		 var cancelled= $(this).val();
                e.preventDefault();
                var data;
                data = $("#socancellation").serialize();
			var indexurl="{{URL::to('socancellation')}}";
              //alert(indexurl);
               var url="{{URL::to('socancellationcancel')}}?cancelled="+cancelled;
                $.post(url, data, function(data1)
                {
				  	       var status=data1.status;
        	 				var msg=data1.message;
                   notyMessage(status,msg,indexurl);
                });
            });
/*deepika purpose:to set index for lines*/

changeClassfields();

});



function changeClassfields()
{

changeClassName('bulk_sales_line_id');
changeClassName('bulk_line_no');
changeClassName('bulk_product_id');
changeClassName('bulk_uom_code_id');
changeClassName('bulk_qty');
changeClassName('bulk_unit_price');
changeClassName('bulk_line_sub_total');
changeClassName('bulk_tax_group_id');
changeClassName('bulk_tax_amount');
changeClassName('bulk_line_total');
changeClassName('bulk_comments');
}

function changeClassName(className)
{

$('.' + className).each(function (index,el)
{
var cls=$(this).attr('class');
var regex = new RegExp("("+className+")(([0-9]*\.)?[0-9]+).*","g");
var cls_new=cls.replace(regex,'');
cls_new=cls_new+" "+className+index;
cls_new= cls_new.replace(/[\s,]+/g,' ').trim();
//console.log(cls_new);

$(this).attr('class',cls_new);
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly","readonly");
}

});
}
</script>

@include('layouts.php_js_validation')
@endsection
