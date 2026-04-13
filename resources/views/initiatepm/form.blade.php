@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Initiate PM</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form method="post" action="" id="initiatepm" data-parsley-validate>
        {{ csrf_field() }}
        <input type="hidden" name="savestatus" id="savestatus" value="">
        <input type="hidden" name="initiate_pm_id" id="initiate_pm_id" class="form-control initiate_pm_id"
          value="{{ $initiate_pm_id }}" readonly>



        <div class="row g-4">
          <div class="col-md-6">

            <div class="mb-3 row">
              <label for="pm_no" class="col-md-4 col-form-label">PM No</label>
              <div class="col-md-8">
                <input type="text" id="pm_no" name="pm_no" class="form-control" value="{{ $pm_no }}" readonly>
                <span class="btn btn-danger dup_name d-none"></span>
              </div>
            </div>

            <div class="mb-3 row none">
              <label for="machine_id" class="col-md-4 col-form-label">Machine Name</label>
              <div class="col-md-8">
                <select name="machine_id" id="machine_id" class="form-select select2">
                  {!! $machine_id !!}
                </select>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="initiate_date" class="col-md-4 col-form-label"><span class="text-danger">*</span>Initiate PM
                Date</label>
              <div class="col-md-8">
                <input type="text" name="initiate_date" id="initiate_date" class="form-control initiate_date"
                  data-link-format="yyyy-mm-dd" value="{{ $initiate_date }}" required>
              </div>
            </div>

          </div>

          <div class="col-md-6">

            <div class="mb-3 row none">
              <label for="department_id" class="col-md-4 col-form-label">Department Name</label>
              <div class="col-md-8">
                <select name="department_id" id="department_id" class="form-select select2">
                  {!! $department_id !!}
                </select>
              </div>
            </div>

            <div class="mb-3 row none">
              <label for="actual_pm_date" class="col-md-4 col-form-label">Actual PM Date</label>
              <div class="col-md-8">
                <input type="text" name="actual_pm_date" id="actual_pm_date" class="form-control actual_pm_date"
                  data-link-format="yyyy-mm-dd" value="{{ $actual_pm_date }}" readonly>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="user_clearance_by" class="col-md-4 col-form-label"><span class="text-danger">*</span>User
                Department Clearance</label>
              <div class="col-md-8">
                <select name="user_clearance_by[]" id="user_clearance_by" class="form-select select2" required multiple>
                  {!! $user_clearance_by !!}
                </select>
              </div>
            </div>

          </div>
        </div>

        <div class="row mt-4">
          <div class="col text-center">
            <button type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
            <a href="{{ URL::to('initiatepm') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
          </div>
        </div>

    </div>
  </div>
  </form>



@endsection
@push('scripts')

  <script>
    $(document).ready(function () {

      $(document).on("focus", ".initiate_date", function () {

        $(this).datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: "yy-mm-dd",
          minDate: 0,
          showAnim: "slideDown",
          yearRange: "-25:+0",

        });
      });

      /*deepika purpose:to save function*/
      $('#savestatus').val('');
      $(document).on('click', '.saveform', function () {

        var btnval = $(this).val();
        if (btnval == 'APPLYCHANGES')
          var savestatus = 'APPLY CHANGES';
        else if (btnval == 'SAVE' || btnval == 'SAVENEW')
          var savestatus = 'SAVE';

        $('#savestatus').val(savestatus);
        var url = "{{ URL::to('initiatepmsave') }}";
        var red_url = "{{ URL::to('initiatepm') }}";
        var create_url = "{{ URL::to('initiatepmcreate') }}";
        //capacityrequiredvalid();
        validationrule('initiatepm');
        var formdata = $('#initiatepm').serialize();
        var form = $('#initiatepm');
        if (btnval != 'APPLYCHANGES') {
          form.parsley().validate();
          var form = $('#initiatepm');
          form.parsley().validate();
          if (form.parsley().isValid()) {
            var $btn = $(this);
            $btn.prop('disabled', true);
            $.post(url, formdata, function (data) {
              var status = data.status;
              var msg = data.message;

              if (btnval != 'SAVE' && btnval != 'DRAFT') {

                showCustomAlert(msg, status);
                setTimeout(function () {
                  window.location.href = create_url;
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
      /*end*/



    });
  </script>

@endpush