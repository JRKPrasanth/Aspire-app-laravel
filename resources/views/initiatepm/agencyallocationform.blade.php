@extends('layouts.header')
@section('content')
  <h3 class="text-danger">PM Agency / Engineer Allocation </h3>
  @include('layouts.breadcrumb')


  <form method="post" action="" id="pmclearance" data-parsley-validate>
    {{ csrf_field() }}
    <input type="hidden" name="savestatus" id="savestatus">
    <input type="hidden" name="initiate_pm_id" id="initiate_pm_id" class="form-control initiate_pm_id"
      value="{{ $initiate_pm_id }}" readonly>
    <input type="hidden" name="status" class="status">
    <input type="hidden" name="postpone_status" class="postpone_status">

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body">

        <div class="row g-4">

          <!-- Left Column -->
          <div class="col-md-6">

            <div class="mb-3 row">
              <label class="col-md-4 col-form-label">PM No</label>
              <div class="col-md-8">
                <input type="text" id="pm_no" name="pm_no" class="form-control pm_no" value="{{ $pm_no }}" readonly>
                <span class="btn btn-danger dup_name d-none"></span>
              </div>
            </div>

            <div class="mb-3 row none">
              <label class="col-md-4 col-form-label">Machine Name</label>
              <div class="col-md-8">
                <select name="machine_id" class="form-select select2 machine_id" id="machine_id">
                  {!! $machine_id !!}
                </select>
              </div>
            </div>

            <div class="mb-3 row none">
              <label class="col-md-4 col-form-label">Initiated Date</label>
              <div class="col-md-8">
                <input type="text" name="actual_pm_date" id="actual_pm_date"
                  class="form-control actual_pm_date datepicker" value="{{ $initiate_date }}" readonly>
              </div>
            </div>

            <div class="mb-3 row clearance_date">
              <label class="col-md-4 col-form-label"><span class="text-danger">*</span> Postpone To</label>
              <div class="col-md-8">
                <input type="text" name="postponed_date" id="postponed_date"
                  class="form-control postponed_date datepicker" value="{{ $postponed_date }}">
              </div>
            </div>

            <div class="mb-3 row shift_timings none">
              <label class="col-md-4 col-form-label"><span class="text-danger">*</span> Shift Timing</label>
              <div class="col-md-8">
                <input type="text" name="shift_timing" id="shift_timing" class="form-control shift_timing datepicker"
                  value="{{ $shift_timing }}" readonly>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-md-4 col-form-label"><span class="text-danger">*</span> <span class="agency_eng">Agency
                  Allocation</span></label>
              <div class="col-md-8">
                <select name="agency_allocation" class="form-select agency_allocation select2" required>
                  <option>--Please Select--</option>
                </select>
              </div>
            </div>

          </div>

          <!-- Right Column -->
          <div class="col-md-6">

            <div class="mb-3 row none">
              <label class="col-md-4 col-form-label">Department Name</label>
              <div class="col-md-8">
                <select name="department_id" class="form-select department_id select2" id="department_id">
                  {!! $department_id !!}
                </select>
              </div>
            </div>

            <div class="mb-3 row none">
              <label class="col-md-4 col-form-label">User Clearance By</label>
              <div class="col-md-8">
                <select name="user_clearance_by[]" class="form-select user_clearance_by select2" id="user_clearance_by"
                  multiple>
                  {!! $user_clearance_by !!}
                </select>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-md-4 col-form-label"><span class="text-danger">*</span> Change Initiated Date</label>
              <div class="col-md-8">
                <select name="change_date" class="form-select change_date file select2" required>
                  <option value="0">Please Select</option>
                  <option value="1" @if($change_date == 1) selected @endif>Yes</option>
                  <option value="2" @if($change_date == 2) selected @endif>No</option>
                </select>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-md-4 col-form-label"><span class="text-danger">*</span> Allocation Type</label>
              <div class="col-md-8">
                <select name="allocation_type" class="form-select allocation_type select2" tabindex="4" required>
                  <option>--Please Select--</option>
                  <option value="agency">Agency</option>
                  <option value="engineer">Engineer</option>
                </select>
              </div>
            </div>

          </div>

        </div>

        <!-- Buttons -->
        <div class="row mt-4">
          <div class="col text-center">
            <button type="button" class="btn btn-success save_btn saveform px-4 me-2">Save</button>
            <a href="{{ URL::to('pmagencyallocation') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
          </div>
        </div>

      </div>
    </div>
  </form>



@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      $(document).on('change', '.allocation_type', function () {
        var type = $(this).val();

        if (type === "engineer") {
          $('.agency_eng').html("Engineer Allocation");

          var url = "{{ URL::to('jcomboforminv?table=hr_employee_t:employee_id:employee_number|first_name') }}&parent=department=53&order_by=employee_number asc";

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

              $('.agency_allocation').html('<option value="">-- Please Select --</option>');
              $.each(data, function (i, item) {
                $('.agency_allocation').append(`<option value="${item.val}">${item.option_name}</option>`);
              });

              $('.agency_allocation').trigger('change.select2');
            }
          });

        } else {
          $('.agency_eng').html("Agency Allocation");

          var url = "{{ URL::to('jcomboform1?table=ma_agency_t:agency_id:agency_name') }}";

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

              $('.agency_allocation').html('<option value="">-- Please Select --</option>');
              $.each(data, function (i, item) {
                $('.agency_allocation').append(`<option value="${item.val}">${item.option_name}</option>`);
              });

              $('.agency_allocation').trigger('change.select2');
            }
          });
        }
      });


      var date_change = "{{$change_date}}";
      if (date_change == "1") {
        $(".clearance_date").show();
        $(".shift_timings").hide();
        $(".postponed_date").attr('required', 'required');
        $(".shift_timing").removeAttr('required', false);

      } else {

        $(".clearance_date").hide();
        $(".shift_timings").show();
        $(".shift_timing").attr('required', 'required');
        $(".postponed_date").removeAttr('required', false);
      }

      /* purpose:to save function*/
      $('#savestatus').val('');
      $(document).on('click', '.saveform', function () {

        var btnval = $(this).val();
        if (btnval == 'APPLYCHANGES')
          var savestatus = 'APPLY CHANGES';
        else if (btnval == 'SAVE' || btnval == 'SAVENEW')
          var savestatus = 'SAVE';

        $('#savestatus').val(savestatus);
        var url = "{{ URL::to('pmagencyallocationsave') }}";
        var red_url = "{{ URL::to('pmagencyallocation') }}";
        var formdata = $('#pmclearance').serialize();
        var form = $('#pmclearance');
        if (btnval != 'APPLYCHANGES') {
          form.parsley().validate();
          var form = $('#pmclearance');
          form.parsley().validate();
          if (form.parsley().isValid()) {
            var $btn = $(this);
            $btn.prop('disabled', true);
            $.post(url, formdata, function (data) {
              var status = data.status;
              var msg = '<span style="color:#090065">' + data.message + '</span>  ';
              var id = data.id;
              if (btnval != 'SAVE' && btnval != 'DRAFT') {

                showCustomAlert('Saved successfully!', 'success');
                setTimeout(function () {
                  window.location.href = red_url;
                }, 1500);
              }
              else {
                showCustomAlert(msg, status);
                setTimeout(function () {
                  window.location.href = red_url;
                }, 1500);
              }
            });
            return false;
          }
        }
      });

    });
  </script>

@endpush