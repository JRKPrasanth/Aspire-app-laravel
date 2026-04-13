@extends('layouts.header')
@section('content')

<style type="text/css">
	.select2-container{
		height: 30px;
	}
</style>


		<h2 class="heads">Open Stock Upload
		<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{URL::to('purchasepricelist')}}"'></a></span>
	</h2>
<span class="ui_close_btn"></span>

<form method="post" action="{{ URL::to('openstocksave') }}" id="openstock" data-parsley-validate>
{{ csrf_field() }}

<div class="card">

	
<div class="card-body card-block normalform">
	<div class="col-md-6">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Item Name</label>
			<div class="col-md-7">
			<input class="form-control product_openstock_upload_id" id="product_openstock_upload_id" name="product_openstock_upload_id" size="16" type="hidden" value="{{ $openstockdata->product_openstock_upload_id }}" readonly>
				<select name='item_name' rows='5' class='select2 item_name' id="item_name" >
					{!! $item_name !!}
				</select>	
			</div>
			
		</div>	
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Subinventory Name</label>
			<div class="col-md-7">
				<select name='subinventory_name' class='form-control select2 subinventory_name' id="subinventory_name" >
					{!! $subinventory_name !!}
				</select>				
			</div>
			<div class="col-md-2">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Locator Code</label>
			<div class="col-md-7">
				<select name='locator_code' rows='5' class='form-control select2 locator_code' style="height: 30px;" >
					{!! $locator_code !!}
				</select>				
			</div>
			<div class="col-md-2 showinline">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Qty</label>
			<div class="col-md-7">
				<input type="text" name="qty" id="qty" value="{{ $openstockdata->qty }}" class="form-control qty">
			</div>
			<div class="col-md-2">
			</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Batch Number</label>
			<div class="col-md-7">
				<input type="text" name="batch_number" id="batch_number" value="{{ $openstockdata->batch_number }}" class="form-control batch_number">
			</div>
			<div class="col-md-2">
			</div>
		</div>

		</div>
			<div class="col-md-6">

				<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Cost</label>
			<div class="col-md-7">
				<input type="text" name="cost" id="cost" value="{{ $openstockdata->cost }}" class="form-control cost">
			</div>
			<div class="col-md-2 showline">
				
				</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Batch Name</label>
			<div class="col-md-7">
				<input type="text" name="batch_name" id="batch_name" value="{{ $openstockdata->batch_name }}" class="form-control batch_name" readonly>
			</div>
			<div class="col-md-2 showline">
				
				</div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Batch Date</label>
			<div class="col-md-7">
				<input type="text" name="batch_date" id="batch_date" value="{{ $openstockdata->batch_date }}" class="form-control batch_date" readonly>
			</div>
			<div class="col-md-2 showline">
					
				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Batch Status</label>
			<div class="col-md-7">
				<input type="text" name="batch_status" id="batch_status" value="{{ $openstockdata->batch_status }}" class="form-control batch_status" readonly>
			</div>
			<div class="col-md-2 showline">
					
				</div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Batch Comments</label>
			<div class="col-md-7">
				<textarea name="batch_comments" id="batch_comments" class="form-control batch_comments" readonly>{{$openstockdata->batch_comments}}</textarea>
			</div>
			<div class="col-md-2 showline">
					
				</div>
		</div>

	
</div>	
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			<button type="submit" class="btn save">Submit</button>
			<a href="../openstockupload" class='btn cancel'  >Cancel</a>
			  
		</div>
	</div>
</div>	
	
</div>
	

</div>
	</form>
	
<script>
$(document).ready(function(){
	
	$('.group_name').on('keyup',function(){
		this.value= this.value.toUpperCase();
	});
	
	$('.product_openstock_upload_id').attr('readonly',true);		

	
	$('.locator_code').css('pointer-events','none');
	$(document).on('change','.subinventory_name',function()
	{
		$('.locator_code').css('pointer-events','auto');
		var subinventoryid=$(this).val();
		console.log(subinventoryid);
		
		$(".locator_code").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:locator_code:locator_code') }}&parent=subinventory_id="+subinventoryid+"&order_by=locator_code asc",
			{selected_value:""});	
	});


});

</script>
@endsection