@extends('layouts.header')
@section('content')
<h3 class="text-danger">Create Vendor</h3>
	
 <form method="post" action="" id="vendor_form" class="vendor_form" data-parsley-validate enctype="multipart/form-data">
            {{ csrf_field() }}
 <input type="hidden" value="" name="savestatus" id="savestatus" />
 <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
			
<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-md-4">
                    <input type="hidden" id="vendor_id" name="vendor_id" class="form-control" value="{{ $row->vendor_id }}">

                    <div class="mb-3 row">
                        <label for="vendor_name" class="col-md-4 col-form-label"><span class="text-danger">*</span>Vendor Name</label>
                        <div class="col-md-8">
                            <input type="text" id="vendor_name" name="vendor_name" class="form-control vendor_name" value="{{ $row->vendor_name }}" required>
                            <span class="btn btn-danger dup_name d-none mt-2"></span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email_id" class="col-md-4 col-form-label"><span class="text-danger">*</span>Vendor Mail ID</label>
                        <div class="col-md-8">
                            <input type="email" id="email_id" name="email_id" class="form-control" value="{{ $row->email_id }}" required>
                            <span class="btn btn-danger email_vali d-none mt-2">Mail format is example123@gmail.com</span>
                        </div>
                    </div>
                </div>

                <!-- Middle Column -->
                <div class="col-md-4">
                    <div class="mb-3 row">
                        <label for="contact_no" class="col-md-4 col-form-label"><span class="text-danger">*</span>Contact No</label>
                        <div class="col-md-8">
                            <input type="text" id="contact_no" name="contact_no" class="form-control" value="{{ $row->contact_no }}" maxlength="15" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="address" class="col-md-4 col-form-label"><span class="text-danger">*</span>Address</label>
                        <div class="col-md-8">
                            <input type="text" id="address" name="address" class="form-control" value="{{ $row->address }}" maxlength="100" required>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="mb-3 row">
                        <label for="active" class="col-md-4 col-form-label">Active</label>
                        <div class="col-md-8">
                            <select name="active" id="active" class="form-select select2">
                                <option value="Yes" {{ $row->active == "Yes" ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $row->active == "No" ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="row mt-4">
                <div class="col text-center">
                    <button type="button" class="btn btn-success saveform" value="SAVE">Save</button>
                    <button type="button" class="btn btn-secondary" onclick='location.href="../newvendor"'>Cancel</button>
                </div>
            </div>
        </form>
   </div>






@endsection
@push('scripts')

<script>

	// Allow only numeric input for mobile number
	$(document).on('keypress', '#contact_no', function(ev){
		var regex = new RegExp("^[0-9]+$");
		var str = String.fromCharCode(ev.which);
		if (regex.test(str)) {
			return true;
		}
		ev.preventDefault();
		return false;
	});	
	
	
        var dup_chk = true;
        function duplicate_validate()
        {
            var vendor_name = $(".vendor_name").val();
            var vendor_id = $("#vendor_id").val();
			let result = true;
			
            $.ajax({
                cache: false,
                url: "{{URL::to('vendorcheckname')}}",
                type: 'GET',
                dataType: 'json',
                async : false,
                data: {vendor_name : vendor_name,vendor_id : vendor_id},
                success: function(response)
                {
				if (parseInt(response) === 1) {
                        $('.dup_name').html('Vendor Name:'+vendor_name+' Already Exists ');
                        $('.dup_name').show();
                        $(".vendor_name").val('');
                        dup_chk = false;
						 result = false;
					  } else {
							$('.dup_name').addClass('d-none');
						}
                              },
        error: function(xhr, status, error) {
            console.log("Duplicate check error:", error);
            result = false;
        }
    });

    return result;
        }

	 $(email_id).on('keyup', function () 
    {
		 $('.email_vali').hide();
	 });

    /**************** email validation start ***********/
    function ValidateEmail(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    };

     // Save Form

$(document).on('click', '.saveform', function () {
    const form = $("#vendor_form");
    let dup_chk = true; 

    form.parsley().validate(); 

    if (form.parsley().isValid() && dup_chk === true) {
        const formData = form.serialize(); 

		let isDuplicate = duplicate_validate();

        if (!isDuplicate) {
            showCustomAlert("Duplicate Vendor name found. Please enter a different name.", 'warning');
            return;
        }
		
        $.ajax({
            url: "{{ url('vendorsave') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    showCustomAlert(response.message || 'Saved successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ url('newvendor') }}";
                    }, 1500);
                } else {
                    showCustomAlert(response.message || 'Save failed. Please check your input.', 'error');
                }
            },
            error: function (xhr) {
                let errorMsg = 'Unexpected error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showCustomAlert(errorMsg, 'error');
            }
        });
    } else {
        showCustomAlert("Please fill out all required fields correctly.", 'warning');
    }
});	
	
</script>

@endpush
