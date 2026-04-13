@extends('layouts.header')
@section('content')

<h2 class="heads">Supplier Site <span class="ui_close_btn"><a href="../suppliersiteupload" class="collapse-close pull-right btn-danger" onclick="../suppliersiteupload"></a></span></h2> 


<form method="post" action="{{ url('suppliersiteuploadsave') }}" id="suppliersiteupload" data-parsley-validate>
{{ csrf_field() }}
<div class="card">

	
<div class="card-body card-block normalform">
	<div class="row">
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Supplier Name</label>
			<div class="col-md-6">
				<input class="form-control suppliersite_upload_id" id="suppliersite_upload_id" name="suppliersite_upload_id" size="16" type="hidden" value="{{ $suppliersiteuploaddata['suppliersite_upload_id'] }}" readonly>
				<input type="text" name="supplier_name" id="supplier_name" value="{{ $suppliersiteuploaddata['supplier_name'] }}" class="form-control supplier_name" >
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<!-- <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Supplier Site Number</label>
			<div class="col-md-6">
				<input type="text" name="supplier_site_number" id="supplier_site_number" value="{{ $suppliersiteuploaddata['supplier_site_number'] }}" class="form-control supplier_site_number" readonly>
			</div>
			<div class="col-md-2">
			</div>
		</div> -->	
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Supplier Site Name</label>
			<div class="col-md-6">
				<input type="text" name="supplier_site_name" id="supplier_site_name" value="{{ $suppliersiteuploaddata['supplier_site_name'] }}" class="form-control supplier_site_name" >
			</div>
			<div class="col-md-2">
			</div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Address</label>
			<div class="col-md-6">
				<input type="text" name="address" id="address" value="{{ $suppliersiteuploaddata['address'] }}" class="form-control address" >
			</div>
			<div class="col-md-2 showinline">
			</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">GST Number</label>
			<div class="col-md-6">
				<input type="text" name="gst_number" id="gst_number" value="{{ $suppliersiteuploaddata['gst_number'] }}" class="form-control gst_number" >
			</div>
			<div class="col-md-2 showline">
				
				</div>
		</div>
                <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">TAN Number</label>
			<div class="col-md-6">
				<input type="text" name="tan_number" id="tan_number" value="{{ $suppliersiteuploaddata['tan_number'] }}" class="form-control tan_number" >
			</div>
			<div class="col-md-2 showline">
				
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
				<input type="text" name="pincode" id="pincode" value="{{ $suppliersiteuploaddata['pincode'] }}" class="form-control pincode" >
			</div>
			<div class="col-md-2 showline">
				
				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Contact Name</label>
			<div class="col-md-6">
				<input type="text" name="contact_person" id="contact_person" value="{{ $suppliersiteuploaddata['contact_person'] }}" class="form-control contact_person" >
			</div>
			<div class="col-md-2 showline">
				
				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Contact Number</label>
			<div class="col-md-6">
				<input type="text" name="contact_number" id="contact_number" value="{{ $suppliersiteuploaddata['contact_number'] }}" class="form-control contact_number" >
			</div>
			<div class="col-md-2 showline">
				
				</div>
		</div>
            <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Mail Id</label>
			<div class="col-md-6">
				<input type="text" name="mail_id" id="mail_id" value="{{ $suppliersiteuploaddata['mail_id'] }}" class="form-control mail_id" >
			</div>
			<div class="col-md-2 showline">
				
				</div>
		</div>
		
		
	</div>
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Primary Address</label>
			<div class="col-md-6">
				<input type="text" name="primary_address" id="primary_address" value="{{ $suppliersiteuploaddata['primary_address'] }}" class="form-control primary_address" >
			</div>
			<div class="col-md-2 showline">
				
				</div>
		</div>
            <div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Active</label>
			<div class="col-md-6">
				<input type="text" name="active" id="active" value="{{ $suppliersiteuploaddata['active'] }}" class="form-control active" >
			</div>
			<div class="col-md-2 showline">
				
				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Name</label>
			<div class="col-md-6">
				<input type="text" name="batch_name" id="batch_name" value="{{ $suppliersiteuploaddata['batch_name'] }}" class="form-control batch_name" readonly>
			</div>
			<div class="col-md-2 showline">
				
				</div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Date</label>
			<div class="col-md-6">
				<input type="text" name="batch_date" id="batch_date" value="{{ $suppliersiteuploaddata['batch_date'] }}" class="form-control batch_date" readonly>
			</div>
			<div class="col-md-2 showline">
					
				</div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Status</label>
			<div class="col-md-6">
				<input type="text" name="batch_status" id="batch_status" value="{{ $suppliersiteuploaddata['batch_status'] }}" class="form-control batch_status" readonly>
			</div>
			<div class="col-md-2 showline">
					
				</div>
		</div>
		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Comments</label>
			<div class="col-md-6">
				<input type="text" name="batch_comments" id="batch_comments" value="{{ $suppliersiteuploaddata['batch_comments'] }}" class="form-control batch_comments" readonly>
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
			<button type="button" class="btn save">Save</button>
			<a href="../suppliersiteupload" class='btn cancel'  >Cancel</a>
			  
		</div>
	</div>
</div>	
	
</div>
	

</div>
	</form>
	

<script>
$(document).ready(function(){
	
	$('.supplier_site_name').on('keyup',function(){
		this.value= this.value.toUpperCase();
	});
	
	$(document).on('click','.save',function()
    {
    	var url		= "{{ URL::to('suppliersiteuploadsave') }}";
    	var create_url = "{{ URL::to('suppliersiteupload') }}";
    	var formdata	= $('#suppliersiteupload').serialize();
    	var form = $('#suppliersiteupload');
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
    
//    $(document).on('change', '.country', function ()
//     {
//		var country = $('.country').val();
//                alert(country);
//		$(".state").jCombo("{{ URL::to('jcomboformlogin?table=m_states_t:state_id:state_name') }}&parent=country_id="+country+ '&order_by=state_name asc',
//		{});
//	});
//        
//         $(document).on('change', '.state', function ()
//     {
//		var state = $('.state').val();
//		$(".city").jCombo("{{ URL::to('jcomboformlogin?table=m_cities_t:city_id:city_name') }}&parent=state_id="+state+ '&order_by=city_name asc',
//		{});
//	});


});

</script>
@endsection