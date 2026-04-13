@extends('layouts.header')
@section('content')
<style>
	/*
[class*="col-"] {
    margin-bottom: 10px;
}*/
</style>
<body>
<span class="ui_close_btn"></span>
<div class="container" style="height:1000px;">
<div class="row">
	<div class="col-lg-1">
	</div>
<div class="col-lg-10">
<div class="card">
<div class="card-header">
<strong>Sales Enquiry</strong> 
</div>
	
<div class="card-body card-block">
	<div class="col-md-6">
		<div class="has-success form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Inquiry No</label>
			<div class="col-md-7">
			<input class="form-control so_inquiry_hdr_id" id="so_inquiry_hdr_id" name="so_inquiry_hdr_id" size="16" type="hidden" value="{{ $row->so_inquiry_hdr_id }}" readonly>
				<input type="text" id="inquiry_no" name="inquiry_no" class="form-control inquiry_no" value="{{ $row->inquiry_no }}">
			</div>
		</div>
		<div class="has-success form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Inquiry Type</label>
			<div class="col-md-7">
				<input type="text" name="inquiry_type" id="inquiry_type" class="form-control inquiry_type" value="{{ $row->inquiry_type }}">
			</div>
		</div>
		
		<div class="has-success form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Customer</label>
			<div class="col-md-7">
				<select name='customer_id' rows='5' class='selectpicker customer_id' data-show-subtext="true" data-live-search="true"  >
					<option value="0">--Select--</option>
				{!! $customer_id !!}
				</select>
			</div>
		</div>
		
	</div>	
	
	
	
	<div class="col-md-6">
		<div class="has-success form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Project Name</label>
			<div class="col-md-7">
			<select name='project_id' rows='5' class='selectpicker project_id' data-show-subtext="true" data-live-search="true" >
				{!! $project_id !!}
				</select>
			</div>
		</div>
		
		<div class="has-success form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Inquiry Date</label>
			<div class="col-md-7">
			<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control inquiry_date" id="inquiry_date" name="inquiry_date" size="16" type="text" value="{{ $row->inquiry_date }}" readonly>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			</div>
			<input type="hidden" id="inquiry_date" value="{{ $row->inquiry_date }}" />
			</div>
		</div>
		
		<div class="has-success form-group row">
			<label for="inputIsValid" class="form-control-label col-md-5">Organization</label>
			<div class="col-md-7">
				<select name='organization_id' rows='5' class='selectpicker organization_id'  data-show-subtext="true" data-live-search="true"  >
					<option value="1">Option 1</option>
					<option value="2">Option</option>
				</select>
			</div>
		</div>
		
	</div>	
	
</div>	
	
	
	
</div>
</div>
	<div class="col-lg-1">
	</div>
</div>
@extends('layouts.footer')
</div>
</body>
	<script>
	$(document).ready(function(){
	$('.group_name').on('keyup',function(){
	this.value= this.value.toUpperCase();
	});
		
		$('.form_date').datetimepicker({
	format: 'yyyy-mm-dd',
weekStart: 1,
todayBtn:  1,
autoclose: 1,
todayHighlight: 1,
startView: 2,
minView: 2,
forceParse: 0
});

	});
	</script>

@endsection
