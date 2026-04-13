@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Professional Tax</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow rounded-4">
    <form id="save" enctype="multipart/form-data" data-parsley-validate>
      {{ csrf_field() }}
      <input type="hidden" name="edit_id" value="{{ $ptax_state_id }}" id="edit_id" />

      <div class="card-body">
        <div class="row g-4">
          <!-- State -->
          <div class="col-md-4">
            <label class="form-label"><span class="text-danger">*</span> State</label>
            <div class="input-group">
              <select id="state_id" name="state_id" class="form-select select2 state_id" required>
                {!! $state !!}
              </select>
            </div>
            <span class="badge bg-danger dup_name d-none"></span>
          </div>

          <!-- Description -->
          <div class="col-md-4">
            <label class="form-label">Description</label>
            <input type="text" id="description" name="description" class="form-control" value="{{ $description }}">
          </div>

          <!-- Active -->
          <div class="col-md-4">
            <label class="form-label"><span class="text-danger">*</span> Active</label>
            <select id="active" name="active" class="form-select select2 active" required>
              <option value="">-- Please Select --</option>
              <option value="Yes" {{ $active == 'Yes' ? 'selected' : '' }}>Yes</option>
              <option value="No" {{ $active == 'No' ? 'selected' : '' }}>No</option>
            </select>
          </div>
        </div>


        <div class="row mt-4">
          <div class="col-12 linetable">
            <div id="preview-area" class="table-responsive">
              <table class="table table-bordered clone_table">
                <thead class="table-light">
                  <tr>
                    <th>From Value</th>
                    <th>To Value</th>
                    <th>Deduction Amount</th>
                    <th style="width: 60px;"></th>
                  </tr>
                </thead>
                <tbody class="clone_lines_body">
                  @if(count($line_data) > 0)
                    @foreach($line_data as $key => $value)
                      <tr class="line-row">

                        <td> <input type="hidden" name="professional_tax_id[]" value="{{ $value->ptax_details_id }}" />
                          <input type="text" class="form-control from_value" name="from_value[]"
                            value="{{ $value->from_value }}" required>
                        </td>
                        <td><input type="text" class="form-control to_value" name="to_value[]" value="{{ $value->to_value }}"
                            required></td>
                        <td><input type="text" class="form-control deduction_amount" name="deduction_amount[]"
                            value="{{ $value->deduction_amount }}" required></td>
                        <td>
                        <td class="text-center">
                          <button type="button" class="btn btn-sm btn-danger remove-row">
                            <i class="fas fa-minus-circle"></i>
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr class="line-row">

                      <td><input type="hidden" name="professional_tax_id[]" value="" />
                        <input type="text" class="form-control from_value" name="from_value[]" required>
                      </td>
                      <td><input type="text" class="form-control to_value" name="to_value[]" required></td>
                      <td><input type="text" class="form-control deduction_amount" name="deduction_amount[]" required></td>
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
          <button type="button" class="btn btn-success save_form px-4 me-2">Save</button>
          <a href="{{ url('professional') }}" class="btn btn-secondary px-4">Cancel</a>

        </div>

    </form>

  </div>









@endsection
@push('scripts')

  <script>


    /*Validation*/
    $(document).on('keypress', '.from_value,.to_value,.deduction_amount', function (ev) {
      var regex = new RegExp("^[0-9.]+$");
      var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
      if (regex.test(str)) {
        return true;
      }
      ev.preventDefault();
      return false;
    });

    $("#state_id").change(function () {
      $('.dup_name').hide();
    });

    // dub check
    var dup_chk = true;

    function duplicate_validate() {

      var state_id = $(".state_id").val();
      var state_name = $(".state_id option:selected").text();

      var edit_id = $("#edit_id").val();
      $.ajax({
        cache: false,
        url: "{{URL::to('ptcheckname')}}", //this is your uri
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { state_id: state_id, edit_id: edit_id },
        success: function (response) {
          if (response == 1) {
            $('.dup_name')
              .html('State: ' + state_name + ' already exists')
              .removeClass('d-none')
              .addClass('d-block');

            $(".state_name").val('');
            dup_chk = false;
          }
          else if (response == 0) {
            var html = "";
            $('.dup_name').hide();
            dup_chk = true;

          }
        },
        error: function (xhr, resp, text) {
          console.log(xhr, resp, text);
        }
      });
    }
    $(".shift_name").keyup(function () {
      $('.dup_name').hide();
    });


    // save
    $(document).on('click', '.save_form', function () {

      var data;
      data = $("#save").serialize();
      duplicate_validate();
      var form = $('#save');
      form.parsley().validate();
      if (form.parsley().isValid() && dup_chk) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.post("{{ URL::to('professionaltaxsave')}}", data, function (data) {
          if (data == 1) {

            showCustomAlert('Professional Tax Saved Successfully', 'success');
            setTimeout(function () {
              var url = "{{URL::to('professional')}}";
              window.location.href = url;
            }, 1300);
          }
          else if (data == 2) {
            showCustomAlert('Professional Tax Updated Successfully', 'success');
            var url = "{{URL::to('professional')}}";
            window.location.href = url;
          }
        });
      }

    });

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


  </script>

@endpush