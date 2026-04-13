@extends('layouts.header')
@section('content')
<h2 class="text-danger">Time Formats</h2>
@include('layouts.breadcrumb')
<?php error_reporting(0);?>

<form method="post" action="{{ url('timeformatssave') }}" id="Timeform" data-parsley-validate enctype="multipart/form-data">
  {{ csrf_field() }}

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">

      <input type="hidden" class="form-control" id="time_formats_id" name="time_formats_id" value="{{ $timefmdata['time_formats_id'] }}" readonly>

      <div class="row mb-3">
        <div class="col-md-4">
          <label for="php_format" class="form-label">
            <span class="text-danger">*</span> PHP Format
          </label>
          <input type="text" id="php_format" name="php_format" required class="form-control" value="{{ $timefmdata['php_format'] }}">
        </div>

        <div class="col-md-4">
          <label for="js_format" class="form-label">
            <span class="text-danger">*</span> JavaScript Format
          </label>
          <input type="text" id="js_format" name="js_format" required class="form-control" value="{{ $timefmdata['js_format'] }}">
        </div>

        <div class="col-md-4">
          <label for="display_format" class="form-label">
            <span class="text-danger">*</span> Display Format
          </label>
          <input type="text" id="display_format" name="display_format" required class="form-control" value="{{ $timefmdata['display_format'] }}">
        </div>
      </div>

      <div class="text-center mt-4">
        <button type="button" class="btn btn-success saveform px-4 me-2">Submit</button>
        <a href="{{ url('timeformats') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
      </div>

    </div>
  </div>
</form>


@endsection
@push('scripts')

	<script>
		
	  var dup_chk = '';
        function duplicate_validate()
        {
            var php_format = $(".php_format").val();
            var js_format = $(".js_format").val();
            var display_format = $(".display_format").val();
            var edit_id =  $(".time_formats_id").val();
            $.ajax({
                cache: false,
                url: "{{URL :: to('timeformatcheck') }}", //this is your uri
                type: 'GET',
                dataType: 'json',
                async : false,
                data: {php_format : php_format,js_format : js_format,display_format : display_format,edit_id : edit_id},
                success: function(response)
                {
                    console.log(response);
                    if(response == 1)
                    {
                        notyMsg('info','Already this Combination Exists for this Group');
                  
                        $(".php_format").val('');
                         $(".js_format").val('');
                         $(".display_format").val('');
                        dup_chk = false;

                    }
                    else if(response == 0)
                    {
                        var html ="";
                           
                        dup_chk = true;

                    }

                },
                error: function(xhr, resp, text)
                {
                    console.log(xhr, resp, text);
                }
            });
        }


		// save function
$(document).on('click', '.saveform', function () {
    const form = $("#Timeform");
    let dup_chk = true; // Declare duplicate check variable

    form.parsley().validate(); // Validate form using Parsley

    if (form.parsley().isValid() && dup_chk === true) {
        const formData = form.serialize(); 

        $.ajax({
            url: "{{ url('timeformatsave') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    showCustomAlert(response.message || 'Saved successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ url('timeformats') }}";
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
