@extends('layouts.header')
@section('content')
<h2 class="text-danger">Create Company</h2>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
<div class="card-body card-block">
<form method="post" action="" id="company_form" class="needs-validation" data-parsley-validate novalidate>
  @csrf
  <input type="hidden" name="savestatus" id="savestatus" />
  <input type="hidden" name="company_id" id="company_id" value="{{ $row->company_id }}">

  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> Company Name</label>
      <input type="text" name="company_name" class="form-control" value="{{ $row->company_name }}" required>
      <div class="invalid-feedback dup_name d-none"></div>
    </div>

    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> Company Code</label>
      <input type="text" name="company_code" class="form-control" value="{{ $row->company_code }}" required>
    </div>

    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> Website Address</label>
      <input type="text" name="website_address" class="form-control" value="{{ $row->website_address }}" required>
    </div>

    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> Email ID</label>
      <input type="email" name="email_id" class="form-control" value="{{ $row->email_id }}" required>
      <div class="invalid-feedback email_vali d-none">Email format is example123@gmail.com</div>
    </div>

    <div class="col-md-4">
      <label class="form-label">Contact No</label>
      <input type="text" name="contact_no" class="form-control" value="{{ $row->contact_no }}" maxlength="10">
    </div>

    <div class="col-md-4">
      <label class="form-label"><span class="text-danger">*</span> GST No</label>
      <input type="text" name="gst_no" class="form-control" value="{{ $row->gst_no }}" maxlength="15" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Service Tax Reg No</label>
      <input type="text" name="tax_reg_no" class="form-control" value="{{ $row->tax_reg_no }}" maxlength="15">
    </div>

    <div class="col-md-4">
      <label class="form-label">Excise Reg No</label>
      <input type="text" name="excise_registration_no" class="form-control" value="{{ $row->excise_registration_no }}" maxlength="15">
    </div>

    <div class="col-md-4">
      <label class="form-label">CIN No</label>
      <input type="text" name="cin_no" class="form-control" value="{{ $row->cin_no }}" maxlength="30">
    </div>

    <div class="col-md-4">
      <label class="form-label">PAN No</label>
      <input type="text" name="pan_no" class="form-control" value="{{ $row->pan_no }}" maxlength="15">
    </div>

    <div class="col-md-4">
      <label class="form-label">Active</label>
      <select name="active" class="form-select select2">
        <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
        <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
      </select>
    </div>
  </div>
<!--********************* Line Data table Section - Bootstrap 5 Version *********************-->
	
<div class="row mt-4">
  <div class="col-12 linetable">
<div id="preview-area" class="table-responsive">
  <table class="table table-bordered company_table">
    <thead class="table-light">
      <tr>
        <th style="width: 80px;">Line No</th>
        <th>Location Name</th>
        <th>Description</th>
        <th style="width: 60px;"></th>
      </tr>
    </thead>
<tbody class="company_lines_body">
  @if(count($linedata) > 0)
    @foreach($linedata as $key => $value)
      <tr class="line-row">
        <td>
          <input type="hidden" name="bulk_company_line_id[]" class="form-control input-sm bulk_company_line_id" value="{{ $value->company_line_id }}">
          <input type="text" class="form-control bulk_line_no" name="bulk_line_no[]" value="{{ $key + 1 }}" readonly>
        </td>
        <td>
          <select name="bulk_locationid[]" class="form-select select2 bulk_locationid" required>
            {!! $value->location_id !!}
          </select>
        </td>
        <td>
          <input type="text" class="form-control bulk_description" name="bulk_description[]" value="{{ $value->description }}">
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
        <input type="hidden" name="bulk_company_line_id[]" class="form-control input-sm bulk_company_line_id" value="">
        <input type="text" class="form-control bulk_line_no" name="bulk_line_no[]" value="1" readonly>
      </td>
      <td>
        <select name="bulk_locationid[]" class="form-select select2 bulk_locationid" required>
          {!! $location_id !!}
        </select>
      </td>
      <td>
        <input type="text" class="form-control bulk_description" name="bulk_description[]" placeholder="Enter description">
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
    <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4 me-2">Cancel</a>
  </div>
</form>
</div>
</div>   

@endsection
@push('scripts')

<script>

	// Init select2 on page load
$(function () {
  $('.company_lines_body').find('select.select2').select2({ width: '100%' });
});

// Add Row
$(document).on('click', '.add-row', function () {
    const $tbody   = $('.company_lines_body');
    const $lastRow = $tbody.find('tr:last');

    // 1) Destroy select2 on the last row BEFORE cloning
    $lastRow.find('select.select2').each(function () {
        if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
    });

    // 2) Clone the cleaned row
    const $newRow = $lastRow.clone(false, false);

    // 3) Clear values in the cloned row
    $newRow.find('.bulk_company_line_id').val('');
    $newRow.find('.bulk_line_no').val(''); // will be set by updateLineNumbers()
    $newRow.find('.bulk_description').val('');
    $newRow.find('select.bulk_locationid').val(null); // no option selected

    // 4) Append cloned row
    $tbody.append($newRow);

    // 5) Re-init select2 on ALL location selects inside the tbody
    $tbody.find('select.select2').select2({ width: '100%' });

    // 6) Update line numbers
    updateLineNumbers();
});

// Remove button
$(document).on('click', '.remove-row', function () {
    const rowCount = $('.company_lines_body tr').length;
    if (rowCount > 1) {
        $(this).closest('tr').remove();
        updateLineNumbers();
    } else {
        showCustomAlert("You Can't Delete ", "warning");
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
    const form = $("#company_form");
    let dup_chk = true; // Declare duplicate check variable

    form.parsley().validate(); // Validate form using Parsley


    if (form.parsley().isValid() && dup_chk === true) {
        const formData = form.serialize(); // Serialize form data
			var $btn = $(this);            
			$btn.prop('disabled', true);
        $.ajax({
            url: "{{ url('companysave') }}",
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    showCustomAlert('Saved successfully!','success');
                    setTimeout(() => {
                        window.location.href = "{{ url('company') }}";
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

    // Email Format Check
    function ValidateEmail(email) {
        const regex = /^([\w-\.]+)@([\w-]+\.)+([a-zA-Z]{2,4})$/;
        return regex.test(email);
    }

    // Duplicate Name Check
    var dup_chk = true;
    function duplicate_validate() {
        const name = $(".company_name").val();
        const id = $("#company_id").val();

        $.ajax({
            url: "{{URL::to('companycheckname')}}",
            type: 'GET',
            data: { company_name: name, company_id: id },
            async: false,
            success: function (res) {
                if (res == 1) {
                    $('.dup_name').text('Company Name: ' + name + ' already exists').show();
                    $(".company_name").val('');
                    dup_chk = false;
                } else {
                    $('.dup_name').hide();
                    dup_chk = true;
                }
            }
        });
    }


</script>

@include('layouts.php_js_validation')
@endpush