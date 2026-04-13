@extends('layouts.header')
@section('content')
<span class="ui_close_btn"></span>
<div class="row">
	<div class="col-lg-1"></div>
<form method="post" action="{{ url('customersiteuploadsave') }}" id="customersiteupload" data-parsley-validate>
{{ csrf_field() }}
<div class="col-lg-12">
<div class="card">
<div class="card-header">
 <h4 class="heads">Customersite Upload
<span class="ui_close_btn"><a href="../customersiteupload" class="collapse-close pull-right btn-danger" onclick="../customersiteupload"></a></span></h4>
</div>

<div class="card-body card-block normalform">
	<div class="col-md-12">
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Customer Name</label>
			<div class="col-md-6">
				<input class="form-control customersite_upload_id" id="customersite_upload_id" name="customersite_upload_id" size="16" type="hidden" value="{{ $customersiteuploaddata['customersite_upload_id'] }}" >
				<input type="text" name="customer_name" id="customer_name" value="{{ $customersiteuploaddata['customer_name'] }}" class="form-control customer_name" >
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<!-- <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Customer Site Number</label>
			<div class="col-md-6">
				<input type="text" name="customer_site_number" id="customer_site_number" value="{{ $customersiteuploaddata['customer_site_number'] }}" class="form-control customer_site_number" readonly>
			</div>
			<div class="col-md-2">
			</div>
		</div> -->
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Customer Site Type</label>
			<div class="col-md-6">
				<input type="text" name="site_type" id="site_type" value="{{ $customersiteuploaddata['site_type'] }}" class="form-control site_type" >
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Customer Site Name</label>
			<div class="col-md-6">
				<input type="text" name="customer_site_name" id="customer_site_name" value="{{ $customersiteuploaddata['customer_site_name'] }}" class="form-control customer_site_name" >
			</div>
			<div class="col-md-2">
			</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Address</label>
			<div class="col-md-6">
				<input type="text" name="address" id="address" value="{{ $customersiteuploaddata['address'] }}" class="form-control address" >
			</div>
			<div class="col-md-2 showinline">
			</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Tan No</label>
			<div class="col-md-6">
				<input type="text" name="tan_no" id="tan_no" value="{{ $customersiteuploaddata['tan_no'] }}" class="form-control tan_no" >
			</div>
			<div class="col-md-2 showinline">
			</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Country</label>
			<div class="col-md-6">
				<select name='country' rows='5' class='select2 country' data-show-subtext="true" data-live-search="true"  readonly >
				{!! $country !!}
				</select>
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Primary Address</label>
			<div class="col-md-6">
				<select name='primary_address' rows='5' class='select2 primary_address' id="primary_address"  tabindex="3" >
			<option  <?php if($customersiteuploaddata['primary_address']=='YES'){ echo "selected"; }else{ } ?> value="YES" >YES</option>
			<option <?php if($customersiteuploaddata['primary_address']=='NO'){ echo "selected"; }else{ } ?>  value="NO" >NO</option>
				</select>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">State</label>
			<div class="col-md-6">
				<select name='state' rows='5' class='form-control select2 state' data-show-subtext="true" data-live-search="true"  >
				{!! $state !!}
				</select>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">City</label>
			<div class="col-md-6">
				<select name='city' rows='5' class='form-control select2 city' data-show-subtext="true" data-live-search="true"  required>
				{!! $city !!}
				</select>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Pincode</label>
			<div class="col-md-6">
				<input type="text" name="pincode" id="pincode" value="{{ $customersiteuploaddata['pincode'] }}" class="form-control pincode" >
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Contact Name</label>
			<div class="col-md-6">
				<input type="text" name="contact_person" id="contact_person" value="{{ $customersiteuploaddata['contact_person'] }}" class="form-control contact_person" >
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Contact Number</label>
			<div class="col-md-6">
				<input type="text" name="contact_number" id="contact_number" value="{{ $customersiteuploaddata['contact_number'] }}" class="form-control contact_number" >
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Contact Email</label>
			<div class="col-md-6">
				<input type="text" name="contact_email" id="contact_email" value="{{ $customersiteuploaddata['contact_email'] }}" class="form-control contact_email" >
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>

	</div>
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">GST NO</label>
			<div class="col-md-6">
				<input type="text" name="gst_no" id="gst_no" value="{{ $customersiteuploaddata['gst_no'] }}" class="form-control gst_no" readonly>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Name</label>
			<div class="col-md-6">
				<input type="text" name="batch_name" id="batch_name" value="{{ $customersiteuploaddata['batch_name'] }}" class="form-control batch_name" readonly>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Date</label>
			<div class="col-md-6">
				<input type="text" name="batch_date" id="batch_date" value="{{ $customersiteuploaddata['batch_date'] }}" class="form-control batch_date" readonly>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Active</label>
			<div class="col-md-6">
				<select name='active' rows='5' class='select2 active' id="active"  tabindex="3" >
			<option  <?php if($customersiteuploaddata['active']=='Yes'){ echo "selected"; }else{ } ?> value="Yes" >Yes</option>
			<option <?php if($customersiteuploaddata['active']=='No'){ echo "selected"; }else{ } ?>  value="No" >No</option>
				</select>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Status</label>
			<div class="col-md-6">
				<input type="text" name="batch_status" id="batch_status" value="{{ $customersiteuploaddata['batch_status'] }}" class="form-control batch_status" readonly>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Comments</label>
			<div class="col-md-6">
				<textarea  name="batch_comments" id="batch_comments" class="form-control batch_comments" readonly >{{ $customersiteuploaddata['batch_comments'] }}</textarea>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>

		
	</div>

</div>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			<!--  <a class='btn applychanges'>Apply Changes</a>-->
			<button type="button" class="btn save">SAVE</button>
			<a href="../customersiteupload" class='btn cancel'  >Cancel</a>

		</div>
	</div>
</div>

</div>


</div>
</div>
	</form>
	<div class="col-lg-1">
	</div>
</div>
<script>
$(document).ready(function(){

	$('.supplier_site_name').on('keyup',function(){
		this.value= this.value.toUpperCase();
	});
// save data
	$(document).on('click','.save',function()
    {
    	var url		= "{{ URL::to('customersiteuploadsave') }}";
    	var create_url = "{{ URL::to('customersiteupload') }}";
    	var formdata	= $('#customersiteupload').serialize();
    	var form = $('#customersiteupload');
    	$.post(url,formdata,function(data)
        {

        	var status      = data.status;
			var msg			=data.message;
            notyMsg(status,msg);
            setTimeout(function(){
           		window.location.href=create_url;
            }, 1500);
        });
    });

//End save data
});

</script>
@endsection
