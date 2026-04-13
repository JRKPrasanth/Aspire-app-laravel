@extends('layouts.header')
@section('content')
  <h2 class="text-danger">Approval Settings </h2>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body mt-2">
      <form method="post" action="" id="approvalsettings" class="approvalsettings" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="save_status" id="save_status" value="" />
        <input type="hidden" name="approvalsettings_hdr_id" id="approvalsettings_hdr_id"
          class="form-control approvalsettings_hdr_id" value="{{ $row->approvalsettings_hdr_id }}" readonly>

        <div class="row g-3">
          <!-- Module Name -->
          <div class="col-md-6">
            <div class="form-group row">
              <label for="module_name" class="col-form-label col-md-5">
                <span class="text-danger">*</span>Module Name
              </label>
              <div class="col-md-7">
                <select name="module_name" id="module_name" class="form-select select2 module_name" required>
                  {!! $module_name !!}
                </select>
                <span class="text-danger dup_name d-none"></span>
              </div>
            </div>
          </div>

          <!-- Created By -->
          <div class="col-md-6 none">
            <div class="form-group row">
              <label for="created_by" class="col-form-label col-md-5">Created By</label>
              <div class="col-md-7">
                <select name="created_by" id="created_by" class="form-select select2 created_by">
                  {!! $created_by !!}
                </select>
              </div>
            </div>
          </div>
        </div>

        <!--****************Linedata ********************-->
        <div class="row mt-4">
          <div class="col-12 linetable">
            <div class="table-responsive">
              <table class="table table-bordered company_table">
                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th>Value From</th>
                    <th>Value To</th>
                    <th>Approve Required</th>
                    <th>Approver</th>
                    <th>Comments</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="company_lines_body">
                  @if(count($linedata) > 0)
                    @foreach($linedata as $key => $value)
                                  <tr class="line-row">
                                    <td>
                                      <input type="hidden" name="bulk_approvalsettings_line_id[]"
                                        class="form-control input-sm bulk_approvalsettings_line_id"
                                        value="{{ $value->approvalsettings_line_id }}">
                                      <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                                        value="{{ $key + 1 }}" readonly="readonly">
                                    </td>
                                    <td><input type="text" name="bulk_value_from[]" class="form-control input-sm bulk_value_from"
                                        value="{{ $value->value_from }}"></td>


                                    <td>
                                      <input type="text" name="bulk_value_to[]"
                                        class="form-control input-sm bulk_value_to input_qty_width" value="{{ $value->value_to }}"
                                        minlength="1">
                                    </td>
                                    <td>
                                      <select type="bulk_approve_required" name="bulk_approve_required[]"
                                        class="select2 bulk_approve_required" required>
                                        <option value="">Please select</option>
                                        <option value="Yes" <?php    if ($value->approve_required == 'Yes') {
                        echo "selected";
                      } else {
                        echo "";
                      } ?>>Yes</option>
                                        <option value="No" <?php    if ($value->approve_required == 'No') {
                        echo "selected";
                      } else {
                        echo "";
                      } ?>>No</option>
                                      </select>
                                    </td>
                                    <td>

                                      <select name="bulk_approver_id[]" class="select2 input-sm bulk_approver_id">
                                        {!! $value->approver_id !!}
                                      </select>
                                    </td>
                                    <td>
                                      <input id="bulk_comments" type="text" name="bulk_comments[]" class="form-control bulk_comments"
                                        value="{{$value->comments}}" row="5" />
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
                        <input type="hidden" name="bulk_approvalsettings_line_id[]"
                          class="form-control input-sm bulk_approvalsettings_line_id" value="">
                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1"
                          readonly="readonly">
                      </td>
                      <td><input type="text" name="bulk_value_from[]" class="form-control input-sm bulk_value_from" value=""
                          required></td>
                      <td>
                        <input type="text" name="bulk_value_to[]"
                          class="form-control input-sm bulk_value_to input_qty_width" value="" minlength="1">
                      </td>
                      <td>
                        <select type="bulk_approve_required" name="bulk_approve_required[]"
                          class="select2 bulk_approve_required">
                          <option value="">--please select--</option>
                          <option value="Yes" selected="selected">Yes</option>
                          <option value="No">No</option>
                        </select>
                      </td>
                      <td>
                        <select name="bulk_approver_id[]" class="select2 input-sm bulk_approver_id" required>
                          {!! $approver_id !!}
                        </select>
                      </td>
                      <td>
                        <input type="text" id="bulk_comments" name="bulk_comments[]" class="form-control bulk_comments"
                          value="" row="5" />
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
          <a href="{{ URL::to('approvalsettings') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
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
      const $tbody = $('.company_lines_body');
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
        showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
      }
    });

    // Renumber Line Nos
    function updateLineNumbers() {
      $('.company_lines_body tr').each(function (index) {
        $(this).find('.bulk_line_no').val(index + 1);
      });
    }


    $('.bulk_approve_required').change(function () {
      var req = $(this).val();
      if (req == 'Yes') {
        $('.bulk_approver_id').attr('required', true);
      } else {
        $('.bulk_approver_id').attr('required', false);
      }
    });

    $(document).on('keypress', '.bulk_value_from,.bulk_value_to', function (ev) {
      var regex = new RegExp("^[0-9.]+$");
      var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
      if (regex.test(str)) {
        return true;
      }
      ev.preventDefault();
      return false;
    });


    var pagemode = '<?php echo $pagemode;?>';
    if (pagemode == 'create') {
      $(".state,.city").css('pointer-events', 'none');
    }


    $('.carrier_name').on('keyup', function () {
      this.value = this.value.toUpperCase();
    });

    // Save Form

    $(document).on('click', '.saveform', function () {
      const form = $("#approvalsettings");
      let dup_chk = true; // Declare duplicate check variable

      form.parsley().validate(); // Validate form using Parsley

      if (form.parsley().isValid() && dup_chk === true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        const formData = form.serialize();

        $.ajax({
          url: "{{ url('approvalsettingssave') }}",
          type: "POST",
          data: formData,
          success: function (response) {
            if (response.status === "success") {
              showCustomAlert(response.message || 'Saved successfully!','success');
              setTimeout(() => {
                window.location.href = "{{ url('approvalsettings') }}";
              }, 1500);
            } else {
              showCustomAlert(response.message || 'Save failed. Please check your input.','error');
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