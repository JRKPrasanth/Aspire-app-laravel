@extends('layouts.header')
@section('content')
<h3 class="text-danger">EP Amount Details</h3>
@include('layouts.breadcrumb')


<form method="POST" action="" id="employee_form" class="employee_form" data-parsley-validate>
    @csrf
    <input type="hidden" name="savestatus" id="savestatus" />
    <input type="hidden" name="linescheck" id="linescheck" />

    <div class="card shadow-lg rounded-4 border-0">
		<div class="card-header bg-success text-white fw-semibold"></div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end text-nowrap">
                            <span class="text-danger">*</span> Department
                        </label>
                        <div class="col-md-6">
                            <select name="department" id="department" class="form-select select2" required>
                                {!! $department !!}
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end text-nowrap">Designation</label>
                        <div class="col-md-6">
                            <input type="hidden" name="id" id="id" value="{{ $row->id }}">
                            <select name="desigination" id="desigination" class="form-select select2" required>
                                {!! $desigination !!}
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end text-nowrap">OT Type</label>
                        <div class="col-md-6">
                            <select name="ottype" class="form-select select2" required>
                                <option value="">-- please select --</option>
                                <option value="Morning" {{ $row->ot_type == 'Morning' ? 'selected' : '' }}>Morning</option>
                                <option value="Evening" {{ $row->ot_type == 'Evening' ? 'selected' : '' }}>Evening</option>
                                <option value="Night" {{ $row->ot_type == 'Night' ? 'selected' : '' }}>Night</option>
                                <option value="Sunday" {{ $row->ot_type == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" style="pointer-events:none;">
                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end text-nowrap">Created By</label>
                        <div class="col-md-6">
                            <select name="created_by" id="created_by" class="form-select select2" readonly>
                                {!! $created_by !!}
                            </select>
                        </div>
                    </div>
                </div>
            </div> 
			
			
		<div class="row">
  <div class="col-12 linetable">
<div class="table-responsive">
  <table class="table table-bordered clone_table">
    <thead class="table-light">
      <tr>
        <th style="width: 80px;">Line No</th>
        <th>EP Hrs</th>
        <th>EP Amount</th>
        <th>Food Amount </th>
        <th style="width: 60px;"></th>
      </tr>
    </thead>
<tbody class="clone_lines_body">
  @if(count($linedata) > 0)
    @foreach($linedata as $key => $value)
      <tr class="line-row">
                                            <td>
                                                <input type="hidden" name="bulk_id[]" class="form-control input-sm bulk_id" value="{{ $value->id }}">
                                            
                                                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
                                            </td>
                                              <td>
                                              <select name="bulk_ot_hrs[]" class="form-control bulk_ot_hrs select2" data-show-subtext="true" data-live-search="true" required>
                                               <option value="">--Please Select--</option>
                                            
                                               <option value="01:00" {{ $value->ot_hrs == '01:00' ? 'selected' : '' }}>01:00</option>
                                               <option value="02:00" {{ $value->ot_hrs == '02:00' ? 'selected' : '' }}>02:00</option>
                                               <option value="03:00" {{ $value->ot_hrs == '03:00' ? 'selected' : '' }}>03:00</option>
                                               <option value="04:00" {{ $value->ot_hrs == '04:00' ? 'selected' : '' }}>04:00</option>
                                               <option value="05:00" {{ $value->ot_hrs == '05:00' ? 'selected' : '' }}>05:00</option>
                                               <option value="06:00" {{ $value->ot_hrs == '06:00' ? 'selected' : '' }}>06:00</option>
                                               <option value="07:00" {{ $value->ot_hrs == '07:00' ? 'selected' : '' }}>07:00</option>
                                               <option value="08:00" {{ $value->ot_hrs == '08:00' ? 'selected' : '' }}>08:00</option>
                                               <option value="09:00" {{ $value->ot_hrs == '09:00' ? 'selected' : '' }}>09:00</option>
                                               <option value="10:00" {{ $value->ot_hrs == '10:00' ? 'selected' : '' }}>10:00</option>
                                               <option value="11:00" {{ $value->ot_hrs == '11:00' ? 'selected' : '' }}>11:00</option>
                                               <option value="12:00" {{ $value->ot_hrs == '12:00' ? 'selected' : '' }}>12:00</option>
                                            
                                            </select>
                                             </td>
                                            <td>
                                                <input type="text" name="bulk_ot_amt[]" class="form-control input-sm bulk_ot_amt" value="{{$value->ot_amount}}">
                                            </td>
                                             <td>
                                                <input type="text" name="bulk_food_amount[]" class="form-control input-sm bulk_food_amount" value="{{$value->food_amount}}">
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
                                              <select name="bulk_ot_hrs[]" class="form-control bulk_ot_hrs select2" data-show-subtext="true" data-live-search="true" required>
                                               <option value="">--Please Select--</option>
                                            
                                               <option value="01:00" >01:00</option>
                                               <option value="02:00">02:00</option>
                                               <option value="03:00">03:00</option>
                                               <option value="04:00">04:00</option>
                                               <option value="05:00">05:00</option>
                                               <option value="06:00">06:00</option>
                                               <option value="07:00">07:00</option>
                                               <option value="08:00">08:00</option>
                                               <option value="09:00">09:00</option>
                                               <option value="10:00">10:00</option>
                                               <option value="11:00">11:00</option>
                                               <option value="12:00">12:00</option>
                                            
                                            </select>
                                             </td>
                                            <td>
                                                <input type="text" name="bulk_ot_amt[]" class="form-control input-sm bulk_ot_amt" value="">
                                            </td>
                                              <td>
                                                <input type="text" name="bulk_food_amount[]" class="form-control input-sm bulk_food_amount" value="">
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
    <a href="{{ URL::to('epamountentry') }}" class="btn btn-secondary px-4">Cancel</a>
  </div>	

        </div> 
    </div>
</form>

@endsection
@push('scripts')	
	
<script>
  
	// Add Row
$(document).on('click', '.add-row', function () {
    const $lastRow = $('.clone_lines_body tr:last');
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
    $('.clone_lines_body').append($newRow);

    // Reinitialize select2
    $newRow.find('select.select2').select2({ width: '100%' });

		// Update line numbers
		updateLineNumbers();
	});



  // Remove button
  $(document).on('click', '.remove-row', function () {
    const rowCount = $('.clone_lines_body tr').length;
    if (rowCount > 1) {
      $(this).closest('tr').remove();
      updateLineNumbers();
    } else {
      showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
  });

  // Renumber Line Nos
  function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
      $(this).find('.bulk_line_no').val(index + 1);
    });
  }

     // Save Form

$(document).on('click', '.saveform', function () {
    const form = $("#employee_form");
    let dup_chk = true; // Declare duplicate check variable

    form.parsley().validate(); // Validate form using Parsley

    if (form.parsley().isValid() && dup_chk === true) {
        const formData = form.serialize(); 
			var $btn = $(this);            
			$btn.prop('disabled', true);
        $.ajax({
            url: "{{ url('epamountentrysave') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    showCustomAlert(response.message || 'Saved successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ url('epamountentry') }}";
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