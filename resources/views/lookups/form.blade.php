@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Common Lookups</h3>
  @include('layouts.breadcrumb')


  <form method="post" action="" id="lookups" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body card-block headerdiv1">

        <!------------------------------------- Body content start here ---------------------------->
        <div class="row">
          <input type="hidden" class="form-control" id="lookuphdr_id" name="lookuphdr_id" value="{{ $row->lookuphdr_id }}"
            readonly>

          <div class="col-md-6 mb-3">
            <label for="lookup_type" class="form-label">
              <span class="text-danger">*</span> Field Name
            </label>
            <input type="text" id="lookup_type" name="lookup_type" class="form-control" required
              value="{{ $row->lookup_type }}">
          </div>

          <div class="col-md-6 mb-3">
            <label for="description" class="form-label">Description</label>
            <input name="description" id="description" class="form-control" value="{{ $row->description }}">
          </div>
        </div>

        <!------------------------------------------------------------------------------------------>

        <div class="row mt-2">
          <div class="col-12 linetable">
            <div class="table-responsive">
              <table class="table table-bordered company_table">
                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th>Field Option</th>
                    <th>Field Option Meaning</th>
                    <th>Active</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="company_lines_body">
                  @if(count($linedata) > 0)
                    @foreach($linedata as $key => $value)
                      <tr class="line-row">
                        <td>
                          <input type="hidden" name="bulk_lookuplines_id[]" class="form-control input-sm bulk_lookuplines_id"
                            value="{{ $value->lookuplines_id }}">
                          <input type="text" name="bulk_line_no[]" required class="form-control input-sm bulk_line_no"
                            value="{{ $key + 1 }}" readonly="readonly">
                        </td>

                        <td>
                          <input type="text" name="bulk_lookup_code[]" required class="form-control input-sm bulk_lookup_code"
                            value="{{$value->lookup_code}}">
                        </td>
                        <td>
                          <input type="text" name="bulk_lookup_meaning[]" required
                            class="form-control input-sm bulk_lookup_meaning" value="{{ $value->lookup_meaning }}">
                        </td>

                        <td>
                          <select name="bulk_active[]" id="bulk_active" required class="select2 bulk_active"
                            data-show-subtext="true" data-live-search="true">
                            <option value=''>--Please Select--</option>
                            <?php    if ($value->active == "Yes") { ?>
                            <option value='Yes' selected>Yes</option>
                            <?php    } else { ?>
                            <option value='No' selected>No</option>
                            <?php    } ?>
                          </select>
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
                        <input type="hidden" required name="bulk_lookuplines_id[]"
                          class="form-control input-sm bulk_lookuplines_id" value="" required>
                        <input type="text" required name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                          value="" readonly="readonly">
                      </td>
                      <td>
                        <input type="text" required name="bulk_lookup_code[]" class="form-control input-sm bulk_lookup_code"
                          required="">
                      </td>
                      <td>
                        <input type="text" required name="bulk_lookup_meaning[]"
                          class="form-control input-sm bulk_lookup_meaning" required>
                      </td>

                      <td>
                        <select name="bulk_active[]" required id="bulk_active" class="select2 bulk_active"
                          data-show-subtext="true" data-live-search="true">
                          <option value=''>--Please Select--</option>
                          <option value='Yes'>Yes</option>
                          <option value='No'>No</option>

                        </select>
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
          <a href="{{ url('lookup') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
        </div>
      </div>
    </div>

  </form>



@endsection
@push('scripts')

  <script>

    // Add Row
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
      const form = $("#lookups");
      let dup_chk = true; // Declare duplicate check variable

      form.parsley().validate(); // Validate form using Parsley

      if (form.parsley().isValid() && dup_chk === true) {
        const formData = form.serialize(); // Serialize form data
			  var $btn = $(this);            
			  $btn.prop('disabled', true);

        $.ajax({
          url: "{{ url('lookupsave') }}",
          type: "POST",
          data: formData,
          success: function (response) {
            if (response.status === "success") {
              showCustomAlert(response.message || 'Saved successfully!','success');
              setTimeout(() => {
                window.location.href = "{{ url('lookup') }}";
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