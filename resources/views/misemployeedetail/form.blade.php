@extends('layouts.header')
@section('content')

<h3 class="text-danger">Create Marketing Employee Details</h3>
<?php error_reporting(0);
?>
<form method="POST" action="" id="employee_form" class="employee_form" data-parsley-validate>
    <input type="hidden" value="" name="savestatus" id="savestatus" />
    <input type="hidden" value="" name="linescheck" id="linescheck">
    {{ csrf_field() }}

   <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body card-block ">
                <div class="col-md-12">
                    <!------------------------------------- Body content start here ---------------------------->
                        <div class="row">
                          <div class="row g-3">

  <!-- Employee Name -->
  <div class="col-md-6">
    <div class="row mb-3 align-items-center">
      <label for="employee_name" class="col-md-4 col-form-label">
        <span class="text-danger">*</span> Employee Name
      </label>
      <div class="col-md-6">
        <select name="employee_name" id="employee_name" class="form-select select2" required>
          {!! $employee_name !!}
        </select>
      </div>
    </div>
  </div>

  <!-- Designation -->
  <div class="col-md-6">
    <div class="row mb-3 align-items-center">
      <label for="desigination" class="col-md-4 col-form-label">Designation</label>
      <div class="col-md-6">
        <input type="hidden" name="id" id="id" value="{{ $row->id }}">
        <select name="desigination" id="desigination" class="form-select select2" required>
          {!! $desigination !!}
        </select>
      </div>
    </div>
  </div>

  <!-- Active -->
  <div class="col-md-6">
    <div class="row mb-3 align-items-center">
      <label for="active" class="col-md-4 col-form-label">Active</label>
      <div class="col-md-6">
        <select name="active" id="active" class="form-select select2">
          <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
          <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Created By -->
  <div class="col-md-6 none">
    <div class="row mb-3 align-items-center">
      <label for="created_by" class="col-md-4 col-form-label">Created By</label>
      <div class="col-md-6">
        <select name="created_by" id="created_by" class="form-select select2" readonly style="pointer-events: none;">
          {!! $created_by !!}
        </select>
      </div>
    </div>
  </div>

</div>
 </div>

         <div class="row">
  <div class="col-12 linetable">
<div class="table-responsive">
  <table class="table table-bordered company_table">
    <thead class="table-light">
      <tr>
        <th style="width: 80px;">Line No</th>
        <th>Zone</th>
        <th>Region</th>
        <th>State</th>
        <th>Hq Name</th>
        <th style="width: 60px;"></th>
      </tr>
    </thead>
<tbody class="company_lines_body">
  @if(count($linedata) > 0)
    @foreach($linedata as $key => $value)
      <tr class="line-row">

   <td>
	     <input type="hidden" name="bulk_id[]" class="form-control input-sm bulk_id" value="{{ $value->id }}">
       <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
   </td>
   <td>
       <input type="text" name="bulk_zone[]" class="form-control input-sm bulk_zone" value="{{$value->zone}}" required>
   </td>
   <td>
       <input type="text" name="bulk_region[]" class="form-control input-sm bulk_region" value="{{$value->region}}" required>
   </td>
     <td>
       <input type="text" name="bulk_state[]" class="form-control input-sm bulk_state" value="{{$value->state}}" required>
   </td>
    <td>
       <input type="text" name="bulk_hq_name[]" class="form-control input-sm bulk_hq_name" value="{{$value->hq_name}}" required>
   </td>
        <td class="text-center">
          <button type="button" class="btn btn-sm btn-danger remove-row">
            <i class="fas fa-minus-circle"></i>
          </button>
        </td>
      </tr>
    @endforeach
  @else
    <tr class="line-row">

  <td>
	        <input type="hidden" name="bulk_id[]" class="form-control input-sm bulk_id" value="">
      <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly">
  </td>
  <td>
      <input type="text" name="bulk_zone[]" class="form-control input-sm bulk_zone" value="" required>
  </td>
  <td>
      <input type="text" name="bulk_region[]" class="form-control input-sm bulk_region" value="" required>
  </td>
    <td>
      <input type="text" name="bulk_state[]" class="form-control input-sm bulk_state" value="" required>
  </td>
   <td>
      <input type="text" name="bulk_hq_name[]" class="form-control input-sm bulk_hq_name" value="" required>
  </td>

      <td class="text-center">
        <button type="button" class="btn btn-sm btn-danger remove-row">
          <i class="fas fa-minus-circle"></i>
        </button>
      </td>
    </tr>
  @endif
</tbody>

  </table>

  <div class="text-end">
    <button type="button" class="btn btn-success btn-sm add-row">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>
</div>

  </div>
</div>
<!-- END -->

  <div class="text-center mt-4">
    <button type="button" class="btn btn-success saveform px-4 me-2">Save</button>
    <a href="{{ url('salesmisreportsdetil') }}" class="btn btn-secondary px-4">Cancel</a>
  </div>
</form>
</div>
</div>   

@endsection
@push('scripts')	
	
<script>
  
	$(document).on('click', '.add-row', function () {
    const $lastRow = $('.company_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false); // clone without events or data

    // Clear all input and select values in the cloned row
    $newRow.find('input').val('');
    $newRow.find('select').val('').trigger('change');

    // Remove any Select2 artifacts before reinitializing
    $newRow.find('select.select2').each(function () {
        if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id'); 
       $(this).next('.select2').remove(); // remove the select2 container
    });

    // Append the cleaned-up cloned row
    $('.company_lines_body').append($newRow);

    // Reinitialize select2
    $newRow.find('select.select2').select2({ width: '100%' });

		// Update line numbers
		updateLineNumbers();
	});



  // Remove button
  $(document).on('click', '.remove-row', function () {
    const rowCount = $('.company_lines_body tr').length;
    if (rowCount > 1) {
      $(this).closest('tr').remove();
      updateLineNumbers();
    } else {
      showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
  });

  // Renumber Line Nos
  function updateLineNumbers() {
    $('.company_lines_body tr').each(function (index) {
      $(this).find('.bulk_line_no').val(index + 1);
    });
  }
	
	
    // Save Form

  $(document).on('click', '.saveform', function () {
    const form = $("#employee_form");
    let dup_chk = true; // Declare duplicate check variable

    form.parsley().validate(); // Validate form using Parsley


    if (form.parsley().isValid() && dup_chk === true) {

        var $btn = $(this);
        $btn.prop('disabled', true);

        const formData = form.serialize(); // Serialize form data

        $.ajax({
            url: "{{ url('salesmisreportsdetilsave') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    showCustomAlert(response.message || 'Saved successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ url('salesmisreportsdetil') }}";
                    }, 1500);
                } else {
                    showCustomAlert(response.message || 'Save failed. Please check your input.', 'error');
                    $btn.prop('disabled', false);
                }
            },
            error: function (xhr) {
                let errorMsg = 'Unexpected error occurred.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showCustomAlert(errorMsg, 'error');
                $btn.prop('disabled', false);
            }
        });
    } else {
        showCustomAlert("Please fill out all required fields correctly.", 'warning');
    }
});
    // ---END---
</script>

@endpush