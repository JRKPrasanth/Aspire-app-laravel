@extends('layouts.header')
@section('content')
<h3 class="text-danger">Professional Tax Delimiter</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0 mb-4">

<div class="card-header bg-success text-white fw-bold">PT DELIMITER</div>
  <div class="card-body">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="form-group row align-items-center">
          <label for="year" class="col-sm-4 col-form-label fw-semibold">Year</label>
          <div class="col-sm-8">
            <select name="year" class="form-select select2 year" id="year">
              <!-- Options dynamically added -->
            </select>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group row align-items-center">
          <label for="month" class="col-sm-4 col-form-label fw-semibold">Month</label>
          <div class="col-sm-8">
            <select name="month" class="form-select select2 month" id="month">
              <!-- Options dynamically added -->
            </select>
          </div>
        </div>
      </div>
		
	      <div class="col-md-4">
        <div class="form-group row align-items-center">
          <label for="employee_type" class="col-sm-4 col-form-label fw-semibold">Employee Type</label>
          <div class="col-sm-8">
            <select name="employee_type" class="form-select select2 employee_type" id="employee_type">
              <!-- Options dynamically added -->
            </select>
          </div>
        </div>
      </div>
		
    </div>
  </div>
</div>



<div class="card shadow-lg rounded-4 border-0">
	<div class="card-body">
  <div class="table-responsive">	
                <div class="detail">
                    
                </div>
	  
            </div>

  </div>
</div>
@endsection
@push('scripts')

<script>
	
	
       var min = 2024,
    max = new Date().getFullYear(),
    select = document.getElementById('year');

    for (var i = max; i>=min; i--)
    {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
    }  	
		
		
// month		

	var url = "{{URL::to('jcomboformlogin?table=month:id:description') }}&order_by=id";

			$.ajax({
				url: url,
				type: 'GET',
	success: function (data) {
		// Parse JSON string if needed
		if (typeof data === "string") {
			try {
				data = JSON.parse(data);
			} catch (e) {
				console.error("Invalid JSON response:", data);
				return;
			}
		}

		$('.month').html('<option value="">-- Select Month --</option>');

		$.each(data, function (i, item) {
			let selected = item.val == "{{ $row->month ?? '' }}" ? 'selected' : '';
			$('.month').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
		});

		$('.month').trigger('change.select2'); 
	}		
		
	});	
		
	
var url = "{{ URL::to('jcomboformlogin?table=a_lookuplines_t:lookuplines_id:lookup_code') }}&order_by=lookup_code &parent=lookup_type='EMPLOYEE_TYPE'";

$.ajax({
    url: url,
    type: 'GET',
    success: function (data) {
        if (typeof data === "string") {
            try {
                data = JSON.parse(data);
            } catch (e) {
                console.error("Invalid JSON response:", data);
                return;
            }
        }

        $('#employee_type').html('<option value="">-- Select Type --</option>');

        $.each(data, function (i, item) {
            let selected = item.val == "{{ $row->employee_type ?? '' }}" ? 'selected' : '';
            $('#employee_type').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });

        $('#employee_type').trigger('change.select2'); // If using select2
    },
    error: function (xhr) {
        console.error("AJAX error:", xhr.responseText);
    }
});


	
	
$(document).ready(function()
{
  
        $("#year,#month,#employee_type").change(function()
    {
        var year = $('#year').val();
        var month = $('#month').val();
		var type = $('#employee_type').val();
		  
        if (month != '' && year != '' && type != '')
        {

            
            var url = "{{URL::to('delimeterreportpt')}}";
            $('.detail').html("");
            $.getJSON(url+'/'+month+'/'+year+'/'+type, function(data)
            {
               console.log(data.download_ling);

                 $('.detail').append(data.data);


            });
        }
    });


});
</script>

@endpush
