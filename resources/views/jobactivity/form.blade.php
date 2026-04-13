@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Job Activity</h3>
  <?php error_reporting(0);?>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold"></div>
    <div class="card-body card-block">

      <form action="" id="beatmapping_form">
        <input type="hidden" name="job_activity_id" value="{{ $row->job_activity_id }}" id="job_activity_id" />
        <input type="hidden" name="beatmappinglines_id" value="" id="beatmappinglines_id" />
        {{ csrf_field()}}

        <div class="row">
          <div class="col-md-4">
            <div class="row mb-3">
              <label for="inputIsValid" class="col-form-label col-md-5"><span class="req">*</span>Employee Name</label>
              <div class="col-md-7">
                <select name='employee_id' id="employee_id" rows='5' class='employee_id select2' required="required">
                  {!! $employee_id!!}
                </select>
              </div>
              <span class="btn btn-danger dup_name" style="display:none;"></span>
            </div>
          </div>
          <div class="col-md-4">

            <div class="row mb-3">
              <label for="inputIsValid" class="col-form-label col-md-5"><span class="req">*</span>Type</label>
              <div class="col-md-7">
                <select name="type" class="form-control type select2" data-live-search="true" required="required">
                  <option value="">--Please Select--</option>
                  <option value="MAJOR" {{ $row->type == 'MAJOR' ? 'selected' : '' }}>MAJOR</option>
                  <option value="SUB" {{ $row->type == 'SUB' ? 'selected' : '' }}>SUB</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-4">

            <div class="row mb-3">
              <label for="inputIsValid" class="col-form-label col-md-5">Remarks</label>
              <div class="col-md-7">
                <input type="text" id="remarks" name="remarks" class="form-control remarks" value="{{ $row->remarks }}">
              </div>
            </div>
          </div>
        </div>


        <!-------------------------Linedata -------------------------------->

        <div class="row mt-2">
          <div class=" col-md-12">
            <div id="preview-area" class="table-responsive">
              <table class="table table-bordered clone_table" style="width: 150%;">
                <thead class="table-light">
                  <tr>
                    <th>Line No</th>
                    <th>Activity Name</th>
                    <th>Product Name</th>
                    <th>Machine Name</th>
                    <th>Batch No</th>
                    <th>Qty</th>
                    <th>Start DateTime</th>
                    <th>End DateTime</th>
                    <th>Duration</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>

                <tbody class="clone_lines_body">
                  <?php  if (count($linedata) <= 0) { ?>
                  <tr class="rcopy clone clonedInput">
                    <td><input type="hidden" name="bulk_job_activity_line_id[]"
                        class="form-control  bulk_job_activity_line_id" value="">
                      <input type="text" name="bulk_line_no[]" class="form-control  bulk_line_no" value="{{ $key + 1 }}"
                        readonly="readonly">
                    </td>
                    <td><select id="bulk_activity_name" name="bulk_activity_name[]" class="bulk_activity_name select2"
                        value="" required>{!! $activity_name !!}</select></td>
                    <td><select id="bulk_product" name="bulk_product[]" class=" bulk_product select2"
                        value="">{!! $product !!}</select></td>
                    <td><select id="bulk_machine" name="bulk_machine[]" class=" bulk_machine select2"
                        value="">{!! $machine !!}</select></td>
                    <td> <input type="text" name="bulk_batch[]" class="form-control  bulk_batch"></td>
                    <td> <input type="text" name="bulk_qty[]" class="form-control  bulk_qty"></td>
                    <td><input type='text' name='bulk_start_datetime[]'
                        class=' bulk_start_datetime form-control start_datetime' value='' required="required"></td>
                    <td><input type='text' name='bulk_end_datetime[]' class='bulk_end_datetime  form-control end_datetime'
                        value='' required="required"></td>
                    <td> <input type="text" name="bulk_duration[]" class="form-control  bulk_duration"
                        readonly="readonly"></td>
                    <td class="text-center">
                      <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fas fa-minus-circle"></i>
                      </button>
                    </td>
                  </tr>
                  <?php } else {
    foreach ($linedata as $key => $value) { ?>
                  <tr class="rcopy clone clonedInput">
                    <td><input type="hidden" name="bulk_job_activity_line_id[]"
                        class="form-control  bulk_job_activity_line_id" value="{{$value->job_activity_line_id}}">
                      <input type="text" name="bulk_line_no[]" class="form-control  bulk_line_no" readonly="readonly"
                        value="{{$value->line_no}}">
                    </td>
                    <td><select id="bulk_activity_name" name="bulk_activity_name[]" class=" bulk_activity_name select2"
                        value="" required>{!! $value->activity_name !!}</select></td>
                    <td><select id="bulk_product" name="bulk_product[]" class="select2 bulk_product"
                        value="">{!! $value->product !!}</select></td>
                    <td><select id="bulk_machine" name="bulk_machine[]" class="select2 bulk_machine"
                        value="">{!! $value->machine !!}</select></td>
                    <td> <input type="text" name="bulk_batch[]" class="form-control  bulk_batch"
                        value="{{$value->batch_no}}"></td>
                    <td> <input type="text" name="bulk_qty[]" class="form-control  bulk_qty" value="{{$value->qty}}"></td>
                    <td><input type='text' name='bulk_start_datetime[]'
                        class="bulk_start_datetime form-control start_datetime" value="{{$value->start_datetime}}"
                        required="required"></td>
                    <td><input type='text' name='bulk_end_datetime[]' class='bulk_end_datetime  form-control end_datetime'
                        value="{{$value->end_datetime}}" required="required"></td>
                    <td> <input type="text" name="bulk_duration[]" class="form-control  bulk_duration"
                        value="{{$value->duration}}" readonly="readonly"></td>
                    <td class="text-center">
                      <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fas fa-minus-circle"></i>
                      </button>
                    </td>
                  </tr>
                  <?php  }
  } ?>
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

        <div class="row mt-4 mb-3">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">
              <button name="submit" type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
              <a class='btn btn-danger px-4 me-2' href="{{ url('jobactivity') }}">Cancel</a>
            </div>
          </div>
        </div>

      </form>

    </div>
  </div>

  <input type="hidden" class="so" value='0'>
  <input type="hidden" class="pdtindex" value="" />



@endsection
@push('scripts')

  <script>

    // Add Row
    $(document).on('click', '.add-row', function () {

      const $lastRow = $('.clone_lines_body tr:last');
      const $newRow = $lastRow.clone(false, false); // clone without events

      // Clear all input values
      $newRow.find('input').val('');
      $newRow.find('select').val('').trigger('change');

      // Remove Select2 artifacts before reinitializing
      $newRow.find('select.select2').each(function () {
        if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
          $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id');
        $(this).next('.select2').remove();
      });

      // Remove existing datepicker instance (important)
      $newRow.find('.start_datetime, .end_datetime').each(function () {
        $(this).removeClass('hasDatepicker'); // for jQuery UI
        $(this).datepicker('destroy'); // if already initialized
      });

      // Append row
      $('.clone_lines_body').append($newRow);

      // Reinitialize Select2
      $newRow.find('select.select2').select2({
        width: '100%'
      });

      const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
      const timeFormat = "HH:mm:ss";
      var d = new Date();
      var jobdate = d.setDate(d.getDate() - 30);

      // ✅ Reinitialize Datepicker
      $newRow.find('.start_datetime').datetimepicker({

            changeMonth: true,
            changeYear: true,
            dateFormat: dateFormat,
            timeFormat: timeFormat,
            controlType: 'select',
            oneLine: true,
            showSecond: true,
            minDate: new Date(jobdate),
            maxDate: new Date(),
            showAnim: "slideDown",
            yearRange: "-25:+0",
            onSelect: function () {
              let startDate = $(this).datetimepicker("getDate");
              $(this).closest('tr').find(".end_datetime").datetimepicker("option", "minDate", startDate);
            }
        
      });

      $newRow.find('.end_datetime').datetimepicker({

            changeMonth: true,
            changeYear: true,
            dateFormat: dateFormat,
            timeFormat: timeFormat,
            controlType: 'select',
            oneLine: true,
            showSecond: true,
            minDate: new Date(jobdate),
            maxDate: new Date(),
            showAnim: "slideDown",
            yearRange: "-25:+0"
      
        
      });

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


    $(document).ready(function () {

      // save
      $(document).on('click', '.saveform', function () {

        var form = $('#beatmapping_form');
        form.parsley().validate();
        if (form.parsley().isValid()) {

          var $btn = $(this);
          $btn.prop('disabled', true);
          var form_data = new FormData(document.getElementById('beatmapping_form'));
          $.ajax({

            url: "{{URL::to('jobactivitysave')}}",
            type: "POST",
            data: form_data,
            enctype: 'multipart/form-data',
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            async: true,
            xhr: function () {
              var xhr = $.ajaxSettings.xhr();
              if (xhr.upload) {
                xhr.upload.addEventListener('progress', function (event) {
                  var percent = 0;
                  var position = event.loaded || event.position;
                  var total = event.total;
                  if (event.lengthComputable) {
                    percent = Math.ceil(position / total * 100);
                  }

                }, true);
              }
              return xhr;

            }
          }).done(function (data) {
            var status = data.status;
            var msg = data.message;
            var url = "{{URL::to('jobactivity')}}";


            showCustomAlert(msg, status);
            setTimeout(function () {
              window.location.href = url;
            }, 1500);

          });

        }

      });


      const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
      const timeFormat = "HH:mm:ss";
      var d = new Date();
      var jobdate = d.setDate(d.getDate() - 30);

      // Start datetime
      $(document).on("focus", ".start_datetime", function () {
        if (!$(this).hasClass("hasDatepicker")) {   // prevent double init
          $(this).datetimepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: dateFormat,
            timeFormat: timeFormat,
            controlType: 'select',
            oneLine: true,
            showSecond: true,
            minDate: new Date(jobdate),
            maxDate: new Date(),
            showAnim: "slideDown",
            yearRange: "-25:+0",
            onSelect: function () {
              let startDate = $(this).datetimepicker("getDate");
              $(this).closest('tr').find(".end_datetime").datetimepicker("option", "minDate", startDate);
            }
          });
        }
      });

      // End datetime
      $(document).on("focus", ".end_datetime", function () {
        if (!$(this).hasClass("hasDatepicker")) {
          $(this).datetimepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: dateFormat,
            timeFormat: timeFormat,
            controlType: 'select',
            oneLine: true,
            showSecond: true,
            minDate: new Date(jobdate),
            maxDate: new Date(),
            showAnim: "slideDown",
            yearRange: "-25:+0"
          });
        }
      });



      $(document).on("change", ".bulk_start_datetime,.bulk_end_datetime", function () {
        var $row = $(this).closest('tr');  // get current row
        var start = $row.find('.bulk_start_datetime').val();
        var end = $row.find('.bulk_end_datetime').val();

        if (start && end) {
          hourscal($row);
        }
      });

      function hourscal($row) {
        var startVal = $row.find('.bulk_start_datetime').val();
        var endVal = $row.find('.bulk_end_datetime').val();

        var start = new Date(startVal);
        var end = new Date(endVal);

        if (start && end && end > start) {
          var diffMs = end - start;
          var diffSeconds = diffMs / 1000;

          var HH = Math.floor(diffSeconds / 3600);
          var MM = Math.floor((diffSeconds % 3600) / 60);

          var totalHr = HH + '.' + (MM < 10 ? '0' + MM : MM);
          $row.find('.bulk_duration').val(totalHr);
        } else {
          $row.find('.bulk_duration').val('');
        }
      }



    });


    $(document).on('change', '.type', function () {
      console.log("Type changed");
      var batchtype = $('.type').val();
      if (batchtype === "MAJOR") {

        $('.bulk_product').attr('required', true);
        $('.bulk_machine').attr('required', true);
        $('.bulk_batch').attr('required', true);
        $('.bulk_qty').attr('required', true);


      } else {

        $('.bulk_product').removeAttr('required');
        $('.bulk_machine').removeAttr('required');
        $('.bulk_batch').removeAttr('required');
        $('.bulk_qty').removeAttr('required');
      }
    });

  </script>


@endpush