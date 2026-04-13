@extends('layouts.header')
@section('content')
  <?php if ($pageMethod == "pmmonthlycheckcreate") { ?>
  <h3 class="text-danger">PM Monthly Check</h3>
  <?php } else {?>
  <h3 class="text-danger">PM Clearance Approve </h3>
  <?php } ?>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form method="post" action="" id="pmclearance" data-parsley-validate>
        {{ csrf_field() }}
        <input type="hidden" name="savestatus" id="savestatus" />
        <input type="hidden" name="pm_checking_id" id="pm_checking_id" class="form-control pm_checking_id"
          value="{{$pm_checking_id}}" readonly>
        <input type="hidden" name="initiate_pm_id" id="initiate_pm_id" class="form-control initiate_pm_id"
          value="{{$initiate_pm_id}}" readonly>
        <input type="hidden" name="status" class="status">
        <input type="hidden" name="postpone_status" class="postpone_status">


        <div class="row g-4">
          <!-- Left Column -->
          <div class="col-md-6">
            <div class="mb-3 row none">
              <label class="col-md-4 col-form-label">PM No</label>
              <div class="col-md-8">
                <input type="text" id="pm_no" name="pm_no" class="form-control pm_no" value="{{$pm_no}}">
                <span class="btn btn-danger dup_name d-none"></span>
              </div>
            </div>

            <div class="mb-3 row none">
              <label class="col-md-4 col-form-label">Machine Name</label>
              <div class="col-md-8">
                <select name="machine_id" class="form-select select2 machine_id" id="machine_id"
                  style="pointer-events: none; background-color: #e9ecef;">
                  {!! $machine_id !!}
                </select>
              </div>
            </div>

            <div class="mb-3 row none">
              <label class="col-md-4 col-form-label">Initiated Date</label>
              <div class="col-md-8">
                <input type="text" name="actual_pm_date" id="actual_pm_date"
                  class="form-control actual_pm_date datepicker" value="{{$initiate_date}}" readonly>
              </div>
            </div>

            <div class="mb-3 row none">
              <label class="col-md-4 col-form-label text-danger">* Change Initiated Date</label>
              <div class="col-md-8">
                <select name="change_date" class="form-select select2 change_date file" required>
                  <option value="0">Please Select</option>
                  <option value="1" @if($change_date == 1) selected @endif>Yes</option>
                  <option value="2" @if($change_date == 2) selected @endif>No</option>
                </select>
              </div>
            </div>

            <div class="mb-3 row clearance_date">
              <label class="col-md-4 col-form-label text-danger">* Postpone To</label>
              <div class="col-md-8">
                <input type="text" name="postponed_date" id="postponed_date"
                  class="form-control postponed_date datepicker" value="{{$postponed_date}}">
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-md-4 col-form-label text-danger">* PM Start Date</label>
              <div class="col-md-8">
                <input type="text" class="form-control start_date_time1" id="pm_start_date_time" name="pm_start_date_time"
                  required value="{{$pm_sdate}}" readonly>
              </div>
            </div>

            <div class="mb-3 row shift_timings none">
              <label class="col-md-4 col-form-label text-danger">* Shift Timing</label>
              <div class="col-md-8">
                <input type="text" name="shift_timing" id="shift_timing" class="form-control shift_timing"
                  value="{{$shift_timing}}" readonly>
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

            <div class="mb-3 row none">
              <label class="col-md-4 col-form-label text-danger">* Allocation Type</label>
              <div class="col-md-8">
                <select name="allocation_type" class="form-select allocation_type select2" required>
                  <option>--Please Select--</option>
                  <option value="agency" @if($allocation_type == "agency") selected @endif>Agency</option>
                  <option value="engineer" @if($allocation_type == "engineer") selected @endif>Engineer</option>
                </select>
              </div>
            </div>

            <div class="mb-3 row none">
              <label class="col-md-4 col-form-label text-danger">*
                @if($allocation_type == "agency") Agency Allocation @else Engineer Allocation @endif
              </label>
              <div class="col-md-8">
                <select name="agency_allocation" class="form-select agency_allocation select2" required>
                  {!! $agency_allocation !!}
                </select>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-md-4 col-form-label text-danger">* PM End Date</label>
              <div class="col-md-8">
                <input type="text" class="form-control end_date_time1" id="pm_end_date_time" name="pm_end_date_time"
                  required value="{{$pm_edate}}" readonly>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 mt-4">
          @if(count($check_details) > 0)
            <table class="table table-bordered">
              <thead>
                <tr class="table-warning">
                  <th>Activity Description</th>
                  <th>Standard Condition & Counter Measure</th>
                  <th>Checklist Image</th>
                  <th>Observation</th>
                  <th>Status</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody>
                @if($pagemode == 'create')
                  @foreach($check_details as $value)
                    <tr>
                      <td>
                        {{ $value->checklist_name }}
                        <input type="hidden" name="checklist[]" value="{{ $value->checklist_id }}">
                      </td>
                      <td>{{ $value->terms }}</td>
                      <td>
                        @if($value->file != "")
                          <img src="{{ asset('Uploads/checklist/' . $value->file) }}" height="50">
                        @else
                          <img src="{{ asset('upload/machineupload/noimg.png') }}" height="50">
                        @endif
                      </td>
                      <td><input type="text" name="observation[]" class="form-control observation"></td>
                      <td><input type="text" name="status[]" class="form-control status"></td>
                      <td><input type="text" name="remarks[]" class="form-control remarks"></td>
                    </tr>
                  @endforeach
                @else
                  @foreach($check_details as $value)
                    <tr>
                      <td>
                        {{ $value->checklist_name }}
                        <input type="hidden" name="checklist[]" value="{{ $value->checklist_id }}">
                      </td>
                      <td>{{ $value->terms }}</td>
                      <td>
                        <img
                          src="{{ $value->file ? asset('Uploads/checklist/' . $value->file) : asset('upload/machineupload/noimg.png') }}"
                          height="50">
                      </td>
                      <td><input type="text" name="observation[]" class="form-control observation"
                          value="{{ $value->observation }}"></td>
                      <td><input type="text" name="status[]" class="form-control status" value="{{ $value->status }}"></td>
                      <td><input type="text" name="remarks[]" class="form-control remarks" value="{{ $value->remarks }}"></td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          @else
            <div class="text-center text-danger mt-3">
              NO CHECKLIST FOR THIS MACHINE. YOU CAN'T SAVE.
            </div>
          @endif
        </div>


        <!-- Form Actions -->
        <div class="row mt-4">
          <div class="col text-center">
            @if($pageMethod == "pmmonthlycheckcreate")
              <button type="submit" class="btn btn-success save_btn saveform px-4 me-2" value="SAVE">Save</button>
              <a href="{{ URL::to('pmmonthlycheck') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
            @else
              <button type="submit" class="btn btn-success save_btn saveform px-4 me-2" value="APPROVED">Approve</button>
              <a href="{{ URL::to('pmmonthlycheckapproval') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
            @endif
          </div>
        </div>
      </form>
    </div>
  </div>




@endsection
@push('scripts')

  <script>

    $(document).ready(function () {
      <?php if (count($check_details) == 0) { ?>
      $('.save_btn').attr('disabled', true);
      <?php } ?>

      var groupname = "{{\Session::get('groupname')}}";
      if (groupname == "12") {
        $(".remarks").removeAttr('required', false);
        $('.remarks').css("pointer-events", "none");
        $(".observation").attr('required', 'required');
        $(".status").attr('required', 'required');
      } else if (groupname == "10") {
        $(".observation").removeAttr('required', false);
        $(".status").removeAttr('required', false);
        $('.observation').css("pointer-events", "none");
        $('.status').css("pointer-events", "none");
        $(".remarks").attr('required', 'required');
      }


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
        if (btnval == 'APPROVED')
          var savestatus = 'APPROVED';
        else if (btnval == 'SAVE')
          var savestatus = 'SAVE';

        $('#savestatus').val(savestatus);
        var url = "{{ URL::to('pmmonthlychecksave') }}";
        var red_url = "{{ URL::to('pmmonthlycheck') }}";
        var create_url		="{{ URL::to('pmmonthlycheckapproval') }}";
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
              var msg = data.message;
              var id = data.id;
              if (btnval == 'SAVE' && btnval == 'DRAFT') {

                showCustomAlert(msg,status);
                setTimeout(function () {
                  window.location.href = red_url;
                }, 1500);
              }else {
                showCustomAlert(msg,status);
                setTimeout(function () {
                  window.location.href = create_url;
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